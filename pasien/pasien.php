<?php
include '../config/db.php';
include '../header.php';

// Tangani feedback
$success = $_GET['success'] ?? null;
$error = $_GET['error'] ?? null;
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

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include '../sidebar.php'; ?>

        <div class="content-wrapper">
            <section class="content">
                <div class="container-fluid">
                    <!-- Notifikasi -->
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

                    <div class="row mb-4 align-items-center">
                        <div class="col-md-6">
                            <h1 class="m-0">Data Pasien</h1>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="tambah.php" class="btn btn-success btn-sm">
                                <i class="fas fa-plus mr-1"></i> Pasien Baru
                            </a>
                        </div>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="5%">ID</th>
                                            <th>Kode</th>
                                            <th>Nama</th>
                                            <th width="15%">TTL</th>
                                            <th width="10%">Gender</th>
                                            <th>Email</th>
                                            <th>Alamat</th>
                                            <th width="15%">Kelurahan</th>
                                            <th width="12%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT p.*, k.nama_kelurahan 
                                                FROM pasien p
                                                LEFT JOIN kelurahan k ON p.kelurahan_id = k.id
                                                ORDER BY p.id DESC";
                                        $result = $conn->query($sql);

                                        if ($result && $result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>
                                                    <td>{$row['id']}</td>
                                                    <td>{$row['kode']}</td>
                                                    <td>{$row['nama']}</td>
                                                    <td>{$row['tmp_lahir']}, " . date('d M Y', strtotime($row['tgl_lhir'])) . "</td>
                                                    <td>" . ($row['gender'] == 'L' ? 'Laki-laki' : 'Perempuan') . "</td>
                                                    <td>{$row['email']}</td>
                                                    <td>{$row['alamat']}</td>
                                                    <td>{$row['nama_kelurahan']}</td>
                                                    <td>
                                                        <a href='edit.php?id={$row['id']}' 
                                                           class='btn btn-warning btn-sm btn-action'
                                                           data-toggle='tooltip' title='Edit'>
                                                            <i class='fas fa-edit'></i>
                                                        </a>
                                                        <button onclick='confirmDelete({$row['id']})' 
                                                                class='btn btn-danger btn-sm btn-action'
                                                                data-toggle='tooltip' title='Hapus'>
                                                            <i class='fas fa-trash'></i>
                                                        </button>
                                                    </td>
                                                </tr>";
                                            }
                                        } else {
                                            echo "<tr>
                                                <td colspan='9' class='text-center py-4'>
                                                    <i class='fas fa-exclamation-circle mr-2'></i>Tidak ada data
                                                </td>
                                            </tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <?php include '../footer.php'; ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Data Pasien?',
                html: `<p>Anda yakin ingin menghapus data pasien ini?</p>
                   <small class='text-muted'>Data yang dihapus tidak dapat dikembalikan</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus!',
                cancelButtonText: '<i class="fas fa-times"></i> Batal',
                showClass: {
                    popup: 'animate__animated animate__fadeIn'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOut'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`delete.php?id=${id}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: data.message,
                                    timer: 1500,
                                    showConfirmButton: false,
                                    willClose: () => location.reload()
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: data.message
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Kesalahan Sistem',
                                text: 'Terjadi kesalahan saat menghubungi server'
                            });
                        });
                }
            });
        }

        // Aktifkan tooltip
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
</body>

</html>