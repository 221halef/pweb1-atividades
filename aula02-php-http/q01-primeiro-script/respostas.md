# Questão 01: O primeiro script e o log do servidor

**Nível:** 🟢 Básico

## Arquivos desta questão

| Arquivo | O que é |
|---|---|
| `ola.php` | Código fornecido (não altere) |

## 1. Análise do código

**1.1 O arquivo mistura HTML e PHP. Indique quais linhas são HTML e quais são PHP.**

Linhas puramente HTML:
Da linha 1 até a 8, e as linhas 11 e 12.

Linhas com código PHP:
As linhas 9 e 10. Nessas linhas, o HTML (as tags <p>) é misturado com o PHP. Tudo o que está dentro das marcações <?php ... ?> (linha 9) e <?= ... ?> (linha 10) é código PHP.

**1.2 Qual a diferença entre `<?php echo ... ?>` e `<?= ... ?>`?**

A sintaxe `<?php echo ... ?>` é a forma padrão para executar instruções PHP e exibir um valor na tela. Já a sintaxe `<?= ... ?>` é uma tag curta de exibição (*short echo tag*), que funciona como um atalho idêntico ao `<?php echo ... ?>`, sendo usada principalmente para simplificar a inserção direta de variáveis ou expressões dentro do código HTML.

## 2. Saídas do terminal

> Copie do terminal e cole entre as linhas de três crases. Depois explique **cada linha** com suas palavras.

**2.1 Log do servidor (as linhas que apareceram no terminal do `php -S` ao abrir a página no navegador)**

```text
[Wed Oct  7 15:46:46 2026] PHP 8.5.11 Development Server (http://localhost:8000) started
[Wed Oct  7 15:46:49 2026] [::1]:57696 Accepted
[Wed Oct  7 15:46:49 2026] [::1]:57696 [200]: GET /q01-primeiro-script/ola.php
[Wed Oct  7 15:46:49 2026] [::1]:57696 Closing
```

**Explicação linha por linha:**

A primeira linha indica a data, hora e endereço (http://localhost:8000) em que o servidor local do PHP foi iniciado. A segunda linha mostra que o servidor aceitou uma nova conexão feita pelo seu navegador ([::1] é o endereço local IPv6) na porta 57696. A terceira linha regista a requisição para aceder ao ficheiro /q01-primeiro-script/ola.php usando o método GET, e o código [200] confirma que a página foi processada e entregue com sucesso. A quarta linha indica que a conexão entre o servidor e o navegador foi encerrada logo após o envio do conteúdo.

**2.2 Saída de `curl -i http://localhost:8000/q01-primeiro-script/ola.php`**

```text
HTTP/1.1 200 OK
Host: localhost:8000
Date: Wed, 07 Oct 2026 19:02:56 GMT
Connection: close
X-Powered-By: PHP/8.5.11
Content-type: text/html; charset=UTF-8

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Questão 01</title>
</head>
<body>
  <h1>Olá, turma!</h1>
  <p>Este texto foi escrito pelo PHP.</p>
  <p>Hora no servidor: 19:02:56</p>
</body>
</html>
```

**Explicação linha por linha:**

A primeira linha (HTTP/1.1 200 OK) indica que a requisição foi processada e entregue com sucesso. As linhas seguintes são os cabeçalhos HTTP: Host mostra o servidor acessado, Date registra a data e hora do envio em formato GMT, Connection: close avisa que a conexão foi encerrada após o envio, X-Powered-By informa a versão do PHP utilizada no servidor e Content-type define que o conteúdo é um documento HTML formatado em UTF-8. A linha em branco serve para separar os cabeçalhos do corpo da resposta. Por fim, todo o bloco HTML no final é o resultado gerado pelo PHP e enviado diretamente para o terminal.

## 3. Perguntas

**3.1 Aperte F5 três vezes. O que muda na página e no log? Por quê?**

Na página, o horário exibido no campo "Hora no servidor" muda a cada atualização porque o PHP executa a função date('H:i:s') no servidor em tempo real a cada requisição recebida. No log, surgem três novos blocos de registros (Accepted, GET e Closing) indicando a abertura e o fechamento de novas conexões HTTP, onde o horário de cada registro é atualizado e o número da porta temporária do navegador muda a cada nova requisição.

**3.2 No "Exibir código-fonte" (Ctrl+U), aparece alguma linha de PHP? Explique.**

Não, nenhuma linha de PHP aparece no código-fonte. Isso ocorre porque o PHP é executado inteiramente no lado do servidor, que processa todos os scripts e envia para o navegador apenas o código HTML final já gerado.

**3.3 Na saída do curl, o que indicam a primeira linha, o cabeçalho `Content-type` e o cabeçalho `X-Powered-By`?**

A primeira linha indica que a requisição foi processada com sucesso pelo servidor (código 200 OK), o cabeçalho Content-type informa que o conteúdo retornado é um documento HTML codificado em UTF-8, e o X-Powered-By mostra a tecnologia e a versão do PHP utilizadas no servidor para gerar a resposta.
