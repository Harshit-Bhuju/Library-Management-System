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
$where = "1=1"; // Show all books, including inactive
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
    $where .= " AND b.available_copies > 0 AND b.is_active = 1";
} elseif ($availability === 'unavailable') {
    $where .= " AND (b.available_copies = 0 OR b.is_active = 0)";
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
                    <select name="category" class="form-control" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['category_id']; ?>" <?php echo $category_filter == $cat['category_id'] ? 'selected' : ''; ?>>
                                <?php echo e($cat['category_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="w-40">
                    <select name="availability" class="form-control" onchange="this.form.submit()">
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
                            <div class="book-cover-placeholder group cursor-pointer" onclick='openBookDetail(<?php echo htmlspecialchars(json_encode($book), ENT_QUOTES, 'UTF-8'); ?>)'>
                                <?php if ($book['cover_image']): ?>
                                    <img src="<?php echo BASE_URL . $book['cover_image']; ?>"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" alt="">
                                <?php else: ?>
                                    <i class="fa-solid fa-book"></i>
                                <?php endif; ?>
                            </div>

                            <!-- Status Badges -->
                            <div class="absolute top-2 right-2 flex flex-col gap-1 items-end">
                                <?php if (!$book['is_active']): ?>
                                    <span class="badge badge-danger">
                                        <i class="fa-solid fa-ban mr-1"></i>Inactive
                                    </span>
                                <?php elseif ($book['available_copies'] > 0): ?>
                                    <span class="badge badge-success">
                                        <i class="fa-solid fa-check mr-1"></i><?php echo $book['available_copies']; ?> left
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-warning">
                                        <i class="fa-solid fa-clock mr-1"></i>Out of Stock
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="p-4" data-aos="fade-up" data-aos-delay="50">
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
                                <button onclick='openBookDetail(<?php echo htmlspecialchars(json_encode($book), ENT_QUOTES, 'UTF-8'); ?>)'
                                    class="btn btn-sm btn-outline flex-1 justify-center">
                                    <i class="fa-solid fa-eye"></i> View
                                </button>
                                <?php if (!isAdmin()): ?>
                                    <?php if ($book['is_active'] && $book['available_copies'] > 0): ?>
                                        <button onclick="requestBook(<?php echo $book['book_id']; ?>)"
                                            class="btn btn-sm btn-primary flex-1 justify-center"
                                            <?php echo !canBorrowMore($user_id) ? 'disabled title="Borrow limit reached"' : ''; ?>>
                                            <i class="fa-solid fa-hand"></i> Request
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-outline flex-1 justify-center opacity-50 cursor-not-allowed" disabled>
                                            <i class="fa-solid <?php echo !$book['is_active'] ? 'fa-ban' : 'fa-hourglass-half'; ?>"></i>
                                            <?php echo !$book['is_active'] ? 'Inactive' : 'No Stock'; ?>
                                        </button>
                                    <?php endif; ?>
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
                    <div>
                        <span class="text-gray-500">Status:</span>
                        <span id="detail_status" class="font-medium ml-1"></span>
                    </div>
                </div>

                <div class="mt-4">
                    <h4 class="text-sm font-semibold text-gray-500 mb-1">Description</h4>
                    <p id="detail_description" class="text-sm text-gray-600 dark:text-gray-400"></p>
                </div>

                <hr class="my-6 border-gray-100 dark:border-slate-700">

                <!-- Reviews Section -->
                <div class="mt-4">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="font-bold dark:text-white">Reviews & Ratings</h4>
                        <div id="detail_rating" class="flex items-center gap-1"></div>
                    </div>

                    <!-- Add Review Form -->
                    <div class="bg-gray-50 dark:bg-slate-800/50 rounded-xl p-4 mb-6">
                        <div class="flex justify-between items-center mb-3">
                            <h5 id="reviewFormTitle" class="text-sm font-semibold dark:text-white">Write a Review</h5>
                            <button type="button" id="deleteReviewBtn" class="text-xs text-red-500 hover:text-red-600 hidden" onclick="deleteMyReview()">
                                <i class="fa-solid fa-trash mr-1"></i> Delete My Review
                            </button>
                        </div>
                        <form id="reviewForm" class="space-y-3">
                            <input type="hidden" name="book_id" id="review_book_id">
                            <div class="star-rating flex gap-1 text-xl">
                                <input type="hidden" name="rating" id="review_rating_val" value="0">
                                <i class="fa-regular fa-star star cursor-pointer"></i>
                                <i class="fa-regular fa-star star cursor-pointer"></i>
                                <i class="fa-regular fa-star star cursor-pointer"></i>
                                <i class="fa-regular fa-star star cursor-pointer"></i>
                                <i class="fa-regular fa-star star cursor-pointer"></i>
                            </div>
                            <textarea name="review_text" id="review_text_area" rows="2" class="form-control text-sm" placeholder="Share your thoughts about this book..."></textarea>
                            <button type="submit" id="submitReviewBtn" class="btn btn-sm btn-primary w-full justify-center">
                                <i class="fa-solid fa-paper-plane mr-2"></i> Submit Review
                            </button>
                        </form>
                    </div>

                    <!-- Reviews List -->
                    <div id="reviewsList" class="space-y-4 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                        <!-- Reviews will be loaded here -->
                        <div class="text-center py-4 text-gray-400 text-sm">Loading reviews...</div>
                    </div>
                </div>

                <!-- Request Action in Modal -->
                <div class="mt-8 pt-6 border-t border-gray-100 dark:border-slate-700 flex justify-end">
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

        // Review Form setup
        document.getElementById('review_book_id').value = book.book_id;
        document.getElementById('review_rating_val').value = 0;
        document.querySelector('#reviewForm textarea').value = '';
        StarRating.updateStars(document.querySelectorAll('#reviewForm .star'), 0);

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

        // Load Reviews
        loadReviews(book.book_id);

        // Setup Request Button in Modal
        const reqBtn = document.getElementById('modalRequestBtn');
        const statusEl = document.getElementById('detail_status');
        // ...
        // (rest of the Status logic remains same, but I'll provide the full function below to ensure consistency)
        // ...
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

    function loadReviews(bookId) {
        const container = document.getElementById('reviewsList');
        const formTitle = document.getElementById('reviewFormTitle');
        const submitBtn = document.getElementById('submitReviewBtn');
        const deleteBtn = document.getElementById('deleteReviewBtn');
        const ratingInput = document.getElementById('review_rating_val');
        const textArea = document.getElementById('review_text_area');

        container.innerHTML = '<div class="text-center py-4 text-gray-400 text-sm"><i class="fa-solid fa-spinner fa-spin mr-2"></i>Loading reviews...</div>';

        fetch(`../api/get_reviews.php?book_id=${bookId}`)
            .then(response => response.json())
            .then(data => {
                // Handle user's own review
                if (data.user_review) {
                    formTitle.textContent = 'Update Your Review';
                    submitBtn.innerHTML = '<i class="fa-solid fa-rotate mr-2"></i> Update Review';
                    deleteBtn.classList.remove('hidden');
                    ratingInput.value = data.user_review.rating;
                    textArea.value = data.user_review.review_text;
                    StarRating.updateStars(document.querySelectorAll('#reviewForm .star'), data.user_review.rating);
                } else {
                    formTitle.textContent = 'Write a Review';
                    submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane mr-2"></i> Submit Review';
                    deleteBtn.classList.add('hidden');
                    ratingInput.value = 0;
                    textArea.value = '';
                    StarRating.updateStars(document.querySelectorAll('#reviewForm .star'), 0);
                }

                if (data.success && data.reviews.length > 0) {
                    container.innerHTML = data.reviews.map(review => `
                        <div class="border-b border-gray-100 dark:border-slate-700 pb-3 last:border-0">
                            <div class="flex justify-between items-start mb-1">
                                <span class="font-semibold text-sm dark:text-white ${review.user_id == <?php echo $_SESSION['user_id']; ?> ? 'text-primary-600' : ''}">
                                    ${review.reviewer_name} ${review.user_id == <?php echo $_SESSION['user_id']; ?> ? '(You)' : ''}
                                </span>
                                <div class="flex text-xs text-yellow-400">
                                    ${Array(5).fill(0).map((_, i) => `<i class="fa-${i < review.rating ? 'solid' : 'regular'} fa-star"></i>`).join('')}
                                </div>
                            </div>
                            <p class="text-xs text-gray-600 dark:text-gray-400">${review.review_text || '<span class="italic">No comment</span>'}</p>
                            <span class="text-[10px] text-gray-400 mt-1 block">${review.created_at}</span>
                        </div>
                    `).join('');
                } else {
                    container.innerHTML = '<div class="text-center py-4 text-gray-400 text-sm">No reviews yet. Be the first to review!</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                container.innerHTML = '<div class="text-center py-4 text-red-400 text-sm">Error loading reviews.</div>';
            });
    }


    // Handle Review Submission
    document.getElementById('reviewForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const bookId = document.getElementById('review_book_id').value;
        const rating = document.getElementById('review_rating_val').value;

        if (rating == 0) {
            Toast.error('Please select a star rating.');
            return;
        }

        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalBtnHtml = submitBtn.innerHTML;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Submitting...';

        fetch('submit_review.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Toast.success(data.message);
                    loadReviews(bookId);
                    // Reset form
                    document.getElementById('review_rating_val').value = 0;
                    this.querySelector('textarea').value = '';
                    this.querySelectorAll('.star').forEach(s => {
                        s.classList.remove('filled', 'fa-solid');
                        s.classList.add('fa-regular');
                    });
                } else {
                    Toast.error(data.message);
                }
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
            });
    });

    function requestBook(bookId) {
        document.getElementById('request_book_id').value = bookId;
        openModal('requestModal');
    }

    function confirmRequest() {
        const bookId = document.getElementById('request_book_id').value;
        const btn = document.querySelector(`button[onclick="requestBook(${bookId})"]`);

        if (btn) {
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
            btn.disabled = true;
        }

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
                    if (btn) {
                        btn.innerHTML = originalText || '<i class="fa-solid fa-hand"></i> Request';
                        btn.disabled = false;
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Toast.error('An error occurred. Please try again.');
                if (btn) {
                    btn.innerHTML = originalText || '<i class="fa-solid fa-hand"></i> Request';
                    btn.disabled = false;
                }
            });
    }

    async function deleteMyReview() {
        const bookId = document.getElementById('review_book_id').value;
        const confirmed = await ConfirmModal.show('Are you sure you want to delete your review?', 'Delete Review', 'danger');

        if (!confirmed) return;

        const formData = new FormData();
        formData.append('book_id', bookId);

        fetch('../api/delete_review.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Toast.success(data.message);
                    loadReviews(bookId);
                } else {
                    Toast.error(data.message);
                }
            });
    }
</script>

<?php require_once '../includes/footer.php'; ?>