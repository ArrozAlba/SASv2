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
$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_p_enfermedades.php",$ls_permisos,$la_seguridad,$la_permisos);
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
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_enfermedades.js"></script>



</head>

<body onLoad="javascript:ue_nuevo_codigo();">
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
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
	  	</table>	 </td>
  </tr>
 <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="../../js/menu/menu.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>

  <tr>
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../../../public/imagenes/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar();"><img src="../../../public/imagenes/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_buscar();"><img src="../../../public/imagenes/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_eliminar();"><img src="../../../public/imagenes/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_cerrar();"><img src="../../../public/imagenes/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><img src="../../../public/imagenes/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="24"><div align="center"></div></td>
    <td class="toolbar" width="618">&nbsp;</td>
  </tr>
</table>
<?php

	

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
	}
	else
	{
		$ls_operacion="";
		}

	
	
	//
?>

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
          <td height="22" colspan="10">Registro de Enfermedades</td>
        </tr>
        <tr>
          <td width="91" height="22">&nbsp;</td>
          
          <td height="22" colspan="6">&nbsp;</td>
        </tr>
        <tr>
          <td height="22" align="left"><div align="right">Nro. Registro</div></td>
          <td height="22" colspan="4"><input name="txtnroreg" type="text" id="txtnroreg" maxlength="15" size="16" readonly style="text-align:center" >      <input name="hidstatus" type="hidden" id="hidstatus"></td>
	      </tr>
		   <tr>
		 <td height="22"><div align="right">Fecha  Elaboraci&oacute;n</div></td>
          <td height="22" colspan="3"><input name="txtfecelab" type="text" id="txtfecelab" size="16"   maxlength="15" style="text-align:center" datepicker="true" readonly> 
            <input name="reset" type="reset" onClick="return showCalendar('txtfecelab', '%d/%m/%Y');" value=" ... " />          </td>
	    </tr>
		  
		  <tr>
		  <td height="22" align="left"><div align="right">C&oacute;digo Personal</div></td>
          <td height="22" colspan="4"><input name="txtcodper" maxlength="10" type="text" size="16" id="txtcodper"   style="text-align:center" readonly>
            <a href="javascript:catalogo_personal();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Personal" name="buscartip" width="15" height="15" border="0" id="buscartip"></a>           </td>
		  <tr>
		  <td height="22" align="left"><div align="right">Nombre </div></td>
          <td height="22" colspan="4"><input name="txtnomper" type="text" id="txtnomper"  size="40" style="text-align:justify" readonly>           </td>
	      </tr>
		  
		    <tr>
      <td height="22"><div align="right">Tipo de Enfermedad </div></td>
      <td height="22" colspan="4"><input name="txtcodenf" type="text" id="txtcodenf"  maxlength="15" size="16"  style="text-align:justify" >
        <a href="javascript: catalogo_tipoenfermedad();"><img src="../../../../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
        <input name="txtdenenf" type="text" class="sin-borde" id="txtdenenf" style="text-align:justify"  size="50" maxlength="50" readonly="true"></td> 
		</tr>
		  <tr>
		 <td height="22"><div align="right">Fecha Inicio</div></td>
          <td height="22" colspan="3"><input name="txtfecini" type="text" id="txtfecini"  maxlength="15" size="16"  style="text-align:center" datepicker="true" readonly> 
            <input name="reset" type="reset" onClick="return showCalendar('txtfecini', '%d/%m/%Y');" value=" ... " />          </td>
	    </tr>
		
		 <tr>
          <td height="22" align="left"><div align="right">Observaci&oacute;n </div></td>
          <td height="22" colspan="4"><textarea name="txtobs" cols="86" rows="5" id="txtobs" onKeyUp="ue_validarcomillas(this);" style="text-align:justify"></textarea></td>
        </tr>
		 <tr>
		
		  <td height="22" align="left"><div align="right">D&iacute;as de Reposo</div></td>
	  <td height="22" colspan="4"><input name="txtrep" type="text" id="txtrep" maxlength="3" size="7" style="text-align:center"  onKeyUp="javascript: ue_validarnumero(this);"   >
            </td>
	      </tr>
		<tr>
          <td height="22" colspan="4">&nbsp;</td>
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
     
   	    
     <select name="comboriecon" id="comboriecon" style="visibility:hidden"> </select>
	 <select name="comborielet" id="comborielet" style="visibility:hidden"> </select>
      <input name="txtobsenf" type="hidden" id="txtobsenf">
    <input name="operacion" type="hidden" id="operacion">
	
	<input name="txtnumerofilas" type="hidden" id="txtnumerofilas" value="<? print $lo_solicitud->li_filas;?>">
	<input name="operacion" type="hidden" id="operacion">


  </p>

<div align="center"></div>
<p align="center" class="oculto1" id="mostrar" style="font:#EBEBEB"  ></p>
</form>


</body>


</html>


