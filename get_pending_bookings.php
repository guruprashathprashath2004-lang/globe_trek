<?php
require_once 'db_connect.php';
header('Content-Type: application/json');

$sql = "SELECT b.booking_id, b.customer_name, b.customer_email, b.customer_phone, b.package_name,
               p.name AS province_name
        FROM bookings b
        LEFT JOIN provinces p ON b.province_id = p.id
        WHERE b.status = 'pending'
        ORDER BY b.booking_date ASC";

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
    echo '"customer_name":"' . addslashes($r['customer_name']) . '",';
    echo '"customer_email":"' . addslashes($r['customer_email']) . '",';
    echo '"customer_phone":"' . addslashes($r['customer_phone'] ?? '') . '",';
    echo '"package_name":"' . addslashes($r['package_name']) . '",';
    echo '"province_name":"' . addslashes($r['province_name'] ?? '') . '"';
    echo '}';
    if ($i < count($rows) - 1) echo ',';
}
echo ']';

mysqli_close($conn);
?>
