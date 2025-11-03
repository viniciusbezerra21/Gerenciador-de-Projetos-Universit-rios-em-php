<?php
require_once '../config/security.php';
require_once '../config/database.php';

$mensagem = '';
$tipo_mensagem = '';

if (isset($_SESSION['usuario_id'])) {
    header('Location: projetos.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
        $mensagem = 'Erro de segurança. Tente novamente.';
        $tipo_mensagem = 'erro';
    } else {
        $email = sanitizeInput($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';

        if (empty($email) || empty($senha)) {
            $mensagem = 'Por favor, preencha todos os campos.';
            $tipo_mensagem = 'erro';
        } elseif (!validateEmail($email)) {
            $mensagem = 'Email inválido.';
            $tipo_mensagem = 'erro';
        } else {
            $conn = getConnection();
            
            $stmt = $conn->prepare("SELECT id, nome, email, tipo, senha FROM usuarios WHERE email = ?");
            if (!$stmt) {
                $mensagem = 'Erro no servidor. Tente novamente.';
                $tipo_mensagem = 'erro';
            } else {
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();
                $usuario = $result->fetch_assoc();

                if ($usuario && password_verify($senha, $usuario['senha'])) {
                    regenerateSessionId();
                    
                    $_SESSION['usuario_id'] = $usuario['id'];
                    $_SESSION['usuario_nome'] = $usuario['nome'];
                    $_SESSION['usuario_tipo'] = $usuario['tipo'];
                    $_SESSION['usuario_email'] = $usuario['email'];
                    $_SESSION['login_time'] = time();

                    $stmt->close();
                    $conn->close();
                    
                    header('Location: projetos.php');
                    exit;
                } else {
                    $mensagem = 'Email ou senha incorretos.';
                    $tipo_mensagem = 'erro';
                    // Log attempt (opcional para auditoria)
                }
                
                $stmt->close();
            }
            
            $conn->close();
        }
    }
}

$csrf_token = generateCSRFToken();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Projetos</title>
    <link rel="stylesheet" href="../css/forms.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Raleway:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="header">
                <h1>Login</h1>
                <p>Sistema de Gerenciamento de Projetos Acadêmicos</p>
            </div>

            <?php if ($mensagem): ?>
                <div class="mensagem <?php echo htmlspecialchars($tipo_mensagem); ?>">
                    <?php echo htmlspecialchars($mensagem); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['expired'])): ?>
                <div class="mensagem aviso">
                    Sua sessão expirou. Por favor, faça login novamente.
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <!-- Adicionar token CSRF -->
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required placeholder="seu@email.com" autocomplete="email">
                </div>

                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" required placeholder="Digite sua senha" autocomplete="current-password">
                </div>

                <button type="submit" class="btn-primary">Entrar</button>

                <div class="footer-link">
                    Não tem uma conta? <a href="cadastro.php">Cadastre-se</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
