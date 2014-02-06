<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo de Conceptos</title>
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
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
   <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Conceptos </td>
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
$ls_sql=" SELECT * ".
        " FROM sep_conceptos ".
		" ORDER BY codconsep ASC";
		
$rs_doc=$io_sql->select($ls_sql);
$data=$rs_doc;
if($row=$io_sql->fetch_row($rs_doc))
{
	$data=$io_sql->obtener_datos($rs_doc);
    $arrcols=array_keys($data);
    $totcol=count($arrcols);
    $io_ds->data=$data;
    $totrow=$io_ds->getRowCount("codconsep");
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla>";
print "<tr class=titulo-celda>";
print "<td>C&oacute;digo </td>";
print "<td>Denominaci&oacute;n</td>";
print "<td>Monto</td>";
print "</tr>";
for($z=1;$z<=$totrow;$z++)
{
	print "<tr class=celdas-blancas>";
	$codigo      =$data["codconsep"][$z];
	$denominacion=$data["denconsep"][$z];
    $monto       =$data["monconsepe"][$z];    
    $observacion =$data["obsconesp"][$z];
    $cuenta      =$data["spg_cuenta"][$z]; 
    $ls_sql=" SELECT denominacion ".
	        " FROM spg_cuentas ".
			" WHERE spg_cuenta='".$cuenta."' ".
			" ORDER BY spg_cuenta ASC";
    $rs=$io_sql->select($ls_sql);  
    if($row=$io_sql->fetch_row($rs))
    {
       $ls_dencon=$row["denominacion"];                  
    }	
       
    $ld_montotot =number_format($monto,2,",",".");    
	print "<td  align=center><a href=\"javascript: aceptar('$codigo','$denominacion','$ld_montotot','$observacion','$cuenta','$ls_dencon');\">".$codigo."</a></td>";
	print "<td  align=left>".$denominacion."</td>";
	print "<td  align=right>".$ld_montotot."</td>";
	print "</tr>";			
}
$io_sql->free_result($rs_doc);
print "</table>";
}
else
{?>
<script language="javascript">
alert("No se han creado Conceptos !!!");
close();
</script>
<?php
}
?>
</div>
  </form>
</body>
<script language="JavaScript">
  function aceptar(codigo,denominacion,monto,observacion,cuenta,dencon)
  {
    opener.document.form1.txtcodigo.value=codigo;
	opener.document.form1.txtcodigo.readOnly=true;
    opener.document.form1.txtdenominacion.value=denominacion;
    opener.document.form1.txtmonto.value=monto;
    opener.document.form1.txtcuenta.value=cuenta;
    opener.document.form1.txtobservacion.value=observacion;
    opener.document.form1.txtdencuenta.value=dencon;
	opener.document.form1.operacion.value="CARGAR";
	opener.document.form1.hidestatus.value="GRABADO";
	opener.document.form1.submit();
	close();
  }
</script>
</html>