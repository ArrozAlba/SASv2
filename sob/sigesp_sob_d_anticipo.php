<?php
	session_start();
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
	$ls_logusr=$_SESSION["la_logusr"];
	require_once("class_folder/class_funciones_sob.php");
	$io_fun_sob=new class_funciones_sob();
	$io_fun_sob->uf_load_seguridad("SOB","sigesp_sob_d_anticipo.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Elaboraci&oacute;n de Anticipo</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
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
<style type="text/css">
<!--
.style6 {color: #000000}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
-->
</style><meta http-equiv="Content-Type" content="text/html; charset="></head>
<body link="#006699" vlink="#006699" alink="#006699">
<?Php
require_once("../shared/class_folder/class_sql.php");
require_once("class_folder/sigesp_sob_c_contrato.php");
$io_contrato=new sigesp_sob_c_contrato();
require_once("../shared/class_folder/grid_param.php");
$io_grid=new grid_param();
require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
require_once("../shared/class_folder/class_datastore.php");
$io_datastore=new class_datastore();
require_once("../shared/class_folder/class_funciones.php");
$io_function=new class_funciones();
require_once("../shared/class_folder/class_datastore.php");
$io_datastore=new class_datastore();
require_once ("class_folder/sigesp_sob_c_funciones_sob.php");
$io_funsob= new sigesp_sob_c_funciones_sob();
require_once("class_folder/sigesp_sob_class_obra.php");
$io_obra=new sigesp_sob_class_obra(); 
require_once("class_folder/sigesp_sob_c_anticipo.php");
$io_anticipo=new sigesp_sob_c_anticipo(); 
require_once("class_folder/sigesp_sob_c_contrato.php");
$io_contrato=new sigesp_sob_c_contrato(); 
require_once("../shared/class_folder/evaluate_formula.php");
$io_formula=new evaluate_formula();
require_once("class_folder/sigesp_sob_class_mensajes.php");
$io_mensaje=new sigesp_sob_class_mensajes();
$la_empresa=$_SESSION["la_empresa"];
$ls_codemp=$la_empresa["codemp"];

$ls_tituloretenciones="Retenciones Asignadas";
$li_anchoretenciones=600;
$ls_nametable="grid";
$la_columretenciones[1]="Código";
$la_columretenciones[2]="Descripción";
$la_columretenciones[3]="Cuenta";
$la_columretenciones[4]="Deducible";
$la_columretenciones[5]="Monto";
$la_columretenciones[6]="Total";
$la_columretenciones[7]="Edición";

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$li_filasretenciones=$_POST["hidfilasretenciones"];
	if ($ls_operacion != "ue_cargarretenciones" && $ls_operacion != "ue_removerretenciones")
	{
		for($li_i=1;$li_i<$li_filasretenciones;$li_i++)
		{
			$ls_codigo=$_POST["txtcodret".$li_i];
			$ls_descripcion=$_POST["txtdesret".$li_i];
			$ls_cuenta=$_POST["txtcueret".$li_i];
			$ls_deduccion=$_POST["txtdedret".$li_i];
			$ls_monret=$_POST["txtmonret".$li_i];
			$ls_totret=$_POST["txttotret".$li_i];
			$ls_formula=$_POST["formula".$li_i];
			$la_objectretenciones[$li_i][1]="<input name=txtcodret".$li_i." type=text id=txtcodret".$li_i." class=sin-borde value='".$ls_codigo."' style= text-align:center size=5 readonly>";
			$la_objectretenciones[$li_i][2]="<input name=txtdesret".$li_i." type=text id=txtdesret".$li_i." class=sin-borde value='".$ls_descripcion."' style= text-align:left size=30 readonly>";
			$la_objectretenciones[$li_i][3]="<input name=txtcueret".$li_i." type=text id=txtcueret".$li_i." class=sin-borde value='".$ls_cuenta."' style= text-align:center size=20 readonly>";
			$la_objectretenciones[$li_i][4]="<input name=txtdedret".$li_i." type=text id=txtdedret".$li_i." class=sin-borde value='".$ls_deduccion."' style= text-align:center size=15 readonly><input name=formula".$li_i." type=hidden id=formula".$li_i." value='".$ls_formula."'>";
			$la_objectretenciones[$li_i][5]="<input name=txtmonret".$li_i." type=text id=txtmonret".$li_i." class=sin-borde value='".$ls_monret."' style= text-align:center size=15 onKeyPress=return(validaCajas(this,'d',event,21))  onBlur=javascript:ue_validamonto(this)  onChange=ue_calcretencion(this)>";
			$la_objectretenciones[$li_i][6]="<input name=txttotret".$li_i." type=text id=txttotret".$li_i." class=sin-borde value='".$ls_totret."' style= text-align:center size=15 readonly>";
		    $la_objectretenciones[$li_i][7]="<div align=center><a href=javascript:ue_removerretenciones(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a></div>";
		}	
		$la_objectretenciones[$li_filasretenciones][1]="<input name=txtcodret".$li_filasretenciones." type=text id=txtcodret".$li_filasretenciones." class=sin-borde style= text-align:center size=5 readonly>";
		$la_objectretenciones[$li_filasretenciones][2]="<input name=txtdesret".$li_filasretenciones." type=text id=txtdesret".$li_filasretenciones." class=sin-borde style= text-align:left size=30 readonly>";
		$la_objectretenciones[$li_filasretenciones][3]="<input name=txtcueret".$li_filasretenciones." type=text id=txtcueret".$li_filasretenciones." class=sin-borde  style= text-align:center size=20 readonly><input name=formula".$li_filasretenciones." type=hidden id=formula".$li_filasretenciones.">";
		$la_objectretenciones[$li_filasretenciones][4]="<input name=txtdedret".$li_filasretenciones." type=text id=txtdedret".$li_filasretenciones." class=sin-borde  style= text-align:center size=15 readonly>";
		$la_objectretenciones[$li_filasretenciones][5]="<input name=txtmonret".$li_filasretenciones." type=text id=txtmonret".$li_filasretenciones." class=sin-borde style= text-align:left size=15 readonly>";
		$la_objectretenciones[$li_filasretenciones][6]="<input name=txttotret".$li_filasretenciones." type=text id=txttotret".$li_filasretenciones." class=sin-borde style= text-align:left size=15 readonly>";
		$la_objectretenciones[$li_filasretenciones][7]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	}		
}
else
{
	$ls_datoscontrato="OCULTAR";
	$ls_datosobra="OCULTAR";
	$ls_codobr="";
	$ls_desobr="";
	$ls_estobr="";
	$ls_munobr="";
	$ls_parobr="";
	$ls_comobr="";
	$ls_dirobr="";
	$ls_codcon="";
	$ls_nomtco ="";
	$ls_fecinicon ="";
	$li_placon="";
	$ls_moncon="";
	$ls_estcon="";
	$li_filasretenciones=1;
	$li_removerretenciones="";
	$ls_operacion="";
	$li_placon="";
	$ls_codtco="";
	$ls_totreten="0,00";
	$ls_codant="";
	$ls_fecant="";
	$ls_estant="";
	$ls_fecintant=""; 
	$ls_monto="0,00";
	$ls_porant="0,00";
	$ls_sc_cuenta="";
	$ls_placonuni="";
	$ls_conant="";
	$ld_montocontratofinal="";
	$ld_monvar="";
	$ls_nompro="";
	$ld_montocontrato=0;
	$ls_montotant="0,00";
	$ld_montototalanticipo=0;
	$li_filasretenciones=1;
	$la_objectretenciones[1][1]="<input name=txtcodret1 type=text id=txtcodret1 class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectretenciones[1][2]="<input name=txtdesret1 type=text id=txtdesret1 class=sin-borde style= text-align:left size=30 readonly>";
	$la_objectretenciones[1][3]="<input name=txtcueret1 type=text id=txtcueret1 class=sin-borde style= text-align:center size=20 readonly>";
	$la_objectretenciones[1][4]="<input name=txtdedret1 type=text id=txtdedret1 class=sin-borde style= text-align:center size=15 readonly><input name=formula1 type=hidden id=formula1>";
	$la_objectretenciones[1][5]="<input name=txtmonret1 type=text id=txtmonret1 class=sin-borde style= text-align:center size=15 readonly>";
	$la_objectretenciones[1][6]="<input name=txttotret1 type=text id=txttotret1 class=sin-borde style= text-align:center size=15 readonly>";
	$la_objectretenciones[1][7]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
}
/////////Instrucciones para evitar que las cajitas pierdan la informacion cada vez que se realiza un submit/////////////
if	(array_key_exists("hiddatoscontrato",$_POST)){	$ls_datoscontrato=$_POST["hiddatoscontrato"]; }
else{$ls_datoscontrato="OCULTAR";}

if	(array_key_exists("hiddatosobra",$_POST)){	$ls_datosobra=$_POST["hiddatosobra"]; }
else{$ls_datosobra="OCULTAR";}

if	(array_key_exists("operacion",$_POST)){	$ls_operacion=$_POST["operacion"]; }
else{$ls_operacion="";}

if	(array_key_exists("txtcodcon",$_POST)){	$ls_codcon=$_POST["txtcodcon"]; }
else{$ls_codcon="";}

if	(array_key_exists("txtcodobr",$_POST)){$ls_codobr=$_POST["txtcodobr"]; }
else{$ls_codobr="";}

if	(array_key_exists("hiddesobr",$_POST)){$ls_desobr=$_POST["hiddesobr"]; }
else{$ls_desobr="";}

if	(array_key_exists("txtestobr",$_POST)){$ls_estobr=$_POST["txtestobr"]; }
else{$ls_estobr="";}

if	(array_key_exists("txtmunobr",$_POST)){$ls_munobr=$_POST["txtmunobr"]; }
else{$ls_munobr="";}

if	(array_key_exists("txtparobr",$_POST)){$ls_parobr=$_POST["txtparobr"]; }
else{$ls_parobr="";}

if	(array_key_exists("txtcomobr",$_POST)){$ls_comobr=$_POST["txtcomobr"]; }
else{$ls_comobr="";}

if	(array_key_exists("txtdirobr",$_POST)){$ls_dirobr=$_POST["txtdirobr"]; }
else{$ls_dirobr="";}

if	(array_key_exists("txtnomtco",$_POST)){$ls_destipcon=$_POST["txtnomtco"]; }
else{$ls_destipcon="";}

if	(array_key_exists("txtfecinicon",$_POST)){$ls_fecinicon=$_POST["txtfecinicon"]; }
else{$ls_fecinicon="";}	

if	(array_key_exists("txtmoncon",$_POST)){$ld_montocontrato=$_POST["txtmoncon"]; }
else{$ls_montocontrato="";}	

if	(array_key_exists("txtestcon",$_POST)){$ls_estcon=$_POST["txtestcon"]; }
else{$ls_estcon="";}	

if	(array_key_exists("hidfilasretenciones",$_POST)){$li_filasretenciones=$_POST["hidfilasretenciones"]; }
else{$li_filasretenciones=1;}	

if	(array_key_exists("hidremoverretenciones",$_POST)){$li_removerretenciones=$_POST["hidremoverretenciones"]; }
else{$li_removerretenciones="";}

if	(array_key_exists("txtplacon",$_POST)){$li_placon=$_POST["txtplacon"]; }
else{$li_placon="0";}

if	(array_key_exists("txtcodant",$_POST)){$ls_codant=$_POST["txtcodant"]; }
else{$ls_codant="";}

if	(array_key_exists("txtfecant",$_POST)){$ls_fecant=$_POST["txtfecant"]; }
else{$ls_fecant="";}

if	(array_key_exists("txtfecintant",$_POST)){$ls_fecintant=$_POST["txtfecintant"]; }
else{$ls_fecintant="";}

if	(array_key_exists("txtmonto",$_POST)){$ls_monto=$_POST["txtmonto"]; }
else{$ls_monto="0,00";}

if	(array_key_exists("txtporant",$_POST)){$ls_porant=$_POST["txtporant"]; }
else{$ls_porant="0,00";}

if	(array_key_exists("txtmontotant",$_POST)){$ls_montotant=$_POST["txtmontotant"]; }
else{$ls_montotant="0,00";}

if	(array_key_exists("txtsc_cuenta",$_POST)){$ls_sc_cuenta=$_POST["txtsc_cuenta"]; }
else{$ls_sc_cuenta="";}

if	(array_key_exists("txtconant",$_POST)){$ls_conant=$_POST["txtconant"]; }
else{$ls_conant="";}

if	(array_key_exists("txtnompro",$_POST)){$ls_nompro=$_POST["txtnompro"]; }
else{$ls_nompro="";}

if	(array_key_exists("txtplaconuni",$_POST)){$ls_placonuni=$_POST["txtplaconuni"]; }
else{$ls_placonuni="";}

if	(array_key_exists("hidobra",$_POST)){$ls_obra=$_POST["hidobra"]; }
else{$ls_obra="";}

if	(array_key_exists("txtnomtco",$_POST)){$ls_nomtco=$_POST["txtnomtco"]; }
else{$ls_nomtco="";}

if	(array_key_exists("txtcodtco",$_POST)){$ls_codtco=$_POST["txtcodtco"]; }
else{$ls_codtco="";}

if	(array_key_exists("hidmontocontrato",$_POST)){$ld_montocontratofinal=$_POST["hidmontocontrato"]; }
else{$ld_montocontratofinal=0;}

if	(array_key_exists("hidmontototalanticipo",$_POST)){$ld_montototalanticipo=$_POST["hidmontototalanticipo"]; }
else{$ld_montototalanticipo=0;}

if	(array_key_exists("txtestant",$_POST)){	$ls_estant=$_POST["txtestant"]; }
else{$ls_estant="";}

if	(array_key_exists("txtvartot",$_POST)){	$ld_monvar=$_POST["txtvartot"]; }
else{$ld_monvar="";}

if	(array_key_exists("txttotreten",$_POST)){$ls_totreten=$_POST["txttotreten"]; }
else{$ls_totreten="0,00";}

if	(array_key_exists("txtcodtipdoc",$_POST)){$ls_codtipdoc=$_POST["txtcodtipdoc"]; }
else{$ls_codtipdoc="";}

if	(array_key_exists("txtdentipdoc",$_POST)){$ls_dentipdoc=$_POST["txtdentipdoc"]; }
else{$ls_dentipdoc="";}
if	(array_key_exists("hidstatus",$_POST)){$ls_hidstatus=$_POST["hidstatus"]; }
else{$ls_hidstatus="";}


////////////////////////////////Operaciones de Actualizacion//////////////////////////////////////

if($ls_operacion=="ue_cargarretenciones")
{	
	$li_filasretenciones=$li_filasretenciones+1;
	
	for($li_i=1;$li_i<$li_filasretenciones;$li_i++)
	{
		$ls_codigo=$_POST["txtcodret".$li_i];
		$ls_descripcion=$_POST["txtdesret".$li_i];
		$ls_cuenta=$_POST["txtcueret".$li_i];
		$ls_deduccion=$_POST["txtdedret".$li_i];
		$ls_monret=$_POST["txtmonret".$li_i];
		$ls_totret=$_POST["txttotret".$li_i];
		$ls_formula=$_POST["formula".$li_i];
		$la_objectretenciones[$li_i][1]="<input name=txtcodret".$li_i." type=text id=txtcodret".$li_i." class=sin-borde value='".$ls_codigo."' style= text-align:center size=5 readonly>";
		$la_objectretenciones[$li_i][2]="<input name=txtdesret".$li_i." type=text id=txtdesret".$li_i." class=sin-borde value='".$ls_descripcion."' style= text-align:left size=30 readonly>";
		$la_objectretenciones[$li_i][3]="<input name=txtcueret".$li_i." type=text id=txtcueret".$li_i." class=sin-borde value='".$ls_cuenta."' style= text-align:center size=20 readonly>";
		$la_objectretenciones[$li_i][4]="<input name=txtdedret".$li_i." type=text id=txtdedret".$li_i." class=sin-borde value='".$ls_deduccion."' style= text-align:center size=15 readonly><input name=formula".$li_i." type=hidden id=formula".$li_i." value='".$ls_formula."'>";
		$la_objectretenciones[$li_i][5]="<input name=txtmonret".$li_i." type=text id=txtmonret".$li_i." class=sin-borde value='".$ls_monret."' style= text-align:center size=15  onKeyPress=return(validaCajas(this,'d',event,21)) onBlur=ue_calcretencion(this)>";
		$la_objectretenciones[$li_i][6]="<input name=txttotret".$li_i." type=text id=txttotret".$li_i." class=sin-borde value='".$ls_totret."' style= text-align:center size=15 readonly>";
		$la_objectretenciones[$li_i][7]="<div align=center><a href=javascript:ue_removerretenciones(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a></div>";
	}	
	$la_objectretenciones[$li_filasretenciones][1]="<input name=txtcodret".$li_filasretenciones." type=text id=txtcodret".$li_filasretenciones." class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectretenciones[$li_filasretenciones][2]="<input name=txtdesret".$li_filasretenciones." type=text id=txtdesret".$li_filasretenciones." class=sin-borde style= text-align:left size=30 readonly>";
	$la_objectretenciones[$li_filasretenciones][3]="<input name=txtcueret".$li_filasretenciones." type=text id=txtcueret".$li_filasretenciones." class=sin-borde  style= text-align:center size=20 readonly><input name=formula".$li_filasretenciones." type=hidden id=formula".$li_filasretenciones.">";
    $la_objectretenciones[$li_filasretenciones][4]="<input name=txtdedret".$li_filasretenciones." type=text id=txtdedret".$li_filasretenciones." class=sin-borde  style= text-align:center size=15 readonly>";
	$la_objectretenciones[$li_filasretenciones][5]="<input name=txtmonret".$li_filasretenciones." type=text id=txtmonret".$li_filasretenciones." class=sin-borde style= text-align:left size=15 readonly>";
	$la_objectretenciones[$li_filasretenciones][6]="<input name=txttotret".$li_filasretenciones." type=text id=txttotret".$li_filasretenciones." class=sin-borde style= text-align:left size=15 readonly>";
	$la_objectretenciones[$li_filasretenciones][7]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	
}
elseif($ls_operacion=="ue_removerretenciones")
{
	$li_filasretenciones=$li_filasretenciones-1;
	$li_temp=0;

	for($li_i=1;$li_i<=$li_filasretenciones;$li_i++)
	{
		if($li_i!=$li_removerretenciones)
		{		
			$li_temp=$li_temp+1;
			$ls_codigo=$_POST["txtcodret".$li_i];
			$ls_descripcion=$_POST["txtdesret".$li_i];
			$ls_cuenta=$_POST["txtcueret".$li_i];
			$ls_monret=$_POST["txtmonret".$li_i];
			$ls_totret=$_POST["txttotret".$li_i];
			$ls_deduccion=$_POST["txtdedret".$li_i];
			$ls_formula=$_POST["formula".$li_i];
			$la_objectretenciones[$li_temp][1]="<input name=txtcodret".$li_temp." type=text id=txtcodret".$li_temp." class=sin-borde value='".$ls_codigo."' style= text-align:center size=5 readonly>";
			$la_objectretenciones[$li_temp][2]="<input name=txtdesret".$li_temp." type=text id=txtdesret".$li_temp." class=sin-borde value='".$ls_descripcion."' style= text-align:left size=30 readonly>";
			$la_objectretenciones[$li_temp][3]="<input name=txtcueret".$li_temp." type=text id=txtcueret".$li_temp." class=sin-borde value='".$ls_cuenta."' style= text-align:center size=20 readonly>";
			$la_objectretenciones[$li_temp][4]="<input name=txtdedret".$li_temp." type=text id=txtdedret".$li_temp." class=sin-borde value='".$ls_deduccion."' style= text-align:center size=15 readonly><input name=formula".$li_i." type=hidden id=formula".$li_i." value='".$ls_formula."'>";
			$la_objectretenciones[$li_temp][5]="<input name=txtmonret".$li_temp." type=text id=txtmonret".$li_temp." class=sin-borde value='".$ls_monret."' style= text-align:center size=15 onKeyPress=return(validaCajas(this,'d',event,21)) onBlur=ue_calcretencion(this)>";
			$la_objectretenciones[$li_temp][6]="<input name=txttotret".$li_temp." type=text id=txttotret".$li_temp." class=sin-borde value='".$ls_totret."' style= text-align:center size=15 readonly>";
			$la_objectretenciones[$li_temp][7]="<div align=center><a href=javascript:ue_removerretenciones(".$li_temp.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a></div>";
		}
	}
	$la_objectretenciones[$li_filasretenciones][1]="<input name=txtcodret".$li_filasretenciones." type=text id=txtcodret".$li_filasretenciones." class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectretenciones[$li_filasretenciones][2]="<input name=txtdesret".$li_filasretenciones." type=text id=txtdesret".$li_filasretenciones." class=sin-borde style= text-align:left size=30 readonly>";
	$la_objectretenciones[$li_filasretenciones][3]="<input name=txtcueret".$li_filasretenciones." type=text id=txtcueret".$li_filasretenciones." class=sin-borde  style= text-align:center size=20 readonly><input name=formula".$li_filasretenciones." type=hidden id=formula".$li_filasretenciones.">";
	$la_objectretenciones[$li_filasretenciones][4]="<input name=txtdedret".$li_filasretenciones." type=text id=txtdedret".$li_filasretenciones." class=sin-borde  style= text-align:center size=15 readonly>";
	$la_objectretenciones[$li_filasretenciones][5]="<input name=txtmonret".$li_filasretenciones." type=text id=txtmonret".$li_filasretenciones." class=sin-borde style= text-align:left size=15 >";
	$la_objectretenciones[$li_filasretenciones][6]="<input name=txttotret".$li_filasretenciones." type=text id=txttotret".$li_filasretenciones." class=sin-borde style= text-align:left size=15 readonly>";
	$la_objectretenciones[$li_filasretenciones][7]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	
	$ls_operacion="ue_calcretencion";
}
elseif($ls_operacion=="ue_cargarcontrato")
{
	require_once("class_folder/sigesp_sob_c_variacion.php");
	$io_variacion=new sigesp_sob_c_variacion();
	$la_aumento=$la_disminucion="";
	$lb_valido=$io_variacion->uf_select_variacion($ls_codcon,1,$la_aumento);
	$ld_totalaumento=0;
	$ld_totaldisminucion=0;
	if($lb_valido!=0)
	{
		$x = (count($la_aumento, COUNT_RECURSIVE) / count($la_aumento)) - 1;
		for($li_i=1;$li_i<=$x;$li_i++)
		{
			$ld_totalaumento=$ld_totalaumento+$la_aumento["monto"][$li_i];
		}
	}
	$lb_valido=$io_variacion->uf_select_variacion($ls_codcon,2,$la_disminucion);
	if($lb_valido!=0)
	{
		$x = (count($la_disminucion, COUNT_RECURSIVE) / count($la_disminucion)) - 1;
		for($li_i=1;$li_i<=$x;$li_i++)
		{
			$ld_totaldisminucion=$ld_totaldisminucion+$la_disminucion["monto"][$li_i];
		}
	}	
	$ld_totalvariacion=$ld_totalaumento-$ld_totaldisminucion;
	
	$lb_valido=$io_contrato-> uf_select_contrato($ls_codcon,$la_contrato);
	if ($lb_valido)
	{
		$io_datastore->data=$la_contrato;
		$li_i=1;
		$ls_codcon=$io_datastore->getValue("codcon",$li_i);
		$ls_moncon=$io_funsob->uf_convertir_numerocadena($io_datastore->getValue("monto",$li_i));
	}	
	
	$io_anticipo->uf_calcular_montoanticipo($ls_codcon,$ld_montototalanticipoaux);
	$ld_montototalanticipo=$io_funsob->uf_convertir_numerocadena($ld_montototalanticipoaux);
	$ld_montocontrato=$la_contrato["monto"][1];
	$ld_montocontratofinal=$ld_montocontrato+$ld_totalvariacion;	
	$ld_montocontrato=$io_funsob->uf_convertir_numerocadena($ld_montocontrato);	
	$ld_montocontratofinal=$io_funsob->uf_convertir_numerocadena($ld_montocontratofinal);
	$ld_monvar=$io_funsob->uf_convertir_numerocadena($ld_totalvariacion);
	$lb_valido=$io_obra->uf_select_obra($io_datastore->getValue("codobr",$li_i),$la_obra);	
	if($lb_valido)
		$ls_desobr=$la_obra["desobr"][1];
	$ls_operacion="ue_nuevo";		
}
elseif($ls_operacion=="ue_guardar")
{
	$ld_monto=$io_funsob->uf_convertir_cadenanumero($ls_monto);
	$ld_montotant=$io_funsob->uf_convertir_cadenanumero($ls_montotant);
	$ld_porant=$io_funsob->uf_convertir_cadenanumero($ls_porant);
	$lf_fecant=$io_function->uf_convertirdatetobd($ls_fecant);
	$lf_fecintant=$io_function->uf_convertirdatetobd($ls_fecintant);
	$lb_existe=$io_anticipo->uf_select_anticipo ($ls_codant,$ls_codcon,$la_data);
	$ls_hidstatus=$_POST["hidstatus"];
	$lb_valido=$io_anticipo->uf_validar_disponibilidad_anticipos($ls_codcon ,$ls_codant,$ld_monto);
	if($lb_valido)
	{
			if($ls_hidstatus!="C")
			{
				$io_anticipo->io_sql->begin_transaction();
				$ls_codantaux=$ls_codant;
					$lb_valido=$io_anticipo->uf_guardar_anticipo($ls_codcon ,$ls_codant ,$lf_fecant,$lf_fecintant,$ld_porant,$ld_monto,$ls_conant,$ld_montotant,$ls_sc_cuenta,$la_seguridad);
					if($lb_valido)
					{
						if($ls_codantaux!=$ls_codant)
						{$io_msg->message("Se le asigno el nuevo numero ".$ls_codant." ");}
						$io_anticipo->io_sql->commit();
						$io_msg->message("El Anticipo ha sido Registrado.");
					}
					else
					{
						$io_anticipo->io_sql->rollback();
						$io_msg->message("Se genero un error al registrar el Anticipo.");
					}
			}//end del if si no existe
			else
			{	
				if($lb_existe)
				{
					$lb_valido=$io_anticipo->uf_select_estado($ls_codcon,$ls_codant,$li_estado);
					$ls_estspgscg=$io_obra->uf_contabilizado("SELECT estspgscg FROM sob_anticipo WHERE codemp='$ls_codemp' AND codcon='$ls_codcon' AND codant='$ls_codant'");
					if($lb_valido)
					{
						if($li_estado==1 && $ls_estspgscg==0)
						{				
								$la_retenciones["codret"][1]="";
								$la_retenciones["monret"][1]="";
								$la_retenciones["montotret"][1]="";
								$ls_monto=$io_funsob->uf_convertir_cadenanumero($ls_monto);
								$ls_montotant=$io_funsob->uf_convertir_cadenanumero($ls_montotant);
								$ls_porant=$io_funsob->uf_convertir_cadenanumero($ls_porant);
								$ls_fecant=$io_function->uf_convertirdatetobd($ls_fecant);
								$ls_fecintant=$io_function->uf_convertirdatetobd($ls_fecintant);
								$io_anticipo->io_sql->begin_transaction();
								$lb_valido=$io_anticipo->uf_update_anticipo($ls_codcon, $ls_codant,$ls_fecant,$ls_fecintant,$ls_porant,$ls_monto,$ls_conant,$ls_montotant,
																			$ls_sc_cuenta,$la_seguridad);
								if($lb_valido)
								{
									$ls_codcon="";
									$ls_codant="";
									$ls_fecintant="";
									$ls_fecant="";
									$ls_monto="0,00";
									$ls_porant="0,00";
									$ls_totreten="0,00";
									$ls_sc_cuenta="";
									$ls_conant="";
									$ls_totreten="0,00";
									$ld_montocontratofinal="";
									$ld_monvar="";
									$ls_montotant="0,00";
									$ls_estant="";
									$ls_datoscontrato="OCULTAR";
								}
								if($lb_valido===false)
								{
									$io_mensaje->error_modificar();
									$io_msg->message("en retenciones");
									$io_anticipo->io_sql->rollback();
								}
								elseif($lb_valido===true)
								{
									$io_mensaje->modificar();
									$io_anticipo->io_sql->commit();
								}
								
						}
						else
						{
							if($ls_estspgscg==0)
							{
								$ls_estado=$io_funsob->uf_convertir_numeroestado($li_estado);
								$io_msg->message("El registro no puede ser modificado, su estado es $ls_estado");
							}
							else
							{
								$io_msg->message("El registro no puede ser modificado, ya esta Comprometido");
							}
						}
					}
				}
				else
				{
					$io_msg->message("El anticipo no existe");
				}
			}
		}
	
}
elseif($ls_operacion=="ue_cargaranticipo")
{
}
elseif($ls_operacion=="ue_eliminar")///Esto es una eliminacion lógica!
{
	$lb_existe=$io_anticipo->uf_select_anticipo($ls_codant,$ls_codcon,$la_data);	
	if ($lb_existe)
	{
		$lb_valido=$io_anticipo->uf_select_estado($ls_codcon,$ls_codant,$li_estado);
		$ls_estspgscg=$io_obra->uf_contabilizado("SELECT estspgscg FROM sob_anticipo WHERE codemp='$ls_codemp' AND codcon='$ls_codcon' AND codant='$ls_codant'");
		if($lb_valido)
		{
			if($li_estado==1 && $ls_estspgscg==0)
			{
				$io_anticipo->io_sql->begin_transaction();
				$lb_valido=$io_anticipo->uf_update_estado($ls_codcon,$ls_codant,3,$la_seguridad);
				if($lb_valido)
				{
					$io_mensaje->anular();
					$io_anticipo->io_sql->commit();
				}
				else
				{
					$io_msg->message("Ocurrio un error al anular el anticipo");
					$io_anticipo->io_sql->rollback();
				}
			}
			else
			{
				if($ls_estspgscg==0)
				{
					$ls_estado=$io_funsob->uf_convertir_numeroestado($li_estado);
					if ($ls_estado=="ANULADO")
						$io_msg->message("Este Anticipo ya está Anulado!!!");
					else
						$io_msg->message("Este Anticipo no puede ser Anulado, su estado es ".$ls_estado);
				}
				else
				{
					$io_msg->message("Este Anticipo no puede ser Anulado, ya esta Contabilizado");
				}
			}
		}
	}
	else
	{
		$io_msg->message("Debe seleccionar un Anticipo existente!!!");
	}
	$ls_codcon="";
	$ls_codant="";
	$ls_fecintant="";
	$ls_fecant="";
	$ls_monto="0,00";
	$ls_totreten="0,00";
	$ls_porant="0,00";
	$ls_sc_cuenta="";
	$ls_conant="";
	$ls_montotant="0,00";	
	$ld_montocontratofinal="";
	$ld_monvar="";
	$ls_estant="";
	$ls_datoscontrato="OCULTAR";
	$li_filasretenciones=1;
	$la_objectretenciones[$li_filasretenciones][1]="<input name=txtcodret".$li_filasretenciones." type=text id=txtcodret".$li_filasretenciones." class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectretenciones[$li_filasretenciones][2]="<input name=txtdesret".$li_filasretenciones." type=text id=txtdesret".$li_filasretenciones." class=sin-borde style= text-align:left size=30 readonly>";
	$la_objectretenciones[$li_filasretenciones][3]="<input name=txtcueret".$li_filasretenciones." type=text id=txtcueret".$li_filasretenciones." class=sin-borde  style= text-align:center size=20 readonly><input name=formula".$li_filasretenciones." type=hidden id=formula".$li_filasretenciones.">";
	$la_objectretenciones[$li_filasretenciones][4]="<input name=txtdedret".$li_filasretenciones." type=text id=txtdedret".$li_filasretenciones." class=sin-borde  style= text-align:center size=15 readonly>";
	$la_objectretenciones[$li_filasretenciones][5]="<input name=txtmonret".$li_filasretenciones." type=text id=txtmonret".$li_filasretenciones." class=sin-borde style= text-align:left size=15 >";
	$la_objectretenciones[$li_filasretenciones][6]="<input name=txttotret".$li_filasretenciones." type=text id=txttotret".$li_filasretenciones." class=sin-borde style= text-align:left size=15 readonly>";
	$la_objectretenciones[$li_filasretenciones][7]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
}
if($ls_operacion=="PROCESAR")
{
//	$lb_valido=$io_anticipo->uf_select_estado($ls_codcon,$ls_codant,$li_estado);
	$ls_estspgscg=$io_obra->uf_contabilizado("SELECT estspgscg FROM sob_anticipo WHERE codemp='$ls_codemp' AND codcon='$ls_codcon' AND codant='$ls_codant'");
	//if($ls_estspgscg==1)
	//{
		$ls_numrecdoc=trim($ls_codcon).trim($ls_codant);
		if	(array_key_exists("txtcodtipdoc",$_POST)){$ls_codtipdoc=$_POST["txtcodtipdoc"]; }
		else{$ls_codtipdoc="";}
		$ld_montotant=$io_funsob->uf_convertir_cadenanumero($ls_montotant);
		$ld_totreten=$io_funsob->uf_convertir_cadenanumero($ls_totreten);

		$lb_valido=$io_anticipo->uf_procesar_recepcion_documentos($ls_codemp,$ls_numrecdoc,$ls_codtipdoc,$ls_conant,$ls_fecant,$ld_montotant,$ld_totreten,
																  $ls_codcon,$ls_codant,$la_seguridad);
		if($lb_valido)
		{
			$io_msg->message("La Recepcion de Documentos se genero satisfactoriamente");
		}
		else
		{
			$io_msg->message("No se genero la Recepcion de Documentos");
		}
	//}
	//else
	//{
	//	$io_msg->message("El anticipo debe estar en estatus de contabilizado");
	//}

}
if($ls_operacion=="ue_nuevo")//Abre una ficha de obra nueva
{
	if($ld_montototalanticipo==$ld_montocontratofinal)
	{
		$io_msg->message("No pueden realizarse nuevo anticipos sobre este contrato, ya que el monto limite se ha alcanzado!!!");
	}
	else
	{
		require_once("../shared/class_folder/class_funciones_db.php");
		require_once ("../shared/class_folder/sigesp_include.php");		
		$io_include=new sigesp_include();
		$io_connect=$io_include->uf_conectar();
		$io_funcdb=new class_funciones_db($io_connect);
		$la_empresa=$_SESSION["la_empresa"];
		require_once("../shared/class_folder/sigesp_c_generar_consecutivo.php");
		$io_keygen= new sigesp_c_generar_consecutivo();
		$ls_codant= $io_keygen->uf_generar_numero_nuevo("SOB","sob_anticipo","codant","SOBANT",3,"","","");
//		$ls_codant=$io_anticipo->uf_generar_codigoanticipo($ls_codcon);
		$ls_fecant=date("d/m/Y");
		$ls_fecintant=""; 
		$ls_monto="0,00";
		$ls_porant="0,00";
		$ls_totreten="0,00";
		$lb_valido=$io_anticipo->uf_select_cuentacontable($ls_codcon,$ls_cuenta);
			if($lb_valido)
				$ls_sc_cuenta=$ls_cuenta;			
		$ls_conant="";
		$ls_montotant="0,00";
		$li_filasretenciones=1;
		$ls_estant="EMITIDO";
		$li_removerretenciones="";
		$ls_operacion="";	
	}
	
}

?>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">
		<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
			
              <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Obras </td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  	    <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
        </table>
	</td>
  </tr>
  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img name="imgnuevo" id="imgnuevo" src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a><!--img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20">--><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>
  <p>&nbsp;
  </p>
  <form name="form1" method="post" action="">
  <?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_sob->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_sob);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	

  <table width="828" border="0" align="center" cellpadding="0" cellspacing="0"  class="contorno">
      <tr class="formato-blanco">
        <td>&nbsp;</td>
        <td colspan="9" class="titulo-celdanew">Datos del Contrato </td>
        <td>&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td width="115">&nbsp;</td>
        <td colspan="2">&nbsp;</td>
        <td colspan="4">&nbsp;</td>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td width="18" height="22"><div align="right"></div></td>
        <td width="78"><div align="right">Contrato</div></td>
        <td colspan="3"><input name="txtcodcon" type="text" id="txtcodcon" style="text-align:center " value="<?php print $ls_codcon ?>" size="15" maxlength="12" readonly="true">
        <input name="operacion" type="hidden" id="operacion">
		 <input name="hidstatus" type="hidden" id="hidstatus" value="<?php print $ls_hidstatus; ?>">

        <a href="javascript:ue_catcontrato();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a> </td>
        <td width="81">&nbsp;</td>
        <td colspan="4"><div align="right"><a href="javascript:uf_mostrar_ocultar_contrato();">&nbsp;&nbsp;<img src="../shared/imagebank/mas.gif" width="9" height="17" border="0"></a><a href="javascript:uf_mostrar_ocultar_contrato();">Datos del Contrato </a></div></td>
        <td width="6">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td height="13"><div align="right"></div></td>
        <td height="13" colspan="4"></td>
        <td colspan="5"><div align="right"></div></td>
        <td>&nbsp;</td>
      </tr>
      
		<?Php
			if ($ls_datoscontrato=="MOSTRAR")
			{
				?>				
				<tr class="formato-blanco">
				  <td><div align="right"></div></td>
				  <td colspan="9" align="center" valign="top">
				  
				  <table width="509" height="30" border="0" cellpadding="1" cellspacing="1">
                    <tr class="letras-peque&ntilde;as">
                      <td width="204" class="celdas-blancas"><div align="right" class="letras-peque&ntilde;as">Monto del Contrato</div></td>
                      <td width="298" class="letras-peque&ntilde;as"><input name="txtmoncon" type="text"  style="text-align:right "  value="<?php print $ld_montocontrato?>" size="21" maxlength="21"  readonly="true">
                      Bs.</td>
                    </tr>
					
                    <tr class="letras-peque&ntilde;as">
                      <td class="letras-pequeñas"><div align="right">Variaciones (Aumentos-Disminuciones) </div></td>
                      <td class="letras-peque&ntilde;as"><input name="txtvartot"  id="txtvartot" type="text"  style="text-align:right "  value="<?php print $ld_monvar?>" size="21" maxlength="21"  readonly="true">
                        Bs.					  </td>
                    </tr>					
                    <tr class="letras-peque&ntilde;as">
                      <td class="letras-pequeñas"><div align="right">Total Monto Contrato </div></td>
                      <td class="letras-peque&ntilde;as"><input name="txtmontotfin" id="txtmontotfin" type="text"  style="text-align:right "  value="<?php print $ld_montocontratofinal?>" size="21" maxlength="21"  readonly="true">
                        Bs.</td>
                    </tr>
					
                    <tr class="letras-peque&ntilde;as">
                      <td class="celdas-blancas"><div align="right" class="letras-peque&ntilde;as">Monto Anticipos Previos</div></td>
                      <td class="letras-peque&ntilde;as"><input name="txtmontototalanticipo" type="text" onKeyPress="return(currencyFormat(this,'.',',',event))" style="text-align:right " id="txtmontototalanticipo2"  value="<?php print $ld_montototalanticipo?>" size="21" maxlength="21"  readonly="true">
                      Bs.</td>
                    </tr>
                    <tr class="letras-peque&ntilde;as">
                      <td class="celdas-blancas"><div align="right" class="letras-peque&ntilde;as">Obra</div></td>
                      <td class="letras-peque&ntilde;as"><textarea name="txtdesobr" cols="50" rows="1" readonly="true" id="textarea"><?php print $ls_desobr?></textarea></td>
                    </tr>
                  </table>			      			      </td>
				  <td>&nbsp;</td>
    </tr>
			<?Php
			}
			?>		
      
	  <tr class="formato-blanco">
        <td height="13" colspan="11" class="titulo-celdanew">Datos del Anticipo </td>
      </tr>	  
      <tr class="formato-blanco">
        <td height="39">&nbsp;</td>
        <td height="39"><div align="right">C&oacute;digo</div></td>
        <td height="39"><input name="txtcodant" id="txtcodant" style="text-align:center " value="<?php print $ls_codant?>" readonly="true" type="text" size="3" maxlength="3"></td>
        <td width="163" height="39"><div align="right">Estado</div></td>
        <td width="128"><input name="txtestant"  readonly="true" type="text" class="celdas-grises" id="txtestant" style="text-align:center " value="<?php print $ls_estant;?>" size="15" maxlength="20"></td>
        <td height="39" colspan="3">&nbsp;</td>
        <td width="34" height="39"><div align="right">Fecha</div></td>
        <td width="160" height="39"><input name="txtfecant" type="text" id="txtfecant"  style="text-align:center" value="<?php print $ls_fecant ?>" size="10" maxlength="10"  readonly="true"></td>
        <td height="39">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td height="26">&nbsp;</td>
        <td height="26">&nbsp;</td>
        <td height="26"><div align="right">Fecha del Anticipo</div></td>
        <td height="26" colspan="2"><input name="txtfecintant"   type="text" id="txtfecintant"  style="text-align:left" value="<?php print $ls_fecintant ?>" size="11" maxlength="10"  readonly="true" datepicker="true"></td>
        <td height="26" colspan="6">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td height="26">&nbsp;</td>
        <td height="26">&nbsp;</td>
        <td height="26"><div align="right">Monto del Anticipo </div></td>
        <td height="26" colspan="4"><input name="txtmonto" type="text"  style="text-align:right" id="txtmonto"size="21"   value="<?php print $ls_monto?>" maxlength="21" onKeyPress="return(validaCajas(this,'d',event,21)) " onBlur="javascript:ue_validamonto(this)" onFocus="javascript:ue_guardarvalor()" ></td>
		<td width="52" height="26"><div align="right">% del Monto del Contrato</div></td>
        <td height="26" colspan="2"><input name="txtporant"  style="text-align:right "type="text" id="txtporant" onKeyPress="return keyRestrict(event,'0123456789'+',');"   onBlur="javascript:ue_validaporcentaje(this)" value="<?php print $ls_porant?>" size="21" maxlength="6" onFocus="javascript:ue_guardarvalor()" readonly>
        %.</td>
        <td height="26">&nbsp;</td>
      </tr>
     
      <tr class="formato-blanco">
        <td height="25">&nbsp;</td>
        <td height="25">&nbsp;</td>
        <td height="25"><div align="right">Cuenta Contable</div></td>
        <td height="25" colspan="7"><input name="txtsc_cuenta"  style="text-align:center " readonly="true" type="text" id="txtsc_cuenta" value="<?php print $ls_sc_cuenta?> ">        </td>
        <td height="25">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td height="47">&nbsp;</td>
        <td height="47">&nbsp;</td>
        <td height="47"><div align="right">Concepto</div></td>
        <td height="47" colspan="7"><textarea name="txtconant" cols="70" rows="2" onKeyDown="textCounter(this,254)"  onKeyUp="textCounter(this,254)" id="txtconant" onKeyPress="return(validaCajas(this,'x',event,254))"><?php print $ls_conant?></textarea></td>
        <td height="47">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="11"><div align="center"></div></td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="11">&nbsp;</td>
      </tr>
    </table>
  <!-- Los Hidden son colocados a partir de aca-->
<input name="hiddatoscontrato" type="hidden" id="hiddatoscontrato" value="<?php print $ls_datoscontrato;?>">
<input name="hiddatosobra" type="hidden" id="hiddatosobra" value="<?php print $ls_datosobra;?>">
<input name="hidfilasretenciones" type="hidden" id="hidfilasretenciones" value="<?php print $li_filasretenciones;?>">
<input name="hidremoverretenciones" type="hidden" id="hidremoverretenciones" value="<?php print $li_removerretenciones;?>">
<input name="hidobra" type="hidden" id="hidobra" value="<?php print $ls_obra;?>">
<input name="hidmontocontrato" type="hidden" id="hidmontocontrato" value="<?php print $ld_montocontratofinal;?>">
<input name="hidmontototalanticipo" type="hidden" id="hidmontototalanticipo" value="<?php print $ld_montototalanticipo;?>">
<input name="hiddesobr" type="hidden" id="hiddesobr" value="<?php print $ls_desobr;?>">
<input name="monto" id="monto" type="hidden">
<input name="porcentaje" id="porcentaje" type="hidden">



<!-- Fin de la declaracion de Hidden-->
  <input name="estgenrd" type="hidden" id="estgenrd">
  </form>
</body>
<script language="javascript">

///////Funciones para llamar catalogos////////////////
function ue_catcontrato()
{
	f=document.form1;
	f.operacion.value="";			
	var opener="anticipo";
	pagina="sigesp_cat_contrato.php?opener="+opener;
	popupWin(pagina,"catalogo",850,400);
//	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=400,resizable=yes,location=no,left=0,top=0;");
}

function ue_buscartipodocumento()
{
	window.open("sigesp_sob_cat_tipodocumentos.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_catretenciones()
{
	f=document.form1;
	if(f.txtcodant.value=="")
	{
		alert("Debe seleccionar un nuevo Anticipo!!!");
	}
	else
	{
		if(f.txtmonto.value=="" || parseInt(f.txtmonto.value)==0)
		{
			alert("Debe especificar un monto para el Anticipo!!!");
			f.txtmonto.value="0,00";
			f.txtmonto.focus();			
		}
		else
		{
			lb_valido=true;
			for(li_i=1;li_i<f.hidfilasretenciones.value;li_i++)
			{				
				
				if(eval("f.txtmonret"+li_i+".value==''") || parseFloat(uf_convertir_monto(eval("f.txtmonret"+li_i+".value")))==0)
				{					
					lb_valido=false;
					break;		
				}
			}
			
			if(lb_valido)
			{
				f.operacion.value="";			
				pagina="sigesp_cat_retenciones.php";
				popupWin(pagina,"catalogo",600,300);
			}
			else
			{
				ls_codret=eval("f.txtcodret"+li_i+".value");
				alert("Favor indicar el monto objeto de la retención "+ls_codret);
			}
			
		}		
	}
	
}

///////Fin de las Funciones para para llamar catalogos/////

//////Funciones para cargar datos provenientes de catalogos///////
function ue_cargarcontrato(ls_codigo,ls_desobr,ls_estado,ls_codest,ld_monto,ls_placon,ls_placonuni,ls_mulcon,ls_tiemulcon,ls_mulreuni,ls_lapgarcon,ls_lapgaruni,
						ls_codtco,ls_monmaxcon,ls_pormaxcon,ls_obscon,ls_porejefiscon,ls_porejefincon,ls_monejefincon,ls_codasi,ls_feccrecon,
						ls_fecinicon,ls_nomtco,ls_codobr,ls_codpro,ls_codproins,ls_precon)
{
	f=document.form1;
	f.txtcodcon.value=ls_codigo;
	f.hidobra.value=ls_codobr;
	f.operacion.value="ue_cargarcontrato";
	f.submit();
}
function ue_cargarretenciones(codigo,descripcion,cuenta,deducible,formula)
{
	f=document.form1;
	f.operacion.value="ue_cargarretenciones";	
	lb_existe=false;
		
	for(li_i=1;li_i<=f.hidfilasretenciones.value && !lb_existe;li_i++)
	{
		ls_codigo=eval("f.txtcodret"+li_i+".value");
		//alert("codigo nuevo '"+codigo+"' codigo de la comparacion '"+eval("f.txtcodpar"+f.filaspartidas.value+".value")+"'");
		if(ls_codigo==codigo)
		{
			alert("Detalle ya existe!!!");
			lb_existe=true;
		}
	}	
	
	if(!lb_existe)
	{
		eval("f.txtcodret"+f.hidfilasretenciones.value+".value='"+codigo+"'");
		eval("f.txtdesret"+f.hidfilasretenciones.value+".value='"+descripcion+"'");
		eval("f.txtcueret"+f.hidfilasretenciones.value+".value='"+cuenta+"'");
		eval("f.txtdedret"+f.hidfilasretenciones.value+".value='"+deducible+"'");
		eval("f.formula"+f.hidfilasretenciones.value+".value='"+formula+"'");
		f.submit();
	}

}

function ue_removerretenciones(li_fila)
{
	f=document.form1;
	f.hidremoverretenciones.value=li_fila;
	f.operacion.value="ue_removerretenciones"
	f.action="sigesp_sob_d_anticipo.php";
	f.submit();
}

function ue_cargaranticipo (ls_codcon,ls_desobr,ls_estado,ls_codest,ls_codobr,ld_monto,ls_codant,
							ls_fecintant,ld_porant,ls_conant,ld_montotant,ls_cuenta,ls_fecant,ld_montocontrato,ls_estgenrd)
{
	f=document.form1;
	f.txtcodcon.value=ls_codcon;
	f.hidobra.value=ls_codobr;
	f.txtcodant.value=ls_codant;
	f.txtfecintant.value=ls_fecintant;
	f.txtfecant.value=ls_fecant;
	f.txtmonto.value=uf_convertir(ld_monto);
	f.txtporant.value=uf_convertir(ld_porant);
	f.txtsc_cuenta.value=ls_cuenta;
	f.txtconant.value=ls_conant;
	f.txtestant.value=ls_estado;
	f.estgenrd.value=ls_estgenrd;
	f.hidmontototalanticipo.value=uf_convertir(ld_montotant);
	f.hidmontocontrato.value=uf_convertir(ld_montocontrato);
	f.operacion.value="ue_cargaranticipo";
	f.hidstatus.value="C";
	f.submit();
}

//////Fin de las funciones para cargar datos provenientes de catalogos///
function ue_generar_recepcion()
{
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	if(li_ejecutar==1)
	{
		hidestatus=f.hidstatus.value;
		conant=f.txtcodant.value;
		if(f.txtcodtipdoc.value=="")
		{
			alert("Debe indicar un tipo de documento para la Recepción a generar");
		}
		else
		{
			if((conant!="")&&(hidestatus=="C"))
			{
				f.operacion.value="PROCESAR";
				f.action="sigesp_sob_d_anticipo.php";
				f.submit();
			}
		}
	}
	else
   	{alert("No tiene permiso para realizar esta operacion");}
}


//Funciones de Validacion///////////////////

function ue_validaporcentaje(ld_porcentaje)
  {
    ld_porant=parseFloat(f.txtporant.value);
	//alert(ld_porant);
	if (ld_porant>100)
	 {
	  	ld_porcentaje.value="0,00";
		alert("El campo de porcentaje no debe exceder del 100%");	  
	 }
	else 
	 {
	  ue_validamonto(ld_porcentaje);
	 } 
  }

function validamontolleno()
{
	lb_valido=true;
	for(li_i=1;li_i<f.filasfuentes.value;li_i++)
	{
		if((eval("f.txtmonfuefin"+li_i+".value")  == "") || (eval("f.txtmonfuefin"+li_i+".value")  == "0,00"))
		{
			lb_valido=false;
		}
	}	
	return lb_valido;
}

function ue_validamonto(txt)
{
	ue_getformat(txt)
	f=document.form1;
	ld_montolimitecontrato=parseFloat(uf_convertir_monto(f.hidmontocontrato.value))-parseFloat(uf_convertir_monto(f.hidmontototalanticipo.value));
	ld_montocontrato=parseFloat(uf_convertir_monto(f.hidmontocontrato.value));
	ld_montoanticipo=parseFloat(uf_convertir_monto(f.txtmonto.value));
	ld_porcentaje=parseFloat(uf_convertir_monto(f.txtporant.value));
	ld_porcentajelimite=ld_montolimitecontrato*100/ld_montocontrato;	
	if(txt.value!="")
	{
		if(txt.id=="txtmonto")
		{
			if(f.monto.value!=txt.value)
			{
				if(ld_montoanticipo<=ld_montolimitecontrato)
				{
					f.txtporant.value=uf_convertir(ld_montoanticipo*100/ld_montocontrato);
					if(f.hidfilasretenciones.value>1)
						ue_actualizarmontototal();
					else
						f.txtmontotant.value=f.txtmonto.value;	
				}
				else
				{
					txt.value="0,00";
					alert("El monto del Anticipo no debe pasar al monto total del contrato menos la sumatoria de los anticipos anteriores!!!");
					txt.focus();
				}
			}			
		}
		else
		{
			if(f.porcentaje.value!=txt.value)
			{
				if(ld_porcentaje<=ld_porcentajelimite)
				{
					f.txtmonto.value=uf_convertir(ld_porcentaje*ld_montocontrato/100);
					if(f.hidfilasretenciones.value>1)
						ue_actualizarmontototal();	
					else
						f.txtmontotant.value=f.txtmonto.value;	
				}
				else
				{
					txt.value="0,00";
					alert("El monto del Anticipo no debe pasar al monto total del contrato menos la sumatoria de los anticipos anteriores!!!");
					txt.focus();
				}
			}			
		}		
	}	
	
}

function ue_actualizarmontototal()
{
	f=document.form1;
	f.operacion.value="ue_calcretencion";
	f.submit();
}


function uf_procesarporcentaje (txt)
{
	f=document.form1;
	ld_montocontrato=parseFloat(uf_convertir_monto(f.txtmonto.value));	
	ld_montomaximo=parseFloat(uf_convertir_monto(f.txtmonmaxcon.value));
	if (ld_montocontrato!=ld_montomaximo || f.txtpormaxcon.value!="0,00")
	{	//7
		if(txt.id=="txtmonmaxcon")
		{//3
			if(ld_montomaximo>0)
			{//4
				if (ld_montomaximo < ld_montocontrato)
				{//5
					alert("El Monto Máximo debe ser mayor o igual al Monto del Contrato!!!");
					txt.value=uf_convertir(ld_montocontrato);
				}//5
				else
				{//6
					/*alert("monto maximo "+ld_montomaximo);
					alert("monto contrato "+ld_montocontrato);*/
					ld_montoaumento=parseFloat(ld_montomaximo-ld_montocontrato);
					/*alert ("monto aument "+ld_montoaumento);
					alert ("monto contrato "+ld_montocontrato);*/
					ld_porcentaje=(ld_montoaumento*100/ld_montocontrato);
					f.txtpormaxcon.value=uf_convertir(ld_porcentaje);
				}//6
			}//4
		}//3
		else
		{//2
			ld_porcentaje=uf_convertir_monto(txt.value);
			if (ld_porcentaje>0)//1
			{
				ld_montomaximo=parseFloat(ld_porcentaje*ld_montocontrato/100)+parseFloat(ld_montocontrato);
				f.txtmonmaxcon.value=uf_convertir(ld_montomaximo);		
			}//1
		}//2
	}//end primer if//7
}

function ue_guardarvalor()
{
	f=document.form1;
	f.monto.value=f.txtmonto.value;
	f.porcentaje.value=f.txtporant.value;
}

//////////////////////////////Fin de las funciones de validacion
function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		if(f.txtcodcon.value=="")
		{
			alert("Debe seleccionar un Contrato!!!");
		}
		else
		{
			f.operacion.value="ue_nuevo";
			f.action="sigesp_sob_d_anticipo.php";
			f.submit();
		}
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}	    	
}
		/*Function:  ue_buscar()
	 *
	 *Descripción: Función que se encarga de hacer el llamado al catalogo de obras*/  
function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		var estado="";
		pagina="sigesp_cat_anticipo.php?estado="+estado;
		popupWin(pagina,"catalogo",700,350);
//		window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=350,resizable=yes,location=no,status=no,top=0,left=0");
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
} 
/*Fin de la Función ue_buscar()*/

/*Function ue_guardar
	Funcion que se encarga de guardar los datos de la obra, revisando previamente la validez de los datos
*/

function ue_guardar()
{
	f=document.form1;		
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_status=f.hidstatus.value;
	if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
	{
		with(form1)
		{
			if(ue_valida_null(txtcodcon,"Código del Contrato")==false)
			{
			}
			else
			{
				if(ue_valida_null(txtcodant,"Código de Anticipo")==false)
				{				
				}
				else
				{
					if(ue_valida_null(txtfecintant,"Fecha del Anticipo")==false)
					{				
						txtfecintant.focus();
					}
					else
					{
						if(txtmonto.value=="" || parseInt(txtmonto.value)==0)
						{				
							txtmonto.focus();
							alert("El Campo Monto está vacío!!!");
						}
						else
						{
							if(txtporant.value=="" || txtporant.value=="0,00" || txtporant.value=="0,0" || txtporant.value=="0," || txtporant.value=="0")
							{				
								txtporant.focus();
								alert("El campo porcentaje está vacío!!!");
							}
						
							else
							{
								if(ue_valida_null(txtconant,"Concepto")==false)
								{				
									txtconant.focus();
								}
								else
								{
									f.action="sigesp_sob_d_anticipo.php";
									f.operacion.value="ue_guardar";
									f.submit();
								}
							}
						}
					}
				}
			}		
		}//fin del with	
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}	
}///////Fin de la funcion ue_guardar

function ue_eliminar()
{
	var lb_borrar="";		
	f=document.form1;
	li_eliminar=f.eliminar.value;
	if(li_eliminar==1)
	{	
	   if (f.txtcodant.value=="")
	   {
		 alert("No ha seleccionado ningún registro para eliminar !!!");
	   }
		else
		{
			borrar=confirm("¿ Esta seguro de eliminar este registro ?");
			if (borrar==true)
			   { 
				 f=document.form1;
				 f.operacion.value="ue_eliminar";
				 f.action="sigesp_sob_d_anticipo.php";
				 f.submit();
			   }
		}	
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}   
}
	
function uf_mostrar_ocultar_contrato()  
{
	f=document.form1;
	if (f.txtcodcon.value=="")
	{
		alert("Debe seleccionar un Contrato!!!");
	}
	else
	{
		if (f.hiddatoscontrato.value == "OCULTAR")
		{
			f.hiddatoscontrato.value = "MOSTRAR";
			f.operacion.value="ue_cargarcontrato";
			
		}
		else
		{
			f.hiddatoscontrato.value = "OCULTAR";
			f.operacion.value="";
		}
		f.submit();
	}
}

function uf_mostrar_ocultar_obra()  
{
	f=document.form1;
	if (f.txtcodcon.value=="")
	{
		alert("Debe seleccionar un Contrato!!!");
	}
	else
	{
		if (f.hiddatosobra.value == "OCULTAR")
		{
			f.hiddatosobra.value = "MOSTRAR";
			f.operacion.value="ue_cargarobra";
			
		}
		else
		{
			f.hiddatosobra.value = "OCULTAR";
			f.operacion.value="";
		}
		f.submit();
	}
}

function ue_calcretencion(c)
 {
	f=document.form1;
	ld_subtot=parseFloat(uf_convertir_monto(f.txtmonto.value));
	ld_monret=parseFloat(uf_convertir_monto(c.value));
	if(ld_monret<ld_subtot)
	{
	 	 f.operacion.value="ue_calcretencion";
		 f.submit();
	}
	else
	{
	 alert("El monto objeto de retencion debe ser menor al total del anticipo");
	 c.value="0,00";
	}
 }

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>