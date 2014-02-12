<?php
session_start();
if(array_key_exists("codact",$_GET))
{
	$ls_codact=$_GET["codact"];
	$ls_ideact=$_GET["ideact"];
}
else
{
	$ls_codact=$_POST["hidcodact"];
	$ls_ideact=$_POST["hidideact"];
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Partes</title>
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
    <input name="hidcodact" type="hidden" id="hidcodact">
    <input name="hidideact" type="hidden" id="hidideact">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Partes </td>
    </tr>
  </table>
<br>
    <?php
require_once("../shared/class_folder/sigesp_include.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
require_once("../shared/class_folder/class_datastore.php");
$ds=new class_datastore();
require_once("../shared/class_folder/class_sql.php");
$io_sql=new class_sql($con);
require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
require_once("../shared/class_folder/class_funciones.php");
$io_fun= new class_funciones();
$arr=$_SESSION["la_empresa"];
$ls_codemp=$arr["codemp"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codpar="%".$_POST["txtcodact"]."%";
	$ls_denpar="%".$_POST["txtdenpar"]."%";
	$ls_maract="%".$_POST["txtmaract"]."%";
	$ls_modact="%".$_POST["txtmodact"]."%";
	$ls_status="%".$_POST["hidstatus"]."%";
	
}
else
{
	$ls_operacion="BUSCAR";
	$ls_codpar="%%";
	$ls_denpar="%%";
}
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td width='120' align='center'>Codigo </td>";
print "<td >Denominación</td>";
print "</tr>";

if($ls_operacion=="BUSCAR")
{
	$ls_sql="SELECT * FROM saf_partes".
			" WHERE codemp='".$ls_codemp."'". 
			" AND codact= '".$ls_codact."'".
			" AND ideact='".$ls_ideact."'".
			" AND codpar like '".$ls_codpar."' ".
			" AND denpar like '".$ls_denpar."'";
	$rs_cta=$io_sql->select($ls_sql);
	$data=$rs_cta;
	if($row=$io_sql->fetch_row($rs_cta))
	{
		$data=$io_sql->obtener_datos($rs_cta);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds->data=$data;

		$totrow=$ds->getRowCount("codpar");
	
		for($z=1;$z<=$totrow;$z++)
		{
			print "<tr class=celdas-blancas>";
			$ls_codpar= $data["codpar"][$z];
			$ls_denpar=$data["denpar"][$z];

			print " <td><a href=\"javascript: aceptar('$ls_codpar','$ls_denpar');\">".$ls_codpar."</a></td>";
			print "<td>".$data["denpar"][$z]."</td>";
			print "</tr>";			
		}
	}
	else
	{
		$io_msg->message("No hay registros");
	}
}
print "</table>";

function uf_validar_fecha ($ls_desde,$ls_hasta)
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_validar_fecha
	//	Access:    public
	//	Arguments:
	//  			  ls_desde    // fecha de inicio de un periodo
	//  			  ls_hasta    // fecha de cierre de un periodo
	//
	//	Returns:	  $lb_valido-----> true: encontrado false: no encontrado
	//	Description:  Funcion que valida que las fechas de un periodo no esten solapadas
	//              
	//////////////////////////////////////////////////////////////////////////////		

	$ls_fechavalida=false;
	$io_msg= new class_mensajes();
	
	if(($ls_desde=="")and($ls_hasta==""))
	{
		$ls_fechavalida=false;	
	}
	else
	{
		if($ls_hasta < $ls_desde)
		{
			
			$io_msg->message("Debe introducir un periodo de tiempo valido ");	
		}
		else
		$ls_fechavalida=true;
	}
	return $ls_fechavalida;
}
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
  function aceptar(codact,denact,seract,ideact)
  {

	opener.document.form1.txtcodpar.value=codact;
	opener.document.form1.txtdenpar.value=denact;
	close();
  }
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_saf_cat_partes.php";
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
