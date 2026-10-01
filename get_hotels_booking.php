<?php
require_once 'db_connect.php';
header('Content-Type: application/json');

$province_id = isset($_GET['province_id']) ? intval($_GET['province_id']) : 0;

if ($province_id <= 0) {
    echo '[]';
    exit;
}

$sql = "SELECT hd.id, hd.hotel_name, hd.hotel_type, hd.price, 
               d.name AS district_name
        FROM hotel_district hd
        JOIN districts d ON hd.district_id = d.id
        WHERE d.province_id = " . $province_id . "
        ORDER BY d.name ASC, hd.hotel_name ASC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode(['error' => mysqli_error($conn)]);
    exit;
}

$rows = [];
while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = [
        'id'            => (int)$row['id'],
        'hotel_name'    => $row['hotel_name'],
        'hotel_type'    => $row['hotel_type'],
        'price'         => (int)$row['price'],
        'district_name' => $row['district_name']
    ];
}

echo json_encode($rows);
mysqli_close($conn);
?>