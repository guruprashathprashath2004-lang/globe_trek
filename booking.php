<?php
// booking.php
// GlobeTrek - Database read and write file
// Use your existing dbconnect.php for connection

require_once 'db_connect.php'; // Your existing DB connection file

// Get the action from URL
$action = '';
if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

// =============================================
// ACTION: Get all provinces
// =============================================
if ($action == 'getProvinces') {

    $sql = "SELECT id, name, recommended_days FROM provinces ORDER BY id";
    $result = mysqli_query($conn, $sql);

    $provinces = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $provinces[] = $row;
    }

    header('Content-Type: application/json');
    echo '[';
    $first = true;
    foreach ($provinces as $p) {
        if (!$first) echo ',';
        echo '{"id":"' . $p['id'] . '","name":"' . addslashes($p['name']) . '","recommended_days":"' . $p['recommended_days'] . '"}';
        $first = false;
    }
    echo ']';
    exit;
}

// =============================================
// ACTION: Get all packages
// =============================================
if ($action == 'getPackages') {

    $sql = "SELECT id, name, display_name, description FROM packages ORDER BY id";
    $result = mysqli_query($conn, $sql);

    $packages = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $packages[] = $row;
    }

    header('Content-Type: application/json');
    echo '[';
    $first = true;
    foreach ($packages as $p) {
        if (!$first) echo ',';
        $desc = addslashes($p['description']);
        $disp = addslashes($p['display_name']);
        echo '{"id":"' . $p['id'] . '","name":"' . $p['name'] . '","display_name":"' . $disp . '","description":"' . $desc . '"}';
        $first = false;
    }
    echo ']';
    exit;
}

// =============================================
// ACTION: Get vehicles by package
// =============================================
if ($action == 'getVehicles') {

    $package = '';
    if (isset($_GET['package'])) {
        $package = mysqli_real_escape_string($conn, $_GET['package']);
    }

    $sql = "SELECT id, package_name, vehicle_key, vehicle_label, cost FROM vehicles WHERE package_name = '" . $package . "'";
    $result = mysqli_query($conn, $sql);

    $vehicles = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $vehicles[] = $row;
    }

    header('Content-Type: application/json');
    echo '[';
    $first = true;
    foreach ($vehicles as $v) {
        if (!$first) echo ',';
        $label = addslashes($v['vehicle_label']);
        echo '{"id":"' . $v['id'] . '","vehicle_key":"' . $v['vehicle_key'] . '","vehicle_label":"' . $label . '","cost":"' . $v['cost'] . '"}';
        $first = false;
    }
    echo ']';
    exit;
}

// =============================================
// ACTION: Get meal plans
// =============================================
if ($action == 'getMealPlans') {

    $sql = "SELECT id, meal_key, meal_name, cost_per_person FROM meal_plans ORDER BY id";
    $result = mysqli_query($conn, $sql);

    $meals = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $meals[] = $row;
    }

    header('Content-Type: application/json');
    echo '[';
    $first = true;
    foreach ($meals as $m) {
        if (!$first) echo ',';
        $name = addslashes($m['meal_name']);
        echo '{"id":"' . $m['id'] . '","meal_key":"' . $m['meal_key'] . '","meal_name":"' . $name . '","cost_per_person":"' . $m['cost_per_person'] . '"}';
        $first = false;
    }
    echo ']';
    exit;
}

// =============================================
// ACTION: Get room types
// =============================================
if ($action == 'getRoomTypes') {

    $sql = "SELECT id, room_key, room_name, multiplier FROM room_types ORDER BY id";
    $result = mysqli_query($conn, $sql);

    $rooms = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $rooms[] = $row;
    }

    header('Content-Type: application/json');
    echo '[';
    $first = true;
    foreach ($rooms as $r) {
        if (!$first) echo ',';
        $name = addslashes($r['room_name']);
        echo '{"id":"' . $r['id'] . '","room_key":"' . $r['room_key'] . '","room_name":"' . $name . '","multiplier":"' . $r['multiplier'] . '"}';
        $first = false;
    }
    echo ']';
    exit;
}

// =============================================
// ACTION: Get hotels by province
// =============================================
if ($action == 'getHotels') {

    $province_id = '';
    if (isset($_GET['province_id'])) {
        $province_id = mysqli_real_escape_string($conn, $_GET['province_id']);
    }

    // Get hotels from districts that belong to this province
    $sql = "SELECT h.hotel_name, h.hotel_type, h.price, d.name AS district_name 
            FROM hotel_district h 
            JOIN districts d ON h.district_id = d.id 
            WHERE d.province_id = '" . $province_id . "' 
            ORDER BY d.name, h.hotel_type";

    $result = mysqli_query($conn, $sql);

    $hotels = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $hotels[] = $row;
    }

    header('Content-Type: application/json');
    echo '[';
    $first = true;
    foreach ($hotels as $h) {
        if (!$first) echo ',';
        $name = addslashes($h['hotel_name']);
        $dist = addslashes($h['district_name']);
        echo '{"hotel_name":"' . $name . '","hotel_type":"' . $h['hotel_type'] . '","price":"' . $h['price'] . '","district_name":"' . $dist . '"}';
        $first = false;
    }
    echo ']';
    exit;
}

// =============================================
// ACTION: Save new booking (POST)
// =============================================
if ($action == 'saveBooking' && $_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get all form values - clean them to prevent SQL injection
    $booking_id     = mysqli_real_escape_string($conn, $_POST['booking_id']);
    $customer_name  = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $customer_email = mysqli_real_escape_string($conn, $_POST['customer_email']);
    $customer_phone = mysqli_real_escape_string($conn, $_POST['customer_phone']);
    $province_id    = mysqli_real_escape_string($conn, $_POST['province_id']);
    $package_name   = mysqli_real_escape_string($conn, $_POST['package_name']);
    $vehicle_key    = mysqli_real_escape_string($conn, $_POST['vehicle_key']);
    $meal_key       = mysqli_real_escape_string($conn, $_POST['meal_key']);
    $room_key       = mysqli_real_escape_string($conn, $_POST['room_key']);
    $hotel_type     = mysqli_real_escape_string($conn, $_POST['hotel_type']);
    $adults         = (int)$_POST['adults'];
    $children       = (int)$_POST['children'];
    $start_date     = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date       = mysqli_real_escape_string($conn, $_POST['end_date']);
    $num_days       = (int)$_POST['num_days'];
    $guide_included = (int)$_POST['guide_included'];
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    $total_amount   = (int)$_POST['total_amount'];
    $advance_amount = (int)$_POST['advance_amount'];

    // Handle empty dates - set to NULL
    if ($start_date == '') {
        $start_date_sql = "NULL";
    } else {
        $start_date_sql = "'" . $start_date . "'";
    }

    if ($end_date == '') {
        $end_date_sql = "NULL";
    } else {
        $end_date_sql = "'" . $end_date . "'";
    }

    
$user_id = isset($_POST['user_id']) && intval($_POST['user_id']) > 0 
           ? intval($_POST['user_id']) 
           : "NULL";

$sql = "INSERT INTO bookings (
    booking_id, user_id, customer_name, customer_email, customer_phone,
    province_id, package_name, vehicle_key, meal_key, room_key,
    hotel_type, adults, children, start_date, end_date, num_days,
    guide_included, payment_method, total_amount, advance_amount, status
) VALUES (
    '$booking_id', $user_id, '$customer_name', '$customer_email', '$customer_phone',
    '$province_id', '$package_name', '$vehicle_key', '$meal_key', '$room_key',
    '$hotel_type', $adults, $children, $start_date_sql, $end_date_sql, $num_days,
    $guide_included, '$payment_method', $total_amount, $advance_amount, 'pending'
)";

    $result = mysqli_query($conn, $sql);

    header('Content-Type: application/json');

    if ($result) {
        // Success
        echo '{"success":true,"message":"Booking saved successfully","booking_id":"' . $booking_id . '"}';
    } else {
        // Failed - show error
        $error = mysqli_error($conn);
        echo '{"success":false,"error":"' . addslashes($error) . '"}';
    }

    exit;
}

// =============================================
// ACTION: Get bookings by email (My Bookings)
// =============================================
if ($action == 'getMyBookings') {
    $email = '';
    if (isset($_GET['email'])) {
        $email = mysqli_real_escape_string($conn, $_GET['email']);
    }

    $sql = "SELECT 
                b.booking_id, b.package_name, b.num_days, b.total_amount,
                b.status, b.booking_date, p.name AS province_name,
                COALESCE(SUM(pay.amount), 0) AS paid_amount,
                b.total_amount - COALESCE(SUM(pay.amount), 0) AS due_amount
            FROM bookings b
            LEFT JOIN provinces p ON b.province_id = p.id
            LEFT JOIN payments pay ON b.booking_id = pay.booking_id
            WHERE b.customer_email = '" . $email . "'
            GROUP BY b.booking_id, b.package_name, b.num_days, b.total_amount,
                     b.status, b.booking_date, p.name
            ORDER BY b.booking_date DESC";

    $result = mysqli_query($conn, $sql);
    $bookings = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $bookings[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($bookings);
    exit;
}

// =============================================
// If no valid action - show error
// =============================================
header('Content-Type: application/json');
echo '{"error":"Invalid action"}';
exit;
?>
