<?php
session_start();
require_once('../system/dbconn.php');

$token = mysqli_real_escape_string($dbsurat, $_POST['token']);
date_default_timezone_set("Asia/Jakarta");
$tgl = date('Y-m-d H:i:s');
$nip = mysqli_real_escape_string($dbsurat, $_SESSION['nip']);
$keterangan = mysqli_real_escape_string($dbsurat, $_POST['keterangan']);

//update status validasi kaprodi
$sql = mysqli_query($dbsurat, "UPDATE pkl
					SET tglvalidasi2 = '$tgl', 
					validasi2 = '2',
                    keterangan='$keterangan',
                    statussurat=2
					WHERE token = '$token' AND validator2='$nip'");

header("location:index.php");
