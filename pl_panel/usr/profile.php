<?php
	include('../../core.php');

	$get_usr = $_GET['h'];

	$System = new System();
	$System->check_usr();
	
	@session_start();

	$con = $System->conDB();
	$User = $System->get_user_by_h($_SESSION['h'], $con);

	$lang = $System->parse_lang();

	$query = $System->queryDB("SELECT * FROM pl_users WHERE h='$get_usr'", $con);
	$row = $System->fetch_array($query);
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
		$System->set_header($User->h,$lang);
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
				echo "<br><a href='editprofile.php?action'>".$lang['edit_my_info']."</a>";
			}?></td>

		</tr>

	</table>


	<?php
		$query = $System->queryDB("SELECT * FROM pl_settings WHERE property='showgroups'", $con);
		$row = $System->fetch_array($query);
		$show_groups = $row['value'];
		if ($show_groups <= 2) {
			if($show_groups == 1 OR $User->privilege >= 2) {
				echo '
					<table>
						<thead>
							<th>'.$lang["your_groups"].'</th>
						</thead>
						<tbody>
				';

				$userid = $User->id;
				$query = $System->queryDB("SELECT * FROM pl_groupuser WHERE user_h='$get_usr'", $con);
				while ($row = $System->fetch_array($query)) {
					$group_h = $row['group_h'];
					$query2 = $System->queryDB("SELECT * FROM pl_groups WHERE h='$group_h'", $con);
					$row2 = $System->fetch_array($query2);
					$groupname = $row2['name'];

					echo '<tr><td><a href="group.php?h='.$group_h.'&page=index">'.$groupname.'</a></td></tr>';
				}
				
				echo '</tbody></table>';
			}
			
		}
	?>
</body>
</html>
