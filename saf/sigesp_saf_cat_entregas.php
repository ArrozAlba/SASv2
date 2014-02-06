<?php
session_start();
require_once("class_funciones_activos.php");
$io_fact= new class_funciones_activos("../");
if(array_key_exists("coddestino",$_POST))
{
	$ls_coddestino=$_POST["coddestino"];
	$ls_tipo=$_POST["tipo"];
}
else
{
	$ls_coddestino=$io_fact->uf_obtenervalor_get("coddestino","");
	$ls_tipo=$io_fact->uf_obtenervalor_get("tipo","");
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Entregas</title>
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
    <input name="operacion"  type="hidden" id="operacion">
    <input name="hidstatus"  type="hidden" id="hidstatus">
    <input name="coddestino" type="hidden" id="coddestino" value="<?php print $ls_coddestino ?>">
    <input name="tipo"       type="hidden" id="tipo"       value="<?php print $ls_tipo ?>">
  </p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Entregas</td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
        <tr>
          <td>&nbsp;</td>
          <td height="18">&nbsp;</td>
          <td width="148" rowspan="3"><table width="145" border="0" cellspacing="0" class="formato-blanco">
            <tr>
              <td colspan="2"><div align="center" class="titulo-conect">Rango de Fechas </div></td>
            </tr>
            <tr>
              <td width="36"><div align="right">Desde</div></td>
              <td width="103" height="22"><input name="txtdesde" type="text" id="txtdesde" size="15"  datepicker="true"  onKeyPress="ue_separadores(this,'/',patron,true);"></td>
            </tr>
            <tr>
              <td><div align="right">Hasta</div></td>
              <td height="22"><input name="txthasta" type="text" id="txthasta" size="15"  datepicker="true"  onKeyPress="ue_separadores(this,'/',patron,true);"></td>
            </tr>
          </table></td>
          <td>&nbsp;</td>
        </tr>
      <tr>
        <td width="120"><div align="right">Comprobante</div></td>
        <td width="162" height="22"><div align="left">
          <input name="txtcmpent" type="text" id="txtcmpent">
        </div></td>
        <td width="68">&nbsp;</td>
      </tr>
      <tr>
        <td><div align="right"></div></td>
        <td height="22"><div align="left"></div></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="3"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Buscar</a></div></td>
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
$arre=$_SESSION["la_empresa"];
$ls_codemp=$arre["codemp"];
$ls_gestor = $_SESSION["ls_gestor"];
require_once("sigesp_saf_c_activo.php");
$io_saf = new sigesp_saf_c_activo();
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_cmpent="%".$_POST["txtcmpent"]."%";
	$ld_fecdes="%".$_POST["txtdesde"]."%";
	$ld_fechas="%".$_POST["txthasta"]."%";
	if(($ld_fecdes=="%%")||($ld_fechas=="%%"))
	{
		$ld_fecdes="";
		$ld_fechas="";
	}
	else
	{
		$ld_fecdes=substr($ld_fecdes,1,10);
		$ld_fechas=substr($ld_fechas,1,10);
			
		$ld_fecdes=$io_fun->uf_convertirdatetobd($ld_fecdes);
		$ld_fechas=$io_fun->uf_convertirdatetobd($ld_fechas);
	}
}
else
{
	$ls_operacion="";
}
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td width='75'>Comprobante </td>";
print "<td width='75'>Fecha de Comprobante</td>";
print "<td width='75'>Fecha de Entrega</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	if(strtoupper($ls_gestor)=="MYSQL")
	{
	   $ls_cadena_personal="CONCAT(sno_personal.nomper,' ',sno_personal.apeper)";
	}
	else
	{
	   $ls_cadena_personal="sno_personal.nomper||' '||sno_personal.apeper";
	}
	if(strtoupper($ls_gestor)=="MYSQL")
	{
	   $ls_cadena_beneficiario="CONCAT(rpc_beneficiario.nombene,' ',rpc_beneficiario.apebene)";
	}
	else
	{
	   $ls_cadena_beneficiario="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
	}
	$ls_estcat=$io_saf->uf_select_valor_config($ls_codemp);
	if(($ld_fecdes!="")&&($ld_fechas!=""))
	{
		$ls_sqlint=" AND saf_entrega.feccmp >= '". $ld_fecdes ." 00:00:00'".
				   " AND saf_entrega.feccmp <= '". $ld_fechas ." 23:59:59' ";
	}
	else
	{
		$ls_sqlint="";
	}
	$ls_sql="SELECT saf_entrega.*, ".
			"       (CASE tipres    WHEN 'P' THEN (SELECT ".$ls_cadena_personal." ".
			"								       FROM   sno_personal                                        ".
			"								       WHERE  sno_personal.codemp=saf_entrega.codemp AND       ".
			"										      sno_personal.codper=saf_entrega.codres)       ".
			"				        WHEN 'B' THEN (SELECT ".$ls_cadena_beneficiario." ".
			"								       FROM   rpc_beneficiario ".
			"								       WHERE  rpc_beneficiario.codemp=saf_entrega.codemp AND    ".      
			"										      rpc_beneficiario.ced_bene=saf_entrega.codres)  ".
			"	    END) AS nomres, ".
			"       (CASE tiprec WHEN 'P' THEN (SELECT ".$ls_cadena_personal."  ".
			"								       FROM   sno_personal   ".
			"								       WHERE  sno_personal.codemp=saf_entrega.codemp AND        ".
			"										      sno_personal.codper=saf_entrega.codrec)        ".
			"			  	        WHEN 'B' THEN (SELECT ".$ls_cadena_beneficiario." ".
			"								       FROM   rpc_beneficiario ".
			"								       WHERE  rpc_beneficiario.codemp=saf_entrega.codemp AND   ".
			"										      rpc_beneficiario.ced_bene=saf_entrega.codrec) ".
			"	     END) AS nomrec,  ".
			"       (CASE tipdes WHEN 'P' THEN (SELECT ".$ls_cadena_personal."  ".
			"								       FROM   sno_personal   ".
			"								       WHERE  sno_personal.codemp=saf_entrega.codemp AND        ".
			"										      sno_personal.codper=saf_entrega.coddes)        ".
			"			  	        WHEN 'B' THEN (SELECT ".$ls_cadena_beneficiario." ".
			"								       FROM   rpc_beneficiario ".
			"								       WHERE  rpc_beneficiario.codemp=saf_entrega.codemp AND   ".
			"										      rpc_beneficiario.ced_bene=saf_entrega.coddes) ".
			"	     END) AS nomdes,  ".
	        "       (SELECT denuniadm  ".
            "        FROM   spg_unidadadministrativa ".
            "        WHERE  spg_unidadadministrativa.coduniadm=saf_entrega.coduniadm) as denuniadm,".
			"       (SELECT denuniadm  ".
            "        FROM   spg_unidadadministrativa ".
            "        WHERE  spg_unidadadministrativa.coduniadm=saf_entrega.codunisol) as denunisol".
	        "  FROM saf_entrega ".
			" WHERE saf_entrega.codemp='".$ls_codemp."'".$ls_sqlint.
			"   AND saf_entrega.cmpent like '".$ls_cmpent."' ";		 
	$rs_cta=$io_sql->select($ls_sql);
	$li_row=$io_sql->num_rows($rs_cta);
	if($li_row>0)
	{
		while($row=$io_sql->fetch_row($rs_cta))
		{
			print "<tr class=celdas-blancas>";
			$ls_cmpent=$row["cmpent"];
			$ld_feccmp=$row["feccmp"];
			$ld_fecent=$row["fecent"];
			$ls_descmp=$row["obsent"];
			$ls_estproent=$row["estproent"];
			$ld_feccmp=$io_fun->uf_convertirfecmostrar($ld_feccmp);
			$ld_fecent=$io_fun->uf_convertirfecmostrar($ld_fecent);
			$ls_codres = $row["codres"];
			$ls_denres = $row["nomres"];
			$ls_codrec = $row["codrec"];
			$ls_denrec = $row["nomrec"];
			$ls_coddes = $row["coddes"];
			$ls_dendes = $row["nomdes"];
			$ls_coduniadm = $row["coduniadm"];
			$ls_denuniadm = $row["denuniadm"];
			$ls_codunisol = $row["codunisol"];
			$ls_denunisol = $row["denunisol"];
			$ls_tipres = $row["tipres"];
			$ls_tiprec = $row["tiprec"];
			$ls_tipdes = $row["tipdes"];

			print "<td><a href=\"javascript: aceptar('$ls_cmpent','$ld_feccmp','$ld_fecent','$ls_estproent','$ls_descmp','$ls_coduniadm','$ls_denuniadm','$ls_codunisol','$ls_denunisol',".
				  "'$ls_tipres','$ls_codres','$ls_denres','$ls_tiprec','$ls_codrec','$ls_denrec','$ls_tipdes','$ls_coddes','$ls_dendes',".
				  "'$ls_coddestino');\">".$ls_cmpent."</a></td>";
			print "<td>".$ld_feccmp."</td>";
			print "<td>".$ld_fecent."</td>";
			print "</tr>";			
		}
	}
	else
	{
			$io_msg->message("No hay registros");
	}
}
print "</table>";
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

	function aceptar(ls_cmpent,ld_feccmp,ld_fecent,ls_estproent,ls_descmp,ls_coduniadm,ls_denuniadm,ls_codunisol,ls_denunisol,
				     ls_tipres,ls_codres,ls_denres,ls_tiprec,ls_codrec,ls_denrec,ls_tipdes,ls_coddes,ls_dendes,
				     ls_coddestino)
	{
		opener.document.form1.txtcmpent.value=ls_cmpent;
		if(ls_coddestino!="reporte")
		{
			opener.document.form1.txtfeccmp.value=ld_feccmp;
			opener.document.form1.txtfecent.value=ld_feccmp;
			opener.document.form1.txtcoduniadm.value=ls_coduniadm;
			opener.document.form1.txtdenuniadm.value=ls_denuniadm;
			opener.document.form1.txtcodunisol.value=ls_codunisol;
			opener.document.form1.txtdenunisol.value=ls_denunisol;
			
			opener.document.form1.txtcmpent.readOnly=true;
			opener.document.form1.txtfeccmp.readOnly=true;
			opener.document.form1.txtcoduniadm.readOnly=true;
			opener.document.form1.txtdenuniadm.readOnly=true;
			opener.document.form1.txtcodunisol.readOnly=true;
			opener.document.form1.txtdenunisol.readOnly=true;
			
			opener.document.form1.txtdescmp.value=ls_descmp;
			opener.document.form1.hidestproent.value=ls_estproent;
			
		    opener.document.form1.txtcodres.value=ls_codres;
		    opener.document.form1.txtcodres.readOnly=true;
		    opener.document.form1.txtnomres.value=ls_denres;
		    opener.document.form1.txtnomres.readOnly=true;
			
		    opener.document.form1.txtcodrec.value=ls_codrec;
		    opener.document.form1.txtcodrec.readOnly=true;
		    opener.document.form1.txtnomrec.value=ls_denrec;
		    opener.document.form1.txtnomrec.readOnly=true;
		    
			opener.document.form1.txtcoddes.value=ls_coddes;
		    opener.document.form1.txtcoddes.readOnly=true;
		    opener.document.form1.txtnomdes.value=ls_dendes;
		    opener.document.form1.txtnomdes.readOnly=true;
			
			opener.document.form1.cmbtipres.value=ls_tipres;
			opener.document.form1.cmbtiprec.value=ls_tiprec;
			opener.document.form1.cmbtipdes.value=ls_tipdes;
			
		}
		opener.document.form1.operacion.value="BUSCARDETALLE";
		opener.document.form1.submit();
		close();
	}
	function ue_search()
	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		f.action="sigesp_saf_cat_entregas.php";
		f.submit();
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

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>