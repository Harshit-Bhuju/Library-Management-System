<?php
require_once '../config/config.php';
require_once '../includes/functions.php';
requireAdmin();

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_book'])) {
        $isbn = sanitize($_POST['isbn']);
        $title = sanitize($_POST['title']);
        $author = sanitize($_POST['author']);
        $category = sanitize($_POST['category']);
        $copies = (int)$_POST['copies'];
        $location = sanitize($_POST['location']);
        
        // Basic Validation
        if (empty($isbn) || empty($title)) {
            setFlash('error', 'ISBN and Title are required');
        } else {
            // Check Category exists
            $cat_check = $pdo->prepare("SELECT category_id FROM categories WHERE category_name = ?");
            $cat_check->execute([$category]);
            if ($cat_check->rowCount() == 0) {
                // Insert new category if not exists (Auto-create)
                $pdo->prepare("INSERT INTO categories (category_name) VALUES (?)")->execute([$category]);
                $category_id = $pdo->lastInsertId();
            } else {
                $category_id = $cat_check->fetch()['category_id'];
            }

            try {
                $sql = "INSERT INTO books (isbn, title, author, category_id, total_copies, available_copies, shelf_location) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$isbn, $title, $author, $category_id, $copies, $copies, $location]);
                setFlash('success', 'Book added successfully!');
            } catch (Exception $e) {
                setFlash('error', 'Error adding book: ' . $e->getMessage());
            }
        }
        redirect('admin/manage_books.php');
    }
    
    // Delete Handle
    if (isset($_POST['delete_book'])) {
        $id = (int)$_POST['book_id'];
        try {
            $pdo->prepare("DELETE FROM books WHERE book_id = ?")->execute([$id]);
            setFlash('success', 'Book deleted');
        } catch (Exception $e) {
            setFlash('error', 'Cannot delete book: ' . $e->getMessage());
        }
        redirect('admin/manage_books.php');
    }
}

// Fetch Books
$books = $pdo->query("
    SELECT b.*, c.category_name 
    FROM books b 
    LEFT JOIN categories c ON b.category_id = c.category_id 
    ORDER BY b.created_at DESC
")->fetchAll();

$pageTitle = 'Manage Books';
require_once '../includes/header.php';
?>

<div class="dashboard-layout">
    <?php require_once '../includes/sidebar.php'; ?>
    
    <main class="main-content">
        <header class="flex justify-between items-center" style="margin-bottom: 2rem;">
            <h1>Manage Books</h1>
            <button class="btn btn-primary" onclick="openModal('addBookModal')">
                <i class="fa-solid fa-plus"></i> Add New Book
            </button>
        </header>

        <?php echo getFlash(); ?>

        <div class="card" style="background: var(--card-bg); padding: 1.5rem; border-radius: 1rem; border: 1px solid var(--border-color); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
            <div style="margin-bottom: 1rem; display: flex; gap: 1rem;">
                <input type="text" id="bookSearch" class="form-control" placeholder="Search books..." onkeyup="filterTable('bookTable', 1)">
            </div>
            
            <div style="overflow-x: auto;">
                <table class="data-table" id="bookTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>ISBN</th>
                            <th>Available</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($books as $book): ?>
                        <tr>
                            <td>#<?php echo $book['book_id']; ?></td>
                            <td>
                                <div style="font-weight: 600;"><?php echo htmlspecialchars($book['title']); ?></div>
                            </td>
                            <td><?php echo htmlspecialchars($book['author']); ?></td>
                            <td><span style="background: rgba(99, 102, 241, 0.1); color: var(--primary-color); padding: 2px 8px; border-radius: 4px; font-size: 0.8rem;"><?php echo htmlspecialchars($book['category_name']); ?></span></td>
                            <td><?php echo htmlspecialchars($book['isbn']); ?></td>
                            <td>
                                <span class="<?php echo ($book['available_copies'] > 0) ? 'text-success' : 'text-danger'; ?>" style="font-weight: 600;">
                                    <?php echo $book['available_copies']; ?> / <?php echo $book['total_copies']; ?>
                                </span>
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <button class="btn btn-outline" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <form method="POST" onsubmit="return confirm('Are you sure?');" style="display:inline;">
                                        <input type="hidden" name="delete_book" value="1">
                                        <input type="hidden" name="book_id" value="<?php echo $book['book_id']; ?>">
                                        <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Add Book Modal -->
<div id="addBookModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 100; align-items: center; justify-content: center;">
    <div style="background: var(--card-bg); padding: 2rem; border-radius: 1rem; width: 500px; max-width: 90%; position: relative;" class="slide-up">
        <button onclick="closeModal('addBookModal')" style="position: absolute; top: 1rem; right: 1rem; background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-muted);">&times;</button>
        <h2 style="margin-bottom: 1.5rem;">Add New Book</h2>
        
        <form method="POST">
            <input type="hidden" name="add_book" value="1">
            <div class="form-group">
                <label class="form-label">Book Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="flex gap-4">
                <div class="form-group w-full">
                    <label class="form-label">ISBN</label>
                    <input type="text" name="isbn" class="form-control" required>
                </div>
                <div class="form-group w-full">
                    <label class="form-label">Category</label>
                    <input type="text" name="category" class="form-control" list="catList" required>
                    <datalist id="catList">
                        <option value="Fiction">
                        <option value="Science">
                        <option value="Technology">
                        <option value="History">
                    </datalist>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="form-group w-full">
                    <label class="form-label">Author</label>
                    <input type="text" name="author" class="form-control" required>
                </div>
                <div class="form-group w-full">
                    <label class="form-label">Total Copies</label>
                    <input type="number" name="copies" class="form-control" value="1" min="1" required>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Shelf Location</label>
                <input type="text" name="location" class="form-control" placeholder="e.g. A-12">
            </div>
            
            <button type="submit" class="btn btn-primary w-full justify-center">Add Book</button>
        </form>
    </div>
</div>

<script>
    // Simple Modal Logic
    function openModal(id) {
        document.getElementById(id).style.display = 'flex';
    }
    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }
    
    // Close on click outside
    window.onclick = function(event) {
        if (event.target.id === 'addBookModal') {
            closeModal('addBookModal');
        }
    }

    // Simple Table Filter
    function filterTable(tableId, colIndex) {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("bookSearch");
        filter = input.value.toUpperCase();
        table = document.getElementById(tableId);
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[colIndex]; // Search by Title (Index 1)
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>

<?php require_once '../includes/footer.php'; ?>
