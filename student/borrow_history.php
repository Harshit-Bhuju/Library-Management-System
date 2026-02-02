<?php
require_once '../config/config.php';
require_once '../includes/functions.php';
requireLogin();

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT i.*, b.title, b.isbn
    FROM issued_books i
    JOIN books b ON i.book_id = b.book_id
    WHERE i.user_id = ?
    ORDER BY i.issue_date DESC
");
$stmt->execute([$user_id]);
$history = $stmt->fetchAll();

$pageTitle = 'Borrow History';
require_once '../includes/header.php';
?>

<div class="dashboard-layout">
    <?php require_once '../includes/sidebar.php'; ?>
    <main class="main-content">
        <h1>Borrow History</h1>
        <div class="card mt-4" style="background: var(--card-bg); padding: 1.5rem; border-radius: 1rem; margin-top: 1.5rem;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Book</th>
                        <th>Issued</th>
                        <th>Returned</th>
                        <th>Status</th>
                        <th>Fine</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($history) > 0): ?>
                        <?php foreach($history as $record): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($record['title']); ?></td>
                            <td><?php echo formatDate($record['issue_date']); ?></td>
                            <td><?php echo $record['return_date'] ? formatDate($record['return_date']) : '-'; ?></td>
                            <td><?php echo ucfirst($record['status']); ?></td>
                            <td><?php echo ($record['fine_amount'] > 0) ? '$'.$record['fine_amount'] : '-'; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center;">No history found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
<?php require_once '../includes/footer.php'; ?>
