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
$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_p_pasantias.php",$ls_permisos,$la_seguridad,$la_permisos);
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
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_pasantias.js"></script>



</head>

<body onLoad="javascript: ue_nuevo_codigo();">
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
	  	</table>
	 </td>
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
          <td height="22" colspan="9">Registro de Pasant&iacute;a</td>
        </tr>
        <tr>
          <td width="85" height="22">&nbsp;</td>
          
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
        <tr>
          <td height="22" align="left"><div align="right">Nro. Registro</div></td>
          <td height="22" colspan="4"><input name="txtnropas" type="text" id="txtnropas" maxlength="15" style="text-align:center" readonly  size="16" >
              <input name="hidstatus" type="hidden" id="hidstatus"></td>
	      </tr>
		   <tr>
		 <td height="22"><div align="right">Fecha  Incorporaci&oacute;n</div></td>
          <td height="22" colspan="2"><input name="txtfecini" type="text" id="txtfecini"  maxlength="15"  size="16" style="text-align:center" datepicker="true" readonly> 
            <input name="reset" type="reset" onClick="return showCalendar('txtfecini', '%d/%m/%Y');" value=" ... " />          </td>
	    </tr>
		<tr>
		 <td height="22"><div align="right">Fecha  Finalizaci&oacute;n</div></td>
          <td height="22" colspan="2"><input name="txtfecfin" type="text" id="txtfecfin"  maxlength="15"  size="16" style="text-align:center" datepicker="true" readonly>
          <input name="reset" type="reset" onClick="return showCalendar('txtfecfin', '%d/%m/%Y');" value=" ... " />          </td>
	    </tr>
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
		   <tr class="titulo-nuevo">
		          <td height="22" colspan="9">Datos del Pasante</td>
        </tr>
		<tr>
          <td width="85" height="22">&nbsp;</td>
          
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
		  <tr> 
          <td height="22" align="left"><div align="right">Nombres</div></td>
          <td width="217" height="22"><input name="txtnompas" type="text" id="txtnompas"  onKeyUp='ue_validarcomillas(this);' maxlength="40" style="text-align:justify" size="35" ></td>
          <td width="104" height="22" align="left"><div align="right">Apellidos</div></td>
          <td width="274" height="22" colspan="2"><input name="txtapepas" type="text" id="txtapepas" onKeyUp='ue_validarcomillas(this);'  maxlength="30" style="text-align:justify"size="35" ></td>
        </tr>
		  <tr>
          <td height="22" align="left"><div align="right">Cedula</div></td>
          <td height="22"><input name="txtcedpas" type="text" id="txtcedpas" maxlength="10" style="text-align:justify"    onKeyUp="javascript: ue_validarnumero(this);" ></td>
          <td height="22"><div align="right">Fecha Nac.</div></td>
          <td height="22" colspan="2"><input name="txtfecnac" type="text" id="txtfecnac" maxlength="15" style="text-align:justify" datepicker="true" readonly> 
            <input name="reset" type="reset" onClick="return showCalendar('txtfecnac', '%d/%m/%Y');" value=" ... " />          </td>
        </tr>
		
        <tr>
          <td height="22" align="left"><div align="right">G&eacute;nero</div></td>
          <td height="22" bordercolor="0"><select name="combosexo" id="combosexo">
              <option value="null">--Seleccione--</option>
              <option value="F">Femenino</option>
              <option value="M">Masculino</option>
            </select>          </td>
          <td height="22"><div align="right">Estado Civil</div></td>
          <td height="22" colspan="2"><select name="comboedociv" id="comboedociv">
              <option value= "null">--Seleccione--</option>
              <option value="S"> Soltero </option>
              <option value="C"> Casado </option>
              <option value="V"> Viudo </option>
              <option value="D">Divorciado </option>
              <option value="O">Concubino </option>
          </select></td>
        </tr>
		 <tr>
          <td height="22" align="left"><div align="right">Telf. hab.</div></td>
          <td height="22"><input name="txttelhab" type="text" id="txttelhab"  maxlength="15" style="text-align:justify" onKeyUp="javascript: ue_validarnumero(this);"></td>
          <td height="22"><div align="right">Telf. M&oacute;vil</div></td>
          <td height="22" colspan="2"><input name="txttelmov" type="text" id="txttelmov" style="text-align:justify" onKeyUp="javascript: ue_validarnumero(this);" v></td>
        </tr>
        <tr>
          <td height="22" align="left"><div align="right">E-mail</div></td>
          <td height="22"><input name="txtemail" type="text" id="txtemail"  maxlength="100" style="text-align:justify"></td>
          <td height="22"><div align="right">Reside en </div></td>
          <td height="22" colspan="2"><select name="comboest"   id="comboest" onChange="ue_cambioestado();">
              <option value="null">--Seleccione Estado--</option>
            </select>          </td>
        <tr>
          <td height="22" align="left"></td>
          <td height="22"></td>
          <td height="22"></td>
          <td height="22" colspan="2"><select name="combomun" id="combomun"  onclick="valida_combomun();"  onChange="ue_cambiomunicipio();"     >
              <option value="null">--Seleccione Municipio--</option>
            </select>         
			<input name="hidcodmun"  type="hidden" id="hidcodmun"  value="">		  </td>
        </tr>
        <tr>
          <td height="22" align="left"></td>
          <td height="22"></td>
          <td height="22"></td>
          <td height="22" colspan="2"><select name="combopar" id="combopar"  onclick="valida_combopar();">
              <option value="null">--Seleccione Parroquia--</option>
            </select>         
			<input name="hidcodpar"  type="hidden" id="hidcodpar"  value="">		  </td>
        </tr>
        <tr>
          <td height="22" align="left"><div align="right">Direcci&oacute;n </div></td>
          <td height="22" colspan="4"><textarea name="txtdirpas" cols="86" rows="2" id="txtdirpas" onKeyUp='ue_validarcomillas(this);' style="text-align:justify"></textarea></td>
        </tr>
		<tr>
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
		   <tr class="titulo-nuevo">
		          <td height="22" colspan="9">Datos Acad&eacute;micos</td>
        </tr>
		<tr>
          <td width="85" height="22">&nbsp;</td>
          
          <td height="22" colspan="5">&nbsp;</td>
        </tr>
		<tr>
		  <td height="22" align="left"><div align="right">Insitución Universitaria </div></td>
          <td height="22" colspan="4"><input name="txtuniv" type="text" id="txtuniv" onKeyUp='ue_validarcomillas(this);' style="text-align:justify"  size="50" maxlength="256"></td>
        </tr>
		<tr>
		  <td height="22" align="left"><div align="right">Carrera </div></td>
          <td height="22" colspan="4"><input name="txtcarre" type="text" id="txtcarre" onKeyUp='ue_validarcomillas(this);' style="text-align:justify"  size="35" maxlength="256"></td>
        </tr>
		
		
		
		
			<tr>
		  <td height="22" align="left"><div align="right">Tutor Empresarial </div></td>
          <td height="22" colspan="4"><input name="txtcodper" type="text" id="txtcodper" style="text-align:justify"  size="16" maxlength="256" readonly>  <a href="javascript:catalogo_personal();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Personal" name="buscartip" width="15" height="15" border="0" id="buscartip"></a> <input name="txtnomper" type="text" class="sin-borde" id="txtnomper"  size="40" maxlength="80" readonly> </td>
        </tr>
      </table>	 
      <p>&nbsp;</p>
     
 </td> 
</table>
  <p>
    <input type="hidden" id="hidguardar"/>
	    
    <input type="hidden" id="txtcatcausas" value="">
    <input name="hidcontrol" type="hidden" id="hidcontrol" value="">
	  
	 
	<input name="txtnumerofilas" type="hidden" id="txtnumerofilas" value="<? print $lo_solicitud->li_filas;?>">
	<input name="operacion" type="hidden" id="operacion">


  </p>

<div align="center"></div>
<p align="center" class="oculto1" id="mostrar" style="font:#EBEBEB"  ></p>
</form>


</body>


</html>

