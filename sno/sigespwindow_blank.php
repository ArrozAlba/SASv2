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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey)){
		window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ 
		return false; 
		} 
		} 
	}
</script>
<title >Sistema de N&oacute;mina</title>
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
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {color: #6699CC}
-->
</style>
</head>

<body>
<form name="form1" method="post" action="">
</form>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Nómina</td>
			<td width="346" bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
	  	    <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
        </table>
    </td>
  </tr>
  <tr>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
</table>
<?php
	
	// validación de los release necesarios poara que funcione el sistema de nómina
	require_once("../shared/class_folder/sigesp_release.php");
    $io_release= new sigesp_release();
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_dt_spg');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 1");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_programacionreporte');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_ipasme_dependencias');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 3");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personal','cenmedper');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 4");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_concepto','aplarccon');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 5");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_trabajoanterior','emppubtraant');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 6");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_metodobanco','debcuelph');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 7");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_metodobanco','codagelph');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 8");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','estmodest');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 9");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personalnomina','codclavia');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 10 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_estudiorealizado','desestrea');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 11");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sss_derechos_usuarios','codintper');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 12 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_trabajoanterior','codded');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 13");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('scv_categorias');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 38 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_prestamosamortizado');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 39 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sss_registro_eventos','codintper');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 41");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_periodo','peradi');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.3");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_hperiodo','peradi');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.4");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_thperiodo','peradi');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.5");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_tablavacacion','anoserpre');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.15");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_metodobanco','numconnom');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.17");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_constanciatrabajo','marinfcont');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.19");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_fideiperiodo','diafid');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.20 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_fideicomiso','capantcom');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.21 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_cestaticket','codcli');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.23 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_ubicacionfisica','codpai');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.29 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_ubicacionfisica','dirubifis');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.30 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_constanciatrabajo','tamletpiecont');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.34 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_estudiorealizado','aprestrea');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.35 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personal','turper');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.36 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}

	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personal','hcmper');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.37 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_familiar','hcfam');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.38 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_estudiorealizado','horestrea');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.39 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personal','tipsanper');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.40 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_nomina','conpernom');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.44 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_hnomina','conpernom');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.45 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_thnomina','conpernom');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.46 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_constanciatrabajo','arcrtfcont');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.47 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_nomina','conpronom');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.63 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_hnomina','conpronom');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.64 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_thnomina','conpronom');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.65 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_concepto','conprocon');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.66 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_hconcepto','conprocon');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.67 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_thconcepto','conprocon');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.68 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_proyecto');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.69 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_proyectopersonal');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.70 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_hproyecto');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.71 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_hproyectopersonal');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.72 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_thproyecto');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.73 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_thproyectopersonal');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.74 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_proyectopersonal','pordiames');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.75 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_hproyectopersonal','pordiames');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.76 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_thproyectopersonal','pordiames');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.77 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_componente');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.79 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_rango');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.80 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personal','codcom');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.81 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personal','numexpper');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.82 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_nomina','titrepnom');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.86 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_hnomina','titrepnom');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.87 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_thnomina','titrepnom');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.88 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personalnomina','codunirac');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.90 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_hpersonalnomina','codunirac');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.91 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_thpersonalnomina','codunirac');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.92 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_nomina','codorgcestic');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.95 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_hnomina','codorgcestic');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.96 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_thnomina','codorgcestic');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.97 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personalnomina','pagtaqper');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.98 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_hpersonalnomina','pagtaqper');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.99 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_thpersonalnomina','pagtaqper');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 3.00 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_metodobanco','pagtaqnom');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 3.01 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personal','codpainac');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 3.02 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_constante','conespseg');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 3.13 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_hconstante','conespseg');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 3.14 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_thconstante','conespseg');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 3.15 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_archivotxt');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 3.16 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_archivotxtcampo');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 3.17 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_tabulador','maxpasgra');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 3.18 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_htabulador','maxpasgra');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 3.19 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_thtabulador','maxpasgra');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 3.20 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_beneficiario');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 3.21 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_beneficiario','forpagben');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 3.22 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_archivotxtcampo','tipcam');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 3.23 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_beneficiario','nacben');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 3.24 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_tipopersonalsss');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_10");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personal','codtippersss');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_11 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_sueldominimo');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_15 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_concepto','intingcon');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_19 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_hconcepto','intingcon');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_20 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_thconcepto','intingcon');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_21 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_dt_spi');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_22 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('srh_unidadvipladin');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_23 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_clasificacionobrero');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_26 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_hclasificacionobrero');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_27 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_thclasificacionobrero');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_28 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personalnomina','grado');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_29 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_hpersonalnomina','grado');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_30 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_thpersonalnomina','grado');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_31 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personalnomina','fecascper');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_32 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_hpersonalnomina','fecascper');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_33 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_thpersonalnomina','fecascper');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_34 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_prestamos','tipcuopre');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_35 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_hprestamos','tipcuopre');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_36 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_thprestamos','tipcuopre');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_37 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_personalpension');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_49 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_hpersonalpension');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_50 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_thpersonalpension');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_51 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personal','fecreingper');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_56 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_nomina','confidnom');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_66 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_hnomina','confidnom');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_67 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_thnomina','confidnom');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_68 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_periodo','ingconper');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_69 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_hperiodo','ingconper');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_70 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_thperiodo','ingconper');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_71 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_fideiconfigurable','cueprefid');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_72 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_concepto','repacucon');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_82 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_hconcepto','repacucon');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_83 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_thconcepto','repacucon');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_84 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_clasificacionobrero','anovig');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_88 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_hclasificacionobrero','anovig');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_89 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_thclasificacionobrero','anovig');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_90 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_beneficiario','nexben');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 1.92 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personal','enviorec');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 1.94 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_beneficiario','cedaut');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 1.95 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_categoria_rango');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_99 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_rango','codcat');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_00 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_nomina','informa');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_05 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_hnomina','informa');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_06 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_thnomina','informa');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_07 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_nomina','divcon');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_09 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_hnomina','divcon');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_09 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_thnomina','divcon');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_09 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_concepto','quirepcon');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_10 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_hconcepto','quirepcon');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_10 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_thconcepto','quirepcon');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_10 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_salida','priquisal');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_11 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_hsalida','priquisal');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_11 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_thsalida','priquisal');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_11 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personal','fecleypen');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_12 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personalnomina','descasicar');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_24 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_programacionreporte','numcarf');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_25 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_causales');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_33 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personal','codcausa');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_34 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_metodobanco','nroref');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_36 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_concepto','asifidper');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_44 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_hconcepto','asifidper');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_45 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_thconcepto','asifidper');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_46 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personalnomina','coddep');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_49 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_hpersonalnomina','coddep');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_50 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_thpersonalnomina','coddep');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_51 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_asignacioncargo','grado');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_57 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_hasignacioncargo','grado');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_58 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_thasignacioncargo','grado');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_59 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_dt_scg_int');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_64 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_vista('cierre_contableaportes_contable');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_70 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personal','situacion');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_75 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personal','fecsitu');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_86 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personal','talcamper');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_87 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_dt_scg_int','codestpro1');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_90 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personal','anoservprecont');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_92 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personalpension','tipjub');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_93 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_thpersonalpension','tipjub');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_94 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_hpersonalpension','tipjub');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_95 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personal','anoservprefijo');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_96 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personal','codorg');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_36 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_archivotxt','acumon');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_47 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_fideicomiso','conpreant');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_66 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_vacacpersonal','diapervac');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_67 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_hvacacpersonal','pagpersal');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_69 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_dt_spi','estcla');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_71 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_codigounicorac');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_74 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_hcodigounicorac');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_75 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_thcodigounicorac');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_76 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido)
	{
		$tamano2=$io_release->io_function_db->uf_tamano_type_columna('sno_asignacioncargo','claasicar'); 
		if ($tamano2=="5")
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_77 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personal','porcajahoper');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_92 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_familiar','hijesp');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_93 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_concepto','persalnor');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_96 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_hconcepto','persalnor');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_97 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_thconcepto','persalnor');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_98 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personalnomina','salnorper');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_99 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_hpersonalnomina','salnorper');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_00 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_thpersonalnomina','salnorper');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_01 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_cestaticket','mondesdia');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_14 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_dt_spg','codfuefin');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_15 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_constante','esttopmod');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_17 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_hconstante','esttopmod');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_18 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_thconstante','esttopmod');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_19 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_constantepersonal','montopcon');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_20 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_hconstantepersonal','montopcon');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_21 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_thconstantepersonal','montopcon');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_22 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sigesp_correo');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_32 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_correo','msjremitente');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_33 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_rd');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_40 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_nomina','recdocpagperche');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_42 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_hnomina','recdocpagperche');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_43 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_thnomina','recdocpagperche');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_44 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_encargaduria');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_45 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_concepto','aplresenc');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_47 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_hconcepto','aplresenc');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_48 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_thconcepto','aplresenc');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_49 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_constante','conperenc');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_50 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_hconstante','conperenc');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_51 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_thconstante','conperenc');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_52 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personalnomina','estencper');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_53 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_hpersonalnomina','estencper');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_54 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_thpersonalnomina','estencper');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_55 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_hencargaduria');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_56 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_thencargaduria');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_57 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_fideicomiso','porintcap');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_62 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_dt_spg','spg_codemp');
		if($lb_valido==true)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_64 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	$lb_valido=TRUE;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_nomina','estctaalt');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_65 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_hnomina','estctaalt');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_66 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_thnomina','estctaalt');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_67 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido1=$io_release->io_function_db->uf_select_column('sno_constantepersonal','conperenc');
		if($lb_valido1==true)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_2_01 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_entes');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_2_16 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_vista('calculo_conceptospersonal');	
		if ($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_2_10 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_vista('calculo_personal');	
		
		if ($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_2_11 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_vista('calculo_personaltabulador');	
		if ($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_2_12 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_index("sno_conceptopersonal","index_conceptopersonal_1");
		if ($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_2_17 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_index("sno_conceptopersonal","index_conceptopersonal_2");
		if ($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_2_18 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_familiar','estbonjug');
		if ($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_3_23 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('srh_gerencia');
		if ($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_3_25 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personal','anoperobr');
		if ($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_3_26 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personal','codger');
		if ($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_3_27 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('srh_departamento','codger');
		if ($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_3_28 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_beneficiario','numexpben');
		if ($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_3_29 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personal','carantper');
		if ($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_3_31 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_primasdocentes');
		if ($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_3_36 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_primadocentepersonal');
		if ($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_3_37 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_hprimasdocentes');
		if ($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_3_38 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_hprimadocentepersonal');
		if ($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_3_39 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_thprimasdocentes');
		if ($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_3_40 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_thprimadocentepersonal');
		if ($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_3_41 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->uf_select_config('SNO','RELEASE','3_42');
		if ($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_3_42 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->uf_select_config('SNO','RELEASE','4_11');
		if ($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_4_11 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->uf_select_config('SNO','RELEASE','4_12');
		if ($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_4_12 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_familiar','cedula');
		if ($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_4_22 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personal','rifper');
		if ($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_4_27 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personalisr','codconret');
		if ($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_4_28 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_vacacpersonal','calpagvac');
		if ($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_4_30 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_nomina','racobrnom');
		if ($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_4_35 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_hnomina','racobrnom');
		if ($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_4_36 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_thnomina','racobrnom');
		if ($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_4_37 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
?>
</body>
</html>