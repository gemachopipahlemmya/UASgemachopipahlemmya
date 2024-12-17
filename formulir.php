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

// Ambil data magang untuk pilihan dropdown
$sql = "SELECT id_magang, magang FROM magang";
$result = $conn->query($sql);

// Proses data jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $email = $_POST['email'];
    $telepon = $_POST['telepon'];
    $id_magang = $_POST['id_magang'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $asal_sekolah = $_POST['asal_sekolah'];
    $jurusan = $_POST['jurusan'];

    // Proses upload foto
    $foto = $_FILES['foto']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($foto);
    $upload_ok = 1;

    // Cek jika file adalah gambar
    $check = getimagesize($_FILES['foto']['tmp_name']);
    if ($check === false) {
        $upload_ok = 0;
        echo "<script>alert('File yang diunggah bukan gambar!');</script>";
    }

    // Jika upload berhasil
    if ($upload_ok && move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
        // Insert data ke tabel pendataan
        $stmt = $conn->prepare("INSERT INTO pendataan 
            (id_magang, nama, email, tanggal_lahir, alamat, asal_sekolah, jurusan, telepon, foto) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssss", $id_magang, $nama, $email, $tanggal_lahir, $alamat, $asal_sekolah, $jurusan, $telepon, $foto);

        if ($stmt->execute()) {
            echo "<script>alert('Pendaftaran berhasil!'); window.location.href = 'berhasil.php';</script>";
        } else {
            echo "<script>alert('Pendaftaran gagal! Silakan coba lagi.');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Gagal mengunggah foto.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pendaftaran Magang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        form {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        form input, form textarea, form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        form button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        form button:hover {
            background-color: #45a049;
        }
        .btn-back {
            display: inline-block;
            margin-top: 20px;
            background-color: #008CBA;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 4px;
        }
        .btn-back:hover {
            background-color: #007bb5;
        }
    </style>
</head>
<body>
    <h1>Formulir Pendaftaran Magang</h1>

    <form method="POST" action="" enctype="multipart/form-data">
        <label for="nama">Nama Pendaftar</label>
        <input type="text" id="nama" name="nama" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="tanggal_lahir">Tanggal Lahir</label>
        <input type="date" id="tanggal_lahir" name="tanggal_lahir" required>

        <label for="alamat">Alamat</label>
        <textarea id="alamat" name="alamat" rows="4" required></textarea>

        <label for="asal_sekolah">Asal Sekolah</label>
        <input type="text" id="asal_sekolah" name="asal_sekolah" required>

        <label for="jurusan">Jurusan</label>
        <input type="text" id="jurusan" name="jurusan" required>

        <label for="telepon">No. Telepon</label>
        <input type="text" id="telepon" name="telepon" required>

        <label for="foto">Unggah Foto</label>
        <input type="file" id="foto" name="foto" accept="image/*" required>

        <label for="id_magang">Pilih Program Magang</label>
        <select id="id_magang" name="id_magang" required>
            <option value="">-- Pilih Program Magang --</option>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id_magang'] . "'>" . $row['magang'] . "</option>";
                }
            } else {
                echo "<option value=''>Tidak ada program magang tersedia</option>";
            }
            ?>
        </select>

        <button type="submit">Daftar</button>
    </form>
</body>
</html>

<?php
// Tutup koneksi
$conn->close();
?>
