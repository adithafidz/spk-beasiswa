<?php
session_start();
// Proteksi Halaman: Hanya mahasiswa aktif yang sudah login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

// --- PROSES KALKULASI ALGORITMA SAW (Sama seperti di admin) ---
$kriteria_query = mysqli_query($koneksi, "SELECT * FROM kriteria");
$kriteria = [];
while ($row = mysqli_fetch_assoc($kriteria_query)) { $kriteria[$row['id_kriteria']] = $row; }

$alternatif_query = mysqli_query($koneksi, "SELECT * FROM alternatif");
$alternatif = [];
while ($row = mysqli_fetch_assoc($alternatif_query)) { $alternatif[$row['id_alternatif']] = $row; }

$nilai_query = mysqli_query($koneksi, "SELECT * FROM nilai_alternatif");
$matrix_x = []; $max_min = [];
while ($row = mysqli_fetch_assoc($nilai_query)) {
    $matrix_x[$row['id_alternatif']][$row['id_kriteria']] = $row['nilai'];
    if (!isset($max_min[$row['id_kriteria']])) {
        $max_min[$row['id_kriteria']] = ['max' => $row['nilai'], 'min' => $row['nilai']];
    } else {
        if ($row['nilai'] > $max_min[$row['id_kriteria']]['max']) $max_min[$row['id_kriteria']]['max'] = $row['nilai'];
        if ($row['nilai'] < $max_min[$row['id_kriteria']]['min']) $max_min[$row['id_kriteria']]['min'] = $row['nilai'];
    }
}

$hasil_akhir = [];
foreach ($alternatif as $id_alt => $alt) {
    $nilai_v = 0;
    foreach ($kriteria as $id_krit => $krit) {
        $nilai_asli = isset($matrix_x[$id_alt][$id_krit]) ? $matrix_x[$id_alt][$id_krit] : 0;
        if ($krit['jenis'] == 'benefit') {
            $r = ($max_min[$id_krit]['max'] != 0) ? ($nilai_asli / $max_min[$id_krit]['max']) : 0;
        } else {
            $r = ($nilai_asli != 0) ? ($max_min[$id_krit]['min'] / $nilai_asli) : 0;
        }
        $nilai_v += $r * $krit['bobot'];
    }
    $hasil_akhir[] = [
        'nim' => $alt['nim'],
        'nama' => $alt['nama_mahasiswa'],
        'skor' => round($nilai_v, 4)
    ];
}
usort($hasil_akhir, function($a, $b) { return $b['skor'] <=> $a['skor']; });

// Cari tahu posisi peringkat mahasiswa yang sedang login saat ini
$nim_login = $_SESSION['username'];
$peringkat_saya = 0;
$skor_saya = 0;
$rank = 1;

foreach ($hasil_akhir as $h) {
    if ($h['nim'] == $nim_login) {
        $peringkat_saya = $rank;
        $skor_saya = $h['skor'];
        break;
    }
    $rank++;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><title>Hasil Seleksi Beasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">Portal Beasiswa Mahasiswa</a>
        <div>
            <a href="daftar.php" class="btn btn-light btn-sm me-2">Form Pendaftaran</a>
            <a href="logout.php" class="btn btn-danger btn-sm">Log Out</a>
        </div>
    </div>
</nav>

<div class="container my-5 col-md-8">
    <div class="card p-5 shadow-sm bg-white text-center rounded-4">
        <h3 class="fw-bold text-dark mb-2">Informasi Hasil Seleksi</h3>
        <p class="text-muted mb-4">Akun NIM: <strong><?= $nim_login; ?></strong> - Nama: <strong><?= $_SESSION['nama']; ?></strong></p>
        
        <hr>

        <?php if ($peringkat_saya == 0): ?>
            <!-- Kondisi jika mahasiswa belum isi formulir pendaftaran -->
            <div class="py-4">
                <i class="bi bi-exclamation-triangle-fill text-warning display-1"></i>
                <h4 class="mt-3 fw-bold">Kamu Belum Mengisi Data Kriteria</h4>
                <p class="text-muted">Silakan isi formulir pendaftaran terlebih dahulu agar sistem bisa menghitung skor kelayakanmu.</p>
                <a href="daftar.php" class="btn btn-primary px-4 py-2 fw-bold">Isi Formulir Sekarang</a>
            </div>
        <?php else: ?>
            <!-- Kondisi jika data sudah dihitung oleh algoritma SAW -->
            <div class="row my-4 justify-content-center">
                <div class="col-md-5 mb-3">
                    <div class="card bg-primary text-white p-4 rounded-3 h-100 border-0">
                        <p class="small text-uppercase mb-1 opacity-75 fw-bold">Skor Akhir Preferensi (V)</p>
                        <h1 class="display-4 fw-bold mb-0"><?= $skor_saya; ?></h1>
                        <p class="small mt-2 mb-0">*Skala Maksimal 1.0000</p>
                    </div>
                </div>
                <div class="col-md-5 mb-3">
                    <div class="card bg-dark text-white p-4 rounded-3 h-100 border-0">
                        <p class="small text-uppercase mb-1 opacity-75 fw-bold">Posisi Peringkat Kamu</p>
                        <h1 class="display-4 fw-bold mb-0">#<?= $peringkat_saya; ?></h1>
                        <p class="small mt-2 mb-0">Dari total <?= count($hasil_akhir); ?> pendaftar</p>
                    </div>
                </div>
            </div>

            <div class="alert alert-info py-3 border-0 rounded-3">
                <i class="bi bi-info-circle-fill me-2"></i>
                <strong>Status Kelayakan:</strong> 
                <?php if($peringkat_saya == 1): ?>
                    <span class="text-success fw-bold">Kamu menempati peringkat tertinggi! Prioritas Utama Penerima Beasiswa.</span>
                <?php elseif($skor_saya >= 0.7): ?>
                    <span class="text-primary fw-bold">Nilai kriteria kamu sangat kompetitif dan layak dipertimbangkan.</span>
                <?php else: ?>
                    <span class="text-secondary fw-bold">Data kamu aman di sistem. Keputusan mutlak kuota kelulusan berada di tangan pihak admin kampus.</span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>