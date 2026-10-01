<?php

// render_packages.php

$host = 'localhost';
$db   = 'globe_trek';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo '<p style="color:red;">DB Error: Could not connect.</p>';
    exit;
}

// ── Helper: Format LKR ──
function fmt($n) {
    return 'LKR ' . number_format($n, 0, '.', ',');
}

// ── 1. Get all packages ──
$pkgResult = $conn->query("SELECT name, display_name, description FROM packages ORDER BY id ASC");
$packages = [];
while ($row = $pkgResult->fetch_assoc()) {
    $packages[] = $row;
}

// ── 2. Print package cards ──
foreach ($packages as $pkg) {
    $pkgName = $conn->real_escape_string($pkg['name']);

    // Get vehicles for this package
    $vResult = $conn->query("SELECT vehicle_label, cost FROM vehicles WHERE package_name='$pkgName' ORDER BY id ASC");
    $vehicles = [];
    while ($v = $vResult->fetch_assoc()) {
        $vehicles[] = $v;
    }

    // Build vehicles HTML
    $vehiclesHTML = '';
    if (!empty($vehicles)) {
        $vehiclesHTML .= '<div class="pkg-vehicles">';
        $vehiclesHTML .= '<div class="pkg-vehicles-title">Transport Options</div>';
        foreach ($vehicles as $v) {
            $vehiclesHTML .= '
            <div class="pkg-vehicle-item">
                <span>' . htmlspecialchars($v['vehicle_label']) . '</span>
                <span class="pkg-vehicle-cost">' . fmt($v['cost']) . '</span>
            </div>';
        }
        $vehiclesHTML .= '</div>';
    }

    // Print the card
    echo '
    <div class="pkg-card" data-pkg="' . htmlspecialchars($pkg['name']) . '">
        <div class="pkg-card-name">' . htmlspecialchars($pkg['display_name']) . '</div>
        <div class="pkg-card-desc">' . htmlspecialchars($pkg['description']) . '</div>
        ' . $vehiclesHTML . '
    </div>';
}

// ── 3. Print add-ons strip ──
// Meals
$mealsResult = $conn->query("SELECT meal_name, cost_per_person FROM meal_plans ORDER BY id ASC");
$mealsHTML = '';
while ($m = $mealsResult->fetch_assoc()) {
    $cost = (int)$m['cost_per_person'] === 0 ? 'Free' : '+' . fmt($m['cost_per_person']) . ' / person';
    $mealsHTML .= '
    <div class="addon-item">
        <span class="addon-label">' . htmlspecialchars($m['meal_name']) . '</span>
        <span class="addon-price">' . $cost . '</span>
    </div>';
}

// Rooms
$roomsResult = $conn->query("SELECT room_name, multiplier FROM room_types ORDER BY id ASC");
$roomsHTML = '';
while ($r = $roomsResult->fetch_assoc()) {
    $roomsHTML .= '
    <div class="addon-item">
        <span class="addon-label">' . htmlspecialchars($r['room_name']) . '</span>
        <span class="addon-price">×' . number_format($r['multiplier'], 1) . ' rate</span>
    </div>';
}

// Print the addons strip
echo '
<div class="pkg-addons-strip" id="pkgAddonsStrip">
    <div class="addon-col">
        <div class="addon-title"><span class="material-icons">restaurant</span> Meal Plans</div>
        <div class="addon-items">' . $mealsHTML . '</div>
    </div>
    <div class="addon-divider"></div>
    <div class="addon-col">
        <div class="addon-title"><span class="material-icons">bed</span> Room Types</div>
        <div class="addon-items">' . $roomsHTML . '</div>
    </div>
    <div class="addon-divider"></div>
    <div class="addon-col">
        <div class="addon-title"><span class="material-icons">person_pin</span> Optional Add-on</div>
        <div class="addon-items">
            <div class="addon-item">
                <span class="addon-label">🧭 Tour Guide</span>
                <span class="addon-price">LKR 5,000 / day</span>
            </div>
        </div>
    </div>
</div>';

$conn->close();
?>

