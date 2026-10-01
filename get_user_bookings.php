<?php
require_once 'db_connect.php';
header('Content-Type: application/json');

$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

if (!$user_id) {
    echo json_encode([]);
    exit;
}

// user_id is null in bookings — so get the username from users table
// then match against customer_name OR customer_email in bookings
$user_result = mysqli_query($conn, "SELECT username, email FROM users WHERE id = $user_id");

if (!$user_result || mysqli_num_rows($user_result) === 0) {
    echo json_encode([]);
    exit;
}

$user = mysqli_fetch_assoc($user_result);
$username = mysqli_real_escape_string($conn, $user['username']);
$email    = mysqli_real_escape_string($conn, $user['email']);

// Match by customer_name OR customer_email
$sql = "
    SELECT 
        b.booking_id,
        b.customer_name,
        b.customer_email,
        b.total_amount,
        b.status,
        b.start_date,
        b.end_date,
        p.name AS province_name
    FROM bookings b
    LEFT JOIN provinces p ON b.province_id = p.id
    WHERE b.customer_name = '$username'
       OR b.customer_email = '$email'
    ORDER BY b.booking_date DESC
";

$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode(['error' => mysqli_error($conn)]);
    exit;
}

$rows = [];
while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
}

mysqli_close($conn);
echo json_encode($rows);
?>
