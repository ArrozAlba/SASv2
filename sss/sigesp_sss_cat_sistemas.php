<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo Sistemas</title>
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
  </p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Sistemas </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="101"><div align="right">Codigo </div></td>
        <td width="397" height="22"><div align="left">
          <input name="txtcodigo" type="text" id="txtcodigo">        
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Nombre</div></td>
        <td height="22"><div align="left">
          <input name="txtnombre" type="text" id="txtnombre">
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
$in=new sigesp_include();
$con=$in->uf_conectar();
require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
require_once("../shared/class_folder/class_datastore.php");
$ds=new class_datastore();
require_once("../shared/class_folder/class_sql.php");
$io_sql=new class_sql($con);
require_once("class_funciones_seguridad.php");
$io_funciones_seguridad=new class_funciones_seguridad();

$ls_destino=$io_funciones_seguridad->uf_obtenervalor_get("destino","");
if($ls_destino=="")
{$ls_destino=$io_funciones_seguridad->uf_obtenervalor("hiddestino","");}
$arr=$_SESSION["la_empresa"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codigo="%".$_POST["txtcodigo"]."%";
	$ls_nombre="%".$_POST["txtnombre"]."%";
	$ls_status="%".$_POST["hidstatus"]."%";
}
else
{
	$ls_operacion="BUSCAR";
	$ls_codigo="%%";
	$ls_nombre="%%";
	$ls_status="%%";
}
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Codigo </td>";
print "<td>Nombre</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_sql="SELECT * FROM sss_sistemas".
			" WHERE codsis LIKE '".$ls_codigo."'".
			" AND nomsis LIKE '".$ls_nombre."' ";
    $rs_cta=$io_sql->select($ls_sql);
	$data=$rs_cta;
	if($row=$io_sql->fetch_row($rs_cta))
	{
		$data=$io_sql->obtener_datos($rs_cta);

		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds->data=$data;

		$totrow=$ds->getRowCount("codsis");
	
		for($z=1;$z<=$totrow;$z++)
		{
			print "<tr class=celdas-blancas>";
			$ls_codigo=$data["codsis"][$z];
			$ls_nombre=$data["nomsis"][$z];
			print "<td><a href=\"javascript: aceptar('$ls_codigo','$ls_nombre','$ls_status','$ls_destino');\">".$ls_codigo."</a></td>";
			print "<td>".$data["nomsis"][$z]."</td>";
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
</div>
<input name="hiddestino" type="hidden" id="hiddestino" value="<?php print $ls_destino?>">
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
	function aceptar(codsis,nomsis,status,destino)
	{	
		if(destino=="")
		{
			opener.document.form1.txtcodigo.value=codsis;
			opener.document.form1.txtcodigo.disable=true;
			opener.document.form1.txtnombre.value=nomsis;
			opener.document.form1.hidstatus.value="C";
			opener.document.form1.hidcodigo.value=codsis;
		}
		if(destino=="Reporte")
		{
			opener.document.form1.txtcodsis.value=codsis;
			opener.document.form1.txtnomsis.value=nomsis;
		}
		close();
	}
	function ue_search()
	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		f.action="sigesp_sss_cat_sistemas.php";
		f.submit();
	}
</script>
</html>
