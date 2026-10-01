<?php
require_once 'db_connect.php';
header('Content-Type: application/json');

$id           = isset($_POST['id'])           ? intval($_POST['id'])               : 0;
$display_name = isset($_POST['display_name']) ? trim($_POST['display_name'])        : '';
$description  = isset($_POST['description'])  ? trim($_POST['description'])         : '';

if (!$id || !$display_name) {
    echo json_encode(['success' => false, 'message' => 'Missing fields']);
    exit;
}

$stmt = mysqli_prepare($conn, "UPDATE packages SET display_name = ?, description = ? WHERE id = ?");
mysqli_stmt_bind_param($stmt, "ssi", $display_name, $description, $id);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
