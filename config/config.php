<?php
// Config - Global Constants
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // Update with your DB password
define('DB_NAME', 'library_system');

define('BASE_URL', 'http://localhost/library-management-system/'); // Adjust if in a subfolder
define('SITE_NAME', 'LMS - Library Management System');

// Error reporting (Disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('Asia/Kathmandu'); 

session_start();
?>
