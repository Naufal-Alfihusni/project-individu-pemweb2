<?php
include '../config/db.php';
include '../header.php';

// ======== HANDLE FORM SUBMIT (UPDATE) ========
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $tmp_lahir = $_POST['tmp_lahir'];
    $tgl_lhir = $_POST['tgl_lhir'];
    $gender = $_POST['gender'];
    $email = $_POST['email'] ?? null;
    $alamat = $_POST['alamat'];
    $kelurahan_id = $_POST['kelurahan_id'];

    try {
        $stmt = $conn->prepare("UPDATE pasien SET 
            kode = ?,
            nama = ?,
            tmp_lahir = ?,
            tgl_lhir = ?,
            gender = ?,
            email = ?,
            alamat = ?,
            kelurahan_id = ?
            WHERE id = ?");

        $stmt->bind_param(
            "ssssssssi",
            $kode,
            $nama,
            $tmp_lahir,
            $tgl_lhir,
            $gender,
            $email,
            $alamat,
            $kelurahan_id,
            $id
        );

        if ($stmt->execute()) {
            header("Location: pasien.php?success=update");
            exit();
        } else {
            throw new Exception("Gagal memperbarui data: " . $stmt->error);
        }

        $stmt->close();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// ======== TAMPILKAN FORM EDIT ========
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: pasien.php?error=invalid_id");
    exit();
}

// Ambil data pasien
$stmt_pasien = $conn->prepare("SELECT * FROM pasien WHERE id = ?");
$stmt_pasien->bind_param("i", $id);
$stmt_pasien->execute();
$result = $stmt_pasien->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    header("Location: pasien.php?error=data_not_found");
    exit();
}

// Ambil data kelurahan
$sql_kelurahan = "SELECT id, nama_kelurahan FROM kelurahan";
$kelurahan = $conn->query($sql_kelurahan);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Pasien - Puskesmas</title>
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <style>
        .card-header {
            background-color: #ffc107;
            color: white;
        }

        .required:after {
            content: " *";
            color: red;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include '../sidebar.php'; ?>

        <div class="content-wrapper">
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-8 mx-auto">
                            <div class="card card-warning">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-edit mr-2"></i>Edit Data Pasien</h3>
                                </div>

                                <form method="POST">
                                    <input type="hidden" name="id" value="<?= $data['id'] ?>">
                                    <div class="card-body">
                                        <?php if (isset($error)): ?>
                                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                                        <?php endif; ?>

                                        <!-- Form fields sama seperti versi sebelumnya -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="required">Kode Pasien</label>
                                                    <input type="text" name="kode"
                                                        class="form-control"
                                                        value="<?= htmlspecialchars($data['kode']) ?>"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="required">Nama Pasien</label>
                                                    <input type="text" name="nama"
                                                        class="form-control"
                                                        value="<?= htmlspecialchars($data['nama']) ?>"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="required">Tempat Lahir</label>
                                                    <input type="text" name="tmp_lahir"
                                                        class="form-control"
                                                        value="<?= htmlspecialchars($data['tmp_lahir']) ?>"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="required">Tanggal Lahir</label>
                                                    <input type="text" name="tgl_lhir"
                                                        class="form-control"
                                                        value="<?= htmlspecialchars($data['tgl_lhir']) ?>"
                                                        required>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Gender</label>
                                                    <select name="gender" class="form-control" required>
                                                        <option value="L" <?= $data['gender'] == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                                                        <option value="P" <?= $data['gender'] == 'P' ? 'selected' : '' ?>>Perempuan</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="required">Email</label>
                                                    <input type="email" name="email"
                                                        class="form-control"
                                                        value="<?= htmlspecialchars($data['email']) ?>"
                                                        required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="required">Alamat</label>
                                                <textarea name="alamat"
                                                    class="form-control"
                                                    rows="3"
                                                    required><?= htmlspecialchars($data['alamat']) ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="required">Kelurahan</label>
                                            <select name="kelurahan_id" class="form-control" required>
                                                <option value="">Pilih Kelurahan</option>
                                                <?php while ($row = $kelurahan->fetch_assoc()): ?>
                                                    <option value="<?= $row['id'] ?>"
                                                        <?= $data['kelurahan_id'] == $row['id'] ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($row['nama_kelurahan']) ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>


                                        <div class="card-footer text-right">
                                            <button type="submit" class="btn btn-warning">
                                                <i class="fas fa-save mr-1"></i> Simpan Perubahan
                                            </button>
                                            <a href="pasien.php" class="btn btn-default">
                                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                                            </a>
                                        </div>
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
<label>Alamat: <input type="text" name="alamat" value="<?= $data['alamat'] ?>" required></label><br>
<label>Kelurahan ID: <input type="number" name="kelurahan_id" value="<?= $data['kelurahan_id'] ?>" required></label><br>