<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Traslados</title>
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
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Traslados</td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="109"><div align="right">C&oacute;digo</div></td>
        <td width="389" height="22"><div align="left">
          <input name="txtcmpmov" type="text" id="txtcmpmov">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Fecha</div></td>
        <td height="22"><div align="left">          <input name="txtfectraact" type="text" id="txtfectraact" datepicker="true">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Buscar</a></div></td>
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
$arre=$_SESSION["la_empresa"];
$ls_codemp=$arre["codemp"];
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_cmpmov="%".$_POST["txtcmpmov"]."%";
	$ld_fectraact="%".$_POST["txtfectraact"]."%";
	$ls_status="%".$_POST["hidstatus"]."%";
	$ls_hora="";
	if($ld_fectraact!="%%")
	{
		$ld_fectraact=$_POST["txtfectraact"];
		$ls_hora=" 00:00:00";
		$ld_fectraact=$io_fun->uf_convertirdatetobd($ld_fectraact);
	}
}
else
{
	$ls_operacion="";

}
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td width='50'>Comprobante </td>";
print "<td width='65'>Fecha</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_sql="SELECT * FROM saf_traslado".
			" WHERE codemp='".$ls_codemp."'".
			" AND cmpmov like '".$ls_cmpmov."'".
			" AND fectraact like '".$ld_fectraact.$ls_hora."' ";
	$rs_cta=$io_sql->select($ls_sql);
    $data=$rs_cta;

	if($row=$io_sql->fetch_row($rs_cta))
	{
		$data=$io_sql->obtener_datos($rs_cta);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds->data=$data;

		$totrow=$ds->getRowCount("cmpmov");
	
		for($z=1;$z<=$totrow;$z++)
		{
			print "<tr class=celdas-blancas>";
			$ls_cmpmov=$data["cmpmov"][$z];
			$ld_fectraact=$data["fectraact"][$z];
			$ls_obstra=$data["obstra"][$z];
			$ld_fectraact=$io_fun->uf_convertirfecmostrar($ld_fectraact);
			print "<td><a href=\"javascript: aceptar('$ls_cmpmov','$ld_fectraact','$ls_obstra');\">".$ls_cmpmov."</a></td>";
			print "<td>".$ld_fectraact."</td>";
			print "</tr>";			
		}
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
  function aceptar(ls_cmpmov,ld_fectraact,ls_obstra)
  {

	opener.document.form1.txtcmpmov.value=ls_cmpmov;
	opener.document.form1.txtfectraact.value=ld_fectraact;
	opener.document.form1.txtobstra.value=ls_obstra;
	opener.document.form1.operacion.value="BUSCARDETALLE";
	opener.document.form1.txtcmpmov.readOnly=true;
	opener.document.form1.txtfectraact.readOnly=true;
	opener.document.form1.submit();
	close();
  }
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_saf_cat_traslado.php";
  f.submit();
  }
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
