<?php
	include("../../core.php");
	$System = new System();
	$System->check_admin();
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Admin | Teeach</title>
	<link rel="stylesheet" href="../../src/css/main.css" />
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'/>
</head>
<body>
	<h1><?php echo _("Administration"); ?></h1>
	<div id="admin_menu">
		<a href="users.php?action"><img class="icon" src="../../src/ico/users.png" alt="Users" style="border-radius: 100%"><?php echo _("Users"); ?></a>
		<a href="groups.php?action"><?php echo _("Groups"); ?></a>
		<!--<a href="organization.php?action"><img src="../../src/ico/university.png" alt="University" class="icon" style="border-radius: 100%"></a>-->
		<a href="posts.php?action"><img src="../../src/ico/pencil.png" alt="Posts" class="icon" style="border-radius: 100%"><?php echo _("Posts");?></a>		
		<a href="settings.php?action"><img class="icon" src="../../src/ico/settings.png" alt="Settings"><?php echo _("Settings"); ?></a>
	</div>
	<br>
	<a href="../usr/index.php">Volver al panel de Usuario</a>
	<br>
	<p>Hora del servidor: <?php echo date("d-m-Y H:i:s")?></p>
</body>
</html>