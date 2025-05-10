<?php
include '../config/db.php';
include '../header.php';

// Ambil data pasien dan dokter
$pasien = $conn->query("SELECT id, nama FROM pasien");
$dokter = $conn->query("SELECT id, nama FROM paramedik WHERE kategori = 'Dokter'");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'tanggal' => $_POST['tanggal'],
        'berat' => $_POST['berat'],
        'tinggi' => $_POST['tinggi'],
        'tensi' => $_POST['tensi'],
        'keterangan' => $_POST['keterangan'],
        'pasien_id' => $_POST['pasien_id'],
        'dokter_id' => $_POST['dokter_id']
    ];

    try {
        $stmt = $conn->prepare("INSERT INTO periksa 
            (tanggal, berat, tinggi, tensi, keterangan, pasien_id, dokter_id)
            VALUES (?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param(
            "sddssii",
            $data['tanggal'],
            $data['berat'],
            $data['tinggi'],
            $data['tensi'],
            $data['keterangan'],
            $data['pasien_id'],
            $data['dokter_id']
        );

        if ($stmt->execute()) {
            header("Location: periksa.php?success=tambah");
            exit();
        }
    } catch (Exception $e) {
        $error = "Gagal menambah data: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tambah Pasien Baru</title>
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <style>
        .card-header {
            background-color: #28a745;
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
                        <div class="card-header bg-primary">
                            <h3 class="card-title"><i class="fas fa-plus"></i> Tambah Pemeriksaan</h3>
                        </div>
                        <form method="POST">
                            <div class="card-body">
                                <?php if (isset($error)): ?>
                                    <div class="alert alert-danger"><?= $error ?></div>
                                <?php endif; ?>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tanggal</label>
                                            <input type="date" name="tanggal"
                                                value="<?= date('Y-m-d') ?>"
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Berat Badan (kg)</label>
                                            <input type="number" step="0.1" name="berat"
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Tinggi Badan (cm)</label>
                                            <input type="number" step="0.1" name="tinggi"
                                                class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tekanan Darah</label>
                                            <input type="text" name="tensi"
                                                placeholder="Contoh: 120/80"
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Pasien</label>
                                            <select name="pasien_id" class="form-control" required>
                                                <?php while ($row = $pasien->fetch_assoc()): ?>
                                                    <option value="<?= $row['id'] ?>">
                                                        <?= $row['nama'] ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Dokter</label>
                                            <select name="dokter_id" class="form-control" required>
                                                <?php while ($row = $dokter->fetch_assoc()): ?>
                                                    <option value="<?= $row['id'] ?>">
                                                        <?= $row['nama'] ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <textarea name="keterangan" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
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