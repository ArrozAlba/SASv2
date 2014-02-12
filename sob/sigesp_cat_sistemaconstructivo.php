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
	$ls_campo="codsiscon";
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
<title>Catálogo de Sistemas Constructivos</title>
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
<table width="600" border="0" align="center" cellpadding="1" cellspacing="1" >
  <tr>
   <td width="600" colspan="2" class="titulo-celda">Cat&aacute;logo de Sistemas Constructivos </td>
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
	require_once("../shared/class_folder/class_mensajes.php");
	$ls_empresa=$_SESSION["la_empresa"];
	$io_msg=new class_mensajes();	
	$io_conect=new sigesp_include();
	$conn=$io_conect->uf_conectar();
	$io_datastore=new class_datastore();
	$io_sql=new class_sql($conn);
	$ls_sql=" SELECT *
			  FROM sob_sistemaconstructivo 
			  WHERE codemp='".$ls_empresa["codemp"]."'  ORDER BY $ls_campo $ls_orden ";
	$rs_data=$io_sql->select($ls_sql);
	$data=$rs_data;
	if($row=$io_sql->fetch_row($rs_data))
	{
		$data=$io_sql->obtener_datos($rs_data);
		$la_arrcols=array_keys($data);
		$li_totcol=count($la_arrcols);
		$io_datastore->data=$data;
		$li_totrow=$io_datastore->getRowCount("codsiscon");
		print "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla>";
		print "<tr class=titulo-celda>";
		print "<td><a href=javascript:ue_ordenar('codsiscon','');><font color=#FFFFFF>Código</font></a></td>";
		print "<td><a href=javascript:ue_ordenar('nomsiscon','');><font color=#FFFFFF>Nombre</font></a></td>";
		print "<td><a href=javascript:ue_ordenar('dessiscon','');><font color=#FFFFFF>Descripción</font></a></td>";
		print "</tr>";
		for($li_z=1;$li_z<=$li_totrow;$li_z++)
		{
			print "<tr class=celdas-blancas>";
			$ls_codigo=$data["codsiscon"][$li_z];
			$ls_nombre=$data["nomsiscon"][$li_z];
			$ls_descripcion=$data["dessiscon"][$li_z];
			print "<td><a href=\"javascript: aceptar('$ls_codigo','$ls_nombre','$ls_descripcion');\">".$ls_codigo."</a></td>";
			print "<td align=left>".$ls_nombre."</td>";
			print "<td align=left>".$ls_descripcion."</td>";
			print "</tr>";			
		}
		print "</table>";
	}
	else
	  {
		$io_msg->message("No se han creado Sistemas Constructivos");
	  }
	$io_sql->free_result($rs_data);
	$io_sql->close();
?>
</div>
  </form>
</body>
<script language="JavaScript">
  /*function aceptar(codigo,nombre,descripcion)
  {
    opener.document.form1.txtcodsiscon.value=codigo;
    opener.document.form1.txtnomsiscon.value=nombre;
	opener.document.form1.txtdessiscon.value=descripcion;
	close();
  }*/
  
  function aceptar (codigo,nombre,descripcion)
  {
  	opener.ue_cargarsistemaconstructivo(codigo,nombre,descripcion);
	close();
  }
</script>
</html>