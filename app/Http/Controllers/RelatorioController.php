<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Servico;
use Illuminate\Http\Request;

class RelatorioController extends Controller
{
    public function index(Request $request)
    {
        $query = Servico::with([
            'cliente',
            'tecnicos',
            'questionariosVinculados.questionario.perguntas',
            'horas',
        ])->whereNotNull('data_inicio');

        if ($request->filled('cliente_id')) {
            $query->where('cliente_id', (int) $request->cliente_id);
        }

        if ($request->filled('busca_cliente')) {
            $q = trim((string) $request->busca_cliente);
            if ($q !== '') {
                $query->whereHas('cliente', static function ($c) use ($q) {
                    $c->where('nome', 'like', '%'.$q.'%');
                });
            }
        }

        if ($request->filled('data_de')) {
            $query->whereDate('data_inicio', '>=', $request->data_de);
        }

        if ($request->filled('data_ate')) {
            $query->whereDate('data_inicio', '<=', $request->data_ate);
        }

        $servicos = $query
            ->orderByDesc('data_inicio')
            ->orderByDesc('horario_agendamento')
            ->paginate(25)
            ->withQueryString();

        $clientes = Cliente::orderBy('nome')->get(['id', 'nome']);

        return view('crm.relatorios.index', compact('servicos', 'clientes'));
    }
}
