<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Documento sin t&iacute;tulo</title>
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

$ls_sql="SELECT sc_cuenta,denominacion FROM sigesp_plan_unico ";
$rs_cta=$SQL->select($ls_sql);
$data=$rs_cta;
if($row=$SQL->fetch_row($rs_cta))
{
	$data=$SQL->obtener_datos($rs_cta);
}
else
{
	 if($arr["esttipcont"]==0)
	 {
			$msg->message("Se procederá a la carga del plan único,\n esto puede tardar unos minutos,no se desespere");
			$int_scg->uf_cargar_plan_unico_cuenta_general( );
			$int_scg->SQL=$SQL;
	 }
	 elseif($arr["esttipcont"]==1)
	 {
			$msg->message("Se procederá a la carga del plan único,\n esto puede tardar unos minutos,no se desespere");
			$int_scg->SQL=$SQL;
			$int_scg->uf_cargar_plan_unico_cuenta_fiscal( );
	 }
	else
	{
		die("Error");
	}
	$rs_cta=$SQL->select($ls_sql);
	$data=$SQL->obtener_datos($rs_cta);
			
	

}
$arrcols=array_keys($data);
$totcol=count($arrcols);
$ds->data=$data;

$totrow=$ds->getRowCount("sc_cuenta");
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla>";
print "<tr class=titulo-celda>";
for($i=0;$i<$totcol;$i++)
{
print "<td>".$arrcols[$i]."</td>";
}
print "</tr>";
for($z=1;$z<=$totrow;$z++)
{
	print "<tr class=celdas-blancas>";
	$valor=$data["sc_cuenta"][$z];
	$denominacion=$data["denominacion"][$z];
	print "<td><a href=\"javascript: aceptar('$valor','$denominacion');\">".$valor."</a></td>";
	print "<td>".$data["denominacion"][$z]."</td>";
	print "</tr>";			
}
print "</table>";
?>
</div>
</body>
<script language="JavaScript">
  function aceptar(c,d)
  {
    opener.document.form1.txtcuenta.value=c;
    opener.document.form1.txtdenominacion.value=d;
	close();
  }
</script>
</html>
