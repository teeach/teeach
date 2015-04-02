<?php
	include("../../config.php");
	include("../../functions/content.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Timetable</title>
	<link rel="stylesheet" href="../../css/main.css">
	<?php set_head(); ?>
</head>
<body>
	<?php
		$group = $_GET['group'];
		$con = mysqli_connect($dbserver, $dbuser, $dbpass, $database);
		
		echo "<table id='table'>";

		$q = $con->query("select * from pl_config WHERE property='midweek'");
		$r = mysqli_fetch_array($q);
		$midweek = $r['value'];
		if ($midweek = 'true') {
			echo "<tr><th></th><th>Monday</th><th>Tuesday</th><th>Wednesday</th><th>Thursday</th><th>Friday</th></tr>";
		}
		$q2 = $con->query("select * from pl_hours");
		
		$q3 = $con->query("select * from pl_groups");
		$r3 = mysqli_fetch_array($q3);

		while($r2 = mysqli_fetch_array($q2)) {
			echo "<tr><td>".$r2['name']."</td></tr>";
		}

		set_footer();
	?>
</body>
</html>