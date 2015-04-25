<?php
	session_start();
	session_destroy();
	include("core.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<?php
		$System = new System();
		$System->set_head(); 
	?>
	<meta charset="UTF-8">
	<title><?php echo _("Logout"); ?> | Teeach</title>
	<link rel="stylesheet" href="src/css/main.css">
	
</head>
<body>
	<h1><?php echo _("You're logout"); ?></h1>
	<p>Cerraste sesión exitosamente.</p>
	<a href="index.php">Haz clic aquí para aceptar</a>
</body>
</html>