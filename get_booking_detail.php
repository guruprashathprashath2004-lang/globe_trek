<?php
require_once 'db_connect.php';
header('Content-Type: application/json');

$booking_id = mysqli_real_escape_string($conn, trim($_GET['booking_id'] ?? ''));

if (empty($booking_id)) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT b.*, p.name AS province_name, pk.display_name AS package_name
        FROM bookings b 
        LEFT JOIN provinces p  ON b.province_id = p.id
        LEFT JOIN packages  pk ON b.package_name = pk.name
        WHERE b.booking_id = '$booking_id' 
        LIMIT 1";

$result = mysqli_query($conn, $sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => mysqli_error($conn)]);
    exit;
}

$r = mysqli_fetch_assoc($result);

if (!$r) {
    echo json_encode([]);
    exit;
}

// Cast numeric fields
$numeric = ['id','user_id','province_id','adults','children','num_days','guide_included','total_amount','advance_amount'];
foreach ($numeric as $f) {
    if (isset($r[$f])) {
        $r[$f] = (int)$r[$f];
    }
}

mysqli_close($conn);
echo json_encode($r);
?>
