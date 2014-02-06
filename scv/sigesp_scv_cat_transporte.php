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
<title>Cat&aacute;logo de Transportes de Viaticos </title>
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
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
    <input name="hiddestino" type="hidden" id="hiddestino" value="<?php print $ls_destino; ?>">
    <input name="hidstatus" type="hidden" id="hidstatus">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" height="15" colspan="2" class="titulo-celda">Cat&aacute;logo de Transportes de Viaticos </td>
    </tr>
  </table>
<br>
    <table width="501" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="131" height="18"><div align="right">C&oacute;digo</div></td>
        <td width="368" height="22"><div align="left">
          <input name="txtcodtra" type="text" id="txtcodtra">
        </div>          <div align="right"></div>          <div align="right">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Denominaci&oacute;n</div></td>
        <td height="22"><div align="left">
          <input name="txtdentra" type="text" id="txtdentra">
</div></td>
      </tr>
      <tr>
        <td><div align="right">Tipo</div></td>
        <td height="22"><select name="cmbcodtiptra" id="cmbcodtiptra">
          <option value="%%">-- Seleccione --</option>
          <option value="01">A&eacute;reo</option>
          <option value="02">Mar&iacute;timo</option>
          <option value="03">Terrestre</option>
        </select></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Buscar</a></div></td>
      </tr>
    </table>
  <br>
    <?php
require_once("../shared/class_folder/sigesp_include.php");
$in=     new sigesp_include();
$con=$in->uf_conectar();
require_once("../shared/class_folder/class_mensajes.php");
$io_msg= new class_mensajes();
require_once("../shared/class_folder/class_datastore.php");
$ds=     new class_datastore();
require_once("../shared/class_folder/class_sql.php");
$io_sql= new class_sql($con);
require_once("../shared/class_folder/class_funciones.php");
$io_fun= new class_funciones();
$arr=$_SESSION["la_empresa"];
$ls_codemp=$arr["codemp"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codtra="%".$_POST["txtcodtra"]."%";
	$ls_dentra="%".$_POST["txtdentra"]."%";
	$ls_codtiptra=$_POST["cmbcodtiptra"];
}
else
{
	$ls_operacion="BUSCAR";
	$ls_codtra="%%";
	$ls_codtiptra="%%";
	$ls_dentra="%%";
}
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td width='50'>Código </td>";
print "<td width='50'>Tipo</td>";
print "<td>Denominación</td>";
print "</tr>";

if($ls_operacion=="BUSCAR")
{
	$ls_sql="SELECT * FROM scv_transportes".
			" WHERE codtra LIKE '".$ls_codtra."'".
			"   AND codtiptra LIKE '".$ls_codtiptra."'".
			"   AND dentra LIKE '".$ls_dentra."'";
	$rs_cta=$io_sql->select($ls_sql);
	$data=$rs_cta;
	if($row=$io_sql->fetch_row($rs_cta))
	{
		$data=$io_sql->obtener_datos($rs_cta);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds->data=$data;
		$totrow=$ds->getRowCount("codtra");
		for($z=1;$z<=$totrow;$z++)
		{
			print "<tr class=celdas-blancas>";
			$ls_codtra= $data["codtra"][$z];
			$ls_codtiptra= trim($data["codtiptra"][$z]);
			switch($ls_codtiptra)
			{
				case "01":
					$ls_dentiptra="Aéreo";
				break;
				case "02":
					$ls_dentiptra="Marítimo";
				break;
				case "03":
					$ls_dentiptra="Terrestre";
				break;
			}
			$ls_dentra= trim($data["dentra"][$z]);
			$li_tartra= $data["tartra"][$z];
			$li_tartra=number_format($li_tartra,2,',','.');
			switch($ls_destino)
			{
				case"SOLICITUD":
					print " <td align='center'><a href=\"javascript: aceptar('$ls_codtra','$ls_codtiptra','$ls_dentra');\">".$ls_codtra."</a></td>";
					print "<td>".$ls_dentiptra."</td>";
					print "<td>".$ls_dentra."</td>";
					print "</tr>";			
				break;
				case"DEFINICION":
					print " <td align='center'><a href=\"javascript: aceptar_definicion('$ls_codtra','$ls_codtiptra','$ls_dentra',".
						  "                                                             '$li_tartra');\">".$ls_codtra."</a></td>";
					print "<td>".$ls_dentiptra."</td>";
					print "<td>".$ls_dentra."</td>";
					print "</tr>";			
				break;
			}
		}
	}
	else
	{
	 ?>
	<script language="javascript">
	alert("No se han creado Transportes");
	close();
	</script>
<?php	//$io_msg->message("No hay registros.");
		
	}

}
print "</table>";

?>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">   
	function aceptar(ls_codtra,ls_codtiptra,ls_dentra)
	{
		opener.document.form1.txtcodasi.value=ls_codtra;
		opener.document.form1.txtdenasi.value=ls_dentra;
		opener.document.form1.txtproasi.value= "TRP";
		close();
	}
  
	function aceptar_definicion(ls_codtra,ls_codtiptra,ls_dentra,li_tartra)
	{
		opener.document.form1.txtcodtra.value=ls_codtra;
		opener.document.form1.txtdentra.value=ls_dentra;
		opener.document.form1.cmbcodtiptra.value=ls_codtiptra;
		opener.document.form1.txttartra.value= li_tartra;
		opener.document.form1.existe.value="TRUE";
		opener.document.form1.txttartra.readOnly=true;
		opener.document.form1.hidstatus.value="C";
		close();
	}

	function ue_search()
	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		f.action="sigesp_scv_cat_transporte.php";
		f.submit();
	}
  

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
