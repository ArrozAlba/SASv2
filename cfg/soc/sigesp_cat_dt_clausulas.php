<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Agregrar Clausulas</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../../shared/css/tablas.css"  rel="stylesheet" type="text/css">
<link href="../../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
</head>

<body>
<br>
<?Php
require_once("../../shared/class_folder/sigesp_include.php");
$io_in=new sigesp_include();
$con=$io_in->uf_conectar();
require_once("../../shared/class_folder/class_sql.php");
$io_sql=new class_sql($con);
require_once("../../shared/class_folder/grid_param.php");
$in_grid=new grid_param();
require_once("../../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
require_once("class_folder/sigesp_soc_c_modcla.php");
$io_cfg=new sigesp_soc_c_modcla($con);



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
      <td height="13" colspan="3" class="titulo-ventana"><span> </span></td>
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
	$ls_ventanas="sigesp_cat_dt_clausulas.php";

	$la_seguridad["empresa"]=$la_codemp;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;


	$ls_titletable="Catálogo de Clausulas";
	$li_widthtable=520;
	$ls_nametable="grid";
	$lo_title[1]="Agregar";
	$lo_title[2]="Código";
	$lo_title[3]="Denominación";
	$grid1="grid";	
	$li_totrows="";
	$lo_object="";
	$ls_codemp=$la_emp["codemp"];
	
	switch ($ls_operacion) 
	{
		case "":
			$lb_valido=$io_cfg->uf_saf_load_clausulas($ls_codemp,$li_totrows,$lo_object);		
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
      <td width="242" height="22" colspan="2" align="right"><div align="right"> <a href="javascript: ue_cancelar();"><img src="../../shared/imagebank/tools15/eliminar.gif" alt="Cancelar" width="15" height="15" border="0"></a><a href="javascript: ue_cancelar();">Cerrar</a> </div></td>
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

	li_gridtotrows=eval("opener.document.form1.totrows.value"); 

	ls_codcla=eval("f.txtcodcla"+li_row+".value"); 
	ls_dencla=eval("f.txtdencla"+li_row+".value");
	for(li_j=1; (li_j<=li_gridtotrows)&& lb_valido; li_j++)
	{ 
		ls_codclagrid=eval("opener.document.form1.txtcodcla"+li_j+".value");
		ls_denclagrid=eval("opener.document.form1.txtdencla"+li_j+".value"); 
		if((ls_codcla==ls_codclagrid)&&(ls_dencla==ls_denclagrid))
		{
			alert("La Clausula : "+" "+ls_denclagrid+" "+ "ya fue incluida !!!");
			lb_valido=false;
			
		}
	}
	if (lb_valido)
	{
		ls_codcla=eval("f.txtcodcla"+li_row+".value");
		ls_dencla=eval("f.txtdencla"+li_row+".value");
		obj=eval("opener.document.form1.txtcodcla"+li_gridtotrows+"");
		obj.value=ls_codcla;
		obj=eval("opener.document.form1.txtdencla"+li_gridtotrows+"");
		obj.value=ls_dencla;

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
