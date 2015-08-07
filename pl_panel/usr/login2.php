<?php
  include("../../core.php");

  $System = new System();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="description" content="La educación del futuro... hoy">
<?php $System->set_head(); ?>
<title>Accede a Teeach</title>
<link href="../../src/css/login_new.css" type="text/css" rel="stylesheet">
<script src="JS/jquery-1.9.1.min.js"></script>
<style type="text/css">
@-ms-viewport{width: extend-to-zoom; zoom: 1.0;}
</style>
</head>

<body ondragstart="return false;">

<div align="center">

<div class="cuadro">

<div class="accreg">
<div class="acceso" id="acceso"><p class="activo">ACCESO</p></div>
<div class="registro" id="registro"><p class="noactivo">REGISTRO</p></div>
</div>


<div class="form">
<form id="form_login" accept-charset="utf-8" action="login.php?action=check" method="post">
<span class="etiqueta">Nombre de usuario</span>
<div align="center"><input class="inputform" name="username" type="text" placeholder="John Doe" maxlength="35" required /></div>

<span class="etiqueta">Contraseña</span>
<div align="center"><input class="inputform" name="password" type="password" placeholder="************" maxlength="50" required /></div>

<div align="center"><input id="acceder" name="submit" type="submit" value="ACCEDER"></div>
</form>
</div>

<div class="form">
<form id="form_registro" accept-charset="utf-8" action="register.php?action=success" method="post">
<span class="etiqueta">Nombre de usuario</span>
<div align="center"><input class="inputform" name="username" type="text" placeholder="John Doe" maxlength="35" required /></div>

<span class="etiqueta">Email</span>
<div align="center"><input class="inputform" name="email" type="email" placeholder="johndoe@email.com" maxlength="35" required /></div>

<span class="etiqueta">Contraseña</span>
<div align="center"><input class="inputform" name="password" type="password" placeholder="************" maxlength="50" required /></div>

<span class="etiqueta">Repetir contraseña</span>
<div align="center"><input class="inputform" name="rpassword" type="password" placeholder="************" maxlength="50" required /></div>

<span class="etiqueta">Contraseña de acceso</span>
<div align="center"><input class="inputform" name="accesspass" type="password" placeholder="************" maxlength="50" required /></div>

<div align="center"><input id="registrar" name="submit" type="submit" value="REGISTRARSE"></div>
</form>
</div>

</div><!-- FIN cuadro -->

<div class="pass_olvidada">
<a href="#">¿Has olvidado tu contraseña?</a>
</div>



</div><!--fin alineamiento central-->

<?php $System->set_footer(); ?>

<script src="../../src/js/login.js"></script>
</body>
</html>