<?php
require_once '../config/config.php';
require_once '../includes/functions.php';
requireAdmin();

$fine_per_day = (float) getSetting('fine_per_day', DEFAULT_FINE_PER_DAY);

// Handle Return
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['return_book'])) {
    if (!verifyCSRFToken($_POST[CSRF_TOKEN_NAME] ?? '')) {
        setFlash('error', 'Invalid request.');
        redirect('admin/return_book.php');
    }

    $issue_id = (int) $_POST['issue_id'];
    $fine_paid = isset($_POST['fine_paid']) ? 1 : 0;

    try {
        // Get issue details
        $stmt = $pdo->prepare("
            SELECT i.*, b.title, b.book_id, u.name, u.user_id
            FROM issued_books i
            JOIN books b ON i.book_id = b.book_id
            JOIN users u ON i.user_id = u.user_id
            WHERE i.issue_id = ? AND i.status IN ('issued', 'overdue')
        ");
        $stmt->execute([$issue_id]);
        $issue = $stmt->fetch();

        if (!$issue) {
            throw new Exception('Issue record not found or already returned.');
        }

        // Calculate fine
        $due_date = new DateTime($issue['due_date']);
        $today = new DateTime();
        $fine_amount = 0;

        if ($today > $due_date) {
            $days_overdue = $today->diff($due_date)->days;
            $fine_amount = $days_overdue * $fine_per_day;
        }

        $pdo->beginTransaction();

        // Update issue record
        $update = $pdo->prepare("
            UPDATE issued_books 
            SET status = 'returned', 
                return_date = CURDATE(), 
                fine_amount = ?,
                fine_paid = ?
            WHERE issue_id = ?
        ");
        $update->execute([$fine_amount, $fine_paid, $issue_id]);

        // Increase available copies
        $pdo->prepare("UPDATE books SET available_copies = available_copies + 1 WHERE book_id = ?")->execute([$issue['book_id']]);

        // Auto-Reactivate when stock returns
        $pdo->prepare("UPDATE books SET is_active = 1 WHERE book_id = ?")->execute([$issue['book_id']]);

        $pdo->commit();

        // Log activity
        logActivity('book_return', "Returned '{$issue['title']}' from {$issue['name']}" . ($fine_amount > 0 ? " (Fine: " . CURRENCY_SYMBOL . " {$fine_amount})" : ''));

        // Notification removed

        $success_msg = "Book '{$issue['title']}' returned successfully!";
        if ($fine_amount > 0) {
            $success_msg .= " Fine: " . CURRENCY_SYMBOL . " {$fine_amount}" . ($fine_paid ? ' (Collected)' : ' (Pending)');
        }

        setFlash('success', $success_msg);
        redirect('admin/return_book.php');
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        setFlash('error', $e->getMessage());
        redirect('admin/return_book.php');
    }
}

// Fetch all issued books (not returned)
$issued = $pdo->query("
    SELECT i.*, b.title, b.author, b.isbn, u.name as borrower_name, u.email, u.role,
           DATEDIFF(CURDATE(), i.due_date) as days_overdue
    FROM issued_books i
    JOIN books b ON i.book_id = b.book_id
    JOIN users u ON i.user_id = u.user_id
    WHERE i.status IN ('issued', 'overdue')
    ORDER BY i.due_date ASC
")->fetchAll();

// Update overdue status
$pdo->query("UPDATE issued_books SET status = 'overdue' WHERE status = 'issued' AND due_date < CURDATE()");

$pageTitle = 'Return Book';
require_once '../includes/header.php';
?>

<div class="dashboard-layout">
    <?php require_once '../includes/sidebar.php'; ?>

    <main class="main-content lg:mt-0 mt-16">
        <header class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white">Return Book</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Process book returns and collect fines</p>
            </div>

            <div class="flex items-center gap-2">
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Search..."
                        class="form-control pl-10 py-2 w-64"
                        onkeyup="filterTable('issuedTable', 'searchInput')">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>
        </header>

        <?php echo getFlash(); ?>

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="stat-card text-center" data-aos="fade-up">
                <p class="text-2xl font-bold text-primary-600"><?php echo count($issued); ?></p>
                <p class="text-sm text-gray-500">Currently Issued</p>
            </div>
            <div class="stat-card text-center" data-aos="fade-up" data-aos-delay="100">
                <?php
                $overdue_count = count(array_filter($issued, fn($i) => $i['days_overdue'] > 0));
                ?>
                <p class="text-2xl font-bold text-red-600"><?php echo $overdue_count; ?></p>
                <p class="text-sm text-gray-500">Overdue</p>
            </div>
            <div class="stat-card text-center" data-aos="fade-up" data-aos-delay="150">
                <?php
                $pending_fines = array_sum(array_map(fn($i) => $i['days_overdue'] > 0 ? $i['days_overdue'] * $fine_per_day : 0, $issued));
                ?>
                <p class="text-2xl font-bold text-yellow-600"><?php echo CURRENCY_SYMBOL . ' ' . number_format($pending_fines, 2); ?></p>
                <p class="text-sm text-gray-500">Pending Fines</p>
            </div>
            <div class="stat-card text-center" data-aos="fade-up" data-aos-delay="200">
                <p class="text-2xl font-bold text-gray-600"><?php echo CURRENCY_SYMBOL . ' ' . number_format($fine_per_day, 2); ?></p>
                <p class="text-sm text-gray-500">Fine/Day</p>
            </div>
        </div>

        <!-- Issued Books Table -->
        <div class="stat-card" data-aos="fade-up">
            <div class="overflow-x-auto">
                <table class="data-table" id="issuedTable">
                    <thead>
                        <tr>
                            <th>Book</th>
                            <th>Borrower</th>
                            <th>Issue Date</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Fine</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($issued) > 0): ?>
                            <?php foreach ($issued as $issue): ?>
                                <?php
                                $is_overdue = $issue['days_overdue'] > 0;
                                $fine = $is_overdue ? $issue['days_overdue'] * $fine_per_day : 0;
                                ?>
                                <tr class="<?php echo $is_overdue ? 'bg-red-50 dark:bg-red-900/10' : ''; ?>">
                                    <td>
                                        <div>
                                            <p class="font-medium dark:text-white"><?php echo e($issue['title']); ?></p>
                                            <p class="text-xs text-gray-500"><?php echo e($issue['author']); ?></p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <?php echo getAvatar($issue['borrower_name'], 32); ?>
                                            <div class="flex flex-col">
                                                <p class="font-medium text-sm dark:text-white leading-tight"><?php echo e($issue['borrower_name']); ?></p>
                                                <div class="mt-1"><?php echo getRoleBadge($issue['role']); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-sm"><?php echo formatDate($issue['issue_date']); ?></td>
                                    <td>
                                        <span class="text-sm <?php echo $is_overdue ? 'text-red-600 font-semibold' : ''; ?>">
                                            <?php echo formatDate($issue['due_date']); ?>
                                            <?php if ($is_overdue): ?>
                                                <br><span class="text-xs">(<?php echo $issue['days_overdue']; ?> days ago)</span>
                                            <?php endif; ?>
                                        </span>
                                    </td>
                                    <td><?php echo getStatusBadge($issue['status']); ?></td>
                                    <td>
                                        <?php if ($fine > 0): ?>
                                            <span class="text-red-600 font-semibold"><?php echo CURRENCY_SYMBOL . ' ' . number_format($fine, 2); ?></span>
                                        <?php else: ?>
                                            <span class="text-green-600">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button onclick="openReturnModal(<?php echo $issue['issue_id']; ?>, '<?php echo e(addslashes($issue['title'])); ?>', '<?php echo e(addslashes($issue['borrower_name'])); ?>', <?php echo $fine; ?>)"
                                            class="btn btn-sm btn-success">
                                            <i class="fa-solid fa-rotate-left mr-1"></i> Return
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-12">
                                    <i class="fa-solid fa-check-circle text-5xl text-green-400 mb-4"></i>
                                    <p class="text-gray-500">No books currently issued</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Return Modal -->
<div class="modal-overlay" id="returnModal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal('returnModal')">
            <i class="fa-solid fa-times"></i>
        </button>

        <h3 class="text-xl font-bold mb-4 dark:text-white">Confirm Return</h3>

        <form method="POST">
            <input type="hidden" name="return_book" value="1">
            <input type="hidden" name="issue_id" id="return_issue_id">
            <?php echo csrfInput(); ?>

            <div class="bg-gray-50 dark:bg-slate-700 rounded-lg p-4 mb-4">
                <p class="text-sm text-gray-500">Book</p>
                <p class="font-semibold dark:text-white" id="return_book_title"></p>
                <p class="text-sm text-gray-500 mt-2">Borrower</p>
                <p class="font-medium dark:text-white" id="return_borrower"></p>
            </div>

            <div id="fineSection" class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4 mb-4 hidden">
                <div class="flex justify-between items-center">
                    <span class="text-red-700 dark:text-red-300 font-medium">Fine Amount</span>
                    <span class="text-2xl font-bold text-red-600" id="return_fine"><?php echo CURRENCY_SYMBOL; ?> 0.00</span>
                </div>
                <label class="flex items-center gap-2 mt-3 cursor-pointer">
                    <input type="checkbox" name="fine_paid" class="w-4 h-4 accent-green-600">
                    <span class="text-sm text-red-700 dark:text-red-300">Fine has been collected</span>
                </label>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="btn btn-success flex-1 justify-center">
                    <i class="fa-solid fa-check mr-2"></i> Process Return
                </button>
                <button type="button" onclick="closeModal('returnModal')" class="btn btn-outline">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openReturnModal(issueId, bookTitle, borrower, fine) {
        document.getElementById('return_issue_id').value = issueId;
        document.getElementById('return_book_title').textContent = bookTitle;
        document.getElementById('return_borrower').textContent = borrower;
        document.getElementById('return_fine').textContent = '<?php echo CURRENCY_SYMBOL; ?> ' + fine.toFixed(2);

        const fineSection = document.getElementById('fineSection');
        if (fine > 0) {
            fineSection.classList.remove('hidden');
        } else {
            fineSection.classList.add('hidden');
        }

        openModal('returnModal');
    }
</script>

<?php require_once '../includes/footer.php'; ?>