<?php
require_once '../config/config.php';
require_once '../includes/functions.php';
requireAdmin();

// Handle category actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!verifyCSRFToken($_POST[CSRF_TOKEN_NAME] ?? '')) {
        setFlash('error', 'Invalid request.');
        redirect('admin/settings.php');
    }

    // Add Category
    if (isset($_POST['add_category'])) {
        $name = sanitize($_POST['category_name']);
        $desc = sanitize($_POST['description']);

        try {
            $pdo->prepare("INSERT INTO categories (category_name, description) VALUES (?, ?)")->execute([$name, $desc]);
            logActivity('category_add', "Added category: {$name}");
            setFlash('success', "Category '{$name}' added!");
        } catch (PDOException $e) {
            setFlash('error', 'Category already exists.');
        }
        redirect('admin/settings.php');
    }

    // Delete Category
    if (isset($_POST['delete_category'])) {
        $id = (int) $_POST['category_id'];

        $books = $pdo->prepare("SELECT COUNT(*) FROM books WHERE category_id = ?");
        $books->execute([$id]);

        if ($books->fetchColumn() > 0) {
            setFlash('error', 'Cannot delete: Category has books.');
        } else {
            $pdo->prepare("DELETE FROM categories WHERE category_id = ?")->execute([$id]);
            setFlash('success', 'Category deleted.');
        }
        redirect('admin/settings.php');
    }

    // Update System Settings
    if (isset($_POST['update_settings'])) {
        $settings = [
            'library_name' => sanitize($_POST['library_name']),
            'borrow_period_days' => (int) $_POST['borrow_period_days'],
            'fine_per_day' => (float) $_POST['fine_per_day'],
            'max_books_student' => (int) $_POST['max_books_student'],
            'max_books_teacher' => (int) $_POST['max_books_teacher'],
            'allow_overdue_borrow' => isset($_POST['allow_overdue_borrow']) ? '1' : '0'
        ];

        foreach ($settings as $key => $value) {
            $pdo->prepare("
                INSERT INTO system_settings (setting_key, setting_value) 
                VALUES (?, ?) 
                ON DUPLICATE KEY UPDATE setting_value = ?
            ")->execute([$key, $value, $value]);
        }

        logActivity('settings_update', 'Updated system settings');
        setFlash('success', 'Settings saved successfully!');
        redirect('admin/settings.php');
    }
}

// Fetch categories
$categories = $pdo->query("
    SELECT c.*, 
           (SELECT COUNT(*) FROM books WHERE category_id = c.category_id) as book_count
    FROM categories c
    ORDER BY category_name
")->fetchAll();

// Fetch current settings
$settings = [];
$settings_rows = $pdo->query("SELECT setting_key, setting_value FROM system_settings")->fetchAll();
foreach ($settings_rows as $row) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

$pageTitle = 'Settings';
require_once '../includes/header.php';
?>

<div class="dashboard-layout">
    <?php require_once '../includes/sidebar.php'; ?>

    <main class="main-content lg:mt-0 mt-16">
        <header class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white">Settings</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Configure library system</p>
        </header>

        <?php echo getFlash(); ?>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- System Settings -->
            <div class="stat-card" data-aos="fade-up">
                <h3 class="text-lg font-semibold mb-4 dark:text-white">
                    <i class="fa-solid fa-sliders text-primary-500 mr-2"></i>
                    System Settings
                </h3>

                <form method="POST" class="space-y-4">
                    <input type="hidden" name="update_settings" value="1">
                    <?php echo csrfInput(); ?>

                    <div>
                        <label class="form-label">Library Name</label>
                        <input type="text" name="library_name" class="form-control"
                            value="<?php echo e($settings['library_name'] ?? 'Library Management System'); ?>">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Default Borrow Period (days)</label>
                            <input type="number" name="borrow_period_days" class="form-control" min="1" max="90"
                                value="<?php echo $settings['borrow_period_days'] ?? DEFAULT_BORROW_DAYS; ?>">
                        </div>

                        <div>
                            <label class="form-label">Fine Per Day ($)</label>
                            <input type="number" name="fine_per_day" class="form-control" min="0" step="0.5"
                                value="<?php echo $settings['fine_per_day'] ?? DEFAULT_FINE_PER_DAY; ?>">
                        </div>

                        <div>
                            <label class="form-label">Max Books - Student</label>
                            <input type="number" name="max_books_student" class="form-control" min="1" max="20"
                                value="<?php echo $settings['max_books_student'] ?? MAX_BOOKS_STUDENT; ?>">
                        </div>

                        <div>
                            <label class="form-label">Max Books - Teacher</label>
                            <input type="number" name="max_books_teacher" class="form-control" min="1" max="50"
                                value="<?php echo $settings['max_books_teacher'] ?? MAX_BOOKS_TEACHER; ?>">
                        </div>
                    </div>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="allow_overdue_borrow"
                            <?php echo ($settings['allow_overdue_borrow'] ?? '0') === '1' ? 'checked' : ''; ?>
                            class="w-4 h-4 accent-primary-600">
                        <span class="text-sm dark:text-white">Allow borrowing with overdue books</span>
                    </label>

                    <button type="submit" class="btn btn-primary w-full justify-center">
                        <i class="fa-solid fa-save mr-2"></i> Save Settings
                    </button>
                </form>
            </div>

            <!-- Categories -->
            <div class="stat-card" data-aos="fade-up" data-aos-delay="100">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold dark:text-white">
                        <i class="fa-solid fa-tags text-primary-500 mr-2"></i>
                        Categories
                    </h3>
                    <button onclick="openModal('addCategoryModal')" class="btn btn-sm btn-primary">
                        <i class="fa-solid fa-plus"></i> Add
                    </button>
                </div>

                <div class="space-y-2 max-h-96 overflow-y-auto">
                    <?php foreach ($categories as $cat): ?>
                        <div class="flex items-center justify-between bg-gray-50 dark:bg-slate-700 rounded-lg px-4 py-3">
                            <div>
                                <p class="font-medium dark:text-white"><?php echo e($cat['category_name']); ?></p>
                                <p class="text-xs text-gray-500"><?php echo $cat['book_count']; ?> books</p>
                            </div>
                            <?php if ($cat['book_count'] == 0): ?>
                                <form method="POST" class="inline" onsubmit="return confirmDelete('Delete this category?')">
                                    <input type="hidden" name="delete_category" value="1">
                                    <input type="hidden" name="category_id" value="<?php echo $cat['category_id']; ?>">
                                    <?php echo csrfInput(); ?>
                                    <button type="submit" class="text-red-500 hover:text-red-700 p-1">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>

                    <?php if (count($categories) == 0): ?>
                        <p class="text-center text-gray-500 py-8">No categories yet</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Quick Reference -->
            <div class="stat-card lg:col-span-2" data-aos="fade-up" data-aos-delay="200">
                <h3 class="text-lg font-semibold mb-4 dark:text-white">
                    <i class="fa-solid fa-info-circle text-primary-500 mr-2"></i>
                    System Information
                </h3>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div class="bg-gray-50 dark:bg-slate-700 p-4 rounded-lg">
                        <p class="text-gray-500">PHP Version</p>
                        <p class="font-semibold dark:text-white"><?php echo PHP_VERSION; ?></p>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-700 p-4 rounded-lg">
                        <p class="text-gray-500">Database</p>
                        <p class="font-semibold dark:text-white">MySQL</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-700 p-4 rounded-lg">
                        <p class="text-gray-500">Total Books</p>
                        <p class="font-semibold dark:text-white"><?php echo $pdo->query("SELECT COUNT(*) FROM books")->fetchColumn(); ?></p>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-700 p-4 rounded-lg">
                        <p class="text-gray-500">Total Users</p>
                        <p class="font-semibold dark:text-white"><?php echo $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Add Category Modal -->
<div class="modal-overlay" id="addCategoryModal">
    <div class="modal-content max-w-md">
        <button class="modal-close" onclick="closeModal('addCategoryModal')">
            <i class="fa-solid fa-times"></i>
        </button>

        <h3 class="text-xl font-bold mb-4 dark:text-white">Add Category</h3>

        <form method="POST" class="space-y-4">
            <input type="hidden" name="add_category" value="1">
            <?php echo csrfInput(); ?>

            <div>
                <label class="form-label">Category Name *</label>
                <input type="text" name="category_name" class="form-control" required>
            </div>

            <div>
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="2"></textarea>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="btn btn-primary flex-1 justify-center">
                    <i class="fa-solid fa-plus mr-2"></i> Add Category
                </button>
                <button type="button" onclick="closeModal('addCategoryModal')" class="btn btn-outline">Cancel</button>
            </div>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>