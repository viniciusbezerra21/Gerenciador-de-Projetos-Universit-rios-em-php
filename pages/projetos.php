<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$nome = $_SESSION['usuario_nome'];
$tipo = $_SESSION['usuario_tipo'];
$usuario_id = $_SESSION['usuario_id'];

$conexao = getConnection();

if (!$conexao) {
    die("Erro na conexão com o banco de dados");
}

// Filtros
$filtro_area = isset($_GET['area']) ? $_GET['area'] : '';
$filtro_status = isset($_GET['status']) ? $_GET['status'] : '';
$filtro_busca = isset($_GET['busca']) ? $_GET['busca'] : '';

$query = "SELECT p.*, o.nome as orientador_nome, a.nome as area_nome, s.descricao as status_descricao 
          FROM projetos p 
          LEFT JOIN orientadores o ON p.id_orientador = o.id 
          LEFT JOIN areas a ON p.id_area = a.id 
          LEFT JOIN status s ON p.status = s.id 
          WHERE 1=1";

// Aplicar filtros
if (!empty($filtro_area)) {
    $query .= " AND p.id_area = " . intval($filtro_area);
}
if (!empty($filtro_status)) {
    $query .= " AND p.status = " . intval($filtro_status);
}
if (!empty($filtro_busca)) {
    $filtro_busca_safe = $conexao->real_escape_string($filtro_busca);
    $query .= " AND (p.titulo LIKE '%$filtro_busca_safe%' OR p.resumo LIKE '%$filtro_busca_safe%')";
}

$query .= " ORDER BY p.data_cadastro DESC";
$result = $conexao->query($query);

if (!$result) {
    die("Erro na consulta: " . $conexao->error);
}

$projetos = [];
while ($row = $result->fetch_assoc()) {
    $projetos[] = $row;
}

// Buscar áreas e status para os filtros
$query_areas = "SELECT id, nome FROM areas ORDER BY nome";
$result_areas = $conexao->query($query_areas);
$areas = [];
if ($result_areas) {
    while ($row = $result_areas->fetch_assoc()) {
        $areas[] = $row;
    }
}

$query_status = "SELECT id, descricao FROM status ORDER BY id";
$result_status = $conexao->query($query_status);
$status_list = [];
if ($result_status) {
    while ($row = $result_status->fetch_assoc()) {
        $status_list[] = $row;
    }
}

$conexao->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Projetos</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <aside>
        <h2 class="titulo-sidebar">Bem-vindo(a), <?php echo htmlspecialchars($nome); ?>!</h2>
        <div class="funcoes-sidebar">
            <ul>
                <li><a href="projetos.php">Seus Projetos</a></li>
                <li><a href="cadastrar_projeto.php">Cadastrar Projeto</a></li>
                <li><a href="relatorios.php">Gerar Relatórios</a></li>
                <li><a href="../php/logout.php">Sair</a></li>
            </ul>
        </div>
    </aside>

    <main>
        <div class="container">
            <h1 class="titulo">Veja seus Projetos</h1>

            <div class="apresentacao">
                <p>Seus projetos cadastrados</p>
            </div>

            <!-- Filtros -->
            <div class="filtros-container">
                <form method="GET" action="" class="filtros-form">
                    <div class="filtro-group">
                        <input type="text" 
                               name="busca" 
                               placeholder="Buscar por título ou resumo..." 
                               value="<?php echo htmlspecialchars($filtro_busca); ?>"
                               class="input-busca">
                    </div>

                    <div class="filtro-group">
                        <select name="area" class="select-filtro">
                            <option value="">Todas as Áreas</option>
                            <?php foreach ($areas as $area): ?>
                                <option value="<?php echo $area['id']; ?>" 
                                        <?php echo ($filtro_area == $area['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($area['nome']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="filtro-group">
                        <select name="status" class="select-filtro">
                            <option value="">Todos os Status</option>
                            <?php foreach ($status_list as $status): ?>
                                <option value="<?php echo $status['id']; ?>" 
                                        <?php echo ($filtro_status == $status['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($status['descricao']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn-filtrar">Filtrar</button>
                    <a href="projetos.php" class="btn-limpar">Limpar</a>
                </form>
            </div>

            <!-- Lista de Projetos -->
            <div class="lista-projetos">
                <ul id="projetos">
                    <?php if (count($projetos) > 0): ?>
                        <?php foreach ($projetos as $projeto): ?>
                            <li class="projeto-card">
                                <div class="imagem-projeto">
                                    <?php if (!empty($projeto['imagem']) && file_exists('../uploads/' . $projeto['imagem'])): ?>
                                        <img src="../uploads/<?php echo htmlspecialchars($projeto['imagem']); ?>" 
                                             alt="<?php echo htmlspecialchars($projeto['titulo']); ?>">
                                    <?php else: ?>
                                        <img src="https://via.placeholder.com/350x200?text=Sem+Imagem" 
                                             alt="Sem imagem">
                                    <?php endif; ?>
                                </div>
                                
                                <div class="projeto-info">
                                    <h3 class="titulo-card"><?php echo htmlspecialchars($projeto['titulo']); ?></h3>
                                    <p class="descricao-card"><?php echo htmlspecialchars(substr($projeto['resumo'], 0, 150)) . '...'; ?></p>
                                    
                                    <div class="projeto-meta">
                                        <span class="meta-item">
                                            <strong>Área:</strong> <?php echo htmlspecialchars($projeto['area_nome']); ?>
                                        </span>
                                        <span class="meta-item">
                                            <strong>Status:</strong> <?php echo htmlspecialchars($projeto['status_descricao']); ?>
                                        </span>
                                        <span class="meta-item">
                                            <strong>Orientador:</strong> <?php echo htmlspecialchars($projeto['orientador_nome']); ?>
                                        </span>
                                    </div>
                                    
                                    <a href="editar_projeto.php?id=<?php echo $projeto['id']; ?>" class="btn-card">Ver Detalhes</a>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="sem-projetos-container">
                            <p class="sem-projetos">Nenhum projeto encontrado.</p>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </main>

    <script src="../js/projeto.js"></script>
</body>
</html>
