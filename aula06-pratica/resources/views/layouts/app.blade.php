<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', 'Feira Digital')</title>
    <style>
        body { font-family: sans-serif; margin: 2rem auto; max-width: 48rem; line-height: 1.5; }
        nav a { margin-right: 1rem; }
        .erros { color: #b91c1c; }
        .sucesso { color: #15803d; }
        label { display: block; margin-top: .75rem; }
        input { display: block; width: 20rem; max-width: 100%; }
    </style>
</head>
<body>
    @include('partials.menu')

    <h1>@yield('titulo', 'Feira Digital')</h1>

    @if (session('mensagem'))
        <p class="sucesso">{{ session('mensagem') }}</p>
    @endif

    <main>
        @yield('conteudo', $conteudo ?? '')
    </main>
</body>
</html>
