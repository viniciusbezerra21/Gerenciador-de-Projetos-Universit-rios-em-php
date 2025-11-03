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
        $nome = sanitizeInput($_POST['nome'] ?? '');
        $email = sanitizeInput($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';
        $tipo = sanitizeInput($_POST['tipo'] ?? 'aluno');
        
        // Validate tipo is either 'aluno' or 'orientador'
        if (!in_array($tipo, ['aluno', 'orientador'])) {
            $tipo = 'aluno';
        }

        if (empty($nome) || empty($email) || empty($senha)) {
            $mensagem = 'Por favor, preencha todos os campos.';
            $tipo_mensagem = 'erro';
        } elseif (!validateEmail($email)) {
            $mensagem = 'Email inválido.';
            $tipo_mensagem = 'erro';
        } elseif (strlen($senha) < 8) {
            $mensagem = 'A senha deve ter no mínimo 8 caracteres.';
            $tipo_mensagem = 'erro';
        } else {
            $conn = getConnection();
            
            // Verificar duplicidade em usuarios
            $stmt_check = $conn->prepare("SELECT id FROM usuarios WHERE email = ? OR nome = ?");
            if (!$stmt_check) {
                $mensagem = 'Erro no servidor. Tente novamente.';
                $tipo_mensagem = 'erro';
            } else {
                $stmt_check->bind_param("ss", $email, $nome);
                $stmt_check->execute();
                $result_check = $stmt_check->get_result();
                
                if ($result_check->num_rows > 0) {
                    // Verificar qual campo é duplicado
                    $stmt_check_email = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
                    $stmt_check_email->bind_param("s", $email);
                    $stmt_check_email->execute();
                    
                    if ($stmt_check_email->get_result()->num_rows > 0) {
                        $mensagem = 'Este email já está cadastrado!';
                    } else {
                        $mensagem = 'Este nome de usuário já está em uso!';
                    }
                    $tipo_mensagem = 'erro';
                    $stmt_check_email->close();
                } else {
                    // Hash da senha
                    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                    
                    // Iniciar transação para garantir integridade
                    $conn->begin_transaction();
                    
                    try {
                        // 1. INSERT na tabela usuarios
                        $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
                        if (!$stmt) {
                            throw new Exception('Erro ao preparar statement de usuários');
                        }
                        
                        $stmt->bind_param("ssss", $nome, $email, $senha_hash, $tipo);
                        
                        if (!$stmt->execute()) {
                            throw new Exception('Erro ao inserir usuário');
                        }
                        
                        $usuario_id = $conn->insert_id;
                        $stmt->close();
                        
                        // 2. INSERT na tabela correspondente (alunos ou orientadores)
                        if ($tipo === 'aluno') {
                            // Gerar matrícula automática: ANO + ID com 4 dígitos
                            $matricula = date('Y') . str_pad($usuario_id, 4, '0', STR_PAD_LEFT);
                            
                            $stmt_aluno = $conn->prepare("INSERT INTO alunos (nome, email, matricula) VALUES (?, ?, ?)");
                            if (!$stmt_aluno) {
                                throw new Exception('Erro ao preparar statement de alunos');
                            }
                            
                            $stmt_aluno->bind_param("sss", $nome, $email, $matricula);
                            
                            if (!$stmt_aluno->execute()) {
                                throw new Exception('Erro ao inserir aluno: ' . $stmt_aluno->error);
                            }
                            
                            error_log("✓ Aluno cadastrado: $nome (Matrícula: $matricula, Email: $email)");
                            $stmt_aluno->close();
                            
                        } else if ($tipo === 'orientador') {
                            $stmt_orientador = $conn->prepare("INSERT INTO orientadores (nome, email) VALUES (?, ?)");
                            if (!$stmt_orientador) {
                                throw new Exception('Erro ao preparar statement de orientadores');
                            }
                            
                            $stmt_orientador->bind_param("ss", $nome, $email);
                            
                            if (!$stmt_orientador->execute()) {
                                throw new Exception('Erro ao inserir orientador: ' . $stmt_orientador->error);
                            }
                            
                            error_log("✓ Orientador cadastrado: $nome (Email: $email)");
                            $stmt_orientador->close();
                        }
                        
                        // Commit da transação
                        $conn->commit();
                        
                        $conn->close();
                        header('Location: login.php?cadastro=sucesso');
                        exit;
                        
                    } catch (Exception $e) {
                        // Rollback em caso de erro
                        $conn->rollback();
                        error_log("Erro no cadastro: " . $e->getMessage());
                        $mensagem = 'Erro ao cadastrar usuário. Tente novamente.';
                        $tipo_mensagem = 'erro';
                    }
                }
                $stmt_check->close();
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
    <title>Cadastro - Sistema de Projetos</title>
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

        .form-group.user-type-group {
            margin-bottom: 24px;
        }

        .radio-group {
            display: flex;
            gap: 20px;
            margin-top: 12px;
        }

        .radio-option {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            flex: 1;
        }

        .radio-option input[type="radio"] {
            cursor: pointer;
            accent-color: #059669;
        }

        .radio-option label {
            cursor: pointer;
            margin: 0;
            font-weight: 500;
            flex: 1;
        }

        .radio-option input[type="radio"]:checked + label {
            color: #059669;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="header">
                <h1>Cadastro</h1>
                <p>Sistema de Gerenciamento de Projetos Acadêmicos</p>
            </div>

            <?php if ($mensagem): ?>
                <div class="mensagem <?php echo htmlspecialchars($tipo_mensagem); ?>">
                    <?php echo htmlspecialchars($mensagem); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                
                <div class="form-group">
                    <label for="nome">Nome Completo</label>
                    <input type="text" id="nome" name="nome" required placeholder="Digite seu nome completo" autocomplete="name">
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required placeholder="seu@email.com" autocomplete="email">
                </div>

                <div class="form-group user-type-group">
                    <label>Tipo de Cadastro</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" id="tipo-aluno" name="tipo" value="aluno" checked required>
                            <label for="tipo-aluno">Aluno</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="tipo-orientador" name="tipo" value="orientador" required>
                            <label for="tipo-orientador">Orientador</label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="senha">Senha</label>
                    <div class="password-input-wrapper">
                        <input type="password" id="senha" name="senha" required placeholder="Digite uma senha forte" autocomplete="new-password">
                        <button type="button" class="toggle-password-btn" onclick="togglePasswordVisibility('senha')">
                            <svg class="eye-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 12S5.4 6 12 6S23 12 23 12S18.6 18 12 18S1 12 1 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>
                    <small style="color: #64748b; margin-top: 4px; display: block;">Mínimo de 8 caracteres</small>
                </div>

                <button type="submit" class="btn-primary">Cadastrar</button>

                <div class="footer-link">
                    Já tem uma conta? <a href="login.php">Fazer login</a>
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