<?php
// includes/db.php
$DB_HOST = '127.0.0.1';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'rp_library';

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
    die("DB Connection failed: " . $mysqli->connect_error);
}
$mysqli->set_charset("utf8mb4");
?>
