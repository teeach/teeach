<?php
	include('../../core.php'); 
	include('../../PasswordHash.php');
    
    session_start();
    $h = $_SESSION['h'];

    $System = new System();
    $System->check_usr();
    
    $con = $System->conDB();
    $User = $System->get_user_by_h($h, $con);

    $lang = $System->parse_lang();
    

    if (@$_GET['action']=="success") {
        $error = 0;
		$name = $_POST['name'];
		$surname = $_POST['surname'];
		$email = $_POST['email'];
		$url_img = $_POST['url_img'];
		$lang_val = $_POST['lang'];
        if ($_POST['act_password'] != $_POST['new_password']) {
            
            $t_hasher = new PasswordHash(8, FALSE);
            
            $query = $System->queryDB("SELECT * FROM pl_users WHERE username='$User->username'", $con);
            
            $row = $System-fetch_array($query);
            
            $pass_db = $row['pass'];
            
            $check = $t_hasher->CheckPassword($_POST['act_password'], $pass_db);
            
            if ($check) {
                $t_hasher = new PasswordHash(8, FALSE);
                $pass_hash = $t_hasher->HashPassword($_POST['new_password']);
                $query = $con->queryDB("UPDATE pl_users SET pass='$pass_hash' WHERE id=$User->id", $con);
            }else{
                echo 'Contraseña incorrecta';
                $error += 1;
            }
        }
        if($error == 0){
            $query = $System->queryDB("UPDATE pl_users SET name='$name' WHERE id=$User->id", $con);
            $query = $System->queryDB("UPDATE pl_users SET surname='$surname' WHERE id=$User->id", $con);
            $query = $System->queryDB("UPDATE pl_users SET email='$email' WHERE id=$User->id", $con);
            $query = $System->queryDB("UPDATE pl_users SET photo='$url_img' WHERE id=$User->id", $con);
            $query = $System->queryDB("UPDATE pl_users SET lang='$lang_val' WHERE id=$User->id", $con);
            header("Location: profile.php?h=".$User->h);
        }
	}
    
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title><?php echo $lang['edit_profile']." | Teeach"; ?></title>
	<link rel="stylesheet" href="../../src/css/main.css">
	<?php
		$System = new System();
		$System->set_head(); 
		
	?>
</head>
<body>
	<?php 

		$System->set_header($User->h,$lang);
		
		echo '
			<table>
			<form method="post" action="editprofile.php?action=success" name="perfil" style="padding: 10px">
				<tr><td><label for="name">'.$lang["name"].': </label></td><td><input type="text" name="name" value="'.$User->name.'"></td></tr>
				<tr><td>'.$lang["surname"].': </td><td><input type="text" name="surname" value="'.$User->surname.'"></td></tr>
				<tr><td>'.$lang["language"].'</td><td>
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
				</td></tr>
				<tr><td><label for="email">'.$lang["email"].': </label></td><td><input type="text" name="email" value="'.$User->email.'"></td></tr>			
				<tr><td>'.$lang["change_password"].'<br>'.$lang["not_changed_if"].'</td><td>'.$lang["actual_password"].': <input type="password" name="act_password"></td><td>'.$lang["new_password"].': <input type="password" name="new_password"></td></tr>
				<tr><td>'.$lang["profile_photo"].': </td><td><input id="photo_url" type="text" value="'.$User->photo.'" name="url_img"></td></tr>
				<tr><td><img id="previsualizar_img"></td><td></td></tr>
				<tr><td><input type="submit" value="'.$lang["save"].'"></td><td></td></tr>
			</form>
			</table>
		';

	$System->set_footer(); ?>

    <script>
        $("#photo_url" ).keyup(function() {
            var value = $( this ).val();
            $("#previsualizar_img").show();
            $("#previsualizar_img").attr("src", value);
            $("#previsualizar_img").error(function(){
                $(this).hide();
            });
        })
        .keyup();
    </script>
</body>
</html>
