<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Proposta;
use App\Models\PropostaAcompanhamento;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade\Pdf;

class PropostaController extends Controller
{
    public function index(Request $request)
    {
        $query = Proposta::query()->with(['cliente', 'responsavel']);

        // Filtros
        if ($request->filled('cliente_id')) {
            $query->where('cliente_id', $request->cliente_id);
        }

        if ($request->filled('cliente_search')) {
            $query->whereHas('cliente', function($q) use ($request) {
                $q->where('nome', 'like', '%' . $request->cliente_search . '%')
                  ->orWhere('email', 'like', '%' . $request->cliente_search . '%')
                  ->orWhere('empresa', 'like', '%' . $request->cliente_search . '%');
            });
        }

        if ($request->filled('codigo_proposta')) {
            $query->where('codigo_proposta', 'like', '%' . $request->codigo_proposta . '%');
        }

        if ($request->filled('responsavel_id')) {
            $query->where('responsavel_id', $request->responsavel_id);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('valor_min')) {
            $query->where('valor_final', '>=', $request->valor_min);
        }

        if ($request->filled('valor_max')) {
            $query->where('valor_final', '<=', $request->valor_max);
        }

        if ($request->filled('motivo_ganho')) {
            $query->where('motivo_ganho', 'like', '%' . $request->motivo_ganho . '%');
        }

        if ($request->filled('motivo_perda')) {
            $query->where('motivo_perda', 'like', '%' . $request->motivo_perda . '%');
        }

        if ($request->filled('motivo_negociacao')) {
            $query->where('motivo_negociacao', 'like', '%' . $request->motivo_negociacao . '%');
        }

        $tipoDataFiltro = $request->get('tipo_data', 'criacao');
        if (!in_array($tipoDataFiltro, ['criacao', 'fechamento'], true)) {
            $tipoDataFiltro = 'criacao';
        }
        $colunaData = $tipoDataFiltro === 'fechamento' ? 'data_fechamento' : 'data_criacao';

        [$dataDe, $dataAte] = $this->resolverPeriodo($request);
        if ($dataDe) {
            $query->whereDate($colunaData, '>=', $dataDe);
        }
        if ($dataAte) {
            $query->whereDate($colunaData, '<=', $dataAte);
        }

        if ($request->filled('tempo_fechamento_min')) {
            $query->whereRaw('DATEDIFF(COALESCE(data_fechamento, CURDATE()), data_criacao) >= ?', [(int) $request->tempo_fechamento_min]);
        }

        if ($request->filled('tempo_fechamento_max')) {
            $query->whereRaw('DATEDIFF(COALESCE(data_fechamento, CURDATE()), data_criacao) <= ?', [(int) $request->tempo_fechamento_max]);
        }

        $propostas = $query->orderByDesc('created_at')->get();

        $metricasQuery = (clone $query);

        // Estatísticas
        $totalGeral = (clone $metricasQuery)->sum('valor_final');
        $emNegociacao = (clone $metricasQuery)->whereIn('estado', ['primeiro_contato', 'em_analise'])->sum('valor_final');
        $valorFechado = (clone $metricasQuery)->where('estado', 'fechado')->sum('valor_final');
        $acoesPendentes = (clone $metricasQuery)->whereIn('estado', ['primeiro_contato', 'em_analise'])->count();

        // Contadores por estado
        $primeiroContato = (clone $metricasQuery)->where('estado', 'primeiro_contato')->count();
        $emAnalise = (clone $metricasQuery)->where('estado', 'em_analise')->count();
        $fechado = (clone $metricasQuery)->where('estado', 'fechado')->count();
        $perdido = (clone $metricasQuery)->where('estado', 'perdido')->count();

        $clientes = Cliente::orderBy('nome')->get();
        $responsaveis = User::where('role', 'admin')->orWhere('role', 'comercial')->get();

        return view('crm.funil', compact(
            'propostas',
            'totalGeral',
            'emNegociacao',
            'valorFechado',
            'acoesPendentes',
            'primeiroContato',
            'emAnalise',
            'fechado',
            'perdido',
            'clientes',
            'responsaveis',
            'tipoDataFiltro'
        ));
    }

    public function pdf(Proposta $proposta)
    {
        $proposta->load(['cliente', 'responsavel']);

        if (!class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            abort(500, 'Módulo de PDF não está disponível no servidor.');
        }

        $pdf = Pdf::loadView('crm.proposta-pdf', compact('proposta'))->setPaper('a4');

        $filename = 'Proposta-' . ($proposta->codigo_proposta ?: $proposta->id) . '.pdf';

        return $pdf->download($filename);
    }

    public function create()
    {
        $clientes = Cliente::orderBy('nome')->get();
        $responsaveis = User::where('role', 'admin')->orWhere('role', 'comercial')->get();
        
        return view('crm.nova-proposta', compact('clientes', 'responsaveis'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cliente_id' => 'required|exists:clientes,id',
            'valor_final' => 'required|numeric|min:0',
            'estado' => 'required|in:primeiro_contato,em_analise,fechado,perdido,outros',
            'titulo' => 'nullable|string|max:255',
            'descricao_inicial' => 'nullable|string',
            'configuracoes_tecnicas' => 'nullable|string',
            'motivo_ganho' => 'nullable|string|max:2000',
            'motivo_perda' => 'nullable|string|max:2000',
            'motivo_negociacao' => 'nullable|string|max:2000',
            'responsavel_id' => 'nullable|exists:users,id',
            'data_criacao' => 'required|date',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            throw new ValidationException($validator);
        }

        $data = $validator->validated();
        $data['codigo_proposta'] = Proposta::gerarCodigo();

        Proposta::create($data);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Proposta criada com sucesso!']);
        }

        return redirect()->route('crm.funil')->with('success', 'Proposta criada com sucesso!');
    }

    public function update(Request $request, Proposta $proposta)
    {
        $validator = Validator::make($request->all(), [
            'estado' => 'required|in:primeiro_contato,em_analise,fechado,perdido,outros',
            'valor_final' => 'nullable|numeric|min:0',
            'data_fechamento' => 'nullable|date',
            'motivo_ganho' => 'nullable|string|max:2000',
            'motivo_perda' => 'nullable|string|max:2000',
            'motivo_negociacao' => 'nullable|string|max:2000',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            throw new ValidationException($validator);
        }

        $data = $validator->validated();
        
        if (isset($data['estado']) && $data['estado'] === 'fechado' && empty($data['data_fechamento']) && !$proposta->data_fechamento) {
            $data['data_fechamento'] = now()->toDateString();
        }

        if (isset($data['estado']) && $data['estado'] === 'perdido' && empty($data['data_fechamento']) && !$proposta->data_fechamento) {
            $data['data_fechamento'] = now()->toDateString();
        }

        $proposta->update($data);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Proposta atualizada com sucesso!']);
        }

        return redirect()->route('crm.funil')->with('success', 'Proposta atualizada com sucesso!');
    }

    public function destroy(Request $request, Proposta $proposta)
    {
        $proposta->delete();
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Proposta excluída com sucesso!']);
        }
        
        return redirect()->route('crm.funil')->with('success', 'Proposta excluída com sucesso!');
    }

    public function show(Proposta $proposta)
    {
        $proposta->load(['cliente', 'responsavel', 'acompanhamentos.usuario']);
        
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'proposta' => $proposta,
            ]);
        }
        
        return view('crm.proposta-detalhes', compact('proposta'));
    }

    public function getAcompanhamentos(Proposta $proposta)
    {
        $acompanhamentos = $proposta->acompanhamentos()->with('usuario')->orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'acompanhamentos' => $acompanhamentos,
        ]);
    }

    public function storeAcompanhamento(Request $request, Proposta $proposta)
    {
        $validator = Validator::make($request->all(), [
            'descricao' => 'required|string|max:5000',
            'data_retorno' => 'nullable|date',
            'data_evento' => 'nullable|date',
            'tipo' => 'nullable|string|in:acompanhamento,retorno,fechamento,contato,reuniao,outros',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        $data['proposta_id'] = $proposta->id;
        $data['usuario_id'] = auth()->id();
        $data['tipo'] = $data['tipo'] ?? 'acompanhamento';

        $acompanhamento = PropostaAcompanhamento::create($data);
        $acompanhamento->load('usuario');

        return response()->json([
            'success' => true,
            'message' => 'Acompanhamento adicionado com sucesso!',
            'acompanhamento' => $acompanhamento,
        ]);
    }

    private function resolverPeriodo(Request $request): array
    {
        if ($request->filled('data_de') || $request->filled('data_ate')) {
            return [$request->input('data_de') ?: null, $request->input('data_ate') ?: null];
        }

        $periodo = $request->get('periodo');
        if (!$periodo) {
            return [null, null];
        }

        $hoje = Carbon::today();
        return match ($periodo) {
            'hoje' => [$hoje->toDateString(), $hoje->toDateString()],
            '7dias' => [$hoje->copy()->subDays(6)->toDateString(), $hoje->toDateString()],
            '30dias' => [$hoje->copy()->subDays(29)->toDateString(), $hoje->toDateString()],
            'mes_atual' => [$hoje->copy()->startOfMonth()->toDateString(), $hoje->copy()->endOfMonth()->toDateString()],
            'trimestre' => [$hoje->copy()->startOfQuarter()->toDateString(), $hoje->copy()->endOfQuarter()->toDateString()],
            'ano_atual' => [$hoje->copy()->startOfYear()->toDateString(), $hoje->copy()->endOfYear()->toDateString()],
            default => [null, null],
        };
    }
}
