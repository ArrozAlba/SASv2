<?php
session_start();
$arr=$_SESSION["la_empresa"];
require_once("../shared/class_folder/sigesp_include.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
require_once("../shared/class_folder/class_mensajes.php");
$msg=new class_mensajes();
require_once("../shared/class_folder/class_datastore.php");
$ds=new class_datastore();
require_once("../shared/class_folder/class_sql.php");
$SQL=new class_sql($con);

$ls_codemp=$arr["codemp"];
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codigo="%".$_POST["txtcodestprog3"]."%";
	$ls_denominacion="%".$_POST["denominacion"]."%";
	if(array_key_exists("tipo",$_GET))
	{
		$ls_tipo=$_GET["tipo"];
	}
	else
	{
		$ls_tipo="";
	}

}
else
{
	$ls_operacion="";
	if(array_key_exists("tipo",$_GET))
	{
		$ls_tipo=$_GET["tipo"];
	}
	else
	{
		$ls_tipo="";
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Programatica Nivel 3 <?php print $arr["nomestpro3"] ?></title>
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
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo <?php print $arr["nomestpro3"] ?>  </td>
    	</tr>
	 </table>
	 <br>
	 <table width="550" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="118"><div align="right">Codigo</div></td>
        <td width="380"><input name="txtcodestprog3" type="text" id="txtcodestprog3"  size="22" maxlength="3" style="text-align:center"></td>
      </tr>
      <tr>
        <td><div align="right">Denominacion</div></td>
        <td><div align="left">
          <input name="denominacion" type="text" id="denominacion"  size="72" maxlength="100">
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
print "<td>".$arr["nomestpro2"]."</td>";
print "<td>Código </td>";
print "<td>Denominación</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_sql="SELECT a.codestpro1 as codestpro1,a.denestpro1 as denestpro1,b.codestpro2 as codestpro2,
					b.denestpro2 as denestpro2,c.codestpro3 as codestpro3,c.denestpro3 as denestpro3
			FROM spg_ep1 a,spg_ep2 b,spg_ep3 c
			WHERE a.codemp=b.codemp AND a.codemp=c.codemp AND a.codemp='".$ls_codemp."' AND a.codestpro1=b.codestpro1 AND a.codestpro1=c.codestpro1  AND b.codestpro2=c.codestpro2 
			AND c.codestpro3 like '".$ls_codigo."' AND c.denestpro3 like '".$ls_denominacion."'";
	$rs_cta=$SQL->select($ls_sql);
	$data=$rs_cta;
	
	if($row=$SQL->fetch_row($rs_cta))
	{
		$data=$SQL->obtener_datos($rs_cta);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds->data=$data;
		$totrow=$ds->getRowCount("codestpro3");
	
		for($z=1;$z<=$totrow;$z++)
		{
			print "<tr class=celdas-blancas>";
			$codestprog1=$data["codestpro1"][$z];
			$denestprog1=$data["denestpro1"][$z];
			$codestprog2=$data["codestpro2"][$z];
			$denestprog2=$data["denestpro2"][$z];
			$codigo=$data["codestpro3"][$z];
			$denominacion=$data["denestpro3"][$z];
			if($ls_tipo=="")
			{
				print "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$codestprog1','$denestprog1','$codestprog2','$denestprog2','$codigo','$denominacion');\">".trim($codestprog1)."</td>";
				print "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$codestprog1','$denestprog1','$codestprog2','$denestprog2','$codigo','$denominacion');\">".trim($codestprog2)."</td>";
				print "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$codestprog1','$denestprog1','$codestprog2','$denestprog2','$codigo','$denominacion');\">".trim($codigo)."</a></td>";
				print "<td width=130 align=\"left\">".trim($denominacion)."</td>";
				print "</tr>";	
			}
			if($ls_tipo=="apertura")
			{
				print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_apertura('$codestprog1','$denestprog1','$codestprog2','$denestprog2','$codigo','$denominacion');\">".trim($codestprog1)."</td>";
				print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_apertura('$codestprog1','$denestprog1','$codestprog2','$denestprog2','$codigo','$denominacion');\">".trim($codestprog2)."</td>";
				print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_apertura('$codestprog1','$denestprog1','$codestprog2','$denestprog2','$codigo','$denominacion');\">".trim($codigo)."</a></td>";
				print "<td width=130 align=\"left\">".trim($denominacion)."</td>";
				print "</tr>";	
			}
			if($ls_tipo=="progrep")
			{
				print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_progrep('$codestprog1','$denestprog1','$codestprog2','$denestprog2','$codigo','$denominacion');\">".trim($codestprog1)."</td>";
				print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_progrep('$codestprog1','$denestprog1','$codestprog2','$denestprog2','$codigo','$denominacion');\">".trim($codestprog2)."</td>";
				print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_progrep('$codestprog1','$denestprog1','$codestprog2','$denestprog2','$codigo','$denominacion');\">".trim($codigo)."</a></td>";
				print "<td width=130 align=\"left\">".trim($denominacion)."</td>";
				print "</tr>";	
			}
			if($ls_tipo=="reporte")		
			{
				print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rep('$codestprog1','$codestprog2','$codigo');\">".trim($codestprog1)."</td>";
				print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rep('$codestprog1','$codestprog2','$codigo');\">".trim($codestprog2)."</td>";
				print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rep('$codestprog1','$codestprog2','$codigo');\">".trim($codigo)."</a></td>";
				print "<td width=130 align=\"left\">".trim($denominacion)."</td>";
				print "</tr>";	
			}
			if($ls_tipo=="rephas")		
			{
				print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rephas('$codestprog1','$codestprog2','$codigo');\">".trim($codestprog1)."</td>";
				print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rephas('$codestprog1','$codestprog2','$codigo');\">".trim($codestprog2)."</td>";
				print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rephas('$codestprog1','$codestprog2','$codigo');\">".trim($codigo)."</a></td>";
				print "<td width=130 align=\"left\">".trim($denominacion)."</td>";
				print "</tr>";	
			}
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

  function aceptar(codestprog1,denestprog1,codestprog2,denestprog2,codestprog3,deno3)
  {
    opener.document.form1.denestpro1.value=denestprog1;
	opener.document.form1.codestpro1.value=codestprog1;
    opener.document.form1.denestpro2.value=denestprog2;
	opener.document.form1.codestpro2.value=codestprog2;
    opener.document.form1.denestpro3.value=deno3;
	opener.document.form1.codestpro3.value=codestprog3;
//	opener.document.form1.operacionestprog3.value="BUSCAR";
//	opener.document.form1.submit();
	close();
  }
  
  function aceptar_apertura(codestprog1,denestprog1,codestprog2,denestprog2,codestprog3,deno3)
  {
    opener.document.form1.denestpro1.value=denestprog1;
	opener.document.form1.codestpro1.value=codestprog1;
    opener.document.form1.denestpro2.value=denestprog2;
	opener.document.form1.codestpro2.value=codestprog2;
    opener.document.form1.denestpro3.value=deno3;
	opener.document.form1.codestpro3.value=codestprog3;
 	opener.document.form1.operacion.value="CARGAR";
    opener.document.form1.submit();
	close();
  }
  
  function aceptar_progrep(codestprog1,denestprog1,codestprog2,denestprog2,codestprog3,deno3)
  {
    opener.document.form1.denestpro1.value=denestprog1;
	opener.document.form1.codestpro1.value=codestprog1;
    opener.document.form1.denestpro2.value=denestprog2;
	opener.document.form1.codestpro2.value=codestprog2;
    opener.document.form1.denestpro3.value=deno3;
	opener.document.form1.codestpro3.value=codestprog3;
 	opener.document.form1.operacion.value="CARGAR";
    opener.document.form1.submit();
	close();
  }
  
  function aceptar_apertura(codestprog1,denestprog1,codestprog2,denestprog2,codestprog3,deno3)
  {
    opener.document.form1.denestpro1.value=denestprog1;
	opener.document.form1.codestpro1.value=codestprog1;
    opener.document.form1.denestpro2.value=denestprog2;
	opener.document.form1.codestpro2.value=codestprog2;
    opener.document.form1.denestpro3.value=deno3;
	opener.document.form1.codestpro3.value=codestprog3;
 	opener.document.form1.operacion.value="CARGAR";
    opener.document.form1.submit();
	close();
  }
  
  function aceptar_rep(codestprog1,codestprog2,codestprog3)
  {
	opener.document.form1.codestpro1.value=codestprog1;
	opener.document.form1.codestpro2.value=codestprog2;
	opener.document.form1.codestpro3.value=codestprog3;
	opener.document.form1.codestpro3.readOnly=true;
	close();
  }
  
  function aceptar_rephas(codestprog1,codestprog2,codestprog3)
  {
	opener.document.form1.codestpro1.value=codestprog1;
	opener.document.form1.codestpro2.value=codestprog2;
	opener.document.form1.codestpro3h.value=codestprog3;
	opener.document.form1.codestpro3h.readOnly=true;
	close();
  }

  
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_public_estpro.php?tipo=<?php print $ls_tipo; ?>";
  f.submit();
  }
</script>
</html>
