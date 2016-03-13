<?php
	session_start();
	include('core.php');
	include('PasswordHash.php');
	$System = new System();
	
	if(isset($_POST['lang'])){
		$lang_val = $_POST['lang'];
		$lang = $System->parse_lang("src/lang/".$_POST['lang'].".json");
	}
	elseif(isset($_SESSION['lang'])){
		$lang_val = $_SESSION['lang'];
		$lang = $System->parse_lang("src/lang/".$_SESSION['lang'].".json");
	}else{
		$lang_val = 'en_EN';
		$lang = $System->parse_lang("src/lang/en_EN.json");
	}

	if(@$_GET['step'] == "checklanguage") {
		$_SESSION["lang"] = $_POST["lang"];
		header('Location: install.php?step=1');
	}
	
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Install | Teeach</title>
	<link rel="stylesheet" href="src/css/main.css">
	<?php $System->set_head(); ?>
	<script src='src/js/main.js'></script>
	<script src='src/js/install.js'></script>
</head>
<body>
	<?php

		echo '
			<div class="installation">
				<div class="installation_header">
					<h1>te<span style="color:#c96">e</span>ach</h1>
					<h3>Installation</h3>
				</div>
		';

		$version =  phpversion();
		$version = explode(".", $version);
		if ($version[0] < 5 and $version[1] < 4) {
			echo $lang["php_version_incompatible"];
			echo "<br>";
			echo "Version: ";
			echo phpversion();
		} else {
			date_default_timezone_set("Europe/Madrid");
			if (isset($_GET['step'])) {
				$step = $_GET['step'];
			} else {
				$step = 1;
			}
			
			if ($step == "1") {
				
				echo '
					<center>
						<div class="installation_box">
							<h1>'.$lang["hello"].'</h1>
							<p>'.$lang["thanks_teeach"].'</p>
							<p>'.$lang["now_install"].'</p>
							
							<br>

							'.$lang["first_select_language"].':<br>
							<form method="post" action="install.php?step=checklanguage" id="lang_form">
								<label for="lang"></label>
									<select name="lang" id="lang_selector">';

										$fp_langs = fopen("src/lang/langs.json", "r");
										$rfile_langs = fread($fp_langs, filesize("src/lang/langs.json"));
										$json_langs = json_decode($rfile_langs);
										foreach ($json_langs->{"langs"} as $index => $row_langs) {

											$text_lang = $System->read_language($row_langs);

											echo '<option value="'.$row_langs.'"';if($lang_val == $row_langs) echo "selected";echo'>'.$text_lang.'</option>';

										}
									echo '
									</select>
							</form>

							<br>

							<a href="install.php?step=2"><button>'.$lang["next"].'</button></a>
						</div>
					</center>
				';
			} elseif($step == "2") {
				echo '
					<center>
						<div class="installation_box">
							<h1>'.$lang["terms"].'</h1>
					
								'.$lang["term1"].'<br>
								'.$lang["term2"].'<br>
								'.$lang["term3"].'<br>
								'.$lang["term4"].'<br>
								'.$lang["term5"].'<br>
								'.$lang["term6"].'<br>
					
							<button onclick="goStep3();">'.$lang["accept_and_next"].'</button>
						</div>
					</center>
				';
			} elseif($step == "3") {
				echo '
					<div class="installation_box">
						<h1>'.$lang["database"].'</h1>				
						<p>'.$lang["database_info"].':</p>
						<br>
						<table>
							<form name="form_db" action="install.php?step=4" method="POST">

								<tr>									
									<td><label for="type_db">'.$lang["database_type"].': </label></td>
									<td><select name="type_db"><option value="1">MySQL</option></select></td>
								</tr>

								<tr>
									<td><label for="server_db">'.$lang["database_server"].': </label></td>
									<td><input type="text" name="server_db" id="server_db" required></td>
								</tr>

								<tr>
									<td><label for="name_db">'.$lang["database_name"].': </label></td>
									<td><input type="text" name="name_db" required></td>
								</tr>
							
								<tr>
									<td><label for="user_db">'.$lang["database_user"].': </label></td>
									<td><input type="text" name="user_db" required></td>
								</tr>
							
								<tr>
									<td><label for="pass_db">'.$lang["password"].': </label></td>
									<td><input type="password" name="pass_db"></td>
								</tr>
							
								<tr>
									<td><input type="hidden" name="url" id="url" value=""></td>
									<td><input type="submit" value="'.$lang['next'].'"></td>
								</tr>							
							</form>
						</table>
					</div>

					<script>getUrl();</script>
				';
			} elseif($step == "4") {
				$url = $_POST['url'];
				$server_db = $_POST['server_db'];
				$name_db = $_POST['name_db'];
				$user_db = $_POST['user_db'];
				$pass_db = $_POST['pass_db'];

				$fp = fopen("config.json", "w+");
				$array = array('dbserver' => $server_db, 'database' => $name_db, 'dbuser' => $user_db, 'dbpass' => $pass_db, 'url' => $url);
				$json = json_encode($array);
				$wrt = fputs($fp, $json);
				fclose($fp);

				//Crear tablas BD

				$sql = file_get_contents("tmp/db.sql");
				$tokens = preg_split("/(--.*\s+|\s+|\/\*.*\*\/)/", $sql, null, PREG_SPLIT_NO_EMPTY);
				$length = count($tokens);
		
				$query = '';
				$inSentence = false;
				$curDelimiter = ";";

				for($i = 0; $i < $length; $i++) {
					$lower = strtolower($tokens[$i]);
					$isStarter = in_array($lower, array(
					'select', 'update', 'delete', 'insert',
					'delimiter', 'create', 'alter', 'drop', 
					'call', 'set', 'use'
				));

				if($inSentence) {
					if($tokens[$i] == $curDelimiter || substr(trim($tokens[$i]), -1*(strlen($curDelimiter))) == $curDelimiter) {
						$query .= str_replace($curDelimiter, '', $tokens[$i]);
						$con = $System->conDB("config.json");
						$con->query($query);
						$query = "";
						$tokens[$i] = '';
						$inSentence = false;
					}
				}
				else if($isStarter) {
					if ($lower == 'delimiter' && isset($tokens[$i+1]))  
						$curDelimiter = $tokens[$i+1]; 
					else
						$inSentence = true;
						$query = "";
					}
					$query .= "{$tokens[$i]} ";
				}

				echo '
					<div class="installation_box">
						<h1>'.$lang["initial_settings"].'</h1><br/>
						<form name="initial_settings" id="initial_settings" action="install.php?step=5" method="POST">
							<table>
								<tr><td><h3><b style="font-weight:bold">'.$lang["your_center"].'</b></h3></td><td></td></tr>							
								<tr><td><label for="centername">'.$lang["centername"].': </label></td><td><input type="text" name="centername"></td></tr>
								<tr><td><label for="logo">'.$lang["logo"].': </label><div class="tip">'.$lang["tip_logo"].'<a target="_blank" href="http://teeach.org/go?link=b1l14nqQ&lang=es_ES">'.$lang["more_information"].'</a></div></td><td><input type="text" name="logo"></td></tr>
								<tr><td><label for="accesspass">'.$lang["accesspass"].': </label><div class="tip">'.$lang["tip_accesspass"].'<a target="_blank" href="http://teeach.org/go?link=23aaa535&lang=es_ES">'.$lang["more_information"].'</a></div></td><td><input type="text" name="accesspass"></td></tr>
								<tr><td><h3><b style="font-weight:bold">'.$lang["your_account"].'</b></h3></td><td></td></tr>
								<tr><td><label for="username">'.$lang["username"].': </label></td><td><input type="text" name="username"></td></tr>
								<tr><td><label for="email">'.$lang["email"].': </label></td><td><input type="text" name="email"></td></tr>
								<tr><td><label for="pass">'.$lang["password"].': </label></td><td><input type="password" name="pass"></td></tr>
								<tr><td><label for="rpass">'.$lang["repeat_password"].': </label></td><td><input type="password" name="rpass"></td></tr>
								<tr><td></td><td><input type="button" value="'.$lang["create"].'" id="initial_settings_button"></td></tr>
							</table>
							<div id="initial_settings_advice" style="background: #CC8181;padding:20px;display:none">
								'.$lang["password_advice"].'
								<button id="skip_advice">'.$lang["continue_anyway"].'</button>
							</div>
						</form>
					</div>
				';
			} elseif($step == "5") {
				//Datos del admin
				$username = $_POST['username'];
				$email = $_POST['email'];
				$pass = $_POST['pass'];
				$rpass = $_POST['rpass'];
				$date = date("Y-m-d H:i:s");

				$h = substr( md5(microtime()), 1, 18);
				$pass_md5 = md5($pass);

				if ($pass != $rpass) {
					die("Password incorrect");
				}

				$t_hasher = new PasswordHash(8, FALSE);
				$pass_hash = $t_hasher->HashPassword($pass);

				$con = $System->conDB("config.json");
				$query = $con->query("INSERT INTO pl_users(username,email,pass,privilege,h,creation_date,tour) VALUES('$username','$email','$pass_hash',4,'$h','$date',0)")or die(mysql_error());

				//Post de prueba

				$testpost_title = $lang["testpost_title"];
				$testpost_body = $lang["testpost_body"];
				$testpost_h = substr( md5(microtime()), 1, 18);

				$query = $con->query("INSERT INTO pl_posts(title,body,h,author) VALUES('$testpost_title','$testpost_body','$testpost_h','teeach')")or die("Query error!");

				//Datos del centro
				$centername = $_POST['centername'];
				$logo = $_POST['logo'];
				$accesspass = $_POST['accesspass'];
				$lang_val = $_SESSION["lang"];

				//SET Settings Table
				$query = $con->query("INSERT INTO pl_settings(property,value) VALUES ('centername','$centername'),('logo','$logo'),('accesspass','$accesspass'),('lang','$lang_val'),('post_per_page','5'),('show_post_author','true'),('show_last_time','1'),('show_address','2'),('show_phone','2'),('show_groups','1'),('enable_profile_photo','true'),('JP','2'),('allow_create_categories','true')")or die("Query error!");

				echo '
					<center>
						<div class="installation_box">
							<h1>'.$lang["the_end"].'</h1><br/>
							<p>'.$lang["thanks_teeach"].'</p><br/>
							<a href="pl_panel/usr/login.php"><button>'.$lang["finish"].'</button></a>
						</div>
					</center>
				';
			}
		}

		echo '
				<div class="installation_footer">
					<p>©2016 Teeach. A open-source project</p>
				</div>
			</div>
		';
	?>
</body>
</html>
