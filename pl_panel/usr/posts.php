<?php
	session_start();

	include("../../core.php");

	$System = new System();
	$System->check_usr();
	$con = $System->conDB();
	$User = $System->get_user_by_h($_SESSION['h'], $con);
	$lang = $System->parse_lang();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php $System->set_head(); ?>
	<title><?php echo $lang["posts"]; ?> | Teeach</title>
	<link rel="stylesheet" href="../../src/css/posts.css">
</head>
<body>
	<?php
		if(@$_GET['action'] == "comment") {

			$h = $System->rand_str(10);
			$comment = $_POST['comment'];
			$post_h = $_GET['h'];
			$user_h = $_SESSION['h'];

			$query = $System->queryDB("INSERT INTO pl_comments(h, comment, post_h, user_h) VALUES('$h','$comment','$post_h','$user_h')", $con);

			echo '<script>location.href="posts.php?h='.$post_h.'"</script>';

		} else {

			$System->set_header($User->h,$lang);

			$query_setting_show_author = $System->queryDB("SELECT * FROM pl_settings WHERE property='show_post_author'", $con);
			$row_setting_show_author = $System->fetch_array($query_setting_show_author);
			$query_setting_show_date = $System->queryDB("SELECT * FROM pl_settings WHERE property='show_post_date'", $con);
			$row_setting_show_date = $System->fetch_array($query_setting_show_date);

			$post_h = $_GET['h'];

			$query = $System->queryDB("SELECT * FROM pl_posts WHERE h='$post_h'", $con);
			$row = $System->fetch_array($query);
			$author_h = $row['author'];

			echo '
				<div class="ui_full_width">
					<h1>'.$row["title"].'</h1>
					<p>
			';

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
				echo $creation_date_format = $System->get_date_format($row["creation_date"], $lang, $con);
			}

			if($User->photo == "") {
				$profile_photo = "../../src/ico/user.png";
			} else {
				$profile_photo = $User->photo;
			}

			echo '</p>'.$row["body"];

			echo '
				<div class="new_comment">
					<form method="POST" action="posts.php?action=comment&h='.$_GET["h"].'">
						<table>
							<tr>
								<td><img src="'.$profile_photo.'"></td>
								<td><textarea rows="3" cols="50" name="comment"></textarea></td>
								<td><input type="submit" value="Enviar"></td>
							</tr>
						</table>
					</form>
				</div>
			';

			$query = $System->queryDB("SELECT * FROM pl_comments WHERE post_h='$post_h'", $con);
			while ($row = $System->fetch_array($query)) {
				$user_h = $row['user_h'];
				$query_usr = $System->queryDB("SELECT * FROM pl_users WHERE h='$user_h'", $con);
				$row_usr = $System->fetch_array($query_usr);
				if ($row_usr["photo"] == "") {
					$profile_photo = "../../src/ico/user.png";
				} else {
					$profile_photo = $row_usr["photo"];
				}
				echo '
					<div class="comment">
						<table>
							<tr>
								<td><img src="'.$profile_photo.'"></td>
								<td>
									<a href="profile.php?h='.$row_usr["h"].'" target="_blank"><b>'.$row_usr["name"].' '.$row_usr["surname"].'</b></a><br>
									<p>'.$row["comment"].'</p>
								</td>
							</tr>
						</table>
					</div>
				';
			}

			echo '</div>';

		}
		
	?>
	
</body>
</html>