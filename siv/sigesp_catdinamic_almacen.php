<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Almac&eacute;n </title>
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
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Almac&eacute;n </td>
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
          <input name="txtcodalm" type="text" id="txtnombre2">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Nombre Fiscal </div></td>
        <td height="22"><div align="left">          <input name="txtnomfisalm" type="text" id="txtnomfisalm">
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
		$ls_codalm="%".$_POST["txtcodalm"]."%";
		$ls_nomfisalm="%".$_POST["txtnomfisalm"]."%";
		$ls_status="%".$_POST["hidstatus"]."%";
	}
	else
	{
		$ls_operacion="BUSCAR";
		$ls_codalm="%%";
		$ls_nomfisalm="%%";
		$ls_status="%%";
	}
	print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	print "<tr class=titulo-celda>";
	print "<td>Código</td>";
	print "<td>Nombre Fiscal</td>";
	print "<td>Responsable</td>";
	print "</tr>";
	if($ls_operacion=="BUSCAR")
	{
		$ls_sql="SELECT * FROM siv_almacen".
				" WHERE codemp = '".$ls_codemp."'".
				" AND codalm LIKE '".$ls_codalm."'".
				" AND nomfisalm LIKE '".$ls_nomfisalm."'";
				
		$rs_cta=$io_sql->select($ls_sql);
		$data=$rs_cta;
		if($row=$io_sql->fetch_row($rs_cta))
		{
			$data=$io_sql->obtener_datos($rs_cta);
			$arrcols=array_keys($data);
			$totcol=count($arrcols);
			$ds->data=$data;
	
			$totrow=$ds->getRowCount("codalm");
		
			for($z=1;$z<=$totrow;$z++)
			{
				print "<tr class=celdas-blancas>";
				$ls_codalm=    $data["codalm"][$z];
				$ls_nomfisalm= $data["nomfisalm"][$z];
				$ls_desalm=    $data["desalm"][$z];
				$ls_telalm=    $data["telalm"][$z];
				$ls_ubialm=    $data["ubialm"][$z];
				$ls_nomresalm= $data["nomresalm"][$z];
				$ls_telresalm= $data["telresalm"][$z];
				print "<td><a href=\"javascript: aceptar('$ls_codalm','$ls_nomfisalm','$ls_desalm','$ls_telalm','$ls_ubialm',";
				print "'$ls_nomresalm','$ls_telresalm','$ls_status','$li_linea');\">".$ls_codalm."</a></td>";
				print "<td>".$data["nomfisalm"][$z]."</td>";
				print "<td>".$data["nomresalm"][$z]."</td>";
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
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
	function aceptar(ls_codalm,ls_nomfisalm,ls_desalm,ls_telalm,ls_ubialm,ls_nomresalm,ls_telresalm,hidstatus,li_linea)
	{
		obj=eval("opener.document.form1.txtcodalm"+li_linea+"");
		obj.value=ls_codalm;
		opener.document.form1.txtnomfisalm.value=ls_nomfisalm;
		opener.document.form1.txtdesalm.value=ls_desalm;
		opener.document.form1.txttelalm.value=ls_telalm;
		opener.document.form1.txtubialm.value=ls_ubialm;
		opener.document.form1.txtnomresalm.value=ls_nomresalm;
		opener.document.form1.txttelresalm.value=ls_telresalm;
		opener.document.form1.hidstatus.value="C";
		close();
	}
	
	function ue_search()
  	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		f.action="sigesp_catdinamic_almacen.php";
		f.submit();
	}
</script>
</html>
