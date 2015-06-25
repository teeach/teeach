<?php

	include("../../core.php");

	$System = new System;
    $con = $System->conDB("../../config.json");
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
    // CÓDIGO EN PRUEBAS!!! [TEST]
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
			@$showgroups = $_POST['showgroups'];

			if(isset($showgroups)) {
				$showgroups = "true";
			} else {
				$showgroups = "false";
			}

			$query = $con->query("UPDATE pl_settings SET value='$centername' WHERE property='centername'")or die("Query error!");
			$query = $con->query("UPDATE pl_settings SET value='$logo' WHERE property='logo'")or die("Query error!");
			$query = $con->query("UPDATE pl_settings SET value='$accesspass' WHERE property='accesspass'")or die("Query error!");
			$query = $con->query("UPDATE pl_settings SET value='$showgroups' WHERE property='showgroups'")or die("Query error!");

			echo '<a href="settings.php?action">Accept</a>';

		} else {
			
			//Queries
			$query_centername = $con->query("SELECT * FROM pl_settings WHERE property='centername'");
			$query_logo = $con->query("SELECT * FROM pl_settings WHERE property='logo'");
			$query_accesspass = $con->query("SELECT * FROM pl_settings WHERE property='accesspass'");
			$query_sg = $con->query("SELECT * FROM pl_settings WHERE property='showgroups'");

			//Arrays
			$row_centername = mysqli_fetch_array($query_centername);
			$row_logo = mysqli_fetch_array($query_logo);
			$row_accesspass = mysqli_fetch_array($query_accesspass);
			$row_sg = mysqli_fetch_array($query_sg);

			//Values
			$centername = $row_centername['value'];
			$logo = $row_logo['value'];
			$accesspass = $row_accesspass['value'];
			$sg = $row_sg['value'];


			echo '<a href="index.php"><img src="../../src/ico/back.svg" alt="Atrás" class="btn_back"></a><h2><a href="index.php">Admin</a> >> <a href="settings.php?action">'._("Settings").'</a></h2>
				<center>
					<form action="settings.php?action=save" method="post">			
						<div class="contenedor">
						<div id="pestanas">
            				<ul id=lista>
                				<li id="pestana1"><a href="javascript:cambiarPestanna(pestanas,pestana1);">'._("Basic").'</a></li>
                				<li id="pestana4"><a href="javascript:cambiarPestanna(pestanas,pestana4);">'._("Privacy").'</a></li>
                				<li id="pestana3"><a href="javascript:cambiarPestanna(pestanas,pestana3);">'._("Advanced").'</a></li>
                				<li id="pestana2"><a href="javascript:cambiarPestanna(pestanas,pestana2);">'._("About").'</a></li>
            				</ul>
        				</div> 
        				<div id="contenidopestanas">
           					<div id="cpestana1">
                				<label for="centername">'._("Centername").': </label><input type="text" name="centername" value="'.$centername.'"><br>
                				<label for="logo">'._("Logo").': </label><input type="text" name="logo" value="'.$logo.'"><br>
                				<img src="'.$logo.'" alt="logo"><br>
                				<label for="accesspass">'._("Accesspass").': </label><input type="text" name="accesspass" value="'.$accesspass.'">
            				</div>
            				<div id="cpestana2">
            					<b>Teeach</b><br>
            					<p>In Dev</p><br>
                				'._("Server time: ").': '.date("d-m-Y H:i:s").'
            				</div>
            				<div id="cpestana3">
            					<!--<label for="JP">Join a group: </label>
            					<select name="JP">
									<option value="1">'._("Direct").'</option>
									<option value="2">'._("Request").'</option>
									<option value="3">'._("None").'</option>
            					</select>-->
            					<p>Coming soon...</p>
            				</div>
            				<div id="cpestana4">
            				';

            				if ($sg == "true") {
            					echo '<input type="checkbox" name="showgroups" checked>';
            				} else {
            					echo '<input type="checkbox" name="showgroups">';
            				}
            echo '            				
            					<label for="showgroups">'._("Show groups in user profile").'</label>
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