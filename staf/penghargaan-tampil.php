<?php
session_start();
$user = $_SESSION['user'];
$nip = $_SESSION['nip'];
$nama = $_SESSION['nama'];
$prodi = $_SESSION['prodi'];
$hakakses = $_SESSION['hakakses'];
$jabatan = $_SESSION['jabatan'];
if ($jabatan != "kasubag-akademik") {
  header("location:../deauth.php");
}
require('../system/dbconn.php');
require('../system/myfunc.php');
$no = 1;
$tahun = date('Y');
$tahunlalu = date('Y', strtotime('-1 year'));
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SAINTEK e-Office</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="../template/plugins/fontawesome6/css/all.css">
  <link rel="stylesheet" href="../template/plugins/fontawesome6/css/all.css">
  <link rel="stylesheet" href="../template/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../template/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../template/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="../template/dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini text-sm">
  <!-- Site wrapper -->
  <div class="wrapper">
    <!-- Navbar -->
    <?php
    require('navbar.php');
    ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php
    require('sidebar.php');
    ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <a href="index.php" class="btn btn-primary"><i class="nav-icon fas fa-arrow-left"></i> KEMBALI</a>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <!-- Pengajuan Surat Mahasiswa -->
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">Pengajuan Penghargaan Mahasiswa</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped text-sm">
              <thead>
                <tr>
                  <th width="5%">No.</th>
                  <th>Nama</th>
                  <th>NIM</th>
                  <th>No. HP</th>
                  <th>E-Mail</th>
                  <th>Prodi</th>
                  <th>Kegiatan</th>
                  <th>Tingkat</th>
                  <th>Peringkat</th>
                  <th>Nama Kegiatan</th>
                  <th>Bukti</th>
                </tr>
              </thead>
              <tbody>
                <?php $no = 1; ?>

                <!-- PKL Koordinator-->
                <?php
                $query = mysqli_query($dbsurat, "SELECT * FROM penghargaan WHERE statussurat=1 ORDER BY tanggal DESC");
                $jmldata = mysqli_num_rows($query);
                while ($data = mysqli_fetch_array($query)) {
                  $nodata = $data['no'];
                  $nim = $data['nim'];
                  $nama = $data['nama'];
                  $prodi = $data['prodi'];
                  $kegiatan = $data['kegiatan'];
                  $tingkat = $data['tingkat'];
                  $peringkat = $data['peringkat'];
                  $bukti = $data['bukti'];
                  $namakegiatan = $data['namakegiatan'];
                  $token = $data['token'];
                  $qnohp = mysqli_query($dbsurat, "SELECT * FROM pengguna WHERE nip='$nim'");
                  $dnohp = mysqli_fetch_array($qnohp);
                  $nohp = $dnohp['nohp'];
                  $email = $dnohp['email'];
                ?>
                  <tr>
                    <td><?= $no; ?></td>
                    <td><?= $nama; ?></td>
                    <td><?= $nim; ?></td>
                    <td><?= $nohp; ?></td>
                    <td><?= $email; ?></td>
                    <td><?= $prodi; ?></td>
                    <td><?= $kegiatan; ?></td>
                    <td><?= $tingkat; ?></td>
                    <td><?= $peringkat; ?></td>
                    <td><?= $namakegiatan; ?></td>
                    <td><a href="<?= $bukti; ?>" target="_blank">Lihat</a></td>
                  </tr>
                <?php
                  $no++;
                }
                ?>
                <!-- /. PKL koordinator-->

              </tbody>
            </table>
          </div>
        </div>
      </section>
    </div>

    <?php
    require('footer.php');
    ?>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
    </aside>
    <!-- /.control-sidebar -->
  </div>

  <script src="../template/plugins/jquery/jquery.min.js"></script>
  <script src="../template/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../template/plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="../template/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="../template/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
  <script src="../template/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
  <script src="../template/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
  <script src="../template/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
  <script src="../template/plugins/jszip/jszip.min.js"></script>
  <script src="../template/plugins/pdfmake/pdfmake.min.js"></script>
  <script src="../template/plugins/pdfmake/vfs_fonts.js"></script>
  <script src="../template/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
  <script src="../template/plugins/datatables-buttons/js/buttons.print.min.js"></script>
  <script src="../template/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
  <script src="../template/dist/js/adminlte.min.js"></script>

  <script>
    $(function() {
      $("#example1").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "buttons": ["excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
      $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
      });
    });
  </script>
</body>

</html>