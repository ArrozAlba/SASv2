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
	$io_fun_sob->uf_load_seguridad("SOB","sigesp_sob_d_acta.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Elaboraci&oacute;n de Actas</title>
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
<script type="text/javascript">
	var popupwin = null;
	function popupWin(url,name) {
	popupwin = window.open(url,name,'width=400,height=150,resizable=yes,status=yes');
	}
	if (!document.all) {
	document.captureEvents (Event.CLICK);
	}
	document.onclick = function() {
	if (popupwin != null && !popupwin.closed) {
	popupwin.focus();
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
<?php
require_once("../shared/class_folder/class_sql.php");
require_once("class_folder/sigesp_sob_c_contrato.php");
$io_contrato=new sigesp_sob_c_contrato();
require_once("class_folder/sigesp_sob_class_obra.php");
$io_obra=new sigesp_sob_class_obra();
require_once("class_folder/sigesp_sob_c_acta.php");
$io_acta=new sigesp_sob_c_acta();
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
require_once("class_folder/sigesp_sob_class_mensajes.php");
$io_mensaje=new sigesp_sob_class_mensajes();
require_once("../shared/class_folder/sigesp_include.php");
$io_siginc=new sigesp_include();
$io_connect=$io_siginc->uf_conectar();
require_once("../shared/class_folder/class_sql.php");
$io_sql=new class_sql($io_connect);
$la_empresa=$_SESSION["la_empresa"];
require_once ("class_folder/sigesp_c_generar_consecutivo_acta.php");
$io_keygen= new sigesp_c_generar_consecutivo_acta(); 

function uf_generar_codigoacta($as_tipoacta,$as_codcon,$io_sql,$la_empresa,$io_function)
{
	  //////////////////////////////////////////////////////////////////////////////
	 //	Metodo: uf_generarcodigoacta
	 //	Access:  public
	 //	Returns: proximo codigo del acta buscada
	 //	Description: Funcion que permite generar el proximo codigo de un acta, dependiendo de su tipo
	 // Fecha: 06/04/2006
	 // Autor: Ing. Laura Cabré
	 //////////////////////////////////////////////////////////////////////////////
	$ls_codemp=$la_empresa["codemp"];
	$ls_sql="SELECT codact FROM sob_acta WHERE codemp='".$ls_codemp."' AND codcon='".$as_codcon."' AND tipact='".$as_tipoacta."' ORDER BY codact DESC";		
	$rs_data=$io_sql->select($ls_sql);
	if ($row=$io_sql->fetch_row($rs_data))
	{ 
	  $codigo=$row["codact"];
	  settype($codigo,'int');                             // Asigna el tipo a la variable.
	  $codigo = $codigo + 1;                              // Le sumo uno al entero.
	  settype($codigo,'string');                          // Lo convierto a varchar nuevamente.
	  $ls_codigo=$io_function->uf_cerosizquierda($codigo,6);	 
	}
	else
	{
	  $codigo="1";
	  $ls_codigo=$io_function->uf_cerosizquierda($codigo,6);
	
	}

  return $ls_codigo;	
}


$ls_tituloretenciones="Retenciones Asignadas";
$li_anchoretenciones=600;
$ls_nametable="grid";
$la_columretenciones[1]="Código";
$la_columretenciones[2]="Descripción";
$la_columretenciones[3]="Cuenta";
$la_columretenciones[4]="Deducible";
$la_columretenciones[5]="Edición";

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_tipoacta=$_POST["cmbtipoacta"];
	//$ls_tipoacta=$_POST["hidtipoacta"];
}
else
{
	$ls_datoscontrato="OCULTAR";
	$ls_datosobra="OCULTAR";
	$ls_fecinicon="";
	$ls_placon="";
	$ls_placonuni="";
	$ls_contasi="";
	$ls_contasi="";
	$ls_moncon="";
	$ls_estcon="";
	$ls_codobr="";
	$ls_desobr="";
	$ls_estobr="";
	$ls_control="";
	$ls_estobr="";
	$ls_munobr="";
	$ls_comobr="";
	$ls_codigo="";
	$ls_parobr="";
	$ls_dirobr="";
	$ls_codcon="";
	$ls_codact="";
	$ls_fecact="";
	$ls_obsact="";
	$ls_feciniact="";
	$ls_fecfinact="";
	$ls_nominsact="";
	$ls_cedinsact="";
	$ls_nomsupact="";
	$ls_estact="";
	$ls_cedsupact="";
	$ls_nomresact="";
	$ls_fecrecact="";
	$ls_motact="";
	$ls_codproins="";
	$ls_codpro="";
	$ls_cedresact="";
	$ls_civresact="";
	$ls_civsup="";
	$ls_civinsact="";
	$ls_tipoacta="s1";	
}

/////////Instrucciones para evitar que las cajitas pierdan la informacion cada vez que se realiza un submit/////////////
if	(array_key_exists("hiddatoscontrato",$_POST)){$ls_datoscontrato=$_POST["hiddatoscontrato"]; }
else{$ls_datoscontrato="OCULTAR";}

if	(array_key_exists("hiddatosobra",$_POST)){	$ls_datosobra=$_POST["hiddatosobra"]; }
else{$ls_datosobra="OCULTAR";}

if	(array_key_exists("operacion",$_POST)){	$ls_operacion=$_POST["operacion"]; }
else{$ls_operacion="";}

if	(array_key_exists("txtcodcon",$_POST)){	$ls_codigo=$_POST["txtcodcon"]; }
else{$ls_codigo="";}

if	(array_key_exists("txtobsact",$_POST)){	$ls_obsact=$_POST["txtobsact"]; }
else{$ls_obsact="";}

if	(array_key_exists("txtfecinicon",$_POST)){$ls_fecinicon=$_POST["txtfecinicon"]; }
else{$ls_fecinicon="";}

if	(array_key_exists("hidplacon",$_POST)){$ls_placon=$_POST["hidplacon"]; }
else{$ls_placon="0";}

if	(array_key_exists("hidplaconuni",$_POST)){$ls_placonuni=$_POST["hidplaconuni"]; }
else{$ls_placonuni="";}

if	(array_key_exists("txtcontasi",$_POST)){$ls_contasi=$_POST["txtcontasi"]; }
else{$ls_contasi="";}

if	(array_key_exists("txtmoncon",$_POST)){$ls_moncon=$_POST["txtmoncon"]; }
else{$ls_moncon="";}	

if	(array_key_exists("txtestcon",$_POST)){$ls_estcon=$_POST["txtestcon"]; }
else{$ls_estcon="";}	

if	(array_key_exists("txtcodobr",$_POST)){$ls_codobr=$_POST["txtcodobr"]; }
else{$ls_codobr="";}

if	(array_key_exists("txtdesobr",$_POST)){$ls_desobr=$_POST["txtdesobr"]; }
else{$ls_desobr="";}

if	(array_key_exists("txtestobr",$_POST)){$ls_estobr=$_POST["txtestobr"]; }
else{$ls_estobr="";}

if	(array_key_exists("txtmunobr",$_POST)){$ls_munobr=$_POST["txtmunobr"]; }
else{$ls_munobr="";}

if	(array_key_exists("txtcomobr",$_POST)){$ls_comobr=$_POST["txtcomobr"]; }
else{$ls_comobr="";}

if	(array_key_exists("txtparobr",$_POST)){$ls_parobr=$_POST["txtparobr"]; }
else{$ls_parobr="";}

if	(array_key_exists("txtdirobr",$_POST)){$ls_dirobr=$_POST["txtdirobr"]; }
else{$ls_dirobr="";}

if	(array_key_exists("txtcodact",$_POST)){$ls_codact=$_POST["txtcodact"]; }
else{$ls_codact="";}

if	(array_key_exists("txtfecact",$_POST)){$ls_fecact=$_POST["txtfecact"]; }
else{$ls_fecact="";}

if	(array_key_exists("txtfeciniact",$_POST)){$ls_feciniact=$_POST["txtfeciniact"]; }
else{$ls_feciniact="";}

if	(array_key_exists("txtfecfinact",$_POST)){$ls_fecfinact=$_POST["txtfecfinact"]; }
else{$ls_fecfinact="";}

if	(array_key_exists("txtnominsact",$_POST)){$ls_nominsact=$_POST["txtnominsact"]; }
else{$ls_nominsact="";}

if	(array_key_exists("txtcedinsact",$_POST)){$ls_cedinsact=$_POST["txtcedinsact"]; }
else{$ls_cedinsact="";}

if	(array_key_exists("txtnomsupact",$_POST)){$ls_nomsupact=$_POST["txtnomsupact"]; }
else{$ls_nomsupact="";}

if	(array_key_exists("txtcedsupact",$_POST)){$ls_cedsupact=$_POST["txtcedsupact"]; }
else{$ls_cedsupact="";}

if	(array_key_exists("txtnomresact",$_POST)){$ls_nomresact=$_POST["txtnomresact"]; }
else{$ls_nomresact="";}

if	(array_key_exists("txtcedresact",$_POST)){$ls_cedresact=$_POST["txtcedresact"]; }
else{$ls_cedresact="";}

if	(array_key_exists("txtcivresact",$_POST)){$ls_civresact=$_POST["txtcivresact"]; }
else{$ls_civresact="";}

if	(array_key_exists("txtcivinsact",$_POST)){$ls_civinsact=$_POST["txtcivinsact"]; }
else{$ls_civinsact="";}	

if	(array_key_exists("hidcodproins",$_POST)){$ls_codproins=$_POST["hidcodproins"]; }
else{$ls_codproins="";}	

if	(array_key_exists("hidcodpro",$_POST)){$ls_codpro=$_POST["hidcodpro"]; }
else{$ls_codpro="";}	

if	(array_key_exists("txtestact",$_POST)){$ls_estact=$_POST["txtestact"]; }
else{$ls_estact="";}

if	(array_key_exists("hidcontrol",$_POST)){$ls_control=$_POST["hidcontrol"]; }
else{$ls_control="";}	

if	(array_key_exists("txtfecrecact",$_POST)){$ls_fecrecact=$_POST["txtfecrecact"]; }
else{$ls_fecrecact="";}

if	(array_key_exists("txtmotact",$_POST)){$ls_motact=$_POST["txtmotact"]; }
else{$ls_motact="";}

if	(array_key_exists("hidcodigo",$_POST)){$ls_codcon=$_POST["hidcodigo"]; }
else{$ls_codcon="";}
if	(array_key_exists("hidstatus",$_POST)){$ls_status=$_POST["hidstatus"]; }
else{$ls_status="";}


////////////////////////////////Operaciones de Actualizacion//////////////////////////////////////

if($ls_operacion=="ue_nuevo")//Abre una ficha de obra nueva
{
	require_once("../shared/class_folder/class_funciones_db.php");
	require_once ("../shared/class_folder/sigesp_include.php");		
	$io_include=new sigesp_include();
	$io_connect=$io_include->uf_conectar();
	$io_funcdb=new class_funciones_db($io_connect);
	$la_empresa=$_SESSION["la_empresa"];
	$lb_tieneacta=$io_acta->uf_revisar_contrato_acta($ls_codcon,$ls_tipoacta,$la_seguridad);
	if(!$lb_tieneacta)
	{
	//	$ls_codact=uf_generar_codigoacta($ls_tipoacta,$ls_codcon,$io_sql,$la_empresa,$io_function);						
		$ls_codact= $io_keygen->uf_generar_numero_nuevo("SOB","sob_acta","codact","SOBACT",6,"","codcon",$ls_codcon,"tipact",$ls_tipoacta);
		$ls_feciniact="";
		$ls_fecfinact="";
		$ls_nominsact="";
		$ls_cedinsact="";
		$ls_nomsupact="";
		$ls_cedsupact="";
		$ls_nomresact="";
		$ls_cedresact="";
		$ls_civinsact="";
		$ls_obsact="";
		$ls_civsup="";
		$ls_fecrecact="";
		$ls_motact="";
		$ls_control="";
		$ls_civresact="";
		$ls_estact="EMITIDO";
		$fecha=date("d/m/Y");
		$ls_fecact=$fecha;	
	}
	else
	{
		$io_msg->message("Este Contrato ya tiene un Acta de Inicio!!!");
		$ls_datoscontrato="OCULTAR";
		$ls_datosobra="OCULTAR";
		$ls_placon="";
		$ls_placonuni="";
		$ls_contasi="";
		$ls_contasi="";
		$ls_moncon="";
		$ls_estcon="";
		$ls_codobr="";
		$ls_desobr="";
		$ls_estobr="";
		$ls_estobr="";
		$ls_munobr="";
		$ls_comobr="";
		$ls_obsact="";
		$ls_estact="";
		$ls_parobr="";
		$ls_dirobr="";
		$ls_codcon="";
		$ls_codact="";
		$ls_fecact="";
		$ls_feciniact="";
		$ls_fecfinact="";
		$ls_motact="";
		$ls_fecrecact="";
		$ls_nominsact="";
		$ls_cedinsact="";
		$ls_control="";
		$ls_nomsupact="";
		$ls_cedsupact="";
		$ls_nomresact="";
		$ls_codproins="";
		$ls_codpro="";
		$ls_cedresact="";
		$ls_civresact="";
		$ls_civsup="";
		$ls_civinsact="";
	}
	
}
elseif($ls_operacion=="ue_cargarcontrato")
{
	$lb_valido=$io_contrato->uf_select_contrato($ls_codcon,$la_data);
	if($lb_valido)
	{
		$ls_fecinicon=$io_function->uf_convertirfecmostrar($la_data["fecinicon"][1]);
		$ls_placon=$io_funsob->uf_convertir_decimalentero($la_data["placon"][1]);
		$ls_placonuni=$io_funsob->uf_convertir_letraunidad($la_data["placonuni"][1]);
		$ls_contasi=$la_data["nompro"][1];
		$ls_moncon=$io_funsob->uf_convertir_numerocadena($la_data["monto"][1]);
		$ls_estcon=$io_funsob->uf_convertir_numeroestado($la_data["estcon"][1]);
		$ls_codobr=$la_data["codobr"][1];
		$ls_codcon=$la_data["codcon"][1];
		$lb_valido=$io_obra->uf_select_obra($ls_codobr,$la_data);
		if($lb_valido)
		{
			$ls_desobr=$la_data["desobr"][1];
			$ls_estobr=$la_data["desest"][1];
			$ls_munobr=$la_data["denmun"][1];
			$ls_comobr=$la_data["nomcom"][1];
			$ls_parobr=$la_data["denpar"][1];
			$ls_dirobr=$la_data["dirobr"][1];
		}
	}
}
elseif($ls_operacion=="ue_guardar")
{
	
	if($ls_fecact!="")
		$ls_fecact=$io_function->uf_convertirdatetobd($ls_fecact);
	if($ls_feciniact!="")
		$ls_feciniact=$io_function->uf_convertirdatetobd($ls_feciniact);
	if($ls_fecfinact!="")
		$ls_fecfinact=$io_function->uf_convertirdatetobd($ls_fecfinact);
	if($ls_fecrecact!="")
		$ls_fecrecact=$io_function->uf_convertirdatetobd($ls_fecrecact);
	$li_numero=0;
	$io_acta->io_sql->begin_transaction();
	$lb_existe=$io_acta->uf_select_acta($ls_codcon,$ls_codact,$ls_tipoacta,&$aa_data);
	if($ls_status!="C")
	{	
		$ls_codactaux=$ls_codact;
		$lb_valido=$io_acta->uf_guardar_acta($ls_codcon,$ls_codact,$ls_tipoacta,$ls_fecact,$ls_feciniact,$ls_fecfinact,
											 $ls_fecrecact,$li_numero,"001",$ls_motact,"",$ls_cedinsact,$ls_cedresact,
											 $ls_cedsupact,"",$ls_obsact,$ls_civinsact,$ls_nomresact,$ls_civresact,
											 $la_seguridad);
			if ($lb_valido)
			{
				$lb_valido=$io_contrato->uf_update_ultimoacta($ls_codcon,$ls_tipoacta,$la_seguridad);
				$ls_tipofecha="";
				if($lb_valido)
				{
					switch($ls_tipoacta)
					{
						case 1:
							$li_estado=10;
							$ls_tipofecha="inicio";
							$ls_fecha=$ls_feciniact;
						break;
						case 2:
							$li_estado=8;
							$ls_tipofecha="finalizacion";
							$ls_fecha=$ls_fecfinact;
						break;
						case 5:
							$lb_valido=$io_contrato->uf_select_estado($ls_codcon,$li_estadoanterior);
							if($lb_valido)
							{
								if($li_estadoanterior==9)
									$li_estado=11;
								else
									$li_estado=7;
							}
						break;
						case 6:
							$lb_valido=$io_contrato->uf_select_estado($ls_codcon,$li_estadoanterior);
							if($lb_valido)
							{
								if($li_estadoanterior==11)
									$li_estado=9;
								else
									$li_estado=10;
							}							
						break;
						case 7:
							$li_estado=9;
						break;
						default:
							$li_estado="";						
					}
					if($li_estado=="")
					{
						$lb_valido=true;
					}
					else
					{
						$lb_valido=$io_contrato->uf_update_estado($ls_codcon,$li_estado,$la_seguridad);
					}
					if($lb_valido)
					{
						if($ls_tipofecha=="")
						{
							$lb_valido=true;
						}
						else
						{
							$lb_valido=$io_contrato->uf_update_fechasreales($ls_codcon,$ls_fecha,$ls_tipofecha,$la_seguridad);
						}
						if($lb_valido)
						{
							if($ls_codactaux!=$ls_codact)
							{
								$io_msg->message("Se le asigno el nuevo numero ".$ls_codact." ");
							}
							$io_mensaje->incluir();
							$io_acta->io_sql->commit();
							$ls_feciniact=$io_function->uf_convertirfecmostrar($ls_feciniact);
							$ls_fecrecact=$io_function->uf_convertirfecmostrar($ls_fecrecact);
							$ls_fecfinact=$io_function->uf_convertirfecmostrar($ls_fecfinact);
							$ls_fecact=$io_function->uf_convertirfecmostrar($ls_fecact);						
							$ls_imprimir=$_POST["hidimprimir"];
							if($ls_imprimir=="IMPRIMIR")
							{
								  $ls_documento="CONTRATO";
								  $ls_pagina="sigesp_sob_d_filechooser.php?codcon=".$ls_codcon."&documento=".$ls_documento;
								  print "<script language=JavaScript>";
								  print "popupWin('".$ls_pagina."','ventana');";
								  print "</script>";
							}					
						}
						else
						{
							$io_msg->message("Se produjo un error al procesar la operacion");
							$io_acta->io_sql->rollback();
						}
					}
				}
				else
				{
					$io_msg->message("Error actualizando ultimo acta del contrato");
				}					
			}
			else
			{
				$io_mensaje->error_incluir();
			}
		}/*************************************End del if si no existe (Guardar)*************************/
		else
		{
			if($lb_existe)
			{
				$lb_valido=$io_acta->uf_select_estado($ls_codcon,$ls_codact,$ls_tipoacta,$li_estado);
				if($li_estado==1)
				{	
					$lb_valido=$io_acta->uf_update_acta($ls_codcon,$ls_codact,$ls_tipoacta,$ls_fecact,$ls_feciniact,$ls_fecfinact,
														$ls_fecrecact,$li_numero,"001",$ls_motact,"",$ls_cedinsact,$ls_cedresact,
														$ls_cedsupact,"",$ls_obsact,$ls_civinsact,$ls_nomresact,$ls_civresact,
														$la_seguridad);
					if($lb_valido)
					{					
						$ls_datoscontrato="OCULTAR";
						$ls_datosobra="OCULTAR";
						$ls_placon="";
						$ls_placonuni="";
						$ls_contasi="";
						$ls_contasi="";
						$ls_moncon="";
						$ls_estcon="";
						$ls_codobr="";
						$ls_desobr="";
						$ls_estobr="";
						$ls_estobr="";
						$ls_munobr="";
						$ls_comobr="";
						$ls_estact="";
						$ls_obsact="";
						$ls_parobr="";
						$ls_dirobr="";
						$ls_tipoacta="s1";
						$ls_codcon="";
						$ls_codact="";
						$ls_fecact="";
						$ls_feciniact="";
						$ls_fecfinact="";
						$ls_nominsact="";
						$ls_motact="";
						$ls_fecrecact="";
						$ls_control="";
						$ls_cedinsact="";
						$ls_nomsupact="";
						$ls_cedsupact="";
						$ls_nomresact="";
						$ls_codproins="";
						$ls_codpro="";
						$ls_cedresact="";
						$ls_civresact="";
						$ls_civsup="";
						$ls_civinsact="";
						if($lb_valido===true)
							$io_mensaje->modificar();
					}
					else
					{
						$io_mensaje->error_modificar();
					}
					
				}
				else
				{
					$ls_estado=$io_funsob->uf_convertir_numeroestado($li_estado);
					$io_msg->message("El Acta no puede ser modificada, su estado es $ls_estado");
				}
			}
			else
			{
				$io_msg->message("El Acta a modificar no existe registrada");
			}
		}		
}
elseif($ls_operacion=="ue_eliminar")
{
	$lb_valido=$io_acta->uf_select_estado($ls_codcon,$ls_codact,$ls_tipoacta,$li_estado);
	if($lb_valido)
	{
		if($li_estado==1)
		{
			$lb_valido=$io_acta->uf_update_estado($ls_codcon,$ls_codact,$ls_tipoacta,3,$la_seguridad);
			if($lb_valido)
				$io_mensaje->anular();
			else
				$io_mensaje->error_anular();
			$ls_datoscontrato="OCULTAR";
					$ls_datosobra="OCULTAR";
					$ls_placon="";
					$ls_placonuni="";
					$ls_contasi="";
					$ls_contasi="";
					$ls_moncon="";
					$ls_tipoacta="s1";
					$ls_estcon="";
					$ls_codobr="";
					$ls_desobr="";
					$ls_estobr="";
					$ls_estobr="";
					$ls_munobr="";
					$ls_comobr="";
					$ls_estact="";
					$ls_parobr="";
					$ls_dirobr="";
					$ls_codcon="";
					$ls_codact="";
					$ls_fecact="";
					$ls_feciniact="";
					$ls_fecfinact="";
					$ls_motact="";
					$ls_control="";
					$ls_nominsact="";
					$ls_cedinsact="";
					$ls_obsact="";					
					$ls_nomsupact="";
					$ls_fecrecact="";
					$ls_cedsupact="";
					$ls_nomresact="";
					$ls_codproins="";
					$ls_codpro="";
					$ls_cedresact="";
					$ls_civresact="";
					$ls_civsup="";
					$ls_civinsact="";	
		}
		else
		{
			$ls_estado=$io_funsob->uf_convertir_numeroestado($li_estado);
			$io_msg->message("El Acta no puede ser anulada, su estado es $ls_estado");
		}
	}
}
elseif($ls_operacion=="ue_cargaracta")
{
	$ls_codact=$_POST["codact"];
	$ls_codcon=$_POST["hidcodigo"];	
	$ls_codigo=$_POST["codcon"];
	$ls_fecact=$_POST["fecact"];
	$ls_cedinsact=$_POST["cedinsact"];
	$ls_cedresact=$_POST["cedresact"];
	$ls_nominsact=$_POST["nominsact"];
	$ls_civinsact=$_POST["civinsact"];
	$ls_nomresact=$_POST["nomresact"];
	$ls_civresact=$_POST["civresact"];
	$ls_estact=$_POST["estact"];
	$ls_obsact=$_POST["obsact"];
	$ls_feciniact=$_POST["feciniact"];
	$ls_fecfinact=$_POST["fecfinact"];
	$ls_fecrecact=$_POST["fecrecact"];	
	$ls_motact=$_POST["hidmotivo"];
}
elseif($ls_operacion=="ue_cambiaracta")
{
	$ls_datoscontrato="OCULTAR";
	$ls_datosobra="OCULTAR";
	$ls_fecinicon="";
	$ls_placon="";
	$ls_placonuni="";
	$ls_codigo="";
	$ls_contasi="";
	$ls_contasi="";
	$ls_moncon="";
	$ls_estcon="";
	$ls_codobr="";
	$ls_desobr="";
	$ls_estobr="";
	$ls_control="";
	$ls_estobr="";
	$ls_munobr="";
	$ls_comobr="";
	$ls_parobr="";
	$ls_dirobr="";
	$ls_codcon="";
	$ls_codact="";
	$ls_fecact="";
	$ls_obsact="";
	$ls_feciniact="";
	$ls_fecfinact="";
	$ls_nominsact="";
	$ls_cedinsact="";
	$ls_nomsupact="";
	$ls_estact="";
	$ls_cedsupact="";
	$ls_nomresact="";
	$ls_fecrecact="";
	$ls_motact="";
	$ls_codproins="";
	$ls_codpro="";
	$ls_cedresact="";
	$ls_civresact="";
	$ls_civsup="";
	$ls_civinsact="";
}
elseif($ls_operacion=="ue_imprimir")
{
	$lb_existe=$io_acta->uf_select_acta($ls_codcon,$ls_codact,$ls_tipoacta,$aa_data);
	if($lb_existe===true)
	{
		$ls_operacion="imprimir_acta";
	}
	else
	{
		if($lb_existe===0)
		{
			$ls_operacion="confirmar_guardar";
		}
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
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img name="imgnuevo" id="imgnuevo" src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a><a href="javascript:ue_imprimir();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0"></a><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
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
<input name="hidstatus" type="hidden" id="hidstatus" value="<?php print $ls_status; ?>">
  <table width="685" border="0" align="center" cellpadding="0" cellspacing="0"  class="contorno">
      <tr class="formato-blanco">
        <td width="21" height="33"><div align="right"></div></td>
        <td colspan="3"><div align="left">Tipo de Acta		
		<?Php			
			$la_data["value"][2]="1";
			$la_data["etiq"][2]="Inicio";
			$la_data["value"][3]="2";
			$la_data["etiq"][3]="Finalización";
			$la_data["value"][4]="3";
			$la_data["etiq"][4]="Recepción Provisional";
			$la_data["value"][5]="4";
			$la_data["etiq"][5]="Recepción Definitiva";
			$la_data["value"][6]="5";
			$la_data["etiq"][6]="Paralización";
			$la_data["value"][7]="6";
			$la_data["etiq"][7]="Reanudación";
			$la_data["value"][8]="7";
			$la_data["etiq"][8]="Prórroga";
			$io_datastore->data=$la_data;
		?>			
         <select name="cmbtipoacta" size="1" id="cmbtipoacta" onChange="javascript:ue_cambiaracta();">
                <option value="s1">Seleccione...</option>
                <?Php
					for($li_i=2;$li_i<=8;$li_i++)
					{
						 $ls_value=$io_datastore->getValue("value",$li_i);
						 $ls_etiqueta=$io_datastore->getValue("etiq",$li_i);
						 if ($ls_value==$ls_tipoacta)
						 {
							  print "<option value='$ls_value' selected>$ls_etiqueta</option>";
						 }
						 else
						 {
							  print "<option value='$ls_value'>$ls_etiqueta</option>";
						 }
					} 
	            ?>
          </select>
              <input name="hidtipoacta" type="hidden" id="hidtipoacta" value="<?php print $ls_tipoacta ?>">
        </div></td>
        <td width="29">&nbsp;</td>
        <td colspan="3"><div align="right"></div></td>
        <td width="34">&nbsp;</td>
      </tr>
	  <?
	  if($ls_tipoacta=="s1")
	  {
	  ?>
    </table>
	  <?
	  }
	  else
	  {
	  ?>
	   <tr class="formato-blanco">
        <td>&nbsp;</td>
        <td colspan="7" class="titulo-celdanew">Datos del Contrato </td>
        <td>&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td width="82">&nbsp;</td>
        <td width="187">&nbsp;</td>
        <td colspan="3">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td width="16" height="22"><div align="right"></div></td>
        <td width="37"><div align="right">C&oacute;digo</div></td>
        <td colspan="2"><input name="txtcodcon" type="text" id="txtcodcon" style="text-align:center " value="<?php print $ls_codigo ?>" size="32" maxlength="32" readonly="true">
   
        <?Php
		if($ls_control=="")
		{
		?>
		<a href="javascript:ue_catcontrato();" >
			<img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" >
		</a>
		<?
		}
		else
		{
		?>
			<img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" >		
		<?
		}
		?>
		
	    </td>
        <td width="114">&nbsp;</td>
        <td colspan="3"><div align="right"><a href="javascript:uf_mostrar_ocultar_contrato();">&nbsp;&nbsp;<img src="../shared/imagebank/mas.gif" width="9" height="17" border="0"></a><a href="javascript:uf_mostrar_ocultar_contrato();">Datos del Contrato </a></div></td>
        <td width="15">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td height="13"><div align="right"></div></td>
        <td height="13" colspan="3"></td>
        <td colspan="4"><div align="right"></div></td>
        <td>&nbsp;</td>
      </tr>
      
		<?Php
			if ($ls_datoscontrato=="MOSTRAR")
			{
			?>		
				<tr class="formato-blanco">
				  <td height="79" class="sin-borde">&nbsp;</td>
				  <td height="79" colspan="7" align="center" valign="top" class="sin-borde">				  <table width="480" height="111" border="0" cellpadding="0"  cellspacing="0" >
                    <tr class="letras-pequeñas">
                      <td width="126" height="13"><div align="right">Fecha de Inicio</div></td>
                      <td width="96"><input name="txtfecinicon"  stype="text" id="fecinicon"  style="text-align:center "value="<?php print $ls_fecinicon?>" size="11" maxlength="11" readonly="true"></td>
                      <td width="60"><div align="right">Duraci&oacute;n</div></td>
                      <td width="198"><input name="txtplacon"  style="text-align:center "  type="text" id="txtplacon" value="<?php print $ls_placon?> <?php print $ls_placonuni?>" size="11" maxlength="11" readonly="true">
&nbsp;&nbsp;&nbsp;
                      </td>
                    </tr>
                    <tr class="letras-pequeñas">
                      <td height="22"> <div align="right">Contratista</div></td>
                      <td height="22" colspan="3">
                      <input name="txtcontasi" type="text" id="txtcontasi" value="<?php print $ls_contasi?>" size="70" maxlength="254" readonly="true"></td>
                    </tr>
                    <tr class="letras-pequeñas">
                      <td height="19" valign="top" class="navigation"><div align="right">Monto</div></td>
                      <td height="19" colspan="3" valign="top"><input name="txtmoncon" type="text"  readonly="true" id="txtmoncon"  style="text-align: right "value="<?php print $ls_moncon?>" size="21" maxlength="21"></td>
                    </tr>
                    <tr class="letras-pequeñas">
                      <td height="19" valign="top" class="navigation"><div align="right">Estado Actual</div></td>
                      <td height="19" colspan="3" valign="top"><input name="txtestcon" type="text" id="txtestcon" value="<?php print $ls_estcon?>" size="21" maxlength="30" readonly="true" style="text-align:center "></td>
                    </tr>
                    <tr class="letras-pequeñas">
                      <td height="19" valign="top" class="navigation"><div align="right">C&oacute;digo de la Obra</div></td>
                      <td height="19" colspan="3" valign="top"><input name="txtcodobr" id="txtcodobr2" value="<?php print $ls_codobr?>" readonly="true"  style="text-align:center "  type="text" size="6" ></td>
                    </tr>
                    <tr class="letras-pequeñas">
                      <td height="19" valign="top" class="navigation"><div align="right">Descripci&oacute;n</div></td>
                      <td height="19" colspan="3" valign="top"><input name="txtdesobr" type="text" id="txtdesobr2" value="<?php print $ls_desobr?>" size="70" readonly="true"></td>
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
					<td height="19" class="sin-borde">&nbsp;</td>
					<td height="19" colspan="7" align="center" valign="top" class="sin-borde">
					</td>
					<td height="19" class="sin-borde">&nbsp;</td>
				  	</tr>				 
				 <?Php
				 	}
				 ?>  
		 
		 
	  <tr class="formato-blanco">
	  <?
	  if($ls_tipoacta=="1")
	  {
	  ?>
        <td height="13" colspan="9" class="titulo-celdanew">Datos del Acta de Inicio </td>
		<?
		}elseif($ls_tipoacta=="2")
		{
		?>
		 <td height="13" colspan="9" class="titulo-celdanew">Datos del Acta de Finalización </td>
		<?
		}
		elseif($ls_tipoacta=="3")
		{
		?>
			 <td height="13" colspan="9" class="titulo-celdanew">Datos del Acta de Recepción Provisional </td>
		<?		
		}elseif($ls_tipoacta=="4")
		{
		?>
			 <td height="13" colspan="9" class="titulo-celdanew">Datos del Acta de Recepción Definitiva</td>
		<?
		}
		elseif($ls_tipoacta=="5")
		{
		?>
			 <td height="13" colspan="9" class="titulo-celdanew">Datos del Acta de Paralización</td>
		<?
		}elseif($ls_tipoacta=="6")
		{
		?>
			 <td height="13" colspan="9" class="titulo-celdanew">Datos del Acta de Reanudación</td>
		<?
		}elseif($ls_tipoacta=="7")
		{
		?>
			 <td height="13" colspan="9" class="titulo-celdanew">Datos del Acta de Prórroga</td>
		<?
		}		
		?>
      </tr>	  
      <tr class="formato-blanco">
        <td height="39">&nbsp;</td>
        <td height="39"><div align="right">C&oacute;digo</div></td>
        <td height="39"><input name="txtcodact" id="txtcodact" style="text-align:center " value="<?php print $ls_codact?>" readonly="true" type="text" size="6" maxlength="6">        </td>
        <td height="39" width="250"><div align="left">Estado
          <input name="txtestact" type="text" class="celdas-grises" id="txtestact" value="<?php print $ls_estact;?>" size="20" maxlength="20" style="text-align:center ">
        </div></td>
        <td height="39" colspan="2">&nbsp;</td>
        <td width="106" height="39"><div align="right">Fecha:</div></td>
        <td width="122" height="39"><input name="txtfecact" type="text" id="txtfecact"  style="text-align:center" value="<?php print $ls_fecact ?>" size="10" maxlength="10"  readonly="true"></td>
        <td height="39">&nbsp;</td>
      </tr>
	  <?Php
	  if ($ls_tipoacta=="1" )
	  {	  
	  ?>  
      <tr class="formato-blanco">
        <td height="26">&nbsp;</td>
        <td height="26">&nbsp;</td>
        <td height="26" width="180"><div align="right">Fecha de Inicio</div></td>
        <td height="26"><input name="txtfeciniact"   type="text" id="txtfeciniact" style="text-align:left" value="<?php print $ls_feciniact ?>" size="11" maxlength="10"    readonly="true" datepicker="true"></td>
        <td height="26">&nbsp;</td>
        <td height="26">&nbsp;</td>
      </tr>
	  <?Php
	  }
	  elseif($ls_tipoacta=="2")
	  {
	  ?>
	    <tr class="formato-blanco">
        <td height="26">&nbsp;</td>
        <td height="26">&nbsp;</td>
		<td height="26" width="180"><div align="right">Fecha de Fin</div></td>
        <td height="26" ><input name="txtfecfinact"   type="text" id="txtfecfinact"  style="text-align:left" value="<?php print $ls_fecfinact ?>" size="11" maxlength="10"   readonly="true" datepicker="true"></td>
        <td height="26">&nbsp;</td>
        <td height="26">&nbsp;</td>
      </tr>  
	  <?
	  }
	  elseif($ls_tipoacta=="3" || $ls_tipoacta=="4")
	  {
	  ?>
	   <tr class="formato-blanco">
        <td height="26">&nbsp;</td>
        <td height="26">&nbsp;</td>
        <td height="26" width="150"><div align="right">Fecha de Recepción</div></td>
        <td height="26"><input name="txtfecrecact"   type="text" id="txtfecrecact" style="text-align:left" value="<?php print $ls_fecrecact ?>" size="11" maxlength="10"    readonly="true" datepicker="true"></td>
        <td height="26">&nbsp;</td>
        <td height="26">&nbsp;</td>
      </tr>
	  
	  <?
	  }
	  elseif($ls_tipoacta=="5")
	  {
	  ?>
	   <tr class="formato-blanco">
        <td height="26">&nbsp;</td>
        <td height="26">&nbsp;</td>
        <td height="26"><div align="right">Fecha Paraliz.</div></td>
        <td height="26"><input name="txtfecfinact"   type="text" id="txtfecfinact"  style="text-align:left" value="<?php print $ls_fecfinact ?>" size="11" maxlength="10"   readonly="true" datepicker="true"></td>
        <td height="26" colspan="2"><div align="right">Fecha Reinicio</div></td>
        <td height="26"><input name="txtfeciniact"   type="text" id="txtfeciniact"  style="text-align:left" value="<?php print $ls_feciniact ?>" size="11" maxlength="10"    readonly="true" datepicker="true"></td>
        <td height="26">&nbsp;</td>
        <td height="26">&nbsp;</td>
      </tr>
	  
	  <?
	  }
	  elseif($ls_tipoacta=="6")
	  {
	  ?>
	    <tr class="formato-blanco">
        <td height="26">&nbsp;</td>
        <td height="26">&nbsp;</td>
        <td height="26"><div align="right">Fecha Reinicio</div></td>
        <td height="26"><input name="txtfeciniact"   type="text" id="txtfeciniact" style="text-align:left" value="<?php print $ls_feciniact ?>" size="11" maxlength="10"    readonly="true" datepicker="true"></td>
        <td height="26" colspan="2" width="200"><div align="right">Nueva Fecha Finaliz.</div></td>
        <td height="26"><input name="txtfecfinact"   type="text" id="txtfecfinact"  style="text-align:left" value="<?php print $ls_fecfinact ?>" size="11" maxlength="10"   readonly="true" datepicker="true"></td>
        <td height="26">&nbsp;</td>
        <td height="26">&nbsp;</td>
      </tr>
	  <?
	  }
	  elseif($ls_tipoacta=="7")
	  {
	  ?>
	   <tr class="formato-blanco">
        <td height="26">&nbsp;</td>
        <td height="26">&nbsp;</td>       
        <td height="26" width="160"><div align="right">Nueva Fecha de Fin</div></td>
        <td height="26"><input name="txtfecfinact"   type="text" id="txtfecfinact"  style="text-align:left" value="<?php print $ls_fecfinact ?>" size="11" maxlength="10"   readonly="true" datepicker="true"></td>
        <td height="26">&nbsp;</td>
        <td height="26">&nbsp;</td>
      </tr>
	  
	  <?
	  }
	  ?>	  
	  
      <tr class="formato-blanco">
        <td height="25">&nbsp;</td>
        <td height="25">&nbsp;</td>
        <td height="25"><div align="right">Ing. Inspector</div></td>
        <td height="25" colspan="5" width="800"><input name="txtnominsact" type="text"  style="text-align: left" id="txtnominsact" readonly="true" size="50" value="<?php print $ls_nominsact?>" maxlength="50">          &nbsp;&nbsp;&nbsp;&nbsp;C.I.
        <input name="txtcedinsact" type="text" id="txtcedinsact" value="<?php print $ls_cedinsact?>" size="10" readonly="true" maxlength="10" style="text-align:center ">
        &nbsp;
        C.I.V.        
        <input name="txtcivinsact" type="text" id="txtcivinsact"  value="<?php print $ls_civinsact;?>" size="10" style="text-align:center " maxlength="10"  onKeyPress="return(validaCajas(this,'x',event,10))"> 
        <a href="javascript:ue_catinspectores();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"  ></a>          <div align="left"></div>        </td>
		<td height="25">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td height="24">&nbsp;</td>
        <td height="24">&nbsp;</td>
        <td height="24"><div align="right">Ing. Residente</div></td>
        <td height="24" colspan="5"><input name="txtnomresact" type="text" id="txtnomresact" value="<?php print $ls_nomresact?>" size="50"  maxlength="50" style="text-align:left " onKeyPress="return(validaCajas(this,'x',event,254))">
        &nbsp;&nbsp;&nbsp;&nbsp;C.I.
        <input name="txtcedresact" type="text" id="txtcedresact"  value="<?php print $ls_cedresact?>" size="10" maxlength="10" style="text-align:center "  onKeyPress="return(validaCajas(this,'x',event,10))">
        &nbsp;&nbsp;C.I.V.
        <input name="txtcivresact" type="text" style="text-align:center " value="<?php print $ls_civresact;?>" size="10" maxlength="10"  onKeyPress="return(validaCajas(this,'x',event,10))">
        <a href="javascript:ue_catresidentes();"></a></td>
        <td height="24">&nbsp;</td>
      </tr>		
      <?Php
	  	if($ls_tipoacta=="5")
	  	{
	  ?>
	    <tr class="formato-blanco">
        <td height="39">&nbsp;</td>
        <td>&nbsp;</td>
        <td><div align="right">Motivo Paraliz.</div></td>
        <td colspan="5"><textarea name="txtmotact" cols="80" rows="2" id="txtmotact" onKeyDown="textCounter(this,254)" onKeyUp="textCounter(this,254)" onKeyPress="return(validaCajas(this,'x',event,254))"><?php print $ls_motact;?></textarea></td>
        <td>&nbsp;</td>
      </tr>
	  <?
	  }
	  elseif($ls_tipoacta=="7")
	  {
	  ?>
	      <tr class="formato-blanco">
        <td height="39">&nbsp;</td>
        <td>&nbsp;</td>
        <td><div align="right">Motivo Prórroga</div></td>
        <td colspan="5"><textarea name="txtmotact" cols="80" rows="2" id="txtmotact" onKeyDown="textCounter(this,254)" onKeyUp="textCounter(this,254)"><?php print $ls_motact;?></textarea></td>
        <td>&nbsp;</td>
      </tr>
	   <?
	  }
	  ?>  
	  
      <tr class="formato-blanco">
        <td height="40 ">&nbsp;</td>
        <td>&nbsp;</td>
        <td><div align="right">Observaci&oacute;n</div></td>
        <td colspan="5"><textarea name="txtobsact" cols="80" rows="2" id="txtobsact" onKeyDown="textCounter(this,254)" onKeyUp="textCounter(this,254)" onKeyPress="return(validaCajas(this,'x',event,254))"><?php print $ls_obsact;?></textarea></td>
        <td>&nbsp;</td>
      </tr>
	  
      <tr class="formato-blanco">
        <td colspan="9">&nbsp;</td>
      </tr>
    </table>
	<?
	}
	?>	
	
	
  <!-- Los Hidden son colocados a partir de aca-->
<input name="hiddatoscontrato" type="hidden" id="hiddatoscontrato" value="<?php print $ls_datoscontrato;?>">
<input name="hiddatosobra" type="hidden" id="hiddatosobra" value="<?php print $ls_datosobra;?>">
<input name="hidcodproins" type="hidden" id="hidcodproins" value="<?php print $ls_codproins;?>">
<input name="hidcodpro" type="hidden" id="hidcodpro" value="<?php print $ls_codpro;?>">
<input name="hidplacon" type="hidden" id="hidplacon" value="<?php print $ls_placon;?>">
<input name="hidplaconuni" type="hidden" id="hidplaconuni" value="<?php print $ls_placonuni;?>">
<input name="hidcontrol" type="hidden" id="hidcontrol" value="<?php print $ls_control?>">
<input name="operacion" type="hidden" id="operacion">
<input name="codact" type="hidden" id="codact">
<input name="codcon" type="hidden" id="codcon">
<input name="fecact" type="hidden" id="fecact">
<input name="cedinsact" type="hidden" id="cedinsact">
<input name="cedresact" type="hidden" id="cedresact">
<input name="nominsact" type="hidden" id="nominsact">
<input name="civinsact" type="hidden" id="civinsact">
<input name="nomresact" type="hidden" id="nomresact">
<input name="civresact" type="hidden" id="civresact">
<input name="estact" type="hidden" id="estact">
<input name="obsact" type="hidden" id="obsact">
<input name="fecrecact" type="hidden" id="fecrecact">
<input name="fecfinact" type="hidden" id="fecfinact">
<input name="feciniact" type="hidden" id="feciniact">
<input type="hidden" name="hidimprimir" id="hidimprimir">
<input type="hidden" name="hidcodigo" id="hidcodigo" value="<?php print $ls_codcon?>">
<input type="hidden" name="hidmotivo" id="hidmotivo" value="<?php print $ls_motact?>">
<?Php
if($ls_operacion=="imprimir_acta")
{
	?>
	<script language="javascript">
		f=document.form1;
		var tipact=f.hidtipoacta.value;
		var codact=f.txtcodact.value;
		var codcon=f.hidcodigo.value;
		var documento="ACTA";
		pagina="sigesp_sob_d_filechooser.php?tipact="+tipact+"&codact="+codact+"&codcon="+codcon+"&documento="+documento;
		//window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=400,height=150,resizable=yes,location=no,status=no,left=300,top=200");
		popupWin(pagina,'win1');
	</script>
	<?Php
}elseif($ls_operacion=="confirmar_guardar")
{
	?>
	<script language="javascript">
		f=document.form1;
		guardar=confirm("El Acta no ha sido guardada.\n ¿Desea guardarla ahora?");
		if(guardar)
		{
		/***********************************************/
			li_incluir=f.incluir.value;
			li_cambiar=f.cambiar.value;
			lb_status=f.hidstatus.value;
			if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
			{
				seguir=true;
				if (f.hidtipoacta.value!="s1")
				{
					if(f.hidtipoacta.value=="1")
					{
						//seguir=ue_comparar_intervalo('txtfeciniact','txtfecfinact','La fecha de inicio del Acta debe ser menor o igual a la fecha de finalización');
						var la_objetos=new Array (5,"txtcodcon","txtcodact","txtfeciniact","txtnominsact","txtnomresact");
						var la_mensajes=new Array ("","Código del Contrato","Código del Acta","Fecha de Inicio","Fecha de Finalización","Ing. Inspector","Ing. Residente");
					}
					else
					{
						if(f.hidtipoacta.value=="3" || f.hidtipoacta.value=="4")
						{
							var la_objetos=new Array (5,"txtcodcon","txtcodact","txtfecrecact","txtnominsact","txtnomresact");
							var la_mensajes=new Array ("","Código del Contrato","Código del Acta","Fecha de Recepción de la Obra","Ing. Inspector","Ing. Residente");
						}
						else
						{
							if(f.hidtipoacta.value=="5")
							{
								seguir=ue_comparar_intervalo('txtfecfinact','txtfeciniact','La fecha de Reinicio del Acta debe ser menor o igual a la nueva Fecha de Finalización');
								var la_objetos=new Array (6,"txtcodcon","txtcodact","txtfecfinact","txtfeciniact","txtnominsact","txtnomresact");
								var la_mensajes=new Array ("","Código del Contrato","Código del Acta","Fecha de Paralización","Fecha de Reanudación del Acta","Ing. Inspector","Ing. Residente");
							}
							else
							{
								if(f.hidtipoacta.value=="6")
								{
									seguir=ue_comparar_intervalo('txtfeciniact','txtfecfinact','La fecha de Paralización del Acta debe ser menor o igual a la fecha de Reinicio');
									var la_objetos=new Array (6,"txtcodcon","txtcodact","txtfeciniact","txtfecfinact","txtnominsact","txtnomresact");
									var la_mensajes=new Array ("","Código del Contrato","Código del Acta","Fecha de Reinicio","Nueva Fecha de Finalización del Acta","Ing. Inspector","Ing. Residente");
								}
								else
								{
									if(f.hidtipoacta.value=="7")
									{
										var la_objetos=new Array (5,"txtcodcon","txtcodact","txtfecfinact","txtnominsact","txtnomresact");
										var la_mensajes=new Array ("","Código del Contrato","Código del Acta","Nueva Fecha de Finalización del Acta","Ing. Inspector","Ing. Residente");
									}
									else
									{
										if(f.hidtipoacta.value=="2")
										{
											//seguir=ue_comparar_intervalo('txtfeciniact','txtfecfinact','La fecha de inicio del Acta debe ser menor o igual a la fecha de finalización');
											var la_objetos=new Array (5,"txtcodcon","txtcodact","txtfecfinact","txtnominsact","txtnomresact");
											var la_mensajes=new Array ("","Código del Contrato","Código del Acta","Fecha de Finalización","Ing. Inspector","Ing. Residente");
										}
									}
								}
							}
						}
						
					}	
							
											
					if(seguir)
					{
						lb_valido=true;
						//alert("objetos "+la_objetos[0]);
						//var la_objetos=new Array ("txtcodcon","txtcodact","txtfeciniact","txtfecfinact","txtnominsact","txtnomresact");
						//var la_mensajes=new Array ("Código del Contrato","Código del Acta","Fecha de Inicio","Fecha de Finalización","Ing. Inspector","Ing. Residente");
						for (li_i=1;li_i<la_objetos[0];li_i++)
						{
							if(ue_valida_null(eval("f."+la_objetos[li_i]),la_mensajes[li_i])==false)
							{
								eval("f."+la_objetos[li_i]+".focus();");
								lb_valido=false;
								break;				
							}
						}
						if(lb_valido)
						{
							f.operacion.value="ue_guardar";
							f.hidimprimir.value="IMPRIMIR";
							f.action="";
							var tipact=f.hidtipoacta.value;
							var codact=f.txtcodact.value;
							var codcon=f.txtcodcon.value;							
							f.submit();												
						}	
					}
				}
				else
				{
					 alert("No hay ningún registro para guardar !!!");
				}
			}
			else
			{
				alert("No tiene permiso para realizar esta operacion");
			}
		/***********************************************/		
		}//end del if(guardar)		
	</script>
	<?Php
}


?>

<!-- Fin de la declaracion de Hidden-->
  </form>
</body>
<script language="javascript">


///////Funciones para llamar catalogos////////////////
function ue_catcontrato()
{
	f=document.form1;
	f.operacion.value="";			
	var tipoacta = f.hidtipoacta.value;
	pagina="sigesp_cat_contratoactas.php?tipoacta="+tipoacta;
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=500,resizable=yes,location=no");
}

function ue_catinspectores()
{
	f=document.form1;
	if(f.txtcodact.value=="")
	{
		alert("Debe seleccionar una nueva Acta!!!");
	}
	else
	{
		if( f.hidtipoacta.value=="5" || f.hidtipoacta.value=="6")
		{
			if(f.txtfeciniact.value=="" || f.txtfecfinact.value=="")
			{
				f.operacion.value="";			
				var codpro = f.hidcodproins.value;
				var tipocatalogo="INSPECTOR";
				pagina="sigesp_cat_inspectores.php?codpro="+codpro+"&tipocatalogo="+tipocatalogo;
				window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,resizable=yes,location=no,left=0,top=0");
			}
			else
			{
				if(f.hidtipoacta.value=="1" || f.hidtipoacta.value=="2")
					seguir=ue_comparar_intervalo('txtfeciniact','txtfecfinact','La fecha de inicio del Acta debe ser menor o igual a la fecha de finalización');
				else
					if(f.hidtipoacta.value=="5")
						seguir=ue_comparar_intervalo('txtfecfinact','txtfeciniact','La fecha de Reinicio del Acta debe ser menor o igual a la nueva Fecha de Finalización');
					else
						if(f.hidtipoacta.value=="6")
							seguir=ue_comparar_intervalo('txtfeciniact','txtfecfinact','La fecha de Paralización del Acta debe ser menor o igual a la fecha de Reinicio');
						
						
				if(seguir)
				{
					
					f.operacion.value="";			
					var codpro = f.hidcodproins.value;
					var tipocatalogo="INSPECTOR";
					pagina="sigesp_cat_inspectores.php?codpro="+codpro+"&tipocatalogo="+tipocatalogo;
					window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,resizable=yes,location=no,left=0,top=0");
				}
			}
		}
		else
		{
			if(f.hidtipoacta.value=="1" || f.hidtipoacta.value=="2" || f.hidtipoacta.value=="3" || f.hidtipoacta.value=="4" || f.hidtipoacta.value=="7")
			{
				f.operacion.value="";			
				var codpro = f.hidcodproins.value;
				var tipocatalogo="INSPECTOR";
				pagina="sigesp_cat_inspectores.php?codpro="+codpro+"&tipocatalogo="+tipocatalogo;
				window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,resizable=yes,location=no,left=0,top=0");
			}			
		}
	}		
}

function ue_catresidentes()
{
	f=document.form1;
	if(f.txtcodact.value=="")
	{
		alert("Debe seleccionar una nueva Acta!!!");
	}
	else
	{
		if( f.hidtipoacta.value=="5" || f.hidtipoacta.value=="6")
		{
			if(f.txtfeciniact.value=="" || f.txtfecfinact.value=="")
			{
				f.operacion.value="";			
				var codpro = f.hidcodpro.value;
				var tipocatalogo="RESIDENTE";
				pagina="sigesp_cat_inspectores.php?codpro="+codpro+"&tipocatalogo="+tipocatalogo;
				window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=200,resizable=yes,location=no,left=0,top=0");
			}
			else
			{
				if(f.hidtipoacta.value=="1" || f.hidtipoacta.value=="2")
					seguir=ue_comparar_intervalo('txtfeciniact','txtfecfinact','La fecha de inicio del Acta debe ser menor o igual a la fecha de finalización');
				else
					if(f.hidtipoacta.value=="5")
						seguir=ue_comparar_intervalo('txtfecfinact','txtfeciniact','La fecha de Reinicio del Acta debe ser menor o igual a la nueva Fecha de Finalización');
					else
						if(f.hidtipoacta.value=="6")
							seguir=ue_comparar_intervalo('txtfeciniact','txtfecfinact','La fecha de Paralización del Acta debe ser menor o igual a la fecha de Reinicio');
							
				if(seguir)
				{
					
					f.operacion.value="";			
					var codpro = f.hidcodpro.value;
					var tipocatalogo="RESIDENTE";
					pagina="sigesp_cat_inspectores.php?codpro="+codpro+"&tipocatalogo="+tipocatalogo;
					window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=200,resizable=yes,location=no,left=0,top=0");
				}
			}	
		}
		else
		{
			
			if( f.hidtipoacta.value=="1" || f.hidtipoacta.value=="2" || f.hidtipoacta.value=="3" || f.hidtipoacta.value=="4" || f.hidtipoacta.value=="7")
			{
				f.operacion.value="";			
				var codpro = f.hidcodpro.value;
				var tipocatalogo="RESIDENTE";
				pagina="sigesp_cat_inspectores.php?codpro="+codpro+"&tipocatalogo="+tipocatalogo;
				window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=200,resizable=yes,location=no,left=0,top=0");
			}			
		}
	}
}

///////Fin de las Funciones para para llamar catalogos/////

//////Funciones para cargar datos provenientes de catalogos///////

function ue_cargarcontrato(ls_codigo,ls_desobr,ls_estado,ls_codest,ls_codasi,ls_feccrecon,ls_fecinicon,ls_codobr,ls_codpro,ls_codproins,ls_precon)
{
	f=document.form1;
	f.txtcodcon.value=ls_precon+ls_codigo;
	f.hidcodproins.value=ls_codproins;
	f.hidcodpro.value=ls_codpro;
	f.hidcodigo.value=ls_codigo;
	f.operacion.value="";	
}

function ue_cargarinspector(ls_codpro,ls_nomsup,ls_cedsup,ls_civ)
{
	f=document.form1;
	f.txtnominsact.value=ls_nomsup;
	f.txtcedinsact.value=ls_cedsup;
	f.txtcivinsact.value=ls_civ;	
}

function ue_cargarresidente(ls_codpro,ls_nomsup,ls_cedsup,ls_civ)
{
	f=document.form1;
	f.txtnomresact.value=ls_nomsup;
	f.txtcedresact.value=ls_cedsup;
	f.txtcivresact.value=ls_civ;	
}

function ue_cargaracta(ls_codact,ls_codcon,ls_desobr,ls_estact,ls_fecact,ls_feciniact,ls_fecfinact,ls_cedinsact,ls_cedresact,ls_nominsact,ls_civinsact,ls_nomresact,ls_civresact,ls_codpro,ls_codproins,ls_obsact,ls_fecrecact,li_tipact,ls_precon,ls_motact)
{
	f=document.form1;
	f.codact.value=ls_codact;
	f.codcon.value=ls_precon+ls_codcon;;
	f.hidcodigo.value=ls_codcon;
	f.fecact.value=ls_fecact;	
	f.cedinsact.value=ls_cedinsact;
	f.cedresact.value=ls_cedresact;
	f.nominsact.value=ls_nominsact;
	f.civinsact.value=ls_civinsact;
	f.nomresact.value=ls_nomresact;
	f.civresact.value=ls_civresact;
	f.operacion.value="ue_cargaracta";
	f.estact.value=ls_estact;
	f.hidcodpro.value=ls_codpro;
	f.hidcodproins.value=ls_codproins;
	f.obsact.value=ls_obsact;
	f.hidcontrol.value="x";
	f.cmbtipoacta.value=li_tipact;
	f.feciniact.value=ls_feciniact;
	f.fecfinact.value=ls_fecfinact;
	f.fecrecact.value=ls_fecrecact;
	f.hidmotivo.value=ls_motact;	
	f.hidstatus.value="C";
	f.submit();
	
}

//////////////////////////////Fin de las funciones de validacion//////////////
function ue_nuevo()
{
  	f=document.form1;
  	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		 if (f.cmbtipoacta.value!="s1")
		 {
			 if(f.txtcodcon.value=="")
				alert("Debe seleccionar un Contrato!!!");
			 else
			 {
				  f.operacion.value="ue_nuevo";
				  f.action="";
				  f.submit();
			 }
		}
		else
		{
			alert("Debe seleccionar un Tipo de Acta!!!");
		} 
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
  
}

function ue_guardar()
{
	f=document.form1;	
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_status=f.hidstatus.value;
	if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
	{
		seguir=true;
		if (f.hidtipoacta.value!="s1")
		{
			if(f.hidtipoacta.value=="1")
			{
				//seguir=ue_comparar_intervalo('txtfeciniact','txtfecfinact','La fecha de inicio del Acta debe ser menor o igual a la fecha de finalización');
				var la_objetos=new Array (5,"txtcodcon","txtcodact","txtfeciniact","txtnominsact","txtnomresact");
				var la_mensajes=new Array ("","Código del Contrato","Código del Acta","Fecha de Inicio","Ing. Inspector","Ing. Residente");
			}
			else
			{
				if(f.hidtipoacta.value=="3" || f.hidtipoacta.value=="4")
				{
					var la_objetos=new Array (5,"txtcodcon","txtcodact","txtfecrecact","txtnominsact","txtnomresact");
					var la_mensajes=new Array ("","Código del Contrato","Código del Acta","Fecha de Recepción de la Obra","Ing. Inspector","Ing. Residente");
				}
				else
				{
					if(f.hidtipoacta.value=="5")
					{
						seguir=ue_comparar_intervalo('txtfecfinact','txtfeciniact','La fecha de Reinicio del Acta debe ser menor o igual a la nueva Fecha de Finalización');
						var la_objetos=new Array (6,"txtcodcon","txtcodact","txtfecfinact","txtfeciniact","txtnominsact","txtnomresact");
						var la_mensajes=new Array ("","Código del Contrato","Código del Acta","Fecha de Paralización","Fecha de Reanudación del Acta","Ing. Inspector","Ing. Residente");
					}
					else
					{
						if(f.hidtipoacta.value=="6")
						{
							seguir=ue_comparar_intervalo('txtfeciniact','txtfecfinact','La fecha de Paralización del Acta debe ser menor o igual a la fecha de Reinicio');
							var la_objetos=new Array (6,"txtcodcon","txtcodact","txtfeciniact","txtfecfinact","txtnominsact","txtnomresact");
							var la_mensajes=new Array ("","Código del Contrato","Código del Acta","Fecha de Reinicio","Nueva Fecha de Finalización del Acta","Ing. Inspector","Ing. Residente");
						}
						else
						{
							if(f.hidtipoacta.value=="7")
							{
								var la_objetos=new Array (5,"txtcodcon","txtcodact","txtfecfinact","txtnominsact","txtnomresact");
								var la_mensajes=new Array ("","Código del Contrato","Código del Acta","Nueva Fecha de Finalización del Acta","Ing. Inspector","Ing. Residente");
							}
							else
							{
								if(f.hidtipoacta.value=="2")
								{
									//seguir=ue_comparar_intervalo('txtfeciniact','txtfecfinact','La fecha de inicio del Acta debe ser menor o igual a la fecha de finalización');
									var la_objetos=new Array (5,"txtcodcon","txtcodact","txtfecfinact","txtnominsact","txtnomresact");
									var la_mensajes=new Array ("","Código del Contrato","Código del Acta","Fecha de Finalización","Ing. Inspector","Ing. Residente");
								}
							}
						}
					}
				}
				
			}	
					
									
			if(seguir)
			{
				lb_valido=true;
				//alert("objetos "+la_objetos[0]);
				//var la_objetos=new Array ("txtcodcon","txtcodact","txtfeciniact","txtfecfinact","txtnominsact","txtnomresact");
				//var la_mensajes=new Array ("Código del Contrato","Código del Acta","Fecha de Inicio","Fecha de Finalización","Ing. Inspector","Ing. Residente");
				for (li_i=1;li_i<la_objetos[0];li_i++)
				{
					if(ue_valida_null(eval("f."+la_objetos[li_i]),la_mensajes[li_i])==false)
					{
						eval("f."+la_objetos[li_i]+".focus();");
						lb_valido=false;
						break;				
					}
				}
				if(lb_valido)
				{
					f.operacion.value="ue_guardar";
					f.action="";
					f.submit();
				}	
			}
		}
		else
		{
			 alert("No hay ningún registro para guardar !!!");
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
		var tipoacta=f.hidtipoacta.value;
		pagina="sigesp_cat_acta.php?tipoacta="+tipoacta;
		window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=350,resizable=yes,location=no,status=no,left=0,top=0");
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
} 

function ue_eliminar()
{
	f=document.form1;
	li_eliminar=f.eliminar.value;
	if(li_eliminar==1)
	{
		if (f.hidtipoacta.value!="s1")
		{
			if (f.txtcodact.value=="")
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
				 f.action="";
				 f.submit();
			   }
			}
		}
		else
		{
			 alert("No ha seleccionado ningún registro para eliminar !!!");
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
	if(f.hidtipoacta.value!="s1")
	{
		if(f.txtcodcon.value!="" && f.txtcodact.value!= "")
		{
			f.operacion.value="ue_imprimir";
			f.submit();
		}
		else
		{
			alert("Debe seleccionar un Acta!!!");
		}
	}
	else
	{
		alert("Debe seleccionar un Acta!!!");
	}	
	/*var tipoacta=f.hidtipoacta.value;
	pagina="sigesp_sob_d_filechooser.php";
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=400,height=150,resizable=yes,location=no,status=no,left=300,top=200");*/
}

function uf_mostrar_ocultar_obra()  
{
	f=document.form1;
	if (f.txtcodcon.value=="")
	{
		alert("Debe seleccionar un Contrato!!");
	}
	else
	{
		if (f.hiddatosobra.value == "OCULTAR")
		{
			f.hiddatosobra.value = "MOSTRAR";
			f.operacion.value="ue_cargarcontrato";
			
		}
		else
		{
			f.hiddatosobra.value = "OCULTAR";
			f.operacion.value="";
		}
		f.submit();
	}
}

function uf_mostrar_ocultar_contrato()  
{
	f=document.form1;
	if(f.txtcodcon.value=="")
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
		}
		f.submit();
	}
}

function mensaje()
{
	alert("No puede cambiar el Contrato!!!");
}

function ue_cambiaracta()
{
	f=document.form1;
	f.operacion.value="ue_cambiaracta";	
	f.submit();
}

function mensaje()
{
	alert ("paso");
}

 
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>