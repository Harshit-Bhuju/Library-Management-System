<?php
// ======================================
// AUTHENTICATION PROCESS
// Login & Registration Handler
// ======================================

require_once '../config/db.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // ======================================
    // LOGIN LOGIC
    // ======================================
    if (isset($_POST['action']) && $_POST['action'] == 'login') {

        // Validate CSRF token
        if (!verifyCSRFToken($_POST[CSRF_TOKEN_NAME] ?? '')) {
            setFlash('error', 'Invalid request. Please try again.');
            redirect('index.php');
        }

        $email = sanitize($_POST['email']);
        $password = $_POST['password'];
        $remember = isset($_POST['remember_me']);

        try {
            // Find user by email
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user) {
                // Verify password
                if (password_verify($password, $user['password'])) {

                    // Check if account is active
                    if ($user['is_active'] == 0) {
                        setFlash('error', 'Your account has been deactivated. Please contact the administrator.');
                        redirect('index.php');
                    }

                    // Set session variables
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['name'] = $user['name'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['last_activity'] = time();

                    // Handle Remember Me
                    if ($remember) {
                        $token = bin2hex(random_bytes(32));
                        $hashedToken = password_hash($token, PASSWORD_DEFAULT);

                        // Save token to database
                        $stmt = $pdo->prepare("UPDATE users SET remember_token = ? WHERE user_id = ?");
                        $stmt->execute([$hashedToken, $user['user_id']]);

                        // Set cookie for 30 days
                        $cookieValue = $user['user_id'] . ':' . $token;
                        setcookie('remember_me', $cookieValue, [
                            'expires' => time() + (86400 * REMEMBER_ME_DAYS),
                            'path' => '/',
                            'secure' => isset($_SERVER['HTTPS']),
                            'httponly' => true,
                            'samesite' => 'Lax'
                        ]);
                    }

                    // Update last login
                    $stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?");
                    $stmt->execute([$user['user_id']]);

                    // Log activity
                    logActivity('login', 'User logged in successfully');

                    // Redirect based on role
                    setFlash('success', 'Welcome back, ' . $user['name'] . '!');

                    if ($user['role'] == 'admin') {
                        redirect('admin/dashboard.php');
                    } elseif ($user['role'] == 'teacher') {
                        redirect('teacher/dashboard.php');
                    } else {
                        redirect('student/dashboard.php');
                    }
                } else {
                    setFlash('error', 'Incorrect password. Please try again.');
                    redirect('index.php');
                }
            } else {
                setFlash('error', 'No account found with that email address.');
                redirect('index.php');
            }
        } catch (Exception $e) {
            setFlash('error', 'Login error: ' . $e->getMessage());
            redirect('index.php');
        }
    }

    // ======================================
    // REGISTRATION LOGIC
    // ======================================
    elseif (isset($_POST['action']) && $_POST['action'] == 'register') {

        // Validate CSRF token
        if (!verifyCSRFToken($_POST[CSRF_TOKEN_NAME] ?? '')) {
            setFlash('error', 'Invalid request. Please try again.');
            redirect('auth/register.php');
        }

        $name = sanitize($_POST['name']);
        $email = sanitize($_POST['email']);
        $phone = sanitize($_POST['phone'] ?? '');
        $department = sanitize($_POST['department']);
        $department = sanitize($_POST['department']);
        $class = (int)$_POST['class'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'] ?? '';

        // Backend Validation
        $errors = [];

        if (empty($name)) {
            $errors[] = 'Name is required';
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email is required';
        }

        if (strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters';
        }

        if ($password !== $confirm_password) {
            $errors[] = 'Passwords do not match';
        }

        if (!empty($errors)) {
            setFlash('error', implode('<br>', $errors));
            redirect('auth/register.php');
        }

        try {
            // Check if email already exists
            $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->rowCount() > 0) {
                setFlash('error', 'An account with this email already exists.');
                redirect('auth/register.php');
            }

            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert User
            $sql = "INSERT INTO users (name, email, phone, department, class, password, role) 
                    VALUES (?, ?, ?, ?, ?, ?, 'student')";
            $stmt = $pdo->prepare($sql);

            if ($stmt->execute([$name, $email, $phone, $department, $class, $hashed_password])) {
                // Log activity (using system for unlogged actions)
                $newUserId = $pdo->lastInsertId();

                // Send welcome notification
                // sendNotification removed

                setFlash('success', 'Registration successful! Please login with your email and password.');
                redirect('index.php');
            } else {
                setFlash('error', 'Registration failed. Please try again.');
                redirect('auth/register.php');
            }
        } catch (Exception $e) {
            setFlash('error', 'Error: ' . $e->getMessage());
            redirect('auth/register.php');
        }
    }

    // ======================================
    // PASSWORD CHANGE LOGIC
    // ======================================
    elseif (isset($_POST['action']) && $_POST['action'] == 'change_password') {
        requireLogin();

        // Validate CSRF token
        if (!verifyCSRFToken($_POST[CSRF_TOKEN_NAME] ?? '')) {
            setFlash('error', 'Invalid request. Please try again.');
            redirectToDashboard();
        }

        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if (strlen($new_password) < 6) {
            setFlash('error', 'New password must be at least 6 characters.');
            redirect('student/profile.php');
        }

        if ($new_password !== $confirm_password) {
            setFlash('error', 'New passwords do not match.');
            redirect('student/profile.php');
        }

        try {
            // Verify current password
            $stmt = $pdo->prepare("SELECT password FROM users WHERE user_id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch();

            if (!password_verify($current_password, $user['password'])) {
                setFlash('error', 'Current password is incorrect.');
                redirect('student/profile.php');
            }

            // Update password
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE user_id = ?");
            $stmt->execute([$hashed, $_SESSION['user_id']]);

            logActivity('password_change', 'Password changed successfully');
            setFlash('success', 'Password changed successfully!');
            redirect('student/profile.php');
        } catch (Exception $e) {
            setFlash('error', 'Error changing password: ' . $e->getMessage());
            redirect('student/profile.php');
        }
    }
} else {
    redirect('index.php');
}
