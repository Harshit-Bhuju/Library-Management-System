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

// 1. Identify favorite categories (from returned books or 4+ star reviews)
$fav_categories_stmt = $pdo->prepare("
    SELECT b.category_id, COUNT(*) as interaction_count
    FROM issued_books i
    JOIN books b ON i.book_id = b.book_id
    WHERE i.user_id = ? AND i.status = 'returned'
    GROUP BY b.category_id
    UNION ALL
    SELECT b.category_id, COUNT(*) as interaction_count
    FROM reviews r
    JOIN books b ON r.book_id = b.book_id
    WHERE r.user_id = ? AND r.rating >= 4
    GROUP BY b.category_id
    ORDER BY interaction_count DESC
    LIMIT 3
");
$fav_categories_stmt->execute([$user_id, $user_id]);
$fav_categories = $fav_categories_stmt->fetchAll(PDO::FETCH_COLUMN);

// 2. Fetch Personalized Recommendations
$rec_query = "
    SELECT DISTINCT b.*, c.category_name,
           (SELECT AVG(rating) FROM reviews WHERE book_id = b.book_id) as avg_rating
    FROM books b
    LEFT JOIN categories c ON b.category_id = c.category_id
    WHERE b.book_id NOT IN (
        SELECT book_id FROM issued_books WHERE user_id = ?
    )
    AND b.is_active = 1
";

if (!empty($fav_categories)) {
    $placeholders = implode(',', array_fill(0, count($fav_categories), '?'));
    $rec_query .= " ORDER BY (b.category_id IN ($placeholders)) DESC, RAND() LIMIT 4";
    $params = array_merge([$user_id], $fav_categories);
} else {
    $rec_query .= " ORDER BY RAND() LIMIT 4";
    $params = [$user_id];
}

$recommended = $pdo->prepare($rec_query);
$recommended->execute($params);
$recommendations = $recommended->fetchAll();


// Notification query removed

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
                <span class="text-2xl font-bold text-red-600">NPR <?php echo number_format($total_fines, 2); ?></span>
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
                        <h2 class="text-2xl md:text-3xl font-bold mt-1 <?php echo $total_fines > 0 ? 'text-red-600' : 'text-gray-400'; ?>">NPR <?php echo number_format($total_fines, 2); ?></h2>
                        <p class="text-xs text-gray-400 mt-1">To pay</p>
                    </div>
                    <div class="w-12 h-12 <?php echo $total_fines > 0 ? 'bg-red-100 dark:bg-red-900/30' : 'bg-gray-100 dark:bg-gray-700'; ?> rounded-xl flex items-center justify-center <?php echo $total_fines > 0 ? 'text-red-600' : 'text-gray-400'; ?>">
                        <i class="fa-solid fa-receipt text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recently Returned Books -->
        <?php
        $returned_list_stmt = $pdo->prepare("
            SELECT i.*, b.title, b.author, b.cover_image
            FROM issued_books i
            JOIN books b ON i.book_id = b.book_id
            WHERE i.user_id = ? AND i.status = 'returned'
            ORDER BY i.return_date DESC
            LIMIT 3
        ");
        $returned_list_stmt->execute([$user_id]);
        $recently_returned = $returned_list_stmt->fetchAll();
        ?>

        <?php if (count($recently_returned) > 0): ?>
            <div class="mb-8" data-aos="fade-up">
                <h3 class="text-lg font-semibold dark:text-white mb-4">Recently Returned</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php foreach ($recently_returned as $book): ?>
                        <div class="stat-card opacity-75">
                            <div class="flex gap-4">
                                <div class="w-16 h-24 bg-gray-200 dark:bg-slate-600 rounded-lg flex items-center justify-center text-gray-400 flex-shrink-0 overflow-hidden">
                                    <?php if ($book['cover_image']): ?>
                                        <img src="<?php echo BASE_URL . $book['cover_image']; ?>" class="w-full h-full object-cover" alt="">
                                    <?php else: ?>
                                        <i class="fa-solid fa-book text-2xl"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-sm line-clamp-2 dark:text-white"><?php echo e($book['title']); ?></h4>
                                    <p class="text-xs text-gray-500 mt-0.5"><?php echo e($book['author']); ?></p>
                                    <div class="mt-2 text-xs text-green-600">
                                        <i class="fa-solid fa-check-circle mr-1"></i> Returned on <?php echo formatDate($book['return_date'], 'M j'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

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

        <!-- Pending Requests Section -->
        <?php
        $pending_stmt = $pdo->prepare("
            SELECT i.*, b.title, b.author, b.cover_image
            FROM issued_books i
            JOIN books b ON i.book_id = b.book_id
            WHERE i.user_id = ? AND i.status = 'requested'
            ORDER BY i.issue_date DESC
        ");
        $pending_stmt->execute([$user_id]);
        $pending_requests = $pending_stmt->fetchAll();
        ?>

        <?php if (count($pending_requests) > 0): ?>
            <div class="mb-8" data-aos="fade-up">
                <h3 class="text-lg font-semibold dark:text-white mb-4">Pending Book Requests</h3>
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-400 p-4 mb-4">
                    <p class="text-sm text-yellow-700 dark:text-yellow-200">
                        <i class="fa-solid fa-info-circle mr-2"></i>
                        Please wait for admin approval. You cannot borrow the book until the request is approved.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php foreach ($pending_requests as $req): ?>
                        <div class="stat-card border-yellow-200 dark:border-yellow-800">
                            <div class="flex gap-4">
                                <div class="w-16 h-24 bg-gray-200 dark:bg-slate-600 rounded-lg flex items-center justify-center text-gray-400 flex-shrink-0 overflow-hidden">
                                    <?php if ($req['cover_image']): ?>
                                        <img src="<?php echo BASE_URL . $req['cover_image']; ?>" class="w-full h-full object-cover" alt="">
                                    <?php else: ?>
                                        <i class="fa-solid fa-book text-2xl"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-sm line-clamp-2 dark:text-white"><?php echo e($req['title']); ?></h4>
                                    <p class="text-xs text-gray-500 mt-0.5"><?php echo e($req['author']); ?></p>
                                    <div class="flex items-center justify-between mt-2">
                                        <span class="inline-block px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">
                                            Pending Approval
                                        </span>
                                        <button onclick="cancelMyRequest(<?php echo $req['issue_id']; ?>)" class="text-red-500 hover:text-red-700 text-xs font-medium flex items-center gap-1 transition-colors" title="Cancel Request">
                                            <i class="fa-solid fa-times-circle"></i> Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <script>
            function cancelMyRequest(issueId) {
                if (!confirm('Are you sure you want to cancel this book request?')) return;

                const formData = new FormData();
                formData.append('issue_id', issueId);

                fetch('cancel_request.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Toast.success(data.message);
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            Toast.error(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Toast.error('An error occurred. Please try again.');
                    });
            }
        </script>

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
                            <div class="book-card overflow-hidden cursor-pointer hover:shadow-md transition-shadow" onclick='openBookDetail(<?php echo htmlspecialchars(json_encode($book), ENT_QUOTES, 'UTF-8'); ?>)'>
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

            <!-- Recent Notifications Removed -->
        </div>
    </main>
</div>

<!-- Book Detail Modal -->
<div class="modal-overlay" id="bookDetailModal">
    <div class="modal-content max-w-2xl">
        <button class="modal-close" onclick="closeModal('bookDetailModal')">
            <i class="fa-solid fa-times"></i>
        </button>

        <div class="flex gap-6">
            <div class="w-40 flex-shrink-0">
                <div id="detail_cover" class="w-full h-56 bg-gray-200 dark:bg-slate-700 rounded-lg flex items-center justify-center text-gray-400 overflow-hidden">
                    <i class="fa-solid fa-book text-3xl"></i>
                </div>
            </div>

            <div class="flex-1">
                <span id="detail_category" class="badge badge-primary mb-2"></span>
                <h3 id="detail_title" class="text-xl font-bold dark:text-white"></h3>
                <p id="detail_author" class="text-gray-500 mt-1"></p>

                <div class="grid grid-cols-2 gap-3 mt-4 text-sm">
                    <div>
                        <span class="text-gray-500">ISBN:</span>
                        <span id="detail_isbn" class="font-medium dark:text-white ml-1"></span>
                    </div>
                    <div>
                        <span class="text-gray-500">Publisher:</span>
                        <span id="detail_publisher" class="font-medium dark:text-white ml-1"></span>
                    </div>
                    <div>
                        <span class="text-gray-500">Total Copies:</span>
                        <span id="detail_total" class="font-medium dark:text-white ml-1"></span>
                    </div>
                    <div>
                        <span class="text-gray-500">Available:</span>
                        <span id="detail_available" class="font-medium ml-1"></span>
                    </div>
                    <div>
                        <span class="text-gray-500">Status:</span>
                        <span id="detail_status" class="font-medium ml-1"></span>
                    </div>
                </div>

                <div class="mt-4">
                    <h4 class="text-sm font-semibold text-gray-500 mb-1">Description</h4>
                    <p id="detail_description" class="text-sm text-gray-600 dark:text-gray-400"></p>
                </div>

                <div class="mt-4 flex gap-2">
                    <div id="detail_rating" class="flex items-center gap-1"></div>
                </div>

                <!-- Request Action in Modal -->
                <div class="mt-6 flex justify-end">
                    <button id="modalRequestBtn" class="btn btn-primary">
                        <i class="fa-solid fa-hand-holding-hand mr-2"></i> Request Book
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Request Confirmation Modal -->
<div class="modal-overlay" id="requestModal">
    <div class="modal-content max-w-sm text-center">
        <div class="w-16 h-16 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-question text-3xl"></i>
        </div>
        <h3 class="text-xl font-bold mb-2 dark:text-white">Request Book?</h3>
        <p class="text-gray-500 mb-6">Are you sure you want to request this book? You will need to collect it from the library.</p>

        <div class="flex gap-3 justify-center">
            <input type="hidden" id="request_book_id">
            <button onclick="confirmRequest()" class="btn btn-primary">Yes, Request</button>
            <button onclick="closeModal('requestModal')" class="btn btn-outline">Cancel</button>
        </div>
    </div>
</div>

<script>
    function openBookDetail(book) {
        document.getElementById('detail_title').textContent = book.title;
        document.getElementById('detail_author').textContent = 'by ' + book.author;
        document.getElementById('detail_category').textContent = book.category_name || 'Uncategorized';
        document.getElementById('detail_isbn').textContent = book.isbn || 'N/A';
        document.getElementById('detail_publisher').textContent = book.publisher || 'N/A';
        document.getElementById('detail_total').textContent = book.total_copies;
        document.getElementById('detail_description').textContent = book.description || 'No description available.';

        const available = document.getElementById('detail_available');
        available.textContent = book.available_copies;
        available.className = book.available_copies > 0 ? 'font-medium ml-1 text-green-600' : 'font-medium ml-1 text-red-600';

        const cover = document.getElementById('detail_cover');
        if (book.cover_image) {
            cover.innerHTML = `<img src="<?php echo BASE_URL; ?>${book.cover_image}" class="w-full h-full object-cover" alt="">`;
        } else {
            cover.innerHTML = '<i class="fa-solid fa-book text-3xl"></i>';
        }

        const rating = document.getElementById('detail_rating');
        if (book.avg_rating) {
            rating.innerHTML = `
                <i class="fa-solid fa-star text-yellow-400"></i>
                <span class="font-medium">${parseFloat(book.avg_rating).toFixed(1)}</span>
                <span class="text-gray-400 text-sm">(${book.review_count} reviews)</span>
            `;
        } else {
            rating.innerHTML = '<span class="text-gray-400 text-sm">No ratings yet</span>';
        }

        // Setup Request Button in Modal
        const reqBtn = document.getElementById('modalRequestBtn');
        const statusEl = document.getElementById('detail_status');

        if (!book.is_active) {
            statusEl.textContent = 'Inactive';
            statusEl.className = 'font-medium ml-1 text-red-600';
            reqBtn.disabled = true;
            reqBtn.innerHTML = '<i class="fa-solid fa-ban mr-2"></i> Inactive';
            reqBtn.className = 'btn btn-outline opacity-50 cursor-not-allowed';
        } else if (book.available_copies <= 0) {
            statusEl.textContent = 'Out of Stock';
            statusEl.className = 'font-medium ml-1 text-yellow-600';
            reqBtn.disabled = true;
            reqBtn.innerHTML = '<i class="fa-solid fa-clock mr-2"></i> Out of Stock';
            reqBtn.className = 'btn btn-outline opacity-50 cursor-not-allowed';
        } else {
            statusEl.textContent = 'Active';
            statusEl.className = 'font-medium ml-1 text-green-600';
            reqBtn.disabled = false;
            reqBtn.innerHTML = '<i class="fa-solid fa-hand-holding-hand mr-2"></i> Request Book';
            reqBtn.className = 'btn btn-primary';
            reqBtn.onclick = function() {
                closeModal('bookDetailModal');
                requestBook(book.book_id);
            };
        }

        openModal('bookDetailModal');
    }

    function requestBook(bookId) {
        document.getElementById('request_book_id').value = bookId;
        openModal('requestModal');
    }

    function confirmRequest() {
        const bookId = document.getElementById('request_book_id').value;
        const btn = document.querySelector(`#requestModal .btn-primary`); // Select the confirm button

        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
        btn.disabled = true;

        const formData = new FormData();
        formData.append('book_id', bookId);

        fetch('request_book.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                closeModal('requestModal');
                if (data.success) {
                    Toast.success(data.message);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    Toast.error(data.message);
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Toast.error('An error occurred. Please try again.');
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
    }
</script>

<?php require_once '../includes/footer.php'; ?>