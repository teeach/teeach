<?php
	include('../../core.php');

	$get_usr = $_GET['h'];

	$System = new System();
	$System->check_usr();
	
	@session_start();

	$con = $System->conDB("../../config.json");
	$User = $System->get_user_by_id($_SESSION['h'], $con);

	$query = $con->query("SELECT * FROM pl_users WHERE h='$get_usr'")or die("Query error 1!");
	$row = mysqli_fetch_array($query);
	$profile_name = $row['name'];
	$profile_surname = $row['surname'];
	$profile_email = $row['email'];
	$profile_photo = $row['photo'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title><?php echo "$User->name | Teeach"; ?></title>
	<link rel="stylesheet" href="../../src/css/main.css">
	<?php		
		$System->set_head();
	?>
</head>
<body>
	<?php
		$query = $con->query("SELECT * FROM pl_settings WHERE property='centername'");
		$row = mysqli_fetch_array($query);
		$centername = $row['value'];
		$System->set_header($centername);
		$System->set_usr_menu($User->h,$User->privilege);
	?>

	<table class="profile_table">
		<tr>
			<td>
				<?php
					if ($profile_photo != "") {
						echo '<img class="main_profile_photo" src="'.$profile_photo.'" alt="profile_photo">';
					} else {
						echo '<img class="main_profile_photo" src="../../src/ico/user.png" alt="user">';
					}
				?>
			</td>
			<td><?php echo "<h1>".$profile_name."</h1>";
			echo $profile_surname; 
			if ($User->h==$get_usr) {
				echo "<br><a href='editprofile.php?action'>Editar mi información</a>";
			}?></td>

		</tr>

	</table>


	<?php
		$query = $con->query("SELECT * FROM pl_settings WHERE property='showgroups'")or die("Query error 2!");
		$row = mysqli_fetch_array($query);
		$showgroups = $row['value'];
		if ($showgroups == "true") {
			echo '
				<table>
					<thead>
						<th>Mis grupos</th>
					</thead>
					<tbody>
			';

			$userid = $User->id;
				$query = $con->query("SELECT * FROM pl_groupuser WHERE user_h='$get_usr'")or die("Query Error 3!");
				while ($row = mysqli_fetch_array($query)) {
					$group_h = $row['group_h'];
					$query2 = $con->query("SELECT * FROM pl_groups WHERE h='$group_h'")or die("Query Error 4!");
					$row2 = mysqli_fetch_array($query2);
					$groupname = $row2['name'];

					echo '<tr><td><a href="group.php?h='.$group_h.'">'.$groupname.'</a></td></tr>';
				}

				echo '</tbody></table>';
		}
	?>
</body>
</html>
