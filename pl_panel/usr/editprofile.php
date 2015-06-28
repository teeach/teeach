<?php
	include('../../core.php'); 
	include('../../PasswordHash.php');
    
    session_start();
    $h = $_SESSION['h'];

    $System = new System();
    $connection = $System->conDB("../../config.json");
    $User = $System->get_user_by_id($h, $connection);

    if (@$_GET['action']=="success") {
        $error = 0;
		$name = $_POST['name'];
		$surname = $_POST['surname'];
		$email = $_POST['email'];
		$url_img = $_POST['url_img'];
        if($_POST['act_password'] != $_POST['new_password']){
            
            $t_hasher = new PasswordHash(8, FALSE);
            
            $query = $connection->query("select * from pl_users where username='$User->username'") or die ("Error!");
            
            $row = mysqli_fetch_array($query);
            
            $pass_db = $row['pass'];
            
            $check = $t_hasher->CheckPassword($_POST['act_password'], $pass_db);
            
            if ($check) {
                $t_hasher = new PasswordHash(8, FALSE);
                $pass_hash = $t_hasher->HashPassword($_POST['new_password']);
                $query = $connection->query("update pl_users set pass='$pass_hash' where id=$User->id")or die("Error!");
            }else{
                echo 'Contraseña incorrecta';
                $error += 1;
            }
        }
        if($error == 0){
            $query = $connection->query("update pl_users set name='$name' where id=$User->id")or die("Error!");
            $query = $connection->query("update pl_users set surname='$surname' where id=$User->id")or die("Error!");
            $query = $connection->query("update pl_users set email='$email' where id=$User->id")or die("Error!");
            $query = $connection->query("update pl_users set photo='$url_img' where id=$User->id")or die("Error!");
            //~ header("Location: profile.php?h=".$User->h);
        }
	}
    
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title><?php echo "Editar perfil | Project Learn"; ?></title>
	<link rel="stylesheet" href="../../src/css/main.css">
	<?php
		$System = new System();
		$System->set_head(); 
	?>
</head>
<body>
	<?php 
		$query = $connection->query("SELECT * FROM pl_settings WHERE property='centername'");
		$row = mysqli_fetch_array($query);
		$centername = $row['value'];
		$System->set_header($centername);
		$System->set_usr_menu($User->h,$User->privilege);
	
		echo '
			<table>
			<form method="post" action="editprofile.php?action=success" name="perfil" style="padding: 10px">
				<tr><td><label for="name">'._("Name: ").'</label></td><td><input type="text" name="name" value="'.$User->name.'"></td></tr>
				<tr><td>'._("Surname: ").'</td><td><input type="text" name="surname" value="'.$User->surname.'"></td></tr>
				<tr><td><label for="email">'._("Email: ").'</label></td><td><input type="text" name="email" value="'.$User->email.'"></td></tr>			
				<tr><td>'._("Change password <br>(Not changed if left blank): ").'</td><td>Actual password: <input type="password" name="act_password"></td><td>New password: <input type="password" name="new_password"></td></tr>
				<tr><td>'._("Profile photo: ").'</td><td><input id="photo_url" type="text" value="'.$User->photo.'" name="url_img"></td></tr>
				<tr><td><img id="previsualizar_img"></td><td></td></tr>
				<tr><td><input type="submit" value="Enviar"></td><td></td></tr>
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