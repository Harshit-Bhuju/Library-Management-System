<?php
require_once '../config/config.php';
require_once '../includes/functions.php';
requireLogin();

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$pageTitle = 'My Profile';
require_once '../includes/header.php';
?>

<div class="dashboard-layout">
    <?php require_once '../includes/sidebar.php'; ?>
    <main class="main-content">
        <h1>My Profile</h1>
        
        <div class="card" style="background: var(--card-bg); padding: 2rem; border-radius: 1rem; margin-top: 1.5rem; max-width: 600px;">
            <div class="flex items-center gap-4 mb-6">
                <div style="width: 80px; height: 80px; background: var(--primary-color); border-radius: 50%; color: white; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 700;">
                    <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                </div>
                <div>
                    <h2 style="margin: 0;"><?php echo htmlspecialchars($user['name']); ?></h2>
                    <p style="color: var(--text-muted);"><?php echo htmlspecialchars($user['role']); ?></p>
                </div>
            </div>
            
            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div>
                    <label class="form-label">Roll No</label>
                    <div class="form-control" readonly><?php echo htmlspecialchars($user['roll_no']); ?></div>
                </div>
                <div>
                    <label class="form-label">Email</label>
                    <div class="form-control" readonly><?php echo htmlspecialchars($user['email']); ?></div>
                </div>
                <div>
                    <label class="form-label">Department</label>
                    <div class="form-control" readonly><?php echo htmlspecialchars($user['department']); ?></div>
                </div>
                <div>
                    <label class="form-label">Year</label>
                    <div class="form-control" readonly><?php echo htmlspecialchars($user['year']); ?></div>
                </div>
            </div>
            
            <div style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                <p style="color: var(--text-muted); font-size: 0.9rem;">Member since: <?php echo formatDate($user['created_at']); ?></p>
            </div>
        </div>
    </main>
</div>
<?php require_once '../includes/footer.php'; ?>
