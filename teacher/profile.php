<?php
require_once '../config/config.php';
require_once '../includes/functions.php';
requireLogin();

$user_id = $_SESSION['user_id'];

// Fetch user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!verifyCSRFToken($_POST[CSRF_TOKEN_NAME] ?? '')) {
        setFlash('error', 'Invalid request.');
        redirect($_SESSION['role'] . '/profile.php');
    }

    if (isset($_POST['update_profile'])) {
        $name = sanitize($_POST['name']);
        $phone = sanitize($_POST['phone']);
        $department = sanitize($_POST['department']);

        try {
            $stmt = $pdo->prepare("UPDATE users SET name = ?, phone = ?, department = ? WHERE user_id = ?");
            $stmt->execute([$name, $phone, $department, $user_id]);

            $_SESSION['name'] = $name;
            logActivity('profile_update', 'Updated profile information');
            setFlash('success', 'Profile updated successfully!');
        } catch (PDOException $e) {
            setFlash('error', 'Failed to update profile.');
        }
        redirect($_SESSION['role'] . '/profile.php');
    }

    if (isset($_POST['change_password'])) {
        $current = $_POST['current_password'];
        $new = $_POST['new_password'];
        $confirm = $_POST['confirm_password'];

        if (!password_verify($current, $user['password'])) {
            setFlash('error', 'Current password is incorrect.');
        } elseif (strlen($new) < 6) {
            setFlash('error', 'New password must be at least 6 characters.');
        } elseif ($new !== $confirm) {
            setFlash('error', 'New passwords do not match.');
        } else {
            $hashed = password_hash($new, PASSWORD_DEFAULT);
            $pdo->prepare("UPDATE users SET password = ? WHERE user_id = ?")->execute([$hashed, $user_id]);

            logActivity('password_change', 'Changed password');
            setFlash('success', 'Password changed successfully!');
        }
        redirect($_SESSION['role'] . '/profile.php');
    }
}

// Stats
$borrow_stats = $pdo->prepare("
    SELECT 
        COUNT(*) as total_borrows,
        SUM(status = 'returned') as books_read,
        SUM(status IN ('issued', 'overdue')) as current_borrows
    FROM issued_books WHERE user_id = ?
");
$borrow_stats->execute([$user_id]);
$stats = $borrow_stats->fetch();

$pageTitle = 'My Profile';
require_once '../includes/header.php';
?>

<div class="dashboard-layout">
    <?php require_once '../includes/sidebar.php'; ?>

    <main class="main-content lg:mt-0 mt-16">
        <header class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white">My Profile</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Manage your account settings</p>
        </header>

        <?php echo getFlash(); ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Profile Card -->
            <div class="lg:col-span-1">
                <div class="stat-card text-center" data-aos="fade-up">
                    <div class="mb-4">
                        <?php echo getAvatar($user['name'], 96, 'mx-auto'); ?>
                    </div>
                    <h3 class="text-xl font-bold dark:text-white"><?php echo e($user['name']); ?></h3>
                    <p class="text-gray-500"><?php echo e($user['email']); ?></p>
                    <div class="mt-2">
                        <?php echo getRoleBadge($user['role']); ?>
                    </div>

                    <div class="border-t border-gray-100 dark:border-slate-700 mt-4 pt-4">
                        <div class="grid grid-cols-3 gap-2 text-center">
                            <div>
                                <p class="text-lg font-bold dark:text-white"><?php echo $stats['total_borrows']; ?></p>
                                <p class="text-xs text-gray-500">Total</p>
                            </div>
                            <div>
                                <p class="text-lg font-bold text-green-600"><?php echo $stats['books_read']; ?></p>
                                <p class="text-xs text-gray-500">Read</p>
                            </div>
                            <div>
                                <p class="text-lg font-bold text-primary-600"><?php echo $stats['current_borrows']; ?></p>
                                <p class="text-xs text-gray-500">Active</p>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 dark:border-slate-700 mt-4 pt-4 text-sm text-gray-500">
                        <p><i class="fa-solid fa-calendar mr-2"></i>Joined <?php echo formatDate($user['created_at'], 'M j, Y'); ?></p>
                        <?php if ($user['last_login']): ?>
                            <p class="mt-1"><i class="fa-solid fa-clock mr-2"></i>Last login <?php echo formatDateTime($user['last_login']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Edit Forms -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Profile Info -->
                <div class="stat-card" data-aos="fade-up" data-aos-delay="100">
                    <h3 class="text-lg font-semibold mb-4 dark:text-white">
                        <i class="fa-solid fa-user-pen text-primary-500 mr-2"></i>
                        Profile Information
                    </h3>

                    <form method="POST" class="space-y-4">
                        <input type="hidden" name="update_profile" value="1">
                        <?php echo csrfInput(); ?>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control" value="<?php echo e($user['name']); ?>" required>
                            </div>

                            <div>
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control bg-gray-100 dark:bg-slate-600" value="<?php echo e($user['email']); ?>" disabled>
                                <p class="text-xs text-gray-500 mt-1">Email cannot be changed</p>
                            </div>

                            <div>
                                <label class="form-label">Phone</label>
                                <input type="tel" name="phone" class="form-control" value="<?php echo e($user['phone']); ?>">
                            </div>

                            <div>
                                <label class="form-label">Department</label>
                                <select name="department" class="form-control">
                                    <option value="">Select Department</option>
                                    <?php
                                    $departments = ['Physical Science', 'Biology Science', 'Business', 'Management', 'Hotel Management', 'Computer Science'];
                                    foreach ($departments as $dept): ?>
                                        <option value="<?php echo $dept; ?>" <?php echo $user['department'] == $dept ? 'selected' : ''; ?>><?php echo $dept; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-save mr-2"></i> Save Changes
                        </button>
                    </form>
                </div>

                <!-- Change Password -->
                <div class="stat-card" data-aos="fade-up" data-aos-delay="150">
                    <h3 class="text-lg font-semibold mb-4 dark:text-white">
                        <i class="fa-solid fa-lock text-primary-500 mr-2"></i>
                        Change Password
                    </h3>

                    <form method="POST" class="space-y-4">
                        <input type="hidden" name="change_password" value="1">
                        <?php echo csrfInput(); ?>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="form-label">Current Password</label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>

                            <div>
                                <label class="form-label">New Password</label>
                                <input type="password" name="new_password" class="form-control" minlength="6" required>
                            </div>

                            <div>
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" name="confirm_password" class="form-control" minlength="6" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-secondary">
                            <i class="fa-solid fa-key mr-2"></i> Change Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require_once '../includes/footer.php'; ?>