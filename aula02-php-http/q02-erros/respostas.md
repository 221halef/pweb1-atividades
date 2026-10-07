# Questão 02: Lendo mensagens de erro

**Nível:** 🟢 Básico

## Arquivos desta questão

| Arquivo | O que é |
|---|---|
| `erro.php` | Código fornecido com um erro proposital (corrija no item 3.3) |

## 1. Análise do código

**1.1 Antes de executar: qual é o erro do código? Em que linha ele está?**

Estava faltando ponto e vírgula na segunda do código

## 2. Saídas do terminal

> Copie do terminal e cole entre as linhas de três crases. Depois explique **cada linha** com suas palavras.

**2.1 Log do servidor ao abrir a página com `display_errors=1`**

```text
[Wed Oct  7 16:17:43 2026] PHP 8.5.11 Development Server (http://localhost:8000) started
[Wed Oct  7 16:17:48 2026] [::1]:47808 Accepted
[Wed Oct  7 16:17:48 2026] [::1]:47808 [200]: GET /q02-erros/erro.php
[Wed Oct  7 16:17:48 2026] [::1]:47808 Closing
```

**Explicação linha por linha:**

A primeira linha indica a data, hora e endereço em que o servidor local do PHP foi iniciado. A segunda linha mostra que o servidor aceitou uma nova conexão local ([::1]) na porta 47808. A terceira linha registra a requisição para acessar o arquivo /q02-erros/erro.php pelo método GET, retornando o código [200] de sucesso. A quarta linha indica que a conexão foi encerrada logo após o envio da resposta.

**2.2 Log do servidor ao abrir a página com `display_errors=0`**

```text
[Wed Oct  7 16:20:51 2026] PHP 8.5.11 Development Server (http://localhost:8000) started
[Wed Oct  7 16:21:31 2026] [::1]:56002 Accepted
[Wed Oct  7 16:21:31 2026] [::1]:56002 [200]: GET /q02-erros/erro.php
[Wed Oct  7 16:21:31 2026] [::1]:56002 Closing
```

**Explicação linha por linha:**

A primeira linha indica a data, hora e endereço em que o servidor local do PHP foi iniciado. A segunda linha mostra que o servidor aceitou uma nova conexão local ([::1]) na porta 56002. A terceira linha registra a requisição para acessar o arquivo /q02-erros/erro.php pelo método GET, retornando o código [200] de sucesso. A quarta linha indica que a conexão foi encerrada logo após o envio da resposta.

## 3. Perguntas

**3.1 A mensagem diz que o erro está em qual linha? Por que não é a linha onde falta o `;`?**

_(responda aqui)_

**3.2 Compare o status registrado no log nas duas execuções (`[200]` e `[500]`). Por que eles são diferentes? Qual dos dois é o correto do ponto de vista do HTTP?**

_(responda aqui)_

**3.3 Corrija o código, faça um commit e cole aqui o log da execução corrigida.**

_(responda aqui)_
