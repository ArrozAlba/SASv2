<?php
session_start();
$arr=$_SESSION["la_empresa"];
require_once("sigesp_include.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
require_once("class_mensajes.php");
$msg=new class_mensajes();
require_once("class_folder/class_datastore.php");
$ds=new class_datastore();
require_once("class_folder/class_sql.php");
$SQL=new class_sql($con);

$ls_codemp=$arr["CodEmp"];
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codestprog1=$_POST["codestpro1"];
	$ls_denestprog1=$_POST["denestpro1"];
	$ls_codestprog2=$_POST["codestpro2"];
	$ls_denestprog2=$_POST["denestpro2"];
	$ls_codigo="%".$_POST["txtcodestprog3"]."%";
	$ls_denominacion="%".$_POST["denominacion"]."%";
}
else
{
	$ls_operacion="";
	$ls_codestprog1=$_GET["codestpro1"];
	$ls_denestprog1=$_GET["denestpro1"];
	$ls_codestprog2=$_GET["codestpro2"];
	$ls_denestprog2=$_GET["denestpro2"];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Programatica Nivel 2 <?php print $arr["NomEstPro2"] ?> </title>
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
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  	 <table width="550" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo <?php print $arr["NomEstPro2"] ?>  </td>
    	</tr>
	 </table>
	 <br>
	 <table width="550" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="118"><div align="right"><?php print $arr["NomEstPro1"]?></div></td>
        <td width="380"><div align="left">
          <input name="codestpro1" type="text" id="codestpro1" value="<?php print $ls_codestprog1 ?>" size="22" maxlength="20" readonly style="text-align:center">        
          <input name="denestpro1" type="text" class="sin-borde" id="denestpro1" size="50" value="<?php print $ls_denestprog1 ?>" readonly>
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><?php print $arr["NomEstPro2"]?></div></td>
        <td><div align="left">
          <input name="codestpro2" type="text" id="codestpro2" value="<?php print  $ls_codestprog2?>" size="22" maxlength="6" readonly style="text-align:center">
          <input name="denestpro2" type="text" id="denestpro2" value="<?php print $ls_denestprog2?>" size="50" class="sin-borde" readonly>
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Codigo</div></td>
        <td><input name="txtcodestprog3" type="text" id="txtcodestprog3"  size="22" maxlength="3" style="text-align:center"></td>
      </tr>
      <tr>
        <td><div align="right">Denominacion</div></td>
        <td><div align="left">
          <input name="denominacion" type="text" id="denominacion"  size="72" maxlength="100">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<br>
    <?php

print "<table width=550 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>".$arr["NomEstPro1"]."</td>";
print "<td>".$arr["NomEstPro2"]."</td>";
print "<td>Código </td>";
print "<td>Denominación</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_sql="SELECT CodEstPro1,CodEstPro2,CodEstPro3,DenEstPro3 FROM spg_ep3 WHERE CodEmp='".$ls_codemp."' AND CodEstPro1 ='".$ls_codestprog1."' AND CodEstPro2 ='".$ls_codestprog2."' AND CodEstPro3 like '".$ls_codigo."' AND DenEstPro3 like '".$ls_denominacion."' ";
	$rs_cta=$SQL->select($ls_sql);
	$data=$rs_cta;
	
	if($row=$SQL->fetch_row($rs_cta))
	{
		$data=$SQL->obtener_datos($rs_cta);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds->data=$data;
		$totrow=$ds->getRowCount("CodEstPro3");
	
		for($z=1;$z<=$totrow;$z++)
		{
			print "<tr class=celdas-blancas>";
			$codestprog1=$data["CodEstPro1"][$z];
			$codestprog2=$data["CodEstPro2"][$z];
			$codigo=$data["CodEstPro3"][$z];
			$denominacion=$data["DenEstPro3"][$z];
			print "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$codigo','$denominacion');\">".trim($codestprog1)."</td>";
			print "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$codigo','$denominacion');\">".trim($codestprog2)."</td>";
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
    opener.document.form1.denestpro3.value=deno;
	opener.document.form1.codestpro3.value=codestprog2;
	close();
  }
  
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_public_estpro3.php";
  f.submit();
  }
</script>
</html>