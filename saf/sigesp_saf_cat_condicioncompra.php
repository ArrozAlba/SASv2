<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Condici&oacute;n de Compra</title>
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
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" height="15" colspan="2" class="titulo-celda">Cat&aacute;logo de Condici&oacute;n de Compra </td>
    </tr>
  </table>
<br>
    <table width="501" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="131" height="18"><div align="right">C&oacute;digo</div></td>
        <td width="368" height="22"><div align="left">
          <input name="txtcodconcom" type="text" id="txtcodconcom">
        </div>          <div align="right"></div>          <div align="right">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Denominaci&oacute;n</div></td>
        <td height="22"><div align="left">
          <input name="txtdenconcom" type="text" id="txtdenconcom">
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
	$ls_codconcom="%".$_POST["txtcodconcom"]."%";
	$ls_denconcom="%".$_POST["txtdenconcom"]."%";
	
}
else
{
	$ls_operacion="BUSCAR";
	$ls_codconcom="%%";
	$ls_denconcom="%%";
}
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td width='50'>Código </td>";
print "<td>Denominación</td>";
print "</tr>";

if($ls_operacion=="BUSCAR")
{
	$ls_sql="SELECT * FROM saf_condicioncompra".
			" WHERE codconcom LIKE '".$ls_codconcom."'".
			" AND denconcom LIKE '".$ls_denconcom."'";
			
	$rs_cta=$io_sql->select($ls_sql);
	$data=$rs_cta;
	if($row=$io_sql->fetch_row($rs_cta))
	{
		$data=$io_sql->obtener_datos($rs_cta);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds->data=$data;
		$totrow=$ds->getRowCount("codconcom");
		for($z=1;$z<=$totrow;$z++)
		{
			print "<tr class=celdas-blancas>";
			$ls_codconcom= $data["codconcom"][$z];
			$ls_denconcom= $data["denconcom"][$z];
			print " <td align='center'><a href=\"javascript: aceptar('$ls_codconcom','$ls_denconcom');\">".$ls_codconcom."</a></td>";
			print "<td>".$ls_denconcom."</td>";
			print "</tr>";			
		}
	}
	else
	{
		$io_msg->message("No hay registros.");
	}
}
print "</table>";

?>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">   
	function aceptar(ls_codconcom,ls_denconcom)
	{
		opener.document.form1.txtcodconcom.value=ls_codconcom;
		opener.document.form1.txtdenconcom.value=ls_denconcom;
		close();
	}
  
	function ue_search()
	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		f.action="sigesp_saf_cat_condicioncompra.php";
		f.submit();
	}
  

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
