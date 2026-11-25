@extends('layouts.app')

@section('titulo', 'Produtos da feira' . (isset($categoria) ? " — {$categoria}" : ''))

@section('conteudo')
    <ul>
        @foreach ($produtos as $id => $produto)
            <li>
                <a href="{{ route('produtos.show', $id) }}">{{ $produto['nome'] }}</a>
                — R$ {{ number_format($produto['preco'], 2, ',', '.') }}
                @if ($produto['estoque'] === 0)
                    <strong>Esgotado</strong>
                @else
                    ({{ $produto['estoque'] }} em estoque)
                @endif
            </li>
        @endforeach
    </ul>
@endsection
