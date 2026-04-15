<?php

namespace App\Http\Controllers;

use App\Models\Questionario;
use App\Models\QuestionarioPergunta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class QuestionarioController extends Controller
{
    public function index(Request $request)
    {
        $query = Questionario::query()->withCount('perguntas');

        if ($request->filled('search')) {
            $query->where('titulo', 'like', '%' . $request->search . '%');
        }

        $questionarios = $query->latest()->paginate(10)->withQueryString();

        return view('crm.questionarios.index', compact('questionarios'));
    }

    public function create()
    {
        return view('crm.questionarios.form', [
            'questionario' => new Questionario(),
            'perguntas' => collect(),
        ]);
    }

    public function edit(Questionario $questionario)
    {
        $questionario->load('perguntas');

        return view('crm.questionarios.form', [
            'questionario' => $questionario,
            'perguntas' => $questionario->perguntas,
        ]);
    }

    public function store(Request $request)
    {
        return $this->persist($request, new Questionario());
    }

    public function update(Request $request, Questionario $questionario)
    {
        return $this->persist($request, $questionario);
    }

    public function duplicate(Questionario $questionario)
    {
        DB::transaction(function () use ($questionario) {
            $questionario->load('perguntas');
            $novo = $questionario->replicate();
            $novo->titulo = $questionario->titulo . ' (Cópia)';
            $novo->save();

            foreach ($questionario->perguntas as $pergunta) {
                $novaPergunta = $pergunta->replicate();
                $novaPergunta->questionario_id = $novo->id;
                $novaPergunta->save();
            }
        });

        return redirect()->route('crm.questionarios.index')->with('success', 'Questionário duplicado com sucesso.');
    }

    public function destroy(Questionario $questionario)
    {
        $questionario->delete();
        return redirect()->route('crm.questionarios.index')->with('success', 'Questionário removido com sucesso.');
    }

    private function persist(Request $request, Questionario $questionario)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'titulo' => 'required|string|max:255',
                'incluir_cabecalho' => 'nullable|boolean',
                'incluir_rodape' => 'nullable|boolean',
                'exibir_na_os_digital' => 'nullable|boolean',
                'perguntas_mesma_linha' => 'required|integer|min:1|max:4',
                'exibir_pergunta_resposta_mesma_linha' => 'nullable|boolean',
                'exibir_nao_respondidas_relatorio' => 'nullable|boolean',
                'questionario_pmoc' => 'nullable|boolean',
                'habilitar_resposta_equipamento' => 'nullable|boolean',
                'perguntas' => 'required|array|min:1',
                'perguntas.*.texto' => 'required|string|max:1000',
                'perguntas.*.tipo_resposta' => 'required|string|in:texto,textarea,numero,data,sim_nao,selecionar',
                'perguntas.*.opcoes' => 'nullable|string|max:5000',
                'perguntas.*.resposta_obrigatoria' => 'nullable|boolean',
                'perguntas.*.descricao_pergunta' => 'nullable|boolean',
            ],
            [
                'perguntas.required' => 'Adicione pelo menos uma pergunta ao questionário.',
                'perguntas.min' => 'Adicione pelo menos uma pergunta ao questionário.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::transaction(function () use ($request, $questionario, $validator) {
            $data = $validator->validated();

            $questionario->fill([
                'titulo' => $data['titulo'],
                'incluir_cabecalho' => (bool) ($request->boolean('incluir_cabecalho')),
                'incluir_rodape' => (bool) ($request->boolean('incluir_rodape')),
                'exibir_na_os_digital' => (bool) ($request->boolean('exibir_na_os_digital')),
                'perguntas_mesma_linha' => $data['perguntas_mesma_linha'],
                'exibir_pergunta_resposta_mesma_linha' => (bool) ($request->boolean('exibir_pergunta_resposta_mesma_linha')),
                'exibir_nao_respondidas_relatorio' => (bool) ($request->boolean('exibir_nao_respondidas_relatorio')),
                'questionario_pmoc' => (bool) ($request->boolean('questionario_pmoc')),
                'habilitar_resposta_equipamento' => (bool) ($request->boolean('habilitar_resposta_equipamento')),
            ]);
            $questionario->save();

            $questionario->perguntas()->delete();

            $ordem = 1;
            foreach (($data['perguntas'] ?? []) as $pergunta) {
                $opcoes = null;
                if (($pergunta['tipo_resposta'] ?? null) === 'selecionar' && !empty($pergunta['opcoes'])) {
                    $linhas = preg_split('/\r\n|\r|\n/', (string) $pergunta['opcoes']);
                    $linhas = array_values(array_filter(array_map('trim', $linhas)));
                    $opcoes = !empty($linhas) ? $linhas : null;
                }

                QuestionarioPergunta::create([
                    'questionario_id' => $questionario->id,
                    'ordem' => $ordem++,
                    'texto' => $pergunta['texto'],
                    'tipo_resposta' => $pergunta['tipo_resposta'],
                    'opcoes' => $opcoes,
                    'resposta_obrigatoria' => !empty($pergunta['resposta_obrigatoria']),
                    'descricao_pergunta' => !empty($pergunta['descricao_pergunta']),
                ]);
            }
        });

        return redirect()->route('crm.questionarios.index')->with(
            'success',
            $questionario->wasRecentlyCreated ? 'Questionário criado com sucesso.' : 'Questionário atualizado com sucesso.'
        );
    }
}
