<?php
$host = "sql209.infinityfree.com";
$user = "if0_40541064";
$pass = "dapet100ya";
$db   = "if0_40541064_global_time";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
