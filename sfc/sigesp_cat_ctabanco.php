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
$in		   = new sigesp_include();
$con	   = $in->uf_conectar();
$io_msg    = new class_mensajes();
$io_sql    = new class_sql($con);
$fun       = new class_funciones();
$ls_codemp = $arr["codemp"];

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

if (array_key_exists("codban",$_REQUEST)){
	$ls_codigo = $_REQUEST["codban"];
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
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	 <div align="center"><br>
         <?php
print "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>C&oacute;digo </td>";
print "<td>Denominaci&oacute;n</td>";
print "<td>Banco</td>";
print "<td>Tipo de Cuenta</td>";
print "<td>Cuenta Contable</td>";
print "<td>Denominaci&oacute;n Cta. Contable</td>";
print "<td>Apertura</td>";
print "</tr>";

if($ls_operacion=="BUSCAR")
{
	$ls_sql="SELECT a.ctaban as ctaban,a.dencta as dencta,a.sc_cuenta as sc_cuenta,d.denominacion as denominacion,a.codban as codban,c.nomban as nomban,a.codtipcta as codtipcta,b.nomtipcta as nomtipcta,a.fecapr as fecapr,a.feccie as feccie,a.estact as estact ".
			" FROM scb_ctabanco a,scb_tipocuenta b,scb_banco c,scg_cuentas d ".
			" WHERE a.codemp='".$ls_codemp."' AND a.codban like '%".$ls_codigo."%'  AND a.ctaban like '".$ls_ctaban."' AND a.dencta like '".$ls_denominacion."'".
			"   AND a.codtipcta=b.codtipcta AND a.codban=c.codban AND (a.sc_cuenta=d.sc_cuenta AND a.codemp=d.codemp)";

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
					  $ls_status  = $row["estact"];
					  print "<td><a href=\"javascript: aceptar('$ls_ctaban','$ls_dencta','$ls_ctascg');\">".$ls_ctaban."</a></td>";
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
?>
       </div>
     </div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function aceptar(ctaban,dencta,ctascg)
  {
    opener.document.form1.txtctaban.value=ctaban;
    opener.document.form1.txtdescta.value=dencta;
	opener.document.form1.txtctascg.value=ctascg;
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