<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';
// ... sisa kode admin di bawahnya ...

include 'koneksi.php';

// --- PROSES PERHITUNGAN ALGORITMA SAW ---
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
        'id_alternatif' => $id_alt,
        'nim' => $alt['nim'],
        'nama' => $alt['nama_mahasiswa'],
        'skor' => round($nilai_v, 4)
    ];
}
// Urutkan Ranking DESC
usort($hasil_akhir, function($a, $b) { return $b['skor'] <=> $a['skor']; });
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin SPK Beasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="#">Dashboard Admin SPK Beasiswa</a>
        <div>
            <a href="register_admin.php" class="btn btn-sm btn-warning me-2">
                <i class="bi bi-person-plus-fill me-1"></i>Tambah Admin Baru
            </a>
            <a href="logout.php" class="btn btn-sm btn-danger">Log Out</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="card shadow-sm p-4 mb-5">
        <h4 class="text-success mb-3">Hasil Perankingan Terkini</h4>
        <div class="table-responsive">
            <table class="table table-striped table-bordered text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Peringkat</th>
                        <th>NIM</th>
                        <th>Nama Lengkap</th>
                        <th>Skor Akhir (V)</th>
                        <th>Status Kelayakan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($hasil_akhir)): ?>
                        <tr><td colspan="5">Belum ada data pendaftar.</td></tr>
                    <?php else: 
                        $rank = 1;
                        foreach($hasil_akhir as $h): ?>
                        <tr>
                            <td class="fw-bold"><?= $rank; ?></td>
                            <td><?= $h['nim']; ?></td>
                            <td class="text-start"><?= $h['nama']; ?></td>
                            <td class="text-primary fw-bold"><?= $h['skor']; ?></td>
                            <td>
                                <?php if($rank == 1): ?>
                                    <span class="badge bg-danger">Prioritas Utama</span>
                                <?php elseif($h['skor'] >= 0.7): ?>
                                    <span class="badge bg-success">Sangat Layak</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Dipertimbangkan</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php $rank++; endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card shadow-sm p-4">
        <h4 class="text-secondary mb-3">Kelola Data Pendaftar</h4>
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle text-center">
                <thead class="table-secondary">
                    <tr>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>C1 (IPK)</th>
                        <th>C2 (Gaji)</th>
                        <th>C3 (Anak)</th>
                        <th>C4 (Prestasi)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($alternatif as $id_alt => $alt): ?>
                    <tr>
                        <td><?= $alt['nim']; ?></td>
                        <td class="text-start"><?= $alt['nama_mahasiswa']; ?></td>
                        <td><?= isset($matrix_x[$id_alt][1]) ? $matrix_x[$id_alt][1] : '-'; ?></td>
                        <td><?= isset($matrix_x[$id_alt][2]) ? $matrix_x[$id_alt][2] : '-'; ?></td>
                        <td><?= isset($matrix_x[$id_alt][3]) ? $matrix_x[$id_alt][3] : '-'; ?></td>
                        <td><?= isset($matrix_x[$id_alt][4]) ? $matrix_x[$id_alt][4] : '-'; ?></td>
                        <td>
                            <a href="edit.php?id=<?= $id_alt; ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="delete.php?id=<?= $id_alt; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>