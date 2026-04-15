<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PropostaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PlantaBaixaController;
use App\Http\Controllers\EquipamentoController;
use App\Http\Controllers\ColaboradorController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\PrecificacaoController;
use App\Http\Controllers\AgendaRelatorioController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\QuestionarioController;
use App\Http\Controllers\EstoqueController;
use App\Http\Controllers\FinanceiroController;
use App\Http\Controllers\ColaboradorPortalController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // CRM - Funil de Vendas (técnicos: apenas rotas permitidas pelo middleware tecnico.portal)
    Route::prefix('crm')->middleware('tecnico.portal')->name('crm.')->group(function () {
        Route::post('/colaborador/servicos/{servico}/iniciar', [ColaboradorPortalController::class, 'iniciarServico'])->name('colaborador.servicos.iniciar');
        Route::get('/colaborador/servicos/{servico}/execucao', [ColaboradorPortalController::class, 'execucao'])->name('colaborador.execucao');
        Route::get('/funil', [PropostaController::class, 'index'])->name('funil');
        Route::get('/propostas/nova', [PropostaController::class, 'create'])->name('nova-proposta');
        Route::post('/propostas', [PropostaController::class, 'store'])->name('propostas.store');
        Route::get('/propostas/{proposta}/acompanhamentos', [PropostaController::class, 'getAcompanhamentos'])->name('propostas.acompanhamentos');
        Route::post('/propostas/{proposta}/acompanhamentos', [PropostaController::class, 'storeAcompanhamento'])->name('propostas.acompanhamentos.store');
        Route::get('/propostas/{proposta}/pdf', [PropostaController::class, 'pdf'])->name('propostas.pdf');
        Route::get('/propostas/{proposta}', [PropostaController::class, 'show'])->name('propostas.show');
        Route::match(['put', 'post'], '/propostas/{proposta}', [PropostaController::class, 'update'])->name('propostas.update');
        Route::delete('/propostas/{proposta}', [PropostaController::class, 'destroy'])->name('propostas.destroy');
        
        // Clientes
        Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
        // Rotas específicas ANTES da rota dinâmica
        Route::get('/clientes/search', [ClienteController::class, 'search'])->name('clientes.search');
        Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
        Route::match(['put', 'post'], '/clientes/{cliente}', [ClienteController::class, 'update'])->name('clientes.update');
        Route::delete('/clientes/{cliente}', [ClienteController::class, 'destroy'])->name('clientes.destroy');
        Route::get('/clientes/{cliente}', [ClienteController::class, 'show'])->name('clientes.show');

        // Plantas baixas - rotas mais específicas antes de /plantas/{planta}
        Route::get('/plantas/{planta}/imagem', [PlantaBaixaController::class, 'serveImagem'])->name('plantas.imagem');
        Route::get('/plantas/{planta}/marcadores', [PlantaBaixaController::class, 'marcadores'])->name('plantas.marcadores');
        Route::post('/plantas/{planta}/marcadores', [PlantaBaixaController::class, 'storeMarcador'])->name('plantas.marcadores.store');
        Route::delete('/plantas/{planta}', [PlantaBaixaController::class, 'destroy'])->name('plantas.destroy');
        Route::get('/plantas/{planta}', [PlantaBaixaController::class, 'show'])->name('plantas.show');
        Route::match(['put', 'patch'], '/plantas/marcadores/{marcador}', [PlantaBaixaController::class, 'updateMarcador'])->name('plantas.marcadores.update');
        Route::delete('/plantas/marcadores/{marcador}', [PlantaBaixaController::class, 'destroyMarcador'])->name('plantas.marcadores.destroy');
        
        // Equipamentos
        Route::get('/equipamentos', [EquipamentoController::class, 'index'])->name('equipamentos.index');
        Route::post('/equipamentos', [EquipamentoController::class, 'store'])->name('equipamentos.store');
        Route::match(['put', 'post'], '/equipamentos/{equipamento}', [EquipamentoController::class, 'update'])->name('equipamentos.update');
        Route::delete('/equipamentos/{equipamento}', [EquipamentoController::class, 'destroy'])->name('equipamentos.destroy');
        
        // Colaboradores
        Route::get('/colaboradores', [ColaboradorController::class, 'index'])->name('colaboradores.index');
        Route::post('/colaboradores', [ColaboradorController::class, 'store'])->name('colaboradores.store');
        Route::get('/colaboradores/{colaborador}/details', [ColaboradorController::class, 'details'])->name('colaboradores.details');
        Route::get('/colaboradores/{colaborador}', [ColaboradorController::class, 'show'])->name('colaboradores.show');
        Route::match(['put', 'post'], '/colaboradores/{colaborador}', [ColaboradorController::class, 'update'])->name('colaboradores.update');
        Route::delete('/colaboradores/{colaborador}', [ColaboradorController::class, 'destroy'])->name('colaboradores.destroy');
            Route::post('/colaboradores/{colaborador}/documentos', [ColaboradorController::class, 'storeDocumento'])->name('colaboradores.documentos.store');
            Route::patch('/colaboradores/documentos/{documento}/certificacao', [ColaboradorController::class, 'updateDocumentoCertificacao'])->name('colaboradores.documentos.certificacao');
            Route::post('/colaboradores/{colaborador}/pastas', [ColaboradorController::class, 'criarPasta'])->name('colaboradores.pastas.store');
            Route::delete('/colaboradores/documentos/{documento}', [ColaboradorController::class, 'destroyDocumento'])->name('colaboradores.documentos.destroy');
            Route::post('/colaboradores/documentos/{documento}/mover', [ColaboradorController::class, 'moverDocumento'])->name('colaboradores.documentos.move');
            Route::post('/colaboradores/{colaborador}/pastas/renomear', [ColaboradorController::class, 'renomearPasta'])->name('colaboradores.pastas.rename');
            Route::get('/colaboradores/documentos/{documento}/arquivo', [ColaboradorController::class, 'visualizarDocumento'])->name('colaboradores.documentos.view');

        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/novo', [UsuarioController::class, 'create'])->name('usuarios.create');
        Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
        Route::get('/usuarios/{user}/editar', [UsuarioController::class, 'edit'])->name('usuarios.edit');
        Route::match(['put', 'post'], '/usuarios/{user}', [UsuarioController::class, 'update'])->name('usuarios.update');
        Route::delete('/usuarios/{user}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
        
        // Serviços - Rotas específicas devem vir ANTES das rotas com parâmetros dinâmicos
        Route::get('/servicos', [ServicoController::class, 'index'])->name('servicos.index');
        Route::get('/servicos/equipamentos', [ServicoController::class, 'getEquipamentos'])->name('servicos.equipamentos');
        Route::get('/servicos/tecnicos', [ServicoController::class, 'getTecnicos'])->name('servicos.tecnicos');
        Route::post('/servicos', [ServicoController::class, 'store'])->name('servicos.store');
        Route::get('/servicos/{servico}/relatorio', [ServicoController::class, 'relatorio'])->name('servicos.relatorio');
        Route::get('/servicos/{servico}/relatorio/pdf', [ServicoController::class, 'relatorioPdf'])->name('servicos.relatorio.pdf');
        Route::get('/servicos/{servico}/preencher-relatorio', [ServicoController::class, 'preencherRelatorio'])->name('servicos.preencher-relatorio');
        Route::post('/servicos/{servico}/salvar-relatorio', [ServicoController::class, 'salvarRelatorio'])->name('servicos.salvar-relatorio');
        Route::get('/agenda', [AgendaRelatorioController::class, 'index'])->name('agenda');
        Route::get('/relatorios/agenda', fn () => redirect()->route('crm.agenda')->setStatusCode(301));
        Route::get('/relatorios', [RelatorioController::class, 'index'])->name('relatorios.index');
        Route::get('/relatorios/{servico}', [ServicoController::class, 'relatorioAtendimento'])->name('relatorios.show');
        Route::post('/relatorios/{servico}/detalhes', [ServicoController::class, 'salvarDetalhesAtendimento'])->name('relatorios.detalhes');
        Route::post('/relatorios/{servico}/relato', [ServicoController::class, 'salvarRelatoAtendimento'])->name('relatorios.relato');
        Route::post('/relatorios/{servico}/anexos', [ServicoController::class, 'uploadAnexosAtendimento'])->name('relatorios.anexos');
        Route::delete('/relatorios/{servico}/anexos/{anexo}', [ServicoController::class, 'removerAnexoAtendimento'])->name('relatorios.anexos.destroy');
        Route::post('/relatorios/{servico}/horas', [ServicoController::class, 'adicionarHoraAtendimento'])->name('relatorios.horas');
        Route::post('/relatorios/{servico}/questionarios/vincular', [ServicoController::class, 'vincularQuestionarioAtendimento'])->name('relatorios.questionarios.vincular');
        Route::post('/relatorios/{servico}/questionarios/{vinculo}/respostas', [ServicoController::class, 'salvarRespostasQuestionarioAtendimento'])->name('relatorios.questionarios.respostas');
        Route::get('/servicos/{servico}', [ServicoController::class, 'show'])->name('servicos.show');
        Route::match(['put', 'post'], '/servicos/{servico}', [ServicoController::class, 'update'])->name('servicos.update');
        Route::delete('/servicos/{servico}', [ServicoController::class, 'destroy'])->name('servicos.destroy');

        // Biblioteca de questionários
        Route::get('/questionarios', [QuestionarioController::class, 'index'])->name('questionarios.index');
        Route::get('/questionarios/novo', [QuestionarioController::class, 'create'])->name('questionarios.create');
        Route::post('/questionarios', [QuestionarioController::class, 'store'])->name('questionarios.store');
        Route::get('/questionarios/{questionario}/editar', [QuestionarioController::class, 'edit'])->name('questionarios.edit');
        Route::match(['put', 'post'], '/questionarios/{questionario}', [QuestionarioController::class, 'update'])->name('questionarios.update');
        Route::post('/questionarios/{questionario}/duplicar', [QuestionarioController::class, 'duplicate'])->name('questionarios.duplicate');
        Route::delete('/questionarios/{questionario}', [QuestionarioController::class, 'destroy'])->name('questionarios.destroy');
        
        // Precificação
        Route::get('/precificacao', [PrecificacaoController::class, 'index'])->name('precificacao.index');
        Route::post('/precificacao/calcular', [PrecificacaoController::class, 'calcular'])->name('precificacao.calcular');
        Route::post('/precificacao/parametros', [PrecificacaoController::class, 'updateParametros'])->name('precificacao.parametros.update');
        Route::post('/precificacao/enviar-funil', [PrecificacaoController::class, 'enviarParaFunil'])->name('precificacao.enviar-funil');

        // Financeiro
        Route::get('/financeiro/dashboard', [FinanceiroController::class, 'dashboard'])->name('financeiro.dashboard');
        Route::get('/financeiro/contas-pagar', [FinanceiroController::class, 'contasPagar'])->name('financeiro.contas-pagar.index');
        Route::post('/financeiro/contas-pagar', [FinanceiroController::class, 'storeContaPagar'])->name('financeiro.contas-pagar.store');
        Route::match(['put', 'post'], '/financeiro/contas-pagar/{contaPagar}', [FinanceiroController::class, 'updateContaPagar'])->name('financeiro.contas-pagar.update');
        Route::post('/financeiro/contas-pagar/{contaPagar}/baixar', [FinanceiroController::class, 'baixarContaPagar'])->name('financeiro.contas-pagar.baixar');
        Route::delete('/financeiro/contas-pagar/{contaPagar}', [FinanceiroController::class, 'destroyContaPagar'])->name('financeiro.contas-pagar.destroy');

        Route::get('/financeiro/contas-receber', [FinanceiroController::class, 'contasReceber'])->name('financeiro.contas-receber.index');
        Route::post('/financeiro/contas-receber', [FinanceiroController::class, 'storeContaReceber'])->name('financeiro.contas-receber.store');
        Route::match(['put', 'post'], '/financeiro/contas-receber/{contaReceber}', [FinanceiroController::class, 'updateContaReceber'])->name('financeiro.contas-receber.update');
        Route::post('/financeiro/contas-receber/{contaReceber}/baixar', [FinanceiroController::class, 'baixarContaReceber'])->name('financeiro.contas-receber.baixar');
        Route::delete('/financeiro/contas-receber/{contaReceber}', [FinanceiroController::class, 'destroyContaReceber'])->name('financeiro.contas-receber.destroy');

        Route::get('/financeiro/dre', [FinanceiroController::class, 'dre'])->name('financeiro.dre');

        // Estoque - itens com estoque baixo (filtrar por fornecedor, PDF, WhatsApp)
        Route::get('/financeiro/estoque-baixo', [EstoqueController::class, 'estoqueBaixo'])->name('financeiro.estoque.baixo');
        Route::get('/financeiro/estoque-baixo/pdf', [EstoqueController::class, 'estoqueBaixoPdf'])->name('financeiro.estoque.baixo.pdf');
        Route::get('/financeiro/estoque-itens', [EstoqueController::class, 'index'])->name('financeiro.estoque.itens');
        Route::post('/financeiro/estoque-itens', [EstoqueController::class, 'store'])->name('financeiro.estoque.itens.store');
        Route::match(['put', 'post'], '/financeiro/estoque-itens/{itemEstoque}', [EstoqueController::class, 'update'])->name('financeiro.estoque.itens.update');
        Route::delete('/financeiro/estoque-itens/{itemEstoque}', [EstoqueController::class, 'destroy'])->name('financeiro.estoque.itens.destroy');
    });
});
