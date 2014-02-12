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
<title>Cat&aacute;logo de Préstamos</title>
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
  <br>
      <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
        <tr class="titulo-celda">
          <td height="22" colspan="4">Cat&aacute;logo de Pr&eacute;stamos </td>
        </tr>
        <tr>
		 <td>&nbsp;</td>
		 <td>&nbsp;</td>
		 <td>&nbsp;</td>
		 <td>&nbsp;</td>
		</tr>
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
          <input name="txtcmpmov" type="text" id="txtcmpmov">
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
      <div align="center"><br>
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
	$ls_cmpres="%".$_POST["txtcmpmov"]."%";
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
print "<td width='50'>Comprobante </td>";
print "<td width='150'>Unidad Cedente</td>";
print "<td width='150'>Unidad Receptora</td>";
print "<td width='65'>Fecha</td>";
print "<td width='65'>Testigo</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	if(strtoupper($ls_gestor)=="MYSQLT")
	{
	   $ls_cadena_personal="CONCAT(sno_personal.nomper,' ',sno_personal.apeper)";
	}
	else
	{
	   $ls_cadena_personal="sno_personal.nomper||' '||sno_personal.apeper";
	}
	if(strtoupper($ls_gestor)=="MYSQLT")
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
		$ls_sqlint=" AND saf_prestamo.fecpreact >= '". $ld_fecdes ." 00:00:00'".
				   " AND saf_prestamo.fecpreact <= '". $ld_fechas ." 23:59:59' ";
	}
	else
	{
		$ls_sqlint="";
	}
	$ls_sql="SELECT distinct saf_prestamo.cmppre,saf_prestamo.coduniced,
	           (select spg_unidadadministrativa.denuniadm 
			      from spg_unidadadministrativa
			      where spg_unidadadministrativa.codemp='".$ls_codemp."'
				  and saf_prestamo.coduniced=spg_unidadadministrativa.coduniadm)AS nomadmced,
			 saf_prestamo.codunirec,
			    (select spg_unidadadministrativa.denuniadm from spg_unidadadministrativa
				   where  spg_unidadadministrativa.codemp='".$ls_codemp."' 
				   and saf_prestamo.codunirec=spg_unidadadministrativa.coduniadm)AS nomadmrec,
			 saf_prestamo.fecpreact,saf_prestamo.codresced,
			    (select ".$ls_cadena_personal." 
				   from sno_personal
				   where sno_personal.codemp='".$ls_codemp."'
				   and saf_prestamo.codresced=sno_personal.codper)AS nomresced,
			 saf_prestamo.codresrec,
			   (select ".$ls_cadena_personal." 
			      from sno_personal 
			      where sno_personal.codemp='".$ls_codemp."' 
				  and saf_prestamo.codresrec=sno_personal.codper)AS nomresrec,saf_prestamo.codtespre,
			  (select ".$ls_cadena_personal." 
			      from sno_personal 
			      where sno_personal.codemp='".$ls_codemp."' 
				  and saf_prestamo.codtespre=sno_personal.codper)AS nomtest,".
			"  saf_prestamo.estpropre".
			"  FROM saf_prestamo,saf_dt_prestamo".
			"  WHERE saf_prestamo.codemp='".$ls_codemp."' ".
			"   AND saf_prestamo.cmppre=saf_dt_prestamo.cmppre".
			"   AND saf_prestamo.coduniced=saf_dt_prestamo.coduniced ".
			"   AND saf_prestamo.codunirec=saf_dt_prestamo.codunirec".
			"   AND saf_prestamo.codemp='".$ls_codemp."'".$ls_sqlint.
			"   AND saf_prestamo.cmppre like '".$ls_cmpres."' ";
	//print "---------->".$ls_sql;
	$rs_cta=$io_sql->select($ls_sql);
	$li_row=$io_sql->num_rows($rs_cta);
	if($li_row>0)
	{
		while($row=$io_sql->fetch_row($rs_cta))
		{
			print "<tr class=celdas-blancas>";
			$ls_cmpres=$row["cmppre"]; 
			$ls_coduniced=$row["coduniced"];
			$ls_desuniced=$row["nomadmced"];
			$ls_codunirec=$row["codunirec"];
			$ls_desunirec=$row["nomadmrec"];
			$ld_feccmp=$row["fecpreact"];
			$ld_feccmp=$io_fun->uf_convertirfecmostrar($ld_feccmp);
			$ls_codresced = $row["codresced"];
			$ls_nomresced = $row["nomresced"];
			$ls_codresrec= $row["codresrec"];   
			$ls_nomresrec = $row["nomresrec"];
			$ls_codtest= $row["codtespre"];
			$ls_nomtest= $row["nomtest"];  
			$ls_estpropre= $row["estpropre"]; 
			print "<td><a href=\"javascript: aceptar('$ls_cmpres','$ls_coduniced','$ls_desuniced','$ls_codunirec','$ls_desunirec',".
				  "'$ld_feccmp','$ls_codresced','$ls_nomresced','$ls_codresrec','$ls_nomresrec','$ls_codtest','$ls_nomtest','$ls_estpropre');\">".$ls_cmpres."</a></td>";
			print "<td>".$ls_desuniced."</td>";
			print "<td>".$ls_desunirec."</td>";
			print "<td>".$ld_feccmp."</td>";
			print "<td>".$ls_codtest."</td>";
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
      </div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
	function aceptar($ls_cmpres,$ls_coduniced,$ls_desuniced,$ls_codunirec,$ls_desunirec,$ld_feccmp,
	                 $ls_codresced,$ls_nomresced,$ls_codresrec,$ls_nomresrec,$ls_codtest,$ls_nomtest,$ls_estpropre)
	{
		opener.document.form1.txtcmpres.value=$ls_cmpres;
		opener.document.form1.txtcoduniadm.value=$ls_coduniced;
		opener.document.form1.txtdenuniadm.value=$ls_desuniced;
		opener.document.form1.txtcoduni2.value=$ls_codunirec;
		opener.document.form1.txtdenuni2.value=$ls_desunirec; 
		opener.document.form1.txtfecenacta.value=$ld_feccmp; 
		opener.document.form1.txtcodresced.value=$ls_codresced;
		opener.document.form1.txtnomresced.value=$ls_nomresced;
		opener.document.form1.txtcodresrece.value=$ls_codresrec;
		opener.document.form1.txtnomresrec.value=$ls_nomresrec;
		opener.document.form1.txtcodper.value=$ls_codtest;
		opener.document.form1.txtnomper.value=$ls_nomtest;
		opener.document.form1.hidestpres.value=$ls_estpropre;
		opener.document.form1.txtcodper.readOnly=true;
		opener.document.form1.txtnomper.readOnly=true;
		opener.document.form1.txtcodresrece.readOnly=true;
		opener.document.form1.txtnomresrec.readOnly=true;
		opener.document.form1.txtfecenacta.readOnly=true;
		opener.document.form1.txtcodresced.readOnly=true;   
		opener.document.form1.txtnomresced.readOnly=true;
		opener.document.form1.txtcmpres.readOnly=true;
		opener.document.form1.txtcoduniadm.readOnly=true;
		opener.document.form1.txtcoduni2.readOnly=true;
		opener.document.form1.txtdenuni2.readOnly=true;
		opener.document.form1.hidestpres.readOnly=true;
		tipo=document.form1.tipo.value;
	
		opener.document.form1.operacion.value="BUSCARDETALLE";
		opener.document.form1.submit();
		close();
	}
	function ue_search()
	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		f.action="sigesp_saf_cat_prestamo.php";
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