<?php
// Koneksi ke database
$koneksi = new mysqli("localhost", "root", "", "kuesioner_db");

// Ambil semua soal dari database
$soal = $koneksi->query("SELECT * FROM soal");
$soalData = [];
while ($row = $soal->fetch_assoc()) {
    $soalData[] = $row;
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Kuesioner</title>
</head>

<body>
    <!-- Tombol Kembali ke Halaman Utama -->
    <br><a href="index.php">Kembali ke Halaman Utama</a>
    <h1>Kuesioner Pengisian</h1>
    <form action="proses_jawaban.php" method="POST">
        <label for="nama">Nama Anda:</label>
        <input type="text" name="nama" required><br><br>

        <?php foreach ($soalData as $soal): ?>
        <fieldset>
            <legend><?php echo $soal['pertanyaan']; ?></legend>

            <label><input type="radio" name="jawaban[<?php echo $soal['id']; ?>]" value="Sangat Tidak Setuju" required>
                Sangat Tidak Setuju</label><br>
            <label><input type="radio" name="jawaban[<?php echo $soal['id']; ?>]" value="Tidak Setuju"> Tidak
                Setuju</label><br>
            <label><input type="radio" name="jawaban[<?php echo $soal['id']; ?>]" value="Netral"> Netral</label><br>
            <label><input type="radio" name="jawaban[<?php echo $soal['id']; ?>]" value="Setuju"> Setuju</label><br>
            <label><input type="radio" name="jawaban[<?php echo $soal['id']; ?>]" value="Sangat Setuju"> Sangat
                Setuju</label><br>
        </fieldset>
        <?php endforeach; ?>

        <button type="submit">Kirim Jawaban</button>
    </form>
</body>

</html>