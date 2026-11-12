<?php

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "db_mahasiswa";

// Lakukan koneksi dengan database
$connection = mysqli_connect(
    $dbhost,
    $dbuser,
    $dbpass,
    $dbname
);

if (!$connection) {
    die("Tidak dapat terhubung dengan database: "
        . mysqli_connect_error());
}

// Pilih karakter database
mysqli_set_charset($connection, "utf8mb4");

?>