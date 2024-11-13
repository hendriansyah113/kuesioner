<?php
// Koneksi ke database
$host = "localhost";
$username = "root";
$password = "";
$dbname = "kuesioner_db"; // Ganti dengan nama database Anda

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil semua soal
$soalQuery = "SELECT id, pertanyaan FROM soal";
$soalResult = $conn->query($soalQuery);

// Menyiapkan data untuk Chart.js
$chartData = [];
$soalList = [];
while ($row = $soalResult->fetch_assoc()) {
    $soalList[] = $row['pertanyaan'];
    $soalId = $row['id'];

    // Query untuk menghitung jumlah jawaban untuk setiap soal
    $jawabanQuery = "
        SELECT 
            COUNT(CASE WHEN jawaban.jawaban = 'Sangat tidak setuju' THEN 1 END) AS sangat_tidak_setuju,
            COUNT(CASE WHEN jawaban.jawaban = 'Tidak setuju' THEN 1 END) AS tidak_setuju,
            COUNT(CASE WHEN jawaban.jawaban = 'Netral' THEN 1 END) AS netral,
            COUNT(CASE WHEN jawaban.jawaban = 'Setuju' THEN 1 END) AS setuju,
            COUNT(CASE WHEN jawaban.jawaban = 'Sangat setuju' THEN 1 END) AS sangat_setuju
        FROM hasil
        JOIN jawaban ON hasil.id_jawaban = jawaban.id
        WHERE hasil.id_soal = $soalId
    ";

    $jawabanResult = $conn->query($jawabanQuery);
    $jawabanData = $jawabanResult->fetch_assoc();

    $chartData[] = [
        'soal' => $row['pertanyaan'],
        'sangat_tidak_setuju' => $jawabanData['sangat_tidak_setuju'],
        'tidak_setuju' => $jawabanData['tidak_setuju'],
        'netral' => $jawabanData['netral'],
        'setuju' => $jawabanData['setuju'],
        'sangat_setuju' => $jawabanData['sangat_setuju']
    ];
}

// Query untuk menampilkan data tabel hasil kuesioner
$tabelQuery = "
   SELECT 
    responden.id AS id_responden,
    responden.nama, 
    hasil.id_soal, 
    jawaban.jawaban
FROM hasil
JOIN responden ON hasil.id_responden = responden.id
JOIN soal ON hasil.id_soal = soal.id
JOIN jawaban ON hasil.id_jawaban = jawaban.id
ORDER BY responden.id, soal.id
";
$tabelResult = $conn->query($tabelQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kuesioner</title>
    <!-- Tombol Kembali ke Halaman Utama -->
    <a href="index.php">Kembali ke Halaman Utama</a><br><br>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
    .container {
        width: 80%;
        margin: 0 auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 30px;
    }

    table,
    th,
    td {
        border: 1px solid black;
    }

    th,
    td {
        padding: 10px;
        text-align: center;
    }
    </style>
</head>

<body>
    <div class="container">
        <h1>Dashboard Kuesioner</h1>

        <!-- Bar Chart -->
        <canvas id="barChart" width="400" height="200"></canvas>

        <script>
        var chartData = <?php echo json_encode($chartData); ?>;
        var labels = chartData.map(function(item) {
            return item.soal;
        });
        var sangat_tidak_setuju = chartData.map(function(item) {
            return item.sangat_tidak_setuju;
        });
        var tidak_setuju = chartData.map(function(item) {
            return item.tidak_setuju;
        });
        var netral = chartData.map(function(item) {
            return item.netral;
        });
        var setuju = chartData.map(function(item) {
            return item.setuju;
        });
        var sangat_setuju = chartData.map(function(item) {
            return item.sangat_setuju;
        });

        var ctx = document.getElementById('barChart').getContext('2d');
        var barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                        label: 'Sangat Tidak Setuju',
                        data: sangat_tidak_setuju,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Tidak Setuju',
                        data: tidak_setuju,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Netral',
                        data: netral,
                        backgroundColor: 'rgba(255, 206, 86, 0.2)',
                        borderColor: 'rgba(255, 206, 86, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Setuju',
                        data: setuju,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Sangat Setuju',
                        data: sangat_setuju,
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        </script>

        <!-- Tabel Hasil Kuesioner -->
        <h2>Data Hasil Kuesioner</h2>
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <?php foreach ($soalList as $soal) { ?>
                    <th><?php echo $soal; ?></th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                // Ambil data hasil kuesioner berdasarkan id_responden
                $dataResponden = [];
                while ($row = $tabelResult->fetch_assoc()) {
                    // Gunakan id_responden sebagai kunci utama
                    $dataResponden[$row['id_responden']][$row['id_soal']] = $row['jawaban'];
                }

                // Menampilkan hasil kuesioner per responden
                foreach ($dataResponden as $id_responden => $jawaban) {
                    // Ambil nama responden berdasarkan id_responden
                    $respondenQuery = $conn->query("SELECT nama FROM responden WHERE id = '$id_responden'");
                    $respondenData = $respondenQuery->fetch_assoc();
                ?>
                <tr>
                    <td><?php echo $respondenData['nama']; ?></td>
                    <?php foreach ($soalList as $soalIndex => $soal) { ?>
                    <td><?php echo isset($jawaban[$soalIndex + 1]) ? $jawaban[$soalIndex + 1] : '-'; ?></td>
                    <?php } ?>
                </tr>
                <?php } ?>
            </tbody>
        </table>

    </div>
</body>

</html>

<?php
// Menutup koneksi
$conn->close();
?>