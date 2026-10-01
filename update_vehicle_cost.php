<?php
require_once 'db_connect.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo '{"success":false}';
    exit;
}

$id   = intval($_POST['id'] ?? 0);
$cost = intval($_POST['cost'] ?? 0);

if ($id <= 0 || $cost < 0) {
    echo '{"success":false,"error":"Invalid input"}';
    exit;
}

$sql = "UPDATE vehicles SET cost = $cost WHERE id = $id";

if (mysqli_query($conn, $sql)) {
    echo '{"success":true}';
} else {
    echo '{"success":false,"error":"' . addslashes(mysqli_error($conn)) . '"}';
}

mysqli_close($conn);
?>
