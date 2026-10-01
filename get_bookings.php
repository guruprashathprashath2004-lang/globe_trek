<?php
require_once 'db_connect.php';
header('Content-Type: application/json');

$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
$limit   = isset($_GET['limit'])   ? intval($_GET['limit'])   : 0;

$sql = "
    SELECT 
        b.booking_id,
        b.customer_name,
        b.customer_email,
        b.customer_phone,
        b.status,
        b.total_amount,
        b.advance_amount,
        b.start_date,
        b.end_date,
        b.num_days,
        b.adults,
        b.children,
        b.hotel_type,
        b.room_key,
        b.vehicle_key,
        b.meal_key,
        b.guide_included,
        b.payment_method,
        b.booking_date,
        p.name         AS province_name,
        pk.display_name AS package_name
    FROM bookings b
    LEFT JOIN provinces p  ON b.province_id  = p.id
    LEFT JOIN packages  pk ON b.package_name = pk.name
    WHERE 1=1
";

if ($user_id > 0) {
    $sql .= " AND b.user_id = " . intval($user_id);
}

$sql .= " ORDER BY b.booking_date DESC";

if ($limit > 0) {
    $sql .= " LIMIT " . intval($limit);
}

$result = mysqli_query($conn, $sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => mysqli_error($conn)]);
    exit;
}

$rows = [];
while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
}

mysqli_close($conn);
echo json_encode($rows);
?>
