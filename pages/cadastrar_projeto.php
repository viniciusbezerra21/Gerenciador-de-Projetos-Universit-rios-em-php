<?php
session_start();
require_once '../config/database.php';
require_once '../config/security.php';

requireLogin();

$conexao = getConnection();
$nome = sanitizeInput($_SESSION['usuario_nome']);
$tipo = $_SESSION['usuario_tipo'];
$usuario_email = sanitizeInput($_SESSION['usuario_email']);
$mensagem = '';
$tipo_mensagem = '';

// DEBUG: Mostrar informações do usuário
error_log("=== CADASTRO DE PROJETO ===");
error_log("Email do usuário: " . $usuario_email);
error_log("Tipo de usuário: " . $tipo);

$id_orientador_usuario = null;
$orientador_nome_usuario = null;

if ($tipo === 'orientador') {
    $stmt_orientador_id = $conexao->prepare("SELECT id, nome FROM orientadores WHERE email = ? LIMIT 1");
    if ($stmt_orientador_id) {
        $stmt_orientador_id->bind_param("s", $usuario_email);
        $stmt_orientador_id->execute();
        $result = $stmt_orientador_id->get_result();
        if ($result && $result->num_rows > 0) {
            $orientador_data = $result->fetch_assoc();
            $id_orientador_usuario = $orientador_data['id'];
            $orientador_nome_usuario = $orientador_data['nome'];
            error_log("Orientador encontrado: ID=" . $id_orientador_usuario . ", Nome=" . $orientador_nome_usuario);
        }
        $stmt_orientador_id->close();
    }
}

$query_orientadores = "SELECT id, nome FROM orientadores ORDER BY nome";
$result_orientadores = $conexao->query($query_orientadores);

$query_status = "SELECT id, descricao AS nome FROM status ORDER BY id";
$result_status = $conexao->query($query_status);

$query_areas = "SELECT id, nome FROM areas ORDER BY nome";
$result_areas = $conexao->query($query_areas);

$csrf_token = generateCSRFToken();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
        $mensagem = 'Erro de segurança. Tente novamente.';
        $tipo_mensagem = 'erro';
    } else {
        $titulo = sanitizeInput(trim($_POST['titulo']));
        $resumo = sanitizeInput(trim($_POST['resumo']));
        
        if ($tipo === 'orientador') {
            $id_orientador = $id_orientador_usuario;
        } else {
            $id_orientador = intval($_POST['id_orientador']);
        }
        
        $id_area = intval($_POST['id_area']);
        $status = intval($_POST['id_status']);
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
                $projeto_id = $conexao->insert_id;
                error_log("Projeto cadastrado com ID: " . $projeto_id);
                
                // IMPORTANTE: Vincular o aluno ao projeto
                if ($tipo === 'aluno') {
                    error_log("Tipo de usuário é ALUNO, buscando ID do aluno...");
                    
                    $stmt_aluno = $conexao->prepare("SELECT id FROM alunos WHERE email = ? LIMIT 1");
                    $stmt_aluno->bind_param("s", $usuario_email);
                    $stmt_aluno->execute();
                    $result_aluno = $stmt_aluno->get_result();
                    
                    if ($result_aluno && $result_aluno->num_rows > 0) {
                        $aluno = $result_aluno->fetch_assoc();
                        $aluno_id = $aluno['id'];
                        error_log("ID do aluno encontrado: " . $aluno_id);
                        
                        $stmt_link = $conexao->prepare("INSERT INTO projetos_alunos (id_projeto, id_aluno) VALUES (?, ?)");
                        $stmt_link->bind_param("ii", $projeto_id, $aluno_id);
                        
                        if ($stmt_link->execute()) {
                            error_log("✓ Projeto vinculado ao aluno com SUCESSO!");
                            error_log("  Projeto ID: " . $projeto_id . " | Aluno ID: " . $aluno_id);
                        } else {
                            error_log("✗ ERRO ao vincular projeto ao aluno: " . $stmt_link->error);
                        }
                        $stmt_link->close();
                    } else {
                        error_log("✗ ERRO: Aluno não encontrado na tabela 'alunos' com email: " . $usuario_email);
                        error_log("  Verifique se o aluno foi cadastrado corretamente na tabela 'alunos'");
                    }
                    $stmt_aluno->close();
                } else if ($tipo === 'orientador') {
                    error_log("Tipo de usuário é ORIENTADOR - projeto automaticamente vinculado via id_orientador");
                } else {
                    error_log("Tipo de usuário desconhecido: " . $tipo);
                }
                
                // Upload de documentos
                if (isset($_FILES['documentos']) && !empty($_FILES['documentos']['name'][0])) {
                    $documentos_dir = '../uploads/documentos/';
                    if (!file_exists($documentos_dir)) {
                        mkdir($documentos_dir, 0777, true);
                    }
                    
                    $extensoes_doc_permitidas = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'txt'];
                    $max_file_size = 10 * 1024 * 1024; // 10MB
                    
                    $total_files = count($_FILES['documentos']['name']);
                    for ($i = 0; $i < $total_files; $i++) {
                        if ($_FILES['documentos']['error'][$i] === UPLOAD_ERR_OK) {
                            $nome_original = $_FILES['documentos']['name'][$i];
                            $tamanho = $_FILES['documentos']['size'][$i];
                            $extensao = strtolower(pathinfo($nome_original, PATHINFO_EXTENSION));
                            
                            if (in_array($extensao, $extensoes_doc_permitidas) && $tamanho <= $max_file_size) {
                                $nome_arquivo = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $nome_original);
                                $caminho_destino = $documentos_dir . $nome_arquivo;
                                
                                if (move_uploaded_file($_FILES['documentos']['tmp_name'][$i], $caminho_destino)) {
                                    $descricao = isset($_POST['descricao_doc'][$i]) ? sanitizeInput($_POST['descricao_doc'][$i]) : '';
                                    
                                    $stmt_doc = $conexao->prepare("INSERT INTO documentos (id_projeto, nome_original, nome_arquivo, tipo_arquivo, tamanho, descricao) VALUES (?, ?, ?, ?, ?, ?)");
                                    $stmt_doc->bind_param("isssis", $projeto_id, $nome_original, $nome_arquivo, $extensao, $tamanho, $descricao);
                                    $stmt_doc->execute();
                                    $stmt_doc->close();
                                }
                            }
                        }
                    }
                }
                
                $mensagem = 'Projeto cadastrado com sucesso!';
                $tipo_mensagem = 'sucesso';
            } else {
                $mensagem = 'Erro ao cadastrar projeto: ' . $conexao->error;
                $tipo_mensagem = 'erro';
                error_log("ERRO ao inserir projeto: " . $conexao->error);
            }
            $stmt->close();
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
    <title>Cadastrar Projeto</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        .documentos-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .documento-item {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            align-items: flex-start;
        }
        .documento-item input[type="file"] {
            flex: 1;
        }
        .documento-item input[type="text"] {
            flex: 2;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn-add-doc {
            background-color: #27ae60;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
        .btn-add-doc:hover {
            background-color: #229954;
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

     <main class="main-content">
        <div class="content-card">
            <!-- Adicionar mensagem específica para orientadores -->
            <?php if ($tipo === 'orientador'): ?>
                <h2>Cadastrar Novo Projeto como Orientador</h2>
                <p style="color: #3d7bb8; font-weight: 500; margin-bottom: 20px;">
                    Os projetos que você cadastrar serão automaticamente vinculados a você como orientador responsável.
                </p>
            <?php else: ?>
                <h2>Cadastrar Novo Projeto</h2>
            <?php endif; ?>
            
            <?php if ($mensagem): ?>
                <div class="mensagem <?php echo $tipo_mensagem; ?>">
                    <?php echo htmlspecialchars($mensagem); ?>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                
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

                <!-- Mostrar seleção de orientador apenas para alunos -->
                <?php if ($tipo === 'aluno'): ?>
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
                <?php else: ?>
                    <!-- Para orientadores, mostrar informação de quem está orientando -->
                    <div class="form-group">
                        <label>Orientador Responsável</label>
                        <input type="text" value="<?php echo htmlspecialchars($orientador_nome_usuario); ?>" readonly style="background-color: #f5f5f5;">
                        <small>Seus projetos serão automaticamente associados a você como orientador responsável.</small>
                    </div>
                <?php endif; ?>

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
                
                <div class="documentos-section">
                    <h3>Documentos Acadêmicos (Opcional)</h3>
                    <p style="font-size: 0.9rem; color: #666; margin-bottom: 15px;">
                        Adicione arquivos relacionados ao projeto (PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, TXT). Máximo 10MB por arquivo.
                    </p>
                    <div id="documentos-container">
                        <div class="documento-item">
                            <input type="file" name="documentos[]" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.txt">
                            <input type="text" name="descricao_doc[]" placeholder="Descrição do documento (opcional)">
                        </div>
                    </div>
                    <button type="button" class="btn-add-doc" onclick="addDocumentoField()">+ Adicionar Outro Documento</button>
                </div>
                
                <button type="submit" class="btn-primary">Cadastrar Projeto</button>
            </form>
        </div>
    </main>
    
    <script>
        function addDocumentoField() {
            const container = document.getElementById('documentos-container');
            const newField = document.createElement('div');
            newField.className = 'documento-item';
            newField.innerHTML = `
                <input type="file" name="documentos[]" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.txt">
                <input type="text" name="descricao_doc[]" placeholder="Descrição do documento (opcional)">
            `;
            container.appendChild(newField);
        }
    </script>
</body>
</html>
