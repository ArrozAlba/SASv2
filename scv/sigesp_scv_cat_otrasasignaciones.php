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
<title>Cat&aacute;logo de Otras Asignaciones de Vi&aacute;ticos</title>
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
$ls_sql= " SELECT codotrasi,denotrasi,tarotrasi".
		 "   FROM scv_otrasasignaciones".
		 "  WHERE codemp='".$ls_codemp."'".
		 "  ORDER BY codotrasi ASC ";
$rs_data= $io_sql->select($ls_sql);
$data= $rs_data;
?>
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
   <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Otras Asignaciones de Viáticos</td>
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
	$totrow= $io_dstar->getRowCount("codotrasi");
	print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	print "<tr class=titulo-celda>";
	print "<td>Código</td>";
	print "<td>Concepto</td>";
	print "</tr>";
	for ($z=1;$z<=$totrow;$z++)
	{
		switch($ls_destino)
		{
			case"DEFINICION":
				print "<tr class=celdas-blancas>";
				$ls_codotrasi= $data["codotrasi"][$z];
				$ls_denotrasi= $data["denotrasi"][$z];
				$li_tarotrasi= $data["tarotrasi"][$z];
				$li_tarotrasi=number_format($li_tarotrasi,2,',','.');
				print "<td><a href=\"javascript: aceptar('$ls_codotrasi','$ls_denotrasi','$li_tarotrasi');\">".$ls_codotrasi."</a></td>";
				print "<td>".$ls_denotrasi."</td>";
				print "</tr>";			
			break;

			case"SOLICITUD":
				print "<tr class=celdas-blancas>";
				$ls_codotrasi= $data["codotrasi"][$z];
				$ls_denotrasi= $data["denotrasi"][$z];
				print "<td><a href=\"javascript: aceptar_solicitud('$ls_codotrasi','$ls_denotrasi');\">".$ls_codotrasi."</a></td>";
				print "<td>".$ls_denotrasi."</td>";
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
	alert("No se han creado Otras Asignaciones");
	close();
	</script>
<?php
}		 

?>
  </div>
</form>
</body>
<script language="JavaScript">
function aceptar(ls_codotrasi,ls_denotrasi,li_tarotrasi)
{
	opener.document.form1.txtcodotrasi.value= ls_codotrasi;
	opener.document.form1.txtdenotrasi.value= ls_denotrasi;
	opener.document.form1.txttarotrasi.value= li_tarotrasi;
	opener.document.form1.txttarotrasi.readOnly=true;
	opener.document.form1.hidestatus.value= 'C';
	opener.document.form1.existe.value= 'TRUE';
	close();
}

function aceptar_solicitud(ls_codotrasi,ls_denotrasi)
{
	opener.document.form1.txtcodasi.value= ls_codotrasi;
	opener.document.form1.txtdenasi.value= ls_denotrasi;
	opener.document.form1.txtproasi.value= "TOA";
	opener.document.form1.hidestatus.value= 'C';
	close();
}


</script>
</html>