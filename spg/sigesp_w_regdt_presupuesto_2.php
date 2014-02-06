<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2"  src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2"  src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="javascript"     src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2"  src="js/funciones_gasto.js"></script>
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
<link href="../shared/css/general.css"  rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css"   rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style2 {font-size: 11px}
.Estilo1 {font-weight: bold}
-->
</style>
</head>
<body>
<?php
$dat=$_SESSION["la_empresa"];
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sigesp_int.php");
require_once("../shared/class_folder/class_sigesp_int_scg.php");

$io_function=new class_funciones();	
$io_include=new sigesp_include();	
$io_connect=$io_include->uf_conectar();
$io_sql=new class_sql($io_connect);
$io_msg = new class_mensajes();

require_once("sigesp_spg_c_comprobante.php");
$in_classcmp=new sigesp_spg_c_comprobante();
require_once("../shared/class_folder/sigesp_c_seguridad.php");
$io_seguridad= new sigesp_c_seguridad();
/////////////////////////////////////Parametros necesarios para seguridad////////////////////////////
	$ls_empresa=$dat["codemp"];
	$li_estmodest=$dat["estmodest"];
	if(array_key_exists("la_logusr",$_SESSION))
	{
	$ls_logusr=$_SESSION["la_logusr"];
	}
	else
	{
	$ls_logusr="";
	}
	$ls_sistema="SPG";
	$ls_ventana="sigesp_spg_p_comprobante.php";
	$la_security[1]=$ls_empresa;
	$la_security[2]=$ls_sistema;
	$la_security[3]=$ls_logusr;
	$la_security[4]=$ls_ventana;
//////////////////////////////////////////////////////////////////////////////////////////////////
if (array_key_exists("operacion",$_POST))
{
    $ls_operacion=$_POST["operacion"];
	$ls_documento=$_POST["txtdocumento"];	
    $ls_estpro1=$_POST["codestpro1"];
	$ls_estpro2=$_POST["codestpro2"];
	$ls_estpro3=$_POST["codestpro3"];
	$ls_cuentaplan=$_POST["txtcuenta"];
	$ls_denominacion=$_POST["txtdenominacion"];
	$ls_procedencia=$_POST["txtprocedencia"];
	$ls_descripcion=$_POST["txtdescripcion"];
	$ls_comprobante=$_POST["comprobante"];
	$ls_proccomp   =$_POST["procede"];
	$ls_desccomp   =$_POST["descripcion"];
	$ld_fecha	   =$_POST["fecha"];
	$ls_tipo       =$_POST["tipo"];
	$ls_provbene   =$_POST["provbene"];
	$ls_redfon     =$_POST["renfon"];
	$ls_codfuefin  =$_POST["codfuefin"];
	$ls_filapre    =$_POST["filapre"]; 
	$ls_filacont    =$_POST["totcont"];
	$ls_txtscg      =$_POST["txtscg"];  
	
    if($li_estmodest==2)
	{
		$ls_estpro4=$_POST["codestpro4"];
		$ls_estpro5=$_POST["codestpro5"];
	}

}
else
{
	$ls_operacion="";	
    $ls_estpro1="";
	$ls_estpro2="";
	$ls_estpro3="";
	$ls_estpro4="";
	$ls_estpro5="";
	$ls_estcla="";
	$ls_cuentaplan="";
	$ls_denominacion="";
	$ls_procedencia="SPGCMP";
	$ls_descripcion=$_GET["descripcion"];
	$ls_comprobante=$_GET["comprobante"];
	$ls_proccomp   =$_GET["procede"];
	$ls_desccomp   =$_GET["descripcion"];
	$ld_fecha	   =$_GET["fecha"];
	$ls_tipo       =$_GET["tipo"];
	$ls_provbene   =$_GET["provbene"];
	$ls_codfuefin  =$_GET["codfuefin"];
	
	$ls_filapre    =$_GET["filapre"];
	$ls_filacont   =$_GET["totcont"]; 
	$ls_txtscg      ="";  
	
    if($li_estmodest==2)
	{
		$ls_estpro4="";
		$ls_estpro5="";
	}
	//------------------------------------------
	$ls_redfon=$_GET["renfon"];
	if ($ls_redfon=='0')
	{
		$ls_documento=$_GET["comprobante"];
		$ls_valor="";
	}
	else
	{
	    $ls_comprobante=$_GET["comprobante"];
		$ls_filapre2=$ls_filapre-1;	
		$ls_documento=$ls_filapre2+1;	
		$ls_documento=str_pad($ls_documento,15,"0",0);	
		$ls_valor="readonly";
	}
	//------------------------------------------
	

}

if($ls_operacion=="GUARDARPRE")
{
	$ls_cuenta = $_POST["txtcuenta"];
	$ls_est1   = $_POST["codestpro1"];
	$ls_est2   = $_POST["codestpro2"];
	$ls_est3   = $_POST["codestpro3"];
    $ls_estcla = $_POST["estcla"];
	if($li_estmodest==2)
	{
		$ls_est4 = $_POST["codestpro4"];
		$ls_est5 = $_POST["codestpro5"];
		$ls_est1 = $io_function->uf_cerosizquierda($ls_est1,25);
		$ls_est2 = $io_function->uf_cerosizquierda($ls_est2,25);
		$ls_est3 = $io_function->uf_cerosizquierda($ls_est3,25);
		$ls_est4 = $io_function->uf_cerosizquierda($ls_est4,25);
		$ls_est5 = $io_function->uf_cerosizquierda($ls_est5,25);
	}
	else
	{
		$ls_est1 = $io_function->uf_cerosizquierda($ls_est1,25);
		$ls_est2 = $io_function->uf_cerosizquierda($ls_est2,25);
		$ls_est3 = $io_function->uf_cerosizquierda($ls_est3,25);
		$ls_est4 = $io_function->uf_cerosizquierda(0,25);
		$ls_est5 = $io_function->uf_cerosizquierda(0,25);
	}
	$ld_monto       = $_POST["txtmonto"];
	$ldec_monto=str_replace(".","",$ld_monto);
	$ldec_monto=str_replace(",",".",$ldec_monto);
	$ls_operacionpre= $_POST["txtoperacion"];
	$ld_disponible=0;
	$lb_valido=$in_classcmp->uf_spg_select_disponibilidad($ls_cuenta,$ls_est1,$ls_est2,$ls_est3,$ls_est4,$ls_est5,$ld_disponible,$ls_operacionpre,$ldec_monto,$ls_estcla);
	//$lb_valido=false; // se cambio para fudeco
	if(($ld_disponible<$ldec_monto)&&($lb_valido))
	{
	   $io_msg->message(" La Cuenta  ".trim($ls_cuenta)."  no tiene disponibilidad " ); 
	   $ls_cuentaplan= "";
	   $ls_denominacion= "";
	   $ls_descripcion= "";
	}
	else
	{
        $ldec_monto=0;
		$ls_comprobante= $_POST["comprobante"];
		$ld_fecha      = $_POST["fecha"];
		$ls_proccomp   = $_POST["procede"];
		$ls_desccomp   = $_POST["descripcion"];
		$ls_provbene   = $_POST["provbene"];	
		$ls_tipo	   = $_POST["tipo"];
		$ls_codban     = "---";
		$ls_ctaban     = "-------------------------";
		$ls_redfon     =$_POST["renfon"];
		$ls_codfuefin  =$_POST["codfuefin"]; 
			
		if($ls_tipo=="P")
		{
			$ls_prov=$ls_provbene;
			$ls_bene="----------";
		}
		else
		{
			$ls_bene=$ls_provbene;
			$ls_prov="----------";
		}
		//$in_classcmp->io_int_scg->io_sql->begin_transaction();
		//$in_classcmp->io_int_int->uf_int_init_transaction_begin();
		$lb_valido=$in_classcmp->uf_guardar_automatico($ls_comprobante,$ld_fecha,$ls_proccomp,$ls_desccomp,&$ls_prov,&$ls_bene,$ls_tipo,1,$ls_codban,$ls_ctaban,$ls_redfon,$ls_codfuefin);
		
		$arr_cmp["comprobante"]=$ls_comprobante;
		$ld_fecdb=$io_function->uf_convertirdatetobd($ld_fecha);
		$arr_cmp["fecha"]      =$ld_fecdb;
		$arr_cmp["procedencia"]=$ls_proccomp;
		$arr_cmp["descripcion"]=$ls_desccomp;
		$arr_cmp["proveedor"]  =$ls_prov;
		$arr_cmp["beneficiario"]=$ls_bene;
		$arr_cmp["tipo"]       =$ls_tipo;
		$arr_cmp["codemp"]     =$dat["codemp"];
		$arr_cmp["tipo_comp"]  =1;
		if($lb_valido)
		{
			$ls_cuenta      = $_POST["txtcuenta"];
			$ls_est1        = $_POST["codestpro1"];
			$ls_est2        = $_POST["codestpro2"];
			$ls_est3        = $_POST["codestpro3"];
			if($li_estmodest==2)
			{
				$ls_est4        = $_POST["codestpro4"];
				$ls_est5        = $_POST["codestpro5"];
				$ls_est1 = $io_function->uf_cerosizquierda($ls_est1,25);
				$ls_est2 = $io_function->uf_cerosizquierda($ls_est2,25);
				$ls_est3 = $io_function->uf_cerosizquierda($ls_est3,25);
				$ls_est4 = $io_function->uf_cerosizquierda($ls_est4,25);
				$ls_est5 = $io_function->uf_cerosizquierda($ls_est5,25);
			}
			else
			{
				$ls_est1 = $io_function->uf_cerosizquierda($ls_est1,25);
				$ls_est2 = $io_function->uf_cerosizquierda($ls_est2,25);
				$ls_est3 = $io_function->uf_cerosizquierda($ls_est3,25);
				$ls_est4 = $io_function->uf_cerosizquierda(0,25);
				$ls_est5 = $io_function->uf_cerosizquierda(0,25);
			}
            $ls_estcla = $_POST["estcla"];
			$ls_documento   = $_POST["txtdocumento"];
			$ls_denominacion= $_POST["txtdenominacion"];
			$ls_procede     = $_POST["txtprocedencia"];
			$ls_operacionpre= $_POST["txtoperacion"];
			$ld_monto       = $_POST["txtmonto"];
			$ldec_monto=str_replace(".","",$ld_monto);
			$ldec_monto=str_replace(",",".",$ldec_monto);
			//$in_classcmp->io_int_scg->io_sql->begin_transaction();
			$lb_valido=$in_classcmp->uf_guardar_movimientos($arr_cmp,$ls_est1,$ls_est2,$ls_est3,$ls_est4,$ls_est5,$ls_cuenta,$ls_procede,$ls_descripcion,$ls_documento,$ls_operacionpre,0,$ldec_monto,"C",$ls_codban,$ls_ctaban,$ls_estcla);
			if($lb_valido)
			{
				$ls_codemp=$_SESSION["la_empresa"]["codemp"];
				$ls_codestpro[0]=$ls_est1;
                $ls_codestpro[1]=$ls_est2;
                $ls_codestpro[2]=$ls_est3;
                $ls_codestpro[3]=$ls_est4;
                $ls_codestpro[4]=$ls_est5;
                $ls_codestpro[5]=$ls_estcla;
				/*$lb_valido=$in_classcmp->uf_update_bsf_sigespcmp(0,$ls_codemp,$ls_procede,$ls_comprobante,
				                                                 $ld_fecdb,$ls_codban,$ls_ctaban,$la_security);				
                if($lb_valido)
				{
					$lb_valido=$in_classcmp->uf_update_bsf_spgdtcmp($ldec_monto,$ls_codemp,$ls_procede,$ls_comprobante,
																	$ld_fecdb,$ls_codban,$ls_ctaban,$ls_codestpro,
																	$ls_cuenta,$ls_procede,$ls_documento,
																	$ls_operacionpre,$la_security);
				}
				if($lb_valido)												
				{
				   $lb_valido=$in_classcmp->uf_update_bsf_spgcuentas($ldec_monto,$ls_operacionpre,$ls_codemp,
				                                                     $ls_codestpro,$ls_cuenta,$ld_fecha,$la_security);
				}*/
				if($lb_valido)
				{
					$in_classcmp->io_int_scg->io_sql->commit();
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="INSERT";
					if($in_classcmp->io_int_scg->is_log_transacciones!="")
					{
						$ls_desc_event=$in_classcmp->io_int_scg->is_log_transacciones."Inserto el movimiento presupuestario ".$ls_documento." con operacion".$ls_operacionpre." por un monto de ".$ldec_monto." para la cuenta ".$ls_cuenta." correspondiente a la estructura programatica ".$ls_est1."-".$ls_est2."-".$ls_est3."-".$ls_est4."-".$ls_est5;
					}
					else
					{
						$ls_desc_event="Inserto el movimiento presupuestario ".$ls_documento." con operacion".$ls_operacionpre." por un monto de ".$ldec_monto." para la cuenta ".$ls_cuenta." correspondiente a la estructura programatica ".$ls_est1."-".$ls_est2."-".$ls_est3."-".$ls_est4."-".$ls_est5."; para el comprobante ".$ls_comprobante." de fecha ".$ld_fecha;
					}
					$ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventana,$ls_desc_event);
					////////////////////////////////         SEGURIDAD               //////////////////////////////
				}
				else
				{
					$in_classcmp->io_int_scg->io_sql->rollback();
				}
				
			}
			else	
			{	
				$in_classcmp->io_int_scg->io_sql->rollback();
			}
		} 
		else
		{
			$ls_cuenta      = $_POST["txtcuenta"];
			$ls_est1        = $_POST["codestpro1"];
			$ls_est2        = $_POST["codestpro2"];
			$ls_est3        = $_POST["codestpro3"];
			if($li_estmodest==2)
			{
				$ls_est4        = $_POST["codestpro4"];
				$ls_est5        = $_POST["codestpro5"];
			}
			$ls_documento   = $_POST["txtdocumento"];
			$ls_denominacion= $_POST["txtdenominacion"];
			$ls_procede     = $_POST["txtprocedencia"];
			$ls_operacionpre= $_POST["txtoperacion"];
			$ld_monto       = $_POST["txtmonto"];
		}
		$in_classcmp->io_int_int->uf_sql_transaction($lb_valido);  		
		?>
		<script language="javascript">
			f=opener.document.form1;
			f.chrenfon.disabled="";
			f.operacion.value="CARGAR_DT";
			f.action="sigesp_spg_p_comprobante.php";
			f.submit();
		</script>		
		<?php 
		//---------------------------------------------------------------------------------------
		$ls_redfon     =$_POST["renfon"];
		if ($ls_redfon=='1')
		{
			$ls_numero=$in_classcmp->uf_buscar_documento($ls_empresa,$ls_comprobante,$ld_fecha);						
			if ($ls_numero>0)
			{
				$ls_documento=$ls_numero+1;
				$ls_documento=str_pad($ls_documento,15,"0",0);
			}
			else
			{
				$ls_documento=str_pad(1,15,"0",0);
			}
			$ls_valor="readonly";			
			$_POST["txtdocumento"]=$ls_documento;
		}
		else
		{
			$ls_documento=$_POST["txtdocumento"];		
			$ls_valor="";
		}
		//----------------------------------------------------------------------------------------     
	}	
}

if($ls_operacion=="VALIDAR_CUENTA_PRESUPUESTARIA")
{
	$ls_cuenta      = $_POST["txtcuenta"];
	$ls_est1        = $_POST["codestpro1"];
	$ls_est2        = $_POST["codestpro2"];
	$ls_est3        = $_POST["codestpro3"];
	$arr_cmp["codemp"] = $dat["codemp"];
    $ls_estcla = $_POST["estcla"];

	if($li_estmodest==2)
	{
		$ls_est4        = $_POST["codestpro4"];
		$ls_est5        = $_POST["codestpro5"];
		$ls_est1=$io_function->uf_cerosizquierda($ls_est1,25);
		$ls_est2=$io_function->uf_cerosizquierda($ls_est2,25);
		$ls_est3=$io_function->uf_cerosizquierda($ls_est3,25);
		$ls_est4=$io_function->uf_cerosizquierda($ls_est4,25);
		$ls_est5=$io_function->uf_cerosizquierda($ls_est5,25);
	}
	else
	{
		$ls_est4        = $io_function->uf_cerosizquierda(0,25);
		$ls_est5        = $io_function->uf_cerosizquierda(0,25);
	}
    $lb_valido=$in_classcmp->uf_validar_cuenta_presupuestaria($arr_cmp,$ls_est1,$ls_est2,$ls_est3,$ls_est4,$ls_est5,$ls_cuenta);
    if (!$lb_valido)
	{
	   $io_msg->message(" La Cuenta  ".$ls_cuenta."  no existe" ); 
	   $$ls_cuentaplan = "";
	}
	else
	{
		$$ls_cuentaplan = $_POST["txtcuenta"];
		$ls_est1        = $_POST["codestpro1"];
		$ls_est2        = $_POST["codestpro2"];
		$ls_est3        = $_POST["codestpro3"];
        $ls_estcla = $_POST["estcla"];
		$ls_redfon     =$_POST["renfon"];
		$ls_codfuefin  =$_POST["codfuefin"]; 
		if($li_estmodest==2)
		{
			$ls_est4        = $_POST["codestpro4"];
			$ls_est5        = $_POST["codestpro5"];
		}
		$ls_documento   = $_POST["txtdocumento"];
		$ls_denominacion= $_POST["txtdenominacion"];
		$ls_procede     = $_POST["txtprocedencia"];
		$ls_operacionpre= $_POST["txtoperacion"];
		$ld_monto       = $_POST["txtmonto"];
	}
}

switch ($ls_operacion) {
   case 'AAP':
       $ls_apertura="selected";
       $ls_aumento="";
       $ls_disminucion="";
       $ls_precompromiso="";	   
       $ls_compromiso="";
       $ls_compromisogastocausado="";
       $ls_gastocausado="";
       $ls_causadopago="";
       $ls_pago="";
       $ls_compromisocausasopago="";	   	   	   
       break;
   case 'AU':
       $ls_apertura="";
       $ls_aumento="selected";
       $ls_disminucion="";
       $ls_precompromiso="";	   
       $ls_compromiso="";
       $ls_compromisogastocausado="";
       $ls_gastocausado="";
       $ls_causadopago="";
       $ls_pago="";
       $ls_compromisocausasopago="";	   	   	   
       break;
   case 'DI':
       $ls_apertura="";
       $ls_aumento="";
       $ls_disminucion="selected";
       $ls_precompromiso="";	   
       $ls_compromiso="";
       $ls_compromisogastocausado=""; 
       $ls_gastocausado="";
       $ls_causadopago="";
       $ls_pago="";
       $ls_compromisocausasopago="";	   	   	   
       break;
	case 'PC':
       $ls_apertura="";
       $ls_aumento="";
       $ls_disminucion="";
       $ls_precompromiso="selected";	   
       $ls_compromiso="";
       $ls_compromisogastocausado="";
	   $ls_gastocausado="";
       $ls_causadopago="";
       $ls_pago="";
       $ls_compromisocausasopago="";	   	   	   
	   break;
	case 'CS':   
       $ls_apertura="";
       $ls_aumento="";
       $ls_disminucion="";
       $ls_precompromiso="";	   
       $ls_compromiso="selected";
	   $ls_compromisogastocausado="";	   
       $ls_gastocausado="";
       $ls_causadopago="";
       $ls_pago="";
       $ls_compromisocausasopago="";	   	   	   
	   break;
	case 'CG': 
       $ls_apertura="";
       $ls_aumento="";
       $ls_disminucion="";
       $ls_precompromiso="";	   
       $ls_compromiso="";
	   $ls_compromisogastocausado="selected";
       $ls_gastocausado="";
       $ls_causadopago="";
       $ls_pago="";
       $ls_compromisocausasopago="";	   	   	   
	   break;
	case 'GC':
       $ls_apertura="";
       $ls_aumento="";
       $ls_disminucion="";
       $ls_precompromiso="";	   
       $ls_compromiso="";
       $ls_compromisogastocausado="";
	   $ls_gastocausado="selected";
       $ls_causadopago="";
       $ls_pago="";
       $ls_compromisocausasopago="";	   	   	   
	   break;   
	case 'CP':
       $ls_apertura="";
       $ls_aumento="";
       $ls_disminucion="";
       $ls_precompromiso="";	   
       $ls_compromiso="";
       $ls_compromisogastocausado="";
       $ls_gastocausado="";
       $ls_causadopago="selected";
       $ls_pago="";
       $ls_compromisocausasopago="";	   	   	   
	   break;
	case 'PG':
       $ls_apertura="";
       $ls_aumento="";
       $ls_disminucion="";
       $ls_precompromiso="";	   
       $ls_compromiso="";
       $ls_compromisogastocausado="";
	   $ls_gastocausado="";
       $ls_causadopago="";
       $ls_pago="selected";
       $ls_compromisocausasopago="";	   	   	   
	   break;
	case 'CCP':
       $ls_apertura="";
       $ls_aumento="";
       $ls_disminucion="";
       $ls_precompromiso="";	   
       $ls_compromiso="";
	   $ls_compromisogastocausado="";
       $ls_gastocausado="";
       $ls_causadopago="";
       $ls_pago="";
       $ls_compromisocausasopago="selected";	   	   	   
	   break;
    default:
	   $ls_apertura="";
       $ls_aumento="";
       $ls_disminucion="";
       $ls_precompromiso="";	   
       $ls_compromiso="";
	   $ls_compromisogastocausado="selected";
       $ls_gastocausado="";
       $ls_causadopago="";
       $ls_pago="";
       $ls_compromisocausasopago="";	   	   	   
	   break;
}
?>
<form method="post" name="form1" action="" id="sigesp_w_regdt_presupuesto_2"> 
<table width="583" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
   <td height="22" colspan="2" class="titulo-celda">Entrada de Comprobante de Gastos </td>
  </tr>
  <tr>
    <td height="13">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="119" height="22" align="right">Documento</td>
    <td width="450"><input name="txtdocumento" type="text" id="txtdocumento" style="text-align:center" onBlur="javascript:valid_cmp(this);" size="22" maxlength="15" value="<?php print str_pad($ls_documento,15,"0",0); ?>" <?php print $ls_valor;?> onChange="javascript:valid_cmp(this);"></td>
  </tr>
  <tr>
    <td height="22" align="right">Descripci&oacute;n</td>
    <td><input name="txtdescripcion" type="text" id="txtdescripcion" size="80" maxlength="100" style="text-align:left" value="<?php print $ls_descripcion;?>"></td>
  </tr>
  <tr>
    <td height="22" align="right">Procedencia</td>
    <td><input name="txtprocedencia" type="text" id="txtprocedencia" size="22" maxlength="6" style="text-align:center" value="<?php print $ls_procedencia;?>" readonly></td>
  </tr>
   <tr>
    <?php 
	  $li_estmodest  = $dat["estmodest"];
	  $ls_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
	  $ls_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
	  $ls_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
	  $ls_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
	  $ls_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
	  /*if($li_estmodest==1)
	  {*/
	  ?>
    <td height="22"><div align="right"><?php print $dat["nomestpro1"];  ?></div></td>
    <td>
      <input name="codestpro1" type="text" id="codestpro1" size="<?php print $ls_loncodestpro1; ?>" maxlength="<?php print $ls_loncodestpro1; ?>" style="text-align:center" value="<?php print $ls_estpro1; ?>" readonly>
      <a href="javascript:catalogo_estpro1();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Catálogo de Estructura Programatica 1"></a>      <input name="denestpro1" type="text" class="sin-borde" id="denestpro1" size="53" readonly>     
      <div align="left">      </div></td>
  </tr>
  <tr>
    <td height="22"><div align="right"><?php print $dat["nomestpro2"] ; ?></div>      </td>
    <td><input name="codestpro2" type="text" id="codestpro2" size="<?php print $ls_loncodestpro2; ?>" maxlength="<?php print $ls_loncodestpro2; ?>" style="text-align:center" value="<?php print $ls_estpro2; ?>" readonly>
      <a href="javascript:catalogo_estpro2();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Catálogo de Estructura Programatica 2"></a>
      <input name="denestpro2" type="text" class="sin-borde" id="denestpro2" size="53" readonly></td>
  </tr>
  <tr>
    <td height="22"><div align="right"><?php print $dat["nomestpro3"] ; ?></div></td>
    <td>      <div align="left">
      <input name="codestpro3" type="text" id="codestpro3" size="<?php print $ls_loncodestpro3; ?>" maxlength="<?php print $ls_loncodestpro3; ?>" style="text-align:center" value="<?php print $ls_estpro3; ?>" readonly>
      <a href="javascript:catalogo_estpro3();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Catálogo de Estructura Programatica 3"></a>
      <input name="denestpro3" type="text" class="sin-borde" id="denestpro3" size="53" readonly>
      </div></td>
  </tr>
	<?php
	 if($li_estmodest==2)
	 {	
	?>
  <tr>
    <td height="22"><div align="right"><?php print $dat["nomestpro4"] ; ?></div></td>
    <td><div align="left">
        <input name="codestpro4" type="text" id="codestpro4" size="<?php print $ls_loncodestpro4; ?>" maxlength="<?php print $ls_loncodestpro4; ?>" style="text-align:center" value="<?php print $ls_estpro4; ?>"readonly>
        <a href="javascript:catalogo_estpro4();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a>
        <input name="denestpro4" type="text" class="sin-borde" id="denestpro4" size="53" readonly>
    </div></td>
  </tr>
  <tr>
    <td height="22"><div align="right"><?php print $dat["nomestpro5"] ; ?></div></td>
    <td><div align="left">
        <input name="codestpro5" type="text" id="codestpro5" size="<?php print $ls_loncodestpro5 ?>" maxlength="<?php print $ls_loncodestpro5 ?>" style="text-align:center" value="<?php print $ls_estpro5; ?>" readonly>
        <a href="javascript:catalogo_estpro5();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 3"></a>
        <input name="denestpro5" type="text" class="sin-borde" id="denestpro5" size="53" readonly>
    </div></td>
  </tr>
	<?php  
	 }
	 ?>
  <tr>
    <td height="22"><div align="right">Cuenta</div></td>
    <td><input name="txtcuenta" type="text" id="txtcuenta" readonly="true" value="<?php print $ls_cuentaplan ?>" size="22" style="text-align:center" onKeyPress="return keyRestrict(event,'1234567890');" onBlur="javascript:validar_cuenta();"> 
    <a href="javascript:catalogo_cuentasSPG();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Catálogo de Cuentas de Gastos"></a>	 <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion3" style="text-align:left" value="<?php print $ls_denominacion ?>" size="50" maxlength="254"></td>
  </tr>
  <tr>
    <td height="22"><div align="right">Operaci&oacute;n</div></td>
    <td><div align="left">
      <select name="txtoperacion" id="txtoperacion" style="width:200px">
        <option value="PC"  <?php print $ls_precompromiso;?>>PRE-COMPROMISO</option>
        <option value="CS"  <?php print $ls_compromiso;?>>COMPROMISO SIMPLE</option>
        <option value="CG"  <?php print $ls_compromisogastocausado;?>>COMPROMISO Y GASTO CAUSADO</option>
        <option value="GC"  <?php print $ls_gastocausado;?>>GASTO CAUSADO</option>
        <option value="CP"  <?php print $ls_causadopago;?>>GASTO CAUSADO Y PAGO</option>
        <option value="PG"  <?php print $ls_pago;?>>PAGO</option>
        <option value="CCP" <?php print $ls_compromisocausasopago;?>>COMPROMISO,CAUSADO Y PAGADO</option>
      </select>
    </div></td>
  </tr>
  <tr>
    <td height="22" align="right">Monto</td>
    <td><input name="txtmonto" type="text" id="txtmonto" style="text-align:right" size="22" onKeyPress="javascript:return(currency_Format(this,'.',',',event))"> 
      <a href="javascript:aceptar_presupuestario();"><img src="../shared/imagebank/tools15/aprobado.gif" alt="Agregar Detalle Presupuestario" width="15" height="15" border="0"></a> <a href="javascript: close();"><img src="../shared/imagebank/eliminar.gif" alt="Cancelar Registro de Detalle Presupuestario" width="15" height="15" border="0"></a></td>
  </tr>
  <tr>
    <td height="22">&nbsp;</td>
    <td><input name="operacion" type="hidden" id="operacion">
      <input name="comprobante" type="hidden" id="comprobante" value="<?php print $ls_comprobante;?>">
      <input name="procede" type="hidden" id="procede" value="<?php print $ls_proccomp;?>">
      <input name="fecha" type="hidden" id="fecha" value="<?php print $ld_fecha;?>">
      <input name="provbene" type="hidden" id="provbene" value="<?php print $ls_provbene;?>">
      <input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo;?>">
      <input name="descripcion" type="hidden" id="descripcion" value="<?php print $ls_desccomp;?>">
      <input name="estmodest" type="hidden" id="estmodest" value="<?php print $li_estmodest; ?>">
      <input name="estcla"    type="hidden" id="estcla" value="<?php print $ls_estcla; ?>">
	  <input name="renfon"    type="hidden" id="renfon" value="<?php print $ls_redfon; ?>">
	  <input name="codfuefin"    type="hidden" id="codfuefin" value="<?php print $ls_codfuefin; ?>">
	  <input name="filapre"    type="hidden" id="filapre" value="<?php print $ls_filapre; ?>">
	  <input name="totcont"    type="hidden" id="totcont" value="<?php print $ls_filacont; ?>">
	  <input name="txtscg"    type="hidden" id="txtscg" value="<?php print $ls_txtscg; ?>">
      </td>
  </tr>
</table>
</form>
</body>
<script language="JavaScript">

  //----------------------------------------	
		function lTrim(sStr)
		{
			 while (sStr.charAt(0) == " ")
		     sStr = sStr.substr(1, sStr.length - 1);
			 return sStr;
		}	 
		
		function rTrim(sStr)
		{
			 while (sStr.charAt(sStr.length - 1) == " ")
		     sStr = sStr.substr(0, sStr.length - 1);
			 return sStr;
		}
		function allTrim(sStr){
		  return rTrim(lTrim(sStr));
		}
		
		function ue_formato_calculo(monto)
		{
			while(monto.indexOf('.')>0)
			{//Elimino todos los puntos o separadores de miles
				monto=monto.replace(".","");
			}
			monto=monto.replace(",",".");	
			return monto;
		}

  //----------------------------------------
  
  function aceptar_presupuestario()
  {
  	f=document.form1;
	ls_cuenta=f.txtcuenta.value;
	ls_estpro1=f.codestpro1.value;
	ls_estpro2=f.codestpro2.value;
	ls_estpro3=f.codestpro3.value;
	ls_descripcion=f.txtdescripcion.value;
	ls_procedencia=f.txtprocedencia.value;
	ls_documento=f.txtdocumento.value;
	ls_operacion=f.txtoperacion.value;
	ldec_monto=f.txtmonto.value;
	estmodest   = f.estmodest.value;
	estcla=f.estcla.value;
	renfon= f.renfon.value;
	ls_fecha= f.fecha.value;
	comprobante=f.comprobante.value;
	if((ls_cuenta!="")&&(ls_estpro1!="")&&(ls_estpro2!="")&&(ls_estpro3!="")&&(ls_descripcion!="")&&(ls_procedencia!="")&&(ls_documento!="")&&(ls_operacion!="")&&(ldec_monto!=""))
	{
		if(estmodest==1)
		{
			programatica=ls_estpro1+"-"+ls_estpro2+"-"+ls_estpro3;		
		}
		else
		{
			ls_estpro4=f.codestpro4.value;
			ls_estpro5=f.codestpro5.value;
			programatica=ls_estpro1+"-"+ls_estpro2+"-"+ls_estpro3+"-"+ls_estpro4+"-"+ls_estpro5;	
		}
		//alert(programatica);
		///---------EN LOTE-------------------------------------------------------------------
			//filapre   =   f.filapre.value;
			/*filapre   =  opener.document.form1.totpre.value;
			if (filapre==1)
			{
			   if(ExisteObjetoDestino(opener.document.form1.txtcuenta1))
			   {
			    filaprenew=2;
			   }
			   else
			   {
			    filaprenew=filapre;
			   } 
			}
			else
			{
			 if(!ExisteObjetoDestino(eval("opener.document.form1.txtcuenta"+filapre)))
			 {
			  filaprenew=eval(filapre);
			 }
			 else
			 {
			  filaprenew=eval(filapre+"+1");
			 } 
			}
			if(filaprenew==0)
			{
			 filaprenew=eval(filaprenew+"+1");
			}*/
			filaprenew=ue_calcular_total_fila_opener("txtcuenta");
			filaprenew=eval(filaprenew+1);
			filacont  =   f.totcont.value;
			scg=f.txtscg.value; 
			valido=true;
			for(j=1;(j<filaprenew)&&(valido);j++)
			{
				cuentagrid=eval("opener.document.form1.txtcuenta"+j+".value");
				cuentagrid=allTrim(cuentagrid);
				programaticagrid=eval("opener.document.form1.txtprogramatico"+j+".value");
				programaticagrid=allTrim(programaticagrid);
				docuemntogrid=eval("opener.document.form1.txtdocumento"+j+".value");
				docuemntogrid=allTrim(docuemntogrid);
				
				ls_cuenta=allTrim(ls_cuenta);
				programatica=allTrim(programatica);
				ls_documento=allTrim(ls_documento);
				//alert(programaticagrid+" "+programatica);
				
				if ((cuentagrid==ls_cuenta)&&(programaticagrid==programatica)&&(docuemntogrid==ls_documento))
				{
					alert("El Detalle presupuestario ya existe...");
					valido=false;				
				}
			}		  
		//////////////////CARGAR LOS DETALLES PRESUPUESTARIOS///////////////////////////////
			parametros="";
			filavacia=0;
			for(i=1;(i<filaprenew)&&(valido);i++)
			{
				cuenta1=eval("opener.document.form1.txtcuenta"+i+".value");
				programatica1=eval("opener.document.form1.txtprogramatico"+i+".value");
				documento1=eval("opener.document.form1.txtdocumento"+i+".value");
				descrip1=eval("opener.document.form1.txtdescripcion"+i+".value");
				procede1=eval("opener.document.form1.txtprocede"+i+".value");
				operacion1=eval("opener.document.form1.txtoperacion"+i+".value");
				monto1=eval("opener.document.form1.txtmonto"+i+".value");
				estcla1=eval("opener.document.form1.txtestcla"+i+".value");
				ls_scg1=eval("opener.document.form1.txtscgcta"+i+".value");
				
				parametros=parametros+"&txtcuenta"+i+"="+cuenta1+"&txtprogramtico"+i+"="+programatica1+""+
					   "&txtdocumento"+i+"="+documento1+"&txtdescripcion"+i+"="+descrip1+""+
					   "&txtprocede"+i+"="+procede1+"&txtoperacion"+i+"="+operacion1+""+
					   "&txtmonto"+i+"="+monto1+"&txtestcla"+i+"="+estcla1+"&scgcta"+i+"="+ls_scg1;		   
			}// fin del for
			//totaldetalles=eval(filapre+"+1");
			totaldetalles=eval(filaprenew);
			parametros=parametros+"&txtcuenta"+filaprenew+"="+ls_cuenta+"&txtprogramtico"+filaprenew+"="+programatica+""+
					   "&txtdocumento"+filaprenew+"="+ls_documento+"&txtdescripcion"+filaprenew+"="+ls_descripcion+""+
					   "&txtprocede"+filaprenew+"="+ls_procedencia+"&txtoperacion"+filaprenew+"="+ls_operacion+""+
					   "&txtmonto"+filaprenew+"="+ldec_monto+"&txtestcla"+filaprenew+"="+estcla+"&scgcta"+filaprenew+"="+scg+"&totaldetalles="+totaldetalles+"&estmodest="+estmodest+"&fecha="+ls_fecha+"&comprobante="+comprobante;
					   
			/*parametros=parametros+"&txtcuenta"+filapre+"="+ls_cuenta+"&txtprogramtico"+filapre+"="+programatica+""+
					   "&txtdocumento"+filapre+"="+ls_documento+"&txtdescripcion"+filapre+"="+ls_descripcion+""+
					   "&txtprocede"+filapre+"="+ls_procedencia+"&txtoperacion"+filapre+"="+ls_operacion+""+
					   "&txtmonto"+filapre+"="+ldec_monto+"&txtestcla"+filapre+"="+estcla+"&scgcta"+filapre+"="+scg+"&totaldetalles="+totaldetalles+"&estmodest="+estmodest+"&fecha="+ls_fecha+"&comprobante="+comprobante;*/		   
			
		////////////////////////////////////////////////////////////////////////////////////
		
		///////////////CARGA LOS DETALLES CONTABLES/////////////////////////////////////////
		cuentascg=f.txtscg.value;
		docscg=f.txtdocumento.value;
		desdoc=f.txtdescripcion.value;
		montocont=f.txtmonto.value;
		montoper=ue_formato_calculo(montocont)				
		if ((parseFloat(montoper)) > (parseFloat(0.00)))
		{
		
			debhab='D';
		}
		else
		{
			debhab='H';	
			montocont=montocont.replace(/-/,"");								
		}
		
		procdoc=f.txtprocedencia.value;
		encontrado=true;
			filacont=ue_calcular_total_fila_opener("txtcontable");
			filacont=eval(filacont+1);
		 for (k=1;k<filacont;k++)
		    {
			  cuentascg1 = eval("opener.document.form1.txtcontable"+k+".value"); 
			  docscg1	 = eval("opener.document.form1.txtdocscg"+k+".value");
			  desdoc1	 = eval("opener.document.form1.txtdesdoc"+k+".value");
			  procdoc1   = eval("opener.document.form1.txtprocdoc"+k+".value");
			  debhab1	 = eval("opener.document.form1.txtdebhab"+k+".value");
			  montocont1 = eval("opener.document.form1.txtmontocont"+k+".value");
			  cuentascg1 = allTrim(cuentascg1);
			  docscg1    = allTrim(docscg1);				
			  if ((cuentascg1==cuentascg)&&(docscg1==docscg)&&(valido))
				 { 
				   ld_mondetcon  = ue_formato_calculo(montocont1);
				   ld_montotcon  = ue_formato_calculo(montocont);
					if (debhab1 == debhab)
					{
						montocont1    = parseFloat(ld_mondetcon)+parseFloat(ld_montotcon);
					}
					else
					{
						montocont1    = parseFloat(ld_mondetcon)-parseFloat(ld_montotcon);
						if (montocont1<0)
						{
							montocont1=montocont1*(-1);
							if(debhab1=='H')
							{
								debhab1='D';
							}
							else
							{
								if(debhab1=='D')
								{
									debhab1='H';
								}
							}
						}
					}
			       montocont1    = uf_convertir(montocont1);
				   eval("opener.document.form1.txtmontocont"+k+".value='"+montocont1+"'");
				   encontrado   = false;
				 }			
			    parametros=parametros+"&txtcontable"+k+"="+cuentascg1+"&txtdocscg"+k+"="+docscg1+""+
					   "&txtdesdoc"+k+"="+desdoc1+"&txtprocdoc"+k+"="+procdoc1+""+
					   "&txtdebhab"+k+"="+debhab1+"&txtmontocont"+k+"="+montocont1;		   	   
		   }// fin del for	   
		   if (encontrado===true)
		      { 
			    if ((ls_operacion != "CS")&&(ls_operacion != "PC"))
				{
				totaldetallescont=eval(filacont+1);
			    parametros =parametros+"&txtcontable"+filacont+"="+cuentascg+"&txtdocscg"+filacont+"="+docscg+""+
						    "&txtdesdoc"+filacont+"="+desdoc+"&txtprocdoc"+filacont+"="+procdoc+""+
						    "&txtdebhab"+filacont+"="+debhab+"&txtmontocont"+filacont+"="+montocont;
				}
				else
				{
				 totaldetallescont = filacont;
				}						
			  }
		   else
		      {
			    totaldetallescont = filacont;
			  }	
			parametros=parametros+"&totaldetallescont="+totaldetallescont;
		///////////////////////////////////////////////////////////////////////////////////
			if ((parametros!="")&&(valido))
			{
				// Div donde se van a cargar los resultados
				opener.document.form1.totpre.value=totaldetalles;
				opener.document.form1.totcon.value=totaldetallescont;
					divgrid = opener.document.getElementById("detallespres");
				// Instancia del Objeto AJAX
				ajax=objetoAjax();
				// Pagina donde están los métodos para buscar y pintar los resultados
				ajax.open("POST","class_folder/sigesp_spg_c_comprobante_ajax.php",true);
				ajax.onreadystatechange=function()
				{
					if(ajax.readyState==4)
					{
						if(ajax.status==200)
						{//mostramos los datos dentro del contenedor
							divgrid.innerHTML = ajax.responseText
						}
						else
						{
							if(ajax.status==404)
							{
								divgrid.innerHTML = "La página no existe";
							}
							else
							{//mostramos el posible error     
								divgrid.innerHTML = "Error:".ajax.status;
							}
						}					
					}// fin del if
				}// fin del la funcion ajax
				ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
				// Enviar todos los campos a la pagina para que haga el procesamiento
				ajax.send("proceso=AGREGARDETALLES"+parametros);
				opener.document.form1.totpre.value=totaldetalles;
				opener.document.form1.totcon.value=totaldetallescont;
				document.form1.filapre.value=totaldetalles;	
				document.form1.totcont.value=totaldetallescont;	
				if (renfon==1)
				{
					valor=parseInt(ls_documento)+1;
					document.form1.txtdocumento.value=valor;
				    valor2= document.form1.txtdocumento.value;
					valor3=rellenar_cad(valor2,15,"doc"); 
					document.form1.txtdocumento.value=valor3;
				}		
			}//FIN del if
	 }
	 else
	 {
	 	alert("Complete los detalles del movimiento");
	 }		
  }//fin de la funcion  
  
  
  function uf_close()
  {
	  close()
  }
	
function valid_cmp(cmp)
{
	if((cmp.value==0)||(cmp.value==""))
	{
	alert("Introduzca un numero comprobante valido");
	cmp.focus();
	}
	else
	{
	rellenar_cad(cmp.value,15,"doc");
	}
}

function validar_cuenta()
{
  	f=document.form1;
	ls_cuenta=f.txtcuenta.value;
	ls_estpro1=f.codestpro1.value;
	ls_estpro2=f.codestpro2.value;
	ls_estpro3=f.codestpro3.value;
	estmodest   = f.estmodest.value;
    if(estmodest==1)
	{
		if((ls_cuenta!="")&&(ls_estpro1!="")&&(ls_estpro2!="")&&(ls_estpro3!=""))
		{
			f.operacion.value="VALIDAR_CUENTA_PRESUPUESTARIA";
			f.action="sigesp_w_regdt_presupuesto.php";
			f.submit();
		}
		else
		{
		  alert(" La Estructura Presupuestaria no puede estar vacia");
		}
	}
	else
	{
		ls_estpro4=f.codestpro4.value;
		ls_estpro5=f.codestpro5.value;
		if((ls_cuenta!="")&&(ls_estpro1!="")&&(ls_estpro2!="")&&(ls_estpro3!="")&&(ls_estpro4!="")&&(ls_estpro5!=""))
		{
			f.operacion.value="VALIDAR_CUENTA_PRESUPUESTARIA";
			f.action="sigesp_w_regdt_presupuesto.php";
			f.submit();
		}
		else
		{
		  alert(" La Estructura Presupuestaria no puede estar vacia");
		}

	}	
}
//Funciones de validacion de fecha.
function rellenar_cad(cadena,longitud,campo)
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
	else
	{
		document.form1.txtcomprobante.value=cadena;
	}
    return cadena;
}

 function catalogo_cuentasSPG()
 {
	   f=document.form1;
	   codest1 = f.codestpro1.value;
	   codest2 = f.codestpro2.value;
	   codest3 = f.codestpro3.value;
       estmodest = f.estmodest.value;
       estcla = f.estcla.value;
	   if(estmodest==1)
	   {
		   if((codest1!="")&&(codest2!="")&&(codest3!=""))
		   {
			   pagina="sigesp_cat_ctasspg.php?opera=sigesp_w_regdt-presupuesto_2&codestpro1="+codest1+"&hicodest2="+codest2+"&hicodest3="+codest3+"&estcla="+estcla;
			   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=700,height=400,resizable=yes,location=no");
		   }
		   else
		   {
			   alert("Debe completar la Estructura Presupuestaria");
		   }
	   }	   
	   else if(estmodest==2)
	   {
		   codest4=f.codestpro4.value;
		   codest5=f.codestpro5.value;
		   if((codest1!="")&&(codest2!="")&&(codest3!="")&&(codest4!="")&&(codest5!=""))
		   {
			   pagina="sigesp_cat_ctasspg.php?codestpro1="+codest1+"&hicodest2="+codest2+"&hicodest3="+codest3+"&hicodest4="+codest4
			   +"&hicodest5="+codest5+"&estcla="+estcla;
			   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=760,height=400,resizable=yes,location=no");
		   }
		   else
		   {
			   alert("Debe completar la programatica");
		   }
	  }
 }
 
function catalogo_estpro1()
{
	   pagina="sigesp_cat_public_estpro1.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
}
function catalogo_estpro2()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	denestpro1=f.denestpro1.value;
	estcla=f.estcla.value;
	if((codestpro1!="")&&(denestpro1!=""))
	{
		pagina="sigesp_cat_public_estpro2.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&estcla="+estcla;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura nivel 1");
	}
}
function catalogo_estpro3()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	denestpro1=f.denestpro1.value;
	codestpro2=f.codestpro2.value;
	denestpro2=f.denestpro2.value;
	codestpro3=f.codestpro3.value;
	denestpro3=f.denestpro3.value;
	estmodest=f.estmodest.value;
	estcla=f.estcla.value;
	if(estmodest==1)
	{
		if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!="")&&(codestpro3=="")&&(denestpro3==""))
		{
			pagina="sigesp_cat_public_estpro3.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2
					+"&denestpro2="+denestpro2+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
			pagina="sigesp_cat_public_estpro.php";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
	}
	else
	{
		if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!=""))
		{
			pagina="sigesp_cat_public_estpro3.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2
					+"&denestpro2="+denestpro2+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
		}
		else
		{
		   alert("Seleccione la Estructura nivel 2");
		}
	}
}
function catalogo_estpro4()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	denestpro1=f.denestpro1.value;
	codestpro2=f.codestpro2.value;
	denestpro2=f.denestpro2.value;
	codestpro3=f.codestpro3.value;
	denestpro3=f.denestpro3.value;
	estcla=f.estcla.value;
	if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!="")&&(codestpro3!="")&&(denestpro3!=""))
	{
			pagina="sigesp_cat_public_estpro4.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2
					+"&denestpro2="+denestpro2+"&codestpro3="+codestpro3+"&denestpro3="+denestpro3+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura nivel 3 ");
	}
}
function catalogo_estpro5()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	denestpro1=f.denestpro1.value;
	codestpro2=f.codestpro2.value;
	denestpro2=f.denestpro2.value;
	codestpro3=f.codestpro3.value;
	denestpro3=f.denestpro3.value;
	codestpro4=f.codestpro4.value;
	denestpro4=f.denestpro4.value;
	codestpro5=f.codestpro5.value;
	denestpro5=f.denestpro5.value;
	estcla=f.estcla.value;
	if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!="")&&(codestpro3!="")&&(denestpro3!="")&&
	   (codestpro4!="")&&(denestpro4!="")&&(codestpro5=="")&&(denestpro5==""))
	{
			pagina="sigesp_cat_public_estpro5.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2
					+"&denestpro2="+denestpro2+"&codestpro3="+codestpro3+"&denestpro3="+denestpro3+"&codestpro4="+codestpro4
					+"&denestpro4="+denestpro4+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
	else
	{
			pagina="sigesp_cat_public_estprograma.php";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
}
function  uf_format(obj)
{
	ldec_monto=obj.value;
	obj.value=uf_convertir(ldec_monto);
}

function ExisteObjetoDestino(objeto)
{
if (!objeto) {
    return false;
} 
else {
    return true;
}
}


function currency_Format(fld, milSep, decSep, e) { 
    var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789-'; 
    var aux = aux2 = ''; 
    var whichCode = (window.Event) ? e.which : e.keyCode; 
    if (whichCode == 13) return true; // Enter 
	if (whichCode == 8) return true; // Enter 
	if (whichCode == 127) return true; // Enter 	
	if (whichCode == 9) return true; // Enter 	
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
	 
	 // Se agrego por lo del negativo
	 valorfinal =  fld.value ;
	 valorfinal = valorfinal.split('-.').join('-');
	 fld.value=valorfinal ;
	/////////////////// 
    } 
    return false; 
   }
   
function ue_calcular_total_fila_opener(campo)
{
	existe=true;
	li_i=1;
	while(existe)
	{
		existe=opener.document.getElementById(campo+li_i);
		if(existe!=null)
		{
			li_i=li_i+1;
		}
		else
		{
			existe=false;
			li_i=li_i-1;
		}
	}
	return li_i;
}

</script>
</html>
