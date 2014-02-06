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
$io_fun_banco->uf_load_seguridad("SCB","sigesp_scb_p_opd_causapaga.php",$ls_permisos,$la_seguridad,$la_permisos);
$li_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
$li_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
$li_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
$li_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
$li_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
$li_estciespg = $io_fun_banco->uf_load_estatus_cierre($li_estciespi,$li_estciescg);

$li_diasem    = date('w');
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
<title>Orden de Pago Directa con Compromiso Previo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	background-color: #CCCCCC;
	margin-left: 0px;
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
<link href="../shared/css/general.css"  rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css"   rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/valida_fecha.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_scb.js"></script>
<script language="javascript">
	function uf_valida_cuadre()
	{
		f=document.form1;
		ldec_diferencia=f.txtdiferencia.value;
		ldec_diferencia=uf_convertir_monto(ldec_diferencia);
		ls_operacion=f.operacion.value;
		alert(ls_operacion);
		if((ldec_diferencia!=0)&&((ls_operacion=="")||(ls_operacion=="GUARDAR")))
		{
			alert("Comprobante descuadrado Contablemente");
			f.operacion.value="CARGAR_DT";
			f.action="sigesp_scb_p_movbanco.php";
			f.submit();
		}
	}
</script>
<style type="text/css">
<!--
.Estilo1 {color: #6699CC}
-->
</style>
</head>
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
    <td height="25" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
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
    <td class="toolbar" width="22"><div align="center"><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" title="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" title="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript:ue_imprimir();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" title="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript:ue_generar_cmp();"><img src="../shared/imagebank/tools20/ejecutar.gif" title="Generar Comprobante de Retenci&oacute;n IVA"  width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript:ue_generar_cmp_mun();"><img src="../shared/imagebank/tools20/ejecutar.gif" title="Generar Comprobante de Retenci&oacute;n Municipal" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript:ue_generar_cmp_islr();"><img src="../shared/imagebank/tools20/ejecutar.gif" title="Comprobante ISLR" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="580"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" title="Ayuda" width="20" height="20"></td>
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
	require_once("../shared/class_folder/grid_param.php");
	$io_msg		= new class_mensajes();	
	$fun		= new class_funciones();	
	$lb_guardar = true;
    $sig_inc    = new sigesp_include();
    $con		= $sig_inc->uf_conectar();
 	$obj_spg	= new ddlb_operaciones_spg($con);
 	$obj_spi	= new ddlb_operaciones_spi($con);
 	$obj_con	= new ddlb_conceptos($con);
	$io_grid	= new grid_param();

	require_once("sigesp_scb_c_ordenpago.php");
	$in_classmovbco=new sigesp_scb_c_ordenpago($la_seguridad);
	require_once("sigesp_scb_c_config.php");
	$io_config=new sigesp_scb_c_config($la_seguridad);
	$arr_config=$io_config->uf_cargar_config();

	if(array_key_exists("numordpag",$arr_config))
		$ls_config_ordpag=$arr_config['numordpag'];
	else
		$ls_config_ordpag="";
	$li_size_numordpag=strlen($ls_config_ordpag);
	if (array_key_exists("operacion",$_POST))
	   {
		$ls_operacion= $_POST["operacion"];
		$ls_estcla= $_POST["hidestcla"];
		$ls_modalidad=$_POST["cmbmodalidad"];
		$ls_mov_operacion=$_POST["cmboperacion"];
		$ls_opepre=$_POST["opepre"];
		$ls_docmov=$_POST["txtdocumento"];
		$ld_fecha=$_POST["txtfecha"];
		$ls_codban=$_POST["txtcodban"];
		$ls_denban=$_POST["txtdenban"];
		$ls_cuenta_banco=$_POST["txtcuenta"];
		$ls_ctaban=$ls_cuenta_banco;
		$ls_ctaban_tesoreria=$_POST["txtctatesoreria"];
		$ls_codban_tesoreria=$_POST["txtcodbansig"];
		$ls_dencuenta_banco=$_POST["txtdenominacion"];
		$ls_provbene=$_POST["txtprovbene"];
		$ls_desproben=$_POST["txtdesproben"];
		$ls_tipo=$_POST["rb_provbene"];
		$lastspg = $_POST["lastspg"];
		$ldec_mondeb=0;
		$ldec_monhab=0;
		$ldec_diferencia=0;
		$ldec_monspg=0;
		$ldec_monspi=0;
		$ls_estmov=$_POST["estmov"];
		$ls_tipdocressig=$_POST["txttipdocres"];
		$ls_fecordpagsig=$_POST["txtfecdocres"];
		$ls_numdocressig=$_POST["txtnumdocres"];
		$ls_codbansig=$_POST["txtcodbansig"];
		$ls_tipreg=$_POST["txttipreg"];
		$ls_codfuefin=$_POST["txtftefinanciamiento"];
		$ls_codfuefin=rtrim($_POST["txtftefinanciamiento"]);
		if($ls_codfuefin=="")
		{
			$ls_codfuefin="--";
		}
		$ls_denfuefin=rtrim($_POST["txtdenftefinanciamiento"]);		
		$ls_forpagsig=$_POST["txttippag"];
		$ls_medpagsig=$_POST["txtmediopag"];
		$ls_unidad=$_POST["txtcoduniadm"];
		$ls_denuniadm=$_POST["txtdenuniadm"];
		$ls_resuac=$_POST["txtresuac"];
		$ls_estuac=$_POST["txtestuac"];
		$ls_codestpro1=$_POST["codestpro1"];
		$ls_codestpro2=$_POST["codestpro2"];
		$ls_codestpro3=$_POST["codestpro3"];
		$ls_proyecto=$_POST["denestpro1"];
		$ls_origen=$_POST["txtorigen"];
		$ldec_montomov=$_POST["txtmonto"];
		$ldec_monobjret=$_POST["txtmonobjret"];
		$ldec_montoret=$_POST["txtretenido"];
		$ldec_disponible=$_POST["txtdisponible"];
		$ldec_montomov=str_replace(".","",$ldec_montomov);
		$ldec_montomov=str_replace(",",".",$ldec_montomov);
		$ldec_monobjret=str_replace(".","",$ldec_monobjret);
		$ldec_monobjret=str_replace(",",".",$ldec_monobjret);
		$ldec_montoret=str_replace(".","",$ldec_montoret);
		$ldec_montoret=str_replace(",",".",$ldec_montoret);
		$ls_codconmov=$_POST["codconmov"];
		$ls_desmov=$_POST["txtconcepto"];
		$ls_cuenta_scg=$_POST["txtcuenta_scg"];
		$li_estint=$_POST["estint"];
		if(array_key_exists("nocontabili",$_POST))
		{
			$lb_nocontab="checked";
		}
		else
		{
			$lb_nocontab="";
		}
		$ls_estdoc=$_POST["status_doc"];
		$ls_nombreaut=$_POST["nombreaut"];
		$ls_codbanaut=$_POST["codbanaut"];
		$ls_nombanaut=$_POST["nombanaut"];
		$ls_rifaut=$_POST["rifaut"];
		$ls_ctabanaut=$_POST["ctabanaut"];
		$ls_nrocontrol=$_POST["txtnrocontrol"];
	}
	else
	   {
		 $ls_operacion= "NUEVO" ;
		 $ls_chevau="";	
		 $ls_estcla = "";		
	   }	

if (($li_estciespg==1 || $li_estciespi==1) && $ls_operacion=="NUEVO")
   {
	 $io_msg->message("Ya fué procesado el Cierre Presupuestario, sólo será posible la consulta de Movimientos Bancarios !!!");	   
   }

	$li_row=0;
	$li_rows_spg=0;
	$li_rows_ret=0;
	$li_rows_spi=0;
	if($ls_operacion=="CARGAR_DT")
	{
		uf_cargar_dt();
	}	
	function uf_cargar_dt()
	{
		global $in_classmovbco;
		global $objectScg;
		global $li_row;
		global $ldec_mondeb;
		global $ldec_monhab;
		global $objectSpg;
		global $li_rows_spg;
		global $ldec_monspg;
		global $objectSpi;
		global $li_rows_spi;
		global $ldec_monspi;
		global $objectRet;
		global $ls_estmov;
		global $li_rows_ret;
		global $ldec_diferencia;
		global $ls_docmov;
		global $ls_codban_tesoreria;
		global $ls_ctaban_tesoreria;
		global $ls_mov_operacion;
		$in_classmovbco->uf_cargar_dt($ls_docmov,$ls_codban_tesoreria,$ls_ctaban_tesoreria,$ls_mov_operacion,$ls_estmov,&$objectScg,&$li_row,&$ldec_mondeb,&$ldec_monhab,&$objectSpg,&$li_rows_spg,&$ldec_monspg,&$objectSpi,&$li_rows_spi,&$ldec_monspi);
		$ldec_diferencia=$ldec_mondeb-$ldec_monhab;
	}

	function uf_nuevo($ls_config_ordpag)
	{
		global $ls_mov_operacion;
		global $in_classmovbco;
		$ls_mov_operacion="OP";
	    global $ls_opepre;
		$ls_opepre="";
		global $ls_docmov;
		global $readonly_doc;
		$ls_docmov="";
		global $ls_codban;
		$ls_codban="";
		global $ls_denban;
		$ls_denban="";
		global $ls_estmov;
		$ls_estmov="N";
		global $ls_estdoc;
		$ls_estdoc="N";
		global $ls_ctaban;
		$ls_ctaban="";
		global $ls_cuenta_scg;
		$ls_cuenta_scg="";
		global $ls_codban_tesoreria;
		$ls_codban_tesoreria="";
		global $ls_ctaban_tesoreria;
		$ls_ctaban_tesoreria="";
		global $ls_provbene;
		$ls_provbene="----------";
		global $ls_desproben;
		$ls_desproben="Ninguno";
		global $ls_tipo;
		$ls_tipo="P";
		global $lastspg;
		$lastspg = 0;
		$array_fecha=getdate();
		$ls_dia=$array_fecha["mday"];
		$ls_mes=$array_fecha["mon"];
		$ls_ano=$array_fecha["year"];
		global $ld_fecha;
		global $fun;
		$ld_fecha=$fun->uf_cerosizquierda($ls_dia,2)."/".$fun->uf_cerosizquierda($ls_mes,2)."/".$ls_ano;
		global $ldec_monspg;
		$ldec_monspg=0;
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
		$array_fecha=getdate();
		$ls_dia=$array_fecha["mday"];
		$ls_mes=$array_fecha["mon"];
		$ls_ano=$array_fecha["year"];
		global $objectSpg;
		global $objectScg;
		global $li_row;
		global $li_temp_spg;
		$li_temp_spg=0;
		global $ls_modalidad;
		global $ldec_mondeb;
		global $ldec_monhab;
		global $ldec_diferencia;
		global $ls_tipreg;
		$ls_tipreg="CAUSADO";
		$ldec_mondeb=0;
		$ldec_monhab=0;
		$ldec_diferencia=0;
		$ls_modalidad='PP';
		global $ls_nombreaut;
		global $ls_codbanaut;
		global $ls_nombanaut;
		global $ls_rifaut;
		global $ls_ctabanaut;
		$ls_nombreaut="";
		$ls_codbanaut="";
		$ls_nombanaut="";
		$ls_rifaut="";
		$ls_ctabanaut="";
		global $ls_unidad;
		$ls_unidad="";
		global $ls_denuniadm;
		$ls_denuniadm="";
		global $ls_tipdocressig;
		global $ls_fecordpagsig;
		global $ls_numdocressig;
		global $ls_codfuefin;
		global $ls_denfuefin;
		global $ls_forpagsig;
		global $ls_medpagsig;
		global $ls_resuac;
		global $ls_estuac;
		global $ls_codestpro1;
		global $ls_codestpro2;
		global $ls_codestpro3;
		global $ls_proyecto;
		global $ls_origen;
		$ls_tipdocressig="";
		$ls_fecordpagsig="";
		$ls_numdocressig="";
		$ls_codfuefin="";
		$ls_denfuefin="";
		$ls_forpagsig="";
		$ls_medpagsig="";
		$ls_resuac="";
		$ls_estuac="";
		$ls_codestpro1="";
		$ls_codestpro2="";
		$ls_codestpro3="";
		$ls_proyecto="";
		$ls_origen="";
		$objectSpg[$li_temp_spg][1]  = "<input type=text name=txtcuenta".$li_temp_spg."       id=txtcuenta".$li_temp_spg."       value='' class=sin-borde readonly style=text-align:center size=10 maxlength=10 >";
		$objectSpg[$li_temp_spg][2]  = "<input type=text name=txtprogramatico".$li_temp_spg." id=txtprogramatico".$li_temp_spg." value='' class=sin-borde readonly style=text-align:center size=32 maxlength=29 >"; 
		$objectSpg[$li_temp_spg][3]  = "<input type=text name=txtdocumento".$li_temp_spg."    id=txtdocumento".$li_temp_spg."    value='' class=sin-borde readonly style=text-align:center size=10 maxlength=10>";
		$objectSpg[$li_temp_spg][4]  = "<input type=text name=txtdescripcion".$li_temp_spg."  id=txtdescripcion".$li_temp_spg."  value='' class=sin-borde readonly style=text-align:left>";
		$objectSpg[$li_temp_spg][5]  = "<input type=text name=txtprocede".$li_temp_spg."      id=txtprocede".$li_temp_spg."      value='' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
		$objectSpg[$li_temp_spg][6]  = "<input type=text name=txtoperacion".$li_temp_spg."    id=txtoperacion".$li_temp_spg."    value='' class=sin-borde readonly style=text-align:center size=5 maxlength=3>";
		$objectSpg[$li_temp_spg][7]  = "<input type=text name=txtmonto".$li_temp_spg."        id=txtmonto".$li_temp_spg."        value='' class=sin-borde readonly style=text-align:right>";		
		$objectSpg[$li_temp_spg][8]  = "<a href=javascript:uf_delete_Spg('".$li_temp_spg."');><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar detalle Presupuestario de Gasto' width=15 height=15 border=0></a>";	
		$li_row=0;
		$objectScg[$li_row][1] = "<input type=text name=txtcontable".$li_row." id=txtcontable".$li_row."  value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";		
		$objectScg[$li_row][2] = "<input type=text name=txtdocscg".$li_row."    value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
		$objectScg[$li_row][3] = "<input type=text name=txtdesdoc".$li_row."    value='' class=sin-borde readonly style=text-align:left size=30 maxlength=254>";
		$objectScg[$li_row][4] = "<input type=text name=txtprocdoc".$li_row."   value='' class=sin-borde readonly style=text-align:center size=8 maxlength=6>";
		$objectScg[$li_row][5] = "<input type=text name=txtdebhab".$li_row."    value='' class=sin-borde readonly style=text-align:center size=8 maxlength=1>"; 
		$objectScg[$li_row][6] = "<input type=text name=txtmontocont".$li_row." value='' class=sin-borde readonly style=text-align:right size=16 maxlength=22>";
		$objectScg[$li_row][7] = "<input type=text name=txtcodded".$li_row." value='' class=sin-borde readonly style=text-align:right size=5 maxlength=5>";
		$objectScg[$li_row][8] = "<a href=javascript:uf_delete_Scg('".$li_row."');><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar detalle contable' width=15 height=15 border=0></a>";
	}

	$title=array('1'=>'Cuenta', '2'=>'Programatico','3'=>'Documento','4'=>'Descripción','5'=>'Procede','6'=>'Operación','7'=>'Monto','8'=>'Edición');
	$title2[1]="Cuenta";       $title2[2]="Documento";      $title2[3]="Descripción";   $title2[4]="Procede";   	   $title2[5]="Debe/Haber";    $title2[6]="Monto";      $title2[7]="Deduccion";   $title2[8]="Edición";
    $grid1="grid_SPG";	
	$grid2="gridscg";	
	
	if($ls_operacion == "GUARDAR")
	{			
		$ls_tipo=$_POST["rb_provbene"];
		$ls_provbene=$_POST["txtprovbene"];
		if($ls_tipo=='P')
		{
			$ls_codpro=$ls_provbene;	
			$ls_cedbene='----------';
		}
		else
		{
			$ls_cedbene=$ls_provbene;	
			$ls_codpro='----------';
		}		
		$ls_nomproben=$_POST["txtdesproben"];

		$li_estserext=0;							
		$lb_valido=$in_classmovbco->uf_guardar_automatico($ls_codban_tesoreria,$ls_ctaban_tesoreria,$ls_docmov,$ls_mov_operacion,$ld_fecha,$ls_desmov,$ls_codconmov,$ls_codpro,$ls_cedbene,$ls_nomproben,$ldec_montomov,$ldec_monobjret,$ldec_montoret,'',$ls_estmov,0,0,'M','SCBMOV','',$ls_estdoc,$ls_tipo,$ls_tipdocressig,$ls_numdocressig,$ls_fecordpagsig,$ls_tipreg,$ls_codfuefin,$ls_origen,$ls_forpagsig,$ls_medpagsig,$ls_modalidad,$ls_unidad,$ls_codbansig,$ls_codestpro1,$ls_codban,$ls_denban,$ls_cuenta_banco,$ls_codbanaut,$ls_nombanaut,$ls_ctabanaut,$ls_rifaut,$ls_nombreaut,$ls_nrocontrol,$li_estserext);
		if($lb_valido)
		{
			$in_classmovbco->io_sql->commit();
			$ls_estdoc='C';
			$io_msg->message("Movimiento Registrado");
		}
		else
		{
			$in_classmovbco->io_sql->rollback();
			$io_msg->message($in_classmovbco->is_msg_error);
		}
		uf_cargar_dt();			
	}
	if($ls_operacion == "ELIMINAR")
	{
		$in_classmovbco->io_sql->begin_transaction();
		$lb_valido=$in_classmovbco->uf_delete_all_movimiento($ls_docmov,$ls_codban_tesoreria,$ls_ctaban_tesoreria,$ls_mov_operacion,$ls_estmov);
		if($lb_valido)
		{
			$in_classmovbco->io_sql->commit();
		}	
		else
		{
			$in_classmovbco->io_sql->rollback();			
		}
		$io_msg->message($in_classmovbco->is_msg_error);
		$ls_operacion=="NUEVO";
		uf_nuevo($ls_config_ordpag);
	}
	if($ls_operacion=="DELETESPG")
	{
		$li_row_delete   = $_POST["delete_spg"];
		$ls_cuenta_spg   = $_POST["txtcuenta".$li_row_delete];
		$ls_programatica = $_POST["txtprogramatico".$li_row_delete];
	    $ls_estcla       = trim($_POST["hidestcla".$li_row_delete]);
		$ls_codestpro1   = substr($ls_programatica,0,$li_loncodestpro1);
		$ls_codestpro2   = substr($ls_programatica,$li_loncodestpro1+1,$li_loncodestpro2);
		$ls_codestpro3   = substr($ls_programatica,$li_loncodestpro1+$li_loncodestpro2+2,$li_loncodestpro3);
		$ls_codestpro1   = trim(str_pad($ls_codestpro1,25,0,0));
		$ls_codestpro2   = trim(str_pad($ls_codestpro2,25,0,0));
		$ls_codestpro3   = trim(str_pad($ls_codestpro3,25,0,0));
		if ($_SESSION["la_empresa"]["estmodest"]==2)
		   {
		     $ls_codestpro4 = substr($ls_programatica,$li_loncodestpro1+$li_loncodestpro2+$li_loncodestpro3+3,$li_loncodestpro4);
			 $ls_codestpro5 = substr($ls_programatica,$li_loncodestpro1+$li_loncodestpro2+$li_loncodestpro3+$li_loncodestpro4+4,$li_loncodestpro5);
			 $ls_codestpro4 = trim(str_pad($ls_codestpro4,25,0,0));
			 $ls_codestpro5 = trim(str_pad($ls_codestpro5,25,0,0));
		   }
		else
		   {
		     $ls_codestpro4 = $ls_codestpro5 = str_pad("",25,0,0);
		   }
		$ls_programatica = $ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
		$ls_numdoc=$_POST["txtdocumento".$li_row_delete];
		$ls_operacion=$_POST["txtoperacion".$li_row_delete];
		$ldec_montospg=$_POST["txtmonto".$li_row_delete];
		$ldec_montospg=str_replace(".","",$ldec_montospg);
		$ldec_montospg=str_replace(",",".",$ldec_montospg);
		$in_classmovbco->io_sql->begin_transaction();
		$lb_valido=$in_classmovbco->uf_delete_dt_spg($ls_docmov,$ls_codban_tesoreria,$ls_ctaban_tesoreria,$ls_mov_operacion,$ls_estmov,$ls_numdoc,$ls_cuenta_spg,$ls_operacion,$ls_programatica,$ldec_montospg,$ls_estcla);
		$io_msg->message($in_classmovbco->is_msg_error);
		if($lb_valido)
		{
			$in_classmovbco->io_sql->commit();
		}
		else
		{
			$in_classmovbco->io_sql->rollback();
		}
		uf_cargar_dt();
	}
	if($ls_operacion=="DELETESCG")
	{
		$li_row_delete=$_POST["delete_scg"];
		$ls_codded=$_POST["txtcodded".$li_row_delete];
		$ls_cuentascg=$_POST["txtcontable".$li_row_delete];
		$ls_debhab=$_POST["txtdebhab".$li_row_delete];
		$ls_numdoc=$_POST["txtdocscg".$li_row_delete];
		$ldec_montoscg=$_POST["txtmontocont".$li_row_delete];
		$ldec_montoscg=str_replace(".","",$ldec_montoscg);
		$ldec_montoscg=str_replace(",",".",$ldec_montoscg);
		$arr_movbco["codban"]=$ls_codban_tesoreria;
		$arr_movbco["ctaban"]=$ls_ctaban_tesoreria;
		$arr_movbco["mov_document"]=$ls_docmov;
		$ld_fecdb=$fun->uf_convertirdatetobd($ld_fecha);
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
		$lb_valido=$in_classmovbco->uf_delete_dt_scg($ls_docmov,$ls_codban_tesoreria,$ls_ctaban_tesoreria,$ls_mov_operacion,$ls_estmov,$ls_numdoc,$ls_cuentascg,$ls_debhab,$ls_codded,$ldec_montoscg,'SCG');
		if($ls_codded!="00000")
		{
			$ls_operacioncon="H";			
			$ldec_monto=str_replace(".","",$ldec_montoscg);
			$ldec_monto=str_replace(",",".",$ldec_monto);
			$lb_valido=$in_classmovbco->uf_update_montodelete($arr_movbco,$ls_cuenta_scg,'SCBOPD',$ls_desmov,$ls_docmov,$ls_operacioncon,$ldec_montoscg,$ldec_monobjret,'00000');
		}
		$io_msg->message($in_classmovbco->is_msg_error);
		if($lb_valido)
		{
			$in_classmovbco->io_sql->commit();
		}
		else
		{
			$in_classmovbco->io_sql->rollback();
		}
		uf_cargar_dt();
	}
	if($ls_operacion == "NUEVO")
	{
		$ls_operacion= "" ;
		uf_nuevo($ls_config_ordpag);
		$ldec_disponible="";
		$ls_tipdocressig="";
		$ls_fecordpagsig="";
		$ls_numdocressig="";
		$ls_codbansig="";
		$ls_forpagsig="";
		$ls_codfuefin="";
		$ls_denfuefin="";
		$ls_forpagsig="";
		$ls_medpagsig="";
		$ls_unidad="";
		$ls_denuniadm="";
		$ls_resuac="";
		$ls_estuac="";
		$ls_codestpro1="";
		$ls_codestpro2="";
		$ls_codestpro3="";
		$ls_proyecto="";
		$ls_codban="";
		$ls_estdoc="N";
		$ls_ctaban="";	
		$ls_nrocontrol="";
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
	if($ls_modalidad=='PP')
	{
		$rb_pp="selcted";
		$rb_oa="";
		$rb_cm="";			
	}
	if($ls_modalidad=='OA')
	{
		$rb_pp="";
		$rb_oa="selected";
		$rb_cm="";			
	}
	if($ls_modalidad=='CM')
	{
		$rb_pp="";
		$rb_oa="";
		$rb_cm="selected";			
	}
	switch($ls_forpagsig){ 
		case 'A':
			$ls_select_a="selected";
			$ls_select_d="";
			break;
		case 'D':
			$ls_select_a="";
			$ls_select_d="selected";
			break;
	}	

?>
  <form name="form1" method="post" action="" id="sigesp_scb_p_opd_causapaga.php">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_banco->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_banco);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <br>
  <table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-ventana">
      <td height="21" colspan="6"> <input name="hidmesabi" type="hidden" id="hidmesabi" value="true">
        Orden de Pago Directa con Compromiso Previo 
      <input name="hidestciespi" type="hidden" id="hidestciespi" value="<?php echo $li_estciespi; ?>">
      <input name="hidestciespg" type="hidden" id="hidestciespg" value="<?php echo $li_estciespg; ?>"></td>
    </tr>
    <tr>
      <td colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td width="83" height="22"><div align="right">Modalidad  </div></td>
      <td height="22" colspan="5"><select name="cmbmodalidad" onChange="javascript:uf_valida_cambio();">
        <option value="PP" <?php print $rb_pp;?>>Pago a Proveedores</option>
        <option value="OA" <?php print $rb_oa;?>>Pago Organismos Adscritos</option>
        <option value="CM" <?php print $rb_cm;?>>Fondo en Avance/Fondo en Anticipo</option>
      </select>
      <input name="modordpag" type="hidden" id="modordpag" value="<?php print $ls_modalidad;?>"></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Documento</td>
      <td height="22" style="text-align:left"><input name="txtdocumento" type="text" id="txtdocumento" style="text-align:center" value="<?php print $ls_docmov;?>" size="24" maxlength="<?php print $li_size_numordpag;?>" onBlur="javascript:rellenar_cad(this.value,<?php print $li_size_numordpag;?>,'doc')">        
	    <input name="estmov" type="hidden" id="estmov" value="<?php print $ls_estmov;?>">
        <input name="txtchequera" type="hidden" id="txtchequera" value="<?php print $ls_numchequera;?>">
      </td>
      <td height="22">&nbsp;</td>
      <td width="199" height="22">&nbsp;</td>
      <td width="74" height="22" style="text-align:right">Fecha</td>
      <td width="146" height="22" style="text-align:left"><input name="txtfecha" type="text" id="txtfecha"  style="text-align:center" value="<?php print $ld_fecha;?>" size="24" maxlength="10" datepicker="true" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);"></td>
    </tr>
	  <script language="javascript">uf_validar_estatus_mes();</script>
    <tr>
      <td height="22"><div align="right">Unidad Administradora</div></td>
      <td height="22" colspan="5"><div align="left">
        <input name="txtcoduniadm" type="text" id="txtcoduniadm" value="<?php print $ls_unidad;?>" size="8" readonly style="text-align:center">
        <a href="javascript:catunidadadm()"><img src="../shared/imagebank/tools15/buscar.gif" alt="Unidades Administrativas" name="bot_provbene" width="15" height="15" border="0" id="bot_provbene"></a>
        <input name="txtdenuniadm" type="text" class="sin-borde" id="txtdenuniadm" value="<?php print $ls_denuniadm;?>" size="95" readonly>
        <input name="txtestuac" type="hidden" id="txtestuac" value="<?php print $ls_estuac;?>" size="5">
        <input name="txtresuac" type="hidden" id="txtresuac" value="<?php print $ls_respuac;?>" size="5">
      </div></td>
    </tr>
    <tr>
      <td height="20"><div align="right">Proyecto</div></td>
      <td height="20" colspan="5"><input name="codestpro1" type="text" id="codestpro1" style="text-align:center" value="<?php print $ls_codestpro1;?>" size="22">
      <input name="codestpro2" type="hidden" id="codestpro2" style="text-align:center" value="<?php print $ls_codestpro2;?>" size="8">
      <input name="codestpro3" type="hidden" id="codestpro3" style="text-align:center" value="<?php print $ls_codestpro3;?>" size="5">
      <a href="javascript:catalogo_estpro();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo Estructura Presupuestaria" width="15" height="15" border="0">
      <input name="hidestcla" type="hidden" id="hidestcla" value="<?php echo $ls_estcla ?>">
      </a></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Nombre Proyecto</td>
      <td height="22" colspan="5" style="text-align:left">
	    <input name="denestpro1" type="text" id="denestpro1" value="<?php print $ls_proyecto;?>" size="113">
        <input name="denestpro2" type="hidden" id="denestpro2">
        <input name="denestpro3" type="hidden" id="denestpro3">
</div></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Banco Origen</div></td>
      <td height="22" colspan="2"><input name="txtcodbansig" type="text" id="txtcodbansig" style="text-align:center" value="<?php print $ls_codban_tesoreria?>" size="5" maxlength="3" readonly>
        <a href="javascript:catalogo_bancocuenta();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo Estructura Presupuestaria" width="15" height="15" border="0"></a>
        <input name="txtnombansig" type="text" class="sin-borde" id="txtnombansig" size="30" readonly>        </td>
      <td height="22" colspan="3">Cuenta Origen
        <input name="txtctatesoreria" type="text" id="txtctatesoreria" value="<?php print $ls_ctaban_tesoreria;?>" size="26" maxlength="25" readonly>
        <input name="txtdenctatesoreria" type="text" class="sin-borde" id="txtdenctatesoreria" size="30" readonly></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Tipo Destino</div></td>
      <td height="22">
        <div align="center">
          <table width="175" border="0" align="left" cellpadding="0" cellspacing="0" class="formato-blanco">
            <tr>
              <td width="173">
                
                <div align="left">
                  <input name="rb_provbene" type="radio" class="sin-borde" id="radio" onClick="javascript:uf_verificar_provbene(this.checked,document.form1.tipo.value);" value="P" checked <?php print $rb_p;?>>
                Proveedor                
                <input type="radio" name="rb_provbene" id="radio" value="B" class="sin-borde" onClick="javascript:uf_verificar_provbene(this.checked,document.form1.tipo.value);" <?php print $rb_b;?>>
                Beneficiario                
                <input name="tipo" type="hidden" id="tipo">
              </div></td>
            </tr>
          </table>
      </div></td>
      <td height="22">&nbsp;</td>
      <td height="22" colspan="3"><input name="txtprovbene" type="text" id="txtprovbene" style="text-align:center" value="<?php print $ls_provbene?>" size="24" readonly>
        <a href="javascript:catprovbene()"><img src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo Proveedores/Beneficiarios" name="bot_provbene" width="15" height="15" border="0" id="bot_provbene"></a>
        <input name="txtdesproben" type="text" id="txtdesproben" size="35" maxlength="250" class="sin-borde" value="<?php print $ls_desproben;?>"  readonly>
        <input name="txttitprovbene" type="hidden" class="sin-borde" id="txttitprovbene" style="text-align:right" size="15" readonly></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Banco</div></td>
      <td height="22" colspan="2"><div align="left">
             <input name="txtcodban" type="text" id="txtcodban"  style="text-align:center" value="<?php print $ls_codban;?>" size="10" readonly>
             <input name="txtdenban" type="text" id="txtdenban" value="<?php print $ls_denban?>" size="32" class="sin-borde" readonly>
          </div></td>
      <td height="22">&nbsp;</td>
      <td height="22">&nbsp;</td>
      <td height="22"><div align="left">
        <input name="txtdisponible" type="hidden" id="txtdisponible">
      </div></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Cuenta</div></td>
      <td colspan="3"><div align="left">
          <input name="txtcuenta" type="text" id="txtcuenta" style="text-align:center" value="<?php print $ls_ctaban; ?>" size="30" maxlength="25" readonly>
          <a href="javascript:catalogo_cuentabanco();"></a>
          <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion" style="text-align:left" size="35" maxlength="254" readonly>
          <input name="txttipocuenta" type="hidden" id="txttipocuenta">
          <input name="txtdentipocuenta" type="hidden" id="txtdentipocuenta">
      </div></td>
      <td><div align="right">Cta. Contable</div></td>
      <td><div align="left">
        <input name="txtcuenta_scg" type="text" id="txtcuenta_scg2" style="text-align:center" value="<?php print $ls_cuenta_scg;?>" size="24" readonly>
      </div></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Operaci&oacute;n</div></td>
      <td><div align="left">
        <input name="cmboperacion" type="text" id="cmboperacion" value="OP" readonly style="text-align:center">
</div></td>
      <td><div align="right">Afectación</div></td>
      <td><input name="opepre" type="text" id="opepre" value="CP" readonly size="8" maxlength="3" style="text-align:center" >
        <strong>      Causa y Paga  </strong></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Tipo Concepto</td>
      <td colspan="3" style="text-align:left"><?php $obj_con->uf_cargar_conceptos($ls_mov_operacion,$ls_codconmov);	?>
        <input name="codconmov" type="hidden" id="codconmov" value="<?php print $ls_codconmov;?>">
      </td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Concepto</td>
      <td colspan="5" style="text-align:left"><input name="txtconcepto" type="text" id="txtconcepto" value="<?php print $ls_desmov;?>" size="120" onKeyPress="return keyRestrict(event,'0123456789'+'abcdefghijklmnopqrstuvwxyzñ .,*/-()$%&!ºªáéíóú[]{}<>')"></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Monto</td>
      <td width="193" style="text-align:left"><input name="txtmonto" type="text" id="txtmonto" style="text-align:right" onBlur="javascript:uf_format(this);uf_montoobjret(this);" value="<?php print number_format($ldec_montomov,2,",",".");?>" size="24" readonly></td>
      <td width="83" style="text-align:right">M.O.R.</td>
      <td><input name="txtmonobjret" type="text" id="txtmonobjret" style="text-align:right" onBlur="javascript:validar_monto();javascript:uf_format(this);" value="<?php print  number_format($ldec_monobjret,2,",",".");?>" size="24"></td>
      <td style="text-align:right">Monto Retenido</td>
      <td style="text-align:left"><input name="txtretenido" type="text" id="txtretenido" style="text-align:right" value="<?php print number_format($ldec_montoret,2,",",".");?>" size="24" readonly></td>
    </tr>
    <tr>
      <td height="22"><div align="right">
          		<?php
				if($ls_mov_operacion=="NC")
				{
				?>
          			<input name="txtint" id="txtint" type="text" value="Interes" class="sin-borde" size="10" style="text-align:right" readonly>
         		<?php	
				}	
				?>
      </div></td>
      <td colspan="3"><div align="left">
	            <?php
				if($ls_mov_operacion=="NC")
				{
				?>
          			<input name="chkinteres" type="checkbox" class="sin-borde" id="chkinteres" style="width:15px; height:15px" onClick="uf_selec_interes(this);" value="1" <?php print $lb_checked;?>>
         		<?php	
				}	
				?>
                <input name="estint" type="hidden" id="estint" value="<?php print $li_estint;?>">
</div></td>
      <td style="text-align:right"><input name="nocontabili" type="checkbox" class="sin-borde" id="nocontabili" value="checkbox" <?php print $lb_nocontab;?>></td>
      <td>No Contabilizable </td>
    </tr>
    <tr>
      <td height="13" colspan="6">&nbsp;</td>
    </tr>
    <tr class="titulo-celdanew">
      <td height="15" colspan="6">Datos del Documento de Respaldo </td>
    </tr>
    <tr>
      <td height="15" colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td height="22"><div align="right">Tipo Documento </div></td>
      <td height="15"><div align="left">
        <input name="txttipdocres" type="text" id="txttipdocres" value="<?php print $ls_tipdocressig;?>" size="15" maxlength="2">
      </div></td>
      <td height="15" style="text-align:right">Nro. Documento</td>
      <td height="15" style="text-align:left"><input name="txtnumdocres" type="text" id="txtnumdocres" value="<?php print $ls_numdocressig;?>" size="17" maxlength="15"></td>
      <td height="15" style="text-align:right">Fecha de Pago</td>
      <td height="15" style="text-align:left"><input name="txtfecdocres" type="text" id="txtfecdocres" style="text-align:center" value="<?php print $ls_fecordpagsig;?>"  size="24" maxlength="10"  datepicker="true" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);"></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Tipo de Registro </div></td>
      <td height="13"><div align="left">
        <input name="txttipreg" type="text" id="txttipreg" style="text-align:center" value="<?php print $ls_tipreg;?>" size="15" maxlength="15" readonly>
      </div></td>
      <td height="13"><div align="right">Fte. Financiamiento </div></td>
      <td height="13"><input name="txtftefinanciamiento" type="text" id="txtftefinanciamiento" style="text-align:center" value="<?php print $ls_codfuefin;?>" size="3" maxlength="2" readonly>
        <a href="javascript: uf_cat_fte_financia();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo Fuente de Financiamiento" width="15" height="15" border="0"></a>
      <input name="txtdenftefinanciamiento" type="text" class="sin-borde" id="txtdenftefinanciamiento" value="<?php print $ls_denfuefin; ?>" readonly></td>
      <td height="13" style="text-align:left">Origen</td>
      <td height="13"><input name="txtorigen" type="text" id="txtorigen" size="24"></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Forma de Pago</td>
      <td height="13"><select name="txttippag" id="txttippag" style="width:120px">
        <option value="D">Directa</option>
        <option value="A">Avance</option>
      </select>      </td>
      <td height="13" style="text-align:right">Medio de Pago</td>
      <td height="13"><select name="txtmediopag" id="txtmediopag">
          <option value="1">Abono en Cuenta</option>
          <option value="2">Transferencia</option>
          <option value="3">Carta de Cr&eacute;dito</option>
          <option value="4">T&iacute;tulo o Bono de la Deuda P&uacute;blica</option>
          <option value="5">Efect&iacute;vo</option>
        </select>      </td>
      <td height="13" style="text-align:right">Nro.Control</td>
      <td height="13"><input name="txtnrocontrol" type="text" id="txtnrocontrol" value="<?php print $ls_nrocontrol;?>" size="24" maxlength="15"></td>
    </tr>
    <tr>
      <td height="13"><div align="right"> </div> <a href="#01"> </a></td>
      <td height="13" colspan="3">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
    </tr>
    <tr class="titulo-celdanew">
      <td height="15" colspan="6">Autorizado al Cobro </td>
    </tr>
    <tr>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Nombre</td>
      <td height="22"><input name="nombreaut" type="text" id="nombreaut" value="<?php print $ls_nombreaut;?>" size="30" maxlength="60"></td>
      <td height="22" style="text-align:right">R.I.F.</td>
      <td height="22" style="text-align:left"><input name="rifaut" type="text" id="rifaut" value="<?php print $ls_rifaut;?>" size="15" maxlength="10"></td>
      <td height="22">&nbsp;</td>
      <td height="22">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Banco</td>
      <td height="22" style="text-align:left"><input name="codbanaut" type="text" id="codbanaut" value="<?php print $ls_codbanaut;?>" size="4" maxlength="3" readonly>        <a href="javascript:catbanaut();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo de Bancos" name="bot_provbene" width="15" height="15" border="0" id="bot_provbene"></a>  
        <input name="nombanaut" type="text" class="sin-borde" id="nombanaut" value="<?php print $ls_nombanaut;?>" size="21">
      </td>
      <td height="22"><div align="right">Cuenta Nro. </div></td>
      <td height="22"><input name="ctabanaut" type="text" id="ctabanaut" value="<?php print $ls_ctabanaut;?>" size="30" maxlength="25"></td>
      <td height="22">&nbsp;</td>
      <td height="22">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="6">&nbsp;&nbsp;<a href="javascript: uf_agregar_dtpre();"><img src="../shared/imagebank/tools/nuevo.gif" width="15" height="15" border="0">Agregar detalle Presupuesto</a> <a href="javascript: uf_agregar_dtcargos();"><img src="../shared/imagebank/tools/nuevo.gif" alt="Detalle Deducciones" width="15" height="15" border="0">Agregar detalle Cargos </a></td>
    </tr>
    <tr>
      <td height="22" colspan="6"><div align="center"><?php $io_grid->makegrid($li_rows_spg,$title,$objectSpg,770,'Detalles Presupuestarios',$grid1);?>
              <input name="totpre"  type="hidden" id="totpre"  value="<?php print $li_rows_spg?>">
              <input name="lastspg" type="hidden" id="lastspg" value="<?php print $lastspg;?>">
              <input name="delete_spg" type="hidden" id="delete_spg">
      </div></td>
    </tr>
    <tr>
      <td height="22" colspan="6"><table width="223" border="0" align="right" cellpadding="0" cellspacing="0">
          <tr>
            <td width="96" height="20"><div align="right">Total Presupuesto </div></td>
            <td width="127"><input name="totspg" type="text" id="totspg" value="<?php print number_format($ldec_monspg,2,',','.');?>" style="text-align:right" readonly></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td height="22" colspan="6">&nbsp;&nbsp;<a href="javascript: uf_agregar_dtscg(false);"><img src="../shared/imagebank/tools/nuevo.gif" alt="Detalle Contable" width="15" height="15" border="0">Agregar detalle Contable </a><a href="javascript: uf_agregar_dtscg(true);"><img src="../shared/imagebank/tools/nuevo.gif" alt="Detalle Deducciones" width="15" height="15" border="0">Agregar detalle Retenciones </a> </td>
    </tr>
    <tr>
      <td height="22" colspan="6"><div align="center"> <a name="01" id="01"></a>
              <?php $io_grid->makegrid($li_row,$title2,$objectScg,770,'Detalles Contable',$grid2);?>
              <input name="totcon"  type="hidden" id="totcon"  size=5 value="<?php print $li_row?>">
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
  if (uf_evaluate_cierre())
     {
	   f.operacion.value ="NUEVO";
	   f.action="sigesp_scb_p_opd_causapaga.php";
	   f.submit();
	 }
}

function ue_guardar()
{
  uf_validar_estatus_mes();
  lb_mesabi = f.hidmesabi.value;
  if (lb_mesabi=='true')
     {
	  if (uf_evaluate_cierre())
		 {
		   ls_status = f.estmov.value;
		   if ((ls_status!='C')&&(ls_status!='O')&&(ls_status!='A'))
			  {
				ls_operacion=f.cmboperacion.value;		
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
				if(f.nocontabili.checked==true)
				{
					ls_estmov="L";
				}
				else
				{
					ls_estmov="N";
				}
				li_cobrapaga=0;			
				if(ls_operacion=="CH")
				{
					ls_chevau=f.txtchevau.value;
					if(ls_chevau=="")
					{
						lb_valido=false;
						f.txtchevau.focus();
					}
					else
					{
						lb_valido=true;
					}
				}
				else
				{
					ls_chevau=" ";
					lb_valido=true;
				}
				if(f.rb_provbene[0].checked)
				{
					ls_tipo="P";
				}
				if(f.rb_provbene[1].checked)
				{
					ls_tipo="B";
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
					f.operacion.value ="GUARDAR";
					f.action="sigesp_scb_p_opd_causapaga.php";
					f.submit();
				}
				else
				{
					alert("No ha completado los datos ");
				}		
			  }
		   else
			  {
				alert("No puede eliminar el movimiento, ya fue Contabilizado o Anulado");
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
	  if (uf_evaluate_cierre('SPG'))
		 {
		   ls_status = f.estmov.value;
		   if ((ls_status!='C')&&(ls_status!='O')&&(ls_status!='A'))
			  {
				if (confirm("Esta seguro de eliminar el documento?,\n esta operación no se puede deshacer."))
				   {
					 f.operacion.value ="ELIMINAR";
					 f.action="sigesp_scb_p_opd_causapaga.php";
					 f.submit();	
				   }
			  }
		   else
			  {
				alert("No puede eliminar el movimiento, ya fue Contabilizado o Anulado");
			  }	 
		 }
     }
  else
     {
	   alert("Operación No puede ser procesada, El Més está Cerrado !!!");
	 }
}

function ue_buscar()
{
  window.open("sigesp_cat_mov_orden_pago.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=583,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_imprimir()
{
  ls_numdoc=f.txtdocumento.value;
  ls_codope='OP';
  ls_codban=f.txtcodbansig.value;
  ls_ctaban=f.txtctatesoreria.value;	
  ls_pagina="reportes/sigesp_scb_rpp_ordenpago.php?codban="+ls_codban+"&ctaban="+ls_ctaban+"&numdoc="+ls_numdoc+"&codope="+ls_codope;
  window.open(ls_pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
}

function ue_generar_cmp()
{
	ls_numdoc=f.txtdocumento.value;
	ls_numdoc=f.txtdocumento.value;
	ls_codope='OP';
	ls_codban=f.txtcodbansig.value;
	ls_ctaban=f.txtctatesoreria.value;	
	ls_nrocontrol=f.txtnrocontrol.value;
	ls_numdocres=f.txtnumdocres.value;
	ls_fecdocres=f.txtfecdocres.value;
	ls_desope=f.txtconcepto.value;
	ls_nrocontrol=f.txtnrocontrol.value;
	ldec_monto=uf_convertir_monto(f.txtmonto.value);	
	ld_fecha=f.txtfecha.value;
	if(f.rb_provbene[0].checked)
	{
		ls_tipo="P";
		ls_codpro=f.txtprovbene.value;
		ls_cedbene="";
	}
	if(f.rb_provbene[1].checked)
	{
		ls_tipo="B";
		ls_codpro="";
		ls_cedbene=f.txtprovbene.value;
	}
	ls_provbene=f.txtprovbene.value;
	if (ls_codban!='' && ls_ctaban!='' && ls_numdoc!='' && ls_codope!='')
	   {
		 ls_pagina="reportes/sigesp_scb_rpp_cmpretiva_op?codban="+ls_codban+"&ctaban="+ls_ctaban+"&numdoc="+ls_numdoc+"&codope="+ls_codope+"&numdocres="+ls_numdocres+"&fecdocres="+ls_fecdocres+"&nrocontrol="+ls_nrocontrol+"&desope="+ls_desope+"&monto="+ldec_monto+"&tipodestino="+ls_tipo+"&codpro="+ls_codpro+"&cedbene="+ls_cedbene+"&fecmov="+ld_fecha;
		 window.open(ls_pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
	   }
}

function ue_generar_cmp_mun()
{
	ls_numdoc=f.txtdocumento.value;
	ls_numdoc=f.txtdocumento.value;
	ls_codope='OP';
	ls_codban=f.txtcodbansig.value;
	ls_ctaban=f.txtctatesoreria.value;	
	ls_nrocontrol=f.txtnrocontrol.value;
	ls_numdocres=f.txtnumdocres.value;
	ls_fecdocres=f.txtfecdocres.value;
	ls_desope=f.txtconcepto.value;
	ls_nrocontrol=f.txtnrocontrol.value;
	ldec_monto=uf_convertir_monto(f.txtmonto.value);	
	ld_fecha=f.txtfecha.value;
	if(f.rb_provbene[0].checked)
	{
		ls_tipo="P";
		ls_codpro=f.txtprovbene.value;
		ls_cedbene="";
	}
	if(f.rb_provbene[1].checked)
	{
		ls_tipo="B";
		ls_codpro="";
		ls_cedbene=f.txtprovbene.value;
	}
	ls_provbene=f.txtprovbene.value;
	if (ls_codban!='' && ls_ctaban!='' && ls_numdoc!='' && ls_codope!='')
	   {
	     ls_pagina="reportes/sigesp_scb_rpp_comp_ret_mun_op.php?codban="+ls_codban+"&ctaban="+ls_ctaban+"&numdoc="+ls_numdoc+"&codope="+ls_codope+"&numdocres="+ls_numdocres+"&fecdocres="+ls_fecdocres+"&nrocontrol="+ls_nrocontrol+"&desope="+ls_desope+"&monto="+ldec_monto+"&tipodestino="+ls_tipo+"&codpro="+ls_codpro+"&cedbene="+ls_cedbene+"&fecmov="+ld_fecha;
	     window.open(ls_pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
       }
}

function ue_generar_cmp_islr()
{
   ls_numdoc=f.txtdocumento.value;
   ls_numdoc=f.txtdocumento.value;
   ls_codope='OP';
   ls_codban=f.txtcodbansig.value;
   ls_ctaban=f.txtctatesoreria.value;	
   ls_nrocontrol=f.txtnrocontrol.value;
   ls_numdocres=f.txtnumdocres.value;
   ls_fecdocres=f.txtfecdocres.value;
   ls_desope=f.txtconcepto.value;
   ls_nrocontrol=f.txtnrocontrol.value;
   ldec_monto=uf_convertir_monto(f.txtmonto.value);	
   ld_fecha=f.txtfecha.value;
   if (f.rb_provbene[0].checked)
	  {
		ls_tipo="P";
		ls_codpro=f.txtprovbene.value;
		ls_cedbene="";
	  }
   if (f.rb_provbene[1].checked)
	  {
		ls_tipo="B";
		ls_codpro="";
		ls_cedbene=f.txtprovbene.value;
	  }
   ls_provbene=f.txtprovbene.value;
   if (ls_codban!='' && ls_ctaban!='' && ls_numdoc!='' && ls_codope!='')
	  {
		ls_pagina="reportes/sigesp_scb_rpp_islr.php?codban="+ls_codban+"&ctaban="+ls_ctaban+"&numdoc="+ls_numdoc+"&codope="+ls_codope+"&numdocres="+ls_numdocres+"&fecdocres="+ls_fecdocres+"&nrocontrol="+ls_nrocontrol+"&desope="+ls_desope+"&monto="+ldec_monto+"&tipodestino="+ls_tipo+"&codpro="+ls_codpro+"&cedbene="+ls_cedbene+"&fecmov="+ld_fecha;
		window.open(ls_pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
	  }
}

function uf_cat_fte_financia()
{
  uf_validar_estatus_mes();
  if (uf_evaluate_cierre('SPG'))
     {
	   ls_status=f.estmov.value;
	   if ((ls_status!='C')&&(ls_status!='O')&&(ls_status!='A'))
	      {
	        pagina="sigesp_sep_cat_fuente.php";
	        window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
  	      }
	   else
	      {
		    alert("No puede eliminar el movimiento, ya fue Contabilizado o Anulado");
	      }
     }
}
   
function rellenar_cad(cadena,longitud,campo)
{
  if (cadena!="")
	 {
	   var mystring=new String(cadena);
	   cadena_ceros="";
	   lencad=mystring.length;
		
	   total=longitud-lencad;
	   for (i=1;i<=total;i++)
		   {
		     cadena_ceros=cadena_ceros+"0";
		   }
	   cadena=cadena_ceros+cadena;
	   if (campo=="doc")
		  {
		    document.form1.txtdocumento.value=cadena;
		  }
     }
}
	
function catalogo_cuentabanco()
{
  uf_validar_estatus_mes();
  if (uf_evaluate_cierre('SPG'))
     {
	   ls_codban=f.txtcodban.value;
	   ls_denban=f.txtdenban.value;
	   ls_status=f.estmov.value;
	   if ((ls_status!='C')&&(ls_status!='O')&&(ls_status!='A'))
		  {
	  	    if ((ls_codban!=""))
		       {
			     pagina="sigesp_cat_ctabanco.php?codigo="+ls_codban+"&denban="+ls_denban;
			     window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
		       }
		    else
		       {
				 alert("Seleccione el Banco");   
		       }
	      }
	   else
	      {
	 	    alert("No puede eliminar el movimiento, ya fue Contabilizado o Anulado");
	      }	  
	 }
}
	 
function catalogo_cuentascg()
{
  if (uf_evaluate_cierre('SPG'))
     {
	   ls_status=f.estmov.value;
	   if ((ls_status!='C')&&(ls_status!='O')&&(ls_status!='A'))
		  {
		    pagina="sigesp_cat_filt_scg.php?filtro="+'11102'+"&opener=sigesp_scb_d_colocacion.php";
		    window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
	 	  }
	   else
	      {
	 	    alert("No puede eliminar el movimiento, ya fue Contabilizado o Anulado");
	      }
	 }
}
	 	 
function catalogo_bancocuenta()
{
  uf_validar_estatus_mes();
  if (uf_evaluate_cierre('SPG'))
     {
	   ls_status=f.estmov.value;
	   if ((ls_status!='C')&&(ls_status!='O')&&(ls_status!='A'))
	      {
	   	    pagina="sigesp_cat_bancos_tesoreria.php";
	   	    window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=620,height=400,resizable=yes,location=no");
	      }
	   else
	      {
	 	    alert("No puede eliminar el movimiento, ya fue Contabilizado o Anulado");
	      }	
	 }
}
   
function uf_verificar_operacion()
{
  uf_validar_estatus_mes();
  ls_status=f.estmov.value;
  if ((ls_status!='C')&&(ls_status!='O')&&(ls_status!='A'))
     {
	   f.operacion.value="CAMBIO_OPERA";
	   f.opepre.value=f.cmboperacion.value;	
	   f.submit();    
     }
  else
     {
       alert("No puede eliminar el movimiento, ya fue Contabilizado o Anulado");
     }	   	
}
   
function catprovbene()
{
  uf_validar_estatus_mes();
  if (uf_evaluate_cierre('SPG'))
     {
	   ls_status=f.estmov.value;
	   if ((ls_status!='C')&&(ls_status!='O')&&(ls_status!='A'))
 	      {
		    if (f.rb_provbene[0].checked==true)
		       {
			     f.txtprovbene.disabled=false;	
			     window.open("sigesp_cat_prov_op.php","Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
			   }
			else if(f.rb_provbene[1].checked==true)
		       {	
			   	 f.txtprovbene.disabled=false;	
			     window.open("sigesp_cat_bene_op.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
			   }
	      }
	   else
	      {
		    alert("No puede eliminar el movimiento, ya fue Contabilizado o Anulado");
	      }	
     }
}   

function uf_verificar_provbene(lb_checked,obj)
{
	ls_status=f.estmov.value;
	if((ls_status!='C')&&(ls_status!='O')&&(ls_status!='A'))
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
	}
	else
	{
		 alert("No puede eliminar el movimiento, ya fue Contabilizado o Anulado");
	}		
}

function  uf_agregar_dtcargos()
{
  lb_mesabi = f.hidmesabi.value;
  if (lb_mesabi=='true')
     {
	  if (uf_evaluate_cierre('SPG'))
		 {
			ls_status=f.estmov.value;
			if((ls_status!='C')&&(ls_status!='O')&&(ls_status!='A'))
			{
				ls_estdoc=f.status_doc.value;
				ls_modalidad=f.cmbmodalidad.value;
				ls_operacion=f.cmboperacion.value;		
				ls_cuenta_scg=f.txtcuenta_scg.value;
				ls_descripcion=f.txtconcepto.value;
				ls_procede="SCBOPD";
				ls_documento=f.txtdocumento.value;
				ldec_monto=f.txtmonto.value;
				ls_cuenta_scg=f.txtcuenta_scg.value;
				ld_fecha=f.txtfecha.value;
				ls_codban=f.txtcodbansig.value;
				ls_ctaban=f.txtctatesoreria.value;
				ls_cuenta_scg=f.txtcuenta_scg.value;
				ldec_objret=f.txtmonobjret.value;
				ldec_monret=f.txtretenido.value;
				ls_codconmov=f.ddlb_conceptos.value;
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
				ls_afectacion='CP';
				ls_chevau=" ";
				lb_valido=true;
				ls_estmov="N";
				li_estint=0;
				li_cobrapaga=0;
				if(f.rb_provbene[0].checked)
				{
					ls_tipo="P";
				}
				if(f.rb_provbene[1].checked)
				{
					ls_tipo="B";
				}
				ls_provbene=f.txtprovbene.value;
				ls_nomproben=f.txtdesproben.value;
				ls_tipdocres=f.txttipdocres.value;
				ls_numdocres=f.txtnumdocres.value;
				ls_fecdocres=f.txtfecdocres.value;
				if(ls_fecdocres=="")
				{		
					lb_valido=false;
					alert("Fecha del documento de respaldo no puede ser nula");
				}
				ls_tipreg=f.txttipreg.value;
				ls_fte_financiamiento=f.txtftefinanciamiento.value;
				ls_origen=f.txtorigen.value;
				ls_tippag=f.txttippag.value;
				ls_mediopago=f.txtmediopag.value;
				ls_coduniadm=f.txtcoduniadm.value;
				ls_codestpro1=f.codestpro1.value;
				ls_denestpro1=f.denestpro1.value;
				ls_codbansig=f.txtcodbansig.value;
				ls_estuac=f.txtestuac.value;
				ls_nombreaut=f.nombreaut.value;
				ls_codbanaut=f.codbanaut.value;
				ls_nombanaut=f.nombanaut.value;
				ls_rifaut=f.rifaut.value;
				ls_ctabanaut=f.ctabanaut.value;
				ls_codbanbene=f.txtcodban.value;
				ls_ctabanbene=f.txtcuenta.value;
				ls_nombanbene=f.txtdenban.value;
				ls_nrocontrol=f.txtnrocontrol.value;
				if(ls_modalidad=='CM')
				{
					alert("La Modalidad de la Orden de Pago seleccionada no permite asientos de ningun tipo");
				}
				else
				{
					if((lb_valido)&&(ls_provbene!="")&&(ls_descripcion!="")&&(ls_codban!="")&(ls_ctaban!="")&&(ls_documento!="")&&(ls_operacion!="")&&(ldec_monto>0))
					{
						ls_pagina = "sigesp_w_regdt_cargos_op.php?txtprocedencia=SCBMOV&mov_document="+ls_documento+"&fecha="+
										ld_fecha+"&procede="+ls_procede+"&tipo="+ls_tipo+"&provbene="+ls_provbene+"&descripcion="+
										ls_descripcion+"&codban="+ls_codban+"&ctaban="+ls_ctaban+"&cuenta_scg="+
										ls_cuenta_scg+"&mov_operacion="+ls_operacion+"&monto="+ldec_monto+"&objret="+ldec_objret+"&retenido="+
										ldec_monret+"&chevau="+ls_chevau+"&estint="+li_estint+"&cobrapaga="+li_cobrapaga+"&afectacion="+
										ls_afectacion+"&estbpd=D&txtnomproben="+ls_nomproben+"&estmov="+ls_estmov+"&codconmov="+
										ls_codconmov+"&tip_mov= &opener=sigesp_scb_p_opd_causapaga.php&estdoc="+ls_estdoc+"&tipdocres="+
										ls_tipdocres+"&numdocres="+ls_numdocres+"&fecdocres="+ls_fecdocres+"&nrocontrol="+ls_nrocontrol+"&tipreg="+ls_tipreg+"&ftefinancia="+ls_fte_financiamiento+"&origen="+ls_origen+
										"&tippag="+ls_tippag+"&mediopago="+ls_mediopago+"&modalidad="+ls_modalidad+"&coduniadm="+ls_coduniadm+"&estuac="+ls_estuac+"&codestpro1="+ls_codestpro1+"&denestpro1="+ls_denestpro1+"&codbansig="+ls_codbansig+
										"&nombreaut="+ls_nombreaut+"&codbanaut="+ls_codbanaut+"&nombanaut="+ls_nombanaut+"&rifaut="+ls_rifaut+"&ctabanaut="+ls_ctabanaut+
										"&codbanbene="+ls_codbanbene+"&ctabanbene="+ls_ctabanbene+"&nombanbene="+ls_nombanbene;	;							
						window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=585,height=310,left=50,top=50,location=no,resizable=no,dependent=yes");
					}
					else
					{
						alert("Complete los datos del Movimiento");
					}
				}
			}
			else
			{
				 alert("No puede eliminar el movimiento, ya fue Contabilizado o Anulado");
			}		
		}
     }
  else
     {
	   alert("Operación No puede ser procesada, El Més está Cerrado !!!");
	 }
}

function  uf_agregar_dtpre()
{
  lb_mesabi = f.hidmesabi.value;
  if (lb_mesabi=='true')
     {
	  if (uf_evaluate_cierre('SPG'))
		 {
			ls_status=f.estmov.value;
			if((ls_status!='C')&&(ls_status!='O')&&(ls_status!='A'))
			{
				ls_estdoc=f.status_doc.value;
				ls_modalidad=f.cmbmodalidad.value;
				ls_operacion=f.cmboperacion.value;		
				ls_cuenta_scg=f.txtcuenta_scg.value;
				ls_descripcion=f.txtconcepto.value;
				ls_procede="SCBOPD";
				ls_documento=f.txtdocumento.value;
				ldec_monto=f.txtmonto.value;
				ls_cuenta_scg=f.txtcuenta_scg.value;
				ld_fecha=f.txtfecha.value;
				ls_codban=f.txtcodbansig.value;
				ls_ctaban=f.txtctatesoreria.value;
				ls_cuenta_scg=f.txtcuenta_scg.value;
				ldec_objret=f.txtmonobjret.value;
				ldec_monret=f.txtretenido.value;
				ls_codconmov=f.ddlb_conceptos.value;
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
				ls_afectacion='CP';
				ls_chevau=" ";
				lb_valido=true;
				ls_estmov="N";
				li_estint=0;
				li_cobrapaga=0;
				if(f.rb_provbene[0].checked)
				{
					ls_tipo="P";
				}
				if(f.rb_provbene[1].checked)
				{
					ls_tipo="B";
				}
				ls_provbene=f.txtprovbene.value;
				ls_nomproben=f.txtdesproben.value;
				ls_tipdocres=f.txttipdocres.value;
				ls_numdocres=f.txtnumdocres.value;
				ls_fecdocres=f.txtfecdocres.value;
				if(ls_fecdocres=="")
				{		
					lb_valido=false;
					alert("Fecha del documento de respaldo no puede ser nula");
				}
				ls_tipreg=f.txttipreg.value;
				ls_fte_financiamiento=f.txtftefinanciamiento.value;
				ls_origen=f.txtorigen.value;
				ls_tippag=f.txttippag.value;
				ls_mediopago=f.txtmediopag.value;
				ls_coduniadm=f.txtcoduniadm.value;
				ls_codestpro1=f.codestpro1.value;
				ls_denestpro1=f.denestpro1.value;
				ls_codbansig=f.txtcodbansig.value;
				ls_estuac=f.txtestuac.value;
				ls_nombreaut=f.nombreaut.value;
				ls_codbanaut=f.codbanaut.value;
				ls_nombanaut=f.nombanaut.value;
				ls_rifaut=f.rifaut.value;
				ls_ctabanaut=f.ctabanaut.value;
				ls_codbanbene=f.txtcodban.value;
				ls_ctabanbene=f.txtcuenta.value;
				ls_nombanbene=f.txtdenban.value;
				ls_nrocontrol=f.txtnrocontrol.value;
				if(ls_modalidad=='CM')
				{
					alert("La Modalidad de la Orden de Pago seleccionada no permite asientos de ningun tipo");
				}
				else
				{
					if((lb_valido)&&(ls_provbene!="")&&(ls_descripcion!="")&&(ls_codban!="")&(ls_ctaban!="")&&(ls_documento!="")&&(ls_operacion!=""))
					{
						ls_pagina = "sigesp_scb_cat_compromisos_op.php?txtprocedencia=SCBMOV&mov_document="+ls_documento+"&fecha="+
										ld_fecha+"&procede="+ls_procede+"&tipo="+ls_tipo+"&provbene="+ls_provbene+"&descripcion="+
										ls_descripcion+"&codban="+ls_codban+"&ctaban="+ls_ctaban+"&cuenta_scg="+
										ls_cuenta_scg+"&mov_operacion="+ls_operacion+"&monto="+ldec_monto+"&objret="+ldec_objret+"&retenido="+
										ldec_monret+"&chevau="+ls_chevau+"&estint="+li_estint+"&cobrapaga="+li_cobrapaga+"&afectacion="+
										ls_afectacion+"&estbpd=D&txtnomproben="+ls_nomproben+"&estmov="+ls_estmov+"&codconmov="+
										ls_codconmov+"&tip_mov= &opener=sigesp_scb_p_opd_causapaga.php&estdoc="+ls_estdoc+"&tipdocres="+
										ls_tipdocres+"&numdocres="+ls_numdocres+"&fecdocres="+ls_fecdocres+"&nrocontrol="+ls_nrocontrol+"&tipreg="+ls_tipreg+"&ftefinancia="+ls_fte_financiamiento+"&origen="+ls_origen+
										"&tippag="+ls_tippag+"&mediopago="+ls_mediopago+"&modalidad="+ls_modalidad+"&coduniadm="+ls_coduniadm+"&estuac="+ls_estuac+"&codestpro1="+ls_codestpro1+"&denestpro1="+ls_denestpro1+"&codbansig="+ls_codbansig+
										"&nombreaut="+ls_nombreaut+"&codbanaut="+ls_codbanaut+"&nombanaut="+ls_nombanaut+"&rifaut="+ls_rifaut+"&ctabanaut="+ls_ctabanaut+
										"&codbanbene="+ls_codbanbene+"&ctabanbene="+ls_ctabanbene+"&nombanbene="+ls_nombanbene+"&hidcodtipdoc=00001";							
						window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=630,height=310,left=50,top=50,location=no,resizable=yes,dependent=yes");
					}
					else
					{
						alert("Complete los datos del Movimiento");
					}
				}
			}
			else
			{
				 alert("No puede eliminar el movimiento, ya fue Contabilizado o Anulado");
			}	
		}
     }
  else
     {
	   alert("Operación No puede ser procesada, El Més está Cerrado !!!");
	 }
}

function  uf_agregar_dtscg(lb_retencion)
{
  lb_mesabi = f.hidmesabi.value;
  if (lb_mesabi=='true')
     {
	  if (uf_evaluate_cierre('SPG'))
		 {
			ls_status=f.estmov.value;
			if((ls_status!='C')&&(ls_status!='O')&&(ls_status!='A'))
			{
				ls_estdoc=f.status_doc.value;
				ls_modalidad=f.cmbmodalidad.value;
				ls_operacion=f.cmboperacion.value;		
				ls_descripcion=f.txtconcepto.value;
				ls_procede="SCBOPD";
				ls_documento=f.txtdocumento.value;
				ldec_monto=f.txtmonto.value;
				ls_cuenta_scg=f.txtcuenta_scg.value;
				ld_fecha=f.txtfecha.value;
				ls_codban=f.txtcodbansig.value;
				ls_ctaban=f.txtctatesoreria.value;
				ls_cuenta_scg=f.txtcuenta_scg.value;
				ldec_objret=f.txtmonobjret.value;
				ldec_monret=f.txtretenido.value;
				ls_codconmov=f.ddlb_conceptos.value;
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
				ls_afectacion='CP';
				ls_chevau=" ";
				lb_valido=true;
				ls_estmov="N";
				li_estint=0;
				li_cobrapaga=0;
				if(f.rb_provbene[0].checked)
				{
					ls_tipo="P";
				}
				if(f.rb_provbene[1].checked)
				{
					ls_tipo="B";
				}
				ls_provbene=f.txtprovbene.value;
				ls_nomproben=f.txtdesproben.value;
				ls_tipdocres=f.txttipdocres.value;
				ls_numdocres=f.txtnumdocres.value;
				ls_fecdocres=f.txtfecdocres.value;
				if(ls_fecdocres=="")
				{		
					lb_valido=false;
					alert("Fecha del documento de respaldo no puede ser nula");
				}
				ls_tipreg=f.txttipreg.value;
				ls_fte_financiamiento=f.txtftefinanciamiento.value;
				ls_origen=f.txtorigen.value;
				ls_tippag=f.txttippag.value;
				ls_mediopago=f.txtmediopag.value;
				ls_coduniadm=f.txtcoduniadm.value;
				ls_codestpro1=f.codestpro1.value;
				ls_denestpro1=f.denestpro1.value;
				ls_codbansig=f.txtcodbansig.value;
				ls_estuac=f.txtestuac.value;
				ls_nombreaut=f.nombreaut.value;
				ls_codbanaut=f.codbanaut.value;
				ls_nombanaut=f.nombanaut.value;
				ls_rifaut=f.rifaut.value;
				ls_ctabanaut=f.ctabanaut.value;
				ls_codbanbene=f.txtcodban.value;
				ls_ctabanbene=f.txtcuenta.value;
				ls_nombanbene=f.txtdenban.value;
				ls_nrocontrol=f.txtnrocontrol.value;
				if(!lb_retencion)
				{
					ls_opener="sigesp_w_regdt_contable_op.php";
					ls_config="scrollbars=no,width=570,height=180";
				}
				else
				{
					if(ldec_objret>0)
					{
						ls_opener="sigesp_w_regdt_deducciones_op.php";
						ls_config="scrollbars=yes,width=585,height=310";
					}
					else
					{
						alert("Indique el Monto Objeto de Retención");
						return;
					}
				}
				if(ls_modalidad=='CM')
				{
					alert("La Modalidad de la Orden de Pago seleccionada no permite asientos de ningun tipo");
				}
				else
				{
					if((lb_valido)&&(ls_provbene!="")&&(ls_descripcion!="")&&(ls_codban!="")&(ls_ctaban!="")&&(ls_documento!="")&&(ls_operacion!=""))
					{
						ls_pagina = ls_opener+"?txtprocedencia=SCBMOV&mov_document="+ls_documento+"&fecha="+
										ld_fecha+"&procede="+ls_procede+"&tipo="+ls_tipo+"&provbene="+ls_provbene+"&descripcion="+
										ls_descripcion+"&codban="+ls_codban+"&ctaban="+ls_ctaban+"&cuenta_scg="+
										ls_cuenta_scg+"&mov_operacion="+ls_operacion+"&monto="+ldec_monto+"&objret="+ldec_objret+"&retenido="+
										ldec_monret+"&chevau="+ls_chevau+"&estint="+li_estint+"&cobrapaga="+li_cobrapaga+"&afectacion="+
										ls_afectacion+"&estbpd=D&txtnomproben="+ls_nomproben+"&estmov="+ls_estmov+"&codconmov="+
										ls_codconmov+"&tip_mov= &opener=sigesp_scb_p_opd_causapaga.php&estdoc="+ls_estdoc+"&tipdocres="+
										ls_tipdocres+"&numdocres="+ls_numdocres+"&fecdocres="+ls_fecdocres+"&nrocontrol="+ls_nrocontrol+"&tipreg="+ls_tipreg+"&ftefinancia="+ls_fte_financiamiento+"&origen="+ls_origen+
										"&tippag="+ls_tippag+"&mediopago="+ls_mediopago+"&modalidad="+ls_modalidad+"&coduniadm="+ls_coduniadm+"&estuac="+ls_estuac+"&codestpro1="+ls_codestpro1+"&denestpro1="+ls_denestpro1+"&codbansig="+ls_codbansig+
										"&nombreaut="+ls_nombreaut+"&codbanaut="+ls_codbanaut+"&nombanaut="+ls_nombanaut+"&rifaut="+ls_rifaut+"&ctabanaut="+ls_ctabanaut+
										"&codbanbene="+ls_codbanbene+"&ctabanbene="+ls_ctabanbene+"&nombanbene="+ls_nombanbene;	;							
						window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,"+ls_config+",left=50,top=50,location=no,resizable=no,dependent=yes");
					}
					else
					{
						alert("Complete los datos del Movimiento");
					}
				}
			}
			else
			{
				 alert("No puede eliminar el movimiento, ya fue Contabilizado o Anulado");
			}	
		 }
     }
  else
     {
	   alert("Operación No puede ser procesada, El Més está Cerrado !!!");
	 }
}
   
function uf_delete_Spg(row)
{
  lb_mesabi = f.hidmesabi.value;
  if (lb_mesabi=='true')
     {
	  if (uf_evaluate_cierre('SPG'))
		 {
		   ls_status=f.estmov.value;
		   if ((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A'))
			  {
				ls_cuenta      = eval("f.txtcuenta"+row+".value");
				ls_estcla      = eval("f.hidestcla"+row+".value");
				ls_estprog     = eval("f.txtprogramatico"+row+".value");
				ls_documento   = eval("f.txtdocumento"+row+".value");
				ls_descripcion = eval("f.txtdescripcion"+row+".value");
				ls_procede     = eval("f.txtprocede"+row+".value");
				ls_operacion   = eval("f.txtoperacion"+row+".value");
				ldec_monto     = eval("f.txtmonto"+row+".value");
				if ((ls_cuenta!="")&&(ls_estprog!="")&&(ls_documento!="")&&(ls_procede!="")&&(ls_operacion!="")&&(ldec_monto!=""))
				   {
					 f.operacion.value="DELETESPG";
					 f.delete_spg.value=row;
					 f.action="sigesp_scb_p_opd_causapaga.php";
					 f.submit();
				   }
				else
				   {
					 alert("No hay datos para eliminar");
				   }
			  }
		   else
			  {
				alert("El Movimiento ya fue Contabilizado o esta Anulado");
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
	  if (uf_evaluate_cierre('SPG'))
		 {
		   ls_status=f.estmov.value;
		   if ((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A'))
			  {
				ls_cuenta=eval("f.txtcontable"+row+".value");
				ls_documento=eval("f.txtdocscg"+row+".value");
				ls_descripcion=eval("f.txtdesdoc"+row+".value");
				ls_debhab=eval("f.txtdebhab"+row+".value");
				ldec_montocont=eval("f.txtmontocont"+row+".value");
				if ((ls_cuenta!="")&&(ls_documento!="")&&(ls_descripcion!="")&&(ls_debhab!=""))
				   {
					 f.operacion.value="DELETESCG";
					 f.delete_scg.value=row;
					 f.action="sigesp_scb_p_opd_causapaga.php";
					 f.submit();
				   }
				else
				   {
					 alert("No hay datos para eliminar");
				   }
			  }
		   else
			  { 
				alert("El Movimiento ya fue Contabilizado o esta Anulado");
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
		ls_provbene=f.txtprovbene.value;
		ldec_monto=f.txtmonto.value;
		ldec_montoobjret=f.txtmonobjret.value;
		ldec_montoret=f.txtretenido.value;
		ldec_diferencia=f.txtdiferencia.value;
		
   }

function uf_selec_interes(obj)
{
  alert(obj.checked);
  if (obj.checked==true)
	 {
	   f.estint.value=1;
	 }
  else
	 {
  	   f.estint.value=0;
	 }
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
  if (parseFloat(ldec_monto)<parseFloat(ldec_monobjret))
     {
	   alert("Monto Objeto a Retención no puede ser mayor al del Movimiento");	
	   f.txtmonobjret.value=uf_convertir(0);
	   f.txtmonobjret.focus();		
     }
}
   
function catunidadadm()
{
  uf_validar_estatus_mes();
  if (uf_evaluate_cierre('SPG'))
     {
	   ls_status=f.estmov.value;
	   if ((ls_status!='C')&&(ls_status!='O')&&(ls_status!='A'))
		  {    
		    pagina="sigesp_spg_cat_unidad.php";
		    window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=583,height=400,resizable=yes,location=no");
		  }
	   else
		  {
		    alert("El Movimiento ya fue Contabilizado o esta Anulado");
		  }
	 }
}
	
function  uf_montoobjret(obj)//Asigno el monto del documento al monto objeto a retencion
{
  ldec_monto=obj.value;
  f.txtmonobjret.value=ldec_monto;
}
   
function catalogo_estpro()
{
uf_validar_estatus_mes();
if (uf_evaluate_cierre('SPG'))
   {
	 f=document.form1;
	 ls_status=f.estmov.value;
	 if ((ls_status!='C')&&(ls_status!='O')&&(ls_status!='A'))
	    {
		  ls_coduniadm=f.txtcoduniadm.value;
		  ls_estuniadm=f.txtestuac.value;
		  li_totpre=f.totpre.value;
		  li_totcon=f.totcon.value;
		  if ((li_totpre==0)&&(li_totcon==0))
		     {
			   if (ls_coduniadm!="")
			      {
				    pagina="sigesp_cat_estpro_op.php?hidcoduniadm="+ls_coduniadm+"&hidestuniadm="+ls_estuniadm;
				    window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=630,height=400,resizable=yes,location=no");
			      }
			   else
			      {
				    alert("Seleccione la unidad administradora");
			      }
		     }
		  else
		     {
			   alert("No puede modificar el proyecto,hay detalles Contables y/o Presupuestarios.");
		     }
	    }
	 else
	    {
		  alert("El Movimiento ya fue Contabilizado o esta Anulado");
	    }   	
   }
}

function uf_valida_cambio()
{
    uf_validar_estatus_mes();
	ls_modalidad = f.modordpag.value;
	ls_status    = f.status_doc.value;
	if(ls_status=='C')
	{
		f.cmbmodalidad.value=ls_modalidad;
	}
	else
	{
		f.modordpag.value=f.cmbmodalidad.value;
	}
}

function catbanaut()
{
  uf_validar_estatus_mes();
  if (uf_evaluate_cierre('SPG'))
     {
	   ls_status=f.estmov.value;
	   if ((ls_status!='C')&&(ls_status!='O')&&(ls_status!='A'))
	      {
		    pagina="sigesp_cat_bancos.php?procede='OP'"
		    window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	      }
 	   else
	      {
	  	    alert("El Movimiento ya fue Contabilizado o esta Anulado");
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
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>