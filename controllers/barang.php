<?php

include __DIR__ . "/../helpers/loaders.php";

$action = isset($_GET['action']) ? $_GET['action'] : "";

if(Authentication::isLoggedIn()) {
    
    if($action == "edit") {
        $id = htmlentities($_POST['id']);
		$kategori = htmlentities($_POST['kategori']);
		$nama = htmlentities($_POST['nama']);
		$merk = htmlentities($_POST['merk']);
		$beli = htmlentities($_POST['beli']);
		$jual = htmlentities($_POST['jual']);
		$satuan = htmlentities($_POST['satuan']);
		$stok = htmlentities($_POST['stok']);
		$tgl = htmlentities($_POST['tgl']);
        
		$update = DB::table("barang")->where("id_barang", "=", $id)->update([
			"id_kategori" => $kategori,
			"nama_barang" => $nama,
			"merk" => $merk,
			"harga_beli" => $beli,
			"harga_jual" => $jual,
			"satuan_barang" => $satuan,
			"stok" => $stok,
			"tgl_update" => $tgl,
		]);
		echo '<script>window.location="../index.php?page=barang/edit&barang='.$id.'&success=edit-data"</script>';
    }

	if($action == "tambah") {
		$id = $_POST['id'];
		$kategori = $_POST['kategori'];
		$nama = $_POST['nama'];
		$merk = $_POST['merk'];
		$beli = $_POST['beli'];
		$jual = $_POST['jual'];
		$satuan = $_POST['satuan'];
		$stok = $_POST['stok'];
		$tgl = $_POST['tgl'];

		DB::table("barang")->insert([
			"id_barang" => $id,
			"id_kategori" => $kategori,
			"nama_barang" => $nama,
			"merk" => $merk,
			"harga_beli" => $beli,
			"harga_jual" => $jual,
			"satuan_barang" => $satuan,
			"stok" => $stok,
			"tgl_input" => $tgl
		]);
	
		echo '<script>window.location="../index.php?page=barang&success=tambah-data"</script>';
	}

	if($action == "delete") {
		$id = $_GET['id'];
		DB::table("barang")->where("id_barang", "=", $id)->delete();
		echo '<script>window.location="../index.php?page=barang&remove=hapus-data"</script>';
	}

	if($action == "restock") {
		$restok = htmlentities($_POST['restok']);
		$id = htmlentities($_POST['id']);

		$data = DB::table("barang")->where("id_barang", "=", $id)->first();
		if($data) {

			DB::table("barang")->where("id_barang", "=", $id)->update(["stok" => (int)$data['stok'] + $restok]);
		}


		echo '<script>window.location="../index.php?page=barang&success-stok=stok-data"</script>';
	}


} else {
    echo '<script>window.location="../index.php"</script>';
}