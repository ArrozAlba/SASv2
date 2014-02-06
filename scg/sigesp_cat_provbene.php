<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Documento sin t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<form name="form1" method="post" action="">
  <input name="provbene" type="hidden" id="provbene" value="<? print $_GET["provbene"];?>">
</form>
<?php
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_sigesp_int.php");
require_once("../shared/class_folder/class_sigesp_int_scg.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
$int_scg=new class_sigesp_int_scg();
$msg=new class_mensajes();
$ds=new class_datastore();
$SQL=new class_sql($con);
$la_emp=$_SESSION["la_empresa"];
$ls_provbene=$_GET["provbene"];
print $ls_provbene;
//print "<table width=500 border=1 cellpadding=0 cellspacing=0 bordercolor=#690074>";
if($ls_provbene=="P")
{
	$ls_sql="SELECT cod_pro,nompro FROM rpc_proveedor " ;
	$rs_cta=$SQL->select($ls_sql);
	$data=$rs_cta;
	/*if($row=$SQL->fetch_row($rs_cta))
	{
		$data=$SQL->obtener_datos($rs_cta);

	}*/
}
elseif($ls_provbene=="B")
{
	
	$ls_sql=" SELECT ced_bene,nombene FROM rpc_beneficiario ";
	$rs_cta=$SQL->select($ls_sql);
	$data=$rs_cta;
}
	print "Total ".$SQL->num_rows($rs_cta);
	
	if($row=$SQL->fetch_row($rs_cta))
	{
		$data=$SQL->obtener_datos($rs_cta);
	}
		$ds->data=$data;
		$la_cols=array_keys($data);
		$li_totcol=count($la_cols);
		print "  ".$ls_provbene." ".$la_cols[0];
		$li_totrow=$ds->getRowCount($la_cols[0]);
		print "  ".$ls_provbene;
		print "<table width=500 border=1 cellpadding=0 cellspacing=0 bordercolor=#690074>";
		print "<tr  bgcolor=#D5D5D5>";
		for($li_i=0;$li_i<$li_totcol;$li_i++)
		{
		print "<td>".$la_cols[$li_i]."</td>";
		}
		print "</tr>";
		for($li_row=1;$li_row<=$li_totrow;$li_z++)
		{
			print "<tr bgcolor=#C8DAEC>";
			$ls_codigo=$data[$la_cols[1]][$li_row];
			$ls_nombre=$data[$la_cols[0]][$li_row];
			print "<td><a href=\"javascript: aceptar('$ls_codigo','$ls_nombre');\">".$ls_codigo."</a></td>";
			print "<td>".$ls_nombre."</td>";
			print "</tr>";			
		}
		print "</table>";	
		
?>

<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
