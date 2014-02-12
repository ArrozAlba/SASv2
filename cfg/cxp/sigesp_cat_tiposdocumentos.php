<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo de Tipos de Documentos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
   <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo Tipos de Documentos</td>
  </tr>
</table>
<div align="center"><br>
  <?php
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_sql.php");

$io_conect=new sigesp_include();
$conn=$io_conect->uf_conectar();
$io_dstipodoc=new class_datastore();
$io_sql=new class_sql($conn);
$arr=$_SESSION["la_empresa"];
$ls_sql=" SELECT * ".
        " FROM cxp_documento ".
		" ORDER BY codtipdoc ASC";
$rs_tipodoc=$io_sql->select($ls_sql);
$data=$rs_tipodoc;
if($row=$io_sql->fetch_row($rs_tipodoc))
{
	$data=$io_sql->obtener_datos($rs_tipodoc);
	$arrcols=array_keys($data);
	$totcol=count($arrcols);
	$io_dstipodoc->data=$data;
	$totrow=$io_dstipodoc->getRowCount("codtipdoc");
    print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla>";
	print "<tr class=titulo-celda>";
	print "<td>Código</td>";
	print "<td>Denominación</td>";
	print "</tr>";
	for($z=1;$z<=$totrow;$z++)
	{
		print "<tr class=celdas-blancas>";
		$codigo        =$data["codtipdoc"][$z];
		$denominacion  =$data["dentipdoc"][$z];
		$contable      =$data["estcon"][$z];
		$presupuestaria=$data["estpre"][$z];
		print "<td style=text-align:center><a href=\"javascript: aceptar('$codigo','$denominacion','$contable','$presupuestaria');\">".$codigo."</a></td>";
		print "<td style=text-align:left>".$denominacion."</td>";
		print "</tr>";			
	}
    print "</table>";
$io_sql->free_result($rs_tipodoc);
}
else
  {
    ?>
    <script language="javascript">
	alert("No se han creado Documentos !!!");
	close();
	</script>
   <?php
  }
?>
</div>
</body>
<script language="JavaScript">
  function aceptar(codigo,denominacion,estatuscont,estatuspre)
  {
    opener.document.form1.txttipodoc.value=codigo;
    opener.document.form1.txtdentipdoc.value=denominacion;
	opener.document.form1.hidcontable.value=estatuscont;
  	opener.document.form1.hidpresupuestario.value=estatuspre;
	opener.document.form1.hidestatus.value="GRABADO";
 	close();
  }
</script>
</html>