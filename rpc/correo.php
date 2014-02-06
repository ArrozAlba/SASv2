<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Documento sin t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<?php

    if(array_key_exists("operacion",$_POST))
	{
	   $ls_operacion=$_POST["operacion"];
	   $ls_destinatario=$_POST["txtdestinatario"];
       $ls_codpro      =$_POST["txtcodpro"];
	}
    else
	{
	   $ls_operacion="";
       $ls_destinatario=$_POST["txtdestinatario"];
       $ls_codpro      =$_POST["txtcodpro"];
	}
    $ls_asunto="Registro de Proveedores";
    $ls_cuerpo="Su Registro en el fue Exitoso y su Codigo es :  ".$ls_codpro;
	if (mail($ls_destinatario,$ls_asunto,$ls_cuerpo))
    {
		print "Correo Enviado"
	}
	else
	{
		print "Falló el Envio del Correo al Proveedor".$ls_codpro;
	}	

?> 
</body>
</html>