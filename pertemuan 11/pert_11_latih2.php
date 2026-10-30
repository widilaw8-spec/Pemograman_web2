<?php
// Menghubungkan ke server MySQL
$link = mysqli_connect("localhost", "root", "");

// Memeriksa koneksi
if (!$link) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Nama database yang akan dibuat
$dbname = "lat_dbase";

// Membuat database
$sql = "CREATE DATABASE $dbname";
$cek = mysqli_query($link, $sql);

// Memeriksa hasil pembuatan database
if ($cek) {
    echo "Database $dbname berhasil dibuat";
} else {
    echo "Gagal membuat database: " . mysqli_error($link);
}

// Menutup koneksi
mysqli_close($link);
?>