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
	$io_fun_sob->uf_load_seguridad("SOB","sigesp_sob_d_variaciones.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Variacion Contrato</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_sob.js"></script>
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
-->
</style><meta http-equiv="Content-Type" content="text/html; charset="></head>
<body link="#006699" vlink="#006699" alink="#006699">
<?php
/******************************************/
/* FECHA: 25/03/2006                      */ 
/* AUTOR: GERARDO CORDERO                 */         
/******************************************/


/**************************************** DECLARACIONES  ********************************************************************************/

require_once("../shared/class_folder/class_sql.php");
require_once("class_folder/sigesp_sob_c_valuacion.php");
require_once("class_folder/sigesp_sob_c_variacion.php");
require_once("class_folder/sigesp_sob_c_funciones_sob.php");
require_once("../shared/class_folder/grid_param.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/evaluate_formula.php");
$io_variacion=new sigesp_sob_c_variacion();
$io_valuacion=new sigesp_sob_c_valuacion();
$io_funcsob=new sigesp_sob_c_funciones_sob();
$io_evalform=new evaluate_formula();
$io_grid=new grid_param();
$io_msg=new class_mensajes();
$io_datastore=new class_datastore();
$io_function=new class_funciones();
$io_datastore=new class_datastore();



$ls_titulopartidas="Partidas Asignadas";
$li_anchopartidas=600;
$ls_nametable="grid";
$la_columpartidas[1]="";
$la_columpartidas[2]="Código";
$la_columpartidas[3]="Partida";
$la_columpartidas[4]="Uni. Med.";
$la_columpartidas[5]="Precio";
$la_columpartidas[6]="Precio Nuevo";
$la_columpartidas[7]="Cant(Or)";
$la_columpartidas[8]="Cant(Nu)";
$la_columpartidas[9]="Total";

$ls_titulocuentas="Cuentas de Gastos";
$li_anchocuentas=600;
$ls_nametable="grid2";
$la_columcuentas[1]="Código Presupuestario";
$la_columcuentas[2]="Estatus";
$la_columcuentas[3]="Cuenta";
$la_columcuentas[4]="Monto";
$la_columcuentas[5]="Edición";
/****************************************************************************************************************************************/

/******************************************************	OBTENER VALORES DE LOS TXT *********************************************************/
if(array_key_exists("operacion",$_POST))
{
	$ls_opemostrar=$_POST["opemostrar"];
	$ls_operacion=$_POST["operacion"];
	$ls_codcon=$_POST["txtcodcon"];
	$ls_codvar=$_POST["txtcodvar"];
	$ls_tipvar=$_POST["cmbtipvar"];
	$ls_motvar=$_POST["txtmotvar"];
	$ls_fecvar=$_POST["txtfecvar"];
	$ls_monto=$_POST["txtmonto"];
	$ls_monco=$_POST["txtmoncon"];
	$ls_estvar=$_POST["txtestvar"]; 
	$ls_chk=$_POST["hidstatus"];
	$ls_totcon=$_POST["hidmonco"];
	$ls_estapr=$_POST["hidestapr"];
	$li_filaspartidas=$_POST["filaspartidas"];
	$li_filascuentas=$_POST["filascuentas"];

	$li_filaspartidas=$_POST["filaspartidas"];
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
			$ls_codasi=$_POST["codasi".$li_i];
			$ls_codobr=$_POST["codobr".$li_i];
			if(!empty($_POST["flagpar".$li_i]))
			{
			 $la_objectpartidas[$li_i][1]="<input type=checkbox name=flagpar".$li_i." value=1 checked class=sin-borde>";
			}
			else
			{
			 $la_objectpartidas[$li_i][1]="<input type=checkbox name=flagpar".$li_i." value=1 class=sin-borde>";
			}
	        $la_objectpartidas[$li_i][2]="<input name=txtcodpar".$li_i." type=text id=txtcodpar".$li_i." value='".$ls_codigo."' class=sin-borde style= text-align:center size=8 readonly><input name=codasi".$li_i." type=hidden id=codasi".$li_i." value='".$ls_codasi."'><input name=codobr".$li_i." type=hidden id=codobr".$li_i." value='".$ls_codobr."'>";
	        $la_objectpartidas[$li_i][3]="<input name=txtnompar".$li_i." type=text id=txtnompar".$li_i." value='".$ls_nombre."' class=sin-borde style= text-align:left size=25 readonly>";
	        $la_objectpartidas[$li_i][4]="<input name=txtnomuni".$li_i." type=text id=txtnomuni".$li_i." value='".$ls_unidad."' class=sin-borde size=5 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][5]="<input name=txtpreuni".$li_i." type=text id=txtpreuni".$li_i." value='".$ls_preuni."' class=sin-borde size=15 style= text-align:center readonly>";
			$la_objectpartidas[$li_i][6]="<input name=txtpreunimod".$li_i." type=text id=txtpreunimod".$li_i." value='".$ls_preunimod."' class=sin-borde size=15 style= text-align:center onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=ue_subtotal();>";
	        $la_objectpartidas[$li_i][7]="<input name=txtcanttot".$li_i." type=text id=txtcanttot".$li_i." value='".$ls_canttot."' class=sin-borde size=5 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][8]="<input name=txtcantpar".$li_i." type=text id=txtcantpar".$li_i." value='".$ls_cantpar."' class=sin-borde size=5 style= text-align:center onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=ue_subtotal();>";
	        $la_objectpartidas[$li_i][9]="<input name=txttotal".$li_i." type=text id=txttotal".$li_i." value='".$ls_total."' class=sin-borde size=15 style= text-align:center readonly>";	
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

  if ($ls_operacion != "ue_cargarcuenta" && $ls_operacion != "ue_removercuenta")
	{
		$li_filasfuentes=$_POST["filascuentas"];
		for($li_i=1;$li_i<$li_filasfuentes;$li_i++)
		{		
		   $ls_codigo=$_POST["txtcodcue".$li_i];
		   $ls_codest1=$_POST["codest1".$li_i];
		   $ls_codest2=$_POST["codest2".$li_i];
		   $ls_codest3=$_POST["codest3".$li_i];
		   $ls_codest4=$_POST["codest4".$li_i];
		   $ls_codest5=$_POST["codest5".$li_i];
		   $ls_disponible=$_POST["disponible".$li_i];
		   $ls_nombre=$_POST["txtnomcue".$li_i];
		   $ls_moncar=$_POST["txtmoncue".$li_i];
		   $ls_estcla=$_POST["txtestcla".$li_i];
		   $la_objectcuentas[$li_i][1]="<input name=txtcodcue".$li_i." type=text id=txtcodcue".$li_i." class=sin-borde style= text-align:center size=40 value='".$ls_codigo."' readonly><input name=codest1".$li_i." type=hidden id=codest1".$li_i." value='".$ls_codest1."'><input name=codest2".$li_i." type=hidden id=codest2".$li_i." value='".$ls_codest2."'><input name=codest3".$li_i." type=hidden id=codest3".$li_i." value='".$ls_codest3."'><input name=codest4".$li_i." type=hidden id=codest4".$li_i." value='".$ls_codest4."'><input name=codest5".$li_i." type=hidden id=codest5".$li_i." value='".$ls_codest5."'>";
		   $la_objectcuentas[$li_i][2]="<input name=txtestcla".$li_i." type=text id=txtestcla".$li_i." class=sin-borde style= text-align:center size=20 value='".$ls_estcla."' readonly>";
		   $la_objectcuentas[$li_i][3]="<input name=txtnomcue".$li_i." type=text id=txtnomcue".$li_i." class=sin-borde style= text-align:left size=10 value='".$ls_nombre."' readonly >";
		   $la_objectcuentas[$li_i][4]="<input name=txtmoncue".$li_i." type=text id=txtmoncue".$li_i." class=sin-borde size=20 style= text-align:center value='".$ls_moncar."' onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=javascript:ue_validardispo()><input name=disponible".$li_i." type=hidden id=disponible".$li_i." value='".$ls_disponible."'>";
		   $la_objectcuentas[$li_i][5]="<div align=center><a href=javascript:ue_removercuenta(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a></div>";
		}	
		$la_objectcuentas[$li_filascuentas][1]="<input name=txtcodcue".$li_filascuentas." type=text id=txtcodcue".$li_filascuentas." class=sin-borde style= text-align:center size=40 readonly><input name=codest1".$li_filascuentas." type=hidden id=codest1".$li_filascuentas."><input name=codest2".$li_filascuentas." type=hidden id=codest2".$li_filascuentas."><input name=codest3".$li_filascuentas." type=hidden id=codest3".$li_filascuentas."><input name=codest4".$li_filascuentas." type=hidden id=codest4".$li_filascuentas."><input name=codest5".$li_filascuentas." type=hidden id=codest5".$li_filascuentas.">";
	    $la_objectcuentas[$li_filascuentas][2]="<input name=txtestcla".$li_filascuentas." type=text id=txtestcla".$li_filascuentas." class=sin-borde style= text-align:center size=20 readonly>";
		$la_objectcuentas[$li_filascuentas][3]="<input name=txtnomcue".$li_filascuentas." type=text id=txtnomcue".$li_filascuentas." class=sin-borde style= text-align:left size=10 readonly><input name=disponible".$li_filascuentas." type=hidden id=disponible".$li_filascuentas.">";
		$la_objectcuentas[$li_filascuentas][4]="<input name=txtmoncue".$li_filascuentas." type=text id=txtmoncue".$li_filascuentas." class=sin-borde style= text-align:center size=20 readonly>";
		$la_objectcuentas[$li_filasfuentes][5]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	}

	
}
/*******************************************************************************************************************************************************/

/************************************************ INICIALIZA LAS VARIABLES SI NO HAY SUBMIT *******************************************************************************************************/
else
{
    $ls_opemostrar="";
	$ls_operacion="";
	$ls_codcon="";
	$ls_codvar="";
	$ls_tipvar="";
	$ls_motvar="";
	$ls_fecvar="";
	$ls_monto="0,00";
	$ls_monco="0,00";
	$ls_estvar=""; 
	$ls_chk="";
	$ls_totcon="";
	$ls_estapr="";
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
	
	$li_filascuentas=1;
	$la_objectcuentas[1][1]="<input name=txtcodcue1 type=text id=txtcodcue1 class=sin-borde style= text-align:center size=40 readonly><input name=codest11 type=hidden id=codest11><input name=codest21 type=hidden id=codest21><input name=codest31 type=hidden id=codest31><input name=codest41 type=hidden id=codest41><input name=codest51 type=hidden id=codest51>";
    $la_objectcuentas[1][2]="<input name=txtestcla1 type=text id=txtestcla1 class=sin-borde style= text-align:center size=20  readonly>";
	$la_objectcuentas[1][3]="<input name=txtnomcue1 type=text id=txtnomcue1 class=sin-borde style= text-align:left size=10 readonly>";
	$la_objectcuentas[1][4]="<input name=txtmoncue1 type=text id=txtmoncue1 class=sin-borde style= text-align:center size=20 readonly><input name=disponible1 type=hidden id=disponible1>";
	$la_objectcuentas[1][5]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
	
}
/***************************************************************************************************************************************************************************/

/***************************************************************************************************************************************************************************/
if($ls_operacion=="ue_datcontrato")
{
   $ls_codcon=$_POST["txtcodcon"];
   $io_valuacion->uf_select_contrato($ls_codcon,&$la_contrato);
   $io_valuacion->uf_select_anticipos($ls_codcon,&$ls_totant);
   $io_valuacion->uf_select_variaciones($ls_codcon,&$ld_aum,1);
   $io_valuacion->uf_select_variaciones($ls_codcon,&$ld_dis,2);
   $ls_desobr=$la_contrato["desobr"][1];
   $ls_puncue=$la_contrato["puncueasi"][1];
   $ls_estcon=$io_funcsob->uf_convertir_numeroestado ($la_contrato["estcon"][1]);
   $ls_moncon=$la_contrato["monto"][1];
   $ls_feccon=$io_function->uf_convertirfecmostrar($la_contrato["feccon"][1]);
   $ls_totcon=$la_contrato["monto"][1]+$ld_aum+$ld_dis;
   require_once("../shared/class_folder/class_funciones_db.php");
	require_once ("../shared/class_folder/sigesp_include.php");		
	$io_include=new sigesp_include();
	$io_connect=$io_include->uf_conectar();
	$io_funcdb=new class_funciones_db($io_connect);
	$la_empresa=$_SESSION["la_empresa"];
//	$io_variacion->uf_select_newcodigo($ls_codcon,&$ls_codvar);
	require_once("../shared/class_folder/sigesp_c_generar_consecutivo.php");
	$io_keygen= new sigesp_c_generar_consecutivo();
	$ls_codvar= $io_keygen->uf_generar_numero_nuevo("SOB","sob_variacioncontrato","codvar","SOBVAR",3,"","codcon",$ls_codcon);
	$ls_fecvar="";
	$ls_motvar="";
	$ls_chk="";
	$ls_estvar="EMITIDO";
  
   $lb_validop=$io_variacion->uf_select_partidasasignadas($ls_codcon,&$la_partidas,&$li_totalfilas);
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
				$ls_canttot=$io_datastore->getValue("canxeje",$li_i);
				$ls_codasi=$io_datastore->getValue("codasi",$li_i);
				$ls_codobr=$io_datastore->getValue("codobr",$li_i);
				$la_objectpartidas[$li_i][1]="<input type=checkbox name=flagpar".$li_i." value=1 class=sin-borde>";
				$la_objectpartidas[$li_i][2]="<input name=txtcodpar".$li_i." type=text id=txtcodpar".$li_i." value='".$ls_codigo."' class=sin-borde style= text-align:center size=8 readonly><input name=codasi".$li_i." type=hidden id=codasi".$li_i." value='".$ls_codasi."'><input name=codobr".$li_i." type=hidden id=codobr".$li_i." value='".$ls_codobr."'>";
				$la_objectpartidas[$li_i][3]="<input name=txtnompar".$li_i." type=text id=txtnompar".$li_i." value='".$ls_nombre."' class=sin-borde style= text-align:left size=25 readonly>";
				$la_objectpartidas[$li_i][4]="<input name=txtnomuni".$li_i." type=text id=txtnomuni".$li_i." value='".$ls_unidad."' class=sin-borde size=5 style= text-align:center readonly>";
				$la_objectpartidas[$li_i][5]="<input name=txtpreuni".$li_i." type=text id=txtpreuni".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_preuni)."' class=sin-borde size=15 style= text-align:center readonly>";
				$la_objectpartidas[$li_i][6]="<input name=txtpreunimod".$li_i." type=text id=txtpreunimod".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_preuni)."' class=sin-borde size=15 style= text-align:center onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=ue_subtotal();>";
				$la_objectpartidas[$li_i][7]="<input name=txtcanttot".$li_i." type=text id=txtcanttot".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_canttot)."' class=sin-borde size=5 style= text-align:center readonly>";
				$la_objectpartidas[$li_i][8]="<input name=txtcantpar".$li_i." type=text id=txtcantpar".$li_i." value='0,00' class=sin-borde size=5 style= text-align:center onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=ue_subtotal();>";
				$la_objectpartidas[$li_i][9]="<input name=txttotal".$li_i." type=text id=txttotal".$li_i." class=sin-borde size=15 style= text-align:center readonly>";	
				
		}
		$li_filaspartidas=$li_filaspartidas+1;	
		$la_objectpartidas[$li_filaspartidas][1]="<input type=checkbox name=flagpar".$li_filaspartidas." value=1 disabled class=sin-borde>";
		$la_objectpartidas[$li_filaspartidas][2]="<input name=txtcodpar".$li_filaspartidas." type=text id=txtcodpar".$li_filaspartidas." class=sin-borde style= text-align:center size=8 readonly><input name=codasi".$li_filaspartidas." type=hidden id=codasi".$li_filaspartidas."><input name=codobr".$li_filaspartidas." type=hidden id=codobr".$li_filaspartidas.">";
		$la_objectpartidas[$li_filaspartidas][3]="<input name=txtnompar".$li_filaspartidas." type=text id=txtnompar".$li_filaspartidas." class=sin-borde style= text-align:left size=25 readonly>";
		$la_objectpartidas[$li_filaspartidas][4]="<input name=txtnomuni".$li_filaspartidas." type=text id=txtnomuni".$li_filaspartidas." class=sin-borde size=5 style= text-align:center readonly>";
		$la_objectpartidas[$li_filaspartidas][5]="<input name=txtpreuni".$li_filaspartidas." type=text id=txtpreuni".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectpartidas[$li_filaspartidas][6]="<input name=txtpreunimod".$li_filaspartidas." type=text id=txtpreunimod".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectpartidas[$li_filaspartidas][7]="<input name=txtcanttot".$li_filaspartidas." type=text id=txtcanttot".$li_filaspartidas." class=sin-borde size=5 style= text-align:center readonly>";
		$la_objectpartidas[$li_filaspartidas][8]="<input name=txtcantpar".$li_filaspartidas." type=text id=txtcantpar".$li_filaspartidas." class=sin-borde size=5 style= text-align:center readonly>";
		$la_objectpartidas[$li_filaspartidas][9]="<input name=txttotal".$li_filaspartidas." type=text id=txttotal".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	}
	$lb_valido=$io_variacion->uf_load_cuentas($ls_codcon,&$rs_datacuentas);
	if($lb_valido)
	{
		$li_i=0;
		while((!$rs_datacuentas->EOF))
		{
			$li_i++;
			$ls_codest1=$rs_datacuentas->fields["codestpro1"];
			$ls_codest2=$rs_datacuentas->fields["codestpro2"];
			$ls_codest3=$rs_datacuentas->fields["codestpro3"];
			$ls_codest4=$rs_datacuentas->fields["codestpro4"];
			$ls_codest5=$rs_datacuentas->fields["codestpro5"];
			$ls_estcla=$rs_datacuentas->fields["estcla"];
			$ls_cuenta=$rs_datacuentas->fields["spg_cuenta"];
			$li_disponible=$rs_datacuentas->fields["disponible"];
			if($ls_estcla=="A")
			{
				$ls_estcla="ACCION";
			}
			else
			{
				$ls_estcla="PROYECTO";
			}
			$io_fun_sob->uf_formatoprogramatica($ls_codest1.$ls_codest2.$ls_codest3.$ls_codest4.$ls_codest5,&$ls_programatica);

			$la_objectcuentas[$li_i][1]="<input name=txtcodcue".$li_i." type=text id=txtcodcue".$li_i." class=sin-borde style= text-align:center size=40 value='".$ls_programatica."' readonly><input name=codest1".$li_i." type=hidden id=codest1".$li_i." value='".$ls_codest1."'><input name=codest2".$li_i." type=hidden id=codest2".$li_i." value='".$ls_codest2."'><input name=codest3".$li_i." type=hidden id=codest3".$li_i." value='".$ls_codest3."'><input name=codest4".$li_i." type=hidden id=codest4".$li_i." value='".$ls_codest4."'><input name=codest5".$li_i." type=hidden id=codest5".$li_i." value='".$ls_codest5."'>";
			$la_objectcuentas[$li_i][2]="<input name=txtestcla".$li_i." type=text id=txtestcla".$li_i." class=sin-borde style= text-align:center size=20 value='".$ls_estcla."' readonly>";
			$la_objectcuentas[$li_i][3]="<input name=txtnomcue".$li_i." type=text id=txtnomcue".$li_i." class=sin-borde style= text-align:left size=10 value='".$ls_cuenta."' readonly >";
			$la_objectcuentas[$li_i][4]="<input name=txtmoncue".$li_i." type=text id=txtmoncue".$li_i." class=sin-borde size=20 style= text-align:center value='' onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=javascript:ue_validardispo()><input name=disponible".$li_i." type=hidden id=disponible".$li_i." value='".$li_disponible."'>";
			$la_objectcuentas[$li_i][5]="<div align=center><a href=javascript:ue_removercuenta(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a></div>";

			$rs_datacuentas->MoveNext();
		}
		$li_filascuentas=$li_i+1;
		$la_objectcuentas[$li_filascuentas][1]="<input name=txtcodcue".$li_filascuentas." type=text id=txtcodcue".$li_filascuentas." class=sin-borde style= text-align:center size=40 readonly><input name=codest1".$li_filascuentas." type=hidden id=codest1".$li_filascuentas."><input name=codest2".$li_filascuentas." type=hidden id=codest2".$li_filascuentas."><input name=codest3".$li_filascuentas." type=hidden id=codest3".$li_filascuentas."><input name=codest4".$li_filascuentas." type=hidden id=codest4".$li_filascuentas."><input name=codest5".$li_filascuentas." type=hidden id=codest5".$li_filascuentas.">";
		$la_objectcuentas[$li_filascuentas][2]="<input name=txtestcla".$li_filascuentas." type=text id=txtestcla".$li_filascuentas." class=sin-borde style= text-align:center size=20 readonly>";
		$la_objectcuentas[$li_filascuentas][3]="<input name=txtnomcue".$li_filascuentas." type=text id=txtnomcue".$li_filascuentas." class=sin-borde style= text-align:left size=10 readonly>";
		$la_objectcuentas[$li_filascuentas][4]="<input name=txtmoncue".$li_filascuentas." type=text id=txtmoncue".$li_filascuentas." class=sin-borde size=20 style= text-align:center readonly><input name=disponible".$li_filascuentas." type=hidden id=disponible".$li_filascuentas." value=''>";
		$la_objectcuentas[$li_filascuentas][5]="<input name=txtvacio".$li_filascuentas." type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	}

}
/***************************************************************************************************************************************************************************/

/*******************************************INSERTAR CAMPO EN GRID CUENTAS********************************************************************************************************************************/
elseif($ls_operacion=="ue_cargarcuenta")
{
	
	$li_filascuentas=$_POST["filascuentas"];
	$li_filascuentas=$li_filascuentas+1;
	
	for($li_i=1;$li_i<$li_filascuentas;$li_i++)
	{
		$ls_codigo=$_POST["txtcodcue".$li_i];
		$ls_codest1=$_POST["codest1".$li_i];
		$ls_codest2=$_POST["codest2".$li_i];
		$ls_codest3=$_POST["codest3".$li_i];
		$ls_codest4=$_POST["codest4".$li_i];
		$ls_codest5=$_POST["codest5".$li_i];
		$ls_disponible=$_POST["disponible".$li_i];
		$ls_nombre=$_POST["txtnomcue".$li_i];
		$ls_moncar=$_POST["txtmoncue".$li_i];
		$ls_estcla=$_POST["txtestcla".$li_i];
		$la_objectcuentas[$li_i][1]="<input name=txtcodcue".$li_i." type=text id=txtcodcue".$li_i." class=sin-borde style= text-align:center size=40 value='".$ls_codigo."' readonly><input name=codest1".$li_i." type=hidden id=codest1".$li_i." value='".$ls_codest1."'><input name=codest2".$li_i." type=hidden id=codest2".$li_i." value='".$ls_codest2."'><input name=codest3".$li_i." type=hidden id=codest3".$li_i." value='".$ls_codest3."'><input name=codest4".$li_i." type=hidden id=codest4".$li_i." value='".$ls_codest4."'><input name=codest5".$li_i." type=hidden id=codest5".$li_i." value='".$ls_codest5."'>";
		$la_objectcuentas[$li_i][2]="<input name=txtestcla".$li_i." type=text id=txtestcla".$li_i." class=sin-borde style= text-align:center size=20 value='".$ls_estcla."' readonly>";
		$la_objectcuentas[$li_i][3]="<input name=txtnomcue".$li_i." type=text id=txtnomcue".$li_i." class=sin-borde style= text-align:left size=10 value='".$ls_nombre."' readonly >";
		$la_objectcuentas[$li_i][4]="<input name=txtmoncue".$li_i." type=text id=txtmoncue".$li_i." class=sin-borde size=20 style= text-align:center value='".$ls_moncar."' onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=javascript:ue_validardispo()><input name=disponible".$li_i." type=hidden id=disponible".$li_i." value='".$ls_disponible."'>";
		$la_objectcuentas[$li_i][5]="<div align=center><a href=javascript:ue_removercuenta(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a></div>";
	}	
	$la_objectcuentas[$li_filascuentas][1]="<input name=txtcodcue".$li_filascuentas." type=text id=txtcodcue".$li_filascuentas." class=sin-borde style= text-align:center size=40 readonly><input name=codest1".$li_filascuentas." type=hidden id=codest1".$li_filascuentas."><input name=codest2".$li_filascuentas." type=hidden id=codest2".$li_filascuentas."><input name=codest3".$li_filascuentas." type=hidden id=codest3".$li_filascuentas."><input name=codest4".$li_filascuentas." type=hidden id=codest4".$li_filascuentas."><input name=codest5".$li_filascuentas." type=hidden id=codest5".$li_filascuentas.">";
	$la_objectcuentas[$li_filascuentas][2]="<input name=txtestcla".$li_filascuentas." type=text id=txtestcla".$li_filascuentas." class=sin-borde style= text-align:center size=20 readonly>";
	$la_objectcuentas[$li_filascuentas][3]="<input name=txtnomcue".$li_filascuentas." type=text id=txtnomcue".$li_filascuentas." class=sin-borde style= text-align:left size=10 readonly>";
	$la_objectcuentas[$li_filascuentas][4]="<input name=txtmoncue".$li_filascuentas." type=text id=txtmoncue".$li_filascuentas." class=sin-borde size=20 style= text-align:center readonly><input name=disponible".$li_filascuentas." type=hidden id=disponible".$li_filascuentas." value=''>";
	$la_objectcuentas[$li_filascuentas][5]="<input name=txtvacio".$li_filascuentas." type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
}
/***************************************************************************************************************************************************************************/

/***************************************REMOVER CAMPO EN GRID CUENTAS************************************************************************************************************************************/
elseif($ls_operacion=="ue_removercuenta")
{
   
	$li_filascuentas=$_POST["filascuentas"];
	$li_filascuentas=$li_filascuentas-1;
	$li_removercuenta=$_POST["hidremovercuenta"];
	$li_temp=0;
	for($li_i=1;$li_i<=$li_filascuentas;$li_i++)
	{
		if($li_i!=$li_removercuenta)
		{		
			$li_temp=$li_temp+1;
			$ls_codigo=$_POST["txtcodcue".$li_i];
		    $ls_codest1=$_POST["codest1".$li_i];
		    $ls_codest2=$_POST["codest2".$li_i];
		    $ls_codest3=$_POST["codest3".$li_i];
		    $ls_codest4=$_POST["codest4".$li_i];
		    $ls_codest5=$_POST["codest5".$li_i];
			$ls_disponible=$_POST["disponible".$li_i];
		    $ls_nombre=$_POST["txtnomcue".$li_i];
		    $ls_moncar=$_POST["txtmoncue".$li_i];
		    $ls_estcla=$_POST["txtestcla".$li_i];
		    $la_objectcuentas[$li_i][1]="<input name=txtcodcue".$li_i." type=text id=txtcodcue".$li_i." class=sin-borde style= text-align:center size=40 value='".$ls_codigo."' readonly><input name=codest1".$li_i." type=hidden id=codest1".$li_i." value='".$ls_codest1."'><input name=codest2".$li_i." type=hidden id=codest2".$li_i." value='".$ls_codest2."'><input name=codest3".$li_i." type=hidden id=codest3".$li_i." value='".$ls_codest3."'><input name=codest4".$li_i." type=hidden id=codest4".$li_i." value='".$ls_codest4."'><input name=codest5".$li_i." type=hidden id=codest5".$li_i." value='".$ls_codest5."'>";
			$la_objectcuentas[$li_i][2]="<input name=txtestcla".$li_i." type=text id=txtestcla".$li_i." class=sin-borde style= text-align:center size=20 value='".$ls_estcla."' readonly>";
		    $la_objectcuentas[$li_i][3]="<input name=txtnomcue".$li_i." type=text id=txtnomcue".$li_i." class=sin-borde style= text-align:left size=10 value='".$ls_nombre."' readonly >";
		    $la_objectcuentas[$li_i][4]="<input name=txtmoncue".$li_i." type=text id=txtmoncue".$li_i." class=sin-borde size=20 style= text-align:center value='".$ls_moncar."' onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=javascript:ue_validardispo()><input name=disponible".$li_i." type=hidden id=disponible".$li_i." value='".$ls_disponible."'>";
		    $la_objectcuentas[$li_i][5]="<div align=center><a href=javascript:ue_removercuenta(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a></div>";
		}
	}
	$la_objectcuentas[$li_filascuentas][1]="<input name=txtcodcue".$li_filascuentas." type=text id=txtcodcue".$li_filascuentas." class=sin-borde style= text-align:center size=40 readonly><input name=codest1".$li_filascuentas." type=hidden id=codest1".$li_filascuentas."><input name=codest2".$li_filascuentas." type=hidden id=codest2".$li_filascuentas."><input name=codest3".$li_filascuentas." type=hidden id=codest3".$li_filascuentas."><input name=codest4".$li_filascuentas." type=hidden id=codest4".$li_filascuentas."><input name=codest5".$li_filascuentas." type=hidden id=codest5".$li_filascuentas.">";
	$la_objectcuentas[$li_filascuentas][2]="<input name=txtestcla".$li_filascuentas." type=text id=txtestcla".$li_filascuentas." class=sin-borde style= text-align:center size=20 value='".$ls_estcla."' readonly>";
	$la_objectcuentas[$li_filascuentas][3]="<input name=txtnomcue".$li_filascuentas." type=text id=txtnomcue".$li_filascuentas." class=sin-borde style= text-align:left size=10 readonly>";
	$la_objectcuentas[$li_filascuentas][4]="<input name=txtmoncue".$li_filascuentas." type=text id=txtmoncue".$li_filascuentas." class=sin-borde size=20 style= text-align:center readonly><input name=disponible".$li_filascuentas." type=hidden id=disponible".$li_filascuentas.">";
	$la_objectcuentas[$li_filascuentas][5]="<input name=txtvacio".$li_filascuentas." type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
}
/***************************************************************************************************************************************************************************/

/************************************************ PREPARANDO INSERCION DE NUEVO REGISTRO ****************************************************************************/
elseif($ls_operacion=="ue_nuevo")
{
    $ls_opemostrar="";
	$ls_operacion="";
	$ls_codcon="";
	$ls_codvar="";
	$ls_tipvar="";
	$ls_motvar="";
	$ls_fecvar="";
	$ls_monto="0,00";
	$ls_monco="0,00";
	$ls_estvar=""; 
	$ls_chk="";
	$ls_totcon="";
		
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
	
	
	$li_filascuentas=1;
	$la_objectcuentas[1][1]="<input name=txtcodcue1 type=text id=txtcodcue1 class=sin-borde style= text-align:center size=40 readonly><input name=codest11 type=hidden id=codest11><input name=codest21 type=hidden id=codest21><input name=codest31 type=hidden id=codest31><input name=codest41 type=hidden id=codest41><input name=codest51 type=hidden id=codest51>";
	$la_objectcuentas[1][2]="<input name=txtestcla1 type=text id=txtestcla1 class=sin-borde style= text-align:center size=20  readonly>";
	$la_objectcuentas[1][3]="<input name=txtnomcue1 type=text id=txtnomcue1 class=sin-borde style= text-align:left size=10 readonly>";
	$la_objectcuentas[1][4]="<input name=txtmoncue1 type=text id=txtmoncue1 class=sin-borde style= text-align:center size=20 readonly><input name=disponible1 type=hidden id=disponible1";
	$la_objectcuentas[1][5]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
	
	
}
/***************************************************************************************************************************************************************************/


/*******************************************INSERCION DE REGISTRO EN BD*******************************************************************************************************************************/
elseif($ls_operacion=="ue_guardar")
{
  $ld_date=$io_function->uf_convertirdatetobd($ls_fecvar);
  $io_variacion->io_sql->begin_transaction();
  $ls_codvaraux=$ls_codvar;
  $lb_valido=$io_variacion->uf_guardar_variacion($ls_codvar,$ls_codcon,$ls_tipvar,$ls_motvar,$ld_date,$ls_monto,$ls_chk,$la_seguridad);
  if($lb_valido)
  {
    $lb_valido=$io_variacion->uf_update_montocontrato($ls_codcon,$ls_monco,$la_seguridad);
	if($lb_valido)
	{
	   /*************  GUARDANDO PARTIDAS  ********************/
		$li_partidas=1;	
		for ($li_i=1;$li_i<$li_filaspartidas;$li_i++)
		 {
		  if(!empty($_POST["flagpar".$li_i]))
		   {
			$la_partidas["codpar"][$li_partidas]=$_POST["txtcodpar".$li_i];
			$la_partidas["cantant"][$li_partidas]=$_POST["txtcanttot".$li_i];
			$la_partidas["cantnew"][$li_partidas]=$_POST["txtcantpar".$li_i];
			$la_partidas["preant"][$li_partidas]=$_POST["txtpreuni".$li_i];
			$la_partidas["prenew"][$li_partidas]=$_POST["txtpreunimod".$li_i];
			$la_partidas["codasi"][$li_partidas]=$_POST["codasi".$li_i];
			$la_partidas["codobr"][$li_partidas]=$_POST["codobr".$li_i];
			$li_partidas++;
		   }
		 }
		$lb_valido=$io_variacion->uf_update_dtpartidas($ls_codvar,$ls_codcon,$la_partidas,$li_partidas,$la_seguridad);
	   /***********************************************/
	}
	if($lb_valido)
	{
	   /**********  GUARDANDO CUENTAS  ***************/	
		for ($li_i=1;$li_i<$li_filascuentas;$li_i++)
		 {
		   $la_cuentas["codcue"][$li_i]=$_POST["txtnomcue".$li_i];
		   $la_cuentas["codest1"][$li_i]=str_pad($_POST["codest1".$li_i],25,'0',STR_PAD_LEFT);
		   $la_cuentas["codest2"][$li_i]=str_pad($_POST["codest2".$li_i],25,'0',STR_PAD_LEFT);
		   $la_cuentas["codest3"][$li_i]=str_pad($_POST["codest3".$li_i],25,'0',STR_PAD_LEFT);
		   $la_cuentas["codest4"][$li_i]=str_pad($_POST["codest4".$li_i],25,'0',STR_PAD_LEFT);
		   $la_cuentas["codest5"][$li_i]=str_pad($_POST["codest5".$li_i],25,'0',STR_PAD_LEFT);
		   $la_cuentas["estcla"][$li_i]=$_POST["txtestcla".$li_i];
		   $la_cuentas["moncue"][$li_i]=$_POST["txtmoncue".$li_i];
		 }
		$lb_valido=$io_variacion->uf_update_dtcuentas($ls_codvar,$ls_codcon,$la_cuentas,$li_filascuentas,$la_seguridad);
		/***********************************************/
	}
	if($lb_valido)
	{
  		if($ls_codvaraux!=$ls_codvar)
		{
			 $io_msg->message("Se le asigno un  nuevo numero ".$ls_codvar);
		}
	 	$io_msg->message("El proceso se ejecuto correctamente");
		$io_variacion->io_sql->commit();
	}
	else
	{
	 	$io_msg->message("Ocurrio un error al procesar la solicitud");
		$io_variacion->io_sql->rollback();
	}
/*	 print "<script language=javascript>";
	 print "location.href=location";
	 print "</script>";*/
  }
}
/***************************************************************************************************************************************************************************/

/*******************************************ANULAR UNA ASIGNACION*******************************************************************************************************************************/
elseif($ls_operacion=="ue_anular")
{   
   $io_variacion->uf_select_estado($ls_codvar,$ls_codcon,&$ls_estasi);
   if(($ls_estasi==1)||($ls_estasi==6))
   { 
     $io_variacion->uf_update_estado($ls_codvar,$ls_codcon,3,$la_seguridad);
	 $io_msg->message("Esta Variacion fue Anulada!!");
   }
   else
   {
    $io_msg->message("Esta Variacion no puede ser Anulada!!");
   }
  print "<script language=javascript>";
  print "location.href=location";
  print "</script>";
}
/***************************************************************************************************************************************************************************/

/*******************************************CARGAR DATOS DE UNA ASIGNACION*******************************************************************************************************************************/
elseif($ls_operacion=="ue_cargarvariacion")
{   
    $ls_tipvar=$_POST["hidtipvar"];
	$ls_codasi=$_POST["hidcodasi"];
		
	$lb_validop=$io_variacion->uf_select_allpartidas($ls_codvar,$ls_codasi,&$la_partidas,&$li_totalfilas);
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
			$ls_preunimod=$io_datastore->getValue("prenue",$li_i);
			$ls_canttot=$io_datastore->getValue("canorigi",$li_i);
			$ls_canvalpar=$io_datastore->getValue("cannue",$li_i);
			$ls_codasi=$io_datastore->getValue("codasi",$li_i);
			$ls_codobr=$io_datastore->getValue("codobr",$li_i);
			$ld_ncanttot=$ls_canttot-$ls_canvalpar;
			if($ls_canvalpar=="")
			{
			 $la_objectpartidas[$li_i][1]="<input type=checkbox name=flagpar".$li_i." value=1  class=sin-borde>";
			 $ls_total="0,00";
			}
			else
			{
			 $la_objectpartidas[$li_i][1]="<input type=checkbox name=flagpar".$li_i." value=1 checked class=sin-borde>";
			 $ls_total=$ls_canvalpar*$ls_preunimod;
			 
			}
	        $la_objectpartidas[$li_i][2]="<input name=txtcodpar".$li_i." type=text id=txtcodpar".$li_i." value='".$ls_codigo."' class=sin-borde style= text-align:center size=8 readonly><input name=codasi".$li_i." type=hidden id=codasi".$li_i." value='".$ls_codasi."'><input name=codobr".$li_i." type=hidden id=codobr".$li_i." value='".$ls_codobr."'>";
	        $la_objectpartidas[$li_i][3]="<input name=txtnompar".$li_i." type=text id=txtnompar".$li_i." value='".$ls_nombre."' class=sin-borde style= text-align:left size=25 readonly>";
	        $la_objectpartidas[$li_i][4]="<input name=txtnomuni".$li_i." type=text id=txtnomuni".$li_i." value='".$ls_unidad."' class=sin-borde size=5 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][5]="<input name=txtpreuni".$li_i." type=text id=txtpreuni".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_preuni)."' class=sin-borde size=15 style= text-align:center readonly>";
			$la_objectpartidas[$li_i][6]="<input name=txtpreunimod".$li_i." type=text id=txtpreunimod".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_preunimod)."' class=sin-borde size=15 style= text-align:center onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=ue_subtotal();>";
	        $la_objectpartidas[$li_i][7]="<input name=txtcanttot".$li_i." type=text id=txtcanttot".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ld_ncanttot)."' class=sin-borde size=5 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][8]="<input name=txtcantpar".$li_i." type=text id=txtcantpar".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_canvalpar)."' class=sin-borde size=5 style= text-align:center onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=ue_subtotal();>";
	        $la_objectpartidas[$li_i][9]="<input name=txttotal".$li_i." type=text id=txttotal".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_total)."'  class=sin-borde size=15 style= text-align:center readonly>";	
	}
	$li_filaspartidas=$li_filaspartidas+1;	
	$la_objectpartidas[$li_filaspartidas][1]="<input type=checkbox name=flagpar".$li_filaspartidas." value=1 disabled class=sin-borde>";
	$la_objectpartidas[$li_filaspartidas][2]="<input name=txtcodpar".$li_filaspartidas." type=text id=txtcodpar".$li_filaspartidas." class=sin-borde style= text-align:center size=8 readonly><input name=codasi".$li_filaspartidas." type=hidden id=codasi".$li_filaspartidas."><input name=codobr".$li_filaspartidas." type=hidden id=codobr".$li_filaspartidas.">";
	$la_objectpartidas[$li_filaspartidas][3]="<input name=txtnompar".$li_filaspartidas." type=text id=txtnompar".$li_filaspartidas." class=sin-borde style= text-align:left size=25 readonly>";
	$la_objectpartidas[$li_filaspartidas][4]="<input name=txtnomuni".$li_filaspartidas." type=text id=txtnomuni".$li_filaspartidas." class=sin-borde size=5 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][5]="<input name=txtpreuni".$li_filaspartidas." type=text id=txtpreuni".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][6]="<input name=txtpreunimod".$li_filaspartidas." type=text id=txtpreunimod".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][7]="<input name=txtcanttot".$li_filaspartidas." type=text id=txtcanttot".$li_filaspartidas." class=sin-borde size=5 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][8]="<input name=txtcantpar".$li_filaspartidas." type=text id=txtcantpar".$li_filaspartidas." class=sin-borde size=5 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][9]="<input name=txttotal".$li_filaspartidas." type=text id=txttotal".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	}
   /*****************************************************************************************/
	
	/*************************CARGANDO CUENTAS**********************/
	$lb_validoc=$io_variacion->uf_select_cuentas($ls_codvar,$ls_codcon,$la_cuentas,$li_totalfilas);
	if($lb_validoc)
	{
	$io_datastore->data=$la_cuentas;
	$li_filascuentas=$io_datastore->getRowCount("spg_cuenta");
	for($li_i=1;$li_i<=$li_filascuentas;$li_i++)
	{
		$ls_codest1=$io_funcsob->uf_convertir_cadenanumero($io_datastore->getValue("codestpro1",$li_i));
		$ls_codest2=$io_funcsob->uf_convertir_cadenanumero($io_datastore->getValue("codestpro2",$li_i));
		$ls_codest3=$io_funcsob->uf_convertir_cadenanumero($io_datastore->getValue("codestpro3",$li_i));
		$ls_codest4=$io_funcsob->uf_convertir_cadenanumero($io_datastore->getValue("codestpro4",$li_i));
		$ls_codest5=$io_funcsob->uf_convertir_cadenanumero($io_datastore->getValue("codestpro5",$li_i));
		$ls_codigo=$ls_codest1.$ls_codest2.$ls_codest3.$ls_codest4.$ls_codest5;
		$ls_nombre=$io_datastore->getValue("spg_cuenta",$li_i);
		$ls_moncar=$io_datastore->getValue("monto",$li_i);
		$ls_disponible=$io_datastore->getValue("disponible",$li_i);
		$ls_estcla=$io_datastore->getValue("estcla",$li_i);
		if($ls_estcla=="A")
		{
			$ls_estcla="ACCION";
		}
		else
		{
			$ls_estcla="PROYECTO";
		}
		$la_objectcuentas[$li_i][1]="<input name=txtcodcue".$li_i." type=text id=txtcodcue".$li_i." class=sin-borde style= text-align:center size=40 value='".$ls_codigo."' readonly><input name=codest1".$li_i." type=hidden id=codest1".$li_i." value='".$ls_codest1."'><input name=codest2".$li_i." type=hidden id=codest2".$li_i." value='".$ls_codest2."'><input name=codest3".$li_i." type=hidden id=codest3".$li_i." value='".$ls_codest3."'><input name=codest4".$li_i." type=hidden id=codest4".$li_i." value='".$ls_codest4."'><input name=codest5".$li_i." type=hidden id=codest5".$li_i." value='".$ls_codest5."'>";
		$la_objectcuentas[$li_i][2]="<input name=txtestcla".$li_i." type=text id=txtestcla".$li_i." class=sin-borde style= text-align:center size=20 value='".$ls_estcla."' readonly>";
		$la_objectcuentas[$li_i][3]="<input name=txtnomcue".$li_i." type=text id=txtnomcue".$li_i." class=sin-borde style= text-align:left size=10 value='".$ls_nombre."' readonly >";
		$la_objectcuentas[$li_i][4]="<input name=txtmoncue".$li_i." type=text id=txtmoncue".$li_i." class=sin-borde size=20 style= text-align:center value='".$io_funcsob->uf_convertir_numerocadena($ls_moncar)."' onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=javascript:ue_validardispo()><input name=disponible".$li_i." type=hidden id=disponible".$li_i." value='".$ls_disponible."'>";
		$la_objectcuentas[$li_i][5]="<div align=center><a href=javascript:ue_removercuenta(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a></div>";
	}	
	$li_filascuentas=$li_filascuentas+1;
	$la_objectcuentas[$li_filascuentas][1]="<input name=txtcodcue".$li_filascuentas." type=text id=txtcodcue".$li_filascuentas." class=sin-borde style= text-align:center size=40 readonly><input name=codest1".$li_filascuentas." type=hidden id=codest1".$li_filascuentas."><input name=codest2".$li_filascuentas." type=hidden id=codest2".$li_filascuentas."><input name=codest3".$li_filascuentas." type=hidden id=codest3".$li_filascuentas."><input name=codest4".$li_filascuentas." type=hidden id=codest4".$li_filascuentas."><input name=codest5".$li_filascuentas." type=hidden id=codest5".$li_filascuentas.">";
	$la_objectcuentas[$li_filascuentas][2]="<input name=txtestcla".$li_filascuentas." type=text id=txtestcla".$li_filascuentas." class=sin-borde style= text-align:center size=20  readonly>";
	$la_objectcuentas[$li_filascuentas][3]="<input name=txtnomcue".$li_filascuentas." type=text id=txtnomcue".$li_filascuentas." class=sin-borde style= text-align:left size=10 readonly>";
	$la_objectcuentas[$li_filascuentas][4]="<input name=txtmoncue".$li_filascuentas." type=text id=txtmoncue".$li_filascuentas." class=sin-borde size=20 style= text-align:center readonly><input name=disponible".$li_filascuentas." type=hidden id=disponible".$li_filascuentas." value='".$ls_disponible."'>";
	$la_objectcuentas[$li_filascuentas][5]="<input name=txtvacio".$li_filascuentas." type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
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
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
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
  <input name="hidmonco" type="hidden" id="hidmonco" value="<?php print $ls_totcon; ?>">
  
  
  <table width="780" border="0" align="center" cellpadding="0" cellspacing="0"  class="contorno">
      <tr class="formato-blanco">
        <th colspan="5" class="titulo-celdanew" scope="col">Datos del Contrato</th>
      </tr>
      <tr class="formato-blanco">
        <th colspan="5" scope="col"></th>
      </tr>
       <tr class="formato-blanco">
         <td height="22">&nbsp;</td>
         <td height="22">&nbsp;</td>
         <td colspan="3">&nbsp;</td>
       </tr>
       <tr class="formato-blanco">
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right">Contrato</div></td>
        <td colspan="3"><input name="txtcodcon" type="text" id="txtcodcon" style="text-align:center " value="<?php print $ls_codcon; ?>" size="15" maxlength="12" readonly="true">
        <a href="javascript:ue_catcontrato();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>         </td>
      </tr>
      <tr class="formato-blanco">
      <td height="13" colspan="6" align="center" valign="top" class="sin-borde"><div align="right"><a href="javascript:ue_uf_mostrar_ocultar_obra();">&nbsp;&nbsp;<img src="../shared/imagebank/mas.gif" width="9" height="17" border="0"></a><a href="javascript:uf_mostrar_ocultar_contrato();">Datos del Contrato </a>
				   </div></td>
    </tr>
	   <?php
	    if($ls_opemostrar=="MOSTRAR")
		 { 
		   $ls_codcon=$_POST["txtcodcon"];
           $io_valuacion->uf_select_contrato($ls_codcon,&$la_contrato);
           $io_valuacion->uf_select_anticipos($ls_codcon,&$ls_totant);
           $io_valuacion->uf_select_variaciones($ls_codcon,&$ld_aum,1);
           $io_valuacion->uf_select_variaciones($ls_codcon,&$ld_dis,2);
           $ls_desobr=$la_contrato["desobr"][1];
           $ls_puncue=$la_contrato["puncueasi"][1];
           $ls_estcon=$io_funcsob->uf_convertir_numeroestado ($la_contrato["estcon"][1]);
           $ls_moncon=$la_contrato["monto"][1];
           $ls_feccon=$io_function->uf_convertirfecmostrar($la_contrato["feccon"][1]);
           $ls_totcon=$la_contrato["monto"][1]+$ld_aum+$ld_dis;
	    ?>
      <tr class="formato-blanco">
        <td height="68" colspan="6" align="center" valign="top" class="sin-borde"><table width="544" height="137" border="0" cellpadding="0" cellspacing="4" class="formato-blanco">
          <tr>
            <td>&nbsp;</td>
            <td width="129">&nbsp;</td>
            <td width="62"><div align="right">Estatus</div></td>
            <td width="111"><span class="style6">
              <input name="txtestobr" type="text" class="celdas-grises" id="txtestobr"  style="text-align:left" value="<?php print $ls_estcon; ?>" size="20" maxlength="20" readonly="true">
            </span></td>
          </tr>
          <tr>
            <td width="220"><div align="right"><span class="style6">Descripcion de la Obra</span></div></td>
            <td colspan="3"><span class="style6">
              <textarea name="txtdesobr" cols="55" rows="2" readonly="true" id="txtdesobr" style="text-align:left"><?php print $ls_desobr; ?></textarea>
            </span><span class="style6"></span></td>
          </tr>
          <tr>
            <td><div align="right">Fecha Contrato </div></td>
            <td><span class="style6">
              <input name="txtfeccon" type="text" id="txtfeccon"  style="text-align: center" value="<?php print $ls_feccon; ?>" size="10" maxlength="10" readonly="true">
            </span></td>
            <td><div align="right"></div></td>
        
		    <td><span class="style6">
              <input name="txtpuncueasi" type="hidden" id="txtpuncuenasi"  style="text-align: center" value="<?php print $ls_puncue;  ?>" size="20" maxlength="20" readonly="true">
            </span></td>
          </tr>
          <tr>
            <td><div align="right"><span class="style6">Monto Contrato </span></div></td>
            <td><span class="style6">
              <input name="txtmoncon" type="text" id="txtmoncon"  style="text-align: right" value="<?php print $io_funcsob->uf_convertir_numerocadena($ls_moncon) ?>" size="20" maxlength="20" readonly="true">
            </span></td>
            <td><div align="right">Anticipo</div></td>
            <td><span class="style6">
              <input name="txtmonant" type="text" id="txtmonant"  style="text-align: right" value="<?php print $io_funcsob->uf_convertir_numerocadena($ls_totant) ?>" size="20" maxlength="20" readonly="true">
            </span></td>
          </tr>
          <tr>
            <td><div align="right">Contrato + Aumentos - Disminuciones </div></td>
            <td><span class="style6">
              <input name="txtmontotcon" type="text" id="txtmontotcon"  style="text-align: right" value="<?php print $io_funcsob->uf_convertir_numerocadena($ls_totcon) ?>" size="20" maxlength="20" readonly="true">
            </span></td>
            <td>&nbsp;</td>
            <td><span class="style6"> </span></td>
          </tr>
        </table></td>
    </tr>
	   <?
		}
	   ?>
      <tr class="formato-blanco">
        <td height="13" colspan="6" align="center" valign="top" class="sin-borde">&nbsp;</td>
      </tr>
	  <tr class="titulo-celdanew">
        <th colspan="5" scope="col" class="titulo-celdanew">Variaci&oacute;n del Contrato</th>
    </tr>
      <tr class="formato-blanco">
        <td colspan="5">
		<input name="operacion" type="hidden" id="operacion">
		<input name="opemostrar" type="hidden" id="opemostrar" value="<?php print $ls_opemostrar; ?>">
		<input name="hidtipvar" type="hidden" id="hidtipvar">
		<input name="hidcodasi" type="hidden" id="hidcodasi">        <input name="hidestapr" type="hidden" id="hidestapr" value="<?php print $ls_estapr;?>"></td>
      </tr>
      <tr class="formato-blanco">
        <td height="22">&nbsp;</td>
        <td>&nbsp;</td>
        <td width="228">&nbsp;</td>
        <td width="162"><div align="right">Estado</div></td>
        <td width="207"><span class="style6">
          <input name="txtestvar" type="text" class="celdas-grises" id="txtestvar"  style="text-align: center" value="<?php print $ls_estvar; ?>" size="15" maxlength="15" readonly="true">
        </span></td>
      </tr>
      <tr class="formato-blanco">
        <td width="65" height="22"><div align="right"></div></td>
        <td width="116">          <div align="right">C&oacute;digo</div></td>
        <td>
          <div align="left">
            <input name="txtcodvar" id="txtcodvar" style="text-align:center " value="<?php print $ls_codvar; ?>" readonly="true" type="text" size="3" maxlength="3">
          </div>          
          <div align="right"></div></td>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right">Tipo Variaci&oacute;n</div></td>
        <td colspan="3">
		  <select name="cmbtipvar" id="cmbtipvar">
		    <?php 
			  if(($ls_tipvar=="")||($ls_tipvar=="s1"))
			   {
			?>
          <option value="s1" selected>Seleccione</option>
		  <option value="1">Aumento</option>
          <option value="2">Disminuci&oacute;n</option>
		    <?php
			   }
			   else
			   {
			    if($ls_tipvar=="1")
				{
			?>
			 <option value="s1" >Seleccione</option>
		     <option value="1" selected>Aumento</option>
             <option value="2">Disminuci&oacute;n</option>
            <?php 
			    }
			    else
			    {
			?>
			 <option value="s1">Seleccione</option>
		     <option value="1">Aumento</option>
             <option value="2" selected>Disminuci&oacute;n</option>
			<?php
		        } 	
			   } 
			?>
        </select></td>
      </tr>
      <tr class="formato-blanco">
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right">Fecha</div></td>
        <td colspan="3"><input name="txtfecvar" type="text" id="txtfecvar"  style="text-align: left" value="<?php print $ls_fecvar; ?>" size="11" maxlength="11"   onKeyPress="javascript:ue_validafecha();" datepicker="true" readonly="true"></td>
      </tr>
      <tr class="formato-blanco">
        <td height="22"><div align="right"></div></td>
        <td height="22"><div align="right">Concepto</div></td>
        <td colspan="3"><textarea name="txtmotvar" cols="80" rows="2" id="txtmotvar"><?php print $ls_motvar; ?></textarea></td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="5">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="5"></td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="5"><div align="center">
          <table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr class="formato-blanco">
              <td width="15" height="13">&nbsp;</td>
              <td width="593"><div align="left"></div></td>
            </tr>
            <tr align="center" class="formato-blanco">
              <td colspan="2"><?php $io_grid->makegrid($li_filaspartidas,$la_columpartidas,$la_objectpartidas,$li_anchopartidas,$ls_titulopartidas,$ls_nametable);?></td>
            </tr>
            <input name="filaspartidas" type="hidden" id="filaspartidas" value="<?php print $li_filaspartidas; ?>">
            <tr class="formato-blanco">
              <td colspan="2">&nbsp;</td>
            </tr>
          </table>
        </div></td>
      </tr>
      <tr class="formato-blanco">
        <td height="23">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><div align="right">Monto de la Variaci&oacute;n </div></td>
        <td><input name="txtmonto" type="text" id="txtmonto"  style="text-align: right" value="<?php print $ls_monto; ?>" size="21" maxlength="21" readonly="true"></td>
      </tr>
      <tr class="formato-blanco">
        <td height="23" colspan="5"><table width="609" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr class="formato-blanco">
            <td width="14" height="11">&nbsp;</td>
            <td width="593">&nbsp;</td>
          </tr>
          <tr align="center" class="formato-blanco">
            <td height="11" colspan="2">
              <?php $io_grid->makegrid($li_filascuentas,$la_columcuentas,$la_objectcuentas,$li_anchocuentas,$ls_titulocuentas,$ls_nametable);?>            </td>
            <input name="filascuentas" type="hidden" id="filascuentas" value="<?php print $li_filascuentas; ?>">
            <input name="hidremovercuenta" type="hidden" id="hidremovercuenta" value="<?php print $li_removercuenta; ?>">
          </tr>
          <tr class="formato-blanco">
            <td colspan="2">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr class="formato-blanco">
        <td height="23">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><div align="right">Nuevo Monto del Contrato</div></td>
        <td><input name="txtmoncon" type="text" id="txtmoncon"  style="text-align: right" value="<?php print $ls_monco; ?>" size="20" maxlength="20" onKeyPress="return(currencyFormat(this,'.',',',event))" onBlur="uf_validarbasimp();"></td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="5">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="5"><div align="center">
          </div></td>
      </tr>
    </table>
  <input name="hidstatus" type="hidden" id="hidstatus"  value="<?php print $ls_chk; ?>">
    <div align="center"></div>
  </form>
</body>
<script language="javascript">

/*******************************************CATALOGOS********************************************************************************************************/

function ue_catcontrato()
{
	f=document.form1;
	f.operacion.value="";
	var opener="variacion";			
	pagina="sigesp_cat_contrato.php?opener="+opener;
	popupWin(pagina,"catalogo",850,500);
	//window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=200,resizable=yes,location=no");
}

/*function ue_catobra()
{
	f=document.form1;
	f.operacion.value="";			
	pagina="sigesp_cat_obra.php";
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=200,resizable=yes,location=no");
}
*/

function ue_catcuentagasto()
{
	f=document.form1;
	f.operacion.value="";			
	pagina="sigesp_sob_pdt_spgcuentas.php";
	popupWin(pagina,"catalogo",650,400);
	//window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=200,resizable=yes,location=no,status=yes");
}


function ue_buscar()
{
  f=document.form1;
  li_leer=f.leer.value;
  if(li_leer==1)
   {
     f.operacion.value="";
     pagina="sigesp_cat_variacion.php?estado=";
	 popupWin(pagina,"catalogo",670,400);
    // window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=350,resizable=yes,location=no");
   }
	else
	{
	  alert("No tiene permiso para realizar esta operacion");
	}
} 

/*************************************************************************************************************************************************/

/*******************************************CARGAR Y REMOVER DATOS********************************************************************************************************/
function ue_cargarcontrato(ls_codigo,ls_desobr,ls_estado,ls_codest,ld_monto,ls_placon,ls_placonuni,ls_mulcon,ls_tiemulcon,ls_mulreuni,ls_lapgarcon,ls_lapgaruni,
						ls_codtco,ls_monmaxcon,ls_pormaxcon,ls_obscon,ls_porejefiscon,ls_porejefincon,ls_monejefincon,ls_codasi,ls_feccrecon,
						ls_fecinicon,ls_nomtco,ls_codobr,ls_codpro,ls_codproins)
{
	f=document.form1;
	f.txtcodcon.value=ls_codigo;
	f.operacion.value="ue_datcontrato";
	f.submit();
}


function ue_cargarvariacion(ls_codvar,ls_codcon,ls_tipvar,ls_motvar,ls_fecha,ls_monto,ls_monco,ls_nomestval,ls_codasi,ls_estapr)
{
    f.txtcodcon.value=ls_codcon;
	f.txtcodvar.value=ls_codvar;
	f.txtmotvar.value=ls_motvar;
	f.txtfecvar.value=ls_fecha;
	f.txtmonto.value=uf_convertir(ls_monto);
	f.txtmoncon.value=uf_convertir(ls_monco);
	f.txtestvar.value=ls_nomestval;
	f.hidstatus.value="C";
	f.hidtipvar.value=ls_tipvar;
	f.hidcodasi.value=ls_codasi;
	f.hidmonco.value=ls_monco;
	f.hidestapr.value=ls_estapr;
	f.operacion.value="ue_cargarvariacion";
	f.action="sigesp_sob_d_variacion.php";
	f.submit();
}


function ue_cargarcuenta(codcuenta,nomcuenta,codest1,codest2,codest3,codest4,codest5,dispo,estcla)
{
	f=document.form1;
	f.operacion.value="ue_cargarcuenta";	
	lb_existe=false;
	
	for(li_i=1;li_i<=f.filascuentas.value && !lb_existe;li_i++)
	{
		codpre=codest1+codest2+codest3+codest4+codest5;
		ls_codigo=eval("f.txtcodcue"+li_i+".value");
		ls_nombre=eval("f.txtnomcue"+li_i+".value");
		if((ls_nombre==codcuenta)&&(ls_codigo=codpre))
		{
			alert("La Cuenta ya ha sido cargada!!!");
			lb_existe=true;
		}
	}	
	
	if(!lb_existe)
	{
	    
		eval("f.txtcodcue"+f.filascuentas.value+".value='"+codpre+"'");
		eval("f.txtestcla"+f.filascuentas.value+".value='"+estcla+"'");
		eval("f.codest1"+f.filascuentas.value+".value='"+codest1+"'");
		eval("f.codest2"+f.filascuentas.value+".value='"+codest2+"'");
		eval("f.codest3"+f.filascuentas.value+".value='"+codest3+"'");
		eval("f.codest4"+f.filascuentas.value+".value='"+codest4+"'");
		eval("f.codest5"+f.filascuentas.value+".value='"+codest5+"'");
		eval("f.txtnomcue"+f.filascuentas.value+".value='"+codcuenta+"'");
		eval("f.txtmoncue"+f.filascuentas.value+".value=''");
		eval("f.disponible"+f.filascuentas.value+".value='"+dispo+"'");
		f.submit();
	}
/*	f=document.form1;
	f.operacion.value="ue_cargarcuenta";	
	lb_existe=false;
	
	for(li_i=1;li_i<=f.filascuentas.value && !lb_existe;li_i++)
	{
		codpre=codest1+codest2+codest3+codest4+codest5;
		ls_codigo=eval("f.txtcodcue"+li_i+".value");
		ls_nombre=eval("f.txtnomcue"+li_i+".value");
		if((ls_nombre==codcuenta)&&(ls_codigo=codpre))
		{
			alert("La Cuenta ya ha sido cargada!!!");
			lb_existe=true;
		}
	}	
	
	if(!lb_existe)
	{
	    
		eval("f.txtcodcue"+f.filascuentas.value+".value='"+codpre+"'");
		eval("f.codest1"+f.filascuentas.value+".value='"+codest1+"'");
		eval("f.codest2"+f.filascuentas.value+".value='"+codest2+"'");
		eval("f.codest3"+f.filascuentas.value+".value='"+codest3+"'");
		eval("f.codest4"+f.filascuentas.value+".value='"+codest4+"'");
		eval("f.codest5"+f.filascuentas.value+".value='"+codest5+"'");
		eval("f.txtnomcue"+f.filascuentas.value+".value='"+codcuenta+"'");
		eval("f.txtmoncue"+f.filascuentas.value+".value=''");
		eval("f.disponible"+f.filascuentas.value+".value='"+dispo+"'");
		f.submit();
	}
*/}

function ue_removercuenta(li_fila)
{
	f=document.form1;
	f.hidremovercuenta.value=li_fila;
	f.operacion.value="ue_removercuenta"
	f.action="sigesp_sob_d_variacion.php";
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
			f.txtfecvar.value="";
			f.txtmotvar.value="";
			f.txtmonto.value="0,00";
			f.txtmoncon.value="0,00";
			f.action="sigesp_sob_d_variacion.php";
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
		 if (ue_valida_null(f.txtcodcon,"Contrato")==false)
		  {
		   f.txtcodcon.focus();
		  }
		 else
		 {
		  if (ue_valida_null(f.txtcodvar,"Código")==false)
		   {
			 f.txtcodvar.focus();
		   }
		   else
		   {
			if (ue_valida_null(f.cmbtipvar,"Tipo de Variacion")==false)
			 {
			   f.txtcodproasi.focus();
			 }
			 else
			 {
			  if (ue_valida_null(f.txtfecvar,"Fecha")==false)
			   {
				 f.txtcodinsasi.focus();
			   }
			   else
			   {
				if(ue_validarmontocuentas())
				 {
					   filas=ue_calcular_total_fila_local("txtcodcue");
					   totaldet=0;
					   for(li_i=1;li_i<filas;li_i++)
					   {
							monto=eval("f.txtmoncue"+li_i+".value"); 
							monto=monto.replace(",","."); 
							monto = parseFloat(monto);
							totaldet=totaldet+monto;
					   }
						totalgen=f.txtmonto.value;
						totalgen = totalgen.replace(",",".");
						totalgen = parseFloat(totalgen);
						if(totaldet==totalgen)
						{
							 f.action="sigesp_sob_d_variacion.php";
							 f.operacion.value="ue_guardar";
							 f.submit();
						}
						else
						{
							alert("El total de Cuentas Presupuestarias difiere del total de la Variacion");
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
		alert("La variacion esta aprobada. No se puede modificar");
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
	
    return false; 
   } 
/*************************************************************************************************************************************************/

/************************************************************************************************************************************************/
 
function ue_subtotal()
{
	f=document.form1;
	li_filasparitdas=f.filaspartidas.value;
	ls_tipvar=f.cmbtipvar.value;
	ld_subtotal=0;
	ld_subtoA=0;
	ld_subtoB=0;
	ls_cero="0,00";
	if(ls_tipvar=="s1")
	{
	 alert("Por Favor, indique el Tipo de Variacion!!");
	}
	else
	{
	for(li_i=1;li_i<=li_filasparitdas;li_i++)
	{
	  if(eval("f.flagpar"+li_i+".checked==true"))
		{
		 if((eval("f.txtcantpar"+li_i+".value")=="")||(eval("f.txtcantpar"+li_i+".value")=="0,00"))
		  {
		   ld_cantpar=0;
		  }
		 ld_cantpar=parseFloat(uf_convertir_monto(eval("f.txtcantpar"+li_i+".value")));
		 if((eval("f.txtpreunimod"+li_i+".value")=="")||(eval("f.txtpreunimod"+li_i+".value")=="0,00"))
		  {
		   ld_preuni=0;
		  }
		  else
		   {
		    ld_preref=parseFloat(uf_convertir_monto(eval("f.txtpreuni"+li_i+".value")));
			ld_preuni=parseFloat(uf_convertir_monto(eval("f.txtpreunimod"+li_i+".value")));
		   }
		     
		 if(eval("f.txtcanttot"+li_i+".value")=="")
		  {
		    ld_canttot=0;
		  }
		  else
		   {
		     ld_canttot=parseFloat(uf_convertir_monto(eval("f.txtcanttot"+li_i+".value")));
		   } 
		 //SI LA OPERACION ES UN AUMENTO.      
		 if(ls_tipvar=="1")
		  {
		   ld_subtoB=ld_preuni*ld_cantpar;
		   if(ld_preref<ld_preuni)
		   	{
			   ld_difpre=ld_preuni-ld_preref;
		       ld_subtoA=ld_difpre*ld_canttot;
			}
			ld_totpar=ld_subtoA+ld_subtoB;
		  }
		  //SI LA OPERACION ES UNA DISMINUCION.		
		  if(ls_tipvar=="2")
			  {
			   if((ld_preref>ld_preuni)||(ld_canttot>=ld_cantpar))
			   	{
				   ld_difpre=ld_preref-ld_preuni;
				   ld_difcan=ld_canttot-ld_cantpar;
			       ld_subtoB=ld_preref*ld_cantpar;//cantidad a disminuir
				   ld_subtoA=ld_difpre*ld_difcan;
			       ld_totpar=ld_subtoA+ld_subtoB;
				}
				else
				 {
				   alert("No se detecto un cambio asociado a una Disminucion!!");
				   ld_totpar=0;
				 }
		      }			  		
		ls_totp=uf_convertir(ld_totpar);
		ld_subtotal=ld_subtotal+ld_totpar;
		eval("f.txttotal"+li_i+".value='"+ls_totp+"'");	
    }
  }	
	if(ls_tipvar=="1")
	{
	 ld_monco=parseFloat(f.hidmonco.value);
	 ld_nmonco=ld_monco+ld_subtotal;
	}
    
	if(ls_tipvar=="2")
	{
	 ld_monco=parseFloat(f.hidmonco.value);
	 ld_nmonco=ld_monco-ld_subtotal;
	}
	
	f.txtmonto.value=uf_convertir(ld_subtotal);
	f.txtmoncon.value=uf_convertir(ld_nmonco);
  }
}

  
function ue_validardispo()
{
	f=document.form1;
	ld_montotasi=uf_convertir_monto(f.txtmonto.value);
	ld_montotcue=0;
	for(li_i=1;li_i<=f.filascuentas.value;li_i++)
	{
		
		if(eval("f.txtmoncue"+li_i+".value")=="")
		{
		  ld_monto=0;
		}
		else
		{
		  ld_monto=parseFloat(uf_convertir_monto(eval("f.txtmoncue"+li_i+".value")));
		}
		if(eval("f.disponible"+li_i+".value")=="")
		{
		   ld_dispo=0;
		}
		else
		{
		   ld_dispo=parseFloat(eval("f.disponible"+li_i+".value"));
		}
		
		//alert("monto->"+ld_monto+" disponible->"+ld_dispo);
		if(ld_monto>ld_dispo)
		{
			alert("El monto asignado a la cuenta es mayor que su Diponibilidad!!");
			eval("f.txtmoncue"+li_i+".value=''")
		}
		ld_montotcue=ld_montotcue+ld_monto;
	}	
    //alert("monto total asignado->"+ld_montotasi+" Totales cuentas->"+ld_montotcue);
	if(ld_montotasi<ld_montotcue)
	{
		alert("El monto asignado a las cuentas sobre pasa el total de la Variacion!!");
		eval("f.txtmoncue"+li_i+".value=''")
	}
}

function ue_validarmontocuentas()
{
	f=document.form1;
	ld_montotasi=uf_convertir_monto(f.txtmonto.value);
	ld_montotcue=0;
	lb_flag=false;
	for(li_i=1;li_i<=f.filascuentas.value;li_i++)
	{
		if(eval("f.txtmoncue"+li_i+".value")=="")
		{
		  ld_monto=0;
		}
		else
		{
		  ld_monto=parseFloat(uf_convertir_monto(eval("f.txtmoncue"+li_i+".value")));
		}
		ld_montotcue=ld_montotcue+ld_monto;
    }
	//alert("Mc->"+ld_monto+" MTc")	
    if(ld_montotcue==0)
	{
	 alert("Debe asignar al menos una cuenta de gastos a la Variacion!!");
	}
	else
	{
	if(ld_montotasi==ld_montotcue)
	{
	 lb_flag=true;
	}
	else
	{
	  alert("El monto asignado a las Cuentas debe coincidir con el total de la Variacion!!");
	}
	}
	return lb_flag;	
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
		  if (f.txtcodvar.value=="")
		  {
			alert("Debe seleccionar la Variacion a Anular!!");
		  }
		  else
		  {
			f.action="sigesp_sob_d_variacion.php";
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
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>