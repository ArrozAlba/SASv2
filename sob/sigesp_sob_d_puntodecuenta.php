<?php
session_start();
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_conexion.php'";
	print "</script>";		
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Elaboraci&oacute;n de Punto de Cuenta</title>
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
.Estilo1 {color: #666666}
-->
</style><meta http-equiv="Content-Type" content="text/html; charset="></head>
<body link="#006699" vlink="#006699" alink="#006699">
<?Php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SOB";
	$ls_ventanas="sigesp_sob_d_puntodecuenta.php";

	$la_seguridad["empresa"]=$ls_empresa;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
			$la_permisos=$io_seguridad->uf_sss_load_permisossigesp();
		}
		else
		{
			$ls_permisos=             $_POST["permisos"];
			$la_permisos["leer"]=     $_POST["leer"];
			$la_permisos["incluir"]=  $_POST["incluir"];
			$la_permisos["cambiar"]=  $_POST["cambiar"];
			$la_permisos["eliminar"]= $_POST["eliminar"];
			$la_permisos["imprimir"]= $_POST["imprimir"];
			$la_permisos["anular"]=   $_POST["anular"];
			$la_permisos["ejecutar"]= $_POST["ejecutar"];
		}
	}
	else
	{
		$la_permisos["leer"]="";
		$la_permisos["incluir"]="";
		$la_permisos["cambiar"]="";
		$la_permisos["eliminar"]="";
		$la_permisos["imprimir"]="";
		$la_permisos["anular"]="";
		$la_permisos["ejecutar"]="";
		$ls_permisos=$io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_permisos);
	}

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

require_once("../shared/class_folder/class_sql.php");
require_once("class_folder/sigesp_sob_c_contrato.php");
$io_contrato=new sigesp_sob_c_contrato();
require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
require_once("../shared/class_folder/class_funciones.php");
$io_function=new class_funciones();
require_once ("class_folder/sigesp_sob_c_funciones_sob.php");
$io_funsob= new sigesp_sob_c_funciones_sob();
require_once("class_folder/sigesp_sob_class_obra.php");
$io_obra=new sigesp_sob_class_obra(); 
require_once("../shared/class_folder/evaluate_formula.php");
$io_formula=new evaluate_formula();
require_once("../shared/class_folder/class_datastore.php");
$io_datastore=new class_datastore();
require_once("../shared/class_folder/grid_param.php");
$io_grid=new grid_param();
require_once("class_folder/sigesp_sob_c_puntodecuenta.php");
$io_puntodecuenta=new sigesp_sob_c_puntodecuenta();

$ls_titulo="Cuentas de Gastos";
$li_ancho=600;
$ls_nametable="grid";
$la_columna[1]="Monto";
$la_columna[2]="I.V.A.";
$la_columna[3]="Código Presupuestario";
$la_columna[4]="Cuenta";
$la_columna[5]="Monto";
$la_columna[6]="Edición";

$ls_titulocargos="Cargos";
$li_anchocargos=600;
$ls_nametable="grid3";
$la_columcargos[1]="Código";
$la_columcargos[2]="Denominación";
$la_columcargos[3]="Monto";
$la_columcargos[4]="Edición";


function uf_limpiar()
{
	global $ls_operacion, $ls_datosobra,$ls_codobr,$ls_monobr,$ls_montasi,$ls_feciniobr,$ls_fecfinobr,$ls_estobr,$ls_parobr,
			$ls_munobr,$ls_comobr,$ls_codpuncue,$ls_estpuncue,$ls_fecpuncue,$ls_despuncue,$ls_rempuncue,$ls_asupuncue,$ls_codpropuncue,
			$ls_nompropuncue,$ls_replegcon,$ls_lapejepuncue,$ls_monnetpuncue,$ls_monivapuncue,$ls_monbrupuncue,$ls_porantpuncue,
			$ls_monantpuncue,$ls_basimp,$ls_obspuncue,$li_filas,$li_filaeliminar,$la_objeto,$li_filascargos,$li_removercargo,$la_objectcargos,$ls_porivapuncue,$ls_codunidad;	
	$ls_operacion="";
	$ls_datosobra="OCULTAR";	
	$ls_monobr="";
	$ls_montasi="";
	$ls_feciniobr="";
	$ls_fecfinobr="";
	$ls_estobr="";
	$ls_parobr="";
	$ls_munobr="";
	$ls_comobr="";
	$ls_codpuncue="";
	$ls_estpuncue="";
	$ls_fecpuncue="";
	$ls_despuncue="";
	$ls_rempuncue="";
	$ls_asupuncue="";
	$ls_codpropuncue="";
	$ls_nompropuncue="";
	$ls_replegcon="";
	$ls_lapejepuncue="0,00";
	$ls_monnetpuncue="0,00";
	$ls_monivapuncue="0,00";
	$ls_monbrupuncue="0,00";
	$ls_porantpuncue="0,00";
	$ls_monantpuncue="0,00";
	$ls_basimp="0,00";
	$ls_obspuncue="";
	$ls_codunidad="";
	$li_filas=1;
	$li_filaeliminar=0;
	$ls_porivapuncue="0,00";
	$la_objeto=array();
	$la_objeto[1][1]="<input type=text name=xxx1 id=xxx1 size=1 class=sin-borde readonly>";//"&nbsp;&nbsp;<input type=radio name=concepto1 id=monto1  class=sin-borde onClick=javascript:alert(this.id) >";
	$la_objeto[1][2]="<input type=text name=xxx1 id=xxx1 size=1 class=sin-borde readonly>";//"&nbsp;<input type=radio name=concepto1 id=iva1 class=sin-borde>";
	$la_objeto[1][3]="<input name=txtcodcue1 type=text id=txtcodcue1 class=sin-borde style= text-align:center size=40 readonly><input name=codest11 type=hidden id=codest11><input name=codest21 type=hidden id=codest21><input name=codest31 type=hidden id=codest31><input name=codest41 type=hidden id=codest41><input name=codest51 type=hidden id=codest51>";
	$la_objeto[1][4]="<input name=txtnomcue1 type=text id=txtnomcue1 class=sin-borde style= text-align:center size=10 readonly>";
	$la_objeto[1][5]="<input name=txtmoncue1 type=text id=txtmoncue1 class=sin-borde style= text-align:center size=20 readonly><input name=disponible1 type=hidden id=disponible1>";
	$la_objeto[1][6]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";		
	
	$li_filascargos=1;
	$la_objectcargos[1][1]="<input name=txtcodcar1 type=text id=txtcodcar1 class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectcargos[1][2]="<input name=txtnomcar1 type=text id=txtnomcar1 class=sin-borde style= text-align:left size=50 readonly>";
	$la_objectcargos[1][3]="<input name=txtmoncar1 type=text id=txtmoncar1 class=sin-borde style= text-align:center size=20 readonly><input name=formula1 type=hidden id=formula1><input name=prog1 type=hidden id=prog1><input name=spgcuenta1 type=hidden id=spgcuenta1>";
	$la_objectcargos[1][4]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
}

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];	
	$li_filas=$_POST["hidfilas"];
	if($ls_operacion!="ue_cargarcuenta" && $ls_operacion!="ue_removercuenta")
	{
		for($li_i=1;$li_i<$li_filas;$li_i++)
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
			if(array_key_exists("concepto".$li_i."",$_POST))
			{
				$ls_chk=$_POST["concepto".$li_i];
				if($ls_chk=="monto")
				{
					$la_objeto[$li_i][1]="&nbsp;&nbsp;<input type=radio name=concepto".$li_i." value='monto' id=monto".$li_i." class=sin-borde checked style=cursor:default onClick=javascript:ue_cargarmonto(this)>";
					$la_objeto[$li_i][2]="&nbsp;<input type=radio name=concepto".$li_i." id=iva".$li_i."  value='iva' class=sin-borde  onClick=javascript:ue_verificariva(this) style=cursor:default>";
				}
				else
				{
					$la_objeto[$li_i][1]="&nbsp;&nbsp;<input type=radio name=concepto".$li_i." value='monto' id=monto".$li_i." class=sin-borde style=cursor:default onClick=javascript:ue_cargarmonto(this)>";
					$la_objeto[$li_i][2]="&nbsp;<input type=radio name=concepto".$li_i." id=iva".$li_i."  value='iva' class=sin-borde checked onClick=javascript:ue_verificariva(this) style=cursor:default>";
				}
			}
			else
			{
				$la_objeto[$li_i][1]="&nbsp;&nbsp;<input type=radio name=concepto".$li_i." value='monto' id=monto".$li_i." class=sin-borde style=cursor:default onClick=javascript:ue_cargarmonto(this)>";
				$la_objeto[$li_i][2]="&nbsp;<input type=radio name=concepto".$li_i." id=iva".$li_i."  value='iva' class=sin-borde onClick=javascript:ue_verificariva(this) style=cursor:default>";
			}
			
			$la_objeto[$li_i][3]="<input name=txtcodcue".$li_i." type=text id=txtcodcue".$li_i." class=sin-borde style= text-align:center size=40 value='".$ls_codigo."' readonly><input name=codest1".$li_i." type=hidden id=codest1".$li_i." value='".$ls_codest1."'><input name=codest2".$li_i." type=hidden id=codest2".$li_i." value='".$ls_codest2."'><input name=codest3".$li_i." type=hidden id=codest3".$li_i." value='".$ls_codest3."'><input name=codest4".$li_i." type=hidden id=codest4".$li_i." value='".$ls_codest4."'><input name=codest5".$li_i." type=hidden id=codest5".$li_i." value='".$ls_codest5."'>";
			$la_objeto[$li_i][4]="<input name=txtnomcue".$li_i." type=text id=txtnomcue".$li_i." class=sin-borde style= text-align:center size=10 value='".$ls_nombre."' readonly >";
			$la_objeto[$li_i][5]="<input name=txtmoncue".$li_i." type=text id=txtmoncue".$li_i." class=sin-borde size=20 style= text-align:right value='".$ls_moncar."' onKeyPress=return(validaCajas(this,'d',event,20)) onKeyUp=javascript:ue_validardispo(this) onBlur=javascript:ue_getformat(this) ><input name=disponible".$li_i." type=hidden id=disponible".$li_i." value='".$ls_disponible."'>";
			$la_objeto[$li_i][6]="<a href=javascript:ue_removercuenta(".$li_i.");><div align=center><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></div></a>";
		}	
		$la_objeto[$li_filas][1]="<input type=text name=xxx".$li_filas." id=xxx".$li_filas." size=1 class=sin-borde readonly>";
		$la_objeto[$li_filas][2]="<input type=text name=xxx".$li_filas." id=xxx".$li_filas." size=1 class=sin-borde readonly>";
		$la_objeto[$li_filas][3]="<input name=txtcodcue".$li_filas." type=text id=txtcodcue".$li_filas." class=sin-borde style= text-align:center size=40 readonly><input name=codest1".$li_filas." type=hidden id=codest1".$li_filas."><input name=codest2".$li_filas." type=hidden id=codest2".$li_filas."><input name=codest3".$li_filas." type=hidden id=codest3".$li_filas."><input name=codest4".$li_filas." type=hidden id=codest4".$li_filas."><input name=codest5".$li_filas." type=hidden id=codest5".$li_filas.">";
		$la_objeto[$li_filas][4]="<input name=txtnomcue".$li_filas." type=text id=txtnomcue".$li_filas." class=sin-borde style= text-align:left size=10 readonly>";
		$la_objeto[$li_filas][5]="<input name=txtmoncue".$li_filas." type=text id=txtmoncue".$li_filas." class=sin-borde size=20 style= text-align:center readonly><input name=disponible".$li_filas." type=hidden id=disponible".$li_filas.">";
		$la_objeto[$li_filas][6]="<input name=txtvacio".$li_filas." type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	}
	
	if ($ls_operacion != "ue_cargarcargo" && $ls_operacion != "ue_removercargo")
	{
		$li_filascargos=$_POST["filascargos"];
		for($li_i=1;$li_i<$li_filascargos;$li_i++)
		{		
			$ls_codigo=$_POST["txtcodcar".$li_i];
			$ls_nombre=$_POST["txtnomcar".$li_i];
			$ls_moncue=$_POST["txtmoncar".$li_i];
			$ls_formula=$_POST["formula".$li_i];
			$ls_prog=$_POST["prog".$li_i];
			$ls_spgcuenta=$_POST["spgcuenta".$li_i];
			$la_objectcargos[$li_i][1]="<input name=txtcodcar".$li_i." type=text id=txtcodcar".$li_i." class=sin-borde style= text-align:center size=5 value='".$ls_codigo."' readonly>";
			$la_objectcargos[$li_i][2]="<input name=txtnomcar".$li_i." type=text id=txtnomcar".$li_i." class=sin-borde style= text-align:left size=60 value='".$ls_nombre."' readonly >";
			$la_objectcargos[$li_i][3]="<input name=txtmoncar".$li_i." type=text id=txtmoncar".$li_i." class=sin-borde size=20 style= text-align:center value='".$ls_moncue."' readonly><input name=formula".$li_i." type=hidden id=formula".$li_i." value='".$ls_formula."'><input name=prog".$li_i." type=hidden id=prog".$li_i." value='".$ls_prog."'><input name=spgcuenta".$li_i." type=hidden id=spgcuenta".$li_i." value='".$ls_spgcuenta."'>";
			$la_objectcargos[$li_i][4]="<a href=javascript:ue_removercargo(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
		}	
		$la_objectcargos[$li_filascargos][1]="<input name=txtcodcar".$li_filascargos." type=text id=txtcodcar".$li_filascargos." class=sin-borde style= text-align:center size=5 readonly>";
		$la_objectcargos[$li_filascargos][2]="<input name=txtnomcar".$li_filascargos." type=text id=txtnomcar".$li_filascargos." class=sin-borde style= text-align:left size=50 readonly>";
		$la_objectcargos[$li_filascargos][3]="<input name=txtmoncar".$li_filascargos." type=text id=txtmoncar".$li_filascargos." class=sin-borde style= text-align:center size=20 readonly><input name=formula".$li_filascargos." type=hidden id=formula".$li_filascargos."><input name=prog".$li_filascargos." type=hidden id=prog".$li_filascargos."><input name=spgcuenta".$li_filascargos." type=hidden id=spgcuenta".$li_filascargos.">";
		$la_objectcargos[$li_filascargos][4]="<input name=txtvacio type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	}
}
else
{
	uf_limpiar();
	$ls_codobr="";
	$ls_operacion="";
}
if	(array_key_exists("hiddatosobra",$_POST)){$ls_datosobra=$_POST["hiddatosobra"]; }
if	(array_key_exists("txtcodobr",$_POST)){$ls_codobr=$_POST["txtcodobr"]; }
if	(array_key_exists("monobr",$_POST)){$ls_monobr=$_POST["monobr"]; }
if	(array_key_exists("txtfeciniobr",$_POST)){$ls_feciniobr=$_POST["txtfeciniobr"]; }
if	(array_key_exists("txtfecfinobr",$_POST)){$ls_fecfinobr=$_POST["txtfecfinobr"]; }
if	(array_key_exists("txtestobr",$_POST)){$ls_estobr=$_POST["txtestobr"]; }
if	(array_key_exists("txtparobr",$_POST)){$ls_parobr=$_POST["txtparobr"]; }
if	(array_key_exists("txtmunobr",$_POST)){$ls_munobr=$_POST["txtmunobr"]; }
if	(array_key_exists("txtnomcom",$_POST)){$ls_comobr=$_POST["txtnomcom"]; }
if	(array_key_exists("txtcodpuncue",$_POST)){$ls_codpuncue=$_POST["txtcodpuncue"]; }
if	(array_key_exists("txtestpuncue",$_POST)){$ls_estpuncue=$_POST["txtestpuncue"]; }
if	(array_key_exists("txtfecpuncue",$_POST)){$ls_fecpuncue=$_POST["txtfecpuncue"]; }
if	(array_key_exists("txtdespuncue",$_POST)){$ls_despuncue=$_POST["txtdespuncue"]; }
if	(array_key_exists("txtrempuncue",$_POST)){$ls_rempuncue=$_POST["txtrempuncue"]; }
if	(array_key_exists("txtasupuncue",$_POST)){$ls_asupuncue=$_POST["txtasupuncue"]; }
if	(array_key_exists("txtcodpropuncue",$_POST)){$ls_codpropuncue=$_POST["txtcodpropuncue"]; }
if	(array_key_exists("txtnompropuncue",$_POST)){$ls_nompropuncue=$_POST["txtnompropuncue"]; }
if	(array_key_exists("txtreplegcon",$_POST)){$ls_replegcon=$_POST["txtreplegcon"]; }
if	(array_key_exists("txtlapejepuncue",$_POST)){$ls_lapejepuncue=$_POST["txtlapejepuncue"]; }
if	(array_key_exists("txtmonnetpuncue",$_POST)){$ls_monnetpuncue=$_POST["txtmonnetpuncue"]; }
if	(array_key_exists("txtporivapuncue",$_POST)){$ls_porivapuncue=$_POST["txtporivapuncue"];  }
if	(array_key_exists("txtmonivapuncue",$_POST)){$ls_monivapuncue=$_POST["txtmonivapuncue"];  }
if	(array_key_exists("txtmonbrupuncue",$_POST)){$ls_monbrupuncue=$_POST["txtmonbrupuncue"]; }
if	(array_key_exists("txtporantpuncue",$_POST)){$ls_porantpuncue=$_POST["txtporantpuncue"]; }
if	(array_key_exists("txtmonantpuncue",$_POST)){$ls_monantpuncue=$_POST["txtmonantpuncue"]; }
if	(array_key_exists("txtobspuncue",$_POST)){$ls_obspuncue=$_POST["txtobspuncue"]; }	
if	(array_key_exists("hidfilas",$_POST)){$li_filas=$_POST["hidfilas"]; }		
if	(array_key_exists("hidfilaeliminar",$_POST)){$li_filaeliminar=$_POST["hidfilaeliminar"]; }	
if	(array_key_exists("cmblapejeunipuncue",$_POST)){$ls_codunidad=$_POST["cmblapejeunipuncue"]; }	
if	(array_key_exists("txtmontotobr",$_POST)){$ls_monobr=$_POST["txtmontotobr"]; }	
if	(array_key_exists("txtbasimp",$_POST)){$ls_basimp=$_POST["txtbasimp"]; }	

////////////////////////////////Operaciones de Actualizacion//////////////////////////////////////
if($ls_operacion=="ue_nuevo")//Abre una ficha de obra nueva
{
	uf_limpiar();
	$ls_codpuncue=$io_puntodecuenta->uf_generar_codigo($ls_codobr);
	
}
elseif($ls_operacion=="ue_guardar")
{
	$lb_validoselect=$io_puntodecuenta->uf_select_puntodecuenta($ls_codpuncue,$ls_codobr,$la_data);
	if($lb_validoselect===0)
	{
		$lb_validoguardar=$io_puntodecuenta->uf_guardar_puntodecuenta($ls_codobr ,$ls_codpuncue ,$ls_codpropuncue,$ls_codunidad,$ls_despuncue,$ls_rempuncue,
																	$ls_asupuncue,$ls_lapejepuncue,$ls_monnetpuncue,$ls_monivapuncue,
																	$ls_monbrupuncue,$ls_monantpuncue,$ls_porantpuncue,$ls_obspuncue,$ls_fecpuncue,$ls_basimp,$la_seguridad);
		if($lb_validoguardar)
		{
			$lb_validocuentas=true;
			for($li_i=1;$li_i<$li_filas;$li_i++)
			{				
				$ls_codest1=$_POST["codest1".$li_i];
				$ls_codest2=$_POST["codest2".$li_i];
				$ls_codest3=$_POST["codest3".$li_i];
				$ls_codest4=$_POST["codest4".$li_i];
				$ls_codest5=$_POST["codest5".$li_i];				
				$ls_moncar=$_POST["txtmoncue".$li_i];	
				$ls_nombre=$_POST["txtnomcue".$li_i];
				if(array_key_exists("concepto".$li_i."",$_POST))
				{
					$ls_chk=$_POST["concepto".$li_i];
					if($ls_chk=="monto")
					{
						$ls_concepto=1;
					}
					else
					{
						$ls_concepto=2;
					}
				}
				$lb_valido=$io_puntodecuenta->uf_guardar_cuentas($ls_codpuncue,$ls_codobr,$ls_codest5,$ls_codest4,$ls_codest3,$ls_codest2,$ls_codest1,$ls_nombre,$ls_concepto,$ls_moncar,$la_seguridad) ;
				if(!$lb_valido)
					$lb_validocuentas=false;
 			   
			}
			 /************  GUARDANDO CARGOS   **************/
                $la_cargos["codcar"][1]="";
	            $la_cargos["moncar"][1]="";
	            $la_cargos["formula"][1]="";
	            $la_cargos["codestpro"][1]="";
	            $la_cargos["spgcuenta"][1]="";
	            for ($li_i=1;$li_i<$li_filascargos;$li_i++)
                 {
	               $la_cargos["codcar"][$li_i]=$_POST["txtcodcar".$li_i];
	               $la_cargos["moncar"][$li_i]=$_POST["txtmoncar".$li_i];
	               $la_cargos["formula"][$li_i]=$_POST["formula".$li_i];
	               $la_cargos["codestpro"][$li_i]=$_POST["prog".$li_i];
	               $la_cargos["spgcuenta"][$li_i]=$_POST["spgcuenta".$li_i];
	             }
                $io_puntodecuenta->uf_update_dtcargos($ls_codpuncue,$ls_basimp,$la_cargos,$li_filascargos,$la_seguridad);
               /***********************************************/ 
			if($lb_validocuentas || $lb_validoguardar)
			{
				$lb_validoestado=$io_puntodecuenta->uf_update_estado($ls_codpuncue,$ls_codobr,1,$la_seguridad);
				if($lb_validoestado)
				{
					$io_msg->message("Registro Incluido!!!");
					uf_limpiar();
					$ls_codobr="";
				}
			}
		}
		$ls_imprimir=$_POST["hidimprimir"];
		if($ls_imprimir=="IMPRIMIR")
		{
			  $ls_documento="PUNTODECUENTA";
			  $ls_pagina="sigesp_sob_d_filechooser.php?codpuncue=".$ls_codpuncue."&codobr=".$ls_codobr."&documento=".$ls_documento;
			  print "<script language=JavaScript>";
			  print "popupWin('".$ls_pagina."','ventana',400,200);";
			  print "</script>";
		}
	}
	elseif($lb_validoselect!==false)//En caso de que se una actualizacion
	{
		$lb_validoupdate=$io_puntodecuenta->uf_update_puntodecuenta($ls_codobr,$ls_codpuncue ,$ls_codpropuncue,$ls_codunidad,$ls_despuncue,$ls_rempuncue,
									$ls_asupuncue,$ls_lapejepuncue,$ls_monnetpuncue,$ls_monivapuncue,
									$ls_monbrupuncue,$ls_monantpuncue,$ls_porantpuncue,$ls_obspuncue,$ls_fecpuncue,$ls_basimp,$la_seguridad);
	
		if($lb_validoupdate)
		{
			$la_cuentas=array();
			$lb_validocuentas=true;
			for($li_i=1;$li_i<$li_filas;$li_i++)
			{
				$la_cuentas["codestpro1"][$li_i]=$_POST["codest1".$li_i];
				$la_cuentas["codestpro2"][$li_i]=$_POST["codest2".$li_i];
				$la_cuentas["codestpro3"][$li_i]=$_POST["codest3".$li_i];
				$la_cuentas["codestpro4"][$li_i]=$_POST["codest4".$li_i];
				$la_cuentas["codestpro5"][$li_i]=$_POST["codest5".$li_i];				
				$la_cuentas["monto"][$li_i]=$_POST["txtmoncue".$li_i];	
				$la_cuentas["spg_cuenta"][$li_i]=$_POST["txtnomcue".$li_i];				
				if(array_key_exists("concepto".$li_i."",$_POST))
				{
					$ls_chk=$_POST["concepto".$li_i];
					if($ls_chk=="monto")
					{
						$la_cuentas["concuepuncue"][$li_i]=1;
					}
					else
					{
						$la_cuentas["concuepuncue"][$li_i]=2;
					}
				}
			}
			$lb_valido=$io_puntodecuenta->uf_update_cuentas($ls_codpuncue,$ls_codobr,$la_cuentas,$li_filas,$la_seguridad);	
			
			/************  GUARDANDO CARGOS   **************/
                $la_cargos["codcar"][1]="";
	            $la_cargos["moncar"][1]="";
	            $la_cargos["formula"][1]="";
	            $la_cargos["codestpro"][1]="";
	            $la_cargos["spgcuenta"][1]="";
	            for ($li_i=1;$li_i<$li_filascargos;$li_i++)
                 {
	               $la_cargos["codcar"][$li_i]=$_POST["txtcodcar".$li_i];
	               $la_cargos["moncar"][$li_i]=$_POST["txtmoncar".$li_i];
	               $la_cargos["formula"][$li_i]=$_POST["formula".$li_i];
	               $la_cargos["codestpro"][$li_i]=$_POST["prog".$li_i];
	               $la_cargos["spgcuenta"][$li_i]=$_POST["spgcuenta".$li_i];
	             }
                $io_puntodecuenta->uf_update_dtcargos($ls_codpuncue,$ls_basimp,$la_cargos,$li_filascargos,$la_seguridad);
               /***********************************************/ 			
		}
		
		if($lb_valido || $lb_validoupdate )
		{
			$io_msg->message("Registro Actualizado!!!");
			$ls_codobr="";
			uf_limpiar();
		}
	}

}
elseif($ls_operacion=="ue_eliminar")///Esto es una eliminacion lógica!
{
	$lb_valido=$io_puntodecuenta->uf_update_estado($ls_codpuncue,$ls_codobr,3,"");
	if($lb_valido!=false)
	{
		$io_msg->message("Registro Anulado!!!");
		$ls_codobr="";
		uf_limpiar();
	}
	
}
elseif($ls_operacion=="ue_cargarobra")
{
	$ls_dataobra=array();
	$lb_valido=$io_obra->uf_select_obra($ls_codobr,$la_dataobra);
	if($lb_valido)
	{
		$ls_monobr=$io_funsob->uf_convertir_numerocadena($la_dataobra["monto"][1]);
		$ls_feciniobr=$io_function->uf_convertirfecmostrar($la_dataobra["feciniobr"][1]);
		$ls_fecfinobr=$io_function->uf_convertirfecmostrar($la_dataobra["fecfinobr"][1]);
		$ls_estobr=$la_dataobra["desest"][1];
		$ls_parobr=$la_dataobra["denpar"][1];
		$ls_munobr=$la_dataobra["denmun"][1];
		$ls_comobr=$la_dataobra["nomcom"][1];
	}
}
elseif($ls_operacion=="ue_cargarcuenta")
{
	$li_filas=$li_filas+1;		
	for($li_i=1;$li_i<$li_filas;$li_i++)
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
		if(array_key_exists("concepto".$li_i."",$_POST))
		{
			$ls_chk=$_POST["concepto".$li_i];
			if($ls_chk=="monto")
			{
				$la_objeto[$li_i][1]="&nbsp;&nbsp;<input type=radio name=concepto".$li_i." value='monto' id=monto".$li_i." class=sin-borde checked style=cursor:default onClick=javascript:ue_cargarmonto(this)>";
				$la_objeto[$li_i][2]="&nbsp;<input type=radio name=concepto".$li_i." id=iva".$li_i."  value='iva' class=sin-borde  onClick=javascript:ue_verificariva(this) style=cursor:default>";
			}
			else
			{
				$la_objeto[$li_i][1]="&nbsp;&nbsp;<input type=radio name=concepto".$li_i." value='monto' id=monto".$li_i." class=sin-borde style=cursor:default onClick=javascript:ue_cargarmonto(this)>";
				$la_objeto[$li_i][2]="&nbsp;<input type=radio name=concepto".$li_i." id=iva".$li_i."  value='iva' class=sin-borde checked onClick=javascript:ue_verificariva(this) style=cursor:default>";
			}
		}
		else
		{
			$la_objeto[$li_i][1]="&nbsp;&nbsp;<input type=radio name=concepto".$li_i." value='monto' id=monto".$li_i." class=sin-borde style=cursor:default onClick=javascript:ue_cargarmonto(this)>";
			$la_objeto[$li_i][2]="&nbsp;<input type=radio name=concepto".$li_i." id=iva".$li_i."  value='iva' class=sin-borde onClick=javascript:ue_verificariva(this) style=cursor:default>";
		}
		
		$la_objeto[$li_i][3]="<input name=txtcodcue".$li_i." type=text id=txtcodcue".$li_i." class=sin-borde style= text-align:center size=40 value='".$ls_codigo."' readonly><input name=codest1".$li_i." type=hidden id=codest1".$li_i." value='".$ls_codest1."'><input name=codest2".$li_i." type=hidden id=codest2".$li_i." value='".$ls_codest2."'><input name=codest3".$li_i." type=hidden id=codest3".$li_i." value='".$ls_codest3."'><input name=codest4".$li_i." type=hidden id=codest4".$li_i." value='".$ls_codest4."'><input name=codest5".$li_i." type=hidden id=codest5".$li_i." value='".$ls_codest5."'>";
		$la_objeto[$li_i][4]="<input name=txtnomcue".$li_i." type=text id=txtnomcue".$li_i." class=sin-borde style= text-align:center size=10 value='".$ls_nombre."' readonly >";
		$la_objeto[$li_i][5]="<input name=txtmoncue".$li_i." type=text id=txtmoncue".$li_i." class=sin-borde size=20 style= text-align:right value='".$ls_moncar."' onKeyPress=return(validaCajas(this,'d',event,20)) onKeyUp=javascript:ue_validardispo(this) onBlur=javascript:ue_getformat(this)><input name=disponible".$li_i." type=hidden id=disponible".$li_i." value='".$ls_disponible."'>";
		$la_objeto[$li_i][6]="<a href=javascript:ue_removercuenta(".$li_i.");><div align=center><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></div></a>";
	}	
	$la_objeto[$li_filas][1]="<input type=text name=xxx".$li_filas." id=xxx".$li_filas." size=1 class=sin-borde readonly>";
	$la_objeto[$li_filas][2]="<input type=text name=xxx".$li_filas." id=xxx".$li_filas." size=1 class=sin-borde readonly>";
	$la_objeto[$li_filas][3]="<input name=txtcodcue".$li_filas." type=text id=txtcodcue".$li_filas." class=sin-borde style= text-align:center size=40 readonly><input name=codest1".$li_filas." type=hidden id=codest1".$li_filas."><input name=codest2".$li_filas." type=hidden id=codest2".$li_filas."><input name=codest3".$li_filas." type=hidden id=codest3".$li_filas."><input name=codest4".$li_filas." type=hidden id=codest4".$li_filas."><input name=codest5".$li_filas." type=hidden id=codest5".$li_filas.">";
	$la_objeto[$li_filas][4]="<input name=txtnomcue".$li_filas." type=text id=txtnomcue".$li_filas." class=sin-borde style= text-align:left size=10 readonly>";
	$la_objeto[$li_filas][5]="<input name=txtmoncue".$li_filas." type=text id=txtmoncue".$li_filas." class=sin-borde size=20 style= text-align:center readonly><input name=disponible".$li_filas." type=hidden id=disponible".$li_filas." >";
	$la_objeto[$li_filas][6]="<input name=txtvacio".$li_filas." type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
}
elseif($ls_operacion=="ue_removercuenta")
{
	$li_filas=$li_filas-1;
	$li_removerfila=$_POST["hidfilaeliminar"];
	$li_temp=0;
	for($li_i=1;$li_i<=$li_filas;$li_i++)
	{
		if($li_i!=$li_removerfila)
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
			if(array_key_exists("concepto".$li_i."",$_POST))
			{
				$ls_chk=$_POST["concepto".$li_i];
				if($ls_chk=="monto")
				{
					$la_objeto[$li_temp][1]="&nbsp;&nbsp;<input type=radio name=concepto".$li_temp." value='monto' id=monto".$li_temp." class=sin-borde checked style=cursor:default onClick=javascript:ue_cargarmonto(this)>";
					$la_objeto[$li_temp][2]="&nbsp;<input type=radio name=concepto".$li_temp." id=iva".$li_temp."  value='iva' class=sin-borde  onClick=javascript:ue_verificariva(this) style=cursor:default>";
				}
				else
				{
					$la_objeto[$li_temp][1]="&nbsp;&nbsp;<input type=radio name=concepto".$li_temp." value='monto' id=monto".$li_temp." class=sin-borde style=cursor:default onClick=javascript:ue_cargarmonto(this)>";
					$la_objeto[$li_temp][2]="&nbsp;<input type=radio name=concepto".$li_temp." id=iva".$li_temp."  value='iva' class=sin-borde checked onClick=javascript:ue_verificariva(this) style=cursor:default>";
				}
			}
			else
			{
				$la_objeto[$li_temp][1]="&nbsp;&nbsp;<input type=radio name=concepto".$li_temp." value='monto' id=monto".$li_temp." class=sin-borde style=cursor:default onClick=javascript:ue_cargarmonto(this)>";
				$la_objeto[$li_temp][2]="&nbsp;<input type=radio name=concepto".$li_temp." id=iva".$li_temp."  value='iva' class=sin-borde onClick=javascript:ue_verificariva(this) style=cursor:default>";
			}
			
			$la_objeto[$li_temp][3]="<input name=txtcodcue".$li_temp." type=text id=txtcodcue".$li_temp." class=sin-borde style= text-align:center size=40 value='".$ls_codigo."' readonly><input name=codest1".$li_temp." type=hidden id=codest1".$li_temp." value='".$ls_codest1."'><input name=codest2".$li_temp." type=hidden id=codest2".$li_temp." value='".$ls_codest2."'><input name=codest3".$li_temp." type=hidden id=codest3".$li_temp." value='".$ls_codest3."'><input name=codest4".$li_temp." type=hidden id=codest4".$li_temp." value='".$ls_codest4."'><input name=codest5".$li_temp." type=hidden id=codest5".$li_temp." value='".$ls_codest5."'>";
			$la_objeto[$li_temp][4]="<input name=txtnomcue".$li_temp." type=text id=txtnomcue".$li_temp." class=sin-borde style= text-align:center size=10 value='".$ls_nombre."' readonly >";
			$la_objeto[$li_temp][5]="<input name=txtmoncue".$li_temp." type=text id=txtmoncue".$li_temp." class=sin-borde size=20 style= text-align:right value='".$ls_moncar."' onKeyPress=return(validaCajas(this,'d',event,20)) onKeyUp=javascript:ue_validardispo(this) onBlur=javascript:ue_getformat(this)><input name=disponible".$li_temp." type=hidden id=disponible".$li_temp." value='".$ls_disponible."'>";
			$la_objeto[$li_temp][6]="<a href=javascript:ue_removercuenta(".$li_temp.");><div align=center><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></div></a>";
		}		
	}	
	$la_objeto[$li_filas][1]="<input type=text name=xxx".$li_filas." id=xxx".$li_filas." size=1 class=sin-borde readonly>";
	$la_objeto[$li_filas][2]="<input type=text name=xxx".$li_filas." id=xxx".$li_filas." size=1 class=sin-borde readonly>";
	$la_objeto[$li_filas][3]="<input name=txtcodcue".$li_filas." type=text id=txtcodcue".$li_filas." class=sin-borde style= text-align:center size=40 readonly><input name=codest1".$li_filas." type=hidden id=codest1".$li_filas."><input name=codest2".$li_filas." type=hidden id=codest2".$li_filas."><input name=codest3".$li_filas." type=hidden id=codest3".$li_filas."><input name=codest4".$li_filas." type=hidden id=codest4".$li_filas."><input name=codest5".$li_filas." type=hidden id=codest5".$li_filas.">";
	$la_objeto[$li_filas][4]="<input name=txtnomcue".$li_filas." type=text id=txtnomcue".$li_filas." class=sin-borde style= text-align:left size=10 readonly>";
	$la_objeto[$li_filas][5]="<input name=txtmoncue".$li_filas." type=text id=txtmoncue".$li_filas." class=sin-borde size=20 style= text-align:center readonly><input name=disponible".$li_filas." type=hidden id=disponible".$li_filas.">";
	$la_objeto[$li_filas][6]="<input name=txtvacio".$li_filas." type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
}

/*************************************************INSERTAR CAMPO EN GRID CARGOS**************************************************************************************************************************/
elseif($ls_operacion=="ue_cargarcargo")
{
	$ls_baseimp=$_POST["txtbasimp"];
	$ls_montpar=$_POST["txtmonnetpuncue"];
	$ld_baseimpo=$io_funsob->uf_convertir_cadenanumero($ls_baseimp);
	$ld_montopar=$io_funsob->uf_convertir_cadenanumero($ls_montpar);
	$ld_montotasi=0;
	
	$li_filascargos=$_POST["filascargos"];
	$li_filascargos=$li_filascargos+1;
	
	for($li_i=1;$li_i<$li_filascargos;$li_i++)
	{
		$ls_codigo=$_POST["txtcodcar".$li_i];
		$ls_nombre=$_POST["txtnomcar".$li_i];
		$ls_formula=$_POST["formula".$li_i];
		$ls_prog=$_POST["prog".$li_i];
		$ls_spgcuenta=$_POST["spgcuenta".$li_i];
		$ld_result=$io_formula->uf_evaluar($ls_formula,$ld_baseimpo);
		$ld_montotasi=$ld_montotasi+$ld_result;
		$la_objectcargos[$li_i][1]="<input name=txtcodcar".$li_i." type=text id=txtcodcar".$li_i." class=sin-borde style= text-align:center size=5 value='".$ls_codigo."' readonly>";
		$la_objectcargos[$li_i][2]="<input name=txtnomcar".$li_i." type=text id=txtnomcar".$li_i." class=sin-borde style= text-align:left size=60 value='".$ls_nombre."' readonly >";
		$la_objectcargos[$li_i][3]="<input name=txtmoncar".$li_i." type=text id=txtmoncar".$li_i." class=sin-borde size=20 style= text-align:center value='".$io_funsob->uf_convertir_numerocadena($ld_result)."' readonly><input name=formula".$li_i." type=hidden id=formula".$li_i." value='".$ls_formula."'><input name=prog".$li_i." type=hidden id=prog".$li_i." value='".$ls_prog."'><input name=spgcuenta".$li_i." type=hidden id=spgcuenta".$li_i." value='".$ls_spgcuenta."'>";
		$la_objectcargos[$li_i][4]="<a href=javascript:ue_removercargo(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
	    
	}	
	$la_objectcargos[$li_filascargos][1]="<input name=txtcodcar".$li_filascargos." type=text id=txtcodcar".$li_filascargos." class=sin-borde style= text-align:center size=5 >";
	$la_objectcargos[$li_filascargos][2]="<input name=txtnomcar".$li_filascargos." type=text id=txtnomcar".$li_filascargos." class=sin-borde style= text-align:left size=50 >";
	$la_objectcargos[$li_filascargos][3]="<input name=txtmoncar".$li_filascargos." type=text id=txtmoncar".$li_filascargos." class=sin-borde size=20 style= text-align:center><input name=formula".$li_filascargos." type=hidden id=formula".$li_filascargos."><input name=prog".$li_filascargos." type=hidden id=prog".$li_filascargos."><input name=spgcuenta".$li_filascargos." type=hidden id=spgcuenta".$li_filascargos.">";
	$la_objectcargos[$li_filascargos][4]="<input name=txtvacio".$li_filascargos." type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";

     $ld_subtotal=$ld_montopar-$ld_baseimpo;
	 $ld_resultado=$ld_baseimpo+$ld_montotasi+$ld_subtotal; 
	 $ls_monivapuncue=$io_funsob->uf_convertir_numerocadena($ld_montotasi); 
	 $ls_monbrupuncue=$io_funsob->uf_convertir_numerocadena($ld_resultado);
}
/***************************************************************************************************************************************************************************/

/*******************************************************REMOVER CAMPO EN GRID CARGOS********************************************************************************************************************/
elseif($ls_operacion=="ue_removercargo")
{
    $ls_baseimp=$_POST["txtbasimp"];
	$ls_montpar=$_POST["txtmonnetpuncue"];
	$ld_baseimpo=$io_funsob->uf_convertir_cadenanumero($ls_baseimp);
	$ld_montopar=$io_funsob->uf_convertir_cadenanumero($ls_montpar);
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
			$ls_codigo=$_POST["txtcodcar".$li_i];
			$ls_nombre=$_POST["txtnomcar".$li_i];
			$ls_monto=$_POST["txtmoncar".$li_i];
			$ls_formula=$_POST["formula".$li_i];
			$ls_prog=$_POST["prog".$li_i];
		    $ls_spgcuenta=$_POST["spgcuenta".$li_i];
			$la_objectcargos[$li_temp][1]="<input name=txtcodcar".$li_temp." type=text id=txtcodcar".$li_temp." class=sin-borde style= text-align:center size=5 value='".$ls_codigo."' readonly>";
			$la_objectcargos[$li_temp][2]="<input name=txtnomcar".$li_temp." type=text id=txtnomcar".$li_temp." class=sin-borde style= text-align:left size=60 value='".$ls_nombre."' readonly >";
			$la_objectcargos[$li_temp][3]="<input name=txtmoncar".$li_temp." type=text id=txtmoncar".$li_temp." class=sin-borde size=20 style= text-align:center value='".$ls_monto."' readonly><input name=formula".$li_i." type=hidden id=formula".$li_i." value='".$ls_formula."'><input name=prog".$li_i." type=hidden id=prog".$li_i." value='".$ls_prog."'><input name=spgcuenta".$li_i." type=hidden id=spgcuenta".$li_i." value='".$ls_spgcuenta."'>";
			$la_objectcargos[$li_temp][4]="<a href=javascript:ue_removercargo(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
			$ld_result=$io_formula->uf_evaluar($ls_formula,$ld_baseimpo);
		    $ld_montotasi=$ld_montotasi+$ld_result;
		}
	}
	$la_objectcargos[$li_filascargos][1]="<input name=txtcodcar".$li_filascargos." type=text id=txtcodcar".$li_filascargos." class=sin-borde style= text-align:center size=5 readonly>";
	$la_objectcargos[$li_filascargos][2]="<input name=txtnomcar".$li_filascargos." type=text id=txtnomcar".$li_filascargos." class=sin-borde style= text-align:left size=50 readonly>";
	$la_objectcargos[$li_filascargos][3]="<input name=txtmoncar".$li_filascargos." type=text id=txtmoncar".$li_filascargos." class=sin-borde size=20 style= text-align:center readonly><input name=formula".$li_filascargos." type=hidden id=formula".$li_filascargos."><input name=prog".$li_filascargos." type=hidden id=prog".$li_filascargos."><input name=spgcuenta".$li_filascargos." type=hidden id=spgcuenta".$li_filascargos.">";
	$la_objectcargos[$li_filascargos][4]="<input name=txtvacio".$li_filascargos." type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	
	$ld_subtotal=$ld_montopar-$ld_baseimpo;
	$ld_resultado=$ld_baseimpo+$ld_montotasi+$ld_subtotal;
	$ls_monivapuncue=$io_funsob->uf_convertir_numerocadena($ld_montotasi);   
	$ls_monbrupuncue=$io_funsob->uf_convertir_numerocadena($ld_resultado);
}
/***************************************************************************************************************************************************************************/
elseif($ls_operacion=="ue_cargarpuntodecuenta")
{
	//$ls_codunidad=$_POST["hidlapejeunipuncue"];
	$lb_valido=$io_puntodecuenta->uf_select_cuentas($ls_codpuncue,$ls_codobr,$la_datacuentas,$li_rows);
	if($lb_valido)
	{
		$li_filas=$li_rows+1;
		for($li_i=1;$li_i<$li_filas;$li_i++)
		{		
			$ls_codest1=$io_funsob->uf_convertir_cadenanumero($la_datacuentas["codestpro1"][$li_i]);
			$ls_codest2=$io_funsob->uf_convertir_cadenanumero($la_datacuentas["codestpro2"][$li_i]);
			$ls_codest3=$io_funsob->uf_convertir_cadenanumero($la_datacuentas["codestpro3"][$li_i]);
			$ls_codest4=$io_funsob->uf_convertir_cadenanumero($la_datacuentas["codestpro4"][$li_i]);
			$ls_codest5=$io_funsob->uf_convertir_cadenanumero($la_datacuentas["codestpro5"][$li_i]);
			$ls_codigo=$ls_codest1.$ls_codest2.$ls_codest3.$ls_codest4.$ls_codest5;
			$ls_nombre=$la_datacuentas["spg_cuenta"][$li_i];
			$ls_moncar=$io_funsob->uf_convertir_numerocadena($la_datacuentas["monto"][$li_i]);
			$ls_disponible=$la_datacuentas["disponible"][$li_i];
			$ls_concepto=$la_datacuentas["concuepuncue"][$li_i];
			if($ls_concepto==1)
			{
				$la_objeto[$li_i][1]="&nbsp;&nbsp;<input type=radio name=concepto".$li_i." value='monto' id=monto".$li_i." class=sin-borde checked style=cursor:default onClick=javascript:ue_cargarmonto(this)>";
				$la_objeto[$li_i][2]="&nbsp;<input type=radio name=concepto".$li_i." id=iva".$li_i."  value='iva' class=sin-borde  onClick=javascript:ue_verificariva(this) style=cursor:default>";
			}
			else
			{
				$la_objeto[$li_i][1]="&nbsp;&nbsp;<input type=radio name=concepto".$li_i." value='monto' id=monto".$li_i." class=sin-borde style=cursor:default onClick=javascript:ue_cargarmonto(this)>";
				$la_objeto[$li_i][2]="&nbsp;<input type=radio name=concepto".$li_i." id=iva".$li_i."  value='iva' class=sin-borde checked onClick=javascript:ue_verificariva(this) style=cursor:default>";
			}	
		
		$la_objeto[$li_i][3]="<input name=txtcodcue".$li_i." type=text id=txtcodcue".$li_i." class=sin-borde style= text-align:center size=40 value='".$ls_codigo."' readonly><input name=codest1".$li_i." type=hidden id=codest1".$li_i." value='".$ls_codest1."'><input name=codest2".$li_i." type=hidden id=codest2".$li_i." value='".$ls_codest2."'><input name=codest3".$li_i." type=hidden id=codest3".$li_i." value='".$ls_codest3."'><input name=codest4".$li_i." type=hidden id=codest4".$li_i." value='".$ls_codest4."'><input name=codest5".$li_i." type=hidden id=codest5".$li_i." value='".$ls_codest5."'>";
		$la_objeto[$li_i][4]="<input name=txtnomcue".$li_i." type=text id=txtnomcue".$li_i." class=sin-borde style= text-align:center size=10 value='".$ls_nombre."' readonly >";
		$la_objeto[$li_i][5]="<input name=txtmoncue".$li_i." type=text id=txtmoncue".$li_i." class=sin-borde size=20 style= text-align:right value='".$ls_moncar."' onKeyPress=return(validaCajas(this,'d',event,20)) onKeyUp=javascript:ue_validardispo(this) onBlur=javascript:ue_getformat(this)><input name=disponible".$li_i." type=hidden id=disponible".$li_i." value='".$ls_disponible."'>";
		$la_objeto[$li_i][6]="<a href=javascript:ue_removercuenta(".$li_i.");><div align=center><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></div></a>";
	}	
	$la_objeto[$li_filas][1]="<input type=text name=xxx".$li_filas." id=xxx".$li_filas." size=1 class=sin-borde readonly>";
	$la_objeto[$li_filas][2]="<input type=text name=xxx".$li_filas." id=xxx".$li_filas." size=1 class=sin-borde readonly>";
	$la_objeto[$li_filas][3]="<input name=txtcodcue".$li_filas." type=text id=txtcodcue".$li_filas." class=sin-borde style= text-align:center size=40 readonly><input name=codest1".$li_filas." type=hidden id=codest1".$li_filas."><input name=codest2".$li_filas." type=hidden id=codest2".$li_filas."><input name=codest3".$li_filas." type=hidden id=codest3".$li_filas."><input name=codest4".$li_filas." type=hidden id=codest4".$li_filas."><input name=codest5".$li_filas." type=hidden id=codest5".$li_filas.">";
	$la_objeto[$li_filas][4]="<input name=txtnomcue".$li_filas." type=text id=txtnomcue".$li_filas." class=sin-borde style= text-align:left size=10 readonly>";
	$la_objeto[$li_filas][5]="<input name=txtmoncue".$li_filas." type=text id=txtmoncue".$li_filas." class=sin-borde size=20 style= text-align:center readonly><input name=disponible".$li_filas." type=hidden id=disponible".$li_filas." >";
	$la_objeto[$li_filas][6]="<input name=txtvacio".$li_filas." type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	}
	
	/*************************CARGANDO CARGOS***********************/ //OJOOO FALTA CTSSPG DEL CARGO!!!!!
	$lb_validoca=$io_puntodecuenta-> uf_select_cargos($ls_codpuncue,$la_cargos,$li_totalfilas);
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
		$la_objectcargos[$li_i][1]="<input name=txtcodcar".$li_i." type=text id=txtcodcar".$li_i." class=sin-borde style= text-align:center size=5 value='".$ls_codigo."' readonly>";
		$la_objectcargos[$li_i][2]="<input name=txtnomcar".$li_i." type=text id=txtnomcar".$li_i." class=sin-borde style= text-align:left size=60 value='".$ls_nombre."' readonly >";
		$la_objectcargos[$li_i][3]="<input name=txtmoncar".$li_i." type=text id=txtmoncar".$li_i." class=sin-borde size=20 style= text-align:center value='".$io_funsob->uf_convertir_numerocadena($ls_moncar)."' readonly><input name=formula".$li_i." type=hidden id=formula".$li_i." value='".$ls_formula."'>";
		$la_objectcargos[$li_i][4]="<div align=center><a href=javascript:ue_removercargo(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a></div>";
	}	
	$li_filascargos=$li_filascargos+1;
	$la_objectcargos[$li_filascargos][1]="<input name=txtcodcar".$li_filascargos." type=text id=txtcodcar".$li_filascargos." class=sin-borde style= text-align:center size=5 >";
	$la_objectcargos[$li_filascargos][2]="<input name=txtnomcar".$li_filascargos." type=text id=txtnomcar".$li_filascargos." class=sin-borde style= text-align:left size=50 >";
	$la_objectcargos[$li_filascargos][3]="<input name=txtmoncar".$li_filascargos." type=text id=txtmoncar".$li_filascargos." class=sin-borde size=20 style= text-align:center><input name=formula".$li_filascargos." type=hidden id=formula".$li_filascargos.">";
	$la_objectcargos[$li_filascargos][4]="<input name=txtvacio".$li_filascargos." type=text id=txtvacio class=sin-borde style= text-align:center size=5 readonly>";
	}
}

elseif($ls_operacion=="ue_imprimir")
{	  
	  $lb_valido=$io_puntodecuenta->uf_select_puntodecuenta($ls_codpuncue,$ls_codobr,$la_data);
	  if($lb_valido===true)
	  {
		  $ls_documento="PUNTODECUENTA";
		  $ls_pagina="sigesp_sob_d_filechooser.php?codpuncue=".$ls_codpuncue."&codobr=".$ls_codobr."&documento=".$ls_documento;
		  print "<script language=JavaScript>";
		  print "popupWin('".$ls_pagina."','ventana',400,200);";
		  print "</script>";
	  }
	  elseif($lb_valido===0)
	  {
			
				print"<script>";
				print"f=document.form1;";
				print"guardar=confirm('El Punto de Cuenta no ha sido guardado. ¿Desea guardarlo ahora?');";
				print"if (guardar)";
				print"{";
					print"f.hidimprimir.value='IMPRIMIR';";
					print"f.operacion.value='ue_guardar';";
					print"f.submit();";		
				print"}";	
			print"</script>";	
			
	  }
}
?>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
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
  <form name="form1" method="post" action="">
  <?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos)||($ls_logusr=="PSEGIS"))
{
	print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	print("<input type=hidden name=leer     id=leer value='$la_permisos[leer]'>");
	print("<input type=hidden name=incluir  id=incluir value='$la_permisos[incluir]'>");
	print("<input type=hidden name=cambiar  id=cambiar value='$la_permisos[cambiar]'>");
	print("<input type=hidden name=eliminar id=eliminar value='$la_permisos[eliminar]'>");
	print("<input type=hidden name=imprimir id=imprimir value='$la_permisos[imprimir]'>");
	print("<input type=hidden name=anular   id=anular value='$la_permisos[anular]'>");
	print("<input type=hidden name=ejecutar id=ejecutar value='$la_permisos[ejecutar]'>");
}
else
{
	
	print("<script language=JavaScript>");
	print(" location.href='sigespwindow_blank.php'");
	print("</script>");
}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	

  <table width="738" border="0" align="center" cellpadding="0" cellspacing="3"  class="contorno">
    <tr class="formato-blanco">
      <td>&nbsp;</td>
      <td colspan="7" class="titulo-celdanew">Datos de la Obra</td>
      <td>&nbsp;</td>
    </tr>
    <tr class="formato-blanco">
      <td>&nbsp;</td>
      <td colspan="8">&nbsp;</td>
    </tr>
    <tr class="formato-blanco">
      <td width="11" height="22"><div align="right"></div></td>
      <td colspan="3">C&oacute;digo
          <input name="txtcodobr" type="text" id="txtcodobr" style="text-align:center " value="<?php print $ls_codobr ?>" size="8" maxlength="8" readonly="true">
          <input name="operacion" type="hidden" id="operacion">
          <input name="hidstatus" type="hidden" id="hidstatus">
          <?
		  if (array_key_exists("hidstatus",$_POST))
		  	$ls_hidstatus=$_POST["hidstatus"];
		  else
			$ls_hidstatus="";
		  if($ls_hidstatus!="C")
		  {
		  ?>
		  <a href="javascript:ue_catobra();">
		  <img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0">		  </a>
		  <?
		  }
		  else
		  {
		  ?>
		  	<img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0">
		  <?php 
		  }
		  ?>		  </td>
      <td colspan="4"><div align="right"><a href="javascript:uf_mostrar_ocultar_contrato();">&nbsp;&nbsp;<img src="../shared/imagebank/mas.gif" width="9" height="17" border="0"></a><a href="javascript:uf_mostrar_ocultar_obra();">Datos de la Obra </a></div></td>
      <td width="13">&nbsp;</td>
    </tr>
    <tr class="formato-blanco">
      <td height="13"><div align="right"></div></td>
      <td height="13" colspan="7"><div align="right"></div></td>
      <td>&nbsp;</td>
    </tr>
    <?Php
			if ($ls_datosobra=="MOSTRAR")
			{?>
    <tr class="formato-blanco">
      <td><div align="right"></div></td>
      <td colspan="7" align="center" valign="top"><table width="417" height="92" border="0" cellpadding="0" cellspacing="4">
          <tr class="letras-pequeñas">
            <td width="87" height="18"><span class="Estilo1">Monto Total</span></td>
            <td width="133"><span class="style6">
              <input name="txtmontotobr" type="text" id="txtmontotobr"  style="text-align: right" value="<?php print $ls_monobr ?>" size="20" maxlength="20"  readonly="true" >
            </span></td>
            <td width="56">&nbsp;</td>
            <td width="121"><span class="style6"></span></td>
          </tr>
          <tr class="letras-pequeñas">
            <td height="18">Fecha Inicio</td>
            <td><span class="style6">
              <input name="txtfeciniobr" type="text" id="txtfeciniobr"  style="text-align:left" value="<?php print $ls_feciniobr ?>" size="20" maxlength="20" readonly="true">
            </span></td>
            <td><span class="Estilo1">Fecha Fin</span></td>
            <td><span class="style6">
              <input name="txtfecfinobr" type="text" id="txtfecfinobr"  style="text-align:left" value="<?php print $ls_fecfinobr ?>" size="20" maxlength="20" readonly="true">
            </span></td>
          </tr>
          <tr class="letras-pequeñas">
            <td height="18"><span class="Estilo1">Estado</span></td>
            <td><span class="style6">
              <input name="txtestobr" type="text" id="txtestobr"  style="text-align:left" value="<?php print $ls_estobr?>" size="20" maxlength="20" readonly="true">
            </span></td>
            <td>Municipio</td>
            <td><span class="style6">
              <input name="txtmunobr" type="text" id="txtmunobr"  style="text-align:left" value="<?php print $ls_munobr ?>" size="20" maxlength="20" readonly="true">
            </span></td>
          </tr>
          <tr class="letras-pequeñas">
            <td height="18">Parroquia</td>
            <td><span class="style6">
              <input name="txtparobr" type="text" id="txtparobr"  style="text-align:left" value="<?php print $ls_parobr ?>" size="20" maxlength="20" readonly="true">
            </span></td>
            <td>Comunidad</td>
            <td><span class="style6">
              <input name="txtnomcom" type="text" id="txtnomcom"  style="text-align:left" value="<?php print $ls_comobr ?>" size="20" maxlength="20" readonly="true">
            </span></td>
          </tr>
      </table>      </td>
      <td>&nbsp;</td>
    </tr>
    <?Php
			}
			else
			{
			?>
    <tr class="formato-blanco">
      <td height="10" class="sin-borde">&nbsp;</td>
      <td height="10" colspan="7" align="center" valign="top" class="sin-borde"> </td>
      <td height="10" class="sin-borde">&nbsp;</td>
    </tr>
    <?
			}			
			?>
    <tr class="formato-blanco">
      <td height="13" colspan="9" class="titulo-celdanew">Datos del Punto de Cuenta</td>
    </tr>
    <tr class="formato-blanco">
      <td height="18">&nbsp;</td>
      <td width="136" height="18"><div align="right"></div>
          <div align="right">C&oacute;digo </div></td>
      <td width="30" height="18">
        <div align="left">
          <input name="txtcodpuncue" id="txtcodpuncue" style="text-align:center " value="<?php print $ls_codpuncue?>" readonly="true" type="text" size="3" maxlength="3">
      </div></td>
      <td height="18" colspan="2"><div align="right"></div></td>
      <td height="18">&nbsp;</td>
      <td height="18"><div align="right">Fecha</div></td>
      <td width="160" height="18"><input name="txtfecpuncue" type="text" id="txtfecpuncue"  style="text-align:left" value="<?php print $ls_fecpuncue?>" size="11" maxlength="11"  readonly="true" datepicker="true"></td>
      <td height="18">&nbsp;</td>
    </tr>
    <tr class="formato-blanco">
      <td height="18">&nbsp;</td>
      <td height="18"><div align="right">Presentado a</div></td>
      <td height="18" colspan="7"><input name="txtdespuncue" type="text" id="txtdespuncue" value="<?php print $ls_despuncue?>" size="50" maxlength="50"></td>
    </tr>
    <tr class="formato-blanco">
      <td height="18">&nbsp;</td>
      <td height="18"><div align="right">Presentado por</div></td>
      <td height="18" colspan="6"><input name="txtrempuncue" type="text" id="txtrempuncue" value="<?php print $ls_rempuncue?>" size="50" maxlength="50"></td>
      <td height="18">&nbsp;</td>
    </tr>
    <tr class="formato-blanco">
      <td height="44">&nbsp;</td>
      <td height="44"><div align="right">Asunto</div></td>
      <td height="44" colspan="6"><textarea name="txtasupuncue"  id="txtasupuncue" cols="80" rows="2" wrap="VIRTUAL" onKeyDown="textCounter(this,255)"><?php print $ls_asupuncue?></textarea></td>
      <td height="44">&nbsp;</td>
    </tr>
    <tr class="formato-blanco">
      <td height="20">&nbsp;</td>
      <td height="20"><div align="right">Contratista</div></td>
      <td height="20" colspan="6"><input name="txtcodpropuncue" type="text" id="txtcodpropuncue" style="text-align:center " value="<?php print $ls_codpropuncue ?>" size="10" maxlength="10" readonly="false">
          <a href="javascript:ue_catcontratista();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
          <input name="txtnompropuncue" type="text" id="txtnompropuncue"  style="text-align:left" class="sin-borde" value="<?php print $ls_nompropuncue ?>" size="70" maxlength="100" readonly="true"></td>
      <td height="20">&nbsp;</td>
    </tr>
    <tr class="formato-blanco">
      <td height="18">&nbsp;</td>
      <td><div align="right">Representante Legal</div></td>
      <td colspan="6"><input name="txtreplegcon" type="text" id="txtreplegcon" value="<?php print $ls_replegcon?>" size="50" maxlength="50"></td>
      <td>&nbsp;</td>
    </tr>
    <tr class="formato-blanco">
      <td height="19">&nbsp;</td>
      <td><div align="right">Lapso de Ejecuci&oacute;n </div></td>
      <td><input name="txtlapejepuncue" type="text" id="txtlapejepuncue" style="text-align:right " value="<?php print $ls_lapejepuncue?>" size="6" maxlength="6" onKeyDown="javascritp:document.form1.cmblapejeunipuncue.disabled=false;" onKeyPress="return(validaCajas(this,'d',event,21))"></td>
      <td width="99"><?Php
			   				
				$lb_valido=$io_contrato->uf_llenarcombo_unidadtiempo($la_unidades);					
				if($lb_valido)
				{
					$io_datastore->data=$la_unidades;
					$li_totalfilas=$io_datastore->getRowCount("coduni");					
				}
				if($ls_lapejepuncue!="" && $ls_lapejepuncue!="0,00")
				{
				
          		print "<select name='cmblapejeunipuncue' size='1' id='cmblapejeunipuncue'>";
		  		}
				else
				{
					print "<select name='cmblapejeunipuncue' size='1' id='cmblapejeunipuncue' disabled>";
				}
				?>
          <option value="---">Seleccione</option>
          <?Php
					for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
					{
					 $ls_codigo=$io_datastore->getValue("coduni",$li_i);
					 $ls_descripcion=$io_datastore->getValue("nomuni",$li_i);
					 if ($ls_codigo==$ls_codunidad)
					 {
						  print "<option value='$ls_codigo' selected>$ls_descripcion</option>";
					 }
					 else
					 {
						  print "<option value='$ls_codigo'>$ls_descripcion</option>";
					 }
					} 
	        ?>     
			<input type="hidden" name="hidlapejeunipuncue" id="hidlapejeunipuncue">  
      <td width="97">&nbsp;</td>
      <td width="67">&nbsp;</td>
      <td width="93">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr class="formato-blanco">
      <td height="19">&nbsp;</td>
      <td><div align="right">Monto Neto</div></td>
      <td colspan="6">
        <input name="txtmonnetpuncue" type="text" id="txtmonnetpuncue" value="<?php print $ls_monnetpuncue?>" size="21" maxlength="21" style="text-align:right " onKeyPress="return(validaCajas(this,'d',event,21)) "  onBlur="javascript:ue_getformat(this)" >
        Bs.
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
      <td>&nbsp;</td>
    </tr>
    
    <tr class="formato-blanco">
      <td height="18">&nbsp;</td>
      <td><div align="right">Base Imponible</div></td>
      <td colspan="6"><input name="txtbasimp" type="text" id="txtbasimp" value="<?php print $ls_basimp?>" size="21" maxlength="21" style="text-align:right " onKeyPress="return(validaCajas(this,'d',event,21))">
Bs.
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
      <td>&nbsp;</td>
    </tr>
    <tr class="formato-blanco">
      <td height="18">&nbsp;</td>
      <td><div align="right">Total Impuesto  </div></td>
      <td colspan="6"><input name="txtmonivapuncue" type="text" id="txtmonivapuncue" value="<?php print $ls_monivapuncue?>" size="21" maxlength="21" style="text-align:right " onKeyPress="return(validaCajas(this,'d',event,21))">
Bs.
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
      <td>&nbsp;</td>
    </tr>
    <tr class="formato-blanco">
      <td height="18">&nbsp;</td>
      <td><div align="right">Total</div></td>
      <td colspan="6"><input name="txtmonbrupuncue" type="text" id="txtmonbrupuncue" value="<?php print $ls_monbrupuncue?>" size="21" maxlength="21" style="text-align:right " onKeyPress="return(validaCajas(this,'d',event,21))" readonly="true">
      Bs.</td>
      <td>&nbsp;</td>
    </tr>
    <tr class="formato-blanco">
      <td height="18">&nbsp;</td>
      <td><div align="right">Monto  Anticipo </div>
        <div align="right"></div></td>
      <td colspan="6"><input name="txtporantpuncue" type="text" id="txtporantpuncue" value="<?php print $ls_porantpuncue?>" size="6" maxlength="6" style="text-align:right " onKeyPress="return(validaCajas(this,'d',event,6))" onKeyUp="javascript:ue_calcularmonto(this,document.form1.txtmonantpuncue)" onBlur="javascript:ue_getformat(this)">
      %&nbsp;&nbsp;&nbsp;
        <input name="txtmonantpuncue" type="text" id="txtmonantpuncue" value="<?php print $ls_monantpuncue?>" size="21" maxlength="21" style="text-align:right " onKeyPress="return(validaCajas(this,'d',event,21))" readonly="true">
        Bs.      </td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="bottom" class="formato-blanco">
      <td height="50" colspan="9"><table width="609" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr class="formato-blanco">
          <td width="14" height="11">&nbsp;</td>
          <td width="593"><a href="javascript:ue_catcargos();"><img src="../shared/imagebank/mas.gif" width="9" height="17" border="0"></a><a href="javascript:ue_catcargos();">Agregar Detalle </a></td>
        </tr>
        <tr align="center" class="formato-blanco">
          <td height="11" colspan="2"><?php $io_grid->makegrid($li_filascargos,$la_columcargos,$la_objectcargos,$li_anchocargos,$ls_titulocargos,$ls_nametable);?>          </td>
          <input name="filascargos" type="hidden" id="filascargos" value="<?php print $li_filascargos;?>">
          <input name="hidremovercargo" type="hidden" id="hidremovercargo" value="<?php print $li_removercargo;?>">
        </tr>
        <tr class="formato-blanco">
          <td colspan="2">&nbsp;</td>
        </tr>
      </table></td>
    </tr>
    
    <tr class="formato-blanco">
      <td height="44" colspan="9"><table width="610" border="0" align="center" cellpadding="0" cellspacing="0"  class="sin-borde">
        <tr class="formato-blanco">
          <td width="15" height="13">&nbsp;</td>
          <td width="593"><div align="left"><a href="javascript:ue_catretenciones();"><img src="../shared/imagebank/mas.gif" width="9" height="17" border="0"></a><a href="javascript:ue_catcuentagasto();">Agregar Detalle</a></div></td>
        </tr>
        <tr align="center" class="formato-blanco">
          <td colspan="2"><?php $io_grid->makegrid($li_filas,$la_columna,$la_objeto,$li_ancho,$ls_titulo,$ls_nametable);?>          </td>
        </tr>
        <tr class="formato-blanco">
          <td colspan="2">&nbsp;</td>
        </tr>
      </table></td>
    </tr>
    <tr class="formato-blanco">
      <td height="44">&nbsp;</td>
      <td><div align="right">Observaciones</div></td>
      <td colspan="6"><textarea name="txtobspuncue" cols="80" rows="2" wrap="VIRTUAL" id="txtobspuncue" onKeyDown="textCounter(this,255)"><?php print $ls_obspuncue?></textarea></td>
      <td>&nbsp;</td>
    </tr>
    <tr class="formato-blanco">
      <td height="24">&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="6">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <p>

<input name="hiddatosobra" type="hidden" id="hiddatosobra" value="<?php print $ls_datosobra;?>">
<input name="hidfilas" type="hidden" id="hidfilas" value="<?php print $li_filas;?>">
<input name="hidfilaeliminar" type="hidden" id="hidfilaeliminar" value="<?php print $li_filaeliminar;?>">
<input type="hidden" name="hidimprimir" id="hidimprimir">
  
<!-- Fin de la declaracion de Hidden-->
  </p>
  </form>
</body>
<script language="javascript">

//--------------------------------------Funciones para llamar catalogos---------------------------//
function ue_catobra()
{
	f=document.form1;
	f.operacion.value="";			
	pagina="sigesp_cat_obra.php?estado=''";
	popupWin(pagina,"catalogo",850,400);
}

function ue_catcontratista()
{
	f=document.form1;
	f.operacion.value="";			
	pagina="sigesp_cat_contratista.php";
	popupWin(pagina,"catalogo",600,300);
}


function ue_catcuentagasto()
{
	f=document.form1;
	f.operacion.value="";			
	pagina="sigesp_cat_ctasSPG.php";
	popupWin(pagina,"catalogo",700,600);
}


//--------------------------------------Funciones para cargar datos provenientes de catalogos------------------------------------//
function ue_cargarobra(ls_codigo,ls_descripcion,ls_estado,ls_codest,ls_codten,ls_codtipest,ls_codpai,ls_codmun,ls_codpar,ls_codcom,
  				         ls_codsiscon,ls_codpro,ls_codtob,ls_dirobr,ls_obsobr,ls_resobr,ld_monto,ls_feccreobr,ls_nompro,
				         ls_fechainicio,ls_fechafin,ls_nomten,ls_nomtipest,ls_nomsiscon,ls_nomtob,ls_estado,ls_codpais)
{
	f=document.form1;
	f.operacion.value="";
	f.txtcodobr.value=ls_codigo;	
}

function ue_cargarcontratista(codigo,nombre,rep)
{
	f=document.form1;
	f.txtcodpropuncue.value=codigo;
	f.txtnompropuncue.value=nombre;
	f.txtreplegcon.value=rep;
}

function uf_mostrar_ocultar_obra()
{
	f=document.form1;
	if (f.txtcodobr.value=="")
	{
		alert("Debe seleccionar una Obra!!!");
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

function ue_cargarcuenta(codcuenta,nomcuenta,codest1,codest2,codest3,codest4,codest5,dispo)
{
	f=document.form1;
	f.operacion.value="ue_cargarcuenta";	
	lb_existe=false;
	
	for(li_i=1;li_i<=f.hidfilas.value && !lb_existe;li_i++)
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
	    
		eval("f.txtcodcue"+f.hidfilas.value+".value='"+codpre+"'");
		eval("f.codest1"+f.hidfilas.value+".value='"+codest1+"'");
		eval("f.codest2"+f.hidfilas.value+".value='"+codest2+"'");
		eval("f.codest3"+f.hidfilas.value+".value='"+codest3+"'");
		eval("f.codest4"+f.hidfilas.value+".value='"+codest4+"'");
		eval("f.codest5"+f.hidfilas.value+".value='"+codest5+"'");
		eval("f.txtnomcue"+f.hidfilas.value+".value='"+codcuenta+"'");
		eval("f.txtmoncue"+f.hidfilas.value+".value='0,00'");
		eval("f.disponible"+f.hidfilas.value+".value='"+dispo+"'");		
		f.submit();
	}
}

function ue_removercuenta(fila)
{
	f=document.form1;
	f.hidfilaeliminar.value=fila;
	f.operacion.value="ue_removercuenta"	
	f.submit();
}

function ue_cargarpuntodecuenta(ls_codobr,ls_codpuncue,ls_rempuncue,ls_despuncue,ls_asupuncue,ls_fecpuncue,ls_codpropuncue,
								ls_nompropuncue,ls_replegpuncue,ls_lapejepuncue,ls_lapejeunipuncue,ls_monnetpuncue,ls_monivapuncue,
								ls_porivapuncue,ls_monantpuncue,ls_porantpuncue,ls_obspuncue,ls_monbrupuncue)
{
	with(document.form1)
	{
		txtcodobr.value=ls_codobr;
		txtcodpuncue.value=ls_codpuncue;
		txtrempuncue.value=ls_rempuncue;
		txtdespuncue.value=ls_despuncue;
		txtasupuncue.value=ls_asupuncue;
		txtfecpuncue.value=ls_fecpuncue;
		txtcodpropuncue.value=ls_codpropuncue;
		txtnompropuncue.value=ls_nompropuncue;
		txtreplegcon.value=ls_replegpuncue;
		txtlapejepuncue.value=ls_lapejepuncue;
		cmblapejeunipuncue.value=ls_lapejeunipuncue;
		txtmonnetpuncue.value=ls_monnetpuncue;
		txtmonivapuncue.value=ls_monivapuncue;
		txtbasimp.value=ls_porivapuncue;
		txtmonantpuncue.value=ls_monantpuncue;
		txtporantpuncue.value=ls_porantpuncue;
		txtobspuncue.value=ls_obspuncue;
		txtmonbrupuncue.value=ls_monbrupuncue;
		hidstatus.value="C";
		//hidlapejeunipuncue.value=ls_lapejeunipuncue;
		operacion.value="ue_cargarpuntodecuenta";
		submit();
	}
}



//---------------------------------------Validaciones---------------------------------------------------------------------------//

function ue_validardispo(fila)
{
	f=document.form1;
	ls_cajita=fila.id;
	ls_cajita=ls_cajita.replace("txtmoncue","");	
	if(eval("f.monto"+ls_cajita+".checked"))
	{
		ld_montomaximo=parseFloat(uf_convertir_monto(f.txtmonnetpuncue.value));		
		lb_continuar=true;
		ls_tipocajita="monto";
	}		
	else
	{
		if(eval("f.iva"+ls_cajita+".checked"))
		{
			ld_montomaximo=parseFloat(uf_convertir_monto(f.txtmonivapuncue.value));		
			lb_continuar=true;
			ls_tipocajita="iva"
		}
		else
		{
			alert("Debe seleccionar el Concepto de la Cuenta!!!");
			lb_continuar=false;
			fila.value="0,00";
		}
	}
	if(lb_continuar)
	{
		ld_nuevomonto=0;		
		for(li_i=1;li_i<f.hidfilas.value;li_i++)
		{
			if(ls_tipocajita=="monto")
			{
				if(eval("f.monto"+li_i+".checked"))
					ld_nuevomonto=ld_nuevomonto+parseFloat(uf_convertir_monto(eval("f.txtmoncue"+li_i+".value")));
			}
			else
			{
				if(eval("f.iva"+li_i+".checked"))
					ld_nuevomonto=ld_nuevomonto+parseFloat(uf_convertir_monto(eval("f.txtmoncue"+li_i+".value")));
			}
			
		}	
		if(ld_montomaximo<ld_nuevomonto)
		{
			lb_continuar=false;			
			if(ls_tipocajita=="monto")
				alert("El monto asignado a la cuenta debe ser menor que el Monto Neto!!!");
			else
				alert("El monto asignado a la cuenta debe ser menor que el Monto del I.V.A.!!!");
			fila.value="0,00";
		}
		else
			lb_continuar=true;
		
		if(lb_continuar)
		{
			ld_disponible=parseFloat(uf_convertir_monto(eval("f.disponible"+ls_cajita+".value")));		
			if(ld_nuevomonto>ld_disponible)
			{
				alert("El monto asignado a la cuenta es mayor que su Diponibilidad!!!")
				fila.value="0,00";
			}
		}	
	}
}

function ue_verificariva(chk)
{	
	f=document.form1;	
	ls_numero=(chk.id).replace("iva","");
	for(li_i=1;li_i<f.hidfilas.value;li_i++)
	{
		if(li_i!=ls_numero)
		{
			if(eval("f.iva"+li_i+".checked"))
			{
				eval("f.iva"+li_i+".checked=false;");
				eval("f.monto"+li_i+".checked=true;");
				ue_cargarmonto(eval("f.monto"+li_i));
				break;
			}
		}		
	}	
	
	ue_cargarmonto(chk);
}

function ue_cargarmonto(chk)
{
	f=document.form1;	
	if((chk.id).substr(0,1)=="i")
	{		
		ls_numero=(chk.id).replace("iva","");
		ld_disponible=parseFloat(uf_convertir_monto(eval("f.disponible"+ls_numero+".value")));		
		if(parseFloat(uf_convertir_monto(f.txtmonivapuncue.value))>ld_disponible)
		{
			alert("El monto asignado a la cuenta es mayor que su Diponibilidad!!!")
			eval("f.txtmoncue"+ls_numero+".value='0,00';");
			eval("f.iva"+ls_numero+".checked=false;");
		}
		else
		{
			eval("f.txtmoncue"+ls_numero+".value=f.txtmonivapuncue.value;");
		}		
	}
	else
	{
		ls_numero=(chk.id).replace("monto","");		
		ld_disponible=parseFloat(uf_convertir_monto(eval("f.disponible"+ls_numero+".value")));
		if(parseFloat(uf_convertir_monto(f.txtmonnetpuncue.value))<=ld_disponible)
		{		
			var li_haymas=false;
			for(li_i=1;li_i<f.hidfilas.value;li_i++)
			{
				if(li_i!=ls_numero)
				{
					if(eval("f.monto"+li_i+".checked"))
					{
						li_haymas=true;
						break;
					}
				}
			}
			if(!li_haymas)
			{
				eval("f.txtmoncue"+ls_numero+".value=f.txtmonnetpuncue.value;");
			}
			else
			{
				eval("f.txtmoncue"+ls_numero+".value='0,00';");
			}
		}
		else
		{
			ls_disponible=uf_convertir(ld_disponible);
			eval("f.txtmoncue"+ls_numero+".value=ls_disponible;");
		}
	}	
}

function ue_calcularmonto(porcentaje,montofinal)
{
	f=document.form1;
	if(porcentaje.value!="" && parseFloat(uf_convertir_monto(porcentaje.value))!=0)
	{
		montofinal.value=uf_convertir((parseFloat(uf_convertir_monto(porcentaje.value))*parseFloat(uf_convertir_monto(f.txtmonnetpuncue.value)))/100);
		ue_calcular_monto_bruto();
	}
	else
	{
		montofinal.value="0,00";
	}
}

function ue_calcularporcentajes(porcentaje1,porcentaje2,montofinal1,montofinal2)
{
	f=document.form1;
	if(f.txtmonnetpuncue.value!="" && parseFloat(uf_convertir_monto(f.txtmonnetpuncue.value))!=0)
	{
		if(parseFloat(uf_convertir_monto(porcentaje1.value))!=0)
			ue_calcularmonto(porcentaje1,montofinal1);
		if(parseFloat(uf_convertir_monto(porcentaje2.value))!=0)
			ue_calcularmonto(porcentaje2,montofinal2);		
	}
	else
	{
		montofinal1.value="0,00";
		montofinal2.value="0,00";
	}
	ue_calcular_monto_bruto();
}

function ue_calcular_monto_bruto()
{
	f=document.form1;
	if(f.txtmonnetpuncue.value!="")
	{
		ls_montoneto=parseFloat(uf_convertir_monto(f.txtmonnetpuncue.value));
		ls_montoiva=parseFloat(uf_convertir_monto(f.txtmonivapuncue.value));
		f.txtmonbrupuncue.value=uf_convertir(ls_montoneto+ls_montoiva);
	}
	else
	{
		f.txtmonbrupuncue.value="0,00";
	}
}

function ue_validar_guardar()
{
		lb_guardar=true;
		f=document.form1;
		if(!ue_valida_null(f.txtcodobr,"Código de la Obra"))
		{
			lb_guardar=false;
		}
		else
		{
			if(!ue_valida_null(f.txtcodpuncue,"Código del Punto de Cuenta"))
			{
				lb_guardar=false;
			}
			else
			{
				if(!ue_valida_null(f.txtfecpuncue,"Fecha"))
				{
					lb_guardar=false;
				}
				else
				{
					if(!ue_valida_null(f.txtdespuncue,"Presentado a"))
					{
						lb_guardar=false;
					}
					else
					{
						if(!ue_valida_null(f.txtrempuncue,"Presentado por"))
						{
							lb_guardar=false;
						}
						else
						{
							if(!ue_valida_null(f.txtasupuncue,"Asunto"))
							{
								lb_guardar=false;
							}
							else
							{
								if(!ue_valida_null(f.txtcodpropuncue,"Contratista"))
								{
									lb_guardar=false;
								}
								else
								{
									if(f.txtmonnetpuncue.value=="0,00")
									{
										alert("El campo Monto Neto está vacío!!!")
										lb_guardar=false;
									}		
									else
									{
										if(f.txtlapejepuncue.value!="0,00")
										{
											if(f.cmblapejeunipuncue.value=="---")
											{
												alert("El campo Unidad para el Lapso de Ejecución está vacío!!!");
												lb_guardar=false;
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
		
		if(lb_guardar)
		{
			lb_haycuenta=false;			
			for(li_i=1;li_i<f.hidfilas.value;li_i++)
			{
				if(eval("f.monto"+li_i+".checked"))
				{
					lb_haycuenta=true;
					break;
				}
			}
			if(!lb_haycuenta)
			{
				alert("Debe especificar una Cuenta de Gastos para el Monto Neto!!!");
				lb_guardar=false;
			}
		}
		
		if(lb_guardar)
		{
			lb_haycuenta=false;			
			for(li_i=1;li_i<f.hidfilas.value;li_i++)
			{
				if(eval("f.iva"+li_i+".checked"))
				{
					lb_haycuenta=true;
					break;
				}
			}
			
			if(f.txtmonivapuncue.value!="0,00" && !lb_haycuenta)			
			{
				alert("Debe especificar una Cuenta de Gastos para el Monto del I.V.A.!!!");
				lb_guardar=false;
			}
			else
			{
				if(f.txtmonivapuncue.value=="0,00" && lb_haycuenta)
				{
					alert("Debe especificar el Monto del I.V.A.!!!");
					lb_guardar=false;
				}
			}
		}		
		
		if(lb_guardar)
		{
			lb_nohaymonto=false;			
			for(li_i=1;li_i<f.hidfilas.value;li_i++)
			{
				if(eval("f.txtmoncue"+li_i+".value=='0,00'"))
				{
					lb_nohaymonto=true;
					break;
				}
			}
			if(lb_nohaymonto)
			{
				alert("Debe especificar todos los montos de las Cuentas de Gastos!!!");
				lb_guardar=false;
			}
		}		
		
		if(lb_guardar)
		{
			ld_monto=0;
			ld_iva=0;
			for(li_i=1;li_i<f.hidfilas.value;li_i++)
			{
				ld_cantidad=eval("f.txtmoncue"+li_i+".value;");
				ld_cantidad=parseFloat(uf_convertir_monto(ld_cantidad));
				if(eval("f.monto"+li_i+".checked"))
				{
					ld_monto+=ld_cantidad;
				}
				else
				{
					ld_iva+=ld_cantidad;
				}
			}
			
			if(uf_convertir(ld_monto)!=f.txtmonnetpuncue.value)
			{
				alert("La sumatoria de los montos asignados a las Cuentas de Gastos para el Monto Neto \n debe ser igual al Total!!!")
				lb_guardar=false;
			}
			else
			{
				if(uf_convertir(ld_iva)!=f.txtmonivapuncue.value)
				{
					alert("El monto asignado a la Cuentas de Gastos para el I.V.A. \n debe ser igual al total!!!")
					lb_guardar=false;
				}
			}
		}
		

	return lb_guardar;
}

//-------------------------------------------------Funciones de Actualizacion------------------------------------//
function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		if(f.txtcodobr.value=="")
		{
			alert("Debe seleccionar una Obra!!!");
		}
		else
		{
			f.operacion.value="ue_nuevo";			
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
		pagina="sigesp_cat_puntodecuenta.php";
		popupWin(pagina,"catalogo",750,350);

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
		lb_guardar=ue_validar_guardar()		
		if(lb_guardar)
		{
			f.operacion.value="ue_guardar";
			f.submit();
		}
								
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
	   if (f.txtcodpuncue.value=="")
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
				 f.submit();
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
	lb_imprimir=ue_validar_guardar()
	if(lb_imprimir)
	{
		f.operacion.value="ue_imprimir";
		f.submit();		    
	}
}
	
function ue_cargarcargo(cod,nom,formula,progra,spg_cu)
{
	f=document.form1;
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
	
	if(!lb_existe)
	{
		eval("f.txtcodcar"+f.filascargos.value+".value='"+cod+"'");
		eval("f.txtnomcar"+f.filascargos.value+".value='"+nom+"'");
		eval("f.formula"+f.filascargos.value+".value='"+formula+"'");
		eval("f.prog"+f.filascargos.value+".value='"+progra+"'");
		eval("f.spgcuenta"+f.filascargos.value+".value='"+spg_cu+"'");
    	f.submit();
	}
}

function ue_removercargo(li_fila)
{
	f=document.form1;
	f.hidremovercargo.value=li_fila;
	f.operacion.value="ue_removercargo"
	f.action="sigesp_sob_d_puntodecuenta.php";
	f.submit();
}

function ue_catcargos()
{
	f=document.form1;
	f.operacion.value="";
	if((f.txtbasimp.value=="")||(f.txtbasimp.value=="0,00"))
	{
	 alert("Debe indicar la Base imponible a la cual se le aplicaran los Cargos!!")
	}
	else
	{			
	pagina="sigesp_cat_cargos.php";
	popupWin(pagina,"catalogo",650,400);
	//window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=200,resizable=yes,location=no,status=yes");
	}
}


</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>