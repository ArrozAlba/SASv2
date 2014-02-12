<?Php
/*************************************************************************/
/**************  INICIO DE LA PAGINA E INICIO DE SESION   ****************/
/*************************************************************************/
session_start();
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "location.href='sigesp_conexion.php'";
	print "</script>";
}
$la_datemp=$_SESSION["la_empresa"];
$pathbase = getcwd();
$pathbase= str_replace("sfc","siv",$pathbase);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Contabilizaci&oacute;n de movimientos del dia</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
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
.Estilo3 {color: #6699CC}
-->
</style>
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<?php
//********************************************         SEGURIDAD       ****************************************************
require_once("../shared/class_folder/sigesp_c_seguridad.php");
$io_seguridad= new sigesp_c_seguridad();

$arre=$_SESSION["la_empresa"];
$ls_empresa=$arre["codemp"];
$ls_logusr=$_SESSION["la_logusr"];
$ls_sistema="SFC";
$ls_ventanas="sigesp_sfc_d_cierre_diario.php";
$la_seguridad["empresa"]=$ls_empresa;
$la_seguridad["logusr"]=$ls_logusr;
$la_seguridad["sistema"]=$ls_sistema;
$la_seguridad["ventanas"]=$ls_ventanas;

if (array_key_exists("permisos",$_POST))
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
//*****************         SEGURIDAD    ****************************************/
?>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="2" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="535" height="20" class="cd-menu"><span class="descripcion_sistema Estilo1 Estilo3">Sistema de Facturaci&oacute;n</span></td>
    <td width="243" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">&nbsp;</td>
    <td height="20" class="cd-menu"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
  </tr>
  <tr>
    <td height="20" colspan="2" class="cd-menu">
	<script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script>
	<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>	</td>
  </tr>
  <tr>
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar"><a href="javascript: ue_ver();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Buscar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a><!--img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"--><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>
<?php
//********************************************         SEGURIDAD       ****************************************************
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();
	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SFC";
	$ls_ventanas="sigesp_sfc_d_cierrecaja.php";
	$la_seguridad["empresa"]=$ls_empresa;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
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
//**********************************************         SEGURIDAD    ****************************************//
//**************************************************************************************************************//
//**********************************************            LIBRERIAS     ****************************************//
//**************************************************************************************************************//
require_once("class_folder/sigesp_sfc_c_cajero.php");
require_once("class_folder/sigesp_sfc_c_cierre.php");
require_once("class_folder/sigesp_sfc_c_int_spi.php");
require_once("class_folder/sigesp_sfc_c_int_data.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("class_folder/sigesp_sfc_c_secuencia.php");
require_once("../shared/class_folder/class_funciones.php");


$io_secuencia=new sigesp_sfc_c_secuencia();
$io_function=new class_funciones();
$io_datastore= new class_datastore();
$io_datascg= new class_datastore();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_sql=new class_sql($io_connect);
$io_data=new class_datastore();
$io_data_scg=new class_datastore();
$io_cierre=new sigesp_sfc_c_cierre();
$io_msg=new class_mensajes();

$io_msg=new class_mensajes();
$io_cajero=new sigesp_sfc_c_cajero();
$io_intspi=new sigesp_sfc_c_int_spi();
$io_intdat=new sigesp_sfc_c_int_data();
$ls_total_facturado=0;
$ls_total_cobrado=0;
$ls_total_notfac=0;
$ls_total_notcob=0;

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codusu="%".$_POST["txtcodusu"]."%";
	$ls_codusuV=$_POST["txtcodusu"];
	$ls_nomusuV=$_POST["txtnomusu"];
	$ls_codcie=$_POST["hidcodcie"];
	$ls_feccie=$_POST["txtfeccie"];
	$ls_hidstatus=$_POST["hidstatus"];
	if(array_key_exists("chkprecie",$_POST)){
       $ls_precie="V";
    }
	else{
	   $ls_precie="F";
	}
	$ls_codcaj=$_POST["txtcodcaj"];
	$ls_nomcaj=$_POST["txtnomcaj"];
}
else
{
	$ls_operacion="";
	$ls_codusu="";
	$ls_codusuV="";
	$ls_nomusuV="";
	$ls_nomusu="";
	$ls_codcie="";
	$ls_precie="";
	$ls_feccie=date('d/m/Y');
	$ls_hidstatus="";
	$ls_codcaj="";
	$ls_nomcaj="";
}

?>
<p>&nbsp;</p>
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

    <table width="617" height="443" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="560" height="195"><div align="center">
          <table width="509"  border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
            <tr>
              <td colspan="3" class="titulo-ventana">Contabilizaci&oacute;n de movimientos del dia</td>
            </tr>
            <tr>
              <td >
			  <input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">
			  <input name="hidstatus" type="hidden" id="hidstatus" value="<? print $ls_hidstatus?>">
			  <input name="hidcodcie" type="hidden" id="hidcodcie" value="<?php print $ls_codcie ?>"></td>
              <td colspan="2" >&nbsp;</td>
            </tr>

			<tr>
			  <td height="22" align="right">&nbsp;</td>
			  <td width="341" ><div align="right">Fecha</div></td>
		      <td width="81" ><input name="txtfeccie" type="text" id="txtfeccie"  style="text-align:left" value="<?php print $ls_feccie?>" size="11" maxlength="10"  datepicker="true"  readonly="true"></td>
			</tr>

			<tr>
              <td width="85" height="22" align="right">Cajero </td>
              <td colspan="2" ><input name="txtcodusu" type="text" id="txtcodusu" style="text-align:center " value="<?php print $ls_codusuV?>" size="15" maxlength="15"  readonly="true">
			  <!-- javascript:ue_catusuario(); -->
              <a href="javascript:ue_catusuario();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de tipos de unidades"></a>
              <input name="txtnomusu" type="text" id="txtnomusu" class="sin-borde" size="40" readonly="true" value="<?php print $ls_nomusuV?>" ></td>
            </tr>
            <tr>
              <td width="85" height="22" align="right">Caja </td>
              <td colspan="2" ><input name="txtcodcaj" type="text" id="txtcodcaj" style="text-align:center " value="<?php print $ls_codcaj?>" size="15" maxlength="15"  readonly="true">
			  <!-- javascript:ue_catusuario(); -->
              <a href="javascript:ue_catcaja();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de tipos de unidades"></a>
              <input name="txtnomcaj" type="text" id="txtnomcaj" class="sin-borde" size="40" readonly="true" value="<?php print $ls_nomcaj?>" ></td>
            </tr>            <tr>
              <td width="85" height="22" align="right">&nbsp;</td>
              <td colspan="2" ><div align="left">
                <p>&nbsp;</p>
                <p><a href="javascript:ue_procesar();">Procesar<img src="Imagenes/ejecutar.gif" width="20" height="20" border="0"></a></p>
              </div></td>
            </tr>
            <tr>
              <td height="8" colspan="3">
				<?PHP
/************************************************************************************************************************/
/***************************   PROCESAR ********************************************************************************/
/************************************************************************************************************************/
$ls_codtie=$_SESSION["ls_codtienda"];
if($ls_operacion=="PROCESAR")
{
   $ls_fecprueba=substr($ls_feccie,6,4)."-".substr($ls_feccie,3,2)."-".substr($ls_feccie,0,2);
   $lb_validocierre=$io_cierre->uf_select_cierre($ls_fecprueba);
   if (!$lb_validocierre)
   {
   $io_msg->message ("No ha sido realizado el cierre para ese día!!!");
   }
   else
   {				
		print "<script language=JavaScript>document.form1.hidcodcie.value='".$ls_codcie."' </script>";
		$io_secuencia->uf_obtener_secuencia("numerocierre",&$ls_codcie);
		$ls_codcie=substr($ls_codcie,8,strlen($ls_codcie));
	   	/***************************************************************************************/
		/*****************************        INTEGRACION CONTABLE         *********************/
		/***************************************************************************************/			
			/****** DATOS BASICOS ***********/
			  $ls_numcomp=$ls_codcie;
			  $ls_numcomp=substr($ls_codtie,2,2).'-'.$io_function->uf_cerosizquierda($ls_numcomp,12);
			  $ls_nomtie        =$_SESSION["ls_nomtienda"];	  
			  $ld_totaliva=0;
			  $ld_totalivacontado=0;
			  $ld_totalivacredito=0;
			/********************************/	
							   
				require_once("class_folder/sigesp_sfc_c_contabilizar.php");  
				$io_contabiliza=new sigesp_sfc_c_contabilizar($ls_fecprueba);				
				$io_contabiliza->ls_spicuenta    =$_SESSION["ls_spicuenta"];	
				if ($io_contabiliza->ls_spicuenta<>'')
				{	
					$lb_valido=$io_intdat->uf_buscar_contrapartida($io_contabiliza->ls_spicuenta,&$io_contabiliza->ls_cuentascg);
					if ($lb_valido)
					{
						//Asignacion de Cuentas contables  para contabilizacion
						$lb_valido=$io_intdat->uf_buscar_cuentacontable('111010101',&$io_contabiliza->ls_cuentacaja);
						if ($lb_valido)
						{
							$lb_valido=$io_intdat->uf_buscar_cuentacontable('112030102',&$io_contabiliza->ls_cuentaxcob);
							if ($lb_valido)
							{
								$lb_valido=$io_intdat->uf_buscar_cuentacontable('112030103',&$io_contabiliza->ls_cuentacarta);
								if ($lb_valido)
								{
									$lb_valido=$io_intdat->uf_buscar_cuentacontable('219090101',&$io_contabiliza->ls_cuentaiva);
									if ($lb_valido)
									{
										$lb_valido=$io_intdat->uf_buscar_cuentacontable('219090201',&$io_contabiliza->ls_cuentaadelanto);
										if ($lb_valido)
										{
										$lb_valido=$io_intdat->uf_buscar_cuentacontable('643010103',&$io_contabiliza->ls_cuentadev);  
										if ($lb_valido)
										{
											$lb_valido=$io_intdat->uf_buscar_cuentacontable('691010101',&$io_contabiliza->ls_cuenta_costoventa);
											if ($lb_valido)
											{
												$lb_valido=$io_intdat->uf_buscar_cuentacontable('11304010',&$io_contabiliza->ls_cuenta_inventario);
												if ($lb_valido)
												{
													$lb_valido=$io_intdat->uf_buscar_cuentacontable('112049902',&$io_contabiliza->ls_cuenta_retiva);  
													if ($lb_valido)
													{
														$lb_valido=$io_intdat->uf_buscar_cuentacontable('112049904',&$io_contabiliza->ls_cuenta_retislr); 
														if ($lb_valido)
														{
															//$lb_valido=$io_intdat->uf_buscar_cuentacontable_retenciones('21103090101004',&$io_contabiliza->ls_cuenta_retmun); 
															$lb_valido=$io_intdat->uf_buscar_cuentacontable('613140101',&$io_contabiliza->ls_cuenta_retunoxmil); 
															//Establecimiento de parametros para el proceso de integracion de los movimientos.
															$io_contabiliza->io_int_int->is_codemp=$_SESSION["la_empresa"]["codemp"];
															$io_contabiliza->io_int_int->is_procedencia="SFCCIE";
															$io_contabiliza->io_int_int->is_comprobante=$ls_numcomp;
															$io_contabiliza->io_int_int->id_fecha=$ls_feccie;
															$_SESSION["fechacomprobante"]=$ls_feccie;
															$io_contabiliza->io_int_int->ii_tipo_comp=1;
															$io_contabiliza->io_int_int->is_descripcion="COMPROBANTE VENTAS AL CONTADO TIENDA  ".$_SESSION["ls_nomtienda"];
															$io_contabiliza->io_int_int->is_tipo='-';
															$io_contabiliza->io_int_int->is_cod_prov='----------';
															$io_contabiliza->io_int_int->is_ced_ben='----------';
															$io_contabiliza->io_int_int->as_codban='---';
															$io_contabiliza->io_int_int->as_ctaban='-------------------------'; 
															$io_contabiliza->io_int_int->io_int_scg->as_codban='---';
															$io_contabiliza->io_int_int->io_int_scg->as_ctaban='-------------------------'; 																 
															$io_contabiliza->io_int_int->is_modo='C'; 
															$lb_existe_contabilizacion=false;
															$lb_existe_contabilizacion=$io_intdat->uf_select_comprobante($_SESSION["la_empresa"]["codemp"],'SFCCIE',$ls_feccie,'---','-------------------------');
															//print $lb_existe_contabilizacion;
															if ($lb_existe_contabilizacion)
															{										
															
															$io_msg->message("El documento que intenta registrar ya existe en el sistema");													$io_msg->message("Ocurrio un error en el proceso");
															}
															else
															{																												
															//Procesamiento de la contabilizacion de los movimientos
															$io_contabiliza->io_int_int->uf_init_create_datastore();  
															$lb_valido=$io_contabiliza->uf_procesar_contabilizacion_facturas($la_seguridad); //Casos 1 - 2 - 3 .1 - 3.2 - 4 .1 - 4.2 - 4.3 - 5 
															if($lb_valido)
															{
															$lb_valido=$io_contabiliza->uf_procesar_contabilizacion_devoluciones($la_seguridad); //Casos 7.1 - 7.2 - 7.3 ------- Caso 6 
															}
															if($lb_valido)
															{
															$lb_valido=$io_contabiliza->uf_procesar_contabilizacion_dep_trans_credito(); //Casos 10.1 - 10.2 - 10.3
															}
															if($lb_valido)
															{
															$io_contabiliza->io_int_int->uf_int_init_transaction_begin();
															$lb_valido=$io_contabiliza->io_int_int->uf_init_end_transaccion_integracion($la_seguridad);
															}
															if($lb_valido)
															{
																$io_msg->message("Proceso Ejecutado Satisfactoriamente");
															}
														
															else
															{
																	$io_msg->message("Ocurrio un error en el proceso");
															}
															$io_contabiliza->io_int_int->uf_sql_transaction($lb_valido);
														}
														
														}
														else
														{
															$io_msg->message("Ocurrio un error en el proceso, verifique que la tienda tenga una cuenta contable para retencion de 1x100");
														}
													}
													else
													{
														$io_msg->message("Ocurrio un error en el proceso, verifique que la tienda tenga una cuenta contable para retencion de ISLR");
													}
												}
												else
												{
													$io_msg->message("Ocurrio un error en el proceso, verifique que la tienda tenga una cuenta contable para retencion de IVA");
												}
											}
											else
											{
												$io_msg->message("Ocurrio un error en el proceso, verifique que la tienda tenga una cuenta contable para Inventario");
											}
										}
										else
										{
											$io_msg->message("Ocurrio un error en el proceso, verifique que la tienda tenga una cuenta contable para Costo de Venta");
										}
									}
									else
									{
										$io_msg->message("Ocurrio un error en el proceso, verifique que la tienda tenga una cuenta contable para Devoluciones");
									}
								}
								else
								{
									$io_msg->message("Ocurrio un error en el proceso, verifique que la tienda tenga una cuenta contable para Adelantos Recibidos");
								}
							}
							else
							{
								$io_msg->message("Ocurrio un error en el proceso, verifique que la tienda tenga una cuenta contable para Debito Fiscal");
							}
						}
						else
						{
							$io_msg->message("Ocurrio un error en el proceso, verifique que la tienda tenga una cuenta contable para Cartas Ordenes");
						}
					}
					else
					{
						$io_msg->message("Ocurrio un error en el proceso, verifique que la tienda tenga una cuenta contable para Caja");
					}
				}
				else
				{
					$io_msg->message("Ocurrio un error en el proceso, verifique que la tienda tenga una cuenta contable asociada");
				}														
		}
		else
		{
		$io_msg->message("Ocurrio un error en el proceso, verifique que la tienda tiene una cuenta presupuestaria de ingreso asociada");		
		}					
  	}
 }

/************************************** FIN DE RUTINA "PROCESAR" *****************************************************/
?>
              <p>&nbsp;</p></td>
            </tr>
          </table>
          <p>&nbsp;</p>
        </div></td>
      </tr>
  </table>
</form>
</body>

<script language="JavaScript">

/*******************************************************************************************************************************/
	function ue_procesar()
	{
		f=document.form1;
		 if(f.txtcodcaj.value=="" || f.txtcodusu.value=="")
		 {
			alert("Debe Seleccionar el cajero y la caja de la cual desea hacer el cierre");
		  }
		  else
		  {
			li_incluir=f.incluir.value;
			li_cambiar=f.cambiar.value;
			lb_status=f.hidstatus.value;
			if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
			{
				if (lb_status!="C")
				{
					f.hidstatus.value="C";
				}
			
					f.operacion.value="PROCESAR";
					f.action="sigesp_sfc_d_cierre_diario.php";
					f.submit();
			}
			else
			{
					alert("No tiene permiso para realizar esta operacion");
			}
	  }
}

function ue_ver()
{
  f=document.form1;
  f.operacion.value="VER";
  f.action="sigesp_sfc_d_cierre_diario.php";
  f.submit();
}
function ue_nuevo()
{
	f=document.form1;

	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{
		f.operacion.value="ue_nuevo";
		f.txtcodusu.value="";
		f.txtnomusu.value="";
		f.txtcodtie.value="";
		f.txtnomtie.value="";
		f.action="sigesp_sfc_d_cajero.php";
		f.submit();
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
		if (lb_status!="C")
		{
			f.hidstatus.value="C";
		}

		with(f)
		{
			if (ue_valida_null(txtcodusu,"Usuario")==false)
			{
				txtcodusu.focus();
			}
			else
			{
				if (ue_valida_null(txtcodtie,"Tienda")==false)
				{
				  txtcodtie.focus();
				}
				else
				{
					f.operacion.value="ue_guardar";
					f.action="sigesp_sfc_d_cajero.php";
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

function ue_eliminar()
{
	f=document.form1;
	li_eliminar=f.eliminar.value;	
	if( li_eliminar==1)
	{
		with(f)
		{
			if (ue_valida_null(txtcodusu,"Usuario")==false)
			{
				txtcodusu.focus();
			}
			else
			{
				if (confirm("ï¿½ Esta seguro de eliminar este registro ?"))
				{
					f.operacion.value="ue_eliminar";
					f.action="sigesp_sfc_d_cierre_diario.php";
					f.submit();
				}
				else
				{
					f=document.form1;
					f.action="sigesp_sfc_d_cierre_diario.php";
					alert("Eliminaciï¿½n Cancelada !!!");
					f.txtcodusu.value="";
					f.txtnomusu.value="";
					f.operacion.value="";
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

function ue_buscar()
{
	f=document.form1;

	li_leer=f.leer.value;
	if(li_leer==1)
	{
		f.operacion.value="";
		pagina="sigesp_cat_cierre.php";
		popupWin(pagina,"catalogo",650,300);
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_cargarcaja(codcaja,desccaja,precot,prefac,predev,preped,sercot,serfac,serdev,serped,sernot,sercon,formalibre,precob,sercob)
{
	f=document.form1;
	f.txtcodcaj.value = codcaja;
	f.txtnomcaj.value = desccaja;

}

function ue_catcaja(){
	pagina="sigesp_cat_caja.php";
	popupWin(pagina,"catalogo",650,300);
}

/***********************************************************************************************************************************/

		function ue_catusuario()
		{
            f=document.form1;
			f.operacion.value="";
		    pagina="../sss/sigesp_sss_cat_usuarios.php?destino=Reporte";
	    	popupWin(pagina,"catalogo",520,200);
		}

		function ue_cattienda()
		{
            f=document.form1;
			f.operacion.value="";
		    pagina="sigesp_cat_tienda.php";
	    	popupWin(pagina,"catalogo",520,200);
		}

		function ue_cargartienda(codtie,nomtie,dirtie,teltie,riftie,codpai,codest,codmun,codpar)
		{
 	   	    f=document.form1;
			f.txtcodtie.value=codtie;
        	f.txtnomtie.value=nomtie;

		}

		function ue_cargarcajero(codusu,nomusu,codtie,nomtie)
		{
		    f=document.form1;
			f.txtcodusu.value=codusu;
			f.txtnomusu.value=nomusu;
			/*f.txtcodtie.value=codtie;*/
			/*f.txtnomtie.value=nomtie;*/
       	}

		function ue_cargarcierre(codcie,codusu,feccie,nomusu)
		{
		    f=document.form1;
			f.hidcodcie.value=codcie;
			f.txtfeccie.value=feccie;
			f.txtcodusu.value=codusu;
			f.txtnomusu.value=nomusu;
			f.hidstatus.value="C";
		}


/***********************************************************************************************************************************/

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
