<?php
	include("../../core.php");

	session_start();

	$System = new System();

	$con = $System->conDB("../../config.json");
	$User = $System->get_user_by_id($_SESSION['h'], $con);
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title><?php echo "Hola, $User->name | Project Learn"; ?></title>
	<link rel="stylesheet" href="../../src/css/main.css">
	<?php
		
		$System->set_head();

		
	?>
</head>
<body>
	<?php

	if(!isset($_SESSION['h'])) {
		die("You aren't logged in.");
	}

	$System->set_header();
	$System->set_usr_menu($User->h,$User->privilege);

		echo "<a href='profile.php?h=".$User->h."'>Hola $User->name $User->surname1</a>. Tienes 0 mensajes.";
	?>
</body>
</html>
