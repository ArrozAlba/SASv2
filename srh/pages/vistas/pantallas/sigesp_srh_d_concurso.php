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
require_once("../../../class_folder/utilidades/class_funciones_srh.php");
require_once("../../../../shared/class_folder/class_fecha.php");
require_once("../../../../shared/class_folder/class_datastore.php");
require_once("../../../../shared/class_folder/class_funciones_db.php"); 
$io_fun_srh=new class_funciones_srh('../../../../');
$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_d_concurso.php",$ls_permisos,$la_seguridad,$la_permisos);
$io_fecha    = new class_fecha('../../../../');

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
  
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Definici&oacute;n de Concurso </title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
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
<script type="text/javascript" language="JavaScript1.2" src="../../../public/js/librerias_comunes.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_concurso.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../../../sno/js/funcion_nomina.js"></script>

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

<style type="text/css">
<!--
.style1 {color: #EBEBEB}
.Estilo1 {
	color: #6699CC;
	font-size: 12px;
	font-family: Georgia, "Times New Roman", Times, serif;
}
-->
</style>
</head>

<body onLoad="javascript:ue_nuevo();">
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
</table>


<p>&nbsp;</p>
<div align="center">
  <table width="675" height="368" border="0" class="formato-blanco">
    <tr>
      <td width="667" height="358"><div align="left">
   <form name="form1"  id="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_srh->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_srh);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	
<table width="595" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td colspan="3" class="titulo-ventana">Definici&oacute;n de Concurso</td>
  </tr>
  <tr class="formato-blanco">
    <td width="93" height="19">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr class="formato-blanco">
    <td height="29"><div align="right">C&oacute;digo</div></td>
    <td width="160" height="29"><input name="txtcodcon" type="text" id="txtcodcon" size="11" maxlength="10"  readonly   style="text-align:center ">
        <input name="hidstatus" type="hidden" id="hidstatus">  </td>
    <td width="342" class="sin-borde"><div id="existe" class="letras-pequeñas" style="display:none"> 
		</div></td>
  </tr>
  <tr class="formato-blanco">
    <td height="28"><div align="right">Descripci&oacute;n</div></td>
    <td height="28" colspan="2"><input name="txtdescon" type="text" id="txtdescon" onKeyUp="ue_validarcomillas(this);"  size="80" maxlength="254"></td>
  </tr>
  
  <tr class="formato-blanco"> 
 <td height="28"><div align="right">Fecha Apertura</div></td>
  <td height="28" valign="middle"><input name="txtfechaaper" type="text" id="txtfechaaper"     size="16"   style="text-align:center" readonly > <input name="reset" type="reset" onclick="return showCalendar('txtfechaaper', '%d/%m/%Y');" value=" ... " /></td>
         <td>&nbsp;</td>
  </tr>
  
   <tr class="formato-blanco"> 
 <td height="28"><div align="right">Fecha Cierre</div></td>
  <td height="28" valign="middle"> <input name="txtfechacie" type="text" id="txtfechacie" 
  size="16"     style="text-align:center" readonly > <input name="reset" type="reset" onclick="return showCalendar('txtfechacie', '%d/%m/%Y');" value=" ... " /> </td>           <td>&nbsp;</td>
  </tr>
  
  <tr class="formato-blanco">
    <td height="28"><div align="right">Cargo</div></td>
  <td colspan="3"><div align="left">
            <input name="txtcodcar" type="text" id="txtcodcar"   size="16"  style="text-align:left"  readonly> 
			<a href="javascript:catalogo_cargo();"><img src="../../../../shared/imagebank/tools15/buscar.gif"  name="buscartip" width="15" height="15" border="0" id="buscartip">Buscar Cargo</a>
			<a href="javascript:catalogo_cargo_rac();"><img src="../../../../shared/imagebank/tools15/buscar.gif"  name="buscartip" width="15" height="15" border="0" id="buscartip">Buscar Cargo (RAC)</a>
			 </div>
			  <input name="txtcodnom"  type="hidden" id="txtcodnom"   readonly>
			 </td>
  </tr>
  
  <tr>
        <td height="22"><div align="right">Denominaci&oacute;n del Cargo</div></td>
		<td colspan="3"><div align="left">
             
            <input name="txtdescar" type="text" id="txtdescar"  style="text-align:justify" size="60"  readonly >
        </div></td>
      </tr>
  
  <tr class="formato-blanco"> 
 <td height="28"><div align="right">Cantidad Cargos</div></td>
  <td height="28" valign="middle"> <input name="txtcantcar" type="text" id="txtcantcar" onKeyUp="ue_validarcomillas(this); ue_validarnumero(this);"  size="6" maxlength="5"  style="text-align:center" > </td>
         <td>&nbsp;</td>
  </tr>
  
 <tr class="formato-blanco">
    <td height="28"><div align="right">Tipo</div></td>
    <td height="28" valign="middle">
	    <label>
		<select name="combotipo" id="combotipo">
		  <option value="null">--Seleccione--</option>
		  <option value="Interno">Interno</option>
		  <option value="Externo">Externo</option>
		  <option value="Mixto">Mixto</option>
		  <option value="Otro">Otro</option>
		</select>
		</label>
	
	</td>
  </tr>
  
  <tr class="formato-blanco"> 
 <td height="28"><div align="right">Estado</div></td>
  <td height="28" valign="baseline"><p>
    <label>
    <select name="comboestatus" id="comboestatus">
      <option value="null">--Seleccione--</option>
      <option value="Abierto">Abierto</option>
      <option value="Cerrado">Cerrado</option>
    </select>
    </label>
  </p></td>
  </tr>
  
     <tr>
          <td height="22">&nbsp;<input name="txttipo" type="hidden" id="txttipo" size="2" maxlength="2" value="M" readonly></td>
          <td colspan="4"> <div align="right"></div></td>
   </tr>
  
</table>
   <input name="hidcontrolcar" type="hidden" id="hidcontrolcar" value="1">
   <input name="hidcontrol" type="hidden" id="hidcontrol" value="">
   <input name="hidcontrol2" type="hidden" id="hidcontrol2" value="">
          </form>
      </div></td>
    </tr>

  </table>
</div>

<div align="center"></div>
<p align="center" class="style1" id="mostrar" style="font:#EBEBEB" ></p>



</body>


</html>