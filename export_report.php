<?php
require_once 'db_connect.php';

$period = isset($_GET['period']) ? $_GET['period'] : 'month';

$where = '';
switch ($period) {
    case 'month':
        $where = "WHERE b.booking_date >= DATE_FORMAT(NOW(), '%Y-%m-01')";
        break;
    case 'quarter':
        $where = "WHERE b.booking_date >= DATE_SUB(NOW(), INTERVAL 3 MONTH)";
        break;
    case 'year':
        $where = "WHERE YEAR(b.booking_date) = YEAR(NOW())";
        break;
    case 'all':
    default:
        $where = '';
        break;
}

$sql = "SELECT 
            b.booking_id,
            b.customer_name,
            b.customer_email,
            b.customer_phone,
            p.name AS province_name,
            b.package_name,
            b.vehicle_key,
            b.meal_key,
            b.room_key,
            b.hotel_type,
            b.adults,
            b.children,
            b.start_date,
            b.end_date,
            b.num_days,
            b.guide_included,
            b.payment_method,
            b.total_amount,
            b.advance_amount,
            b.status,
            b.booking_date
        FROM bookings b
        LEFT JOIN provinces p ON b.province_id = p.id
        $where
        ORDER BY b.booking_date DESC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die('Query failed: ' . mysqli_error($conn));
}

$filename = 'GlobeTrek_Report_' . strtoupper($period) . '_' . date('Ymd_His') . '.csv';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');

$output = fopen('php://output', 'w');

// Company header rows
fputcsv($output, ['GlobeTrek Adventures']);
fputcsv($output, ['Negombo, Sri Lanka']);
fputcsv($output, ['Email: info@globetrek.lk  |  Phone: +94 31 222 0000']);
fputcsv($output, ['']);
fputcsv($output, ['Booking Report - Period: ' . strtoupper($period)]);
fputcsv($output, ['Generated On: ' . date('Y-m-d H:i:s')]);
fputcsv($output, ['']);

// Column headers
fputcsv($output, [
    'Booking ID',
    'Customer Name',
    'Email',
    'Phone',
    'Province',
    'Package',
    'Vehicle',
    'Meal Plan',
    'Room Type',
    'Hotel Type',
    'Adults',
    'Children',
    'Start Date',
    'End Date',
    'Days',
    'Tour Guide',
    'Payment Method',
    'Total Amount (LKR)',
    'Advance (LKR)',
    'Status',
    'Booking Date'
]);

$total_revenue = 0;
$total_bookings = 0;

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [
        $row['booking_id'],
        $row['customer_name'],
        $row['customer_email'],
        $row['customer_phone'],
        $row['province_name'],
        $row['package_name'],
        $row['vehicle_key'],
        $row['meal_key'],
        $row['room_key'],
        $row['hotel_type'],
        $row['adults'],
        $row['children'],
        $row['start_date'],
        $row['end_date'],
        $row['num_days'],
        ($row['guide_included'] ? 'Yes' : 'No'),
        $row['payment_method'],
        $row['total_amount'],
        $row['advance_amount'],
        $row['status'],
        $row['booking_date']
    ]);
    if ($row['status'] !== 'cancelled') {
        $total_revenue += (int)$row['total_amount'];
    }
    $total_bookings++;
}

// Summary rows
fputcsv($output, ['']);
fputcsv($output, ['--- SUMMARY ---']);
fputcsv($output, ['Total Bookings', $total_bookings]);
fputcsv($output, ['Total Revenue (LKR)', $total_revenue]);

fclose($output);
mysqli_close($conn);
exit;
?>
