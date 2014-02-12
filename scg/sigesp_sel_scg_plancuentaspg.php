<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Cuentas Contables</title>
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
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
a:hover {
	color: #006699;
}
-->
</style></head>

<body>
<form name="form1" method="post" action="">
  <p align="left">
    <input name="operacion" type="hidden" id="operacion">
</p>
  <div align="left">
    <table width="500" border="0" align="left" cellpadding="1" cellspacing="1">
      <tr>
        <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Cuentas Contables</td>
      </tr>
    </table>
    <p>&nbsp;</p>
    <p><br>
    </p>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td align="right" width="122">Cuenta</td>
        <td width="238"><div align="left">
            <input name="codigo" type="text" id="codigo">        
        </div></td>
        <td width="138">&nbsp;</td>
      </tr>
      <tr>
        <td><div align="right">Denominaci&oacute;n</div></td>
        <td colspan="2"><div align="left">
            <input name="nombre" type="text" id="nombre">
  <label></label>
  <br>
          </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar </a></div></td>
      </tr>
    </table>
    <br>
    <?php
require_once("../shared/class_folder/sigesp_include.php");
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_sigesp_int.php");
require_once("../shared/class_folder/class_sigesp_int_scg.php");
$dat=$_SESSION["la_empresa"];
$io_int_scg=new class_sigesp_int_scg();
$io_msg=new class_mensajes();
$ds=new class_datastore();
$io_sql=new class_sql($io_connect);
$arr=$_SESSION["la_empresa"];
$as_codemp=$arr["codemp"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$li_fila=$_POST["fila"];
	$ls_codigo=$_POST["codigo"]."%";
	$ls_denominacion="%".$_POST["nombre"]."%";

	
}
else
{
	$ls_operacion="";
	$li_fila=$_GET["fila"];

}
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Cuenta Contable</td>";
print "<td>Denominación</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_cadena ="SELECT sc_cuenta, denominacion, status, asignado, distribuir," . 
		        "       enero, febrero, marzo, abril, mayo, junio, julio, agosto, septiembre, octubre, noviembre, diciembre,".
		        "       nivel, referencia FROM scg_cuentas ".
		        "WHERE  codemp = '".$as_codemp."' AND sc_cuenta like '".$ls_codigo."' AND denominacion like '".$ls_denominacion."' ORDER BY sc_cuenta";
//$ls_sql="SELECT SC_cuenta,denominacion FROM SIGESP_Plan_unico ";
	$rs_cta=$io_sql->select($ls_cadena);
	$data=$rs_cta;
	if($row=$io_sql->fetch_row($rs_cta))
	{
		$data=$io_sql->obtener_datos($rs_cta);
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
				print "<td>".$cuenta."</td>";
				print "<td  align=left>".$denominacion."</td>";
			}
			else
			{
				print "<tr class=celdas-azules>";
				print "<td><a href=\"javascript: aceptar('$cuenta','$denominacion','$status');\">".$cuenta."</a></td>";
				print "<td  align=left>".$denominacion."</td>";
			}
			print "</tr>";			
		}
	}
	else
	{
		$io_msg->message("No se han definido cuentas ");
		print "<script language=JavaScript>";
		print " close();";
		print "<script>";
	}
}
print "</table>";
?>
    <input name="fila" type="hidden" id="fila" value="<?php print $li_fila; ?>">
  </div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

  function aceptar(cuenta,d,status)
  {
	  f=document.form1;
      fila=f.fila.value;
  	  eval("opener.document.form1.txtcuentares"+fila+".value='"+cuenta+"'");
	 close();
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_sel_scg_plancuentaspg.php";
	  f.submit();
  }
</script>
</html>