<?php
require_once 'db_connect.php';
header('Content-Type: application/json');

$sql = "
    SELECT 
        b.booking_id,
        b.customer_name,
        b.customer_email,
        b.total_amount,
        b.payment_method,
        b.status        AS booking_status,
        COALESCE(SUM(pay.amount), 0)                          AS advance_paid,
        b.total_amount - COALESCE(SUM(pay.amount), 0)         AS due_amount,
        CASE 
            WHEN b.total_amount - COALESCE(SUM(pay.amount), 0) <= 0 
            THEN 'Paid'
            ELSE 'Pending Payment'
        END                                                    AS payment_status
    FROM bookings b
    LEFT JOIN payments pay ON b.booking_id = pay.booking_id
    GROUP BY 
        b.booking_id,
        b.customer_name,
        b.customer_email,
        b.total_amount,
        b.payment_method,
        b.status
    ORDER BY b.booking_date DESC
";

$result = mysqli_query($conn, $sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => mysqli_error($conn)]);
    exit;
}

$rows = [];
while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = [
        'booking_id'      => $row['booking_id'],
        'customer_name'   => $row['customer_name'],
        'customer_email'  => $row['customer_email'],
        'payment_method'  => $row['payment_method'],
        'total_amount'    => (int)$row['total_amount'],
        'advance_paid'    => (int)$row['advance_paid'],
        'due_amount'      => (int)$row['due_amount'],
        'booking_status'  => $row['booking_status'],
        'payment_status'  => $row['payment_status'],
    ];
}

mysqli_close($conn);
echo json_encode($rows);
?>