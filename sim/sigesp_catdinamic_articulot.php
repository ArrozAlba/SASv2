<?php
session_start();
  //-----------------------------------------------------------------------------------------------------------------------------------
   	function uf_formatonumerico($as_valor)
   	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:     uf_formatonumerico
		//	Arguments:    as_valor  // valor sin formato num�rico
		//	Returns:	  $as_valor valor num�rico formateado
		//	Description:  Funci�n que le da formato a los valores num�ricos que vienen de la BD
		//////////////////////////////////////////////////////////////////////////////
		$as_valor=str_replace(".",",",$as_valor);
		$li_poscoma = strpos($as_valor, ",");
		$li_contador = 1;
		if ($li_poscoma==0)
		{
			$li_poscoma = strlen($as_valor);
			$as_valor = $as_valor.",00";
		}
		$as_valor = substr($as_valor,0,$li_poscoma+3);
		$li_poscoma = $li_poscoma - 1;
		for($li_index=$li_poscoma;$li_index>=0;--$li_index)
		{
			if(($li_contador==3)&&(($li_index-1)>=0))
			{
				$as_valor = substr($as_valor,0,$li_index).".".substr($as_valor,$li_index);
				$li_contador=1;
			}
			else
			{
				$li_contador=$li_contador + 1;
			}
		}
		return $as_valor;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Art&iacute;culo</title>
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
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/class_sql.php");
	$in     =new sigesp_include();
	$con    =$in->uf_conectar();
	$io_msg =new class_mensajes();
	$ds     =new class_datastore();
	$io_sql =new class_sql($con);
	$io_fun =new class_funciones();

	$arre=$_SESSION["la_empresa"];
	$ls_gestor=$_SESSION["ls_gestor"];
	$ls_codemp=$arre["codemp"];
	//print $ls_codemp;

	if (array_key_exists("linea",$_GET))
	{
		$li_linea=  $_GET["linea"];
		$ls_codalm= $_GET["almacen"];
	}
	else
	{
		if(array_key_exists("hidlinea",$_POST))
		{
			$li_linea=  $_POST["hidlinea"];
			$ls_codalm= $_POST["hidcodalm"];
		}
		else
		{
			$li_linea="";
		}
	}

	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_codart="%".$_POST["txtcodart"]."%";
		$ls_denart="%".$_POST["txtdenart"]."%";
		$ls_status="%".$_POST["hidstatus"]."%";

	}
	else
	{
		$ls_operacion="";

	}
?>

<form name="form2" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
    <input name="hidstatus" type="hidden" id="hidstatus">

</p>
  <table width="550" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="550" colspan="2" class="titulo-celda">Cat&aacute;logo del Producto </td>
    </tr>
  </table>
<br>
    <table width="550" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td>&nbsp;</td>
        <td><input name="hidcodalm" type="hidden" id="hidcodalm" value="<?php print $ls_codalm ?>"></td>
      </tr>
      <tr>
        <td width="80"><div align="right">C&oacute;digo</div></td>
        <td width="418" height="22"><div align="left">
          <input name="txtcodart" type="text" id="txtnombre2">
        </div></td>
      </tr>
      <tr>
        <td width="80"><div align="right">Denominaci&oacute;n</div></td>
        <td width="418" height="22"><div align="left">
          <input name="txtdenart" type="text" id="txtdenart">
        </div></td>
      </tr>

      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Buscar</a></div></td>
      </tr>

	</table>
	<br>
<?php
	print "<table width=550 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	print "<tr class=titulo-celda>";
	print "<td width=140>C�digo</td>";
	print "<td>Denominaci�n</td>";
	print "<td>Existencia</td>";
	print "<td>Unidad Operativa de Suministro</td>";
	print "<td>Proveedor</td>";
	print "</tr>";

	if($ls_operacion=="BUSCAR")
	{
		if($ls_gestor=='MYSQL')
		{
			$ls_sql="SELECT sim_dt_movimiento.*,sim_articulo.denart,sim_articulo.codunimed,".
					"      (SELECT unidad FROM sim_unidadmedida".
					"        WHERE sim_unidadmedida.codunimed = sim_articulo.codunimed) unidad,".
					"      (SELECT existencia FROM sim_articuloalmacen".
					"        WHERE sim_dt_movimiento.codart=sim_articuloalmacen.codart ".
					"          AND sim_dt_movimiento.codalm=sim_articuloalmacen.codalm) existencia".
					"  FROM sim_dt_movimiento,sim_articulo".
					" WHERE sim_dt_movimiento.codart=sim_articulo.codart".
					"   AND sim_dt_movimiento.codemp = '".$ls_codemp."'".
					"   AND sim_dt_movimiento.codalm = '".$ls_codalm."'".
					"   AND sim_dt_movimiento.codart ilike '".$ls_codart."'".
					"   AND CONCAT(sim_dt_movimiento.promov,sim_dt_movimiento.numdocori) NOT IN".
					"      (SELECT CONCAT(sim_dt_movimiento.promov,sim_dt_movimiento.numdocori)".
					"         FROM sim_dt_movimiento".
					"        WHERE opeinv ='REV')".
					" GROUP BY codart ";
		}
		else
		{
			$ls_sql="SELECT sim_dt_movimiento.codart,MIN(sim_dt_movimiento.cosart) AS cosart,sim_dt_movimiento.codalm,".
					" sim_articulo.denart,sim_articulo.codunimed,sim_dt_movimiento.codtiend,sim_dt_movimiento.cod_pro," .
					" (SELECT nompro FROM rpc_proveedor".
					"    WHERE rpc_proveedor.cod_pro = sim_dt_movimiento.cod_pro) AS denpro, ".
					"  (SELECT unidad FROM sim_unidadmedida".
					"    WHERE sim_unidadmedida.codunimed = sim_articulo.codunimed) AS unidad,".
					" (SELECT existencia FROM sim_articuloalmacen".
					"   WHERE sim_dt_movimiento.codart=sim_articuloalmacen.codart ".
					" AND sim_dt_movimiento.codalm=sim_articuloalmacen.codalm and sim_articuloalmacen.cod_pro=sim_dt_movimiento.cod_pro" .
					" and sim_dt_movimiento.codtiend=sim_articuloalmacen.codtiend and sim_articuloalmacen.codtiend ilike '".substr($ls_codalm,6,4)."' " .
					" and sim_articuloalmacen.codart ilike '".$ls_codart."' group by sim_articuloalmacen.codart,sim_articuloalmacen.existencia) AS existencia".
					"  FROM sim_dt_movimiento,sim_articulo,sim_articuloalmacen WHERE sim_articuloalmacen.existencia>0".
					"   AND sim_dt_movimiento.codart=sim_articulo.codart AND sim_dt_movimiento.codemp = '".$ls_codemp."'".
					"   AND sim_dt_movimiento.codalm = '".$ls_codalm."'".
					"   AND sim_dt_movimiento.codart ilike '".$ls_codart."' and sim_articulo.denart ilike '".$ls_denart."'".
					"   AND sim_dt_movimiento.codtiend ilike '".substr($ls_codalm,6,4)."'".
					"   AND sim_dt_movimiento.promov || sim_dt_movimiento.numdocori NOT IN".
					"      (SELECT sim_dt_movimiento.promov || sim_dt_movimiento.numdocori".
					"         FROM sim_dt_movimiento".
					"        WHERE opeinv ='REV') and sim_articuloalmacen.codemp=sim_dt_movimiento.codemp and " .
					" sim_articuloalmacen.codart=sim_dt_movimiento.codart and sim_articuloalmacen.codart=sim_articulo.codart " .
					" and sim_articuloalmacen.codalm=sim_dt_movimiento.codalm " .
					" and sim_articuloalmacen.codtiend=sim_dt_movimiento.codtiend and sim_articuloalmacen.cod_pro=sim_dt_movimiento.cod_pro ".
					" GROUP BY sim_dt_movimiento.codart,sim_articulo.denart,sim_articulo.codunimed,".
					"          sim_dt_movimiento.codalm, sim_dt_movimiento.cod_pro,sim_dt_movimiento.codtiend".
					" ORDER BY sim_articulo.denart ASC";

		}

		//print $ls_sql;
		$rs_cta=$io_sql->select($ls_sql);
		$data=$rs_cta;

		if($row=$io_sql->fetch_row($rs_cta))
		{
			$data=$io_sql->obtener_datos($rs_cta);
			$arrcols=array_keys($data);
			$totcol=count($arrcols);
			$ds->data=$data;

			$totrow=$ds->getRowCount("codart");

			for($z=1;$z<=$totrow;$z++)
			{
				print "<tr class=celdas-blancas>";
				$ls_codart=$data["codart"][$z];
				$ls_denart=$data["denart"][$z];
				$li_cosart=$data["cosart"][$z];
				$li_cosart=round($li_cosart,2);
				$li_cosart=uf_formatonumerico($li_cosart);
				$li_unidad=$data["unidad"][$z];
				$li_existencia=$data["existencia"][$z];
				$li_existencia=number_format($li_existencia,2,',','.');
				$ls_codtiend=$data["codtiend"][$z];
				$ls_denproveedor=$data["denpro"][$z];
				$ls_codproveedor=$data["cod_pro"][$z];


				print "<td><a href=\"javascript: aceptar('$ls_codart','$ls_denart','$li_cosart','$li_linea','$li_unidad','$ls_codtiend','$ls_denproveedor','$ls_codproveedor','$li_existencia');\">".$ls_codart."</a></td>";
				print "<td>".$data["denart"][$z]."</td>";
				print "<td>".$li_existencia."</td>";
				print "<td>".$data["codtiend"][$z]."</td>";
				print "<td>".$data["denpro"][$z]."</td>";
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
  <br>
</div>
<input name="hidlinea" type="hidden" id="hidlinea" value="<?php print $li_linea?>">
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
	function aceptar(ls_codart,ls_denart,li_cosart,li_linea,li_unidad,ls_codtiend,ls_denproveedor,ls_codproveedor,li_existencia)
	{

		/*obj=eval("opener.document.form1.txtcodart"+li_linea+"");
		obj.value=ls_codart;


		obj2=eval("opener.document.form1.txtdenart"+li_linea+"");
		obj2.value=ls_denart;

		obj2=eval("opener.document.form1.txtcosuni"+li_linea+"");
		obj2.value=li_cosart;

		obj2=eval("opener.document.form1.txtdenproveedor"+li_linea+"");
		obj2.value=ls_denproveedor;

		obj2=eval("opener.document.form1.txtcodproveedor"+li_linea+"");
		obj2.value=ls_codproveedor;

		obj2=eval("opener.document.form1.hidunidad"+li_linea+"");
		obj2.value=li_unidad;*/

		opener.ue_cargarproducto(ls_codart,ls_denart,li_cosart,li_linea,li_unidad,ls_codtiend,ls_denproveedor,ls_codproveedor,li_existencia);

		close();
	}

	function ue_search()
  	{
		f=document.form2;
		f.operacion.value="BUSCAR";
		f.action="sigesp_catdinamic_articulot.php";
		f.submit();
	}
</script>
</html>
