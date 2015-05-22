<?php
	include("../../core.php");

	session_start();

	$System = new System();

	$con = $System->conDB("../../config.json");
	$User = $System->get_user_by_id($_SESSION['h'], $con);
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title><?php echo "Hola, $User->name | Teeach"; ?></title>
	<link rel="stylesheet" href="../../src/css/main.css">
	<?php
		
		$System->set_head();

		
	?>
</head>
<body>
	<?php

	if(!isset($_SESSION['h'])) {
		die("You aren't logged in.");
	}

	$query = $con->query("SELECT * FROM pl_settings WHERE property='centername'");
	$row = mysqli_fetch_array($query);
	$centername = $row['value'];
	$System->set_header($centername);
	$System->set_usr_menu($User->h,$User->privilege);

		echo "
			<section id='posts'>
				<h1>Posts</h1><br><br>
				";

		$query = $con->query("select * from pl_posts ORDER BY id DESC");
		while($row = mysqli_fetch_array($query)) {

			$title = $row['title'];
			$body = $row['body'];
			$h = $row['h'];
			$author_h = $row['author'];

			$query2 = $con->query("select * from pl_users where h='$author_h'")or die("Query error!");
			$row2 = mysqli_fetch_array($query2);

			$author = $row2['name']." ".$row2['subname1'];

			echo "
				<article>
					<h2>".$title."</h2>
					<h5>Writed by ".$author."</h5>
					".$body."
				</article>
				<br><br>
			";

		}

		echo "
			</section>
		";
	?>
	<section id="groups">
		<div class="sectiontitle">
			<?php echo _("Groups"); ?>
		</div>
		<ul>
			<?php
				$userid = $User->id;
				$query = $con->query("SELECT * FROM pl_groupuser WHERE userid=$userid")or die("Query Error!");
				while ($row = mysqli_fetch_array($query)) {
					$groupid = $row['groupid'];
					$query2 = $con->query("SELECT * FROM pl_groups WHERE id=$groupid")or die("Query Error!");
					$row2 = mysqli_fetch_array($query2);
					$groupname = $row2['name'];
					$grouph = $row2['h'];

					echo '<li><a href="group.php?action=view&h='.$grouph.'&page=index">'.$groupname.'</a></li>';
				}
			?>
		</ul>
		<a href="group.php?action=join">Join</a>
	</section>
</body>
</html>
