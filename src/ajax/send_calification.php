<?php
	include('../../core.php');
	$System = new System();
	$con = $System->conDB("../../config.json");

	$mark = $_POST['mark'];
	$observations = $_POST['observations'];
	$cal_h = $_POST['cal_h'];

	$query = $con->query("UPDATE pl_califications SET mark='$mark', observations='$observations' WHERE h='$cal_h'")or die("Query error!");
?>