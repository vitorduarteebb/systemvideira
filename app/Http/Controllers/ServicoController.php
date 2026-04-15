<?php

namespace App\Http\Controllers;

use App\Models\Servico;
use App\Models\Cliente;
use App\Models\Equipamento;
use App\Models\Colaborador;
use App\Models\ServicoDiaTrabalho;
use App\Models\ServicoAnexo;
use App\Models\ServicoHoraRegistro;
use App\Models\Questionario;
use App\Models\ServicoQuestionario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ServicoController extends Controller
{
    protected function ensureTecnicoPodeServico(Servico $servico): void
    {
        $u = auth()->user();
        if (! $u || ! $u->isTecnico()) {
            return;
        }
        $c = Colaborador::resolveFromUser($u);
        abort_unless($c && $servico->tecnicos->contains('id', $c->id), 403, 'Acesso negado a este serviço.');
    }

    public function index(Request $request)
    {
        $query = Servico::with(['cliente', 'equipamento', 'tecnicos']);

        // Busca
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('codigo_ve', 'like', "%{$search}%")
                  ->orWhere('descricao', 'like', "%{$search}%")
                  ->orWhereHas('cliente', function($q) use ($search) {
                      $q->where('nome', 'like', "%{$search}%");
                  });
            });
        }

        $servicos = $query->orderBy('data_inicio', 'desc')->paginate(20);
        $clientes = Cliente::orderBy('nome')->get();

        return view('crm.servicos', compact('servicos', 'clientes'));
    }

    public function getEquipamentos(Request $request)
    {
        try {
            $clienteId = $request->get('cliente_id');
            
            if (!$clienteId) {
                return response()->json([], 200);
            }

            $equipamentos = Equipamento::where('cliente_id', $clienteId)
                ->where('ativo', true)
                ->orderBy('nome')
                ->get();

            return response()->json($equipamentos->toArray(), 200);
        } catch (\Exception $e) {
            return response()->json([], 200);
        }
    }

    public function getTecnicos(Request $request)
    {
        try {
            $search = $request->get('search', '');
            
            $query = Colaborador::where('ativo', true)
                ->where(function($q) use ($search) {
                    if ($search) {
                        $q->where('nome_profissional', 'like', "%{$search}%")
                          ->orWhere('cpf', 'like', "%{$search}%");
                    }
                })
                ->orderBy('nome_profissional')
                ->limit(20);

            $tecnicos = $query->get(['id', 'nome_profissional', 'departamento', 'cpf']);

            return response()->json($tecnicos->toArray(), 200);
        } catch (\Exception $e) {
            return response()->json([], 200);
        }
    }

    public function show(Servico $servico)
    {
        $servico->load(['cliente', 'equipamento', 'tecnicos', 'diasTrabalho']);
        
        return response()->json([
            'success' => true,
            'servico' => $servico
        ]);
    }

    public function store(Request $request)
    {
        // Processar técnicos se vierem como string JSON antes da validação
        $requestData = $request->all();
        if ($request->has('tecnicos') && is_string($request->input('tecnicos'))) {
            $tecnicosJson = json_decode($request->input('tecnicos'), true);
            if (is_array($tecnicosJson)) {
                $requestData['tecnicos'] = $tecnicosJson;
            }
        }
        
        $validator = Validator::make($requestData, [
            'codigo_ve' => 'nullable|string|max:255',
            'descricao' => 'nullable|string',
            'cliente_id' => 'nullable|exists:clientes,id',
            'equipamento_id' => 'nullable|exists:equipamentos,id',
            'faturamento_estimado' => 'nullable|numeric|min:0',
            'data_inicio' => 'required|date',
            'status_operacional' => 'required|in:pendente,em_andamento,pausado,concluido,cancelado',
            'duracao_dias' => 'required|integer|min:1',
            'tecnicos' => 'nullable|array',
            'tecnicos.*' => 'exists:colaboradores,id',
            'dias_trabalho' => 'nullable|array',
            'dias_trabalho.*.data' => 'required|date',
            'dias_trabalho.*.hora_inicio' => 'required',
            'dias_trabalho.*.hora_fim' => 'required',
            'dias_trabalho.*.intervalo_minutos' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            throw new ValidationException($validator);
        }

        DB::beginTransaction();
        try {
            $data = $validator->validated();
            
            // Normalizar campos vazios para null
            $data['codigo_ve'] = !empty($data['codigo_ve']) ? $data['codigo_ve'] : null;
            $data['descricao'] = !empty($data['descricao']) ? $data['descricao'] : null;
            $data['cliente_id'] = !empty($data['cliente_id']) ? $data['cliente_id'] : null;
            $data['equipamento_id'] = !empty($data['equipamento_id']) ? $data['equipamento_id'] : null;
            $data['faturamento_estimado'] = $data['faturamento_estimado'] ?? 0;

            // Processar técnicos se vierem como string JSON
            $tecnicosIds = [];
            if ($request->has('tecnicos')) {
                $tecnicosInput = $request->input('tecnicos');
                if (is_string($tecnicosInput) && !empty($tecnicosInput)) {
                    $tecnicosIds = json_decode($tecnicosInput, true) ?? [];
                } elseif (is_array($tecnicosInput)) {
                    $tecnicosIds = $tecnicosInput;
                }
            }

            $servico = Servico::create($data);

            // Gerar número O.S. automaticamente se não informado
            if (empty($servico->numero_os)) {
                $ano = date('Y');
                $seq = Servico::whereYear('created_at', $ano)->count();
                $servico->numero_os = 'OS-' . $ano . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);
                $servico->save();
            }

            // Associar técnicos
            if (!empty($tecnicosIds) && is_array($tecnicosIds)) {
                $servico->tecnicos()->sync($tecnicosIds);
            }

            // Criar dias de trabalho
            if (isset($data['dias_trabalho']) && is_array($data['dias_trabalho'])) {
                foreach ($data['dias_trabalho'] as $index => $dia) {
                    ServicoDiaTrabalho::create([
                        'servico_id' => $servico->id,
                        'dia_numero' => $index + 1,
                        'data' => $dia['data'],
                        'hora_inicio' => $dia['hora_inicio'],
                        'hora_fim' => $dia['hora_fim'],
                        'intervalo_minutos' => $dia['intervalo_minutos'] ?? 60,
                        'escalavel' => true,
                    ]);
                }
            } else {
                // Criar dias automaticamente baseado na duração
                // Padrão: gerar somente dias úteis (seg-sex), mas o usuário pode editar no front.
                $cursor = Carbon::parse($data['data_inicio'])->startOfDay();
                $diaNumero = 1;
                while ($diaNumero <= (int) $data['duracao_dias']) {
                    if (! $cursor->isWeekend()) {
                        $dataTrabalho = $cursor->copy();
                    ServicoDiaTrabalho::create([
                        'servico_id' => $servico->id,
                            'dia_numero' => $diaNumero,
                            'data' => $dataTrabalho->toDateString(),
                        'hora_inicio' => '08:00',
                        'hora_fim' => '17:00',
                        'intervalo_minutos' => 60,
                        'escalavel' => true,
                    ]);
                        $diaNumero++;
                }
                    $cursor->addDay();
                }
            }

            DB::commit();

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'servico' => $servico->load(['cliente', 'equipamento', 'tecnicos', 'diasTrabalho']),
                    'message' => 'Serviço criado com sucesso!'
                ]);
            }

            return redirect()->route('crm.servicos.index')->with('success', 'Serviço criado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Erro ao criar serviço: ' . $e->getMessage()], 500);
            }
            
            throw $e;
        }
    }

    public function update(Request $request, Servico $servico)
    {
        // Processar técnicos se vierem como string JSON antes da validação
        $requestData = $request->all();
        if ($request->has('tecnicos') && is_string($request->input('tecnicos'))) {
            $tecnicosJson = json_decode($request->input('tecnicos'), true);
            if (is_array($tecnicosJson)) {
                $requestData['tecnicos'] = $tecnicosJson;
            }
        }
        
        $validator = Validator::make($requestData, [
            'codigo_ve' => 'nullable|string|max:255',
            'descricao' => 'nullable|string',
            'cliente_id' => 'nullable|exists:clientes,id',
            'equipamento_id' => 'nullable|exists:equipamentos,id',
            'faturamento_estimado' => 'nullable|numeric|min:0',
            'data_inicio' => 'required|date',
            'status_operacional' => 'required|in:pendente,em_andamento,pausado,concluido,cancelado',
            'duracao_dias' => 'required|integer|min:1',
            'tecnicos' => 'nullable|array',
            'tecnicos.*' => 'exists:colaboradores,id',
            'dias_trabalho' => 'nullable|array',
            'dias_trabalho.*.data' => 'required|date',
            'dias_trabalho.*.hora_inicio' => 'required',
            'dias_trabalho.*.hora_fim' => 'required',
            'dias_trabalho.*.intervalo_minutos' => 'nullable|integer|min:0',
            'dias_trabalho.*.escalavel' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            throw new ValidationException($validator);
        }

        DB::beginTransaction();
        try {
            $data = $validator->validated();
            
            // Normalizar campos vazios para null
            $data['codigo_ve'] = !empty($data['codigo_ve']) ? $data['codigo_ve'] : null;
            $data['descricao'] = !empty($data['descricao']) ? $data['descricao'] : null;
            $data['cliente_id'] = !empty($data['cliente_id']) ? $data['cliente_id'] : null;
            $data['equipamento_id'] = !empty($data['equipamento_id']) ? $data['equipamento_id'] : null;
            
            // Processar técnicos se vierem como string JSON
            $tecnicosIds = [];
            if ($request->has('tecnicos')) {
                $tecnicosInput = $request->input('tecnicos');
                if (is_string($tecnicosInput) && !empty($tecnicosInput)) {
                    $tecnicosIds = json_decode($tecnicosInput, true) ?? [];
                } elseif (is_array($tecnicosInput)) {
                    $tecnicosIds = $tecnicosInput;
                }
            }
            
            $servico->update($data);

            // Sincronizar equipe apenas quando o front envia o campo (evita apagar técnicos ao salvar sem abrir a aba)
            if ($request->has('tecnicos')) {
                if (! is_array($tecnicosIds)) {
                    $tecnicosIds = [];
                }
                $servico->tecnicos()->sync($tecnicosIds);
            }

            // Atualizar dias de trabalho - recriar baseado na duração se não fornecidos
            if (isset($data['dias_trabalho']) && is_array($data['dias_trabalho']) && count($data['dias_trabalho']) > 0) {
                $servico->diasTrabalho()->delete();
                foreach ($data['dias_trabalho'] as $index => $dia) {
                    ServicoDiaTrabalho::create([
                        'servico_id' => $servico->id,
                        'dia_numero' => $index + 1,
                        'data' => $dia['data'],
                        'hora_inicio' => $dia['hora_inicio'],
                        'hora_fim' => $dia['hora_fim'],
                        'intervalo_minutos' => $dia['intervalo_minutos'] ?? 60,
                        'escalavel' => $dia['escalavel'] ?? true,
                    ]);
                }
            } else {
                // Recriar dias automaticamente baseado na duração
                $servico->diasTrabalho()->delete();
                $cursor = Carbon::parse($data['data_inicio'])->startOfDay();
                $diaNumero = 1;
                while ($diaNumero <= (int) $data['duracao_dias']) {
                    if (! $cursor->isWeekend()) {
                        $dataTrabalho = $cursor->copy();
                    ServicoDiaTrabalho::create([
                        'servico_id' => $servico->id,
                            'dia_numero' => $diaNumero,
                            'data' => $dataTrabalho->toDateString(),
                        'hora_inicio' => '08:00',
                        'hora_fim' => '17:00',
                        'intervalo_minutos' => 60,
                        'escalavel' => true,
                    ]);
                        $diaNumero++;
                }
                    $cursor->addDay();
                }
            }

            DB::commit();

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Serviço atualizado com sucesso!'
                ]);
            }

            return redirect()->route('crm.servicos.index')->with('success', 'Serviço atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Erro ao atualizar serviço: ' . $e->getMessage()], 500);
            }
            
            throw $e;
        }
    }

    public function destroy(Request $request, Servico $servico)
    {
        $servico->delete();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Serviço excluído com sucesso!']);
        }

        return redirect()->route('crm.servicos.index')->with('success', 'Serviço excluído com sucesso!');
    }

    public function relatorio(Servico $servico)
    {
        $servico->load([
            'cliente',
            'equipamento',
            'tecnicos',
            'diasTrabalho',
            'assinaturaUsuario.colaboradorConta',
            'questionariosVinculados.questionario.perguntas',
            'questionariosVinculados.usuario',
        ]);
        
        // Dados da empresa (pode ser movido para config ou banco)
        $empresa = [
            'nome' => 'Videira Engenharia',
            'telefone' => '(13) 9917-24693',
            'cnpj' => '47.465.361/0001-77',
            'email' => 'videira@videiraengenharia.com.br',
            'endereco' => 'Rua Silva Jardim, 166 sala 907',
        ];
        
        return view('crm.servico-relatorio', compact('servico', 'empresa'));
    }

    public function relatorioPdf(Servico $servico)
    {
        $servico->load([
            'cliente',
            'equipamento',
            'tecnicos',
            'diasTrabalho',
            'assinaturaUsuario.colaboradorConta',
            'questionariosVinculados.questionario.perguntas',
            'questionariosVinculados.usuario',
        ]);
        
        // Dados da empresa
        $empresa = [
            'nome' => 'Videira Engenharia',
            'telefone' => '(13) 9917-24693',
            'cnpj' => '47.465.361/0001-77',
            'email' => 'videira@videiraengenharia.com.br',
            'endereco' => 'Rua Silva Jardim, 166 sala 907',
        ];
        
        // Verificar se DomPDF está disponível
        if (class_exists('\Barryvdh\DomPDF\Facade\Pdf')) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('crm.servico-relatorio', compact('servico', 'empresa'));
            return $pdf->download('relatorio-servico-' . ($servico->numero_os ?? $servico->id) . '.pdf');
        }
        
        // Fallback: retornar HTML para impressão
        return view('crm.servico-relatorio', compact('servico', 'empresa'));
    }

    public function preencherRelatorio(Servico $servico)
    {
        $servico->load(['cliente', 'equipamento', 'tecnicos']);
        return view('crm.servico-preencher-relatorio', compact('servico'));
    }

    public function relatorioAtendimento(Servico $servico)
    {
        $servico->load([
            'cliente',
            'equipamento',
            'tecnicos',
            'diasTrabalho',
            'anexos.usuario',
            'horas.colaborador',
            'horas.usuario',
            'questionariosVinculados.questionario.perguntas',
            'questionariosVinculados.usuario',
        ]);

        $questionariosDisponiveis = Questionario::withCount('perguntas')->orderBy('titulo')->get();
        $colaboradores = Colaborador::where('ativo', true)->orderBy('nome_profissional')->get();
        $equipamentosCliente = Equipamento::query()
            ->when($servico->cliente_id, function ($query) use ($servico) {
                $query->where('cliente_id', $servico->cliente_id);
            })
            ->where('ativo', true)
            ->orderBy('nome')
            ->get();

        if ($servico->equipamento_id && $servico->equipamento && ! $equipamentosCliente->contains('id', $servico->equipamento_id)) {
            $equipamentosCliente = $equipamentosCliente->prepend($servico->equipamento)->values();
        }

        return view('crm.relatorio-atendimento', compact('servico', 'questionariosDisponiveis', 'colaboradores', 'equipamentosCliente'));
    }

    public function salvarRelatorio(Request $request, Servico $servico)
    {
        $validator = Validator::make($request->all(), [
            'horario_chegada' => 'nullable|date',
            'horario_saida' => 'nullable|date',
            'horario_inicio_execucao' => 'nullable|date',
            'horario_fim_execucao' => 'nullable|date',
            'inicio_deslocamento' => 'nullable|date_format:H:i',
            'duracao_deslocamento_minutos' => 'nullable|integer|min:0',
            'relato_execucao' => 'nullable|string|max:5000',
            'fotos.*' => 'nullable|image|max:5120',
            'checklist_pmoc' => 'nullable|array',
            'assinatura_base64' => 'nullable|string',
            'status_operacional' => 'nullable|in:pendente,em_andamento,pausado,concluido,cancelado',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $data = $validator->validated();

            // Processar fotos
            $fotosPaths = [];
            if ($request->hasFile('fotos')) {
                foreach ($request->file('fotos') as $foto) {
                    if ($foto->isValid()) {
                        $path = $foto->store('servicos/fotos', 'public');
                        $fotosPaths[] = $path;
                    }
                }
            }

            // Se já existem fotos, manter as antigas e adicionar novas
            $fotosExistentes = $servico->fotos ?? [];
            if (!empty($fotosPaths)) {
                $fotosExistentes = array_merge($fotosExistentes, $fotosPaths);
            }
            $data['fotos'] = !empty($fotosExistentes) ? $fotosExistentes : null;

            // Processar assinatura
            if ($request->has('assinatura_base64') && !empty($request->assinatura_base64)) {
                $data['assinatura_base64'] = $request->assinatura_base64;
                $data['assinatura_usuario_id'] = auth()->id();
            }

            // Processar checklist PMOC
            if ($request->has('checklist_pmoc')) {
                $checklistInput = $request->input('checklist_pmoc');
                if (is_string($checklistInput)) {
                    $checklistDecoded = json_decode($checklistInput, true);
                    $data['checklist_pmoc'] = is_array($checklistDecoded) ? $checklistDecoded : [];
                } elseif (is_array($checklistInput)) {
                    $data['checklist_pmoc'] = $checklistInput;
                }
            }

            // Atualizar status se fornecido
            if (isset($data['status_operacional'])) {
                $servico->status_operacional = $data['status_operacional'];
            }

            // Atualizar campos do relatório
            $servico->fill($data);
            $servico->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Relatório salvo com sucesso!',
                'servico' => $servico->fresh(['cliente', 'equipamento', 'tecnicos'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao salvar relatório: ' . $e->getMessage()
            ], 500);
        }
    }

    public function salvarDetalhesAtendimento(Request $request, Servico $servico)
    {
        if ($request->user()?->isTecnico()) {
            abort(403, 'Edição de detalhes administrativos não disponível para o seu perfil.');
        }

        $validator = Validator::make($request->all(), [
            'tipo_tarefa' => 'nullable|string|max:255',
            'equipamento_id' => 'nullable|exists:equipamentos,id',
            'orientacao' => 'nullable|string|max:2000',
            'horario_agendamento' => 'nullable|date',
            'horario_chegada' => 'nullable|date',
            'horario_saida' => 'nullable|date',
            'horario_inicio_execucao' => 'nullable|date',
            'horario_fim_execucao' => 'nullable|date',
            'inicio_deslocamento' => 'nullable|date_format:H:i',
            'duracao_deslocamento_minutos' => 'nullable|integer|min:0',
            'status_operacional' => 'nullable|in:pendente,em_andamento,pausado,concluido,cancelado',
            'descricao' => 'nullable|string|max:3000',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $servico->update($validator->validated());

        return response()->json(['success' => true, 'message' => 'Detalhes atualizados com sucesso.']);
    }

    public function salvarRelatoAtendimento(Request $request, Servico $servico)
    {
        $this->ensureTecnicoPodeServico($servico);

        $statusAntes = $servico->status_operacional;

        $validator = Validator::make($request->all(), [
            'relato_execucao' => 'nullable|string|max:5000',
            'checklist_pmoc' => 'nullable|array',
            'status_operacional' => 'nullable|in:pendente,em_andamento,pausado,concluido,cancelado',
            'assinatura_base64' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        $assinatura = $data['assinatura_base64'] ?? null;
        unset($data['assinatura_base64']);

        if ($request->has('status_operacional')) {
            $data['status_operacional'] = $request->status_operacional;
        }

        $novoStatus = $data['status_operacional'] ?? $servico->status_operacional;

        if ($request->user()?->isTecnico() && $novoStatus === 'concluido' && empty(trim((string) $assinatura))) {
            return response()->json([
                'success' => false,
                'message' => 'Para finalizar, é necessário assinar no campo de assinatura.',
            ], 422);
        }

        $servico->fill($data);
        if (! empty($assinatura)) {
            $servico->assinatura_base64 = $assinatura;
            $servico->assinatura_usuario_id = auth()->id();
        }
        if ($novoStatus === 'concluido') {
            if (! $servico->horario_fim_execucao) {
                $servico->horario_fim_execucao = now();
            }
            if (! $servico->horario_saida) {
                $servico->horario_saida = $servico->horario_fim_execucao;
            }
        }
        $servico->save();

        if ($novoStatus === 'concluido' && ! empty($assinatura) && ! in_array($statusAntes, ['concluido', 'cancelado'], true)) {
            $colab = Colaborador::resolveFromUser(auth()->user());
            if ($colab) {
                ServicoHoraRegistro::create([
                    'servico_id' => $servico->id,
                    'colaborador_id' => $colab->id,
                    'usuario_id' => auth()->id(),
                    'monitoramento' => 'check_out',
                    'horario' => now(),
                ]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Relato salvo com sucesso.']);
    }

    public function uploadAnexosAtendimento(Request $request, Servico $servico)
    {
        $this->ensureTecnicoPodeServico($servico);

        $validator = Validator::make($request->all(), [
            'anexos' => 'nullable|array',
            'anexos.*' => 'file|max:10240',
            'fotos' => 'nullable|array',
            'fotos.*' => 'image|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $criados = [];
        foreach (($request->file('anexos') ?? []) as $arquivo) {
            $path = $arquivo->store('servicos/anexos', 'public');
            $criados[] = ServicoAnexo::create([
                'servico_id' => $servico->id,
                'usuario_id' => auth()->id(),
                'tipo' => 'arquivo',
                'nome_original' => $arquivo->getClientOriginalName(),
                'path' => $path,
            ]);
        }

        $fotosNovas = [];
        foreach (($request->file('fotos') ?? []) as $foto) {
            $path = $foto->store('servicos/fotos', 'public');
            $fotosNovas[] = $path;
            $criados[] = ServicoAnexo::create([
                'servico_id' => $servico->id,
                'usuario_id' => auth()->id(),
                'tipo' => 'foto',
                'nome_original' => $foto->getClientOriginalName(),
                'path' => $path,
            ]);
        }

        if (!empty($fotosNovas)) {
            $fotosAtual = is_array($servico->fotos) ? $servico->fotos : [];
            $servico->fotos = array_values(array_unique(array_merge($fotosAtual, $fotosNovas)));
            $servico->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Anexos enviados com sucesso.',
            'anexos' => $criados,
        ]);
    }

    public function removerAnexoAtendimento(Servico $servico, ServicoAnexo $anexo)
    {
        $this->ensureTecnicoPodeServico($servico);

        if ($anexo->servico_id !== $servico->id) {
            return response()->json(['success' => false, 'message' => 'Anexo inválido.'], 422);
        }

        Storage::disk('public')->delete($anexo->path);
        $anexo->delete();

        return response()->json(['success' => true, 'message' => 'Anexo removido com sucesso.']);
    }

    public function adicionarHoraAtendimento(Request $request, Servico $servico)
    {
        $this->ensureTecnicoPodeServico($servico);

        $validator = Validator::make($request->all(), [
            'colaborador_id' => 'nullable|exists:colaboradores,id',
            'colaborador_ids' => 'nullable|array',
            'colaborador_ids.*' => 'nullable|exists:colaboradores,id',
            'monitoramento' => 'required|in:check_in,check_out,pausa,retorno,ajuste',
            'horario' => 'required|date',
            'motivo' => 'nullable|string|max:255',
            'justificativa' => 'nullable|string|max:2000',
            'ajuste_manual' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        $colaboradorIds = array_values(array_filter((array) ($request->input('colaborador_ids') ?? [])));
        if (empty($colaboradorIds) && $request->filled('colaborador_id')) {
            $colaboradorIds = [(int) $request->input('colaborador_id')];
        }

        $baseData = $data;
        unset($baseData['colaborador_ids']);
        $baseData['servico_id'] = $servico->id;
        $baseData['usuario_id'] = auth()->id();
        $baseData['ajuste_manual'] = $request->boolean('ajuste_manual') || $baseData['monitoramento'] === 'ajuste';

        if ($baseData['ajuste_manual'] && (empty($baseData['motivo']) || empty($baseData['justificativa']))) {
            return response()->json([
                'success' => false,
                'message' => 'Motivo e justificativa são obrigatórios em ajustes manuais.',
            ], 422);
        }

        if ($baseData['monitoramento'] === 'pausa' && empty(trim($baseData['motivo'] ?? ''))) {
            return response()->json([
                'success' => false,
                'message' => 'Motivo da pausa é obrigatório.',
            ], 422);
        }

        // Permite check-in sem colaborador selecionado (salva como null), mas quando houver
        // seleção múltipla, gera um registro por colaborador.
        if (empty($colaboradorIds)) {
            $colaboradorIds = [null];
        }

        $registros = [];
        foreach ($colaboradorIds as $colaboradorId) {
            $payload = $baseData;
            $payload['colaborador_id'] = $colaboradorId ?: null;

            $ultimoRegistro = ServicoHoraRegistro::where('servico_id', $servico->id)
                ->where('colaborador_id', $payload['colaborador_id'])
                ->latest('horario')
                ->first();

            if ($ultimoRegistro) {
                $minutos = $ultimoRegistro->horario->diffInMinutes($payload['horario']);
                $payload['tempo_corrido_minutos'] = max(0, $minutos);
            }

            $registros[] = ServicoHoraRegistro::create($payload);
        }

        foreach ($registros as $r) {
            $r->load(['colaborador', 'usuario']);
        }

        return response()->json([
            'success' => true,
            'message' => count($registros) > 1 ? 'Registros de horas adicionados.' : 'Registro de hora adicionado.',
            'registros' => $registros,
        ]);
    }

    public function vincularQuestionarioAtendimento(Request $request, Servico $servico)
    {
        $this->ensureTecnicoPodeServico($servico);

        $validator = Validator::make($request->all(), [
            'questionario_id' => 'required|exists:questionarios,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $vinculo = ServicoQuestionario::firstOrCreate(
            [
                'servico_id' => $servico->id,
                'questionario_id' => $request->questionario_id,
            ],
            [
                'usuario_id' => auth()->id(),
                'respostas' => [],
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Questionário vinculado com sucesso.',
            'vinculo' => $vinculo->load('questionario.perguntas'),
        ]);
    }

    public function salvarRespostasQuestionarioAtendimento(Request $request, Servico $servico, ServicoQuestionario $vinculo)
    {
        if ($vinculo->servico_id !== $servico->id) {
            return response()->json(['success' => false, 'message' => 'Questionário inválido.'], 422);
        }

        $this->ensureTecnicoPodeServico($servico);

        $validator = Validator::make($request->all(), [
            'respostas' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $vinculo->update([
            'respostas' => $request->respostas,
            'usuario_id' => auth()->id(),
        ]);

        return response()->json(['success' => true, 'message' => 'Respostas salvas com sucesso.']);
    }
}
