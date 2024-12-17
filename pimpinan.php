<?php
// Koneksi ke database
$host = "localhost";
$username = "root";
$password = "";
$dbname = "pendataan_magang";

$conn = new mysqli($host, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Fungsi untuk men-generate CSV
if (isset($_GET['download'])) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=data_pendaftar.csv');
    $output = fopen("php://output", "w");
    // Tambahkan header kolom
    fputcsv($output, ['ID', 'Nama', 'Email', 'Tanggal Lahir', 'Alamat', 'Asal Sekolah', 'Jurusan', 'Telepon', 'Foto']);
    
    // Ambil data dari database
    $sql = "SELECT * FROM pendataan";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, $row);
        }
    }
    fclose($output);
    exit();
}

// Ambil data untuk ditampilkan di tabel
$sql = "SELECT * FROM pendataan";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pendaftar Magang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .buttons {
            margin-bottom: 20px;
        }
        .buttons button, .buttons a {
            margin-right: 10px;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
        }
        .print-btn {
            background-color: #4CAF50;
            color: white;
        }
        .print-btn:hover {
            background-color: #45a049;
        }
        .download-btn {
            background-color: #008CBA;
            color: white;
        }
        .download-btn:hover {
            background-color: #007bb5;
        }
        .back-btn {
            background-color: #f44336;
            color: white;
        }
        .back-btn:hover {
            background-color: #d32f2f;
        }
    </style>
    <script>
        // Fungsi untuk mencetak halaman
        function printPage() {
            window.print();
        }
    </script>
</head>
<body>
    <h1>Data Pendaftar Magang</h1>

    <div class="buttons">
        <!-- Tombol Cetak -->
        <button class="print-btn" onclick="printPage()">Cetak</button>
        <!-- Tombol Download -->
        <a class="download-btn" href="?download=true">Download File</a>
        <!-- Tombol Kembali -->
        <a class="back-btn" href="index.php">Kembali</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Tanggal Lahir</th>
                <th>Alamat</th>
                <th>Asal Sekolah</th>
                <th>Jurusan</th>
                <th>Telepon</th>
                <th>Foto</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['id_pendataan']}</td>
                        <td>{$row['nama']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['tanggal_lahir']}</td>
                        <td>{$row['alamat']}</td>
                        <td>{$row['asal_sekolah']}</td>
                        <td>{$row['jurusan']}</td>
                        <td>{$row['telepon']}</td>
                        <td><img src='uploads/{$row['foto']}' alt='Foto' width='50'></td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='9'>Tidak ada data pendaftar.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>

<?php
// Tutup koneksi
$conn->close();
?>
