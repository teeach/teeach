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
		function showTip() {
			document.getElementById("tip").style.display = "block";
		}

		function hideTip() {
			document.getElementById("tip").style.display = "none";
		}

		function confirmDel() {
			var ans = confirm("¿Seguro que quieres eliminar todas las asignaturas creadas?");
			if (ans = true) {
				location.href = "subjects.php?action=deleteall";
			}
		}
	</script>
</head>
<body>

	<?php
		$action = $_GET['action'];
		if ($action == "new") {
			echo '
				<a href="index.php"><img src="../../src/ico/back.svg" alt="Atrás" class="btn_back"></a><h2><a href="index.php">Admin</a> >> <a href="organization.php">Organización</a> >> <a href="subjects.php?action">Asignaturas</a> >> <a href="subjects.php?action=new">Nueva</a></h2>
				<table style="padding: 20px;">
				<form name="cu" method="post" action="subjects.php?action=success" autocomplete="off">
					<tr><td><label for="subject">Asignatura: </label></td><td><input type="text" name="subject" required onclick="showTip()" onblur="hideTip()"></td></tr>
					<tr><td></td><td><h6 style="display:none" id="tip">Puedes escribir varias a la vez: Matemáticas, Lengua y lit., Biología</h6></td></tr>
					<tr><td><input type="submit" value="Enviar" onclick="comprobarformulario()"/></td></tr>
				</form>
				</table>
			';
		} elseif ($action == "success") {
			$subjects = $_POST['subject'];
			$subject_array = explode(", ", $subjects);
			$arraycount = count($subject_array);
    		$System->conDB("../../config.json");
    		for ($i=0;$i<$arraycount;$i++) {
    			$subject = $subject_array[$i];
    			$query = $con->query("insert into pl_subjects(name) values ('$subject')")or die("Error");
    		}    		
    		header('Location: subjects.php?action');
    	} elseif ($action == "deleteall") {
    		$System->conDB("../../config.json");
    		$query = $con->query("delete from pl_subjects")or die("Error!");
    		header('Location: subjects.php?action');
    	} else {
			echo '<a href="index.php"><img src="../../src/ico/back.svg" alt="Atrás" class="btn_back"></a><h2><a href="index.php">Admin</a> >> <a href="organization.php">Organización</a> >> <a href="subjects.php?action">Asignaturas</a></h2>
			<ul class="submenu">
			<b>Acciones: </b>
			<a href="subjects.php?action=new"><li>Nueva</li></a>
			<a href="javascript:void();" onclick="confirmDel();"><li>Limpiar todo</li></a>
			</ul>
			<center>
					<table class="table">
						<thead>
							<th>ID</th>
							<th>Asignatura</th>
						</thead>
						<tbody>
		';
				$System->conDB("../../config.json");
				$query = $con->query("select * from pl_subjects");
				while($row = mysqli_fetch_array($query)) {
					$id = $row['id'];
					$subject = $row['name'];
					echo "
					<tr>
						<td>".$row['id']."</td>
						<td>".$subject."</td>
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