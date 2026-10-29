# Questão 07: O dado pode não vir, o dado pode ser perigoso

**Nível:** 🟡 Intermediário

## Arquivos desta questão

| Arquivo | O que é |
|---|---|
| `saudacao.php` | Código fornecido, que você vai corrigir no item 1.2 |

## 1. Análise do código

**1.1 Quais são os dois problemas deste código?**

1. **O dado pode não vir.** `$_GET['nome']` não tem valor padrão. Se a URL não tiver `?nome=...`, o PHP mostra um aviso de chave indefinida (`Undefined array key "nome"`).
2. **O dado pode ser perigoso.** O valor vem da URL e vai direto para o HTML, sem proteção. Quem controla a URL pode colocar tags HTML ou JavaScript na página (XSS).

**1.2 Corrija o código usando `??` (valor padrão "Visitante") e `htmlspecialchars`. Faça um commit **antes** e outro **depois** da correção. Cole o código corrigido e explique.**

```php
<?php
$nome = $_GET['nome'] ?? "Visitante";
echo "Olá, " . htmlspecialchars($nome);
```

O `??` usa `"Visitante"` quando a URL não tem o nome, e o aviso desaparece. O `htmlspecialchars` troca caracteres especiais do HTML (`<`, `>`, `&`, `"` e `'`) por códigos (`&lt;`, `&gt;`...), então o navegador mostra o texto em vez de executá-lo.

## 2. Saídas do terminal

> Copie do terminal e cole entre as linhas de três crases. Depois explique **cada linha** com suas palavras.

**2.1 Antes da correção: log do servidor ao abrir `saudacao.php` sem parâmetro**

```text
[Wed Oct 28 23:10:31 2026] 127.0.0.1:45134 Accepted
[Wed Oct 28 23:10:31 2026] PHP Warning:  Undefined array key "nome" in /home/claude/aula02-php-http/q07-tratamento/saudacao.php on line 2
[Wed Oct 28 23:10:31 2026] 127.0.0.1:45134 [200]: GET /q07-tratamento/saudacao.php
[Wed Oct 28 23:10:31 2026] 127.0.0.1:45134 Closing
```

**Explicação linha por linha:**

- `Accepted`: o servidor aceitou a conexão.
- `PHP Warning: Undefined array key "nome" ... on line 2`: o aviso do PHP. Ele diz que a chave `nome` não existe em `$_GET` e que o problema está na linha 2.
- `[200]: GET /q07-tratamento/saudacao.php`: um aviso não é erro fatal, então o código continuou e a resposta saiu com 200. Não há query string na URL.
- `Closing`: a conexão foi fechada.

**2.2 Antes da correção: saída de `curl.exe -s "http://localhost:8000/q07-tratamento/saudacao.php?nome=<b>Oi</b>"`**

```text
Olá, <b>Oi</b>
```

**Explicação linha por linha:**

O PHP colocou o valor da URL direto na resposta, então o `<b>Oi</b>` chegou como HTML de verdade. Em um navegador, a palavra "Oi" apareceria em negrito. Isso mostra que o usuário consegue mudar o HTML da página.

**2.3 Depois da correção: saída do mesmo comando do item 2.2**

```text
Olá, &lt;b&gt;Oi&lt;/b&gt;
```

**Explicação linha por linha:**

O `htmlspecialchars` trocou `<` por `&lt;` e `>` por `&gt;`. O navegador mostra esses códigos como os símbolos `<` e `>`, então o usuário vê o texto `<b>Oi</b>` na tela, sem negrito.

**2.4 Depois da correção: um nome comum, com acento, como o navegador envia (`curl.exe -s "http://localhost:8000/q07-tratamento/saudacao.php?nome=Jo%C3%A3o"`)**

```text
Olá, João
```

```text
[Wed Oct 28 23:34:05 2026] 127.0.0.1:55610 Accepted
[Wed Oct 28 23:34:05 2026] 127.0.0.1:55610 [200]: GET /q07-tratamento/saudacao.php?nome=Jo%C3%A3o
[Wed Oct 28 23:34:05 2026] 127.0.0.1:55610 Closing
```

**Explicação linha por linha:**

- `Olá, João`: o nome veio na URL, então o `??` não usou o `"Visitante"`. O `htmlspecialchars` não mexeu no `ã`, porque só troca os caracteres especiais do HTML (`<`, `>`, `&`, `"` e `'`).
- No log, o `ã` aparece como `%C3%A3`, que é a codificação de URL, como na Questão 06.
- O status é `[200]` e não há nenhum aviso do PHP.

## 3. Perguntas

**3.1 Qual aviso apareceu no item 2.1? O que ele significa?**

Apareceu `Warning: Undefined array key "nome"`. Significa que o código tentou ler a chave `nome` em `$_GET`, mas ela não existe porque a URL não enviou esse parâmetro. É um aviso, e não um erro fatal, então a página continuou e mostrou "Olá, " vazio.

**3.2 Compare o corpo da resposta em 2.2 e 2.3. O que o `htmlspecialchars` fez com `<b>`?**

Em 2.2, o corpo tinha `<b>Oi</b>`, que o navegador interpreta como tag. Em 2.3, o corpo tem `&lt;b&gt;Oi&lt;/b&gt;`. O `htmlspecialchars` converteu os sinais `<` e `>` em códigos HTML (entidades), que o navegador mostra como texto e não executa.

**3.3 Teste no navegador `?nome=<script>alert('Ataque')</script>` antes e depois da correção. Explique o que aconteceu e por que isso é perigoso (XSS).**

Antes da correção, o corpo da resposta ficou `Olá, <script>alert('Ataque')</script>`. O navegador entende isso como um script e executa o `alert`, mostrando uma janela de "Ataque". Depois da correção, o corpo ficou `Olá, &lt;script&gt;alert(&#039;Ataque&#039;)&lt;/script&gt;`, e o navegador só mostra o texto na tela, sem executar nada.

Isso é XSS (Cross-Site Scripting): um atacante coloca código JavaScript em um dado que o site mostra, e o navegador de outra pessoa executa esse código como se fosse do site. Ele pode roubar cookies, redirecionar para páginas falsas ou mudar o conteúdo da página. Basta o atacante mandar a vítima clicar em um link com o script na URL.
