# Questão 02: Lendo mensagens de erro

**Nível:** 🟢 Básico

## Arquivos desta questão

| Arquivo | O que é |
|---|---|
| `erro.php` | Código fornecido com um erro proposital (corrija no item 3.3) |

## 1. Análise do código

**1.1 Antes de executar: qual é o erro do código? Em que linha ele está?**

Falta o ponto e vírgula no fim da linha 2 (`$curso = "ADS"`). O PHP exige `;` no fim de cada instrução.

## 2. Saídas do terminal

> Copie do terminal e cole entre as linhas de três crases. Depois explique **cada linha** com suas palavras.

**2.1 Log do servidor ao abrir a página com `display_errors=1`**

```text
[Wed Oct 28 23:09:49 2026] PHP 8.3.6 Development Server (http://localhost:8000) started
[Wed Oct 28 23:09:50 2026] 127.0.0.1:33148 Accepted
[Wed Oct 28 23:09:50 2026] PHP Parse error:  syntax error, unexpected token "echo" in /home/claude/aula02-php-http/q02-erros/erro.php on line 3
[Wed Oct 28 23:09:50 2026] 127.0.0.1:33148 [200]: GET /q02-erros/erro.php - syntax error, unexpected token "echo" in /home/claude/aula02-php-http/q02-erros/erro.php on line 3
[Wed Oct 28 23:09:50 2026] 127.0.0.1:33148 Closing
```

**Explicação linha por linha:**

- `PHP 8.3.6 Development Server ... started`: o servidor subiu em `localhost:8000`.
- `127.0.0.1:33148 Accepted`: o servidor aceitou uma conexão do meu computador (`127.0.0.1`), pela porta 33148.
- `PHP Parse error: ... unexpected token "echo" ... on line 3`: o erro do PHP. Ele diz que o `echo` apareceu onde não devia, na linha 3 do `erro.php`.
- `[200]: GET /q02-erros/erro.php - syntax error...`: a requisição foi um `GET` para esse arquivo, o status registrado foi 200 e, no fim, o log repete a mensagem do erro.
- `Closing`: a conexão foi fechada.

Na página apareceu a mensagem `Parse error: syntax error, unexpected token "echo" ... on line 3`.

**2.2 Log do servidor ao abrir a página com `display_errors=0`**

```text
[Wed Oct 28 23:09:51 2026] PHP 8.3.6 Development Server (http://localhost:8000) started
[Wed Oct 28 23:09:52 2026] 127.0.0.1:33156 Accepted
[Wed Oct 28 23:09:52 2026] PHP Parse error:  syntax error, unexpected token "echo" in /home/claude/aula02-php-http/q02-erros/erro.php on line 3
[Wed Oct 28 23:09:52 2026] 127.0.0.1:33156 [500]: GET /q02-erros/erro.php - syntax error, unexpected token "echo" in /home/claude/aula02-php-http/q02-erros/erro.php on line 3
[Wed Oct 28 23:09:52 2026] 127.0.0.1:33156 Closing
```

**Explicação linha por linha:**

As linhas são as mesmas do item 2.1, com uma diferença importante: o status agora é `[500]` em vez de `[200]`. O erro continua aparecendo no log, mas a página ficou em branco, porque o PHP não mostrou a mensagem no corpo da resposta.

## 3. Perguntas

**3.1 A mensagem diz que o erro está em qual linha? Por que não é a linha onde falta o `;`?**

A mensagem diz linha 3, mas o `;` que falta é da linha 2. O PHP lê o código em sequência e uma quebra de linha não termina uma instrução. Ele só percebe o problema quando chega no `echo` da linha 3, porque ali um `echo` não podia aparecer. Por isso o PHP aponta a linha onde percebeu o erro, e eu preciso olhar também a linha anterior.

**3.2 Compare o status registrado no log nas duas execuções (`[200]` e `[500]`). Por que eles são diferentes? Qual dos dois é o correto do ponto de vista do HTTP?**

Com `display_errors=1`, o PHP escreveu a mensagem de erro dentro da página e a resposta saiu com `200`. Com `display_errors=0`, ele não mostrou nada na página e respondeu `500`. Do ponto de vista do HTTP, o correto é o `500 Internal Server Error`: o servidor falhou, e o `200` diz que deu tudo certo, o que não é verdade. Mostrar erros na página também é perigoso fora do desenvolvimento, porque revela caminhos de arquivos do servidor.

**3.3 Corrija o código, faça um commit e cole aqui o log da execução corrigida.**

Código corrigido (coloquei o `;` no fim da linha 2):

```php
<?php
$curso = "ADS";
echo "Bem-vindo ao curso de $curso";
```

```text
[Wed Oct 28 23:11:11 2026] 127.0.0.1:54402 Accepted
[Wed Oct 28 23:11:11 2026] 127.0.0.1:54402 [200]: GET /q02-erros/erro.php
[Wed Oct 28 23:11:11 2026] 127.0.0.1:54402 Closing
```

Agora o log não tem nenhuma linha de erro, o status é `[200]` e a página mostra "Bem-vindo ao curso de ADS".
