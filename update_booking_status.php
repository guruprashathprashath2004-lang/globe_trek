<?php
require_once 'db_connect.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo '{"success":false}';
    exit;
}

$booking_id = mysqli_real_escape_string($conn, trim($_POST['booking_id'] ?? ''));
$status     = mysqli_real_escape_string($conn, trim($_POST['status'] ?? ''));

$allowed = array('pending', 'confirmed', 'cancelled', 'completed');
if (empty($booking_id) || !in_array($status, $allowed)) {
    echo '{"success":false,"error":"Invalid input"}';
    exit;
}

$sql = "UPDATE bookings SET status = '$status' WHERE booking_id = '$booking_id'";

if (mysqli_query($conn, $sql)) {
    echo '{"success":true}';
} else {
    echo '{"success":false,"error":"' . addslashes(mysqli_error($conn)) . '"}';
}

mysqli_close($conn);
?>
