<?php

namespace App\Http\Controllers;

use App\Models\Colaborador;
use App\Models\ColaboradorDocumento;
use App\Models\ColaboradorPasta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class ColaboradorController extends Controller
{
    public function index(Request $request)
    {
        $diasCert = (int) config('colaboradores.certificacao_dias_alerta', 30);

        $query = Colaborador::withCount('documentos')->with([
            'documentos' => static function ($q) {
                $q->select('id', 'colaborador_id', 'data_vencimento')
                    ->whereNotNull('data_vencimento');
            },
        ]);

        // Busca
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nome_profissional', 'like', "%{$search}%")
                    ->orWhere('cpf', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $colaboradores = $query->orderBy('nome_profissional')->paginate(20);

        $colaboradores->getCollection()->transform(static function ($c) use ($diasCert) {
            $c->setAttribute(
                'certificacao_alerta_nivel',
                ColaboradorDocumento::nivelPiorCertificacao($c->documentos, $diasCert)
            );

            return $c;
        });

        return view('crm.colaboradores', compact('colaboradores', 'diasCert'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome_profissional' => 'required|string|max:255',
            'departamento' => 'nullable|in:operacional,comercial,administrativo,tecnico,outro',
            'valor_hora' => 'nullable|numeric|min:0',
            'ativo' => 'boolean',
            'cpf' => 'nullable|string|max:14|unique:colaboradores,cpf',
            'telefone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'observacoes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            throw new ValidationException($validator);
        }

        $data = $validator->validated();
        $data['departamento'] = $data['departamento'] ?? 'operacional';
        // Processar campo ativo - pode vir como '1', '0', true, false ou não vir
        $data['ativo'] = $request->has('ativo') && ($request->input('ativo') === '1' || $request->input('ativo') === true || $request->boolean('ativo'));

        $colaborador = Colaborador::create($data);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'colaborador' => [
                    'id' => $colaborador->id,
                    'documentos' => $colaborador->documentos,
                ],
                'message' => 'Colaborador cadastrado com sucesso!'
            ]);
        }

        return redirect()->route('crm.colaboradores.index')->with('success', 'Colaborador cadastrado com sucesso!');
    }

    public function show(Colaborador $colaborador)
    {
        $colaborador->load('documentos');

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'colaborador' => $colaborador,
            ]);
        }

        return redirect()->route('crm.colaboradores.index');
    }

    public function details(Colaborador $colaborador)
    {
        $colaborador->load(['documentos', 'pastas']);
        $diasAlerta = (int) config('colaboradores.certificacao_dias_alerta', 30);

        return view('crm.details_colaborador', compact('colaborador', 'diasAlerta'));
    }

    public function updateDocumentoCertificacao(Request $request, ColaboradorDocumento $documento)
    {
        $validator = Validator::make($request->all(), [
            'nome_documento' => 'nullable|string|max:255',
            'data_vencimento' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        if ($request->filled('nome_documento')) {
            $documento->nome_documento = Str::limit(trim((string) $data['nome_documento']), 255, '');
        }
        if ($request->exists('data_vencimento')) {
            $documento->data_vencimento = ! empty($data['data_vencimento'] ?? null)
                ? $data['data_vencimento']
                : null;
        }

        $documento->cert_proximo_alerta_em = null;
        $documento->cert_vencido_alerta_em = null;
        $documento->save();

        return response()->json([
            'success' => true,
            'message' => 'Certificação atualizada.',
            'documento' => $documento->fresh(),
        ]);
    }

    public function update(Request $request, Colaborador $colaborador)
    {
        $validator = Validator::make($request->all(), [
            'nome_profissional' => 'required|string|max:255',
            'departamento' => 'nullable|in:operacional,comercial,administrativo,tecnico,outro',
            'valor_hora' => 'nullable|numeric|min:0',
            'ativo' => 'boolean',
            'cpf' => 'nullable|string|max:14|unique:colaboradores,cpf,' . $colaborador->id,
            'telefone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'observacoes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            throw new ValidationException($validator);
        }

        $data = $validator->validated();
        $data['departamento'] = $data['departamento'] ?? ($colaborador->departamento ?: 'operacional');
        // Processar campo ativo - pode vir como '1', '0', true, false ou não vir
        $data['ativo'] = $request->has('ativo') && ($request->input('ativo') === '1' || $request->input('ativo') === true || $request->boolean('ativo'));

        $colaborador->update($data);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Colaborador atualizado com sucesso!'
            ]);
        }

        return redirect()->route('crm.colaboradores.index')->with('success', 'Colaborador atualizado com sucesso!');
    }

    public function destroy(Request $request, Colaborador $colaborador)
    {
        $colaborador->delete();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Colaborador excluído com sucesso!']);
        }

        return redirect()->route('crm.colaboradores.index')->with('success', 'Colaborador excluído com sucesso!');
    }

    public function storeDocumento(Request $request, Colaborador $colaborador)
    {
        $validator = Validator::make($request->all(), [
            'nome_documento' => 'nullable|string|max:255',
            'data_vencimento' => 'nullable|date',
            'arquivo' => 'nullable|file|max:10240',
            'arquivos' => 'nullable|array',
            'arquivos.*' => 'file|max:10240',
            'caminhos' => 'nullable|array',
            'caminhos.*' => 'nullable|string|max:500',
            'observacoes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        $nomeBase = trim((string) ($data['nome_documento'] ?? ''));
        $dataVencimento = $data['data_vencimento'] ?? null;
        $observacoes = $data['observacoes'] ?? null;
        $arquivos = [];
        $caminhos = $request->input('caminhos', []);

        if ($request->hasFile('arquivo') && $request->file('arquivo')->isValid()) {
            $arquivos[] = $request->file('arquivo');
        }
        if ($request->hasFile('arquivos')) {
            foreach ((array) $request->file('arquivos') as $arquivo) {
                if ($arquivo && $arquivo->isValid()) {
                    $arquivos[] = $arquivo;
                }
            }
        }

        $documentosCriados = [];

        if (count($arquivos) > 0) {
            foreach ($arquivos as $index => $arquivo) {
                $path = $arquivo->store('colaborador-documentos', 'public');
                $nomeArquivo = pathinfo($arquivo->getClientOriginalName(), PATHINFO_FILENAME);
                $caminhoRelativo = trim((string) ($caminhos[$index] ?? ''));
                if ($caminhoRelativo === '') {
                    $caminhoRelativo = $arquivo->getClientOriginalName();
                }
                $nomeDocumento = $nomeBase !== ''
                    ? (count($arquivos) === 1 ? $nomeBase : $nomeBase . ' - ' . $nomeArquivo)
                    : $nomeArquivo;

                $documentosCriados[] = $colaborador->documentos()->create([
                    'nome_documento' => Str::limit($nomeDocumento, 255, ''),
                    'data_vencimento' => $dataVencimento,
                    'arquivo_path' => $path,
                    'arquivo_nome_original' => $arquivo->getClientOriginalName(),
                    'arquivo_mime' => $arquivo->getClientMimeType(),
                    'arquivo_tamanho' => $arquivo->getSize(),
                    'caminho_relativo' => Str::limit(str_replace('\\', '/', $caminhoRelativo), 500, ''),
                    'observacoes' => $observacoes,
                ]);

                $pastaArquivo = trim((string) pathinfo($caminhoRelativo, PATHINFO_DIRNAME), '/');
                if ($pastaArquivo !== '' && $pastaArquivo !== '.') {
                    $this->ensureFolderHierarchy($colaborador->id, $pastaArquivo);
                }
            }
        } else {
            if ($nomeBase === '') {
                return response()->json([
                    'success' => false,
                    'message' => 'Informe o nome do documento ou selecione ao menos um arquivo.'
                ], 422);
            }

            $documentosCriados[] = $colaborador->documentos()->create([
                'nome_documento' => Str::limit($nomeBase, 255, ''),
                'data_vencimento' => $dataVencimento,
                'observacoes' => $observacoes,
            ]);
        }

        $documento = $documentosCriados[0];

        return response()->json([
            'success' => true,
            'message' => 'Documento registrado com sucesso!',
            'documento' => $documento,
            'documentos' => $documentosCriados,
            'arquivo_url' => $documento->arquivo_path ? Storage::url($documento->arquivo_path) : null,
        ]);
    }

    public function destroyDocumento(ColaboradorDocumento $documento)
    {
        if ($documento->arquivo_path) {
            Storage::disk('public')->delete($documento->arquivo_path);
        }
        
        $documento->delete();

        return response()->json(['success' => true, 'message' => 'Documento excluído com sucesso!']);
    }

    public function visualizarDocumento(ColaboradorDocumento $documento)
    {
        if (!$documento->arquivo_path || !Storage::disk('public')->exists($documento->arquivo_path)) {
            abort(404, 'Arquivo não encontrado.');
        }

        $arquivoPath = Storage::disk('public')->path($documento->arquivo_path);
        return response()->file($arquivoPath);
    }

    public function moverDocumento(Request $request, ColaboradorDocumento $documento)
    {
        $validator = Validator::make($request->all(), [
            'pasta_destino' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $pastaDestino = trim((string) $request->input('pasta_destino', ''));
        $pastaDestino = str_replace('\\', '/', $pastaDestino);
        $pastaDestino = trim($pastaDestino, '/');

        $nomeArquivo = $documento->arquivo_nome_original;
        if (!$nomeArquivo) {
            $nomeArquivo = $documento->arquivo_path ? basename($documento->arquivo_path) : ($documento->nome_documento ?: ('arquivo-' . $documento->id));
        }

        $novoCaminho = $pastaDestino !== '' ? ($pastaDestino . '/' . $nomeArquivo) : $nomeArquivo;
        $documento->caminho_relativo = Str::limit($novoCaminho, 500, '');
        $documento->save();

        if ($pastaDestino !== '') {
            $this->ensureFolderHierarchy($documento->colaborador_id, $pastaDestino);
        }

        return response()->json([
            'success' => true,
            'message' => 'Documento movido com sucesso.',
            'documento' => $documento,
        ]);
    }

    public function criarPasta(Request $request, Colaborador $colaborador)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'pasta_pai' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $nome = trim(str_replace(['\\', '/'], '', (string) $request->input('nome')));
        if ($nome === '') {
            return response()->json(['success' => false, 'message' => 'Nome da pasta inválido.'], 422);
        }

        $pastaPai = trim(str_replace('\\', '/', (string) $request->input('pasta_pai', '')), '/');
        $caminho = $pastaPai !== '' ? ($pastaPai . '/' . $nome) : $nome;

        $this->ensureFolderHierarchy($colaborador->id, $caminho);

        return response()->json([
            'success' => true,
            'message' => 'Pasta criada com sucesso.',
            'pastas' => $colaborador->pastas()->orderBy('caminho_relativo')->get(),
        ]);
    }

    public function renomearPasta(Request $request, Colaborador $colaborador)
    {
        $validator = Validator::make($request->all(), [
            'pasta_atual' => 'required|string|max:500',
            'pasta_nova' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $pastaAtual = trim(str_replace('\\', '/', (string) $request->input('pasta_atual')), '/');
        $pastaNova = trim(str_replace('\\', '/', (string) $request->input('pasta_nova')), '/');

        if ($pastaAtual === '' || $pastaNova === '') {
            return response()->json(['success' => false, 'message' => 'Pastas inválidas.'], 422);
        }

        $alterados = 0;
        $documentos = $colaborador->documentos()->get();

        foreach ($documentos as $documento) {
            $caminho = $documento->caminho_relativo ?: ($documento->arquivo_nome_original ?: '');
            $caminho = trim(str_replace('\\', '/', (string) $caminho), '/');
            if ($caminho === '') {
                continue;
            }

            if ($caminho === $pastaAtual || Str::startsWith($caminho, $pastaAtual . '/')) {
                $sufixo = ltrim((string) substr($caminho, strlen($pastaAtual)), '/');
                $novoCaminho = $pastaNova . ($sufixo !== '' ? '/' . $sufixo : '');
                $documento->caminho_relativo = Str::limit($novoCaminho, 500, '');
                $documento->save();
                $alterados++;
            }
        }

        $pastas = $colaborador->pastas()->get();
        foreach ($pastas as $pasta) {
            $caminho = trim(str_replace('\\', '/', (string) $pasta->caminho_relativo), '/');
            if ($caminho === '') {
                continue;
            }
            if ($caminho === $pastaAtual || Str::startsWith($caminho, $pastaAtual . '/')) {
                $sufixo = ltrim((string) substr($caminho, strlen($pastaAtual)), '/');
                $novoCaminho = $pastaNova . ($sufixo !== '' ? '/' . $sufixo : '');
                $pasta->caminho_relativo = Str::limit($novoCaminho, 500, '');
                $pasta->nome = basename($pasta->caminho_relativo);
                $pasta->save();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Pasta renomeada com sucesso.',
            'alterados' => $alterados,
            'documentos' => $colaborador->documentos()->get(),
            'pastas' => $colaborador->pastas()->orderBy('caminho_relativo')->get(),
        ]);
    }

    private function ensureFolderHierarchy(int $colaboradorId, string $folderPath): void
    {
        $folderPath = trim(str_replace('\\', '/', $folderPath), '/');
        if ($folderPath === '') {
            return;
        }

        $parts = array_values(array_filter(explode('/', $folderPath), fn($p) => trim($p) !== ''));
        $cursor = '';

        foreach ($parts as $part) {
            $cursor = $cursor === '' ? $part : ($cursor . '/' . $part);
            ColaboradorPasta::firstOrCreate(
                ['colaborador_id' => $colaboradorId, 'caminho_relativo' => $cursor],
                ['nome' => $part]
            );
        }
    }
}
