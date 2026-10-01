<?php
require_once 'db_connect.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo '{"success":false}';
    exit;
}

$id   = intval($_POST['id'] ?? 0);
$role = mysqli_real_escape_string($conn, trim($_POST['role'] ?? ''));

$allowed = array('admin', 'staff', 'customer');
if ($id <= 0 || !in_array($role, $allowed)) {
    echo '{"success":false,"error":"Invalid input"}';
    exit;
}

$sql = "UPDATE users SET role = '$role' WHERE id = $id";

if (mysqli_query($conn, $sql)) {
    echo '{"success":true}';
} else {
    echo '{"success":false,"error":"' . addslashes(mysqli_error($conn)) . '"}';
}

mysqli_close($conn);
?>
