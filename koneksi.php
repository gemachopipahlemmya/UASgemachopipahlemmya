<?php
// Konfigurasi database
$host     = "localhost"; // Nama host
$username = "root";      // Username database
$password = "";          // Password database
$database = "pendataan_magang"; // Nama database

// Membuat koneksi
$conn = mysqli_connect($host, $username, $password, $database);

// Periksa koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

echo " ";
?>
