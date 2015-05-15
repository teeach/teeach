<?
    include("core.php");
    $System = new System;
    $users = $_POST["users"];
    $subject = $_POST["subject"];
    $content = $_POST["content"];
    $data = ["users"=>$users, "subject"=>$subject, "content"=>$content];
    echo json_encode($data);
    //~ $fp = fopen("config.json", "r");
	//~ $file = fread($fp, filesize("config.json"));
//~ 
	//~ $json = json_decode($file);
//~ 
	//~ $dbserver = $json->{'dbserver'};
	//~ $dbuser = $json->{'dbuser'};
	//~ $dbpass = $json->{'dbpass'};
	//~ $database = $json->{'database'};	
//~ 
	//~ $con = mysqli_connect($dbserver, $dbuser, $dbpass, $database);
    //~ foreach ($users as $user) {
        //~ $query = $con->query("select * from pl_users where username='$usuario'");
    //~ }
?>
