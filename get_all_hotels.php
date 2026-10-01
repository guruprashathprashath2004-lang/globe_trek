<?php
require_once 'db_connect.php';

$province_filter = '';
$type_filter = '';

if (!empty($_GET['province'])) {
    $prov = mysqli_real_escape_string($conn, $_GET['province']);
    $province_filter = "AND p.name = '$prov'";
}

if (!empty($_GET['type'])) {
    $type = mysqli_real_escape_string($conn, $_GET['type']);
    $type_filter = "AND h.hotel_type = '$type'";
}

$sql = "SELECT 
            h.id,
            h.hotel_name,
            h.hotel_type,
            h.price,
            d.name AS district_name,
            p.name AS province_name
        FROM hotel_district h
        LEFT JOIN districts d ON h.district_id = d.id
        LEFT JOIN provinces p ON d.province_id = p.id
        WHERE 1=1
        $province_filter
        $type_filter
        ORDER BY p.name, d.name, h.hotel_name";

$result = mysqli_query($conn, $sql);

if (!$result) {
    http_response_code(500);
    echo '[]';
    exit;
}

$hotels = array();

while ($row = mysqli_fetch_assoc($result)) {
    $hotels[] = array(
        'id'            => (int)$row['id'],
        'hotel_name'    => $row['hotel_name'],
        'hotel_type'    => $row['hotel_type'],
        'price'         => (int)$row['price'],
        'district_name' => $row['district_name'],
        'province_name' => $row['province_name']
    );
}

mysqli_close($conn);

header('Content-Type: application/json');
echo json_encode($hotels);
exit;
?>
