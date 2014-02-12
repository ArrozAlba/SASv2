<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_inicio_sesion.php'";
	print "</script>";		
}
require_once("sigesp_saf_c_activo.php");
$ls_codemp = $_SESSION["la_empresa"]["codemp"];
$io_saf_tipcat= new sigesp_saf_c_activo();
$ls_rbtipocat=$io_saf_tipcat->uf_select_valor_config($ls_codemp);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Sistema de Activos Fijos</title>
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

<?php
	
	// validación de los release necesarios poara que funcione el sistema de nómina
	require_once("../shared/class_folder/sigesp_release.php");
    $io_release= new sigesp_release();
	$lb_valido=true;
    if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('saf_activo','codestpro1');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('saf_movimiento','codrespri');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD 3.32");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('saf_dt_movimiento','estcat');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD 3.33");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('saf_item');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD 2008_1_04");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		} 
    }
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('saf_activo','codite');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD 2008_1_05");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('siv_articulo','codact');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD 2001_1_06");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('siv_dt_recepcion','estregact');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD 2001_1_07");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('siv_dt_despacho','estincact');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD 2001_1_08");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('saf_cambioresponsable','codact');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD 2001_1_09");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('saf_movimiento','ubigeoact');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD 2.27 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
   $li_tamano=$io_release->io_function_db->uf_tamano_type_columna('saf_conservacionbien','codconbie');
   if ($li_tamano=="1")
   {
	$io_release->io_msg->message(utf8_encode(" Debe Procesar Instala/Procesos/Mantenimiento/Release Version 2008_3_03 "));
	print "<script language=JavaScript>";
	print "location.href='../index_modules.php'";
	print "</script>";				   	   
   }
   $lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('saf_activo','tipinm');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD 2008_3_38");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('saf_edificios');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD 2008_3_39");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('saf_tipoestructura');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD 2008_3_40");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('saf_componente');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD 2008_3_41");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('saf_edificiotipest');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD 2008_3_42");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$tamano1=$io_release->io_function_db->uf_tamano_type_columna('saf_activo','codrot');
		if ($tamano1=="1")
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_60");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
    if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('saf_autsalida');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_07");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
    if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('saf_dt_autsalida');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_07");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
    if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('saf_entrega');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_08");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	$lb_valido=true;
    if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('saf_dt_entrega');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_09");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
    if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('saf_prestamo');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_10");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}  
    $lb_valido=true;
    if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('saf_dt_prestamo');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_10");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}	
	$lb_valido=true;
    if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('saf_dta','estactpre');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_11");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	} 
    $lb_valido=true;
    if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('saf_dta','codunipre');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_12");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
    $lb_valido=true;
    if ($lb_valido)
	   {
		 $lb_existe = $io_release->io_function_db->uf_select_constraint('saf_activo','fk_saf_activo__saf_item');	
		 if ($lb_existe)
	 	    {
			  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_27");
			  print "<script language=JavaScript>";
			  print "location.href='../index_modules.php'";
			  print "</script>";		
		    }
	   }
    $lb_valido=true;
    if ($lb_valido)
	   {
		 $lb_existe = $io_release->uf_select_config('SAF','RELEASE','4_33');	
		 if (!$lb_existe)
	 	    {
			  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_4_33");
			  print "<script language=JavaScript>";
			  print "location.href='../index_modules.php'";
			  print "</script>";		
		    }
	   }
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Activos Fijos</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  </tr>
  <tr>
  <?php 
    if ($ls_rbtipocat == 1) 
    {
   ?>
   <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_csc.js"></script></td>
  <?php 
    }
	elseif ($ls_rbtipocat == 2)
	{
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_cgr.js"></script></td>
  <?php 
	}
	else
	{
   ?>
	<td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  <?php 
	}
   ?>
  </tr>
</table>

</body>
</html>