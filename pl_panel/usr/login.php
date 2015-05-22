<?php
	if (@$_GET['action']=="check") {
		require '../../PasswordHash.php';
		$usuario = $_POST['usuario'];
		$pass = $_POST['pass'];

		$t_hasher = new PasswordHash(8, FALSE);

		$fp = fopen("../../config.json", "r");
		$file = fread($fp, filesize("../../config.json"));

		$json = json_decode($file);

		$dbserver = $json->{'dbserver'};
		$dbuser = $json->{'dbuser'};
		$dbpass = $json->{'dbpass'};
		$database = $json->{'database'};	

		$con = mysqli_connect($dbserver, $dbuser, $dbpass, $database);
		$query = $con->query("select * from pl_users where username='$usuario'");
		$row = mysqli_fetch_array($query);
			$userid = $row['id'];
			$pass_db = $row['pass'];

		$check = $t_hasher->CheckPassword($pass, $pass_db);

		if ($check) {
			session_start();
			$_SESSION['h'] = $row['h'];			
			//header('Location: index.php');
		} else {
			header('Location: login.php?err=autf');
		}
	}

?>

<?php
	session_start();
	include('../../core.php');
	if (isset($_SESSION['h'])) {
		$user_h = $row['h'];
		$time = date("Y-m-d H:i:s");
		$query_last_time = $con->query("UPDATE pl_users SET last_time='$time' WHERE h='$user_h'")or die("Query error!");
		header('Location: index.php');
	}
?>




<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title><?php echo _("Log in"); ?> | Teeach</title>
	<?php
		$System = new System();
		$System->set_head();
	?>
</head>

<body>	
	<div class="main">
        <?php
            if(isset($_GET['err'])) {
                $err = $_GET['err'];
                if($err == "autf") {
                    echo '<div class="message"><p>'._("Username or password are incorrect.").'</p></div>';
                }
            }
        ?>	
        <div class="container">
            <h1><?php echo _("Log in"); ?></h1>
            
            <form class="login" method="POST" action="login.php?action=check" autocomplete="off">
                <label class="username"><input type="text" id="usuario" name="usuario" placeholder="<?php echo _("Username");?>" required></label>
                <label class="password"><input type="password" id="pass" name="pass" placeholder="<?php echo _("Password");?>"  /></label> 
                <input type="submit" value="<?php echo _("Log in");?>" />
            </form>
            <a class="extra_link" href="register.php"><?php echo _("Create acount &raquo;"); ?></a>
        </div>  
    </div>

	<?php $System->set_footer(); ?>

</body>
</html>