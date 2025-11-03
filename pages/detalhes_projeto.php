<?php
session_start();
require_once '../config/database.php';
require_once '../config/security.php';

$conexao = getConnection();

// Allow viewing if logged in OR if viewing public projects
$is_logged_in = isset($_SESSION['usuario_id']);
$usuario_id = $is_logged_in ? $_SESSION['usuario_id'] : null;
$usuario_email = $is_logged_in ? $_SESSION['usuario_email'] : null;
$usuario_tipo = $is_logged_in ? $_SESSION['usuario_tipo'] : null;
$nome = $is_logged_in ? $_SESSION['usuario_nome'] : 'Visitante';

$id_projeto = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_projeto === 0) {
    header('Location: ' . ($is_logged_in ? 'projetos.php' : '../index.php'));
    exit;
}

$stmt = $conexao->prepare("SELECT p.*, o.nome as orientador_nome, o.email as orientador_email, 
                           a.nome as area_nome, s.descricao as status_descricao 
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
    header('Location: ' . ($is_logged_in ? 'projetos.php' : '../index.php'));
    exit;
}

$is_owner = false;
if ($is_logged_in) {
    if ($usuario_tipo === 'orientador') {
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
}

$stmt = $conexao->prepare("SELECT a.id, a.nome, a.matricula, a.email 
                           FROM alunos a 
                           INNER JOIN projetos_alunos pa ON a.id = pa.id_aluno 
                           WHERE pa.id_projeto = ?");
$stmt->bind_param("i", $id_projeto);
$stmt->execute();
$result_alunos = $stmt->get_result();
$alunos = [];
while ($aluno = $result_alunos->fetch_assoc()) {
    $alunos[] = $aluno;
}
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

$conexao->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($projeto['titulo']); ?> - Detalhes</title>
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
        
        .projeto-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        
        .info-item {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        
        .info-item strong {
            display: block;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .alunos-section, .documentos-section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        
        .alunos-lista {
            list-style: none;
            margin-top: 20px;
        }
        
        .aluno-item {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        
        .documentos-lista {
            list-style: none;
            margin-top: 20px;
        }
        
        .documento-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        
        .documento-info {
            flex: 1;
        }
        
        .documento-actions {
            display: flex;
            gap: 10px;
        }
        
        .btn-preview, .btn-download {
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
            color: white;
            font-size: 0.9rem;
        }
        
        .btn-preview {
            background-color: #3498db;
        }
        
        .btn-preview:hover {
            background-color: #2980b9;
        }
        
        .btn-download {
            background-color: #27ae60;
        }
        
        .btn-download:hover {
            background-color: #229954;
        }
        
        .btn-editar {
            padding: 12px 25px;
            background-color: #f39c12;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-editar:hover {
            background-color: #e67e22;
        }
        
        /* Modal styles for document preview */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
        }
        
        .modal-content {
            position: relative;
            background-color: white;
            margin: 2% auto;
            padding: 0;
            width: 90%;
            height: 90%;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .modal-header {
            padding: 20px;
            background-color: #2c3e50;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-close {
            color: white;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            background: none;
            border: none;
        }
        
        .modal-close:hover {
            color: #e74c3c;
        }
        
        .modal-body {
            height: calc(100% - 70px);
            padding: 0;
        }
        
        .modal-body iframe {
            width: 100%;
            height: 100%;
            border: none;
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
    <?php if ($is_logged_in): ?>
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
    <?php endif; ?>

    <main>
        <div class="container">
            <div class="projeto-detalhes">
                <div class="projeto-header">
                    <h1><?php echo htmlspecialchars($projeto['titulo']); ?></h1>
                    <?php if ($is_owner): ?>
                        <a href="editar_projeto.php?id=<?php echo $projeto['id']; ?>" class="btn-editar">Editar Projeto</a>
                    <?php endif; ?>
                </div>
                
                <?php if (!empty($projeto['imagem']) && file_exists('../uploads/' . $projeto['imagem'])): ?>
                    <img src="../uploads/<?php echo htmlspecialchars($projeto['imagem']); ?>" 
                         alt="<?php echo htmlspecialchars($projeto['titulo']); ?>" 
                         class="projeto-imagem-preview">
                <?php endif; ?>
                
                <div class="projeto-info-grid">
                    <div class="info-item">
                        <strong>Área</strong>
                        <?php echo htmlspecialchars($projeto['area_nome']); ?>
                    </div>
                    <div class="info-item">
                        <strong>Status</strong>
                        <?php echo htmlspecialchars($projeto['status_descricao']); ?>
                    </div>
                    <div class="info-item">
                        <strong>Orientador</strong>
                        <?php echo htmlspecialchars($projeto['orientador_nome']); ?>
                    </div>
                    <div class="info-item">
                        <strong>Data de Cadastro</strong>
                        <?php echo date('d/m/Y', strtotime($projeto['data_cadastro'])); ?>
                    </div>
                </div>
                
                <div style="margin-top: 30px;">
                    <h3>Resumo do Projeto</h3>
                    <p style="line-height: 1.6; color: #555; margin-top: 10px;">
                        <?php echo nl2br(htmlspecialchars($projeto['resumo'])); ?>
                    </p>
                </div>
            </div>
            
            <?php if (count($alunos) > 0): ?>
            <div class="alunos-section">
                <h2>Alunos Participantes</h2>
                <ul class="alunos-lista">
                    <?php foreach ($alunos as $aluno): ?>
                        <li class="aluno-item">
                            <strong><?php echo htmlspecialchars($aluno['nome']); ?></strong><br>
                            <small>Matrícula: <?php echo htmlspecialchars($aluno['matricula']); ?> | Email: <?php echo htmlspecialchars($aluno['email']); ?></small>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            
            <?php if (count($documentos) > 0): ?>
            <div class="documentos-section">
                <h2>Documentos Acadêmicos</h2>
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
                            <div class="documento-actions">
                                <?php if (in_array(strtolower($doc['tipo_arquivo']), ['pdf'])): ?>
                                    <button class="btn-preview" onclick="previewDocument('<?php echo htmlspecialchars($doc['nome_arquivo']); ?>', '<?php echo htmlspecialchars($doc['nome_original']); ?>')">
                                        Visualizar
                                    </button>
                                <?php endif; ?>
                                <a href="../uploads/documentos/<?php echo htmlspecialchars($doc['nome_arquivo']); ?>" 
                                   download="<?php echo htmlspecialchars($doc['nome_original']); ?>" 
                                   class="btn-download">
                                    Download
                                </a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            
            <div style="margin-top: 30px;">
                <a href="<?php echo $is_logged_in ? 'projetos.php' : '../index.php'; ?>" class="btn-primary">Voltar</a>
            </div>
        </div>
    </main>

    <!-- Modal for document preview -->
    <div id="documentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Visualizar Documento</h2>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <iframe id="documentFrame" src="/placeholder.svg"></iframe>
            </div>
        </div>
    </div>

    <script>
        function previewDocument(filename, originalName) {
            const modal = document.getElementById('documentModal');
            const frame = document.getElementById('documentFrame');
            const title = document.getElementById('modalTitle');
            
            frame.src = '../uploads/documentos/' + filename;
            title.textContent = originalName;
            modal.style.display = 'block';
        }
        
        function closeModal() {
            const modal = document.getElementById('documentModal');
            const frame = document.getElementById('documentFrame');
            
            modal.style.display = 'none';
            frame.src = '';
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('documentModal');
            if (event.target == modal) {
                closeModal();
            }
        }
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });
    </script>
    
    <script src="../js/projeto.js"></script>
</body>
</html>
