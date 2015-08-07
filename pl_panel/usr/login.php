<?php
	if (@$_GET['action']=="check") {
		require '../../PasswordHash.php';
		$usuario = $_POST['username'];
		$pass = $_POST['password'];

		$t_hasher = new PasswordHash(8, FALSE);

		$fp = fopen("../../config.json", "r");
		$file = fread($fp, filesize("../../config.json"));

		$json = json_decode($file);

		$dbserver = $json->{'dbserver'};
		$dbuser = $json->{'dbuser'};
		$dbpass = $json->{'dbpass'};
		$database = $json->{'database'};

		$con = mysqli_connect($dbserver, $dbuser, $dbpass, $database);
		$query = $con->query("SELECT * FROM pl_users WHERE username='$usuario'");
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
	$System = new System();
	$lang = $System->parse_lang("../../src/lang/".$System->load_locale().".json");
	$System->set_head();
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
	<title><?php echo $lang["log_in"]; ?> | Teeach</title>

</head>

<body>	
	<div class="main">
        <?php
            if(isset($_GET['err'])) {
                $err = $_GET['err'];
                if($err == "autf") {
                    echo '<div class="notification"><p>'.$lang["username_password_incorrect"].'</p></div>';
                }
            }
        ?>	
        <div class="container">
            <h1><?php echo $lang["log_in"]; ?></h1>
            
            <form class="login" method="POST" action="login.php?action=check" autocomplete="off">
                <label class="username"><input type="text" id="usuario" name="username" placeholder="<?php echo $lang["username"];?>" required></label>
                <label class="password"><input type="password" id="pass" name="password" placeholder="<?php echo $lang["password"];?>"  /></label>
                <input type="submit" value="<?php echo $lang["log_in"];?>" />
            </form>
            <a class="extra_link" href="register.php"><?php echo $lang["create_account"]."&raquo"; ?></a>
        </div>
    </div>

	<?php $System->set_footer(); ?>

</body>
</html>
