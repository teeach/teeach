<?php
	session_start();
	include('../../core.php');
	$System = new System();
	$System->check_usr();
	
	$con = $System->conDB("../../config.json");
	$User = $System->get_user_by_id($_SESSION['h'], $con);

	$lang = $System->parse_lang("../../src/lang/".$System->load_locale().".json");

	$user_h = $_SESSION['h'];

	@$h = $_GET['h'];

	if(isset($_GET['h'])) {		
		$query = $con->query("SELECT * FROM pl_groupuser WHERE group_h='$h' AND user_h='$user_h' AND status!='waiting'")or die("Query error!");
		if(!$row = mysqli_fetch_array($query)) {
			die($lang["not_permission"]);
		}
	}

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
	<title><?php echo $lang["groups"]; ?> | Teeach</title>
	<link rel="stylesheet" href="../../src/css/main.css">
	<script src="../../src/ckeditor/ckeditor.js"></script>
	<script>
		//Popups
		function open_popup() {
			$("#dialog").dialog();
		};

		$(document).on("ready", function() {
			$('#new_category_button').on("click", function() {
				$('#category').fadeOut(350);
				$('#new_category').fadeIn(350);
			});
		});
	</script>
</head>
<body>
	<?php

		//Popups
			echo '
				<div id="dialog" title="'.$lang["new_unit"].'" style="display:none">
					<form method="POST" action="group.php?action=save_unit&h='.@$_GET['h'].'">
						<label for="unit">'.$lang["name"].': </label><input type="text" name="unit"><br>
						<input type="submit" value="'.$lang["save"].'">
					</form>
				</div>
			';

		$query = $con->query("SELECT * FROM pl_settings WHERE property='centername'");
		$row = mysqli_fetch_array($query);
		$centername = $row['value'];
		$System->set_header($centername);
		$System->set_usr_menu($User->h,$User->privilege,$lang);

		if (@$_GET['action'] == "create") {

			echo '
				<h1>'.$lang["create_a_group"].'</h1>
				<table>
					<form method="POST" action="group.php?action=confirm_create">
						<tr><td><label for="name">'.$lang["name"].': </label></td><td><input type="text" name="name"></td></tr>
						<tr><td><label for="category">'.$lang["category"].': </label></td><td>
							<div id="category"><select name="category">';
								$query = $con->query("SELECT * FROM pl_categories");
								while($row = mysqli_fetch_array($query)) {
									$name = $row['name'];
									$h = $row['h'];

									echo '<option value="'.$h.'">'.$name.'</option>';
								}
						echo ' </select>';

								$query = $con->query("SELECT * FROM pl_settings WHERE property='allow_create_categories'");
								$row_acc = mysqli_fetch_array($query);
								$allow_create_categories = $row_acc['value'];

								if ($allow_create_categories == "true") {
									echo ' '.$lang["or_create_a"].' <a href="#" id="new_category_button">'.$lang["new_min"].'</a>.</div><div id="new_category" style="display:none"><input type="text" name="new_category" placeholder="'.$lang["new_category"].'"></div>';
								} else {
									echo '</div>';
								}

						echo '								

						</td></tr>
						<tr><td></td><td><input type="submit" value="'.$lang["create"].'"></td></tr>
					</form>
				</table>
			';

		} elseif (@$_GET['action'] == "confirm_create") {

			$group_name = $_POST['name'];
			$category_h = $_POST['category'];

			if($_POST['new_category'] != "") {
				$new_category_h = substr( md5(microtime()), 1, 18);
				$category_name = $_POST['new_category'];
				$query = $con->query("INSERT INTO pl_categories(name,h) VALUES('$category_name','$new_category_h')")or die("Query error!");
				$category_h = $new_category_h;
			}

			$h = substr( md5(microtime()), 1, 18);

			//Create group
			$query = $con->query("INSERT INTO pl_groups(name,h,category_h) VALUES ('$group_name','$h','$category_h')")or die("Query error!");

			//Create first leader
			$query = $con->query("INSERT INTO pl_groupuser(group_h,user_h,status) VALUES('$h','$User->h','leader')")or die("Query error!");

			echo '<script>location.href="group.php?h='.$h.'&page=index"</script>';

		} elseif (@$_GET['action'] == "join") {
			echo '
				<h1>'.$lang["join_group"].'</h1>
				<p>'.$lang["select_group"].' </p>
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

			$query = $con->query("SELECT * FROM pl_groupuser WHERE user_h='$user_h'")or die("Query error!");
			while ($row = mysqli_fetch_array($query)) {
				if($gh == $row['group_h']) {
					die($lang["already_group"]." <a href='index.php'><button>".$lang["accept"]."</button></a>");
				}
			}

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
			$Work = $System->get_work_by_h($work_h, $con);
			$attachment_json = $Work->attachment;

			$query_status = $con->query("SELECT * FROM pl_groupuser WHERE user_h='$User->h'")or die("Query error!");
			$row_status = mysqli_fetch_array($query_status);
			$status = $row_status['status'];

			echo '
				<div class="ui_full_width">
            		<div class="ui_head ui_head_width_actions">';

            if($Work->status == "invisible") {
            	echo '<h2><i class="fa fa-eye-slash"></i> '.$Work->name.'</h2>';
            } else {
            	switch($Work->type) {
					case 1:
						// Notes ~ Apuntes
						echo '<h2><i class="fa fa-book"></i> '.$Work->name.'</h2>';
						break;
					case 2:
						//Homework ~ Tarea
						echo '<h2><i class="fa fa-pencil"></i> '.$Work->name.'</h2>';
						break;
					case 3:
						//Exam ~ Examen
						echo '<h2><i class="fa fa-pencil"></i> '.$Work->name.'</h2>';
						break;
					default:
						//Invalid type
						die("Invalid type. Contact to administrator.");
				}
            }

			if ($status == "leader") {
				echo '<div class="ui_actions">';
				if($Work->type != 1) {
					if($Work->status == "visible") {
						echo '<a href="group.php?action=edit_work&h='.$Work->h.'"><button class="ui_action" class="ui_tooltip" title="Edit"><i class="fa fa-hourglass-end"></i> '.$lang["finish_term"].'</button></a>';
					}
				}
				
				echo '					
                    	<a href="group.php?action=edit_work&h='.$Work->h.'"><button class="ui_action" class="ui_tooltip" title="Edit"><i class="fa fa-pencil"></i> '.$lang["edit"].'</i></button></a>
            		</div>
				';
			}

			echo '					
				</div>

				<div class="ui_sidebar left">
                	<nav class="ui_vertical_nav">
                    	<ul>
                       		<li><a href="group.php?h='.$Work->group_h.'&page=index">'.$lang["works"].'</a></li>
                       		<li><a href="group.php?h='.$Work->group_h.'&page=users">'.$lang["users"].'</a></li>';

                       		if($status == "leader") {
                       			echo '<li><a href="group.php?h='.$Work->group_h.'&page=requests">'.$lang["requests"].'</a></li>';
                       		}
                       		
                       		echo '
                    	</ul>
                	</nav>
            	</div>

            	<div class="ui_sidebar right">
			';
			
			$attachments = json_decode($attachment_json);
            
            echo '    	
            	</div>
					'.$Work->description.'
            
            	<div>
            	<br>';
            	foreach($attachments as $attachment){
					echo'<a class="ui_attachment" href="../../'.$attachment->{"path"}.'"><i class="fa fa-paperclip"></i> '.$attachment->{"name"}.'</a><br>';
				}
				echo'
            	</div>';
            	if($Work->status == "visible" and ($Work->type == 2 or $Work->type == 3)){

            		//Check if you have sent the reply before
            		$user_h = $_SESSION['h'];
            		$query = $con->query("SELECT * FROM pl_califications WHERE user_h='$user_h'")or die("Query error!");
            		if($row = mysqli_fetch_array($query)) {
            			$description = $row['description'];
            			echo '
            				<div class="answer">
            					'.$description.'
            				</div>
            			';
            		} else {
            			echo '
							<div class="answer">					
								<form method="post" action="group.php?h='.$_GET["h"].'&action=answer" enctype="multipart/form-data">
									<table>
										<tr>
											<td>
												<div class="add_attachments"><i class="fa fa-paperclip"></i></div>
											</td>

											<td>
												<textarea id="editor1" name="description"></textarea>
											</td>

											<td>
												<input type="submit" value='.$lang["send"].'>
											</td>
										</tr>

										<tr>
											<td></td>
											<td class="attachments"></td>
											<td></td>
										</tr>
								</table>
							</form>
						</div>
						
						<script type="text/javascript">  
							CKEDITOR.replace( "editor1", {
							enterMode: CKEDITOR.ENTER_BR,
							skin : "office2013",
							height: "75px",
							width: "500px",
							toolbar : [
								{ name: "basicstyles", groups: [ "basicstyles", "cleanup" ], items: [ "Bold", "Italic", "Underline", "Strike", "TextColor", "BGColor", "Subscript", "Superscript" ] },
								{ name: "paragraph", groups: [ "list", "indent", "blocks", "align"], items: [ "-", "NumberedList", "BulletedList", "-", "Link", "Unlink", "Image", "Table", "Smiley", "Maximize" ] }
							]
							});      
						</script>
						
						<script>
							var attachments = 0;
							
							$( ".add_attachments" ).click(function() {
								$( ".attachments" ).append("<input type=\"file\" name=\""+attachments+"\"><br>" );
								attachments += 1;
							});
						</script>
					
						</div>';
            		}

					
				}
            echo'	
            </div>
			';

		} elseif(@$_GET['action'] == "answer") {

			$work_h = $_GET['h'];
			$user_h = $_SESSION['h'];
			$description = $_POST['description'];
			$h = substr( md5(microtime()), 1, 18);
			$delivery_date = date("Y-m-d H:i:s");

			$query = $con->query("INSERT INTO pl_califications(work_h,user_h,h,description,delivery_date) VALUES('$work_h','$user_h','$h','$description','$delivery_date')")or die("Query error!");

			echo '<script>location.href="group.php?action=view&h='.$work_h.'"</script>';

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

			$query_status = $con->query("SELECT * FROM pl_groupuser WHERE user_h='$User->h'")or die("Query error!");
			$row_status = mysqli_fetch_array($query_status);
			$status = $row_status['status'];
			
			if($status != "leader") {
				die($lang["not_permission"]." <a href='group.php?h=".$h."&page=index'>".$lang['accept']."</a>");
			}

			//Group Hash
			$gh = $_GET['h'];
			$unit_h = $_GET['unit'];

			$query = $con->query("SELECT * FROM pl_units WHERE h='$unit_h'")or die("Query error!");
			$row = mysqli_fetch_array($query);
			$unit_name = $row['name'];

			echo '
			<div class="ui_full_width">
			<div class="ui_head ui_head_width_actions">
				<h2>'.$lang["new_work_in"].' '.$unit_name.'</h2>
			</div>
			<form action="group.php?action=save_work&h='.$gh.'&unit='.$unit_h.'" method="POST" enctype="multipart/form-data">
				<table>
					<tr><td><label for="workname">'.$lang["workname"].'</label></td><td><input type="text" name="workname" size="40"></td></tr>
					<tr><td><label for="type">'.$lang["type"].'</label></td><td>
						<select name="type">
							<option value="1">'.$lang["notes"].'</option>
							<option value="2">'.$lang["homework"].'</option>
							<option value="3">'.$lang["exam"].'</option>
						</select>
					</td></tr>
					<tr><td><label for="visible">'.$lang["visible"].'</label> <div class="tip">asdfñjklasf</div></td><td><input type="checkbox" name="visible" checked="true"></td></tr>

					<tr><td></td><td><textarea cols="80" id="editor1" name="desc" rows="10"></textarea></td></tr>
					<tr><td>'.$lang["attachments_files"].'</td><td class="attachments"></td><td></td></tr>
					<tr><td><div class="add_attachments">'.$lang["add_attachment"].'</div></td></tr>
					<tr><td></td><td><input type="submit" value='.$lang["save"].'></td></tr>
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
        
        <script>
		var attachments = 0;
		
		$( ".add_attachments" ).click(function() {
			$( ".attachments" ).append("<input type=\"file\" name=\""+attachments+"\"><br>" );
			attachments += 1;
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

			$upload_error = 0;

			$attachments = [];

			foreach($_FILES as $key=>$file){
				$target_dir = "uploads/";
				$info = new SplFileInfo($file["name"]);
				$extension = $info->getExtension();
				$target_file = $target_dir . $System->rand_string(10).'.'.$extension;
				
				if (move_uploaded_file($file["tmp_name"], '../../'.$target_file)) {
					$file_data = [];
					$file_data["path"] = $target_file;
					$file_data["name"] = $file["name"];
					$attachments[] = $file_data;
				} else {
					$upload_error = +1;
				}	
			}
			
			$attachment_json = json_encode($attachments);
			
			//~ echo $attachment_json;
			
			
			if ($upload_error == 0) {
				$query = $con->query("INSERT INTO pl_works(name,type,h,creation_date,description,group_h,unit_h,status,attachment) VALUES('$workname',$type,'$h','$date','$desc','$gh','$unit_h','$status','$attachment_json')")or die("Query error!");
				echo '<script>location.href="group.php?h='.$gh.'&page=index"</script>';
			} else {
				echo ''.$lang["upload_error"].' <a href="group.php?h='.$gh.'&page=index">'.$lang["return"].'</a>';
			}	

			//~ if($_FILES["attachment"]["size"] != 0){
				//~ $target_dir = "uploads/";
				//~ $info = new SplFileInfo($_FILES["attachment"]["name"]);
				//~ $extension = $info->getExtension();
				//~ $target_file = $target_dir . $System->rand_string(10).'.'.$extension;
				//~ 
				//~ if (move_uploaded_file($_FILES["attachment"]["tmp_name"], '../../'.$target_file)) {
					//~ $query = $con->query("INSERT INTO pl_works(name,type,h,creation_date,description,unit_h,status,attachment) VALUES('$workname',$type,'$h','$date','$desc','$unit_h','$status','$target_file')")or die("Query error!");
					//~ echo '<a href="group.php?h='.$gh.'&page=index">'.$lang["accept"].'</a>';
				//~ } else {
					//~ echo ''.$lang["upload_error"].' <a href="group.php?h='.$gh.'&page=index">'.$lang["return"].'</a>';
				//~ }
			//~ }

		} elseif(@$_GET['action'] == "edit_work") {

			$work_h = $_GET['h'];
			$Work = $System->get_work_by_h($work_h, $con);

			echo '
				<div class="ui_full_width">
					<div class="ui_head ui_head_width_actions">
						<h2>'.$lang["edit"].' '.$Work->name.'</h2>
					</div>
					<form action="group.php?action=update_work&h='.$work_h.'&group='.$Work->group_h.'" method="POST" enctype="multipart/form-data">
						<table>
							<tr><td><label for="workname">'.$lang["workname"].'</label></td><td><input type="text" name="workname" value="'.$Work->name.'" size="40"></td></tr>
							<tr><td><label for="type">'.$lang["type"].'</label></td><td>
								<select name="type">';
									switch($Work->type) {
										case 1:
											echo '
												<option value="1" selected>'.$lang["notes"].'</option>
												<option value="2">'.$lang["homework"].'</option>
												<option value="3">'.$lang["exam"].'</option>
											';
											break;
										case 2:
											echo '
												<option value="1">'.$lang["notes"].'</option>
												<option value="2" selected>'.$lang["homework"].'</option>
												<option value="3">'.$lang["exam"].'</option>
											';
											break;
										case 3:
											echo '
												<option value="1">'.$lang["notes"].'</option>
												<option value="2">'.$lang["homework"].'</option>
												<option value="3" selected>'.$lang["exam"].'</option>
											';
											break;
										default:
											echo '
												<option value="1">'.$lang["notes"].'</option>
												<option value="2">'.$lang["homework"].'</option>
												<option value="3">'.$lang["exam"].'</option>
											';

									}

									echo '									
								</select>
							</td></tr>
							<tr><td><label for="visible">'.$lang["visible"].'</label></td><td>';

							switch ($Work->status) {
								case 'visible':
									echo '<input type="checkbox" name="visible" checked="true">';
									break;
								case 'invisible':
									echo '<input type="checkbox" name="visible">';
									break;
								default:
									echo '<input type="checkbox" name="visible">';
							}

							echo '
							</td></tr>
							<tr><td></td><td><textarea cols="80" id="editor1" name="desc" rows="10">'.$Work->description.'</textarea></td></tr>
							<!--<tr><td>'.$lang["attachments_files"].'</td><td class="attachments"></td><td></td></tr>
							<tr><td><div class="add_attachments">'.$lang["add_attachment"].'</div></td></tr>-->
							<tr><td></td><td><input type="submit" value='.$lang["save"].'></td></tr>
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

		} elseif(@$_GET['action'] == "update_work") {

			$work_h = $_GET['h'];
			$group_h = $_GET['group'];

			$name = $_POST['workname'];
			$description = $_POST['desc'];
			$type = $_POST['type'];
			$visible = $_POST['visible'];

			if($visible == "on") {
				$status = "visible";
			} else {
				$status = "invisible";
			}

			$query = $con->query("UPDATE pl_works SET name='$name',description='$description',type='$type',status='$status' WHERE h='$work_h'")or die("Query error!");
			echo "<a href='group.php?h=".$group_h."&page=index'>".$lang['accept']."</a>";

		} elseif(@$_GET['action'] == "del_work") {

			$work_h = $_GET['h'];

			$query = $con->query("SELECT * FROM pl_works WHERE h='$work_h'")or die("Query error!");

			$row = mysqli_fetch_array($query);
			$work_name = $row['name'];
			$group_h = $row['group_h'];

			echo "¿Estás seguro de que quieres eliminar ".$work_name."?";

			echo "<a href='group.php?action=confirm_delete&h=".$work_h."&group=".$group_h."'><button>".$lang['yes']."</button></a> <a href='group.php?h=".$group_h."&page=index'><button>".$lang['no']."</button></a>";

		} elseif(@$_GET['action'] == "confirm_delete") {

			$work_h = $_GET['h'];
			$group_h = $_GET['group'];

			$query = $con->query("DELETE FROM pl_works WHERE h='$work_h'")or die("Query error!");

			echo "<script>location.href='group.php?h=".$group_h."&page=index'</script>";

		} elseif(@$_GET['action'] == "save_unit") {

			$unit = $_POST['unit'];
			$h = substr( md5(microtime()), 1, 18);
			$gh = $_GET['h'];

			$query = $con->query("INSERT INTO pl_units(name,h,group_h) VALUES ('$unit','$h','$gh')")or die("Query error!");

			echo "<script>location.href='group.php?h=".$gh."&page=index'</script>";

		} elseif(@$_GET['action'] == "accept_request") {

			$request_id = $_GET['request_id'];

			$query = $con->query("UPDATE pl_groupuser SET status='active' WHERE id=$request_id")or die("Query error!");

			echo "<a href='index.php'>Accept</a>";

		} elseif(@$_GET['page'] == "index") {

			$gh = $_GET['h'];
			$query = $con->query("SELECT * FROM pl_groups WHERE h='$gh'")or die("Query error!");
			$row = mysqli_fetch_array($query);
			$groupname = $row['name'];

			//USER STATUS
			$query_status = $con->query("SELECT * FROM pl_groupuser WHERE group_h='$gh' AND user_h='$User->h'")or die("Query error!");
			$row_status = mysqli_fetch_array($query_status);
			$status = $row_status['status'];

				echo '				
					<div class="ui_full_width">
            			<div class="ui_head ui_head_width_actions">
                			<h2><i class="fa fa-users"></i> '.$groupname.'</h2>
            			</div>
            			<div class="ui_sidebar left">
                			<nav class="ui_vertical_nav">
                    			<ul>
                        			<li class="active"><a href="group.php?h='.$gh.'&page=index">'.$lang["works"].'</a></li>
                        			<li><a href="group.php?h='.$gh.'&page=users">'.$lang["users"].'</a></li>';

                        			if ($status == "leader") {
                        				echo '<li><a href="group.php?h='.$gh.'&page=requests">'.$lang["requests"].'</a></li>';
                        			}

                			echo '
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

				$query2 = $con->query("SELECT * FROM pl_works WHERE unit_h='$unit_h'")or die("Query error works!");
				while ($row2 = mysqli_fetch_array($query2)) {					
					$work_h = $row2['h'];
					$work_name = $row2['name'];
					$work_desc = $row2['description'];
					$work_type = $row2['type'];
					$work_status = $row2['status'];

					if($status == "leader") {
						if($work_status == "invisible") {
							echo '<a style="opacity: 0.5" href="group.php?action=view&h='.$work_h.'"><li class="work"><i class="fa fa-eye-slash"></i>'.$work_name.'<a href="group.php?action=del_work&h='.$work_h.'" class="right group_button"><i class="fa fa-trash"></i></a> <a href="group.php?action=edit_work&h='.$work_h.'" class="right group_button"><i class="fa fa-pencil-square-o"></i></a></li></a>';
						} else {
							echo '<a href="group.php?action=view&h='.$work_h.'"><li class="work">'.$work_name.'<a href="group.php?action=del_work&h='.$work_h.'" class="right group_button"><i class="fa fa-trash"></i></a> <a href="group.php?action=edit_work&h='.$work_h.'" class="right group_button"><i class="fa fa-pencil-square-o"></i></a></li></a>';
						}
					} else {
						if($work_status != "invisible") {
							echo '<a href="group.php?action=view&h='.$work_h.'"><li class="work">'.$work_name.'</li></a>';
						}
					}					
				}

				if ($status == "leader") {
					echo '
						<a href="group.php?action=new_work&h='.$h.'&unit='.$unit_h.'"><li class="new_work">'.$lang["new_work"].'</li></a>';
				}
				echo '</ul>';				
			}

			if ($status == "leader") {
				echo '
					<a onclick="open_popup()"><li class="new_unit">'.$lang["new_unit"].'</li></a>';
			}

			echo '</ul>';

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
                        <li><a href="group.php?h='.$gh.'&page=index">'.$lang["works"].'</a></li>
                        <li class="active"><a href="group.php?h='.$gh.'&page=users">'.$lang["users"].'</a></li>';
                        if ($status == "leader") {
                        	echo '<li><a href="group.php?h='.$gh.'&page=requests">'.$lang["requests"].'</a></li>';
                        }
                    echo '
                    </ul>
                </nav>
            </div>
            
            <div class="ui_width_sidebar right">
                
                <table class="ui_table">
                <thead>
                    <th class="select"><input class="select_all" type="checkbox" /></th>
                    <th>'.$lang["name_and_surname"].'</th>
                    <th>'.$lang["email"].'</th>
                    <th>'.$lang["address"].'</th>
                    <th>'.$lang["phone"].'</th>
                    <th class="actions">'.$lang["actions"].'</th>
                </thead>
                <tbody>';
                $query = $con->query("SELECT * FROM pl_groupuser WHERE group_h='$gh' AND status!='waiting'");
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
		} elseif(@$_GET['page'] == "requests") {

			$gh = $_GET['h'];

			$query_status = $con->query("SELECT * FROM pl_groupuser WHERE user_h='$User->h'")or die("Query error!");
			$row_status = mysqli_fetch_array($query_status);
			$status = $row_status['status'];

			$query1 = $con->query("SELECT * FROM pl_groups WHERE h='$gh'")or die("Query error!");
			$row1 = mysqli_fetch_array($query1);
			$groupname = $row1['name'];

			if($status != "leader") {
				die($lang["not_permission"]." <a href='group.php?h=".$h."&page=index'>".$lang['accept']."</a>");
			}

			echo '
				<div class="ui_full_width">
            		<div class="ui_head ui_head_width_actions">
                		<h2><i class="fa fa-users"></i> '.$groupname.'</h2>
            		</div>
            		<div class="ui_sidebar left">
                		<nav class="ui_vertical_nav">
                    		<ul>
                        		<li><a href="group.php?h='.$gh.'&page=index">'.$lang["works"].'</a></li>
                        		<li><a href="group.php?h='.$gh.'&page=users">'.$lang["users"].'</a></li>
                        		<li class="active"><a href="group.php?h='.$gh.'&page=requests">'.$lang["requests"].'</a></li>
                    		</ul>
                		</nav>
            		</div>
            		<div class="ui_width_sidebar right">
			';

			$query = $con->query("SELECT * FROM pl_groupuser WHERE status='waiting'")or die("Query error!");
			while ($row = mysqli_fetch_array($query)) {

				$group_h = $row['group_h'];
				$user_h = $row['user_h'];
				$request_id = $row['id'];

				$query_group = $con->query("SELECT * FROM pl_groups WHERE h='$group_h'")or die("Query error!");
				$query_user = $con->query("SELECT * FROM pl_users WHERE h='$user_h'")or die("Query error!");

				$row_group = mysqli_fetch_array($query_group);
				$row_user = mysqli_fetch_array($query_user);

				//~User Data
				$name = $row_user['name'];
				$surname = $row_user['surname'];

				//~Group Data
				$groupname = $row_group['name'];

				echo "<a href='group.php?action=accept_request&request_id=".$request_id."'><li>".$name." ".$surname." ".$lang['wants_to_join']." ".$groupname.". ".$lang['click_here_accept']."</li></a>";
			}

			echo '</div></div>';

		}
		
	?>		

	<?php $System->set_footer(); ?>
</body>
</html>
