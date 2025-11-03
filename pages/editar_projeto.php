<?php
session_start();
require_once '../config/database.php';
require_once '../config/security.php';

requireLogin();

$conexao = getConnection();

$nome = $_SESSION['usuario_nome'];
$tipo = $_SESSION['usuario_tipo'];
$usuario_id = $_SESSION['usuario_id'];
$usuario_email = $_SESSION['usuario_email'];
$mensagem = '';
$tipo_mensagem = '';

$id_projeto = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_projeto === 0) {
    header('Location: projetos.php');
    exit;
}

$stmt = $conexao->prepare("SELECT p.*, o.nome as orientador_nome, a.nome as area_nome, s.descricao as status_descricao 
                           FROM projetos p 
                           LEFT JOIN orientadores o ON p.id_orientador = o.id 
                           LEFT JOIN areas a ON p.id_area = a.id 
                           LEFT JOIN status s ON p.status = s.id 
                           WHERE p.id = ?");
$stmt->bind_param("i", $id_projeto);
$stmt->execute();
$result = $stmt->get_result();
$projeto = $result->fetch_assoc();
$stmt->close();

if (!$projeto) {
    header('Location: projetos.php');
    exit;
}

$is_owner = false;
if ($tipo === 'orientador') {
    $stmt = $conexao->prepare("SELECT id FROM orientadores WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $usuario_email);
    $stmt->execute();
    $result_orientador = $stmt->get_result();
    if ($result_orientador && $result_orientador->num_rows > 0) {
        $orientador = $result_orientador->fetch_assoc();
        $is_owner = ($projeto['id_orientador'] == $orientador['id']);
    }
    $stmt->close();
} else {
    $stmt = $conexao->prepare("SELECT a.id FROM alunos a 
                               INNER JOIN projetos_alunos pa ON a.id = pa.id_aluno 
                               WHERE a.email = ? AND pa.id_projeto = ? LIMIT 1");
    $stmt->bind_param("si", $usuario_email, $id_projeto);
    $stmt->execute();
    $result_aluno = $stmt->get_result();
    $is_owner = ($result_aluno && $result_aluno->num_rows > 0);
    $stmt->close();
}

if (!$is_owner) {
    header('Location: detalhes_projeto.php?id=' . $id_projeto);
    exit;
}


$stmt = $conexao->prepare("SELECT a.id, a.nome, a.matricula, a.email 
                           FROM alunos a 
                           INNER JOIN projetos_alunos pa ON a.id = pa.id_aluno 
                           WHERE pa.id_projeto = ?");
$stmt->bind_param("i", $id_projeto);
$stmt->execute();
$result_alunos_projeto = $stmt->get_result();
$stmt->close();

$stmt = $conexao->prepare("SELECT * FROM documentos WHERE id_projeto = ? ORDER BY data_upload DESC");
$stmt->bind_param("i", $id_projeto);
$stmt->execute();
$result_docs = $stmt->get_result();
$documentos = [];
while ($doc = $result_docs->fetch_assoc()) {
    $documentos[] = $doc;
}
$stmt->close();

$query_alunos = "SELECT id, nome, matricula FROM alunos ORDER BY nome";
$result_alunos = $conexao->query($query_alunos);

$query_orientadores = "SELECT id, nome FROM orientadores ORDER BY nome";
$result_orientadores = $conexao->query($query_orientadores);

$query_areas = "SELECT id, nome FROM areas ORDER BY nome";
$result_areas = $conexao->query($query_areas);

$query_status = "SELECT id, descricao FROM status ORDER BY id";
$result_status = $conexao->query($query_status);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao'])) {
    $acao = $_POST['acao'];
    
    if ($acao === 'atualizar_projeto') {
        $titulo = trim($_POST['titulo']);
        $resumo = trim($_POST['resumo']);
        $id_orientador = $_POST['id_orientador'];
        $id_area = $_POST['id_area'];
        $status = $_POST['id_status'];
        $imagem_nome = $projeto['imagem'];
        
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
                
                if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_destino)) {
                    if (!empty($projeto['imagem']) && file_exists('../uploads/' . $projeto['imagem'])) {
                        unlink('../uploads/' . $projeto['imagem']);
                    }
                } else {
                    $mensagem = 'Erro ao fazer upload da imagem!';
                    $tipo_mensagem = 'erro';
                    $imagem_nome = $projeto['imagem'];
                }
            }
        }
        
        if (empty($titulo) || empty($resumo) || empty($id_orientador) || empty($id_area)) {
            $mensagem = 'Todos os campos são obrigatórios!';
            $tipo_mensagem = 'erro';
        } else if ($tipo_mensagem !== 'erro') {
            $stmt = $conexao->prepare("UPDATE projetos SET titulo = ?, resumo = ?, imagem = ?, id_orientador = ?, id_area = ?, status = ? WHERE id = ?");
            $stmt->bind_param("sssiiii", $titulo, $resumo, $imagem_nome, $id_orientador, $id_area, $status, $id_projeto);
            
            if ($stmt->execute()) {
                $mensagem = 'Projeto atualizado com sucesso!';
                $tipo_mensagem = 'sucesso';
                $projeto['titulo'] = $titulo;
                $projeto['resumo'] = $resumo;
                $projeto['imagem'] = $imagem_nome;
                $projeto['id_orientador'] = $id_orientador;
                $projeto['id_area'] = $id_area;
                $projeto['status'] = $status;
            } else {
                $mensagem = 'Erro ao atualizar projeto!';
                $tipo_mensagem = 'erro';
            }
            $stmt->close();
        }
    } elseif ($acao === 'adicionar_aluno') {
        $id_aluno = intval($_POST['id_aluno']);
        
        $stmt = $conexao->prepare("SELECT * FROM projetos_alunos WHERE id_projeto = ? AND id_aluno = ?");
        $stmt->bind_param("ii", $id_projeto, $id_aluno);
        $stmt->execute();
        $result_check = $stmt->get_result();
        
        if ($result_check->num_rows > 0) {
            $mensagem = 'Este aluno já está vinculado ao projeto!';
            $tipo_mensagem = 'erro';
        } else {
            $stmt = $conexao->prepare("INSERT INTO projetos_alunos (id_projeto, id_aluno) VALUES (?, ?)");
            $stmt->bind_param("ii", $id_projeto, $id_aluno);
            
            if ($stmt->execute()) {
                $mensagem = 'Aluno adicionado ao projeto com sucesso!';
                $tipo_mensagem = 'sucesso';
            } else {
                $mensagem = 'Erro ao adicionar aluno!';
                $tipo_mensagem = 'erro';
            }
        }
        $stmt->close();
        
        $stmt = $conexao->prepare("SELECT a.id, a.nome, a.matricula, a.email 
                                   FROM alunos a 
                                   INNER JOIN projetos_alunos pa ON a.id = pa.id_aluno 
                                   WHERE pa.id_projeto = ?");
        $stmt->bind_param("i", $id_projeto);
        $stmt->execute();
        $result_alunos_projeto = $stmt->get_result();
        $stmt->close();
    } elseif ($acao === 'remover_aluno') {
        $id_aluno = intval($_POST['id_aluno']);
        
        $stmt = $conexao->prepare("DELETE FROM projetos_alunos WHERE id_projeto = ? AND id_aluno = ?");
        $stmt->bind_param("ii", $id_projeto, $id_aluno);
        
        if ($stmt->execute()) {
            $mensagem = 'Aluno removido do projeto com sucesso!';
            $tipo_mensagem = 'sucesso';
        } else {
            $mensagem = 'Erro ao remover aluno!';
            $tipo_mensagem = 'erro';
        }
        $stmt->close();
        
        $stmt = $conexao->prepare("SELECT a.id, a.nome, a.matricula, a.email 
                                   FROM alunos a 
                                   INNER JOIN projetos_alunos pa ON a.id = pa.id_aluno 
                                   WHERE pa.id_projeto = ?");
        $stmt->bind_param("i", $id_projeto);
        $stmt->execute();
        $result_alunos_projeto = $stmt->get_result();
        $stmt->close();
    } elseif ($acao === 'adicionar_documento') {
        if (isset($_FILES['documento']) && $_FILES['documento']['error'] === UPLOAD_ERR_OK) {
            $extensoes_permitidas = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'txt'];
            $nome_original = $_FILES['documento']['name'];
            $extensao = strtolower(pathinfo($nome_original, PATHINFO_EXTENSION));
            $tamanho = $_FILES['documento']['size'];
            $max_size = 10 * 1024 * 1024; // 10MB
            
            if (!in_array($extensao, $extensoes_permitidas)) {
                $mensagem = 'Tipo de arquivo não permitido! Use: PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, TXT';
                $tipo_mensagem = 'erro';
            } elseif ($tamanho > $max_size) {
                $mensagem = 'Arquivo muito grande! Tamanho máximo: 10MB';
                $tipo_mensagem = 'erro';
            } else {
                $nome_arquivo = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $nome_original);
                $caminho_destino = '../uploads/documentos/' . $nome_arquivo;
                
                if (!file_exists('../uploads/documentos')) {
                    mkdir('../uploads/documentos', 0777, true);
                }
                
                if (move_uploaded_file($_FILES['documento']['tmp_name'], $caminho_destino)) {
                    $descricao = trim($_POST['descricao_documento'] ?? '');
                    
                    $stmt = $conexao->prepare("INSERT INTO documentos (id_projeto, nome_original, nome_arquivo, tipo_arquivo, tamanho, descricao) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("isssis", $id_projeto, $nome_original, $nome_arquivo, $extensao, $tamanho, $descricao);
                    
                    if ($stmt->execute()) {
                        $mensagem = 'Documento adicionado com sucesso!';
                        $tipo_mensagem = 'sucesso';
                        
                        // Refresh documents list
                        $stmt = $conexao->prepare("SELECT * FROM documentos WHERE id_projeto = ? ORDER BY data_upload DESC");
                        $stmt->bind_param("i", $id_projeto);
                        $stmt->execute();
                        $result_docs = $stmt->get_result();
                        $documentos = [];
                        while ($doc = $result_docs->fetch_assoc()) {
                            $documentos[] = $doc;
                        }
                        $stmt->close();
                    } else {
                        $mensagem = 'Erro ao salvar documento no banco de dados!';
                        $tipo_mensagem = 'erro';
                        unlink($caminho_destino);
                    }
                    $stmt->close();
                } else {
                    $mensagem = 'Erro ao fazer upload do documento!';
                    $tipo_mensagem = 'erro';
                }
            }
        } else {
            $mensagem = 'Nenhum documento foi enviado!';
            $tipo_mensagem = 'erro';
        }
    } elseif ($acao === 'excluir_documento') {
        $id_documento = intval($_POST['id_documento']);
        
        $stmt = $conexao->prepare("SELECT * FROM documentos WHERE id = ? AND id_projeto = ?");
        $stmt->bind_param("ii", $id_documento, $id_projeto);
        $stmt->execute();
        $result_doc = $stmt->get_result();
        
        if ($result_doc && $result_doc->num_rows > 0) {
            $documento = $result_doc->fetch_assoc();
            
            // Delete file from filesystem
            if (file_exists('../uploads/documentos/' . $documento['nome_arquivo'])) {
                unlink('../uploads/documentos/' . $documento['nome_arquivo']);
            }
            
            // Delete from database
            $stmt = $conexao->prepare("DELETE FROM documentos WHERE id = ?");
            $stmt->bind_param("i", $id_documento);
            
            if ($stmt->execute()) {
                $mensagem = 'Documento excluído com sucesso!';
                $tipo_mensagem = 'sucesso';
                
                // Refresh documents list
                $stmt = $conexao->prepare("SELECT * FROM documentos WHERE id_projeto = ? ORDER BY data_upload DESC");
                $stmt->bind_param("i", $id_projeto);
                $stmt->execute();
                $result_docs = $stmt->get_result();
                $documentos = [];
                while ($doc = $result_docs->fetch_assoc()) {
                    $documentos[] = $doc;
                }
                $stmt->close();
            } else {
                $mensagem = 'Erro ao excluir documento!';
                $tipo_mensagem = 'erro';
            }
        } else {
            $mensagem = 'Documento não encontrado!';
            $tipo_mensagem = 'erro';
        }
        $stmt->close();
    } elseif ($acao === 'excluir_projeto') {
        
        // Delete project image
        if (!empty($projeto['imagem']) && file_exists('../uploads/' . $projeto['imagem'])) {
            unlink('../uploads/' . $projeto['imagem']);
        }
        
        // Delete all documents
        $stmt = $conexao->prepare("SELECT nome_arquivo FROM documentos WHERE id_projeto = ?");
        $stmt->bind_param("i", $id_projeto);
        $stmt->execute();
        $result_docs = $stmt->get_result();
        
        while ($doc = $result_docs->fetch_assoc()) {
            if (file_exists('../uploads/documentos/' . $doc['nome_arquivo'])) {
                unlink('../uploads/documentos/' . $doc['nome_arquivo']);
            }
        }
        $stmt->close();
        
        // Delete project from database (CASCADE will delete related records)
        $stmt = $conexao->prepare("DELETE FROM projetos WHERE id = ?");
        $stmt->bind_param("i", $id_projeto);
        
        if ($stmt->execute()) {
            $stmt->close();
            $conexao->close();
            header('Location: projetos.php?msg=excluido');
            exit;
        } else {
            $mensagem = 'Erro ao excluir projeto!';
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
    <title>Editar Projeto - <?php echo htmlspecialchars($projeto['titulo']); ?></title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        .projeto-detalhes {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        
        .projeto-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e0e0e0;
        }
        
        .projeto-imagem-preview {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .alunos-section, .documentos-section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        
        .alunos-lista, .documentos-lista {
            list-style: none;
            margin-top: 20px;
        }
        
        .aluno-item, .documento-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        
        .aluno-info, .documento-info {
            flex: 1;
        }
        
        .btn-remover, .btn-excluir {
            padding: 8px 16px;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        
        .btn-remover:hover, .btn-excluir:hover {
            background-color: #c0392b;
        }
        
        .adicionar-aluno-form, .adicionar-documento-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 20px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        
        .adicionar-aluno-form select, .adicionar-documento-form input, .adicionar-documento-form textarea {
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            font-size: 1rem;
        }
        
        .btn-adicionar {
            padding: 12px 25px;
            background-color: #27ae60;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            align-self: flex-start;
        }
        
        .btn-adicionar:hover {
            background-color: #229954;
        }
        
        .file-icon {
            display: inline-block;
            padding: 5px 10px;
            background-color: #3498db;
            color: white;
            border-radius: 3px;
            font-size: 0.8rem;
            font-weight: bold;
            margin-right: 10px;
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
                <li><a href="../php/logout.php">Sair</a></li>
            </ul>
        </div>
    </aside>

    <main>
        <div class="container">
            <h1 class="titulo">Editar Projeto</h1>
            
            <?php if ($mensagem): ?>
                <div class="mensagem <?php echo $tipo_mensagem; ?>">
                    <?php echo htmlspecialchars($mensagem); ?>
                </div>
            <?php endif; ?>
            
            <div class="projeto-detalhes">
                <div class="projeto-header">
                    <h2><?php echo htmlspecialchars($projeto['titulo']); ?></h2>
                    <form method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este projeto? Todos os arquivos e documentos serão removidos permanentemente!');" style="display: inline;">
                        <input type="hidden" name="acao" value="excluir_projeto">
                        <button type="submit" class="btn-excluir">Excluir Projeto</button>
                    </form>
                </div>
                
                <?php if (!empty($projeto['imagem']) && file_exists('../uploads/' . $projeto['imagem'])): ?>
                    <img src="../uploads/<?php echo htmlspecialchars($projeto['imagem']); ?>" 
                         alt="<?php echo htmlspecialchars($projeto['titulo']); ?>" 
                         class="projeto-imagem-preview">
                <?php endif; ?>
                
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="acao" value="atualizar_projeto">
                    
                    <div class="form-group">
                        <label for="titulo">Título do Projeto</label>
                        <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($projeto['titulo']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="resumo">Resumo</label>
                        <textarea id="resumo" name="resumo" rows="5" required><?php echo htmlspecialchars($projeto['resumo']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="imagem">Alterar Imagem do Projeto</label>
                        <input type="file" id="imagem" name="imagem" accept="image/*">
                        <small>Formatos aceitos: JPG, JPEG, PNG, GIF. Deixe em branco para manter a imagem atual.</small>
                    </div>

                    <div class="form-group">
                        <label for="id_orientador">Orientador</label>
                        <select id="id_orientador" name="id_orientador" required>
                            <?php 
                            $result_orientadores->data_seek(0);
                            while ($orientador = $result_orientadores->fetch_assoc()): 
                            ?>
                                <option value="<?php echo $orientador['id']; ?>" 
                                        <?php echo ($projeto['id_orientador'] == $orientador['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($orientador['nome']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="id_area">Área</label>
                        <select id="id_area" name="id_area" required>
                            <?php 
                            $result_areas->data_seek(0);
                            while ($area = $result_areas->fetch_assoc()): 
                            ?>
                                <option value="<?php echo $area['id']; ?>" 
                                        <?php echo ($projeto['id_area'] == $area['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($area['nome']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="id_status">Status</label>
                        <select id="id_status" name="id_status" required>
                            <?php 
                            $result_status->data_seek(0);
                            while ($status = $result_status->fetch_assoc()): 
                            ?>
                                <option value="<?php echo $status['id']; ?>" 
                                        <?php echo ($projeto['status'] == $status['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($status['descricao']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn-primary">Atualizar Projeto</button>
                </form>
            </div>
            
            <div class="alunos-section">
                <h2>Alunos Vinculados ao Projeto</h2>
                
                <?php if ($result_alunos_projeto->num_rows > 0): ?>
                    <ul class="alunos-lista">
                        <?php 
                        $result_alunos_projeto->data_seek(0);
                        while ($aluno = $result_alunos_projeto->fetch_assoc()): 
                        ?>
                            <li class="aluno-item">
                                <div class="aluno-info">
                                    <strong><?php echo htmlspecialchars($aluno['nome']); ?></strong><br>
                                    <small>Matrícula: <?php echo htmlspecialchars($aluno['matricula']); ?> | Email: <?php echo htmlspecialchars($aluno['email']); ?></small>
                                </div>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="acao" value="remover_aluno">
                                    <input type="hidden" name="id_aluno" value="<?php echo $aluno['id']; ?>">
                                    <button type="submit" class="btn-remover" onclick="return confirm('Remover este aluno do projeto?');">Remover</button>
                                </form>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p style="color: #7f8c8d; margin-top: 20px;">Nenhum aluno vinculado a este projeto.</p>
                <?php endif; ?>
                
                <form method="POST" class="adicionar-aluno-form">
                    <input type="hidden" name="acao" value="adicionar_aluno">
                    <select name="id_aluno" required>
                        <option value="">Selecione um aluno para adicionar</option>
                        <?php 
                        $result_alunos->data_seek(0);
                        while ($aluno = $result_alunos->fetch_assoc()): 
                        ?>
                            <option value="<?php echo $aluno['id']; ?>">
                                <?php echo htmlspecialchars($aluno['nome']); ?> (<?php echo htmlspecialchars($aluno['matricula']); ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <button type="submit" class="btn-adicionar">Adicionar Aluno</button>
                </form>
            </div>
            
            <!-- Documents section with upload and delete functionality -->
            <div class="documentos-section">
                <h2>Documentos Acadêmicos</h2>
                
                <?php if (count($documentos) > 0): ?>
                    <ul class="documentos-lista">
                        <?php foreach ($documentos as $doc): ?>
                            <li class="documento-item">
                                <div class="documento-info">
                                    <span class="file-icon"><?php echo strtoupper($doc['tipo_arquivo']); ?></span>
                                    <strong><?php echo htmlspecialchars($doc['nome_original']); ?></strong>
                                    <?php if (!empty($doc['descricao'])): ?>
                                        <br><small><?php echo htmlspecialchars($doc['descricao']); ?></small>
                                    <?php endif; ?>
                                    <br><small>Enviado em: <?php echo date('d/m/Y H:i', strtotime($doc['data_upload'])); ?> | Tamanho: <?php echo number_format($doc['tamanho'] / 1024, 2); ?> KB</small>
                                </div>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="acao" value="excluir_documento">
                                    <input type="hidden" name="id_documento" value="<?php echo $doc['id']; ?>">
                                    <button type="submit" class="btn-remover" onclick="return confirm('Excluir este documento?');">Excluir</button>
                                </form>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p style="color: #7f8c8d; margin-top: 20px;">Nenhum documento adicionado a este projeto.</p>
                <?php endif; ?>
                
                <form method="POST" enctype="multipart/form-data" class="adicionar-documento-form">
                    <input type="hidden" name="acao" value="adicionar_documento">
                    <label for="documento">Adicionar Documento</label>
                    <input type="file" id="documento" name="documento" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.txt" required>
                    <small>Formatos aceitos: PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, TXT. Tamanho máximo: 10MB</small>
                    <label for="descricao_documento">Descrição (opcional)</label>
                    <textarea id="descricao_documento" name="descricao_documento" rows="2" placeholder="Breve descrição do documento..."></textarea>
                    <button type="submit" class="btn-adicionar">Adicionar Documento</button>
                </form>
            </div>
        </div>
    </main>

    <script src="../js/projeto.js"></script>
</body>
</html>
