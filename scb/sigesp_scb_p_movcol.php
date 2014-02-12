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
$io_fun_banco->uf_load_seguridad("SCB","sigesp_scb_p_movcol.php",$ls_permisos,$la_seguridad,$la_permisos);
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
<title>Movimiento de Colocaci&oacute;n</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
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
		f.action="sigesp_scb_p_movcol.php";			
		f.submit();
	}
}
</script>
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
    </table>  </td>
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
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" title="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="20"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Guardar" title="Guardar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="20"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" title="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" title="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="20"><div align="center"><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="544">&nbsp;</td>
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

	$msg		= new class_mensajes();	
	$fun		= new class_funciones();	
	$lb_guardar = true;
    $sig_inc	= new sigesp_include();
    $con		= $sig_inc->uf_conectar();
 	$obj_spg	= new ddlb_operaciones_spg($con);
 	$obj_spi	= new ddlb_operaciones_spi($con);
 	$obj_con	= new ddlb_conceptos($con);
	$io_grid	= new grid_param();
	
	require_once("sigesp_scb_c_movcol.php");
	$in_classmovcol=new sigesp_scb_c_movcol($la_seguridad);

	if( array_key_exists("operacion",$_POST))
	{
		$ls_operacion= $_POST["operacion"];
		$ls_mov_operacion=$_POST["cmboperacion"];
		if($ls_operacion=="CAMBIO_OPERA")
		{
			$ls_opepre="";	
			$ls_codconmov="";
		}
		else
		{
			if(($ls_mov_operacion=="CH")||($ls_mov_operacion=="ND"))
			{			
				$ls_opepre=$_POST["opepre"];
			}
			elseif(($ls_mov_operacion=="DP")||($ls_mov_operacion=="NC"))
			{
				$ls_opepre=$_POST["opepre"];
			}
			else
			{
				$ls_opepre=$_POST["opepre"];
			}
		}
		$ls_docmov=$_POST["txtdocumento"];
		$ld_fecha=$_POST["txtfecha"];
		$ls_codban=$_POST["txtcodban"];
		$ls_denban=$_POST["txtdenban"];
		$ls_cuenta_banco=$_POST["txtcuenta"];
		$ls_dencuenta_banco=$_POST["txtdenominacion"];
		
		$lastspg = $_POST["lastspg"];
		$lastscg = $_POST["lastscg"];
		$lastret = $_POST["lastret"];
		$lastspi = $_POST["lastspi"];
		$ldec_mondeb=0;
		$ldec_monhab=0;
		$ldec_diferencia=0;
		$ldec_monspg=0;
		$ldec_monspi=0;
		$ldec_montomov=$_POST["txtmonto"];
		$ldec_montomov=str_replace(".","",$ldec_montomov);
		$ldec_montomov=str_replace(",",".",$ldec_montomov);
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
		$ls_numcol=$_POST["txtcolocacion"];
		$ls_dencol=$_POST["txtdencol"];
		$ldec_tasa=$_POST["txttasa"];
		$ls_estcol=$_POST["estcol"];
	}
	else
	{
		$ls_operacion= "NUEVO" ;
		$ls_estcol="N";
		
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
		global $in_classmovcol;
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
		global $li_rows_ret;
		global $ldec_montoret;
		global $ldec_diferencia;
		global $ls_docmov;
		global $ls_codban;
		global $ls_cuenta_banco;
		global $ls_mov_operacion;
		global $ls_numcol;
		$ldec_montoret=0;
		$in_classmovcol->uf_cargar_dt($ls_docmov,$ls_numcol,$ls_codban,$ls_cuenta_banco,$ls_mov_operacion,&$objectScg,&$li_row_scg,&$ldec_mondeb,&$ldec_monhab,&$objectSpg,&$li_temp_spg,&$ldec_monto_spg,&$objectSpi,&$li_temp_spi,&$ldec_monto_spi,&$objectRet,&$li_temp_ret,&$ldec_montoret);
		$li_row=$li_row_scg	;
		$li_rows_spg=$li_temp_spg;
		$ldec_diferencia=$ldec_mondeb-$ldec_monhab;
	}
	
	function uf_nuevo()
	{
		global $ls_mov_operacion;
		$ls_mov_operacion="NC";
	    global $ls_opepre;
		$ls_opepre="";
		global $ldec_tasa;
		$ldec_tasa="";
		global $ls_docmov;
		$ls_docmov="";
		global $ls_codban;
		$ls_codban="";
		global $ls_denban;
		$ls_denban="";
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
		global $ld_fecha;
		global $fun;
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
		$ls_codconmov='000';
		global $ls_desmov;
		$ls_desmov="";
		global $ls_cuenta_scg;
		$ls_cuenta_scg="";
		global $lb_nocontab;
		$lb_nocontab="";
		global $ls_numcol;
		$ls_numcol="";
		global $ls_dencol;
		$ls_dencol="";
		global $li_estint;
		$li_estint=0;
		global $objectScg;
		global $objectSpg;
		global $objectSpi;
		global $objectRet;
		global $li_row_scg;
		$li_row_scg=1;
		$objectScg[$li_row_scg][1] = "<input type=text name=txtcontable".$li_row_scg." id=txtcontable".$li_row_scg."  value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";		
		$objectScg[$li_row_scg][2] = "<input type=text name=txtdocscg".$li_row_scg."    value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
		$objectScg[$li_row_scg][3] = "<input type=text name=txtdesdoc".$li_row_scg."    value='' class=sin-borde readonly style=text-align:left size=35 maxlength=254>";
		$objectScg[$li_row_scg][4] = "<input type=text name=txtprocdoc".$li_row_scg."   value='' class=sin-borde readonly style=text-align:center size=8 maxlength=6>";
		$objectScg[$li_row_scg][5] = "<input type=text name=txtdebhab".$li_row_scg."    value='' class=sin-borde readonly style=text-align:center size=8 maxlength=1>"; 
		$objectScg[$li_row_scg][6] = "<input type=text name=txtmontocont".$li_row_scg." value='' class=sin-borde readonly style=text-align:right size=22 maxlength=22>";
		$objectScg[$li_row_scg][7] = "<a href=javascript:uf_delete_Scg('".$li_row_scg."');><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar detalle contable' width=15 height=15 border=0></a>";
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
		global $li_temp_ret;
		$li_temp_ret=1;
		$objectRet[$li_temp_ret][1]  = "<input type=text name=txtdeduccion".$li_temp_ret."   value='' class=sin-borde readonly style=text-align:center  size=5 maxlength=5>";
		$objectRet[$li_temp_ret][2]  = "<input type=text name=txtcuentaret".$li_temp_ret."   value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>"; 
		$objectRet[$li_temp_ret][3]  = "<input type=text name=txtdescret".$li_temp_ret."     value='' class=sin-borde readonly style=text-align:left size=32 maxlength=45>";
		$objectRet[$li_temp_ret][4]  = "<input type=text name=txtdocret".$li_temp_ret."      value='' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
		$objectRet[$li_temp_ret][5]  = "<input type=text name=txtprocederet".$li_temp_ret."  value='' class=sin-borde readonly style=text-align:center size=6 maxlength=6>";
		$objectRet[$li_temp_ret][6]  = "<input type=text name=txtmontoobjret".$li_temp_ret." value='' class=sin-borde readonly style=text-align:right>";		
		$objectRet[$li_temp_ret][7]  = "<input type=text name=txtmontoret".$li_temp_ret."    value='' class=sin-borde readonly style=text-align:right >";
		$objectRet[$li_temp_ret][8]  = "<a href=javascript:uf_delete_Ret('".$li_temp_ret."');><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar Retención' width=15 height=15 border=0></a>";	
	}

	$titleSpi[1]="Cuenta";     $titleSpi[2]="Descripción";  $titleSpi[3]="Procede";     $titleSpi[4]="Documento";   $titleSpi[5]="Operación";   $titleSpi[6]="Monto";              $titleSpi[7]="Edición";
	$title2[1]="Cuenta";       $title2[2]="Documento";      $title2[3]="Descripción";   $title2[4]="Debe/Haber";    $title2[5]="Monto";         $title2[6]="Edición";
	$title[1]="Cuenta";        $title[2]="Programatico";    $title[3]="Documento";      $title[4]="Descripción";    $title[5]="Operación";      $title[6]= "Monto";                $title[7]="Edición";     
	$titleRet[1]="Deducción";  $titleRet[2]="Cuenta";       $titleRet[3]="Descripción"; $titleRet[4]="Documento";   $titleRet[5]="Procede";     $titleRet[6]="Objeto a Retencion"; $titleRet[7]="Retenido";  $titleRet[8]="Edición";
	
	$gridSpi="grid_Spi";
	$grid2="gridscg";	
    $grid1="grid_SPG";	
    $gridRet="grid_Ret";	
	
	if($ls_operacion == "NUEVO")
	{
		$ls_operacion= "" ;
		uf_nuevo();
	}
	if($ls_operacion == "GUARDAR")
	{			
		uf_cargar_dt();			
	}
	if($ls_operacion == "ELIMINAR")
	{
		$in_classmovcol->SQL->begin_transaction();
		$lb_valido=$in_classmovcol->uf_delete_all_movimiento($ls_docmov,$ls_numcol,$ls_codban,$ls_cuenta_banco,$ls_mov_operacion);
		if($lb_valido)
		{
			$in_classmovcol->SQL->commit();
		}	
		else
		{
			$in_classmovcol->SQL->rollback();			
		}
		$msg->message($in_classmovcol->is_msg_error);
		uf_nuevo();
	}
	if($ls_operacion=="DELETESCG")
	{
		$li_row_delete=$_POST["delete_scg"];
		$ls_codded='00000';
		$ls_cuenta_scg=$_POST["txtcontable".$li_row_delete];
		$ls_debhab=$_POST["txtdebhab".$li_row_delete];
		$ls_numdoc=$_POST["txtdocscg".$li_row_delete];
		$in_classmovcol->SQL->begin_transaction();
		$lb_valido=$in_classmovcol->uf_delete_dt_scg($ls_docmov,$ls_codban,$ls_cuenta_banco,$ls_mov_operacion,$ls_numcol,$ls_cuenta_scg,$ls_debhab,$ls_codded);
		$msg->message($in_classmovcol->is_msg_error);
		if($lb_valido)
		{
			$in_classmovcol->SQL->commit();
		}
		else
		{
			$in_classmovcol->SQL->rollback();
		}
		uf_cargar_dt();
	}
	if($ls_operacion=="DELETESPG")
	{
		$li_row_delete   = $_POST["delete_spg"];
		$ls_cuenta_spg   = $_POST["txtcuenta".$li_row_delete];
		$ls_programatica = $_POST["txtprogramatico".$li_row_delete];
		$ls_numdoc       = $_POST["txtdocumento".$li_row_delete];
		$ls_operacion    = $_POST["txtoperacion".$li_row_delete];
		$ls_estcla       = $_POST["hidestcla".$li_row_delete];
		$in_classmovcol->SQL->begin_transaction();
		$lb_valido=$in_classmovcol->uf_delete_dt_spg($ls_docmov,$ls_codban,$ls_cuenta_banco,$ls_mov_operacion,$ls_numcol,$ls_cuenta_spg,$ls_operacion,$ls_programatica,$ls_estcla);
		$msg->message($in_classmovcol->is_msg_error);
		if($lb_valido)
		{
			$in_classmovcol->SQL->commit();
		}
		else
		{
			$in_classmovcol->SQL->rollback();
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
		$arr_movbco["numcol"]   =$ls_numcol;
		
		$in_classmovcol->SQL->begin_transaction();
		$lb_valido=$in_classmovcol->uf_delete_dt_scg($ls_docmov,$ls_codban,$ls_cuenta_banco,$ls_mov_operacion,$ls_numcol,$ls_cuenta,$ls_debhab,$ls_codded);
		$msg->message($in_classmovcol->is_msg_error);
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
			$lb_valido=$in_classmovcol->uf_update_montodelete($arr_movbco,$ls_cuenta_scg,'SCBMOV',$ls_desmov,$ls_docmov,$ls_operacioncon,$ldec_monto,$ldec_monobjret,'00000');
			$in_classmovcolo->SQL->commit();
		}
		else
		{
			$in_classmovcol->SQL->rollback();
		}
		uf_cargar_dt();
	}
	if($ls_operacion == "CAMBIO_OPERA")
	{
		uf_cargar_dt();		
	}
	if($ls_mov_operacion=='ND')
	{
		$lb_nd="selected";
		$lb_nc="";
		$lb_dp="";		
	}
	if($ls_mov_operacion=='NC')
	{
		$lb_nd="";
		$lb_nc="selected";
		$lb_dp="";
		
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
	}
	
	

?>
  <form name="form1" method="post" action="" id="sigesp_scb_p_movcol.php">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_banco->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_banco);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <br>
  <table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-ventana">
      <td height="22" colspan="4"><input name="hidmesabi" type="hidden" id="hidmesabi" value="true">
      Movimientos de Colocaci&oacute;n </td>
    </tr>
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td width="110" height="22"><div align="right">Documento</div></td>
      <td width="202"><div align="left">
          <input name="txtdocumento" type="text" id="txtdocumento" style="text-align:center" onBlur="rellenar_cad(this.value,15,'doc')" value="<?php print $ls_docmov;?>" size="24" maxlength="15">
          <input name="estcol" type="hidden" id="estcol" value="<?php print $ls_estcol?>">
      </div></td>
      <td width="218"><div align="right">Fecha</div></td>
      <td width="248"><div align="left">
          <input name="txtfecha" type="text" id="txtfecha"  style="text-align:center" value="<?php print $ld_fecha;?>" size="24" maxlength="10" datepicker="true" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);">
      </div></td>
    </tr>
      <script language="javascript">uf_validar_estatus_mes();</script>
	<tr>
      <td height="22"><div align="right">Banco</div></td>
      <td colspan="3"><div align="left">
          <input name="txtcodban" type="text" id="txtcodban"  style="text-align:center" value="<?php print $ls_codban;?>" size="10" readonly>
          <a href="javascript:cat_bancos();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Bancos"></a>
          <input name="txtdenban" type="text" id="txtdenban" value="<?php print $ls_denban?>" size="100" class="sin-borde" readonly>
      </div></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Colocaci&oacute;n</div></td>
      <td colspan="3"><input name="txtcolocacion" type="text" id="txtcolocacion" value="<?php print $ls_numcol;?> " style="text-align:center">
      <a href="javascript:cat_colocaciones();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Bancos"></a>
      <input name="txtdencol" type="text" class="sin-borde" id="txtdencol" value="<?php print $ls_dencol;?>" size="90" readonly></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Cuenta Bancaria </div></td>
      <td colspan="3"><div align="left">
          <input name="txtcuenta" type="text" id="txtcuenta" style="text-align:center" value="<?php print $ls_cuenta_banco; ?>" size="30" maxlength="25" readonly>
          <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion" style="text-align:left" value="<?php print $ls_dencuenta_banco; ?>" size="50" maxlength="254" readonly>
          &nbsp;&nbsp;&nbsp;<input name="txtcuenta_scg" type="text" class="sin-borde" id="txtcuenta_scg" value="<?php print $ls_cuenta_scg;?>" size="30" readonly>
      </div></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Operaci&oacute;n</div></td>
      <td><div align="left">
          <select name="cmboperacion" id="select" onChange="javascript:uf_verificar_operacion();" style="width:120px">
            <option value="ND" <?php print $lb_nd;?>>Nota de D&eacute;bito</option>
            <option value="NC" <?php print $lb_nc;?>>Nota Cr&eacute;dito</option>
            <option value="DP" <?php print $lb_dp;?>>Dep&oacute;sito</option>
          </select>
      </div></td>
      <td><div align="right"><?php if($ls_mov_operacion=="CH")
								{
									print "Voucher";
								}
								?>
	  </div></td>
      <td><div align="left"><?php if($ls_mov_operacion=="CH")
								{
									print "<input name=txtchevau type=text id=txtchevau size=28 maxlength=25 value='".$ls_chevau."'>";
								}
								?>
	  </div></td>
    </tr>
    <tr>
      <td height="22"><div align="right"><?php 
	   if($ls_mov_operacion!="RE"){print "Afectación";} ?></div></td>
      <td><div align="left">
          <?php  if(($ls_mov_operacion=='ND')||($ls_mov_operacion=='CH'))
				{
					$obj_spg->uf_cargar_ddlb_spg(0,$ls_opepre,$ls_mov_operacion); 
					
				}
				elseif(($ls_mov_operacion=='DP')||($ls_mov_operacion=='NC'))
				{
					$obj_spi->uf_cargar_ddlb_spi(0,$ls_opepre,$ls_mov_operacion); 
					
				}				
				?>
          <input name="opepre" type="hidden" id="opepre" value="<?php print $ls_opepre;?>">
</div></td>
      <td><div align="right">Concepto</div></td>
      <td><div align="left"><?php $obj_con->uf_cargar_conceptos($ls_mov_operacion,$ls_codconmov);	?>
        <input name="codconmov" type="hidden" id="codconmov" value="<?php print $ls_codconmov;?>">
      </div></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Concepto Movimiento </div></td>
      <td colspan="3"><div align="left">
          <input name="txtconcepto" type="text" id="txtconcepto" value="<?php print $ls_desmov;?>" size="120" maxlength="254" onKeyPress="return keyRestrict(event,'0123456789'+'abcdefghijklmnopqrstuvwxyzñ .,*/-()$%&!ºªáéíóú[]{}<>')">
      </div></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Tasa</div></td>
      <td><div align="left">
        <input name="txttasa" type="text" id="txttasa" value="<?php print $ldec_tasa;?>" maxlength="22">
      </div></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="22"><div align="right">Monto</div></td>
      <td><div align="left">
          <input name="txtmonto" type="text" id="txtmonto" style="text-align:right" onBlur="javascript:uf_format(this);" onKeyPress="return(currencyFormat(this,'.',',',event));" value="<?php print number_format($ldec_montomov,2,",",".");?>" size="24" maxlength="22">
      </div></td>
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
          			<input name="chkinteres" type="checkbox" id="chkinteres" value="1" style="width:15px; height:15px" onClick="uf_selec_interes(this);" <?php print $lb_checked;?>>
         		<?php	
				}	
				?>
                <input name="estint" type="hidden" id="estint" value="<?php print $li_estint;?>">
</div></td>
      <td><div align="right">No Contabilizable </div></td>
      <td><div align="left">
        <input name="nocontabili" type="checkbox" id="nocontabili" value="checkbox" style="width:15px; height:15px" <?php print $lb_nocontab;?>>
      </div></td>
    </tr>
    <tr>
      <td height="13" colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td height="21" colspan="4"><table width="613" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td width="207"><div align="center"><a href="#01">Detalle Presupuesto de Gasto </a></div></td>
          <td width="203"><div align="center"><a href="#02">Detalle Retenciones </a></div></td>
          <td width="203"><div align="center"><a href="#03">Detalle Presupuesto de Ingreso </a></div></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="13"><div align="right"> </div> <a href="#01"> </a></td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="4">&nbsp;&nbsp;<a href="javascript: uf_agregar_dtcon();"><img src="../shared/imagebank/tools/nuevo.gif" width="15" height="15" border="0">Agregar detalle Contable </a> </td>
    </tr>
    <tr>
      <td height="22" colspan="4"><div align="center"><?php $io_grid->makegrid($li_row,$title2,$objectScg,770,'Detalles Contable',$grid2);?>
        
          <input name="totcon"  type="hidden" id="totcon"  size=5 value="<?php print $totalcon?>">
          <input name="lastscg" type="hidden" id="lastscg" size=5 value="<?php print $lastscg;?>">
          <input name="delete_scg" type="hidden" id="delete_scg" size=5>
      </div></td>
    </tr>
    <tr>
      <td height="22" colspan="4"><table width="210" border="0" align="right" cellpadding="0" cellspacing="0">
        <tr>
          <td width="82" height="20"><div align="right">Total Debe</div></td>
          <td width="128"><input name="txtdebe" type="text" id="txtdebe" value="<?php print number_format($ldec_mondeb,2,',','.');?>" style="text-align:right"></td>
        </tr>
        <tr>
          <td height="20"><div align="right">Total Haber</div></td>
          <td><input name="txthaber" type="text" id="txthaber" value="<?php print number_format($ldec_monhab,2,',','.');?>" style="text-align:right"></td>
        </tr>
        <tr>
          <td height="20"><div align="right">Diferencia</div></td>
          <td><input name="txtdiferencia" type="text" id="txtdiferencia" value="<?php print number_format($ldec_diferencia,2,',','.');?>" style="text-align:right"></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="22" colspan="4">&nbsp;&nbsp;<a href="javascript: alert('Este movimiento no puede registrar detalle de presupuesto');"><img src="../shared/imagebank/tools/nuevo.gif" width="15" height="15" border="0">Agregar detalle Presupuesto</a> </td>
    </tr>
    <tr>
      <td height="22" colspan="4"><div align="center">
        <a name="01" id="01"></a>
        <?php $io_grid->makegrid($li_rows_spg,$title,$objectSpg,770,'Detalles Presupuestarios',$grid1);?>
        <input name="totpre"  type="hidden" id="totpre"  value="<?php print $totalpre?>">
        <input name="lastspg" type="hidden" id="lastspg" value="<?php print $lastspg;?>">
        <input name="delete_spg" type="hidden" id="delete_spg">
</div></td>
    </tr>
    <tr>
      <td height="22" colspan="4"><table width="223" border="0" align="right" cellpadding="0" cellspacing="0">
        <tr>
          <td width="96" height="20"><div align="right">Total Presupuesto </div></td>
          <td width="127"><input name="totspg" type="text" id="totspg" value="<?php print number_format($ldec_monspg,2,',','.');?>" style="text-align:right"></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="22" colspan="4">&nbsp;&nbsp;<a href="javascript: alert('Este movimiento no puede registrar Retenciones');"><img src="../shared/imagebank/tools/nuevo.gif" width="15" height="15" border="0">Agregar detalle Retenciones </a> </td>
    </tr>
    <tr>
      <td height="22" colspan="4"><div align="center"><a name="02" id="02"></a>
        <?php $io_grid->makegrid($li_rows_ret,$titleRet,$objectRet,770,'Detalles Retenciones',$gridRet);?>        
          <input name="totret"  type="hidden" id="totret"  value="<?php print $totalret?>">
          <input name="lastret" type="hidden" id="lastret" value="<?php print $lastret;?>">
          <input name="delete_ret" type="hidden" id="delete_scg3">
      </div></td>
    </tr>
    <tr>
      <td height="22" colspan="4"><table width="223" border="0" align="right" cellpadding="0" cellspacing="0">
        <tr>
          <td width="96" height="20"><div align="right">Total Retenci&oacute;n </div></td>
          <td width="127"><input name="txtret" type="text" id="txtret" value="<?php print number_format($ldec_montoret,2,',','.');?>" style="text-align:right" readonly></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="22" colspan="4">&nbsp;&nbsp;<img src="../shared/imagebank/tools/nuevo.gif" width="15" height="15" border="0">Agregar detalle Ingreso  </td>
    </tr>
    <tr>
      <td height="22" colspan="4"><div align="center">
        <a name="03"></a>
        <?php $io_grid->makegrid($li_rows_spi,$titleSpi,$objectSpi,770,'Detalle Ingresos',$gridSpi);?>
        <input name="totspi" type="hidden" id="totspi" value="<?php print $totalspi?>">
        <input name="lastspi" type="hidden" id="lastspi" value="<?php print $lastspi;?>">
        <input name="delete_spi" type="hidden" id="delete_spi">
      </div></td>
    </tr>
    <tr>
      <td height="22" colspan="4"><div align="center"><a href="#00">Volver Arriba</a> </div></td>
    </tr>
  </table>
  <p><input name="operacion" type="hidden" id="operacion">
  
</p>
  </form>
</body>
<script language="javascript">
var patron = new Array(2,2,4);
f = document.form1;
function ue_nuevo()
{
  f.operacion.value ="NUEVO";
  f.action="sigesp_scb_p_movcol.php";
  f.submit();
}

function ue_guardar()
{
  lb_mesabi = f.hidmesabi.value;
  if (lb_mesabi=='true')
     {
		f=document.form1;
		ls_estcol=f.estcol.value;
		if(ls_estcol=="N")
		{
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
			if(ls_operacion=="ND")
			{
			  li_estint=0;
			}
			else
			{
				li_estint=0;
			}
			
			while(ldec_monto.indexOf('.')>0)
			{//Elimino todos los puntos o separadores de miles
				ldec_monto=ldec_monto.replace(".","");
			}
			ldec_monto=ldec_monto.replace(",",".");
			
			if((lb_valido)&&(ls_descripcion!="")&&(ls_codban!="")&(ls_ctaban!="")&&(ls_documento!="")&&(ls_operacion!="")&&(ldec_monto>0))
			{
				f.operacion.value ="GUARDAR";
				f.action="sigesp_scb_p_movcol.php";
				f.submit();
			}
			else
			{
				alert("Complete los datos del movimiento.");
			}
		}
		else if(ls_estcol=="C")
		{
			alert("El movimiento no puede ser modificado, ya fue contabilizado");
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
		f=document.form1;
		ls_estcol=f.estcol.value;
		if(ls_estcol=="N")
		{
		  if(confirm("Está seguro? \nEsta acción no puede ser reversada"))	
		  {
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
			if(ls_operacion=="ND")
			{
				/*if(f.chkinteres.checked)
				{
					li_estint=1;
				}
				else
				{*/
					li_estint=0;
				/*}*/
			}
			else
			{
				li_estint=0;
			}
			
			while(ldec_monto.indexOf('.')>0)
			{//Elimino todos los puntos o separadores de miles
				ldec_monto=ldec_monto.replace(".","");
			}
			ldec_monto=ldec_monto.replace(",",".");
			
			if((lb_valido)&&(ls_descripcion!="")&&(ls_codban!="")&(ls_ctaban!="")&&(ls_documento!="")&&(ls_operacion!="")&&(ldec_monto>0))
			{
				f.operacion.value ="ELIMINAR";
				f.action="sigesp_scb_p_movcol.php";
				f.submit();
			}
			else
			{
				alert("No ha completado los datos");
			}
		  }
		}
		else if(ls_estcol=="C")
		{
			alert("El movimiento no puede ser eliminado, ya fue contabilizado");
		}
     }
  else
     {
	   alert("Operación No puede ser procesada, El Més está Cerrado !!!");
	 }
}

function ue_buscar()
{
	window.open("sigesp_cat_mov_colocacion.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=750,height=400,left=50,top=50,location=no,resizable=yes");
}

    //Funciones de validacion de fecha.
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
		}
	}
	
	//Catalogo de cuentas contables
	function catalogo_cuentabanco()
	 {
	   uf_validar_estatus_mes();
	   lb_mesabi = f.hidmesabi.value;
	   if (lb_mesabi=='true')
		  {
		    ls_codban = f.txtcodban.value;
		    ls_denban = f.txtdenban.value;
			if (ls_codban!="")
			   {
			     pagina = "sigesp_cat_ctabanco.php?codigo="+ls_codban+"&denban="+ls_denban;
				 window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
			   }
			else
			   {
			     alert("Seleccione el Banco !!!");
			   }	  
	      }
       else
          {
	        alert("Operación No puede ser procesada, El Més está Cerrado !!!");
	      }
	 }
	 
	 function cat_colocaciones()
	 {
	   uf_validar_estatus_mes();
	   lb_mesabi = f.hidmesabi.value;
	   if (lb_mesabi=='true')
		  {
		    ls_codban=f.txtcodban.value;
		    ls_denban=f.txtdenban.value;
		    if((ls_codban!=""))
		    {
			   pagina="sigesp_cat_colocaciones.php?codigo="+ls_codban+"&denban="+ls_denban;
			   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=700,height=400,resizable=yes,location=no");
		    }
		    else
		    {
		 		alert("Seleccione el Banco");   
		    } 
	      }
       else
          {
	        alert("Operación No puede ser procesada, El Més está Cerrado !!!");
	      }
	 }	 
	 
	 function cat_tipocol()
	 {
	   uf_validar_estatus_mes();
	   pagina="sigesp_cat_tipocolocacion.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
	 }
	 
	 function catalogo_cuentascg()
	 {
	   uf_validar_estatus_mes();
	   lb_mesabi = f.hidmesabi.value;
	   if (lb_mesabi=='true')
		  {
		    pagina="sigesp_cat_filt_scg.php?filtro="+'11102'+"&opener=sigesp_scb_d_colocacion.php";
		    window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
	      }
       else
          {
	        alert("Operación No puede ser procesada, El Més está Cerrado !!!");
	      }
	 }
	 	 
	 function cat_bancos()
	 {
	   uf_validar_estatus_mes();
	   lb_mesabi = f.hidmesabi.value;
	   if (lb_mesabi=='true')
		  {
	        pagina="sigesp_cat_bancos.php";
	        window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
	      }
       else
          {
	        alert("Operación No puede ser procesada, El Més está Cerrado !!!");
	      }
	 }

	function cat_conceptos()
	{
	   uf_validar_estatus_mes();
	   lb_mesabi = f.hidmesabi.value;
	   if (lb_mesabi=='true')
		  {
		    pagina="sigesp_cat_conceptos.php";
		    window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
	      }
       else
          {
	        alert("Operación No puede ser procesada, El Més está Cerrado !!!");
	      }
	}
   
    function uf_verificar_operacion()
    {
	  f.operacion.value="CAMBIO_OPERA";
	  f.opepre.value=f.cmboperacion.value;
	  f.submit();   
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
	  lb_mesabi = f.hidmesabi.value;
	  if (lb_mesabi=='true')
		 {
			if(f.rb_provbene[0].checked==true)
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
		   alert("Operación No puede ser procesada, El Més está Cerrado !!!");
		 }
	}   

	function uf_verificar_provbene(lb_checked,obj)
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

   function  uf_agregar_dtcon()
   {
     lb_mesabi = f.hidmesabi.value;
	 if (lb_mesabi=='true')
		{
			f=document.form1;
			ls_estcol=f.estcol.value;
			if(ls_estcol=="N")
			{
				ls_documento=f.txtdocumento.value;
				ls_codban=f.txtcodban.value;
				ls_ctaban=f.txtcuenta.value;
				ls_operacion=f.cmboperacion.value;		
				li_lastscg=f.lastscg.value;
				li_newrow=parseInt(li_lastscg,10)+1;
				ls_cuenta_scg=f.txtcuenta_scg.value;
				ls_descripcion=f.txtconcepto.value;
				ls_procede="SCBCOL";
				ldec_monto=f.txtmonto.value;
				ls_cuenta_scg=f.txtcuenta_scg.value;
				ld_fecha=f.txtfecha.value;
				ls_cuenta_scg=f.txtcuenta_scg.value;
				total=f.totcon.value;
				ls_codconmov=f.ddlb_conceptos.value;
				ls_numcol=f.txtcolocacion.value;
				ls_tasa=f.txttasa.value;
				ls_fecha=f.txtfecha.value;
				if(f.nocontabili.checked==true)
				{
					ls_estmov="L";
				}
				else
				{
					ls_estmov="N";
				}
				ldec_tasa=f.txttasa.value;
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
				
				while(ldec_monto.indexOf('.')>0)
				{//Elimino todos los puntos o separadores de miles
					ldec_monto=ldec_monto.replace(".","");
				}
				ldec_monto=ldec_monto.replace(",",".");		
		
				if((lb_valido)&&(ls_descripcion!="")&&(ls_codban!="")&(ls_ctaban!="")&&(ls_documento!="")&&(ls_operacion!="")&&(ldec_monto>0) &&(ls_tasa!="") &&(ls_fecha!=""))
				{
					ls_pagina = "sigesp_w_regdt_col_contable.php?txtprocedencia=SCBCOL&txtdoccol="+ls_documento+"&fecha="+ld_fecha+"&procede="+ls_procede+"&descripcion="+ls_descripcion+"&codban="+ls_codban+"&ctaban="+ls_ctaban+"&cuenta_scg="+ls_cuenta_scg+"&mov_operacion="+ls_operacion+"&monto="+ldec_monto+"&chevau="+ls_chevau+"&estint="+li_estint+"&cobrapaga="+li_cobrapaga+"&estbpd=M&estmov="+ls_estmov+"&codconmov="+ls_codconmov+"&tasa="+ldec_tasa+"&opener=sigesp_scb_p_movcol.php&numcol="+ls_numcol;
					window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=no,width=570,height=182,left=50,top=50,location=no,resizable=no,dependent=yes");
				}
				else
				{
					alert("Complete los datos del Movimiento");
				}
			}
			else if(ls_estcol=="C")
			{
				alert("El movimiento no puede ser modificado, ya fue contabilizado");
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
			f=document.form1;
			ls_operacion=f.cmboperacion.value;		
			li_lastscg=f.lastscg.value;
			li_newrow=parseInt(li_lastscg,10)+1;
			ls_cuenta_scg=f.txtcuenta_scg.value;
			ls_descripcion=f.txtconcepto.value;
			ls_numcol=f.txtcolocacion.value;
			ls_procede="SCBMOV";
			ls_documento=f.txtdocumento.value;
			ldec_monto=f.txtmonto.value;
			ls_cuenta_scg=f.txtcuenta_scg.value;
			ld_fecha=f.txtfecha.value;
			ls_codban=f.txtcodban.value;
			ls_ctaban=f.txtcuenta.value;
			ls_cuenta_scg=f.txtcuenta_scg.value;
			total=f.totcon.value;
			ls_codconmov=f.ddlb_conceptos.value;
			
			while(ldec_monto.indexOf('.')>0)
			{//Elimino todos los puntos o separadores de miles
				ldec_monto=ldec_monto.replace(".","");
			}
			ldec_monto=ldec_monto.replace(",",".");
			
			if((ls_operacion=="CH")||(ls_operacion=="ND"))
			{			
				li_cobrapaga=f.ddlb_spg.value;			
			}
			else if((ls_operacion=="DP")||(ls_operacion=="NC"))
			{
				li_cobrapaga=f.ddlb_spi.value;
			}
			ldec_tasa=f.txttasa.value;
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
	
			if((lb_valido)&&(ls_descripcion!="")&&(ls_codban!="")&(ls_ctaban!="")&&(ls_documento!="")&&(ls_operacion!="")&&(ldec_monto>0))
			{
				if((ls_operacion!="NC")&&(ls_operacion!="DP"))
				{
					ls_pagina = "sigesp_w_regdt_col_presupuesto.php?txtprocedencia=SCBCOL&numdoc="+ls_documento+"&fecha="+ld_fecha+"&procede="+ls_procede+"&descripcion="+ls_descripcion+"&codban="+ls_codban+"&ctaban="+ls_ctaban+"&cuenta_scg="+ls_cuenta_scg+"&mov_operacion="+ls_operacion+"&monto="+ldec_monto+"&chevau="+ls_chevau+"&estint="+li_estint+"&cobrapaga="+li_cobrapaga+"&estbpd=M&estmov="+ls_estmov+"&codconmov="+ls_codconmov+"&tasa="+ldec_tasa+"&opener=sigesp_scb_p_movcol.php&txtdoccol="+ls_numcol;
					window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=no,width=570,height=250,left=50,top=50,location=no,resizable=no,dependent=yes");
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
	      alert("Operación No puede ser procesada, El Més está Cerrado !!!");
	    }   
   }
   
   function  uf_agregar_dtret(operacion)
   {
     lb_mesabi = f.hidmesabi.value;
	 if (lb_mesabi=='true')
		{
			f=document.form1;
			ls_documento=f.txtdocumento.value;
			ls_procede="SCBMOV";
			 f=document.form1;
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
			ls_codconmov=f.ddlb_conceptos.value;
			
			while(ldec_monto.indexOf('.')>0)
			{//Elimino todos los puntos o separadores de miles
				ldec_monto=ldec_monto.replace(".","");
			}
			ldec_monto=ldec_monto.replace(",",".");
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
			
			
			if((ls_descripcion!="")&&(ls_codban!="")&(ls_ctaban!="")&&(ls_documento!="")&&(ls_operacion!="")&&(ldec_monto>0))
			{
				if(ls_operacion=="CH")
				{
					ls_pagina = "sigesp_w_regdt_deducciones.php?txtdocumento="+ls_documento+"&fecha="+ld_fecha+"&procede="+ls_procede+"&descripcion="+ls_descripcion+"&codban="+ls_codban+"&ctaban="+ls_ctaban+"&cuenta_scg="+ls_cuenta_scg+"&mov_operacion="+ls_operacion+"&monto="+ldec_monto+"&chevau="+ls_chevau+"&estint="+li_estint+"&cobrapaga="+li_cobrapaga+"&estbpd=M&txtnomproben="+ls_nomproben+"&estmov="+ls_estmov+"&codconmov="+ls_codconmov+"&tip_mov= &opener=sigesp_scb_p_movcol.php";
					window.open(ls_pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=570,height=350,left=50,top=50,location=no,resizable=no,dependent=yes");
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
	      alert("Operación No puede ser procesada, El Més está Cerrado !!!");
	    }   
   }
   
   function uf_delete_Scg(row)
   {
     lb_mesabi = f.hidmesabi.value;
	 if (lb_mesabi=='true')
		{
			f=document.form1;
			ls_cuenta=eval("f.txtcontable"+row+".value");
			ls_documento=eval("f.txtdocscg"+row+".value");
			ls_descripcion=eval("f.txtdesdoc"+row+".value");
			ls_debhab=eval("f.txtdebhab"+row+".value");
			ldec_montocont=eval("f.txtmontocont"+row+".value");
			if((ls_cuenta!="")&&(ls_documento!="")&&(ls_descripcion!="")&&(ls_debhab!=""))
			{
				f.operacion.value="DELETESCG";
				f.delete_scg.value=row;
				f.action="sigesp_scb_p_movcol.php";
				f.submit();
			}
			else
			{
				alert("No hay datos para eliminar");
			}
        }
   }
   
   function uf_delete_Spg(row)
   {
     lb_mesabi = f.hidmesabi.value;
	 if (lb_mesabi=='true')
		{
			f=document.form1;
			ls_cuenta=eval("f.txtcuenta"+row+".value");
			ls_estprog=eval("f.txtprogramatico"+row+".value");
			ls_documento=eval("f.txtdocumento"+row+".value");
			ls_descripcion=eval("f.txtdescripcion"+row+".value");
			ls_operacion=eval("f.txtoperacion"+row+".value");
			ldec_monto=eval("f.txtmonto"+row+".value");
			if((ls_cuenta!="")&&(ls_estprog!="")&&(ls_documento!="")&&(ls_descripcion!="")&&(ls_operacion!="")&&(ldec_monto!=""))
			{
				f.operacion.value="DELETESPG";
				f.delete_spg.value=row;
				f.action="sigesp_scb_p_movcol.php";
				f.submit();
			}
			else
			{
				alert("No hay datos para eliminar");
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
   		  f=document.form1;
		  f.operacion.value="DELETERET";
		  f.delete_ret.value=row;
		  f.action="sigesp_scb_p_movcol.php";
		  f.submit();
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
   		  f=document.form1;
		  f.operacion.value="DELETESPI";
		  f.delete_spi.value=row;
		  f.action="sigesp_scb_p_movcol.php";
		  f.submit();
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
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>