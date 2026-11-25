@extends('layouts.app')

@section('titulo', 'Cardápio do dia')

@section('conteudo')
    <p>Quantidade de pratos: {{ count($pratos) }}</p>

    @forelse ($pratos as $prato)
        <ul>
            <li>{{ $prato['nome'] }} — R$ {{ number_format($prato['preco'], 2, ',', '.') }}</li>
        </ul>
    @empty
        <p>Cardápio do dia ainda não definido.</p>
    @endforelse
@endsection
