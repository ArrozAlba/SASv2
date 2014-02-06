<?Php
session_start();
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "opener.location.href='../sigesp_conexion.php';";
	print "close();";
	print "</script>";		
} 
if(!array_key_exists("campo",$_POST))
{
	$ls_campo="d.codded";
	$ls_orden="ASC";
}
else
{
	$ls_campo=$_POST["campo"];
	$ls_orden=$_POST["orden"];
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
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
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
<input type="hidden" name="campo" id="campo" value="<? print $ls_campo;?>" >
<input type="hidden" name="orden" id="orden" value="<? print $ls_orden;?>">
  <div align="center">
<?Php
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("class_folder/sigesp_sob_c_funciones_sob.php");
	require_once("../shared/class_folder/class_mensajes.php");
	$la_empresa=$_SESSION["la_empresa"];
	$ls_codemp=$la_empresa["codemp"];
	$io_msg=new class_mensajes();
	$io_funnum=new sigesp_sob_c_funciones_sob();	
	$io_conect=new sigesp_include();
	$conn=$io_conect->uf_conectar();
	$io_datastore=new class_datastore();
	$io_sql=new class_sql($conn);
	$ls_sql=" SELECT  d.codded AS codded, d.dended AS dended, d.formula,d.sc_cuenta AS cuenta, d.monded AS deducible
			  FROM sigesp_deducciones d WHERE codemp='".$ls_codemp."'
			  ORDER BY $ls_campo $ls_orden";
	$rs_data=$io_sql->select($ls_sql);	
	if($row=$io_sql->fetch_row($rs_data))
	{
		$data=$io_sql->obtener_datos($rs_data);
		$la_arrcols=array_keys($data);
		$li_totcol=count($la_arrcols);
		$io_datastore->data=$data;
		$li_totrow=$io_datastore->getRowCount("codded");
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla>";
		print "<tr class=titulo-celda>";
		print "<td><a href=javascript:ue_ordenar('d.codded','');><font color=#FFFFFF>Código</font></a></td>";
		print "<td><a href=javascript:ue_ordenar('d.dended','');><font color=#FFFFFF>Descripción</font></a></td>";
		print "<td><a href=javascript:ue_ordenar('d.sc_cuenta','');><font color=#FFFFFF>Cuenta</font></a></td>";
		print "<td><a href=javascript:ue_ordenar('d.monded','');><font color=#FFFFFF>Deducible</font></a></td>";
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
			print "<td align=left>".$ls_descripcion."</td>";
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