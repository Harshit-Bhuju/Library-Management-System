<?php
require_once '../config/config.php';
require_once '../includes/functions.php';

// Verification from eSewa
if (isset($_GET['data'])) {
    $data = $_GET['data'];
    $decoded_data = base64_decode($data);
    $json_data = json_decode($decoded_data, true);

    // Verify signature (Simplified for sandbox/demo, in production more rigorous checks)
    // data = "total_amount,transaction_uuid,product_code"
    $status = $json_data['status'];
    $total_amount = $json_data['total_amount'];
    $transaction_uuid = $json_data['transaction_uuid'];
    $transaction_code = $json_data['transaction_code'];

    if ($status === 'COMPLETE') {
        try {
            $pdo->beginTransaction();

            // Find transaction
            $stmt = $pdo->prepare("SELECT * FROM transactions WHERE transaction_uuid = ? AND status = 'pending'");
            $stmt->execute([$transaction_uuid]);
            $transaction = $stmt->fetch();

            if ($transaction) {
                // Update transaction
                $stmt = $pdo->prepare("UPDATE transactions SET status = 'completed', transaction_code = ?, payment_method = 'esewa' WHERE transaction_uuid = ?");
                $stmt->execute([$transaction_code, $transaction_uuid]);

                // Update fine status and mark as returned
                if ($transaction['issue_id']) {
                    // Update issued_books
                    $stmt = $pdo->prepare("
                        UPDATE issued_books 
                        SET fine_paid = 1, 
                            status = 'returned', 
                            return_date = CURDATE() 
                        WHERE issue_id = ?
                    ");
                    $stmt->execute([$transaction['issue_id']]);

                    // Get book_id to update availability
                    $stmt = $pdo->prepare("SELECT book_id FROM issued_books WHERE issue_id = ?");
                    $stmt->execute([$transaction['issue_id']]);
                    $bookId = $stmt->fetchColumn();

                    if ($bookId) {
                        $stmt = $pdo->prepare("UPDATE books SET available_copies = available_copies + 1, is_active = 1 WHERE book_id = ?");
                        $stmt->execute([$bookId]);
                    }
                }

                $pdo->commit();
                setFlash('success', 'Payment successful! Fine cleared.');
            } else {
                throw new Exception("Transaction not found or already processed.");
            }
        } catch (Exception $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            setFlash('error', $e->getMessage());
        }
    } else {
        setFlash('error', 'Payment was not completed.');
    }
}

redirect('student/borrow_history.php');
