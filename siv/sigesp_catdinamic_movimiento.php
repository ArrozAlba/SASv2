<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Movimiento</title>
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
    <input name="hidstatus" type="hidden" id="hidstatus">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Movimiento</td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="1" class="formato-blanco" align="center">
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="80"><div align="right">Numero </div></td>
        <td width="418" height="22"><div align="left">
          <input name="txtnummov" type="text" id="txtnummov" size="5" maxlength="3">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Fecha</div></td>
        <td height="22"><div align="left"><input name="txtfecmov" type="text" id="txtfecmov" size="12" maxlength="12" datepicker="true">
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
	require_once("../shared/class_folder/class_funciones.php");
	$in=     new sigesp_include();
	$con=    $in->uf_conectar();
	$ds=     new class_datastore();
	$io_sql= new class_sql($con);
	$io_func=new class_funciones();
	$io_msg= new class_mensajes();
	
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
		$ls_nummov="%".$_POST["txtnummov"]."%";
		$ld_fecmov="%".$_POST["txtfecmov"]."%";
		if ($ld_fecmov!="%%")
		{
			$porc="%";
			$ld_fecmov=str_replace($porc,"",$ld_fecmov);
			$ld_fecmov=$io_func->uf_convertirdatetobd($ld_fecmov);
		}
		$ls_status="%".$_POST["hidstatus"]."%";
	}
	else
	{
		$ls_operacion="";
	
	}
	print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	print "<tr class=titulo-celda>";
	print "<td>Número</td>";
	print "<td>Fecha</td>";
	print "<td width=300>Solicitante</td>";
	print "</tr>";
	if($ls_operacion=="BUSCAR")
	{
		$ls_sql="SELECT * FROM siv_movimiento".
				" WHERE nummov LIKE '".$ls_nummov."'".
				" AND fecmov LIKE '".$ld_fecmov."'";
		$rs_cta=$io_sql->select($ls_sql);
		$data=$rs_cta;
		if($row=$io_sql->fetch_row($rs_cta))
		{
			$data=$io_sql->obtener_datos($rs_cta);
			$arrcols=array_keys($data);
			$totcol=count($arrcols);
			$ds->data=$data;
	
			$totrow=$ds->getRowCount("nummov");
		
			for($z=1;$z<=$totrow;$z++)
			{
				print "<tr class=celdas-blancas>";
				$ls_nummov=$data["nummov"][$z];
				$ld_fecmov=$data["fecmov"][$z];
				$ld_fecmov=$io_func->uf_convertirfecmostrar($ld_fecmov);
				$ls_nomsol=$data["nomsol"][$z];
				$ls_codusu=$data["codusu"][$z];
				print "<td><a href=\"javascript: aceptar('$ls_nummov','$ld_fecmov','$ls_nomsol','$ls_codusu',";
				print "'$ls_status');\">".$ls_nummov."</a></td>";
				print "<td>".$ld_fecmov."</td>";
				print "<td>".$data["nomsol"][$z]."</td>";
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
	function aceptar(ls_nummov,ld_fecmov,ls_nomsol,ls_codusu,hidstatus)
	{ 
		opener.document.form1.txtnummov.value=ls_nummov;
		opener.document.form1.txtfecmov.value=ld_fecmov;
		opener.document.form1.txtnomsol.value=ls_nomsol;
		opener.document.form1.txtcodusu.value=ls_codusu;
		opener.document.form1.hidestatus.value="C";
		opener.document.form1.operacion.value="BUSCARDETALLE";
		opener.document.form1.hidreadonly.value="false";
		opener.document.form1.action="sigesp_siv_p_movimiento.php";
		opener.document.form1.submit();
		close();
	}
	
	function ue_search()
  	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		f.action="sigesp_catdinamic_movimiento.php";
		f.submit();
	}
</script>
<script language="javascript" src="js/js_intra/datepickercontrol.js"></script>
</html>
