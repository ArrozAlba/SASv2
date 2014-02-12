<?php
//session_id('8675309');
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
<title>Catalogo tipos de Cuenta</title>
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
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Tipos de Cuenta </td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67"><div align="right">Codigo</div></td>
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
	$ls_codigo="%".$_POST["codigo"]."%";
	$ls_denominacion="%".$_POST["denominacion"]."%";
}
else
{
	$ls_operacion="";

}
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Código </td>";
print "<td>Denominación</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_sql="SELECT * FROM scb_tipocuenta WHERE  codtipcta like '".$ls_codigo."' AND nomtipcta like '".$ls_denominacion."' ";
	$rs_cta=$SQL->select($ls_sql);
	$data=$rs_cta;
	if($row=$SQL->fetch_row($rs_cta))
	{
		$data=$SQL->obtener_datos($rs_cta);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds->data=$data;
		$totrow=$ds->getRowCount("codtipcta");
	
		for($z=1;$z<=$totrow;$z++)
		{
			print "<tr class=celdas-blancas>";
			$codigo=$data["codtipcta"][$z];
			$denominacion=$data["nomtipcta"][$z];
			print "<td><a href=\"javascript: aceptar('$codigo','$denominacion');\">".$codigo."</a></td>";
			print "<td>".$denominacion."</td>";
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
  function aceptar(codigo,deno)
  {
    opener.document.form1.txttipocuenta.value=codigo;
    opener.document.form1.txtdentipocuenta.value=deno;
	close();
  }
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_tipoctas.php";
  f.submit();
  }
</script>
</html>
