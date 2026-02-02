<?php
require_once '../config/config.php';
require_once '../includes/functions.php';
requireStaff(); // Both admin and teacher can issue

$message = '';
$borrow_period = (int) getSetting('borrow_period_days', DEFAULT_BORROW_DAYS);

// Handle Issue Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['issue_book'])) {
    if (!verifyCSRFToken($_POST[CSRF_TOKEN_NAME] ?? '')) {
        setFlash('error', 'Invalid request.');
        redirect('admin/issue_book.php');
    }

    $user_id = (int) $_POST['user_id'];
    $book_id = (int) $_POST['book_id'];
    $due_date = sanitize($_POST['due_date']);

    try {
        // Validate user exists and is a student or teacher
        $user_stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ? AND is_active = 1");
        $user_stmt->execute([$user_id]);
        $user = $user_stmt->fetch();

        if (!$user) {
            throw new Exception('User not found or inactive.');
        }

        // Check user's borrow limit
        $limit = $user['role'] === 'teacher' ? (int) getSetting('max_books_teacher', MAX_BOOKS_TEACHER) : (int) getSetting('max_books_student', MAX_BOOKS_STUDENT);
        $current = getCurrentBorrowCount($user_id);

        if ($current >= $limit) {
            throw new Exception("User has reached their borrow limit ({$limit} books).");
        }

        // Check if user has overdue books (optional setting)
        $allow_overdue = (bool) getSetting('allow_overdue_borrow', 0);
        if (!$allow_overdue && hasOverdueBooks($user_id)) {
            throw new Exception('User has overdue books. Please return them first.');
        }

        // Validate book exists and is available
        $book_stmt = $pdo->prepare("SELECT * FROM books WHERE book_id = ? AND is_active = 1");
        $book_stmt->execute([$book_id]);
        $book = $book_stmt->fetch();

        if (!$book) {
            throw new Exception('Book not found.');
        }

        if ($book['available_copies'] < 1) {
            throw new Exception('No copies available for this book.');
        }

        // Check if user already has this book
        $existing = $pdo->prepare("SELECT issue_id FROM issued_books WHERE user_id = ? AND book_id = ? AND status IN ('issued', 'overdue')");
        $existing->execute([$user_id, $book_id]);
        if ($existing->rowCount() > 0) {
            throw new Exception('User already has this book borrowed.');
        }

        // Issue the book
        $pdo->beginTransaction();

        $issue_stmt = $pdo->prepare("
            INSERT INTO issued_books (book_id, user_id, issued_by, issue_date, due_date, status) 
            VALUES (?, ?, ?, CURDATE(), ?, 'issued')
        ");
        $issue_stmt->execute([$book_id, $user_id, $_SESSION['user_id'], $due_date]);

        // Update available copies
        $pdo->prepare("UPDATE books SET available_copies = available_copies - 1 WHERE book_id = ?")->execute([$book_id]);

        $pdo->commit();

        // Log activity
        logActivity('book_issue', "Issued '{$book['title']}' to {$user['name']}");

        // Send notification to user
        sendNotification(
            $user_id,
            'Book Issued',
            "You have borrowed '{$book['title']}'. Due date: " . formatDate($due_date),
            'success',
            'student/dashboard.php'
        );

        setFlash('success', "Book '{$book['title']}' issued successfully to {$user['name']}!");
        redirect(isAdmin() ? 'admin/issue_book.php' : 'teacher/issue_book.php');
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        setFlash('error', $e->getMessage());
        redirect(isAdmin() ? 'admin/issue_book.php' : 'teacher/issue_book.php');
    }
}

// Fetch students and teachers for dropdown
$users = $pdo->query("SELECT user_id, name, email, role, department FROM users WHERE role IN ('student', 'teacher') AND is_active = 1 ORDER BY name")->fetchAll();

// Fetch available books
$books = $pdo->query("SELECT book_id, title, author, isbn, available_copies FROM books WHERE available_copies > 0 AND is_active = 1 ORDER BY title")->fetchAll();

$pageTitle = 'Issue Book';
require_once '../includes/header.php';
?>

<div class="dashboard-layout">
    <?php require_once '../includes/sidebar.php'; ?>

    <main class="main-content lg:mt-0 mt-16">
        <header class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white">Issue Book</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Issue a book to a student or teacher</p>
            </div>
        </header>

        <?php echo getFlash(); ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Issue Form -->
            <div class="lg:col-span-2">
                <div class="stat-card" data-aos="fade-up">
                    <form method="POST" class="space-y-6">
                        <input type="hidden" name="issue_book" value="1">
                        <?php echo csrfInput(); ?>

                        <!-- Select User -->
                        <div>
                            <label class="form-label">Select User *</label>
                            <select name="user_id" id="userSelect" class="form-control" required>
                                <option value="">-- Search and select user --</option>
                                <?php foreach ($users as $user): ?>
                                    <option value="<?php echo $user['user_id']; ?>"
                                        data-role="<?php echo $user['role']; ?>"
                                        data-dept="<?php echo e($user['department']); ?>">
                                        <?php echo e($user['name']); ?> (<?php echo e($user['email']); ?>) - <?php echo ucfirst($user['role']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <p class="text-xs text-gray-500 mt-1" id="userInfo"></p>
                        </div>

                        <!-- Select Book -->
                        <div>
                            <label class="form-label">Select Book *</label>
                            <select name="book_id" id="bookSelect" class="form-control" required>
                                <option value="">-- Search and select book --</option>
                                <?php foreach ($books as $book): ?>
                                    <option value="<?php echo $book['book_id']; ?>"
                                        data-copies="<?php echo $book['available_copies']; ?>">
                                        <?php echo e($book['title']); ?> by <?php echo e($book['author']); ?> (<?php echo $book['available_copies']; ?> available)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <p class="text-xs text-gray-500 mt-1" id="bookInfo"></p>
                        </div>

                        <!-- Due Date -->
                        <div>
                            <label class="form-label">Due Date *</label>
                            <input type="date" name="due_date" class="form-control"
                                value="<?php echo date('Y-m-d', strtotime("+{$borrow_period} days")); ?>"
                                min="<?php echo date('Y-m-d'); ?>"
                                required>
                            <p class="text-xs text-gray-500 mt-1">Default: <?php echo $borrow_period; ?> days from today</p>
                        </div>

                        <div class="flex gap-3">
                            <button type="submit" class="btn btn-primary flex-1 justify-center">
                                <i class="fa-solid fa-hand-holding-hand mr-2"></i> Issue Book
                            </button>
                            <button type="reset" class="btn btn-outline">Clear</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Panel -->
            <div class="space-y-4">
                <div class="stat-card" data-aos="fade-up" data-aos-delay="100">
                    <h3 class="font-semibold mb-4 dark:text-white">
                        <i class="fa-solid fa-info-circle text-primary-500 mr-2"></i>
                        Issue Rules
                    </h3>
                    <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-check text-green-500 mt-1"></i>
                            <span>Students can borrow up to <?php echo getSetting('max_books_student', MAX_BOOKS_STUDENT); ?> books</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-check text-green-500 mt-1"></i>
                            <span>Teachers can borrow up to <?php echo getSetting('max_books_teacher', MAX_BOOKS_TEACHER); ?> books</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-check text-green-500 mt-1"></i>
                            <span>Default borrow period is <?php echo $borrow_period; ?> days</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-exclamation-triangle text-yellow-500 mt-1"></i>
                            <span>Fine: $<?php echo getSetting('fine_per_day', DEFAULT_FINE_PER_DAY); ?> per day after due date</span>
                        </li>
                        <?php if (!getSetting('allow_overdue_borrow', 0)): ?>
                            <li class="flex items-start gap-2">
                                <i class="fa-solid fa-ban text-red-500 mt-1"></i>
                                <span>Users with overdue books cannot borrow</span>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="stat-card" data-aos="fade-up" data-aos-delay="150">
                    <h3 class="font-semibold mb-4 dark:text-white">
                        <i class="fa-solid fa-chart-simple text-primary-500 mr-2"></i>
                        Quick Stats
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Available Books</span>
                            <span class="font-semibold dark:text-white"><?php echo count($books); ?></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Active Users</span>
                            <span class="font-semibold dark:text-white"><?php echo count($users); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    // User selection info
    document.getElementById('userSelect').addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        const info = document.getElementById('userInfo');
        if (selected.value) {
            const role = selected.dataset.role;
            const dept = selected.dataset.dept;
            const limit = role === 'teacher' ? <?php echo getSetting('max_books_teacher', MAX_BOOKS_TEACHER); ?> : <?php echo getSetting('max_books_student', MAX_BOOKS_STUDENT); ?>;
            info.innerHTML = `<span class="text-primary-600">${role.charAt(0).toUpperCase() + role.slice(1)}</span> • ${dept || 'No department'} • Limit: ${limit} books`;
        } else {
            info.innerHTML = '';
        }
    });

    // Book selection info
    document.getElementById('bookSelect').addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        const info = document.getElementById('bookInfo');
        if (selected.value) {
            const copies = selected.dataset.copies;
            info.innerHTML = `<span class="${copies > 2 ? 'text-green-600' : 'text-yellow-600'}">${copies} copies available</span>`;
        } else {
            info.innerHTML = '';
        }
    });
</script>

<?php require_once '../includes/footer.php'; ?>