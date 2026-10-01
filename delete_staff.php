<?php
require_once 'db_connect.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo '{"success":false}';
    exit;
}

$id = intval($_POST['id'] ?? 0);

if ($id <= 0) {
    echo '{"success":false,"error":"Invalid ID"}';
    exit;
}

$sql = "DELETE FROM users WHERE id = $id AND role IN ('staff','admin')";

if (mysqli_query($conn, $sql)) {
    echo '{"success":true}';
} else {
    echo '{"success":false,"error":"' . addslashes(mysqli_error($conn)) . '"}';
}

mysqli_close($conn);
?>
