<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\ContaPagar;
use App\Models\ContaReceber;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceiroController extends Controller
{
    public function dashboard(Request $request)
    {
        $inicio = $request->filled('inicio') ? Carbon::parse($request->inicio)->startOfDay() : now()->startOfMonth();
        $fim = $request->filled('fim') ? Carbon::parse($request->fim)->endOfDay() : now()->endOfMonth();

        $totalReceberAberto = (float) ContaReceber::selectRaw('COALESCE(SUM(valor_total - valor_recebido), 0) as total')
            ->whereIn('status', ['aberto', 'parcial'])
            ->value('total');

        $totalPagarAberto = (float) ContaPagar::selectRaw('COALESCE(SUM(valor_total - valor_pago), 0) as total')
            ->whereIn('status', ['aberto', 'parcial'])
            ->value('total');

        $inadimplencias = ContaReceber::whereIn('status', ['aberto', 'parcial'])
            ->whereDate('data_vencimento', '<', now()->toDateString());

        $quantidadeInadimplencia = (clone $inadimplencias)->count();
        $valorInadimplencia = (float) (clone $inadimplencias)
            ->selectRaw('COALESCE(SUM(valor_total - valor_recebido), 0) as total')
            ->value('total');

        $duplicatasVencidas = ContaReceber::whereIn('status', ['aberto', 'parcial'])
            ->whereDate('data_vencimento', '<', now()->toDateString())
            ->where('total_parcelas', '>', 1)
            ->count();

        $fluxoPrevisto = $totalReceberAberto - $totalPagarAberto;

        $dre = $this->calcularDre($inicio, $fim, $request->get('regime', 'competencia'));

        $projecoes = $this->montarProjecoes(6);

        $titulosCriticos = ContaReceber::whereIn('status', ['aberto', 'parcial'])
            ->whereDate('data_vencimento', '<=', now()->addDays(7)->toDateString())
            ->orderBy('data_vencimento')
            ->limit(12)
            ->get();

        return view('financeiro.dashboard', compact(
            'inicio',
            'fim',
            'totalReceberAberto',
            'totalPagarAberto',
            'quantidadeInadimplencia',
            'valorInadimplencia',
            'duplicatasVencidas',
            'fluxoPrevisto',
            'dre',
            'projecoes',
            'titulosCriticos'
        ));
    }

    public function contasPagar(Request $request)
    {
        $query = ContaPagar::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('fornecedor')) {
            $query->where('fornecedor', 'like', '%' . $request->fornecedor . '%');
        }
        if ($request->filled('vencimento_de')) {
            $query->whereDate('data_vencimento', '>=', $request->vencimento_de);
        }
        if ($request->filled('vencimento_ate')) {
            $query->whereDate('data_vencimento', '<=', $request->vencimento_ate);
        }
        if ($request->boolean('apenas_vencidas')) {
            $query->whereIn('status', ['aberto', 'parcial'])
                ->whereDate('data_vencimento', '<', now()->toDateString());
        }

        $contas = $query->orderBy('data_vencimento')->paginate(20)->withQueryString();

        return view('financeiro.contas-pagar', compact('contas'));
    }

    public function storeContaPagar(Request $request)
    {
        $data = $request->validate([
            'fornecedor' => ['required', 'string', 'max:255'],
            'descricao' => ['required', 'string', 'max:255'],
            'categoria' => ['nullable', 'string', 'max:120'],
            'numero_documento' => ['nullable', 'string', 'max:120'],
            'grupo_duplicata' => ['nullable', 'string', 'max:120'],
            'parcela' => ['nullable', 'integer', 'min:1'],
            'total_parcelas' => ['nullable', 'integer', 'min:1'],
            'valor_total' => ['required', 'numeric', 'min:0.01'],
            'valor_pago' => ['nullable', 'numeric', 'min:0'],
            'data_emissao' => ['nullable', 'date'],
            'data_vencimento' => ['required', 'date'],
            'data_pagamento' => ['nullable', 'date'],
            'observacoes' => ['nullable', 'string'],
        ]);

        $data['valor_pago'] = (float) ($data['valor_pago'] ?? 0);
        $data['parcela'] = $data['parcela'] ?? 1;
        $data['total_parcelas'] = $data['total_parcelas'] ?? 1;
        $data['status'] = $this->resolverStatusPagar((float) $data['valor_total'], (float) $data['valor_pago']);

        ContaPagar::create($data);

        return back()->with('success', 'Conta a pagar cadastrada com sucesso.');
    }

    public function updateContaPagar(Request $request, ContaPagar $contaPagar)
    {
        $data = $request->validate([
            'fornecedor' => ['required', 'string', 'max:255'],
            'descricao' => ['required', 'string', 'max:255'],
            'categoria' => ['nullable', 'string', 'max:120'],
            'numero_documento' => ['nullable', 'string', 'max:120'],
            'grupo_duplicata' => ['nullable', 'string', 'max:120'],
            'parcela' => ['nullable', 'integer', 'min:1'],
            'total_parcelas' => ['nullable', 'integer', 'min:1'],
            'valor_total' => ['required', 'numeric', 'min:0.01'],
            'valor_pago' => ['nullable', 'numeric', 'min:0'],
            'data_emissao' => ['nullable', 'date'],
            'data_vencimento' => ['required', 'date'],
            'data_pagamento' => ['nullable', 'date'],
            'observacoes' => ['nullable', 'string'],
        ]);

        $data['valor_pago'] = (float) ($data['valor_pago'] ?? 0);
        $data['parcela'] = $data['parcela'] ?? 1;
        $data['total_parcelas'] = $data['total_parcelas'] ?? 1;
        $data['status'] = $this->resolverStatusPagar((float) $data['valor_total'], (float) $data['valor_pago']);

        $contaPagar->update($data);

        return back()->with('success', 'Conta a pagar atualizada.');
    }

    public function baixarContaPagar(Request $request, ContaPagar $contaPagar)
    {
        $data = $request->validate([
            'valor' => ['required', 'numeric', 'min:0.01'],
            'data_pagamento' => ['nullable', 'date'],
        ]);

        $novoValorPago = (float) $contaPagar->valor_pago + (float) $data['valor'];
        $novoValorPago = min($novoValorPago, (float) $contaPagar->valor_total);

        $contaPagar->update([
            'valor_pago' => $novoValorPago,
            'data_pagamento' => $data['data_pagamento'] ?? now()->toDateString(),
            'status' => $this->resolverStatusPagar((float) $contaPagar->valor_total, $novoValorPago),
        ]);

        return back()->with('success', 'Pagamento lançado com sucesso.');
    }

    public function destroyContaPagar(ContaPagar $contaPagar)
    {
        $contaPagar->delete();

        return back()->with('success', 'Conta a pagar excluída.');
    }

    public function contasReceber(Request $request)
    {
        $query = ContaReceber::query()->with('cliente');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('cliente')) {
            $query->where(function ($q) use ($request) {
                $q->where('cliente_nome', 'like', '%' . $request->cliente . '%')
                    ->orWhereHas('cliente', function ($clienteQuery) use ($request) {
                        $clienteQuery->where('razao_social', 'like', '%' . $request->cliente . '%');
                    });
            });
        }
        if ($request->filled('vencimento_de')) {
            $query->whereDate('data_vencimento', '>=', $request->vencimento_de);
        }
        if ($request->filled('vencimento_ate')) {
            $query->whereDate('data_vencimento', '<=', $request->vencimento_ate);
        }
        if ($request->boolean('apenas_inadimplentes')) {
            $query->whereIn('status', ['aberto', 'parcial'])
                ->whereDate('data_vencimento', '<', now()->toDateString());
        }

        $contas = $query->orderBy('data_vencimento')->paginate(20)->withQueryString();
        $clientes = Cliente::orderBy('razao_social')->get(['id', 'razao_social']);

        return view('financeiro.contas-receber', compact('contas', 'clientes'));
    }

    public function storeContaReceber(Request $request)
    {
        $data = $request->validate([
            'cliente_id' => ['nullable', 'exists:clientes,id'],
            'cliente_nome' => ['nullable', 'string', 'max:255'],
            'descricao' => ['required', 'string', 'max:255'],
            'categoria' => ['nullable', 'string', 'max:120'],
            'numero_documento' => ['nullable', 'string', 'max:120'],
            'grupo_duplicata' => ['nullable', 'string', 'max:120'],
            'parcela' => ['nullable', 'integer', 'min:1'],
            'total_parcelas' => ['nullable', 'integer', 'min:1'],
            'valor_total' => ['required', 'numeric', 'min:0.01'],
            'valor_recebido' => ['nullable', 'numeric', 'min:0'],
            'data_emissao' => ['nullable', 'date'],
            'data_vencimento' => ['required', 'date'],
            'data_recebimento' => ['nullable', 'date'],
            'observacoes' => ['nullable', 'string'],
        ]);

        $data['valor_recebido'] = (float) ($data['valor_recebido'] ?? 0);
        $data['parcela'] = $data['parcela'] ?? 1;
        $data['total_parcelas'] = $data['total_parcelas'] ?? 1;
        $data['status'] = $this->resolverStatusReceber((float) $data['valor_total'], (float) $data['valor_recebido']);

        ContaReceber::create($data);

        return back()->with('success', 'Conta a receber cadastrada com sucesso.');
    }

    public function updateContaReceber(Request $request, ContaReceber $contaReceber)
    {
        $data = $request->validate([
            'cliente_id' => ['nullable', 'exists:clientes,id'],
            'cliente_nome' => ['nullable', 'string', 'max:255'],
            'descricao' => ['required', 'string', 'max:255'],
            'categoria' => ['nullable', 'string', 'max:120'],
            'numero_documento' => ['nullable', 'string', 'max:120'],
            'grupo_duplicata' => ['nullable', 'string', 'max:120'],
            'parcela' => ['nullable', 'integer', 'min:1'],
            'total_parcelas' => ['nullable', 'integer', 'min:1'],
            'valor_total' => ['required', 'numeric', 'min:0.01'],
            'valor_recebido' => ['nullable', 'numeric', 'min:0'],
            'data_emissao' => ['nullable', 'date'],
            'data_vencimento' => ['required', 'date'],
            'data_recebimento' => ['nullable', 'date'],
            'observacoes' => ['nullable', 'string'],
        ]);

        $data['valor_recebido'] = (float) ($data['valor_recebido'] ?? 0);
        $data['parcela'] = $data['parcela'] ?? 1;
        $data['total_parcelas'] = $data['total_parcelas'] ?? 1;
        $data['status'] = $this->resolverStatusReceber((float) $data['valor_total'], (float) $data['valor_recebido']);

        $contaReceber->update($data);

        return back()->with('success', 'Conta a receber atualizada.');
    }

    public function baixarContaReceber(Request $request, ContaReceber $contaReceber)
    {
        $data = $request->validate([
            'valor' => ['required', 'numeric', 'min:0.01'],
            'data_recebimento' => ['nullable', 'date'],
        ]);

        $novoValorRecebido = (float) $contaReceber->valor_recebido + (float) $data['valor'];
        $novoValorRecebido = min($novoValorRecebido, (float) $contaReceber->valor_total);

        $contaReceber->update([
            'valor_recebido' => $novoValorRecebido,
            'data_recebimento' => $data['data_recebimento'] ?? now()->toDateString(),
            'status' => $this->resolverStatusReceber((float) $contaReceber->valor_total, $novoValorRecebido),
        ]);

        return back()->with('success', 'Recebimento lançado com sucesso.');
    }

    public function destroyContaReceber(ContaReceber $contaReceber)
    {
        $contaReceber->delete();

        return back()->with('success', 'Conta a receber excluída.');
    }

    public function dre(Request $request)
    {
        $inicio = $request->filled('inicio') ? Carbon::parse($request->inicio)->startOfDay() : now()->startOfMonth();
        $fim = $request->filled('fim') ? Carbon::parse($request->fim)->endOfDay() : now()->endOfMonth();
        $regime = $request->get('regime', 'competencia');

        $dre = $this->calcularDre($inicio, $fim, $regime);

        return view('financeiro.dre', compact('dre', 'inicio', 'fim', 'regime'));
    }

    private function calcularDre(Carbon $inicio, Carbon $fim, string $regime): array
    {
        $colunaReceitas = $regime === 'caixa' ? 'data_recebimento' : 'data_vencimento';
        $colunaDespesas = $regime === 'caixa' ? 'data_pagamento' : 'data_vencimento';

        $receitaBruta = (float) ContaReceber::query()
            ->when($regime === 'caixa', function ($q) {
                $q->where('status', 'recebido');
            })
            ->whereBetween($colunaReceitas, [$inicio->toDateString(), $fim->toDateString()])
            ->sum(DB::raw($regime === 'caixa' ? 'valor_recebido' : 'valor_total'));

        $deducoes = 0.0;
        $receitaLiquida = $receitaBruta - $deducoes;

        $despesasOperacionais = (float) ContaPagar::query()
            ->when($regime === 'caixa', function ($q) {
                $q->where('status', 'pago');
            })
            ->whereBetween($colunaDespesas, [$inicio->toDateString(), $fim->toDateString()])
            ->sum(DB::raw($regime === 'caixa' ? 'valor_pago' : 'valor_total'));

        $resultadoOperacional = $receitaLiquida - $despesasOperacionais;
        $margem = $receitaLiquida > 0 ? ($resultadoOperacional / $receitaLiquida) * 100 : 0;

        return [
            'receita_bruta' => $receitaBruta,
            'deducoes' => $deducoes,
            'receita_liquida' => $receitaLiquida,
            'despesas_operacionais' => $despesasOperacionais,
            'resultado_operacional' => $resultadoOperacional,
            'margem_percentual' => $margem,
        ];
    }

    private function montarProjecoes(int $meses): array
    {
        $labels = [];
        $receber = [];
        $pagar = [];

        for ($i = 0; $i < $meses; $i++) {
            $base = now()->copy()->startOfMonth()->addMonths($i);
            $inicioMes = $base->copy()->startOfMonth()->toDateString();
            $fimMes = $base->copy()->endOfMonth()->toDateString();

            $labels[] = $base->translatedFormat('M/Y');

            $receber[] = (float) ContaReceber::whereIn('status', ['aberto', 'parcial'])
                ->whereBetween('data_vencimento', [$inicioMes, $fimMes])
                ->selectRaw('COALESCE(SUM(valor_total - valor_recebido), 0) as total')
                ->value('total');

            $pagar[] = (float) ContaPagar::whereIn('status', ['aberto', 'parcial'])
                ->whereBetween('data_vencimento', [$inicioMes, $fimMes])
                ->selectRaw('COALESCE(SUM(valor_total - valor_pago), 0) as total')
                ->value('total');
        }

        return [
            'labels' => $labels,
            'receber' => $receber,
            'pagar' => $pagar,
        ];
    }

    private function resolverStatusPagar(float $valorTotal, float $valorPago): string
    {
        if ($valorPago <= 0) {
            return 'aberto';
        }

        return $valorPago >= $valorTotal ? 'pago' : 'parcial';
    }

    private function resolverStatusReceber(float $valorTotal, float $valorRecebido): string
    {
        if ($valorRecebido <= 0) {
            return 'aberto';
        }

        return $valorRecebido >= $valorTotal ? 'recebido' : 'parcial';
    }
}
