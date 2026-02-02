<?php
require_once 'config/config.php';
require_once 'config/db.php';

try {
    echo "<h1>Resetting Admin Password</h1>";

    $email = 'admin@library.com';
    $password = 'admin123';
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if admin exists
    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Update existing admin
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE user_id = ?");
        $stmt->execute([$hashed_password, $user['user_id']]);
        echo "✅ Admin password updated to: <strong>$password</strong><br>";
    } else {
        // Create new admin
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'admin')");
        $stmt->execute(['System Admin', $email, $hashed_password]);
        echo "✅ Admin account created with password: <strong>$password</strong><br>";
    }

    echo "<h3><a href='index.php'>Go to Login</a></h3>";

} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
