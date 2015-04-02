<?php
	include("../../core.php");
	include("../../usr.php");

	$System = new System();
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Usuarios | Project Learn</title>
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
    		$h = substr( md5(microtime()), 1, 18);
    		$password = substr( md5(microtime()), 1, 6);
    		$t_hasher = new PasswordHash(8, FALSE);
            $pass_hash = $t_hasher->HashPassword($password);

    		$System->conDB("../../config.json");
    		$query = $con->query("insert into pl_users(username,name,subname1,subname2,email,phone,home,birthday,h,pass) values ('$user','$name','$subname1','$subname2','$email',$phone,'$home','$birth','$h','$password')")or die("Error");
    		echo "<p>¡Perfecto la contraseña es: ".$password."</p>";
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
						<th>Nombre</th>
						<th>Apellidos</th>
						<th>Grupo</th>
						<th>Correo</th>
						<th>Teléfono</th>
						<th>Nacimiento</th>
						<th>Localidad</th>
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
					if ($row['privilege'] == 4) {
						$nombre = "<b><span style='color:#a00'>".$row['name']."</span></b>";
					} else {
						$nombre = $row['name'];
					}
					echo "
					<tr>
						<td><input type='checkbox'></td>
						<td>".$row['id']."</td>
						<td>".$nombre."</td>
						<td>".$row['subname1'].", ".$row['subname2']."</td>
						<td>".$grupo."</td>
						<td>".$row['email']."</td>
						<td>".$row['phone']."</td>
						<td>".$row['birthday']."</td>
						<td>".$row['home']."</td>
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