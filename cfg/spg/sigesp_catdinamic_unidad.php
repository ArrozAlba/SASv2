<?php
session_start();
$dat=$_SESSION["la_empresa"];
require_once("../../shared/class_folder/sigesp_include.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
require_once("../../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
require_once("../../shared/class_folder/class_datastore.php");
$ds=new class_datastore();
require_once("../../shared/class_folder/class_sql.php");
$SQL=new class_sql($con);
$ls_codemp=$dat["codemp"];
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codigo="%".$_POST["codigo"]."%";
	$ls_denominacion="%".$_POST["denominacion"]."%";
	$ls_estpro1=$_POST["codestpro1"];
	$ls_estpro2=$_POST["codestpro2"];
	$ls_estpro3=$_POST["codestpro3"];
}
else
{
	$ls_operacion="";
	$ls_estpro1="";
	$ls_estpro2="";
	$ls_estpro3="";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Unidades Ejecutoras </title>
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
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  	 <table width="564" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="560" colspan="2" class="titulo-celda">Cat&aacute;logo de Unidades Ejecutoras  </td>
    	</tr>
	 </table>
	 <br>
	 <table width="564" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="111"><div align="right">Codigo</div></td>
        <td width="451"><div align="left">
          <input name="codigo" type="text" id="codigo">        
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Denominacion</div></td>
        <td><div align="left">
          <input name="denominacion" type="text" id="denominacion">
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><?php print $dat["nomestpro1"];?></div></td>
        <td><div align="left">
          <input name="codestpro1" type="text" id="codestpro1" size="22" maxlength="20" style="text-align:center" readonly>
          <a href="javascript:catalogo_estpro1();"><img src="../../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a>
          <input name="denestpro1" type="text" class="sin-borde" id="denestpro1" size="53" readonly>          
        </div>
       </td>
      </tr>
      <tr>
        <td><div align="right"> <?php print $dat["nomestpro2"];?></div>         </td>
        <td><div align="left">
          <input name="codestpro2" type="text" id="codestpro22" size="22" maxlength="6" style="text-align:center" readonly>
          <a href="javascript:catalogo_estpro2();"><img src="../../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a>
          <input name="denestpro2" type="text" class="sin-borde" id="denestpro2" size="53" readonly>
        </div></td>
      </tr>
      <tr>
        <td><div align="right">    <?php print $dat["nomestpro3"];?></div>      </td>
        <td><div align="left">
          <input name="codestpro3" type="text" id="codestpro32" size="22" maxlength="3" style="text-align:center" readonly>
          <a href="javascript:catalogo_estpro3();"><img src="../../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a>
          <input name="denestpro3" type="text" class="sin-borde" id="denestpro3" size="53" readonly>
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
	<br>
    <?php

print "<table width=564 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Código </td>";
print "<td>Denominación</td>";
print "<td>Emite Req.</td>";
print "<td>".$dat["nomestpro1"]."</td>";
print "<td>".$dat["nomestpro2"]."</td>";
print "<td>".$dat["nomestpro3"]."</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_sql="SELECT coduniadm,denuniadm,estemireq,codestpro1,codestpro2,codestpro3,coduniadmsig FROM spg_unidadadministrativa WHERE codemp='".$ls_codemp."' AND coduniadm like '".$ls_codigo."' AND denuniadm like '".$ls_denominacion."'";
	if($ls_estpro1!="")
	{
		 $ls_sql=$ls_sql." AND codestpro1='".$ls_estpro1."'";
		 if($ls_estpro2!="")
		 {
	 		$ls_sql=$ls_sql." AND codestpro2='".$ls_estpro2."'";
		 }	
		 if($ls_estpro3!="")
		 {
	 		$ls_sql=$ls_sql." AND codestpro3='".$ls_estpro3."'";
		 }	
	}
	$rs_unidad=$SQL->select($ls_sql);
	$data=$rs_unidad;
	if($row=$SQL->fetch_row($rs_unidad))
	{
		$data=$SQL->obtener_datos($rs_unidad);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds->data=$data;
		$totrow=$ds->getRowCount("coduniadm");
		$SQL->free_result($rs_unidad);
		$SQL->close();
		for($z=1;$z<=$totrow;$z++)
		{
			print "<tr class=celdas-blancas>";
			$codigo=$data["coduniadm"][$z];
			$denominacion=$data["denuniadm"][$z];
			$estreq=$data["estemireq"][$z];
			$codestpro1=trim($data["codestpro1"][$z]);
			$codestpro2=trim($data["codestpro2"][$z]);
			$codestpro3=trim($data["codestpro3"][$z]);
			$coduniadmsig=$data["coduniadmsig"][$z];
			print "<td align=center><a href=\"javascript: aceptar('$codigo','$denominacion','$estreq','$codestpro1','$codestpro2','$codestpro3','$coduniadmsig');\">".$codigo."</a></td>";
			print "<td>".$denominacion."</td>";
			print "<td align=center>".$estreq."</td>";
			print "<td align=center>".$codestpro1."</td>";
			print "<td align=center>".$codestpro2."</td>";
			print "<td align=center>".$codestpro3."</td>";
			print "</tr>";			
		}
	}
	else
	{
		$io_msg->message("No se han definido unidades administrativas");		
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
  function aceptar(codigo,deno,estreq,codest1,codest2,codest3,coduniadmsig)
  {
    opener.document.form1.txtcodigo.value=codigo;
    opener.document.form1.txtdenominacion.value=deno;
	if(estreq==1)
	{
		opener.document.form1.estreq.checked=true;
	}
	else
	{
		opener.document.form1.estreq.checked=false;
	}
	opener.document.form1.codestpro1.value=codest1;
	opener.document.form1.codestpro2.value=codest2;
	opener.document.form1.codestpro3.value=codest3;
	opener.document.form1.status.value='C';
	opener.document.form1.txtcodigo.readOnly=true;
	//opener.document.form1.operacion.value="BUSCAR";
	//opener.document.form1.submit();
	close();
  }
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_catdinamic_unidad.php";
  f.submit();
  }
function catalogo_estpro1()
{
	   pagina="sigesp_cat_public_estpro1.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
}
function catalogo_estpro2()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	denestpro1=f.denestpro1.value;
	if((codestpro1!="")&&(denestpro1!=""))
	{
		pagina="sigesp_cat_public_estpro2.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura nivel 1");
	}
}
function catalogo_estpro3()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	denestpro1=f.denestpro1.value;
	codestpro2=f.codestpro2.value;
	denestpro2=f.denestpro2.value;
	if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!=""))
	{
    	pagina="sigesp_cat_public_estpro3.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
	else
	{
		pagina="sigesp_cat_public_estpro.php";
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
}
</script>
</html>
