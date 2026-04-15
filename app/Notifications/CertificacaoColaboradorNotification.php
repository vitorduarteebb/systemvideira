<?php

namespace App\Notifications;

use App\Models\ColaboradorDocumento;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CertificacaoColaboradorNotification extends Notification
{
    use Queueable;

    public function __construct(
        public ColaboradorDocumento $documento,
        public string $tipo /** proximo | vencido */
    ) {}

    public function via(object $notifiable): array
    {
        $channels = ['database'];
        if (filter_var(config('colaboradores.notificar_email_certificacao'), FILTER_VALIDATE_BOOLEAN)) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $colab = $this->documento->colaborador;
        $nome = $colab?->nome_profissional ?? 'Colaborador';
        $doc = $this->documento->nome_documento ?? 'Documento';
        $v = $this->documento->data_vencimento?->format('d/m/Y') ?? '—';

        if ($this->tipo === 'vencido') {
            $subject = "[VIDEIRA] Certificação vencida: {$nome} — {$doc}";
            $line = "A certificação/documento **{$doc}** de **{$nome}** está **vencida** (validade {$v}). Atualize na área de documentos do colaborador.";
        } else {
            $subject = "[VIDEIRA] Certificação a vencer: {$nome} — {$doc}";
            $line = "A certificação/documento **{$doc}** de **{$nome}** está **próxima do vencimento** (validade {$v}).";
        }

        return (new MailMessage)
            ->subject($subject)
            ->line($line)
            ->action('Abrir documentos do colaborador', route('crm.colaboradores.details', $this->documento->colaborador_id));
    }

    public function toArray(object $notifiable): array
    {
        $colab = $this->documento->colaborador;
        $nome = $colab?->nome_profissional ?? 'Colaborador';
        $doc = $this->documento->nome_documento ?? 'Documento';
        $v = $this->documento->data_vencimento?->format('d/m/Y') ?? '—';

        if ($this->tipo === 'vencido') {
            $title = 'Certificação vencida';
            $body = "{$nome}: {$doc} (vencimento {$v})";
        } else {
            $title = 'Certificação a vencer';
            $body = "{$nome}: {$doc} (validade {$v})";
        }

        return [
            'title' => $title,
            'body' => $body,
            'tipo' => $this->tipo,
            'documento_id' => $this->documento->id,
            'colaborador_id' => $this->documento->colaborador_id,
            'url' => route('crm.colaboradores.details', $this->documento->colaborador_id),
        ];
    }
}
