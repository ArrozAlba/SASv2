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
$io_fun_banco->uf_load_seguridad("SCB","sigesp_scb_p_transferencias.php",$ls_permisos,$la_seguridad,$la_permisos);
$li_estciespg = $io_fun_banco->uf_load_estatus_cierre($li_estciespi,$li_estciescg);
$ls_disabled = "";
if ($li_estciescg==1)
   {
     $ls_disabled = "disabled";
   } 
$li_diasem = date('w');
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
<title>Transferencias Bancarias</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/general.css"  rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css"   rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
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
  <td width="778" height="20" colspan="11" bgcolor="#E7E7E7">
    <table width="778" border="0" align="center" cellpadding="0" cellspacing="0">			
      <td width="430" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Caja y Banco</td>
	  <td width="350" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
	  <tr>
	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	<td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
      </tr>
    </table>
  </td>
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
  </tr>
  <tr>
    <td height="20" width="22" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" title="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Guardar" title="Guardar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22">&nbsp;</td>
    <td class="toolbar" width="690">&nbsp;</td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/class_funciones.php");
    require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/ddlb_operaciones_spg.php");
	require_once("../shared/class_folder/ddlb_operaciones_spi.php");
	require_once("../shared/class_folder/ddlb_conceptos.php");
	require_once("sigesp_scb_c_transferencias.php");
	require_once("class_folder/sigesp_scb_c_disponibilidad_financiera.php");
	$msg        = new class_mensajes();	
	$fun        = new class_funciones();	
	$lb_guardar = true;
    $sig_inc    = new sigesp_include();
    $con        = $sig_inc->uf_conectar();
	$arre		= $_SESSION["la_empresa"];
	$ls_empresa = $arre["codemp"];
	$io_disfin    = new sigesp_scb_c_disponibilidad_financiera("../");
	$ls_tipvaldis = $io_disfin->uf_load_tipo_validacion();

	
	$in_classtrans=new sigesp_scb_c_transferencias($la_seguridad);
	require_once("sigesp_scb_c_movbanco.php");
	$in_classmovbco=new sigesp_scb_c_movbanco($la_seguridad);

	if( array_key_exists("operacion",$_POST))
	{
		$ls_operacion= $_POST["operacion"];
		$ld_fecha=$_POST["txtfecha"];		
		$ls_opeorigen=$_POST["cmboperacionorigen"];
	    $ls_opedestino=$_POST["cmboperaciondestino"];
		$ls_docorigen=$_POST["txtdocorigen"];
		$ls_docdestino=$_POST["txtdocdestino"];
		$ls_codban=$_POST["txtcodban"];
		$ls_denban=$_POST["txtdenban"];
		$ls_cuenta_banco=$_POST["txtcuenta"];
		$ls_dencuenta_banco=$_POST["txtdenominacion"];	
		$ls_codbandestino=$_POST["txtcodbandestino"];
		$ls_denbandestino=$_POST["txtdenbandestino"];
		$ls_cuenta_bancodestino=$_POST["txtcuentadestino"];
		$ls_dencuenta_bancodestino=$_POST["txtdenominaciondestino"];
		$ls_cuenta_scg=$_POST["txtcuenta_scg"];
		$ls_cuenta_scgdestino=$_POST["txtcuenta_scgdestino"];
		$ldec_disponible=$_POST["txtdisponible"];
		$ldec_disponibledestino=$_POST["txtdisponibledestino"];
		if($ls_opeorigen=="CH")
		{
			$ls_chevau="";
		}		
		$ldec_mondeb=0;
		$ldec_monhab=0;
		$ldec_diferencia=0;
		$ldec_monspg=0;
		$ldec_monspi=0;
		$ldec_montomov=$_POST["txtmonto"];
		if(($ls_opeorigen=='CH')&&(array_key_exists("txtmonobjret",$_POST)))
		{
			$ldec_monobjret=$_POST["txtmonobjret"];
			$ldec_monobjret=str_replace(".","",$ldec_monobjret);
			$ldec_monobjret=str_replace(",",".",$ldec_monobjret);			
		}
		else
		{
			$ldec_monobjret=0;
		}
		if(($ls_opeorigen=='CH')&&(array_key_exists("txtretenido",$_POST)))
		{
			$ldec_montoret=$_POST["txtretenido"];					
			$ldec_montoret=str_replace(".","",$ldec_montoret);
			$ldec_montoret=str_replace(",",".",$ldec_montoret);
		}
		else
		{
			$ldec_montoret=0;
		}
		
		$ldec_montomov=str_replace(".","",$ldec_montomov);
		$ldec_montomov=str_replace(",",".",$ldec_montomov);
		$ls_desmov=$_POST["txtconcepto"];	
		if(array_key_exists("chkestinternet",$_POST))
		{
			$li_chkinternet=1;
			$lb_chkinternet="checked";
		}
		else
		{
			$li_chkinternet=0;
			$lb_chkinternet="";
		}
	}
	else
	{
		$ls_operacion= "NUEVO" ;		
		$lb_chkinternet="";
		$ls_numtrans="";		
	}	

	
	function uf_nuevo()
	{
		if(array_key_exists("la_deducciones",$_SESSION))
		{
			unset($_SESSION["la_deducciones"]);
		}
		global $ls_opeorigen;
		$ls_opeorigen="";
	    global $ls_opedestino;
		$ls_opedestino="";
		global $ls_docorigen;
		$ls_docorigen="";
		global $ls_docdestino;
		$ls_docdestino="";
		global $ls_codban;
		$ls_codban="";
		global $ls_denban;
		$ls_denban="";
		global $ls_cuenta_banco;
		$ls_cuenta_banco="";
		global $ls_dencuenta_banco;
		$ls_dencuenta_banco="";	
		global $ls_codbandestino;	
		$ls_codbandestino="";
		global $ls_denbandestino;
		$ls_denbandestino="";
		global $ls_cuenta_bancodestino;
		$ls_cuenta_bancodestino="";
		global $ls_dencuenta_bancodestino;
		$ls_dencuenta_bancodestino="";
		global $ls_cuenta_scg;
		$ls_cuenta_scg="";
		global $ls_cuenta_scgdestino;
		$ls_cuenta_scgdestino="";
		global $ldec_disponible;
		$ldec_disponible="0,00";
		global $ldec_disponibledestino;
		$ldec_disponibledestino="0,00";

		global $ls_provbene;
		$ls_provbene="----------";
		global $ls_desproben;
		$ls_desproben="Ninguno";
		global $ls_tipo;
		$ls_tipo="-";
		global $lastspg;
		$lastspg = 0;
		
		global $ld_fecha;
		$ld_fecha=date("d/m/Y");
		global $lastscg;
		$lastscg=0;
		global $lastret;
		$lastret=0;
		global $lastspi;
		$lastspi=0;
		global $ldec_mondeb;
		$ldec_mondeb=0;
		global $ldec_monhab;
		$ldec_monhab=0;
		global $ldec_diferencia;
		$ldec_diferencia=0;
		global $ldec_monspg;
		$ldec_monspg=0;
		global $ldec_monspi;
		$ldec_monspi=0;
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
		global $lb_nocontab;
		$lb_nocontab="";
		global $li_estint;
		$li_estint=0;
		global $ls_ctascg;
		global $ls_denctascg;
		//Cuentas auxiliares para transferencia///
		$ls_ctascg="";
		$ls_denctascg="";
		//////////////////////////////////////////
		
	}
	
	
	if($ls_operacion == "NUEVO")
	{
		$ls_operacion= "" ;
		uf_nuevo();
	}
	if(($ls_operacion!="")&&($ls_codban==$ls_codbandestino)&&($ls_cuenta_banco==$ls_cuenta_bancodestino))
	{
		$ls_codbandestino="";
		$ls_denbandestino="";
		$ls_cuenta_bancodestino="";
		$ls_dencuenta_bancodestino="";	
		$ls_opeorigen="";
		$ls_opedestino="";
		$ls_cuenta_scgdestino="";
		$msg->message("Cuenta origen y destino son iguales,Verifique");
	}
	
	if($ls_operacion == "GUARDAR")
	{			
		if($ls_opeorigen =='CH')
		{
			$ls_chevau = $_POST["txtchevau"];
		}
		else 
		{
			$ls_chevau = "";
		}
		$ls_conmov=$_POST["txtconcepto"];
		$li_row=1;
		//Inserto en el datastore los datos del origen del movimiento
		 $arr_data["numtra"][1]=$ls_docorigen;
		 $arr_data["Codban"][1]=$ls_codban;
		 $arr_data["Ctaban"][1]=$ls_cuenta_banco;
		 $arr_data["numdoc"][1]=$ls_docorigen;
		 $arr_data["codope"][1]=$ls_opeorigen;
		 $arr_data["fecmov"][1]=$ld_fecha;
		 $arr_data["concepto"][1]=$ls_conmov;
		 $arr_data["ced_bene"][1]='----------';
		 $arr_data["cod_prov"][1]='----------';
		 $arr_data["debhab"][1]='H'; 
		 $arr_data["scg_cuenta"][1]=$ls_cuenta_scg;
		 $arr_data["nomproben"][1]='--------------- ';
		 $arr_data["estmov"][1]='N';	
		 $arr_data["monto"][1] =doubleval($ldec_montomov)-doubleval($ldec_montoret);
		 $arr_data["monobjret"][1]=$ldec_monobjret;
		 $arr_data["monret"][1]=$ldec_montoret;
		 $arr_data["chevau"][1]=$ls_chevau;
		 $arr_data["estbpd"][1]='M';
		 $arr_data["procede_doc"][1]='SCBTRA';
		 $arr_data["estmovint"][1]=0;
	     $arr_data["codded"][1]='00000';
		
		 $li_row=$li_row+1;		 
		 $arr_data["numtra"][$li_row]=$ls_docorigen;
		 $arr_data["Codban"][$li_row]=$ls_codban;
		 $arr_data["Ctaban"][$li_row]=$ls_cuenta_banco;
		 $arr_data["numdoc"][$li_row]=$ls_docdestino;
		 $arr_data["codope"][$li_row]=$ls_opeorigen;
		 $arr_data["fecmov"][$li_row]=$ld_fecha;
		 $arr_data["concepto"][$li_row]=$ls_conmov;
		 $arr_data["ced_bene"][$li_row]='----------';
		 $arr_data["cod_prov"][$li_row]='----------';
		 $arr_data["debhab"][$li_row]='D'; 
		 $arr_data["scg_cuenta"][$li_row]=$ls_cuenta_scgdestino;
		 $arr_data["nomproben"][$li_row]='--------------- ';
		 $arr_data["estmov"][$li_row]='N';	
		 $arr_data["monto"][$li_row] =$ldec_montomov;
		 $arr_data["monobjret"][$li_row]=$ldec_monobjret;
		 $arr_data["monret"][$li_row]=$ldec_montoret;
		 $arr_data["chevau"][$li_row]=$ls_chevau;
		 $arr_data["estbpd"][$li_row]='M';
		 $arr_data["procede_doc"][$li_row]='SCBTRA';
		 $arr_data["codded"][$li_row]='00000';
		 $arr_data["estmovint"][$li_row]=0;
		if(array_key_exists("la_deducciones",$_SESSION))
		{
			$la_deducciones=$_SESSION["la_deducciones"];
			$li_total=count($la_deducciones["Codded"]);
			for($i=1;$i<=$li_total;$i++)
			{
				if(array_key_exists("$i",$la_deducciones["Codded"]))//6
				{

						 $ls_ctascgded=$la_deducciones["SC_Cuenta"][$i];
						 $ls_dended=$la_deducciones["Dended"][$i];
						 $ls_codded=$la_deducciones["Codded"][$i];
						 $ldec_objret=$la_deducciones["MonObjRet"][$i];
						 $ldec_montoret=$la_deducciones["MonRet"][$i];
						 if($ls_codded!="")
 						 {
							 $li_row=$li_row+1;
					 		 $arr_data["numtra"][$li_row]=$ls_docorigen;
							 $arr_data["Codban"][$li_row]=$ls_codban;
							 $arr_data["Ctaban"][$li_row]=$ls_cuenta_banco;
							 $arr_data["numdoc"][$li_row]=$ls_docorigen;
							 $arr_data["codope"][$li_row]=$ls_opeorigen;
							 $arr_data["fecmov"][$li_row]=$ld_fecha;
							 $arr_data["concepto"][$li_row]=$ls_dended;
							 $arr_data["ced_bene"][$li_row]='----------';
							 $arr_data["cod_prov"][$li_row]='----------';
							 $arr_data["debhab"][$li_row]='H'; 
							 $arr_data["scg_cuenta"][$li_row]=$ls_ctascgded;
							 $arr_data["nomproben"][$li_row]='--------------- ';
							 $arr_data["estmov"][$li_row]='N';	
							 $arr_data["monto"][$li_row] =$ldec_montoret;
							 $arr_data["monobjret"][$li_row]=$ldec_objret;
							 $arr_data["monret"][$li_row]=$ldec_montoret;
							 $arr_data["chevau"][$li_row]=$ls_chevau;
							 $arr_data["estbpd"][$li_row]='M';
							 $arr_data["procede_doc"][$li_row]='SCBTRA';
							 $arr_data["codded"][$li_row]=$ls_codded;
							 $arr_data["estmovint"][$li_row]=0;
						}
				}
			}
		}					
		
		//Inserto en el datastore los datos del destino del movimiento
		 $li_row=1;
 		 $arr_datadestino["numtra"][$li_row]=$ls_docdestino;
		 $arr_datadestino["Codban"][$li_row]=$ls_codbandestino;
		 $arr_datadestino["Ctaban"][$li_row]=$ls_cuenta_bancodestino;
		 $arr_datadestino["numdoc"][$li_row]=$ls_docdestino;
		 $arr_datadestino["codope"][$li_row]=$ls_opedestino;
		 $arr_datadestino["fecmov"][$li_row]=$ld_fecha;
		 $arr_datadestino["concepto"][$li_row]="Tansferencia de Fondos, del ".$ls_codban." cuenta ".$ls_cuenta_banco." al ".$ls_codbandestino." cuenta ".$ls_cuenta_bancodestino;
		 $arr_datadestino["ced_bene"][$li_row]='----------';
		 $arr_datadestino["cod_prov"][$li_row]='----------';
		 $arr_datadestino["nomproben"][$li_row]='Ninguno';
		 $arr_datadestino["estmov"][$li_row]='L';	
		 $arr_datadestino["monto"][$li_row] =$ldec_montomov;
		 $arr_datadestino["monobjret"][$li_row]=0;
		 $arr_datadestino["monret"][$li_row]=0;
		 $arr_datadestino["chevau"][$li_row]="";
		 $arr_datadestino["estbpd"][$li_row]='M';
		 $arr_datadestino["procede_doc"][$li_row]='SCBTRA';
		 $arr_datadestino["estmovint"][$li_row]=0;
		 $arr_datadestino["codded"][$li_row]='00000';		 
		
		$in_classtrans->io_sql->begin_transaction();
		$lb_valido=$in_classtrans->uf_procesar_transferencia($arr_data,$arr_datadestino,$la_seguridad);
		
		if($lb_valido)
		{
			$in_classtrans->io_sql->commit();
			$msg->message("Movimiento Registrado !!!");
			uf_nuevo();
		}
		else
		{
			$in_classtrans->io_sql->rollback();
			$msg->message("Error en transacción".",".$in_classtrans->is_msg_error);			
		}

	}
			
	if($ls_opeorigen=='CH')
	{
		require_once("sigesp_scb_c_movbanco.php");
		$in_classmovbco=new sigesp_scb_c_movbanco($la_seguridad);
		$ls_chevau=$in_classmovbco->uf_generar_voucher($ls_empresa);
		$lb_selCH="selected";
		$lb_selND="";
		$ls_opedestino="DP";
		$ls_destino="Depósito";
	}
	else
	{
		$lb_selCH="";
		$lb_selND="selected";
		$ls_opedestino="NC";
		$ls_destino="Nota de Crédito";
	}
	
	if($ls_operacion == "VERIFICAR_VAUCHER")
	{
		$ls_chevaux=$_POST["txtchevau"];
		$lb_existe=$in_classmovbco->uf_select_voucher($ls_chevaux);
		if($lb_existe)
		{
			$msg->message("Nº de Voucher ya existe, favor indicar otro");
			$ls_chevau="";
		}
		else
		{
			$ls_chevau=$ls_chevaux;
		}		
	}
	
?>
  <form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_banco->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_banco);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <p><br>
  </p>
  <table width="722" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-ventana">
      <td height="22" colspan="4"><input name="hidmesabi" type="hidden" id="hidmesabi" value="true">
        Transferencias Bancarias
      <input name="hidestciescg" type="hidden" id="hidestciescg" value="<?php echo $li_estciescg; ?>"></td>
    </tr>
    <tr>
      <td height="13" colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td height="22">&nbsp;</td>
      <td>&nbsp;</td>
      <td height="22"><div align="right">Fecha</div></td>
      <td><div align="left">
          <input name="txtfecha" type="text" id="txtfecha"  style="text-align:center" value="<?php print $ld_fecha;?>" size="24" maxlength="10" datepicker="true" <?php echo $ls_disabled; ?> onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);uf_validar_estatus_mes();">
      </div></td>
    </tr>
      <script language="javascript">uf_validar_estatus_mes();</script>
    <tr>
      <td height="22"><div align="right">Concepto Movimiento </div></td>
      <td colspan="3"><div align="left">
          <input name="txtconcepto" type="text" id="txtconcepto" value="<?php print $ls_desmov;?>" size="108" <?php echo $ls_disabled; ?>>
      </div></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Monto</div></td>
      <td><div align="left">
          <input name="txtmonto" type="text" id="txtmonto" style="text-align:right" onBlur="javascript:uf_format(this);" value="<?php print number_format($ldec_montomov,2,",",".");?>" size="24" <?php echo $ls_disabled; ?>>
      </div></td>
      <td><div align="right">
        <?php if($ls_opeorigen=="CH")
								{
									print "Monto Objeto a Retención";
								}
								?>
       </div></td>
      <td><div align="left"> 
	  <?php if($ls_opeorigen=="CH")
	  {
	  ?>
	  <input name="txtmonobjret" type="text" id="txtmonobjret" style="text-align:right" onBlur="javascript:validar_monto();javascript:uf_format(this);" value="<?php print  number_format($ldec_monobjret,2,",",".");?>" size="24">
	  <?php
	  }
	  ?>
	  </div></td>
    </tr>
    <tr>
      <td height="22"><div align="right"><?php if($ls_opeorigen=="CH")print "Monto Retenido"?> </div></td>
      <td><div align="left">
	  <?php if($ls_opeorigen=="CH")
		{
	  ?>
	 <input name="txtretenido" type="text" id="txtretenido" style="text-align:right" value="<?php print number_format($ldec_montoret,2,",",".");?>" size="24" readonly>
	 <a href="javascript:uf_cat_deducciones();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo de deducciones" title="Cat&aacute;logo de Deducciones" width="15" height="15" border="0"></a>	  <?php
		}
	  ?>	
      </div></td>
      <td><div align="right">Via Web</div></td>
      <td><div align="left">
        <input name="chkestinternet" type="checkbox" class="sin-borde" id="chkestinternet" style="width:15px; height:15px" onClick="javascript:uf_checkinternet();" value="1"  <?php print $lb_chkinternet; echo $ls_disabled; ?>>
      </div></td>
    </tr>
    <tr>
      <td height="22">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr class="formato-azul">
      <td height="15" colspan="4"><div align="center"><strong>Datos de Origen</strong></div></td>
    </tr>
    <tr>
      <td height="13">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="123" height="22"><div align="right">Documento</div></td>
      <td width="189"><div align="left">
          <input name="txtdocorigen" type="text" id="txtdocorigen" style="text-align:center" onBlur="rellenar( this,15)" value="<?php print $ls_docorigen;?>" size="24" <?php echo $ls_disabled; ?>>
      </div></td>
      <td width="228">&nbsp;</td>
      <td width="180">&nbsp;</td>
    </tr>
    <tr>
      <td height="22"><div align="right">Banco</div></td>
      <td colspan="3"><div align="left">
          <input name="txtcodban" type="text" id="txtcodban"  style="text-align:center" value="<?php print $ls_codban;?>" size="10" readonly>
          <a href="javascript:cat_bancos();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Bancos"></a>
          <input name="txtdenban" type="text" id="txtdenban" value="<?php print $ls_denban?>" size="51" class="sin-borde" readonly>
      </div></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Cuenta</div></td>
      <td colspan="3"><div align="left">
          <input name="txtcuenta" type="text" id="txtcuenta" style="text-align:center" value="<?php print $ls_cuenta_banco; ?>" size="30" maxlength="25" readonly>
          <a href="javascript:catalogo_cuentabanco();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Cuentas Bancarias"></a>
          <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion" style="text-align:left" value="<?php print $ls_dencuenta_banco; ?>" size="50" maxlength="254" readonly>
          <input name="txttipocuenta" type="hidden" id="txttipocuenta">
          <input name="txtdentipocuenta" type="hidden" id="txtdentipocuenta">
      </div></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Cuenta Contable </div></td>
      <td><div align="left">
          <input name="txtcuenta_scg" type="text" id="txtcuenta_scg" style="text-align:center" value="<?php print $ls_cuenta_scg;?>" size="24" readonly>
      </div></td>
      <td><div align="right">Disponible</div></td>
      <td><div align="left">
          <input name="txtdisponible" type="text" id="txtdisponible" style="text-align:right" value="<?php print $ldec_disponible;?>" size="24" readonly>
      </div></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Operaci&oacute;n</div></td>
      <td style="text-align:left"><select name="cmboperacionorigen" id="cmboperacionorigen" style="width:110px;text-align:center" onChange="javascript:uf_verificar_operacion();" <?php echo $ls_disabled; ?>>
          <option value="CH" <?php print $lb_selCH;?>>Cheque</option>
          <option value="ND" <?php print $lb_selND;?>>Nota de Débito</option>
        </select>
      </td>
      <td><div align="right">
        <?php if($ls_opeorigen=="CH")
								{
									print "Voucher";
								}
								?>
</div></td>
      <td><div align="left">
          <?php if($ls_opeorigen=="CH")
								{
									print "<input name=txtchevau type=text id=txtchevau size=28 maxlength=25 value='".$ls_chevau."' onChange=javascript:ue_verificar_vaucher()>";
								}
								?>
      </div></td>
    </tr>
    <tr>
      <td height="13">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr class="formato-azul">
      <td height="15" colspan="4"><div align="center"><strong>Datos de Destino </strong></div></td>
    </tr>
    <tr>
      <td height="13">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="22"><div align="right">Documento</div></td>
      <td><div align="left">
          <input name="txtdocdestino" type="text" id="txtdocdestino" style="text-align:center" onBlur="rellenar(this,15)" value="<?php print $ls_docdestino;?>" size="24" <?php echo $ls_disabled; ?>>
      </div></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="22"><div align="right">Banco</div></td>
      <td colspan="3"><div align="left">
          <input name="txtcodbandestino" type="text" id="txtcodbandestino"  style="text-align:center" value="<?php print $ls_codbandestino;?>" size="10" readonly>
          <a href="javascript:cat_bancosdestino();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Bancos"></a>
          <input name="txtdenbandestino" type="text" id="txtdenbandestino" value="<?php print $ls_denbandestino;?>" size="51" class="sin-borde" readonly>
      </div></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Cuenta</div></td>
      <td colspan="3"><div align="left">
          <input name="txtcuentadestino" type="text" id="txtcuentadestino" style="text-align:center" value="<?php print $ls_cuenta_bancodestino; ?>" size="30" maxlength="25" readonly>
          <a href="javascript:catalogo_cuentabancodestino();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Cuentas Bancarias"></a>
          <input name="txtdenominaciondestino" type="text" class="sin-borde" id="txtdenominaciondestino" style="text-align:left" value="<?php print $ls_dencuenta_bancodestino; ?>" size="50" maxlength="254" readonly>
          <input name="txttipocuentadestino" type="hidden" id="txttipocuentadestino">
          <input name="txtdentipocuentadestino" type="hidden" id="txtdentipocuentadestino">
      </div></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Cuenta Contable </div></td>
      <td><div align="left">
          <input name="txtcuenta_scgdestino" type="text" id="txtcuenta_scgdestino" style="text-align:center" value="<?php print $ls_cuenta_scgdestino;?>" size="24" readonly>
      </div></td>
      <td><div align="right">Disponible</div></td>
      <td><div align="left">
          <input name="txtdisponibledestino" type="text" id="txtdisponibledestino" style="text-align:right" value="<?php print $ldec_disponibledestino;?>" size="24" readonly>
      </div></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Operaci&oacute;n</div></td>
      <td><div align="left">
          <input name="cmboperaciondestino" type="hidden" id="cmboperaciondestino" value="<?php print $ls_opedestino;?>" readonly style="text-align:center">
          <input name="operaciondestino" type="text" id="operaciondestino" value="<?php print $ls_destino?>" readonly style="text-align:center">
</div></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="13" colspan="4">&nbsp;</td>
    </tr>
  </table>
  <p><input name="operacion" type="hidden" id="operacion">
</p>
  </form>
</body>
<script language="javascript">
f = document.form1;
var patron = new Array(2,2,4);
function ue_nuevo()
{
  if (uf_evaluate_cierre('SCG'))
     {
	   f.operacion.value ="NUEVO";
	   f.action="sigesp_scb_p_transferencias.php";
	   f.submit();
     }
}

function ue_guardar()
{
  lb_valido = f.hidmesabi.value;
  if (lb_valido=='true')
     {
	  if (uf_evaluate_cierre('SCG'))
		 {
		   ls_codbanorigen =f.txtcodban.value;
		   ls_codbandestino=f.txtcodbandestino.value;
		   ls_ctaorigen    =f.txtcuenta.value;
		   ls_ctadestino   =f.txtcuentadestino.value;
		   ls_nombanorigen =f.txtdenban.value;
		   ls_nombandestino=f.txtdenbandestino.value;
		   ls_docorigen    =f.txtdocorigen.value;
		   ls_docdestino   =f.txtdocdestino.value;
		   if ((ls_codbanorigen!="")&&(ls_ctaorigen!="")&&(ls_codbandestino!="")&&(ls_ctadestino!="")&&(ls_docorigen!="")&&(ls_docdestino!=""))
		   {
				if(ls_ctaorigen!=ls_ctadestino)
				{
					ld_totmondis = f.txtdisponible.value;
					ls_tipvaldis = "<?php echo $ls_tipvaldis; ?>";
					ls_codope    = "<?php echo $ls_opeorigen; ?>";
					lb_valido    = uf_validar_disponible(ls_codope,ls_tipvaldis,ld_totmondis,f.txtmonto.value);
					if (lb_valido)
					{
						 f.operacion.value ="GUARDAR";
						 f.action="sigesp_scb_p_transferencias.php";
						 f.submit();
					}
				}   
				else
				{
					alert("Cuenta Origen y Destino no pueden ser iguales");
					f.txtcuentadestino.value="";
					f.txtdenominaciondestino.value="";
				}
		   }
		   else
		   {
				alert("Complete los datos !!!");
		   }
		 }	 
	 }
  else
     {
	   alert("Operación No puede ser procesada, El Més está Cerrado !!!");
	 }
}

	
function rellenar(obj,longitud)
{
		if(obj.value!="")
		{
			ls_cadena=obj.value;
			var mystring=new String(ls_cadena);
			cadena_ceros="";
			lencad=mystring.length;
			total=longitud-lencad;
			for(i=1;i<=total;i++)
			{
				cadena_ceros=cadena_ceros+"0";
			}
			cadena=cadena_ceros+ls_cadena;
			obj.value=cadena;
		}
	}
	
function catalogo_cuentabanco()
{
  uf_validar_estatus_mes();
  if (f.hidmesabi.value=='true')
     {
	  if (uf_evaluate_cierre('SCG'))
		 {
		   ls_codban=f.txtcodban.value;
		   ls_denban=f.txtdenban.value;
		   if ((ls_codban!=""))
			  {
				pagina="sigesp_cat_ctabancoorigen.php?codigo="+ls_codban+"&denban="+ls_denban;
				window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
			  }
		   else
			  {
				alert("Seleccione el Banco !!!");   
			  }	  
		 }
     }
  else
     {
	   alert("Operación No puede ser procesada, El Més está Cerrado !!!");
	 }
}
	 
function catalogo_cuentabancodestino()
{
  uf_validar_estatus_mes();
  if (f.hidmesabi.value=='true')
     {
	  if (uf_evaluate_cierre('SCG'))
		 {
		   ls_codban=f.txtcodbandestino.value;
		   ls_denban=f.txtdenbandestino.value;
		   if ((ls_codban!=""))
			  {
				pagina="sigesp_cat_ctabancodestino.php?codigo="+ls_codban+"&denban="+ls_denban;
				window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
			  }
		   else
			  {
				alert("Seleccione el Banco !!!");   
			  }	  
		 }
     }
  else
     {
	   alert("Operación No puede ser procesada, El Més está Cerrado !!!");
	 }
}
	 	 
function cat_bancos()
{
  uf_validar_estatus_mes();
  if (f.hidmesabi.value=='true')
     {
	   if (uf_evaluate_cierre('SCG'))
		  {
		    pagina="sigesp_cat_bancoorigen.php";
		    window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
		  }
     }
  else
     {
	   alert("Operación No puede ser procesada, El Més está Cerrado !!!");
	 }
}

function cat_bancosdestino()
{
  uf_validar_estatus_mes();
  if (f.hidmesabi.value=='true')
     {
	   if (uf_evaluate_cierre('SCG'))
		  {
		    pagina="sigesp_cat_bancosdestino.php";
		    window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
		  }
     }
  else
     {
	   alert("Operación No puede ser procesada, El Més está Cerrado !!!");
	 }
}
	
function uf_cat_deducciones() 
{
  if (uf_evaluate_cierre('SCG'))
     {
       ls_documento=f.txtdocorigen.value;
       ldec_monto=f.txtmonto.value;
	   ldec_monobjret=f.txtmonobjret.value;	   
	   pagina="sigesp_cat_deducciones.php?monto="+ldec_monto+"&objret="+ldec_monobjret+"&txtdocumento="+ls_documento;
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
     }
}
	
function uf_checkinternet()
{
		f=document.form1;
		lb_chkinternet=f.chkestinternet.checked;
		ls_codbanorigen=f.txtcodban.value;
		ls_codbandestino=f.txtcodbandestino.value;
		ls_ctaorigen=f.txtcuenta.value;
		ls_ctadestino=f.txtcuentadestino.value;
		ls_nombanorigen=f.txtdenban.value;
		ls_nombandestino=f.txtdenbandestino.value;
		if((lb_chkinternet))
		{		
			ls_opeorigen  = 'ND';
			f.cmboperacionorigen.value = ls_opeorigen;
			ls_opedestino = 'NC';
			f.cmboperaciondestino.value = ls_opedestino;
		}
		else
		{
			ls_opeorigen  = 'CH';
			f.cmboperacionorigen.value = ls_opeorigen;
			ls_opedestino = 'DP';
			f.cmboperaciondestino.value = ls_opedestino;		
		}
		ls_contrans = 'Transferencia de fondos del '+ls_nombanorigen+' cuenta '+ls_ctaorigen+' al '+ls_nombandestino+' cuenta '+ls_ctadestino;
		f.txtconcepto.value=ls_contrans;
		f.submit();

   }

function uf_verificar_operacion()
{
		ls_opeorigen=f.cmboperacionorigen.value;
		ls_opedestino=f.cmboperaciondestino.value;
		ls_nombanorigen=f.txtdenban.value;
		ls_ctaorigen=f.txtcuenta.value;
		ls_nombandestino=f.txtdenbandestino.value;
		ls_ctadestino=f.txtcuentadestino.value;
		ls_codbanorigen=f.txtcodban.value;
		ls_codbandestino=f.txtcodbandestino.value;
		if(f.chkestinternet.checked)
		{
			li_status=1;
		}
		else
		{
			li_status=0;
		}
		if((ls_codbanorigen!=ls_codbandestino)&&(li_status==0))
		{
			ls_opedestino = 'DP';
			f.cmboperaciondestino.value = ls_opedestino;
			f.operaciondestino.value = 'Depósito';
			f.cmboperacionorigen.value='CH';
		}
		else
		{
			ls_opedestino = 'NC';
			f.cmboperaciondestino.value = ls_opedestino;
			f.operaciondestino.value = 'Nota de Crédito';
			f.cmboperacionorigen.value='ND';
		}
		ls_contrans = 'Transferencia de fondos del '+ls_nombanorigen+' cuenta '+ls_ctaorigen+' al '+ls_nombandestino+' cuenta '+ls_ctadestino;
		f.txtconcepto.value=ls_contrans;
		f.submit();
   }
   
function uf_format(obj)
{
  ldec_monto=uf_convertir(obj.value);
  obj.value=ldec_monto;
}
   
function uf_validar_campos(operacion)
{
		ls_documento=f.txtdocumento.value;
		if(ls_documento=="")
		{
			alert("Debe introducir un numero de documento");
			return false;	
		}
		
		ls_codban=f.txtcodban.value;
		ls_cuentaban=f.txtcuenta.value;
		if((ls_codban=="")&&(ls_ctaban==""))
		{
			alert("Seleccione el banco y la cuenta");
		}
		ls_cuenta_scg=f.txtcuenta_scg.value;
		ld_fecha=f.txtfecha.value;
		ls_concepto=f.txtconcepto.value;
		if(f.rb_provbene[0].checked)
		{
			ls_tipo_dest="P";
		}
		if(f.rb_provbene[1].checked)
		{
			ls_tipo_dest="B";
		}
		if(f.rb_provbene[2].checked)
		{
			ls_tipo_dest="N";
		}
		ls_provbene=f.txtprovbene.value;
		ldec_monto=f.txtmonto.value;
		ldec_montoobjret=f.txtmonobjret.value;
		ldec_montoret=f.txtretenido.value;
		ldec_diferencia=f.txtdiferencia.value;
		
   }

function validar_monto()
{
	ldec_monto=f.txtmonto.value;
	ldec_monobjret=f.txtmonobjret.value;
	while(ldec_monobjret.indexOf('.')>0)
	{//Elimino todos los puntos o separadores de miles
		ldec_monobjret=ldec_monobjret.replace(".","");
	}
	ldec_monobjret=ldec_monobjret.replace(",",".");
	while(ldec_monto.indexOf('.')>0)
	{//Elimino todos los puntos o separadores de miles
		ldec_monto=ldec_monto.replace(".","");
	}
	ldec_monto=ldec_monto.replace(",",".");
	if(parseFloat(ldec_monto)<parseFloat(ldec_monobjret))
	{
		alert("Monto Objeto a Retención no puede ser mayor al del Movimiento");	
		f.txtmonobjret.value=uf_convertir(0);
		f.txtmonobjret.focus();
		
	}
	
   }
   
function ue_verificar_vaucher()
{
  f.operacion.value="VERIFICAR_VAUCHER";
  f.submit();
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
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>