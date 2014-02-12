<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "close();";
	print "opener.document.form1.submit();";
	print "</script>";		
}
$arr=$_SESSION["la_empresa"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Fuentes de Financiamiento por Estructura</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:hover {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style></head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
  </p>
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Fuente de Financiamiento por Estructura</td>
   	   </tr>
  </table>
	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67"><div align="right">C&oacute;digo</div></td>
        <td width="431"><div align="left">
          <input name="codigo" type="text" id="codigo">        
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Nombre</div></td>
        <td><div align="left">
          <input name="denominacion" type="text" id="denominacion">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<br>
<?php
include("../shared/class_folder/sigesp_include.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
require_once("../shared/class_folder/class_sql.php");
$SQL=new class_sql($con);
$ds=new class_datastore();
require_once("sigesp_spg_c_mod_presupuestarias.php");
$in_classcmp=new sigesp_spg_c_mod_presupuestarias();

$ls_codemp=$arr["codemp"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codigo="%".$_POST["codigo"]."%";
	$ls_denominacion="%".$_POST["denominacion"]."%";
	if(array_key_exists("codestpro1",$_POST))
	{
		$ls_codestpro1=$_POST["codestpro1"];
	}
	else
	{
		$ls_codestpro1="";
	}
	
	if(array_key_exists("codestpro2",$_POST))
	{
		$ls_codestpro2=$_POST["codestpro2"];
	}
	else
	{
		$ls_codestpro2="";
	}
	
	if(array_key_exists("codestpro3",$_POST))
	{
		$ls_codestpro3=$_POST["codestpro3"];
	}
	else
	{
		$ls_codestpro3="";
	}
	
	if(array_key_exists("codestpro4",$_POST))
	{
		$ls_codestpro4=$_POST["codestpro4"];
	}
	else
	{
		$ls_codestpro4="";
	}
	
	if(array_key_exists("codestpro5",$_POST))
	{
		$ls_codestpro5=$_POST["codestpro5"];
	}
	else
	{
		$ls_codestpro5="";
	}
	if(array_key_exists("estcla",$_POST))
	{
		$ls_estcla=$_POST["estcla"];
	}
	else
	{
		$ls_estcla="";
	}
	
	if(array_key_exists("cuenta",$_POST))
	{
		$ls_cuenta=$_POST["cuenta"];
	}
	else
	{
		$ls_cuenta="";
	}
}
else
{
	$ls_operacion="BUSCAR";
	$ls_codigo="%%";
	$ls_denominacion="%%";
	if(array_key_exists("codestpro1",$_GET))
	{
		$ls_codestpro1=$_GET["codestpro1"];
	}
	else
	{
		$ls_codestpro1="";
	}
	
	if(array_key_exists("codestpro2",$_GET))
	{
		$ls_codestpro2=$_GET["codestpro2"];
	}
	else
	{
		$ls_codestpro2="";
	}
	
	if(array_key_exists("codestpro3",$_GET))
	{
		$ls_codestpro3=$_GET["codestpro3"];
	}
	else
	{
		$ls_codestpro3="";
	}
	
	if(array_key_exists("codestpro4",$_GET))
	{
		$ls_codestpro4=$_GET["codestpro4"];
	}
	else
	{
		$ls_codestpro4="";
	}
	
	if(array_key_exists("codestpro5",$_GET))
	{
		$ls_codestpro5=$_GET["codestpro5"];
	}
	else
	{
		$ls_codestpro5="";
	}
	if(array_key_exists("estcla",$_GET))
	{
		$ls_estcla=$_GET["estcla"];
	}
	else
	{
		$ls_estcla="";
	}
	
	if(array_key_exists("cuenta",$_GET))
	{
		$ls_cuenta=$_GET["cuenta"];
	}
	else
	{
		$ls_cuenta="";
	}
}
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Código </td>";
print "<td>Denominación</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$la_codestpro[0] = $ls_codestpro1;
	$la_codestpro[1] = $ls_codestpro2;
	$la_codestpro[2] = $ls_codestpro3;
	$la_codestpro[3] = $ls_codestpro4;
	$la_codestpro[4] = $ls_codestpro5;
	$la_codestpro[5] = $ls_estcla;
	$la_codestpro[6] = $ls_cuenta;
	$rs_fuefin=$in_classcmp->uf_load_fuentes_financiamiento_estructura($la_codestpro,$ls_codigo,$ls_denominacion);
	$data=$rs_fuefin;
	if(($rs_fuefin===false))
	{
		$io_msg->message("Error en select");
	}
	else
	{
		if($row=$SQL->fetch_row($rs_fuefin))
		{
			$data=$SQL->obtener_datos($rs_fuefin);
			$arrcols=array_keys($data);
			$totcol=count($arrcols);
			$ds->data=$data;
			$totrow=$ds->getRowCount("codfuefin");
			for($z=1;$z<=$totrow;$z++)
			{
					print "<tr class=celdas-blancas>";
					$codigo=$data["codfuefin"][$z];
					$denominacion=$data["denfuefin"][$z];
					print "<td><a href=\"javascript: aceptar('$codigo','$denominacion');\">".$codigo."</a></td>";
					print "<td>".$denominacion."</td>";
					print "</tr>";			
			}
						
				if(($totrow==1)&&($data["codfuefin"][1]=="--"))
				{
				 $io_msg->message("No se han asociado Fuentes de Financiamiento a la Estructura y/o Cuenta seleccionada...Verifique por Favor");
				}
			
		}
		else
		{
			$io_msg->message("No se han asociado Fuentes de Financiamiento a la Estructura y/o Cuenta seleccionada...Verifique por Favor");
			print "<script language='JavaScript'>";
			print "close()";
			print "</script>";
		}
	}
}
print "</table>";
?>
</div>
<input name="codestpro1" type="hidden" id="codestpro1" value="<?php print $ls_codestpro1; ?>">
<input name="codestpro2" type="hidden" id="codestpro2" value="<?php print $ls_codestpro2; ?>">
<input name="codestpro3" type="hidden" id="codestpro3" value="<?php print $ls_codestpro3; ?>">
<input name="codestpro4" type="hidden" id="codestpro4" value="<?php print $ls_codestpro4; ?>">
<input name="codestpro5" type="hidden" id="codestpro5" value="<?php print $ls_codestpro5; ?>">
<input name="estcla" type="hidden" id="estcla" value="<?php print $ls_estcla; ?>">
<input name="cuenta" type="hidden" id="cuenta" value="<?php print $ls_cuenta; ?>">
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function aceptar(codigo,deno)
  {
    opener.document.form1.txtcodfuefin.value=codigo;
    opener.document.form1.txtdenfuefin.value=deno;
	close();
  }
 
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_fuentefinanciamiento.php?aa_codestpro="<?php $la_codestpro?>;
  f.submit();
  }
</script>
</html>
