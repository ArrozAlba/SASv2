<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_inicio_sesion.php'";
	print "</script>";		
}

require_once("../../../../shared/class_folder/class_funciones.php");
$io_funcion=new class_funciones();
$io_funcion->uf_limpiar_sesion();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
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
<title>Sistema de Recursos Humanos</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="imagetoolbar" content="no"> 
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #f3f3f3;
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
.Estilo26 {
	color: #6699CC;
	font-size: 12px;
	font-family: Georgia, "Times New Roman", Times, serif;
}
-->
</style>
<script type="text/javascript" language="JavaScript1.2" src="../../../public/js/librerias_comunes.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>


</head>

<body>

<?php
		
	if (array_key_exists("opener",$_GET))
	{
	  $ls_opener=$_GET["opener"];
	  if ($ls_opener == "tabulador")
	  {
	      print("<script language='javascript'>");
		  print("location.href = 'sigespwindow_blank.php';");
	      print("pagina    = '../../../../sno/sigesp_snorh_d_tabulador.php?valor=srh';");
		  print("ancho     = 900;");
		  print("alto      = 850;");
		  print("arriba    = (screen.height/2)-(alto/2);");
		  print("izquierda = (screen.width/2)-(ancho/2);");
	      print("window.open(pagina,'catalogo2','status=yes,menubar=no,toolbar=no,scrollbars=yes,width='+ancho+',height='+alto+',resizable=yes,location=no,top='+arriba+',left='+izquierda);");
		  print("</script>");
	  }
	  
	  if ($ls_opener == "asignacion_cargo")
	  {
	      print("<script language='javascript'>");
		  print("location.href = 'sigespwindow_blank.php?valor=srh';");
	      print("pagina    = '../../../../sno/sigesp_snorh_d_asignacioncargo.php?valor=srh';");
		  print("ancho     = 900;");
		  print("alto      = 850;");
		  print("arriba    = (screen.height/2)-(alto/2);");
		  print("izquierda = (screen.width/2)-(ancho/2);");
	      print("window.open(pagina,'catalogo3','status=yes,menubar=no,toolbar=no,scrollbars=yes,width='+ancho+',height='+alto+',resizable=yes,location=no,top='+arriba+',left='+izquierda);");
		  print("</script>");
	  }
	  
	  if ($ls_opener == "constancia_trab")
	  {
	      print("<script language='javascript'>");
		  print("location.href = 'sigespwindow_blank.php?valor=srh';");
	      print("pagina    = '../../../../sno/sigesp_snorh_d_constanciatrabajo.php?valor=srh';");
		  print("ancho     = 900;");
		  print("alto      = 850;");
		  print("arriba    = (screen.height/2)-(alto/2);");
		  print("izquierda = (screen.width/2)-(ancho/2);");
	      print("window.open(pagina,'catalogo3','status=yes,menubar=no,toolbar=no,scrollbars=yes,width='+ancho+',height='+alto+',resizable=yes,location=no,top='+arriba+',left='+izquierda);");
		  print("</script>");
	  }
	  
	  if ($ls_opener == "rep_constancia_trab")
	  {
	      print("<script language='javascript'>");
		  print("location.href = 'sigespwindow_blank.php?valor=srh';");
	      print("pagina    = '../../../../sno/sigesp_snorh_r_constanciatrabajo.php?valor=srh';");
		  print("ancho     = 900;");
		  print("alto      = 850;");
		  print("arriba    = (screen.height/2)-(alto/2);");
		  print("izquierda = (screen.width/2)-(ancho/2);");
	      print("window.open(pagina,'catalogo3','status=yes,menubar=no,toolbar=no,scrollbars=yes,width='+ancho+',height='+alto+',resizable=yes,location=no,top='+arriba+',left='+izquierda);");
		  print("</script>");
	  }
	  
	  if ($ls_opener == "rep_constancia_trab_sso")
	  {
	      print("<script language='javascript'>");
		  print("location.href = 'sigespwindow_blank.php?valor=srh';");
	      print("pagina    = '../../../../sno/sigesp_snorh_r_constanciatrabajosegurosocial.php?valor=srh';");
		  print("ancho     = 900;");
		  print("alto      = 850;");
		  print("arriba    = (screen.height/2)-(alto/2);");
		  print("izquierda = (screen.width/2)-(ancho/2);");
	      print("window.open(pagina,'catalogo3','status=yes,menubar=no,toolbar=no,scrollbars=yes,width='+ancho+',height='+alto+',resizable=yes,location=no,top='+arriba+',left='+izquierda);");
		  print("</script>");
	  }
	  
	  if ($ls_opener == "listado_personal_1")
	  {
	      print("<script language='javascript'>");
		  print("location.href = 'sigespwindow_blank.php?valor=srh';");
	      print("pagina    = '../../../../sno/sigesp_snorh_r_listadopersonal.php?valor=srh';");
		  print("ancho     = 900;");
		  print("alto      = 850;");
		  print("arriba    = (screen.height/2)-(alto/2);");
		  print("izquierda = (screen.width/2)-(ancho/2);");
	      print("window.open(pagina,'catalogo3','status=yes,menubar=no,toolbar=no,scrollbars=yes,width='+ancho+',height='+alto+',resizable=yes,location=no,top='+arriba+',left='+izquierda);");
		  print("</script>");
	  }
	  
	  if ($ls_opener == "listado_personal_2")
	  {
	      print("<script language='javascript'>");
		  print("location.href = 'sigespwindow_blank.php?valor=srh';");
	      print("pagina    = '../../../../sno/sigesp_snorh_r_listadopersonalcontratado.php?valor=srh';");
		  print("ancho     = 900;");
		  print("alto      = 850;");
		  print("arriba    = (screen.height/2)-(alto/2);");
		  print("izquierda = (screen.width/2)-(ancho/2);");
	      print("window.open(pagina,'catalogo3','status=yes,menubar=no,toolbar=no,scrollbars=yes,width='+ancho+',height='+alto+',resizable=yes,location=no,top='+arriba+',left='+izquierda);");
		  print("</script>");
	  }
	  
	  if ($ls_opener == "listado_personal_3")
	  {
	      print("<script language='javascript'>");
		  print("location.href = 'sigespwindow_blank.php?valor=srh';");
	      print("pagina    = '../../../../sno/sigesp_snorh_r_unidadadministrativa.php?valor=srh';");
		  print("ancho     = 900;");
		  print("alto      = 850;");
		  print("arriba    = (screen.height/2)-(alto/2);");
		  print("izquierda = (screen.width/2)-(ancho/2);");
	      print("window.open(pagina,'catalogo3','status=yes,menubar=no,toolbar=no,scrollbars=yes,width='+ancho+',height='+alto+',resizable=yes,location=no,top='+arriba+',left='+izquierda);");
		  print("</script>");
	  }
	  
	  if ($ls_opener == "listado_personal_4")
	  {
	      print("<script language='javascript'>");
		  print("location.href = 'sigespwindow_blank.php?valor=srh';");
	      print("pagina    = '../../../../sno/sigesp_snorh_r_listadopersonalgenerico.php?valor=srh';");
		  print("ancho     = 900;");
		  print("alto      = 850;");
		  print("arriba    = (screen.height/2)-(alto/2);");
		  print("izquierda = (screen.width/2)-(ancho/2);");
	      print("window.open(pagina,'catalogo3','status=yes,menubar=no,toolbar=no,scrollbars=yes,width='+ancho+',height='+alto+',resizable=yes,location=no,top='+arriba+',left='+izquierda);");
		  print("</script>");
	  }
	  
	  if ($ls_opener == "listado_personal_5")
	  {
	      print("<script language='javascript'>");
		  print("location.href = 'sigespwindow_blank.php?valor=srh';");
	      print("pagina    = '../../../../sno/sigesp_snorh_r_listadocomponente.php?valor=srh';");
		  print("ancho     = 900;");
		  print("alto      = 850;");
		  print("arriba    = (screen.height/2)-(alto/2);");
		  print("izquierda = (screen.width/2)-(ancho/2);");
	      print("window.open(pagina,'catalogo3','status=yes,menubar=no,toolbar=no,scrollbars=yes,width='+ancho+',height='+alto+',resizable=yes,location=no,top='+arriba+',left='+izquierda);");
		  print("</script>");
	  }
	  
	  if ($ls_opener == "listado_personal_6")
	  {
	      print("<script language='javascript'>");
		  print("location.href = 'sigespwindow_blank.php?valor=srh';");
	      print("pagina    = '../../../../sno/sigesp_snorh_r_fichapersonal.php?valor=srh';");
		  print("ancho     = 900;");
		  print("alto      = 850;");
		  print("arriba    = (screen.height/2)-(alto/2);");
		  print("izquierda = (screen.width/2)-(ancho/2);");
	      print("window.open(pagina,'catalogo3','status=yes,menubar=no,toolbar=no,scrollbars=yes,width='+ancho+',height='+alto+',resizable=yes,location=no,top='+arriba+',left='+izquierda);");
		  print("</script>");
	  }
	  
	   if ($ls_opener == "listado_personal_7")
	  {
	      print("<script language='javascript'>");
		  print("location.href = 'sigespwindow_blank.php?valor=srh';");
	      print("pagina    = '../../../../sno/sigesp_snorh_r_listadocumpleano.php?valor=srh';");
		  print("ancho     = 900;");
		  print("alto      = 850;");
		  print("arriba    = (screen.height/2)-(alto/2);");
		  print("izquierda = (screen.width/2)-(ancho/2);");
	      print("window.open(pagina,'catalogo3','status=yes,menubar=no,toolbar=no,scrollbars=yes,width='+ancho+',height='+alto+',resizable=yes,location=no,top='+arriba+',left='+izquierda);");
		  print("</script>");
	  }
	  
	   if ($ls_opener == "listado_personal_8")
	  {
	      print("<script language='javascript'>");
		  print("location.href = 'sigespwindow_blank.php?valor=srh';");
	      print("pagina    = '../../../../sno/sigesp_snorh_r_familiar.php?valor=srh';");
		  print("ancho     = 900;");
		  print("alto      = 850;");
		  print("arriba    = (screen.height/2)-(alto/2);");
		  print("izquierda = (screen.width/2)-(ancho/2);");
	      print("window.open(pagina,'catalogo3','status=yes,menubar=no,toolbar=no,scrollbars=yes,width='+ancho+',height='+alto+',resizable=yes,location=no,top='+arriba+',left='+izquierda);");
		  print("</script>");
	  }
	  
	   if ($ls_opener == "listado_personal_9")
	  {
	      print("<script language='javascript'>");
		  print("location.href = 'sigespwindow_blank.php?valor=srh';");
	      print("pagina    = '../../../../sno/sigesp_snorh_r_vacaciones.php?valor=srh';");
		  print("ancho     = 900;");
		  print("alto      = 850;");
		  print("arriba    = (screen.height/2)-(alto/2);");
		  print("izquierda = (screen.width/2)-(ancho/2);");
	      print("window.open(pagina,'catalogo3','status=yes,menubar=no,toolbar=no,scrollbars=yes,width='+ancho+',height='+alto+',resizable=yes,location=no,top='+arriba+',left='+izquierda);");
		  print("</script>");
	  }
	  
	   if ($ls_opener == "listado_personal_10")
	  {
	      print("<script language='javascript'>");
		  print("location.href = 'sigespwindow_blank.php?valor=srh';");
	      print("pagina    = '../../../../sno/sigesp_snorh_r_credencialespersonal.php?valor=srh';");
		  print("ancho     = 900;");
		  print("alto      = 850;");
		  print("arriba    = (screen.height/2)-(alto/2);");
		  print("izquierda = (screen.width/2)-(ancho/2);");
	      print("window.open(pagina,'catalogo3','status=yes,menubar=no,toolbar=no,scrollbars=yes,width='+ancho+',height='+alto+',resizable=yes,location=no,top='+arriba+',left='+izquierda);");
		  print("</script>");
	  }
	  
	  if ($ls_opener == "listado_personal_11")
	  {
	      print("<script language='javascript'>");
		  print("location.href = 'sigespwindow_blank.php?valor=srh';");
	      print("pagina    = '../../../../sno/sigesp_snorh_r_antiguedadpersonal.php?valor=srh';");
		  print("ancho     = 900;");
		  print("alto      = 850;");
		  print("arriba    = (screen.height/2)-(alto/2);");
		  print("izquierda = (screen.width/2)-(ancho/2);");
	      print("window.open(pagina,'catalogo3','status=yes,menubar=no,toolbar=no,scrollbars=yes,width='+ancho+',height='+alto+',resizable=yes,location=no,top='+arriba+',left='+izquierda);");
		  print("</script>");
	  }
	  
	   if ($ls_opener == "listado_personal_12")
	  {
	      print("<script language='javascript'>");
		  print("location.href = 'sigespwindow_blank.php?valor=srh';");
	      print("pagina    = '../../../../sno/sigesp_snorh_r_unidadvipladin.php?valor=srh';");
		  print("ancho     = 900;");
		  print("alto      = 850;");
		  print("arriba    = (screen.height/2)-(alto/2);");
		  print("izquierda = (screen.width/2)-(ancho/2);");
	      print("window.open(pagina,'catalogo3','status=yes,menubar=no,toolbar=no,scrollbars=yes,width='+ancho+',height='+alto+',resizable=yes,location=no,top='+arriba+',left='+izquierda);");
		  print("</script>");
	  }
	  
	  if ($ls_opener == "cambio_estatus")
	  {
	      print("<script language='javascript'>");
		  print("location.href = 'sigespwindow_blank.php?valor=srh';");
	      print("pagina    = '../../../../sno/sigesp_snorh_p_personalcambioestatus.php?valor=srh';");
		  print("ancho     = 900;");
		  print("alto      = 850;");
		  print("arriba    = (screen.height/2)-(alto/2);");
		  print("izquierda = (screen.width/2)-(ancho/2);");
	      print("window.open(pagina,'catalogo3','status=yes,menubar=no,toolbar=no,scrollbars=yes,width='+ancho+',height='+alto+',resizable=yes,location=no,top='+arriba+',left='+izquierda);");
		  print("</script>");
	  }
	  if ($ls_opener == "causas_ret")
	  {
	      print("<script language='javascript'>");
		  print("location.href = 'sigespwindow_blank.php?valor=srh';");
	      print("pagina    = '../../../../sno/sigesp_snorh_d_causa.php?valor=srh';");
		  print("ancho     = 900;");
		  print("alto      = 850;");
		  print("arriba    = (screen.height/2)-(alto/2);");
		  print("izquierda = (screen.width/2)-(ancho/2);");
	      print("window.open(pagina,'catalogo3','status=yes,menubar=no,toolbar=no,scrollbars=yes,width='+ancho+',height='+alto+',resizable=yes,location=no,top='+arriba+',left='+izquierda);");
		  print("</script>");
	  }
	 
	  
	}
?>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../../../public/imagenes/header.jpg" width="778" height="40"></td>
  </tr>
  
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema  Estilo26">Sistema de Recursos Humanos</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  </tr>
  
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="../../js/menu/menu.js"></script></td>
  </tr>
  
</table>
<form name="form1" method="post" action="">
</form>


<?php
/// validación de los release necesarios 
	require_once("../../../../shared/class_folder/sigesp_release.php");
    $io_release= new sigesp_release();
	$lb_valido1=true;
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('srh_unidadvipladin');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_23");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('srh_movimiento_personal','nummov');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_64");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('srh_solicitud_adiestramiento','codunivipladin');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_65");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('srh_competencias_adiestramiento');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_74");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('srh_causas_adiestramiento');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_75");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('srh_necesidad_adiestramiento');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_76");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('srh_persona_necesidad_adiestramiento');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_77");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('srh_dt_causas_adiestramiento');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_78");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('srh_dt_competencias_adiestramiento');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_79");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('srh_concurso');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_85");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('srh_movimiento_personal');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_86");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}

	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_constraint('srh_persona_concurso','srh_persona_concurso_codemp_fkey');	
		if($lb_valido==true)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_1_87");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_beneficiario','nexben');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 3.91 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personal','enviorec');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 1.92 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}

	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_beneficiario','cedaut');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 1.93 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('srh_departamento','minorguniadm');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release  2_01 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('srh_movimiento_personal','minorguniadm');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release  2_02 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('srh_movimiento_personal','codunivi');
		if($lb_valido==true)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release  2_03 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_constraint('srh_dt_ganadores_concurso','srh_dt_ganadores_concurso_pkey');
		
		if($lb_valido==true)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release  2_08 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_constraint('srh_dt_cargo','srh_dt_cargo_codemp_fkey');
		
		if($lb_valido==true)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release  2_13 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_constraint('srh_dt_cargo','FK_srh_dt_cargo_2');
		
		if($lb_valido==true)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release  2_14 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('srh_contratos','codcar');
		
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release  2_15 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('srh_puntuacion_bono_merito','nompunt');
		
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release  2_16 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('srh_defcontrato');
		
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release  2_19 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('srh_premiacion');
		
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release  2_20 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('srh_llamada_atencion','causa');
		
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release  2_21 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_permiso','tothorper');
		
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release  2_26 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_constraint('srh_premiacion','srh_premiacion_pkey');
		
		if($lb_valido==true)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release  2_31 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('srh_hmovimiento_personal');
		
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release  2_32 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sno_causales');
		
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release  2_33 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personal','codcausa');
		
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release  2_34 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('srh_contratos','apeper');
		
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release  2_39 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('srh_bono_merito','codpun');
		
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release  2_41 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('srh_puntosunitri');
		
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release  2_42 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('srh_dt_puntosunitri');
		
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release  2_43 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('srh_bono_merito','codtipper');
		
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release  2_47 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido1)
	{
		
		switch($_SESSION["ls_gestor"])
	   {
			case "MYSQLT":						
				$lb_valido=$io_release->io_function_db->uf_select_type_columna('srh_dt_puntosunitri','prompun','double');
			
			 break;
				   
			case "POSTGRES":
				$lb_valido=$io_release->io_function_db->uf_select_type_columna('srh_dt_puntosunitri','prompun','double precision');
								
			break;  				  
		}		
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release  2_67 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}		
	}
	if($lb_valido1)
	{
	    switch($_SESSION["ls_gestor"])
	   {
			case "MYSQLT":					
			    $lb_valido=$io_release->io_function_db->uf_select_type_columna('srh_dt_puntosunitri','unitri','double');
			 break;
				   
			case "POSTGRES":
				$lb_valido=$io_release->io_function_db->uf_select_type_columna('srh_dt_puntosunitri','unitri','double precision');
								
			break;  				  
		}		
		
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release  2_68 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
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
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personal','fecsitu');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_86 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personal','talcamper');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_87 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personal','anoservprecont');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_92 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personal','anoservprefijo');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_96 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('srh_concursante');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_23 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('srh_estudiosconcursante');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_24 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('srh_cursosconcursante');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_25 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('srh_trabajosconcursante');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_26 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('srh_familiaresconcursante');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_27 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('srh_requisitosconcursante');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_28 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('srh_requisitos_concurso');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_29 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('srh_organigrama');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_35 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personal','codorg');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_36 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('srh_dt_odi','cododi');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_89 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('srh_dt_revisiones_odi','cododi');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_90 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('srh_evaluacion_odi','cododi');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_91 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personal','porcajahoper');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_92 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
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
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	
	
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_familiar','estbonjug');
		if ($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_3_23 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";			
		}
	}
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('srh_gerencia');
		if ($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_3_25 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personal','anoperobr');
		if ($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_3_26 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personal','codger');
		if ($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_3_27 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('srh_departamento','codger');
		if ($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_3_28 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";			
		}
	}
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_beneficiario','numexpben');
		if ($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_3_29 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personal','carantper');
		if ($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_3_31 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_personaldeduccion','coddettipded');
		if ($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_3_32 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
	
	
	if($lb_valido1)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sno_familiardeduccion','coddettipded');
		if ($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2009_3_33 ");
			print "<script language=JavaScript>";
			print "location.href='../../../../index_modules.php'";
			print "</script>";		
		}
	}
?>
</body>
<script language="javascript"> 

  function ue_cerrar()
  {
	f=document.form1;
	f.action="sigespwindow_blank.php";
	f.submit();
  }
  
  function ue_close()
  {
    close();
  }

</script>
</html>
