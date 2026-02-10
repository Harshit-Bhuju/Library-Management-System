<?php
require_once '../config/config.php';
require_once '../includes/functions.php';
requireLogin();

$user_id = $_SESSION['user_id'];

// Pagination
$page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$perPage = 15;
$offset = ($page - 1) * $perPage;

// Filter
$status_filter = isset($_GET['status']) ? sanitize($_GET['status']) : '';

$where = "i.user_id = ?";
$params = [$user_id];

if ($status_filter && in_array($status_filter, ['issued', 'overdue', 'returned'])) {
    $where .= " AND i.status = ?";
    $params[] = $status_filter;
}

// Get count
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM issued_books i WHERE $where");
$countStmt->execute($params);
$total = $countStmt->fetchColumn();
$totalPages = ceil($total / $perPage);

// Fetch history
$stmt = $pdo->prepare("
    SELECT i.*, b.title, b.author, b.cover_image
    FROM issued_books i
    JOIN books b ON i.book_id = b.book_id
    WHERE $where
    ORDER BY i.issue_date DESC
    LIMIT $perPage OFFSET $offset
");
$stmt->execute($params);
$history = $stmt->fetchAll();

// Stats
$stats = $pdo->prepare("
    SELECT 
        COUNT(*) as total,
        SUM(status IN ('issued', 'overdue')) as active,
        SUM(status = 'returned') as returned,
        COALESCE(SUM(CASE WHEN fine_paid = 0 THEN fine_amount ELSE 0 END), 0) as pending_fines
    FROM issued_books 
    WHERE user_id = ?
");
$stats->execute([$user_id]);
$stats = $stats->fetch();

$pageTitle = 'Borrow History';
require_once '../includes/header.php';
?>

<div class="dashboard-layout">
    <?php require_once '../includes/sidebar.php'; ?>

    <main class="main-content lg:mt-0 mt-16">
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white">Borrow History</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Your complete borrowing record</p>
            </div>
        </header>

        <?php echo getFlash(); ?>

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="stat-card text-center" data-aos="fade-up">
                <p class="text-2xl font-bold text-primary-600"><?php echo $stats['total']; ?></p>
                <p class="text-sm text-gray-500">Total Books</p>
            </div>
            <div class="stat-card text-center" data-aos="fade-up" data-aos-delay="100">
                <p class="text-2xl font-bold text-yellow-600"><?php echo $stats['active']; ?></p>
                <p class="text-sm text-gray-500">Currently Active</p>
            </div>
            <div class="stat-card text-center" data-aos="fade-up" data-aos-delay="150">
                <p class="text-2xl font-bold text-green-600"><?php echo $stats['returned']; ?></p>
                <p class="text-sm text-gray-500">Returned</p>
            </div>
            <div class="stat-card text-center" data-aos="fade-up" data-aos-delay="200">
                <p class="text-2xl font-bold <?php echo $stats['pending_fines'] > 0 ? 'text-red-600' : 'text-gray-400'; ?>"><?php echo CURRENCY_SYMBOL; ?> <?php echo number_format($stats['pending_fines'], 2); ?></p>
                <p class="text-sm text-gray-500">Pending Fines</p>
            </div>
        </div>

        <!-- Filter -->
        <div class="stat-card mb-6" data-aos="fade-up">
            <div class="flex gap-2">
                <a href="borrow_history.php" class="btn btn-sm <?php echo !$status_filter ? 'btn-primary' : 'btn-outline'; ?>">All</a>
                <a href="?status=issued" class="btn btn-sm <?php echo $status_filter === 'issued' ? 'btn-primary' : 'btn-outline'; ?>">Issued</a>
                <a href="?status=overdue" class="btn btn-sm <?php echo $status_filter === 'overdue' ? 'btn-primary' : 'btn-outline'; ?>">Overdue</a>
                <a href="?status=returned" class="btn btn-sm <?php echo $status_filter === 'returned' ? 'btn-primary' : 'btn-outline'; ?>">Returned</a>
            </div>
        </div>

        <!-- History Table -->
        <div class="stat-card" data-aos="fade-up">
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Book</th>
                            <th>Issue Date</th>
                            <th>Due Date</th>
                            <th>Return Date</th>
                            <th>Status</th>
                            <th>Fine</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($history) > 0): ?>
                            <?php foreach ($history as $item): ?>
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-14 bg-gray-200 dark:bg-slate-600 rounded flex items-center justify-center text-gray-400 flex-shrink-0 overflow-hidden">
                                                <?php if ($item['cover_image']): ?>
                                                    <img src="<?php echo BASE_URL . $item['cover_image']; ?>" class="w-full h-full object-cover" alt="">
                                                <?php else: ?>
                                                    <i class="fa-solid fa-book"></i>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <p class="font-medium dark:text-white"><?php echo e($item['title']); ?></p>
                                                <p class="text-xs text-gray-500"><?php echo e($item['author']); ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-sm"><?php echo formatDate($item['issue_date']); ?></td>
                                    <td class="text-sm <?php echo isOverdue($item['due_date']) && $item['status'] != 'returned' ? 'text-red-600 font-medium' : ''; ?>">
                                        <?php echo formatDate($item['due_date']); ?>
                                    </td>
                                    <td class="text-sm">
                                        <?php echo $item['return_date'] ? formatDate($item['return_date']) : '-'; ?>
                                    </td>
                                    <td><?php echo getStatusBadge($item['status']); ?></td>
                                    <td>
                                        <?php if ($item['fine_amount'] > 0): ?>
                                            <div class="flex items-center gap-2">
                                                <span class="<?php echo $item['fine_paid'] ? 'text-green-600' : 'text-red-600 font-medium'; ?>">
                                                    <?php echo CURRENCY_SYMBOL; ?> <?php echo number_format($item['fine_amount'], 2); ?>
                                                    <?php if ($item['fine_paid']): ?>
                                                        <i class="fa-solid fa-check ml-1" title="Paid"></i>
                                                    <?php endif; ?>
                                                </span>
                                                <?php if (!$item['fine_paid'] && $item['status'] !== 'returned'): ?>
                                                    <a href="pay_fine.php?issue_id=<?php echo $item['issue_id']; ?>"
                                                        class="btn btn-xs btn-primary">
                                                        <i class="fa-solid fa-wallet mr-1"></i> Pay
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-gray-400">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-12">
                                    <i class="fa-solid fa-clock-rotate-left text-5xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-500">No borrow history found</p>
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
                        <a href="?page=<?php echo $page - 1; ?>&status=<?php echo $status_filter; ?>" class="btn btn-outline btn-sm">Previous</a>
                    <?php endif; ?>

                    <span class="btn btn-sm bg-gray-100 dark:bg-slate-700 cursor-default">
                        Page <?php echo $page; ?> of <?php echo $totalPages; ?>
                    </span>

                    <?php if ($page < $totalPages): ?>
                        <a href="?page=<?php echo $page + 1; ?>&status=<?php echo $status_filter; ?>" class="btn btn-outline btn-sm">Next</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php require_once '../includes/footer.php'; ?>