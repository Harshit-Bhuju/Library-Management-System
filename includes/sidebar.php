<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <i class="fa-solid fa-book-open"></i>
        <span>LMS Portal</span>
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

            <p class="nav-section-title">Transactions</p>

            <a href="<?php echo BASE_URL; ?>admin/issue_book.php" class="nav-link <?php echo (currentPage() == 'issue_book.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-hand-holding-hand"></i> Issue Book
            </a>
            <a href="<?php echo BASE_URL; ?>admin/return_book.php" class="nav-link <?php echo (currentPage() == 'return_book.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-rotate-left"></i> Return Book
            </a>

        <?php elseif (isTeacher()): ?>
            <!-- Teacher Links -->
            <p class="nav-section-title">Main Menu</p>

            <a href="<?php echo BASE_URL; ?>teacher/dashboard.php" class="nav-link <?php echo (currentPage() == 'dashboard.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-gauge-high"></i> Dashboard
            </a>

            <p class="nav-section-title">Library</p>

            <a href="<?php echo BASE_URL; ?>teacher/browse_books.php" class="nav-link <?php echo (currentPage() == 'browse_books.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-magnifying-glass"></i> Browse Books
            </a>
            <a href="<?php echo BASE_URL; ?>teacher/issue_book.php" class="nav-link <?php echo (currentPage() == 'issue_book.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-hand-holding-hand"></i> Issue Book
            </a>

            <p class="nav-section-title">Account</p>

            <a href="<?php echo BASE_URL; ?>teacher/my_books.php" class="nav-link <?php echo (currentPage() == 'my_books.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-book-open"></i> My Books
            </a>
            <a href="<?php echo BASE_URL; ?>teacher/profile.php" class="nav-link <?php echo (currentPage() == 'profile.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-user"></i> My Profile
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

            <p class="nav-section-title">Account</p>

            <a href="<?php echo BASE_URL; ?>student/notifications.php" class="nav-link <?php echo (currentPage() == 'notifications.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-bell"></i> Notifications
                <?php
                $notifCount = getUnreadNotificationCount();
                if ($notifCount > 0):
                ?>
                    <span class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full"><?php echo $notifCount; ?></span>
                <?php endif; ?>
            </a>
            <a href="<?php echo BASE_URL; ?>student/profile.php" class="nav-link <?php echo (currentPage() == 'profile.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-user"></i> My Profile
            </a>
        <?php endif; ?>

        <!-- Common Links for All Logged In Users -->
        <div class="mt-auto pt-4 border-t border-gray-700">
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