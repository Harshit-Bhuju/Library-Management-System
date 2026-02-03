<?php
require_once '../config/config.php';
require_once '../includes/functions.php';
requireAdmin();

// Fetch Stats
$total_books = $pdo->query("SELECT COUNT(*) FROM books")->fetchColumn();
$total_students = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'student'")->fetchColumn();
$issued_books = $pdo->query("SELECT COUNT(*) FROM issued_books WHERE status IN ('issued', 'overdue')")->fetchColumn();
$overdue_books = $pdo->query("SELECT COUNT(*) FROM issued_books WHERE status = 'overdue'")->fetchColumn();
$total_returned = $pdo->query("SELECT COUNT(*) FROM issued_books WHERE status = 'returned'")->fetchColumn();
$total_fines = $pdo->query("SELECT COALESCE(SUM(fine_amount), 0) FROM issued_books WHERE fine_paid = 0")->fetchColumn();

// Update overdue status
$pdo->query("UPDATE issued_books SET status = 'overdue' WHERE status = 'issued' AND due_date < CURDATE()");

// Recent Activities
$stmt = $pdo->query("
    SELECT i.*, b.title, u.name as student_name, u.role as user_role
    FROM issued_books i 
    JOIN books b ON i.book_id = b.book_id 
    JOIN users u ON i.user_id = u.user_id 
    WHERE i.status != 'requested'
    ORDER BY i.issue_id DESC LIMIT 10
");
$recent_issues = $stmt->fetchAll();

// Popular Books
$popular_books = $pdo->query("
    SELECT b.title, b.author, COUNT(i.issue_id) as borrow_count
    FROM books b
    LEFT JOIN issued_books i ON b.book_id = i.book_id
    GROUP BY b.book_id
    ORDER BY borrow_count DESC
    LIMIT 5
")->fetchAll();

// Monthly Stats
$monthly_stats = $pdo->query("
    SELECT 
        DATE_FORMAT(issue_date, '%b') as month,
        COUNT(*) as issues
    FROM issued_books 
    WHERE issue_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    GROUP BY YEAR(issue_date), MONTH(issue_date)
    ORDER BY issue_date ASC
")->fetchAll();

// Category Stats
$category_stats = $pdo->query("
    SELECT c.category_name, COUNT(i.issue_id) as count
    FROM issued_books i
    JOIN books b ON i.book_id = b.book_id
    JOIN categories c ON b.category_id = c.category_id
    GROUP BY c.category_id
    LIMIT 5
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
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="stat-card p-5" data-aos="fade-up">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Books</p>
                        <h2 class="text-3xl font-bold mt-2 dark:text-white"><?php echo $total_books; ?></h2>
                        <a href="manage_books.php" class="text-xs text-primary-600 hover:underline mt-1 inline-block">View All</a>
                    </div>
                    <div class="bg-primary-50 dark:bg-primary-900/20 p-3 rounded-lg text-primary-600">
                        <i class="fa-solid fa-book text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card p-5" data-aos="fade-up" data-aos-delay="50">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Issued Books</p>
                        <h2 class="text-3xl font-bold mt-2 text-yellow-600"><?php echo $issued_books; ?></h2>
                        <p class="text-xs text-red-500 mt-1"><?php echo $overdue_books; ?> Overdue</p>
                    </div>
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 p-3 rounded-lg text-yellow-600">
                        <i class="fa-solid fa-hand-holding-hand text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card p-5" data-aos="fade-up" data-aos-delay="100">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Returned</p>
                        <h2 class="text-3xl font-bold mt-2 text-green-600"><?php echo $total_returned; ?></h2>
                        <a href="return_book.php" class="text-xs text-green-600 hover:underline mt-1 inline-block">Process Return</a>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900/20 p-3 rounded-lg text-green-600">
                        <i class="fa-solid fa-check-circle text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card p-5" data-aos="fade-up" data-aos-delay="150">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Students</p>
                        <h2 class="text-3xl font-bold mt-2 dark:text-white"><?php echo $total_students; ?></h2>
                        <p class="text-xs text-gray-400 mt-1">Active Users</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-800 p-3 rounded-lg text-gray-600 dark:text-gray-400">
                        <i class="fa-solid fa-users text-xl"></i>
                    </div>
                </div>
            </div>

            <?php
            // New Requests Count
            $pending_requests = $pdo->query("SELECT COUNT(*) FROM issued_books WHERE status = 'requested'")->fetchColumn();
            ?>
            <a href="manage_requests.php" class="stat-card p-5 hover:border-blue-500 transition-colors pointer-events-auto" data-aos="fade-up" data-aos-delay="200">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Requests</p>
                        <h2 class="text-3xl font-bold mt-2 text-blue-600"><?php echo $pending_requests; ?></h2>
                        <p class="text-xs text-blue-500 mt-1">Pending Approval</p>
                    </div>
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg text-blue-600">
                        <i class="fa-solid fa-clipboard-question text-xl"></i>
                    </div>
                </div>
            </a>

            <div class="stat-card p-5" data-aos="fade-up" data-aos-delay="250">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Unpaid Fines</p>
                        <h2 class="text-xl font-bold mt-2 text-red-600">NPR <?php echo number_format($total_fines, 2); ?></h2>
                        <p class="text-xs text-gray-400 mt-1">Total Outstanding</p>
                    </div>
                    <div class="bg-red-50 dark:bg-red-900/20 p-3 rounded-lg text-red-600">
                        <i class="fa-solid fa-coins text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Monthly Activity Chart -->
            <div class="stat-card lg:col-span-2" data-aos="fade-up">
                <h3 class="text-lg font-semibold mb-4 dark:text-white">Monthly Activity</h3>
                <div class="h-64">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>

            <!-- Issues by Category -->
            <div class="stat-card" data-aos="fade-up" data-aos-delay="100">
                <h3 class="text-lg font-semibold mb-4 dark:text-white">Issues by Category</h3>
                <div class="h-64 flex items-center justify-center">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Activity Table -->
        <div class="stat-card" data-aos="fade-up">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold dark:text-white">Recent Activity</h3>
                <a href="issue_book.php" class="text-primary-600 hover:text-primary-700 text-sm font-medium">View All →</a>
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

    // Category Chart
    const ctxCat = document.getElementById('categoryChart').getContext('2d');
    const catData = <?php echo json_encode($category_stats); ?>;

    new Chart(ctxCat, {
        type: 'doughnut',
        data: {
            labels: catData.map(item => item.category_name),
            datasets: [{
                data: catData.map(item => item.count),
                backgroundColor: [
                    '#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        usePointStyle: true,
                        boxWidth: 8
                    }
                }
            }
        }
    });
</script>

<?php require_once '../includes/footer.php'; ?>