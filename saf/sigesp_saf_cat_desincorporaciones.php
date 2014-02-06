<?php
session_start();
require_once("class_funciones_activos.php");
$io_fact= new class_funciones_activos();
if(array_key_exists("coddestino",$_POST))
{
	$ls_coddestino=$_POST["coddestino"];
}
else
{
	$ls_coddestino=$io_fact->uf_obtenervalor_get("coddestino","");
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Desincorporaciones</title>
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
    <input name="coddestino" type="hidden" id="coddestino" value="<?php print $ls_coddestino ?>">
  </p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Desincorporaciones</td>
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
require_once("class_funciones_activos.php");
$io_fun_activos=new class_funciones_activos();

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
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td width='50'>Comprobante </td>";
print "<td width='150'>Causa</td>";
print "<td width='65'>Fecha</td>";
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
	$ls_sql=" SELECT saf_movimiento.*,saf_causas.dencau ".
	        " FROM   saf_movimiento,saf_causas ".
			" WHERE  saf_movimiento.codcau=saf_causas.codcau ".
			" AND    saf_causas.tipcau='D' AND saf_causas.estcat='".$ls_estcat."' ".
			" AND    saf_movimiento.codemp='".$ls_codemp."'".$ls_sqlint.
			" AND    saf_movimiento.cmpmov like '".$ls_cmpmov."' ".
			" AND    saf_movimiento.codcau like '".$ls_codcau."' ";
	$rs_cta=$io_sql->select($ls_sql);
	$li_row=$io_sql->num_rows($rs_cta);
	if($li_row>0)
	{
		while($row=$io_sql->fetch_row($rs_cta))
		{
			print "<tr class=celdas-blancas>";
			$ls_cmpmov=$row["cmpmov"];
			$ls_codcau=$row["codcau"];
			$ls_dencau=$row["dencau"];
			$ld_feccmp=$io_fun->uf_formatovalidofecha($row["feccmp"]);
			$ls_descmp=$row["descmp"];
			$ls_estpromov=$row["estpromov"];
			$ld_feccmp=$io_fun->uf_convertirfecmostrar($ld_feccmp);
			print "<td><a href=\"javascript: aceptar('$ls_cmpmov','$ls_codcau','$ls_dencau','$ld_feccmp','$ls_descmp',".
				  "'$ls_estpromov','$ls_coddestino');\">".$ls_cmpmov."</a></td>";
			print "<td>".$ls_dencau."</td>";
			print "<td>".$ld_feccmp."</td>";
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
function aceptar(ls_cmpmov,ls_codcau,ls_dencau,ld_fectraact,ls_obstra,ls_estpromov,ls_coddestino)
{
	f=document.form1;
	if(ls_coddestino=="codcmpmov")
	{
		obj=eval("opener.document.form1."+ls_coddestino+"");
		obj.value=ls_cmpmov;
		close();
	}
	else
	{
	 opener.document.form1.txtcmpmov.value=ls_cmpmov;
	 if(ls_coddestino!="reporte")
	 {
		opener.document.form1.txtcodcau.value=ls_codcau;
		opener.document.form1.txtdencau.value=ls_dencau;
		opener.document.form1.txtfeccmp.value=ld_fectraact;
		opener.document.form1.txtdescmp.value=ls_obstra;
		opener.document.form1.hidestpromov.value=ls_estpromov;
		opener.document.form1.hidstatus.value="C";
		opener.document.form1.txtcmpmov.readOnly=true;
		opener.document.form1.txtcodcau.readOnly=true;
		opener.document.form1.txtdencau.readOnly=true;
		opener.document.form1.txtfeccmp.readOnly=true;
	 } 
		opener.document.form1.operacion.value="BUSCARDETALLE";
		opener.document.form1.submit();
		close();
    }	
}

function ue_search()
{
	f=document.form1;
	f.operacion.value="BUSCAR";
	f.action="sigesp_saf_cat_desincorporaciones.php";
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