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
<title>Cat&aacute;logo de Partidas</title>
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
   <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Partidas </td>
  </tr>
</table>
<br>
<form name="form1" method="post" action="">
  <div align="center">
<?Php
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/class_sql.php");
	$la_empresa=$_SESSION["la_empresa"];
	$ls_codemp=$la_empresa["codemp"];
	$ls_codobrasi=$_GET["ls_codobrasi"];
	 
	$io_conect=new sigesp_include();
	$conn=$io_conect->uf_conectar();
	$io_datastore=new class_datastore();
	$io_sql=new class_sql($conn);
	$ls_sql="SELECT p.codpar,s.nompar,s.despar,u.nomuni,u.coduni,p.codobr
             FROM sob_partida s,sob_unidad u,sob_partidaobra p
             WHERE s.codemp='".$ls_codemp."' AND s.coduni=u.coduni AND s.codpar=p.codpar AND p.codobr='".$ls_codobrasi."'
             ORDER BY s.codpar ASC";
	$rs_data=$io_sql->select($ls_sql);

	$data=$rs_data;
	if($row=$io_sql->fetch_row($rs_data))
	{
		$data=$io_sql->obtener_datos($rs_data);
		$la_arrcols=array_keys($data);
		$li_totcol=count($la_arrcols);
		$io_datastore->data=$data;
		$li_totrow=$io_datastore->getRowCount("codpar");
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla>";
		print "<tr class=titulo-celda>";
		print "<td>Código</td>";
		print "<td>Nombre</td>";
		print "<td>Descripción</td>";
		print "<td>Unidad</td>";
		print "</tr>";
		for($li_z=1;$li_z<=$li_totrow;$li_z++)
		{
			print "<tr class=celdas-blancas>";
			$ls_codigo=$data["codpar"][$li_z];
			$ls_nombre=$data["nompar"][$li_z];
			$ls_descripcion=$data["despar"][$li_z];
			$ls_unidad=$data["nomuni"][$li_z];
			$ls_codunidad=$data["coduni"][$li_z];
			print "<td><a href=\"javascript: aceptar('$ls_codigo','$ls_nombre','$ls_descripcion','$ls_codunidad','$ls_unidad');\">".$ls_codigo."</a></td>";
			print "<td>".$ls_nombre."</td>";
			print "<td>".$ls_descripcion."</td>";
			print "<td>".$ls_unidad."</td>";
			print "</tr>";			
		}
		print "</table>";
	}
	else
	  {
		print "No se han creado Partidas!!!";
	  }
	$io_sql->free_result($rs_data);
	$io_sql->close();
?>
</div>
  </form>
</body>
<script language="JavaScript">
 
  function aceptar(codigo,nombre,descripcion,codunidad,nomunidad)
  {
 	opener.ue_cargarpartida(codigo,nombre,descripcion,codunidad,nomunidad);
	close();
  }
</script>
</html>