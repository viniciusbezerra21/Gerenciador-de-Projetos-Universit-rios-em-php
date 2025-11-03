<?php
require_once './config/security.php';

// Check if user is logged in
$isLoggedIn = isset($_SESSION['usuario_id']);
$userName = $isLoggedIn ? $_SESSION['usuario_nome'] : '';

$csrfToken = generateCSRFToken();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Artigos - Plataforma de Projetos de Pesquisa</title>
    <link rel="stylesheet" href="./css/index.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Raleway:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <style>
        .password-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            margin-bottom: 10px; /* Added space around password wrapper */
        }

        .password-input-wrapper input[type="password"],
        .password-input-wrapper input[type="text"] {
            width: 100%;
            padding-right: 45px; /* Adjusted input padding for eye icon */
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
    <!-- Header -->
    <header class="cabecalho">
        <!-- Updated logo to use image path -->
        <div class="logo">
            <img src="./images/logo.png" alt="Logo">
        </div>
        <nav class="menu">
            <ul class="lista-menu">
                <li><a href="#inicio">Início</a></li>
                <li><a href="#sobre">Sobre</a></li>
                <li><a href="#servicos">Serviços</a></li>
                <li><a href="#contato">Contato</a></li>
            </ul>
            <!-- Show different menu based on login status -->
            <?php if ($isLoggedIn): ?>
                <ul class="lista-login">
                    <li class="welcome-message">Bem-vindo, <strong><?php echo htmlspecialchars($userName); ?></strong></li>
                    <li><a href="pages/meu_perfil.php" class="btn-login">Meu Perfil</a></li>
                    <li><a href="php/logout.php" class="btn-cadastrar">Sair</a></li>
                </ul>
            <?php else: ?>
                <ul class="lista-login">
                    <li><a href="pages/login.php" class="btn-login">Login</a></li>
                    <li><a href="pages/cadastro.php" class="btn-cadastrar">Cadastrar-se</a></li>
                </ul>
            <?php endif; ?>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="inicio">
        <div class="hero-content">
            <div class="hero-text">
                <h2 class="hero-title">O site perfeito para cadastrar seus Artigos da Facul!</h2>
                <p class="hero-subtitle">Centralize, gerencie e compartilhe o conhecimento produzido na sua instituição. Uma plataforma feita para simplificar o cadastro e o acompanhamento de projetos de pesquisa.</p>
                <div class="hero-buttons">
                    <!-- Show different buttons based on login status -->
                    <?php if ($isLoggedIn): ?>
                        <a href="pages/cadastrar_projeto.php" class="btn-primary">Cadastrar Projeto</a>
                        <a href="pages/meu_perfil.php" class="btn-secondary">Meus Projetos</a>
                    <?php else: ?>
                        <a href="pages/cadastro.php" class="btn-primary">Começar Agora</a>
                        <a href="pages/artigos_publicos.php" class="btn-secondary">Ver Artigos Cadastrados</a>
                    <?php endif; ?>
                </div>
            </div>
            <!-- Hide login form if user is already logged in -->
            <?php if (!$isLoggedIn): ?>
                <div class="hero-form">
                    <div class="login-card">
                        <h3>Nunca perca seus artigos!</h3>
                        <!-- Added CSRF token to fix security error on login -->
                        <form action="pages/login.php" method="POST">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                            <div class="form-group">
                                <label for="email">E-mail:</label>
                                <input type="email" id="email" name="email" placeholder="seu@email.com" required>
                            </div>
                            <div class="form-group">
                                <label for="senha">Senha:</label>
                                <!-- Updated password wrapper to match login.php styling -->
                                <div class="password-input-wrapper">
                                    <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
                                    <button type="button" class="toggle-password-btn" onclick="togglePasswordVisibility('senha')">
                                        <svg class="eye-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1 12S5.4 6 12 6S23 12 23 12S18.6 18 12 18S1 12 1 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <p class="signup-link">Não tem uma conta? <a href="pages/cadastro.php">Crie uma!</a></p>
                            <button type="submit" class="btn-login-form">Login</button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="hero-form">
                    <div class="login-card">
                        <h3>Bem-vindo de volta!</h3>
                        <p style="text-align: center; margin: 20px 0;">Você está logado como <strong><?php echo htmlspecialchars($userName); ?></strong></p>
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            <a href="pages/cadastrar_projeto.php" class="btn-login-form" style="text-decoration: none; text-align: center;">Cadastrar Novo Projeto</a>
                            <a href="pages/meu_perfil.php" class="btn-login-form" style="text-decoration: none; text-align: center; background-color: #6c757d;">Ver Meus Projetos</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Sobre Section -->
    <section class="sobre" id="sobre">
        <div class="container">
            <h2 class="section-title">Sobre a Plataforma</h2>
            <div class="sobre-content">
                <p class="sobre-text">A Plataforma de Projetos de Pesquisa foi desenvolvida para facilitar o registro e a gestão de iniciativas acadêmicas. Aqui, alunos e orientadores podem cadastrar seus projetos, acompanhar o andamento e divulgar suas produções em um só lugar.</p>
                <p class="sobre-text">Mais do que um sistema, é um espaço para valorizar a pesquisa e incentivar a inovação dentro das instituições de ensino.</p>
            </div>
        </div>
    </section>

    <!-- Para Quem É Section -->
    <section class="para-quem">
        <div class="container">
            <h2 class="section-title">Para quem é</h2>
            <div class="cards-grid">
                <div class="card">
                    <div class="card-icon">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 21V19C20 17.9391 19.5786 16.9217 18.8284 16.1716C18.0783 15.4214 17.0609 15 16 15H8C6.93913 15 5.92172 15.4214 5.17157 16.1716C4.42143 16.9217 4 17.9391 4 19V21" stroke="#59a4eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 11C14.2091 11 16 9.20914 16 7C16 4.79086 14.2091 3 12 3C9.79086 3 8 4.79086 8 7C8 9.20914 9.79086 11 12 11Z" stroke="#59a4eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h3 class="card-title">Estudantes</h3>
                    <p class="card-description">Cadastre e acompanhe seus projetos, organize ideias e mantenha tudo documentado.</p>
                </div>
                <div class="card">
                    <div class="card-icon">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17 21V19C17 18.1137 16.5786 17.2528 15.8284 16.5523C15.6184 15.8519 14.8581 15.3516 14 15.13" stroke="#59a4eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9 11C11.2091 11 13 9.20914 13 7C13 4.79086 11.2091 3 9 3C6.79086 3 5 4.79086 5 7C5 9.20914 6.79086 11 9 11Z" stroke="#59a4eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M23 21V19C22.9993 18.1137 22.7044 17.2528 22.1614 16.5523C21.6184 15.8519 20.8581 15.3516 20 15.13" stroke="#59a4eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M16 3.13C16.8604 3.35031 17.623 3.85071 18.1676 4.55232C18.7122 5.25392 19.0078 6.11683 19.0078 7.005C19.0078 7.89318 18.7122 8.75608 18.1676 9.45769C17.623 10.1593 16.8604 10.6597 16 10.88" stroke="#59a4eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h3 class="card-title">Orientadores</h3>
                    <p class="card-description">Gerencie suas turmas, valide projetos e acompanhe o progresso de cada aluno.</p>
                </div>
                <div class="card">
                    <div class="card-icon">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 9L12 2L21 9V20C21 20.5304 20.7893 21.0391 20.4142 21.4142C20.0391 21.7893 19.5304 22 19 22H5C4.46957 22 3.96086 21.7893 3.58579 21.4142C3.21071 21.0391 3 20.5304 3 20V9Z" stroke="#59a4eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9 22V12H15V22" stroke="#59a4eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h3 class="card-title">Instituições</h3>
                    <p class="card-description">Tenha uma visão centralizada das pesquisas em andamento e seus resultados.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Funcionalidades Section -->
    <section class="funcionalidades" id="servicos">
        <div class="container">
            <h2 class="section-title">Funcionalidades</h2>
            <div class="funcionalidades-grid">
                <div class="funcionalidade-item">
                    <div class="funcionalidade-icon">✓</div>
                    <p>Cadastro e edição de projetos de forma simples e rápida</p>
                </div>
                <div class="funcionalidade-item">
                    <div class="funcionalidade-icon">✓</div>
                    <p>Associação de alunos, orientadores e áreas temáticas</p>
                </div>
                <div class="funcionalidade-item">
                    <div class="funcionalidade-icon">✓</div>
                    <p>Controle de status (em andamento, concluído, publicado)</p>
                </div>
                <div class="funcionalidade-item">
                    <div class="funcionalidade-icon">✓</div>
                    <p>Sistema de login e relatórios</p>
                </div>
                <div class="funcionalidade-item">
                    <div class="funcionalidade-icon">✓</div>
                    <p>Interface leve, responsiva e fácil de usar</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Por Que Usar Section -->
    <section class="por-que-usar">
        <div class="container">
            <h2 class="section-title">Por que usar</h2>
            <div class="por-que-content">
                <p class="por-que-text">Muitos projetos acadêmicos acabam se perdendo em planilhas e documentos isolados. Com esta plataforma, todo o conhecimento gerado na escola ou universidade ganha visibilidade e organização.</p>
                <p class="por-que-highlight">Um ambiente único para registrar, acompanhar e compartilhar inovação.</p>
            </div>
        </div>
    </section>

    <!-- Missão Section -->
    <section class="missao">
        <div class="container">
            <h2 class="section-title">Missão</h2>
            <p class="missao-text">Valorizar o conhecimento científico e tecnológico produzido nas instituições de ensino, promovendo a colaboração entre alunos, professores e a comunidade acadêmica.</p>
        </div>
    </section>

    <!-- Contato Section -->
    <section class="contato" id="contato">
        <div class="container">
            <h2 class="section-title">Entre em Contato</h2>
            <div class="contato-content">
                <p>Tem dúvidas ou sugestões? Entre em contato comigo</p>
                <a href="mailto:viniciusg21bezerra@gmail.com" class="btn-contato">Enviar E-mail</a>
                <a href="https://api.whatsapp.com/send?phone=5547989132699" target="_blank" class="btn-contato">Enviar Mensagem</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="rodape">
        <div class="container">
            <p>Desenvolvido com dedicação por um estudante do curso de Desenvolvimento de Sistemas.</p>
            <p>Uma iniciativa voltada à inovação, aprendizado e gestão do conhecimento.</p>
            <p class="copyright">&copy; 2025 Gerenciamento de Artigos. Todos os direitos reservados à Vinicius Gabriel Bezerra.</p>
        </div>
    </footer>
</body>
</html>

<script>
    function togglePasswordVisibility(inputId) {
        var input = document.getElementById(inputId);
        if (input.type === "password") {
            input.type = "text";
        } else {
            input.type = "password";
        }
    }
</script>
