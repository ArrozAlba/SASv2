<?php
session_start();
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codgru=$_POST["txtcodgru"];
	$ls_dengru=$_POST["txtdengru"];
	$ls_codsubgru=$_POST["txtcodsubgru"];
	$ls_densubgru=$_POST["txtdensubgru"];
	$ls_codsec=$_POST["txtcodsec"];
	$ls_densec=$_POST["txtdensec"];
	$ls_tipo=$_POST["tipo"];
}
else
{
	$ls_operacion="BUSCAR";
	$ls_tipo=$_GET["tipo"];
	$ls_codgru=$_GET["txtcodgru"];
	$ls_dengru=$_GET["txtdengru"];
	$ls_codsubgru=$_GET["txtcodsubgru"];
	$ls_densubgru=$_GET["txtdensubgru"];
	$ls_codsec="%%";
	$ls_densec="%%";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Secciones</title>
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
    <input name="hidsubgrupo" type="hidden" id="hidsubgrupo" value="<?php print $ls_codsubgru; ?>">
    <input name="hidstatus" type="hidden" id="hidstatus">
    <input name="hidgrupo" type="hidden" id="hidgrupo" value="<?php print $ls_codgru; ?>">
    <input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo; ?>">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Secciones</td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td><div align="right">Codigo del Grupo </div></td>
        <td height="22"><div align="left">
          <label>
          <input name="txtcodgru" type="text" id="txtcodgru" value="<?php print $ls_codgru; ?>" size="10">
          </label>
          <label>
          <input name="txtdengru" type="text" class="sin-borde" id="txtdengru" value="<?php  print $ls_dengru; ?>" size="50">
          </label>
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Codigo del SubGrupo </div></td>
        <td height="22"><div align="left">
          <label>
          <input name="txtcodsubgru" type="text" id="txtcodsubgru" value="<?php print $ls_codsubgru; ?>" size="10">
          </label>
          <label>
          <input name="txtdensubgru" type="text" class="sin-borde" id="txtdensubgru" value="<?php print $ls_densubgru; ?>" size="50">
          </label>
        </div></td>
      </tr>
      <tr>
        <td width="109"><div align="right">C&oacute;digo</div></td>
        <td width="389" height="22"><div align="left">
          <input name="txtcodsec" type="text" id="txtcodsec" size="10">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Denominaci&oacute;n</div></td>
        <td height="22"><div align="left">          <input name="txtdensec" type="text" id="txtdensec" size="65">
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
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_mensajes.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
$io_msg=new class_mensajes();
$ds=new class_datastore();
$io_sql=new class_sql($con);
$arr=$_SESSION["la_empresa"];
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codsec="%".$_POST["txtcodsec"]."%";
	$ls_densec="%".$_POST["txtdensec"]."%";
	$ls_status="%".$_POST["hidstatus"]."%";
}
else
{
	$ls_operacion="BUSCAR";
	$ls_codsec="%%";
	$ls_densec="%%";
}
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td width='50'>Grupo </td>";
print "<td width='65'>Sub Grupo</td>";
print "<td width='50'>Sección</td>";
print "<td>Denominación de la Sección</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_sql="SELECT codgru,codsubgru,codsec,densec".
			"  FROM saf_seccion".
			" WHERE codgru='".$ls_codgru."' ".
			"   AND codsubgru='".$ls_codsubgru."'".
			"   AND codsec like '".$ls_codsec."'".
			"   AND densec like '".$ls_densec."' ";
	$rs_cta=$io_sql->select($ls_sql);
	$li_rows=$io_sql->num_rows($rs_cta);
	if($li_rows>0)
	{
		while($row=$io_sql->fetch_row($rs_cta))
		{
			print "<tr class=celdas-blancas>";
			$ls_codgru=$row["codgru"];
			$ls_codsubgru=$row["codsubgru"];
			$ls_codsec=$row["codsec"];
			$ls_densec=$row["densec"];
			switch ($ls_tipo)
			{
				case "":
					print "<td><a href=\"javascript: aceptar('$ls_codgru','$ls_codsubgru','$ls_codsec','$ls_densec');\">".$ls_codgru."</a></td>";
					print "<td>".$ls_codsubgru."</td>";
					print "<td>".$ls_codsec."</td>";
					print "<td>".$ls_densec."</td>";
				break;
				case "ACTIVOS";
					print "<td><a href=\"javascript: aceptar_activos('$ls_codgru','$ls_codsubgru','$ls_codsec','$ls_densec');\">".$ls_codgru."</a></td>";
					print "<td>".$ls_codsubgru."</td>";
					print "<td>".$ls_codsec."</td>";
					print "<td>".$ls_densec."</td>";
				break;
				
			}
			print "</tr>";
		}
	}
	else
	{
		$io_msg->message("No se encontraron Registros a esta busqueda");
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
	function aceptar(codgrupo,codsubgrupo,codseccion,denominacion)
	{
		opener.document.form1.txtcodgru.value=codgrupo;
		opener.document.form1.txtcodsubgru.value=codsubgrupo;
		opener.document.form1.txtcodsec.value=codseccion;
		opener.document.form1.txtdensec.value=denominacion;
		opener.document.form1.hidstatus.value="C";
		opener.document.form1.buttonir.disabled=false;
		opener.document.form1.txtcodsec.readOnly=true;
		close();
	}
	function aceptar_activos(codgrupo,codsubgrupo,codseccion,denominacion)
	{
		opener.document.form1.txtcodsec.value=codseccion;
		opener.document.form1.txtdensec.value=denominacion;
		close();
	}
	function ue_search()
	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		f.action="sigesp_saf_cat_seccion.php";
		f.submit();
	}
</script>
</html>
