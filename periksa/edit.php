<?php
include '../config/db.php';
include '../header.php';

$id = $_GET['id'] ?? header("Location: periksa.php");

// Ambil data pemeriksaan
$stmt = $conn->prepare("SELECT * FROM periksa WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

// Ambil data dropdown
$pasien = $conn->query("SELECT id, nama FROM pasien");
$dokter = $conn->query("SELECT id, nama FROM paramedik WHERE kategori = 'Dokter'");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $update = [
        'tanggal' => $_POST['tanggal'],
        'berat' => $_POST['berat'],
        'tinggi' => $_POST['tinggi'],
        'tensi' => $_POST['tensi'],
        'keterangan' => $_POST['keterangan'],
        'pasien_id' => $_POST['pasien_id'],
        'dokter_id' => $_POST['dokter_id'],
        'id' => $id
    ];

    try {
        $stmt = $conn->prepare("UPDATE periksa SET
            tanggal = ?,
            berat = ?,
            tinggi = ?,
            tensi = ?,
            keterangan = ?,
            pasien_id = ?,
            dokter_id = ?
            WHERE id = ?");

        $stmt->bind_param(
            "sddssiii",
            $update['tanggal'],
            $update['berat'],
            $update['tinggi'],
            $update['tensi'],
            $update['keterangan'],
            $update['pasien_id'],
            $update['dokter_id'],
            $update['id']
        );

        if ($stmt->execute()) {
            header("Location: periksa.php?success=edit");
            exit();
        }
    } catch (Exception $e) {
        $error = "Gagal update data: " . $e->getMessage();
    }
}
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
                    <div class="card">
                        <div class="card-header bg-warning">
                            <h3 class="card-title"><i class="fas fa-edit"></i> Edit Pemeriksaan</h3>
                        </div>
                        <form method="POST">
                            <div class="card-body">
                                <?php if (isset($error)): ?>
                                    <div class="alert alert-danger"><?= $error ?></div>
                                <?php endif; ?>

                                <!-- Form sama seperti tambah.php tapi dengan value -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tanggal</label>
                                            <input type="date" name="tanggal"
                                                value="<?= $data['tanggal'] ?>"
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Berat Badan (kg)</label>
                                            <input type="number" step="0.1" name="berat"
                                                value="<?= $data['berat'] ?>"
                                                class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tinggi Badan (cm)</label>
                                            <input type="number" step="0.1" name="tinggi"
                                                value="<?= $data['tinggi'] ?>"
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Tekanan Darah</label>
                                            <input type="text" name="tensi"
                                                value="<?= $data['tensi'] ?>"
                                                class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Pasien</label>
                                            <select name="pasien_id" class="form-control" required>
                                                <?php while ($row = $pasien->fetch_assoc()): ?>
                                                    <option value="<?= $row['id'] ?>"
                                                        <?= $row['id'] == $data['pasien_id'] ? 'selected' : '' ?>>
                                                        <?= $row['nama'] ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Dokter</label>
                                            <select name="dokter_id" class="form-control" required>
                                                <?php while ($row = $dokter->fetch_assoc()): ?>
                                                    <option value="<?= $row['id'] ?>"
                                                        <?= $row['id'] == $data['dokter_id'] ? 'selected' : '' ?>>
                                                        <?= $row['nama'] ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <textarea name="keterangan"
                                        class="form-control"
                                        rows="3"><?= $data['keterangan'] ?></textarea>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                                <a href="periksa.php" class="btn btn-default">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <?php include '../footer.php'; ?>
</body>

</html>