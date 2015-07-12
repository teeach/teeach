<?php
	session_start();
	include('../../core.php');
	$System = new System();
	$System->check_usr();
	
	$con = $System->conDB("../../config.json");
	$User = $System->get_user_by_id($_SESSION['h'], $con);

	@$h = $_GET['h'];

	$query = $con->query("SELECT * FROM pl_groups WHERE h='$h'")or die(_("This group doesn't exist."));
	$row = mysqli_fetch_array($query);

	$group_name = $row['name'];
	$groupid = $row['id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php $System->set_head(); ?>
	<title><?php echo _("Groups"); ?> | Teeach</title>
	<link rel="stylesheet" href="../../src/css/main.css">
	<script src="../../ckeditor/ckeditor.js"></script>
	<script>
		//Popups
		function open_popup() {
			$("#dialog").dialog();
		};
	</script>
</head>
<body>
	<?php

		//Popups
			echo '
				<div id="dialog" title="New unit" style="display:none">
					<form method="POST" action="group.php?action=save_unit&h='.@$_GET['h'].'">
						<label for="unit">'._("Name:").' </label><input type="text" name="unit"><br>
						<input type="submit" value="Enviar">
					</form>
				</div>
			';

		$query = $con->query("SELECT * FROM pl_settings WHERE property='centername'");
		$row = mysqli_fetch_array($query);
		$centername = $row['value'];
		$System->set_header($centername);
		$System->set_usr_menu($User->h,$User->privilege);

		if (@$_GET['action'] == "create") {

			echo '
				<h1>'._("Create a group").'</h1>
				<table>
					<form method="POST" action="group.php?action=confirm_create">
						<tr><td><label for="name">'._("Name: ").'</label></td><td><input type="text" name="name"></td></tr>
						<tr><td><label for="category">'._("Category: ").'</label></td><td>
							<select name="category">';
								$query = $con->query("SELECT * FROM pl_categories");
								while($row = mysqli_fetch_array($query)) {
									$name = $row['name'];
									$h = $row['h'];

									echo '<option value="'.$h.'">'.$name.'</option>';
								}
						echo ' </select>
						</td></tr>
						<tr><td></td><td><input type="submit" value="'._("Create").'"></td></tr>
					</form>
				</table>
			';

		} elseif (@$_GET['action'] == "confirm_create") {

			$group_name = $_POST['name'];
			$category_h = $_POST['category'];

			$h = substr( md5(microtime()), 1, 18);

			//Create group
			$query = $con->query("INSERT INTO pl_groups(name,h,category_h) VALUES ('$group_name','$h','$category_h')")or die("Query error!");

			//Create first leader
			$query = $con->query("INSERT INTO pl_groupuser(group_h,user_h,status) VALUES('$h','$User->h','leader')")or die("Query error!");

		} elseif (@$_GET['action'] == "join") {
			echo '
				<h1>'._("Join a group").'</h1>
				<p>'._("Select a group:").' </p>
					';

				$query1 = $con->query("SELECT * FROM pl_categories")or die("Query error!");
				while ($row1 = mysqli_fetch_array($query1)) {
					$category_name = $row1['name'];
					$category_h = $row1['h'];
					echo '
						<h3>'.$category_name.'</h3>
						<ul class="grouplist">
					';

					$query2 = $con->query("SELECT * FROM pl_groups WHERE category_h='$category_h'")or die("Query error!");
					while ($row2 = mysqli_fetch_array($query2)) {
						$groupname = $row2['name'];
						$gh = $row2['h'];
						echo '<a href="group.php?action=send_request&group='.$gh.'"><li>'.$groupname.'</li></a>';
					}
					echo '</ul>';
				}

		} elseif (@$_GET['action'] == "send_request") {

			$gh = $_GET['group'];
			$user_h = $User->h;

			//Check Settings
			$query_settings = $con->query("SELECT * FROM pl_settings WHERE property='JP'");
			$row_settings = mysqli_fetch_array($query_settings);
			$JP = $row_settings['value'];
			
			switch($JP) {
				case 1:
					//Direct ~ No need permission
					$query = $con->query("INSERT INTO pl_groupuser(group_h,user_h,status) VALUES('$gh','$user_h','active')")or die("Query error!");
					echo _("Great! You've joined to group. <a href='group.php?group=".$gh."&page=index'>Enter</a>");
				case 2:
					//Request ~ Need permission
					$query = $con->query("INSERT INTO pl_groupuser(group_h,user_h,status) VALUES('$gh','$user_h','waiting')")or die("Query error!");
					echo _("You've sent a request successfully! <a href='index.php'>Return to Index Page</a>");
					break;
				case 3:
					//Diabled ~ Lock requests
					echo _("The administrator has disabled the activation to groups. Try again later. <a href='index.php'>Accept</a>");
				default:
					//Error ~ Invalid setting
					echo _("Error in table pl_settings The value of JP is invalid!");
			}

		} elseif(@$_GET['action'] == "view") {
			
			$work_h = $_GET['h'];

			$query = $con->query("SELECT * FROM pl_works WHERE h='$work_h'")or die("Query error!");
			$row = mysqli_fetch_array($query);

			$work_name = $row['name'];
			$work_desc = $row['description'];
			$work_type = $row['type'];

			echo '
				<div class="ui_full_width">
            		<div class="ui_head ui_head_width_actions">';

			switch($work_type) {
				case 1:
					// Notes ~ Apuntes
					echo '<h2><i class="fa fa-book"></i> '.$work_name.'</h2>';
					break;
				case 2:
					//Homework ~ Tarea
					echo '<h2><i class="fa fa-pencil"></i> '.$work_name.'</h2>';
					break;
				case 3:
					//Exam ~ Examen
					echo '<h2><i class="fa fa-pencil"></i> '.$work_name.'</h2>';
					break;
				default:
					//Invalid type
					die("Invalid type. Contact to administrator.");

			}
			
			
                		
            echo '    	
            	</div>
					'.$work_desc.'
            	</div>
			';

		} elseif(@$_GET['action'] == "add") {

			$group_h = $_GET['group'];
			$user_h = $_GET['user'];

			//ID Group
			$querygroup = $con->query("SELECT * FROM pl_groups WHERE h='$group_h'")or die("Query error!");
			$rowgroup = mysqli_fetch_array($querygroup);
			$group_id = $rowgroup['id'];

			//ID User
			$queryuser = $con->query("SELECT * FROM pl_users WHERE h='$user_h'")or die("Query error!");
			$rowuser = mysqli_fetch_array($queryuser);
			$user_id = $rowuser['id'];

			//Add
			$queryadd = $con->query("INSERT INTO pl_groupuser(groupid,userid) VALUES($group_id,$user_id)")or die("Query error!");

			echo '<a href="group.php?h='.$group_h.'">Accept</a>';

		} elseif(@$_GET['action'] == "quit") {

			$group_h = $_GET['group'];
			$user_h = $_GET['user'];

			$query = $con->query("DELETE FROM pl_groupuser WHERE group_h='$group_h' AND user_h='$user_h'")or die("Query error!");
			
			echo '<a href="group.php?h='.$group_h.'&page=users">Accept</a>';

		} elseif(@$_GET['action'] == "new_work") {

			//Group Hash
			$gh = $_GET['h'];
			$unit_h = $_GET['unit'];

			$query = $con->query("SELECT * FROM pl_units WHERE h='$unit_h'")or die("Query error!");
			$row = mysqli_fetch_array($query);
			$unit_name = $row['name'];

			echo '
			<div class="ui_full_width">
			<div class="ui_head ui_head_width_actions">
				<h2>'.("New work in ").$unit_name.'</h2>
			</div>
			<form action="group.php?action=save_work&h='.$gh.'&unit='.$unit_h.'" method="POST">
				<table>
					<tr><td><label for="workname">'._("Workname").'</label></td><td><input type="text" name="workname"></td></tr>
					<tr><td><label for="type">'._("Type").'</label></td><td>
						<select name="type">
							<option value="1">'._("Notes").'</option>
							<option value="2">'._("Homework").'</option>
							<option value="3">'._("Exam").'</option>
						</select>
					</td></tr>
					<tr><td><label for="visible">'._("Visible").'</label></td><td><input type="checkbox" name="visible" checked="true"></td></tr>
					<tr><td></td><td><textarea cols="80" id="editor1" name="desc" rows="10"></textarea></td></tr>
					<tr><td></td><td><input type="submit" value='._("Accept").'></td></tr>
				</table>
			</form>
			</div>
			
			<script type="text/javascript">  
                CKEDITOR.replace( "editor1", { 
                enterMode: CKEDITOR.ENTER_BR,
                skin : "office2013",
                toolbar : [
                    { name: "document", groups: [ "mode", "document", "doctools" ], items: [ "Source", "-", "Save", "Preview", "-", "Templates" ] },
                    { name: "clipboard", groups: ["undo"], items: ["Undo", "Redo" ] },
                    { name: "editing", groups: [ "find", "selection"], items: ["Replace", "-", "SelectAll"] },
                    "/",
                    { name: "basicstyles", groups: [ "basicstyles", "cleanup" ], items: [ "Bold", "Italic", "Underline", "Strike", "Subscript", "Superscript", "-", "RemoveFormat" ] },
                    { name: "paragraph", groups: [ "list", "indent", "blocks", "align"], items: [ "NumberedList", "BulletedList", "-", "Outdent", "Indent", "-", "Blockquote", "CreateDiv", "-", "JustifyLeft", "JustifyCenter", "JustifyRight", "JustifyBlock" ] },
                    "/",
                    { name: "links", items: [ "Link", "Unlink" ] },
                    { name: "insert", items: [ "Image", "Flash", "Table", "HorizontalRule", "Smiley", "SpecialChar", "Iframe" ] },
                    "/",
                    { name: "styles", items: [ "Styles", "Format", "Font", "FontSize" ] },
                    { name: "colors", items: [ "TextColor", "BGColor" ] },
                    { name: "tools", items: [ "Maximize"] }
                ]
                });      
        </script>
			';

		} elseif(@$_GET['action'] == "save_work") {

			//Group Hash
			$gh = $_GET['h'];

			$unit_h = $_GET['unit'];

			$workname = $_POST['workname'];
			$type = $_POST['type'];
			$desc = $_POST['desc'];
			$visible = $_POST['visible'];

			if($visible == "on") {
				$status = "visible";
			} else {
				$status = "invisible";
			}

			$h = substr( md5(microtime()), 1, 18);
			$date = date("Y-m-d H:i:s");

			$query = $con->query("INSERT INTO pl_works(name,type,h,creation_date,description,unit_h,status) VALUES('$workname',$type,'$h','$date','$desc','$unit_h','$status')")or die("Query error!");

			echo '<a href="group.php?h='.$gh.'&page=index">'._("Accept").'</a>';

		} elseif(@$_GET['action'] == "save_unit") {

			$unit = $_POST['unit'];
			$h = substr( md5(microtime()), 1, 18);
			$gh = $_GET['h'];

			$query = $con->query("INSERT INTO pl_units(name,h,group_h) VALUES ('$unit','$h','$gh')")or die("Query error!");

			echo "<a href='group.php?h=".$gh."&page=index'>Accept</a>";



		} elseif(@$_GET['page'] == "index") {

			$gh = $_GET['h'];
			$query = $con->query("SELECT * FROM pl_groups WHERE h='$gh'")or die("Query error!");
			$row = mysqli_fetch_array($query);
			$groupname = $row['name'];

			$privilege = $User->privilege;

			if ($privilege >= 2) {
				echo '
					
				';
			}

				echo '				
					<div class="ui_full_width">
            <div class="ui_head ui_head_width_actions">
                <h2><i class="fa fa-users"></i> '.$groupname.'</h2>';


                if ($privilege >= 2) {  
              		echo '
                	<div class="ui_actions">
                		<a href="group.php?action=new_work&h='.$gh.'"><button class="ui_action" class="ui_tooltip" title="'._("New Work").'"><i class="fa fa-plus"></i></button></a>
                	</div>
                	';
            	}
            echo '
            	</div>
            	<div class="ui_sidebar left">
                <nav class="ui_vertical_nav">
                    <ul>
                        <li class="active"><a href="group.php?h='.$gh.'&page=index">'._("Works").'</a></li>
                        <li><a href="group.php?h='.$gh.'&page=users">'._("Users").'</a></li>
                    </ul>
                </nav>                            
            	</div>
            
            <div class="ui_width_sidebar right">
			';

			echo '<ul class="units">';

			$query1 = $con->query("SELECT * FROM pl_units WHERE group_h='$gh'")or die("Query error!");
			while ($row1 = mysqli_fetch_array($query1)) {
				$unit_h = $row1['h'];
				$unit_name = $row1['name'];

				echo '
					<li class="unit">'.$unit_name.'</li>
					<ul class="works">
				';

				$query2 = $con->query("SELECT * FROM pl_works WHERE unit_h='$unit_h'")or die("Query error!");
				while ($row2 = mysqli_fetch_array($query2)) {					
					$work_h = $row2['h'];
					$work_name = $row2['name'];
					$work_desc = $row2['description'];
					$work_type = $row2['type'];

					echo '<a href="group.php?action=view&h='.$work_h.'"><li class="work">'.$work_name.'</li></a>';
				}

				echo '
					<a href="group.php?action=new_work&h='.$h.'&unit='.$unit_h.'"><li class="new_work">'._("New Work").'</li></a>
				</ul>';
			}

			echo '
				<a onclick="open_popup()"><li class="new_unit">'._("New unit").'</li></a>
			</ul>';

			

		} elseif(@$_GET['page'] == "users") {

			$gh = $_GET['h'];

			$query_status = $con->query("SELECT * FROM pl_groupuser WHERE user_h='$User->h'")or die("Query error!");
			$row_status = mysqli_fetch_array($query_status);
			$status = $row_status['status'];

			$query1 = $con->query("SELECT * FROM pl_groups WHERE h='$gh'")or die("Query error!");
			$row1 = mysqli_fetch_array($query1);
			$groupname = $row1['name'];

			echo '

			<div class="ui_full_width">
            <div class="ui_head ui_head_width_actions">
                <h2><i class="fa fa-users"></i> '.$groupname.'</h2>
                <div class="ui_actions">
                    <a href="group.php?action=add&h='.$gh.'"><button class="ui_action" class="ui_tooltip" title="Add New"><i class="fa fa-plus"></i></button></a>
                    <!--<button class="ui_action" class="ui_tooltip" title="Remove Selections"><i class="fa fa-trash"></i></button>-->
                </div>
            </div>
            <div class="ui_sidebar left">
                <nav class="ui_vertical_nav">
                    <ul>
                        <li><a href="group.php?h='.$gh.'&page=index">'._("Works").'</a></li>
                        <li class="active"><a href="group.php?h='.$gh.'&page=users">'._("Users").'</a></li>
                    </ul>
                </nav>
            </div>
            
            <div class="ui_width_sidebar right">
                
                <table class="ui_table">
                <thead>
                    <th class="select"><input class="select_all" type="checkbox" /></th>
                    <th>'._("Name and surname").'</th>
                    <th>'._("Email").'</th>
                    <th>'._("Address").'</th>
                    <th>'._("Phone").'</th>
                    <th class="actions">'._("Actions").'</th>
                </thead>
                <tbody>';
                $query = $con->query("SELECT * FROM pl_groupuser WHERE group_h='$gh'");
				while ($row = mysqli_fetch_array($query)) {
					$user_h = $row['user_h'];
					$query2 = $con->query("SELECT * FROM pl_users WHERE h='$user_h'");
					$row2 = mysqli_fetch_array($query2);

					$name = $row2['name'];
					$surname = $row2['surname'];
					$email = $row2['email'];
					$address = $row2['address'];
					$phone = $row2['phone'];

					echo '<tr><td><input type="checkbox"></td>';

					if ($row['status'] == "leader") {
						echo '<td><a style="color:#0B0B3B; font-weight: bold " href="profile.php?h='.$user_h.'">'.$name." ".$surname.'</a></td>';
					} else {
						echo '<td><a href="profile.php?h='.$user_h.'">'.$name." ".$surname.'</a></td>';
					}

					echo '<td>'.$email.'</td><td>'.$address.'</td><td>'.$phone.'</td><td><a href="messages.php?action=new&to='.$user_h.'"><i class="fa fa-envelope"></i></a>';

					if ($status == "leader") {
						echo ' <a href="group.php?action=quit&group='.$gh.'&user='.$user_h.'"><i class="fa fa-eraser"></i></a>';
					}
					
					echo '</td></tr>';
				}

			echo '
				</tbody>
				</table>
			';
		}
		
	?>		

	<?php $System->set_footer(); ?>
</body>
</html>