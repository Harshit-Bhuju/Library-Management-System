<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <i class="fa-solid fa-book-open"></i> LMS Portal
    </div>
    
    <nav>
        <?php if(isAdmin()): ?>
            <!-- Admin Links -->
            <a href="<?php echo BASE_URL; ?>admin/dashboard.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-gauge-high"></i> Dashboard
            </a>
            <a href="<?php echo BASE_URL; ?>admin/manage_books.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'manage_books.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-book"></i> Manage Books
            </a>
            <a href="<?php echo BASE_URL; ?>admin/manage_students.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'manage_students.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-users"></i> Students
            </a>
            <a href="<?php echo BASE_URL; ?>admin/issue_book.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'issue_book.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-hand-holding-hand"></i> Issue Book
            </a>
            <a href="<?php echo BASE_URL; ?>admin/return_book.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'return_book.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-rotate-left"></i> Return Book
            </a>
        <?php else: ?>
            <!-- Student Links -->
            <a href="<?php echo BASE_URL; ?>student/dashboard.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-gauge-high"></i> Dashboard
            </a>
            <a href="<?php echo BASE_URL; ?>student/browse_books.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'browse_books.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-magnifying-glass"></i> Browse Books
            </a>
            <a href="<?php echo BASE_URL; ?>student/borrow_history.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'borrow_history.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-clock-rotate-left"></i> My History
            </a>
            <a href="<?php echo BASE_URL; ?>student/profile.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'profile.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-user"></i> My Profile
            </a>
        <?php endif; ?>

        <a href="<?php echo BASE_URL; ?>auth/logout.php" class="nav-link" style="margin-top: 2rem; color: var(--danger);">
            <i class="fa-solid fa-right-from-bracket"></i> Logout
        </a>
    </nav>
</aside>
