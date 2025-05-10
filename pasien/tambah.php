<?php
include '../config/db.php';
include '../header.php';

// --- Bagian POST handler dipindahkan ke atas ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $stmt = $conn->prepare("INSERT INTO pasien 
            (kode, nama, tmp_lahir, tgl_lhir, gender, email, alamat, kelurahan_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param(
            "sssssssi",
            $_POST['kode'],
            $_POST['nama'],
            $_POST['tmp_lahir'],
            $_POST['tgl_lahir'],
            $_POST['gender'],
            $_POST['email'],
            $_POST['alamat'],
            $_POST['kelurahan_id']
        );

        if ($stmt->execute()) {
            header("Location: pasien.php?success=create");
            exit();
        } else {
            throw new Exception("Gagal menyimpan data: " . $stmt->error);
        }

        $stmt->close();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// --- Sisanya tetap di bawah ---
// Ambil data kelurahan untuk dropdown
$sql_kelurahan = "SELECT id, nama_kelurahan FROM kelurahan";
$kelurahan = $conn->query($sql_kelurahan);
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
                    <div class="row">
                        <div class="col-md-8 mx-auto">
                            <div class="card card-success">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-user-plus mr-2"></i>Tambah Pasien Baru</h3>
                                </div>

                                <form method="POST" action="">
                                    <div class="card-body">
                                        <?php if (isset($error)): ?>
                                            <div class="alert alert-danger"><?= $error ?></div>
                                        <?php endif; ?>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="required">Kode Pasien</label>
                                                    <input type="text" name="kode"
                                                        class="form-control"
                                                        placeholder="PS-001"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="required">Nama Lengkap</label>
                                                    <input type="text" name="nama"
                                                        class="form-control"
                                                        placeholder="John Doe"
                                                        required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="required">Tempat Lahir</label>
                                                    <input type="text" name="tmp_lahir"
                                                        class="form-control"
                                                        placeholder="Jakarta"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="required">Tanggal Lahir</label>
                                                    <input type="date" name="tgl_lahir"
                                                        class="form-control"
                                                        max="<?= date('Y-m-d') ?>"
                                                        required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="required">Jenis Kelamin</label>
                                                    <select name="gender" class="form-control" required>
                                                        <option value="">Pilih Gender</option>
                                                        <option value="L">Laki-laki</option>
                                                        <option value="P">Perempuan</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input type="email" name="email"
                                                        class="form-control"
                                                        placeholder="johndoe@example.com">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="required">Alamat</label>
                                            <textarea name="alamat"
                                                class="form-control"
                                                rows="3"
                                                placeholder="Jl. Contoh No. 123"
                                                required></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label class="required">Kelurahan</label>
                                            <select name="kelurahan_id" class="form-control" required>
                                                <option value="">Pilih Kelurahan</option>
                                                <?php while ($row = $kelurahan->fetch_assoc()): ?>
                                                    <option value="<?= $row['id'] ?>">
                                                        <?= htmlspecialchars($row['nama_kelurahan']) ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="card-footer text-right">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-save mr-1"></i> Simpan
                                        </button>
                                        <a href="pasien.php" class="btn btn-default">
                                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                                        </a>
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