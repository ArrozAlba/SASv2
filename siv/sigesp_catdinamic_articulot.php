<?php
session_start();
  //-----------------------------------------------------------------------------------------------------------------------------------
   	function uf_formatonumerico($as_valor)
   	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:     uf_formatonumerico
		//	Arguments:    as_valor  // valor sin formato numérico
		//	Returns:	  $as_valor valor numérico formateado
		//	Description:  Función que le da formato a los valores numéricos que vienen de la BD
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
		$ls_status="%".$_POST["hidstatus"]."%";
	}
	else
	{
		$ls_operacion="";
	
	}
?>

<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
    <input name="hidstatus" type="hidden" id="hidstatus">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Art&iacute;culo</td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td>&nbsp;</td>
        <td><input name="hidcodalm" type="hidden" id="hidcodalm" value="<?php print $ls_codalm ?>"></td>
      </tr>
      <tr>
        <td width="80"><div align="right">C&oacute;digo</div></td>
        <td width="418"><div align="left">
          <input name="txtcodart" type="text" id="txtnombre2">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Buscar</a></div></td>
      </tr>
    </table>
<?php
	print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	print "<tr class=titulo-celda>";
	print "<td width=140>Código</td>";
	print "<td>Denominacion</td>";
	print "<td>Existencia</td>";
	print "</tr>";
	if($ls_operacion=="BUSCAR")
	{
		if($ls_gestor=='MYSQLT')
		{
			$ls_sql="SELECT siv_dt_movimiento.*,siv_articulo.denart,siv_articulo.codunimed,".
					"      (SELECT unidad FROM siv_unidadmedida".
					"        WHERE siv_unidadmedida.codunimed = siv_articulo.codunimed) unidad,".
					"      (SELECT existencia FROM siv_articuloalmacen".
					"        WHERE siv_dt_movimiento.codart=siv_articuloalmacen.codart ".
					"          AND siv_dt_movimiento.codalm=siv_articuloalmacen.codalm) existencia".
					"  FROM siv_dt_movimiento,siv_articulo".
					" WHERE siv_dt_movimiento.codart=siv_articulo.codart".
					"   AND siv_dt_movimiento.codemp = '".$ls_codemp."'".
					"   AND siv_dt_movimiento.codalm = '".$ls_codalm."'".
					"   AND siv_dt_movimiento.codart like '".$ls_codart."'".
					"   AND CONCAT(siv_dt_movimiento.promov,siv_dt_movimiento.numdocori) NOT IN".
					"      (SELECT CONCAT(siv_dt_movimiento.promov,siv_dt_movimiento.numdocori)".
					"         FROM siv_dt_movimiento".
					"        WHERE opeinv ='REV')".
					" GROUP BY codart ";
		}
		else
		{
			$ls_sql="SELECT siv_dt_movimiento.codart,MIN(siv_dt_movimiento.cosart) AS cosart,siv_dt_movimiento.codalm,".
					"       siv_articulo.denart,siv_articulo.codunimed,".
					"      (SELECT unidad FROM siv_unidadmedida".
					"        WHERE siv_unidadmedida.codunimed = siv_articulo.codunimed) AS unidad,".
					"      (SELECT existencia FROM siv_articuloalmacen".
					"        WHERE siv_dt_movimiento.codart=siv_articuloalmacen.codart ".
					"          AND siv_dt_movimiento.codalm=siv_articuloalmacen.codalm) AS existencia".
					"  FROM siv_dt_movimiento,siv_articulo".
					" WHERE siv_dt_movimiento.codart=siv_articulo.codart".
					"   AND siv_dt_movimiento.codemp = '".$ls_codemp."'".
					"   AND siv_dt_movimiento.codalm = '".$ls_codalm."'".
					"   AND siv_dt_movimiento.codart like '".$ls_codart."'".
					"   AND siv_dt_movimiento.promov || siv_dt_movimiento.numdocori NOT IN".
					"      (SELECT siv_dt_movimiento.promov || siv_dt_movimiento.numdocori".
					"         FROM siv_dt_movimiento".
					"        WHERE opeinv ='REV')".
					" GROUP BY siv_dt_movimiento.codart,siv_articulo.denart,siv_articulo.codunimed,".
					"          siv_dt_movimiento.codalm ".
					" ORDER BY siv_dt_movimiento.codart";
		}
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
			
				print "<td><a href=\"javascript: aceptar('$ls_codart','$ls_denart','$li_cosart','$li_linea','$li_unidad');\">".$ls_codart."</a></td>";
				print "<td>".$data["denart"][$z]."</td>";
				print "<td>".$li_existencia."</td>";
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
	function aceptar(ls_codart,ls_denart,li_cosart,li_linea,li_unidad)
	{
		obj=eval("opener.document.form1.txtcodart"+li_linea+"");
		obj.value=ls_codart;
		obj1=eval("opener.document.form1.txtdenart"+li_linea+"");
		obj1.value=ls_denart;
		obj1=eval("opener.document.form1.txtcosuni"+li_linea+"");
		obj1.value=li_cosart;
		obj1=eval("opener.document.form1.hidunidad"+li_linea+"");
		obj1.value=li_unidad;
		close();
	}
	
	function ue_search()
  	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		f.action="sigesp_catdinamic_articulot.php";
		f.submit();
	}
</script>
</html>
