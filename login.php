<?php
header('Content-Type: application/json');
session_start();
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}

$email    = trim($_POST['email']    ?? '');
$password =      $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    echo json_encode(['status' => 'error', 'message' => 'Email and password are required.']);
    exit;
}

$stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($id, $username, $hashed, $role);
$stmt->fetch();
$stmt->close();

if (!$id) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid email or password.']);
    exit;
}

if (!password_verify($password, $hashed)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid email or password.']);
    exit;
}

$_SESSION['user_id']  = $id;
$_SESSION['username'] = $username;
$_SESSION['role']     = $role;
$_SESSION['email']    = $email;

echo json_encode([
    'status'   => 'success',
    'role'     => $role,
    'username' => $username,
    'email'    => $email,
    'user_id'  => $id      
]);
?>