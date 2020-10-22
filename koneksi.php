<?php
$server   = "localhost";
$username = "root";
$password = "";
$database = "project_tm_tfidf";

// Koneksi dan memilih database di server
$conn = mysqli_connect($server,$username,$password) or die("Koneksi gagal");
mysqli_select_db($conn, $database) or die("Database tidak ditemukan");
?>