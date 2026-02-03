<?php
require_once '../config/config.php';
require_once '../includes/functions.php';
requireAdmin();

// Handle Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST[CSRF_TOKEN_NAME] ?? '')) {
        setFlash('error', 'Invalid request.');
        redirect('admin/manage_requests.php');
    }

    $issue_id = (int) $_POST['issue_id'];
    $action = $_POST['action'];

    if ($action === 'approve') {
        // Approve: Set status to 'issued', issue_date = NOW, due_date = NOW + 15 days
        // User requested: "15 days from tomorrow". So if approved today, due date = tomorrow + 15 = today + 16.
        try {
            $pdo->beginTransaction();

            // Double check availability before approving
            $check = $pdo->prepare("SELECT book_id FROM issued_books WHERE issue_id = ? AND status = 'requested'");
            $check->execute([$issue_id]);
            $req_data = $check->fetch();

            if ($req_data) {
                $book_id = $req_data['book_id'];

                // Get book details
                $book_check = $pdo->prepare("SELECT available_copies FROM books WHERE book_id = ? FOR UPDATE");
                $book_check->execute([$book_id]);
                $book_info = $book_check->fetch();

                if ($book_info['available_copies'] <= 0) {
                    throw new Exception("Cannot approve: Book is now out of stock.");
                }

                $stmt = $pdo->prepare("
                    UPDATE issued_books 
                    SET status = 'issued', 
                        issued_by = ?, 
                        issue_date = CURDATE(), 
                        due_date = DATE_ADD(CURDATE(), INTERVAL 16 DAY) 
                    WHERE issue_id = ? AND status = 'requested'
                ");
                $stmt->execute([$_SESSION['user_id'], $issue_id]);

                // Update available copies
                $pdo->prepare("UPDATE books SET available_copies = available_copies - 1 WHERE book_id = ?")->execute([$book_id]);

                // Auto-Inactivate if stock is finished
                $pdo->prepare("UPDATE books SET is_active = 0 WHERE book_id = ? AND available_copies = 0")->execute([$book_id]);

                $pdo->commit();

                logActivity('book_approve', "Approved issue ID: $issue_id");
                setFlash('success', 'Request approved successfully.');
            } else {
                throw new Exception("Request not found.");
            }
        } catch (Exception $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            setFlash('error', $e->getMessage());
        }
    } elseif ($action === 'reject') {
        // Reject: Set status to 'cancelled'
        try {
            // Notify before updating (Removed)
            $req = $pdo->prepare("SELECT book_id, user_id FROM issued_books WHERE issue_id = ?");
            $req->execute([$issue_id]);
            $details = $req->fetch();

            if ($details) {
                // Notification removed

                $pdo->prepare("UPDATE issued_books SET status = 'cancelled' WHERE issue_id = ?")->execute([$issue_id]);
                logActivity('book_reject', "Rejected (Cancelled) issue ID: $issue_id");
                setFlash('success', 'Request rejected/cancelled.');
            }
        } catch (PDOException $e) {
            setFlash('error', 'Error: ' . $e->getMessage());
        }
    }

    redirect('admin/manage_requests.php');
}

// Fetch Requests
$requests = $pdo->query("
    SELECT i.*, b.title, b.cover_image, u.name as student_name, u.email, u.class
    FROM issued_books i
    JOIN books b ON i.book_id = b.book_id
    JOIN users u ON i.user_id = u.user_id
    WHERE i.status = 'requested'
    ORDER BY i.issue_date ASC
")->fetchAll();

$pageTitle = 'Manage Requests';
require_once '../includes/header.php';
?>

<div class="dashboard-layout">
    <?php require_once '../includes/sidebar.php'; ?>

    <main class="main-content lg:mt-0 mt-16">
        <header class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold dark:text-white">Book Requests</h1>
                <p class="text-gray-500 text-sm">Manage student book requests</p>
            </div>
            <div class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg text-sm font-medium">
                <?php echo count($requests); ?> Pending Requests
            </div>
        </header>

        <?php echo getFlash(); ?>

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-slate-700 text-gray-600 dark:text-gray-200 text-sm uppercase tracking-wider">
                            <th class="px-6 py-4 font-semibold">Book</th>
                            <th class="px-6 py-4 font-semibold">Student</th>
                            <th class="px-6 py-4 font-semibold">Requested On</th>
                            <th class="px-6 py-4 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                        <?php if (count($requests) > 0): ?>
                            <?php foreach ($requests as $req): ?>
                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-14 bg-gray-200 rounded flex-shrink-0 overflow-hidden">
                                                <?php if ($req['cover_image']): ?>
                                                    <img src="<?php echo BASE_URL . $req['cover_image']; ?>" class="w-full h-full object-cover">
                                                <?php else: ?>
                                                    <div class="flex items-center justify-center h-full text-gray-400"><i class="fa-solid fa-book"></i></div>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-800 dark:text-gray-200"><?php echo e($req['title']); ?></p>
                                                <p class="text-xs text-gray-500">ID: <?php echo $req['book_id']; ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div>
                                            <p class="font-medium text-gray-800 dark:text-gray-200"><?php echo e($req['student_name']); ?></p>
                                            <p class="text-xs text-gray-500">Class <?php echo $req['class']; ?></p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        <?php echo formatDate($req['issue_date']); ?>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <form method="POST" class="inline" onsubmit="return confirmAction(event, 'Approve this book request?', 'Confirm Approval', 'primary')">
                                                <input type="hidden" name="issue_id" value="<?php echo $req['issue_id']; ?>">
                                                <input type="hidden" name="action" value="approve">
                                                <?php echo csrfInput(); ?>
                                                <button type="submit" class="btn btn-sm btn-success text-white" title="Approve">
                                                    <i class="fa-solid fa-check mr-1"></i> Approve
                                                </button>
                                            </form>

                                            <form method="POST" class="inline" onsubmit="return confirmAction(event, 'Reject this request?', 'Confirm Rejection')">
                                                <input type="hidden" name="issue_id" value="<?php echo $req['issue_id']; ?>">
                                                <input type="hidden" name="action" value="reject">
                                                <?php echo csrfInput(); ?>
                                                <button type="submit" class="btn btn-sm btn-danger text-white" title="Reject">
                                                    <i class="fa-solid fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-gray-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-3">
                                            <i class="fa-solid fa-clipboard-check text-2xl text-gray-400"></i>
                                        </div>
                                        <p>No pending book requests.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<?php require_once '../includes/footer.php'; ?>