<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome', [
        'titulo' => 'Feira Digital',
        'conteudo' => '<p>Bem-vindo à Feira Digital.</p>',
    ]);
})->name('inicio');

Route::get('/sobre', function () {
    return response('Sobre a Feira Digital', 200)->header('Content-Type', 'text/plain');
})->name('sobre');

Route::get('/contato', function () {
    return response('Contato: contato@feira.test', 200)->header('Content-Type', 'text/plain');
})->name('contato');

Route::get('/cardapio', function () {
    return view('cardapio', ['pratos' => []]);
})->name('cardapio');

Route::get('/produtos', function () {
    return response('Produtos da feira', 200)->header('Content-Type', 'text/plain');
})->name('produtos.index');

Route::fallback(function () {
    return response('Página não encontrada', 404)->header('Content-Type', 'text/plain');
});
