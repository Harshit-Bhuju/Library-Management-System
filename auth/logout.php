<?php
require_once '../config/config.php';
session_destroy();

// Clear Remember Me cookie
if (isset($_COOKIE['remember_me'])) {
    setcookie('remember_me', '', [
        'expires' => time() - 3600,
        'path' => '/',
        'secure' => isset($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
}

header("Location: " . BASE_URL . "index.php");
exit();
