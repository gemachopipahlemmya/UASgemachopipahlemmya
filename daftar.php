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
$id_magang = isset($_GET['id_magang']) ? $_GET['id_magang'] : 0;

// Ambil data magang berdasarkan id_magang
$sql = "SELECT * FROM magang WHERE id_magang = $id_magang";
$result = $conn->query($sql);
$magang = $result->fetch_assoc();

if (!$magang) {
    die("Magang tidak ditemukan.");
}

// Proses pendaftaran
if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];
    $asal_sekolah = $_POST['asal_sekolah'];
    $jurusan = $_POST['jurusan'];
    $telepon = $_POST['telepon'];

    // Proses unggah foto
    $foto = "";
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto = $_FILES['foto']['name'];
        $target_dir = "uploads/"; // Direktori untuk menyimpan foto
        $target_file = $target_dir . basename($_FILES['foto']['name']);
        move_uploaded_file($_FILES['foto']['tmp_name'], $target_file);
    }

    // Query untuk menyimpan data pendaftaran
    $sql_insert = "INSERT INTO pendataan (id_magang, nama, email, tanggal_lahir, alamat, asal_sekolah, jurusan, telepon, foto) 
                   VALUES ('$id_magang', '$nama', '$email', '$tanggal_lahir', '$alamat', '$asal_sekolah', '$jurusan', '$telepon', '$foto')";

    if ($conn->query($sql_insert) === TRUE) {
        echo "Pendaftaran berhasil!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Magang</title>
    <style>
        label {
            display: block;
            margin-top: 10px;
        }
        input, textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
        }
        .submit-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
        }
        .submit-btn:hover {
            background-color: #45a049;
        }
        .back-btn {
            background-color: #f44336;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            margin-top: 20px;
        }
        .back-btn:hover {
            background-color: #e53935;
        }
    </style>
</head>
<body>
    <h1>Pendaftaran Magang: <?php echo $magang['magang']; ?></h1>
    <p>Lokasi: <?php echo $magang['lokasi']; ?></p>

    <h2>Formulir Pendaftaran</h2>
    <form action="daftar.php?id_magang=<?php echo $id_magang; ?>" method="post" enctype="multipart/form-data">
        <label for="nama">Nama Lengkap:</label>
        <input type="text" id="nama" name="nama" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="tanggal_lahir">Tanggal Lahir:</label>
        <input type="date" id="tanggal_lahir" name="tanggal_lahir" required>

        <label for="alamat">Alamat:</label>
        <textarea id="alamat" name="alamat" required></textarea>

        <label for="asal_sekolah">Asal Sekolah:</label>
        <input type="text" id="asal_sekolah" name="asal_sekolah" required>

        <label for="jurusan">Jurusan:</label>
        <input type="text" id="jurusan" name="jurusan" required>

        <label for="telepon">Nomor Telepon:</label>
        <input type="text" id="telepon" name="telepon" required>

        <label for="foto">Foto (JPG/PNG):</label>
        <input type="file" id="foto" name="foto" accept="image/*" required>

        <button type="submit" name="submit" class="submit-btn">Daftar</button>
    </form>

    <!-- Tombol Kembali ke Halaman Utama -->
    <a href="index.php">
        <button class="back-btn">Kembali ke Halaman Utama</button>
    </a>

</body>
</html>

<?php
// Tutup koneksi
$conn->close();
?>
