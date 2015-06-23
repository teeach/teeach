<?php
	class System {
       
       	function set_head() {
			echo "
				<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>
				<link rel='stylesheet' href='../../src/css/main.css'>
				<link rel='stylesheet' href='//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'>
                <script src='https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
			";
			$lang = "es_ES";
			putenv('LC_ALL='.$lang);
			setlocale(LC_ALL, $lang);
			bindtextdomain("app", "../../locale");
			textdomain("app");
			date_default_timezone_set("Europe/Madrid");
		}

        function get_date() {
            echo date("d-m-Y H:i:s");
        }
                
		function set_header($centername) {
			echo '
				<header id="header">
    				<div class="main_title">
        				<h1>'.$centername.'</h1>
    				</div>
    				
			
			';
		}

		function set_footer() {
			echo "
                <footer>
                    ©2015 Teeach<br>
                    Early development version
                </footer>";
		}

		function set_usr_menu($h,$p) {
            $fp = fopen("../../config.json", "r");
            $rfile = fread($fp, filesize("../../config.json"));

            $json = json_decode($rfile);

            $dbserver = $json->{"dbserver"};
            $dbuser = $json->{"dbuser"};
            $dbpass = $json->{"dbpass"};
            $database = $json->{"database"};

            $con = mysqli_connect($dbserver, $dbuser, $dbpass, $database)or die("Error al conectar BD!");
            $query = $con->query("SELECT * FROM pl_users WHERE h='$h'")or die("Query error!");
            $row = mysqli_fetch_array($query);

            //Current User data
            $id = $row['id'];
            $name = $row['name'];
            $surname1 = $row['subname1'];
            $surname2 = $row['subname2'];
            $username = $row['username'];
            $profile_photo = $row['photo'];


		//Student Menu
		if ($p == 1) {
			echo '
				<nav class="main_menu">
        			<ul>
            			<li><a href="index.php">'._("Index").'</a></li>
                        <li>
                            <a href="../admin">'._("Groups").'</a>
                            <ul>';
                                $query = $con->query("SELECT * FROM pl_groupuser WHERE userid=$id")or die("Query error!");
                                while($row1 = mysqli_fetch_array($query)) {
                                    $groupid = $row1['groupid'];
                                    $query2 = $con->query("SELECT * FROM pl_groups WHERE id=$groupid")or die("Query error!");
                                    $row2 = mysqli_fetch_array($query2);
                                    $name = $row2['name'];
                                    $h = $row2['h'];
                                    echo '<li><a class="icon_users" href="group.php?action=view&h='.$h.'&page=index"><i class="fa fa-users"></i> '.$name.'</a></li>';
                                }
                            echo '                                
                            </ul>
                         </li>
            			<li><a href="diary.php">'._("Diary").'</a></li>
            			<li><a href="messages.php">'._("Messages").'</a></li>
        			</ul>
    			</nav>
    			<nav class="user_menu">
        			<ul>
            			<li><a class="user_image" href="#"><img src="'.$profile_photo.'" /></a>
                		<ul>
                    		<li class="view_profile">
                    		<a href="profile.php?h='.$h.'">
                        		'.$name." ".$surname1." ".$surname2.'
                        		<span>'._("View profile").'</span>
                        	</a>
                    		</li>
                    		<li class="edit_profile"><a href="editprofile.php"><i class="fa fa-pencil"></i> '._("Edit profile").'</a></li>
                    		<li class="logout_user"><a href="logout.php"><i class="fa fa-sign-out"></i> '._("Log out").'</a></li>
                		</ul>
            		</li>
        		</ul>
    			</nav>
    			</header>
			';

		//Teacher Menu
		} elseif ($p == 2) {
			echo '
				<nav class="main_menu">
        			<ul>
            			<li><a href="index.php">'._("Index").'</a></li>
            			<li><a href="diary.php">'._("Diary").'</a></li>
            			<li><a href="messages.php">'._("Messages").'</a></li> 
        			</ul>
    			</nav>
    			<nav class="user_menu">
        			<ul>
            			<li><a class="user_image" href="#"><img src="'.$profile_photo.'" /></a>
                		<ul>
                    		<li class="view_profile">
                    		<a href="profile.php?h='.$h.'">
                                '.$name." ".$surname1." ".$surname2.'
                                <span>'._("View profile").'</span>
                            </a>
                    		</li>
                    		<li class="edit_profile"><a href="editprofile.php"><i class="fa fa-pencil"></i> '._("Edit profile").'</a></li>
                    		<li class="logout_user"><a href="logout.php"><i class="fa fa-sign-out"></i> '._("Log out").'</a></li>
                		</ul>
            		</li>
        		</ul>
    			</nav>
    			</header>
				';

		//Admin Menu
		} else {
			echo '
				<nav class="main_menu">
        			<ul>
            			<li><a href="index.php">'._("Index").'</a></li>
            			<li><a href="diary.php">'._("Diary").'</a></li>
            			<li><a href="messages.php">'._("Messages").'</a></li>
           				<li>
                            <a href="../admin">'._("Admin").'</a>
                	        <ul>
                    	        <li><a class="icon_users" href="../admin/users.php?action"><i class="fa fa-users"></i> '._("Users").'</a></li>
                    	        <li><a class="icon_org" href="../admin/groups.php?action"><i class="fa fa-graduation-cap"></i> '._("Groups").'</a></li>
                    	        <li><a class="icon_post" href="../admin/posts.php?action"><i class="fa fa-pencil"></i> '._("Posts").'</a></li>
                    	        <li><a class="icon_config" href="../admin/settings.php?action"><i class="fa fa-cog"></i> '._("Settings").'</a></li>
                	        </ul>
            		     </li>
        			</ul>
    			</nav>
    			<nav class="user_menu">
        			<ul>
            			<li><a class="user_image" href="#"><img src="'.$profile_photo.'" /></a>
                		<ul>
                    		<li class="view_profile">
                    		<a href="profile.php?h='.$h.'">
                                '.$name." ".$surname1." ".$surname2.'
                                <span>'._("View profile").'</span>
                            </a>
                    		</li>
                    		<li class="edit_profile"><a href="editprofile.php"><i class="fa fa-pencil"></i> '._("Edit profile").'</a></li>
                    		<li class="logout_user"><a href="logout.php"><i class="fa fa-sign-out"></i> '._("Log out").'</a></li>
                		</ul>
            		</li>
        		</ul>
    			</nav>
    			</header>
				';		
		}
	}

		function conDB($filejson) {
			$fp = fopen($filejson, "r");
			$rfile = fread($fp, filesize($filejson));

			$json = json_decode($rfile);

			$dbserver = $json->{"dbserver"};
			$dbuser = $json->{"dbuser"};
			$dbpass = $json->{"dbpass"};
			$database = $json->{"database"};

			$con = mysqli_connect($dbserver, $dbuser, $dbpass, $database)or die("Error al conectar BD!");

		return $con;

		}
        
        function get_user_by_id($h, $connection){
            $query = $connection->query("select * from pl_users where h='$h'");
            $result = mysqli_fetch_array($query);
            $user = new User($result['id'],$result['username'],$result['name'],$result['subname1'],$result['subname2'],$result['email'],$result['phone'],$result['level'],$result['h'],$result['photo'],$result['birthday'],$result['home'],$result['pass'],$result['privilege'],$result['group']);
            return $user;
        }

        function rand_string( $length ) {
            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

            $size = strlen( $chars );
            for( $i = 0; $i < $length; $i++ ) {
                $str .= $chars[ rand( 0, $size - 1 ) ];
            }

            return $str;
        }

    }
    
    class User {
        
        function __construct($id, $username, $name, $subname1, $subname2, $email, $phone, $level, $h, $photo, $birthday, $home, $pass, $privilege, $group){
            $this->id = $id;
            $this->username = $username;
            $this->name = $name;
            $this->surname1 = $subname1;
            $this->surname2 = $subname2;
            $this->email = $email;
            $this->phone = $phone;
            $this->level = $level;
            $this->h = $h;
            $this->photo = $photo;
            $this->birthday = $birthday;
            $this->home = $home;
            $this->pass = $pass;
            $this->privilege = $privilege;
            $this->group = $group;
        }
    }
?>