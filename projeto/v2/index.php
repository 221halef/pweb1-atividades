<?php
class Projeto
{
    private string $titulo;
    private string $descricao;
    private string $tecnologias;

    public function __construct(string $titulo, string $descricao, string $tecnologias)
    {
        if (trim($titulo) === '') {
            throw new Exception('O projeto precisa de um título.');
        }

        $this->titulo = $titulo;
        $this->descricao = $descricao;
        $this->tecnologias = $tecnologias;
    }

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function getDescricao(): string
    {
        return $this->descricao;
    }

    public function getTecnologias(): string
    {
        return $this->tecnologias;
    }

    public function paraArray(): array
    {
        return [
            'titulo' => $this->titulo,
            'descricao' => $this->descricao,
            'tecnologias' => $this->tecnologias,
        ];
    }
}

class Perfil
{
    private string $nome;
    private string $curso;
    private string $campus;
    private string $cidade;
    private string $apresentacao;
    private array $habilidades;
    private array $projetos = [];

    public function __construct(
        string $nome,
        string $curso,
        string $campus,
        string $cidade,
        string $apresentacao,
        array $habilidades
    ) {
        if (count($habilidades) < 5) {
            throw new Exception('O perfil precisa de pelo menos 5 habilidades.');
        }

        $this->nome = $nome;
        $this->curso = $curso;
        $this->campus = $campus;
        $this->cidade = $cidade;
        $this->apresentacao = $apresentacao;
        $this->habilidades = $habilidades;
    }

    public function adicionarProjeto(Projeto $projeto): void
    {
        $this->projetos[count($this->projetos) + 1] = $projeto;
    }

    public function getProjeto(int $id): Projeto
    {
        if (!isset($this->projetos[$id])) {
            throw new Exception('Projeto não encontrado.');
        }

        return $this->projetos[$id];
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getCurso(): string
    {
        return $this->curso;
    }

    public function getCampus(): string
    {
        return $this->campus;
    }

    public function getCidade(): string
    {
        return $this->cidade;
    }

    public function getApresentacao(): string
    {
        return $this->apresentacao;
    }

    public function getHabilidades(): array
    {
        return $this->habilidades;
    }

    public function getProjetos(): array
    {
        return $this->projetos;
    }

    public function paraArray(): array
    {
        $projetos = [];
        foreach ($this->projetos as $id => $projeto) {
            $projetos[$id] = $projeto->paraArray();
        }

        return [
            'nome' => $this->nome,
            'curso' => $this->curso,
            'campus' => $this->campus,
            'cidade' => $this->cidade,
            'apresentacao' => $this->apresentacao,
            'habilidades' => $this->habilidades,
            'projetos' => $projetos,
        ];
    }
}

// Dados do perfil
$perfil = [
// Dados do perfil
$perfil = [
    'nome' => 'João',
    'curso' => 'Tecnologia em Análise e Desenvolvimento de Sistemas',
    'campus' => 'IFCE Campus Boa Viagem',
    'cidade' => 'Boa Viagem, CE',
    'apresentacao' => 'Sou estudante de ADS e quero trabalhar como desenvolvedor backend com Java. Estou aprendendo programação web com PHP, HTML e CSS.',
    'habilidades' => ['HTML', 'CSS', 'PHP', 'Git', 'Java', 'Linux'],
];

$projetos = [
    1 => [
        'titulo' => 'Calculadora de média',
        'descricao' => 'Calcula a média de duas notas recebidas pela URL.',
        'tecnologias' => 'PHP, HTML',
    ],
    2 => [
        'titulo' => 'PizzarIA',
        'descricao' => 'Sistema de pizzaria com atendimento por IA.',
        'tecnologias' => 'UML, Arquitetura de software',
    ],
    3 => [
        'titulo' => 'Perfil do Estudante',
        'descricao' => 'Este site: um perfil pessoal com páginas, tema com cookie e API em JSON.',
        'tecnologias' => 'PHP, HTML, CSS',
    ],
];

// Tema
$tema = $_GET['tema'] ?? $_COOKIE['tema'] ?? 'claro';

if ($tema !== 'claro' && $tema !== 'escuro') {
    $tema = 'claro';
}

if (isset($_GET['tema'])) {
    setcookie('tema', $tema, time() + 60 * 60 * 24 * 30);
}

if ($tema === 'claro') {
    $outroTema = 'escuro';
} else {
    $outroTema = 'claro';
}

// Rota JSON
if (isset($_GET['formato']) && $_GET['formato'] === 'json') {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(
        ['perfil' => $perfil, 'projetos' => $projetos],
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
    );
    exit;
}

// Página
$pagina = $_GET['pagina'] ?? 'inicio';
$titulo = 'Início';

if ($pagina === 'inicio') {
    $titulo = 'Início';
} elseif ($pagina === 'habilidades') {
    $titulo = 'Habilidades';
} elseif ($pagina === 'projetos') {
    $titulo = 'Projetos';
} elseif ($pagina === 'projeto') {
    $id = (int) ($_GET['id'] ?? 0);
    if (isset($projetos[$id])) {
        $titulo = $projetos[$id]['titulo'];
    } else {
        http_response_code(404);
        $pagina = 'projeto-nao-encontrado';
        $titulo = 'Projeto não encontrado';
    }
} else {
    http_response_code(404);
    $pagina = 'nao-encontrada';
    $titulo = 'Página não encontrada';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($titulo) ?> | Meu perfil</title>
  <link rel="stylesheet" href="estilo.css">
</head>
<body class="tema-<?= htmlspecialchars($tema) ?>">
  <!-- Cabeçalho -->
  <header>
    <h1><?= htmlspecialchars($perfil['nome']) ?></h1>
    <p><?= htmlspecialchars($perfil['curso']) ?></p>
    <nav>
      <a href="?pagina=inicio">Início</a>
      <a href="?pagina=habilidades">Habilidades</a>
      <a href="?pagina=projetos">Projetos</a>
      <a class="botao-tema" href="?tema=<?= htmlspecialchars($outroTema) ?>">Tema <?= htmlspecialchars($outroTema) ?></a>
    </nav>
  </header>

  <!-- Conteúdo -->
  <main>
    <?php if ($pagina === 'inicio'): ?>
      <h2>Sobre mim</h2>
      <p><?= htmlspecialchars($perfil['apresentacao']) ?></p>
      <p><strong>Curso:</strong> <?= htmlspecialchars($perfil['curso']) ?></p>
      <p><strong>Campus:</strong> <?= htmlspecialchars($perfil['campus']) ?></p>
      <p><strong>Cidade:</strong> <?= htmlspecialchars($perfil['cidade']) ?></p>

    <?php elseif ($pagina === 'habilidades'): ?>
      <h2>Habilidades</h2>
      <p><?= htmlspecialchars(implode(', ', $perfil['habilidades'])) ?></p>
      <p>Total: <?= count($perfil['habilidades']) ?> habilidades</p>

    <?php elseif ($pagina === 'projetos'): ?>
      <h2>Projetos</h2>
      <div class="cartoes">
        <?php foreach ($projetos as $numero => $projeto): ?>
          <div class="cartao">
            <h3><?= htmlspecialchars($projeto['titulo']) ?></h3>
            <p><?= htmlspecialchars($projeto['tecnologias']) ?></p>
            <a href="?pagina=projeto&amp;id=<?= $numero ?>">Ver detalhes</a>
          </div>
        <?php endforeach; ?>
      </div>

    <?php elseif ($pagina === 'projeto'): ?>
      <h2><?= htmlspecialchars($projetos[$id]['titulo']) ?></h2>
      <p><?= htmlspecialchars($projetos[$id]['descricao']) ?></p>
      <p><strong>Tecnologias:</strong> <?= htmlspecialchars($projetos[$id]['tecnologias']) ?></p>
      <p><a href="?pagina=projetos">Voltar para projetos</a></p>

    <?php elseif ($pagina === 'projeto-nao-encontrado'): ?>
      <h2>Projeto não encontrado</h2>
      <p><a href="?pagina=projetos">Voltar para projetos</a></p>

    <?php else: ?>
      <h2>Página não encontrada</h2>
      <p><a href="?pagina=inicio">Voltar para o início</a></p>
    <?php endif; ?>
  </main>

  <!-- Rodapé -->
  <footer>
    <p>Tema atual: <?= htmlspecialchars($tema) ?> |
       <a href="?formato=json">Ver em JSON</a> |
       <?= date('Y') ?></p>
  </footer>
</body>
</html>
