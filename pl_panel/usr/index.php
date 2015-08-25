<?php
	include("../../core.php");

	session_start();

	$System = new System();
	$System->check_usr();

	$con = $System->conDB("../../config.json");
	$User = $System->get_user_by_id($_SESSION['h'], $con);
	
	$lang = $System->parse_lang("../../src/lang/".$System->load_locale().".json");
    
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
			<section id='posts'>
				<h1>".$lang['posts']."</h1><br><br>
			";
				
		$page = (int) (!isset($_GET["p"]) ? 1 : $_GET["p"]);
		
		$Pagination = new Pagination(5);
		$Pagination->prepaginate($page);		
		$query = $con->query("SELECT * FROM pl_posts ORDER BY id DESC LIMIT ".$Pagination->startpoint.", ".$Pagination->limit."");
		
		
		while($row = mysqli_fetch_array($query)) {

			$title = $row['title'];
			$body = $row['body'];
			$h = $row['h'];
			$author_h = $row['author'];

			$query2 = $con->query("SELECT * FROM pl_users WHERE h='$author_h'")or die("Query error!");
			$row2 = mysqli_fetch_array($query2);

			$author = $row2['name']." ".$row2['surname'];

			echo "
				<article>
					<h2>".$title."</h2>
					<h5>Written by ".$author."</h5>
					".$body."
				</article>
				<br><br>
			";

		}

		echo "
			</section>
			</div>
		";
		
		$items = $con->query("SELECT * FROM pl_posts")->num_rows;
		$Pagination->paginate($items);
				
	?>
	<div class="ui_width_sidebar right">
	<section id="index_groups">
		<div class="sectiontitle">
			
			<?php echo $lang["groups"]; ?>
		</div>
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

		<?php
			if($User->privilege >= 3) {
				echo '<a href="group.php?action=create"><button>'.$lang["create"].'</button></a>';
			}
		
		echo '
			<a href="group.php?action=join"><button>'.$lang["join"].'</button></a>
		';
		?>
	</section>
	</div>
	</div>
	<?php echo $System->set_footer(); ?>
</body>
</html>
