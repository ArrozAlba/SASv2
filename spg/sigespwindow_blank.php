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
<title >Sistema de Presupuesto de Gastos</title>
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
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
<tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Contabilidad Presupuestaria de Gasto</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  </tr>
  <tr>
         <?php
	   if(array_key_exists("confinstr",$_SESSION["la_empresa"]))
	  {
      if($_SESSION["la_empresa"]["confinstr"]=='A')
	  {
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  <?php
      }
      elseif($_SESSION["la_empresa"]["confinstr"]=='V')
	  {
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_2007.js"></script></td>
  <?php
      }
      elseif($_SESSION["la_empresa"]["confinstr"]=='N')
	  {
   ?>
       <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_2008.js"></script></td>
  <?php
      }
	  	 }
	  else
	  {
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_2008.js"></script></td>
	<?php 
	}
	?>
  </tr>
</table>
<?php
	
	require_once("../shared/class_folder/sigesp_release.php");
    $io_release= new sigesp_release();
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','salinipro');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','salinieje');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release");
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
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','nomorgads');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release");
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
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_cmp_md','codfuefin');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_cmp_md','coduac');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','codasiona');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_cmp','esttrfcmp');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
		if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','estciespg');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','confinstr');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('spg_ep3','estreradi');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 3_87");
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
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_moneda','imamon');
		if($lb_valido==true)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_03");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_moneda','tascamaux');
		if($lb_valido==true)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_04");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_moneda','tascam');
		if($lb_valido==true)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_05");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sigesp_dt_moneda');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_06");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','estmodprog');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_25");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('spg_dtmp_cmp','codfuefin');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_36");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('spg_dt_cmp','codfuefin');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_37");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('spg_regmodprogramado');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_4_71");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('spg_dtmp_mensual');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_4_20");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
?>
</body>
</html>