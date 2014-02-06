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
<title>Cat&aacute;logo de Tarifas de Vi&aacute;ticos</title>
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
$ls_sql= " SELECT scv_tarifas.*,".
		 "        (SELECT despai FROM sigesp_pais".
		 "          WHERE scv_tarifas.codpai=sigesp_pais.codpai) as despai,".
		 "        (SELECT desnom FROM sno_nomina".
		 "          WHERE scv_tarifas.codemp=sno_nomina.codemp".
		 "			  AND scv_tarifas.codnom=sno_nomina.codnom) as desnom,".
		 "        (SELECT dencat FROM scv_categorias".
		 "          WHERE scv_tarifas.codemp=scv_categorias.codemp".
		 "            AND scv_tarifas.codcat=scv_categorias.codcat) as dencat,".
		 "        (SELECT denreg FROM scv_regiones".
		 "          WHERE scv_tarifas.codreg=scv_regiones.codreg) as denreg".
		 "   FROM scv_tarifas".
		 "  WHERE codemp='".$ls_codemp."'".
		 "    AND   codtar<>'--' ".
		 "  ORDER BY codtar ASC ";
$rs_data= $io_sql->select($ls_sql);
$data= $rs_data;
?>
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
   <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Tarifas de Viáticos</td>
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
		switch($ls_destino)
		{
			case"SOLICITUD":
				print "<tr class=celdas-blancas>";
				$ls_codtar= $data["codtar"][$z];
				$ls_dentar= $data["dentar"][$z];
				$ls_codpai= $data["codpai"][$z];
				$ls_despai= $data["despai"][$z];
				$ls_codreg= $data["codreg"][$z];
				$ls_denreg= $data["denreg"][$z];
				$li_monbol= $data["monbol"][$z];
				$li_mondol= $data["mondol"][$z];
				$li_monpas= $data["monpas"][$z];
				$li_monhos= $data["monhos"][$z];
				$li_monali= $data["monali"][$z];
				$li_monmov= $data["monmov"][$z];
				$li_exterior=$data["nacext"][$z];
				print "<td><a href=\"javascript: aceptar('$ls_codtar','$ls_dentar','$ls_codpai','$ls_despai','$ls_codreg',".
					  "                                  '$li_monbol','$li_mondol','$li_monpas','$li_monhos','$li_monali',".
					  "                                  '$li_monmov','$li_exterior');\">".$ls_codtar."</a></td>";
				print "<td>".$ls_dentar."</td>";
				print "</tr>";			
			break;

			case"DEFINICION":
				print "<tr class=celdas-blancas>";
				$ls_codtar= $data["codtar"][$z];
				$ls_dentar= $data["dentar"][$z];
				$ls_codpai= $data["codpai"][$z];
				$ls_despai= $data["despai"][$z];
				$ls_codreg= $data["codreg"][$z];
				$ls_denreg= $data["denreg"][$z];
				$ls_codcat= $data["codcat"][$z];
				$ls_dencat= $data["dencat"][$z];
				$ls_codnom= $data["codnom"][$z];
				$ls_desnom= $data["desnom"][$z];
				print "<td><a href=\"javascript: aceptar_definicion('$ls_codtar','$ls_dentar','$ls_codpai','$ls_despai','$ls_codreg',".
					  "                                             '$ls_denreg','$ls_codcat','$ls_dencat','$ls_codnom','$ls_desnom')".
					  "                                             ;\">".$ls_codtar."</a></td>";
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
	alert("No se han creado Tarifas de Viáticos");
	close();
	</script>
<?php
}		 

?>
  </div>
</form>
</body>
<script language="JavaScript">
function aceptar(ls_codtar,ls_dentar,ls_codpai,ls_despai,ls_codreg,li_monbol,li_mondol,li_monpas,li_monhos,li_monali,
				 li_monmov,li_exterior)
{
	opener.document.form1.txtcodasi.value= ls_codtar;
	opener.document.form1.txtdenasi.value= ls_dentar;
	opener.document.form1.txtproasi.value= "TVS";
	close();
}

function aceptar_definicion(ls_codtar,ls_dentar,ls_codpai,ls_despai,ls_codreg,ls_denreg,ls_codcat,ls_dencat,ls_codnom,ls_desnom)
{
	opener.document.form1.txtcodtar.value= ls_codtar;
	opener.document.form1.txtdentar.value= ls_dentar;
	opener.document.form1.txtcodnom.value= ls_codnom;
	opener.document.form1.txtdesnom.value= ls_desnom;
	opener.document.form1.txtcodcat.value= ls_codcat;
	opener.document.form1.txtdencat.value= ls_dencat;
	opener.document.form1.hidestatustar.value= 'C';
	opener.document.form1.operacion.value="BUSCAR";
	opener.document.form1.existe.value="TRUE";
	opener.document.form1.submit();
	close();
}

</script>
</html>