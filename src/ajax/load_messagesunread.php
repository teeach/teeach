<?php
	include('../../core.php');
	$System = new System();	
	$con = $System->conDB("../../config.json");

	$lang = $System->parse_lang("../lang/".$System->load_locale().".json");

	$user_h = $_POST['user_h'];

	$query = $con->query("SELECT * FROM pl_messages WHERE to_h='$user_h' AND unread=1")or die("Query error!");
	$num_messagesunread = mysqli_num_rows($query);

	echo $num_messagesunread;
?>