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
    $issue_id = isset($_POST['issue_id']) ? (int) $_POST['issue_id'] : 0;
    $user_id = $_SESSION['user_id'];

    if (!$issue_id) {
        echo json_encode(['success' => false, 'message' => 'Invalid Request ID']);
        exit;
    }

    try {
        // Verify ownership and status
        $stmt = $pdo->prepare("SELECT status FROM issued_books WHERE issue_id = ? AND user_id = ?");
        $stmt->execute([$issue_id, $user_id]);
        $request = $stmt->fetch();

        if (!$request) {
            echo json_encode(['success' => false, 'message' => 'Request not found or access denied.']);
            exit;
        }

        if ($request['status'] !== 'requested') {
            echo json_encode(['success' => false, 'message' => 'Only pending requests can be cancelled.']);
            exit;
        }

        // Update status to cancelled
        $stmt = $pdo->prepare("UPDATE issued_books SET status = 'cancelled' WHERE issue_id = ? AND user_id = ?");
        $stmt->execute([$issue_id, $user_id]);

        logActivity('book_request_cancel', "Cancelled request ID: $issue_id");

        echo json_encode(['success' => true, 'message' => 'Request cancelled successfully.']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
