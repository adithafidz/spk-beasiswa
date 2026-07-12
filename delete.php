<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';
// ... sisa kode admin di bawahnya ...

include 'koneksi.php';

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Hapus data dari tabel alternatif
    $query = "DELETE FROM alternatif WHERE id_alternatif = $id";
    if(mysqli_query($koneksi, $query)) {
        header("Location: admin.php");
    } else {
        echo "Gagal menghapus data: " . mysqli_error($koneksi);
    }
}
?>