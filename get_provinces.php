<?php
include "db_connect.php";
 
$sql = "SELECT id, name FROM provinces ORDER BY id";
$result = $conn->query($sql);
 
while ($row = $result->fetch_assoc()) {
    echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
}
?>