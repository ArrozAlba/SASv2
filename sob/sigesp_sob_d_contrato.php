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
	$io_fun_sob->uf_load_seguridad("SOB","sigesp_sob_d_contrato.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head>
<title>Elaboraci&oacute;n de Contrato</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_sob.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<link href="js/ComboDinamico/ComboBox.css" rel="stylesheet" type="text/css">
<script src="js/ComboDinamico/ComboBox.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
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

<span class="toolbar"><a name="inicio"></a></span>
<?Php

require_once("class_folder/sigesp_sob_c_contrato.php");
$io_contrato=new sigesp_sob_c_contrato();
require_once("class_folder/sigesp_sob_class_obra.php");
$io_obra=new sigesp_sob_class_obra();
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
$io_funnum= new sigesp_sob_c_funciones_sob(); 
require_once ("class_folder/sigesp_sob_class_asignacion.php");
$io_asignacion=new sigesp_sob_class_asignacion();
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
$la_columretenciones[5]="Edición";

$ls_titulocondiciones="Condiciones de Pago";
$li_anchocondiciones=375;
$ls_nametable="grid2";
$la_columcondiciones[1]="#";
$la_columcondiciones[2]="Fecha";
$la_columcondiciones[3]="Monto";
$la_columcondiciones[4]="Porcentaje";
$la_columcondiciones[5]="Edición";

$ls_titulogarantias="Garantías Presentadas";
$li_anchogarantias=600;
$ls_nametable="grid2";
$la_columgarantias[1]="#";
$la_columgarantias[2]="Descripción";
$la_columgarantias[3]="Edición";

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$li_filasretenciones=$_POST["hidfilasretenciones"];
	
	////////Cargando nuevamente el objeto de las tablas///////////
	if ($ls_operacion != "ue_cargarretenciones" && $ls_operacion != "ue_removerretenciones" && $ls_operacion != "ue_cargarcontrato")
	{
		for($li_i=1;$li_i<$li_filasretenciones;$li_i++)
		{
			$ls_codigo=$_POST["txtcodret".$li_i];
			$ls_descripcion=$_POST["txtdesret".$li_i];
			$ls_cuenta=$_POST["txtcueret".$li_i];
			$ls_deduccion=$_POST["txtdedret".$li_i];
			$la_objectretenciones[$li_i][1]="<input name=txtcodret".$li_i." type=text id=txtcodret".$li_i." class=sin-borde value='".$ls_codigo."' style= text-align:center size=5 readonly>";
			$la_objectretenciones[$li_i][2]="<input name=txtdesret".$li_i." type=text id=txtdesret".$li_i." class=sin-borde value='".$ls_descripcion."' style= text-align:left size=35 readonly>";
			$la_objectretenciones[$li_i][3]="<input name=txtcueret".$li_i." type=text id=txtcueret".$li_i." class=sin-borde value='".$ls_cuenta."' style= text-align:left size=23 readonly>";
			$la_objectretenciones[$li_i][4]="<input name=txtdedret".$li_i." type=text id=txtdedret".$li_i." class=sin-borde value='".$ls_deduccion."' style= text-align:left size=20 readonly>";
			$la_objectretenciones[$li_i][5]="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=javascript:ue_removerretenciones(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
		}	
		$la_objectretenciones[$li_filasretenciones][1]="<input name=txtcodret".$li_filasretenciones." type=text id=txtcodret".$li_filasretenciones." class=sin-borde style= text-align:center size=5 readonly>";
		$la_objectretenciones[$li_filasretenciones][2]="<input name=txtdesret".$li_filasretenciones." type=text id=txtdesret".$li_filasretenciones." class=sin-borde style= text-align:left size=25 readonly>";
		$la_objectretenciones[$li_filasretenciones][3]="<input name=txtcueret".$li_filasretenciones." type=text id=txtcueret".$li_filasretenciones." class=sin-borde  style= text-align:center size=20 readonly>";
		$la_objectretenciones[$li_filasretenciones][4]="<input name=txtdedret".$li_filasretenciones." type=text id=txtdedret".$li_filasretenciones." class=sin-borde  style= text-align:center size=10 readonly>";
		$la_objectretenciones[$li_filasretenciones][5]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	}		

///////////////////////////////////////////////////////////////////////////////////////////////////	
}
else
{
	$ls_datosasignacion="OCULTAR";
	$ls_datosobra="OCULTAR";
	$ls_datoscontrato="OCULTAR";
	$ls_puncueasi="";
	$ls_fecasi="";
	$ls_contasi="";
	$ls_inspasi="";
	$ls_monasi="";
	$ls_codobr="";
	$ls_desobr="";
	$ls_estobr="";
	$ls_munobr="";
	$ls_parobr="";
	$ls_comobr="";
	$ls_dirobr="";
	$ls_codasi="";
	$ls_codcon="";
	$ls_fecfincon="";
	$ls_feccon ="";
	$ls_destipcon ="";
	$ls_fecinicon ="";
	$li_placon="";
	$ls_monto="0,00";
	$ls_monmaxcon="0,00";
	$ls_pormaxcon="0,00";
	$ls_hidprefijo="";
	$ls_mulcon="0,00";
	$ls_lapgarcon="";
	$ls_obscon="";
	$ls_estcon="";
	$ls_porejefiscon="";
	$ls_porejefincon="";
	$ls_monejefincon="";
	$li_filascondiciones=1;
	$li_filasgarantias=1;
	$li_filasretenciones=1;
	$li_removerretenciones="";
	$li_removercondiciones="";
	$li_removergarantias="";	
	$ls_lapmulcon="";
	$ls_operacion="";
	$li_placon="";
	$ls_codunigarantia="";
	$ls_coduniduracion="";
	$ls_coduniretraso="";
	$ls_codtco="";
	$ls_carasi="";
	$ls_montotasi="";
	$ls_estado="";
	$ls_hidstatus="";
	$li_hidfilasretenciones=1;
	$la_objectretenciones[1][1]="<input name=txtcodret1 type=text id=txtcodret1 class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectretenciones[1][2]="<input name=txtdesret1 type=text id=txtdesret1 class=sin-borde style= text-align:left size=35 readonly>";
	$la_objectretenciones[1][3]="<input name=txtcueret1 type=text id=txtcueret1 class=sin-borde style= text-align:center size=23 readonly>";
	$la_objectretenciones[1][4]="<input name=txtdedret1 type=text id=txtdedret1 class=sin-borde style= text-align:center size=20 readonly>";
	$la_objectretenciones[1][5]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	
	$li_filascondiciones=1;
	$la_objectcondiciones[1][1]="<input name=txtnumcon1 type=text id=txtnumcon1 class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectcondiciones[1][2]="<input name=txtfeccon1  type=text id=txtfeccon1  class=sin-borde style= text-align:center size=10 readonly>";
	$la_objectcondiciones[1][3]="<input name=txtmoncon1 type=text id=txtmoncon1 class=sin-borde style= text-align:center size=30 readonly>";
	$la_objectcondiciones[1][4]="<input name=txtporcon1 type=text id=txtporcon1 class=sin-borde style= text-align:center size=11 readonly>";
	$la_objectcondiciones[1][5]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
	
	$li_filasgarantias=1;
	$la_objectgarantias[1][1]="<input name=txtnumgar1 type=text id=txtnumgar1 class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectgarantias[1][2]="<input name=txtdesgar1  type=text id=txtdesgar1  class=sin-borde style= text-align:left size=90 readonly >";
	$la_objectgarantias[1][3]="<input name=txtvacio3 type=text id=txtvacio3 class=sin-borde style= text-align:center size=5 readonly>";

}

/////////Instrucciones para evitar que las cajitas pierdan la informacion cada vez que se realiza un submit/////////////
if	(array_key_exists("hiddatosasignacion",$_POST)){	$ls_datosasignacion=$_POST["hiddatosasignacion"]; }
else{$ls_datosasignacion="OCULTAR";}

if	(array_key_exists("hiddatosobra",$_POST)){	$ls_datosobra=$_POST["hiddatosobra"]; }
else{$ls_datosobra="OCULTAR";}

if	(array_key_exists("hiddatoscontrato",$_POST)){	$ls_datoscontrato=$_POST["hiddatoscontrato"]; }
else{$ls_datoscontrato="OCULTAR";}

if	(array_key_exists("operacion",$_POST)){	$ls_operacion=$_POST["operacion"]; }
else{$ls_operacion="";}

if	(array_key_exists("txtcodcon",$_POST)){	$ls_codcon=$_POST["txtcodcon"]; }
else{$ls_codcon="";}

if	(array_key_exists("txtpuncueasi",$_POST)){	$ls_puncueasi=$_POST["txtpuncueasi"]; }
else{$ls_puncueasi="";}

if	(array_key_exists("txtfecasi",$_POST)){	$ls_fecasi=$_POST["txtfecasi"]; }
else{$ls_fecasi="";}

if	(array_key_exists("txtcontasi",$_POST)){$ls_contasi=$_POST["txtcontasi"]; }
else{$ls_contasi="";}

if	(array_key_exists("txtmontasi",$_POST)){$ls_montasi=$_POST["txtmontasi"]; }
else{$ls_montasi="";}

if	(array_key_exists("txtinspasi",$_POST)){$ls_inspasi=$_POST["txtinspasi"]; }
else{$ls_inspasi="";}

if	(array_key_exists("txtmonasi",$_POST)){$ls_monasi=$_POST["txtmonasi"]; }
else{$ls_monasi="";}

if	(array_key_exists("txtcodobr",$_POST)){$ls_codobr=$_POST["txtcodobr"]; }
else{$ls_codobr="";}

if	(array_key_exists("txtdesobr",$_POST)){$ls_desobr=$_POST["txtdesobr"]; }
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

if	(array_key_exists("txtcodasi",$_POST)){$ls_codasi=$_POST["txtcodasi"]; }
else{$ls_codasi="";}

if	(array_key_exists("txtcodcon",$_POST)){$ls_codcon=$_POST["txtcodcon"]; }
else{$ls_codcon="";}

if	(array_key_exists("txtfeccon",$_POST)){$ls_feccon=$_POST["txtfeccon"]; }
else{$ls_feccon="";}

if	(array_key_exists("txtdestipcon",$_POST)){$ls_destipcon=$_POST["txtdestipcon"]; }
else{$ls_destipcon="";}

if	(array_key_exists("txtfecinicon",$_POST)){$ls_fecinicon=$_POST["txtfecinicon"]; }
else{$ls_fecinicon="";}	

if	(array_key_exists("txtmonto",$_POST)){$ls_monto=$_POST["txtmonto"]; }
else{$ls_monto="0,00";}	

if	(array_key_exists("txtmonmaxcon",$_POST)){$ls_monmaxcon=$_POST["txtmonmaxcon"]; }
else{$ls_monmaxcon="0,00";}	

if	(array_key_exists("txtpormaxcon",$_POST)){$ls_pormaxcon=$_POST["txtpormaxcon"]; }
else{$ls_pormaxcon="0,00";}	

if	(array_key_exists("txtmulcon",$_POST)){$ls_mulcon=$_POST["txtmulcon"]; }
else{$ls_mulcon="0,00";}		

if	(array_key_exists("txtlapgarcon",$_POST)){$ls_lapgarcon=$_POST["txtlapgarcon"]; }
else{$ls_lapgarcon="";}			

if	(array_key_exists("txtobscon",$_POST)){$ls_obscon=$_POST["txtobscon"]; }
else{$ls_obscon="";}			

if	(array_key_exists("txtestcon",$_POST)){$ls_estcon=$_POST["txtestcon"]; }
else{$ls_estcon="";}	

if	(array_key_exists("txtporejefiscon",$_POST)){$ls_porejefiscon=$_POST["txtporejefiscon"]; }
else{$ls_porejefiscon="";}		

if	(array_key_exists("txtporejefincon",$_POST)){$ls_porejefincon=$_POST["txtporejefincon"]; }
else{$ls_porejefincon="";}	

if	(array_key_exists("txtmonejefincon",$_POST)){$ls_monejefincon=$_POST["txtmonejefincon"]; }
else{$ls_monejefincon="";}	

if	(array_key_exists("hidfilascondiciones",$_POST)){$li_filascondiciones=$_POST["hidfilascondiciones"]; }
else{$li_filascondiciones=1;}		

if	(array_key_exists("hidfilasgarantias",$_POST)){$li_filasgarantias=$_POST["hidfilasgarantias"]; }
else{$li_filasgarantias=1;}		

if	(array_key_exists("hidfilasretenciones",$_POST)){$li_filasretenciones=$_POST["hidfilasretenciones"]; }
else{$li_filasretenciones=1;}	

if	(array_key_exists("hidremoverretenciones",$_POST)){$li_removerretenciones=$_POST["hidremoverretenciones"]; }
else{$li_removerretenciones="";}

if	(array_key_exists("hidremovercondiciones",$_POST)){$li_removercondiciones=$_POST["hidremovercondiciones"]; }
else{$li_removercondiciones="";}	

if	(array_key_exists("hidremovergarantias",$_POST)){$li_removergarantias=$_POST["hidremovergarantias"]; }
else{$li_removergarantias="";}	

if	(array_key_exists("txtplacon",$_POST)){$li_placon=$_POST["txtplacon"]; }
else{$li_placon="";}	

if	(array_key_exists("txtlapmulcon",$_POST)){$ls_lapmulcon=$_POST["txtlapmulcon"]; }
else{$ls_lapmulcon="";}	

if	(array_key_exists("hidgarantia",$_POST)){$ls_codunigarantia=$_POST["hidgarantia"]; }
else{$ls_codunigarantia="";}

if	(array_key_exists("hidduracion",$_POST)){$ls_coduniduracion=$_POST["hidduracion"]; }
else{$ls_coduniduracion="";}

if	(array_key_exists("hidretraso",$_POST)){$ls_coduniretraso=$_POST["hidretraso"]; }
else{$ls_coduniretraso="";}

if	(array_key_exists("hidtipocontrato",$_POST)){$ls_codtco=$_POST["hidtipocontrato"]; }
else{$ls_codtco="";}

if	(array_key_exists("cmbgarantia",$_POST)){$ls_codunigarantia=$_POST["cmbgarantia"]; }
else{$ls_codunigarantia="";}

if	(array_key_exists("cmbduracion",$_POST)){$ls_coduniduracion=$_POST["cmbduracion"]; }
else{$ls_coduniduracion="";}

if	(array_key_exists("cmbretraso",$_POST)){$ls_coduniretraso=$_POST["cmbretraso"]; }
else{$ls_coduniretraso="";}

if	(array_key_exists("cmbtipocontrato",$_POST)){$ls_codtco=$_POST["cmbtipocontrato"]; }
else{$ls_codtco="";}

if	(array_key_exists("txtcarasi",$_POST)){$ls_carasi=$_POST["txtcarasi"]; }
else{$ls_carasi="";}

if	(array_key_exists("txtmontotasi",$_POST)){$ls_montotasi=$_POST["txtmontotasi"]; }
else{$ls_montotasi="";}

if	(array_key_exists("txtestcon",$_POST)){$ls_estado=$_POST["txtestcon"]; }
else{$ls_estado="";}

if	(array_key_exists("txtfecfincon",$_POST)){$ls_fecfincon=$_POST["txtfecfincon"]; }
else{$ls_fecfincon="";}

if	(array_key_exists("hidprefijo",$_POST)){$ls_hidprefijo=$_POST["hidprefijo"]; }
else{$ls_hidprefijo="";}

if	(array_key_exists("hidfecasi",$_POST)){$ls_fecasiaux=$_POST["hidfecasi"]; }
else{$ls_fecasiaux="";}

if	(array_key_exists("hidstatus",$_POST)){$ls_hidstatus=$_POST["hidstatus"]; }
else{$ls_hidstatus="";}

if	(array_key_exists("hidestapr",$_POST)){$ls_estapr=$_POST["hidestapr"]; }
else{$ls_estapr="";}

////////////////////////////////Operaciones de Actualizacion//////////////////////////////////////

if($ls_operacion=="ue_nuevo")//Abre una ficha de obra nueva
{
	$io_asignacion->uf_select_estado($ls_codasi,$ls_estasi);
	if($ls_estasi!=1 && $ls_estasi!=5 && $ls_estasi!=6)
	{
		$ls_estado=$io_funnum->uf_convertir_numeroestado($ls_estasi);
		$io_msg->message("Debe seleccionar una nueva Asignación, ya que su estado es $ls_estado");
		$ls_codasi="";
		$ls_codcon="";
		$ls_estado="";
		$ls_feccon ="";		
		$ls_monto="";
		$ls_monmaxcon="";
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
		$ls_codcon= $io_keygen->uf_generar_numero_nuevo("SOB","sob_contrato","codcon","SOBCON",12,"","","");
		if($ls_codcon===false)
		{
			print "<script language=JavaScript>";
			print "location.href='sigespwindow_blank.php'";
			print "</script>";		
		}
		//$ls_codcon=$io_funcdb->uf_generar_codigo(true,$la_empresa["codemp"],"sob_contrato","codcon",6);
		$ls_estado="EMITIDO";
		//$fecha=date("d/m/Y");
		//$ls_feccon=$ls_fecasi;	
		$lb_valido=$io_contrato->uf_select_asignacion($ls_codasi,$la_asignacion);
		if ($lb_valido)
		{
			$io_datastore->data=$la_asignacion;
			$li_i=1;
			$ls_monto=$io_datastore->getValue("montotasi",$li_i);
			$ls_monto=$io_funnum->uf_convertir_numerocadena($ls_monto);
			//$ls_monmaxcon=$ls_monto;
		}
	}	
	$ls_datoscontrato="OCULTAR";	
	$ls_destipcon ="";
	$ls_fecinicon ="";
	$ls_monmaxcon="0,00";
	$ls_pormaxcon="0,00";
	$ls_mulcon="0,00";
	$ls_lapgarcon="";
	$ls_codtco="";
	$ls_obscon="";
	$ls_estcon="";	
	$ls_fecfincon="";
	$li_placon="";
	$ls_porejefiscon="";
	$ls_porejefincon="";
	$ls_hidprefijo="";
	$ls_monejefincon="";
	$li_filascondiciones=1;
	$li_filasgarantias=1;
	$li_filasretenciones=1;
	$li_removerretenciones="";
	$li_removercondiciones="";
	$li_removergarantias="";	
	$ls_lapmulcon="";
	$ls_operacion="";
	$ls_codunigarantia="";
	$ls_coduniduracion="";
	$ls_coduniretraso="";
	$li_hidfilasretenciones=1;
	$ls_hidstatus="";
	$la_objectretenciones[1][1]="<input name=txtcodret1 type=text id=txtcodret1 class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectretenciones[1][2]="<input name=txtdesret1 type=text id=txtdesret1 class=sin-borde style= text-align:left size=35 readonly>";
	$la_objectretenciones[1][3]="<input name=txtcueret1 type=text id=txtcueret1 class=sin-borde style= text-align:center size=23 readonly>";
	$la_objectretenciones[1][4]="<input name=txtdedret1 type=text id=txtdedret1 class=sin-borde style= text-align:center size=20 readonly>";
	$la_objectretenciones[1][5]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
}
elseif($ls_operacion=="ue_cargarretenciones")
{	
	$li_filasretenciones=$li_filasretenciones+1;
	
	for($li_i=1;$li_i<$li_filasretenciones;$li_i++)
	{
		$ls_codigo=$_POST["txtcodret".$li_i];
		$ls_descripcion=$_POST["txtdesret".$li_i];
		$ls_cuenta=$_POST["txtcueret".$li_i];
		$ls_deduccion=$_POST["txtdedret".$li_i];
		$la_objectretenciones[$li_i][1]="<input name=txtcodret".$li_i." type=text id=txtcodret".$li_i." class=sin-borde value='".$ls_codigo."' style= text-align:center size=5 readonly>";
		$la_objectretenciones[$li_i][2]="<input name=txtdesret".$li_i." type=text id=txtdesret".$li_i." class=sin-borde value='".$ls_descripcion."' style= text-align:left size=35 readonly>";
		$la_objectretenciones[$li_i][3]="<input name=txtcueret".$li_i." type=text id=txtcueret".$li_i." class=sin-borde value='".$ls_cuenta."' style= text-align:center size=23 readonly>";
		$la_objectretenciones[$li_i][4]="<input name=txtdedret".$li_i." type=text id=txtdedret".$li_i." class=sin-borde value='".$ls_deduccion."' style= text-align:center size=20 readonly>";
		$la_objectretenciones[$li_i][5]="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=javascript:ue_removerretenciones(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
	}	
	$la_objectretenciones[$li_filasretenciones][1]="<input name=txtcodret".$li_filasretenciones." type=text id=txtcodret".$li_filasretenciones." class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectretenciones[$li_filasretenciones][2]="<input name=txtdesret".$li_filasretenciones." type=text id=txtdesret".$li_filasretenciones." class=sin-borde style= text-align:left size=35 readonly>";
	$la_objectretenciones[$li_filasretenciones][3]="<input name=txtcueret".$li_filasretenciones." type=text id=txtcueret".$li_filasretenciones." class=sin-borde  style= text-align:center size=23 readonly>";
	$la_objectretenciones[$li_filasretenciones][4]="<input name=txtdedret".$li_filasretenciones." type=text id=txtdedret".$li_filasretenciones." class=sin-borde  style= text-align:center size=20 readonly>";
	$la_objectretenciones[$li_filasretenciones][5]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	
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
			$ls_deduccion=$_POST["txtdedret".$li_i];
			$la_objectretenciones[$li_temp][1]="<input name=txtcodret".$li_temp." type=text id=txtcodret".$li_temp." class=sin-borde value='".$ls_codigo."' style= text-align:center size=5 readonly>";
			$la_objectretenciones[$li_temp][2]="<input name=txtdesret".$li_temp." type=text id=txtdesret".$li_temp." class=sin-borde value='".$ls_descripcion."' style= text-align:left size=35 readonly>";
			$la_objectretenciones[$li_temp][3]="<input name=txtcueret".$li_temp." type=text id=txtcueret".$li_temp." class=sin-borde value='".$ls_cuenta."' style= text-align:center size=23 readonly>";
			$la_objectretenciones[$li_temp][4]="<input name=txtdedret".$li_temp." type=text id=txtdedret".$li_temp." class=sin-borde value='".$ls_deduccion."' style= text-align:center size=20 readonly>";
			$la_objectretenciones[$li_temp][5]="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=javascript:ue_removerretenciones(".$li_temp.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
		}
	}
	$la_objectretenciones[$li_filasretenciones][1]="<input name=txtcodret".$li_filasretenciones." type=text id=txtcodret".$li_filasretenciones." class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectretenciones[$li_filasretenciones][2]="<input name=txtdesret".$li_filasretenciones." type=text id=txtdesret".$li_filasretenciones." class=sin-borde style= text-align:left size=35 readonly>";
	$la_objectretenciones[$li_filasretenciones][3]="<input name=txtcueret".$li_filasretenciones." type=text id=txtcueret".$li_filasretenciones." class=sin-borde  style= text-align:center size=23 readonly>";
	$la_objectretenciones[$li_filasretenciones][4]="<input name=txtdedret".$li_filasretenciones." type=text id=txtdedret".$li_filasretenciones." class=sin-borde  style= text-align:center size=20 readonly>";
	$la_objectretenciones[$li_filasretenciones][5]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
}
elseif($ls_operacion=="ue_cargarcondiciones")
{
	$li_filascondiciones=$li_filascondiciones+1;
	for($li_i=1;$li_i<$li_filascondiciones;$li_i++)
	{		
		$ls_moncon=$_POST["txtmoncon".$li_i];
		$ls_porcentaje=$_POST["txtporcon".$li_i];	
		$ls_fecha=$_POST["txtfeccon".$li_i];	
		$la_objectcondiciones[$li_i][1]="<input name=txtnumcon".$li_i."  type=text id=txtnumcon".$li_i."  class=sin-borde style= text-align:center value='".$li_i."' size=5 readonly>";
		$la_objectcondiciones[$li_i][2]="<input name=txtfeccon".$li_i."  type=text id=txtfeccon".$li_i."  class=sin-borde style= text-align:center value='".$ls_fecha."' size=10 readonly>";
		$la_objectcondiciones[$li_i][3]="<input name=txtmoncon".$li_i."  type=text id=txtmoncon".$li_i."  class=sin-borde style= text-align:center value='".$ls_moncon."' size=30 readonly>";
		$la_objectcondiciones[$li_i][4]="<input name=txtporcon".$li_i."  type=text id=txtporcon".$li_i."  class=sin-borde style= text-align:center value='".$ls_porcentaje."'  size=11 readonly>";
		$la_objectcondiciones[$li_i][5]="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=javascript:ue_removercondiciones(".$li_i.");>&nbsp;&nbsp;<img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
	}	
	$la_objectcondiciones[$li_filascondiciones][1]="<input name=txtnumcon".$li_filascondiciones."  type=text id=txtnumcon".$li_filascondiciones."  class=sin-borde style= text-align:center  size=5 readonly>";
	$la_objectcondiciones[$li_filascondiciones][2]="<input name=txtfeccon".$li_filascondiciones."  type=text id=txtfeccon".$li_filascondiciones."  class=sin-borde style= text-align:center  size=10 readonly>";
	$la_objectcondiciones[$li_filascondiciones][3]="<input name=txtmoncon".$li_filascondiciones."  type=text id=txtmoncon".$li_filascondiciones."  class=sin-borde style= text-align:center  size=30 readonly>";
	$la_objectcondiciones[$li_filascondiciones][4]="<input name=txtporcon".$li_filascondiciones."  type=text id=txtporcon".$li_filascondiciones."  class=sin-borde style= text-align:center  size=11 readonly>";
	$la_objectcondiciones[$li_filascondiciones][5]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
}
elseif($ls_operacion=="ue_removercondiciones")
{
	$li_filascondiciones=$li_filascondiciones-1;
	$li_removercondiciones=$_POST["hidremovercondiciones"];
	$li_temp=0;
	for($li_i=1;$li_i<=$li_filascondiciones;$li_i++)
	{
		if($li_i!=$li_removercondiciones)
		{		
			$li_temp=$li_temp+1;
			$ls_numero=$_POST["txtnumcon".$li_i];
			$ls_monto=$_POST["txtmoncon".$li_i];
			$ls_porcentaje=$_POST["txtporcon".$li_i];
			$ls_fecha=$_POST["txtfeccon".$li_i];
			$la_objectcondiciones[$li_temp][1]="<input name=txtnumcon".$li_temp."  type=text id=txtnumcon".$li_temp."  class=sin-borde style= text-align:center value='".$li_temp."' size=5 readonly>";
			$la_objectcondiciones[$li_temp][2]="<input name=txtfeccon".$li_temp."  type=text id=txtfeccon".$li_temp."  class=sin-borde style= text-align:center value='".$ls_fecha."' size=10 readonly>";
			$la_objectcondiciones[$li_temp][3]="<input name=txtmoncon".$li_temp."  type=text id=txtmoncon".$li_temp."  class=sin-borde style= text-align:center value='".$ls_monto."' size=30 maxlength=21 >";
			$la_objectcondiciones[$li_temp][4]="<input name=txtporcon".$li_temp."  type=text id=txtporcon".$li_temp."  class=sin-borde style= text-align:center value='".$ls_porcentaje."' size=11 >";
			$la_objectcondiciones[$li_temp][5]="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=javascript:ue_removercondiciones(".$li_i.");>&nbsp;&nbsp;<img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
		}		
	}
	$la_objectcondiciones[$li_filascondiciones][1]="<input name=txtnumcon".$li_filascondiciones."  type=text id=txtnumcon".$li_filascondiciones."  class=sin-borde style= text-align:center  size=5 readonly>";
	$la_objectcondiciones[$li_filascondiciones][2]="<input name=txtfeccon".$li_filascondiciones."  type=text id=txtfeccon".$li_filascondiciones."  class=sin-borde style= text-align:center  size=10 readonly>";
	$la_objectcondiciones[$li_filascondiciones][3]="<input name=txtmoncon".$li_filascondiciones."  type=text id=txtmoncon".$li_filascondiciones."  class=sin-borde style= text-align:center  size=30 readonly>";
	$la_objectcondiciones[$li_filascondiciones][4]="<input name=txtporcon".$li_filascondiciones."  type=text id=txtporcon".$li_filascondiciones."  class=sin-borde style= text-align:center  size=11 readonly>";
	$la_objectcondiciones[$li_filascondiciones][5]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
	
}elseif($ls_operacion=="ue_cargargarantias")
{
	$li_filasgarantias=$li_filasgarantias+1;
	for($li_i=1;$li_i<$li_filasgarantias;$li_i++)
		{		
			$ls_descripcion=$_POST["txtdesgar".$li_i];
			$la_objectgarantias[$li_i][1]="<input name=txtnumgar".$li_i."  type=text id=txtnumgar".$li_i."  class=sin-borde value='".$li_i."' style= text-align:center size=5 readonly>";
			$la_objectgarantias[$li_i][2]="<input name=txtdesgar".$li_i."  type=text id=txtdesgar".$li_i."  class=sin-borde value='".$ls_descripcion."' style= text-align:left size=90 >";
			$la_objectgarantias[$li_i][3]="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=javascript:ue_removergarantias(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
		}	
		$la_objectgarantias[$li_filasgarantias][1]="<input name=txtnumgar".$li_filasgarantias."  type=text id=txtnumgar".$li_filasgarantias."  class=sin-borde  style= text-align:center size=5 readonly>";
		$la_objectgarantias[$li_filasgarantias][2]="<input name=txtdesgar".$li_filasgarantias."  type=text id=txtdesgar".$li_filasgarantias."  class=sin-borde  style= text-align:left size=90 readonly >";
		$la_objectgarantias[$li_filasgarantias][3]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
}

elseif($ls_operacion=="ue_removergarantias")
{
	$li_filasgarantias=$li_filasgarantias-1;
	$li_removergarantias=$_POST["hidremovergarantias"];
	$li_temp=0;
	for($li_i=1;$li_i<=$li_filasgarantias;$li_i++)
	{
		if($li_i!=$li_removergarantias)
		{		
			$li_temp=$li_temp+1;
			$ls_descripcion=$_POST["txtdesgar".$li_temp];
			$la_objectgarantias[$li_temp][1]="<input name=txtnumgar".$li_temp."  type=text id=txtnumgar".$li_temp."  class=sin-borde value='".$li_temp."' style= text-align:center size=5 readonly>";
			$la_objectgarantias[$li_temp][2]="<input name=txtdesgar".$li_temp."  type=text id=txtdesgar".$li_temp."  class=sin-borde value='".$ls_descripcion."' style= text-align:left size=90 >";
			$la_objectgarantias[$li_temp][3]="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=javascript:ue_removergarantias(".$li_temp.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
		}		
	}
	$la_objectgarantias[$li_filasgarantias][1]="<input name=txtnumgar".$li_filasgarantias."  type=text id=txtnumgar".$li_filasgarantias."  class=sin-borde  style= text-align:center size=5 readonly>";
	$la_objectgarantias[$li_filasgarantias][2]="<input name=txtdesgar".$li_filasgarantias."  type=text id=txtdesgar".$li_filasgarantias."  class=sin-borde  style= text-align:left size=90 readonly>";
	$la_objectgarantias[$li_filasgarantias][3]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	
}
elseif($ls_operacion=="ue_cargarasignacion")
{
	$lb_valido=$io_contrato->uf_select_asignacion($ls_codasi,$la_asignacion);
	if ($lb_valido)
	{
		$io_datastore->data=$la_asignacion;
		$li_i=1;
		$ls_puncueasi=$io_datastore->getValue("puncueasi",$li_i);
		$ls_fecasi=$io_function->uf_convertirfecmostrar($io_datastore->getValue("fecasi",$li_i));
		$ls_contasi=$io_datastore->getValue("contasi",$li_i);
		$ls_monasi=$io_datastore->getValue("monparasi",$li_i);
		$ls_monasi=$io_funnum->uf_convertir_numerocadena($ls_monasi);
		$ls_codobr=$io_datastore->getValue("codobr",$li_i);
		$ls_desobr=$io_datastore->getValue("desobr",$li_i);
		$ls_estobr=$io_datastore->getValue("desest",$li_i);
		$ls_munobr=$io_datastore->getValue("denmun",$li_i);
		$ls_parobr=$io_datastore->getValue("denpar",$li_i);
		$ls_comobr=$io_datastore->getValue("nomcom",$li_i);
		$ls_dirobr=$io_datastore->getValue("dirobr",$li_i);
		$ls_montotasi=$io_datastore->getValue("montotasi",$li_i);
		$ls_montotasi=$io_funnum->uf_convertir_numerocadena($ls_montotasi);
		$ls_validocargo=$io_contrato->uf_select_cargoasignacion($ls_codasi,$la_cargos);
		if($ls_validocargo)
		{
			$io_datastore->data=$la_cargos;
			$li_totalcargos=$io_datastore->getRowCount("dencar");
			$ls_cadenacargos="";
			for ($li_i=1;$li_i<=$li_totalcargos;$li_i++)
			{
				if ($ls_cadenacargos=="")
				{
					$ls_cadenacargos=$io_datastore->getValue("dencar",$li_i);
				}
				else
				{
					$ls_cadenacargos=$ls_cadenacargos.", ".$io_datastore->getValue("dencar",$li_i);
				}					
			}
			$ls_carasi=$ls_cadenacargos;			
		}		
	}	
}
elseif($ls_operacion=="ue_guardar")
{
	$ls_feccon=$io_function->uf_convertirdatetobd($ls_feccon);
	$ls_fecinicon=$io_function->uf_convertirdatetobd($ls_fecinicon);
	$ls_fecfincon=$io_function->uf_convertirdatetobd($ls_fecfincon);
	$ls_estcon=1;
	$ls_hidstatus=$_POST["hidstatus"];
	$lb_existe=$io_contrato->uf_select_contrato($ls_codcon,$la_data);
	if($ls_hidstatus!="C")
	{
		if($ls_lapmulcon=="" || $ls_lapmulcon=="0")
			$ls_coduniretraso="---";
		if($ls_lapgarcon=="" || $ls_lapgarcon=="0")
			$ls_codunigarantia="---";
		$ls_precon=$_POST["hidprefijo"];
		$io_contrato->io_sql->begin_transaction();
		$ls_codconaux=$ls_codcon;
		$lb_valido = $io_contrato->uf_guardar_contrato(&$ls_codcon ,$ls_codasi,$ls_monto,$ls_feccon,$ls_fecinicon,$li_placon,
									$ls_coduniduracion,$ls_mulcon,$ls_lapmulcon,$ls_coduniretraso,$ls_lapgarcon,$ls_codunigarantia,
									$ls_codtco ,$ls_monmaxcon,$ls_pormaxcon,$ls_estcon,$ls_obscon,$ls_fecfincon,$ls_hidprefijo,$la_seguridad);    
									
		if($lb_valido)
		{		
			$lb_valido=$io_asignacion->uf_update_estado($ls_codasi,4,$la_seguridad);
			for($li_i=1;$li_i<$li_filasretenciones;$li_i++)
			{
				$ls_codded=$_POST["txtcodret".$li_i];
				$lb_validoretenciones=$io_contrato->uf_guardar_retenciones($ls_codcon,$ls_codded,$la_seguridad);
				if (!$lb_validoretenciones)
				{
					print "Hubo un error al intentar insertar la retencion $li_i";			          
					$lb_valido=false;
					break;
				}
					
			}
			if($lb_valido)
			{		
				$lb_estadocontrato=$io_contrato->uf_update_estado($ls_codcon,1,$la_seguridad);
				if($ls_codconaux!=$ls_codcon)
				{
					$io_msg->message("Se le asigno un nuevo numero ".$ls_codcon."");
				}
				$io_mensaje->incluir();
			}	
			else
			{
				if(!$lb_validoretenciones)
					$io_msg->message("Error en retenciones!!!");
				if(!$lb_validocondiciones)
					$io_msg->message("Error en condiciones!!!");
				if(!$lb_validogarantias)
					$io_msg->message("Error en garantias!!!");		
					
			}			
		}
		else
		{
				$io_mensaje->error_incluir();
		}
		if($lb_valido)
		{
			$io_contrato->io_sql->commit();
		}
		else
		{
			$io_contrato->io_sql->rollback();
		}
		$ls_imprimir=$_POST["hidimprimir"];
		if($ls_imprimir=="IMPRIMIR")
		{
			 $ls_documento="CONTRATO";
			  $ls_pagina="sigesp_sob_d_filechooser.php?codcon=".$ls_codcon."&documento=".$ls_documento;
			  print "<script language=JavaScript>";
			  print "popupWin('".$ls_pagina."','ventana');";
			  print "</script>";
		}
	}//end del if si existe
	else
	{
		if($lb_existe)
		{
			$lb_valido=$io_contrato->uf_select_estado($ls_codcon,$li_estado);
			$li_estadocontabilizacion=$io_obra->uf_contabilizado("SELECT estspgscg FROM sob_contrato WHERE codemp='$ls_codemp' AND codcon='$ls_codcon'");
			if($lb_valido)
			{
				$io_contrato->io_sql->begin_transaction();
				if($li_estado==1 && $li_estadocontabilizacion==0)
				{
					if($la_data["codasi"][1]!=$ls_codasi)
					{
						$lb_valido=$io_asignacion->uf_update_estado($ls_codasi,4,$la_seguridad);
						if($lb_valido)
						{
							$lb_valido=$io_asignacion->uf_update_estado($la_data["codasi"][1],5,$la_seguridad);
							if(!$lb_valido)
							{
								$lb_valido=$io_asignacion->uf_update_estado($ls_codasi,5,$la_seguridad);
								$io_msg->message("El campo Asignacion no fue actualizado");
							}						
						}
						else
						{
							$io_msg->message("El campo Asignacion no fue actualizado");
						}
					}
					
					if($ls_lapmulcon=="" || $ls_lapmulcon=="0")
						$ls_coduniretraso="---";
					if($ls_lapgarcon=="" || $ls_lapgarcon=="0")
						$ls_codunigarantia="---";				
					$lb_valido=$io_contrato->uf_update_contrato($ls_codcon ,$ls_codasi,$ls_monto,$ls_feccon,$ls_fecinicon,$li_placon,
										$ls_coduniduracion,$ls_mulcon,$ls_lapmulcon,$ls_coduniretraso,$ls_lapgarcon,$ls_codunigarantia,
										$ls_codtco ,$ls_monmaxcon,$ls_pormaxcon,$ls_estcon,$ls_obscon,$ls_fecfincon,$ls_hidprefijo,$la_seguridad);
					if($lb_valido)
					{
						$la_retenciones["codret"][1]="";
						for($li_i=1;$li_i<$li_filasretenciones;$li_i++)
						{
							$la_retenciones["codret"][$li_i]=$_POST["txtcodret".$li_i];													
						}
						$lb_valido=$io_contrato->uf_update_retenciones($ls_codcon,$la_retenciones,$li_filasretenciones,$la_seguridad);
					}//fin del if del valido si se modifico exitosamente la cabecera
					if($lb_valido===true)
					{
						$io_mensaje->modificar();
					}
				}
				elseif($li_estado==6 && $li_estadocontabilizacion==0)
					{
					if($la_data["codasi"][1]!=$ls_codasi)
					{
						$lb_valido=$io_asignacion->uf_update_estado($ls_codasi,4,$la_seguridad);
						if($lb_valido)
						{
							$lb_valido=$io_asignacion->uf_update_estado($la_data["codasi"][1],5,$la_seguridad);
							if(!$lb_valido)
							{
								$lb_valido=$io_asignacion->uf_update_estado($ls_codasi,5,$la_seguridad);
								$io_msg->message("El campo Asignacion no fue actualizado");
							}						
						}
						else
						{
							$io_msg->message("El campo Asignacion no fue actualizado");
						}
					}
					
					if($ls_lapmulcon=="" || $ls_lapmulcon=="0")
						$ls_coduniretraso="---";
					if($ls_lapgarcon=="" || $ls_lapgarcon=="0")
						$ls_codunigarantia="---";				
					$lb_valido=$io_contrato->uf_update_contrato($ls_codcon ,$ls_codasi,$ls_monto,$ls_feccon,$ls_fecinicon,$li_placon,
										$ls_coduniduracion,$ls_mulcon,$ls_lapmulcon,$ls_coduniretraso,$ls_lapgarcon,$ls_codunigarantia,
										$ls_codtco ,$ls_monmaxcon,$ls_pormaxcon,$ls_estcon,$ls_obscon,$ls_fecfincon,$ls_hidprefijo,$la_seguridad);
					if($lb_valido)
					{
						$la_retenciones["codret"][1]="";
						for($li_i=1;$li_i<$li_filasretenciones;$li_i++)
						{
							$la_retenciones["codret"][$li_i]=$_POST["txtcodret".$li_i];													
						}
						$lb_validoretenciones=$io_contrato->uf_update_retenciones($ls_codcon,$la_retenciones,$li_filasretenciones,$la_seguridad);
					}//fin del if del valido si se modifico exitosamente la cabecera
					if($lb_valido===true)
					{
						$io_mensaje->modificar();
					}
					else
					{
						if($lb_validocondiciones || $lb_validoretenciones || $lb_validogarantias)
							$io_mensaje->modificar();
					}				
				}
				else
				{
					if($li_estadocontabilizacion==0)
					{
						$ls_estado=$io_funnum->uf_convertir_numeroestado($li_estado);
						$io_msg->message("Este Contrato no puede ser modificado, su estado es ".$ls_estado);
					}
					else
					{
						$io_msg->message("Este Contrato no puede ser modificado, ya esta Contabilizado");
					}
				}
				if($lb_valido)
				{
					$io_contrato->io_sql->commit();
				}
				else
				{	
					$io_msg->message("Se produjo un error al procesar la solicitud");
					$io_contrato->io_sql->rollback();
				}
			}
			else
			{
				$io_msg->message("El Contrato no existe");
			}
		}
	}
	$ls_datosasignacion="OCULTAR";
	$ls_datosobra="OCULTAR";
	$ls_datoscontrato="OCULTAR";
	$ls_puncueasi="";
	$ls_fecasi="";
	$ls_contasi="";
	$ls_inspasi="";
	$ls_monasi="";
	$ls_codobr="";
	$ls_desobr="";
	$ls_estobr="";
	$ls_munobr="";
	$ls_parobr="";
	$ls_hidprefijo="";
	$ls_comobr="";
	$ls_dirobr="";
	$ls_codasi="";
	$ls_codcon="";
	$ls_estado="";
	$ls_fecfincon="";
	$ls_feccon ="";
	$ls_destipcon ="";
	$ls_fecinicon ="";
	$li_placon="";
	$ls_monto="0,00";
	$ls_monmaxcon="0,00";
	$ls_pormaxcon="0,00";
	$ls_mulcon="0,00";
	$ls_lapgarcon="";
	$ls_obscon="";
	$ls_estcon="";
	$ls_hidstatus="";
	$ls_porejefiscon="";
	$ls_porejefincon="";
	$ls_monejefincon="";
	$li_filascondiciones=1;
	$li_filasgarantias=1;
	$li_filasretenciones=1;
	$li_removerretenciones="";
	$li_removercondiciones="";
	$li_removergarantias="";	
	$ls_lapmulcon="";
	$ls_operacion="";
	$li_placon="";
	$ls_codunigarantia="";
	$ls_coduniduracion="";
	$ls_coduniretraso="";
	$ls_codtco="";
	$ls_carasi="";
	$ls_montotasi="";
	$li_hidfilasretenciones=1;
	$la_objectretenciones[1][1]="<input name=txtcodret1 type=text id=txtcodret1 class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectretenciones[1][2]="<input name=txtdesret1 type=text id=txtdesret1 class=sin-borde style= text-align:left size=35 readonly>";
	$la_objectretenciones[1][3]="<input name=txtcueret1 type=text id=txtcueret1 class=sin-borde style= text-align:center size=23 readonly>";
	$la_objectretenciones[1][4]="<input name=txtdedret1 type=text id=txtdedret1 class=sin-borde style= text-align:center size=20 readonly>";
	$la_objectretenciones[1][5]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
}//end del elseif guardar
elseif($ls_operacion=="ue_cargarcontrato")
{
	$ls_codtco=$_POST["hidtipocontrato"];
	$lb_validoretenciones=$io_contrato->uf_select_retenciones ($ls_codcon,$la_data,$filas);
	if($lb_validoretenciones)
	{
		$li_filasretenciones=$filas+1;
	}
	else
	{
		$li_filasretenciones=1;
	}
	for($li_i=1;$li_i<$li_filasretenciones;$li_i++)
	{
		$ls_codigo=$la_data["codded"][$li_i];
		$ls_descripcion=$la_data["dended"][$li_i];
		$ls_cuenta=$la_data["cuenta"][$li_i];
		$ls_deduccion=$io_funnum->uf_convertir_numerocadena($la_data["deducible"][$li_i]);
		$la_objectretenciones[$li_i][1]="<input name=txtcodret".$li_i." type=text id=txtcodret".$li_i." class=sin-borde value='".$ls_codigo."' style= text-align:center size=5 readonly>";
		$la_objectretenciones[$li_i][2]="<input name=txtdesret".$li_i." type=text id=txtdesret".$li_i." class=sin-borde value='".$ls_descripcion."' style= text-align:left size=35 readonly>";
		$la_objectretenciones[$li_i][3]="<input name=txtcueret".$li_i." type=text id=txtcueret".$li_i." class=sin-borde value='".$ls_cuenta."' style= text-align:center size=23 readonly>";
		$la_objectretenciones[$li_i][4]="<input name=txtdedret".$li_i." type=text id=txtdedret".$li_i." class=sin-borde value='".$ls_deduccion."' style= text-align:center size=20 readonly>";
		$la_objectretenciones[$li_i][5]="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=javascript:ue_removerretenciones(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
	}	
	$la_objectretenciones[$li_filasretenciones][1]="<input name=txtcodret".$li_filasretenciones." type=text id=txtcodret".$li_filasretenciones." class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectretenciones[$li_filasretenciones][2]="<input name=txtdesret".$li_filasretenciones." type=text id=txtdesret".$li_filasretenciones." class=sin-borde style= text-align:left size=35 readonly>";
	$la_objectretenciones[$li_filasretenciones][3]="<input name=txtcueret".$li_filasretenciones." type=text id=txtcueret".$li_filasretenciones." class=sin-borde  style= text-align:center size=23 readonly>";
	$la_objectretenciones[$li_filasretenciones][4]="<input name=txtdedret".$li_filasretenciones." type=text id=txtdedret".$li_filasretenciones." class=sin-borde  style= text-align:center size=20 readonly>";
	$la_objectretenciones[$li_filasretenciones][5]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	
}
elseif($ls_operacion=="ue_eliminar")///Esto es una eliminacion lógica!
{
	$lb_existe=$io_contrato->uf_select_contrato($ls_codcon,$la_data);	
	if ($lb_existe)
	{
		$io_contrato->io_sql->begin_transaction();
		$lb_valido=$io_asignacion->uf_update_estado($ls_codasi,5,$la_seguridad);
		$lb_valido=$io_contrato->uf_select_estado($ls_codcon,$li_estado);
		$li_estadocontabilizacion=$io_obra->uf_contabilizado("SELECT estspgscg FROM sob_contrato WHERE codemp='0001' AND codcon='000001'");
		if($lb_valido)
		{
			if($li_estado==1 && $li_estadocontabilizacion==0)
			{
				$lb_valido=$io_contrato->uf_update_estado($ls_codcon,3,$la_seguridad);
				if($lb_valido)
				{
					$io_mensaje->anular();
					$io_contrato->io_sql->commit();
				}
				else
				{
					$io_msg->message("Ocurrio un error al Anular este contrato");
					$io_contrato->io_sql->rollback();
				}
			}
			else
			{
				if($li_estadocontabilizacion==0)
				{
					$ls_estado=$io_funnum->uf_convertir_numeroestado($li_estado);
					if ($ls_estado=="ANULADO")
						$io_msg->message("Este Contrato ya está Anulado!!!");
					else
						$io_msg->message("Este Contrato no puede ser Anulado, su estado es ".$ls_estado);
				}
				else
				{
					$io_msg->message("Este Contrato no puede ser Anulado, ya esta Contabilizado");
				}
			}
		} 
	}
	else
	{
		$io_msg->message("Debe seleccionar un Contrato existente!!!");
	}
	$ls_datosasignacion="OCULTAR";
	$ls_datosobra="OCULTAR";
	$ls_datoscontrato="OCULTAR";
	$ls_puncueasi="";
	$ls_fecasi="";
	$ls_contasi="";
	$ls_inspasi="";
	$ls_monasi="";
	$ls_codobr="";
	$ls_desobr="";
	$ls_estobr="";
	$ls_munobr="";
	$ls_hidprefijo="";
	$ls_parobr="";
	$ls_comobr="";
	$ls_dirobr="";
	$ls_fecfincon="";
	$ls_codasi="";
	$ls_codcon="";
	$ls_feccon ="";
	$ls_estado="";
	$ls_destipcon ="";
	$ls_fecinicon ="";
	$li_placon="";
	$ls_monto="0,00";
	$ls_monmaxcon="0,00";
	$ls_pormaxcon="0,00";
	$ls_mulcon="0,00";
	$ls_lapgarcon="";
	$ls_obscon="";
	$ls_estcon="";
	$ls_porejefiscon="";
	$ls_porejefincon="";
	$ls_monejefincon="";
	$li_filascondiciones=1;
	$li_filasgarantias=1;
	$li_filasretenciones=1;
	$li_removerretenciones="";
	$li_removercondiciones="";
	$li_removergarantias="";	
	$ls_lapmulcon="";
	$ls_operacion="";
	$li_placon="";
	$ls_codunigarantia="";
	$ls_coduniduracion="";
	$ls_coduniretraso="";
	$ls_codtco="";
	$ls_carasi="";
	$ls_montotasi="";
	$ls_hidstatus="";
	$li_hidfilasretenciones=1;
	$la_objectretenciones[1][1]="<input name=txtcodret1 type=text id=txtcodret1 class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectretenciones[1][2]="<input name=txtdesret1 type=text id=txtdesret1 class=sin-borde style= text-align:left size=35 readonly>";
	$la_objectretenciones[1][3]="<input name=txtcueret1 type=text id=txtcueret1 class=sin-borde style= text-align:center size=23 readonly>";
	$la_objectretenciones[1][4]="<input name=txtdedret1 type=text id=txtdedret1 class=sin-borde style= text-align:center size=20 readonly>";
	$la_objectretenciones[1][5]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
}
elseif($ls_operacion=="ue_cargar_estadocontrato")
{
	$lb_existe=$io_contrato->uf_select_contrato($ls_codcon,$la_data);
	if ($lb_existe)
	{
		$li_filas=0;
		$io_contrato->uf_select_estadoactual ($ls_codcon,$la_estadocontrato,$li_filas);
		$ls_estado=$io_funnum->uf_convertir_numeroestado($la_estadocontrato["estcon"][1]);
		$ls_porejefiscon=$io_funnum->uf_convertir_numerocadena($la_estadocontrato["porejefiscon"][1]);
		$ls_porejefincon=$io_funnum->uf_convertir_numerocadena($la_estadocontrato["porejefincon"][1]);
		$ls_monejefincon=$io_funnum->uf_convertir_numerocadena($la_estadocontrato["monejefincon"][1]);
	}
	else
	{
		$io_msg->message("Debe seleccionar un Contrato Existente!!!");
		$ls_datoscontrato="OCULTAR";
	}
	
}
?>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno" id="tabla">
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
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img name="imgnuevo" id="imgnuevo" src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a><a href="javascript:ue_imprimir();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0"></a><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>
  <p>&nbsp;
  </p>
  <form name="form1" method="post" action="" >
  <?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_sob->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_sob);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	

  <table width="743" border="0" align="center" cellpadding="0" cellspacing="0"  class="contorno">
      <tr class="formato-blanco">
        <td>&nbsp;</td>
        <td colspan="9" class="titulo-celdanew">Datos de la Asignaci&oacute;n </td>
        <td>&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td width="115">&nbsp;</td>
        <td width="122">&nbsp;</td>
        <td colspan="2">&nbsp;</td>
        <td width="63">&nbsp;</td>
        <td width="43">&nbsp;</td>
        <td width="32">&nbsp;</td>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td width="14" height="22"><div align="right"></div></td>
        <td width="33"><div align="right">C&oacute;digo</div></td>
        <td colspan="2">		          <input name="txtcodasi" type="text" id="txtcodasi" style="text-align:center " value="<?php print $ls_codasi ?>" size="8" maxlength="8"  readonly="true">
        <input name="operacion" type="hidden" id="operacion">
		 <input name="hidstatus" type="hidden" id="hidstatus" value="<?php print $ls_hidstatus; ?>">
        <a href="javascript:ue_catasignacion();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a> <input name="hidfecasi" type="hidden" id="hidfecasi" value="<?php print $ls_fecasiaux?>"></td>
        <td colspan="2">&nbsp;</td>
        <td colspan="4"><div align="right"><a href="javascript:uf_mostrar_ocultar_asignacion();">&nbsp;&nbsp;<img src="../shared/imagebank/mas.gif" width="9" height="17" border="0">Datos de la Asignaci&oacute;n </a></div></td>
        <td width="23">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td height="13"><div align="right"></div></td>
        <td height="13" colspan="3"></td>
        <td colspan="6"><div align="right"></div></td>
        <td>&nbsp;</td>
      </tr>
      
		<?Php
			if ($ls_datosasignacion=="MOSTRAR")
			{
			?>		
				<tr class="formato-blanco">
				  <td height="79" class="sin-borde">&nbsp;</td>
				  <td height="79" colspan="9" align="center" valign="top" class="sin-borde">				  <table width="498" height="182" border="0" cellpadding="1"  cellspacing="1" class="letras-peque&ntilde;as" id="datosasignacion" >
                    <tr>
                      <td width="121" height="20" ><div align="right">Punto de Cuenta N&ordm;</div></td>
                      <td width="10" >&nbsp;</td>
                      <td width="371">
<input name="txtpuncueasi" type="text" id="txtpuncueasi" readonly="true" value="<?php print $ls_puncueasi?>" size="6" maxlength="6">                        
<div align="right"></div></td>
                    </tr>
                    <tr>
                      <td height="20"><div align="right">Fecha de Asignaci&oacute;n</div></td>
                      <td height="20">&nbsp;</td>
                      <td width="371"><input name="txtfecasi"   type="text" id="fecasi" value="<?php print $ls_fecasi?>" size="11" maxlength="11" readonly="true"></td>
                    </tr>
                    <tr>
                      <td height="20"><div align="right">Empresa Contratista</div></td>
                      <td height="20">&nbsp;</td>
                      <td height="20">
                        <input name="txtcontasi" id="txtcontasi3" value="<?php print $ls_contasi?>" size="70" maxlength="254" readonly="true"></td>
                    </tr>
                    <tr>
                      <td height="20" valign="top" class="navigation"><div align="right">Monto Parcial</div></td>
                      <td height="20" valign="top" class="navigation">&nbsp;</td>
                      <td height="20" valign="top"><input name="txtmonasi" type="text"  readonly="true" id="txtmonasi3" value="<?php print $ls_monasi?>" size="21" maxlength="21"></td>
                    </tr>
                    <tr>
                      <td height="20" valign="top" class="navigation"><div align="right">Cargos Asociados</div></td>
                      <td height="20" valign="top" class="navigation">&nbsp;</td>
                      <td height="20" valign="top"><input name="txtcarasi" type="text" id="txtcarasi" value="<?php print $ls_carasi?>" size="70" maxlength="254" readonly="true"></td>
                    </tr>
                    <tr>
                      <td height="20" valign="top" class="navigation"><div align="right">Monto Total</div></td>
                      <td height="20" valign="top" class="navigation">&nbsp;</td>
                      <td height="20" valign="top"><input name="txtmontotasi" type="text" readonly="true" id="txtmontotasi" value="<?php print $ls_montotasi?>" size="21" maxlength="21"></td>
                    </tr>
                    <tr>
                      <td height="20" valign="top" class="navigation"><div align="right">C&oacute;digo de la Obra </div></td>
                      <td height="20" valign="top" class="navigation">&nbsp;</td>
                      <td height="20" valign="top"><input name="txtcodobr" type="text" id="txtcodobr"   value="<?php print $ls_codobr?>" size="6" maxlength="6" readonly="true"></td>
                    </tr>
                    <tr>
                      <td height="33" valign="top" class="navigation"><div align="right">Descripci&oacute;n</div></td>
                      <td height="33" valign="top" class="navigation">&nbsp;</td>
                      <td height="33" valign="top"><textarea name="txtdesobr" cols="67" rows="1" readonly="true" id="txtdesobr"><?php print $ls_desobr?></textarea></td>
                    </tr>
                  </table></td>
				  <td height="79" class="sin-borde">&nbsp;</td>
    			</tr>
			<?Php
			}
			else
			{
			?>
			<?Php
			}
			?>		
      
	  		<?Php
				if ($ls_datosobra == "MOSTRAR")
				{					
			 ?>
				 <?Php
				 }
				 else
				 {
				 ?>
				 	<tr class="formato-blanco">
					<td height="10" class="sin-borde">&nbsp;</td>
					<td height="10" colspan="9" align="center" valign="top" class="sin-borde">					</td>
					<td height="10" class="sin-borde">&nbsp;</td>
				  	</tr>				 
				 <?Php
				 	}
				 ?> 
				 
		 
		 
	  <tr class="formato-blanco">
        <td height="13" colspan="11" class="titulo-celdanew">Datos del Contrato</td>
      </tr>	  
      <tr class="formato-blanco">
        <td height="39">&nbsp;</td>
        <td height="39"><div align="right"></div></td>
        <td height="39" align="left" valign="middle" id="tbcombo" >
		<div align="right">Código </div>
        <td height="39" align="left" valign="middle" id="tbcombo" ><input name="txtcodcon" id="txtcodcon"    readonly="true" style="text-align:center " value="<?php print $ls_codcon?>" type="text" size="12" maxlength="12">        
        <td height="39" align="left" valign="middle" id="tbcombo" >        
        <td height="39" align="left" valign="middle" id="tbcombo" >        
        <td height="39" align="left" valign="middle" id="tbcombo" >        
        <td height="39" align="left" valign="middle" id="tbcombo" >        
        <td height="39"><div align="right">Fecha</div></td>
        <td width="173" height="39"><input name="txtfeccon" type="text" id="txtfeccon"  style="text-align:center" value="<?php print $ls_feccon ?>" size="12" maxlength="10"  onKeyDown="javascript:ue_formatofecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" datepicker="true" ></td>
        <td height="39">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td height="26">&nbsp;</td>
        <td height="26">&nbsp;</td>
        <td height="26" colspan="2" align="right" valign="middle">                  Tipo de Contrato
          <?Php
				//if($ls_codobr=="")
					//$lb_valido=false;
				//else
					$lb_valido=$io_contrato->uf_llenarcombo_tipocontrato($la_datos);
					
				if($lb_valido)
				{
					$io_datastore->data=$la_datos;
					$li_totalfilas=$io_datastore->getRowCount("codtco");					
				}
				
				?>
          <select name="cmbtipocontrato" size="1" id="select"  >
            <option value="s1" >Seleccione</option>
            <?Php
					for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
					{
					 $ls_codigo=$io_datastore->getValue("codtco",$li_i);
					 $ls_descripcion=$io_datastore->getValue("nomtco",$li_i); 
					 $ls_descripcioncontrato=$io_datastore->getValue("nomtco",$li_i);
					 
				
					 if ($ls_codigo==$ls_codtco)
					 {
						  print "<option value='$ls_codigo' selected >$ls_codigo</option>";
					 }
					 else
					 {
						  print "<option value='$ls_codigo'>$ls_codigo</option>";
					 }
					} 
	           ?>
          </select></td>
        <td height="26" colspan="6" align="left" valign="middle"><input name="hidtipocontrato" type="hidden" id="hidtipocontrato3" value="<?php print $ls_codtco ?>">
&nbsp;&nbsp;&nbsp;
<input name="txtdestipcon" type="text" class="sin-borde" value="<?php print $ls_destipcon ?>" readonly="true"></td>
        <td height="26">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td height="26" rowspan="2">&nbsp;</td>
        <td height="26" rowspan="2">&nbsp;</td>
        <td height="24" colspan="2" align="right">Fecha de Inicio     <input name="txtfecinicon"   type="text" id="txtfecinicon"  style="text-align:left" value="<?php print $ls_fecinicon ?>" size="11" maxlength="10"    onBlur="DiferenciaFechas();" readonly="true" datepicker="true"></td>
        <td height="26" colspan="2" rowspan="2"> <div align="right">Duraci&oacute;n (Nro. Dias) </div></td>
        <td height="26" rowspan="2"><input name="txtplacon" type="text" id="txtplacon"  onKeyPress="return validaCajas(this,'i',event)"  onBlur="javascript: ue_calcularperiodo(this);" style="text-align:right" value="<?php print $li_placon ?>" size="6" maxlength="6" readonly="true"  ></td>
        <td height="26" colspan="4" rowspan="2">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td height="13" colspan="2" align="right">Fecha de Finaliz.         <input name="txtfecfincon" type="text" id="txtfecfincon"  style="text-align:left" value="<?php print $ls_fecfincon ?>" size="11" maxlength="10"    onBlur="DiferenciaFechas();" datepicker="true" readonly="true"></td>
      </tr>
      <tr class="formato-blanco">
        <td height="34">&nbsp;</td>
        <td height="34">&nbsp;</td>
        <td height="34" colspan="2" align="right">Monto<input name="txtmonto" type="text"  style="text-align:right" id="txtmonto" readonly="true" size="21" value="<?php print $ls_monto?>" maxlength="21">        </td>
        <td width="16" height="34"><div align="right">Bs.</div></td>
        <td width="107" align="right">Monto L&iacute;mite</td>
        <td height="34" colspan="4"><input name="txtmonmaxcon"  style="text-align:right "type="text" id="txtmonmaxcon" onKeyPress="return(validaCajas(this,'d',event,21)) "  onBlur="javascript:uf_procesarporcentaje(this)" value="<?php print $ls_monmaxcon?>" size="21" maxlength="21" onFocus="javascript:ue_guardarvalor()" >
        Bs.&nbsp;
        <input name="txtpormaxcon" type="text" id="txtpormaxcon" value="<?php print $ls_pormaxcon?>" size="14" style="text-align:right " maxlength="15"  onKeyPress="return(validaCajas(this,'d',event,21)) "    onBlur="javascript:uf_procesarporcentaje(this)" onFocus="javascript:ue_guardarvalor()">
        %</td>
        <td height="34">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td height="61">&nbsp;</td>
        <td height="61">&nbsp;</td>
        <td height="61"><div align="right">Observaci&oacute;n</div></td>
        <td height="61" colspan="7"><textarea name="txtobscon" cols="70" rows="2" id="txtobscon"  onKeyDown="textCounter(this,254)"  onKeyUp="textCounter(this,254)" onKeyPress="return(validaCajas(this,'x',event,254))"><?php print $ls_obscon?></textarea></td>
        <td height="61">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="11"><div align="center">
          <table width="610" border="0" align="center" cellpadding="0" cellspacing="0"  class="sin-borde">
            <tr class="formato-blanco">
              <td width="15" height="13">&nbsp;</td>
              <td width="593"><div align="left"><a href="javascript:ue_catretenciones();"><img src="../shared/imagebank/mas.gif" width="9" height="17" border="0"></a><a href="javascript:ue_catretenciones();">Agregar Detalle</a></div></td>
            </tr>
            <tr align="center" class="formato-blanco">
              <td colspan="2"><?php $io_grid->makegrid($li_filasretenciones,$la_columretenciones,$la_objectretenciones,$li_anchoretenciones,$ls_tituloretenciones,$ls_nametable);?> </td>
            </tr>
			<tr class="formato-blanco">
              <td colspan="2">&nbsp;</td>
            </tr>
          </table>
        </div></td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="11"><table width="609" border="0" align="center" cellpadding="0" cellspacing="0"  class="sin-borde">
          <tr class="formato-blanco">
            <td width="14">&nbsp;</td>
            <td width="403">&nbsp;</td>
            <td width="190">&nbsp;</td>
          </tr>
          <tr class="formato-blanco">
            <td colspan="3"><div align="center"><a href="#inicio">Volver Arriba</a></div></td>
          </tr>
          <tr class="formato-blanco">
            <td colspan="3">&nbsp;</td>
          </tr>
          <tr class="formato-blanco">
            <td colspan="3"><div align="right"></div></td>
          </tr>
        </table></td>
      </tr>
    </table>
  <!-- Los Hidden son colocados a partir de aca-->
<input name="hiddatosasignacion" type="hidden" id="hiddatosasignacion" value="<?php print $ls_datosasignacion;?>">
<input name="hiddatosobra" type="hidden" id="hiddatosobra" value="<?php print $ls_datosobra;?>">
<input name="hiddatoscontrato" type="hidden" id="hiddatoscontrato" value="<?php print $ls_datoscontrato;?>">
<input name="hidfilasretenciones" type="hidden" id="hidfilasretenciones" value="<?php print $li_filasretenciones;?>">
<input name="hidremoverretenciones" type="hidden" id="hidremoverretenciones" value="<?php print $li_removerretenciones;?>">
<input name="monto" id="monto" type="hidden">
<input name="porcentaje" id="porcentaje" type="hidden">
<input type="hidden" name="hidimprimir" id="hidimprimir">
<input type="hidden" name="hidprefijo" id="hidprefijo" value="<?php print $ls_hidprefijo;?>" >
<input type="hidden" name="hidestapr" id="hidprefijo" value="<?php print $ls_estapr;?>" >
<?Php

if($ls_operacion=="ue_imprimir")
{
	
  $lb_valido=$io_contrato->uf_select_contrato($ls_codcon,$la_data);
  if($lb_valido===true)
  {
  	  $ls_documento="CONTRATO";
	  $ls_pagina="sigesp_sob_d_filechooser.php?codcon=".$ls_codcon."&documento=".$ls_documento;
	  print "<script language=JavaScript>";
	  print "popupWin('".$ls_pagina."','ventana',400,200);";
	  print "</script>";
  }
  elseif($lb_valido===0)
  {
		?>		
		<script language="javascript">
			f=document.form1;
			guardar=confirm("El Contrato no ha sido guardado.\n ¿Desea guardarlo ahora?");
			if (guardar)
			{
				f.hidimprimir.value="IMPRIMIR";
				f.operacion.value="ue_guardar";
				ue_agregarprefijo();
				f.hidprefijo.value=combojs.valcon.value;
				f.submit();		
			}		
		</script>	
		<?			
  }
}
?>


<!-- Fin de la declaracion de Hidden-->
  </form>
</body>
<script language="javascript">
//Funciones para llenar los combos/////////////
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
function ue_llenarcmbmunicipio()
{
	f=document.form1;
	f.action="sigesp_sob_d_obra.php";
	f.operacion.value="municipio";
	ue_agregarprefijo();
	f.submit();
}


///////Fin de las funciones para llenar los combos///////////////

///////Funciones para llamar catalogos////////////////
function ue_catretenciones()
{
	f=document.form1;
	estapr=f.hidestapr.value;
	if(estapr==1)
	{
		alert("El contrato esta aprobado y no puede ser modificado");
	}
	else
	{
		if(f.txtcodcon.value!="")
		{
			f.operacion.value="";			
			pagina="sigesp_cat_retenciones.php";
			popupWin(pagina,"catalogo",550,200);
			//window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=200,resizable=yes,location=no,top=0,left=0");
		}
		else
		{
			alert("Debe seleccionar un nuevo Contrato!!");
		}
	}
}

function ue_catasignacion()
{
	f=document.form1;	
	var estado=3;
	pagina="sigesp_cat_asignacion.php?estado="+estado+"&hidorigen=DC";
	popupWin(pagina,"catalogo",850,400);
	//window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=400,resizable=yes,location=no,top=0,left=0");
}

function ue_detcondiciones()
{
	f=document.form1;
	if(f.txtcodcon.value=="")
	{
		alert ("Debe seleccionar un nuevo Contrato!!!");
	}
	else
	{
		if(f.txtfecinicon.value!="")
		{
			f.operacion.value="";	
			monto=f.txtmonto.value;		
			fecha=f.txtfecinicon.value;
			li_filascondiciones=f.hidfilascondiciones.value;
			totalporcentaje=0;
			for (i=1;i<li_filascondiciones;i++)
			{
				totalporcentaje=totalporcentaje+parseFloat(uf_convertir_monto(eval("f.txtporcon"+i+".value")));
			}
			if (totalporcentaje>=100)
				alert("El 100% del monto total ya fue cubierto!!!");
			else
			{
				pagina="sigesp_sob_pdt_condiciones.php?monto="+monto+"&fecha="+fecha+"&porcentaje="+totalporcentaje;
				popupWin(pagina,"catalogo",360,210);
//				window.open(pagina,"catalogo","menubar=no,status=yes,toolbar=no,scrollbars=yes,width=360,height=210,resizable=yes,location=no");
			}
		}
		else
		{
			alert("Debe seleccionar una Fecha de Inicio para el Contrato!!!");
			f.txtfecinicon.focus();
		}	
	}		
}
function DiferenciaFechas ()
{
    //Obtiene los datos del formulario
	f=document.form1;
    CadenaFecha1 = f.txtfecfincon.value;
    CadenaFecha2 = f.txtfecinicon.value;
	if((CadenaFecha1!="")&&(CadenaFecha2!=""))
	{
		//Obtiene dia, mes y año
		var fecha1 = new fecha( CadenaFecha1 )   
		var fecha2 = new fecha( CadenaFecha2 )
		//Obtiene objetos Date
		var miFecha1 = new Date( fecha1.anio, fecha1.mes, fecha1.dia )
		var miFecha2 = new Date( fecha2.anio, fecha2.mes, fecha2.dia )
	
		//Resta fechas y redondea
		var diferencia = miFecha1.getTime() - miFecha2.getTime()
		var dias = Math.floor(diferencia / (1000 * 60 * 60 * 24))
		dias=(parseFloat(dias) + 1);
	    f.txtplacon.value=dias+",00";
		if(dias<0)
		{
			f.txtplacon.value="";
		} 
    	return false
	}
}
function fecha( cadena ) {

    //Separador para la introduccion de las fechas
    var separador = "/"

    //Separa por dia, mes y año
    if ( cadena.indexOf( separador ) != -1 ) {
         var posi1 = 0
         var posi2 = cadena.indexOf( separador, posi1 + 1 )
         var posi3 = cadena.indexOf( separador, posi2 + 1 )
         this.dia = cadena.substring( posi1, posi2 )
         this.mes = cadena.substring( posi2 + 1, posi3 )
		 this.mes= this.mes-1;
         this.anio = cadena.substring( posi3 + 1, cadena.length )
    } else {
         this.dia = 0
         this.mes = 0
         this.anio = 0   
    }
}

function ue_detgarantias()
{
	f=document.form1;
	if (f.txtcodcon.value=="")
	{
		alert("Debe seleccionar un nuevo Contrato!!!");
	}
	else
	{
		f.operacion.value="";		
		pagina="sigesp_sob_pdt_garantias.php";
		popupWin(pagina,"catalogo",700,210);
//		window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=210,resizable=yes,location=no");
	}	
}
/////////////////////////////////////////////////////////////////////////////

///////Fin de las Funciones para para llamar catalogos/////

//////Funciones para cargar datos provenientes de catalogos///////
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
		ue_agregarprefijo();
		f.submit();
	}
}

function ue_cargarcontrato(ls_codigo,ls_desobr,ls_estado,ls_codest,ld_monto,ls_placon,ls_placonuni,ls_mulcon,ls_tiemulcon,ls_mulreuni,ls_lapgarcon,ls_lapgaruni,
						ls_codtco,ls_monmaxcon,ls_pormaxcon,ls_obscon,ls_porejefiscon,ls_porejefincon,ls_monejefincon,ls_codasi,ls_feccrecon,
						ls_fecinicon,ls_nomtco,ls_codobr,ls_codpro,ls_codproins,ls_fecfincon,ls_precon,ls_estapr)
{
	f=document.form1;
	f.hiddatoscontrato.value="OCULTAR";
	f.hiddatosasignacion.value="OCULTAR";
	f.hiddatosobra.value="OCULTAR";
	f.txtcodcon.value=ls_codigo;
	f.txtfeccon.value =ls_feccrecon;
	//f.txtestcon.value=ls_estado;
	//f.txtdestipcon.value=ls_nomtco;
	f.txtfecinicon.value=ls_fecinicon;
	f.txtpormaxcon.value=uf_convertir(ls_pormaxcon);
	f.hidtipocontrato.value=ls_codtco;
	f.txtobscon.value=ls_obscon;
	f.txtmonto.value=uf_convertir(ld_monto);
	f.txtmonmaxcon.value=uf_convertir(ls_monmaxcon);
	f.txtcodasi.value=ls_codasi;
	f.txtfecfincon.value=ls_fecfincon;
	f.operacion.value="ue_cargarcontrato";
	f.action="sigesp_sob_d_contrato.php";
	f.hidstatus.value="C";
	f.hidestapr.value=ls_estapr;
	DiferenciaFechas();
	//ue_agregarprefijo();
	f.submit();
}

function ue_cargarasignacion(ls_codasi,ls_codobr,ls_codpro,ls_codproins,ld_puncue,ls_obsasi,ls_monparasi,ls_basimpasi,ls_montotasi,ls_estasi,ls_desobr,ls_nompro,ls_fecasi)
{
	f=document.form1;
	f.txtcodasi.value=ls_codasi;
	lb_submit=false
	ue_agregarprefijo();
	if(f.hiddatosasignacion.value=="MOSTRAR")
	{
		f.operacion.value="ue_cargarasignacion";		
		f.submit();
	}else
	{
		if(f.hiddatosobra.value=="MOSTRAR")
		{
			f.operacion.value="ue_cargarasignacion";
			f.submit();
		}
	}	
}
	 
function ue_removerretenciones(li_fila)
{
	f=document.form1;
	f.hidremoverretenciones.value=li_fila;
	f.operacion.value="ue_removerretenciones"
	f.action="sigesp_sob_d_contrato.php";
	ue_agregarprefijo();
	f.submit();
}

function ue_removercondiciones(li_fila)
{
	f=document.form1;
	f.hidremovercondiciones.value=li_fila;
	f.operacion.value="ue_removercondiciones"
	f.action="sigesp_sob_d_contrato.php";
	ue_agregarprefijo();
	f.submit();
}

function ue_removergarantias(li_fila)
{
	f=document.form1;
	f.hidremovergarantias.value=li_fila;
	f.operacion.value="ue_removergarantias"
	f.action="sigesp_sob_d_contrato.php";
	ue_agregarprefijo();
	f.submit();
}

//////Fin de las funciones para cargar datos provenientes de catalogos///



//Funciones de Validacion///////////////////
/*Function ue_validafecha (f)
	Descripcion: Funcion que valida si las dos fechas introducidas por el usuario son correctas, es decir
				 que la fecha de inicio sea menor a la fecha de fin.
			
	Argumentos: f que representa al formulario que realiza la llamada 
*/

function ue_validafechacreacion()
{
	f=document.form1;
	li_fechacreacion=Date.parse(f.txtfeccon.value);
	li_fechainicio=Date.parse(f.txtfecinicon.value);
	if (li_fechainicio < li_fechacreacion)
	{
		alert ("La fecha de inicio debe ser mayor a la fecha actual!!!");
		f.txtfecinicon.value="";
	}	
}

function validarangofechas()
{
	f=document.form1;
	lb_valido=true;
	li_fechainicio=Date.parse(f.txtfeciniobr.value);
	li_fechafin=Date.parse(f.txtfecfinobr.value);
	if (li_fechainicio>=li_fechafin)
	{
		alert ("La fecha de inicio debe ser menor a la fecha de fin!!!");
		f.txtfecfinobr.value="";
		lb_valido=false;
	}
	return lb_valido;
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

function uf_procesarporcentaje (txt)
{
	f=document.form1;
	ue_getformat(txt);
	ld_montocontrato=parseFloat(uf_convertir_monto(f.txtmonto.value));	
	ld_montomaximo=parseFloat(uf_convertir_monto(f.txtmonmaxcon.value));
	if (ld_montocontrato!=ld_montomaximo || f.txtpormaxcon.value!="0,00")
	{	//7
		if(txt.id=="txtmonmaxcon")
		{//3
			if(f.monto.value!=txt.value)
			{
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
			}			
		}//3
		else
		{//2
			if (f.porcentaje.value!=txt.value)
			{
				ld_porcentaje=uf_convertir_monto(txt.value);
				if (ld_porcentaje>0)//1
				{
					ld_montomaximo=parseFloat(ld_porcentaje*ld_montocontrato/100)+parseFloat(ld_montocontrato);
					if (ld_montomaximo < ld_montocontrato)
					{
						alert("El Monto Máximo debe ser mayor o igual al Monto del Contrato!!!");
						f.txtmonmaxcon.value=uf_convertir(ld_montocontrato);
						f.txtpormaxcon.value="0,00"
					}
					f.txtmonmaxcon.value=uf_convertir(ld_montomaximo);		
				}//1
			}		
		}//2
	}//end primer if//7
}

function ue_guardarvalor()
{
	f=document.form1;
	f.monto.value=f.txtmonmaxcon.value;
	f.porcentaje.value=f.txtpormaxcon.value;
}

//////////////////////////////Fin de las funciones de validacion
function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{
		if(f.txtcodasi.value=="")
		{
			alert("Debe seleccionar una Asignación!!!");
		}
		else
		{
			f.txtfeccon.value=f.hidfecasi.value;
			f.operacion.value="ue_nuevo";
			f.action="sigesp_sob_d_contrato.php";
			ue_agregarprefijo();
			f.submit();
		} 	
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}
function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		var estado="";
		pagina="sigesp_cat_contrato.php?estado="+estado;
		popupWin(pagina,"catalogo",850,400);
//		window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=400,resizable=yes,location=no,status=no,top=0,left=0");
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
	lb_valido=true;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_status=f.hidstatus.value;
	estapr=f.hidestapr.value;
	if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
	{
		with(form1)
		{
			if(ue_valida_null(txtcodasi,"Código de la Asignación")==false)
			{
				lb_valido=false;
			}
			else
			{
				if(ue_valida_null(txtcodcon,"Código del Contrato")==false)
				{
					lb_valido=false;
				}
				else
				{
					if(ue_valida_null(cmbtipocontrato,"Tipo de Contrato")==false)
					{
						cmbtipocontrato.focus();
						lb_valido=false;
					}
					else
					{
						if(ue_valida_null(txtfecinicon,"Fecha de Inicio")==false)
						{
							txtfecinicon.focus();
							lb_valido=false;
						}
						else
						{
							if(parseInt(txtplacon.value)==0 || txtplacon.value=="")
							{
								alert("El Plazo de Duración del Contrato está vacío!!!");
								txtplacon.value="";
								txtplacon.focus();							
								lb_valido=false;
							}
						}
					}
				}
			}	
		}
		lb_valido_fecha=ue_comparar_intervalo("txtfecinicon","txtfecfincon","La fecha de inicio del contrato debe ser menor de la de finalización!!!");
		if(estapr==1)
		{
			alert("El contrato esta aprobado y no puede ser modificado");
		}
		else
		{
			if(lb_valido && lb_valido_fecha)
			{
				f.action="sigesp_sob_d_contrato.php";
				f.operacion.value="ue_guardar";
				ue_agregarprefijo();
				f.submit();
			}
		}
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}

}///////Fin de la funcion ue_guardar

function ue_eliminar()
{
	f=document.form1;
	li_eliminar=f.eliminar.value;
	estapr=f.hidestapr.value;
	if(li_eliminar==1)
	{	
		var lb_borrar="";				
		if (f.txtcodcon.value=="")
		{
			alert("No ha seleccionado ningún Contrato para eliminar !!!");
		}
		else
		{
			if(estapr==1)
			{
				alert("El contrato esta aprobado y no puede ser modificado");
			}
			else
			{
				borrar=confirm("¿ Esta seguro de eliminar este Contrato ?");
				if (borrar==true)
				{ 
					f=document.form1;
					f.operacion.value="ue_eliminar";
					f.action="sigesp_sob_d_contrato.php";
					ue_agregarprefijo();
					f.submit();
				}
			}
		}	
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}   
}

function ue_imprimir()
{
	f=document.form1;
	lb_valido=true;	
	if(f.txtcodcon.value!="")
	{
	  	with(form1)
		{
			if(ue_valida_null(txtcodasi,"Código de la Asignación")==false)
			{
				lb_valido=false;
			}
			else
			{
				if(ue_valida_null(txtcodcon,"Código del Contrato")==false)
				{
					lb_valido=false;
				}
				else
				{
					if(ue_valida_null(cmbtipocontrato,"Tipo de Contrato")==false)
					{
						cmbtipocontrato.focus();
						lb_valido=false;
					}
					else
					{
						if(ue_valida_null(txtfecinicon,"Fecha de Inicio")==false)
						{
							txtfecinicon.focus();
							lb_valido=false;
						}
						else
						{
							if(parseInt(txtplacon.value)==0 || txtplacon.value=="")
							{
								alert("El Plazo de Duración del Contrato está vacío!!!");
								txtplacon.value="";
								txtplacon.focus();							
								lb_valido=false;
							}
						}
					}
				}
			}	
		}	  
	  	if (lb_valido)
	  	{
			f.operacion.value="ue_imprimir";
			ue_agregarprefijo();
			f.submit();		    
		}
	}
	else
	{
		alert("Debe seleccionar un Contrato!!!");
	}	
}

	
function uf_mostrar_ocultar_asignacion()  
{
	f=document.form1;
	if (f.txtcodasi.value=="")
	{
		alert("Debe seleccionar una Asignación!!");
	}
	else
	{
		if (f.hiddatosasignacion.value == "OCULTAR")
		{
			f.hiddatosasignacion.value = "MOSTRAR";
			f.operacion.value="ue_cargarasignacion";
			
		}
		else
		{
			f.hiddatosasignacion.value = "OCULTAR";
			f.operacion.value="";
		}
		ue_agregarprefijo();
		f.submit();
	}
}

function uf_mostrar_ocultar_obra()  
{
	f=document.form1;
	if (f.txtcodasi.value=="")
	{
		alert("Debe seleccionar una Asignación!!");
	}
	else
	{
		if (f.hiddatosobra.value == "OCULTAR")
		{
			f.hiddatosobra.value = "MOSTRAR";
			f.operacion.value="ue_cargarasignacion";
			
		}
		else
		{
			f.hiddatosobra.value = "OCULTAR";
			f.operacion.value="";
		}
		ue_agregarprefijo();
		f.submit();
	}
}

function uf_mostrar_ocultar_contrato()  
{
	f=document.form1;
	if (f.hiddatoscontrato.value == "OCULTAR")
	{
		if(f.txtcodcon.value!="")
		{
			f.hiddatoscontrato.value = "MOSTRAR";
			f.operacion.value="ue_cargar_estadocontrato";
			ue_agregarprefijo();
			f.submit();
		}
		else
		{
			alert("Debe seleccionar un Contrato Existente!!!")
		}
	}
	else
	{
		f.hiddatoscontrato.value = "OCULTAR";
		ue_agregarprefijo();
		f.submit();	
	}
	
}

function ue_activar_unidad(txt)
{
	f=document.form1;
	if(txt.id=="txtlapmulcon")
	{
		
		if(parseInt(txt.value)!=0 && txt.value!="")
		{
			f.cmbretraso.disabled=false;
			//alert("es distinto de 0 y vacio")
		}
		else
		{
			f.cmbretraso.disabled=true;
			//alert("es igual a 0 o vacio")
		}
	}
	else
	{
		if(txt.id=="txtlapgarcon")
		{
			if(parseInt(txt.value)!=0 && txt.value!="")
			{
				f.cmbgarantia.disabled=false;
			}
			else
			{
				f.cmbgarantia.disabled=true;
			}
		}
		else
		{
			if(parseInt(txt.value)!=0 && txt.value!="")
			{
				f.cmbduracion.disabled=false;
			}
			else
			{
				f.cmbduracion.disabled=true;
			}
		}
	}
	
}

function ue_restarfechas(fechainicio,fechafinal,periodo) 
{
  var fechaini = new Date();
  fechaini.setFullYear(parseFloat(fechainicio.substr(6,4)),(parseFloat(fechainicio.substr(3,2))-1),parseFloat(fechainicio.substr(0,2)));
  var fechafin = new Date();
  fechafin.setFullYear(parseFloat(fechafinal.substr(6,4)),(parseFloat(fechafinal.substr(3,2))-1),parseFloat(fechafinal.substr(0,2)));
  var tiempoRestante = fechafin.getTime() - fechaini.getTime();
  var divisor;
  switch(periodo)
  {
    case 'd': divisor = 1000 * 60 * 60 * 24; break;
	case 'm': divisor = 1000 * 60 * 60 * 24 * 30; break;
	case 'a': divisor = 1000 * 60 * 60 * 24 * 30 * 12; break;
  }  
  var time = Math.floor(tiempoRestante / divisor);
  return time;
}

function ue_calcularperiodo(caja)
{
	f=document.form1;
	if (caja.id == "txtfecfincon")
	{
		if(f.txtfecinicon.value!="" && f.txtfecfincon.value!="")
		{
			if(ue_comparar_intervalo("txtfecinicon","txtfecfincon",""))
			{				
				f.cmbduracion.value = 'd';
				f.cmbduracion.disabled = false;
				var tiempo = ue_restarfechas(f.txtfecinicon.value,f.txtfecfincon.value,f.cmbduracion.value);
				f.txtplacon.value = tiempo;
			}
		}
		else
		{
			if ((f.txtplacon.value != "") && (f.cmbduracion.value != "---")&& (f.txtfecinicon.value!=""))
			{
			  var time;
			  switch(f.cmbduracion.value)
			  {
				case 'd': time = parseInt(f.txtplacon.value); break;
				case 'm': time = parseInt(f.txtplacon.value) * 30; break;
				case 'a': time = parseInt(f.txtplacon.value) * 365; break;
			  }
			  var fechaini = new Date();
			  fechaini.setFullYear(parseInt(f.txtfecinicon.value.substr(6,4)),parseInt(f.txtfecinicon.value.substr(3,2))-1,parseInt(f.txtfecinicon.value.substr(0,2)));
			  var fecfin = new Date();
			  fecfin.setDate(fechaini.getDate()+time);
			  var dia,mes,ano;
			  var month = fecfin.getMonth() + 1;
			  if (fecfin.getDate() < 10)
			  {dia = "0"+fecfin.getDate().toString();}
			  else
			  {dia = fecfin.getDate().toString();}
			  if (month < 10)
			  {mes = "0"+month.toString();}
			  else
			  {mes = month.toString();}
			  ano = fecfin.getFullYear().toString();
			 f.txtfecfincon.value = dia+"/"+mes+"/"+ano;
			}
		}
	}
	else
	{
		if ((f.txtplacon.value != "") && (f.cmbduracion.value != "---")&& (f.txtfecinicon.value!=""))
		{
		  var time;
		  switch(f.cmbduracion.value)
		  {
			case 'd': time = parseInt(f.txtplacon.value); break;
			case 'm': time = parseInt(f.txtplacon.value) * 30; break;
			case 'a': time = parseInt(f.txtplacon.value) * 365; break;
		  }
		  var fechaini = new Date();
		  fechaini.setFullYear(parseInt(f.txtfecinicon.value.substr(6,4)),parseInt(f.txtfecinicon.value.substr(3,2))-1,parseInt(f.txtfecinicon.value.substr(0,2)));
		  var fecfin = new Date();
		  fecfin.setDate(fechaini.getDate()+time);
		  var dia,mes,ano;
		  var month = fecfin.getMonth() + 1;
		  if (fecfin.getDate() < 10)
		  {dia = "0"+fecfin.getDate().toString();}
		  else
		  {dia = fecfin.getDate().toString();}
		  if (month < 10)
		  {mes = "0"+month.toString();}
		  else
		  {mes = month.toString();}
		  ano = fecfin.getFullYear().toString();
		 f.txtfecfincon.value = dia+"/"+mes+"/"+ano;
		}
	}
} 

function ue_agregarprefijo()
{
	//f.hidprefijo.value=combojs.valcon.value;
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>