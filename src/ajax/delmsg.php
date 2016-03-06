<?php

    $h = $_POST["h"];
    include("../../core.php");
    $fp = fopen("../../config.json", "r");
	$file = fread($fp, filesize("../../config.json"));

	$json = json_decode($file);

	$dbserver = $json->{'dbserver'};
	$dbuser = $json->{'dbuser'};
	$dbpass = $json->{'dbpass'};
	$database = $json->{'database'};

	$con = mysqli_connect($dbserver, $dbuser, $dbpass, $database);
	$query = $con->query("DELETE FROM pl_messages WHERE h= '".$h."'")or die("Query error!");
    
    echo 'Done';
    
?>