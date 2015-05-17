<?php
 
    //connection information
    $param = $_GET["term"];
     
    $fp = fopen("config.json", "r");
	$file = fread($fp, filesize("config.json"));

	$json = json_decode($file);

	$dbserver = $json->{'dbserver'};
	$dbuser = $json->{'dbuser'};
	$dbpass = $json->{'dbpass'};
	$database = $json->{'database'};	

	$con = mysqli_connect($dbserver, $dbuser, $dbpass, $database);
	$query = $con->query("select * from pl_users where username like '%".$param."%'");
    
    $friends = [];
    
    //build array of results
    while($row = mysqli_fetch_array($query)){
        $friends[] = array("id" => $row["id"], "value" => $row["username"], "icon" => $row["photo"]);
    }
     
    //echo JSON to page
    $response = json_encode($friends);
    echo $response;
     
?>
