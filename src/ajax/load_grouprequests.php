<?php
	include('../../core.php');
	$System = new System();	
	$con = $System->conDB("../../config.json");

	$group_h = $_POST['group_h'];

	$query = $con->query("SELECT * FROM pl_groupuser WHERE group_h='$group_h' AND status='waiting'");
	$num_requests = mysqli_num_rows($query);

	echo $num_requests;
?>