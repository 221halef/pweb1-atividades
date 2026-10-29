# Questão 04: Tipos e conversões

**Nível:** 🟢 Básico

## Arquivos desta questão

| Arquivo | O que é |
|---|---|
| `tipos.php` | Código fornecido (no item 3.3 você vai acrescentar linhas) |

## 1. Análise do código

**1.1 Antes de executar, preencha a coluna "Minha previsão" com o tipo e o valor de cada linha.**

| Linha | Minha previsão | Resultado real |
|---|---|---|
| `"10" + 5` | int(15) | int(15) |
| `"10" . 5` | string(3) "105" | string(3) "105" |
| `7 / 2` | float(3.5) | float(3.5) |
| `7 % 2` | int(1) | int(1) |
| `(int) "3.9 kg"` | int(3) | int(3) |
| `(bool) "0"` | bool(false) | bool(false) |
| `(bool) "false"` | bool(true) | bool(true) |
| `0.1 + 0.2 == 0.3` | bool(false) | bool(false) |

O `var_dump` mostra o tipo e o valor. O PHP tem tipagem dinâmica: ele converte sozinho quando o operador pede, como o `+` pedindo números e o `.` pedindo texto.

## 2. Saídas do terminal

> Copie do terminal e cole entre as linhas de três crases. Depois explique **cada linha** com suas palavras.

**2.1 Saída de `curl.exe -s http://localhost:8000/q04-tipos/tipos.php`**

```text
<pre>int(15)
string(3) "105"
float(3.5)
int(1)
int(3)
bool(false)
bool(true)
bool(false)
bool(false)
int(2)
</pre>
```

**Explicação linha por linha:**

- `int(15)`: o `+` é de soma, então o PHP converte `"10"` para o número 10 e soma 5.
- `string(3) "105"`: o `.` é de concatenação, então o PHP converte o 5 para texto e junta. É uma string de 3 caracteres.
- `float(3.5)`: a divisão `/` resulta em decimal, porque 7 não divide 2 exatamente.
- `int(1)`: `%` é o resto da divisão. 7 dividido por 2 dá 3 e sobra 1.
- `int(3)`: `(int)` lê os números do começo do texto e ignora o resto. `"3.9 kg"` vira 3, sem arredondar.
- `bool(false)`: o texto `"0"` é considerado falso.
- `bool(true)`: o texto `"false"` é um texto não vazio, então é verdadeiro.
- `bool(false)`: `0.1 + 0.2` não dá exatamente 0.3 no computador, por causa do arredondamento dos decimais.
- `bool(false)` e `int(2)`: são as duas linhas que eu acrescentei, explicadas no item 3.3.

## 3. Perguntas

**3.1 Explique a diferença entre `"10" + 5` e `"10" . 5`.**

O `+` é o operador de soma: o PHP transforma `"10"` em número e o resultado é `int(15)`. O `.` é o operador de concatenação: o PHP transforma o 5 em texto e o resultado é a string `"105"`. O operador escolhe como os valores são tratados.

**3.2 Por que `(bool) "0"` é `false`, mas `(bool) "false"` é `true`?**

No PHP, a string `"0"` (e a string vazia) são os únicos textos considerados falsos. Qualquer outro texto não vazio é verdadeiro, mesmo que seja a palavra `"false"`. O PHP não lê o significado da palavra, só confere se o texto é vazio ou `"0"`.

**3.3 Acrescente ao arquivo duas linhas `var_dump` criadas por você, com resultados que te surpreenderam ou que você achou interessantes. Explique cada uma.**

```php
var_dump("abc" == 0);
var_dump(10 / 5);
```

- `var_dump("abc" == 0)` deu `bool(false)`. Eu esperava `true`, porque em versões antigas do PHP o texto `"abc"` virava 0 na comparação. No PHP 8, se o texto não é numérico, o PHP compara o número como texto (`"0"` com `"abc"`), e dá falso.
- `var_dump(10 / 5)` deu `int(2)`. Eu esperava `float(2)`, porque a divisão `/` costuma dar decimal, como em `7 / 2`. Quando a divisão é exata, o PHP devolve um inteiro.
