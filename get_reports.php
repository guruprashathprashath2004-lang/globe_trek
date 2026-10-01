<?php
require_once 'db_connect.php';
header('Content-Type: application/json');

$period = trim($_GET['period'] ?? 'month');

$date_filter = "";
if ($period === 'month') {
    $date_filter = "AND MONTH(booking_date) = MONTH(NOW()) AND YEAR(booking_date) = YEAR(NOW())";
} elseif ($period === 'quarter') {
    $date_filter = "AND booking_date >= DATE_SUB(NOW(), INTERVAL 3 MONTH)";
} elseif ($period === 'year') {
    $date_filter = "AND YEAR(booking_date) = YEAR(NOW())";
} else {
    $date_filter = "";
}

// Totals
$r1 = mysqli_query($conn, "SELECT COUNT(*) AS cnt, SUM(total_amount) AS rev FROM bookings WHERE 1=1 $date_filter");
$totals = mysqli_fetch_assoc($r1);

$r2 = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM bookings WHERE status = 'cancelled' $date_filter");
$cancelled = mysqli_fetch_assoc($r2)['cnt'];

$total_bookings = intval($totals['cnt']);
$total_revenue  = intval($totals['rev']);
$avg_value      = $total_bookings > 0 ? round($total_revenue / $total_bookings) : 0;
$cancel_rate    = $total_bookings > 0 ? round(($cancelled / $total_bookings) * 100) : 0;

// By Province
$r3 = mysqli_query($conn, "SELECT p.name AS province_name, SUM(b.total_amount) AS revenue
      FROM bookings b LEFT JOIN provinces p ON b.province_id = p.id
      WHERE 1=1 $date_filter GROUP BY b.province_id, p.name ORDER BY revenue DESC LIMIT 9");

$by_prov = array();
while ($row = mysqli_fetch_assoc($r3)) {
    $by_prov[] = $row;
}

// By Package
$r4 = mysqli_query($conn, "SELECT package_name, COUNT(*) AS count
      FROM bookings WHERE 1=1 $date_filter GROUP BY package_name ORDER BY count DESC");

$by_pkg = array();
while ($row = mysqli_fetch_assoc($r4)) {
    $by_pkg[] = $row;
}

// Booking list
$r5 = mysqli_query($conn, "SELECT b.booking_id, b.customer_name, b.total_amount, b.status, b.package_name, b.booking_date, p.name AS province_name
      FROM bookings b LEFT JOIN provinces p ON b.province_id = p.id
      WHERE 1=1 $date_filter ORDER BY b.booking_date DESC LIMIT 50");

$bookings = array();
while ($row = mysqli_fetch_assoc($r5)) {
    $bookings[] = $row;
}

// Build output manually
echo '{';
echo '"total_revenue":' . $total_revenue . ',';
echo '"total_bookings":' . $total_bookings . ',';
echo '"avg_value":' . $avg_value . ',';
echo '"cancel_rate":' . $cancel_rate . ',';

echo '"by_province":[';
for ($i = 0; $i < count($by_prov); $i++) {
    $r = $by_prov[$i];
    echo '{"province_name":"' . addslashes($r['province_name']) . '","revenue":' . intval($r['revenue']) . '}';
    if ($i < count($by_prov) - 1) echo ',';
}
echo '],';

echo '"by_package":[';
for ($i = 0; $i < count($by_pkg); $i++) {
    $r = $by_pkg[$i];
    echo '{"package_name":"' . addslashes($r['package_name']) . '","count":' . intval($r['count']) . '}';
    if ($i < count($by_pkg) - 1) echo ',';
}
echo '],';

echo '"bookings":[';
for ($i = 0; $i < count($bookings); $i++) {
    $b = $bookings[$i];
    echo '{';
    echo '"booking_id":"' . addslashes($b['booking_id']) . '",';
    echo '"customer_name":"' . addslashes($b['customer_name']) . '",';
    echo '"province_name":"' . addslashes($b['province_name'] ?? '') . '",';
    echo '"package_name":"' . addslashes($b['package_name']) . '",';
    echo '"total_amount":' . intval($b['total_amount']) . ',';
    echo '"status":"' . addslashes($b['status']) . '",';
    echo '"booking_date":"' . addslashes($b['booking_date']) . '"';
    echo '}';
    if ($i < count($bookings) - 1) echo ',';
}
echo ']';

echo '}';

mysqli_close($conn);
?>
