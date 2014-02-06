<?php
//session_id('8675309');
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo Proveedores</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
        <td width="67"><div align="right">C&oacute;digo</div></td>
        <td width="431"><div align="left">
          <input name="codigo" type="text" id="codigo">        
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Nombre</div></td>
        <td><div align="left">
          <input name="nombre" type="text" id="nombre">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<br>
    <?php
require_once("../../shared/class_folder/sigesp_include.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
require_once("../../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
require_once("../../shared/class_folder/class_sql.php");
$SQL=new class_sql($con);
$ds=new class_datastore();
$arr=$_SESSION["la_empresa"];
require_once("../class_folder/class_funciones_cfg.php");
$fun=new class_funciones_cfg();
$ls_proveedor=$fun->uf_obtenervalor_get("proveedor","");

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codigo="%".$_POST["codigo"]."%";
	$ls_nombre="%".$_POST["nombre"]."%";
}
else
{
	$ls_operacion="";

}
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Código </td>";
print "<td>Nombre del Proveedor</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_sql="SELECT cod_pro,nompro FROM rpc_proveedor where cod_pro like '".$ls_codigo."' AND nompro like '".$ls_nombre."' ";
	$rs_cta=$SQL->select($ls_sql);
	$data=$rs_cta;
	if($row=$SQL->fetch_row($rs_cta))
	{
		$data=$SQL->obtener_datos($rs_cta);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds->data=$data;
		$totrow=$ds->getRowCount("cod_pro");
	
		for($z=1;$z<=$totrow;$z++)
		{
			print "<tr class=celdas-blancas>";
			$codigo=$data["cod_pro"][$z];
			$nombre=$data["nompro"][$z];
			switch ($ls_proveedor)
			{
				  case "":
					print "<td><a href=\"javascript: aceptar('$codigo','$nombre');\">".$codigo."</a></td>";
					print "<td>".$data["nompro"][$z]."</td>";
					print "</tr>";	
				  break;
				  
				  case "proveedor":
					print "<td><a href=\"javascript: aceptar2('$codigo','$nombre');\">".$codigo."</a></td>";
					print "<td>".$data["nompro"][$z]."</td>";
					print "</tr>";
				  break;
			}		
		} 
	}
	else
	{
		$io_msg->message("No se han definido proveedores");
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
  function aceptar(prov,d)
  {
    opener.document.form1.txtprovbene.value=prov;
    opener.document.form1.txtdesproben.value=d;
	close();
  }
  function aceptar2(prov,d)
  {
    opener.document.form1.txtcodpro.value=prov;
    opener.document.form1.txtdenpro.value=d;
	close();
  }
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_catdinamic_prov.php?proveedor=<?PHP print $ls_proveedor;?>";
  f.submit();
  }
</script>
</html>
