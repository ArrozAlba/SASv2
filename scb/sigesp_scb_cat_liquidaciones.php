<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Cr&eacute;ditos por Liquidar</title>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/general.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css" />
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
<p>
<?php
require_once("../shared/class_folder/grid_param.php");
require_once("class_folder/sigesp_scb_c_liquidacion_creditos.php");
$io_scb  = new sigesp_scb_c_liquidacion_creditos("../");
$io_grid = new grid_param();
$li_totrows = 0;
$ls_rutfil = $_GET["rutfil"];

$la_rowgri[1]  = "Cédula";
$la_rowgri[2]  = "Nombre Beneficiario";
$la_rowgri[3]  = "Concepto";
$la_rowgri[4]  = "Monto";
$la_rowgri[5]  = "Fecha";
$la_rowgri[6]  = "Operación";
$la_object     = $io_scb->uf_load_liquidaciones($ls_rutfil,$li_totrows);
?>
</p>
<form id="sigesp_scb_cat_solicitudes_desembolso.php" name="form1" method="post" action="">
  <table width="364" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="sin-borde">
      <td height="11" colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td width="43">&nbsp;</td>
      <td width="60">&nbsp;</td>
      <td width="64">&nbsp;</td>
      <td width="64">&nbsp;</td>
      <td width="64">&nbsp;</td>
      <td width="67">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="6"><div align="center">
			<?php $io_grid->make_gridScroll($li_totrows,$la_rowgri,$la_object,648,'Créditos por Liquidar',"grid_liquidacion",100); ?>
      </div></td>	
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>
</body>
<script language="javascript">
f   = document.form1;
fop = opener.document.form1;
function uf_aceptar(as_codban,as_nomban,as_ctaban,as_denctaban,as_fecmov,ad_monmov,as_conmov,as_cedben,as_nomben,as_nombenalt,as_codope,as_docnum,as_filnam,as_estsep){
  lb_valido = true;
  if (as_codope=="ND")
     {
	   if (as_codban=="" || as_ctaban=="")
	      {
		    lb_valido = false;
			alert("Tipo de Operación ND=Nota de Débito, el xml debe proporcionar Banco y Cuenta Bancaria !!!");
		  }
	 }
  if (lb_valido)
     {
	   if (as_estsep=='C')
	      {
		    fop.txtcodban.value = as_codban;
		    fop.txtnomban.value = as_nomban;  
		    fop.txtctaban.value = as_ctaban;
		    fop.txtdenctaban.value = as_denctaban;
		    fop.txtfecmov.value = as_fecmov;
		    fop.txtmonmov.value = ad_monmov;  
		    fop.txtcedben.value = as_cedben;
		    fop.txtnomben.value = as_nomben; 
		    fop.hidnombenalt.value = as_nombenalt; 
		    fop.hiddocnum.value = as_docnum;
		    fop.hidfilnam.value = as_filnam;
		    fop.hidcodope.value = as_codope;
		    if (as_codope!='CH')
			   { 
				 fop.txtchevau.disabled = true;
			   }
		    fop.txtconmov.value = as_conmov;
		    fop.operacion.value = "CARGAR_DT";
		    fop.submit();
		    close();
		  }
	   else
	      {
		    alert("La SEP Nro "+as_docnum+", asociada al Crédito debe estar Contabilizada !!!");
		  }
	 }
}
</script>
</html>