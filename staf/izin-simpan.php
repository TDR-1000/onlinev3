<?php
session_start();
require('../system/dbconn.php');
require('../system/myfunc.php');
require('../system/phpmailer/sendmail.php');

date_default_timezone_set("Asia/Jakarta");
$tglsurat = date('Y-m-d H:i:s');

$nama = $_SESSION['nama'];
$nip = $_SESSION['nip'];
$pangkat = $_POST['pangkat'];
$golongan = $_POST['golongan'];
$pangkatgolongan = $pangkat . ' - ' . $golongan;
$jabatan = $_SESSION['jabatan'];
$prodi = $_SESSION['prodi'];
$jenisizin = mysqli_real_escape_string($dbsurat, $_POST['jenisizin']);
$alasan = mysqli_real_escape_string($dbsurat, $_POST['alasan']);
$sisacuti = mysqli_real_escape_string($dbsurat, $_POST['sisacuti']);
$tgl1 = $_POST['tgl1'];
$tgl2 = $_POST['tgl2'];
$token = md5(uniqid());
$cuti = jmlcuti($tgl1, $tgl2, $dbsurat);

if ($cuti > $sisacuti) {
    header("location:izin-isi.php?pesan=over");
} else {
    //kaprodi keatas verifikasi wd2
    if ($jabatan == 'kaprodi' or $jabatan == 'dekan' or $jabatan == 'wadek1' or $jabatan == 'wadek3' or $jabatan == 'kabag-tu') {
        //cari nip kaprodi
        $jabatanwd = 'wadek2';
        $stmt = $dbsurat->prepare("SELECT * FROM pejabat WHERE kdjabatan=?");
        $stmt->bind_param("s", $jabatanwd);
        $stmt->execute();
        $result = $stmt->get_result();
        $dhasil = $result->fetch_assoc();
        $nipkaprodi = $dhasil['nip'];
        $namakaprodi = $dhasil['nama'];
        //cari nip wd-2
        $nipwd = $dhasil['nip'];
    } elseif ($jabatan == 'wadek2') {
        //cari nip kaprodi
        $jabatanwd = 'dekan';
        $stmt = $dbsurat->prepare("SELECT * FROM pejabat WHERE kdjabatan=?");
        $stmt->bind_param("s", $jabatanwd);
        $stmt->execute();
        $result = $stmt->get_result();
        $dhasil = $result->fetch_assoc();
        $nipkaprodi = $dhasil['nip'];
        $namakaprodi = $dhasil['nama'];
        //cari nip wd-2
        $nipwd = $dhasil['nip'];
    } else {
        //cari nip kaprodi
        if ($prodi == 'SAINTEK') {
            $kdjabatan = 'kabag-tu';
        } else {
            $kdjabatan = 'kaprodi';
        }
        $stmt = $dbsurat->prepare("SELECT * FROM pejabat WHERE prodi=? AND kdjabatan=?");
        $stmt->bind_param("ss", $prodi, $kdjabatan);
        $stmt->execute();
        $result = $stmt->get_result();
        $dhasil = $result->fetch_assoc();
        $nipkaprodi = $dhasil['nip'];
        $namakaprodi = $dhasil['nama'];

        //cari nip wd-2
        $jabatanwd = 'kabag-tu';
        $stmt = $dbsurat->prepare("SELECT * FROM pejabat WHERE kdjabatan=?");
        $stmt->bind_param("s", $jabatanwd);
        $stmt->execute();
        $result = $stmt->get_result();
        $dhasil = $result->fetch_assoc();
        $nipwd = $dhasil['nip'];
    }

    if ($jabatan == 'dosen') {
        $jabatan = 'Dosen';
    } elseif ($jabatan == 'kaprodi') {
        $jabatan = 'Ketua Program Studi';
    } elseif ($jabatan == 'wadek1') {
        $jabatan = 'Wakil Dekan bidang Akademik';
    } elseif ($jabatan == 'wadek2') {
        $jabatan = 'Wakil Dekan bidang AUPK';
    } elseif ($jabatan == 'wadek3') {
        $jabatan = 'Wakil Dekan bidang Kemahasiswaan';
    } elseif ($jabatan == 'tendik') {
        $jabatan = 'Tenaga Kependidikan';
    } elseif ($jabatan == 'kabag') {
        $jabatan = 'Kepala Bagian AUPK';
    } elseif ($jabatan == 'kasubag') {
        $jabatan = 'Kepala Sub Bagian';
    }

    $sql = mysqli_query($dbsurat, "INSERT INTO izin (prodi, tglsurat, nama, nip,pangkat,jabatan, tglizin1, tglizin2,jmlizin,jenisizin,alasan,validator1,validator2,token) 
			                    VALUES ('$prodi','$tglsurat','$nama','$nip','$pangkatgolongan','$jabatan','$tgl1','$tgl2','$cuti','$jenisizin','$alasan','$nipkaprodi','$nipwd','$token')");

    //kirim email;
    //cari email kaprodi berdasarkan NIP
    $sql2 = mysqli_query($dbsurat, "SELECT * FROM pengguna WHERE nip='$nipkaprodi'");
    $dsql2 = mysqli_fetch_array($sql2);
    $emailkaprodi = $dsql2['email'];

    $subject = "Pengajuan Izin";
    $pesan = "Yth. " . $namakaprodi . "<br/>
        <br/>
		Assalamualaikum wr. wb.
        <br />
		<br />
		Dengan hormat,
		<br />
        Terdapat pengajuan <b>Surat Izin</b> atas nama " . $nama . " di sistem SAINTEK e-Office.<br/>
        Silahkan klik tombol dibawah ini untuk melakukan verifikasi surat di website SAINTEK e-Office<br/>
        <br/>
        <a href='https://saintek.uin-malang.ac.id/online/' style=' background-color: #0045CE;border: none;color: white;padding: 8px 16px;text-align: center;text-decoration: none;display: inline-block;font-size: 16px;'>SAINTEK e-Office</a><br/>
        <br/>
        atau klik URL berikut ini <a href='https://saintek.uin-malang.ac.id/online/'>https://saintek.uin-malang.ac.id/online/</a> apabila tombol diatas tidak berfungsi.<br/>
        <br/>
        Wassalamualaikum wr. wb.
		<br/>
        <br/>
        <b>SAINTEK e-Office</b>";
    sendmail($emailkaprodi, $namakaprodi, $subject, $pesan);
    header("location:index.php");
}
