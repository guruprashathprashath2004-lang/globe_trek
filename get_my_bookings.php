<?php
require_once 'db_connect.php';
header('Content-Type: application/json');

$email = mysqli_real_escape_string($conn, trim($_GET['email'] ?? ''));

if (empty($email)) {
    echo '[]';
    exit;
}

$sql = "SELECT b.booking_id, b.status, b.total_amount, b.num_days, b.package_name, b.receipt_file,
               p.name AS province_name
        FROM bookings b
        LEFT JOIN provinces p ON b.province_id = p.id
        WHERE b.customer_email = '$email'
        ORDER BY b.booking_date DESC";

$result = mysqli_query($conn, $sql);

$rows = array();
while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
}

echo '[';
for ($i = 0; $i < count($rows); $i++) {
    $r = $rows[$i];
    echo '{';
    echo '"booking_id":"' . addslashes($r['booking_id']) . '",';
    echo '"status":"' . addslashes($r['status']) . '",';
    echo '"total_amount":' . intval($r['total_amount']) . ',';
    echo '"num_days":' . intval($r['num_days']) . ',';
    echo '"package_name":"' . addslashes($r['package_name']) . '",';
    echo '"receipt_file":"' . addslashes($r['receipt_file'] ?? '') . '",';
    echo '"province_name":"' . addslashes($r['province_name'] ?? '') . '"';
    echo '}';
    if ($i < count($rows) - 1) echo ',';
}
echo ']';

mysqli_close($conn);
?>
