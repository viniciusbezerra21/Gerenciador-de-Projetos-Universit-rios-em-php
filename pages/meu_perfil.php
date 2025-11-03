<?php
session_start();
require_once '../config/security.php';
require_once '../config/database.php';

requireLogin();

$nome = sanitizeInput($_SESSION['usuario_nome']);
$tipo = $_SESSION['usuario_tipo'];
$usuario_id = $_SESSION['usuario_id'];
$usuario_email = sanitizeInput($_SESSION['usuario_email']);

$conexao = getConnection();

if (!$conexao) {
    die("Erro na conexão com o banco de dados");
}

$foto_perfil = null;
$stmt_user = $conexao->prepare("SELECT foto_perfil FROM usuarios WHERE id = ?");
if (!$stmt_user) {
    error_log("Prepare failed: " . $conexao->error);
} else {
    $stmt_user->bind_param("i", $usuario_id);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();
    if ($result_user && $result_user->num_rows > 0) {
        $user_data = $result_user->fetch_assoc();
        $foto_perfil = $user_data['foto_perfil'] ?? null;
    }
    $stmt_user->close();
}

// Filtros
$filtro_area = isset($_GET['area']) ? intval($_GET['area']) : 0;
$filtro_status = isset($_GET['status']) ? intval($_GET['status']) : 0;
$filtro_busca = sanitizeInput($_GET['busca'] ?? '');

$query = "SELECT p.*, o.nome as orientador_nome, a.nome as area_nome, s.descricao as status_descricao 
          FROM projetos p 
          LEFT JOIN orientadores o ON p.id_orientador = o.id 
          LEFT JOIN areas a ON p.id_area = a.id 
          LEFT JOIN status s ON p.status = s.id ";

$where_conditions = [];
$params = [];
$types = '';

if ($tipo === 'orientador') {
    $stmt = $conexao->prepare("SELECT id FROM orientadores WHERE email = ? LIMIT 1");
    if (!$stmt) {
        error_log("Prepare orientador failed: " . $conexao->error);
    } else {
        $stmt->bind_param("s", $usuario_email);
        $stmt->execute();
        $result_orientador = $stmt->get_result();
        
        if ($result_orientador && $result_orientador->num_rows > 0) {
            $orientador = $result_orientador->fetch_assoc();
            $where_conditions[] = "p.id_orientador = ?";
            $params[] = $orientador['id'];
            $types .= 'i';
        } else {
            $where_conditions[] = "1=0";
        }
        $stmt->close();
    }
} else if ($tipo === 'aluno') {
    $stmt = $conexao->prepare("SELECT id FROM alunos WHERE email = ? LIMIT 1");
    if (!$stmt) {
        error_log("Prepare aluno failed: " . $conexao->error);
    } else {
        $stmt->bind_param("s", $usuario_email);
        $stmt->execute();
        $result_aluno = $stmt->get_result();
        
        if ($result_aluno && $result_aluno->num_rows > 0) {
            $aluno = $result_aluno->fetch_assoc();
            $query .= "INNER JOIN projetos_alunos pa ON p.id = pa.id_projeto ";
            $where_conditions[] = "pa.id_aluno = ?";
            $params[] = $aluno['id'];
            $types .= 'i';
        } else {
            $where_conditions[] = "1=0";
        }
        $stmt->close();
    }
}

// Add WHERE clause
$query .= "WHERE " . (count($where_conditions) > 0 ? implode(" AND ", $where_conditions) : "1=1");

// Apply filters
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
if (!$stmt) {
    error_log("Prepare query failed: " . $conexao->error);
    $projetos = [];
} else {
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $projetos = [];
    while ($row = $result->fetch_assoc()) {
        $projetos[] = $row;
    }
    $stmt->close();
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
    <title>Meu Perfil - Meus Projetos</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* Added styles for profile section */
        .perfil-header {
            background: linear-gradient(135deg, #59a4eb 0%, #3d7bb8 100%);
            color: white;
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 30px;
        }
        .perfil-foto {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            flex-shrink: 0;
        }
        .perfil-foto-placeholder {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 4px solid white;
            flex-shrink: 0;
            color: white;
            font-size: 12px;
            text-align: center;
            padding: 10px;
        }
        .perfil-info {
            flex: 1;
        }
        .perfil-info h1 {
            margin: 0 0 10px 0;
            font-size: 28px;
        }
        .perfil-info p {
            margin: 5px 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .btn-editar-perfil {
            background-color: white;
            color: #59a4eb;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            cursor: pointer;
            font-weight: 600;
            display: inline-block;
            margin-top: 10px;
        }
        .btn-editar-perfil:hover {
            background-color: #f0f0f0;
        }
    </style>
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
            <!-- Added profile header with photo display -->
            <div class="perfil-header">
                <?php if ($foto_perfil && file_exists('../uploads/perfil/' . $foto_perfil)): ?>
                    <img src="../uploads/perfil/<?php echo htmlspecialchars($foto_perfil); ?>" 
                         alt="Foto de perfil" 
                         class="perfil-foto">
                <?php else: ?>
                    <div class="perfil-foto-placeholder">Sem foto</div>
                <?php endif; ?>
                
                <div class="perfil-info">
                    <h1><?php echo htmlspecialchars($nome); ?></h1>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario_email); ?></p>
                    <p><strong>Tipo:</strong> <?php echo htmlspecialchars(ucfirst($tipo)); ?></p>
                    <a href="editar_perfil.php" class="btn-editar-perfil">Editar Perfil</a>
                </div>
            </div>

            <h1 class="titulo">Meu Perfil - Meus Projetos</h1>

            <div class="apresentacao">
                <p>Aqui estão todos os seus projetos cadastrados (Tipo de usuário: <?php echo htmlspecialchars($tipo); ?>)</p>
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
                    <a href="meu_perfil.php" class="btn-limpar">Limpar</a>
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
                                            <strong>Orientador:</strong> <?php echo htmlspecialchars($projeto['orientador_nome'] ?? 'N/A'); ?>
                                        </span>
                                    </div>
                                    
                                    <a href="detalhes_projeto.php?id=<?php echo $projeto['id']; ?>" class="btn-card">Ver Detalhes</a>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="sem-projetos-container">
                            <p class="sem-projetos">Você ainda não cadastrou nenhum projeto. <a href="cadastrar_projeto.php">Cadastre seu primeiro projeto!</a></p>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </main>

    <script src="../js/projeto.js"></script>
</body>
</html>
