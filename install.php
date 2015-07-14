<?php
	session_start();
	include('core.php');
	include('PasswordHash.php');
	$System = new System();
	
	if(isset($_POST['lang'])){
		$lang = $System->parse_lang("src/lang/".$_POST['lang'].".json");
	}
	elseif(isset($_SESSION['lang'])){
		$lang = $System->parse_lang("src/lang/".$_SESSION['lang'].".json");
	}else{
		$lang = $System->parse_lang("src/lang/en_EN.json");
	}
	
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title><?php echo _("Install");?> | Teeach</title>
	<link rel="stylesheet" href="src/css/main.css">
	<?php $System->set_head(); ?>
	<script>
		function advertenciaDev() {
			//alert("Project Learn is in a very early stage of development. Also, this software can produces damage irreparable. Project Learn doesn't accountable of any damage.");
			location.href = "install.php?step=2";
		}
		function getUrl() {
			var url = location.href;
			var url_installation = url.replace("/install.php?step=3","");
			document.getElementById("url").value = url_installation;
		}

		function goStep3() {
			location.href = "install.php?step=3";
		}
	</script>
</head>
<body>
	<?php
		$version =  phpversion();
		$version = explode(".", $version);
		if($version[0] < 5 and $version[1] < 4){
			echo $lang["php_version_incompatible"];
			echo "<br>";
			echo "Version: ";
			echo phpversion();
		}else{
			date_default_timezone_set("Europe/Madrid");
			if(isset($_GET['step'])){
				$step = $_GET['step'];
			}else{
				$step = 1;
			}
			
			if ($step == "1") {
				
				echo '<center><h1>'.$lang["hello"].'</h1><p>'.$lang["thanks_teeach"].'</p><p>'.$lang["now_install"].'</p>
				<br>
				'.$lang["first_select_language"].':<br>
				<form method="post" action="install.php?step=2">
					<label for="lang"></label>
						<select name="lang">';
							$fp_langs = fopen("src/lang/langs.json", "r");
							$rfile_langs = fread($fp_langs, filesize("src/lang/langs.json"));
							$json_langs = json_decode($rfile_langs);
							foreach ($json_langs->{"langs"} as $index => $row_langs) {
								echo '<option value="'.$row_langs.'">'.$row_langs.'</option>';
							}
						echo'
						</select><br>
						<input type="submit" value="'.$lang["next"].'">
				</form>';
			} elseif($step == "2") {
				$_SESSION["lang"] = $_POST["lang"];
				echo '
					<center>
					<h1>'.$lang["terms"].'</h1>
					
						'.$lang["term1"].'<br>
						'.$lang["term2"].'<br>
						'.$lang["term3"].'<br>
						'.$lang["term4"].'<br>
						'.$lang["term5"].'<br>
						'.$lang["term6"].'<br>
					
					<button onclick="goStep3();">'.$lang["accept_and_next"].'</button>
					</center>
				';
			} elseif($step == "3") {
				echo '
					<h1>'.$lang["database"].'</h1>				
					<p>'.$lang["database_info"].':</p>
					<form name="form_db" action="install.php?step=4" method="POST">
						<label for="type_db">'.$lang["database_type"].': </label><select name="type_db"><option value="1">MySQL</option></select><br>
						<label for="server_db">'.$lang["database_server"].': </label><input type="text" name="server_db" required><br>
						<label for="name_db">'.$lang["database_name"].': </label><input type="text" name="name_db" required><br>
						<label for="user_db">'.$lang["database_user"].': </label><input type="text" name="user_db" required><br>
						<label for="pass_db">'.$lang["password"].': </label><input type="password" name="pass_db"><br>
						<input type="hidden" name="url" id="url" value="">
						<input type="submit" value="'.$lang['next'].'">
					</form>
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

				//CÓDIGO EN PRUEBAS!!!!!




				$sql = file_get_contents("tmp/db.sql"); // Leo el archivo
				// Lo siguiente hace gran parte de la magia, nos devuelve todos los tokens no vacíos del archivo
				$tokens = preg_split("/(--.*\s+|\s+|\/\*.*\*\/)/", $sql, null, PREG_SPLIT_NO_EMPTY);
				$length = count($tokens);
		
				$query = '';
				$inSentence = false;
				$curDelimiter = ";";
				// Comienzo a recorrer el string
				for($i = 0; $i < $length; $i++) {
					$lower = strtolower($tokens[$i]);
					$isStarter = in_array($lower, array( // Chequeo si el token actual es el comienzo de una consulta
					'select', 'update', 'delete', 'insert',
					'delimiter', 'create', 'alter', 'drop', 
					'call', 'set', 'use'
				));

				if($inSentence) { // Si estoy parseando una sentencia me fijo si lo que viene es un delimitador para terminar la consulta
					if($tokens[$i] == $curDelimiter || substr(trim($tokens[$i]), -1*(strlen($curDelimiter))) == $curDelimiter) { 
						// Si terminamos el parseo ejecuto la consulta
						$query .= str_replace($curDelimiter, '', $tokens[$i]); // Elimino el delimitador
						$con = $System->conDB("config.json");
						$con->query($query);
						$query = ""; // Preparo la consulta para continuar con la siguiente sentencia
						$tokens[$i] = '';
						$inSentence = false;
					}
				}
				else if($isStarter) { // Si hay que comenzar una consulta, verifico qué tipo de consulta es
				// Si es delimitador, cambio el delimitador usado. No marco comienzo de secuencia porque el delimitador se encarga de eso en la próxima iteración
					if($lower == 'delimiter' && isset($tokens[$i+1]))  
						$curDelimiter = $tokens[$i+1]; 
					else
						$inSentence = true; // Si no, comienzo una consulta 
						$query = "";
					}
					$query .= "{$tokens[$i]} "; // Voy acumulando los tokens en el string que contiene la consulta
				}

				//FIN DEL CÓDIGO EN PRUEBAS!!!!

				echo '
					<h1>'.$lang["initial_settings"].'</h1>
					<form action="install.php?step=5" method="POST">
						<h3>'.$lang["your_center"].'</h3>
						<table>
							<tr><td><label for="centername">'.$lang["centername"].': </label></td><td><input type="text" name="centername"></td></tr>
							<tr><td><label for="logo">'.$lang["logo"].': </label></td><td><input type="text" name="logo"></td></tr>
							<tr><td><label for="logo">'.$lang["accesspass"].': </label></td><td><input type="text" name="accesspass"></td></tr>
						</table>
						<h3>'.$lang["your_account"].'</h3>
						<table>
							<tr><td><label for="username">'.$lang["username"].': </label></td><td><input type="text" name="username"></td></tr>
							<tr><td><label for="email">'.$lang["email"].': </label></td><td><input type="text" name="email"></td></tr>
							<tr><td><label for="pass">'.$lang["password"].': </label></td><td><input type="password" name="pass"></td></tr>
							<tr><td><label for="rpass">'.$lang["repeat_password"].': </label></td><td><input type="password" name="rpass"></td></tr>
						</table>
						<input type="submit" value="'.$lang["create"].'">
					</form>
				';
			} elseif($step == "5") {
				//Datos del admin
				$username = $_POST['username'];
				$email = $_POST['email'];
				$pass = $_POST['pass'];
				$rpass = $_POST['rpass'];

				$h = substr( md5(microtime()), 1, 18);
				$pass_md5 = md5($pass);

				if ($pass != $rpass) {
					die("Password incorrect");
				}

				$t_hasher = new PasswordHash(8, FALSE);
				$pass_hash = $t_hasher->HashPassword($pass);

				$con = $System->conDB("config.json");
				$query = $con->query("INSERT INTO pl_users(username,email,pass,privilege,h) VALUES('$username','$email','$pass_hash',4,'$h')")or die(mysql_error());

				//Datos del centro
				$centername = $_POST['centername'];
				$logo = $_POST['logo'];
				$accesspass = $_POST['accesspass'];
				$lang_val = $_SESSION["lang"];

				$query = $con->query("INSERT INTO pl_settings(property,value) VALUES('centername','$centername')")or die("Query error!");
				$query = $con->query("INSERT INTO pl_settings(property,value) VALUES('logo','$logo')")or die("Query error!");
				$query = $con->query("INSERT INTO pl_settings(property,value) VALUES('accesspass','$accesspass')")or die("Query error!");
				$query = $con->query("INSERT INTO pl_settings(property,value) VALUES('JP','2')")or die("Query error!");
				$query = $con->query("INSERT INTO pl_settings(property,value) VALUES('showgroups','true')")or die("Query error!");
				$query = $con->query("INSERT INTO pl_settings(property,value) VALUES('lang','$lang_val')")or die("Query error!");

				echo '
					<h1>'.$lang["the_end"].'</h1>
					<p>'.$lang["thanks_teeach"].'</p>
					<a href="pl_panel/usr/login.php">'.$lang["finish"].'</a>
				';
			}
		}
	?>
</body>
</html>
