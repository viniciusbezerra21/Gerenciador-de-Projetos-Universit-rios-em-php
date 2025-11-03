<?php
/**
 * Arquivo de Segurança Central
 * Gerencia autenticação, CSRF, sessão e validações de segurança
 */

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    // Configurações seguras de sessão
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_secure', isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? '1' : '0');
    ini_set('session.cookie_samesite', 'Strict');
    ini_set('session.gc_maxlifetime', '3600');
    
    @session_set_cookie_params([
        'lifetime' => 3600,
        'path' => '/',
        'domain' => isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost',
        'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
    
    @session_start();
}

/**
 * Verifica autenticação do usuário
 * Valida se a sessão está ativa e não expirou
 */
function requireLogin() {
    if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['session_created'])) {
        header('Location: login.php');
        exit;
    }
    
    // Validar timeout de 1 hora
    $timeout = 3600;
    if (time() - $_SESSION['session_created'] > $timeout) {
        session_destroy();
        header('Location: login.php?expired=1');
        exit;
    }
    
    // Atualizar tempo de atividade
    $_SESSION['last_activity'] = time();
}

/**
 * Gera token CSRF
 */
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Valida token CSRF
 */
function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Sanitiza entrada do usuário
 */
function sanitizeInput($input) {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

/**
 * Valida email
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Valida força de senha
 */
function validatePassword($password) {
    if (strlen($password) < 8) {
        return ['valid' => false, 'message' => 'Senha deve ter no mínimo 8 caracteres.'];
    }
    if (!preg_match('/[A-Z]/', $password)) {
        return ['valid' => false, 'message' => 'Senha deve conter pelo menos uma letra maiúscula.'];
    }
    if (!preg_match('/[0-9]/', $password)) {
        return ['valid' => false, 'message' => 'Senha deve conter pelo menos um número.'];
    }
    return ['valid' => true];
}

/**
 * Regenera ID de sessão
 */
function regenerateSessionId() {
    session_regenerate_id(true);
    $_SESSION['session_created'] = time();
}

/**
 * Valida tipo de usuário
 */
function validateUserType($type) {
    return in_array($type, ['aluno', 'orientador'], true);
}

/**
 * Verifica acesso para orientador (apenas orientadores)
 */
function requireOrientador() {
    requireLogin();
    if ($_SESSION['usuario_tipo'] !== 'orientador') {
        header('Location: projetos.php');
        exit;
    }
}

/**
 * Verifica acesso para aluno (apenas alunos)
 */
function requireAluno() {
    requireLogin();
    if ($_SESSION['usuario_tipo'] !== 'aluno') {
        header('Location: projetos.php');
        exit;
    }
}

?>
