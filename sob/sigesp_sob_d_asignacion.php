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
	$io_fun_sob->uf_load_seguridad("SOB","sigesp_sob_d_asignacion.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	
	function uf_limpiar()
	{
		global $ls_formula,$ls_opemostrar,$ls_operacion,$ls_codasi,$ls_ptocueasi,$ls_codobrasi,$ls_nomobrasi,$ls_codproasi,$ls_tipconpro,$ls_nomproasi;
		global $ls_codinsasi,$ls_nominsasi,$ls_fecasi,$ls_monparasi,$ls_basimpasi,$ls_montotasi,$ls_obsasi,$ls_estobr,$ls_monobr,$ls_monasi,$ls_resmonasi;
		global $ls_estadoasi,$ls_chk,$li_filaspartidas,$la_objectpartidas,$li_filascuentas,$la_objectcuentas;
		$ls_formula="";
		$ls_opemostrar="";
		$ls_operacion="";
		$ls_codasi="";
		$ls_ptocueasi="";
		$ls_codobrasi="";
		$ls_nomobrasi="";
		$ls_codproasi="";
		$ls_tipconpro="";
		$ls_nomproasi="";
		$ls_codinsasi="";
		$ls_nominsasi="";
		$ls_fecasi="";
		$ls_monparasi="0,00";
		$ls_basimpasi="0,00";
		$ls_montotasi="0,00";
		$ls_obsasi="";
		$ls_estobr="";
		$ls_monobr="";
		$ls_monasi="";
		$ls_resmonasi="";
		$ls_estadoasi="";
		$ls_chk="";
			
		$li_filaspartidas=1;
		$la_objectpartidas[$li_filaspartidas][1]="<input type=checkbox name=flagpar".$li_filaspartidas." value=1  onClick='javascript: ue_marcartodo(".$li_i.");' disabled class=sin-borde>";
		$la_objectpartidas[$li_filaspartidas][2]="<input name=txtcodpar".$li_filaspartidas." type=text id=txtcodpar".$li_filaspartidas." class=sin-borde style= text-align:center size=8 readonly><input name=canpareje".$li_filaspartidas." type=hidden id=canpareje".$li_filaspartidas.">";
		$la_objectpartidas[$li_filaspartidas][3]="<input name=txtnompar".$li_filaspartidas." type=text id=txtnompar".$li_filaspartidas." class=sin-borde style= text-align:left size=25 readonly>";
		$la_objectpartidas[$li_filaspartidas][4]="<input name=txtnomuni".$li_filaspartidas." type=text id=txtnomuni".$li_filaspartidas." class=sin-borde size=5 style= text-align:center readonly>";
		$la_objectpartidas[$li_filaspartidas][5]="<input name=txtpreuni".$li_filaspartidas." type=text id=txtpreuni".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectpartidas[$li_filaspartidas][6]="<input name=txtpreunimod".$li_filaspartidas." type=text id=txtpreunimod".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectpartidas[$li_filaspartidas][7]="<input name=txtcanttot".$li_filaspartidas." type=text id=txtcanttot".$li_filaspartidas." class=sin-borde size=5 style= text-align:center readonly>";
		$la_objectpartidas[$li_filaspartidas][8]="<input name=txtcantpar".$li_filaspartidas." type=text id=txtcantpar".$li_filaspartidas." class=sin-borde size=5 style= text-align:center $ls_readonly>";
		$la_objectpartidas[$li_filaspartidas][9]="<input name=txttotal".$li_filaspartidas." type=text id=txttotal".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
		
		$li_filascuentas=1;
		$la_objectcuentas[1][1]="<input name=txtcodcue1 type=text id=txtcodcue1 class=sin-borde style= text-align:center size=40 readonly><input name=codest11 type=hidden id=codest11><input name=codest21 type=hidden id=codest21><input name=codest31 type=hidden id=codest31><input name=codest41 type=hidden id=codest41><input name=codest51 type=hidden id=codest51>";
		$la_objectcuentas[1][2]="<input name=txtestcla1 type=text id=txtestcla1 class=sin-borde style= text-align:center size=20 readonly>";
		$la_objectcuentas[1][3]="<input name=txtnomcue1 type=text id=txtnomcue1 class=sin-borde style= text-align:left size=10 readonly>";
		$la_objectcuentas[1][4]="<input name=txtmoncue1 type=text id=txtmoncue1 class=sin-borde style= text-align:center size=20 readonly><input name=disponible1 type=hidden id=disponible1>";
		$la_objectcuentas[1][5]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
		if (array_key_exists("cargos",$_SESSION))
		{
			unset($_SESSION["cargos"]);
		}
	
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Asignacion de Obras</title>
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
.Estilo2 {font-size: 10px; color: #6699CC;}
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
require_once("class_folder/sigesp_sob_c_funciones_sob.php");
require_once("../shared/class_folder/grid_param.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/evaluate_formula.php");
require_once("class_folder/sigesp_sob_c_puntodecuenta.php");
$io_puntodecuenta=new sigesp_sob_c_puntodecuenta();
$io_asignacion=new sigesp_sob_class_asignacion();
$io_obra=new sigesp_sob_class_obra();
$io_funcsob=new sigesp_sob_c_funciones_sob();
$io_evalform=new evaluate_formula();
$io_grid=new grid_param();
$io_msg=new class_mensajes();
$io_datastore=new class_datastore();
$io_function=new class_funciones();
$io_ds_cargos=new class_datastore(); // Datastored de cargos



$ls_titulopartidas="Partidas Asignadas";
$li_anchopartidas=600;
$ls_nametable="grid";
$la_columpartidas[1]="";
$la_columpartidas[2]="Código";
$la_columpartidas[3]="Partida";
$la_columpartidas[4]="Uni. Med.";
$la_columpartidas[5]="(Ref)Pre. Uni.";
$la_columpartidas[6]="Pre. Unitario";
$la_columpartidas[7]="Cant(O)";
$la_columpartidas[8]="Cant(A)";
$la_columpartidas[9]="Total";

$ls_titulocuentas="Cuentas de Gastos";
$li_anchocuentas=650;
$ls_nametable="grid2";
$la_columcuentas[1]="Código Presupuestario";
$la_columcuentas[2]="Estatus";
$la_columcuentas[3]="Cuenta";
$la_columcuentas[4]="Monto";
$la_columcuentas[5]="Edición";

$ls_titulocargos="Cargos";
$li_anchocargos=650;
$ls_nametable="grid3";
$la_columcargos[1]="Código";
$la_columcargos[2]="Denominación";
$la_columcargos[3]="Monto";
$la_columcargos[4]="Edición";


/****************************************************************************************************************************************/
$ls_readonly="";

/****************************************************************************************************************************************/


/******************************************************	OBTENER VALORES DE LOS TXT *********************************************************/
if(array_key_exists("operacion",$_POST))
{
	$ls_opemostrar=$_POST["opemostrar"];
	$ls_operacion=$_POST["operacion"];
	$ls_codasi=$_POST["txtcodasi"];
	$ls_ptocueasi=$_POST["txtptocueasi"];
	$ls_codobrasi=$_POST["txtcodobrasi"];
	$ls_nomobrasi=$_POST["txtnomobrasi"];
	$ls_codproasi=$_POST["txtcodproasi"];
	$ls_tipconpro=$_POST["tipconpro"];
	$ls_nomproasi=$_POST["txtnomproasi"];
	$ls_codinsasi=$_POST["txtcodinsasi"];
	$ls_nominsasi=$_POST["txtnominsasi"];
	$ls_fecasi=$_POST["txtfecasi"];
	$ls_monparasi=$_POST["txtmonparasi"];
	$ls_monparasi=$io_funcsob->uf_convertir_cadenanumero($ls_monparasi);
	$ls_basimpasi=$_POST["txtbasimpasi"];
	$ls_basimpasi=$io_funcsob->uf_convertir_cadenanumero($ls_basimpasi);
	$ls_montotasi=$_POST["txtmontotasi2"];
	$ls_montotasi=$io_funcsob->uf_convertir_cadenanumero($ls_montotasi);
	$ls_obsasi=$_POST["txtobsasi"];
	$li_filaspartidas=$_POST["filaspartidas"];
	$li_filascuentas=$_POST["filascuentas"];
//	$li_filascargos=$_POST["filascargos"];
	$ls_estobr=$_POST["hidestobr"]; 
	$ls_monobr=$_POST["hidmonobr"];  
	$ls_monasi=$_POST["hidmonasi"];
	$ls_estapr=$_POST["hidestapr"];
	$ls_resmonasi=$_POST["hidresmonasi"];
	$ls_estadoasi=$_POST["txtestasi"];
	$ls_chk=$_POST["hidstatus"];
	//print ("operacion!!".$ls_operacion);
	if (!empty ($ls_ptocueasi))
	 {
	   $ls_readonly="readonly"; 
	 }

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
			$ls_canpareje=$_POST["canpareje".$li_i];
			if(!empty($_POST["flagpar".$li_i]))
			{
			 $la_objectpartidas[$li_i][1]="<input type=checkbox name=flagpar".$li_i." value=1 checked class=sin-borde onClick='javascript: ue_marcartodo(".$li_i.");'>";
			}
			else
			{
			 $la_objectpartidas[$li_i][1]="<input type=checkbox name=flagpar".$li_i." value=1 class=sin-borde onClick='javascript: ue_marcartodo(".$li_i.");'>";
			}
	        $la_objectpartidas[$li_i][2]="<input name=txtcodpar".$li_i." type=text id=txtcodpar".$li_i." value='".$ls_codigo."' class=sin-borde style= text-align:center size=8 readonly><input name=canpareje".$li_i." type=hidden id=canpareje".$li_i." value='".$ls_canpareje."'>";
	        $la_objectpartidas[$li_i][3]="<input name=txtnompar".$li_i." type=text id=txtnompar".$li_i." value='".$ls_nombre."' class=sin-borde style= text-align:left size=25 readonly>";
	        $la_objectpartidas[$li_i][4]="<input name=txtnomuni".$li_i." type=text id=txtnomuni".$li_i." value='".$ls_unidad."' class=sin-borde size=5 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][5]="<input name=txtpreuni".$li_i." type=text id=txtpreuni".$li_i." value='".$ls_preuni."' class=sin-borde size=15 style= text-align:center readonly>";
			$la_objectpartidas[$li_i][6]="<input name=txtpreunimod".$li_i." type=text id=txtpreunimod".$li_i." value='".$ls_preunimod."' class=sin-borde size=15 style= text-align:center onKeyPress=return(currencyFormat(this,'.',',',event))>";
	        $la_objectpartidas[$li_i][7]="<input name=txtcanttot".$li_i." type=text id=txtcanttot".$li_i." value='".$ls_canttot."' class=sin-borde size=5 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][8]="<input name=txtcantpar".$li_i." type=text id=txtcantpar".$li_i." value='".$ls_cantpar."' class=sin-borde size=5 style= text-align:center onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=ue_subtotal();>";
	        $la_objectpartidas[$li_i][9]="<input name=txttotal".$li_i." type=text id=txttotal".$li_i." value='".$ls_total."' class=sin-borde size=15 style= text-align:center readonly>";	
			if($ls_canttot==0)
			{
				$la_objectpartidas[$li_i][1]="<input type=checkbox name=flagpar".$li_i." value=1 class=sin-borde  onClick='javascript: ue_marcartodo(".$li_i.");' disabled>";
				$la_objectpartidas[$li_i][2]="<input name=txtcodpar".$li_i." type=text id=txtcodpar".$li_i." value='".$ls_codigo."' class=sin-borde style= text-align:center size=8 readonly><input name=canpareje".$li_i." type=hidden id=canpareje".$li_i." value='".$ls_canpareje."'>";
				$la_objectpartidas[$li_i][3]="<input name=txtnompar".$li_i." type=text id=txtnompar".$li_i." value='".$ls_nombre."' class=sin-borde style= text-align:left size=25 readonly>";
				$la_objectpartidas[$li_i][4]="<input name=txtnomuni".$li_i." type=text id=txtnomuni".$li_i." value='".$ls_unidad."' class=sin-borde size=5 style= text-align:center readonly>";
				$la_objectpartidas[$li_i][5]="<input name=txtpreuni".$li_i." type=text id=txtpreuni".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_preuni)."' class=sin-borde size=15 style= text-align:center readonly>";
				$la_objectpartidas[$li_i][6]="<input name=txtpreunimod".$li_i." type=text id=txtpreunimod".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_preuni)."' class=sin-borde size=15 style= text-align:center readonly>";
				$la_objectpartidas[$li_i][7]="<input name=txtcanttot".$li_i." type=text id=txtcanttot".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_canttot)."' class=sin-borde size=5 style= text-align:center readonly>";
				$la_objectpartidas[$li_i][8]="<input name=txtcantpar".$li_i." type=text id=txtcantpar".$li_i." class=sin-borde size=5 style= text-align:center readonly>";
				$la_objectpartidas[$li_i][9]="<input name=txttotal".$li_i." type=text id=txttotal".$li_i." class=sin-borde size=15 style= text-align:center readonly>";	
			}
	}	
	$la_objectpartidas[$li_filaspartidas][1]="<input type=checkbox name=flagpar".$li_filaspartidas." value=1 onClick='javascript: ue_marcartodo(".$li_i.");' disabled class=sin-borde>";
	$la_objectpartidas[$li_filaspartidas][2]="<input name=txtcodpar".$li_filaspartidas." type=text id=txtcodpar".$li_filaspartidas." class=sin-borde style= text-align:center size=8 readonly>";
	$la_objectpartidas[$li_filaspartidas][3]="<input name=txtnompar".$li_filaspartidas." type=text id=txtnompar".$li_filaspartidas." class=sin-borde style= text-align:left size=25 readonly>";
	$la_objectpartidas[$li_filaspartidas][4]="<input name=txtnomuni".$li_filaspartidas." type=text id=txtnomuni".$li_filaspartidas." class=sin-borde size=5 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][5]="<input name=txtpreuni".$li_filaspartidas." type=text id=txtpreuni".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][6]="<input name=txtpreunimod".$li_filaspartidas." type=text id=txtpreunimod".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][7]="<input name=txtcanttot".$li_filaspartidas." type=text id=txtcanttot".$li_filaspartidas." class=sin-borde size=5 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][8]="<input name=txtcantpar".$li_filaspartidas." type=text id=txtcantpar".$li_filaspartidas." class=sin-borde size=5 style= text-align:center $ls_readonly>";
	$la_objectpartidas[$li_filaspartidas][9]="<input name=txttotal".$li_filaspartidas." type=text id=txttotal".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";

	if ($ls_operacion != "ue_cargarcuenta" && $ls_operacion != "ue_removercuenta")
	{
		$li_filasfuentes=$_POST["filascuentas"];
		$li_row=0;
		for($li_i=1;$li_i<$li_filasfuentes;$li_i++)
		{		
		   $ls_codigo=$_POST["txtcodcue".$li_i];
		   $ls_codest1=$_POST["codest1".$li_i];
		   $ls_codest2=$_POST["codest2".$li_i];
		   $ls_codest3=$_POST["codest3".$li_i];
		   $ls_codest4=$_POST["codest4".$li_i];
		   $ls_codest5=$_POST["codest5".$li_i];
		   $ls_estcla=$_POST["txtestcla".$li_i];
		   $ls_disponible=$_POST["disponible".$li_i];
		   $ls_nombre=$_POST["txtnomcue".$li_i];
		   $ls_moncar=$_POST["txtmoncue".$li_i];
		   $ls_estcar=$_POST["estcar".$li_i];
		   if($ls_estcar!="C")
		   {
		   	   $li_row++;
			   $la_objectcuentas[$li_row][1]="<input name=txtcodcue".$li_row." type=text id=txtcodcue".$li_row." class=sin-borde style= text-align:center size=40 value='".$ls_codigo."' readonly><input name=codest1".$li_row." type=hidden id=codest1".$li_row." value='".$ls_codest1."'><input name=codest2".$li_row." type=hidden id=codest2".$li_row." value='".$ls_codest2."'><input name=codest3".$li_row." type=hidden id=codest3".$li_row." value='".$ls_codest3."'><input name=codest4".$li_row." type=hidden id=codest4".$li_row." value='".$ls_codest4."'><input name=codest5".$li_row." type=hidden id=codest5".$li_row." value='".$ls_codest5."'>".
										   "<input name=estcar".$li_row." type=hidden id=estcar".$li_row." value=''>";
			   $la_objectcuentas[$li_row][2]="<input name=txtestcla".$li_row." type=text id=txtestcla".$li_row." class=sin-borde style= text-align:center size=20 value='".$ls_estcla."' readonly>";
			   $la_objectcuentas[$li_row][3]="<input name=txtnomcue".$li_row." type=text id=txtnomcue".$li_row." class=sin-borde style= text-align:left size=10 value='".$ls_nombre."' readonly >";
			   $la_objectcuentas[$li_row][4]="<input name=txtmoncue".$li_row." type=text id=txtmoncue".$li_row." class=sin-borde size=20 style= text-align:center value='".$ls_moncar."' onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=javascript:ue_validardispo()><input name=disponible".$li_row." type=hidden id=disponible".$li_row." value='".$ls_disponible."'>";
			   $la_objectcuentas[$li_row][5]="<a href=javascript:ue_removercuenta(".$li_row.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
			}
		}
		$li_filascuentas=$li_row;
/*		$la_objectcuentas[$li_filascuentas][1]="<input name=txtcodcue".$li_filascuentas." type=text id=txtcodcue".$li_filascuentas." class=sin-borde style= text-align:center size=40 readonly><input name=codest1".$li_filascuentas." type=hidden id=codest1".$li_filascuentas."><input name=codest2".$li_filascuentas." type=hidden id=codest2".$li_filascuentas."><input name=codest3".$li_filascuentas." type=hidden id=codest3".$li_filascuentas."><input name=codest4".$li_filascuentas." type=hidden id=codest4".$li_filascuentas."><input name=codest5".$li_filascuentas." type=hidden id=codest5".$li_filascuentas.">".
										  	   "<input name=estcar".$li_row." type=hidden id=estcar".$li_row." value=''>";
	    $la_objectcuentas[$li_filascuentas][2]="<input name=txtestcla".$li_filascuentas." type=text id=txtestcla".$li_filascuentas." class=sin-borde style= text-align:center size=20 readonly>";
		$la_objectcuentas[$li_filascuentas][3]="<input name=txtnomcue".$li_filascuentas." type=text id=txtnomcue".$li_filascuentas." class=sin-borde style= text-align:left size=10 readonly><input name=disponible".$li_filascuentas." type=hidden id=disponible".$li_filascuentas.">";
		$la_objectcuentas[$li_filascuentas][4]="<input name=txtmoncue".$li_filascuentas." type=text id=txtmoncue".$li_filascuentas." class=sin-borde style= text-align:center size=20 readonly>";
		$la_objectcuentas[$li_filasfuentes][5]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
*/	}
/*	if ($ls_operacion != "ue_cargarcargo" && $ls_operacion != "ue_removercargo")
	{
			$li_filascargos=$_POST["filascargos"];
		
		for($li_i=1;$li_i<$li_filascargos;$li_i++)
		{		
			$ls_codigo     = $_POST["txtcodcar".$li_i];
			$ls_nombre     = $_POST["txtnomcar".$li_i];
			$ls_moncue     = $_POST["txtmoncar".$li_i];
			$ls_formula    = $_POST["formula".$li_i];
			$ls_prog       = $_POST["prog".$li_i];
			$ls_spgcuenta  = $_POST["spgcuenta".$li_i];
			$la_objectcargos[$li_i][1]="<input name=txtcodcar".$li_i." type=text id=txtcodcar".$li_i." class=sin-borde style= text-align:center size=5 value='".$ls_codigo."' readonly><input name=prog".$li_i." type=hidden id=prog".$li_i." value='".$ls_prog."'><input name=spgcuenta".$li_i." type=hidden id=spgcuenta".$li_i." value='".$ls_spgcuenta."'>";
			$la_objectcargos[$li_i][2]="<input name=txtnomcar".$li_i." type=text id=txtnomcar".$li_i." class=sin-borde style= text-align:left size=60 value='".$ls_nombre."' readonly >";
			$la_objectcargos[$li_i][3]="<input name=txtmoncar".$li_i." type=text id=txtmoncar".$li_i." class=sin-borde size=20 style= text-align:center value='".$ls_moncue."' readonly><input name=formula".$li_i." type=hidden id=formula".$li_i." value='".$ls_formula."'>";
			$la_objectcargos[$li_i][4]="<a href=javascript:ue_removercargo(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
		}	
		$la_objectcargos[$li_filascargos][1]="<input name=txtcodcar".$li_filascargos." type=text id=txtcodcar".$li_filascargos." class=sin-borde style= text-align:center size=5 readonly><input name=prog".$li_filascargos." type=hidden id=prog".$li_filascargos."><input name=spgcuenta".$li_filascargos." type=hidden id=spgcuenta".$li_filascargos.">";
		$la_objectcargos[$li_filascargos][2]="<input name=txtnomcar".$li_filascargos." type=text id=txtnomcar".$li_filascargos." class=sin-borde style= text-align:left size=50 readonly>";
		$la_objectcargos[$li_filascargos][3]="<input name=txtmoncar".$li_filascargos." type=text id=txtmoncar".$li_filascargos." class=sin-borde style= text-align:center size=20 readonly><input name=formula".$li_filascargos." type=hidden id=formula".$li_filascargos.">";
		$la_objectcargos[$li_filascargos][4]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	}*/
}
/*******************************************************************************************************************************************************/

/************************************************ INICIALIZA LAS VARIABLES SI NO HAY SUBMIT *******************************************************************************************************/
else
{
    $ls_formula="";
	$ls_opemostrar="";
	$ls_operacion="";
	$ls_codasi="";
	$ls_ptocueasi="";
	$ls_codobrasi="";
	$ls_nomobrasi="";
	$ls_codproasi="";
	$ls_tipconpro="";
	$ls_nomproasi="";
	$ls_codinsasi="";
	$ls_nominsasi="";
	$ls_fecasi="";
	$ls_monparasi="0,00";
	$ls_basimpasi="0,00";
	$ls_montotasi="0,00";
	$ls_obsasi="";
	$ls_estobr="";
	$ls_monobr="";
	$ls_monasi="";
	$ls_resmonasi="";
	$ls_estadoasi="";
	$ls_chk="";
		
	$li_filaspartidas=1;
	$la_objectpartidas[$li_filaspartidas][1]="<input type=checkbox name=flagpar".$li_filaspartidas." value=1  onClick='javascript: ue_marcartodo(".$li_filaspartidas.");' disabled class=sin-borde>";
	$la_objectpartidas[$li_filaspartidas][2]="<input name=txtcodpar".$li_filaspartidas." type=text id=txtcodpar".$li_filaspartidas." class=sin-borde style= text-align:center size=8 readonly><input name=canpareje".$li_filaspartidas." type=hidden id=canpareje".$li_filaspartidas.">";
	$la_objectpartidas[$li_filaspartidas][3]="<input name=txtnompar".$li_filaspartidas." type=text id=txtnompar".$li_filaspartidas." class=sin-borde style= text-align:left size=25 readonly>";
	$la_objectpartidas[$li_filaspartidas][4]="<input name=txtnomuni".$li_filaspartidas." type=text id=txtnomuni".$li_filaspartidas." class=sin-borde size=5 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][5]="<input name=txtpreuni".$li_filaspartidas." type=text id=txtpreuni".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][6]="<input name=txtpreunimod".$li_filaspartidas." type=text id=txtpreunimod".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][7]="<input name=txtcanttot".$li_filaspartidas." type=text id=txtcanttot".$li_filaspartidas." class=sin-borde size=5 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][8]="<input name=txtcantpar".$li_filaspartidas." type=text id=txtcantpar".$li_filaspartidas." class=sin-borde size=5 style= text-align:center $ls_readonly>";
	$la_objectpartidas[$li_filaspartidas][9]="<input name=txttotal".$li_filaspartidas." type=text id=txttotal".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	
	$li_filascuentas=1;
	$la_objectcuentas[1][1]="<input name=txtcodcue1 type=text id=txtcodcue1 class=sin-borde style= text-align:center size=40 readonly><input name=codest11 type=hidden id=codest11><input name=codest21 type=hidden id=codest21><input name=codest31 type=hidden id=codest31><input name=codest41 type=hidden id=codest41><input name=codest51 type=hidden id=codest51>";
	$la_objectcuentas[1][2]="<input name=txtestcla1 type=text id=txtestcla1 class=sin-borde style= text-align:center size=20 readonly>";
	$la_objectcuentas[1][3]="<input name=txtnomcue1 type=text id=txtnomcue1 class=sin-borde style= text-align:left size=10 readonly>";
	$la_objectcuentas[1][4]="<input name=txtmoncue1 type=text id=txtmoncue1 class=sin-borde style= text-align:center size=20 readonly><input name=disponible1 type=hidden id=disponible1>";
	$la_objectcuentas[1][5]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
	
	$li_filascargos=1;
	$la_objectcargos[1][1]="<input name=txtcodcar1 type=text id=txtcodcar1 class=sin-borde style= text-align:center size=5 readonly><input name=formula1 type=hidden id=formula1><input name=prog1 type=hidden id=prog1><input name=spgcuenta1 type=hidden id=spgcuenta1>";
	$la_objectcargos[1][2]="<input name=txtnomcar1 type=text id=txtnomcar1 class=sin-borde style= text-align:left size=50 readonly>";
	$la_objectcargos[1][3]="<input name=txtmoncar1 type=text id=txtmoncar1 class=sin-borde style= text-align:center size=20 readonly>";
	$la_objectcargos[1][4]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";

}
/***************************************************************************************************************************************************************************/

/***************************************************************************************************************************************************************************/
if($ls_operacion=="ue_actulizarmontoasi")
{
    require_once("../shared/class_folder/class_funciones_db.php");
	require_once ("../shared/class_folder/sigesp_include.php");		
	$io_include=new sigesp_include();
	$io_connect=$io_include->uf_conectar();
	$io_funcdb=new class_funciones_db($io_connect);
	$la_empresa=$_SESSION["la_empresa"];
	require_once("../shared/class_folder/sigesp_c_generar_consecutivo.php");
	$io_keygen= new sigesp_c_generar_consecutivo();
	$ls_codasi= $io_keygen->uf_generar_numero_nuevo("SOB","sob_asignacion","codasi","SOBASI",6,"","","");
	unset($io_keygen);
	$ls_codobr=$_POST["txtcodobrasi"];
	$io_asignacion->uf_select_obra($ls_codobr,&$la_obra);
    $io_asignacion->uf_select_montoasignado($ls_codobr,&$ls_monasi);
	$ls_monobr=$la_obra["monto"][1];
	if($ls_monobr==$ls_monasi)
	{
	 $io_msg->message("La Obra seleccionada ya fue asignada en su totalidad!!");
	}
	
	if (!empty ($ls_ptocueasi))
	 {
	   $ls_readonly="readonly"; 
	 }
	 
	$lb_validop=$io_asignacion->uf_select_partidasobra($ls_codobrasi,&$la_partidas,&$li_totalfilas);
	if($lb_validop)
	{
	$io_datastore->data=$la_partidas;
	$li_filaspartidas=$io_datastore->getRowCount("codpar");
	for($li_i=1;$li_i<=$li_filaspartidas;$li_i++)
	{
		    $ls_codigo=$io_datastore->getValue("codpar",$li_i);
			$ls_nombre=$io_datastore->getValue("nompar",$li_i);
			$ls_unidad=$io_datastore->getValue("nomuni",$li_i);
			$ls_preuni=$io_datastore->getValue("prepar",$li_i);
			$ls_canttot=$io_datastore->getValue("canxeje",$li_i);
			$ls_canpareje=$io_datastore->getValue("canparasi",$li_i);
			$la_objectpartidas[$li_i][1]="<input type=checkbox name=flagpar".$li_i." value=1 class=sin-borde  onClick='javascript: ue_marcartodo(".$li_i.");'>";
	        $la_objectpartidas[$li_i][2]="<input name=txtcodpar".$li_i." type=text id=txtcodpar".$li_i." value='".$ls_codigo."' class=sin-borde style= text-align:center size=8 readonly><input name=canpareje".$li_i." type=hidden id=canpareje".$li_i." value='".$ls_canpareje."'>";
	        $la_objectpartidas[$li_i][3]="<input name=txtnompar".$li_i." type=text id=txtnompar".$li_i." value='".$ls_nombre."' class=sin-borde style= text-align:left size=25 readonly>";
	        $la_objectpartidas[$li_i][4]="<input name=txtnomuni".$li_i." type=text id=txtnomuni".$li_i." value='".$ls_unidad."' class=sin-borde size=5 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][5]="<input name=txtpreuni".$li_i." type=text id=txtpreuni".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_preuni)."' class=sin-borde size=15 style= text-align:center readonly>";
			$la_objectpartidas[$li_i][6]="<input name=txtpreunimod".$li_i." type=text id=txtpreunimod".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_preuni)."' class=sin-borde size=15 style= text-align:center onKeyPress=return(currencyFormat(this,'.',',',event))>";
	        $la_objectpartidas[$li_i][7]="<input name=txtcanttot".$li_i." type=text id=txtcanttot".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_canttot)."' class=sin-borde size=5 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][8]="<input name=txtcantpar".$li_i." type=text id=txtcantpar".$li_i." class=sin-borde size=5 style= text-align:center onKeyPress=return(currencyFormat(this,'.',',',event)) $ls_readonly  onBlur=ue_subtotal();>";
	        $la_objectpartidas[$li_i][9]="<input name=txttotal".$li_i." type=text id=txttotal".$li_i." class=sin-borde size=15 style= text-align:center readonly>";	
			if($ls_canttot==0)
			{
			$la_objectpartidas[$li_i][1]="<input type=checkbox name=flagpar".$li_i." value=1 class=sin-borde  onClick='javascript: ue_marcartodo(".$li_i.");' disabled>";
	        $la_objectpartidas[$li_i][2]="<input name=txtcodpar".$li_i." type=text id=txtcodpar".$li_i." value='".$ls_codigo."' class=sin-borde style= text-align:center size=8 readonly><input name=canpareje".$li_i." type=hidden id=canpareje".$li_i." value='".$ls_canpareje."'>";
	        $la_objectpartidas[$li_i][3]="<input name=txtnompar".$li_i." type=text id=txtnompar".$li_i." value='".$ls_nombre."' class=sin-borde style= text-align:left size=25 readonly>";
	        $la_objectpartidas[$li_i][4]="<input name=txtnomuni".$li_i." type=text id=txtnomuni".$li_i." value='".$ls_unidad."' class=sin-borde size=5 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][5]="<input name=txtpreuni".$li_i." type=text id=txtpreuni".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_preuni)."' class=sin-borde size=15 style= text-align:center readonly>";
			$la_objectpartidas[$li_i][6]="<input name=txtpreunimod".$li_i." type=text id=txtpreunimod".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_preuni)."' class=sin-borde size=15 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][7]="<input name=txtcanttot".$li_i." type=text id=txtcanttot".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_canttot)."' class=sin-borde size=5 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][8]="<input name=txtcantpar".$li_i." type=text id=txtcantpar".$li_i." class=sin-borde size=5 style= text-align:center $ls_readonly>";
	        $la_objectpartidas[$li_i][9]="<input name=txttotal".$li_i." type=text id=txttotal".$li_i." class=sin-borde size=15 style= text-align:center readonly>";	
			}
	}
	$li_filaspartidas=$li_filaspartidas+1;	
	$la_objectpartidas[$li_filaspartidas][1]="<input type=checkbox name=flagpar".$li_filaspartidas." value=1 disabled class=sin-borde  onClick='javascript: ue_marcartodo(".$li_i.");'>";
	$la_objectpartidas[$li_filaspartidas][2]="<input name=txtcodpar".$li_filaspartidas." type=text id=txtcodpar".$li_filaspartidas." class=sin-borde style= text-align:center size=8 readonly><input name=canpareje".$li_filaspartidas." type=hidden id=canpareje".$li_filaspartidas.">";
	$la_objectpartidas[$li_filaspartidas][3]="<input name=txtnompar".$li_filaspartidas." type=text id=txtnompar".$li_filaspartidas." class=sin-borde style= text-align:left size=25 readonly>";
	$la_objectpartidas[$li_filaspartidas][4]="<input name=txtnomuni".$li_filaspartidas." type=text id=txtnomuni".$li_filaspartidas." class=sin-borde size=5 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][5]="<input name=txtpreuni".$li_filaspartidas." type=text id=txtpreuni".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][6]="<input name=txtpreunimod".$li_filaspartidas." type=text id=txtpreunimod".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][7]="<input name=txtcanttot".$li_filaspartidas." type=text id=txtcanttot".$li_filaspartidas." class=sin-borde size=5 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][8]="<input name=txtcantpar".$li_filaspartidas." type=text id=txtcantpar".$li_filaspartidas." class=sin-borde size=5 style= text-align:center $ls_readonly>";
	$la_objectpartidas[$li_filaspartidas][9]="<input name=txttotal".$li_filaspartidas." type=text id=txttotal".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	
	$li_filascuentas=1;
	$la_objectcuentas[1][1]="<input name=txtcodcue1 type=text id=txtcodcue1 class=sin-borde style= text-align:center size=40 readonly><input name=codest11 type=hidden id=codest11><input name=codest21 type=hidden id=codest21><input name=codest31 type=hidden id=codest31><input name=codest41 type=hidden id=codest41><input name=codest51 type=hidden id=codest51>";
	$la_objectcuentas[1][2]="<input name=txtestcla1 type=text id=txtestcla1 class=sin-borde style= text-align:center size=20 readonly>";
	$la_objectcuentas[1][3]="<input name=txtnomcue1 type=text id=txtnomcue1 class=sin-borde style= text-align:left size=10 readonly>";
	$la_objectcuentas[1][4]="<input name=txtmoncue1 type=text id=txtmoncue1 class=sin-borde style= text-align:center size=20 readonly><input name=disponible1 type=hidden id=disponible1>";
	$la_objectcuentas[1][5]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
	}
	if (array_key_exists("cargos",$_SESSION))
	{
		unset($_SESSION["cargos"]);
	}
}
/***************************************************************************************************************************************************************************/
/*elseif($ls_operacion="ue_verificarexistencia")
{	
	$lb_valido=$io_contrato->uf_select_contrato ($ls_codcon,$la_data);
	if($lb_valido)
	{
		if(count($la_data)>0)
		{
			$io_msg->message("El Código del Contrato ya existe!!!");
			$ls_codcon="";
		}
	}

}*/
/************************************************ PREPARANDO INSERCION DE NUEVO REGISTRO ****************************************************************************/
elseif($ls_operacion=="ue_nuevo1")
{
    require_once("../shared/class_folder/class_funciones_db.php");
	require_once ("../shared/class_folder/sigesp_include.php");		
	$io_include=new sigesp_include();
	$io_connect=$io_include->uf_conectar();
	$io_funcdb=new class_funciones_db($io_connect);
	$la_empresa=$_SESSION["la_empresa"];
	require_once("../shared/class_folder/sigesp_c_generar_consecutivo.php");
	$io_keygen= new sigesp_c_generar_consecutivo();
	$ls_codasi= $io_keygen->uf_generar_numero_nuevo("SOB","sob_asignacion","codasi","SOBASI",6,"","","");
	unset($io_keygen);
//	$ls_codasi=$io_funcdb->uf_generar_codigo(true,$la_empresa["codemp"],"sob_asignacion","codasi",6);
	$ls_ptocueasi="";
	$ls_codproasi="";
	$ls_tipconpro="";
	$ls_nomproasi="";
	$ls_codinsasi="";
	$ls_nominsasi="";
	$ls_fecasi="";
	$ls_obsasi="";
	$ls_estadoasi="EMITIDO";
	
	
	$li_filascuentas=1;
	$la_objectcuentas[1][1]="<input name=txtcodcue1 type=text id=txtcodcue1 class=sin-borde style= text-align:center size=40 readonly><input name=codest11 type=hidden id=codest11><input name=codest21 type=hidden id=codest21><input name=codest31 type=hidden id=codest31><input name=codest41 type=hidden id=codest41><input name=codest51 type=hidden id=codest51>";
	$la_objectcuentas[1][2]="<input name=txtestcla1 type=text id=txtestcla1 class=sin-borde style= text-align:center size=20 readonly>";
	$la_objectcuentas[1][3]="<input name=txtnomcue1 type=text id=txtnomcue1 class=sin-borde style= text-align:left size=10 readonly>";
	$la_objectcuentas[1][4]="<input name=txtmoncue1 type=text id=txtmoncue1 class=sin-borde style= text-align:center size=20 readonly><input name=disponible1 type=hidden id=disponible1";
	$la_objectcuentas[1][5]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
	
	$li_filascargos=1;
	$la_objectcargos[1][1]="<input name=txtcodcar1 type=text id=txtcodcar1 class=sin-borde style= text-align:center size=5 readonly><input name=formula1 type=hidden id=formula1><input name=prog1 type=hidden id=prog1><input name=spgcuenta1 type=hidden id=spgcuenta1>";
	$la_objectcargos[1][2]="<input name=txtnomcar1 type=text id=txtnomcar1 class=sin-borde style= text-align:left size=50 readonly>";
	$la_objectcargos[1][3]="<input name=txtmoncar1 type=text id=txtmoncar1 class=sin-borde style= text-align:center size=20 readonly>";
	$la_objectcargos[1][4]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
	
	
}
elseif($ls_operacion=="ue_nuevo")
{
	uf_limpiar();
}
/***************************************************************************************************************************************************************************/

/*******************************************INSERTAR CAMPO EN GRID CUENTAS********************************************************************************************************************************/
elseif($ls_operacion=="ue_agregarcargo")
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
		$la_objectcuentas[$li_i][1]="<input name=txtcodcue".$li_i." type=text id=txtcodcue".$li_i." class=sin-borde style= text-align:center size=40 value='".$ls_codigo."' readonly><input name=codest1".$li_i." type=hidden id=codest1".$li_i." value='".$ls_codest1."'><input name=codest2".$li_i." type=hidden id=codest2".$li_i." value='".$ls_codest2."'><input name=codest3".$li_i." type=hidden id=codest3".$li_i." value='".$ls_codest3."'><input name=codest4".$li_i." type=hidden id=codest4".$li_i." value='".$ls_codest4."'><input name=codest5".$li_i." type=hidden id=codest5".$li_i." value='".$ls_codest5."'>".
								    "<input name=estcar".$li_i." type=hidden id=estcar".$li_i." value=''>";
	    $la_objectcuentas[$li_i][2]="<input name=txtestcla".$li_i." type=text id=txtestcla".$li_i." class=sin-borde style= text-align:center size=20 value='".$ls_estcla."' readonly>";
		$la_objectcuentas[$li_i][3]="<input name=txtnomcue".$li_i." type=text id=txtnomcue".$li_i." class=sin-borde style= text-align:left size=10 value='".$ls_nombre."' readonly >";
		$la_objectcuentas[$li_i][4]="<input name=txtmoncue".$li_i." type=text id=txtmoncue".$li_i." class=sin-borde size=20 style= text-align:center value='".$ls_moncar."' onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=javascript:ue_validardispo()><input name=disponible".$li_i." type=hidden id=disponible".$li_i." value='".$ls_disponible."'>";
		$la_objectcuentas[$li_i][5]="<a href=javascript:ue_removercuenta(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
	}	
	$la_objectcuentas[$li_filascuentas][1]="<input name=txtcodcue".$li_filascuentas." type=text id=txtcodcue".$li_filascuentas." class=sin-borde style= text-align:center size=40 readonly><input name=codest1".$li_filascuentas." type=hidden id=codest1".$li_filascuentas."><input name=codest2".$li_filascuentas." type=hidden id=codest2".$li_filascuentas."><input name=codest3".$li_filascuentas." type=hidden id=codest3".$li_filascuentas."><input name=codest4".$li_filascuentas." type=hidden id=codest4".$li_filascuentas."><input name=codest5".$li_filascuentas." type=hidden id=codest5".$li_filascuentas.">";
	$la_objectcuentas[$li_filascuentas][2]="<input name=txtestcla".$li_filascuentas." type=text id=txtestcla".$li_filascuentas." class=sin-borde style= text-align:center size=20 readonly>";
	$la_objectcuentas[$li_filascuentas][3]="<input name=txtnomcue".$li_filascuentas." type=text id=txtnomcue".$li_filascuentas." class=sin-borde style= text-align:left size=10 readonly>";
	$la_objectcuentas[$li_filascuentas][4]="<input name=txtmoncue".$li_filascuentas." type=text id=txtmoncue".$li_filascuentas." class=sin-borde size=20 style= text-align:center readonly><input name=disponible".$li_filascuentas." type=hidden id=disponible".$li_filascuentas." value='".$ls_disponible."'>";
	$la_objectcuentas[$li_filascuentas][5]="<input name=txtvacio".$li_filascuentas." type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	$li_filascargos=$_POST["filascargos"];
	$li_filascargos=$li_filascargos+1;
	
	for($li_i=1;$li_i<$li_filascargos;$li_i++)
	{
		$ls_codigo=$_POST["txtcodcar".$li_i];
		$ls_nombre=$_POST["txtnomcar".$li_i];
		$ld_monto=$_POST["txtmoncar".$li_i];
		$ls_formula=$_POST["formula".$li_i];
		$ls_prog=$_POST["prog".$li_i];
		$ls_spgcuenta=$_POST["spgcuenta".$li_i];
		$la_objectcargos[$li_i][1]="<input name=txtcodcar".$li_i." type=text id=txtcodcar".$li_i." class=sin-borde style= text-align:center size=5 value='".$ls_codigo."' readonly><input name=prog".$li_i." type=hidden id=prog".$li_i." value='".$ls_prog."'><input name=spgcuenta".$li_i." type=hidden id=spgcuenta".$li_i." value='".$ls_spgcuenta."'>";
		$la_objectcargos[$li_i][2]="<input name=txtnomcar".$li_i." type=text id=txtnomcar".$li_i." class=sin-borde style= text-align:left size=60 value='".$ls_nombre."' readonly >";
		$la_objectcargos[$li_i][3]="<input name=txtmoncar".$li_i." type=text id=txtmoncar".$li_i." class=sin-borde size=20 style= text-align:center value='".$ld_monto."' readonly><input name=formula".$li_i." type=hidden id=formula".$li_i." value='".$ls_formula."'>";
		$la_objectcargos[$li_i][4]="<a href=javascript:ue_removercargo(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
	    
	}	
	
	$la_objectcargos[$li_filascargos][1]="<input name=txtcodcar".$li_filascargos." type=text id=txtcodcar".$li_filascargos." class=sin-borde style= text-align:center size=5 ><input name=prog".$li_filascargos." type=hidden id=prog".$li_filascargos."><input name=spgcuenta".$li_filascargos." type=hidden id=spgcuenta".$li_filascargos.">";
	$la_objectcargos[$li_filascargos][2]="<input name=txtnomcar".$li_filascargos." type=text id=txtnomcar".$li_filascargos." class=sin-borde style= text-align:left size=50 >";
	$la_objectcargos[$li_filascargos][3]="<input name=txtmoncar".$li_filascargos." type=text id=txtmoncar".$li_filascargos." class=sin-borde size=20 style= text-align:center><input name=formula".$li_filascargos." type=hidden id=formula".$li_filascargos.">";
	$la_objectcargos[$li_filascargos][4]="<input name=txtvacio".$li_filascargos." type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
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
		$la_objectcuentas[$li_i][1]="<input name=txtcodcue".$li_i." type=text id=txtcodcue".$li_i." class=sin-borde style= text-align:center size=40 value='".$ls_codigo."' readonly><input name=codest1".$li_i." type=hidden id=codest1".$li_i." value='".$ls_codest1."'><input name=codest2".$li_i." type=hidden id=codest2".$li_i." value='".$ls_codest2."'><input name=codest3".$li_i." type=hidden id=codest3".$li_i." value='".$ls_codest3."'><input name=codest4".$li_i." type=hidden id=codest4".$li_i." value='".$ls_codest4."'><input name=codest5".$li_i." type=hidden id=codest5".$li_i." value='".$ls_codest5."'>".
								    "<input name=estcar".$li_i." type=hidden id=estcar".$li_i." value=''>";
	    $la_objectcuentas[$li_i][2]="<input name=txtestcla".$li_i." type=text id=txtestcla".$li_i." class=sin-borde style= text-align:center size=20 value='".$ls_estcla."' readonly>";
		$la_objectcuentas[$li_i][3]="<input name=txtnomcue".$li_i." type=text id=txtnomcue".$li_i." class=sin-borde style= text-align:left size=10 value='".$ls_nombre."' readonly >";
		$la_objectcuentas[$li_i][4]="<input name=txtmoncue".$li_i." type=text id=txtmoncue".$li_i." class=sin-borde size=20 style= text-align:center value='".$ls_moncar."' onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=javascript:ue_validardispo()><input name=disponible".$li_i." type=hidden id=disponible".$li_i." value='".$ls_disponible."'>";
		$la_objectcuentas[$li_i][5]="<a href=javascript:ue_removercuenta(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
	}	
	$la_objectcuentas[$li_filascuentas][1]="<input name=txtcodcue".$li_filascuentas." type=text id=txtcodcue".$li_filascuentas." class=sin-borde style= text-align:center size=40 readonly><input name=codest1".$li_filascuentas." type=hidden id=codest1".$li_filascuentas."><input name=codest2".$li_filascuentas." type=hidden id=codest2".$li_filascuentas."><input name=codest3".$li_filascuentas." type=hidden id=codest3".$li_filascuentas."><input name=codest4".$li_filascuentas." type=hidden id=codest4".$li_filascuentas."><input name=codest5".$li_filascuentas." type=hidden id=codest5".$li_filascuentas.">";
	$la_objectcuentas[$li_filascuentas][2]="<input name=txtestcla".$li_filascuentas." type=text id=txtestcla".$li_filascuentas." class=sin-borde style= text-align:center size=20 readonly>";
	$la_objectcuentas[$li_filascuentas][3]="<input name=txtnomcue".$li_filascuentas." type=text id=txtnomcue".$li_filascuentas." class=sin-borde style= text-align:left size=10 readonly>";
	$la_objectcuentas[$li_filascuentas][4]="<input name=txtmoncue".$li_filascuentas." type=text id=txtmoncue".$li_filascuentas." class=sin-borde size=20 style= text-align:center readonly><input name=disponible".$li_filascuentas." type=hidden id=disponible".$li_filascuentas." value='".$ls_disponible."'>";
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
			$ls_codigo     = $_POST["txtcodcue".$li_i];
		    $ls_codest1    = $_POST["codest1".$li_i];
		    $ls_codest2    = $_POST["codest2".$li_i];
		    $ls_codest3    = $_POST["codest3".$li_i];
		    $ls_codest4    = $_POST["codest4".$li_i];
		    $ls_codest5    = $_POST["codest5".$li_i];
			$ls_disponible = $_POST["disponible".$li_i];
		    $ls_nombre     = $_POST["txtnomcue".$li_i];
		    $ls_moncar     = $_POST["txtmoncue".$li_i];
		    $ls_estcla     = $_POST["txtestcla".$li_i];
		    $ls_estcar     = $_POST["estcar".$li_i];
			//$ls_prog       = $_POST["prog".$li_temp];
			//$ls_spgcuenta  = $_POST["spgcuenta".$li_temp];
			
			
		    $la_objectcuentas[$li_temp][1]="<input name=txtcodcue".$li_temp." type=text id=txtcodcue".$li_temp." class=sin-borde style= text-align:center size=40 value='".$ls_codigo."' readonly><input name=codest1".$li_temp." type=hidden id=codest1".$li_temp." value='".$ls_codest1."'><input name=codest2".$li_temp." type=hidden id=codest2".$li_temp." value='".$ls_codest2."'><input name=codest3".$li_temp." type=hidden id=codest3".$li_temp." value='".$ls_codest3."'><input name=codest4".$li_temp." type=hidden id=codest4".$li_temp." value='".$ls_codest4."'><input name=codest5".$li_temp." type=hidden id=codest5".$li_temp." value='".$ls_codest5."'><input name=disponible".$li_temp." type=hidden id=disponible".$li_temp." value='".$ls_disponible."'><input name=txtnomcue".$li_temp." type=hidden id=txtnomcue".$li_temp." value='".$ls_nombre."'><input name=txtmoncue".$li_temp." type=hidden id=txtmoncue".$li_temp." value='".$ls_moncar."'>".
								           "<input name=estcar".$li_temp." type=hidden id=estcar".$li_temp." value='".$ls_estcar."'>";
	   		$la_objectcuentas[$li_temp][2]="<input name=txtestcla".$li_temp." type=text id=txtestcla".$li_temp." class=sin-borde style= text-align:center size=20 value='".$ls_estcla."' readonly>";
		    $la_objectcuentas[$li_temp][3]="<input name=txtnomcue".$li_temp." type=text id=txtnomcue".$li_temp." class=sin-borde style= text-align:left size=10 value='".$ls_nombre."' readonly >";
		    $la_objectcuentas[$li_temp][4]="<input name=txtmoncue".$li_temp." type=text id=txtmoncue".$li_temp." class=sin-borde size=20 style= text-align:center value='".$ls_moncar."' onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=javascript:ue_validardispo()><input name=disponible".$li_temp." type=hidden id=disponible".$li_temp." value='".$ls_disponible."'>";
		    if($ls_estcar!="C")
			{
				$la_objectcuentas[$li_temp][5]="<a href=javascript:ue_removercuenta(".$li_temp.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
			}
			else
			{
				$la_objectcuentas[$li_temp][5]="";
			}
		}
	}
	$la_objectcuentas[$li_filascuentas][1]="<input name=txtcodcue".$li_filascuentas." type=text id=txtcodcue".$li_filascuentas." class=sin-borde style= text-align:center size=40 readonly><input name=codest1".$li_filascuentas." type=hidden id=codest1".$li_filascuentas."><input name=codest2".$li_filascuentas." type=hidden id=codest2".$li_filascuentas."><input name=codest3".$li_filascuentas." type=hidden id=codest3".$li_filascuentas."><input name=codest4".$li_filascuentas." type=hidden id=codest4".$li_filascuentas."><input name=codest5".$li_filascuentas." type=hidden id=codest5".$li_filascuentas.">";
	$la_objectcuentas[$li_filascuentas][2]="<input name=txtestcla".$li_filascuentas." type=text id=txtestcla".$li_filascuentas." class=sin-borde style= text-align:center size=20 readonly>";
	$la_objectcuentas[$li_filascuentas][3]="<input name=txtnomcue".$li_filascuentas." type=text id=txtnomcue".$li_filascuentas." class=sin-borde style= text-align:left size=10 readonly>";
	$la_objectcuentas[$li_filascuentas][4]="<input name=txtmoncue".$li_filascuentas." type=text id=txtmoncue".$li_filascuentas." class=sin-borde size=20 style= text-align:center readonly><input name=disponible".$li_filascuentas." type=hidden id=disponible".$li_filascuentas.">";
	$la_objectcuentas[$li_filascuentas][5]="<input name=txtvacio".$li_filascuentas." type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";


}
/***************************************************************************************************************************************************************************/

/*************************************************INSERTAR CAMPO EN GRID CARGOS**************************************************************************************************************************/
elseif($ls_operacion=="ue_cargarcargo")
{
	$lb_valido	=false;
	$ls_baseimp =$_POST["txtbasimpasi"];
	$ls_montpar =$_POST["txtmonparasi"];
	$ld_baseimpo=$io_funcsob->uf_convertir_cadenanumero($ls_baseimp);
	$ld_montopar=$io_funcsob->uf_convertir_cadenanumero($ls_montpar);
	$ld_montotasi=0;
/////////////////////////////////////////////////////CARLOS////////////////////////////////////////////////////////////////////////
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
		   $la_objectcuentas[$li_i][5]="<a href=javascript:ue_removercuenta(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
		}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	$li_filascargos=$_POST["filascargos"];
	$li_filascargos=$li_filascargos+1;
	
	for($li_i=1;$li_i<$li_filascargos;$li_i++)
	{
		$ls_codigo=$_POST["txtcodcar".$li_i];
		$ls_nombre=$_POST["txtnomcar".$li_i];
		$ls_formula=$_POST["formula".$li_i];
		$ls_prog=$_POST["prog".$li_i];
		$ls_spgcuenta=$_POST["spgcuenta".$li_i];
		$ld_result=$io_evalform->uf_evaluar($ls_formula,$ld_baseimpo,$lb_valido);
		$ld_montotasi=$ld_montotasi+$ld_result;
		
		$la_objectcargos[$li_i][1]="<input name=txtcodcar".$li_i." type=text id=txtcodcar".$li_i." class=sin-borde style= text-align:center size=5 value='".$ls_codigo."' readonly><input name=prog".$li_i." type=hidden id=prog".$li_i." value='".$ls_prog."'><input name=spgcuenta".$li_i." type=hidden id=spgcuenta".$li_i." value='".$ls_spgcuenta."'>";
		$la_objectcargos[$li_i][2]="<input name=txtnomcar".$li_i." type=text id=txtnomcar".$li_i." class=sin-borde style= text-align:left size=60 value='".$ls_nombre."' readonly >";
		$la_objectcargos[$li_i][3]="<input name=txtmoncar".$li_i." type=text id=txtmoncar".$li_i." class=sin-borde size=20 style= text-align:center value='".$io_funcsob->uf_convertir_numerocadena($ld_result)."' readonly><input name=formula".$li_i." type=hidden id=formula".$li_i." value='".$ls_formula."'>";
		$la_objectcargos[$li_i][4]="<a href=javascript:ue_removercargo(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
	    
	}	
	
	$la_objectcargos[$li_filascargos][1]="<input name=txtcodcar".$li_filascargos." type=text id=txtcodcar".$li_filascargos." class=sin-borde style= text-align:center size=5 ><input name=prog".$li_filascargos." type=hidden id=prog".$li_filascargos."><input name=spgcuenta".$li_filascargos." type=hidden id=spgcuenta".$li_filascargos.">";
	$la_objectcargos[$li_filascargos][2]="<input name=txtnomcar".$li_filascargos." type=text id=txtnomcar".$li_filascargos." class=sin-borde style= text-align:left size=50 >";
	$la_objectcargos[$li_filascargos][3]="<input name=txtmoncar".$li_filascargos." type=text id=txtmoncar".$li_filascargos." class=sin-borde size=20 style= text-align:center><input name=formula".$li_filascargos." type=hidden id=formula".$li_filascargos.">";
	$la_objectcargos[$li_filascargos][4]="<input name=txtvacio".$li_filascargos." type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
//////////////////////////////////////////////////////////CARLOS/////////////////////////////////////////////////////////////////////////    

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     $ld_subtotal=$ld_montopar-$ld_baseimpo;
	 $ld_resultado=$ld_baseimpo+$ld_montotasi+$ld_subtotal;  
	 $ls_montotasi=$io_funcsob->uf_convertir_numerocadena($ld_resultado);
}
/***************************************************************************************************************************************************************************/

/*******************************************************REMOVER CAMPO EN GRID CARGOS********************************************************************************************************************/
elseif($ls_operacion=="ue_removercargo")
{
    $lb_valido=false;
	$ls_baseimp=$_POST["txtbasimpasi"];
	$ls_montpar=$_POST["txtmonparasi"];
	$ld_baseimpo=$io_funcsob->uf_convertir_cadenanumero($ls_baseimp);
	$ld_montopar=$io_funcsob->uf_convertir_cadenanumero($ls_montpar);
	$ld_montotasi=0;
	        
	$li_filascargos=$_POST["filascargos"];
	$li_filascargos=$li_filascargos-1;
	$li_removercargo=$_POST["hidremovercargo"];
	$li_temp=0;
	for($li_i=1;$li_i<=$li_filascargos;$li_i++)
	{
		if($li_i!=$li_removercargo)
		{		
			$li_temp=$li_temp+1;
			$ls_codigo=$_POST["txtcodcar".$li_temp];
			$ls_nombre=$_POST["txtnomcar".$li_temp];
			$ls_monto=$_POST["txtmoncar".$li_temp];
			$ls_formula=$_POST["formula".$li_temp];
			$ls_prog=$_POST["prog".$li_temp];
		    $ls_spgcuenta=$_POST["spgcuenta".$li_temp];
			$la_objectcargos[$li_temp][1]="<input name=txtcodcar".$li_temp." type=text id=txtcodcar".$li_temp." class=sin-borde style= text-align:center size=5 value='".$ls_codigo."' readonly><input name=prog".$li_temp." type=hidden id=prog".$li_temp." value='".$ls_prog."'><input name=spgcuenta".$li_temp." type=hidden id=spgcuenta".$li_temp." value='".$ls_spgcuenta."'>";
			$la_objectcargos[$li_temp][2]="<input name=txtnomcar".$li_temp." type=text id=txtnomcar".$li_temp." class=sin-borde style= text-align:left size=60 value='".$ls_nombre."' readonly >";
			$la_objectcargos[$li_temp][3]="<input name=txtmoncar".$li_temp." type=text id=txtmoncar".$li_temp." class=sin-borde size=20 style= text-align:center value='".$ls_monto."' readonly><input name=formula".$li_temp." type=hidden id=formula".$li_temp." value='".$ls_formula."'>";
			$la_objectcargos[$li_temp][4]="<a href=javascript:ue_removercargo(".$li_temp.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
			$ld_result=$io_evalform->uf_evaluar($ls_formula,$ld_baseimpo,$lb_valido);
		    $ld_montotasi=$ld_montotasi+$ld_result;
		}
	}
	$la_objectcargos[$li_filascargos][1]="<input name=txtcodcar".$li_filascargos." type=text id=txtcodcar".$li_filascargos." class=sin-borde style= text-align:center size=5 readonly><input name=prog".$li_filascargos." type=hidden id=prog".$li_filascargos."><input name=spgcuenta".$li_filascargos." type=hidden id=spgcuenta".$li_filascargos.">";
	$la_objectcargos[$li_filascargos][2]="<input name=txtnomcar".$li_filascargos." type=text id=txtnomcar".$li_filascargos." class=sin-borde style= text-align:left size=50 readonly>";
	$la_objectcargos[$li_filascargos][3]="<input name=txtmoncar".$li_filascargos." type=text id=txtmoncar".$li_filascargos." class=sin-borde size=20 style= text-align:center readonly><input name=formula".$li_filascargos." type=hidden id=formula".$li_filascargos.">";
	$la_objectcargos[$li_filascargos][4]="<input name=txtvacio".$li_filascargos." type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	
	$ld_subtotal=$ld_montopar-$ld_baseimpo;
	$ld_resultado=$ld_baseimpo+$ld_montotasi+$ld_subtotal;  
	$ls_montotasi=$io_funcsob->uf_convertir_numerocadena($ld_resultado);
}
/***************************************************************************************************************************************************************************/

/*******************************************INSERCION DE REGISTRO EN BD*******************************************************************************************************************************/
elseif($ls_operacion=="ue_guardar")
{
  $ld_date=$io_function->uf_convertirdatetobd($ls_fecasi);
  $ls_chk=$_POST["hidstatus"];
  $io_asignacion->io_sql->begin_transaction();
  $ls_codasiaux=$ls_codasi;
  $lb_valido=$io_asignacion->uf_guardar_asignacion($ls_codasi,$ls_codobrasi,$ls_codproasi,$ls_codinsasi,$ls_ptocueasi,$ld_date,$ls_obsasi,$ls_monparasi,$ls_basimpasi,$ls_montotasi,$la_seguridad,$ls_chk);
  if($lb_valido)
  {
   /************  GUARDANDO PARTIDAS   ***********/
	$li_partidas=1;
    $la_partidas["codpar"][1]="";
	$la_partidas["canteje"][1]="";
	$la_partidas["cant"][1]="";
	$la_partidas["preref"][1]="";
	$la_partidas["pre"][1]="";
	
    for ($li_i=1;$li_i<$li_filaspartidas;$li_i++)
	{
	   if(!empty($_POST["flagpar".$li_i]))
	   {
	   	$la_partidas["codpar"][$li_partidas]=$_POST["txtcodpar".$li_i];
		$la_partidas["canteje"][$li_partidas]=$_POST["canpareje".$li_i];
	    $la_partidas["cant"][$li_partidas]=$_POST["txtcantpar".$li_i];
		$la_partidas["preref"][$li_partidas]=$_POST["txtpreuni".$li_i];
	    $la_partidas["pre"][$li_partidas]=$_POST["txtpreunimod".$li_i];
		$li_partidas++;
	   }
	}
	$lb_valido=$io_asignacion->uf_update_dtpartidas($ls_codasi,$ls_codobrasi,$la_partidas,$li_partidas,$la_seguridad);
   /***********************************************/
   
   /************  GUARDANDO CARGOS   **************/
    $la_cargos["codcar"][1]="";
	$la_cargos["moncar"][1]="";
	$la_cargos["formula"][1]="";
	$la_cargos["codestpro"][1]="";
	$la_cargos["spgcuenta"][1]="";
	$li_filascargos=0;
	if (array_key_exists("cargos",$_SESSION))
	{
		$la_sesscar=$_SESSION["cargos"];
		$li_filascargos=count($la_sesscar["codcar"]);
		for ($li_i=1;$li_i<=$li_filascargos;$li_i++)
		{
			  $la_cargos["codcar"][$li_i]    = $la_sesscar["codcar"][$li_i];
			  $la_cargos["moncar"][$li_i]    = $la_sesscar["monimp"][$li_i];
			  $la_cargos["basimp"][$li_i]    = $la_sesscar["baseimp"][$li_i];
			  $la_cargos["formula"][$li_i]   = $la_sesscar["formula"][$li_i];
			  $la_cargos["codestpro"][$li_i] = $la_sesscar["codestpro"][$li_i];
			  $la_cargos["estcla"][$li_i] = $la_sesscar["estcla"][$li_i];
			  $la_cargos["spgcuenta"][$li_i] = $la_sesscar["spgcuenta"][$li_i];
		}
		$li_filascargos=$li_filascargos+1;
	}
	if($lb_valido)
	{ 
		$lb_valido=$io_asignacion->uf_update_dtcargos($ls_codasi,$ls_basimpasi,$la_cargos,$li_filascargos,$la_seguridad);
	}
		
  /***********************************************/ 
  /************  GUARDANDO CUENTAS   **************/	
	$la_cuentas["codcue"][1]="";
	$la_cuentas["codest1"][1]="";
	$la_cuentas["codest2"][1]="";
	$la_cuentas["codest3"][1]="";
	$la_cuentas["codest4"][1]="";
	$la_cuentas["codest5"][1]="";
	$la_cuentas["estcla"][1]="";
	$la_cuentas["moncue"][1]="";
	$li_filascuentas=$io_fun_sob->uf_obtenervalor("filascuentas",0);
	
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
		//print "ESTRUC->".$la_cuentas["codest1"][$li_i]." - ".$la_cuentas["codest2"][$li_i]." - ".$la_cuentas["codest3"][$li_i]."<br>";
		   $ls_codigo=$_POST["txtcodcue".$li_i];
		   $ls_codest1=$_POST["codest1".$li_i];
		   $ls_codest2=$_POST["codest2".$li_i];
		   $ls_codest3=$_POST["codest3".$li_i];
		   $ls_codest4=$_POST["codest4".$li_i];
		   $ls_codest5=$_POST["codest5".$li_i];
		   $ls_estcla=$_POST["txtestcla".$li_i];
		   $ls_disponible=$_POST["disponible".$li_i];
		   $ls_nombre=$_POST["txtnomcue".$li_i];
		   $ls_moncar=$_POST["txtmoncue".$li_i];
		   $ls_estcar=$_POST["estcar".$li_i];
		   $li_row=$li_i;
		   $la_objectcuentas[$li_row][1]="<input name=txtcodcue".$li_row." type=text id=txtcodcue".$li_row." class=sin-borde style= text-align:center size=40 value='".$ls_codigo."' readonly><input name=codest1".$li_row." type=hidden id=codest1".$li_row." value='".$ls_codest1."'><input name=codest2".$li_row." type=hidden id=codest2".$li_row." value='".$ls_codest2."'><input name=codest3".$li_row." type=hidden id=codest3".$li_row." value='".$ls_codest3."'><input name=codest4".$li_row." type=hidden id=codest4".$li_row." value='".$ls_codest4."'><input name=codest5".$li_row." type=hidden id=codest5".$li_row." value='".$ls_codest5."'>".
									   "<input name=estcar".$li_row." type=hidden id=estcar".$li_row." value=''>";
		   $la_objectcuentas[$li_row][2]="<input name=txtestcla".$li_row." type=text id=txtestcla".$li_row." class=sin-borde style= text-align:center size=20 value='".$ls_estcla."' readonly>";
		   $la_objectcuentas[$li_row][3]="<input name=txtnomcue".$li_row." type=text id=txtnomcue".$li_row." class=sin-borde style= text-align:left size=10 value='".$ls_nombre."' readonly >";
		   $la_objectcuentas[$li_row][4]="<input name=txtmoncue".$li_row." type=text id=txtmoncue".$li_row." class=sin-borde size=20 style= text-align:center value='".$ls_moncar."' onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=javascript:ue_validardispo()><input name=disponible".$li_row." type=hidden id=disponible".$li_row." value='".$ls_disponible."'>";
		   	if($ls_estcar!="C")
		   	{
			   $la_objectcuentas[$li_row][5]="<a href=javascript:ue_removercuenta(".$li_row.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
			}
			else
			{
				$la_objectcuentas[$li_row][5]="";
			}
	}
		$li_totrowspg=li_i+1;
		$la_objectcuentas[$li_filascuentas][1]="<input name=txtcodcue".$li_filascuentas." type=text id=txtcodcue".$li_filascuentas." class=sin-borde style= text-align:center size=40 readonly><input name=codest1".$li_filascuentas." type=hidden id=codest1".$li_filascuentas."><input name=codest2".$li_filascuentas." type=hidden id=codest2".$li_filascuentas."><input name=codest3".$li_filascuentas." type=hidden id=codest3".$li_filascuentas."><input name=codest4".$li_filascuentas." type=hidden id=codest4".$li_filascuentas."><input name=codest5".$li_filascuentas." type=hidden id=codest5".$li_filascuentas.">".
										  	   "<input name=estcar".$li_filascuentas." type=hidden id=estcar".$li_filascuentas." value=''>";
	    $la_objectcuentas[$li_filascuentas][2]="<input name=txtestcla".$li_filascuentas." type=text id=txtestcla".$li_filascuentas." class=sin-borde style= text-align:center size=20 readonly>";
		$la_objectcuentas[$li_filascuentas][3]="<input name=txtnomcue".$li_filascuentas." type=text id=txtnomcue".$li_filascuentas." class=sin-borde style= text-align:left size=10 readonly><input name=disponible".$li_filascuentas." type=hidden id=disponible".$li_filascuentas.">";
		$la_objectcuentas[$li_filascuentas][4]="<input name=txtmoncue".$li_filascuentas." type=text id=txtmoncue".$li_filascuentas." class=sin-borde style= text-align:center size=20 readonly>";
		$la_objectcuentas[$li_filascuentas][5]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	if($lb_valido)
	{	 
		$lb_valido=$io_asignacion->uf_update_dtcuentas($ls_codasi,$la_cuentas,$li_filascuentas,$la_seguridad);
	}
	if($lb_valido)
	{
		$lb_ok=$io_asignacion->uf_validar_cuentas($la_cuentas);
	}
   /***********************************************/ 	
	if($lb_valido)
	{
		$lb_valido=$io_asignacion->uf_update_estadoobra($ls_codobrasi,2);
	}
	if($lb_valido)
	{
		if($ls_codasiaux!=$ls_codasi)
		{$io_msg->message("Se le asigno un nuevo numero de asignación ".$ls_codasi );}
		$io_msg->message("Operación Ejecutada Exitosamente");
		$io_asignacion->io_sql->commit();
		uf_limpiar();
	}
	else
	{
		$io_msg->message("No se proceso la operación");
		$io_asignacion->io_sql->rollback();
	}
	/*print "<script language=javascript>";
	print "location.href=location";
	print "</script>";*/
  }
}
/***************************************************************************************************************************************************************************/

/*******************************************ANULAR UNA ASIGNACION*******************************************************************************************************************************/
elseif($ls_operacion=="ue_anular")
{   
	$io_asignacion->uf_select_estado($ls_codasi,&$ls_estasi);
	if(($ls_estasi==1)||($ls_estasi==6))
	{ 
		$io_asignacion->io_sql->begin_transaction();	
		$lb_valido=$io_asignacion->uf_update_estado($ls_codasi,3,$la_seguridad);
		if($lb_valido)
		{
			for ($li_i=1;$li_i<$li_filaspartidas;$li_i++)
			{
				if(!empty($_POST["flagpar".$li_i]))
				{
					$ls_codparG=$_POST["txtcodpar".$li_i];
					$ls_canejeG=$_POST["canpareje".$li_i];
					$ls_canparG=$_POST["txtcantpar".$li_i];
					$lb_valido=$io_asignacion->uf_update_actcantidad($ls_codobrasi,$ls_codparG,$ls_canparG,$ls_canejeG,$la_seguridad);
				}
				if(!$lb_valido)
				{
					break;
				}
			}
			if($lb_valido)
			{
				$io_msg->message("Registro Anulado Exitosamente");
				$io_asignacion->io_sql->commit();
			}
			else
			{
				$io_msg->message("No se proceso la Anulacion");
				$io_asignacion->io_sql->rollback();
			}
		}
   }
   else
   {
    $io_msg->message("Esta Asignacion no puede ser Anulada!!");
   }
    print "<script language=javascript>";
	print "location.href=location";
	print "</script>";
}
/***************************************************************************************************************************************************************************/

/*******************************************CARGAR DATOS DE UNA ASIGNACION*******************************************************************************************************************************/
elseif($ls_operacion=="ue_cargarasignacion")
{   
    $ls_codasi=$_POST["txtcodasi"];
	$ls_est=$_POST["txtestasi"];
	$ls_estadoasi=$io_funcsob->uf_convertir_numeroestado($ls_est);
	$io_asignacion->uf_buscar_inspector($ls_codasi,&$ls_nominsasi);
	
	/*************************CARGANDO PARTIDAS***********************/
    $lb_validop=$io_asignacion->uf_select_allpartidas($ls_codobrasi,$ls_codasi,$la_partidas,$ai_rows);
	if($lb_validop)
	{
	$io_datastore->data=$la_partidas;
	$li_filaspartidas=$io_datastore->getRowCount("codpar");
	if (!empty ($ls_ptocueasi))
	 {
	   $ls_readonly="readonly"; 
	 }
	for($li_i=1;$li_i<=$li_filaspartidas;$li_i++)
	{
		$ls_codigo=$io_datastore->getValue("codpar",$li_i);
		$ls_codigoasi=$io_datastore->getValue("codasi",$li_i);
		$ls_canpareje=$io_datastore->getValue("canparasi",$li_i);
		$ls_nombre=$io_datastore->getValue("nompar",$li_i);
		$ls_unidad=$io_datastore->getValue("nomuni",$li_i);
		$ls_preuni=$io_datastore->getValue("prepar",$li_i);
		$ls_preunimod=$io_datastore->getValue("preparasi",$li_i);
		$ls_canttot=$io_datastore->getValue("canttot",$li_i);
		$ls_cantpar=$io_datastore->getValue("canparobrasi",$li_i);
		$ld_total=$ls_cantpar*$ls_preunimod;
		$ld_cant=$ls_canttot+$ls_cantpar;
		if(($ls_cantpar!=0))
		{
		 $la_objectpartidas[$li_i][1]="<input type=checkbox name=flagpar".$li_i." value=1 class=sin-borde checked  onClick='javascript: ue_marcartodo(".$li_i.");'>";
		}
		else
		{
		 $la_objectpartidas[$li_i][1]="<input type=checkbox name=flagpar".$li_i." value=1 class=sin-borde  onClick='javascript: ue_marcartodo(".$li_i.");'>";
		}
	    $la_objectpartidas[$li_i][2]="<input name=txtcodpar".$li_i." type=text id=txtcodpar".$li_i." value='".$ls_codigo."' class=sin-borde style= text-align:center size=8 readonly><input name=canpareje".$li_i." type=hidden id=canpareje".$li_i." value='".$ls_canpareje."'>";
	    $la_objectpartidas[$li_i][3]="<input name=txtnompar".$li_i." type=text id=txtnompar".$li_i." value='".$ls_nombre."' class=sin-borde style= text-align:left size=25 readonly>";
	    $la_objectpartidas[$li_i][4]="<input name=txtnomuni".$li_i." type=text id=txtnomuni".$li_i." value='".$ls_unidad."' class=sin-borde size=5 style= text-align:center readonly>";
	    $la_objectpartidas[$li_i][5]="<input name=txtpreuni".$li_i." type=text id=txtpreuni".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_preuni)."' class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectpartidas[$li_i][6]="<input name=txtpreunimod".$li_i." type=text id=txtpreunimod".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_preunimod)."' class=sin-borde size=15 style= text-align:center onKeyPress=return(currencyFormat(this,'.',',',event))>";
	    $la_objectpartidas[$li_i][7]="<input name=txtcanttot".$li_i." type=text id=txtcanttot".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ld_cant)."' class=sin-borde size=5 style= text-align:center readonly>";
	    $la_objectpartidas[$li_i][8]="<input name=txtcantpar".$li_i." type=text id=txtcantpar".$li_i." class=sin-borde size=5 style= text-align:center value='".$io_funcsob->uf_convertir_numerocadena($ls_cantpar)."' onKeyPress=return(currencyFormat(this,'.',',',event))  onBlur=ue_subtotal(); $ls_readonly>";
	    $la_objectpartidas[$li_i][9]="<input name=txttotal".$li_i." type=text id=txttotal".$li_i." class=sin-borde size=15 style= text-align:center value='".$io_funcsob->uf_convertir_numerocadena($ld_total)."' readonly>";
		
		if($ld_cant==0)
			{
			$la_objectpartidas[$li_i][1]="<input type=checkbox name=flagpar".$li_i." value=1 class=sin-borde  onClick='javascript: ue_marcartodo(".$li_i.");' disabled>";
	        $la_objectpartidas[$li_i][2]="<input name=txtcodpar".$li_i." type=text id=txtcodpar".$li_i." value='".$ls_codigo."' class=sin-borde style= text-align:center size=8 readonly><input name=canpareje".$li_i." type=hidden id=canpareje".$li_i." value='".$ls_canpareje."'>";
	        $la_objectpartidas[$li_i][3]="<input name=txtnompar".$li_i." type=text id=txtnompar".$li_i." value='".$ls_nombre."' class=sin-borde style= text-align:left size=25 readonly>";
	        $la_objectpartidas[$li_i][4]="<input name=txtnomuni".$li_i." type=text id=txtnomuni".$li_i." value='".$ls_unidad."' class=sin-borde size=5 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][5]="<input name=txtpreuni".$li_i." type=text id=txtpreuni".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_preuni)."' class=sin-borde size=15 style= text-align:center readonly>";
			$la_objectpartidas[$li_i][6]="<input name=txtpreunimod".$li_i." type=text id=txtpreunimod".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_preuni)."' class=sin-borde size=15 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][7]="<input name=txtcanttot".$li_i." type=text id=txtcanttot".$li_i." value='".$io_funcsob->uf_convertir_numerocadena($ls_canttot)."' class=sin-borde size=5 style= text-align:center readonly>";
	        $la_objectpartidas[$li_i][8]="<input name=txtcantpar".$li_i." type=text id=txtcantpar".$li_i." class=sin-borde size=5 style= text-align:center $ls_readonly>";
	        $la_objectpartidas[$li_i][9]="<input name=txttotal".$li_i." type=text id=txttotal".$li_i." class=sin-borde size=15 style= text-align:center readonly>";	
			}
	}	
	$li_filaspartidas=$li_filaspartidas+1;
	$la_objectpartidas[$li_filaspartidas][1]="<input type=checkbox name=flagpar".$li_filaspartidas." value=1 disabled class=sin-borde onClick='javascript: ue_marcartodo(".$li_i.");'>";
	$la_objectpartidas[$li_filaspartidas][2]="<input name=txtcodpar".$li_filaspartidas." type=text id=txtcodpar".$li_filaspartidas." class=sin-borde style= text-align:center size=8 readonly><input name=canpareje".$li_filaspartidas." type=hidden id=canpareje".$li_filaspartidas.">";
	$la_objectpartidas[$li_filaspartidas][3]="<input name=txtnompar".$li_filaspartidas." type=text id=txtnompar".$li_filaspartidas." class=sin-borde style= text-align:left size=25 readonly>";
	$la_objectpartidas[$li_filaspartidas][4]="<input name=txtnomuni".$li_filaspartidas." type=text id=txtnomuni".$li_filaspartidas." class=sin-borde size=5 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][5]="<input name=txtpreuni".$li_filaspartidas." type=text id=txtpreuni".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][6]="<input name=txtpreunimod".$li_filaspartidas." type=text id=txtpreunimod".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][7]="<input name=txtcanttot".$li_filaspartidas." type=text id=txtcanttot".$li_filaspartidas." class=sin-borde size=5 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][8]="<input name=txtcantpar".$li_filaspartidas." type=text id=txtcantpar".$li_filaspartidas." class=sin-borde size=5 style= text-align:center $ls_readonly>";
	$la_objectpartidas[$li_filaspartidas][9]="<input name=txttotal".$li_filaspartidas." type=text id=txttotal".$li_filaspartidas." class=sin-borde size=15 style= text-align:center readonly>";
	}
	
	/*************************CARGANDO CUENTAS**********************/
	$lb_validoc=$io_asignacion->uf_select_cuentas($ls_codasi,$la_cuentas,$li_totalfilas);
	if($lb_validoc)
	{
	$io_datastore->data=$la_cuentas;
	$li_filascuentas=$io_datastore->getRowCount("spg_cuenta");
	$lb_valido=$io_fun_sob->uf_loadmodalidad(&$li_len1,&$li_len2,&$li_len3,&$li_len4,&$li_len5,&$ls_titulo);
	for($li_i=1;$li_i<=$li_filascuentas;$li_i++)
	{
		$ls_codest1=$io_funcsob->uf_convertir_cadenanumero($io_datastore->getValue("codestpro1",$li_i));
		$ls_codest2=$io_funcsob->uf_convertir_cadenanumero($io_datastore->getValue("codestpro2",$li_i));
		$ls_codest3=$io_funcsob->uf_convertir_cadenanumero($io_datastore->getValue("codestpro3",$li_i));
		$ls_codest4=$io_funcsob->uf_convertir_cadenanumero($io_datastore->getValue("codestpro4",$li_i));
		$ls_codest5=$io_funcsob->uf_convertir_cadenanumero($io_datastore->getValue("codestpro5",$li_i));
		$ls_estcla=$io_funcsob->uf_convertir_cadenanumero($io_datastore->getValue("estcla",$li_i));
		switch($ls_estcla)
		{
			case "A":
				$ls_estcla=utf8_encode("ACCION");
				break;
			case "P":
				$ls_estcla=utf8_encode("PROYECTO");
				break;
		}
		$ls_codigo=substr($ls_codest1,(strlen($ls_codest1)-$li_len1),$li_len1).substr($ls_codest2,(strlen($ls_codest2)-$li_len2),$li_len2).substr($ls_codest3,(strlen($ls_codest3)-$li_len3),$li_len3).substr($ls_codest4,(strlen($ls_codest4)-$li_len4),$li_len4).substr($ls_codest5,(strlen($ls_codest5)-$li_len5),$li_len5);
		$ls_nombre=$io_datastore->getValue("spg_cuenta",$li_i);
		$ls_moncar=$io_datastore->getValue("monto",$li_i);
		$ls_disponible=$io_datastore->getValue("disponible",$li_i);
		$la_objectcuentas[$li_i][1]="<input name=txtcodcue".$li_i." type=text id=txtcodcue".$li_i." class=sin-borde style= text-align:center size=40 value='".$ls_codigo."' readonly><input name=codest1".$li_i." type=hidden id=codest1".$li_i." value='".$ls_codest1."'><input name=codest2".$li_i." type=hidden id=codest2".$li_i." value='".$ls_codest2."'><input name=codest3".$li_i." type=hidden id=codest3".$li_i." value='".$ls_codest3."'><input name=codest4".$li_i." type=hidden id=codest4".$li_i." value='".$ls_codest4."'><input name=codest5".$li_i." type=hidden id=codest5".$li_i." value='".$ls_codest5."'>";
	   	$la_objectcuentas[$li_i][2]="<input name=txtestcla".$li_i." type=text id=txtestcla".$li_i." class=sin-borde style= text-align:center size=20 value='".$ls_estcla."' readonly>";
		$la_objectcuentas[$li_i][3]="<input name=txtnomcue".$li_i." type=text id=txtnomcue".$li_i." class=sin-borde style= text-align:left size=10 value='".$ls_nombre."' readonly >";
		$la_objectcuentas[$li_i][4]="<input name=txtmoncue".$li_i." type=text id=txtmoncue".$li_i." class=sin-borde size=20 style= text-align:center value='".$io_funcsob->uf_convertir_numerocadena($ls_moncar)."' onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=javascript:ue_validardispo()><input name=disponible".$li_i." type=hidden id=disponible".$li_i." value='".$ls_disponible."'>";
		$la_objectcuentas[$li_i][5]="<div align=center><a href=javascript:ue_removercuenta(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a></div>";
	}	
	$li_filascuentas=$li_filascuentas+1;
	$la_objectcuentas[$li_filascuentas][1]="<input name=txtcodcue".$li_filascuentas." type=text id=txtcodcue".$li_filascuentas." class=sin-borde style= text-align:center size=40 readonly><input name=codest1".$li_filascuentas." type=hidden id=codest1".$li_filascuentas."><input name=codest2".$li_filascuentas." type=hidden id=codest2".$li_filascuentas."><input name=codest3".$li_filascuentas." type=hidden id=codest3".$li_filascuentas."><input name=codest4".$li_filascuentas." type=hidden id=codest4".$li_filascuentas."><input name=codest5".$li_filascuentas." type=hidden id=codest5".$li_filascuentas.">";
	$la_objectcuentas[$li_filascuentas][2]="<input name=txtestcla".$li_filascuentas." type=text id=txtestcla".$li_filascuentas." class=sin-borde style= text-align:center size=20 readonly>";
	$la_objectcuentas[$li_filascuentas][3]="<input name=txtnomcue".$li_filascuentas." type=text id=txtnomcue".$li_filascuentas." class=sin-borde style= text-align:left size=10 readonly>";
	$la_objectcuentas[$li_filascuentas][4]="<input name=txtmoncue".$li_filascuentas." type=text id=txtmoncue".$li_filascuentas." class=sin-borde size=20 style= text-align:center readonly><input name=disponible".$li_filascuentas." type=hidden id=disponible".$li_filascuentas." value='".$ls_disponible."'>";
	$la_objectcuentas[$li_filascuentas][5]="<input name=txtvacio".$li_filascuentas." type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	}

   
  
}
/*******************************************CARGAR DATOS DE UNA ASIGNACION*******************************************************************************************************************************/
elseif($ls_operacion=="ue_cargarptocuenta")
{   
    $ls_ptocueasi=$_POST["txtptocueasi"];
	$io_asignacion->uf_select_ptocuenta($ls_ptocueasi,$la_ptocuen);
	
	$ls_codproasi=$la_ptocuen["cod_pro"][1];
	$ls_nomproasi=$la_ptocuen["nompro"][1];
	$ls_tipconpro=$la_ptocuen["tipconpro"][1];
	/*************************CARGANDO CUENTAS**********************/
	$lb_validoc=$io_asignacion->uf_select_cuentaspto($ls_ptocueasi,$ls_codobrasi,$la_cuentas,$li_totalfilas);
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
		$la_objectcuentas[$li_i][1]="<input name=txtcodcue".$li_i." type=text id=txtcodcue".$li_i." class=sin-borde style= text-align:center size=40 value='".$ls_codigo."' readonly><input name=codest1".$li_i." type=hidden id=codest1".$li_i." value='".$ls_codest1."'><input name=codest2".$li_i." type=hidden id=codest2".$li_i." value='".$ls_codest2."'><input name=codest3".$li_i." type=hidden id=codest3".$li_i." value='".$ls_codest3."'><input name=codest4".$li_i." type=hidden id=codest4".$li_i." value='".$ls_codest4."'><input name=codest5".$li_i." type=hidden id=codest5".$li_i." value='".$ls_codest5."'>";
	   	$la_objectcuentas[$li_i][2]="<input name=txtestcla".$li_i." type=text id=txtestcla".$li_i." class=sin-borde style= text-align:center size=20 value='".$ls_estcla."' readonly>";
		$la_objectcuentas[$li_i][3]="<input name=txtnomcue".$li_i." type=text id=txtnomcue".$li_i." class=sin-borde style= text-align:left size=10 value='".$ls_nombre."' readonly >";
		$la_objectcuentas[$li_i][4]="<input name=txtmoncue".$li_i." type=text id=txtmoncue".$li_i." class=sin-borde size=20 style= text-align:center value='".$io_funcsob->uf_convertir_numerocadena($ls_moncar)."' onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=javascript:ue_validardispo()><input name=disponible".$li_i." type=hidden id=disponible".$li_i." value='".$ls_disponible."'>";
		$la_objectcuentas[$li_i][5]="<div align=center><a href=javascript:ue_removercuenta(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a></div>";
	}	
	$li_filascuentas=$li_filascuentas+1;
	$la_objectcuentas[$li_filascuentas][1]="<input name=txtcodcue".$li_filascuentas." type=text id=txtcodcue".$li_filascuentas." class=sin-borde style= text-align:center size=40 readonly><input name=codest1".$li_filascuentas." type=hidden id=codest1".$li_filascuentas."><input name=codest2".$li_filascuentas." type=hidden id=codest2".$li_filascuentas."><input name=codest3".$li_filascuentas." type=hidden id=codest3".$li_filascuentas."><input name=codest4".$li_filascuentas." type=hidden id=codest4".$li_filascuentas."><input name=codest5".$li_filascuentas." type=hidden id=codest5".$li_filascuentas.">";
   	$la_objectcuentas[$li_filascuentas][2]="<input name=txtestcla".$li_filascuentas." type=text id=txtestcla".$li_filascuentas." class=sin-borde style= text-align:center size=20 readonly>";
	$la_objectcuentas[$li_filascuentas][3]="<input name=txtnomcue".$li_filascuentas." type=text id=txtnomcue".$li_filascuentas." class=sin-borde style= text-align:left size=10 readonly>";
	$la_objectcuentas[$li_filascuentas][4]="<input name=txtmoncue".$li_filascuentas." type=text id=txtmoncue".$li_filascuentas." class=sin-borde size=20 style= text-align:center readonly><input name=disponible".$li_filascuentas." type=hidden id=disponible".$li_filascuentas." value='".$ls_disponible."'>";
	$la_objectcuentas[$li_filascuentas][5]="<input name=txtvacio".$li_filascuentas." type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	}

/*************************CARGANDO CARGOS***********************/ //OJOOO FALTA CTSSPG DEL CARGO!!!!!
	$lb_validoca=$io_puntodecuenta-> uf_select_cargos($ls_ptocueasi,$la_cargos,$li_totalfilas);
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
		$ls_prog=$io_datastore->getValue("prog",$li_i);
		$ls_spgcta=$io_datastore->getValue("spg_cuenta",$li_i);
		$la_objectcargos[$li_i][1]="<input name=txtcodcar".$li_i." type=text id=txtcodcar".$li_i." class=sin-borde style= text-align:center size=5 value='".$ls_codigo."' readonly><input name=prog".$li_i." type=hidden id=prog".$li_i."><input name=spgcuenta".$li_i." type=hidden id=spgcuenta".$li_i.">";
		$la_objectcargos[$li_i][2]="<input name=txtnomcar".$li_i." type=text id=txtnomcar".$li_i." class=sin-borde style= text-align:left size=60 value='".$ls_nombre."' readonly >";
		$la_objectcargos[$li_i][3]="<input name=txtmoncar".$li_i." type=text id=txtmoncar".$li_i." class=sin-borde size=20 style= text-align:center value='".$io_funcsob->uf_convertir_numerocadena($ls_moncar)."' readonly><input name=formula".$li_i." type=hidden id=formula".$li_i." value='".$ls_formula."'>";
		$la_objectcargos[$li_i][4]="<div align=center><a href=javascript:ue_removercargo(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a></div>";
	}	
	$li_filascargos=$li_filascargos+1;
	$la_objectcargos[$li_filascargos][1]="<input name=txtcodcar".$li_filascargos." type=text id=txtcodcar".$li_filascargos." class=sin-borde style= text-align:center size=5 ><input name=prog".$li_filascargos." type=hidden id=prog".$li_filascargos."><input name=spgcuenta".$li_filascargos." type=hidden id=spgcuenta".$li_filascargos.">";
	$la_objectcargos[$li_filascargos][2]="<input name=txtnomcar".$li_filascargos." type=text id=txtnomcar".$li_filascargos." class=sin-borde style= text-align:left size=50 >";
	$la_objectcargos[$li_filascargos][3]="<input name=txtmoncar".$li_filascargos." type=text id=txtmoncar".$li_filascargos." class=sin-borde size=20 style= text-align:center><input name=formula".$li_filascargos." type=hidden id=formula".$li_filascargos.">";
	$la_objectcargos[$li_filascargos][4]="<input name=txtvacio".$li_filascargos." type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	}
  
}
elseif($ls_operacion=="CARGARCARGOS")
{
	$la_cargos=$_SESSION["cargos"];
	$li_totrowspg=$io_fun_sob->uf_obtenervalor("totrowspg",0);
	$io_ds_cargos->data=$_SESSION["cargos"];
	$io_ds_cargos->group_by(array('0'=>'codestpro','1'=>'estcla','2'=>'spgcuenta'),array('0'=>'monimp'),'monimp');
	$li_totcargos=$io_ds_cargos->getRowCount('codestpro');	
	$li_len1=0;
	$li_len2=0;
	$li_len3=0;
	$li_len4=0;
	$li_len5=0;
	$ls_titulo="";
	$lb_valido=$io_fun_sob->uf_loadmodalidad(&$li_len1,&$li_len2,&$li_len3,&$li_len4,&$li_len5,&$ls_titulo);
	$li_totrow=0;
	
	$li_totrowspg=$li_filaspartidas;
	$ls_basimpasi=0;
	for($li_i=1;$li_i<=$li_totcargos;$li_i++)
	{
			$ls_codpro=$io_ds_cargos->getValue("codestpro",$li_i);
			$ls_estcla=$io_ds_cargos->getValue("estcla",$li_i);
			$ls_cuenta=$io_ds_cargos->getValue("spgcuenta",$li_i);
			$li_moncue=$io_ds_cargos->getValue("monimp",$li_i);
			$ls_codestpro1=substr($ls_codpro,0,25);
			$ls_codestpro2=substr($ls_codpro,25,25);
			$ls_codestpro3=substr($ls_codpro,50,25);
			$ls_codestpro4=substr($ls_codpro,75,25);
			$ls_codestpro5=substr($ls_codpro,100,25);
			$ls_codestpro1=substr($ls_codestpro1,(25-$li_len1),$li_len1);
			$ls_codestpro2=substr($ls_codestpro2,(25-$li_len2),$li_len2);
			$ls_codestpro3=substr($ls_codestpro3,(25-$li_len3),$li_len3);
			$ls_codestpro4=substr($ls_codestpro4,(25-$li_len4),$li_len4);
			$ls_codestpro5=substr($ls_codestpro5,(25-$li_len5),$li_len5);
			$ls_codpro=	$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;	
			$ls_basimpasi=$ls_basimpasi+$li_moncue;
			switch($ls_estcla)
			{
				case "A":
					$ls_estcla=utf8_encode("ACCION");
					break;
				case "P":
					$ls_estcla=utf8_encode("PROYECTO");
					break;
			}
			
			$la_objectcuentas[$li_totrowspg][1]="<input name=txtcodcue".$li_totrowspg." type=text id=txtcodcue".$li_totrowspg." class=sin-borde style= text-align:center size=40 value='".$ls_codpro."' readonly>".
											    "<input name=codest1".$li_totrowspg." type=hidden id=codest1".$li_totrowspg." value='".$ls_codestpro1."'>".
												"<input name=codest2".$li_totrowspg." type=hidden id=codest2".$li_totrowspg." value='".$ls_codestpro2."'>".
												"<input name=codest3".$li_totrowspg." type=hidden id=codest3".$li_totrowspg." value='".$ls_codestpro3."'>".
												"<input name=codest4".$li_totrowspg." type=hidden id=codest4".$li_totrowspg." value='".$ls_codestpro4."'>".
												"<input name=codest5".$li_totrowspg." type=hidden id=codest5".$li_totrowspg." value='".$ls_codestpro5."'>".
												"<input name=estcar".$li_totrowspg." type=hidden id=estcar".$li_totrowspg." value='C'>";
			$la_objectcuentas[$li_totrowspg][2]="<input name=txtestcla".$li_totrowspg." type=text id=txtestcla".$li_totrowspg." class=sin-borde style= text-align:center size=20 value='".$ls_estcla."' readonly>";
			$la_objectcuentas[$li_totrowspg][3]="<input name=txtnomcue".$li_totrowspg." type=text id=txtnomcue".$li_totrowspg." class=sin-borde style= text-align:left size=10 value='".$ls_cuenta."' readonly >";
			$la_objectcuentas[$li_totrowspg][4]="<input name=txtmoncue".$li_totrowspg." type=text id=txtmoncue".$li_totrowspg." class=sin-borde size=20 style= text-align:center readonly value='".$io_funcsob->uf_convertir_numerocadena($li_moncue)."' onKeyPress=return(currencyFormat(this,'.',',',event)) onBlur=javascript:ue_validardispo()><input name=disponible".$li_totrowspg." type=hidden id=disponible".$li_totrowspg." value='".$ls_disponible."'>";
			$la_objectcuentas[$li_totrowspg][5]="";
			$li_totrowspg++;
	}
	$li_filascuentas=$li_totrowspg;
	$la_objectcuentas[$li_filascuentas][1]="<input name=txtcodcue".$li_filascuentas." type=text id=txtcodcue".$li_filascuentas." class=sin-borde style= text-align:center size=40 readonly><input name=codest1".$li_filascuentas." type=hidden id=codest1".$li_filascuentas."><input name=codest2".$li_filascuentas." type=hidden id=codest2".$li_filascuentas."><input name=codest3".$li_filascuentas." type=hidden id=codest3".$li_filascuentas."><input name=codest4".$li_filascuentas." type=hidden id=codest4".$li_filascuentas."><input name=codest5".$li_filascuentas." type=hidden id=codest5".$li_filascuentas.">";
   	$la_objectcuentas[$li_filascuentas][2]="<input name=txtestcla".$li_filascuentas." type=text id=txtestcla".$li_filascuentas." class=sin-borde style= text-align:center size=20 readonly>";
	$la_objectcuentas[$li_filascuentas][3]="<input name=txtnomcue".$li_filascuentas." type=text id=txtnomcue".$li_filascuentas." class=sin-borde style= text-align:left size=10 readonly>";
	$la_objectcuentas[$li_filascuentas][4]="<input name=txtmoncue".$li_filascuentas." type=text id=txtmoncue".$li_filascuentas." class=sin-borde size=20 style= text-align:center readonly><input name=disponible".$li_filascuentas." type=hidden id=disponible".$li_filascuentas." value='".$ls_disponible."'>";
	$la_objectcuentas[$li_filascuentas][5]="<input name=txtvacio".$li_filascuentas." type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
}
$ls_montotasi=$ls_monparasi+$ls_basimpasi;
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
  <form name="formulario" method="post" action="">
  <?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_sob->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_sob);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	
  <input name="hidestobr" type="hidden" id="hidestobr" value="<?php print $ls_estobr ?>">
  <input name="hidmonobr" type="hidden" id="hidstaobr" value="<?php print $ls_monobr ?>">
  <input name="hidmonasi" type="hidden" id="hidmonasi" value="<?php print $ls_monasi ?>">
  <input name="hidresmonasi" type="hidden" id="hidresmonasi" value="<?php print $ls_resmonasi ?>">
  
  <table width="780" border="0" align="center" cellpadding="0" cellspacing="0"  class="contorno">
      <tr class="formato-blanco">
        <th colspan="9" class="titulo-celdanew" scope="col">Obra a Asignar </th>
      </tr>
      <tr class="formato-blanco">
        <th colspan="9" scope="col"></th>
      </tr>
       <tr class="formato-blanco">
         <td height="22">&nbsp;</td>
         <td height="22">&nbsp;</td>
         <td colspan="7">&nbsp;</td>
       </tr>
       <tr class="formato-blanco">
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right">Obra:</div></td>
        <td colspan="7"><input name="txtcodobrasi" type="text" id="txtcodobrasi" style="text-align:center " value="<?php print $ls_codobrasi ?>" size="6" maxlength="6" readonly="true">
        <a href="javascript:ue_catobra();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
        <input name="txtnomobrasi" type="text" id="txtnomobrasi"  style="text-align:left" class="sin-borde" value="<?php print $ls_nomobrasi ?>" size="70" maxlength="100" readonly="true">          </td>
      </tr>
      <tr class="formato-blanco">
      <td height="13" colspan="10" align="center" valign="top" class="sin-borde"><div align="right"><a href="javascript:ue_uf_mostrar_ocultar_obra();">&nbsp;&nbsp;<img src="../shared/imagebank/mas.gif" width="9" height="17" border="0"></a><a href="javascript:uf_mostrar_ocultar_obra();">Datos de la Obra&nbsp;&nbsp;&nbsp;</a>
				   </div></td>
    </tr>
	   <?
	    if($ls_opemostrar=="MOSTRAR")
		 { 
		  $ls_codobr=$_POST["txtcodobrasi"];
          $io_asignacion->uf_select_obra($ls_codobr,&$la_obra);
          $io_asignacion->uf_select_montoasignado($ls_codobr,&$ld_monasi);
		  $ls_staobr=$io_funcsob->uf_convertir_numeroestado ($la_obra["staobr"][1]);
		  $ls_monobr=$io_funcsob->uf_convertir_numerocadena($la_obra["monto"][1]);
		  $ls_montasi=$io_funcsob->uf_convertir_numerocadena($ld_monasi);
		  $ls_resmontasi=$io_funcsob->uf_convertir_numerocadena($la_obra["monto"][1]-$ld_monasi);
		  $ls_feciniobr=$io_function->uf_convertirfecmostrar($la_obra["feciniobr"][1]);
		  $ls_fecfinobr=$io_function->uf_convertirfecmostrar($la_obra["fecfinobr"][1]);
	    ?>
      <tr class="formato-blanco">
        <td height="13" colspan="10" align="center" valign="top" class="sin-borde"><table width="469" height="137" border="0" cellpadding="0" cellspacing="4" class="formato-blanco">
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>Estatus:</td>
            <td><span class="style6"><input name="txtestobr" type="text" class="celdas-grises" id="txtestobr"  style="text-align:left" value="<?php print $ls_staobr ?>" size="20" maxlength="20" readonly="true">
            </span></td></tr>
          <tr>
            <td width="121"><span class="style6">Monto Total</span></td>
            <td width="138"><span class="style6">
              <input name="txtmontotobr" type="text" id="txtmontotobr"  style="text-align: right" value="<?php print $ls_monobr ?>" size="20" maxlength="20"  readonly="true">
            </span></td>
            <td width="70"><span class="center">Resta  Asignar </span></td>
            <td width="118"><span class="style6">
              <input name="txtresmonasi" type="text" id="txtresmonasi"  style="text-align: right" value="<?php print $ls_resmontasi ?>" size="20" maxlength="20"  readonly="true">
            </span></td>
          </tr>
          <tr>
            <td><span class="style6">Monto Asignado</span></td>
            <td><span class="style6">
              <input name="txtmontotasi" type="text" id="txtmontotasi"  style="text-align:right" value="<?php print $ls_montasi ?>" size="20" maxlength="20" readonly="true">
            </span></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>Fecha Inicio</td>
            <td><span class="style6">
              <input name="txtfeciniobr" type="text" id="txtfeciniobr"  style="text-align:left" value="<?php print $ls_feciniobr ?>" size="20" maxlength="20" readonly="true">
            </span></td>
            <td><span class="style6">Fecha Fin</span></td>
            <td><span class="style6">
              <input name="txtfecfinobr" type="text" id="txtfecfinobr"  style="text-align:left" value="<?php print $ls_fecfinobr ?>" size="20" maxlength="20" readonly="true">
            </span></td>
          </tr>
          <tr>
            <td><span class="style6">Estado</span></td>
            <td><span class="style6">
              <input name="txtestobr" type="text" id="txtestobr"  style="text-align:left" value="<?php print $la_obra["desest"][1] ?>" size="20" maxlength="20" readonly="true">
            </span></td>
            <td>Municipio</td>
            <td><span class="style6">
              <input name="txtmunobr" type="text" id="txtmunobr"  style="text-align:left" value="<?php print $la_obra["denmun"][1] ?>" size="20" maxlength="20" readonly="true">
            </span></td>
          </tr>
          <tr>
            <td>Parroquia</td>
            <td><span class="style6">
              <input name="txtparobr" type="text" id="txtparobr"  style="text-align:left" value="<?php print $la_obra["denpar"][1] ?>" size="20" maxlength="20" readonly="true">
            </span></td>
            <td>Comunidad</td>
            <td><span class="style6">
              <input name="txtnomcom" type="text" id="txtnomcom"  style="text-align:left" value="<?php print $la_obra["nomcom"][1] ?>" size="20" maxlength="20" readonly="true">
            </span></td>
          </tr>
        </table></td>
    </tr>
	   <?
		}
	   ?>
      <tr class="formato-blanco">
        <td height="13" colspan="10" align="center" valign="top" class="sin-borde">&nbsp;</td>
      </tr>
	  <tr class="titulo-celdanew">
        <th colspan="9" scope="col" class="titulo-celdanew">Asignaci&oacute;n</th>
    </tr>
      <tr class="formato-blanco">
        <td  colspan="9"><input name="operacion" type="hidden" id="operacion">
		<input name="opemostrar" type="hidden" id="opemostrar" value="<?php print $ls_opemostrar ?>">        <input name="hidestapr" type="hidden" id="hidestapr" value="<?php print $ls_estapr; ?>"></td>
      </tr>
      <tr class="formato-blanco">
        <td height="22">&nbsp;</td>
        <td>&nbsp;</td>
        <td width="143">&nbsp;</td>
        <td colspan="3">&nbsp;</td>
        <td width="135">&nbsp;</td>
        <td width="86"><div align="right">Estado</div></td>
        <td width="135"><span class="style6">
          <input name="txtestasi" type="text" class="celdas-grises" id="txtestasi"  style="text-align: center" value="<?php print $ls_estadoasi ?>" size="15" maxlength="15" readonly="true">
        </span></td>
      </tr>
      <tr class="formato-blanco">
        <td width="18" height="22"><div align="right"></div></td>
        <td width="102"><div align="right">C&oacute;digo</div></td>
        <td><input name="txtcodasi" type="text" id="txtcodasi" style="text-align:center " value="<?php print $ls_codasi ?>" size="6" maxlength="6"  readonly="true" >          <div align="right"></div></td>
        <td colspan="3"><div align="right"></div></td>
        <td colspan="3"><input name="txtptocueasi" type="hidden" id="txtptocueasi" style="text-align:center " value="<?php print $ls_ptocueasi ?>" size="3" maxlength="3" readonly="true">
        <a href="javascript:ue_catpuntodecuenta();"></a></td>
      </tr>
       <tr class="formato-blanco">
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right">Contratista</div></td>
        <td colspan="7"><input name="txtcodproasi" type="text" id="txtcodproasi" style="text-align:center " value="<?php print $ls_codproasi ?>" size="10" maxlength="10" readonly="true">
          <a href="javascript:ue_catcontratista();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
        <input name="txtnomproasi" type="text" id="txtnomproasi"  style="text-align:left" class="sin-borde" value="<?php print $ls_nomproasi ?>" size="70" maxlength="100" readonly="true">
        <input name="tipconpro" type="hidden" id="tipconpro" value="<?php print $ls_tipconpro; ?>"></td></tr>
      <tr class="formato-blanco">
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right">Inspector(a) / Empresa Inspectora</div></td>
        <td colspan="7"><input name="txtcodinsasi" type="text" id="txtcodinsasi" style="text-align:center " value="<?php print $ls_codinsasi ?>" size="10" maxlength="10" readonly="true">
          <a href="javascript:ue_catinspectora();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
          <input name="txtnominsasi" type="text" id="txtnominsasi"  style="text-align:left" class="sin-borde" value="<?php print $ls_nominsasi ?>" size="70" maxlength="100" readonly="true"></td>
      </tr>
      <tr class="formato-blanco">
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right">Fecha</div></td>
        <td colspan="7"><input name="txtfecasi" type="text" id="txtfecasi"  style="text-align:left" value="<?php print $ls_fecasi ?>" size="11" maxlength="10"   onKeyPress="javascript:ue_validafecha();" datepicker="true" readonly="true"></td>
      </tr>
      <tr class="formato-blanco">
        <td height="22"><div align="right"></div></td>
        <td height="22"><div align="right">Observacion</div></td>
        <td colspan="7"><textarea name="txtobsasi" cols="80" rows="2" id="txtobsasi" onKeyPress="return(validaCajas(this,'x',event))"  onKeyDown="textCounter(this,254)" ><?php print $ls_obsasi ?></textarea></td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="9">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="9"></td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="9"><div align="center">
          <table width="610" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr class="formato-blanco">
              <td width="15" height="13">&nbsp;</td>
              <td width="593"><div align="left"></div></td>
            </tr>
            <tr align="center" class="formato-blanco">
              <td colspan="2"><?php $io_grid->makegrid($li_filaspartidas,$la_columpartidas,$la_objectpartidas,$li_anchopartidas,$ls_titulopartidas,$ls_nametable);?>              </td>
            </tr>
            <input name="filaspartidas" type="hidden" id="filaspartidas" value="<?php print $li_filaspartidas;?>">
            <tr class="formato-blanco">
              <td colspan="2"><p class="Estilo2">Cant(O): Cantidad total de la Obra</p>
              <p class="Estilo2">Cant(A): Cantidad a Asignar </p></td>
            </tr>
          </table>
        </div></td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="9"><div align="center">
          <table width="609" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr class="formato-blanco">
              <td height="11">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr class="formato-blanco">
              <td width="14" height="11">&nbsp;</td>
              <td width="593"><a href="javascript:ue_catfuentefinanciamiento();"></a><a href="javascript:ue_catcuentagasto();">Agregar Detalle </a></td>
            </tr>
            <tr align="center" class="formato-blanco">
              <td height="11" colspan="2">
			  <?php $io_grid->makegrid($li_filascuentas,$la_columcuentas,$la_objectcuentas,$li_anchocuentas,$ls_titulocuentas,$ls_nametable);?>			  </td>
			  <input name="filascuentas" type="hidden" id="filascuentas" value="<?php print $li_filascuentas;?>">
				<input name="hidremovercuenta" type="hidden" id="hidremovercuenta" value="<?php print $li_removercuenta;?>">
            </tr>
            <tr class="formato-blanco">
              <td colspan="2">&nbsp;</td>
            </tr>
          </table>
        </div></td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="9">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td height="23">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td width="26">&nbsp;</td>
        <td width="34">&nbsp;</td>
        <td width="99">&nbsp;</td>
        <td>&nbsp;</td>
        <td><div align="right">Sub-Total</div></td>
        <td><input name="txtmonparasi" type="text" id="txtmonparasi"  style="text-align: right" value="<?php print number_format($ls_monparasi,2,',','.'); ?>" size="20" maxlength="20" readonly="true"></td>
      </tr>
      <tr class="formato-blanco">
        <td height="23">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><input name="totrowspg" type="hidden" id="totrowspg" value="<?php print $li_totrowspg; ?>"></td>
        <td><div align="right">
          <input name="btncreditos" type="button" class="boton" id="btncreditos" value="Otros Creditos" onClick="javascript: ue_otroscreditos();">
        </div></td>
        <td><input name="txtbasimpasi" type="text" id="txtbasimpasi"  style="text-align: right" value="<?php print number_format($ls_basimpasi,2,',','.'); ?>" size="20" maxlength="20" onKeyPress="return(currencyFormat(this,'.',',',event))" onBlur="uf_validarbasimp();"></td>
      </tr>
      <tr class="formato-blanco">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><div align="right">Total</div></td>
        <td><input name="txtmontotasi2" type="text" id="txtmontotasi2"  style="text-align: right" value="<?php print number_format($ls_montotasi,2,',','.'); ?>" size="20" maxlength="20"  readonly="true"></td>
      </tr>
      <tr class="formato-blanco">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
  <input name="hidstatus" type="hidden" id="hidstatus" value="<?php print $ls_chk ?>">
    <div align="center"></div>
  </form>
</body>
<script language="javascript">

/*******************************************CATALOGOS********************************************************************************************************/
function ue_catcontratista()
{
	f=document.formulario;
	f.operacion.value="";			
	pagina="sigesp_cat_contratista.php";
	popupWin(pagina,"catalogo",580,700);
	//window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=200,resizable=yes,location=no");
}

function ue_catinspectora()
{
	f=document.formulario;
	f.operacion.value="";			
	pagina="sigesp_cat_inspectora.php";
	popupWin(pagina,"catalogo",530,700);
	//window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=200,resizable=yes,location=no");
}

function a()
{
	f=document.formulario;
	f.operacion.value="";			
	pagina="sigesp_sob_pdt_otroscreditos.php";
	popupWin(pagina,"catalogo",530,700);
	//window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=200,resizable=yes,location=no");
}

function ue_catobra()
{
	f=document.formulario;
	f.operacion.value="";			
	pagina="sigesp_cat_obra.php?estado=''";
	popupWin(pagina,"catalogo",850,450);
	//window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=200,resizable=yes,location=no");
}


function ue_catcuentagasto()
{
	f=document.formulario;
	f.operacion.value="";			
	pagina="sigesp_sob_pdt_spgcuentas.php";
	popupWin(pagina,"catalogo",650,250);
	//window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=200,resizable=yes,location=no,status=yes");
}

function ue_catcargos()
{
	f=document.formulario;
	f.operacion.value="";
	if((f.txtbasimpasi.value=="")||(f.txtbasimpasi.value=="0,00"))
	{
	 alert("Debe indicar la Base imponible a la cual se le aplicaran los Cargos!!")
	}
	else
	{			
	pagina="sigesp_sob_cat_cargos.php";
	popupWin(pagina,"catalogo",650,400);
	//window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=200,resizable=yes,location=no,status=yes");
	}
}

function ue_catpuntodecuenta()
{
	pagina="sigesp_cat_puntodecuenta.php";
	popupWin(pagina,"catalogo",750,350);
}

function ue_buscar()
{
  f=document.formulario;
  li_leer=f.leer.value;
  if(li_leer==1)
   {
     f.operacion.value="";
     //pagina="sigesp_cat_asignacion.php?estado="+"&origen=DA";
	 pagina="sigesp_cat_asignacion.php?hidorigen=DA&estado=";
     popupWin(pagina,"catalogo",850,450);
	 //window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=350,resizable=yes,location=no");
   }
	else
	{
	  alert("No tiene permiso para realizar esta operacion");
	}
} 

/*************************************************************************************************************************************************/

/*******************************************CARGAR Y REMOVER DATOS********************************************************************************************************/
function ue_cargarcontratista(codigo,nombre,representante,tipconpro)
{
	f=document.formulario;
	ins=f.txtcodinsasi.value;
	if(ins==codigo)
	{
	 alert("El Inspector no puede ser Contratista de la Obra");
	}
	else
	{
	  f.txtcodproasi.value=codigo;
	  f.txtnomproasi.value=nombre;
	  f.tipconpro.value=tipconpro;
	}
}
function ue_cargarinspectora(codins,nomins)
{
	f=document.formulario;
	contr=f.txtcodproasi.value;
	if(contr==codins)
	{
	 alert("El Contratista no puede ser Inspector de la Obra");
	}
	else
	{
	 f.txtcodinsasi.value=codins;
	 f.txtnominsasi.value=nomins;
	}
}

function ue_cargarasignacion(ls_codasi,ls_codobr,ls_codpro,ls_codproins,ld_puncue,ls_obsasi,ls_monparasi,ls_basimpasi,ls_montotasi,ls_estasi,ls_desobr,
							 ls_nompro,ls_fecasi,ls_estapr)
//(ls_codasi,ls_codobr,ls_codpro,ls_codproins,ls_puncue,ls_obsasi,ls_monparasi,ls_basimpasi,ls_montotasi,ls_estasi,ls_desobr,ls_fecasi)
{
    f.txtcodasi.value=ls_codasi;
	f.txtcodobrasi.value=ls_codobr;
	f.txtcodproasi.value=ls_codpro;
	f.txtcodinsasi.value=ls_codproins;
	f.txtptocueasi.value=ld_puncue;
	f.txtnomproasi.value=ls_nompro;
	f.txtobsasi.value=ls_obsasi;
	f.txtmonparasi.value=uf_convertir(ls_monparasi);
	f.txtbasimpasi.value=uf_convertir(ls_basimpasi);
	f.txtmontotasi2.value=uf_convertir(ls_montotasi);
	f.txtestasi.value=ls_estasi;	
	f.txtnomobrasi.value=ls_desobr;
	f.txtfecasi.value=ls_fecasi;
	f.hidestapr.value=ls_estapr;
	f.hidstatus.value="C";
	f.operacion.value="ue_cargarasignacion";
	f.action="sigesp_sob_d_asignacion.php";

	f.submit();
}

function ue_cargarpuntodecuenta(ls_codobr,ls_codpuncue,ls_rempuncue,ls_despuncue,ls_asupuncue,ls_fecpuncue,ls_codpropuncue,
								ls_nompropuncue,ls_replegpuncue,ls_lapejepuncue,ls_lapejeunipuncue,ls_monnetpuncue,ls_monivapuncue,
								ls_porivapuncue,ls_monantpuncue,ls_porantpuncue,ls_obspuncue,ls_monbrupuncue)
{
	f=document.formulario;
	f.txtptocueasi.value=ls_codpuncue;
	f.operacion.value="ue_cargarptocuenta";
	f.action="sigesp_sob_d_asignacion.php";
	f.submit();
}


function ue_cargarobra(ls_codigo,ls_descripcion,ls_estado,ls_codest,ls_codten,ls_codtipest,ls_codpai,ls_codmun,ls_codpar,ls_codcom,
  				         ls_codsiscon,ls_codpro,ls_codtob,ls_dirobr,ls_obsobr,ls_resobr,ld_monto,ls_feccreobr,ls_nompro,
				         ls_fechainicio,ls_fechafin,ls_nomten,ls_nomtipest,ls_nomsiscon,ls_nomtob)
{
	f=document.formulario;
	f.txtcodobrasi.value=ls_codigo;
	f.txtnomobrasi.value=ls_descripcion;
	f.hidmonobr.value=ld_monto;
	f.operacion.value="ue_actulizarmontoasi";
	f.submit();
}

function ue_cargarcuenta(codcuenta,nomcuenta,codest1,codest2,codest3,codest4,codest5,dispo,estcla)
{
	f=document.formulario;
	f.operacion.value="ue_cargarcuenta";	
	lb_existe=false;
	
	for(li_i=1;li_i<=f.filascuentas.value && !lb_existe;li_i++)
	{
		codpre=codest1+codest2+codest3+codest4+codest5;
		ls_codigo=eval("f.txtcodcue"+li_i+".value");
		ls_nombre=eval("f.txtnomcue"+li_i+".value");
		if((ls_nombre==codcuenta)&&(ls_codigo==codpre))
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
}

function ue_removercuenta(li_fila)
{
	f=document.formulario;
	li_cargos=f.txtbasimpasi.value;
	li_cargos = li_cargos.replace(",",".");
	li_cargos = parseFloat(li_cargos);
	if(li_cargos>0)
	{
		alert("Debe eliminar los cargos antes de eliminar una cuenta presupuestaria");	
	}
	else
	{
		f.hidremovercuenta.value=li_fila;
		f.operacion.value="ue_removercuenta"
		f.action="sigesp_sob_d_asignacion.php";
		f.submit();
	}
}

function ue_cargarcargo(cod,nom,formula,progra,prograaux,estcla,spg_cu)
{
	f=document.formulario;
	f.operacion.value="ue_cargarcargo";	
	lb_existe=false;
	
	for(li_i=1;li_i<=f.filascargos.value && !lb_existe;li_i++)
	{
		ls_codigo=eval("f.txtcodcar"+li_i+".value");
		if(ls_codigo==cod)
		{
			alert("La Cuenta ya ha sido cargada!!!");
			lb_existe=true;
		}
	}	
    lb_repeat = false;
	li_totrowpre = f.filascuentas.value;
	for (li_i=1;li_i<li_totrowpre;li_i++)
    {
		ls_codestpro= eval("f.txtcodcue"+li_i+".value");
		ls_spgcta= eval("f.txtnomcue"+li_i+".value");
		ls_estcla= eval("f.txtestcla"+li_i+".value");
	    if ((ls_spgcta==spg_cu) && (ls_codestpro==prograaux)&& (ls_estcla==estcla))
		{
			lb_repeat = true;
			li_filpre = li_i;
			break;
		} 
	}	
	ld_basimp = f.txtbasimpasi.value;
	while (ld_basimp.indexOf(".")!=-1)
	{ 
		ld_basimp=ld_basimp.replace(".","");
	}
	ld_basimp=ld_basimp.replace(",","."); 
	ld_basimp = parseFloat(ld_basimp);
	while(formula.indexOf("$LD_MONTO")!=-1)
	{ 
		formula=formula.replace("$LD_MONTO",ld_basimp);
	}
	ld_result    = eval(formula);
	if (lb_repeat)
	{
	     
		ld_monspgcta = eval("f.txtmoncue"+li_filpre+".value");
		while (ld_monspgcta.indexOf(".")!=-1)
		{ 
			ld_monspgcta=ld_monspgcta.replace(".","");
	  	}
		ld_monspgcta = ld_monspgcta.replace(",",".");
		ld_monspgcta = parseFloat(ld_monspgcta);
	    ld_montotpre = parseFloat(ld_monspgcta)+parseFloat(ld_result);
		ld_montotpre=uf_convertir(ld_montotpre);
		//eval("f.txtmoncue"+li_filpre+".value="+ld_montotpre+"")
		obj=eval("f.txtmoncue"+li_filpre+"");
		obj.value=ld_montotpre;
	}
	else
	{
		ls_codestpro1=progra.substr(0,25);
		ls_codestpro2=progra.substr(25,25);
		ls_codestpro3=progra.substr(50,25);
		ls_codestpro4=progra.substr(75,25);
		ls_codestpro5=progra.substr(100,25);
		ld_resulaux=uf_convertir(ld_result);
		eval("f.txtcodcue"+li_totrowpre+".value='"+prograaux+"'");
		eval("f.txtnomcue"+li_totrowpre+".value='"+spg_cu+"'");
		eval("f.txtestcla"+li_totrowpre+".value='"+estcla+"'");
		eval("f.codest1"+li_totrowpre+".value='"+ls_codestpro1+"'");
		eval("f.codest2"+li_totrowpre+".value='"+ls_codestpro2+"'");
		eval("f.codest3"+li_totrowpre+".value='"+ls_codestpro3+"'");
		eval("f.codest4"+li_totrowpre+".value='"+ls_codestpro4+"'");
		eval("f.codest5"+li_totrowpre+".value='"+ls_codestpro5+"'");
		obj=eval("f.txtmoncue"+li_totrowpre+"");
		obj.value=ld_resulaux;
	}
	
	
	if(!lb_existe)
	{
		eval("f.txtcodcar"+f.filascargos.value+".value='"+cod+"'");
		eval("f.txtnomcar"+f.filascargos.value+".value='"+nom+"'");
		//eval("f.txtmoncar"+f.filascargos.value+".value='"+ld_moncar+"'");
		eval("f.formula"+f.filascargos.value+".value='"+formula+"'");
		eval("f.prog"+f.filascargos.value+".value='"+progra+"'");
		eval("f.spgcuenta"+f.filascargos.value+".value='"+spg_cu+"'");
		ld_total=f.txtmontotasi.value;
		while (ld_total.indexOf(".")!=-1)
		{ 
			ld_total=ld_total.replace(".","");
		}
		ld_total=ld_total.replace(",","."); 
		ld_total = parseFloat(ld_total);
		ld_totalcont=ld_total + ld_result;
		ld_totalcont=uf_convertir(ld_totalcont);
		f.txtmontotasi.value=ld_totalcont;
		
		ld_result=uf_convertir(ld_result);
		obj=eval("f.txtmoncar"+f.filascargos.value+"");
		obj.value=ld_result;
	}
	f.operacion.value="ue_agregarcargo"
	f.action="sigesp_sob_d_asignacion.php";
  	f.submit();

}

function ue_removercargo(li_fila)
{
	f=document.formulario;
	f.hidremovercargo.value=li_fila;
	f.operacion.value="ue_removercargo"
	f.action="sigesp_sob_d_asignacion.php";
	f.submit();
}

function ue_marcartodo(li_fila)
{
	f=document.formulario;
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
/*************************************************************************************************************************************************/

/*********************************GENERAR NUEVO***********************************************************************************************************/
function ue_nuevo()
		{
		  f=document.formulario;
	      li_incluir=f.incluir.value;
	      if(li_incluir==1)
	       {		
		     f.operacion.value="ue_nuevo";
		     f.txtptocueasi.value="";
	         f.txtcodproasi.value="";
	         f.txtnomproasi.value="";
	         f.txtcodinsasi.value="";
	         f.txtnominsasi.value="";
	         f.txtfecasi.value="";
	         f.txtobsasi.value="";
		     f.action="sigesp_sob_d_asignacion.php";
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
	f=document.formulario;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_status=f.hidstatus.value;
	ls_estapr=f.hidestapr.value;
	if(ls_estapr!="1")
	{
		if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
		{
		 if (ue_valida_null(f.txtcodobrasi,"Obra")==false)
		  {
		   f.txtcodobrasi.focus();
		  }
		 else
		 {
		  if (ue_valida_null(f.txtcodasi,"Código")==false)
		   {
			 f.txtcodasi.focus();
		   }
		   else
		   {
			if (ue_valida_null(f.txtcodproasi,"Código del Contratista")==false)
			 {
			   f.txtcodproasi.focus();
			 }
			 else
			 {
			  if (ue_valida_null(f.txtcodinsasi,"Código del Inspector")==false)
			   {
				 f.txtcodinsasi.focus();
			   }
			   else
			   {
				if (ue_valida_null(f.txtcodinsasi,"Código")==false)
				 {
				  f.txtcodinsasi.focus();
				 }
				 else
				 {
				  if (ue_valida_null(f.txtfecasi,"Fecha")==false)
				   {
					f.txtfecasi.focus();
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
						totalgen=f.txtmontotasi2.value;
						totalgen = totalgen.replace(",",".");
						totalgen = parseFloat(totalgen);
						if(totaldet==totalgen)
						{
							f.filaspartidas.value=filas;
							f.action="sigesp_sob_d_asignacion.php";
							f.operacion.value="ue_guardar";
							f.submit();
						}
						else
						{
							alert("El total de las cuentas presupuestarias difiere del total general");
						}
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
		alert("La asignacion esta aprobada. No se puede modificar");
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
	if ((fld.id != "txtbasimpasi")||(fld.id != "txtmoncue"))
	  {
    	txt=fld.id.charAt(3);
		if(txt!="p")
		{
		 if(txt!="m")
		  {
		  // ue_subtotal();
		  }
		} 
	  }	
    return false; 
   } 
/*************************************************************************************************************************************************/

/************************************************************************************************************************************************/
 
function ue_subtotal()
{
	f=document.formulario;
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
			}
			else
			{
				ld_cantpar=uf_convertir_monto(eval("f.txtcantpar"+li_i+".value"));
			}
			ld_canpar=parseFloat(ld_cantpar);
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
				alert("La cantidad que se está Asignando, supera la cantidad establecida en la Obra!!");
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
		else
		{
			if((eval("f.txtcantpar"+li_i+".value")!=""))
			{
				if((eval("f.txtcantpar"+li_i+".value")!="0,00"))
				{
					alert("Debe seleccionar la partida antes de colocarle cantidad.");
					eval("f.txtcantpar"+li_i+".value=''");
				}
			}
			else
			{
				eval("f.txttotal"+li_i+".value=''");
			}
		}
	}	
	f.txtmonparasi.value=uf_convertir(ld_subtotal);
//	f.txtbasimpasi.value=uf_convertir(ld_subtotal);
	f.txtmontotasi.value=uf_convertir(ld_subtotal);
}

function uf_validarbasimp() 
 { 
   f=document.formulario;
   if((f.txtmonparasi.value=="")||(f.txtmonparasi.value=="0,00"))
   {
     alert("Debe calcular el Monto a Asignar!!");
   }
   else
   {
     ld_montoasi=parseFloat(uf_convertir_monto(f.txtmonparasi.value));
     if(f.txtbasimpasi.value=="")
      {
        ld_basimp=0;
      }
      else
      {
        ld_basimp=parseFloat(uf_convertir_monto(f.txtbasimpasi.value));
      }

      if(ld_basimp>ld_montoasi)
      {
       alert("la Base Imponible supera al Sub Total de la Asignacion!!");
	   f.txtbasimpasi.value="0,00";
	  }
   }
 } 
  
function ue_validardispo()
{
	f=document.formulario;
	valido=true;
	ld_montotasi=uf_convertir_monto(f.txtmontotasi.value);
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
			valido=false;
			alert("El monto asignado a la cuenta es mayor que su Disponibilidad!!");
			eval("f.txtmoncue"+li_i+".value=''")
		}
		ld_montotcue=ld_montotcue+ld_monto;
	}	
    //alert("monto total asignado->"+ld_montotasi+" Totales cuentas->"+ld_montotcue);
	if(ld_montotasi<ld_montotcue)
	{
		valido=false;
		alert("El monto asignado a las cuentas sobre pasa el total Asignado");
		eval("f.txtmoncue"+li_i+".value=''")
	}
	/*if(valido)
	{
		f.submit();
	}*/
}

function ue_validarmontocuentas()
{
	f=document.formulario;
	ld_monparasi=uf_convertir_monto(f.txtmontotasi2.value);
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
	 alert("Debe asignar al menos una cuenta de gastos a la Asigancion!!");
	}
	else
	{
	   lb_flag=true;
	/*if(ld_monparasi==ld_montotcue)
	{
	   lb_flag=true;
	}
	else
	{
	  alert("El monto asignado a las Cuentas debe coincidir con el sub total Asignado!!");
	}*/
	}
	return lb_flag;	
}
/************************************************************************************************************************************************/
function uf_mostrar_ocultar_obra()  
{
	f=document.formulario;
	if (f.txtcodobrasi.value=="")
	{
		alert("Debe seleccionar la Obra a Asignar!!");
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
	f=document.formulario;
	li_eliminar=f.eliminar.value;
	ls_estapr=f.hidestapr.value;
	if(ls_estapr!="1")
	{
		if(li_eliminar==1)
		{	
		  if (f.txtcodasi.value=="")
		  {
			alert("Debe seleccionar la Asigancion a Anular!!");
		  }
		  else
		  {
			si=confirm("Esta seguro?");
			 if(si)
			 {
				f.action="sigesp_sob_d_asignacion.php";
				f.operacion.value="ue_anular";
				f.submit();
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
		alert("La asignacion esta aprobada. No se puede modificar");
	}
	
}

function ue_verificarexistencia()
{
	f=document.formulario;
	f.operacion.value="ue_verificarexistencia";
	f.submit();
}

function ue_otroscreditos()
{
	f = document.formulario;
	codobr=f.txtcodobrasi.value;
	codasi=f.txtcodasi.value;
	subtotal=ue_formato_calculo(f.txtmonparasi.value);
	tipocontribuyente=f.tipconpro.value;
	totrowspg=ue_calcular_total_fila_local("txtcodcue");
	f.totrowspg.value=totrowspg;
	estaprord=f.hidestapr.value;
	if (estaprord!='1') // No está Aprobada
	{
		if(tipocontribuyente!="F") // No es un Contribuyente formal
		{
			if((codobr=="")||(parseFloat(subtotal)<=0)||(codasi==""))
			{
				alert("Debe Seleccionar una obra, numero de asignacion \n y el Monto del Subtotal Debe ser Mayor que Cero");
			}
			else
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
				totalpar=f.txtmonparasi.value;
				totalpar = totalpar.replace(",",".");
				totalpar = parseFloat(totalpar);
				if(totaldet==totalpar)
				{
					pagina="sigesp_sob_pdt_otroscreditos.php?codobr="+codobr+"&subtotal="+subtotal+"&codasi="+codasi+"";
					window.open(pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=300,left=50,top=50,resizable=yes,location=no");
				}
				else
				{
					alert("Debe cargar las cuentas presupuestarias con sus respectivos montos para cuadrar la base imponible");
				}
			}
		}
		else
		{
			alert("El contribuyente es formal no se le aplican Otros Créditos");
		}
	}
	else
	{
		alert("La asignacion esta aprobada. No se puede modificar");
	}
}	

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>