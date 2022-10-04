<?php
require 'functions.php';

$rows = query("SELECT * FROM stock");
?>
<html>
<head>
    <title>Stok Barang</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
</head>

<body>
<div class="container">
    <h2 class="mt-2 mb-3">Stok Barang</h2>
    <div class="data-tables datatable-dark">
    <table class="table table-bordered" id="exportstok" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Barang</th>
                <th>Kode Barang</th>
                <th>Kuantitas</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; 
            ?>
            <?php foreach($rows as $row): ?>
            <tr>
                <td><?=$i?></td>
                <td><?=$row["namabarang"]?></td>
                <td><?=$row["kdbarang"]?></td>
                <td><?=$row["kuantitas"]?></td>
                <td><?=$row["keterangan"]?></td>
            </tr>    
            <?php $i++; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="index.php" class="text-decoration-none">Back to Dashboard</a>
    </div>
</div>
	
<script>
$(document).ready(function() {
    $('#exportstok').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'csv','excel', 'pdf', 'print'
        ]
    } );
} );

</script>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>

	

</body>

</html>