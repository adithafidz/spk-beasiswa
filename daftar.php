<?php
session_start();
// 1. Proteksi Halaman: Pastikan yang masuk adalah mahasiswa yang sudah login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

$pesan = "";
if (isset($_POST['submit'])) {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $c1 = $_POST['c1']; // IPK
    $c2 = $_POST['c2']; // Penghasilan
    $c3 = $_POST['c3']; // Tanggungan
    $c4 = $_POST['c4']; // Prestasi

    // 2. Insert ke tabel alternatif (mahasiswa)
    $query_alt = "INSERT INTO alternatif (nim, nama_mahasiswa) VALUES ('$nim', '$nama')";
    if (mysqli_query($koneksi, $query_alt)) {
        $id_alternatif = mysqli_insert_id($koneksi);

        // 3. Insert nilai kriteria ke tabel nilai_alternatif
        mysqli_query($koneksi, "INSERT INTO nilai_alternatif (id_alternatif, id_kriteria, nilai) VALUES ($id_alternatif, 1, $c1)");
        mysqli_query($koneksi, "INSERT INTO nilai_alternatif (id_alternatif, id_kriteria, nilai) VALUES ($id_alternatif, 2, $c2)");
        mysqli_query($koneksi, "INSERT INTO nilai_alternatif (id_alternatif, id_kriteria, nilai) VALUES ($id_alternatif, 3, $c3)");
        mysqli_query($koneksi, "INSERT INTO nilai_alternatif (id_alternatif, id_kriteria, nilai) VALUES ($id_alternatif, 4, $c4)");

        $pesan = "<div class='alert alert-success'>Pendaftaran berhasil! Data kamu sudah masuk ke sistem seleksi admin.</div>";
    } else {
        $pesan = "<div class='alert alert-danger'>Gagal mendaftar: " . mysqli_error($koneksi) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pendaftaran Beasiswa Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">Portal Beasiswa Mahasiswa</a>
        <div class="ms-auto d-flex align-items-center text-white me-3">
            <!-- Tambahkan tombol Lihat Hasil di sini -->
            <a href="lihat_hasil.php" class="btn btn-warning btn-sm me-2 fw-bold text-dark"><i class="bi bi-trophy-fill me-1"></i> Lihat Hasil Peringkat</a>
            <span class="small me-3">Halo, <strong><?= $_SESSION['nama']; ?></strong></span>
            <a href="logout.php" class="btn btn-danger btn-sm">Log Out</a>
        </div>
    </div>
</nav>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm p-4">
                <h3 class="text-center text-primary mb-4">Formulir Pendaftaran Beasiswa</h3>
                <?= $pesan; ?>
                <form action="" method="POST">
                    <h5>A. Biodata Diri</h5>
                    <div class="mb-3">
                        <label class="form-label">NIM</label>
                        <input type="text" name="nim" class="form-control" value="<?= $_SESSION['username']; ?>" readonly required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" value="<?= $_SESSION['nama']; ?>" readonly required>
                    </div>

                    <h5>B. Data Kriteria Seleksi</h5>
                    <div class="mb-3">
                        <label class="form-label">C1 - Indeks Prestasi Kumulatif (IPK)</label>
                        <select name="c1" class="form-select" required>
                            <option value="5">IPK &gt;= 3.75 (Sangat Bagus)</option>
                            <option value="4">IPK 3.50 - 3.74 (Bagus)</option>
                            <option value="3">IPK 3.00 - 3.49 (Cukup)</option>
                            <option value="2">IPK 2.75 - 2.99 (Kurang)</option>
                            <option value="1">IPK &lt; 2.75 (Sangat Kurang)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">C2 - Penghasilan Orang Tua (Semakin kecil skor, semakin prioritas/Cost)</label>
                        <select name="c2" class="form-select" required>
                            <option value="5">Rp &gt; 5.000.000 (Mampu)</option>
                            <option value="4">Rp 3.500.000 - Rp 5.000.000 (Cukup Mampu)</option>
                            <option value="3">Rp 2.000.000 - Rp 3.499.000 (Sedang)</option>
                            <option value="2">Rp 1.000.000 - Rp 1.999.000 (Kurang Mampu)</option>
                            <option value="1">Rp &lt; 1.000.000 (Sangat Kurang Mampu)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">C3 - Jumlah Tanggungan Orang Tua</label>
                        <select name="c3" class="form-select" required>
                            <option value="5">Tanggungan &gt;= 5 anak</option>
                            <option value="4">Tanggungan 4 anak</option>
                            <option value="3">Tanggungan 3 anak</option>
                            <option value="2">Tanggungan 2 anak</option>
                            <option value="1">Tanggungan 1 anak</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">C4 - Prestasi Non-Akademik</label>
                        <select name="c4" class="form-select" required>
                            <option value="5">Tingkat Internasional / Nasional</option>
                            <option value="4">Tingkat Provinsi</option>
                            <option value="3">Tingkat Kota / Kabupaten</option>
                            <option value="2">Tingkat Kampus / Sekolah</option>
                            <option value="1">Tidak Ada Prestasi</option>
                        </select>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" name="submit" class="btn btn-primary btn-lg">Kirim Pendaftaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>