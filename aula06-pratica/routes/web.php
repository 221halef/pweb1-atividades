<?php

use App\Http\Controllers\CardapioController;
use App\Http\Controllers\InscricaoController;
use App\Http\Controllers\ProdutoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('inicio', [
        'titulo' => 'Feira Digital',
    ]);
})->name('inicio');

Route::get('/admin', function () {
    return response('Área restrita', 403);
});

Route::get('/em-breve', function () {
    return response('Página em construção', 503);
});

Route::get('/loja', function () {
    return redirect('/feira');
});

Route::get('/produtos-antigos', function () {
    return redirect('/produtos', 301);
});

Route::get('/feira', function () {
    return response('Bem-vindo à feira', 200);
});

Route::get('/tabuada/{numero}', function (int $numero) {
    $html = '';
    for ($i = 1; $i <= 10; $i++) {
        $html .= "{$numero} x {$i} = " . ($numero * $i) . '<br>';
    }
    return $html;
})->whereNumber('numero');

Route::get('/idade/{ano}', function (int $ano) {
    $idade = (int) date('Y') - $ano;
    return "Quem nasceu em {$ano} tem ou vai fazer {$idade} anos em " . date('Y') . '.';
})->whereNumber('ano');

Route::get('/boas-vindas-turma/{turma?}', function (string $turma = 'ADS') {
    return "Bem-vinda, turma de {$turma}!";
});

Route::get('/meu-nome', function () {
    return 'Meu nome é Halef e estudo ADS no IFCE.';
});

$perfil = function (): array {
    return [
        'nome' => 'Halef',
        'semestre' => 4,
        'media' => 8.5,
        'bolsista' => true,
        'disciplinas' => ['Programação Web', 'Banco de Dados', 'Redes'],
        'endereco' => ['cidade' => 'Fortaleza', 'uf' => 'CE'],
    ];
};

Route::get('/perfil-json', function () use ($perfil) {
    return response()->json($perfil());
});

Route::get('/perfil-criado', function () use ($perfil) {
    return response()->json($perfil(), 201);
});

Route::get('/cartao', function () {
    return '
        <h2>Halef</h2>
        <p>Semestre: 4º</p>
        <ul>
            <li>Programação web</li>
            <li>Música</li>
            <li>Café</li>
        </ul>
    ';
});

Route::get('/inscricao', [InscricaoController::class, 'create'])->name('inscricao.create');
Route::post('/inscricao', [InscricaoController::class, 'store'])->name('inscricao.store');

Route::get('/quem-somos', function () {
    return response('Sobre a Feira Digital', 200)->header('Content-Type', 'text/plain');
})->name('sobre');

Route::get('/contato', function () {
    return response('Contato: contato@feira.test', 200)->header('Content-Type', 'text/plain');
})->name('contato');

Route::get('/cardapio-do-dia', [CardapioController::class, 'index'])->name('cardapio');

Route::get('/produtos', [ProdutoController::class, 'index'])->name('produtos.index');

Route::get('/produtos/categoria/{categoria}', [ProdutoController::class, 'categoria'])
    ->whereAlpha('categoria')
    ->name('produtos.categoria');

Route::get('/produtos/{id}/json', [ProdutoController::class, 'showJson'])
    ->whereNumber('id')
    ->name('produtos.show-json');

Route::get('/produtos/{id}', [ProdutoController::class, 'show'])
    ->whereNumber('id')
    ->name('produtos.show');

Route::fallback(function () {
    return response('Página não encontrada', 404)->header('Content-Type', 'text/plain');
});
