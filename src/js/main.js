if(typeof jQuery == "undefined") {
	alert("Error: jQuery no está cargado, y por lo tanto, Teeach no puede funcionar correctamente");
}


$(document).on("ready", function() {

	$('.tip').before("<i class='fa fa-question-circle tip_button'></i>");

	$('.tip_button').on("click", function() {
		$(this).toggleClass('fa-question-circle');
		$(this).toggleClass('fa-times-circle tip_close');
		$(this).next().toggle();		
	});
});

//Popups
function open_popup() {
	$('#dialog').dialog();
};