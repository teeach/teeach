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
</head>
<body>

	<?php
		$action = $_GET['action'];
		if ($action == "new") {
			echo '
				<a href="index.php"><img src="../../src/ico/back.svg" alt="Atrás" class="btn_back"></a><h2><a href="index.php">Admin</a> >> <a href="posts.php?action">Posts</a> >> <a href="posts.php?action=new">Nuevo</a></h2>
				<table style="padding: 20px;">
				<form name="cu" method="post" action="posts.php?action=success" autocomplete="off">
					<tr><td><label for="title">'._("Title:").'</label></td><td><input type="text" name="title" required/></td></tr>
					<tr><td>'._("Body: ").'</td><td><textarea name="body" rows="6" cols="50" required></textarea></td></tr>
					<tr><td></td><td><input type="submit" value="Enviar"/></td></tr>
				</form>
				</table>
			';
		} elseif ($action == "success") {
			
			$title = $_POST['title'];
			$body = $_POST['body'];
    		$h = substr( md5(microtime()), 1, 18);
    		$author = $_SESSION['h'];

    		$System->conDB("../../config.json");
    		$query = $con->query("insert into pl_posts(title,body,h,author) values('$title','$body','$h','$author')")or die("Query Error!");
    		echo "<p>¡Perfecto!</p><a href='posts.php?action'>Aceptar</a>";

    	} elseif($action == "edit") {

    		$h = $_GET['h'];

    		$System->conDB("../../config.json");
    		$query = $con->query("select * from pl_posts where h='$h'")or die("Query error!");
    		$row = mysqli_fetch_array($query);

    		$title = $row['title'];
    		$body = $row['body'];

    		echo '
				<a href="index.php"><img src="../../src/ico/back.svg" alt="Atrás" class="btn_back"></a><h2><a href="index.php">Admin</a> >> <a href="posts.php?action">Posts</a> >> <a href="posts.php?action=new">Editar</a></h2>
				<table style="padding: 20px;">
				<form name="cu" method="post" action="posts.php?action=update&h='.$h.'" autocomplete="off">
					<tr><td><label for="title">'._("Title:").'</label></td><td><input type="text" name="title" value="'.$title.'" required/></td></tr>
					<tr><td>'._("Body: ").'</td><td><textarea name="body" rows="6" cols="50" required>'.$body.'</textarea></td></tr>
					<tr><td></td><td><input type="submit" value="Guardar"/></td></tr>
				</form>
				</table>
    		';

    	} elseif($action == "update") {

    		$h = $_GET['h'];
    		$title = $_POST['title'];
    		$body = $_POST['body'];

    		$System->conDB("../../config.json");
    		$query = $con->query("update pl_posts set title='$title',body='$body' where h='$h'")or die("Query error!");

    		echo "Actualizado. <a href='posts.php?action'>Aceptar</a>";

    	} elseif($action == "delete") {

    		$h = $_GET['h'];

    		$con = $System->conDB("../../config.json");
    		$query = $con->query("delete from pl_posts where h='$h'")or die("Query error!");

    		echo "Eliminado. <a href='posts.php?action'>Aceptar</a>";

		} else {

			echo '<a href="index.php"><img src="../../src/ico/back.svg" alt="Atrás" class="btn_back"></a><h2><a href="index.php">Admin</a> >> <a href="posts.php?action">Posts</a></h2>
			<ul class="submenu">
			<b>Acciones: </b>
			<a href="posts.php?action=new"><li>Nuevo</li></a>
			</ul>
			<center>
				<table class="table">
					<thead>
						<th>ID</th>
						<th>Title</th>
						<th>Body</th>
						<th>Actions</th>
					</thead>
					<tbody>
		';
				$System->conDB("../../config.json");
				$query = $con->query("select * from pl_posts");
				while($row = mysqli_fetch_array($query)) {
					echo "
					<tr>
						<td>".$row['id']."</td>
						<td>".$row['title']."</td>
						<td>".$row['body']."</td>
						<td><a href='posts.php?action=edit&h=".$row['h']."'>Edit</a> <a href='posts.php?action=delete&h=".$row['h']."'>Del</a></td>
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