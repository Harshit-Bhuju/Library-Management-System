<?php
// Helper Functions

/**
 * Sanitize user input
 */
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Redirect to a specific page
 */
function redirect($url) {
    header("Location: " . BASE_URL . $url);
    exit();
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Check if user is admin
 */
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/**
 * Enforce login (redirect if not logged in)
 */
function requireLogin() {
    if (!isLoggedIn()) {
        redirect('index.php');
    }
}

/**
 * Enforce Admin (redirect if not admin)
 */
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        redirect('student/dashboard.php');
    }
}

/**
 * Flash Message using Session
 * Usage: setFlash('success', 'Operation successful!');
 * Display: echo getFlash();
 */
function setFlash($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        
        $class = ($flash['type'] == 'success') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
        // Using a simple alert style for now, can be upgraded to Toast later
        return "<div class='p-4 mb-4 rounded {$class}'>{$flash['message']}</div>";
    }
    return '';
}

/**
 * Format Date
 */
function formatDate($date) {
    return date('F j, Y', strtotime($date));
}
?>
