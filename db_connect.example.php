<?php
$host = "localhost";
$dbname = "globe_trek";
$user = "your_db_user";
$pass = "your_db_password";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    http_response_code(500);
    echo '{"error":"DB connection failed"}';
    exit;
}

mysqli_set_charset($conn, "utf8");
?>
