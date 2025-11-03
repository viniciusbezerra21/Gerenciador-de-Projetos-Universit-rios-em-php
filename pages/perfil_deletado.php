<?php

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Deletado - Plataforma de Projetos</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 20px;
        }
        .container-deletado {
            background: white;
            border-radius: 15px;
            padding: 50px;
            max-width: 500px;
            width: 100%;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }
        .icone-despedida {
            font-size: 80px;
            margin-bottom: 20px;
            opacity: 0.8;
        }
        .titulo-despedida {
            color: #2c3e50;
            font-size: 28px;
            margin-bottom: 15px;
        }
        .mensagem-despedida {
            color: #7f8c8d;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .destaque {
            color: #e74c3c;
            font-weight: 600;
        }
        .link-retorno {
            display: inline-block;
            padding: 12px 30px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s ease;
            margin-top: 20px;
        }
        .link-retorno:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        }
    </style>
</head>
<body>
    <div class="container-deletado">
        <div class="icone-despedida">👋</div>
        <h1 class="titulo-despedida">Sua Conta Foi Deletada</h1>
        <p class="mensagem-despedida">
            Lamentamos imensamente que você tenha decidido partir. Sua conta e todos os dados associados foram 
            <span class="destaque">permanentemente removidos</span> do sistema.
        </p>
        <p class="mensagem-despedida">
            Esperamos poder ajudá-lo novamente no futuro. Caso mude de ideia, você sempre pode criar uma nova conta.
        </p>
        <a href="../index.php" class="link-retorno">Voltar ao Início</a>
    </div>
</body>
</html>
