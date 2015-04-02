<?php
	include("../../core.php");
	include("../../usr.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Settings | Project Learn</title>
	<link rel="stylesheet" href="../../src/css/main.css" />
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'/>
</head>
<body>

	<?php
		$action = $_GET['action'];
		if ($action == "new") {
			echo '
				<a href="index.php"><img src="../../src/ico/back.svg" alt="Atrás" class="btn_back"></a><h2><a href="index.php">Admin</a> >> <a href="users.php?action">Usuarios</a></h2>
				<table style="padding: 20px;">
				<form name="cu" method="post" action="users.php?action=success" autocomplete="off">
					<tr><td><label for="usr">'._("Username: ").'</label></td><td><input type="text" name="user" required onfocus="display_txt1()" onblur="hide_txt1()"/></td></tr>
					<tr><td/><td><h6 style="display:none" id="txt_user">'._("The username must has 6 to 30 characters.").'</h6></td></tr>
					<tr><td><label for="name">'._("Name: ").'</label></td><td><input type="text" name="name" required/></td></tr>
					<tr><td><label for="subname1">'._("Subname 1: ").'</label></td><td><input type="text" name="subname1" required/></td></tr>
					<tr><td><label for="subname2">'._("Subname 2: ").'</label></td><td><input type="text" name="subname2" required/></td></tr>
					<tr><td><label for="email">'._("Email: ").'</label></td><td><input type="email" name="email" required/></td></tr>
					<tr><td><label for="phone">'._("Phone: ").'</label></td><td><input type="tel" name="phone" required onblur="checkPhone()"/></td></tr>
					<tr><td><label for="home">'._("Home :").'</label></td><td><input type="text" name="home" required/></td></tr>
					<tr><td><label for="birth">'._("Birthdate: ").'</label></td><td><input type="date" name="birth" required/></td></tr>
					<tr><td><input type="submit" value="'._("Send").'" onclick="comprobarformulario()"/></td></tr>
				</form>
				</table>
			';
		} elseif ($action == "success") {

			$centername = $_POST['centername'];

			$System = new System();
    		$System->conDB("../../config.json");
    		$query = $con->query("update pl_config set value='$centername' where property='centername'")or die("Error");

    		header('Location: settings.php?action&message=true');

		} else {

			echo '<a href="index.php"><img src="../../src/ico/back.svg" alt="Atrás" class="btn_back"></a><h2><a href="index.php">Admin</a> >> <a href="settings.php?action">'._("Settings").'</a></h2>
				<center>
				';

				if (@$_GET['message'] == 'true') {
					echo '<div class="msg_ok"><img src="../../src/ico/ok.png"/>¡Éxito!</div>';
				}

				$con2 = mysqli_connect($dbserver, $dbuser, $dbpass, $database);
				$query2 = $con2->query("select * from pl_config");
				while($row2 = mysqli_fetch_array($query2)) {
					$property = $row2['property'];
					if ($property == "centername") {
						$centername = $row2['value'];
						break;
					}
				}
					echo "
					<form action='settings.php?action=success' method='POST'>
						<label for='centername'>"._('Centername: ')."</label><input type='text' name='centername' value='".$centername."'>
						<input type='submit' value='"._('Send')."'>
					</form>";
				}
			echo "
	</center>";
	?>
</body>
</html>