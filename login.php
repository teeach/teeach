<?php
	session_start();
	include('core.php');
	if (isset($_SESSION['h'])) {
		header('Location: pl_panel/usr/index.php');
	}
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title><?php echo _("Log in"); ?> | Teeach</title>
	<link rel="stylesheet" href="src/css/login.css">
	<link rel="stylesheet" href="src/css/main.css">
	<?php
		$System = new System();
		$System->set_head();
	?>
</head>

<body>	
	
	<center>
		<h1><?php echo _("Log in"); ?></h1>
	<section id="box">

		<?php
			if(isset($_GET['err'])) {
				$err = $_GET['err'];
				if($err == "autf") {
					echo '<div class="msg_error"><img src="src/ico/error.png"/>'._("Username or password are incorrect.").'</div>';
				}
			}
		?>		
	<form method="POST" action="plogin.php" autocomplete="off">
		<table>
			<tr>
				<td><label for="usuario"><?php echo _("Username: ");?></label></td>
				<td><input type="text" id="usuario" name="usuario" required></td>
			</tr>
			<tr>
				<td><label for="pass"><?php echo _("Password: ");?></label></td>
				<td><input type="password" id="pass" name="pass"></td>
			</tr>
		</table>
		<input type="submit" value="Login">
	</form>
</section>
</center>

	<?php $System->set_footer(); ?>

</body>
</html>