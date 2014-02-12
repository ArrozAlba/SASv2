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
$io_fun_banco->uf_load_seguridad("SCB","sigesp_scb_p_pago_directo.php",$ls_permisos,$la_seguridad,$la_permisos);
$ls_reporte   = $io_fun_banco->uf_select_config("SCB","REPORTE","CHEQUE_VOUCHER","sigesp_scb_rpp_voucher_pdf.php","C");//print $ls_reporte;

$ls_empresa       = $_SESSION["la_empresa"]["codemp"];
$li_estmodest     = $_SESSION["la_empresa"]["estmodest"];
$li_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
$li_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
$li_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
$li_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
$li_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
$li_estciespg     = $io_fun_banco->uf_load_estatus_cierre($li_estciespi,$li_estciescg);
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
<title>Pago Directo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
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
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
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
		ldec_diferencia=uf_convertir_monto(ldec_diferencia);
		ls_operacion=f.operacion.value;
		if((ldec_diferencia!=0)&&((ls_operacion=="")||(ls_operacion=="GUARDAR")))
		{
			alert("Comprobante descuadrado Contablemente");
			f.operacion.value="CARGAR_DT";
			f.action="sigesp_scb_p_pago_directo.php";
			f.submit();
		}
	}
</script>
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
  </tr>
  <tr>
    <td height="20" width="22" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" title="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Guardar" title="Guardar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" title="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript:ue_imprimir('<?php print $ls_reporte ?>');"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" title="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" title="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="646"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" title="Ayuda" width="20" height="20"></td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/grid_param.php");
	require_once("../shared/class_folder/ddlb_conceptos.php");
	require_once("../shared/class_folder/class_mensajes.php");	
    require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/ddlb_operaciones_spg.php");
	require_once("../shared/class_folder/ddlb_operaciones_spi.php");
	require_once("class_folder/sigesp_scb_c_disponibilidad_financiera.php");
	
	$io_msg		= new class_mensajes();	
	$fun		= new class_funciones();	
	$lb_guardar = true;
    $sig_inc	= new sigesp_include();
    $con		= $sig_inc->uf_conectar();
	$obj_spg	= new ddlb_operaciones_spg($con);
	$obj_spi	= new ddlb_operaciones_spi($con);
	$obj_con	= new ddlb_conceptos($con);
	$io_grid	= new grid_param();
	$io_disfin    = new sigesp_scb_c_disponibilidad_financiera("../");
    $ls_tipvaldis = $io_disfin->uf_load_tipo_validacion();


	require_once("sigesp_scb_c_movbanco.php");
	$in_classmovbco=new sigesp_scb_c_movbanco($la_seguridad);

	if( array_key_exists("operacion",$_POST))
	{
		$ls_operacion= $_POST["operacion"];
		$ls_mov_operacion=$_POST["cmboperacion"];
		$ls_numchequera   = $_POST["txtchequera"]; 
		
		if($ls_operacion=="CAMBIO_OPERA")
		{
			$ls_opepre="";	
			$ls_codconmov="";
		}
		else
		{
			if(($ls_mov_operacion=="CH")||($ls_mov_operacion=="ND"))
			{			
				$ls_opepre=$_POST["ddlb_spg"];	
			}
			elseif(($ls_mov_operacion=="DP")||($ls_mov_operacion=="NC"))
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
		$ls_estcon=$_POST["estcon"];
		$ls_docmov=$_POST["txtdocumento"];
		$ld_fecha=$_POST["txtfecha"];
		$ls_codban=$_POST["txtcodban"];
		$ls_denban=$_POST["txtdenban"];
		$ls_cuenta_banco=$_POST["txtcuenta"];
		$ls_dencuenta_banco=$_POST["txtdenominacion"];
		$ls_provbene=$_POST["txtprovbene"];
		$ls_desproben=$_POST["txtdesproben"];
		if (array_key_exists("rb_provbene",$_POST))
		   {
		     $ls_tipo = $_POST["rb_provbene"];
		   }
		else
		   {
		     $ls_tipo = '-';
		   }
		$lastspg = $_POST["lastspg"];
		$lastscg = $_POST["lastscg"];
		$ldec_mondeb=0;
		$ldec_monhab=0;
		$ldec_diferencia=0;
		$ldec_monspg=0;
		$ldec_monspi=0;
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
		if(($ls_codconmov=="---")||(!empty($ls_codconmov)))
		{
			if(($ls_codigoconcepto!="---")||(!empty($ls_codigoconcepto)))
			{
				$ls_codconmov=$ls_codigoconcepto;
			}
		}


		$ls_desmov=$_POST["txtconcepto"];
		$ls_cuenta_scg=$_POST["txtcuenta_scg"];
		$li_estint=$_POST["estint"];
		$ls_codfuefin=rtrim($_POST["txtftefinanciamiento"]);
		if($ls_codfuefin=="")
		{
			$ls_codfuefin="--";
		}
		$ls_denfuefin=rtrim($_POST["txtdenftefinanciamiento"]);
		if(array_key_exists("nocontabili",$_POST))
		{
			$lb_nocontab="checked";
			$ls_estmov='L';			
		}
		else
		{
			$lb_nocontab="";
			$ls_estmov=$_POST["estmov"];
		}
		$ls_estdoc=$_POST["status_doc"];
		$ls_chevau=$_POST["txtchevau"];
		$ls_estbpd='D';
		$ls_codtipfon    = $_POST["hidcodtipfon"];
		$ls_numordpagmin = $_POST["txtnumordpagmin"];
		$ld_monmaxmov    = $_POST["hidmonmaxmov"];
	}
	else
	{
		uf_nuevo();
		$ls_operacion= "NUEVO" ;
		$ls_estdoc="N";		
		$ls_numchequera = "";	
		$ls_chevau=$in_classmovbco->uf_generar_voucher($ls_empresa);		
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
		global $ls_estcon;
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
		global $ls_codban;
		global $ls_cuenta_banco;
		global $ls_mov_operacion;
		$in_classmovbco->uf_cargar_dt($ls_docmov,$ls_codban,$ls_cuenta_banco,$ls_mov_operacion,$ls_estmov,&$objectScg,&$li_row,&$ldec_mondeb,&$ldec_monhab,&$objectSpg,&$li_rows_spg,&$ldec_monspg,&$objectSpi,&$li_rows_spi,&$ldec_monspi,&$objectRet,&$li_rows_ret,&$ldec_montoret);
			
		$ldec_diferencia=$ldec_mondeb-$ldec_monhab;
	}
	
	function uf_nuevo()
	{
		global $ls_mov_operacion,$ls_codtipfon,$ls_numordpagmin,$ld_monmaxmov,$totalpre;
		$ls_mov_operacion="CH";
	    global $ls_opepre;
		$ls_opepre=0;	
		global $ls_docmov;
		$ls_docmov="";
		global $ls_numchequera;
		$ls_numchequera="";
		global $ls_codban;
		global $ls_estcon;
		$ls_estcon=0;
		$ls_codban="";
		global $ls_denban;
		$ls_denban="";
		global $ls_estmov;
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
		global $ldec_disponible;
		$ldec_disponible=0;
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
		$ls_codconmov='000';
		global $ls_desmov;
		$ls_desmov="";
		global $ls_cuenta_scg;
		$ls_cuenta_scg="";
		global $lb_nocontab;
		$lb_nocontab="";
		global $li_estint;
		$li_estint=0;
		global $ls_codconmov;
		$ls_codconmov='---';
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
		$ls_denfuefin=$ls_codtipfon=$ls_numordpagmin="";
		$ld_monmaxmov = $totalpre = 0;

		$li_row_scg=1;
		$objectScg[$li_row_scg][1] = "<input type=text name=txtcontable".$li_row_scg." id=txtcontable".$li_row_scg."  value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";		
		$objectScg[$li_row_scg][2] = "<input type=text name=txtdocscg".$li_row_scg."    value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
		$objectScg[$li_row_scg][3] = "<input type=text name=txtdesdoc".$li_row_scg."    value='' class=sin-borde readonly style=text-align:left size=35 maxlength=254>";
		$objectScg[$li_row_scg][4] = "<input type=text name=txtprocdoc".$li_row_scg."   value='' class=sin-borde readonly style=text-align:center size=8 maxlength=6>";
		$objectScg[$li_row_scg][5] = "<input type=text name=txtdebhab".$li_row_scg."    value='' class=sin-borde readonly style=text-align:center size=8 maxlength=1>"; 
		$objectScg[$li_row_scg][6] = "<input type=text name=txtmontocont".$li_row_scg." value='' class=sin-borde readonly style=text-align:right size=22 maxlength=22>";
		$objectScg[$li_row_scg][7] = "<input type=text name=txtcodded".$li_row_scg."    value='' class=sin-borde readonly style=text-align:right size=5 maxlength=5>";
		$objectScg[$li_row_scg][8] = "<a href=javascript:uf_delete_Scg('".$li_row_scg."');><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar detalle contable' width=15 height=15 border=0></a>";
		global $li_temp_spg;
		$li_temp_spg=1;
		$objectSpg[$li_temp_spg][1]  = "<input type=text name=txtcuenta".$li_temp_spg."       id=txtcuenta".$li_temp_spg."       value='' class=sin-borde readonly style=text-align:center size=10 maxlength=10 >";
		$objectSpg[$li_temp_spg][2]  = "<input type=text name=txtprogramatico".$li_temp_spg." id=txtprogramatico".$li_temp_spg." value='' class=sin-borde readonly style=text-align:center size=32 maxlength=29 >"; 
		$objectSpg[$li_temp_spg][3]  = "<input type=text name=txtdocumento".$li_temp_spg."    id=txtdocumento".$li_temp_spg."    value='' class=sin-borde readonly style=text-align:center size=10 maxlength=10>";
		$objectSpg[$li_temp_spg][4]  = "<input type=text name=txtdescripcion".$li_temp_spg."  id=txtdescripcion".$li_temp_spg."  value='' class=sin-borde readonly style=text-align:left>";
		$objectSpg[$li_temp_spg][5]  = "<input type=text name=txtprocede".$li_temp_spg."      id=txtprocede".$li_temp_spg."      value='' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
		$objectSpg[$li_temp_spg][6]  = "<input type=text name=txtoperacion".$li_temp_spg."    id=txtoperacion".$li_temp_spg."    value='' class=sin-borde readonly style=text-align:center size=5 maxlength=3>";
		$objectSpg[$li_temp_spg][7]  = "<input type=text name=txtmonto".$li_temp_spg."        id=txtmonto".$li_temp_spg."        value='' class=sin-borde readonly style=text-align:right>";		
		$objectSpg[$li_temp_spg][8]  = "<a href=javascript:uf_delete_Spg('".$li_temp_spg."');><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar detalle Presupuestario de Gasto' width=15 height=15 border=0></a>";	
		global $li_temp_spi;
		$li_temp_spi=1;
		$objectSpi[$li_temp_spi][1]  = "<input type=text name=txtcuentaspi".$li_temp_spi." value='' class=sin-borde readonly style=text-align:center size=6 maxlength=5>";
		$objectSpi[$li_temp_spi][2]  = "<input type=text name=txtdescspi".$li_temp_spi."   value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>"; 
		$objectSpi[$li_temp_spi][3]  = "<input type=text name=txtprocspi".$li_temp_spi."   value='' class=sin-borde readonly style=text-align:center size=32 maxlength=45>";
		$objectSpi[$li_temp_spi][4]  = "<input type=text name=txtdocspi".$li_temp_spi."    value='' class=sin-borde readonly style=text-align:center>";
		$objectSpi[$li_temp_spi][5]  = "<input type=text name=txtopespi".$li_temp_spi."    value='' class=sin-borde readonly style=text-align:center size=7 maxlength=6>";
		$objectSpi[$li_temp_spi][6]  = "<input type=text name=txtmontospi".$li_temp_spi."  value='' class=sin-borde readonly style=text-align:center size=5 maxlength=3>";
		$objectSpi[$li_temp_spi][7]  = "<a href=javascript:uf_delete_Spi('".$li_temp_spi."');><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar detalle Presupuestario de Ingreso' width=15 height=15 border=0></a>";	
	}

	$titleSpi=array('1'=>'Cuenta','2'=>'Descripción','3'=>'Procede','4'=>'Documento','5'=>'Operación','6'=>'Monto','7'=>'Edición');
	$title2=array('1'=>'Cuenta','2'=>'Documento','3'=>'Descripción','4'=>'Procede','5'=>'Debe/Haber','6'=>'Monto','7'=>'Deducción','8'=>'Edición');
	$title=array('1'=>'Cuenta', '2'=>'Programatico','3'=>'Documento','4'=>'Descripción','5'=>'Procede','6'=>'Operación','7'=>'Monto','8'=>'Edición');
	
	$gridSpi="grid_Spi";
	$grid2="gridscg";	
    $grid1="grid_SPG";	
	if($ls_operacion == "NUEVO")
	{
		$ls_operacion= "" ;
		uf_nuevo();
		$ldec_disponible="";
		$ls_chevau=$in_classmovbco->uf_generar_voucher($ls_empresa);
	}
	if($ls_operacion == "GUARDAR")
	{			
		$ls_provbene=$_POST["txtprovbene"];
		$ls_desproben=$_POST["txtdesproben"];
		$ls_tipo=$_POST["rb_provbene"];
		$ls_numchequera = $_POST["txtchequera"];
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
		$ls_chevau = str_pad($ls_chevau,25,0,0);
		$in_classmovbco->io_sql->begin_transaction();
		$lb_valido=$in_classmovbco->uf_guardar_automatico($ls_codban,$ls_cuenta_banco,$ls_docmov,$ls_mov_operacion,$ld_fecha,$ls_desmov,$ls_codconmov,$ls_codpro,$ls_cedbene,$ls_desproben,$ldec_montomov,$ldec_monobjret, $ldec_montoret,$ls_chevau,$ls_estmov,$li_estint,"$ls_opepre",$ls_estbpd,'SCBMOV',' ',$ls_estdoc,$ls_tipo,$ls_codfuefin,$ls_numordpagmin,$ls_codtipfon,0);
		if($lb_valido)
		{
		     $lb_valido=$in_classmovbco->uf_actualizar_estatus_ch($ls_codban,$ls_cuenta_banco,$ls_docmov,$ls_numchequera,1);
             if ($lb_valido)
			    {
				  $in_classmovbco->io_sql->commit();
				}
			 else
			    {
				  $in_classmovbco->io_sql->rollback();
				}		
		}
		else
		{
			$in_classmovbco->io_sql->rollback();					
		}
		$io_msg->message($in_classmovbco->is_msg_error);
		uf_cargar_dt();						
	}
	if($ls_operacion == "ELIMINAR")
	{
		$in_classmovbco->io_sql->begin_transaction();
		$lb_valido=$in_classmovbco->uf_delete_all_movimiento($ls_docmov,$ls_codban,$ls_cuenta_banco,$ls_mov_operacion,$ls_estmov);
		if($lb_valido)
		{
		     $lb_valido=$in_classmovbco->uf_actualizar_estatus_ch($ls_codban,$ls_cuenta_banco,$ls_docmov,$ls_numchequera,0);
             if ($lb_valido)
			    {
				  $in_classmovbco->io_sql->commit();
				}
			 else
			    {
				  $in_classmovbco->io_sql->rollback();
				}
		}	
		else
		{
			$in_classmovbco->io_sql->rollback();			
		}
		$io_msg->message($in_classmovbco->is_msg_error);
		uf_nuevo();
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
		$arr_movbco["codban"]=$ls_codban;
		$arr_movbco["ctaban"]=$ls_cuenta_banco;
		$arr_movbco["mov_document"]=$ls_docmov;
		$ld_fecdb=$fun->uf_convertirdatetobd($ld_fecha);
		$arr_movbco["codope"]=$ls_mov_operacion;
		$arr_movbco["fecha"]=$ld_fecha;
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
		$arr_movbco["estmov"]=$ls_estmov;
		$arr_movbco["retenido"] =$ldec_montoret;
		$in_classmovbco->io_sql->begin_transaction();
		$lb_valido=$in_classmovbco->uf_delete_dt_scg($ls_docmov,$ls_codban,$ls_cuenta_banco,$ls_mov_operacion,$ls_estmov,$ls_numdoc,$ls_cuentascg,$ls_debhab,$ls_codded,$ldec_montoscg,'SCG');
		$io_msg->message($in_classmovbco->is_msg_error);
		if($lb_valido)
		{
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
				$lb_valido=$in_classmovbco->uf_update_montodelete($arr_movbco,$ls_cuenta_scg,'SCBMOV',$ls_desmov,$ls_docmov,$ls_operacioncon,$ldec_monto,$ldec_monobjret,'00000');
				$ldec_montoret=$ldec_montoret-$ldec_monto;
			}
			$in_classmovbco->io_sql->commit();
		}
		else
		{
			$in_classmovbco->io_sql->rollback();
		}
		uf_cargar_dt();
	}
	if($ls_operacion=="DELETESPG")
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
		if ($li_estmodest==2)
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
	if($ls_operacion=="DELETERET")
	{
		$li_row_delete=$_POST["delete_ret"];
		$ls_codded=$_POST["txtdeduccion".$li_row_delete];
		$ls_cuenta=$_POST["txtcontable".$li_row_delete];
		$ls_debhab=$_POST["txtdebhab".$li_row_delete];
		$ls_numdoc=$_POST["txtdocscg".$li_row_delete];
		$ldec_monto=$_POST["txtmontoret".$li_row_delete];
		$ldec_monto=str_replace(".","",$ldec_monto);
		$ldec_monto=str_replace(",",".",$ldec_monto);
		$arr_movbco["codban"]=$ls_codban;
		$arr_movbco["ctaban"]=$ls_cuenta_banco;
		$arr_movbco["mov_document"]=$ls_docmov;
		$ld_fecdb=$fun->uf_convertirdatetobd($ld_fecha);
		$arr_movbco["codope"]=$ls_mov_operacion;
		$arr_movbco["fecha"]=$ld_fecha;
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
		$arr_movbco["estmov"]=$ls_estmov;
		$arr_movbco["retenido"] =$ldec_montoret;
		$in_classmovbco->io_sql->begin_transaction();
		$lb_valido=$in_classmovbco->uf_delete_dt_scg($ls_docmov,$ls_codban,$ls_cuenta_banco,$ls_mov_operacion,$ls_estmov,$ls_numdoc,$ls_cuenta,$ls_debhab,$ls_codded,$ldec_monto,'SCG');
		$io_msg->message($in_classmovbco->is_msg_error);
		if($lb_valido)
		{
			if(($ls_mov_operacion=="ND")||($ls_mov_operacion=="RE")||($ls_mov_operacion=="CH"))
			{
				$ls_operacioncon="H";
			}
			else
			{
				$ls_operacioncon="D";
			}
			$ldec_monto=str_replace(".","",$ldec_monto);
			$ldec_monto=str_replace(",",".",$ldec_monto);
			$lb_valido=$in_classmovbco->uf_update_montodelete($arr_movbco,$ls_cuenta_scg,'SCBMOV',$ls_desmov,$ls_docmov,$ls_operacioncon,$ldec_monto,$ldec_monobjret,'00000');
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
			$io_msg->message("Nº de Voucher ya existe, favor indicar otro");
			uf_cargar_dt();
			$ls_chevau="";
		}
		else
		{
			uf_cargar_dt();
			$ls_chevau=$ls_chevaux;
		}		
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
		if(($ls_operacion=="NUEVO"))
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

?>
  <form name="form1" method="post" action="" id="sigesp_scb_p_pago_directo.php">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_banco->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_banco);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <br>
  <table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-ventana">
      <td height="22" colspan="8"><input name="hidmesabi" type="hidden" id="hidmesabi" value="true">
      <input name="hidcodtipfon" type="hidden" id="hidcodtipfon" value="<?php echo $ls_codtipfon; ?>">
        <input name="hiddentipfon" type="hidden" id="hiddentipfon">
        <input name="hidmonmaxmov" type="hidden" id="hidmonmaxmov" value="<?php echo $ld_monmaxmov; ?>">
        Pago Directo      
      <input name="hidestciespg" type="hidden" id="hidestciespg" value="<?php echo $li_estciespg; ?>">
      <input name="hidestciespi" type="hidden" id="hidestciespi" value="<?php echo $li_estciespi; ?>"></td>
    </tr>
    <tr>
      <td colspan="8">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="2" style="text-align:right">Operaci&oacute;n</td>
      <td colspan="6" style="text-align:left"><input name="cmboperacion" type="text" id="cmboperacion" value="CH" readonly style="text-align:center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No. Orden Pago Ministerio
        <input name="txtnumordpagmin" type="text" id="txtnumordpagmin" onKeyPress="return keyRestrict(event,'0123456789'); " value="<?php echo $ls_numordpagmin; ?>" size="20" maxlength="15" style="text-align:center" readonly>
      &nbsp;<a href="javascript:uf_catalogo_ordenes();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar Ordenes de Pago Ministerio..." width="15" height="15" border="0" title="Buscar Ordenes de Pago Ministerio..."></a></td>
    </tr>
    <tr>
      <td height="22" colspan="2" style="text-align:right">Tipo Concepto</td>
      <td colspan="4" style="text-align:left"><?php $obj_con->uf_cargar_conceptos($ls_mov_operacion,$ls_codconmov);	?>
        <input name="codconmov" type="hidden" id="codconmov" value="<?php print $ls_codconmov;?>"></td>
      <td style="text-align:right">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="2" style="text-align:right">Banco</td>
      <td colspan="4" style="text-align:left"><input name="txtcodban" type="text" id="txtcodban"  style="text-align:center" value="<?php print $ls_codban;?>" size="10" readonly>
      <a href="javascript:cat_bancos();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Bancos"></a>
      <input name="txtdenban" type="text" id="txtdenban" value="<?php print $ls_denban?>" size="51" class="sin-borde" readonly>      </td>
      <td style="text-align:right"><strong>Disponible</strong></td>
      <td><input name="txtdisponible" type="text" id="txtdisponible" style="text-align:right" value="<?php print $ldec_disponible;?>" size="24" readonly></td>
    </tr>
    <tr>
      <td height="22" colspan="2" style="text-align:right">Cuenta</td>
      <td colspan="4"><input name="txtcuenta" type="text" id="txtcuenta" style="text-align:center" value="<?php print $ls_cuenta_banco; ?>" size="30" maxlength="25" readonly>
      <a href="javascript:catalogo_cuentabanco();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Cuentas Bancarias"></a>
      <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion" style="text-align:left" value="<?php print $ls_dencuenta_banco; ?>" size="35" maxlength="254" readonly>
      <input name="txttipocuenta" type="hidden" id="txttipocuenta">
      <input name="txtdentipocuenta" type="hidden" id="txtdentipocuenta">      </td>
      <td style="text-align:right">Contable</td>
      <td><input name="txtcuenta_scg" type="text" id="txtcuenta_scg2" style="text-align:center" value="<?php print $ls_cuenta_scg;?>" size="24" readonly></td>
    </tr>
    <tr>
      <td width="103" height="22" colspan="2" style="text-align:right">Documento</td>
      <td colspan="4"><div align="left">
          <input name="txtdocumento" type="text" id="txtdocumento" style="text-align:center" onBlur="rellenar_cad(this.value,15,'doc')" value="<?php print $ls_docmov;?>" size="24" maxlength="15" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+'-()[]{}#/');">
          <a href="javascript:cat_cheque();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo de Cheques" width="15" height="15" border="0"></a>
          <input name="estmov" type="hidden" id="estmov" value="<?php print $ls_estmov;?>">
		  <input name="estcon" type="hidden" id="estcon" value="<?php print $ls_estcon;?>">
          <input name="txtchequera" type="hidden" id="txtchequera" value="<?php print $ls_numchequera;?>">
</div></td>
      <td width="83" style="text-align:right">Fecha</td>
      <td width="168" style="text-align:left"><input name="txtfecha" type="text" id="txtfecha"  style="text-align:center" value="<?php print $ld_fecha;?>" size="24" maxlength="10" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" datepicker="true"></td>
    </tr>
	  <script language="javascript">uf_validar_estatus_mes();</script>
	<tr>
      <td height="22" style="text-align:right">&nbsp;</td>
      <td style="text-align:right"><?php if($ls_mov_operacion!="RE")print "Afectaci&oacute;n"; ?></td>
      <td colspan="2" style="text-align:left"><?php  if(($ls_mov_operacion=='ND')||($ls_mov_operacion=='CH'))
				{
					$obj_spg->uf_cargar_ddlb_spg(0,$ls_opepre,$ls_mov_operacion); 	
				}
				elseif(($ls_mov_operacion=='DP')||($ls_mov_operacion=='NC'))
				{
					$obj_spi->uf_cargar_ddlb_spi(0,$ls_opepre,$ls_mov_operacion); 
				}				
				?>
      <input name="opepre" type="hidden" id="opepre2" value="<?php print $ls_opepre;?>"></td>
      <td width="58">&nbsp;</td>
      <td width="125">&nbsp;</td>
      <td><div align="right"><?php if($ls_mov_operacion=="CH")
								{
									print "Voucher";
								}
								?>
	  </div></td>
      <td style="text-align:left"><?php if($ls_mov_operacion=="CH")
								{?>
								  <input name="txtchevau" type="text" id="txtchevau" value="<?php print $ls_chevau ?>" style="text-align:center" size="28" maxlength="25" onChange="javascript:ue_verificar_vaucher();" onBlur="javascript:rellenar_cad(this.value,25,'voucher');" onKeyPress="return keyRestrict(event,'0123456789'); "></td>
								<?php
								}
								?>
    </tr>    
    <tr>
      <td height="22" colspan="2" style="text-align:right">Concepto</td>
      <td colspan="6" style="text-align:left"><input name="txtconcepto" type="text" id="txtconcepto" value="<?php print $ls_desmov;?>" size="120"  onKeyPress="return keyRestrict(event,'0123456789'+'abcdefghijklmnopqrstuvwxyzñ .,*/-()$%&!ºªáéíóú[]{}<>')"></td>
    </tr>
    <tr>
      <td height="22" colspan="2" style="text-align:right">Tipo Destino</td>
      <td colspan="2">
	  	   <table width="231" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
            <tr>
              <td width="229"><label>
                <input name="rb_provbene" type="radio" class="sin-borde" id="radio" onClick="javascript:uf_verificar_provbene(this.checked,document.form1.tipo.value);" value="P" checked <?php print $rb_p; ?>>
Proveedor</label>
                <label>
                <input type="radio" name="rb_provbene" id="radio" value="B" class="sin-borde" onClick="javascript:uf_verificar_provbene(this.checked,document.form1.tipo.value);" <?php print $rb_b;?>>
Beneficiario</label>
                <label>                </label>
                <input name="tipo" type="hidden" id="tipo"></td>
            </tr>
          </table>      </td>
      <td colspan="4"><input name="txtprovbene" type="text" id="txtprovbene2" style="text-align:center" value="<?php print $ls_provbene?>" size="24" readonly>
      <a href="javascript:catprovbene()"><img id="bot_provbene" src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo Proveedores/Beneficiarios" width="15" height="15" border="0"></a>
      <input name="txtdesproben" type="text" id="txtdesproben" size="42" maxlength="250" class="sin-borde" value="<?php print $ls_desproben;?>"  readonly>
      <input name="txttitprovbene" type="hidden" class="sin-borde" id="txttitprovbene" style="text-align:right" size="15" readonly></td>
    </tr>
    <tr>
      <td height="22" colspan="2" style="text-align:right">Monto</td>
      <td width="183"><span style="text-align:left">
        <input name="txtmonto" type="text" id="txtmonto" style="text-align:right" onBlur="javascript:uf_format(this);uf_montoobjret(this);uf_verificar_monto(this);" onKeyPress="return(currencyFormat(this,'.',',',event));" value="<?php print number_format($ldec_montomov,2,",",".");?>" size="24">
      </span></td>
      <td width="50" style="text-align:right">M.O.R</td>
      <td colspan="2"><input name="txtmonobjret" type="text" id="txtmonobjret" style="text-align:right" onBlur="javascript:validar_monto();javascript:uf_format(this);" onKeyPress="return(currencyFormat(this,'.',',',event));" value="<?php print  number_format($ldec_monobjret,2,",",".");?>" size="22"></td>
      <td style="text-align:right">Monto Retenido</td>
      <td><span style="text-align:left">
        <input name="txtretenido" type="text" id="txtretenido2" style="text-align:right" value="<?php print number_format($ldec_montoret,2,",",".");?>" size="24" readonly>
      </span></td>
    </tr>
    <tr>
      <td height="22" colspan="2"><div align="right">Fte. Financiamiento</div></td>
      <td colspan="6"><input name="txtftefinanciamiento" type="text" id="txtftefinanciamiento" style="text-align:center" value="<?php print $ls_codfuefin;?>" size="3" maxlength="2" readonly>
        <a href="javascript: uf_cat_fte_financia();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo Fuente de Financiamiento" width="15" height="15" border="0"></a>
        <input name="txtdenftefinanciamiento" type="text" class="sin-borde" id="txtdenftefinanciamiento" value="<?php print $ls_denfuefin;?>" size="90" readonly></td>
    </tr>
    <tr>
      <td height="22" colspan="2"><div align="right">
          		<?php
				if($ls_mov_operacion=="NC")
				{
				?>
          			<input name="txtint" id="txtint" type="text" value="Interes" class="sin-borde" size="10" style="text-align:right" readonly>
         		<?php	
				}	
				?>
      </div></td>
      <td colspan="4"><div align="left">
	            <?php
				if($ls_mov_operacion=="NC")
				{
				?>
          			<input name="chkinteres" type="checkbox" id="chkinteres" value="1" style="width:15px; height:15px" onClick="uf_selec_interes(this);" <?php print $lb_checked;?>>
         		<?php	
				}	
				?>
                <input name="estint" type="hidden" id="estint" value="<?php print $li_estint;?>">
</div></td>
      <td style="text-align:right"><input name="nocontabili" type="checkbox" class="sin-borde" id="nocontabili" style="width:15px; height:15px" value="checkbox" <?php print $lb_nocontab; ?>></td>
      <td>No Contabilizable </td>
    </tr>
    <tr>
      <td height="13" colspan="8">&nbsp;</td>
    </tr>
    <tr>
      <td height="15" colspan="8"><table width="292" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td width="290"><div align="center"><a href="#01">Ir a Detalle Contable/ Retenciones </a></div></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td height="13" colspan="2"><div align="right"> </div> <a href="#01"> </a></td>
      <td height="13" colspan="4">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="8">&nbsp;&nbsp;<a href="javascript: uf_agregar_dtpre();"><img src="../shared/imagebank/tools/nuevo.gif" width="15" height="15" border="0">Agregar detalle Presupuesto</a> </td>
    </tr>
    <tr>
      <td height="22" colspan="8"><div align="center"><?php $io_grid->makegrid($li_rows_spg,$title,$objectSpg,770,'Detalles Presupuestarios',$grid1);?>
              <input name="totpre"  type="hidden" id="totpre"  value="<?php print $totalpre?>">
              <input name="lastspg" type="hidden" id="lastspg" value="<?php print $lastspg;?>">
              <input name="delete_spg" type="hidden" id="delete_spg">
      </div></td>
    </tr>
    <tr>
      <td height="22" colspan="8"><table width="223" border="0" align="right" cellpadding="0" cellspacing="0">
          <tr>
            <td width="96" height="20"><div align="right">Total Presupuesto </div></td>
            <td width="127"><input name="totspg" type="text" id="totspg" readonly value="<?php print number_format($ldec_monspg,2,',','.');?>" style="text-align:right"></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td height="17" colspan="8">&nbsp;</td>
    </tr>
    <tr>
      <td height="17" colspan="8">&nbsp;&nbsp;<a href="javascript: uf_agregar_dtcon();"><img src="../shared/imagebank/tools/nuevo.gif" width="15" height="15" border="0">Agregar detalle Contable </a><a href="javascript: uf_agregar_dtret('$ls_mov_operacion');"><img src="../shared/imagebank/tools/nuevo.gif" width="15" height="15" border="0">Agregar detalle Retenciones</a> </td>
    </tr>
    <tr>
      <td height="19" colspan="8"><div align="center"><a name="01" id="01"></a>
        <?php $io_grid->makegrid($li_row,$title2,$objectScg,770,'Detalles Contable',$grid2);?>
        
          <input name="totcon"  type="hidden" id="totcon"  size=5 value="<?php print $totalcon?>">
          <input name="lastscg" type="hidden" id="lastscg" size=5 value="<?php print $lastscg;?>">
          <input name="delete_scg" type="hidden" id="delete_scg" size=5>
      </div></td>
    </tr>
    <tr>
      <td height="22" colspan="8"><table width="210" border="0" align="right" cellpadding="0" cellspacing="0">
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
      <td height="22" colspan="8"><div align="center"><a href="#00">Volver Arriba</a> </div></td>
    </tr>
  </table>
  <p><input name="operacion" type="hidden" id="operacion">
    <input name="status_doc" type="hidden" id="status_doc" value="<?php print $ls_estdoc;?>">
  </p>
  </form>
</body>
<script language="javascript">
f=document.form1;
var patron = new Array(2,2,4);
function ue_nuevo()
{
  if (uf_evaluate_cierre('SPG'))
     {
       f.operacion.value ="NUEVO";
       f.action="sigesp_scb_p_pago_directo.php";
       f.submit();
	 } 
}

function ue_guardar()
{
  lb_mesabi = f.hidmesabi.value;
  if (lb_mesabi=='true')
     {
		if (uf_evaluate_cierre('SPG'))
		   {
			ls_status=f.estmov.value;
			if((ls_status!='C')&&(ls_status!='O')&&(ls_status!='A'))
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
					lb_valido=true;
				}
				if(ls_operacion=="ND")
				{
					if(f.chkinteres.checked)
					{
						li_estint=1;
					}
					else
					{
						li_estint=0;
					}
				}
				else
				{
					li_estint=0;
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
				if((lb_valido)&&(ls_provbene!="")&&(ls_descripcion!="")&&(ls_codban!="")&(ls_ctaban!="")&&(ls_documento!="")&&(ls_operacion!=""))
				{
					lb_valido = uf_validar_monto_movimiento();
					if (lb_valido)
					   {
						 ld_totmondis = f.txtdisponible.value;
						 ls_tipvaldis = "<?php echo $ls_tipvaldis; ?>";
						 lb_valido    = uf_validar_disponible("CH",ls_tipvaldis,ld_totmondis,f.txtmonto.value);
						 if (lb_valido)
							{
							  f.operacion.value ="GUARDAR";
							  f.action="sigesp_scb_p_pago_directo.php";
							  f.submit();
							}
					   }
				}
				else
				{
					alert("No ha completado los datos ");
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
	  if (uf_evaluate_cierre('SPG'))
		 {
		   ls_status=f.estmov.value;
		   ls_estcon=f.estcon.value;
		   if ((ls_status!='C')&&(ls_status!='O')&&(ls_status!='A')&& (ls_estcon!='1'))
			  {
				if (confirm("Esta seguro de Eliminar el Documento?,\n esta operación no se puede deshacer." ))
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
						  ls_chevau=" ";
						  lb_valido=true;
						}
					 if (ls_operacion=="ND")
						{
						  if (f.chkinteres.checked)
							 {
							   li_estint=1;
							 }
						  else
							 {
							   li_estint=0;
							 }
						}
					 else
						{
						  li_estint=0;
						}
					 if (f.rb_provbene[0].checked)
						{
						  ls_tipo="P";
						}
					 if (f.rb_provbene[1].checked)
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
					 if ((lb_valido)&&(ls_provbene!="")&&(ls_descripcion!="")&&(ls_codban!="")&(ls_ctaban!="")&&(ls_documento!="")&&(ls_operacion!=""))
						{
						  f.operacion.value ="ELIMINAR";
						  f.action="sigesp_scb_p_pago_directo.php";
						  f.submit();
						}
					 else
						{
						  alert("No ha completado los datos");
						}
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
					 alert("No puede eliminar el movimiento, ya fue Anulado");
				   }		
				else
				   {
					 alert("No puede eliminar el movimiento, ya fue Conciliado");
				   }
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
  window.open("sigesp_cat_mov_bancario.php?opener=sigesp_scb_p_pago_directo.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=650,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_imprimir(ls_reporte)
{
	ls_numdoc=f.txtdocumento.value;
	ls_codope='CH';
	ls_chevau=f.txtchevau.value;
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
	
function catalogo_cuentabanco()
{
   valor=f.ddlb_conceptos.value;
  if (uf_evaluate_cierre('SPG'))
     {
	   ls_codban=f.txtcodban.value;
	   ls_nomban=f.txtdenban.value;
	   ls_status=f.estmov.value;
	   if ((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A'))
	      {
	  	    if ((ls_codban!=""))
		       {
			     pagina="sigesp_cat_ctabanco.php?codigo="+ls_codban+"&hidnomban="+ls_nomban+"&codcon="+valor;
			     window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=650,height=400,resizable=yes,location=no");
		       }
		    else
		       {
			     alert("Seleccione el Banco");   
		       }
	      }
	   else
		  {
		    alert("El Movimiento ya fue Contabilizado o esta Anulado");   
		  }
	 }
}	 

function catalogo_cuentascg()
{
  if (uf_evaluate_cierre('SPG'))
     {
       ls_status=f.estmov.value;
       if ((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A')) 
          {
			pagina="sigesp_cat_filt_scg.php?filtro="+'11102'+"&opener=sigesp_scb_d_colocacion.php";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
          }
       else
          {
	        alert("El Movimiento ya fue Contabilizado o esta Anulado");   
          }
     }
}
	 	 
function cat_bancos()
{
  valor=f.ddlb_conceptos.value;  
  if (uf_evaluate_cierre('SPG'))
     {
       ls_status=f.estmov.value;
       if ((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A'))
	      {
	   	    pagina="sigesp_cat_bancos.php?codcon="+valor;
	   	    window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
	      }
	   else
	      {
		    alert("El Movimiento ya fue Contabilizado o esta Anulado");   
	      }	 
	 }
}

function cat_conceptos()
{
  if (uf_evaluate_cierre('SPG'))
     {
       ls_status=f.estmov.value;
	   if ((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A'))
		  { 
		    pagina="sigesp_cat_conceptos.php";
	   		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
		  }
	   else
		  {
		    alert("El Movimiento ya fue Contabilizado o esta Anulado");   
		  }
	 }
}
	
function currencyDate(date)
{ 
ls_date=date.value;
li_long=ls_date.length;
		 
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
   
function uf_verificar_operacion()
{
	ls_status=f.estmov.value;
	if((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A'))
	{
		f.operacion.value="CAMBIO_OPERA";
		f.opepre.value=f.cmboperacion.value;
		f.submit();   
	}	
	else
	{
		alert("El Movimiento ya fue contabilizado o esta Anulado");
	}
}
   
function catprovbene()
{
  if (uf_evaluate_cierre('SPG'))
     {
	   ls_status=f.estmov.value;
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
	  if (uf_evaluate_cierre('SPG'))
		 {
			ls_status=f.estmov.value;
			if((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A'))
			{
				ls_estdoc=f.status_doc.value;
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
				ls_codconmov=f.ddlb_conceptos.value;
				ls_codfuefin=f.txtftefinanciamiento.value;
				if(f.nocontabili.checked==true)
				{
					ls_estmov="L";
				}
				else
				{
					ls_estmov="N";
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
					lb_valido=true;
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
				}
				else
				{
					li_estint=0;
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
				  ld_totmondis = f.txtdisponible.value;
				  ls_tipvaldis = "<?php echo $ls_tipvaldis; ?>";
				  lb_valido    = uf_validar_disponible("CH",ls_tipvaldis,ld_totmondis,f.txtmonto.value);
				  if (lb_valido)
					 {
					   ls_pagina = "sigesp_w_regdt_contable.php?txtprocedencia=SCBMOV&mov_document="+ls_documento+"&fecha="+ld_fecha+"&procede="+ls_procede+
								   "&tipo="+ls_tipo+"&provbene="+ls_provbene+"&descripcion="+ls_descripcion+"&codban="+ls_codban+"&ctaban="+ls_ctaban+
								   "&cuenta_scg="+ls_cuenta_scg+"&mov_operacion="+ls_operacion+"&monto="+ldec_monto+"&objret="+ldec_objret+"&retenido="+ldec_monret+
								   "&chevau="+ls_chevau+"&estint="+li_estint+"&cobrapaga="+li_cobrapaga+"&estbpd=D&txtnomproben="+ls_nomproben+"&estmov="+ls_estmov+
								   "&codconmov="+ls_codconmov+"&tip_mov= &opener=sigesp_scb_p_pago_directo.php&estdoc="+ls_estdoc+"&codfuefin="+ls_codfuefin;
					   window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=no,width=570,height=182,left=50,top=50,location=no,resizable=no,dependent=yes");
					 }
				}
				else
				{
					alert("Complete los datos del Movimiento");
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
   
function  uf_agregar_dtpre()
{
  lb_mesabi = f.hidmesabi.value;
  if (lb_mesabi=='true')
     {
	  if (uf_evaluate_cierre('SPG'))
		 {
			ls_status=f.estmov.value;
			if((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A'))
			{	
				ls_estdoc=f.status_doc.value;
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
				}
				else
				{
					li_estint=0;
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
				ls_nomproben=f.txtdesproben.value;
				if((lb_valido)&&(ls_provbene!="")&&(ls_descripcion!="")&&(ls_codban!="")&(ls_ctaban!="")&&(ls_documento!="")&&(ls_operacion!="")&&(ldec_monto>0))
				{
					if((ls_operacion!="NC")&&(ls_operacion!="DP"))
					{
					  ld_totmondis = f.txtdisponible.value;
					  ls_tipvaldis = "<?php echo $ls_tipvaldis; ?>";
					  lb_valido    = uf_validar_disponible("CH",ls_tipvaldis,ld_totmondis,f.txtmonto.value);
					  if (lb_valido)
						 {
						   ls_codtipfon    = f.hidcodtipfon.value;
						   ls_numordpagmin = f.txtnumordpagmin.value;
						   ls_pagina = "sigesp_w_regdt_presupuesto.php?txtprocedencia=SCBMOV&mov_document="+ls_documento+"&fecha="+ld_fecha+"&procede="+ls_procede+
									   "&tipo="+ls_tipo+"&provbene="+ls_provbene+"&descripcion="+ls_descripcion+"&codban="+ls_codban+"&ctaban="+ls_ctaban+
									   "&cuenta_scg="+ls_cuenta_scg+"&mov_operacion="+ls_operacion+"&monto="+ldec_monto+"&objret="+ldec_objret+"&retenido="+ldec_monret+
									   "&chevau="+ls_chevau+"&estint="+li_estint+"&cobrapaga="+li_cobrapaga+"&afectacion="+ls_afectacion+
									   "&estbpd=D&txtnomproben="+ls_nomproben+"&estmov="+ls_estmov+"&codconmov="+ls_codconmov+
									   "&tip_mov= &opener=sigesp_scb_p_pago_directo.php&estdoc="+ls_estdoc+"&codfuefin="+ls_codfuefin+"&numordpagmin="+ls_numordpagmin+"&codtipfon="+ls_codtipfon+"&estcob=0";
						   window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=no,width=580,height=300,left=50,top=50,location=no,resizable=yes,dependent=yes");
						 }
	
					}
					else
					{
						alert("Movimiento no puede registrar un gasto");			
					}
				}
				else
				{
					alert("Complete los datos del Movimiento");
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

function  uf_agregar_dtret(operacion)
{
  lb_mesabi = f.hidmesabi.value;
  if (lb_mesabi=='true')
     {
	  if (uf_evaluate_cierre('SPG'))
		 {
			f=document.form1;
			ls_status=f.estmov.value;
			if((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A'))
			{
				ls_estdoc=f.status_doc.value;
				ldec_monobjret=f.txtmonobjret.value;
				ls_documento=f.txtdocumento.value;
				ls_procede="SCBMOV";
				 f=document.form1;
				ls_operacion=f.cmboperacion.value;		
				li_lastscg=f.lastscg.value;
				li_newrow=parseInt(li_lastscg,10)+1;
				ls_cuenta_scg=f.txtcuenta_scg.value;
				ls_descripcion=f.txtconcepto.value;
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
				}
				else
				{
					li_estint=0;
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
				ls_nomproben=f.txtdesproben.value;
				
				if((ls_provbene!="")&&(ls_descripcion!="")&&(ls_codban!="")&(ls_ctaban!="")&&(ls_documento!="")&&(ls_operacion!="")&&(ldec_monto>0)&&(ldec_objret>0))
				{
					if(ls_operacion=="CH")
					{
					  ld_totmondis = f.txtdisponible.value;
					  ls_tipvaldis = "<?php echo $ls_tipvaldis; ?>";
					  lb_valido    = uf_validar_disponible("CH",ls_tipvaldis,ld_totmondis,f.txtmonto.value);
					  if (lb_valido)
						 {
						   ls_pagina = "sigesp_w_regdt_deducciones.php?objret="+ldec_monobjret+"&txtdocumento="+ls_documento+"&txtprocede=SCBMOV&mov_document="+ls_documento+
									   "&fecha="+ld_fecha+"&procede="+ls_procede+"&tipo="+ls_tipo+"&provbene="+ls_provbene+"&descripcion="+ls_descripcion+"&codban="+ls_codban+
									   "&ctaban="+ls_ctaban+"&cuenta_scg="+ls_cuenta_scg+"&mov_operacion="+ls_operacion+"&monto="+ldec_monto+"&objret="+ldec_objret+
									   "&retenido="+ldec_monret+"&chevau="+ls_chevau+"&estint="+li_estint+"&cobrapaga="+li_cobrapaga+"&estbpd=D&txtnomproben="+ls_nomproben+
									   "&estmov="+ls_estmov+"&codconmov="+ls_codconmov+"&tip_mov= &opener=sigesp_scb_p_pago_directo.php&estdoc="+ls_estdoc+"&codfuefin="+ls_codfuefin;
						   window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=570,height=350,left=50,top=50,location=no,resizable=no,dependent=yes");
						 }
					}
					else
					{
						alert("Movimiento no aplican retenciones");
					}
				}
				else
				{
					alert("Complete los datos del Movimiento");
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
				ls_procede=eval("f.txtprocdoc"+row+".value");
				ls_debhab=eval("f.txtdebhab"+row+".value");
				ldec_montocont=eval("f.txtmontocont"+row+".value");
				if ((ls_cuenta!="")&&(ls_documento!="")&&(ls_descripcion!="")&&(ls_procede!="")&&(ls_debhab!=""))
				   {
					 f.operacion.value="DELETESCG";
					 f.delete_scg.value=row;
					 f.action="sigesp_scb_p_pago_directo.php";
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
				ls_cuenta=eval("f.txtcuenta"+row+".value");
				ls_estprog=eval("f.txtprogramatico"+row+".value");
				ls_documento=eval("f.txtdocumento"+row+".value");
				ls_descripcion=eval("f.txtdescripcion"+row+".value");
				ls_procede=eval("f.txtprocede"+row+".value");
				ls_operacion=eval("f.txtoperacion"+row+".value");
				ldec_monto=eval("f.txtmonto"+row+".value");
				if ((ls_cuenta!="")&&(ls_estprog!="")&&(ls_documento!="")&&(ls_descripcion!="")&&(ls_procede!="")&&(ls_operacion!="")&&(ldec_monto!=""))
				   {
					 f.operacion.value="DELETESPG";
					 f.delete_spg.value=row;
					 f.action="sigesp_scb_p_pago_directo.php";
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

function uf_delete_Ret(row)
{
  lb_mesabi = f.hidmesabi.value;
  if (lb_mesabi=='true')
     {
	  if (uf_evaluate_cierre('SPG'))
		 {
		   ls_status=f.estmov.value;
		   if ((ls_status!="C")&&(ls_status!='O')&&(ls_status!='A'))
			  {
				f.operacion.value="DELETERET";
				f.delete_ret.value=row;
				f.action="sigesp_scb_p_pago_directo.php";
				f.submit();
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
	if(obj.checked==true)
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
if(parseFloat(ldec_monto)<parseFloat(ldec_monobjret))
{
	alert("Monto Objeto a Retención no puede ser mayor al del Movimiento");	
	f.txtmonobjret.value=uf_convertir(0);
	f.txtmonobjret.focus();	
}
}

function cat_cheque()
{
  if (uf_evaluate_cierre('SPG'))
     {
	   ls_codban = f.txtcodban.value;
	   ls_ctaban = f.txtcuenta.value;	
	   ls_status = f.estmov.value;
	   if ((ls_status!='C')&&(ls_status!='O')&&(ls_status!='A'))
		  {   
		    if ((ls_codban!="")&&(ls_ctaban!=""))
			   {
				 pagina="sigesp_cat_cheques.php?codban="+ls_codban+"&ctaban="+ls_ctaban;
				 window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
			   }
		    else
			   {
				 alert("Seleccione el Banco y la Cuenta a la que pertenece el Cheque");
			   }
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

function  uf_nocontabili()
{   
  lb_status=f.nocontabili.checked;
  if (lb_status)
	 {
	   f.estmov.value='L';
	 }  
} 

function ue_verificar_vaucher()
{
  f.operacion.value="VERIFICAR_VAUCHER";
  f.submit();
}

function uf_verificar_monto(monto)
{
  ld_debe  = parseFloat(uf_convertir_monto(f.txtdebe.value));
  ld_haber = parseFloat(uf_convertir_monto(f.txthaber.value));
  ls_max   = Math.max(ld_debe,ld_haber);
  ld_monto = parseFloat(uf_convertir_monto(monto.value));
  if (ld_monto<ls_max)
	 {
	   alert("El monto total del movimiento no debe ser inferior a los asientos contables realizados");
	   monto.value="0,00";
	 }
}   

function uf_cat_fte_financia()
{
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
		    alert("No puede realizar esta operacion el movimiento ya fue Contabilizado o Anulado");
	      }   
     }
}
  
function rellenar_cadena(cadena,longitud)
{
var mystring=new String(cadena);
cadena_ceros="";
lencad=mystring.length;
total=longitud-lencad;
if (cadena!="")
   {
     for (i=1;i<=total;i++)
	 {
	   cadena_ceros=cadena_ceros+"0";
	 }
     cadena=cadena_ceros+cadena;
     document.form1.txtchevau.value=cadena;
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

function uf_catalogo_ordenes()
{
  pagina="sigesp_scb_cat_ordenes_pago_ministerio.php";
  window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=800,height=450,resizable=yes,location=no,dependent=yes");
}

function uf_validar_monto_movimiento()
{
  ld_montotmov = f.txtmonto.value;
  ld_sumdetmov = parseFloat(0);
  ld_montotmov = ue_formato_calculo(ld_montotmov);  
  li_totrowspg = ue_calcular_total_fila_local("txtmonto");
  for (li_i=1;li_i<=li_totrowspg;li_i++)
      {
	    ld_mondetmov = eval("f.txtmonto"+li_i+".value");
	    ld_mondetmov = ue_formato_calculo(ld_mondetmov);
	    ld_sumdetmov = eval(ld_sumdetmov+"+"+ld_mondetmov);
      }
  if (ld_montotmov!=ld_sumdetmov)
	 {
	   alert("Sumatoria de los Detalles Presupuestarios de Gasto No coincide con el Monto del Documento !!!");
	   return false;
	 }
  else
	 {
	   return true;
	 }
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>