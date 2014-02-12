<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
     print "<script language=JavaScript>";
     print "location.href='../sigesp_inicio_sesion.php'";
     print "</script>";		
   }
require_once("class_folder/class_funciones_soc.php");
$io_fun_compra = new class_funciones_soc();
$io_fun_compra->uf_load_seguridad("SOC","sigesp_soc_r_aceptacion_servicios.php",$ls_permisos,&$la_seguridad,$la_permisos);

$ls_logusr = $_SESSION["la_logusr"];
$ls_codemp = $_SESSION["la_empresa"]["codemp"];
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_soc.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../soc/js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/validaciones.js"></script>
<title>Acta de Aceptaci&oacute;n de Servicios</title>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/general.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css" />
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<style type="text/css">
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
.Estilo1 {color: #6699CC}
-->
</style></head>

<body>
<?php
if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion = $_POST["operacion"];
     $ls_numordcom = $_POST["txtnumordcom"];
	 $ls_codper    = $_POST["txtcedper"];
     $ls_fecordcom = $_POST["txtfecordcom"];
	 $ls_nomper    = $_POST["txtnompersol"];
   }
else
   {
	 $ls_operacion = "";
     $ls_numordcom = "";
	 $ls_codper    = "";
     $ls_fecordcom = "";
	 $ls_nomper    = "";
   }

?>
<div align="center">
  <table width="800" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
    <tr>
      <td width="800" height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" alt="Encabezado" width="800" height="40" /></td>
    </tr>
    <td height="20" colspan="12" bgcolor="#E7E7E7">
    <table width="800" border="0" align="center" cellpadding="0" cellspacing="0">			
      <td width="430" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Ordenes de Compra</td>
	  <td width="350" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
	  <tr>
	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	<td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
      </tr>
    </table></td><tr>
      <td height="20" bgcolor="#E7E7E7" class="cd-menu" style="text-align:left"><script type="text/javascript" language="JavaScript1.2" src="../soc/js/menu.js"></script></td>
    </tr>
    <tr>
      <td height="13" colspan="11" class="toolbar"></td>
    </tr>
    <tr style="text-align:left">
      <td width="800" height="13" colspan="11" class="toolbar" style="text-align:left"><a href="javascript: ue_imprimir();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0" title="Imprimir" /></a><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0" title="Salir" /></a></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <form id="formulario" name="formulario" method="post" action="">
  <?php
  //////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_compra->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_compra);
  //////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
  ?>
    <table width="573" height="216" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td height="214"><div align="center">
          <table width="509" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
             <tr class="titulo-ventana">
               <td height="22" colspan="2" align="center" class="titulo-ventana">Aceptaci&oacute;n de Servicios </td>
              </tr>
             <tr>
               <td width="108" height="13" align="center">&nbsp;</td>
               <td height="13">&nbsp;</td>
             </tr>
             <tr>
              <td height="22" align="center"><div align="right">Orden de Servicio </div></td>
              <td width="399" height="22"><div align="left">
                  <input name="txtnumordcomdes" type="text" id="txtnumordcomdes" style="text-align:center" maxlength="15" value="<?php print $ls_numordcom ?>" />
              <a href="javascript: uf_catalogo('sigesp_soc_cat_orden_compra.php?origen=AS&tipo=REPORTE-DESDE');"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0" /></a></div></td>
            </tr>
            <tr>
              <td height="22" align="center"><div align="right">Personal</div></td>
              <td height="22" align="center"><div align="left">
                  <input name="txtcedper" type="text" id="txtcedper" style="text-align:center" value="<?php print $ls_codper ?>" readonly="readonly" />
                  <a href="javascript: uf_catalogo('sigesp_soc_cat_personal.php?origen=AS');"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0" /></a>
                  <input name="txtnompersol" type="text" class="sin-borde" id="txtnompersol" value="<?php print $ls_nomper ?>" size="40" readonly="readonly" />
              </div></td>
            </tr>
            <tr>
              <td height="22" align="center"><div align="right">Rep. del Proveedor </div></td>
              <td height="22" align="center"><div align="left">
                  <input name="txtnomrespro" type="text" id="txtnomrespro" size="60" onkeypress="return keyRestrict(event,'1234567890'+'abcdefghijklmn&ntilde;opqrstuvwxyz&aacute;&eacute;&iacute;&oacute;&uacute; ()@#!%/[]*-+_');" />
              </div></td>
            </tr>
            <tr>
              <td height="22" align="center"><div align="right">Fecha Servicio </div></td>
              <td height="22" align="center"><div align="left">
                  <input name="txtfecordcom" type="text" id="txtfecordcom" value="<?php print $ls_fecordcom ?>" size="17"  datepicker="true" onkeypress="currencyDate(this);" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);"/>
              </div></td>
            </tr>
            <tr>
              <td height="22" align="center"><div align="right"></div></td>
              <td height="22" align="center"><div align="right">
                  <input name="hidstatus" type="hidden" id="hidstatus" />
                  <input name="operacion"   type="hidden"   id="operacion2"   value="<?php print $ls_operacion;?>" />
              </div></td>
            </tr>
          </table>
        </div></td>
      </tr>
    </table>
  </form>
  <p>&nbsp;</p>
</div>
</body>
<script language="javascript">
f = document.formulario;
function ue_imprimir()
{
  li_imprimir = f.imprimir.value;
  if (li_imprimir)
	 {
       ls_numordcom = f.txtnumordcomdes.value;
	   ls_fecordcom = f.txtfecordcom.value;
	   ls_cedper    = f.txtcedper.value;
	   ls_nomper    = f.txtnompersol.value;
	   ls_nomreppro = f.txtnomrespro.value;
	   if (ls_numordcom!="" && ls_fecordcom!="")
	      {
	        ls_pagina = "reportes/sigesp_soc_rpp_aceptacion_servicios.php?numordcom="+ls_numordcom+"&fecordcom="+ls_fecordcom+"&cedper="+ls_cedper+"&nomper="+ls_nomper+"&nomreppro="+ls_nomreppro;  				
	        window.open(ls_pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no,left=50,top=50");
	      }
       else
	      {
	        alert("Debe Completar los Datos del Reporte Número y Fecha de la  Orden Servicio !!!");
	      }
     }
   else
	 {
	   alert("No tiene permiso para realizar esta operación");
	 }
} 

	function uf_catalogo(ls_catalogo)
	{
		window.open(ls_catalogo,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=630,height=400,left=120,top=70,location=no,resizable=yes");
	}
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>