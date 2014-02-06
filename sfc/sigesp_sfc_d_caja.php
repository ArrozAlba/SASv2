<?Php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../index.php'";
	 print "</script>";
   }
$la_datemp=$_SESSION["la_empresa"];
$ls_codtie=$_SESSION["ls_codtienda"];
//$ls_codtie='0002'
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Definici&oacute;n de Caja</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
.Estilo1 {color: #6699CC}
-->
</style>
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="2" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="421" height="20" class="cd-menu"><span class="descripcion_sistema Estilo1 Estilo1">Sistema de Facturaci&oacute;n</span></td>
    <td width="357" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
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
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a><!--img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"--><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>

<?Php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SFC";
	$ls_ventanas="sigesp_sfc_d_caja.php";

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

//////////////////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////*/
	require_once("class_folder/sigesp_sfc_c_caja.php");
	require_once("class_folder/sigesp_sfc_c_secuencia.php");
	require_once("class_folder/sigesp_sfc_c_factura.php");
	require_once("class_folder/sigesp_sfc_c_cotizacion.php");
	require_once("class_folder/sigesp_sfc_c_cobranza.php");
	require_once("class_folder/sigesp_sfc_c_cobranzacarta.php");
	require_once("class_folder/sigesp_sfc_c_devolucion.php");
	require_once("class_folder/sigesp_sfc_c_nota.php");
	require_once("class_folder/sigesp_sfc_c_pedido.php");
	require_once("class_folder/sigesp_sfc_c_ordenentrega.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_funciones.php");

	$is_msg=new class_mensajes();
	$io_secuencia=new sigesp_sfc_c_secuencia();
	$io_caja=new sigesp_sfc_c_caja();

	$io_include = new sigesp_include();
	$io_connect = $io_include->uf_conectar();
	$io_sql= new class_sql($io_connect);
	$io_factura= new sigesp_sfc_c_factura();
	$io_cotizacion= new sigesp_sfc_c_cotizacion();
	$io_cobro= new sigesp_sfc_c_cobranza();
	$io_cobrocarta= new sigesp_sfc_c_cobranzacarta();
	$io_devol= new sigesp_sfc_c_devolucion();
	$io_nota= new sigesp_sfc_c_nota();
	$io_pedido= new sigesp_sfc_c_pedido();
	$io_orden= new sigesp_sfc_c_ordenentrega();
	$io_function = new class_funciones();
/********************************************************************************************************************************/

	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_codcaja=$_POST["txtcodcaja"];
		$ls_desccaja=$_POST["txtdesccaja"];
		$ls_codtienda=$_POST["txtcodtienda"];
		$ls_tienda=$_POST["txttienda"];

		$ls_concotusu=$_POST["txtconcotusu"];
		$ls_confacusu=$_POST["txtconfacusu"];
		$ls_connotusu=$_POST["txtconnotusu"];
		$ls_condevusu=$_POST["txtcondevusu"];
		$ls_conpedusu=$_POST["txtconpedusu"];
		$ls_conconusu=$_POST["txtconconusu"];
		$ls_concobusu=$_POST["txtconcobusu"];
		$ls_conordusu=$_POST["txtconordusu"];


		$ls_precot=$_POST["txtprecot"];
		$ls_prefac=$_POST["txtprefac"];
		$ls_predev=$_POST["txtpredev"];
		$ls_preped=$_POST["txtpreped"];
		$ls_precob=$_POST["txtprecob"];
		$ls_preordent=$_POST["txtpreord"];

		$ls_sercot=$_POST["txtsercot"];
		$ls_serfac=$_POST["txtserfac"];
		$ls_sernot=$_POST["txtsernot"];
		$ls_serdev=$_POST["txtserdev"];
		$ls_serped=$_POST["txtserped"];
		$ls_sercon=$_POST["txtsercon"];
		$ls_sercob=$_POST["txtsercob"];
		$ls_serordent=$_POST["txtserord"];		
		$ls_hidstatus=$_POST["hidstatusseg"];
		$ls_formalibreordent="";
		if($ls_operacion=="ue_cargar")
			$ls_formalibre=$_POST["hidopcformlib"];
		else
			$ls_formalibre=$_POST["opcformlib"];

		$ls_status=$_POST["hidstatus"];
    }
	else
	{
		$ls_operacion="";
		$ls_hidstatus="";
		$ls_codcaja="";
		$ls_desccaja="";
		$ls_destienda="";
		$ls_concotusu="0";
		$ls_confacusu="0";
		$ls_connotusu="0";
		$ls_condevusu="0";
		$ls_conpedusu="0";
		$ls_conconusu="0";
		$ls_concobusu="0";
		$ls_conordusu="0";

		$ls_precot="COT";
	    $ls_prefac="FAC";
	    $ls_predev="DEV";
		$ls_preped="PED";
		$ls_precob="COB";
		$ls_preordent="ORD";
		
		$ls_sercot="A";
		$ls_serfac="A";
		$ls_sernot="A";
		$ls_serdev="A";
		$ls_serped="A";
		$ls_sercon="A";
		$ls_sercob="A";
		$ls_serordent="A";			
		$ls_formalibre="S";

		$ls_status="";
	}



	/*Cuando la operacion es UE_NUEVO*/
	if($ls_operacion=="ue_nuevo")
	{
		$ls_codcaja="";
		$ls_desccaja="";
		$ls_destienda="";
		$ls_hidstatus="";
		$ls_concotusu="0";
		$ls_confacusu="0";
		$ls_connotusu="0";
		$ls_condevusu="0";
		$ls_conpedusu="0";
		$ls_concobusu="0";
		$ls_conordusu="0";
		$ls_precot="COT";
	    $ls_prefac="FAC";
	    $ls_predev="DEV";
		$ls_preped="PED";
		$ls_precob="COB";
		$ls_preordent="ORD";
		$ls_sercot="A";
		$ls_serfac="A";
		$ls_sernot="A";
		$ls_serdev="A";
		$ls_serped="A";
		$ls_sercon="A";
		$ls_sercob="A";
		$ls_serordent="A";			
		$ls_formalibre="S";

		$ls_status="";

	}
	elseif($ls_operacion=="ue_guardar")
	{


		$ls_num=$io_function->uf_cerosizquierda($ls_confacusu+1,20);
		$ls_numfactem=$ls_prefac."-".$ls_serfac.$ls_num;
		//print $ls_numfactem;
		$lb_validofactura=$io_factura->uf_select_factura($ls_numfactem);
		if ($lb_validofactura)
		{
			// print "NO VALIDO";
			 $is_msg->message ("El correlativo de la Factura es Invalido, Ya existe una factura con ese correlativo!!!");
		}
		else
		{
			$ls_num=$io_function->uf_cerosizquierda($ls_concotusu+1,20);
			$ls_numcottem=$ls_precot."-".$ls_sercot.$ls_num;
			//print $ls_numfactem;
			$lb_validocot=$io_cotizacion->uf_select_cotizacion($ls_numcottem);
			if ($lb_validocot)
			{
				// print "NO VALIDO";
				 $is_msg->message ("El correlativo de la Cotizacion es Invalido, Ya existe una cotizacion con ese correlativo!!!");
			}else{

				$ls_num=$io_function->uf_cerosizquierda($ls_concobusu+1,20);
				$ls_numcobtem=$ls_precob."-".$ls_sercob.$ls_num;
				//print $ls_numfactem;
				$lb_validocob=$io_cobro->uf_select_cobro($ls_numcobtem,$ls_codtienda);
				$lb_validocobcarta=$io_cobrocarta->uf_select_cobro($ls_numcobtem,$ls_codtienda);


				if (($lb_validocob) or ($lb_validocobcarta))
				{
					// print "NO VALIDO";
					 $is_msg->message ("El correlativo de la Cobranzas es Invalido, Ya existe una Cobranza con ese correlativo!!!");
				}else{

					$ls_num=$io_function->uf_cerosizquierda($ls_condevusu+1,20);
					$ls_numdevtem=$ls_predev."-".$ls_serdev.$ls_num;
					//print $ls_numfactem;
					$estdev="";
					$lb_validodev=$io_devol->uf_select_devolucion($ls_numdevtem,$ls_codtienda,$estdev);
					if ($lb_validodev)
					{
						// print "NO VALIDO";
						 $is_msg->message ("El correlativo de la Devolucion es Invalido, Ya existe una Devolucion con ese correlativo!!!");
					}else{

						$ls_num=$io_function->uf_cerosizquierda($ls_conpedusu+1,20);
						$ls_numpedtem=$ls_preped."-".$ls_serped.$ls_num;
						//print $ls_numfactem;
						$lb_validoped=$io_pedido->uf_select_pedido($ls_numpedtem,$ls_codtienda);
						if ($lb_validoped)
						{
							// print "NO VALIDO";
							 $is_msg->message ("El correlativo del Pedido es Invalido, Ya existe un Pedido con ese correlativo!!!");
						}
						else
						{
							$ls_num=$io_function->uf_cerosizquierda($ls_conordusu+1,20);
							$ls_numordtem=$ls_preordent."-".$ls_serordent.$ls_num;
						    $lb_validoorden=$io_orden->uf_select_orden($ls_numordtem,$ls_codtienda);						
							$lb_validoorden=false;
							if ($lb_validoorden)
							{
						
								 $is_msg->message ("El correlativo de la Orden de Entrega es Invalido, Ya existe una Orden con ese correlativo!!!");
							}
							else
							{	
								$lb_valido=$io_caja->uf_guardar_caja($ls_codcaja,$ls_desccaja,$ls_precot,$ls_prefac,$ls_predev,$ls_preped,$ls_sercot,$ls_serfac,$ls_serdev,$ls_serped,$ls_sernot,$ls_sercon,$ls_formalibre,$ls_precob,$ls_sercob,$ls_codtienda,$ls_preordent,$ls_serordent,$ls_formalibreordent,$la_seguridad,&$lb_existe_caja);
								$ls_mensaje=$io_caja->io_msgc;
	
								if ($lb_valido==true)
								{
									if($ls_status=="1" )
									{
	
									    $io_secuencia->uf_actualizar_secuencia($ls_codcaja.$ls_codtienda."cot",$ls_concotusu);
									    $io_secuencia->uf_actualizar_secuencia($ls_codcaja.$ls_codtienda."fac",$ls_confacusu);
									    $io_secuencia->uf_actualizar_secuencia($ls_codcaja.$ls_codtienda."not",$ls_connotusu);
									    $io_secuencia->uf_actualizar_secuencia($ls_codcaja.$ls_codtienda."dev",$ls_condevusu);
									    $io_secuencia->uf_actualizar_secuencia($ls_codcaja.$ls_codtienda."cob",$ls_concobusu);
									    $io_secuencia->uf_actualizar_secuencia($ls_codcaja.$ls_codtienda."ped",$ls_conpedusu);
										$io_secuencia->uf_actualizar_secuencia($ls_codcaja.$ls_codtienda."ord",$ls_conordusu);								   
										$secuen=$io_secuencia->uf_ver_secuenciaexiste($ls_codcaja.$ls_codtienda."con",&$ls_lastvalcon);
										$ls_con=$ls_lastvalcon;
	
										if(($secuen==false) and ($ls_con<>" "))
										{
										   if($ls_formalibre=="S")
										   {
											$io_secuencia->uf_crear_secuencia2($ls_codcaja.$ls_codtienda."con",$ls_conconusu);
										   }
										}
										else
										{
											if($ls_formalibre=="S")
											$io_secuencia->uf_actualizar_secuencia($ls_codcaja.$ls_codtienda."con",$ls_conconusu);											
										}
										$is_msg->message ($ls_mensaje);
									}
									else
									{
									    if(!$lb_existe_caja)
										{
											$io_secuencia->uf_crear_secuencia($ls_codcaja.$ls_codtienda."cot",$ls_concotusu);
											$io_secuencia->uf_crear_secuencia($ls_codcaja.$ls_codtienda."fac",$ls_confacusu);
											$io_secuencia->uf_crear_secuencia($ls_codcaja.$ls_codtienda."not",$ls_connotusu);
											$io_secuencia->uf_crear_secuencia($ls_codcaja.$ls_codtienda."dev",$ls_condevusu);
											$io_secuencia->uf_crear_secuencia($ls_codcaja.$ls_codtienda."cob",$ls_concobusu);
											$io_secuencia->uf_crear_secuencia($ls_codcaja.$ls_codtienda."ped",$ls_conpedusu);
											$io_secuencia->uf_crear_secuencia($ls_codcaja.$ls_codtienda."ord",$ls_conordusu);								   								   
											if($ls_formalibre=="S")
											{
												$io_secuencia->uf_crear_secuencia($ls_codcaja.$ls_codtienda."con",$ls_conconusu);		
											}
										    $is_msg->message ($ls_mensaje);
										}
										else
										{
											$is_msg->message ("La caja que intenta crear ya existe, si desea modificarla \\n seleccionela desde el catálogo");
										}
										
									}
	
									//$is_msg->message ($ls_mensaje);
									$ls_codcaja="";
									$ls_desccaja="";
									$ls_destienda="";
									$ls_concotusu="";
									$ls_confacusu="";
									$ls_connotusu="";
									$ls_condevusu="";
									$ls_conpedusu="";
									$ls_conconusu="";
									$ls_concobusu="";
									$ls_conordusu="";
									$ls_precot="COT";
									$ls_prefac="FAC";
									$ls_predev="DEV";
									$ls_preped="PED";
									$ls_precob="COB";
									$ls_preordent="ORD";
									$ls_sercot="";
									$ls_serfac="";
									$ls_sernot="";
									$ls_serdev="";
									$ls_serped="";
									$ls_sercon="";
									$ls_sercob="";
									$ls_serordent="";
									$ls_hidstatus="";
									$ls_formalibre="N";
									?>
									<script language="javascript">
										//alert("Ahora debera Ingresar Nuevamente para Actualizar su Sesion");
										//window.location="../index.php";	
									</script>
							<?
	
								}//if lb_valido
								else
								{
									if($lb_valido==0)
									{
										$ls_codcaja="";
										$ls_desccaja="";
										$ls_destienda="";
										$ls_concotusu="";
										$ls_confacusu="";
										$ls_connotusu="";
										$ls_condevusu="";
										$ls_conpedusu="";
										$ls_conconusu="";
										$ls_concobusu="";
										$ls_conordusu="";
										$ls_precot="COT";
										$ls_prefac="FAC";
										$ls_predev="DEV";
										$ls_preped="PED";
										$ls_precob="COB";
										$ls_preordent="ORD";
										$ls_sercot="";
										$ls_serfac="";
										$ls_sernot="";
										$ls_serdev="";
										$ls_serped="";
										$ls_sercon="";
										$ls_sercob="";
										$ls_serordent="";
										$ls_formalibre="";
										$ls_hidstatus="";
									}
									else
									{
										//$is_msg->message ($ls_mensaje);
									}
								}//else lb_valido
	
							}
						}	
					}

				}

			}
		}
	}
/*******************************************************************************************************************************/
/**************************************************      ELIMINAR      *********************************************************/
/*******************************************************************************************************************************/
	elseif($ls_operacion=="ue_eliminar")
	{


	/***********************  verificar si la caja estï¿½ relacionada a un cajero***********************************************/
	     $ls_sql="SELECT *
                   FROM sfc_cajero
                   WHERE codemp='".$la_datemp["codemp"]."' AND codtiend='".$ls_codtienda."'AND cod_caja='".$ls_codcaja."'";

		$rs_datauni=$io_sql->select($ls_sql);

		if($rs_datauni==false)
		{
			$lb_valido_cot=false;
		}
		else
		{
			if($row=$io_sql->fetch_row($rs_datauni))
			{
				$lb_valido_cot=true; //Registro encontrado
		        $is_msg->message ("La caja tiene cajeros asociados, no se puede eliminar!!!");
			}
			else
			{
				$lb_valido_cot=false; //"Registro no encontrado"
			}
		}


       /********************************************************************************************************************/

	if ($lb_valido_cot==false) // si caja no posee cajero asociado se puede ï¿½eliminar!
	 {
		$lb_valido=$io_caja->uf_delete_caja($ls_codcaja,$ls_codtienda,$la_seguridad);
		if ($lb_valido===true)
		{
			//$is_msg->message($io_caja->io_msgc);
			$ls_desccaja="";
			$ls_concotusu="";
		    $ls_confacusu="";
		    $ls_connotusu="";
		    $ls_condevusu="";
			$ls_conpedusu="";
			$ls_conconusu="";
			$ls_concobusu="";
			$ls_conordusu="";
			$ls_precot="COT";
		    $ls_prefac="FAC";
		    $ls_predev="DEV";
			$ls_preped="PED";
			$ls_precob="COB";
			$ls_preord="ORD";
		    $ls_sercot="";
		    $ls_serfac="";
		    $ls_sernot="";
		    $ls_serdev="";
			$ls_serped="";
			$ls_sercon="";
			$ls_sercob="";
			$ls_serordent="";
			$ls_hidstatus="";
			$io_secuencia->uf_eliminar_secuencia($ls_codcaja.$ls_codtienda."cot");
			$io_secuencia->uf_eliminar_secuencia($ls_codcaja.$ls_codtienda."fac");
			$io_secuencia->uf_eliminar_secuencia($ls_codcaja.$ls_codtienda."not");
			$io_secuencia->uf_eliminar_secuencia($ls_codcaja.$ls_codtienda."dev");
			$io_secuencia->uf_eliminar_secuencia($ls_codcaja.$ls_codtienda."ped");
			$io_secuencia->uf_eliminar_secuencia($ls_codcaja.$ls_codtienda."cob");
			$io_secuencia->uf_eliminar_secuencia($ls_codcaja.$ls_codtienda."ord");
			if($ls_formalibre=="S"){
			 	$io_secuencia->uf_eliminar_secuencia($ls_codcaja.$ls_codtienda."con",$ls_conconusu);
			}
			$ls_codcaja="";
		}
	  }
}
elseif($ls_operacion=="ue_cargar")
{
    $io_secuencia->uf_ver_secuencia($ls_codcaja.$ls_codtienda."cot",&$ls_lastvalcot);
	$io_secuencia->uf_ver_secuencia($ls_codcaja.$ls_codtienda."fac",&$ls_lastvalfac);
	$io_secuencia->uf_ver_secuencia($ls_codcaja.$ls_codtienda."not",&$ls_lastvalnot);
	$io_secuencia->uf_ver_secuencia($ls_codcaja.$ls_codtienda."dev",&$ls_lastvaldev);
	$io_secuencia->uf_ver_secuencia($ls_codcaja.$ls_codtienda."ped",&$ls_lastvalped);
	$io_secuencia->uf_ver_secuencia($ls_codcaja.$ls_codtienda."cob",&$ls_lastvalcob);
	$io_secuencia->uf_ver_secuencia($ls_codcaja.$ls_codtienda."ord",&$ls_lastvalord);
	if($ls_formalibre=="S")
	 {
	 	$io_secuencia->uf_ver_secuencia($ls_codcaja.$ls_codtienda."con",&$ls_lastvalcon);
	 }



	$ls_concotusu=$ls_lastvalcot;
    $ls_confacusu=$ls_lastvalfac;
    $ls_connotusu=$ls_lastvalnot;
    $ls_condevusu=$ls_lastvaldev;
	$ls_conpedusu=$ls_lastvalped;
	$ls_conconusu=$ls_lastvalcon;
	$ls_concobusu=$ls_lastvalcob;
	$ls_conordusu=$ls_lastvalord;

}
elseif($ls_operacion=="ue_actualizar_option")
	{
		  if ( $ls_formalibre=="S")
		  {
		 $ls_formalibre="S";

		  }
		  else
		  {
		 $ls_formalibre="N";

		  }
		$ls_operacion="";

	}

?>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php


//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos))
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
//////////////////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////
?>

    <table width="624" height="312" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="683" height="268">
          <table width="568"  border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
            <tr>
              <td colspan="7" class="titulo-ventana">Caja</td>
            </tr>
            <tr>
              <td >
			    <input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">
                <input name="hidstatus" type="hidden" id="hidstatus"  value="<? print $ls_status?>">
				<input name="hidstatusseg" type="hidden" id="hidstatusseg"  value="<? print $ls_hidstatus?>">
                <input name="hidopcformlib" type="hidden" id="hidopcformlib"  value="<? print $ls_formalibre?>">
				<input name="txtcodtienda" type="hidden" id="txtcodtienda"  value="<? print $ls_codtienda?>"></td>
                
			   <td width="433" >&nbsp;</td>
			   <td width="18" colspan="6" >&nbsp;</td>
            </tr>

			<tr>
              <td width="115" height="22" align="right">Caja </td>
              <td colspan="6" ><input name="txtcodcaja" type="text" style="text-align:left" id="txtcodcaja" value="<? print  $ls_codcaja;?>" size="4" maxlength="3" onKeyPress="return(validaCajas(this,'a',event,254))" >
              </td>
			</tr>
            <tr>
              <td  width="115" height="22" align="right">Descripcion </td>
               <td><input name="txtdesccaja" type="text" style="text-align:left " id="txtdesccaja" value="<? print  $ls_desccaja;?>" size="50" maxlength="100" > </td>
            </tr>


            <tr>
              <td  width="115" height="22" align="right">Unidad Operativa de Suministro </td>
               <td><input name="txttienda" type="text" style="text-align:left " id="txttienda" value="<? print  $ls_codtienda;?>" size="50" maxlength="100" >
               <a href="javascript:ue_buscartienda();">
        <img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a>
         </td>

            </tr>



            <tr>
              <td height="22" colspan="7" align="right">&nbsp;</td>
            </tr>
            <tr>
              <td height="22" colspan="7" align="right"><table border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco" width="552">
                <tr>
                  <td height="17" colspan="7"  class="titulo-ventana">Codificaci&oacute;n de Documentos </td>
                </tr>
                <tr>
                  <td width="82" height="22" align="right">&nbsp;</td>
                  <td >&nbsp;</td>
                  <td >&nbsp;</td>
                  <td >&nbsp;</td>
                  <td >&nbsp;</td>
                  <td >&nbsp;</td>
                  <td >&nbsp;</td>
                </tr>
                <tr>
                  <td height="22" align="right"> Cotizaci&oacute;n </td>
                  <td width="138" ><label> </label>
                      <div align="right">Prefijo</div></td>
                  <td width="27" ><label>
                    <input name="txtprecot" type="text" id="txtprecot" value="<?php print $ls_precot?>" size="4" maxlength="3" readonly>
                  </label></td>
                  <td width="42" ><div align="right">Serie</div></td>
                  <td width="34" ><label>
                    <input name="txtsercot" type="text" id="txtsercot" value="<?php print $ls_sercot ?>" size="5" maxlength="5">
                  </label></td>
                  <td width="58" ><div align="right">Correlativo</div></td>
                  <td width="169" ><input name="txtconcotusu" type="text" id="txtconcotusu" size="30" maxlength="30" value="<?php print $ls_concotusu?>"></td>
                </tr>
                <tr>
                  <td height="22" align="right">Factura </td>
                  <td ><div align="right">Prefijo</div></td>
                  <td ><label>
                    <input name="txtprefac" type="text" id="txtprefac" value="<?php print $ls_prefac ?>" size="4" maxlength="3" readonly>
                  </label></td>
                  <td ><div align="right">Serie</div></td>
                  <td ><label>
                    <input name="txtserfac" type="text" id="txtserfac" value="<?php print $ls_serfac ?>" size="5" maxlength="5">
                  </label></td>
                  <td ><div align="right">Correlativo</div></td>
                  <td ><input name="txtconfacusu" type="text" id="txtconfacusu" size="30" maxlength="30" value="<?php print $ls_confacusu?>"></td>
                </tr>
				<tr>
				  <td height="22" align="right">Orden de Entrega </td>
				  <td ><div align="right">Prefijo</div></td>
				  <td ><label>
                    <input name="txtpreord" type="text" id="txtpreord" value="<?php print $ls_preordent ?>" size="4" maxlength="3" readonly>
                  </label></td>
				  <td ><div align="right">Serie</div></td>
				  <td ><label>
                    <input name="txtserord" type="text" id="txtserord" value="<?php print $ls_serordent ?>" size="5" maxlength="5" >
                  </label></td>
				  <td ><div align="right">Correlativo</div></td>
				  <td ><input name="txtconordusu" type="text" id="txtconordusu" size="30" maxlength="30" value="<?php print $ls_conordusu?>"></td>
			    </tr>
				<tr>
                  <td height="22" align="right">Cobro </td>
                  <td ><div align="right">Prefijo</div></td>
                  <td ><label>
                    <input name="txtprecob" type="text" id="txtprecob" value="<?php print $ls_precob ?>" size="4" maxlength="3" readonly>
                  </label></td>
                  <td ><div align="right">Serie</div></td>
                  <td ><label>
                    <input name="txtsercob" type="text" id="txtsercob" value="<?php print $ls_sercob ?>" size="5" maxlength="5" >
                  </label></td>
                  <td ><div align="right">Correlativo</div></td>
                  <td ><input name="txtconcobusu" type="text" id="txtconcobusu" size="30" maxlength="30" value="<?php print $ls_concobusu?>"></td>
                </tr>
                <tr>
                  <td height="22" align="right"> Devoluci&oacute;n </td>
                  <td ><div align="right">Prefijo</div></td>
                  <td ><label>
                    <input name="txtpredev" type="text" id="txtpredev" value="<?php print $ls_predev ?>" size="4" maxlength="3" readonly>
                  </label></td>
                  <td ><div align="right">Serie</div></td>
                  <td ><label>
                    <input name="txtserdev" type="text" id="txtserdev" value="<?php print $ls_serdev ?>" size="5" maxlength="5">
                  </label></td>
                  <td ><div align="right">Correlativo</div></td>
                  <td ><input name="txtcondevusu" type="text" id="txtcondevusu" size="30" maxlength="30" value="<?php print $ls_condevusu?>"></td>
                </tr>

				 <tr>
                  <td height="22" align="right"> Pedido </td>
                  <td ><div align="right">Prefijo</div></td>
                  <td ><label>
                    <input name="txtpreped" type="text" id="txtpreped" value="<?php print $ls_preped ?>" size="4" maxlength="3" readonly>
                  </label></td>
                  <td ><div align="right">Serie</div></td>
                  <td ><label>
                    <input name="txtserped" type="text" id="txtserped" value="<?php print $ls_serped ?>" size="5" maxlength="5">
                  </label></td>
                  <td ><div align="right">Correlativo</div></td>
                  <td ><input name="txtconpedusu" type="text" id="txtconpedusu" size="30" maxlength="30" value="<?php print $ls_conpedusu?>"></td>
                </tr>

                <tr>
                  <td height="22" align="right"> Notas </td>
                  <td >&nbsp;</td>
                  <td >&nbsp;</td>
                  <td ><div align="right">Serie</div></td>
                  <td ><label>
                    <input name="txtsernot" type="text" id="txtsernot" value="<?php print $ls_sernot ?>" size="5" maxlength="5">
                  </label></td>
                  <td ><div align="right">Correlativo</div></td>
                  <td ><input name="txtconnotusu" type="text" id="txtconnotusu" size="30" maxlength="30" value="<?php print $ls_connotusu?>"></td>
                </tr>

			  <!--Datos del N&uacute;mero de Control para las formas Libres-->
                <tr>
                  <td height="21" align="right">Forma Libre				  </td>
                  <td colspan="6">
				   <label>
				  <?php

				if($ls_formalibre=="S")

					{
					?>


				   <input name="opcformlib" type="radio" value="N" onClick="actualizar_option()">
				                    No                </label>
								 <label>
			       <input name="opcformlib" type="radio" value="S"  checked="checked"  onClick="actualizar_option()" >
			        Si                 </label>

				  	<?php
					}
					elseif($ls_formalibre=="N")
					{
					?>

                    <input name="opcformlib" type="radio" value="N"  checked="checked"  onClick="actualizar_option()">
					No                </label>
								 <label>
			       <input name="opcformlib" type="radio" value="S"  onClick="actualizar_option()" >
			        Si                 </label>


					<?php
					 }
					elseif($ls_formalibre=="")
					{
					?>
					 <input name="opcformlib" type="radio" value="N"  onClick="actualizar_option()">
					No                </label>
								 <label>
			       <input name="opcformlib" type="radio" value="S"  onClick="actualizar_option()" >
			        Si                 </label>


					<?php
					}
					 ?>				  </td>
                  <td >&nbsp;</td>
                </tr>
               	<tr>
				 	 <td width="89" align="right" >Control </td>
					  <td >&nbsp;</td>
					  <td >&nbsp;</td>
					  <td ><div align="right" >Serie</div></td>
					  <td ><label>
						<input   name="txtsercon" type="text" id="txtsercon" value="<?php print $ls_sercon ?>" size="5" maxlength="5">
					  </label></td>
					  <td ><div align="right">Correlativo</div></td>
					  <td ><input   name="txtconconusu" type="text" id="txtconconusu" size="30" maxlength="30" value="<?php print $ls_conconusu;?>"></td>
                </tr>
			    <tr>
                  <td height="22" align="right">&nbsp;</td>
                  <td colspan="6" >&nbsp;</td>
                </tr>

			    <tr>
                  <td height="8">&nbsp;</td>
                  <td colspan="6">&nbsp;</td>
                </tr>
              </table></td>
            </tr>
		  </table>
		</td>
      </tr>
  </table>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
</form>
</body>

<script language="JavaScript">


/*******************************************************************************************************************************/
function actualizar_option()
{
			f=document.form1;
			f.operacion.value="ue_actualizar_option";
			f.action="sigesp_sfc_d_caja.php";
			f.submit();

}

function ue_formalibre()
{

  f=document.form1;


  if(f.opcformlib[1].checked)
	{
		window.open("sigesp_catdinamic_ordenes.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");

		 f.action="sigesp_sfc_d_caja.php";
		 f.submit();
	}

}

function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{
	f.operacion.value="ue_nuevo";
	f.txtcodcaja.value="";
	f.txtdesccaja.value="";
	f.action="sigesp_sfc_d_caja.php";
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
	lb_status=f.hidstatusseg.value;
	if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
	{
	if (lb_status!="C")
		{
		f.hidstatus.value="C";
		}
	with(f)
		{
			if (ue_valida_null(txtcodcaja,"Caja")==false) //revisar "Usuario=?
			{
				txtcodcaja.focus();
			}
			else
			{
				if (ue_valida_null(txtdesccaja,"Descripcion")==false) //revisar "TiENDA=?
				{
				  txtdesccaja.focus();
				}
				else
				{
					if (ue_valida_null(txtconcotusu,"Correlativo Cotización")==false) //revisar "Usuario=?
					{
						txtconcotusu.focus();
					}
					else
					{
						if (ue_valida_null(txtconfacusu,"Correlativo Factura")==false) //revisar "Usuario=?
						{
							txtconfacusu.focus();
						}
						else
						{
							if (ue_valida_null(txtconordusu,"Correlativo Orden de Entrega")==false) //revisar "Usuario=?
							{
								txtconordusu.focus();
							}
							else
							{	
								if (ue_valida_null(txtconcobusu,"Correlativo Cobros")==false) //revisar "Usuario=?
								{
									txtconcobusu.focus();
								}
								else
								{	
									if (ue_valida_null(txtconpedusu,"Correlativo Pedidos")==false) //revisar "Usuario=?
									{
										txtconpedusu.focus();
									}
									else
									{
										if (ue_valida_null(txtcondevusu,"Correlativo Devoluciones")==false) //revisar "Usuario=?
										{
											txtcondevusu.focus();
										}
										else
										{	
											if (ue_valida_null(txtconnotusu,"Correlativo Notas")==false) //revisar "Usuario=?
											{
												txtconnotusu.focus();
											}
											else
											{	
												f.hidopcformlib.value=getRadioButton();
												f.operacion.value="ue_guardar";
												f.action="sigesp_sfc_d_caja.php";
												f.submit();
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
		with(f)
		{
			if (ue_valida_null(txtcodcaja,"Caja")==false)
			{
				txtcodcaja.focus();
			}
			else
			{
				if (confirm("Esta seguro de eliminar este registro ?"))
				{
					f.operacion.value="ue_eliminar";
					f.action="sigesp_sfc_d_caja.php";
					f.submit();
				}
				else
				{
					f=document.form1;
					f.action="sigesp_sfc_d_caja.php";
					alert("Eliminacion Cancelada !!!");
					f.txtcodcaja.value="";
					f.txtdesccaja.value="";
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
	pagina="sigesp_cat_caja.php";
	popupWin(pagina,"catalogo",650,300);
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}



		function ue_cargarcaja(codtienda,ls_destienda,codcaja,desccaja,precot,prefac,predev,preped,sercot,serfac,serdev,serped,sernot,sercon,formalibre,precob,sercob,preordent,serordent)
		{
		    f=document.form1;
			f.txtcodtienda.value=codtienda;
			f.txttienda.value=ls_destienda;
			f.txtcodcaja.value=codcaja;
			f.txtdesccaja.value=desccaja;
			f.txtprecot.value=precot;
			f.txtprefac.value=prefac;
			f.txtpredev.value=predev;
			f.txtpreped.value=preped;
			f.txtsercot.value=sercot;
			f.txtserfac.value=serfac;
			f.txtserdev.value=serdev;
			f.txtserped.value=serped;
			f.txtsernot.value=sernot;
			f.hidstatusseg.value="C";
			if (sercon!='')
			{
			f.txtsercon.value=sercon;
			}
			f.hidstatus.value="1";
			f.hidopcformlib.value=formalibre;

			f.txtprecob.value=precob;
			f.txtsercob.value=sercob;
			f.txtpreord.value=preordent;
			f.txtserord.value=serordent;
			f.operacion.value="ue_cargar";
			//alert (formalibre);
			f.submit();

       	}




	function ue_buscartienda()
		{
            f=document.form1;

			f.operacion.value="";
			pagina="sigesp_cat_tienda.php";
			popupWin(pagina,"catalogo_tiendas",600,250);




		}

/***********************************************************************************************************************************/

		function ue_cargartienda(codtie,nomtie,dirtie,teltie,riftie,codpai,codest,codmun,codpar,items,spi_cuenta,denominacion,codunidad,denunidad,cuentapre,denpre,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5)
		{
			f=document.form1;

			f.txtcodtienda.value=codtie;
            f.txttienda.value=nomtie;


		}
/***********************************************************************************************************************************/

 function getRadioButton()
 {
   for (i=0; i < document.form1.opcformlib.length; i++)
    {
     if (document.form1.opcformlib[i].checked)
	  {
       valor=document.form1.opcformlib[i].value;

	  }

   }

  return valor;
 }



</script>
</html>