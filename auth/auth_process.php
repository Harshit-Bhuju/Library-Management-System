<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // LOGIN Logic
    if (isset($_POST['action']) && $_POST['action'] == 'login') {
        $identifier = sanitize($_POST['identifier']); // Email or Roll No
        $password = $_POST['password'];

        try {
            // Check by email OR roll_no
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? OR roll_no = ?");
            $stmt->execute([$identifier, $identifier]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Success
                if ($user['is_active'] == 0) {
                    setFlash('error', 'Your account is deactivated. Contact admin.');
                    redirect('index.php');
                }

                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['roll_no'] = $user['roll_no'];

                if ($user['role'] == 'admin') {
                    redirect('admin/dashboard.php');
                } else {
                    redirect('student/dashboard.php');
                }

            } else {
                setFlash('error', 'Invalid credentials');
                redirect('index.php');
            }
        } catch (Exception $e) {
            setFlash('error', 'System error: ' . $e->getMessage());
            redirect('index.php');
        }
    }

    // REGISTRATION Logic
    elseif (isset($_POST['action']) && $_POST['action'] == 'register') {
        $name = sanitize($_POST['name']);
        $roll_no = sanitize($_POST['roll_no']);
        $email = sanitize($_POST['email']);
        $department = sanitize($_POST['department']);
        $year = (int)$_POST['year'];
        $password = $_POST['password'];

        // Backend Validation
        if (empty($name) || empty($email) || empty($password)) {
            setFlash('error', 'All fields are required');
            redirect('auth/register.php');
        }

        try {
            // Check if user exists
            $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ? OR roll_no = ?");
            $stmt->execute([$email, $roll_no]);
            if ($stmt->rowCount() > 0) {
                setFlash('error', 'User already exists (Email or Roll No)');
                redirect('auth/register.php');
            }

            // Insert User
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (name, roll_no, email, department, year, password, role) VALUES (?, ?, ?, ?, ?, ?, 'student')";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$name, $roll_no, $email, $department, $year, $hashed_password])) {
                setFlash('success', 'Registration successful! Please login.');
                redirect('index.php');
            } else {
                setFlash('error', 'Registration failed.');
                redirect('auth/register.php');
            }

        } catch (Exception $e) {
            setFlash('error', 'Error: ' . $e->getMessage());
            redirect('auth/register.php');
        }
    }
} else {
    redirect('index.php');
}
?>
