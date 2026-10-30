<?php
// Hostname atau IP server
$servername = "localhost";

// Username dan password database
$dbusername = "root";
$dbpassword = "";

// Membuat koneksi ke server MySQL
$link = mysqli_connect($servername, $dbusername, $dbpassword);

// Memeriksa koneksi
if (!$link) {
    die("Tidak dapat terhubung ke server: " . mysqli_connect_error());
} else {
    echo "OK.. Koneksi berhasil";
}
?>
