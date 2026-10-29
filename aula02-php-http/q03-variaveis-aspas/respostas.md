# Questão 03: Variáveis, aspas e concatenação

**Nível:** 🟢 Básico

## Arquivos desta questão

| Arquivo | O que é |
|---|---|
| `aspas.php` | Código fornecido (não altere) |

## 1. Análise do código

**1.1 Antes de executar, preencha a coluna "Minha previsão" com o que cada linha vai mostrar.**

| Linha | Minha previsão | Resultado real |
|---|---|---|
| `"Aluna: " . $nome` | Aluna: Maria | Aluna: Maria |
| `"Aluna: $nome"` | Aluna: Maria | Aluna: Maria |
| `'Aluna: $nome'` | Aluna: $nome | Aluna: $nome |
| `"Cursa o {$semestre}º semestre de $curso"` | Cursa o 2º semestre de ADS | Cursa o 2º semestre de ADS |
| `$nome . $curso` | MariaADS | MariaADS |
| `<?= $nome ?>` | Maria | Maria |

Eu prevejo o resultado olhando o tipo de aspas: aspas duplas trocam a variável pelo valor, e aspas simples mostram o texto exatamente como está. O ponto (`.`) junta os textos sem criar espaço.

**1.2 Para que servem as chaves `{}` em `{$semestre}`?**

As chaves dizem ao PHP onde termina o nome da variável. Sem elas, em `"$semestreº"`, o PHP leria `$semestreº` como o nome da variável, porque o `º` pode fazer parte de um nome. Com `{$semestre}`, ele pega só `$semestre` e o `º` fica como texto.

## 2. Saídas do terminal

> Copie do terminal e cole entre as linhas de três crases. Depois explique **cada linha** com suas palavras.

**2.1 Saída de `curl.exe -s http://localhost:8000/q03-variaveis-aspas/aspas.php`**

```text
<p>Aluna: Maria</p>
<p>Aluna: Maria</p>
<p>Aluna: $nome</p>
<p>Cursa o 2º semestre de ADS</p>
<p>MariaADS</p>
<p>Atalho: Maria</p>
```

**Explicação linha por linha:**

- Linha 1: o `.` concatena o texto `"Aluna: "` com o valor de `$nome`. Resultado: `Aluna: Maria`.
- Linha 2: as aspas duplas fazem a interpolação, ou seja, o PHP troca `$nome` por `Maria`. O resultado é igual ao da linha 1.
- Linha 3: as aspas simples não interpolam. O PHP mostra o texto literal `Aluna: $nome`.
- Linha 4: as chaves isolam `$semestre` e o PHP mostra `2º`. `$curso` é trocado por `ADS`.
- Linha 5: `$nome . $curso` junta os dois valores sem nada no meio, por isso sai `MariaADS`.
- Linha 6: `<?= $nome ?>` é o atalho do `echo`, então mostra `Maria`.

## 3. Perguntas

**3.1 Em quais linhas sua previsão errou? Explique o motivo.**

Minha previsão bateu com o resultado nas seis linhas. As mais fáceis de errar são a linha 3, porque quem esquece que aspas simples não interpolam espera ver `Maria`, e a linha 5, porque quem não lembra que o `.` não coloca espaço espera ver `Maria ADS`.

**3.2 Por que `$nome . $curso` aparece sem espaço entre as palavras? Como corrigir?**

O operador `.` só junta os dois valores e não acrescenta nada entre eles. Para ter um espaço, eu coloco um texto com espaço no meio: `$nome . " " . $curso`, que mostra `Maria ADS`. Também funciona com interpolação: `"$nome $curso"`.
