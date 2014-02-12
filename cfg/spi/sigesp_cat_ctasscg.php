<?php
//session_id('8675309');
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Cuentas Contables</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
  <br>
  <div align="center">
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td height="22" colspan="3" align="right" class="titulo-celda">Cat&aacute;logo de Cuentas Contables</td>
      </tr>
      <tr>
        <td height="13" align="right">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="122" height="22" align="right">Cuenta</td>
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
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar </a></div></td>
      </tr>
    </table>
	<br>
    <?php
require_once("../../shared/class_folder/sigesp_include.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
$dat=$_SESSION["la_empresa"];
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
$SQL=new class_sql($con);
$ds=new class_datastore();
$arr=$_SESSION["la_empresa"];
$as_codemp=$arr["codemp"];
$ls_codcon_ingreso=$arr["ingreso"];
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codigo=$_POST["codigo"]."%";
	$ls_denominacion="%".$_POST["nombre"]."%";
}
else
{
	$ls_operacion="";
}
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td  style=text-align:center width=100>Cuenta Contable</td>";
print "<td  style=text-align:center width=400>Denominación</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_cadena ="SELECT sc_cuenta, denominacion, status, asignado, distribuir," . 
		        "       enero, febrero, marzo, abril, mayo, junio, julio, agosto, septiembre, octubre, noviembre, diciembre,".
		        "       nivel, referencia FROM scg_cuentas ".
		        "WHERE codemp = '".$as_codemp."' AND sc_cuenta like '".$ls_codcon_ingreso."%'  AND sc_cuenta like '".$ls_codigo."' AND denominacion like '".$ls_denominacion."' ORDER BY sc_cuenta";
	$rs_cta=$SQL->select($ls_cadena);
	$data=$rs_cta;
	if($row=$SQL->fetch_row($rs_cta))
	{
		$data=$SQL->obtener_datos($rs_cta);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds->data=$data;
		$totrow=$ds->getRowCount("sc_cuenta");
		for($z=1;$z<=$totrow;$z++)
		{
			$cuenta=$data["sc_cuenta"][$z];
			$denominacion=$data["denominacion"][$z];
			$status=$data["status"][$z];
			if($status=="S")
			{
				print "<tr class=celdas-blancas>";
				print "<td style=text-align:center width=50>".$cuenta."</td>";
				print "<td style=text-align:left   width=450>".$denominacion."</td>";
			}
			else
			{
				print "<tr class=celdas-azules>";
				print "<td style=text-align:center width=50><a href=\"javascript: aceptar('$cuenta','$denominacion','$status');\">".$cuenta."</a></td>";
				print "<td style=text-align:left   width=450>".$denominacion."</td>";
			}
			print "</tr>";			
		}
	}
	else
	{
		$io_msg->message("No se han creado cuentas");
		print "<script language=JavaScript>";
		print " close();";
		print "<script>";
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

  function aceptar(cuenta,d,status)
  {
    opener.document.form1.txtcuenta.value=cuenta;
	opener.document.form1.txtdenominacion.value=d;
	close();
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cat_ctasscg.php";
	  f.submit();
  }

</script>
</html>
