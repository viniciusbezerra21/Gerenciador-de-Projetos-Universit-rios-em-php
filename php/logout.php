<?php
require_once '../config/security.php';

if (session_status() !== PHP_SESSION_NONE) {
    $_SESSION = [];
    
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
}

header('Location: ../index.php');
exit;
?>
