<?php

namespace App\Http\Controllers;

use App\Models\ItemEstoque;
use Illuminate\Http\Request;

class EstoqueController extends Controller
{
    public function estoqueBaixo(Request $request)
    {
        $fornecedores = ItemEstoque::where('ativo', true)
            ->whereNotNull('fornecedor')
            ->where('fornecedor', '!=', '')
            ->distinct()
            ->orderBy('fornecedor')
            ->pluck('fornecedor');

        $query = ItemEstoque::estoqueBaixo();
        if ($request->filled('fornecedor')) {
            $query->porFornecedor($request->fornecedor);
        }
        $itens = $query->orderBy('fornecedor')->orderBy('nome')->get();

        return view('financeiro.estoque-baixo', [
            'itens' => $itens,
            'fornecedores' => $fornecedores,
            'fornecedorFiltro' => $request->get('fornecedor'),
        ]);
    }

    public function estoqueBaixoPdf(Request $request)
    {
        $query = ItemEstoque::estoqueBaixo();
        if ($request->filled('fornecedor')) {
            $query->porFornecedor($request->fornecedor);
        }
        $itens = $query->orderBy('fornecedor')->orderBy('nome')->get();
        $fornecedorLabel = $request->filled('fornecedor')
            ? $request->fornecedor
            : 'Todos os fornecedores';

        if (class_exists('\Barryvdh\DomPDF\Facade\Pdf')) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('financeiro.estoque-baixo-pdf', [
                'itens' => $itens,
                'fornecedorLabel' => $fornecedorLabel,
            ]);
            $nomeArquivo = 'estoque-baixo-' . now()->format('Y-m-d') . '.pdf';
            return $pdf->download($nomeArquivo);
        }

        return view('financeiro.estoque-baixo-pdf', [
            'itens' => $itens,
            'fornecedorLabel' => $fornecedorLabel,
        ]);
    }

    public function index(Request $request)
    {
        $query = ItemEstoque::query();
        if ($request->filled('fornecedor')) {
            $query->porFornecedor($request->fornecedor);
        }
        if ($request->filled('busca')) {
            $query->where(function ($q) use ($request) {
                $q->where('nome', 'like', '%' . $request->busca . '%')
                    ->orWhere('codigo', 'like', '%' . $request->busca . '%');
            });
        }
        $itens = $query->orderBy('fornecedor')->orderBy('nome')->paginate(30)->withQueryString();
        $fornecedores = ItemEstoque::where('ativo', true)
            ->whereNotNull('fornecedor')
            ->where('fornecedor', '!=', '')
            ->distinct()
            ->orderBy('fornecedor')
            ->pluck('fornecedor');

        return view('financeiro.estoque-itens', [
            'itens' => $itens,
            'fornecedores' => $fornecedores,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'unidade' => 'nullable|string|max:20',
            'quantidade_atual' => 'required|numeric|min:0',
            'estoque_minimo' => 'required|numeric|min:0',
            'fornecedor' => 'nullable|string|max:255',
            'codigo' => 'nullable|string|max:80',
        ]);

        ItemEstoque::create($request->only([
            'nome', 'unidade', 'quantidade_atual', 'estoque_minimo', 'fornecedor', 'codigo'
        ]));

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Item cadastrado.']);
        }
        return redirect()->route('crm.financeiro.estoque.itens')->with('success', 'Item cadastrado.');
    }

    public function update(Request $request, ItemEstoque $itemEstoque)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'unidade' => 'nullable|string|max:20',
            'quantidade_atual' => 'required|numeric|min:0',
            'estoque_minimo' => 'required|numeric|min:0',
            'fornecedor' => 'nullable|string|max:255',
            'codigo' => 'nullable|string|max:80',
        ]);

        $itemEstoque->update($request->only([
            'nome', 'unidade', 'quantidade_atual', 'estoque_minimo', 'fornecedor', 'codigo'
        ]));

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Item atualizado.']);
        }
        return redirect()->route('crm.financeiro.estoque.itens')->with('success', 'Item atualizado.');
    }

    public function destroy(ItemEstoque $itemEstoque)
    {
        $itemEstoque->delete();
        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('crm.financeiro.estoque.itens')->with('success', 'Item removido.');
    }
}
