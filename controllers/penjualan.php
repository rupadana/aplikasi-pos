<?php

include __DIR__ . "/../helpers/loaders.php";

$action = isset($_GET['action']) ? $_GET['action'] : "";

if (Authentication::isLoggedIn()) {

    if ($action == "delete") {
        
		$id = $_GET['id'];
		
        Penjualan::delete($id);
		echo '<script>window.location="../index.php?page=jual"</script>';
    }

    if($action == "edit") {
        $id = htmlentities($_POST['id']);
		$id_barang = htmlentities($_POST['id_barang']);
		$jumlah = htmlentities($_POST['jumlah']);

		$hasil = Barang::findByIdBarang($id_barang);

		if($hasil['stok'] > $jumlah)
		{
			$jual = $hasil['harga_jual'];
			$total = $jual * $jumlah;
            DB::table("penjualan")->where("id_penjualan", '=', $id)->update([
                "jumlah" => $jumlah,
                "total" => $total
            ]);
			echo '<script>window.location="../index.php?page=jual#keranjang"</script>';
		}else{
			echo '<script>alert("Keranjang Melebihi Stok Barang Anda !");
					window.location="../index.php?page=jual#keranjang"</script>';
		}
    }

    if($action == "reset-keranjang") {
        DB::table("penjualan")->delete();

        echo '<script>window.location="../index.php?page=jual"</script>';
    }


    if($action == "jual") {
        $id = $_GET['id'];
		
		// get tabel barang id_barang 
		$hsl = DB::table("barang")->where("id_barang", '=', $id)->first();

		if($hsl['stok'] > 0)
		{
			$kasir =  $_GET['id_kasir'];
			$jumlah = 1;
			$total = $hsl['harga_jual'];
			$tgl = date("j F Y, G:i");

            DB::table("penjualan")
            ->insert([
                "id_barang" => $id,
                "id_member" => $kasir,
                "jumlah" => $jumlah,
                "total" => $total,
                "tanggal_input" => $tgl
            ]);
			echo '<script>window.location="../index.php?page=jual&success=tambah-data"</script>';

		}else{
			echo '<script>alert("Stok Barang Anda Telah Habis !");
					window.location="../index.php?page=jual#keranjang"</script>';
		}
    }
} else {
    echo '<script>window.location="../index.php"</script>';
}
