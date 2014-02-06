<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Par&aacute;metros de Calificaci&oacute;n de Proveedores</title>
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
<link href="../shared/css/general.css"  rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css"   rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");

$io_conect=new sigesp_include();
$conn=$io_conect->uf_conectar();
$io_dsclasi=new class_datastore();
$io_sql=new class_sql($conn);
$arr=$_SESSION["la_empresa"];
$ls_sql=" SELECT codclas,denclas ".
        " FROM rpc_clasificacion WHERE codclas<>'--'".
		" ORDER BY codclas ASC ";
$rs_clas=$io_sql->select($ls_sql);
$data=$rs_clas;
?>
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
   <td width="496" height="22" colspan="2" class="titulo-celda">Par&aacute;metros de Calificaci&oacute;n de Proveedores </td>
  </tr>
</table>
  <br>
<form name="form1" method="post" action="">
  <div align="center">
    <?php
if ($row=$io_sql->fetch_row($rs_clas))
   {
     $data=$io_sql->obtener_datos($rs_clas);
	 $arrcols=array_keys($data);
     $totcol=count($arrcols);
     $io_dsclasi->data=$data;
     $totrow=$io_dsclasi->getRowCount("codclas");
     print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	 print "<tr class=titulo-celda>";
	 print "<td>Código</td>";
	 print "<td>Denominación</td>";
 	 print "</tr>";
	 for ($z=1;$z<=$totrow;$z++)
         {
			print "<tr class=celdas-blancas>";
			$codigo=$data["codclas"][$z];
			$denominacion=$data["denclas"][$z];
			print "<td><a href=\"javascript: aceptar('$codigo','$denominacion');\">".$codigo."</a></td>";
			print "<td>".$denominacion."</td>";
			print "</tr>";			
         }
	print "</table>";
    $io_sql->free_result($rs_clas);
	}
else
    { ?>
	  <script language="javascript">
	  alert("No se han creado Clasificaciones !!!");
	  close();
	  </script>
	<?php
	}		 

?>
  </div>
</form>
</body>
<script language="JavaScript">
function aceptar(codigo,denominacion)
{
	opener.document.form1.txtcodigo.value=codigo;
	opener.document.form1.txtdenominacion.value=denominacion;
	close();
}
</script>
</html>