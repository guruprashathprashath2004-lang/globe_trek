<?php
require_once 'db_connect.php';
header('Content-Type: application/json');

$r1 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM bookings");
$total = mysqli_fetch_assoc($r1)['total'];

$r2 = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM bookings WHERE status = 'pending'");
$pending = mysqli_fetch_assoc($r2)['cnt'];

$r3 = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM bookings WHERE status = 'confirmed' AND MONTH(booking_date) = MONTH(NOW()) AND YEAR(booking_date) = YEAR(NOW())");
$confirmed = mysqli_fetch_assoc($r3)['cnt'];

$r4 = mysqli_query($conn, "SELECT SUM(total_amount) AS rev FROM bookings WHERE status IN ('confirmed','completed')");
$revenue = mysqli_fetch_assoc($r4)['rev'];

echo '{';
echo '"total":' . intval($total) . ',';
echo '"pending":' . intval($pending) . ',';
echo '"confirmed":' . intval($confirmed) . ',';
echo '"revenue":' . intval($revenue);
echo '}';

mysqli_close($conn);
?>
