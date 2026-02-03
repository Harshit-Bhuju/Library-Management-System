<?php
require_once '../config/config.php';
require_once '../includes/functions.php';

// Ensure user is logged in
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_id = isset($_POST['book_id']) ? (int) $_POST['book_id'] : 0;
    $user_id = $_SESSION['user_id'];

    if (!$book_id) {
        echo json_encode(['success' => false, 'message' => 'Invalid book ID']);
        exit;
    }

    // Check if user already requested or has this book
    $query = $pdo->prepare("SELECT status FROM issued_books WHERE user_id = ? AND book_id = ? AND status IN ('issued', 'requested', 'overdue')");
    $query->execute([$user_id, $book_id]);

    if ($query->rowCount() > 0) {
        echo json_encode(['success' => false, 'message' => 'You have already requested or currently have this book.']);
        exit;
    }

    // Check availability
    $book = $pdo->prepare("SELECT available_copies FROM books WHERE book_id = ?");
    $book->execute([$book_id]);
    $bookData = $book->fetch();

    if ($bookData['available_copies'] <= 0) {
        echo json_encode(['success' => false, 'message' => 'Book is currently unavailable.']);
        exit;
    }

    // Check borrow limit
    if (!canBorrowMore($user_id)) {
        $limit = getBorrowLimit();
        echo json_encode([
            'success' => false,
            'message' => "You already booked {$limit} books. You have to finish the due of the oldest book you booked."
        ]);
        exit;
    }

    try {
        $pdo->beginTransaction();

        // Insert request
        // issue_date is today, but status is 'requested', so it is not yet "issued" in terms of possession
        $stmt = $pdo->prepare("INSERT INTO issued_books (book_id, user_id, issue_date, due_date, status) VALUES (?, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 15 DAY), 'requested')");
        $stmt->execute([$book_id, $user_id]);

        $pdo->commit();

        logActivity('book_request', "Requested book ID: $book_id");

        echo json_encode(['success' => true, 'message' => 'Book requested successfully! Please visit the library to collect it.']);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
