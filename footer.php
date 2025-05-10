  <!-- Main Footer -->
  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; <?php echo date('Y'); ?> <a href="#">Puskesmas Digital</a>.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- DataTables -->
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>

<script>
  $(function () {
    // Aktifkan DataTables
    $('#tabel-pasien').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true
    });
    
    // Pertahankan sidebar terbuka
    $('body').removeClass('sidebar-collapse');
    
    // Highlight menu aktif
    $('.nav-sidebar a').each(function() {
      if (this.href === window.location.href) {
        $(this).addClass('active');
        $(this).closest('.has-treeview').addClass('menu-open');
      }
    });
  });
</script>
</body>
</html>