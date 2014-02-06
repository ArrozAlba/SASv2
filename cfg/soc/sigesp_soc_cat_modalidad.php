<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Modalidad de Clausulas</title>
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
   <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Modalidad de Clausulas</td>
  </tr>
</table>
<br>
<form name="form1" method="post" action=""  >
  <div align="center">
    <?php
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_sql.php");

$io_in=new sigesp_include();
$con=$io_in->uf_conectar();
$io_ds=new class_datastore();
$io_sql=new class_sql($con);
$arr=$_SESSION["la_empresa"];
$ls_sql=" SELECT * FROM soc_modalidadclausulas WHERE codtipmod <>'--' ORDER  BY codtipmod ASC";
$rs_servicio=$io_sql->select($ls_sql);
$data=$rs_servicio;
if($row=$io_sql->fetch_row($rs_servicio))
  {
	$data=$io_sql->obtener_datos($rs_servicio);
    $arrcols=array_keys($data);
    $totcol=count($arrcols);
    $io_ds->data=$data;
    $totrow=$io_ds->getRowCount("codtipmod");
    print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla>";
	print "<tr class=titulo-celda>";
	print "<td>C&oacute;digo </td>";
	print "<td>Denominaci&oacute;n</td>";
	print "</tr>";
	for($z=1;$z<=$totrow;$z++)
	   {
		 print "<tr class=celdas-blancas>";
		 $ls_codigo       =$data["codtipmod"][$z];	
		 $ls_denominacion =$data["denmodcla"][$z];
	 	 print "<td  align=center><a href=\"javascript: aceptar('$ls_codigo','$ls_denominacion');\">".$ls_codigo."</a></td>";
		 print "<td  align=left>".$ls_denominacion."</td>";
		 print "</tr>";			
	   }
	$io_sql->free_result($rs_servicio);
	print "</table>";
}
else
{ 
$totrow=0;
?>
 <script language="javascript">
 alert("No se han creado Modalidades !!!");
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
    opener.document.form1.txtcodigo.readOnly=true;
	opener.document.form1.txtdenominacion.value=denominacion;
	opener.document.form1.operacion.value="CARGAR";
	opener.document.form1.hidestatus.value="GRABADO";
	opener.document.form1.submit(); 
	close();
  }
</script>
</html>
