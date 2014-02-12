<?php
session_start();
require_once("class_folder/class_funciones_viaticos.php");
$io_fun_viaticos=new class_funciones_viaticos();
if(array_key_exists("hiddestino",$_POST))
{
	$ls_destino=$io_fun_viaticos->uf_obtenervalor("hiddestino","");
}
else
{
	$ls_destino=$io_fun_viaticos->uf_obtenervalor_get("destino","");
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Misiones</title>
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
$conn= $io_conect->uf_conectar();
$io_dsmis= new class_datastore();
$io_sql= new class_sql($conn);
$arr= $_SESSION["la_empresa"];
$ls_sql= " SELECT * FROM scv_misiones".
		 " WHERE codmis<>'----'".
		 " ORDER BY codmis ASC ";
$rs_data= $io_sql->select($ls_sql);
$data= $rs_data;
?>
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
   <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Misiones</td>
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
	$io_dsmis->data= $data;
	$totrow= $io_dsmis->getRowCount("codmis");
	print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	print "<tr class=titulo-celda>";
	print "<td>Código</td>";
	print "<td>Denominación</td>";
	print "</tr>";
	for ($z=1;$z<=$totrow;$z++)
	{
		switch($ls_destino)
		{
			case"SOLICITUD":
				print "<tr class=celdas-blancas>";
				$ls_codmis= $data["codmis"][$z];
				$ls_denmis= $data["denmis"][$z];
				print "<td><a href=\"javascript: aceptar('$ls_codmis','$ls_denmis');\">".$ls_codmis."</a></td>";
				print "<td>".$ls_denmis."</td>";
				print "</tr>";			
			break;

			case"DEFINICION":
				print "<tr class=celdas-blancas>";
				$ls_codmis= $data["codmis"][$z];
				$ls_denmis= $data["denmis"][$z];
				print "<td><a href=\"javascript: aceptar_definicion('$ls_codmis','$ls_denmis');\">".$ls_codmis."</a></td>";
				print "<td>".$ls_denmis."</td>";
				print "</tr>";			
			break;
		}
	}
	print "</table>";
	$io_sql->free_result($rs_data);
}
else
    { ?>
	  <script language="javascript">
	  alert("No se han creado Misiones");
	  close();
	  </script>
	<?php
	}		 

?>
  </div>
</form>
</body>
<script language="JavaScript">
function aceptar(ls_codmis,ls_denmis)
{
	opener.document.form1.txtcodmis.value= ls_codmis;
	opener.document.form1.txtdenmis.value= ls_denmis;
	ls_obssolvia=opener.document.form1.txtobssolvia.value;
	if(ls_obssolvia=="")
	{
		opener.document.form1.txtobssolvia.value= ls_denmis;
	}
	else
	{
		opener.document.form1.txtobssolvia.value= ls_obssolvia+", "+ls_denmis;
	}
	close();
}

function aceptar_definicion(ls_codmis,ls_denmis)
{
	opener.document.form1.txtcodmis.value= ls_codmis;
	opener.document.form1.txtdenmis.value= ls_denmis;
	opener.document.form1.existe.value= "TRUE";
	opener.document.form1.hidestatus.value= "C";
	close();
}
</script>
</html>