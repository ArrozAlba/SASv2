<?php
session_start();
$arr=$_SESSION["la_empresa"];
require_once("../shared/class_folder/sigesp_include.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
$msg=new class_mensajes();
require_once("../shared/class_folder/class_sql.php");
$SQL=new class_sql($con);
$ds=new class_datastore();

$ls_codemp=$arr["codemp"];
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codestprog1=$_POST["codestpro1"];
	$ls_denestprog1=$_POST["denestpro1"];
	$ls_codigo="%".$_POST["codestprog2"]."%";
	$ls_denominacion="%".$_POST["denominacion"]."%";
}
else
{
	$ls_operacion="BUSCAR";
	$ls_codestprog1=$_GET["codestpro1"];
	$ls_denestprog1=$_GET["denestpro1"];
	$ls_codigo="%%";
	$ls_denominacion="%%";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Programatica Nivel 2 <?php print $arr["nomestpro2"] ?> </title>
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
  	 <table width="550" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo <?php print $arr["nomestpro2"] ?>  </td>
    	</tr>
  </table>
	 <br>
	 <table width="550" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="137"><div align="right"><?php print $arr["nomestpro1"]?></div></td>
        <td width="461"><div align="left">
          <input name="codestpro1" type="text" id="codestpro1" value="<?php print $ls_codestprog1 ?>" size="22" maxlength="20" readonly>        
          <input name="denestpro1" type="text" class="sin-borde" id="denestpro1" size="50" value="<?php print $ls_denestprog1 ?>" readonly>
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Codigo</div></td>
        <td><div align="left">
          <input name="codestprog2" type="text" id="codestprog2" size="22" maxlength="6">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Denominacion</div></td>
        <td><div align="left">
          <input name="denominacion" type="text" id="denominacion" size="72" maxlength="100">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
	<br>
    <?php

print "<table width=550 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>".$arr["nomestpro1"]."</td>";
print "<td>Código </td>";
print "<td>Denominación</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_sql="SELECT codestpro1,codestpro2,denestpro2 FROM spg_ep2 WHERE codemp='".$ls_codemp."' AND codestpro1 ='".$ls_codestprog1."' AND codestpro2 like '".$ls_codigo."' AND denestpro2 like '".$ls_denominacion."' ";
	$rs_cta=$SQL->select($ls_sql);
	$data=$rs_cta;
	
	if($row=$SQL->fetch_row($rs_cta))
	{
		$data=$SQL->obtener_datos($rs_cta);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds->data=$data;
		$totrow=$ds->getRowCount("codestpro1");
	
		for($z=1;$z<=$totrow;$z++)
		{
			print "<tr class=celdas-blancas>";
			$codestprog1=$data["codestpro1"][$z];
			$codigo=$data["codestpro2"][$z];
			$denominacion=$data["denestpro2"][$z];
			print "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$codigo','$denominacion');\">".trim($codestprog1)."</td>";
			print "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$codigo','$denominacion');\">".trim($codigo)."</a></td>";
			print "<td width=130 align=\"left\">".trim($denominacion)."</td>";
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
  function aceptar(codestprog2,deno)
  {
    opener.document.form1.denestpro2.value=deno;
	opener.document.form1.codestpro2.value=codestprog2;
    opener.document.form1.denestpro3.value="";
	opener.document.form1.codestpro3.value="";
//	opener.document.form1.operacionestprog2.value="BUSCAR";
	//opener.document.form1.submit();
	close();
  }
  
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_public_estpro2.php";
  f.submit();
  }
</script>
</html>
