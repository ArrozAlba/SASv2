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
$io_fun_banco = new class_funciones_banco();
$io_fun_banco->uf_load_seguridad("SCB","sigesp_scb_p_pago_anticipo.php",$ls_permisos,&$la_seguridad,$la_permisos);
$ls_reporte   = $io_fun_banco->uf_select_config("SCB","REPORTE","CHEQUE_VOUCHER","sigesp_scb_rpp_voucher_pdf.php","C");//print $ls_reporte;
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
<title>Pagos de Anticipo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/valida_fecha.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_scb.js"></script>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css"  rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css"   rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
<body onUnload="javascript:uf_valida_cuadre();">
<script language="javascript">
	function uf_valida_cuadre()
	{
		f=document.form1;
		ldec_diferencia=f.txtdiferencia.value;
		ldec_monto=f.txtmonto.value;
		ldec_monto=uf_convertir_monto(ldec_monto);
		ldec_haber=f.txthaber.value;
		ldec_haber=uf_convertir_monto(ldec_haber);
		ldec_diferencia=uf_convertir_monto(ldec_diferencia);
		ls_operacion=f.operacion.value;
		if((ldec_diferencia!=0)&&((ls_operacion=="")||(ls_operacion=="GUARDAR")||(ls_operacion=='NUEVO')))
		{
			alert("Comprobante descuadrado Contablemente");
			f.operacion.value="CARGAR_DT";
			f.action="sigesp_scb_p_pago_anticipo.php";
			f.submit();
		}
	}
</script>
<span class="toolbar"><a name="00"></a></span>
<table width="778" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
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
    <td height="18" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="22" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" title="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Guardar" title="Guardar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="21"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" title="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript:ue_imprimir('<?php print $ls_reporte ?>');"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" title="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" title="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="647"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" title="Ayuda"></td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/grid_param.php");
	require_once("../shared/class_folder/class_mensajes.php");
    require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/ddlb_conceptos.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_funciones_db.php");
	require_once("../shared/class_folder/ddlb_operaciones_spg.php");
	require_once("../shared/class_folder/ddlb_operaciones_spi.php");	
	require_once("class_folder/sigesp_scb_c_disponibilidad_financiera.php");
	
	$msg            = new class_mensajes();	
	$fun            = new class_funciones();	
    $sig_inc        = new sigesp_include();
    $con            = $sig_inc->uf_conectar();
	$obj_spg        = new ddlb_operaciones_spg($con);
	$io_function_db = new class_funciones_db($con);	
	$obj_spi        = new ddlb_operaciones_spi($con);
	$obj_con        = new ddlb_conceptos($con);
	$io_grid        = new grid_param();
	$ls_empresa     = $_SESSION["la_empresa"]["codemp"];
	$as_estmodest   = $_SESSION["la_empresa"]["estmodest"];
	$li_estciespg   = $io_fun_banco->uf_load_estatus_cierre($li_estciespi,$li_estciescg);
	$io_disfin    = new sigesp_scb_c_disponibilidad_financiera("../");
	$ls_tipvaldis = $io_disfin->uf_load_tipo_validacion();

	$li_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
	$li_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
	$li_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
	$li_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
	$li_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];

    require_once("sigesp_scb_c_movbanco.php");
	$in_classmovbco=new sigesp_scb_c_movbanco($la_seguridad);
	require_once("sigesp_scb_c_config.php");
	$in_classconfig=new sigesp_scb_c_config($la_seguridad);

	if( array_key_exists("operacion",$_POST))
	{
		$ls_operacion= $_POST["operacion"];
		$ls_mov_operacion=$_POST["cmboperacion"];
		$ls_estdoc=$_POST["status_doc"];
		
		if(array_key_exists("ddlb_conceptos",$_POST))
		{			
			$ls_codigoconcepto=$_POST["ddlb_conceptos"];		
		}		
	    $li_estciescg = $_POST["hidestciescg"];				
		$ls_docmov=$_POST["txtdocumento"];
		$ld_fecha=$_POST["txtfecha"];
		$ls_codban=$_POST["txtcodban"];
		$ls_denban=$_POST["txtdenban"];
		$ls_cuenta_banco=$_POST["txtcuenta"];
		$ls_dencuenta_banco=$_POST["txtdenominacion"];
		$ls_provbene=$_POST["txtprovbene"];
		$ls_desproben=$_POST["txtdesproben"];
		$ls_tipo=$_POST["rb_provbene"];		
		$lastscg = $_POST["lastscg"];	
		$ldec_mondeb=0;
		$ldec_monhab=0;
		$ldec_diferencia=0;
		$ldec_monspg=0;
		$ldec_monspi=0;
		$ls_estmov=$_POST["estmov"];
		$ls_estcon=$_POST["estcon"];
		$ldec_montomov=$_POST["txtmonto"];
		$ldec_monobjret=$_POST["txtmonobjret"];
		$ldec_montoret=$_POST["txtretenido"];
		$ldec_montomov=str_replace(".","",$ldec_montomov);
		$ldec_montomov=str_replace(",",".",$ldec_montomov);
		$ldec_monobjret=str_replace(".","",$ldec_monobjret);
		$ldec_monobjret=str_replace(",",".",$ldec_monobjret);
		$ldec_montoret=str_replace(".","",$ldec_montoret);
		$ldec_montoret=str_replace(",",".",$ldec_montoret);
		$ls_codconmov=$_POST["codconmov"];
		if(($ls_codconmov=="---")||(!empty($ls_codconmov)))
		{   
			if(($ls_codigoconcepto!="---")||(!empty($ls_codigoconcepto)))
			{
				$ls_codconmov=$ls_codigoconcepto;
			}
		}
		$ls_desmov=$_POST["txtconcepto"];
		$ls_cuenta_scg=$_POST["txtcuenta_scg"];
		$ldec_disponible=$_POST["txtdisponible"];
		$li_estint=$_POST["estint"];
		$ls_codfuefin=rtrim($_POST["txtftefinanciamiento"]);
		if($ls_codfuefin=="")
		{
			$ls_codfuefin="--";
		}
		$ls_denfuefin=rtrim($_POST["txtdenftefinanciamiento"]);			
		if($ls_mov_operacion=='CH')
		{
			if(array_key_exists("txtchevau",$_POST))
			{	$ls_chevau=$_POST["txtchevau"];	}
			else
			{	$ls_chevau="";	}
		}
		else
		{	$ls_chevau="";	}
		$ls_estbpd='M';
		$li_estimpche=$_POST["estimpche"];
		
	}
	else
	{
		$ls_operacion= "NUEVO" ;
		$ls_estdoc="N";
		$li_estimpche=0;	
		
	}	
	$li_row=0;	
	$li_rows_ret=0;	
	if($ls_operacion=="CARGAR_DT")
	{
		$ls_codconmov=$_POST["codconmov"]; 
		$ls_operaban="CHEQUE";
		$ls_afecta="CONTABLE";
		if(($ls_codconmov=="---")||(!empty($ls_codconmov)))
		{   
			if(($ls_codigoconcepto!="---")||(!empty($ls_codigoconcepto)))
			{   if ($ls_codigoconcepto!="---")
			    {
					$ls_codconmov=$ls_codigoconcepto;
				}
			}
		}	
		uf_cargar_dt(); 
	}
	
	function uf_cargar_dt()
	{
		global $in_classmovbco;
		global $objectScg;
		global $li_row;
		global $ls_estmov;
		global $ls_estcon;
		global $ldec_mondeb;
		global $ldec_monhab;	
		global $objectRet;
		global $li_rows_ret;
		global $ldec_montoret;
		global $ldec_diferencia;
		global $ls_docmov;
		global $ls_codban;
		global $ls_cuenta_banco;
		global $ls_mov_operacion;
		global $ls_chevau;
		if ($ls_mov_operacion=="CHEQUE")
		{
			$ls_mov_operacion="CH";
		}		
		$in_classmovbco->uf_cargar_dt_cont($ls_docmov,$ls_codban,$ls_cuenta_banco,$ls_mov_operacion,$ls_estmov,&$objectScg,&$li_row,&$ldec_mondeb,&$ldec_monhab);
		
		$ls_chevau=$in_classmovbco->uf_numero_voucher($_SESSION["la_empresa"]["codemp"],$ls_codban,$ls_cuenta_banco,$ls_docmov);		
		$ldec_diferencia=round($ldec_mondeb,2)-round($ldec_monhab,2);		
		
	}
	
	
	function uf_nuevo()
	{
		global $ls_operaban;
		$ls_operaban="CHEQUE";
		global $ls_afecta;
		$ls_afecta="CONTABLE";
		global $ls_codconmov;	
		global $ls_mov_operacion;
		$ls_mov_operacion='CH';
		global $ls_estdoc;
		$ls_estdoc="N";		
	    global $ls_opepre;
		$ls_opepre=0;		
		global $ls_codban;
		$ls_codban="";
		global $ls_denban;
		$ls_denban="";
		global $ls_estmov;
		global $ls_estcon;
		$ls_estcon=0;
		$ls_estmov="N";
		global $ls_cuenta_banco;
		$ls_cuenta_banco="";
		global $ls_dencuenta_banco;
		$ls_dencuenta_banco="";	
		global $ls_provbene;
		$ls_provbene="----------";
		global $ls_desproben;
		$ls_desproben="Ninguno";
		global $ls_tipo;
		$ls_tipo="-";
		global $lastspg;
		$lastspg = 0;
		$array_fecha=getdate();
		$ls_dia=$array_fecha["mday"];
		$ls_mes=$array_fecha["mon"];
		$ls_ano=$array_fecha["year"];
		global $ld_fecha;
		global $fun;
		$ld_fecha=$fun->uf_cerosizquierda($ls_dia,2)."/".$fun->uf_cerosizquierda($ls_mes,2)."/".$ls_ano;
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
		global $ls_cuenta_scg;
		$ls_cuenta_scg="";
		global $ldec_disponible;
		$ldec_disponible="";
		global $lb_nocontab;
		$lb_nocontab="";
		global $li_estint;
		$li_estint=0;
		$array_fecha=getdate();
		$ls_dia=$array_fecha["mday"];
		$ls_mes=$array_fecha["mon"];
		$ls_ano=$array_fecha["year"];
		global $objectScg;
		global $objectSpg;
		global $objectSpi;
		global $objectRet;
		global $li_row_scg;
		global $ls_codfuefin;
		global $ls_denfuefin;
		$ls_codfuefin="";
		$ls_denfuefin="";
		$li_row_scg=1;
		$objectScg[$li_row_scg][1] = "<input type=text name=txtcontable".$li_row_scg."  id=txtcontable".$li_row_scg."  value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";		
		$objectScg[$li_row_scg][2] = "<input type=text name=txtdocscg".$li_row_scg."    value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
		$objectScg[$li_row_scg][3] = "<input type=text name=txtdesdoc".$li_row_scg."    value='' class=sin-borde readonly style=text-align:left size=35 maxlength=254>";
		$objectScg[$li_row_scg][4] = "<input type=text name=txtprocdoc".$li_row_scg."   value='' class=sin-borde readonly style=text-align:center size=8 maxlength=6>";
		$objectScg[$li_row_scg][5] = "<input type=text name=txtdebhab".$li_row_scg."    value='' class=sin-borde readonly style=text-align:center size=8 maxlength=1>"; 
		$objectScg[$li_row_scg][6] = "<input type=text name=txtmontocont".$li_row_scg." value='' class=sin-borde readonly style=text-align:right size=22 maxlength=22>";
		$objectScg[$li_row_scg][7] = "<input type=text name=txtcodded".$li_row_scg."    value='' class=sin-borde readonly style=text-align:right size=5 maxlength=5>";
		$objectScg[$li_row_scg][8] = "<a href=javascript:uf_delete_Scg('".$li_row_scg."');><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar detalle contable' width=15 height=15 border=0></a>";

	}// fin de nuevo
	$title2[1]="Cuenta";       $title2[2]="Documento";      $title2[3]="Descripción";   $title2[4]="Procede";   	   $title2[5]="Debe/Haber";    $title2[6]="Monto";      $title2[7]="Deduccion";   $title2[8]="Edición";
	
	$grid2="gridscg";   	

	
	if($ls_operacion == "NUEVO")
	{
		$ls_estmanant="";
		$in_classmovbco->uf_validar_pago_anticipo($ls_estmanant);
		if ($ls_estmanant==0)
		{
			print("<script language=JavaScript>");
		    print(" alert('Este Proceso esta definido solo para el caso de Vialidad y Construcción SUCRE.');");
		    print(" location.href='sigespwindow_blank.php'");
		    print("</script>");			    
		}
		$ls_operacion= "" ;
		uf_nuevo();
		$ls_numcarord="";
		$ls_docmov="";
		$ls_chevau=$in_classmovbco->uf_generar_voucher($ls_empresa);
	}
	
	if($ls_operacion == "GUARDAR")
	{			
		$ls_provbene=$_POST["txtprovbene"];
		$ls_desproben=$_POST["txtdesproben"];
		$ls_operaban="CHEQUE";
		$ls_afecta="CONTABLE";
		if ($ls_operaban=="CHEQUE")
		 {
		 	$ls_mov_operacion="CH";
		 }
		$ls_tipo=$_POST["rb_provbene"];
		switch ($ls_tipo){
			case 'P':
				$ls_codpro=$ls_provbene;
				$ls_cedbene="----------";
				break;	
			case 'B':
				$ls_codpro="----------";
				$ls_cedbene=$ls_provbene;
				break;
			default:
				$ls_codpro="----------";
				$ls_cedbene="----------";
		}
		if(($ls_codconmov=="---")||(!empty($ls_codconmov)))
		{    
			if(($ls_codigoconcepto!="---")||(!empty($ls_codigoconcepto)))
			{
				$ls_codconmov=$ls_codigoconcepto;
			}
		}	
		$in_classmovbco->io_sql->begin_transaction();
		$ls_docant='---------------';
		$ls_monamo=0;
		$lb_valido=$in_classmovbco->uf_guardar_automatico2($ls_codban,$ls_cuenta_banco,$ls_docmov,$ls_mov_operacion,$ld_fecha,$ls_desmov,$ls_codconmov,$ls_codpro,$ls_cedbene,$ls_desproben,$ldec_montomov,$ldec_monobjret,$ldec_montoret,$ls_chevau,$ls_estmov,$li_estint,"$ls_opepre",$ls_estbpd,'SCBMOV',' ',$ls_estdoc,$ls_tipo,$ls_codfuefin,'1',$ls_docant,$ls_monamo);// se guarda el movimiento marcado como anticipo
		if($lb_valido)
		{
			$in_classmovbco->io_sql->commit();			
		}
		else
		{
			$in_classmovbco->io_sql->rollback();					
		}
		$msg->message($in_classmovbco->is_msg_error);
		uf_cargar_dt();			
	}
	if($ls_operacion == "ELIMINAR")
	{
		$in_classmovbco->io_sql->begin_transaction();
		$lb_valido=$in_classmovbco->uf_delete_all_movimiento($ls_docmov,$ls_codban,$ls_cuenta_banco,$ls_mov_operacion,$ls_estmov);
		if($lb_valido)
		{
			$in_classmovbco->io_sql->commit();
		}	
		else
		{
			$in_classmovbco->io_sql->rollback();			
		}
		$msg->message($in_classmovbco->is_msg_error);
		$ls_docmov="";
		uf_nuevo();
	}
	if($ls_operacion=="DELETESCG")
	{
		$ls_operaban="CHEQUE";		
		if(($ls_codconmov=="---")||(!empty($ls_codconmov)))
		{    
			if(($ls_codigoconcepto!="---")||(!empty($ls_codigoconcepto)))
			{
				$ls_codconmov=$ls_codigoconcepto;
			}
		}	
		$li_row_delete = $_POST["delete_scg"];
		$ls_codded     = trim($_POST["txtcodded".$li_row_delete]);
		$ls_cuentascg  = trim($_POST["txtcontable".$li_row_delete]);
		$ls_debhab     = trim($_POST["txtdebhab".$li_row_delete]);
		$ls_numdoc     = $_POST["txtdocscg".$li_row_delete];
		$ldec_montoscg = $_POST["txtmontocont".$li_row_delete];
		$ldec_montoscg = str_replace(".","",$ldec_montoscg);
		$ldec_montoscg = str_replace(",",".",$ldec_montoscg);
		$arr_movbco["codban"]=$ls_codban;
		$arr_movbco["ctaban"]=$ls_cuenta_banco;
		$arr_movbco["mov_document"]=$ls_docmov;
		$ld_fecdb=$fun->uf_convertirdatetobd($ld_fecha);
		if ($ls_operaban=="CHEQUE")
		{
			$ls_mov_operacion="CH";
		}
		$arr_movbco["codope"]=$ls_mov_operacion;
		$arr_movbco["fecha"]=$ld_fecha;
		$arr_movbco["estmov"]=$ls_estmov;
		if($ls_tipo=="P")
		{
			$arr_movbco["codpro"] =$ls_provbene;
			$arr_movbco["cedbene"]="----------";
		}
		else
		{
			$arr_movbco["cedbene"]=$ls_provbene;
			$arr_movbco["codpro"] ="----------";
		}
		$arr_movbco["monto_mov"]=$ldec_montomov;
		$arr_movbco["objret"]   =$ldec_monobjret;
		$arr_movbco["retenido"] =$ldec_montoret;
		$in_classmovbco->io_sql->begin_transaction();
		$lb_valido=$in_classmovbco->uf_delete_dt_scg($ls_docmov,$ls_codban,$ls_cuenta_banco,$ls_mov_operacion,$ls_estmov,$ls_numdoc,$ls_cuentascg,$ls_debhab,$ls_codded,$ldec_montoscg,'SCG');
		if (($lb_valido)&&($ls_debhab=="D"))
		{
			$lb_valido=$in_classmovbco->uf_delete_anticipo($ls_docmov,$ls_codban,$ls_cuenta_banco,$ls_mov_operacion,
			                                               $ls_estmov,$ls_cuentascg);
		}
		if($ls_codded!="00000")
		{
			if($ls_mov_operacion=="CH")
			{
				$ls_operacioncon="H";
			}			
			$ldec_monto=$ldec_montoscg;
			$lb_valido=$in_classmovbco->uf_update_montodelete($arr_movbco,$ls_cuenta_scg,'SCBMOV',$ls_desmov,$ls_docmov,$ls_operacioncon,$ldec_montoscg,$ldec_monobjret,'00000');
			$ldec_montoret=$ldec_montoret-$ldec_monto;
		}
		$msg->message($in_classmovbco->is_msg_error);
		if($lb_valido)
		{
			$in_classmovbco->io_sql->commit();
		}
		else
		{
			$in_classmovbco->io_sql->rollback();
		}
		uf_cargar_dt();
	}// fin del if
	  
	if($ls_operacion == "VERIFICAR_VAUCHER")
	{
		$ls_chevaux=$_POST["txtchevau"];
		$ls_operaban=$_POST["cmboperacion"];		
		$lb_existe=$in_classmovbco->uf_select_voucher($ls_chevaux);
		if($lb_existe)
		{
			$msg->message("Nº de Voucher ya existe, favor indicar otro");
			uf_cargar_dt();
		}
		else
		{
			uf_cargar_dt();
			$ls_chevau=$ls_chevaux;
		}
	}
	
	if($ls_operaban=='CH')
	{
		$lb_nd="";
		$lb_nc="";
		$lb_dp="";
		$lb_re="";
		$lb_ch="selected";		
	}
	if($ls_tipo=='-')
	{
		$rb_n="checked";
		$rb_p="";
		$rb_b="";			
	}
	if($ls_tipo=='P')
	{
		$rb_n="";
		$rb_p="checked";
		$rb_b="";			
	}
	if($ls_tipo=='B')
	{
		$rb_n="";
		$rb_p="";
		$rb_b="checked";			
	}
	
	if($ls_estdoc=='C')
	{
		$ls_readOnly_doc="readonly";
		$ls_readOnly_ban="readonly";
		$ls_readOnly_cta="readonly";
		$ls_readOnly_ctascg="readonly";
		$ls_readOnly_ope="onClick='return false'";		
	}
	else
	{
		$ls_readOnly_doc="";
		$ls_readOnly_ban="";
		$ls_readOnly_cta="";
		$ls_readOnly_ctascg="";
		$ls_readOnly_ope="";		
	}
?>
<form name="form1" method="post" action="" id="form1">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_banco->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_banco);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <br>
  <table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-ventana">
      <td height="22" colspan="6"><input name="hidmesabi" type="hidden" id="hidmesabi" value="true">
        Pagos de Anticipos
        <input name="hidestciespg" type="hidden" id="hidestciespg" value="<?php echo $li_estciespg; ?>">
      <input name="hidestciespi" type="hidden" id="hidestciespi" value="<?php echo $li_estciespi; ?>">
      <input name="hidestciescg" type="hidden" id="hidestciescg" value="<?php echo $li_estciescg; ?>"></td>
    </tr>
    <tr>
      <td height="19" colspan="6"><input name="estmov" type="hidden" id="estmov" value="<?php print $ls_estmov;?>">
      <input name="estcon" type="hidden" id="estcon" value="<?php print $ls_estcon;?>">
      <input name="estimpche" type="hidden" id="estimpche" value="<?php print $li_estimpche?>">      </td>
    </tr>
    <tr>
      <td width="107" height="22" style="text-align:right">Banco</td>
      <td colspan="3" style="text-align:left"><input name="txtcodban" type="text" id="txtcodban"  style="text-align:center" value="<?php print $ls_codban;?>" size="10" readonly>
        <a href="javascript:cat_bancos();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Bancos"></a>
        <input name="txtdenban" type="text" id="txtdenban" value="<?php print $ls_denban?>" size="51" class="sin-borde" readonly>      </td>
      <td width="95" style="text-align:right">Fecha</td>
      <td width="178" style="text-align:left"><input name="txtfecha" type="text" id="txtfecha"  style="text-align:center" value="<?php print $ld_fecha;?>" size="24" maxlength="10" datepicker="true" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);"></td>
    </tr>
      <script language="javascript">uf_validar_estatus_mes();</script>
	<tr>
      <td height="22" style="text-align:right">Cuenta</td>
      <td colspan="3" style="text-align:">
        <input name="txtcuenta" type="text" id="txtcuenta" style="text-align:center" value="<?php print $ls_cuenta_banco; ?>" size="30" maxlength="25" readonly>
        <a href="javascript:catalogo_cuentabanco();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Cuentas Bancarias"></a>
        <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion" style="text-align:left" value="<?php print $ls_dencuenta_banco; ?>" size="35" maxlength="254" readonly>
        <input name="txttipocuenta" type="hidden" id="txttipocuenta">
        <input name="txtdentipocuenta" type="hidden" id="txtdentipocuenta">      </td>
      <td style="text-align:right"><strong>Disponible</strong></td>
      <td style="text-align:left"><input name="txtdisponible" type="text" id="txtdisponible" style="text-align:right" size="24" value="<?php print $ldec_disponible;?>" readonly></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Documento</td>
      <td colspan="3" style="text-align:left"><input name="txtdocumento" type="text" id="txtdocumento" style="text-align:center" onBlur="rellenar_cad(this.value,15,'doc')" value="<?php print $ls_docmov;?>" size="24" maxlength="15" <?php print $ls_readOnly_doc; ?>></td>
      <td style="text-align:right">Contable</td>
      <td style="text-align:left"><input name="txtcuenta_scg" type="text" id="txtcuenta_scg" style="text-align:center" value="<?php print $ls_cuenta_scg;?>" size="24" readonly></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Operaci&oacute;n</td>
      <td><div align="left">
	      <input name="cmboperacion" type="text" id="cmboperacion" style="text-align:center" value="<?php print $ls_operaban;?>" size="10" readonly>         
      </div></td>
      <td align="right"></td>
      <td width="146" align="left"><div align="left">
	      <input name="tipo" type="hidden" id="tipo">         
      </div></td>
	  <td align="right">Voucher</td>
      <td style="text-align:left">
								  <input name="txtchevau" type="text" id="txtchevau" value="<?php print $ls_chevau ?>" style="text-align:center" size="28" maxlength="25" onChange="javascript:ue_verificar_vaucher();" onBlur="javascript:rellenar_cad(this.value,25,'voucher');" onKeyPress="return keyRestrict(event,'0123456789'); ">							
	  </td>	  
      <td align="right">
	  </td>
    </tr>
    <tr>
     <td height="22" style="text-align:right">Tipo Concepto</td>
      <td colspan="2" style="text-align:left"><?php $obj_con->uf_cargar_conceptos($ls_mov_operacion,$ls_codconmov);	?>
	  <input name="codconmov" type="hidden" id="codconmov" value="<?php print $ls_codconmov;?>"></td>  
      <td colspan="2" style="text-align:right"></td>
      <td style="text-align:left"></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Concepto </td>
      <td colspan="5" style="text-align:left"><input name="txtconcepto" type="text" id="txtconcepto" value="<?php print $ls_desmov;?>" size="122" maxlength="254" onKeyPress="return keyRestrict(event,'0123456789'+'abcdefghijklmnopqrstuvwxyzñ .,*/-()$%&!ºªáéíóú[]{}<>')"></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Tipo Destino</td>
      <td colspan="2" style="text-align:left"><table width="241" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td width="239"><input type="radio" name="rb_provbene" id="radio" value="P" class="sin-borde" onClick="javascript:uf_verificar_provbene(this.checked,document.form1.tipo.value);" <?php print $rb_p; ?> >
            Proveedor
              <input type="radio" name="rb_provbene" id="radio" value="B" class="sin-borde" onClick="javascript:uf_verificar_provbene(this.checked,document.form1.tipo.value);" <?php print $rb_b; ?>>
            Beneficiario
            <input name="rb_provbene" id="radio" type="radio" class="sin-borde" value="-" <?php print $rb_n; ?> onClick="javascript:uf_verificar_provbene(this.checked,document.form1.tipo.value);">
            Ninguno</td>
        </tr>
      </table></td>
      <td colspan="3"><input name="txtprovbene" type="text" id="txtprovbene" style="text-align:center" value="<?php print $ls_provbene?>" size="24" readonly>
      <a href="javascript:catprovbene()"><img id="bot_provbene" src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo Proveedores/Beneficiarios" width="15" height="15" border="0"></a>
      <input name="txtdesproben" type="text" id="txtdesproben" size="42" maxlength="250" class="sin-borde" value="<?php print $ls_desproben;?>"  readonly>
      <input name="txttitprovbene" type="hidden" class="sin-borde" id="txttitprovbene" style="text-align:right" size="15" readonly></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Monto</td>
      <td width="188"><div align="left">
	  <input name="txtmonto" type="text" id="txtmonto" style="text-align:right" onBlur="javascript:uf_format(this);uf_montoobjret(this);uf_verificar_monto(this);" onKeyPress="return(currencyFormat(this,'.',',',event));" value="<?php print number_format($ldec_montomov,2,",",".");?>" size="24" maxlength="22">
      </div></td>
      <td width="64"><div align="right">M.O.R.</div></td>
      <td>
        <div align="left">
          <input name="txtmonobjret" type="text" id="txtmonobjret" style="text-align:right" onBlur="javascript:uf_format(this);javascript:uf_verificar_retenido();" onKeyPress="return(currencyFormat(this,'.',',',event));" value="<?php print  number_format($ldec_monobjret,2,",",".");?>" size="24" maxlength="22">
        </div></td>
      <td><div align="right">Monto Retenido</div></td>
      <td><div align="left">
        <input name="txtretenido" type="text" id="txtretenido" style="text-align:right" value="<?php print number_format($ldec_montoret,2,",",".");?>" size="24" maxlength="22" readonly>
      </div></td>
    </tr>
    <tr>
      <td height="22">Fte. Financiamiento</td>
      <td><input name="txtftefinanciamiento" type="text" id="txtftefinanciamiento" style="text-align:center" value="<?php print $ls_codfuefin;?>" size="3" maxlength="2" readonly>
        <a href="javascript: uf_cat_fte_financia();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo Fuente de Financiamiento" width="15" height="15" border="0"></a>
        <input name="txtdenftefinanciamiento" type="text" class="sin-borde" id="txtdenftefinanciamiento" value="<?php print $ls_denfuefin;?>" readonly></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>    
    <tr>
      <td height="13" colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td height="21" colspan="6"><table width="613" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td><div align="center"><a href="#01">Detalle Contable/Retenciones </a></div>            <div align="center"><a href="#03"></a></div></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td height="13"><div align="right"> </div> <a href="#01"> </a></td>
      <td height="13" colspan="2">&nbsp;</td>
      <td height="13" colspan="2">&nbsp;</td>
      <td height="13">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="6">&nbsp;&nbsp;<a href="javascript: uf_agregar_dtcon();"><img src="../shared/imagebank/tools/nuevo.gif" alt="Detalle Contable" width="15" height="15" border="0">Agregar detalle Contable </a><a href="javascript: uf_agregar_dtret('$ls_mov_operacion');"><img src="../shared/imagebank/tools/nuevo.gif" alt="Detalle Deducciones" width="15" height="15" border="0">Agregar detalle Retenciones </a> </td>
    </tr>
    <tr>
      <td height="22" colspan="6"><div align="center">
        <a name="01" id="01"></a>
        <?php $io_grid->makegrid($li_row,$title2,$objectScg,770,'Detalles Contable',$grid2);?>
          <input name="totcon"  type="hidden" id="totcon"  size=5 value="<?php print $li_row ?>">
          <input name="lastscg" type="hidden" id="lastscg" size=5 value="<?php print $lastscg;?>">
          <input name="delete_scg" type="hidden" id="delete_scg" size=5>         
      </div></td>
    </tr>
    <tr>
      <td height="22" colspan="6"><table width="210" border="0" align="right" cellpadding="0" cellspacing="0">
          <tr>
            <td width="82" height="20"><div align="right">Total Debe</div></td>
            <td width="128"><input name="txtdebe" type="text" id="txtdebe" value="<?php print number_format($ldec_mondeb,2,',','.');?>" style="text-align:right" readonly></td>
          </tr>
          <tr>
            <td height="20"><div align="right">Total Haber</div></td>
            <td><input name="txthaber" type="text" id="txthaber" value="<?php print number_format($ldec_monhab,2,',','.');?>" style="text-align:right" readonly></td>
          </tr>
          <tr>
            <td height="20"><div align="right">Diferencia</div></td>
            <td><input name="txtdiferencia" type="text" id="txtdiferencia" value="<?php print number_format($ldec_diferencia,2,',','.');?>" style="text-align:right" readonly></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td height="22" colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="6"><div align="center"><a href="#00">Volver Arriba</a> </div></td>
    </tr>
  </table>
  <p><input name="operacion" type="hidden" id="operacion">
    <input name="status_doc" type="hidden" id="status_doc" value="<?php print $ls_estdoc;?>">
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
	   f.action="sigesp_scb_p_pago_anticipo.php";
	   f.submit();
	 }
}

function ue_guardar()
{
  lb_mesabi = f.hidmesabi.value;
  if (lb_mesabi=='true')
     {
	  lb_valido = true;  
	  li_totrowscg = f.totcon.value;
	  if (li_totrowscg>1)
	  {
		lb_valido = uf_evaluate_cierre('SCG');
	  }
	  if (lb_valido)
		 {
		   ls_status = f.estmov.value;
		   if ((ls_status!='C')&&(ls_status!='O')&&(ls_status!='A'))
			  {
				ls_codban    = f.txtcodban.value;
				ls_ctaban    = f.txtcuenta.value;
				ls_operacion = f.cmboperacion.value;
				if (ls_operacion=="CHEQUE")
				{
					ls_operacion="CH";
				}	
				ls_documento = f.txtdocumento.value;
				li_lastscg   = f.lastscg.value;
				li_newrow    = parseInt(li_lastscg,10)+1;
				ls_cuenta_scg  = f.txtcuenta_scg.value;
				ls_descripcion = f.txtconcepto.value;
				ls_procede    = "SCBMOV";
				ldec_monto    = f.txtmonto.value;
				ls_cuenta_scg = f.txtcuenta_scg.value;
				ld_fecha      = f.txtfecha.value;
				ls_cuenta_scg = f.txtcuenta_scg.value;
				total         = f.totcon.value;
				ldec_monobjret=f.txtmonobjret.value;
				ldec_monret=f.txtretenido.value;
				ls_nomproben=f.txtdesproben.value;
				ls_estmov="N";
				li_estint=0;
				lb_anula=0;		
				li_cobrapaga="0";	// afectaciòn CCP, compromete, causa y paga					    
				if (ls_operacion=="CH")
				   {
					 ls_chevau = f.txtchevau.value;
					 lb_valido = true;
				   }
				else
				   {
					 ls_chevau = " ";
					 lb_valido = true;
				   }		   
				if (f.rb_provbene[0].checked)
				   {
					 ls_tipo="P";
				   }
				if (f.rb_provbene[1].checked)
				   {
					 ls_tipo="B";
				   }
				if (f.rb_provbene[2].checked)
				   {
					 ls_tipo="-";
				   }
				ls_provbene=f.txtprovbene.value;
				ldec_objret=ldec_monobjret;
				while(ldec_objret.indexOf('.')>0)
				{//Elimino todos los puntos o separadores de miles
				  ldec_objret=ldec_objret.replace(".","");
				}
				ldec_objret=ldec_objret.replace(",",".");
				while(ldec_monto.indexOf('.')>0)
				{//Elimino todos los puntos o separadores de miles
				  ldec_monto=ldec_monto.replace(".","");
				}
				ldec_monto=ldec_monto.replace(",",".");
				while(ldec_monret.indexOf('.')>0)
				{//Elimino todos los puntos o separadores de miles
				  ldec_monret=ldec_monret.replace(".","");
				}
				ldec_monret=ldec_monret.replace(",",".");
				if((lb_valido)&&(ls_provbene!="")&&(ls_descripcion!="")&&(ls_codban!="")&(ls_ctaban!="")&&(ls_documento!="")&&(ls_operacion!="")&&(ldec_monto>0))
				   {
					 ld_totmondis = f.txtdisponible.value;
					 ls_tipvaldis = "<?php echo $ls_tipvaldis; ?>";
					 lb_valido    = uf_validar_disponible("CH",ls_tipvaldis,ld_totmondis,f.txtmonto.value);
					 if (lb_valido)
						{
						  f.operacion.value = "GUARDAR";
						  f.action = "sigesp_scb_p_pago_anticipo.php";
						  f.submit();					
						}
				   }
				else
				   {
					 alert("No ha completado los datos");
				   }
			  }
		   else
			  {
				alert("No puede realizar esta operacion el movimiento ya fue Contabilizado o Anulado");
			  }	 
		 }
    }
  else
     {
	   alert("Operación No puede ser procesada, El Més está Cerrado !!!");
	 }
}

function ue_eliminar()
{
  lb_mesabi = f.hidmesabi.value;
  if (lb_mesabi=='true')
     {
	  lb_valido = true;
	  li_totrowscg = f.totcon.value;
	  if (li_totrowscg>1)
		{
			lb_valido = uf_evaluate_cierre('SCG');
		}	 
		
	  if (lb_valido)
		 {
		   ls_status    = f.estmov.value;
		   ls_operacion = f.cmboperacion.value;
		   if (ls_operacion=="CHEQUE")
			 {
				ls_operacion="CH";
			 }	   
		   ls_estcon    = f.estcon.value;
		   if (ls_operacion!="CH")
			  {
				if ((ls_status!='C')&&(ls_status!='O')&&(ls_status!='A')&& (ls_estcon!='1'))
				   {
					 li_lastscg=f.lastscg.value;
					 li_newrow=parseInt(li_lastscg,10)+1;
					 ls_cuenta_scg=f.txtcuenta_scg.value;
					 ls_descripcion=f.txtconcepto.value;
					 ls_procede="SCBMOV";
					 ls_documento=f.txtdocumento.value;
					 ldec_monto=f.txtmonto.value;
					 ls_cuenta_scg=f.txtcuenta_scg.value;
					 ld_fecha=f.txtfecha.value;
					 ls_codban=f.txtcodban.value;
					 ls_ctaban=f.txtcuenta.value;
					 ls_cuenta_scg=f.txtcuenta_scg.value;
					 total=f.totcon.value;
					 ldec_monobjret=f.txtmonobjret.value;
					 ldec_monret=f.txtretenido.value;
					 ls_nomproben=f.txtdesproben.value;
					 ls_estmov="N";
					 li_estint=0;
					  li_cobrapaga="0";	// afectaciòn CCP, compromete, causa y paga				 
					 if (ls_operacion=="CH")
						{
						  ls_chevau=f.txtchevau.value;
						  lb_valido=true;
						}
					 else
						{
						  ls_chevau="";
						  lb_valido=true;
						}			     
					 if (f.rb_provbene[0].checked)
						{
						  ls_tipo="P";
						}
					 if (f.rb_provbene[1].checked)
						{
						  ls_tipo="B";
						}
					 if (f.rb_provbene[2].checked)
						{
						  ls_tipo="-";
						}
					 ls_provbene=f.txtprovbene.value;
					 ldec_objret=ldec_monobjret;
					 while(ldec_objret.indexOf('.')>0)
					 {//Elimino todos los puntos o separadores de miles
					   ldec_objret=ldec_objret.replace(".","");
					 }
					 ldec_objret=ldec_objret.replace(",",".");
					 while(ldec_monto.indexOf('.')>0)
					 {//Elimino todos los puntos o separadores de miles
					   ldec_monto=ldec_monto.replace(".","");
					 }
					 ldec_monto=ldec_monto.replace(",",".");
					 while(ldec_monret.indexOf('.')>0)
					 {//Elimino todos los puntos o separadores de miles
					   ldec_monret=ldec_monret.replace(".","");
					 }
					 ldec_monret=ldec_monret.replace(",",".");
					 if ((lb_valido)&&(ls_provbene!="")&&(ls_descripcion!="")&&(ls_codban!="")&(ls_ctaban!="")&&(ls_documento!="")&&(ls_operacion!=""))
						{
						  if (confirm("Esta seguro de Eliminar el Documento?,\n esta operación no se puede deshacer." ))
							 {
							   f.operacion.value ="ELIMINAR";
							   f.action="sigesp_scb_p_pago_anticipo.php";
							   f.submit();
							 }
						}
					 else
						{
						  alert("Seleccione un documento valido, o que ya este registrado");
						}
				   }
				else
				   {
					 if (ls_status=='C')
						{
						  alert("No puede eliminar el movimiento, ya fue Contabilizado");
						}
					 else if((ls_status=='O') || (ls_status=='A'))
						{
						  alert("No puede eliminar el movimiento, Anulado");
						}
					 else
						{
						  alert("No puede eliminar el movimiento, ya fue Conciliado");
						}
				   }
			  }
		   else
			  {
				if (ls_operacion=="CH")
				   {
					 alert("Los Cheques deben ser eliminados a través de Eliminación de Cheques no Contabilizados");
				   }
				else
				   {
					 alert("Las Carta Orden deben ser eliminadas a través de Eliminación de Carta Orden no Contabilizada");
				   }
			  }	   
		 }
     }
  else
     {
	   alert("Operación No puede ser procesada, El Més está Cerrado !!!");
	 }
}  

function ue_imprimir(ls_reporte)
{
	ls_numdoc=f.txtdocumento.value;
	ls_codope=f.cmboperacion.value;	
	if (ls_codope=="CHEQUE")
	{
		ls_codope="CH";
	}
	ls_status=f.estmov.value;	
	if(ls_codope=='CH')
	{
		ls_chevau=f.txtchevau.value;
	}
	else
	{
		ls_chevau="";
	}
	ls_codban=f.txtcodban.value;
	ls_ctaban=f.txtcuenta.value;
	if((ls_numdoc!="")&&(ls_codban!="")&&(ls_ctaban!="")&&(ls_codope!=""))
	{			
			ls_pagina="reportes/"+ls_reporte+"?codban="+ls_codban+"&ctaban="+ls_ctaban+"&numdoc="+ls_numdoc+"&chevau="+ls_chevau+"&codope="+ls_codope;
				window.open(ls_pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
			
	}
	else
	{
		alert("Seleccione un documento valido, o que ya este registrado");
	}
	
}

function ue_buscar()
{
	var x=document.body.clientWidth;
	var y=(document.body.clientHeight)-200;
	window.open("sigesp_cat_pago_anticipo.php?opener=sigesp_scb_p_pago_anticipo.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width="+x+",height="+y+",left=0,top=0,location=no,resizable=yes");
}

function rellenar_cad(cadena,longitud,campo)
{
	if (cadena!="")
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
		if(campo=="cmp")
		{
			document.form1.txtcomprobante.value=cadena;
		}
		if(campo=="cod")
		{
			document.form1.txtcodigo.value=cadena;
		}
		if(campo=="chequera")
		{
			document.form1.txtchequera.value=cadena;
		}
		if(campo=="numcheque")
		{
			document.form1.txtnumcheque.value=cadena;
		}
		if(campo=="desde")
		{
			document.form1.txtdesde.value=cadena;
		}
		if(campo=="hasta")
		{
			document.form1.txthasta.value=cadena;
		}
		if(campo=="voucher")
		{
			document.form1.txtchevau.value=cadena;
		}
	}
}

function catalogo_cuentabanco()
{
  uf_validar_estatus_mes();
  if (uf_evaluate_cierre('SCG'))
     {
       ls_status = f.estmov.value;
       if ((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A'))
          {
		    ls_codban=f.txtcodban.value;
		    ls_nomban=f.txtdenban.value;
		    if ((ls_codban!=""))
		       {
			     pagina="sigesp_cat_ctabanco.php?codigo="+ls_codban+"&hidnomban="+ls_nomban;
			     window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=720,height=400,resizable=yes,location=no");
		       }
		    else
		       {
			     alert("Debe seleccionar el Banco asociado a la cuenta");   
		       }
		  }
	   else
	      {
		    alert("El Movimiento ya fue Contabilizado o esta Anulado !!!");   
	      }  
	 }
}
	 	 
function cat_bancos()
{
  if (uf_evaluate_cierre('SCG'))
     {
       ls_status=f.estmov.value;
	   if ((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A'))
	      {
	   	    pagina="sigesp_cat_bancos.php";
	   	    window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
	      }
	   else
	      {
		    alert("El Movimiento ya fue Contabilizado o esta Anulado !!!");
	      }	 
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

function cat_conceptos()
{
  uf_validar_estatus_mes();
	ls_status=f.estmov.value;
	if((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A'))
	{
		pagina="sigesp_cat_conceptos.php";
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("El Movimiento ya fue Contabilizado o esta Anulado !!!");   
	}
}
     
function uf_desaparecer(objeto)
{
  eval("document.form1."+objeto+".style.visibility='hidden'");
}
   
function uf_aparecer(objeto)
{
  eval("document.form1."+objeto+".style.visibility='visible'");
}
   
function catprovbene()
{
  uf_validar_estatus_mes();
  if (uf_evaluate_cierre('SCG'))
     {
	   ls_status = f.estmov.value;
	   if ((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A'))
	      {
		    if (f.rb_provbene[0].checked==true)
		       {
			     f.txtprovbene.disabled=false;	
			     window.open("sigesp_catdinamic_prov.php","Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
		       }
		    else if(f.rb_provbene[1].checked==true)
		       {
			     f.txtprovbene.disabled=false;	
			     window.open("sigesp_catdinamic_bene.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
		       }
	      }
	   else
	      {
		    alert("El Movimiento ya fue contabilizado o esta Anulado");
	      }
     }
}   

function uf_verificar_provbene(lb_checked,obj)
{
	ls_status=f.estmov.value;
	if((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A'))
	{
		if((f.rb_provbene[0].checked)&&(obj!='P'))
		{
			f.tipo.value='P';
			f.txtprovbene.value="";
			f.txtdesproben.value="";
			f.txttitprovbene.value="Proveedor";
		}
		if((f.rb_provbene[1].checked)&&(obj!='B'))
		{
			f.txtprovbene.value="";
			f.txtdesproben.value="";
			f.tipo.value='B';
			f.txttitprovbene.value="Beneficiario";
		}
		if((f.rb_provbene[2].checked)&&(obj!='N'))
		{
			f.txtprovbene.value="----------";
			f.txtdesproben.value="Ninguno";
			f.tipo.value='N';
			f.txttitprovbene.value="";
		}
	}
	else
	{
		alert("El Movimiento ya fue contabilizado o esta Anulado");
	}
}

function  uf_agregar_dtcon()
{
  lb_mesabi = f.hidmesabi.value;
  if (lb_mesabi=='true')
     {
	  if (uf_evaluate_cierre('SCG'))
		 {
		   ls_status=f.estmov.value;
		   if ((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A'))
			  {
				ls_opera=f.cmboperacion.value;		
				ls_estdoc=f.status_doc.value;
				li_lastscg=f.lastscg.value;
				li_newrow=parseInt(li_lastscg,10)+1;
				ls_cuenta_scg=f.txtcuenta_scg.value;
				ls_descripcion=f.txtconcepto.value;
				ls_procede="SCBMOV";
				ls_documento=f.txtdocumento.value;
				ldec_monto=f.txtmonto.value;
				ls_cuenta_scg=f.txtcuenta_scg.value;
				ld_fecha=f.txtfecha.value;
				ls_codban=f.txtcodban.value;
				ls_ctaban=f.txtcuenta.value;
				ls_cuenta_scg=f.txtcuenta_scg.value;
				total=f.totcon.value;
				ldec_monobjret=f.txtmonobjret.value;
				ldec_monret=f.txtretenido.value;
				ls_nomproben=f.txtdesproben.value;
				ls_codconmov=f.ddlb_conceptos.value;
				ls_codfuefin=f.txtftefinanciamiento.value;
							
				if (ls_opera=="CHEQUE")
				   { 			
					 ls_operacion="CH";// operacion Cheque
					 li_cobrapaga="2"; // tipo de Afectacion "CONTABLE"
					 ls_chevau=f.txtchevau.value;
					 lb_valido=true;			
				   }			
				if (f.rb_provbene[0].checked)
				   {
					 ls_tipo="P";
				   }
				if (f.rb_provbene[1].checked)
				   {
					 ls_tipo="B";
				   }
				if (f.rb_provbene[2].checked)
				   {
					 ls_tipo="-";
				   }
				ls_provbene=f.txtprovbene.value;
				ldec_objret=ldec_monobjret;
		
				ldec_objret=uf_convertir_monto(ldec_objret);
		
				ldec_monto=uf_convertir_monto(ldec_monto);
		
				ldec_monret=uf_convertir_monto(ldec_monret);
				li_estint=0;
				ls_estmov='N';
				ls_anticipo='1';
				if ((lb_valido)&&(ls_provbene!="")&&(ls_descripcion!="")&&(ls_codban!="")&(ls_ctaban!="")&&(ls_documento!="")&&(ls_operacion!="")&&(ldec_monto>0))
				   {
					 ld_totmondis = f.txtdisponible.value;
					 ls_tipvaldis = "<?php echo $ls_tipvaldis; ?>";
					 lb_valido    = uf_validar_disponible("CH",ls_tipvaldis,ld_totmondis,f.txtmonto.value);
					 if (lb_valido)
						{
						  ls_pagina = "sigesp_w_regdt_contable.php?txtprocedencia=SCBMOV&mov_document="+ls_documento+"&fecha="+ld_fecha+"&procede="+ls_procede+"&tipo="+ls_tipo+
									  "&provbene="+ls_provbene+"&descripcion="+ls_descripcion+"&codban="+ls_codban+"&ctaban="+ls_ctaban+"&cuenta_scg="+ls_cuenta_scg+
									  "&mov_operacion="+ls_operacion+"&monto="+ldec_monto+"&objret="+ldec_monobjret+"&retenido="+ldec_monret+"&chevau="+ls_chevau+
									  "&estint="+li_estint+"&cobrapaga="+li_cobrapaga+"&estbpd=M&txtnomproben="+ls_nomproben+"&estmov="+ls_estmov+"&codconmov="+ls_codconmov+
									  "&tip_mov= &opener=sigesp_scb_p_pago_anticipo.php&estdoc="+ls_estdoc+"&codfuefin="+ls_codfuefin+"&anticipo="+ls_anticipo;
						  window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=580,height=210,left=50,top=50,location=no,resizable=yes,dependent=yes");
						}
				   }
				else
				   {
					 alert("Complete los datos del Movimiento");
				   }
			  }
		   else
			  {
				alert("El Movimiento ya fue Contabilizado o esta Anulado !!!");
			  }	 
		 }
     }
  else
     {
	   alert("Operación No puede ser procesada, El Més está Cerrado !!!");
	 }
}
    
function  uf_agregar_dtret(operacion)
{
  lb_mesabi = f.hidmesabi.value;
  if (lb_mesabi=='true')
     { 
	  if (uf_evaluate_cierre('SCG'))
		 {
		   ls_status=f.estmov.value;
		   if ((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A'))
			  {
				ls_estdoc=f.status_doc.value;
				ldec_monobjret=f.txtmonobjret.value;
				ls_operacion=f.cmboperacion.value;
				ls_estmov="N";
				if (ls_operacion=="CHEQUE")
				{
					ls_operacion="CH";
				}		
				li_lastscg=f.lastscg.value;
				li_newrow=parseInt(li_lastscg,10)+1;
				ls_cuenta_scg=f.txtcuenta_scg.value;
				ls_descripcion=f.txtconcepto.value;
				ls_procede="SCBMOV";
				ls_documento=f.txtdocumento.value;
				ldec_monto=f.txtmonto.value;
				ls_cuenta_scg=f.txtcuenta_scg.value;
				ld_fecha=f.txtfecha.value;
				ls_codban=f.txtcodban.value;
				ls_ctaban=f.txtcuenta.value;
				ls_cuenta_scg=f.txtcuenta_scg.value;
				total=f.totcon.value;
				ldec_objret=f.txtmonobjret.value;
				ldec_monret=f.txtretenido.value;
				ls_codconmov=f.ddlb_conceptos.value;
				ls_codfuefin=f.txtftefinanciamiento.value;
				li_estint=0;
				while(ldec_objret.indexOf('.')>0)
				{//Elimino todos los puntos o separadores de miles
					ldec_objret=ldec_objret.replace(".","");
				}
				ldec_objret=ldec_objret.replace(",",".");
				while(ldec_monto.indexOf('.')>0)
				{//Elimino todos los puntos o separadores de miles
					ldec_monto=ldec_monto.replace(".","");
				}
				ldec_monto=ldec_monto.replace(",",".");
				if(ldec_monto==0)
				{
					alert("Monto del movimiento no puede ser igual a cero");
					lb_valido=false;
				}
				else
				{
					lb_valido=true;
				}
				if((ldec_objret==0)||(lb_valido==false))
				{
					alert("Monto Objeto a Retención no debe ser igual a cero");
					lb_valido=false;
				}
				else
				{
					lb_valido=true;
				}
				
				if(ls_operacion=="CH")
				{			
					 li_cobrapaga="0";	// afectaciòn CCP, compromete, causa y paga			
				}			
				if(ls_operacion=="CH")
				{
					ls_chevau=f.txtchevau.value;			
					lb_valido=true;				
				}
				else
				{
					ls_chevau=" ";
				}			
				if(f.rb_provbene[0].checked)
				{
					ls_tipo="P";
				}
				if(f.rb_provbene[1].checked)
				{
					ls_tipo="B";
				}
				if(f.rb_provbene[2].checked)
				{
					ls_tipo="-";
				}
				ls_provbene=f.txtprovbene.value;
				ls_nomproben=f.txtdesproben.value;
				if(lb_valido)
				{
					if((ls_provbene!="")&&(ls_descripcion!="")&&(ls_codban!="")&(ls_ctaban!="")&&(ls_documento!="")&&(ls_operacion!="")&&(ldec_monto>0)&&(ldec_objret>0))
					{
						if(ls_operacion=="CH")
						{
						  ld_totmondis = f.txtdisponible.value;
						  ls_tipvaldis = "<?php echo $ls_tipvaldis; ?>";
						  lb_valido    = uf_validar_disponible("CH",ls_tipvaldis,ld_totmondis,f.txtmonto.value);
						  if (lb_valido)
							 { 
							   ls_pagina = "sigesp_w_regdt_deducciones.php?txtdocumento="+ls_documento+"&txtprocede=SCBMOV&mov_document="+ls_documento+"&fecha="+ld_fecha+
										   "&procede="+ls_procede+"&tipo="+ls_tipo+"&provbene="+ls_provbene+"&descripcion="+ls_descripcion+"&codban="+ls_codban+
										   "&ctaban="+ls_ctaban+"&cuenta_scg="+ls_cuenta_scg+"&mov_operacion="+ls_operacion+"&monto="+ldec_monto+"&objret="+ldec_objret+
										   "&retenido="+ldec_monret+"&chevau="+ls_chevau+"&estint="+li_estint+"&cobrapaga="+li_cobrapaga+
										   "&estbpd=M&txtnomproben="+ls_nomproben+"&estmov="+ls_estmov+"&codconmov="+ls_codconmov+
										   "&tip_mov= &opener=sigesp_scb_p_pago_anticipo.php&estdoc="+ls_estdoc+"&codfuefin="+ls_codfuefin;
							   window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=570,height=350,left=50,top=50,location=no,resizable=no,dependent=yes");
							 }
						}
						else
						{
							alert("Al Movimiento no aplican retenciones");
						}
					}
					else
					{
						alert("Complete los datos del Movimiento");
					}
				}
			}
		   else
			  {
				alert("El Movimiento ya fue Contabilizado o esta Anulado !!!");
			  }
		 }
     }
  else
     {
	   alert("Operación No puede ser procesada, El Més está Cerrado !!!");
	 }
}
   
function uf_delete_Scg(row)
{
  lb_mesabi = f.hidmesabi.value;
  if (lb_mesabi=='true')
     {
	  if (uf_evaluate_cierre('SCG'))
		 {
		   ls_status = f.estmov.value;
		   if ((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A'))
			  {
				ls_cuenta	   = eval("f.txtcontable"+row+".value");
				ls_documento   = eval("f.txtdocscg"+row+".value");
				ls_descripcion = eval("f.txtdesdoc"+row+".value");
				ls_procede=eval("f.txtprocdoc"+row+".value");
				ls_debhab=eval("f.txtdebhab"+row+".value");
				ldec_montocont=eval("f.txtmontocont"+row+".value");
				if ((ls_cuenta!="")&&(ls_documento!="")&&(ls_descripcion!="")&&(ls_procede!="")&&(ls_debhab!=""))
				   {
					 f.operacion.value="DELETESCG";
					 f.delete_scg.value=row;
					 f.action="sigesp_scb_p_pago_anticipo.php";
					 f.submit();
				   }
				else
				   {
					 alert("No hay datos para eliminar");
				   }
			  }
		   else
			  {
				alert("El Movimiento ya fue Contabilizado o esta Anulado !!!");
			  }	 
		 }
     }
  else
     {
	   alert("Operación No puede ser procesada, El Més está Cerrado !!!");
	 }
}
   
function uf_delete_Ret(row)
{
  lb_mesabi = f.hidmesabi.value;
  if (lb_mesabi=='true')
     {
	  if (uf_evaluate_cierre('SCG'))
		 {
		   ls_status=f.estmov.value;
		   if ((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A'))
			  {
				f.operacion.value="DELETERET";
				f.delete_ret.value=row;
				f.action="sigesp_scb_p_pago_anticipo.php";
				f.submit();
			  }
		   else
			  {
				alert("El Movimiento ya fue Contabilizado o esta Anulado !!!");
			  }
		 }
     }
  else
     {
	   alert("Operación No puede ser procesada, El Més está Cerrado !!!");
	 }
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
      
function uf_verificar_retenido()
{
	ldec_monret=f.txtretenido.value;
	ldec_monret=uf_convertir_monto(ldec_monret);
	ldec_monobjret=f.txtmonobjret.value;
	ldec_monobjret=uf_convertir_monto(ldec_monobjret);
	ldec_monto=f.txtmonto.value;
	ldec_monto=uf_convertir_monto(ldec_monto);
	ldec_monto     = parseFloat(ldec_monto);
	ldec_monobjret = parseFloat(ldec_monobjret);
	if(ldec_monto>ldec_monobjret)
	{
		if(ldec_monret>0)
		{
			f.txtmonobjret.readOnly=true;
			alert("Error no puede modificar el monto objeto a retención porque ya se realizaron retenciones en base al mismo");
		}			
	}
	else
	{
		alert("Monto Objeto a Retención no puede ser mayor al monto del movimiento !!!");
		f.txtmonobjret.value=uf_convertir(ldec_monto);
	}		
}   

function  uf_montoobjret(obj)
{
  ldec_monto=obj.value;
  f.txtmonobjret.value=ldec_monto;
}
  
function ue_verificar_vaucher()
{
  f.operacion.value="VERIFICAR_VAUCHER";
  f.submit();
}
  
function uf_verificar_monto(monto)
{
	ld_debe=parseFloat(uf_convertir_monto(f.txtdebe.value));
	ld_haber=parseFloat(uf_convertir_monto(f.txthaber.value));
	ls_max=Math.max(ld_debe,ld_haber);
	ld_monto=parseFloat(uf_convertir_monto(monto.value));
	if(ld_monto<ls_max)
	{
		alert("El monto total del movimiento no debe ser inferior a los asientos contables realizados");
		monto.value="0,00";
	}
}

function uf_cat_fte_financia()
{
  uf_validar_estatus_mes();
  ls_status=f.estmov.value;
  if ((ls_status!='C')&&(ls_status!='O')&&(ls_status!='A'))
     {
       pagina="sigesp_sep_cat_fuente.php";
       window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
     }
  else
     {
	   alert("No puede realizar esta operacion el movimiento ya fue Contabilizado o Anulado");
     }  
}  
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>