<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Reasignaciones</title>
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
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Modificaciones </td>
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
          <input name="txtcmpmov" type="text" id="txtcmpmov">
        </div></td>
        <td width="68">&nbsp;</td>
      </tr>
      <tr>
        <td><div align="right">Causa de Movimiento </div></td>
        <td height="22"><div align="left">          <input name="txtcodcau" type="text" id="txtcodcau">
        </div></td>
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
require_once("sigesp_saf_c_activo.php");
$io_saf = new sigesp_saf_c_activo();
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_cmpmov="%".$_POST["txtcmpmov"]."%";
	$ls_codcau="%".$_POST["txtcodcau"]."%";
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
print "<table width=550 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td width='50'>Comprobante</td>";
print "<td width='200'>Causa</td>";
print "<td>Activo</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	if(($ld_fecdes!="")&&($ld_fechas!=""))
	{
		$ls_sqlint=" AND saf_movimiento.feccmp >= '". $ld_fecdes ."'".
				   " AND saf_movimiento.feccmp <= '". $ld_fechas ."' ";
	}
	else
	{
		$ls_sqlint="";
	}
	$ls_estcat=$io_saf->uf_select_valor_config($ls_codemp);
	$ls_sql="SELECT saf_movimiento.*, ".
			"       (SELECT codact    ".
			"        FROM saf_partes  ".
			"        WHERE saf_movimiento.cmpmov=saf_partes.cmpmov ".
			"        GROUP BY cmpmov,codact) AS codact, ".
			"       (SELECT ideact   ".
			"        FROM saf_partes ".
			"        WHERE saf_movimiento.cmpmov=saf_partes.cmpmov ".
			"        GROUP BY cmpmov,ideact) AS ideact, ".
			"       (SELECT seract   ".
			"         FROM saf_dta,saf_partes ".
			"        WHERE saf_dta.codemp=saf_partes.codemp ".
			"          AND saf_dta.codact=saf_partes.codact".
			"          AND saf_dta.ideact=saf_partes.ideact".
			"          AND saf_movimiento.cmpmov=saf_partes.cmpmov) AS seract, ".
			"       (SELECT dencau ".
			"        FROM saf_causas ".
			"        WHERE saf_movimiento.codcau=saf_causas.codcau ".
			"        AND   saf_causas.estcat='".$ls_estcat."'      ".
			"        GROUP BY cmpmov,dencau) AS dencau".
			" FROM  saf_movimiento".
			" WHERE cmpmov IN  ".
			"       (SELECT cmpmov FROM saf_partes GROUP BY cmpmov) ".
			" AND   saf_movimiento.codemp='".$ls_codemp."' ".$ls_sqlint.
			" AND   saf_movimiento.cmpmov like '".$ls_cmpmov."'";
	$rs_cta=$io_sql->select($ls_sql);
	$li_num=$row=$io_sql->num_rows($rs_cta);
	if($li_num>0)
	{
		while($row=$io_sql->fetch_row($rs_cta))
		{
			$ls_codact=$row["codact"];
			$ls_sql="SELECT * FROM saf_activo WHERE codact='".$ls_codact."'";
			$rs_data=$io_sql->select($ls_sql);
			if($row1=$io_sql->fetch_row($rs_data))
			{
				$ls_denact=$row1["denact"];
			}
			print "<tr class=celdas-blancas>";
			$ls_cmpmov=$row["cmpmov"];
			$ls_codact=$row["codact"];
			$ls_codcau=$row["codcau"];
			$ls_dencau=$row["dencau"];
			$ls_ideact=$row["ideact"];
			$ls_seract=$row["seract"];
			print $ls_seract;
			$ls_descmp=$row["descmp"];
			$ld_feccmp=$io_fun->uf_formatovalidofecha($row["feccmp"]);
			$ls_estpromov=$row["estpromov"];
			$ld_feccmp=$io_fun->uf_convertirfecmostrar($ld_feccmp);
			print "<td><a href=\"javascript: aceptar('$ls_cmpmov','$ls_codact','$ls_ideact','$ls_denact','$ls_descmp','$ld_feccmp','$ls_codcau','$ls_dencau','$ls_estpromov','$ls_seract');\">".$ls_cmpmov."</a></td>";
			print "<td>".$ls_dencau."</td>";
			print "<td>".$ls_denact."</td>";
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
function aceptar(ls_cmpmov,ls_codact,ls_ideact,ls_denact,ls_descmp,ls_feccmp,ls_codcau,ls_dencau,ls_estpromov,ls_seract)
{
	opener.document.form1.txtcmpmov.value=ls_cmpmov;
	opener.document.form1.txtcodcau.value=ls_codcau;
	opener.document.form1.txtdencau.value=ls_dencau;
	opener.document.form1.txtfeccmp.value=ls_feccmp;
	opener.document.form1.txtdescmp.value=ls_descmp;
	opener.document.form1.txtcodact.value=ls_codact;
	opener.document.form1.txtideact.value=ls_ideact;
	opener.document.form1.txtseract.value=ls_seract;
	opener.document.form1.txtdenact.value=ls_denact;
	opener.document.form1.hidestpromov.value=ls_estpromov;
	opener.document.form1.hidstatus.value="C";
	opener.document.form1.operacion.value="BUSCARDETALLE";
	opener.document.form1.txtcmpmov.readOnly=true;
	opener.document.form1.txtcodcau.readOnly=true;
	opener.document.form1.txtdencau.readOnly=true;
	opener.document.form1.txtfeccmp.readOnly=true;
	opener.document.form1.submit();
	close();
}
function ue_search()
{
	f=document.form1;
	f.operacion.value="BUSCAR";
	f.action="sigesp_saf_cat_modificaciones.php";
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