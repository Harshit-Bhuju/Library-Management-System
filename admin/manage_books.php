<?php
require_once '../config/config.php';
require_once '../includes/functions.php';
requireAdmin();

// Handle book actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!verifyCSRFToken($_POST[CSRF_TOKEN_NAME] ?? '')) {
        setFlash('error', 'Invalid request.');
        redirect('admin/manage_books.php');
    }

    // Add Book
    if (isset($_POST['add_book'])) {
        $title = sanitize($_POST['title']);
        $author = sanitize($_POST['author']);
        $isbn = sanitize($_POST['isbn']);
        $publisher = sanitize($_POST['publisher']);
        $category_id = (int) $_POST['category_id'];
        $total_copies = (int) $_POST['total_copies'];
        $description = sanitize($_POST['description']);

        // Handle cover image upload
        $cover_image = null;
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $cover_image = handleBookImageUpload($_FILES['cover_image']);
        }

        try {
            $stmt = $pdo->prepare("
                INSERT INTO books (title, author, isbn, publisher, category_id, total_copies, available_copies, cover_image, description) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$title, $author, $isbn, $publisher, $category_id, $total_copies, $total_copies, $cover_image, $description]);

            logActivity('book_add', "Added book: {$title}");
            setFlash('success', "Book '{$title}' added successfully!");
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                setFlash('error', 'A book with this ISBN already exists.');
            } else {
                setFlash('error', 'Failed to add book: ' . $e->getMessage());
            }
        }
        redirect('admin/manage_books.php');
    }

    // Delete Book
    if (isset($_POST['delete_book'])) {
        $book_id = (int) $_POST['book_id'];

        // Check if book is currently issued
        $issued = $pdo->prepare("SELECT COUNT(*) FROM issued_books WHERE book_id = ? AND status IN ('issued', 'overdue')");
        $issued->execute([$book_id]);

        if ($issued->fetchColumn() > 0) {
            setFlash('error', 'Cannot delete: This book is currently issued.');
        } else {
            $stmt = $pdo->prepare("DELETE FROM books WHERE book_id = ?");
            $stmt->execute([$book_id]);

            logActivity('book_delete', "Deleted book ID: {$book_id}");
            setFlash('success', 'Book deleted successfully!');
        }
        redirect('admin/manage_books.php');
    }

    // Update Book
    if (isset($_POST['update_book'])) {
        $book_id = (int) $_POST['book_id'];
        $title = sanitize($_POST['title']);
        $author = sanitize($_POST['author']);
        $isbn = sanitize($_POST['isbn']);
        $publisher = sanitize($_POST['publisher']);
        $category_id = (int) $_POST['category_id'];
        $total_copies = (int) $_POST['total_copies'];
        $description = sanitize($_POST['description']);

        // Get current book data
        $current = $pdo->prepare("SELECT * FROM books WHERE book_id = ?");
        $current->execute([$book_id]);
        $book = $current->fetch();

        // Handle cover image
        $cover_image = $book['cover_image'];
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $new_cover = handleBookImageUpload($_FILES['cover_image']);
            if ($new_cover) {
                $cover_image = $new_cover;
            }
        }

        // Calculate available copies adjustment
        $copies_diff = $total_copies - $book['total_copies'];
        $new_available = $book['available_copies'] + $copies_diff;
        if ($new_available < 0) $new_available = 0;

        try {
            $stmt = $pdo->prepare("
                UPDATE books 
                SET title = ?, author = ?, isbn = ?, publisher = ?, category_id = ?, 
                    total_copies = ?, available_copies = ?, cover_image = ?, description = ?,
                    is_active = CASE WHEN ? > 0 THEN 1 ELSE is_active END
                WHERE book_id = ?
            ");
            $stmt->execute([$title, $author, $isbn, $publisher, $category_id, $total_copies, $new_available, $cover_image, $description, $new_available, $book_id]);

            logActivity('book_update', "Updated book: {$title}");
            setFlash('success', "Book '{$title}' updated successfully!");
        } catch (PDOException $e) {
            setFlash('error', 'Failed to update book: ' . $e->getMessage());
        }
        redirect('admin/manage_books.php');
    }

    // Toggle Book Status (Active/Inactive)
    if (isset($_POST['toggle_status'])) {
        $book_id = (int) $_POST['book_id'];

        try {
            $stmt = $pdo->prepare("UPDATE books SET is_active = NOT is_active WHERE book_id = ?");
            $stmt->execute([$book_id]);

            $stmt = $pdo->prepare("SELECT title, is_active FROM books WHERE book_id = ?");
            $stmt->execute([$book_id]);
            $book = $stmt->fetch();

            $status = $book['is_active'] ? 'activated' : 'deactivated';
            logActivity('book_toggle', "{$status} book: {$book['title']}");
            setFlash('success', "Book '{$book['title']}' has been {$status}.");
        } catch (PDOException $e) {
            setFlash('error', 'Failed to toggle status: ' . $e->getMessage());
        }
        redirect('admin/manage_books.php');
    }
}

// Fetch categories
$categories = $pdo->query("SELECT * FROM categories ORDER BY category_name")->fetchAll();

// Fetch books with pagination
$page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$perPage = 15;
$offset = ($page - 1) * $perPage;

$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$category_filter = isset($_GET['category']) ? (int) $_GET['category'] : 0;

$where = "1=1";
$params = [];

if ($search) {
    $where .= " AND (b.title LIKE ? OR b.author LIKE ? OR b.isbn LIKE ?)";
    $params = array_merge($params, ["%$search%", "%$search%", "%$search%"]);
}
if ($category_filter) {
    $where .= " AND b.category_id = ?";
    $params[] = $category_filter;
}

$countStmt = $pdo->prepare("SELECT COUNT(*) FROM books b WHERE $where");
$countStmt->execute($params);
$total = $countStmt->fetchColumn();
$totalPages = ceil($total / $perPage);

$stmt = $pdo->prepare("
    SELECT b.*, c.category_name 
    FROM books b 
    LEFT JOIN categories c ON b.category_id = c.category_id 
    WHERE $where 
    ORDER BY b.created_at DESC 
    LIMIT $perPage OFFSET $offset
");
$stmt->execute($params);
$books = $stmt->fetchAll();

$pageTitle = 'Manage Books';
require_once '../includes/header.php';
?>

<div class="dashboard-layout">
    <?php require_once '../includes/sidebar.php'; ?>

    <main class="main-content lg:mt-0 mt-16">
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white">Manage Books</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1"><?php echo number_format($total); ?> books in library</p>
            </div>

            <button onclick="openModal('addBookModal')" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> Add Book
            </button>
        </header>

        <?php echo getFlash(); ?>

        <!-- Filters -->
        <div class="stat-card mb-6" data-aos="fade-up">
            <form method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <div class="relative">
                        <input type="text" name="search" placeholder="Search books..."
                            value="<?php echo e($search); ?>"
                            class="form-control pl-10 pr-20">
                        <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 btn btn-sm btn-secondary">
                            Search
                        </button>
                        <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>
                <div class="w-full sm:w-48">
                    <select name="category" class="form-control" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['category_id']; ?>" <?php echo $category_filter == $cat['category_id'] ? 'selected' : ''; ?>>
                                <?php echo e($cat['category_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!-- Filter button removed - Auto filtering enabled -->
                <?php if ($search || $category_filter): ?>
                    <a href="manage_books.php" class="btn btn-outline">Clear</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Books Table -->
        <div class="stat-card" data-aos="fade-up">
            <div class="data-table-container">
                <table class="data-table" id="booksTable">
                    <thead>
                        <tr>
                            <th>Book</th>
                            <th>Author</th>
                            <th>ISBN</th>
                            <th>Category</th>
                            <th>Copies</th>
                            <th>Reviews</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($books) > 0): ?>
                            <?php foreach ($books as $book): ?>
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-14 bg-gray-200 dark:bg-slate-600 rounded flex items-center justify-center text-gray-400 flex-shrink-0 overflow-hidden">
                                                <?php if ($book['cover_image']): ?>
                                                    <img src="<?php echo BASE_URL . $book['cover_image']; ?>" class="w-full h-full object-cover" alt="">
                                                <?php else: ?>
                                                    <i class="fa-solid fa-book"></i>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <p class="font-medium dark:text-white"><?php echo e($book['title']); ?></p>
                                                <p class="text-xs text-gray-500"><?php echo e($book['publisher']); ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-sm"><?php echo e($book['author']); ?></td>
                                    <td class="text-sm font-mono"><?php echo e($book['isbn']); ?></td>
                                    <td>
                                        <span class="badge badge-primary"><?php echo e($book['category_name'] ?? 'Uncategorized'); ?></span>
                                    </td>
                                    <td>
                                        <span class="<?php echo $book['available_copies'] > 0 ? 'text-green-600' : 'text-red-600'; ?> font-medium">
                                            <?php echo $book['available_copies']; ?>/<?php echo $book['total_copies']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-1 text-xs text-yellow-500">
                                            <i class="fa-solid fa-star"></i>
                                            <a href="manage_reviews.php?search=<?php echo urlencode($book['title']); ?>" class="hover:underline">
                                                Reviews (<?php
                                                            $rs = $pdo->prepare("SELECT COUNT(*) FROM reviews WHERE book_id = ?");
                                                            $rs->execute([$book['book_id']]);
                                                            echo $rs->fetchColumn();
                                                            ?>)
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($book['is_active']): ?>
                                            <span class="badge badge-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="flex gap-2">
                                            <button onclick='openEditModal(<?php echo htmlspecialchars(json_encode($book), ENT_QUOTES, 'UTF-8'); ?>)'
                                                class="btn btn-sm btn-outline" title="Edit">
                                                <i class="fa-solid fa-edit"></i>
                                            </button>

                                            <form method="POST" class="inline" onsubmit="return confirmAction(event, '<?php echo $book['is_active'] ? 'Deactivate' : 'Activate'; ?> this book?', 'Confirm Status Change', 'primary')">
                                                <input type="hidden" name="toggle_status" value="1">
                                                <input type="hidden" name="book_id" value="<?php echo $book['book_id']; ?>">
                                                <?php echo csrfInput(); ?>
                                                <button type="submit" class="btn btn-sm btn-outline" title="<?php echo $book['is_active'] ? 'Deactivate' : 'Activate'; ?>">
                                                    <i class="fa-solid <?php echo $book['is_active'] ? 'fa-ban' : 'fa-check'; ?>"></i>
                                                </button>
                                            </form>

                                            <form method="POST" class="inline" onsubmit="return confirmAction(event, 'Delete this book?')">
                                                <input type="hidden" name="delete_book" value="1">
                                                <input type="hidden" name="book_id" value="<?php echo $book['book_id']; ?>">
                                                <?php echo csrfInput(); ?>
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-12">
                                    <i class="fa-solid fa-book-open text-5xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-500">No books found</p>
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
                        <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo $category_filter; ?>"
                            class="btn btn-outline btn-sm">Previous</a>
                    <?php endif; ?>

                    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                        <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo $category_filter; ?>"
                            class="btn btn-sm <?php echo $i === $page ? 'btn-primary' : 'btn-outline'; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo $category_filter; ?>"
                            class="btn btn-outline btn-sm">Next</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<!-- Add Book Modal -->
<div class="modal-overlay" id="addBookModal">
    <div class="modal-content max-w-2xl">
        <button class="modal-close" onclick="closeModal('addBookModal')">
            <i class="fa-solid fa-times"></i>
        </button>

        <h3 class="text-xl font-bold mb-4 dark:text-white">Add New Book</h3>

        <form method="POST" enctype="multipart/form-data" class="space-y-4">
            <input type="hidden" name="add_book" value="1">
            <?php echo csrfInput(); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" class="form-control" required>
                </div>

                <div>
                    <label class="form-label">Author *</label>
                    <input type="text" name="author" class="form-control" required>
                </div>

                <div>
                    <label class="form-label">ISBN</label>
                    <input type="text" name="isbn" class="form-control">
                </div>

                <div>
                    <label class="form-label">Publisher</label>
                    <input type="text" name="publisher" class="form-control">
                </div>

                <div>
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-control">
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['category_id']; ?>"><?php echo e($cat['category_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="form-label">Total Copies *</label>
                    <input type="number" name="total_copies" class="form-control" min="1" value="1" required>
                </div>

                <div>
                    <label class="form-label">Cover Image</label>
                    <input type="file" name="cover_image" class="form-control" accept="image/*">
                </div>

                <div class="md:col-span-2">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="btn btn-primary flex-1 justify-center">
                    <i class="fa-solid fa-plus mr-2"></i> Add Book
                </button>
                <button type="button" onclick="closeModal('addBookModal')" class="btn btn-outline">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Book Modal -->
<div class="modal-overlay" id="editBookModal">
    <div class="modal-content max-w-2xl">
        <button class="modal-close" onclick="closeModal('editBookModal')">
            <i class="fa-solid fa-times"></i>
        </button>

        <h3 class="text-xl font-bold mb-4 dark:text-white">Edit Book</h3>

        <form method="POST" enctype="multipart/form-data" class="space-y-4">
            <input type="hidden" name="update_book" value="1">
            <input type="hidden" name="book_id" id="edit_book_id">
            <?php echo csrfInput(); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" id="edit_title" class="form-control" required>
                </div>

                <div>
                    <label class="form-label">Author *</label>
                    <input type="text" name="author" id="edit_author" class="form-control" required>
                </div>

                <div>
                    <label class="form-label">ISBN</label>
                    <input type="text" name="isbn" id="edit_isbn" class="form-control">
                </div>

                <div>
                    <label class="form-label">Publisher</label>
                    <input type="text" name="publisher" id="edit_publisher" class="form-control">
                </div>

                <div>
                    <label class="form-label">Category</label>
                    <select name="category_id" id="edit_category" class="form-control">
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['category_id']; ?>"><?php echo e($cat['category_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="form-label">Total Copies *</label>
                    <input type="number" name="total_copies" id="edit_copies" class="form-control" min="1" required>
                </div>

                <div>
                    <label class="form-label">Cover Image</label>
                    <input type="file" name="cover_image" class="form-control" accept="image/*">
                    <p class="text-xs text-gray-500 mt-1">Leave empty to keep current image</p>
                </div>

                <div class="md:col-span-2">
                    <label class="form-label">Description</label>
                    <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="btn btn-primary flex-1 justify-center">
                    <i class="fa-solid fa-save mr-2"></i> Update Book
                </button>
                <button type="button" onclick="closeModal('editBookModal')" class="btn btn-outline">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(book) {
        document.getElementById('edit_book_id').value = book.book_id;
        document.getElementById('edit_title').value = book.title;
        document.getElementById('edit_author').value = book.author;
        document.getElementById('edit_isbn').value = book.isbn || '';
        document.getElementById('edit_publisher').value = book.publisher || '';
        document.getElementById('edit_category').value = book.category_id || '';
        document.getElementById('edit_copies').value = book.total_copies;
        document.getElementById('edit_description').value = book.description || '';

        openModal('editBookModal');
    }
</script>

<?php require_once '../includes/footer.php'; ?>