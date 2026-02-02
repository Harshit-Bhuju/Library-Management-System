<?php
require_once '../config/config.php';
require_once '../includes/functions.php';
requireAdmin();

// Actions
if (isset($_GET['deactivate'])) {
    $id = (int)$_GET['deactivate'];
    $pdo->prepare("UPDATE users SET is_active = 0 WHERE user_id = ?")->execute([$id]);
    setFlash('success', 'Student deactivated');
    redirect('admin/manage_students.php');
}
if (isset($_GET['activate'])) {
    $id = (int)$_GET['activate'];
    $pdo->prepare("UPDATE users SET is_active = 1 WHERE user_id = ?")->execute([$id]);
    setFlash('success', 'Student activated');
    redirect('admin/manage_students.php');
}

// Fetch Students
$students = $pdo->query("SELECT * FROM users WHERE role = 'student' ORDER BY created_at DESC")->fetchAll();

$pageTitle = 'Manage Students';
require_once '../includes/header.php';
?>

<div class="dashboard-layout">
    <?php require_once '../includes/sidebar.php'; ?>
    
    <main class="main-content">
        <header class="flex justify-between items-center" style="margin-bottom: 2rem;">
            <h1>Manage Students</h1>
        </header>

        <?php echo getFlash(); ?>

        <div class="card" style="background: var(--card-bg); padding: 1.5rem; border-radius: 1rem; border: 1px solid var(--border-color);">
             <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Roll No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Dept / Class</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($students as $student): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($student['roll_no']); ?></td>
                            <td><?php echo htmlspecialchars($student['name']); ?></td>
                            <td><?php echo htmlspecialchars($student['email']); ?></td>
                            <td><?php echo htmlspecialchars($student['department'] ?? '-'); ?> / Class <?php echo htmlspecialchars($student['year'] ?? '-'); ?></td>
                            <td>
                                <?php if($student['is_active']): ?>
                                    <span style="color: var(--success); font-weight: 600;">Active</span>
                                <?php else: ?>
                                    <span style="color: var(--danger); font-weight: 600;">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($student['is_active']): ?>
                                    <a href="?deactivate=<?php echo $student['user_id']; ?>" class="btn btn-outline" style="padding: 0.25rem 0.5rem; color: var(--danger); border-color: var(--danger);" onclick="return confirm('Deactivate student?');">
                                        <i class="fa-solid fa-ban"></i>
                                    </a>
                                <?php else: ?>
                                    <a href="?activate=<?php echo $student['user_id']; ?>" class="btn btn-outline" style="padding: 0.25rem 0.5rem; color: var(--success); border-color: var(--success);">
                                        <i class="fa-solid fa-check"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
             </div>
        </div>
    </main>
</div>
<?php require_once '../includes/footer.php'; ?>
