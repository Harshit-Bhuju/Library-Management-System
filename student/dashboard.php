<?php
require_once '../config/config.php';
require_once '../includes/functions.php';
requireLogin();

$user_id = $_SESSION['user_id'];

// Fetch Stats
$borrowed_stmt = $pdo->prepare("SELECT COUNT(*) FROM issued_books WHERE user_id = ? AND status IN ('issued', 'overdue')");
$borrowed_stmt->execute([$user_id]);
$current_borrows = $borrowed_stmt->fetchColumn();

$returned_stmt = $pdo->prepare("SELECT COUNT(*) FROM issued_books WHERE user_id = ? AND status = 'returned'");
$returned_stmt->execute([$user_id]);
$total_read = $returned_stmt->fetchColumn();

$overdue_stmt = $pdo->prepare("SELECT COUNT(*) FROM issued_books WHERE user_id = ? AND status = 'overdue'");
$overdue_stmt->execute([$user_id]);
$overdue_count = $overdue_stmt->fetchColumn();

$fines_stmt = $pdo->prepare("SELECT COALESCE(SUM(fine_amount), 0) FROM issued_books WHERE user_id = ? AND fine_paid = 0");
$fines_stmt->execute([$user_id]);
$total_fines = $fines_stmt->fetchColumn();

$borrow_limit = getBorrowLimit();
$available_slots = $borrow_limit - $current_borrows;

// Fetch Currently Issued Books with countdown
$stmt = $pdo->prepare("
    SELECT i.*, b.title, b.author, b.cover_image, 
           DATEDIFF(i.due_date, CURDATE()) as days_left
    FROM issued_books i
    JOIN books b ON i.book_id = b.book_id
    WHERE i.user_id = ? AND i.status IN ('issued', 'overdue')
    ORDER BY i.due_date ASC
");
$stmt->execute([$user_id]);
$my_books = $stmt->fetchAll();

// Recommended Books (based on same category as previously borrowed)
$recommended = $pdo->prepare("
    SELECT DISTINCT b.*, c.category_name,
           (SELECT AVG(rating) FROM reviews WHERE book_id = b.book_id) as avg_rating
    FROM books b
    LEFT JOIN categories c ON b.category_id = c.category_id
    WHERE b.available_copies > 0
    AND b.book_id NOT IN (
        SELECT book_id FROM issued_books WHERE user_id = ?
    )
    ORDER BY RAND()
    LIMIT 4
");
$recommended->execute([$user_id]);
$recommendations = $recommended->fetchAll();

// Recent Notifications
$notifications = $pdo->prepare("
    SELECT * FROM notifications 
    WHERE user_id = ? 
    ORDER BY created_at DESC 
    LIMIT 3
");
$notifications->execute([$user_id]);
$recent_notifications = $notifications->fetchAll();

$pageTitle = 'Student Dashboard';
require_once '../includes/header.php';
?>

<div class="dashboard-layout">
    <?php require_once '../includes/sidebar.php'; ?>

    <main class="main-content lg:mt-0 mt-16">
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white">My Dashboard</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Welcome back, <?php echo e($_SESSION['name']); ?>!</p>
            </div>

            <div class="flex items-center gap-3">
                <button onclick="ThemeManager.toggle()" class="hidden lg:flex btn btn-outline">
                    <i class="fa-solid fa-moon dark:hidden"></i>
                    <i class="fa-solid fa-sun hidden dark:inline"></i>
                </button>

                <a href="browse_books.php" class="btn btn-primary">
                    <i class="fa-solid fa-search"></i>
                    <span class="hidden sm:inline">Browse Books</span>
                </a>
            </div>
        </header>

        <?php echo getFlash(); ?>

        <!-- Alert for Overdue Books -->
        <?php if ($overdue_count > 0): ?>
            <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-xl p-4 mb-6 flex items-center gap-3" data-aos="fade-down">
                <div class="w-10 h-10 bg-red-100 dark:bg-red-900/50 rounded-full flex items-center justify-center text-red-600">
                    <i class="fa-solid fa-exclamation-triangle"></i>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-red-800 dark:text-red-200">You have <?php echo $overdue_count; ?> overdue book(s)!</p>
                    <p class="text-sm text-red-600 dark:text-red-300">Please return them as soon as possible to avoid additional fines.</p>
                </div>
                <span class="text-2xl font-bold text-red-600">$<?php echo number_format($total_fines, 2); ?></span>
            </div>
        <?php endif; ?>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
            <div class="stat-card" data-aos="fade-up" data-aos-delay="100">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Current Borrows</p>
                        <h2 class="text-2xl md:text-3xl font-bold mt-1"><?php echo $current_borrows; ?></h2>
                        <div class="mt-2">
                            <div class="flex gap-1">
                                <?php for ($i = 0; $i < $borrow_limit; $i++): ?>
                                    <div class="w-6 h-2 rounded-full <?php echo $i < $current_borrows ? 'bg-primary-500' : 'bg-gray-200 dark:bg-slate-600'; ?>"></div>
                                <?php endfor; ?>
                            </div>
                            <p class="text-xs text-gray-400 mt-1"><?php echo $available_slots; ?> of <?php echo $borrow_limit; ?> slots free</p>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center text-primary-600">
                        <i class="fa-solid fa-book-open text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card" data-aos="fade-up" data-aos-delay="150">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Books Read</p>
                        <h2 class="text-2xl md:text-3xl font-bold mt-1 text-green-600"><?php echo $total_read; ?></h2>
                        <p class="text-xs text-gray-400 mt-1">Completed</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center text-green-600">
                        <i class="fa-solid fa-check-circle text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card" data-aos="fade-up" data-aos-delay="200">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Overdue</p>
                        <h2 class="text-2xl md:text-3xl font-bold mt-1 <?php echo $overdue_count > 0 ? 'text-red-600' : 'text-gray-400'; ?>"><?php echo $overdue_count; ?></h2>
                        <p class="text-xs text-gray-400 mt-1">Books</p>
                    </div>
                    <div class="w-12 h-12 <?php echo $overdue_count > 0 ? 'bg-red-100 dark:bg-red-900/30' : 'bg-gray-100 dark:bg-gray-700'; ?> rounded-xl flex items-center justify-center <?php echo $overdue_count > 0 ? 'text-red-600' : 'text-gray-400'; ?>">
                        <i class="fa-solid fa-clock text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card" data-aos="fade-up" data-aos-delay="250">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Pending Fines</p>
                        <h2 class="text-2xl md:text-3xl font-bold mt-1 <?php echo $total_fines > 0 ? 'text-red-600' : 'text-gray-400'; ?>">$<?php echo number_format($total_fines, 2); ?></h2>
                        <p class="text-xs text-gray-400 mt-1">To pay</p>
                    </div>
                    <div class="w-12 h-12 <?php echo $total_fines > 0 ? 'bg-red-100 dark:bg-red-900/30' : 'bg-gray-100 dark:bg-gray-700'; ?> rounded-xl flex items-center justify-center <?php echo $total_fines > 0 ? 'text-red-600' : 'text-gray-400'; ?>">
                        <i class="fa-solid fa-receipt text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Currently Issued Books -->
        <div class="mb-8" data-aos="fade-up">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold dark:text-white">Currently Borrowed</h3>
                <a href="borrow_history.php" class="text-primary-600 hover:text-primary-700 text-sm font-medium">View History →</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php if (count($my_books) > 0): ?>
                    <?php foreach ($my_books as $book): ?>
                        <div class="stat-card <?php echo $book['days_left'] < 0 ? 'border-red-300 dark:border-red-700 bg-red-50/50 dark:bg-red-900/10' : ($book['days_left'] <= 3 ? 'border-yellow-300 dark:border-yellow-700' : ''); ?>">
                            <div class="flex gap-4">
                                <div class="w-20 h-28 bg-gray-200 dark:bg-slate-600 rounded-lg flex items-center justify-center text-gray-400 flex-shrink-0 overflow-hidden">
                                    <?php if ($book['cover_image']): ?>
                                        <img src="<?php echo BASE_URL . $book['cover_image']; ?>" class="w-full h-full object-cover" alt="">
                                    <?php else: ?>
                                        <i class="fa-solid fa-book text-2xl"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-sm line-clamp-2 dark:text-white"><?php echo e($book['title']); ?></h4>
                                    <p class="text-xs text-gray-500 mt-0.5"><?php echo e($book['author']); ?></p>

                                    <div class="mt-3 space-y-1">
                                        <div class="text-xs text-gray-500">
                                            <i class="fa-solid fa-calendar-plus mr-1"></i> Issued: <?php echo formatDate($book['issue_date'], 'M j'); ?>
                                        </div>
                                        <div class="text-xs <?php echo $book['days_left'] < 0 ? 'text-red-600 font-medium' : ($book['days_left'] <= 3 ? 'text-yellow-600' : 'text-gray-500'); ?>">
                                            <i class="fa-solid fa-calendar-check mr-1"></i>
                                            Due: <?php echo formatDate($book['due_date'], 'M j'); ?>
                                            <?php if ($book['days_left'] < 0): ?>
                                                <span class="ml-1">(<?php echo abs($book['days_left']); ?> days late!)</span>
                                            <?php elseif ($book['days_left'] == 0): ?>
                                                <span class="ml-1">(Today!)</span>
                                            <?php elseif ($book['days_left'] <= 3): ?>
                                                <span class="ml-1">(<?php echo $book['days_left']; ?> days left)</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Countdown Timer -->
                                    <?php if ($book['days_left'] >= 0): ?>
                                        <div class="mt-2" data-countdown="<?php echo $book['due_date']; ?> 23:59:59"></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-full text-center py-12 stat-card">
                        <i class="fa-solid fa-book-open text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
                        <p class="text-gray-500 dark:text-gray-400 mb-4">You haven't borrowed any books yet.</p>
                        <a href="browse_books.php" class="btn btn-primary">
                            <i class="fa-solid fa-search mr-2"></i> Browse Library
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recommendations & Notifications Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recommendations -->
            <div class="lg:col-span-2 stat-card" data-aos="fade-up">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold dark:text-white">
                        <i class="fa-solid fa-wand-magic-sparkles text-primary-500 mr-2"></i>
                        Recommended for You
                    </h3>
                    <a href="browse_books.php" class="text-primary-600 hover:text-primary-700 text-sm font-medium">See All →</a>
                </div>

                <?php if (count($recommendations) > 0): ?>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <?php foreach ($recommendations as $book): ?>
                            <div class="book-card overflow-hidden">
                                <div class="book-cover-placeholder">
                                    <?php if ($book['cover_image']): ?>
                                        <img src="<?php echo BASE_URL . $book['cover_image']; ?>" class="w-full h-full object-cover" alt="">
                                    <?php else: ?>
                                        <i class="fa-solid fa-book"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="p-3">
                                    <h4 class="font-medium text-sm line-clamp-1 dark:text-white"><?php echo e($book['title']); ?></h4>
                                    <p class="text-xs text-gray-500 truncate"><?php echo e($book['author']); ?></p>
                                    <?php if ($book['avg_rating']): ?>
                                        <div class="flex items-center gap-1 mt-1">
                                            <i class="fa-solid fa-star text-yellow-400 text-xs"></i>
                                            <span class="text-xs text-gray-500"><?php echo number_format($book['avg_rating'], 1); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-center text-gray-500 py-8">No recommendations available</p>
                <?php endif; ?>
            </div>

            <!-- Recent Notifications -->
            <div class="stat-card" data-aos="fade-up" data-aos-delay="100">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold dark:text-white">
                        <i class="fa-solid fa-bell text-primary-500 mr-2"></i>
                        Notifications
                    </h3>
                    <a href="notifications.php" class="text-primary-600 hover:text-primary-700 text-sm font-medium">View All →</a>
                </div>

                <?php if (count($recent_notifications) > 0): ?>
                    <div class="space-y-3">
                        <?php foreach ($recent_notifications as $notif): ?>
                            <div class="p-3 rounded-lg <?php echo $notif['is_read'] ? 'bg-gray-50 dark:bg-slate-700' : 'bg-primary-50 dark:bg-primary-900/20'; ?>">
                                <div class="flex items-start gap-2">
                                    <i class="fa-solid <?php
                                                        echo match ($notif['notification_type']) {
                                                            'success' => 'fa-check-circle text-green-500',
                                                            'warning' => 'fa-exclamation-triangle text-yellow-500',
                                                            'danger' => 'fa-exclamation-circle text-red-500',
                                                            default => 'fa-info-circle text-blue-500'
                                                        };
                                                        ?> mt-0.5"></i>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium dark:text-white"><?php echo e($notif['title']); ?></p>
                                        <p class="text-xs text-gray-500 line-clamp-2"><?php echo e($notif['message']); ?></p>
                                        <p class="text-xs text-gray-400 mt-1"><?php echo formatDateTime($notif['created_at']); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8 text-gray-500">
                        <i class="fa-solid fa-bell-slash text-3xl mb-2 opacity-50"></i>
                        <p class="text-sm">No notifications yet</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<?php require_once '../includes/footer.php'; ?>