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
	$ls_campo="s.codpar";
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
<title>Cat&aacute;logo de Partidas</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript" src="js/validaciones.js"></script>
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


<body >
<form name="form1" method="post" action="">
<input type="hidden" name="campo" id="campo" value="<? print $ls_campo;?>" >
<input type="hidden" name="orden" id="orden" value="<? print $ls_orden;?>">
<?
	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_descatpar="%".$_POST["txtdescatpar"]."%";	
		$ls_nompar="%".$_POST["txtnompar"]."%";	
		$ls_codpar="%".$_POST["txtcodpar"]."%";		
		$ls_codcovpar="%".$_POST["txtcodcovpar"]."%";	
		$ls_nomuni="%".$_POST["txtnomuni"]."%";	
		$ls_opener=$_POST["hidopener"];			
		$ls_descripcioncatpar=$_POST["txtdescatpar"];	
		$ls_nombrepartida=$_POST["txtnompar"];	
		$ls_codigopartida=$_POST["txtcodpar"];		
		$ls_codigocovenimpartida=$_POST["txtcodcovpar"];	
		$ls_nombreunidad=$_POST["txtnomuni"];	
	}
	else
	{
		$ls_operacion="";	
		$ls_opener=$_GET["hidopener"];
		$ls_descripcioncatpar="";
		$ls_nombrepartida="";
		$ls_codigopartida="";
		$ls_codigocovenimpartida="";
		$ls_nombreunidad="";
	}
?>
<table width="700" border="0" align="center" cellpadding="1" cellspacing="1" >
  <tr>
   <td width="700" colspan="2" class="titulo-celda"><div align="center">Cat&aacute;logo de Partidas </div></td>
  </tr>
</table>
	 <br>
	 <table width="700" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="109"  height="30"><div align="right">C&oacute;digo Partida</div></td>
        <td width="109"><input name="txtcodpar" type="text" id="txtcodpar2" value="<? print $ls_codigopartida;?>" size="10" maxlength="10"></td>
        <td width="98"><div align="right">Categor&iacute;a Partida</div></td>
        <td width="382" valign="middle"><input name="txtdescatpar" type="text" id="txtdescatpar" value="<? print $ls_descripcioncatpar;?>" size="60" maxlength="254">
        <a href="javascript: ue_catcategoriaspartidas();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></td>
      </tr>
      <tr>
        <td  height="18"><div align="right">C&oacute;digo COVENIN </div></td>
        <td  height="18"><input name="txtcodcovpar" type="text" id="txtcodcovpar" value="<? print $ls_codigocovenimpartida;?>" size="15" maxlength="15"></td>
        <td><div align="right">Nombre Partida</div></td>
        <td><input name="txtnompar" type="text" id="txtnompar2" value="<? print $ls_nombrepartida;?>" size="60" maxlength="254"></td>
      </tr>
      <tr>
        <td height="28"><div align="right">Unidad</div></td>
        <td height="28"><input name="txtnomuni" type="text" id="txtnomuni" value="<? print $ls_nombreunidad;?>" size="10" maxlength="30"></td>
        <td colspan="2"><div align="left">          </div></td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
        <td colspan="2"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<input name="operacion" id="operacion" type="hidden">
	<br>
<br>

  <div align="center">
<?Php
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("class_folder/sigesp_sob_c_funciones_sob.php");
	$io_funsob=new sigesp_sob_c_funciones_sob();	
	$io_msg=new class_mensajes();
	$la_empresa=$_SESSION["la_empresa"];
	$ls_codemp=$la_empresa["codemp"];
	$io_conect=new sigesp_include();
	$conn=$io_conect->uf_conectar();
	$io_datastore=new class_datastore();
	$io_sql=new class_sql($conn);
			
if($ls_operacion=="ue_buscar")
{
	$ls_sql=" SELECT s.codpar,s.nompar,s.despar,u.nomuni,u.coduni,s.prepar,s.codcovpar,s.codcatpar,c.descatpar
			  FROM sob_partida s,sob_unidad u,sob_categoriapartida c
			  WHERE s.codemp='".$ls_codemp."' AND s.coduni=u.coduni AND s.codcatpar=c.codcatpar AND c.descatpar like '".$ls_descatpar."' 
			  AND s.nompar like '".$ls_nompar."' AND s.codpar like '".$ls_codpar."' AND s.codcovpar like '".$ls_codcovpar."' 
			  AND u.nomuni like '".$ls_nomuni."'
			  ORDER BY ".$ls_campo." ".$ls_orden."";
	$rs_data=$io_sql->select($ls_sql);
	$data=$rs_data;
	if($row=$io_sql->fetch_row($rs_data))
	{
		$data=$io_sql->obtener_datos($rs_data);
		$la_arrcols=array_keys($data);
		$li_totcol=count($la_arrcols);
		$io_datastore->data=$data;
		$li_totrow=$io_datastore->getRowCount("codpar");
		print "<table width=700 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla>";
		print "<tr class=titulo-celda>";
		print "<td><a href=javascript:ue_ordenar('s.codpar','ue_buscar');><font color=#FFFFFF>Código</font></a></td>";
		print "<td><a href=javascript:ue_ordenar('s.codcovpar','ue_buscar');><font color=#FFFFFF>Código COVENIN</font></a></td>";
		print "<td><a href=javascript:ue_ordenar('c.descatpar','ue_buscar');><font color=#FFFFFF>Categoría</font></a></td>";		
		print "<td><a href=javascript:ue_ordenar('s.despar','ue_buscar');><font color=#FFFFFF>Descripción</font></a></td>";
		print "<td><a href=javascript:ue_ordenar('s.prepar','ue_buscar');><font color=#FFFFFF>Precio Unitario</font></a></td>";
		print "<td><a href=javascript:ue_ordenar('u.nomuni','ue_buscar');><font color=#FFFFFF>Unidad</font></a></td>";
		print "</tr>";
		for($li_z=1;$li_z<=$li_totrow;$li_z++)
		{
			print "<tr class=celdas-blancas>";
			$ls_codigo=$data["codpar"][$li_z];
			$ls_nombre=$data["nompar"][$li_z];
			$ls_descripcion=$data["despar"][$li_z];
			$ls_unidad=$data["nomuni"][$li_z];
			$ls_codunidad=$data["coduni"][$li_z];
			$ls_prepar=$io_funsob->uf_convertir_numerocadena($data["prepar"][$li_z]);
			$ls_codcovpar=$data["codcovpar"][$li_z];
			$ls_codcatpar=$data["codcatpar"][$li_z];
			$ls_descatpar=$data["descatpar"][$li_z];
			print "<td style=\"text-align:center \"><a href=\"javascript: aceptar('$ls_codigo','$ls_nombre','$ls_descripcion','$ls_codunidad','$ls_unidad','$ls_prepar','$ls_codcovpar','$ls_codcatpar','$ls_descatpar');\">".$ls_codigo."</a></td>";
			print "<td style=\"text-align:center \">".$ls_codcovpar."</td>";			
			print "<td style=\"text-align:left \">".$ls_descatpar."</td>";
			print "<td style=\"text-align:left \">".$ls_nombre."</td>";
			print "<td style=\"text-align:right \">".$ls_prepar."</td>";
			print "<td style=\"text-align:center \">".$ls_unidad."</td>";
			print "</tr>";			
		}
		print "</table>";
	}
	else
	  {
		$io_msg->message("No existen Partidas que cumplan con este patrón de búsqueda!!!");
	  }
	$io_sql->free_result($rs_data);
	$io_sql->close();
}
?>
</div>
<input name="hidopener" id="hidopener" type="hidden" value="<? print $ls_opener;?>">
</form>
</body>
<script language="JavaScript">
 
  function aceptar(codigo,nombre,descripcion,codunidad,nomunidad,prepar,codcovpar,codcatpar,descatpar)
  {
 	f=document.form1;
	opener.ue_cargarpartida(codigo,nombre,descripcion,codunidad,nomunidad,prepar,codcovpar,codcatpar,descatpar);
	if(f.hidopener.value!="obra")
		close();
  }
  
  function ue_catcategoriaspartidas()
  {
  		f=document.form1;
		f.operacion.value="";
		pagina="sigesp_cat_categoriapartida.php";
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=650,height=300,resizable=yes,location=no,status=yes,top=0,left=50;");
  }
  
  function ue_cargarcategoriapartida(codigo,nombre)
  {
  		f=document.form1;
		f.txtdescatpar.value=nombre;
  }
  
  function ue_search()
  {
  	f=document.form1;
	f.operacion.value="ue_buscar";
	f.submit();
  }
</script>
</html>