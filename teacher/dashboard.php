<?php
require_once '../config/config.php';
require_once '../includes/functions.php';
requireTeacher();

$user_id = $_SESSION['user_id'];

// Fetch Stats
$borrowed_count = $pdo->prepare("SELECT COUNT(*) FROM issued_books WHERE user_id = ? AND status IN ('issued', 'overdue')");
$borrowed_count->execute([$user_id]);
$current_borrows = $borrowed_count->fetchColumn();

$issued_by_me = $pdo->prepare("SELECT COUNT(*) FROM issued_books WHERE issued_by = ? AND DATE(issue_date) = CURDATE()");
$issued_by_me->execute([$user_id]);
$issued_today = $issued_by_me->fetchColumn();

$returned_count = $pdo->prepare("SELECT COUNT(*) FROM issued_books WHERE user_id = ? AND status = 'returned'");
$returned_count->execute([$user_id]);
$total_read = $returned_count->fetchColumn();

$borrow_limit = getBorrowLimit();
$available_slots = $borrow_limit - $current_borrows;

// Fetch My Currently Issued Books
$stmt = $pdo->prepare("
    SELECT i.*, b.title, b.author, b.cover_image, DATEDIFF(i.due_date, CURDATE()) as days_left
    FROM issued_books i
    JOIN books b ON i.book_id = b.book_id
    WHERE i.user_id = ? AND i.status IN ('issued', 'overdue')
    ORDER BY i.due_date ASC
");
$stmt->execute([$user_id]);
$my_books = $stmt->fetchAll();

// Recent Issues by me (as issuer)
$stmt = $pdo->prepare("
    SELECT i.*, b.title, u.name as student_name
    FROM issued_books i
    JOIN books b ON i.book_id = b.book_id
    JOIN users u ON i.user_id = u.user_id
    WHERE i.issued_by = ?
    ORDER BY i.issue_date DESC
    LIMIT 5
");
$stmt->execute([$user_id]);
$recent_issued = $stmt->fetchAll();

$pageTitle = 'Teacher Dashboard';
require_once '../includes/header.php';
?>

<div class="dashboard-layout">
    <?php require_once '../includes/sidebar.php'; ?>

    <main class="main-content lg:mt-0 mt-16">
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white">Teacher Dashboard</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Welcome, <?php echo e($_SESSION['name']); ?>!</p>
            </div>

            <div class="flex items-center gap-3">
                <button onclick="ThemeManager.toggle()" class="hidden lg:flex btn btn-outline">
                    <i class="fa-solid fa-moon dark:hidden"></i>
                    <i class="fa-solid fa-sun hidden dark:inline"></i>
                </button>

                <a href="issue_book.php" class="btn btn-primary">
                    <i class="fa-solid fa-hand-holding-hand"></i>
                    <span class="hidden sm:inline">Issue Book</span>
                </a>
            </div>
        </header>

        <?php echo getFlash(); ?>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
            <div class="stat-card" data-aos="fade-up" data-aos-delay="100">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">My Borrows</p>
                        <h2 class="text-2xl md:text-3xl font-bold mt-1"><?php echo $current_borrows; ?></h2>
                        <p class="text-xs text-gray-400 mt-1"><?php echo $available_slots; ?> slots left</p>
                    </div>
                    <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center text-primary-600">
                        <i class="fa-solid fa-book-open text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card" data-aos="fade-up" data-aos-delay="150">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Issued Today</p>
                        <h2 class="text-2xl md:text-3xl font-bold mt-1 text-blue-600"><?php echo $issued_today; ?></h2>
                        <p class="text-xs text-gray-400 mt-1">By you</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center text-blue-600">
                        <i class="fa-solid fa-hand-holding-hand text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card" data-aos="fade-up" data-aos-delay="200">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Books Read</p>
                        <h2 class="text-2xl md:text-3xl font-bold mt-1 text-green-600"><?php echo $total_read; ?></h2>
                        <p class="text-xs text-gray-400 mt-1">Total</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center text-green-600">
                        <i class="fa-solid fa-check-circle text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card" data-aos="fade-up" data-aos-delay="250">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Borrow Limit</p>
                        <h2 class="text-2xl md:text-3xl font-bold mt-1 text-purple-600"><?php echo $borrow_limit; ?></h2>
                        <p class="text-xs text-gray-400 mt-1">Max books</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center text-purple-600">
                        <i class="fa-solid fa-layer-group text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mb-8" data-aos="fade-up">
            <h3 class="text-lg font-semibold mb-4 dark:text-white">Quick Actions</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <a href="issue_book.php" class="stat-card hover:border-primary-500 transition-colors text-center py-6">
                    <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center text-primary-600 mx-auto mb-3">
                        <i class="fa-solid fa-hand-holding-hand text-xl"></i>
                    </div>
                    <p class="font-medium dark:text-white">Issue to Student</p>
                </a>

                <a href="browse_books.php" class="stat-card hover:border-blue-500 transition-colors text-center py-6">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center text-blue-600 mx-auto mb-3">
                        <i class="fa-solid fa-search text-xl"></i>
                    </div>
                    <p class="font-medium dark:text-white">Browse Library</p>
                </a>

                <a href="profile.php" class="stat-card hover:border-green-500 transition-colors text-center py-6">
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center text-green-600 mx-auto mb-3">
                        <i class="fa-solid fa-user text-xl"></i>
                    </div>
                    <p class="font-medium dark:text-white">My Profile</p>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- My Current Books -->
            <div class="stat-card" data-aos="fade-up">
                <h3 class="text-lg font-semibold mb-4 dark:text-white">My Current Books</h3>

                <?php if (count($my_books) > 0): ?>
                    <div class="space-y-4">
                        <?php foreach ($my_books as $book): ?>
                            <div class="flex gap-4 p-3 rounded-lg <?php echo $book['days_left'] < 0 ? 'bg-red-50 dark:bg-red-900/20' : ($book['days_left'] <= 3 ? 'bg-yellow-50 dark:bg-yellow-900/20' : 'bg-gray-50 dark:bg-slate-700'); ?>">
                                <div class="w-16 h-20 bg-gray-200 dark:bg-slate-600 rounded-lg flex items-center justify-center text-gray-400 flex-shrink-0">
                                    <?php if ($book['cover_image']): ?>
                                        <img src="<?php echo BASE_URL . $book['cover_image']; ?>" class="w-full h-full object-cover rounded-lg" alt="">
                                    <?php else: ?>
                                        <i class="fa-solid fa-book text-2xl"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold truncate dark:text-white"><?php echo e($book['title']); ?></h4>
                                    <p class="text-sm text-gray-500"><?php echo e($book['author']); ?></p>
                                    <div class="mt-2">
                                        <?php if ($book['days_left'] < 0): ?>
                                            <span class="text-xs font-medium text-red-600">
                                                <i class="fa-solid fa-exclamation-triangle"></i> Overdue by <?php echo abs($book['days_left']); ?> days
                                            </span>
                                        <?php elseif ($book['days_left'] == 0): ?>
                                            <span class="text-xs font-medium text-orange-600">
                                                <i class="fa-solid fa-clock"></i> Due today!
                                            </span>
                                        <?php else: ?>
                                            <span class="text-xs text-gray-500">
                                                <i class="fa-solid fa-calendar"></i> Due in <?php echo $book['days_left']; ?> days
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8 text-gray-500">
                        <i class="fa-solid fa-book-open text-4xl mb-3 opacity-50"></i>
                        <p>You haven't borrowed any books yet.</p>
                        <a href="browse_books.php" class="btn btn-primary btn-sm mt-4">Browse Library</a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Recently Issued by Me -->
            <div class="stat-card" data-aos="fade-up" data-aos-delay="100">
                <h3 class="text-lg font-semibold mb-4 dark:text-white">Recently Issued by You</h3>

                <?php if (count($recent_issued) > 0): ?>
                    <div class="space-y-3">
                        <?php foreach ($recent_issued as $issue): ?>
                            <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-slate-700">
                                <?php echo getAvatar($issue['student_name'], 36); ?>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-sm truncate dark:text-white"><?php echo e($issue['title']); ?></p>
                                    <p class="text-xs text-gray-500">To: <?php echo e($issue['student_name']); ?></p>
                                </div>
                                <span class="text-xs text-gray-400"><?php echo formatDate($issue['issue_date'], 'M j'); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8 text-gray-500">
                        <i class="fa-solid fa-hand-holding-hand text-4xl mb-3 opacity-50"></i>
                        <p>No books issued yet.</p>
                        <a href="issue_book.php" class="btn btn-primary btn-sm mt-4">Issue a Book</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<?php require_once '../includes/footer.php'; ?>