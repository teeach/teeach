<?php

	include("../../core.php");

	$System = new System();
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title><?php echo _("Users"); ?> | Teeach</title>
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
					<tr><td><label for="usr">'._("Usuario: ").'</label></td><td><input type="text" name="user" required onfocus="display_txt1()" onblur="hide_txt1()"/></td></tr>
					<tr><td/><td><h6 style="display:none" id="txt_user">El nombre de usuario de 6 a 29 carácteres</h6></td></tr>
					<tr><td><label for="name">'._("Nombre: ").'</label></td><td><input type="text" name="name" required/></td></tr>
					<tr><td><label for="surname">'._("Apellido: ").'</label></td><td><input type="text" name="surname" required/></td></tr>
					<tr><td><label for="email">'._("Correo: ").'</label></td><td><input type="email" name="email" required/></td></tr>
					<tr><td><label for="phone">'._("Teléfono: ").'</label></td><td><input type="tel" name="phone" required onblur="checkPhone()"/></td></tr>
					<tr><td><label for="home">'._("Localidad: ").'</label></td><td><input type="text" name="home" required/></td></tr>
					<tr><td><label for="birth">'._("Nacimiento: ").'</label></td><td><input type="date" name="birth" required/></td></tr>
					<tr><td><label for="privilege">'._("Privilegios: ").'</label></td><td>
						<select name="privilege">
							<option value="1">'._("Student").'</option>
							<option value="2">'._("Teacher").'</option>
							<option value="3">'._("Administrator").'</option>
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
			$home = $_POST['home'];
			$birth = $_POST['birth'];
			$privilege = $_POST['privilege'];
    		$h = substr( md5(microtime()), 1, 18);
    		$password = substr( md5(microtime()), 1, 6);
    		$t_hasher = new PasswordHash(8, FALSE);
            $pass = $t_hasher->HashPassword($password);

    		$con = $System->conDB("../../config.json");
    		$query = $con->query("INSERT INTO pl_users(username,name,surname,email,phone,home,birthday,h,pass,privilege) VALUES ('$user','$name','$surname','$email',$phone,'$home','$birth','$h','$pass',$privilege)")or die("Error");
    		echo "<p>¡Perfecto la contraseña es: ".$password."</p><a href='users.php?action'>Aceptar</a>";

    	} elseif($action == "edit") {

    		$h = $_GET['h'];

    		$con = $System->conDB("../../config.json");
    		$query = $con->query("SELECT * FROM pl_users WHERE h='$h'")or die("Query error!");
    		$row = mysqli_fetch_array($query);

    		echo '
				<a href="index.php"><img src="../../src/ico/back.svg" alt="Atrás" class="btn_back"></a><h2><a href="index.php">Admin</a> >> <a href="users.php?action">Usuarios</a> >> Edit</h2>
				<table style="padding: 20px;">
				<form name="cu" method="post" action="users.php?action=update&h='.$h.'" autocomplete="off">
					<tr><td><label for="usr">'._("Username: ").'</label></td><td><input type="text" name="user" value="'.$row["username"].'" required onfocus="display_txt1()" onblur="hide_txt1()"/></td></tr>
					<tr><td/><td><h6 style="display:none" id="txt_user">El nombre de usuario de 6 a 29 carácteres</h6></td></tr>
					<tr><td><label for="name">'._("Name: ").'</label></td><td><input type="text" name="name" value="'.$row['name'].'" required/></td></tr>
					<tr><td><label for="surname">'._("Surname: ").'</label></td><td><input type="text" name="surname" value="'.$row['surname'].'" required/></td></tr>
					<tr><td><label for="email">'._("Email: ").'</label></td><td><input type="email" name="email" value="'.$row['email'].'" required/></td></tr>
					<tr><td><label for="phone">'._("Phone: ").'</label></td><td><input type="tel" name="phone" value="'.$row['phone'].'" required onblur="checkPhone()"/></td></tr>
					<tr><td><label for="home">'._("Home: ").'</label></td><td><input type="text" name="home" value="'.$row['home'].'" required/></td></tr>
					<tr><td><label for="birth">'._("Birthdate: ").'</label></td><td><input type="date" name="birth" value="'.$row['birthday'].'" required/></td></tr>
					<tr><td><label for="privilege">Privilegios: </label></td><td>
						<select name="privilege">
							<option value="1">'._("Student").'</option>
							<option value="2">'._("Teacher").'</option>
							<option value="3">'._("Administrator").'</option>
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
    		$home = $_POST['home'];
    		$birth = $_POST['birth'];
    		$privilege = $_POST['privilege'];

    		$con = $System->conDB("../../config.json");
    		$query = $con->query("UPDATE pl_users SET username='$username',name='$name',surname='$surname',email='$email',phone='$phone',home='$home',birthday='$birth',privilege=$privilege WHERE h='$h'")or die("Query error!");

    		echo "<a href='users.php?action'>Aceptar</a>";

    	} elseif($action == "delete") {

    		$h = $_GET['h'];

    		$con = $System->conDB("../../config.json");
    		$query = $con->query("SELECT * FROM pl_users WHERE h='$h'")or die("Query error!");
    		$row = mysqli_fetch_array($query);

    		$privilege = $row['privilege'];

    		if ($privilege == 4) {
    			die("<h1>"._('What are you doing!?')."</h1><p>"._('You cannot delete the general admin because the database will be destroyed!')."</p><a href='users.php?action'>"._('Accept')."</a>");
    		}

    		$query = $con->query("DELETE FROM pl_users WHERE h='$h'")or die("Query error!");

    		echo "<a href='users.php?action'>Aceptar</a>";

		} else {

			echo '
			<div class="admin_header">

				<div class="admin_hmenu">
					<a href="index.php"><img src="../../src/ico/back.svg" alt="Atrás" class="btn_back"></a>				
					<h2><a href="index.php">Admin</a> >> <a href="users.php?action">'._("Users").'</a></h2>
                </div>

				<div class="submenu">
					<ul>
                    	<a href="users.php?action=new"><li><img src="../../src/ico/add.png">'._("New").'</li></a>
                	</ul>
                </div>
            </div>
            <div class="ui_full_width">
                <table class="ui_table">
                    <thead>
                        <th class="select"><input class="select_all" type="checkbox" /></th>
                        <th>#</th>
                        <th>'._("Name").'</th>
                        <th>'._("Surname").'</th>
                        <th>'._("Username").'</th>
                        <th>'._("Email").'</th>
                        <th>'._("Phone").'</th>
                        <th>'._("Birthdate").'</th>
                        <th>'._("Address").'</th>
                        <th>'._("Privilege").'</th>
                        <th class="actions">'._("Actions").'</th>
                    </thead>
                    <tbody>';
                                    
				$con = $System->conDB("../../config.json");
				$query = $con->query("SELECT * FROM pl_users");
				while($row = mysqli_fetch_array($query)) {
					//Comprobar si es administrador
					if ($row['privilege'] >= 3) {
						$nombre = "<b><span style='color:#a00'>".$row['name']."</span></b>";
					} else {
						$nombre = $row['name'];
					}

					switch($row['privilege']) {
						case 1:
							$privilege = _("User");
							break;
						case 2:
							$privilege = _("Teacher");
							break;
						case 3:
							$privilege = _("Admin");
							break;
						case 4:
							$privilege = _("Admin");
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
						<td>".$row['birthday']."</td>
						<td>".$row['address']."</td>
						<td>".$privilege."</td>
						<td class='actions'>
							<a class='ui_action' href='users.php?action=edit&h=".$row['h']."'>"._('Edit')."</a>
                            <a class='ui_action' href='users.php?action=delete&h=".$row['h']."'>"._('Delete')."</a>
                        </td>
					</tr>";
				}
			echo "
		</tbody>
	</table>
	</div>";
		}
	?>
</body>
</html>