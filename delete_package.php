<?php
require_once 'db_connect.php';
header('Content-Type: application/json');

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'Invalid ID']);
    exit;
}

$stmt = mysqli_prepare($conn, "DELETE FROM packages WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
