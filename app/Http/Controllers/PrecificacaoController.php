<?php

namespace App\Http\Controllers;

use App\Models\ParametroPrecificacao;
use App\Models\Cliente;
use App\Models\Proposta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PrecificacaoController extends Controller
{
    public function index()
    {
        $parametros = ParametroPrecificacao::getParametros();
        $clientes = Cliente::orderBy('nome')->get();
        
        return view('crm.precificacao', compact('parametros', 'clientes'));
    }

    public function calcular(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dias_obra' => 'required|integer|min:1',
            'qtd_equipe' => 'required|integer|min:1',
            'servico_local' => 'boolean',
            'taxa_horaria' => 'required|numeric|min:0',
            'horas_dia' => 'required|numeric|min:0|max:24',
            'diaria_combustivel' => 'nullable|numeric|min:0',
            'locacao_veiculo' => 'nullable|numeric|min:0',
            'materiais' => 'nullable|numeric|min:0',
            'terceiros' => 'nullable|numeric|min:0',
            'admin' => 'nullable|numeric|min:0',
            'outros' => 'nullable|numeric|min:0',
            'margem_liquida_alvo' => 'required|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        $parametros = ParametroPrecificacao::getParametros();

        // Calcular Mão de Obra
        $subtotalMO = $data['taxa_horaria'] * $data['horas_dia'] * $data['dias_obra'] * $data['qtd_equipe'];

        // Calcular Logística
        $totalCombustivel = ($data['diaria_combustivel'] ?? 0) * $data['dias_obra'];
        $locacaoVeiculo = ($data['locacao_veiculo'] ?? $parametros->locacao_veiculo_diaria) * $data['dias_obra'];
        $subtotalLogistica = $totalCombustivel + $locacaoVeiculo;

        // Custos Diretos
        $custosDiretos = $subtotalMO + $subtotalLogistica;

        // Insumos & Variáveis
        $materiais = $data['materiais'] ?? 0;
        $terceiros = $data['terceiros'] ?? 0;
        $admin = $data['admin'] ?? 0;
        $outros = $data['outros'] ?? 0;
        $insumosVariaveis = $materiais + $terceiros + $admin + $outros;

        // Custo Operacional Bruto
        $custoOperacionalBruto = $custosDiretos + $insumosVariaveis;

        // Calcular Valor de Proposta baseado na margem líquida alvo
        $margemLiquidaAlvo = $data['margem_liquida_alvo'] / 100;
        
        // Fórmula: Valor Proposta = Custo Bruto / (1 - Margem Líquida - Alíquota Impostos/100 - Taxa Admin/100)
        $aliquotaImpostos = $parametros->aliquota_impostos / 100;
        $taxaAdm = $parametros->taxa_adm_fixa / 100;
        
        $valorProposta = $custoOperacionalBruto / (1 - $margemLiquidaAlvo - $aliquotaImpostos - $taxaAdm);

        // Calcular valores finais
        $cargaTributaria = $valorProposta * $aliquotaImpostos;
        $custoAdmin = $valorProposta * $taxaAdm;
        $cargaTributariaTotal = $cargaTributaria + $custoAdmin;
        
        $lucroLiquidoReal = $valorProposta - $custoOperacionalBruto - $cargaTributariaTotal;
        $margemFinal = ($lucroLiquidoReal / $valorProposta) * 100;

        return response()->json([
            'success' => true,
            'calculos' => [
                'subtotal_mo' => number_format($subtotalMO, 2, ',', '.'),
                'total_combustivel' => number_format($totalCombustivel, 2, ',', '.'),
                'locacao_veiculo' => number_format($locacaoVeiculo, 2, ',', '.'),
                'subtotal_logistica' => number_format($subtotalLogistica, 2, ',', '.'),
                'custos_diretos' => number_format($custosDiretos, 2, ',', '.'),
                'insumos_variaveis' => number_format($insumosVariaveis, 2, ',', '.'),
                'custo_operacional_bruto' => number_format($custoOperacionalBruto, 2, ',', '.'),
                'valor_proposta' => number_format($valorProposta, 3, ',', '.'),
                'carga_tributaria' => number_format($cargaTributariaTotal, 3, ',', '.'),
                'lucro_liquido_real' => number_format($lucroLiquidoReal, 3, ',', '.'),
                'margem_final' => number_format($margemFinal, 1, ',', '.'),
                'markup_unitario' => number_format($lucroLiquidoReal, 3, ',', '.'),
            ]
        ]);
    }

    public function updateParametros(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'custo_mo_hora' => 'required|numeric|min:0',
            'aliquota_impostos' => 'required|numeric|min:0|max:100',
            'taxa_adm_fixa' => 'required|numeric|min:0|max:100',
            'refeicao_diaria_pessoa' => 'required|numeric|min:0',
            'pernoite_diaria_pessoa' => 'required|numeric|min:0',
            'locacao_veiculo_diaria' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $parametros = ParametroPrecificacao::getParametros();
        $parametros->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Parâmetros atualizados com sucesso!'
        ]);
    }

    public function enviarParaFunil(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'cliente_id' => 'required|exists:clientes,id',
                'valor_final' => 'required|numeric|min:0',
                'titulo' => 'nullable|string|max:255',
                'descricao_inicial' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false, 
                    'errors' => $validator->errors(),
                    'message' => 'Erro de validação. Verifique os dados enviados.'
                ], 422);
            }

            $data = $validator->validated();
            
            // Gerar código da proposta (obrigatório)
            $data['codigo_proposta'] = Proposta::gerarCodigo();
            
            // Definir responsável (usuário autenticado) - pode ser null se não houver autenticação
            $data['responsavel_id'] = auth()->check() ? auth()->id() : null;
            
            // Definir estado padrão
            $data['estado'] = 'primeiro_contato';
            
            // Definir data de criação
            $data['data_criacao'] = now()->format('Y-m-d');
            
            // Garantir que o título não seja vazio
            if (empty($data['titulo']) || trim($data['titulo']) === '') {
                $data['titulo'] = 'Proposta ' . $data['codigo_proposta'];
            }
            
            // Log dos dados antes de criar
            Log::info('Criando proposta com dados:', $data);

            // Criar a proposta
            $proposta = Proposta::create($data);
            
            Log::info('Proposta criada com sucesso. ID: ' . $proposta->id . ', Código: ' . $proposta->codigo_proposta);

            return response()->json([
                'success' => true,
                'message' => 'Proposta enviada para o Funil CRM com sucesso!',
                'proposta_id' => $proposta->id,
                'codigo_proposta' => $proposta->codigo_proposta,
            ]);
            
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Erro ao criar proposta: ' . $e->getMessage());
            Log::error('SQL: ' . $e->getSql());
            Log::error('Bindings: ' . json_encode($e->getBindings()));
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao salvar no banco de dados. Verifique os logs para mais detalhes.',
                'error' => $e->getMessage()
            ], 500);
            
        } catch (\Exception $e) {
            Log::error('Erro inesperado ao criar proposta: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro inesperado ao salvar proposta.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
