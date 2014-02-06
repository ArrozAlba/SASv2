<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo de Cuentas Contables</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
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
<?php
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_sigesp_int.php");
require_once("../shared/class_folder/class_sigesp_int_scg.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
$int_scg=new class_sigesp_int_scg();
$msg=new class_mensajes();
$ds=new class_datastore();
$SQL=new class_sql($con);
$arr=$_SESSION["la_empresa"];
$as_codemp=$arr["codemp"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion    = $_POST["operacion"];
	$ls_codigo       = "%".$_POST["codigo"]."%";
	$ls_denominacion = "%".$_POST["nombre"]."%";
    $ls_resultado    = $_POST["txtresultado"];
}
else
{
	$ls_operacion="";
    $ls_resultado=$_GET["txtresultado"];
}
?>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion"></p>
  <div align="center">
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr class="titulo-celda">
        <td height="22" colspan="3" align="right"><div align="center">Cat&aacute;logo de Cuentas Contables </div></td>
      </tr>
      <tr>
        <td height="13" align="right">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="84" height="22" align="right">Cuenta</td>
        <td width="276" height="22"><div align="left">
          <input name="codigo" type="text" id="codigo" size="40" maxlength="25" style="text-align:center">
          <input name="txtresultado" type="hidden" id="txtresultado" value="<?php print $ls_resultado ?>">
        </div></td>
        <td width="138" height="22">&nbsp;</td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominaci&oacute;n</div></td>
        <td height="22" colspan="2"><div align="left">
          <input name="nombre" type="text" id="nombre" size="65" maxlength="254" style="text-align:left">
<label></label>
<br>
          </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right"></div></td>
        <td height="22" colspan="2"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a></div></td>
      </tr>
    </table>
	<br>
<?php
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td  style=text-align:center width=100>Cuenta Contable</td>";
print "<td  style=text-align:center width=400>Denominación</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{  
	$ls_cadena =" SELECT sc_cuenta,status,denominacion FROM scg_cuentas ".
		        " WHERE codemp = '".$as_codemp."' AND sc_cuenta like '".$ls_resultado."%' ORDER BY sc_cuenta";
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
				print "<td style=text-align:center width=100><a href=\"javascript: aceptar('$cuenta');\">".$cuenta."</a></td>";
				print "<td style=text-align:left   width=400>".$denominacion."</td>";
			}
			else
			{
				print "<tr class=celdas-azules>";
				print "<td style=text-align:center width=100><a href=\"javascript: aceptar('$cuenta');\">".$cuenta."</a></td>";
				print "<td style=text-align:left   width=400>".$denominacion."</td>";
			}
			print "</tr>";			
		}
	}
	else
	{ ?>
		<script language="javascript">
		  alert("No se han creado Cuentas Contables para este criterio !!!");
		</script>
      <?php
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

  function aceptar(cuenta)
  {
    fop    = opener.document.form1;
	ls_cuenta = cuenta;
	objeto = ls_cuenta.substring(0,1); 
	if (objeto=='1')
	   {
	     fop.txtctafinanciera.value=cuenta;   
	     fop.txtctafinanciera.readOnly=true;
	   }
	else
	   {
	     fop.txtctafiscal.value=cuenta;
	     fop.txtctafiscal.readOnly=true;
	   }
    close();
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cfg_cat_scgcuentas_financiera.php";
	  f.submit();
  }
</script>
</html>