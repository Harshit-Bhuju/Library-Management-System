<?php
require_once '../config/config.php';
require_once '../includes/functions.php';
requireAdmin();

// Fetch Stats
$total_books = $pdo->query("SELECT COUNT(*) FROM books")->fetchColumn();
$total_students = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'student'")->fetchColumn();
$total_teachers = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'teacher'")->fetchColumn();
$issued_books = $pdo->query("SELECT COUNT(*) FROM issued_books WHERE status IN ('issued', 'overdue')")->fetchColumn();
$overdue_books = $pdo->query("SELECT COUNT(*) FROM issued_books WHERE status = 'overdue'")->fetchColumn();
$total_returned = $pdo->query("SELECT COUNT(*) FROM issued_books WHERE status = 'returned'")->fetchColumn();
$total_fines = $pdo->query("SELECT COALESCE(SUM(fine_amount), 0) FROM issued_books WHERE fine_paid = 0")->fetchColumn();

// Update overdue status for books past due date
$pdo->query("UPDATE issued_books SET status = 'overdue' WHERE status = 'issued' AND due_date < CURDATE()");

// Recent Activities (Latest 10 Issues)
$stmt = $pdo->query("
    SELECT i.*, b.title, u.name as student_name, u.role as user_role
    FROM issued_books i 
    JOIN books b ON i.book_id = b.book_id 
    JOIN users u ON i.user_id = u.user_id 
    ORDER BY i.issue_id DESC LIMIT 10
");
$recent_issues = $stmt->fetchAll();

// Popular Books (Most Borrowed)
$popular_books = $pdo->query("
    SELECT b.title, b.author, COUNT(i.issue_id) as borrow_count
    FROM books b
    LEFT JOIN issued_books i ON b.book_id = i.book_id
    GROUP BY b.book_id
    ORDER BY borrow_count DESC
    LIMIT 5
")->fetchAll();

// Monthly Stats for Chart
$monthly_stats = $pdo->query("
    SELECT 
        DATE_FORMAT(issue_date, '%b') as month,
        COUNT(*) as issues
    FROM issued_books 
    WHERE issue_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    GROUP BY YEAR(issue_date), MONTH(issue_date)
    ORDER BY issue_date ASC
")->fetchAll();

$pageTitle = 'Admin Dashboard';
require_once '../includes/header.php';
?>

<div class="dashboard-layout">
    <?php require_once '../includes/sidebar.php'; ?>

    <main class="main-content lg:mt-0 mt-16">
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white">Dashboard</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Welcome back, <?php echo e($_SESSION['name']); ?>!</p>
            </div>

            <div class="flex items-center gap-3">
                <button onclick="ThemeManager.toggle()" class="hidden lg:flex btn btn-outline">
                    <i class="fa-solid fa-moon dark:hidden"></i>
                    <i class="fa-solid fa-sun hidden dark:inline"></i>
                </button>

                <a href="issue_book.php" class="btn btn-primary">
                    <i class="fa-solid fa-plus"></i>
                    <span class="hidden sm:inline">Quick Issue</span>
                </a>
            </div>
        </header>

        <?php echo getFlash(); ?>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
            <div class="stat-card" data-aos="fade-up" data-aos-delay="100">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Total Books</p>
                        <h2 class="text-2xl md:text-3xl font-bold mt-1" data-counter="<?php echo $total_books; ?>"><?php echo $total_books; ?></h2>
                        <p class="text-xs text-gray-400 mt-1">In library</p>
                    </div>
                    <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center text-primary-600 dark:text-primary-400">
                        <i class="fa-solid fa-book text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card" data-aos="fade-up" data-aos-delay="150">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Issued Books</p>
                        <h2 class="text-2xl md:text-3xl font-bold mt-1 text-yellow-600" data-counter="<?php echo $issued_books; ?>"><?php echo $issued_books; ?></h2>
                        <?php if ($overdue_books > 0): ?>
                            <p class="text-xs text-red-500 mt-1"><i class="fa-solid fa-exclamation-triangle"></i> <?php echo $overdue_books; ?> overdue</p>
                        <?php else: ?>
                            <p class="text-xs text-gray-400 mt-1">Currently out</p>
                        <?php endif; ?>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-xl flex items-center justify-center text-yellow-600 dark:text-yellow-400">
                        <i class="fa-solid fa-hand-holding-hand text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card" data-aos="fade-up" data-aos-delay="200">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Students</p>
                        <h2 class="text-2xl md:text-3xl font-bold mt-1 text-green-600" data-counter="<?php echo $total_students; ?>"><?php echo $total_students; ?></h2>
                        <p class="text-xs text-gray-400 mt-1"><?php echo $total_teachers; ?> teachers</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center text-green-600 dark:text-green-400">
                        <i class="fa-solid fa-users text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card" data-aos="fade-up" data-aos-delay="250">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Pending Fines</p>
                        <h2 class="text-2xl md:text-3xl font-bold mt-1 text-red-600">$<?php echo number_format($total_fines, 2); ?></h2>
                        <p class="text-xs text-gray-400 mt-1">To collect</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-xl flex items-center justify-center text-red-600 dark:text-red-400">
                        <i class="fa-solid fa-dollar-sign text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mb-8" data-aos="fade-up">
            <h3 class="text-lg font-semibold mb-4 dark:text-white">Quick Actions</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="issue_book.php" class="stat-card hover:border-primary-500 transition-colors text-center py-6">
                    <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center text-primary-600 mx-auto mb-3">
                        <i class="fa-solid fa-hand-holding-hand text-xl"></i>
                    </div>
                    <p class="font-medium dark:text-white">Issue Book</p>
                </a>

                <a href="return_book.php" class="stat-card hover:border-green-500 transition-colors text-center py-6">
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center text-green-600 mx-auto mb-3">
                        <i class="fa-solid fa-rotate-left text-xl"></i>
                    </div>
                    <p class="font-medium dark:text-white">Return Book</p>
                </a>

                <a href="manage_books.php" class="stat-card hover:border-blue-500 transition-colors text-center py-6">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center text-blue-600 mx-auto mb-3">
                        <i class="fa-solid fa-plus text-xl"></i>
                    </div>
                    <p class="font-medium dark:text-white">Add Book</p>
                </a>

                <a href="manage_students.php" class="stat-card hover:border-purple-500 transition-colors text-center py-6">
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center text-purple-600 mx-auto mb-3">
                        <i class="fa-solid fa-user-plus text-xl"></i>
                    </div>
                    <p class="font-medium dark:text-white">Add User</p>
                </a>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Monthly Activity Chart -->
            <div class="stat-card" data-aos="fade-up">
                <h3 class="text-lg font-semibold mb-4 dark:text-white">Monthly Activity</h3>
                <canvas id="monthlyChart" height="200"></canvas>
            </div>

            <!-- Popular Books -->
            <div class="stat-card" data-aos="fade-up" data-aos-delay="100">
                <h3 class="text-lg font-semibold mb-4 dark:text-white">Popular Books</h3>
                <?php if (count($popular_books) > 0): ?>
                    <div class="space-y-3">
                        <?php foreach ($popular_books as $index => $book): ?>
                            <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                                <div class="w-8 h-8 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center text-primary-600 font-bold text-sm">
                                    <?php echo $index + 1; ?>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-sm truncate dark:text-white"><?php echo e($book['title']); ?></p>
                                    <p class="text-xs text-gray-500"><?php echo e($book['author']); ?></p>
                                </div>
                                <span class="badge badge-primary"><?php echo $book['borrow_count']; ?> borrows</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500 text-center py-4">No data available yet</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recent Activity Table -->
        <div class="stat-card" data-aos="fade-up">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold dark:text-white">Recent Activity</h3>
                <a href="analytics.php" class="text-primary-600 hover:text-primary-700 text-sm font-medium">View All →</a>
            </div>

            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Book</th>
                            <th>User</th>
                            <th>Issued</th>
                            <th>Due</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($recent_issues) > 0): ?>
                            <?php foreach ($recent_issues as $issue): ?>
                                <tr>
                                    <td>
                                        <div class="font-medium dark:text-white"><?php echo e($issue['title']); ?></div>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <?php echo getAvatar($issue['student_name'], 28); ?>
                                            <div>
                                                <p class="text-sm dark:text-white"><?php echo e($issue['student_name']); ?></p>
                                                <p class="text-xs text-gray-400"><?php echo ucfirst($issue['user_role']); ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-sm"><?php echo formatDate($issue['issue_date'], 'M j'); ?></td>
                                    <td>
                                        <span class="text-sm <?php echo isOverdue($issue['due_date']) && $issue['status'] != 'returned' ? 'text-red-600 font-medium' : ''; ?>">
                                            <?php echo formatDate($issue['due_date'], 'M j'); ?>
                                        </span>
                                    </td>
                                    <td><?php echo getStatusBadge($issue['status']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-8 text-gray-500">
                                    <i class="fa-solid fa-inbox text-4xl mb-2 opacity-50"></i>
                                    <p>No recent activity</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script>
    // Monthly Chart
    const ctx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyData = <?php echo json_encode($monthly_stats); ?>;

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthlyData.map(item => item.month),
            datasets: [{
                label: 'Books Issued',
                data: monthlyData.map(item => item.issues),
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#6366f1',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>

<?php require_once '../includes/footer.php'; ?>