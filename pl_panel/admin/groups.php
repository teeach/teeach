<?php

	include("../../core.php");

	$System = new System();
	$con = $System->conDB("../../config.json");
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title><?php echo _("Groups"); ?> | Teeach</title>
	<link rel="stylesheet" href="../../src/css/main.css" />
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'/>
</head>
<body>

	<?php
		$action = $_GET['action'];
		if ($action == "new") {
			echo '
				<a href="groups.php?action"><img src="../../src/ico/back.svg" alt="Atrás" class="btn_back"></a>
				<table style="padding: 20px;">
				<form name="cg" method="post" action="groups.php?action=success" autocomplete="off">
					<tr><td><label for="name">'._("Group name: ").'</label></td><td><input type="text" name="name" required onfocus="display_txt1()" onblur="hide_txt1()"/></td></tr>
					<tr><td><label for="category">'._("Category: ").'</label></td><td>
						<select name="category">
						';
						$query = $con->query("SELECT * FROM pl_categories")or die("Query error!");
						while ($row = mysqli_fetch_array($query)) {
							$category_id = $row['id'];
							$category_name = $row['name'];
							echo '<option value="'.$category_id.'">'.$category_name.'</option>';
						}
						echo '
						</select>
					</td></tr>
					<tr><td/><td><h6 style="display:none" id="txt_user">'._("El nombre de grupo de 6 a 29 carácteres").'</h6></td></tr>
					<tr><td><input type="submit" value="Enviar"/></td></tr>
				</form>
				</table>
			';
		} elseif ($action == "success") {
			$name = $_POST['name'];
			$h = substr( md5(microtime()), 1, 18);

			$category_id = $_POST['category'];

			$query1 = $con->query("SELECT * FROM pl_categories WHERE id=$category_id")or die("Query error!");
			$row1 = mysqli_fetch_array($query1);
			$category_h = $row1['h'];

    		$query = $con->query("INSERT INTO pl_groups(name,h,category_h) VALUES ('$name','$h','$category_h')")or die("Query error!");
    		echo "<p>¡Perfecto!</p><a href='groups.php?action'>Accept</a>";
    	} elseif($action == "edit") {

    		$h = $_GET['h'];

    		$System->conDB("../../config.json");
    		$query = $con->query("SELECT * FROM pl_groups WHERE h='$h'");
    		$row = mysqli_fetch_array($query);

    		$groupname = $row['name'];

			echo '
			<a href="index.php"><img src="../../src/ico/back.svg" alt="Atrás" class="btn_back"></a><h2><a href="index.php">Admin</a> >> <a href="groups.php?action">Grupos</a> >> Editar</h2>
			<form method="post" action="groups.php?action=update&h='.$h.'">
				<label for="name">Nombre de grupo: </label><input type="text" name="name" value="'.$groupname.'"><br>
				<input type="submit" value="Enviar">
			</form>
			';
		} elseif($action == "update") {

			$groupname = $_POST['name'];

			$h = $_GET['h'];

			$con = $System->conDB("../../config.json");

			$query = $con->query("UPDATE pl_groups SET name='$groupname' WHERE h='$h'")or die("Query error!");

			echo "<a href='groups.php?action'>Aceptar</a>";

		} elseif($action == "delete") {

			$h = $_GET['h'];

			$con = $System->conDB("../../config.json");

			$query = $con->query("DELETE FROM pl_groups WHERE h='$h'")or die("Query error!");

			echo "¡Eliminado! <a href='groups.php?action'>Aceptar</a>";

		} elseif($action == "requests") {
			
			$query = $con->query("SELECT * FROM pl_groupuser WHERE status='waiting'")or die("Query error!");
			while ($row = mysqli_fetch_array($query)) {

				$group_h = $row['group_h'];
				$user_h = $row['user_h'];
				$request_id = $row['id'];

				$query_group = $con->query("SELECT * FROM pl_groups WHERE h='$group_h'")or die("Query error!");
				$query_user = $con->query("SELECT * FROM pl_users WHERE h='$user_h'")or die("Query error!");

				$row_group = mysqli_fetch_array($query_group);
				$row_user = mysqli_fetch_array($query_user);

				//~User Data
				$name = $row_user['name'];
				$surname = $row_user['surname'];

				//~Group Data
				$groupname = $row_group['name'];

				echo "<a href='groups.php?action=accept_request&request_id=".$request_id."'><li>".$name." ".$surname." wants join to ".$groupname.". Click here to accept this request.</li></a>";
			}

			echo "<br><a href='groups.php?action'>Back</a>";

		} elseif($action == "accept_request") {

			$request_id = $_GET['request_id'];

			$query = $con->query("UPDATE pl_groupuser SET status='active' WHERE id=$request_id")or die("Query error!");

			echo "<a href='groups.php?action=requests'>Accept</a>";

		} else {

			echo '
				<div class="admin_header">

				<div class="admin_hmenu">
					<a href="index.php"><img src="../../src/ico/back.svg" alt="Atrás" class="btn_back"></a>				
					<h2><a href="index.php">Admin</a> >> <a href="users.php?action">'._("Groups").'</a></h2>
                </div>

			<div class="submenu">
				<ul>
					<a href="groups.php?action=new"><li>'._("New").'</li></a>
					<a href="categories.php?action"><li>'._("Categories").'</li></a>
				';

			$query_setting = $con->query("SELECT * FROM pl_settings WHERE property='JP'");
			$row_setting = mysqli_fetch_array($query_setting);
			$JP = $row_setting['value'];

			if($JP == 2) {
				echo '<a href="groups.php?action=requests"><li>'._("Requests").'</li></a>';
			}

			echo '
			</ul>
			</div>
			</div>
			<center>
				<div class="table">
					<table>
						<thead>
							<th>#</th>
							<th>'._("Name").'</th>
							<th>'._("Users").'</th>
							<th>'._("Category").'</th>
							<th>'._("Actions").'</th>
						</thead>
						<tbody>
		';
				
				$query = $con->query("SELECT * FROM pl_groups");

				while($row = mysqli_fetch_array($query)) {

					$group_h = $row['h'];

					$category_h = $row['category_h'];

					$query1 = $con->query("SELECT * FROM pl_categories WHERE h='$category_h'")or die("Query error!");
					$row1 = mysqli_fetch_array($query1);
					$category_name = $row1['name'];

					$query2 = $con->query("SELECT * FROM pl_groupuser WHERE group_h='$group_h'")or die("Query error!");

					echo "
					<tr>
						<td>".$row['id']."</td>
						<td>".$row['name']."</td>
						<td>";

					while ($row2 = mysqli_fetch_array($query2)) {
						$user_h = $row2['user_h'];

						$query3 = $con->query("SELECT * FROM pl_users WHERE h='$user_h'");
						$row3 = mysqli_fetch_array($query3);

						$username = $row3['username'];

						echo $username.", ";
					}

					echo "
					</td>
					<td>".$category_name."</td>
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