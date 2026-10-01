<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include "db_connect.php";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'DB connection failed: ' . $e->getMessage()]);
    exit;
}

$action = $_GET['action'] ?? '';

// ── Process Payment ────────────────────────────────────────────
if ($action === 'processPayment' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $booking_id      = trim($_POST['booking_id'] ?? '');
    $cardholder_name = trim($_POST['cardholder_name'] ?? '');
    $card_number     = trim($_POST['card_number'] ?? '');   // Only last 4 digits stored
    $expiry          = trim($_POST['expiry'] ?? '');
    $amount          = intval($_POST['amount'] ?? 0);
    $payment_type    = trim($_POST['payment_type'] ?? 'advance'); // 'advance' or 'full'
    $status          = 'paid';

    // Validate required fields
    if (!$booking_id || !$cardholder_name || !$card_number || !$expiry || $amount <= 0) {
        echo json_encode(['success' => false, 'message' => 'Missing required payment fields.']);
        exit;
    }

    // Check booking exists
    $stmt = $pdo->prepare("SELECT id FROM bookings WHERE booking_id = ?");
    $stmt->execute([$booking_id]);
    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Booking not found.']);
        exit;
    }

    // Generate transaction ID
    $transaction_id = 'TXN-' . strtoupper(base_convert(time() . rand(100, 999), 10, 36));

    try {
    // Insert into payments table
    $ins = $pdo->prepare("
        INSERT INTO payments 
            (booking_id, cardholder_name, card_number, expiry, amount, status, paid_at)
        VALUES 
            (:booking_id, :cardholder_name, :card_number, :expiry, :amount, :status, NOW())
    ");
    $ins->execute([
        ':booking_id'      => $booking_id,
        ':cardholder_name' => $cardholder_name,
        ':card_number'     => $card_number,
        ':expiry'          => $expiry,
        ':amount'          => $amount,
        ':status'          => $status,
    ]);

    echo json_encode([
        'success'        => true,
        'transaction_id' => $transaction_id,
        'message'        => 'Payment recorded successfully.'
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Payment insert failed: ' . $e->getMessage()]);
}
    exit;
}

// ── Unknown action ─────────────────────────────────────────────
echo json_encode(['success' => false, 'message' => 'Unknown action.']);
exit;
?>
