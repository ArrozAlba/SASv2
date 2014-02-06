<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
$ls_logusr=$_SESSION["la_logusr"];
require_once("class_funciones_banco.php");
$io_fun_banco= new class_funciones_banco();
$io_fun_banco->uf_load_seguridad("SCB","sigesp_scb_p_emision_chq.php",$ls_permisos,$la_seguridad,$la_permisos);
$ls_reporte = $io_fun_banco->uf_select_config("SCB","REPORTE","CHEQUE_VOUCHER","sigesp_scb_rpp_voucher_man_agr_pdf.php","C");//print $ls_reporte;
$li_diasem  = date('w');
switch ($li_diasem){
  case '0': $ls_diasem='Domingo';
  break; 
  case '1': $ls_diasem='Lunes';
  break;
  case '2': $ls_diasem='Martes';
  break;
  case '3': $ls_diasem='Mi&eacute;rcoles';
  break;
  case '4': $ls_diasem='Jueves';
  break;
  case '5': $ls_diasem='Viernes';
  break;
  case '6': $ls_diasem='S&aacute;bado';
  break;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Emisi&oacute;n de Cheques</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/general.css"  rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css"   rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/valida_fecha.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_scb.js"></script>
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:hover {
	color: #006699;
}
a:active {
	color: #006699;
}
.Estilo1 {color: #6699CC}
-->
</style></head>
<body>
<span class="toolbar"><a name="00"></a></span>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
  <td height="20" colspan="12" bgcolor="#E7E7E7">
    <table width="778" border="0" align="center" cellpadding="0" cellspacing="0">			
      <td width="430" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Caja y Banco</td>
	  <td width="350" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
	  <tr>
	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	<td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="22" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" title="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Guardar" title="Guardar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript:ue_imprimir();"><img src="../shared/imagebank/tools20/imprimir.gif" width="20" height="20" border="0" alt="Imprimir" title="Imprimir"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" title="Ayuda" width="20" height="20"></td>
    <td class="toolbar" width="668">&nbsp;</td>
  </tr>
</table>
<?php
require_once("sigesp_scb_c_emision_chq.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/grid_param.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/ddlb_conceptos.php");
require_once("../shared/class_folder/class_funciones.php");	
require_once("class_folder/sigesp_scb_c_disponibilidad_financiera.php");
	
$io_msg		= new class_mensajes();	
$io_funcion = new class_funciones();	
$io_include	= new sigesp_include();
$ls_conect	= $io_include->uf_conectar();
$obj_con	= new ddlb_conceptos($ls_conect);
$io_grid	= new grid_param();
$io_emiche  = new sigesp_scb_c_emision_chq();
$io_update 	= new class_funciones_banco();	
$ls_codemp 	= $_SESSION["la_empresa"]["codemp"];
$io_disfin    = new sigesp_scb_c_disponibilidad_financiera("../");
$ls_tipvaldis = $io_disfin->uf_load_tipo_validacion();
$li_estciespg = $io_fun_banco->uf_load_estatus_cierre($li_estciespi,$li_estciescg);

	require_once("sigesp_scb_c_movbanco.php");
	$in_classmovbco=new sigesp_scb_c_movbanco($la_seguridad);
	if( array_key_exists("operacion",$_POST))
	{
		$ls_operacion= $_POST["operacion"];
		$ls_estdoc=$_POST["status_doc"]; 
		$ls_mov_operacion="CH";
		$ls_numdoc=$_POST["txtdocumento"];
		$ls_codban=$_POST["txtcodban"];
		$ls_denban=$_POST["txtdenban"];
		$ls_ctaban=$_POST["txtcuenta"];
		$ls_dencuenta_banco=$_POST["txtdenominacion"];
		$ls_provbene=$_POST["txtprovbene"];
		$ls_desproben=$_POST["txtdesproben"];
		
		if (array_key_exists("rb_provbene",$_POST))
		   {
		     $ls_tipproben=$_POST["rb_provbene"];   
		   }
		else
		   {
		     $ls_tipproben = '-';
		   }
		   
		if  (array_key_exists("rb_voucher",$_POST))
		{
			$ls_tipobanco = $_POST["rb_voucher"];		 	 
		}
		else
		{
			$ls_tipobanco = "-"; // Tiene dos eventos INSERT o UPDATE
		}		   
   
		$ls_chevau		 = $_POST["txtchevau"];
		$ldec_montomov   = $_POST["totalchq"];
		$ldec_monobjret  = $_POST["txtmonobjret"];
		$ldec_montoret   = $_POST["txtretenido"];
		$ldec_montomov   = str_replace(".","",$ldec_montomov);
		$ldec_montomov   = str_replace(",",".",$ldec_montomov);
		$ldec_monobjret  = str_replace(".","",$ldec_monobjret);
		$ldec_monobjret  = str_replace(",",".",$ldec_monobjret);
		$ldec_montoret   = str_replace(".","",$ldec_montoret);
		$ldec_montoret   = str_replace(",",".",$ldec_montoret);
		$ls_estmov		 = $_POST["estmov"];		
		$ls_codconmov    = $_POST["codconmov"];
		$ls_desmov		 = $_POST["txtconcepto"];
		$ls_cuenta_scg   = $_POST["txtcuenta_scg"];
		$ldec_disponible = $_POST["txtdisponible"];	
		$ld_fecha		 = $_POST["txtfecha"];
		$ls_numchequera	 = $_POST["txtchequera"];
		$ls_fuente		 = $_POST["fuente"];
		$ls_lectura      = $_POST["txtlectura"];
		$li_totfilsel    = $_POST["hidtotfilsel"];
		$ls_codtipfon    = $_POST["hidcodtipfon"];
		$ls_numordpagmin = $_POST["txtnumordpagmin"];
		$ld_monmaxmov    = $_POST["hidmonmaxmov"];		
	}
	else
	{
	    $ls_operacion= "NUEVO" ;
		$ls_estdoc="N";	
		$ls_estmov="N";	
		$ls_numchequera="";
		$ls_anticipo= 'style="display:none"';	
		//    Validando si las retenciones municipales se deben hacer por Banco o por Cuentas por pagar
		require_once("sigesp_scb_c_config.php");
		$in_classconfig=new sigesp_scb_c_config($la_seguridad);
		$ls_fuente=$in_classconfig->uf_select_fuente();	
		$ls_lectura="";
	}	
	$li_row=0;
	$li_rows_spg=0;
	$li_rows_ret=0;
	$li_rows_spi=0;
	$li_totfilsel = 0;

$ls_disabled = "";

if ($li_estciescg==1)
   {
	 $ls_disabled = "disabled";		 
   }
if (($li_estciespg==1 || $li_estciespi==1) && $li_estciescg==0 && $ls_operacion=="NUEVO")
   {
	 $io_msg->message("Ya fué procesado el Cierre Presupuestario, sólo serán cargadas Programaciones de Pago asociadas a Recepciones de Documentos netamente Contables !!!");	   
   }
elseif($li_estciespg==1 && $li_estciespi==1 && $li_estciescg==1 && $ls_operacion=="NUEVO")
   {
     $io_msg->message("Ya fué procesado el Cierre Contable, No pueden efectuarse movimientos, Contacte al Administrador del Sistema !!!");
   }
	
if ($ls_operacion=="VERIFICAR_VAUCHER")
   {
     $ls_chevaux = $_POST["txtchevau"];
	 $lb_existe  = $in_classmovbco->uf_select_voucher($ls_chevaux);
	 if ($lb_existe)
		{
		  $io_msg->message("Nº de Voucher ya existe, favor indicar otro");	
		  $ls_chevau="";		
		}
	 $ls_operacion="CARGAR_DT";
   }
	
if ($ls_operacion=="CARGAR_DT")
   {
     
	 $io_emiche->uf_cargar_programaciones($ls_tipproben,$ls_provbene,$ls_codban,$ls_ctaban,$object,$li_rows,$ls_desmov,$ls_numordpagmin,$ls_codtipfon);
	 if ($li_rows>0 && !empty($ls_codban) && !empty($ls_ctaban))
		{
		  if ($_SESSION["la_empresa"]["confi_ch"]==1)
		     {
			   $ls_codusu = $_SESSION["la_logusr"];
			   $ls_numdoc = $io_update->uf_select_cheques($ls_codban,$ls_ctaban,$ls_codusu,&$ls_numchequera);
			   if (!empty($ls_numdoc))
				  {			      
				    $ls_lectura   = "readonly";			 
				  }
			   else
				  {
				    $io_msg->message("No tiene Chequera asociada !!!");
				    $ls_numdoc="";
				  } 			
			 }
		}
	  //---------para buscar pagos de anticipos para los proveedores o beneficiarios----------------------------
	    $monsal=0;
		if ($ls_tipproben=="P")
		{
		   $cod_pro=$ls_provbene;
		   $ced_bene="----------";
		}
		else
		{
			$cod_pro="----------";
		    $ced_bene=$ls_provbene;
		}		
	    $io_emiche->uf_buscar_anticipos($cod_pro, $ced_bene, &$monsal);
		if ($monsal>0)
		{
			$ls_anticipo= 'style="display:compact"';
		}
		else
		{
			$ls_anticipo= 'style="display:none"';
		}
	  //--------------------------------------------------------------------------------------------------------
   }
	
	function uf_nuevo($ls_disabled)
	{
		global $li_totfilsel,$ls_numordpagmin,$ls_codtipfon,$ld_monmaxmov;
		$li_totfilsel = 0;
		global $ls_mov_operacion;
		global $ls_estdoc;
		$ls_estdoc="N";	
		$ls_mov_operacion="CH";
	    global $la_seguridad;
		global $ls_opepre;
		$ls_opepre="";
		global $ls_numdoc;
		$ls_numdoc="";
		global $ls_codban;
		$ls_codban="";
		global $ls_denban;
		$ls_denban="";
		global $ls_estmov;
		$ls_estmov="N";
		global $ls_ctaban;
		$ls_ctaban="";
		global $ls_dencuenta_banco;
		$ls_dencuenta_banco="";	
		global $ls_provbene;
		$ls_provbene="----------";
		global $ls_desproben;
		$ls_desproben="Ninguno";
		global $ls_tipproben;
		$ls_tipproben="-";
		global $ls_chevau;
		require_once("sigesp_scb_c_movbanco.php");
		$in_classmovbanco=new sigesp_scb_c_movbanco($la_seguridad);
		global $ls_empresa;
		global $ldec_disponible;	
		$ldec_disponible="";	
		$ls_chevau = $in_classmovbanco->uf_generar_voucher($ls_empresa);
		$array_fecha=getdate();
		$ls_dia=$array_fecha["mday"];
		$ls_mes=$array_fecha["mon"];
		$ls_ano=$array_fecha["year"];
		global $ld_fecha;
		global $io_funcion;
		$ld_fecha=$io_funcion->uf_cerosizquierda($ls_dia,2)."/".$io_funcion->uf_cerosizquierda($ls_mes,2)."/".$ls_ano;
		global $ldec_montomov;
		$ldec_montomov="";
		global $ldec_monobjret;
		$ldec_monobjret="";
		global $ldec_montoret;
		$ldec_montoret="";
		global $ls_codconmov;
		$ls_codconmov='---';
		global $ls_desmov;
		$ls_desmov="";
		global $ls_cuenta_scg;
		$ls_cuenta_scg="";
		global $li_rows;
		global $li_temp;
		global $object;
		global $ld_fecha;
		$ls_numordpagmin = $ls_codtipfon = "";		
		$ld_monmaxmov = 0;
		if (array_key_exists("la_deducciones",$_SESSION))
		   {
		     unset($_SESSION["la_deducciones"]);
		   }
		$li_temp=1;	
		$li_rows=$li_temp;
		$ld_fecha=date("d/m/Y");
		$object[$li_temp][1]  = "<input name=chk".$li_temp." type=checkbox id=chk".$li_temp." value=1 class=sin-borde onClick='return false;' $ls_disabled>";
		$object[$li_temp][2]  = "<input type=text name=txtnumsol".$li_temp." value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
		$object[$li_temp][3]  = "<input type=text name=txtconsol".$li_temp." value='' class=sin-borde readonly style=text-align:left size=45 maxlength=254>";
		$object[$li_temp][4]  = "<input type=text name=txtmonsol".$li_temp." value='".number_format(0,2,",",".")."' class=sin-borde readonly style=text-align:right size=18 maxlength=18>";
		$object[$li_temp][5]  = "<input type=text name=txtmontopendiente".$li_temp."  value='".number_format(0,2,",",".")."' class=sin-borde readonly style=text-align:right size=18 maxlength=18>";				
		$object[$li_temp][6]  = "<input type=text name=txtmonto".$li_temp."  value='".number_format(0,2,",",".")."' class=sin-borde onBlur=javascript:uf_actualizar_monto(".$li_temp."); style=text-align:right size=18 maxlength=18>";				
	}

	$title[1]="";   $title[2]="Solicitud";    $title[3]="Concepto Solicitud";   $title[4]="Monto Solicitud"; $title[5]="Monto Pendiente";  $title[6]="Monto a Pagar";  
	$grid="grid";	
 	
if ($ls_operacion == "NUEVO")
   {
     $ls_operacion= "" ;
	 $ls_estmov="N";	
	 $ls_numchequera="";	
  	 uf_nuevo($ls_disabled);
	$ls_anticipo= 'style="display:none"';	
   }

	if($ls_operacion=="GUARDAR")
	{		
		$li_cont = 0;
		if($ls_tipproben=='P')
		{
			$ls_codpro=$ls_provbene;
			$ls_cedbene="----------";
		}
		else
		{
			$ls_codpro="----------";
			$ls_cedbene=$ls_provbene;
		}
		require_once("sigesp_scb_c_movbanco.php");
		$in_classmovbanco=new sigesp_scb_c_movbanco($la_seguridad);
	    $ls_estretiva = $_SESSION["la_empresa"]["estretiva"];//Indica si las Retenciones IVA se aplican por Cuentas por Pagar o Banco.		
		$li_totfilsel = $_POST["hidtotfilsel"];
		$li_totalRows = $_POST["totalrows"];
		$ls_clactacon = $_SESSION["la_empresa"]["clactacon"];
		$arr_movbco["codban"]		= $ls_codban;
		$arr_movbco["ctaban"]	    = $ls_ctaban;
		$arr_movbco["mov_document"] = $ls_numdoc;
		$ld_fecdb				    = $io_funcion->uf_convertirdatetobd($ld_fecha);
		$arr_movbco["codope"]       = $ls_mov_operacion;
		$arr_movbco["fecha"]        = $ld_fecha;
		$arr_movbco["codpro"]       = $ls_codpro;
		$arr_movbco["cedbene"]      = $ls_cedbene;
		$arr_movbco["monto_mov"]    = $ldec_montomov;
		$arr_movbco["objret"]       = $ldec_monobjret;
		$arr_movbco["retenido"]     = $ldec_montoret;
		$arr_movbco["estmov"]       = $ls_estmov;
		$ls_codfuefin = $_POST["txtcodfuefin1"];
		$ls_codemp    = $_SESSION["la_empresa"]["codemp"];
		$ls_modageret = $_SESSION["la_empresa"]["modageret"];
		$ldec_montoretbanco=$ldec_montoret;
		$lb_valido    = $in_classmovbanco->uf_guardar_automatico($ls_codban,$ls_ctaban,$ls_numdoc,$ls_mov_operacion,$ld_fecha,$ls_desmov,$ls_codconmov,$ls_codpro,$ls_cedbene,$ls_desproben,$ldec_montomov,$ldec_monobjret,$ldec_montoret,$ls_chevau,$ls_estmov,0,1,$ls_tipproben,'SCBBCH','',$ls_estdoc,$ls_tipproben,$ls_codfuefin,$ls_numordpagmin,$ls_codtipfon,0);
		if ($lb_valido)
		   {
			 $lb_valido = $io_emiche->uf_actualizar_estatus_ch($ls_codban,$ls_ctaban,$ls_numdoc,$ls_numchequera);
			 if (!$lb_valido)
			    {
				  $io_msg->message($io_emiche->is_msg_error);
			    }			
		   }
		$lb_pago=false;
		if ($lb_valido)
		   {
		     for ($li_i=1;$li_i<=$li_totalRows;$li_i++)				
			     {
				   if (array_key_exists("chk".$li_i,$_POST))
				      {
					    $li_cont++;
						$lb_pago			 = true;					
					    $ld_montotret 		 = 0;
						$ls_numsol   		 = $_POST["txtnumsol".$li_i];
					    $ldec_monsol 		 = $_POST["txtmonsol".$li_i];
					    $ldec_monsol 		 = str_replace(".","",$ldec_monsol);
					    $ldec_monsol		 = str_replace(",",".",$ldec_monsol);
						$ldec_montopendiente = $_POST["txtmontopendiente".$li_i];
						$ldec_montopendiente = str_replace(".","",$ldec_montopendiente);
						$ldec_montopendiente = str_replace(",",".",$ldec_montopendiente);
						$ldec_monto			 = $_POST["txtmonto".$li_i];
						$ldec_monto			 = str_replace(".","",$ldec_monto);
						$ldec_monto			 = str_replace(",",".",$ldec_monto);
						$ls_codfuefin		 = $_POST["txtcodfuefin".$li_i];
						if ($ldec_montopendiente==$ldec_monto)
					       {
						     $ls_estsol='C';	//Cancelado							
						   }
					    else
					       {
						     $ls_estsol='P';//Programado
					       }
				 	    $lb_valido = $in_classmovbanco->uf_check_insert_fuentefinancimiento($ls_codemp,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_mov_operacion,$ls_estmov,$ls_codfuefin);
					    if ($lb_valido)
					       {													
						     $lb_valido=$io_emiche->uf_procesar_emision_chq($ls_codban,$ls_ctaban,$ls_numdoc,$ls_mov_operacion,$ls_numsol,$ls_estmov,$ldec_monto,$ls_estsol);
					       }
					    if ($lb_valido)
					       {
					        if ($ls_clactacon==1)
							{
								$ls_ctaprovbene = $io_emiche->uf_select_ctacxpclasificador($ls_numsol,$ls_tipproben,$ls_provbene);
							}
							else
							{
								$ls_ctaprovbene = $io_emiche->uf_select_ctaprovbene($ls_tipproben,$ls_provbene,&$as_codban,&$as_ctaban);
						    }
							if ($ls_estretiva=='B')//Retenciones aplicadas desde el Módulo de Cuentas Por Pagar y reflejadas en el Módulo Banco.
							    {
							      $ls_procede_doc = "CXPSOP";
								  $la_deducciones1 = $io_emiche->uf_load_retenciones_iva_cxp($ls_codemp,$ls_numsol);
								}
							 elseif($ls_estretiva=='C')//Retenciones aplicadas desde el Módulo de Cuentas Por Pagar.
							    {
								  $ls_procede_doc = "SCBBCH";
								  if (array_key_exists("la_deducciones",$_SESSION))
								     {
									   $la_deducciones1=$_SESSION["la_deducciones"];
								     }										
								} 
							 $li_total = 0;
							 if (!empty($la_deducciones1))
							    {
								  $li_total = count($la_deducciones1["codded"]);
							    }
							 if ($ls_modageret=="B")/// se realiza el calculo de la ret. municipal
							    {
							 	  $la_deducciones2=$_SESSION["la_deducciones"];
								  $li_total2 = count($la_deducciones2["Codded"]);
								  for ($j=1;$j<=$li_total2;$j++)
								      { 
								        if (array_key_exists("$j",$la_deducciones2["Codded"]))
									       {
											 $ls_ctascg1	 = trim($la_deducciones2["SC_Cuenta"][$j]);
											 $ls_dended1	 = $la_deducciones2["Dended"][$j];
											 $ls_codded1	 = $la_deducciones2["Codded"][$j];
											 $ldec_objret1   = $la_deducciones2["MonObjRet"][$j];
											 $ldec_montoret1 = $la_deducciones2["MonRet"][$j];										
											 $ld_montotret += $ldec_montoret1; 
											 if (!empty($ls_codded1))
											    {
												  $lb_valido=$in_classmovbanco->uf_procesar_dt_contable($arr_movbco,$ls_ctascg1,$ls_procede_doc,$ls_dended1,$ls_numsol,'H',$ldec_montoret1,$ldec_objret1,true,$ls_codded1);
											    }
										   }
								      }
							    }							 
							 for ($i=1;$i<=$li_total;$i++)
								 {
								   if (array_key_exists("$i",$la_deducciones1["codded"]))
									  {
									    $ls_ctascg	   = trim($la_deducciones1["sc_cuenta"][$i]);
									    $ls_dended	   = $la_deducciones1["dended"][$i];
										$ls_codded	   = $la_deducciones1["codded"][$i];
										$ldec_objret   = $la_deducciones1["monobjret"][$i];
										$ldec_montoret = $la_deducciones1["monret"][$i];										
										$ld_montotret += $ldec_montoret; 
										if ($ls_codded!="")
										   { 
										     $lb_valido=$in_classmovbanco->uf_procesar_dt_contable($arr_movbco,$ls_ctascg,$ls_procede_doc,$ls_dended,$ls_numsol,'H',$ldec_montoret,$ldec_objret,true,$ls_codded);
										   }
									  }
								 }
							 
							 if ($ls_estretiva=='B')
							    {
								  $ldec_montotot=($ldec_montomov-$ldec_montoretbanco);
								  //$ldec_montotot=$ldec_montomov;
							    }
							 elseif($ls_estretiva=='C')
							 {
								  if ($ls_modageret=="B")/// se realiza el calculo de la ret. municipal
							      {
									$ldec_montotot=$ldec_montomov-$ld_montotret;
								  }
								  else
								  {
								  	$ldec_montotot=$ldec_montomov;
								  }		
							 }
							 unset($la_deducciones1);
					       }
				        if ($lb_valido)
					       { 
					         $ldec_monto_spg=0;
						     $io_emiche->uf_buscar_dt_cxpspg($ls_numsol);
					 	     if (array_key_exists("codestpro1",$io_emiche->ds_sol->data))
						        {
						          $li_total_rows=$io_emiche->ds_sol->getRowCount("codestpro1");
						          for ($li_x=1;$li_x<=$li_total_rows;$li_x++)
									  {
									    $ldec_monto_aux=$io_emiche->ds_sol->getValue("monto",$li_x);
									    $ldec_monto_spg=$ldec_monto_spg + $ldec_monto_aux;
									  }										
								  $ldec_montospg2=0;
								  for ($li_y=1;$li_y<=$li_total_rows;$li_y++)
									  { 
									    $ldec_monto_aux=$io_emiche->ds_sol->getValue("monto",$li_y);
									    if ($lb_valido)
										   {							
										     if ($ls_estsol!="C")
											    {
												  $ldec_MontoSpgDet = round(round($ldec_monto_aux , 2 ) *($ldec_monto  / $ldec_monto_spg),2);
												  $ldec_montospg2= $ldec_montospg2 + $ldec_MontoSpgDet;
											    }
										     else
											    {
												  $ldec_MontoSpgDet =round($ldec_monto_aux,2);
												  $ldec_montospg2 = $ldec_montospg2 + $ldec_MontoSpgDet;
											    }												
										     if (($ldec_MontoSpgDet > $ldec_monto)&&($ls_estsol!="C"))
											    {												
												  $ldec_MontoSpgDet = $ldec_monto;
												  $ldec_montospg2   = $ldec_MontoSpgDet;
											    }
										     if (($ldec_montospg2 > $ldec_monto)&&($ls_estsol!="C"))
											    {
												  $ldec_MontoSpgDet = $ldec_MontoSpgDet - ($ldec_montospg2 - $ldec_monto);
											    }
										     if (($ldec_montospg2 < $ldec_monto)&&($li_y==$li_total_rows)&&($ldec_montospg2!=$ldec_monto_spg))
											    {
												  $ldec_MontoSpgDet = $ldec_MontoSpgDet + ($ldec_monto - $ldec_montospg2);
											    }
										     $ls_estcla      = $io_emiche->ds_sol->getValue("estcla",$li_y);
										     $ls_codestpro1  = $io_emiche->ds_sol->getValue("codestpro1",$li_y);
										     $ls_codestpro2  = $io_emiche->ds_sol->getValue("codestpro2",$li_y);
										     $ls_codestpro3  = $io_emiche->ds_sol->getValue("codestpro3",$li_y);
										     $ls_codestpro4  = $io_emiche->ds_sol->getValue("codestpro4",$li_y);
										     $ls_codestpro5  = $io_emiche->ds_sol->getValue("codestpro5",$li_y);			
										     $ls_programa    = $ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
										     $ls_cuentaspg   = $io_emiche->ds_sol->getValue("spg_cuenta",$li_y);
										     $ls_descripcion = $io_emiche->ds_sol->getValue("descripcion",$li_y);			
										     $lb_valido      = $in_classmovbanco->uf_procesar_dt_gasto($ls_codban,$ls_ctaban,$ls_numdoc,'CH',$ls_estmov,$ls_programa,$ls_cuentaspg,$ls_numsol,$ls_descripcion,'CXPSOP',$ldec_MontoSpgDet,'PG',$ls_estcla);										     
										 }
									  }  
							    }
						   }
						$lb_valido = $in_classmovbanco->uf_procesar_dt_contable($arr_movbco,$ls_ctaprovbene,'CXPSOP',$ls_desmov,$ls_numsol,'D',$ldec_monto+$ld_montotret-$ldec_montoretbanco,$ldec_monobjret,true,'00000');//Se comenta la suma por desconocer bajo que circunstancias se deben sumar las retenciones. Agregada resta de retenciones bancarias.
					    if ($lb_valido && $li_cont==$li_totfilsel)
						   {
							 $lb_valido = $in_classmovbanco->uf_procesar_dt_contable($arr_movbco,$ls_cuenta_scg,'SCBBCH',$ls_desmov,$ls_numdoc,'H',$ldec_montotot,$ldec_monobjret,true,'00000');
						   }
				      }
		          }
		   }
		
		if ($lb_valido && $lb_pago)
		   {
			 $in_classmovbanco->io_sql->commit();
			 $io_msg->message("Movimiento registrado !!!");			
			 ?>
			 <script language="javascript">
			 ls_voucher = "<?php echo $ls_tipobanco; ?>";

			 if(ls_voucher=="V")
			 {
				ls_reporte="sigesp_scb_rpp_voucher_ven_pdf.php";
			 }
			 if(ls_voucher=="A")
			 {
				ls_reporte="sigesp_scb_rpp_voucher_man_agr_pdf.php";
			 }	
			 			 
			 if(ls_voucher=="P")
			 {
				ls_reporte="sigesp_scb_rpp_voucher_man_prov_pdf.php";
			 }	
			 ls_pagina="reportes/"+ls_reporte+"?codban=<?php print $ls_codban ?>&ctaban=<?php print $ls_ctaban ?>&numdoc=<?php print $ls_numdoc?>&chevau=<?php print $ls_chevau?>&codope=CH";
			 window.open(ls_pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=583,height=400,left=50,top=50,location=no,resizable=yes");
			 </script>
			 <?php 
		   }
		else
		{
			$in_classmovbanco->io_sql->rollback();
			if (!empty($in_classmovbanco->is_msg_error))
			   {
			     $io_msg->message($in_classmovbanco->is_msg_error."; No pudo registrarse el movimiento ");     
			   }
			else
			   {
			     $io_msg->message(" No pudo registrarse el movimiento ");
			   }
		}
		$ls_chevau="";
		uf_nuevo($ls_disabled);	
		$ls_anticipo= 'style="display:none"';			
	}
	
	if($ls_tipproben=='-')
	{
		$rb_n="checked";
		$rb_p="";
		$rb_b="";			
	}
	if($ls_tipproben=='P')
	{
		$rb_n="";
		$rb_p="checked";
		$rb_b="";			
	}
	if($ls_tipproben=='B')
	{
		$rb_n="";
		$rb_p="";
		$rb_b="checked";			
	}
	
	if($ls_tipobanco=='-')
	{
		$rb_bn="checked";
		$rb_bv="";
		$rb_ba="";			
	}
	if($ls_tipobanco=='V')
	{
		$rb_bn="";
		$rb_bv="checked";
		$rb_ba="";			
	}
	if($ls_tipobanco=='A')
	{
		$rb_bn="";
		$rb_bv="";
		$rb_ba="checked";			
	}
	if($ls_tipobanco=='P')
	{
		$rb_bn="";
		$rb_bv="";
		$rb_bbva="checked";			
	}
?>
  <form name="form1" method="post" action="" id="sigesp_scb_p_emision_chq.php">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_banco->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_banco);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <br>
  <table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-ventana">
      <td height="22" colspan="4"><input name="hidmesabi" type="hidden" id="hidmesabi" value="true">
      <input name="hidtotfilsel" type="hidden" id="hidtotfilsel" value="<?php echo $li_totfilsel; ?>">
        Emisi&oacute;n de Cheques      
      <input name="hidestciescg" type="hidden" id="hidestciescg" value="<?php echo $li_estciescg; ?>">
      <input name="hidestciespg" type="hidden" id="hidestciespg" value="<?php echo $li_estciespg; ?>">
      <input name="hidestciespi" type="hidden" id="hidestciespi" value="<?php echo $li_estciespi; ?>">
      <input name="hidcodtipfon" type="hidden" id="hidcodtipfon" value="<?php echo $ls_codtipfon; ?>">
      <input name="hiddentipfon" type="hidden" id="hiddentipfon">
      <input name="hidmonmaxmov" type="hidden" id="hidmonmaxmov" value="<?php echo $ld_monmaxmov; ?>"></td>
    </tr>
    <tr>
      <td height="13" colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="4"><div align="center">
          <input type="radio" name="rb_voucher" id="radio" value="V" border="1" checked <?php print $rb_p; echo $ls_disabled; ?> > 
        Banco de Venezuela 
        <input type="radio" name="rb_voucher" id="radio" value="A" border="1" <?php print $rb_ba; ?>> 
      Banco Agricola   
	  <input type="radio" name="rb_voucher" id="radio" value="P" border="1"     <?php print $rb_bbva; ?>>
Voucher Provincial</div></td>
    </tr>
    <tr>
      <td height="22">&nbsp;</td>
      <td height="22" colspan="3"><table width="263" border="0" align="left" cellpadding="0" cellspacing="0" bgcolor="#E2E2E2" class="formato-blanco">
        <tr>
          <td width="261"><div align="center">
              <label>
              <input name="rb_provbene" type="radio" class="sin-borde" id="radio" onClick="javascript:uf_verificar_provbene(this.checked,document.form1.tipo.value);" value="P" checked <?php print $rb_p;echo $ls_disabled; ?>>
                Proveedor</label>
              <label>
              <input type="radio" name="rb_provbene" id="radio" value="B" class="sin-borde" onClick="javascript:uf_verificar_provbene(this.checked,document.form1.tipo.value);" <?php print $rb_b;echo $ls_disabled; ?>>
                Beneficiario</label>
              <label> </label>
              <input name="tipo" type="hidden" id="tipo">
          </div></td>
        </tr>
      </table>
                                                                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No. Orden Pago Ministerio
        <input name="txtnumordpagmin" type="text" id="txtnumordpagmin" onKeyPress="return keyRestrict(event,'0123456789'); " value="<?php echo $ls_numordpagmin; ?>" size="20" maxlength="15" style="text-align:center" readonly>
      &nbsp;<a href="javascript:uf_catalogo_ordenes();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar Ordenes de Pago Ministerio..." width="15" height="15" border="0" title="Buscar Ordenes de Pago Ministerio..."></a></td>
    </tr>
    <tr>
      <td height="22"><div align="right">
          <input name="txttitprovbene" type="text" class="sin-borde" id="txttitprovbene" style="text-align:right" size="15" readonly>
      </div></td>
      <td height="22" colspan="3"><div align="left">
          <input name="txtprovbene" type="text" id="txtprovbene" style="text-align:center" value="<?php print $ls_provbene?>" size="24" readonly>
          <a href="javascript:catprovbene()"><img id="bot_provbene" src="../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Programaciones de Pago" title="Cat&aacute;logo Programaciones de Pago" width="15" height="15" border="0"></a>
          <input name="txtdesproben" type="text" id="txtdesproben" size="85" maxlength="250" class="sin-borde" value="<?php print $ls_desproben;?>"  readonly>
      </div></td>
    </tr>
    <tr >
      <td height="13" colspan="4">&nbsp;</td>
    </tr>
    <tr class="formato-azul" >
      <td height="22" colspan="4" style="text-align:center"><strong>Datos del Cheque</strong></td>
    </tr>
    <tr>
      <td height="13" style="text-align:right">&nbsp;</td>
      <td height="13" colspan="3" style="text-align:left">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Tipo Concepto</td>
      <td height="22" colspan="3" style="text-align:left"><?php $obj_con->uf_cargar_conceptos($ls_mov_operacion,$ls_codconmov);	?>
          <input name="codconmov" type="hidden" id="codconmov" value="<?php print $ls_codconmov;?>">      </td>
    </tr>
    
    <tr>
      <td width="115" height="22" style="text-align:right">Banco</td>
      <td height="22" style="text-align:left"><input name="txtcodban" type="text" id="txtcodban"  style="text-align:center" value="<?php print $ls_codban;?>" size="10" readonly>
          <a href="javascript:cat_bancos();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Bancos" title="Cat&aacute;logo de Bancos"></a>
          <input name="txtdenban" type="text" id="txtdenban" value="<?php print $ls_denban?>" size="40" class="sin-borde" readonly>      </td>
      <td height="22" style="text-align:right">Fecha</td>
      <td height="22" style="text-align:left"><input name="txtfecha" type="text" id="txtfecha" value="<?php print $ld_fecha;?>" style="text-align:center" <?php echo $ls_disabled; ?> datepicker="true" maxlength="10" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);uf_validar_estatus_mes();"></td>
    </tr>
      <script language="javascript">uf_validar_estatus_mes();</script>
	<tr>
      <td height="22" style="text-align:right">Cuenta</td>
      <td height="22" colspan="3" style="text-align:left">
          <input name="txtcuenta" type="text" id="txtcuenta" style="text-align:center" value="<?php print $ls_ctaban; ?>" size="30" maxlength="25" readonly>
          <a href="javascript:catalogo_cuentabanco();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Cuentas Bancarias" title="Cat&aacute;logo de Cuentas Bancarias"></a>
          <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion" style="text-align:left" value="<?php print $ls_dencuenta_banco; ?>" size="50" maxlength="254" readonly>
          <input name="txttipocuenta" type="hidden" id="txttipocuenta">
          <input name="txtdentipocuenta" type="hidden" id="txtdentipocuenta">      </td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Cuenta Contable</td>
      <td width="376" height="22" style="text-align:left"><input name="txtcuenta_scg" type="text" id="txtcuenta_scg" style="text-align:center" value="<?php print $ls_cuenta_scg;?>" size="24" readonly></td>
      <td width="85" height="22" style="text-align:right">Disponible</td>
      <td width="184" height="22" style="text-align:left"><input name="txtdisponible" type="text" id="txtdisponible" style="text-align:right" value="<?php print $ldec_disponible;?>" size="28" readonly></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Documento</td>
      <td height="22" style="text-align:left">
        <input name="txtdocumento" type="text" id="txtdocumento" value="<?php print $ls_numdoc;?>" size="24" maxlength="15" onBlur="javascript:rellenar_cad(this.value,15,'doc');" style="text-align:center" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+'-()[]{}#/');" <?php print $ls_lectura; echo $ls_disabled;?> >
        <?php
	     if ($_SESSION["la_empresa"]["confi_ch"]==0)
		    {
		?>
        <a href="javascript:cat_cheque();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar Chequera" width="15" height="15" border="0" ></a>
        <?php
		    }
		?>
        <input name="estmovld" type="hidden" id="estmovld" value="<?php print $ls_estmov;?>">
        <input name="txtchequera" type="hidden" id="txtchequera" value="<?php print $ls_numchequera;?>">
		<input name="txtlectura" type="hidden" id="txtlectura" value="<?php print $ls_lectura;?>">      </td>
      <td height="22" style="text-align:right">Voucher</td>
      <td height="22" style="text-align:left"><input name="txtchevau" type="text" id="txtchevau" size="28" maxlength="25" value="<?php print $ls_chevau;?>" style="text-align:center" <?php echo $ls_disabled; ?> onChange="javascript:ue_verificar_vaucher()" onBlur="javascript:rellenar_cad(this.value,25,'voucher');" onKeyPress="return keyRestrict(event,'1234567890');"></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Concepto</td>
      <td height="22" colspan="3"><input name="txtconcepto" type="text" id="txtconcepto"  onKeyPress="return keyRestrict(event,'0123456789'+'abcdefghijklmnopqrstuvwxyzñ .,*/-()$%&!ºªáéíóú[]{}<>')" value="<?php print $ls_desmov;?>" size="117" maxlength="254" <?php echo $ls_disabled; ?>></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Total</td>
      <td height="22"><input name="totalchq" type="text" id="totalchq" style="text-align:right" value="<?php print number_format($ldec_montomov,2,",",".");?>" size="24" readonly>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;          
        M.O.R 
          <input name="txtmonobjret" type="text" id="txtmonobjret" style="text-align:right" onBlur="javascript:uf_validar_monobjret(this);" onKeyPress="return(currencyFormat(this,'.',',',event))" value="<?php print  number_format($ldec_monobjret,2,",",".");?>" size="28" <?php echo $ls_disabled; ?>></td>
      <td height="22" style="text-align:right">Monto Retenido</td>
      <td height="22"><input name="txtretenido" type="text" id="txtretenido" style="text-align:right" value="<?php print number_format($ldec_montoret,2,",",".");?>" size="24" readonly>
      <a href="javascript:uf_cat_deducciones();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo de Deducciones" title="Cat&aacute;logo de Deducciones" width="15" height="15" border="0"></a></td>
    </tr>
    <tr>
      <td height="13">&nbsp;</td>
      <td height="13"></td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
    </tr>
    <tr>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
    </tr>
    <tr <? print $ls_anticipo ?>>
      <td height="13">&nbsp;</td>
      <td height="13" >
	      <input name="btnanticipo" type="button" class="boton" id="btnanticipo" value="Amortización Anticipo" onClick="ue_amortizar_anticipo();">	  </td>
      <td height="13" >&nbsp;</td>
      <td height="13" >&nbsp;</td>
    </tr>
    <tr>
      <td height="13">&nbsp;</td>
      <td height="13" >&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
    </tr>
	
    <tr>                                              
      <td height="22" colspan="4"><div align="center"><?php $io_grid->make_gridScroll($li_rows,$title,$object,762,'Solicitudes Programadas',$grid,100);?>
        <input name="fila_selected" type="hidden" id="fila_selected">
        <input name="totalrows" type="hidden" id="totalrows" value="<?php print $li_rows;?>">
        <input name="operacion" type="hidden" id="operacion">
		<input name="status_doc" type="hidden" id="status_doc" value="<?php print $ls_estdoc;?>">
        <input name="estmov" type="hidden" id="estmov" value="<?php print $ls_estmov;?>">
        <input name="fuente" type="hidden" id="fuente" value="<?php print $ls_fuente;?>">
      </div></td>
    </tr>
    <tr>
      <td height="22" colspan="4">&nbsp;</td>
    </tr>
  </table>
  <p>&nbsp;</p>
  </form>
</body>
<script language="javascript">
f=document.form1;
var patron = new Array(2,2,4);
function ue_nuevo()
{
  if (uf_evaluate_cierre('SCG'))
     {
       f.operacion.value ="NUEVO";
       f.action="sigesp_scb_p_emision_chq.php";
       f.submit();	 
	 }
}

function ue_guardar()
{
  lb_mesabi = f.hidmesabi.value;
  if (lb_mesabi=='true')
     {
	  if (uf_evaluate_cierre('SCG'))
		 {
		   ls_numdoc	 = f.txtdocumento.value;
		   ls_chevau	 = f.txtchevau.value;
		   ls_codban	 = f.txtcodban.value;
		   ls_ctaban     = f.txtcuenta.value;
		   ls_concepto	 = f.txtconcepto.value;
		   ldec_totalchq = f.totalchq.value;
		   ldec_totalchq = uf_convertir_monto(ldec_totalchq);
		   if ((ls_numdoc!="")&&(ls_codban!="")&&(ls_ctaban!="")&&(ls_concepto!="")&&(ldec_totalchq>0))
			  {
				ld_totmondis = f.txtdisponible.value;
				ls_tipvaldis = "<?php echo $ls_tipvaldis; ?>";
				lb_valido    = uf_validar_disponible("CH",ls_tipvaldis,ld_totmondis,f.totalchq.value);
				if (lb_valido)
				   {
					 f.operacion.value ="GUARDAR";
					 f.action="sigesp_scb_p_emision_chq.php";
					 f.submit();		 
				   }
			  }
		   else
			  {
				alert("Complete los datos del Movimiento !!!");
			  }
		 }	 
	 }
  else
     {
	   alert("Operación No puede ser procesada, El Més está Cerrado !!!");
	 }
}

function ue_imprimir()
{
	ls_numdoc=f.txtdocumento.value;
	ls_codope='CH';
	ls_codban=f.txtcodban.value;
	ls_ctaban=f.txtcuenta.value;	
	ls_chevau=f.txtchevau.value;	
	if((ls_numdoc!="")&&(ls_codban!="")&&(ls_ctaban!="")&&(ls_codope!=""))
	{
		if(f.rb_voucher[0].checked)
		{
			ls_voucher="V";
		}
		else
		if(f.rb_voucher[1].checked)
		{
			ls_voucher="A";
		}

        if(ls_voucher=="V")
		{
			ls_reporte="sigesp_scb_rpp_voucher_ven_pdf.php";
		}
		if(ls_voucher=="A")
		{
			ls_reporte="sigesp_scb_rpp_voucher_man_agr_pdf.php";
		}	
				
		ls_pagina="reportes/"+ls_reporte+"?codban="+ls_codban+"&ctaban="+ls_ctaban+"&numdoc="+ls_numdoc+"&chevau="+ls_chevau+"&codope="+ls_codope;
		window.open(ls_pagina,"catalogo","menubar=yes,toolbar=yes,scrollbars=yes,width=583,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
		alert("Seleccione un documento valido, o que ya este registrado");
	}
}

function rellenar_cad(cadena,longitud,campo)
{
	if(cadena!="")
	{
		var mystring=new String(cadena);
		cadena_ceros="";
		lencad=mystring.length;
	
		total=longitud-lencad;
		for(i=1;i<=total;i++)
		{
			cadena_ceros=cadena_ceros+"0";
		}
		cadena=cadena_ceros+cadena;
		if(campo=="doc")
		{
			document.form1.txtdocumento.value=cadena;
		}
		if(campo=="voucher")
		{
			document.form1.txtchevau.value=cadena;
		}
	}	
}	
	
//Catalogo de cuentas contables
function catalogo_cuentabanco()
{
   valor=f.ddlb_conceptos.value; 
  if (uf_evaluate_cierre('SCG'))
     {
	   ls_codban = f.txtcodban.value;
	   ls_denban = f.txtdenban.value;
	   if (ls_codban!="")
		  {
		    pagina="sigesp_cat_ctabanco2.php?codigo="+ls_codban+"&hidnomban="+ls_denban+"&codcon="+valor;
		    window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=650,height=400,resizable=yes,location=no");
		  }
	   else
		  {
		    alert("Seleccione el Banco !!!");   
		  }
     }
}
	 
function cat_bancos()
{
  valor=f.ddlb_conceptos.value; 
  if (uf_evaluate_cierre('SCG'))
     {
       pagina="sigesp_cat_bancos.php?codcon="+valor;
       window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
     }
}
   
function catprovbene()
{
  uf_validar_estatus_mes();
  if (uf_evaluate_cierre('SCG'))
     {
       if (f.rb_provbene[0].checked==true)
	      {
	        f.txtprovbene.disabled=false;	
	        window.open("sigesp_cat_prog_proveedores.php","Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=650,height=450,left=50,location=no,resizable=yes");
	      }
       else if(f.rb_provbene[1].checked==true)
	      {
	        f.txtprovbene.disabled=false;	
	        window.open("sigesp_cat_prog_beneficiario.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=650,height=450,left=50,location=no,resizable=yes");
	      }   
     }
}   

function uf_verificar_provbene(lb_checked,obj)
{
  if ((f.rb_provbene[0].checked)&&(obj!='P'))
	 {
	   f.tipo.value='P';
	   f.txtprovbene.value="";
	   f.txtdesproben.value="";
	   f.txttitprovbene.value="Proveedor";
	 }
  if ((f.rb_provbene[1].checked)&&(obj!='B'))
	 {
	   f.txtprovbene.value="";
	   f.txtdesproben.value="";
	   f.tipo.value='B';
	   f.txttitprovbene.value="Beneficiario";
	 }
}

function uf_selected(li_i)
{
  uf_validar_estatus_mes();  
  if (eval("f.chk"+li_i+".checked==true;"))
	 {
	   li_totfilsel = parseInt(f.hidtotfilsel.value)+1;
	 }
  else
	 {
	   li_totfilsel = parseInt(f.hidtotfilsel.value)-1;
	 }
  f.hidtotfilsel.value = li_totfilsel;
  f.fila_selected.value=li_i;
  ldec_monto = eval("f.txtmontopendiente"+li_i+".value");
  ls_numsol  = eval("f.txtnumsol"+li_i+".value");
  uf_calcular();
  update_concepto();
}

function uf_actualizar_monto(li_i)
{
	ldec_monto= eval("f.txtmonto"+li_i+".value");
	ldec_montopendiente= eval("f.txtmontopendiente"+li_i+".value");
	ldec_temp1=ldec_monto;
	ldec_temp2=ldec_montopendiente;
	while(ldec_temp1.indexOf('.')>0)
	{//Elimino todos los puntos o separadores de miles
		ldec_temp1=ldec_temp1.replace(".","");
	}
	ldec_temp1=ldec_temp1.replace(",",".");
	while(ldec_temp2.indexOf('.')>0)
	{//Elimino todos los puntos o separadores de miles
		ldec_temp2=ldec_temp2.replace(".","");
	}
	ldec_temp2=ldec_temp2.replace(",",".");

	if(parseFloat(ldec_temp1)<=parseFloat(ldec_temp2))
	{
		eval("f.txtmonto"+li_i+".value='"+uf_convertir(ldec_temp1)+"'");
	}
	else
	{
		alert("Monto a cancelar no puede ser mayor al monto pendiente");
		eval("f.txtmonto"+li_i+".value='"+ldec_montopendiente+"'");	
		eval("f.txtmonto"+li_i+".focus()");
	}
	uf_calcular();
}
	
function uf_calcular()
{
	li_total=f.totalrows.value;
	ldec_total=0;
	for(i=1;i<=li_total;i++)
	{
		if(eval("f.chk"+i+".checked"))
		{
			ldec_monto=eval("f.txtmonto"+i+".value");
			while(ldec_monto.indexOf('.')>0)
			{//Elimino todos los puntos o separadores de miles
				ldec_monto=ldec_monto.replace(".","");
			}
			ldec_monto=ldec_monto.replace(",",".");
			ldec_total=parseFloat(ldec_total)+parseFloat(ldec_monto);

		}
	}
	f.totalchq.value=uf_convertir(ldec_total);
	f.txtmonobjret.value=uf_convertir(ldec_total);
}
   
function uf_cat_deducciones() 
{
  if (uf_evaluate_cierre('SCG'))
     {
       if (f.fuente.value=="B")
          {
	        ls_documento=f.txtdocumento.value;
	        ldec_monto=f.totalchq.value;
	        ldec_monto=uf_convertir_monto(ldec_monto);//Lo convierto a decimal separado solo por punto( . )
	        ldec_monobjret=f.txtmonobjret.value;	
	        ls_origen="1";
	        ldec_monobjret=uf_convertir_monto(ldec_monobjret);//Lo convierto a decimal separado solo por punto( . )
	        if ((ls_documento!="")&&(ldec_monto>0)&&(ldec_monobjret>0)&&(ldec_monto>=ldec_monobjret))   
	           {
		         ldec_monto=uf_convertir(ldec_monto);//Lo convierto a formato decimal con separdores de miles y decimales
		         ldec_monobjret=uf_convertir(ldec_monobjret);//Lo convierto a formato decimal con separdores de miles y decimales
		         pagina="sigesp_cat_deducciones.php?monto="+ldec_monto+"&objret="+ldec_monobjret+"&txtdocumento="+ls_documento+"&origen="+ls_origen;
		         window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	           }
	        else
	           {
			     if (ls_documento=="")
			        {
				      alert("Introduzca un numero de documento");
			        }
			     else if(ldec_monto<=0)
			        {
				      alert("El monto de be ser mayor a cero(0)");
			        }
			     else if(ldec_monobjret<=0)
			        {
				      alert("La base imponible debe ser mayor a cero(0)");
			        }
			     else if(ldec_monto<ldec_monobjret)
			        {
				      alert("Base imponible no puede ser mayor al monto del documento");
				      f.txtmonobjret.value=uf_convertir(ldec_monto);
			        }
	           }
          }
       else
          {
		    alert("Las retenciones municipales deben aplicarse a través del módulo de Cuentas por Pagar");
          } 
     }
} 
	
function uf_validar_monobjret(txtmonobjret)
{
	ldec_monobjret=txtmonobjret.value;
	ldec_monto=f.totalchq.value;
	ldec_monobjret=uf_convertir_monto(ldec_monobjret);
	ldec_monto=uf_convertir_monto(ldec_monto);
	if(ldec_monto>=ldec_monobjret)
	{
		txtmonobjret.value=uf_convertir(ldec_monobjret);
	}
	else
	{
		txtmonobjret.value=uf_convertir(ldec_monto);
		alert("Monto Objeto a Retención no puede ser mayor al monto total del Cheque.");
		txtmonobjret.focus();
	}	
}
	
function ue_verificar_vaucher()
{
	f.operacion.value="VERIFICAR_VAUCHER";
	f.submit();
}

function update_concepto()
{
  li_totchk  = 0;
  li_totrows = f.totalrows.value;
  for (i=1;i<=li_totrows;i++)
      {
	    if (eval("f.chk"+i+".checked"))
		   {
		     li_totchk++;
		     ls_concepto = eval("f.txtconsol"+i+".value");
		   }
	  }
  if (li_totchk==1)
     {
	   f.txtconcepto.value = ls_concepto;
	 } 
  else
     {
       f.txtconcepto.value="";
	 }
}

function uf_evaluate_cierre(as_tipafe)
{
  lb_valido = true;
  if (as_tipafe=='SPG' || as_tipafe=='SPI')
     {
       li_estciespg = f.hidestciespg.value;
       li_estciespi = f.hidestciespi.value;
	   if (li_estciespg==1 || li_estciespi==1)
		  {
		    lb_valido = false;
		    alert("Ya fué procesado el Cierre Presupuestario, No pueden efectuarse movimientos, Contacte al Administrador del Sistema !!!");
		  }	   
	 }
  else
     {
	   if (as_tipafe=='SCG')
	      {
  		    li_estciescg = f.hidestciescg.value;
			if (li_estciescg==1)
			   {
			     lb_valido = false;
			     alert("Ya fué procesado el Cierre Contable, No pueden efectuarse movimientos, Contacte al Administrador del Sistema !!!");
			   }
		  }
	 }
  return lb_valido
}

function ue_amortizar_anticipo()
{
     sc_cuenta=f.txtcuenta_scg.value;
	 codban=f.txtcodban.value;
	 ctaban=f.txtcuenta.value;
	 provbene=f.txtprovbene.value;
	 if (f.rb_provbene[0].checked==true)
	 {
	 	tipproben="P"; 
	 }
	 else
	 {
	 	tipproben="B";
	 }
	 total_filas=f.totalrows.value; 
	 j=0;
	 for (i=1;i<=total_filas;i++)
	 {
	 	if (eval("f.chk"+i+".checked"))
		{
		   j++;
		   ls_montochq = f.totalchq.value;
		}
	 }// fin del for
	 documento=f.txtdocumento.value;
	 montret=f.txtretenido.value;
	 mov_operacion='CH';
	 fecha=f.txtfecha.value;
	 dencon=f.txtconcepto.value;
	 codmov=f.ddlb_conceptos.value;
	 desproben=f.txtdesproben.value;
	 montobjret=f.txtmonobjret.value;
	 chevau=f.txtchevau.value;
	 estmov=f.estmov.value;
	 fuente=f.txtcodfuefin1.value; 
	 
	 if ((j!=0)&&(documento!=""))
	 {	 
	 	window.open("sigesp_pdt_amortizacion_anticipo.php?provbene="+provbene+"&tipproben="+tipproben+"&montochq="+ls_montochq+"&sc_cuenta="+sc_cuenta+"&codban="+codban+"&ctaban="+ctaban+"&docum="+documento+"&montret="+montret+"&mov_operacion="+mov_operacion+"&fecha="+fecha+"&dencon="+dencon+"&codmov="+codmov+"&desproben="+desproben+"&montobjret="+montobjret+"&chevau="+chevau+"&estmov="+estmov+"&fuente="+fuente,"Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=400,left=50,location=no,resizable=yes");
	 }
	 else
	 {
	 	alert ("Debe terminar de llenar todos los datos");
	 }
	 
}

function currencyDate(date)
{ 
ls_date=date.value;
li_long=ls_date.length;
f=document.form1;
		 
	if(li_long==2)
	{
		ls_date=ls_date+"/";
		ls_string=ls_date.substr(0,2);
		li_string=parseInt(ls_string,10);

		if((li_string>=1)&&(li_string<=31))
		{
			date.value=ls_date;
		}
		else
		{
			date.value="";
		}
		
	}
	if(li_long==5)
	{
		ls_date=ls_date+"/";
		ls_string=ls_date.substr(3,2);
		li_string=parseInt(ls_string,10);
		if((li_string>=1)&&(li_string<=12))
		{
			date.value=ls_date;
		}
		else
		{
			date.value=ls_date.substr(0,3);
		}
	}
	if(li_long==10)
	{
		ls_string=ls_date.substr(6,4);
		li_string=parseInt(ls_string,10);
		if((li_string>=1900)&&(li_string<=2090))
		{
			date.value=ls_date;
		}
		else
		{
			date.value=ls_date.substr(0,6);
		}
	}
}

function cat_cheque()
{
  ls_codban=f.txtcodban.value;
  ls_ctaban=f.txtcuenta.value;	   
  if ((ls_codban!="")&&(ls_ctaban!=""))
     {
	   pagina="sigesp_cat_cheques.php?codban="+ls_codban+"&ctaban="+ls_ctaban;
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=650,height=400,resizable=yes,location=no"); 
     }
}

function uf_catalogo_ordenes()
{
  pagina="sigesp_scb_cat_ordenes_pago_ministerio.php?origen=EC";
  window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=800,height=450,resizable=yes,location=no,dependent=yes");
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>