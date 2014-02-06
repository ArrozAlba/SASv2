<?php
session_start();
require_once("class_folder/class_funciones_viaticos.php");
$io_fun_viaticos=new class_funciones_viaticos();
$ls_destino=$io_fun_viaticos->uf_obtenervalor_get("destino","");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Tarifas por Distancias</title>
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
$ls_sql= " SELECT scv_tarifakms.*".
		 "   FROM scv_tarifakms".
		 "  WHERE codemp='".$ls_codemp."'".
		 "  ORDER BY codtar ASC ";
$rs_data= $io_sql->select($ls_sql);
$data= $rs_data;
?>
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
   <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Tarifas por Distancias</td>
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
	$totrow= $io_dstar->getRowCount("codtar");
	print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	print "<tr class=titulo-celda>";
	print "<td>Código</td>";
	print "<td>Denominación</td>";
	print "</tr>";
	for ($z=1;$z<=$totrow;$z++)
	{
		print "<tr class=celdas-blancas>";
		$ls_codtar= $data["codtar"][$z];
		$ls_dentar= $data["dentar"][$z];
		$li_kmsdes= $data["kmsdes"][$z];
		$li_kmshas= $data["kmshas"][$z];
		$li_montar= $data["montar"][$z];
		$li_kmsdes= number_format($li_kmsdes,2,",",".");
		$li_kmshas= number_format($li_kmshas,2,",",".");
		$li_montar= number_format($li_montar,2,",",".");
		switch($ls_destino)
		{
			case"SOLICITUD":
				print "<td><a href=\"javascript: aceptar('$ls_codtar','$ls_dentar','$li_kmsdes','$li_kmshas',".
					  "								     '$li_montar');\">".$ls_codtar."</a></td>";
				print "<td>".$ls_dentar."</td>";
				print "</tr>";			
			break;
			case"DEFINICION":
				print "<td><a href=\"javascript: aceptar_definicion('$ls_codtar','$ls_dentar','$li_kmsdes','$li_kmshas',".
					  "								                '$li_montar');\">".$ls_codtar."</a></td>";
				print "<td>".$ls_dentar."</td>";
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
	alert("No se han creado Tarifas");
	close();
	</script>
<?php
}		 

?>
  </div>
</form>
</body>
<script language="JavaScript">
function aceptar(ls_codtar,ls_dentar,li_kmsdes,li_kmshas,li_montar)
{
	opener.document.form1.txtcodasi.value= ls_codtar;
	opener.document.form1.txtdenasi.value= ls_dentar;
	opener.document.form1.txtproasi.value= "TDS";
	close();
}

function aceptar_definicion(ls_codtar,ls_dentar,li_kmsdes,li_kmshas,li_montar)
{
	opener.document.form1.txtcodtar.value= ls_codtar;
	opener.document.form1.txtdentar.value= ls_dentar;
	opener.document.form1.txtkmsdes.value= li_kmsdes;
	opener.document.form1.txtkmshas.value= li_kmshas;
	opener.document.form1.txtmontar.value= li_montar;
	opener.document.form1.txtmontar.readOnly=true;
	opener.document.form1.existe.value="TRUE";
	opener.document.form1.hidestatus.value= 'C';
	close();
}

</script>
</html>