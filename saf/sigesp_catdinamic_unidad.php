<?php
session_start();
$dat=$_SESSION["la_empresa"];
require_once("../shared/class_folder/sigesp_include.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
require_once("../shared/class_folder/class_datastore.php");
$ds=new class_datastore();
require_once("../shared/class_folder/class_sql.php");
$io_sql=new class_sql($con);
$ls_codemp=$dat["codemp"];
if(array_key_exists("submit",$_GET))
{
	$ls_submit=1;
}
else
{
	$ls_submit=0;
}
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codigo="%".$_POST["codigo"]."%";
	$ls_denominacion="%".$_POST["denominacion"]."%";
	$ls_submit=$_POST["txtsubmit"];
	$ls_estpro1="";
	$ls_estpro2="";
	$ls_estpro3="";
}
else
{
	$ls_operacion="";
	$ls_estpro1="";
	$ls_estpro2="";
	$ls_estpro3="";
}
if (isset($_GET["tipo"]))
{
	$ls_tipo=$_GET["tipo"];	
}
else
{
	$ls_tipo="";
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Unidades Administrativas </title>
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
	<input name="hidtipo" type="hidden" id="hidtipo" value="<?php print $ls_tipo?>">
    <input name="txtsubmit" type="hidden" id="txtsubmit" value="<?php print $ls_submit ?>">
  </p>
  	 <table width="535" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="531" colspan="2" class="titulo-celda">Cat&aacute;logo de Unidades Administrativas  </td>
    	</tr>
  </table>
	 <br>
	 <table width="535" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="111"><div align="right">Codigo</div></td>
        <td width="422" height="22"><div align="left">
          <input name="codigo" type="text" id="codigo">        
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Denominacion</div></td>
        <td height="22"><div align="left">
          <input name="denominacion" type="text" id="denominacion">
        </div></td>
      </tr>
      <tr>
        <td height="13"><div align="right"></div></td>
        <td><div align="left">
        </div>
       </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"> Buscar</a></div></td>
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
	$ls_sql="SELECT * FROM spg_unidadadministrativa".
			" WHERE codemp='".$ls_codemp."'".
			" AND coduniadm like '".$ls_codigo."'".
			" AND denuniadm like '".$ls_denominacion."'";
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
	else
	{
	  $ls_sql=$ls_sql;
	 }
	$rs_unidad=$io_sql->select($ls_sql);
	$data=$rs_unidad;
	if($row=$io_sql->fetch_row($rs_unidad))
	{
		$data=$io_sql->obtener_datos($rs_unidad);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds->data=$data;
		$totrow=$ds->getRowCount("coduniadm");
		$io_sql->free_result($rs_unidad);
		$io_sql->close();
		for($z=1;$z<=$totrow;$z++)
		{
			print "<tr class=celdas-blancas>";
			$ls_codigo=$data["coduniadm"][$z];
			$ls_denominacion=$data["denuniadm"][$z];
			$ls_estreq=$data["estemireq"][$z];
			
			if (empty ($data["codestpro1"][$z]))
			{
			  $ls_codestpro1="";
			}
			else
			{
			  $ls_codestpro1=$data["codestpro1"][$z];
			}
			
			
			if (empty ($data["codestpro2"][$z]))
			{
			  $ls_codestpro2="";
			}
			else
			{
			  $ls_codestpro2=$data["codestpro2"][$z];
			}
			
			if (empty ($data["codestpro3"][$z]))
			{
			  $ls_codestpro3="";
			}
			else
			{
			  $ls_codestpro3=$data["codestpro3"][$z];
			}
			
			
			print "<td align=center><a href=\"javascript: aceptar('$ls_codigo','$ls_denominacion','$ls_estreq','$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_submit');\">".$ls_codigo."</a></td>";
			print "<td>".$ls_denominacion."</td>";
			print "<td align=center>".$ls_estreq."</td>";
			print "<td align=center>".$ls_codestpro1."</td>";
			print "<td align=center>".$ls_codestpro2."</td>";
			print "<td align=center>".$ls_codestpro3."</td>";
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
	function aceptar(ls_codigo,ls_denominacion,ls_estreq,ls_codest1,ls_codest2,ls_codest3,ls_submit)
	{
			ls_tipo=document.form1.hidtipo.value;
			 
			if (ls_tipo=='1')
			{
			  opener.document.form1.txtcoduni.value=ls_codigo;
			  opener.document.form1.txtdenuni.value=ls_denominacion;
			}
			else if (ls_tipo=='2')
			{
			   opener.document.form1.txtcoduni2.value=ls_codigo;
			   opener.document.form1.txtdenuni2.value=ls_denominacion;
			  
			}
			else
			{
				opener.document.form1.txtcoduni.value=ls_codigo;
				opener.document.form1.txtdenuni.value=ls_denominacion;
				if(ls_submit==1)
				{
					opener.document.form1.operacion.value="VERIFICARUNIDAD";
					opener.document.form1.submit();
				}
				
			}
		close();
	}
	function ue_search()
	{
		f=document.form1;
		ls_tipo=document.form1.hidtipo.value;
		f.operacion.value="BUSCAR";
		f.action="sigesp_catdinamic_unidad.php?tipo="+ls_tipo;
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
			alert("Seleccione la Estructura de nivel Anterior");
		}
	}
</script>
</html>
