# Questão 09: Método HTTP e código de status

**Nível:** 🔴 Avançado

## Arquivos desta questão

| Arquivo | O que é |
|---|---|
| `api.php` | Criado por você, conforme o enunciado |

## 1. Análise do código

**1.1 Cole o código do `api.php` e explique cada parte: a verificação do método, o status, os cabeçalhos e o JSON.**

```php
<?php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    echo json_encode(['erro' => 'Método não permitido'], JSON_UNESCAPED_UNICODE);
    exit;
}

http_response_code(200);
echo json_encode(['sucesso' => 'Dados recebidos'], JSON_UNESCAPED_UNICODE);
```

- `header('Content-Type: ...')`: avisa que a resposta é JSON. Fica no topo porque é enviado em todas as respostas.
- `$_SERVER['REQUEST_METHOD']`: guarda o método da requisição (`GET`, `POST`, `PUT`...). O `if` com `!== 'POST'` pega qualquer método diferente de `POST`.
- `http_response_code(405)`: muda o status para 405 (Method Not Allowed), que significa que o método não é aceito nesse endereço.
- `header('Allow: POST')`: diz ao cliente quais métodos são aceitos.
- `echo json_encode(['erro' => ...])`: envia o erro em JSON, com `JSON_UNESCAPED_UNICODE` para manter o acento de "Método".
- `exit`: encerra o script, para que o código do `POST` não rode.
- Se o método for `POST`, o código passa do `if`, define o status 200 e envia `{"sucesso": "Dados recebidos"}`.

## 2. Saídas do terminal

> Copie do terminal e cole entre as linhas de três crases. Depois explique **cada linha** com suas palavras.

**2.1 Saída de `curl.exe -i http://localhost:8000/q09-somente-post/api.php`**

```text
HTTP/1.1 405 Method Not Allowed
Host: localhost:8000
Date: Wed, 28 Oct 2026 23:10:45 GMT
Connection: close
X-Powered-By: PHP/8.3.6
Content-Type: application/json; charset=utf-8
Allow: POST

{"erro":"Método não permitido"}
```

**Explicação linha por linha:**

- `HTTP/1.1 405 Method Not Allowed`: o `curl` sem `-X` usa `GET`, que não é aceito, e o servidor respondeu 405.
- `Host`, `Date`, `Connection` e `X-Powered-By`: cabeçalhos padrão.
- `Content-Type: application/json; charset=utf-8`: o corpo é JSON, como enviei com o `header()`.
- `Allow: POST`: o único método aceito nesse endereço.
- O corpo é `{"erro":"Método não permitido"}`, o JSON do erro.

**2.2 Saída de `curl.exe -i -X POST http://localhost:8000/q09-somente-post/api.php`**

```text
HTTP/1.1 200 OK
Host: localhost:8000
Date: Wed, 28 Oct 2026 23:10:45 GMT
Connection: close
X-Powered-By: PHP/8.3.6
Content-Type: application/json; charset=utf-8

{"sucesso":"Dados recebidos"}
```

**Explicação linha por linha:**

- `HTTP/1.1 200 OK`: o método `POST` é aceito, então deu certo.
- Os cabeçalhos são os mesmos, mas não há `Allow`, porque não houve erro de método.
- O corpo é `{"sucesso":"Dados recebidos"}`.

**2.3 Saída de `curl.exe -i -X PUT http://localhost:8000/q09-somente-post/api.php`**

```text
HTTP/1.1 405 Method Not Allowed
Host: localhost:8000
Date: Wed, 28 Oct 2026 23:10:45 GMT
Connection: close
X-Powered-By: PHP/8.3.6
Content-Type: application/json; charset=utf-8
Allow: POST

{"erro":"Método não permitido"}
```

**Explicação linha por linha:**

É igual ao item 2.1. O `PUT` também é diferente de `POST`, então o `if` responde 405 com `Allow: POST` e o JSON de erro.

**2.4 Log do servidor das três requisições**

```text
[Wed Oct 28 23:10:45 2026] 127.0.0.1:51880 Accepted
[Wed Oct 28 23:10:45 2026] 127.0.0.1:51880 [405]: GET /q09-somente-post/api.php
[Wed Oct 28 23:10:45 2026] 127.0.0.1:51880 Closing
[Wed Oct 28 23:10:45 2026] 127.0.0.1:51892 Accepted
[Wed Oct 28 23:10:45 2026] 127.0.0.1:51892 [200]: POST /q09-somente-post/api.php
[Wed Oct 28 23:10:45 2026] 127.0.0.1:51892 Closing
[Wed Oct 28 23:10:45 2026] 127.0.0.1:51898 Accepted
[Wed Oct 28 23:10:45 2026] 127.0.0.1:51898 [405]: PUT /q09-somente-post/api.php
[Wed Oct 28 23:10:45 2026] 127.0.0.1:51898 Closing
```

**Explicação linha por linha:**

Cada requisição tem três linhas (`Accepted`, a requisição e `Closing`), e a porta muda a cada uma. Na linha do meio aparecem o status e o método: `[405]: GET`, `[200]: POST` e `[405]: PUT`. Eles batem com as respostas do `curl`.

## 3. Perguntas

**3.1 Que método o navegador usa ao abrir o endereço? Qual status ele recebe?**

Ao abrir um endereço digitando na barra, o navegador usa `GET`. Como a API só aceita `POST`, ele recebe o status 405 e o JSON `{"erro":"Método não permitido"}`. É o mesmo resultado do item 2.1.

**3.2 Para que serve o cabeçalho `Allow`?**

Ele diz ao cliente quais métodos o endereço aceita. É obrigatório em respostas 405. Aqui, `Allow: POST` avisa que só o `POST` funciona.

**3.3 No log, onde aparecem o método e o status de cada requisição?**

Na linha do meio de cada requisição, no formato `[status]: MÉTODO /caminho`. O status fica entre colchetes e o método vem logo depois dos dois pontos: `[405]: GET`, `[200]: POST` e `[405]: PUT`.
