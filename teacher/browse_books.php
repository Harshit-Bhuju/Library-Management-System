<?php
require_once '../config/config.php';
require_once '../includes/functions.php';
requireLogin();

$user_id = $_SESSION['user_id'];

// Fetch categories
$categories = $pdo->query("SELECT * FROM categories ORDER BY category_name")->fetchAll();

// Filters
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$category_filter = isset($_GET['category']) ? (int) $_GET['category'] : 0;
$availability = isset($_GET['availability']) ? sanitize($_GET['availability']) : '';

$page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$perPage = 12;
$offset = ($page - 1) * $perPage;

// Build query
$where = "b.is_active = 1";
$params = [];

if ($search) {
    $where .= " AND (b.title LIKE ? OR b.author LIKE ? OR b.isbn LIKE ?)";
    $params = array_merge($params, ["%$search%", "%$search%", "%$search%"]);
}
if ($category_filter) {
    $where .= " AND b.category_id = ?";
    $params[] = $category_filter;
}
if ($availability === 'available') {
    $where .= " AND b.available_copies > 0";
} elseif ($availability === 'unavailable') {
    $where .= " AND b.available_copies = 0";
}

// Get total count
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM books b WHERE $where");
$countStmt->execute($params);
$total = $countStmt->fetchColumn();
$totalPages = ceil($total / $perPage);

// Fetch books
$stmt = $pdo->prepare("
    SELECT b.*, c.category_name,
           (SELECT AVG(rating) FROM reviews WHERE book_id = b.book_id) as avg_rating,
           (SELECT COUNT(*) FROM reviews WHERE book_id = b.book_id) as review_count
    FROM books b 
    LEFT JOIN categories c ON b.category_id = c.category_id 
    WHERE $where 
    ORDER BY b.created_at DESC 
    LIMIT $perPage OFFSET $offset
");
$stmt->execute($params);
$books = $stmt->fetchAll();

$pageTitle = 'Browse Library';
require_once '../includes/header.php';
?>

<div class="dashboard-layout">
    <?php require_once '../includes/sidebar.php'; ?>

    <main class="main-content lg:mt-0 mt-16">
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white">Browse Library</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1"><?php echo number_format($total); ?> books available</p>
            </div>
        </header>

        <?php echo getFlash(); ?>

        <!-- Filters -->
        <div class="stat-card mb-6" data-aos="fade-up">
            <form method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <div class="relative">
                        <input type="text" name="search" placeholder="Search by title, author, or ISBN..."
                            value="<?php echo e($search); ?>"
                            class="form-control pl-10">
                        <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>
                <div class="w-40">
                    <select name="category" class="form-control">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['category_id']; ?>" <?php echo $category_filter == $cat['category_id'] ? 'selected' : ''; ?>>
                                <?php echo e($cat['category_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="w-40">
                    <select name="availability" class="form-control">
                        <option value="">Any Status</option>
                        <option value="available" <?php echo $availability === 'available' ? 'selected' : ''; ?>>Available</option>
                        <option value="unavailable" <?php echo $availability === 'unavailable' ? 'selected' : ''; ?>>Not Available</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-search"></i> Search
                </button>
                <?php if ($search || $category_filter || $availability): ?>
                    <a href="browse_books.php" class="btn btn-outline">Clear</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Books Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            <?php if (count($books) > 0): ?>
                <?php foreach ($books as $book): ?>
                    <div class="book-card" data-aos="fade-up">
                        <!-- Cover -->
                        <div class="relative">
                            <div class="book-cover-placeholder group">
                                <?php if ($book['cover_image']): ?>
                                    <img src="<?php echo BASE_URL . $book['cover_image']; ?>"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" alt="">
                                <?php else: ?>
                                    <i class="fa-solid fa-book"></i>
                                <?php endif; ?>
                            </div>

                            <!-- Availability Badge -->
                            <div class="absolute top-2 right-2">
                                <?php if ($book['available_copies'] > 0): ?>
                                    <span class="badge badge-success">
                                        <i class="fa-solid fa-check mr-1"></i><?php echo $book['available_copies']; ?> left
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-danger">
                                        <i class="fa-solid fa-times mr-1"></i>Unavailable
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="p-4">
                            <span class="text-xs text-primary-600 font-medium"><?php echo e($book['category_name'] ?? 'Uncategorized'); ?></span>
                            <h3 class="font-semibold text-sm md:text-base mt-1 line-clamp-2 dark:text-white" title="<?php echo e($book['title']); ?>">
                                <?php echo e($book['title']); ?>
                            </h3>
                            <p class="text-gray-500 text-sm truncate"><?php echo e($book['author']); ?></p>

                            <!-- Rating -->
                            <div class="flex items-center gap-2 mt-2">
                                <?php if ($book['avg_rating']): ?>
                                    <div class="flex items-center gap-1">
                                        <i class="fa-solid fa-star text-yellow-400 text-sm"></i>
                                        <span class="text-sm font-medium"><?php echo number_format($book['avg_rating'], 1); ?></span>
                                        <span class="text-xs text-gray-400">(<?php echo $book['review_count']; ?>)</span>
                                    </div>
                                <?php else: ?>
                                    <span class="text-xs text-gray-400">No ratings yet</span>
                                <?php endif; ?>
                            </div>

                            <!-- Actions -->
                            <div class="mt-3 flex gap-2">
                                <button onclick='openBookDetail(<?php echo json_encode($book); ?>)'
                                    class="btn btn-sm btn-outline flex-1 justify-center">
                                    <i class="fa-solid fa-eye"></i> View
                                </button>
                                <?php if (!isAdmin() && $book['available_copies'] > 0): ?>
                                    <button onclick="requestBook(<?php echo $book['book_id']; ?>)"
                                        class="btn btn-sm btn-primary flex-1 justify-center"
                                        <?php echo !canBorrowMore($user_id) ? 'disabled title="Borrow limit reached"' : ''; ?>>
                                        <i class="fa-solid fa-hand"></i> Request
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-16">
                    <i class="fa-solid fa-search text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
                    <p class="text-gray-500 dark:text-gray-400 mb-2">No books found</p>
                    <p class="text-sm text-gray-400">Try adjusting your search filters</p>
                    <?php if ($search || $category_filter || $availability): ?>
                        <a href="browse_books.php" class="btn btn-primary mt-4">Clear Filters</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="flex justify-center gap-2 mt-8">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo $category_filter; ?>&availability=<?php echo $availability; ?>"
                        class="btn btn-outline btn-sm">
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>
                <?php endif; ?>

                <span class="btn btn-sm bg-gray-100 dark:bg-slate-700 cursor-default">
                    Page <?php echo $page; ?> of <?php echo $totalPages; ?>
                </span>

                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo $category_filter; ?>&availability=<?php echo $availability; ?>"
                        class="btn btn-outline btn-sm">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
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
                </div>

                <div class="mt-4">
                    <h4 class="text-sm font-semibold text-gray-500 mb-1">Description</h4>
                    <p id="detail_description" class="text-sm text-gray-600 dark:text-gray-400"></p>
                </div>

                <div class="mt-4 flex gap-2">
                    <div id="detail_rating" class="flex items-center gap-1"></div>
                </div>
            </div>
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

        openModal('bookDetailModal');
    }

    function requestBook(bookId) {
        alert('Book request feature coming soon! Please visit the library desk to borrow this book.');
    }
</script>

<?php require_once '../includes/footer.php'; ?>