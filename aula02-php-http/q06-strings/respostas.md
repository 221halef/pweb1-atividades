# Questão 06: Strings e acentuação

**Nível:** 🟡 Intermediário

## Arquivos desta questão

| Arquivo | O que é |
|---|---|
| `palavra.php` | Código fornecido, que você vai completar no item 1.2 |

> Se aparecer `Call to undefined function mb_strlen()`, ative a linha `extension=mbstring` no seu `php.ini` e reinicie o servidor. Registre isso na resposta 3.3.

## 1. Análise do código

**1.1 Explique o código original linha por linha.**

- `$palavra = $_GET['palavra'] ?? "Ceara";`: lê o parâmetro `palavra` da URL. Se ele não vier, usa `"Ceara"`.
- `echo "<p>Palavra: $palavra</p>";`: mostra a palavra dentro de um parágrafo. As aspas duplas trocam `$palavra` pelo valor.
- `echo "<p>strlen: " . strlen($palavra) . "</p>";`: mostra o resultado de `strlen`, que conta os bytes da palavra, e junta tudo com `.`.

**1.2 Complete o código para mostrar também: `mb_strlen`, `strtoupper`, `mb_strtoupper` e uma classificação com `if`: "palavra curta" (até 5 letras) ou "palavra longa". Use `mb_strlen` na classificação. Cole o código e explique.**

```php
<?php
$palavra = $_GET['palavra'] ?? "Ceara";

echo "<p>Palavra: $palavra</p>";
echo "<p>strlen: " . strlen($palavra) . "</p>";
echo "<p>mb_strlen: " . mb_strlen($palavra) . "</p>";
echo "<p>strtoupper: " . strtoupper($palavra) . "</p>";
echo "<p>mb_strtoupper: " . mb_strtoupper($palavra) . "</p>";

if (mb_strlen($palavra) <= 5) {
    echo "<p>Classificação: palavra curta</p>";
} else {
    echo "<p>Classificação: palavra longa</p>";
}
```

As funções com `mb_` (multibyte) entendem letras com acento. Na classificação, uso `mb_strlen` porque ela conta letras de verdade. Se a palavra tem até 5 letras, é curta, senão é longa.

## 2. Saídas do terminal

> Copie do terminal e cole entre as linhas de três crases. Depois explique **cada linha** com suas palavras.

**2.1 Saída de `curl.exe -s "http://localhost:8000/q06-strings/palavra.php?palavra=Ceara"`**

```text
<p>Palavra: Ceara</p><p>strlen: 5</p><p>mb_strlen: 5</p><p>strtoupper: CEARA</p><p>mb_strtoupper: CEARA</p><p>Classificação: palavra curta</p>
```

**Explicação linha por linha:**

Como não há quebras de linha nos `echo`, tudo sai em uma linha só. Os pedaços são:

- `Palavra: Ceara`: a palavra enviada na URL.
- `strlen: 5` e `mb_strlen: 5`: sem acento, cada letra ocupa 1 byte, então as duas contagens dão 5.
- `strtoupper: CEARA` e `mb_strtoupper: CEARA`: as duas funções deixam tudo em maiúsculas.
- `Classificação: palavra curta`: 5 é menor ou igual a 5.

**2.2 Abra no navegador `palavra.php?palavra=Ceará` e cole a linha do log do servidor**

```text
[Wed Oct 28 23:10:26 2026] 127.0.0.1:45128 Accepted
[Wed Oct 28 23:10:26 2026] 127.0.0.1:45128 [200]: GET /q06-strings/palavra.php?palavra=Cear%C3%A1
[Wed Oct 28 23:10:26 2026] 127.0.0.1:45128 Closing
```

Página recebida (o mesmo pedido, com o endereço já codificado):

```text
<p>Palavra: Ceará</p><p>strlen: 6</p><p>mb_strlen: 5</p><p>strtoupper: CEARá</p><p>mb_strtoupper: CEARÁ</p><p>Classificação: palavra curta</p>
```

**Explicação linha por linha:**

- Na linha do log, `Ceará` aparece como `Cear%C3%A1`. O `%C3%A1` é o `á` codificado para a URL.
- Na página, o PHP decodificou o valor e mostrou `Ceará`. `strlen` deu 6 e `mb_strlen` deu 5, e as duas versões em maiúsculas são diferentes (`CEARá` e `CEARÁ`). Explico isso nas perguntas.

## 3. Perguntas

**3.1 Compare o `strlen` de "Ceara" e de "Ceará". Por que o acento muda o resultado?**

"Ceara" tem `strlen` 5 e "Ceará" tem `strlen` 6, embora as duas tenham 5 letras. O `strlen` conta bytes, e não letras. Em UTF-8, as letras sem acento ocupam 1 byte, mas o `á` ocupa 2 bytes. Por isso a palavra com acento tem um byte a mais. O `mb_strlen` conta letras e dá 5 nas duas.

**3.2 No log, a palavra aparece como `Cear%C3%A1`. O que é isso? Por que o navegador transformou o "á"?**

É a codificação de URL (percent-encoding). Uma URL só pode ter certos caracteres, então o navegador troca os outros pelos bytes em hexadecimal, cada um depois de um `%`. O `á` em UTF-8 tem dois bytes, `C3` e `A1`, e vira `%C3%A1`. O PHP decodifica isso sozinho antes de guardar em `$_GET`.

**3.3 Qual a diferença entre `strtoupper` e `mb_strtoupper` com a palavra "Ceará"?**

O `strtoupper("Ceará")` deu `CEARá`: ele trabalha byte a byte e só converte as letras sem acento, então o `á` ficou minúsculo. O `mb_strtoupper("Ceará")` deu `CEARÁ`, porque entende que o `á` é uma letra de 2 bytes e sabe qual é a maiúscula dela.

Ao testar, apareceu o erro `Call to undefined function mb_strlen()`, porque a extensão `mbstring` não estava ativada. Eu ativei a extensão e subi o servidor de novo, e as funções passaram a funcionar.
