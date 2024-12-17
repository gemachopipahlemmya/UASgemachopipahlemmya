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

// Ambil ID pendaftar dari parameter URL
$id_pendataan = isset($_GET['id_pendataan']) ? intval($_GET['id_pendataan']) : 0;

// Ambil data pendaftar berdasarkan ID
$sql = "SELECT * FROM pendataan WHERE id_pendataan = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_pendataan);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    die("Pendaftar dengan ID tersebut tidak ditemukan.");
}

// Inisialisasi variabel untuk pesan sukses
$success_message = "";

// Proses data jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];
    $asal_sekolah = $_POST['asal_sekolah'];
    $jurusan = $_POST['jurusan'];
    $telepon = $_POST['telepon'];
    $id_magang = $_POST['id_magang'];

    // Proses upload foto jika ada file yang diunggah
    $foto = $data['foto']; // Foto lama
    if (isset($_FILES['foto']) && $_FILES['foto']['size'] > 0) {
        $foto = $_FILES['foto']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($foto);

        // Cek apakah file adalah gambar
        $check = getimagesize($_FILES['foto']['tmp_name']);
        if ($check !== false) {
            move_uploaded_file($_FILES['foto']['tmp_name'], $target_file);
        } else {
            echo "<script>alert('File yang diunggah bukan gambar!');</script>";
        }
    }

    // Update data di database
    $update_sql = "UPDATE pendataan 
                   SET nama = ?, email = ?, tanggal_lahir = ?, alamat = ?, asal_sekolah = ?, jurusan = ?, telepon = ?, id_magang = ?, foto = ?
                   WHERE id_pendataan = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sssssssssi", $nama, $email, $tanggal_lahir, $alamat, $asal_sekolah, $jurusan, $telepon, $id_magang, $foto, $id_pendataan);

    if ($stmt->execute()) {
        $success_message = "Data berhasil diperbarui!";
    } else {
        echo "<script>alert('Data gagal diperbarui! Silakan coba lagi.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pendaftar</title>
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
        .success {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #4CAF50;
            background-color: #dff0d8;
            color: #3c763d;
            border-radius: 5px;
            text-align: center;
        }
        .btn-back {
            display: block;
            width: fit-content;
            margin: 20px auto 0;
            padding: 10px 20px;
            background-color: #008CBA;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 4px;
        }
        .btn-back:hover {
            background-color: #007bb5;
        }
    </style>
</head>
<body>

    <!-- Tampilkan pesan sukses jika ada -->
    <?php if ($success_message): ?>
        <div class="success">
            <?php echo $success_message; ?>
        </div>
        <a href="admin.php" class="btn-back">Kembali ke Halaman Utama</a>
    <?php else: ?>
        <form method="POST" action="" enctype="multipart/form-data">
            <label for="nama">Nama</label>
            <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($data['nama']); ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($data['email']); ?>" required>

            <label for="tanggal_lahir">Tanggal Lahir</label>
            <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo htmlspecialchars($data['tanggal_lahir']); ?>" required>

            <label for="alamat">Alamat</label>
            <textarea id="alamat" name="alamat" rows="4" required><?php echo htmlspecialchars($data['alamat']); ?></textarea>

            <label for="asal_sekolah">Asal Sekolah</label>
            <input type="text" id="asal_sekolah" name="asal_sekolah" value="<?php echo htmlspecialchars($data['asal_sekolah']); ?>" required>

            <label for="jurusan">Jurusan</label>
            <input type="text" id="jurusan" name="jurusan" value="<?php echo htmlspecialchars($data['jurusan']); ?>" required>

            <label for="telepon">No. Telepon</label>
            <input type="text" id="telepon" name="telepon" value="<?php echo htmlspecialchars($data['telepon']); ?>" required>

            <label for="id_magang">Program Magang</label>
            <select id="id_magang" name="id_magang" required>
                <option value="">-- Pilih Program Magang --</option>
                <?php
                $programs = $conn->query("SELECT id_magang, magang FROM magang");
                while ($program = $programs->fetch_assoc()) {
                    $selected = $data['id_magang'] == $program['id_magang'] ? 'selected' : '';
                    echo "<option value='" . $program['id_magang'] . "' $selected>" . $program['magang'] . "</option>";
                }
                ?>
            </select>

            <label for="foto">Unggah Foto</label>
            <input type="file" id="foto" name="foto" accept="image/*">
            <small>Foto saat ini: <?php echo htmlspecialchars($data['foto']); ?></small>

            <button type="submit">Simpan Perubahan</button>
        </form>
    <?php endif; ?>
</body>
</html>

<?php
// Tutup koneksi
$conn->close();
?>
