<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Cuentas Contables</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
</head>

<body>
<div align="center">
  <?php
require_once("../../shared/class_folder/class_fecha.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_sigesp_int.php");
require_once("../../shared/class_folder/class_sigesp_int_scg.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
$int_scg=new class_sigesp_int_scg();
$msg=new class_mensajes();
$ds=new class_datastore();
$SQL=new class_sql($con);
$arr=$_SESSION["la_empresa"];
$as_codemp=$arr["codemp"];

$ls_cadena ="SELECT sc_cuenta, denominacion, status, asignado, distribuir," . 
		   "enero, febrero, marzo, abril, mayo, junio, julio, agosto, septiembre, octubre, noviembre, diciembre,".
		   "nivel, referencia FROM scg_cuentas ".
		   "WHERE codemp = '".$as_codemp."' ORDER BY sc_cuenta";
$rs_cta=$SQL->select($ls_cadena);
$data=$rs_cta;
if($row=$SQL->fetch_row($rs_cta))
{
	$data=$SQL->obtener_datos($rs_cta);
}
else
{
print "No se han creado Cuentas Contables";
}
$arrcols=array_keys($data);
$totcol=count($arrcols);

//print count($data["SC_cuenta"]);
$ds->data=$data;

$totrow=$ds->getRowCount("sc_cuenta");
?>
</div>
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1" class="fondo-tabla">
	<tr class="titulo-celda">
	<td>Cuenta Contable</td>
	<td>Denominación</td>
</tr>
<?php
for($z=1;$z<=$totrow;$z++)
{
	$valor=$data["sc_cuenta"][$z];
	$denominacion=$data["denominacion"][$z];
	$status=$data["status"][$z];
	if($status=="S")
	{
	print "<tr class=celdas-blancas>";
	print "<td>".$valor."</td>";
	print "<td>".$data["denominacion"][$z]."</td>";
	}
	else
	{
	print "<tr class=celdas-azul>";
	print "<td><a href=\"javascript: aceptar('$valor','$denominacion');\">".$valor."</a></td>";
	print "<td>".$data["denominacion"][$z]."</td>";
	}
	print "</tr>";			
}
?>
</table>
</body>
<script language="JavaScript">
  function aceptar(c,d)
  {
    opener.document.form1.txtcuenta.value=c;
    opener.document.form1.txtdescdoc.value=d;
	close();
  }
</script>
</html>
