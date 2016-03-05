$(document).on("ready", function() {

	var user_h = $("#user_h").attr("value");

	//Load num. messages unread
	$.ajax({
		url: "../../src/ajax/load_messagesunread.php",
		type: "POST",
		data: {user_h:user_h},
		success: function(num_messages_unread) {
			$("#num_messages_unread").html("("+num_messages_unread+")");
		}
	});


});