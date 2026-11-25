@extends('layouts.app')

@section('titulo', 'Inscrição de produtores')

@section('conteudo')
    @if ($errors->any())
        <div class="erros">
            <ul>
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('inscricao.store') }}">
        @csrf

        <label for="nome">Nome:</label>
        <input id="nome" name="nome" value="{{ old('nome') }}">
        @error('nome') <span class="erros">{{ $message }}</span> @enderror

        <label for="email">E-mail:</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}">
        @error('email') <span class="erros">{{ $message }}</span> @enderror

        <label for="produto">Produto:</label>
        <input id="produto" name="produto" value="{{ old('produto') }}">
        @error('produto') <span class="erros">{{ $message }}</span> @enderror

        <label for="quantidade">Quantidade:</label>
        <input id="quantidade" name="quantidade" type="number" value="{{ old('quantidade') }}">
        @error('quantidade') <span class="erros">{{ $message }}</span> @enderror

        <label for="telefone">Telefone (opcional):</label>
        <input id="telefone" name="telefone" value="{{ old('telefone') }}">
        @error('telefone') <span class="erros">{{ $message }}</span> @enderror

        <p><button type="submit">Enviar inscrição</button></p>
    </form>
@endsection
