<?php
// ======================================
// LIBRARY MANAGEMENT SYSTEM - CONFIG
// ======================================

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'library_system');

// Site Configuration - Dynamic Base URL
$protocol = 'http';
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    $protocol = 'https';
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $protocol = 'https';
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on') {
    $protocol = 'https';
}
$host = $_SERVER['HTTP_HOST'];

// Dynamic Base URL - assumes the app is always in 'library-management-system' folder
// Modify the path below if you move the app to the root directory or a different folder
define('BASE_URL', $protocol . "://" . $host . "/library-management-system/");
define('SITE_NAME', 'LMS - Library Management System');

// Security Configuration
define('SESSION_TIMEOUT', 1800); // 30 minutes in seconds
define('REMEMBER_ME_DAYS', 30);  // Days for persistent login
define('CSRF_TOKEN_NAME', 'csrf_token');

// Library Rules (defaults, can be overridden by system_settings table)
define('DEFAULT_BORROW_DAYS', 15);
define('DEFAULT_FINE_PER_DAY', 10);
define('MAX_BOOKS_STUDENT', 3);
define('MAX_BOOKS_TEACHER', 5);
define('CURRENCY_SYMBOL', 'NPR');

// eSewa Configuration (Sandbox)
define('ESEWA_MERCHANT_CODE', 'EPAYTEST');
define('ESEWA_SECRET_KEY', '8gBm/:&EnhH.1/q');
define('ESEWA_URL', 'https://rc-epay.esewa.com.np/api/epay/main/v2/form');

// Upload Configuration
define('UPLOAD_PATH', __DIR__ . '/../uploads/');
define('COVERS_PATH', UPLOAD_PATH . 'covers/');
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp']);

// Error Reporting (Disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('Asia/Kathmandu');

// Start Session with security settings
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => isset($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

// Regenerate session ID periodically to prevent fixation
if (!isset($_SESSION['last_regeneration'])) {
    $_SESSION['last_regeneration'] = time();
} elseif (time() - $_SESSION['last_regeneration'] > 300) { // Every 5 minutes
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}
