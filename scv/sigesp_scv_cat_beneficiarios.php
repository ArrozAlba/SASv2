<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo de Beneficiarios</title>
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
<?php
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");
require_once("class_folder/class_funciones_viaticos.php");
$io_fun_viaticos=new class_funciones_viaticos();

$io_conect=new sigesp_include();
$con=$io_conect->uf_conectar();
$io_msg=new class_mensajes();
$io_dsbene=new class_datastore();
$io_sql=new class_sql($con);

if (array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_cedula="%".$_POST["txtcedula"]."%";
	$ls_nombre="%".$_POST["txtnombre"]."%";
	$ls_tipo=$_POST["tipo"];
}
else
{
	$ls_operacion="";
	$ls_tipo=$io_fun_viaticos->uf_obtenertipo();
}
?>
<form name="form1" method="post" action="">
  <table width="500" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr class="titulo-celda">
        <td height="22" colspan="2">Cat&aacute;logo de Beneficiarios</td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22"><input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo; ?>"></td>
      </tr>
      <tr>
        <td width="64" height="22"><div align="right">C&eacute;dula</div></td>
        <td height="22"><input name="txtcedula" type="text" id="txtcedula" size="25">        <div align="right"></div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Nombre</div></td>
        <td height="22"><input name="txtnombre" type="text" id="nombre2" size="25">          <div align="right"></div>      </td>
      <tr>
        <td height="22"><div align="right">Apellido</div></td>
        <td height="22"><input name="txtapellido" type="text" id="txtapellido" size="25">      </td>
    <tr>
   <td height="22" colspan="2"><div align="right"><a href="javascript:ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" width="20" height="20" border="0">Buscar Beneficiario</a></div></td>
   </tr>
<input name="operacion" type="hidden" id="operacion"> 
</table> 
</form>      

<?php
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda  height=22>";
print "<td>Cédula </td>";
print "<td>Nombre del Beneficiario</td>";
print "</tr>";

if ($ls_operacion=="BUSCAR")
   {
	$ls_cedbene= "%".$_POST["txtcedula"]."%";
	$ls_nombene= $_POST["txtnombre"];
	$ls_apebene= "%".$_POST["txtapellido"]."%";
	$ls_codemp= $_SESSION["la_empresa"]["codemp"];
	if($ls_tipo="reporte")
	{
		$ls_sql=" SELECT ced_bene,nombene,apebene ".
				"   FROM rpc_beneficiario ".
				"  WHERE codemp='".$ls_codemp."'".
				"    AND ced_bene like '".$ls_cedbene."'".
				"    AND nombene like '%".$ls_nombene."%'".
				"    AND apebene like '".$ls_apebene."'".
				"    AND ced_bene<>'----------'".
				"  ORDER BY ced_bene ASC";
	}
	else
	{
		$ls_sql=" SELECT ced_bene,nombene,apebene ".
				"   FROM rpc_beneficiario ".
				"  WHERE codemp='".$ls_codemp."'".
				"    AND ced_bene like '".$ls_cedbene."'".
				"    AND nombene like '%".$ls_nombene."%'".
				"    AND apebene like '".$ls_apebene."'".
				"    AND ced_bene<>'----------'".
				"    AND ced_bene NOT IN (SELECT cedper".
				"                           FROM sno_personal".
				"                          WHERE rpc_beneficiario.codemp=sno_personal.codemp".
				"                            AND rpc_beneficiario.ced_bene=sno_personal.cedper)";
				"  ORDER BY ced_bene ASC";
	}
	$rs_bene=$io_sql->select($ls_sql);
	$data=$rs_bene;
    if ($row=$io_sql->fetch_row($rs_bene))
	{
	    $data=$io_sql->obtener_datos($rs_bene);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$io_dsbene->data=$data;
		$totrow=$io_dsbene->getRowCount("ced_bene");
		for ($z=1;$z<=$totrow;$z++)
		{
			print "<tr class=celdas-blancas>";
			$ls_cedbene  = $data["ced_bene"][$z];
			$ls_nombene   = $data["nombene"][$z];
			$ls_apebene  = $data["apebene"][$z];
			if (!empty($ls_apebene) && $ls_apebene!='.')
			   {
			     $ls_nombene = $ls_nombene.", ".$ls_apebene.".";
			   }
			print "<td  style=text-align:center><a href=\"javascript: aceptar('$ls_cedbene','$ls_nombene','$ls_apebene');\">".$ls_cedbene."</a></td>";
			print "<td  style=text-align:left>".$ls_nombene."</td>";
			print "</tr>";			
		}
	print "</table>";
	}
	else
	{
	 ?>
	 <script language="javascript">
		alert("No se han encontrado registros para esta Búsqueda");
	 </script>
	 <?php	
	}  
}
print "</table>";
?>
</body>

<script language="JavaScript">
	function aceptar(cedula,nombre,apellido)
	{
		opener.document.form1.txtcodben.value=cedula;
		opener.document.form1.txtnomben.value=nombre;
		opener.document.form1.txtcedben.value=cedula;
		close();
	}
  
	function ue_search()
	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		f.action="sigesp_scv_cat_beneficiarios.php";
		f.submit();
	}
</script>
</html>