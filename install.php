<?php
	include('core.php');
	include('PasswordHash.php');
	$System = new System();
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
		$step = $_GET['step'];
		if ($step == "1") {
			
			echo '<center><h1>'._("Hello").'</h1><p>'._("Thanks for choosing Teeach.").'</p><p>'._("Now, we're going to install.").'</p><br><button onclick="advertenciaDev()">'._("Next").'</button></center>';
		} elseif($step == "2") {
			echo '
				<center>
				<h1>'._("Terms").'</h1>
				
					'._("Teeach is the only smart software for administration of educational databases.").'<br>
					'._("We still doesn't have the contract.").'<br>
					'._("Even so, if you press Accept button, you will accept our future terms that we will tell through email.").'<br>
					'._("To register this software we reserve the right to send promocional mails from Teeach and our affiliates.").'<br>
					'._("Teeach is open source and you can obtain it code in GitHub clicking here.").'<br>
					'._("Thanks by trust in Teeach!").'<br>
				
				<button onclick="goStep3();">'._("Accept and next").'</button>
				</center>
			';
		} elseif($step == "3") {
			echo '
				<h1>'._("Database").'</h1>				
				<p>'._("Teeach uses databases from save data. To connect, we need:").'</p>
				<form name="form_db" action="install.php?step=4" method="POST">
					<label for="type_db">'._("Database type: ").'</label><select name="type_db"><option value="1">MySQL</option></select><br>
					<label for="server_db">'._("Server DB: ").'</label><input type="text" name="server_db" required><br>
					<label for="name_db">'._("Name DB: ").'</label><input type="text" name="name_db" required><br>
					<label for="user_db">'._("User DB: ").'</label><input type="text" name="user_db" required><br>
					<label for="pass_db">'._("Password: ").'</label><input type="password" name="pass_db"><br>
					<input type="hidden" name="url" id="url" value="">
					<input type="submit" value="'._("Send").'">
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
				<h1>'._("Initial settings").'</h1>
				<form action="install.php?step=5" method="POST">
					<h3>'._("Your center").'</h3>
					<table>
						<tr><td><label for="centername">'._("Centername: ").'</label></td><td><input type="text" name="centername"></td></tr>
						<tr><td><label for="logo">'._("Logo: ").'</label></td><td><input type="text" name="logo"></td></tr>
					</table>
					<h3>'._("Your account").'</h3>
					<table>
						<tr><td><label for="username">'._("Username: ").'</label></td><td><input type="text" name="username"></td></tr>
						<tr><td><label for="email">'._("Email: ").'</label></td><td><input type="text" name="email"></td></tr>
						<tr><td><label for="pass">'._("Password: ").'</label></td><td><input type="password" name="pass"></td></tr>
						<tr><td><label for="rpass">'._("Repeat password: ").'</label></td><td><input type="password" name="rpass"></td></tr>
					</table>
					<input type="submit" value="'._("Create").'">
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
			$query = $con->query("insert into pl_users(username,email,pass,privilege,h) values('$username','$email','$pass_hash',4,'$h')")or die(mysql_error());

			//Datos del centro
			$centername = $_POST['centername'];
			$logo = $_POST['logo'];

			$query = $con->query("insert into pl_config(property,value) values('centername','$centername')")or die(mysql_error());
			$query = $con->query("insert into pl_config(property,value) values('logo','$logo')")or die(mysql_error());

			echo '
				<h1>'._("The End").'</h1>
				<p>'._("Thanks for choosing Teeach.").'</p>
				<a href="login.php">'._("Finish").'</a>
			';
		}
	?>
</body>
</html>