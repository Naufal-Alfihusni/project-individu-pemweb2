<?php include '../dbpuskesmas/config/db.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Manajemen Puskesmas</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="plugins/sweetalert2/sweetalert2.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- DataTables -->
  <link
    rel="stylesheet"
    href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css" />

  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

  <link
    rel="stylesheet"
    href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css" />

  <style>
    .dashboard-card {
      transition: transform 0.3s;
      border-left: 4px solid #007bff;
    }

    .dashboard-card:hover {
      transform: translateY(-5px);
    }

    .chart-container {
      background: #fff;
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="index.php" class="nav-link">Home</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="#" class="nav-link">Kontak</a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <span class="nav-link">
            <i class="far fa-user-circle mr-1"></i>
            <?php echo isset($_SESSION['nama']) ? $_SESSION['nama'] : 'Guest'; ?>
          </span>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../logout.php" title="Logout">
            <i class="fas fa-sign-out-alt"></i>
          </a>
        </li>
      </ul>
    </nav>

    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="index.php" class="brand-link">
        <img src="dist/img/png-clipart-puskesmas-logo-graphics-logo-rumah-sakit-angle-leaf.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Puskesmas</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="dist/img/pp.JPG" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <a href="#" class="d-block">Naufal Alfihusni</a>
          </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
          <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
              <button class="btn btn-sidebar">
                <i class="fas fa-search fa-fw"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
            <li class="nav-item">
              <a href="index.php" class="nav-link">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                  Dashboard
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="paramedik/paramedik.php" class="nav-link">
                <i class="nav-icon fas fa-user-md"></i>
                <p>Paramedik</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="pasien/pasien.php" class="nav-link">
                <i class="nav-icon fas fa-procedures"></i>
                <p>Pasien</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="periksa/periksa.php" class="nav-link">
                <i class="nav-icon fas fa-file-medical"></i>
                <p>Periksa</p>
              </a>
            </li>


          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <div class="content-wrapper">
      <section class="content">
        <div class="container-fluid">
          <div class="row mb-4">
            <div class="col-12">
              <h1 class="m-0 text-dark"><i class="fas fa-clinic-medical mr-2"></i>Dashboard Puskesmas</h1>
            </div>
          </div>

          <!-- Info Boxes -->
          <div class="row">
            <div class="col-lg-3 col-6">
              <div class="small-box bg-info dashboard-card">
                <div class="inner">
                  <?php
                  $sql = "SELECT COUNT(*) AS total FROM pasien";
                  $result = $conn->query($sql);
                  $data = $result->fetch_assoc();
                  ?>
                  <h3><?= number_format($data['total']) ?></h3>
                  <p>Pasien Terdaftar</p>
                </div>
                <div class="icon">
                  <i class="fas fa-procedures"></i>
                </div>
                <a href="../dbpuskesmas/pasien/pasien.php" class="small-box-footer">
                  More info <i class="fas fa-arrow-circle-right"></i>
                </a>
              </div>
            </div>

            <div class="col-lg-3 col-6">
              <div class="small-box bg-success dashboard-card">
                <div class="inner">
                  <?php
                  $sql = "SELECT COUNT(*) AS total FROM paramedik";
                  $result = $conn->query($sql);
                  $data = $result->fetch_assoc();
                  ?>
                  <h3><?= number_format($data['total']) ?></h3>
                  <p>Tenaga Medis</p>
                </div>
                <div class="icon">
                  <i class="fas fa-user-md"></i>
                </div>
                <a href="../dbpuskesmas/paramedik/paramedik.php" class="small-box-footer">
                  More info <i class="fas fa-arrow-circle-right"></i>
                </a>
              </div>
            </div>

            <div class="col-lg-3 col-6">
              <div class="small-box bg-warning dashboard-card">
                <div class="inner">
                  <?php
                  $sql = "SELECT COUNT(*) AS total FROM periksa";
                  $result = $conn->query($sql);
                  $data = $result->fetch_assoc();
                  ?>
                  <h3><?= number_format($data['total']) ?></h3>
                  <p>Total Pemeriksaan</p>
                </div>
                <div class="icon">
                  <i class="fas fa-file-medical"></i>
                </div>
                <a href="../dbpuskesmas/periksa/periksa.php" class="small-box-footer">
                  More info <i class="fas fa-arrow-circle-right"></i>
                </a>
              </div>
            </div>

            <div class="col-lg-3 col-6">
              <div class="small-box bg-danger dashboard-card">
                <div class="inner">
                  <h3>24/7</h3>
                  <p>Layanan Darurat</p>
                </div>
                <div class="icon">
                  <i class="fas fa-ambulance"></i>
                </div>
                <a href="#" class="small-box-footer">
                  Hubungi <i class="fas fa-phone"></i>
                </a>
              </div>
            </div>
          </div>

          <!-- Charts and Recent Activity -->
          <div class="row">
            <div class="col-md-8">
              <div class="chart-container">
                <h5><i class="fas fa-chart-line mr-2"></i>Statistik Pemeriksaan Bulanan</h5>
                <canvas id="visitsChart" style="height: 300px"></canvas>
              </div>
            </div>

            <div class="col-md-4">
              <div class="card">
                <div class="card-header bg-primary">
                  <h3 class="card-title"><i class="fas fa-calendar-check mr-2"></i>Pemeriksaan Terakhir</h3>
                </div>
                <div class="card-body p-0">
                  <ul class="products-list product-list-in-card pl-2 pr-2">
                    <?php
                    $sql = "SELECT p.tanggal, ps.nama, pm.nama AS dokter 
                            FROM periksa p
                            JOIN pasien ps ON p.pasien_id = ps.id
                            JOIN paramedik pm ON p.dokter_id = pm.id
                            ORDER BY p.tanggal DESC LIMIT 5";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()):
                    ?>
                      <li class="item">
                        <div class="product-info">
                          <a href="javascript:void(0)" class="product-title">
                            <?= htmlspecialchars($row['nama']) ?>
                            <span class="badge badge-info float-right"><?= date('d M Y', strtotime($row['tanggal'])) ?></span>
                          </a>
                          <span class="product-description">
                            Dokter: <?= htmlspecialchars($row['dokter']) ?>
                          </span>
                        </div>
                      </li>
                    <?php endwhile; ?>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <!-- Tambahkan Script Chart.js -->
    <script src="plugins/chart.js/Chart.min.js"></script>
    <script>
      // Ambil data real dari database
      <?php
      $currentYear = date('Y');
      $chartQuery = "SELECT 
                  MONTH(tanggal) AS bulan,
                  COUNT(*) AS total 
                FROM periksa 
                WHERE YEAR(tanggal) = '$currentYear'
                GROUP BY MONTH(tanggal)
                ORDER BY MONTH(tanggal)";

      $chartResult = $conn->query($chartQuery);

      $labels = [];
      $data = array_fill(0, 12, 0); // Inisialisasi 12 bulan dengan nilai 0

      if ($chartResult->num_rows > 0) {
        while ($row = $chartResult->fetch_assoc()) {
          $monthNum = $row['bulan'];
          $data[$monthNum - 1] = $row['total']; // Array dimulai dari 0 (Jan=0)
        }
      }

      // Konversi angka bulan ke nama bulan
      $monthNames = [
        'Jan',
        'Feb',
        'Mar',
        'Apr',
        'Mei',
        'Jun',
        'Jul',
        'Agu',
        'Sep',
        'Okt',
        'Nov',
        'Des'
      ];
      ?>

      const chartData = {
        labels: <?= json_encode($monthNames) ?>,
        datasets: [{
          label: 'Jumlah Pemeriksaan',
          data: <?= json_encode($data) ?>,
          borderColor: '#007bff',
          backgroundColor: 'rgba(0, 123, 255, 0.2)',
          tension: 0.4,
          fill: true
        }]
      };

      const ctx = document.getElementById('visitsChart').getContext('2d');
      new Chart(ctx, {
        type: 'line',
        data: chartData,
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: 'top',
            },
            tooltip: {
              callbacks: {
                title: (context) => 'Bulan ' + chartData.labels[context[0].dataIndex]
              }
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                stepSize: 1
              }
            }
          }
        }
      });
    </script>

  </div>
  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- overlayScrollbars -->
  <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>
</body>

</html>