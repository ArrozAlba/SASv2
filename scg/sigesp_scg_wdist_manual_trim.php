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
<title>Asignaci&oacute;n de Fondos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="javascript1.2" src="../shared/js/valida_tecla.js"></script>
<style type="text/css">
<!--
.Estilo1 {font-size: 15px}
-->
</style>
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

if (array_key_exists("txtMarzo",$_POST))
{
   $ld_marzo=$_POST["txtMarzo"];
}

if (array_key_exists("txtJunio",$_POST))
{
   $ld_junio=$_POST["txtJunio"];
}

if (array_key_exists("txtSeptiembre",$_POST))
{
   $ld_septiembre=$_POST["txtSeptiembre"];
}

if (array_key_exists("txtDiciembre",$_POST))
{
   $ld_diciembre=$_POST["txtDiciembre"];
}

if (array_key_exists("txtTotal",$_POST))
{
   $ld_total=$_POST["txtTotal"];
}
else
{
   $ld_total=0.0000;
}

if (array_key_exists("txtAsignado",$_GET))
{
   $ld_asignado=$_GET["txtAsignado"];
}
?>
<form name="form1" method="post" action="">
  <p>&nbsp;</p>
  <table width="460" height="204" border="0" align="center" cellpadding="1" cellspacing="1" class="formato-blanco">
    <tr class="titulo-ventana">
      <td colspan="6"><div align="left" class="titulo-ventana">
          <div align="center" class="titulo">
            <div align="left" class="titulo-ventana">
              <div align="center">Asignaci&oacute;n de Fondos Trimestral </div>
            </div>
          </div>
      </div></td>
    </tr>
    <tr>
      <td class="fd-blanco">&nbsp;</td>
      <td>&nbsp;</td>
      <td width="17" rowspan="9">&nbsp;</td>
      <td class="fd-blanco">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
    <?php 
  if ($ls_operacion=="")
  {
  	  global $ds_prorep;
	  $i=$_GET["fila"];
	  $ds_prorep->data=$_SESSION["objact"];
	  $li_num=$ds_prorep->getRowCount("cod_report");
	  $ld_marzo=$ds_prorep->getValue("marzo",$i);
	  $ld_junio=$ds_prorep->getValue("junio",$i);
	  $ld_septiembre=$ds_prorep->getValue("septiembre",$i);
	  $ld_diciembre=$ds_prorep->getValue("diciembre",$i);
      $ld_total="0.0000";	
  }//($ls_operacion=="")
  ?>
      <td width="71" class="fd-blanco"><div align="right">Trimestre(1)</div></td>
      <td width="137">
        <div align="left">
          <input name="txtMarzo" type="text" class="fd-blanco" id="txtMarzo" onKeyPress="return(currencyFormat(this,'.',',',event))" value="<?php print $ld_marzo ?>" onBlur="uf_actualizar(this)">
      </div></td>
      <td width="75" class="fd-blanco"><div align="right">Trimestre(2)</div></td>
      <td width="134"><input name="txtJunio" type="text" class="fd-blanco" id="txtJulio3" onKeyPress="return(currencyFormat(this,'.',',',event))" value="<?php print $ld_junio?>" onBlur="uf_actualizar(this)"></td>
      <td width="5"><div align="right"> </div></td>
    </tr>
    <tr>
      <td class="fd-blanco Estilo1">&nbsp;</td>
      <td><span class="Estilo1"></span></td>
      <td class="fd-blanco Estilo1">&nbsp;</td>
      <td><span class="Estilo1"></span></td>
      <td><span class="Estilo1"></span></td>
    </tr>
    <tr>
      <td class="fd-blanco"><div align="right">Trimestre(3)</div></td>
      <td><input name="txtSeptiembre" type="text" class="fd-blanco" id="txtJunio5"   onKeyPress="return(currencyFormat(this,'.',',',event))" value="<?php print $ld_septiembre?>" onBlur="uf_actualizar(this)"></td>
      <td class="fd-blanco"><div align="right">Trimestre(4)</div></td>
      <td><input name="txtDiciembre" type="text" class="fd-blanco" id="txtDiciembre3" onKeyPress="return(currencyFormat(this,'.',',',event))" value="<?php print $ld_diciembre?>" onBlur="uf_actualizar(this)"></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td class="fd-blanco Estilo1">&nbsp;</td>
      <td><span class="Estilo1"></span></td>
      <td class="fd-blanco Estilo1">&nbsp;</td>
      <td><span class="Estilo1"></span></td>
      <td><span class="Estilo1"></span></td>
    </tr>
    <tr>
   <?php
  if($ls_operacion=="ACEPTAR")
  {
	global $ds_prorep;	
    $i=$_POST["fila"];
	$ld_asignado=$_POST["txtAsignado"];
	$ld_marzo=$_POST["txtMarzo"];
	$ld_junio=$_POST["txtJunio"];
	$ld_septiembre=$_POST["txtSeptiembre"];
	$ld_diciembre=$_POST["txtDiciembre"];
	$ld_asignado=str_replace('.','',$ld_asignado);
	$ld_asignado=str_replace(',','.',$ld_asignado);		
	$ld_marzo=str_replace('.','',$ld_marzo);
	$ld_marzo=str_replace(',','.',$ld_marzo);
	$ld_junio=str_replace('.','',$ld_junio);
	$ld_junio=str_replace(',','.',$ld_junio);
	$ld_septiembre=str_replace('.','',$ld_septiembre);
	$ld_septiembre=str_replace(',','.',$ld_septiembre);
	$ld_diciembre=str_replace('.','',$ld_diciembre);
	$ld_diciembre=str_replace(',','.',$ld_diciembre);
  
    $ld_total=uf_calcular_total($ld_marzo, $ld_junio, $ld_septiembre, $ld_diciembre );
	
    if ($ld_total > $ld_asignado)
    {
       $msg->message("El Total es mayor al monto asignado. Por favor revise los montos ");  
    }
    else
    {
	  $ds_prorep->data=$_SESSION["objact"];
	  $ls_modrep="3"; //Modalidad Trimestral
	  $ls_distribuir="2";
	  $ds_prorep->updateRow("asignado",$ld_asignado,$i);
	  $ds_prorep->updateRow("marzo",$ld_marzo,$i);
	  $ds_prorep->updateRow("junio",$ld_junio,$i);
	  $ds_prorep->updateRow("septiembre",$ld_septiembre,$i);
	  $ds_prorep->updateRow("diciembre",$ld_diciembre,$i);
	  $ds_prorep->updateRow("modrep",$ls_modrep,$i);		
	  $ds_prorep->updateRow("distribuir",$ls_distribuir,$i);
      ?>
	  <script language="javascript">
		close();
		opener.f.submit();
	  </script>
      <?php
   }//else
  }//aceptar
  
  function uf_calcular_total($ad_marzo, $ad_junio, $ad_septiembre, $ad_diciembre )
  {
      $ld_total =  $ad_marzo + $ad_junio + $ad_septiembre + $ad_diciembre ;
	  return $ld_total;
  }
  
  if($ls_operacion=="ACTUALIZAR")
  {
      $ds_prorep->data=$_SESSION["objact"];
	  $i=$_POST["fila"];
	  $ld_marzo=$_POST["txtMarzo"];
	  $ld_junio=$_POST["txtJunio"];
	  $ld_septiembre=$_POST["txtSeptiembre"];
	  $ld_diciembre=$_POST["txtDiciembre"];
	  $ld_asignado=$_POST["txtAsignado"];

	  $ld_asignado=str_replace('.','',$ld_asignado);
	  $ld_asignado=str_replace(',','.',$ld_asignado);		
	  $ld_marzo=str_replace('.','',$ld_marzo);
	  $ld_marzo=str_replace(',','.',$ld_marzo);
	  $ld_junio=str_replace('.','',$ld_junio);
	  $ld_junio=str_replace(',','.',$ld_junio);
	  $ld_septiembre=str_replace('.','',$ld_septiembre);
	  $ld_septiembre=str_replace(',','.',$ld_septiembre);
	  $ld_diciembre=str_replace('.','',$ld_diciembre);
	  $ld_diciembre=str_replace(',','.',$ld_diciembre);
	  
	  $ld_total=uf_calcular_total($ld_marzo,$ld_junio,$ld_septiembre,$ld_diciembre);
	  if ($ld_total > $ld_asignado)
      {
        $msg->message("El Total es mayor al monto asignado. Por favor revise los montos ");  
     }
	  $ld_total=number_format($ld_total,2,",",".");
	  $ld_asignado=number_format($ld_asignado,2,",",".");
  }
  
?>
      <td class="fd-blanco"><div align="right">Total</div></td>
      <td><input name="txtTotal" type="text" class="fd-blanco" id="txtTotal"   onKeyPress="return(currencyFormat(this,'.',',',event));"value="<?php print $ld_total?>" readonly></td>
      <td colspan="3" rowspan="4"><input name="botAceptar" type="button" class="boton" id="botAceptar2" onClick="ue_aceptar()" value="Aceptar">
          <input name="botCancelar" type="button" class="boton" id="botCancelar" onClick="ue_cancelar()" value="Cancelar">
          <input name="operacion" type="hidden" id="operacion" value="<?php print $ls_operacion?>">
          <input name="fila" type="hidden" id="fila" value="<?php print $i?>"></td>
    </tr>
    <tr>
      <td class="fd-blanco Estilo1">&nbsp;</td>
      <td><span class="Estilo1"></span></td>
    </tr>
    <tr>
      <td class="fd-blanco"><div align="right">Asignado</div></td>
      <td><input name="txtAsignado" type="text" class="fd-blanco" id="txtAsignado"   onKeyPress="return(currencyFormat(this,'.',',',event));"value="<?php print $ld_asignado ?>" readonly></td>
    </tr>
    <tr>
      <td class="fd-blanco">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <p align="left">&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
 
</form>
<?php
//print "Total=".$ds_prorep->getRowCount("cod_report");
$_SESSION["objact"]=$ds_prorep->data;
?>
<script language="javascript">

function ue_aceptar()
{
	f=document.form1;
	ld_asignado=f.txtAsignado.value  
	ld_asignado=parseFloat(uf_convertir_monto(ld_asignado));
	ld_m3=f.txtMarzo.value;    
	ld_m3=parseFloat(uf_convertir_monto(ld_m3));
	ld_m6=f.txtJunio.value;
	ld_m6=parseFloat(uf_convertir_monto(ld_m6));
	ld_m9=f.txtSeptiembre.value;           
	ld_m9=parseFloat(uf_convertir_monto(ld_m9));
	ld_m12=f.txtDiciembre.value;       
	ld_m12=parseFloat(uf_convertir_monto(ld_m12));
	ld_total = parseFloat( ld_m3 +  ld_m6  + ld_m9 + ld_m12);
	if (ld_total!=ld_asignado)
	{
	  alert(" La Distribución no cuadra con lo asignado. Por favor revise los montos ");
	}
	else
	{
		f.operacion.value="ACEPTAR";
		f.txtAsignado.value;
		f.action="sigesp_scg_wdist_manual_trim.php";
		f.submit();
	}
}

function ue_cancelar()
{
	f=document.form1;
	alert("El Proceso se cancelara.....Esta de Acuerdo");
	close();
}

function uf_actualizar(obj)
{
		f=document.form1;
		if(obj.value=="")
		{
		  obj.value="0.0000";
		}
		ld_asignado=f.txtAsignado.value  
		ld_asignado=parseFloat(uf_convertir_monto(ld_asignado));
 
		ld_m3=f.txtMarzo.value;    
		ld_m3=parseFloat(uf_convertir_monto(ld_m3));
		
		ld_m6=f.txtJunio.value;
		ld_m6=parseFloat(uf_convertir_monto(ld_m6));
	
		ld_m9=f.txtSeptiembre.value;           
		ld_m9=parseFloat(uf_convertir_monto(ld_m9));
	
		ld_m12=f.txtDiciembre.value;       
		ld_m12=parseFloat(uf_convertir_monto(ld_m12));
	
		ld_total = parseFloat( ld_m3 + ld_m6 + ld_m9 + ld_m12);
		ld_total=redondear(ld_total,2);
		if (ld_total>ld_asignado)
		{
		  alert(" El Total es mayor al monto asignado. Por favor revise los montos ");
		}	
		f.txtTotal.value=ld_total;
		ld_total=uf_convertir(f.txtTotal.value);
		f.txtTotal.value=ld_total;
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