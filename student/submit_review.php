<?php
require_once '../config/config.php';
require_once '../includes/functions.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $book_id = (int)($_POST['book_id'] ?? 0);
    $rating = (int)($_POST['rating'] ?? 0);
    $review_text = sanitize($_POST['review_text'] ?? '');

    if ($book_id <= 0 || $rating < 1 || $rating > 5) {
        echo json_encode(['success' => false, 'message' => 'Invalid data provided.']);
        exit;
    }

    try {
        // Check if user already reviewed this book
        $check = $pdo->prepare("SELECT review_id FROM reviews WHERE user_id = ? AND book_id = ?");
        $check->execute([$user_id, $book_id]);

        if ($check->rowCount() > 0) {
            // Update existing review
            $stmt = $pdo->prepare("UPDATE reviews SET rating = ?, review_text = ?, created_at = NOW() WHERE user_id = ? AND book_id = ?");
            $stmt->execute([$rating, $review_text, $user_id, $book_id]);
            $message = 'Review updated successfully!';
        } else {
            // Insert new review
            $stmt = $pdo->prepare("INSERT INTO reviews (user_id, book_id, rating, review_text) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user_id, $book_id, $rating, $review_text]);
            $message = 'Review submitted successfully!';
        }

        logActivity('book_review', "Reviewed book ID: $book_id with $rating stars");
        echo json_encode(['success' => true, 'message' => $message]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
