<?php
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo 'Invalid request';
    exit;
}

$username = trim($_POST['username'] ?? '');
$email    = trim($_POST['email']    ?? '');
$phone    = trim($_POST['phone']    ?? '');
$password =       $_POST['password'] ?? '';

// Basic server-side validation
if (empty($username) || empty($email) || empty($password)) {
    echo 'All required fields must be filled.';
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo 'Invalid email address.';
    exit;
}

if (strlen($password) < 6) {
    echo 'Password must be at least 6 characters.';
    exit;
}

// Check if email already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->close();
    echo 'An account with this email already exists.';
    exit;
}
$stmt->close();

// Hash the password
$hashed = password_hash($password, PASSWORD_DEFAULT);

// Insert — role is always 'customer' for self-registration
$stmt = $conn->prepare(
    "INSERT INTO users (username, email, phone, password, role) VALUES (?, ?, ?, ?, 'customer')"
);
$stmt->bind_param("ssss", $username, $email, $phone, $hashed);

if ($stmt->execute()) {
    $stmt->close();
    echo 'success';
} else {
    $stmt->close();
    echo 'Registration failed. Please try again.';
}
?>
