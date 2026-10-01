<?php
require_once 'db_connect.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid method']);
    exit;
}

$booking_id     = mysqli_real_escape_string($conn, trim($_POST['booking_id']     ?? ''));
$customer_name  = mysqli_real_escape_string($conn, trim($_POST['customer_name']  ?? ''));
$customer_email = mysqli_real_escape_string($conn, trim($_POST['customer_email'] ?? ''));
$customer_phone = mysqli_real_escape_string($conn, trim($_POST['customer_phone'] ?? ''));
$province_id    = intval($_POST['province_id']    ?? 0);
$package_name   = mysqli_real_escape_string($conn, trim($_POST['package_name']   ?? ''));
$vehicle_key    = mysqli_real_escape_string($conn, trim($_POST['vehicle_key']    ?? ''));
$meal_key       = mysqli_real_escape_string($conn, trim($_POST['meal_key']       ?? 'bo'));
$room_key       = mysqli_real_escape_string($conn, trim($_POST['room_key']       ?? 'single'));
$hotel_type     = mysqli_real_escape_string($conn, trim($_POST['hotel_type']     ?? 'normal'));
$adults         = intval($_POST['adults']         ?? 1);
$children       = intval($_POST['children']       ?? 0);
$start_date     = mysqli_real_escape_string($conn, trim($_POST['start_date']     ?? ''));
$end_date       = mysqli_real_escape_string($conn, trim($_POST['end_date']       ?? ''));
$num_days       = intval($_POST['num_days']       ?? 0);
$guide_included = intval($_POST['guide_included'] ?? 0);
$payment_method = mysqli_real_escape_string($conn, trim($_POST['payment_method'] ?? 'cash'));
$total_amount   = intval($_POST['total_amount']   ?? 0);
$advance_amount = intval($_POST['advance_amount'] ?? 0);

$user_id = isset($_POST['user_id']) && intval($_POST['user_id']) > 0
           ? intval($_POST['user_id'])
           : 'NULL';

if (empty($booking_id) || empty($customer_name) || empty($customer_email)) {
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

$start_val = !empty($start_date) ? "'" . $start_date . "'" : "NULL";
$end_val   = !empty($end_date)   ? "'" . $end_date   . "'" : "NULL";

$sql = "INSERT INTO bookings 
        (booking_id, user_id, customer_name, customer_email, customer_phone, 
         province_id, package_name, vehicle_key, meal_key, room_key, 
         hotel_type, adults, children, start_date, end_date, num_days, 
         guide_included, payment_method, total_amount, advance_amount, status) 
        VALUES 
        ('$booking_id', $user_id, '$customer_name', '$customer_email', '$customer_phone',
         $province_id, '$package_name', '$vehicle_key', '$meal_key', '$room_key',
         '$hotel_type', $adults, $children, $start_val, $end_val, $num_days,
         $guide_included, '$payment_method', $total_amount, $advance_amount, 'pending')";

if (mysqli_query($conn, $sql)) {
    echo json_encode(['success' => true, 'booking_id' => $booking_id]);
} else {
    echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
}

mysqli_close($conn);
?>