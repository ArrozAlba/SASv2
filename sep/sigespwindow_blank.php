<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_inicio_sesion.php'";
	print "</script>";		
}

require_once("../shared/class_folder/class_funciones.php");
$io_funcion=new class_funciones();
$io_funcion->uf_limpiar_sesion();
// validación de los release necesarios para que funcione la definicion de sigesp_empresa.
require_once("../shared/class_folder/sigesp_release.php");
$io_release= new sigesp_release();
   $lb_valido=true;
    if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','estparsindis');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_61");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}		
	}
	$lb_valido=true;
    if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','basdatcmp');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_62");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}		
	}
	$lb_valido=true;
    if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sep_solicitud','nombenalt');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_72");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}		
	}
	$lb_valido=true;
    if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sep_tiposolicitud','estayueco');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_73");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}		
	}
	unset($io_release);
	//***********************************************************************************************************************************
	require_once("../shared/class_folder/sigesp_release.php");
    $io_release= new sigesp_release();
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sep_dta_cargos','codestpro1');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_3_50");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		unset($io_release);
	}
	//***********************************************************************************************************************************
	require_once("../shared/class_folder/sigesp_release.php");
    $io_release= new sigesp_release();
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sep_dts_cargos','codestpro1');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_3_51");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		unset($io_release);
	}
	//***********************************************************************************************************************************
	require_once("../shared/class_folder/sigesp_release.php");
    $io_release= new sigesp_release();
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sep_dtc_cargos','codestpro1');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_3_52");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		unset($io_release);
	}
	$lb_valido=true;
	//***********************************************************************************************************************************
	require_once("../shared/class_folder/sigesp_release.php");
    $io_release= new sigesp_release();
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sep_solicitud','tipsepbie');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_76");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		unset($io_release);
	}
	//***********************************************************************************************************************************
	require_once("../shared/class_folder/sigesp_release.php");
    $io_release= new sigesp_release();
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_existe = $io_release->uf_select_config('SEP','RELEASE','4_06');
	    if(!$lb_existe)
	    {
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_4_06");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		unset($io_release);
	}
	//***********************************************************************************************************************************
	require_once("../shared/class_folder/sigesp_release.php");
    $io_release= new sigesp_release();
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_existe = $io_release->uf_select_config('SEP','RELEASE','4_07');
	    if(!$lb_existe)
	    {
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_4_07");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		unset($io_release);
	}
	//***********************************************************************************************************************************
	require_once("../shared/class_folder/sigesp_release.php");
    $io_release= new sigesp_release();
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_existe = $io_release->uf_select_config('SEP','RELEASE','4_08');
	    if(!$lb_existe)
	    {
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_4_08");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		unset($io_release);
	}
	//***********************************************************************************************************************************
	require_once("../shared/class_folder/sigesp_release.php");
    $io_release= new sigesp_release();
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sep_solicitud','codusu');
	    if(!$lb_valido)
	    {
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_4_31");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		unset($io_release);
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Solicitud de Ejecuci&oacute;n Presupuestaria</title>
<meta http-equiv="imagetoolbar" content="no"> 
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #EFEBEF;
}

a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:active {
	color: #006699;
}

-->
</style>
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<link href="css/sep.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			
          <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Solicitud 
            de Ejecuci&oacute;n Presupuestaria</td>
			<td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  	</table>
	 </td>
	 <tr>
    <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
  </tr>
  </tr>
  <tr>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/sigesp_release.php");
    $io_release= new sigesp_release();
	
	$lb_valido=true;
    if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','estparsindis');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_61");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}		
	}
?>
</body>
</html>