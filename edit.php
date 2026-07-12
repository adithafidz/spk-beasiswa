<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';
// ... sisa kode admin di bawahnya ...

include 'koneksi.php';

$id = $_GET['id'];

// Ambil data lama
$alt_query = mysqli_query($koneksi, "SELECT * FROM alternatif WHERE id_alternatif = $id");
$data_alt = mysqli_fetch_assoc($alt_query);

$nilai_query = mysqli_query($koneksi, "SELECT * FROM nilai_alternatif WHERE id_alternatif = $id");
$nilai_lama = [];
while($r = mysqli_fetch_assoc($nilai_query)) {
    $nilai_lama[$r['id_kriteria']] = $r['nilai'];
}

if(isset($_POST['update'])) {
    $nama = $_POST['nama'];
    $c1 = $_POST['c1']; $c2 = $_POST['c2']; $c3 = $_POST['c3']; $c4 = $_POST['c4'];

    // Update nama
    mysqli_query($koneksi, "UPDATE alternatif SET nama_mahasiswa='$nama' WHERE id_alternatif=$id");

    // Update nilai masing-masing kriteria
    mysqli_query($koneksi, "UPDATE nilai_alternatif SET nilai=$c1 WHERE id_alternatif=$id AND id_kriteria=1");
    mysqli_query($koneksi, "UPDATE nilai_alternatif SET nilai=$c2 WHERE id_alternatif=$id AND id_kriteria=2");
    mysqli_query($koneksi, "UPDATE nilai_alternatif SET nilai=$c3 WHERE id_alternatif=$id AND id_kriteria=3");
    mysqli_query($koneksi, "UPDATE nilai_alternatif SET nilai=$c4 WHERE id_alternatif=$id AND id_kriteria=4");

    header("Location: admin.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Alternatif</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5 col-md-6">
    <div class="card p-4 shadow-sm">
        <h4 class="mb-4">Edit Data: <?= $data_alt['nim']; ?></h4>
        <form action="" method="POST">
            <div class="mb-3">
                <label class="form-label">Nama Mahasiswa</label>
                <input type="text" name="nama" class="form-control" value="<?= $data_alt['nama_mahasiswa']; ?>" required>
            </div>
            <div class="mb-3"><label class="form-label">Skor C1 (IPK)</label><input type="number" name="c1" class="form-control" min="1" max="5" value="<?= $nilai_lama[1]; ?>"></div>
            <div class="mb-3"><label class="form-label">Skor C2 (Gaji)</label><input type="number" name="c2" class="form-control" min="1" max="5" value="<?= $nilai_lama[2]; ?>"></div>
            <div class="mb-3"><label class="form-label">Skor C3 (Anak)</label><input type="number" name="c3" class="form-control" min="1" max="5" value="<?= $nilai_lama[3]; ?>"></div>
            <div class="mb-4"><label class="form-label">Skor C4 (Prestasi)</label><input type="number" name="c4" class="form-control" min="1" max="5" value="<?= $nilai_lama[4]; ?>"></div>
            
            <button type="submit" name="update" class="btn btn-success">Simpan Perubahan</button>
            <a href="admin.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
</body>
</html>