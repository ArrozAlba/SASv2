<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "close();";
	 print "opener.document.form1.submit();";
	 print "</script>";		
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Colocaciones</title>
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
</head>

<body>
<form name="form1" method="post" action="">
<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_funciones.php");

$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_msg     = new class_mensajes();
$io_sql     = new class_sql($ls_conect);
$io_funcion = new class_funciones();
$ls_codemp  = $_SESSION["la_empresa"]["codemp"];

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion = $_POST["operacion"];
	 $ls_codban    = $_POST["codigo"];
	 $ls_denban	   = $_POST["denban"];
	 $ls_ctaban	   = $_POST["cuenta"];
	 $ls_denctaban = $_POST["denominacion"];
   }
else
   {
	 $ls_operacion = "";
     $ls_denctaban = "";
	 $ls_codban    = $_GET["codigo"];
	 $ls_denban    = $_GET["denban"];
   }
?>
<br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr class="titulo-celda">
        <td height="22" colspan="2"><input name="operacion" type="hidden" id="operacion">
        Cat&aacute;logo de Colocaciones</td>
       </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="73" height="22"><div align="right">Cuenta</div></td>
        <td width="425" height="22"><div align="left">
          <input name="cuenta" type="text" id="cuenta">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Nombre</div></td>
        <td height="22"><div align="left">
          <input name="denominacion" type="text" id="denominacion" value="<?php print $ls_denctaban ?>" size="60" maxlength="254">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Banco</div></td>
        <td height="22"><input name="denban" type="text" id="denban" value="<?php print $ls_denban;?>">
        <input name="codigo" type="hidden" id="codigo" value="<?php print $ls_codban;?>"></td>
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
print "<td>Código </td>";
print "<td>Denominación</td>";
print "<td>Banco</td>";
print "<td>Tipo de Colocación</td>";
print "<td>Cuenta</td>";
print "<td>Denominación Cuenta</td>";
print "<td>Apertura</td>";
print "<td>Culminación</td>";
print "<td>Tasa Interes</td>";
print "<td>Dias</td>";
print "</tr>";


if ($ls_operacion=="BUSCAR")
   {
     $ls_sql = "SELECT scb_banco.codban,scb_colocacion.numcol,scb_colocacion.dencol,scb_colocacion.codtipcol,scb_colocacion.feccol,
	                   scb_colocacion.fecvencol,scb_colocacion.tascol,scb_colocacion.sc_cuenta,scb_colocacion.monto,
					   scb_colocacion.diacol,scb_banco.codemp,scb_banco.nomban,scb_ctabanco.dencta,scb_ctabanco.ctaban
			      FROM scb_colocacion ,scb_banco, scb_ctabanco
		         WHERE scb_colocacion.codban like '%".$ls_codban."%'
				   AND scb_colocacion.ctaban like '%".$ls_ctaban."%'
				   AND scb_ctabanco.dencta like '%".$ls_denctaban."%'
				   AND scb_colocacion.codemp = scb_banco.codemp 
				   AND scb_colocacion.codemp = scb_ctabanco.codemp 
				   AND scb_colocacion.codban = scb_banco.codban 
				   AND scb_colocacion.ctaban = scb_ctabanco.ctaban 
				   AND scb_colocacion.codban = scb_ctabanco.codban 
				   AND scb_colocacion.ctaban = scb_ctabanco.ctaban";//print $ls_sql;
   	$rs_data = $io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
	     $io_msg->message("No se han creado Comprobantes de Retención !!!");
	   }
	else
	   {
	     $li_numrows = $io_sql->num_rows($rs_data);
		 if ($li_numrows>0)
		    {
			  while($row=$io_sql->fetch_row($rs_data))
			       {
					 print "<tr class=celdas-blancas>";
					 $ls_codban    = trim($row["codban"]);
					 $ls_nomban    = $row["nomban"];
					 $ls_ctaban    = trim($row["ctaban"]);
					 $ls_dencta    = $row["dencta"];
					 $ls_numcol    = trim($row["numcol"]);
					 $ls_dencol    = $row["dencol"];
					 $ls_codtipcol = trim($row["codtipcol"]);
					 $ld_feccol    = $io_funcion->uf_formatovalidofecha($row["feccol"]);
					 $ld_feccol    = $io_funcion->uf_convertirfecmostrar($ld_feccol);
					 $ld_fecvencol = $io_funcion->uf_formatovalidofecha($row["fecvencol"]);
					 $ld_fecvencol = $io_funcion->uf_convertirfecmostrar($ld_fecvencol);
					 $ld_monto	   = number_format($row["monto"],2,',','.');
					 $ld_tascol	   = trim($row["tascol"]);
					 $ls_scgcta	   = trim($row["sc_cuenta"]);
					 $li_diacol	   = trim($row["diacol"]);
					 print "<td><a href=\"javascript: aceptar('$ls_numcol','$ls_dencol','$ls_ctaban','$ls_dencta','$ls_scgcta','$ld_tascol','$ld_monto');\">".$ls_numcol."</a></td>";
					 print "<td>".$ls_dencol."</td>";						
					 print "<td>".$ls_nomban."</td>";
					 print "<td>".$ls_codtipcol."</td>";			
					 print "<td>".$ls_ctaban."</td>";
					 print "<td>".$ls_dencta."</td>";						
					 print "<td>".$ld_feccol."</td>";
					 print "<td>".$ld_fecvencol."</td>";																			
					 print "<td>".$ld_tascol."</td>";
					 print "<td>".$li_diacol."</td>";					
					 print "</tr>";			
				   }
			}
		 else
		    {
			  $io_msg->message("No se han creado Colocaciones Para este Banco !!!");
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
fop = opener.document.form1;
function aceptar(ls_numcol,ls_dencol,ls_ctaban,ls_dencta,ls_scgcta,ld_tascol,ld_monto)
{
  fop.txtcolocacion.value   = ls_numcol;
  fop.txtdencol.value		= ls_dencol;
  fop.txtcuenta.value		= ls_ctaban;
  fop.txtdenominacion.value = ls_dencta;
  fop.txtcuenta_scg.value	= ls_scgcta;
  fop.txttasa.value			= ld_tascol;
  fop.txtmonto.value		= ld_monto;
  close();
}

  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_colocaciones.php";
  f.submit();
  }
</script>
</html>