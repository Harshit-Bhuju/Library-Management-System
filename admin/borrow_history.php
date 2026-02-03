<?php
require_once '../config/config.php';
require_once '../includes/functions.php';
requireAdmin();

// Pagination
$page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$perPage = 20;
$offset = ($page - 1) * $perPage;

// Filters
$status_filter = isset($_GET['status']) ? sanitize($_GET['status']) : '';
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';

$where = "1=1";
$params = [];

if ($status_filter) {
    if ($status_filter === 'active') {
        $where .= " AND i.status IN ('issued', 'overdue')";
    } else {
        $where .= " AND i.status = ?";
        $params[] = $status_filter;
    }
}

if ($search) {
    $where .= " AND (u.name LIKE ? OR u.email LIKE ? OR b.title LIKE ? OR b.isbn LIKE ?)";
    $searchParam = "%$search%";
    $params = array_merge($params, [$searchParam, $searchParam, $searchParam, $searchParam]);
}

// Get total count
$countStmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM issued_books i
    JOIN users u ON i.user_id = u.user_id
    JOIN books b ON i.book_id = b.book_id
    WHERE $where
");
$countStmt->execute($params);
$total = $countStmt->fetchColumn();
$totalPages = ceil($total / $perPage);

// Fetch records
$stmt = $pdo->prepare("
    SELECT i.*, u.name as user_name, u.role as user_role, b.title as book_title, b.cover_image
    FROM issued_books i
    JOIN users u ON i.user_id = u.user_id
    JOIN books b ON i.book_id = b.book_id
    WHERE $where
    ORDER BY i.issue_date DESC, i.issue_id DESC
    LIMIT $perPage OFFSET $offset
");
$stmt->execute($params);
$records = $stmt->fetchAll();

$pageTitle = 'Borrow History Tracking';
require_once '../includes/header.php';
?>

<div class="dashboard-layout">
    <?php require_once '../includes/sidebar.php'; ?>

    <main class="main-content lg:mt-0 mt-16">
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white">Borrow History</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Complete record of all issued and returned books</p>
            </div>
        </header>

        <?php echo getFlash(); ?>

        <!-- Filters -->
        <div class="stat-card mb-6" data-aos="fade-up">
            <form method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[250px]">
                    <div class="relative">
                        <input type="text" name="search" placeholder="Search by student, book, ISBN..."
                            value="<?php echo e($search); ?>" class="form-control pl-10">
                        <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>
                <div class="w-48">
                    <select name="status" class="form-control" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        <option value="active" <?php echo $status_filter === 'active' ? 'selected' : ''; ?>>Active (Issued/Overdue)</option>
                        <option value="issued" <?php echo $status_filter === 'issued' ? 'selected' : ''; ?>>Issued</option>
                        <option value="overdue" <?php echo $status_filter === 'overdue' ? 'selected' : ''; ?>>Overdue</option>
                        <option value="returned" <?php echo $status_filter === 'returned' ? 'selected' : ''; ?>>Returned</option>
                        <option value="cancelled" <?php echo $status_filter === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                </div>
                <?php if ($search || $status_filter): ?>
                    <a href="borrow_history.php" class="btn btn-outline">Reset</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- History Table -->
        <div class="stat-card" data-aos="fade-up">
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Book</th>
                            <th>Student</th>
                            <th>Dates</th>
                            <th>Status</th>
                            <th>Fine</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($records) > 0): ?>
                            <?php foreach ($records as $record): ?>
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-14 bg-gray-200 dark:bg-slate-600 rounded flex items-center justify-center text-gray-400 flex-shrink-0 overflow-hidden">
                                                <?php if ($record['cover_image']): ?>
                                                    <img src="<?php echo BASE_URL . $record['cover_image']; ?>" class="w-full h-full object-cover" alt="">
                                                <?php else: ?>
                                                    <i class="fa-solid fa-book"></i>
                                                <?php endif; ?>
                                            </div>
                                            <p class="font-medium text-sm line-clamp-2 dark:text-white"><?php echo e($record['book_title']); ?></p>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="font-medium text-sm dark:text-white"><?php echo e($record['user_name']); ?></p>
                                        <p class="text-xs text-gray-500 uppercase"><?php echo e($record['user_role']); ?></p>
                                    </td>
                                    <td class="text-xs">
                                        <div class="space-y-1">
                                            <p><span class="text-gray-400">Issued:</span> <?php echo formatDate($record['issue_date']); ?></p>
                                            <p><span class="text-gray-400">Due:</span> <?php echo formatDate($record['due_date']); ?></p>
                                            <?php if ($record['return_date']): ?>
                                                <p><span class="text-green-500 font-medium">Returned:</span> <?php echo formatDate($record['return_date']); ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td><?php echo getStatusBadge($record['status']); ?></td>
                                    <td>
                                        <?php if ($record['fine_amount'] > 0): ?>
                                            <span class="<?php echo $record['fine_paid'] ? 'text-green-600' : 'text-red-600 font-medium'; ?>">
                                                NPR <?php echo number_format($record['fine_amount'], 2); ?>
                                                <?php if ($record['fine_paid']): ?>
                                                    <i class="fa-solid fa-check-circle ml-1"></i>
                                                <?php endif; ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-gray-400">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-12">
                                    <i class="fa-solid fa-history text-5xl text-gray-200 mb-4"></i>
                                    <p class="text-gray-500">No records found</p>
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
                        <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo $status_filter; ?>" class="btn btn-outline btn-sm">Previous</a>
                    <?php endif; ?>

                    <span class="btn btn-sm bg-gray-100 dark:bg-slate-700 cursor-default">
                        Page <?php echo $page; ?> of <?php echo $totalPages; ?>
                    </span>

                    <?php if ($page < $totalPages): ?>
                        <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo $status_filter; ?>" class="btn btn-outline btn-sm">Next</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php require_once '../includes/footer.php'; ?>