<?php
	class System {
        function __construct(){
            $lang = "es_ES";
			putenv('LC_ALL='.$lang);
			setlocale(LC_ALL, $lang);
			bindtextdomain("app", "./locale");
			textdomain("app");
			date_default_timezone_set("Europe/Madrid");
        }
		function set_head() {
			echo "
				<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'/>
				<link rel='stylesheet' href='../../src/css/main.css'>
				<link rel='stylesheet' href='//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'>
                <script src='https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
			";
		}

                function get_date(){
                    echo date("d-m-Y H:i:s");
                }
                
		function set_header() {
			echo "
				<header></header>
			";
		}

		function set_footer() {
			echo "<footer>©2015 Teeach</footer>";
		}

		function set_usr_menu($h,$p) {
			
		if ($p == 1) {
			echo "
				<nav>
					<ul>
						<a href='profile.php?h=$h'><li>Yo</li></a>
						<a href='index.php'><li>Inicio</li></a>
						<a href='diary.php'>Agenda</a>
						<a href='messages.php'><li>Messages</li></a>
						<a href='logout.php'>Salir</a>
					</ul>
				</nav>
			";
		} elseif($p == 4 || $p == 3) {
			echo "
					<nav>
						<ul>
							<a href='profile.php?h=$h'><li>Yo</li></a>
							<a href='index.php'><li>Inicio</li></a>
							<a href='diary.php'>Agenda</a>
							<a href='messages.php'><li>Messages</li></a>
							<a href='../admin'><li>Admin</li></a>
							<a href='logout.php'>Salir</a>
						</ul>
					</nav>
				";
		} else {
			echo "
					<nav>
						<ul>
							<a href='profile.php?h=$h'><li>Yo</li></a>
							<a href='index.php'><li>Inicio</li></a>
							<a href='diary.php'>Agenda</a>
							<a href='logout.php'>Salir</a>
						</ul>
					</nav>
				";
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
    
    class User{
        
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
