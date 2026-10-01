<?php
require_once 'db_connect.php';
header('Content-Type: application/json');

$result = mysqli_query($conn, "SELECT id, username, email, phone, role, created_at FROM users ORDER BY created_at DESC");

$rows = array();
while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
}

echo '[';
for ($i = 0; $i < count($rows); $i++) {
    $r = $rows[$i];
    echo '{';
    echo '"id":' . intval($r['id']) . ',';
    echo '"username":"' . addslashes($r['username']) . '",';
    echo '"email":"' . addslashes($r['email']) . '",';
    echo '"phone":"' . addslashes($r['phone'] ?? '') . '",';
    echo '"role":"' . addslashes($r['role']) . '",';
    echo '"created_at":"' . addslashes($r['created_at']) . '"';
    echo '}';
    if ($i < count($rows) - 1) echo ',';
}
echo ']';

mysqli_close($conn);
?>
