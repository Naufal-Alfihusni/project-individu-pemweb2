<?php
include '../config/db.php';
include '../header.php';

// Validasi ID dengan filter_var
$id = filter_var($_GET['id'] ?? 0, FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: paramedik.php?error=invalid_id");
    exit;
}

// Ambil data paramedik dengan prepared statement
$stmt = $conn->prepare("SELECT * FROM paramedik WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$paramedik = $result->fetch_assoc();

if (!$paramedik) {
    header("Location: paramedik.php?error=not_found");
    exit;
}

// Proses update data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $stmt = $conn->prepare("UPDATE paramedik SET 
            nama = ?, 
            gender = ?, 
            tmp_lahir = ?, 
            tgl_lahir = ?, 
            kategori = ?, 
            telpon = ?, 
            alamat = ?, 
            unitkerja_id = ? 
            WHERE id = ?");

        // Perbaikan tipe data parameter
        $stmt->bind_param(
            "sssssssii", // 8 string dan 2 integer (termasuk id)
            $_POST['nama'],
            $_POST['gender'],
            $_POST['tmp_lahir'],
            $_POST['tgl_lahir'],
            $_POST['kategori'],
            $_POST['telpon'],
            $_POST['alamat'],
            $_POST['unitkerja_id'],
            $id
        );

        if ($stmt->execute()) {
            header("Location: paramedik.php?success=update");
            exit;
        } else {
            throw new Exception("Gagal memperbarui data: " . $stmt->error);
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Paramedik</title>
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include '../sidebar.php'; ?>

        <div class="content-wrapper">
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-8 mx-auto">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Edit Data Paramedik</h3>
                                </div>

                                <form method="POST">
                                    <div class="card-body">
                                        <?php if (isset($error)): ?>
                                            <div class="alert alert-danger"><?= $error ?></div>
                                        <?php endif; ?>

                                        <div class="form-group">
                                            <label>Nama</label>
                                            <input type="text" name="nama"
                                                class="form-control"
                                                value="<?= htmlspecialchars($paramedik['nama']) ?>"
                                                required>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Gender</label>
                                                    <select name="gender" class="form-control" required>
                                                        <option value="L" <?= $paramedik['gender'] == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                                                        <option value="P" <?= $paramedik['gender'] == 'P' ? 'selected' : '' ?>>Perempuan</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Kategori</label>
                                                    <select name="kategori" class="form-control" required>
                                                        <option value="Dokter" <?= $paramedik['kategori'] == 'Dokter' ? 'selected' : '' ?>>Dokter</option>
                                                        <option value="Perawat" <?= $paramedik['kategori'] == 'Perawat' ? 'selected' : '' ?>>Perawat</option>
                                                        <option value="Bidan" <?= $paramedik['kategori'] == 'Bidan' ? 'selected' : '' ?>>Bidan</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Tempat Lahir</label>
                                                    <input type="text" name="tmp_lahir"
                                                        class="form-control"
                                                        value="<?= htmlspecialchars($paramedik['tmp_lahir']) ?>"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Tanggal Lahir</label>
                                                    <input type="date" name="tgl_lahir"
                                                        class="form-control"
                                                        value="<?= htmlspecialchars($paramedik['tgl_lahir']) ?>"
                                                        required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Telepon</label>
                                            <input type="text" name="telpon"
                                                class="form-control"
                                                value="<?= htmlspecialchars($paramedik['telpon']) ?>"
                                                required>
                                        </div>

                                        <div class="form-group">
                                            <label>Alamat</label>
                                            <textarea name="alamat"
                                                class="form-control"
                                                rows="3"
                                                required><?= htmlspecialchars($paramedik['alamat']) ?></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Unit Kerja</label>
                                            <select name="unitkerja_id" class="form-control" required>
                                                <?php
                                                $sql = "SELECT * FROM unit_kerja";
                                                $result = $conn->query($sql);
                                                while ($unit = $result->fetch_assoc()):
                                                ?>
                                                    <option value="<?= $unit['id'] ?>"
                                                        <?= $unit['id'] == $paramedik['unitkerja_id'] ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($unit['nama_unit']) ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save mr-1"></i> Simpan Perubahan
                                        </button>
                                        <a href="paramedik.php" class="btn btn-default">Kembali</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <?php include '../footer.php'; ?>
</body>

</html>