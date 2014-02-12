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
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Art&iacute;culo</td>
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
	$ds     =new class_datastore();
	$io_sql =new class_sql($con);
	$io_fun =new class_funciones();
	require_once("class_funciones_inventario.php");
	$io_fun_siv=new class_funciones_inventario();
	$ls_tipo=$io_fun_siv->uf_obtenervalor_get("tipo","");  
	
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
	print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	print "<tr class=titulo-celda>";
	print "<td width=100>Código</td>";
	print "<td>Denominacion</td>";
	print "<td>Clasificación</td>";
	print "</tr>";
	if($ls_operacion=="BUSCAR")
	{
		$ls_sql="SELECT siv_articulo.*,".
				"      (SELECT dentipart FROM siv_tipoarticulo".
				"        WHERE siv_tipoarticulo.codtipart = siv_articulo.codtipart) AS dentipart,".
				"      (SELECT tipart FROM siv_tipoarticulo".
				"        WHERE siv_tipoarticulo.codtipart = siv_articulo.codtipart) AS tipart,".
				"      (SELECT denunimed FROM siv_unidadmedida".
				"        WHERE siv_unidadmedida.codunimed = siv_articulo.codunimed) AS denunimed,".
				"      (SELECT unidad FROM siv_unidadmedida".
				"        WHERE siv_unidadmedida.codunimed = siv_articulo.codunimed) AS unidad".
				"  FROM siv_articulo".
				" WHERE codemp = '".$ls_codemp."'".
				"   AND codart LIKE '".$ls_codart."'".
				"   AND denart LIKE '".$ls_denart."'".
				" ORDER BY codart";
		$rs_cta=$io_sql->select($ls_sql);
		$li_row=$io_sql->num_rows($rs_cta);
		if($li_row>0)
		{
			while($row=$io_sql->fetch_row($rs_cta))
			{
				print "<tr class=celdas-blancas>";
				$ls_codart=$row["codart"];
				$ls_denart=$row["denart"];
				$li_unidad=$row["unidad"];
				$ls_clasi=$row["tipart"]; 
				$spg_cuenta=$row["spg_cuenta"];
				if ($ls_clasi=="")
				{
					$ls_clasificación="No posee";
				}elseif ($ls_clasi=="1")
				{
					$ls_clasificación="Bienes";
				}elseif ($ls_clasi=="2")
				{
					$ls_clasificación="Material y Suministro";
				}
			    switch($ls_tipo)
				{
					  case"":
							print "<td><a href=\"javascript: aceptar('$ls_codart','$ls_denart','$li_linea',$li_unidad,'$ls_coddestino','$ls_dendestino','$ls_clasi');\">".$ls_codart."</a></td>";
							print "<td>".$row["denart"]."</td>";
							print "<td>".$ls_clasificación."</td>";
							print "</tr>";	
					  break;

					   case"tipo":
							print "<td><a href=\"javascript: aceptar2('$ls_codart','$ls_denart','$li_linea',$li_unidad,'$ls_coddestino','$ls_dendestino','$ls_clasi');\">".$ls_codart."</a></td>";
							print "<td>".$row["denart"]."</td>";
							print "<td>".$ls_clasificación."</td>";
							print "</tr>";	
					  break;
				}  	
			}
		}
		else
		{
			$io_msg->message("No existen articulos asociados a esta busqueda");
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
	function aceptar(ls_codart,ls_denart,li_linea,li_unidad,ls_coddestino,ls_dendestino,ls_clasi)
	{  
		obj=eval("opener.document.form1."+ls_coddestino+li_linea+"");
		obj.value=ls_codart;
		obj1=eval("opener.document.form1."+ls_dendestino+li_linea+"");
		obj1.value=ls_denart;
		obj1=eval("opener.document.form1.hidunidad"+li_linea+"");
		obj1.value=li_unidad;
		obj1=eval("opener.document.form1.hclasi"+li_linea+"");
		obj1.value=ls_clasi;
		close();
	}
	
	function aceptar2(ls_codart,ls_denart,li_linea,li_unidad,ls_coddestino,ls_dendestino,ls_clasi)
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
		f.action="sigesp_catdinamic_articulom.php?tipo=<?PHP print $ls_tipo;?>";
		f.submit();
	}
</script>
</html>
