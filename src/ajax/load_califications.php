<?php
	include('../../core.php');
	$System = new System();	
	$con = $System->conDB();

	$lang = $System->parse_lang();

	$work_h = $_POST['work_h'];

	$query = $System->queryDB("SELECT * FROM pl_califications WHERE work_h='$work_h'", $con);

	echo '
		<table class="ui_table">
			<thead>
				<th>'.$lang["student-a"].'</th>
				<th>'.$lang["sending"].'</th>
				<th>'.$lang["calification"].'</th>
			</thead>
			<tbody>
	';

	while ($row = $System->fetch_array($query)) {

		$user_h = $row['user_h'];
		$description = $row['description'];
		$h = $row['h'];

		$query2 = $System->queryDB("SELECT * FROM pl_users WHERE h='$user_h'", $con);
		$row2 = $System->fetch_array($query2);
		$name = $row2['name'];
		$surname = $row2['surname'];
		$mark = $row['mark'];
		$observations = $row['observations'];
		
		echo '
			<tr id="'.$h.'">
				<td>'.$name.' '.$surname.'</td>
				<td>'.$description.'</td>';

				if($mark == 0 && $observations == "") {
						
					echo '<td><a class="new_cal_btn">'.$lang["rate"].'</a><div class="edit_cal" style="display:none"><form><table class="cal_insert"><tr><td>Nota</td><td>Observaciones</td></tr><tr><td><input type="text" name="mark" class="cal_insert_mark"></td><td><textarea name="observations" class="cal_insert_observations"></textarea></td></tr></table></form><input type="submit" value="Guardar" class="send_cal_btn"></div></td>';

				} else {

					echo '<td>'.$mark.'</td>';

				}

		echo '	
			</tr>
		';
	}

	echo '
			</tbody>
		</table>
	';
?>