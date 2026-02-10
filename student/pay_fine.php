<?php
require_once '../config/config.php';
require_once '../includes/functions.php';
requireLogin();

if (!isset($_GET['issue_id'])) {
    setFlash('error', 'Issue ID missing.');
    redirect('student/borrow_history.php');
}

$issue_id = (int) $_GET['issue_id'];
$user_id = $_SESSION['user_id'];

// Get issue details
$stmt = $pdo->prepare("
    SELECT i.*, b.title
    FROM issued_books i
    JOIN books b ON i.book_id = b.book_id
    WHERE i.issue_id = ? AND i.user_id = ? AND i.fine_paid = 0 AND i.fine_amount > 0 AND i.status != 'returned'
");
$stmt->execute([$issue_id, $user_id]);
$issue = $stmt->fetch();

if (!$issue) {
    setFlash('error', 'No pending fine found for this book.');
    redirect('student/borrow_history.php');
}

$amount = $issue['fine_amount'];
$amount_form = number_format($amount, 1, '.', ''); // e.g. 150.0
$transaction_uuid = uniqid('FINE-') . '-' . $issue_id . '-' . time();
$product_code = ESEWA_MERCHANT_CODE;

// Generate signature for eSewa v2
// data = "total_amount=value,transaction_uuid=value,product_code=value"
$s_data = "total_amount={$amount_form},transaction_uuid={$transaction_uuid},product_code={$product_code}";
$s_hash = hash_hmac('sha256', $s_data, ESEWA_SECRET_KEY, true);
$signature = base64_encode($s_hash);

// Record pending transaction
$stmt = $pdo->prepare("INSERT INTO transactions (user_id, issue_id, amount, transaction_uuid, status) VALUES (?, ?, ?, ?, 'pending')");
$stmt->execute([$user_id, $issue_id, $amount, $transaction_uuid]);

// URLs
$success_url = BASE_URL . "api/esewa_success.php";
$failure_url = BASE_URL . "api/esewa_failure.php";

?>
<!DOCTYPE html>
<html>

<head>
    <title>Redirecting to eSewa...</title>
</head>

<body onload="document.getElementById('esewaForm').submit();">
    <p>Redirecting to eSewa...</p>
    <form action="<?php echo ESEWA_URL; ?>" method="POST" id="esewaForm">
        <input type="hidden" id="amount" name="amount" value="<?php echo $amount_form; ?>" required>
        <input type="hidden" id="tax_amount" name="tax_amount" value="0" required>
        <input type="hidden" id="total_amount" name="total_amount" value="<?php echo $amount_form; ?>" required>
        <input type="hidden" id="transaction_uuid" name="transaction_uuid" value="<?php echo $transaction_uuid; ?>" required>
        <input type="hidden" id="product_code" name="product_code" value="<?php echo $product_code; ?>" required>
        <input type="hidden" id="product_service_charge" name="product_service_charge" value="0" required>
        <input type="hidden" id="product_delivery_charge" name="product_delivery_charge" value="0" required>
        <input type="hidden" id="success_url" name="success_url" value="<?php echo $success_url; ?>" required>
        <input type="hidden" id="failure_url" name="failure_url" value="<?php echo $failure_url; ?>" required>
        <input type="hidden" id="signed_field_names" name="signed_field_names" value="total_amount,transaction_uuid,product_code" required>
        <input type="hidden" id="signature" name="signature" value="<?php echo $signature; ?>" required>
    </form>
</body>

</html>