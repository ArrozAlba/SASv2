<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Ordenes de Compra </title>
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
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
    <input name="hidstatus" type="hidden" id="hidstatus">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Ordenes de Compra </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td>&nbsp;</td>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td width="80"><div align="right">N&uacute;mero</div></td>
        <td height="22" colspan="2"><div align="left">
          <input name="txtnumordcom" type="text" id="txtnumordcom">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Proveedor</div></td>
        <td height="22" colspan="2"><div align="left">          <input name="txtcodpro" type="text" id="txtcodpro">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td width="154">
          <div align="left">
          </div></td>
        <td width="264"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Buscar</a></div></td>
      </tr>
    </table>
  <br>
<?php
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_sql.php");
	$in     =new sigesp_include();
	$con    =$in->uf_conectar();
	$io_msg =new class_mensajes();
	$io_fun =new class_funciones();
	$ds     =new class_datastore();
	$io_sql =new class_sql($con);
	
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];

	if (array_key_exists("linea",$_GET))
	{
		$li_linea=$_GET["linea"];
	}
	else
	{
		if(array_key_exists("hidlinea",$_POST))
		{
			$li_linea=$_POST["hidlinea"];
		}
		else
		{
			$li_linea="";
		}
	}
	
	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_numordcom="%".$_POST["txtnumordcom"]."%";
		$ls_cod_pro="%".$_POST["txtcodpro"]."%";
		$ls_status="%".$_POST["hidstatus"]."%";
	}
	else
	{
		$ls_operacion="";
	
	}
	print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	print "<tr class=titulo-celda>";
	print "<td>Número</td>";
	print "<td>Proveedor</td>";
	print "<td>Fecha</td>";
	print "</tr>";
	if($ls_operacion=="BUSCAR")
	{
		$ls_sql="SELECT soc_ordencompra.numordcom,soc_ordencompra.cod_pro,soc_ordencompra.fecordcom, ".
				"      (SELECT nompro FROM rpc_proveedor".
				"        WHERE rpc_proveedor.cod_pro=soc_ordencompra.cod_pro) AS nompro".
				"  FROM soc_ordencompra".
				" WHERE codemp = '".$ls_codemp."'".
				"   AND estpenalm = 0".
				"   AND numordcom LIKE '".$ls_numordcom."'".
				"   AND cod_pro LIKE '".$ls_cod_pro."' AND ".
				"   estcondat = 'B' ".
				"   AND EXISTS".
				"      (SELECT * FROM soc_dt_bienes ".
				"        WHERE soc_ordencompra.numordcom=soc_dt_bienes.numordcom)".
				" GROUP BY soc_ordencompra.numordcom,soc_ordencompra.cod_pro,soc_ordencompra.fecordcom";
		$rs_cta=$io_sql->select($ls_sql);
		$data=$rs_cta;
		if($row=$io_sql->fetch_row($rs_cta))
		{
			$data=$io_sql->obtener_datos($rs_cta);
			$arrcols=array_keys($data);
			$totcol=count($arrcols);
			$ds->data=$data;
	
			$totrow=$ds->getRowCount("numordcom");
		
			for($z=1;$z<=$totrow;$z++)
			{
				print "<tr class=celdas-blancas>";
				$ls_numordcom= $data["numordcom"][$z];
				$ls_codpro=    $data["cod_pro"][$z];
				$ls_nompro=    $data["nompro"][$z];
				$ld_fecordcom= $data["fecordcom"][$z];
				$ld_fecordcom=$io_fun->uf_convertirfecmostrar($ld_fecordcom);
				print "<td><a href=\"javascript: aceptar('$ls_numordcom','$ls_codpro','$ls_nompro');\">".$ls_numordcom."</a></td>";
				print "<td>".$data["cod_pro"][$z]."</td>";
				print "<td>".$ld_fecordcom."</td>";
				print "</tr>";			
			}
		}
		else
		{
			$io_msg->message("No hay registros.");
		}
	}
	print "</table>";
?>
</div>
<input name="hidlinea" type="hidden" id="hidlinea" value="<?php print $li_linea?>">
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
	function aceptar(ls_numordcom,ls_codpro,ls_nompro)
	{
		opener.document.form1.txtnumordcom.value=ls_numordcom;
		opener.document.form1.txtcodpro.value=ls_codpro;
		opener.document.form1.txtdenpro.value=ls_nompro;
		opener.document.form1.hidstatus.value="C";
		opener.document.form1.operacion.value="BUSCARDETALLEORDEN";
		opener.document.form1.hidreadonly.value="readonly";
		opener.document.form1.action="sigesp_siv_p_recepcion.php";
		opener.document.form1.submit();
		close();
	}
	
	function ue_search()
  	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		f.action="sigesp_catdinamic_ordenes.php";
		f.submit();
	}
</script>
</html>
