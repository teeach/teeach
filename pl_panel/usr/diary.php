<?php
	include("../../core.php");
	include("../../usr.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title><?php echo "Hola, $usr_name | Teeach"; ?></title>
	<link rel="stylesheet" href="../../src/css/main.css">
	<?php
		$System = new System();
		$System->set_head(); 
	?>
</head>
<body>
	<?php

	$System = new System();
	$query = $con->query("SELECT * FROM pl_settings WHERE property='centername'");
	$row = mysqli_fetch_array($query);
	$centername = $row['value'];
	$System->set_header($centername);
	$System->set_usr_menu($User->h,$User->privilege);

	echo "<h1>Coming soon</h1>";

	?>
</body>
</html>