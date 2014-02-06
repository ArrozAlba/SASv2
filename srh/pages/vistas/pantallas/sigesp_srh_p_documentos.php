<?php
	session_start();
  	unset($_SESSION["parametros"]);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_inicio_sesion.php'";
	print "</script>";		
}
$ls_logusr=$_SESSION["la_logusr"];
require_once("../../../class_folder/utilidades/class_funciones_srh.php");
$io_fun_srh=new class_funciones_srh('../../../../');
$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_p_documentos.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>SIGESP - Sistema Integrado de Gesti&oacute;n para Entes del Sector P&uacute;blico</title>



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
.Estilo25 {
	color: #6699CC;
	font-size: 12px;
	font-family: Georgia, "Times New Roman", Times, serif;
}
-->
</style>

<script type="text/javascript" language="JavaScript1.2" src="../../../public/js/librerias_comunes.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_documentos.js"></script>



</head>

<body id="cuerpo" onLoad="javasxcript:ue_nuevo_codigo();">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../../../public/imagenes/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo25">Sistema de Recursos Humanos</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  </tr>
 <tr>
    <td height="20" colspan="7" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="../../js/menu/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" colspan="7" align="center" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td width="25" height="20" align="center" class="toolbar"><a href="javascript: ue_nuevo();"><img src="../../../public/imagenes/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a></td>
    <td width="25" class="toolbar" align="center"><a href="javascript: ue_guardar();"><img src="../../../public/imagenes/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a></td>
    <td width="25" class="toolbar" align="center"><a href="javascript: ue_buscar();"><img src="../../../public/imagenes/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></td>
    <td width="25" class="toolbar" align="center"><a href="javascript: ue_eliminar();"><img src="../../../public/imagenes/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a></td>
    <td width="25" class="toolbar" align="center"><a href="javascript: ue_cerrar();"><img src="../../../public/imagenes/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
    <td width="25" class="toolbar" align="center"><img src="../../../public/imagenes/ayuda.gif" alt="Ayuda" width="20" height="20"></td>
    <td width="630" class="toolbar">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>

<form name="form1" method="post" action=""  >
 <div align="center"></div>
<p align="center" class="oculto1" id="mostrar" style="font:#EBEBEB"  ></p>
      <table width="715" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td width="715" height="136"><p>
      <?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_srh->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_srh);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
    </p>
      <p>&nbsp;</p>
      <table width="688" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
        <tr class="titulo-nuevo">
          <td height="22" colspan="9">Registro de Documentos</td>
        </tr>
        <tr>
          <td width="122" height="22">&nbsp;</td>
          
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
        <tr>
          <td height="22" align="left"><div align="right">Nro. Documento</div></td>
          <td height="22" colspan="4"><input name="txtnrodoc" type="text" id="txtnrodoc" maxlength="15" style="text-align:center" readonly >      <input name="hidstatus" type="hidden" id="hidstatus"></td>
	      </tr>
		   <tr>
		  <td height="22" align="left"><div align="right">Denominaci&oacute;n</div></td>
          <td height="22" colspan="4"><input name="txtdendoc"  type="text" size="70" id="txtdendoc" onKeyUp="ue_validarcomillas(this);"   style="text-align:justify" >
                       </td>
		   <tr>
      <td height="22"><div align="right">Tipo de Documento</div></td>
      <td height="22"><input name="txtcodtipdoc" type="text" id="txtcodtipdoc"  maxlength="15" style="text-align:justify" >
        <a href="javascript: catalogo_tipodocumento();"><img src="../../../../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
        <input name="txtdentipdoc" type="text" class="sin-borde" id="txtdentipdoc" style="text-align:justify"  size="50" maxlength="50" readonly="true"></td> 
		</tr>
		  
		    <tr>
      <td height="20"><div align="right">Acceso </div></td>
      <td height="20">
        <div align="left">
          <select name="cmbaccdoc" id="cmbaccdoc">
              <option value= "null">--Seleccione--</option>
              <option value="Local"> Local </option>
              <option value="Internet"> Internet </option>
              </select>
        </div></td>
    </tr>
		 
		  
		  <tr>
          <td height="22" align="left"><div align="right">Direcci&oacute;n </div></td>
          <td height="22" colspan="4"><input name="txtdirdoc" type="text" id="txtdirdoc" onKeyUp="ue_validarcomillas(this);" style="text-align:justify" value="" size="70"> 
          (Internet)</td>
        </tr>
		   
		
		
		 <tr>
          <td height="22" align="left"><div align="right">Archivo PDF </div></td>
          <td height="22" colspan="4"><input name="txarchdoc" type="file" id="txtarchdoc" style="text-align:justify"  onBlur="javascript: cambiar_nombre ();"  size="70">(Local)</td>
		 </tr>
		 <tr>
		  <td height="22" align="left"><div align="right">Nombre del Archivo</div></td>
          <td height="22" colspan="4"><input name="txtnomarch"  type="text" size="70" id="txtnomarch" readonly  style="text-align:justify" >
                       </td>
		   <tr>
		
		  
		 
		<tr>
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
	    </table>
	  </td>
	  <td height="22" colspan="5">&nbsp;</td>
		</tr>
		<tr>
		<td> <p>&nbsp;</p> <p>&nbsp;</p> </td>
		</tr>
		
		
      </table>	 
     
     
 </td> 
</table>
 <input type="hidden" id="higuardar2">
  <input type="hidden" id="hidguardar">
  <p>
     
   	    
  
    <input name="operacion" type="hidden" id="operacion">
	
	<input name="txtnumerofilas" type="hidden" id="txtnumerofilas" value="<? print $lo_solicitud->li_filas;?>">
	<input name="operacion" type="hidden" id="operacion">


  </p>

<div align="center"></div>
<p align="center" class="oculto1" id="mostrar" style="font:#EBEBEB"  ></p>
</form>


</body>


</html>


