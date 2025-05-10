<?php
include '../config/db.php';
include '../header.php'; // Sudah include sidebar.php

// Pindahkan logic POST ke atas
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $stmt = $conn->prepare("INSERT INTO paramedik 
            (nama, gender, tmp_lahir, tgl_lahir, kategori, telpon, alamat, unitkerja_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param(
            "sssssssi",
            $_POST['nama'],
            $_POST['gender'],
            $_POST['tmp_lahir'],
            $_POST['tgl_lahir'],
            $_POST['kategori'],
            $_POST['telpon'],
            $_POST['alamat'],
            $_POST['unitkerja_id']
        );

        if ($stmt->execute()) {
            header("Location: paramedik.php?success=create");
            exit();
        } else {
            throw new Exception("Gagal menyimpan data: " . $stmt->error);
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen Puskesmas</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <style>
        .content-wrapper {
            margin-left: 250px;
            padding: 20px;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Tambah Paramedik</h3>
                    </div>
                    <form method="POST">
                        <div class="card-body">
                            <?php if (isset($error)): ?>
                                <div class="alert alert-danger"><?= $error ?></div>
                            <?php endif; ?>
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" name="nama" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Gender</label>
                                <select name="gender" class="form-control" required>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tempat Lahir</label>
                                        <input type="text" name="tmp_lahir" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tanggal Lahir</label>
                                        <input type="date" name="tgl_lahir" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Kategori</label>
                                <select name="kategori" class="form-control" required>
                                    <option value="Dokter">Dokter</option>
                                    <option value="Perawat">Perawat</option>
                                    <option value="Bidan">Bidan</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Telepon</label>
                                <input type="text" name="telpon" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Alamat</label>
                                <textarea name="alamat" class="form-control" required></textarea>
                            </div>

                            <div class="form-group">
                                <label>Unit Kerja</label>
                                <select name="unitkerja_id" class="form-control" required>
                                    <?php
                                    $sql = "SELECT * FROM unit_kerja";
                                    $result = $conn->query($sql);
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='{$row['id']}'>{$row['nama_unit']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="paramedik.php" class="btn btn-default">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
    <?php include '../footer.php'; ?>
</body>

</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Gunakan prepared statement
    $stmt = $conn->prepare("INSERT INTO paramedik 
        (nama, gender, tmp_lahir, tgl_lahir, kategori, telpon, alamat, unitkerja_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "sssssssi",
        $_POST['nama'],
        $_POST['gender'],
        $_POST['tmp_lahir'],
        $_POST['tgl_lahir'],
        $_POST['kategori'],
        $_POST['telpon'],
        $_POST['alamat'],
        $_POST['unitkerja_id']
    );

    if ($stmt->execute()) {
        header("Location: paramedik.php");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

include '../footer.php';
?>