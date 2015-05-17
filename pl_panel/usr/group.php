<?php
	session_start();
	include('../../core.php');
	$System = new System();
	$con = $System->conDB("../../config.json");
	$User = $System->get_user_by_id($_SESSION['h'], $con);

	@$h = $_GET['h'];

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

		if (@$_GET['action'] == "join") {
			echo '
				<h1>'._("Join a group").'</h1>
				<p>Select a group: </p>
				<ul class="grouplist">
					';

				$query = $con->query("SELECT * FROM pl_groups")or die("Query error!");
				while($row = mysqli_fetch_array($query)) {
					$groupname = $row['name'];
					$gh = $row['h'];
					echo '<a href="group.php?action=send_request&group='.$gh.'"><li>'.$groupname.'</li></a>';
				}

			echo '
				</ul>
			';

		} elseif (@$_GET['action'] == "send_request") {

			$gh = $_GET['group'];

			$query = $con->query("SELECT * FROM pl_groups WHERE h='$gh'")or die("Query error!");
			$row = mysqli_fetch_array($query);
			$groupname = $row['name'];
			$gid = $row['id'];

			$query2 = $con->query("SELECT * FROM pl_groupuser WHERE groupid=$gid");
			while ($row2 = mysqli_fetch_array($query2)) {
				$uid = $row2['userid'];
				$query3 = $con->query("SELECT * FROM pl_users WHERE id=$uid");
				$row3 = mysqli_fetch_array($query3);
				$privilege = $row3['privilege'];
				if ($privilege >= 2) {

					//User info.
					$name = $User->name;
					$surname = $User->surname1;

					//Receiver.
					$to_id = $uid;					
					$subject = $name." ".$surname." wants to join ".$groupname;
					$body = "group.php?action=add&group=".$gh."&user=".$User->h." ";
					$h = substr( md5(microtime()), 1, 18);
					$date = date("Y-m-d H:i:s");

					$query4 = $con->query("INSERT INTO pl_messages(to_id,subject,body,h,date) VALUES($to_id,'$subject','$body','$h','$date')")or die("Query 4 error!");
				}

				
			}

			echo '<a href="index.php">Great! Return to Index Page</a>';

		} elseif(@$_GET['action'] == "add") {

			$group_h = $_GET['group'];
			$user_h = $_GET['user'];

			//ID Group
			$querygroup = $con->query("SELECT * FROM pl_groups WHERE h='$group_h'")or die("Query error!");
			$rowgroup = mysqli_fetch_array($querygroup);
			$group_id = $rowgroup['id'];

			//ID User
			$queryuser = $con->query("SELECT * FROM pl_users WHERE h='$user_h'")or die("Query error!");
			$rowuser = mysqli_fetch_array($queryuser);
			$user_id = $rowuser['id'];

			//Add
			$queryadd = $con->query("INSERT INTO pl_groupuser(groupid,userid) VALUES($group_id,$user_id)")or die("Query error!");

			echo '<a href="group.php?h='.$group_h.'">Accept</a>';

		} else {
			echo '
				<aside>
					<h3>'.$group_name.'</h3>
					<ul>
						<li><a href="#">'._("Users").'</a></li>
					</ul>
				</aside>

				<h1>'._("Users").'</h1>
				<table>
					<thead>
						<th>Users</th>
					</thead>
					<tbody>
			';
			$query = $con->query("SELECT * FROM pl_groupuser WHERE groupid=$groupid");
					while ($row = mysqli_fetch_array($query)) {
						$userid = $row['userid'];
						$query2 = $con->query("SELECT * FROM pl_users WHERE id=$userid");
						$row2 = mysqli_fetch_array($query2);

						$name = $row2['name'];
						$surname1 = $row2['subname1'];
						$surname2 = $row2['subname2'];
						$user_h = $row2['h'];
					}

			echo '<tr><td><a href="profile.php?h='.$user_h.'">'.$name." ".$surname1." ".$surname2.'</a></td></tr>
			</tbody>
			</table>';
		}
		
	?>		

	<?php $System->set_footer(); ?>
</body>
</html>