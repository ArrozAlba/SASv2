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
$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_d_defcontrato.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../../../public/js/librerias_comunes.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_defcontrato.js"></script>

<title >Definici&oacute;n de Contrato</title>
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
.Estilo1 {
	color: #6699CC;
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 12px;
}

-->
</style>

</head>
<body onLoad="javascript:ue_nuevo_codigo();">

<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../../../public/imagenes/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Recursos Humanos</td>
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
</table><p>&nbsp;</p>
<form name="form1" method="post" enctype="multipart/form-data" action="">
 <div align="center"></div>
<p align="center" class="oculto1" id="mostrar" style="font:#EBEBEB"  ></p>
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_srh->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_srh);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	
<table width="700" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td>
      <p>&nbsp;</p>
      <table width="650" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-ventana">Configuraci&oacute;n de Contrato</td>
        </tr>
        <tr>
          <td width="130" height="22">&nbsp;</td>
          <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo</div></td>
          <td colspan="3">
            <div align="left">
              <input name="txtcodcont" type="text" id="txtcodcont" size="5" maxlength="3" readonly>
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Descripci&oacute;n</div></td>
          <td colspan="3">
            <div align="left">
              <input name="txtdescont" type="text" id="txtdescont" size="60" maxlength="254" onKeyUp="ue_validarcomillas(this);">
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Tama&ntilde;o de Letra </div></td>
          <td height="22">
            <div align="left">
              <input name="txttamletcont" type="text" id="txttamletcont"  onKeyUp="javascript: ue_validarnumero(this);" onBlur="javascript: validar_cero (this);" size="5" maxlength="2" style="text-align:right" >            
            </div></td>
          <td height="22"><div align="right">Tama&ntilde;o Letra Pie Pagina </div></td>
          <td height="22"><div align="left">
            <input name="txttamletpiecont" type="text" id="txttamletpiecont"  size="5" maxlength="2" onKeyUp="javascript: ue_validarnumero(this);" onBlur="javascript: validar_cero (this);" style="text-align:right" >
         </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Interlineado</div></td>
          <td height="22" colspan="3">
            <div align="left">
              <select name="cmdintlincont" id="cmdintlincont">
                <option value="1" selected>1</option>
                <option value="2">1.5</option>
                <option value="3">2</option>
              </select>
            </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Margen Superior </div></td>
          <td width="119" height="22"><div align="left">
            <label>
            <input name="txtmarsupcont" type="text" id="txtmarsupcont" style="text-align:right"  size="8" maxlength="5" onKeyPress="return(ue_formatonumero(this,'.',',',event))">
            </label> 
            cm
</div></td>
          <td width="162"><div align="right">Margen Inferior </div></td>
          <td width="229"><div align="left">
            <label>
            <input name="txtmarinfcont" type="text" id="txtmarinfcont" style="text-align:right"  size="8" maxlength="5" onKeyPress="return(ue_formatonumero(this,'.',',',event))">
            </label> 
            cm
</div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">T&iacute;tulo</div></td>
          <td height="22" colspan="3"><label>
            <input name="txttitcont" type="text" id="txttitcont"  size="80" maxlength="250" onKeyUp="ue_validarcomillas(this);">
            </label></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Actualizar Plantilla rtf </div></td>
          <td height="22" colspan="3"><input name="txtarcrtfcont" type="file" id="txtarcrtfcont"  onBlur="javascript: cambiar_nombre();" size="50" maxlength="200" ></td>
        </tr>
		<tr>
          <td height="22"><div align="right">Plantilla rtf </div></td>
          <td height="22" colspan="3"><label>
            <input name="txtnomrtf" type="text" id="txtnomrtf" size="50" maxlength="60"  readonly>
          </label></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Campos Personal </div></td>
          <td height="22" colspan="3">
            <div align="left">
              <select name="cmbcamper" id="cmbcamper"  >
                <option value="" selected>--Seleccione--</option>
			    <option value="$ls_dia">DIA</option>
			    <option value="$ls_mes">MES</option>
				<option value="$ls_ano">A&Ntilde;O</option>
                <option value="$ls_nombres">NOMBRES</option>
                <option value="$ls_apellidos">APELLIDOS</option>
				<option value="$ls_nacionalidad">NACIONALIDAD</option>	
				<option value="$ls_profesion">PROFESION</option>						
				<option value="$ls_cedula">CEDULA</option>
                <option value="$ls_cargo">CARGO</option> 
				<option value="$ls_unidad_administrativa">UNIDAD ADMINISTRATIVA</option>               
				<option value="$ls_nroreg">Nº REGISTRO CONTRATO</option>
				<option value="$ls_tipo_contrato">TIPO CONTRATO</option>
                <option value="$ld_fecha_inicio">FECHA INCIO CONTRATO</option>
                <option value="$ld_fecha_culminacion">FECHA CULMINACION CONTRATO</option>                
                <option value="$ls_horario">HORARIO</option>
                <option value="$ls_funciones">FUNCIONES</option>
				<option value="$ls_observacion">OBSERVACION</option>
				<option value="$ls_descripcion">DESCRIPCION</option>
				<option value="$li_monto_contrato">SUELDO DEL CONTRATO</option>             
              </select>
              <a href="javascript: ue_ingresarcampo();"><img src="../../../../shared/imagebank/arrow.gif" alt="Ingresar" width="13" height="13" border="0"></a> </div></td></tr>
        <tr>
          <td height="22" colspan="4" class="titulo-celdanew">Contenido</td>
          </tr>
        <tr>
          <td height="22" colspan="4"><div align="center">
            <textarea name="txtconcont" cols="100" rows="20" id="txtconcont" onKeyUp="javascript: ue_validarcomillas(this);"></textarea>
          </div></td>
          </tr>
        <tr class="titulo-celdanew">
          <td height="22" colspan="4">Pie de Pagina </td>
        </tr>
        <tr>
          <td height="22" colspan="4"><div align="center">
            <textarea name="txtpiepagcont" cols="100" rows="5" id="txtpiepagcont" onKeyUp="javascript: ue_validarcomillas(this);"></textarea>
          </div></td>
          </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td height="22" colspan="3"><input name="operacion" type="hidden" id="operacion">
		     <input type="hidden" id="hidguardar" name="hidguardar">
		  </td>
        </tr>
      </table>   
	 
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>      
<p>&nbsp;</p>
</body>

</html>