<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo de Tipos de Documentos</title>
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
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
   <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo Tipos de Documentos</td>
  </tr>
</table>
<div align="center"><br>
  <?php
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");
require_once("class_folder/class_funciones_sob.php");

$io_conect=new sigesp_include();
$conn=$io_conect->uf_conectar();
$io_msg=new class_mensajes();
$io_dstipodoc=new class_datastore();
$io_fun_sob=new class_funciones_sob();
$io_sql=new class_sql($conn);
$arr=$_SESSION["la_empresa"];
$ls_tipo=$io_fun_sob->uf_obtenertipo();
$ls_criterio="";
if($ls_tipo=="")
{
	$li_estcon=1;
	$li_estpre=3; 
	$ls_criterio=" OR estpre='4'";
}
else
{
	$li_estcon=1;
	$li_estpre=1;
}

$ls_sql=" SELECT codtipdoc,dentipdoc ".
        "   FROM cxp_documento ".
		"  WHERE estcon=".$li_estcon."".
		"    AND estpre=".$li_estpre."$ls_criterio".
		"  ORDER BY codtipdoc ASC";
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
		$ls_codtipdoc =$data["codtipdoc"][$z];
		$ls_dentipdoc =$data["dentipdoc"][$z];
		print "<td style=text-align:center><a href=\"javascript: aceptar('$ls_codtipdoc','$ls_dentipdoc');\">".$ls_codtipdoc."</a></td>";
		print "<td style=text-align:left>".$ls_dentipdoc."</td>";
		print "</tr>";			
	}
    print "</table>";
}
else
  {
    print "No se han creado Documentos !!!";
  }
$io_sql->free_result($rs_tipodoc);
$io_sql->close();  
?>
</div>
</body>
<script language="JavaScript">
function aceptar(codtipdoc,dentipdoc)
{
	opener.document.form1.txtcodtipdoc.value=codtipdoc;
	opener.document.form1.txtdentipdoc.value=dentipdoc;
	close();
}
</script>
</html>