<?php
require_once '../config/config.php';
require_once '../includes/functions.php';
requireAdmin();

// Fetch Stats
$total_books = $pdo->query("SELECT COUNT(*) FROM books")->fetchColumn();
$total_students = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'student'")->fetchColumn();
$issued_books = $pdo->query("SELECT COUNT(*) FROM issued_books WHERE status = 'issued' OR status = 'overdue'")->fetchColumn();
$total_returned = $pdo->query("SELECT COUNT(*) FROM issued_books WHERE status = 'returned'")->fetchColumn();

// Recent Activities (Latest 5 Issues)
$stmt = $pdo->query("
    SELECT i.*, b.title, u.name as student_name 
    FROM issued_books i 
    JOIN books b ON i.book_id = b.book_id 
    JOIN users u ON i.user_id = u.user_id 
    ORDER BY i.issue_date DESC LIMIT 5
");
$recent_issues = $stmt->fetchAll();

$pageTitle = 'Admin Dashboard';
require_once '../includes/header.php';
?>

<div class="dashboard-layout">
    <?php require_once '../includes/sidebar.php'; ?>
    
    <main class="main-content">
        <header class="flex justify-between items-center" style="margin-bottom: 2rem;">
            <div>
                <h1 style="font-size: 1.8rem; font-weight: 700;">Dashboard</h1>
                <p style="color: var(--text-muted);">Welcome back, Admin</p>
            </div>
            
            <div class="flex gap-4">
                <button class="btn btn-outline">
                    <i class="fa-solid fa-bell"></i>
                </button>
                <div class="user-profile flex items-center gap-2">
                    <div style="width: 40px; height: 40px; background: var(--primary-color); border-radius: 50%; color: white; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                        A
                    </div>
                    <span>Admin</span>
                </div>
            </div>
        </header>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card" data-aos="fade-up" data-aos-delay="100">
                <div class="flex justify-between items-center">
                    <div>
                        <p style="color: var(--text-muted); font-size: 0.9rem;">Total Books</p>
                        <h2 style="font-size: 2rem; font-weight: 700; margin: 0.5rem 0;"><?php echo $total_books; ?></h2>
                    </div>
                    <div style="width: 50px; height: 50px; background: rgba(99, 102, 241, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--primary-color); font-size: 1.5rem;">
                        <i class="fa-solid fa-book"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card" data-aos="fade-up" data-aos-delay="200">
                <div class="flex justify-between items-center">
                    <div>
                        <p style="color: var(--text-muted); font-size: 0.9rem;">Issued Books</p>
                        <h2 style="font-size: 2rem; font-weight: 700; margin: 0.5rem 0;"><?php echo $issued_books; ?></h2>
                    </div>
                    <div style="width: 50px; height: 50px; background: rgba(245, 158, 11, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--warning); font-size: 1.5rem;">
                        <i class="fa-solid fa-hand-holding-hand"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card" data-aos="fade-up" data-aos-delay="300">
                <div class="flex justify-between items-center">
                    <div>
                        <p style="color: var(--text-muted); font-size: 0.9rem;">Students</p>
                        <h2 style="font-size: 2rem; font-weight: 700; margin: 0.5rem 0;"><?php echo $total_students; ?></h2>
                    </div>
                    <div style="width: 50px; height: 50px; background: rgba(16, 185, 129, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--success); font-size: 1.5rem;">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
            </div>

             <div class="stat-card" data-aos="fade-up" data-aos-delay="400">
                <div class="flex justify-between items-center">
                    <div>
                        <p style="color: var(--text-muted); font-size: 0.9rem;">Returned</p>
                        <h2 style="font-size: 2rem; font-weight: 700; margin: 0.5rem 0;"><?php echo $total_returned; ?></h2>
                    </div>
                    <div style="width: 50px; height: 50px; background: rgba(59, 130, 246, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #3b82f6; font-size: 1.5rem;">
                        <i class="fa-solid fa-check-double"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <h3 style="margin-bottom: 1rem; font-size: 1.25rem;">Recent Issues</h3>
        <div style="overflow-x: auto;">
            <table class="data-table" data-aos="fade-up">
                <thead>
                    <tr>
                        <th>Book Title</th>
                        <th>Student</th>
                        <th>Date Issued</th>
                        <th>Due Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($recent_issues) > 0): ?>
                        <?php foreach($recent_issues as $issue): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($issue['title']); ?></td>
                            <td><?php echo htmlspecialchars($issue['student_name']); ?></td>
                            <td><?php echo formatDate($issue['issue_date']); ?></td>
                            <td><?php echo formatDate($issue['due_date']); ?></td>
                            <td>
                                <span style="
                                    padding: 0.25rem 0.5rem; 
                                    border-radius: 9999px; 
                                    font-size: 0.75rem; 
                                    font-weight: 500;
                                    background: <?php echo ($issue['status'] == 'issued') ? 'rgba(245, 158, 11, 0.1)' : (($issue['status'] == 'overdue') ? 'rgba(239, 68, 68, 0.1)' : 'rgba(16, 185, 129, 0.1)'); ?>;
                                    color: <?php echo ($issue['status'] == 'issued') ? 'var(--warning)' : (($issue['status'] == 'overdue') ? 'var(--danger)' : 'var(--success)'); ?>;
                                ">
                                    <?php echo ucfirst($issue['status']); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--text-muted);">No recent activity</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </main>
</div>

<?php require_once '../includes/footer.php'; ?>
