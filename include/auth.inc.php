<?php
declare(strict_types=1);

function requireLogin(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (empty($_SESSION['user_id'])) {
        http_response_code(401);
        exit('Unauthorized');
    }

    // timeout 30 นาที
    $now = time();
    $timeout = 1800;

    if (!empty($_SESSION['last_activity']) && ($now - (int)$_SESSION['last_activity']) > $timeout) {
        session_unset();
        session_destroy();
        http_response_code(401);
        exit('Session expired');
    }
    $_SESSION['last_activity'] = $now;

    // bind กับ user agent แบบเบา ๆ
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
    if (empty($_SESSION['ua'])) {
        $_SESSION['ua'] = $ua;
    } elseif ($_SESSION['ua'] !== $ua) {
        session_unset();
        session_destroy();
        http_response_code(401);
        exit('Invalid session');
    }
}
?>