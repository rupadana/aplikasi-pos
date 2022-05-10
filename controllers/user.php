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

    if ($action == "update-image") {
        $id = htmlentities($_POST['id']);
		set_time_limit(0);
		$allowedImageType = array("image/gif",   "image/JPG",   "image/jpeg",   "image/pjpeg",   "image/png",   "image/x-png"  );
		
		if ($_FILES['foto']["error"] > 0) {
			$output['error']= "Error in File";
		} elseif (!in_array($_FILES['foto']["type"], $allowedImageType)) {
			echo '<script>alert("You can only upload JPG, PNG and GIF file");window.location="../index.php?page=user"</script>';
		}elseif (round($_FILES['foto']["size"] / 1024) > 4096) {
			// echo "WARNING !!! Besar Gambar Tidak Boleh Lebih Dari 4 MB";
			echo '<script>alert("WARNING !!! Besar Gambar Tidak Boleh Lebih Dari 4 MB");window.location="../index.php?page=user"</script>';
		}else{
			$dir = __DIR__ . "/../assets/img/user/";
			$tmp_name = $_FILES['foto']['tmp_name'];
			$name = time().basename($_FILES['foto']['name']);
            if(move_uploaded_file($tmp_name, $dir.$name)){
					//post foto lama
				$foto2 = $_POST['foto2'];
				//remove foto di direktori
				unlink($dir.$foto2.'');
				//input foto
				$id = $_POST['id'];
				$data[] = $name;
				$data[] = $id;
                
                DB::table("member")->where("id_member", "=", $id)->update([
                    "gambar" => $name
                ]);
				echo '<script>window.location="../index.php?page=user&success=edit-data"</script>';
			}else{
				echo '<script>alert("Masukan Gambar !");window.location="../index.php?page=user"</script>';
			}
		}
    }
} else {
    echo '<script>window.location="../index.php"</script>';
}
