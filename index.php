<?php
	session_start();
	if(isset($_SESSION['h'])) {
		header('Location: pl_panel/usr');
	} else {
		if(file_exists("config.json")) {
			header('Location: pl_panel/usr/login.php');
		} else {
			header('Location: install.php?step=1');
		}		
	}
?>