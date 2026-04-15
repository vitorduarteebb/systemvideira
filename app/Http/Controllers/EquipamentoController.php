<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Equipamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class EquipamentoController extends Controller
{
    /**
     * FormData/HTML envia "" em campos vazios; regras nullable|date (etc.) falham com string vazia.
     */
    private function normalizarCamposOpcionaisEquipamento(Request $request): void
    {
        foreach ([
            'tipo_unidade',
            'tag',
            'capacidade_btus',
            'ultima_manutencao',
            'localizacao',
            'observacoes_tecnicas',
        ] as $key) {
            $v = $request->input($key);
            if ($v === '' || (is_string($v) && trim($v) === '')) {
                $request->merge([$key => null]);
            }
        }

        $tu = $request->input('tipo_unidade');
        if (is_string($tu) && trim($tu) !== '') {
            $slug = Str::slug(trim($tu), '_');
            $request->merge(['tipo_unidade' => $slug !== '' ? Str::limit($slug, 64, '') : null]);
        }
    }

    public function index(Request $request)
    {
        $query = Equipamento::with('cliente');

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('tag', 'like', "%{$search}%")
                  ->orWhere('capacidade_btus', 'like', "%{$search}%")
                  ->orWhere('localizacao', 'like', "%{$search}%");
            });
        }

        if ($request->filled('cliente_id')) {
            $query->where('cliente_id', $request->cliente_id);
        }

        if ($request->filled('tipo_unidade')) {
            $query->where('tipo_unidade', $request->tipo_unidade);
        }

        $equipamentos = $query->orderBy('nome')->paginate(20);
        $clientes = Cliente::orderBy('nome')->get();

        return view('crm.equipamentos', compact('equipamentos', 'clientes'));
    }

    public function store(Request $request)
    {
        $this->normalizarCamposOpcionaisEquipamento($request);

        $validator = Validator::make($request->all(), [
            'cliente_id' => 'required|exists:clientes,id',
            'nome' => 'required|string|max:255',
            'tag' => 'nullable|string|max:255',
            'tipo_unidade' => 'nullable|string|max:64|regex:/^[a-z0-9_]+$/i',
            'capacidade_btus' => 'nullable|string|max:50',
            'ultima_manutencao' => 'nullable|date',
            'localizacao' => 'nullable|string|max:255',
            'observacoes_tecnicas' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            throw new ValidationException($validator);
        }

        $data = $validator->validated();
        $tu = $data['tipo_unidade'] ?? null;
        $data['tipo_unidade'] = (is_string($tu) && $tu !== '') ? $tu : 'condensadora';
        $data['ativo'] = true;

        $equipamento = Equipamento::create($data);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'equipamento' => $equipamento->load('cliente'),
                'message' => 'Equipamento cadastrado com sucesso!'
            ]);
        }

        return redirect()->route('crm.equipamentos.index')->with('success', 'Equipamento cadastrado com sucesso!');
    }

    public function update(Request $request, Equipamento $equipamento)
    {
        $this->normalizarCamposOpcionaisEquipamento($request);

        $validator = Validator::make($request->all(), [
            'cliente_id' => 'required|exists:clientes,id',
            'nome' => 'required|string|max:255',
            'tag' => 'nullable|string|max:255',
            'tipo_unidade' => 'nullable|string|max:64|regex:/^[a-z0-9_]+$/i',
            'capacidade_btus' => 'nullable|string|max:50',
            'ultima_manutencao' => 'nullable|date',
            'localizacao' => 'nullable|string|max:255',
            'observacoes_tecnicas' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            throw new ValidationException($validator);
        }
        $data = $validator->validated();
        $tu = $data['tipo_unidade'] ?? null;
        if (! is_string($tu) || $tu === '') {
            $data['tipo_unidade'] = $equipamento->tipo_unidade ?: 'condensadora';
        }
        $equipamento->update($data);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Equipamento atualizado com sucesso!'
            ]);
        }

        return redirect()->route('crm.equipamentos.index')->with('success', 'Equipamento atualizado com sucesso!');
    }

    public function destroy(Request $request, Equipamento $equipamento)
    {
        $equipamento->delete();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Equipamento excluído com sucesso!']);
        }

        return redirect()->route('crm.equipamentos.index')->with('success', 'Equipamento excluído com sucesso!');
    }
}
