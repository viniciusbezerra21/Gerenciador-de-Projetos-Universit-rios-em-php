<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ./pages/login.php');
    exit;
}

$nome = $_SESSION['usuario_nome'];
$tipo = $_SESSION['usuario_tipo'];
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Projetos</title>
    <link rel="stylesheet" href="../css/style.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Raleway:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
</head>

<body>
    <aside>
        <h2 class="titulo-sidebar">Bem-vindo(a), <?php echo htmlspecialchars($nome); ?>!</h2>
        <div class="funcoes-sidebar">
            <ul>
                <li id="dashboard"><a href="dashboard.php">Seus Projetos</a></li>
                <li id="cadastrar-projeto"><a href="cadastrar_projeto.php">Cadastrar Projeto</a></li>
                <li id="relatorios"><a href="relatorios.php">Gerar Relatórios</a></li>
                <li id="sair"><a href="../php/logout.php">Sair</a></li>
            </ul>
        </div>
    </aside>

    <main>
        <div class="container">
            <h1 class="titulo">Veja seus Projetos</h1>

            <div class="apresentacao">
                <p>Seus projetos cadastrados:</p>
            </div>

            <div class="lista-projetos">
                <ul id="projetos">
                    <li>
                        <h3 class="titulo-card">Projeto 1</h3>
                        <p class="descricao-card">Descrição do projeto 1...</p>
                        <div class="imagem-projeto">
                            <img>
                        </div>
                        <a href="#" class="btn-card">Ver Detalhes</a>
                    </li>
                </ul>
            </div>
        </div>



    </main>


</body>

</html>