<?php
	include("../../core.php");
	//~ include("../../usr.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Administrador | Educa</title>
	<link rel="stylesheet" href="../../src/css/main.css" />
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'/>
</head>
<body>
	<h2><?php echo _("Administration"); ?></h2>
	<div id="admin_menu">
		<a href="users.php?action"><img class="icon" src="../../src/ico/users.png" alt="Users" style="border-radius: 100%"><?php echo _("Users & Groups"); ?></a>
		<a href="organization.php?action"><img src="../../src/ico/university.png" alt="University" class="icon" style="border-radius: 100%"><?php echo _("Organization");?></a>
		<!--<a href="subjects.php?action"><?php echo _("Subjects"); ?></a>-->
		<!--<a href="groups.php?action"><?php echo _("Groups"); ?></a>-->
		<!--<a href="hours.php?action"><?php echo _("Timetables"); ?></a>-->
		<!--<a href="exams.php?action"><?php echo _("Exams"); ?></a>-->
		<a href="settings.php?action"><img class="icon" src="../../src/ico/settings.png" alt="Settings"><?php echo _("Settings"); ?></a>
	</div>
</body>
</html>