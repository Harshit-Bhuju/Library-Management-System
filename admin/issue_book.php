<?php
require_once '../config/config.php';
require_once '../includes/functions.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $roll_no = sanitize($_POST['roll_no']);
    $isbn = sanitize($_POST['isbn']);
    $due_date = $_POST['due_date'];

    try {
        $pdo->beginTransaction();

        // 1. Get Student
        $stmt = $pdo->prepare("SELECT user_id, name FROM users WHERE roll_no = ? AND role = 'student'");
        $stmt->execute([$roll_no]);
        $student = $stmt->fetch();

        if (!$student) {
            throw new Exception("Roll No not found.");
        }

        // 2. Get Book
        $stmt = $pdo->prepare("SELECT book_id, title, available_copies FROM books WHERE isbn = ?");
        $stmt->execute([$isbn]);
        $book = $stmt->fetch();

        if (!$book) {
            throw new Exception("Book ISBN not found.");
        }

        if ($book['available_copies'] < 1) {
            throw new Exception("Book is currently unavailable.");
        }

        // 3. Check for Existing Issued Copy (Optional Rule: Cannot same book twice)
        // ... skipping for simplicity

        // 4. Issue Book
        $sql = "INSERT INTO issued_books (book_id, user_id, issue_date, due_date, status) VALUES (?, ?, CURDATE(), ?, 'issued')";
        $pdo->prepare($sql)->execute([$book['book_id'], $student['user_id'], $due_date]);

        // 5. Update Book Count
        $pdo->prepare("UPDATE books SET available_copies = available_copies - 1 WHERE book_id = ?")->execute([$book['book_id']]);

        $pdo->commit();
        setFlash('success', "Book '{$book['title']}' issued to {$student['name']}.");
        redirect('admin/manage_books.php'); // Redirect to book list or stay

    } catch (Exception $e) {
        $pdo->rollBack();
        setFlash('error', $e->getMessage());
    }
}

$pageTitle = 'Issue Book';
require_once '../includes/header.php';
?>

<div class="dashboard-layout">
    <?php require_once '../includes/sidebar.php'; ?>
    
    <main class="main-content">
        <header style="margin-bottom: 2rem;">
            <h1>Issue New Book</h1>
        </header>

        <?php echo getFlash(); ?>

        <div class="card" style="background: var(--card-bg); padding: 2rem; border-radius: 1rem; border: 1px solid var(--border-color); max-width: 600px; margin: 0 auto;">
            <form method="POST">
                <div class="form-group">
                    <label class="form-label">Roll No</label>
                    <input type="text" name="roll_no" class="form-control" placeholder="Enter Roll No" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Book ISBN</label>
                    <input type="text" name="isbn" class="form-control" placeholder="Scan or Enter ISBN" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Due Date</label>
                    <input type="date" name="due_date" class="form-control" required value="<?php echo date('Y-m-d', strtotime('+14 days')); ?>">
                    <small style="color: var(--text-muted);">Default: 14 days from today</small>
                </div>

                <div style="margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary w-full justify-center">
                        <i class="fa-solid fa-check"></i> Confirm Issue
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

<?php require_once '../includes/footer.php'; ?>
