<?php
require_once '../config/config.php';
require_once '../includes/functions.php';
requireLogin();

// Fetch Books (Simple fetch all for now, can add server-side search later)
$stmt = $pdo->prepare("
    SELECT b.*, c.category_name 
    FROM books b 
    LEFT JOIN categories c ON b.category_id = c.category_id 
    ORDER BY b.title ASC
");
$stmt->execute();
$books = $stmt->fetchAll();

$pageTitle = 'Browse Books';
require_once '../includes/header.php';
?>

<div class="dashboard-layout">
    <?php require_once '../includes/sidebar.php'; ?>
    
    <main class="main-content">
        <header style="margin-bottom: 2rem;">
            <h1>Browse Library</h1>
        </header>

        <div class="card" style="background: var(--card-bg); padding: 1.5rem; border-radius: 1rem; margin-bottom: 2rem;">
            <div class="flex gap-4">
                <input type="text" id="searchInput" class="form-control" placeholder="Search by title, author, or ISBN..." style="max-width: 400px;">
                <select id="categoryFilter" class="form-control" style="max-width: 200px;">
                    <option value="">All Categories</option>
                    <!-- Categories would be dynamic -->
                    <option value="Fiction">Fiction</option>
                    <option value="Science">Science</option>
                </select>
            </div>
        </div>

        <div class="grid" id="bookGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1.5rem;">
            <?php foreach($books as $book): ?>
                 <div class="stat-card book-item" data-title="<?php echo strtolower($book['title']); ?>" data-author="<?php echo strtolower($book['author']); ?>" data-category="<?php echo strtolower($book['category_name'] ?? ''); ?>" style="padding: 1rem; display: flex; flex-direction: column; height: 100%;">
                    <div style="height: 250px; background: #eee; border-radius: 0.5rem; margin-bottom: 1rem; display: flex; align-items: center; justify-content: center; color: #999;">
                        <?php if($book['cover_image']): ?>
                             <img src="<?php echo BASE_URL . 'uploads/book_covers/' . $book['cover_image']; ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 0.5rem;">
                        <?php else: ?>
                            <i class="fa-solid fa-book fa-3x"></i>
                        <?php endif; ?>
                    </div>
                    
                    <h3 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 0.25rem; line-height: 1.4;"><?php echo htmlspecialchars($book['title']); ?></h3>
                    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0.5rem;"><?php echo htmlspecialchars($book['author']); ?></p>
                    
                    <div style="margin-top: auto; padding-top: 1rem;">
                        <div class="flex justify-between items-center mb-2">
                             <span style="font-size: 0.8rem; background: rgba(99, 102, 241, 0.1); color: var(--primary-color); padding: 2px 6px; border-radius: 4px;">
                                <?php echo htmlspecialchars($book['category_name']); ?>
                            </span>
                             <span style="font-size: 0.8rem; font-weight: 600; <?php echo ($book['available_copies'] > 0) ? 'color: var(--success);' : 'color: var(--danger);'; ?>">
                                <?php echo ($book['available_copies'] > 0) ? 'Available' : 'Out of Stock'; ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const bookItems = document.querySelectorAll('.book-item');

    searchInput.addEventListener('keyup', (e) => {
        const term = e.target.value.toLowerCase();
        
        bookItems.forEach(item => {
            const title = item.dataset.title;
            const author = item.dataset.author;
            
            if(title.includes(term) || author.includes(term)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    });
</script>

<?php require_once '../includes/footer.php'; ?>
