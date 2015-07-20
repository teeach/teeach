<?php
	class System {
       
       	function set_head() {
			echo "
				<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>
				<link rel='stylesheet' href='../../src/css/main.css'>
				<link rel='stylesheet' href='//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'>
                <link rel='stylesheet' href='path/to/font-awesome/css/font-awesome.min.css'>
                <script src='https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
                <script src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js'></script>
                <link rel='stylesheet' href='../../src/css/jquery-ui.theme.min.css' />
                <script src='../../src/js/check-all.js'></script>
			";
			//~ $lang = "es_ES";
			//~ putenv('LC_ALL='.$lang);
			//~ setlocale(LC_ALL, $lang);
			//~ bindtextdomain("app", "../../locale");
			//~ textdomain("app");
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

		function load_locale(){
			
			@session_start();
						
			$con = $this->conDB("../../config.json");
            $query2 = $con->query("SELECT * FROM pl_settings WHERE property='lang'")or die("Query error!");
			$row2 = mysqli_fetch_array($query2);
			
            if(isset($_SESSION['h'])) {
				$user_h = $_SESSION['h'];
				$query = $con->query("SELECT * FROM pl_users WHERE h='$user_h'")or die("Query error!");
				$row = mysqli_fetch_array($query);
				if($row["lang"] == ""){
					$lang = $row2["value"];
				}else{
					$lang = $row["lang"];
				}
			}else{
				$lang = $row2["value"];
			}
			return $lang;
		}
		
		function parse_lang($json){
			$fp = fopen($json, "r");
            $rfile = fread($fp, filesize($json));

            $json_lang = json_decode($rfile);
			
			$lang = [];
			foreach ($json_lang as $index => $row_lang) {
				$lang[$index] = $row_lang;
			}
			return $lang;
		}

		function set_usr_menu($h,$p,$lang) {
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
            $surname = $row['surname'];
            $username = $row['username'];
            $profile_photo = $row['photo'];

            if ($profile_photo == "") {
                $profile_photo = "../../src/ico/user.png";
            }
			
			
            //Main Menu
            echo '
            <header>
                <nav class="main_menu">
                    <ul>
                        <li><a href="index.php">'.$lang["index"].'</a></li>
                        <li>
                            <a href="#">'.$lang["groups"].'</a>
                            <ul>';
                                $query = $con->query("SELECT * FROM pl_groupuser WHERE user_h='$h'")or die("Query error!");
                                while($row1 = mysqli_fetch_array($query)) {
                                    $group_h = $row1['group_h'];
                                    $query2 = $con->query("SELECT * FROM pl_groups WHERE h='$group_h'")or die("Query error!");
                                    $row2 = mysqli_fetch_array($query2);
                                    $groupname = $row2['name'];
                                    echo '<li><a class="icon_users" href="group.php?h='.$group_h.'&page=index"><i class="fa fa-users"></i> '.$groupname.'</a></li>';
                                }
                            echo '                                
                            </ul>
                         </li>
                        <li><a href="diary.php">'.$lang["diary"].'</a></li>
                        <li><a href="messages.php">'.$lang["messages"].'</a></li>
                    
            ';
        //If you're Admin...
		if ($p >= 3) {
			echo '				
    			<li>
                    <a href="../admin">'.$lang["admin"].'</a>
                    <ul>
                        <li><a class="icon_users" href="../admin/users.php?action"><i class="fa fa-users"></i> '.$lang["users"].'</a></li>
                        <li><a class="icon_org" href="../admin/groups.php?action"><i class="fa fa-graduation-cap"></i> '.$lang["groups"].'</a></li>
                        <li><a class="icon_post" href="../admin/posts.php?action"><i class="fa fa-pencil"></i> '.$lang["posts"].'</a></li>
                        <li><a class="icon_config" href="../admin/settings.php?action"><i class="fa fa-cog"></i> '.$lang["settings"].'</a></li>
                    </ul>
                </li>
			';
        }

		echo '
            </ul>
        </nav>
        <nav class="user_menu">
                <ul>
                    <li><a class="user_image" href="#"><img src="'.$profile_photo.'" /></a>
                    <ul>
                        <li class="view_profile">
                        <a href="profile.php?h='.$h.'">
                            '.$name." ".$surname.'
                            <span>'.$lang["view_profile"].'</span>
                        </a>
                        </li>
                        <li class="edit_profile"><a href="editprofile.php"><i class="fa fa-pencil"></i> '.$lang["edit_profile"].'</a></li>
                        <li class="logout_user"><a href="logout.php"><i class="fa fa-sign-out"></i> '.$lang["log_out"].'</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        </header>
        </header>
        ';
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
            $query = $connection->query("SELECT * FROM pl_users WHERE h='$h'");
            $result = mysqli_fetch_array($query);
            $user = new User($result['id'],$result['username'],$result['name'],$result['surname'],$result['email'],$result['phone'],$result['level'],$result['h'],$result['photo'],$result['birthday'],$result['home'],$result['pass'],$result['privilege']);
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

        function check_usr() {
            @session_start();
            if(!isset($_SESSION['h'])) {
                header('Location: ../../index.php');
            }
        }

        function check_admin() {
            @session_start();
            if(!isset($_SESSION['h'])) {
                header('Location: ../../index.php');
            }

            $h = $_SESSION['h'];

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
            $privilege = $row['privilege'];

            if($privilege < 3) {
                die("Only administrators can enter here.");
            }

        }

    }
    
    class User {
        
        function __construct($id, $username, $name, $surname, $email, $phone, $level, $h, $photo, $birthday, $home, $pass, $privilege){
            $this->id = $id;
            $this->username = $username;
            $this->name = $name;
            $this->surname = $surname;
            $this->email = $email;
            $this->phone = $phone;
            $this->level = $level;
            $this->h = $h;
            $this->photo = $photo;
            $this->birthday = $birthday;
            $this->home = $home;
            $this->pass = $pass;
            $this->privilege = $privilege;
        }
    }

    class Group {
        function __construct($id,$name,$h,$category_h) {
            $this->id = $id;
            $this->name = $name;
            $this->h = $h;
            $this->category_h = $category_h;
        }
    }
    
    class Message {
        function __construct($id,$from_h,$to_h,$subject,$body,$h,$date){
            $this->id = $id;
            $this->from_h = $from_H;
            $this->to_h = $to_h;
            $this->subject = $subject;
            $this->body = $body;
            $this->h = $h;
            $this->date = $date;
        }
    }
?>
