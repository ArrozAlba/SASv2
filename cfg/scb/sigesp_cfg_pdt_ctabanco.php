<?php
session_start();
$arr=$_SESSION["la_empresa"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cuentas</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/number_format.js"></script>
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
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
	color: #006699#006699;
}
-->
</style></head>

<body>
<form name="form1" method="post" action="">
<?php

require_once("../../shared/class_folder/sigesp_include.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
require_once("../../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
require_once("../../shared/class_folder/class_sql.php");
$SQL=new class_sql($con);
$ds=new class_datastore();
require_once("../../shared/class_folder/class_funciones.php");
$fun=new class_funciones();
$ls_codemp=$arr["codemp"];
require_once("sigesp_c_cuentas_banco.php");
$io_ctaban = new sigesp_c_cuentas_banco();

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codigo=$_POST["txtcodban"];
	$ls_ctaban=$_POST["cuenta"];
}
else
{
	$ls_operacion="";
	$ls_codigo="";
	$ls_ctaban="";
}
?>
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Cuentas </td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td height="22"><div align="right">Banco</div></td>
        <td height="22"><input name="txtcodban" type="text" id="txtcodban"></td>
      </tr>
      <tr>
        <td width="100"><div align="right">Cuenta</div></td>
        <td width="398"><div align="left">
          <input name="cuenta" type="text" id="cuenta">        
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<br>
    <?php
print "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Banco</td>";
print "<td>Cuenta </td>";
print "<td>Denominación</td>";
print "<td></td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_sql="SELECT scb_ctabanco.ctaban as ctaban,scb_ctabanco.dencta as dencta,scb_ctabanco.codban as codban,".
			"       scb_banco.nomban as nomban,scb_ctabanco.codtipcta as codtipcta,scb_tipocuenta.nomtipcta as nomtipcta".
			"  FROM scb_ctabanco,scb_tipocuenta,scb_banco  ".
			" WHERE scb_ctabanco.codemp='".$ls_codemp."'".
			"   AND scb_ctabanco.codtipcta=scb_tipocuenta.codtipcta".
			"   AND scb_ctabanco.codban=scb_banco.codban".
			"   AND scb_ctabanco.ctaban <> '-------------------------'".
			"   AND scb_banco.nomban like '%".$ls_codigo."%'".
			"   AND scb_ctabanco.ctaban like '%".$ls_ctaban."%'".
			" ORDER BY scb_banco.nomban";
			print "4__".$ls_sql;
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
						$ls_codban=$data["codban"][$z];
						$ls_nomban=$data["nomban"][$z];
						$ls_ctaban=$data["ctaban"][$z];
						$ls_dencta=$data["dencta"][$z];
						print "<td><input type=text name=txtdenban".$z." id=txtdenban".$z." value='".$ls_nomban."' class=sin-borde readonly style=text-align:left size=25 ><input type=hidden name=txtcodban".$z." id=txtcodban".$z." value='".$ls_codban."' class=sin-borde readonly style=text-align:center size=17 ></td>";
						print "<td><input type=text name=txtctaban".$z." id=txtctaban".$z." value='".$ls_ctaban."' class=sin-borde readonly style=text-align:left size=30 ></td>";
						print "<td><input type=text name=txtdencta".$z." id=txtdencta".$z." value='".$ls_dencta."' class=sin-borde readonly style=text-align:left size=40 ></td>";
						print "<td><a href='javascript: ue_agregar(".$z.");'><img src=../../shared/imagebank/tools15/aprobado.gif width=15 height=15 class=sin-borde></a></td>";
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
  function ue_agregar(row)
  {
	f=document.form1;
	li_gridtotrows=ue_calcular_total_fila_opener("txtcodban");
	lb_valido=true;
	codban=eval("f.txtcodban"+row+".value");
	nomban=eval("f.txtdenban"+row+".value");
	ctaban=eval("f.txtctaban"+row+".value");
	dencta=eval("f.txtdencta"+row+".value");
	for(li_j=1; (li_j<=li_gridtotrows)&& lb_valido; li_j++)
	{
		ls_codbangrid=eval("opener.document.form1.txtcodban"+li_j+".value");
		ls_ctabangrid=eval("opener.document.form1.txtctaban"+li_j+".value");
		if((ls_codbangrid==codban)&&(ls_ctabangrid==ctaban))
		{
			alert("La cuenta ya existe en el grid.");
			lb_valido=false;
		}
	
	}
	if(lb_valido)
	{
		obj=eval("opener.document.form1.txtcodban"+li_gridtotrows+".value='"+codban+"'");
		obj=eval("opener.document.form1.txtdenban"+li_gridtotrows+".value='"+nomban+"'");
		obj=eval("opener.document.form1.txtctaban"+li_gridtotrows+".value='"+ctaban+"'");
		obj=eval("opener.document.form1.txtdencta"+li_gridtotrows+".value='"+dencta+"'");
		opener.document.form1.operacion.value="AGREGARDETALLE";
		opener.document.form1.submit();
	}
  }
  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cfg_pdt_ctabanco.php";
	  f.submit();
  }
  
function ue_calcular_total_fila_opener(campo)
{
	existe=true;
	li_i=1;
	while(existe)
	{
		existe=opener.document.getElementById(campo+li_i);
		if(existe!=null)
		{
			li_i=li_i+1;
		}
		else
		{
			existe=false;
			li_i=li_i-1;
		}
	}
	return li_i;
}
  
</script>
</html>
