<?php
require_once '../config/config.php';
require_once '../includes/functions.php';
requireAdmin();

// Handle Delete Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_review'])) {
    if (!verifyCSRFToken($_POST[CSRF_TOKEN_NAME] ?? '')) {
        setFlash('error', 'Invalid request.');
        redirect('admin/manage_reviews.php');
    }

    $review_id = (int)$_POST['review_id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM reviews WHERE review_id = ?");
        $stmt->execute([$review_id]);

        logActivity('review_delete', "Deleted review ID: $review_id");
        setFlash('success', 'Review deleted successfully.');
    } catch (PDOException $e) {
        setFlash('error', 'Failed to delete review.');
    }
    redirect('admin/manage_reviews.php');
}

// Fetch Reviews with Pagination
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = 15;
$offset = ($page - 1) * $perPage;

$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$where = "1=1";
$params = [];

if ($search) {
    $where .= " AND (u.name LIKE ? OR b.title LIKE ? OR r.review_text LIKE ?)";
    $params = array_merge($params, ["%$search%", "%$search%", "%$search%"]);
}

$countStmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM reviews r 
    JOIN users u ON r.user_id = u.user_id 
    JOIN books b ON r.book_id = b.book_id 
    WHERE $where
");
$countStmt->execute($params);
$total = $countStmt->fetchColumn();
$totalPages = ceil($total / $perPage);

$stmt = $pdo->prepare("
    SELECT r.*, u.name as reviewer_name, b.title as book_title, b.cover_image 
    FROM reviews r
    JOIN users u ON r.user_id = u.user_id
    JOIN books b ON r.book_id = b.book_id
    WHERE $where
    ORDER BY r.created_at DESC
    LIMIT $perPage OFFSET $offset
");
$stmt->execute($params);
$reviews = $stmt->fetchAll();

$pageTitle = 'Manage Reviews';
require_once '../includes/header.php';
?>

<div class="dashboard-layout">
    <?php require_once '../includes/sidebar.php'; ?>

    <main class="main-content lg:mt-0 mt-16">
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white">Manage Reviews</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1"><?php echo number_format($total); ?> total reviews</p>
            </div>
        </header>

        <?php echo getFlash(); ?>

        <!-- Search -->
        <div class="stat-card mb-6" data-aos="fade-up">
            <form method="GET" class="flex gap-4">
                <div class="flex-1 relative">
                    <input type="text" name="search" placeholder="Search by reviewer, book, or content..."
                        value="<?php echo e($search); ?>" class="form-control pl-10 pr-20">
                    <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 btn btn-sm btn-secondary">Search</button>
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>
                <?php if ($search): ?>
                    <a href="manage_reviews.php" class="btn btn-outline">Clear</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Reviews Grid/Table -->
        <div class="stat-card" data-aos="fade-up">
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Book</th>
                            <th>Reviewer</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($reviews) > 0): ?>
                            <?php foreach ($reviews as $review): ?>
                                <tr>
                                    <td class="max-w-[200px]">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-12 bg-gray-100 dark:bg-slate-700 rounded flex items-center justify-center flex-shrink-0 overflow-hidden">
                                                <?php if ($review['cover_image']): ?>
                                                    <img src="<?php echo BASE_URL . $review['cover_image']; ?>" class="w-full h-full object-cover">
                                                <?php else: ?>
                                                    <i class="fa-solid fa-book text-gray-400 text-xs"></i>
                                                <?php endif; ?>
                                            </div>
                                            <span class="text-sm font-medium line-clamp-2 dark:text-white"><?php echo e($review['book_title']); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-sm dark:text-white"><?php echo e($review['reviewer_name']); ?></span>
                                    </td>
                                    <td>
                                        <div class="flex text-xs text-yellow-400">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fa-<?php echo $i <= $review['rating'] ? 'solid' : 'regular'; ?> fa-star"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </td>
                                    <td class="max-w-xs">
                                        <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2" title="<?php echo e($review['review_text']); ?>">
                                            <?php echo e($review['review_text'] ?: 'No comment'); ?>
                                        </p>
                                    </td>
                                    <td class="text-xs text-gray-400">
                                        <?php echo date('M j, Y', strtotime($review['created_at'])); ?>
                                    </td>
                                    <td>
                                        <form method="POST" class="inline" onsubmit="return confirmAction(event, 'Delete this review?')">
                                            <input type="hidden" name="delete_review" value="1">
                                            <input type="hidden" name="review_id" value="<?php echo $review['review_id']; ?>">
                                            <?php echo csrfInput(); ?>
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-12">
                                    <i class="fa-solid fa-message text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
                                    <p class="text-gray-500">No reviews found</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="flex justify-center gap-2 mt-6">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>" class="btn btn-outline btn-sm">Previous</a>
                    <?php endif; ?>
                    <span class="btn btn-sm cursor-default">Page <?php echo $page; ?> of <?php echo $totalPages; ?></span>
                    <?php if ($page < $totalPages): ?>
                        <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>" class="btn btn-outline btn-sm">Next</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php require_once '../includes/footer.php'; ?>