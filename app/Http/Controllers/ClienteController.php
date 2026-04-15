<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\PlantaBaixa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $query = Cliente::query();

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('razao_social', 'like', "%{$search}%")
                  ->orWhere('cnpj', 'like', "%{$search}%")
                  ->orWhere('cpf', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telefone', 'like', "%{$search}%");
            });
        }

        $clientes = $query->orderBy('nome')->paginate(20);

        // Carregar plantas baixas separadamente para evitar erro se a tabela não existir
        try {
            $clientes->load('plantasBaixas');
        } catch (\Exception $e) {
            // Se a tabela não existir, apenas continua sem carregar as plantas
        }

        return view('crm.clientes', compact('clientes'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'razao_social' => 'nullable|string|max:255',
            'cnpj' => 'nullable|string|max:18',
            'cpf' => 'nullable|string|max:14',
            'telefone' => 'nullable|string|max:20',
            'endereco_completo' => 'nullable|string',
            'emails_responsaveis' => 'nullable|array',
            'emails_responsaveis.*' => 'email|max:255',
            'plantas' => 'nullable|array',
            'plantas.*.nome' => 'nullable|string|max:255',
            'plantas.*.descricao' => 'nullable|string',
            'plantas.*.imagem' => 'nullable|image|max:5120',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            throw new ValidationException($validator);
        }

        $data = $validator->validated();
        
        // Processar emails - garantir que seja array
        if (isset($data['emails_responsaveis'])) {
            // Se for string JSON, converter para array
            if (is_string($data['emails_responsaveis'])) {
                $decoded = json_decode($data['emails_responsaveis'], true);
                $data['emails_responsaveis'] = is_array($decoded) ? $decoded : [];
            }
            // Remover emails vazios
            $data['emails_responsaveis'] = array_values(array_filter(array_map('trim', $data['emails_responsaveis'])));
            // Se estiver vazio, definir como null
            if (empty($data['emails_responsaveis'])) {
                $data['emails_responsaveis'] = null;
            }
        }

        $cliente = Cliente::create($data);

        $this->salvarPlantasDoRequest($request, $cliente);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'cliente' => $cliente->load('plantasBaixas'),
                'message' => 'Cliente cadastrado com sucesso!'
            ]);
        }

        return redirect()->route('crm.clientes.index')->with('success', 'Cliente cadastrado com sucesso!');
    }

    public function show(Cliente $cliente)
    {
        // Se for requisição AJAX/JSON, retornar JSON
        if (request()->expectsJson() || request()->ajax()) {
            $cliente->load(['plantasBaixas', 'equipamentos', 'propostas', 'servicos']);
            
            return response()->json([
                'success' => true,
                'cliente' => $cliente
            ]);
        }
        
        // Se for requisição normal, retornar view
        $cliente->load([
            'plantasBaixas' => function($query) {
                $query->where('ativa', true);
            },
            'equipamentos' => function($query) {
                $query->where('ativo', true)->orderBy('nome');
            },
            'propostas' => function($query) {
                $query->orderBy('data_criacao', 'desc')->limit(10);
            },
            'servicos' => function($query) {
                $query->with(['tecnicos', 'equipamento'])->orderBy('data_inicio', 'desc')->limit(10);
            }
        ]);

        // Contadores reais (não limitados)
        $cliente->loadCount([
            'servicos',
            'propostas',
            'equipamentos as equipamentos_ativos_count' => function ($q) {
                $q->where('ativo', true);
            },
            'plantasBaixas as plantas_ativas_count' => function ($q) {
                $q->where('ativa', true);
            },
        ]);
        
        return view('crm.cliente-detalhes', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'razao_social' => 'nullable|string|max:255',
            'cnpj' => 'nullable|string|max:18',
            'cpf' => 'nullable|string|max:14',
            'telefone' => 'nullable|string|max:20',
            'endereco_completo' => 'nullable|string',
            'emails_responsaveis' => 'nullable|array',
            'emails_responsaveis.*' => 'email|max:255',
            'plantas' => 'nullable|array',
            'plantas.*.nome' => 'nullable|string|max:255',
            'plantas.*.descricao' => 'nullable|string',
            'plantas.*.imagem' => 'nullable|image|max:5120',
            'plantas.*.id' => 'nullable|exists:plantas_baixas,id',
            'plantas_sincronizar' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            throw new ValidationException($validator);
        }

        $data = $validator->validated();
        unset($data['plantas_sincronizar']);
        
        // Processar emails - garantir que seja array
        if (isset($data['emails_responsaveis'])) {
            if (is_string($data['emails_responsaveis'])) {
                $decoded = json_decode($data['emails_responsaveis'], true);
                $data['emails_responsaveis'] = is_array($decoded) ? $decoded : [];
            }
            $data['emails_responsaveis'] = array_values(array_filter(array_map('trim', $data['emails_responsaveis'])));
            if (empty($data['emails_responsaveis'])) {
                $data['emails_responsaveis'] = null;
            }
        }

        $cliente->update($data);

        $keptPlantaIds = $this->salvarPlantasDoRequest($request, $cliente);
        if ($request->boolean('plantas_sincronizar')) {
            $this->removerPlantasOrfas($cliente, $keptPlantaIds);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'cliente' => $cliente->load('plantasBaixas'),
                'message' => 'Cliente atualizado com sucesso!'
            ]);
        }

        return redirect()->route('crm.clientes.index')->with('success', 'Cliente atualizado com sucesso!');
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        $clientes = Cliente::where('nome', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('empresa', 'like', "%{$query}%")
            ->limit(10)
            ->get();

        return response()->json($clientes);
    }

    public function destroy(Request $request, Cliente $cliente)
    {
        $cliente->delete();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cliente excluído com sucesso!',
            ]);
        }

        return redirect()->route('crm.clientes.index')->with('success', 'Cliente excluído com sucesso!');
    }

    /**
     * @return array<int, int> IDs das plantas criadas ou atualizadas neste request
     */
    private function salvarPlantasDoRequest(Request $request, Cliente $cliente): array
    {
        $plantas = $request->input('plantas', []);
        $keptIds = [];
        if (! is_array($plantas) || empty($plantas)) {
            return $keptIds;
        }

        foreach ($plantas as $index => $plantaData) {
            if (! is_array($plantaData)) {
                continue;
            }

            $nome = trim((string) ($plantaData['nome'] ?? ''));
            $descricao = isset($plantaData['descricao']) ? trim((string) $plantaData['descricao']) : null;
            $plantaId = isset($plantaData['id']) ? (int) $plantaData['id'] : null;
            $imagem = $request->file("plantas.{$index}.imagem");
            $imagemPath = null;

            if ($imagem && $imagem->isValid()) {
                $imagemPath = $imagem->store('plantas-baixas', 'public');
            }

            if ($plantaId) {
                $planta = PlantaBaixa::where('id', $plantaId)->where('cliente_id', $cliente->id)->first();
                if (! $planta) {
                    continue;
                }

                $updateData = [
                    'nome' => $nome !== '' ? $nome : $planta->nome,
                    'descricao' => $descricao ?: null,
                ];

                if ($imagemPath) {
                    if ($planta->imagem_path) {
                        Storage::disk('public')->delete($planta->imagem_path);
                    }
                    $updateData['imagem_path'] = $imagemPath;
                }

                $planta->update($updateData);
                $keptIds[] = (int) $planta->id;

                continue;
            }

            if ($nome === '') {
                continue;
            }

            $nova = PlantaBaixa::create([
                'cliente_id' => $cliente->id,
                'nome' => $nome,
                'descricao' => $descricao ?: null,
                'imagem_path' => $imagemPath,
                'ativa' => true,
            ]);
            $keptIds[] = (int) $nova->id;
        }

        return $keptIds;
    }

    /** Remove plantas do cliente que não vieram na lista enviada (edição com sincronização). */
    private function removerPlantasOrfas(Cliente $cliente, array $keptIds): void
    {
        $q = PlantaBaixa::where('cliente_id', $cliente->id);
        if (! empty($keptIds)) {
            $q->whereNotIn('id', $keptIds);
        }
        foreach ($q->get() as $planta) {
            if ($planta->imagem_path) {
                Storage::disk('public')->delete(str_replace('\\', '/', $planta->imagem_path));
            }
            $planta->delete();
        }
    }
}
