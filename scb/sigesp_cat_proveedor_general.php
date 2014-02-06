<?php
//session_id('8675309');
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "close();";
	print "opener.document.form1.submit();";
	print "</script>";		
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Documento sin t&iacute;tulo</title>
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
<link href="../SCG/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../SCG/css/general.css" rel="stylesheet" type="text/css">
<link href="../SCG/css/tablas.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php
require_once("../shared/class_folder/sigesp_include.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
//include("class_mensajes.php");
$msg=new class_mensajes();
require_once("../shared/class_folder/class_datastore.php");
$ds=new class_datastore();
$SQL=new class_sql($con);
$arr=$_SESSION["la_empresa"];

$ls_sql="SELECT cod_pro,nompro FROM rpc_proveedor WHERE codemp='".$arr["codemp"]."' AND cod_pro<>'----------' AND estprov=0";
$rs_cta=$SQL->select($ls_sql);
$data=$rs_cta;
if($row=$SQL->fetch_row($rs_cta))
{
	$data=$SQL->obtener_datos($rs_cta);
}
$arrcols=array_keys($data);
$totcol=count($arrcols);
$ds->data=$data;
$totrow=$ds->getRowCount("cod_pro");
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla>";
print "<tr class=titulo-celda>";
print "<td>Código </td>";
print "<td>Nombre del Proveedor</td>";
print "</tr>";
for($z=1;$z<=$totrow;$z++)
{
	print "<tr class=celdas-blancas>";
	$codigo=$data["cod_pro"][$z];
	$nombre=$data["nompro"][$z];
	print "<td><a href=\"javascript: aceptar('$codigo','$nombre');\">".$codigo."</a></td>";
	print "<td>".$data["nompro"][$z]."</td>";
	print "</tr>";			
}
print "</table>";
?>
</body>
<script language="JavaScript">
  function aceptar(prov,d)
  {
    opener.document.form1.txtprovbene.value=prov;
    //opener.document.form1.txtdenominacion.value=d;
	//opener.buscar();
	close();
  }
</script>
</html>
