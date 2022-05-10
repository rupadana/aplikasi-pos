<?php

include __DIR__ . "/../helpers/loaders.php";

$action = isset($_GET['action']) ? $_GET['action'] : "";

if (Authentication::isLoggedIn()) {

    if ($action == "edit-member") {
        $id = htmlentities($_POST['id']);
		$nama = htmlentities($_POST['nama']);
		$alamat = htmlentities($_POST['alamat']);
		$tlp = htmlentities($_POST['tlp']);
		$email = htmlentities($_POST['email']);
		$nik = htmlentities($_POST['nik']);
        DB::table("member")->where("id_member", "=", $id)->update([
            "nm_member" => $nama,
            "alamat_member" => $alamat,
            "telepon" => $tlp,
            "email" => $email,
            "NIK" => $nik
        ]);
		
		echo '<script>window.location="../index.php?page=user&success=edit-data"</script>';
    }

    if ($action == "ganti-pass") {
        $id = htmlentities($_POST['id']);
		$user = htmlentities($_POST['user']);
		$pass = htmlentities($_POST['pass']);

        DB::table("login")->where("id_member", "=", $id)->update([
            "user" => $user,
            "pass" => md5($pass)
        ]);

		echo '<script>window.location="../index.php?page=user&success=edit-data"</script>';
    }

    if ($action == "delete") {
        $id = htmlentities($_GET['id']);

        Kategori::delete($id);

        echo '<script>window.location="../index.php?page=kategori&&remove=hapus-data"</script>';
    }
} else {
    echo '<script>window.location="../index.php"</script>';
}
