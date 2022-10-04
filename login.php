<?php 
session_start();
require 'functions.php';

if( isset($_COOKIE["id"]) && isset($_COOKIE["key"]) ) {
    $iduser = $_COOKIE["id"];
    $hashedUsername = $_COOKIE["key"];

    $result = mysqli_query($conn, "SELECT username FROM user WHERE iduser = '$iduser'");
    $row = mysqli_fetch_assoc($result);

    if( hash("sha256", $row["username"]) === $hashedUsername ) {
        $_SESSION["login"] = true;
        $_SESSION["username"] = $row["username"];
    }
}

// cek sesi login 
if( isset($_SESSION["login"]) ) {
    header('Location: index.php');
    exit;
}

// cek username dan password saat login
if( isset($_POST["login"]) ) {
    
    $username = htmlspecialchars(strtolower(stripslashes(preg_replace("/\s+/", "", $_POST["username"]))));
    $password = htmlspecialchars(mysqli_real_escape_string($conn, $_POST["password"]));

    $result = mysqli_query($conn, "SELECT * FROM user WHERE username='$username'");
    if(mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        if(password_verify($password, $row["password"])) {
            $_SESSION["login"] = true;
            $_SESSION["username"] = $username;
            
            if( isset($_POST["remember"]) ) {
                setcookie("id", $row["iduser"], time() + 86400);
                setcookie("key", hash("sha256", $row["username"]), time() + 86400);
            }

            header('Location: index.php');
            exit;
        }
    }

    echo "<script>
            alert('Your username or password doesn\'t exist');
            </script>";
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
        <title>Login</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="bg-light">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Login</h3></div>
                                    <div class="card-body">
                                        <form action="" method="POST">
                                            <div class="form-group">
                                                <label class="small mb-1" for="inputUsername">Username</label>
                                                <input name="username" class="form-control py-4" id="inputUsername" type="text" placeholder="Enter username"/>
                                            </div>
                                            <div class="form-group">
                                                <label class="small mb-1" for="inputPassword">Password</label>
                                                <input name="password" class="form-control py-4" id="inputPassword" type="password" placeholder="Enter password" required/>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" name="remember" type="checkbox" id="flexCheckDefault">
                                                <label class="form-check-label" for="flexCheckDefault">Remember me</label>
                                            </div>
                                            <div class="form-group d-flex align-items-center justify-content-between mt-5 mb-0">
                                                <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center">
                                        <div class="small"><a href="register.php" class="text-decoration-none">Need an account? Sign up!</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>
