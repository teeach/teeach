<?php

	include("../../core.php");

	$System = new System();
    $System->check_admin();

    $lang = $System->parse_lang("../../src/lang/".$System->load_locale().".json");
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title><?php echo $lang["posts"]; ?> | Teeach</title>
	<link rel="stylesheet" href="../../src/css/main.css" />
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'/>
	<script type="text/javascript" src="../../src/ckeditor/ckeditor.js"></script>
</head>
<body>

	<?php
		$action = $_GET['action'];
		if ($action == "new") {
			echo '
				<a href="index.php"><img src="../../src/ico/back.svg" alt="Atrás" class="btn_back"></a>
				<table style="padding: 20px;">
				<form name="cu" method="post" action="posts.php?action=success" autocomplete="off">
					<tr><td><label for="title">'.$lang["title"].': </label></td><td><input type="text" name="title" required/></td></tr>
					<tr><td>'.$lang["body"].'</td><td><textarea id="editor1" name="body" rows="6" cols="50" required></textarea></td></tr>
					<tr><td></td><td><input type="submit" value="Enviar"/></td></tr>
				</form>
				</table>
				
				<script type="text/javascript">
                		CKEDITOR.replace( "editor1", {
                		enterMode: CKEDITOR.ENTER_BR,
                		skin : "office2013",
                		toolbar : [
                    		{ name: "document", groups: [ "mode", "document", "doctools" ], items: [ "Source", "-", "Save", "Preview", "-", "Templates" ] },
                    		{ name: "clipboard", groups: ["undo"], items: ["Undo", "Redo" ] },
                    		{ name: "editing", groups: [ "find", "selection"], items: ["Replace", "-", "SelectAll"] },
                    		"/",
                    		{ name: "basicstyles", groups: [ "basicstyles", "cleanup" ], items: [ "Bold", "Italic", "Underline", "Strike", "Subscript", "Superscript", "-", "RemoveFormat" ] },
                    		{ name: "paragraph", groups: [ "list", "indent", "blocks", "align"], items: [ "NumberedList", "BulletedList", "-", "Outdent", "Indent", "-", "Blockquote", "CreateDiv", "-", "JustifyLeft", "JustifyCenter", "JustifyRight", "JustifyBlock" ] },
                    		"/",
                    		{ name: "links", items: [ "Link", "Unlink" ] },
                    		{ name: "insert", items: [ "Image", "Flash", "Table", "HorizontalRule", "Smiley", "SpecialChar", "Iframe" ] },
                    		"/",
                    		{ name: "styles", items: [ "Styles", "Format", "Font", "FontSize" ] },
                    		{ name: "colors", items: [ "TextColor", "BGColor" ] },
                    		{ name: "tools", items: [ "Maximize"] }
                		]
                	});      
        			</script>
			';
		} elseif ($action == "success") {
			
			$title = $_POST['title'];
			$body = $_POST['body'];
    		$h = substr( md5(microtime()), 1, 18);
    		session_start();
    		$author = $_SESSION['h'];

    		$con = $System->conDB("../../config.json");
    		$query = $con->query("INSERT INTO pl_posts(title,body,h,author) VALUES('$title','$body','$h','$author')")or die("Query Error!");
    		echo "<script>location.href='posts.php?action'</script>";

    	} elseif($action == "edit") {

    		$h = $_GET['h'];

    		$con = $System->conDB("../../config.json");
    		$query = $con->query("SELECT * FROM pl_posts WHERE h='$h'")or die("Query error!");
    		$row = mysqli_fetch_array($query);

    		$title = $row['title'];
    		$body = $row['body'];

    		echo '
				<a href="index.php"><img src="../../src/ico/back.svg" alt="Atrás" class="btn_back"></a>
				<table style="padding: 20px;">
				<form name="cu" method="post" action="posts.php?action=update&h='.$h.'" autocomplete="off">
					<tr><td><label for="title">'.$lang["title"].': </label></td><td><input type="text" name="title" value="'.$title.'" required/></td></tr>
					<tr><td>'.$lang["body"].': </td><td><textarea name="body" id="editor1" rows="6" cols="50" required>'.$body.'</textarea></td></tr>
					<tr><td></td><td><input type="submit" value="Guardar"/></td></tr>
				</form>
				</table>
				
				 <script type="text/javascript">  
                		CKEDITOR.replace( "editor1", { 
                		enterMode: CKEDITOR.ENTER_BR,
                		skin : "office2013",
                		toolbar : [
                    		{ name: "document", groups: [ "mode", "document", "doctools" ], items: [ "Source", "-", "Save", "Preview", "-", "Templates" ] },
                    		{ name: "clipboard", groups: ["undo"], items: ["Undo", "Redo" ] },
                    		{ name: "editing", groups: [ "find", "selection"], items: ["Replace", "-", "SelectAll"] },
                    		"/",
                    		{ name: "basicstyles", groups: [ "basicstyles", "cleanup" ], items: [ "Bold", "Italic", "Underline", "Strike", "Subscript", "Superscript", "-", "RemoveFormat" ] },
                    		{ name: "paragraph", groups: [ "list", "indent", "blocks", "align"], items: [ "NumberedList", "BulletedList", "-", "Outdent", "Indent", "-", "Blockquote", "CreateDiv", "-", "JustifyLeft", "JustifyCenter", "JustifyRight", "JustifyBlock" ] },
                    		"/",
                    		{ name: "links", items: [ "Link", "Unlink" ] },
                    		{ name: "insert", items: [ "Image", "Flash", "Table", "HorizontalRule", "Smiley", "SpecialChar", "Iframe" ] },
                    		"/",
                    		{ name: "styles", items: [ "Styles", "Format", "Font", "FontSize" ] },
                    		{ name: "colors", items: [ "TextColor", "BGColor" ] },
                    		{ name: "tools", items: [ "Maximize"] }
                		]
                	});      
        			</script>
    		';

    	} elseif($action == "update") {

    		$h = $_GET['h'];
    		$title = $_POST['title'];
    		$body = $_POST['body'];

    		$System->conDB("../../config.json");
    		$query = $con->query("UPDATE pl_posts SET title='$title',body='$body' WHERE h='$h'")or die("Query error!");

    		echo "<script>location.href='posts.php?action'</script>";

    	} elseif($action == "delete") {

    		$h = $_GET['h'];

    		$con = $System->conDB("../../config.json");
    		$query = $con->query("DELETE FROM pl_posts WHERE h='$h'")or die("Query error!");

    		echo "<script>location.href='posts.php?action'</script>";

		} else {

			echo '
			<div class="admin_header">
				<div class="admin_hmenu">
					<a href="index.php"><img src="../../src/ico/back.svg" alt="Atrás" class="btn_back"></a>				
					<h2><a href="index.php">Admin</a> >> <a href="posts.php?action">'.$lang["posts"].'</a></h2>
                </div>
				<div class="submenu">
					<ul>
                    	<a href="posts.php?action=new"><li><img src="../../src/ico/add.png">'.$lang["new"].'</li></a>
                	</ul>
                </div>
            </div>
			<center>
				<table class="ui_table">
					<thead>
						<th>#</th>
						<th>'.$lang["title"].'</th>
						<th>'.$lang["body"].'</th>
						<th>'.$lang["actions"].'</th>
					</thead>
					<tbody>
		';
				$con = $System->conDB("../../config.json");
				$query = $con->query("SELECT * FROM pl_posts");
				while($row = mysqli_fetch_array($query)) {
					echo "
					<tr>
						<td>".$row['id']."</td>
						<td>".$row['title']."</td>
						<td>".$row['body']."</td>
						<td><a href='posts.php?action=edit&h=".$row['h']."'>Edit</a> <a href='posts.php?action=delete&h=".$row['h']."'>Del</a></td>
					</tr>";
				}
			echo "
		</tbody>
	</table>
	</center>";
		}
	?>
</body>
</html>