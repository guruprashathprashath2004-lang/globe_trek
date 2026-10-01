<?php
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Method not allowed';
    exit;
}

$id    = isset($_POST['id'])    ? (int)$_POST['id']    : 0;
$price = isset($_POST['price']) ? (int)$_POST['price'] : 0;

if (!$id || $price <= 0) {
    http_response_code(400);
    echo 'Invalid id or price';
    exit;
}

$sql = "UPDATE hotel_district SET price = $price WHERE id = $id";
$result = mysqli_query($conn, $sql);

if (!$result) {
    http_response_code(500);
    echo 'Update failed: ' . mysqli_error($conn);
    exit;
}

mysqli_close($conn);
header('Content-Type: application/json');
echo '{"success":true}';
exit;
?>
