<?php

include __DIR__ . "/../helpers/loaders.php";

$action = isset($_GET['action']) ? $_GET['action'] : "";

if (Authentication::isLoggedIn()) {

    if ($action == "edit") {
        $nama = htmlentities($_POST['kategori']);
        $id = htmlentities($_POST['id']);

        $update = DB::table("kategori")->where("id_kategori", "=", $id)->update([
            "nama_kategori" => $nama
        ]);

        echo '<script>window.location="../index.php?page=kategori&uid=' . $id . '&success-edit=edit-data"</script>';
    }

    if ($action == "tambah") {
        $nama = $_POST['kategori'];
        $tgl = date("j F Y, G:i");
        $insert = DB::table("kategori")->insert([
            "nama_kategori" => $nama,
            "tgl_input" => $tgl
        ]);
        echo '<script>window.location="../index.php?page=kategori&&success=tambah-data"</script>';
    }

    if ($action == "delete") {
        $id = htmlentities($_GET['id']);

        Kategori::delete($id);

        echo '<script>window.location="../index.php?page=kategori&&remove=hapus-data"</script>';
    }
} else {
    echo '<script>window.location="../index.php"</script>';
}
