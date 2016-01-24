$(document).on("ready", function() {

	//Lang Selector

	$("#lang_selector").change(function() {
		$("#lang_form").submit();
	});

	//Password secure-insecure

	$("#initial_settings_button").on("click", function() {
		var pass = document.initial_settings.pass.value;
		if (pass.length < 8) {
			$("#initial_settings_button").css("display", "none");
			$("#initial_settings_advice").css("display", "inline-block");
		} else {
			$("#initial_settings").submit();
		}
	});

	//Auto localhost

	var url = $("#url").attr("value");
	if (url.contains("localhost")) {
		$("#server_db").attr("value", "localhost");
	}

});

//Old functions - DO NOT DELETE!!

function getUrl() {
	var url = location.href;
	var url_installation = url.replace("/install.php?step=3","");
	document.getElementById("url").value = url_installation;
}

function goStep3() {
	location.href = "install.php?step=3";
}