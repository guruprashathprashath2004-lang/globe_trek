<?php
include "db_connect.php";
 
if (!isset($_GET['province_id'])) {
    echo "";
    exit;
}
 
$province_id = intval($_GET['province_id']);
 
$sql = "SELECT id, name FROM districts WHERE province_id = $province_id ORDER BY id";
$result = $conn->query($sql);
 
while ($row = $result->fetch_assoc()) {
    echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
}
?>