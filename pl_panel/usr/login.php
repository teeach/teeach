<?php
	session_start();
	include("../../core.php");
	$System = new System();
	$con = $System->conDB("../../config.json");
	$lang = $System->parse_lang("../../src/lang/".$System->load_locale().".json");

	// Check login method
	//
	// 1 ~ Email or username
	// 2 ~ Only email
	// 3 ~ Only username

	$query = $con->query("SELECT * FROM pl_settings WHERE property='login_method'")or die("Query error!");
	$row = mysqli_fetch_array($query);
	$login_method = $row['value'];

	if (@$_GET['action']=="check") {
		require '../../PasswordHash.php';
		$user = $_POST['username'];
		$pass = $_POST['password'];

		$t_hasher = new PasswordHash(8, FALSE);

		if($login_method == "1") {
			$query = $con->query("SELECT * FROM pl_users WHERE username='$user' OR email='$user'")or die("Query error!");
		} elseif($login_method == "2") {
			$query = $con->query("SELECT * FROM pl_users WHERE email='$user'")or die("Query error!");
		} else {
			$query = $con->query("SELECT * FROM pl_users WHERE username='$user'")or die("Query error!");
		}

		
		$row = mysqli_fetch_array($query);
		$user_h = $row['h'];
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
	
	// ==== LOGIN FORM ==== //
	
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

            	<?php
            		if($login_method == "1") {
            			echo '<label class="username"><input type="text" id="usuario" name="username" placeholder="'.$lang["email_or_user"].'" required></label>';
            		} elseif($login_method == "2") {
            			echo '<label class="username"><input type="text" id="usuario" name="username" placeholder="'.$lang["email"].'" required></label>';
            		} else {
            			echo '<label class="username"><input type="text" id="usuario" name="username" placeholder="'.$lang["username"].'" required></label>';
            		}
            	?>
            	
                <label class="password"><input type="password" id="pass" name="password" placeholder="<?php echo $lang["password"];?>"  /></label>
                <input type="submit" value="<?php echo $lang["log_in"];?>" />
            </form>
            <a class="extra_link" href="register.php"><?php echo $lang["create_account"]."&raquo"; ?></a>
            <a class="extra_link" href="recover.php"><?php echo $lang["forgot_password"]."&raquo"; ?></a>
        </div>
    </div>

	<?php $System->set_footer(); ?>

</body>
</html>
