<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de N&oacute;minas </title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:hover {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style></head>

<body>
<?php
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");

$io_conect= new sigesp_include();
$conn = $io_conect->uf_conectar();
$io_dstar= new class_datastore();
$io_sql= new class_sql($conn);
$ls_codemp=$_SESSION["la_empresa"]["codemp"];
$ls_sql= " SELECT * FROM sno_nomina".
		 " WHERE codemp='".$ls_codemp."'".
		 " ORDER BY codnom ASC ";
$rs_data= $io_sql->select($ls_sql);
$data= $rs_data;
?>
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
   <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de N&oacute;minas </td>
  </tr>
</table>
  <br>
<form name="form1" method="post" action="">
  <div align="center">
    <?php
if ($row=$io_sql->fetch_row($rs_data))
{
	$data= $io_sql->obtener_datos($rs_data);
	$arrcols= array_keys($data);
	$totcol= count($arrcols);
	$io_dstar->data= $data;
	$totrow= $io_dstar->getRowCount("codnom");
	print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	print "<tr class=titulo-celda>";
	print "<td>Código</td>";
	print "<td>Denominación</td>";
	print "</tr>";
	for ($z=1;$z<=$totrow;$z++)
	{
		print "<tr class=celdas-blancas>";
		$ls_codnom= $data["codnom"][$z];
		$ls_desnom= $data["desnom"][$z];
		print "<td><a href=\"javascript: aceptar('$ls_codnom','$ls_desnom');\">".$ls_codnom."</a></td>";
		print "<td>".$ls_desnom."</td>";
		print "</tr>";			
	}
	print "</table>";
	$io_sql->free_result($rs_data);
}
else
{ ?>
	<script language="javascript">
	alert("No se han creado Categorías de Viáticos");
	close();
	</script>
<?php
}		 

?>
  </div>
</form>
</body>
<script language="JavaScript">
function aceptar(ls_codnom,ls_desnom)
{
	opener.document.form1.txtcodintper.value= ls_codnom;
	opener.document.form1.txtdesintper.value= ls_desnom;
	close();
}
</script>
</html>