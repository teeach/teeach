<?php
	include('../../core.php'); 
	//~ include('../../usr.php');

    if ($_GET['action']=="success") {

		$name = $_POST['name'];
		$subname1 = $_POST['subname1'];
		$subname2 = $_POST['subname2'];
		$email = $_POST['email'];
		$url_img = $_POST['url_img'];

		$query = $con->query("update pl_users set name='$name' where id=$User->id")or die("Error!");
		$query = $con->query("update pl_users set subname1='$subname1' where id=$usr_id")or die("Error!");
		$query = $con->query("update pl_users set subname2='$subname2' where id=$usr_id")or die("Error!");
		$query = $con->query("update pl_users set email='$email' where id=$usr_id")or die("Error!");
		$query = $con->query("update pl_users set photo='$url_img' where id=$usr_id")or die("Error!");
		header("Location: profile.php?h=".$usr_h);
	}

    session_start();
    $h = $_SESSION['h'];

    $System = new System();
    $connection = $System->conDB("../../config.json");
    $User = $System->get_user_by_id($h, $connection);
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
<!--
	<script>
		function comprobarImagen() {
			var url_img = document.perfil.url_img.value;
			var ext_permitidas = new Array(".gif", ".jpg",".jpeg",".png",".swg");
			var ext = (url_img.substring(url_img.lastIndexOf("."))).toLowerCase();
			var permitida = false;
			for (var i=0; i < ext_permitidas.length; i++) {
				if(ext_permitidas[i]==ext) {
					permitida = true;
					break;
				}
			}
			if(!permitida) {
				alert("Project Learn sólo admite las extensiones gif, jpg, png y swg.");
			} else {
				document.getElementById("previsualizar_img").src = url_img;
			}
		}
	</script>
-->
</head>
<body>
	<?php 
		$System->set_header(); 
		$System->set_usr_menu($usr_h,$usr_privilege);
	
		echo '
			<table>
			<form method="post" action="editprofile.php?action=success" name="perfil" style="padding: 10px">
				<tr><td><label for="name">'._("Name: ").'</label></td><td><input type="text" name="name" value="'.$User->name.'"></td></tr>
				<tr><td>'._("Subnames: ").'</td><td><input type="text" name="subname1" value="'.$User->surname1.'">, <input type="text" name="subname2" value="'.$User->surname2.'"></td></tr>
				<tr><td><label for="email">'._("Email: ").'</label></td><td><input type="text" name="email" value="'.$User->email.'"></td></tr>			
				<tr><td>'._("Password: ").'</td><td><a href="#">Cambiar contraseña</a></td></tr>
				<tr><td>'._("Profile photo: ").'</td><td><input id="photo_url" type="text" value="'.$User->photo.'" name="url_img"></td></tr>
				<tr><td><img src="" id="previsualizar_img"></td><td></td></tr>
				<tr><td><input type="submit" value="Enviar"></td><td></td></tr>
			</form>
			</table>
		';

	$System->set_footer(); ?>
<!--
	<script>comprobarImagen();</script>
-->
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
