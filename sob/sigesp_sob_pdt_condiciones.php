<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Detalle de Condiciones de Pago</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
body {
	margin-top: 0px;
}
-->
</style></head>

<body>


<form name="form1" method="post" action="">
<?Php
$ls_monto=$_GET["monto"];
$ls_fecha=$_GET["fecha"];
$ls_porcentaje=$_GET["porcentaje"];
$ls_por="0,00";
$ls_mon="0,00";
?>
<table width="293" border="0" align="center" cellpadding="0" cellspacing="0" class="titulo-celdanew">
  <tr>
    <td width="301"><div align="center">Detalles de Condiciones de Pago </div></td>
  </tr>
</table>
  <table width="291" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td height="42"><div align="right">Fecha</div></td>
      <td><input name="txtfecha" type="text" id="txtfecha" size="11" maxlength="12" readonly="true" datepicker="true"  ></td>
    </tr>
    <tr>
      <td width="107" height="42"><div align="right">Porcentaje</div></td>
      <td width="182"><input name="txtporcentaje" type="text" id="txtporcentaje" style="text-align:right "  onBlur="javascript:uf_procesarmonto(this)" onKeyPress="return(currencyFormat(this,'.',',',event))" value="<? print $ls_por?>" size="6" maxlength="6" onKeyDown="textCounter(this,6)"  onKeyUp="textCounter(this,6)">
&nbsp;&nbsp;%</td>
    </tr>
    <tr>
      <td height="43"><div align="right">Monto</div></td>
      <td><input name="txtmonto" type="text" id="txtmonto" style="text-align:right "  onBlur="javascript:uf_procesarmonto(this)" onKeyPress="return(currencyFormat(this,'.',',',event))" value="<? print $ls_mon?>"></td>
    </tr>
    <tr>
      <td><div align="right"></div></td>
      <td><a href="javascript:uf_aceptar();"><img src="../shared/imagebank/aprobado.gif" alt="Aceptar" width="15" height="15" border="0" onClick="javascript:ue_comparar_intervalo('hidfecha','txtfecha','La fecha de la condicion debe ser mayor o igual a la fecha de inicio del Contrato!!!')"></a><a href="javascript:uf_cancelar();"><img src="../shared/imagebank/eliminar.gif" border="0" alt="Cancelar" width="15" height="15" ></a></td>
    </tr>
  </table>
  <input name="hidmonto" id="hidmonto" type="hidden" value="<? print $ls_monto?>">
  <input name="hidfecha" id="hidfecha" type="hidden" value="<? print $ls_fecha?>">
  <input name="hidporcentaje" id="hidporcentaje" type="hidden" value="<? print $ls_porcentaje?>">
</form>
<p>&nbsp;</p>
</body>
<script language="javascript">
function uf_procesarmonto (txt)
{
	f=document.form1;
	ld_montocontrato=uf_convertir_monto(f.hidmonto.value);	
	ld_porcentajecontrato=uf_convertir_monto(f.hidporcentaje.value);
	if(txt.id=="txtmonto")
	{
		ld_montocondicion=uf_convertir_monto(txt.value);		
		ld_porcentaje=ld_montocondicion*100/ld_montocontrato;
		if ((parseFloat(ld_porcentaje)+parseFloat(ld_porcentajecontrato)) <= 100)
		{
			f.txtporcentaje.value=uf_convertir(ld_porcentaje);
		}
		else
		{
			alert("El total de los porcentajes no debe exceder el 100%!!!");
			f.txtmonto.value="0,00";
			f.txtporcentaje.value="0,00";
		}
		
	}
	else
	{
		ld_porcentaje=uf_convertir_monto(txt.value);
		if ((parseFloat(ld_porcentaje)+parseFloat(ld_porcentajecontrato)) <= 100)
		{
			ld_montocondicion=ld_porcentaje*ld_montocontrato/100;
			f.txtmonto.value=uf_convertir(ld_montocondicion);		
		}
		else
		{
			alert("El total de los porcentajes no debe exceder el 100%!!!");
			f.txtporcentaje.value="0,00";
			f.txtmonto.value="0,00";
		}
		
	}
}

function uf_aceptar()
{
	f=document.form1;
	uf_procesarmonto(f.txtporcentaje);
	ls_monto=f.txtmonto.value;
	ls_porcentaje=f.txtporcentaje.value;
	ls_fecha=f.txtfecha.value;
	if (ls_fecha=="")
	{
		alert("La fecha está vacía!!!");
		f.txtfecha.focus();
	}
	else
	{
		if(ls_porcentaje=="" || parseFloat(ls_porcentaje)==0)
		{
			alert("Debe especificar un Porcentaje y/o un Monto!!!");
			f.txtporcentaje.focus();
		}
		else
		{
			
			opener.ue_cargarcondiciones(ls_monto,ls_porcentaje,ls_fecha);
			f.txtmonto.value="";
			f.txtfecha.value="";
			f.txtporcentaje.value="";
			close();
		}	
	}	
}

function uf_cancelar()
{
	f.txtmonto.value="";
	f.txtfecha.value="";
	f.txtporcentaje.value="";
	f.txtfecha.focus();
}
function currencyFormat(fld, milSep, decSep, e)
{ 
    var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789'; 
    var aux = aux2 = ''; 
    var whichCode = (window.Event) ? e.which : e.keyCode; 
    if (whichCode == 13) return true; // Enter 
    if (whichCode == 8) return true; // Backspace <-
    if (whichCode == 127) return true; // Suprimir -Del 
    key = String.fromCharCode(whichCode); // Get key value from key code 
    if (strCheck.indexOf(key) == -1) return false; // Not a valid key 
    len = fld.value.length; 
    for(i = 0; i < len; i++) 
     if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != decSep)) break; 
    aux = ''; 
    for(; i < len; i++) 
     if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i); 
    aux += key; 
    len = aux.length; 
    if (len == 0) fld.value = ''; 
    if (len == 1) fld.value = '0'+ decSep + '0' + aux; 
    if (len == 2) fld.value = '0'+ decSep + aux; 
    if (len > 2) { 
     aux2 = ''; 
     for (j = 0, i = len - 3; i >= 0; i--) { 
      if (j == 3) { 
       aux2 += milSep; 
       j = 0; 
      } 
      aux2 += aux.charAt(i); 
      j++; 
     } 
     fld.value = ''; 
     len2 = aux2.length; 
     for (i = len2 - 1; i >= 0; i--) 
      fld.value += aux2.charAt(i); 
     fld.value += decSep + aux.substr(len - 2, len); 
    } 
	
    return false; 
   }
   



</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
