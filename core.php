<?php
	class System {
       
       	function set_head() {
			echo "
				<link rel='stylesheet' href='../../src/css/main.css'>

                <!--jQuery-->
                <script src='https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

                <!--jQuery UI-->
                <script src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js'></script>
                <link rel='stylesheet' href='../../src/js/jquery/jquery-ui.theme.css' />

                <script src='../../src/js/check-all.js'></script>
                <link rel='stylesheet' href='//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'>
                <link rel='stylesheet' href='path/to/font-awesome/css/font-awesome.min.css'>
			";
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
                    Pre-Alpha
                </footer>";
		}

		function load_locale() {
			
			@session_start();
						
			$con = $this->conDB("../../config.json");
            $query2 = $con->query("SELECT * FROM pl_settings WHERE property='lang'")or die("Query error!");
			$row2 = mysqli_fetch_array($query2);
			
            if(isset($_SESSION['h'])) {
				$user_h = $_SESSION['h'];
				$query = $con->query("SELECT * FROM pl_users WHERE h='$user_h'")or die("Query error!");
				$row = mysqli_fetch_array($query);
				if ($row["lang"] == "") {
					$lang = $row2["value"];
				} else {
					$lang = $row["lang"];
				}
			} else {
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
                        <!--<li><a href="diary.php">'.$lang["diary"].'</a></li>-->
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
        
        function get_user_by_id($h, $connection) {
            $query = $connection->query("SELECT * FROM pl_users WHERE h='$h'")or die("Query error!");
            $result = mysqli_fetch_array($query);
            $user = new User($result['id'],$result['username'],$result['name'],$result['surname'],$result['email'],$result['address'],$result['phone'],$result['level'],$result['h'],$result['lang'],$result['photo'],$result['birthday'],$result['pass'],$result['privilege'],$result['creation_date'],$result['last_time'],$result['status']);
            return $user;
        }

        function get_work_by_h($h, $con) {
            $query = $con->query("SELECT * FROM pl_works WHERE h='$h'")or die("Query error!");
            $row = mysqli_fetch_array($query);
            $work = new Work($row['id'],$row['name'],$row['h'],$row['description'],$row['type'],$row['creation_date'],$row['group_h'],$row['unit_h'],$row['status'],$row['attachment']);
            return $work;
        }

        function get_message_by_h($h, $con) {
            $query = $con->query("SELECT * FROM pl_messages WHERE h='$h'")or die("Query error!");
            $row = mysqli_fetch_array($query);
            $message = new Message($row['id'],$row['from_h'],$row['to_h'],$row['subject'],$row['body'],$row['h'],$row['date']);
            return $message;
        }

        function rand_string( $length ) {
            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
			$str = "";
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
        
        function __construct($id, $username, $name, $surname, $email, $address, $phone, $level, $h, $lang, $photo, $birthday, $pass, $privilege, $creation_date, $last_time, $status) {
            $this->id = $id;
            $this->username = $username;
            $this->name = $name;
            $this->surname = $surname;
            $this->email = $email;
            $this->address = $address;
            $this->phone = $phone;
            $this->level = $level;
            $this->h = $h;
            $this->photo = $photo;
            $this->birthday = $birthday;
            $this->pass = $pass;
            $this->privilege = $privilege;
            $this->creation_date = $creation_date;
            $this->last_time = $last_time;
            $this->status = $status;
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

    class Work {
        function __construct($id, $name, $h, $description, $type, $creation_date, $group_h, $unit_h, $status, $attachment) {
            $this->id = $id;
            $this->name = $name;
            $this->h = $h;
            $this->description = $description;
            $this->type = $type;
            $this->creation_date = $creation_date;
            $this->group_h = $group_h;
            $this->unit_h = $unit_h;
            $this->status = $status;
            $this->attachment = $attachment;
        }
    }
    
    class Message {
        function __construct($id,$from_h,$to_h,$subject,$body,$h,$date){
            $this->id = $id;
            $this->from_h = $from_h;
            $this->to_h = $to_h;
            $this->subject = $subject;
            $this->body = $body;
            $this->h = $h;
            $this->date = $date;
        }
    }
?>
