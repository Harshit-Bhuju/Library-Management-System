<?php
require_once '../config/config.php';
require_once '../includes/functions.php';
requireAdmin();

// Date range filter
$start_date = isset($_GET['start']) ? sanitize($_GET['start']) : date('Y-m-01');
$end_date = isset($_GET['end']) ? sanitize($_GET['end']) : date('Y-m-d');

// Fetch detailed analytics

// Issues by category
$by_category = $pdo->prepare("
    SELECT c.category_name, COUNT(i.issue_id) as count
    FROM categories c
    LEFT JOIN books b ON c.category_id = b.category_id
    LEFT JOIN issued_books i ON b.book_id = i.book_id 
        AND i.issue_date BETWEEN ? AND ?
    GROUP BY c.category_id
    ORDER BY count DESC
");
$by_category->execute([$start_date, $end_date]);
$category_stats = $by_category->fetchAll();

// Issues by day
$daily_stats = $pdo->prepare("
    SELECT DATE(issue_date) as date, COUNT(*) as count
    FROM issued_books
    WHERE issue_date BETWEEN ? AND ?
    GROUP BY DATE(issue_date)
    ORDER BY date ASC
");
$daily_stats->execute([$start_date, $end_date]);
$daily_data = $daily_stats->fetchAll();

// Issues by role
$by_role = $pdo->prepare("
    SELECT u.role, COUNT(i.issue_id) as count
    FROM users u
    LEFT JOIN issued_books i ON u.user_id = i.user_id 
        AND i.issue_date BETWEEN ? AND ?
    GROUP BY u.role
");
$by_role->execute([$start_date, $end_date]);
$role_stats = $by_role->fetchAll();

// Top borrowers
$top_borrowers = $pdo->prepare("
    SELECT u.name, u.role, COUNT(i.issue_id) as count
    FROM users u
    JOIN issued_books i ON u.user_id = i.user_id
    WHERE i.issue_date BETWEEN ? AND ?
    GROUP BY u.user_id
    ORDER BY count DESC
    LIMIT 10
");
$top_borrowers->execute([$start_date, $end_date]);
$borrowers = $top_borrowers->fetchAll();

// Most popular books
$popular_books = $pdo->prepare("
    SELECT b.title, b.author, COUNT(i.issue_id) as count
    FROM books b
    JOIN issued_books i ON b.book_id = i.book_id
    WHERE i.issue_date BETWEEN ? AND ?
    GROUP BY b.book_id
    ORDER BY count DESC
    LIMIT 10
");
$popular_books->execute([$start_date, $end_date]);
$popular = $popular_books->fetchAll();

// Fine collection
$fines = $pdo->prepare("
    SELECT 
        COALESCE(SUM(fine_amount), 0) as total_fines,
        COALESCE(SUM(CASE WHEN fine_paid = 1 THEN fine_amount ELSE 0 END), 0) as collected,
        COALESCE(SUM(CASE WHEN fine_paid = 0 THEN fine_amount ELSE 0 END), 0) as pending
    FROM issued_books
    WHERE issue_date BETWEEN ? AND ?
");
$fines->execute([$start_date, $end_date]);
$fine_stats = $fines->fetch();

// Summary stats
$summary = $pdo->prepare("
    SELECT 
        COUNT(*) as total_issues,
        SUM(status = 'returned') as returned,
        SUM(status = 'overdue') as overdue
    FROM issued_books
    WHERE issue_date BETWEEN ? AND ?
");
$summary->execute([$start_date, $end_date]);
$summary_stats = $summary->fetch();

$pageTitle = 'Analytics';
require_once '../includes/header.php';
?>

<div class="dashboard-layout">
    <?php require_once '../includes/sidebar.php'; ?>

    <main class="main-content lg:mt-0 mt-16">
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white">Analytics</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Library performance insights</p>
            </div>
        </header>

        <!-- Date Filter -->
        <div class="stat-card mb-6" data-aos="fade-up">
            <form method="GET" class="flex flex-wrap items-end gap-4">
                <div>
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start" value="<?php echo $start_date; ?>" class="form-control">
                </div>
                <div>
                    <label class="form-label">End Date</label>
                    <input type="date" name="end" value="<?php echo $end_date; ?>" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-filter mr-2"></i> Apply
                </button>
                <a href="analytics.php" class="btn btn-outline">Reset</a>
            </form>
        </div>

        <!-- Summary Stats -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
            <div class="stat-card text-center" data-aos="fade-up">
                <p class="text-2xl font-bold text-primary-600"><?php echo $summary_stats['total_issues']; ?></p>
                <p class="text-sm text-gray-500">Total Issues</p>
            </div>
            <div class="stat-card text-center" data-aos="fade-up" data-aos-delay="50">
                <p class="text-2xl font-bold text-green-600"><?php echo $summary_stats['returned']; ?></p>
                <p class="text-sm text-gray-500">Returned</p>
            </div>
            <div class="stat-card text-center" data-aos="fade-up" data-aos-delay="100">
                <p class="text-2xl font-bold text-red-600"><?php echo $summary_stats['overdue']; ?></p>
                <p class="text-sm text-gray-500">Overdue</p>
            </div>
            <div class="stat-card text-center" data-aos="fade-up" data-aos-delay="150">
                <p class="text-2xl font-bold text-green-600">$<?php echo number_format($fine_stats['collected'], 2); ?></p>
                <p class="text-sm text-gray-500">Fines Collected</p>
            </div>
            <div class="stat-card text-center" data-aos="fade-up" data-aos-delay="200">
                <p class="text-2xl font-bold text-yellow-600">$<?php echo number_format($fine_stats['pending'], 2); ?></p>
                <p class="text-sm text-gray-500">Fines Pending</p>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Daily Activity -->
            <div class="stat-card" data-aos="fade-up">
                <h3 class="text-lg font-semibold mb-4 dark:text-white">Daily Activity</h3>
                <canvas id="dailyChart" height="250"></canvas>
            </div>

            <!-- By Category -->
            <div class="stat-card" data-aos="fade-up" data-aos-delay="100">
                <h3 class="text-lg font-semibold mb-4 dark:text-white">Issues by Category</h3>
                <canvas id="categoryChart" height="250"></canvas>
            </div>
        </div>

        <!-- Tables Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Top Borrowers -->
            <div class="stat-card" data-aos="fade-up">
                <h3 class="text-lg font-semibold mb-4 dark:text-white">
                    <i class="fa-solid fa-trophy text-yellow-500 mr-2"></i>
                    Top Borrowers
                </h3>
                <?php if (count($borrowers) > 0): ?>
                    <div class="space-y-3">
                        <?php foreach ($borrowers as $index => $borrower): ?>
                            <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700">
                                <div class="w-8 h-8 <?php
                                                    echo $index < 3 ? 'bg-yellow-100 text-yellow-600' : 'bg-gray-100 text-gray-600 dark:bg-slate-600 dark:text-gray-400';
                                                    ?> rounded-full flex items-center justify-center font-bold text-sm">
                                    <?php echo $index + 1; ?>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-sm dark:text-white"><?php echo e($borrower['name']); ?></p>
                                    <p class="text-xs text-gray-500"><?php echo ucfirst($borrower['role']); ?></p>
                                </div>
                                <span class="badge badge-primary"><?php echo $borrower['count']; ?> books</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-center text-gray-500 py-8">No data for this period</p>
                <?php endif; ?>
            </div>

            <!-- Popular Books -->
            <div class="stat-card" data-aos="fade-up" data-aos-delay="100">
                <h3 class="text-lg font-semibold mb-4 dark:text-white">
                    <i class="fa-solid fa-fire text-orange-500 mr-2"></i>
                    Most Popular Books
                </h3>
                <?php if (count($popular) > 0): ?>
                    <div class="space-y-3">
                        <?php foreach ($popular as $index => $book): ?>
                            <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700">
                                <div class="w-8 h-8 <?php
                                                    echo $index < 3 ? 'bg-orange-100 text-orange-600' : 'bg-gray-100 text-gray-600 dark:bg-slate-600 dark:text-gray-400';
                                                    ?> rounded-full flex items-center justify-center font-bold text-sm">
                                    <?php echo $index + 1; ?>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-sm truncate dark:text-white"><?php echo e($book['title']); ?></p>
                                    <p class="text-xs text-gray-500 truncate"><?php echo e($book['author']); ?></p>
                                </div>
                                <span class="badge badge-info"><?php echo $book['count']; ?> issues</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-center text-gray-500 py-8">No data for this period</p>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<script>
    // Daily Activity Chart
    const dailyData = <?php echo json_encode($daily_data); ?>;
    new Chart(document.getElementById('dailyChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: dailyData.map(d => d.date),
            datasets: [{
                label: 'Issues',
                data: dailyData.map(d => d.count),
                backgroundColor: 'rgba(99, 102, 241, 0.7)',
                borderColor: '#6366f1',
                borderWidth: 1,
                borderRadius: 4
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
                        color: 'rgba(0,0,0,0.05)'
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
    const categoryData = <?php echo json_encode($category_stats); ?>;
    const colors = ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#14b8a6', '#f97316'];
    new Chart(document.getElementById('categoryChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: categoryData.map(c => c.category_name),
            datasets: [{
                data: categoryData.map(c => c.count),
                backgroundColor: colors.slice(0, categoryData.length),
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
                        boxWidth: 12,
                        padding: 12
                    }
                }
            }
        }
    });
</script>

<?php require_once '../includes/footer.php'; ?>