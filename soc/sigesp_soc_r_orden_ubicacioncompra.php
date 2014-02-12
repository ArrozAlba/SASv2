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
$io_fun_compra->uf_load_seguridad("SOC","sigesp_soc_r_orden_ubicacioncompra.php",$ls_permisos,&$la_seguridad,$la_permisos);

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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/validaciones.js"></script>
<title>Reporte de Ubicacion de Orden de Compra</title>
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
</style>
</head>
<body>
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
      <td width="800" height="13" colspan="11" class="toolbar" style="text-align:left"><span class="toolbar" style="text-align:left"></span><a href="javascript: ue_imprimir();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0" title="Imprimir" /></a><a href="../soc/sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0" title="Salir" /></a></td>
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
    <table width="543" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td height="22" colspan="6" class="titulo-ventana"><input name="operacion" type="hidden" id="operacion" value="<?php print $ls_operacion ?>" />
          Reporte de Ubicacion de Orden de Compra
          
         </td>
      </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td height="13" colspan="6">&nbsp;</td>
      </tr>
      <tr>
        <td height="13" colspan="6"><table width="490" height="41" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td height="13" style="text-align:right"><div align="left"><strong>Tipo de Orden de Compra </strong></div></td>
          </tr>
          <tr>
            <td height="26" style="text-align:right"><label></label>
              <div align="left"><strong>Bienes</strong>
                <input name="rdtipo" type="radio" class="sin-borde" value="B" />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>Servicios </strong>
<input name="rdtipo" type="radio" class="sin-borde" value="S" />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>&nbsp; Ambas </strong>
<input name="rdtipo" type="radio" class="sin-borde" value="A" checked="checked" />
</div></td>
        </tr>
        </table></td>
      </tr>
      <tr>
        <td height="13" colspan="6">&nbsp;</td>
      </tr>
      <tr>
        <td height="13" colspan="6"><table width="490" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td colspan="4" style="text-align:left"><strong>Orden de Compra </strong></td>
          </tr>
          <tr>
            <td width="63" style="text-align:right">Desde</td>
            <td width="171" style="text-align:left"><input name="txtnumordcomdes" type="text" id="txtnumordcomdes"  size="20" maxlength="15"  style="text-align:center "  onblur="javascript:rellenar_cad(this.value,15,this)" onkeypress="return keyRestrict(event,'1234567890');" />
              <a href="javascript: ue_catalogo('sigesp_soc_cat_orden_compra.php?tipo=REPORTE-DESDE');"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar hasta..." name="buscar1" width="15" height="15" border="0"  id="buscar1" /></a></td>
            <td width="44" style="text-align:right">Hasta</td>
            <td width="154" style="text-align:left"><input name="txtnumordcomhas" type="text" id="txtnumordcomhas"  size="20" maxlength="15"  style="text-align:center"  onblur="javascript:rellenar_cad(this.value,15,this)"  onkeypress="return keyRestrict(event,'1234567890');" />
              <a href="javascript: ue_catalogo('sigesp_soc_cat_orden_compra.php?tipo=REPORTE-HASTA');"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar desde..." name="buscar2" width="15" height="15" border="0" id="buscar2" /></a></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="13" colspan="6">&nbsp;</td>
      </tr>
      <tr>
        <td height="13" colspan="6"><table width="490" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td colspan="4" style="text-align:left"><strong>Proveedor</strong></td>
          </tr>
          <tr>
            <td width="63" style="text-align:right">Desde</td>
            <td width="171" style="text-align:left"><input name="txtcodprodes" type="text" id="txtcodprodes" size="20" maxlength="15"  style="text-align:center "  onblur="javascript:rellenar_cad(this.value,15,this)" onkeypress="return keyRestrict(event,'1234567890');" />
              <a href="javascript: ue_catalogo('sigesp_soc_cat_proveedor.php?tipo=REPDES');"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar hasta..." name="buscar1" width="15" height="15" border="0"  id="buscar1" /></a></td>
            <td width="44" style="text-align:right">Hasta</td>
            <td width="154" style="text-align:left"><input name="txtcodprohas" type="text" id="txtcodprohas" size="20" maxlength="15"  style="text-align:center"  onblur="javascript:rellenar_cad(this.value,15,this)"  onkeypress="return keyRestrict(event,'1234567890');" />
              <a href="javascript: ue_catalogo('sigesp_soc_cat_proveedor.php?tipo=REPHAS');"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar desde..." name="buscar2" width="15" height="15" border="0" id="buscar2" /></a></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="22" height="22">&nbsp;</td>
        <td width="22" height="22">&nbsp;</td>
        <td width="22" height="22">&nbsp;</td>
        <td width="22" height="22">&nbsp;</td>
        <td width="22" height="22">&nbsp;</td>
        <td width="22" height="22">&nbsp;</td>
      </tr>
    </table>
  </form>
  <p>&nbsp;</p>
</div>
</body>
<script language="javascript">
f = document.formulario;

function ue_catalogo_bienes(ls_tipo)
{
    f.tipo.value=ls_tipo
	window.open("sigesp_soc_cat_bienes.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,resizable=yes,location=no,left=50,top=50");          
}

function ue_catalogo_servicios(ls_tipo)
{
    f.tipo.value=ls_tipo
	window.open("sigesp_soc_cat_servicios.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,resizable=yes,location=no,left=50,top=50");          
}

function rellenar_cad(cadena,longitud,objeto)
{//1
	var mystring = new String(cadena);
	cadena_ceros = "";
	lencad       = mystring.length;
    total        = longitud-lencad;
	if (cadena!="")
	   {
	     for (i=1;i<=total;i++)
			 {
			   cadena_ceros=cadena_ceros+"0";
			 }
	     cadena=cadena_ceros+cadena;
		 objeto.value=cadena;
	   }
}

function ue_imprimir()
{
	if (f.rdtipo[0].checked)
	{ 
		ls_tipord ="B";
	}
	if (f.rdtipo[1].checked)
	{ 
		ls_tipord ="S";
	}
	if (f.rdtipo[2].checked)
	{ 
		ls_tipord ="A";
	}		
	ls_numordcomdes = f.txtnumordcomdes.value;
	ls_numordcomhas = f.txtnumordcomhas.value;
	ls_codprodes    = f.txtcodprodes.value;
	ls_codprohas    = f.txtcodprohas.value;
		
	ls_reporte  = "sigesp_soc_rpp_ubicacioncompra.php";
	pagina="reportes/"+ls_reporte+"?txtnumordcomdes="+ls_numordcomdes+"&rdtipo="+ls_tipord
					 +"&txtnumordcomhas="+ls_numordcomhas+"&txtcodprodes="+ls_codprodes+"&txtcodprohas="+ls_codprohas;
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no,left=50,top=50");
}  


function ue_catalogo(ls_catalogo)
{
	// abre el catalogo que se paso por parametros
	window.open(ls_catalogo,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=750,height=400,left=50,top=50,location=no,resizable=yes");
}

var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>