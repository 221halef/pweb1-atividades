<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class ProdutoController extends Controller
{
    private function produtos(): array
    {
        return [
            1 => [
                'nome' => 'Leite de cabra',
                'preco' => 12.0,
                'categoria' => 'laticinios',
                'estoque' => 20,
                'descricao' => 'Leite fresco de cabra da feira.',
            ],
            2 => [
                'nome' => 'Queijo coalho',
                'preco' => 15.0,
                'categoria' => 'laticinios',
                'estoque' => 0,
                'descricao' => 'Queijo coalho artesanal.',
            ],
            3 => [
                'nome' => 'Doce de leite',
                'preco' => 10.0,
                'categoria' => 'doces',
                'estoque' => 8,
                'descricao' => 'Doce de leite caseiro.',
            ],
            4 => [
                'nome' => 'Mel de abelha',
                'preco' => 22.0,
                'categoria' => 'mel',
                'estoque' => 15,
                'descricao' => 'Mel puro de abelha sem ferrão.',
            ],
            5 => [
                'nome' => 'Bolo de cenoura',
                'preco' => 9.0,
                'categoria' => 'doces',
                'estoque' => 6,
                'descricao' => 'Bolo de cenoura com cobertura de chocolate.',
            ],
        ];
    }

    public function index(): View
    {
        $produtos = $this->produtos();

        return view('produtos.index', ['produtos' => $produtos]);
    }

    public function show(int $id): View
    {
        $produtos = $this->produtos();

        abort_unless(isset($produtos[$id]), 404, 'Produto não encontrado');

        return view('produtos.show', [
            'produto' => $produtos[$id],
            'id' => $id,
        ]);
    }

    public function showJson(int $id): JsonResponse
    {
        $produtos = $this->produtos();

        if (! isset($produtos[$id])) {
            return response()->json(['erro' => "Produto {$id} não encontrado"], 404);
        }

        return response()->json(['id' => $id] + $produtos[$id]);
    }

    public function categoria(string $categoria): View
    {
        $filtrados = array_filter(
            $this->produtos(),
            fn ($produto) => $produto['categoria'] === $categoria
        );

        abort_if(empty($filtrados), 404, 'Categoria não encontrada');

        return view('produtos.index', [
            'produtos' => $filtrados,
            'categoria' => $categoria,
        ]);
    }
}
