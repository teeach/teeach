<?php

	include("../../core.php");

	$System = new System();
	$System->check_admin();

	$lang = $System->parse_lang();
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title><?php echo $lang["users"]; ?> | Teeach</title>
	<link rel="stylesheet" href="../../src/css/main.css" />
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'/>
	<script>
		function comprobarformulario() {
			
		}

		function display_txt1() {
			document.getElementById("txt_user").style.display="block";
		}

		function hide_txt1() {
			document.getElementById("txt_user").style.display="none";

			var user = document.cu.user.value;
			if (user.length >= 6 && user.length <= 29) {
				document.cu.user.style.borderColor = "#0a0";
			} else {
				document.cu.user.style.borderColor = "#a00";
			}
		}

		function checkPhone() {
			var phone = document.cu.phone.value;
			if (phone.length == 9) {
				document.cu.phone.style.borderColor = "#0a0";
			} else {
				document.cu.phone.style.borderColor = "#a00";
			}
		}
	</script>
</head>
<body>

	<?php
		$action = $_GET['action'];
		if ($action == "new") {
			echo '
				<a href="users.php?action"><img src="../../src/ico/back.svg" alt="Atrás" class="btn_back"></a>
				<table style="padding: 20px;">
				<form name="cu" method="post" action="users.php?action=success" autocomplete="off">
					<tr><td><label for="usr">'.$lang["user"].': </label></td><td><input type="text" name="user" required onfocus="display_txt1()" onblur="hide_txt1()"/></td></tr>
					<tr><td/><td><h6 style="display:none" id="txt_user">El nombre de usuario de 6 a 29 carácteres</h6></td></tr>
					<tr><td><label for="name">'.$lang["name"].': </label></td><td><input type="text" name="name" required/></td></tr>
					<tr><td><label for="surname">'.$lang["surname"].': </label></td><td><input type="text" name="surname" required/></td></tr>
					<tr><td><label for="email">'.$lang["email"].': </label></td><td><input type="email" name="email" required/></td></tr>
					<tr><td><label for="phone">'.$lang["phone"].': </label></td><td><input type="tel" name="phone" required onblur="checkPhone()"/></td></tr>
					<tr><td><label for="home">'.$lang["address"].': </label></td><td><input type="text" name="address" required/></td></tr>
					<tr><td><label for="birth">'.$lang["birthdate"].': </label></td><td><input type="date" name="birth" required/></td></tr>
					<tr><td><label for="privilege">'.$lang["privilege"].': </label></td><td>
						<select name="privilege">
							<option value="1">'.$lang["student"].'</option>
							<option value="2">'.$lang["teacher"].'</option>
							<option value="3">'.$lang["administrator"].'</option>
						</select>
					</td></tr>
					<tr><td><input type="submit" value="Enviar" onclick="comprobarformulario()"/></td></tr>
				</form>
				</table>
			';
		} elseif ($action == "success") {
			require '../../PasswordHash.php';
			$user = $_POST['user'];
			$name = $_POST['name'];
			$surname = $_POST['surname'];
			$email = $_POST['email'];
			$phone = $_POST['phone'];
			$address = $_POST['address'];
			$birth = $_POST['birth'];
			$privilege = $_POST['privilege'];
    		$h = $System->rand_str(10);
    		$password = substr( md5(microtime()), 1, 6);
    		$t_hasher = new PasswordHash(8, FALSE);
            $pass = $t_hasher->HashPassword($password);

    		$con = $System->conDB("../../config.json");
    		$query = $System->queryDB("INSERT INTO pl_users(username,name,surname,email,phone,address,birthdate,h,pass,privilege) VALUES ('$user','$name','$surname','$email',$phone,'$address','$birth','$h','$pass',$privilege)", $con);
    		echo "<p>¡Perfecto la contraseña es: ".$password."</p><a href='users.php?action'>".$lang['accept']."</a>";

    	} elseif($action == "edit") {

    		$h = $_GET['h'];

    		$con = $System->conDB("../../config.json");
    		$query = $System->queryDB("SELECT * FROM pl_users WHERE h='$h'", $con);
    		$row = $System->fetch_array($query);

    		echo '
				<a href="index.php"><img src="../../src/ico/back.svg" alt="Atrás" class="btn_back"></a><h2><a href="index.php">Admin</a> >> <a href="users.php?action">Usuarios</a> >> Edit</h2>
				<table style="padding: 20px;">
				<form name="cu" method="post" action="users.php?action=update&h='.$h.'" autocomplete="off">
					<tr><td><label for="usr">'.$lang["user"].': </label></td><td><input type="text" name="user" value="'.$row["username"].'" required onfocus="display_txt1()" onblur="hide_txt1()"/></td></tr>
					<tr><td/><td><h6 style="display:none" id="txt_user">El nombre de usuario de 6 a 29 carácteres</h6></td></tr>
					<tr><td><label for="name">'.$lang["name"].': </label></td><td><input type="text" name="name" value="'.$row['name'].'" required/></td></tr>
					<tr><td><label for="surname">'.$lang["surname"].': </label></td><td><input type="text" name="surname" value="'.$row['surname'].'" required/></td></tr>
					<tr><td><label for="email">'.$lang["email"].': </label></td><td><input type="email" name="email" value="'.$row['email'].'" required/></td></tr>
					<tr><td><label for="phone">'.$lang["phone"].': </label></td><td><input type="tel" name="phone" value="'.$row['phone'].'" required onblur="checkPhone()"/></td></tr>
					<tr><td><label for="home">'.$lang["address"].': </label></td><td><input type="text" name="address" value="'.$row['home'].'" required/></td></tr>
					<tr><td><label for="birth">'.$lang["birthdate"].': </label></td><td><input type="date" name="birth" value="'.$row['birthdate'].'" required/></td></tr>
					<tr><td><label for="privilege">'.$lang["privilege"].': </label></td><td>
						<select name="privilege">
							<option value="1">'.$lang["student"].'</option>
							<option value="2">'.$lang["teacher"].'</option>
							<option value="3">'.$lang["administrator"].'</option>
						</select>
					</td></tr>
					<tr><td><input type="submit" value="Enviar" onclick="comprobarformulario()"/></td></tr>
				</form>
				</table>
    		';

    	} elseif($action == "update") {

    		$h = $_GET['h'];

    		$username = $_POST['user'];
    		$name = $_POST['name'];
    		$surname = $_POST['surname'];
    		$email = $_POST['email'];
    		$phone = $_POST['phone'];
    		$address = $_POST['address'];
    		$birth = $_POST['birth'];
    		$privilege = $_POST['privilege'];

    		$con = $System->conDB("../../config.json");
    		$query = $System->queryDB("UPDATE pl_users SET username='$username',name='$name',surname='$surname',email='$email',phone='$phone',address='$address',birthdate='$birth',privilege=$privilege WHERE h='$h'", $con);

    		echo "<a href='users.php?action'>".$lang['accept']."</a>";

    	} elseif($action == "delete") {

    		$h = $_GET['h'];

    		$con = $System->conDB("../../config.json");
    		$query = $System->queryDB("SELECT * FROM pl_users WHERE h='$h'", $con);
    		$row = $System->fetch_array($query);

    		$privilege = $row['privilege'];

    		if ($privilege == 4) {
    			die("<h1>"._('What are you doing!?')."</h1><p>"._('You cannot delete the general admin because the database will be destroyed!')."</p><a href='users.php?action'>".$lang['accept']."</a>");
    		}

    		$query = $System->queryDB("DELETE FROM pl_users WHERE h='$h'", $con);

    		echo "<a href='users.php?action'>Aceptar</a>";

		} else {

			echo '
			<div class="admin_header">

				<div class="admin_hmenu">
					<a href="index.php"><img src="../../src/ico/back.svg" alt="Atrás" class="btn_back"></a>				
					<h2><a href="index.php">Admin</a> >> <a href="users.php?action">'.$lang["users"].'</a></h2>
                </div>

				<div class="submenu">
					<ul>
                    	<a href="users.php?action=new"><li><img src="../../src/ico/add.png">'.$lang["new"].'</li></a>
                	</ul>
                </div>
            </div>
            <center>
                <table class="ui_table">
                    <thead>
                        <th class="select"><input class="select_all" type="checkbox" /></th>
                        <th>#</th>
                        <th>'.$lang["name"].'</th>
                        <th>'.$lang["surname"].'</th>
                        <th>'.$lang["user"].'</th>
                        <th>'.$lang["email"].'</th>
                        <th>'.$lang["phone"].'</th>
                        <th>'.$lang["birthdate"].'</th>
                        <th>'.$lang["address"].'</th>
                        <th>'.$lang["privilege"].'</th>
                        <th class="actions">'.$lang["actions"].'</th>
                    </thead>
                    <tbody>';
                                    
				$con = $System->conDB("../../config.json");
				$query = $System->queryDB("SELECT * FROM pl_users", $con);
				while($row = $System->fetch_array($query)) {
					//Comprobar si es administrador
					if ($row['privilege'] >= 3) {
						$nombre = "<b><span style='color:#a00'>".$row['name']."</span></b>";
					} else {
						$nombre = $row['name'];
					}

					switch($row['privilege']) {
						case 1:
							$privilege = $lang["user"];
							break;
						case 2:
							$privilege = $lang["teacher"];
							break;
						case 3:
							$privilege = $lang["admin"];
							break;
						case 4:
							$privilege = $lang["admin"];
					}

					echo "
					<tr>
						<td class='select'><input type='checkbox' class='checkbox' /></td>
						<td>".$row['id']."</td>
						<td>".$nombre."</td>
						<td>".$row['surname']."</td>
						<td>".$row['username']."</td>
						<td>".$row['email']."</td>
						<td>".$row['phone']."</td>
						<td>".$birthdate = $System->get_date_format($row['birthdate'], $lang, $con)."</td>
						<td>".$row['address']."</td>
						<td>".$privilege."</td>
						<td class='actions'>
							<a class='ui_action' href='users.php?action=edit&h=".$row['h']."'>".$lang['edit']."</a>
                            <a class='ui_action' href='users.php?action=delete&h=".$row['h']."'>".$lang['delete']."</a>
                        </td>
					</tr>";
				}
			echo "
		</tbody>
	</table>
	</center>";
		}
	?>
</body>
</html>