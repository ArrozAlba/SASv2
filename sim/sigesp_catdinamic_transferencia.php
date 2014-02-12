<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Transferencias</title>
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
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/funciones.js"></script>
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
    <input name="hidstatus" type="hidden" id="hidstatus">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Transferencias </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="1" class="formato-blanco" align="center">

    <td width="121" ><input name="txtcodtie" type="hidden" id="txtcodtie" value="<? print $ls_codtie?>" size="5" maxlength="4"></td>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
     <tr>
       <td width="121" height="33"><div align="right">Unidad Operativa de Suministro</div></td>
        <td> <input name="txtdestienda" type="text" id="txtdestienda"  value="<? print $ls_destienda?>" size="50" maxlength="50"><a href="javascript:ue_buscartienda();">
        <img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></td>

      </tr>


      <tr>
        <td width="121"><div align="right">Numero </div></td>
        <td width="370" height="22"><div align="left">
          <input name="txtnumtra" type="text" id="txtnumtra" maxlength="15">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Fecha</div></td>
        <td height="22"><div align="left"><input name="txtfecemi" type="text" id="txtfecemi" size="20" maxlength="12" datepicker="true">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Buscar</a></div></td>
      </tr>
  </table>
  <br>
<?php
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/class_sql.php");
	$in=     new sigesp_include();
	$con=    $in->uf_conectar();
	$ds=     new class_datastore();
	$io_sql= new class_sql($con);
	$io_func=new class_funciones();
	$io_msg= new class_mensajes();

	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];

	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_numtra="%".$_POST["txtnumtra"]."%";
		$ld_fecemi=$_POST["txtfecemi"];
		if ($ld_fecemi!="")
		{
			$porc="%";
			$ld_fecemi=str_replace($porc,"",$ld_fecemi);
			$ld_fecemi=$io_func->uf_convertirdatetobd($ld_fecemi);
		}
		$ls_status="%".$_POST["hidstatus"]."%";
		$ls_codtiend=$_POST["txtcodtie"];
		$ls_destienda=$_POST["txtdestienda"];
	}
	else
	{
		$ls_operacion="";

	}
	print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	print "<tr class=titulo-celda>";
	print "<td width=50>N�mero</td>";
	print "<td width=100>Unidad Operativa de Suministro</td>";
	print "<td width=50>Fecha</td>";
	print "<td width=140>Origen</td>";
	print "<td width=140>Destino</td>";
	print "</tr>";
	if($ls_operacion=="BUSCAR")
	{
		if($ld_fecemi!="")
		{
			$ls_aux_fec=" AND fecemi ='".$ld_fecemi."' ";
		}
		$ls_sql="SELECT sim_transferencia.*, ".
				"      (SELECT nomfisalm FROM sim_almacen".
				"        WHERE sim_almacen.codalm = sim_transferencia.codalmori) AS nomfisori,".
				"      (SELECT nomfisalm FROM sim_almacen".
				"        WHERE sim_almacen.codalm = sim_transferencia.codalmdes) AS nomfisdes," .
				"      (SELECT dentie from sfc_tienda where codtiend=substr(sim_transferencia.codalmori,7,4)) AS tiendaori," .
				"      (SELECT dentie from sfc_tienda where codtiend=substr(sim_transferencia.codalmdes,7,4)) AS tiendades ".
			    "  FROM	sim_transferencia".
				" WHERE codemp = '".$ls_codemp."'".
				"   AND numtra ilike '".$ls_numtra."'".
				$ls_aux_fec." AND codtiend ilike '%".$ls_codtiend."%' ";

		$rs_cta=$io_sql->select($ls_sql);
		$data=$rs_cta;
		if($row=$io_sql->fetch_row($rs_cta))
		{
			$data=$io_sql->obtener_datos($rs_cta);
			$arrcols=array_keys($data);
			$totcol=count($arrcols);
			$ds->data=$data;

			$totrow=$ds->getRowCount("numtra");

			for($z=1;$z<=$totrow;$z++)
			{
				print "<tr class=celdas-blancas>";
				$ls_numtra=    $data["numtra"][$z];
				$ld_fecemi=    $data["fecemi"][$z];
				$ld_fecemi=    $io_func->uf_convertirfecmostrar($ld_fecemi);
				$ls_obstra=    $data["obstra"][$z];
				$ls_codusu=    $data["codusu"][$z];
				$ls_codalmori= $data["codalmori"][$z];
				$ls_codalmdes= $data["codalmdes"][$z];
				$ls_nomfisori= $data["nomfisori"][$z];
				$ls_nomfisdes= $data["nomfisdes"][$z];
				$ls_tiendaori= $data["tiendaori"][$z];
				$ls_tiendades= $data["tiendades"][$z];
				if($ls_destienda=="")
				{
					$ls_destienda=$ls_tiendaori;
				}
				print "<td><a href=\"javascript: aceptar('$ls_numtra','$ld_fecemi','$ls_obstra','$ls_codusu','$ls_codalmori','$ls_codalmdes',";
				print "'$ls_nomfisori','$ls_nomfisdes','$ls_status','$ls_tiendaori','$ls_tiendades');\">".$ls_numtra."</a></td>";
				print "<td>".$ls_destienda."</td>";
				print "<td>".$ld_fecemi."</td>";
				print "<td>".$data["nomfisori"][$z]."</td>";
				print "<td>".$data["nomfisdes"][$z]."</td>";
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
	function aceptar(ls_numtra,ld_fecemi,ls_obstra,ls_codusu,ls_codalmori,ls_codalmdes,ls_nomfisori,ls_nomfisdes,hidstatus,ls_tiendaori,ls_tiendades)
	{
		opener.document.form1.txtnumtra.value=ls_numtra;
		opener.document.form1.txtfecemi.value=ld_fecemi;
		opener.document.form1.txtcodusu.value=ls_codusu;
		opener.document.form1.txtcodalm.value=ls_codalmori;
		opener.document.form1.txtcodalmdes.value=ls_codalmdes;
		opener.document.form1.txtnomfisalm.value=ls_nomfisori+" - "+ls_tiendaori;
		opener.document.form1.txtnomfisdes.value=ls_nomfisdes+" - "+ls_tiendades;
		opener.document.form1.txtobstra.value=ls_obstra;
		opener.document.form1.hidestatus.value="C";
		opener.document.form1.operacion.value="BUSCARDETALLE";
		opener.document.form1.hidreadonly.value="false";
		opener.document.form1.action="sigesp_sim_p_transferencia.php";
		opener.document.form1.submit();
		close();
	}

	function ue_search()
  	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		f.action="sigesp_catdinamic_transferencia.php";
		f.submit();
	}


/**********************************************************************/

	function ue_buscartienda()
		{
            f=document.form1;

			f.operacion.value="";
			pagina="sigesp_cat_tienda.php";
			popupWin(pagina,"catalogo_tiendas",600,250);




		}

/**********************************************************************/

		function ue_cargartienda(codtie,nomtie,dirtie,teltie,riftie,codpai,codest,codmun,codpar,items,spi_cuenta,denominacion,codunidad,denunidad,cuentaiva,deniva)
		{
			f=document.form1;

			f.txtcodtie.value=codtie;
            f.txtdestienda.value=nomtie;


		}





</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
