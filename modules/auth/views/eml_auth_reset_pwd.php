<?php $link = site_url('auth/reset_password?key='.urlencode($encrypted_key)); ?>
<h1>Solicitud de recuperacion de contrase&ntilde;a</h1>
<h2>Sistema de inventario TIHANY</h2>

<p>Se ha recibido una solicitud de recuperaci&oacute;n de contrase&ntilde;a</p>
<p><small>Si usted no ha realizado esta solicitud, haga caso omiso a este correo</small></p>
<p>Para iniciar el proceso de recuperaci&oacute;n de contrase&ntilde;a, haga click en el siguiente
v&iacute;nculo, o c&oacute;pielo y p&eacute;guelo en la barra de direcci&oacute;n de su navegador Web:</p>

<p><strong><a href="<?php echo $link; ?>"><?php echo $link; ?></a></strong></p>

<p>En el formulario que aparece a continuaci&oacute;n, ingrese la siguiente clave donde se es requerido:</p>

<p style="text-align:center"><strong><?php echo $hash; ?></strong></p>