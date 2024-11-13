<?php
// Koneksi ke database
$koneksi = new mysqli("localhost", "root", "", "kuesioner_db");

// Ambil data yang dikirim dari kuesioner.php
$nama = $_POST['nama'];
$jawaban = $_POST['jawaban']; // Array dengan format soal_id => jawaban

// Validasi input
if (empty($nama) || empty($jawaban)) {
    die("Nama dan jawaban tidak boleh kosong.");
}

$koneksi->query("INSERT INTO responden (nama) VALUES ('$nama')");
$id_responden = $koneksi->insert_id; // Ambil id responden yang baru saja dimasukkan

// Simpan jawaban ke tabel hasil
foreach ($jawaban as $id_soal => $jawaban_user) {
    // Cari id jawaban yang sesuai
    $jawaban_db = $koneksi->query("SELECT id FROM jawaban WHERE jawaban = '$jawaban_user'")->fetch_assoc();
    $id_jawaban = $jawaban_db['id'];

    // Insert jawaban ke tabel hasil
    $koneksi->query("INSERT INTO hasil (id_responden, id_soal, id_jawaban) VALUES ($id_responden, $id_soal, $id_jawaban)");
}

// Redirect setelah pengisian kuesioner berhasil
header("Location: terima_kasih.php");
exit();