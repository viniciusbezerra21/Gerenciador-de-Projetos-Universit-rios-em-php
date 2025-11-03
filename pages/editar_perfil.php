<?php
session_start();
require_once '../config/database.php';
require_once '../config/security.php';

requireLogin();

$conexao = getConnection();
$usuario_id = $_SESSION['usuario_id'];
$usuario_nome = sanitizeInput($_SESSION['usuario_nome']);
$usuario_tipo = $_SESSION['usuario_tipo'];
$usuario_email = sanitizeInput($_SESSION['usuario_email']);
$mensagem = '';
$tipo_mensagem = '';

// Buscar dados atuais do usuário
$stmt = $conexao->prepare("SELECT * FROM usuarios WHERE id = ?");
if (!$stmt) {
    die('Erro na preparação da query: ' . $conexao->error);
}
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
$stmt->close();

// Buscar foto de perfil
$foto_perfil = null;
if ($usuario && !empty($usuario['foto_perfil']) && file_exists('../uploads/perfil/' . $usuario['foto_perfil'])) {
    $foto_perfil = $usuario['foto_perfil'];
}

$csrf_token = generateCSRFToken();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
        $mensagem = 'Erro de segurança. Tente novamente.';
        $tipo_mensagem = 'erro';
    } else {
        // Processar upload de foto
        if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['foto_perfil']['error'] !== UPLOAD_ERR_OK) {
                $erros_upload = [
                    UPLOAD_ERR_INI_SIZE => 'Arquivo excede o tamanho máximo do servidor.',
                    UPLOAD_ERR_FORM_SIZE => 'Arquivo excede o tamanho máximo do formulário.',
                    UPLOAD_ERR_PARTIAL => 'Upload do arquivo foi incompleto.',
                    UPLOAD_ERR_NO_FILE => 'Nenhum arquivo foi enviado.',
                    UPLOAD_ERR_NO_TMP_DIR => 'Pasta temporária não encontrada.',
                    UPLOAD_ERR_CANT_WRITE => 'Não foi possível escrever o arquivo no disco.',
                    UPLOAD_ERR_EXTENSION => 'Uma extensão PHP interrompeu o upload.'
                ];
                $mensagem = $erros_upload[$_FILES['foto_perfil']['error']] ?? 'Erro desconhecido no upload.';
                $tipo_mensagem = 'erro';
            } else {
                $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'gif'];
                $nome_arquivo = $_FILES['foto_perfil']['name'];
                $tamanho = $_FILES['foto_perfil']['size'];
                $extensao = strtolower(pathinfo($nome_arquivo, PATHINFO_EXTENSION));
                
                // Validar extensão e tamanho (máximo 5MB)
                if (!in_array($extensao, $extensoes_permitidas)) {
                    $mensagem = 'Formato de imagem não permitido! Use JPG, JPEG, PNG ou GIF.';
                    $tipo_mensagem = 'erro';
                } elseif ($tamanho > 5 * 1024 * 1024) {
                    $mensagem = 'Arquivo muito grande! Máximo 5MB.';
                    $tipo_mensagem = 'erro';
                } else {
                    $dir_perfil = '../uploads/perfil';
                    if (!is_dir($dir_perfil)) {
                        if (!@mkdir($dir_perfil, 0755, true)) {
                            $mensagem = 'Erro ao criar diretório de uploads. Verifique as permissões.';
                            $tipo_mensagem = 'erro';
                        }
                    }
                    
                    // Verificar se o diretório foi criado com sucesso
                    if ($tipo_mensagem !== 'erro' && is_dir($dir_perfil)) {
                        // Deletar foto antiga se existir
                        if ($usuario && !empty($usuario['foto_perfil'])) {
                            $foto_antiga = $dir_perfil . '/' . $usuario['foto_perfil'];
                            if (file_exists($foto_antiga)) {
                                @unlink($foto_antiga);
                            }
                        }
                        
                        // Salvar nova foto
                        $foto_nome = 'perfil_' . $usuario_id . '_' . time() . '.' . $extensao;
                        $caminho_destino = $dir_perfil . '/' . $foto_nome;
                        
                        if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $caminho_destino)) {
                            $stmt = $conexao->prepare("UPDATE usuarios SET foto_perfil = ? WHERE id = ?");
                            if (!$stmt) {
                                $mensagem = 'Erro ao preparar atualização: ' . $conexao->error;
                                $tipo_mensagem = 'erro';
                                @unlink($caminho_destino); // Remove arquivo se não conseguir atualizar BD
                            } else {
                                $stmt->bind_param("si", $foto_nome, $usuario_id);
                                
                                if ($stmt->execute()) {
                                    $mensagem = 'Foto de perfil atualizada com sucesso!';
                                    $tipo_mensagem = 'sucesso';
                                    $foto_perfil = $foto_nome;
                                } else {
                                    $mensagem = 'Erro ao atualizar foto: ' . $conexao->error;
                                    $tipo_mensagem = 'erro';
                                    @unlink($caminho_destino); // Remove arquivo se não conseguir atualizar BD
                                }
                                $stmt->close();
                            }
                        } else {
                            $mensagem = 'Erro ao fazer upload da imagem! Verifique as permissões da pasta.';
                            $tipo_mensagem = 'erro';
                        }
                    }
                }
            }
        } else {
            $mensagem = 'Por favor, selecione uma imagem.';
            $tipo_mensagem = 'erro';
        }
    }
}

$conexao->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        .perfil-section {
            background-color: #f8f9fa;
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 30px;
            text-align: center;
        }
        .foto-perfil-atual {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 20px;
            border: 4px solid #59a4eb;
        }
        .foto-placeholder {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background-color: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            border: 4px solid #59a4eb;
            color: #999;
            font-size: 14px;
        }
        .info-usuario {
            margin: 20px 0;
        }
        .info-usuario p {
            margin: 8px 0;
            font-size: 16px;
        }
        .info-usuario strong {
            color: #333;
        }
    </style>
</head>
<body>
<aside>
    <h2 class="titulo-sidebar">Bem-vindo(a), <?php echo htmlspecialchars($usuario_nome); ?>!</h2>
    <div class="funcoes-sidebar">
        <ul>
            <li><a href="meu_perfil.php">Meu Perfil</a></li>
            <li><a href="editar_perfil.php">Editar Perfil</a></li>
            <li><a href="projetos.php">Todos os Projetos</a></li>
            <li><a href="cadastrar_projeto.php">Cadastrar Projeto</a></li>
            <li><a href="relatorios.php">Gerar Relatórios</a></li>
            <li><a href="../index.php">Inicio</a></li>
            <li><a href="../php/logout.php">Sair</a></li>
        </ul>
    </div>
</aside>

<main class="main-content">
    <div class="content-card">
        <h2>Editar Perfil</h2>
        
        <?php if ($mensagem): ?>
            <div class="mensagem <?php echo $tipo_mensagem; ?>">
                <?php echo htmlspecialchars($mensagem); ?>
            </div>
        <?php endif; ?>

        <div class="perfil-section">
            <h3>Foto de Perfil</h3>
            
            <?php if ($foto_perfil): ?>
                <img src="../uploads/perfil/<?php echo htmlspecialchars($foto_perfil); ?>" 
                     alt="Foto de perfil" 
                     class="foto-perfil-atual">
            <?php else: ?>
                <div class="foto-placeholder">Sem foto</div>
            <?php endif; ?>
            
            <div class="info-usuario">
                <p><strong>Nome:</strong> <?php echo htmlspecialchars($usuario_nome); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario_email); ?></p>
                <p><strong>Tipo de usuário:</strong> <?php echo htmlspecialchars(ucfirst($usuario_tipo)); ?></p>
            </div>
        </div>

        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
            
            <div class="form-group">
                <label for="foto_perfil">Alterar Foto de Perfil</label>
                <input type="file" id="foto_perfil" name="foto_perfil" accept="image/*">
                <small>Formatos aceitos: JPG, JPEG, PNG, GIF. Máximo 5MB.</small>
            </div>

            <button type="submit" class="btn-primary">Atualizar Foto</button>
            <a href="meu_perfil.php" class="btn-primary" style="background-color: #6c757d; text-decoration: none; padding: 10px 20px; border-radius: 4px; display: inline-block;">Voltar</a>
        </form>
    </div>
</main>

<script src="../js/projeto.js"></script>
</body>
</html>
