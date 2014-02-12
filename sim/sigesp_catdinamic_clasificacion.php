<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Clasificaci&oacute;n </title>
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
<?

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

if (array_key_exists("codclades",$_GET))
	{
		$ls_codclades=$_GET["codclades"];
	}
	else
	{
		if(array_key_exists("codclades",$_POST))
		{
			$ls_codclades=$_POST["codclades"];
		}
		else
		{
			$ls_codclades=$_POST["codcla"];
		}
	}


	if (array_key_exists("tienda",$_REQUEST)){
		$ls_tienda = $_REQUEST["tienda"];
	}

	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_codcla="%".$_POST["txtcodcla"]."%";
		$ls_nomcla="%".$_POST["txtnomcla"]."%";
		$ls_status="%".$_POST["hidstatus"]."%";
		$ls_tienda = $_POST["hidtienda"];
		$ls_codclades=$_POST["coddestino"];
	}
	else
	{
		$ls_operacion="";
		$ls_codcla="%%";
		$ls_nomcla="%%";
		$ls_status="%%";
	}

	//print $ls_tienda;

?>
<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
    <input name="hidstatus" type="hidden" id="hidstatus">
    <input name="coddestino" type="hidden" id="coddestino" value="<?php print $ls_codalmdes ?>">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Clasificaci&oacute;n</td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="80"><div align="right">C&oacute;digo</div></td>
        <td width="418" height="22"><div align="left">
          <input name="txtcodcla" type="text" id="txtnombre2">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Clasificaci&oacute;n</div></td>
        <td height="22"><div align="left">          <input name="txtnomcla" type="text" id="txtnomcla">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Buscar</a></div></td>
      </tr>
    </table>
  <br>
<?php
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/class_sql.php");
	$in     =new sigesp_include();
	$con    =$in->uf_conectar();
	$io_msg =new class_mensajes();
	$ds     =new class_datastore();
	$io_sql =new class_sql($con);

	print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	print "<tr class=titulo-celda>";

	print "<td>C&oacute;digo</td>";
	print "<td>Clasificaci&oacute;n</td>";
	print "</tr>";
	if($ls_operacion=="BUSCAR")
	{


		$ls_sql="SELECT codcla,dencla " .
				"FROM sfc_clasificacion".
				" WHERE codcla like '".$ls_codcla."'".
				" AND dencla iLIKE '".$ls_nomcla."'";
       // print $ls_sql."<br>";
		$rs_cta=$io_sql->select($ls_sql);
		$data=$rs_cta;
		if($row=$io_sql->fetch_row($rs_cta))
		{
			$data=$io_sql->obtener_datos($rs_cta);
			$arrcols=array_keys($data);
			$totcol=count($arrcols);
			$ds->data=$data;

			$totrow=$ds->getRowCount("codcla");

			for($z=1;$z<=$totrow;$z++)
			{
				print "<tr class=celdas-blancas>";

				$ls_codcla=    $data["codcla"][$z];
				$ls_nomcla= $data["dencla"][$z];
				
				print "<td><a href=\"javascript: aceptar('$ls_codcla','$ls_nomcla','$ls_status','$li_linea');\">".$ls_codcla."</a></td>";
				print "<td>".$data["dencla"][$z]."</td>";
				print "</tr>";


			}
		}
		else
		{
			$io_msg->message("No hay registros");
		}
	}


	print "</table>";
?>
</div>
<input name="hidlinea" type="hidden" id="hidlinea" value="<?php print $li_linea?>">
<input name="hidtienda" type="hidden" id="hidtienda" value="<?php print $ls_tienda?>">
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
	function aceptar(ls_codcla,ls_dencla,hidstatus,li_linea)
	{

		f=document.form1;
		opener.document.form1.txtcodcla.value=ls_codcla;
		opener.document.form1.txtnomcla.value=ls_dencla;
		close();	

	}

	function ue_search()
  	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		f.action="sigesp_catdinamic_clasificacion.php";
		f.submit();
	}
</script>
</html>
