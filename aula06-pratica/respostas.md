# Aula 06: Laravel na prática

Nome: Halef

Projeto: `aula06-pratica` (Feira Digital)

## A01: Meu cartão de visita

### Saídas
```
$ curl.exe -i http://127.0.0.1:8000/meu-nome
HTTP/1.1 200 OK
Host: 127.0.0.1:8000
Connection: close
X-Powered-By: PHP/8.5.12
Content-Type: text/html; charset=utf-8
Cache-Control: no-cache, private
Date: Wed, 25 Nov 2026 22:24:33 GMT

Meu nome é Halef e estudo ADS no IFCE.

$ curl.exe -i http://127.0.0.1:8000/cartao
HTTP/1.1 200 OK
Host: 127.0.0.1:8000
Connection: close
X-Powered-By: PHP/8.5.12
Content-Type: text/html; charset=utf-8
Cache-Control: no-cache, private
Date: Wed, 25 Nov 2026 22:24:34 GMT


        <h2>Halef</h2>
        <p>Semestre: 4º</p>
        <ul>
            <li>Programação web</li>
            <li>Música</li>
            <li>Café</li>
        </ul>

$ curl.exe -i http://127.0.0.1:8000/meu-nome/
HTTP/1.1 200 OK
...
Content-Type: text/html; charset=utf-8

Meu nome é Halef e estudo ADS no IFCE.

$ curl.exe -i http://127.0.0.1:8000/Meu-Nome
HTTP/1.1 404 Not Found
...
Content-Type: text/plain; charset=utf-8

Página não encontrada
```

### Caminho da requisição
GET /cartao -> rota (web.php) -> closure -> HTML 200

### Respostas
a) As duas respostas tiveram `Content-Type: text/html; charset=utf-8`. Isso acontece porque uma string devolvida diretamente por uma rota já é tratada pelo Laravel como uma resposta HTML — o framework não inspeciona o conteúdo para decidir o tipo. Mesmo `/meu-nome`, que só tem texto sem tags, é enviado como `text/html`; por isso o navegador interpreta as tags de `/cartao` normalmente.

b) `/meu-nome/` (com barra no fim) também respondeu 200 com o mesmo texto: o roteador do Laravel normaliza a barra final e encontra a rota. Já `/Meu-Nome` caiu no `Route::fallback` e respondeu 404 com "Página não encontrada". Portanto, o endereço da rota **diferencia maiúsculas de minúsculas**: o caminho é sensível a caso.

c) O caminho é `GET /cartao -> rota (web.php) -> closure -> HTML 200`. Ficaram de fora o **controller** e a **view**: a lógica está toda na closure e o HTML foi devolvido como string, sem Blade.


## A02: Meu perfil em JSON

### Saídas
```
$ curl.exe -i http://127.0.0.1:8000/perfil-json
HTTP/1.1 200 OK
Content-Type: application/json

{"nome":"Halef","semestre":4,"media":8.5,"bolsista":true,"disciplinas":["Programa\u00e7\u00e3o Web","Banco de Dados","Redes"],"endereco":{"cidade":"Fortaleza","uf":"CE"}}

$ curl.exe -i http://127.0.0.1:8000/perfil-criado
HTTP/1.1 201 Created
Content-Type: application/json

{"nome":"Halef","semestre":4,"media":8.5,"bolsista":true,"disciplinas":["Programa\u00e7\u00e3o Web","Banco de Dados","Redes"],"endereco":{"cidade":"Fortaleza","uf":"CE"}}

$ teste temporário com media 8.0
HTTP/1.1 200 OK
Content-Type: application/json
{"nome":"Halef","semestre":4,"media":8,"bolsista":true,"disciplinas":[...],"endereco":{...}}
```

### Caminho da requisição
GET /perfil-json -> rota (web.php) -> closure -> response()->json() -> JSON 200

### Respostas
a) A primeira linha mudou de `HTTP/1.1 200 OK` para `HTTP/1.1 201 Created`. O corpo e o `Content-Type: application/json` ficaram iguais; só o status foi alterado pelo segundo argumento de `response()->json([...], 201)`.

b) O `true` do PHP virou `true` no JSON (booleano, sem aspas). O array `disciplinas`, com índices numéricos sequenciais, virou `["Programa...", "Banco de Dados", "Redes"]` — colchetes, porque é uma lista. O array `endereco`, associativo (`cidade` e `uf`), virou `{"cidade":"Fortaleza","uf":"CE"}` — chaves, porque tem chaves nomeadas. O `json_encode` transforma arrays sequenciais em arrays JSON e arrays com chaves textuais em objetos JSON.

c) Com `media` igual a `8.0`, apareceu `"media":8`, sem a casa decimal. O `json_encode` omite a parte `.0` de um float inteiro; para forçar `8.0` seria preciso a opção `JSON_PRESERVE_ZERO_FRACTION`. Com `8.5`, a casa aparece normalmente.


## A03: Tabuada e idade

### Saídas
```
$ curl.exe -i http://127.0.0.1:8000/tabuada/7
HTTP/1.1 200 OK
Content-Type: text/html; charset=utf-8

7 x 1 = 7<br>7 x 2 = 14<br>7 x 3 = 21<br>7 x 4 = 28<br>7 x 5 = 35<br>7 x 6 = 42<br>7 x 7 = 49<br>7 x 8 = 56<br>7 x 9 = 63<br>7 x 10 = 70<br>

$ curl.exe -i http://127.0.0.1:8000/tabuada/sete
HTTP/1.1 404 Not Found
Content-Type: text/plain; charset=utf-8

Página não encontrada

$ curl.exe -i http://127.0.0.1:8000/idade/2005
HTTP/1.1 200 OK
Content-Type: text/html; charset=utf-8

Quem nasceu em 2005 tem ou vai fazer 21 anos em 2026.

$ curl.exe -i http://127.0.0.1:8000/idade/dois-mil
HTTP/1.1 404 Not Found
Content-Type: text/plain; charset=utf-8

Página não encontrada

$ curl.exe -i http://127.0.0.1:8000/boas-vindas-turma
HTTP/1.1 200 OK
Content-Type: text/html; charset=utf-8

Bem-vinda, turma de ADS!

$ curl.exe -i http://127.0.0.1:8000/boas-vindas-turma/Redes
HTTP/1.1 200 OK
Content-Type: text/html; charset=utf-8

Bem-vinda, turma de Redes!

$ teste temporário: sem whereNumber, /tabuada/sete
HTTP/1.1 500 Internal Server Error
Content-Type: text/html; charset=utf-8
(local.ERROR: Argument #1 ($numero) must be of type int, string given)

$ teste temporário: sem valor padrão, /boas-vindas-turma
HTTP/1.1 500 Internal Server Error
Content-Type: text/html; charset=utf-8
(local.ERROR: Too few arguments to function ... 0 passed ... and exactly 1 expected)
```

### Caminho da requisição
GET /tabuada/7 -> rota (web.php, com whereNumber) -> closure -> HTML 200

### Respostas
a) Apareceu a página de fallback com `HTTP/1.1 404 Not Found` e o texto "Página não encontrada" (`Content-Type: text/plain`). Quem respondeu foi a rota `Route::fallback`, não a rota da tabuada: o `whereNumber('numero)` impediu que `sete` combinasse com `/tabuada/{numero}`, então nenhuma rota normal casou.

b) Sem o `whereNumber`, a rota casou com `sete`, mas a closure recebeu a string `"sete"` num parâmetro tipado como `int`, gerando erro: `Argument #1 ($numero) must be of type int, string given` e status 500. Com a restrição, o Laravel rejeita o parâmetro antes de chamar a closure e devolve um 404 limpo. A restrição é melhor porque evita erro interno, mantém a URL previsível e transforma entrada inválida em uma resposta HTTP adequada.

c) O `?` em `{turma?}` torna o parâmetro opcional: a rota atende tanto `/boas-vindas-turma` quanto `/boas-vindas-turma/Redes`. Ao tirar o valor padrão `'ADS'` da closure, o acesso sem parâmetro gerou `Too few arguments to function ... 0 passed ... and exactly 1 expected` (500), porque a rota casou mas a função exigia um argumento que não veio. O valor padrão é o que garante o fallback "ADS" quando o segmento falta.


## A04: Status e redirecionamentos

### Saídas
```
$ curl.exe -i http://127.0.0.1:8000/admin
HTTP/1.1 403 Forbidden
Content-Type: text/html; charset=utf-8

Área restrita

$ curl.exe -i http://127.0.0.1:8000/em-breve
HTTP/1.1 503 Service Unavailable
Content-Type: text/html; charset=utf-8

Página em construção

$ curl.exe -i http://127.0.0.1:8000/loja
HTTP/1.1 302 Found
Location: http://127.0.0.1:8000/feira
Content-Type: text/html; charset=utf-8

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="refresh" content="0;url='http://127.0.0.1:8000/feira'" />
        ...

$ curl.exe -i http://127.0.0.1:8000/produtos-antigos
HTTP/1.1 301 Moved Permanently
Location: http://127.0.0.1:8000/produtos
Content-Type: text/html; charset=utf-8

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="refresh" content="0;url='http://127.0.0.1:8000/produtos'" />
        ...

$ curl.exe -i -L http://127.0.0.1:8000/loja
HTTP/1.1 302 Found
Location: http://127.0.0.1:8000/feira
HTTP/1.1 200 OK
Bem-vindo à feira
```

### Caminho da requisição
GET /admin -> rota (web.php) -> closure -> response('Área restrita', 403) -> HTML 403

### Respostas
a) As quatro saídas estão acima. As linhas `Location:` dos redirecionamentos são `Location: http://127.0.0.1:8000/feira` (302) e `Location: http://127.0.0.1:8000/produtos` (301).

b) Eu provaria com `curl.exe -i` (ou com a aba Rede do DevTools): a linha de status diz `HTTP/1.1 403 Forbidden`. O navegador exibe o corpo da resposta como uma página normal, mas o código 403 é que comunica ao cliente que o acesso foi negado — o texto sozinho não é um erro HTTP.

c) O navegador fez **duas** requisições: primeiro `GET /loja`, recebeu 302 com `Location`, e depois `GET /feira`, recebendo 200. O `curl.exe -i` mostrou só uma porque, sem a opção `-L`, ele não segue redirecionamentos; com `-L` aparecem as duas respostas, como registrado acima.

d) Use **302** para mudanças temporárias: a Feira Digital mudou o endereço da loja para `/feira` durante uma promoção e pretende voltar a `/loja` depois; os motores de busca devem manter o endereço antigo. Use **301** para mudanças permanentes: o catálogo `/produtos-antigos` foi substituído definitivamente por `/produtos`, então o endereço antigo não voltará e o 301 manda atualizar os links indexados.


## A05: O cardápio da feira

### Saídas
```
$ curl.exe -i http://127.0.0.1:8000/cardapio
HTTP/1.1 200 OK
Content-Type: text/html; charset=utf-8

<!DOCTYPE html>
<html lang="pt-br">
...
    <nav>
    <a href="http://127.0.0.1:8000">Início</a>
    <a href="http://127.0.0.1:8000/produtos">Produtos</a>
    <a href="http://127.0.0.1:8000/cardapio">Cardápio</a>
    ...
    <h1>Cardápio do dia</h1>
    <main>
            <p>Quantidade de pratos: 3</p>
            <ul><li>Baião de dois — R$ 18,00</li></ul>
            <ul><li>Tapioca de queijo — R$ 8,50</li></ul>
            <ul><li>Cuscuz com ovo — R$ 7,00</li></ul>

$ teste temporário com $pratos = []:
HTTP/1.1 200 OK
Content-Type: text/html; charset=utf-8
            <p>Quantidade de pratos: 0</p>
            <p>Cardápio do dia ainda não definido.</p>

$ teste temporário com '<b>Panelada</b>' (Ctrl+U):
            <li>&lt;b&gt;Panelada&lt;/b&gt; — R$ 7,00</li>
```

### Caminho da requisição
GET /cardapio -> rota nomeada cardapio (web.php) -> closure -> view cardapio -> layout layouts.app -> HTML 200

### Respostas
a) `GET /cardapio -> rota (web.php) -> closure -> view cardapio -> layout layouts.app -> HTML 200`. A closure monta o array `$pratos`, passa para a view e o Blade monta o HTML final dentro do layout.

b) No código-fonte (Ctrl+U) apareceu `<li>&lt;b&gt;Panelada&lt;/b&gt; — R$ 7,00</li>`, ou seja, as tags foram exibidas como texto. Não ficou em negrito porque `{{ $prato['nome'] }}` aplica escape HTML (`htmlspecialchars`): `<` virou `&lt;` e `>` virou `&gt;`. Isso protege o site contra XSS — se um usuário pudesse digitar `<script>...</script>` no nome do prato, o navegador trataria como texto e não executaria o código.

c) O Blade juntou `resources/views/cardapio.blade.php` (conteúdo da seção), `resources/views/layouts/app.blade.php` (layout com `<html>`, Menu, `<title>` e `<h1>`) e `resources/views/partials/menu.blade.php` (incluído pelo layout via `@include`).


## A06: Menu completo e rotas com nome

### Saídas
```
$ php artisan route:list --except-vendor

  GET|HEAD       / ................................. inicio › routes/web.php:5
  GET|HEAD       admin ..................................... routes/web.php:12
  GET|HEAD       boas-vindas-turma/{turma?} ................ routes/web.php:45
  GET|HEAD       cardapio-do-dia ................ cardapio › routes/web.php:92
  GET|HEAD       cartao .................................... routes/web.php:72
  GET|HEAD       contato ......................... contato › routes/web.php:88
  GET|HEAD       em-breve .................................. routes/web.php:16
  GET|HEAD       feira ..................................... routes/web.php:28
  GET|HEAD       idade/{ano} ............................... routes/web.php:40
  GET|HEAD       loja ...................................... routes/web.php:20
  GET|HEAD       meu-nome .................................. routes/web.php:49
  GET|HEAD       perfil-criado ............................. routes/web.php:68
  GET|HEAD       perfil-json ............................... routes/web.php:64
  GET|HEAD       produtos ............ produtos.index › routes/web.php:102
  GET|HEAD       produtos-antigos .......................... routes/web.php:24
  GET|HEAD       quem-somos ........................ sobre › routes/web.php:84
  GET|HEAD       tabuada/{numero} .......................... routes/web.php:32
  GET|HEAD       {fallbackPlaceholder} .................... routes/web.php:106

                                                           Showing [18] routes

$ links gerados no menu depois da mudança de endereço
href="http://127.0.0.1:8000"
href="http://127.0.0.1:8000/produtos"
href="http://127.0.0.1:8000/cardapio-do-dia"
href="http://127.0.0.1:8000/quem-somos"
href="http://127.0.0.1:8000/contato"

$ erro provocado: route('sobree') no menu
HTTP/1.1 500 Internal Server Error
Content-Type: text/html; charset=utf-8
(local.ERROR: Route [sobree] not defined. (View: .../partials/menu.blade.php))
```

### Caminho da requisição
GET / -> rota nomeada inicio (web.php) -> closure -> view inicio -> layout -> partials/menu -> route() -> HTML 200

### Respostas
a) Na listagem, todas as rotas são atendidas por **closure**, inclusive `produtos.index` e `cardapio`. Se fossem controller, a coluna de ação mostraria `ProdutoController@index` (ou `ProdutoController::index`); aqui aparece apenas `routes/web.php:NN`, o que indica que a ação é uma função anônima definida no próprio arquivo de rotas. Nenhuma rota de controller aparece porque ainda não trocamos nenhuma closure nesta etapa.

b) Os links continuaram funcionando porque o menu não contém URLs fixas: ele usa `route('cardapio')` e `route('sobre')`. O helper `route()` consulta o nome da rota no registro do Laravel e devolve o endereço atual. Ao mudar `/cardapio` para `/cardapio-do-dia` e `/sobre` para `/quem-somos` mantendo os mesmos nomes, os links foram gerados automaticamente com os novos caminhos, sem editar as views.

c) A mensagem foi `Route [sobree] not defined. (View: .../resources/views/partials/menu.blade.php)` com status 500. Ela aparece em **todas** as páginas porque o menu está no layout (`@include('partials.menu')` em `layouts/app.blade.php`), e todas as views do site estendem esse layout. Assim, qualquer página que tenta renderizar o menu dispara a exceção.


## A07: Do closure ao controller

### Saídas
```
Antes (trecho de routes/web.php):
Route::get('/cardapio-do-dia', function () {
    $pratos = [
        ['nome' => 'Baião de dois', 'preco' => 18.0],
        ['nome' => 'Tapioca de queijo', 'preco' => 8.5],
        ['nome' => 'Cuscuz com ovo', 'preco' => 7.0],
    ];

    return view('cardapio', ['pratos' => $pratos]);
})->name('cardapio');

Depois (trecho de routes/web.php):
use App\Http\Controllers\CardapioController;
...
Route::get('/cardapio-do-dia', [CardapioController::class, 'index'])->name('cardapio');

$ php artisan route:list --except-vendor | grep cardapio
GET|HEAD  cardapio-do-dia ......... cardapio › CardapioController@index

$ curl.exe -i http://127.0.0.1:8000/cardapio-do-dia (antes e depois, idêntico)
HTTP/1.1 200 OK
Content-Type: text/html; charset=utf-8
            <p>Quantidade de pratos: 3</p>
            <li>Baião de dois — R$ 18,00</li>
            <li>Tapioca de queijo — R$ 8,50</li>
            <li>Cuscuz com ovo — R$ 7,00</li>

$ erro provocado: sem a linha use App\Http\Controllers\CardapioController;
HTTP/1.1 500 Internal Server Error
(local.ERROR: Target class [CardapioController] does not exist.)
```

### Caminho da requisição
GET /cardapio-do-dia -> rota (web.php) -> CardapioController@index -> pratos() -> view cardapio -> layout -> HTML 200

### Respostas
a) O trecho "antes" e o "depois" estão acima. Saiu do `web.php` a montagem do array `$pratos` e a chamada `view('cardapio', ...)`; foi para `app/Http/Controllers/CardapioController.php`, nos métodos `pratos()` (privado, com os dados) e `index()` (que devolve a view). O `web.php` ficou só com o endereço, o controller, o método e o nome da rota.

b) O caminho agora é `GET /cardapio-do-dia -> rota (web.php) -> CardapioController@index -> view cardapio -> HTML 200`. Entrou no desenho a parte **Controller** do MVC: a rota apenas encaminha para a classe, que decide quais dados mandar para a view.

c) Sem o `use App\Http\Controllers\CardapioController;`, o `CardapioController::class` em `web.php` passou a valer o nome curto `CardapioController` no namespace global. O container tentou resolver essa classe e não a encontrou: `Target class [CardapioController] does not exist.` com 500. A linha `use` é o que traz a classe para o arquivo de rotas.

d) A vantagem é organização e manutenção: com o crescimento do projeto, a lógica de "quais pratos existem" fica junto de outras ações do cardápio, fora do arquivo de rotas. Fica mais fácil testar, reutilizar o método `pratos()` e alterar a regra de negócio sem mexer no `web.php`. Para o usuário, a página é idêntica — a mudança é interna.


## A08: Estoque e categorias

### Saídas
```
$ curl.exe -i http://127.0.0.1:8000/produtos
HTTP/1.1 200 OK
Content-Type: text/html; charset=utf-8
    <title>Produtos da feira</title>
    <li><a href="http://127.0.0.1:8000/produtos/1">Leite de cabra</a> — R$ 12,00 (20 em estoque)</li>
    <li><a href="http://127.0.0.1:8000/produtos/2">Queijo coalho</a> — R$ 15,00 <strong>Esgotado</strong></li>
    <li><a href="http://127.0.0.1:8000/produtos/3">Doce de leite</a> — R$ 10,00 (8 em estoque)</li>
    <li><a href="http://127.0.0.1:8000/produtos/4">Mel de abelha</a> — R$ 22,00 (15 em estoque)</li>
    <li><a href="http://127.0.0.1:8000/produtos/5">Bolo de cenoura</a> — R$ 9,00 (6 em estoque)</li>

$ curl.exe -i http://127.0.0.1:8000/produtos/categoria/doces
HTTP/1.1 200 OK
Content-Type: text/html; charset=utf-8
    <title>Produtos da feira — doces</title>
    <li><a href="http://127.0.0.1:8000/produtos/3">Doce de leite</a> — R$ 10,00 (8 em estoque)</li>
    <li><a href="http://127.0.0.1:8000/produtos/5">Bolo de cenoura</a> — R$ 9,00 (6 em estoque)</li>

$ curl.exe -i http://127.0.0.1:8000/produtos/categoria/frutas
HTTP/1.1 404 Not Found
Content-Type: text/html; charset=utf-8
        <title>Not Found</title>
(página padrão de erro 404 do Laravel, gerada por abort(404) no método categoria)

$ curl.exe -i http://127.0.0.1:8000/produtos/categoria/123
HTTP/1.1 404 Not Found
Content-Type: text/plain; charset=utf-8
Página não encontrada
(Route::fallback, porque whereAlpha('categoria') rejeitou "123")

$ curl.exe -i http://127.0.0.1:8000/produtos/2
HTTP/1.1 200 OK
Content-Type: text/html; charset=utf-8
    <p><strong>Categoria:</strong> laticinios</p>
    <p><strong>Estoque:</strong> 0</p>
```

### Caminho da requisição
GET /produtos/categoria/doces -> rota (web.php, whereAlpha) -> ProdutoController@categoria -> array_filter -> view produtos.index -> HTML 200

### Respostas
a) Em `/produtos/categoria/frutas`, quem respondeu 404 foi o próprio `ProdutoController@categoria`: o `whereAlpha` aceitou `frutas` como palavra, o método executou, `array_filter` não encontrou produtos e `abort(404)` gerou a página padrão do Laravel (HTML, com `<title>Not Found</title>`). Em `/produtos/categoria/123`, quem respondeu foi o `Route::fallback`: o `whereAlpha` rejeitou o valor numérico, nenhuma rota casou e apareceu a página em texto "Página não encontrada" (`Content-Type: text/plain`).

b) O `array_filter` preserva as chaves originais do array (por exemplo, os doces ficam com chaves `3` e `5`). Como `route('produtos.show', $id)` usa exatamente essa chave como `$id`, os links continuam apontando para osprodutos corretos — se as chaves fossem renumeradas para `0` e `1`, os links quebrariam ou levariam ao produto errado.

c) É possível porque uma view é só um template: ela recebe os dados por variáveis e não sabe qual método do controller a enviou. Tanto `index()` quanto `categoria()` chamam `view('produtos.index', [...])` com um array `$produtos` (completo ou filtrado). A view percorre o que recebeu com `@foreach`; por isso o mesmo arquivo atende às duas rotas.


## A09: Um produto em JSON

### Saídas
<!-- curl.exe -i de um produto que existe, de um que não existe e de /produtos/abc/json -->
```
(cole aqui a saída do terminal)
```

### Caminho da requisição
_(ex.: GET /cardapio -> rota (web.php) -> CardapioController@index -> view cardapio -> HTML 200)_

### Respostas
a) _(responda aqui)_
b) _(responda aqui)_
c) _(responda aqui)_


## A10: Inscrição de produtores

### Saídas
<!-- mensagens de erro dos testes (a) e (b), mensagem de sucesso do teste (c), curl.exe -i do teste (d) -->
```
(cole aqui a saída do terminal)
```

### Caminho da requisição
_(ex.: GET /cardapio -> rota (web.php) -> CardapioController@index -> view cardapio -> HTML 200)_

### Respostas
a) _(responda aqui)_
b) _(responda aqui)_
c) _(responda aqui)_
d) _(responda aqui)_

