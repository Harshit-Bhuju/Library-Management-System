<?php
// ======================================
// LIBRARY MANAGEMENT SYSTEM - FUNCTIONS
// Helper Functions & Security Utilities
// ======================================

require_once __DIR__ . '/../config/db.php';

// ======================================
// INPUT SANITIZATION
// ======================================

/**
 * Sanitize user input
 */
function sanitize($data)
{
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Sanitize for output (decode htmlspecialchars for display)
 */
function e($string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// ======================================
// NAVIGATION & REDIRECTS
// ======================================

/**
 * Redirect to a specific page
 */
function redirect($url)
{
    header("Location: " . BASE_URL . $url);
    exit();
}

/**
 * Get current page name
 */
function currentPage()
{
    return basename($_SERVER['PHP_SELF']);
}

// ======================================
// AUTHENTICATION & AUTHORIZATION
// ======================================

/**
 * Check if user is logged in
 */
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

/**
 * Check if user is admin
 */
function isAdmin()
{
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/**
 * Check if user is teacher
 */
function isTeacher()
{
    return isset($_SESSION['role']) && $_SESSION['role'] === 'teacher';
}

/**
 * Check if user is student
 */
function isStudent()
{
    return isset($_SESSION['role']) && $_SESSION['role'] === 'student';
}

/**
 * Check if user has specific role
 */
function hasRole($role)
{
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}

/**
 * Check if user has any of the specified roles
 */
function hasAnyRole($roles)
{
    if (!isset($_SESSION['role'])) return false;
    return in_array($_SESSION['role'], $roles);
}

/**
 * Enforce login (redirect if not logged in)
 */
function requireLogin()
{
    checkSessionTimeout();
    if (!isLoggedIn()) {
        setFlash('error', 'Please login to continue.');
        redirect('index.php');
    }
}

/**
 * Enforce Admin access
 */
function requireAdmin()
{
    requireLogin();
    if (!isAdmin()) {
        setFlash('error', 'Access denied. Admin privileges required.');
        redirectToDashboard();
    }
}

/**
 * Enforce Teacher access (teachers and admins)
 */
function requireTeacher()
{
    requireLogin();
    if (!isTeacher() && !isAdmin()) {
        setFlash('error', 'Access denied. Teacher privileges required.');
        redirectToDashboard();
    }
}

/**
 * Enforce Staff access (teachers and admins)
 */
function requireStaff()
{
    requireLogin();
    if (!isTeacher() && !isAdmin()) {
        setFlash('error', 'Access denied. Staff privileges required.');
        redirectToDashboard();
    }
}

/**
 * Redirect to appropriate dashboard based on role
 */
function redirectToDashboard()
{
    if (isAdmin()) {
        redirect('admin/dashboard.php');
    } elseif (isTeacher()) {
        redirect('teacher/dashboard.php');
    } else {
        redirect('student/dashboard.php');
    }
}

/**
 * Check session timeout
 */
function checkSessionTimeout()
{
    if (isset($_SESSION['last_activity'])) {
        if (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT) {
            // Session expired
            session_unset();
            session_destroy();
            session_start();
            setFlash('error', 'Your session has expired. Please login again.');
            redirect('index.php');
        }
    }
    $_SESSION['last_activity'] = time();
}

// ======================================
// CSRF PROTECTION
// ======================================

/**
 * Generate CSRF Token
 */
function generateCSRFToken()
{
    if (empty($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

/**
 * Verify CSRF Token
 */
function verifyCSRFToken($token)
{
    if (empty($_SESSION[CSRF_TOKEN_NAME]) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

/**
 * Output CSRF hidden input field
 */
function csrfInput()
{
    return '<input type="hidden" name="' . CSRF_TOKEN_NAME . '" value="' . generateCSRFToken() . '">';
}

/**
 * Validate CSRF on POST requests
 */
function validateCSRF()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST[CSRF_TOKEN_NAME]) || !verifyCSRFToken($_POST[CSRF_TOKEN_NAME])) {
            setFlash('error', 'Invalid request. Please try again.');
            redirect($_SERVER['HTTP_REFERER'] ?? 'index.php');
        }
    }
}

// ======================================
// FLASH MESSAGES
// ======================================

/**
 * Set Flash Message
 */
function setFlash($type, $message)
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Get and display Flash Message
 */
function getFlash()
{
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);

        $icons = [
            'success' => 'fa-check-circle',
            'error' => 'fa-exclamation-circle',
            'warning' => 'fa-exclamation-triangle',
            'info' => 'fa-info-circle'
        ];

        $colors = [
            'success' => 'bg-green-50 text-green-800 border-green-200',
            'error' => 'bg-red-50 text-red-800 border-red-200',
            'warning' => 'bg-yellow-50 text-yellow-800 border-yellow-200',
            'info' => 'bg-blue-50 text-blue-800 border-blue-200'
        ];

        $icon = $icons[$flash['type']] ?? 'fa-info-circle';
        $color = $colors[$flash['type']] ?? $colors['info'];

        return "<div class='flash-message p-4 mb-4 rounded-lg border {$color} flex items-center gap-3' data-aos='fade-down'>
                    <i class='fa-solid {$icon}'></i>
                    <span>{$flash['message']}</span>
                    <button onclick='this.parentElement.remove()' class='ml-auto hover:opacity-70'>
                        <i class='fa-solid fa-times'></i>
                    </button>
                </div>";
    }
    return '';
}

// ======================================
// DATE & TIME UTILITIES
// ======================================

/**
 * Format Date for display
 */
function formatDate($date, $format = 'F j, Y')
{
    return date($format, strtotime($date));
}

/**
 * Format Date with time
 */
function formatDateTime($date)
{
    return date('M j, Y g:i A', strtotime($date));
}

/**
 * Calculate days difference
 */
function daysDifference($date1, $date2 = null)
{
    $d1 = new DateTime($date1);
    $d2 = $date2 ? new DateTime($date2) : new DateTime();
    return $d1->diff($d2)->days;
}

/**
 * Check if date is overdue
 */
function isOverdue($dueDate)
{
    return strtotime($dueDate) < strtotime(date('Y-m-d'));
}

/**
 * Get due date status class
 */
function getDueDateClass($dueDate)
{
    $daysLeft = (strtotime($dueDate) - strtotime(date('Y-m-d'))) / 86400;
    if ($daysLeft < 0) return 'text-red-600 bg-red-50';
    if ($daysLeft <= 3) return 'text-yellow-600 bg-yellow-50';
    return 'text-green-600 bg-green-50';
}

// ======================================
// ACTIVITY LOGGING
// ======================================

/**
 * Log user activity
 */
function logActivity($actionType, $description)
{
    global $pdo;
    if (!isLoggedIn()) return;

    try {
        $stmt = $pdo->prepare("INSERT INTO activity_log (user_id, action_type, action_description, ip_address) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $_SESSION['user_id'],
            $actionType,
            $description,
            $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ]);
    } catch (Exception $e) {
        // Silently fail - logging shouldn't break the app
        error_log("Activity log error: " . $e->getMessage());
    }
}

// ======================================
// NOTIFICATION HELPERS
// ======================================

/**
 * Send notification to user
 */
function sendNotification($userId, $title, $message, $type = 'info', $link = null)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO notifications (user_id, title, message, notification_type, link) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$userId, $title, $message, $type, $link]);
    } catch (Exception $e) {
        error_log("Notification error: " . $e->getMessage());
        return false;
    }
}

/**
 * Get unread notification count
 */
function getUnreadNotificationCount($userId = null)
{
    global $pdo;
    $userId = $userId ?? ($_SESSION['user_id'] ?? 0);
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0");
    $stmt->execute([$userId]);
    return $stmt->fetchColumn();
}

// ======================================
// SYSTEM SETTINGS
// ======================================

/**
 * Get system setting
 */
function getSetting($key, $default = null)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT setting_value FROM system_settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $result = $stmt->fetchColumn();
        return $result !== false ? $result : $default;
    } catch (Exception $e) {
        return $default;
    }
}

/**
 * Get borrow limit for current user role
 */
function getBorrowLimit()
{
    if (isTeacher()) {
        return (int) getSetting('max_books_teacher', MAX_BOOKS_TEACHER);
    }
    return (int) getSetting('max_books_student', MAX_BOOKS_STUDENT);
}

/**
 * Get current borrow count for a user
 */
function getCurrentBorrowCount($userId = null)
{
    global $pdo;
    $userId = $userId ?? ($_SESSION['user_id'] ?? 0);
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM issued_books WHERE user_id = ? AND status IN ('issued', 'overdue')");
    $stmt->execute([$userId]);
    return (int) $stmt->fetchColumn();
}

/**
 * Check if user can borrow more books
 */
function canBorrowMore($userId = null)
{
    return getCurrentBorrowCount($userId) < getBorrowLimit();
}

/**
 * Check if user has overdue books
 */
function hasOverdueBooks($userId = null)
{
    global $pdo;
    $userId = $userId ?? ($_SESSION['user_id'] ?? 0);
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM issued_books WHERE user_id = ? AND status = 'overdue'");
    $stmt->execute([$userId]);
    return $stmt->fetchColumn() > 0;
}

// ======================================
// UI HELPERS
// ======================================

/**
 * Get role badge HTML
 */
function getRoleBadge($role)
{
    $badges = [
        'admin' => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-700">Admin</span>',
        'teacher' => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">Teacher</span>',
        'student' => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">Student</span>'
    ];
    return $badges[$role] ?? $badges['student'];
}

/**
 * Get status badge HTML
 */
function getStatusBadge($status)
{
    $badges = [
        'issued' => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700">Issued</span>',
        'returned' => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">Returned</span>',
        'overdue' => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">Overdue</span>'
    ];
    return $badges[$status] ?? '';
}

/**
 * Get user initials for avatar
 */
function getInitials($name)
{
    $words = explode(' ', $name);
    $initials = '';
    foreach ($words as $word) {
        $initials .= strtoupper(substr($word, 0, 1));
    }
    return substr($initials, 0, 2);
}

/**
 * Generate avatar HTML
 */
function getAvatar($name, $size = 40)
{
    $initials = getInitials($name);
    $colors = ['bg-primary-500', 'bg-blue-500', 'bg-green-500', 'bg-purple-500', 'bg-pink-500'];
    $colorIndex = ord($name[0]) % count($colors);
    $color = $colors[$colorIndex];

    return "<div class='{$color} text-white rounded-full flex items-center justify-center font-semibold' 
                style='width: {$size}px; height: {$size}px; font-size: " . ($size / 2.5) . "px;'>
                {$initials}
            </div>";
}

// ======================================
// FILE UPLOAD HELPERS
// ======================================

/**
 * Handle cover image upload
 */
function handleBookImageUpload($file)
{
    if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
        return null;
    }

    // Validate file type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, ALLOWED_IMAGE_TYPES)) {
        throw new Exception('Invalid file type. Only JPEG, PNG, and WebP are allowed.');
    }

    // Validate file size
    if ($file['size'] > MAX_UPLOAD_SIZE) {
        throw new Exception('File size exceeds maximum allowed (5MB).');
    }

    // Create uploads directory if not exists
    if (!is_dir(COVERS_PATH)) {
        mkdir(COVERS_PATH, 0755, true);
    }

    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('cover_') . '.' . $extension;
    $filepath = COVERS_PATH . $filename;

    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return 'uploads/covers/' . $filename;
    }

    throw new Exception('Failed to upload file.');
}

// ======================================
// PAGINATION HELPER
// ======================================

/**
 * Generate pagination HTML
 */
function paginate($totalItems, $currentPage, $perPage, $urlPattern)
{
    $totalPages = ceil($totalItems / $perPage);
    if ($totalPages <= 1) return '';

    $html = '<div class="flex items-center justify-center gap-2 mt-6">';

    // Previous button
    if ($currentPage > 1) {
        $html .= '<a href="' . str_replace('{page}', $currentPage - 1, $urlPattern) . '" 
                     class="px-3 py-2 rounded-lg border hover:bg-gray-50 transition">
                     <i class="fa-solid fa-chevron-left"></i>
                 </a>';
    }

    // Page numbers
    for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++) {
        $activeClass = $i === $currentPage ? 'bg-primary-600 text-white' : 'hover:bg-gray-50';
        $html .= '<a href="' . str_replace('{page}', $i, $urlPattern) . '" 
                     class="px-3 py-2 rounded-lg border ' . $activeClass . ' transition">' . $i . '</a>';
    }

    // Next button
    if ($currentPage < $totalPages) {
        $html .= '<a href="' . str_replace('{page}', $currentPage + 1, $urlPattern) . '" 
                     class="px-3 py-2 rounded-lg border hover:bg-gray-50 transition">
                     <i class="fa-solid fa-chevron-right"></i>
                 </a>';
    }

    $html .= '</div>';
    return $html;
}
