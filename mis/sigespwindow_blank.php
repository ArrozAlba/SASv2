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
<title >M&oacute;dulo Integrador Sigesp</title>
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
</head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="762" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7"><table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
      <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">M&oacute;dulo Integrador</td>
            <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-peque&ntilde;as"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
      <tr>
        <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
        <td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
      </table></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
</table>
<?php
	
	require_once("../shared/class_folder/sigesp_release.php");
    $io_release= new sigesp_release();
	$lb_valido=true;
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
		$lb_valido=$io_release->io_function_db->uf_select_column('soc_enlace_sep','estordcom');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.2 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('scb_movbco','nrocontrolop');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.42 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('scb_movbco_spgop','baseimp');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.43 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sep_solicitud','fechaconta');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.48 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('soc_ordencompra','fechaconta');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.49 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('cxp_solicitudes','fechaconta');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.50 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('cxp_sol_dc','fechaconta');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.51 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('scb_movbco','fechaconta');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.52 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('scb_movcol','fechaconta');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.53 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sob_asignacion','fechaconta');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.54 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sob_contrato','fechaconta');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.55 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_dt_scg','fechaconta');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.56 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_dt_spg','fechaconta');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.57 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('saf_depreciacion','fechaconta');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.58 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('scb_movbco_fuefinanciamiento');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Release 3.10 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		} 
     }
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('scb_movbco','conanu');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_25 ");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','estvaldis');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_39");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('saf_depreciacion_int');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2_63");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		} 
     }
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','estintcred');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_83");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('scb_movbco','estant');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_50");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
    if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('scb_movbco_anticipo');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_51");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('spi_dt_cmp','estcla');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_72");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('spi_dtmp_cmp','estcla');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_73");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_cmp','estrenfon');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 3_94");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_cmp','codfuefin');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 3_95");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
?>
</body>
</html>