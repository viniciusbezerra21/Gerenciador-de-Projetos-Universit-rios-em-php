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
        $remember_me = isset($_POST['remember_me']) && $_POST['remember_me'] === '1';

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

                    if ($remember_me) {
                        createRememberMeCookie($usuario['id']);
                    }

                    $stmt->close();
                    $conn->close();
                    
                    header('Location: projetos.php');
                    exit;
                } else {
                    $mensagem = 'Email ou senha incorretos.';
                    $tipo_mensagem = 'erro';
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
    <style>
        .password-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-input-wrapper input[type="password"],
        .password-input-wrapper input[type="text"] {
            width: 100%;
            padding-right: 45px;
        }

        .toggle-password-btn {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            color: #94a3b8;
        }

        .toggle-password-btn:hover {
            color: #59a4eb;
            transform: scale(1.1);
        }

        .toggle-password-btn:active {
            transform: scale(0.95);
        }

        .eye-icon {
            width: 20px;
            height: 20px;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
    </style>
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
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required placeholder="seu@email.com" autocomplete="email">
                </div>

                <div class="form-group">
                    <label for="senha">Senha</label>
                    <!-- added password input wrapper with toggle button -->
                    <div class="password-input-wrapper">
                        <input type="password" id="senha" name="senha" required placeholder="Digite sua senha" autocomplete="current-password">
                        <button type="button" class="toggle-password-btn" onclick="togglePasswordVisibility('senha')">
                            <svg class="eye-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 12S5.4 6 12 6S23 12 23 12S18.6 18 12 18S1 12 1 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Added remember me checkbox -->
                <div class="form-group" style="display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" id="remember_me" name="remember_me" value="1" style="width: auto; margin: 0;">
                    <label for="remember_me" style="margin: 0; font-weight: normal;">Lembrar-me por 30 dias</label>
                </div>

                <button type="submit" class="btn-primary">Entrar</button>

                <div class="footer-link">
                    Não tem uma conta? <a href="cadastro.php">Cadastre-se</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePasswordVisibility(inputId) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
            } else {
                input.type = 'password';
            }
        }
    </script>
</body>
</html>
