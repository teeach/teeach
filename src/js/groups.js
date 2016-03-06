$(document).on("ready", function() {

	var group_h = $("#group_h").attr("value");

	//Load group requests
	$.ajax({
		url: "../../src/ajax/load_grouprequests.php",
		type: "POST",
		data: {group_h:group_h},
		success: function(num_requests) {
			$("#num_requests").html("("+num_requests+")");
		}
	});

	$("#view_cal").on("click", function() {

		var work_h = $("#work_h").attr("value");

		$.ajax({
			beforeSend: function() {
				$("#cal").html('<i class="fa fa-circle-o-notch fa-spin" style="color: #320"></i>');
			},
			url: "../../src/ajax/load_califications.php",
			type: "POST",
			data: {work_h:work_h},
			timeout: 10000,
			success: function(cal) {
				$("#cal").html(cal);
			},
			error: function() {
				console.log("error");
			}
		});

	});

	$("#cal").on("click",".new_cal_btn", function() {
		
		console.log("Correcto!");

		$(this).prev().html("<form><table><tr><td>Nota</td><td>Observaciones</td></tr><tr><td><input type='text' name='mark'></td><td><textarea name='observations'></textarea></td></tr></table></form><input type='submit' value='Guardar'>");

	});

	$("tr").on("mouseover", function() {
		$(this).children("td").children(".user_actions").css("opacity", "1");
	});

	$("tr").on("mouseout", function() {
		$(this).children("td").children(".user_actions").css("opacity", "0");
	});

});

/*function new_cal() {
	
	
}*/