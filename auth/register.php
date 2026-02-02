<?php
require_once '../config/config.php';
require_once '../includes/functions.php';

$pageTitle = 'Register';
require_once '../includes/header.php';
?>

<div class="auth-wrapper">
    <div class="auth-card" data-aos="fade-up">
        <div class="auth-header">
            <div class="auth-title">Create Account</div>
            <p>Join the Library Management System</p>
        </div>

        <?php echo getFlash(); ?>

        <form action="auth_process.php" method="POST" class="auth-form">
            <input type="hidden" name="action" value="register">

            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Roll No</label>
                <input type="text" name="roll_no" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="flex gap-4">
                <div class="form-group w-full">
                    <label class="form-label">Department</label>
                    <select name="department" class="form-control">
                        <option value="Computer Science">Computer Science</option>
                        <option value="Engineering">Engineering</option>
                        <option value="Business">Business</option>
                        <option value="Arts">Arts</option>
                    </select>
                </div>
                <div class="form-group w-full">
                    <label class="form-label">Year</label>
                    <select name="year" class="form-control">
                        <option value="1">1st Year</option>
                        <option value="2">2nd Year</option>
                        <option value="3">3rd Year</option>
                        <option value="4">4th Year</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required minlength="6">
            </div>

            <button type="submit" class="btn btn-primary w-full justify-center">
                Register Now
            </button>
        </form>

        <div style="margin-top: 1.5rem; text-align: center; border-top: 1px solid var(--border-color); padding-top: 1rem;">
            Already have an account? <a href="../index.php" style="color: var(--primary-color); font-weight: 500; text-decoration: none;">Login Here</a>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
