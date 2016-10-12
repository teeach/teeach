<?php
	session_start();
	include("core.php");
	$System = new System();

	if(isset($_SESSION['h'])) {
		header('Location: pl_panel/usr');
	} else {
		if(file_exists("config.json")) {
			
			$con = $System->conDB("config.json");
			$query = $con->query("SELECT * FROM pl_settings WHERE property='index_page'")or die("Query error!");
			$row = mysqli_fetch_array($query);
			$index_page = $row['value'];

			if($index_page != "1") {
				header('Location: pl_panel/usr/index.php');
			} else {
				header('Location: pl_panel/usr/login.php');
			}
			
		} else {
			header('Location: install.php?step=1');
		}		
	}
?>