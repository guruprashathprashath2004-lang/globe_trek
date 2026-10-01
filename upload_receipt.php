<?php
require_once 'db_connect.php';
header('Content-Type: application/json');

$booking_id = isset($_POST['booking_id']) ? trim($_POST['booking_id']) : '';

if (!$booking_id || !isset($_FILES['receipt'])) {
    echo json_encode(['success' => false, 'message' => 'Missing data']);
    exit;
}

$file    = $_FILES['receipt'];
$maxSize = 5 * 1024 * 1024;
$allowed = ['image/jpeg', 'image/png', 'application/pdf'];

if ($file['size'] > $maxSize) {
    echo json_encode(['success' => false, 'message' => 'File too large (max 5MB)']);
    exit;
}
if (!in_array($file['type'], $allowed)) {
    echo json_encode(['success' => false, 'message' => 'Invalid file type']);
    exit;
}

// Create receipts folder if not exists
$uploadDir = __DIR__ . '/receipts/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = 'REC_' . $booking_id . '_' . time() . '.' . $ext;
$dest     = $uploadDir . $filename;

if (!move_uploaded_file($file['tmp_name'], $dest)) {
    echo json_encode(['success' => false, 'message' => 'Upload failed']);
    exit;
}

// Store in receipts table
$orig_name = $file['name'];
$file_size = $file['size'];

$stmt = mysqli_prepare($conn,
    "INSERT INTO receipts (booking_id, filename, original_name, file_size, uploaded_by)
     VALUES (?, ?, ?, ?, 'staff')
     ON DUPLICATE KEY UPDATE
       filename = VALUES(filename),
       original_name = VALUES(original_name),
       file_size = VALUES(file_size),
       uploaded_at = CURRENT_TIMESTAMP"
);
mysqli_stmt_bind_param($stmt, "sssi", $booking_id, $filename, $orig_name, $file_size);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

mysqli_close($conn);

echo json_encode(['success' => true, 'filename' => $filename]);
?>
