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
<title>Catalogo de Colocaciones</title>
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
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Colocaciones </td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
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
        <td><input name="codigo" type="text" id="codigo"></td>
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
require_once("../shared/class_folder/class_funciones.php");
$fun=new class_funciones();
$ls_codemp=$arr["codemp"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codban="%".$_POST["codigo"]."%";
	$ls_cuenta="%".$_POST["cuenta"]."%";
	$ls_numcol="%".$_POST["denominacion"]."%";
}
else
{
	$ls_operacion="";
}
print "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Colocación</td>";
print "<td>Denominación</td>";
print "<td>Banco</td>";
print "<td>Cuenta</td>";
print "<td>monto</td>";
print "<td>Intereses</td>";
print "<td>Tasa</td>";
print "<td>Dias</td>";
print "<td>Status</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	/*$ls_sql=" SELECT a.codban as codban,b.nomban as nomban,a.ctaban as ctaban,c.dencta as dencta,a.numcol
			as numcol, a.dencol as dencol ,a.codtipcol as codtipcol,d.nomtipcol as nomtipcol,a.feccol
			as feccol,a.diacol as diacol,a.tascol as tascol,a.monto as monto,a.fecvencol as fecvencol,a.monint
			as monint,a.sc_cuenta as sc_cuenta,a.spi_cuenta as spi_cuenta,a.estreicol as estreicol
			FROM scb_colocacion a,scb_banco b,scb_ctabanco c,scb_tipocolocacion d
			WHERE a.codemp=b.codemp AND a.codemp=c.codemp AND a.codemp='".$ls_codemp."' AND a.codban like '".$ls_codban."' AND a.ctaban like '".$ls_cuenta."' AND a.numcol like '".$ls_numcol."' AND a.codban=b.codban AND b.codban=c.codban
			AND a.ctaban=c.ctaban AND a.codtipcol=d.codtipcol ";*/
			
			$ls_sql="SELECT a.codban as codban,b.nomban as nomban,a.ctaban as ctaban,c.dencta as dencta,a.numcol as numcol,
					 a.dencol as dencol ,a.codtipcol as codtipcol,d.nomtipcol as nomtipcol,a.feccol as feccol,
					 a.diacol as diacol,a.tascol as tascol,a.monto as monto,a.fecvencol as fecvencol,a.monint as monint,
					 a.sc_cuenta as sc_cuenta,e.denominacion as denominacion,a.spi_cuenta as spi_cuenta,a.estreicol as estreicol
					 FROM scb_colocacion a,scb_banco b,scb_ctabanco c,scb_tipocolocacion d,scg_cuentas e
					 WHERE a.codemp=b.codemp AND a.codemp=c.codemp AND a.codemp=e.codemp   AND a.codban=b.codban AND a.codban=c.codban AND a.ctaban=c.ctaban
					 AND a.codtipcol=d.codtipcol AND a.codemp='".$ls_codemp."' AND a.codban like '%".$ls_codban."%' AND a.ctaban like '%".$ls_cuenta."%'
					 AND a.numcol like '%".$ls_numcol."%' AND a.sc_cuenta=e.sc_cuenta";

			$rs_cta=$SQL->select($ls_sql);
			
			$data=$rs_cta;
			if($rs_cta==false)
			{
				$msg->message("No se han creado colocaciones");
			}
			else
			{
				if($row=$SQL->fetch_row($rs_cta))
				{
						$data=$SQL->obtener_datos($rs_cta);
						$arrcols=array_keys($data);
						$totcol=count($arrcols);
						$ds->data=$data;
						$totrow=$ds->getRowCount("numcol");
						
					for($z=1;$z<=$totrow;$z++)
					{
						print "<tr class=celdas-blancas>";
						$codban=$data["codban"][$z];
						$nomban=$data["nomban"][$z];
						$ctaban=$data["ctaban"][$z];
						$dencta=$data["dencta"][$z];
						$numcol=$data["numcol"][$z];
						$dencol=$data["dencol"][$z];
						$codtipcol=$data["codtipcol"][$z];
						$nomtipcol=$data["nomtipcol"][$z];
						$feccol=$fun->uf_convertirfecmostrar($data["feccol"][$z]);
						$diacol=$data["diacol"][$z];
						$tascol=$data["tascol"][$z];
						$monto =$data["monto"][$z];
						$fecvencol=$fun->uf_convertirfecmostrar($data["fecvencol"][$z]);
						$monint=$data["monint"][$z];
						$sc_cuenta=$data["sc_cuenta"][$z];
						$denscg=$data["denominacion"][$z];
						$spi_cuenta=$data["spi_cuenta"][$z];
						$status=$data["estreicol"][$z];
						print "<td align=center><a href=\"javascript: aceptar('$numcol','$dencol','$codban','$nomban','$ctaban','$dencta','$status','$codtipcol','$nomtipcol','$feccol',$monto,'$fecvencol',$monint,'$sc_cuenta','$denscg','$spi_cuenta','$diacol','$tascol');\">".$numcol."</a></td>";
						print "<td align=center>".$dencol."</td>";		
						print "<td align=center>".$nomban."</td>";
						print "<td align=center>".$ctaban."</td>";
						print "<td align=center>".number_format($monto,2,",",".")."</td>";
						print "<td align=center>".number_format($monint,2,",",".")."</td>";
						print "<td align=center>".$tascol."</td>";
						print "<td align=center>".$diacol."</td>";
						print "<td align=center>".$status."</td>";					
						print "</tr>";			
					}
				}
				else
				{
					$msg->message("No se han creado colocaciones");
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
 
  function aceptar(numcol,dencol,codban,nomban,ctaban,dencta,status,codtipcol,nomtipcol,feccol,monto,fecvencol,monint,sc_cuenta,denscg,spi_cuenta,diacol,tascol)
  {
		opener.document.form1.txtcolocacion.value  = numcol;
		opener.document.form1.txtdencolocacion.value = dencol;
		opener.document.form1.txttipocolocacion.value= codtipcol;
		opener.document.form1.txtnomtipcol.value= nomtipcol;
		opener.document.form1.txtcodban.value    = codban;
		opener.document.form1.txtdenban.value    = nomban;
		opener.document.form1.txtcuenta.value    = ctaban;
		opener.document.form1.txtdenominacion.value=dencta;
		if(status==1)
		{
			opener.document.form1.rb_reintegro[1].checked=true;
		}
		else
		{
			opener.document.form1.rb_reintegro[0].checked=true;
		}	
			
		opener.document.form1.select.value = diacol;
		opener.document.form1.txtdesde.value = feccol;
		opener.document.form1.txthasta.value = fecvencol;
		opener.document.form1.txttasa.value = tascol;
		opener.document.form1.txtmonto.value = monto;
		opener.document.form1.txtinteres.value = monint;
		opener.document.form1.txtcuentascg.value=sc_cuenta;
		opener.document.form1.txtdenominacionscg.value=denscg;
		opener.document.form1.txtcolocacion.readOnly=true;
		close();
  }
  
  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_catdinamic_colocacion.php";
	  f.submit();
  }
</script>
</html>
