<?php
session_start();
require_once("class_funciones_activos.php");
$io_fac= new class_funciones_activos();
if(array_key_exists("hidestact",$_POST))
{
	$ls_estact=$_POST["hidestact"];
	$ls_coddestino=$_POST["coddestino"];
	$ls_dendestino=$_POST["dendestino"];
}
else
{
	$ls_estact=$io_fac->uf_obtenervalor_get("estact","");
	$ls_coddestino=$io_fac->uf_obtenervalor_get("coddestino","txtcodact");
	$ls_dendestino=$io_fac->uf_obtenervalor_get("dendestino","txtdenact");
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Activos </title>
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
    <input name="hidstatus" type="hidden" id="hidstatus">
</p>
  <table width="603" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="599" colspan="2" class="titulo-celda">Cat&aacute;logo de Activos </td>
    </tr>
  </table>
<br>
    <table width="600" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="85" height="18"><div align="right">C&oacute;digo</div></td>
        <td width="182" height="22"><div align="left">
          <input name="txtcodact" type="text" id="txtcodact">
        </div></td>
        <td colspan="2" rowspan="4"><div align="right"></div>          <div align="right">
          </div></td>
        <td><input name="dendestino" type="hidden" id="dendestino" value="<?php print $ls_dendestino ?>">
        <input name="coddestino" type="hidden" id="coddestino" value="<?php print $ls_coddestino ?>"></td>
      </tr>
      <tr>
        <td><div align="right">Denominaci&oacute;n</div></td>
        <td height="22"><div align="left">          <input name="txtdenact" type="text" id="txtdenact">
        </div></td>
        <td width="116">&nbsp;</td>
      </tr>
      <tr>
        <td><div align="right">Marca </div></td>
        <td height="22"><div align="left">
            <input name="txtmaract" type="text" id="txtmaract">
        </div></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><div align="right">Modelo</div></td>
        <td height="22"><div align="left">
          <input name="txtmodact" type="text" id="txtmodact">
        </div></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="4"><div align="right"><a href="javascript: ue_search();">
          <input name="hidestact" type="hidden" id="hidestact" value="<?php print $ls_estact ?>">
          <img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Buscar</a></div></td>
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
$ls_sqlest="";
if($ls_estact!="")
{
	$li_cant=strlen($ls_estact);
	if($li_cant>1)
	{
		$ls_estact1=substr($ls_estact,0,1);
		$ls_estact2=substr($ls_estact,1,1);
		$ls_sqlest=" AND (saf_dta.estact='".$ls_estact1."'".
				   " OR saf_dta.estact='".$ls_estact2."'";
		if($li_cant==3)
		{
			$ls_estact3=substr($ls_estact,2,1);
			$ls_sqlest=$ls_sqlest." OR saf_dta.estact='".$ls_estact3."'";
		}
		$ls_sqlest=$ls_sqlest.")";
	}
	else
	{
		$ls_sqlest=" AND saf_dta.estact='".$ls_estact."'";
	}
		//print $ls_estact1."--".$ls_estact2."--".$ls_estact3;
}

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codact="%".$_POST["txtcodact"]."%";
	$ls_denact="%".$_POST["txtdenact"]."%";
	$ls_maract="%".$_POST["txtmaract"]."%";
	$ls_modact="%".$_POST["txtmodact"]."%";
	$ls_status="%".$_POST["hidstatus"]."%";
	
}
else
{
	$ls_operacion="";

}
print "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td width='50'>Codigo </td>";
print "<td width='60'>Serial</td>";
print "<td width='60'>Identificación</td>";
print "<td width='270'>Denominacion</td>";
print "<td>Marca</td>";
print "<td>Modelo</td>";
print "</tr>";

if($ls_operacion=="BUSCAR")
{
	$ls_sql="SELECT saf_activo.*, saf_dta.ideact, saf_dta.seract".
			"  FROM saf_activo,saf_dta".
			" WHERE saf_activo.codact=saf_dta.codact". 
			"   AND saf_activo.codact like '".$ls_codact."'".
			"   AND saf_activo.denact like '".$ls_denact."'".
			"   AND saf_activo.maract like '".$ls_maract."' ".
			"   AND saf_activo.modact like '".$ls_modact."'".
			$ls_sqlest;

	$rs_cta=$io_sql->select($ls_sql);
	$data=$rs_cta;
	if($row=$io_sql->fetch_row($rs_cta))
	{
		$data=$io_sql->obtener_datos($rs_cta);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds->data=$data;

		$totrow=$ds->getRowCount("codgru");
	
		for($z=1;$z<=$totrow;$z++)
		{
			print "<tr class=celdas-blancas>";
			$ls_codact= $data["codact"][$z];
			$ls_seract=$data["seract"][$z];
			$ls_ideact=$data["ideact"][$z];
			$ls_denact= $data["denact"][$z];
			$ls_maract= $data["maract"][$z];
			$ls_modact= $data["modact"][$z];

			print " <td><a href=\"javascript: aceptar('$ls_codact','$ls_denact','$ls_seract','$ls_ideact','$ls_coddestino','$ls_dendestino');\">".$ls_codact."</a></td>";

			print "<td>".$data["seract"][$z]."</td>";
			print "<td>".$data["ideact"][$z]."</td>";
			print "<td>".$data["denact"][$z]."</td>";
			print "<td>".$data["maract"][$z]."</td>";
			print "<td>".$data["modact"][$z]."</td>";
			print "</tr>";			
		}
	}
}
print "</table>";

	//-----------------------------------------------------------------------------------------------------------------------------------
   	// Función que le da formato a los valore numéricos que vienen de la BD
	// parametro de entrada = Valor númerico que se desa formatear
	// parametro de retorno = valor numérico formateado
   	function uf_formatonumerico($as_valor)
   	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_validar_fecha
	//	Access:    public
	//	Arguments:
	//  			  as_valor    // Valor númerico que se desa formatear
	//
	//	Returns:	  $lb_valido-----> true: encontrado false: no encontrado
	//	Description:  Función que le da formato a los valore numéricos que vienen de la BD
	//              
	//////////////////////////////////////////////////////////////////////////////		
		$li_poscoma = stripos($as_valor, ",");
		$li_contador = 0;
		if ($li_poscoma==0)
		{
			$li_poscoma = strlen($as_valor);
			$as_valor = $as_valor.",00";
		}
		for($li_index=$li_poscoma;$li_index>=0;--$li_index)
		{
			if(($li_contador==3)&&(($li_index-1)>0)) 
			{
				$as_valor = substr($as_valor,0,$li_index).".".substr($as_valor,$li_index);
				$li_contador=1;
			}
			else
			{
				$li_contador=$li_contador + 1;
			}
		}
		return $as_valor;
	}

?>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">  
  function aceptar(codact,denact,seract,ideact,coddestino,dendestino)
  {
	obj=eval("opener.document.form1."+coddestino+"");
	obj.value=codact;
	obj=eval("opener.document.form1."+dendestino+"");
	obj.value=denact;
	opener.document.form1.txtseract.value=seract;
	opener.document.form1.txtideact.value=ideact;
	//opener.document.form1.operacion.value="BUSCARACTIVO";
	//opener.document.form1.submit();
	close();
  }
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_saf_cat_codactivoss.php";
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
