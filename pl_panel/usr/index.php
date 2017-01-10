<?php
	include("../../core.php");

	session_start();

	$System = new System();
	$con = $System->conDB();
	$query = $System->queryDB("SELECT * FROM pl_settings WHERE property='index_page'", $con);
	$row = $System->fetch_array($query);
	$index_page = $row['value'];

	if($index_page == 1) { //~ If index page is "login" ...
		if(!isset($_SESSION['h'])) {
        	header('Location: ../../index.php');
    	}
	}
	
	@$User = $System->get_user_by_h($_SESSION['h'], $con);
	
	$lang = $System->parse_lang();

	if(@$_GET['action'] == "close") {
		$query = $System->queryDB("UPDATE pl_users SET tour=1 WHERE h='$User->h'", $con);
		header('Location: index.php');
	}
    
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title><?php echo $lang['hi'].", ".$User->name." | Teeach"; ?></title>
	<link rel="stylesheet" href="../../src/css/index.css">
	<?php
		$System->set_head();
	?>
</head>
<body>
	<?php

	//if(!isset($_SESSION['h'])) {
	//	die("You aren't logged in.");
	//}

	$System->set_header($User->h, $lang);

		echo "
			<div class='index_page'>
				<div class='index_posts'>
			";
				
		$page = (int) (!isset($_GET["p"]) ? 1 : $_GET["p"]);
		
		$Pagination = new Pagination(5);
		$Pagination->prepaginate($page);		
		$query = $System->queryDB("SELECT * FROM pl_posts ORDER BY id DESC LIMIT ".$Pagination->startpoint.", ".$Pagination->limit."", $con);
		
		
		while ($row = $System->fetch_array($query)) {

			$title = $row['title'];
			$body = $row['body'];
			$h = $row['h'];
			$author_h = $row['author'];
			$creation_date = $row['creation_date'];

			$query_setting_show_author = $System->queryDB("SELECT * FROM pl_settings WHERE property='show_post_author'", $con);
			$row_setting_show_author = $System->fetch_array($query_setting_show_author);
			$query_setting_show_date = $System->queryDB("SELECT * FROM pl_settings WHERE property='show_post_date'", $con);
			$row_setting_show_date = $System->fetch_array($query_setting_show_date);

			echo '
				<div class="post">
					<h1>'.$title.'</h1>
					<p>';

			if ($row_setting_show_author['value'] == "true") {
				
				if($author_h == "teeach") {
					echo $lang["writed_by"].' <a target="_blank" href="http://teeach.org">Teeach</a> ';
				} else {
					$query2 = $System->queryDB("SELECT * FROM pl_users WHERE h='$author_h'", $con);
					$row2 = $System->fetch_array($query2);

					$author = $row2['name']." ".$row2['surname'];

					echo $lang["writed_by"].' <a target="_blank" href="profile.php?h='.$author_h.'">'.$author.'</a> ';
				}

			}

			if($row_setting_show_author['value'] == $row_setting_show_date['value']) {
				echo '~ ';
			}

			if($row_setting_show_date['value'] == "true") {
				echo $creation_date_format = $System->get_date_format($creation_date, $lang, $con);
			}

			echo '</p>'.$body.'<div class="postbox">';

			if($User->privilege >= 3) {
				echo '<a href="../admin/posts.php?action=edit&h='.$h.'">'.$lang["edit"].'</a> ';
			}

			$num_comments = $System->queryDB("SELECT * FROM pl_comments WHERE post_h='$h'", $con)->num_rows;
			echo '<a href="posts.php?h='.$h.'"><i class="fa fa-comment-o" aria-hidden="true"></i>'.$num_comments.'</a></div></div>';

		}		
		
		$items = $System->queryDB("SELECT * FROM pl_posts", $con)->num_rows;
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
						$query = $System->queryDB("SELECT * FROM pl_groupuser WHERE user_h='$User->h'", $con);
						while ($row = $System->fetch_array($query)) {
							$group_h = $row['group_h'];
							$status = $row['status'];					
							if($status != "waiting") {
								$query2 = $System->queryDB("SELECT * FROM pl_groups WHERE h='$group_h'", $con);
								$row2 = $System->fetch_array($query2);
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

				$query = $System->queryDB("SELECT * FROM pl_settings WHERE property='JP'", $con);
				$row = $System->fetch_array($query);
				$JP = $row['value'];

				if($JP != "3") {
					echo '<a href="group.php?action=join"><button>'.$lang["join"].'</button></a>';
				}		
			
			?>
		</section>
	</div>
	<!--<?php echo $System->set_footer(); ?>-->
	<?php
		$query = $System->queryDB("SELECT * FROM pl_users WHERE h='$User->h'", $con);
		$row = $System->fetch_array($query);
		if($row['tour'] == 0 && isset($_SESSION['h'])) {
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
