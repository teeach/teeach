<?php
	include("../../core.php");
	include("../../usr.php");

	$System = new System;
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title><?php echo _("Settings"); ?> | Teeach</title>
	<link rel="stylesheet" href="../../src/css/main.css" />
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'/>
	<?php $System->set_head(); ?>
	<script>
		// Dadas la division que contiene todas las pestañas y la de la pestaña que se 
		// quiere mostrar, la funcion oculta todas las pestañas a excepcion de esa.
		function cambiarPestanna(pestannas,pestanna) {
    
    	// Obtiene los elementos con los identificadores pasados.
    	pestanna = document.getElementById(pestanna.id);
    	listaPestannas = document.getElementById(pestannas.id);
    
    	// Obtiene las divisiones que tienen el contenido de las pestañas.
    	cpestanna = document.getElementById('c'+pestanna.id);
    	listacPestannas = document.getElementById('contenido'+pestannas.id);
    
		i=0;
    	// Recorre la lista ocultando todas las pestañas y restaurando el fondo 
    	// y el padding de las pestañas.
    	while (typeof listacPestannas.getElementsByTagName('div')[i] != 'undefined'){
        	$(document).ready(function(){
           		$(listacPestannas.getElementsByTagName('div')[i]).css('display','none');
            	$(listaPestannas.getElementsByTagName('li')[i]).css('background','');
            	$(listaPestannas.getElementsByTagName('li')[i]).css('padding-bottom','');
        });
        i += 1;
    	}
 
   		$(document).ready(function(){
        // Muestra el contenido de la pestaña pasada como parametro a la funcion,
        // cambia el color de la pestaña y aumenta el padding para que tape el  
        // borde superior del contenido que esta juesto debajo y se vea de este 
        // modo que esta seleccionada.
        $(cpestanna).css('display','');
        $(pestanna).css('background','#fff');
    }); 
}
	</script>
</head>
<body onload="javascript:cambiarPestanna(pestanas,pestana1);">

	<?php
		$action = $_GET['action'];

		if ($action == "save") {
			
			$centername = $_POST['centername'];
			$logo = $_POST['logo'];
			$accesspass = $_POST['accesspass'];

			$query = $con->query("update pl_settings set value='$centername' where property='centername'")or die("Query error!");
			$query = $con->query("update pl_settings set value='$logo' where property='logo'")or die("Query error!");
			$query = $con->query("update pl_settings set value='$accesspass' where property='accesspass'")or die("Query error!");

			echo '<a href="settings.php?action">Accept</a>';

		} else {
			
			//Queries
			$query_centername = $con->query("select * from pl_settings where property='centername'");
			$query_logo = $con->query("select * from pl_settings where property='logo'");
			$query_accesspass = $con->query("select * from pl_settings where property='accesspass'");

			//Arrays
			$row_centername = mysqli_fetch_array($query_centername);
			$row_logo = mysqli_fetch_array($query_logo);
			$row_accesspass = mysqli_fetch_array($query_accesspass);

			//Values
			$centername = $row_centername['value'];
			$logo = $row_logo['value'];
			$accesspass = $row_accesspass['value'];


			echo '<a href="index.php"><img src="../../src/ico/back.svg" alt="Atrás" class="btn_back"></a><h2><a href="index.php">Admin</a> >> <a href="settings.php?action">'._("Settings").'</a></h2>
				<center>
					<form action="settings.php?action=save" method="post">			
						<div class="contenedor">
						<div id="pestanas">
            				<ul id=lista>
                				<li id="pestana1"><a href="javascript:cambiarPestanna(pestanas,pestana1);">'._("Basic").'</a></li>
                				<li id="pestana2"><a href="javascript:cambiarPestanna(pestanas,pestana2);">'._("About").'</a></li>
            				</ul>
        				</div> 
        				<div id="contenidopestanas">
           					<div id="cpestana1">
                				<label for="centername">Centername: </label><input type="text" name="centername" value="'.$centername.'"><br>
                				<label for="logo">Logo: </label><input type="text" name="logo" value="'.$logo.'"><br>
                				<img src="'.$logo.'" alt="logo"><br>
                				<label for="accesspass">Accesspass: </label><input type="text" name="accesspass" value="'.$accesspass.'">
            				</div>
            				<div id="cpestana2">
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