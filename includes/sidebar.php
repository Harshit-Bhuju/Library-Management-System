<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo flex items-center justify-between">
        <div class="flex items-center gap-3">
            <i class="fa-solid fa-book-open"></i>
            <span>LMS Portal</span>
        </div>
        <button id="sidebarClose" class="lg:hidden p-2 text-gray-400 hover:text-white">
            <i class="fa-solid fa-times text-xl"></i>
        </button>
    </div>

    <nav>
        <?php if (isAdmin()): ?>
            <!-- Admin Links -->
            <p class="nav-section-title">Main Menu</p>

            <a href="<?php echo BASE_URL; ?>admin/dashboard.php" class="nav-link <?php echo (currentPage() == 'dashboard.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-gauge-high"></i> Dashboard
            </a>
            <a href="<?php echo BASE_URL; ?>admin/analytics.php" class="nav-link <?php echo (currentPage() == 'analytics.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-chart-line"></i> Analytics
            </a>

            <p class="nav-section-title">Library</p>

            <a href="<?php echo BASE_URL; ?>admin/manage_books.php" class="nav-link <?php echo (currentPage() == 'manage_books.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-book"></i> Manage Books
            </a>
            <a href="<?php echo BASE_URL; ?>admin/manage_students.php" class="nav-link <?php echo (currentPage() == 'manage_students.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-users"></i> Manage Users
            </a>
            <a href="<?php echo BASE_URL; ?>admin/manage_reviews.php" class="nav-link <?php echo (currentPage() == 'manage_reviews.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-message"></i> Manage Reviews
            </a>

            <p class="nav-section-title">Transactions</p>

            <a href="<?php echo BASE_URL; ?>admin/manage_requests.php" class="nav-link <?php echo (currentPage() == 'manage_requests.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-clipboard-check"></i> Book Requests
            </a>
            <a href="<?php echo BASE_URL; ?>admin/issue_book.php" class="nav-link <?php echo (currentPage() == 'issue_book.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-hand-holding-hand"></i> Issue Book
            </a>
            <a href="<?php echo BASE_URL; ?>admin/return_book.php" class="nav-link <?php echo (currentPage() == 'return_book.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-rotate-left"></i> Return Book
            </a>
            <a href="<?php echo BASE_URL; ?>admin/borrow_history.php" class="nav-link <?php echo (currentPage() == 'borrow_history.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-history"></i> Borrow History
            </a>
            <a href="<?php echo BASE_URL; ?>admin/transaction_history.php" class="nav-link <?php echo (currentPage() == 'transaction_history.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-file-invoice-dollar"></i> Fine Payments
            </a>

        <?php else: ?>
            <!-- Student Links -->
            <p class="nav-section-title">Main Menu</p>

            <a href="<?php echo BASE_URL; ?>student/dashboard.php" class="nav-link <?php echo (currentPage() == 'dashboard.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-gauge-high"></i> Dashboard
            </a>

            <p class="nav-section-title">Library</p>

            <a href="<?php echo BASE_URL; ?>student/browse_books.php" class="nav-link <?php echo (currentPage() == 'browse_books.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-magnifying-glass"></i> Browse Books
            </a>
            <a href="<?php echo BASE_URL; ?>student/borrow_history.php" class="nav-link <?php echo (currentPage() == 'borrow_history.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-clock-rotate-left"></i> My History
            </a>
            <a href="<?php echo BASE_URL; ?>student/transaction_history.php" class="nav-link <?php echo (currentPage() == 'transaction_history.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-receipt"></i> My Transactions
            </a>

            <p class="nav-section-title">Account</p>

            <!-- Notification Link Removed -->
            <a href="<?php echo BASE_URL; ?>student/profile.php" class="nav-link <?php echo (currentPage() == 'profile.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-user"></i> My Profile
            </a>
        <?php endif; ?>

        <!-- Common Links for All Logged In Users -->
        <div class="mt-auto pt-4 border-t border-gray-700">
            <!-- About Us -->
            <a href="<?php echo BASE_URL; ?>about.php" class="nav-link <?php echo (currentPage() == 'about.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-circle-info"></i> About Us
            </a>

            <!-- Theme Toggle -->
            <button onclick="ThemeManager.toggle()" class="nav-link w-full text-left">
                <i class="fa-solid fa-moon dark:hidden"></i>
                <i class="fa-solid fa-sun hidden dark:inline"></i>
                <span class="dark:hidden">Dark Mode</span>
                <span class="hidden dark:inline">Light Mode</span>
            </button>

            <!-- Logout -->
            <a href="<?php echo BASE_URL; ?>auth/logout.php" class="nav-link text-red-400 hover:text-red-300 hover:bg-red-900/20">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </div>
    </nav>

    <!-- User Info at Bottom -->
    <?php if (isLoggedIn()): ?>
        <div class="mt-4 pt-4 border-t border-gray-700">
            <div class="flex items-center gap-3 px-2">
                <?php echo getAvatar($_SESSION['name'], 36); ?>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate"><?php echo e($_SESSION['name']); ?></p>
                    <p class="text-xs text-gray-400 truncate"><?php echo ucfirst($_SESSION['role']); ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>
</aside>
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>