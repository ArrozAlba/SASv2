<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cr&eacute;ditos</title>
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey))
		{
			window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ return false;} 
		} 
	}
</script>
</head>

<body>
<br>
<?php
require_once("../shared/class_folder/sigesp_include.php");
$io_in=new sigesp_include();
$con=$io_in->uf_conectar();

require_once("../shared/class_folder/class_sql.php");
$io_sql=new class_sql($con);

require_once("../shared/class_folder/grid_param.php");
$in_grid=new grid_param();

require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();

require_once("sigesp_siv_c_cargos.php");
$io_siv=new sigesp_siv_c_cargos();


$la_emp=$_SESSION["la_empresa"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codart=$_POST["txtcodart"];
	$ls_denart=$_POST["txtdenart"];
}
else
{
	$ls_operacion="";	
}


if (array_key_exists("codart",$_GET))
{
	$ls_codart=$_GET["codart"];
	$ls_denart=$_GET["denart"];
}
?>
<form name="form1" method="post" action="">
  <table width="576" height="92" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td height="13" colspan="3" class="titulo-ventana">Registro de Cr&eacute;ditos</td>
    </tr>
    <tr class="formato-blanco">
      <td height="22" colspan="3">
        <div align="left">
          <input name="txtdenart" type="text" class="sin-borde2" id="txtdenart" value="<?php print $ls_denart?>" size="65" readonly="true">
          <input name="txtcodart" type="hidden" id="txtcodart" value="<?php print $ls_codart ?>">
          <input name="txtunidad" type="hidden" id="txtunidad">
          <input name="txtobsunimed" type="hidden" id="txtobsunimed">
          <input name="hidstatus" type="hidden" id="hidstatus">
      </div></td>
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
	$ls_sistema="SIV";
	$ls_ventanas="sigesp_siv_d_articulo.php";

	$la_seguridad["empresa"]=$la_codemp;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;


	$ls_titletable="Catálogo de Cr&eacute;ditos";
	$li_widthtable=520;
	$ls_nametable="grid";
	$lo_title[1]="Check";
	$lo_title[2]="C&oacute;digo";
	$lo_title[3]="Denominaci&oacute;n";
	$lo_title[4]="Porcentaje"; 
	$grid1="grid";	
	$li_totrows="";
	$lo_object="";
	$ls_codemp=$la_emp["codemp"];
	
	switch ($ls_operacion) 
	{
		case "":
			$lb_valido=$io_siv->uf_siv_select_cargos($ls_codemp,$ls_codart,$li_totrows,$lo_object);		
			if (!$lb_valido)
			{
				$io_msg->message("No hay registros");
			}
		break;
		case "GUARDAR":
			$lb_valido=true;
			$li_totrows= $_POST["totalfilas"];
			$li_temp=0;
			for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				$ls_codcar= $_POST["txtcodcar".$li_i];
				if(array_key_exists("chkagregar".$li_i,$_POST))
				{
					$li_check= $_POST["chkagregar".$li_i];
					if ($li_check==1)
					{
						$lb_encontrado=$io_siv->uf_siv_select_cargosxarticulo($ls_codemp,$ls_codart,$ls_codcar);
						if(!$lb_encontrado)
						{
							$lb_valido=$io_siv->uf_siv_insert_cargosxarticulo($ls_codemp,$ls_codart,$ls_codcar,$la_seguridad);
						}
					}// fin  if($li_check==1)
				}// fin  if(array_key_exists("chkreversar".$li_i,$_POST))
				else
				{
					$lb_encontrado=$io_siv->uf_siv_select_cargosxarticulo($ls_codemp,$ls_codart,$ls_codcar);
					if($lb_encontrado)
					{
						$lb_valido=$io_siv->uf_siv_delete_cargosxarticulo($ls_codemp,$ls_codart,$ls_codcar,$la_seguridad);

					}
				}
			}// fin  for($li_i=1;$li_i<=$li_totrows;$li_i++)
			if($lb_valido)
			{
				$io_msg->message("Los cargos fueron actualizados.");
				print "<script language=JavaScript>";			
				print "close();";			
				print "</script>";			
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
          <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
          <input name="operacion" type="hidden" id="operacion">
      </div></td>
      <td width="242" height="22" colspan="2"><div align="right"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Guardar" width="20" height="20" border="0"></a><a href="javascript: ue_guardar();">Guardar</a><a href="javascript: ue_cancelar();"><img src="../shared/imagebank/eliminar.gif" alt="Cancelar" width="15" height="15" border="0"></a><a href="javascript: ue_cancelar();">Cancelar</a> </div></td>
    </tr>
  </table>
  <p align="center">&nbsp;</p>
  <p align="center">    <span class="Estilo1"></span>
    <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
  </p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

	function ue_guardar()
	{
		f=document.form1;
		f.operacion.value="GUARDAR"
		f.action="sigesp_siv_d_cargos.php";
		f.submit();
	}
	
	function ue_cancelar()
	{
		window.close();
	}

</script>
</html>
