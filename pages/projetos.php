<?php
require_once '../config/security.php';
require_once '../config/database.php';

requireLogin();

$nome = sanitizeInput($_SESSION['usuario_nome']);
$tipo = $_SESSION['usuario_tipo'];
$usuario_id = $_SESSION['usuario_id'];

$conexao = getConnection();

if (!$conexao) {
    die("Erro na conexão com o banco de dados");
}

// Filtros
$filtro_area = isset($_GET['area']) ? intval($_GET['area']) : 0;
$filtro_status = isset($_GET['status']) ? intval($_GET['status']) : 0;
$filtro_busca = sanitizeInput($_GET['busca'] ?? '');

$query = "SELECT p.*, o.nome as orientador_nome, a.nome as area_nome, s.descricao as status_descricao 
          FROM projetos p 
          LEFT JOIN orientadores o ON p.id_orientador = o.id 
          LEFT JOIN areas a ON p.id_area = a.id 
          LEFT JOIN status s ON p.status = s.id 
          WHERE 1=1";

$params = [];
$types = '';

if ($filtro_area > 0) {
    $query .= " AND p.id_area = ?";
    $params[] = $filtro_area;
    $types .= 'i';
}
if ($filtro_status > 0) {
    $query .= " AND p.status = ?";
    $params[] = $filtro_status;
    $types .= 'i';
}
if (!empty($filtro_busca)) {
    $query .= " AND (p.titulo LIKE ? OR p.resumo LIKE ?)";
    $search_term = "%$filtro_busca%";
    $params[] = $search_term;
    $params[] = $search_term;
    $types .= 'ss';
}

$query .= " ORDER BY p.data_cadastro DESC";
$stmt = $conexao->prepare($query);

if ($stmt && !empty($params)) {
    $stmt->bind_param($types, ...$params);
}

if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conexao->query($query);
}

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

if (isset($stmt)) {
    $stmt->close();
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
                <li><a href="meu_perfil.php">Meu Perfil</a></li>
                <li><a href="projetos.php">Todos os Projetos</a></li>
                <li><a href="cadastrar_projeto.php">Cadastrar Projeto</a></li>
                <li><a href="relatorios.php">Gerar Relatórios</a></li>
                <li><a href="../index.php">Inicio</a></li>
                
                <li><a href="../php/logout.php">Sair</a></li>
            </ul>
        </div>
    </aside>

    <main>
        <div class="container">
            <h1 class="titulo">Veja projetos de outras pessoas</h1>

            <div class="apresentacao">
                <p>Aqui está uma lista de todos os projetos cadastrados.</p>
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
                                    
                                    <!-- Updated link to go to details page instead of edit page -->
                                    <a href="detalhes_projeto.php?id=<?php echo $projeto['id']; ?>" class="btn-card">Ver Detalhes</a>
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
