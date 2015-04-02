<?php
	include("../../core.php");
	include("../../usr.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title><?php echo "Hola, $usr_name | Project Learn"; ?></title>
	<link rel="stylesheet" href="../../src/css/main.css">
	<?php
		$System = new System();
		$System->set_head(); 
	?>
</head>
<body>
	<?php

	$System = new System();
	$System->set_header();
	$System->set_usr_menu($usr_h,$usr_privilege);

	echo "<h1>Coming soon</h1>";

	?>
</body>
</html>