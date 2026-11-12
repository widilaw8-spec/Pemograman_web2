<?php

// Cek tombol Submit
if (isset($_POST['Submit']) && $_POST['Submit'] == "Submit") {

    $id_mahasiswa = $_POST['id_mahasiswa'];
    $nama = $_POST['nama'];
    $jurusan = $_POST['jurusan'];
    $alamat = $_POST['alamat'];
    $telepon = $_POST['telepon'];

    // Validasi data kosong
    if (
        empty($id_mahasiswa) ||
        empty($nama) ||
        empty($jurusan) ||
        empty($alamat) ||
        empty($telepon)
    ) {
        echo "<script>
        alert('Data Harap Dilengkapi!');
        document.location='pert_13_form_input_data.php';
        </script>";
        exit;
    }

    // Koneksi database
    include "koneksi.php";

    // Cek NIM di database
    $sql = "SELECT id_mahasiswa FROM mahasiswa
            WHERE id_mahasiswa = ?";

    $cek = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($cek, "s", $id_mahasiswa);
    mysqli_stmt_execute($cek);
    mysqli_stmt_store_result($cek);

    if (mysqli_stmt_num_rows($cek) > 0) {

        echo "<script>
        alert('NIM sudah dipakai!, silahkan ganti NIM yang lain');
        document.location='pert_13_form_input_data.php';
        </script>";

        exit;
    }

    mysqli_stmt_close($cek);

    // Masukkan data ke tabel
    $input = "INSERT INTO mahasiswa
    (id_mahasiswa, nama, jurusan, alamat, telepon)
    VALUES (?, ?, ?, ?, ?)";

    $query_input = mysqli_prepare($connection, $input);

    mysqli_stmt_bind_param(
        $query_input,
        "sssss",
        $id_mahasiswa,
        $nama,
        $jurusan,
        $alamat,
        $telepon
    );

    if (mysqli_stmt_execute($query_input)) {

        // Jika sukses
        echo "<script>
        alert('Input Data Mahasiswa Berhasil');
        document.location='pert_13_form_input_data.php';
        </script>";

    } else {

        // Jika gagal
        echo "Input Data Mahasiswa Gagal!, Silahkan diulangi!";

    }

    // Tutup koneksi database
    mysqli_stmt_close($query_input);
    mysqli_close($connection);

}

?>