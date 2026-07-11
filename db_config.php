<?php
// Nama server selalunya localhost
$host = "localhost";
$user = "root";
$pass = "";
$db   = "cabutan_bertuah";

$conn = new mysqli($host, $user, $pass, $db);

// Semak kalau ada error
if ($conn->connect_error) {
    die("Sambungan ke database gagal: " . $conn->connect_error);
}
?>