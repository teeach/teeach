<?php

	session_start();

	include("../../core.php");

	$System = new System();
	$System->check_usr();
	$System = new System();
	$con = $System->conDB("../../config.json");
	$User = $System->get_user_by_id($_SESSION['h'], $con);

	$lang = $System->parse_lang("../../src/lang/".$System->load_locale().".json");

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title><?php echo $lang["messages"];?> | Teeach </title>
	<link rel="stylesheet" href="../../src/css/main.css">
	<script src="../../src/ckeditor/ckeditor.js"></script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<?
		$System->set_head();
	?>
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <style>
            .ui-autocomplete-loading {
            background: white url("images/ui-anim_basic_16x16.gif") right center no-repeat;
            }
        </style>
        <script>
            var elements = [];
            $(function() {
            function split( val ) {
              return val.split( /,\s*/ );
            }
            function extractLast( term ) {
              return split( term ).pop();
            }
            
            $( "#birds" )
              // don't navigate away from the field on tab when selecting an item
              
                .bind( "keydown", function( event ) {
                    if ( event.keyCode === $.ui.keyCode.TAB &&
                        $( this ).autocomplete( "instance" ).menu.active ) {
                            event.preventDefault();
                    }
                })
              
              
                .autocomplete({
                    source: function( request, response ) {
                        $.getJSON( "../../src/searchbox/search.php", {
                            term: extractLast( request.term )
                        }, response );
                    },
                    search: function() {
                        // custom minLength
                        var term = extractLast( this.value );
                        if ( term.length < 2 ) {
                            return false;
                        }
                    },
                    focus: function() {
                        // prevent value inserted on focus
                        return false;
                    },
                    select: function( event, ui ) {
                        var terms = split( this.value );
                        // remove the current input
                        terms.pop();
                        // add the selected item
                        terms.push( ui.item.value );
                        // add placeholder to get the comma-and-space at the end
                        //~ terms.push( "" );
                        //~ this.value = terms.join( ", " );
                        this.value = "";
                        elements.push(ui.item.id);
                        $( "#users" ).append( "<div class='"+ui.item.id+"' style='color: white; width:200px; border-radius:5px; background: #242424'><img style='border-radius:50px; width:64px' src='"+ui.item.icon+"'> " + ui.item.value +" <i id='"+ui.item.id+"' class='fa fa-times erase'></i> </div>" );
                        return false;
                    }
                });
            });
        </script>

</head>
<body>
	<?php
		$query = $con->query("SELECT * FROM pl_settings WHERE property='centername'");
		$row = mysqli_fetch_array($query);
		$centername = $row['value'];
		$System->set_header($centername);
		$System->set_usr_menu($User->h,$User->privilege,$lang);

		if (@$_GET['action'] == "new") {

			if (isset($_GET['to'])) {

				$to_h = $_GET['to'];
				$query = $con->query("SELECT * FROM pl_users WHERE h='$to_h'")or die("Query error!");
				$row = mysqli_fetch_array($query);
				$username = $row['username'];

				echo '
				<div class="ui_full_width">
					<div class="ui_head ui_head_width_actions">
						<h2><i class="fa fa-envelope"></i> '.$lang["new_message"].'</h2>
					</div>
					<table>
						<form action="messages.php?action=send" method="POST">
							<tr><td><label for="to">'.$lang["to"].': </label></td><td><input type="text" name="to" value="'.$username.'"></td></tr>
							<tr><td><label for="subject">'.$lang["subject"].': </label></td><td><input type="text" name="subject"></td></tr>
							<tr><td></td><td><textarea cols="80" id="editor1" name="body" rows="10"></textarea></td></tr>
							<tr><td></td><td><input type="submit" value="Enviar"></td></tr>
						</form>
					</table>
				</div>
				';
			} else {

				echo '
				<div class="ui_full_width">
					<div class="ui_head ui_head_width_actions">
						<h2><i class="fa fa-envelope"></i> '.$lang["new_message"].'</h2>
					</div>
					<div class="ui-widget">
					'.$lang["to"].':
					  <input id="birds" size="50">
					  <div id="users"></div>
					</div>
					<table>
						<form action="sendmessage.php" id="addusers">
							<tr><td><label for="subject">'.$lang["subject"].'</label></td><td><input type="text" id="subject" name="subject" value=""></td></tr>
							<tr><td></td><td><textarea cols="80" id="editor1" name="editor1" rows="10"></textarea></td></tr>
							<tr><td></td><td><input type="submit" value="Enviar"></td></tr>
						</form>
					</table>
				</div>
				<script>            
					$("body").on(\'click\', ".erase" , function() {
						var index = elements.indexOf($(this).attr("id"));
						$("div."+$(this).attr("id")).slideUp();
						if (index > -1) {
							elements.splice(index, 1);
						}
						//~ alert(elements);
					});
				</script>
				<script>
					// Attach a submit handler to the form
					$( "#addusers" ).submit(function( event ) {
					  // Stop form from submitting normally
					  event.preventDefault();
					 
					  // Get some values from elements on the page:
					  var $form = $( this ),
						term = elements,
						url = $form.attr( "action" );
					  var sender = '.json_encode($_SESSION['h']).' 
					 
					  // Send the data using post
					  var posting = $.post( url, { users: term, subject: $("#subject").val(), content: CKEDITOR.instances[\'editor1\'].getData(), sender: sender});
					  // Put the results in a div
					  posting.done(function( data ) {
						  alert(data);
						var content = $( data ).find( "#content" );
						$( "#result" ).empty().append( content );
					  });
					});
				</script>
				';
			}
				echo '
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

		} elseif(@$_GET['action'] == "send") {

			$to_username = $_POST['to'];

			$query = $con->query("SELECT * FROM pl_users WHERE username='$to_username'");
			$row = mysqli_fetch_array($query);

			$to = $row['h'];

			if ($to == "") {
				die($lang["user_not_exist"]);
			}

			$subject = $_POST['subject'];
			$body = $_POST['body'];
			$from = $User->h;
			$h = substr( md5(microtime()), 1, 18);

			$date = date("Y-m-d H:i:s");

			$query2 = $con->query("INSERT INTO pl_messages(from_h,to_h,subject,body,unread,h,date) VALUES('$from','$to','$subject','$body',1,'$h','$date')")or die("Query error!");

			echo '<a href="messages.php">'.$lang["accept"].'</a>';

		} elseif(@$_GET['action'] == "sent") {

			echo '
			<div class="ui_full_width">
				<div class="ui_head ui_head_width_actions">
				<h2><i class="fa fa-envelope"></i> '.$lang["messages"].'</h2>
                
                	<div class="ui_actions">
                    	<a href="messages.php?action=new"><button class="ui_action" class="ui_tooltip" title="Add New"><i class="fa fa-plus"></i></button></a>
                	</div>
            	</div>

            	<div class="ui_sidebar left">

                <nav class="ui_vertical_nav">
                    <ul>
                        <li><a href="messages.php">'.$lang["received"].'</a></li>
                        <li class="active"><a href="messages.php?action=sent">'.$lang["sent"].'</a></li>
                    </ul>
                </nav>                            
            	</div>

            	<div class="ui_width_sidebar right">
			';

				$query = $con->query("SELECT * FROM pl_messages WHERE from_h='$User->h' ORDER BY id DESC")or die("Query error!");

				while ($row = mysqli_fetch_array($query)) {

					$to_h = $row['to_h'];
					$subject = $row['subject'];
					$date = $row['date'];
					$body = $row['body'];
					$h = $row['h'];

					$query1 = $con->query("SELECT * FROM pl_users WHERE h='$to_h'")or die("Query error!");
					$row1 = mysqli_fetch_array($query1);

					$to_name = $row1['name'];
					$to_surname = $row1['surname'];

					echo '<div id="'.$h.'" class="'.$h.' message">'.$to_name.' '.$to_surname.' <div style="width:250px; display:inline-block; padding-left:10px"><a href="messages.php?action=view&h='.$h.'">'.$subject.'</a></div> '.date("d-m-Y H:i", strtotime($date)).' <div class="actions" style="float:right"><i id="'.$h.'" class="fa fa-share-square-o action answer"></i> <i id="'.$h.'" class="fa fa-trash-o action delete"></i></div></div>';
				}
                
                echo '
                    </div>
                </div>
                ';

		} elseif(@$_GET['action'] == "view") {

			$h = $_GET['h'];

			$Message = $System->get_message_by_h($h, $con);

			$query = $con->query("UPDATE pl_messages SET unread=0")or die("Query error!");

			echo '
				<div class="ui_full_width">
					<div class="ui_head ui_head_width_actions">
						<h2>'.$Message->subject.'</h2>

						<div class="ui_actions">
							<a href="messages.php?action=new&to='.$Message->to_h.'"><button class="ui_action" class="ui_tooltip" title="Reply"><i class="fa fa-reply"></i> '.$lang["reply"].'</button></a>
							<a href="messages.php?action=delete&h='.$Message->h.'"><button class="ui_action" class="ui_tooltip" title="Delete"><i class="fa fa-trash"></i> '.$lang["delete"].'</button></a>
						</div>
					</div>

					<div class="ui_sidebar left">
                		<nav class="ui_vertical_nav">
                    		<ul>
                        		<li><a href="messages.php">'.$lang["received"].'</a></li>
                        		<li><a href="messages.php?action=sent">'.$lang["sent"].'</a></li>
                    		</ul>
                		</nav>
            		</div>

            		<div class="ui_width_sidebar right">
						'.$Message->body.'
            		</div>
											
			';

		} elseif(@$_GET['action'] == "delete") {

			$message_h = $_GET['h'];

			$query = $con->query("DELETE FROM pl_messages WHERE h='$message_h'")or die("Query error!");

			echo "Message deleted. <a href='messages.php'>Aceptar</a>";

		} else {
			echo '
				<div class="ui_full_width">
					<div class="ui_head ui_head_width_actions">
						<h2><i class="fa fa-envelope"></i> '.$lang["messages"].'</h2>
                
                		<div class="ui_actions">
                   			<a href="messages.php?action=new"><button class="ui_action" class="ui_tooltip" title="Add New"><i class="fa fa-plus"></i></button></a>
                		</div>
            		</div>

            		<div class="ui_sidebar left">

                	<nav class="ui_vertical_nav">
                    	<ul>
                        	<li class="active"><a href="messages.php">'.$lang["received"].'</a></li>
                        	<li><a href="messages.php?action=sent">'.$lang["sent"].'</a></li>
                    	</ul>
                	</nav>                            
            		</div>

            		<div class="ui_width_sidebar right">
			';

				$query = $con->query("SELECT * FROM pl_messages WHERE to_h='$User->h' ORDER BY id DESC")or die("Query error!");

				while ($row = mysqli_fetch_array($query)) {
					$from_h = $row['from_h'];
					$query_from_h = $con->query("SELECT * FROM pl_users WHERE id='$from_h'")or die("Query error!");
					$row_from_h = mysqli_fetch_array($query_from_h);
					$from_h = $row_from_h['h'];
					$subject = $row['subject'];
					$date = $row['date'];
					$unread = $row['unread'];

					$from = $System->get_user_by_id($from_h, $con);

					$h = $row['h'];

					if($unread == 1) {
						echo '<div id="'.$h.'" class="'.$h.' message">'.$from->name.' '.$from->surname.' <div style="width:250px; display:inline-block; padding-left:10px; font-family:RobotoBold"><a href="messages.php?action=view&h='.$h.'">'.$subject.'</a></div> '.date("d-m-Y H:i", strtotime($date)).' <div class="actions" style="float:right"><i id="'.$h.'" class="fa fa-share-square-o action answer reply"></i> <i id="'.$h.'" class="fa fa-trash-o action delete"></i></div></div>';
					} else {
						echo '<div id="'.$h.'" class="'.$h.' message">'.$from->name.' '.$from->surname.' <div style="width:250px; display:inline-block; padding-left:10px"><a href="messages.php?action=view&h='.$h.'">'.$subject.'</a></div> '.date("d-m-Y H:i", strtotime($date)).' <div class="actions" style="float:right"><i id="'.$h.'" class="fa fa-share-square-o action answer reply"></i> <i id="'.$h.'" class="fa fa-trash-o action delete"></i></div></div>';
					}
				}
            echo '
                </div>
            </div>
            ';
		}
	?>
<script>
    $(".delete").click(function() {
        var $msg = $(this);
        var posting = $.post( "delmsg.php", {h:$(this).attr("id")});
          // Put the results in a div
          posting.done(function( data ) {
            //~ alert($("."+$(this).attr("id")).text());
            $("div."+$msg.attr("id")).toggle("slide");
            //~ var content = $( data ).find( "#content" );
            //~ $( "#result" ).empty().append( content );
          });
    });

</script>
</body>
</html>
