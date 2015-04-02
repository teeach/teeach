<?php
	include("../../core.php");
	include("../../usr.php");

	$System = new System();
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Usuarios | Educa</title>
	<link rel="stylesheet" href="../../src/css/main.css" />
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'/>
</head>
<body>

	<?php
		$action = $_GET['action'];
		if ($action == "new") {
			echo '
				<a href="groups.php?action"><img src="../../src/ico/back.svg" alt="Atrás" class="btn_back"></a><h2><a href="index.php">Admin</a> >> <a href="groups.php?action">Grupos</a> >> <a href="groups.php?action=new">Nuevo</a></h2>
				<table style="padding: 20px;">
				<form name="cg" method="post" action="groups.php?action=success" autocomplete="off">
					<tr><td><label for="name">Nombre del grupo: </label></td><td><input type="text" name="name" required onfocus="display_txt1()" onblur="hide_txt1()"/></td></tr>
					<tr><td/><td><h6 style="display:none" id="txt_user">El nombre de grupo de 6 a 29 carácteres</h6></td></tr>
					<tr><td><label for="level">Curso: </label></td><td>
						<input type="text" name="level">
					</td></tr>
					<tr><td><input type="submit" value="Enviar"/></td></tr>
				</form>
				</table>
			';
		} elseif ($action == "success") {
			$name = $_POST['name'];
			$level = $_POST['level'];
			$h = substr( md5(microtime()), 1, 18);

			$System = new System();

			$System->conDB("../../config.json");
    		$query = $con->query("insert into pl_groups(name,h,level) values ('$name','$h','$level')")or die("Error");
    		echo "<p>¡Perfecto!</p>";
		} else {

			echo '<a href="index.php"><img src="../../src/ico/back.svg" alt="Atrás" class="btn_back"></a><h2><a href="index.php">Admin</a> >> <a href="groups.php?action">Grupos</a></h2>
			<ul class="submenu">
			<b>Acciones: </b>
			<a href="groups.php?action=new"><li>Nuevo</li></a>
			</ul>
			<center>
				<div class="table">
					<table>
						<thead>
							<th>ID</th>
							<th>Nombre</th>
							<th>Curso</th>
						</thead>
						<tbody>
		';
				$System->conDB("../../config.json");
				$query = $con->query("select * from pl_groups");

				while($row2 = mysqli_fetch_array($query)) {

					$grupoid = $row2['id'];
					

					echo "
					<tr>
						<td>".$row2['id']."</td>
						<td>".$row2['name']."</td>
						<td>".$row2['level']."</td>
					</tr>";
				}
			echo "
		</tbody>
	</table>
	</div>
	</center>";
		}
	?>
	
	<?php $System->set_footer(); ?>
</body>
</html>