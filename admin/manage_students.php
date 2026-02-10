<?php
require_once '../config/config.php';
require_once '../includes/functions.php';
requireAdmin();

// Handle user actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!verifyCSRFToken($_POST[CSRF_TOKEN_NAME] ?? '')) {
        setFlash('error', 'Invalid request.');
        redirect('admin/manage_students.php');
    }

    // Add User
    if (isset($_POST['add_user'])) {
        $name = sanitize($_POST['name']);
        $email = sanitize($_POST['email']);
        $phone = sanitize($_POST['phone']);
        $department = sanitize($_POST['department']);
        $department = sanitize($_POST['department']);
        $class = sanitize($_POST['class']);
        $role = in_array($_POST['role'], ['student', 'teacher', 'admin']) ? $_POST['role'] : 'student';
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("
                INSERT INTO users (name, email, phone, department, class, role, password, is_active) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 1)
            ");
            $stmt->execute([$name, $email, $phone, $department, $class, $role, $password]);

            logActivity('user_add', "Added user: {$name} ({$role})");
            setFlash('success', "User '{$name}' created successfully!");
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                setFlash('error', 'A user with this email already exists.');
            } else {
                setFlash('error', 'Failed to create user: ' . $e->getMessage());
            }
        }
        redirect('admin/manage_students.php');
    }

    // Toggle Status
    if (isset($_POST['toggle_status'])) {
        $user_id = (int) $_POST['user_id'];
        $pdo->prepare("UPDATE users SET is_active = NOT is_active WHERE user_id = ?")->execute([$user_id]);
        setFlash('success', 'User status updated.');
        redirect('admin/manage_students.php');
    }

    // Delete User
    if (isset($_POST['delete_user'])) {
        $user_id = (int) $_POST['user_id'];

        // Check for issued books
        $issued = $pdo->prepare("SELECT COUNT(*) FROM issued_books WHERE user_id = ? AND status IN ('issued', 'overdue')");
        $issued->execute([$user_id]);

        if ($issued->fetchColumn() > 0) {
            setFlash('error', 'Cannot delete: User has books currently issued.');
        } else {
            $pdo->prepare("DELETE FROM users WHERE user_id = ? AND role != 'admin'")->execute([$user_id]);
            logActivity('user_delete', "Deleted user ID: {$user_id}");
            setFlash('success', 'User deleted successfully!');
        }
        redirect('admin/manage_students.php');
    }
}

// Pagination & Filters
$page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$perPage = 15;
$offset = ($page - 1) * $perPage;

$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$role_filter = isset($_GET['role']) ? sanitize($_GET['role']) : '';

$where = "1=1";
$params = [];

if ($search) {
    $where .= " AND (name LIKE ? OR email LIKE ?)";
    $params = ["%$search%", "%$search%"];
}
if ($role_filter && in_array($role_filter, ['student', 'teacher', 'admin'])) {
    $where .= " AND role = ?";
    $params[] = $role_filter;
}

$countStmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE $where");
$countStmt->execute($params);
$total = $countStmt->fetchColumn();
$totalPages = ceil($total / $perPage);

$stmt = $pdo->prepare("
    SELECT u.*,
           (SELECT COUNT(*) FROM issued_books WHERE user_id = u.user_id AND status IN ('issued', 'overdue')) as active_borrows
    FROM users u
    WHERE $where
    ORDER BY u.created_at DESC
    LIMIT $perPage OFFSET $offset
");
$stmt->execute($params);
$users = $stmt->fetchAll();

// Stats
$stats = $pdo->query("
    SELECT 
        COUNT(*) as total,
        SUM(role = 'student') as students,
        SUM(role = 'teacher') as teachers,
        SUM(role = 'admin') as admins,
        SUM(is_active = 0) as inactive
    FROM users
")->fetch();

$pageTitle = 'Manage Users';
require_once '../includes/header.php';
?>

<div class="dashboard-layout">
    <?php require_once '../includes/sidebar.php'; ?>

    <main class="main-content lg:mt-0 mt-16">
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white">Manage Users</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1"><?php echo number_format($total); ?> users registered</p>
            </div>

            <button onclick="openModal('addUserModal')" class="btn btn-primary">
                <i class="fa-solid fa-user-plus"></i> Add User
            </button>
        </header>

        <?php echo getFlash(); ?>

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
            <div class="stat-card text-center" data-aos="fade-up">
                <p class="text-2xl font-bold text-primary-600"><?php echo $stats['total']; ?></p>
                <p class="text-sm text-gray-500">Total</p>
            </div>
            <div class="stat-card text-center" data-aos="fade-up" data-aos-delay="50">
                <p class="text-2xl font-bold text-blue-600"><?php echo $stats['students']; ?></p>
                <p class="text-sm text-gray-500">Students</p>
            </div>
            <div class="stat-card text-center" data-aos="fade-up" data-aos-delay="100">
                <p class="text-2xl font-bold text-green-600"><?php echo $stats['teachers']; ?></p>
                <p class="text-sm text-gray-500">Teachers</p>
            </div>
            <div class="stat-card text-center" data-aos="fade-up" data-aos-delay="150">
                <p class="text-2xl font-bold text-purple-600"><?php echo $stats['admins']; ?></p>
                <p class="text-sm text-gray-500">Admins</p>
            </div>
            <div class="stat-card text-center" data-aos="fade-up" data-aos-delay="200">
                <p class="text-2xl font-bold text-gray-400"><?php echo $stats['inactive']; ?></p>
                <p class="text-sm text-gray-500">Inactive</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="stat-card mb-6" data-aos="fade-up">
            <form method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <div class="relative">
                        <input type="text" name="search" placeholder="Search by name or email..."
                            value="<?php echo e($search); ?>"
                            class="form-control pl-10 pr-20">
                        <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 btn btn-sm btn-secondary">
                            Search
                        </button>
                        <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>
                <div class="w-40">
                    <select name="role" class="form-control" onchange="this.form.submit()">
                        <option value="">All Roles</option>
                        <option value="student" <?php echo $role_filter === 'student' ? 'selected' : ''; ?>>Students</option>
                        <option value="admin" <?php echo $role_filter === 'admin' ? 'selected' : ''; ?>>Admins</option>
                    </select>
                </div>
                <!-- Filter button removed - Auto filtering enabled -->
                <?php if ($search || $role_filter): ?>
                    <a href="manage_students.php" class="btn btn-outline">Clear</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Users Table -->
        <div class="stat-card" data-aos="fade-up">
            <div class="data-table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Department</th>
                            <th>Active Borrows</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($users) > 0): ?>
                            <?php foreach ($users as $user): ?>
                                <tr class="<?php echo !$user['is_active'] ? 'opacity-60' : ''; ?>">
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <?php echo getAvatar($user['name'], 36); ?>
                                            <div>
                                                <p class="font-medium dark:text-white"><?php echo e($user['name']); ?></p>
                                                <p class="text-xs text-gray-500"><?php echo e($user['email']); ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo getRoleBadge($user['role']); ?></td>
                                    <td class="text-sm"><?php echo e($user['department'] ?: '-'); ?></td>
                                    <td>
                                        <?php if ($user['active_borrows'] > 0): ?>
                                            <span class="badge badge-warning"><?php echo $user['active_borrows']; ?> books</span>
                                        <?php else: ?>
                                            <span class="text-gray-400">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo $user['is_active'] ? 'badge-success' : 'badge-danger'; ?>">
                                            <?php echo $user['is_active'] ? 'Active' : 'Inactive'; ?>
                                        </span>
                                    </td>
                                    <td class="text-sm"><?php echo formatDate($user['created_at'], 'M j, Y'); ?></td>
                                    <td>
                                        <div class="flex gap-2">
                                            <form method="POST" class="inline" onsubmit="return confirmAction(event, 'Change this user\'s active status?', 'Confirm Status Change', 'primary')">
                                                <input type="hidden" name="toggle_status" value="1">
                                                <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                                <?php echo csrfInput(); ?>
                                                <button type="submit" class="btn btn-sm btn-outline" title="<?php echo $user['is_active'] ? 'Deactivate' : 'Activate'; ?>">
                                                    <i class="fa-solid <?php echo $user['is_active'] ? 'fa-ban' : 'fa-check'; ?>"></i>
                                                </button>
                                            </form>

                                            <?php if ($user['role'] !== 'admin'): ?>
                                                <form method="POST" class="inline" onsubmit="return confirmAction(event, 'Delete this user?')">
                                                    <input type="hidden" name="delete_user" value="1">
                                                    <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                                    <?php echo csrfInput(); ?>
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-12">
                                    <i class="fa-solid fa-users text-5xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-500">No users found</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="flex justify-center gap-2 mt-6">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&role=<?php echo $role_filter; ?>"
                            class="btn btn-outline btn-sm">Previous</a>
                    <?php endif; ?>

                    <span class="btn btn-sm bg-gray-100 dark:bg-slate-700 cursor-default">
                        Page <?php echo $page; ?> of <?php echo $totalPages; ?>
                    </span>

                    <?php if ($page < $totalPages): ?>
                        <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&role=<?php echo $role_filter; ?>"
                            class="btn btn-outline btn-sm">Next</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<!-- Add User Modal -->
<div class="modal-overlay" id="addUserModal">
    <div class="modal-content max-w-xl">
        <button class="modal-close" onclick="closeModal('addUserModal')">
            <i class="fa-solid fa-times"></i>
        </button>

        <h3 class="text-xl font-bold mb-4 dark:text-white">Add New User</h3>

        <form method="POST" class="space-y-4">
            <input type="hidden" name="add_user" value="1">
            <?php echo csrfInput(); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div>
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div>
                    <label class="form-label">Phone</label>
                    <input type="tel" name="phone" class="form-control">
                </div>

                <div>
                    <label class="form-label">Role *</label>
                    <select name="role" class="form-control" required>
                        <option value="student">Student</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div>
                    <label class="form-label">Department</label>
                    <select name="department" class="form-control">
                        <option value="">Select Department</option>
                        <option value="Physical Science">Physical Science</option>
                        <option value="Biology Science">Biology Science</option>
                        <option value="Business">Business</option>
                        <option value="Management">Management</option>
                        <option value="Hotel Management">Hotel Management</option>
                        <option value="Computer Science">Computer Science</option>
                    </select>
                </div>

                <div>
                    <label class="form-label">Class</label>
                    <select name="class" class="form-control">
                        <option value="">Select Class</option>
                        <option value="11">Class 11</option>
                        <option value="12">Class 12</option>
                    </select>
                </div>

                <div>
                    <label class="form-label">Password *</label>
                    <input type="password" name="password" class="form-control" minlength="6" required>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="btn btn-primary flex-1 justify-center">
                    <i class="fa-solid fa-user-plus mr-2"></i> Create User
                </button>
                <button type="button" onclick="closeModal('addUserModal')" class="btn btn-outline">Cancel</button>
            </div>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>