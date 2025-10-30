<?php
session_start();
require_once '../config/database.php';

// Cria conexão
$conexao = getConnection();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$nome = $_SESSION['usuario_nome'];
$tipo = $_SESSION['usuario_tipo'];
$mensagem = '';
$tipo_mensagem = '';

// Buscar orientadores e áreas
$query_orientadores = "SELECT id, nome FROM orientadores ORDER BY nome";
$result_orientadores = $conexao->query($query_orientadores);

$query_areas = "SELECT id, nome FROM areas ORDER BY nome";
$result_areas = $conexao->query($query_areas);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $resumo = trim($_POST['resumo']);
    $id_orientador = $_POST['id_orientador'];
    $id_area = $_POST['id_area'];
    $status = 1; // Status padrão: Em andamento

    if (empty($titulo) || empty($resumo) || empty($id_orientador) || empty($id_area)) {
        $mensagem = 'Todos os campos são obrigatórios!';
        $tipo_mensagem = 'erro';
    } else {
        $stmt = $conexao->prepare("INSERT INTO projetos (titulo, resumo, id_orientador, id_area, status, data_cadastro) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssiii", $titulo, $resumo, $id_orientador, $id_area, $status);
        
        if ($stmt->execute()) {
            $mensagem = 'Projeto cadastrado com sucesso!';
            $tipo_mensagem = 'sucesso';
        } else {
            $mensagem = 'Erro ao cadastrar projeto: ' . $conexao->error;
            $tipo_mensagem = 'erro';
        }
        $stmt->close();
    }
}

// Fecha conexão no final (boa prática)
$conexao->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Projeto</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
<aside>
        <h2 class="titulo-sidebar">Bem-vindo(a), <?php echo htmlspecialchars($nome); ?>!</h2>
        <div class="funcoes-sidebar">
            <ul>
                <li><a href="dashboard.php">Seus Projetos</a></li>
                <li><a href="cadastrar_projeto.php">Cadastrar Projeto</a></li>
                <li><a href="relatorios.php">Gerar Relatórios</a></li>
                <li><a href="../php/logout.php">Sair</a></li>
            </ul>
        </div>
    </aside>

    <main>
        <div class="container">
            <h1 class="titulo">Cadastrar Projeto</h1>

            <div class="apresentacao">
                <p>Preencha o formulário abaixo para cadastrar um novo projeto acadêmico.</p>
            </div>

            <?php if ($mensagem): ?>
                <div class="mensagem <?php echo $tipo_mensagem; ?>">
                    <?php echo htmlspecialchars($mensagem); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/php/validar_cadastro.php" id="formCadastro" class="form-projeto">
                <div class="form-group">
                    <label for="titulo">Título</label>
                    <input type="text" id="titulo" name="titulo" required placeholder="Digite o título do projeto">
                </div>
                
                <div class="form-group">
                    <label for="resumo">Resumo</label>
                    <textarea id="resumo" name="resumo" required placeholder="Digite o resumo do projeto"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="id_orientador">Orientador</label>
                    <select name="id_orientador" id="id_orientador" required>
                        <option value="">Selecione...</option>
                        <?php while ($row = $result_orientadores->fetch_assoc()): ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo $row['nome']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="id_area">Área</label>
                    <select name="id_area" id="id_area" required>
                        <option value="">Selecione...</option>
                        <?php while ($row = $result_areas->fetch_assoc()): ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo $row['nome']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <button type="submit" class="btn-primary">Cadastrar Projeto</button>
            </form>

        </div>
    </main>
</body>
</html>
