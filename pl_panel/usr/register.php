<?php
	include("../../core.php");
	$System = new System;
	$con = $System->conDB("../../config.json");
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo _("Register"); ?> | Teeach</title>
	<?php $System->set_head(); ?>
</head>
<body>
	<?php

	if (@$_GET['action'] == "success") {

		$username = $_POST['username'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$rpassword = $_POST['rpassword'];
		$accesspass_form = $_POST['accesspass'];

		$query = $con->query("SELECT * FROM pl_settings WHERE property='accesspass'")or die("Query error!");
		$row = mysqli_fetch_array($query);
		$accesspass = $row['value'];

		if ($password != $rpassword) {
			die("The passwords don't match.<br><a href='register.php'>Accept</a>");
		}

		if ($accesspass_form != $accesspass) {
			die("Accesspass incorrect.<br><a href='register.php'>Accept</a>");
		}

		require '../../PasswordHash.php';

		$h = substr( md5(microtime()), 1, 18);
    	$t_hasher = new PasswordHash(8, FALSE);
        $pass = $t_hasher->HashPassword($password);
        $date = date("Y-m-d H:i:s");

		$query = $con->query("INSERT INTO pl_users(username,email,pass,h,privilege,creation_date) VALUES('$username','$email','$pass','$h',1,'$date')")or die("Query error!");

		$query2 = $con->query("SELECT * FROM pl_settings WHERE property='centername'")or die("Query error!");
		$row2 = mysqli_fetch_array($query2);
		$centername = $row2['value'];

		echo '
			<h1>'._("Hi ").$username.'</h1>
			<p>'._("Welcome to Teeach from ").$centername.'</p>
			<a href="login.php">'._("Log in").'</a>
		';

	} else {
		$query = $con->query("SELECT * FROM pl_settings WHERE property='accesspass'")or die("Query error!");
		$row = mysqli_fetch_array($query);
		$accesspass = $row['value'];

		echo '
			<h1>'._("New account").'</h1>
			<form action="register.php?action=success" method="post">
				<table>
					<tr><td><label for="username">'._("Username: ").'</label></td><td><input type="text" name="username"></td></tr>
					<tr><td><label for="email">'._("Email: ").'</label></td><td><input type="text" name="email"></td></tr>
					<tr><td><label for="password">'._("Password: ").'</label></td><td><input type="password" name="password"></td></tr>
					<tr><td><label for="rpassword">'._("Repeat password: ").'</label></td><td><input type="password" name="rpassword"></td></tr>
		';

		if ($accesspass != "") {
			echo '<tr><td><label for="accesspass">Accesspass: </label></td><td><input type="text" name="accesspass"></td></tr>';
		}

		echo '
				<tr><td></td><td><input type="submit" value="'._("Register").'"></td></tr>
			</table>
			</form>
		';
	}
		
	?>
</body>
</html>