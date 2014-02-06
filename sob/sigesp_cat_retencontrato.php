<?Php
session_start();
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "opener.location.href='../sigesp_conexion.php';";
	print "close();";
	print "</script>";		
} 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Retenciones</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:hover {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style></head>

<body>
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1" >
  <tr>
   <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Retenciones </td>
  </tr>
</table>
<br>
<form name="form1" method="post" action="">
  <div align="center">
<?Php
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("class_folder/sigesp_sob_c_funciones_sob.php");
	require_once("../shared/class_folder/class_mensajes.php");
	$la_empresa=$_SESSION["la_empresa"];
	$ls_codcon=$_GET["codcon"];
	$ls_codemp=$la_empresa["codemp"];
	$io_msg=new class_mensajes();
	$io_funnum=new sigesp_sob_c_funciones_sob();	
	$io_conect=new sigesp_include();
	$conn=$io_conect->uf_conectar();
	$io_datastore=new class_datastore();
	$io_sql=new class_sql($conn);
	$ls_sql="SELECT rc.codded,d.dended as dended,d.sc_cuenta as cuenta,d.monded as deducible,d.formula
			 FROM sob_retencioncontrato rc, sigesp_deducciones d
			 WHERE rc.codemp='".$ls_codemp."' AND d.codemp='".$ls_codemp."' AND rc.codded=d.codded AND rc.codcon='".$ls_codcon."' ORDER BY d.codded ASC";
	$rs_data=$io_sql->select($ls_sql);
	$data=$rs_data;
	if($row=$io_sql->fetch_row($rs_data))
	{
		$data=$io_sql->obtener_datos($rs_data);
		$la_arrcols=array_keys($data);
		$li_totcol=count($la_arrcols);
		$io_datastore->data=$data;
		$li_totrow=$io_datastore->getRowCount("codded");
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla>";
		print "<tr class=titulo-celda>";
		print "<td>Código</td>";
		print "<td>Descripción</td>";
		print "<td>Cuenta</td>";
		print "<td>Deducible</td>";
		print "</tr>";
		for($li_z=1;$li_z<=$li_totrow;$li_z++)
		{
			print "<tr class=celdas-blancas>";
			$ls_codigo=$data["codded"][$li_z];			
			$ls_descripcion=$data["dended"][$li_z];
			$ls_cuenta=$data["cuenta"][$li_z];
			$ls_deducible=$data["deducible"][$li_z];
			$ls_formula=$data["formula"][$li_z];
			$ls_deducible=$io_funnum->uf_convertir_numerocadena($ls_deducible);
			print "<td align=center><a href=\"javascript: aceptar('$ls_codigo','$ls_descripcion','$ls_cuenta','$ls_deducible','$ls_formula');\">".$ls_codigo."</a></td>";
			print "<td align=center>".$ls_descripcion."</td>";
			print "<td align=center>".$ls_cuenta."</td>";
			print "<td align=center>".$ls_deducible."</td>";
			print "</tr>";			
		}
		print "</table>";
	}
	else
	  {
		$io_msg->message("No se han creado Retenciones");
	  }
	$io_sql->free_result($rs_data);
	$io_sql->close();
?>
</div>
  </form>
</body>
<script language="JavaScript">
  function aceptar(codigo,descripcion,cuenta,deducible,formula)
  {
    opener.ue_cargarretenciones(codigo,descripcion,cuenta,deducible,formula);
	close();
  }
</script>
</html>