<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Solicitudes de Pago </title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
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
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
    <input name="hidsubgrupo" type="hidden" id="hidsubgrupo" value="<?php print $ls_codsubgru ?>">
    <input name="hidstatus" type="hidden" id="hidstatus">
    <input name="hidgrupo" type="hidden" id="hidgrupo" value="<?php print $ls_codgru ?>">
</p>
  <table width="550" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" height="15" colspan="2" class="titulo-celda">Cat&aacute;logo de Solicitudes de Pago </td>
    </tr>
  </table>
<br>
    <table width="550" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="131" height="18"><div align="right">No. de la Solicitud </div></td>
        <td width="417" height="22"><div align="left">
          <input name="txtnumsol" type="text" id="txtnumsol">
        </div>          <div align="right"></div>          <div align="right">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Proveedor</div></td>
        <td height="22"><div align="left">          <input name="txtcodpro" type="text" id="txtcodpro">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Buscar</a></div></td>
      </tr>
    </table>
  <br>
    <?php
require_once("../shared/class_folder/sigesp_include.php");
$in=     new sigesp_include();
$con=$in->uf_conectar();
require_once("../shared/class_folder/class_mensajes.php");
$io_msg= new class_mensajes();
require_once("../shared/class_folder/class_datastore.php");
$ds=     new class_datastore();
require_once("../shared/class_folder/class_sql.php");
$io_sql= new class_sql($con);
require_once("../shared/class_folder/class_funciones.php");
$io_fun= new class_funciones();
$arr=$_SESSION["la_empresa"];
$ls_codemp=$arr["codemp"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_numsol="%".$_POST["txtnumsol"]."%";
	$ls_codpro="%".$_POST["txtcodpro"]."%";
	
}
else
{
	$ls_operacion="";
}
print "<table width=550 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td width='50'>Número </td>";
print "<td width='60'>Fecha</td>";
print "<td width='240'>Proveedor</td>";
print "<td width='90'>Monto</td>";
print "</tr>";

if($ls_operacion=="BUSCAR")
{
	$ls_sql="SELECT cxp_solicitudes.*,".
			"(SELECT nompro FROM rpc_proveedor WHERE cxp_solicitudes.cod_pro=rpc_proveedor.cod_pro) as denpro".
			" FROM cxp_solicitudes".
			" WHERE cxp_solicitudes.codemp='".$ls_codemp."'".
			" AND cxp_solicitudes.numsol LIKE '".$ls_numsol."'".
			" AND cxp_solicitudes.cod_pro LIKE '".$ls_codpro."'".
			" ORDER BY cxp_solicitudes.numsol";		
	$rs_cta=$io_sql->select($ls_sql);
	$data=$rs_cta;
	if($row=$io_sql->fetch_row($rs_cta))
	{
		$data=$io_sql->obtener_datos($rs_cta);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds->data=$data;

		$totrow=$ds->getRowCount("numsol");
	
		for($z=1;$z<=$totrow;$z++)
		{
			print "<tr class=celdas-blancas>";
			$ls_numsol= $data["numsol"][$z];
			$ls_codpro=    $data["cod_pro"][$z];
			$ls_denpro=    $data["denpro"][$z];
			$ld_fecemisol= $data["fecemisol"][$z];
			$li_monsol=    $data["monsol"][$z];
			$ld_fecemisol=$io_fun->uf_convertirfecmostrar($ld_fecemisol);
			$li_monsol=number_format($li_monsol,2,',','.');
			print " <td align='center'><a href=\"javascript: aceptar('$ls_numsol','$ls_codpro','$ls_denpro','$ld_fecemisol','$li_monsol');\">".$ls_numsol."</a></td>";
			print "<td align='center'>".$ld_fecemisol."</td>";
			print "<td align='left'>".$ls_denpro."</td>";
			print "<td align='right'>".$li_monsol."</td>";
			print "</tr>";			
		}
	}
}
print "</table>";

?>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">   
	function aceptar(ls_numsol,ls_codpro,ls_denpro,ld_fecemisol,li_monsol)
	{
		opener.document.form1.txtnumsolpag.value=ls_numsol;
		opener.document.form1.txtfecemisol.value=ld_fecemisol;
		close();
	}
  
	function ue_search()
	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		f.action="sigesp_saf_cat_solicitudpago.php";
		f.submit();
	}
  
////////////////////////    Validar la Fecha     ///////////////////////////
function valSep(oTxt){ 
    var bOk = false; 
    var sep1 = oTxt.value.charAt(2); 
    var sep2 = oTxt.value.charAt(5); 
    bOk = bOk || ((sep1 == "-") && (sep2 == "-")); 
    bOk = bOk || ((sep1 == "/") && (sep2 == "/")); 
    return bOk; 
   } 

   function finMes(oTxt){ 
    var nMes = parseInt(oTxt.value.substr(3, 2), 10); 
    var nAno = parseInt(oTxt.value.substr(6), 10); 
    var nRes = 0; 
    switch (nMes){ 
     case 1: nRes = 31; break; 
     case 2: nRes = 28; break; 
     case 3: nRes = 31; break; 
     case 4: nRes = 30; break; 
     case 5: nRes = 31; break; 
     case 6: nRes = 30; break; 
     case 7: nRes = 31; break; 
     case 8: nRes = 31; break; 
     case 9: nRes = 30; break; 
     case 10: nRes = 31; break; 
     case 11: nRes = 30; break; 
     case 12: nRes = 31; break; 
    } 
    return nRes + (((nMes == 2) && (nAno % 4) == 0)? 1: 0); 
   } 

   function valDia(oTxt){ 
    var bOk = false; 
    var nDia = parseInt(oTxt.value.substr(0, 2), 10); 
    bOk = bOk || ((nDia >= 1) && (nDia <= finMes(oTxt))); 
    return bOk; 
   } 

   function valMes(oTxt){ 
    var bOk = false; 
    var nMes = parseInt(oTxt.value.substr(3, 2), 10); 
    bOk = bOk || ((nMes >= 1) && (nMes <= 12)); 
    return bOk; 
   } 

   function valAno(oTxt){ 
    var bOk = true; 
    var nAno = oTxt.value.substr(6); 
    bOk = bOk && ((nAno.length == 2) || (nAno.length == 4)); 
    if (bOk){ 
     for (var i = 0; i < nAno.length; i++){ 
      bOk = bOk && esDigito(nAno.charAt(i)); 
     } 
    } 
    return bOk; 
   } 

   function valFecha(oTxt){ 
    var bOk = true; 
	
		if (oTxt.value != ""){ 
		 bOk = bOk && (valAno(oTxt)); 
		 bOk = bOk && (valMes(oTxt)); 
		 bOk = bOk && (valDia(oTxt)); 
		 bOk = bOk && (valSep(oTxt)); 
		 if (!bOk){ 
		  alert("Fecha inválida ,verifique el formato(Ejemplo: 10/10/2005) \n o introduzca una fecha correcta."); 
		  oTxt.value = "01/01/2005"; 
		  oTxt.focus(); 
		 } 
		}
	 
   }

  function esDigito(sChr){ 
    var sCod = sChr.charCodeAt(0); 
    return ((sCod > 47) && (sCod < 58)); 
   }

////////////////////////    Validar la Fecha     ///////////////////////////
var patron = new Array(2,2,4)
var patron2 = new Array(1,3,3,3,3)
function ue_separadores(d,sep,pat,nums){
if(d.valant != d.value){
	val = d.value
	largo = val.length
	val = val.split(sep)
	val2 = ''
	for(r=0;r<val.length;r++){
		val2 += val[r]	
	}
	if(nums){
		for(z=0;z<val2.length;z++){
			if(isNaN(val2.charAt(z))){
				letra = new RegExp(val2.charAt(z),"g")
				val2 = val2.replace(letra,"")
			}
		}
	}
	val = ''
	val3 = new Array()
	for(s=0; s<pat.length; s++){
		val3[s] = val2.substring(0,pat[s])
		val2 = val2.substr(pat[s])
	}
	for(q=0;q<val3.length; q++){
		if(q ==0){
			val = val3[q]
		}
		else{
			if(val3[q] != ""){
				val += sep + val3[q]
				}
		}
	}
	d.value = val
	d.valant = val
	}
}
function ue_limpiar(periodo)
{
	f=document.form1;
	if(periodo=="Desde")
	{
		f.txtdesde.value="";
	}
	else
	{
		if(periodo=="Hasta")
		{
			f.txthasta.value="";
		}
	}
	
}

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
