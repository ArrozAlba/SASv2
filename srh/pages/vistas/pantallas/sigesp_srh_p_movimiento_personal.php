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
$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_p_amonestacion.php",$ls_permisos,$la_seguridad,$la_permisos);
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
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_personal.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../../../shared/js/number_format.js"></script>



</head>

<body onLoad="javascript: ue_nuevo_movimiento();" >
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
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript: ue_limpiar_movimiento();"><img src="../../../public/imagenes/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar_movimiento();"><img src="../../../public/imagenes/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_buscar_movimientos();"><img src="../../../public/imagenes/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_eliminar_movimiento();"><img src="../../../public/imagenes/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
	<td class="toolbar" width="24"><div align="center"><a href="javascript: ue_cerrar();"><img src="../../../public/imagenes/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><img src="../../../public/imagenes/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="24"><div align="center"></div></td>
    <td class="toolbar" width="618">&nbsp;</td>
  </tr>
</table>


<p>&nbsp;</p>
<form name="form1" method="post" action=""  ><div >


<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_srh->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_srh);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
 <div align="center"></div>
<p align="center" class="oculto1" id="mostrar" style="font:#EBEBEB"  ></p>
 <table width="728" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td width="728" height="136"><p>&nbsp;</p>
 <table width="703" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
      <tr>
        <td colspan="4"><div align="center">
            
        </div></td>
      </tr>
      <tr class="titulo-ventana">
        <td height="20" colspan="6" class="titulo-ventana">Registro de Movimiento de Personal</td>
      </tr>
      <tr>
        <td width="152" height="22"><div align="right"></div></td>
        <td colspan="3"><div align="left"></div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">N&uacute;mero Movimiento</div></td>
        <td colspan="3"><div align="left">
            <input name="txtnummov" type="text" id="txtnummov" onKeyUp="javascript: ue_validarnumero(this);"  size="16" maxlength="15" readonly>
        </div></td>
      </tr>
	   <tr>
        <td height="22"><div align="right">Fecha Registro </div></td>
        <td colspan="3"><div align="left">
            <input name="txtfecreg" type="text" id="txtfecreg" size="15" maxlength="10" readonly>
            <input name="reset422" type="reset" onClick="return showCalendar('txtfecreg', '%d/%m/%Y');" value=" ... " />
        </div></td>
      </tr>
	   <tr>
			  <td height="22" align="left"><div align="right">C&oacute;digo Personal</div></td>
          <td height="22" colspan="4"><input name="txtcodper" type="text" id="txtcodper"  maxlength="10"  size="16" style="text-align:center" readonly  >
               <a href="javascript:catalogo_personal();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Personal" name="buscartip" width="15" height="15" border="0" id="buscartip"></a> </td>
          <td width="42" valign="middle"> </td>
		  </tr>
		 
		       
        <tr>
		  <td height="22" align="left"><div align="right">Nombre</div></td>
          <td height="22" colspan="3"><input name="txtnomper" type="text" id="txtnomper"   style="text-align:justify" size="60" readonly ></tr>
         <tr>
	  <tr>
        <td height="22"><div align="right">Cargo Actual</div></td>
        <td colspan="3"><div align="left">
            <input name="txtcaract" type="text" id="txtcaract"  size="60"  readonly>
        </div></td>
      </tr>
	  <tr>
        <td height="22"><div align="right">Unidad Administrativa Actual</div></td>
        <td colspan="3"><div align="left">
            <input name="txtuniadm" type="text" id="txtuniadm"   size="60"  readonly>
        </div></td>
      </tr>
	   <tr>
        <td height="22"><div align="right">Sueldo B&aacute;sico Actual</div></td>
        <td colspan="3"><div align="left"><input name="txtsuelact" type="text" id="txtsuelact" readonly >
        </div></td>
      </tr>
     
     
      <tr>
          <td height="20"><div align="right">Unidad Administrativa Propuesta </div></td>
          <td height="20" colspan="4"><label>
            <input name="txtcoduniadm" type="text" id="txtcoduniadm"  size="15"  readonly>
           <a href="javascript: catalogo_unidad_adm();"><img src="../../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>&nbsp;
            
            <input name="txtdenuniadm" type="text" class="sin-borde" id="txtdenuniadm"  size="60" maxlength="50" readonly>
            </label></td>
        </tr>
      <tr>
        <td height="22"><div align="right">Cargo Propuesto</div></td>
        <td colspan="3"><div align="left">
            <input name="txtcodcar" type="text" id="txtcodcar"   size="16"  style="text-align:left"  readonly> 
			<a href="javascript:catalogo_cargo();"><img src="../../../../shared/imagebank/tools15/buscar.gif"  name="buscartip" width="15" height="15" border="0" id="buscartip">Buscar Cargo</a>
			<a href="javascript:catalogo_cargo_rac();"><img src="../../../../shared/imagebank/tools15/buscar.gif"  name="buscartip" width="15" height="15" border="0" id="buscartip">Buscar Cargo (RAC)</a>
			 </div>
			  <input name="txtcodnom"  type="hidden" id="txtcodnom"   readonly>			 </td>
      </tr>
	  
	   <tr>
        <td height="22"><div align="right">Denominaci&oacute;n del Cargo</div></td>
		<td colspan="3"><div align="left">
             
            <input name="txtdescar" type="text" id="txtdescar"  style="text-align:justify" size="60"  readonly >
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Grado</div></td>
        <td colspan="3"><label>
          <input name="txtgrapro" onKeyUp="javascript: ue_validarnumero(this);" type="text" size="5" id="txtgrapro" readonly>
        </label></td>
		</tr>
		<tr>
		<td height="22"><div align="right">Paso</div></td>
		 <td colspan="3"><label>
         <input name="txtpaspro" onKeyUp="javascript: ue_validarnumero(this);" type="text" size="5" id="txtpaspro"  readonly>
        </label></td>
      </tr>
	  
      <tr>
        <td height="22"><div align="right">Sueldo B&aacute;sico Propuesto</div></td>
        <td colspan="3"><div align="left"><input name="txtsuelpro" type="text" id="txtsuelpro" onKeyPress="return(ue_formatonumero(this,'.',',',event))" >
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Compensaci&oacute;n Propuesta</div></td>
        <td colspan="3"><div align="left"><input name="txtcompro" type="text" id="txtcompro" onKeyPress="return(ue_formatonumero(this,'.',',',event))" >
        </div></td>
      </tr>
	  <tr>
        <td height="22"><div align="right">Sueldo Total Propuesto</div></td>
        <td colspan="3"><div align="left"><input name="txtsuetotpro" type="text" id="txtsuetotpro"  onFocus="javascript: sumar_sueldo();" onKeyPress="return(ue_formatonumero(this,'.',',',event))" readonly>
        </div></td>
      </tr>
	  <tr>
        <td height="22"><div align="right">Fecha Inicio Movimiento </div></td>
        <td colspan="3"><div align="left">
            <input name="txtfecinimov" type="text" id="txtfecinimov" size="15" maxlength="10" readonly>
            <input name="reset422" type="reset" onClick="return showCalendar('txtfecinimov', '%d/%m/%Y');" value=" ... " />
        </div></td>
      </tr>
	    <tr>
        <td height="22"><div align="right">Grupo de Movimiento</div></td>
        <td colspan="3"><div align="left">
          <input name="txtcodgrumov" type="text" id="txtcodgrumov"  maxlength="16" style="text-align:justify"  readonly >
            <a href="javascript:catalogo_tipo_movimiento();"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Personal" name="buscartip" width="15" height="15" border="0" id="buscartip"></a>
            <input name="txtdengrumov" type="text" class="sin-borde" id="txtdengrumov"  size="40" maxlength="80" readonly>
        </div></td>
      </tr>
	   <tr>
        <td height="22"><div align="right">Motivo del Movimiento</div></td>
        <td colspan="3"><div align="left"><textarea name="txtmotivo" cols="70" onKeyUp="javascript: ue_validarcomillas(this);" rows="3" id="txtmotivo"></textarea>
        </div></td>
      </tr>
	   <tr>
        <td height="22"><div align="right">Observaci&oacute;n</div></td>
        <td colspan="3"><div align="left"><textarea name="txtobs" cols="70" onKeyUp="javascript: ue_validarcomillas(this);" rows="3" id="txtobs"> </textarea>
        </div></td>
      </tr>
	  
      <tr>
        <td height="22">&nbsp;</td>
        <td colspan="3">&nbsp;</td>
      </tr>
	 
    </table>
	   
      <p>&nbsp;</p></td> </tr>
     
</table>
  <p>
    <input type="hidden" id="hidguardar_mov" name="hidguardar_mov">
    <input name="hidcontrolcar" type="hidden" id="hidcontrolcar" value="1">
	<input name="txtnumerofilas" type="hidden" id="txtnumerofilas" value="<? print $lo_solicitud->li_filas;?>">
	<input name="operacion" type="hidden" id="operacion">
	<input name="hidcoduniadm" type="hidden" id="hidcoduniadm">
	<input name="hidcodcar" type="hidden" id="hidcodcar">
	<input name="hidcodnom" type="hidden" id="hidcodnom">	
	<input name="hidgrado" type="hidden" id="hidgrado">	
	<input name="hidpaso" type="hidden" id="hidpaso">	
  </p>

  <div align="center"></div>
<p align="center" class="oculto1" id="mostrar" style="font:#EBEBEB"  ></p>
</form>


</body>


</html>

<script>

function catalogo_personal ()
{

  window.open("../catalogos/sigesp_srh_cat_personal.php?valor_cat=0"+"&tipo=11","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	
}


</script>