<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
$ls_logusr    = $_SESSION["la_logusr"];
$li_estpreing = $_SESSION["la_empresa"]["estpreing"];
require_once("class_funciones_banco.php");
$io_fun_banco = new class_funciones_banco();
$io_fun_banco->uf_load_seguridad("SCB","sigesp_scb_p_movbanco.php",$ls_permisos,&$la_seguridad,$la_permisos);
$ls_reporte   = $io_fun_banco->uf_select_config("SCB","REPORTE","CHEQUE_VOUCHER","sigesp_scb_rpp_voucher_ven_pdf.php","C");//print $ls_reporte;
$ls_reporteanulado = $io_fun_banco->uf_select_config("SCB","REPORTE","CHEQUE_VOUCHER_ANULADO","sigesp_scb_rpp_voucher_ven_pdf.php","C");
$ls_report = $io_fun_banco->uf_select_config("SCB","REPORTE","CARTA_ORDEN","sigesp_scb_rpp_cartaorden_pdf.php","C");
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
<title>Movimiento de Banco</title>
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
			f.action="sigesp_scb_p_movbanco.php";
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
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_funciones_db.php");
    require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/ddlb_operaciones_spg.php");
	require_once("../shared/class_folder/ddlb_operaciones_spi.php");
	require_once("../shared/class_folder/ddlb_conceptos.php");
	require_once("../shared/class_folder/grid_param.php");
	require_once("class_funciones_banco.php");
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
	$funciones_banco= new class_funciones_banco();
	$io_disfin      = new sigesp_scb_c_disponibilidad_financiera("../");
	$ls_tipvaldis   = $io_disfin->uf_load_tipo_validacion();
	$ls_empresa     = $_SESSION["la_empresa"]["codemp"];
	$as_estmodest   = $_SESSION["la_empresa"]["estmodest"];
	$li_estciespg   = $io_fun_banco->uf_load_estatus_cierre($li_estciespi,$li_estciescg);

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
		if($ls_operacion=="CAMBIO_OPERA")
		{
			$ls_opepre=0;	
			$ls_codconmov="---";
		}
		else
		{
			if(array_key_exists("ddlb_spg",$_POST))
			{			
				$ls_opepre=$_POST["ddlb_spg"];		
			}
			elseif(array_key_exists("ddlb_spi",$_POST))
			{
				$ls_opepre=$_POST["ddlb_spi"];
			}			
			else
			{
				$ls_opepre=$_POST["opepre"];
			}
		}
		if(array_key_exists("ddlb_conceptos",$_POST))
		{			
			$ls_codigoconcepto=$_POST["ddlb_conceptos"]; 	
		}
		$li_estciespg = $_POST["hidestciespg"];
	    $li_estciespi = $_POST["hidestciespi"];
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
		$lastspg = $_POST["lastspg"];
		$lastscg = $_POST["lastscg"];
		$lastspi = $_POST["lastspi"];
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
		$li_estcob=$_POST["estcob"];
		$ls_codfuefin=rtrim($_POST["txtftefinanciamiento"]);
		if($ls_codfuefin=="")
		{
			$ls_codfuefin="--";
		}
		$ls_denfuefin=rtrim($_POST["txtdenftefinanciamiento"]);
		if(array_key_exists("nocontabili",$_POST))
		{   $lb_nocontab="checked";  }
		else
		{   $lb_nocontab="";   }
		$ls_numcarord=$_POST["txtnumcarord"];
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
		$l_codcon=$_POST["codcon"];
	    $ls_numordpagmin = $_POST["txtnumordpagmin"];
        $ls_codtipfon = $_POST["hidcodtipfon"];	
		$ls_dentipfon = $_POST["hiddentipfon"];	
		$ld_monmaxmov =	$_POST["hidmonmaxmov"];	   
	}
	else
	{
		$ls_operacion= "NUEVO" ;
		$ls_estdoc="N";
		$li_estimpche=0;
		$l_codcon="";
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
		global $ls_estmov;
		global $ls_estcon;
		global $ldec_mondeb;
		global $ldec_monhab;
		global $objectSpg;
		global $li_rows_spg;
		global $ldec_monspg;
		global $ldec_monspi;
		global $objectSpi;
		global $li_rows_spi;
		global $objectRet;
		global $li_rows_ret;
		global $ldec_montoret;
		global $ldec_diferencia;
		global $ls_docmov;
		global $ls_codban;
		global $ls_cuenta_banco;
		global $ls_mov_operacion;
		global $ls_chevau;
		$in_classmovbco->uf_cargar_dt($ls_docmov,$ls_codban,$ls_cuenta_banco,$ls_mov_operacion,$ls_estmov,&$objectScg,&$li_row,&$ldec_mondeb,&$ldec_monhab,&$objectSpg,&$li_rows_spg,&$ldec_monspg,&$objectSpi,&$li_rows_spi,&$ldec_monspi);
		$ls_chevau=$in_classmovbco->uf_numero_voucher($_SESSION["la_empresa"]["codemp"],$ls_codban,$ls_cuenta_banco,$ls_docmov);
		$ldec_diferencia=round($ldec_mondeb,2)-round($ldec_monhab,2);
	
	}
	
	function uf_nuevo()
	{
		global $ls_estdoc,$li_estpreing;
		$ls_estdoc="N";
		global $ls_mov_operacion;
		$ls_mov_operacion="NC";
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
		global $li_estcob;
		$li_estcob=0;
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
		global $l_codcon;
		$l_codcon="";
		global $ls_numordpagmin,$ls_codtipfon,$ls_dentipfon,$ld_monmaxmov;
		$ls_numordpagmin = $ls_codtipfon = $ls_dentipfon = "";
		$ld_monmaxmov = 0;
		
		$li_row_scg=1;
		$objectScg[$li_row_scg][1] = "<input type=text name=txtcontable".$li_row_scg."  id=txtcontable".$li_row_scg."  value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";		
		$objectScg[$li_row_scg][2] = "<input type=text name=txtdocscg".$li_row_scg."    value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
		$objectScg[$li_row_scg][3] = "<input type=text name=txtdesdoc".$li_row_scg."    value='' class=sin-borde readonly style=text-align:left size=35 maxlength=254>";
		$objectScg[$li_row_scg][4] = "<input type=text name=txtprocdoc".$li_row_scg."   value='' class=sin-borde readonly style=text-align:center size=8 maxlength=6>";
		$objectScg[$li_row_scg][5] = "<input type=text name=txtdebhab".$li_row_scg."    value='' class=sin-borde readonly style=text-align:center size=8 maxlength=1>"; 
		$objectScg[$li_row_scg][6] = "<input type=text name=txtmontocont".$li_row_scg." value='' class=sin-borde readonly style=text-align:right size=22 maxlength=22>";
		$objectScg[$li_row_scg][7] = "<input type=text name=txtcodded".$li_row_scg."    value='' class=sin-borde readonly style=text-align:right size=5 maxlength=5>";
		$objectScg[$li_row_scg][8] = "<a href=javascript:uf_delete_Scg('".$li_row_scg."');><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar detalle contable' width=15 height=15 border=0></a>";
		
		global $li_temp_spg;
		$li_temp_spg=1;
		$objectSpg[$li_temp_spg][1] = "<input type=text name=txtcuenta".$li_temp_spg."       id=txtcuenta".$li_temp_spg."       value='' class=sin-borde readonly style=text-align:center size=10 maxlength=10>";
		$objectSpg[$li_temp_spg][2] = "<input type=text name=txtprogramatico".$li_temp_spg." id=txtprogramatico".$li_temp_spg." value='' class=sin-borde readonly style=text-align:center size=30 maxlength=129><input type=hidden name=hidestcla".$li_temp_spg." id=hidestcla".$li_temp_spg." value=''>"; 
		$objectSpg[$li_temp_spg][3] = "<input type=text name=txtdocumento".$li_temp_spg."    id=txtdocumento".$li_temp_spg."    value='' class=sin-borde readonly style=text-align:center size=13 maxlength=15>";
		$objectSpg[$li_temp_spg][4] = "<input type=text name=txtdescripcion".$li_temp_spg."  id=txtdescripcion".$li_temp_spg."  value='' class=sin-borde readonly style=text-align:left>";
		$objectSpg[$li_temp_spg][5] = "<input type=text name=txtprocede".$li_temp_spg."      id=txtprocede".$li_temp_spg."      value='' class=sin-borde readonly style=text-align:center size=5 maxlength=6>";
		$objectSpg[$li_temp_spg][6] = "<input type=text name=txtoperacion".$li_temp_spg."    id=txtoperacion".$li_temp_spg."    value='' class=sin-borde readonly style=text-align:center size=5 maxlength=3>";
		$objectSpg[$li_temp_spg][7] = "<input type=text name=txtmonto".$li_temp_spg."        id=txtmonto".$li_temp_spg."        value='' class=sin-borde readonly style=text-align:right  size=15 maxlength=19>";		
		$objectSpg[$li_temp_spg][8] = "<a href=javascript:uf_delete_Spg('".$li_temp_spg."');><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar detalle Presupuestario de Gasto' width=15 height=15 border=0></a>";	
		
		global $li_temp_spi;
		$li_temp_spi=1;
		$objectSpi[$li_temp_spi][1] = "<input type=text name=txtcuentaspi".$li_temp_spi." value='' class=sin-borde readonly style=text-align:center size=10>";
		if ($li_estpreing==1)
		   {
			 $objectSpi[$li_temp_spi][2] = "<input type=text name=txtcodestprospi".$li_temp_spi." value='' class=sin-borde readonly style=text-align:center size=30 maxlength=129><input type=hidden name=hidestclaspi".$li_temp_spg." id=hidestclaspi".$li_temp_spg." value=''>";
			 $objectSpi[$li_temp_spi][3] = "<input type=text name=txtdocspi".$li_temp_spi."       value='' class=sin-borde readonly style=text-align:center size=13 maxlength=15>";
			 $objectSpi[$li_temp_spi][4] = "<input type=text name=txtdescspi".$li_temp_spi."      value='' class=sin-borde readonly style=text-align:left>"; 
			 $objectSpi[$li_temp_spi][5] = "<input type=text name=txtprocspi".$li_temp_spi."      value='' class=sin-borde readonly style=text-align:center size=5  maxlength=6>";
			 $objectSpi[$li_temp_spi][6] = "<input type=text name=txtopespi".$li_temp_spi."       value='' class=sin-borde readonly style=text-align:center size=5  maxlength=3>";
			 $objectSpi[$li_temp_spi][7] = "<input type=text name=txtmontospi".$li_temp_spi."     value='' class=sin-borde readonly style=text-align:right  size=15 maxlength=19>";
			 $objectSpi[$li_temp_spi][8] = "<a href=javascript:uf_delete_Spi('".$li_temp_spi."');><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar detalle Presupuestario de Ingreso' width=15 height=15 border=0></a>";			   
		   }
		else
		   {
			 $objectSpi[$li_temp_spi][2] = "<input type=text name=txtdescspi".$li_temp_spi."   value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>"; 
			 $objectSpi[$li_temp_spi][3] = "<input type=text name=txtprocspi".$li_temp_spi."   value='' class=sin-borde readonly style=text-align:center size=32 maxlength=45>";
			 $objectSpi[$li_temp_spi][4] = "<input type=text name=txtdocspi".$li_temp_spi."    value='' class=sin-borde readonly style=text-align:center>";
			 $objectSpi[$li_temp_spi][5] = "<input type=text name=txtopespi".$li_temp_spi."    value='' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
			 $objectSpi[$li_temp_spi][6] = "<input type=text name=txtmontospi".$li_temp_spi."  value='' class=sin-borde readonly style=text-align:center size=5 maxlength=3>";
			 $objectSpi[$li_temp_spi][7] = "<a href=javascript:uf_delete_Spi('".$li_temp_spi."');><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar detalle Presupuestario de Ingreso' width=15 height=15 border=0></a>";	
		   }
	}
	
	$title2[1]="Cuenta";       $title2[2]="Documento";      $title2[3]="Descripción";   $title2[4]="Procede";   	   $title2[5]="Debe/Haber";    $title2[6]="Monto";      $title2[7]="Deduccion";   $title2[8]="Edición";
	$title[1]="Cuenta";        $title[2]="Programático";    $title[3]="Documento";      $title[4]="Descripción";   $title[5]="Procede";        $title[6]="Operación";  $title[7]="Monto";       $title[8]="Edición";
    $titleSpi[1] = "Cuenta";
	if ($li_estpreing==1)
	   {
		 $titleSpi[2] = "Programático";
		 $titleSpi[3] = "Documento";
		 $titleSpi[4] = "Descripción";
		 $titleSpi[5] = "Procede";
		 $titleSpi[6] = "Operación";
		 $titleSpi[7] = "Monto";
		 $titleSpi[8] = "Edición";	   
	   }
	else
	   {
		 $titleSpi[2] = "Descripción";
		 $titleSpi[3] = "Procede";
		 $titleSpi[4] = "Documento";
		 $titleSpi[5] = "Operación";
		 $titleSpi[6] = "Monto";
		 $titleSpi[7] = "Edición";
	   }
	$gridSpi="grid_Spi";
	$grid2="gridscg";	
    $grid1="grid_SPG";	

	
	if($ls_operacion == "NUEVO")
	{
		$ls_operacion= "" ;
		uf_nuevo();
		$ls_numcarord="";
		$ls_docmov="";
	}
	if($ls_operacion=="PRINT_CARTAORDEN")
	{
		$ls_tipo=$_POST["rb_provbene"];
		uf_cargar_dt();	
		$ls_codigo=$in_classconfig->uf_buscar_seleccionado();
		if ($ls_codigo!="000")
		   {
			 $ls_pagina = "reportes/".$ls_report."?codigo=$ls_codigo&codban=$ls_codban&ctaban=$ls_cuenta_banco&numdoc=$ls_numcarord&chevau=&codope=ND&tipproben=$ls_tipo";
		   }
		else
			$ls_pagina="reportes/sigesp_scb_rpp_voucher_ven_pdf.php?codban=$ls_codban&ctaban=$ls_cuenta_banco&numdoc=$ls_docmov&chevau=&codope=ND";			
		?>
		<script language="javascript">						
		window.open('<?php print $ls_pagina; ?>',"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=583,height=400,left=50,top=50,location=no,resizable=yes");
		</script>
		<?php 		
	}
	if($ls_operacion == "GUARDAR")
	{			
		$ls_provbene=$_POST["txtprovbene"];
		$ls_desproben=$_POST["txtdesproben"];
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
		$in_classmovbco->io_sql->begin_transaction();
		$lb_valido=$in_classmovbco->uf_guardar_automatico($ls_codban,$ls_cuenta_banco,$ls_docmov,$ls_mov_operacion,$ld_fecha,$ls_desmov,$ls_codconmov,$ls_codpro,$ls_cedbene,$ls_desproben,$ldec_montomov,$ldec_monobjret,$ldec_montoret,$ls_chevau,$ls_estmov,$li_estint,"$ls_opepre",$ls_estbpd,'SCBMOV',' ',$ls_estdoc,$ls_tipo,$ls_codfuefin,$ls_numordpagmin,$ls_codtipfon,$li_estcob);
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
  if ($ls_operacion == "ELIMINAR")
	 {
	   $lb_valido = true;
	   $in_classmovbco->io_sql->begin_transaction();
	   if ($ls_mov_operacion=='DP' || $ls_mov_operacion=='NC')
	      {
		    if (!empty($ls_numordpagmin) || !empty($ls_codtipfon))
			   {
			     $lb_valido = $in_classmovbco->uf_load_documentos_asociados($ls_numordpagmin,$ls_codtipfon);
			     if (!$lb_valido)
				    {
					  uf_cargar_dt();	
					}
			   }
		  }
	   if ($lb_valido)
	      {
		    $lb_valido = $in_classmovbco->uf_delete_all_movimiento($ls_docmov,$ls_codban,$ls_cuenta_banco,$ls_mov_operacion,$ls_estmov);
		    if ($lb_valido)
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
	 }
	if($ls_operacion=="DELETESCG")
	{
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
		if($ls_codded!="00000")
		{
			if(($ls_mov_operacion=="ND")||($ls_mov_operacion=="RE")||($ls_mov_operacion=="CH"))
			{
				$ls_operacioncon="H";
			}
			else
			{
				$ls_operacioncon="D";
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
	}

if ($ls_operacion=="DELETESPG")
   {
     $li_row_delete   = $_POST["delete_spg"];
	 $ls_cuenta_spg   = trim($_POST["txtcuenta".$li_row_delete]);
	 $ls_programatica = trim($_POST["txtprogramatico".$li_row_delete]);
 	 $ls_estcla       = trim($_POST["hidestcla".$li_row_delete]);
	 $ls_codestpro1   = substr($ls_programatica,0,$li_loncodestpro1);
	 $ls_codestpro2   = substr($ls_programatica,$li_loncodestpro1+1,$li_loncodestpro2);
	 $ls_codestpro3   = substr($ls_programatica,$li_loncodestpro1+$li_loncodestpro2+2,$li_loncodestpro3);
	 $ls_codestpro1   = trim(str_pad($ls_codestpro1,25,0,0));
	 $ls_codestpro2   = trim(str_pad($ls_codestpro2,25,0,0));
	 $ls_codestpro3   = trim(str_pad($ls_codestpro3,25,0,0));
	 if ($as_estmodest==2)
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
	 $ls_numdoc     = $_POST["txtdocumento".$li_row_delete];
	 $ls_operacion  = $_POST["txtoperacion".$li_row_delete];
 	 $ldec_montospg = $_POST["txtmonto".$li_row_delete];
	 $ldec_montospg = str_replace(".","",$ldec_montospg);
	 $ldec_montospg = str_replace(",",".",$ldec_montospg);
	 $in_classmovbco->io_sql->begin_transaction();
	 $lb_valido=$in_classmovbco->uf_delete_dt_spg($ls_docmov,$ls_codban,$ls_cuenta_banco,$ls_mov_operacion,$ls_estmov,$ls_numdoc,$ls_cuenta_spg,$ls_operacion,$ls_programatica,$ldec_montospg,$ls_estcla);
	 $msg->message($in_classmovbco->is_msg_error);
	 if ($lb_valido)
		{
		  $in_classmovbco->io_sql->commit();
		}
	 else
		{
		  $in_classmovbco->io_sql->rollback();
		}
	 uf_cargar_dt();
   }
  
  if($ls_operacion=="DELETESPI")
	{
		$li_row_delete = $_POST["delete_spi"];
		$ls_cuenta_spi = trim($_POST["txtcuentaspi".$li_row_delete]);
		$ls_numdoc     = $_POST["txtdocspi".$li_row_delete];
		$ls_operacion  = trim($_POST["txtopespi".$li_row_delete]);
		$ldec_montospg = $_POST["txtmontospi".$li_row_delete];
		if ($li_estpreing==1)
		   {
			 $ls_estcla     = trim($_POST["hidestclaspi".$li_row_delete]);
			 $ls_codestpro  = $_POST["txtcodestprospi".$li_row_delete];
		     $ls_codestpro1 = substr($ls_codestpro,0,$li_loncodestpro1);
			 $ls_codestpro2 = substr($ls_codestpro,$li_loncodestpro1+1,$li_loncodestpro2);
			 $ls_codestpro3 = substr($ls_codestpro,$li_loncodestpro1+$li_loncodestpro2+2,$li_loncodestpro3);
			 $ls_codestpro1 = trim(str_pad($ls_codestpro1,25,0,0));
			 $ls_codestpro2 = trim(str_pad($ls_codestpro2,25,0,0));
			 $ls_codestpro3 = trim(str_pad($ls_codestpro3,25,0,0));
			 if ($as_estmodest==2)
			    {
				  $ls_codestpro4 = substr($ls_codestpro,$li_loncodestpro1+$li_loncodestpro2+$li_loncodestpro3+3,$li_loncodestpro4);
				  $ls_codestpro5 = substr($ls_codestpro,-$li_loncodestpro5);
				  $ls_codestpro4 = trim(str_pad($ls_codestpro4,25,0,0));
				  $ls_codestpro5 = trim(str_pad($ls_codestpro5,25,0,0));
				}
		     else
			    {
				  $ls_codestpro4 = $ls_codestpro5 = str_pad("",25,0,0);
				}
		   }
		else
		   {
		     $ls_estcla = '-';
			 $ls_codestpro1 = $ls_codestpro2 = $ls_codestpro3 = $ls_codestpro4 = $ls_codestpro5 = str_pad('',25,'-',0);		   
		   }
		$ldec_montospg = str_replace(".","",$ldec_montospg);
		$ldec_montospg = str_replace(",",".",$ldec_montospg);
		$in_classmovbco->io_sql->begin_transaction();
		$lb_valido=$in_classmovbco->uf_delete_dt_spi($ls_docmov,$ls_codban,$ls_cuenta_banco,$ls_mov_operacion,$ls_estmov,$ls_numdoc,$ls_cuenta_spi,$ls_operacion,$ldec_montospg,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla);
			
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
	}
	if($ls_operacion == "CAMBIO_OPERA")
	{
		uf_cargar_dt();	
	}
	if($ls_operacion == "VERIFICAR_VAUCHER")
	{
		$ls_chevaux=$_POST["txtchevau"];
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
	
	if($ls_operacion == "TIPO_CONCEPTO")
	{
		$ls_mov_operacion=$_POST["cmboperacion"]; 
		$ls_codcon=str_pad($_POST["codcon"],3,"0",0);			
		if(!empty($ls_codcon))
		{
			$ls_codconmov=$ls_codcon;
			
		}
		$funciones_banco->buscar_banco_cta($ls_codcon, $ls_codban, $ls_nomban, $ls_ctaban, $ls_denctaban);
		uf_cargar_dt();
	}
	if($ls_mov_operacion=='ND')
	{
		$lb_nd="selected";
		$lb_nc="";
		$lb_dp="";
		$lb_re="";
		$lb_ch="";
	}
	if($ls_mov_operacion=='NC')
	{
		$lb_nd="";
		$lb_nc="selected";
		$lb_dp="";
		$lb_re="";
		$lb_ch="";
		if($li_estint==1)
		{
			$lb_checked="checked";
		}
		else
		{
			$lb_checked="";
		}
		if($li_estcob==1)
		{
			$lb_checkedcob="checked";
		}
		else
		{
			$lb_checkedcob="";
		}
	}
	if($ls_mov_operacion=='DP')
	{
		$lb_nd="";
		$lb_nc="";
		$lb_dp="selected";
		$lb_re="";
		$lb_ch="";
	}
	if($ls_mov_operacion=='RE')
	{
		$lb_nd="";
		$lb_nc="";
		$lb_dp="";
		$lb_re="selected";
		$lb_ch="";
	}
	if($ls_mov_operacion=='CH')
	{
		$lb_nd="";
		$lb_nc="";
		$lb_dp="";
		$lb_re="";
		$lb_ch="selected";
		if(($ls_operacion=="NUEVO")||($ls_operacion=="CAMBIO_OPERA"))
		{
			$ls_chevau=$in_classmovbco->uf_generar_voucher($ls_empresa);
		}
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
  <form name="form1" method="post" action="" id="sigesp_scb_p_movbanco.php">
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
      <input name="estmov"      type="hidden" id="estmov"     value="<?php print $ls_estmov;?>">
        <input name="estcon"    type="hidden" id="estcon"     value="<?php print $ls_estcon;?>">
        <input name="estimpche" type="hidden" id="estimpche"  value="<?php print $li_estimpche?>">
        Movimientos de Banco
      <input name="hidestciespg" type="hidden" id="hidestciespg" value="<?php echo $li_estciespg; ?>">
      <input name="hidestciespi" type="hidden" id="hidestciespi" value="<?php echo $li_estciespi; ?>">
      <input name="hidestciescg" type="hidden" id="hidestciescg" value="<?php echo $li_estciescg; ?>">
      <input name="hidcodtipfon" type="hidden" id="hidcodtipfon" value="<?php echo $ls_codtipfon; ?>">
      <input name="hiddentipfon" type="hidden" id="hiddentipfon" value="<?php echo $ls_dentipfon; ?>">
      <input name="hidmonmaxmov" type="hidden" id="hidmonmaxmov" value="<?php echo $ld_monmaxmov; ?>"></td>
    </tr>
    <tr>
      <td height="13" colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td height="23" colspan="6"><div align="center">
        <input type="radio" name="rb_voucher" id="radio3" value="V" border="1" style="width:10 ; height:10" >
        Voucher Venezuela
        <input type="radio" name="rb_voucher" id="radio4" value="A" border="1" style="width:10 ; height:10">
      Voucher Agricola 
	  <input type="radio" name="rb_voucher" id="radio5" value="P" border="1" style="width:10 ; height:10" >
        Voucher Provincial</div></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Operaci&oacute;n</td>
      <td><div align="left">
          <select name="cmboperacion" id="select" onChange="javascript:uf_verificar_operacion();" style="width:120px">
            <option value="ND" <?php print $lb_nd;?>>Nota de D&eacute;bito</option>
            <option value="NC" <?php print $lb_nc;?>>Nota Cr&eacute;dito</option>
            <option value="DP" <?php print $lb_dp;?>>Dep&oacute;sito</option>
            <option value="RE" <?php print $lb_re;?>>Retiro</option>
            <option value="CH" <?php print $lb_ch;?>>Cheque</option>
          </select>
          <label></label>
      </div></td>
      <td>&nbsp;</td>
      <?php
	    if ($ls_mov_operacion!='RE')
		   {
	  ?>
	  <td colspan="3">No. Orden Pago Ministerio
      <input name="txtnumordpagmin" type="text" id="txtnumordpagmin" onKeyPress="return keyRestrict(event,'0123456789'); " value="<?php echo $ls_numordpagmin; ?>" size="20" maxlength="15" style="text-align:center" onKeyUp="javascript:uf_chequear();" <?php print $ls_readOnly_doc; if ($ls_mov_operacion=='CH' || $ls_mov_operacion=='ND'){ ?> readonly <?php } ?>><?php if ($ls_mov_operacion=='CH' || $ls_mov_operacion=='ND'){ ?>&nbsp;<a href="javascript:uf_catalogo_ordenes();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar Ordenes de Pago Ministerio..." width="15" height="15" border="0" title="Buscar Ordenes de Pago Ministerio..."></a>      <?php }?></td>
	  <?php 
	       }
	  ?> 
    </tr>
    <?php
	if ($ls_mov_operacion=='DP' || $ls_mov_operacion=='NC')
	   {
	?>
	<tr>
      <td height="22" style="text-align:right">Tipo de Fondo </td>
      <td colspan="5" style="text-align:left"><label>
        <input name="txtcodtipfon" type="text" id="txtcodtipfon" value="<?php echo $ls_codtipfon; ?>" size="9" maxlength="4" style="text-align:center" readonly>
        <a href="javascript:uf_cat_tipo_fondo();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar Tipo Fondo..." width="15" height="15" border="0" title="Buscar Tipo Fondo..."></a> 
        <input name="txtdentipfon" type="text" class="sin-borde" id="txtdentipfon" value="<?php echo $ls_dentipfon; ?>" size="110" maxlength="254" readonly>
      </label></td>
    </tr>
    <?php
	   }
	if ($ls_mov_operacion!='RE')
	   {	
	?>	
	<tr>
      <td height="22" style="text-align:right"><?php print "Afectaci&oacute;n"; ?></td>
      <td colspan="5" style="text-align:left">
	  <?php  
	    if (($ls_mov_operacion=='ND')||($ls_mov_operacion=='CH'))
		   {
		     $obj_spg->uf_cargar_ddlb_spg(0,$ls_opepre,$ls_mov_operacion); 	
		   }
		elseif(($ls_mov_operacion=='DP')||($ls_mov_operacion=='NC'))
		   {
		     $obj_spi->uf_cargar_ddlb_spi(0,$ls_opepre,$ls_mov_operacion); 
		   }				
	  ?>	  </td>
    </tr>
    <?php
	   }
	?>
	<tr>
      <td height="22" style="text-align:right">Tipo Concepto</td>
      <td colspan="5" style="text-align:left"><?php $obj_con->uf_cargar_conceptos($ls_mov_operacion,$ls_codconmov, $ls_codban,$ls_denban, $ls_cuenta_banco,$ls_dencuenta_banco);	?>
          <input name="codconmov" type="hidden" id="codconmov" value="<?php print str_pad($ls_codconmov,3,"0",0);?>">
		  <input name="tipo"      type="hidden" id="tipo">          
	      <input name="opepre"    type="hidden" id="opepre" value="<?php print $ls_opepre;?>">	  </td>
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
      <td height="22" style="text-align:right">N&ordm; Carta Orden</td>
      <td><span style="text-align:left">
        <input name="txtnumcarord" type="text" id="txtnumcarord" value="<?php print $ls_numcarord;?>" size="24" maxlength="15" style="text-align:center" readonly>
      </span></td>
      <td align="right">&nbsp;</td>
      <td width="146" align="left">&nbsp;</td>
      <td align="right"><?php if($ls_mov_operacion=="CH")
								{
									print "Voucher";
								}
								?></td>
      <td style="text-align:left"><?php if($ls_mov_operacion=="CH")
								{?>
								  <input name="txtchevau" type="text" id="txtchevau" value="<?php print $ls_chevau ?>" style="text-align:center" size="28" maxlength="25" onChange="javascript:ue_verificar_vaucher();" onBlur="javascript:rellenar_cad(this.value,25,'voucher');" onKeyPress="return keyRestrict(event,'0123456789'); "></td>
								<?php
								}
								?>
	  </td>
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
<input name="txtmonto" type="text" id="txtmonto" style="text-align:right" onBlur="javascript:uf_montoobjret(this);javascript:uf_verificar_monto(this);" onKeyPress="return(currencyFormat(this,'.',',',event));" value="<?php print number_format($ldec_montomov,2,",",".");?>" size="24" maxlength="22">	  </div></td>
      <td width="64"><div align="right">M.O.R.</div></td>
      <td>
        <div align="left">
          <input name="txtmonobjret" type="text" id="txtmonobjret" style="text-align:right" onBlur="javascript:uf_verificar_retenido();" onKeyPress="return(currencyFormat(this,'.',',',event));" value="<?php print  number_format($ldec_monobjret,2,",",".");?>" size="24" maxlength="22">
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
      <td><div align="left">
	            <?php
				if($ls_mov_operacion=="NC")
				{
				?>
          			<input name="chkinteres" type="checkbox" class="sin-borde" <?php print $lb_checked;?> id="chkinteres" style="width:15px; height:15px" onClick="uf_selec_interes(this);" value="1">
         		<?php	
				}	
				?>
                <input name="estint" type="hidden" id="estint" value="<?php print $li_estint;?>">
</div></td>
      <td><div align="right">
        <input name="nocontabili" type="checkbox" class="sin-borde" id="nocontabili" style="width:15px; height:15px" onClick="javascript:uf_nocontabili();" value="1" <?php print $lb_nocontab; ?>>
      </div></td>
      <td style="text-align:left">No Contabilizable</td>
      <td style="text-align:right"><input name="chkanula" type="checkbox" class="sin-borde" id="chkanula" value="1"></td>
      <td style="text-align:left"><span style="text-align:right">Anular Movimiento</span></td>
    </tr>
    <tr>
      <td height="22"><div align="right">
          <?php
				if($ls_mov_operacion=="NC")
				{
				?>
          <input name="txtcob" id="txtcob" type="text" value="Cobranza" class="sin-borde" size="10" style="text-align:right" readonly>
          <?php	
				}	
				?>
      </div></td>
      <td><div align="left">
          <?php
				if($ls_mov_operacion=="NC")
				{
				?>
          <input name="chkcobranza" type="checkbox" class="sin-borde" <?php print $lb_checkedcob;?> id="chkcobranza" style="width:15px; height:15px" onClick="uf_selec_cobranza(this);" value="1">
          <?php	
				}	
				?>
          <input name="estcob" type="hidden" id="estcob" value="<?php print $li_estcob;?>">
      </div></td>
      <td><div align="right"></div></td>
      <td style="text-align:left">&nbsp;</td>
      <td style="text-align:right">&nbsp;</td>
      <td style="text-align:left">&nbsp;</td>
    </tr>
    <tr>
      <td height="21" colspan="6"><table width="613" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td width="203"><div align="center"><a href="#01">Detalle Contable/Retenciones </a></div></td>
          <td width="203"><div align="center"><a href="#03">Detalle Presupuesto de Ingreso </a></div></td>
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
      <td height="22" colspan="6">&nbsp;&nbsp;<a href="javascript: uf_agregar_dtpre();"><img src="../shared/imagebank/tools/nuevo.gif" width="15" height="15" border="0">Agregar detalle Presupuesto</a> </td>
    </tr>
    <tr>
      <td height="22" colspan="6"><div align="center"><?php $io_grid->makegrid($li_rows_spg,$title,$objectSpg,770,'Detalles Presupuestarios',$grid1);?>
        <input name="totpre"  type="hidden" id="totpre"  value="<?php print $li_rows_spg; ?>">
        <input name="lastspg" type="hidden" id="lastspg" value="<?php print $lastspg;?>">
        <input name="delete_spg" type="hidden" id="delete_spg">
		<input name="delete_spi" type="hidden" id="delete_spi">
		
</div></td>
    </tr>
    <tr>
      <td height="22" colspan="6"><table width="223" border="0" align="right" cellpadding="0" cellspacing="0">
        <tr>
          <td width="96" height="20"><div align="right">Total Presupuesto </div></td>
          <td width="127"><input name="totspg" type="text" id="totspg" readonly value="<?php print number_format($ldec_monspg,2,',','.');?>" style="text-align:right"></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="22" colspan="6">&nbsp;&nbsp; </td>
    </tr>
    <tr>
      <td height="22" colspan="6">&nbsp;&nbsp;<img src="../shared/imagebank/tools/nuevo.gif" width="15" height="15" border="0"><a href="javascript:ue_det_ingreso()">Agregar detalle Ingreso</a></td>
    </tr>
    <tr>
      <td height="22" colspan="6"><div align="center">
        <a name="03"></a>
        <?php $io_grid->makegrid($li_rows_spi,$titleSpi,$objectSpi,770,'Detalle Ingresos',$gridSpi);?>
        <input name="totspi" type="hidden" id="totspi" value="<?php print $li_rows_spi ?>">
        <input name="lastspi" type="hidden" id="lastspi" value="<?php print $lastspi;?>">
      </div></td>
    </tr>
    <tr>
      <td height="22" colspan="6"><table width="223" border="0" align="right" cellpadding="0" cellspacing="0">
        <tr>
          <td width="96" height="20"><div align="right">Total Ingreso </div></td>
          <td width="127"><input name="totspgi" type="text" id="totspgi" readonly value="<?php print number_format($ldec_monspi,2,',','.');?>" style="text-align:right"></td>
        </tr>
      </table></td>
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
          <input name="formatoanula" type="hidden" id="formatoanula" value="<?php print $ls_reporteanulado; ?>">
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
	<input name="codcon"     type="hidden" id="codcon" value="<?php print $l_codcon;?>">    
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
	   f.action="sigesp_scb_p_movbanco.php";
	   f.submit();
	 }
}

function ue_guardar()
{
  ls_operacion = f.cmboperacion.value;
  li_estcob=0;
  if (ls_operacion!='RE')
     {
	   ls_numordpagmin = f.txtnumordpagmin.value; 
	   if (ls_operacion=='DP' || ls_operacion=='NC')
	      {
		    ls_codtipfon = f.hidcodtipfon.value;
		  }
	 }
  else
     {
	   ls_numordpagmin = ""; 
	   ls_codtipfon    = "";
	 }

  uf_validar_estatus_mes();
  lb_mesabi = f.hidmesabi.value;
  if (lb_mesabi=='true')
     {
	   lb_valido 	= true;
	   li_totrowspi = f.totspi.value;
	   li_totrowspg = f.totpre.value;
	   li_totrowscg = f.totcon.value;	   
	   if (f.nocontabili.checked==true)
	      {
		    ls_spgcta = '';
		    ls_spicta = '';
	      }
	   else
	      {
		    ls_spgcta    = eval("f.txtcuenta1.value");
		    ls_spicta    = eval("f.txtcuentaspi1.value");
	      }
	   ls_tipvaldis    = "<?php echo $ls_tipvaldis; ?>";
	   ld_totmondis    = f.txtdisponible.value;
     }
  else
     {
	   lb_valido = false;
	   alert("Operación No puede ser procesada, El Més está Cerrado !!!");
	 }
  if (ls_numordpagmin!='' && ls_codtipfon!='' && (ls_operacion=='CH' || ls_operacion=='ND') && lb_valido)
     {
	   ld_montotmov = f.txtmonto.value;
	   ld_monmaxmov = f.hidmonmaxmov.value;
	   ld_montotmov = ue_formato_calculo(ld_montotmov);
	   if (ld_montotmov>ld_monmaxmov)
	      {
		    lb_valido = false;
			alert("Monto del Movimiento supera el Monto Disponible de la Orden de Pago Ministerio !!!");
		  }
	 }
  if (lb_valido)
     {
	   if ((li_totrowspg>=1 || li_totrowspi>=1) && (ls_spgcta!='' || ls_spicta!=''))
		   {
		    lb_valido = uf_evaluate_cierre('SPG');
		    if (lb_valido)
			   {
				 if (ls_spgcta!='')
				    {
					  ls_objeto = "txtmonto";
				    }
				 else if (ls_spicta!='')
				    {
					  ls_objeto = "txtmontospi";
				    }
				 if (li_estcob==0)
				 {
				 	lb_valido = uf_validar_monto_movimiento(ls_objeto);
			   	 }
			   }
		  }
	   else if (li_totrowscg>1 || f.hidestciespg.value==1)
		  {
		    lb_valido = uf_evaluate_cierre('SCG');
		  }
	 }  
  if (lb_valido)
     {
	   ls_status = f.estmov.value;
	   if ((ls_status!='C')&&(ls_status!='O')&&(ls_status!='A'))
	      {
		    ls_codban    = f.txtcodban.value;
		    ls_ctaban    = f.txtcuenta.value;		    
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
		    if (f.chkanula.checked==true)
			   {
				 lb_anula=true;
			   }
			else
			   {
			     lb_anula=false;
			   }
			if (f.nocontabili.checked==true)
			   {
			     ls_estmov="L";
			   }
			else
			   {
			     ls_estmov="N";
			   }
			if ((ls_operacion=="CH")||(ls_operacion=="ND"))
			   { 			
			     li_cobrapaga=f.ddlb_spg.value;			
			   }
		    else if((ls_operacion=="DP")||(ls_operacion=="NC"))
		       {
			     li_cobrapaga=f.ddlb_spi.value;
		       }
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
		    if (ls_operacion=="NC")
		       {
			     if (f.chkinteres.checked)
			        {
				      li_estint=1;
			        }
			     else
			        {
				      li_estint=0;
			        }
				 if (f.chkcobranza.checked)
			        {
				      li_estcob=1;
			        }
			     else
			        {
				      li_estcob=0;
			        }	
		       }
		    else
			   {
			     li_estint=0;
				 li_estcob=0;
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
		    if ((lb_valido)&&(ls_descripcion!="")&&(ls_codban!="")&&(ls_ctaban!="")&&(ls_documento!="")&&( ((ldec_monto==0)&&(ls_operacion=='CH'))||(lb_anula) ))
		       {
			     ls_status_doc = f.status_doc.value;
			     if (ls_status_doc != "C")
			        {
				      if (confirm("El Movimiento se registrara como Anulado,desea Continuar?"))
				         {
						   lb_valido = uf_validar_disponible(ls_operacion,ls_tipvaldis,ld_totmondis,f.txtmonto.value);
						   if (lb_valido)
						      {
							    f.operacion.value ="GUARDAR";
							    f.action="sigesp_scb_p_movbanco.php";
							    f.estmov.value='A';
							    f.submit();
							  }
						 }
			        }
			     else
			        {				
				      alert("El movimiento será actualizado, pero mantendrá su estatus original");
					  lb_valido = uf_validar_disponible(ls_operacion,ls_tipvaldis,ld_totmondis,f.txtmonto.value);
					  if (lb_valido)
						 {
						   f.operacion.value ="GUARDAR";
						   f.action="sigesp_scb_p_movbanco.php";
						   f.submit();			
			             }					
					}			
		       }
		    else if((lb_valido)&&(ls_provbene!="")&&(ls_descripcion!="")&&(ls_codban!="")&(ls_ctaban!="")&&(ls_documento!="")&&(ls_operacion!="")&&(ldec_monto>0))
		       {
			     lb_valido = uf_validar_disponible(ls_operacion,ls_tipvaldis,ld_totmondis,f.txtmonto.value);
				 if (lb_valido)
					{
					  f.operacion.value ="GUARDAR";
					  f.action="sigesp_scb_p_movbanco.php";
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

function ue_eliminar()
{
  lb_mesabi = f.hidmesabi.value;
  if (lb_mesabi=='true')
     {
	   lb_valido = true;
	   li_totrowspi = f.totspi.value;
	   li_totrowspg = f.totpre.value;
	   if (li_totrowspg>1 || li_totrowspi>1)
		  {
		    lb_valido = uf_evaluate_cierre('SPG');
		  }
	   else
		  {
		    li_totrowscg = f.totcon.value;
		    if (li_totrowscg>1)
			   {
			     lb_valido = uf_evaluate_cierre('SCG');
			   }	 
		  }
     }
  else
     {
	   lb_valido = false;
	   alert("Operación No puede ser procesada, El Més está Cerrado !!!");
	 }
  if (lb_valido)
     {
	   ls_status    = f.estmov.value;
	   ls_operacion = f.cmboperacion.value;
	   ls_numcarord = f.txtnumcarord.value;
	   ls_estcon    = f.estcon.value;
 	   if ((ls_operacion!="CH") && (ls_numcarord==""))
	      {
	        if ((ls_status!='C')&&(ls_status!='O')&&(ls_status!='A')&& (ls_estcon!='1'))
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
			     if (f.nocontabili.checked==true)
			        {
				      ls_estmov="L";
			        }
				 else
				    {
					  ls_estmov="N";
				    }
			     if ((ls_operacion=="CH")||(ls_operacion=="ND"))
					{			
					  li_cobrapaga=f.ddlb_spg.value;			
					}
				 else if((ls_operacion=="DP")||(ls_operacion=="NC"))
					{
					  li_cobrapaga=f.ddlb_spi.value;
					}
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
			     if (ls_operacion=="NC")
			        {
				      if (f.chkinteres.checked)
						 {
						   li_estint=1;
						 }
					  else
						 {
						   li_estint=0;
						 }
					  if (f.chkcobranza.checked)
						 {
						   li_estcob=1;
						 }
					  else
						 {
						   li_estcob=0;
						 }	 
			        }
				 else
				    {
					  li_estint=0;
					  li_estcob=0;
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
						   f.action="sigesp_scb_p_movbanco.php";
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

function ue_imprimir(ls_reporte)
{
	ls_numdoc=f.txtdocumento.value;
	ls_codope=f.cmboperacion.value;
	ls_reporteanulado=f.formatoanula.value;
	ls_status=f.estmov.value;
	ls_numcarord=f.txtnumcarord.value;
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
	
	if(ls_numcarord=="")
	{	
		if((ls_numdoc!="")&&(ls_codban!="")&&(ls_ctaban!="")&&(ls_codope!=""))
		{
			if(f.rb_voucher[0].checked)
			{
				ls_voucher="V";
			}
			else			
			{
				           if(f.rb_voucher[1].checked)
							{
								ls_voucher="A";
							}
							else			
							{
								ls_voucher="P";
							}
			
			}
			
							
			
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
			
			if(ls_status!="A")
			{
				ls_pagina="reportes/"+ls_reporte+"?codban="+ls_codban+"&ctaban="+ls_ctaban+"&numdoc="+ls_numdoc+"&chevau="+ls_chevau+"&codope="+ls_codope;
				window.open(ls_pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
			}
			else
			{
				ls_pagina="reportes/"+ls_reporteanulado+"?codban="+ls_codban+"&ctaban="+ls_ctaban+"&numdoc="+ls_numdoc+"&chevau="+ls_chevau+"&codope="+ls_codope;
				window.open(ls_pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
			}
		}
		else
		{
			if(ls_status=="A")
				alert("El documento está anulado y no puede ser impreso");
			else
				alert("Seleccione un documento valido, o que ya este registrado");
		}
	}
	else
	{
		f.operacion.value="PRINT_CARTAORDEN";
		f.submit();
	}
}

function ue_buscar()
{
	var x=document.body.clientWidth;
	var y=(document.body.clientHeight)-200;
	window.open("sigesp_cat_mov_bancario.php?opener=sigesp_scb_p_movbanco.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width="+x+",height="+y+",left=0,top=0,location=no,resizable=yes");
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
  valor=f.ddlb_conceptos.value; 
  if (uf_evaluate_cierre('SCG'))
     {
       ls_status = f.estmov.value;
       if ((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A'))
          {
		    ls_codban=f.txtcodban.value;
		    ls_nomban=f.txtdenban.value;
		    if ((ls_codban!=""))
		       {
			     pagina="sigesp_cat_ctabanco.php?codigo="+ls_codban+"&hidnomban="+ls_nomban+"&codcon="+valor;
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
  valor=f.ddlb_conceptos.value; 
  if (uf_evaluate_cierre('SCG'))
     {
       ls_status=f.estmov.value;
	   if ((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A'))
	      {
	   	    pagina="sigesp_cat_bancos.php?codcon="+valor;
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
   
function uf_verificar_operacion()
{
  ls_status = f.estmov.value;
  if ((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A'))
	 {
       f.operacion.value = "CAMBIO_OPERA";
	   f.opepre.value	 = f.cmboperacion.value;
	   f.submit();   
	 }
  else
	 {
	   alert("El Movimiento ya fue contabilizado o esta Anulado !!!");
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
	  if (f.nocontabili.checked==false)
	  {
	     if (uf_evaluate_cierre('SCG'))
		 {
		   ls_status=f.estmov.value;
		   if ((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A'))
			  {
				ls_operacion=f.cmboperacion.value;		
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
				if (f.nocontabili.checked==true)
				   {
					 ls_estmov="L";
				   }
				else
				   {
					 ls_estmov="N";
				   }
				if ((ls_operacion=="CH")||(ls_operacion=="ND"))
				   { 			
					 li_cobrapaga=f.ddlb_spg.value;			
				   }
				else if((ls_operacion=="DP")||(ls_operacion=="NC"))
				   {
					 li_cobrapaga=f.ddlb_spi.value;
				   }
				if (ls_operacion=="CH")
				   {
					 ls_chevau=f.txtchevau.value;
					 lb_valido=true;
				   }
				else
				   {
					 ls_chevau=" ";
					 lb_valido=true;
				   }
				if (ls_operacion=="NC")
				   {
					 if (f.chkinteres.checked)
						{
						  li_estint=1;
						}
					 else
						{
						  li_estint=0;
						}
					 if (f.chkcobranza.checked)
						{
						  li_estcob=1;
						}
					 else
						{
						  li_estcob=0;
						}	
				   }
				else
				   {
					 li_estint=0;
					 li_estcob=0;
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
				ls_anticipo=0;
				if ((lb_valido)&&(ls_provbene!="")&&(ls_descripcion!="")&&(ls_codban!="")&(ls_ctaban!="")&&(ls_documento!="")&&(ls_operacion!="")&&(ldec_monto>0))
				   {
					 ld_totmondis = f.txtdisponible.value;
					 ls_tipvaldis = "<?php echo $ls_tipvaldis; ?>";
					 lb_valido    = uf_validar_disponible(ls_operacion,ls_tipvaldis,ld_totmondis,f.txtmonto.value);
					 if (lb_valido)
						{
						  ls_codtipfon    = f.hidcodtipfon.value;
						  ls_numordpagmin = f.txtnumordpagmin.value;
						  ls_pagina = "sigesp_w_regdt_contable.php?txtprocedencia=SCBMOV&mov_document="+ls_documento+"&fecha="+ld_fecha+"&procede="+ls_procede+"&tipo="+ls_tipo+
									  "&provbene="+ls_provbene+"&descripcion="+ls_descripcion+"&codban="+ls_codban+"&ctaban="+ls_ctaban+"&cuenta_scg="+ls_cuenta_scg+
									  "&mov_operacion="+ls_operacion+"&monto="+ldec_monto+"&objret="+ldec_monobjret+"&retenido="+ldec_monret+"&chevau="+ls_chevau+
									  "&estint="+li_estint+"&cobrapaga="+li_cobrapaga+"&estbpd=M&txtnomproben="+ls_nomproben+"&estmov="+ls_estmov+"&codconmov="+ls_codconmov+
									  "&tip_mov= &opener=sigesp_scb_p_movbanco.php&estdoc="+ls_estdoc+"&codfuefin="+ls_codfuefin+
									  "&anticipo="+ls_anticipo+"&codtipfon="+ls_codtipfon+"&numordpagmin="+ls_numordpagmin+"&estcob="+li_estcob;
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
		    alert("La Operación es No Contabilizable!!!");
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
	  if (f.nocontabili.checked==false)
	  {
	     if (uf_evaluate_cierre('SPG'))
		 {
		   ls_status=f.estmov.value;
		   if ((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A'))
			  {		 
				ls_operacion=f.cmboperacion.value;
				ldec_totspg = f.totspg.value;	
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
				ldec_objret=f.txtmonobjret.value;
				ldec_monret=f.txtretenido.value;
				ls_codconmov=f.ddlb_conceptos.value;
				ls_codfuefin=f.txtftefinanciamiento.value;
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
				if ((ls_operacion=="CH")||(ls_operacion=="ND"))
				   {			
					 li_cobrapaga=f.ddlb_spg.value;
					 if (li_cobrapaga==1)
						{
						  ls_afectacion='PG';
						}
					 else
						{
						  ls_afectacion='CCP';
						}
				   }
				else if((ls_operacion=="DP")||(ls_operacion=="NC"))
				   {
					 li_cobrapaga=f.ddlb_spi.value;
					 if (li_cobrapaga==1)
						{
						  ls_afectacion='COB';
						}
					 else
						{
						  ls_afectacion='DC';
						}
				   }
				else
				   {
					 li_cobrapaga=0;
					 ls_afectacion='CCP';
				   }
				if (ls_operacion=="CH")
				   {
					 ls_chevau=f.txtchevau.value;
					 lb_valido=true;
				   }
				else
				   {
					 ls_chevau=" ";
					 lb_valido=true;
				   }
				if (f.nocontabili.checked==true)
				   {
					 ls_estmov="L";
				   }
				else
				   {
					 ls_estmov="N";
				   }
				if (ls_operacion=="NC")
				   {
					 if (f.chkinteres.checked)
						{
						  li_estint=1;
						}
					 else
						{
						  li_estint=0;
						}
					 if (f.chkcobranza.checked)
						{
						  li_estcob=1;
						  ls_afectacion='CCP';
						}
					 else
						{
						  li_estcob=0;
						}	
				   }
				else
				   {
					 li_estint=0;
					 li_estcob=0;
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
				ls_nomproben=f.txtdesproben.value;
				if ((lb_valido)&&(ls_provbene!="")&&(ls_descripcion!="")&&(ls_codban!="")&(ls_ctaban!="")&&(ls_documento!="")&&(ls_operacion!="")&&(ldec_monto>0))
				   {
					 if ((ls_operacion!="NC")&&(ls_operacion!="DP"))
						{
						  ld_totmondis = f.txtdisponible.value;
						  ls_tipvaldis = "<?php echo $ls_tipvaldis; ?>";
						  lb_valido    = uf_validar_disponible(ls_operacion,ls_tipvaldis,ld_totmondis,f.txtmonto.value);
						  if (lb_valido)
							 {
							   ls_codtipfon    = f.hidcodtipfon.value;
							   ls_numordpagmin = f.txtnumordpagmin.value;
							   ls_pagina = "sigesp_w_regdt_presupuesto.php?txtprocedencia=SCBMOV&mov_document="+ls_documento+"&fecha="+ld_fecha+"&procede="+ls_procede+
										  "&tipo="+ls_tipo+"&provbene="+ls_provbene+"&descripcion="+ls_descripcion+"&codban="+ls_codban+"&ctaban="+ls_ctaban+
										  "&cuenta_scg="+ls_cuenta_scg+"&mov_operacion="+ls_operacion+"&monto="+ldec_monto+"&objret="+ldec_objret+"&retenido="+ldec_monret+
										  "&chevau="+ls_chevau+"&estint="+li_estint+"&cobrapaga="+li_cobrapaga+"&afectacion="+ls_afectacion+"&estbpd=M&txtnomproben="+ls_nomproben+
										  "&estmov="+ls_estmov+"&codconmov="+ls_codconmov+"&tip_mov= &opener=sigesp_scb_p_movbanco.php&estdoc="+ls_estdoc+"&codfuefin="+ls_codfuefin+"&numordpagmin="+ls_numordpagmin+"&codtipfon="+ls_codtipfon+"&estcob="+li_estcob;
							   window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=no,width=650,height=350,left=50,top=50,location=no,resizable=yes,dependent=yes");
							 }
						}
					 if ((ls_operacion=="NC")&&(li_estcob=="1"))
						{
						  ld_totmondis = f.txtdisponible.value;
						  ls_tipvaldis = "<?php echo $ls_tipvaldis; ?>";
						  lb_valido    = uf_validar_disponible(ls_operacion,ls_tipvaldis,ld_totmondis,f.txtmonto.value);
						  if (lb_valido)
							 {
							   ls_codtipfon    = f.hidcodtipfon.value;
							   ls_numordpagmin = f.txtnumordpagmin.value;
							   ls_pagina = "sigesp_w_regdt_presupuesto.php?txtprocedencia=SCBMOV&mov_document="+ls_documento+"&fecha="+ld_fecha+"&procede="+ls_procede+
										  "&tipo="+ls_tipo+"&provbene="+ls_provbene+"&descripcion="+ls_descripcion+"&codban="+ls_codban+"&ctaban="+ls_ctaban+
										  "&cuenta_scg="+ls_cuenta_scg+"&mov_operacion="+ls_operacion+"&monto="+ldec_monto+"&objret="+ldec_objret+"&retenido="+ldec_monret+
										  "&chevau="+ls_chevau+"&estint="+li_estint+"&cobrapaga="+li_cobrapaga+"&afectacion="+ls_afectacion+"&estbpd=M&txtnomproben="+ls_nomproben+
										  "&estmov="+ls_estmov+"&codconmov="+ls_codconmov+"&tip_mov= &opener=sigesp_scb_p_movbanco.php&estdoc="+ls_estdoc+"&codfuefin="+ls_codfuefin+"&numordpagmin="+ls_numordpagmin+"&codtipfon="+ls_codtipfon+"&estcob="+li_estcob;
							   window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=no,width=650,height=350,left=50,top=50,location=no,resizable=yes,dependent=yes");
							 }
						}	
					 else
						{
						  alert("El Movimiento no puede registrar un gasto");			
						}
				   }
				else
				   {
					 alert("Complete los datos del Movimiento ");
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
		   alert("La Operación es No Contabilizable!!!");
	    } 	 
     }
  else
     {
	   alert("Operación No puede ser procesada, El Més está Cerrado !!!");
	 } 
}

function ue_det_ingreso()
{
  lb_mesabi = f.hidmesabi.value;

  if (lb_mesabi=='true')
  {
	 if (f.nocontabili.checked==false)
	 {
	     if (uf_evaluate_cierre('SPI'))
		 {
		   ls_status = f.estmov.value;
		   if ((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A'))
			  {		 
				ls_operacion=f.cmboperacion.value;		
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
				ldec_objret=f.txtmonobjret.value;
				ldec_monret=f.txtretenido.value;
				ls_codconmov=f.ddlb_conceptos.value;
				ls_codfuefin=f.txtftefinanciamiento.value;
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
				if((ls_operacion=="CH")||(ls_operacion=="ND"))
				{			
					li_cobrapaga=f.ddlb_spg.value;
					if(li_cobrapaga==1)
					{
						ls_afectacion='PG';
					}
					else
					{
						ls_afectacion='CCP';
					}
				}
				else if((ls_operacion=="DP")||(ls_operacion=="NC"))
				{
					li_cobrapaga=f.ddlb_spi.value;
					if(li_cobrapaga==1)
					{
						ls_afectacion='COB';
					}
					else
					{
						ls_afectacion='DC';
					}
				}
				else
				{
					li_cobrapaga=0;
					ls_afectacion='CCP';
				}
				if(ls_operacion=="CH")
				{
					ls_chevau=f.txtchevau.value;
					lb_valido=true;
				}
				else
				{
					ls_chevau=" ";
					lb_valido=true;
				}
				if(f.nocontabili.checked==true)
				{
					ls_estmov="L";
				}
				else
				{
					ls_estmov="N";
				}
				if(ls_operacion=="NC")
				{
					if(f.chkinteres.checked)
					{
						li_estint=1;
					}
					else
					{
						li_estint=0;
					}
					if(f.chkcobranza.checked)
					{
						li_estcob=1;
					}
					else
					{
						li_estcob=0;
					}
				}
				else
				{
					li_estint=0;
					li_estcob=0;
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
				if((lb_valido)&&(ls_provbene!="")&&(ls_descripcion!="")&&(ls_codban!="")&(ls_ctaban!="")&&(ls_documento!="")&&(ls_operacion!="")&&(ldec_monto>0))
				{
					if((ls_operacion=="NC")||(ls_operacion=="DP"))
					{
						ls_codtipfon   = f.txtcodtipfon.value;
						ls_numordpagmin = f.txtnumordpagmin.value;
						ls_pagina = "sigesp_w_regdt_ingreso.php?txtprocedencia=SCBMOV&mov_document="+ls_documento+"&fecha="+ld_fecha+"&procede="+ls_procede+"&tipo="+ls_tipo+"&provbene="+ls_provbene+"&descripcion="+ls_descripcion+"&codban="+ls_codban+"&ctaban="+ls_ctaban+"&cuenta_scg="+ls_cuenta_scg+"&mov_operacion="+ls_operacion+"&monto="+ldec_monto+"&objret="+ldec_objret+"&retenido="+ldec_monret+"&chevau="+ls_chevau+"&estint="+li_estint+"&cobrapaga="+li_cobrapaga+"&afectacion="+ls_afectacion+"&estbpd=M&txtnomproben="+ls_nomproben+"&estmov="+ls_estmov+"&codconmov="+ls_codconmov+"&tip_mov= &opener=sigesp_scb_p_movbanco.php&estdoc="+ls_estdoc+"&codfuefin="+ls_codfuefin+"&numordpagmin="+ls_numordpagmin+"&codtipfon="+ls_codtipfon+"&estcob="+li_estcob;
						window.open(ls_pagina,"Catalogo","dependent=yes,menubar=no,toolbar=no,scrollbars=yes,width=620,height=350,left=50,top=50,location=no,resizable=yes");
					}
					else
					{
						alert("El Movimiento no puede registrar un ingreso");			
					}
				}
				else
				{
					alert("Complete los datos del Movimiento ");
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
		   alert("La Operación es No Contabilizable!!!");
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
	  if (f.nocontabili.checked==false)
	  {
		 if (uf_evaluate_cierre('SCG'))
		 {
		   ls_status=f.estmov.value;
		   if ((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A'))
			  {
				ls_estdoc=f.status_doc.value;
				ldec_monobjret=f.txtmonobjret.value;
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
				ldec_objret=f.txtmonobjret.value;
				ldec_monret=f.txtretenido.value;
				ls_codconmov=f.ddlb_conceptos.value;
				ls_codfuefin=f.txtftefinanciamiento.value;
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
				
				if((ls_operacion=="CH")||(ls_operacion=="ND"))
				{			
					li_cobrapaga=f.ddlb_spg.value;			
				}
				else if((ls_operacion=="DP")||(ls_operacion=="NC"))
				{
					li_cobrapaga=f.ddlb_spi.value;
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
				if(f.nocontabili.checked==true)
				{
					ls_estmov="L";
				}
				else
				{
					ls_estmov="N";
				}
				if(ls_operacion=="NC")
				{
					if(f.chkinteres.checked)
					{
						li_estint=1;
					}
					else
					{
						li_estint=0;
					}
					if(f.chkcobranza.checked)
					{
						li_estcob=1;
					}
					else
					{
						li_estcob=0;
					}
				}
				else
				{
					li_estint=0;
					li_estcob=0;
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
					 if (ls_operacion=="CH")
						{
						  ld_totmondis = f.txtdisponible.value;
						  ls_tipvaldis = "<?php echo $ls_tipvaldis; ?>";
						  lb_valido    = uf_validar_disponible(ls_operacion,ls_tipvaldis,ld_totmondis,f.txtmonto.value);
						  if (lb_valido)
							 {
								ls_codtipfon    = f.txtcodtipfon.value;
								ls_numordpagmin = f.txtnumordpagmin.value;
								ls_pagina = "sigesp_w_regdt_deducciones.php?txtdocumento="+ls_documento+"&txtprocede=SCBMOV&mov_document="+ls_documento+"&fecha="+ld_fecha+
											"&procede="+ls_procede+"&tipo="+ls_tipo+"&provbene="+ls_provbene+"&descripcion="+ls_descripcion+"&codban="+ls_codban+
											"&ctaban="+ls_ctaban+"&cuenta_scg="+ls_cuenta_scg+"&mov_operacion="+ls_operacion+"&monto="+ldec_monto+"&objret="+ldec_objret+
											"&retenido="+ldec_monret+"&chevau="+ls_chevau+"&estint="+li_estint+"&cobrapaga="+li_cobrapaga+
											"&estbpd=M&txtnomproben="+ls_nomproben+"&estmov="+ls_estmov+"&codconmov="+ls_codconmov+
											"&tip_mov= &opener=sigesp_scb_p_movbanco.php&estdoc="+ls_estdoc+"&codfuefin="+ls_codfuefin+"&codtipfon="+ls_codtipfon+"&numordpagmin="+ls_numordpagmin+"&estcob="+li_estcob;
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
		   alert("La Operación es No Contabilizable!!!");
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
					 f.action="sigesp_scb_p_movbanco.php";
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
   
function uf_delete_Spg(row)
{
  lb_mesabi = f.hidmesabi.value;
  if (lb_mesabi=='true')
     {
	  if (uf_evaluate_cierre('SPG'))
		 {
		   ls_status = f.estmov.value;
		   if ((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A'))
			  {
				ls_cuenta      = eval("f.txtcuenta"+row+".value");
				ls_estprog     = eval("f.txtprogramatico"+row+".value");
				ls_documento   = eval("f.txtdocumento"+row+".value");
				ls_descripcion = eval("f.txtdescripcion"+row+".value");
				ls_procede     = eval("f.txtprocede"+row+".value");
				ls_operacion   = eval("f.txtoperacion"+row+".value");
				ldec_monto     = eval("f.txtmonto"+row+".value");
				ls_estcla      = eval("f.hidestcla"+row+".value");
				if ((ls_cuenta!="")&&(ls_estprog!="")&&(ls_documento!="")&&(ls_descripcion!="")&&(ls_procede!="")&&(ls_operacion!="")&&(ldec_monto!="")&&(ls_estcla!=""))
				   {
					 f.operacion.value="DELETESPG";
					 f.delete_spg.value=row;
					 f.action="sigesp_scb_p_movbanco.php";
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
   
function uf_delete_Spi(row)
{
  lb_mesabi = f.hidmesabi.value;
  if (lb_mesabi=='true')
     {
	  if (uf_evaluate_cierre('SPI'))
		 {
		   ls_status=f.estmov.value;
		   if ((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A'))
			  {
				ls_cuenta 	   = eval("f.txtcuentaspi"+row+".value");
				ls_descripcion = eval("f.txtdescspi"+row+".value");
				ls_procede	   = eval("f.txtprocspi"+row+".value");
				ls_documento   = eval("f.txtdocspi"+row+".value");
				ls_operacion   = eval("f.txtopespi"+row+".value");
				ldec_monto	   = eval("f.txtmontospi"+row+".value");
				if ((ls_cuenta!="")&&(ls_documento!="")&&(ls_descripcion!="")&&(ls_procede!="")&&(ls_operacion!="")&&(ldec_monto!=""))
				   {
					 f.operacion.value="DELETESPI";
					 f.delete_spi.value=row;
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
				f.action="sigesp_scb_p_movbanco.php";
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
   
function uf_selec_interes(obj)
{
	if(obj.checked==true)
	{
		f.estint.value=1;
	}
	else
	{
		f.estint.value=0;
	}
}

function uf_selec_cobranza(obj)
{
	if(obj.checked==true)
	{
		f.estcob.value=1;
	}
	else
	{
		f.estcob.value=0;
	}
}
   
function uf_verificar_retenido()
{
	ldec_monret=f.txtretenido.value;
	ldec_monret=uf_convertir_monto(ldec_monret);
	ldec_monobjret=f.txtmonobjret.value;
	ldec_monobjret=uf_convertir_monto(ldec_monobjret);
	ldec_monto=f.txtmonto.value;
	ldec_monto=uf_convertir_monto(ldec_monto);
	ldec_monto     = roundNumber(parseFloat(ldec_monto));
	ldec_monobjret = roundNumber(parseFloat(ldec_monobjret));

	if(ldec_monto>=ldec_monobjret)
	{
		if(ldec_monret>0)
		{
			f.txtmonobjret.readOnly=true;
			alert("Error no puede modificar el monto objeto a retención porque ya se realizaron retenciones en base al mismo");
		}			
	}
	else
	{
		alert("Monto Objeto a Retención no puede ser mayor al monto del movimiento !!!"+ldec_monto+"    "+ldec_monobjret);
		f.txtmonobjret.value=uf_convertir(ldec_monto);
	}		
}
   
function  uf_nocontabili()
{   
  if (f.estmov.value=='L')
  	{
		alert("El estatus no puede ser modificado nuevamente");
		f.nocontabili.checked=true;
	}
  if ((f.estmov.value=='C')||(f.estmov.value=='O')||(f.estmov.value=='A'))
  	{
		alert("El estatus no puede ser modificado nuevamente");
		f.nocontabili.checked=false;
	}
  else
    {
	  f.estmov.value='L';
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

function uf_cat_tipo_fondo()
{
  ls_status = f.estmov.value;
  ls_codope = "<?php echo $ls_mov_operacion; ?>";
  if (ls_codope=='DP' || ls_codope=='NC')
     {
	   ls_numordpagmin = f.txtnumordpagmin.value;
	   if (ls_numordpagmin!='')
	      {
		    if ((ls_status!='C')&&(ls_status!='O')&&(ls_status!='A'))
			   {
				 pagina="sigesp_scb_cat_tipofondo.php";
				 window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
			   }
		    else
			   {
				 alert("No puede realizar esta operacion el movimiento ya fue Contabilizado o Anulado");
			   }
		  }
	   else
	      {
		    alert("Debe Tipear el No. de Orden de Pago Ministerio !!!");
		    f.txtnumordpagmin.focus();
		  }
	 }
  else
     {
	   alert("Catálogo solamente habilitado para operaciones DP=Depósito y NC=Nota de Crédito !!!");	 
	 }
} 

function uf_chequear()
{
  ls_codope = "<?php echo $ls_mov_operacion; ?>";
  if (ls_codope=='DP' || ls_codope=='NC')
     {
	   ls_codtipfon    = f.txtcodtipfon.value;
	   ls_numordpagmin = f.txtnumordpagmin.value;
	   if (ls_numordpagmin=='' && ls_codtipfon!='')
		  {
		    f.txtcodtipfon.value = "";
		    f.txtdentipfon.value = "";
		    alert("Fué eliminado el enlace con el Tipo de Fondo !!!"); 
		  }
	 }
}

function uf_catalogo_ordenes()
{
  ls_status = f.estmov.value;
  ls_codope = "<?php echo $ls_mov_operacion; ?>";
  if (ls_codope=='CH' || ls_codope=='ND')
     {
       if ((ls_status!='C')&&(ls_status!='O')&&(ls_status!='A'))
		  {
			pagina="sigesp_scb_cat_ordenes_pago_ministerio.php";
		    window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=800,height=450,resizable=yes,location=no,dependent=yes");
		  }
	   else
		  {
		    alert("No puede realizar esta operacion el movimiento ya fue Contabilizado o Anulado");		
		  }
	 }
  else
     {
	   alert("Catálogo solamente habilitado para operaciones CH=Cheque y ND=Nota de Débito !!!");	 
	 }
}

function uf_validar_monto_movimiento(as_objeto)
{
  ld_montotmov = f.txtmonto.value;
  ld_sumdetmov = parseFloat(0);
  ld_montotmov = ue_formato_calculo(ld_montotmov);  
  if (as_objeto=='txtmonto')
     {
	   ls_tippre    = "Gasto";
	   li_totrowspg = f.totpre.value;
	   for (li_i=1;li_i<=li_totrowspg;li_i++)
		   {
			 ld_mondetmov = eval("f."+as_objeto+li_i+".value");
			 ld_mondetmov = ue_formato_calculo(ld_mondetmov);
			 ld_sumdetmov = eval(ld_sumdetmov+"+"+ld_mondetmov);
		   }
	 }
  else
     {
	   ls_tippre    = "Ingreso";
	   li_totrowspi = f.totspi.value;
	   for (li_i=1;li_i<=li_totrowspi;li_i++)
		   {
			 ld_mondetmov = eval("f."+as_objeto+li_i+".value");
			 ld_mondetmov = ue_formato_calculo(ld_mondetmov);
			 ld_sumdetmov = eval(ld_sumdetmov+"+"+ld_mondetmov);
		   }	 
	 }
  ld_sumdetmov= roundNumber(ld_sumdetmov);
  if (ld_montotmov!=ld_sumdetmov)
	 {
	   alert("Sumatoria de los Detalles Presupuestarios de "+ls_tippre+" No coincide con el Monto del Documento !!!");
	   return false;
	 }
  else
	 {
	   return true;
	 }
}
function roundNumber(obj)
{ 
	//var numberField = obj; // Field where the number appears 
	//alert(obj);
	var rnum = obj;
	var rlength = 2; // The number of decimal places to round to 
	var cantidad = parseFloat(obj);
	var decimales = parseFloat(rlength);
	decimales = (!decimales ? 2 : decimales);
return Math.round(cantidad * Math.pow(10, decimales)) / Math.pow(10, decimales);
}

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>