<?php
$koneksi = new mysqli("localhost", "root", "", "kuesioner_db");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Proses tambah soal
    if (isset($_POST['tambah'])) {
        $pertanyaan = $koneksi->real_escape_string($_POST['pertanyaan']);
        $koneksi->query("INSERT INTO soal (pertanyaan) VALUES ('$pertanyaan')");
    }

    // Proses edit soal
    if (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $pertanyaan = $koneksi->real_escape_string($_POST['pertanyaan']);
        $koneksi->query("UPDATE soal SET pertanyaan='$pertanyaan' WHERE id='$id'");
    }

    // Proses hapus soal
    if (isset($_POST['hapus'])) {
        $id = $_POST['id'];

        // Pastikan ID valid dan bukan kosong
        if (!empty($id) && is_numeric($id)) {
            // Debug: Cek apakah ID diterima dengan benar
            echo "ID yang diterima untuk dihapus: " . $id . "<br>";

            // Hapus data terkait di tabel hasil
            $query_hapus_hasil = "DELETE FROM hasil WHERE id_soal='$id'";
            $koneksi->query($query_hapus_hasil);

            // Hapus soal setelah data terkait dihapus
            $query_hapus_soal = "DELETE FROM soal WHERE id='$id'";
            $result = $koneksi->query($query_hapus_soal);

            // Debug: Periksa apakah query dieksekusi dengan benar
            if ($result) {
                echo "Soal berhasil dihapus.<br>";
            } else {
                echo "Gagal menghapus soal. Error: " . $koneksi->error . "<br>";
            }
        } else {
            echo "ID tidak valid atau kosong.<br>";
        }
    }
}

// Ambil data soal dari database
$soal = $koneksi->query("SELECT * FROM soal");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Kelola Soal Kuesioner</title>
</head>

<body>
    <!-- Tombol Kembali ke Halaman Utama -->
    <a href="index.php">Kembali ke Halaman Utama</a><br><br>

    <h1>Kelola Soal Kuesioner</h1>

    <!-- Form Tambah Soal Baru -->
    <h2>Tambah Soal Baru</h2>
    <form action="kelola_soal.php" method="POST">
        <label for="pertanyaan">Pertanyaan:</label><br>
        <input type="text" name="pertanyaan" required><br><br>
        <button type="submit" name="tambah">Tambah Soal</button>
    </form>

    <!-- Tabel Soal -->
    <h2>Daftar Soal</h2>
    <table border="1">
        <tr>
            <th>Pertanyaan</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = $soal->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['pertanyaan']; ?></td>
            <td>
                <!-- Form Edit Soal -->
                <form action="kelola_soal.php" method="POST" style="display:inline-block;">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <input type="text" name="pertanyaan" value="<?php echo $row['pertanyaan']; ?>">
                    <button type="submit" name="edit">Edit</button>
                </form>

                <!-- Form Hapus Soal -->
                <form action="kelola_soal.php" method="POST" style="display:inline-block;">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="hapus"
                        onclick="return confirm('Anda yakin ingin menghapus soal ini?')">Hapus</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>

</html>