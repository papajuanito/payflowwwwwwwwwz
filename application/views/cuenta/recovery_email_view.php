<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
	"http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Solicitud de Cambio de Contraseña - Guerreros de Luz</title>
</head>
<body>
	<h1>Hola Guerrero de Luz</h1>
	
	<p>Hemos recibido una solicitud para cambiar tu contraseña en Guerreros de Luz. Para cambiar tu contraseña accede la siguiente dirección:</p>
	
	<a href="<?php echo site_url ('cuenta/reset_password/'.$guerrero->recovery_token); ?>"><?php echo site_url ('cuenta/reset_password/'.$guerrero->recovery_token); ?></a>
	
	<p>Si no efectuaste la solicitud de cambio de contraseña ignora este correo electrónico.</p>
</body>
</html>
