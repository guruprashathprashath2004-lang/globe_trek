<?php
require_once 'db_connect.php';

$booking_id = isset($_GET['booking_id']) ? trim($_GET['booking_id']) : '';

if (!$booking_id) {
    http_response_code(400);
    echo 'Missing booking_id';
    exit;
}

$safe_id = mysqli_real_escape_string($conn, $booking_id);

$sql = "SELECT b.*, p.name AS province_name
        FROM bookings b
        LEFT JOIN provinces p ON b.province_id = p.id
        WHERE b.booking_id = '$safe_id'
        LIMIT 1";

$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) === 0) {
    http_response_code(404);
    echo 'Booking not found';
    exit;
}

$b = mysqli_fetch_assoc($result);
mysqli_close($conn);

// ── Amounts ──
$total_amount   = (int)($b['total_amount']   ?? 0);
$advance_amount = (int)($b['advance_amount'] ?? 0);
$balance_amount = $total_amount - $advance_amount;

$total_fmt   = 'LKR ' . number_format($total_amount);
$advance_fmt = 'LKR ' . number_format($advance_amount);
$balance_fmt = 'LKR ' . number_format($balance_amount);

// ── Dates ──
$booking_date = !empty($b['booking_date']) ? date('d M Y, h:i A', strtotime($b['booking_date'])) : '—';
$start_date   = !empty($b['start_date'])   ? date('d M Y', strtotime($b['start_date']))           : '—';
$end_date     = !empty($b['end_date'])     ? date('d M Y', strtotime($b['end_date']))             : '—';
$guide_text   = ($b['guide_included'] == 1) ? 'Yes' : 'No';

// ── Status color ──
$status_colors = [
    'confirmed'  => '#34d399',
    'completed'  => '#2dd4b8',
    'cancelled'  => '#f87171',
    'pending'    => '#fbbf24',
];
$status_color = $status_colors[$b['status']] ?? '#fbbf24';

function h($val) {
    return htmlspecialchars($val ?? '—', ENT_QUOTES, 'UTF-8');
}

// ── Serve uploaded receipt file if exists ──
if (!empty($b['receipt_file'])) {
    $filepath = 'receipts/' . $b['receipt_file'];
    if (file_exists($filepath)) {
        $ext = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));
        $mimes = ['pdf'=>'application/pdf','jpg'=>'image/jpeg','jpeg'=>'image/jpeg','png'=>'image/png'];
        $mime = $mimes[$ext] ?? 'application/octet-stream';
        header('Content-Type: ' . $mime);
        header('Content-Disposition: attachment; filename="Receipt_' . $safe_id . '.' . $ext . '"');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
        exit;
    }
}

// ── Generate HTML receipt for print/save ──
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>Receipt – <?= h($b['booking_id']) ?></title>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { background: #020e0d; font-family: 'Segoe UI', sans-serif; color: #f4fffe; padding: 2rem; min-height: 100vh; }
  .receipt { max-width: 640px; margin: 0 auto; background: #0c2826; border: 1px solid rgba(45,212,184,0.25); border-radius: 20px; overflow: hidden; }
  .receipt-header { background: linear-gradient(135deg, #0d3d38, #0a2e2a); padding: 2rem; text-align: center; border-bottom: 1px solid rgba(45,212,184,0.18); }
  .brand { font-size: 1.5rem; font-weight: 800; color: #2dd4b8; letter-spacing: .03em; margin-bottom: .2rem; }
  .brand-sub { font-size: .75rem; color: rgba(244,255,254,.5); }
  .receipt-title { font-size: .7rem; text-transform: uppercase; letter-spacing: .15em; color: rgba(244,255,254,.4); margin-top: 1.2rem; }
  .booking-id { font-size: 1.3rem; font-weight: 800; color: #2dd4b8; font-family: monospace; margin-top: .2rem; }
  .status-pill { display: inline-block; padding: 4px 14px; border-radius: 999px; font-size: .72rem; font-weight: 700; margin-top: .5rem; }
  .section { padding: 1.2rem 1.6rem; border-bottom: 1px solid rgba(244,255,254,.06); }
  .section:last-child { border-bottom: none; }
  .section-title { font-size: .62rem; text-transform: uppercase; letter-spacing: .1em; color: #2dd4b8; margin-bottom: .8rem; font-weight: 700; }
  .row { display: flex; justify-content: space-between; align-items: center; padding: .3rem 0; font-size: .83rem; }
  .row-key { color: rgba(244,255,254,.5); }
  .row-val { color: #f4fffe; font-weight: 500; text-align: right; }
  .amounts { padding: 1.4rem 1.6rem; background: rgba(45,212,184,.04); border-top: 1px solid rgba(45,212,184,.15); }
  .amt-row { display: flex; justify-content: space-between; align-items: center; padding: .45rem 0; font-size: .88rem; border-bottom: 1px solid rgba(244,255,254,.05); }
  .amt-row:last-child { border-bottom: none; }
  .amt-label { color: rgba(244,255,254,.6); }
  .amt-total { font-size: 1.1rem; font-weight: 800; color: #f4fffe; }
  .amt-total-label { font-size: 1rem; font-weight: 700; color: #f4fffe; }
  .amt-advance { color: #34d399; font-weight: 700; }
  .amt-balance { color: #fbbf24; font-weight: 700; }
  .footer { text-align: center; padding: 1.2rem; font-size: .7rem; color: rgba(244,255,254,.3); border-top: 1px solid rgba(45,212,184,.1); }
  .print-btn { display: block; margin: 1.5rem auto 0; padding: .7rem 2rem; background: #2dd4b8; color: #020e0d; border: none; border-radius: 999px; font-size: .85rem; font-weight: 700; cursor: pointer; font-family: inherit; }
  .print-btn:hover { background: #4be8d0; }
  @media print {
    body { background: #fff; color: #000; padding: 0; }
    .receipt { border: 1px solid #ccc; border-radius: 0; }
    .receipt-header { background: #f0faf8 !important; }
    .brand { color: #0a6658; }
    .booking-id, .section-title { color: #0a6658; }
    .row-key, .amt-label { color: #555; }
    .row-val, .amt-total, .amt-total-label { color: #000; }
    .amt-advance { color: #1a7a50; }
    .amt-balance { color: #b45309; }
    .footer { color: #888; }
    .print-btn { display: none; }
  }
</style>
</head>
<body>
<div class="receipt">

  <div class="receipt-header">
    <div class="brand">GlobeTrek</div>
    <div class="brand-sub">Negombo, Sri Lanka &nbsp;|&nbsp; +94 75 972 5440 &nbsp;|&nbsp; info@globetrek.lk</div>
    <div class="receipt-title">Booking Receipt</div>
    <div class="booking-id"><?= h($b['booking_id']) ?></div>
    <div class="status-pill" style="background:<?= $status_color ?>22;color:<?= $status_color ?>;border:1px solid <?= $status_color ?>44">
      <?= strtoupper(h($b['status'])) ?>
    </div>
  </div>

  <div class="section">
    <div class="section-title">Booking Info</div>
    <div class="row"><span class="row-key">Booked On</span><span class="row-val"><?= $booking_date ?></span></div>
    <div class="row"><span class="row-key">Payment Method</span><span class="row-val"><?= h($b['payment_method'] ?: '—') ?></span></div>
  </div>

  <div class="section">
    <div class="section-title">Customer</div>
    <div class="row"><span class="row-key">Name</span><span class="row-val"><?= h($b['customer_name']) ?></span></div>
    <div class="row"><span class="row-key">Email</span><span class="row-val"><?= h($b['customer_email']) ?></span></div>
    <div class="row"><span class="row-key">Phone</span><span class="row-val"><?= h($b['customer_phone'] ?: '—') ?></span></div>
  </div>

  <div class="section">
    <div class="section-title">Trip Details</div>
    <div class="row"><span class="row-key">Province</span><span class="row-val"><?= h($b['province_name'] ?: '—') ?></span></div>
    <div class="row"><span class="row-key">Package</span><span class="row-val"><?= h($b['package_name'] ?: '—') ?></span></div>
    <div class="row"><span class="row-key">Start Date</span><span class="row-val"><?= $start_date ?></span></div>
    <div class="row"><span class="row-key">End Date</span><span class="row-val"><?= $end_date ?></span></div>
    <div class="row"><span class="row-key">Duration</span><span class="row-val"><?= h($b['num_days']) ?> Days</span></div>
    <div class="row"><span class="row-key">Travelers</span><span class="row-val"><?= h($b['adults']) ?> Adult(s) + <?= h($b['children']) ?> Child(ren)</span></div>
  </div>

  <div class="section">
    <div class="section-title">Services</div>
    <div class="row"><span class="row-key">Hotel Type</span><span class="row-val"><?= h($b['hotel_type'] ?: '—') ?></span></div>
    <div class="row"><span class="row-key">Room Type</span><span class="row-val"><?= h($b['room_key'] ?: '—') ?></span></div>
    <div class="row"><span class="row-key">Vehicle</span><span class="row-val"><?= h($b['vehicle_key'] ?: '—') ?></span></div>
    <div class="row"><span class="row-key">Meal Plan</span><span class="row-val"><?= h($b['meal_key'] ?: '—') ?></span></div>
    <div class="row"><span class="row-key">Tour Guide</span><span class="row-val"><?= $guide_text ?></span></div>
  </div>

  <div class="amounts">
    <div class="section-title" style="margin-bottom:.9rem">Payment Summary</div>
    <div class="amt-row">
      <span class="amt-label">Total Package Amount</span>
      <span class="amt-total"><?= $total_fmt ?></span>
    </div>
    <div class="amt-row">
      <span class="amt-label">Advance Paid (30%)</span>
      <span class="amt-advance"><?= $advance_fmt ?></span>
    </div>
    <div class="amt-row">
      <span class="amt-label">Balance Due on Arrival (70%)</span>
      <span class="amt-balance"><?= $balance_fmt ?></span>
    </div>
  </div>

  <div class="footer">
    Thank you for choosing GlobeTrek Adventures!<br>
    This is a computer-generated receipt. Please keep it for reference.
  </div>
</div>

<button class="print-btn" onclick="window.print()">🖨 Print / Save as PDF</button>

</body>
</html>
<?php
exit;
?>
