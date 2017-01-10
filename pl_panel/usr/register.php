<?php
	include("../../core.php");
	$System = new System;
	
	$con = $System->conDB();
	$lang = $System->parse_lang();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo $lang["register"]; ?> | Teeach</title>
	<?php $System->set_head(); ?>
</head>
<body>
	<?php

	if (@$_GET['action'] == "success") {

		$username = $_POST['username'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$rpassword = $_POST['rpassword'];
		@$accesspass_form = $_POST['accesspass'];

		$query = $System->queryDB("SELECT * FROM pl_settings WHERE property='accesspass'", $con);
		$row = $System->fetch_array($query);
		$accesspass = $row['value'];

		if ($password != $rpassword) {
			die($lang['passwords_not_match']."<br><a href='register.php'>".$lang['accept']."</a>");
		}

		if ($accesspass_form != $accesspass) {
			die($lang['incorrect_accesspass']."<br><a href='register.php'>".$lang['accept']."</a>");
		}

		require '../../PasswordHash.php';

		$h = $System->rand_str(10);
    	$t_hasher = new PasswordHash(8, FALSE);
        $pass = $t_hasher->HashPassword($password);
        $date = date("Y-m-d H:i:s");

		$query = $System->queryDB("INSERT INTO pl_users(username,email,pass,h,privilege,creation_date) VALUES('$username','$email','$pass','$h',1,'$date')", $con);

		$query2 = $System->queryDB("SELECT * FROM pl_settings WHERE property='centername'", $con);
		$row2 = $System->fetch_array($query2);
		$centername = $row2['value'];

		echo '
			<h1>'.$lang["hi"]." ".$username.'</h1>
			<p>'.$lang["welcome_teeach_from"]." ".$centername.'</p>
			<a href="login.php">'.$lang["log_in"].'</a>
		';

	} else {
		$query = $System->queryDB("SELECT * FROM pl_settings WHERE property='accesspass'", $con);
		$row = $System->fetch_array($query);
		$accesspass = $row['value'];

		echo '
			<div class="ui_full_width">
            	<div class="ui_head ui_head_width_actions">
					<h1><i class="fa fa-user-plus" style="cursor: default"></i> '.$lang["new_account"].'</h1>
				</div>
				<form action="register.php?action=success" method="post">
					<table>
						<tr><td><label for="username">'.$lang["username"].': </label></td><td><input type="text" name="username"></td></tr>
						<tr><td><label for="email">'.$lang["email"].': </label></td><td><input type="email" name="email" value="'.@$_GET["email"].'"></td></tr>
						<tr><td><label for="password">'.$lang["password"].': </label></td><td><input type="password" name="password"></td></tr>
						<tr><td><label for="rpassword">'.$lang["repeat_password"].': </label></td><td><input type="password" name="rpassword"></td></tr>
		';

		if ($accesspass != "") {
			echo '<tr><td><label for="accesspass">'.$lang["accesspass"].': </label></td><td><input type="text" name="accesspass"></td></tr>';
		}

		echo '
						<tr><td></td><td><input type="submit" value="'.$lang["register"].'"></td></tr>
					</table>
				</form>
			</div>
		';
	}
		
	?>
</body>
</html>