<?php
session_start();
require_once '../config/security.php';
require_once '../config/database.php';

// Verificar autenticação
requireLogin();

// Validar CSRF token
if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
    $_SESSION['erro_exclusao'] = 'Erro de segurança. Operação cancelada.';
    header('Location: ../pages/meu_perfil.php');
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$usuario_email = $_SESSION['usuario_email'];

$conexao = getConnection();

if (!$conexao) {
    $_SESSION['erro_exclusao'] = 'Erro na conexão com o banco de dados.';
    header('Location: ../pages/meu_perfil.php');
    exit;
}

try {
    // Iniciar transação para garantir integridade dos dados
    $conexao->begin_transaction();

    // Deletar todos os projetos do usuário
    $stmt_projetos = $conexao->prepare("
        DELETE FROM projetos 
        WHERE id_orientador = (SELECT id FROM orientadores WHERE email = ?) 
        OR id IN (SELECT id_projeto FROM projetos_alunos WHERE id_aluno = (SELECT id FROM alunos WHERE email = ?))
    ");
    $stmt_projetos->bind_param("ss", $usuario_email, $usuario_email);
    $stmt_projetos->execute();
    $stmt_projetos->close();

    // Deletar relações de aluno-projeto
    $stmt_aluno_proj = $conexao->prepare("
        DELETE FROM projetos_alunos 
        WHERE id_aluno = (SELECT id FROM alunos WHERE email = ?)
    ");
    $stmt_aluno_proj->bind_param("s", $usuario_email);
    $stmt_aluno_proj->execute();
    $stmt_aluno_proj->close();

    // Deletar do registro de orientadores
    $stmt_orientador = $conexao->prepare("DELETE FROM orientadores WHERE email = ?");
    $stmt_orientador->bind_param("s", $usuario_email);
    $stmt_orientador->execute();
    $stmt_orientador->close();

    // Deletar do registro de alunos
    $stmt_aluno = $conexao->prepare("DELETE FROM alunos WHERE email = ?");
    $stmt_aluno->bind_param("s", $usuario_email);
    $stmt_aluno->execute();
    $stmt_aluno->close();

    // Deletar token de "lembrar-me" se existir
    $stmt_token = $conexao->prepare("DELETE FROM remember_tokens WHERE usuario_id = ?");
    $stmt_token->bind_param("i", $usuario_id);
    $stmt_token->execute();
    $stmt_token->close();

    // Deletar foto de perfil se existir
    $stmt_foto = $conexao->prepare("SELECT foto_perfil FROM usuarios WHERE id = ?");
    $stmt_foto->bind_param("i", $usuario_id);
    $stmt_foto->execute();
    $result_foto = $stmt_foto->get_result();
    if ($result_foto && $result_foto->num_rows > 0) {
        $user_foto = $result_foto->fetch_assoc();
        if ($user_foto['foto_perfil']) {
            $caminho_foto = '../uploads/perfil/' . $user_foto['foto_perfil'];
            if (file_exists($caminho_foto)) {
                @unlink($caminho_foto);
            }
        }
    }
    $stmt_foto->close();

    // Deletar usuário
    $stmt_user = $conexao->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt_user->bind_param("i", $usuario_id);
    if (!$stmt_user->execute()) {
        throw new Exception("Erro ao deletar usuário: " . $stmt_user->error);
    }
    $stmt_user->close();

    // Confirmar transação
    $conexao->commit();
    $conexao->close();

    // Limpar sessão e cookies
    $_SESSION = [];
    deleteRememberMeCookie();
    
    if (ini_get('session.use_cookies') !== false) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }
    
    session_destroy();

    // Redirecionar para página de confirmação
    header('Location: ../pages/perfil_deletado.php');
    exit;

} catch (Exception $e) {
    // Fazer rollback em caso de erro
    $conexao->rollback();
    $conexao->close();
    
    error_log("Erro ao deletar perfil do usuário $usuario_id: " . $e->getMessage());
    
    // Redirecionar com mensagem de erro
    $_SESSION['erro_exclusao'] = 'Erro ao processar exclusão da conta. Tente novamente.';
    header('Location: ../pages/meu_perfil.php');
    exit;
}
?>
