<?php
require_once '../config/config.php';
require_once '../includes/functions.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $book_id = (int)($_POST['book_id'] ?? 0);

    if ($book_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid book ID.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM reviews WHERE user_id = ? AND book_id = ?");
        $stmt->execute([$user_id, $book_id]);

        if ($stmt->rowCount() > 0) {
            logActivity('review_delete', "Deleted review for book ID: $book_id");
            echo json_encode(['success' => true, 'message' => 'Review deleted successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Review not found.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
