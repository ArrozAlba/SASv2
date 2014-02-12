<?php
//session_id('8675309');
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo Eventos</title>
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
<link href="css/ventanas.css" rel="stylesheet" type="text/css">
<link href="css/general.css" rel="stylesheet" type="text/css">
<link href="css/tablas.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
    <input name="status" type="hidden" id="status">
  </p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Eventos </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67"><div align="right">Evento</div></td>
        <td width="431"><div align="left">
          <input name="txtevento" type="text" id="txtevento">        
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Descripcion</div></td>
        <td><div align="left">
          <input name="txtdescripcion" type="text" id="txtdescripcion">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Buscar</a></div></td>
      </tr>
    </table>
  <br>
    <?php
include("sigesp_include.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
//include("class_sigesp_int_scg.php");
//$int_scg=new class_sigesp_int_scg();
include("class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
include("class_folder/class_datastore.php");
include("class_folder/class_sql.php");
$ds=new class_datastore();
$io_sql=new class_sql($con);
$arr=$_SESSION["la_empresa"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_evento="%".$_POST["txtevento"]."%";
	$ls_descripcion="%".$_POST["txtdescripcion"]."%";
	$ls_status="%".$_POST["status"]."%";
}
else
{
	$ls_operacion="";

}
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Evento </td>";
print "<td>Descripcion</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_sql="SELECT evento,deseve FROM sss_eventos".
			" WHERE evento LIKE '".$ls_evento."'".
			" AND DesEve LIKE '".$ls_descripcion."'";
    $rs_cta=$io_sql->select($ls_sql);
    $data=$rs_cta;
	if($row=$io_sql->fetch_row($rs_cta))
	{
		$data=$io_sql->obtener_datos($rs_cta);
	
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds->data=$data;

		$totrow=$ds->getRowCount("evento");
	
		for($z=1;$z<=$totrow;$z++)
		{
			print "<tr class=celdas-blancas>";
			$ls_evento=$data["evento"][$z];
			$ls_descripcion=$data["deseve"][$z];
			print "<td><a href=\"javascript: aceptar('$ls_evento','$ls_descripcion','$ls_status');\">".$ls_evento."</a></td>";
			print "<td>".$data["deseve"][$z]."</td>";
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
  function aceptar(prov,d,status)
  {
	opener.document.form1.txtevento.value=prov;
	opener.document.form1.txtdescripcion.value=d;
	opener.document.form1.status.value="C";
	close();
  }
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_catdinamic_eventos.php";
  f.submit();
  }
</script>
</html>
