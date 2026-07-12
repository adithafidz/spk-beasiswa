<?php
session_start();

// PROTEKSI KETAT: Jika bukan admin, tendang kembali ke halaman login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';
$pesan = "";

if (isset($_POST['register_admin'])) {
    $username = $_POST['username'];
    $nama = $_POST['nama'];
    // Menggunakan password_hash agar sinkron dengan sistem login utama kita
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 

    // Cek apakah username admin sudah dipakai
    $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($cek) > 0) {
        $pesan = "<div class='alert alert-danger text-center small py-2'>Username admin sudah terdaftar! Gunakan username lain.</div>";
    } else {
        // Query menyisipkan user baru langsung dengan role 'admin'
        $query = "INSERT INTO users (username, password, nama_lengkap, role) VALUES ('$username', '$password', '$nama', 'admin')";
        if (mysqli_query($koneksi, $query)) {
            $pesan = "<div class='alert alert-success text-center small py-2'>Akun Admin Baru Berhasil Dibuat!</div>";
        } else {
            $pesan = "<div class='alert alert-danger text-center small py-2'>Gagal: " . mysqli_error($koneksi) . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Administrator Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-dark d-flex align-items-center justify-content-center" style="height: 100vh;">

<div class="container col-11 col-sm-8 col-md-6 col-lg-4">
    <div class="card border-0 shadow-lg p-4 bg-white" style="border-radius: 15px;">
        <div class="text-center mb-4">
            <i class="bi bi-person-plus-fill text-warning fs-1"></i>
            <h4 class="fw-bold text-dark mt-2">TAMBAH ADMIN BARU</h4>
            <p class="text-muted small">Daftarkan admin baru untuk sistem SPK Beasiswa</p>
        </div>

        <?= $pesan; ?>

        <form action="" method="POST">
            <div class="mb-3">
                <label class="form-label small fw-bold text-secondary">Username Admin</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-person-badge-fill text-secondary"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold text-secondary">Nama Lengkap</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-card-text text-secondary"></i></span>
                    <input type="text" name="nama" class="form-control" placeholder="Nama lengkap admin baru" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold text-secondary">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-lock-fill text-secondary"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                </div>
            </div>

            <button type="submit" name="register_admin" class="btn btn-warning w-100 py-2 fw-bold text-dark shadow-sm mb-2">
                Daftarkan Admin Baru
            </button>
            
            <div class="text-center mt-3">
                <a href="admin.php" class="text-decoration-none small text-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bundle.min.js"></script>
</body>
</html>