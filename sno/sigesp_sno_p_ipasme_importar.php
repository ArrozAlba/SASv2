<?php
   session_start();
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_codnom=$_SESSION["la_nomina"]["codnom"];
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad_nomina("SNO","sigesp_sno_p_ipasme_importar.php",$ls_codnom,$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/07/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_desnom,$ls_peractnom,$ld_fecdesper,$ld_fechasper,$ls_desper,$ls_operacion,$io_fun_nomina,$li_calculada;

		require_once("sigesp_sno.php");
		$io_sno=new sigesp_sno();
		$lb_valido=$io_sno->uf_crear_sessionnomina();		
		$ls_desnom="";
		$ls_peractnom="";
		$ls_desper="";			
		$ld_fecdesper="";
		$ld_fechasper="";
		if($lb_valido==false)
		{
			print "<script language=JavaScript>";
			print "location.href='sigespwindow_blank.php'";
			print "</script>";		
		}
		else
		{
			$ls_desnom=$_SESSION["la_nomina"]["desnom"];
			$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
			$ls_desper=$_SESSION["la_nomina"]["descripcionperiodo"];
			$ld_fecdesper=$io_sno->io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fecdesper"]);
			$ld_fechasper=$io_sno->io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
		}
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		unset($io_sno);
		require_once("sigesp_sno_c_calcularnomina.php");
		$io_calcularnomina=new sigesp_sno_c_calcularnomina();
		$li_calculada=str_pad($io_calcularnomina->uf_existesalida(),1,"0");
		unset($io_calcularnomina);
   }
   //--------------------------------------------------------------

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
<title >Importar Cobranza Ipasme</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_nomina.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php 
	require_once("sigesp_sno_c_impexpdato.php");
	$io_impexpdato=new sigesp_sno_c_impexpdato();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "PROCESAR":
			$ls_arctxt=$HTTP_POST_FILES["txtarctxt"]["name"]; 
			$ls_tiparctxt=$HTTP_POST_FILES["txtarctxt"]["type"]; 
			if($ls_tiparctxt=="text/plain")
			{
				$lb_valido=$io_impexpdato->uf_importardatos_ipasme($ls_arctxt,$la_seguridad);
			}
			else
			{
				$io_impexpdato->io_mensajes->message("Tipo de archivo inválido. Solo se permiten archivos TXT.");
			}
			break;
	}
	$io_impexpdato->uf_destructor();
	unset($io_impexpdato);
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema"><?php print $ls_desnom;?></td>
			<td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><?php print $ls_desper;?></span></div></td>
			 <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td></tr>
	  </table>
	</td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_nomina.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_procesar();"><img src="../shared/imagebank/tools20/ejecutar.gif" title='Ejecutar' alt="Ejecutar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title='Salir' alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" title='Ayuda' alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>

<p>&nbsp;</p>
<form name="form1" method="post" enctype="multipart/form-data" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank_nomina.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="750" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="700" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="6" class="titulo-ventana">Importar Cobranza Ipasme </td>
        </tr>
        <tr>
          <td width="139" height="22">&nbsp;</td>
          <td colspan="5">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">Per&iacute;odo</div></td>
          <td width="30"><input name="txtperactnom" type="text" class="sin-borde3" id="txtperactnom" value="<?php print $ls_peractnom;?>" size="6" maxlength="3" readonly>          </td>
          <td width="66"><div align="right">Fecha Inicio</div></td>
          <td width="78"><div align="left">
              <input name="txtfecdesper" type="text" class="sin-borde3" id="txtfecdesper" value="<?php print  $ld_fecdesper;?>" size="13" maxlength="10" readonly>
          </div></td>
          <td width="61"><div align="right">Fecha Fin </div></td>
          <td width="312"><div align="left">
              <input name="txtfechasper" type="text" class="sin-borde3" id="txtfechasper" value="<?php print  $ld_fechasper;?>" size="13" maxlength="10" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22" colspan="6" class="titulo-celdanew">Archivo a Importar</td>
        </tr>
        <tr>
          <td height="22"><div align="right">Archivo TXT </div></td>
          <td colspan="5"><div align="left">
            <input name="txtarctxt" type="file" id="txtarctxt" size="50" maxlength="200">
          </div></td>
        </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td colspan="5"><input name="operacion" type="hidden" id="operacion"><input name="accion" type="hidden" id="accion">
		  <input name="calculada" type="hidden" id="calculada" value="<?php print $li_calculada;?>">            </td>
        </tr>
      </table>    
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>      
<p>&nbsp;</p>
</body>
<script language="javascript">
function ue_procesar()
{
	f=document.form1;
	li_calculada=f.calculada.value;
	if(li_calculada=="0")
	{		
		li_ejecutar=f.ejecutar.value;
		if (li_ejecutar==1)
		{
			arctxt=ue_validarvacio(f.txtarctxt.value);
			if(arctxt!="")
			{
				f.operacion.value="PROCESAR";
				f.action="sigesp_sno_p_ipasme_importar.php";
				f.submit();			
			}
			else
			{
				alert("Debe seleccionar el archivo a Importar.");
			}
		}
		else
		{
			alert("No tiene permiso para realizar esta operacion");
		}		
	}
	else
	{
		alert("La nómina ya se calculó reverse y vuelva a intentar");
	}
}

function ue_cerrar()
{
	location.href = "sigespwindow_blank_nomina.php";
}
</script> 
</html>