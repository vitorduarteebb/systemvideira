<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class Servico extends Model
{
    use HasFactory;

    protected $table = 'servicos';

    protected $fillable = [
        'codigo_ve',
        'numero_os',
        'tipo_tarefa',
        'orientacao',
        'descricao',
        'cliente_id',
        'equipamento_id',
        'faturamento_estimado',
        'data_inicio',
        'horario_agendamento',
        'horario_chegada',
        'horario_saida',
        'horario_inicio_execucao',
        'horario_fim_execucao',
        'inicio_deslocamento',
        'duracao_deslocamento_minutos',
        'status_operacional',
        'duracao_dias',
        'observacoes',
        'relato_execucao',
        'checklist_pmoc',
        'assinatura_usuario_id',
        'assinatura_base64',
        'fotos',
    ];

    protected $casts = [
        'faturamento_estimado' => 'decimal:2',
        'data_inicio' => 'date',
        'duracao_dias' => 'integer',
        'horario_agendamento' => 'datetime',
        'horario_chegada' => 'datetime',
        'horario_saida' => 'datetime',
        'horario_inicio_execucao' => 'datetime',
        'horario_fim_execucao' => 'datetime',
        'checklist_pmoc' => 'array',
        'fotos' => 'array',
    ];

    public function assinaturaUsuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assinatura_usuario_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /** Nome do técnico que assinou o relatório; senão, colaboradores escalados no serviço. */
    public function nomeTecnicoResponsavelRelatorio(): string
    {
        if ($this->assinatura_usuario_id) {
            $user = $this->relationLoaded('assinaturaUsuario')
                ? $this->assinaturaUsuario
                : $this->assinaturaUsuario()->first();
            if ($user) {
                $colabConta = $user->relationLoaded('colaboradorConta')
                    ? $user->colaboradorConta
                    : $user->colaboradorConta()->first();
                if ($colabConta && trim((string) $colabConta->nome_profissional) !== '') {
                    return $colabConta->nome_profissional;
                }
                $colab = Colaborador::resolveFromUser($user);
                if ($colab && trim((string) $colab->nome_profissional) !== '') {
                    return $colab->nome_profissional;
                }
                if (trim((string) $user->name) !== '') {
                    return $user->name;
                }
            }
        }

        $this->loadMissing('tecnicos');
        $nomes = $this->tecnicos->pluck('nome_profissional')->filter()->values();
        if ($nomes->isNotEmpty()) {
            return $nomes->join(', ');
        }

        return '-';
    }

    /** Data/hora exibida como “agendamento”: campo oficial ou 1º dia de trabalho ou data de início. */
    public function dataHoraAgendamentoRelatorio(): ?Carbon
    {
        if ($this->horario_agendamento) {
            return $this->horario_agendamento instanceof Carbon
                ? $this->horario_agendamento
                : Carbon::parse($this->horario_agendamento);
        }

        $this->loadMissing('diasTrabalho');
        $primeiro = $this->diasTrabalho->sortBy(function ($d) {
            return ($d->data ? $d->data->format('Y-m-d') : '') . ' ' . ($d->hora_inicio ?? '');
        })->first();

        if ($primeiro && $primeiro->data) {
            $timePart = trim((string) ($primeiro->hora_inicio ?? ''));
            if ($timePart === '') {
                $timePart = '08:00';
            }

            return Carbon::parse($primeiro->data->format('Y-m-d') . ' ' . $timePart);
        }

        if ($this->data_inicio) {
            return Carbon::parse($this->data_inicio->format('Y-m-d') . ' 08:00:00');
        }

        return null;
    }

    public function textoTipoTarefaRelatorio(): string
    {
        $t = trim((string) $this->tipo_tarefa);
        if ($t !== '') {
            return $t;
        }
        $d = trim((string) $this->descricao);
        if ($d === '') {
            return '-';
        }

        return mb_strlen($d) > 100 ? mb_substr($d, 0, 97) . '...' : $d;
    }

    public function textoOrientacaoRelatorio(): string
    {
        $o = trim((string) $this->orientacao);
        if ($o !== '') {
            return $o;
        }
        $check = $this->checklist_pmoc;
        if (is_array($check)) {
            foreach (['obs', 'observacoes', 'orientacao'] as $key) {
                $v = $check[$key] ?? null;
                if (is_string($v) && trim($v) !== '') {
                    return trim($v);
                }
            }
        }
        $obs = trim((string) ($this->observacoes ?? ''));

        return $obs !== '' ? $obs : '-';
    }

    /** Texto para textarea no atendimento (sem placeholder “-”). */
    public function conteudoOrientacaoParaFormulario(): string
    {
        $o = trim((string) $this->orientacao);
        if ($o !== '') {
            return $this->orientacao;
        }
        $check = $this->checklist_pmoc;
        if (is_array($check)) {
            foreach (['obs', 'observacoes', 'orientacao'] as $key) {
                $v = $check[$key] ?? null;
                if (is_string($v) && trim($v) !== '') {
                    return trim($v);
                }
            }
        }
        $obs = trim((string) ($this->observacoes ?? ''));

        return $obs !== '' ? $this->observacoes : '';
    }

    /** Valor sugerido para “tipo da tarefa” quando o campo oficial está vazio. */
    public function conteudoTipoTarefaParaFormulario(): string
    {
        $t = trim((string) $this->tipo_tarefa);
        if ($t !== '') {
            return $this->tipo_tarefa;
        }

        return trim((string) $this->descricao);
    }

    /** Valor `datetime-local` para agendamento (campo ou 1º dia / data início). */
    public function horarioAgendamentoInputValue(): string
    {
        if ($this->horario_agendamento) {
            return $this->horario_agendamento->format('Y-m-d\TH:i');
        }
        $c = $this->dataHoraAgendamentoRelatorio();

        return $c ? $c->format('Y-m-d\TH:i') : '';
    }

    public function horarioInicioExecucaoInputValue(): string
    {
        if ($this->horario_inicio_execucao) {
            return $this->horario_inicio_execucao->format('Y-m-d\TH:i');
        }
        if ($this->horario_chegada) {
            return $this->horario_chegada->format('Y-m-d\TH:i');
        }

        return '';
    }

    public function horarioFimExecucaoInputValue(): string
    {
        if ($this->horario_fim_execucao) {
            return $this->horario_fim_execucao->format('Y-m-d\TH:i');
        }
        if ($this->horario_saida) {
            return $this->horario_saida->format('Y-m-d\TH:i');
        }

        return '';
    }

    /**
     * Minutos de trabalho efetivo por colaborador, somando intervalos após check-in/retorno/ajuste
     * até o próximo evento, exceto períodos após pausa (até retorno) e após check-out.
     */
    public function minutosTrabalhoLiquidosPorColaborador(): Collection
    {
        $this->loadMissing('horas');
        $grouped = $this->horas->groupBy(static fn ($h) => $h->colaborador_id ?? '__sem_colab__');

        return $grouped->map(static function (Collection $registros) {
            $registros = $registros->sortBy('horario')->values();
            $total = 0;
            $prev = null;
            foreach ($registros as $row) {
                if ($prev === null) {
                    $prev = $row;
                    continue;
                }
                $t = (int) ($row->tempo_corrido_minutos ?? 0);
                if ($t > 0) {
                    if ($prev->monitoramento === 'pausa') {
                        // intervalo entre pausa e retorno = descanso
                    } elseif ($prev->monitoramento === 'check_out') {
                        // entre encerramentos / nova sessão
                    } else {
                        $total += $t;
                    }
                }
                $prev = $row;
            }

            return $total;
        });
    }

    public function minutosTrabalhoLiquidosTotal(): int
    {
        return (int) $this->minutosTrabalhoLiquidosPorColaborador()->sum();
    }

    public static function formatarDuracaoMinutos(int $minutos): string
    {
        if ($minutos <= 0) {
            return '0 min';
        }
        $h = intdiv($minutos, 60);
        $m = $minutos % 60;
        if ($h > 0) {
            return sprintf('%dh %dmin', $h, $m);
        }

        return sprintf('%d min', $m);
    }

    public function equipamento()
    {
        return $this->belongsTo(Equipamento::class);
    }

    public function tecnicos()
    {
        return $this->belongsToMany(Colaborador::class, 'servico_tecnicos', 'servico_id', 'colaborador_id');
    }

    public function diasTrabalho()
    {
        return $this->hasMany(ServicoDiaTrabalho::class, 'servico_id');
    }

    public function anexos()
    {
        return $this->hasMany(ServicoAnexo::class, 'servico_id');
    }

    public function horas()
    {
        return $this->hasMany(ServicoHoraRegistro::class, 'servico_id')->orderBy('horario');
    }

    public function questionariosVinculados()
    {
        return $this->hasMany(ServicoQuestionario::class, 'servico_id');
    }

    public function getStatusOperacionalLabelAttribute()
    {
        $status = [
            'pendente' => 'Pendente',
            'em_andamento' => 'Em Andamento',
            'pausado' => 'Pausado',
            'concluido' => 'Concluído',
            'cancelado' => 'Cancelado',
        ];

        return $status[$this->status_operacional] ?? $this->status_operacional;
    }

    /** Status efetivo para exibição: Pendência se houver questionário com respostas obrigatórias faltando */
    public function getStatusEfetivoAttribute(): string
    {
        if (in_array($this->status_operacional, ['concluido', 'cancelado'])) {
            return $this->status_operacional;
        }
        return $this->hasQuestionarioPendente() ? 'pendencia' : $this->status_operacional;
    }

    /** Verifica se há questionário vinculado com perguntas obrigatórias não respondidas */
    public function hasQuestionarioPendente(): bool
    {
        foreach ($this->questionariosVinculados as $vinculo) {
            $respostas = $vinculo->respostas ?? [];
            foreach ($vinculo->questionario->perguntas ?? [] as $pergunta) {
                if ($pergunta->resposta_obrigatoria) {
                    $valor = $respostas[$pergunta->id] ?? $respostas[(string) $pergunta->id] ?? '';
                    if ($valor === '' || $valor === null) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
}
