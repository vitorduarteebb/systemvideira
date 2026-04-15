<?php

namespace App\Console\Commands;

use App\Models\ColaboradorDocumento;
use App\Models\User;
use App\Notifications\CertificacaoColaboradorNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class ColaboradoresAlertarCertificacoesCommand extends Command
{
    protected $signature = 'colaboradores:alertar-certificacoes';

    protected $description = 'Notifica sobre certificações/documentos de colaboradores a vencer ou vencidos';

    public function handle(): int
    {
        $dias = (int) config('colaboradores.certificacao_dias_alerta', 30);
        $intervalo = (int) config('colaboradores.certificacao_intervalo_alerta_dias', 7);
        $now = Carbon::now();

        $users = User::query()->whereNotNull('email')->get();
        if ($users->isEmpty()) {
            $this->warn('Nenhum usuário com e-mail para notificar.');

            return self::SUCCESS;
        }

        $documentos = ColaboradorDocumento::query()
            ->whereNotNull('data_vencimento')
            ->with('colaborador:id,nome_profissional')
            ->get();

        $n = 0;
        foreach ($documentos as $doc) {
            $estado = $doc->estadoCertificacao($dias);

            if ($estado === 'vencido') {
                if ($this->deveEnviar($doc->cert_vencido_alerta_em, $now, $intervalo)) {
                    Notification::send($users, new CertificacaoColaboradorNotification($doc, 'vencido'));
                    $doc->forceFill(['cert_vencido_alerta_em' => $now])->save();
                    $n++;
                }
                continue;
            }

            if ($estado === 'expirando') {
                if ($this->deveEnviar($doc->cert_proximo_alerta_em, $now, $intervalo)) {
                    Notification::send($users, new CertificacaoColaboradorNotification($doc, 'proximo'));
                    $doc->forceFill(['cert_proximo_alerta_em' => $now])->save();
                    $n++;
                }
            }
        }

        $this->info("Alertas processados: {$n} envio(s).");

        return self::SUCCESS;
    }

    private function deveEnviar(?Carbon $ultimo, Carbon $now, int $intervalo): bool
    {
        if ($ultimo === null) {
            return true;
        }

        return $ultimo->diffInDays($now) >= $intervalo;
    }
}
