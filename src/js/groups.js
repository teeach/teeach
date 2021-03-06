$(document).on("ready", function() {

	var group_h = $("#group_h").attr("value");

	//Add attachments
	var attachments = 0;
		
	$(".add_attachments").on("click", function() {
		attachments += 1;
		$(".attachments").append("<div class=\"attachment "+attachments+"\"><i class=\"fa fa-times del_attachment\" id=\""+attachments+"\"></i><input type=\"file\" name=\""+attachments+"\"></div>" );
	});

	$(".attachments").on("click", ".del_attachment", function() {
		console.log("Por el buen camino");
		attachments -= 1;
		$("div."+$(this).attr("id")).slideUp();
	});

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
		
		$(this).next(".edit_cal").css("display", "block");
		$(this).css("display", "none");

	});

	$("#cal").on("click",".send_cal_btn", function() {

		console.log("Correcto!");

		$.ajax({
			url: "../../src/ajax/send_calification.php",
			type: "POST",
			data: {},
			timeout: 10000,
			success: function() {
				$(this).css("display", "none");
			}
		});
	});

	$("tr").on("mouseover", function() {
		$(this).children("td").children(".user_actions").css("opacity", "1");
	});

	$("tr").on("mouseout", function() {
		$(this).children("td").children(".user_actions").css("opacity", "0");
	});

	$("#add_user_button").on("click", function() {
		$(this).css("background-color", "#c96");
		$("#group_add_user").css("display", "block");
		$(this).addClass("selected");
	});
});