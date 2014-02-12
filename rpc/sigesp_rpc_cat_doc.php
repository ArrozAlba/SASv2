<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo Tipos de Documentos</title>
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
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
   <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Recaudos</td>
  </tr>
</table>
<br>
<?php
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");

$io_conect=new sigesp_include();
$conn=$io_conect->uf_conectar();
$io_dsdoc=new class_datastore();
$io_sql=new class_sql($conn);
$arr=$_SESSION["la_empresa"];

$ls_sql=" SELECT coddoc,dendoc ".
        " FROM rpc_documentos ".
		" ORDER BY coddoc ASC";

$rs_documento=$io_sql->select($ls_sql);
$data=$rs_documento;
if($row=$io_sql->fetch_row($rs_documento))
{
	$data=$io_sql->obtener_datos($rs_documento);
	$arrcols=array_keys($data);
	$totcol=count($arrcols);
	$io_dsdoc->data=$data;
	$totrow=$io_dsdoc->getRowCount("coddoc");
    print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla>";
	print "<tr class=titulo-celda>";
	print "<td>Código</td>";
	print "<td>Denominación</td>";
	print "</tr>";
	for($z=1;$z<=$totrow;$z++)
	{
		print "<tr class=celdas-blancas>";
		$codigo=$data["coddoc"][$z];
		$denominacion=$data["dendoc"][$z];
		print "<td><a href=\"javascript: aceptar('$codigo','$denominacion');\">".$codigo."</a></td>";
		print "<td>".$denominacion."</td>";
		print "</tr>";			
	}
    print "</table>";
    $io_sql->free_result($rs_documento);
}
else
{ ?>
	  <script language="javascript">
	  alert("No se han creado Documentos !!!");
	  close();
	  </script>
	<?php
	}		

?>
</body>
<script language="JavaScript">
  function aceptar(codigo,denominacion)
  {
    opener.document.form1.txtcodigo.value       = codigo;
	opener.document.form1.txtdenominacion.value = denominacion;
	close();
  }
</script>
</html>