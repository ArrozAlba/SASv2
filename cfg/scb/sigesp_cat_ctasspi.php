<?
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "opener.document.form1.submit();";
	print "close();";
	print "</script>";		
}
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/class_fecha.php");
require_once("../../shared/class_folder/class_sigesp_int.php");
require_once("../../shared/class_folder/class_sigesp_int_scg.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
$dat=$_SESSION["la_empresa"];
$int_scg=new class_sigesp_int_scg();
$msg=new class_mensajes();
$fun=new class_funciones();
$ds=new class_datastore();
$SQL=new class_sql($con);
$arr=$_SESSION["la_empresa"];
$as_codemp=$arr["codemp"];
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codigo=$_POST["codigo"]."%";
	$ls_denominacion="%".$_POST["nombre"]."%";
	$ls_codscg	= $_POST["txtcuentascg"]."%";
}
else
{
	$ls_operacion="";
	$ls_codscg="";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Cuentas de Ingreso</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
body {
	background-color: #EBEBEB;
}
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
  <table width="650" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Cuentas Ingreso </td>
    </tr>
  </table>
  <br>
  <div align="center">
    <table width="650" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td align="right" width="135">Codigo</td>
        <td width="122"><div align="left">
          <input name="codigo" type="text" id="codigo" size="22" maxlength="20">        
        </div></td>
        <td width="341">&nbsp;</td>
      </tr>
      <tr>
        <td><div align="right">Denominaci&oacute;n</div></td>
        <td colspan="2"><div align="left">
          <input name="nombre" type="text" id="nombre" size="72">
<label></label>
<br>
          </div></td>
      </tr>
      <tr>
        <td><div align="right">Cuenta Contable </div></td>
        <td colspan="2"><div align="left">
          <input name="txtcuentascg" type="text" id="txtcuentascg" size="22" maxlength="20">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar </a></div></td>
      </tr>
    </table>
	<br>
    <?

print "<table width=650 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Cuenta Ingreso</td>";
print "<td>Denominación</td>";
print "<td>Contable</td>";
print "<td>Disponible</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
							
	$ls_cadena =" SELECT *,(previsto+aumento-disminucion) as disponible 
				  FROM spi_cuentas 
		   		  WHERE codemp = '".$as_codemp."' AND spi_cuenta like '".$ls_codigo."' AND denominacion like '".$ls_denominacion."' AND sc_cuenta like '".$ls_codscg."' 
				  ORDER BY spi_cuenta";
	$rs_cta=$SQL->select($ls_cadena);
	if($rs_cta==false)
	{
		$msg->message($fun->uf_convertirmsg($SQL->message));
	}
	else
	{
		while($row=$SQL->fetch_row($rs_cta))
		{
			$cuenta=$row["spi_cuenta"];
			$denominacion=$row["denominacion"];
			$scgcuenta=$row["sc_cuenta"];
			$status=$row["status"];
			$disponible=$row["disponible"];
			if($status=="S")
			{
				print "<tr class=celdas-blancas>";
				print "<td>".$cuenta."</td>";
				print "<td  align=left>".$denominacion."</td>";
				print "<td  align=center>".$scgcuenta."</td>";
				print "<td  align=center width=119>".number_format($disponible,2,",",".")."</td>";
			}
			else
			{
				print "<tr class=celdas-azules>";
				print "<td><a href=\"javascript: aceptar('$cuenta','$denominacion','$scgcuenta','$status');\">".$cuenta."</a></td>";
				print "<td  align=left>".$denominacion."</td>";
				print "<td  align=center>".$scgcuenta."</td>";
				print "<td  align=center>".number_format($disponible,2,",",".")."</td>";				
			}
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
  function aceptar(cuenta,deno,scgcuenta,status)
  {
   /* opener.document.form1.txtcuentacontable.value=cuenta;
	opener.document.form1.txtdencuenta.value=deno;
	opener.document.form1.hidscgcuenta.value=scgcuenta;*/
	opener.document.form1.txtcuentaspi.value=cuenta; 
	opener.document.form1.txtdencuentaspi.value=deno; 
	opener.document.form1.hidscgcuenta.value=scgcuenta;
	close();
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cat_ctasspi.php";
	  f.submit();
  }	
</script>
</html>