<?

	$version =  phpversion();
	$version = explode(".", $version);
	if($version[0] < 5 and $version[1] < 4){
		echo "Tienes una versión incompatible de PHP, porfavor actualiza mínimo a 5.4 (Recomendado 5.6)";
	}else{
		echo "Tienes una versión compatible de PHP";
	}
?>
