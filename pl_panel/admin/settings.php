<?php

	include("../../core.php");

	$System = new System;
    $System->check_admin();
    $con = $System->conDB();
    $lang = $System->parse_lang();
    
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title><?php echo $lang["settings"]; ?> | Teeach</title>
	<link rel="stylesheet" href="../../src/css/main.css" />
    <link rel="stylesheet" href="../../src/css/settings.css" />
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'/>
	<?php $System->set_head(); ?>
    <!-- Tabs JS -->
    <script src="../../src/js/tabs.js"></script>
    <!-- Check All JS -->
    <script src="../../src/js/check-all.js"></script>
    <script src="../../src/js/settings.js"></script>

    <script type="text/javascript">
    	$('.tip').before( "Ayuda" );
    </script>
</head>
<body onload="javascript:cambiarPestanna(pestanas,pestana1);">

	<?php
		$action = $_GET['action'];

		if ($action == "save") {

            //--Basic
			$centername = $_POST['centername'];
			$logo = $_POST['logo'];
			$accesspass = $_POST['accesspass'];
            $default_main_page = $_POST['default_main_page'];
			$lang_val = $_POST['lang'];
            $date_format = $_POST['date_format'];
            $time_format = $_POST['time_format'];
            @$filter_obscene_language = $_POST['filter_obscene_language'];
            $post_per_page = $_POST['post_per_page'];
            @$show_post_author = $_POST['show_post_author'];
            @$show_post_date = $_POST['show_post_date'];

            //--Privacy
			$show_last_time = $_POST['show_last_time'];
            $show_address = $_POST['show_address'];
            $show_phone = $_POST['show_phone'];
            $show_groups = $_POST['show_groups'];
            @$enable_profile_photo = $_POST['enable_profile_photo'];

            //--Security
            $login_method = $_POST['login_method'];

            //--Advanced
            $JP = $_POST['JP'];
            @$allow_create_categories = $_POST['allow_create_categories'];
            $smtp_server = $_POST['smtp_server'];
            $email_username = $_POST['email_username'];
            $email_password = $_POST['email_password'];
            $email_address = $_POST['email_address'];
            $smtp_port = $_POST['smtp_port'];
            $email_name = $_POST['email_name'];
            $email_charset = $_POST['email_charset'];
            @$require_ssl = $_POST['require_ssl'];
            $email_timeout = $_POST['email_timeout'];

			if(@$show_post_author == "on") {
				$show_post_author = "true";
			} else {
				$show_post_author = "false";
			}

            if(@$show_post_date == "on") {
                $show_post_date = "true";
            } else {
                $show_post_date = "false";
            }

            if(@$enable_profile_photo == "on") {
                $enable_profile_photo = "true";
            } else {
                $enable_profile_photo = "false";
            }

            if(@$allow_create_categories == "on") {
                $allow_create_categories = "true";
            } else {
                $allow_create_categories = "false";
            }

            if(@$filter_obscene_language == "on") {
                $filter_obscene_language = 1;
            } else {
                $filter_obscene_language = 0;
            }

            if(@$require_ssl == "on") {
                $require_ssl = 1;
            } else {
                $require_ssl = 0;
            }

            //SET Centername (string) ~ The name of the center
			$query = $System->queryDB("UPDATE pl_settings SET value='$centername' WHERE property='centername'", $con);

            //SET Logo (string) ~ The image that represents the center
			$query = $System->queryDB("UPDATE pl_settings SET value='$logo' WHERE property='logo'", $con);

            //SET Accesspass (string) ~ The access password
			$query = $System->queryDB("UPDATE pl_settings SET value='$accesspass' WHERE property='accesspass'", $con);

            //SET Index Page (integer) ~ Default main page
            //       0 => Home page (posts)
            //       1 => Login page
            $query = $System->queryDB("UPDATE pl_settings SET value='$default_main_page' WHERE property='index_page'", $con);

            //SET Language (string) ~ The default language
            $query = $System->queryDB("UPDATE pl_settings SET value='$lang_val' WHERE property='lang'", $con);

            //SET Date Format (integer) ~ The date format
            //       1 => MM/DD/YYYY
            //       2 => DD/MM/YYYY
            //       3 => DD Month YYYY
            $query = $System->queryDB("UPDATE pl_settings SET value='$date_format' WHERE property='date_format'", $con);

            //SET Time Format (integer) ~ The time format (12h or 24h)
            //       12 => 12h
            //       24 => 24h
            $query = $System->queryDB("UPDATE pl_settings SET value='$time_format' WHERE property='time_format'", $con);

            //SET Filter Obscene Language (boolean) ~ Filter Obscene Language
            $query = $System->queryDB("UPDATE pl_settings SET value='$filter_obscene_language' WHERE property='filter_obscene_language'", $con);

            //SET Post per page (integer) ~ The number of pages that fit on one page
            $query = $System->queryDB("UPDATE pl_settings SET value='$post_per_page' WHERE property='post_per_page'", $con);

            //SET Allow post comments (boolean) ~ Allow post comments (\===IN DEVELOPMENT===/)
            //$query = $con->query("UPDATE pl_settings SET value='$allow_post_comments' WHERE property='allow_post_comments'")or die("Query error!");

            //SET Show post author (boolean) ~ Show post author
            $query = $System->queryDB("UPDATE pl_settings SET value='$show_post_author' WHERE property='show_post_author'", $con);

            //SET Show post date (boolean) ~ Show post date
            $query = $System->queryDB("UPDATE pl_settings SET value='$show_post_date' WHERE property='show_post_date'", $con);

            //SET Show last time (integer) ~ Show last time
            //       1 => All
            //       2 => Only teachers
            //       3 => Only administrators
            //       4 => Nobody
            $query = $System->queryDB("UPDATE pl_settings SET value='$show_last_time' WHERE property='show_last_time'", $con);

            //SET Show address (integer) ~ Show address
            //       1 => All
            //       2 => Only teachers
            //       3 => Only administrators
            //       4 => Nobody
            $query = $System->queryDB("UPDATE pl_settings SET value='$show_address' WHERE property='show_address'", $con);

            //SET Show phone (integer) ~ Show phone
            //       1 => All
            //       2 => Only teachers
            //       3 => Only administrators
            //       4 => Nobody
            $query = $System->queryDB("UPDATE pl_settings SET value='$show_phone' WHERE property='show_phone'", $con);

            //SET Show Groups (boolean) ~ If it's activated, anyone can view the users groups in his profile
			$query = $System->queryDB("UPDATE pl_settings SET value='$show_groups' WHERE property='show_groups'", $con);

            //SET Enable profile photo (boolean)
            $query = $System->queryDB("UPDATE pl_settings SET value='$enable_profile_photo' WHERE property='enable_profile_photo'", $con);

            //SET Show phone (integer) ~ Show phone
            //       1 => All
            //       2 => Only teachers
            //       3 => Only administrators
            $query = $System->queryDB("UPDATE pl_settings SET value='$login_method' WHERE property='login_method'", $con);

            //SET Login method (integer) ~ The method for log in.
            //       1 => Email or username
            //       2 => Only email
            //       3 => Only username
            $query = $System->queryDB("UPDATE pl_settings SET value=$JP WHERE property='JP'", $con);

            //SET Allow create categories (boolean) ~ Anyone can create new categories
            $query = $System->queryDB("UPDATE pl_settings SET value='$allow_create_categories' WHERE property='allow_create_categories'", $con);
		
            //SET SMTP server (string) ~ SMTP server for email
            $query = $System->queryDB("UPDATE pl_settings SET value='$smtp_server' WHERE property='smtp_server'", $con);

            //SET Email username (string)
            $query = $System->queryDB("UPDATE pl_settings SET value='$email_username' WHERE property='email_username'", $con);

            //SET Email password (string)
            $query = $System->queryDB("UPDATE pl_settings SET value='$email_password' WHERE property='email_password'", $con);

            //SET Email address (string)
            $query = $System->queryDB("UPDATE pl_settings SET value='$email_address' WHERE property='email_address'", $con);

            //SET SMTP port (integer)
            $query = $System->queryDB("UPDATE pl_settings SET value='$smtp_port' WHERE property='smtp_port'", $con);

            //SET Email name (string) ~ Email from name
            $query = $System->queryDB("UPDATE pl_settings SET value='$email_name' WHERE property='email_name'", $con);

            //SET Email charset (string)
            $query = $System->queryDB("UPDATE pl_settings SET value='$email_charset' WHERE property='email_charset'", $con);

            //SET Require SSL (boolean) ~ Require SSL?
            $query = $System->queryDB("UPDATE pl_settings SET value='$require_ssl' WHERE property='require_ssl'", $con);

            //SET Timeout (integer) ~ On seconds
            $query = $System->queryDB("UPDATE pl_settings SET value='$email_timeout' WHERE property='email_timeout'", $con);

			if($_FILES["up_lang"]["size"] != 0) {
				$target_dir = "../../src/lang/";
				$target_file = $target_dir . basename($_FILES["up_lang"]["name"]);
				$uploadOk = 1;
				$fileType = pathinfo($target_file,PATHINFO_EXTENSION);
				// Allow certain file formats
				if($fileType != "json") {
					echo "Sorry, only json files are allowed.";
					$uploadOk = 0;
				}
				// Check if $uploadOk is set to 0 by an error
				if ($uploadOk == 0) {
					echo "Sorry, your file was not uploaded.";
				// if everything is ok, try to upload file
				} else {
					$fp_langs = fopen("../../src/lang/langs.json", "r+");
					$rfile_langs = fread($fp_langs, filesize("../../src/lang/langs.json"));
					fclose($fp_langs);
					$json_langs = json_decode($rfile_langs);
					$filename = explode(".", basename( $_FILES["up_lang"]["name"]))[0];
					if(!in_array($filename,$json_langs->{"langs"})){
						$json_langs->{"langs"}[] = $filename;
					}
					
					if (move_uploaded_file($_FILES["up_lang"]["tmp_name"], $target_file)) {
						$fp_langs = fopen("../../src/lang/langs.json", "w");
						fwrite($fp_langs, json_encode($json_langs));
						fclose($fp_langs);
						
					} else {
						echo "Sorry, there was an error uploading your file.";
					}
				}
			}
			
			echo '<script>location.href="settings.php?action";</script>';

		} else {
			
			//---  Queries
			$query_centername = $System->queryDB("SELECT * FROM pl_settings WHERE property='centername'", $con);
			$query_logo = $System->queryDB("SELECT * FROM pl_settings WHERE property='logo'", $con);
			$query_accesspass = $System->queryDB("SELECT * FROM pl_settings WHERE property='accesspass'", $con);
            $query_default_main_page = $System->queryDB("SELECT * FROM pl_settings WHERE property='index_page'", $con);
            $query_lang = $System->queryDB("SELECT * FROM pl_settings WHERE property='lang'", $con);
            $query_date_format = $System->queryDB("SELECT * FROM pl_settings WHERE property='date_format'", $con);
            $query_time_format = $System->queryDB("SELECT * FROM pl_settings WHERE property='time_format'", $con);
            $query_filter_obscene_language = $System->queryDB("SELECT * FROM pl_settings WHERE property='filter_obscene_language'", $con);
            $query_post_per_page = $System->queryDB("SELECT * FROM pl_settings WHERE property='post_per_page'", $con);
            $query_show_post_author = $System->queryDB("SELECT * FROM pl_settings WHERE property='show_post_author'", $con);
            $query_show_post_date = $System->queryDB("SELECT * FROM pl_settings WHERE property='show_post_date'", $con);
            $query_show_last_time = $System->queryDB("SELECT * FROM pl_settings WHERE property='show_last_time'", $con);
            $query_show_address = $System->queryDB("SELECT * FROM pl_settings WHERE property='show_address'", $con);
            $query_show_phone = $System->queryDB("SELECT * FROM pl_settings WHERE property='show_phone'", $con);
			$query_show_groups = $System->queryDB("SELECT * FROM pl_settings WHERE property='show_groups'", $con);
            $query_enable_profile_photo = $System->queryDB("SELECT * FROM pl_settings WHERE property='enable_profile_photo'", $con);
            $query_login_method = $System->queryDB("SELECT * FROM pl_settings WHERE property='login_method'", $con);
            $query_JP = $System->queryDB("SELECT * FROM pl_settings WHERE property='JP'", $con);
            $query_allow_create_categories = $System->queryDB("SELECT * FROM pl_settings WHERE property='allow_create_categories'", $con);
            $query_smtp_server = $System->queryDB("SELECT * FROM pl_settings WHERE property='smtp_server'", $con);
            $query_email_username = $System->queryDB("SELECT * FROM pl_settings WHERE property='email_username'", $con);
            $query_email_password = $System->queryDB("SELECT * FROM pl_settings WHERE property='email_password'", $con);
            $query_email_address = $System->queryDB("SELECT * FROM pl_settings WHERE property='email_address'", $con);
            $query_smtp_port = $System->queryDB("SELECT * FROM pl_settings WHERE property='smtp_port'", $con);
            $query_email_name = $System->queryDB("SELECT * FROM pl_settings WHERE property='email_name'", $con);
            $query_email_charset = $System->queryDB("SELECT * FROM pl_settings WHERE property='email_charset'", $con);
            $query_require_ssl = $System->queryDB("SELECT * FROM pl_settings WHERE property='require_ssl'", $con);
            $query_email_timeout = $System->queryDB("SELECT * FROM pl_settings WHERE property='email_timeout'", $con);

			//---  Arrays
			$row_centername = $System->fetch_array($query_centername);
			$row_logo = $System->fetch_array($query_logo);
			$row_accesspass = $System->fetch_array($query_accesspass);
            $row_default_main_page = $System->fetch_array($query_default_main_page);
            $row_lang = $System->fetch_array($query_lang);
            $row_date_format = $System->fetch_array($query_date_format);
            $row_time_format = $System->fetch_array($query_time_format);
            $row_filter_obscene_language = $System->fetch_array($query_filter_obscene_language);
            $row_post_per_page = $System->fetch_array($query_post_per_page);
            $row_show_post_author = $System->fetch_array($query_show_post_author);
            $row_show_post_date = $System->fetch_array($query_show_post_date);
            $row_show_last_time = $System->fetch_array($query_show_last_time);
            $row_show_address = $System->fetch_array($query_show_address);
            $row_show_phone = $System->fetch_array($query_show_phone);
            $row_show_groups = $System->fetch_array($query_show_groups);
            $row_enable_profile_photo = $System->fetch_array($query_enable_profile_photo);
            $row_login_method = $System->fetch_array($query_login_method);
            $row_JP = $System->fetch_array($query_JP);
            $row_allow_create_categories = $System->fetch_array($query_allow_create_categories);
            $row_smtp_server = $System->fetch_array($query_smtp_server);
            $row_email_username = $System->fetch_array($query_email_username);
            $row_email_password = $System->fetch_array($query_email_password);
            $row_email_address = $System->fetch_array($query_email_address);
            $row_smtp_port = $System->fetch_array($query_smtp_port);
            $row_email_name = $System->fetch_array($query_email_name);
            $row_email_charset = $System->fetch_array($query_email_charset);
            $row_require_ssl = $System->fetch_array($query_require_ssl);
            $row_email_timeout = $System->fetch_array($query_email_timeout);

			//---  Values
			$centername = $row_centername['value'];
			$logo = $row_logo['value'];
			$accesspass = $row_accesspass['value'];
            $default_main_page = $row_default_main_page['value'];
            $lang_val = $row_lang['value'];
            $date_format = $row_date_format['value'];
            $time_format = $row_time_format['value'];
            $filter_obscene_language = $row_filter_obscene_language['value'];
            $post_per_page = $row_post_per_page['value'];
            $show_post_author = $row_show_post_author['value'];
            $show_post_date = $row_show_post_date['value'];
            $show_last_time = $row_show_last_time['value'];
            $show_address = $row_show_address['value'];
            $show_phone = $row_show_phone['value'];
			$show_groups = $row_show_groups['value'];
            $enable_profile_photo = $row_enable_profile_photo['value'];
            $login_method = $row_login_method['value'];
            $JP = $row_JP['value'];
            $allow_create_categories = $row_allow_create_categories['value'];
            $smtp_server = $row_smtp_server['value'];
            $email_username = $row_email_username['value'];
            $email_password = $row_email_password['value'];
            $email_address = $row_email_address['value'];
            $smtp_port = $row_smtp_port['value'];
            $email_name = $row_email_name['value'];
            $email_charset = $row_email_charset['value'];
            $require_ssl = $row_require_ssl['value'];
            $email_timeout = $row_email_timeout['value'];
			
			/*echo '
			
			<script type="text/javascript">
				function submitForm() {
					//~ console.log("submit event");
					var fd = new FormData(document.getElementById("settings"));
					fd.append("centername", $("#centername").val());
					fd.append("logo", $("#logo").val());
					fd.append("accesspass", $("#accesspass").val());
					fd.append("lang", $("#lang").val());
					fd.append("showgroups", $("#showgroups").val());
					fd.append("JP", $("#JP").val());
					fd.append("posts_per_page", $("#posts_per_page").val());
					fd.append("allow_comments", $("#allow_comments").val());
					fd.append("show_author", $("#show_author").val());
					$.ajax({
					  url: "settings.php?action=save",
					  type: "POST",
					  data: fd,
					  enctype: \'multipart/form-data\',
					  processData: false,  // tell jQuery not to process the data
					  contentType: false   // tell jQuery not to set contentType
					}).done(function( data ) {
						alert("'.$lang["saved_data"].'");
						//~ console.log("PHP Output:");
						//~ console.log( data );
					});
					return false;
				}
			</script>';*/

            echo '
			
            <div class="admin_header">
                <div class="admin_hmenu">
                    <a href="index.php"><img src="../../src/ico/back.svg" alt="Atrás" class="btn_back"></a><h2><a href="index.php">Admin</a> >> <a href="settings.php?action">'.$lang["settings"].'</a></h2>
			    </div>
            </div>
            	<center>
					<form id="settings" method="post" enctype="multipart/form-data" action="settings.php?action=save">
						<div class="contenedor">


						<nav class="ui_tabs">
                            <ul>
                                <li class="active"><a href="#tab_01">'.$lang["basic"].'</a></li>
                                <li><a href="#tab_02">'.$lang["privacy"].'</a></li>
                                <li><a href="#tab_05">'.$lang["security"].'</a></li>
                                <li><a href="#tab_03">'.$lang["advanced"].'</a></li>
                                <li><a href="#tab_04">'.$lang["about"].'</a></li>
                            </ul>
                        </nav>


                        <div class="ui_tabs_content">
                            <form class="ui_form">

                                <!-- Basic Settings -->
                                <div id="tab_01" class="ui_tab_content">
                                    <table>
                                    	<tr>
                                    		<td><p style="font-family:RobotoBold">'.$lang["center_data"].'</p></td>
                                    		<td></td>
                                    	</tr>

                                        <tr>
                                        	<td><label for="centername">'.$lang["centername"].': </label></td>
                                        	<td><input type="text" id="centername" name="centername" value="'.$centername.'"></td>
                                        </tr>

                                        <tr>
                                        	<td><label for="logo">'.$lang["logo"].': </label><div class="tip">'.$lang["tip_logo"].'<a target="_blank" href="http://teeach.org/go?link=b1l14nqQ&lang=es_ES">'.$lang["more_information"].'</a></div></td>
                                        	<td>
                                                <!--<select name="logo_type" id="logoType">
                                                    <option name="url">URL</option>
                                                    <option name="upload">'.$lang["upload"].'</option>
                                                </select>-->
                                                <div id="logoURL"><input type="text" name="logo" id="logo" value="'.$logo.'"></div>
                                                <!--<div id="logoUpload"><input type="file" name="up_logo"></div>-->
                                            </td>
                                        </tr>

                                        <tr>
                                        	<td></td>
                                        	<td><img src="'.$logo.'" alt="logo" style="width: 128px;height: 128px"></td>
                                        </tr>

                                        <tr>
                                        	<td><label for="accesspass">'.$lang["accesspass"].': </label><div class="tip">'.$lang["tip_accesspass"].'<a target="_blank" href="http://teeach.org/go?link=23aaa535&lang=es_ES">'.$lang["more_information"].'</a></div></td>
                                        	<td><input type="text" id="accesspass" name="accesspass" value="'.$accesspass.'"></td>
                                        </tr>

                                        <tr>
                                            <td><label for="default_main_page">'.$lang["default_main_page"].': </label></td>
                                            <td>
                                                <select name="default_main_page">
                                                    <option value="1" ';if($default_main_page==1){echo'selected';}echo'>'.$lang["log_in"].'</option>
                                                    <option value="0" ';if($default_main_page==0){echo'selected';}echo'>'.$lang["posts"].'</option>
                                                </select>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td><p style="font-family:RobotoBold">'.$lang["hour_and_language"].'</p></td>
                                            <td></td>
                                        </tr>
                                        
                                        <tr>
                                        	<td><label for="lang">'.$lang["default_language"].': </label></td>
                                        	<td>
												<select id="lang" name="lang">';
													$fp_langs = fopen("../../src/lang/langs.json", "r");
													$rfile_langs = fread($fp_langs, filesize("../../src/lang/langs.json"));
													$json_langs = json_decode($rfile_langs);
													foreach ($json_langs->{"langs"} as $index => $row_langs) {

                                                        $text_lang = $System->read_language($row_langs);

														echo '<option value="'.$row_langs.'"';if($lang_val == $row_langs) echo "selected";echo'>'.$text_lang.'</option>';
													}
													echo '
												</select>
                                        	</td>
                                        </tr>

                                        <tr>
                                            <td><label for="date_format">'.$lang["date_format"].': </label></td>
                                            <td>
                                                <input type="radio" name="date_format" value="1" id="date1" ';if($date_format == 1){echo'checked';}echo'><label for="date1">'.date("m/d/Y").'</label><br>
                                                <input type="radio" name="date_format" value="2" id="date2" ';if($date_format == 2){echo'checked';}echo'><label for="date2">'.date("d/m/Y").'</label><br>                                                
                                                <input type="radio" name="date_format" value="3" id="date3" ';if($date_format == 3){echo'checked';}echo'><label for="date3">'.date("d").' '.$lang["of_date"].' '.$month=$System->get_month($lang, date("m")).' '.$lang["of_date"].' '.date("Y").'</label>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td><label for="time_format">'.$lang["time_format"].'</label>: </td>
                                            <td>
                                                <select name="time_format">
                                                    <option value="12" ';if($time_format==12){echo'selected';}echo'>12 '.$lang["hours"].'</option>
                                                    <option value="24" ';if($time_format==24){echo'selected';}echo'>24 '.$lang["hours"].'</option>
                                                </select>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td><label for="filter_obscene_language">'.$lang["filter_obscene_language"].'</label>: <div class="tip">'.$lang["tip_filter_obscene_language"].'<a target="_blank" href="http://teeach.org/go?link=7d64dfe2">'.$lang["more_information"].'</a></div></td>
                                            <td><input type="checkbox" name="filter_obscene_language" ';if($filter_obscene_language==1){echo "checked";}echo'></td>
                                        </tr>

                                        <tr>
                                        	<td><p style="font-family:RobotoBold">'.$lang["posts"].'</p></td>
                                        	<td></td>
                                        </tr>
                                        
                                        <tr>
                                        	<td><label for="post_per_page">'.$lang["posts_per_page"].':</label></td>
                                        	<td><input type="number" id="posts_per_page" name="post_per_page" min="1" value="'.$row_post_per_page["value"].'"></td>
                                        </tr>

                                        <!--<tr>
                                        	<td><label for="allow_comments">'.$lang["allow_comments"].':</label></td>
                                        	<td><input type="checkbox" id="allow_comments" name="allow_comments" ';if($post_comments=="true"){echo "checked";} echo '></td>
                                        </tr>-->

                                        <tr>
                                        	<td><label for="show_post_author">'.$lang["show_author"].':</label></td>
                                        	<td><input type="checkbox" id="show_author" name="show_post_author" ';if($show_post_author=="true"){echo "checked";} echo '></td>
                                        </tr>

                                        <tr>
                                            <td><label for="show_post_date">'.$lang["show_post_date"].':</label></td>
                                            <td><input type="checkbox" id="show_post_date" name="show_post_date" ';if($show_post_date=="true"){echo "checked";} echo '></td>
                                        </tr>
                                    </table>
                                </div>
                                
                                <div id="tab_02" class="ui_tab_content">
                                
                                <!-- Privacy Settings -->

                                	<table>
                                		<tr>
                                			<td><p style="font-family: RobotoBold">'.$lang["profile"].'</p></td>
                                			<td></td>
                                		</tr>

                                		<tr>
                                			<td><label for="last_time">'.$lang["show_last_time"].'</label></td>
                                			<td>
												<select name="show_last_time">
													<option value="1" '; if($show_last_time == 1){echo'selected';}echo'>'.$lang["everybody"].'</option>
													<option value="2" '; if($show_last_time == 2){echo'selected';}echo'>'.$lang["only_teachers"].'</option>
													<option value="3" '; if($show_last_time == 3){echo'selected';}echo'>'.$lang["only_administrators"].'</option>
													<option value="4" '; if($show_last_time == 4){echo'selected';}echo'>'.$lang["nobody"].'</option>
												</select>
                                			</td>
                                		</tr>

                                        <tr>
                                            <td><label for="show_address">'.$lang["show_address"].'</label></td>
                                            <td>
                                                <select name="show_address">
                                                    <option value="1" '; if($show_address == 1){echo'selected';}echo'>'.$lang["everybody"].'</option>
                                                    <option value="2" '; if($show_address == 2){echo'selected';}echo'>'.$lang["only_teachers"].'</option>
                                                    <option value="3" '; if($show_address == 3){echo'selected';}echo'>'.$lang["only_administrators"].'</option>
                                                    <option value="4" '; if($show_address == 4){echo'selected';}echo'>'.$lang["nobody"].'</option>
                                                </select>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td><label for="">'.$lang["show_phone"].'</label></td>
                                            <td>
                                                <select name="show_phone">
                                                    <option value="1" '; if($show_phone == 1){echo'selected';}echo'>'.$lang["everybody"].'</option>
                                                    <option value="2" '; if($show_phone == 2){echo'selected';}echo'>'.$lang["only_teachers"].'</option>
                                                    <option value="3" '; if($show_phone == 3){echo'selected';}echo'>'.$lang["only_administrators"].'</option>
                                                    <option value="4" '; if($show_phone == 4){echo'selected';}echo'>'.$lang["nobody"].'</option>
                                                </select>
                                            </td>
                                        </tr>

                                		<tr>
                                			<td><label for="showgroups">'.$lang["show_groups_prf"].'</label></td>
                                            <td>
                                                <select name="show_groups">
                                                    <option value="1" '; if($show_groups == 1){echo'selected';}echo'>'.$lang["everybody"].'</option>
                                                    <option value="2" '; if($show_groups == 2){echo'selected';}echo'>'.$lang["only_teachers"].'</option>
                                                    <option value="3" '; if($show_groups == 3){echo'selected';}echo'>'.$lang["only_administrators"].'</option>
                                                    <option value="4" '; if($show_groups == 4){echo'selected';}echo'>'.$lang["nobody"].'</option>
                                                </select>
                                            </td>                        
                                    	</tr>
                                    	<tr><td><label for="enable_profile_photo">'.$lang["enable_profile_photo"].'</label></td><td><input type="checkbox" name="enable_profile_photo" ';if($enable_profile_photo == "true"){echo'checked';}echo'></td></tr>
                                    </table>
                                </div>
                                
                                <!-- Security Settings -->
                                <div id="tab_05" class="ui_tab_content">
                                    <table>

                                        <tr>
                                            <td><p style="font-family:RobotoBold">'.$lang["login_options"].'</p></td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td><label for="login_method">'.$lang["login_with"].'</label> <div class="tip">'.$lang["tip_login_method"].'<a target="_blank" href="#">'.$lang["more_information"].'</a></div></td>
                                            <td>
                                                <select name="login_method">
                                                    <option value="1" '; if($login_method == 1){echo'selected';}echo'>'.$lang["email_or_user"].'</option>
                                                    <option value="2" '; if($login_method == 2){echo'selected';}echo'>'.$lang["email"].'</option>
                                                    <option value="3" '; if($login_method == 3){echo'selected';}echo'>'.$lang["user"].'</option>
                                                </select>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td><label for="logon_tries">Intentos de inicio de sesión:</label></td>
                                            <td><input type="number" name="logon_tries" min="1" value="'.$row_post_per_page["value"].'"></td>
                                        </tr>

                                        <tr>
                                            <td><label for="logon_tries">Duración del bloqueo:</label></td>
                                            <td>
                                                <select name="block_timelapse">
                                                    <option value="1">15 '.$lang["minutes"].'</option>
                                                    <option value="2">30 '.$lang["minutes"].'</option>
                                                    <option value="3">1 '.$lang["hour"].'</option>
                                                    <option value="4">3 '.$lang["hours"].'</option>
                                                    <option value="5">12 '.$lang["hours"].'</option>
                                                    <option value="6">1 '.$lang["day"].'</option>
                                                </select>
                                            </td>
                                        </tr>

                                    </table>
                                </div>

                                <div id="tab_03" class="ui_tab_content">
                                <!-- Advanced Settings -->
                                	<table>
                                		<tr>
                                        	<td><p style="font-family:RobotoBold">'.$lang["categories_and_groups"].'</p></td>
                                        	<td></td>
                                        </tr>

                                        <tr>
                                        	<td><label for="JP">'.$lang["join_group"].': </label><div class="tip">'.$lang["tip_join_group_method"].'<a target="_blank" href="http://teeach.org/go?link=5444a4b0&lang=es_ES">'.$lang["more_information"].'</a></div></td>
                                        	<td>
                                        		<select id="JP" name="JP">
                                                    <option value="1" ';if($JP == 1){echo'selected';}echo'>'.$lang["direct"].'</option>
                                                    <option value="2" ';if($JP == 2){echo'selected';}echo'>'.$lang["request"].'</option>
                                                    <option value="3" ';if($JP == 3){echo'selected';}echo'>'.$lang["disabled"].'</option>
                                                </select>                                        		
                                        	</td>
                                        </tr>

                                        <tr>
                                        	<td><label for="allow_create_categories">'.$lang["allow_create_categories"].'</label></td>
                                        	<td><input type="checkbox" name="allow_create_categories" ';if($allow_create_categories == "true"){echo 'checked';} echo '></td>
                                        </tr>

                                        <tr>
                                            <td><label for="up_lang">'.$lang["upload_lang"].': </label></td>
                                            <td><input type="file" name="up_lang"></td>
                                        </tr>

                                        <tr>
                                            <td><p style="font-family:RobotoBold">'.$lang["email_options"].'</p></td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td><label for="smtp_server">'.$lang["smtp_server"].'</label></td>
                                            <td><input type="text" name="smtp_server" value="'.$smtp_server.'"></input></td>
                                        </tr>

                                        <tr></tr>

                                        <tr>
                                            <td><label for="email_username">'.$lang["username"].'</label></td>
                                            <td><input type="text" name="email_username" value="'.$email_username.'"></td>
                                        </tr>

                                        <tr>
                                            <td><label for="email_password">'.$lang["password"].'</label></td>
                                            <td><input type="password" name="email_password" value="'.$email_password.'"></td>
                                        </tr>

                                        <tr>
                                            <td><a href="#" id="more_email_options_button">Más opciones de correo electrónico</a></td>
                                        </tr>

                                    
                                    <tbody id="more_email_options">
                                        <tr>
                                            <td><label for="email_address">'.$lang["address"].'</label></td>
                                            <td><input type="text" name="email_address" value="'.$email_address.'"></td>
                                        </tr>

                                        <tr>
                                            <td><label for="smtp_port">'.$lang["smtp_port"].'</label></td>
                                            <td><input type="text" name="smtp_port" value="'.$smtp_port.'"></td>
                                        </tr>

                                        <tr>
                                            <td><label for="email_name">'.$lang["name"].'</label></td>
                                            <td><input type="text" name="email_name" value="'.$email_name.'""></td>
                                        </tr>

                                        <tr>
                                            <td><label for="email_charset">'.$lang["charset"].'</label></td>
                                            <td><input type="text" name="email_charset" value="'.$email_charset.'"></td>
                                        </tr>

                                        <tr>
                                            <td><label for="require_ssl">'.$lang["require_ssl"].'</label></td>
                                            <td><input type="checkbox" name="require_ssl" ';if($require_ssl == 1){echo 'checked';} echo '></td>
                                        </tr>

                                        <tr>
                                            <td><label for="email_timeout">'.$lang["timeout"].'</label></td>
                                            <td><input type="number" name="email_timeout" min="1" max="9999" value="'.$email_timeout.'"></td>
                                        </tr>
                                    </tbody>
                                    </table>
                                    </form>
                                </div>
                                <div id="tab_04" class="ui_tab_content">
                                <span style="font-weight: bold">Teeach</span><br>
                                <p>Early Development Version</p><br>
                                '._("Server time: ").' '.date("d-m-Y H:i:s").'
            				</div>
   						</div>

   						<input type="submit" value="'.$lang["save"].'">

   					</form>
    			</center>
			';
		}
	?>
</body>
</html>