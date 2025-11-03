<?php
session_start();
require_once '../config/database.php';

$conexao = getConnection();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$nome = $_SESSION['usuario_nome'];
$tipo = $_SESSION['usuario_tipo'];

$query_total_projetos = "SELECT COUNT(*) as total FROM projetos";
$result_total_projetos = $conexao->query($query_total_projetos);
$total_projetos = $result_total_projetos->fetch_assoc()['total'];

$query_projetos_por_area = "SELECT a.nome as area, COUNT(p.id) as total 
                            FROM areas a 
                            LEFT JOIN projetos p ON a.id = p.id_area 
                            GROUP BY a.id, a.nome 
                            ORDER BY total DESC";
$result_projetos_por_area = $conexao->query($query_projetos_por_area);

$query_projetos_por_status = "SELECT s.descricao as status, COUNT(p.id) as total 
                              FROM status s 
                              LEFT JOIN projetos p ON s.id = p.status 
                              GROUP BY s.id, s.descricao 
                              ORDER BY total DESC";
$result_projetos_por_status = $conexao->query($query_projetos_por_status);

$query_total_alunos = "SELECT COUNT(*) as total FROM alunos";
$result_total_alunos = $conexao->query($query_total_alunos);
$total_alunos = $result_total_alunos->fetch_assoc()['total'];

$query_total_orientadores = "SELECT COUNT(*) as total FROM orientadores";
$result_total_orientadores = $conexao->query($query_total_orientadores);
$total_orientadores = $result_total_orientadores->fetch_assoc()['total'];

$query_projetos_recentes = "SELECT p.titulo, p.data_cadastro, o.nome as orientador, a.nome as area, s.descricao as status 
                            FROM projetos p 
                            LEFT JOIN orientadores o ON p.id_orientador = o.id 
                            LEFT JOIN areas a ON p.id_area = a.id 
                            LEFT JOIN status s ON p.status = s.id 
                            ORDER BY p.data_cadastro DESC 
                            LIMIT 10";
$result_projetos_recentes = $conexao->query($query_projetos_recentes);

$conexao->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios - Sistema de Projetos</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        .relatorios-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: #3498db;
            margin-bottom: 10px;
        }

        .stat-label {
            font-size: 1.1rem;
            color: #7f8c8d;
        }

        .relatorio-section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .relatorio-section h2 {
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e0e0e0;
        }

        .relatorio-table {
            width: 100%;
            border-collapse: collapse;
        }

        .relatorio-table th,
        .relatorio-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        .relatorio-table th {
            background-color: #f8f9fa;
            color: #2c3e50;
            font-weight: 600;
        }

        .relatorio-table tr:hover {
            background-color: #f8f9fa;
        }

        .btn-imprimir {
            padding: 12px 25px;
            background-color: #27ae60;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-bottom: 20px;
        }

        .btn-imprimir:hover {
            background-color: #229954;
        }

        @media print {

            aside,
            .btn-imprimir {
                display: none;
            }

            main {
                margin-left: 0;
            }
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
            <h1 class="titulo">Relatórios e Estatísticas</h1>

            <div class="apresentacao">
                <p>Visualize estatísticas e relatórios do sistema</p>
            </div>

            <button onclick="window.print()" class="btn-imprimir">Imprimir Relatório</button>

            <div class="relatorios-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $total_projetos; ?></div>
                    <div class="stat-label">Total de Projetos</div>
                </div>

                <div class="stat-card">
                    <div class="stat-number"><?php echo $total_alunos; ?></div>
                    <div class="stat-label">Total de Alunos</div>
                </div>

                <div class="stat-card">
                    <div class="stat-number"><?php echo $total_orientadores; ?></div>
                    <div class="stat-label">Total de Orientadores</div>
                </div>
            </div>

            <div class="relatorio-section">
                <h2>Projetos por Área</h2>
                <table class="relatorio-table">
                    <thead>
                        <tr>
                            <th>Área</th>
                            <th>Quantidade de Projetos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result_projetos_por_area->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['area']); ?></td>
                                <td><?php echo $row['total']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <div class="relatorio-section">
                <h2>Projetos por Status</h2>
                <table class="relatorio-table">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Quantidade de Projetos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result_projetos_por_status->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['status']); ?></td>
                                <td><?php echo $row['total']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <div class="relatorio-section">
                <h2>Projetos Recentes</h2>
                <table class="relatorio-table">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Orientador</th>
                            <th>Área</th>
                            <th>Status</th>
                            <th>Data de Cadastro</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result_projetos_recentes->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['titulo']); ?></td>
                                <td><?php echo htmlspecialchars($row['orientador']); ?></td>
                                <td><?php echo htmlspecialchars($row['area']); ?></td>
                                <td><?php echo htmlspecialchars($row['status']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($row['data_cadastro'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script src="../js/projeto.js"></script>
</body>

</html>
