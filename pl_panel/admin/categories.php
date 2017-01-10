<?php

	include("../../core.php");

	$System = new System();
	$System->check_admin();
	$con = $System->conDB();
	$lang = $System->parse_lang();
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title><?php echo _("Categories"); ?> | Teeach</title>
	<link rel="stylesheet" href="../../src/css/main.css" />
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'/>
</head>
<body>

	<?php
		$action = $_GET['action'];
		if ($action == "new") {
			echo '
				<table style="padding: 20px;">
				<form name="cg" method="post" action="categories.php?action=success" autocomplete="off">
					<tr><td><label for="name">'.$lang["name"].': </label></td><td><input type="text" name="name" required onfocus="display_txt1()" onblur="hide_txt1()"/></td></tr>
					<tr><td/><td><h6 style="display:none" id="txt_user">'._("El nombre de grupo de 6 a 29 carácteres").'</h6></td></tr>
					<tr><td><input type="submit" value="'.$lang["create"].'"/></td></tr>
				</form>
				</table>
			';
		} elseif ($action == "success") {
			$name = $_POST['name'];
			$h = substr( md5(microtime()), 1, 18);

			$System = new System();

			$con = $System->conDB("../../config.json");
    		$query = $System->queryDB("INSERT INTO pl_categories(name,h) VALUES ('$name','$h')", $con);
    		echo "<script>location.href='categories.php?action'</script>";
    	} elseif($action == "edit") {

    		$h = $_GET['h'];

    		$System->conDB("../../config.json");
    		$query = $System->queryDB("SELECT * FROM pl_categories WHERE h='$h'", $con);
    		$row = $System->fetch_array($query);

    		$cat_name = $row['name'];

			echo '
			<form method="post" action="categories.php?action=update&h='.$h.'">
				<label for="name">'.$lang["name"].': </label><input type="text" name="name" value="'.$cat_name.'"><br>
				<input type="submit" value="'.$lang["save"].'">
			</form>
			';
		} elseif($action == "update") {

			$cat_name = $_POST['name'];

			$h = $_GET['h'];

			$con = $System->conDB("../../config.json");

			$query = $System->queryDB("UPDATE pl_categories SET name='$cat_name' WHERE h='$h'", $con);

			echo "<script>location.href='categories.php?action'</script>";

		} elseif($action == "delete") {

			$h = $_GET['h'];

			$con = $System->conDB("../../config.json");

			$query = $System->queryDB("DELETE FROM pl_categories WHERE h='$h'", $con);

			echo "<script>location.href='categories.php?action'</script>";

		} else {

			echo '
			<div class="admin_header">

				<div class="admin_hmenu">
					<a href="groups.php?action"><img src="../../src/ico/back.svg" alt="Atrás" class="btn_back"></a>				
					<h2><a href="index.php">Admin</a> >> <a href="groups.php?action">'.$lang["groups"].'</a> >> <a href="categories.php?action">'.$lang["categories"].'</a></h2>
                </div>

				<div class="submenu">
					<ul>
                    	<a href="categories.php?action=new"><li><img src="../../src/ico/add.png">'.$lang["new"].'</li></a>
                	</ul>
             	</div>
             </div>
				';

			echo '
				<table class="ui_table">
					<thead>
						<th>#</th>
						<th>'.$lang["name"].'</th>
						<th>'.$lang["actions"].'</th>
					</thead>
					<tbody>
		';
				$query = $con->query("SELECT * FROM pl_categories");

				while($row = $System->fetch_array($query)) {

					$category_h = $row['h'];

					$query2 = $System->queryDB("SELECT * FROM pl_groupuser WHERE groupid=$category_h", $con);

					echo "
					<tr>
						<td>".$row['id']."</td>
						<td>".$row['name']."</td>
						<td><a href='categories.php?action=edit&h=".$row['h']."'>".$lang['edit']."</a> <a href='categories.php?action=delete&h=".$row['h']."'>".$lang['delete']."</a></td>
					";
				}
			echo "
		</tbody>
	</table>";
		}
	?>
	
	<?php $System->set_footer(); ?>
	
</body>
</html>
