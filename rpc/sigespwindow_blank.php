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

$li_diasem = date('w');
switch ($li_diasem){
  case '0': $ls_diasem='Domingo';
  break; 
  case '1': $ls_diasem='Lunes';
  break;
  case '2': $ls_diasem='Martes';
  break;
  case '3': $ls_diasem='Mi&eacute;rcoles';
  break;
  case '4': $ls_diasem='Jueves';
  break;
  case '5': $ls_diasem='Viernes';
  break;
  case '6': $ls_diasem='S&aacute;bado';
  break;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Registro de Proveedores y Contratistas</title>
<meta http-equiv="imagetoolbar" content="no">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<link href="css/rpc.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:hover {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style></head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu">
	<table width="778" border="0" align="center" cellpadding="0" cellspacing="0">
			
          <td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Proveedores y Beneficiarios</td>
			<td width="349" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequeñas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
	  	  <tr>
	  	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	    <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>

      </table></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
</table>
<?php
	
	// validación de los release necesarios para que funcione el sistema de Registro de Proveedores y Contratistas.
	require_once("../shared/class_folder/sigesp_release.php");
    $io_release= new sigesp_release();
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('rpc_beneficiario','tipconben');	
		if ($lb_valido==false)
		   {
			 $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD");
			 print "<script language=JavaScript>";
			 print "location.href='../index_modules.php'";
			 print "</script>";		
		   }
     	$lb_valido=$io_release->io_function_db->uf_select_column('rpc_proveedor','tipconpro');	
		if ($lb_valido==false)
		   {
			 $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD");
			 print "<script language=JavaScript>";
			 print "location.href='../index_modules.php'";
			 print "</script>";		
		   }
	    $lb_valido=$io_release->io_function_db->uf_select_column('sss_registro_eventos','codintper');	
		if ($lb_valido==false)
		   {
			 $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD");
			 print "<script language=JavaScript>";
			 print "location.href='../index_modules.php'";
			 print "</script>";		
		   }
	    $lb_valido=$io_release->io_function_db->uf_select_table('rpc_espexprov');	
		if ($lb_valido==false)
		   {
			 $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2.41 ");
			 print "<script language=JavaScript>";
			 print "location.href='../index_modules.php'";
			 print "</script>";		
		   }
	    $lb_valido=$io_release->io_function_db->uf_select_table('rpc_niveles');	
		if ($lb_valido==false)
		   {
			 $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_12 ");
			 print "<script language=JavaScript>";
			 print "location.href='../index_modules.php'";
			 print "</script>";		
		   }
	    $lb_valido=$io_release->io_function_db->uf_select_column('rpc_clasifxprov','codniv');	
		if ($lb_valido==false)
		   {
			 $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_13");
			 print "<script language=JavaScript>";
			 print "location.href='../index_modules.php'";
			 print "</script>";		
		   }
	    $lb_valido=$io_release->io_function_db->uf_select_column('rpc_documentos','tipdoc');	
		if ($lb_valido==false)
		   {
			 $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_14");
			 print "<script language=JavaScript>";
			 print "location.href='../index_modules.php'";
			 print "</script>";		
		   }
	    $lb_valido=$io_release->io_function_db->uf_select_column('rpc_proveedor','sc_cuentarecdoc');	
		if ($lb_valido==false)
		   {
			 $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_17");
			 print "<script language=JavaScript>";
			 print "location.href='../index_modules.php'";
			 print "</script>";		
		   }
	    $lb_valido=$io_release->io_function_db->uf_select_column('rpc_beneficiario','sc_cuentarecdoc');	
		if ($lb_valido==false)
		   {
			 $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_18");
			 print "<script language=JavaScript>";
			 print "location.href='../index_modules.php'";
			 print "</script>";		
		   }
	    $lb_valido=$io_release->io_function_db->uf_select_column('rpc_clasifxprov','monfincon');	
		if ($lb_valido==false)
		   {
			 $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release");
			 print "<script language=JavaScript>";
			 print "location.href='../index_modules.php'";
			 print "</script>";		
		   }
	    $lb_valido=$io_release->io_function_db->uf_select_column('rpc_proveedor','sc_ctaant');	
		if ($lb_valido==false)
		   {
			 $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_00");
			 print "<script language=JavaScript>";
			 print "location.href='../index_modules.php'";
			 print "</script>";		
		   }
	    $lb_valido=$io_release->io_function_db->uf_select_column('rpc_proveedor','tipperpro');	
		if ($lb_valido==false)
		   {
			 $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_3_47");
			 print "<script language=JavaScript>";
			 print "location.href='../index_modules.php'";
			 print "</script>";		
		   }
	    $lb_valido=$io_release->io_function_db->uf_select_table('rpc_deduxprov');	
		if ($lb_valido==false)
		   {
			 $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_3_48 ");
			 print "<script language=JavaScript>";
			 print "location.href='../index_modules.php'";
			 print "</script>";		
		   }
	    $lb_valido=$io_release->io_function_db->uf_select_table('rpc_deduxbene');	
		if ($lb_valido==false)
		   {
			 $io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_3_49 ");
			 print "<script language=JavaScript>";
			 print "location.href='../index_modules.php'";
			 print "</script>";		
		   }
	}
?>

</body>
<script language="javascript">
function ue_cerrar()
{
	window.open("sigespwindow_blank.php","Blank","_self");
}
</script> 
</html>