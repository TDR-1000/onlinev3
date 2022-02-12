<?php
require('system/myfunc.php');
$token = $_GET['token'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SAINTEK e-Office</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="template/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="template/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="template/dist/css/adminlte.min.css">
    <style>
        .login-page {
            background-image: url('system/saintek-bg.jpg');
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="#" class="h1"><img src="system/saintek-logo.png" width="100%"></a>
            </div>
            <div class="card-body">
                <?php
                if (isset($_GET['pesan'])) {
                    if ($_GET['pesan'] == "antibot") {
                ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <strong>ERROR!</strong> perhitungan salah!!
                        </div>
                    <?php
                    } elseif ($_GET['pesan'] == "gagal") {
                    ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <strong>ERROR!</strong> User ID / Password salah
                        </div>
                <?php
                    }
                } ?>
                <p class="login-box-msg h3"><b>RESET</b> password</p>
                <form action="lupa-resetok.php" method="post">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Password baru" name="pass1" autocomplete="off" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-key"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Ulangi password baru" name="pass2" autocomplete="off" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-key"></span>
                            </div>
                        </div>
                    </div>

                    <?php
                    $angka1 = rand(1, 5);
                    $angka2 = rand(1, 5);
                    $kunci = $angka1 + $angka2;
                    ?>
                    <div class="input-group mb-3">
                        <input type="number" placeholder="<?= huruf($angka1); ?> ditambah <?= huruf($angka2); ?> ?" class="form-control" name="jawaban" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-question"></span>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <input type="hidden" name="kunci" value="<?= $kunci; ?>">
                    <input type="hidden" name="token" value="<?= $token; ?>">
                    <button type="submit" class="btn btn-warning btn-lg btn-block" onclick="return confirm('Reset Password ?')"><span class="fas fa-key"></span> RESET</button>
                </form>
            </div>
        </div>
    </div>

    <script src="template/plugins/jquery/jquery.min.js"></script>
    <script src="template/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="template/dist/js/adminlte.min.js"></script>
</body>

</html>