<?php
require_once '../config/config.php';
require_once '../includes/functions.php';
requireLogin();

$user_id = $_SESSION['user_id'];

// Pagination
$page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$perPage = 15;
$offset = ($page - 1) * $perPage;

// Fetch transactions
$stmt = $pdo->prepare("
    SELECT t.*, b.title as book_title
    FROM transactions t
    LEFT JOIN issued_books i ON t.issue_id = i.issue_id
    LEFT JOIN books b ON i.book_id = b.book_id
    WHERE t.user_id = ?
    ORDER BY t.created_at DESC
    LIMIT $perPage OFFSET $offset
");
$stmt->execute([$user_id]);
$transactions = $stmt->fetchAll();

// Get count for pagination
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM transactions WHERE user_id = ?");
$countStmt->execute([$user_id]);
$total = $countStmt->fetchColumn();
$totalPages = ceil($total / $perPage);

$pageTitle = 'Transaction History';
require_once '../includes/header.php';
?>

<div class="dashboard-layout">
    <?php require_once '../includes/sidebar.php'; ?>

    <main class="main-content lg:mt-0 mt-16">
        <header class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white">Transaction History</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">View your eSewa payment records</p>
        </header>

        <?php echo getFlash(); ?>

        <div class="stat-card" data-aos="fade-up">
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Transaction ID</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($transactions) > 0): ?>
                            <?php foreach ($transactions as $t): ?>
                                <tr>
                                    <td class="text-sm"><?php echo formatDateTime($t['created_at']); ?></td>
                                    <td>
                                        <p class="font-medium dark:text-white">Fine Payment</p>
                                        <?php if ($t['book_title']): ?>
                                            <p class="text-xs text-gray-500"><?php echo e($t['book_title']); ?></p>
                                        <?php endif; ?>
                                    </td>
                                    <td class="font-semibold"><?php echo CURRENCY_SYMBOL; ?> <?php echo number_format($t['amount'], 2); ?></td>
                                    <td>
                                        <?php if ($t['payment_method'] === 'cash'): ?>
                                            <span class="flex items-center gap-1 text-emerald-600 font-medium text-sm">
                                                <i class="fa-solid fa-money-bill-wave"></i> Cash
                                            </span>
                                        <?php else: ?>
                                            <span class="flex items-center gap-1 text-primary-600 font-medium text-sm">
                                                <i class="fa-solid fa-wallet"></i> eSewa
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-sm font-mono text-gray-500">
                                        <?php echo $t['transaction_code'] ?: '<span class="italic">Pending</span>'; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $statusClass = [
                                            'completed' => 'bg-green-100 text-green-700',
                                            'pending' => 'bg-yellow-100 text-yellow-700',
                                            'failed' => 'bg-red-100 text-red-700'
                                        ];
                                        $class = $statusClass[$t['status']] ?? 'bg-gray-100 text-gray-700';
                                        ?>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full <?php echo $class; ?>">
                                            <?php echo ucfirst($t['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-12">
                                    <i class="fa-solid fa-receipt text-5xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-500">No transactions found</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($totalPages > 1): ?>
                <div class="flex justify-center gap-2 mt-6">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?>" class="btn btn-outline btn-sm">Previous</a>
                    <?php endif; ?>
                    <span class="btn btn-sm bg-gray-100 dark:bg-slate-700 cursor-default">
                        Page <?php echo $page; ?> of <?php echo $totalPages; ?>
                    </span>
                    <?php if ($page < $totalPages): ?>
                        <a href="?page=<?php echo $page + 1; ?>" class="btn btn-outline btn-sm">Next</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php require_once '../includes/footer.php'; ?>