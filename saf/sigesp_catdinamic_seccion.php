<?
//session_id('8675309');
session_start();
if (array_key_exists("operacion",$_POST))
{
	$ls_codgru=$_POST["hidgrupo"];
	$ls_codsubgru=$_POST["hidsubgrupo"];
}
else
{
	if(array_key_exists("codigo",$_GET))
	{
		$ls_codgru=$_GET["codigo"];
		$ls_codsubgru=$_GET["codsubgru"];
	}
	else
	{
		$ls_codgru="";
		$ls_codsubgru="";
	
	}
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
    <input name="hidsubgrupo" type="hidden" id="hidsubgrupo" value="<? print $ls_codsubgru ?>">
    <input name="hidstatus" type="hidden" id="hidstatus">
    <input name="hidgrupo" type="hidden" id="hidgrupo" value="<? print $ls_codgru ?>">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Secciones</td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="109"><div align="right">C&oacute;digo</div></td>
        <td width="389"><div align="left">
          <input name="txtcodsec" type="text" id="txtcodsec">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Denominaci&oacute;n</div></td>
        <td><div align="left">          <input name="txtdensec" type="text" id="txtdensec">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Buscar</a></div></td>
      </tr>
    </table>
  <br>
    <?
require_once("..\shared\class_folder\sigesp_include.php");
require_once("..\shared\class_folder\class_datastore.php");
require_once("..\shared\class_folder\class_sql.php");
require_once("..\shared\class_folder\class_mensajes.php");
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
	$ls_operacion="";

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
	$ls_sql="SELECT CodGru, CodSubGru, CodSec, DenSec FROM saf_seccion WHERE CodGru='".$ls_codgru."' AND CodSubGru='".$ls_codsubgru."' AND CodSec like '".$ls_codsec."'  AND DenSec like '".$ls_densec."' ";
	$rs_cta=$io_sql->select($ls_sql);
    $data=$rs_cta;

	if($row=$io_sql->fetch_row($rs_cta))
	{
		$data=$io_sql->obtener_datos($rs_cta);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds->data=$data;

		$totrow=$ds->getRowCount("CodGru");
	
		for($z=1;$z<=$totrow;$z++)
		{
			print "<tr class=celdas-blancas>";
			$ls_codgru=$data["CodGru"][$z];
			$ls_codsubgru=$data["CodSubGru"][$z];
			$ls_codsec=$data["CodSec"][$z];
			$ls_densec=$data["DenSec"][$z];
			print "<td><a href=\"javascript: aceptar('$ls_codgru','$ls_codsubgru','$ls_codsec','$ls_densec');\">".$ls_codgru."</a></td>";
			print "<td>".$data["CodSubGru"][$z]."</td>";
			print "<td>".$data["CodSec"][$z]."</td>";
			print "<td>".$data["DenSec"][$z]."</td>";
			print "</tr>";			
		}
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
	opener.document.form1.operacion3.value="BUSCAR";
	opener.document.form1.txtcodgru.readOnly=true;
	opener.document.form1.txtdengru.readOnly=true;
	opener.document.form1.txtcodsubgru.readOnly=true;
	opener.document.form1.txtdensubgru.readOnly=true;
	opener.document.form1.txtcodsec.readOnly=true;
	close();
  }
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_catdinamic_seccion.php";
  f.submit();
  }
</script>
</html>
