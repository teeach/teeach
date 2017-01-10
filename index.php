<?php
	session_start();
	include("core.php");
	$System = new System();

	if(isset($_SESSION['h'])) {
		header('Location: pl_panel/usr');
	} else {
		if(file_exists("config.json")) {
			
			$fp = fopen("config.json", "r");
			$rfile = fread($fp, filesize("config.json"));
			$json = json_decode($rfile);

			$dbserver = $json->{"dbserver"};
			$dbuser = $json->{"dbuser"};
			$dbpass = $json->{"dbpass"};
			$database = $json->{"database"};
            $type = $json->{"type"};

            if($type == "mysql") {
                $con = mysqli_connect($dbserver, $dbuser, $dbpass, $database)or die("Error: Teeach cannot connect to the database."); //~ MySQL
            } elseif($type == "postgresql") {
                $con = pg_connect("host= ".$dbserver." dbname=".$database." user=".$dbuser." password=".$dbpass."")or die("Error: Teeach cannot connect to the database."); //~ PostgreSQL
            } else {
                die("Error: Teeach cannot find database type.");
            }
			$query = $System->queryDB("SELECT * FROM pl_settings WHERE property='index_page'", $con);
			$row = $System->fetch_array($query);
			$index_page = $row['value'];

			if($index_page == 0) {
				header('Location: pl_panel/usr/index.php');
			} else {
				header('Location: pl_panel/usr/login.php');
			}
			
		} else {
			header('Location: install.php?step=1');
		}		
	}
?>