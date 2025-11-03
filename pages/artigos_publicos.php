<?php
session_start();
require_once '../config/database.php';

$usuario_logado = isset($_SESSION['usuario_id']);
$nome = $usuario_logado ? $_SESSION['usuario_nome'] : '';

$conexao = getConnection();

if (!$conexao) {
    die("Erro na conexão com o banco de dados");
}

// Filtros
$filtro_area = isset($_GET['area']) ? $_GET['area'] : '';
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

// Buscar áreas para os filtros
$query_areas = "SELECT id, nome FROM areas ORDER BY nome";
$result_areas = $conexao->query($query_areas);
$areas = [];
if ($result_areas) {
    while ($row = $result_areas->fetch_assoc()) {
        $areas[] = $row;
    }
}

$conexao->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artigos Públicos - Gerenciamento de Projetos</title>
    <link rel="stylesheet" href="../css/index.css">
    <?php if ($usuario_logado): ?>
    <link rel="stylesheet" href="../css/style.css">
    <?php endif; ?>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* Estilos específicos para a página de artigos públicos */
        body {
            background-color: #f5f5f5;
            font-family: 'Roboto', sans-serif;
        }

        /* Remover sidebar - página é pública */
        <?php if ($usuario_logado): ?>
        body {
            display: flex;
            min-height: 100vh;
        }
        <?php endif; ?>

        .public-header {
            background: linear-gradient(135deg, #3d5a80 0%, #59a4eb 100%);
            color: white;
            padding: 20px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .public-header .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .public-header h1 {
            font-size: 1.8rem;
            margin: 0;
        }

        .public-header .btn-voltar {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .public-header .btn-voltar:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }

        .public-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .page-intro {
            text-align: center;
            margin-bottom: 40px;
        }

        .page-intro h2 {
            font-size: 2rem;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .page-intro p {
            font-size: 1.1rem;
            color: #7f8c8d;
        }

        .filtros-publicos {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .filtros-form {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
        }

        .filtro-group {
            flex: 1;
            min-width: 200px;
        }

        .input-busca,
        .select-filtro {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .input-busca:focus,
        .select-filtro:focus {
            outline: none;
            border-color: #59a4eb;
        }

        .btn-filtrar,
        .btn-limpar {
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-filtrar {
            background-color: #59a4eb;
            color: white;
        }

        .btn-filtrar:hover {
            background-color: #3d5a80;
        }

        .btn-limpar {
            background-color: #95a5a6;
            color: white;
        }

        .btn-limpar:hover {
            background-color: #7f8c8d;
        }

        .artigos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }

        .artigo-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .artigo-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }

        .artigo-imagem {
            width: 100%;
            height: 200px;
            overflow: hidden;
            background: linear-gradient(135deg, #3d5a80 0%, #59a4eb 100%);
        }

        .artigo-imagem img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .artigo-info {
            padding: 20px;
        }

        .artigo-titulo {
            font-size: 1.4rem;
            color: #2c3e50;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .artigo-resumo {
            color: #7f8c8d;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .artigo-meta {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .meta-item {
            font-size: 0.9rem;
            color: #555;
        }

        .meta-item strong {
            color: #2c3e50;
        }

        .badge-status {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            color: white;
        }

        .badge-status.aprovado {
            background-color: #27ae60;
        }

        .badge-status.em-andamento {
            background-color: #f39c12;
        }

        .badge-status.pendente {
            background-color: #e74c3c;
        }

        .sem-artigos {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .sem-artigos p {
            font-size: 1.2rem;
            color: #95a5a6;
            margin-bottom: 20px;
        }

        .sem-artigos a {
            display: inline-block;
            padding: 12px 30px;
            background-color: #59a4eb;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .sem-artigos a:hover {
            background-color: #3d5a80;
        }

        @media (max-width: 768px) {
            .artigos-grid {
                grid-template-columns: 1fr;
            }

            .filtros-form {
                flex-direction: column;
            }

            .filtro-group {
                width: 100%;
            }

            .public-header .container {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <header class="public-header">
        <div class="container">
            <h1>📚 Artigos Publicados</h1>
            <?php if ($usuario_logado): ?>
                <a href="meu_perfil.php" class="btn-voltar">← Meu Perfil</a>
            <?php else: ?>
                <a href="../index.php" class="btn-voltar">← Voltar ao Início</a>
            <?php endif; ?>
        </div>
    </header>

    <div class="public-container">
        <div class="page-intro">
            <h2>Explore os Projetos da Nossa Comunidade</h2>
            <p>Conheça os projetos de pesquisa cadastrados por estudantes e orientadores</p>
        </div>

        <!-- Filtros -->
        <div class="filtros-publicos">
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

                <button type="submit" class="btn-filtrar">Filtrar</button>
                <a href="artigos_publicos.php" class="btn-limpar">Limpar</a>
            </form>
        </div>

        <!-- Lista de Artigos -->
        <?php if (count($projetos) > 0): ?>
            <div class="artigos-grid">
                <?php foreach ($projetos as $projeto): ?>
                    <div class="artigo-card">
                        <div class="artigo-imagem">
                            <?php if (!empty($projeto['imagem']) && file_exists('../uploads/' . $projeto['imagem'])): ?>
                                <img src="../uploads/<?php echo htmlspecialchars($projeto['imagem']); ?>" 
                                     alt="<?php echo htmlspecialchars($projeto['titulo']); ?>">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/350x200/3d5a80/ffffff?text=Artigo+Acadêmico" 
                                     alt="Imagem padrão">
                            <?php endif; ?>
                        </div>
                        
                        <div class="artigo-info">
                            <h3 class="artigo-titulo"><?php echo htmlspecialchars($projeto['titulo']); ?></h3>
                            <p class="artigo-resumo"><?php echo htmlspecialchars(substr($projeto['resumo'], 0, 150)) . '...'; ?></p>
                            
                            <div class="artigo-meta">
                                <span class="meta-item">
                                    <strong>📂 Área:</strong> <?php echo htmlspecialchars($projeto['area_nome']); ?>
                                </span>
                                <span class="meta-item">
                                    <strong>👨‍🏫 Orientador:</strong> <?php echo htmlspecialchars($projeto['orientador_nome'] ?? 'N/A'); ?>
                                </span>
                                <span class="meta-item">
                                    <strong>📅 Cadastrado em:</strong> <?php echo date('d/m/Y', strtotime($projeto['data_cadastro'])); ?>
                                </span>
                            </div>
                            
                            <?php 
                            $status_class = 'pendente';
                            if ($projeto['status'] == 8) {
                                $status_class = 'aprovado';
                            } elseif ($projeto['status'] == 5) {
                                $status_class = 'em-andamento';
                            }
                            ?>
                            <span class="badge-status <?php echo $status_class; ?>">✓ <?php echo htmlspecialchars($projeto['status_descricao']); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="sem-artigos">
                <p>Nenhum projeto encontrado no momento.</p>
                <?php if ($usuario_logado): ?>
                    <a href="cadastrar_projeto.php">Cadastrar Novo Projeto</a>
                <?php else: ?>
                    <a href="cadastro.php">Cadastre-se Agora</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
