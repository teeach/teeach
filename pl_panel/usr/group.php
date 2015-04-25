<?php
	session_start();
	include('../../core.php');
	$System = new System();
	$con = $System->conDB("../../config.json");
	$User = $System->get_user_by_id($_SESSION['h'], $con);

	$h = $_GET['h'];

	$query = $con->query("SELECT * FROM pl_groups WHERE h='$h'")or die(_("This group doesn't exist."));
	$row = mysqli_fetch_array($query);

	$group_name = $row['name'];
	$groupid = $row['id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php $System->set_head(); ?>
	<title><?php echo _("Groups"); ?> | Teeach</title>
	<link rel="stylesheet" href="../../src/css/main.css">
	
</head>
<body>
	<?php 
		$System->set_header();
		$System->set_usr_menu($User->h, $User->privilege);
	?>

	<aside>
		<h3><?php echo $group_name; ?></h3>
		<ul>
			<li><a href="#"><?php echo _("Users") ?></a></li>
		</ul>
	</aside>

	<h1><?php echo _("Users"); ?></h1>

	<table>
		<thead>
			<th>Users</th>
		</thead>
		<tbody>
				<?php
					$query = $con->query("SELECT * FROM pl_groupuser WHERE groupid=$groupid");
					while ($row = mysqli_fetch_array($query)) {
						$userid = $row['userid'];
						$query2 = $con->query("SELECT * FROM pl_users WHERE id=$userid");
						$row2 = mysqli_fetch_array($query2);

						$name = $row2['name'];
						$surname1 = $row2['subname1'];
						$surname2 = $row2['subname2'];
						$user_h = $row2['h'];

						echo '<tr><td><a href="profile.php?h='.$user_h.'">'.$name." ".$surname1." ".$surname2.'</a></td></tr>';
					}
				?>
		</tbody>
	</table>

	<?php $System->set_footer(); ?>
</body>
</html>