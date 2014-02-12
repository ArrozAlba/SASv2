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
$io_fun_compra->uf_load_seguridad("SOC","sigesp_soc_r_registro_cotizacion.php",$ls_permisos,&$la_seguridad,$la_permisos);

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
<title>Reporte de Registro de Cotizaciones</title>
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
	 $ls_operacion    = $_POST["operacion"];
	 $ls_numcotdes    = $_POST["txtnumcotdes"];
	 $ls_numcothas    = $_POST["txtnumcothas"];
     $ls_feccotdes    = $_POST["txtfeccotdes"];
	 $ls_feccothas    = $_POST["txtfeccothas"];
     $ls_codprodes    = $_POST["txtcodprodes"];
	 $ls_codprohas    = $_POST["txtcodprohas"];
	 $ls_numsolcotdes = $_POST["txtnumsolcotdes"];
	 $ls_numsolcothas = $_POST["txtnumsolcothas"];
   }
else
   {
	 $ls_operacion    = "";
 	 $ls_numcotdes    = "";
	 $ls_numcothas    = "";
     $ls_feccotdes    = '01/'.date("m/Y");
	 $ls_feccothas    = date("d/m/Y");
     $ls_codprodes    = "";
	 $ls_codprohas    = "";
	 $ls_numsolcotdes = "";
	 $ls_numsolcothas = "";
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
        <td height="22" colspan="6" class="titulo-ventana">Reporte de Registro de Cotizaciones 
          <input name="operacion" type="hidden" id="operacion" value="<?php print $ls_operacion ?>" /></td>
      </tr>
      <tr>
        <td width="60" height="13">&nbsp;</td>
        <td width="94" height="13">&nbsp;</td>
        <td width="96" height="13">&nbsp;</td>
        <td width="96" height="13">&nbsp;</td>
        <td width="96" height="13">&nbsp;</td>
        <td width="99" height="13">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" colspan="6" style="text-align:center"><table width="434" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td colspan="4" style="text-align:left"><strong> Nro. Cotizacion</strong></td>
          </tr>
          <tr>
            <td width="63" style="text-align:right">Desde</td>
            <td width="171" style="text-align:left"><input name="txtnumcotdes" type="text" id="txtnumcotdes" value="<?php print $ls_numcotdes ?>" size="20" maxlength="15"  style="text-align:center "  onblur="javascript:rellenar_cad(this.value,15,this)" onkeypress="return keyRestrict(event,'1234567890');" />
              <a href="javascript: ue_catalogo('sigesp_soc_cat_cotizacion.php?origen=REPDES');"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar hasta..." name="buscar1" width="15" height="15" border="0"  id="buscar1" /></a></td>
            <td width="44" style="text-align:right">Hasta</td>
            <td width="154" style="text-align:left"><input name="txtnumcothas" type="text" id="txtnumcothas" value="<?php print $ls_numcothas ?>" size="20" maxlength="15"  style="text-align:center"  onblur="javascript:rellenar_cad(this.value,15,this)"  onkeypress="return keyRestrict(event,'1234567890');" />
                <a href="javascript: ue_catalogo('sigesp_soc_cat_cotizacion.php?origen=REPHAS');"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar desde..." name="buscar2" width="15" height="15" border="0" id="buscar2" /></a></td>
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
        <td height="22" colspan="6" style="text-align:center"><table width="435" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td colspan="4" style="text-align:left"><strong> Proveedores </strong></td>
          </tr>
          <tr>
            <td width="64" style="text-align:right">Desde</td>
            <td width="157" style="text-align:left"><input name="txtcodprodes" type="text" id="txtcodprodes" value="<?php print $ls_codprodes ?>" size="15" maxlength="10"  style="text-align:center "  onblur="javascript:rellenar_cad(this.value,10,this)" onkeypress="return keyRestrict(event,'1234567890');" />
              <a href="javascript:ue_catalogoproveedores('REPDES');"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar hasta..." name="buscar1" width="15" height="15" border="0"  id="buscar1" /></a></td>
            <td width="54" style="text-align:right">Hasta</td>
            <td width="158" style="text-align:left"><input name="txtcodprohas" type="text" id="txtcodprohas" value="<?php print $ls_codprohas ?>" size="15" maxlength="10"  style="text-align:center"  onblur="javascript:rellenar_cad(this.value,10,this)"  onkeypress="return keyRestrict(event,'1234567890');" />
                <a href="javascript:ue_catalogoproveedores('REPHAS');"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar desde..." name="buscar2" width="15" height="15" border="0" id="buscar2" /></a>
              <input name="hidrangocodigos" type="hidden" id="hidrangocodigos" /></td>
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
        <td height="22" colspan="6" style="text-align:center"><table width="435" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td colspan="4" style="text-align:left"><strong>Fecha de la Cotizaci&oacute;n </strong></td>
          </tr>
          <tr>
            <td width="63" style="text-align:right">Desde</td>
            <td width="134" style="text-align:left">
              <input name="txtfecsoldes" type="text" id="txtfeccotdes" value="<?php print $ls_feccotdes ?>" size="12" maxlength="10"  style="text-align:left"  datepicker="true" onkeypress="currencyDate(this);" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);"/></td>
            <td width="49" style="text-align:right">&nbsp;</td>
            <td width="187" style="text-align:left">Hasta
              <input name="txtfeccothas" type="text" id="txtfeccothas" value="<?php print $ls_feccothas ?>" size="12" maxlength="10"  style="text-align:left"  datepicker="true" onkeypress="currencyDate(this);" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);"/></td>
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
        <td height="22" colspan="6" style="text-align:center"><table width="434" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td colspan="4" style="text-align:left"><strong>Solicitud de Cotizaci&oacute;n</strong></td>
          </tr>
          <tr>
            <td width="64" style="text-align:right">Desde</td>
            <td width="170" style="text-align:left"><input name="txtnumsolcotdes" type="text" id="txtnumsolcotdes" value="<?php print $ls_numsolcotdes ?>" size="20" maxlength="15"  style="text-align:center "  onblur="javascript:rellenar_cad(this.value,15,this)" onkeypress="return keyRestrict(event,'1234567890');" />
            <a href="javascript: ue_catalogo('sigesp_soc_cat_solicitud_cotizacion.php?origen=REPDES');"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar hasta..." name="buscar1" width="15" height="15" border="0"  id="buscar1" onclick="document.formulario.operacion.value='RC'" /></a></td>
            <td width="44" style="text-align:right">Hasta</td>
            <td width="154" style="text-align:left"><input name="txtnumsolcothas" type="text" id="txtnumsolcothas" value="<?php print $ls_numsolcothas ?>" size="20" maxlength="15"  style="text-align:center"  onblur="javascript:rellenar_cad(this.value,15,this)"  onkeypress="return keyRestrict(event,'1234567890');" />
            <a href="javascript: ue_catalogo('sigesp_soc_cat_solicitud_cotizacion.php?origen=REPHAS');"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar desde..." name="buscar2" width="15" height="15" border="0" id="buscar2" onclick="document.formulario.operacion.value='RC'" /></a></td>
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
        <td height="30" colspan="6" style="text-align:center"><table width="435" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">

          <tr>
            <td width="65" height="24" style="text-align:right"><strong>Tipo</strong></td>
            <td width="132" style="text-align:left"><label>
              <select name="cmbtipcot" id="cmbtipcot">
                <option value="-">---seleccione---</option>
                <option value="B">Bienes</option>
                <option value="S">Servicios</option>
              </select>
            </label></td>
            <td width="35" style="text-align:right">&nbsp;</td>
            <td width="201" style="text-align:left"><strong>Estatus</strong>
              <label>
              <select name="cmbestcot" id="cmbestcot">
                <option value="-">---seleccione---</option>
                <option value="R">Registro</option>
                <option value="P">Procesada</option>
              </select>
              </label></td>
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
    </table>
  </form>
  <p>&nbsp;</p>
</div>
</body>
<script language="javascript">
f = document.formulario;
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
    var diad = f.txtfecsoldes.value.substr(0, 2); 
    var mesd = f.txtfecsoldes.value.substr(3, 2); 
    var anod = f.txtfecsoldes.value.substr(6, 4); 
    var diah = f.txtfecsolhas.value.substr(0, 2); 
    var mesh = f.txtfecsolhas.value.substr(3, 2); 
    var anoh = f.txtfecsolhas.value.substr(6, 4); 
    
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
	ls_numcotdes    = f.txtnumcotdes.value;
	ls_numcothas    = f.txtnumcothas.value;
	ls_numsolcotdes = f.txtnumsolcotdes.value;
	ls_numsolcothas = f.txtnumsolcothas.value;
	ls_codprodes    = f.txtcodprodes.value;
	ls_codprohas    = f.txtcodprohas.value;
	ls_feccotdes    = f.txtfeccotdes.value;
	ls_feccothas    = f.txtfeccothas.value;
	ls_tipcot       = f.cmbtipcot.value;
	ls_estcot       = f.cmbestcot.value;
	
	pagina="reportes/sigesp_soc_rpp_registro_cotizacion.php?numcotdes="+ls_numcotdes+"&numcothas="+ls_numcothas+"&codprodes="+ls_codprodes+
	       "&codprohas="+ls_codprohas+"&feccotdes="+ls_feccotdes+"&feccothas="+ls_feccothas+
		   "&tipcot="+ls_tipcot+"&estcot="+ls_estcot+"&numsolcotdes="+ls_numsolcotdes+"&numsolcothas="+ls_numsolcothas;
		   				
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no,left=50,top=50");
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

function ue_catalogoproveedores(ls_tipo)
{
	window.open("sigesp_soc_cat_proveedor.php?tipo="+ls_tipo,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,resizable=yes,location=no,left=50,top=50");          
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