<?php
	include("../../core.php");
	$System = new System;
	$con = $System->conDB();
	$lang = $System->parse_lang();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo $lang["recover_password"]; ?> | Teeach</title>
	<?php $System->set_head(); ?>
</head>
<body>
	<?php

	if (@$_GET['action'] == "success") {

		$email = $_POST['email'];
		$query =  $System->queryDB("SELECT * FROM pl_users WHERE email='$email'", $con);
		$row = $System->fetch_array($query);
		if(!isset($row['email'])) {
			die("Esa dirección de correo electrónico no existe en nuestra base de datos. Si le pertence, puede crear una nueva cuenta con ese correo electrónico haciendo clic "."<a href='register.php?email=".$email."'>"."aquí"."</a>.");
		}

		require '../../src/phpmailer/class.phpmailer.php';
    	require '../../src/phpmailer/class.smtp.php';
    	require '../../PasswordHash.php';

    	
    	$new_pass = substr( md5(microtime()), 1, 8);
    	$t_hasher = new PasswordHash(8, FALSE);
        $new_pass_hash = $t_hasher->HashPassword($new_pass);

    	$query1 = $System->queryDB("SELECT * FROM pl_settings WHERE property='smtp_server'", $con);
    	$query2 = $System->queryDB("SELECT * FROM pl_settings WHERE property='email_username'", $con);
    	$query3 = $System->queryDB("SELECT * FROM pl_settings WHERE property='email_password'", $con);
    	$query4 = $System->queryDB("SELECT * FROM pl_settings WHERE property='email_address'", $con);
    	$query5 = $System->queryDB("SELECT * FROM pl_settings WHERE property='email_name'", $con);
    	$query6 = $System->queryDB("SELECT * FROM pl_settings WHERE property='email_charset'", $con);
    	$query7 = $System->queryDB("SELECT * FROM pl_settings WHERE property='require_ssl'", $con);
    	$query8 = $System->queryDB("SELECT * FROM pl_settings WHERE property='email_timeout'", $con);
    	$query9 = $System->queryDB("SELECT * FROM pl_settings WHERE property='smtp_port'", $con);

    	$row1 = $System->fetch_array($query1);
    	$row2 = $System->fetch_array($query2);
    	$row3 = $System->fetch_array($query3);
    	$row4 = $System->fetch_array($query4);
    	$row5 = $System->fetch_array($query5);
    	$row6 = $System->fetch_array($query6);
    	$row7 = $System->fetch_array($query7);
    	$row8 = $System->fetch_array($query8);
    	$row9 = $System->fetch_array($query9);

    	$smtp_server = $row1['value'];
    	$email_username = $row2['value'];
    	$email_password = $row3['value'];
    	$email_address = $row4['value'];
    	$email_name = $row5['value'];
    	$email_charset = $row6['value'];
    	$require_ssl = $row7['value'];
    	$email_timeout = $row8['value'];
    	$smtp_port = $row9['value'];

    	if ($require_ssl == 1) {
    		$require_ssl = "ssl";
    	} else {
    		$require_ssl = "";
    	}

    	$mail = new phpmailer();
    	$mail->IsSMTP();
    	$mail->PluginDir = "src/phpmailer/";
    	$mail->Mailer = "smtp";
    	$mail->Host = $smtp_server;
    	$mail->Port = $smtp_port;
    	$mail->SMTPAuth = true;
    	$mail->SMTPSecure = $require_ssl;
    	$mail->CharSet = $email_charset;
    	$mail->Username = $email_username;
    	$mail->Password = $email_password;
    	$mail->From = $email_username;
    	$mail->FromName = $email_name;
    	$mail->Timeout = $email_timeout;
    	$mail->AddAddress($email);
    	$mail->Subject = $lang["recover_password"];
    	$mail->Body = $lang["recover_password_text1"].$new_pass.$lang["recover_password_text2"];
    	$mail->AltBody = $lang["recover_password_text1"].$new_pass.$lang["recover_password_text2"];
    	$exito = $mail->Send();

    if($exito == true) {
    	$query = $System->queryDB("UPDATE pl_users SET pass='$new_pass_hash' WHERE email='$email'", $con);
      die('Te hemos mandado tu nueva contraseña por correo electrónico. Inicia sesión con esta haciendo clic <a href="login.php">aquí</a><br><br>Si no le aparece nuestro mensaje, puede haber aparecido por error en la carpeta de Spam o Correo no deseado.');
    } else {
      die("No podemos enviarte la información ahora. Inténtelo más tarde.<br><br>Información avanzada: No podemos conectarnos al servidor SMTP. Si eres el administrador, por favor, revise los parámetros en Ajustes.");
    }

	} else {

		echo '
			<div class="ui_full_width">
            	<div class="ui_head ui_head_width_actions">
					<h1><i class="fa fa-user-plus" style="cursor: default"></i> '.$lang["recover_password"].'</h1>
				</div>
				<form action="recover.php?action=success" method="post">
					<table>
						<tr><td><label for="email">'.$lang["email"].': </label></td><td><input type="email" name="email"></td></tr>
						<tr><td></td><td><input type="submit" value="'.$lang["recover"].'"></td></tr>
					</table>
				</form>
			</div>
		';
	}
		
	?>
</body>
</html>