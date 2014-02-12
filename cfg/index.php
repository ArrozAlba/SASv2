<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
// validación de los release necesarios para que funcione la definicion de sigesp_empresa.
require_once("../shared/class_folder/sigesp_release.php");
$io_release= new sigesp_release();
$lb_valido=true;
if ($lb_valido)
   {
	 $lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','modageret');	
	 if ($lb_valido==false)
		{
		  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD");
		  print "<script language=JavaScript>";
		  print "location.href='../index_modules.php'";
		  print "</script>";		
		}
   }
$lb_valido=true;
if ($lb_valido)
   {
	 $lb_valido=$io_release->io_function_db->uf_select_column('sss_registro_eventos','codintper');	
	 if ($lb_valido==false)
		{
		  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD");
		  print "<script language=JavaScript>";
		  print "location.href='../index_modules.php'";
		  print "</script>";		
		}
   } 
$lb_valido=true;
if ($lb_valido)
   {
	 $lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','nomres');	
	 if ($lb_valido==false)
		{
		  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD");
		  print "<script language=JavaScript>";
		  print "location.href='../index_modules.php'";
		  print "</script>";		
		}
   } 
$lb_valido=true;
if ($lb_valido)
   {
	 $lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','concomiva');	
	 if ($lb_valido==false)
		{
		  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD");
		  print "<script language=JavaScript>";
		  print "location.href='../index_modules.php'";
		  print "</script>";		
		}
   } 
$lb_valido=true;
if ($lb_valido)
   {
	 $lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','scctaben');	
	 if ($lb_valido==false)
		{
		  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD");
		  print "<script language=JavaScript>";
		  print "location.href='../index_modules.php'";
		  print "</script>";		
		}
   } 
$lb_valido=true;
if ($lb_valido)
   {
	 $lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','estmodiva');	
	 if ($lb_valido==false)
		{
		  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD");
		  print "<script language=JavaScript>";
		  print "location.href='../index_modules.php'";
		  print "</script>";		
		}
   } 
$lb_valido=true;
if ($lb_valido)
   {
	 $lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','activo_t');	
	 if ($lb_valido==false)
		{
		  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD");
		  print "<script language=JavaScript>";
		  print "location.href='../index_modules.php'";
		  print "</script>";		
		}
   } 
$lb_valido=true;
if ($lb_valido)
   {
	 $lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','pasivo_t');	
	 if ($lb_valido==false)
		{
		  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD");
		  print "<script language=JavaScript>";
		  print "location.href='../index_modules.php'";
		  print "</script>";		
		}
   } 
$lb_valido=true;
if ($lb_valido)
   {
	 $lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','resultado_t');	
	 if ($lb_valido==false)
		{
		  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD");
		  print "<script language=JavaScript>";
		  print "location.href='../index_modules.php'";
		  print "</script>";		
		}
   } 
$lb_valido=true;
if ($lb_valido)
   {
	 $lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','c_financiera');	
	 if ($lb_valido==false)
		{
		  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD");
		  print "<script language=JavaScript>";
		  print "location.href='../index_modules.php'";
		  print "</script>";		
		}
   } 
$lb_valido=true;
if ($lb_valido)
   {
	 $lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','c_fiscal');	
	 if ($lb_valido==false)
		{
		  $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD");
		  print "<script language=JavaScript>";
		  print "location.href='../index_modules.php'";
		  print "</script>";		
		}
   } 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey))
		{
			window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ return false;} 
		} 
	}
</script>
<head>
<title>Sistema Administrativo HUAYRA -**- C.V.A.L -**- , Modulo Configuraci&oacute;n</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

</head>
<frameset rows="*" cols="166,*" framespacing="0" frameborder="NO" border="0">
	  <frame src="left.php" name="leftFrame" scrolling="YES" noresize>
	  <frame src="main.php" name="mainFrame">
  </frameset>
<noframes><body>
</body>
</noframes>
</html>