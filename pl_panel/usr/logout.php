<?php
	session_start();
	session_destroy();
	include("../../core.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<?php
		$System = new System();
		$System->check_usr();
		$System->set_head(); 
	?>
	<meta charset="UTF-8">
	<title><?php echo _("Logout"); ?> | Teeach</title>
	
	
</head>
<body>
	<h1><?php echo _("Good Bye!"); ?></h1>
	<p><?php echo _("You're logout") ?></p>
	<a href="../../index.php"><?php echo _("Accept") ?></a>
</body>
</html>