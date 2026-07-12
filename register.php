<?php
include 'koneksi.php';
$pesan = "";

if (isset($_POST['register'])) {
    $username = $_POST['username']; // NIM digunakan sebagai username
    $nama = $_POST['nama'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Cek apakah NIM sudah terdaftar
    $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($cek) > 0) {
        $pesan = "<div class='alert alert-danger'>NIM sudah terdaftar!</div>";
    } else {
        $query = "INSERT INTO users (username, password, nama_lengkap, role) VALUES ('$username', '$password', '$nama', 'mahasiswa')";
        if (mysqli_query($koneksi, $query)) {
            $pesan = "<div class='alert alert-success'>Akun berhasil dibuat! Silakan <a href='login.php'>Login</a></div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><title>Sign Up - SPPK Beasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="height: 100vh;">
<div class="container col-md-4">
    <div class="card p-4 shadow-sm">
        <h4 class="text-center text-primary mb-3">Daftar Akun Mahasiswa</h4>
        <?= $pesan; ?>
        <form action="" method="POST">
            <div class="mb-3"><label class="form-label">NIM (Akan jadi Username)</label><input type="text" name="username" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Nama Lengkap</label><input type="text" name="nama" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
            <button type="submit" name="register" class="btn btn-primary w-100 mb-2">Sign Up</button>
            <p class="text-center small">Sudah punya akun? <a href="login.php">Login di sini</a></p>
        </form>
    </div>
</div>
</body>
</html>