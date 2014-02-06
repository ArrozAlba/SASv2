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
$io_fun_compra->uf_load_seguridad("SOC","sigesp_soc_r_analisis_cotizacion.php",$ls_permisos,&$la_seguridad,$la_permisos);
$ls_reporte = $io_fun_compra->uf_select_config("SOC","REPORTE","LISTADO_ORDCOM","sigesp_soc_rpp_listado_orden_compra.php","C");

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
<title>Reporte de Orden de Compra</title>
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
<?php
if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion = $_POST["operacion"];
     $ls_numordcomdes = $_POST["txtnumordcomdes"];
     $ls_numordcomhas = $_POST["txtnumordcomhas"];
     $ls_codprodes = $_POST["txtcodprodes"];
	 $ls_codprohas = $_POST["txtcodprohas"];
	 $ls_fecordcomdes = $_POST["txtfecordcomdes"];
	 $ls_fecordcomhas = $_POST["txtfecordcomhas"];
	 $ls_coduniejedes = $_POST["txtcoduniejedes"];
	 $ls_coduniadmhas = $_POST["txtcoduniejehas"];
	 $ls_codartdes = $_POST["txtcodartdes"];
	 $ls_codarthas = $_POST["txtcodarthas"];
	 $ls_codserdes = $_POST["txtcodserdes"];
	 $ls_codserhas = $_POST["txtcodserhas"];
   }
else
   {
	 $ls_operacion = "";
	 $ls_numordcomdes = "";
	 $ls_numordcomhas = "";
     $ls_codprodes = "";
	 $ls_codprohas = "";
     $ls_fecordcomdes = '01/'.date("m/Y");
	 $ls_fecordcomhas = date("d/m/Y");
	 $ls_coduniejedes = "";
	 $ls_coduniadmhas = "";
	 $ls_codartdes = "";
	 $ls_codarthas = "";
	 $ls_codserdes = "";
	 $ls_codserhas = "";
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
          Reporte de Orden de Compra
          
          <input name="formato"    type="hidden" id="formato"    value="<?php print $ls_reporte; ?>" /></td>
      </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr style="visibility:hidden">
        <td height="13" colspan="2" style="text-align:right">Reporte en</td>
        <td height="13"><div align="left">
          <select name="cmbbsf" id="cmbbsf">
            <option value="0" selected="selected">Bs.</option>
            <option value="1">Bs.F.</option>
          </select>
        </div></td>
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
            <td width="171" style="text-align:left"><input name="txtnumordcomdes" type="text" id="txtnumordcomdes" value="<?php print $ls_numordcomdes ?>" size="20" maxlength="15"  style="text-align:center "  onblur="javascript:rellenar_cad(this.value,15,this)" onkeypress="return keyRestrict(event,'1234567890');" />
              <a href="javascript: ue_catalogo('sigesp_soc_cat_orden_compra.php?origen=REPORTE-DESDE');"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar hasta..." name="buscar1" width="15" height="15" border="0"  id="buscar1" /></a></td>
            <td width="44" style="text-align:right">Hasta</td>
            <td width="154" style="text-align:left"><input name="txtnumordcomhas" type="text" id="txtnumordcomhas" value="<?php print $ls_numordcomhas ?>" size="20" maxlength="15"  style="text-align:center"  onblur="javascript:rellenar_cad(this.value,15,this)"  onkeypress="return keyRestrict(event,'1234567890');" />
              <a href="javascript: ue_catalogo('sigesp_soc_cat_orden_compra.php?origen=REPORTE-HASTA');"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar desde..." name="buscar2" width="15" height="15" border="0" id="buscar2" /></a></td>
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
            <td width="171" style="text-align:left"><input name="txtcodprodes" type="text" id="txtcodprodes" value="<?php print $ls_codprodes ?>" size="20" maxlength="15"  style="text-align:center "  onblur="javascript:rellenar_cad(this.value,15,this)" onkeypress="return keyRestrict(event,'1234567890');" />
              <a href="javascript: ue_catalogo('sigesp_soc_cat_proveedor.php?tipo=REPDES');"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar hasta..." name="buscar1" width="15" height="15" border="0"  id="buscar1" /></a></td>
            <td width="44" style="text-align:right">Hasta</td>
            <td width="154" style="text-align:left"><input name="txtcodprohas" type="text" id="txtcodprohas" value="<?php print $ls_codprohas ?>" size="20" maxlength="15"  style="text-align:center"  onblur="javascript:rellenar_cad(this.value,15,this)"  onkeypress="return keyRestrict(event,'1234567890');" />
              <a href="javascript: ue_catalogo('sigesp_soc_cat_proveedor.php?tipo=REPHAS');"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar desde..." name="buscar2" width="15" height="15" border="0" id="buscar2" /></a></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="60" height="13">&nbsp;</td>
        <td width="26" height="13">&nbsp;</td>
        <td width="164" height="13">&nbsp;</td>
        <td width="96" height="13">&nbsp;</td>
        <td width="96" height="13">&nbsp;</td>
        <td width="99" height="13">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" colspan="6" style="text-align:center"><table width="490" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td colspan="4" style="text-align:left"><strong>Fecha</strong></td>
          </tr>
          <tr>
            <td width="63" style="text-align:right">Desde</td>
            <td width="171" style="text-align:left"><input name="txtfecordcomdes" type="text" id="txtfecordcomdes" value="<?php print $ls_fecordcomdes ?>" size="12" maxlength="10"  style="text-align:left"  datepicker="true" onkeypress="currencyDate(this);" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);"/>
              <a href="javascript: ue_catalogo('sigesp_soc_cat_analisis_cotizacion.php?catalogo=txtnumanacotdes');"></a></td>
            <td width="44" style="text-align:right">Hasta</td>
            <td width="154" style="text-align:left"><input name="txtfecordcomhas" type="text" id="txtfecordcomhas" value="<?php print $ls_fecordcomhas ?>" size="12" maxlength="10"  style="text-align:left"  datepicker="true" onkeypress="currencyDate(this);" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);"/>
              <a href="javascript: ue_catalogo('sigesp_soc_cat_analisis_cotizacion.php?catalogo=txtnumanacothas');"></a></td>
          </tr>
        </table></td>
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
        <td height="22" colspan="6" style="text-align:center"><table width="490" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td colspan="4" style="text-align:left"><strong>Unidad Ejecutora </strong></td>
          </tr>
          <tr>
            <td width="64" style="text-align:right">Desde</td>
            <td width="157" style="text-align:left"><input name="txtcoduniejedes" type="text" id="txtcoduniejedes" value="<?php print $ls_coduniejedes ?>" size="15" maxlength="10"  style="text-align:center "  onblur="javascript:rellenar_cad(this.value,10,this)" onkeypress="return keyRestrict(event,'1234567890');" />
              <a href="javascript:ue_catalogo('sigesp_cat_unidad_ejecutora.php?tipo=REPDES');"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar hasta..." name="buscar1" width="15" height="15" border="0"  id="buscar1" /></a></td>
            <td width="54" style="text-align:right">Hasta</td>
            <td width="158" style="text-align:left"><input name="txtcoduniejehas" type="text" id="txtcoduniejehas" value="<?php print $ls_coduniadmhas ?>" size="15" maxlength="10"  style="text-align:center"  onblur="javascript:rellenar_cad(this.value,10,this)"  onkeypress="return keyRestrict(event,'1234567890');" />
              <a href="javascript:ue_catalogo('sigesp_cat_unidad_ejecutora.php?tipo=REPHAS');"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar desde..." name="buscar2" width="15" height="15" border="0" id="buscar2" /></a></td>
          </tr>
        </table></td>
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
        <td height="22" colspan="6" style="text-align:center"><table width="490" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td colspan="4" style="text-align:left"><div align="center"><strong>Todas</strong>
                <input name="esttip" type="radio" class="sin-borde" onClick="uf_deshabilitar()" value="T" checked="checked"/>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>Articulos </strong>
  <input name="esttip" type="radio" class="sin-borde" value="A" onClick="uf_deshabilitar()"/>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>&nbsp; Servicios </strong>
  <input name="esttip" type="radio" class="sin-borde" value="S" onClick="uf_deshabilitar()"/>
            </div></td>
          </tr>
          <tr class="formato-blanco">
            <td width="96" style="text-align:right">Articulo Desde</td>
            <td style="text-align:left"><span class="Estilo2">
              <input name="txtcodartdes" type="text"  style="text-align:center" class="formato-blanco" id="txtcodartdes" value="<?php print $ls_codartdes ?>" size="22" maxlength="20" readonly="readonly" />
              <a href="javascript:ue_catalogo_bienes('REPDES');"><img src="../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo de Articulos" name="busartdes" width="15" height="15" border="0" class="weekend" id="busartdes" /></a></span></td>
            <td style="text-align:right"><span style="text-align:left">Hasta</span></td>
            <td style="text-align:left"><span class="Estilo2">
              <input name="txcodtarthas" type="text" style="text-align:center" class="formato-blanco" id="txcodtarthas" value="<?php print $ls_codarthas ?>" size="22" maxlength="20" readonly="readonly" />
            </span><a href="javascript:ue_catalogo_bienes('REPHAS');"><img src="../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo de Articulos" name="busarthas" width="15" height="15" border="0" id="busarthas" />
            <input name="tipo" type="hidden" id="tipo" />
            </a></td>
          </tr>
          <tr>
            <td width="96" style="text-align:right">Servicios Desde</td>
            <td width="170" style="text-align:left"><span class="Estilo2">
              <input name="txtcodserdes" type="text"  style="text-align:center" class="formato-blanco" id="txtcodserdes" value="<?php print $ls_codserdes ?>" size="22" maxlength="20" readonly="readonly" />
            </span><a href="javascript:ue_catalogo_servicios('REPDES');"><img src="../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo de Servicios" name="busserdes" width="15" height="15" border="0" id="busserdes" /></a></td>
            <td width="42" style="text-align:right"><span style="text-align:left">Hasta </span></td>
            <td width="180" style="text-align:left"><span class="Estilo2">
              <input name="txtcodserhas" type="text" style="text-align:center" class="formato-blanco" id="txtcodserhas" value="<?php print $ls_codserhas ?>" size="22" maxlength="20" readonly="readonly" />
            </span><a href="javascript:ue_catalogo_servicios('REPHAS');"><img src="../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo de Servicios" name="busserhas" width="15" height="15" border="0" id="busserhas" /></a></td>
          </tr>
        </table></td>
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
        <td height="13" colspan="6"><table width="490" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td colspan="3"><strong>Estatus</strong></td>
          </tr>
          <tr>
            <td width="162"><input name="rdemi" type="checkbox" class="sin-borde" id="rdemi" value="1" />
              Emitida</td>
            <td width="155"><input name="rdpre" type="checkbox" class="sin-borde" id="rdpre" value="1" />              
              Pre-Comprometida</td>
            <td width="171"><input name="rdcon" type="checkbox" class="sin-borde" id="rdcon" value="1" />
              <span class="Estilo2">Comprometida (Procesada)</span></td>
          </tr>
          <tr>
            <td><input name="rdanu" type="checkbox" class="sin-borde" id="rdanu" value="1" />
              Anulada</td>
            <td><input name="rdanucom" type="checkbox" class="sin-borde" id="rdanucom" value="1" />
              <span class="Estilo2">Pre-Comp.Anulada              </span></td>
            <td><input name="rdinv" type="checkbox" class="sin-borde" id="rdinv" value="1" />
              <span class="Estilo2">Ent. Compra</span></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="30" colspan="6" style="text-align:center">&nbsp;</td>
      </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
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

//--------------------------------------------------------
//	Función que valida que un intervalo de tiempo sea valido
//--------------------------------------------------------
   function ue_comparar_intervalo()
   { 
	var valido = false; 
    var diad = f.txtfecanades.value.substr(0, 2); 
    var mesd = f.txtfecanades.value.substr(3, 2); 
    var anod = f.txtfecanades.value.substr(6, 4); 
    var diah = f.txtfecanahas.value.substr(0, 2); 
    var mesh = f.txtfecanahas.value.substr(3, 2); 
    var anoh = f.txtfecanahas.value.substr(6, 4); 
    
	if (anod < anoh)
	{
		 valido = true; 
	 }
    else 
	{ 
     if (anod == anoh)
	 { 
      if (mesd < mesh)
	  {
	   valido = true; 
	  }
      else 
	  { 
       if (mesd == mesh)
	   {
 		if (diad <= diah)
		{
		 valido = true; 
		}
	   }
      } 
     } 
    } 
    if (valido==false)
	{
		alert("El rango de fecha es invalido !!!");
	} 
	return valido;
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
	ls_fecordcomdes = f.txtfecordcomdes.value;
	ls_fecordcomhas = f.txtfecordcomhas.value;
	ls_coduniejedes = f.txtcoduniejedes.value;
	ls_coduniadmhas = f.txtcoduniejehas.value;
	ls_codartdes    = f.txtcodartdes.value;
	ls_codarthas    = f.txcodtarthas.value;
	ls_codserdes    = f.txtcodserdes.value;
	ls_codserhas    = f.txtcodserhas.value;
    
	if (f.esttip[0].checked)
	{ 
		ls_tipo ="T";
	}
	if (f.esttip[1].checked)
	{ 
		ls_tipo ="A";
	}
	if (f.esttip[2].checked)
	{ 
		ls_tipo ="S";
	}
	
	if (f.rdemi.checked) //EMITIDA
	{ 
		rdemi = 1;
	}
	else
	{ 
		rdemi = 0;
	}
	
	if (f.rdanu.checked)  // ANULADA
	{ 
		rdanu = 1;
	}
	else
	{ 
		rdanu = 0;
	}

	if (f.rdpre.checked) //PRECOMPROMETIDA
	{ 
		rdpre = 1;
	}
	else
	{ 
		rdpre = 0;
	}
	
	if (f.rdanucom.checked) // ANULADA COMPROMETIDA
	{ 
		rdanucom = 1;
	}
	else
	{ 
		rdanucom = 0;
	}
	
	if (f.rdcon.checked) //  COMPROMETIDA
	{ 
		rdcon = 1;
	}
	else
	{ 
		rdcon = 0;
	}

	if (f.rdinv.checked)
	{ 
		rdinv = 1;
	}
	else
	{ 
		rdinv = 0;
	}
	
	if( ((ls_tipo=='S') && (ls_tipord=='B')) || ((ls_tipo=='B') && (ls_tipord=='S')) )
	{
			alert("No puede hacer busquedas de Bienes con detalles de Servicios o viceversa !!!");
	}
	else
	{
		ls_reporte  = f.formato.value;
		tiporeporte = f.cmbbsf.value;
		
		pagina="reportes/"+ls_reporte+"?txtnumordcomdes="+ls_numordcomdes+"&rdtipo="+ls_tipord
		                 +"&txtnumordcomhas="+ls_numordcomhas+"&txtcodprodes="+ls_codprodes+"&txtcodprohas="+ls_codprohas
						 +"&txtfecordcomdes="+ls_fecordcomdes+"&txtfecordcomhas="+ls_fecordcomhas+"&esttip="+ls_tipo
						 +"&txtcoduniejedes="+ls_coduniejedes+"&txtcoduniejehas="+ls_coduniadmhas
						 +"&txtcodartdes="+ls_codartdes+"&txtcodarthas="+ls_codarthas+"&rdanucom="+rdanucom
						 +"&rdemi="+rdemi+"&rdpre="+rdpre+"&rdcon="+rdcon+"&rdanu="+rdanu+"&rdinv="+rdinv
						 +"&txtcodserdes="+ls_codserdes+"&txtcodserhas="+ls_codserhas+"&tiporeporte="+tiporeporte;
		window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no,left=50,top=50");
	}	
}  

function currencyDate(date)
{ 
	ls_date=date.value;
	li_long=ls_date.length;
	if (li_long==2)
	   {
	     ls_date   = ls_date+"/";
	 	 ls_string = ls_date.substr(0,2);
		 li_string = parseInt(ls_string);
		 if ((li_string>=1)&&(li_string<=31))
			{
			  date.value=ls_date;
			}
		 else
			{
			  date.value="";
			}
			
	   }
	if (li_long==5)
	   {
	     ls_date   = ls_date+"/";
		 ls_string = ls_date.substr(3,2);
		 li_string = parseInt(ls_string);
		 if ((li_string>=1)&&(li_string<=12))
			{
			  date.value=ls_date;
			}
		 else
			{
			  date.value=ls_date.substr(0,3);
			}
	   }
	if (li_long==10)
	   {
	     ls_string = ls_date.substr(6,4);
		 li_string = parseInt(ls_string);
		 if ((li_string>=1900)&&(li_string<=2090))
			{
			  date.value=ls_date;
			}
		 else
			{
			  date.value=ls_date.substr(0,6);
			}
	   }
} 

function ue_catalogo(ls_catalogo)
{
	// abre el catalogo que se paso por parametros
	window.open(ls_catalogo,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=750,height=400,left=50,top=50,location=no,resizable=yes");
}

function uf_deshabilitar()
{
   if (document.formulario.esttip[0].checked)
   {
	  document.formulario.txtcodartdes.value="";          
	  document.formulario.txcodtarthas.value="";
	  document.formulario.txtcodserdes.value="";
	  document.formulario.txtcodserhas.value="";
	  document.formulario.txtcodartdes.disabled=true;	   
	  document.formulario.txcodtarthas.disabled=true;	   
	  document.formulario.txtcodserdes.disabled=true;	   
	  document.formulario.txtcodserhas.disabled=true;	   
	  eval("document.images['busartdes'].style.visibility='visible'");
	  eval("document.images['busarthas'].style.visibility='visible'");
	  eval("document.images['busserdes'].style.visibility='visible'");
	  eval("document.images['busserhas'].style.visibility='visible'");
   }
   
   if (document.formulario.esttip[1].checked)
   {
	  document.formulario.txtcodartdes.value="";
	  document.formulario.txcodtarthas.value="";
	  document.formulario.txtcodserdes.value="";
	  document.formulario.txtcodserhas.value="";
	  
	  document.formulario.txtcodartdes.disabled=true;	   
	  document.formulario.txcodtarthas.disabled=true;	   
	  eval("document.images['busartdes'].style.visibility='visible'");
	  eval("document.images['busarthas'].style.visibility='visible'");
	  document.formulario.txtcodserdes.disabled=false;	   
	  document.formulario.txtcodserhas.disabled=false;	   
	  eval("document.images['busserdes'].style.visibility='hidden'");
	  eval("document.images['busserhas'].style.visibility='hidden'");
   }
   
   if (document.formulario.esttip[2].checked)
   {
	  document.formulario.txtcodartdes.value="";
	  document.formulario.txcodtarthas.value="";
	  document.formulario.txtcodserdes.value="";
	  document.formulario.txtcodserhas.value="";
	  
	  document.formulario.txtcodartdes.disabled=false;	   
	  document.formulario.txcodtarthas.disabled=false;	   
	  eval("document.images['busartdes'].style.visibility='hidden'");
	  eval("document.images['busarthas'].style.visibility='hidden'");		  
	  document.formulario.txtcodserdes.disabled=true;	   
	  document.formulario.txtcodserhas.disabled=true;	   
	  eval("document.images['busserdes'].style.visibility='visible'");
	  eval("document.images['busserhas'].style.visibility='visible'");
   }
}
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>