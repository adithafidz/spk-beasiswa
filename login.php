<?php
session_start();
include 'koneksi.php';
$pesan = "";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role_input = $_POST['role']; // Mengambil role dari hidden input / tab yang aktif

    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username' AND role = '$role_input'");
    
    if (mysqli_num_rows($query) === 1) {
        $row = mysqli_fetch_assoc($query);
        if (password_verify($password, $row['password']) || md5($password) === $row['password']) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['nama'] = $row['nama_lengkap'];
            $_SESSION['role'] = $row['role'];

            if ($row['role'] == 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: daftar.php");
            }
            exit;
        }
    }
    $pesan = "<div class='alert alert-danger text-center small py-2'>Username atau Password untuk role " . ucfirst($role_input) . " salah!</div>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Login - SPK Beasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body {
            transition: background-color 0.5s ease;
            background-color: #e9ecef; /* Default background */
            height: 100vh;
        }
        .login-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .nav-pills .nav-link {
            border-radius: 30px;
            padding: 10px 25px;
            color: #6c757d;
            font-weight: 500;
        }
        .nav-pills .nav-link.active.mhs-tab {
            background-color: #0d6efd !important; /* Biru untuk Mahasiswa */
            color: white;
        }
        .nav-pills .nav-link.active.admin-tab {
            background-color: #212529 !important; /* Hitam Gelap untuk Admin */
            color: white;
        }
        .btn-login {
            transition: all 0.3s ease;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center">

<div class="container col-11 col-sm-8 col-md-6 col-lg-4">
    <ul class="nav nav-pills nav-justified mb-3 bg-white p-1 rounded-pill shadow-sm" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active mhs-tab" id="tab-mahasiswa" type="button" onclick="switchRole('mahasiswa')">
                <i class="bi bi-mortarboard-fill me-2"></i>Mahasiswa
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link admin-tab" id="tab-admin" type="button" onclick="switchRole('admin')">
                <i class="bi bi-shield-lock-fill me-2"></i>Admin
            </button>
        </li>
    </ul>

    <div class="card login-card p-4 bg-white">
        <div class="text-center mb-4">
            <h3 class="fw-bold id-title text-primary" id="login-title">LOGIN MAHASISWA</h3>
            <p class="text-muted small" id="login-desc">Silakan masukkan NIM dan Password untuk mengisi data beasiswa</p>
        </div>

        <?= $pesan; ?>

        <form action="" method="POST">
            <input type="hidden" name="role" id="role-input" value="mahasiswa">

            <div class="mb-3">
                <label class="form-label small fw-bold text-secondary" id="label-username">NIM / Username</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-secondary"><i class="bi bi-person-fill"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan NIM Anda" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold text-secondary">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-secondary"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                </div>
            </div>

            <button type="submit" name="login" id="btn-submit" class="btn btn-primary btn-login w-100 py-2 fw-bold shadow-sm">
                Sign In Sebagai Mahasiswa
            </button>

            <div id="register-container" class="text-center mt-4">
                <p class="text-muted small mb-0">Belum memiliki akun? <a href="register.php" class="text-decoration-none fw-bold">Daftar Akun Baru</a></p>
            </div>
        </form>
    </div>
    <p class="text-center text-muted small mt-4">&copy; 2026 SPK Beasiswa - Tugas Pemrograman Web Lanjut</p>
</div>

<script>
function switchRole(role) {
    // 1. Ambil semua elemen UI yang mau diubah
    const roleInput = document.getElementById('role-input');
    const title = document.getElementById('login-title');
    const desc = document.getElementById('login-desc');
    const labelUsername = document.getElementById('label-username');
    const btnSubmit = document.getElementById('btn-submit');
    const regContainer = document.getElementById('register-container');
    const inputUsername = document.getElementsByName('username')[0];
    
    // 2. Set value hidden input agar PHP tahu role apa yang men-submit
    roleInput.value = role;

    // 3. Kondisi jika Tombol Admin diklik
    if (role === 'admin') {
        document.body.style.backgroundColor = '#212529'; // Background body jadi gelap
        title.innerText = 'LOGIN ADMINISTRATOR';
        title.className = 'fw-bold text-dark';
        desc.innerText = 'Area khusus admin untuk mengelola data & melihat peringkat SAW';
        labelUsername.innerText = 'Username Admin';
        inputUsername.placeholder = 'Masukkan ID Admin';
        
        btnSubmit.innerText = 'Sign In Sebagai Admin';
        btnSubmit.className = 'btn btn-dark btn-login w-100 py-2 fw-bold shadow-sm';
        
        regContainer.style.display = 'none'; // Sembunyikan link "Daftar Akun" karena admin tidak bisa daftar sendiri
        
        // Atur fokus tombol tab aktif
        document.getElementById('tab-admin').classList.add('active');
        document.getElementById('tab-mahasiswa').classList.remove('active');
    } 
    // 4. Kondisi jika Tombol Mahasiswa diklik (Default)
    else {
        document.body.style.backgroundColor = '#e9ecef'; // Background kembali abu-abu terang
        title.innerText = 'LOGIN MAHASISWA';
        title.className = 'fw-bold text-primary';
        desc.innerText = 'Silakan masukkan NIM dan Password untuk mengisi data beasiswa';
        labelUsername.innerText = 'NIM / Username';
        inputUsername.placeholder = 'Masukkan NIM Anda';
        
        btnSubmit.innerText = 'Sign In Sebagai Mahasiswa';
        btnSubmit.className = 'btn btn-primary btn-login w-100 py-2 fw-bold shadow-sm';
        
        regContainer.style.display = 'block'; // Munculkan kembali link daftar akun
        
        document.getElementById('tab-mahasiswa').classList.add('active');
        document.getElementById('tab-admin').classList.remove('active');
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bundle.min.js"></script>
</body>
</html>