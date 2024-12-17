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

// Cek jika ada permintaan hapus
if (isset($_GET['hapus_id'])) {
    $hapus_id = intval($_GET['hapus_id']);
    $sql_hapus = "DELETE FROM pendataan WHERE id_pendataan = ?";
    $stmt = $conn->prepare($sql_hapus);
    $stmt->bind_param("i", $hapus_id);
    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil dihapus'); window.location='admin.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data');</script>";
    }
    $stmt->close();
}

// Ambil data pendaftar
$sql = "SELECT p.id_pendataan, p.nama, p.email, p.tanggal_lahir, p.alamat, p.asal_sekolah, p.jurusan, p.telepon, p.foto
        FROM pendataan p";
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
        .btn-delete {
            background-color: #f44336;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 4px;
            margin-right: 10px;
            margin-top: 5px; /* Menambahkan jarak atas */
            margin-bottom: 5px; /* Menambahkan jarak bawah */
            display: inline-block;
        }
        .btn-delete:hover {
            background-color: #e53935;
        }
        .btn-edit {
            background-color: #ff9800;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 4px; /* Menambahkan jarak atas */
            margin-bottom: 4px; /* Menambahkan jarak bawah */
            display: inline-block;
        }
        .btn-edit:hover {
            background-color: #e68900;
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
        /* Responsif untuk perangkat kecil */
        @media (max-width: 768px) {
            table, th, td {
                font-size: 14px;
                padding: 10px;
            }
            .btn-back {
                width: 100%;
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>
    <h1>Data Pendaftar Magang</h1>

    <!-- Tombol Kembali -->
    <a href="index.php" class="btn-back">Kembali ke Halaman Utama</a>

    <!-- Menampilkan daftar pendaftar -->
    <table>
        <thead>
            <tr>
                <th>ID Pendaftar</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Tanggal Lahir</th>
                <th>Alamat</th>
                <th>Asal Sekolah</th>
                <th>Jurusan</th>
                <th>Telepon</th>
                <th>Foto</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Output data setiap baris
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row["id_pendataan"] . "</td>
                            <td>" . $row["nama"] . "</td>
                            <td>" . $row["email"] . "</td>
                            <td>" . $row["tanggal_lahir"] . "</td>
                            <td>" . $row["alamat"] . "</td>
                            <td>" . $row["asal_sekolah"] . "</td>
                            <td>" . $row["jurusan"] . "</td>
                            <td>" . $row["telepon"] . "</td>
                            <td><img src='uploads/" . $row["foto"] . "' alt='Foto' width='50'></td>
                            <td>
                                <a href='edit_pendaftar.php?id_pendataan=" . $row["id_pendataan"] . "' class='btn-edit'>Edit</a>
                                <a href='admin.php?hapus_id=" . $row["id_pendataan"] . "' class='btn-delete' onclick='return confirm(\"Yakin ingin menghapus data ini?\");'>Hapus</a>
                            </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='10'>Tidak ada data pendaftar.</td></tr>";
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
