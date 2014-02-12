<?
//session_id('8675309');
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Causas de Movimientos</title>
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
    <input name="txtnombrevie" type="hidden" id="txtnombrevie">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Causas de Movimientos </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67"><div align="right">C&oacute;digo</div></td>
        <td width="431"><div align="left">
          <input name="txtcodigo" type="text" id="txtnombre2">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Denominaci&oacute;n</div></td>
        <td><div align="left">          <input name="txtdenominacion" type="text" id="txtdenominacion">
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
$in=new sigesp_include();
$con=$in->uf_conectar();
require_once("..\shared\class_folder\class_mensajes.php");
$io_msg=new class_mensajes();
require_once("..\shared\class_folder\class_datastore.php");
require_once("..\shared\class_folder\class_sql.php");
$ds=new class_datastore();
$io_sql=new class_sql($con);
$arr=$_SESSION["la_empresa"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codigo="%".$_POST["txtcodigo"]."%";
	$ls_denominacion="%".$_POST["txtdenominacion"]."%";
	$ls_status="%".$_POST["hidstatus"]."%";
}
else
{
	$ls_operacion="";

}
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
//print "<td>Empresa </td>";
print "<td>Código</td>";
print "<td>Denominación</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_sql="SELECT CodCau, DenCau, TipCau, EstAfeCon, EstAfePre, ExpCau FROM saf_causas WHERE CodCau like '".$ls_codigo."' AND DenCau like '".$ls_denominacion."'";
    $rs_cta=$io_sql->select($ls_sql);
    $data=$rs_cta;
	if($row=$io_sql->fetch_row($rs_cta))
	{
		$data=$io_sql->obtener_datos($rs_cta);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds->data=$data;

		$totrow=$ds->getRowCount("CodCau");
	
		for($z=1;$z<=$totrow;$z++)
		{
			print "<tr class=celdas-blancas>";
			$ls_codigo=$data["CodCau"][$z];
			$ls_denominacion=$data["DenCau"][$z];
			$ls_tipo=$data["TipCau"][$z];
			$ls_contable=$data["EstAfeCon"][$z];
			$ls_presupuestaria=$data["EstAfePre"][$z];
			$ls_explicacion=$data["ExpCau"][$z];
			print "<td><a href=\"javascript: aceptar('$ls_codigo','$ls_denominacion','$ls_tipo','$ls_contable','$ls_presupuestaria','$ls_explicacion','$ls_status');\">".$ls_codigo."</a></td>";
			//print "<td>".$data["NomGru"][$z]."</td>";
			print "<td>".$data["DenCau"][$z]."</td>";
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
  function aceptar(codigo,denominacion,tipo,contable,presup,explicacion,hidstatus)
  {

	opener.document.form1.txtcodigo.value=codigo;
	opener.document.form1.txtdenominacion.value=denominacion;
	opener.document.form1.txtexplicacion.value=explicacion;
	opener.document.form1.hidstatus.value="C";
//	opener.document.form1.txtcodigo.readonly=true;
	switch(tipo)
	{
		case 'I':
			opener.document.form1.radiotipo[0].checked= true;
			break;
		case 'D':
			opener.document.form1.radiotipo[1].checked= true;
			break;
		case 'R':
			opener.document.form1.radiotipo[2].checked= true;
			break;
		case 'M':
			opener.document.form1.radiotipo[3].checked= true;
			break;

	}
	if (contable==1)
	{
		opener.document.form1.chkcontable.checked= true;
	}

	if (presup==1)
	{
		opener.document.form1.chkpresupuestaria.checked= true;
	}
	close();
  }
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_catdinamic_movimientos.php";
  f.submit();
  }
</script>
</html>
