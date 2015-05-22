<?php
	include("../../core.php");
	include("../../usr.php");

	$System = new System();
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title><?php echo _("Messages");?> | Teeach </title>
	<link rel="stylesheet" href="../../src/css/main.css">
	<?php
		$System = new System();
		$con = $System->conDB("../../config.json");
		$User = $System->get_user_by_id($_SESSION['h'], $con);
		$System->set_head();
	?>

<style>
    .message{
        float:left;
        width:600px;
        border-radius:5px;
        margin:10px;
        background:#242424;
        color: white;
        padding: 10px;
    }
    .fa{
        cursor:pointer;
    }
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
			echo '
				<table>

					<form action="messages.php?action=send" method="POST">
						<tr><td><label for="to">'._("To: (username)").'</label></td><td><input type="text" name="to"></td></tr>
						<tr><td><label for="subject">'._("Subject: ").'</label></td><td><input type="text" name="subject"></td></tr>
						<tr><td></td><td><textarea name="body" cols="50" rows="8"></textarea></td></tr>
						<tr><td><input type="submit" value="Enviar"></td></tr>
					</form>
			</table>
			';

		} elseif(@$_GET['action'] == "send") {

			$to_username = $_POST['to'];

			$query = $con->query("select * from pl_users where username='$to_username'");
			$row = mysqli_fetch_array($query);

			$to = $row['id'];

			$subject = $_POST['subject'];
			$body = $_POST['body'];
			$from = $User->id;
			$h = substr( md5(microtime()), 1, 18);

			$date = date("Y-m-d H:i:s");

			$query2 = $con->query("INSERT INTO pl_messages(from_id,to_id,subject,body,h,date) VALUES($from,$to,'$subject','$body','$h','$date')")or die("Query error!");

			echo '<a href="messages.php">Aceptar</a>';

		} elseif(@$_GET['action'] == "sent") {
			echo '
				<ul class="submenu">
					<a href="messages.php?action=new"><li>'._("New").'</li></a>
				</ul>
				<aside>
					<h3>'._("Mailbox").'</h3>
					<ul>
						<li><a href="messages.php">'._("Received").'</a></li>
						<li><div class="actual_select"><a href="#">'._("Sent").'</a></div></li>
					</ul>
				</aside>
                <div style = "width:500px; margin-left:270px;">
			';

				$query = $con->query("SELECT * FROM pl_messages WHERE from_id=$User->id ORDER BY id DESC")or die("Query error!");

				while ($row = mysqli_fetch_array($query)) {

					$to_id = $row['to_id'];
					$subject = $row['subject'];
					$date = $row['date'];

					$to = $System->get_user_by_id2($to_id, $con);

					$h = $row['h'];


					echo'<div id="'.$h.'" class="'.$h.' message">'.$to->name.' '.$to->surname1.' <div style="width:250px; display:inline-block; padding-left:10px"><a href="messages.php?action=view&h='.$h.'">'.$subject.'</a></div> '.date("d-m-Y H:i", strtotime($date)).' <div class="actions" style="float:right"><i id="'.$h.'" class="fa fa-share-square-o action answer"></i> <i id="'.$h.'" class="fa fa-trash-o action delete"></i></div></div>';
				}
                
                echo'
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
				<ul class="submenu">
					<a href="messages.php?action=new"><li>'._("New").'</li></a>
				</ul>
				<aside>
					<h3>'._("Mailbox").'</h3>
					<ul>
						<li><div class="actual_select"><a href="#">'._("Received").'</a></div></li>
						<li><a href="messages.php?action=sent">'._("Sent").'</a></li>
					</ul>
				</aside>
                
                <div style = "width:500px; margin-left:270px;">
			';

				$query = $con->query("SELECT * FROM pl_messages WHERE to_id=$User->id ORDER BY id DESC")or die("Query error!");

				while ($row = mysqli_fetch_array($query)) {
					$from_id = $row['from_id'];
					$query_from_h = $con->query("SELECT * FROM pl_users WHERE id=$from_id")or die("Query error!");
					$row_from_h = mysqli_fetch_array($query_from_h);
					$from_h = $row_from_h['h'];
					$subject = $row['subject'];
					$date = $row['date'];

					$from = $System->get_user_by_id($from_h, $con);

					$h = $row['h'];
                    
					echo '<div id="'.$h.'" class="'.$h.' message">'.$from->name.' '.$from->surname1.' <div style="width:250px; display:inline-block; padding-left:10px"><a href="messages.php?action=view&h='.$h.'">'.$subject.'</a></div> '.date("d-m-Y H:i", strtotime($date)).' <div class="actions" style="float:right"><i id="'.$h.'" class="fa fa-share-square-o action answer"></i> <i id="'.$h.'" class="fa fa-trash-o action delete"></i></div></div>';
				}
            echo '
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