<?php
	include("../../core.php");
	$System = new System();
	$System->check_admin();
	$lang = $System->parse_lang();
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Admin | Teeach</title>
	<link rel="stylesheet" href="../../src/css/main.css" />
	<link rel="stylesheet" href="../../src/css/admin.css">
	<?php $System->set_head(); ?>
</head>
<body>
	<header id="header" class="admin">
            <div class="main_title">
                <h1><i class="fa fa-wrench"></i> <?php echo $lang["administration"]; ?></h1>
            </div>
                    
            <a class="goto_user_panel" href="../usr"><?php echo $lang["return_usr_panel"]; ?></a>
        </header>
        
        <div class="admin_panel">
            <div class="admin_panel_item">
                <a href="users.php?action">
                    <i class="fa fa-user"></i>
                    <h2><?php echo $lang['users']; ?></h2>
                </a>
            </div>
            <div class="admin_panel_item">
                <a href="groups.php?action">
                    <i class="fa fa-users"></i>
                    <h2><?php echo $lang['groups']; ?></h2>
                </a>
            </div>
            <div class="admin_panel_item">
                <a href="posts.php?action">
                    <i class="fa fa-pencil"></i>
                    <h2><?php echo $lang['posts']; ?></h2>
                </a>
            </div>
            <div class="admin_panel_item">
                <a href="settings.php?action">
                    <i class="fa fa-cog"></i>
                    <h2><?php echo $lang['settings']; ?></h2>
                </a>
            </div>
        </div>
</body>
</html>