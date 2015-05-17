<?
    include("core.php");
    $System = new System;
    $sender = $_POST["sender"];
    $users = $_POST["users"];
    $subject = $_POST["subject"];
    $content = $_POST["content"];
    $data = ["users"=>$users, "subject"=>$subject, "content"=>$content];
    echo json_encode($data);
    $fp = fopen("config.json", "r");
	$file = fread($fp, filesize("config.json"));

	$json = json_decode($file);

	$dbserver = $json->{'dbserver'};
	$dbuser = $json->{'dbuser'};
	$dbpass = $json->{'dbpass'};
	$database = $json->{'database'};

	$con = mysqli_connect($dbserver, $dbuser, $dbpass, $database);
    $sender = $System->get_user_by_id($sender, $con);
    foreach ($users as $user) {
        $query = $con->query("INSERT INTO `plearn`.`pl_messages` (`id`, `from_id`, `to_id`, `subject`, `body`, `h`, `date`) VALUES (NULL, '".$sender->id."', '".$user."', '".$subject."', '".$content."', '".$System->rand_string(20)."', '".date("Y-m-d H:i:s")."')");
    }
?>
