<?php
// Koneksi ke database
$host = "localhost"; // Ganti dengan host jika perlu
$username = "root"; // Ganti dengan username database
$password = ""; // Ganti dengan password database
$dbname = "pendataan_magang"; // Ganti dengan nama database Anda

$conn = new mysqli($host, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil id_magang dari URL
$id_magang = isset($_GET['id_magang']) ? $_GET['id_magang'] : '';

// Ambil data pendaftar berdasarkan id_magang
$sql = "SELECT p.id_pendataan, p.nama, p.alamat, p.email, p.telepon, p.tanggal_lahir, m.magang, m.lokasi
        FROM pendataan p
        JOIN magang m ON p.id_magang = m.id_magang
        WHERE p.id_magang = $id_magang";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pendaftar Magang</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        .btn-back {
            background-color: #008CBA;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            margin-top: 20px;
        }
        .btn-back:hover {
            background-color: #007bb5;
        }
    </style>
</head>
<body>
    <h1>Detail Pendaftar Magang</h1>

    <!-- Tombol Kembali -->
    <a href="admin.php" class="btn-back">Kembali ke Halaman Admin</a>

    <!-- Menampilkan detail pendaftar -->
    <table>
        <thead>
            <tr>
                <th>ID Pendaftar</th>
                <th>Nama Pendaftar</th>
                <th>Alamat</th>
                <th>Email</th>
                <th>No. Telepon</th>
                <th>Tanggal Lahir</th>
                <th>Magang</th>
                <th>Lokasi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Output data setiap baris
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>".$row["id_pendataan"]."</td>
                            <td>".$row["nama"]."</td>
                            <td>".$row["alamat"]."</td>
                            <td>".$row["email"]."</td>
                            <td>".$row["telepon"]."</td>
                            <td>".$row["tanggal_lahir"]."</td>
                            <td>".$row["magang"]."</td>
                            <td>".$row["lokasi"]."</td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>Tidak ada data pendaftar untuk magang ini.</td></tr>";
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
