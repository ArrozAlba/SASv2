<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo Beneficiarios</title>
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
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Beneficiarios</td>
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
          <input name="nombre" type="text" id="nombre">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="left"></div></td>
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
$msg=new class_mensajes();
require_once("../shared/class_folder/class_sql.php");
$SQL=new class_sql($con);
$ds=new class_datastore();
$arr=$_SESSION["la_empresa"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codigo="%".$_POST["codigo"]."%";
	$ls_nombre="%".$_POST["nombre"]."%";
}
else
{
	$ls_operacion="";

}
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Cedula </td>";
print "<td>Nombre del Beneficiario</td>";
print "<td>Cuenta Contable</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_sql=" SELECT ced_bene,nombene,apebene,sc_cuenta ".
	        " FROM  rpc_beneficiario   ".
			" WHERE ced_bene like '".$ls_codigo."' AND nombene like '".$ls_nombre."'";
    $rs_cta=$SQL->select($ls_sql);
	if($row=$SQL->fetch_row($rs_cta))
	{
		while($row=$SQL->fetch_row($rs_cta))
		{
			print "<tr class=celdas-blancas>";
			$ls_cedula=$row["ced_bene"];
			$ls_nombene=$row["nombene"];
			$ls_apebene=$row["apebene"];
			$ls_sc_cuenta=$row["sc_cuenta"];
			$ls_nomapeben=$ls_nombene.", ".$ls_apebene;
			print "<td><a href=\"javascript: aceptar('$ls_cedula','$ls_nombene','$ls_sc_cuenta');\">".$ls_cedula."</a></td>";
			print "<td>".$row["nombene"]."</td>";
			print "<td>".$row["sc_cuenta"]."</td>";
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
  function aceptar(cedula,nombre,cuenta)
  {
	opener.document.form1.txtcedben.value=cedula;
    opener.document.form1.txtnomben.value=nombre;
    opener.document.form1.txtscctaben.value=cuenta;
	close();
  }
  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_catdinamic_bene.php";
	  f.submit();
  }
</script>
</html>