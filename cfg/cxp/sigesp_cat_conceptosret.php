<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Conceptos de Retencion </title>
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
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
a:hover {
	color: #006699;
}
-->
</style></head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
  </p>
  <br>
  <div align="center">
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td height="22" colspan="3" align="right" class="titulo-celda">Cat&aacute;logo de Conceptos de Retencion </td>
      </tr>
      <tr>
        <td height="13" align="right">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="122" height="22" align="right">Codigo</td>
        <td width="238" height="22"><div align="left">
          <input name="codigo" type="text" id="codigo">        
        </div></td>
        <td width="138" height="22">&nbsp;</td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominaci&oacute;n</div></td>
        <td height="22" colspan="2"><div align="left">
          <input name="nombre" type="text" id="nombre">
<label></label>
<br>
          </div></td>
      </tr>
      <tr>
        <td height="13"><div align="right"></div></td>
        <td height="13" colspan="2"><div align="left"></div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar </a></div></td>
      </tr>
    </table>
	<?php
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_datastore.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
$ds=new class_datastore();
$io_sql=new class_sql($con);
$arr=$_SESSION["la_empresa"];
$as_codemp=$arr["codemp"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion    = $_POST["operacion"];
	$ls_codigo       = $_POST["codigo"];
	$ls_denominacion = "%".$_POST["nombre"]."%";
}
else
{
	$ls_operacion="";

}
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td style=text-align:center width=100>Código</td>";
print "<td style=text-align:center width=400>Denominación</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_cadena ="SELECT codconret,desact ".
                "  FROM sigesp_conceptoretencion ".
		        " WHERE codemp='".$as_codemp."'".
				"   AND codconret like '".$ls_codigo."%'".
				"   AND desact like '".$ls_denominacion."' ".
                " ORDER BY codconret";
	$rs_data=$io_sql->select($ls_cadena);
	$li_i=0;
	while(!$rs_data->EOF)
	{
		$li_i++;
		$ls_codconret=$rs_data->fields["codconret"];
		$ls_desact=$rs_data->fields["desact"];
		print "<tr class=celdas-blancas>";
		print "<td style=text-align:center width=100><a href=\"javascript: aceptar('$ls_codconret','$ls_desact');\">".$ls_codconret."</a></td>";
		print "<td style=text-align:left width=400>".$ls_desact."</td>";
		print "</tr>";	
		$rs_data->MoveNext();
	}
	print "</table>";
	if($li_i==0)
	{
	?>
		<script language="javascript">
		alert("No se han cargado los Conceptos de Retencion");
		close();
		</script>
	<?php
	}
}
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

  function aceptar(ls_codconret,ls_desact)
  {
    opener.document.form1.txtcodconret.value=ls_codconret;
	opener.document.form1.txtdenconret.value=ls_desact;
	close();
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cat_conceptosret.php";
	  f.submit();
  }
</script>
</html>