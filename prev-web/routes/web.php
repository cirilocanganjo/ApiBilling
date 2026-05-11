<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\UnidadeController;
use App\Http\Controllers\ProdutosController;
use App\Http\Controllers\VendaController;
use App\Http\Controllers\VendaItemController;
use App\Http\Controllers\PagamentoController;
use App\Http\Controllers\CaixaController;
use App\Http\Controllers\MovimentoEstoqueController;
use App\Http\Controllers\LicencaController;
use App\Http\Controllers\PermissaoController;
use App\Http\Controllers\UsuarioPermissaoController;
use App\Http\Controllers\LogAuditoriaController;
use App\Http\Controllers\SuppliersController;
use App\Livewire\Auth\AuthComponent;

// ============================================================
// LOGIN E AUTENTICAÇÃO
// ============================================================
Route::get('/', AuthComponent::class)->name('app.login');


// ============================================================
// FORNECEDOR
// ============================================================
Route::resource('suppliers', SuppliersController::class);


// ============================================================
// DASHBOARD
// ============================================================
Route::get('/dashboard', function () {
    return view('dashboard.index'); // AdminLTE layout
})->name('dashboard.index');

// ============================================================
// EMPRESAS
// ============================================================
Route::resource('empresas', EmpresaController::class);

// ============================================================
// USUÁRIOS
// ============================================================
Route::resource('usuarios', UsuarioController::class);

// ============================================================
// CLIENTES
// ============================================================
//  Route::resource('clients', ClientsController::class);
// // web.php
// Route::get('clients/form', [ClienteController::class, 'create'])
//     ->name('clients.form');

// ============================================================
// CATEGORIAS
// ============================================================
/* Route::resource('categories', CategoriesController::class); */
//Deste jeito permite usar encrypt na url
Route::get('categories', [CategoriesController::class, 'index'])->name('categories.index');
Route::get('categories/create', [CategoriesController::class, 'create'])->name('categories.create');
Route::post('categories', [CategoriesController::class, 'store'])->name('categories.store');

Route::get('categories/{id}/edit', [CategoriesController::class, 'edit'])->name('categories.edit');
Route::put('categories/{id}', [CategoriesController::class, 'update'])->name('categories.update');
Route::delete('categories/{id}', [CategoriesController::class, 'destroy'])->name('categories.destroy');


// ============================================================
// MARCAS
// ============================================================
Route::resource('marcas', MarcaController::class);

// ============================================================
// UNIDADES
// ============================================================
Route::resource('unidades', UnidadeController::class);

// ============================================================
// PRODUTOS
// ============================================================
// Resource padrão para produtos
Route::resource('produtos', ProdutosController::class);

// Rotas auxiliares que não fazem parte do resource
Route::prefix('produtos')->group(function () {
    Route::get('etiquetas', fn() => view('produtos.etiquetas'))->name('produtos.etiquetas');
    Route::get('fornecedores', fn() => view('produtos.fornecedores'))->name('produtos.fornecedores');
    Route::get('taxas', fn() => view('produtos.taxas'))->name('produtos.taxas');
    Route::get('reajustes', fn() => view('produtos.reajustes'))->name('produtos.reajustes');
});

// ============================================================
// VENDAS E ITENS
// ============================================================
Route::resource('vendas', VendaController::class);
Route::resource('venda_itens', VendaItemController::class);

// ============================================================
// PAGAMENTOS
// ============================================================
Route::resource('pagamentos', PagamentoController::class);

// ============================================================
// CAIXAS
// ============================================================
Route::resource('caixas', CaixaController::class);

// ============================================================
// MOVIMENTOS DE ESTOQUE
// ============================================================
Route::resource('movimentos_estoque', MovimentoEstoqueController::class);

// ============================================================
// LICENÇAS
// ============================================================
Route::resource('licencas', LicencaController::class);

// ============================================================
// PERMISSÕES
// ============================================================
Route::resource('permissoes', PermissaoController::class);
Route::resource('usuario_permissoes', UsuarioPermissaoController::class);

// ============================================================
// LOGS DE AUDITORIA
// ============================================================
Route::resource('logs_auditoria', LogAuditoriaController::class);



/* Route::prefix('clients')->group(function () {
    Route::get('index', fn() => view('clients.index'))->name('clients.index');
    Route::get('create', fn() => view('clients.form'))->name('clients.form');
}); */


Route::prefix('vendas')->group(function () {
    Route::get('historico', fn() => view('vendas.historico'))->name('vendas.historico');
    Route::get('aberto', fn() => view('vendas.aberto'))->name('vendas.aberto');
    Route::get('online', fn() => view('vendas.online'))->name('vendas.online');
    Route::get('orcamento', fn() => view('vendas.orcamento'))->name('vendas.orcamento');
});

Route::prefix('estoque')->group(function () {
    Route::get('produto', fn() => view('estoque.produto'))->name('estoque.produto');
    Route::get('transacoes', fn() => view('estoque.transacoes'))->name('estoque.transacoes');
    Route::get('validade', fn() => view('estoque.validade'))->name('estoque.validade');
});

Route::get('contas/pagar', fn() => view('contas.pagar'))->name('contas.pagar');

Route::prefix('caixas')->group(function () {
    Route::get('atual', fn() => view('caixas.atual'))->name('caixas.atual');
    Route::get('anterior', fn() => view('caixas.anterior'))->name('caixas.anterior');
    Route::get('meios', fn() => view('caixas.meios'))->name('caixas.meios');
    Route::get('contador', fn() => view('caixas.contador'))->name('caixas.contador');
});

Route::prefix('relatorios')->group(function () {
    Route::get('/', fn() => view('relatorios.index'))->name('relatorios.index');
});

Route::get('/check/server/status', fn () => response()->noContent()); // Rota de verificação de status do servidor

require __DIR__.'/dashboard/routes.php';
require __DIR__.'/suppliers/routes.php';
require __DIR__.'/clients/routes.php';
require __DIR__.'/products/routes.php';
require __DIR__.'/categories/routes.php';
require __DIR__.'/subcategories/routes.php';
require __DIR__.'/units/routes.php';
require __DIR__.'/users/routes.php';
