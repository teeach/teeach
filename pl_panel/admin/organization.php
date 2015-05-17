<?php
	include("../../core.php");
	include("../../usr.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Organization | Teeach</title>
	<link rel="stylesheet" href="../../src/css/main.css" />
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'/>
</head>
<body>
	<a href="index.php"><img src="../../src/ico/back.svg" alt="Atrás" class="btn_back"></a><h2><a href="index.php">Admin</a> >> <a href="organization.php">Organization</a></h2>
		<ul class="submenu">
			<b>Acciones: </b>
			<a href="subjects.php?action"><li><?php echo _("Subjects"); ?></li></a>
		</ul>
</body>
</html>