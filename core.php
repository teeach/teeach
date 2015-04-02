<?php
	class System {

		function set_head() {
			putenv('LC_ALL=es_ES');
			setlocale(LC_ALL, 'es_ES');
			bindtextdomain("app", "./locale");
			textdomain("app");
			echo "
				<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'/>
			";
		}

		function set_header() {
			echo "
				<header><h2>Project Learn</h2></header>
			";
		}

		function set_footer() {
			echo "<footer>(c)2015 Project Learn</footer>";
		}

		function set_usr_menu($h,$p) {
			
		if ($p == 1) {
			echo "
				<nav>
					<ul>
						<a href='profile.php?h=$h'><li>Yo</li></a>
						<a href='index.php'><li>Inicio</li></a>
						<a href='#'><li>Exámenes</li></a>
						<a href='#'>Agenda</a>
						<a href='#'>Notificaciones</a>
						<a href='../../logout.php'>Salir</a>
					</ul>
				</nav>
			";
		} else {
			echo "
					<nav>
						<ul>
							<a href='profile.php?h=$h'><li>Yo</li></a>
							<a href='index.php'><li>Inicio</li></a>
							<a href='#'><li>Mis Grupos</li></a>
							<a href='../../logout.php'>Salir</a>
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
            $user = new User($result[0],$result[1],$result[2],$result[3],$result[4],$result[5],$result[6],$result[7],$result[8],$result[9],$result[10],$result[11],$result[12],$result[13],$result[14]);
            return $user;
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
