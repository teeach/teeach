<?php
	include('../../core.php');
	//~ include('../../usr.php');

	$get_usr = $_GET['h'];

	$filejson = "../../config.json";

	$System = new System();
	session_start();
	$con = $System->conDB($filejson);

	$User = $System->get_user_by_id($_SESSION['h'], $con);

	$query = $con->query("select * from pl_users where h='$get_usr'");
	$row = mysqli_fetch_array($query);
	$profile_name = $row['name'];
	$profile_subname1 = $row['subname1'];
	$profile_subname2 = $row['subname2'];
	$profile_email = $row['email'];
	$profile_photo = $row['photo'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title><?php echo "$User->name | Project Learn"; ?></title>
	<link rel="stylesheet" href="../../src/css/main.css">
	<?php		
		$System->set_head();
	?>
</head>
<body>
	<?php $System->set_header(); ?>
	<?php $System->set_usr_menu($User->h, $User->privilege); ?>

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
			<td><?php echo "<h1>$profile_name</h1>";
			echo "$profile_subname1, $profile_subname2"; 
			if ($User->h==$get_usr) {
				echo "<br><a href='editprofile.php?action'>Editar mi información</a>";
			}?></td>

		</tr>

	</table>

	<?php $System->set_footer(); ?>
</body>
</html>