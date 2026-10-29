# Perfil do Estudante: versão 1

**Autor(a):** Halef

## Como executar

```
cd projeto/v1
php -S localhost:8000 -d display_errors=1 -d output_buffering=0
```

Abra http://localhost:8000

## Telas

| Tema claro | Tema escuro |
|---|---|
| ![Tema claro](prints/claro.png) | ![Tema escuro](prints/escuro.png) |

## 1. Mapa de rotas

| URL | O que mostra | Status |
|---|---|---|
| `/` | Página inicial: nome, curso, campus, cidade e texto sobre mim | 200 |
| `/?pagina=habilidades` | Minhas habilidades e o total delas | 200 |
| `/?pagina=projetos` | Cartões com os 3 projetos e link para os detalhes | 200 |
| `/?pagina=projeto&id=1` | Título, descrição e tecnologias do projeto 1 | 200 |
| `/?pagina=projeto&id=99` | Mensagem "Projeto não encontrado" | 404 |
| `/?pagina=qualquercoisa` | Mensagem "Página não encontrada" | 404 |
| `/?tema=escuro` | A página inicial com o tema escuro, gravado em cookie | 200 |
| `/?formato=json` | Todos os dados do perfil em JSON, sem HTML | 200 |

## 2. Saídas do terminal

**2.1 Uma página que existe:** `curl.exe -i "http://localhost:8000/?pagina=habilidades"` (cole só até o início do HTML)

```text
HTTP/1.1 200 OK
Host: localhost:8000
Date: Wed, 28 Oct 2026 21:47:13 GMT
Connection: close
X-Powered-By: PHP/8.3.6
Content-type: text/html; charset=UTF-8

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Habilidades | Meu perfil</title>
  <link rel="stylesheet" href="estilo.css">
</head>
<body class="tema-claro">
```

**Explicação:**

A primeira linha mostra que usei o protocolo HTTP/1.1 e que a resposta foi `200 OK`, ou seja, a página existe. `Content-type: text/html` diz ao navegador que o conteúdo é HTML. `X-Powered-By` mostra que quem respondeu foi o PHP. Depois da linha em branco começa o corpo, que é o HTML gerado pelo `index.php`. O `<title>` é "Habilidades" porque o PHP trocou o título conforme a página pedida, e o `<body>` está com a classe `tema-claro` porque não havia tema na URL nem cookie.

**2.2 Uma página que não existe:** `curl.exe -i "http://localhost:8000/?pagina=projeto&id=99"` (cole só até o início do HTML)

```text
HTTP/1.1 404 Not Found
Host: localhost:8000
Date: Wed, 28 Oct 2026 21:47:13 GMT
Connection: close
X-Powered-By: PHP/8.3.6
Content-type: text/html; charset=UTF-8

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Projeto não encontrado | Meu perfil</title>
  <link rel="stylesheet" href="estilo.css">
</head>
<body class="tema-claro">
```

**Explicação:**

Aqui a primeira linha é `404 Not Found`. Isso acontece porque `isset($projetos[99])` é falso, então o PHP chama `http_response_code(404)` antes de qualquer HTML. O resto da resposta continua sendo uma página HTML normal, com a mensagem "Projeto não encontrado" e o mesmo cabeçalho e rodapé. O status 404 é o que avisa o navegador e outros programas de que o recurso não existe.

**2.3 A troca de tema:** `curl.exe -i "http://localhost:8000/?tema=escuro"` (cole só até a linha do `<body>`)

```text
HTTP/1.1 200 OK
Host: localhost:8000
Date: Wed, 28 Oct 2026 21:47:13 GMT
Connection: close
X-Powered-By: PHP/8.3.6
Set-Cookie: tema=escuro; expires=Fri, 27 Nov 2026 21:47:13 GMT; Max-Age=2592000
Content-type: text/html; charset=UTF-8

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Início | Meu perfil</title>
  <link rel="stylesheet" href="estilo.css">
</head>
<body class="tema-escuro">
```

**Explicação (qual cabeçalho grava o tema e como o PHP sabe o tema na próxima requisição):**

O cabeçalho `Set-Cookie: tema=escuro` é o que grava o tema. Ele vem do `setcookie` e dura 30 dias (`Max-Age=2592000` segundos). O navegador guarda esse cookie e, em toda requisição seguinte ao site, envia `Cookie: tema=escuro`, e o PHP lê isso em `$_COOKIE['tema']`. Na mesma requisição em que o `setcookie` é chamado, o `$_COOKIE` ainda não tem o valor novo, porque ele só volta do navegador na próxima visita. Por isso a linha `$_GET['tema'] ?? $_COOKIE['tema'] ?? 'claro'` dá prioridade à URL: assim o `<body>` já sai com `tema-escuro` nessa mesma resposta.

**2.4 A API:** `curl.exe -i "http://localhost:8000/?formato=json"`

```text
HTTP/1.1 200 OK
Host: localhost:8000
Date: Wed, 28 Oct 2026 21:47:13 GMT
Connection: close
X-Powered-By: PHP/8.3.6
Content-Type: application/json; charset=utf-8

{
    "perfil": {
        "nome": "Halef",
        "curso": "Tecnologia em Análise e Desenvolvimento de Sistemas",
        "campus": "IFCE Campus Boa Viagem",
        "cidade": "Boa Viagem, CE",
        "apresentacao": "Sou estudante de ADS e quero trabalhar como desenvolvedor backend com Java. Estou aprendendo programação web com PHP, HTML e CSS.",
        "habilidades": [
            "HTML",
            "CSS",
            "PHP",
            "Git",
            "Java",
            "Linux"
        ]
    },
    "projetos": {
        "1": {
            "titulo": "Calculadora de média",
            "descricao": "Calcula a média de duas notas recebidas pela URL.",
            "tecnologias": "PHP, HTML"
        },
        "2": {
            "titulo": "AgroAssist",
            "descricao": "Projeto de requisitos e arquitetura de software feito em equipe na disciplina de Arquitetura de Sistemas.",
            "tecnologias": "UML, Arquitetura de software"
        },
        "3": {
            "titulo": "Perfil do Estudante",
            "descricao": "Este site: um perfil pessoal com páginas, tema com cookie e API em JSON.",
            "tecnologias": "PHP, HTML, CSS"
        }
    }
}
```

**Explicação:**

O cabeçalho `Content-Type: application/json; charset=utf-8` avisa que o corpo é JSON, e não HTML. Ele foi enviado com `header()` antes de qualquer saída. O corpo começa direto em `{`, sem `<!DOCTYPE>`, porque o `exit` encerra o script antes do HTML. O `json_encode` transformou os arrays `$perfil` e `$projetos` em JSON. `JSON_PRETTY_PRINT` deixou o texto identado e `JSON_UNESCAPED_UNICODE` manteve acentos como "Análise" em vez de `\u00e1`.

**2.5 Log do servidor** das quatro requisições acima

```text
[Wed Oct 28 21:47:12 2026] PHP 8.3.6 Development Server (http://localhost:8000) started
[Wed Oct 28 21:47:13 2026] 127.0.0.1:37558 Accepted
[Wed Oct 28 21:47:13 2026] 127.0.0.1:37558 [200]: GET /?pagina=habilidades
[Wed Oct 28 21:47:13 2026] 127.0.0.1:37558 Closing
[Wed Oct 28 21:47:13 2026] 127.0.0.1:37568 Accepted
[Wed Oct 28 21:47:13 2026] 127.0.0.1:37568 [404]: GET /?pagina=projeto&id=99
[Wed Oct 28 21:47:13 2026] 127.0.0.1:37568 Closing
[Wed Oct 28 21:47:13 2026] 127.0.0.1:37582 Accepted
[Wed Oct 28 21:47:13 2026] 127.0.0.1:37582 [200]: GET /?tema=escuro
[Wed Oct 28 21:47:13 2026] 127.0.0.1:37582 Closing
[Wed Oct 28 21:47:13 2026] 127.0.0.1:37596 Accepted
[Wed Oct 28 21:47:13 2026] 127.0.0.1:37596 [200]: GET /?formato=json
[Wed Oct 28 21:47:13 2026] 127.0.0.1:37596 Closing
```

**Explicação:**

Cada requisição gera três linhas. `Accepted` é a conexão aberta pelo cliente. Depois vem o status e o que foi pedido, como `[404]: GET /?pagina=projeto&id=99`. Por fim, `Closing` encerra a conexão. O número depois do IP (`37558`, `37568`...) é a porta do cliente e muda a cada requisição. Os status do log batem com os do `curl`: 200, 404, 200 e 200.

## 3. Onde usei cada conceito

| Conceito | Linha(s) do `index.php` | Para que usei |
|---|---|---|
| Array associativo | 3 a 28 | Guardar os dados em `$perfil` e `$projetos` |
| `$_GET` e `??` | 31, 58 | Ler o tema e a página pedidos pela URL, com valor padrão |
| `if` / `elseif` / `else` | 33, 61 a 80, 105 em diante | Validar o tema e escolher qual página mostrar |
| `htmlspecialchars` | 87, 90, 93 em diante | Proteger todo texto que vai para o HTML |
| `setcookie` e `$_COOKIE` | 31, 38 | Gravar o tema por 30 dias e lê-lo nas próximas visitas |
| `http_response_code(404)` | 72, 77 | Responder 404 para projeto ou página inexistente |
| `header` e `json_encode` | 49, 50 a 53 | Enviar o tipo JSON e transformar os arrays em JSON |
| `implode` e `count` | 114, 115 | Juntar as habilidades em um texto e contá-las |

## 4. Decisões e dificuldades

**4.1 Por que toda a lógica PHP fica no topo do arquivo, antes do HTML?**

Porque `setcookie`, `header` e `http_response_code` mudam os cabeçalhos da resposta, e os cabeçalhos precisam ser enviados antes de qualquer conteúdo. Se eu escrevesse HTML antes, o PHP já teria começado a enviar o corpo e daria o erro `headers already sent`. Deixando a lógica no topo, eu decido status, cookie e JSON primeiro e só depois mostro o HTML.

**4.2 Por que o tema escolhido na URL precisa ser validado?**

Porque quem controla a URL é o usuário, e qualquer um pode escrever `?tema=<script>`. Esse valor vai para a classe do `<body>` e para o cookie. Sem validar, eu aceitaria um valor qualquer. Por isso só aceito `claro` ou `escuro`, e todo o resto vira `claro`. Além disso, uso `htmlspecialchars` em todas as saídas como uma segunda proteção.

**4.3 Qual foi a maior dificuldade e como você resolveu?**

A maior dificuldade foi entender que o cookie não aparece em `$_COOKIE` na mesma requisição em que o `setcookie` roda. Resolvi dando prioridade ao `$_GET['tema']` sobre o `$_COOKIE['tema']`, e testei com `curl -i` para ver o `Set-Cookie` na resposta.
