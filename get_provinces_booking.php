<?php
require_once 'db_connect.php';
header('Content-Type: application/json');

$result = mysqli_query($conn, "SELECT id, name, recommended_days FROM provinces ORDER BY name ASC");

$rows = array();
while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
}

echo '[';
for ($i = 0; $i < count($rows); $i++) {
    $r = $rows[$i];
    echo '{';
    echo '"id":' . intval($r['id']) . ',';
    echo '"name":"' . addslashes($r['name']) . '",';
    echo '"recommended_days":' . intval($r['recommended_days']);
    echo '}';
    if ($i < count($rows) - 1) echo ',';
}
echo ']';

mysqli_close($conn);
?>
