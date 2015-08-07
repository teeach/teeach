$(document).ready(function(){

function fadeInRegistro() {
		$("#form_registro").fadeIn(700);
		$(".cuadro").css("height","780px");
};

function fadeInLogin() {
		$("#form_login").fadeIn(700);
		$(".cuadro").css("height","600px");
};

$("#registro").click(function(e) {
	$(".registro>p").addClass('activo');
	$(".registro>p").removeClass('noactivo');
	$(".acceso>p").removeClass('activo');
	$(".acceso>p").addClass('noactivo');
	$("#form_login").fadeOut(650, fadeInRegistro);
});

$("#acceso").click(function(e) {
	$(".registro>p").addClass('noactivo');
	$(".registro>p").removeClass('activo');
	$(".acceso>p").removeClass('noactivo');
	$(".acceso>p").addClass('activo');
	$("#form_registro").fadeOut(650, fadeInLogin);
});

});