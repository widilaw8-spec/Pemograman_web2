<?php
// 1. Koneksi ke database
$conn = new mysqli("localhost", "root", "", "db_pendaftaran");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// 2. Proses simpan data
if (isset($_POST['simpan'])) {

    $tgl_input = $_POST['tgl_input'];
    $kode = strtoupper(trim($_POST['kode']));
    $nama = trim($_POST['nama']);
    $jk = $_POST['jk'];
    $asal_sekolah = trim($_POST['asal_sekolah']);
    $pekerjaan_ortu = trim($_POST['pekerjaan_ortu']);
    $tempat_lahir = trim($_POST['tempat_lahir']);
    $tgl_lahir = $_POST['tgl_lahir'];

    $mat = (int) $_POST['mat'];
    $bing = (int) $_POST['bing'];
    $umum = (int) $_POST['umum'];

    // Validasi nilai
    if ($mat < 0 || $mat > 100 ||
        $bing < 0 || $bing > 100 ||
        $umum < 0 || $umum > 100) {
        die("Nilai harus berada di antara 0 sampai 100.");
    }

    // Menghitung rata-rata
    $rata = ($mat + $bing + $umum) / 3;

    // Menentukan kelulusan
    if ($rata >= 70) {
        $keterangan = "Lulus";
    } elseif ($rata >= 60) {
        $keterangan = "Cadangan";
    } else {
        $keterangan = "Tidak Lulus";
    }

    // Menentukan tempat tes
    $kode_awal = substr($kode, 0, 1);

    if ($kode_awal == "A") {
        $tempat_tes = "Gedung A";
    } elseif ($kode_awal == "B") {
        $tempat_tes = "Gedung B";
    } elseif ($kode_awal == "V") {
        $tempat_tes = "Viktor";
    } else {
        $tempat_tes = "Tidak Diketahui";
    }

    // Menyimpan data menggunakan prepared statement
    $stmt = $conn->prepare(
        "INSERT INTO pendaftar
        (tgl_input, kode, nama, jk, asal_sekolah,
         pekerjaan_ortu, tempat_lahir, tgl_lahir,
         mat, bing, umum, rata_rata, tempat_tes, keterangan)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "ssssssssiiidss",
        $tgl_input,
        $kode,
        $nama,
        $jk,
        $asal_sekolah,
        $pekerjaan_ortu,
        $tempat_lahir,
        $tgl_lahir,
        $mat,
        $bing,
        $umum,
        $rata,
        $tempat_tes,
        $keterangan
    );

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();

        // Mencegah data tersimpan dua kali saat refresh
        header("Location: index.php?berhasil=1");
        exit;
    } else {
        die("Gagal menyimpan data.");
    }
}

// 3. Mengambil semua data dari database
$result = $conn->query(
    "SELECT * FROM pendaftar ORDER BY id DESC"
);

// 4. Menghitung rekapitulasi
$jumlah_pendaftar = $result->num_rows;
$jumlah_lulus = 0;
$jumlah_cadangan = 0;
$jumlah_tidak_lulus = 0;

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;

    if ($row['keterangan'] == "Lulus") {
        $jumlah_lulus++;
    } elseif ($row['keterangan'] == "Cadangan") {
        $jumlah_cadangan++;
    } else {
        $jumlah_tidak_lulus++;
    }
}

// Fungsi untuk mengamankan tampilan teks
function aman($teks) {
    return htmlspecialchars(
        (string) $teks,
        ENT_QUOTES,
        'UTF-8'
    );
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>UTS PHP - Pendaftaran Mahasiswa</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 30px;
            background: #f4f4f4;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }

        h2, h3 {
            text-align: center;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        input[type=text],
        input[type=number],
        input[type=date] {
            width: 100%;
            box-sizing: border-box;
            padding: 8px;
            margin-top: 5px;
        }

        .btn {
            background: blue;
            color: white;
            border: none;
            padding: 10px 20px;
            margin-top: 15px;
            cursor: pointer;
        }

        .btn:hover {
            background: darkblue;
        }

        .info {
            margin-top: 20px;
            padding: 15px;
            background: #eef;
            border-radius: 5px;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 10px;
            text-align: center;
            white-space: nowrap;
        }

        th {
            background: #dbeafe;
        }

        .berhasil {
            background: #d1fae5;
            color: #065f46;
            padding: 12px;
            margin-top: 15px;
        }
    </style>
</head>

<body>
<div class="container">

    <h2>FORM PENDAFTARAN MAHASISWA BARU</h2>

    <?php if (isset($_GET['berhasil'])): ?>
        <div class="berhasil">
            Data pendaftar berhasil disimpan!
        </div>
    <?php endif; ?>

    <form method="POST">

        <label>Tanggal Input</label>
        <input type="date" name="tgl_input"
               value="<?php echo date('Y-m-d'); ?>" required>

        <label>Kode Pendaftaran</label>
        <input type="text" name="kode"
               placeholder="Contoh: A2" required>

        <label>Nama Pendaftar</label>
        <input type="text" name="nama" required>

        <label>Jenis Kelamin</label>
        <input type="radio" name="jk"
               value="Laki-Laki" required> Laki-Laki

        <input type="radio" name="jk"
               value="Perempuan"> Perempuan

        <label>Asal Sekolah</label>
        <input type="text" name="asal_sekolah" required>

        <label>Pekerjaan Orang Tua</label>
        <input type="text" name="pekerjaan_ortu" required>

        <label>Tempat Lahir</label>
        <input type="text" name="tempat_lahir" required>

        <label>Tanggal Lahir</label>
        <input type="date" name="tgl_lahir" required>

        <label>Matematika</label>
        <input type="number" name="mat"
               min="0" max="100" required>

        <label>Bahasa Inggris</label>
        <input type="number" name="bing"
               min="0" max="100" required>

        <label>Umum</label>
        <input type="number" name="umum"
               min="0" max="100" required>

        <button type="submit" name="simpan" class="btn">
            SIMPAN
        </button>

    </form>

    <div class="table-wrapper">
        <table>
            <tr>
                <th>No</th>
                <th>Tgl Input</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>JK</th>
                <th>Tempat Lahir</th>
                <th>Tgl Lahir</th>
                <th>Asal Sekolah</th>
                <th>Pekerjaan Ortu</th>
                <th>Tempat Tes</th>
                <th>Matematika</th>
                <th>B. Inggris</th>
                <th>Umum</th>
                <th>Rata-rata</th>
                <th>Keterangan</th>
            </tr>

            <?php if (count($data) > 0): ?>
                <?php $no = 1; ?>

                <?php foreach ($data as $row): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo aman($row['tgl_input']); ?></td>
                        <td><?php echo aman($row['kode']); ?></td>
                        <td><?php echo aman($row['nama']); ?></td>
                        <td><?php echo aman($row['jk']); ?></td>
                        <td><?php echo aman($row['tempat_lahir']); ?></td>
                        <td><?php echo aman($row['tgl_lahir']); ?></td>
                        <td><?php echo aman($row['asal_sekolah']); ?></td>
                        <td><?php echo aman($row['pekerjaan_ortu']); ?></td>
                        <td><?php echo aman($row['tempat_tes']); ?></td>
                        <td><?php echo aman($row['mat']); ?></td>
                        <td><?php echo aman($row['bing']); ?></td>
                        <td><?php echo aman($row['umum']); ?></td>
                        <td><?php echo number_format((float)$row['rata_rata'], 2); ?></td>
                        <td><?php echo aman($row['keterangan']); ?></td>
                    </tr>
                <?php endforeach; ?>

            <?php else: ?>
                <tr>
                    <td colspan="15">
                        Belum ada data pendaftar.
                    </td>
                </tr>
            <?php endif; ?>
        </table>
    </div>

    <div class="info">
        <h3>REKAP DATA PENDAFTAR</h3>

        <p>Jumlah Pendaftar:
            <b><?php echo $jumlah_pendaftar; ?></b>
        </p>

        <p>Jumlah Lulus:
            <b><?php echo $jumlah_lulus; ?></b>
        </p>

        <p>Jumlah Cadangan:
            <b><?php echo $jumlah_cadangan; ?></b>
        </p>

        <p>Jumlah Tidak Lulus:
            <b><?php echo $jumlah_tidak_lulus; ?></b>
        </p>
    </div>

    <div class="info">
        <h3>KETERANGAN</h3>

        <p><b>Kode Tempat Tes:</b></p>
        <ul>
            <li>A = Gedung A</li>
            <li>B = Gedung B</li>
            <li>V = Viktor</li>
        </ul>

        <p><b>Hasil Seleksi:</b></p>
        <ul>
            <li>Rata-rata 70 atau lebih = Lulus</li>
            <li>Rata-rata 60 sampai kurang dari 70 = Cadangan</li>
            <li>Rata-rata kurang dari 60 = Tidak Lulus</li>
        </ul>
    </div>

</div>
</body>
</html>

<?php
$conn->close();
?>
