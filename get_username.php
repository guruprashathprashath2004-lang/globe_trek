<?php
session_start();

$host = 'localhost';
$dbname = 'globe_trek';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    echo "Guest";
    exit;
}

$email = isset($_GET['email']) ? trim($_GET['email']) : '';

if (empty($email)) {
    echo "Guest";
    exit;
}

$stmt = $conn->prepare("SELECT username FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo $user['username'];
} else {
    // If no username found, use email prefix
    $parts = explode('@', $email);
    echo $parts[0];
}

$stmt->close();
$conn->close();
?>