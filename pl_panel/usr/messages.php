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
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title><?php echo $lang["messages"];?> | Teeach </title>
	<link rel="stylesheet" href="../../src/css/main.css">
	<link rel="stylesheet" href="../../src/css/messages.css">
	<?php $System->set_head(); ?>
        <style>
            .ui-autocomplete-loading {
            background: white url("images/ui-anim_basic_16x16.gif") right center no-repeat;
            }
        </style>
        <script>
            
        </script>

</head>
<body>
	<?php

		$System->set_header($User->h, $lang);

		echo "<input type='hidden' value='".$User->h."' id='user_h'>";

		if (@$_GET['action'] == "new") {

			if (isset($_GET['to'])) {

				//~ Reply
				$to_h = $_GET['to'];
				$query = $con->query("SELECT * FROM pl_users WHERE h='$to_h'")or die("Query error!");
				$row = mysqli_fetch_array($query);
				$username = $row['username'];

				echo '
				<div class="ui_full_width">
					<div class="ui_head ui_head_width_actions">
						<h2><i class="fa fa-envelope"></i> '.$lang["new_message"].'</h2>
					</div>
					<table>
						<form action="messages.php?action=send" method="POST">
							<tr><td><label for="to">'.$lang["to"].': </label></td><td><input type="text" name="to" value="'.$username.'"></td></tr>
							<tr><td><label for="subject">'.$lang["subject"].': </label></td><td><input type="text" name="subject"></td></tr>
							<tr><td></td><td><textarea cols="80" id="editor1" name="body" rows="10"></textarea></td></tr>
							<tr><td></td><td><input type="submit" value="Enviar"></td></tr>
						</form>
					</table>
				</div>
				';

			} else {

				//~ Multi-send
				echo '
				<div class="ui_full_width">
					<div class="ui_head ui_head_width_actions">
						<h2><i class="fa fa-envelope"></i> '.$lang["new_message"].'</h2>
					</div>
					<div class="ui-widget">
					'.$lang["to"].':
					  <input id="birds" size="50">
					  <div id="users"></div>
					</div>
					<table>
						<form action="messages.php?action=multisend" method="POST" id="addusers">
							<tr><td><label for="subject">'.$lang["subject"].'</label></td><td><input type="text" id="subject" name="subject" value="" size="40"></td></tr>
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

			if ($to == "") {
				die($lang["user_not_exist"]);
			}

			$subject = $_POST['subject'];
			$body = $_POST['body'];
			$from = $User->h;
			$h = $System->rand_str(10);

			$date = date("Y-m-d H:i:s");

			$query2 = $System->queryDB("INSERT INTO pl_messages(from_h,to_h,subject,body,unread,h,date) VALUES('$from','$to','$subject','$body',1,'$h','$date')", $con);

			echo '<script>location.href="messages.php"</script>';

		} elseif(@$_GET['action'] == "multisend") {

			$sender = $_SESSION['h'];
    		$users = $_POST["users"];
    		$subject = $_POST["subject"];
    		$body = $_POST["body"];
    		$data = ["users"=>$users, "subject"=>$subject, "body"=>$body];
    		$h = $System->str_rand(10);
    		$date = date("Y-m-d H:i:s");
    		json_encode($data);

    		$sender = $System->get_user_by_h($sender, $con);
    		foreach ($users as $user) {
        		$query = $con->query("INSERT INTO pl_messages(from_h,to_h,subject,body,h,date) VALUES ('$sender', '$user', '$subject', '$content', '$h', '$date')")or die("Query error!");
    		}

    		echo "ale";

		} elseif(@$_GET['action'] == "sent") {

			echo '
			<div class="ui_full_width">
				<div class="ui_head ui_head_width_actions">
				<h2><i class="fa fa-envelope"></i> '.$lang["messages"].'</h2>
                
                	<div class="ui_actions">
                    	<a href="messages.php?action=new"><button class="ui_action" class="ui_tooltip" title="Add New"><i class="fa fa-plus"></i></button></a>
                	</div>
            	</div>

            	<div class="ui_sidebar left">

                <nav class="ui_vertical_nav">
                    <ul>
                        <li><a href="messages.php">'.$lang["received"].' <span id="num_messages_unread"></span></a></li>
                        <li class="active"><a href="messages.php?action=sent">'.$lang["sent"].'</a></li>
                    </ul>
                </nav>                            
            	</div>

            	<div class="ui_width_sidebar right">
			';

				$query = $con->query("SELECT * FROM pl_messages WHERE from_h='$User->h' ORDER BY id DESC")or die("Query error!");

				while ($row = mysqli_fetch_array($query)) {

					$to_h = $row['to_h'];
					$subject = $row['subject'];
					$date = $System->get_date_format($row['date'], $lang, $con);
					$time = $System->get_time_format($row['date'], $con);
					$body = $row['body'];
					$h = $row['h'];

					$query1 = $con->query("SELECT * FROM pl_users WHERE h='$to_h'")or die("Query error!");
					$row1 = mysqli_fetch_array($query1);

					$to_name = $row1['name'];
					$to_surname = $row1['surname'];

					echo '<div id="'.$h.'" class="'.$h.' message">'.$to_name.' '.$to_surname.' <div class="msg_subject"><a href="messages.php?action=view&h='.$h.'">'.$subject.'</a></div> '.$date.' ~ '.$time.'<div class="msg_actions"><i id="'.$h.'" class="fa fa-share-square-o action msg_answer"></i> <i id="'.$h.'" class="fa fa-trash-o action delete"></i></div></div>';
				}
                
                echo '
                    </div>
                </div>
                ';

		} elseif(@$_GET['action'] == "view") {

			$h = $_GET['h'];

			$Message = $System->get_message_by_h($h, $con);

			//Mark like read
			if ($Message->to_h == $_SESSION['h']) {
				$query = $con->query("UPDATE pl_messages SET unread=0 WHERE h='$Message->h'")or die("Query error!");
			}
			
			echo '
				<div class="ui_full_width">
					<div class="ui_head ui_head_width_actions">
						<h2>'.$Message->subject.'</h2>

						<div class="ui_actions">
							<a href="messages.php?action=new&to='.$Message->to_h.'"><button class="ui_action" class="ui_tooltip" title="Reply"><i class="fa fa-reply"></i> '.$lang["reply"].'</button></a>
							<a href="messages.php?action=delete&h='.$Message->h.'"><button class="ui_action" class="ui_tooltip" title="Delete"><i class="fa fa-trash"></i> '.$lang["delete"].'</button></a>
						</div>
					</div>

					<div class="ui_sidebar left">
                		<nav class="ui_vertical_nav">
                    		<ul>
                        		<li><a href="messages.php">'.$lang["received"].' <span id="num_messages_unread"></span></a></li>
                        		<li><a href="messages.php?action=sent">'.$lang["sent"].'</a></li>
                    		</ul>
                		</nav>
            		</div>

            		<div class="ui_width_sidebar right">
						'.$Message->body.'
            		</div>
											
			';

		} elseif(@$_GET['action'] == "delete") {

			$message_h = $_GET['h'];

			$query = $System->queryDB("DELETE FROM pl_messages WHERE h='$message_h'", $con);

			echo "Message deleted. <a href='messages.php'>Aceptar</a>";

		} else {

			echo '
				<div class="ui_full_width">
					<div class="ui_head ui_head_width_actions">
						<h2><i class="fa fa-envelope"></i> '.$lang["messages"].'</h2>
                
                		<div class="ui_actions">
                   			<a href="messages.php?action=new"><button class="ui_action" class="ui_tooltip" title="Add New"><i class="fa fa-plus"></i></button></a>
                		</div>
            		</div>

            		<div class="ui_sidebar left">

                	<nav class="ui_vertical_nav">
                    	<ul>
                        	<li class="active"><a href="messages.php">'.$lang["received"].' <span id="num_messages_unread"></span></a></li>
                        	<li><a href="messages.php?action=sent">'.$lang["sent"].'</a></li>
                    	</ul>
                	</nav>                            
            		</div>

            		<div class="ui_width_sidebar right">
			';

				$query = $System->queryDB("SELECT * FROM pl_messages WHERE to_h='$User->h' ORDER BY id DESC", $con);

				while ($row = $System->fetch_array($query)) {
					$from_h = $row['from_h'];
					$query_from = $System->queryDB("SELECT * FROM pl_users WHERE h='$from_h'", $con);
					$row_from = $System->fetch_array($query_from);
					$from_h = $row_from['h'];
					$from_name = $row_from['name'];
					$from_surname = $row_from['surname'];
					$subject = $row['subject'];
					$date = $System->get_date_format($row['date'], $lang, $con);
					$time = $System->get_time_format($row['date'], $con);
					$unread = $row['unread'];

					$from = $System->get_user_by_id($from_h, $con);

					$h = $row['h'];

					if($unread == 1) {
						echo '<div id="'.$h.'" class="'.$h.' message">'.$from->name.' '.$from->surname.' <div class="msg_subject" style="font-family:RobotoBold"><a href="messages.php?action=view&h='.$h.'">'.$subject.'</a></div> '.$date.' ~ '.$time.'<div class="msg_actions"><a href="messages.php?action=new&to='.$from_h.'"><i id="'.$h.'" class="fa fa-share-square-o action msg_answer msg_reply"></i></a> <i id="'.$h.'" class="fa fa-trash-o action delete"></i></div></div>';
					} else {
						echo '<div id="'.$h.'" class="'.$h.' message">'.$from->name.' '.$from->surname.' <div class="msg_subject"><a href="messages.php?action=view&h='.$h.'">'.$subject.'</a></div> '.$date.' ~ '.$time.'<div class="msg_actions"><a href="messages.php?action=new&to='.$from_h.'"><i id="'.$h.'" class="fa fa-share-square-o action msg_answer msg_reply"></i></a> <i id="'.$h.'" class="fa fa-trash-o action delete"></i></div></div>';
					}
				}
            echo '
                </div>
            </div>
            ';
		}
	?>
</body>
</html>
