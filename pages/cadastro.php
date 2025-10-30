<?php
session_start();
require_once '../config/database.php';

$mensagem = '';
$tipo_mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $tipo = $_POST['tipo']; // 'aluno' ou 'orientador'
    $matricula = isset($_POST['matricula']) ? trim($_POST['matricula']) : null;
    
    // Validações básicas
    if (empty($nome) || empty($email) || empty($senha) || empty($tipo)) {
        $mensagem = 'Por favor, preencha todos os campos obrigatórios.';
        $tipo_mensagem = 'erro';
    } elseif ($tipo === 'aluno' && empty($matricula)) {
        $mensagem = 'Matrícula é obrigatória para alunos.';
        $tipo_mensagem = 'erro';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem = 'Email inválido.';
        $tipo_mensagem = 'erro';
    } else {
        $conn = getConnection();
        $conn->begin_transaction();
        
        try {
            // Hash da senha
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            
            if ($tipo === 'aluno') {
                // Inserir aluno
                $stmt = $conn->prepare("INSERT INTO alunos (nome, matricula, email) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $nome, $matricula, $email);
                $stmt->execute();
                $stmt->close();
            } else {
                // Inserir orientador
                $stmt = $conn->prepare("INSERT INTO orientadores (nome, email) VALUES (?, ?)");
                $stmt->bind_param("ss", $nome, $email);
                $stmt->execute();
                $stmt->close();
            }
            
            // Inserir usuário
            $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nome, $email, $senha_hash, $tipo);
            $stmt->execute();
            $stmt->close();
            
            $conn->commit();
            $mensagem = 'Cadastro realizado com sucesso!';
            $tipo_mensagem = 'sucesso';
            
        } catch(Exception $e) {
            $conn->rollback();
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $mensagem = 'Email ou matrícula já cadastrados.';
            } else {
                $mensagem = 'Erro ao cadastrar: ' . $e->getMessage();
            }
            $tipo_mensagem = 'erro';
        }
        
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Sistema de Projetos</title>
    <link rel="stylesheet" href="../css/forms.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Raleway:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="header">
                <h1>Cadastro</h1>
                <p>Sistema de Gerenciamento de Projetos Acadêmicos</p>
            </div>
            
            <?php if ($mensagem): ?>
                <div class="mensagem <?php echo $tipo_mensagem; ?>">
                    <?php echo htmlspecialchars($mensagem); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" id="formCadastro">
                <div class="form-group">
                    <label for="tipo">Tipo de Cadastro</label>
                    <select name="tipo" id="tipo" required onchange="toggleMatricula()">
                        <option value="">Selecione...</option>
                        <option value="aluno">Aluno</option>
                        <option value="orientador">Orientador</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="nome">Nome Completo</label>
                    <input type="text" id="nome" name="nome" required placeholder="Digite seu nome completo">
                </div>
                
                <div class="form-group" id="matriculaGroup" style="display: none;">
                    <label for="matricula">Matrícula</label>
                    <input type="text" id="matricula" name="matricula" placeholder="Digite sua matrícula">
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required placeholder="seu@email.com">
                </div>
                
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" required placeholder="Mínimo 6 caracteres" minlength="6">
                </div>
                
                <button type="submit" class="btn-primary">Cadastrar</button>
                
                <div class="footer-link">
                    Já tem uma conta? <a href="login.php">Fazer login</a>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function toggleMatricula() {
            const tipo = document.getElementById('tipo').value;
            const matriculaGroup = document.getElementById('matriculaGroup');
            const matriculaInput = document.getElementById('matricula');
            
            if (tipo === 'aluno') {
                matriculaGroup.style.display = 'block';
                matriculaInput.required = true;
            } else {
                matriculaGroup.style.display = 'none';
                matriculaInput.required = false;
                matriculaInput.value = '';
            }
        }
    </script>
</body>
</html>
