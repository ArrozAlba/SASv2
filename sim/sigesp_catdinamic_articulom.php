<?php
	session_start();
	if(array_key_exists("coddestino",$_GET))
	{
		$ls_coddestino=$_GET["coddestino"];
		$ls_dendestino=$_GET["dendestino"];
	}
	else
	{
		$ls_coddestino="txtcodart";
		$ls_dendestino="txtdenart";
	}
	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_codart="%".$_POST["txtcodart"]."%";
		$ls_denart="%".$_POST["txtdenart"]."%";
		
		$ls_status="%".$_POST["hidstatus"]."%";
		$ls_coddestino=$_POST["hidcoddestino"];
		$ls_dendestino=$_POST["hiddendestino"];
	}
	else
	{
		$ls_operacion="";
	
	}?>
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
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
    <input name="hidstatus" type="hidden" id="hidstatus">
    <input name="hidcoddestino" type="hidden" id="hidcoddestino" value="<?php print $ls_coddestino ?>">
    <input name="hiddendestino" type="hidden" id="hiddendestino" value="<?php print $ls_dendestino ?>">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo del Producto </td>
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
          <input name="txtcodart" type="text" id="txtnombre2">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Denominaci&oacute;n</div></td>
        <td height="22"><div align="left">          <input name="txtdenart" type="text" id="txtdenart">
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
	$in     =new sigesp_include();
	$con    =$in->uf_conectar();
	$io_msg =new class_mensajes();
	$io_data   =new class_datastore();
	$io_sql =new class_sql($con);
	$io_fun =new class_funciones();
	
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

	
	if($ls_operacion=="BUSCAR")
	{
		$ls_sql="SELECT sim_articulo.*,".
				"      (SELECT dentipart FROM sim_tipoarticulo".
				"        WHERE sim_tipoarticulo.codtipart = sim_articulo.codtipart) AS dentipart,".
				"      (SELECT denunimed FROM sim_unidadmedida".
				"        WHERE sim_unidadmedida.codunimed = sim_articulo.codunimed) AS denunimed,".
				"      (SELECT unidad FROM sim_unidadmedida".
				"        WHERE sim_unidadmedida.codunimed = sim_articulo.codunimed) AS unidad".
				"  FROM sim_articulo".
				" WHERE codemp = '".$ls_codemp."'".
				"   AND codart ilike '".$ls_codart."'".
				"   AND denart ilike '".$ls_denart."' AND estatus='t' ORDER BY codart";
	//print $ls_sql;	
		$rs_cta=$io_sql->select($ls_sql);
		if($rs_cta==false&&($io_sql->message!=""))
			{
				$io_msg->message("No hay registros");
			}
			else
			{
				if($row=$io_sql->fetch_row($rs_cta))
				{
			
					
					print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
					print "<tr class=titulo-celda>";
					print "<td><font width=100 color=#FFFFFF>Código</font></td>";
					print "<td>Denominación</td>";
					print "<td>Presentación</td>";
					print "<td>Unidad</td>";
					$la_producto=$io_sql->obtener_datos($rs_cta);
					//print_r($la_producto);
					$io_data->data=$la_producto;
					$totrow=$io_data->getRowCount("codart");
					for($z=1;$z<=$totrow;$z++)
					{
						print "<tr class=celdas-blancas>";
						$ls_codart=$io_data->getValue("codart",$z);
		                $ls_denart=$io_data->getValue("denart",$z);
						$li_unidad=$io_data->getValue("unidad",$z);
						$denunidad=$io_data->getValue("denunimed",$z);
						
						print "<td><a href=\"javascript: aceptar('$ls_codart','$ls_denart','$li_linea',$li_unidad,'$ls_coddestino','$ls_dendestino');\">".$ls_codart."</a></td>";
						print "<td>".$ls_denart."</td>";
						print "<td>".$denunidad."</td>";
						print "<td>".$li_unidad."</td>";
						print "</tr>";			
			
					}
		 		}
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
	function aceptar(ls_codart,ls_denart,li_linea,li_unidad,ls_coddestino,ls_dendestino)
	{
		obj=eval("opener.document.form1."+ls_coddestino+li_linea+"");
		obj.value=ls_codart;
		obj1=eval("opener.document.form1."+ls_dendestino+li_linea+"");
		obj1.value=ls_denart;
		obj1=eval("opener.document.form1.hidunidad"+li_linea+"");
		obj1.value=li_unidad;
		close();
	}
	
	function ue_search()
  	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		f.action="sigesp_catdinamic_articulom.php";
		f.submit();
	}
</script>
</html>
