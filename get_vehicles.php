<?php
require_once 'db_connect.php';

$package_filter = '';

if (!empty($_GET['package'])) {
    $pkg = mysqli_real_escape_string($conn, $_GET['package']);
    $package_filter = "WHERE v.package_name = '$pkg'";
}

$sql = "SELECT 
            v.id,
            v.package_name,
            v.vehicle_key,
            v.vehicle_label,
            v.cost,
            pk.display_name AS package_display
        FROM vehicles v
        LEFT JOIN packages pk ON v.package_name = pk.name
        $package_filter
        ORDER BY v.package_name, v.vehicle_label";

$result = mysqli_query($conn, $sql);

if (!$result) {
    http_response_code(500);
    echo '[]';
    exit;
}

$vehicles = array();

while ($row = mysqli_fetch_assoc($result)) {
    $vehicles[] = array(
        'id'              => (int)$row['id'],
        'package_name'    => $row['package_name'],
        'vehicle_key'     => $row['vehicle_key'],
        'vehicle_label'   => $row['vehicle_label'],
        'cost'            => (int)$row['cost'],
        'hotel_type'      => $row['package_display'] ? $row['package_display'] : $row['package_name'],
        'package_display' => $row['package_display']
    );
}

mysqli_close($conn);

header('Content-Type: application/json');
echo json_encode($vehicles);
exit;
?>
