# Questão 10: Cookies e a ordem da resposta HTTP

**Nível:** 🔴 Avançado

## Arquivos desta questão

| Arquivo | O que é |
|---|---|
| `contador.php` | Parte A: criado por você |
| `login.php` | Parte B: código fornecido com erro (corrija no item 1.2) |
| `dashboard.php` | Parte B: criado por você |
| `prints/cookies-application.png` | DevTools, aba Application > Cookies, mostrando o cookie do contador |

> O arquivo `cookies.txt` criado pelo curl **não** deve ser enviado ao GitHub (ele já está no `.gitignore`).

## 1. Análise do código

**1.1 **Parte A:** cole o código do `contador.php` e explique como ele lê e grava o cookie.**

```php
<?php
$visitas = $_COOKIE["visitas"] ?? 0;
$visitas = $visitas + 1;
setcookie("visitas", $visitas, time() + 3600);
echo "Você visitou esta página $visitas vez(es).";
```

Eu leio o cookie com `$_COOKIE["visitas"]`, que guarda o que o navegador enviou. Se o cookie não existe, o `?? 0` usa 0 como valor padrão. Depois somo 1. Para gravar, uso `setcookie()`, que manda o cookie de volta para o navegador com o novo valor e validade de 1 hora (`time() + 3600`). Depois mostro a mensagem com `echo`. O PHP não guarda o número: quem guarda é o navegador.

**1.2 **Parte B:** cole o `login.php` corrigido e o `dashboard.php`, e explique a correção.**

`login.php` corrigido:

```php
<?php
setcookie("usuario_logado", "true", time() + 3600);
header("Location: dashboard.php");
```

`dashboard.php`:

```php
<?php
$logado = $_COOKIE["usuario_logado"] ?? "não";
echo "usuario_logado = $logado";
```

O erro era o `echo` antes do `setcookie()` e do `header()`. O `echo` começa o corpo da resposta e, nesse momento, o PHP já envia os cabeçalhos. Depois disso, não dá mais para criar cookie nem redirecionar. Eu corrigi apagando o `echo`, assim o `setcookie()` e o `header()` rodam primeiro. No `dashboard.php`, eu leio o cookie e mostro "não" se ele não existir.

## 2. Saídas do terminal

> Copie do terminal e cole entre as linhas de três crases. Depois explique **cada linha** com suas palavras.

**2.1 **Parte A:** saída de `curl.exe -i http://localhost:8000/q10-cookies/contador.php`**

```text
HTTP/1.1 200 OK
Host: localhost:8000
Date: Wed, 14 Oct 2026 17:02:41 GMT
Connection: close
X-Powered-By: PHP/8.3.6
Set-Cookie: visitas=1; expires=Wed, 14 Oct 2026 18:02:41 GMT; Max-Age=3600
Content-type: text/html; charset=UTF-8

Você visitou esta página 1 vez(es).
```

**Explicação linha por linha:**

- `HTTP/1.1 200 OK`: o servidor respondeu com sucesso.
- `Host`: o servidor que respondeu (localhost, porta 8000).
- `Date`: data e hora da resposta.
- `Connection: close`: a conexão fecha depois da resposta.
- `X-Powered-By`: mostra que o servidor usa PHP.
- `Set-Cookie`: manda o navegador guardar `visitas=1`, por 3600 segundos (1 hora).
- `Content-type`: o corpo é HTML em UTF-8.
- Linha em branco: separa os cabeçalhos do corpo.
- `Você visitou esta página 1 vez(es).`: o corpo, vindo do `echo`.

**2.2 **Parte A:** saída de `curl.exe -i -c cookies.txt -b cookies.txt http://localhost:8000/q10-cookies/contador.php`, executado **três vezes** (cole a terceira)**

```text
HTTP/1.1 200 OK
Host: localhost:8000
Date: Wed, 14 Oct 2026 17:02:51 GMT
Connection: close
X-Powered-By: PHP/8.3.6
Set-Cookie: visitas=3; expires=Wed, 14 Oct 2026 18:02:51 GMT; Max-Age=3600
Content-type: text/html; charset=UTF-8

Você visitou esta página 3 vez(es).
```

**Explicação linha por linha:**

As linhas são iguais às do item 2.1, com duas diferenças. O `Set-Cookie` agora traz `visitas=3`, e o corpo diz "3 vez(es)". Isso acontece porque o curl enviou o cookie guardado no `cookies.txt` (opção `-b`) e salvou o novo cookie recebido (opção `-c`). Assim, o PHP leu 2, somou 1 e gravou 3.

**2.3 **Parte B, antes da correção:** saída de `curl.exe -i http://localhost:8000/q10-cookies/login.php`**

```text
HTTP/1.1 200 OK
Host: localhost:8000
Date: Wed, 14 Oct 2026 17:02:41 GMT
Connection: close
X-Powered-By: PHP/8.3.6
Content-type: text/html; charset=UTF-8

Carregando a página...<br />
<b>Warning</b>:  Cannot modify header information - headers already sent by (output started at /home/claude/teste/q10-cookies/login.php:2) in <b>/home/claude/teste/q10-cookies/login.php</b> on line <b>3</b><br />
<br />
<b>Warning</b>:  Cannot modify header information - headers already sent by (output started at /home/claude/teste/q10-cookies/login.php:2) in <b>/home/claude/teste/q10-cookies/login.php</b> on line <b>4</b><br />
```

**Explicação linha por linha:**

- `HTTP/1.1 200 OK`: o status é 200 e não 302, então não houve redirecionamento.
- Os cabeçalhos seguintes são os padrões. Não há `Set-Cookie` nem `Location`.
- `Carregando a página...`: o texto do `echo` da linha 2, que começou o corpo.
- Primeiro `Warning`: "headers already sent". O erro está na linha 3 (`setcookie`), e a saída começou na linha 2.
- Segundo `Warning`: o mesmo erro na linha 4 (`header`).

**2.4 **Parte B, depois da correção:** saída de `curl.exe -i http://localhost:8000/q10-cookies/login.php`**

```text
HTTP/1.1 302 Found
Host: localhost:8000
Date: Wed, 14 Oct 2026 17:02:51 GMT
Connection: close
X-Powered-By: PHP/8.3.6
Set-Cookie: usuario_logado=true; expires=Wed, 14 Oct 2026 18:02:51 GMT; Max-Age=3600
Location: dashboard.php
Content-type: text/html; charset=UTF-8

```

**Explicação linha por linha:**

- `HTTP/1.1 302 Found`: redirecionamento temporário.
- `Set-Cookie`: grava `usuario_logado=true` por 1 hora.
- `Location: dashboard.php`: diz ao navegador para onde ir.
- As outras linhas são as padrões, como no item 2.1.
- O corpo está vazio, porque não há `echo`.

## 3. Perguntas

**3.1 No item 2.1, qual cabeçalho da resposta grava o cookie? E por que o contador não passa de 1 se você repetir o comando sem `-c` e `-b`?**

O cabeçalho é o `Set-Cookie`. Sem `-c` e `-b`, o curl não guarda nem reenvia o cookie. O servidor nunca recebe `visitas`, então sempre começa do 0 e mostra 1.

**3.2 No item 2.2, o que fazem as opções `-c` e `-b`? O que isso prova sobre quem "lembra" do usuário no HTTP?**

A opção `-c` salva no arquivo os cookies que o servidor mandou. A opção `-b` envia os cookies do arquivo na requisição. Isso prova que o HTTP não guarda estado: quem lembra do usuário é o cliente, que reenvia o cookie a cada visita.

**3.3 No item 2.3, qual aviso apareceu e por que o redirecionamento não aconteceu?**

Apareceu "Cannot modify header information - headers already sent". O `echo` da linha 2 começou o corpo da resposta, e os cabeçalhos já tinham sido enviados. Por isso o `header("Location")` foi ignorado e o redirecionamento não aconteceu.

**3.4 No item 2.4, explique o status, o cabeçalho `Location` e o cabeçalho `Set-Cookie`. Por que `setcookie()` e `header()` precisam vir antes de qualquer `echo`?**

O status 302 manda o navegador seguir para outra página. O `Location` diz qual é essa página (`dashboard.php`). O `Set-Cookie` grava o cookie `usuario_logado=true` por 1 hora. Na resposta HTTP, os cabeçalhos vêm antes do corpo e são enviados uma vez só. O primeiro `echo` envia os cabeçalhos junto, então `setcookie()` e `header()` precisam rodar antes dele.
