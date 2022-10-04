<?php 
// buat koneksi web php ke database "stockbarang"
$conn = mysqli_connect("localhost", "root", "", "stockbarang");

// fungsi buat registrasi user baru
function register($data) {
    global $conn;
    
    $username = htmlspecialchars(strtolower(stripslashes(preg_replace("/\s+/", "", $data["username"]))));
    $password = htmlspecialchars(mysqli_real_escape_string($conn, $data["password"]));
    $password2 = htmlspecialchars(mysqli_real_escape_string($conn, $data["password2"]));

    $checkUsername = mysqli_query($conn, "SELECT * FROM user WHERE username='$username'");
    
    if(mysqli_num_rows($checkUsername) > 0) {
        echo "<script>
                alert('This username has been used');
                </script>";
        return false;
    }

    if($password !== $password2) {
        echo "<script>
                alert('Password doesn\'t match');
                </script>";
        return false;
    }

    $password = password_hash($password, PASSWORD_DEFAULT);
    mysqli_query($conn, "INSERT INTO user VALUES ('', '$username', '$password')");

    return mysqli_affected_rows($conn);
}

// fungsi buat nampilin data
function query($query) {
    global $conn;

    $result = mysqli_query($conn, $query);

    $rows = [];
    while($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

// fungsi buat nambah barang di halaman dashboard(stock)
function tambahBarang($data) {
    global $conn;

    $namaBarang = htmlspecialchars(trim($data["namabarang"])); 
    $kdBarang = htmlspecialchars(trim($data["kdbarang"])); 
    $kuantitas = htmlspecialchars(abs($data["kuantitas"]));
    $keterangan = htmlspecialchars(trim($data["keterangan"]));

    $query = "INSERT INTO stock (namabarang, kdbarang, kuantitas, keterangan) 
                VALUES ('$namaBarang', '$kdBarang', '$kuantitas', '$keterangan')";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

// fungsi buat nambah barang di halaman data barang masuk
function tambahBarangMasuk($data) {
    global $conn;

    $idBarang = $data["idbarang"]; 
    $kuantitas = htmlspecialchars(abs($data["kuantitas"]));
    $keterangan = htmlspecialchars(trim($data["keterangan"]));

    $result = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idBarang'");
    $row = mysqli_fetch_assoc($result);
    $kuantitasSaatIni = $row["kuantitas"]; 
    $kuantitasUpdate = $kuantitas + $kuantitasSaatIni;
    $query = "INSERT INTO masuk (idbarang, kuantitas, keterangan) 
                VALUES ('$idBarang', '$kuantitas', '$keterangan')";
    
    mysqli_query($conn, $query);
    mysqli_query($conn, "UPDATE stock SET kuantitas='$kuantitasUpdate' WHERE idbarang='$idBarang'");

    return mysqli_affected_rows($conn);
}

// fungsi buat nambah barang di halaman data barang keluar
function tambahBarangKeluar($data) {
    global $conn;

    $idBarang = $data["idbarang"]; 
    $kuantitas = htmlspecialchars(abs($data["kuantitas"]));
    $keterangan = htmlspecialchars(trim($data["keterangan"]));

    $result = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idBarang'");
    $row = mysqli_fetch_assoc($result);
    $kuantitasSaatIni = $row["kuantitas"]; 

    $kuantitasUpdate = ($kuantitas <= $kuantitasSaatIni) ? $kuantitasSaatIni-$kuantitas : 0;
    $query = "INSERT INTO keluar (idbarang, kuantitas, keterangan) 
                VALUES ('$idBarang', '$kuantitas', '$keterangan')";
    
    mysqli_query($conn, $query);
    mysqli_query($conn, "UPDATE stock SET kuantitas='$kuantitasUpdate' WHERE idbarang='$idBarang'");

    return mysqli_affected_rows($conn);
}

function editBarang($data) {
    global $conn;
    $idbarang = $data["idbarangedit"];
    $namabarang = htmlspecialchars(trim($data["namabarangedit"]));
    $kdbarang = htmlspecialchars(trim($data["kdbarangedit"]));
    $keterangan = htmlspecialchars(trim($data["keteranganedit"]));

    mysqli_query($conn, "UPDATE stock SET namabarang='$namabarang', kdbarang='$kdbarang', keterangan='$keterangan' WHERE idbarang='$idbarang'");

    return mysqli_affected_rows($conn);
}

function hapusBarang($data) {
    global $conn;
    $idbarang = $data["idbaranghapus"];

    mysqli_query($conn, "DELETE FROM stock WHERE idbarang='$idbarang'");
    
    return mysqli_affected_rows($conn);
}

function editBarangKeluar($data) {
    global $conn;
    $idkeluar = $data["idkeluaredit"];
    $idbarang = $data["idbarangkeluaredit"];
    $kuantitas = htmlspecialchars(abs($data["kuantitaskeluaredit"]));
    $keterangan = htmlspecialchars(trim($data["keterangankeluaredit"]));
    $kuantitasAsalKeluar = $data["kuantitasAsal"];

    $result = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idbarang'");
    $row = mysqli_fetch_assoc($result);
    $kuantitasSaatIni = $row["kuantitas"];
    $kuantitasSaatIniUpdate = 0;

    $selisih = abs($kuantitas - $kuantitasAsalKeluar);

    if($kuantitas >= $kuantitasAsalKeluar) {
        if($selisih <= $kuantitasSaatIni) {
            $kuantitasSaatIniUpdate = $kuantitasSaatIni - $selisih;
        } else {
            $kuantitasSaatIniUpdate = 0;
        }
    } else {
        $kuantitasSaatIniUpdate = $kuantitasSaatIni + $selisih;
    }

    mysqli_query($conn, "UPDATE keluar SET kuantitas='$kuantitas', keterangan='$keterangan' WHERE idkeluar='$idkeluar'");
    mysqli_query($conn, "UPDATE stock SET kuantitas='$kuantitasSaatIniUpdate' WHERE idbarang = '$idbarang'");

    return mysqli_affected_rows($conn);
}

function hapusBarangKeluar($data) {
    global $conn;
    $idkeluar = $data["idkeluarhapus"];
    $idbarang = $data["idbarangkeluarhapus"];
    $kuantitas = $data["kuantitaskeluarhapus"];

    $result = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idbarang'");
    $row = mysqli_fetch_assoc($result);
    $kuantitasSaatIni = $row["kuantitas"];
    $kuantitasUpdate = $kuantitasSaatIni + $kuantitas;

    mysqli_query($conn, "DELETE FROM keluar WHERE idkeluar='$idkeluar'");
    mysqli_query($conn, "UPDATE stock SET kuantitas='$kuantitasUpdate' WHERE idbarang='$idbarang'");

    return mysqli_affected_rows($conn);
}

function returBarangKeluar($data) {
    global $conn;

    $idBarang = $data["idbarangkeluarretur"];
    $kuantitas = $data["kuantitaskeluarretur"];
    $keterangan = $data["keterangankeluarretur"];

    $query = "INSERT INTO returjual (idbarang, kuantitas, keterangan) 
                VALUES ('$idBarang', '$kuantitas', '$keterangan')";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function editBarangMasuk($data) {
    global $conn;
    $idmasuk = $data["idmasukedit"];
    $idbarang = $data["idbarangmasukedit"];
    $kuantitas = htmlspecialchars(abs($data["kuantitasmasukedit"]));
    $keterangan = htmlspecialchars(trim($data["keteranganmasukedit"]));
    $kuantitasAsalMasuk = $data["kuantitasAsal"];

    $result = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idbarang'");
    $row = mysqli_fetch_assoc($result);
    $kuantitasSaatIni = $row["kuantitas"];
    $kuantitasSaatIniUpdate = 0;

    $selisih = abs($kuantitas - $kuantitasAsalMasuk);

    if($kuantitas >= $kuantitasAsalMasuk) {
        $kuantitasSaatIniUpdate = $kuantitasSaatIni + $selisih;
    } else {
        if($selisih <= $kuantitasSaatIni) {
            $kuantitasSaatIniUpdate = $kuantitasSaatIni - $selisih;
        } else {
            $kuantitasSaatIniUpdate = 0;
        }
    }

    mysqli_query($conn, "UPDATE masuk SET kuantitas='$kuantitas', keterangan='$keterangan' WHERE idmasuk='$idmasuk'");
    mysqli_query($conn, "UPDATE stock SET kuantitas='$kuantitasSaatIniUpdate' WHERE idbarang='$idbarang'");

    return mysqli_affected_rows($conn);
}

function hapusBarangMasuk($data) {
    global $conn;
    $idmasuk = $data["idmasukhapus"];
    $idbarang = $data["idbarangmasukhapus"];
    $kuantitas = $data["kuantitasmasukhapus"];

    $result = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idbarang'");
    $row = mysqli_fetch_assoc($result);
    $kuantitasSaatIni = $row["kuantitas"];
    $kuantitasUpdate = $kuantitasSaatIni - $kuantitas;

    mysqli_query($conn, "DELETE FROM masuk WHERE idmasuk='$idmasuk'");
    mysqli_query($conn, "UPDATE stock SET kuantitas='$kuantitasUpdate' WHERE idbarang='$idbarang'");

    return mysqli_affected_rows($conn);
}

function returBarangMasuk($data) {
    global $conn;

    $idBarang = $data["idbarangmasukretur"];
    $kuantitas = $data["kuantitasmasukretur"];
    $keterangan = $data["keteranganmasukretur"];

    $query = "INSERT INTO returbeli (idbarang, kuantitas, keterangan) 
                VALUES ('$idBarang', '$kuantitas', '$keterangan')";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function hapusReturBarangKeluar($data) {
    global $conn;
    $idreturjual = $data["idreturjualhapus"];

    mysqli_query($conn, "DELETE FROM returjual WHERE idreturjual = '$idreturjual'");

    return mysqli_affected_rows($conn);
}

function hapusReturBarangMasuk($data) {
    global $conn;
    $idreturbeli = $data["idreturbelihapus"];

    mysqli_query($conn, "DELETE FROM returbeli WHERE idreturbeli = '$idreturbeli'");

    return mysqli_affected_rows($conn);
}

?>