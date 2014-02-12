<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Condici&oacute;n del Activo </title>
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
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
    <input name="txtempresa" type="hidden" id="txtempresa">
    <input name="hidstatus" type="hidden" id="hidstatus">
    <input name="txtnombrevie" type="hidden" id="txtnombrevie">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Condici&oacute;n del Activo </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67"><div align="right">C&oacute;digo</div></td>
        <td width="431" height="22"><div align="left">
          <input name="txtcodigo" type="text" id="txtnombre2">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Denominaci&oacute;n</div></td>
        <td height="22"><div align="left">          <input name="txtdenominacion" type="text" id="txtdenominacion">
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
$in=new sigesp_include();
$con=$in->uf_conectar();
require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
require_once("../shared/class_folder/class_datastore.php");
$ds=new class_datastore();
require_once("../shared/class_folder/class_sql.php");
$io_sql=new class_sql($con);
$arr=$_SESSION["la_empresa"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codigo="%".$_POST["txtcodigo"]."%";
	$ls_denominacion="%".$_POST["txtdenominacion"]."%";
	$ls_status="%".$_POST["hidstatus"]."%";
}
else
{
	$ls_operacion="BUSCAR";
	$ls_codigo="%%";
	$ls_denominacion="%%";
	$ls_status="%%";
}
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
//print "<td>Empresa </td>";
print "<td>Código</td>";
print "<td>Denominación</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_sql="SELECT codconbie, denconbie, desconbie FROM saf_conservacionbien".
			" WHERE codconbie like '".$ls_codigo."'".
			"   AND denconbie like '".$ls_denominacion."'".
			" ORDER BY codconbie";
    $rs_cta=$io_sql->select($ls_sql);
    $data=$rs_cta;
	if($row=$io_sql->fetch_row($rs_cta))
	{
		$data=$io_sql->obtener_datos($rs_cta);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds->data=$data;

		$totrow=$ds->getRowCount("codconbie");
	
		for($z=1;$z<=$totrow;$z++)
		{
			print "<tr class=celdas-blancas>";
			$ls_codigo=$data["codconbie"][$z];
			$ls_denominacion=$data["denconbie"][$z];
			$ls_descripcion=$data["desconbie"][$z];
			print "<td><a href=\"javascript: aceptar('$ls_codigo','$ls_denominacion','$ls_descripcion','$ls_status');\">".$ls_codigo."</a></td>";
			print "<td>".$data["denconbie"][$z]."</td>";
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
	function aceptar(ls_codigo,ls_denominacion,ls_descripcion,ls_status)
	{
		opener.document.form1.txtcodconbie.value=ls_codigo;
		opener.document.form1.txtdenconbie.value=ls_denominacion;
		opener.document.form1.txtdescripcion.value=ls_descripcion;
		opener.document.form1.hidstatus.value="C";
		close();
	}
	function ue_search()
	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		f.action="sigesp_saf_cat_condicion.php";
		f.submit();
	}
</script>
</html>
