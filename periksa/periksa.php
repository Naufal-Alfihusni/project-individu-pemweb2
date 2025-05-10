<?php
include '../config/db.php';
include '../header.php';

$sql = "SELECT p.*, 
        ps.nama AS nama_pasien, 
        pm.nama AS nama_dokter 
        FROM periksa p
        LEFT JOIN pasien ps ON p.pasien_id = ps.id
        LEFT JOIN paramedik pm ON p.dokter_id = pm.id
        ORDER BY p.tanggal DESC";
$result = $conn->query($sql);
?>
<?php
// Handle notifikasi
$success = $_GET['success'] ?? null;
$error = $_GET['error'] ?? null;

// Pesan untuk SweetAlert
$alertScript = '';
if ($success === 'delete') {
    $alertScript = "
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data pemeriksaan berhasil dihapus',
                timer: 1500,
                showConfirmButton: false,
                willClose: () => location.reload()
            });
        </script>
    ";
} elseif ($error) {
    $errorMessage = match ($error) {
        'invalid_id' => 'ID tidak valid',
        'not_found' => 'Data tidak ditemukan',
        'delete_failed' => 'Gagal menghapus data',
        default => 'Terjadi kesalahan sistem'
    };
    $alertScript = "
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{$errorMessage}',
                willClose: () => location.href = 'periksa.php'
            });
        </script>
    ";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen Pasien - Puskesmas</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .content-wrapper {
            margin-left: 250px;
            padding: 20px;
        }

        .table thead {
            background-color: #28a745;
            color: white;
        }

        .btn-action {
            margin: 0 3px;
            min-width: 40px;
        }
    </style>
</head>
<?= $alertScript ?>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include '../sidebar.php'; ?>


        <div class="content-wrapper">
            <section class="content">
                <div class="container-fluid">
                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
                            <?= $success === 'delete' ? 'Data berhasil dihapus' : 'Operasi berhasil' ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-ban"></i> Error!</h5>
                            <?= $error === 'delete' ? 'Gagal menghapus data' : 'Terjadi kesalahan' ?>
                        </div>
                    <?php endif; ?>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h1>Data Pemeriksaan</h1>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="tambah.php" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Pemeriksaan Baru
                            </a>
                        </div>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Pasien</th>
                                            <th>Dokter</th>
                                            <th>Berat (kg)</th>
                                            <th>Tinggi (cm)</th>
                                            <th>Tensi</th>
                                            <th>Keterangan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                                                <td><?= $row['nama_pasien'] ?></td>
                                                <td><?= $row['nama_dokter'] ?></td>
                                                <td><?= $row['berat'] ?></td>
                                                <td><?= $row['tinggi'] ?></td>
                                                <td><?= $row['tensi'] ?></td>
                                                <td><?= $row['keterangan'] ?></td>
                                                <td>
                                                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    <button onclick='confirmDelete(<?= $row['id'] ?>)'
                                                        class='btn btn-danger btn-sm btn-action'
                                                        data-toggle='tooltip' title='Hapus'>
                                                        <i class='fas fa-trash'></i>
                                                    </button>

                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</body>

</html>

<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Data?',
            html: `<p>Anda yakin ingin menghapus data ini?</p>
                       <small class="text-muted">Data akan dihapus permanen</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            allowOutsideClick: false,
            showClass: {
                popup: 'animate__animated animate__fadeIn'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOut'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `delete.php?id=${id}`;
            }
        });
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php include '../footer.php'; ?>