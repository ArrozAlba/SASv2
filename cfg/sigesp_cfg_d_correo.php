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
	require_once("class_folder/class_funciones_cfg.php");
	$io_fun_cfg=new class_funciones_cfg();
	$io_fun_cfg->uf_load_seguridad("CFG","sigesp_cfg_d_correo.php",$ls_permisos,$la_seguridad,$la_permisos,"../");
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
   //--------------------------------------------------------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 19/04/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_msjenvio,$ls_msjsmtp,$ls_msjservidor,$ls_msjpuerto,$ls_msjhtml,$ls_msjremitente;

		$ls_msjenvio=0;
		$ls_msjsmtp=0;
		$ls_msjservidor="";
		$ls_msjpuerto="";
		$ls_msjhtml=0;
		$ls_msjremitente="";
   }
   //--------------------------------------------------------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
<html>
<head>
<title>Registro de Control de Número </title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../css/cfg.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funciones_configuracion.js"></script>
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

<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.Estilo1 {
	font-size: 14;
	color: #6699CC;
}
-->
</style>
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Configuración</td>
		<td width="353" bgcolor="#E7E7E7"><div align="right"><span class="letras-negrita"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  <tr>
		<td height="20" bgcolor="#E7E7E7">&nbsp;</td>
		<td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right" class="letras-negrita"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
	</table></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"></a><a href="javascript:ue_eliminar();"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>

<?php
	require_once("class_folder/sigesp_cfg_c_correo.php");
	require_once("../shared/class_folder/class_mensajes.php");
	$io_cfg= new sigesp_cfg_c_correo("../");
	$io_msg= new class_mensajes(); //Instanciando la clase mensajes
	$ls_operacion=$io_fun_cfg->uf_obteneroperacion();
	$ls_msjenvio=$io_fun_cfg->uf_obtenervalor("rdmsjenvio",0);
	$ls_msjsmtp=$io_fun_cfg->uf_obtenervalor("rdmsjsmtp",0);
	$ls_msjservidor=$io_fun_cfg->uf_obtenervalor("txtmsjservidor","");
	$ls_msjpuerto=$io_fun_cfg->uf_obtenervalor("txtmsjpuerto","");
	$ls_msjhtml=$io_fun_cfg->uf_obtenervalor("rdmsjhtml",0);
	$ls_msjremitente=$io_fun_cfg->uf_obtenervalor("txtmsjremitente","");
	switch($ls_operacion)
	{
		case"NUEVO":
			$lb_valido=$io_cfg->uf_load_configuracion_correo($ls_msjenvio,$ls_msjsmtp,$ls_msjservidor,$ls_msjpuerto,$ls_msjhtml,
															 $ls_msjremitente);	
		break;
		case"GUARDAR":
			$lb_valido=$io_cfg->uf_guardar($ls_msjenvio,$ls_msjsmtp,$ls_msjservidor,$ls_msjpuerto,$ls_msjhtml,$ls_msjremitente,$la_seguridad);
			if($lb_valido)
			{
				$lb_valido=$io_cfg->uf_load_configuracion_correo($ls_msjenvio,$ls_msjsmtp,$ls_msjservidor,$ls_msjpuerto,$ls_msjhtml,
																 $ls_msjremitente);	
			}
															   
		break;
	}
	if($ls_msjenvio==1)
	{
		$ls_chkenvio0="checked";
		$ls_chkenvio1="";
	}
	else
	{
		$ls_chkenvio0="";
		$ls_chkenvio1="checked";
	}
	if($ls_msjsmtp==1)
	{
		$ls_chksmtp0="checked";
		$ls_chksmtp1="";
	}
	else
	{
		$ls_chksmtp0="";
		$ls_chksmtp1="checked";
	}
	if($ls_msjhtml==1)
	{
		$ls_chkhtml0="checked";
		$ls_chkhtml1="";
	}
	else
	{
		$ls_chkhtml0="";
		$ls_chkhtml1="checked";
	}
?>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_cfg->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_cfg);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<table width="611" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="616" height="227"><table width="575"  border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="22" colspan="4" class="titulo-ventana">Servidor de Correo Electr&oacute;nico</td>
        </tr>
        <tr>
          <td width="159" height="22" align="right">Envio de Correo </td>
          <td width="318" height="22" colspan="3"><input name="rdmsjenvio" type="radio" class="sin-borde" value="1" <?php print $ls_chkenvio0; ?>>
            S&iacute; 
            <input name="rdmsjenvio" type="radio" class="sin-borde" value="0" <?php print $ls_chkenvio1; ?>> 
            No </td>
        </tr>
        <tr>
          <td height="22" align="right">Servidor SMTP </td>
          <td height="22" colspan="3"><input name="rdmsjsmtp" type="radio" class="sin-borde" value="1" <?php print $ls_chksmtp0; ?>>
            S&iacute;
              <input name="rdmsjsmtp" type="radio" class="sin-borde" value="0"  <?php print $ls_chksmtp1; ?>>
            No</td>
</tr>
        <tr>
          <td height="22" align="right">Nombre del Servidor </td>
          <td height="22" colspan="3"><input name="txtmsjservidor" type="text" id="txtmsjservidor"   onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+'.,-');" value="<?php print $ls_msjservidor; ?>" size="50" maxlength="60"></td>
        </tr>
        <tr>
          <td height="22" align="right">Puerto</td>
          <td height="22" colspan="3"><input name="txtmsjpuerto" type="text" id="txtmsjpuerto" value="<?php print $ls_msjpuerto; ?>" size="12" maxlength="10"  onKeyPress="return keyRestrict(event,'0123456789');"></td>
        </tr>
        <tr>
          <td height="22" align="right">Mensaje HTML </td>
          <td height="22" colspan="3"><input name="rdmsjhtml" type="radio" class="sin-borde" value="1"  <?php print $ls_chkhtml0; ?>>
            S&iacute; 
              <input name="rdmsjhtml" type="radio" class="sin-borde" value="0" <?php print $ls_chkhtml1; ?>>
            No</td>
        </tr>
        <tr>
          <td height="22" align="right">Direcci&oacute;n de Correo del Remitente</td>
          <td height="22" colspan="3"><input name="txtmsjremitente" type="text" id="txtmsjremitente" size="50" maxlength="60"  value="<?php print $ls_msjremitente; ?>"  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+'.,-@');" ></td>
        </tr>
        <tr>
          <td height="15" align="right">&nbsp;</td>
          <td height="15" colspan="3">&nbsp;</td>
        </tr>
        
      </table>
      <input name="operacion" type="hidden" id="operacion"></td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
</body>
<script language="javascript">
function ue_guardar()
{
	f= document.form1;
	li_incluir= f.incluir.value;
	li_cambiar= f.cambiar.value;
	lb_valido=ue_validarcorreo(txtmsjremitente);
	if ((li_cambiar==1)||(li_incluir==1))
	{
		if(lb_valido)
		{
			f=document.form1;
			f.operacion.value="GUARDAR";
			f.action="sigesp_cfg_d_correo.php";
			f.submit();
		}
	}
	else
	{
		alert("No tiene permiso para realizar esta operación");
	}
}


</script>
</html>