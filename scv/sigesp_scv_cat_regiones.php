<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Regiones</title>
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
$io_conect= new sigesp_include();
$conn= $io_conect->uf_conectar();
require_once("../shared/class_folder/class_datastore.php");
$io_sql= new class_sql($conn);
require_once("../shared/class_folder/class_sql.php");
$io_dsclasi= new class_datastore();

if (array_key_exists("catalogo",$_GET))
{$ls_catalogo= $_GET["catalogo"];}
else
{$ls_catalogo="";}
if (array_key_exists("hidpais",$_POST))
{
	$ls_codpai = $_POST["hidpais"];   
}
else
{
	$ls_codpai = $_GET["hidpais"];
}
$ls_sql= "SELECT * FROM scv_regiones".
		 " WHERE    codreg<>'---'".
		 " AND      codpai='".$ls_codpai."'".
		 " ORDER BY codreg ASC ";
$rs_data= $io_sql->select($ls_sql);
$data= $rs_data;
?>
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
   <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Regiones </td>
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
     $io_dsclasi->data= $data;
     $totrow= $io_dsclasi->getRowCount("codreg");
     print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	 print "<tr class=titulo-celda>";
	 print "<td>Código</td>";
	 print "<td>Denominación</td>";
 	 print "</tr>";
	 for ($z=1;$z<=$totrow;$z++)
         {
			print "<tr class=celdas-blancas>";
			$ls_codigo       = $data["codreg"][$z];
			$ls_denominacion = $data["denreg"][$z];
			print "<td><a href=\"javascript: aceptar('$ls_codigo','$ls_denominacion','$ls_catalogo');\">".$ls_codigo."</a></td>";
			print "<td>".$ls_denominacion."</td>";
			print "</tr>";			
         }
	 print "</table>";
     $io_sql->free_result($rs_data);
   }
else
   { ?>
	  <script language="javascript">
	  alert("No se han creado Regiones para este País");
	  close();
	  </script>
	  <?php
   }		 

?>
  </div>
</form>
</body>
<script language="JavaScript">
function aceptar(codigo,denominacion,catalogo)
{
	f= opener.document.form1;
	f.txtcodreg.value= codigo;
	f.txtdenreg.value= denominacion;
	f.existe.value= "TRUE";
	if(catalogo!=1)
	{
		f.hidestatus.value= 'GRABADO';
		f.operacion.value= 'CARGAR';
		f.submit();
	}
	close();
}
</script>
</html>