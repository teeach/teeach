<?php
	include("../../core.php");

	session_start();

	$System = new System();
	$System->check_usr();

	$con = $System->conDB("../../config.json");
	$User = $System->get_user_by_id($_SESSION['h'], $con);
	
	$lang = $System->parse_lang("../../src/lang/".$System->load_locale().".json");

	if(@$_GET['action'] == "close") {
		$query = $con->query("UPDATE pl_users SET tour=1 WHERE h='$User->h'")or die("Query error!");
		header('Location: index.php');
	}
    
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title><?php echo "Hi, $User->name | Teeach"; ?></title>
	<link rel="stylesheet" href="../../src/css/index.css">
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
	$System->set_usr_menu($User->h,$User->privilege,$lang);

		echo "
			<div class='index_page'>
				<div class='index_posts'>
					<h1>".$lang['posts']."</h1>
			";
				
		$page = (int) (!isset($_GET["p"]) ? 1 : $_GET["p"]);
		
		$Pagination = new Pagination(5);
		$Pagination->prepaginate($page);		
		$query = $con->query("SELECT * FROM pl_posts ORDER BY id DESC LIMIT ".$Pagination->startpoint.", ".$Pagination->limit."");
		
		
		while ($row = mysqli_fetch_array($query)) {

			$title = $row['title'];
			$body = $row['body'];
			$h = $row['h'];
			$author_h = $row['author'];

			$query_setting_show_author = $con->query("SELECT * FROM pl_settings WHERE property='show_post_author'")or die("Query error!");
			$row_setting_show_author = mysqli_fetch_array($query_setting_show_author);

			echo '
				<div class="post">
					<h2>'.$title.'</h2>';

			if ($row_setting_show_author['value'] == "true") {
				
				if($author_h == "teeach") {
					echo '<h5>'.$lang["writed_by"].' <a target="_blank" href="http://teeach.org">Teeach</a></h5>';
				} else {
					$query2 = $con->query("SELECT * FROM pl_users WHERE h='$author_h'")or die("Query error!");
					$row2 = mysqli_fetch_array($query2);

					$author = $row2['name']." ".$row2['surname'];

					echo '<h5>'.$lang["writed_by"].' <a target="_blank" href="profile.php?h='.$author_h.'">'.$author.'</a></h5>';
				}				
			}

			echo $body.'</div>';

		}		
		
		$items = $con->query("SELECT * FROM pl_posts")->num_rows;
		$Pagination->paginate($items);

		echo "
			</div>
		";
				
	?>
	<div class="index_right">
		<section class="index_groups">
			<div class="sectiontitle">
				<i class="fa fa-users"></i>
				<?php echo $lang["groups"]; ?>
			</div>
			<div class="sectionbody">
				<ul>
					<?php
						$userid = $User->id;
						$query = $con->query("SELECT * FROM pl_groupuser WHERE user_h='$User->h'")or die("Query 1 Error!");
						while ($row = mysqli_fetch_array($query)) {
							$group_h = $row['group_h'];
							$status = $row['status'];					
							if($status != "waiting") {
								$query2 = $con->query("SELECT * FROM pl_groups WHERE h='$group_h'")or die("Query 2 Error!");
								$row2 = mysqli_fetch_array($query2);
								$groupname = $row2['name'];
								//~ $grouph = $row2['h'];
								echo '<li><a href="group.php?h='.$group_h.'&page=index">'.$groupname.'</a></li>';
							}					
						}
					?>
				</ul>
			</div>

			<?php
				if($User->privilege >= 3) {
					echo '<a href="group.php?action=create"><button>'.$lang["create"].'</button></a>';
				}

				$query = $con->query("SELECT * FROM pl_settings WHERE property='JP'")or die("Query error!");
				$row = mysqli_fetch_array($query);
				$JP = $row['value'];

				if($JP != "3") {
					echo '<a href="group.php?action=join"><button>'.$lang["join"].'</button></a>';
				}		
			
			?>
		</section>
	</div>
	<!--<?php echo $System->set_footer(); ?>-->
	<?php
		$query = $con->query("SELECT * FROM pl_users WHERE h='$User->h'")or die("Query error!");
		$row = mysqli_fetch_array($query);
		if($row['tour'] == 0) {
			echo '
			<div class="horizontal_box">
				<h1>'.$lang["welcome_teeach"].'</h1>
				<h3>'.$lang["help_you"].'</h3>
				<table>
					<tr>
						<td>
							<a href="editprofile.php" target="_blank">
								<i class="fa fa-user" style="background-color: #FF4646"></i><br>
								<b>'.$lang["complete_profile"].'</b>
							</a>
						</td>
						<td>
							<a href="#" target="_blank">
								<i class="fa fa-lightbulb-o" style="background-color: #4687FF"></i><br>
								<b>'.$lang["where_begin"].'</b>
							</a>
						</td>
						<td>
							<a href="#" target="_blank">
								<i class="fa fa-book" style="background-color: #5BD361"></i><br>
								<b>'.$lang["learn_more"].'</b>
							</a>
						</td>
					</tr>
				</table>
				<br>
				<a href="index.php?action=close">'.$lang["close"].'</a>
			</div>
			';
		}		
	?>
</body>
</html>
