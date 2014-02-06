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
$la_datemp=$_SESSION["la_empresa"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cuentas Bancarias</title>
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
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Cuetnas Bancarias</td>
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
$io_siginc=new sigesp_include();
$io_connect=$io_siginc->uf_conectar();
$io_msg=new class_mensajes();
require_once("../shared/class_folder/class_sql.php");
$io_sql=new class_sql($io_connect);
$io_ds=new class_datastore();
require_once("../shared/class_folder/class_funciones.php");
$io_function=new class_funciones();
$ls_codemp=$la_datemp["codemp"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codigo="%".$_POST["codigo"]."%";
	$ls_ctaban="%".$_POST["cuenta"]."%";
	$ls_denominacion="%".$_POST["denominacion"]."%";
}
else
{
	$ls_operacion="";

}
print "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
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
	$ls_sql="SELECT a.ctaban as ctaban,a.dencta as dencta,a.sc_cuenta as sc_cuenta,d.denominacion as denominacion,a.codban as codban,c.nomban as nomban,a.codtipcta as codtipcta,b.nomtipcta as nomtipcta,a.fecapr as fecapr,a.feccie as feccie,a.estact as estact ".
			" FROM scb_ctabanco a,scb_tipocuenta b,scb_banco c,scg_cuentas d ".
			" WHERE a.codemp='".$ls_codemp."' AND a.codtipcta=b.codtipcta AND a.codban=c.codban AND a.codban like '".$ls_codigo."'  AND a.ctaban like '".$ls_ctaban."' ".
			" AND (a.sc_cuenta=d.sc_cuenta AND a.codemp=d.codemp)".
			"   AND ctaban IN (SELECT codintper ".
			"					 FROM sss_permisos_internos ".
			"				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
			"				    UNION ".
			"				   SELECT codintper ".
			"				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
			"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)";

			$rs_data=$io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$msg->message("No se han creado cuentas de banco");
			}
			else
			{
				if($row=$io_sql->fetch_row($rs_data))
				{
						$la_data=$io_sql->obtener_datos($rs_data);
						$la_arrcols=array_keys($la_data);
						$li_totcol=count($la_arrcols);
						$io_ds->data=$la_data;
						$li_totrow=$io_ds->getRowCount("ctaban");
						
					for($li_i=1;$li_i<=$li_totrow;$li_i++)
					{
						print "<tr class=celdas-blancas>";
						$ls_codban=$la_data["codban"][$li_i];
						$ls_nomban=$la_data["nomban"][$li_i];
						$ls_ctaban=$la_data["ctaban"][$li_i];
						$ls_dencta=$la_data["dencta"][$li_i];
						$ls_codtipcta=$la_data["codtipcta"][$li_i];
						$ls_nomtipcta=$la_data["nomtipcta"][$li_i];
						$ls_ctascg=$la_data["sc_cuenta"][$li_i];
						$ls_denctascg=$la_data["denominacion"][$li_i];
						$ld_fecapertura=$io_function->uf_convertirfecmostrar($la_data["fecapr"][$li_i]);
						$ld_feccierre=$io_function->uf_convertirfecmostrar($la_data["feccie"][$li_i]);
						$ls_status=$la_data["estact"][$li_i];
						print "<td><a href=\"javascript: aceptar('$ls_codban','$ls_nomban','$ls_ctaban','$ls_dencta','$ls_ctascg','$ls_denctascg','$ld_fecapertura','$ld_feccierre','$ls_status','$ls_codtipcta','$ls_nomtipcta');\">".$ls_ctaban."</a></td>";
						print "<td>".$ls_dencta."</td>";
						print "<td>".$ls_nomban."</td>";
						print "<td>".$ls_nomtipcta."</td>";
						print "<td>".$ls_ctascg."</td>";
						print "<td>".$ls_denctascg."</td>";																			
						print "<td>".$ld_fecapertura."</td>";					
						print "</tr>";			
					}
				}
				else
				{
					$msg->message("No se han creado cuentas de banco");
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
  function aceptar(codban,nomban,ctaban,dencta,ctascg,denctascg,fecapertura,feccierre,status,codtipcta,nomtipcta)
  {
    opener.document.form1.txtcodigo.value=ctaban;
    opener.document.form1.txtdencta.value=dencta;
	opener.document.form1.txttipocuenta.value=codtipcta;
	opener.document.form1.txtdentipocuenta.value=nomtipcta;
	opener.document.form1.txtcodban.value=codban;
	opener.document.form1.txtdenban.value=nomban;
	opener.document.form1.txtcuentascg.value=ctascg;
	opener.document.form1.txtdenominacionscg.value=denctascg;
	opener.document.form1.txtfechaapertura.value=fecapertura;
	opener.document.form1.txtfechacierre.value=feccierre;
	if(status==1)
	{
		opener.document.form1.status.checked=true;
	}
	else
	{
		opener.document.form1.status.checked=false;
	}
	
	opener.document.form1.txtcodigo.readOnly=true;
	close();
  }
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_catdinamic_ctabanco.php";
  f.submit();
  }
</script>
</html>
