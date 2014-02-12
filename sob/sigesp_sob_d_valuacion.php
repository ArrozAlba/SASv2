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
	$io_fun_sob->uf_load_seguridad("SOB","sigesp_sob_d_valuacion.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Valuacion</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_sob.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
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
	text-decoration: underline;
}
a:active {
	text-decoration: none;
}
.Estilo1 {color: #006699}
-->
</style><meta http-equiv="Content-Type" content="text/html; charset="></head>
<body link="#006699" vlink="#006699" alink="#006699">
<?Php
/******************************************/
/* FECHA: 25/03/2006                      */ 
/* AUTOR: GERARDO CORDERO                 */         
/******************************************/


/**************************************** DECLARACIONES  ********************************************************************************/
require_once("../shared/class_folder/class_sql.php");
require_once("class_folder/sigesp_sob_class_obra.php");
require_once("class_folder/sigesp_sob_class_asignacion.php");
require_once("class_folder/sigesp_sob_c_valuacion.php");
require_once("class_folder/sigesp_sob_c_funciones_sob.php");
require_once("../shared/class_folder/grid_param.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/evaluate_formula.php");
$io_asignacion=new sigesp_sob_class_asignacion();
$io_valuacion=new sigesp_sob_c_valuacion();
$io_obra=new sigesp_sob_class_obra();
$io_funcsob=new sigesp_sob_c_funciones_sob();
$io_evalform=new evaluate_formula();
$io_grid=new grid_param();
$io_msg=new class_mensajes();
$io_datastore=new class_datastore();
$io_function=new class_funciones();
$io_datastore=new class_datastore();

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

$ls_titulopartidas="Partidas Asignadas";
$li_anchopartidas=600;
$ls_nametable="grid2";
$la_columpartidas[1]="";
$la_columpartidas[2]="Código";
$la_columpartidas[3]="Partida";
$la_columpartidas[4]="Uni. Med.";
$la_columpartidas[5]="(Ref)Pre. Uni.";
$la_columpartidas[6]="Pre. Unitario";
$la_columpartidas[7]="Cant(A)";
$la_columpartidas[8]="Cant(V)";
$la_columpartidas[9]="Total";
$la_columpartidas[10]="Monto Ajuste";

$ls_titulocargos="Cargos";
$li_anchocargos=600;
$ls_nametable="grid3";
$la_columcargos[1]="Código";
$la_columcargos[2]="Denominación";
$la_columcargos[3]="Monto";
$la_columcargos[4]="Edición";

/****************************************************************************************************************************************/

/******************************************************	OBTENER VALORES DE LOS TXT *********************************************************/
if(array_key_exists("operacion",$_POST))
{
	$ld_subtot=0;
	$ls_opemostrarA=$_POST["opemostrarA"];
	$ls_opemostrar=$_POST["opemostrar"];
	$ls_operacion=$_POST["operacion"];
	$ls_codval=$_POST["txtcodval"];
	$ls_codcon=$_POST["txtcodcon"];
	$ls_fecinival=$_POST["txtfecinival"];
	$ls_fecfinval=$_POST["txtfecfinval"];
	$ls_obsval=$_POST["txtobsval"];
	$ls_fecha=$_POST["txtfecha"];
	$ls_estadoval=$_POST["txtestval"];
	$li_filasretenciones=$_POST["hidfilasretenciones"];
	$li_removerretenciones=$_POST["hidremoverretenciones"];
	$ls_amoant=$_POST["txtamoant"];
	$ls_amoactual=$_POST["txtamoactual"];
	$ls_poramoactual=$_POST["txtporamoactual"];
    $ls_amoobs=$_POST["txtamoobs"];
    $ls_amores=$_POST["txtamores"]; 
	$ls_amotot=$_POST["txtamotot"];
	$ls_totant=$_POST["hidtotant"]; 
    $ls_totcon=$_POST["hidtotcon"];
	$ls_desobr=$_POST["hiddesobr"];
	$ls_estapr=$_POST["hidestapr"];
	$ls_puncue=$_POST["hidpuncue"];
    $ls_estcon=$_POST["hidestcon"];  
    $ls_moncon=$_POST["hidmoncon"]; 
    $ls_feccon=$_POST["hidfeccon"];
	$ls_subtotpar=$_POST["txtsubtotpar"]; 
	$ls_subtot=$_POST["txtsubtot"]; 
	$ls_basimpval=$_POST["txtbasimpval"];
	$ls_montotval=$_POST["txtmontotval"];
	$ls_totreten=$_POST["txttotreten"];
	$ls_hidamototbd=$_POST["hidamototbd"]; 
	$ls_hidamoresbd=$_POST["hidamoresbd"];
	$li_filaspartidas=$_POST["filaspartidas"];
	$li_filascargos=$_POST["filascargos"];
	$ls_hidcodasi =$_POST["hidcodasi"];
	$ls_chk=$_POST["hidstatus"];
	$li_filaspartidas=$_POST["filaspartidas"];
	if($li_filascargos>1)
	{
		for($li_i=1;$li_i<$li_filascargos;$li_i++)
		{
			$ls_moncar=$_POST["txtmoncar".$li_i];
			$ls_moncar=$io_funcsob->uf_convertir_cadenanumero($ls_moncar);
			$ld_subtot=$ld_subtot+$ls_moncar;
		}
	}
	for($li_i=1;$li_i<$li_filaspartidas;$li_i++)
     {
			$ls_codigo=$_POST["txtcodpar".$li_i];
			$ls_nombre=$_POST["txtnompar".$li_i];
			$ls_unidad=$_POST["txtnomuni".$li_i];
			$ls_preuni=$_POST["txtpreuni".$li_i];
			$ls_preunimod=$_POST["txtpreunimod".$li_i];
			$ls_canttot=$_POST["txtcanttot".$li_i];
			$ls_cantpar=$_POST["txtcantpar".$li_i];
			$ls_total=$_POST["txttotal".$li_i];
			$ls_canpareje=$_POST["canpareje".$li_i];
			$ls_codasi=$_POST["codasi".$li_i];
			$ls_codobr=$_POST["codobr".$li_i];
			
			if(!empty($_POST["flagpar".$li_i]))
			{
			 $la_objectpartidas[$li_i][1]="<input type=checkbox name=flagpar".$li_i." value=1 checked class=sin-borde onClick='javascript: ue_marcartodo(".$li_i.");' >";
			}
			else
			{
			 $la_objectpartidas[$li_i][1]="<input type=checkbox name=flagpar".$li_i." value=1 class=sin-borde onClick='javascript: ue_marcartodo(".$li_i.");' >";
			}
	        $la_objectpartidas[$li_i][2]="<input name=txtcodpar".$li_i." type=text id=txtcodpar".$li_i." value='".$ls_codigo."' class=sin-borde style= text-align:center size=8 readonly><input name=canpareje".$li_i." type=hidden id=canpareje".$li_i." value='".$ls_canpareje."'><input name=codasi".$li_i." type=hidden id=codasi".$li_i." value='".$ls_codasi."'><input name=codobr".$li_i." type=hidden id=codobr".$li_i." value='".$ls_codobr."'>";
	        $la_objectpartidas[$li_i][3]="<input name=txtnompar".$li_i." type=text id=txtnompar".$li_i." value='".$ls_nombre."' class=sin-borde style= text-align:left size=25 readonly>";
	        $la_objectpartidas[$li_i][4]="<input name=txtnomuni".$li_i." type=text id=txtnomuni".$li_i." value='".$ls_unidad."' class=sin-borde size=5 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][5]="<input name=txtpreuni".$li_i." type=text id=txtpreuni".$li_i." value='".$ls_preuni."' class=sin-borde size=15 style= text-align:center readonly>";
			$la_objectpartidas[$li_i][6]="<input name=txtpreunimod".$li_i." type=text id=txtpreunimod".$li_i." value='".$ls_preunimod."' class=sin-borde size=15 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][7]="<input name=txtcanttot".$li_i." type=text id=txtcanttot".$li_i." value='".$ls_canttot."' class=sin-borde size=5 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][8]="<input name=txtcantpar".$li_i." type=text id=txtcantpar".$li_i." value='".$ls_cantpar."' class=sin-borde size=5 style= text-align:center onKeyPress=return(currencyFormat(this,'.',',',event))>";
	        $la_objectpartidas[$li_i][9]="<input name=txttotal".$li_i." type=text id=txttotal".$li_i." value='".$ls_total."' class=sin-borde size=15 style= text-align:center readonly>";	
			$la_objectpartidas[$li_i][10]="<input name=txtmonaju".$li_i."  type=text id=txtmonaju".$li_i."     class=sin-borde  style=text-align:right  size=6 onKeyPress=return(ue_formatonumero_negativo(this,'.',',',event)); value='0,00' onBlur=javascript:ue_ajustar(".$li_i.");>";
			if($io_funcsob->uf_convertir_cadenanumero($ls_canttot)==0)
			{
			 $la_objectpartidas[$li_i][1]="<input type=checkbox name=flagpar".$li_i." value=1 class=sin-borde disabled>";
	        $la_objectpartidas[$li_i][2]="<input name=txtcodpar".$li_i." type=text id=txtcodpar".$li_i." value='".$ls_codigo."' class=sin-borde style= text-align:center size=8 readonly><input name=canpareje".$li_i." type=hidden id=canpareje".$li_i." value='".$ls_canpareje."'><input name=codasi".$li_i." type=hidden id=codasi".$li_i." value='".$ls_codasi."'><input name=codobr".$li_i." type=hidden id=codobr".$li_i." value='".$ls_codobr."'>";
	        $la_objectpartidas[$li_i][3]="<input name=txtnompar".$li_i." type=text id=txtnompar".$li_i." value='".$ls_nombre."' class=sin-borde style= text-align:left size=25 readonly>";
	        $la_objectpartidas[$li_i][4]="<input name=txtnomuni".$li_i." type=text id=txtnomuni".$li_i." value='".$ls_unidad."' class=sin-borde size=5 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][5]="<input name=txtpreuni".$li_i." type=text id=txtpreuni".$li_i." value='".$ls_preuni."' class=sin-borde size=15 style= text-align:center readonly>";
			$la_objectpartidas[$li_i][6]="<input name=txtpreunimod".$li_i." type=text id=txtpreunimod".$li_i." value='".$ls_preunimod."' class=sin-borde size=15 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][7]="<input name=txtcanttot".$li_i." type=text id=txtcanttot".$li_i." value='".$ls_canttot."' class=sin-borde size=5 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][8]="<input name=txtcantpar".$li_i." type=text id=txtcantpar".$li_i." value='".$ls_cantpar."' class=sin-borde size=5 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][9]="<input name=txttotal".$li_i." type=text id=txttotal".$li_i." value='".$ls_total."' class=sin-borde size=15 style= text-align:center readonly>";	
			$la_objectpartidas[$li_i][10]="<input name=txtmonaju".$li_i."  type=text id=txtmonaju".$li_i."     class=sin-borde  style=text-align:right  size=6 onKeyPress=return(ue_formatonumero_negativo(this,'.',',',event)); value='0,00' onBlur=javascript:ue_ajustar(".$li_i.");>";
			}
	}	
	$la_objectpartidas[$li_filaspartidas][1]="<input type=checkbox name=flagpar".$li_filaspartidas." value=1 disabled class=sin-borde>";
	$la_objectpartidas[$li_filaspartidas][2]="<input name=txtcodpar".$li_filaspartidas." type=text id=txtcodpar".$li_filaspartidas." class=sin-borde style= text-align:center size=8 readonly>";
	$la_objectpartidas[$li_filaspartidas][3]="<input name=txtnompar".$li_filaspartidas." type=text id=txtnompar".$li_filaspartidas." class=sin-borde style= text-align:left size=25 readonly>";
	$la_objectpartidas[$li_filaspartidas][4]="<input name=txtnomuni".$li_filaspartidas." type=text id=txtnomuni".$li_filaspartidas." class=sin-borde size=5 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][5]="<input name=txtpreuni".$li_filaspartidas." type=text id=txtpreuni".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][6]="<input name=txtpreunimod".$li_filaspartidas." type=text id=txtpreunimod".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][7]="<input name=txtcanttot".$li_filaspartidas." type=text id=txtcanttot".$li_filaspartidas." class=sin-borde size=5 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][8]="<input name=txtcantpar".$li_filaspartidas." type=text id=txtcantpar".$li_filaspartidas." class=sin-borde size=5 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][9]="<input name=txttotal".$li_filaspartidas." type=text id=txttotal".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";	
	$la_objectpartidas[$li_filaspartidas][10]="<input name=txtmonaju".$li_filaspartidas."  type=text id=txtmonaju".$li_filaspartidas."     class=sin-borde  style=text-align:right  size=6 onKeyPress=return(ue_formatonumero_negativo(this,'.',',',event)); value='0,00'>";
	
		
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
			$la_objectretenciones[$li_i][3]="<input name=txtcueret".$li_i." type=text id=txtcueret".$li_i." class=sin-borde value='".$ls_cuenta."' style= text-align:left size=20 readonly>";
			$la_objectretenciones[$li_i][4]="<input name=txtdedret".$li_i." type=text id=txtdedret".$li_i." class=sin-borde value='".$ls_deduccion."' style= text-align:left size=15 readonly><input name=formula".$li_i." type=hidden id=formula".$li_i." value='".$ls_formula."'>";
			$la_objectretenciones[$li_i][5]="<input name=txtmonret".$li_i." type=text id=txtmonret".$li_i." class=sin-borde value='".$ls_monret."' style= text-align:left size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=ue_calcretencion(this)>";
			$la_objectretenciones[$li_i][6]="<input name=txttotret".$li_i." type=text id=txttotret".$li_i." class=sin-borde value='".$ls_totret."' style= text-align:left size=15 readonly>";
		    $la_objectretenciones[$li_i][7]="<a href=javascript:ue_removerretenciones(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
		}	
		$la_objectretenciones[$li_filasretenciones][1]="<input name=txtcodret".$li_filasretenciones." type=text id=txtcodret".$li_filasretenciones." class=sin-borde style= text-align:center size=5 readonly>";
		$la_objectretenciones[$li_filasretenciones][2]="<input name=txtdesret".$li_filasretenciones." type=text id=txtdesret".$li_filasretenciones." class=sin-borde style= text-align:left size=30 readonly>";
		$la_objectretenciones[$li_filasretenciones][3]="<input name=txtcueret".$li_filasretenciones." type=text id=txtcueret".$li_filasretenciones." class=sin-borde  style= text-align:center size=20 readonly><input name=formula".$li_filasretenciones." type=hidden id=formula".$li_filasretenciones.">";
		$la_objectretenciones[$li_filasretenciones][4]="<input name=txtdedret".$li_filasretenciones." type=text id=txtdedret".$li_filasretenciones." class=sin-borde  style= text-align:center size=15 readonly>";
		$la_objectretenciones[$li_filasretenciones][5]="<input name=txtmonret".$li_filasretenciones." type=text id=txtmonret".$li_filasretenciones." class=sin-borde style= text-align:left size=15 readonly>";
		$la_objectretenciones[$li_filasretenciones][6]="<input name=txttotret".$li_filasretenciones." type=text id=txttotret".$li_filasretenciones." class=sin-borde style= text-align:left size=15 readonly>";
		$la_objectretenciones[$li_filasretenciones][7]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	}
	if ($ls_operacion != "ue_cargarcargo" && $ls_operacion != "ue_removercargo")
	{
		$li_filascargos=$_POST["filascargos"];
		for($li_i=1;$li_i<$li_filascargos;$li_i++)
		{		
			$ls_codigo=$_POST["txtcodcar".$li_i];
			$ls_nombre=$_POST["txtnomcar".$li_i];
			$ls_moncue=$_POST["txtmoncar".$li_i];
			$ls_formula=$_POST["formu".$li_i];
			$ls_codestpro=$_POST["codestpro".$li_i];
			$ls_spgcuenta=$_POST["spgcuenta".$li_i];
			$ls_estcla=$_POST["estcla".$li_i];
			$la_objectcargos[$li_i][1]="<input name=txtcodcar".$li_i." type=text id=txtcodcar".$li_i." class=sin-borde style= text-align:center size=5 value='".$ls_codigo."' readonly><input name=formu".$li_i." type=hidden id=formu".$li_i." value='".$ls_formula."'>";
			$la_objectcargos[$li_i][2]="<input name=txtnomcar".$li_i." type=text id=txtnomcar".$li_i." class=sin-borde style= text-align:left size=60 value='".$ls_nombre."' readonly >".
										"<input name=codestpro".$li_i." type=hidden id=codestpro".$li_i." value='".$ls_codestpro."'><input name=spgcuenta".$li_i." type=hidden id=spgcuenta".$li_i." value='".$ls_spgcuenta."'><input name=estcla".$li_i." type=hidden id=estcla".$li_i." value='".$ls_estcla."'>";
			$la_objectcargos[$li_i][3]="<input name=txtmoncar".$li_i." type=text id=txtmoncar".$li_i." class=sin-borde size=20 style= text-align:center value='".$ls_moncue."' readonly>";
			$la_objectcargos[$li_i][4]="<a href=javascript:ue_removercargo(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
		}	
		$la_objectcargos[$li_filascargos][1]="<input name=txtcodcar".$li_filascargos." type=text id=txtcodcar".$li_filascargos." class=sin-borde style= text-align:center size=5 readonly><input name=formu".$li_filascargos." type=hidden id=formu".$li_filascargos.">";
		$la_objectcargos[$li_filascargos][2]="<input name=txtnomcar".$li_filascargos." type=text id=txtnomcar".$li_filascargos." class=sin-borde style= text-align:left size=50 readonly>".
											"<input name=codestpro".$li_filascargos." type=hidden id=codestpro".$li_filascargos."><input name=spgcuenta".$li_filascargos." type=hidden id=spgcuenta".$li_filascargos."><input name=estcla".$li_filascargos." type=hidden id=estcla".$li_filascargos.">";
		$la_objectcargos[$li_filascargos][3]="<input name=txtmoncar".$li_filascargos." type=text id=txtmoncar".$li_filascargos." class=sin-borde style= text-align:center size=20 readonly>";
		$la_objectcargos[$li_filascargos][4]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	}

	
}
/*******************************************************************************************************************************************************/

/************************************************ INICIALIZA LAS VARIABLES SI NO HAY SUBMIT *******************************************************************************************************/
else
{
	$ls_codtipdoc="";
	$ls_dentipdoc="";
    $ls_opemostrar="";
	$ls_opemostrarA="";
	$ls_operacion="";
	$ls_codval="";
	$ls_codcon="";
	$ls_fecinival="";
	$ls_fecfinval="";
	$ls_obsval="";
	$ls_fecha="";
	$ls_estadoval="";
	$ls_amoant="0,00";
	$ls_amotot="0,00";
	$ls_poramoactual="0,00";
	$ls_amoactual="0,00";
    $ls_amoobs="";
    $ls_amores="0,00"; 
	$ls_totant=""; 
    $ls_totcon="";
	$ls_desobr="";
	$ls_estapr="";
	$ls_puncue="";
    $ls_estcon="";  
    $ls_moncon=""; 
    $ls_feccon="";
	$ls_hidamototbd=""; 
	$ls_hidamoresbd="";
	$li_removerretenciones="";
	$li_removercargo="";
	$ls_subtotpar="0,00"; 
	$ls_subtot="0,00"; 
	$ls_basimpval="0,00";
	$ls_montotval="0,00";
	$ls_totreten="0,00";
	$ls_hidcodasi="";
	$ls_chk="";
	$ls_codtipdoc="";
	$ls_dentipdoc="";
	$ld_subtot=0;
	
	$li_filasretenciones=1;
	$la_objectretenciones[1][1]="<input name=txtcodret1 type=text id=txtcodret1 class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectretenciones[1][2]="<input name=txtdesret1 type=text id=txtdesret1 class=sin-borde style= text-align:left size=30 readonly>";
	$la_objectretenciones[1][3]="<input name=txtcueret1 type=text id=txtcueret1 class=sin-borde style= text-align:center size=20 readonly>";
	$la_objectretenciones[1][4]="<input name=txtdedret1 type=text id=txtdedret1 class=sin-borde style= text-align:center size=15 readonly><input name=formula1 type=hidden id=formula1>";
	$la_objectretenciones[1][5]="<input name=txtmonret1 type=text id=txtmonret1 class=sin-borde style= text-align:center size=15 readonly>";
	$la_objectretenciones[1][6]="<input name=txttotret1 type=text id=txttotret1 class=sin-borde style= text-align:center size=15 readonly>";
	$la_objectretenciones[1][7]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
    
	$li_filaspartidas=1;
	$la_objectpartidas[$li_filaspartidas][1]="<input type=checkbox name=flagpar".$li_filaspartidas." value=1 disabled class=sin-borde>";
	$la_objectpartidas[$li_filaspartidas][2]="<input name=txtcodpar".$li_filaspartidas." type=text id=txtcodpar".$li_filaspartidas." class=sin-borde style= text-align:center size=8 readonly><input name=canpareje".$li_filaspartidas." type=hidden id=canpareje".$li_filaspartidas.">";
	$la_objectpartidas[$li_filaspartidas][3]="<input name=txtnompar".$li_filaspartidas." type=text id=txtnompar".$li_filaspartidas." class=sin-borde style= text-align:left size=25 readonly>";
	$la_objectpartidas[$li_filaspartidas][4]="<input name=txtnomuni".$li_filaspartidas." type=text id=txtnomuni".$li_filaspartidas." class=sin-borde size=5 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][5]="<input name=txtpreuni".$li_filaspartidas." type=text id=txtpreuni".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][6]="<input name=txtpreunimod".$li_filaspartidas." type=text id=txtpreunimod".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][7]="<input name=txtcanttot".$li_filaspartidas." type=text id=txtcanttot".$li_filaspartidas." class=sin-borde size=5 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][8]="<input name=txtcantpar".$li_filaspartidas." type=text id=txtcantpar".$li_filaspartidas." class=sin-borde size=5 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][9]="<input name=txttotal".$li_filaspartidas." type=text id=txttotal".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][10]="<input name=txtmonaju".$li_filaspartidas."  type=text id=txtmonaju".$li_filaspartidas."     class=sin-borde  style=text-align:right  size=6 onKeyPress=return(ue_formatonumero_negativo(this,'.',',',event)); value='0,00'>";
	
	$li_filascargos=1;
	$la_objectcargos[$li_filascargos][1]="<input name=txtcodcar".$li_filascargos." type=text id=txtcodcar".$li_filascargos." class=sin-borde style= text-align:center size=5 readonly><input name=formu".$li_filascargos." type=hidden id=formu".$li_filascargos.">";
	$la_objectcargos[$li_filascargos][2]="<input name=txtnomcar".$li_filascargos." type=text id=txtnomcar".$li_filascargos." class=sin-borde style= text-align:left size=50 readonly>".
										"<input name=codestpro".$li_filascargos." type=hidden id=codestpro".$li_filascargos."><input name=spgcuenta".$li_filascargos." type=hidden id=spgcuenta".$li_filascargos."><input name=estcla".$li_filascargos." type=hidden id=estcla".$li_filascargos.">";
	$la_objectcargos[$li_filascargos][3]="<input name=txtmoncar".$li_filascargos." type=text id=txtmoncar".$li_filascargos." class=sin-borde style= text-align:center size=20 readonly>";
	$la_objectcargos[$li_filascargos][4]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	
}
/***************************************************************************************************************************************************************************/


/************************************************ PREPARANDO INSERCION DE NUEVO REGISTRO ****************************************************************************/
if($ls_operacion=="ue_nuevo")
{
	$ls_codtipdoc="";
	$ls_dentipdoc="";
    $ls_opemostrar="";
	$ls_opemostrarA="";
	$ls_operacion="";
	$ls_codval="";
	$ls_codcon="";
	$ls_fecinival="";
	$ls_fecfinval="";
	$ls_obsval="";
	$ls_fecha="";
	$ls_estadoval="";
	$ls_amoant="0,00";
	$ls_amotot="0,00";
	$ls_poramoactual="0,00";
	$ls_amoactual="0,00";
    $ls_amoobs="";
    $ls_amores="0,00"; 
	$ls_totant=""; 
    $ls_totcon="";
	$ls_desobr="";
	$ls_estapr="";
	$ls_puncue="";
    $ls_estcon="";  
    $ls_moncon=""; 
    $ls_feccon="";
	$ls_hidamototbd=""; 
	$ls_hidamoresbd="";
	$li_removerretenciones="";
	$li_removercargo="";
	$ls_subtotpar="0,00"; 
	$ls_subtot="0,00"; 
	$ls_basimpval="0,00";
	$ls_montotval="0,00";
	$ls_totreten="0,00";
	$ls_hidcodasi="";
	$ls_chk="";
	$ls_codtipdoc="";
	$ls_dentipdoc="";
	$ld_subtot=0;
	$ld_subtot=0;

	$li_filaspartidas=1;
	$la_objectpartidas[$li_filaspartidas][1]="<input type=checkbox name=flagpar".$li_filaspartidas." value=1 disabled class=sin-borde>";
	$la_objectpartidas[$li_filaspartidas][2]="<input name=txtcodpar".$li_filaspartidas." type=text id=txtcodpar".$li_filaspartidas." class=sin-borde style= text-align:center size=8 readonly><input name=canpareje".$li_filaspartidas." type=hidden id=canpareje".$li_filaspartidas.">";
	$la_objectpartidas[$li_filaspartidas][3]="<input name=txtnompar".$li_filaspartidas." type=text id=txtnompar".$li_filaspartidas." class=sin-borde style= text-align:left size=25 readonly>";
	$la_objectpartidas[$li_filaspartidas][4]="<input name=txtnomuni".$li_filaspartidas." type=text id=txtnomuni".$li_filaspartidas." class=sin-borde size=5 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][5]="<input name=txtpreuni".$li_filaspartidas." type=text id=txtpreuni".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][6]="<input name=txtpreunimod".$li_filaspartidas." type=text id=txtpreunimod".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][7]="<input name=txtcanttot".$li_filaspartidas." type=text id=txtcanttot".$li_filaspartidas." class=sin-borde size=5 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][8]="<input name=txtcantpar".$li_filaspartidas." type=text id=txtcantpar".$li_filaspartidas." class=sin-borde size=5 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][9]="<input name=txttotal".$li_filaspartidas." type=text id=txttotal".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][10]="<input name=txtmonaju".$li_filaspartidas."  type=text id=txtmonaju".$li_filaspartidas."     class=sin-borde  style=text-align:right  size=6 onKeyPress=return(ue_formatonumero_negativo(this,'.',',',event)); value='0,00'>";
	
	$li_filasretenciones=1;
	$la_objectretenciones[1][1]="<input name=txtcodret1 type=text id=txtcodret1 class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectretenciones[1][2]="<input name=txtdesret1 type=text id=txtdesret1 class=sin-borde style= text-align:left size=30 readonly>";
	$la_objectretenciones[1][3]="<input name=txtcueret1 type=text id=txtcueret1 class=sin-borde style= text-align:center size=20 readonly>";
	$la_objectretenciones[1][4]="<input name=txtdedret1 type=text id=txtdedret1 class=sin-borde style= text-align:center size=15 readonly><input name=formula1 type=hidden id=formula1>";
	$la_objectretenciones[1][5]="<input name=txtmonret1 type=text id=txtmonret1 class=sin-borde style= text-align:center size=15 readonly>";
	$la_objectretenciones[1][6]="<input name=txttotret1 type=text id=txttotret1 class=sin-borde style= text-align:center size=15 readonly>";
	$la_objectretenciones[1][7]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
		
	$li_filascargos=1;
	$la_objectcargos[$li_filascargos][1]="<input name=txtcodcar".$li_filascargos." type=text id=txtcodcar".$li_filascargos." class=sin-borde style= text-align:center size=5 readonly><input name=formu".$li_filascargos." type=hidden id=formu".$li_filascargos.">";
	$la_objectcargos[$li_filascargos][2]="<input name=txtnomcar".$li_filascargos." type=text id=txtnomcar".$li_filascargos." class=sin-borde style= text-align:left size=50 readonly>".
										"<input name=codestpro".$li_filascargos." type=hidden id=codestpro".$li_filascargos."><input name=spgcuenta".$li_filascargos." type=hidden id=spgcuenta".$li_filascargos."><input name=estcla".$li_filascargos." type=hidden id=estcla".$li_filascargos.">";
	$la_objectcargos[$li_filascargos][3]="<input name=txtmoncar".$li_filascargos." type=text id=txtmoncar".$li_filascargos." class=sin-borde style= text-align:center size=20 readonly>";
	$la_objectcargos[$li_filascargos][4]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	
	
}
/***************************************************************************************************************************************************************************/

/*************************************************INSERTAR CAMPO EN GRID RETENCIONES**************************************************************************************************************************/
elseif($ls_operacion=="ue_cargarretenciones")
{	
	$li_filasretenciones=$li_filasretenciones+1;
	$ls_totreten=0;
	for($li_i=1;$li_i<$li_filasretenciones;$li_i++)
	{
		$ls_codigo=$_POST["txtcodret".$li_i];
		$ls_descripcion=$_POST["txtdesret".$li_i];
		$ls_cuenta=$_POST["txtcueret".$li_i];
		$ls_deduccion=$_POST["txtdedret".$li_i];
		$ls_monret=$_POST["txtmonret".$li_i];
		$ls_totret=$_POST["txttotret".$li_i];
		$ls_formula=$_POST["formula".$li_i];
		$li_iva=$io_valuacion->uf_select_tipodeduccion($ls_codigo);
		$ls_readonly="";
		if($li_iva==1)
		{
			$ls_monret=$ld_subtot;
			$ls_totret=$io_evalform->uf_evaluar($ls_formula,$ls_monret,$lb_valido);
			$ls_totreten=$ls_totreten+$ls_totret;
			$ls_monret=number_format($ls_monret,2,',','.');
			$ls_totret=number_format($ls_totret,2,',','.');
			$ls_readonly="readonly";
		}
		else
		{
			$ls_monret=$io_funcsob->uf_convertir_cadenanumero($ls_basimpval);
			$ls_totret=$io_evalform->uf_evaluar($ls_formula,$ls_monret,$lb_valido);
			$ls_totreten=$ls_totreten+$ls_totret;
			$ls_monret=number_format($ls_monret,2,',','.');
			$ls_totret=number_format($ls_totret,2,',','.');
		}
		$la_objectretenciones[$li_i][1]="<input name=txtcodret".$li_i." type=text id=txtcodret".$li_i." class=sin-borde value='".$ls_codigo."' style= text-align:center size=5 readonly>";
		$la_objectretenciones[$li_i][2]="<input name=txtdesret".$li_i." type=text id=txtdesret".$li_i." class=sin-borde value='".$ls_descripcion."' style= text-align:left size=30 readonly>";
		$la_objectretenciones[$li_i][3]="<input name=txtcueret".$li_i." type=text id=txtcueret".$li_i." class=sin-borde value='".$ls_cuenta."' style= text-align:center size=20 readonly>";
		$la_objectretenciones[$li_i][4]="<input name=txtdedret".$li_i." type=text id=txtdedret".$li_i." class=sin-borde value='".$ls_deduccion."' style= text-align:center size=15 readonly><input name=formula".$li_i." type=hidden id=formula".$li_i." value='".$ls_formula."'>";
		$la_objectretenciones[$li_i][5]="<input name=txtmonret".$li_i." type=text id=txtmonret".$li_i." class=sin-borde value='".$ls_monret."' style= text-align:center size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=ue_calcretencion(this) ".$ls_readonly.">";
		$la_objectretenciones[$li_i][6]="<input name=txttotret".$li_i." type=text id=txttotret".$li_i." class=sin-borde value='".$ls_totret."' style= text-align:center size=15 readonly>";
		$la_objectretenciones[$li_i][7]="<a href=javascript:ue_removerretenciones(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
	}	
	$la_objectretenciones[$li_filasretenciones][1]="<input name=txtcodret".$li_filasretenciones." type=text id=txtcodret".$li_filasretenciones." class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectretenciones[$li_filasretenciones][2]="<input name=txtdesret".$li_filasretenciones." type=text id=txtdesret".$li_filasretenciones." class=sin-borde style= text-align:left size=30 readonly>";
	$la_objectretenciones[$li_filasretenciones][3]="<input name=txtcueret".$li_filasretenciones." type=text id=txtcueret".$li_filasretenciones." class=sin-borde  style= text-align:center size=20 readonly><input name=formula".$li_filasretenciones." type=hidden id=formula".$li_filasretenciones.">";
    $la_objectretenciones[$li_filasretenciones][4]="<input name=txtdedret".$li_filasretenciones." type=text id=txtdedret".$li_filasretenciones." class=sin-borde  style= text-align:center size=15 readonly>";
	$la_objectretenciones[$li_filasretenciones][5]="<input name=txtmonret".$li_filasretenciones." type=text id=txtmonret".$li_filasretenciones." class=sin-borde style= text-align:left size=15 readonly>";
	$la_objectretenciones[$li_filasretenciones][6]="<input name=txttotret".$li_filasretenciones." type=text id=txttotret".$li_filasretenciones." class=sin-borde style= text-align:left size=15 readonly>";
	$la_objectretenciones[$li_filasretenciones][7]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	
	$ls_subcar=$io_funcsob->uf_convertir_cadenanumero($ls_subtot);
	$ls_montotval=$ls_subcar-$ls_totreten;
	$ls_totreten=$io_funcsob->uf_convertir_numerocadena($ls_totreten);
	$ls_montotval=$io_funcsob->uf_convertir_numerocadena($ls_montotval);
}
/***************************************************************************************************************************************************************************/

/*******************************************************REMOVER CAMPO EN GRID RETENCIONES********************************************************************************************************************/
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
			$la_objectretenciones[$li_temp][5]="<input name=txtmonret".$li_temp." type=text id=txtmonret".$li_temp." class=sin-borde value='".$ls_monret."' style= text-align:center size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=ue_calcretencion(this)>";
			$la_objectretenciones[$li_temp][6]="<input name=txttotret".$li_temp." type=text id=txttotret".$li_temp." class=sin-borde value='".$ls_totret."' style= text-align:center size=15 readonly>";
			$la_objectretenciones[$li_temp][7]="<a href=javascript:ue_removerretenciones(".$li_temp.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
		}
	}
	$la_objectretenciones[$li_filasretenciones][1]="<input name=txtcodret".$li_filasretenciones." type=text id=txtcodret".$li_filasretenciones." class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectretenciones[$li_filasretenciones][2]="<input name=txtdesret".$li_filasretenciones." type=text id=txtdesret".$li_filasretenciones." class=sin-borde style= text-align:left size=30 readonly>";
	$la_objectretenciones[$li_filasretenciones][3]="<input name=txtcueret".$li_filasretenciones." type=text id=txtcueret".$li_filasretenciones." class=sin-borde  style= text-align:center size=20 readonly><input name=formula".$li_filasretenciones." type=hidden id=formula".$li_filasretenciones.">";
	$la_objectretenciones[$li_filasretenciones][4]="<input name=txtdedret".$li_filasretenciones." type=text id=txtdedret".$li_filasretenciones." class=sin-borde  style= text-align:center size=15 readonly>";
	$la_objectretenciones[$li_filasretenciones][5]="<input name=txtmonret".$li_filasretenciones." type=text id=txtmonret".$li_filasretenciones." class=sin-borde style= text-align:left size=15 >";
	$la_objectretenciones[$li_filasretenciones][6]="<input name=txttotret".$li_filasretenciones." type=text id=txttotret".$li_filasretenciones." class=sin-borde style= text-align:left size=15 readonly>";
	$la_objectretenciones[$li_filasretenciones][7]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
}
/***************************************************************************************************************************************************************************/
/*************************************************INSERTAR CAMPO EN GRID CARGOS**************************************************************************************************************************/
elseif($ls_operacion=="ue_cargarcargo")
{
	$ls_subtotpar=$_POST["txtsubtotpar"]; 
	$ls_basimpval=$_POST["txtbasimpval"];
	$ld_basimpval=$io_funcsob->uf_convertir_cadenanumero($ls_basimpval);
	$ld_subtotpar=$io_funcsob->uf_convertir_cadenanumero($ls_subtotpar);
	$ld_subtot=0;
	
	$li_filascargos=$_POST["filascargos"];
	$li_filascargos=$li_filascargos+1;
	
	for($li_i=1;$li_i<$li_filascargos;$li_i++)
	{
		$ls_codigo=$_POST["txtcodcar".$li_i];
		$ls_nombre=$_POST["txtnomcar".$li_i];
		$ls_formula=$_POST["formu".$li_i];
		$ls_codestpro=$_POST["codestpro".$li_i];
		$ls_spgcuenta=$_POST["spgcuenta".$li_i];
		$ls_estcla=$_POST["estcla".$li_i];
		$ld_result=$io_evalform->uf_evaluar($ls_formula,$ld_basimpval,$lb_valido);
		$ld_subtot=$ld_subtot+$ld_result;
		$la_objectcargos[$li_i][1]="<input name=txtcodcar".$li_i." type=text id=txtcodcar".$li_i." class=sin-borde style= text-align:center size=5 value='".$ls_codigo."' readonly><input name=formu".$li_i." type=hidden id=formu".$li_i." value='".$ls_formula."'>";
		$la_objectcargos[$li_i][2]="<input name=txtnomcar".$li_i." type=text id=txtnomcar".$li_i." class=sin-borde style= text-align:left size=60 value='".$ls_nombre."' readonly>".
								   "<input name=codestpro".$li_i." type=hidden id=codestpro".$li_i." value='".$ls_codestpro."'><input name=spgcuenta".$li_i." type=hidden id=spgcuenta".$li_i." value='".$ls_spgcuenta."'><input name=estcla".$li_i." type=hidden id=estcla".$li_i." value='".$ls_estcla."'>";
		$la_objectcargos[$li_i][3]="<input name=txtmoncar".$li_i." type=text id=txtmoncar".$li_i." class=sin-borde size=20 style= text-align:center value='".$io_funcsob->uf_convertir_numerocadena($ld_result)."' readonly>";
		$la_objectcargos[$li_i][4]="<a href=javascript:ue_removercargo(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
	}	
	$la_objectcargos[$li_filascargos][1]="<input name=txtcodcar".$li_filascargos." type=text id=txtcodcar".$li_filascargos." class=sin-borde style= text-align:center size=5 readonly><input name=formu".$li_filascargos." type=hidden id=formu".$li_filascargos.">";
	$la_objectcargos[$li_filascargos][2]="<input name=txtnomcar".$li_filascargos." type=text id=txtnomcar".$li_filascargos." class=sin-borde style= text-align:left size=50 readonly>".
										 "<input name=codestpro".$li_filascargos." type=hidden id=codestpro".$li_filascargos."><input name=spgcuenta".$li_filascargos." type=hidden id=spgcuenta".$li_filascargos."><input name=estcla".$li_filascargos." type=hidden id=estcla".$li_filascargos.">";
	$la_objectcargos[$li_filascargos][3]="<input name=txtmoncar".$li_filascargos." type=text id=txtmoncar".$li_filascargos." class=sin-borde style= text-align:center size=20 readonly>";
	$la_objectcargos[$li_filascargos][4]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";


     $ld_subtotal=$ld_subtotpar-$ld_basimpval;
	 $ld_resultado=$ld_basimpval+$ld_subtot+$ld_subtotal;  
	 $ls_subtot=$io_funcsob->uf_convertir_numerocadena($ld_resultado);
	 $ls_montotval=$io_funcsob->uf_convertir_numerocadena($ld_resultado);
}

/***************************************************************************************************************************************************************************/

/*******************************************************REMOVER CAMPO EN GRID CARGOS********************************************************************************************************************/
elseif($ls_operacion=="ue_removercargo")
{
    $ls_subtotpar=$_POST["txtsubtotpar"]; 
	$ls_basimpval=$_POST["txtbasimpval"];
	$ld_basimpval=$io_funcsob->uf_convertir_cadenanumero($ls_basimpval);
	$ld_subtotpar=$io_funcsob->uf_convertir_cadenanumero($ls_subtotpar);
	$ld_subtot=0;
	        
	$li_filascargos=$_POST["filascargos"];
	$li_filascargos=$li_filascargos-1;
	$li_removercargo=$_POST["hidremovercargo"];
	$li_temp=0;
	for($li_i=1;$li_i<=$li_filascargos;$li_i++)
	{
		if($li_i!=$li_removercargo)
		{		
			$li_temp=$li_temp+1;
			$ls_codigo=$_POST["txtcodcar".$li_i];
			$ls_nombre=$_POST["txtnomcar".$li_i];
			$ls_formula=$_POST["formu".$li_i];
			$ls_codestpro=$_POST["codestpro".$li_i];
			$ls_spgcuenta=$_POST["spgcuenta".$li_i];
			$ls_estcla=$_POST["estcla".$li_i];
			$ld_result=$io_evalform->uf_evaluar($ls_formula,$ld_basimpval,$lb_valido);
		    $ld_subtot=$ld_subtot+$ld_result;
			$la_objectcargos[$li_temp][1]="<input name=txtcodcar".$li_temp." type=text id=txtcodcar".$li_temp." class=sin-borde style= text-align:center size=5 value='".$ls_codigo."' readonly><input name=formu".$li_temp." type=hidden id=formu".$li_temp." value='".$ls_formula."'>";
			$la_objectcargos[$li_temp][2]="<input name=txtnomcar".$li_temp." type=text id=txtnomcar".$li_temp." class=sin-borde style= text-align:left size=60 value='".$ls_nombre."' readonly >".
										  "<input name=codestpro".$li_temp." type=hidden id=codestpro".$li_temp." value='".$ls_codestpro."' ><input name=spgcuenta".$li_temp." type=hidden id=spgcuenta".$li_temp." value='".$ls_spgcuenta."' ><input name=estcla".$li_temp." type=hidden id=estcla".$li_temp." value='".$ls_estcla."'>";
			$la_objectcargos[$li_temp][3]="<input name=txtmoncar".$li_temp." type=text id=txtmoncar".$li_temp." class=sin-borde size=20 style= text-align:center value='".$io_funcsob->uf_convertir_numerocadena($ld_result)."' readonly>";
			$la_objectcargos[$li_temp][4]="<a href=javascript:ue_removercargo(".$li_temp.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
			
		}
	}
	$la_objectcargos[$li_filascargos][1]="<input name=txtcodcar".$li_filascargos." type=text id=txtcodcar".$li_filascargos." class=sin-borde style= text-align:center size=5 readonly><input name=formu".$li_filascargos." type=hidden id=formu".$li_filascargos.">";
	$la_objectcargos[$li_filascargos][2]="<input name=txtnomcar".$li_filascargos." type=text id=txtnomcar".$li_filascargos." class=sin-borde style= text-align:left size=50 readonly>".
										"<input name=codestpro".$li_filascargos." type=hidden id=codestpro".$li_filascargos."><input name=spgcuenta".$li_filascargos." type=hidden id=spgcuenta".$li_filascargos."><input name=estcla".$li_filascargos." type=hidden id=estcla".$li_filascargos.">";
	$la_objectcargos[$li_filascargos][3]="<input name=txtmoncar".$li_filascargos." type=text id=txtmoncar".$li_filascargos." class=sin-borde style= text-align:center size=20 readonly>";
	$la_objectcargos[$li_filascargos][4]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";

	 $ld_subtotal=$ld_subtotpar-$ld_basimpval;
	 $ld_resultado=$ld_basimpval+$ld_subtot+$ld_subtotal;  
	 $ls_subtot=$io_funcsob->uf_convertir_numerocadena($ld_resultado);
	 $ls_montotval=$io_funcsob->uf_convertir_numerocadena($ld_resultado);
	
}

/***************************************************************************************************************************************************************************/

/*******************************************INSERCION DE REGISTRO EN BD*******************************************************************************************************************************/
elseif($ls_operacion=="ue_guardar")
{
  $ld_fecha=$io_function->uf_convertirdatetobd($ls_fecha);
  $ld_fecinival=$io_function->uf_convertirdatetobd($ls_fecinival);
  $ld_fecfinval=$io_function->uf_convertirdatetobd($ls_fecfinval);
  $io_valuacion->io_sql->begin_transaction();
  $ls_codvalaux=$ls_codval;
  $lb_valido=$io_valuacion->uf_guardar_valuacion($ls_codval,$ls_codcon,$ld_fecha,$ld_fecinival,$ld_fecfinval,$ls_obsval,$ls_amoactual,$ls_amoobs,$ls_amoant,
  											     $ls_amotot,$ls_amores,$ls_basimpval,$ls_montotval,$ls_subtotpar,$ls_totreten,$ls_subtot,$la_seguridad,$ls_chk);
  if($lb_valido)
  {
    /************************************PARTIDAS*******************************************/
	$li_partidas=1;
    $la_partidas["codpar"][1]="";
	$la_partidas["canteje"][1]="";
	$la_partidas["cant"][1]="";
	$la_partidas["preref"][1]="";
	$la_partidas["preval"][1]="";
	$la_partidas["codasi"][1]="";
	$la_partidas["codobr"][1]="";
	
    for ($li_i=1;$li_i<$li_filaspartidas;$li_i++)
     {
	   if(!empty($_POST["flagpar".$li_i]))
	   {
	   	$la_partidas["codpar"][$li_partidas]=$_POST["txtcodpar".$li_i];
		$la_partidas["canteje"][$li_partidas]=$_POST["canpareje".$li_i];
	    $la_partidas["cant"][$li_partidas]=$_POST["txtcantpar".$li_i];
		$la_partidas["preref"][$li_partidas]=$_POST["txtpreuni".$li_i];
	    $la_partidas["preval"][$li_partidas]=$_POST["txtpreunimod".$li_i];
		$la_partidas["codasi"][$li_partidas]=$_POST["codasi".$li_i];
	    $la_partidas["codobr"][$li_partidas]=$_POST["codobr".$li_i];
		$li_partidas++;
	   }
	 }
	$lb_valido=$io_valuacion->uf_update_dtpartidas($ls_codval,$ls_codcon,$la_partidas,$li_partidas,$la_seguridad); 
   /*****************************************************************************************/ 	 
	if($lb_valido)
	{
		/***********************************CARGOS************************************************/ 	 
		$la_cargos["codcar"][1]="";
		$la_cargos["monto"][1]="";
		$la_cargos["formula"][1]="";
		for ($li_i=1;$li_i<$li_filascargos;$li_i++)
		{
			$la_cargos["codcar"][$li_i]=$_POST["txtcodcar".$li_i];
			$la_cargos["monto"][$li_i]=$_POST["txtmoncar".$li_i];
			$la_cargos["formula"][$li_i]=$_POST["formu".$li_i];
			$la_cargos["codestpro"][$li_i]=$_POST["codestpro".$li_i];
			$la_cargos["spgcuenta"][$li_i]=$_POST["spgcuenta".$li_i];
			$la_cargos["estcla"][$li_i]=$_POST["estcla".$li_i];

		}
		$lb_valido=$io_valuacion->uf_update_dtcargos($ls_codval,$ls_codcon,$ls_basimpval,$la_cargos,$li_filascargos,$la_seguridad); 
		/*****************************************************************************************/ 	 	 
	 }
	if($lb_valido)
	{  
	  /***********************************RETENCIONES*******************************************/ 	 	
		$la_retenciones["codret"][1]="";
		$la_retenciones["monret"][1]="";
		$la_retenciones["montotret"][1]="";
		
		for ($li_i=1;$li_i<$li_filasretenciones;$li_i++)
		 {
		   $la_retenciones["codret"][$li_i]=$_POST["txtcodret".$li_i];
		   $la_retenciones["monret"][$li_i]=$_POST["txtmonret".$li_i];
		   $la_retenciones["montotret"][$li_i]=$_POST["txttotret".$li_i];
		 }
		 $lb_valido=$io_valuacion->uf_update_retenciones($ls_codval,$ls_codcon,$la_retenciones,$li_filasretenciones,$la_seguridad);
	  /*****************************************************************************************/ 	    
	 }
  }
  if($lb_valido)
  {
		if( $ls_codvalaux!=$ls_codval)
		{
			$io_msg->message("Se le asigno un nuevo numero de valuacion. ".$ls_codval);
		}
		print "<script language=javascript>";
		print "document.form1.hidstatus.value='C'";
		print "</script>";
	 
	 $io_msg->message("La operacion se proceso exitosamente");
	 $io_valuacion->io_sql->commit();
	 $ls_hidstatus="C";
  }
  else
  {
	 $io_msg->message("Ocurrio un error al procesar la operacion");
	 $io_valuacion->io_sql->rollback();
  }
/*  print "<script language=javascript>";
  print "location.href=location";
  print "</script>";
*/}
/***************************************************************************************************************************************************************************/
elseif($ls_operacion=="PROCESAR")
{
	$lb_valido=$io_valuacion->uf_validar_contabilizado($ls_hidcodasi);
	if($lb_valido)
	{
		$ld_montotval=$io_funcsob->uf_convertir_cadenanumero($ls_montotval);
		$ld_totreten=$io_funcsob->uf_convertir_cadenanumero($ls_totreten);
		$ld_subtot=$io_funcsob->uf_convertir_cadenanumero($ls_subtot);
		$ld_basimpval=$io_funcsob->uf_convertir_cadenanumero($ls_basimpval);
		$lb_valido=$io_valuacion->uf_procesar_recepcion_documentos($ls_codcon,$ls_codtipdoc,$ls_obsval,$ls_fecha,$ld_montotval,$ld_totreten,$ld_subtot,
																   $ls_codcon,$ld_basimpval,$ls_hidcodasi,$ls_codval,$la_seguridad);
																   
	}
	else
	{
		 $io_msg->message("La Asignacion asociada debe estar contabilizada");
	}
}
/*******************************************BUSCAR DATOS DE CONTRATO*********************************************************************/
elseif($ls_operacion=="ue_datcontrato")
{
   $ls_codcon=$_POST["txtcodcon"];
   $io_valuacion->uf_select_contrato($ls_codcon,&$la_contrato);
   $io_valuacion->uf_select_anticipos($ls_codcon,&$ls_totant);
   $io_valuacion->uf_select_variaciones($ls_codcon,&$ld_aum,1);
   $io_valuacion->uf_select_variaciones($ls_codcon,&$ld_dis,2);
   $ls_desobr=$la_contrato["desobr"][1];
   $ls_puncue=$la_contrato["codasi"][1];
   $ls_estcon=$io_funcsob->uf_convertir_numeroestado ($la_contrato["estcon"][1]);
   $ls_moncon=$la_contrato["monto"][1];
   $ls_feccon=$io_function->uf_convertirfecmostrar($la_contrato["feccon"][1]);
   $ls_totcon=$la_contrato["monto"][1]+$ld_aum+$ld_dis;
   $lb_validop=$io_valuacion->uf_select_partidasasignadas($ls_codcon,&$la_partidas,&$li_totalfilas);
    require_once("../shared/class_folder/class_funciones_db.php");
	require_once ("../shared/class_folder/sigesp_include.php");		
	$io_include=new sigesp_include();
	$io_connect=$io_include->uf_conectar();
	$io_funcdb=new class_funciones_db($io_connect);
	$la_empresa=$_SESSION["la_empresa"];
//	$io_valuacion->uf_select_newcodigo($ls_codcon,&$ls_codval);
	require_once("../shared/class_folder/sigesp_c_generar_consecutivo.php");
	$io_keygen= new sigesp_c_generar_consecutivo();
	$ls_codval= $io_keygen->uf_generar_numero_nuevo("SOB","sob_valuacion","codval","SOBVAL",3,"","codcon",$ls_codcon);
	$ls_fecinival="";
	$ls_fecfinval="";
	$ls_obsval="";
	$ls_fecha=date("d/m/Y");
	$ls_estadoval="EMITIDO";
	$lb_flag=$io_valuacion->uf_select_valanterior($ls_codcon,$ls_codval,$la_data);
    if($lb_flag)
	{
	  $ls_amoant=$io_funcsob->uf_convertir_numerocadena($la_data["amoval"][1]);
	  $ls_amotot=$io_funcsob->uf_convertir_numerocadena($la_data["amototval"][1]);
	  $ls_hidamototbd=$la_data["amototval"][1];
	  $ls_amores=$io_funcsob->uf_convertir_numerocadena($la_data["amoresval"][1]);
	  
	}
	else
	{
      $ls_hidamoant="0,00";
	  $ls_hidamotot="0,00";
 	  $ls_hidamores="0,00";
	}	
	if($lb_validop)
	{
	$io_datastore->data=$la_partidas;
	$li_filaspartidas=$io_datastore->getRowCount("codpar");
	for($li_i=1;$li_i<=$li_filaspartidas;$li_i++)
	{
		    $ls_codigo=$io_datastore->getValue("codpar",$li_i);
			$ls_nombre=$io_datastore->getValue("nompar",$li_i);
			$ls_unidad=$io_datastore->getValue("nomuni",$li_i);
			$ls_preuni=$io_datastore->getValue("prerefparasi",$li_i);
			$ls_preunimod=$io_datastore->getValue("preparasi",$li_i);
			$ls_canttot=$io_datastore->getValue("canxeje",$li_i);
			$ls_canpareje=$io_datastore->getValue("canasipareje",$li_i);
			$ls_codasi=$io_datastore->getValue("codasi",$li_i);
			$ls_codobr=$io_datastore->getValue("codobr",$li_i);
			$la_objectpartidas[$li_i][1]="<input type=checkbox name=flagpar".$li_i." value=1 class=sin-borde onClick='javascript: ue_marcartodo(".$li_i.");' >";
	        $la_objectpartidas[$li_i][2]="<input name=txtcodpar".$li_i." type=text id=txtcodpar".$li_i." value='".$ls_codigo."' class=sin-borde style= text-align:center size=8 readonly><input name=canpareje".$li_i." type=hidden id=canpareje".$li_i." value='".$ls_canpareje."'><input name=codasi".$li_i." type=hidden id=codasi".$li_i." value='".$ls_codasi."'><input name=codobr".$li_i." type=hidden id=codobr".$li_i." value='".$ls_codobr."'>";
	        $la_objectpartidas[$li_i][3]="<input name=txtnompar".$li_i." type=text id=txtnompar".$li_i." value='".$ls_nombre."' class=sin-borde style= text-align:left size=25 readonly>";
	        $la_objectpartidas[$li_i][4]="<input name=txtnomuni".$li_i." type=text id=txtnomuni".$li_i." value='".$ls_unidad."' class=sin-borde size=5 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][5]="<input name=txtpreuni".$li_i." type=text id=txtpreuni".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_preuni)."' class=sin-borde size=15 style= text-align:center readonly>";
			$la_objectpartidas[$li_i][6]="<input name=txtpreunimod".$li_i." type=text id=txtpreunimod".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_preunimod)."' class=sin-borde size=15 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][7]="<input name=txtcanttot".$li_i." type=text id=txtcanttot".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_canttot)."' class=sin-borde size=5 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][8]="<input name=txtcantpar".$li_i." type=text id=txtcantpar".$li_i." class=sin-borde size=5 style= text-align:center onKeyPress=return(currencyFormat(this,'.',',',event))>";
	        $la_objectpartidas[$li_i][9]="<input name=txttotal".$li_i." type=text id=txttotal".$li_i." class=sin-borde size=15 style= text-align:center readonly>";	
			$la_objectpartidas[$li_i][10]="<input name=txtmonaju".$li_i."  type=text id=txtmonaju".$li_i."     class=sin-borde  style=text-align:right  size=6 onKeyPress=return(ue_formatonumero_negativo(this,'.',',',event)); value='0,00' onBlur=javascript:ue_ajustar(".$li_i.");>";
			if($ls_canttot==0)
			{
				$la_objectpartidas[$li_i][1]="<input type=checkbox name=flagpar".$li_i." value=1 class=sin-borde disabled>";
				$la_objectpartidas[$li_i][2]="<input name=txtcodpar".$li_i." type=text id=txtcodpar".$li_i." value='".$ls_codigo."' class=sin-borde style= text-align:center size=8 readonly><input name=canpareje".$li_i." type=hidden id=canpareje".$li_i." value='".$ls_canpareje."'><input name=codasi".$li_i." type=hidden id=codasi".$li_i." value='".$ls_codasi."'><input name=codobr".$li_i." type=hidden id=codobr".$li_i." value='".$ls_codobr."'>";
				$la_objectpartidas[$li_i][3]="<input name=txtnompar".$li_i." type=text id=txtnompar".$li_i." value='".$ls_nombre."' class=sin-borde style= text-align:left size=25 readonly>";
				$la_objectpartidas[$li_i][4]="<input name=txtnomuni".$li_i." type=text id=txtnomuni".$li_i." value='".$ls_unidad."' class=sin-borde size=5 style= text-align:center readonly>";
				$la_objectpartidas[$li_i][5]="<input name=txtpreuni".$li_i." type=text id=txtpreuni".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_preuni)."' class=sin-borde size=15 style= text-align:center readonly>";
				$la_objectpartidas[$li_i][6]="<input name=txtpreunimod".$li_i." type=text id=txtpreunimod".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_preuni)."' class=sin-borde size=15 style= text-align:center readonly>";
				$la_objectpartidas[$li_i][7]="<input name=txtcanttot".$li_i." type=text id=txtcanttot".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_canttot)."' class=sin-borde size=5 style= text-align:center readonly>";
				$la_objectpartidas[$li_i][8]="<input name=txtcantpar".$li_i." type=text id=txtcantpar".$li_i." class=sin-borde size=5 style= text-align:center readonly>";
				$la_objectpartidas[$li_i][9]="<input name=txttotal".$li_i." type=text id=txttotal".$li_i." class=sin-borde size=15 style= text-align:center readonly>";	
				$la_objectpartidas[$li_i][10]="<input name=txtmonaju".$li_i."  type=text id=txtmonaju".$li_i."     class=sin-borde  style=text-align:right  size=6 onKeyPress=return(ue_formatonumero_negativo(this,'.',',',event)); value='0,00' onBlur=javascript:ue_ajustar(".$li_i.");>";
			}
	}
	$li_filaspartidas=$li_filaspartidas+1;	
	$la_objectpartidas[$li_filaspartidas][1]="<input type=checkbox name=flagpar".$li_filaspartidas." value=1 disabled class=sin-borde>";
	$la_objectpartidas[$li_filaspartidas][2]="<input name=txtcodpar".$li_filaspartidas." type=text id=txtcodpar".$li_filaspartidas." class=sin-borde style= text-align:center size=8 readonly><input name=canpareje".$li_filaspartidas." type=hidden id=canpareje".$li_filaspartidas."><input name=codasi".$li_filaspartidas." type=hidden id=codasi".$li_filaspartidas."><input name=codobr".$li_filaspartidas." type=hidden id=codobr".$li_filaspartidas.">";
	$la_objectpartidas[$li_filaspartidas][3]="<input name=txtnompar".$li_filaspartidas." type=text id=txtnompar".$li_filaspartidas." class=sin-borde style= text-align:left size=25 readonly>";
	$la_objectpartidas[$li_filaspartidas][4]="<input name=txtnomuni".$li_filaspartidas." type=text id=txtnomuni".$li_filaspartidas." class=sin-borde size=5 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][5]="<input name=txtpreuni".$li_filaspartidas." type=text id=txtpreuni".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][6]="<input name=txtpreunimod".$li_filaspartidas." type=text id=txtpreunimod".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][7]="<input name=txtcanttot".$li_filaspartidas." type=text id=txtcanttot".$li_filaspartidas." class=sin-borde size=5 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][8]="<input name=txtcantpar".$li_filaspartidas." type=text id=txtcantpar".$li_filaspartidas." class=sin-borde size=5 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][9]="<input name=txttotal".$li_filaspartidas." type=text id=txttotal".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][10]="<input name=txtmonaju".$li_filaspartidas."  type=text id=txtmonaju".$li_filaspartidas."     class=sin-borde  style=text-align:right  size=6 onKeyPress=return(ue_formatonumero_negativo(this,'.',',',event)); value='0,00'>";
	}
}
/***************************************************************************************************************************************************************************/

/*******************************************ANULAR UNA ASIGNACION*******************************************************************************************************************************/
elseif($ls_operacion=="ue_anular")
{   
   $io_valuacion->uf_select_estado($ls_codval,&$ls_estasi);
   if(($ls_estasi==1)||($ls_estasi==6))
   { 
      for ($li_i=1;$li_i<$li_filaspartidas;$li_i++)
       {
	    if(!empty($_POST["flagpar".$li_i]))
	     {
	    	$ls_codparG=$_POST["txtcodpar".$li_i];
	    	$ls_canejeG=$_POST["canpareje".$li_i];
	        $ls_canparG=$_POST["txtcantpar".$li_i];
	        $io_valuacion->uf_update_Actcantidaejecutada($ls_codasi,$ls_codparG,$ls_canparG,$ls_canejeG,$la_seguridad);
	     
	     }
	 }
     $io_valuacion->uf_update_estado($ls_codval,3,$la_seguridad);
	 $io_msg->message("Esta Valuacion fue Anulada!!");
   }
   else
   {
    $io_msg->message("Esta Valuacion no puede ser Anulada!!");
   }
  print "<script language=javascript>";
  print "location.href=location";
  print "</script>";
}
/***************************************************************************************************************************************************************************/

/*******************************************CARGAR DATOS DE LA VALUACION*******************************************************************************************************************************/
elseif($ls_operacion=="ue_cargarvaluacion")
{   
   $ls_codval=$_POST["txtcodval"];
   $ls_hidcodasi =$_POST["hidcodasi"];
   $io_valuacion->uf_select_anticipos($ls_codcon,&$ls_totant);
   /************************CARGANDO PARTIDAS*************************************************/ 
	$lb_validop=$io_valuacion->uf_select_allpartidas($ls_codval,$ls_hidcodasi,&$la_partidas,&$li_totalfilas);
	if($lb_validop)
	{
	$io_datastore->data=$la_partidas;
	$li_filaspartidas=$io_datastore->getRowCount("codpar");
	for($li_i=1;$li_i<=$li_filaspartidas;$li_i++)
	{
		    $ls_codigo=$io_datastore->getValue("codpar",$li_i);
			$ls_nombre=$io_datastore->getValue("nompar",$li_i);
			$ls_unidad=$io_datastore->getValue("nomuni",$li_i);
			$ls_preuni=$io_datastore->getValue("preparasi",$li_i);
			$ls_preunimod=$io_datastore->getValue("prerefparasi",$li_i);
			$ls_canttot=$io_datastore->getValue("canxeje",$li_i);
			$ls_canpareje=$io_datastore->getValue("canasipareje",$li_i);
			$ls_canvalpar=$io_datastore->getValue("canvalpar",$li_i);
			$ls_codasi=$io_datastore->getValue("codasi",$li_i);
			$ls_codobr=$io_datastore->getValue("codobr",$li_i);
			
			if($ls_canvalpar=="")
			{
			 $la_objectpartidas[$li_i][1]="<input type=checkbox name=flagpar".$li_i." value=1  class=sin-borde onClick='javascript: ue_marcartodo(".$li_i.");' >";
			 $ls_total="0,00";
			}
			else
			{
			 $la_objectpartidas[$li_i][1]="<input type=checkbox name=flagpar".$li_i." value=1 checked class=sin-borde onClick='javascript: ue_marcartodo(".$li_i.");' >";
			 $ls_canttot=$ls_canttot+$ls_canvalpar;
			 $ls_total=$ls_canvalpar*$ls_preunimod;
			}
	        $la_objectpartidas[$li_i][2]="<input name=txtcodpar".$li_i." type=text id=txtcodpar".$li_i." value='".$ls_codigo."' class=sin-borde style= text-align:center size=8 readonly><input name=canpareje".$li_i." type=hidden id=canpareje".$li_i." value='".$ls_canpareje."'><input name=codasi".$li_i." type=hidden id=codasi".$li_i." value='".$ls_codasi."'><input name=codobr".$li_i." type=hidden id=codobr".$li_i." value='".$ls_codobr."'>";
	        $la_objectpartidas[$li_i][3]="<input name=txtnompar".$li_i." type=text id=txtnompar".$li_i." value='".$ls_nombre."' class=sin-borde style= text-align:left size=25 readonly>";
	        $la_objectpartidas[$li_i][4]="<input name=txtnomuni".$li_i." type=text id=txtnomuni".$li_i." value='".$ls_unidad."' class=sin-borde size=5 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][5]="<input name=txtpreuni".$li_i." type=text id=txtpreuni".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_preuni)."' class=sin-borde size=15 style= text-align:center readonly>";
			$la_objectpartidas[$li_i][6]="<input name=txtpreunimod".$li_i." type=text id=txtpreunimod".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_preunimod)."' class=sin-borde size=15 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][7]="<input name=txtcanttot".$li_i." type=text id=txtcanttot".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_canttot)."' class=sin-borde size=5 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][8]="<input name=txtcantpar".$li_i." type=text id=txtcantpar".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_canvalpar)."' class=sin-borde size=5 style= text-align:center onKeyPress=return(currencyFormat(this,'.',',',event))>";
	        $la_objectpartidas[$li_i][9]="<input name=txttotal".$li_i." type=text id=txttotal".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_total)."'  class=sin-borde size=15 style= text-align:center readonly>";	
			$la_objectpartidas[$li_i][10]="<input name=txtmonaju".$li_i."  type=text id=txtmonaju".$li_i."     class=sin-borde  style=text-align:right  size=6 onKeyPress=return(ue_formatonumero_negativo(this,'.',',',event)); value='0,00' onBlur=javascript:ue_ajustar(".$li_i.");>";
			if($ls_canttot==0)
			{
			 $la_objectpartidas[$li_i][1]="<input type=checkbox name=flagpar".$li_i." value=1 class=sin-borde disabled>";
	        $la_objectpartidas[$li_i][2]="<input name=txtcodpar".$li_i." type=text id=txtcodpar".$li_i." value='".$ls_codigo."' class=sin-borde style= text-align:center size=8 readonly><input name=canpareje".$li_i." type=hidden id=canpareje".$li_i." value='".$ls_canpareje."'><input name=codasi".$li_i." type=hidden id=codasi".$li_i." value='".$ls_codasi."'><input name=codobr".$li_i." type=hidden id=codobr".$li_i." value='".$ls_codobr."'>";
	        $la_objectpartidas[$li_i][3]="<input name=txtnompar".$li_i." type=text id=txtnompar".$li_i." value='".$ls_nombre."' class=sin-borde style= text-align:left size=25 readonly>";
	        $la_objectpartidas[$li_i][4]="<input name=txtnomuni".$li_i." type=text id=txtnomuni".$li_i." value='".$ls_unidad."' class=sin-borde size=5 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][5]="<input name=txtpreuni".$li_i." type=text id=txtpreuni".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_preuni)."' class=sin-borde size=15 style= text-align:center readonly>";
			$la_objectpartidas[$li_i][6]="<input name=txtpreunimod".$li_i." type=text id=txtpreunimod".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_preuni)."' class=sin-borde size=15 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][7]="<input name=txtcanttot".$li_i." type=text id=txtcanttot".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_canttot)."' class=sin-borde size=5 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][8]="<input name=txtcantpar".$li_i." type=text id=txtcantpar".$li_i." class=sin-borde size=5 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][9]="<input name=txttotal".$li_i." type=text id=txttotal".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_total)."' class=sin-borde size=15 style= text-align:center readonly>";	
			$la_objectpartidas[$li_i][10]="<input name=txtmonaju".$li_i."  type=text id=txtmonaju".$li_i."     class=sin-borde  style=text-align:right  size=6 onKeyPress=return(ue_formatonumero_negativo(this,'.',',',event)); value='0,00' onBlur=javascript:ue_ajustar(".$li_i.");>";
			}
	}
	$li_filaspartidas=$li_filaspartidas+1;	
	$la_objectpartidas[$li_filaspartidas][1]="<input type=checkbox name=flagpar".$li_filaspartidas." value=1 disabled class=sin-borde>";
	$la_objectpartidas[$li_filaspartidas][2]="<input name=txtcodpar".$li_filaspartidas." type=text id=txtcodpar".$li_filaspartidas." class=sin-borde style= text-align:center size=8 readonly><input name=canpareje".$li_filaspartidas." type=hidden id=canpareje".$li_filaspartidas."><input name=codasi".$li_filaspartidas." type=hidden id=codasi".$li_filaspartidas."><input name=codobr".$li_filaspartidas." type=hidden id=codobr".$li_filaspartidas.">";
	$la_objectpartidas[$li_filaspartidas][3]="<input name=txtnompar".$li_filaspartidas." type=text id=txtnompar".$li_filaspartidas." class=sin-borde style= text-align:left size=25 readonly>";
	$la_objectpartidas[$li_filaspartidas][4]="<input name=txtnomuni".$li_filaspartidas." type=text id=txtnomuni".$li_filaspartidas." class=sin-borde size=5 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][5]="<input name=txtpreuni".$li_filaspartidas." type=text id=txtpreuni".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][6]="<input name=txtpreunimod".$li_filaspartidas." type=text id=txtpreunimod".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][7]="<input name=txtcanttot".$li_filaspartidas." type=text id=txtcanttot".$li_filaspartidas." class=sin-borde size=5 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][8]="<input name=txtcantpar".$li_filaspartidas." type=text id=txtcantpar".$li_filaspartidas." class=sin-borde size=5 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][9]="<input name=txttotal".$li_filaspartidas." type=text id=txttotal".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][10]="<input name=txtmonaju".$li_filaspartidas."  type=text id=txtmonaju".$li_filaspartidas."     class=sin-borde  style=text-align:right  size=6 onKeyPress=return(ue_formatonumero_negativo(this,'.',',',event)); value='0,00'>";
	}
   /*****************************************************************************************/
   
   /************************CARGANDO CARGOS**************************************************/ 
    $lb_validoca=$io_valuacion->uf_select_cargos($ls_codval,$ls_codcon,$la_cargos,$li_totalfilas);
	if($lb_validoca)
	{
	$io_datastore->data=$la_cargos;
	$li_filascargos=$io_datastore->getRowCount("codcar");
	for($li_i=1;$li_i<=$li_filascargos;$li_i++)
	{
		$ls_codigo=$io_datastore->getValue("codcar",$li_i);
		$ls_nombre=$io_datastore->getValue("dencar",$li_i);
		$ls_moncar=$io_datastore->getValue("monto",$li_i);
		$ls_formula=$io_datastore->getValue("formula",$li_i);
		$ls_codestpro=$io_datastore->getValue("codestpro",$li_i);
		$ls_spgcuenta=$io_datastore->getValue("spgcuenta",$li_i);
		$ls_estcla=$io_datastore->getValue("estcla",$li_i);
		$la_objectcargos[$li_i][1]="<input name=txtcodcar".$li_i." type=text id=txtcodcar".$li_i." class=sin-borde style= text-align:center size=5 value='".$ls_codigo."' readonly>";
		$la_objectcargos[$li_i][2]="<input name=txtnomcar".$li_i." type=text id=txtnomcar".$li_i." class=sin-borde style= text-align:left size=60 value='".$ls_nombre."' readonly >".
								   "<input name=codestpro".$li_i." type=hidden id=codestpro".$li_i." value='".$ls_codestpro."'><input name=spgcuenta".$li_i." type=hidden id=spgcuenta".$li_i." value='".$ls_spgcuenta."'><input name=estcla".$li_i." type=hidden id=estcla".$li_i." value='".$ls_estcla."'>";
		$la_objectcargos[$li_i][3]="<input name=txtmoncar".$li_i." type=text id=txtmoncar".$li_i." class=sin-borde size=20 style= text-align:center value='".$io_funcsob->uf_convertir_numerocadena($ls_moncar)."' readonly><input name=formu".$li_i." type=hidden id=formu".$li_i." value='".$ls_formula."'>";
		$la_objectcargos[$li_i][4]="<a href=javascript:ue_removercargo(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
	}	
	$li_filascargos=$li_filascargos+1;
	$la_objectcargos[$li_filascargos][1]="<input name=txtcodcar".$li_filascargos." type=text id=txtcodcar".$li_filascargos." class=sin-borde style= text-align:center size=5 >";
	$la_objectcargos[$li_filascargos][2]="<input name=txtnomcar".$li_filascargos." type=text id=txtnomcar".$li_filascargos." class=sin-borde style= text-align:left size=50 >".
										"<input name=codestpro".$li_filascargos." type=hidden id=codestpro".$li_filascargos."><input name=spgcuenta".$li_filascargos." type=hidden id=spgcuenta".$li_filascargos."><input name=estcla".$li_filascargos." type=hidden id=estcla".$li_filascargos.">";
	$la_objectcargos[$li_filascargos][3]="<input name=txtmoncar".$li_filascargos." type=text id=txtmoncar".$li_filascargos." class=sin-borde size=20 style= text-align:center><input name=formula".$li_filascargos." type=hidden id=formula".$li_filascargos.">";
	$la_objectcargos[$li_filascargos][4]="<input name=txtvacio".$li_filascargos." type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	}
	/*****************************************************************************************/
   
   /************************CARGANDO RETENCIONES**************************************************/ 
	$lb_validor=$io_valuacion->uf_select_retenciones($ls_codval,$ls_codcon,$la_retenciones,$li_totalfilas);
	if($lb_validor)
	{
	 $io_datastore->data=$la_retenciones;
	 $li_filasretenciones=$io_datastore->getRowCount("codded");
	 for($li_i=1;$li_i<=$li_filasretenciones;$li_i++)
		{
			$ls_codigo=$io_datastore->getValue("codded",$li_i);
			$ls_descripcion=$io_datastore->getValue("dended",$li_i);
			$ls_cuenta=$io_datastore->getValue("sc_cuenta",$li_i);
			$ls_deduccion=$io_datastore->getValue("monded",$li_i);
			$ls_monret=$io_datastore->getValue("monret",$li_i);
			$ls_totret=$io_datastore->getValue("montotret",$li_i);
			$ls_formula=$io_datastore->getValue("formula",$li_i);
			$la_objectretenciones[$li_i][1]="<input name=txtcodret".$li_i." type=text id=txtcodret".$li_i." class=sin-borde value='".$ls_codigo."' style= text-align:center size=5 readonly>";
			$la_objectretenciones[$li_i][2]="<input name=txtdesret".$li_i." type=text id=txtdesret".$li_i." class=sin-borde value='".$ls_descripcion."' style= text-align:left size=30 readonly>";
			$la_objectretenciones[$li_i][3]="<input name=txtcueret".$li_i." type=text id=txtcueret".$li_i." class=sin-borde value='".$ls_cuenta."' style= text-align:left size=20 readonly>";
			$la_objectretenciones[$li_i][4]="<input name=txtdedret".$li_i." type=text id=txtdedret".$li_i." class=sin-borde value='".$io_funcsob->uf_convertir_numerocadena($ls_deduccion)."' style= text-align:left size=15 readonly><input name=formula".$li_i." type=hidden id=formula".$li_i." value='".$ls_formula."'>";
			$la_objectretenciones[$li_i][5]="<input name=txtmonret".$li_i." type=text id=txtmonret".$li_i." class=sin-borde value='".$io_funcsob->uf_convertir_numerocadena($ls_monret)."' style= text-align:left size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=ue_calcretencion(this);>";
			$la_objectretenciones[$li_i][6]="<input name=txttotret".$li_i." type=text id=txttotret".$li_i." class=sin-borde value='".$io_funcsob->uf_convertir_numerocadena($ls_totret)."' style= text-align:left size=15 readonly>";
		    $la_objectretenciones[$li_i][7]="<a href=javascript:ue_removerretenciones(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
		}
		$li_filasretenciones=$li_filasretenciones+1;	
		$la_objectretenciones[$li_filasretenciones][1]="<input name=txtcodret".$li_filasretenciones." type=text id=txtcodret".$li_filasretenciones." class=sin-borde style= text-align:center size=5 readonly>";
		$la_objectretenciones[$li_filasretenciones][2]="<input name=txtdesret".$li_filasretenciones." type=text id=txtdesret".$li_filasretenciones." class=sin-borde style= text-align:left size=30 readonly>";
		$la_objectretenciones[$li_filasretenciones][3]="<input name=txtcueret".$li_filasretenciones." type=text id=txtcueret".$li_filasretenciones." class=sin-borde  style= text-align:center size=20 readonly><input name=formula".$li_filasretenciones." type=hidden id=formula".$li_filasretenciones.">";
		$la_objectretenciones[$li_filasretenciones][4]="<input name=txtdedret".$li_filasretenciones." type=text id=txtdedret".$li_filasretenciones." class=sin-borde  style= text-align:center size=15 readonly>";
		$la_objectretenciones[$li_filasretenciones][5]="<input name=txtmonret".$li_filasretenciones." type=text id=txtmonret".$li_filasretenciones." class=sin-borde style= text-align:left size=15 readonly>";
		$la_objectretenciones[$li_filasretenciones][6]="<input name=txttotret".$li_filasretenciones." type=text id=txttotret".$li_filasretenciones." class=sin-borde style= text-align:left size=15 readonly>";
		$la_objectretenciones[$li_filasretenciones][7]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
       }
	/*****************************************************************************************/
}
/***************************************************************************************************************************************************************************/

/*******************************************CALCULAR RETENCION DE LA VALUACION*******************************************************************************************************************************/
elseif($ls_operacion=="ue_calcretencion")
{   
  $ls_subtot=$_POST["txtsubtot"];
  $ld_subtot=$io_funcsob->uf_convertir_cadenanumero($ls_subtot);
  $ld_acum=0;
  
  for($li_i=1;$li_i<$li_filasretenciones;$li_i++)
	{
		$ls_codigo=$_POST["txtcodret".$li_i];
		$ls_descripcion=$_POST["txtdesret".$li_i];
		$ls_cuenta=$_POST["txtcueret".$li_i];
		$ls_deduccion=$_POST["txtdedret".$li_i];
		$ls_monret=$_POST["txtmonret".$li_i];
		$ls_formula=$_POST["formula".$li_i];
		$ld_monret=$io_funcsob->uf_convertir_cadenanumero($ls_monret);
		$ld_result=$io_evalform->uf_evaluar($ls_formula,$ld_monret,$lb_valido);
		$ld_acum=$ld_acum+$ld_result;
		$ls_totret=$io_funcsob->uf_convertir_numerocadena($ld_result);
		$la_objectretenciones[$li_i][1]="<input name=txtcodret".$li_i." type=text id=txtcodret".$li_i." class=sin-borde value='".$ls_codigo."' style= text-align:center size=5 readonly>";
		$la_objectretenciones[$li_i][2]="<input name=txtdesret".$li_i." type=text id=txtdesret".$li_i." class=sin-borde value='".$ls_descripcion."' style= text-align:left size=30 readonly>";
		$la_objectretenciones[$li_i][3]="<input name=txtcueret".$li_i." type=text id=txtcueret".$li_i." class=sin-borde value='".$ls_cuenta."' style= text-align:center size=20 readonly>";
		$la_objectretenciones[$li_i][4]="<input name=txtdedret".$li_i." type=text id=txtdedret".$li_i." class=sin-borde value='".$ls_deduccion."' style= text-align:center size=15 readonly><input name=formula".$li_i." type=hidden id=formula".$li_i." value='".$ls_formula."'>";
		$la_objectretenciones[$li_i][5]="<input name=txtmonret".$li_i." type=text id=txtmonret".$li_i." class=sin-borde value='".$ls_monret."' style= text-align:center size=15 onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=ue_calcretencion(this);>";
		$la_objectretenciones[$li_i][6]="<input name=txttotret".$li_i." type=text id=txttotret".$li_i." class=sin-borde value='".$ls_totret."' style= text-align:center size=15 readonly>";
		$la_objectretenciones[$li_i][7]="<a href=javascript:ue_removerretenciones(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
 	}	 
	$la_objectretenciones[$li_filasretenciones][1]="<input name=txtcodret".$li_filasretenciones." type=text id=txtcodret".$li_filasretenciones." class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectretenciones[$li_filasretenciones][2]="<input name=txtdesret".$li_filasretenciones." type=text id=txtdesret".$li_filasretenciones." class=sin-borde style= text-align:left size=30 readonly>";
	$la_objectretenciones[$li_filasretenciones][3]="<input name=txtcueret".$li_filasretenciones." type=text id=txtcueret".$li_filasretenciones." class=sin-borde  style= text-align:center size=20 readonly><input name=formula".$li_filasretenciones." type=hidden id=formula".$li_filasretenciones.">";
    $la_objectretenciones[$li_filasretenciones][4]="<input name=txtdedret".$li_filasretenciones." type=text id=txtdedret".$li_filasretenciones." class=sin-borde  style= text-align:center size=15 readonly>";
	$la_objectretenciones[$li_filasretenciones][5]="<input name=txtmonret".$li_filasretenciones." type=text id=txtmonret".$li_filasretenciones." class=sin-borde style= text-align:left size=15 readonly>";
	$la_objectretenciones[$li_filasretenciones][6]="<input name=txttotret".$li_filasretenciones." type=text id=txttotret".$li_filasretenciones." class=sin-borde style= text-align:left size=15 readonly>";
	$la_objectretenciones[$li_filasretenciones][7]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	
	if($ld_acum<$ld_subtot)
	{
	 $ls_totreten=$io_funcsob->uf_convertir_numerocadena($ld_acum);
	 $ld_montotval=$ld_subtot-$ld_acum;
	 $ls_montotval=$io_funcsob->uf_convertir_numerocadena($ld_montotval);
	}
    else
	{
	 $io_msg->message("El total en retenciones supera el subtotal acumulado!!");
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
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a><!--img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20">--><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
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
  <!-- Estos son los hidden de Datos del Contrato-->
  <input name="hiddesobr" type="hidden" id="hiddesobr" value="<?php print $ls_desobr; ?>">
  <input name="hidpuncue" type="hidden" id="hidpuncue" value="<?php print $ls_puncue; ?>">
  <input name="hidestcon" type="hidden" id="hidestcon" value="<?php print $ls_estcon; ?>">
  <input name="hidmoncon" type="hidden" id="hidmoncon" value="<?php print $ls_moncon; ?>">
  <input name="hidfeccon" type="hidden" id="hidfeccon" value="<?php print $ls_feccon; ?>">
  <input name="hidtotcon" type="hidden" id="hidtotcon" value="<?php print $ls_totcon; ?>">
  <input name="hidtotant" type="hidden" id="hidtotant" value="<?php print $ls_totant; ?>">  
  <input name="hidcodasi" type="hidden" id="hidcodasi" value="<?php print $ls_hidcodasi; ?>">  
  <input name="hidestapr" type="hidden" id="hidestapr" value="<?php print $ls_estapr; ?>">
  <table width="780" border="0" align="center" cellpadding="0" cellspacing="0"  class="contorno">
      <tr class="formato-blanco">
        <th colspan="6" class="titulo-celdanew" scope="col">Datos del Contrato</th>
      </tr>
      <tr class="formato-blanco">
        <th colspan="6" scope="col">&nbsp;</th>
      </tr>
       <tr class="formato-blanco">
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right">Contrato</div></td>
        <td colspan="4"><input name="txtcodcon" type="text" id="txtcodcon" style="text-align:center " value="<?php print $ls_codcon; ?>" size="15" maxlength="12" readonly="true">
        <a href="javascript:ue_catcontrato();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>         </td>
      </tr>
      <tr class="formato-blanco">
      <td height="13" colspan="7" align="center" valign="top" class="sin-borde"><div align="right"><a href="javascript:ue_uf_mostrar_ocultar_obra();">&nbsp;&nbsp;<img src="../shared/imagebank/mas.gif" width="9" height="17" border="0"></a><a href="javascript:uf_mostrar_ocultar_contrato();">Datos del Contrato&nbsp;&nbsp;&nbsp;&nbsp; </a>
				   </div></td>
    </tr>
	   <?
	   $ls_codcon=$_POST["txtcodcon"];
	   $io_valuacion->uf_select_contrato($ls_codcon,&$la_contrato);
	   $io_valuacion->uf_select_anticipos($ls_codcon,&$ls_totant);
	   $io_valuacion->uf_select_variaciones($ls_codcon,&$ld_aum,1);
	   $io_valuacion->uf_select_variaciones($ls_codcon,&$ld_dis,2);
	   $ls_desobr=$la_contrato["desobr"][1];
	   $ls_puncue=$la_contrato["codasi"][1];
	   $ls_estcon=$io_funcsob->uf_convertir_numeroestado ($la_contrato["estcon"][1]);
	   $ls_moncon=$la_contrato["monto"][1];
	   $ls_feccon=$io_function->uf_convertirfecmostrar($la_contrato["feccon"][1]);
	   $ls_totcon=$la_contrato["monto"][1]+$ld_aum+$ld_dis;
	    if($ls_opemostrar=="MOSTRAR")
		 { 
	    ?>
      <tr class="formato-blanco">
        <td height="13" colspan="7" align="center" valign="top" class="sin-borde"><table width="544" height="137" border="0" cellpadding="0" cellspacing="4" class="formato-blanco">
          <tr>
            <td>&nbsp;</td>
            <td width="118">&nbsp;</td>
            <td width="62"><div align="right">Estatus</div></td>
            <td width="111"><span class="style6"><input name="txtestobr" type="text" class="celdas-grises" id="txtestobr"  style="text-align:left" value="<?php print $ls_estcon; ?>" size="20" maxlength="20" readonly="true">
            </span></td></tr>
          <tr>
            <td width="231"><div align="right"><span class="style6">Descripcion de la Obra</span></div></td>
            <td colspan="3"><span class="style6">
            <input name="txtdesobr" type="text" id="txtdesobr"  style="text-align:left" value="<?php print $ls_desobr; ?>" size="55" maxlength="254"  readonly="true">
</span><span class="style6"></span></td>
          </tr>
          <tr>
            <td><div align="right">Fecha Contrato </div></td>
            <td><span class="style6">
              <input name="txtfeccon" type="text" id="txtfeccon"  style="text-align:left" value="<?php print $ls_feccon; ?>" size="20" maxlength="20" readonly="true">
            </span></td>
            <td><div align="right"><span class="style6">Pto. Cuenta </span></div></td>
            <td><span class="style6">
              <input name="txtpuncueasi" type="text" id="txtpuncuenasi"  style="text-align:left" value="<?php print $ls_puncue;  ?>" size="20" maxlength="20" readonly="true">
            </span></td>
          </tr>
          <tr>
            <td><div align="right"><span class="style6">Monto Contrato </span></div></td>
            <td><span class="style6">
              <input name="txtmoncon" type="text" id="txtmoncon"  style="text-align:left" value="<?php print $io_funcsob->uf_convertir_numerocadena($ls_moncon); ?>" size="20" maxlength="20" readonly="true">
            </span></td>
            <td><div align="right">Anticipo</div></td>
            <td><span class="style6">
            <input name="txtmonant" type="text" id="txtmonant"  style="text-align:left" value="<?php print $io_funcsob->uf_convertir_numerocadena($ls_totant); ?>" size="20" maxlength="20" readonly="true">
</span></td>
          </tr>
          <tr>
            <td><div align="right">Contrato + Aumentos - Disminuciones </div></td>
            <td><span class="style6">
            <input name="txtmontotcon" type="text" id="txtmontotcon"  style="text-align:left" value="<?php print $io_funcsob->uf_convertir_numerocadena($ls_totcon); ?>" size="20" maxlength="20" readonly="true">
            </span></td>
            <td>&nbsp;</td>
            <td><span class="style6">
            </span></td>
          </tr>
        </table></td>
    </tr>
	   <?
		}
		else
		{
	   ?>
          <tr>
            <td></td>
            <td><span class="style6">
              <input name="txtmoncon" type="hidden" id="txtmoncon"  style="text-align:left" value="<?php print $io_funcsob->uf_convertir_numerocadena($ls_moncon); ?>" size="20" maxlength="20" readonly="true">
            </span></td>
      <tr class="formato-blanco">
	  <?php 
	  }
	  ?>
        <td height="13" colspan="7" align="center" valign="top" class="sin-borde">&nbsp;</td>
      </tr>
	  <tr class="titulo-celdanew">
        <th height="14" colspan="6" class="titulo-celdanew" scope="col">Valuaci&oacute;n</th>
    </tr>
      <tr class="formato-blanco">
        <td><input name="operacion" type="hidden" id="operacion">
		<input name="opemostrar" type="hidden" id="opemostrar" value="<?php print $ls_opemostrar ?>">
		<input name="opemostrarA" type="hidden" id="opemostrarA" value="<?php print $ls_opemostrarA ?>">        </td>
        <td colspan="5"><?Php
			if(array_key_exists("hidstatus",$_POST))
			{
				$ls_hidstatus=$_POST["hidstatus"];
				if($ls_hidstatus=="C")
				{
					$_SESSION["campoclave"]=$ls_codval;
					$_SESSION["contrato"]=$ls_codcon;
			?>
          <a href="javascript:ue_grabarfoto()"><img src="../shared/imagebank/mas.gif" width="9" height="17" border="0">Incluir Fotos</a>&nbsp;&nbsp;&nbsp;
          <?Php
			}
			}
			?>
          <?Php
			if(array_key_exists("hidstatus",$_POST))
			{
				$ls_hidstatus=$_POST["hidstatus"];
				if($ls_hidstatus=="C")
				{
			?>
          <a href="javascript:ue_verfotos()"><img src="../shared/imagebank/mas.gif" width="9" height="17" border="0">Ver Fotos</a>
          <?Php
			}
			}
			?></td>
      </tr>
      <tr class="formato-blanco">
        <td height="22">&nbsp;</td>
        <td>&nbsp;</td>
        <td colspan="3"><div align="right">Estado</div></td>
        <td width="172"><span class="style6">
          <input name="txtestval" type="text" class="celdas-grises" id="txtestval"  style="text-align:left" value="<?php print $ls_estadoval; ?>" size="10" maxlength="10" readonly="true">
        </span></td>
      </tr>
      <tr class="formato-blanco">
        <td width="88" height="22"><div align="right"></div></td>
        <td width="102"><div align="right">C&oacute;digo</div></td>
        <td colspan="4"><input name="txtcodval" type="text" id="txtcodval" style="text-align:center " value="<?php print $ls_codval; ?>" size="3" maxlength="3" readonly="true">          
        <div align="right"></div>          <div align="right"></div></td>
      </tr>
       <tr class="formato-blanco">
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right">Fecha</div></td>
        <td colspan="4"><input name="txtfecha" type="text" id="txtfecha" value="<?php print $ls_fecha; ?>" size="12" maxlength="10" style="text-align:center"  datepicker="true" onKeyDown="javascript:ue_formatofecha(this,'/',patron,true,event);" ></td>
      </tr>
	  <tr class="formato-blanco">
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right">Fecha Inicio </div></td>
        <td width="83"><input name="txtfecinival" type="text" id="txtfecinival"  style="text-align:left" value="<?php print $ls_fecinival; ?>" size="12" maxlength="11"   onKeyPress="javascript:ue_validafecha();" datepicker="true" readonly="true"></td>
        <td width="158"><div align="right">Fecha Fin </div></td>
        <td width="155"><input name="txtfecfinval" type="text" id="txtfecfinval"  style="text-align:left" value="<?php print $ls_fecfinval; ?>" size="12" maxlength="11"   onKeyPress="javascript:ue_validafecha();" datepicker="true" readonly="true"></td>
        <td>&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td height="22"><div align="right"></div></td>
        <td height="22"><div align="right">Observacion</div></td>
        <td colspan="4"><textarea name="txtobsval" cols="80" rows="2" id="txtobsval" onKeyPress="return(validaCajas(this,'x',event))" onKeyDown="textCounter(this,254)" ><?php print $ls_obsval; ?></textarea></td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="6"></td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="6"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr class="formato-blanco">
            <td width="15" height="13">&nbsp;</td>
            <td width="593"><div align="left"></div></td>
          </tr>
          <tr align="center" class="formato-blanco">
            <td colspan="2"><?php $io_grid->makegrid($li_filaspartidas,$la_columpartidas,$la_objectpartidas,$li_anchopartidas,$ls_titulopartidas,$ls_nametable);?>            </td>
          </tr>
          <input name="filaspartidas" type="hidden" id="filaspartidas" value="<?php print $li_filaspartidas;?>">
          <tr class="formato-blanco">
            <td height="18" colspan="2">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr class="formato-blanco">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><div align="right">Total Partidas</div></td>
        <td><input name="txtsubtotpar" type="text" id="txtsubtotpar" size="20" maxlength="20" readonly="true" value="<?php print $ls_subtotpar?>" style="text-align:right "></td>
      </tr>
      <tr class="formato-blanco">
        <td>&nbsp;</td>
        <td colspan="2"><img src="../shared/imagebank/mas.gif" width="9" height="17" border="0"><span class="Estilo1">Amortizacion Anticipo</span></a></td>
        <td>&nbsp;</td>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="6">
		<table width="633" height="95" border="0" align="center" cellpadding="0" cellspacing="4" class="formato-blanco">
          <tr>
            <td width="134" height="18"><div align="right">Procentaje a Amortizar</div>            </td>
            <td width="75"><span class="style6">
            <input name="txtporamoactual" type="text" id="txtporamoactual"  style="text-align: right" value="<?php print $ls_poramoactual ?>" size="5" maxlength="5" onKeyPress="return(validaCajas(this,'d',event,21)) "  onBlur="javascript:ue_getformat(this)"  onKeyUp="javascript:uf_calcularamo()">
%            </span></td>
            <td width="24"> <div align="right">Total</div></td>
            <td width="105"><span class="style6">
              <input name="txtamoactual" type="text" id="txtamoactual"  style="text-align: right" value="<?php print $ls_amoactual ?>" size="20" maxlength="20" readonly="true">
            </span></td>
            <td width="145"><div align="right">Monto Anticipo </div></td>
            <td width="120"><span class="style6">
              <input name="txttotant" type="text" id="txttotant"  style="text-align: right" value="<?php print $io_funcsob->uf_convertir_numerocadena($ls_totant) ?>" size="20" maxlength="20" readonly="true">
</span></td>
          </tr>
          <tr>
              <td height="18"><div align="right"><span class="style6">Observacion</span></div></td>
            <td colspan="3" rowspan="3" valign="top"><span class="style6">
              <textarea name="txtamoobs" cols="25" rows="2" id="txtamoobs" style="text-align:left" onKeyPress="return(validaCajas(this,'x',event))" onKeyDown="textCounter(this,254)" ><?php print $ls_amoobs ?></textarea>
            </span><span class="style6">            </span></td>
              <td><div align="right"><span class="style6">Amortizaci&oacute;n Anterior </span></div></td>
              <td><span class="style6">
                <input name="txtamoant" type="text" id="txtamoant"  style="text-align: right" value="<?php print $ls_amoant ?>" size="20" maxlength="20" readonly="true">
              </span></td>
          </tr>
          <tr>
            <td height="18"><div align="right"></div></td>
            <td><div align="right">Total Amortizado </div></td>
            <td><span class="style6">
            <input name="txtamotot" type="text" id="txtamotot"  style="text-align: right" value="<?php print $ls_amotot ?>" size="20" maxlength="20" readonly="true">
</span></td>
          </tr>
          <tr>
            <td height="21"><div align="right"></div></td>
            <td><div align="right">Resta por Amortizar</div></td>
            <td><span class="style6">
              <input name="txtamores" type="text" id="txtamores"  style="text-align: right" value="<?php print $ls_amores ?>" size="20" maxlength="20" readonly="true"> 
            </span></td>
          </tr>
        </table>		</td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="6">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="6"><table width="609" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr class="formato-blanco">
            <td width="14" height="11">&nbsp;</td>
            <td width="593"><a href="javascript:ue_catcargos();"><img src="../shared/imagebank/mas.gif" width="9" height="17" border="0"></a><a href="javascript:ue_catcargos();">Agregar Detalle </a></td>
          </tr>
          <tr align="center" class="formato-blanco">
            <td height="11" colspan="2">
              <?php $io_grid->makegrid($li_filascargos,$la_columcargos,$la_objectcargos,$li_anchocargos,$ls_titulocargos,$ls_nametable);?>            </td>
            <input name="filascargos" type="hidden" id="filascargos" value="<?php print $li_filascargos;?>">
            <input name="hidremovercargo" type="hidden" id="hidremovercargo" value="<?php print $li_removercargo;?>">
          </tr>
          <tr class="formato-blanco">
            <td colspan="2">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr class="formato-blanco">
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right">Base Imponible</div></td>
        <td height="22"><input name="txtbasimpval" type="text" id="txtbasimpval" size="20" maxlength="20" value="<?php print $ls_basimpval?>" onKeyPress="return(currencyFormat(this,'.',',',event))" style="text-align:right "></td>
      </tr>
      <tr class="formato-blanco">
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right">Total Cargos </div></td>
        <td height="22"><input name="txttotcar" type="text" id="txttotcar" value="<?php print number_format($ld_subtot,2,',','.'); ?>" size="20" style="text-align:right " readonly></td>
      </tr>
      <tr class="formato-blanco">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><div align="right">Sub-Total</div></td>
        <td><input name="txtsubtot" type="text" id="txtsubtot" size="20" maxlength="20" readonly="true" value="<?php print $ls_subtot;?>" style="text-align:right "></td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="6"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0"  class="sin-borde">
          <tr class="formato-blanco">
            <td width="15" height="13">&nbsp;</td>
            <td width="593"><div align="left"><a href="javascript:ue_catretenciones();"><img src="../shared/imagebank/mas.gif" width="9" height="17" border="0"></a><a href="javascript:ue_catretenciones();">Agregar Detalle</a></div></td>
          </tr>
          <tr align="center" class="formato-blanco">
            <td colspan="2"><?php $io_grid->makegrid($li_filasretenciones,$la_columretenciones,$la_objectretenciones,$li_anchoretenciones,$ls_tituloretenciones,$ls_nametable);?>            </td>
          </tr>
          <tr class="formato-blanco">
            <td colspan="2">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="6">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td height="25">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><div align="right">Total Retenido </div></td>
        <td><input name="txttotreten" type="text" id="txttotreten" value="<?php print $ls_totreten ?>" style="text-align:right " readonly="true"></td>
      </tr>
      <tr class="formato-blanco">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><div align="right">Total</div></td>
        <td><input name="txtmontotval" type="text" id="txtmontotval" value="<?php print $ls_montotval?>" readonly="true" style="text-align:right "></td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="6">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="6">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="6"></td>
      </tr>
    </table>
    <div align="center"></div>
	<input name="hidstatus" type="hidden" id="hidstatus" <?php print $ls_hidstatus; ?>>
	<input name="hidfilasretenciones" type="hidden" id="hidfilasretenciones" value="<?php print $li_filasretenciones;?>">
	<input name="hidremoverretenciones" type="hidden" id="hidremoverretenciones" value="<?php print $li_removerretenciones;?>">
	
	<!-- HIDDEN VALORES A GUARDAR EN LA BD-->
	<input name="hidamototbd" type="hidden" id="hidamototbd" value="<?php print $ls_hidamototbd;?>">
	<input name="hidamoresbd" type="hidden" id="hidamoresbd" value="<?php print $ls_hidamoresbd;?>">
  </form>
</body>
<script language="javascript">
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);

/*******************************************CATALOGOS********************************************************************************************************/
function ue_catcontrato()
{
	f=document.form1;
	f.operacion.value="";	
	var opener="valuacion"		
	pagina="sigesp_cat_contrato.php?opener="+opener;
	popupWin(pagina,"catalogo",850,500);
	//window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=200,resizable=yes,location=no");
}

function ue_catretenciones()
{
	f=document.form1;
	if(f.txtcodcon.value!="")
	{
		f.operacion.value="";
		ls_codcon=f.txtcodcon.value;			
		pagina="sigesp_cat_retencontrato.php?codcon="+ls_codcon;
		popupWin(pagina,"catalogo",600,250);
		//window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=200,resizable=yes,location=no");
	}
	else
	{
		alert("Debe seleccionar un Contrato!!");
	}
}

function ue_catcargos()
{
	f=document.form1;
	if((f.txtbasimpval.value=="0,00")||(f.txtbasimpval.value==""))
	 {
	  alert("Aun no ha indicado la base imponible a la cual se le aplicaran los cargos!!")
	 }
	 else
	 {
	   f.operacion.value="";
	   tipdes="VALUACION";
	   codasi=f.hidpuncue.value;
	   pagina="sigesp_cat_cargos.php?tipdes="+tipdes+"&codasi="+codasi;
	   popupWin(pagina,"catalogo",650,400);
	   //window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=200,resizable=yes,location=no,status=yes"); 
	 }
}

function ue_buscar()
{
 f=document.form1;
 pagina="sigesp_cat_valuacion.php?estado=";
 popupWin(pagina,"catalogo",750,450);
 //window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=350,resizable=yes,location=no");
} 
function ue_buscartipodocumento()
{
	window.open("sigesp_sob_cat_tipodocumentos.php?tipo=valuacion","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_grabarfoto()
{
	var opener="valuacion";
	pagina="sigesp_sob_d_grabarfotos.php?opener="+opener;
	popupWin(pagina,"catalogo",520,220);
}

function ue_verfotos()
{
	var opener="valuacion";
	var codigoval=document.form1.txtcodval.value;
	var codigocon=document.form1.txtcodcon.value;
	pagina="sigesp_sob_d_verfotos.php?opener="+opener+"&campocodigo="+codigoval+"&contrato="+codigocon;
	popupWin(pagina,"catalogo2",800,800);

}
/*************************************************************************************************************************************************/
function ue_generar_recepcion()
{
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	if(li_ejecutar==1)
	{
		conval=f.txtcodval.value;
		if(conval!="")
		{
			f.operacion.value="PROCESAR";
			f.action="sigesp_sob_d_valuacion.php";
			f.submit();
		}
	}
	else
   	{alert("No tiene permiso para realizar esta operacion");}
}

/*******************************************CARGAR Y REMOVER DATOS********************************************************************************************************/
function ue_cargarcontrato(ls_codigo,ls_desobr,ls_estado,ls_codest,ld_monto,ls_placon,ls_placonuni,ls_mulcon,ls_tiemulcon,ls_mulreuni,ls_lapgarcon,ls_lapgaruni,
						ls_codtco,ls_monmaxcon,ls_pormaxcon,ls_obscon,ls_porejefiscon,ls_porejefincon,ls_monejefincon,ls_codasi,ls_feccrecon,
						ls_fecinicon,ls_nomtco,ls_codobr,ls_codpro,ls_codproins)
{
	f=document.form1;
	f.txtcodcon.value=ls_codigo;
	f.hidpuncue.value=ls_codasi;
	f.operacion.value="ue_datcontrato";
	f.submit();
}

function ue_cargarvaluacion(ls_codval,ls_codasi,ls_codcon,ls_fecha,ls_fecinival,ls_fecfinval,ls_obsval,ls_amoval,ls_obsamoval,ls_amoantval,ls_amototval,ls_amoresval,ls_basimpval,ls_montotval,ls_subtotpar,ls_totreten,ls_subtot,ls_nomestval,ls_estapr)
{
    f=document.form1;
	f.txtcodval.value=ls_codval;
	f.txtcodcon.value=ls_codcon;
	f.txtfecha.value=ls_fecha;
	f.txtfecinival.value=ls_fecinival;
	f.txtfecfinval.value=ls_fecfinval;
	f.txtobsval.value=ls_obsval;
	f.txtamoactual.value=uf_convertir(ls_amoval);
	f.txtamoobs.value=ls_obsamoval;
	f.txtamoant.value=uf_convertir(ls_amoantval);
	f.txtamotot.value=uf_convertir(ls_amototval);
	f.txtamores.value=uf_convertir(ls_amoresval);
	f.txtsubtotpar.value=uf_convertir(ls_subtotpar);
	f.txtbasimpval.value=uf_convertir(ls_basimpval);
	f.txtmontotval.value=uf_convertir(ls_montotval);
	f.txttotreten.value=uf_convertir(ls_totreten);
	f.txtsubtot.value=uf_convertir(ls_subtot);
	f.txtestval.value=ls_nomestval;	
	f.hidcodasi.value=ls_codasi;
	f.hidestapr.value=ls_estapr;
	f.hidstatus.value="C";
	f.operacion.value="ue_cargarvaluacion";
	f.action="sigesp_sob_d_valuacion.php";
	f.submit();
}

function ue_cargarretenciones(codigo,descripcion,cuenta,deducible,formula)
{
	f=document.form1;
	f.operacion.value="ue_cargarretenciones";
	lb_existe=false;
	ls_estapr=f.hidestapr.value;
	if(ls_estapr!="1")
	{
	
		for(li_i=1;li_i<=f.hidfilasretenciones.value && !lb_existe;li_i++)
		{
			ls_codigo=eval("f.txtcodret"+li_i+".value");
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
	else
	{
		alert("La valuacion esta aprobada. No se puede modificar");
	}
}

function ue_removerretenciones(li_fila)
{
	f=document.form1;
	f.hidremoverretenciones.value=li_fila;
	f.operacion.value="ue_removerretenciones"
	f.action="sigesp_sob_d_valuacion.php";
	f.submit();
}

function ue_cargarcargo(cod,nom,formula,codestpro,spg_cuenta,estcla)
{
	f=document.form1;
	f.operacion.value="ue_cargarcargo";	
	lb_existe=false;
	ls_estapr=f.hidestapr.value;
	if(ls_estapr!="1")
	{
	
		for(li_i=1;li_i<=f.filascargos.value && !lb_existe;li_i++)
		{
			ls_codigo=eval("f.txtcodcar"+li_i+".value");
			if(ls_codigo==cod)
			{
				alert("El Cargo ya ha sido cargado!!!");
				lb_existe=true;
			}
		}	
		
		if(!lb_existe)
		{
			eval("f.txtcodcar"+f.filascargos.value+".value='"+cod+"'");
			eval("f.txtnomcar"+f.filascargos.value+".value='"+nom+"'");
			eval("f.formu"+f.filascargos.value+".value='"+formula+"'");
			eval("f.codestpro"+f.filascargos.value+".value='"+codestpro+"'");
			eval("f.spgcuenta"+f.filascargos.value+".value='"+spg_cuenta+"'");
			eval("f.estcla"+f.filascargos.value+".value='"+estcla+"'");
			f.submit();
		}
	}
	else
	{
		alert("La valuacion esta aprobada. No se puede modificar");
	}
}

function ue_removercargo(li_fila)
{
	f=document.form1;
	f.hidremovercargo.value=li_fila;
	f.operacion.value="ue_removercargo"
	f.action="sigesp_sob_d_valuacion.php";
	f.submit();
}

/*************************************************************************************************************************************************/

/*********************************GENERAR NUEVO***********************************************************************************************************/
function ue_nuevo()
 {
  f=document.form1;
  li_incluir=f.incluir.value;
  if(li_incluir==1)
   {		 
	   f.operacion.value="ue_nuevo";
	   f.txtfecfinval.value="";
	   f.txtfecinival.value="";
	   f.txtobsval.value="";
	   f.action="sigesp_sob_d_valuacion.php";
	   f.submit();
   }
   else
   {
     alert("No tiene permiso para realizar esta operacion");
   } 
 }
/*************************************************************************************************************************************************/


/*************************************************************************************************************************************************/
function ue_guardar()
{
  f=document.form1;
  li_incluir=f.incluir.value;
  li_cambiar=f.cambiar.value;
  lb_status=f.hidstatus.value;
	ls_estapr=f.hidestapr.value;
	if(ls_estapr!="1")
	{
  
	  if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
	   {	
		if (ue_valida_null(f.txtcodcon,"Codigo Contrato")==false)
		 {
		   f.txtcodcon.focus();
		 }
		 else
		 {
		  if (ue_valida_null(f.txtcodval,"Codigo Valuacion")==false)
		   {
			 f.txtcodval.focus();
		   }
		   else
		   {
			if (ue_valida_null(f.txtfecinival,"Fecha Inicio")==false)
			 {
			   f.txtfecinival.focus();
			 }
			 else
			 {
			  if (ue_valida_null(f.txtfecfinval,"Fecha Fin")==false)
			   {
				 f.txtfecfinval.focus();
			   }
			   else
			   {
				if ((f.txtsubtotpar.value=="")||(f.txtsubtotpar.value=="0,00"))
				 {
				   alert("debe realizar la valuacion de por lo menos una partida!!");
				 }
				 else
				 {
						if(ue_comparar_intervalo("txtfecinival","txtfecfinval","La fecha de inicio de la Valuación debe ser menor de la de finalización!!!"))
						{
							ld_montotval=parseFloat(uf_convertir_monto(f.txtmontotval.value));
							ld_moncon=parseFloat(uf_convertir_monto(f.txtmoncon.value));
							if(ld_montotval<=ld_moncon)
							{
								f.action="sigesp_sob_d_valuacion.php";
								f.operacion.value="ue_guardar";
								f.submit();
							}
							else
							{
								alert("El monto total de la valuacion no puede exeder el monto del contrato");
							}
						}
					 }
				}
			   }
			 } 
		   }
		 } 
		else
		{
			alert("No tiene permiso para realizar esta operacion");
		}   	
	}
	else
	{
		alert("La valuacion esta aprobada. No se puede modificar");
	}
}	
/*************************************************************************************************************************************************/


/******************************************VALIDACIONES****************************************************************************************/
function uf_validacaracter(cadena, obj)
{ 
   opc = false; 
   if (cadena == "%d")//toma solo caracteres  
   if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32))  
   opc = true; 

   if (cadena == "%e")//toma el @, el punto y caracteres. Para Email
   if ((event.keyCode > 63 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32)||(event.keyCode ==46)||(event.keyCode ==95)||(event.keyCode > 47 && event.keyCode < 58))  
   opc = true;    

   if (cadena == "%f")//Toma solo numeros
   { 
     if (event.keyCode > 47 && event.keyCode < 58) 
     opc = true; 
     if (obj.value.search("[.*]") == -1 && obj.value.length != 0) 
     if (event.keyCode == 46) 
     opc = true; 
   } 
   
   if (cadena == "%s") // toma numero y letras
   if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32)||(event.keyCode > 47 && event.keyCode < 58)||(event.keyCode ==46)||(event.keyCode ==47)||(event.keyCode ==35)||(event.keyCode ==45)) 
   opc = true; 
   
   if (cadena == "%c") // toma numero, punto y guion. Para telefonos
   if ((event.keyCode > 47 && event.keyCode < 58)|| (event.keyCode > 44 && event.keyCode < 47))
   opc = true; 
   
   if(opc == false) 
   event.returnValue = false;
 }

function esDigito(sChr)
{ 
    var sCod = sChr.charCodeAt(0); 
    return ((sCod > 47) && (sCod < 58)); 
}

function currencyFormat(fld, milSep, decSep, e) { 
    var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789'; 
    var aux = aux2 = ''; 
    var whichCode = (window.Event) ? e.which : e.keyCode; 
    if (whichCode == 13) return true; // Enter 
    if (whichCode == 8) return true; // Backspace <-
    if (whichCode == 127) return true; // Suprimir -Del 
    key = String.fromCharCode(whichCode); // Get key value from key code 
    if (strCheck.indexOf(key) == -1) return false; // Not a valid key 
    len = fld.value.length; 
    for(i = 0; i < len; i++) 
     if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != decSep)) break; 
    aux = ''; 
    for(; i < len; i++) 
     if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i); 
    aux += key; 
    len = aux.length; 
    if (len == 0) fld.value = ''; 
    if (len == 1) fld.value = '0'+ decSep + '0' + aux; 
    if (len == 2) fld.value = '0'+ decSep + aux; 
    if (len > 2) { 
     aux2 = ''; 
     for (j = 0, i = len - 3; i >= 0; i--) { 
      if (j == 3) { 
       aux2 += milSep; 
       j = 0; 
      } 
      aux2 += aux.charAt(i); 
      j++; 
     } 
     fld.value = ''; 
     len2 = aux2.length; 
     for (i = len2 - 1; i >= 0; i--) 
      fld.value += aux2.charAt(i); 
     fld.value += decSep + aux.substr(len - 2, len); 
    } 
	if (fld.id != "txtbasimpval" && fld.id != "txtporamoactual")
	  {
	   if (fld.id == "txtporamoactual")
	   { 
         uf_calcularamo();
 	   }
	   else
	   {
	    txt=fld.id.charAt(3);
		if(txt!="m")
		{
		  ue_subtotal();
		}
	   }	
	  }	
    return false; 
   } 
/*************************************************************************************************************************************************/

/************************************************************************************************************************************************/
 function ue_calcretencion(c)
 {
	f=document.form1;
	ld_subtot=parseFloat(uf_convertir_monto(f.txtsubtot.value));
	ld_monret=parseFloat(uf_convertir_monto(c.value));
	if(ld_monret<ld_subtot)
	{
	 f.action="sigesp_sob_d_valuacion.php";
	 f.operacion.value="ue_calcretencion";
	 f.submit();
	}
	else
	{
	 alert("El monto objeto de retencion debe ser menor al subtotal acumulado");
	 c.value="0,00";
	}
 }	
 function uf_calcularamo() 
 { 
   f=document.form1;
   if(f.txtporamoactual.value=="")
   {
     alert("Por favor indique el procentaje de la Amortizaciòn!!");
   }
   else
   {
     ld_poramo=parseFloat(uf_convertir_monto(f.txtporamoactual.value));
	 if((f.txtsubtotpar.value=="")||(f.txtsubtotpar.value=="0,00"))
      {
        alert("Debe realizar la valuacion de por lo menos una partida!!")
		f.txtporamoactual.value="0,00";
		f.txtamoactual.value="0,00";
	  }
      else
      {
        ld_totpar=parseFloat(uf_convertir_monto(f.txtsubtotpar.value));
		ld_amoactual=ld_totpar*(ld_poramo/100);
		if(f.hidtotant.value=="")
         {
           ld_totant=0;
         }
         else
         {
           ld_totant=parseFloat(f.hidtotant.value);
         }
        if(ld_amoactual>ld_totant)
         {
           alert("La Amortizacion supera el Monto del Anticipo2!!");
		   f.txtporamoactual.value="0,00";
			 f.txtamoactual.value="0,00";
	     }
         else
	     {
	      if(f.hidamototbd.value=="")
           {
             ld_amotot=0;
           }
           else
           {
             ld_amotot=parseFloat(f.hidamototbd.value);
           }
		   ld_totalamo=ld_amoactual+ld_amotot;
		   if(ld_totalamo>ld_totant)
            {
             alert("La Suma de las Amortizaciones supera el Monto del Anticipo!!");
			 f.txtporamoactual.value="0,00";
			 f.txtamoactual.value="0,00";
	        }
		    else
		    {
		      ld_resta=ld_totant-ld_totalamo;
			  ld_subto=ld_totpar-ld_amoactual;
		      f.txtamoactual.value=uf_convertir(ld_amoactual);
		      f.txtamotot.value=uf_convertir(ld_totalamo);
			  f.txtamores.value=uf_convertir(ld_resta);
			  f.hidamoresbd.value=ld_resta;
			  f.txtsubtot.value=uf_convertir(ld_subto);
	          f.txtbasimpval.value=uf_convertir(ld_subto);
	          f.txtmontotval.value=uf_convertir(ld_subto);
		    }
	  }
     }
   }
 } 

function ue_subtotal()
{
	f=document.form1;
	li_filasparitdas=f.filaspartidas.value;
	ld_subtotal=0;
	ls_cero="0,00"
	for(li_i=1;li_i<=li_filasparitdas;li_i++)
	{
	  if(eval("f.flagpar"+li_i+".checked==true"))
		{
		 if(eval("f.txtcantpar"+li_i+".value")=="")
		  {
		   ld_cantpar=0;
		   alert("No le coloco cantidad a una de las partidas seleccionadas");
		  }
		  else
		   {
		     ld_cantpar=parseFloat(uf_convertir_monto(eval("f.txtcantpar"+li_i+".value")));
		    
			 //tomando el precio unitario de la partida
		     if(eval("f.txtpreunimod"+li_i+".value")=="")
		      {
		       ld_preuni=0;
		      }
		      else
		       {
		         ld_preuni=parseFloat(uf_convertir_monto(eval("f.txtpreunimod"+li_i+".value")));
		       }
		      //tomando la cantidad asignada de la partida
		      if(eval("f.txtcanttot"+li_i+".value")=="")
		       {
		        ld_canttot=0;
		       }
		       else
		        {
		          ld_canttot=parseFloat(uf_convertir_monto(eval("f.txtcanttot"+li_i+".value")));
		        } 
		  
				if(ld_canttot<ld_cantpar)
		         {
		          alert("La cantidad que esta valuando supera a la cantidad Asignada!!");
		          eval("f.txtcantpar"+li_i+".value='"+ls_cero+"'");
		          eval("f.txttotal"+li_i+".value='"+ls_cero+"'");
		         }
		         else
		         {
		          ld_totpar=ld_preuni*ld_cantpar;
				  ls_totp=uf_convertir(ld_totpar);
				  ld_subtotal=ld_subtotal+ld_totpar;
		          eval("f.txttotal"+li_i+".value='"+ls_totp+"'");
		         }
		    }
		  }
		  else
			{
				if((eval("f.txtcantpar"+li_i+".value")!=""))
				{
					if((eval("f.txtcantpar"+li_i+".value")!="0,00"))
					{
						alert("Debe seleccionar la partida antes de colocarle cantidad!!");
						eval("f.txtcantpar"+li_i+".value='"+ls_cero+"'");
					}
				}
				else
				{
					eval("f.txttotal"+li_i+".value=''");
				}
			}
	}	
	f.txtsubtotpar.value=uf_convertir(ld_subtotal);
	f.txtsubtot.value=uf_convertir(ld_subtotal);
	f.txtbasimpval.value=uf_convertir(ld_subtotal);
	f.txtmontotval.value=uf_convertir(ld_subtotal);
}
 

/************************************************************************************************************************************************/
function uf_mostrar_ocultar_contrato()  
{
	f=document.form1;
	if (f.txtcodcon.value=="")
	{
		alert("Debe seleccionar un Contrato!!");
	}
	else
	{
	   
		if (f.opemostrar.value == "")
		{
			f.opemostrar.value = "MOSTRAR";
	    }
		else
		{
		  if (f.opemostrar.value == "MOSTRAR")
		   {
		     f.opemostrar.value = "";
		   } 
		}
		f.submit();
	}
}
function ue_eliminar()  
{
  f=document.form1;
  li_eliminar=f.eliminar.value;
  ls_estapr=f.hidestapr.value;
  if(ls_estapr!="1")
  {
	  if(li_eliminar==1)
	   {		
		if (f.txtcodval.value=="")
		{
			alert("Debe seleccionar la Asigancion a Anular!!");
		}
		else
		{
		 f.action="sigesp_sob_d_valuacion.php";
		 f.operacion.value="ue_anular";
		 f.submit();
		}
	   }
	   else
	   {
		alert("No tiene permiso para realizar esta operacion");
	   }
	}
	else
	{
		alert("La valuacion esta aprobada. No se puede modificar");
	}
}
function ue_ajustar(fila)
{
	f=document.form1;
	totrow=ue_calcular_total_fila_local("txtcodpar");
	ls_ajuste="";
	codpar=eval("f.txtcodpar"+fila+".value");
	montoajuste=eval("f.txtmonaju"+fila+".value");
	montoajuste=ue_formato_calculo(montoajuste);
	if((parseFloat(montoajuste)>=-0.99)&&(parseFloat(montoajuste)<=0.99))
	{
		monto=eval("f.txttotal"+fila+".value");
		monto=ue_formato_calculo(monto);
		monto=eval(monto+"+"+montoajuste);
		monto=redondear(monto,2);
		monto=uf_convertir(monto);
		eval("f.txttotal"+fila+".value='"+monto+"'"); 
		eval("f.txtmonaju"+fila+".value='0,00'");
	}
	else
	{
		alert("el monto del ajuste del cargo "+codcar+" debe ser mayor que -1,00 y menor que 1,00 ");
		eval("f.txtmonaju"+fila+".value='0,00'"); 
	}
	ue_subtotal2();
}
function ue_subtotal2()
{
	f=document.form1;
	li_filasparitdas=f.filaspartidas.value;
	ld_subtotal=0;
	ls_cero="0,00"
	for(li_i=1;li_i<=li_filasparitdas;li_i++)
	{
	  if(eval("f.flagpar"+li_i+".checked==true"))
		{
		 if(eval("f.txtcantpar"+li_i+".value")=="")
		  {
		   ld_cantpar=0;
		   alert("No le coloco cantidad a una de las partidas seleccionadas");
		  }
		  else
		   {
			    ld_cantpar=parseFloat(uf_convertir_monto(eval("f.txtcantpar"+li_i+".value")));
			    ld_canttot=parseFloat(uf_convertir_monto(eval("f.txtcanttot"+li_i+".value")));
				if(ld_canttot<ld_cantpar)
		         {
		          alert("La cantidad que esta valuando supera a la cantidad Asignada!!");
		          eval("f.txtcantpar"+li_i+".value='"+ls_cero+"'");
		          eval("f.txttotal"+li_i+".value='"+ls_cero+"'");
		         }
		         else
		         {
			      ld_totpar=parseFloat(uf_convertir_monto(eval("f.txttotal"+li_i+".value")));
				  ls_totp=uf_convertir(ld_totpar);
				  ld_subtotal=ld_subtotal+ld_totpar;
		    	//  eval("f.txttotal"+li_i+".value='"+ls_totp+"'");
		         }
		    }
		  }
		  else
			{
			  if((eval("f.txtcantpar"+li_i+".value")!=""))
		        {
				 if((eval("f.txtcantpar"+li_i+".value")!="0,00"))
				   {
				     alert("Debe seleccionar la partida antes de colocarle cantidad!!");
				     eval("f.txtcantpar"+li_i+".value='"+ls_cero+"'");
				   }
				 }
			}
	}	
	f.txtsubtotpar.value=uf_convertir(ld_subtotal);
	f.txtsubtot.value=uf_convertir(ld_subtotal);
	f.txtbasimpval.value=uf_convertir(ld_subtotal);
	f.txtmontotval.value=uf_convertir(ld_subtotal);
}

function ue_marcartodo(li_fila)
{
	f=document.form1;
	if(eval("f.flagpar"+li_fila+".checked")==true)
	{
		cantidad=eval("f.txtcanttot"+li_fila+".value"); 
		obj=eval("f.txtcantpar"+li_fila+"");
		obj.value=cantidad;
		ue_subtotal();
	}
	else
	{
		cantidad="";
		obj=eval("f.txtcantpar"+li_fila+"");
		obj.value=cantidad;
		ue_subtotal();
	}
	
}

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>