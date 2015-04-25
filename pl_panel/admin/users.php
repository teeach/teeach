<?php
	include("../../core.php");
	include("../../usr.php");

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
				<a href="index.php"><img src="../../src/ico/back.svg" alt="Atrás" class="btn_back"></a><h2><a href="index.php">Admin</a> >> <a href="users.php?action">Usuarios</a></h2>
				<table style="padding: 20px;">
				<form name="cu" method="post" action="users.php?action=success" autocomplete="off">
					<tr><td><label for="usr">Usuario: </label></td><td><input type="text" name="user" required onfocus="display_txt1()" onblur="hide_txt1()"/></td></tr>
					<tr><td/><td><h6 style="display:none" id="txt_user">El nombre de usuario de 6 a 29 carácteres</h6></td></tr>
					<tr><td><label for="name">Nombre: </label></td><td><input type="text" name="name" required/></td></tr>
					<tr><td><label for="subname1">Apellido 1: </label></td><td><input type="text" name="subname1" required/></td></tr>
					<tr><td><label for="subname2">Apellido 2: </label></td><td><input type="text" name="subname2" required/></td></tr>
					<tr><td><label for="email">Correo: </label></td><td><input type="email" name="email" required/></td></tr>
					<tr><td><label for="phone">Teléfono: </label></td><td><input type="tel" name="phone" required onblur="checkPhone()"/></td></tr>
					<tr><td><label for="home">Localidad: </label></td><td><input type="text" name="home" required/></td></tr>
					<tr><td><label for="birth">Nacimiento: </label></td><td><input type="date" name="birth" required/></td></tr>
					<tr><td><label for="privilege">Privilegios: </label></td><td>
						<select name="privilege">
							<option value="1">Estudiante</option>
							<option value="2">Profesor</option>
							<option value="3">Administrador</option>
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
			$subname1 = $_POST['subname1'];
			$subname2 = $_POST['subname2'];
			$email = $_POST['email'];
			$phone = $_POST['phone'];
			$home = $_POST['home'];
			$birth = $_POST['birth'];
			$privilege = $_POST['privilege'];
    		$h = substr( md5(microtime()), 1, 18);
    		$password = substr( md5(microtime()), 1, 6);
    		$t_hasher = new PasswordHash(8, FALSE);
            $pass = $t_hasher->HashPassword($password);

    		$System->conDB("../../config.json");
    		$query = $con->query("insert into pl_users(username,name,subname1,subname2,email,phone,home,birthday,h,pass,privilege) values ('$user','$name','$subname1','$subname2','$email',$phone,'$home','$birth','$h','$pass',$privilege)")or die("Error");
    		echo "<p>¡Perfecto la contraseña es: ".$password."</p><a href='users.php?action'>Aceptar</a>";

    	} elseif($action == "edit") {

    		$h = $_GET['h'];

    		$con = $System->conDB("../../config.json");
    		$query = $con->query("select * from pl_users where h='$h'")or die("Query error!");
    		$row = mysqli_fetch_array($query);

    		echo '
				<a href="index.php"><img src="../../src/ico/back.svg" alt="Atrás" class="btn_back"></a><h2><a href="index.php">Admin</a> >> <a href="users.php?action">Usuarios</a> >> Edit</h2>
				<table style="padding: 20px;">
				<form name="cu" method="post" action="users.php?action=update&h='.$h.'" autocomplete="off">
					<tr><td><label for="usr">'._("Username: ").'</label></td><td><input type="text" name="user" value="'.$row["username"].'" required onfocus="display_txt1()" onblur="hide_txt1()"/></td></tr>
					<tr><td/><td><h6 style="display:none" id="txt_user">El nombre de usuario de 6 a 29 carácteres</h6></td></tr>
					<tr><td><label for="name">'._("Name: ").'</label></td><td><input type="text" name="name" value="'.$row['name'].'" required/></td></tr>
					<tr><td><label for="subname1">'._("Surname 1: ").'</label></td><td><input type="text" name="subname1" value="'.$row['subname1'].'" required/></td></tr>
					<tr><td><label for="subname2">'._("Surname 2: ").'</label></td><td><input type="text" name="subname2" value="'.$row['subname2'].'" required/></td></tr>
					<tr><td><label for="email">'._("Email: ").'</label></td><td><input type="email" name="email" value="'.$row['email'].'" required/></td></tr>
					<tr><td><label for="phone">'._("Phone: ").'</label></td><td><input type="tel" name="phone" value="'.$row['phone'].'" required onblur="checkPhone()"/></td></tr>
					<tr><td><label for="home">'._("Home: ").'</label></td><td><input type="text" name="home" value="'.$row['home'].'" required/></td></tr>
					<tr><td><label for="birth">'._("Birthdate: ").'</label></td><td><input type="date" name="birth" value="'.$row['birthday'].'" required/></td></tr>
					<tr><td><label for="privilege">Privilegios: </label></td><td>
						<select name="privilege">
							<option value="1">Estudiante</option>
							<option value="2">Profesor</option>
							<option value="3">Administrador</option>
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
    		$subname1 = $_POST['subname1'];
    		$subname2 = $_POST['subname2'];
    		$email = $_POST['email'];
    		$phone = $_POST['phone'];
    		$home = $_POST['home'];
    		$birth = $_POST['birth'];
    		$privilege = $_POST['privilege'];

    		$con = $System->conDB("../../config.json");
    		$query = $con->query("update pl_users set username='$username',name='$name',subname1='$subname1',subname2='$subname2',email='$email',phone='$phone',home='$home',birthday='$birth',privilege=$privilege where h='$h'")or die("Query error!");

    		echo "<a href='users.php?action'>Aceptar</a>";

    	} elseif($action == "delete") {

    		$h = $_GET['h'];

    		$con = $System->conDB("../../config.json");
    		$query = $con->query("select * from pl_users where h='$h'")or die("Query error!");
    		$row = mysqli_fetch_array($query);

    		$privilege = $row['privilege'];

    		if ($privilege == 4) {
    			die("<h1>¿¡Qué haces alma cándida!?</h1><p>¡No se puede borrar el administrador general porque sino la base de datos se destruiría!</p><a href='users.php?action'>Aceptar</a>");
    		}

    		$query = $con->query("delete from pl_users where h='$h'")or die("Query error!");

    		echo "<a href='users.php?action'>Aceptar</a>";

		} else {

			echo '<a href="index.php"><img src="../../src/ico/back.svg" alt="Atrás" class="btn_back"></a><h2><a href="index.php">Admin</a> >> <a href="users.php?action">Usuarios</a></h2>
			<ul class="submenu">
			<b>Acciones: </b>
			<a href="users.php?action=new"><li>Nuevo</li></a>
			<a href="groups.php?action"><li>Grupos</li></a>
			</ul>
			<center>
				<table class="table">
					<thead>
						<th></th>
						<th>ID</th>
						<th>'._("Name").'</th>
						<th>'._("Surnames").'</th>
						<th>'._("Username").'</th>
						<th>'._("Email").'</th>
						<th>'._("Phone").'</th>
						<th>'._("Birthdate").'</th>
						<th>'._("Home").'</th>
						<th>'._("Privilege").'</th>
						<th>'._("Actions").'</th>
					</thead>
					<tbody>
		';
				$System->conDB("../../config.json");
				$query = $con->query("select * from pl_users");
				while($row = mysqli_fetch_array($query)) {
					$grupoid = $row['group'];
					$query2 = $con->query("select * from pl_groups where id=$grupoid");
					$row2 = mysqli_fetch_array($query2);
					$grupo = $row['name'];
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
						<td><input type='checkbox'></td>
						<td>".$row['id']."</td>
						<td>".$nombre."</td>
						<td>".$row['subname1'].", ".$row['subname2']."</td>
						<td>".$row['username']."</td>
						<td>".$row['email']."</td>
						<td>".$row['phone']."</td>
						<td>".$row['birthday']."</td>
						<td>".$row['home']."</td>
						<td>".$privilege."</td>
						<td><a href='users.php?action=edit&h=".$row['h']."'>"._('Edit')."</a> <a href='users.php?action=delete&h=".$row['h']."'>"._('Del')."</a></td>
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