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
	$io_fun_sob->uf_load_seguridad("SOB","sigesp_sob_d_obra.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	function uf_limpiar_variables()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiar_variables
		//		   Access: private
		//	  Description: Destructor de la Clase
		//	   Creado Por: 
		// Fecha Creación: 29/06/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_codobr,$ls_nomobr,$ls_resobr,$ls_codtob,$ls_codsiscon,$ls_feciniobr,$ls_fecfinobr,$ls_dirobr,$ls_staobr,$li_monto,$ls_codest;
		global $ls_codmun,$ls_codpar,$ls_codcom,$ls_nomsiscon,$ls_nomtob,$ls_nomtipest,$ls_nompro,$ls_codpro,$ls_obsobr,$ls_codten,$ls_totalfuente,$li_filaspartidas;
		global $ls_hidstatus;
		
		$ls_codobr="";
		$ls_nomobr="";	    
		$ls_resobr="";	
		$ls_codtob="";	
		$ls_codsiscon="";	
		$ls_codtipest="";
		$ls_feciniobr="";	
		$ls_fecfinobr="";  
		$ls_dirobr="";
		$ls_staobr="EMITIDO";	
		$li_monto="0,00";	
		$ls_codest="";  	
		$ls_codmun="";     
		$ls_codpar="";	
		$ls_codcom="";	
		$ls_nomsiscon="";	
		$ls_nomtob="";
		$ls_nomtipest="";	
		$ls_nompro="";
		$ls_codpro="";
		$ls_obsobr="";		
		$ls_codten="";
		$ls_totalfuente="0,00";
		$li_filaspartidas=1;
		$ls_hidstatus="";
	}
	$ls_reporte=$io_fun_sob->uf_select_config("SOB","REPORTE","FICHA_OBRA","sigesp_sob_rfs_fichaobra.php","C");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Deficici&oacute;n de Obras</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_sob.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
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
</style><meta http-equiv="Content-Type" content="text/html; charset=">
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<span class="toolbar"><a name="inicio"></a></span>

<?php
require_once("../shared/class_folder/class_funciones_db.php");
require_once ("../shared/class_folder/sigesp_include.php");		
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_funcdb=new class_funciones_db($io_connect);
require_once("../shared/class_folder/class_sql.php");
$io_sql=new class_sql($io_connect);
require_once("class_folder/sigesp_sob_class_obra.php");
$io_obra=new sigesp_sob_class_obra();
require_once("../shared/class_folder/grid_param.php");
$io_grid=new grid_param();
require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
require_once("../shared/class_folder/class_funciones.php");
$io_function=new class_funciones();
require_once("../shared/class_folder/class_datastore.php");
$io_datastore=new class_datastore();
require_once("class_folder/sigesp_sob_c_funciones_sob.php");
$io_funsob=new sigesp_sob_c_funciones_sob();
require_once("class_folder/sigesp_sob_class_mensajes.php");
$io_mensaje=new sigesp_sob_class_mensajes();

$ls_titulopartidas="Partidas Asignadas";
$li_anchopartidas=600;
$ls_nametable="grid";
$la_columpartidas[1]="Código";
$la_columpartidas[2]="Partida";
$la_columpartidas[3]="P.U.";
$la_columpartidas[4]="Cantidad";
$la_columpartidas[5]="U.M.";
$la_columpartidas[6]="Edición";

$ls_titulofuentes="Fuentes de Financiamiento";
$li_anchofuentes=600;
$ls_nametable="grid2";
$la_columfuentes[1]="Código";
$la_columfuentes[2]="Fuente de Financiamiento";
$la_columfuentes[3]="Monto";
$la_columfuentes[4]="Edición";

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$io_funsob->uf_obtenervalor("operacion","");
	$li_filaspartidas=$io_funsob->uf_obtenervalor("filaspartidas","");
	$li_filasfuentes=$io_funsob->uf_obtenervalor("filasfuentes","");
	
	////////Cargando nuevamente el objeto de las tablas///////////
	if ($ls_operacion != "ue_cargarpartida" && $ls_operacion != "ue_removerpartida")
	{
		for($li_i=1;$li_i<$li_filaspartidas;$li_i++)
		{
			$ls_codigo=$io_funsob->uf_obtenervalor("txtcodpar".$li_i,"");
			$ls_nombre=$io_funsob->uf_obtenervalor("txtnompar".$li_i,"");
			$ls_unidad=$io_funsob->uf_obtenervalor("txtnomuni".$li_i,"");
			$ls_prepar=$io_funsob->uf_obtenervalor("txtprepar".$li_i,"");
			$ls_canpar=$io_funsob->uf_obtenervalor("txtcanpar".$li_i,"");
/*			$ls_codigo=$_POST["txtcodpar".$li_i];
			$ls_nombre=$_POST["txtnompar".$li_i];
			$ls_unidad=$_POST["txtnomuni".$li_i];
			$ls_prepar=$_POST["txtprepar".$li_i];
			$ls_canpar=$_POST["txtcanpar".$li_i];
*/			$la_objectpartidas[$li_i][1]="<input name=txtcodpar".$li_i." type=text id=txtcodpar".$li_i." class=sin-borde style= text-align:center size=10 value='".$ls_codigo."' readonly>";
			$la_objectpartidas[$li_i][2]="<input name=txtnompar".$li_i." type=text id=txtnompar".$li_i." class=sin-borde style= text-align:left size=50 value='".$ls_nombre."' readonly >";
			$la_objectpartidas[$li_i][3]="<input name=txtprepar".$li_i." type=text id=txtprepar".$li_i." class=sin-borde style= text-align:right size=22 maxlength=22  value='".$ls_prepar."'>";
			$la_objectpartidas[$li_i][4]="<input name=txtcanpar".$li_i." type=text id=txtcanpar".$li_i." class=sin-borde style= text-align:right size=15 value='".$ls_canpar."' onKeyPress=return(validaCajas(this,'d',event,15))  onKeyUp=javascript:uf_llamada_validarmonto(this) onBlur=javascript:ue_getformat(this)  >";
			$la_objectpartidas[$li_i][5]="<input name=txtnomuni".$li_i." type=text id=txtnomuni".$li_i." class=sin-borde size=5 style= text-align:center value='".$ls_unidad."' readonly>";
			$la_objectpartidas[$li_i][6]="&nbsp;&nbsp;&nbsp;<a href=javascript:ue_removerpartida(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center onClick=javascript:valida_monto_total(this,".$li_i.")></a>";
		}	
		$la_objectpartidas[$li_filaspartidas][1]="<input name=txtcodpar".$li_filaspartidas." type=text id=txtcodpar".$li_filaspartidas." class=sin-borde style= text-align:center size=10 readonly >";
		$la_objectpartidas[$li_filaspartidas][2]="<input name=txtnompar".$li_filaspartidas." type=text id=txtnompar".$li_filaspartidas." class=sin-borde style= text-align:left size=50 readonly >";
		$la_objectpartidas[$li_filaspartidas][3]="<input name=txtprepar".$li_filaspartidas." type=text id=txtprepar".$li_filaspartidas." class=sin-borde style= text-align:right size=22 readonly >";
		$la_objectpartidas[$li_filaspartidas][4]="<input name=txtcanpar".$li_filaspartidas." type=text id=txtcanpar".$li_filaspartidas." class=sin-borde style= text-align:right size=15 readonly>";
		$la_objectpartidas[$li_filaspartidas][5]="<input name=txtnomuni".$li_filaspartidas." type=text id=txtnomuni".$li_filaspartidas." class=sin-borde size=5 style= text-align:center readonly>";
		$la_objectpartidas[$li_filaspartidas][6]="<input name=txtvacio".$li_filaspartidas." type=text id=txtvacio class=sin-borde style= text-align:center size=2 readonly>";
	}		

	if ($ls_operacion != "ue_cargarfuente" && $ls_operacion != "ue_removerfuente")
	{
		for($li_i=1;$li_i<$li_filasfuentes;$li_i++)
		{		
			$ls_codigo=$_POST["txtcodfuefin".$li_i];
			$ls_nombre=$_POST["txtnomfuefin".$li_i];
			$ls_monfuefin=$_POST["txtmonfuefin".$li_i];
			$la_objectfuentes[$li_i][1]="<input name=txtcodfuefin".$li_i." type=text id=txtcodfuefin".$li_i." class=sin-borde style= text-align:center size=5 value='".$ls_codigo."' readonly>";
			$la_objectfuentes[$li_i][2]="<input name=txtnomfuefin".$li_i." type=text id=txtcodfuefin".$li_i." class=sin-borde style= text-align:left size=60 value='".$ls_nombre."' readonly >";
			$la_objectfuentes[$li_i][3]="<input name=txtmonfuefin".$li_i." type=text id=txtmonfuefin".$li_i." class=sin-borde size=20 maxlength=21 style= text-align:right value='".$ls_monfuefin."' onKeyPress=return(validaCajas(this,'d',event,15))  onKeyUp=javascript:uf_llamada_validarmonto(this) onBlur=javascript:ue_getformat(this)>";
			$la_objectfuentes[$li_i][4]="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=javascript:ue_removerfuente(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
		}	
		$la_objectfuentes[$li_filasfuentes][1]="<input name=txtcodfuefin".$li_filasfuentes." type=text id=txtcodfuefin".$li_filasfuentes." class=sin-borde style= text-align:center size=5 readonly>";
		$la_objectfuentes[$li_filasfuentes][2]="<input name=txtnomfuefin".$li_filasfuentes." type=text id=txtnomfuefin".$li_filasfuentes." class=sin-borde style= text-align:left size=50 readonly>";
		$la_objectfuentes[$li_filasfuentes][3]="<input name=txtmonfuefin".$li_filasfuentes." type=text id=txtmonfuefin".$li_filasfuentes." class=sin-borde style= text-align:center size=20 readonly>";
		$la_objectfuentes[$li_filasfuentes][4]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	}
///////////////////////////////////////////////////////////////////////////////////////////////////	
}
else
{
	$ls_operacion="";
	uf_limpiar_variables();	
/*	$ls_staobr="";
	$ls_codobr="";
	$ls_nomobr="";	    
	$ls_codpro="";	    
	$ls_resobr="";	
	$ls_codtob="";
	$ls_codsiscon="";	
	$ls_codtipest="";	
	$ls_feciniobr="";	
	$ls_fecfinobr="";	
	$ls_dirobr="";	
	$li_monto="0,00";
	$ls_codest="";  	
	$ls_codmun="";  	
	$ls_codpar="";	
	$ls_codcom="";
	$ls_nomsiscon="";	
	$ls_nomtob="";  	
	$ls_nomtipest="";	
	$ls_nompro="";
	$ls_obsobr="";		
	$ls_codten="";
	$ls_feccreobr="";
	//$ls_codpai='001';
	$ls_totalfuente="0,00";
	$li_filaspartidas=1;
*/	$la_objectpartidas[1][1]="<input name=txtcodpar1 type=text id=txtcodpar1 class=sin-borde style= text-align:center size=10 readonly>";
	$la_objectpartidas[1][2]="<input name=txtnompar1 type=text id=txtnompar1 class=sin-borde style= text-align:left size=50 readonly>";
	$la_objectpartidas[1][3]="<input name=txtprepar1 type=text id=txtprepar1 class=sin-borde style= text-align:right size=22 readonly >";
	$la_objectpartidas[1][4]="<input name=txtcanpar1 type=text id=txtcanpar1 class=sin-borde style= text-align:right size=15 readonly>";
	$la_objectpartidas[1][5]="<input name=txtnomuni1 type=text id=txtnomuni1 class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectpartidas[1][6]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=2 readonly>";
	
	$li_filasfuentes=1;
	$la_objectfuentes[1][1]="<input name=txtcodfuefin1 type=text id=txtcodfuefin1 class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectfuentes[1][2]="<input name=txtnomfuefin1 type=text id=txtnomfuefin1 class=sin-borde style= text-align:left size=50 readonly>";
	$la_objectfuentes[1][3]="<input name=txtmonfuefin1 type=text id=txtmonfuefin1 class=sin-borde style= text-align:center size=20 readonly>";
	$la_objectfuentes[1][4]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=2 readonly>";

}

/////////Instrucciones para evitar que las cajitas pierdan la informacion cada vez que se realiza un submit/////////////
if	(array_key_exists("cmbestado",$_POST)){	$ls_codest=$_POST["cmbestado"]; }
else{$ls_codest="";}

if	(array_key_exists("cmbmunicipio",$_POST)){	$ls_codmun=$_POST["cmbmunicipio"]; }
else{$ls_codmun="";}

if	(array_key_exists("cmbparroquia",$_POST)){	$ls_codpar=$_POST["cmbparroquia"]; }
else{$ls_codpar="";}

if	(array_key_exists("cmbpais",$_POST)){	$ls_codpai=$_POST["cmbpais"]; }
else{/*/$ls_codpai="001";*/}

if	(array_key_exists("cmbcomunidad",$_POST)){	$ls_codcom=$_POST["cmbcomunidad"]; }
else{$ls_codcom="";}

if	(array_key_exists("cmbtenencia",$_POST)){	$ls_codten=$_POST["cmbtenencia"]; }
else{$ls_codten="";}

if	(array_key_exists("txtcodobr",$_POST)){	$ls_codobr=$_POST["txtcodobr"]; }
else{$ls_codobr="";}

if	(array_key_exists("txtnomobr",$_POST)){	$ls_nomobr=$_POST["txtnomobr"]; }
else{$ls_nomobr="";}

if	(array_key_exists("txtcodpro",$_POST)){	$ls_codpro=$_POST["txtcodpro"]; }
else{$ls_codpro="";}

if	(array_key_exists("txtnompro",$_POST)){	$ls_nompro=$_POST["txtnompro"]; }
else{$ls_nompro="";}

if	(array_key_exists("txtresobr",$_POST)){	$ls_resobr=$_POST["txtresobr"]; }
else{$ls_resobr="";}

if	(array_key_exists("txtcodtob",$_POST)){	$ls_codtob=$_POST["txtcodtob"]; }
else{$ls_codtob="";}

if	(array_key_exists("txtcnomtob",$_POST)){	$ls_nomtob=$_POST["txtnomtob"]; }
else{$ls_nomtob="";}

if	(array_key_exists("txtcodsiscon",$_POST)){	$ls_codsiscon=$_POST["txtcodsiscon"]; }
else{$ls_codsiscon="";}

if	(array_key_exists("txtnomsiscon",$_POST)){	$ls_nomsiscon=$_POST["txtnomsiscon"]; }
else{$ls_nomsiscon="";}

if	(array_key_exists("txtcodtipest",$_POST)){	$ls_codtipest=$_POST["txtcodtipest"]; }
else{$ls_codtipest="";}

if	(array_key_exists("txtnomtipest",$_POST)){	$ls_nomtipest=$_POST["txtnomtipest"]; }
else{$ls_nomtipest="";}

if	(array_key_exists("txtfeciniobr",$_POST)){	$ls_feciniobr=$_POST["txtfeciniobr"]; }
else{$ls_feciniobr="";}

if	(array_key_exists("txtfecfinobr",$_POST)){	$ls_fecfinobr=$_POST["txtfecfinobr"]; }
else{$ls_fecfinobr="";}

if	(array_key_exists("txtfeccreobr",$_POST)){	$ls_feccreobr=$_POST["txtfeccreobr"]; }
else{$ls_feccreobr="";}

if	(array_key_exists("txtdirobr",$_POST)){	$ls_dirobr=$_POST["txtdirobr"]; }
else{$ls_dirobr="";}

if	(array_key_exists("txtmonto",$_POST)){	$li_monto=$_POST["txtmonto"]; }
else{$li_monto="0,00";}

if	(array_key_exists("txtnomsiscon",$_POST)){	$ls_nomsiscon=$_POST["txtnomsiscon"]; }
else{$ls_nomsiscon="";}

if	(array_key_exists("txtnomtob",$_POST)){	$ls_nomtob=$_POST["txtnomtob"]; }
else{$ls_nomtob="";}

if	(array_key_exists("txtnomtipest",$_POST)){	$ls_nomtipest=$_POST["txtnomtipest"]; }
else{$ls_nomtipest="";}

if	(array_key_exists("txtnompro",$_POST)){	$ls_nompro=$_POST["txtnompro"]; }
else{$ls_nompro="";}

if	(array_key_exists("txtobsobr",$_POST)){	$ls_obsobr=$_POST["txtobsobr"]; }
else{$ls_obsobr="";}

if	(array_key_exists("filasfuentes",$_POST)){	$ls_filasfuentes=$_POST["filasfuentes"]; }
else{$ls_filasfuentes="";}

if	(array_key_exists("txttotalfuente",$_POST)){	$ls_totalfuente=$_POST["txttotalfuente"]; }
else{$ls_totalfuente="0,00";}

if	(array_key_exists("txtstaobr",$_POST)){	$ls_staobr=$_POST["txtstaobr"]; }
else{$ls_staobr="";}

if	(array_key_exists("hidstatus",$_POST)){	$ls_hidstatus=$_POST["hidstatus"]; }
else{$ls_hidstatus="";}
/////////////////////////////////////////////Operaciones de Actualizacion//////////////////////////////////////

if($ls_operacion=="ue_nuevo")//Abre una ficha de obra nueva
{
	$la_empresa=$_SESSION["la_empresa"];
	uf_limpiar_variables();
	//$ls_codobr=$io_funcdb->uf_generar_codigo(true,$la_empresa["codemp"],"sob_obra","codobr",6);
	require_once("../shared/class_folder/sigesp_c_generar_consecutivo.php");
	$io_keygen= new sigesp_c_generar_consecutivo();
	$ls_codobr= $io_keygen->uf_generar_numero_nuevo("SOB","sob_obra","codobr","SOBASI",6,"","","");
	unset($io_keygen);
/*	$ls_nomobr="";	    
	$ls_nompro="";	   
	$ls_resobr="";	
	$ls_codtob="";	
	$ls_codsiscon="";	
	$ls_codtipest="";
	$ls_feciniobr="";	
	$ls_fecfinobr="";  
	$ls_dirobr="";
	$ls_staobr="EMITIDO";	
	$li_monto="0,00";	
	$ls_codest="";  	
	$ls_codmun="";     
	$ls_codpar="";	
	$ls_codcom="";	
	$ls_nomsiscon="";	
	$ls_nomtob="";
	$ls_nomtipest="";	
	$ls_nompro="";
	$ls_codpro="";
	$ls_obsobr="";		
	$ls_codten="";
	$ls_totalfuente="0,00";
	$li_filaspartidas=1;
*/	$la_objectpartidas[1][1]="<input name=txtcodpar1 type=text id=txtcodpar1 class=sin-borde style= text-align:center size=10 readonly >";
	$la_objectpartidas[1][2]="<input name=txtnompar1 type=text id=txtnompar1 class=sin-borde style= text-align:left size=50 readonly >";
	$la_objectpartidas[1][3]="<input name=txtprepar1 type=text id=txtprepar1 class=sin-borde style= text-align:right size=22 readonly >";
	$la_objectpartidas[1][4]="<input name=txtcanpar1 type=text id=txtcanpar1 class=sin-borde style= text-align:right size=15 readonly>";
	$la_objectpartidas[1][5]="<input name=txtnomuni1 type=text id=txtnomuni1 class=sin-borde size=5 style= text-align:center readonly >";
	$la_objectpartidas[1][6]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=2 readonly>";
	
	$li_filasfuentes=1;
	$la_objectfuentes[1][1]="<input name=txtcodfuefin1 type=text id=txtcodfuefin1 class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectfuentes[1][2]="<input name=txtnomfuefin1 type=text id=txtnomfuefin1 class=sin-borde style= text-align:left size=50 readonly>";
	$la_objectfuentes[1][3]="<input name=txtmonfuefin1 type=text id=txtmonfuefin1 class=sin-borde style= text-align:center size=20 readonly>";
	$la_objectfuentes[1][4]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
	
	$fecha=date("d/m/Y");
	$ls_feccreobr=$fecha;	
}
elseif($ls_operacion=="ue_cargarpartida")
{	
	$li_filaspartidas=$_POST["filaspartidas"];
	$li_filaspartidas=$li_filaspartidas+1;
	
	for($li_i=1;$li_i<$li_filaspartidas;$li_i++)
	{
		$ls_codigo=$_POST["txtcodpar".$li_i];
		$ls_nombre=$_POST["txtnompar".$li_i];
		$ls_unidad=$_POST["txtnomuni".$li_i];
		$ls_prepar=$_POST["txtprepar".$li_i];
		$ls_canpar=$_POST["txtcanpar".$li_i];
		$la_objectpartidas[$li_i][1]="<input name=txtcodpar".$li_i." type=text id=txtcodpar".$li_i." class=sin-borde style= text-align:center size=10 value='".$ls_codigo."' readonly>";
		$la_objectpartidas[$li_i][2]="<input name=txtnompar".$li_i." type=text id=txtnompar".$li_i." class=sin-borde style= text-align:left size=50 value='".$ls_nombre."' readonly >";
		$la_objectpartidas[$li_i][3]="<input name=txtprepar".$li_i." type=text id=txtprepar".$li_i." class=sin-borde style= text-align:right size=22 value='".$ls_prepar."'readonly>";
		$la_objectpartidas[$li_i][4]="<input name=txtcanpar".$li_i." type=text id=txtcanpar".$li_i." class=sin-borde style= text-align:right size=15 value='".$ls_canpar."' onKeyPress=return(validaCajas(this,'d',event,15))  onKeyUp=javascript:uf_llamada_validarmonto(this) onBlur=javascript:ue_getformat(this) >";
		$la_objectpartidas[$li_i][5]="<input name=txtnomuni".$li_i." type=text id=txtnomuni".$li_i." class=sin-borde size=5 style= text-align:center value='".$ls_unidad."' readonly>";
		$la_objectpartidas[$li_i][6]="&nbsp;&nbsp;&nbsp;<a href=javascript:ue_removerpartida(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center onClick=javascript:valida_monto_total(this,".$li_i.")></a>";
	}	
	$la_objectpartidas[$li_filaspartidas][1]="<input name=txtcodpar".$li_filaspartidas." type=text id=txtcodpar".$li_filaspartidas." class=sin-borde style= text-align:center size=10 readonly >";
	$la_objectpartidas[$li_filaspartidas][2]="<input name=txtnompar".$li_filaspartidas." type=text id=txtnompar".$li_filaspartidas." class=sin-borde style= text-align:left size=50 readonly>";
	$la_objectpartidas[$li_filaspartidas][3]="<input name=txtprepar".$li_filaspartidas." type=text id=txtprepar".$li_filaspartidas." class=sin-borde style= text-align:right size=22 readonly >";
	$la_objectpartidas[$li_filaspartidas][4]="<input name=txtcanpar".$li_filaspartidas." type=text id=txtcanpar".$li_filaspartidas." class=sin-borde style= text-align:right size=15  readonly>";
	$la_objectpartidas[$li_filaspartidas][5]="<input name=txtnomuni".$li_filaspartidas." type=text id=txtnomuni".$li_filaspartidas." class=sin-borde size=5 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][6]="<input name=txtvacio".$li_filaspartidas." type=text id=txtvacio class=sin-borde style= text-align:center size=2 readonly>";
	
	?>
	<input  name="auxfilas" id="auxfilas" type="hidden" value="<?php print $li_filaspartidas; ?>">
	
	<?
	
}
elseif($ls_operacion=="ue_removerpartida")
{
	$li_filaspartidas=$_POST["filaspartidas"];
	$li_filaspartidas=$li_filaspartidas-1;
	$li_removerpartida=$_POST["hidremoverpartida"];
	$li_temp=0;

	for($li_i=1;$li_i<=$li_filaspartidas;$li_i++)
	{
		if($li_i!=$li_removerpartida)
		{		
			$li_temp=$li_temp+1;
			$ls_codigo=$_POST["txtcodpar".$li_i];
			$ls_nombre=$_POST["txtnompar".$li_i];
			$ls_unidad=$_POST["txtnomuni".$li_i];
			$ls_prepar=$_POST["txtprepar".$li_i];
			$ls_canpar=$_POST["txtcanpar".$li_i];
			$la_objectpartidas[$li_temp][1]="<input name=txtcodpar".$li_temp." type=text id=txtcodpar".$li_temp." class=sin-borde style= text-align:center size=10 value='".$ls_codigo."' readonly>";
			$la_objectpartidas[$li_temp][2]="<input name=txtnompar".$li_temp." type=text id=txtnompar".$li_temp." class=sin-borde style= text-align:left size=50 value='".$ls_nombre."' readonly >";
			$la_objectpartidas[$li_temp][3]="<input name=txtprepar".$li_temp." type=text id=txtprepar".$li_temp." class=sin-borde style= text-align:right size=22 value='".$ls_prepar."' readonly>";
			$la_objectpartidas[$li_temp][4]="<input name=txtcanpar".$li_temp." type=text id=txtcanpar".$li_temp." class=sin-borde style= text-align:right size=15 value='".$ls_canpar."' onKeyPress=return(validaCajas(this,'d',event,15))  onKeyUp=javascript:uf_llamada_validarmonto(this) onBlur=javascript:ue_getformat(this)  >";
			$la_objectpartidas[$li_temp][5]="<input name=txtnomuni".$li_temp." type=text id=txtnomuni".$li_temp." class=sin-borde size=5 style= text-align:center value='".$ls_unidad."' readonly>";
			$la_objectpartidas[$li_temp][6]="&nbsp;&nbsp;&nbsp;<a href=javascript:ue_removerpartida(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center onClick=javascript:valida_monto_total(this,".$li_temp.")></a>";
		}
	}
	$la_objectpartidas[$li_filaspartidas][1]="<input name=txtcodpar".$li_filaspartidas." type=text id=txtcodpar".$li_filaspartidas." class=sin-borde style= text-align:center size=10 readonly>";
	$la_objectpartidas[$li_filaspartidas][2]="<input name=txtnompar".$li_filaspartidas." type=text id=txtnompar".$li_filaspartidas." class=sin-borde style= text-align:left size=50 readonly>";
	$la_objectpartidas[$li_filaspartidas][3]="<input name=txtprepar".$li_filaspartidas." type=text id=txtprepar".$li_filaspartidas." class=sin-borde style= text-align:right size=22 readonly >";
	$la_objectpartidas[$li_filaspartidas][4]="<input name=txtcanpar".$li_filaspartidas." type=text id=txtcanpar".$li_filaspartidas." class=sin-borde style= text-align:right size=15  readonly>";
	$la_objectpartidas[$li_filaspartidas][5]="<input name=txtnomuni".$li_filaspartidas." type=text id=txtnomuni".$li_filaspartidas." class=sin-borde size=5 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][6]="<input name=txtvacio".$li_filaspartidas." type=text id=txtvacio class=sin-borde style= text-align:center size=2 readonly>";
}
elseif($ls_operacion=="ue_cargarfuente")
{
	$li_filasfuentes=$_POST["filasfuentes"];
	$li_filasfuentes=$li_filasfuentes+1;
	for($li_i=1;$li_i<$li_filasfuentes;$li_i++)
	{		
		$ls_codigo=$_POST["txtcodfuefin".$li_i];
		$ls_nombre=$_POST["txtnomfuefin".$li_i];
		$ls_monfuefin=$_POST["txtmonfuefin".$li_i];
		$la_objectfuentes[$li_i][1]="<input name=txtcodfuefin".$li_i." type=text id=txtcodfuefin".$li_i." class=sin-borde style= text-align:center size=5 value='".$ls_codigo."' readonly>";
		$la_objectfuentes[$li_i][2]="<input name=txtnomfuefin".$li_i." type=text id=txtcodfuefin".$li_i." class=sin-borde style= text-align:left size=60 value='".$ls_nombre."' readonly >";
		$la_objectfuentes[$li_i][3]="<input name=txtmonfuefin".$li_i." type=text id=txtmonfuefin".$li_i." class=sin-borde size=20 maxlength=21 style= text-align:right value='".$ls_monfuefin."' onKeyPress=return(validaCajas(this,'d',event,15))  onKeyUp=javascript:uf_llamada_validarmonto(this) onBlur=javascript:ue_getformat(this)>";
		$la_objectfuentes[$li_i][4]="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=javascript:ue_removerfuente(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
	}	
	$la_objectfuentes[$li_filasfuentes][1]="<input name=txtcodfuefin".$li_filasfuentes." type=text id=txtcodfuefin".$li_filasfuentes." class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectfuentes[$li_filasfuentes][2]="<input name=txtnomfuefin".$li_filasfuentes." type=text id=txtnomfuefin".$li_filasfuentes." class=sin-borde style= text-align:left size=50 readonly>";
	$la_objectfuentes[$li_filasfuentes][3]="<input name=txtmonfuefin".$li_filasfuentes." type=text id=txtmonfuefin".$li_filasfuentes." class=sin-borde style= text-align:center size=20 readonly>";
	$la_objectfuentes[$li_filasfuentes][4]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
}
elseif($ls_operacion=="ue_removerfuente")
{
	$li_filasfuentes=$_POST["filasfuentes"];
	$li_filasfuentes=$li_filasfuentes-1;
	$li_removerfuente=$_POST["hidremoverfuente"];
	$li_temp=0;
	$ld_total=0;
	for($li_i=1;$li_i<=$li_filasfuentes;$li_i++)
	{
		if($li_i!=$li_removerfuente)
		{		
			$li_temp=$li_temp+1;
			$ls_codigo=$_POST["txtcodfuefin".$li_i];
			$ls_nombre=$_POST["txtnomfuefin".$li_i];
			$ls_monfuefin=$_POST["txtmonfuefin".$li_i];
			$ld_total=$ld_total+$ls_monfuefin;
			$la_objectfuentes[$li_temp][1]="<input name=txtcodfuefin".$li_temp." type=text id=txtcodfuefin".$li_temp." class=sin-borde style= text-align:center size=5 value='".$ls_codigo."' readonly>";
			$la_objectfuentes[$li_temp][2]="<input name=txtnomfuefin".$li_temp." type=text id=txtnomfuefin".$li_temp." class=sin-borde style= text-align:left size=60 value='".$ls_nombre."' readonly >";
			$la_objectfuentes[$li_temp][3]="<input name=txtmonfuefin".$li_temp." type=text id=txtmonfuefin".$li_temp." class=sin-borde size=20 maxlength=21 style= text-align:right value='".$ls_monfuefin."' onKeyPress=return(validaCajas(this,'d',event,15))  onKeyUp=javascript:uf_llamada_validarmonto(this) onBlur=javascript:ue_getformat(this)>";
			$la_objectfuentes[$li_temp][4]="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=javascript:ue_removerfuente(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
		}
	}
	$la_objectfuentes[$li_filasfuentes][1]="<input name=txtcodfuefin".$li_filasfuentes." type=text id=txtcodfuefin".$li_filasfuentes." class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectfuentes[$li_filasfuentes][2]="<input name=txtnomfuefin".$li_filasfuentes." type=text id=txtnomfuefin".$li_filasfuentes." class=sin-borde style= text-align:left size=50 readonly>";
	$la_objectfuentes[$li_filasfuentes][3]="<input name=txtmonfuefin".$li_filasfuentes." type=text id=txtmonfuefin".$li_filasfuentes." class=sin-borde size=20 style= text-align:center readonly>";
	$la_objectfuentes[$li_filasfuentes][4]="<input name=txtvacio".$li_filasfuentes." type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	
}elseif($ls_operacion=="ue_guardar")
{
	$lb_fuente=true;
	$ls_hidstatus=$_POST["hidstatus"];
	$ld_dateinicio=$io_function->uf_convertirdatetobd($ls_feciniobr);
	$ld_datefin=$io_function->uf_convertirdatetobd($ls_fecfinobr);
	$lb_existe=$io_obra->uf_select_obra ($ls_codobr,$la_datos);
	$ld_datecreacion=$io_function->uf_convertirdatetobd($ls_feccreobr);
	if($ls_hidstatus!="C")
	{	
		$io_sql->begin_transaction();
		$ls_codobraux=$ls_codobr;
		$lb_valido=$io_obra->uf_guardar_obra(&$ls_codobr,$ls_codten,$ls_codtipest,$ls_codest,$ls_codmun,$ls_codpar,$ls_codcom,$ls_codsiscon,$ls_codpro,$ls_codtob,$ls_nomobr,$ls_dirobr,$ls_obsobr,$ls_resobr,$ld_dateinicio,$ld_datefin,$li_monto,$ld_datecreacion,$la_seguridad);
		if ($lb_valido)
		{
			
			for ($li_i=1;$li_i<$li_filaspartidas;$li_i++)//Guardando las partidas asociadas a la obra
			{
				$ls_codigo=$_POST["txtcodpar".$li_i];
				$ls_cantidad=$io_funsob->uf_convertir_cadenanumero($_POST["txtcanpar".$li_i]);
				$lb_partida=$io_obra->uf_guardar_dtpartidas($ls_codobr,$ls_codigo,$ls_cantidad,$la_seguridad);			
			}
			
			for ($li_i=1;$li_i<$li_filasfuentes;$li_i++)//Guardando las fuentes de financiamiento asociadas a la obra
			{
				$ls_codigo=$_POST["txtcodfuefin".$li_i];
				$ld_monto=$_POST["txtmonfuefin".$li_i];
				$lb_fuente=$io_obra->uf_guardar_dtfuentesfinanciamiento($ls_codobr,$ls_codigo,$ld_monto,$la_seguridad);				
			}		
				
		}
		if ($lb_valido && $lb_partida && $lb_fuente)
		{
			if($ls_codobraux!=$ls_codobr)
			{
				$io_msg->message("Se le Asigno el Nuevo Código de Obra ".$ls_codobr.". ");
			}
			$io_mensaje->incluir();
			$io_sql->commit();
		}
		else
		{
			$io_mensaje->error_incluir();
			$io_sql->rollback();
		}
	}//end del if si no existe la obra
	else
	{
		$lb_valido=$io_obra->uf_select_estado($ls_codobr,$li_estado);
		if($li_estado==1)
		{			
			$ls_hidstatus=$_POST["hidstatus"];
			$io_sql->begin_transaction();
			$lb_valido=$io_obra->uf_update_obra($ls_codobr,$ls_codten,$ls_codtipest,$ls_codest,$ls_codmun,$ls_codpar,$ls_codcom,$ls_codsiscon,$ls_codpro,$ls_codtob,$ls_nomobr,$ls_dirobr,$ls_obsobr,$ls_resobr,$ld_dateinicio,$ld_datefin,$li_monto,$ld_datecreacion,$la_seguridad);
			if($lb_valido)
			{
				$la_partidas["codpar"][1]="";	
				$la_partidas["canpar"][1]="";	
				for ($li_i=1;$li_i<$li_filaspartidas;$li_i++)
				{
					$la_partidas["codpar"][$li_i]=$_POST["txtcodpar".$li_i];
					$la_partidas["canpar"][$li_i]=$io_funsob->uf_convertir_cadenanumero($_POST["txtcanpar".$li_i]);
				}			
				$lb_dtpartidas=$io_obra->uf_update_dtpartidas($ls_codobr,$la_partidas,$li_filaspartidas,$la_seguridad);
				$la_fuentes["codfuefin"][1]="";
				$la_fuentes["monfuefin"][1]="";
				for ($li_i=1;$li_i<$li_filasfuentes;$li_i++)
				{
					$la_fuentes["codfuefin"][$li_i]=$_POST["txtcodfuefin".$li_i];
					$la_fuentes["monfuefin"][$li_i]=$_POST["txtmonfuefin".$li_i];
				}	
				$lb_dtfuentes=$io_obra->uf_update_dtfuentesfinanciamiento($ls_codobr,$la_fuentes,$li_filasfuentes,$la_seguridad);
				if($lb_valido===true)
				{
					$io_mensaje->modificar();					
					$io_sql->commit();
				}
				else
				{
					if($lb_dtpartidas ||  $lb_dtfuentes)
					{
						$io_mensaje->modificar();
						$io_sql->commit();
					}
					else
					{
						$io_msg->mensaje("Error, No se modifico la Obra");
						$io_sql->rollback();
					}
				}
			}
		}
		else
		{
			$ls_estado=$io_funsob->uf_convertir_numeroestado($li_estado);
			$io_msg->message("La Obra no puede ser modificada, su estado es ".$ls_estado);
		}
	}
			
	/*if(!$lb_valido)
	{
		if(($ls_hidstatus=="C")&&(!$lb_existe))
		{
			$io_msg->mensaje("Error, No se modifico la Obra");
		}
		if(($ls_hidstatus!="C"))
	}*/
	//Limpiar el formulario
			uf_limpiar_variables();
/*			$ls_codobr="";
			$ls_nomobr="";	    
			$ls_nompro="";	   
			$ls_resobr="";	
			$ls_codtob="";	
			$ls_codsiscon="";	
			$ls_codtipest="";
			$ls_feciniobr="";	
			$ls_fecfinobr="";  
			$ls_dirobr="";	
			$li_monto="0,00";	
			$ls_codest="";  	
			$ls_codmun="";     
			$ls_codpar="";	
			$ls_codcom="";	
			$ls_nomsiscon="";	
			$ls_nomtob="";
			$ls_nomtipest="";	
			$ls_nompro="";
			$ls_codpro="";
			$ls_obsobr="";		
			$ls_codten="";	
			$ls_totalfuente="0,00";
			$ls_feccreobr="";
			$li_filaspartidas=1;	
			$li_filasfuentes=1;
*/			$la_objectpartidas[$li_filaspartidas][1]="<input name=txtcodpar".$li_filaspartidas." type=text id=txtcodpar".$li_filaspartidas." class=sin-borde style= text-align:center size=10 readonly>";
			$la_objectpartidas[$li_filaspartidas][2]="<input name=txtnompar".$li_filaspartidas." type=text id=txtnompar".$li_filaspartidas." class=sin-borde style= text-align:left size=50 readonly>";
			$la_objectpartidas[$li_filaspartidas][3]="<input name=txtprepar".$li_filaspartidas." type=text id=txtprepar".$li_filaspartidas." class=sin-borde style= text-align:right size=22 readonly >";
  			$la_objectpartidas[$li_filaspartidas][4]="<input name=txtcanpar".$li_filaspartidas." type=text id=txtcanpar".$li_filaspartidas." class=sin-borde style= text-align:right size=15  readonly>";
			$la_objectpartidas[$li_filaspartidas][5]="<input name=txtnomuni".$li_filaspartidas." type=text id=txtnomuni".$li_filaspartidas." class=sin-borde size=5 style= text-align:center readonly>";
			$la_objectpartidas[$li_filaspartidas][6]="<input name=txtvacio".$li_filaspartidas." type=text id=txtvacio class=sin-borde style= text-align:center size=2 readonly>";
			
			$la_objectfuentes[$li_filasfuentes][1]="<input name=txtcodfuefin".$li_filasfuentes." type=text id=txtcodfuefin".$li_filasfuentes." class=sin-borde style= text-align:center size=5 readonly>";
			$la_objectfuentes[$li_filasfuentes][2]="<input name=txtnomfuefin".$li_filasfuentes." type=text id=txtnomfuefin".$li_filasfuentes." class=sin-borde style= text-align:left size=50 readonly>";
			$la_objectfuentes[$li_filasfuentes][3]="<input name=txtmonfuefin".$li_filasfuentes." type=text id=txtmonfuefin".$li_filasfuentes." class=sin-borde style= text-align:center size=20 readonly>";
			$la_objectfuentes[$li_filasfuentes][4]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	
}//end del elseif
elseif($ls_operacion=="ue_cargarobra")
{
	$ls_codten=$_POST["hidtenencia"];
	$ls_codest=$_POST["hidestado"];
	$ls_codmun=$_POST["hidmunicipio"];
	$ls_codpar=$_POST["hidparroquia"];
	$ls_codcom=$_POST["hidcomunidad"];
	$ls_codpai=$_POST["hidpais"];
	/////Cargando las partidas
	$lb_valido=$io_obra->uf_select_partidas ($ls_codobr,$la_partidas,$li_totalfilas);
	if($lb_valido)
	{
	$io_datastore->data=$la_partidas;
	$li_filaspartidas=$io_datastore->getRowCount("codpar");
	for($li_i=1;$li_i<=$li_filaspartidas;$li_i++)
	{
		$ls_codigo=$io_datastore->getValue("codpar",$li_i);
		$ls_nombre=$io_datastore->getValue("nompar",$li_i);
		$ls_unidad=$io_datastore->getValue("nomuni",$li_i);
		$ls_prepar=$io_funsob->uf_convertir_numerocadena($io_datastore->getValue("prepar",$li_i));
		$ls_canpar=$io_funsob->uf_convertir_numerocadena($io_datastore->getValue("canparobr",$li_i));
		$la_objectpartidas[$li_i][1]="<input name=txtcodpar".$li_i." type=text id=txtcodpar".$li_i." class=sin-borde style= text-align:center size=10 value='".$ls_codigo."' readonly>";
		$la_objectpartidas[$li_i][2]="<input name=txtnompar".$li_i." type=text id=txtnompar".$li_i." class=sin-borde style= text-align:left size=50 value='".$ls_nombre."' readonly >";
		$la_objectpartidas[$li_i][3]="<input name=txtprepar".$li_i." type=text id=txtprepar".$li_i." class=sin-borde style= text-align:right size=22 value='".$ls_prepar."' readonly >";
		$la_objectpartidas[$li_i][4]="<input name=txtcanpar".$li_i." type=text id=txtcanpar".$li_i." class=sin-borde style= text-align:right size=15 value='".$ls_canpar."' onKeyPress=return(validaCajas(this,'d',event,15))  onKeyUp=javascript:uf_llamada_validarmonto(this) onBlur=javascript:ue_getformat(this)  >";
		$la_objectpartidas[$li_i][5]="<input name=txtnomuni".$li_i." type=text id=txtnomuni".$li_i." class=sin-borde size=5 style= text-align:center value='".$ls_unidad."' readonly>";
		$la_objectpartidas[$li_i][6]="&nbsp;&nbsp;&nbsp;<a href=javascript:ue_removerpartida(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center on Click=javascript:valida_monto_total(this,".$li_i.")></a>";
	}	
	$li_filaspartidas=$li_filaspartidas+1;
	$la_objectpartidas[$li_filaspartidas][1]="<input name=txtcodpar".$li_filaspartidas." type=text id=txtcodpar".$li_filaspartidas." class=sin-borde style= text-align:center size=10 readonly>";
	$la_objectpartidas[$li_filaspartidas][2]="<input name=txtnompar".$li_filaspartidas." type=text id=txtnompar".$li_filaspartidas." class=sin-borde style= text-align:left size=50 readonly>";
	$la_objectpartidas[$li_filaspartidas][3]="<input name=txtprepar".$li_filaspartidas." type=text id=txtprepar".$li_filaspartidas." class=sin-borde style= text-align:right size=22 readonly >";
	$la_objectpartidas[$li_filaspartidas][4]="<input name=txtcanpar".$li_filaspartidas." type=text id=txtcanpar".$li_filaspartidas." class=sin-borde style= text-align:right size=15  readonly>";
	$la_objectpartidas[$li_filaspartidas][5]="<input name=txtnomuni".$li_filaspartidas." type=text id=txtnomuni".$li_filaspartidas." class=sin-borde size=5 style= text-align:center readonly>";
	$la_objectpartidas[$li_filaspartidas][6]="<input name=txtvacio".$li_filaspartidas." type=text id=txtvacio class=sin-borde style= text-align:center size=2 readonly>";
	}	
	
	///Cargando las Fuentes de Financiamiento//////
	$lb_valido=$io_obra->uf_select_fuentesfinanciamiento ($ls_codobr,$la_fuentes,$li_totalfilas);
	if($lb_valido)
	{
	$io_datastore->data=$la_fuentes;
	$ld_total=0;
	$li_filasfuentes=$io_datastore->getRowCount("codfuefin");
	for($li_i=1;$li_i<=$li_filasfuentes;$li_i++)
	{		
		$ls_codigo=$io_datastore->getValue("codfuefin",$li_i);
		$ls_nombre=$io_datastore->getValue("denfuefin",$li_i);
		$ls_monfuefin=$io_datastore->getValue("monto",$li_i);
		$ld_total=$ld_total+$ls_monfuefin;
		$ls_monfuefin= " ".number_format($ls_monfuefin,2,",",".");
		$la_objectfuentes[$li_i][1]="<input name=txtcodfuefin".$li_i." type=text id=txtcodfuefin".$li_i." class=sin-borde style= text-align:center size=5 value='".$ls_codigo."' readonly>";
		$la_objectfuentes[$li_i][2]="<input name=txtnomfuefin".$li_i." type=text id=txtcodfuefin".$li_i." class=sin-borde style= text-align:left size=60 value='".$ls_nombre."' readonly >";
		$la_objectfuentes[$li_i][3]="<input name=txtmonfuefin".$li_i." type=text id=txtmonfuefin".$li_i." class=sin-borde size=20 maxlength=21 style= text-align:right value='".$ls_monfuefin."' onKeyPress=return(validaCajas(this,'d',event,15))  onKeyUp=javascript:uf_llamada_validarmonto(this) onBlur=javascript:ue_getformat(this)>";
		$la_objectfuentes[$li_i][4]="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=javascript:ue_removerfuente(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
	}	
	$li_filasfuentes=$li_filasfuentes+1;
	$ls_totalfuente=" ".number_format($ld_total,2,",",".");;
	$la_objectfuentes[$li_filasfuentes][1]="<input name=txtcodfuefin".$li_filasfuentes." type=text id=txtcodfuefin".$li_filasfuentes." class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectfuentes[$li_filasfuentes][2]="<input name=txtnomfuefin".$li_filasfuentes." type=text id=txtnomfuefin".$li_filasfuentes." class=sin-borde style= text-align:left size=50 readonly>";
	$la_objectfuentes[$li_filasfuentes][3]="<input name=txtmonfuefin".$li_filasfuentes." type=text id=txtmonfuefin".$li_filasfuentes." class=sin-borde style= text-align:center size=20 readonly>";
	$la_objectfuentes[$li_filasfuentes][4]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	}
}elseif ($ls_operacion=="ue_eliminar")
{
	$lb_validofuente=$lb_validoobra=$lb_validopartida=true;
	$lb_valido=$io_obra->uf_select_estado($ls_codobr,$li_estado);
	if($lb_valido)
	{
		if($li_estado==1)
		{
			$lb_valido=$io_obra->uf_update_estado($ls_codobr,3,$la_seguridad);
			if($lb_valido)
			{
				$io_mensaje->anular();
			}
			else
			{
				$io_mensaje->error_anular();
			}
		}
		else
		{
			if($li_estado==3)
			{
				$io_msg->message("La Obra ya se encuentra Anulada!!!");
			}
			else
			{
				$ls_estado=$io_funsob->uf_convertir_numeroestado($li_estado);
				$io_msg->message("La Obra no puede ser anulada, su estado es ".$ls_estado);
			}
		}
	}
		
		/*
		
	$lb_existeobra=$io_obra->uf_select_obra($ls_codobr,$la_datos);
	if ($lb_existeobra)
	{	
		$lb_tieneasignacion=$io_obra->uf_tieneasignacion ($ls_codobr);
		if (!$lb_tieneasignacion)
		{	
			$lb_validoobra=$io_obra->uf_delete_obra($ls_codobr);
			for($li_i=1;$li_i<=$li_filaspartidas;$li_i++)
			{
				$ls_codigo=$_POST["txtcodpar".$li_i];
				$lb_existepartida= $io_obra->uf_select_partida ($ls_codobr,$ls_codigo);
				if ($lb_existepartida)
				{
					$lb_validopartida=$io_obra->uf_delete_dtpartidas ($ls_codobr,$ls_codigo);
				}			
			}	
			$li_filaspartidas=1;
			$la_objectpartidas[$li_filaspartidas][1]="<input name=txtcodpar".$li_filaspartidas." type=text id=txtcodpar".$li_filaspartidas." class=sin-borde style= text-align:center size=5 readonly>";
			$la_objectpartidas[$li_filaspartidas][2]="<input name=txtnompar".$li_filaspartidas." type=text id=txtnompar".$li_filaspartidas." class=sin-borde style= text-align:left size=50 readonly>";
			$la_objectpartidas[$li_filaspartidas][3]="<input name=txtnomuni".$li_filaspartidas." type=text id=txtnomuni".$li_filaspartidas." class=sin-borde size=20 style= text-align:center readonly>";
			$la_objectpartidas[$li_filaspartidas][4]="<input name=txtvacio".$li_filaspartidas." type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
			
			for($li_i=1;$li_i<=$li_filasfuentes;$li_i++)
			{		
				$ls_codigo=$_POST["txtcodfuefin".$li_i];
				$lb_existefuente=$io_obra->uf_select_fuentefinanciamiento($ls_codobr,$ls_codigo);
				if($lb_existefuente)
				{
					$lb_validofuente=$io_obra->uf_delete_dtfuentesfinanciamiento ($ls_codobr,$ls_codigo);
				}
			}	
			$li_filasfuentes=1;
			$la_objectfuentes[$li_filasfuentes][1]="<input name=txtcodfuefin".$li_filasfuentes." type=text id=txtcodfuefin".$li_filasfuentes." class=sin-borde style= text-align:center size=5 readonly>";
			$la_objectfuentes[$li_filasfuentes][2]="<input name=txtnomfuefin".$li_filasfuentes." type=text id=txtnomfuefin".$li_filasfuentes." class=sin-borde style= text-align:left size=50 readonly>";
			$la_objectfuentes[$li_filasfuentes][3]="<input name=txtmonfuefin".$li_filasfuentes." type=text id=txtmonfuefin".$li_filasfuentes." class=sin-borde style= text-align:center size=20 readonly>";
			$la_objectfuentes[$li_filasfuentes][4]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
		}//fin del if de si no tiene asignacion
		else
		{
			$io_msg->message("Esta obra ya fue asignada a un Contratista, no puede ser eliminada!!!");
		}
		
	}//fin del if existeobra
	
	if ($lb_validoobra && $lb_validopartida && $lb_validofuente && !$lb_tieneasignacion)
		$io_msg->message("Registro Eliminado!!!");*/
		
	$ls_operacion="";	
	uf_limpiar_variables();
/*	$ls_codobr="";
	$ls_nomobr="";	    
	$ls_codpro="";	    
	$ls_resobr="";	
	$ls_codtob="";
	$ls_codsiscon="";	
	$ls_codtipest="";	
	$ls_feciniobr="";	
	$ls_fecfinobr="";	
	$ls_dirobr="";	
	$li_monto="0,00";
	$ls_codest="";  	
	$ls_codmun="";  	
	$ls_codpar="";	
	$ls_codcom="";
	$ls_nomsiscon="";	
	$ls_nomtob="";  	
	$ls_nomtipest="";	
	$ls_nompro="";
	$ls_obsobr="";		
	$ls_codten="";
	$ls_totalfuente="0,00";
*/	$ls_feccreobr="";
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
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img name="imgnuevo" id="imgnuevo" src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a><!--img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"--><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a><a href="javascript:ue_print();"><img src="../shared/imagebank/tools20/imprimir.gif" title='Imprimir' alt="Imprimir" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
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

  <table width="743" border="0" align="center" cellpadding="0" cellspacing="0"  class="contorno">
      <tr class="titulo-celdanew">
        <th colspan="7" scope="col" class="titulo-celdanew">Obra</th>
    </tr>
      <tr class="formato-blanco">
        <td colspan="7">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td width="54" height="22"><div align="right"></div></td>
        <td width="68"><div align="right">C&oacute;digo</div></td>
        <td width="47"><input name="txtcodobr" type="text" id="txtcodobr" style="text-align:center " value="<?php print $ls_codobr ?>" size="8" maxlength="8" readonly="true"></td>
        <td width="88"><input name="operacion" type="hidden" id="operacion"><input name="hidstatus" type="hidden" id="hidstatus" value="<?php print $ls_hidstatus; ?>" ></td>
        <td width="206">&nbsp;</td>
        <td width="73"><div align="right">Fecha:</div></td>
        <td width="205"><input name="txtfeccreobr" type="text" id="txtfeccreobr" value="<?php print $ls_feccreobr ?>" size="12" maxlength="10"  datepicker="true"  readonly="true" style="text-align:center"></td>
      </tr>
      <tr class="formato-blanco">
        <td height="22"><div align="right"></div></td>
        <td height="22"><div align="right">Descripci&oacute;n</div></td>
        <td colspan="5"><textarea name="txtnomobr" cols="80"  onKeyDown="textCounter(this,254)"  onKeyUp="textCounter(this,254)"  rows="2" id="txtnomobr" ><?php print $ls_nomobr ?></textarea></td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="7">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td height="392" colspan="7"><table width="636" border="0" align="center" cellpadding="0" cellspacing="0"  class="sin-borde">
          <tr class="titulo-celdanew">
            <td colspan="9"><div align="center">Caracter&iacute;sticas</div></td>
          </tr>
          <tr class="formato-blanco">
            <td height="22">&nbsp;</td>
            <td>&nbsp;</td>
            <td valign="middle">&nbsp;</td>
            <td colspan="2" valign="bottom">&nbsp;</td>
            <td>&nbsp;</td>
            <td><?php
			if(array_key_exists("hidstatus",$_POST))
			{
				$ls_hidstatus=$_POST["hidstatus"];
				if($ls_hidstatus=="C")
				{
					$_SESSION["campoclave"]=$ls_codobr;
			?>
              <a href="javascript:ue_grabarfoto()"><img src="../shared/imagebank/mas.gif" width="9" height="17" border="0">Incluir Fotos</a>
              <?php
			}
			}
			?></td>
            <td colspan="2"><?php
			if(array_key_exists("hidstatus",$_POST))
			{
				$ls_hidstatus=$_POST["hidstatus"];
				if($ls_hidstatus=="C")
				{
					$_SESSION["campoclave"]=$ls_codobr;
			?>
              <a href="javascript:ue_verfotos()"><img src="../shared/imagebank/mas.gif" width="9" height="17" border="0">Ver Fotos</a>
              <?php
			}
			}
			?></td>
          </tr>
          <tr class="formato-blanco">
            <td height="22">&nbsp;</td>
            <td width="33">&nbsp;</td>
            <td width="110" valign="middle"><div align="right">Estado</div></td>
            <td colspan="2" valign="bottom"><input name="txtstaobr" readonly="true" type="text" class="celdas-grises" id="txtstaobr" value="<?php print $ls_staobr;?>" size="15" style="text-align:center " maxlength="20"></td>
            <td width="57">&nbsp;</td>
            <td width="104">&nbsp;</td>
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr class="formato-blanco">
            <td width="3" height="22"><div align="left"></div></td>
            <td colspan="2"><div align="right">Organismo Ejecutor</div></td>
            <td colspan="6"><input name="txtcodpro" type="text" id="txtcodpro"  style="text-align: center" value="<?php print $ls_codpro ?>" size="4" maxlength="4" readonly="true">
                <a href="javascript:ue_catpropietario();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
                <input name="txtnompro" type="text" id="txtnompro3"  style="text-align:left" class="sin-borde" value="<?php print $ls_nompro ?>" size="50" maxlength="100" readonly="true" >
                <div align="right"></div>
                <div align="left"> </div></td>
          </tr>
          <tr class="formato-blanco">
            <td align="right"><div align="left"></div></td>
            <td colspan="2" align="right"><div align="right">Responsable</div></td>
            <td colspan="6"><input name="txtresobr" type="text" id="txtresobr"   style="text-align:left;" value="<?php print $ls_resobr ?>" size="57" maxlength="50"></td>
          </tr>
          <tr class="formato-blanco">
            <td height="15" align="right">&nbsp;</td>
            <td colspan="2" align="right"><div align="right"></div></td>
            <td colspan="6" align="left">&nbsp;</td>
          </tr>
          <tr class="formato-blanco">
            <td align="right"><div align="left"></div></td>
            <td colspan="2" align="right"><div align="right">Tenencia</div></td>
            <td colspan="6" align="left">
              <?php
				if($ls_codobr=="")
					$lb_valido=false;
				else
					$lb_valido=$io_obra->uf_llenarcombo_tenencia(&$la_tenencia);
					
				if($lb_valido)
				{
					$io_datastore->data=$la_tenencia;
					$li_totalfilas=$io_datastore->getRowCount("codten");
				}
				
			?>
              <select name="cmbtenencia" size="1" id="cmbtenencia">
                <option value="s1">Seleccione...</option>
                <?php
 		        for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
		        {
		         $ls_codigo=$io_datastore->getValue("codten",$li_i);
		         $ls_nomten=$io_datastore->getValue("nomten",$li_i);
		         if ($ls_codigo==$ls_codten)
			     {
				      print "<option value='$ls_codigo' selected>$ls_nomten</option>";
			     }
		         else
			     {
				      print "<option value='$ls_codigo'>$ls_nomten</option>";
			     }
		       } 
	        ?>
              </select>
              <input name="hidtenencia" type="hidden" id="hidtenencia3" value="<?php print $ls_codten ?>">
              <div align="left"></div></td>
          </tr>
          <tr class="formato-blanco">
            <td>&nbsp;</td>
            <td colspan="2"><div align="right">Sistema Constructivo</div></td>
            <td height="22" colspan="6" align="left"><input name="txtcodsiscon" type="text" id="txtcodsiscon"  style="text-align:center" value="<?php print $ls_codsiscon ?>" size="3" maxlength="3"  readonly="true"  >
                <a href="javascript:ue_catsistemaconstructivo();"> <img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"> </a>
                <input name="txtnomsiscon" type="text" id="txtnomsiscon"  style="text-align:left" class="sin-borde" value="<?php print $ls_nomsiscon ?>" size="50" maxlength="100"  readonly="true"  >                  </tr>
          <tr class="formato-blanco">
            <td>&nbsp;</td>
            <td colspan="2"><div align="right">Tipo de Obra</div></td>
            <td height="22" colspan="6" align="left"><input name="txtcodtob" type="text" id="txtcodtob"  style="text-align:center" value="<?php print $ls_codtob ?>" size="3" maxlength="3"  readonly="true" >
                <a href="javascript:ue_cattipoobra();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
                <input name="txtnomtob" type="text" id="txtnomtob"  style="text-align:left" class="sin-borde" value="<?php print $ls_nomtob ?>" size="50" maxlength="100"  readonly="true" >                  </tr>
          <tr class="formato-blanco">
            <td><div align="left"></div></td>
            <td colspan="2"><div align="right">Tipo de Estructura</div></td>
            <td height="19" colspan="6" align="left"><input name="txtcodtipest" type="text" id="txtcodtipest"  style="text-align:center" value="<?php print $ls_codtipest ?>" size="3" maxlength="3"  readonly="true" >
                <a href="javascript:ue_cattipoestructura();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
                <input name="txtnomtipest" type="text" id="txtnomtipest3"  style="text-align:left" class="sin-borde" value="<?php print $ls_nomtipest ?>" size="50" maxlength="100"  readonly="true" >                  </tr>
          <tr class="formato-blanco">
            <td>&nbsp;</td>
            <td colspan="2">&nbsp;</td>
            <td height="19" colspan="4" align="left">
            <td width="60">&nbsp;</td>
            <td width="113">&nbsp;</td>
          </tr>
          <tr class="formato-blanco">
            <td>&nbsp;</td>
            <td height="19" colspan="3" align="right">Fecha de Inicio</td>
            <td height="19" colspan="3" align="left"><input name="txtfeciniobr" type="text" id="txtfeciniobr"  style="text-align:left" value="<?php print $ls_feciniobr ?>" size="11" maxlength="10"   datepicker="true"  readonly="true" >
&nbsp;&nbsp;&nbsp; Fecha de Fin
      <input name="txtfecfinobr" id="txtfecfinobr" type="text"  style="text-align:left" value="<?php print $ls_fecfinobr ?>" size="11" maxlength="10" datepicker="true" readonly="true" >
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr class="formato-blanco">
            <td colspan="9"><div align="right"></div></td>
          </tr>
          <tr class="formato-blanco">
            <td><div align="left"></div></td>
            <td><div align="right">Pa&iacute;s</div></td>
            <td height="36" colspan="7"><?php
				/*if($ls_codobr=="")
					$lb_valido=false;
				else*/
				$lb_valido=$io_obra->uf_llenarcombo_pais($la_datapais);					
				if($lb_valido)
				{
					$io_datastore->data=$la_datapais;
					$li_totalfilas=$io_datastore->getRowCount("codpai");
				}
				
				?>
                <select name="cmbpais" size="1" id="cmbpais" onChange="javascript:ue_llenarcmbestado();">
                  <?php
					for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
					{
					 $ls_codigo=$io_datastore->getValue("codpai",$li_i);
					 $ls_desest=$io_datastore->getValue("despai",$li_i);
					 if ($ls_codigo==$ls_codpai)
					 {
						  print "<option value='$ls_codigo' selected>$ls_desest</option>";
					 }
					 else
					 {
						  print "<option value='$ls_codigo'>$ls_desest</option>";
					 }
					} 
	           ?>
                </select>
                <input name="hidpais" type="hidden" id="hidpais" value="<?php print $ls_codpai?>">
                <div align="right"></div>
                <div align="left"> </div>
                <div align="right"></div></td>
          </tr>
          <tr class="formato-blanco">
            <td colspan="9">&nbsp;</td>
          </tr>
          <tr class="formato-blanco">
            <td><div align="left"></div></td>
            <td><div align="right">Estado</div></td>
            <td><?php
				if($ls_codobr=="")
					$lb_valido=false;
				else
					$lb_valido=$io_obra->uf_llenarcombo_estado($ls_codpai,&$la_tenencia);
					
				if($lb_valido)
				{
					$io_datastore->data=$la_tenencia;
					$li_totalfilas=$io_datastore->getRowCount("codest");
				}
				else
				{
					$li_totalfilas=0;
				}
				?>
                <select name="cmbestado" size="1" id="cmbestado" onChange="javascript:ue_llenarcmbmunicipio();">
                  <option value="s1">Seleccione...</option>
                  <?php
					for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
					{
					 $ls_codigo=$io_datastore->getValue("codest",$li_i);
					 $ls_desest=$io_datastore->getValue("desest",$li_i);
					 if ($ls_codigo==$ls_codest)
					 {
						  print "<option value='$ls_codigo' selected>$ls_desest</option>";
					 }
					 else
					 {
						  print "<option value='$ls_codigo'>$ls_desest</option>";
					 }
					} 
	           ?>
                </select>
      <input name="hidestado" type="hidden" id="hidestado" value="<?php print $ls_codest ?>"></td>
            <td width="60"  height="22" align="right" ><div align="right">Municipio            </div></td>
            <td width="96"  height="22"><?php
					if($ls_codest=="")
						$lb_valido=false;
					else			
						$lb_valido=$io_obra->uf_llenarcombo_municipio($ls_codest,&$la_municipio);
						
					if($lb_valido)
					{
						$io_datastore->data=$la_municipio;
						$li_totalfilas=$io_datastore->getRowCount("codmun");
					}
					else{$li_totalfilas=0;}										
			    ?>
              <select name="cmbmunicipio" size="1" id="cmbmunicipio" onChange="javascript:ue_llenarcmbparroquia();">
                <option value="s1">Seleccione...</option>
                <?php
					for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
					{
						 $ls_codigo=$io_datastore->getValue("codmun",$li_i);
						 $ls_denmun=$io_datastore->getValue("denmun",$li_i);
						 if ($ls_codigo==$ls_codmun)
						 {
							  print "<option value='$ls_codigo' selected>$ls_denmun</option>";
						 }
						 else
						 {
							  print "<option value='$ls_codigo'>$ls_denmun</option>";
						 }
					} 
	            ?>
              </select>
              <input name="hidmunicipio" type="hidden" id="hidmunicipio6" value="<?php print $ls_codmun ?>"></td>
            <td align="right"><div align="right">Parroquia</div></td>
            <td align="right"><div align="left">
                <?php
			    if($ls_codmun=="")
					$lb_valido=false;
				else				
					$lb_valido=$io_obra->uf_llenarcombo_parroquia($ls_codest,$ls_codmun,$la_parroquia);
					
					if($lb_valido)
					{
						$io_datastore->data=$la_parroquia;
						$li_totalfilas=$io_datastore->getRowCount("codpar");
					}										
					else{$li_totalfilas=0;}
			    ?>
                <select name="cmbparroquia" size="1" id="cmbparroquia" onChange="javascript:ue_llenarcmbcomunidad();">
                  <option value="s1">Seleccione...</option>
                  <?php
					for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
					{
					 $ls_codigo=$io_datastore->getValue("codpar",$li_i);
					 $ls_denpar=$io_datastore->getValue("denpar",$li_i);
					 if ($ls_codigo==$ls_codpar)
					 {
						  print "<option value='$ls_codigo' selected>$ls_denpar</option>";
					 }
					 else
					 {
						  print "<option value='$ls_codigo'>$ls_denpar</option>";
					 }
					} 
	            ?>
                </select>
                <input name="hidparroquia" type="hidden" id="hidparroquia6" value="<?php print $ls_codpar ?>">
            </div></td>
            <td align="right"><div align="right">Comunidad</div></td>
            <td><?php
			    if($ls_codpar=="")
					$lb_valido=false;
				else				
					$lb_valido=$io_obra->uf_llenarcombo_comunidad($ls_codest,$ls_codmun,$ls_codpar,$la_municipio);
					
					if($lb_valido)
					{
						$io_datastore->data=$la_municipio;
						$li_totalfilas=$io_datastore->getRowCount("codcom");
					}										
					else{$li_totalfilas=0;}
			    ?>
                <select name="cmbcomunidad" size="1" id="cmbcomunidad" >
                  <option value="s1">Seleccione...</option>
                  <?php
					for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
					{
						 $ls_codigo=$io_datastore->getValue("codcom",$li_i);
						 $ls_nomcom=$io_datastore->getValue("nomcom",$li_i);
						 if ($ls_codigo==$ls_codcom)
						 {
							  print "<option value='$ls_codigo' selected>$ls_nomcom</option>";
						 }
						 else
						 {
							  print "<option value='$ls_codigo'>$ls_nomcom</option>";
						 }
					} 
	            ?>
                </select>
                <input name="hidcomunidad" type="hidden" id="hidcomunidad" value="<?php print $ls_codcom ?>">            </td>
          </tr>
          <tr class="formato-blanco">
            <td>&nbsp;</td>
            <td colspan="2">&nbsp;</td>
            <td height="16" colspan="6" align="left">&nbsp;</td>
          </tr>
          <tr class="formato-blanco">
            <td><div align="left"></div></td>
            <td colspan="2"><div align="right">Direcci&oacute;n</div></td>
            <td height="22" colspan="6" align="left"><div align="left">
                <input name="txtdirobr" type="text" id="txtdirobr" style="text-align:left" value="<?php print $ls_dirobr ?>" size="60" maxlength="200">
            </div></td>
          </tr>
          <tr class="formato-blanco">
            <td height="16">&nbsp;</td>
            <td colspan="8">&nbsp;</td>
          </tr>
          <tr class="formato-blanco">
            <td height="18" rowspan="2">&nbsp;</td>
            <td colspan="2"><div align="right">Observaci&oacute;n</div></td>
            <td colspan="6"><textarea name="txtobsobr" cols="60" rows="2" id="textarea" onKeyDown="textCounter(this,254)"  onKeyUp="textCounter(this,254)" style="text-align:left"><?php print $ls_obsobr ?></textarea></td>
          </tr>
          <tr class="formato-blanco">
            <td colspan="2">&nbsp;</td>
            <td colspan="6">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="7">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="7"><div align="center">
          <table width="610" border="0" align="center" cellpadding="0" cellspacing="0"  class="sin-borde">
            <tr class="formato-blanco">
              <td width="25" height="13">&nbsp;</td>
              <td width="585"><div align="left">
			  
			  
			  
			  
			  
			  
			  
			  	<a href="javascript:ue_catpartida();">
			  		<img src="../shared/imagebank/mas.gif" width="9" height="17" border="0">
				</a>
				<a href="javascript:ue_catpartida();">Agregar Detalle</a>
			  </div></td>
            </tr>
            <tr align="center" class="formato-blanco">
              <td colspan="2"><?php $io_grid->makegrid($li_filaspartidas,$la_columpartidas,$la_objectpartidas,$li_anchopartidas,$ls_titulopartidas,$ls_nametable);?> </td>
            </tr>
			<input name="filaspartidas" type="hidden" id="filaspartidas" value="<?php print $li_filaspartidas;?>">
			<input name="hidremoverpartida" type="hidden" id="hidremoverpartida" value="<?php print $li_removerpartida;?>">
          </table>
        </div></td>
      </tr>
      <tr class="formato-blanco">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><div align="right">Monto Total de la Obra</div></td>
        <td colspan="2"><input name="txtmonto" type="text" id="txtmonto"  style="text-align:right" value="<?php print $li_monto ?>"  readonly="true" size="22" maxlength="21"  ></td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="7">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="7"><div align="center">
          <table width="609" border="0" align="center" cellpadding="0" cellspacing="0"  class="sin-borde">
            <tr class="formato-blanco">
              <td width="14" height="11">&nbsp;</td>
              <td width="595"><a href="javascript:ue_catfuentefinanciamiento();"><img src="../shared/imagebank/mas.gif" width="9" height="17" border="0"></a><a href="javascript:ue_catfuentefinanciamiento();">Agregar Detalle </a></td>
            </tr>
            <tr align="center" class="formato-blanco">
              <td height="11" colspan="2">
			  <?php $io_grid->makegrid($li_filasfuentes,$la_columfuentes,$la_objectfuentes,$li_anchofuentes,$ls_titulofuentes,$ls_nametable);?>
			  </td>
			  <input name="filasfuentes" type="hidden" id="filasfuentes" value="<?php print $li_filasfuentes;?>">
				<input name="hidremoverfuente" type="hidden" id="hidremoverfuente" value="<?php print $li_removerfuente;?>">
            </tr>
          </table>
        </div></td>
      </tr>
      <tr class="formato-blanco">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><div align="right">Total Financiamiento</div></td>
        <td colspan="2"><input name="txttotalfuente" type="text" id="txttotalfuente" style="text-align:right" value="<?php print $ls_totalfuente;?>" size="21" maxlength="25" readonly="true" ></td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="7">            <input name="reporte" type="hidden" id="reporte" value="<?php print $ls_reporte;?>">
</td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="7"><div align="center"><a href="#inicio">Volver Arriba</a></div></td>
      </tr>
      <tr class="formato-blanco">
        <td colspan="7">&nbsp;</td>
      </tr>
    </table>
    <div align="center"></div>
  </form>
  <?
   if($ls_operacion=="ue_cargarpartida" || $ls_operacion=="ue_removerpartida" || $ls_operacion=="ue_cargarfuente" || $ls_operacion=="ue_removerfuente")
   {
   ?>
   	<script language="javascript">
		f=document.form1;
		li_i=f.filaspartidas.value-1;		
		scrollTo(0,350);
	</script>
	<?
   }
   ?>
</body>
<script language="javascript">
//Funciones para llenar los combos/////////////
function ue_llenarcmbestado()
{
	document.form1.operacion.value="pais";
	document.form1.submit();
}

function ue_llenarcmbmunicipio()
{
	f=document.form1;
	f.action="sigesp_sob_d_obra.php";
	f.operacion.value="municipio";
	f.submit();
}

function ue_llenarcmbparroquia()
{
	f=document.form1;
	f.action="sigesp_sob_d_obra.php";
	f.operacion.value="parroquia";
	f.submit();
}

function ue_llenarcmbcomunidad()
{
	f=document.form1;
	f.action="sigesp_sob_d_obra.php";
	f.operacion.value="comunidad";
	f.submit();
}
///////Fin de las funciones para llenar los combos///////////////

///////Funciones para llamar catalogos////////////////
function ue_catpropietario()
{
	f=document.form1;
	f.operacion.value="";			
	pagina="sigesp_cat_organismo.php";
	popupWin(pagina,"catalogo",520,200);
	//window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=200,resizable=yes,location=no");
}

function ue_catsistemaconstructivo()
{
	f=document.form1;
	f.operacion.value="";			
	pagina="sigesp_cat_sistemaconstructivo.php";
	popupWin(pagina,"catalogo",620,200);
//	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=200,resizable=yes,location=no");
}

function ue_cattipoobra()
{
	f=document.form1;
	f.operacion.value="";			
	pagina="sigesp_cat_tipoobra.php";
	popupWin(pagina,"catalogo",520,200);
//	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=200,resizable=yes,location=no");
}

function ue_cattipoestructura()
{
	f=document.form1;
	f.operacion.value="";			
	pagina="sigesp_cat_tipoestructura.php";
	popupWin(pagina,"catalogo",520,200);
//	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=200,resizable=yes,location=no");
}

function ue_catpartida()
{		
	f=document.form1;
	if (f.txtcodobr.value!="")
	{
		f.operacion.value="";	
		var hidopener="obra"
		pagina="sigesp_cat_partida.php?hidopener="+hidopener;
		popupWin(pagina,"catalogo",720,400);
		//window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=720px,height=400px,resizable=yes,location=no,top=0,left=0,dependent=yes");
	}
	else
	{
		alert("Debe seleccionar una nueva Obra!!!");		
	}
}

function ue_catfuentefinanciamiento()
{
	f=document.form1;	
	if (parseInt(f.txtmonto.value)>0 && f.txtcodobr.value!="")
	{			
		f.operacion.value="";
		pagina="sigesp_cat_fuentefinan.php";
		popupWin(pagina,"catalogo",520,200);
//		window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=200,resizable=yes,location=no,status=yes,top=0,left=0");
	}
	else
	{
		if (f.txtcodobr.value=="")
		{
			alert("Debe seleccionar una nueva Obra!!!");
		}
		else
		{
			alert("Debe especificar un monto para la Obra!!!");
		}
		
		
	}
}

function ue_grabarfoto()
{
	var opener="obra";
	pagina="sigesp_sob_d_grabarfotos.php?opener="+opener;
	popupWin(pagina,"catalogo",520,220);
}

function ue_verfotos()
{
	var opener="obra";
	var codigo=document.form1.txtcodobr.value;
	pagina="sigesp_sob_d_verfotos.php?opener="+opener+"&campocodigo="+codigo;
	popupWin(pagina,"catalogo2",800,800);

}
///////Fin de las Funciones para para llamar catalogos/////

//////Funciones para cargar datos provenientes de catalogos///////
function ue_cargarpropietario(codigo,nombre,descripcion)
{
	f=document.form1;
	f.txtcodpro.value=codigo;
	f.txtnompro.value=nombre;
}
function ue_cargarsistemaconstructivo(codigo,nombre,descripcion)
{
	f=document.form1;
	f.txtcodsiscon.value=codigo;
	f.txtnomsiscon.value=nombre;
}

function ue_cargartipoobra(codigo,nombre,descripcion)
{
	f=document.form1;
	f.txtcodtob.value=codigo;
	f.txtnomtob.value=nombre;
}

function ue_cargartipoestructura(codigo,nombre,descripcion)
{
	f=document.form1;
	f.txtcodtipest.value=codigo;
	f.txtnomtipest.value=nombre;
}

function ue_cargarpartida(codigo,nombre,descripcion,codunidad,nomunidad,prepar,codcovpar)
{
	f=document.form1;
	f.operacion.value="ue_cargarpartida";
	
	lb_existe=false;
	
	for(li_i=1;li_i<=f.filaspartidas.value && !lb_existe;li_i++)
	{
		ls_codigo=eval("f.txtcodpar"+li_i+".value");
		//alert("codigo nuevo '"+codigo+"' codigo de la comparacion '"+eval("f.txtcodpar"+f.filaspartidas.value+".value")+"'");
		if(ls_codigo==codigo)
		{
			alert("Detalle ya existe!!!");
			lb_existe=true;
		}
	}	
	
	if(!lb_existe)
	{
		eval("f.txtcodpar"+f.filaspartidas.value+".value='"+codigo+"'");
		eval("f.txtnompar"+f.filaspartidas.value+".value='"+nombre+"'");
		eval("f.txtnomuni"+f.filaspartidas.value+".value='"+nomunidad+"'");
		eval("f.txtprepar"+f.filaspartidas.value+".value='"+prepar+"'");		
		f.submit();
	}
}

function ue_cargarfuente(codigo,nombre)
{
	f=document.form1;
	f.operacion.value="ue_cargarfuente";	
	lb_existe=false;
	
	for(li_i=1;li_i<=f.filasfuentes.value && !lb_existe;li_i++)
	{
		ls_codigo=eval("f.txtcodfuefin"+li_i+".value");
		ls_nombre=eval("f.txtnomfuefin"+li_i+".value");
		if(ls_nombre==nombre)
		{
			alert("Detalle ya existe!!!");
			lb_existe=true;
		}
	}	
	
	if(!lb_existe)
	{
		eval("f.txtcodfuefin"+f.filasfuentes.value+".value='"+codigo+"'");
		eval("f.txtnomfuefin"+f.filasfuentes.value+".value='"+nombre+"'")
		eval("f.txtmonfuefin"+f.filasfuentes.value+".value=''")
		f.submit();
	}
}

function ue_cargarobra(ls_codigo,ls_descripcion,ls_estado,ls_codest,ls_codten,ls_codtipest,ls_codpai,ls_codmun,ls_codpar,ls_codcom,
			  		   ls_codsiscon,ls_codpro,ls_codtob,ls_dirobr,ls_obsobr,ls_resobr,ld_monto,ls_feccreobr,ls_nompro,
			  		   ls_fechainicio,ls_fechafin,ls_nomten,ls_nomtipest,ls_nomsiscon,ls_nomtob,ls_estado,ls_codpais)
{
	f=document.form1;
	f.txtcodobr.value=ls_codigo;
	f.txtnomobr.value=ls_descripcion;
	f.hidestado.value=ls_codest;
	f.hidtenencia.value=ls_codten;
	f.txtcodtipest.value=ls_codtipest;
	f.hidmunicipio.value=ls_codmun;
	f.hidparroquia.value=ls_codpar;
	f.hidcomunidad.value=ls_codcom;
	f.txtcodsiscon.value=ls_codsiscon;
	f.txtcodpro.value=ls_codpro;
	f.txtcodtob.value=ls_codtob;
	f.txtdirobr.value=ls_dirobr;
	f.txtobsobr.value=ls_obsobr;
	f.txtresobr.value=ls_resobr;
	f.txtmonto.value=uf_convertir(ld_monto);
	f.txtfeccreobr.value=ls_feccreobr;
	f.txtnompro.value=ls_nompro;
	f.txtfeciniobr.value=ls_fechainicio;
	f.txtfecfinobr.value=ls_fechafin;
	f.txtnomtipest.value=ls_nomtipest;
	f.txtnomsiscon.value=ls_nomsiscon;
	f.txtnomtob.value=ls_nomtob;
	f.txtstaobr.value=ls_estado;
	f.operacion.value="ue_cargarobra";
	f.hidstatus.value="C";
	f.hidpais.value=ls_codpais;
	f.submit();
}			  
						 
function ue_removerpartida(li_fila)
{
	f=document.form1;
	f.hidremoverpartida.value=li_fila;
	f.operacion.value="ue_removerpartida"
	f.action="sigesp_sob_d_obra.php";
	f.submit();
}

function ue_removerfuente(li_fila)
{
	f=document.form1;
	f.hidremoverfuente.value=li_fila;
	//f.txttotalfuente.value=uf_convertir_monto(f.txttotalfuente.value);
	validamonto((eval("f.txtmonfuefin"+li_fila+".value")),li_fila);
	f.operacion.value="ue_removerfuente"
	f.action="sigesp_sob_d_obra.php";
	f.submit();
}

//////Fin de las funciones para cargar datos provenientes de catalogos///

function validamonto (txt,filaeliminar)
{
	if (txt.value=="")
	{
		txt.value="0,00";
	}
	f=document.form1;
	var ld_totalmonto=0.00;
	if (f.txttotalfuente.value!="")
	{
		var auxTotal=f.txttotalfuente.value;
	}
	else
	{
		var auxTotal="0,00";
	}
	
	for(li_i=1;li_i<f.filasfuentes.value;li_i++)
	{	
			monto=eval("f.txtmonfuefin"+li_i+".value");
			if(monto!="" && monto!="0,00" && monto!="0,0" && monto!="0," && monto!="0")
				ld_totalmonto=ld_totalmonto + parseFloat(uf_convertir_monto(eval("f.txtmonfuefin"+li_i+".value")));			
	}
	
	if (filaeliminar < 500)
	{
		monto=eval("f.txtmonfuefin"+filaeliminar+".value");
		if(monto!="" && monto!="0,00" && monto!="0,0" && monto!="0," && monto!="0")
			ld_totalmonto=ld_totalmonto-parseFloat(uf_convertir_monto(eval("f.txtmonfuefin"+filaeliminar+".value")));		
	}	
	if (ld_totalmonto > uf_convertir_monto(f.txtmonto.value))
	{
		alert("El total de los montos financiados no puede ser mayor al monto de la Obra!!!");
		txt.value="0,00";
		txt.focus();
		f.txttotalfuente.value=auxTotal;
	}
	else
	{
		f.txttotalfuente.value=uf_convertir(ld_totalmonto);
	}	
}

function valida_monto_total(txt,filaeliminar)
{
	f=document.form1;
	//ue_getformat(txt);
	numero=new Array();
	montototal=0;
	for(li_i=1;li_i<f.filaspartidas.value;li_i++)
	{
		cantidad=eval("f.txtcanpar"+li_i+".value;");
		if(li_i!=filaeliminar && cantidad!="" && cantidad!="0,00" && cantidad!="0,0" && cantidad!="0," && cantidad!="0")
		{
			cantidad=parseFloat(uf_convertir_monto(eval("f.txtcanpar"+li_i+".value;")));
			precio=parseFloat(uf_convertir_monto(eval("f.txtprepar"+li_i+".value;")));
			montoparcial=cantidad*precio;
			montototal=montototal+montoparcial;			
		}
	}
	f.txtmonto.value=uf_convertir(montototal);
	
}

function currencyFormat(fld, milSep, decSep, e) 
{ 
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

function uf_llamada_validarmonto(fld)
{
	cadena=fld.id;
	if(cadena.charAt(6)=="f")
   	{
		validamonto(fld,500);
	}
	else
	{
		if(cadena.charAt(6)=="p")
		{
			valida_monto_total(fld,50000);
		}
	}
}

function validamontolleno(cual)
{
	lb_valido=true;
	if(cual=="fuente")
	{
		for(li_i=1;li_i<f.filasfuentes.value;li_i++)
		{
			if((eval("f.txtmonfuefin"+li_i+".value")  == "") || (parseFloat(uf_convertir_monto(eval("f.txtmonfuefin"+li_i+".value")))  == 0))
			{
				lb_valido=false;
			}
		}	
	}
	else
	{
		for(li_i=1;li_i<f.filaspartidas.value;li_i++)
		{
			if ((eval("f.txtcanpar"+li_i+".value")  == "") || (parseFloat(uf_convertir_monto(eval("f.txtcanpar"+li_i+".value")))  == 0))
			{
				lb_valido=false;
			}
		}	
	}
	return lb_valido;
}
//////////////////////////////Fin de las funciones de validacion
function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{
		  f.operacion.value="ue_nuevo";
		  f.txtnomobr.focus(true);
		  f.action="sigesp_sob_d_obra.php";
		  f.submit();
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

		/*Function:  ue_buscar()
	 *
	// *Descripción: Función que se encarga de hacer el llamado al catalogo de obras */
function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		var estado="";
		pagina="sigesp_cat_obra.php?estado="+estado;
		popupWin(pagina,"catalogo",860,400);
//		window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=350,resizable=yes,location=no,status=no,top=0,left=0");
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
} 
/*Fin de la Función ue_buscar()*/

/*Function ue_guardar
	//Funcion que se encarga de guardar los datos de la obra, revisando previamente la validez de los datos*/


function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_status=f.hidstatus.value;
	li_monto=f.txtmonto.value;
	li_montofuente=f.txttotalfuente.value;
	li_monto=ue_formato_calculo(li_monto);
	li_montofuente=ue_formato_calculo(li_montofuente);
	if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
	{
		with(form1)
		{
			if(ue_valida_null(txtcodobr,"Código de la Obra")==false)
			{
			}
			else
			{
				if(ue_valida_null(txtnomobr,"Descripción de la Obra")==false)
				{
					f.txtnomobr.focus();
				}
				else
				{
					if(ue_valida_null(txtcodpro,"Código del Propietario")==false)
					{
						f.txtcodpro.focus();
					}
					else
					{
						if(ue_valida_null(txtresobr,"Responsable de la Obra")==false)
						{
							f.txtresobr.focus();
						}
						else
						{
							if(ue_valida_null(cmbtenencia,"Tenencia")==false)
							{
								f.cmbtenencia.focus();
							}
							else
							{
								if(ue_valida_null(txtfeciniobr,"Fecha de Inicio")==false)
								{
									f.txtfeciniobr.focus();
								}
								else
								{
									if(ue_valida_null(txtfecfinobr,"Fecha de Fin")==false)
									{
										f.txtfecfinobr.focus();
									}
									else
									{
										if(ue_valida_null(cmbestado,"Estado")==false)
										{
											f.comboestado.focus();
										}
										else
										{
											if(ue_valida_null(cmbmunicipio,"Municipio")==false)
											{
												f.combomunicipio.focus();
											}
											else
											{
												if(ue_valida_null(cmbparroquia,"Parroquia")==false)
												{
													f.comboparroquia.focus();
												}
												else
												{
													if(ue_valida_null(cmbcomunidad,"Comunidad")==false)
													{
														f.combocomunidad.focus();
													}
													else
													{
														if(ue_valida_null(txtdirobr,"Dirección")==false)
														{
															f.txtdirobr.focus();
														}
														else
														{
															if(ue_valida_null(txtmonto,"Monto")==false)
															{
																f.txtmonto.focus();
															}
															else
															{															
																	if(validamontolleno("fuente")==true)
																	{
																		if(validamontolleno("partida")==true)
																		{																		
																			if(ue_comparar_intervalo('txtfeciniobr','txtfecfinobr','La fecha de inicio de la Obra debe ser menor que la fecha de fin'))
																			{
																				if(parseFloat(li_monto)==parseFloat(li_montofuente))
																				{
																					f.action="sigesp_sob_d_obra.php";
																					f.operacion.value="ue_guardar";
																					f.submit();
																				}
																				else
																				{
																					alert("El total de la obra debe coincidir con el total del financiamiento.");
																				}
																			}	
																		}
																		else
																		{
																			alert ("Debe Indicar todas las cantidades de las Partidas!!!");
																		}
																		
																	}														
																	else
																	{
																		alert ("Debe Indicar el monto de la Fuente de Financiamiento!!!");
																	}																									
															}
														}
													}
												}
											}
										}
										
									}
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
		if (f.txtcodobr.value=="")
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
				f.action="sigesp_sob_d_obra.php";
				f.submit();
			}
		}	   
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_print()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	valido=true;
	if(li_imprimir==1)
	{	
		codobr=f.txtcodobr.value;		
		estatus=f.hidstatus.value;
		reporte=f.reporte.value;
		if((codobr=="") || (estatus!="C"))
		{
			alert("Debe Seleccionar una Obra.");
		}
		else
		{
			pagina="reportes/"+reporte+"?codobr="+codobr;
			window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}		
}

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>