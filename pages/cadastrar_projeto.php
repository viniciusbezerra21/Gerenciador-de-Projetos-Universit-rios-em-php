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
$mensagem = '';
$tipo_mensagem = '';

$query_orientadores = "SELECT id, nome FROM orientadores ORDER BY nome";
$result_orientadores = $conexao->query($query_orientadores);

$query_status = "SELECT id, descricao AS nome FROM status ORDER BY id";
$result_status = $conexao->query($query_status);

$query_areas = "SELECT id, nome FROM areas ORDER BY nome";
$result_areas = $conexao->query($query_areas);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $resumo = trim($_POST['resumo']);
    $id_orientador = $_POST['id_orientador'];
    $id_area = $_POST['id_area'];
    $status = $_POST['id_status'];
    $imagem_nome = null;

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'gif'];
        $nome_arquivo = $_FILES['imagem']['name'];
        $extensao = strtolower(pathinfo($nome_arquivo, PATHINFO_EXTENSION));
        
        if (in_array($extensao, $extensoes_permitidas)) {
            $imagem_nome = uniqid() . '.' . $extensao;
            $caminho_destino = '../uploads/' . $imagem_nome;
            
            if (!file_exists('../uploads')) {
                mkdir('../uploads', 0777, true);
            }
            
            if (!move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_destino)) {
                $mensagem = 'Erro ao fazer upload da imagem!';
                $tipo_mensagem = 'erro';
                $imagem_nome = null;
            }
        } else {
            $mensagem = 'Formato de imagem não permitido! Use JPG, JPEG, PNG ou GIF.';
            $tipo_mensagem = 'erro';
        }
    }

    if (empty($titulo) || empty($resumo) || empty($id_orientador) || empty($id_area)) {
        $mensagem = 'Todos os campos são obrigatórios!';
        $tipo_mensagem = 'erro';
    } else if ($tipo_mensagem !== 'erro') {
        $stmt = $conexao->prepare("INSERT INTO projetos (titulo, resumo, imagem, id_orientador, id_area, status, data_cadastro) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssiii", $titulo, $resumo, $imagem_nome, $id_orientador, $id_area, $status);
        
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
                <!-- Adicionado links para Meu Perfil e Artigos Públicos -->
                <li><a href="meu_perfil.php">Meu Perfil</a></li>
                <li><a href="projetos.php">Todos os Projetos</a></li>
                <li><a href="cadastrar_projeto.php">Cadastrar Projeto</a></li>
                <li><a href="relatorios.php">Gerar Relatórios</a></li>
                <li><a href="../php/logout.php">Sair</a></li>
            </ul>
        </div>
    </aside>

     <main class="main-content">
        <div class="content-card">
            <h2>Cadastrar Novo Projeto</h2>
            
            <?php if ($mensagem): ?>
                <div class="mensagem <?php echo $tipo_mensagem; ?>">
                    <?php echo htmlspecialchars($mensagem); ?>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="titulo">Título do Projeto</label>
                    <input type="text" id="titulo" name="titulo" required>
                </div>

                <div class="form-group">
                    <label for="resumo">Resumo</label>
                    <textarea id="resumo" name="resumo" rows="5" required></textarea>
                </div>

                <div class="form-group">
                    <label for="imagem">Imagem do Projeto</label>
                    <input type="file" id="imagem" name="imagem" accept="image/*">
                    <small>Formatos aceitos: JPG, JPEG, PNG, GIF</small>
                </div>

                <div class="form-group">
                    <label for="id_orientador">Orientador</label>
                    <select id="id_orientador" name="id_orientador" required>
                        <option value="">Selecione um orientador</option>
                        <?php while ($orientador = $result_orientadores->fetch_assoc()): ?>
                            <option value="<?php echo $orientador['id']; ?>">
                                <?php echo htmlspecialchars($orientador['nome']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="id_area">Área</label>
                    <select id="id_area" name="id_area" required>
                        <option value="">Selecione uma área</option>
                        <?php while ($area = $result_areas->fetch_assoc()): ?>
                            <option value="<?php echo $area['id']; ?>">
                                <?php echo htmlspecialchars($area['nome']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="id_status">Status</label>
                    <select id="id_status" name="id_status" required>
                        <option value="">Selecione um status</option>
                        <?php while ($status = $result_status->fetch_assoc()): ?>
                            <option value="<?php echo $status['id']; ?>">
                                <?php echo htmlspecialchars($status['nome']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <button type="submit" class="btn-primary">Cadastrar Projeto</button>
            </form>
        </div>
    </main>
</body>
</html>
