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

                <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css'>

                <script src='../../src/js/check-all.js'></script>
                <script src='../../src/js/main.js'></script>
                
			";
		}
                
		function set_header($centername) {

			echo '
				<header id="header">
    				<div class="main_title">
        				<a href="index.php"><h1>'.$centername.'</h1></a>
    				</div>
			';
		}

		function set_footer() {
			echo "
                <footer>
                    ©2016 Teeach<br>
                    Early Development Version
                </footer>";
		}

        function get_date() {
            echo date("d-m-Y H:i:s");
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
                                $query = $con->query("SELECT * FROM pl_groupuser WHERE user_h='$h' AND status!='waiting'")or die("Query error!");
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
            $user = new User($result['id'],$result['username'],$result['name'],$result['surname'],$result['email'],$result['address'],$result['phone'],$result['level'],$result['h'],$result['lang'],$result['photo'],$result['birthdate'],$result['pass'],$result['privilege'],$result['creation_date'],$result['last_time'],$result['status']);
            return $user;
        }

        function get_group_by_h($h, $con) {
            $query = $con->query("SELECT * FROM pl_groups WHERE h='$h'")or die("Query error!");
            $row = mysqli_fetch_array($query);
            $group = new Group($row['id'],$row['name'],$row['h'],$row['category_h']);
            return $group;
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

        function get_month($lang, $month) {
            switch ($month) {
                case 01:
                    return $lang["january"];
                    break;
                case 02:
                    return $lang["february"];
                    break;
                case 03:
                    return $lang["march"];
                    break;
                case 04:
                    return $lang["april"];
                    break;
                case 05:
                    return $lang["may"];
                    break;
                case 06:
                    return $lang["june"];
                    break;
                case 07:
                    return $lang["july"];
                    break;
                case 08:
                    return $lang["august"];
                    break;
                case 09:
                    return $lang["september"];
                    break;
                case 10:
                    return $lang["october"];
                    break;
                case 11:
                    return $lang["november"];
                    break;
                case 12:
                    return $lang["december"];
                    break;
                default:
                    return $lang["unknown"];
            }
        }

        function get_date_format($date, $lang, $con) {
            $query = $con->query("SELECT * FROM pl_settings WHERE property='date_format'")or die("Query error!");
            $row = mysqli_fetch_array($query);
            $date_format_setting = $row['value'];

            $date = strtotime($date);

            $year = date("Y", $date);
            $month = date("m", $date);
            $day = date("j", $date);

            if($date_format_setting == "1") {
                $date_format = $month."/".$day."/".$year;
            } elseif($date_format_setting == "2") {
                $date_format = $day."/".$month."/".$year;
            } elseif($date_format_setting == "3") {

                switch ($month) {
                    case 01:
                        $month_writed = $lang["january"];
                        break;
                    case 02:
                        $month_writed = $lang["february"];
                        break;
                    case 03:
                        $month_writed = $lang["march"];
                        break;
                    case 04:
                        $month_writed = $lang["april"];
                        break;
                    case 05:
                        $month_writed = $lang["may"];
                        break;
                    case 06:
                        $month_writed = $lang["june"];
                        break;
                    case 07:
                        $month_writed = $lang["july"];
                        break;
                    case 08:
                        $month_writed = $lang["august"];
                        break;
                    case 09:
                        $month_writed = $lang["september"];
                        break;
                    case 10:
                        $month_writed = $lang["october"];
                        break;
                    case 11:
                        $month_writed = $lang["november"];
                        break;
                    case 12:
                        $month_writed = $lang["december"];
                        break;
                    default:
                        $month_writed = $lang["unknown"];
                }

                $date_format = $day." ".$lang['of_date']." ".$month_writed." ".$lang['of_date']." ".$year;

            }

            return $date_format;
        }

        function get_time_format($time, $con) {
            $query = $con->query("SELECT * FROM pl_settings WHERE property='time_format'")or die("Query error!");
            $row = mysqli_fetch_array($query);
            $time_format_setting = $row['value'];

            $time = strtotime($time);

            $minutes = date("i", $time);

            if($time_format_setting == "12") {
                $hour = date("g", $time);
                $meridiem = date("A", $time);
                $time = $hour.":".$minutes." ".$meridiem;
            } else {
                $hour = date("G", $time);
                $time = $hour.":".$minutes;
            }

            return $time;
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

        function how_many_days($datetime, $lang) {
            $actual_time = date("Y-m-d H:i:s");

            $seconds = abs(strtotime($datetime)-strtotime($actual_time));

            if ($seconds/60 >= 1 && $seconds/60 < 60) {

                $minutes = round($seconds/60, 0);

                if($minutes == 1) {
                    return $lang["ago_"].$minutes." ".$lang["minute"].$lang["_ago"];
                } else {
                    return $lang["ago_"].$minutes." ".$lang["minutes"].$lang["_ago"];
                }

            } elseif ($seconds/3600 >= 1 && $seconds/3600 < 24) {

                $hours = round($seconds/3600, 0);

                if($hours == 1) {
                    return $lang["ago_"].$hours." ".$lang["hour"].$lang["_ago"];
                } else {
                    return $lang["ago_"].$hours." ".$lang["hours"].$lang["_ago"];
                }

            } elseif ($seconds/86400 >= 1 && $seconds/86400 < 7) {

                $days = round($seconds/86400, 0);

                if($days == 1) {
                    return $lang["ago_"].$days." ".$lang["day"].$lang["_ago"];
                } else {
                    return $lang["ago_"].$days." ".$lang["days"].$lang["_ago"];
                }

            } elseif ($seconds/604800 >= 1) {

                $weeks = round($seconds/604800, 0);

                if($weeks == 1) {
                    return $lang["ago_"].$weeks." ".$lang["week"].$lang["_ago"];
                } else {
                    return $lang["ago_"].$weeks." ".$lang["weeks"].$lang["_ago"];
                }

            } else {

                if($seconds == 1) {
                    return $lang["ago_"].$seconds." ".$lang["second"].$lang["_ago"];
                } else {
                    return $lang["ago_"].$seconds." ".$lang["seconds"].$lang["_ago"];
                }

            }

        }

        function filter_obscene_language($str, $con) {
            $query = $con->query("SELECT * FROM pl_settings WHERE property='filter_obscene_language'")or die("Query error!");
            $row = mysqli_fetch_array($query);
            $filter_obscene_language = $row['value'];

            $obscene_words = "acojonar,agilipollada,agilipollado,ass,bastarda,bastardo,bitch,bitching,boluda,boludo,boludez,bullshit,cabron,cabrón,cabrona,cabroncete,cachonda,cachondo,carajo,chichi,chocho,chochona,chuloputas,chumino,cock,cocksucker,cojon,cojón,cojonudo,coñocunt,dick,folla,follada,follado,follador,folladora,follamos,follando,follar,follarse,follo,foutre,fuck,fucked,fucker,fuckers,fuckface,fucking,fucksville,gili,gilipolla,gilipollas,gilipuertas,hijadeputa,hijaputa,hijadeputo,hijaputo,hostia,huevon,huevón,huevona,idiota,imbécil,joder,joderos,jodete,jódete,jodida,jodido,joputa,lameculo,lameculos,malfollada,malfollado,malnacida,malnacido,malparida,malparido,mamada,mamamela,mámamela,mamarla,mamon,mamón,mamona,marica,maricon,maricón,maricona,mariconazo,mariposon,mariposón,merde,mierda,motherfucker,pendeja,pendejo,polla,pollada,pollon,pollón,prick,puta,putada,putain,putas,pute,putilla,putillo,putita,putito,puto,puton,putón,putona,putos,pussyshit,salope,shitty,soplaflautas,soplapollas,shitkicker,subnormal,tocacojones,tocapelotas,tragapollas,tragasables,twat";

            if($filter_obscene_language == 1) {
                $str = "";
                $i = 0;
                $j = 0;
                while ($array_str= explode(" ", $str)) {

                    while ($array_obscene_words = explode(",", $obscene_words)) {

                        if ($array_str[$i] == $array_obscene_words[$j]) {

                            $str += " ****** ";

                        }

                        $j++;

                    }
                    
                    $str += " ".$array_str[$i]." ";
                    $i++;

                }
            }

            return $str;
        }

        function read_language($lang) {
            switch($lang) {
                case 'es_ES':
                    return $lang = "Español (España)";
                    break;
                case 'en_EN':
                    return $lang = "English (England)";
                    break;
                case 'ca_ES':
                    return $lang = "Català (España)";
                    break;
                case 'de_DE':
                    return $lang = "Deutsch (Deutschland)";
                    break;
                case 'fr_FR':
                    return $lang = "Français (France)";
                    break;
                default:
                    //Default
            }
        }

    }
    
    class User {
        
        function __construct($id, $username, $name, $surname, $email, $address, $phone, $level, $h, $lang, $photo, $birthdate, $pass, $privilege, $creation_date, $last_time, $status) {
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
            $this->birthdate = $birthdate;
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

        function set_nav_menu($Group,$privilege,$page,$lang) {
            echo '
                <div class="ui_sidebar left">
                    <nav class="ui_vertical_nav">
                        <ul>
                            <li';if($page=="index"){echo' class="active"';}echo'><a href="group.php?h='.$Group->h.'&page=index">'.$lang["works"].'</a></li>
                            <li';if($page=="users"){echo' class="active"';}echo'><a href="group.php?h='.$Group->h.'&page=users">'.$lang["users"].'</a></li>';

                            if($privilege == "moderator") {
                                echo '
                                    <li';if($page=="requests"){echo' class="active"';}echo'><a href="group.php?h='.$Group->h.'&page=requests">'.$lang["requests"].' <span id="num_requests"></span></a></li>
                                    <li';if($page=="absences"){echo' class="active"';}echo'><a href="group.php?h='.$Group->h.'&page=absences">'.$lang["absences"].'</a></li>
                                ';
                            }
                            
                            echo '
                        </ul>
                    </nav>
                </div>
            ';
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
    
    class Pagination{
			
		function __construct($limit){
			$this->limit = $limit;
			$this->startpoint = 0;
			$this->page = 0;
		}
		
		function prepaginate($page){
			$this->page = $page;
			$this->startpoint = ($page * $this->limit)-$this->limit;
		}
		
		function paginate($items){
			$pags = ceil($items/$this->limit);
			$back = $this->page - 1;
			$forward = $this->page +1;

			$count = 0;

			if($this->page == 1 or $this->page == 2){
				$pagination = 1;
			}else{
				$pagination = $this->page-2;
			}


			if($pags > 1){
			echo'<div style="margin-top: 4px;" class="pagination">';
			echo'<span original-title="" class="pages">Páginas ('.$pags.'):</span>';
			if($this->page > 1){
			echo'
			<a class="pagination_next" href="index.php?&p=1"><<</a>';
			echo'
			<a class="pagination_next" href="index.php?&p='.$back.'">< Anterior</a>';
			}
			while($count < 3){
				$count=$count+1;
				if($pagination<=$pags){
					echo' <a class="pagination'; 
					if($this->page == $pagination){
						echo'_current';
					}
					echo'" href="index.php?&p='.$pagination.'">'.$pagination.'</a>';
				}
				$pagination = $pagination+1;
			}
			if($this->page < $pags){
			echo'
			<a class="pagination_next" href="index.php?&p='.$forward.'">Siguiente ></a>';
			echo'
			<a class="pagination_next" href="index.php?&p='.$pags.'">>></a>';
			}
			echo'</div>';
					
			}
		}
	}
?>