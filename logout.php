<?php
	// oturumu başlat
	session_start();

	$_SESSION  = array();

	// oturumları kapat
	session_destroy();

	// tekrar giriş sayfasına yönlendir.
	header('location: login.php');
	exit;
?>