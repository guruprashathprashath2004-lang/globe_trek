<?php
require_once 'db_connect.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo '{"success":false}';
    exit;
}

$name         = mysqli_real_escape_string($conn, trim($_POST['name'] ?? ''));
$display_name = mysqli_real_escape_string($conn, trim($_POST['display_name'] ?? ''));
$description  = mysqli_real_escape_string($conn, trim($_POST['description'] ?? ''));

if (empty($name) || empty($display_name)) {
    echo '{"success":false,"error":"Name required"}';
    exit;
}

$sql = "INSERT INTO packages (name, display_name, description) VALUES ('$name','$display_name','$description')";

if (mysqli_query($conn, $sql)) {
    echo '{"success":true,"id":' . mysqli_insert_id($conn) . '}';
} else {
    echo '{"success":false,"error":"' . addslashes(mysqli_error($conn)) . '"}';
}

mysqli_close($conn);
?>
