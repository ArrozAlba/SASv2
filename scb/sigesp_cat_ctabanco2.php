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

require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("sigesp_c_cuentas_banco.php");
require_once("class_funciones_banco.php");/////agregado 13/12/2007

$in		   = new sigesp_include();
$con	   = $in->uf_conectar();
$io_msg    = new class_mensajes();
$io_sql    = new class_sql($con);
$fun       = new class_funciones();
$ls_codemp = $arr["codemp"];
$io_ctaban = new sigesp_c_cuentas_banco();
$io_update 		 = new class_funciones_banco();/////agregado el 13/12/2003

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion    = $_POST["operacion"];
	 $ls_codigo       = $_POST["codigo"];
	 $ls_nomban       = $_POST["hidnomban"];
	 $ls_ctaban       = "%".$_POST["cuenta"]."%";
	 $ls_denominacion = "%".$_POST["denominacion"]."%";
   }
else
   {
	 $ls_operacion    = "BUSCAR";
	 $ls_codigo       = $_GET["codigo"];
	 $ls_nomban       = $_GET["hidnomban"];
	 $ls_ctaban       = "%%";
	 $ls_denominacion = "%%";
   }
?>
<table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr class="titulo-celda">
        <td height="22" colspan="2"><input name="operacion" type="hidden" id="operacion">
        <input name="codigo" type="hidden" id="codigo" value="<?php print $ls_codigo;?>">
          <input name="hidnomban" type="hidden" id="hidnomban" value="<?php print $ls_nomban ?>">
          Cat&aacute;logo de Cuentas <?php print $ls_nomban ?></td>
       </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="67" height="22"><div align="right">Cuenta</div></td>
        <td width="431" height="22"><div align="left">
          <input name="cuenta" type="text" id="cuenta" size="35" maxlength="25" style="text-align:center">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Nombre</div></td>
        <td height="22"><div align="left">
          <input name="denominacion" type="text" id="denominacion" size="60">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div>
		  <input name="txtcuenta" type="hidden" id="txtcuenta" value="<?php print $ls_cuenta;?>">
		 <input name="txtcodban" type="hidden" id="txtcodban" value="<?php print $ls_codban;?>">
		 <input name="txtdocumento" type="hidden" id="txtdocumento" value="<?php print $ls_numche;?>">
		 <input name="txtlectura" type="hidden" id="txtlectura" value="<?php print $ls_lectura;?>"></td>
      </tr>
    </table>
	 <div align="center"><br>
<?php
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
			" WHERE a.codemp='".$ls_codemp."' AND a.codban like '%".$ls_codigo."%'  AND a.ctaban like '".$ls_ctaban."' AND a.dencta like '".$ls_denominacion."'".
			"   AND a.codtipcta=b.codtipcta AND a.codban=c.codban AND (a.sc_cuenta=d.sc_cuenta AND a.codemp=d.codemp)".
			"   AND ctaban IN (SELECT codintper ".
			"					 FROM sss_permisos_internos ".
			"				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
			"				    UNION ".
			"				   SELECT codintper ".
			"				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
			"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)";
	$rs_data = $io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
	     $io_msg->message("Error en select");
	   }
 	else
	   {
	     $li_numrows = $io_sql->num_rows($rs_data);
		 if ($li_numrows>0)
		    {
			  while ($row=$io_sql->fetch_row($rs_data))
			        {
					  print "<tr class=celdas-blancas>";
					  $ls_codban 	  = $row["codban"];
					  $ls_nomban 	  = $row["nomban"];
					  $ls_ctaban      = $row["ctaban"];
					  $ls_dencta      = $row["dencta"];
					  $ls_codtipcta   = $row["codtipcta"];
					  $ls_nomtipcta   = $row["nomtipcta"];
					  $ls_ctascg      = $row["sc_cuenta"];
					  $ls_denctascg   = $row["denominacion"];
					  $ls_fecapertura = $fun->uf_convertirfecmostrar($row["fecapr"]);
					  $ls_feccierre   = $fun->uf_convertirfecmostrar($row["feccie"]);
					  $io_ctaban->uf_verificar_saldo($ls_codban,$ls_ctaban,&$adec_saldo);					  
					  if ($adec_saldo>0)
					     {
						   echo "<tr class=celdas-azules>";						   
						 }
					  else
					     {
						   echo "<tr class=celdas-blancas>"; 
						 }
					  $ldec_saldo = number_format($adec_saldo,2,',','.');
					  $ls_status  = $row["estact"];
					  print "<td><a href=\"javascript: aceptar('$ls_codban','$ls_nomban','$ls_ctaban','$ls_dencta','$ls_ctascg','$ls_denctascg','$ls_fecapertura','$ls_feccierre','$ls_status','$ls_codtipcta','$ls_nomtipcta','$ldec_saldo');\">".$ls_ctaban."</a></td>";
					  print "<td>".$ls_dencta."</td>";
					  print "<td>".$ls_nomban."</td>";
					  print "<td>".$ls_nomtipcta."</td>";
					  print "<td>".$ls_ctascg."</td>";
					  print "<td>".$ls_denctascg."</td>";																			
					  print "<td>".$ls_fecapertura."</td>";					
					  print "</tr>";			
					}
			} 
	     else
		    {?>
	          <script language="javascript">
			    alert("No se han creado Cuentas Bancarias !!!");
				close();
			  </script>
	     <?php
			}
		}
}
print "</table>";

if ($ls_operacion=="TOMAR")
   {
     $li_cfgchq = $_SESSION["la_empresa"]["confi_ch"];
	 if ($li_cfgchq=='1')//Generación Automática de los Cheques.
	    {
		  $ls_ctaban = $_POST["txtcuenta"];
		  $ls_codban = $_POST["codigo"];	
		  $ls_codusu = $_SESSION["la_empresa"][""];
		  $ls_numche = $io_update->uf_select_cheques($ls_codban,$ls_ctaban,$ls_codusu,$ls_chenum);
		  if (!empty($ls_numche))
		     {
			   $ls_numche = str_pad($ls_numche,15,0,0);
			 }		
		  else
		     {
			   $io_msg->message("No tiene Chequera asociada !!!");
			 }		  
		  ?>
		  <script language="javascript">uf_gennumche('<?php print $ls_numche; ?>',<?php print $ls_chenum; ?>);</script>
		  <?php
		}
   }
?>

</div>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function aceptar(codban,nomban,ctaban,dencta,ctascg,denctascg,fecapertura,feccierre,status,codtipcta,nomtipcta,saldo)
  {
    opener.document.form1.txtcuenta.value=ctaban;
    opener.document.form1.txtdenominacion.value=dencta;
	opener.document.form1.txttipocuenta.value=codtipcta;
	opener.document.form1.txtdentipocuenta.value=nomtipcta;
	opener.document.form1.txtcuenta_scg.value=ctascg;
	opener.document.form1.txtdisponible.value=uf_convertir(saldo);
    f=document.form1;	   
	f.operacion.value="TOMAR";
	f.txtcuenta.value=ctaban;
	f.txtcodban.value=codban;
	f.action="sigesp_cat_ctabanco2.php";
	f.submit();	   
  }
  
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_ctabanco.php";
  f.submit();
  }

function uf_gennumche(as_numche,as_chequera)
{
  fop = opener.document.form1;
  f   = document.form1;
  if (as_numche!='')
     {
	   fop.txtdocumento.value = as_numche;
	   fop.txtchequera.value  = as_chequera;
	   f.txtlectura.value     = "readonly";
	 }
  else
     {
	   fop.txtdocumento.value = "";
	 }
  close();
}
</script>
</html>