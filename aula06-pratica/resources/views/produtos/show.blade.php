@extends('layouts.app')

@section('titulo', $produto['nome'])

@section('conteudo')
    <p><strong>Categoria:</strong> {{ $produto['categoria'] }}</p>
    <p><strong>Estoque:</strong> {{ $produto['estoque'] }}</p>
    <p><strong>Preço:</strong> R$ {{ number_format($produto['preco'], 2, ',', '.') }}</p>
    <p>{{ $produto['descricao'] }}</p>
    <p><a href="{{ route('produtos.index') }}">Voltar aos produtos</a></p>
@endsection
