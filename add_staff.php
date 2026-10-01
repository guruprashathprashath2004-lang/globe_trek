<?php
require_once 'db_connect.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo '{"success":false}';
    exit;
}

$username = mysqli_real_escape_string($conn, trim($_POST['username'] ?? ''));
$email    = mysqli_real_escape_string($conn, trim($_POST['email'] ?? ''));
$phone    = mysqli_real_escape_string($conn, trim($_POST['phone'] ?? ''));
$role     = mysqli_real_escape_string($conn, trim($_POST['role'] ?? 'staff'));
$password = trim($_POST['password'] ?? '');

$allowed_roles = array('admin', 'staff');
if (!in_array($role, $allowed_roles)) {
    $role = 'staff';
}

if (empty($username) || empty($email) || empty($password)) {
    echo '{"success":false,"error":"Required fields missing"}';
    exit;
}

$hashed = password_hash($password, PASSWORD_DEFAULT);
$hashed_safe = mysqli_real_escape_string($conn, $hashed);

$sql = "INSERT INTO users (username, email, phone, password, role) VALUES ('$username','$email','$phone','$hashed_safe','$role')";

if (mysqli_query($conn, $sql)) {
    echo '{"success":true,"id":' . mysqli_insert_id($conn) . '}';
} else {
    echo '{"success":false,"error":"' . addslashes(mysqli_error($conn)) . '"}';
}

mysqli_close($conn);
?>
