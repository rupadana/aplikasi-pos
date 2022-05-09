<?php

include "./helpers/loaders.php";

@ob_start();
	session_start();

	if(!empty($_SESSION['admin'])){
		//  admin
			include __DIR__ . '/views/layouts/header.php';
			include __DIR__ . '/views/layouts/sidebar.php';
				if(!empty($_GET['page'])){
					include 'admin/module/'.$_GET['page'].'/index.php';
				}else{
					include __DIR__ . '/views/layouts/home.php';
				}
			include __DIR__ . '/views/layouts/footer.php';
		// end admin
	}else{

        include __DIR__ . "/views/auth/login.php";
	}