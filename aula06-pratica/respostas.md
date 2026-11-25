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
<!-- curl.exe -i de /admin, /em-breve, /loja e /produtos-antigos -->
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


## A05: O cardápio da feira

### Saídas
<!-- curl.exe -i de /cardapio e a linha do Ctrl+U com a Panelada -->
```
(cole aqui a saída do terminal)
```

### Caminho da requisição
_(ex.: GET /cardapio -> rota (web.php) -> CardapioController@index -> view cardapio -> HTML 200)_

### Respostas
a) _(responda aqui)_
b) _(responda aqui)_
c) _(responda aqui)_


## A06: Menu completo e rotas com nome

### Saídas
<!-- php artisan route:list --except-vendor e a mensagem de erro do item 4 -->
```
(cole aqui a saída do terminal)
```

### Caminho da requisição
_(ex.: GET /cardapio -> rota (web.php) -> CardapioController@index -> view cardapio -> HTML 200)_

### Respostas
a) _(responda aqui)_
b) _(responda aqui)_
c) _(responda aqui)_


## A07: Do closure ao controller

### Saídas
<!-- trecho da rota antes e depois, curl.exe -i de antes e de depois, mensagem de erro sem o use -->
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


## A08: Estoque e categorias

### Saídas
<!-- curl.exe -i de /produtos/categoria/doces, /produtos/categoria/frutas e /produtos/categoria/123 -->
```
(cole aqui a saída do terminal)
```

### Caminho da requisição
_(ex.: GET /cardapio -> rota (web.php) -> CardapioController@index -> view cardapio -> HTML 200)_

### Respostas
a) _(responda aqui)_
b) _(responda aqui)_
c) _(responda aqui)_


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

