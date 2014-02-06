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

$la_empresa		  = $_SESSION["la_empresa"];
$li_loncodestpro1 = $la_empresa["loncodestpro1"];
$li_loncodestpro2 = $la_empresa["loncodestpro2"];
$li_loncodestpro3 = $la_empresa["loncodestpro3"];
$li_loncodestpro4 = $la_empresa["loncodestpro4"];
$li_loncodestpro5 = $la_empresa["loncodestpro5"];

$li_size1 = $li_loncodestpro1+10;
$li_size2 = $li_loncodestpro2+10;
$li_size3 = $li_loncodestpro3+10;
$li_size4 = $li_loncodestpro4+10;
$li_size5 = $li_loncodestpro5+10;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Entrada de Comprobante de Gastos</title>
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
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<style type="text/css">
<!--
.style2 {font-size: 11px}
-->
</style>
</head>
<body>
<?php
require_once("sigesp_scb_c_ordenpago.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/sigesp_c_seguridad.php");

$io_msg     = new class_mensajes();
$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_funcion = new class_funciones();
$io_sql     = new class_sql($ls_conect);

$io_seguridad = new sigesp_c_seguridad();
$la_empresa   = $_SESSION["la_empresa"];
$ls_empresa   = $la_empresa["codemp"];
$in_classmovorden=new sigesp_scb_c_ordenpago($la_seguridad);
   
if (array_key_exists("operacion",$_POST))
{
    $ls_operacion = $_POST["operacion"];
	$ls_estcla    = $_POST["hidestcla"];
	$ls_spgcta    = $_POST["txtcuenta"];
	$ls_denspgcta = $_POST["txtdenominacion"];
    $ls_estpro1   = $_POST["codestpro1"];
	$ls_estpro2   = $_POST["codestpro2"];
	$ls_estpro3   = $_POST["codestpro3"];
	$ls_denestpro1 =$_POST["denestpro1"];
	$ls_denestpro2 =$_POST["denestpro2"];
	$ls_denestpro3 =$_POST["denestpro3"];
    if ($_SESSION["la_empresa"]["estmodest"]==2)
	   {
		 $ls_estpro4   = $_POST["codestpro4"];
		 $ls_estpro5   = $_POST["codestpro5"];
		 $ls_denestpro4 =$_POST["denestpro4"];
		 $ls_denestpro5 =$_POST["denestpro5"];
	   }
	$ls_procedencia=$_POST["txtprocedencia"];
	$ls_descripcion=$_POST["txtdescripcion"];
	$ls_comprobante=$_POST["comprobante"];
	$ls_proccomp   =$_POST["procede"];
	$ls_desccomp   =$_POST["descripcion"];
	$ld_fecha	   =$_POST["fecha"];
	$ls_tipo       =$_POST["tipo"];
	$ls_provbene   =$_POST["provbene"];
	$ls_mov_document=$_POST["mov_document"];
	$ls_mov_procede=$_POST["procede"];
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
	$ls_afectacion =$_POST["txtafectacion"];
	$ls_tipdocres=$_POST["tipdocres"];
	$ls_numdocres=$_POST["numdocres"];
	$ls_fecdocres=$_POST["fecdocres"];
	$ls_tipreg   =$_POST["tipreg"];
	$ls_fte_financiamiento=$_POST["ftefinancia"];
	$ls_origen=$_POST["origen"];
	$ls_coduniadm=$_POST["coduniadm"];
	$ls_uel=$_POST["txtcoduniadm"];
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
	$ls_nrocontrol =$_POST["nrocontrol"];
	$li_estserext = $_POST["hidserext"];
}
else
{
	$ls_operacion="";
    $ls_estpro1="";
	$ls_estpro2="";
	$ls_estpro3="";
	$ls_estpro4="";
	$ls_estpro5="";
	$ls_denestpro1 = "";
	$ls_denestpro2 = "";
	$ls_denestpro3 = "";
	$ls_denestpro4 = "";
	$ls_denestpro5 = "";
	$ls_spgcta = "";
	$ls_denspgcta = "";
	$ls_denominacion="";
	$ls_procedencia="SCBMOV";
	$ls_estcla = $_GET["hidestcla"];
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
	$ls_nrocontrol=$_GET["nrocontrol"];
	$li_estserext=$_GET["hidestserext"];
}

if($ls_operacion=="GUARDARPRE")
{
	$ldec_monto=$_POST["txtmonto"];
	$ls_estmov="N";

	if($ls_tipo=="P")
	{
		$ls_codpro =$ls_provbene;
		$ls_cedbene="----------";
	}
	else
	{
		$ls_cedbene=$ls_provbene;
		$ls_codpro ="----------";
	}
	
	$in_classmovorden->io_sql->begin_transaction();	
	$ld_fecdb=$io_funcion->uf_convertirdatetobd($ld_fecha);
	$lb_valido=$in_classmovorden->uf_guardar_automatico($ls_codban,$ls_ctaban,$ls_mov_document,$ls_codope,$ld_fecha,$ls_mov_descripcion,$ls_codconmov,$ls_codpro,$ls_cedbene,$ls_nomproben,$ldec_monto_mov,$ldec_objret,$ldec_retenido,$ls_chevau,$ls_estmov,$li_estint,$li_cobrapaga,$ls_estbpd,$ls_mov_procede,$ls_estreglib,$ls_estdoc,$ls_tipo,$ls_tipdocres,$ls_numdocres,$ls_fecdocres,$ls_tipreg,$ls_fte_financiamiento,$ls_origen,$ls_tippag,$ls_mediopago,$ls_modalidad,$ls_coduniadm,$ls_codbansig,$ls_estpro1,$ls_codbanbene,$ls_nombanbene,$ls_ctabanbene,$ls_codbanaut,$ls_nombanaut,$ls_ctabanaut,$ls_rifaut,$ls_nombreaut,$ls_nrocontrol,$li_estserext);
	$arr_movbco["codban"]=$ls_codban;
	$arr_movbco["ctaban"]=$ls_ctaban;
	$arr_movbco["mov_document"]=$ls_mov_document;
	$arr_movbco["codope"]=$ls_codope;
	$arr_movbco["fecha"]=$ld_fecdb;
	$arr_movbco["codpro"]=$ls_codpro;
	$arr_movbco["cedbene"]=$ls_cedbene;
	$arr_movbco["monto_mov"]=$ldec_monto_mov;
	$arr_movbco["objret"]   =$ldec_objret;
	$arr_movbco["retenido"] =$ldec_retenido;
	$arr_movbco["estmov"]=$ls_estmov;
	if($lb_valido)
	{		
		$ls_operacioncon="H";		
		$lb_valido=$in_classmovorden->uf_procesar_dt_contable($arr_movbco,$ls_cuenta_scg,$ls_mov_procede,$ls_mov_descripcion,$ls_mov_document,$ls_operacioncon,$ldec_monto_mov,$ldec_objret,true,'00000');
		$ls_cuenta      = $_POST["txtcuentascg"];
		$ls_denominacion= $_POST["txtdescripcion"];
		$ls_operacioncon= "D";
		$ld_monto       = $_POST["txtmonto"];
		$ldec_monto=str_replace(".","",$ld_monto);
		$ldec_monto=str_replace(",",".",$ldec_monto);
		if($lb_valido)
		{
			$lb_valido=$in_classmovorden->uf_procesar_dt_contable($arr_movbco,$ls_cuenta,$ls_mov_procede,$ls_descripcion,$ls_mov_document,$ls_operacioncon,$ldec_monto,$ldec_objret,false,'00000');
							 
			$ls_spgcuenta  = $_POST["txtcuenta"];
			$ls_codestpro1 = str_pad($_POST["codestpro1"],25,0,0);
			$ls_codestpro2 = str_pad($_POST["codestpro2"],25,0,0);
			$ls_codestpro3 = str_pad($_POST["codestpro3"],25,0,0);
			if ($la_empresa["estmodest"]==2)
			   {
				 $ls_codestpro4  = str_pad($_POST["codestpro4"],25,0,0);
				 $ls_codestpro5  = str_pad($_POST["codestpro5"],25,0,0);
			   }
			else
			   {
				 $ls_codestpro4 = $ls_codestpro5 = str_pad("",25,0,0);
			   }
			$ls_programa  =	$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
			$ls_desmov    = $_POST["txtdescripcion"];
			$ls_operacion = $_POST["txtafectacion"];
			$ldec_monto   = $_POST["txtmonto"];
			$ldec_monto   = str_replace(".","",$ldec_monto);
			$ldec_monto   = str_replace(",",".",$ldec_monto);
			$lb_valido    = $in_classmovorden->uf_procesar_dt_gasto($ls_codban,$ls_ctaban,$ls_mov_document,$ls_codope,$ls_estmov,$ls_programa,$ls_spgcuenta,$ls_mov_document,$ls_desmov,$ls_mov_procede,$ldec_monto,$ls_operacion,$ls_uel,0,'',$ls_estcla);
			if($lb_valido)
			{
				$in_classmovorden->io_sql->commit();
				$ls_estdoc='C';
				?>
				<script language="javascript">
					f=opener.document.form1;
					f.operacion.value="CARGAR_DT";
					f.status_doc.value='C';
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
		else
		{
			$io_msg->message($in_classmovorden->is_msg_error);
			$in_classmovorden->io_sql->rollback();
		}				
	} 	
	else
	{
		$io_msg->message($in_classmovorden->is_msg_error);
		$in_classmovorden->io_sql->rollback();
	}	
}
$ls_apertura="";
$ls_aumento="";
$ls_disminucion="";
$ls_precompromiso="";	   
$ls_compromiso="";
$ls_compromisogastocausado="";
$ls_gastocausado="";
$ls_causadopago="";
$ls_pago="";
$ls_compromisocausasopago="";	   	   	   

switch ($ls_operacion) {
   case 'AAP':
       $ls_apertura="selected";
       break;
   case 'AU':
       $ls_aumento="selected";
       break;
   case 'DI':
       $ls_disminucion="selected";
       break;
	case 'PC':
       $ls_precompromiso="selected";	   
	   break;
	case 'CS':   
       $ls_compromiso="selected";
	   break;
	case 'CG': 
	   $ls_compromisogastocausado="selected";
	   break;
	case 'GC':
	   $ls_gastocausado="selected";
	   break;   
	case 'CP':
       $ls_causadopago="selected";
	   break;
	case 'PG':
       $ls_pago="selected";
	   break;
	case 'CCP':
       $ls_compromisocausasopago="selected";	   	   	   
	   break;
    default:
	   $ls_compromisogastocausado="selected";
	   break;
}
 ?>
<form method="post" name="form1" action=""> 
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_banco->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_banco);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<table width="583" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
   <td height="21" colspan="2" class="titulo-celda">Entrada de Comprobante de Gastos </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="119" height="22" align="right">Documento</td>
    <td width="450" height="22"><div align="left">
      <input name="txtdocumento" type="text" id="txtdocumento" style="text-align:center" onBlur="javascript:valid_cmp(this);" size="22" maxlength="15" value="<?php print $ls_mov_document;?>" readonly>
    </div></td>
  </tr>
  <tr>
    <td height="22" align="right">Descripci&oacute;n</td>
    <td height="22"><div align="left">
      <input name="txtdescripcion" type="text" id="txtdescripcion" size="80" maxlength="100" style="text-align:left" value="<?php print $ls_descripcion;?>">
    </div></td>
  </tr>
  <tr>
    <td height="22" align="right">Procedencia</td>
    <td height="22"><div align="left">
      <input name="txtprocedencia" type="text" id="txtprocedencia" size="22" maxlength="6" style="text-align:center" value="<?php print $ls_procedencia;?>" readonly>
    </div></td>
  </tr>
   <tr>
     <td height="22" style="text-align:right">U.E.L.</td>
     <td height="22" style="text-align:left">
       <input name="txtcoduniadm" type="text" id="txtcoduniadm" size="22" readonly style="text-align:center">
       <a href="javascript:catunidadadm()"><img src="../shared/imagebank/tools15/buscar.gif" alt="Unidades Administrativas" name="bot_provbene" width="15" height="15" border="0" id="bot_provbene"></a>
       <input name="txtdenuniadm" id="txtdenuniadm" type="text" size="53" class="sin-borde" readonly>
       <input name="hidestcla" type="hidden" id="hidestcla" value="<?php echo $ls_estcla ?>">     </td>
   </tr>
   <tr>
    <td height="22" style="text-align:right"><?php print $la_empresa["nomestpro1"]; ?></td>
    <td height="22" style="text-align:left"><input name="codestpro1" type="text" id="codestpro1" style="text-align:center" value="<?php print $ls_estpro1;?>" size="<?php echo $li_size1 ?>" maxlength="<?php echo $li_loncodestpro1 ?>" readonly>
        <a href="javascript:catalogo_estpro1();"></a>      
        <input name="denestpro1" type="text" class="sin-borde" id="denestpro1" value="<?php print $ls_denestpro1;?>" size="50" readonly>     
      <div align="left">      </div></td>
  </tr>
  <tr>
    <td height="22" style="text-align:right"><?php print $la_empresa["nomestpro2"]; ?></td>
    <td height="22" style="text-align:left"><input name="codestpro2" type="text" id="codestpro2" style="text-align:center" value="<?php print $ls_estpro2;?>" size="<?php echo $li_size2 ?>" maxlength="<?php echo $li_loncodestpro2 ?>" readonly>
      <input name="denestpro2" type="text" class="sin-borde" id="denestpro2" value="<?php echo $ls_denestpro2 ?>" size="50" readonly>    </td>
  </tr>
  <tr>
    <td height="22" style="text-align:right"><?php print $la_empresa["nomestpro3"] ; ?></td>
    <td height="22" style="text-align:left"><input name="codestpro3" type="text" id="codestpro3" style="text-align:center" value="<?php print $ls_estpro3;?>" size="<?php echo $li_size3 ?>" maxlength="<?php echo $li_loncodestpro3 ?>" readonly>
      <label><input name="denestpro3" type="text" class="sin-borde" id="denestpro3" value="<?php echo $ls_denestpro3 ?>" size="50" readonly>
      </label>    </td>
  </tr>
  <?php
  if($la_empresa["estmodest"]==2)
  {
  ?>
  <tr>
    <td height="22" style="text-align:right"><?php print $la_empresa["nomestpro4"];?></td>
    <td height="22" style="text-align:left"><input name="codestpro4" type="text" id="codestpro4" style="text-align:center" value="<?php echo $ls_estpro4 ?>" size="<?php echo $li_size4 ?>" maxlength="<?php echo $li_loncodestpro4 ?>" readonly>
        <a href="javascript:catalogo_estpro4();"></a>
        <input name="denestpro4" type="text" class="sin-borde" id="denestpro4" value="<?php echo $ls_denestpro4 ?>" size="53" readonly>    </td>
  </tr>
  <tr>
    <td height="22" style="text-align:right"><?php print $la_empresa["nomestpro5"];?></td>
    <td height="22" style="text-align:left"><input name="codestpro5" type="text" id="codestpro5" style="text-align:center" value="<?php echo $ls_estpro5 ?>" size="<?php echo $li_size5 ?>" maxlength="<?php echo $li_loncodestpro5 ?>" readonly>
        <a href="javascript:catalogo_estpro5();"></a>
        <input name="denestpro5" type="text" class="sin-borde" id="denestpro5" value="<?php echo $ls_denestpro5 ?>" size="53" readonly>    </td>
  </tr>
  <?php
  }
  ?>
  <tr>
    <td height="22" style="text-align:right">Cuenta</td>
    <td height="22" style="text-align:left"><input name="txtcuenta" type="text" id="txtcuenta" style="text-align:center" value="<?php echo $ls_spgcta ?>" size="22" readonly> 
      <a href="javascript:catalogo_cuentasSPG();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Catálogo de Cuentas de Gastos"></a>	 
      <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion" style="text-align:left" value="<?php echo $ls_denspgcta ?>" size="50" maxlength="254" readonly>    </td>
  </tr>
  <tr>
    <td height="22" style="text-align:right">Operaci&oacute;n</td>
    <td height="22" style="text-align:left"><input name="txtafectacion" type="text" id="txtafectacion" value="<?php print $ls_afectacion?>" size="8" style="text-align:center" readonly>  </td>
  </tr>
  <tr>
    <td height="22" style="text-align:right">Monto</td>
    <td height="22" style="text-align:left"><input name="txtmonto" type="text" id="txtmonto" style="text-align:right" size="22" onKeyPress="return(currencyFormat(this,'.',',',event))" onBlur="javascript:this.value=uf_convertir(this.value);"> 
      <a href="javascript:aceptar_presupuestario();"><img src="../shared/imagebank/tools15/aprobado.gif" alt="Agregar Detalle Presupuestario" width="15" height="15" border="0"></a> <a href="javascript: close();"><img src="../shared/imagebank/tools15/eliminar.gif" alt="Cancelar Registro de Detalle Presupuestario" width="15" height="15" border="0"></a></td>
  </tr>
  <tr>
    <td height="22">&nbsp;</td>
    <td height="22"><input name="txtcuentascg" type="hidden" id="txtcuentascg">
      <input name="comprobante" type="hidden" id="comprobante" value="<?php print $ls_comprobante;?>">
      <input name="procede" type="hidden" id="procede" value="<?php print $ls_mov_procede;?>">
	  <input name="fecha" type="hidden" id="fecha" value="<?php print $ld_fecha;?>">
      <input name="provbene" type="hidden" id="provbene" value="<?php print $ls_provbene;?>">
      <input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo;?>">
      <input name="descripcion" type="hidden" id="descripcion" value="<?php print $ls_mov_descripcion;?>">
      <input name="operacion" type="hidden" id="operacion">
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
	  <input name="nombanaut"  type="hidden" id="nombanaut"  value="<?php print $ls_nombanaut;?>">		<input name="nrocontrol" type="hidden" id="nrocontrol" value="<?php print $ls_nrocontrol;?>">
	  <input name="hidserext" type="hidden" id="hidserext" value="<?php echo $li_estserext; ?>"></td>
  </tr>
</table>
</form>
</body>
<script language="JavaScript">
	function aceptar_presupuestario()
	{
		f=document.form1;
		ls_numdoc=f.txtdocumento.value;
		ls_procede=f.txtprocedencia.value;
		ls_codest1=f.codestpro1.value;
		ls_codest2=f.codestpro2.value;
		ls_codest3=f.codestpro3.value;
		ls_cuenta=f.txtcuenta.value;
		ls_operacion=f.txtafectacion.value;
		ldec_monto=f.txtmonto.value;
		if((ls_numdoc!="")&&(ls_procede!="")&&(ls_codest1!="")&&(ls_codest2!="")&&(ls_codest3!="")&&(ls_cuenta!="")&&(ls_operacion!="")&&(ldec_monto!=""))
		{
			f.operacion.value="GUARDARPRE";
			f.action="sigesp_w_regdt_presupuesto_op.php";
			f.submit();
		}
		else
		{
			alert("Complete todos los datos");
		}
	}
	
	function uf_close()
	{
	  close()
	}
	
	function agregar_scg(ls_cuenta,ls_descripcion,ls_documento,ldec_monto,ls_procede,ls_debhab)
	{
		f=document.form1;
		fop=opener.document.form1;
		li_total =fop.totcon.value;
		li_last  =fop.lastscg.value;	
		li_newrow= parseInt(li_last,10)+1;
		ls_cuenta=f.txtcuentascg.value;
		lb_valido=false;
		for(li_i=1;li_i<=li_total&&lb_valido!=true;li_i++)
		{
			
			ls_cuenta_opener=eval("fop.txtcontable"+li_i+".value");
			if(ls_cuenta==ls_cuenta_opener)
			{
				ldec_monto_actual=eval("fop.txtmontocont"+li_i+".value");	
				while(ldec_monto_actual.indexOf('.')>0)
				{//Elimino todos los puntos o separadores de miles
					ldec_monto_actual=ldec_monto_actual.replace(".","");
				}
				ldec_monto_actual=ldec_monto_actual.replace(",",".");//Cambio la coma de separacion de decimales por un punto para poder realizar la operacion
				while(ldec_monto.indexOf('.')>0)
				{//Elimino todos los puntos o separadores de miles
					ldec_monto=ldec_monto.replace(".","");
				}
				ldec_monto=ldec_monto.replace(",",".");//Cambio la coma de separacion de decimales por un punto para poder realizar la operacion
				ldec_monto_nuevo=parseFloat(parseFloat(ldec_monto) + parseFloat(ldec_monto_actual));
				ldec_monto_nuevo=uf_convertir(ldec_monto_nuevo);
				eval("fop.txtmontocont"+li_i+".value='"+ldec_monto_nuevo+"'");	
				lb_valido=true;
			}
		}
		if((li_newrow<=li_total))
		{
			if(!lb_valido)
			{
			eval("fop.txtcontable"+li_newrow+".value='"+ls_cuenta+"'");
			eval("fop.txtdesdoc"+li_newrow+".value='"+ls_descripcion+"'");
			eval("fop.txtdocscg"+li_newrow+".value='"+ls_documento+"'");
			eval("fop.txtmontocont"+li_newrow+".value='"+ldec_monto+"'");
			eval("fop.txtdebhab"+li_newrow+".value='"+ls_debhab+"'");
			eval("fop.txtprocdoc"+li_newrow+".value='"+ls_procede+"'");
			fop.lastscg.value=li_newrow;
			}
			uf_calcular_montoscg();
		}
		else
		{
			alert("Debe agregar mas filas a la tabla");
		}
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
	}
	
	function catalogo_cuentasSPG()
	{
		f=document.form1;
		codest1=f.codestpro1.value;
		codest2=f.codestpro2.value;
		codest3=f.codestpro3.value;
	    ls_denestpro1 = f.denestpro1.value;
		ls_denestpro2 = f.denestpro2.value;
		ls_denestpro3 = f.denestpro3.value;
		ls_estcla     = f.hidestcla.value;
		ls_fecmov     = f.fecha.value;
		if ("<?php print $la_empresa["estmodest"];?>"==2)
		   {
		     codest4=f.codestpro4.value;
		     codest5=f.codestpro5.value;
		     ls_denestpro4 = f.denestpro4.value;
			 ls_denestpro5 = f.denestpro5.value;
			 
			 if ((codest1!="")&&(codest2!="")&&(codest3!="")&&(codest4!="")&&(codest5!=""))
		        {
			      pagina="sigesp_cat_ctaspg.php?codestpro1="+codest1+"&hicodest2="+codest2+"&hicodest3="+codest3+"&hicodest4="+codest4+"&hicodest5="+codest5+"&txtdenestpro1="+ls_denestpro1+"&txtdenestpro2="+ls_denestpro2+"&txtdenestpro3="+ls_denestpro3+"&txtdenestpro4="+ls_denestpro4+"&txtdenestpro5="+ls_denestpro5+"&hidestcla="+ls_estcla+"&fecmov="+ls_fecmov;
			      window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=750,height=500,resizable=yes,location=no");
		        }
		     else
		        {
				  alert("Debe completar la Estructura Presupuestaria !!!");
		        }
		}
		else
		{
		   if((codest1!="")&&(codest2!="")&&(codest3!=""))
		   {
			   pagina="sigesp_cat_ctaspg.php?codestpro1="+codest1+"&hicodest2="+codest2+"&hicodest3="+codest3+"&txtdenestpro1="+ls_denestpro1+"&txtdenestpro2="+ls_denestpro2+"&txtdenestpro3="+ls_denestpro3+"&hidestcla="+ls_estcla+"&fecmov="+ls_fecmov;
			   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=750,height=500,resizable=yes,location=no");
		   }
		   else
		   {
				alert("Debe completar la Estructura Presupuestaria !!!");
		   }
		}
	}

	
	function  uf_cambiar()
	{
		f=document.form1;
		fop=opener.document.form1;
		li_newtotal=f.totalpre.value;
		fop.totpre.value=li_newtotal;
		fop.operacion.value="RECARGAR"
		fop.submit();
		
	}
	function uf_calcular_montoscg()
	{
		f=document.form1;
		ldec_mondeb=0;
		ldec_monhab=0;
		li_total=fop.lastscg.value;
		for(li_i=1;li_i<=li_total;li_i++)
		{
			ls_debhab=eval("fop.txtdebhab"+li_i+".value");
			ldec_monto=eval("fop.txtmontocont"+li_i+".value");	
			while(ldec_monto.indexOf('.')>0)
			{//Elimino todos los puntos o separadores de miles
				ldec_monto=ldec_monto.replace(".","");
			}
			ldec_monto=ldec_monto.replace(",",".");//Cambio la coma de separacion de decimales por un punto para poder realizar la operacion
			if(ls_debhab=="D")
			{
				ldec_mondeb=parseFloat(ldec_mondeb)+parseFloat(ldec_monto);					
			}
			else
			{
				ldec_monhab=parseFloat(ldec_monhab) + parseFloat(ldec_monto);					
			}				
		}
		ldec_diferencia=parseFloat(ldec_mondeb)-parseFloat(ldec_monhab);
		ldec_mondeb=uf_convertir(ldec_mondeb);
		fop.txtdebe.value=ldec_mondeb;	
		ldec_monhab=uf_convertir(ldec_monhab);
		fop.txthaber.value=ldec_monhab;	
		ldec_diferencia=uf_convertir(ldec_diferencia);
		fop.txtdiferencia.value=ldec_diferencia;	
	}
  
	function uf_calcular_montospg()
	{
		f=document.form1;
		ldec_monspg=0;
		li_total=fop.lastspg.value;
		for(li_i=1;li_i<=li_total;li_i++)
		{
				ldec_monto=eval("fop.txtmonto"+li_i+".value");	
				while(ldec_monto.indexOf('.')>0)
				{//Elimino todos los puntos o separadores de miles
					ldec_monto=ldec_monto.replace(".","");
				}
				ldec_monto=ldec_monto.replace(",",".");//Cambio la coma de separacion de decimales por un punto para poder realizar la operacion
				ldec_monspg=parseFloat(ldec_monspg)+parseFloat(ldec_monto);
		}
		ldec_monspg=uf_convertir(ldec_monspg);
		fop.totspg.value=ldec_monspg;		
	}
  
	function uf_format(obj)
	{
		ldec_monto=uf_convertir(obj.value);
		obj.value=ldec_monto;
	}
	
	function catunidadadm()
	{
		f=document.form1;
		ls_coduniadm=f.coduniadm.value;
		ls_estuac=f.estuac.value;
		ls_codestpro1=f.codestpro1.value;
		ls_denestpro1=f.denestpro1.value;
		f.codestpro2.value="";
		f.denestpro2.value="";
        f.codestpro3.value="";
		f.denestpro3.value="";
		if (<?php echo $la_empresa["estmodest"]?>==2)
		   {
			 f.codestpro4.value="";
			 f.denestpro4.value="";
			 f.codestpro5.value="";
			 f.denestpro5.value="";
		   }
		f.txtcuenta.value = "";
		f.txtdenominacion.value="";
		ls_estcla = f.hidestcla.value;
		pagina="sigesp_spg_cat_uel.php?codestpro1="+ls_codestpro1+"&coduniadm="+ls_coduniadm+"&estuac="+ls_estuac+"&denestpro1="+ls_denestpro1+"&hidestcla="+ls_estcla;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=650,height=400,resizable=yes,location=no,dependent=yes");
	}	
</script>
</html>