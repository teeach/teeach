<?php

	include("../../core.php");

	$System = new System;
    $con = $System->conDB("../../config.json");
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title><?php echo _("Settings"); ?> | Teeach</title>
	<link rel="stylesheet" href="../../src/css/main.css" />
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'/>
	<?php $System->set_head(); ?>
    <!-- Tabs JS -->
    <script src="../../src/js/tabs.js"></script>
    <!-- Check All JS -->
    <script src="../../src/js/check-all.js"></script>
</head>
<body onload="javascript:cambiarPestanna(pestanas,pestana1);">

	<?php
		$action = $_GET['action'];

		if ($action == "save") {
			
			$centername = $_POST['centername'];
			$logo = $_POST['logo'];
			$accesspass = $_POST['accesspass'];
			@$showgroups = $_POST['showgroups'];
            $JP = $_POST['JP'];

			if(isset($showgroups)) {
				$showgroups = "true";
			} else {
				$showgroups = "false";
			}

			$query = $con->query("UPDATE pl_settings SET value='$centername' WHERE property='centername'")or die("Query error!");
			$query = $con->query("UPDATE pl_settings SET value='$logo' WHERE property='logo'")or die("Query error!");
			$query = $con->query("UPDATE pl_settings SET value='$accesspass' WHERE property='accesspass'")or die("Query error!");
			$query = $con->query("UPDATE pl_settings SET value='$showgroups' WHERE property='showgroups'")or die("Query error!");
            $query = $con->query("UPDATE pl_settings SET value=$JP WHERE property='JP'")or die("Query error!");

			echo '<a href="settings.php?action">Accept</a>';

		} else {
			
			//Queries
			$query_centername = $con->query("SELECT * FROM pl_settings WHERE property='centername'");
			$query_logo = $con->query("SELECT * FROM pl_settings WHERE property='logo'");
			$query_accesspass = $con->query("SELECT * FROM pl_settings WHERE property='accesspass'");
			$query_sg = $con->query("SELECT * FROM pl_settings WHERE property='showgroups'");
            $query_JP = $con->query("SELECT * FROM pl_settings WHERE property='JP'");

			//Arrays
			$row_centername = mysqli_fetch_array($query_centername);
			$row_logo = mysqli_fetch_array($query_logo);
			$row_accesspass = mysqli_fetch_array($query_accesspass);
			$row_sg = mysqli_fetch_array($query_sg);
            $row_JP = mysqli_fetch_array($query_JP);

			//Values
			$centername = $row_centername['value'];
			$logo = $row_logo['value'];
			$accesspass = $row_accesspass['value'];
			$sg = $row_sg['value'];
            $JP = $row_JP['value'];


			echo '
            <div class="admin_header">
                <div class="admin_hmenu">
                    <a href="index.php"><img src="../../src/ico/back.svg" alt="Atrás" class="btn_back"></a><h2><a href="index.php">Admin</a> >> <a href="settings.php?action">'._("Settings").'</a></h2>
			    </div>
            </div>
            	<center>
					<form action="settings.php?action=save" method="post">			
						<div class="contenedor">


						<nav class="ui_tabs">
                            <ul>
                                <li class="active"><a href="#tab_01">'._("Basic").'</a></li>
                                <li><a href="#tab_02">'._("Privacy").'</a></li>
                                <li><a href="#tab_03">'._("Advanced").'</a></li>
                                <li><a href="#tab_04">'._("About").'</a></li>
                            </ul>        
                        </nav>


                        <div class="ui_tabs_content">
                            <form class="ui_form">
                                <div id="tab_01" class="ui_tab_content">
                                    <label for="centername">'._("Centername").': </label><input type="text" name="centername" value="'.$centername.'"><br>
                                    <label for="logo">'._("Logo").': </label><input type="text" name="logo" value="'.$logo.'"><br>
                                    <img src="'.$logo.'" alt="logo"><br>
                                    <label for="accesspass">'._("Accesspass").': </label><input type="text" name="accesspass" value="'.$accesspass.'">
                                </div>
                                <div id="tab_02" class="ui_tab_content">
                                    <label for="JP">Join a group: </label>
                                    <select name="JP">';
                                    switch($JP) {
                                        case 1:
                                            echo '
                                                <option value="1" selected>'._("Direct").'</option>
                                                <option value="2">'._("Request").'</option>
                                                <option value="3">'._("Disabled").'</option>
                                            ';
                                            break;
                                        case 2:
                                            echo '
                                                <option value="1">'._("Direct").'</option>
                                                <option value="2" selected>'._("Request").'</option>
                                                <option value="3">'._("Disabled").'</option>
                                            ';
                                            break;
                                        case 3:
                                            echo '
                                                <option value="1">'._("Direct").'</option>
                                                <option value="2">'._("Request").'</option>
                                                <option value="3" selected>'._("Disabled").'</option>
                                            ';
                                            break;
                                        default:
                                            echo '
                                                <option value="1" selected>'._("Direct").'</option>
                                                <option value="2">'._("Request").'</option>
                                                <option value="3">'._("Disabled").'</option>
                                            ';
                                    }
                                    echo '
                                    </select>
                                </div>
                                <div id="tab_03" class="ui_tab_content">';
                                if ($sg == "true") {
                                    echo '<input type="checkbox" name="showgroups" checked>';
                                } else {
                                    echo '<input type="checkbox" name="showgroups">';
                                }
                                echo '                          
                                        <label for="showgroups">'._("Show groups in user profile").'</label>
                                    </form>
                                </div>
                                <div id="tab_04" class="ui_tab_content">
                                <b>Teeach</b><br>
                                <p>In Dev</p><br>
                                '._("Server time: ").': '.date("d-m-Y H:i:s").'            				
            				</div>
   						</div>

   						<input type="submit" value="'._("Save").'">

   					</form>
    			</center>
			';
		}
	?>
</body>
</html>