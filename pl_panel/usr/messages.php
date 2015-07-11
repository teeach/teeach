<?php

	session_start();

	include("../../core.php");

	$System = new System();
	$System->check_usr();
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title><?php echo _("Messages");?> | Teeach </title>
	<link rel="stylesheet" href="../../src/css/main.css">
	<script src="../../ckeditor/ckeditor.js"></script>
	<?php
		$System = new System();
		$con = $System->conDB("../../config.json");
		$User = $System->get_user_by_id($_SESSION['h'], $con);
		$System->set_head();
	?>

<style>
    
</style>

</head>
<body>
	<?php
		$query = $con->query("SELECT * FROM pl_settings WHERE property='centername'");
		$row = mysqli_fetch_array($query);
		$centername = $row['value'];
		$System->set_header($centername);
		$System->set_usr_menu($User->h,$User->privilege);

		if (@$_GET['action'] == "new") {

			if (isset($_GET['to'])) {

				$to_h = $_GET['to'];
				$query = $con->query("SELECT * FROM pl_users WHERE h='$to_h'")or die("Query error!");
				$row = mysqli_fetch_array($query);
				$username = $row['username'];

				echo '
				<div class="ui_full_width">
					<div class="ui_head ui_head_width_actions">
						<h2><i class="fa fa-envelope"></i> '._("New Message").'</h2>
					</div>
					<table>
						<form action="messages.php?action=send" method="POST">
							<tr><td><label for="to">'._("To: (username)").'</label></td><td><input type="text" name="to" value="'.$username.'"></td></tr>
							<tr><td><label for="subject">'._("Subject: ").'</label></td><td><input type="text" name="subject"></td></tr>
							<tr><td></td><td><textarea cols="80" id="editor1" name="body" rows="10"></textarea></td></tr>
							<tr><td></td><td><input type="submit" value="Enviar"></td></tr>
						</form>
					</table>
				</div>
				';
			} else {

				echo '
				<div class="ui_full_width">
					<div class="ui_head ui_head_width_actions">
						<h2><i class="fa fa-envelope"></i> '._("New Message").'</h2>
					</div>
					<table>
						<form action="messages.php?action=send" method="POST">
							<tr><td><label for="to">'._("To: (username)").'</label></td><td><input type="text" name="to"></td></tr>
							<tr><td><label for="subject">'._("Subject: ").'</label></td><td><input type="text" name="subject"></td></tr>
							<tr><td></td><td><textarea cols="80" id="editor1" name="body" rows="10"></textarea></td></tr>
							<tr><td></td><td><input type="submit" value="Enviar"></td></tr>
						</form>
					</table>
				</div>
				';
			}
				echo '
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

		} elseif(@$_GET['action'] == "send") {

			$to_username = $_POST['to'];

			$query = $con->query("SELECT * FROM pl_users WHERE username='$to_username'");
			$row = mysqli_fetch_array($query);

			$to = $row['h'];

			$subject = $_POST['subject'];
			$body = $_POST['body'];
			$from = $User->h;
			$h = substr( md5(microtime()), 1, 18);

			$date = date("Y-m-d H:i:s");

			$query2 = $con->query("INSERT INTO pl_messages(from_h,to_h,subject,body,h,date) VALUES('$from','$to','$subject','$body','$h','$date')")or die("Query error!");

			echo '<a href="messages.php">Aceptar</a>';

		} elseif(@$_GET['action'] == "sent") {

			echo '
			<div class="ui_full_width">
				<div class="ui_head ui_head_width_actions">
				<h2><i class="fa fa-envelope"></i> '._("Messages").'</h2>
                
                	<div class="ui_actions">
                    	<a href="messages.php?action=new"><button class="ui_action" class="ui_tooltip" title="Add New"><i class="fa fa-plus"></i></button></a>
                	</div>
            	</div>

            	<div class="ui_sidebar left">

                <nav class="ui_vertical_nav">
                    <ul>
                        <li><a href="messages.php">'._("Received").'</a></li>
                        <li class="active"><a href="messages.php?action=sent">'._("Sent").'</a></li>
                    </ul>
                </nav>                            
            	</div>

            	<div class="ui_width_sidebar right">
			';

				$query = $con->query("SELECT * FROM pl_messages WHERE from_h='$User->h' ORDER BY id DESC")or die("Query error!");

				while ($row = mysqli_fetch_array($query)) {

					$to_h = $row['to_h'];
					$subject = $row['subject'];
					$date = $row['date'];
					$body = $row['body'];
					$h = $row['h'];

					$query1 = $con->query("SELECT * FROM pl_users WHERE h='$to_h'")or die("Query error!");
					$row1 = mysqli_fetch_array($query1);

					$to_name = $row1['name'];
					$to_surname = $row1['surname'];

					echo'<div id="'.$h.'" class="'.$h.' message">'.$to_name.' '.$to_surname.' <div style="width:250px; display:inline-block; padding-left:10px"><a href="messages.php?action=view&h='.$h.'">'.$subject.'</a></div> '.date("d-m-Y H:i", strtotime($date)).' <div class="actions" style="float:right"><i id="'.$h.'" class="fa fa-share-square-o action answer"></i> <i id="'.$h.'" class="fa fa-trash-o action delete"></i></div></div>';
				}
                
                echo'
                    </div>
                </div>
                ';

		} elseif(@$_GET['action'] == "view") {

			$h = $_GET['h'];

			$query = $con->query("SELECT * FROM pl_messages WHERE h='$h'")or die("Query error!");
			$row = mysqli_fetch_array($query);

			$subject = $row['subject'];
			$body = $row['body'];

			echo '
				<h1>'.$subject.'</h1>
				<p>'.$body.'</p>
			';

		} else {
			echo '
				<div class="ui_full_width">
					<div class="ui_head ui_head_width_actions">
						<h2><i class="fa fa-envelope"></i> '._("Messages").'</h2>
                
                		<div class="ui_actions">
                   			<a href="messages.php?action=new"><button class="ui_action" class="ui_tooltip" title="Add New"><i class="fa fa-plus"></i></button></a>
                		</div>
            		</div>

            		<div class="ui_sidebar left">

                	<nav class="ui_vertical_nav">
                    	<ul>
                        	<li class="active"><a href="messages.php">'._("Received").'</a></li>
                        	<li><a href="messages.php?action=sent">'._("Sent").'</a></li>
                    	</ul>
                	</nav>                            
            		</div>

            		<div class="ui_width_sidebar right">
			';

				$query = $con->query("SELECT * FROM pl_messages WHERE to_h='$User->h' ORDER BY id DESC")or die("Query error!");

				while ($row = mysqli_fetch_array($query)) {
					$from_h = $row['from_h'];
					$query_from_h = $con->query("SELECT * FROM pl_users WHERE id='$from_h'")or die("Query error!");
					$row_from_h = mysqli_fetch_array($query_from_h);
					$from_h = $row_from_h['h'];
					$subject = $row['subject'];
					$date = $row['date'];

					$from = $System->get_user_by_id($from_h, $con);

					$h = $row['h'];
                    
					echo '<div id="'.$h.'" class="'.$h.' message">'.$from->name.' '.$from->surname.' <div style="width:250px; display:inline-block; padding-left:10px"><a href="messages.php?action=view&h='.$h.'">'.$subject.'</a></div> '.date("d-m-Y H:i", strtotime($date)).' <div class="actions" style="float:right"><i id="'.$h.'" class="fa fa-share-square-o action answer"></i> <i id="'.$h.'" class="fa fa-trash-o action delete"></i></div></div>';
				}
            echo '
                </div>
            </div>
            ';


		}
	?>
<script>
    $(".delete").click(function() {
        var $msg = $(this);
        var posting = $.post( "delmsg.php", {h:$(this).attr("id")});
          // Put the results in a div
          posting.done(function( data ) {
            //~ alert($("."+$(this).attr("id")).text());
            $("div."+$msg.attr("id")).toggle("slide");
            //~ var content = $( data ).find( "#content" );
            //~ $( "#result" ).empty().append( content );
          });
    });

</script>
</body>
</html>