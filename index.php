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

// Ambil data magang dan jumlah pendaftar
$sql = "SELECT m.id_magang, m.magang, m.lokasi, COUNT(p.id_pendataan) AS total_pendaftar 
        FROM magang m
        LEFT JOIN pendataan p ON m.id_magang = p.id_magang
        GROUP BY m.id_magang";
$result = $conn->query($sql);

// Tutup koneksi setelah data diambil
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pendaftaran Magang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
            color: #333;
            font-weight: bold;
        }
        td {
            background-color: #fff;
        }
        tr:nth-child(even) td {
            background-color: #f9f9f9;
        }
        tr:hover td {
            background-color: #f1f1f1;
        }
        .btn-admin, .btn-pimpinan {
            background-color: #008CBA;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            margin-top: 20px;
            transition: background-color 0.3s;
        }
        .btn-admin:hover, .btn-pimpinan:hover {
            background-color: #007bb5;
        }
        .btn-pimpinan {
            background-color: #f44336;
        }
        .btn-pimpinan:hover {
            background-color: #e53935;
        }
        /* Responsive design */
        @media (max-width: 768px) {
            table, th, td {
                font-size: 14px;
                padding: 10px;
            }
            .btn-admin, .btn-pimpinan {
                width: 100%;
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>
    <h1>Laporan Pendaftaran Magang</h1>

    <!-- Tombol Admin -->
    <a href="admin.php" class="btn-admin">Admin</a>

    <!-- Tombol Pimpinan -->
    <a href="pimpinan.php" class="btn-pimpinan">Pimpinan</a>

    <!-- Menampilkan laporan pendaftaran -->
    <table>
        <thead>
            <tr>
                <th>ID Magang</th>
                <th>Nama Magang</th>
                <th>Lokasi</th>
                <th>Total Pendaftar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row["id_magang"]) ?></td>
                        <td><?= htmlspecialchars($row["magang"]) ?></td>
                        <td><?= htmlspecialchars($row["lokasi"]) ?></td>
                        <td><?= htmlspecialchars($row["total_pendaftar"]) ?></td>
                        <td><a href="formulir.php?id_magang=<?= urlencode($row["id_magang"]) ?>" class="btn-admin">Lihat Pendaftar</a></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Tidak ada data magang.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
