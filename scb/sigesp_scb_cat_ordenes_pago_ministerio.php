<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Cat&aacute;logo de Ordenes de Pago Ministerio</title>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/general.css"  rel="stylesheet" type="text/css" />
<link href="../shared/css/tablas.css"   rel="stylesheet" type="text/css" />
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css" />
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_scb.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/valida_fecha.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
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
-->
</style></head>

<body>
<?php
if (array_key_exists("hidorigen",$_POST)) 
   {
     $ls_origen = $_POST["hidorigen"];
   }
elseif(array_key_exists("origen",$_GET))
   {
     $ls_origen = $_GET["origen"];
   }
else
   {
     $ls_origen = "";
   }
?>
<form id="form1" name="form1" method="post" action="">
  <p>&nbsp;</p>
  <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
    <tr class="titulo-celda">
      <td height="22" colspan="3" style="text-align:center">
        <input name="txtdisponible"    type="hidden" id="txtdisponible" style="text-align:right"  size="24" readonly />
        <input name="txtcuenta_scg"    type="hidden" id="txtcuenta_scg" style="text-align:center" size="24" readonly />Cat&aacute;logo de Ordenes de Pago Ministerio
        <input name="txttipocuenta"    type="hidden" id="txttipocuenta" />
        <input name="txtdentipocuenta" type="hidden" id="txtdentipocuenta" />      <input name="hidorigen" type="hidden" id="hidorigen" value="<?php echo $ls_origen; ?>" /></td>
    </tr>
    <tr>
      <td height="13">&nbsp;</td>
      <td height="13" colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">No. Orden Pago</td>
      <td width="191" height="22" style="text-align:left"><input name="txtnumordpagmin" type="text" id="txtnumordpagmin" size="20" maxlength="15" style="text-align:center" /></td>
      <td width="214" style="text-align:left">Fecha 
        <label>
        <input name="txtfecmov" type="text" id="txtfecmov"  style="text-align:center" size="15" maxlength="10" datepicker="true" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);">
      </label></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Operaci&oacute;n</td>
      <td height="22" colspan="2" style="text-align:left"><label>
        <select name="select" style="width:120px">
          <option value="-">---seleccione---</option>
          <option value="DP">Dep&oacute;sito</option>
          <option value="NC">Nota de Cr&eacute;dito</option>
        </select>
      </label></td>
    </tr>
    <tr>
      <td width="93" height="22" style="text-align:right">C&oacute;digo</td>
      <td height="22" colspan="2" style="text-align:left"><input name="txtcodban" type="text" id="txtcodban" style="text-align:center" onkeypress="return keyRestrict(event,'1234567890');" size="10" maxlength="3" /> 
        <a href="javascript:cat_bancos();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar Bancos..." width="15" height="15" border="0" /></a> <label>
        <input name="txtdenban" type="text" class="sin-borde" id="txtdenban" style="text-align:left" size="55" maxlength="254" readonly />
        </label></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Cuenta Bancaria </td>
      <td height="22" colspan="2" style="text-align:left"><input name="txtcuenta" type="text" id="txtcuenta" size="30" maxlength="25" /> 
        <a href="javascript:catalogo_cuentabanco();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar Cuentas..." width="15" height="15" border="0" /></a> 
        <label>
        <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion" style="text-align:left" size="35" maxlength="254"   />
        </label></td>
    </tr>
    <tr>
      <td height="22">&nbsp;</td>
      <td height="22" colspan="2"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0" /> Buscar</a></div></td>
    </tr>
  </table>
	<p>
  <div id="ordenes" align="center"></div>	
	</p>
</form>
</body>
<script language="javascript">
f   = document.form1;
fop = opener.document.form1;
var patron = new Array(2,2,4);
function aceptar_ordenespago(as_numordpagmin,as_codban,as_nomban,as_ctaban,as_denctaban,as_codtipcta,as_dentipcta,as_scgcta,ad_mondiscta,as_codtipfon,as_dentipfon,ad_monmaxmov)
{
  fop.txtnumordpagmin.value  = as_numordpagmin;
  fop.txtcodban.value 		 = as_codban;
  fop.txtcuenta.value 		 = as_ctaban;
  fop.txtdenban.value 		 = as_nomban;
  fop.txtdenominacion.value  = as_denctaban;
  fop.txttipocuenta.value    = as_codtipcta;
  fop.txtdentipocuenta.value = as_dentipcta;
  fop.txtcuenta_scg.value	 = as_scgcta;
  fop.txtdisponible.value	 = ad_mondiscta;
  fop.hidcodtipfon.value	 = as_codtipfon;
  fop.hiddentipfon.value	 = as_dentipfon;
  fop.hidmonmaxmov.value     = ad_monmaxmov;
  ls_opener                  = opener.document.form1.id;
  if (ls_opener=="sigesp_scb_p_emision_chq.php")
     {
	   fop.operacion.value="CARGAR_DT";
	   fop.action="sigesp_scb_p_emision_chq.php";
	   fop.submit();
	 }
  else if (ls_opener=="sigesp_scb_p_carta_orden_mnd.php")
     {
	   fop.operacion.value="CARGAR_DT";
	   fop.action="sigesp_scb_p_carta_orden_mnd.php";
	   fop.submit();
	 }
  close();
}

function cat_bancos()
{
  window.open("sigesp_cat_bancos.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
}

function catalogo_cuentabanco()
{
  ls_codban = f.txtcodban.value;
  ls_nomban = f.txtdenban.value;
  if (ls_codban!="")
	 {
	   pagina = "sigesp_cat_ctabanco.php?codigo="+ls_codban+"&hidnomban="+ls_nomban;
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=720,height=400,resizable=yes,location=no");
	 }
  else
	 {
	   alert("Debe seleccionar el Banco asociado a la cuenta");   
	 }
}

function ue_search()
{
	f=document.form1;
	// Div donde se van a cargar los resultados
	divgrid = document.getElementById('ordenes');
	// Instancia del Objeto AJAX
	ajax=objetoAjax();
	// Pagina donde están los métodos para buscar y pintar los resultados
	ajax.open("POST","class_folder/sigesp_scb_c_catalogo_ajax.php",true);
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1)
		{
			divgrid.innerHTML = "<img src='imagenes/loading.gif' width='350' height='200'>";//<-- aqui iria la precarga en AJAX 
		}
		else
		{
			if(ajax.readyState==4)
			{
				if(ajax.status==200)
				{//mostramos los datos dentro del contenedor
					divgrid.innerHTML = ajax.responseText
				}
				else
				{
					if(ajax.status==404)
					{
						divgrid.innerHTML = "La página no existe";
					}
					else
					{//mostramos el posible error     
						divgrid.innerHTML = "Error:".ajax.status;
					}
				}
			}
		}
	}	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	// Enviar todos los campos a la pagina para que haga el procesamiento
	ls_numordpagmin = f.txtnumordpagmin.value;
	ls_codban       = f.txtcodban.value;
	ls_ctaban  	    = f.txtcuenta.value;
	ls_fecmov  	    = f.txtfecmov.value;
	ls_codope 		= f.select.value;
	f.txtcodban.value = "";
	f.txtcuenta.value = "";
	f.txtdenban.value = "";
	f.txtdenominacion.value = "";
	f.txtfecmov.value = "";
	f.select.value = "-";
	ls_origen      = f.hidorigen.value;
	ajax.send("catalogo=ORDENESMINISTERIO&numordpagmin="+ls_numordpagmin+"&codban="+ls_codban+"&ctaban="+ls_ctaban+"&fecmov="+ls_fecmov+"&codope="+ls_codope+"&origen="+ls_origen);
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>