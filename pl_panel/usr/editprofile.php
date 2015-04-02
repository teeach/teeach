<?php
	include('../../core.php'); 
	include('../../usr.php');

	$System = new System();

	if ($_GET['action']=="success") {

		$name = $_POST['name'];
		$subname1 = $_POST['subname1'];
		$subname2 = $_POST['subname2'];
		$email = $_POST['email'];
		$url_img = $_POST['url_img'];

		$System->conDB("../../config.json");

		$query = $con->query("update pl_users set name='$name' where id=$usr_id")or die("Error!");
		$query = $con->query("update pl_users set subname1='$subname1' where id=$usr_id")or die("Error!");
		$query = $con->query("update pl_users set subname2='$subname2' where id=$usr_id")or die("Error!");
		$query = $con->query("update pl_users set email='$email' where id=$usr_id")or die("Error!");
		$query = $con->query("update pl_users set photo='$url_img' where id=$usr_id")or die("Error!");
		header("Location: profile.php?h=".$usr_h);
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
				alert("Project Learn sólo admite las extensíones gif, jpg, png y swg.");
			} else {
				document.getElementById("previsualizar_img").src = url_img;
			}
		}
	</script>
</head>
<body>
	<?php 
		$System->set_header(); 
		$System->set_usr_menu($usr_h,$usr_privilege);
	
		echo '
			<table>
			<form method="post" action="editprofile.php?action=success" name="perfil" style="padding: 10px">
				<tr><td><label for="name">'._("Name: ").'</label></td><td><input type="text" name="name" value="'.$usr_name.'"></td></tr>
				<tr><td>'._("Subnames: ").'</td><td><input type="text" name="subname1" value="'.$usr_subname1.'">, <input type="text" name="subname2" value="'.$usr_subname2.'"></td></tr>
				<tr><td><label for="email">'._("Email: ").'</label></td><td><input type="text" name="email" value="'.$usr_email.'"></td></tr>			
				<tr><td>'._("Password: ").'</td><td><a href="#">Cambiar contraseña</a></td></tr>
				<tr><td>'._("Profile photo: ").'</td><td><input type="text" value="'.$usr_photo.'" name="url_img" onblur="comprobarImagen()"></td></tr>
				<tr><td><img id="previsualizar_img"></td><td></td></tr>
				<tr><td><input type="submit" value="Enviar"></td><td></td></tr>
			</form>
			</table>
		';

	$System->set_footer(); ?>
	<script>comprobarImagen();</script>
</body>
</html>