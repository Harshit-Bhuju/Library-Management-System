<?php
require_once '../config/config.php';
require_once '../includes/functions.php';

$book_id = (int)($_GET['book_id'] ?? 0);

if ($book_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid book ID.']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT r.*, u.name as reviewer_name 
        FROM reviews r
        JOIN users u ON r.user_id = u.user_id
        WHERE r.book_id = ?
        ORDER BY r.created_at DESC
    ");
    $stmt->execute([$book_id]);
    $reviews = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'reviews' => $reviews,
        'user_review' => array_values(array_filter($reviews, fn($r) => $r['user_id'] == ($_SESSION['user_id'] ?? 0)))[0] ?? null
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error.']);
}
