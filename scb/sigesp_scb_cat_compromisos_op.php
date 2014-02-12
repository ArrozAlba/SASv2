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
$io_fun_banco->uf_load_seguridad("SCB","sigesp_scb_p_orden_pago_directo.php",$ls_permisos,&$la_seguridad,$la_permisos);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>B&uacute;scar Compromisos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
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
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<script language="javascript" src="../shared/js/number_format.js"></script>
</head>

<body>
<table width="650" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
   <td width="650" colspan="2" class="titulo-celda">B&uacute;scar Compromisos</td>
  </tr>
</table>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_banco->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_banco);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<div align="center">
<input name="hidcodpro"    type="hidden"  id="hidcodpro"    value="<?php print $ls_codpro ?>">
<input name="hidcedbene"   type="hidden"  id="hidcedbene"   value="<?php print $ls_cedbene ?>">
<input name="tipo"         type="hidden"  id="tipo"         value="<?php print $ls_tipodestino ?>">
<input name="provbene"     type="hidden"  id="provbene" 	value="<?php print $ls_provbene;?>">
<input name="fecha"        type="hidden"  id="fecha"  		value="<?php print $ls_fechahasta ?>">
<input name="hidcodtipdoc" type="hidden"  id="hidcodtipdoc" value="<?php print $ls_codtipdoc;?>">
<?php
require_once("sigesp_class_scb.php");
require_once("sigesp_cxp_c_recep_doc.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_funciones.php");

$io_conect        = new sigesp_include();
$conn             = $io_conect->uf_conectar();
$io_msg           = new class_mensajes();
$io_dscompromisos = new class_datastore();
$io_sql           = new class_sql($conn);
$io_classscb      = new sigesp_class_scb($conn);
$io_recepdoc      = new sigesp_cxp_c_recep_doc($conn);
$io_funcion       = new class_funciones(); 
$arre             = $_SESSION["la_empresa"];
$ls_codemp        = $arre["codemp"];

require_once("sigesp_scb_c_ordenpago.php");
$in_classmovorden=new sigesp_scb_c_ordenpago($la_seguridad);
if(array_key_exists("operacion",$_POST))
{
     $ls_tipodestino = $_POST["tipo"];
	 $ls_provbene    = $_POST["provbene"];
     $ls_codtipdoc   = $_POST["hidcodtipdoc"];
     $ls_fecha       = $_POST["fecha"];
	 $ls_operacion   = $_POST["operacion"];
	 $ls_comprobante = $_POST["hidcomprobante"];
	 $ls_procede     = $_POST["hidprocede"];
	 $ld_feccomp     = $_POST["hidfecha"];
	 $ls_descripcion = $_POST["hiddescripcion"];
	 $ldec_total     = $_POST["hidtotal"];
	 $ls_mov_document=$_POST["mov_document"];
	 $ls_mov_procede=$_POST["procede"];
	 $ld_fecha=$_POST["fecha"];
	 $ls_provbene=$_POST["provbene"];
	 $ls_tipo=$_POST["tipo"];
	 $ls_mov_descripcion=$_POST["descripcion"];
	 $ls_codban=$_POST["codban"];
	 $ls_ctaban=$_POST["ctaban"];
	 $ls_cuenta_scg=$_POST["cuenta_scg"];
	 $ls_codope=$_POST["mov_operacion"];
	 $ldec_monto_mov=$_POST["monto"];
	 $ldec_objret=$_POST["objret"];
	 $ldec_retenido=$_POST["retenido"];
	 $ls_chevau=$_POST["chevau"];
	 $li_estint=$_POST["estint"];
	 $li_cobrapaga=$_POST["cobrapaga"];
	 $ls_estbpd=$_POST["estbpd"];
	 $ls_nomproben=$_POST["txtnomproben"];
	 $ls_estmov=$_POST["estmov"];
	 $ls_codconmov=$_POST["codconmov"];
	 $ls_estreglib=$_POST["tip_mov"];
	 $ls_opener   =$_POST["opener"];
	 $ls_estdoc   =$_POST["estdoc"];
	 $ls_afectacion ='CP';
	 $ls_tipdocres=$_POST["tipdocres"];
	 $ls_numdocres=$_POST["numdocres"];
	 $ls_fecdocres=$_POST["fecdocres"];
	 $ls_tipreg   =$_POST["tipreg"];
	 $ls_fte_financiamiento=$_POST["ftefinancia"];
	 $ls_origen=$_POST["origen"];
	 $ls_coduniadm=$_POST["coduniadm"];
	 $ls_uel=$ls_coduniadm;
	 $ls_estuac=$_POST["estuac"];
	 $ls_tippag=$_POST["tippag"];
	 $ls_mediopago=$_POST["mediopago"];
	 $ls_modalidad=$_POST["modalidad"];
	 $ls_codbansig=$_POST["codbansig"];
	 $ls_nombreaut=$_POST["nombreaut"];
	 $ls_codbanaut=$_POST["codbanaut"];
	 $ls_nombanaut=$_POST["nombanaut"];
	 $ls_rifaut   =$_POST["rifaut"]; 
	 $ls_ctabanaut=$_POST["ctabanaut"];
	 $ls_codbanbene=$_POST["codbanbene"];
	 $ls_ctabanbene=$_POST["ctabanbene"];
	 $ls_nombanbene=$_POST["nombanbene"];
     $ls_estpro1=$_POST["codestpro1"];
	 $ls_nrocontrol=$_POST["nrocontrol"];
}
else
{
     $ls_tipodestino = $_GET["tipo"];
	 $ls_provbene    = $_GET["provbene"];
     $ls_codtipdoc   = $_GET["hidcodtipdoc"];
     $ls_fecha       = $_GET["fecha"];
     $ls_fecha       = $io_funcion->uf_convertirdatetobd($ls_fecha);
	 $ls_operacion   = "";
	 $ls_comprobante = "";
	 $ls_procede     = "";
	 $ld_feccomp     = "";
	 $ls_descripcion = "";
	 $ldec_total     = "";	 
	 $ls_estpro1="";
	 $ls_estpro2="";
	 $ls_estpro3="";
	 $ls_cuentaplan="";
	 $ls_denominacion="";
	 $ls_procedencia="SCBMOV";
	 $ls_mov_document=$_GET["mov_document"];
	 $ls_mov_procede=$_GET["procede"];
	 $ld_fecha=$_GET["fecha"];
	 $ls_provbene=$_GET["provbene"];
	 $ls_tipo=$_GET["tipo"];
	 $ls_mov_descripcion=$_GET["descripcion"];
	 $ls_descripcion=$ls_mov_descripcion;
	 $ls_codban=$_GET["codban"];
	 $ls_ctaban=$_GET["ctaban"];
	 $ls_cuenta_scg=$_GET["cuenta_scg"];
	 $ls_codope=$_GET["mov_operacion"];
	 $ldec_monto_mov=$_GET["monto"];
	 $ldec_objret=$_GET["objret"];
	 $ldec_retenido=$_GET["retenido"];
	 $ls_chevau     =$_GET["chevau"];
	 $li_estint     =$_GET["estint"];
	 $li_cobrapaga  =$_GET["cobrapaga"];
	 $ls_estbpd     =$_GET["estbpd"];
	 $ls_nomproben  =$_GET["txtnomproben"];
	 $ls_estmov     =$_GET["estmov"];
	 $ls_codconmov  =$_GET["codconmov"];
	 $ls_estreglib  =$_GET["tip_mov"];
	 $ls_opener     =$_GET["opener"];
	 $ls_estdoc     =$_GET["estdoc"];
	 $ls_afectacion =$_GET["afectacion"];
     $ls_estpro1=$_GET["codestpro1"];
	 $ls_denestpro1=$_GET["denestpro1"];
	 $ls_modalidad=$_GET["modalidad"];
	 $ls_tipdocres=$_GET["tipdocres"];
	 $ls_numdocres=$_GET["numdocres"];
	 $ls_fecdocres=$_GET["fecdocres"];
	 $ls_coduniadm=$_GET["coduniadm"];
	 $ls_estuac=$_GET["estuac"];
	 $ls_tipreg   =$_GET["tipreg"];
	 $ls_fte_financiamiento=$_GET["ftefinancia"];
	 $ls_origen=$_GET["origen"];
	 $ls_tippag=$_GET["tippag"];
	 $ls_mediopago=$_GET["mediopago"];
	 $ls_codbansig=$_GET["codbansig"];
	 $ls_nombreaut=$_GET["nombreaut"];
	 $ls_codbanaut=$_GET["codbanaut"];
	 $ls_nombanaut=$_GET["nombanaut"];
	 $ls_rifaut   =$_GET["rifaut"]; 
	 $ls_ctabanaut=$_GET["ctabanaut"];
	 $ls_codbanbene=$_GET["codbanbene"];
	 $ls_ctabanbene=$_GET["ctabanbene"];
	 $ls_nombanbene=$_GET["nombanbene"];
	 $ls_nrocontrol=$_GET["nrocontrol"];;
}
if($ls_tipodestino=='P')	
{
  $ls_codpro=$ls_provbene;
  $ls_cedbene='----------';
}
else
{
  $ls_codpro='----------';
  $ls_cedbene=$ls_provbene;
}
		 
 if($ls_operacion=="GUARDAR")
 {	
	 require_once("../shared/class_folder/class_datastore.php");
     $io_dspresupuesto = new class_datastore();
	 $io_dscontable    = new class_datastore();
	 $rs_data        = $io_classscb->uf_load_dtcomprobante($ls_comprobante,$ls_procede,$ld_feccomp,$lb_valido);
	 $ld_montopro    = 0;
	 $ld_suma        = 0;
	 $ld_monto=0;$ld_ajuste=0;$ld_causado=0;$ld_anulado=0;$ld_montord=0;$ld_rdcargos=0;$ld_cargos=0;
	 $ld_montodebe=0;
	 while($li_row=$io_classscb->io_sql->fetch_row($rs_data))
	 {
		$ls_estcla       = $li_row["estcla"];
		$ls_codestpro1   = trim($li_row["codestpro1"]);
		$ls_codestpro2   = trim($li_row["codestpro2"]);
		$ls_codestpro3   = trim($li_row["codestpro3"]);
		$ls_codestpro4   = trim($li_row["codestpro4"]);
		$ls_codestpro5   = trim($li_row["codestpro5"]);
		
		$ls_programatica = $ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;	
		
		$ls_spgcuenta  = trim($li_row["spg_cuenta"]);
		$ld_monto      = $li_row["monto"];
		$ld_montopro   = ($ld_montopro+$ld_monto);
		$ld_montocmp   = number_format($ld_monto,2,',','.');

		$ld_ajuste     = $io_classscb->uf_rddc_ajustes($ls_procede,$ls_comprobante,$ls_tipodestino,$ls_codpro,$ls_cedbene,$ls_codestpro1,
		                                               $ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_spgcuenta,$ls_estcla,$lb_valido);
		if (empty($ld_ajuste))
		{
			 $ld_ajuste=0;
		}
		if ($lb_valido)
		{
			 $ld_causado=$io_classscb->uf_rddc_causados($ls_procede,$ls_comprobante,$ls_tipodestino,$ls_codpro,$ls_cedbene,$ls_codestpro1,
														$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_spgcuenta,$ls_estcla,$lb_valido);
		}   
		if (empty($ld_causado))
		{
			 $ld_causado=0;
		}
		if ($lb_valido)
		{
			 $ld_anulado=$io_classscb->uf_rddc_anulados($ls_procede,$ls_comprobante,$ls_tipodestino,$ls_codpro,$ls_cedbene,$ls_codestpro1,
														$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_spgcuenta,$ls_estcla,$lb_valido);   	    
		}   
		if (empty($ld_anulado))
		{
			 $ld_anulado=0;
		}
		if ($lb_valido)
		{
			 $ld_montord=$io_classscb->uf_rddc_recdoc($ls_procede,$ls_comprobante,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
													  $ls_codestpro4,$ls_codestpro5,$ls_spgcuenta,$ls_estcla,$lb_valido);   	    
		}   
		if (empty($ld_montord))
		{
			 $ld_montord=0;
		}
		if ($lb_valido)
		{
			 $ld_rdcargos=$io_classscb->uf_rddc_recdoc_cargos($ls_procede,$ls_comprobante,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,
															  $ls_spgcuenta,$ls_estcla,$lb_valido);   	    
		}   
		if (empty($ld_rdcargos))
		{
			 $ld_rdcargos=0;
		} 
		if ($lb_valido)
		{
			 $ls_fechahasta=$ls_fecha;
			 $ld_cargos=0;
			 $lb_valido=$io_classscb->uf_rd_ajusta_spg($ls_tipodestino,$ls_codpro,$ls_cedbene,$ls_fechahasta,$ls_codtipdoc,
													   $ls_procede,$ls_comprobante,$ld_feccomp,$ld_cargos);
		}	
		else
		{
			 $ld_cargos=0;	
		}		
	}
	if ($lb_valido)
    {
	  $ld_disponible = ($ld_monto+$ld_ajuste)-$ld_causado+$ld_anulado-$ld_montord-$ld_rdcargos-$ld_cargos;
	  $ld_disponible = number_format($ld_disponible,2,',','.');
	  if ($ld_disponible>0)
	  {
		  if ($ls_procede=='SOCCOC')
		  {
			   $rs_resultado = $io_recepdoc->uf_load_dt_orden_compra($ls_codemp,$ls_comprobante,'B',$lb_valido);
		  }
		  else
		  {
			   if($ls_procede=='SEPSPC')
			   {
				   $rs_resultado=$io_recepdoc->uf_load_dtotros_sep($ls_codemp,$ls_comprobante,$lb_valido);  					    
			   }
			   elseif($ls_procede=='SOCCOS')
			   {
				    $rs_resultado=$io_recepdoc->uf_load_dt_orden_compra($ls_codemp,$ls_comprobante,'S',$lb_valido);
			   }
		   }
		   if($lb_valido)
		   {
			   $ld_suma = doubleval($ld_suma);
			   while ($row=$io_sql->fetch_row($rs_resultado))
					 {
					   $ld_monto       = $row["monto"];
					   $ld_suma        = $ld_suma+$ld_monto;
					   $ls_spgcuenta   = $row["spg_cuenta"]; 
					   if (($ls_procede=='SOCCOC')||($ls_procede=='SOCCOS'))
						  {
							$ls_comprobante = $row["numordcom"];
						  }
					   elseif($ls_procede=='SEPSPC')
						  {
							$ls_comprobante = $row["numsol"];
						  }
					   $ls_estcla      = $row["estcla"]; 
					   $ls_codestpro1  = $row["codestpro1"]; 
					   $ls_codestpro2  = $row["codestpro2"]; 
					   $ls_codestpro3  = $row["codestpro3"]; 
					   $ls_codestpro4  = $row["codestpro4"]; 
					   $ls_codestpro5  = $row["codestpro5"]; 
					   $ls_programatica= $ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;	
					   $ldec_baseimp   = $row["baseimp"];	
					   $ls_codcar      = $row["codcar"];
					   $ls_cargo       = '';
					   $lb_valido      = false;
					   $ls_scgcuenta   = $row["sc_cuenta"]; 
					   $io_dspresupuesto->insertRow('txtcodcar',$ls_codcar);
					   $io_dspresupuesto->insertRow('txtestcla',$ls_estcla);
					   $io_dspresupuesto->insertRow('txtmontopre',$ld_monto);
					   $io_dspresupuesto->insertRow('txtbaseimp',$ldec_baseimp);
					   $io_dspresupuesto->insertRow('txtestadistico',$ls_spgcuenta);
					   $io_dspresupuesto->insertRow('txtcompromisopre',$ls_comprobante);
					   $io_dspresupuesto->insertRow('txtprogramatico',$ls_programatica);
					   
					   $ld_monto     = doubleval($ld_monto);
					   $ld_montodebe = $ld_montodebe+$ld_monto;
					   $io_dscontable->insertRow('txtcompromisocon',$ls_comprobante);
					   $io_dscontable->insertRow('txtcontable',$ls_scgcuenta);
					   $io_dscontable->insertRow('txtoperacion','D');
					   $io_dscontable->insertRow('txtmontocont',$ld_monto);
					   $io_dscontable->insertRow('txtmontoorig',$ld_monto);     
					   $ls_deduccion = '';
					 }
				}
			   $io_dscontable->insertRow('txtcompromisocon',$ls_mov_document);
			   $io_dscontable->insertRow('txtcontable',$ls_cuenta_scg);
			   $io_dscontable->insertRow('txtoperacion','H');
			   $io_dscontable->insertRow('txtmontocont',$ld_montopro);
			   $io_dscontable->insertRow('txtmontoorig',$ld_montopro); 
			       
 			   $li_totdspresupuesto=$io_dspresupuesto->getRowCount('txtestadistico'); 
				$li_auxpre   = 0;
				$lb_valido=true;
				$li_aux_estserext=0;
				$lb_valido=$in_classmovorden->uf_guardar_automatico($ls_codban,$ls_ctaban,$ls_mov_document,$ls_codope,$ld_fecha,$ls_mov_descripcion,$ls_codconmov,$ls_codpro,$ls_cedbene,$ls_nomproben,$ld_montopro,$ldec_objret,$ldec_retenido,$ls_chevau,$ls_estmov,$li_estint,$li_cobrapaga,$ls_estbpd,$ls_mov_procede,$ls_estreglib,$ls_estdoc,$ls_tipodestino,$ls_tipdocres,$ls_numdocres,$ls_fecdocres,$ls_tipreg,$ls_fte_financiamiento,$ls_origen,$ls_tippag,$ls_mediopago,$ls_modalidad,$ls_coduniadm,$ls_codbansig,$ls_estpro1,$ls_codbanbene,$ls_nombanbene,$ls_ctabanbene,$ls_codbanaut,$ls_nombanaut,$ls_ctabanaut,$ls_rifaut,$ls_nombreaut,$ls_nrocontrol,$li_aux_estserext);
				if($lb_valido)
				{
					for ($z=1;($z<=$li_totdspresupuesto)&&($lb_valido);$z++)
					{					
						$arr_movbco["codban"]=$ls_codban;
						$arr_movbco["ctaban"]=$ls_ctaban;
						$arr_movbco["mov_document"]=$ls_mov_document;
						$ld_fecdb=$io_funcion->uf_convertirdatetobd($ld_fecha);
						$arr_movbco["codope"]=$ls_codope;
						$arr_movbco["fecha"]=$ld_fecha;
						$arr_movbco["codpro"]=$ls_codpro;
						$arr_movbco["cedbene"]=$ls_cedbene;
						$arr_movbco["monto_mov"]=$ldec_monto_mov;
						$arr_movbco["objret"]   =$ldec_objret;
						$arr_movbco["retenido"] =$ldec_retenido;
						$arr_movbco["estmov"]=$ls_estmov;
						  $li_auxpre++;
						  if ($z>1)
							 {
							   $ls_compre = $io_dspresupuesto->getValue("txtcompromisopre",$z-1);	//Numero del documento anterior.					
							 }
						  $ls_compromisopre = $io_dspresupuesto->getValue("txtcompromisopre",$z);						
						  $ls_programatica  = $io_dspresupuesto->getValue("txtprogramatico",$z);
						  $ls_spgcuenta     = $io_dspresupuesto->getValue("txtestadistico",$z);
						  $ld_montopre      = $io_dspresupuesto->getValue("txtmontopre",$z);
						  $ldec_baseimp     = $io_dspresupuesto->getValue("txtbaseimp",$z);
						  $ls_codcar        = $io_dspresupuesto->getValue("txtcodcar",$z);
						  $ls_estcla        = $io_dspresupuesto->getValue("txtestcla",$z);
						  $lb_valido=$in_classmovorden->uf_procesar_dt_gasto($ls_codban,$ls_ctaban,$ls_mov_document,$ls_codope,$ls_estmov,$ls_programatica,$ls_spgcuenta,$ls_compromisopre,$ls_descripcion,$ls_procede,$ld_montopre,'CP',$ls_uel,$ldec_baseimp,$ls_codcar,$ls_estcla);
						  if(!$lb_valido) 
						  {
							 $io_msg->message($in_classmovorden->is_msg_error);  	
						  }					
					}
				}
				else
				{
					$io_msg->message($in_classmovorden->is_msg_error);
				}
					
				$li_totalpre   = $li_auxpre;
				$totalpre      = $li_auxpre;
				$li_lastrowpre = $li_auxpre;
				if($lb_valido)
				{
					$li_totdscontable=$io_dscontable->getRowCount('txtcontable'); 
					$ld_totaldebe =0;
					$ld_totalhaber=0;
					$ld_montoded  =0;
					for ($w=1;$w<=$li_totdscontable;$w++)
					{
						  $ls_compromisocon = $io_dscontable->getValue('txtcompromisocon',$w);
						  $ls_cuentascg     = $io_dscontable->getValue('txtcontable',$w);
						  $ls_operacioncon  = $io_dscontable->getValue('txtoperacion',$w);
						  $ld_monto         = $io_dscontable->getValue('txtmontocont',$w);
						  $ld_montooriginal = $io_dscontable->getValue('txtmontoorig',$w);
						  $lb_valido=$in_classmovorden->uf_procesar_dt_contable($arr_movbco,$ls_cuentascg,$ls_procede,$ls_descripcion,$ls_compromisocon,$ls_operacioncon,$ld_monto,$ld_monto,false,'00000');
					}
					if($lb_valido)
					{
						$in_classmovorden->io_sql->commit();
						$ls_estdoc='C';
						$ldec_monto_mov=$ld_montopro+$ldec_monto_mov;
						?>
						<script language="javascript">
							f=opener.document.form1;
							f.operacion.value="CARGAR_DT";
							f.status_doc.value='C';
							f.txtmonto.value="<?php print number_format($ldec_monto_mov,2,",",".");?>";
							f.action="<?php print $ls_opener;?>";
							f.submit();
						</script>	
						<?php
					}
					else
					{
						$in_classmovorden->io_sql->rollback();
						$io_msg->message($in_classmovorden->is_msg_error);
					}	
			  	 }
			 }
		}
}

//////////////Carga de los compromisos previos//////////////////////////////////////
$lb_valido = $io_classscb->uf_load_comprobantes_positivos($ls_tipodestino,$ls_codpro,$ls_cedbene,$ls_fecha);   

if ($lb_valido)
{
  $li_total = $io_classscb->ds_comprobantes->getRowCount('comprobante');
  if ($li_total>0)
	 {
	   for ($i=1;$i<=$li_total;$i++)
		   {
			 $ls_procedetemp     = $io_classscb->ds_comprobantes->getValue('procedencia',$i);
			 $ls_comprobantetemp = $io_classscb->ds_comprobantes->getValue('comprobante',$i);
			 $ld_total       = $io_classscb->ds_comprobantes->getValue('total',$i);
			 $ls_descripcion = $io_classscb->ds_comprobantes->getValue('descripcion',$i);
			 $ls_fechatemp       = $io_classscb->ds_comprobantes->getValue('fecha',$i);
			 $ajustes        = $io_classscb->uf_load_monto_ajustes($ls_comprobantetemp,$ls_procedetemp,$ls_tipodestino,$ls_codpro,$ls_cedbene,$lb_valido);//Buscamos montos ajustados, en caso de que los tenga, devolvemos la sumatoria.
			 if ($lb_valido)
				{
				  if (empty($ajustes))
					 {
					   $ld_monto_ajuste=0;
					 }
				  else
					 {
					   $ld_monto_ajuste=$ajustes;
					 }
				  $causado = $io_classscb->uf_load_monto_causados($ls_comprobantetemp,$ls_procedetemp,$ls_tipodestino,$ls_codpro,$ls_cedbene,$lb_valido);//Buscamos montos causados, en caso de que los tenga, devolvemos la sumatoria. 
				  if ($lb_valido)
					 {
					   if (empty($causado))
						  {
							$ld_monto_causado=0;
						  }
					   else
						  {
							$ld_monto_causado=$causado;   
						  }
					   $anulados = $io_classscb->uf_load_monto_anulados($ls_comprobantetemp,$ls_procedetemp,$ls_tipodestino,$ls_codpro,$ls_cedbene,$lb_valido);//Buscamos montos anulados, en caso de que los tenga, devolvemos la sumatoria.
					   if ($lb_valido)
						  {
							if (empty($anulados))
							   {
								 $ld_monto_anulado=0;
							   }
							else
							   {
								 $ld_monto_anulado=$anulados;
							   }
							$recepcion = $io_classscb->uf_load_monto_recepciones($ls_comprobantetemp,$ls_procedetemp,&$lb_valido);//Buscamos montos presupuestarios asociados a la recepcion de documento, en caso de que los tenga, devolvemos la sumatoria.
							if ($lb_valido)
							   {
								 if (empty($recepcion))
									{
									  $ld_monto_recepcion=0;
									}
								 else
									{
									  $ld_monto_recepcion=$recepcion;
									}
								 $ldec_monto_op=$io_classscb->uf_load_monto_ordenespago_directa($ls_comprobantetemp,$ls_procedetemp,$lb_valido);
								 $otroscreditos = $io_classscb->uf_load_monto_cargos($ls_comprobantetemp,$ls_procedetemp,&$lb_valido);
								 if ($lb_valido)
									{
									  if (empty($otroscreditos))
										 {
										   $ld_monto_otroscreditos=0;
										 }
									  else
										 {
										   $ld_monto_otroscreditos =$otroscreditos;
										 }
									   $ld_disponible = ($ld_total+$ld_monto_ajuste)-$ld_monto_causado+$ld_monto_anulado-$ld_monto_recepcion-$ld_monto_otroscreditos-$ldec_monto_op;
									   if ($ld_disponible>0)
										  {
											$ld_monto_solpago=0;
											$ld_acum_solpago =0;
											$ld_acum_solpago= $io_classscb->uf_load_acumulado_solicitudes($ls_comprobantetemp,$ls_codtipdoc,$ls_codpro,$ls_cedbene,$lb_valido);//Buscamos el acumulado en Solicitudes de Pago para la Recepción de Documento.
											if (!empty($ld_acum_solpago))
											   {
												 $ld_monto_solpago = $ld_acum_solpago;
											   }
											if ($ld_total==$ld_monto_solpago)//Verificar que no existan solicitudes de pago con el monto igual a la RD.
											   {
												 $lb_valido=false;
											   }
											else
											   {
												 $lb_valido=true;
											   }
											if ($lb_valido)
											   {
												 $io_dscompromisos->insertRow('comprobante',$ls_comprobantetemp);
												 $io_dscompromisos->insertRow('procedencia',$ls_procedetemp);
												 $io_dscompromisos->insertRow('descripcion',$ls_descripcion);
												 $ls_fechatemp       = $io_funcion->uf_convertirfecmostrar($ls_fechatemp);
												 $io_dscompromisos->insertRow('fecha',$ls_fechatemp);
												 $io_dscompromisos->insertRow('total',$ld_disponible);
											   }
										  }
									 }
								}
						   }
					  }
				}
		   }
		 $li_totds=$io_dscompromisos->getRowCount('comprobante');
		 if ($li_totds>0)
			{
			  print "<table width=650 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla>";
			  print "<tr class=titulo-celda>";
			  print "<td>Comprobante</td>";
			  print "<td>Procede</td>";
			  print "<td>Fecha</td>";
			  print "<td>Descripción</td>";
			  print "<td>Total</td>";
			  print "</tr>";
			  for ($x=1;$x<=$li_totds;$x++)
				  {
					print "<tr class=celdas-blancas>";
					$ls_comprobantetemp = $io_dscompromisos->getValue('comprobante',$x);
					$ls_procedetemp     = $io_dscompromisos->getValue('procedencia',$x);
					$ls_descripciontemp = $io_dscompromisos->getValue('descripcion',$x);
					$ls_fechatemp       = $io_dscompromisos->getValue('fecha',$x);
					$ld_total	    = $io_dscompromisos->getValue('total',$x);
					print "<td  width=110  align=center><a href=\"javascript: aceptar('$ls_comprobantetemp','$ls_procedetemp','$ls_descripciontemp','$ls_fechatemp','$ld_total');\">".$ls_comprobantetemp."</a></td>";
					print "<td  width=70  align=center>".$ls_procedetemp."</td>";
					print "<td  width=70  align=center>".$ls_fechatemp."</td>";
					print "<td  width=180  align=left title='$ls_descripcion'>".$ls_descripciontemp."</td>";
					print "<td  width=100  align=right>".number_format($ld_total,2,",",".")."</td>";
					print "</tr>";
				  }
			  print "</table>";
			}
		 }
	  else
		 {
		   $io_msg->message("No existen compromisos asociados a este Proveedor/Beneficiario !!!"); ?>
		   <script language="javascript">
		   close();
		   </script>
		   <?php
		 }
 }
////////Fin de carga de los compromisos previos/////////////////////////////////////////////////////
?>
   <input name="hidcomprobante" type="hidden" id="hidcomprobante" value="<?php print $ls_comprobante;?>">
   <input name="hidprocede" type="hidden" id="hidprocede" value="<?php print $ls_procede;?>">
   <input name="hidfecha" type="hidden" id="hidfecha" value="<?php print $ls_fecha;?>">
   <input name="hiddescripcion" type="hidden" id="hiddescripcion" value="<?php print $ls_descripcion;?>">
   <input name="hidtotal" type="hidden" id="hidtotal" value="<?php print $ldec_total;?>">
   <input name="operacion" type="hidden" id="operacion">
   <input name="txtcuentascg" type="hidden" id="txtcuentascg">
      <input name="comprobante" type="hidden" id="comprobante" value="<?php print $ls_comprobante;?>">
      <input name="procede" type="hidden" id="procede" value="<?php print $ls_mov_procede;?>">
	  <input name="fecha" type="hidden" id="fecha" value="<?php print $ld_fecha;?>">
      <input name="provbene" type="hidden" id="provbene" value="<?php print $ls_provbene;?>">
      <input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo;?>">
      <input name="descripcion" type="hidden" id="descripcion" value="<?php print $ls_mov_descripcion;?>">
      <input name="mov_document" type="hidden" id="mov_document" value="<?php print $ls_mov_document;?>">
      <input name="codban" type="hidden" id="codban" value="<?php print $ls_codban;?>">
      <input name="ctaban" type="hidden" id="ctaban" value="<?php print $ls_ctaban;?>">
      <input name="cuenta_scg" type="hidden" id="cuenta_scg" value="<?php print $ls_cuenta_scg;?>">
      <input name="mov_operacion" type="hidden" id="mov_operacion" value="<?php print $ls_codope;?>">
      <input name="txtnomproben" type="hidden" id="txtnomproben" value="<?php print $ls_nomproben;?>">
      <input name="monto" type="hidden" id="monto" value="<?php print $ldec_monto_mov;?>">
      <input name="objret" type="hidden" id="objret" value="<?php print $ldec_objret;?>">
      <input name="retenido" type="hidden" id="retenido" value="<?php print $ldec_retenido;?>">
      <input name="chevau" type="hidden" id="chevau" value="<?php print $ls_chevau;?>">
      <input name="estint" type="hidden" id="estint" value="<?php print  $li_estint;?>">
      <input name="cobrapaga" type="hidden" id="cobrapaga" value="<?php print $li_cobrapaga;?>">
      <input name="estbpd" type="hidden" id="estbpd" value="<?php print $ls_estbpd;?>">
      <input name="estmov" type="hidden" id="estmov" value="<?php print $ls_estmov;?>">
      <input name="codconmov" type="hidden" id="codconmov" value="<?php print $ls_codconmov;?>">
      <input name="tip_mov" type="hidden" id="tip_mov" value="<?php print $ls_estreglib;?>">
      <input name="opener" type="hidden" id="opener" value="<?php print $ls_opener;?>">
      <input name="estdoc" type="hidden" id="estdoc" value="<?php print $ls_estdoc;?>">
	  <input name="tipdocres" type="hidden" id="tipdocres" value="<?php print $ls_tipdocres;?>">
	  <input name="numdocres" type="hidden" id="numdocres" value="<?php print $ls_numdocres;?>">
	  <input name="fecdocres" type="hidden" id="fecdocres" value="<?php print $ls_fecdocres;?>">
  	  <input name="tipreg" type="hidden" id="tipreg" value="<?php print $ls_tipreg;?>">
  	  <input name="ftefinancia" type="hidden" id="ftefinancia" value="<?php print $ls_fte_financiamiento;?>">
  	  <input name="origen" type="hidden" id="origen" value="<?php print $ls_origen;?>">
  	  <input name="tippag" type="hidden" id="tippag" value="<?php print $ls_tippag;?>">
  	  <input name="mediopago" type="hidden" id="mediopago" value="<?php print $ls_mediopago;?>">
	  <input name="modalidad" type="hidden" id="modalidad" value="<?php print $ls_modalidad;?>">
	  <input name="coduniadm" type="hidden" id="coduniadm" value="<?php print $ls_coduniadm;?>">
	  <input name="estuac" type="hidden" id="estuac" value="<?php print $ls_estuac; ?>">
	  <input name="codbansig" type="hidden" id="codbansig" value="<?php print $ls_codbansig;?>"> <input name="origen" type="hidden" id="origen" value="<?php print $ls_origen;?>">
  	  <input name="codbanbene" type="hidden" id="codbanbene" value="<?php print $ls_codbanbene;?>">
  	  <input name="ctabanbene" type="hidden" id="ctabanbene" value="<?php print $ls_ctabanbene;?>">
	  <input name="nombanbene" type="hidden" id="nombanbene" value="<?php print $ls_nombanbene;?>">
	  <input name="nombreaut"  type="hidden" id="nombreaut"  value="<?php print $ls_nombreaut;?>">
	  <input name="codbanaut"  type="hidden" id="codbanaut"  value="<?php print $ls_codbanaut; ?>">

	  <input name="ctabanaut"  type="hidden" id="ctabanaut"  value="<?php print $ls_ctabanaut;?>">
	  <input name="rifaut"     type="hidden" id="rifaut"     value="<?php print $ls_rifaut;?>">
	  <input name="nombanaut"  type="hidden" id="nombanaut"  value="<?php print $ls_nombanaut;?>">
      <input name="codestpro1" type="hidden" id="codestpro1" value="<?php print $ls_estpro1; ?>">
      <input name="nrocontrol" type="hidden" id="nrocontrol" value="<?php print $ls_nrocontrol;?>">
</div>
</form>
</body>
<script language="JavaScript">
function aceptar(comprobante,procede,descripcion,fecha,total)
  {
	f = document.form1;
	f.hidcomprobante.value = comprobante;
	f.hidprocede.value     = procede;
	f.hidfecha.value       = fecha;
	f.hidtotal.value       = total;
	f.hiddescripcion.value = descripcion;
	f.operacion.value      = "GUARDAR";
	f.action="sigesp_scb_cat_compromisos_op.php";
	f.submit();
	
  }
</script>
</html>