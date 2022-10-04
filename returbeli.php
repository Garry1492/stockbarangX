<?php 
session_start();

require 'functions.php';

// cek sesi login
if( !isset($_SESSION["login"]) ) {
    header('Location: login.php');
    exit;
}

// ngambil data dari database buat ditampilin
$query = "SELECT stock.idbarang, stock.namabarang, stock.kdbarang, returbeli.idreturbeli, returbeli.tanggal, returbeli.kuantitas, returbeli.keterangan
            FROM stock INNER JOIN returbeli ON stock.idbarang = returbeli.idbarang;";
$rows = query($query);

if ( isset($_POST["hapusDataReturbeli"]) ) {

    if(hapusReturBarangMasuk($_POST) > 0) {
        echo "<script>
                alert('Delete data successful!');
                document.location.href = 'returbeli.php';
                </script>";
    } else {
        echo "<script>
                alert('Delete data failed!');
                </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Data Retur Pembelian</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark bg-gradient">
            <button class="btn btn-link btn-sm order-1 order-lg-0 ml-3 shadow-none" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
            <a class="navbar-brand" href="index.php">Dior Electronic</a>
        </nav>

        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark bg-dark bg-gradient" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Menu Utama</div>
                            <a class="nav-link" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            <div class="sb-sidenav-menu-heading">Transaksi</div>
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Penjualan
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="keluar.php">Data Penjualan</a>
                                    <a class="nav-link" href="returjual.php">Retur Penjualan</a>
                                </nav>
                            </div>
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                                <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                                Pembelian
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                    <a class="nav-link" href="masuk.php" >Data Pembelian</a> 
                                    <a class="nav-link" href="returbeli.php">Retur Pembelian</a> 
                                </nav>
                            </div>
                            <div class="sb-sidenav-menu-heading">Akun</div>
                            <a class="nav-link" href="register.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                                Register
                            </a>
                            <a class="nav-link" href="logout.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                Log out
                            </a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        <?= $_SESSION["username"]; ?>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h1 class="mt-4 mb-4">Data Retur Pembelian</h1>

                        <div class="card mb-4">
                            <div class="card-header">
                                <a href="exportreturbeli.php" class="btn btn-info">Export Data</a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Nama Barang</th>
                                                <th>Kode Barang</th>
                                                <th>Tanggal</th>
                                                <th>Kuantitas</th>
                                                <th>Keterangan</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <!-- perulangan buat nampilin data dari database -->
                                            <?php $i = 1; ?>
                                            <?php foreach($rows as $row): 
                                                $idreturbeli = $row["idreturbeli"];    
                                            ?>
                                                <tr>
                                                    <td><?=$i?></td>
                                                    <td><?=$row["namabarang"]?></td>
                                                    <td><?=$row["kdbarang"]?></td>
                                                    <td><?=$row["tanggal"]?></td>
                                                    <td><?=$row["kuantitas"]?></td>
                                                    <td><?=$row["keterangan"]?></td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#hapusBarangReturbeli<?=$idreturbeli?>">Hapus</button>
                                                    </td>
                                                </tr>

                                                <div class="modal fade" id="hapusBarangReturbeli<?=$idreturbeli?>">
                                                    <div class="modal-dialog">
                                                    <div class="modal-content">

                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                            <h4 class="modal-title text-center">Apakah Anda yakin ingin menghapus data retur pembelian <?=$row["namabarang"]?> sebanyak <?=$row["kuantitas"]?> ini?</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        
                                                        <!-- Modal body -->
                                                        <div class="modal-body">
                                                            <form action="" method="POST">
                                                                <input type="hidden" name="idreturbelihapus" value="<?=$idreturbeli?>">
                                                                <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                                                                    <button type="submit" name="hapusDataReturbeli" class="btn btn-danger btn-block">Hapus</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div> 

                                            <?php $i++; ?>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
                <!-- <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2020</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer> -->
            </div>

        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/datatables-demo.js"></script>
    </body>
</html>
