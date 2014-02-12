<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Entradas de Suministros a Almac&eacute;n </title>
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
<link href="js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
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
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Entradas de Suministros a Almac&eacute;n </td>
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
          <input name="txtnumordcom" type="text" id="txtnumordcom" size="15" maxlength="15">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Almac&eacute;n</div></td>
        <td height="22"><div align="left">
          <input name="txtcodalm" type="text" id="txtcodalm" size="11" maxlength="10">
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
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/class_sql.php");
	$in=     new sigesp_include();
	$con=    $in->uf_conectar();
	$ds=     new class_datastore();
	$io_sql= new class_sql($con);
	$io_func=new class_funciones();
	$io_msg= new class_mensajes();
	$io_fun= new class_funciones();
	
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
		$ls_codalm="%".$_POST["txtcodalm"]."%";
		$ls_status="%".$_POST["hidstatus"]."%";
	}
	else
	{
		$ls_operacion="";
	
	}
	print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	print "<tr class=titulo-celda>";
	print "<td width=100>Compra/Factura</td>";
	print "<td width=50 align='center'>Proveedor</td>";
	print "<td width=140>Almacén</td>";
	print "</tr>";
	if($ls_operacion=="BUSCAR")
	{
		$ls_sql="SELECT siv_recepcion.*, ".
				"      (SELECT nomfisalm FROM siv_almacen".
				"        WHERE siv_almacen.codalm = siv_recepcion.codalm) AS nomfisalm,".
				"      (SELECT nompro FROM rpc_proveedor".
				"        WHERE rpc_proveedor.cod_pro = siv_recepcion.cod_pro) AS nompro".
			    "  FROM siv_recepcion".
				" WHERE codemp = '".$ls_codemp."'".
				"   AND estrevrec = 1".
				"   AND numordcom like '".$ls_numordcom."'".
				"   AND codalm like '".$ls_codalm."'".
				" ORDER BY numordcom";
				
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
				$ls_numconrec= $data["numconrec"][$z];
				$ls_numordcom= $data["numordcom"][$z];
				$ls_obsrec=    $data["obsrec"][$z];
				$ls_codpro=    $data["cod_pro"][$z];
				$ls_nompro=    $data["nompro"][$z];
				$ls_codalm=    $data["codalm"][$z];
				$ls_nomfisalm= $data["nomfisalm"][$z];
				$ls_estpro=    $data["estpro"][$z];
				$ls_estrec=    $data["estrec"][$z];
				$ld_fecrec=    $data["fecrec"][$z];
				$ld_fecrec=    $io_fun->uf_convertirfecmostrar($ld_fecrec);
				print "<td><a href=\"javascript: aceptar('$ls_numconrec','$ls_numordcom','$ls_obsrec','$ls_codpro','$ls_nompro','$ls_codalm','$ls_nomfisalm',";
				print "'$ls_estpro','$ls_estrec','$ld_fecrec');\">".$ls_numordcom."</a></td>";
				print "<td>".$data["nompro"][$z]."</td>";
				print "<td>".$data["nomfisalm"][$z]."</td>";
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
	function aceptar(ls_numconrec,ls_numordcom,ls_obsrec,ls_codpro,ls_nompro,ls_codalm,ls_nomfisalm,ls_estpro,ls_estrec,ld_fecrec)
	{ 
		opener.document.form1.txtnumordcom.value=ls_numordcom;
		/*opener.document.form1.txtnumconrec.value=ls_numconrec;
		opener.document.form1.txtcodpro.value=ls_codpro;
		opener.document.form1.txtdenpro.value=ls_nompro;
		opener.document.form1.txtcodalm.value=ls_codalm;
		opener.document.form1.txtnomfisalm.value=ls_nomfisalm;
		opener.document.form1.txtobsrec.value=ls_obsrec;
		opener.document.form1.txtfecrec.value=ld_fecrec;
		opener.document.form1.hidestatus.value="C";
		if(ls_estpro==1)
		{
			opener.document.form1.radiotipo[1].checked= true;
		}
		else
		{
			opener.document.form1.radiotipo[0].checked= true;
		}

		if(ls_estrec==0)
		{
			opener.document.form1.radiotipentrega[1].checked= true;
		}
		else
		{
			opener.document.form1.radiotipentrega[0].checked= true;
		}
		opener.document.form1.operacion.value="BUSCARDETALLE";
		opener.document.form1.hidreadonly.value="false";
		opener.document.form1.action="sigesp_siv_p_recepcion.php";
		opener.document.form1.submit();*/
		close();
	}
	
	function ue_search()
  	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		f.action="sigesp_cat_recepcion.php";
		f.submit();
	}
</script>
<script language="javascript" src="js/js_intra/datepickercontrol.js"></script>
</html>