<?php

include "./helpers/loaders.php";

@ob_start();
session_start();
if (!empty($_SESSION['admin'])) {
	include __DIR__ . '/views/layouts/header.php';
	include __DIR__ . '/views/layouts/sidebar.php';
	if (!empty($_GET['page'])) {
		include __DIR__ . '/views/' . $_GET['page'] . '/index.php';
	} else {
		include __DIR__ . '/views/layouts/home.php';
	}
	include __DIR__ . '/views/layouts/footer.php';
} else {

	include __DIR__ . "/views/auth/login.php";
}
