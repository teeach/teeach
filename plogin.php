<?php
	require 'PasswordHash.php';
	$usuario = $_POST['usuario'];
	$pass = $_POST['pass'];

	//Algoritmo
	//~ $pass_md5 = md5($pass);
	//~ $pass_enc = $pass_md5;

	$t_hasher = new PasswordHash(8, FALSE);

	$fp = fopen("config.json", "r");
	$file = fread($fp, filesize("config.json"));

	$json = json_decode($file);

	$dbserver = $json->{'dbserver'};
	$dbuser = $json->{'dbuser'};
	$dbpass = $json->{'dbpass'};
	$database = $json->{'database'};	

	$con = mysqli_connect($dbserver, $dbuser, $dbpass, $database);
	$query = $con->query("select * from pl_users where username='$usuario'");
	$row = mysqli_fetch_array($query);
		$userid = $row['id'];
		$pass_db = $row['pass'];

		$check = $t_hasher->CheckPassword($pass, $pass_db);

		if ($check) {
			session_start();
			$_SESSION['h'] = $row['h'];
			header('Location: pl_panel/usr/index.php');
		} else {
			header('Location: login.php?err=autf');
		}
?>