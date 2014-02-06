<?php 
session_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey)){
		window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ 
		return false; 
		} 
		} 
	}
</script>
<title>Asignaci&oacute;n de Fondos Trimestral</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="javascript1.2" src="../shared/js/valida_tecla.js"></script>
<style type="text/css">
<!--
.Estilo1 {font-size: 15px}
-->
</style>
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../sno/css/nomina.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php
require_once("../shared/class_folder/sigesp_include.php");
$in = new sigesp_include();
$con= $in-> uf_conectar ();
require_once("../shared/class_folder/class_mensajes.php");
$msg=new class_mensajes();
require_once("../shared/class_folder/class_datastore.php");
$ds_prorep=new class_datastore();

if(array_key_exists("operacion",$_POST))
{
$ls_operacion=$_POST["operacion"];
}
else
{
$ls_operacion="";
}
   $ls_cuenta=$_GET["txtCuenta"];
   $ls_denominacion=$_GET["txtDenominacion"];
   $i=$_GET["fila"];
   $ls_tipo=$_GET["tipo"];
   if($ls_tipo=="M")
   {
       $ls_readonly="";
       $ld_asignado=$_GET["txtAsignacion"];
	   $ld_marzo="0,00";
	   $ld_junio="0,00";
	   $ld_septiembre="0,00";
	   $ld_diciembre="0,00";
	   if (array_key_exists("txtTotal",$_POST))
	   {
	    $ld_total=$_POST["txtTotal"];
	   }
	   else
	   {
	    $ld_total="0,00";
	   }
   }
   if($ls_tipo=="A")
   {
       $ls_readonly="readonly";
       $ld_asignado=number_format($_GET["txtAsignacion"],2,",",".");
	   $ld_total=$ld_asignado;
	   $ld_marzo=$_GET["marzo"];
	   $ld_junio=$_GET["junio"];
	   $ld_septiembre=$_GET["septiembre"];
	   $ld_diciembre=$_GET["diciembre"];
   }
?>
<form name="form1" method="post" action="">
  <p>&nbsp;</p>
  <table width="596" height="261" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-ventana">
      <td colspan="5"><div align="left" class="titulo-ventana">
        <div align="center" class="titulo">
          <div align="left" class="titulo-ventana">
            <div align="center">Asignaci&oacute;n de Fondos Trimestral </div>
          </div>
        </div>
      </div></td>
    </tr>
    <tr>
      <td height="13" colspan="2" class="fd-blanco">&nbsp;</td>
      <td colspan="2" class="fd-blanco">&nbsp;</td>
      <td width="211">&nbsp;</td>
    </tr>
    <tr>
      <td width="100" height="18" class="fd-blanco"><div align="right">Cuenta</div></td>
      <td colspan="4" class="sin-borde3"><div align="left">
        <input name="txtcuenta" type="text" class="sin-borde3" id="txtcuenta" value="    <?php print   $ls_cuenta ?>">
      </div></td>
    </tr>
    <tr>
      <td height="13" class="fd-blanco">&nbsp;</td>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td height="20" class="fd-blanco"><div align="right">Denominaci&oacute;n</div></td>
      <td colspan="4"><div align="left"><span class="fd-blanco"><span class="sin-borde3">
        <input name="txtdenominacion2" type="text" class="sin-borde3" id="txtdenominacion" value="   <?php print   $ls_denominacion  ?>" size="90" maxlength="150">
      </span></span></div></td>
    </tr>
    <tr>
      <td height="13" class="fd-blanco">&nbsp;</td>
      <td width="193">&nbsp;</td>
      <td colspan="2" class="fd-blanco">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="18" class="fd-blanco"><div align="right">Marzo</div></td>
      <td><input name="txtMarzo" type="text" class="fd-blanco" id="txtMarzo" onBlur="uf_actualizar(this)"  onKeyPress="return(currencyFormat(this,'.',',',event))" value="<?php print $ld_marzo?>" size="25" maxlength="25" style="text-align:right" <?php print $ls_readonly ?>></td>
      <td colspan="2" class="fd-blanco"><div align="right">Junio</div></td>
      <td><input name="txtJunio" type="text" class="fd-blanco" id="txtJunio2" onBlur="uf_actualizar(this)" onKeyPress="return(currencyFormat(this,'.',',',event))" value="<?php print $ld_junio?>" size="25" maxlength="25" style="text-align:right" <?php print $ls_readonly ?>></td>
    </tr>
    <tr>
      <td height="18" class="fd-blanco Estilo1">&nbsp;</td>
      <td><span class="Estilo1"></span></td>
      <td colspan="2" class="fd-blanco Estilo1">&nbsp;</td>
      <td><span class="Estilo1"></span><span class="Estilo1"></span></td>
    </tr>
    <tr>
      <td height="18" class="fd-blanco"><div align="right">Septiembre</div></td>
      <td><input name="txtSeptiembre" type="text" class="fd-blanco" id="txtSeptiembre2" onBlur="uf_actualizar(this)" onKeyPress="return(currencyFormat(this,'.',',',event))" value="<?php print $ld_septiembre?>" size="25" maxlength="25" style="text-align:right" <?php print $ls_readonly ?>></td>
      <td colspan="2" class="fd-blanco"><div align="right">Diciembre</div></td>
      <td><input name="txtDiciembre" type="text" class="fd-blanco" id="txtDiciembre2" onBlur="uf_actualizar(this)" onKeyPress="return(currencyFormat(this,'.',',',event))" value="<?php print $ld_diciembre?>" size="25" maxlength="25" style="text-align:right" <?php print $ls_readonly ?>></td>
    </tr>
    <tr>
      <td height="18" class="fd-blanco Estilo1">&nbsp;</td>
      <td><span class="Estilo1"></span></td>
      <td colspan="2" class="fd-blanco Estilo1">&nbsp;</td>
      <td><span class="Estilo1"></span><span class="Estilo1"></span></td>
    </tr>
    <tr>
      <td height="18" class="fd-blanco"><div align="right">Total</div></td>
      <td><input name="txtTotal" type="text" class="fd-blanco" id="txtTotal"  onKeyPress="return(currencyFormat(this,'.',',',event))" value="<?php print $ld_total ?>" size="25" maxlength="25" readonly style="text-align:right"></td>
      <td colspan="3" rowspan="4"><input name="botAceptar" type="button" class="boton" id="botAceptar" onClick="ue_aceptar()" value="Aceptar">        
        <input name="operacion" type="hidden" id="operacion" value="<?php print $ls_operacion?>">
      <input name="fila" type="hidden" id="fila" value="<?php print $i?>"> <input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo ?>">      </td>
    </tr>

    <tr>
      <td height="18" class="fd-blanco Estilo1">&nbsp;</td>
      <td><span class="Estilo1"></span></td>
    </tr>
    <tr>
      <td height="18" class="fd-blanco"><div align="right">Asignado</div></td>
      <td><input name="txtAsignacion" type="text" class="fd-blanco" id="txtAsignacion2"  onKeyPress="return(currencyFormat(this,'.',',',event))" value="<?php print $ld_asignado ?>" size="25" maxlength="25" readonly style="text-align:right"></td>
    </tr>
    <tr>
      <td class="fd-blanco">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
<script language="javascript">
function ue_aceptar()
{
	f=document.form1;
    li=f.fila.value;
	opcion=f.tipo.value;
	ld_asignado=f.txtAsignacion.value  
	ld_asignado=parseFloat(uf_convertir_monto(ld_asignado));
	
	ld_m3=f.txtMarzo.value;    
	ld_m3=parseFloat(uf_convertir_monto(ld_m3));

	ld_m6=f.txtJunio.value;
	ld_m6=parseFloat(uf_convertir_monto(ld_m6));

	ld_m9=f.txtSeptiembre.value;           
	ld_m9=parseFloat(uf_convertir_monto(ld_m9));

	ld_m12=f.txtDiciembre.value;       
	ld_m12=parseFloat(uf_convertir_monto(ld_m12));

	ld_total = parseFloat(ld_m3 + ld_m6 + ld_m9 + ld_m12);
	if(opcion=="A")
	{
	  total=redondear(ld_total,2);
	  ld_total=total
	}
	if (ld_total!=ld_asignado)
	{
	  alert(" La Distribución no cuadra con lo asignado. Por favor revise los montos ");
	}
	else
	{	
		ld_m3=uf_convertir(ld_m3);		
		ld_m6=uf_convertir(ld_m6);		
		ld_m9=uf_convertir(ld_m9);		
		ld_m12=uf_convertir(ld_m12);	
		txtm1 = "marzo"+li;
        eval("opener.document.form1."+txtm1+".value='"+ld_m3+"'");
        txtm1 = "junio"+li;
        eval("opener.document.form1."+txtm1+".value='"+ld_m6+"'");
        txtm1 = "septiembre"+li;
        eval("opener.document.form1."+txtm1+".value='"+ld_m9+"'");
        txtm1 = "diciembre"+li;
        eval("opener.document.form1."+txtm1+".value='"+ld_m12+"'");
	    opener.document.form1.fila.value=li;
	    opener.document.form1.operacion.value="DISTRIBUIR";
	    if(opcion=="A")
		{
	      opener.document.form1.tipo.value="A";
		}
		else
		{
	      opener.document.form1.tipo.value="M";
		}
	    opener.document.form1.submit();
        close();
	}
}
function redondear(num, dec)
{ 
    num = parseFloat(num); 
    dec = parseFloat(dec); 
    dec = (!dec ? 2 : dec); 
    return Math.round(num * Math.pow(10, dec)) / Math.pow(10, dec); 
}
function uf_actualizar(obj)
{
		f=document.form1;
		if(obj.value=="")
		{
		  obj.value="0,00";
		}
		ld_asignado=f.txtAsignacion.value  
		ld_asignado=parseFloat(uf_convertir_monto(ld_asignado));
 
		ld_m3=f.txtMarzo.value;    
		ld_m3=parseFloat(uf_convertir_monto(ld_m3));
	
		ld_m6=f.txtJunio.value;
		ld_m6=parseFloat(uf_convertir_monto(ld_m6));
		
		ld_m9=f.txtSeptiembre.value;           
		ld_m9=parseFloat(uf_convertir_monto(ld_m9));
	
		ld_m12=f.txtDiciembre.value;       
		ld_m12=parseFloat(uf_convertir_monto(ld_m12));
	
		ld_total = parseFloat(ld_m3 + ld_m6 + ld_m9 + ld_m12);
		ld_total=redondear(ld_total,2);
		if (ld_total>ld_asignado)
		{
		  alert(" El Total es mayor al monto asignado. Por favor revise los montos ");
		}
		else
		{	
			f.txtTotal.value=ld_total;
			ld_total=uf_convertir(f.txtTotal.value);
			f.txtTotal.value=ld_total;
		}	
}

function redondear(num, dec)
{ 
    num = parseFloat(num); 
    dec = parseFloat(dec); 
    dec = (!dec ? 2 : dec); 
    return Math.round(num * Math.pow(10, dec)) / Math.pow(10, dec); 
}

function valida_null(field,valor)
{
  with (field) 
  {
    if (value==null||value=="")
      {
        //alert(mensaje);
		field=valor;
        return true;
      }
}
  
  function EvaluateText(cadena, obj)
  { 
	
    opc = false; 
	
    if (cadena == "%d")  
      if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32))  
      opc = true; 
    if (cadena == "%f")
	{ 
     if (event.keyCode > 47 && event.keyCode < 58) 
      opc = true; 
     if (obj.value.search("[.*]") == -1 && obj.value.length != 0) 
      if (event.keyCode == 46) 
       opc = true; 
    } 
	 if (cadena == "%s") // toma numero y letras
     if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32)||(event.keyCode > 47 && event.keyCode < 58)||(event.keyCode ==46)) 
      opc = true; 
	 if (cadena == "%c") // toma numero y punto
     if ((event.keyCode > 47 && event.keyCode < 58)|| (event.keyCode ==46))
      opc = true; 
    if(opc == false) 
     event.returnValue = false; 
   } 
 }  
 //--------------------------------------------------------
//	Función que formatea un número
//--------------------------------------------------------
function ue_formatonumero(fld, milSep, decSep, e)
{ 
	var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789'; 
    var aux = aux2 = ''; 
    var whichCode = (window.Event) ? e.which : e.keyCode; 

	if (whichCode == 13) return true; // Enter 
	if (whichCode == 8) return true; // Return
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
</html>