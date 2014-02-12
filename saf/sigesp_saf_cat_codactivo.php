<?php
session_start();
require_once("class_funciones_activos.php");
$io_fac= new class_funciones_activos();
if(array_key_exists("hidestact",$_POST))
{
	$ls_estact=$_POST["hidestact"];
}
else
{
	$ls_estact=$io_fac->uf_obtenervalor_get("estact","");
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
  <p align="center">&nbsp;</p>
  <br>
    <table width="600" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
          <tr class="titulo-celda">
            <td height="22" colspan="3"><input name="hidsubgrupo" type="hidden" id="hidsubgrupo" value="<?php print $ls_codsubgru ?>">
              <input name="operacion" type="hidden" id="operacion">
            Cat&aacute;logo de Activos 
            <input name="hidstatus" type="hidden" id="hidstatus">
            <input name="hidgrupo" type="hidden" id="hidgrupo" value="<?php print $ls_codgru ?>"></td>
          </tr>
          <tr>
            <td height="13">&nbsp;</td>
           <td height="13">&nbsp;</td>
            <td width="182">&nbsp;</td>
          </tr>
      <tr>
        <td width="111" height="18" style="text-align:right">C&oacute;digo</td>
        <td width="305" height="22"><input name="txtcodact" type="text" id="txtcodact"></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td style="text-align:right">Denominaci&oacute;n</td>
        <td height="22" colspan="2" style="text-align:left"><input name="txtdenact" type="text" id="txtdenact" size="80"></td>
      </tr>
      <tr>
        <td style="text-align:right">Marca</td>
        <td height="22" colspan="2"><input name="txtmaract" type="text" id="txtmaract" size="80"></td>
      </tr>
      <tr>
        <td style="text-align:right">Modelo</td>
        <td height="22" colspan="2"><input name="txtmodact" type="text" id="txtmodact" size="80"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="4" style="text-align:right"><a href="javascript: ue_search();">
          <input name="hidestact" type="hidden" id="hidestact" value="<?php print $ls_estact ?>">
          <img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Buscar</a></td>
      </tr>
  </table>
    <p align="center">
<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
$in=     new sigesp_include();
$con=$in->uf_conectar();

$io_msg= new class_mensajes();

$io_sql= new class_sql($con);
require_once("../shared/class_folder/class_funciones.php");
$io_fun= new class_funciones();
require_once("class_funciones_activos.php");
$ls_codemp=$_SESSION["la_empresa"]["codemp"];
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
}
if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion = $_POST["operacion"];
	 $ls_codact    = "%".$_POST["txtcodact"]."%";
	 $ls_denact    = "%".$_POST["txtdenact"]."%";
	 $ls_maract    = "%".$_POST["txtmaract"]."%";
	 $ls_modact    = "%".$_POST["txtmodact"]."%";
	 $ls_status    = "%".$_POST["hidstatus"]."%";
   }
else
   {
	 $ls_operacion="";
   }
   
echo "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
echo "<tr class=titulo-celda>";
echo "<td style=text-align:center width=50>C&oacute;digo</td>";
echo "<td style=text-align:center width=270>Denominaci&oacute;n</td>";
echo "<td style=text-align:center width=60>Serial</td>";
echo "<td style=text-align:center width=60>Identificaci&oacute;n</td>";
echo "<td style=text-align:center width=80>Marca</td>";
echo "<td style=text-align:center width=80>Modelo</td>";
echo "</tr>";

if ($ls_operacion=="BUSCAR")
   {
	 $ls_sqlaux = "";
	 $ls_gestor = $_SESSION["ls_gestor"];
	 if ($ls_gestor=='MYSQLT')
	    {
		  $ls_sqlaux = ",CONCAT(sno_personal.nomper,' ',sno_personal.apeper) as nomrespri, 
		                CONCAT(rpc_beneficiario.nombene,' ',rpc_beneficiario.apebene) as respri";
		}
	 elseif($ls_gestor=='POSTGRES')
	    {
		  $ls_sqlaux = ",sno_personal.nomper||' '||sno_personal.apeper as nomrespri, 
		                rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene as respri";
		}
	 $ls_sql="SELECT saf_activo.codact,saf_activo.denact,saf_activo.maract,saf_activo.modact, saf_dta.codres, saf_activo.costo,
	                 saf_dta.ideact, saf_dta.seract, saf_dta.coduniadm, spg_unidadadministrativa.denuniadm $ls_sqlaux
			    FROM saf_activo, saf_dta
	            LEFT OUTER JOIN spg_unidadadministrativa 
			      ON spg_unidadadministrativa.coduniadm = saf_dta.coduniadm
			    LEFT OUTER JOIN sno_personal 
				  ON sno_personal.codper = saf_dta.codres
                LEFT OUTER JOIN rpc_beneficiario 
				  ON rpc_beneficiario.ced_bene = saf_dta.codres
			   WHERE saf_activo.codact like '".$ls_codact."'
			     AND saf_activo.denact like '".$ls_denact."'
			     AND saf_activo.maract like '".$ls_maract."'
			     AND saf_activo.modact like '".$ls_modact."' $ls_sqlest
				 AND saf_activo.codact = saf_dta.codact";//print $ls_sql.'<br>';
	 $rs_data = $io_sql->select($ls_sql);
	 if ($rs_data===false)
	    {
		  $io_msg->message("Error en Consulta, Contacte al Administrador del Sistema !!!");
		}
     else
	    {
		   $li_totrows = $io_sql->num_rows($rs_data);
		   if ($li_totrows>0)
		      {
			    while(!$rs_data->EOF)
				     {
					   echo "<tr class=celdas-blancas>";
			           $ls_codact = $rs_data->fields["codact"];
					   $ls_denact = $rs_data->fields["denact"];
			           $ls_seract = $rs_data->fields["seract"];
					   $ls_maract = $rs_data->fields["maract"];
					   $ls_ideact = $rs_data->fields["ideact"];
					   $ls_modact = $rs_data->fields["modact"];
					   $ld_cosact = number_format($rs_data->fields["costo"],2,',','.');
					   
					   $ls_coduniadm = $rs_data->fields["coduniadm"];
					   $ls_denuniadm = $rs_data->fields["denuniadm"];
					   $ls_codres    = $rs_data->fields["codres"];
					   $ls_nomrespri = $rs_data->fields["nomrespri"];					   
					   if (empty($ls_nomrespri))
					      {
						    $ls_nomrespri = $rs_data->fields["respri"];
 						  }					   
					   echo "<td style=text-align:center width=50><a href=\"javascript: aceptar('$ls_codact','$ls_denact','$ls_seract','$ls_ideact','$ls_coduniadm','$ls_denuniadm','$ls_codres','$ls_nomrespri','$ld_cosact');\">".$ls_codact."</a></td>";
				       echo "<td style=text-align:left   width=270 title='".$ls_denact."'>".$ls_denact."</td>";
					   echo "<td style=text-align:center width=60>".$ls_seract."</td>";
					   echo "<td style=text-align:center width=60>".$ls_ideact."</td>";
					   echo "<td style=text-align:left   width=80 title='".$ls_maract."'>".$ls_maract."</td>";
					   echo "<td style=text-align:left   width=80 title='".$ls_modact."'>".$ls_modact."</td>";	
				       echo "</tr>";
                       $rs_data->MoveNext();
					 }
			  }
		   else
		      {
			    $io_msg->message("No se encontraron Activos !!!");
			  }
		 }  		 
   }
echo "</table>";

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
   	// Funci&oacute;n que le da formato a los valore num&eacute;ricos que vienen de la BD
	// parametro de entrada = Valor n&uacute;merico que se desa formatear
	// parametro de retorno = valor num&eacute;rico formateado
   	function uf_formatonumerico($as_valor)
   	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_validar_fecha
	//	Access:    public
	//	Arguments:
	//  			  as_valor    // Valor n&uacute;merico que se desa formatear
	//
	//	Returns:	  $lb_valido-----> true: encontrado false: no encontrado
	//	Description:  Funci&oacute;n que le da formato a los valore num&eacute;ricos que vienen de la BD
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
  </p>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">  
fop = opener.document.form1;
function aceptar(codact,denact,seract,ideact,as_coduniadm,as_denuniadm,as_codres,as_nomres,ad_cosact)
{
  fop.txtcodact.value=codact;
  fop.txtdenact.value=denact;
  fop.txtseract.value=seract;
  fop.txtideact.value=ideact;
  ls_opener = fop.id;
  if (ls_opener=="sigesp_saf_pdt_reasignacionact.php" || ls_opener=="sigesp_saf_pdt_traslado.php")
     {
	   fop.txtcoduniadm.value = as_coduniadm;
	   fop.txtdenuniadm.value = as_denuniadm;
	   fop.txtcodresp.value   = as_codres;
       fop.txtnomres.value    = as_nomres;
  	   fop.txtmonact.value    = ad_cosact;
	 }
  //fop.txtcosact.value    = ad_cosact;
  fop.operacion.value="BUSCARACTIVO";
  fop.submit();
  close();
}

function ue_search()
{
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_saf_cat_codactivo.php";
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
