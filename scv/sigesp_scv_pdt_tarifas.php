<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Detalle de Lote de Activo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css"  rel="stylesheet" type="text/css">
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
	color: #006699;
}
-->
</style>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
</head>

<body>
<br>
<?Php
require_once("../shared/class_folder/sigesp_include.php");
$io_in=new sigesp_include();
$con=$io_in->uf_conectar();
require_once("../shared/class_folder/class_sql.php");
$io_sql=new class_sql($con);
require_once("../shared/class_folder/grid_param.php");
$in_grid=new grid_param();
require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
require_once("sigesp_saf_c_movimiento.php");
$io_saf=new sigesp_saf_c_movimiento();


$la_emp=$_SESSION["la_empresa"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
}
else
{
	$ls_operacion="";	
}


	if (array_key_exists("totrow",$_GET))
	{
		$li_gridtotrows=$_GET["totrow"];
	}
	else
	{
		$li_gridtotrows="";
	}
?>
<form name="form1" method="post" action="">
  <table width="576" height="92" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td height="13" colspan="3" class="titulo-ventana"><span>Detalle de Lote de Activo </span></td>
    </tr>
    <tr class="formato-blanco">
      <td height="22" colspan="3">
        <div align="left">        </div></td>
    </tr>
    <tr class="formato-blanco">
      <td height="22" colspan="3" align="center">
<table width="575" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="489">
<?php
	$arr=array_keys($_SESSION);	
	$arre=$_SESSION["la_empresa"];
	$la_codemp=$arre["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SAF";
	$ls_ventanas="sigesp_saf_d_incorporacioneslote.php";

	$la_seguridad["empresa"]=$la_codemp;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;


	$ls_titletable="Catálogo de Activos";
	$li_widthtable=520;
	$ls_nametable="grid";
	$lo_title[1]="Agregar";
	$lo_title[2]="Código";
	$lo_title[3]="Serial";
	$lo_title[4]="Denominación";
	$lo_title[5]="Id Activo"; 
	$lo_title[6]="Monto"; 
	$grid1="grid";	
	$li_totrows="";
	$lo_object="";
	$ls_codemp=$la_emp["codemp"];
	
	switch ($ls_operacion) 
	{
		case "":
			$lb_valido=$io_saf->uf_saf_load_activos($ls_codemp,$li_totrows,$lo_object);		
			if (!$lb_valido)
			{
				$io_msg->message("No hay registros");
			}
		break;
	}// fin switch

	$in_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
?></td>
  </tr>
</table>
        <div align="center"></div></td>
    </tr>
    <tr class="formato-blanco">
      <td width="332" height="28"><div align="right">
          <input name="totalfilasgrid" type="hidden" id="totalfilasgrid" value="<?php print $li_gridtotrows;?>">
          <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
          <input name="operacion" type="hidden" id="operacion">
      </div></td>
      <td width="242" height="22" colspan="2" align="right"><div align="right"> <a href="javascript: ue_cancelar();"><img src="../shared/imagebank/tools15/eliminar.gif" alt="Cancelar" width="15" height="15" border="0"></a><a href="javascript: ue_cancelar();">Cerrar</a> </div></td>
    </tr>
  </table>
  <p align="center">&nbsp;</p>
  <p align="center">    <span class="Estilo1"></span>  </p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

function ue_agregar(li_row)
{
	f=document.form1;
	lb_valido=true;
	li_totrows=f.totalfilas.value;
	li_gridtotrows=eval("opener.document.form1.totalfilas.value");

	ls_codact=eval("f.txtcodact"+li_row+".value");
	ls_ideact=eval("f.txtideact"+li_row+".value");
	for(li_j=1; (li_j<=li_gridtotrows)&& lb_valido; li_j++)
	{
		ls_codactgrid=eval("opener.document.form1.txtcodact"+li_j+".value");
		ls_ideactgrid=eval("opener.document.form1.txtidact"+li_j+".value");
		if((ls_codactgrid==ls_codact)&&(ls_ideactgrid==ls_ideact))
		{
			alert("El activo ya esta en el movimiento");
			lb_valido=false;
			
		}
	}
	if (lb_valido)
	{
//		li_gridtotrows=eval("opener.document.form1.totalfilas.value");
		ls_codact=eval("f.txtcodact"+li_row+".value");
		ls_denact=eval("f.txtdenact"+li_row+".value");
		ls_ideact=eval("f.txtideact"+li_row+".value");
		li_monact=eval("f.txtmonact"+li_row+".value");
		obj=eval("opener.document.form1.txtcodact"+li_gridtotrows+"");
		obj.value=ls_codact;
		obj=eval("opener.document.form1.txtdenact"+li_gridtotrows+"");
		obj.value=ls_denact;
		obj=eval("opener.document.form1.txtidact"+li_gridtotrows+"");
		obj.value=ls_ideact;
		obj=eval("opener.document.form1.txtmonact"+li_gridtotrows+"");
		obj.value=li_monact;
		opener.document.form1.operacion.value="AGREGARDETALLE";
		opener.document.form1.submit();
//		close();
	}
}
	
	function ue_cancelar()
	{
		window.close();
	}

</script>
</html>
