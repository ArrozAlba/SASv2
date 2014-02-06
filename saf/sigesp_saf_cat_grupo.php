<?
session_start();
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codigo="%".$_POST["txtcodigo"]."%";
	$ls_denominacion="%".$_POST["txtdenominacion"]."%";
	$ls_status="%".$_POST["hidstatus"]."%";
	$ls_tipo=$_POST["tipo"];
}
else
{
	$ls_operacion="BUSCAR";
	$ls_codigo="%%";
	$ls_denominacion="%%";
	$ls_tipo=$_GET["tipo"];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Grupos </title>
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
    <input name="txtempresa" type="hidden" id="txtempresa">
    <input name="hidstatus" type="hidden" id="hidstatus">
    <input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo; ?>">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Grupos </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="109"><div align="right">C&oacute;digo</div></td>
        <td width="389" height="22"><div align="left">
          <input name="txtcodigo" type="text" id="txtnombre2">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Denominaci&oacute;n</div></td>
        <td height="22"><div align="left">          <input name="txtdenominacion" type="text" id="txtdenominacion">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Buscar</a></div></td>
      </tr>
    </table>
  <br>
    <?
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_mensajes.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
$io_msg=new class_mensajes();
$ds=new class_datastore();
$io_sql=new class_sql($con);
$arr=$_SESSION["la_empresa"];

print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Código</td>";
print "<td>Denominación</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_sql=" SELECT * FROM saf_grupo".
			" WHERE codgru like '".$ls_codigo."'".
			" AND dengru like '".$ls_denominacion."'"; 
    $rs_cta=$io_sql->select($ls_sql);
	$li_numrows=$io_sql->num_rows($rs_cta);
	if($li_numrows>0)
	{
	    while($row=$io_sql->fetch_row($rs_cta))
		{
		    print "<tr class=celdas-blancas>";
			$ls_codigo=$row["codgru"];
			$ls_denominacion=$row["dengru"];
			switch($ls_tipo)
			{
				case '':
					print "<td><a href=\"javascript: aceptar('$ls_codigo','$ls_denominacion');\">".$ls_codigo."</a></td>";
					print "<td>".$ls_denominacion."</td>";
				break;
				
				case 'ACTIVOS':
					print "<td><a href=\"javascript: aceptar_activos('$ls_codigo','$ls_denominacion');\">".$ls_codigo."</a></td>";
					print "<td>".$ls_denominacion."</td>";
				break;
			}
			print "</tr>";			
		}
	}
	else
	{
	   $io_msg->message("No existen registros asociados a la busqueda");
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
	function aceptar(codigo,denominacion)
	{
		opener.document.form1.txtcodgru.value=codigo;
		opener.document.form1.txtdengru.value=denominacion;
		opener.document.form1.buttonir.disabled=false;
		opener.document.form1.hidstatus.value="C";
		close();
	}
	function aceptar_activos(codigo,denominacion)
	{
		opener.document.form1.txtcodgru.value=codigo;
		opener.document.form1.txtdengru.value=denominacion;
		close();
	}
	function ue_search()
	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		f.action="sigesp_saf_cat_grupo.php";
		f.submit();
	}
</script>
</html>