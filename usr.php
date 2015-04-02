<?php

	session_start();

	if(!isset($_SESSION['userid'])) {
		die("You aren't login.");
	}

	$fp = fopen("../../config.json", "r");
	$file = fread($fp, filesize("../../config.json"));

	$json = json_decode($file);

	$dbserver = $json->{"dbserver"};
	$dbuser = $json->{"dbuser"};
	$dbpass = $json->{"dbpass"};
	$database = $json->{"database"};

	$userid = $_SESSION['userid'];
	$con = mysqli_connect($dbserver, $dbuser, $dbpass, $database);
	$query = $con->query("select * from pl_users where id=$userid");
	$row = mysqli_fetch_array($query);

	$usr_id = $userid;
	$usr_name = $row['name'];
	$usr_subname1 = $row['subname1'];
	$usr_subname2 = $row['subname2'];
	$usr_email = $row['email'];
	$usr_tel = $row['phone'];
	$usr_level = $row['level'];
	$usr_h = $row['h'];
	$usr_photo = $row['photo'];
	$usr_birthdate = $row['birthday'];
	$usr_home = $row['home'];
	$usr_privilege = $row['privilege'];
?>