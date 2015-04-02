<?php

	function check_ip() {
		echo "<h1>Comprobar identidad</h1>";
		echo "Antes de continuar, debemos comprobar su identidad. Por favor, inserta el PIN que recibió en su centro.";
		echo "<form action='psecurity.php' method='POST'>PIN: <input type='password' name='pin' id='pin'><br><input type='submit' value='Enviar'></form>";
		
	}

?>