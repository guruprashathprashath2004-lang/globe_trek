<?php
include "db_connect.php";
 
if (!isset($_GET['district_id'])) {
    echo "";
    exit;
}
 
$district_id = intval($_GET['district_id']);
 
$sql = "
SELECT hotel_name
FROM hotel_district
WHERE district_id = $district_id
";
 
$result = $conn->query($sql);
 
while ($row = $result->fetch_assoc()) {
    echo $row['hotel_name'] . "\n";
}
?>