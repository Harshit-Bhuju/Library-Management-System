<?php
require_once '../config/config.php';
require_once '../includes/functions.php';
requireLogin();

$user_id = $_SESSION['user_id'];

// Fetch Stats
$borrowed_count = $pdo->prepare("SELECT COUNT(*) FROM issued_books WHERE user_id = ? AND status = 'issued'");
$borrowed_count->execute([$user_id]);
$current_borrows = $borrowed_count->fetchColumn();

$returned_count = $pdo->prepare("SELECT COUNT(*) FROM issued_books WHERE user_id = ? AND status = 'returned'");
$returned_count->execute([$user_id]);
$total_read = $returned_count->fetchColumn();

$fines_query = $pdo->prepare("SELECT SUM(fine_amount) FROM issued_books WHERE user_id = ?");
$fines_query->execute([$user_id]);
$total_fines = $fines_query->fetchColumn() ?: 0.00;

// Fetch Currently Issued Books
$stmt = $pdo->prepare("
    SELECT i.*, b.title, b.author, b.cover_image, DATEDIFF(CURDATE(), i.due_date) as days_overdue
    FROM issued_books i
    JOIN books b ON i.book_id = b.book_id
    WHERE i.user_id = ? AND i.status != 'returned'
    ORDER BY i.due_date ASC
");
$stmt->execute([$user_id]);
$my_books = $stmt->fetchAll();

$pageTitle = 'Student Dashboard';
require_once '../includes/header.php';
?>

<div class="dashboard-layout">
    <?php require_once '../includes/sidebar.php'; ?>
    
    <main class="main-content">
        <header class="flex justify-between items-center" style="margin-bottom: 2rem;">
            <div>
                <h1 style="font-size: 1.8rem; font-weight: 700;">My Dashboard</h1>
                <p style="color: var(--text-muted);">Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></p>
            </div>
            <div class="user-profile flex items-center gap-2">
                <div style="width: 40px; height: 40px; background: var(--primary-color); border-radius: 50%; color: white; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                    S
                </div>
            </div>
        </header>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card" data-aos="fade-up" data-aos-delay="100">
                <div class="flex justify-between items-center">
                    <div>
                        <p style="color: var(--text-muted); font-size: 0.9rem;">Current Borrows</p>
                        <h2 style="font-size: 2rem; font-weight: 700; margin: 0.5rem 0;"><?php echo $current_borrows; ?></h2>
                    </div>
                    <div style="width: 50px; height: 50px; background: rgba(99, 102, 241, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--primary-color); font-size: 1.5rem;">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card" data-aos="fade-up" data-aos-delay="200">
                <div class="flex justify-between items-center">
                    <div>
                        <p style="color: var(--text-muted); font-size: 0.9rem;">Books Read</p>
                        <h2 style="font-size: 2rem; font-weight: 700; margin: 0.5rem 0;"><?php echo $total_read; ?></h2>
                    </div>
                    <div style="width: 50px; height: 50px; background: rgba(16, 185, 129, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--success); font-size: 1.5rem;">
                        <i class="fa-solid fa-check-circle"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card" data-aos="fade-up" data-aos-delay="300">
                <div class="flex justify-between items-center">
                    <div>
                        <p style="color: var(--text-muted); font-size: 0.9rem;">Pending Fines</p>
                        <h2 style="font-size: 2rem; font-weight: 700; margin: 0.5rem 0; color: var(--danger);">$<?php echo $total_fines; ?></h2>
                    </div>
                    <div style="width: 50px; height: 50px; background: rgba(239, 68, 68, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--danger); font-size: 1.5rem;">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                </div>
            </div>
        </div>

        <h3 style="margin-bottom: 1rem; font-size: 1.25rem;">Currently Issued Books</h3>
        <div class="grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
            <?php if(count($my_books) > 0): ?>
                <?php foreach($my_books as $book): ?>
                    <div class="stat-card" data-aos="fade-up">
                        <div class="flex gap-4">
                            <div style="width: 80px; height: 110px; background: #ddd; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; color: #888;">
                                <i class="fa-solid fa-book fa-2x"></i>
                            </div>
                            <div class="flex-1">
                                <h4 style="font-weight: 600; margin-bottom: 0.25rem;"><?php echo htmlspecialchars($book['title']); ?></h4>
                                <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0.5rem;"><?php echo htmlspecialchars($book['author']); ?></p>
                                
                                <div style="font-size: 0.85rem; margin-bottom: 0.25rem;">
                                    Issued: <span style="font-weight: 500;"><?php echo formatDate($book['issue_date']); ?></span>
                                </div>
                                <div style="font-size: 0.85rem;">
                                    Due: <span style="font-weight: 500; <?php echo ($book['days_overdue'] > 0) ? 'color: var(--danger);' : ''; ?>">
                                        <?php echo formatDate($book['due_date']); ?>
                                        <?php if($book['days_overdue'] > 0) echo "(Overdue!)"; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: var(--text-muted);">
                    <i class="fa-solid fa-book-open" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                    <p>You haven't borrowed any books yet.</p>
                    <a href="browse_books.php" class="btn btn-primary" style="margin-top: 1rem;">Browse Library</a>
                </div>
            <?php endif; ?>
        </div>

    </main>
</div>

<?php require_once '../includes/footer.php'; ?>
