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
<title>Cat&aacute;logo de Cuentas</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
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
<?php

include("../shared/class_folder/sigesp_include.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
require_once("../shared/class_folder/class_sql.php");
$SQL=new class_sql($con);
$ds=new class_datastore();
require_once("../shared/class_folder/class_funciones.php");
$fun=new class_funciones();
$ls_codemp=$arr["codemp"];
require_once("sigesp_c_cuentas_banco.php");
$io_ctaban = new sigesp_c_cuentas_banco();

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codigo=$_POST["codigo"];
	$ls_denban=$_POST["denban"];
	$ls_ctaban="%".$_POST["cuenta"]."%";
	$ls_denominacion="%".$_POST["denominacion"]."%";
}
else
{
	$ls_operacion="BUSCAR";
	$ls_codigo=$_GET["codigo"];
	$ls_denban=$_GET["denban"];
	$ls_ctaban="%%";
	$ls_denominacion="%%";
}
?>
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  	 <table width="700" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td colspan="2" class="titulo-celda"><div align="center">Catalogo de Cuentas del Banco</div></td>
    	</tr>
  </table>
	 <br>
	 <table width="700" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67"><div align="right">Cuenta</div></td>
        <td width="431"><div align="left">
          <input name="cuenta" type="text" id="cuenta">        
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Nombre</div></td>
        <td><div align="left">
          <input name="denominacion" type="text" id="denominacion" size="60">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Banco</div></td>
        <td><input name="denban" type="text" id="denban" value="<?php print $ls_denban;?>">
        <input name="codigo" type="hidden" id="codigo" value="<?php print $ls_codigo;?>"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
	<br>
    <?php
print "<table width=700 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Código </td>";
print "<td>Denominación</td>";
print "<td>Banco</td>";
print "<td>Tipo de Cuenta</td>";
print "<td>Cuenta Contable</td>";
print "<td>Denominación Cta. Contable</td>";
print "<td>Apertura</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_sql=" SELECT a.ctaban as ctaban,a.dencta as dencta,a.sc_cuenta as sc_cuenta,d.denominacion as denominacion, ".
	        "        a.codban as codban,c.nomban as nomban,a.codtipcta as codtipcta,b.nomtipcta as nomtipcta,a.fecapr as fecapr, ".
			"        a.feccie as feccie,a.estact as estact ".
			" FROM   scb_ctabanco a,scb_tipocuenta b,scb_banco c,scg_cuentas d ".
			" WHERE  a.codemp='".$ls_codemp."' AND a.codtipcta=b.codtipcta AND a.codban=c.codban AND ".
			"        a.codban like '%".$ls_codigo."%'  AND a.ctaban like '".$ls_ctaban."' AND ".
			"        a.dencta like '".$ls_denominacion."' AND (a.sc_cuenta=d.sc_cuenta AND a.codemp=d.codemp) ";
			$rs_cta=$SQL->select($ls_sql);
			if($rs_cta===false)
			{
				$io_msg->message("Error en select");
			}
			else
			{
				if($row=$SQL->fetch_row($rs_cta))
				{
					$data=$SQL->obtener_datos($rs_cta);
					$arrcols=array_keys($data);
					$totcol=count($arrcols);
					$ds->data=$data;
					$totrow=$ds->getRowCount("ctaban");
						
					for($z=1;$z<=$totrow;$z++)
					{
						print "<tr class=celdas-blancas>";
						$codban=$data["codban"][$z];
						$nomban=$data["nomban"][$z];
						$ctaban=$data["ctaban"][$z];
						$dencta=$data["dencta"][$z];
						$codtipcta=$data["codtipcta"][$z];
						$nomtipcta=$data["nomtipcta"][$z];
						$ctascg=$data["sc_cuenta"][$z];
						$denctascg=$data["denominacion"][$z];
						$fecapertura=$fun->uf_convertirfecmostrar($data["fecapr"][$z]);
						$feccierre=$fun->uf_convertirfecmostrar($data["feccie"][$z]);
						$lb_valido=$io_ctaban->uf_verificar_saldo($codban,$ctaban,&$adec_saldo);
						if(!$lb_valido)
						{
							$msg->message($io_ctaban->is_msg_error);
						}
						$ldec_saldo=$adec_saldo;
						$status=$data["estact"][$z];
						print "<td><a href=\"javascript: aceptar('$codban','$nomban','$ctaban','$dencta','$ctascg','$denctascg','$fecapertura','$feccierre','$status','$codtipcta','$nomtipcta','$ldec_saldo');\">".$ctaban."</a></td>";
						print "<td>".$dencta."</td>";
						print "<td>".$nomban."</td>";
						print "<td>".$nomtipcta."</td>";
						print "<td>".$ctascg."</td>";
						print "<td>".$denctascg."</td>";																			
						print "<td>".$fecapertura."</td>";					
						print "</tr>";			
					}
				}
				else
				{
					$io_msg->message("No se han definido Cuentas de Banco");
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
  function aceptar(codban,nomban,ctaban,dencta,ctascg,denctascg,fecapertura,feccierre,status,codtipcta,nomtipcta,saldo)
  {
    opener.document.form1.txtcuenta.value=ctaban;
	close();
  }
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_ctabanco.php";
  f.submit();
  }
</script>
</html>
