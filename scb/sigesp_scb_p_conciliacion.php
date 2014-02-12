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
$io_fun_banco->uf_load_seguridad("SCB","sigesp_scb_p_conciliacion.php",$ls_permisos,$la_seguridad,$la_permisos);
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
<title>Conciliaci&oacute;n Bancaria</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/general.css"  rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css"   rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/sigesp_cat_ordenar.js"></script>
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
<table width="778" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
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
    <td class="toolbar"><div align="right" class="letras-peque&ntilde;as"></div></td>
  </tr>
  <tr>
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" title="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="20"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Guardar" title="Guardar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="21"><div align="center"><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="677">&nbsp;</td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_fecha.php");
    require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/ddlb_conceptos.php");
	require_once("../shared/class_folder/grid_param.php");
			
	$msg           = new class_mensajes();	
	$fun           = new class_funciones();	
	$in_classfecha = new class_fecha();
	$lb_guardar    = true;
    $sig_inc       = new sigesp_include();
    $con           = $sig_inc->uf_conectar();
 	$obj_con       = new ddlb_conceptos($con);
	$io_grid       = new grid_param();
	
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	
	require_once("sigesp_scb_c_conciliacion.php");
	$in_classconciliacion=new sigesp_scb_c_conciliacion($la_seguridad);

	if( array_key_exists("operacion",$_POST))
	{
		$ls_operacion= $_POST["operacion"];
		$ls_codban=$_POST["txtcodban"];
		$ls_denban=$_POST["txtdenban"];
		$ls_cuenta_banco=$_POST["txtcuenta"];
		$ls_dencuenta_banco=$_POST["txtdenominacion"];
		$ls_cuenta_scg=$_POST["txtcuenta_scg"];
		$ldec_disponible=$_POST["txtdisponible"];	
		$ld_fecha=$_POST["txtfecha"];
		$li_totfilmod = $_POST["hidtotfilmod"];
		if(array_key_exists("chkcloseconcil",$_POST))
		{
			$li_estcon=1;
		}
		else
		{
			$li_estcon=0;
		}
		$ldec_tranoreglib = $_POST["txttranoreglib"];
		$ldec_tranoreglib = str_replace(".","",$ldec_tranoreglib);
		$ldec_tranoreglib = str_replace(",",".",$ldec_tranoreglib);
		$ldec_salseglib   = $_POST["txtsalseglib"];
		$ldec_salseglib   = str_replace(".","",$ldec_salseglib);
		$ldec_salseglib   = str_replace(",",".",$ldec_salseglib);
		$ldec_errorbco    = $_POST["txterrorban"];
		$ldec_errorbco    = str_replace(".","",$ldec_errorbco);
		$ldec_errorbco    = str_replace(",",".",$ldec_errorbco);
		$ldec_errorlib    = $_POST["txterrorlib"];
		$ldec_errorlib    = str_replace(".","",$ldec_errorlib);
		$ldec_errorlib    = str_replace(",",".",$ldec_errorlib);
		$ldec_tranoregban = $_POST["txttranoregban"];
		$ldec_tranoregban = str_replace(".","",$ldec_tranoregban);
		$ldec_tranoregban = str_replace(",",".",$ldec_tranoregban);
		$ldec_salsegban   = $_POST["txtsalsegban"];
		$ldec_salsegban   = str_replace(".","",$ldec_salsegban);
		$ldec_salsegban   = str_replace(",",".",$ldec_salsegban);
		$ldec_montoconcil = $_POST["txtsaldoconcil"];
		$ldec_montoconcil = str_replace(".","",$ldec_montoconcil);
		$ldec_montoconcil = str_replace(",",".",$ldec_montoconcil);
		$ls_periodo       = $_POST["txtperiodo"];
		$ls_mes     	  = $_POST["cmbmes"];
	}
	else
	{
		$ls_operacion= "NUEVO" ;	
		$ldec_tranoreglib = 0;
		$ldec_salseglib   = 0;
		$ldec_errorbco    = 0;
		$ldec_errorlib    = 0;
		$ldec_tranoregban = 0;
		$ldec_salsegban   = 0;
		$ldec_montoconcil = 0;
		$li_estcon        = 0;
		$li_totfilmod     = 0;
		$ls_periodo=substr($arre["periodo"],0,4);
		$ls_mes='01';
		$ld_fecha=$ls_mes."/".$ls_periodo;
		
	}	
	$li_row=0;
	$li_rows_spg=0;
	$li_rows_ret=0;
	$li_rows_spi=0;
	$ls_filtrond="";
	$ls_filtronc="";
	$ls_filtrod="";
	$ls_filtror="";
	$ls_filtroch="";
	$ls_filtrot="";
	if(array_key_exists("filtro",$_POST))
	{
		$ls_filtro=$_POST["filtro"];		
		switch($ls_filtro)
		{
			case "ND":
				$ls_filtrond="checked";
			break;
			case "NC":
				$ls_filtronc="checked";
			break;
			case "DP":
				$ls_filtrod="checked";
			break;
			case "RE":
				$ls_filtror="checked";
			break;
			case "CH":
				$ls_filtroch="checked";
			break;
			case "T":
				$ls_filtrot="checked";
			break;
		}
	}
	else
	{
		$ls_filtro="T";
		$ls_filtrot="checked";
	}
	
if (array_key_exists("hidorden",$_POST))
   {
     $ls_orden = $_POST["hidorden"];
   }
else
   {
     $ls_orden = "numdoc ASC";
   }	
	
	function uf_nuevo()
	{
		global $ls_codban;
		$ls_codban="";
		global $ls_denban;
		$ls_denban="";
		global $ls_cuenta_banco;
		$ls_cuenta_banco="";
		global $ls_dencuenta_banco;
		$ls_dencuenta_banco="";	
		global $la_seguridad;
		require_once("sigesp_scb_c_movbanco.php");
		$in_classmovbanco=new sigesp_scb_c_movbanco($la_seguridad);
		global $ls_codemp;
		global $ldec_disponible;	
		$ldec_disponible="";	
		global $ldec_diferencia;	
		$ldec_diferencia=0;	
		global $ldec_montoconcil;	
		$ldec_montoconcil=0;	
		global $fun;
		global $ls_cuenta_scg;
		$ls_cuenta_scg="";
		global $li_rows;
		global $li_temp;
		global $object;
		global $ld_fecha;
		global $ld_desde;
		$li_temp=1;	
		$li_rows=$li_temp;
		$object[$li_temp][1]  = "<input name=chk".$li_temp." type=checkbox id=chk".$li_temp." value=1 class=sin-borde onClick=javascript:uf_selected($li_temp);>";
		$object[$li_temp][2]  = "<input type=text name=txtnumdoc".$li_temp." value='' class=sin-borde readonly style=text-align:center size=18 maxlength=15>";
		$object[$li_temp][3]  = "<input type=text name=txtfecmov".$li_temp." value='' class=sin-borde readonly style=text-align:center size=10 maxlength=10>";
		$object[$li_temp][4]  = "<input type=text name=txtconmov".$li_temp." value='' class=sin-borde readonly style=text-align:left size=20 maxlength=20>";
		$object[$li_temp][5]  = "<input type=text name=txtmonto".$li_temp."  value='".number_format(0,2,",",".")."' class=sin-borde readonly style=text-align:right size=20 maxlength=20>";
		$object[$li_temp][6]  = "<input type=text name=txtcodope".$li_temp." value='' class=sin-borde readonly style=text-align:center size=5 maxlength=5>";				
		$object[$li_temp][7]  = "<input type=text name=txtestmov".$li_temp." value='' class=sin-borde readonly style=text-align:center size=5 maxlength=5>";				
		$object[$li_temp][8]  = "<input type=text name=txtfeccon".$li_temp." value='' class=sin-borde readonly style=text-align:center size=10 maxlength=10>";			
		$object[$li_temp][9]  = "<input type=text name=txtestreglib".$li_temp." value='' class=sin-borde readonly style=text-align:center size=5 maxlength=5>";
		$object[$li_temp][10]  = "<input type=text name=txtnumcarord".$li_temp." value='' class=sin-borde readonly style=text-align:center size=5 maxlength=15>";										
	}

	$title[1]="Todos<input name=chkall type=checkbox id=chkall value=1 class=sin-borde style=width:15px;height:15px onClick=javascript:uf_select_all();>";  	
	$title[2]="<a href=javascript:cargar_detalle('numdoc');><font color=#FFFFFF>Documento</font></a>";
	$title[3]="<a href=javascript:cargar_detalle('fecmov');><font color=#FFFFFF>Fecha</font></a>";   
	$title[4]="Concepto"; 
	$title[5]="<a href=javascript:cargar_detalle('monto');><font color=#FFFFFF>Monto</font></a>";
	$title[6]="<a href=javascript:cargar_detalle('codope');><font color=#FFFFFF>Operacion</font></a>";
	$title[7]="Estatus Mov.";
	$title[8]="Fecha Concil.";  
	$title[9]="Reg.Libro";
	$title[10]="<a href=javascript:cargar_detalle('numcarord');><font color=#FFFFFF>Carta Orden</font></a>";  
	$grid="grid";

	if($ls_operacion == "NUEVO")
	{
		$ls_operacion= "" ;
		uf_nuevo();
	}//fin NUEVO
	if($ls_operacion=="ABRIR_CONCILIACION")
	{
		$ld_desde="01/".$ld_fecha;
		$ld_hasta=$in_classfecha->uf_last_day(substr($ld_fecha,0,2),substr($ld_fecha,3,4));	
		$lb_valido=$in_classconciliacion->uf_abrir_conciliacion($ls_codban,$ls_cuenta_banco,$ld_desde,$ld_hasta);
		if($lb_valido)
		{
			$in_classconciliacion->io_sql->commit();			
			$lb_chkconcil="";
			$msg->message("La conciliación fue abierta");				
		}
		else					
		{
			$in_classconciliacion->io_sql->rollback();
			$msg->message("Error al intentar abrir la conciliación");
		}
		$ls_operacion="CARGAR_DT";
		
	
	}
	if ($ls_operacion=="CARGAR_DT")
	   {
	   	 $ld_desde="01/".$ld_fecha;
		 $ld_hasta=$in_classfecha->uf_last_day(substr($ld_fecha,0,2),substr($ld_fecha,3,4));
		 $in_classconciliacion->uf_cargar_movimientos_a_conciliar($ls_codban,$ls_cuenta_banco,&$object,$ld_desde,$ld_hasta,&$li_rows,$ls_filtro,$ls_orden);
		 $li_total_conc =$in_classconciliacion->ds_concil->getRowcount("codban");
		 if ($li_total_conc > 0 )
		    {
			  $ldec_salseglib   = $in_classconciliacion->uf_calcular_saldolibro($ls_codban,$ls_cuenta_banco,$ld_fecha);
			  $ldec_salseglib2  = $in_classconciliacion->ds_concil->getValue("salseglib",1);
			  $ldec_salsegban   = $in_classconciliacion->ds_concil->getValue("salsegbco",1);
			  $ldec_montoconcil = $in_classconciliacion->ds_concil->getValue("conciliacion",1);
			  $li_estcon	    = $in_classconciliacion->ds_concil->getValue("estcon",1);
 	        }		
	}//fin CARGAR_DT
	
	if ($ls_operacion=="GUARDAR")
	   {		
		 $li_total						  = $_POST["totalrows"];
		 $aa_conciliacion["mesano"]		  = str_replace("/","",$ld_fecha);
		 $aa_conciliacion["salseglib"]	  = $ldec_salseglib;
		 $aa_conciliacion["salsegban"]	  = $ldec_salsegban;	
		 $aa_conciliacion["conciliacion"] = $ldec_montoconcil;		 		 
		 $aa_conciliacion["estcon"]		  = $li_estcon;
		 
		 $in_classconciliacion->io_sql->begin_transaction();
		 $li_numrowmod = 0;$lb_valido = true;
		 for ($li_i=1;$li_i<=$li_total;++$li_i)
		     { 
			   $li_change = $_POST["hidchange".$li_i];
			   if ($li_change==1)
			      {
				    $li_numrowmod++;
					$ls_numdoc = $_POST["txtnumdoc".$li_i];
			        $ls_codope = $_POST["txtcodope".$li_i];
			        if (array_key_exists("chk".$li_i,$_POST))
				       {
				         $li_estcon_mov = 1;
				         $ld_feccon     = substr($ld_fecha,3,4).'-'.substr($ld_fecha,0,2).'-'.'01'; 
				       }
			        else
				       {
				         $ld_feccon     = "1900-01-01";
				         $li_estcon_mov = 0;
				       }
                    $lb_valido = $in_classconciliacion->uf_update_movimientos($ls_codemp,$ls_codban,$ls_cuenta_banco,$ls_numdoc,$ls_codope,$li_estcon_mov,$ld_feccon);
				  }
			    if (!$lb_valido || $li_numrowmod==$li_totfilmod)
				   { 
				     break;
				   } 
			 }
		 if ($lb_valido)
		    {
			  $lb_valido = $in_classconciliacion->uf_procesar_conciliacion($aa_conciliacion,$ls_codemp,$ls_codban,$ls_cuenta_banco);
			}
		 if ($lb_valido)
		    {
			  $in_classconciliacion->io_sql->commit();
			  $msg->message("Registro guardado !!!");
			}		 
		 else
		    {
			  $in_classconciliacion->io_sql->rollback();
			  $msg->message( "".$in_classconciliacion->is_msg_error);
			}
		 ?>
		 <script>
		 location.href="sigesp_scb_p_conciliacion.php";
		 </script>
		 <?php
	   }//Fin GUARDAR
	
	function uf_cargar_montos_conciliacion($as_codban,$as_ctaban,$ld_fecha)
	{
		//////////////////////////////////////////////////////////////////////
		//  Function  :   uf_cargar_montos_conciliacion
		//  
		//  
		//  Descripcion: Funcion que invoca las funciones para el calculo de 
		//     			  los saldos para la conciliacion actual
		//////////////////////////////////////////////////////////////////////
		global $in_classfecha;
		global $in_classconciliacion;
		global $ldec_tranoreglib;
		global $ldec_salseglib;
		global $ldec_errorbco;
		global $ldec_errorlib;
		global $ldec_tranoregban;
		global $ldec_salsegban ;
		global $ldec_diferencia;
		if($ld_fecha!="")
		{
			$li_row=0;
			$ldec_salseglib=0;$ldec_salnoreglib=0;$ldec_tranoregban=0;$ldec_errorbco=0;$ldec_errorlib=0;$ldec_tranoreglib=0;
			$ls_mesano="";
			$ldt_fecfinal_mes="";
			$ldt_fecfinal_mes = $in_classfecha->uf_last_day(substr($ld_fecha,3,2),substr($ld_fecha,6,4));
			
			$ls_mesano        = substr($ld_fecha,3,2).substr($ld_fecha,6,4);
			$ldec_tranoreglib = $in_classconciliacion->uf_calcular_tranoreglib($as_codban,$as_ctaban,$ldt_fecfinal_mes);
			$ldec_salseglib   = $in_classconciliacion->uf_calcular_saldolibro($as_codban,$as_ctaban,substr($ldt_fecfinal_mes,3,2)."/".substr($ldt_fecfinal_mes,6,4));
			$ldec_errorbco    = $in_classconciliacion->uf_calcular_errorbco($as_codban,$as_ctaban,$ls_mesano);
			$ldec_errorlib    = $in_classconciliacion->uf_calcular_errorlib($as_codban,$as_ctaban,$ldt_fecfinal_mes);
			$ldec_tranoregban = $in_classconciliacion->uf_calcular_tranoregban($as_codban,$as_ctaban,$ldt_fecfinal_mes);
			$ldec_direrencia  = 0;		
			$ldec_diferencia  = ($ldec_salsegban+$ldec_errorbco+$ldec_tranoregban-$ldec_salseglib-$ldec_errorlib-$ldec_tranoreglib);
		}
	}//Fin uf_cargar_montos_conciliacion
	
	
	if($li_estcon==1)
	{
		$lb_chkconcil="checked";
	}
	else
	{
		$lb_chkconcil="";
	}	

    $lb_01=$lb_02=$lb_03=$lb_04=$lb_05=$lb_06=$lb_07=$lb_08=$lb_09=$lb_10=$lb_11=$lb_12="";
	switch ($ls_mes){
		case '01':
			$lb_01="selected";
			break;
		case '02':
			$lb_02="selected";			
			break;
		case '03':
			$lb_03="selected";
			break;
		case '04':
			$lb_04="selected";			
			break;
		case '05':
			$lb_05="selected";
			break;
		case '06':
			$lb_06="selected";
			break;	
		case '07':
			$lb_07="selected";
			break;
		case '08':
			$lb_08="selected";
			break;
		case '09':
			$lb_09="selected";
			break;
		case '10':
			$lb_10="selected";
			break;
		case '11':
			$lb_11="selected";
			break;
		case '12':
			$lb_12="selected";				
			break;
	}
?>
  <form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_banco->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_banco);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <p>&nbsp;</p>
  <p><br>
  </p>
  <table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-ventana" >
      <td height="22" colspan="4">Conciliaci&oacute;n Bancaria
      <input name="hidtotfilmod" type="hidden" id="hidtotfilmod" value="<?php echo $li_totfilmod ?>"></td>
    </tr>
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td  align="right">Periodo</td>
      <td  align="left">
          <input name="txtfecha" type="text" id="txtfecha" style="text-align:center" value="<?php print $ld_fecha;?>" size="10" maxlength="7" readonly>
          <span class="style1">Mes/A&ntilde;o</span>
          <select name="cmbmes" onChange="javascript: uf_periodo(this);">
            <option value="01" <?php print $lb_01;?>>ENERO</option>
            <option value="02" <?php print $lb_02;?>>FEBRERO</option>
            <option value="03" <?php print $lb_03;?>>MARZO</option>
            <option value="04" <?php print $lb_04;?>>ABRIL</option>
            <option value="05" <?php print $lb_05;?>>MAYO</option>
            <option value="06" <?php print $lb_06;?>>JUNIO</option>
            <option value="07" <?php print $lb_07;?>>JULIO</option>
            <option value="08" <?php print $lb_08;?>>AGOSTO</option>
            <option value="09" <?php print $lb_09;?>>SEPTIEMBRE</option>
            <option value="10" <?php print $lb_10;?>>OCTUBRE</option>
            <option value="11" <?php print $lb_11;?>>NOVIEMBRE</option>
            <option value="12" <?php print $lb_12;?>>DICIEMBRE</option>
          </select>
          <input name="txtperiodo" type="text" id="txtperiodo" value="<?php print $ls_periodo ?>" size="6" maxlength="4" style="text-align:center" readonly>
          <input type="hidden" name="hidorden" id="hidorden" value="<?php print $ls_orden?>"/></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="96" height="22"  align="right">Banco</td>
      <td colspan="3" align="left">
          <input name="txtcodban" type="text" id="txtcodban"  style="text-align:center" value="<?php print $ls_codban;?>" size="10" readonly>
          <a href="javascript:cat_bancos();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Bancos"></a>
          <input name="txtdenban" type="text" id="txtdenban" value="<?php print $ls_denban?>" size="100" class="sin-borde" readonly>      </td>
    </tr>
    <tr>
      <td height="22" align="right">Cuenta</td>
      <td colspan="3" align="left">
          <input name="txtcuenta" type="text" id="txtcuenta" style="text-align:center" value="<?php print $ls_cuenta_banco; ?>" size="30" maxlength="25" readonly>
          <a href="javascript:catalogo_cuentabanco();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Cuentas Bancarias"></a>
          <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion" style="text-align:left" value="<?php print $ls_dencuenta_banco; ?>" size="80" maxlength="254" readonly>
          <input name="txttipocuenta" type="hidden" id="txttipocuenta">
          <input name="txtdentipocuenta" type="hidden" id="txtdentipocuenta">     </td>
    </tr>
    <tr>
      <td height="22" align="right">Cuenta Contable </td>
      <td width="382" align="left">
          <input name="txtcuenta_scg" type="text" id="txtcuenta_scg" style="text-align:center" value="<?php print $ls_cuenta_scg;?>" size="24" readonly>      </td>
      <td width="83" align="right">Disponible</td>
      <td width="217" align="left">
          <input name="txtdisponible" type="text" id="txtdisponible" style="text-align:right" value="<?php print $ldec_disponible;?>" size="28" readonly>      </td>
    </tr>
    <tr>
      <td height="22" align="right">Cerrar Conciliaci&oacute;n </td>
      <td align="left">
        <input type="checkbox" name="chkcloseconcil" value="checkbox" style="width:15px; height:15px"  onClick="javascript:uf_verificar_conciliacion();" <?php print $lb_chkconcil; ?>>
		<input type="hidden" name="estcon" id="estcon" value="<?php print $lb_chkconcil;?>" >
      &nbsp;&nbsp;&nbsp;
	  <?php
	  if($lb_chkconcil=="checked")
	  {
	  ?>  
	 	 	<a  href="javascript:uf_abrir_conciliacion();">Abrir Conciliación</a>  
		<?php
		}
		?>	  </td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="38" colspan="4">
        <div align="center">
          <table width="476" border="0" cellpadding="0" cellspacing="0" class="formato-blancotabla">
            <tr>
              <td width="474"><a href="javascript: uf_cargar_dt();">Cargar Movimientos </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#01">Detalles Conciliaci&oacute;n</a>&nbsp;&nbsp; &nbsp;&nbsp;<a href="javascript:uf_reg_error('B');">Errores en Banco</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:uf_reg_error('L');"> Movimientos del mes </a></td>
            </tr>
          </table>
        </div></td>
    </tr>
    <tr>
      <td height="35" colspan="4"><table width="493" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td width="493">
		  <label>
            <input type="radio" name="filtro" id="filtro" value="T" class="sin-borde" onClick="javascript: uf_cambiar_dt();"  style="cursor:default" <?php print $ls_filtrot;?>>
            Todas </label>
              <label>
		  <label>
            <input type="radio" name="filtro" id="filtro" value="ND" class="sin-borde" onClick="javascript: uf_cambiar_dt();" style="cursor:default" <?php print $ls_filtrond;?>>
            Notas de D&eacute;bito </label>
              <label>
              <input type="radio" name="filtro" id="filtro" value="NC" class="sin-borde" onClick="javascript: uf_cambiar_dt();" style="cursor:default"  <?php print $ls_filtronc;?>>
                Notas de Cr&eacute;dito </label>
              <label>
              <input type="radio" name="filtro" id="filtro" value="DP" class="sin-borde" onClick="javascript: uf_cambiar_dt();"  style="cursor:default" <?php print $ls_filtrod;?>>
                Dep&oacute;sitos </label>
              <label>
              <input type="radio" name="filtro" id="filtro" value="RE" class="sin-borde"   onClick="javascript: uf_cambiar_dt();" style="cursor:default"<?php print $ls_filtror;?>>
                Retiros </label>
              <label>
              <input type="radio" name="filtro" id="filtro" value="CH" class="sin-borde" onClick="javascript: uf_cambiar_dt();"   style="cursor:default" <?php print $ls_filtroch;?>>
                Cheques </label>            </td>
        </tr>
      </table>      </td>
    </tr>
    <tr>
      <td height="23" colspan="4"><div align="center"><?php $io_grid->makegrid($li_rows,$title,$object,770,'Movimientos Bancarios a conciliar',$grid);
	  													uf_cargar_montos_conciliacion($ls_codban,$ls_cuenta_banco,$ld_desde);?>
        <input name="fila_selected" type="hidden" id="fila_selected">
        <input name="totalrows" type="hidden" id="totalrows" value="<?php print $li_rows;?>">
</div></td>
    </tr>
    <tr>
      <td height="13" colspan="4"><div align="center">
        <table width="770" border="0" cellpadding="0" cellspacing="0" class="formato-blancotabla">
          <tr>
            <td colspan="4" class="titulo-ventana"><span class="toolbar"><a name="01" id="01"></a></span>Detalles de la Conciliaci&oacute;n </td>
            </tr>
          <tr>
            <td width="199" height="22"><div align="right">Saldo segun Banco</div></td>
            <td width="120"><div align="left">
              <input name="txtsalsegban" type="text" id="txtsalsegban" style="text-align:right" value="<?php print number_format($ldec_salsegban,2,",",".");?>" onBlur="javascript:uf_format(this);javascript:uf_calcular();" onFocus="select()">
           </div></td>
            <td width="208"><div align="right">Saldo segun libro</div></td>
            <td width="125"><div align="left">
              <input name="txtsalseglib" type="text" id="txtsalseglib" style="text-align:right" value="<?php print number_format($ldec_salseglib,2,",",".");?>" readonly>
			</div></td>
          </tr>
          <tr>
            <td height="22"><div align="right">Transacciones No registradas en banco </div></td>
            <td>
              <div align="left">
                <input name="txttranoregban" type="text" id="txttranoregban" style="text-align:right" value="<?php print number_format($ldec_tranoregban,2,",",".");?>" readonly>
			  </div></td>
            <td><div align="right">Transacciones no registradas en libro </div></td>
            <td><div align="left">
              <input name="txttranoreglib" type="text" id="txttranoreglib" style="text-align:right" value="<?php print number_format($ldec_tranoreglib,2,",",".");?>" readonly>
            </div></td>
          </tr>
          <tr>
            <td height="22"><div align="right">Error en Banco </div></td>
            <td><div align="left">
              <input name="txterrorban" type="text" id="txterrorban" style="text-align:right" value="<?php print number_format($ldec_errorbco,2,",",".");?>" readonly>
			</div></td>
            <td><div align="right">Error en libro </div></td>
            <td><div align="left">
              <input name="txterrorlib" type="text" id="txterrorlib" style="text-align:right" value="<?php print number_format($ldec_errorlib,2,",",".");?>" readonly>
            </div></td>
          </tr>
          <tr>
            <td height="22"><div align="right">Saldo Movimientos Conciliados </div></td>
            <td><div align="left">
              <input name="txtsaldoconcil" type="text" id="txtsaldoconcil" style="text-align:right" value="<?php print number_format($ldec_montoconcil,2,",",".");?>" readonly>
            </div></td>
            <td><div align="right">Diferencia</div></td>
            <td><div align="left">
              <input name="txtdiferencia" type="text" id="txtdiferencia" style="text-align:right" value="<?php print number_format($ldec_diferencia,2,",",".");?>" readonly>
            </div></td>
          </tr>
        </table>
      </div></td>
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
f=document.form1;
function ue_nuevo()
{
	location.href="sigesp_scb_p_conciliacion.php";
}

function ue_imprimir()
{
f.action="prueba_reporte.php";
f.submit();
}
function ue_guardar()
{
	ls_codban=f.txtcodban.value;
	ls_cuenta=f.txtcuenta.value;
	ls_mesano=f.txtfecha.value;
	if((ls_codban!="")&&(ls_cuenta!="")&&(ls_mesano!=""))
	{
		f.operacion.value ="GUARDAR";
		f.action="sigesp_scb_p_conciliacion.php";
		f.submit();
	}
	else
	{
		alert("Complete los datos basicos de la conciliacion !!!");
	}
}

function uf_periodo(obj)
{
  ls_ano		   = f.txtperiodo.value;
  ls_periodo	   = obj.value;
  ls_periodo	   = ls_periodo+"/"+ls_ano;
  f.txtfecha.value = ls_periodo;
  uf_cambio();
}
function uf_cargar_dt()
{
	ls_codban=f.txtcodban.value;
	ls_ctaban=f.txtcuenta.value;
	ls_mesano=f.txtfecha.value;
	if((ls_codban!="")&&(ls_ctaban!="")&&(ls_mesano!=""))
	{
		f.operacion.value ="CARGAR_DT";
		f.action="sigesp_scb_p_conciliacion.php";
		f.submit();
	}
	else
	{
		alert("Complete los datos de la conciliacion (Banco , Cuenta , Periodo ) ");
	}
}

function uf_cambiar_dt()
{
	ls_codban=f.txtcodban.value;
	ls_ctaban=f.txtcuenta.value;
	ls_mesano=f.txtfecha.value;
	if((ls_codban!="")&&(ls_ctaban!="")&&(ls_mesano!=""))
	{
		f.operacion.value ="CARGAR_DT";
		f.action="sigesp_scb_p_conciliacion.php";
		f.submit();
	}
	else
	{
		alert("Complete los datos de la conciliacion (Banco , Cuenta , Periodo ) ");
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
		
	}
	
	//Catalogo de cuentas contables
	function catalogo_cuentabanco()
	 {
	   ls_codban=f.txtcodban.value;
	   ls_denban=f.txtdenban.value;
	   if (ls_codban!="")
		  {
		    pagina="sigesp_cat_ctabanco.php?codigo="+ls_codban+"&hidnomban="+ls_denban;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=700,height=400,resizable=yes,location=no");
		  }
	   else
		  {
		    alert("Seleccione el Banco");   
		  }
	 }
	 
	 function catalogo_cuentascg()
	 {
	   pagina="sigesp_cat_filt_scg.php?filtro="+'11102'+"&opener=sigesp_scb_d_colocacion.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
	 }
	 	 
	 function cat_bancos()
	 {
	   pagina="sigesp_cat_bancos.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
	 }

	function cat_conceptos()
	{
	   pagina="sigesp_cat_conceptos.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
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
			//alert(ls_long);


  //  return false; 
   }
   
function catprovbene()
{
	if(f.rb_provbene[0].checked==true)
	{
		f.txtprovbene.disabled=false;	
		window.open("sigesp_cat_prog_proveedores.php","Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=565,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else if(f.rb_provbene[1].checked==true)
	{
		f.txtprovbene.disabled=false;	
		window.open("sigesp_cat_prog_beneficiario.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=565,height=400,left=50,top=50,location=no,resizable=yes");
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

    function uf_format(obj)
   {
		ldec_monto=uf_convertir(obj.value);
		obj.value=ldec_monto;
   }

	function uf_selected(li_fila)
	{
	  uf_calcular();
	  li_totfilmod = f.hidtotfilmod.value;
	  if (eval("f.hidchange"+li_fila+".value==0"))
	     {
		   li_totfilmod++;
		   f.hidtotfilmod.value = li_totfilmod; 
		 }
	  eval("f.hidchange"+li_fila+".value=1"); 				
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
			eval("f.txtmonto"+li_i+".value='"+uf_convertir(ldec_monto)+"'");
		}
		else
		{
			alert("Monto a cancelar no puede ser mayor al monto pendiente");
			eval("f.txtmonto"+li_i+".value="+ldec_montopendiente);	
			eval("f.txtmonto"+li_i+".focus()");
		}
		uf_calcular();
	}
	
	function uf_calcular()
	{
		li_total=f.totalrows.value;
		ldec_tranoregban=0;
		ldec_saldo_conciliado=0;
		ldec_total=0;
		for(i=1;i<=li_total;i++)
		{
			ldec_monto = eval("f.txtmonto"+i+".value;");
			while(ldec_monto.indexOf('.')>0)
			{//Elimino todos los puntos o separadores de miles
				ldec_monto=ldec_monto.replace(".","");
			}
			ls_numdoc           = eval("f.txtnumdoc"+i+".value;");
			ldec_monto          = ldec_monto.replace(",",".");
			ls_estmov           = eval("f.txtestmov"+i+".value;");
			ls_estreglib        = eval("f.txtestreglib"+i+".value;");
			ls_codope           = eval("f.txtcodope"+i+".value;");
			lb_chk              = eval("f.chk"+i+".checked;");
			ls_numcarord        = eval("f.txtnumcarord"+i+".value;");
			ldec_saldo_concneg  = 0;
			ldec_tranoregbanneg = 0;
			if( ( !lb_chk )  && ( ls_estreglib != 'B' ) )
			{
				  if(( ls_estmov !='A' ))
				  {
					if((ls_codope=='DP')||(ls_codope=='NC'))
					{	
						ldec_tranoregban = parseFloat(ldec_tranoregban) + parseFloat(ldec_monto);
					}
					else
					{
						ldec_tranoregban = parseFloat(ldec_tranoregban) - parseFloat(ldec_monto);				
					}
				  }	
				 else
				 {				
					if((ls_codope=='DP')||(ls_codope=='NC'))
					{	
						ldec_tranoregbanneg = parseFloat(ldec_tranoregbanneg) + parseFloat(ldec_monto);
					}
					else
					{
						ldec_tranoregbanneg = parseFloat(ldec_tranoregbanneg) - parseFloat(ldec_monto);				
					}					
				 }
			}		  
			
			ldec_tranoregban = ldec_tranoregban - ldec_tranoregbanneg;
			ldec_acumanulado=0;		
			
			if((lb_chk) &&  (ls_estreglib !='B'))
			{
				 if(( ls_estmov !='A' ))
				  {				
						if( (ls_codope == 'DP')||(ls_codope=='NC'))
						{
							ldec_saldo_conciliado = parseFloat(ldec_saldo_conciliado) + parseFloat(ldec_monto);
						}
						else
						{
							ldec_saldo_conciliado = parseFloat(ldec_saldo_conciliado) - parseFloat(ldec_monto);
						}
				  }
				  else
				  {
						if((ls_codope=='DP')||(ls_codope=='NC'))
						{	
							ldec_saldo_concneg = parseFloat(ldec_saldo_concneg) + parseFloat(ldec_monto);
						}
						else
						{
						   ldec_saldo_concneg = parseFloat(ldec_saldo_concneg) - parseFloat(ldec_monto);
						}	
				  }
					
			}
			ldec_saldo_conciliado = parseFloat(ldec_saldo_conciliado) - parseFloat(ldec_saldo_concneg);
		}//End for
		f.txttranoregban.value = uf_convertir(ldec_tranoregban);
		ldec_saldo_conciliado  = roundNumber(ldec_saldo_conciliado);
		f.txtsaldoconcil.value = convertir_formato_moneda(ldec_saldo_conciliado);
		
		ldec_tranoreglib = f.txttranoreglib.value;
		while(ldec_tranoreglib.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			ldec_tranoreglib=ldec_tranoreglib.replace(".","");
		}
		ldec_tranoreglib=ldec_tranoreglib.replace(",",".");
		
		ldec_err_banco   = f.txterrorban.value;
		while(ldec_err_banco.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			ldec_err_banco=ldec_err_banco.replace(".","");
		}
		ldec_err_banco=ldec_err_banco.replace(",",".");
		
		ldec_err_libro   = f.txterrorlib.value;
		while(ldec_err_libro.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			ldec_err_libro=ldec_err_libro.replace(".","");
		}
		ldec_err_libro=ldec_err_libro.replace(",",".");
		
		ldec_saldo_libro = f.txtsalseglib.value;
		while(ldec_saldo_libro.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			ldec_saldo_libro=ldec_saldo_libro.replace(".","");
		}		
		ldec_saldo_libro=ldec_saldo_libro.replace(",",".");		
		
		ldec_saldo_banco = f.txtsalsegban.value;
		while(ldec_saldo_banco.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			ldec_saldo_banco=ldec_saldo_banco.replace(".","");
		}
		ldec_saldo_banco=ldec_saldo_banco.replace(",",".");				
		
		ldec_diferencia= parseFloat(ldec_saldo_banco) + parseFloat(ldec_err_banco) +parseFloat(ldec_tranoregban) -  parseFloat(ldec_saldo_libro) - parseFloat(ldec_err_libro) - parseFloat(ldec_tranoreglib );
		f.txtdiferencia.value=uf_convertir(ldec_diferencia);
	}
   
  	function uf_verificar_conciliacion()
	{
		ldec_diferencia = f.txtdiferencia.value;
		ldec_salsegban  = f.txtsalsegban.value;
		ldec_saldoconcil= f.txtsaldoconcil.value;
		li_total=f.totalrows.value;
		lb_chk=true;
		for(i=1;i<=li_total;i++)
		{
			if(!eval("f.chk"+i+".checked;"))
				lb_chk=false;
		}

		while(ldec_diferencia.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			ldec_diferencia=ldec_diferencia.replace(".","");
		}
		ldec_diferencia=ldec_diferencia.replace(",",".");				
		
		while(ldec_salsegban.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			ldec_salsegban=ldec_salsegban.replace(".","");
		}
		ldec_salsegban=ldec_salsegban.replace(",",".");				

		while(ldec_saldoconcil.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			ldec_saldoconcil=ldec_saldoconcil.replace(".","");
		}
		ldec_saldoconcil=ldec_saldoconcil.replace(",",".");				
		
		lb_chkconcil=f.chkcloseconcil.checked;
		if((lb_chkconcil)&&(ldec_diferencia!=0))
		{
			alert("No puede cerrar la conciliacion, aun no esta cuadrada..");
			f.chkcloseconcil.checked=false;
		}
		else
		{
			//if((ldec_salsegban!=0)&&(ldec_diferencia==0))
			if(ldec_diferencia==0)
			{
				
			}
			else
			{
				alert("No ha seleccionado movimientos a conciliar, o falta el saldo según banco");
				f.chkcloseconcil.checked=false;
			}
		}
		
		if(f.estcon.value=="checked")
		{
			f.chkcloseconcil.checked=true;
		}

	}
  
 function  uf_reg_error(tip_mov)
 {
	ls_codban=f.txtcodban.value;
	ls_cuenta=f.txtcuenta.value;
	ls_nomban=f.txtdenban.value;
	ls_dencta=f.txtdenominacion.value;
	ls_cuenta_scg=f.txtcuenta_scg.value;
	ldec_disponible=f.txtdisponible.value;
	ls_mesano=f.txtfecha.value;
	if((ls_codban!="")&&(ls_cuenta!="")&&(ls_mesano!=""))
	{
		if(tip_mov=='L')
		{
			ls_pagina="sigesp_scb_p_concilerror.php?tip_mov=L&nombre=Errores en Libro&txtcodban="+ls_codban+"&txtdenban="+ls_nomban+"&txtcuenta="+ls_cuenta+"&txtdenominacion="+ls_dencta+"&txtcuenta_scg="+ls_cuenta_scg+"&txtdisponible="+ldec_disponible+"&mesano="+ls_mesano;
			window.open(ls_pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
		}
		if(tip_mov=='B')
		{
			ls_pagina="sigesp_scb_p_concilerror.php?tip_mov=B&nombre=Errores en Banco&txtcodban="+ls_codban+"&txtdenban="+ls_nomban+"&txtcuenta="+ls_cuenta+"&txtdenominacion="+ls_dencta+"&txtcuenta_scg="+ls_cuenta_scg+"&txtdisponible="+ldec_disponible+"&mesano="+ls_mesano;
			window.open(ls_pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
		}
	}
	else
	{
		alert("Complete los datos de Banco, Cuenta y Periodo");
	}
 }
 function uf_cambio()
 {
	ls_codban = f.txtcodban.value;
	ls_ctaban = f.txtcuenta.value;
	if ((ls_codban!="") && (ls_ctaban!=""))
	   {
	     li_total=f.totalrows.value;
		 f.operacion.value ="CARGAR_DT";
		 f.action="sigesp_scb_p_conciliacion.php";
		 f.submit(); 
	   }
 }

function uf_select_all()
{
	  total=f.totalrows.value;
	  sel_all=f.chkall.value;
	  ls_fecha=f.txtfecha.value;
	  lb_close=f.chkcloseconcil.checked;
	  li_row=0;
	  li_totfilmod = 0;
	  if(!lb_close)	
	  {	  
		  if(f.chkall.checked)
		  {
			  for(i=1;i<=total;i++)	
			  {
				if((eval("f.txtfeccon"+i+".value")=='01/01/1900')||(eval("f.txtfeccon"+i+".value")=='01/'+ls_fecha))
				{
					eval("f.chk"+i+".checked=true");
					eval("f.hidchange"+i+".value=1");
					li_totfilmod++;
				}
			  }		  
		 }
		 else
		 {
			 for(i=1;i<=total;i++)	
			  {
				if((eval("f.txtfeccon"+i+".value")=='01/01/1900')||(eval("f.txtfeccon"+i+".value")=='01/'+ls_fecha))
				{
					eval("f.chk"+i+".checked=false");
					eval("f.hidchange"+i+".value=1");
					li_totfilmod++;
				}	
			  }		  
		 }
		 f.hidtotfilmod.value = li_totfilmod;
		 uf_calcular();
	}
}
  
function uf_abrir_conciliacion()
{
	if(f.chkcloseconcil.checked)
	{
		if(confirm("Está a punto de abrir la conciliación. Está seguro?"))
		{
			f.operacion.value="ABRIR_CONCILIACION";
			f.submit();
		}
	}
}

function cargar_detalle(ls_parametro)
{
  f.operacion.value = "CARGAR_DT";
  ue_ordenar(ls_parametro);
}

function convertir_formato_moneda(Num) 
{ 
  f = document.form1;
  esnegativo="";
  if(Num<0)
  {
  	esnegativo="-";
  }
  Num=Math.abs(Num);
  Num = "" + Num;
  dec = Num.indexOf(".");
  end = ((dec > -1) ? "" + Num.substring(dec,Num.length) : ",00");
  end = end.replace(".",",");
  Num = "" + parseInt(Num);
  var temp1 = "";
  var temp2 = "";
  
  if (end.length == 2) end += "0";
  if (end.length == 1) end += "00";
  if (end == "") end += ",00";
  var count = 0;
  for (var k = Num.length-1; k >= 0; k--) 
      {
        var oneChar = Num.charAt(k);
        if (count == 3) 
		   {
             temp1 += ".";
             temp1 += oneChar;
             count = 1;
             continue;
           }
         else 
		   {
             temp1 += oneChar;
			 count ++;
           }
	  }
  for (var k = temp1.length-1; k >= 0; k--) 
      {
        var oneChar = temp1.charAt(k);
		temp2 += oneChar;
      }
  ld_monto = esnegativo+temp2 + end;
  return ld_monto;
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>