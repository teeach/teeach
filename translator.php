<?
if(!isset($_GET["action"])){
	$action = "translate";
}else{
	$action = $_GET["action"];
}
if($action == "save"){
	if($_GET["from"] == "translate"){
		header("Content-type: text/plain; charset=utf-8");
		header("Content-Disposition: attachment; filename=".$_GET["lang2"].".json");
		$lang = [];
		foreach($_POST as $key=>$value){
			$lang[$key] = $value;
		}
		print json_encode($lang,JSON_PRETTY_PRINT);
	}elseif($_GET["from"] == "edit"){
		header("Content-type: text/plain; charset=utf-8");
		header("Content-Disposition: attachment; filename=".$_GET["lang1"].".json");
		$lang = [];
		foreach($_POST as $key=>$value){
			$lang[$key] = $value;
		}
		print json_encode($lang,JSON_PRETTY_PRINT);
	}
}elseif($action == "translate"){
	echo'
	<html>
		<head>
			<meta charset="UTF-8">
			<title>Teeach Translator</title>
		</head>
		
		<body>
			<a href="translator.php"><< Back</a> <a href="translator.php?action=edit">Edit language</a><br><br>
		';
				include("core.php");
				$System = new System;
				
				
				if(isset($_GET["lang1"])){
					$lang1 = $_GET["lang1"];
					$lang1_array = $System->parse_lang("src/lang/".$lang1.".json");
					if($_GET["lang_new"] != ""){
						//~ echo"asdfg";
						$lang2 = $_GET["lang_new"];
						//~ $lang2_array = $lang1_array;
					}else{
						$lang2 = $_GET["lang2"];
						$lang2_array = $System->parse_lang("src/lang/".$lang2.".json");
					}
					//~ 
					//~ print_r($lang1_array);
					//~ echo'<br>';
					//~ print_r($lang2_array);
					echo'
					<form method="post" action="translator.php?action=save&lang1='.$lang1.'&lang2='.$lang2.'&from=translate">
						<table>';
						echo'<tr><td>Key</td><td>'.$lang1.'</td><td>'.$lang2.'</td></tr>';
						foreach($lang1_array as $key=>$lang1_value){
							if(isset($lang2_array[$key])){
								echo'<tr><td>'.$key.'</td><td>'.$lang1_value.'</td><td><textarea name = "'.$key.'">'.$lang2_array[$key].'</textarea></td></tr>';
							}else{
								echo'<tr><td>'.$key.'</td><td>'.$lang1_value.'</td><td><textarea name = "'.$key.'"></textarea></td></tr>';
							}
						}
						echo'
							<tr><td><input type="submit" value="Save"></td></tr>
						</table>
						</form>
					';
				}else{
					echo'
					<form method="get" action="translator.php?action=translate">
						Original language:
						<select name="lang1">';
							$fp_langs = fopen("src/lang/langs.json", "r");
							$rfile_langs = fread($fp_langs, filesize("src/lang/langs.json"));
							$json_langs = json_decode($rfile_langs);
							foreach ($json_langs->{"langs"} as $index => $row_langs) {
								echo '<option value="'.$row_langs.'">'.$row_langs.'</option>';
							}
						echo'</select>
						<br>
						Target language:
						<select name="lang2">';
							$fp_langs = fopen("src/lang/langs.json", "r");
							$rfile_langs = fread($fp_langs, filesize("src/lang/langs.json"));
							$json_langs = json_decode($rfile_langs);
							foreach ($json_langs->{"langs"} as $index => $row_langs) {
								echo '<option value="'.$row_langs.'">'.$row_langs.'</option>';
							}
						echo'</select> (existing) or <input type="text" name="lang_new"> (new)<br>
						<input type="submit" value="Translate">
					</form>';
				}
	echo'
		</body>
	</html>
	';
}elseif($action == "edit"){
	echo'
	<html>
		<head>
			<meta charset="UTF-8">
			<title>Teeach Translator</title>
			<script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
		</head>
		
		<body>
		<a href="translator.php"><< Back</a> <a href="translator.php?action=translate">Translate</a><br><br>
		';
				include("core.php");
				$System = new System;
				
				
				if(isset($_GET["lang1"])){
					$lang1 = $_GET["lang1"];
					$lang1_array = $System->parse_lang("src/lang/".$lang1.".json");
					
					echo'<form method="post" action="translator.php?action=save&lang1='.$lang1.'&from=edit">
					<table class="values">
					<tr><td>Key</td><td>'.$lang1.'</td></tr>';
					foreach($lang1_array as $key=>$value){
						echo'<tr><td>'.$key.'</td><td><textarea name = "'.$key.'">'.$value.'</textarea></td></tr>';
					}
					echo'
						</table>
						<table>
						<tr><td><input type="text" class="new_val"></td><td><div style="padding:5px;border-radius:5px;width:35px;height:20px;background:gray;" class="add_val">Add</div></td></tr>
						</table>
						Subir a sevidor <input type="checkbox" name="upload" checked>
						<br>
						<input type="submit" value="Save">
						</form>
					';
				}else{
					echo'
					<form method="get" action="translator.php">
						Original language:
						<select name="lang1">';
							$fp_langs = fopen("src/lang/langs.json", "r");
							$rfile_langs = fread($fp_langs, filesize("src/lang/langs.json"));
							$json_langs = json_decode($rfile_langs);
							foreach ($json_langs->{"langs"} as $index => $row_langs) {
								echo '<option value="'.$row_langs.'">'.$row_langs.'</option>';
							}
						echo'</select><br>
						<input type="hidden" value="edit" name="action">
						<input type="submit" value="Edit">
					</form>';
				}
	echo'
		<script>
		$( ".add_val" ).click(function() {
			if($(".new_val").val() != ""){
				$( ".values" ).append("<tr><td>"+$(".new_val").val()+"</td><td><textarea name=\""+$(".new_val").val()+"\"></textarea></td></tr>" );
			}
			$(".new_val").val("")
		});
		
		</script>
		</body>
	</html>
	';
}
?>
