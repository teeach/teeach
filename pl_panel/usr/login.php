<?php
	if (@$_GET['action']=="check") {
		require '../../PasswordHash.php';
		$usuario = $_POST['usuario'];
		$pass = $_POST['pass'];

		$t_hasher = new PasswordHash(8, FALSE);

		$fp = fopen("../../config.json", "r");
		$file = fread($fp, filesize("../../config.json"));

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
			//header('Location: index.php');
		} else {
			header('Location: login.php?err=autf');
		}
	}

?>

<?php
	session_start();
	include('../../core.php');
	if (isset($_SESSION['h'])) {
		$user_h = $row['h'];
		$time = date("Y-m-d H:i:s");
		$query_last_time = $con->query("UPDATE pl_users SET last_time='$time' WHERE h='$user_h'")or die("Query error!");
		header('Location: index.php');
	}
?>




<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title><?php echo _("Log in"); ?> | Teeach</title>
	<link rel="stylesheet" href="../../src/css/login.css">
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
					echo '<div class="msg_error"><img src="../../src/ico/error.png"/>'._("Username or password are incorrect.").'</div>';
				}
			}
		?>		
	<form method="POST" action="login.php?action=check" autocomplete="off">
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
	<br>
	<a href="register.php"><?php echo _("New account"); ?></a>
</section>
</center>

	<?php $System->set_footer(); ?>

</body>
</html>