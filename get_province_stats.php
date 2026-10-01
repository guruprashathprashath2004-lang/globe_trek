<?php
require_once 'db_connect.php';
header('Content-Type: application/json');

$sql = "SELECT p.name AS province_name, COUNT(b.id) AS count
        FROM bookings b
        LEFT JOIN provinces p ON b.province_id = p.id
        GROUP BY b.province_id, p.name
        ORDER BY count DESC
        LIMIT 9";

$result = mysqli_query($conn, $sql);

$rows = array();
while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
}

echo '[';
for ($i = 0; $i < count($rows); $i++) {
    $r = $rows[$i];
    echo '{';
    echo '"province_name":"' . addslashes($r['province_name']) . '",';
    echo '"count":' . intval($r['count']);
    echo '}';
    if ($i < count($rows) - 1) echo ',';
}
echo ']';

mysqli_close($conn);
?>
