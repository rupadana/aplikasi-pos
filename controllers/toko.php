<?php

include __DIR__ . "/../helpers/loaders.php";

$action = isset($_GET['action']) ? $_GET['action'] : "";

if (Authentication::isLoggedIn()) {

    if ($action == "edit") {
        $nama= htmlentities($_POST['namatoko']);
		$alamat = htmlentities($_POST['alamat']);
		$kontak = htmlentities($_POST['kontak']);
		$pemilik = htmlentities($_POST['pemilik']);
		$id = htmlentities($_POST['id']);
		
        DB::table("toko")->where("id_toko","=", $id)->update([
            "nama_toko" => $nama,
            "alamat_toko" => $alamat,
            "tlp" => $kontak,
            "nama_pemilik" => $pemilik
        ]);
        
		echo '<script>window.location="../index.php?page=pengaturan&success=edit-data"</script>';
    }
} else {
    echo '<script>window.location="../index.php"</script>';
}
