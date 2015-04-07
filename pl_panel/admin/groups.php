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
    	} elseif($action == "edit") {

    		$h = $_GET['h'];

    		$System->conDB("../../config.json");
    		$query = $con->query("select * from pl_groups where h='$h'");
    		$row = mysqli_fetch_array($query);

    		$groupname = $row['name'];
    		$level = $row['level'];

			echo '
			<a href="index.php"><img src="../../src/ico/back.svg" alt="Atrás" class="btn_back"></a><h2><a href="index.php">Admin</a> >> <a href="groups.php?action">Grupos</a> >> Editar</h2>
			<form method="post" action="groups.php?action=update&h='.$h.'">
				<label for="name">Nombre de grupo: </label><input type="text" name="name" value="'.$groupname.'"><br>
				<label for="level">Curso: </label><input type="text" name="level" value="'.$level.'"><br>
				<input type="submit" value="Enviar">
			</form>
			';
		} elseif($action == "update") {

			$groupname = $_POST['name'];
			$level = $_POST['level'];

			$h = $_GET['h'];

			$con = $System->conDB("../../config.json");

			$query = $con->query("update pl_groups set name='$groupname', level='$level' where h='$h'")or die("Query error!");

			echo "<a href='groups.php?action'>Aceptar</a>";

		} elseif($action == "delete") {

			$h = $_GET['h'];

			$con = $System->conDB("../../config.json");

			$query = $con->query("delete from pl_groups where h='$h'")or die("Query error!");

			echo "Eliminado! <a href='groups.php?action'>Aceptar</a>";
			
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
							<th>Usuarios</th>
							<th>Acciones</th>
						</thead>
						<tbody>
		';
				$System->conDB("../../config.json");
				$query = $con->query("select * from pl_groups");

				while($row = mysqli_fetch_array($query)) {

					$grupoid = $row['id'];

					$query2 = $con->query("select * from pl_groupuser where groupid=$grupoid");

					echo "
					<tr>
						<td>".$row['id']."</td>
						<td>".$row['name']."</td>
						<td>".$row['level']."</td>
						<td>";

					while ($row2 = mysqli_fetch_array($query2)) {
						$userid = $row2['userid'];

						$query3 = $con->query("select * from pl_users where id=$userid");
						$row3 = mysqli_fetch_array($query3);

						$username = $row3['username'];

						echo $username.", ";
					}

					echo "
					</td>
					<td><a href='groups.php?action=edit&h=".$row['h']."'>Editar</a> <a href='groups.php?action=delete&h=".$row['h']."'>Eliminar</a></td>
					";
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