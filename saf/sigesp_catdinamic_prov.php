<?
//session_id('8675309');
session_start();

require_once("../shared/class_folder/sigesp_include.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
require_once("../shared/class_folder/class_mensajes.php");
$msg=new class_mensajes();
require_once("../shared/class_folder/class_sql.php");

require_once("class_funciones_activos.php");
$io_fac= new class_funciones_activos();


$io_sql=new class_sql($con);
$ds=new class_datastore();
$arr=$_SESSION["la_empresa"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codigo="%".$_POST["codigo"]."%";
	$ls_nombre="%".$_POST["nombre"]."%";
	$ls_coddestino=$_POST["coddestino1"];
	$ls_dendestino=$_POST["dendestino1"];
}
else
{
	$ls_operacion="";
	$ls_coddestino=$io_fac->uf_obtenervalor_get("coddestino1","txtcodpro");
	$ls_dendestino=$io_fac->uf_obtenervalor_get("dendestino1","txtdenpro");

}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo Proveedores</title>
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
</p>
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Proveedores </td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67"><div align="right">Codigo</div></td>
        <td width="431"><div align="left">
          <input name="codigo" type="text" id="codigo">        
          <input name="coddestino1" type="hidden" id="coddestino1" value="<?php print $ls_coddestino ?>">
          <input name="dendestino1" type="hidden" id="dendestino1" value="<?php print $ls_dendestino ?>">
        </div> </td>
      </tr>
      <tr>
        <td><div align="right">Nombre</div></td>
        <td><div align="left">
          <input name="nombre" type="text" id="nombre">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<br>
    <?

print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Código </td>";
print "<td>Nombre del Proveedor</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_sql="SELECT cod_pro,nompro FROM rpc_proveedor where cod_pro like '".$ls_codigo."' AND nompro like '".$ls_nombre."' ";
	$rd_data=$io_sql->select($ls_sql);
	while($row=$io_sql->fetch_row($rd_data))
	{
			print "<tr class=celdas-blancas>";
			$codigo=$row["cod_pro"];
			$nombre=$row["nompro"];
			print "<td><a href=\"javascript: aceptar('$codigo','$nombre','$ls_coddestino','$ls_dendestino');\">".$codigo."</a></td>";
			print "<td>".$nombre."</td>";
			print "</tr>";			
	}
}
print "</table>";
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function aceptar(codpro,denpro,coddestino,dendestino)
  {
    /*opener.document.form1.txtcodpro.value=codpro;
    opener.document.form1.txtdenpro.value=denpro;
	
	opener.document.form1.txtcodhasp.value=codpro;
    opener.document.form1.txtnomp.value=denpro;*/
	obj=eval("opener.document.form1."+coddestino+"");
	obj.value=codpro;
	obj=eval("opener.document.form1."+dendestino+"");
	obj.value=denpro;
	
	//opener.buscar();
	close();
  }
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_catdinamic_prov.php";
  f.submit();
  }
</script>
</html>
