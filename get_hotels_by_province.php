<?php
include "db_connect.php";
 
if (!isset($_GET['province_id'])) {
    echo "";
    exit;
}
 
$province_id = intval($_GET['province_id']);
 
$sql = "
SELECT hd.hotel_name
FROM hotel_district hd
JOIN districts d ON hd.district_id = d.id
WHERE d.province_id = $province_id
";
 
$result = $conn->query($sql);
 
while ($row = $result->fetch_assoc()) {
    echo $row['hotel_name'] . "\n";
}
?>