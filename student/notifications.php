<?php
require_once '../config/config.php';
require_once '../includes/functions.php';
requireLogin();

$user_id = $_SESSION['user_id'];

// Handle notification actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!verifyCSRFToken($_POST[CSRF_TOKEN_NAME] ?? '')) {
        setFlash('error', 'Invalid request.');
        redirect('student/notifications.php');
    }

    if (isset($_POST['mark_read'])) {
        $notif_id = (int) $_POST['notification_id'];
        $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE notification_id = ? AND user_id = ?")->execute([$notif_id, $user_id]);
        echo json_encode(['success' => true]);
        exit;
    }

    if (isset($_POST['mark_all_read'])) {
        $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?")->execute([$user_id]);
        setFlash('success', 'All notifications marked as read.');
        redirect('student/notifications.php');
    }

    if (isset($_POST['delete_notification'])) {
        $notif_id = (int) $_POST['notification_id'];
        $pdo->prepare("DELETE FROM notifications WHERE notification_id = ? AND user_id = ?")->execute([$notif_id, $user_id]);
        setFlash('success', 'Notification deleted.');
        redirect('student/notifications.php');
    }
}

// Fetch notifications
$stmt = $pdo->prepare("
    SELECT * FROM notifications 
    WHERE user_id = ? 
    ORDER BY created_at DESC
");
$stmt->execute([$user_id]);
$notifications = $stmt->fetchAll();

$unread_count = count(array_filter($notifications, fn($n) => !$n['is_read']));

$pageTitle = 'Notifications';
require_once '../includes/header.php';
?>

<div class="dashboard-layout">
    <?php require_once '../includes/sidebar.php'; ?>

    <main class="main-content lg:mt-0 mt-16">
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white">Notifications</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">
                    <?php if ($unread_count > 0): ?>
                        <span class="text-primary-600 font-medium"><?php echo $unread_count; ?> unread</span> notifications
                    <?php else: ?>
                        All caught up!
                    <?php endif; ?>
                </p>
            </div>

            <?php if ($unread_count > 0): ?>
                <form method="POST" class="inline">
                    <input type="hidden" name="mark_all_read" value="1">
                    <?php echo csrfInput(); ?>
                    <button type="submit" class="btn btn-outline">
                        <i class="fa-solid fa-check-double"></i> Mark All Read
                    </button>
                </form>
            <?php endif; ?>
        </header>

        <?php echo getFlash(); ?>

        <div class="stat-card" data-aos="fade-up">
            <?php if (count($notifications) > 0): ?>
                <div class="divide-y divide-gray-100 dark:divide-slate-700">
                    <?php foreach ($notifications as $notif): ?>
                        <div class="p-4 flex gap-4 <?php echo !$notif['is_read'] ? 'bg-primary-50 dark:bg-primary-900/10' : ''; ?> hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center <?php
                                                                                                    echo match ($notif['notification_type']) {
                                                                                                        'success' => 'bg-green-100 dark:bg-green-900/30 text-green-600',
                                                                                                        'warning' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600',
                                                                                                        'danger' => 'bg-red-100 dark:bg-red-900/30 text-red-600',
                                                                                                        default => 'bg-blue-100 dark:bg-blue-900/30 text-blue-600'
                                                                                                    };
                                                                                                    ?>">
                                    <i class="fa-solid <?php
                                                        echo match ($notif['notification_type']) {
                                                            'success' => 'fa-check-circle',
                                                            'warning' => 'fa-exclamation-triangle',
                                                            'danger' => 'fa-exclamation-circle',
                                                            default => 'fa-info-circle'
                                                        };
                                                        ?>"></i>
                                </div>
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <h4 class="font-semibold text-sm dark:text-white <?php echo !$notif['is_read'] ? '' : 'opacity-80'; ?>">
                                            <?php echo e($notif['title']); ?>
                                            <?php if (!$notif['is_read']): ?>
                                                <span class="inline-block w-2 h-2 bg-primary-500 rounded-full ml-2"></span>
                                            <?php endif; ?>
                                        </h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1"><?php echo e($notif['message']); ?></p>
                                    </div>

                                    <form method="POST" class="flex-shrink-0">
                                        <input type="hidden" name="delete_notification" value="1">
                                        <input type="hidden" name="notification_id" value="<?php echo $notif['notification_id']; ?>">
                                        <?php echo csrfInput(); ?>
                                        <button type="submit" class="text-gray-400 hover:text-red-500 transition p-1" title="Delete">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>

                                <div class="flex items-center gap-3 mt-2">
                                    <span class="text-xs text-gray-400">
                                        <i class="fa-solid fa-clock mr-1"></i>
                                        <?php echo formatDateTime($notif['created_at']); ?>
                                    </span>

                                    <?php if ($notif['link']): ?>
                                        <a href="<?php echo BASE_URL . $notif['link']; ?>" class="text-xs text-primary-600 hover:underline">
                                            View Details →
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-16">
                    <i class="fa-solid fa-bell-slash text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
                    <p class="text-gray-500 dark:text-gray-400">No notifications yet</p>
                    <p class="text-sm text-gray-400 mt-1">You'll see notifications here when you have borrowing activity</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php require_once '../includes/footer.php'; ?>