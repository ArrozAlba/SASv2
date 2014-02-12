<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo Tipo Organización</title>
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
      <td width="496" colspan="2" class="titulo-celda"> Tipo de Empresa</td>
    </tr>
</table>
  <div align="center"><br>
    <?php
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");

$in=new sigesp_include();
$conn=$in->uf_conectar();
$io_dstiporg=new class_datastore();
$io_sql=new class_sql($conn);
$arr=$_SESSION["la_empresa"];

$ls_sql=" SELECT codtipoorg,dentipoorg ".
        " FROM rpc_tipo_organizacion WHERE codtipoorg<>'--'".
		" ORDER BY codtipoorg ASC";

$rs_tipoorg=$io_sql->select($ls_sql);
$data=$rs_tipoorg;
if($row=$io_sql->fetch_row($rs_tipoorg))
{
     $data=$io_sql->obtener_datos($rs_tipoorg);
	 $arrcols=array_keys($data);
     $totcol=count($arrcols);
     $io_dstiporg->data=$data;
     $totrow=$io_dstiporg->getRowCount("codtipoorg");
     print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla>";
	 print "<tr class=titulo-celda>";
	 print "<td>Código</td>";
	 print "<td>Denominación</td>";
	 print "</tr>";
 	 for($z=1;$z<=$totrow;$z++)
     {
		print "<tr class=celdas-blancas>";
		$codigo=$data["codtipoorg"][$z];
		$denominacion=$data["dentipoorg"][$z];
		print "<td><a href=\"javascript: aceptar('$codigo','$denominacion');\">".$codigo."</a></td>";
		print "<td>".$denominacion."</td>";
		print "</tr>";			
     }
     print "</table>";
     $io_sql->free_result($rs_tipoorg);
}
else
{ ?>
 <script language="javascript">
 alert("No se han creados Tipos de Empresa !!!");
 close();
 </script>
<?php
}
?>
  </div>
</body>
<script language="JavaScript">
  function aceptar(codigo,denominacion)
  {
    opener.document.form1.txtcodigo.value=codigo;
	opener.document.form1.txtcodigo.readOnly=true;
    opener.document.form1.txtdenominacion.value=denominacion;
    opener.document.form1.hidestatus.value="GRABADO";
	close();
  }
</script>
</html>