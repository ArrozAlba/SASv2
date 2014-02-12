<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Cat&aacute;logo de Cotizaciones</title>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_soc.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
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
-->
</style>
</head>

<body>
<?php
if (array_key_exists("hidoperacion",$_POST))
   {
	 $ls_operacion = $_POST["hidoperacion"];
 	 $ls_fecdes    = $_POST["txtfecdes"];
	 $ls_fechas    = $_POST["txtfechas"];
     $ls_numcot    = $_POST["txtnumcot"];
	 $ls_origen    = $_POST["origen"];
   }
else
   {
	 $ls_operacion = "";
	 $ls_fecdes    = '01/'.date("m/Y");
	 $ls_fechas    = date("d/m/Y");
     $ls_numcot    = "";
   	 $ls_origen    = $_GET["origen"];
   }
?>
<form id="formulario" name="formulario" method="post" action="">
<br />
  <table width="580" height="114" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-celda">
      <td height="22" colspan="4">Cat&aacute;logo de  Cotizaciones
        
        <input name="hidoperacion" type="hidden" id="hidoperacion" value="<?php print $ls_operacion ?>" />
      <input name="orden" type="hidden" id="orden" value="ASC" />
      <input name="campoorden" type="hidden" id="campoorden" value="numcot" />
      <input name="origen" type="hidden" id="origen" value="<?php print $ls_origen ?>" /></td>
    </tr>
    <tr>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
    </tr>
    <tr>
      <td height="22"><div align="right">Nro Cotizaci&oacute;n </div></td>
      <td height="22"><label>
        <input name="txtnumcot" type="text" id="txtnumcot" style="text-align:center" value="<?php print $ls_numcot ?>" size="20" maxlength="15" onKeyPress="return keyRestrict(event,'0123456789');" onBlur="javascript:rellenar_cad(this.value,15)" />
      </label></td>
      <td height="22">&nbsp;</td>
      <td height="22">Desde
        <input name="txtfecdes" type="text" id="txtfecdes"  value="<?php print $ls_fecdes ?>" size="13" maxlength="10" datepicker="true" onkeypress="currencyDate(this);" style="text-align:left">
      &nbsp;</td>
    </tr>
    <tr>
      <td width="104" height="22"><div align="right">Tipo</div></td>
      <td width="146" height="22"><label>
        <select name="cmbtipcot" id="cmbtipcot" style="width:120px">
          <option value="-">---seleccione---</option>
          <option value="B">Bienes</option>
          <option value="S">Servicios</option>
        </select>
      </label></td>
      <td width="57" height="22">&nbsp;</td>
      <td width="271" height="22">Hasta    
        <input name="txtfechas" type="text" id="txtfechas" value="<?php print $ls_fechas ?>" size="13" maxlength="10" datepicker="true" onkeypress="currencyDate(this);" style="text-align:left" /></td>
    </tr>
    <tr>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
    </tr>
    <tr>
      <td height="22">&nbsp;</td>
      <td height="22">&nbsp;</td>
      <td height="22">&nbsp;</td>
      <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar Solicitud" width="20" height="20" border="0" onclick="ue_search()" />Buscar Cotizaci&oacute;n </a></div></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <div id="resultados" align="center"></div>
  <p>&nbsp;</p>
</form>
</body>
<script language="javascript">
f = document.formulario;
function ue_aceptar(ls_numcot,ls_codpro,ls_numsolcot,ls_fecregcot,ls_obscot,ld_monsubcot,ld_moncrecot,ld_montotcot,li_diaent,ls_estcot,ls_forpag,ld_poriva,li_estinciva,ls_nompro,ls_tipcot)
{
  fop 					   = opener.document.formulario;
  fop.txtnumcot.value 	   = ls_numcot;
  fop.txtcodprov.value 	   = ls_codpro;
  fop.hidnumsolcot.value   = ls_numsolcot;
  fop.txtfecregcot.value   = ls_fecregcot;
  fop.cmbtipcot.value 	   = ls_tipcot;
  fop.txtobscot.value      = ls_obscot;
  fop.txtsubtotal.value    = ld_monsubcot;
  fop.txtcreditos.value    = ld_moncrecot;
  fop.txttotal.value       = ld_montotcot;
  fop.txtdiasentrega.value = li_diaent; 
  fop.txtestatus.value     = ls_estcot;
  fop.cmbformapago.value   = ls_forpag;
    if (li_estinciva=='1')
     {
	   fop.chkincorpora.checked=true;
	 }
  else
     {
	   fop.chkincorpora.checked=false;
	 }
  if (ls_estcot=='REGISTRO')
     {
	   fop.hidestcot.value = '0';
	 }
  else
     {
	   fop.hidestcot.value         = '1';
	   fop.cmbtipcot.disabled      = true;
	   fop.cmbformapago.disabled   = true;
	   fop.txtdiasentrega.readOnly = true; 
	   fop.txtobscot.readOnly      = true; 
	   fop.chkincorpora.disabled   = true;
	 }
  
  fop.txtporiva.value = ld_poriva;
  fop.txtnomprov.value = ls_nompro;
  fop.existe.value     = "TRUE";
  parametros		   = "";
  parametros		   = parametros+"&numcot="+ls_numcot+"&cod_pro="+ls_codpro;
  if (ls_tipcot=='B')
     {
	   proceso='CARGAR_DT_BIENES';
	 }
  else
     {
	   proceso='CARGAR_DT_SERVICIOS';
	 } 
  if (parametros!="")
	 {
		// Div donde se van a cargar los resultados
		divgrid = opener.document.getElementById("bienesservicios");
		// Instancia del Objeto AJAX
		ajax=objetoAjax();
		// Pagina donde están los métodos para buscar y pintar los resultados
		ajax.open("POST","class_folder/sigesp_soc_c_registro_cotizacion_ajax.php",true);
		ajax.onreadystatechange=function(){
			if(ajax.readyState==1)
			{
				//divgrid.innerHTML = "";//<-- aqui iria la precarga en AJAX 
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
		ajax.send("proceso="+proceso+""+parametros);
		}
}

function ue_search()
{
	f   = document.formulario;
	fop = opener.document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	ls_numcot  = f.txtnumcot.value;
	ls_fecdes  = f.txtfecdes.value;
	ls_fechas  = f.txtfechas.value;
	orden      = f.orden.value;
	ls_tipcot  = f.cmbtipcot.value;
	ls_origen  = f.origen.value;  

	campoorden   = f.campoorden.value;
	// Div donde se van a cargar los resultados
	divgrid = document.getElementById('resultados');
	// Instancia del Objeto AJAX
	ajax=objetoAjax();
	// Pagina donde están los métodos para buscar y pintar los resultados
	ajax.open("POST","class_folder/sigesp_soc_c_catalogo_ajax.php",true);
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
	ajax.send("catalogo=COTIZACION_REGISTRO&numcot="+ls_numcot+"&tipo="+ls_tipcot+"&fecdes="+ls_fecdes+"&fechas="+ls_fechas+"&orden="+orden+
			  "&campoorden="+campoorden+"&origen="+ls_origen);
}

function rellenar_cad(cadena,longitud)
{
	var mystring = new String(cadena);
	cadena_ceros = "";
	lencad       = mystring.length;
    total        =longitud-lencad;
    if (cadena!="")
	   {
	     for (i=1;i<=total;i++)
		     {
			   cadena_ceros=cadena_ceros+"0";
		     }
	     cadena=cadena_ceros+cadena;
		 document.formulario.txtnumcot.value=cadena;
	 } 
}

function aceptar_reportedesde(ls_numcot)
{
	fop.txtnumcotdes.value = ls_numcot;
	close();
}

function aceptar_reportehasta(ls_numcot) 
{
	fop.txtnumcothas.value = ls_numcot;
	close();
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>