# Programação Web I: atividades

**Tecnologia em ADS** | IFCE Campus Boa Viagem
Prof. Dr. Renato William Rodrigues de Souza

## Identificação

- **Nome:** Halef
- **Matrícula:** 20251283000224
- **Sistema operacional:** Linux

## Sobre este repositório

Este é o meu repositório de atividades da disciplina. Cada aula tem a sua própria pasta, e cada atividade tem um `respostas.md` com o código analisado, as saídas do terminal e as explicações.

## Aulas

| Aula | Pasta | Tema | Situação |
|---|---|---|---|
| 01 | [`aula01-ferramentas`](aula01-ferramentas/) | Ferramentas para inspecionar a Web | ✅ |
| 02 | [`aula02-php-http`](aula02-php-http/) | PHP e HTTP na prática | ✅ |
| 06 | [`aula06-pratica`](aula06-pratica/) | Laravel na prática: rotas, views e controllers | ✅ |

## Projeto integrador: Perfil do Estudante

| Versão | Pasta | O que entra | Situação |
|---|---|---|---|
| v1 | [`projeto/v1`](projeto/v1/) | Páginas por URL, tema com cookie, 404 e API JSON | ⬜ |

Troque ⬜ por ✅ quando concluir uma aula.

## Como rodar o projeto da aula 06

Dentro de [`aula06-pratica/`](aula06-pratica/):

```bash
composer install
cp .env.example .env
php artisan key:generate
sed -i 's/^SESSION_DRIVER=database/SESSION_DRIVER=file/' .env
php artisan serve
```

Depois acesse `http://127.0.0.1:8000`. Nesta máquina o `vendor/`, o `.env` e a chave já estão prontos: basta rodar `php artisan serve`.

As saídas e explicações das dez atividades estão em [`aula06-pratica/respostas.md`](aula06-pratica/respostas.md).

## Padrão de commits

`tipo(aulaXX-qYY): descrição no imperativo`

- `docs(aula02-q01): adiciona análise e log do servidor`
- `fix(aula02-q02): corrige erro de sintaxe`
- `feat(aula02-q09): cria api que aceita apenas POST`
- `feat(projeto-v1): adiciona página de projetos`
- `feat(aula06-a01): cria rotas /meu-nome e /cartao`
- `docs(aula06-a10): registra testes e respostas`

## Regras de privacidade

Não envie cookies, senhas, IP público, telefone ou e-mail pessoal.
