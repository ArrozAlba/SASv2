<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "close();";
	print "</script>";		
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo Grupos</title>
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
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Grupos</td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67"><div align="right">Empresa</div></td>
        <td width="431" height="22"><div align="left">
          <input name="txtnombre" type="text" id="txtnombre2">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Nombre</div></td>
        <td height="22"><div align="left">          <input name="txtnota" type="text" id="txtnota">
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
//include("class_sigesp_int_scg.php");
//$int_scg=new class_sigesp_int_scg();
require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");
$ds=new class_datastore();
$io_sql=new class_sql($con);
$arr=$_SESSION["la_empresa"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_empresa="%".$_POST["txtempresa"]."%";
	$ls_nombre="%".$_POST["txtnombre"]."%";
	$ls_nombrevie="%".$_POST["txtnombrevie"]."%";
	$ls_nota="%".$_POST["txtnota"]."%";
	$ls_status="%".$_POST["hidstatus"]."%";
}
else
{
	$ls_operacion="";

}
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
//print "<td>Empresa </td>";
print "<td>Nombre</td>";
print "<td>Nota</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_sql="SELECT * FROM sss_grupos".
			" WHERE codemp LIKE '".$ls_empresa."'".
			" AND nomgru LIKE '".$ls_nombre."'";
    $rs_cta=$io_sql->select($ls_sql);
    $data=$rs_cta;
	if($row=$io_sql->fetch_row($rs_cta))
	{
		$data=$io_sql->obtener_datos($rs_cta);
	
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds->data=$data;

		$totrow=$ds->getRowCount("codemp");
	
		for($z=1;$z<=$totrow;$z++)
		{
			print "<tr class=celdas-blancas>";
			$ls_empresa=$data["codemp"][$z];
			$ls_codgru=$data["codgru"][$z];
			$ls_nombre=$data["nomgru"][$z];
			$ls_nombrevie=$data["nomgru"][$z];
			$ls_nota=$data["nota"][$z];
			print "<td><a href=\"javascript: aceptar('$ls_empresa','$ls_codgru','$ls_nombre','$ls_nombrevie','$ls_nota','$ls_status');\">".$ls_nombre."</a></td>";
			//print "<td>".$data["NomGru"][$z]."</td>";
			print "<td>".$data["nota"][$z]."</td>";
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
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function aceptar(prov,codgru,d,v,n,hidstatus)
  {

	opener.document.form1.txtempresa.value=prov;
	opener.document.form1.txtcodigo.value=codgru;
	opener.document.form1.txtnombre.value=d;
	opener.document.form1.txtnombrevie.value=v;
	opener.document.form1.txtnota.value=n;
	opener.document.form1.hidstatus.value="C";
	close();
  }
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_sss_cat_grupos.php";
  f.submit();
  }
</script>
</html>
