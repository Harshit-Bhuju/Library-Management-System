<?php
require_once 'config/config.php';
require_once 'includes/functions.php';

// If already logged in, redirect to dashboard
if (isLoggedIn()) {
    if (isAdmin()) {
        redirect('admin/dashboard.php');
    } else {
        redirect('student/dashboard.php');
    }
}

$pageTitle = 'Login';
require_once 'includes/header.php';
?>

<div class="auth-wrapper">
    <div class="auth-card" data-aos="zoom-in">
        <div class="auth-header">
            <div class="auth-title">
                <i class="fa-solid fa-book-open-reader"></i> LMS
            </div>
            <p>Library Management System</p>
        </div>

        <?php echo getFlash(); ?>

        <form action="auth/auth_process.php" method="POST">
            <input type="hidden" name="action" value="login">
            
            <div class="form-group">
                <label class="form-label">Email or Student ID</label>
                <div style="position: relative;">
                    <i class="fa-solid fa-user" style="position: absolute; top: 15px; left: 15px; color: var(--text-muted);"></i>
                    <input type="text" name="identifier" class="form-control" style="padding-left: 40px;" placeholder="Enter your ID" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <div style="position: relative;">
                    <i class="fa-solid fa-lock" style="position: absolute; top: 15px; left: 15px; color: var(--text-muted);"></i>
                    <input type="password" name="password" class="form-control" style="padding-left: 40px;" placeholder="Enter password" required>
                </div>
            </div>

            <div class="flex justify-between items-center" style="margin-bottom: 1.5rem;">
                <label class="flex items-center gap-2" style="cursor: pointer;">
                    <input type="checkbox"> Remember me
                </label>
                <a href="#" style="color: var(--primary-color); text-decoration: none; font-size: 0.9rem;">Forgot password?</a>
            </div>

            <button type="submit" class="btn btn-primary w-full justify-center">
                Sign In <i class="fa-solid fa-arrow-right"></i>
            </button>
        </form>

        <div style="margin-top: 1.5rem; text-align: center; border-top: 1px solid var(--border-color); padding-top: 1rem;">
            New Student? <a href="auth/register.php" style="color: var(--primary-color); font-weight: 500; text-decoration: none;">Register Here</a>
        </div>
    </div>
</div>

<!-- Theme Toggle Button (Fixed) -->
<button onclick="toggleTheme()" style="position: fixed; bottom: 20px; right: 20px; width: 50px; height: 50px; border-radius: 50%; background: var(--card-bg); border: 1px solid var(--border-color); box-shadow: 0 4px 6px rgba(0,0,0,0.1); cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
    <i class="fa-solid fa-moon"></i>
</button>

<?php require_once 'includes/footer.php'; ?>
