# Questão 08: O que o navegador entende? (Content-Type)

**Nível:** 🟡 Intermediário

## Arquivos desta questão

| Arquivo | O que é |
|---|---|
| `dados.php` | Código fornecido. No item 2.2 você vai descomentar a linha do header |
| `prints/devtools-html.png` | DevTools com o Content-Type antes de descomentar |
| `prints/devtools-json.png` | DevTools com o Content-Type depois de descomentar |

## 1. Análise do código

**1.1 Explique o que fazem `json_encode`, `JSON_PRETTY_PRINT` e `JSON_UNESCAPED_UNICODE`.**

- `json_encode($dados, ...)` transforma o array do PHP em texto no formato JSON.
- `JSON_PRETTY_PRINT` deixa o JSON organizado, com quebras de linha e recuos, para facilitar a leitura. Sem ele, o JSON sai em uma linha só.
- `JSON_UNESCAPED_UNICODE` mantém letras com acento como estão (`Programação`). Sem ele, o PHP troca essas letras por códigos como `\u00e7`.
- O `|` junta as duas opções.

## 2. Saídas do terminal

> Copie do terminal e cole entre as linhas de três crases. Depois explique **cada linha** com suas palavras.

**2.1 Saída de `curl.exe -i http://localhost:8000/q08-content-type/dados.php` com o header comentado**

```text
HTTP/1.1 200 OK
Host: localhost:8000
Date: Wed, 28 Oct 2026 23:10:38 GMT
Connection: close
X-Powered-By: PHP/8.3.6
Content-type: text/html; charset=UTF-8

{
    "disciplina": "Programação Web I",
    "status": "Ativo",
    "alunos": 30
}
```

**Explicação linha por linha:**

- `HTTP/1.1 200 OK`: a requisição deu certo.
- `Host`, `Date`, `Connection: close` e `X-Powered-By`: cabeçalhos padrão, com o servidor, a data, o fechamento da conexão e a versão do PHP.
- `Content-type: text/html; charset=UTF-8`: como não mandei nenhum `Content-Type`, o PHP usou o padrão e disse que o corpo é HTML.
- Depois da linha em branco vem o corpo: o JSON, com os dados do array e os acentos preservados.

**2.2 Saída do mesmo comando com o header descomentado**

```text
HTTP/1.1 200 OK
Host: localhost:8000
Date: Wed, 28 Oct 2026 23:10:39 GMT
Connection: close
X-Powered-By: PHP/8.3.6
Content-Type: application/json; charset=utf-8

{
    "disciplina": "Programação Web I",
    "status": "Ativo",
    "alunos": 30
}
```

**Explicação linha por linha:**

As linhas são iguais às do item 2.1, com uma exceção: o cabeçalho agora é `Content-Type: application/json; charset=utf-8`, enviado pelo `header()` que descomentei. Ele avisa que o corpo é JSON em UTF-8. O corpo continua igual.

## 3. Perguntas

**3.1 Compare o cabeçalho `Content-Type` nas duas saídas. O que mudou?**

Na primeira, era `text/html; charset=UTF-8`, o valor padrão do PHP. Na segunda, passou a ser `application/json; charset=utf-8`, o valor que escolhi com o `header()`. Mudou o tipo declarado do conteúdo.

**3.2 O corpo da resposta mudou? O que isso mostra sobre quem decide como o conteúdo é interpretado?**

Não, o corpo ficou exatamente igual nas duas respostas. Isso mostra que quem decide como o conteúdo é interpretado é o cabeçalho `Content-Type`. Com `text/html`, um navegador trata o JSON como uma página (e junta as quebras de linha); com `application/json`, ele sabe que é um dado e o mostra como JSON. Programas que consomem uma API também dependem desse cabeçalho.

**3.3 Retire o `JSON_UNESCAPED_UNICODE`, rode o curl de novo e explique o que aconteceu com "Programação".**

Sem a opção, o corpo ficou assim:

```text
{
    "disciplina": "Programa\u00e7\u00e3o Web I",
    "status": "Ativo",
    "alunos": 30
}
```

O PHP trocou o `ç` por `\u00e7` e o `ã` por `\u00e3`, que são códigos Unicode. Isso ainda é um JSON válido, e quem lê o JSON converte os códigos de volta. Mas fica ilegível para humanos. Depois do teste, devolvi a opção ao código.
