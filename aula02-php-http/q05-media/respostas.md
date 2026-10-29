# Questão 05: Média com dados da URL

**Nível:** 🟡 Intermediário

## Arquivos desta questão

| Arquivo | O que é |
|---|---|
| `media.php` | Código fornecido, que você vai modificar no item 1.2 |

## 1. Análise do código

**1.1 Explique o código original: o que fazem `const`, os parênteses no cálculo e o operador `? :`?**

- `const MEDIA_APROVACAO = 7;` cria uma constante: um valor com nome que não muda depois de definido. Uso o nome em vez do número 7 solto.
- `($n1 + $n2) / 2`: os parênteses fazem a soma acontecer primeiro. Sem eles, o PHP faria `$n2 / 2` antes e somaria `$n1`, e a média ficaria errada.
- `$media >= MEDIA_APROVACAO ? "Aprovado" : "Em recuperação"` é o operador ternário, um `if` em uma linha: se a condição for verdadeira, vale o texto antes do `:`, senão vale o texto depois.

**1.2 Modifique o código para receber as notas pela URL (`?n1=...&n2=...`), usando `??` com valor padrão `0` e convertendo para `float`. Cole aqui o trecho que você alterou e explique.**

```php
$n1 = (float) ($_GET['n1'] ?? 0);
$n2 = (float) ($_GET['n2'] ?? 0);
```

`$_GET['n1']` lê o valor que veio na URL. O `??` usa 0 quando a nota não foi enviada. O `(float)` converte o valor, que chega como texto, para número decimal, para a conta funcionar.

## 2. Saídas do terminal

> Copie do terminal e cole entre as linhas de três crases. Depois explique **cada linha** com suas palavras.

**2.1 Saída de `curl.exe -s "http://localhost:8000/q05-media/media.php?n1=5&n2=6"`**

```text
<p>Notas: 5 e 6</p>
<p>Média: 5.5</p>
<p>Situação: Em recuperação</p>
```

**Explicação linha por linha:**

- `Notas: 5 e 6`: o PHP leu `n1=5` e `n2=6` da URL e mostrou os dois valores.
- `Média: 5.5`: (5 + 6) / 2 = 5,5.
- `Situação: Em recuperação`: 5,5 é menor que 7, então o ternário escolhe o texto depois do `:`.

**2.2 Saída de `curl.exe -s "http://localhost:8000/q05-media/media.php?n1=9"`**

```text
<p>Notas: 9 e 0</p>
<p>Média: 4.5</p>
<p>Situação: Em recuperação</p>
```

**Explicação linha por linha:**

- `Notas: 9 e 0`: a nota 1 veio da URL (9) e a nota 2 não veio, então o `??` usou o valor padrão 0.
- `Média: 4.5`: (9 + 0) / 2 = 4,5.
- `Situação: Em recuperação`: 4,5 é menor que 7.

**2.3 Log do servidor das duas requisições acima**

```text
[Wed Oct 28 23:10:09 2026] 127.0.0.1:56040 Accepted
[Wed Oct 28 23:10:09 2026] 127.0.0.1:56040 [200]: GET /q05-media/media.php?n1=5&n2=6
[Wed Oct 28 23:10:09 2026] 127.0.0.1:56040 Closing
[Wed Oct 28 23:10:09 2026] 127.0.0.1:56044 Accepted
[Wed Oct 28 23:10:09 2026] 127.0.0.1:56044 [200]: GET /q05-media/media.php?n1=9
[Wed Oct 28 23:10:09 2026] 127.0.0.1:56044 Closing
```

**Explicação linha por linha:**

Cada requisição gera três linhas. `Accepted` é a conexão aberta, e a porta (`56040`, `56044`) muda a cada requisição. A linha do meio mostra o status `[200]`, o método `GET` e o caminho pedido com a query string: `?n1=5&n2=6` na primeira e `?n1=9` na segunda. `Closing` fecha a conexão.

## 3. Perguntas

**3.1 No log, onde aparecem os valores das notas? Que parte da URL é essa?**

Eles aparecem na linha do `GET`, depois do `?`, em `?n1=5&n2=6`. Essa parte é a query string: pares `nome=valor` separados por `&`. O PHP guarda esses pares em `$_GET`.

**3.2 Na requisição 2.2, a nota 2 não foi enviada. Qual valor o código usou? Qual operador garantiu isso?**

O código usou 0. Quem garantiu isso foi o operador `??` (coalescência nula): se `$_GET['n2']` não existe, ele usa o valor à direita, que é 0. Sem o `??`, o PHP mostraria um aviso de chave indefinida.

**3.3 O que acontece com `?n1=abc&n2=10`? Explique o papel do `(float)`.**

A saída foi `Notas: 0 e 10`, `Média: 5` e `Situação: Em recuperação`. O texto `abc` não é número, então o `(float)` o converte para 0. A média ficou (0 + 10) / 2 = 5. O `(float)` garante que o valor vire número antes da conta e evita erro, mas não avisa que o dado estava inválido.
