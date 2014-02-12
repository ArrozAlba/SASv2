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
$la_datemp=$_SESSION["la_empresa"];
$ls_codtie=$_SESSION["ls_codtienda"];


require_once("class_funciones_inventario.php");
$io_fun_activo=new class_funciones_inventario();
$io_fun_activo->uf_load_seguridad("SIM","sigesp_sim_p_toma.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
function uf_limpiarvariables()
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_limpiarvariables
	//         Access: private
	//      Argumento:
	//	      Returns: 
	//    Description: Funcion que limpia todas las veriables que se usan en la pagina
	//	   Creado Por: Ing. Luis Anibal Lang
	// Fecha Creación: 23/03/2006 								Fecha Última Modificación : 23/03/2006 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	global $ls_numtom,$ld_fectom,$ls_codalm,$ls_nomfisalm,$ls_obstom,$lb_ajustar,$ls_titletable,$li_widthtable;
	global $ls_nametable,$lo_title,$li_totrows;
	
	$ld_fectom=date("d/m/Y"); 
	$ls_numtom="";
	$ls_codalm="";
	$ls_nomfisalm="";
	$ls_obstom="";
	$ls_codpro="";
	$ls_denpro="";
	$lb_ajustar=false;

	$ls_titletable="Detalle de la Toma de Inventario";
	$li_widthtable=650;
	$ls_nametable="grid";
	$lo_title[1]="Código";
	$lo_title[2]="Artículo";
	$lo_title[3]="Unidad";
	$lo_title[4]="Cantidad Contada";
	$lo_title[5]="";
	$li_totrows=1;
	$ls_codusu=$_SESSION["la_logusr"];
	$ls_readonly="true";
	$ls_status="";
	
} // end function uf_limpiarvariables()

function uf_agregarlineablanca(&$aa_object,$ai_totrows)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_agregarlineablanca
	//         Access: private
	//      Argumento: $aa_object // arreglo de titulos 
	//				   $ai_totrows // ultima fila pintada en el grid
	//	      Returns: 
	//    Description: Funcion que agrega una linea en blanco al final del grid
	//	   Creado Por: Ing. Luis Anibal Lang
	// Fecha Creación: 23/03/2006 								Fecha Última Modificación : 01/09/2010 
	//                                                          Modificador por: Erlinda Tovar            
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$aa_object[$ai_totrows][1]="<input name=txtcodart".$ai_totrows." type=text id=txtcodart".$ai_totrows." class=sin-borde size=21 maxlength=20 readonly>";
	$aa_object[$ai_totrows][2]="<input name=txtdenart".$ai_totrows." type=text id=txtdenart".$ai_totrows." class=sin-borde size=40 maxlength=50 readonly>";
	$aa_object[$ai_totrows][3]="<div align='center'><select name=cmbunidad".$ai_totrows." style='width:110px '><option value=->-Seleccione uno-</option><option value=D>Detal</option><option value=M>Mayor</option></select></div>";
	$aa_object[$ai_totrows][4]="<input name=txtcanfis".$ai_totrows." type=text id=txtcanfis".$ai_totrows." class=sin-borde size=12 maxlength=12 onKeyPress=return(ue_formatonumero(this,'.',',',event));>";
	$aa_object[$ai_totrows][5]="<img src='../shared/imagebank/tools/espacio.gif' width=20 height=20>";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Entrada de Suministros a Almac&eacute;n </title>
<meta http-equiv="imagetoolbar" content="no"> 
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #EFEBEF;
}

a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:active {
	color: #006699;
}

-->
</style>
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funciones.js"></script>
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
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
.Estilo1 {
	font-size: 14px;
	color: #6699CC;
}
-->
</style>
</head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="7" bgcolor="#E7E7E7" class="cd-menu"><span class="descripcion_sistema Estilo1 Estilo1">Sistema de Inventario</span></td>
    <td height="20" colspan="4" bgcolor="#E7E7E7" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
  </tr>
  <tr>
    <td height="20" colspan="7" bgcolor="#E7E7E7" class="cd-menu">&nbsp;</td>
    <td height="20" colspan="4" bgcolor="#E7E7E7" class="cd-menu"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" colspan="11" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript: ue_procesar();"><img src="../shared/imagebank/tools20/ejecutar.gif" alt="Procesar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_imprimir();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="24"><div align="center"></div></td>
    <td class="toolbar" width="24"><div align="center"></div></td>
    <td class="toolbar" width="618">&nbsp;</td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/sigesp_include.php");
	$in=      new sigesp_include();
	$con=     $in->uf_conectar();
	require_once("../shared/class_folder/class_sql.php");
	$io_sql=  new class_sql($con);
	require_once("../shared/class_folder/class_fecha.php");
	$io_fec=  new class_fecha();
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg=  new class_mensajes();
	require_once("../shared/class_folder/class_funciones_db.php");
	$io_fun=  new class_funciones_db($con);
	require_once("../shared/class_folder/class_funciones.php");
	$io_func= new class_funciones();
	require_once("../shared/class_folder/grid_param.php");
	$in_grid= new grid_param();
	require_once("sigesp_sim_c_toma.php");
	$io_siv=  new sigesp_sim_c_toma();
	require_once("class_funciones_inventario.php");
	$io_finv= new class_funciones_inventario();
	require_once("sigesp_sim_c_articuloxalmacen_prov.php");
	$io_art=  new sigesp_sim_c_articuloxalmacen_prov();
	require_once("sigesp_sim_c_movimientoinventario.php");
	$io_mov=  new sigesp_sim_c_movimientoinventario();
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_codusu=$_SESSION["la_logusr"];
	$ls_operacion=$io_finv->uf_obteneroperacion();
	

	switch ($ls_operacion)
	{
		case "NUEVO":
			uf_limpiarvariables();
			uf_agregarlineablanca($lo_object,$li_totrows);
		break;
		
		case "BUSCARARTICULOS":
			uf_limpiarvariables();
			$ls_codalm=   $_POST["txtcodalm"];
			$ls_nomfisalm=$_POST["txtnomfisalm"];
			$ld_fectom=   $_POST["txtfectom"];
			$ls_estpro=   $_POST["hidestpro"];
			$ls_codpro=   $_POST["txtcodpro"];
		    $ls_denpro=   $_POST["txtdenpro"];
            $ls_codtie=   $_SESSION["ls_codtienda"];
			$ls_obstom=   $_POST["txtobstom"];

			$ls_codtiend=$io_mov->uf_sim_select_tienda($ls_codemp,$ls_codalm,$ls_codpro);
            
			$lb_valido=$io_siv->uf_sim_load_articuloalmacen($ls_codemp,$ls_codalm,$lo_object,$li_totrows,$ls_codtie,$ls_codpro);
			
			if(!$lb_valido)
			{
			    $io_msg->message("No Existen Registros !!!");
			}
			
		break;

		case "BUSCARTOMA":
			uf_limpiarvariables();
			$ls_numtom=   $_POST["txtnumtom"];
			$ls_codalm=   $_POST["txtcodalm"];
			$ls_nomfisalm=$_POST["txtnomfisalm"];
			$ld_fectom=   $_POST["txtfectom"];
			$ls_estpro=   $_POST["hidestpro"];
			$ls_codpro=   $_POST["txtcodpro"];
			$ls_denpro=   $_POST["txtdenpro"];
			$ls_obstom=   $_POST["txtobstom"];
			
            $ls_denpro=$io_siv->uf_sim_load_denpro($ls_codemp,$ls_codpro);
			
			$lb_valido=$io_siv->uf_sim_load_toma($ls_codemp,$ls_numtom,$lo_object,$li_totrows,$ls_codtie);
		break;

		case "PROCESAR":
			uf_limpiarvariables();
			$lb_ok=false;
			$li_totrows=  $_POST["totalfilas"];
			$ls_numtom=   $_POST["txtnumtom"];
			$ls_codalm=   $_POST["txtcodalm"];
			$ls_nomfisalm=$_POST["txtnomfisalm"];
			$ld_fectom=   $_POST["txtfectom"];
			$ls_estpro=   $_POST["hidestpro"];
			$ls_codpro=   $_POST["txtcodpro"];
		    $ls_denpro=   $_POST["txtdenpro"];
			$ls_obstom=   $_POST["txtobstom"];
			$ld_fectomaux= $io_func->uf_convertirdatetobd($ld_fectom);
			$ls_codtiend=$io_mov->uf_sim_select_tienda($ls_codemp,$ls_codalm,$ls_codpro);
			$lb_valido=$io_fec->uf_valida_fecha_mes($ls_codemp,$ld_fectom);
			if($lb_valido)
			{
				
				if($ls_numtom=="")
				{
					$io_sql->begin_transaction();
					$lb_valido=$io_siv->uf_sim_insert_tomainventario($ls_codemp,$ls_codalm,$ls_numtom,$ld_fectomaux,
					                                                 $ls_obstom,$ls_codusu,$la_seguridad,$ls_codtie);
					if ($lb_valido)
					{
						for($li_i=1;$li_i<=$li_totrows;$li_i++)
						{
							$ls_codart=       $_POST["txtcodart".$li_i];
							$ls_denart=       $_POST["txtdenart".$li_i];
							$li_canfis=       $_POST["txtcanfis".$li_i];
							$li_canfisaux=    $_POST["txtcanfis".$li_i];
							$ls_unidad=       $_POST["cmbunidad".$li_i];
							$ls_codpro=       $_POST["txtcodpro"];
		    				$ls_denpro=       $_POST["txtdenpro"];
							$ls_obstom=       $_POST["txtobstom"];

							$la_unidad[0]="";
							$la_unidad[1]="";
							$li_canfisaux=    str_replace(".","",$li_canfisaux);
							$li_canfisaux=    str_replace(",",".",$li_canfisaux);
							$li_canexisis=0;
							$li_canexifisant=0;
							$li_unidad=0;
					
							$lb_valido=$io_siv->uf_sim_select_comparararticulos($ls_codemp,$ls_codalm,$ls_codart,$li_canfisaux,
                   												        $ls_unidad,$li_canexisis,$lb_ok,$li_unidad,$ls_codpro,$ls_codtie);
							if ($lb_valido)
							{				
								$lb_valido=$io_siv->uf_sim_insert_dt_tomainventario($ls_codemp,$ls_codalm,$ls_numtom,
								$ls_codart,$li_canexisis,$li_canfisaux,$li_canexifisant,$ls_unidad,$la_seguridad,$ls_codpro,$ls_codtie);	
					
								if($lb_valido)
								{
									$io_finv->uf_seleccionarcombo("D-M",$ls_unidad,$la_unidad,2);
									$lo_object[$li_i][1]="<input name=txtcodart".$li_i." type=text id=txtcodart".$li_i." class=sin-borde size=21 maxlength=20 value='".$ls_codart."' readonly>";
									$lo_object[$li_i][2]="<input name=txtdenart".$li_i." type=text id=txtdenart".$li_i." class=sin-borde size=40 maxlength=50 value='".$ls_denart."' readonly>";
									$lo_object[$li_i][3]="<div align='center'><select name=cmbunidad".$li_i." style='width:110px '><option value=D ".$la_unidad[0].">Detal</option><option value=M ".$la_unidad[1].">Mayor</option></select></div>";
									$lo_object[$li_i][4]="<input name=txtcanfis".$li_i." type=text id=txtcanfis".$li_i." class=sin-borde size=12 maxlength=12 value='".$li_canfis."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); value=0,00>";
									if($lb_ok)
									{
										$lo_object[$li_i][5]="<img src='../shared/imagebank/ok.png' width=10 height=10";
									}
									else
									{
										$lo_object[$li_i][5]="<img src='../shared/imagebank/failed.png' width=10 height=10>";
										$lb_ajustar=true;
									}
								}
							}
						} // end for
					}
				}
				else
				{
					$io_sql->begin_transaction();
					$lb_valido=$io_siv->uf_sim_update_tomainventario($ls_codemp,$ls_codalm,$ls_numtom,$ld_fectomaux,
					                                                 $ls_obstom,$ls_codusu,$la_seguridad,$ls_codtie);
					if ($lb_valido)
					{
						for($li_i=1;$li_i<=$li_totrows;$li_i++)
						{
							$ls_codart=       $_POST["txtcodart".$li_i];
							$ls_denart=       $_POST["txtdenart".$li_i];
							$li_canfis=       $_POST["txtcanfis".$li_i];
							$li_canfisaux=    $_POST["txtcanfis".$li_i];
							$ls_unidad=       $_POST["cmbunidad".$li_i];
							$ls_codpro=       $_POST["txtcodpro"];
							$ls_denpro=       $_POST["txtdenpro"];
 			                $ls_obstom=       $_POST["txtobstom"];
							
							$la_unidad[0]="";
							$la_unidad[1]="";
							$li_canfisaux=    str_replace(".","",$li_canfisaux);
							$li_canfisaux=    str_replace(",",".",$li_canfisaux);
							$li_canexisis=0;
							$li_canexifisant=0;
							$li_unidad=0;
					
							$lb_valido=$io_siv->uf_sim_select_comparararticulos($ls_codemp,$ls_codalm,$ls_codart,$li_canfisaux,
																		$ls_unidad,$li_canexisis,$lb_ok,$li_unidad,$ls_codpro,$ls_codtie);
							if ($lb_valido)
							{				
								$lb_valido=$io_siv->uf_sim_update_dt_tomainventario($ls_codemp,$ls_codalm,$ls_numtom,
								$ls_codart,$li_canexisis,$li_canfisaux,$li_canexifisant,$ls_unidad,$la_seguridad,$ls_codpro,$ls_codtie);	
					
								if($lb_valido)
								{
									$io_finv->uf_seleccionarcombo("D-M",$ls_unidad,$la_unidad,2);
									$lo_object[$li_i][1]="<input name=txtcodart".$li_i." type=text id=txtcodart".$li_i." class=sin-borde size=21 maxlength=20 value='".$ls_codart."' readonly>";
									$lo_object[$li_i][2]="<input name=txtdenart".$li_i." type=text id=txtdenart".$li_i." class=sin-borde size=40 maxlength=50 value='".$ls_denart."' readonly>";
									$lo_object[$li_i][3]="<div align='center'><select name=cmbunidad".$li_i." style='width:110px '><option value=D ".$la_unidad[0].">Detal</option><option value=M ".$la_unidad[1].">Mayor</option></select></div>";
									$lo_object[$li_i][4]="<input name=txtcanfis".$li_i." type=text id=txtcanfis".$li_i." class=sin-borde size=12 maxlength=12 value='".$li_canfis."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); value=0,00>";
									if($lb_ok)
									{
										$lo_object[$li_i][5]="<img src='../shared/imagebank/ok.png' width=10 height=10";
									}
									else
									{
										$lo_object[$li_i][5]="<img src='../shared/imagebank/failed.png' width=10 height=10>";
										$lb_ajustar=true;
									}
								}
							}
						} // end for
					}
				}
				if($lb_valido)
				{
					$io_sql->commit();
					$io_msg->message("La toma de inventario ha sido procesada");
				}
				else
				{
					$io_sql->rollback();
					$io_msg->message("No se pudo procesar la toma de inventario");
					uf_limpiarvariables();
					uf_agregarlineablanca($lo_object,$li_totrows);
				}
				
		/*	    print("<script language=JavaScript>");
				print("pagina='sigesp_sim_p_toma.php';");
				print(" location.href='sigesp_sfc_d_liberar.php?pagina='+pagina;");
				print("</script>");	*/			
			}
			else
			{
				$io_msg->message("El mes no esta abierto");
                uf_limpiarvariables();
				uf_agregarlineablanca($lo_object,$li_totrows);			
			}
		break;
		
		case "AJUSTAR":
			uf_limpiarvariables();
			$lb_ok=false;
			$ls_nummov="";
			$ls_nomsol="Toma de Inventario";
			$li_totrows=  $_POST["totalfilas"];
			$ls_numtoma=  $_POST["txtnumtom"];
			$ls_codalm=   $_POST["txtcodalm"];
			$ls_nomfisalm=$_POST["txtnomfisalm"];
			$ld_fectom=   $_POST["txtfectom"];
			$ls_codpro=   $_POST["txtcodpro"];
			$ls_denpro=   $_POST["txtdenpro"];
			$ls_obstom=   $_POST["txtobstom"];
			$ls_numdocori="";  // Numero de documento original
			$ld_fecdesart=$ld_fectom;
			
            $ls_codtiend=$io_mov->uf_sim_select_tienda($ls_codemp,$ls_codalm,$ls_codpro);
			$ld_fectomaux= $io_func->uf_convertirdatetobd($ld_fectom);
			$io_sql->begin_transaction();
			$lb_valido=$io_mov->uf_sim_insert_movimiento($ls_nummov,$ld_fectomaux,$ls_nomsol,$ls_codusu,$la_seguridad, $ls_codtiend);
			if($lb_valido)
			{
				for($li_i=1;$li_i<=$li_totrows;$li_i++)
				{
					$ls_codart=       $_POST["txtcodart".$li_i];
					$ls_denart=       $_POST["txtdenart".$li_i];
					$li_canfis=       $_POST["txtcanfis".$li_i];
					$li_canfisaux=    $_POST["txtcanfis".$li_i];
					$ls_unidad=       $_POST["cmbunidad".$li_i];
					//$ls_codpro=       $_POST["txtcodpro"];
			        $ls_denpro=       $_POST["txtdenpro"];
					$ls_obstom=       $_POST["txtobstom"];

					$la_unidad[0]="";
					$la_unidad[1]="";
					$li_canfisaux=    str_replace(".","",$li_canfisaux);
					$li_canfisaux=    str_replace(",",".",$li_canfisaux);
					$li_canexisis=0;
					$li_canexifisant=0;
					
					$lb_valido=$io_siv->uf_sim_select_comparararticulos($ls_codemp,$ls_codalm,$ls_codart,$li_canfisaux,$ls_unidad,
																		$li_canexisis,$lb_ok,$li_unidad,$ls_codpro,$ls_codtiend);
					if ($lb_valido)
					{
						if(!$lb_ok)
						{
							$li_preuniart=0.00;
							$lb_valido=$io_siv->uf_sim_load_ultimocosto($ls_codemp,$ls_codart,$li_preuniart,$ls_codtiend);
							if($ls_unidad=="M")
							{
								$li_canart=($li_canfisaux * $li_unidad);
							}
							else
							{
								$li_canart=$li_canfisaux;
							}
							if($li_canart > $li_canexisis )
							{
								$ls_opeinv="AJE";
								$ls_codprodoc="ALM";
								$ls_promov="TOM";
								$li_candesart=0.00;
								$li_canart= ($li_canart - $li_canexisis);
								$lb_valido=$io_mov->uf_sim_insert_dt_movimiento($ls_codemp,$ls_nummov,$ld_fectomaux,$ls_codart,$ls_codalm,$ls_opeinv,
																			    $ls_codprodoc,$ls_numtoma,$li_canart,$li_preuniart,$ls_promov,$ls_numdocori,
																			    $li_candesart,$ld_fectomaux,$la_seguridad,$ls_codtiend,$ls_codpro);
																			   
								if($lb_valido)
								{
									$lb_valido=$io_art->uf_sim_aumentar_articuloxalmacen($ls_codemp,$ls_codart,$ls_codalm,
																					 	 $li_canart,$ls_codtiend,$ls_codpro,$la_seguridad);
								}
							}
							else
							{
								if($li_canart < $li_canexisis )
								{
									$ls_opeinv="AJS";
									$ls_codprodoc="ALM";
									$ls_promov="TOM";
									$li_canart= ($li_canexisis - $li_canart);
									$li_candesart=$li_canart;
									$lb_valido=$io_mov->uf_sim_insert_dt_movimiento($ls_codemp,$ls_nummov,$ld_fectomaux,$ls_codart,$ls_codalm,$ls_opeinv,
																			   $ls_codprodoc,$ls_numtoma,$li_canart,$li_preuniart,$ls_promov,$ls_numdocori,
																			   $li_candesart,$ld_fectomaux,$la_seguridad,$ls_codtiend,$ls_codpro);
									if($lb_valido)
									{
										$lb_valido=$io_art->uf_sim_disminuir_articuloxalmacen($ls_codemp,$ls_codart,$ls_codalm,$li_canart,$la_seguridad,$ls_codtiend,$ls_codpro);
									}
								}
							} // end else
							if($lb_valido)
							{
								$lb_valido=$io_art->uf_sim_actualizar_cantidad_articulos($ls_codemp,$ls_codart,$ls_codalm,$la_seguridad,$ls_codtiend,$ls_codpro);
							}
						} // end if(!$lb_ok)
					}

				} // end for
			}
			if($lb_valido)
			{
				$lb_valido=$io_siv->uf_sim_update_estatustoma($ls_codemp,$ls_codalm,$ls_numtoma,$la_seguridad,$ls_codtiend);
			}
			if($lb_valido)
			{
				$io_sql->commit();
				$io_msg->message("El ajuste de la toma de inventario fue procesado");
			}
			else
			{
				$io_sql->rollback();
				$io_msg->message("No se pudo procesar el ajuste de la toma de inventario");
			}
            uf_limpiarvariables();
		    uf_agregarlineablanca($lo_object,$li_totrows);						
		break;
	} // end switch
?>

<p>&nbsp;</p>
<div align="center">
  <table width="686" height="84" border="0" class="formato-blanco">
    <tr>
      <td width="800" height="78"><div align="left">
          <form name="form1" method="post" action="">
            <?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_activo->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_activo);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
            <table width="669" border="0">
  <tr class="titulo-ventana">
    <td colspan="5">    
      <div align="center">Toma de Inventario </div></td>
    </tr>
  <tr>
    <td width="6" height="22">&nbsp;</td>
    <td width="77">&nbsp;</td>
    <td width="123"><input name="txtnumtom" type="hidden" id="txtnumtom" value="<?php print $ls_numtom ?>">
      <input name="hidestpro" type="hidden" id="hidestpro" value="<?php print $ls_estpro ?>"></td>
    <td width="297"><div align="right">Fecha</div></td>
    <td width="144"><input name="txtfectom" type="text" id="txtfectom" style="text-align:center " value="<?php print $ld_fectom ?>" size="17" maxlength="10" readonly></td>
  </tr>
  <tr>
    <td height="22">&nbsp;</td>
    <td><div align="right">Proveedor</div></td>
    <td><input name="txtcodpro" type="text" id="txtcodpro" value="<?php print $ls_codpro?>" size="15" maxlength="10" style="text-align:center " readonly> 
      <a href="javascript: ue_cataproveedor();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></td>
    <td><input name="txtdenpro" type="text" class="sin-borde" id="txtdenpro" value="<?php print $ls_denpro ?>" size="50" readonly></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="22">&nbsp;</td>
    <td><div align="right">Almac&eacute;n</div></td>
    <td colspan="3"><input name="txtcodalm" type="text" id="txtcodalm" value="<?php print $ls_codalm ?>" size="12" maxlength="12" readonly>
      <a href="javascript: ue_catalmacen();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
      <input name="txtnomfisalm" type="text" class="sin-borde" id="txtnomfisalm" value="<?php print $ls_nomfisalm ?>" size="55" readonly>
      <input name="txtdesalm" type="hidden" id="txtdesalm">
      <input name="txttelalm" type="hidden" id="txttelalm">
<input name="txtubialm" type="hidden" id="txtubialm">
      <input name="txtnomresalm" type="hidden" id="txtnomresalm">
      <input name="txttelresalm" type="hidden" id="txttelresalm">
      <input name="hidstatus" type="hidden" id="hidstatus">
      <input name="txtcodtiend" type="hidden" id="txtcodtiend" value="<?php print $ls_codtie;?>"></td>
    </tr>
  <tr>
    <td height="15">&nbsp;</td>
    <td><div align="right">Observaci&oacute;n</div></td>
    <td colspan="2" rowspan="2"><textarea name="txtobstom" cols="80" rows="3" id="txtobstom"><?php print $ls_obstom ?></textarea></td
    ><td>&nbsp;</td>
  </tr>
  <tr>
    <td height="22">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="22">&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center"><input name="btnlistado" type="button" class="boton" id="btnlistado" value="Imprimir Listado" onClick="javascript: ue_imprimirlistado();"></td>
    <td align="center"><input name="btnajustar" type="button" class="boton" id="btnajustar4" value="Ajustar Inventario"  onClick="javascript: ue_ajustar();"></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="22" colspan="5" align="center"><?php
		$in_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
	?></td>
    </tr>
  <tr>
    <td height="22">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input name="operacion" type="hidden" id="operacion"></td>
    <td><input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
      <input name="ajustar" type="hidden" id="ajustar" value="<?php print $lb_ajustar?>"></td>
  </tr>
</table>
          </form>
      </div></td>
    </tr>
  </table>
</div>
<p align="center">&nbsp;</p>
</body>
<script language="javascript">
//Funciones de operaciones
function ue_catalmacen()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		window.open("sigesp_sim_cat_almacenestoma.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
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
		window.open("sigesp_sim_cat_tomaalmacen.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}
function ue_imprimirlistado()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{
		codalm   =f.txtcodalm.value;
		nomfisalm=f.txtnomfisalm.value;
		codpro   =f.txtcodpro.value;
		codtiend =f.txtcodtiend.value;
		if(codalm!="")
		{
			window.open("reportes/sigesp_sim_rfs_listadoinventario.php?codpro="+ codpro +"&codalm="+ codalm +"&nomfisalm="+ nomfisalm +"&codtiend="+ codtiend +"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
		}
		else
		{
			alert ("Debe seleccionar un almacen");
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
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{
		codalm= f.txtcodalm.value;
		nomfisalm= f.txtnomfisalm.value;
		numtom= f.txtnumtom.value;
		fectom= f.txtfectom.value;
		obstom= f.txtobstom.value;
		codtiend =f.txtcodtiend.value;
		if(numtom!="")
		{
			window.open("reportes/sigesp_sim_rfs_toma.php?codalm="+ codalm +"&numtom="+ numtom +"&fectom="+ fectom +"&obstom="+ obstom +"&codtiend="+ codtiend +"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
		}
		else
		{
			alert("Debe existir un documento a imprimir");
		}
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}


function ue_procesar()
{
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	if(li_ejecutar==1)
	{	
		lb_valido=false;
		ls_codalm=f.txtcodalm.value;
		li_total=f.totalfilas.value;
		if(ls_codalm=="")
		{
			alert("Debe seleccionar un almacen a procesar");
		}
		else
		{
			for(li_i=1;li_i<=li_total;li_i++)
			{
				ls_unidad= eval("f.cmbunidad"+li_i+".value");
				ls_denart= eval("f.txtdenart"+li_i+".value");
				if(ls_unidad=="-")
				{
					lb_valido=false;
					alert ("Debe indicar la unidad para "+ls_denart+"");
					break;
				}
				else{lb_valido=true;}
			}
			ls_estpro=f.hidestpro.value;
			if(ls_estpro==1)
			{
				alert("Ya esta toma ha sido ajustada. No se puede modificar");
				lb_valido=false;
			}
			if(lb_valido)
			{
				f.operacion.value="PROCESAR";
				f.action="sigesp_sim_p_toma.php";
				f.submit();
			}
		}
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_ajustar()
{
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	if(li_ejecutar==1)
	{	
		lb_ajustar=f.ajustar.value;
		if(lb_ajustar==1)
		{
			if(confirm("¿Seguro desea ajustar el inventario? \n este proceso no tiene reverso"))
			{
				f=document.form1;
				f.operacion.value="AJUSTAR";
				f.action="sigesp_sim_p_toma.php";
				f.submit();
			}
		}
		else
		{
			alert ("No hay articulos que ajustar");
		}
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}

function ue_cataproveedor()
{
	f=document.form1;
	window.open("sigesp_catdinamic_prov.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
}

</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>