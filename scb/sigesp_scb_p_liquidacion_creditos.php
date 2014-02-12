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
$io_fun_scb = new class_funciones_banco();
$io_fun_scb->uf_load_seguridad("SCB","sigesp_scb_p_liquidacion_creditos.php",$ls_permisos,$la_seguridad,$la_permisos);

function uf_limpiar_variables()
{
	//////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_limpiar_variables.
	//		   Access: private
	//	  Description: Función que limpia todas las variables necesarias en la página
	//	   Creado Por: Ing. Néstor Falcón.
	// Fecha Creación: 22/07/2007			Fecha Última Modificación : 22/07/2008. 
	////////////////////////////////////////////////////////////////////////////////////
    global $ls_codban,$ls_nomban,$ls_ctaban,$ls_denctaban,$ls_numdoc,$io_fun_scb,$ls_operacion,$ls_cedben,$ls_nomben,$ls_fecmov;
    global $ld_mondoc,$ls_rutfil,$la_object,$li_totrows,$la_rowgri,$ls_codope,$ls_scgcta,$li_totrowscg,$la_objscg,$la_titscg;
	global $ls_chevau,$ls_conmov,$ls_disabled,$ls_docnum,$ls_nombenalt,$ld_monret,$li_estciespg,$li_estciespi,$li_estciescg;
	
	$ls_codban    = "";
	$ls_nomban    = "";
	$ls_ctaban    = "";
	$ls_denctaban = "";
	$ls_fecmov    = "";
	$ls_cedben    = "";
	$ls_nomben    = $ls_nombenalt = "";
	$ls_numdoc    = $ls_docnum = "";
	$ls_codope    = "";
	$ls_scgcta    = "";
	$ld_mondoc    = "0,00";
	$ls_filnam    = "";
	$ls_conmov    = "";	
	$ls_chevau    =	""; 
	$ls_disabled  = "disabled";
	$li_filsel    = $ld_monret = 0;
	$li_totrows   = $li_totrowscg = 1;	
	$ls_rutfil    = "../scc/liquidacion/pendientes"; 
	
	$la_rowgri[1] = "Cuenta";
	$la_rowgri[2] = "Denominación";
	$la_rowgri[3] = "Estructura Presupuestaria";
	$la_rowgri[4] = "Tipo";
	$la_rowgri[5] = "Monto";

	$la_titscg[1] = "Cuenta";
	$la_titscg[2] = "Denominación";
	$la_titscg[3] = "Debe/Haber";
	$la_titscg[4] = "Monto";
	
	$ls_operacion = $io_fun_scb->uf_obteneroperacion();
	
	if (isset($la_object) || !empty($la_object))
	   {
	     unset($la_object);
	   }
	if (isset($la_objscg) || !empty($la_objscg))
	   {
	     unset($la_objscg);
	   }
	
	if ($ls_operacion=="NUEVO")
	   { 
	     $la_object[$li_totrows][1] = "<input type=text    name=txtspgcta".$li_totrows."    id=txtspgcta".$li_totrows."    value='' class=sin-borde readonly style=text-align:center  size=15 maxlength=25>";
	     $la_object[$li_totrows][2] = "<input type=text    name=txtdenctaspg".$li_totrows." id=txtdenctaspg".$li_totrows." value='' class=sin-borde readonly style=text-align:center  size=45 maxlength=254>";
		 $la_object[$li_totrows][3] = "<input type=text    name=txtcodestpro".$li_totrows." id=txtcodestpro".$li_totrows." value='' class=sin-borde readonly style=text-align:left    size=30 maxlength=254>";
	     $la_object[$li_totrows][4] = "<input type=text    name=txtcodtipest".$li_totrows." id=txtcodtipest".$li_totrows." value='' class=sin-borde readonly style=text-align:center  size=10 maxlength=254>";
		 $la_object[$li_totrows][5] = "<input type=text    name=txtmondetspg".$li_totrows." id=txtmondetspg".$li_totrows." value='' class=sin-borde readonly style=text-align:right   size=17 maxlength=23>
								       <input type=hidden  name=txtfilnam".$li_totrows."    id=txtfilnam".$li_totrows."    value=''>";		

		 $la_objscg[$li_totrows][1] = "<input type=text    name=txtscgcta".$li_totrows."     id=txtscgcta".$li_totrows."    value='' class=sin-borde readonly style=text-align:center  size=15 maxlength=25>";
		 $la_objscg[$li_totrows][2] = "<input type=text    name=txtdenscgcta".$li_totrows."  id=txtdenscgcta".$li_totrows." value='' class=sin-borde readonly style=text-align:center  size=71 maxlength=500>";
		 $la_objscg[$li_totrows][3] = "<input type=text    name=txtdebhab".$li_totrows."     id=txtdebhab".$li_totrows."    value='' class=sin-borde readonly style=text-align:center  size=20>";
		 $la_objscg[$li_totrows][4] = "<input type=text    name=txtmonscg".$li_totrows."     id=txtmonscg".$li_totrows."    value='' class=sin-borde readonly style=text-align:center  size=17>
		 							   <input type=hidden  name=txtdesmovscg".$li_totrows."  id=txtdesmovscg".$li_totrows." value=''>";
	   }
			$li_estciespg = $io_fun_scb->uf_load_estatus_cierre($li_estciespi,$li_estciescg);
			if ($li_estciescg==1 || $li_estciespg==1 || $li_estciespi==1)
			{
				require_once("../shared/class_folder/class_mensajes.php");
				$io_msg = new class_mensajes();
				$io_msg->message("Ya fué procesado el Cierre Presupuestario, No pueden efectuarse movimientos, Contacte al Administrador del Sistema !!!");
				unset($io_msg);
			}  
        
}

function uf_clear_grid()
{
	//////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_clear_grid.
	//		   Access: private
	//	  Description: Función que limpia los grid de Detalles.
	//	   Creado Por: Ing. Néstor Falcón.
	// Fecha Creación: 22/07/2007			Fecha Última Modificación : 22/07/2008. 
	////////////////////////////////////////////////////////////////////////////////////

    global $la_object,$la_objscg,$li_totrows,$li_totrowscg;
  	
	$li_totrows = $li_totrowscg = 1;
	$la_object[$li_totrows][1] = "<input type=text    name=txtspgcta".$li_totrows."    id=txtspgcta".$li_totrows."    value='' class=sin-borde readonly style=text-align:center  size=15 maxlength=25>";
	$la_object[$li_totrows][2] = "<input type=text    name=txtdenctaspg".$li_totrows." id=txtdenctaspg".$li_totrows." value='' class=sin-borde readonly style=text-align:center  size=45 maxlength=254>";
	$la_object[$li_totrows][3] = "<input type=text    name=txtcodestpro".$li_totrows." id=txtcodestpro".$li_totrows." value='' class=sin-borde readonly style=text-align:left    size=30 maxlength=254>";
	$la_object[$li_totrows][4] = "<input type=text    name=txtcodtipest".$li_totrows." id=txtcodtipest".$li_totrows." value='' class=sin-borde readonly style=text-align:center  size=10 maxlength=254>";
	$la_object[$li_totrows][5] = "<input type=text    name=txtmondetspg".$li_totrows." id=txtmondetspg".$li_totrows." value='' class=sin-borde readonly style=text-align:right   size=17 maxlength=23>
							      <input type=hidden  name=txtfilnam".$li_totrows."    id=txtfilnam".$li_totrows."    value=''>";		
	
	$la_objscg[$li_totrowscg][1] = "<input type=text    name=txtscgcta".$li_totrowscg."     id=txtscgcta".$li_totrowscg."    value='' class=sin-borde readonly style=text-align:center  size=15 maxlength=25>";
	$la_objscg[$li_totrowscg][2] = "<input type=text    name=txtdenscgcta".$li_totrowscg."  id=txtdenscgcta".$li_totrowscg." value='' class=sin-borde readonly style=text-align:center  size=71 maxlength=500>";
	$la_objscg[$li_totrowscg][3] = "<input type=text    name=txtdebhab".$li_totrowscg."     id=txtdebhab".$li_totrowscg."    value='' class=sin-borde readonly style=text-align:center  size=20>";
	$la_objscg[$li_totrowscg][4] = "<input type=text    name=txtmonscg".$li_totrowscg."     id=txtmonscg".$li_totrowscg."    value='' class=sin-borde readonly style=text-align:center  size=17>
							        <input type=hidden  name=txtdesmovscg".$li_totrowscg."  id=txtdesmovscg".$li_totrowscg." value=''>";
}

function uf_load_variables($aa_seguridad)
{
	//////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_load_variables
	//		   Access: private
	//	  Description: Función que carga todas las variables necesarias en la página
	//	   Creado Por: Ing. Néstor Falcón.
	// Fecha Creación: 22/07/2007		   Fecha Última Modificación : 22/07/2007.
	//////////////////////////////////////////////////////////////////////////////
	global $li_totrows,$ls_filnam,$ls_numdoc,$ls_codban,$ls_ctaban,$ls_nomban,$ls_denctaban,$ls_cedben,$ls_nomben,$ls_docnum;
	global $ls_nombenalt,$ls_codope,$ls_scgcta,$ls_fecmov,$ld_mondoc,$li_totrowscg,$ls_conmov,$ls_disabled,$io_fun_scb,$ls_chevau;
	global $lb_existe,$ld_monret;
	
	$ls_codban    = $_POST["txtcodban"];
	$ls_nomban    = $_POST["txtnomban"];
	$ls_ctaban    = $_POST["txtctaban"];
	$ls_denctaban = $_POST["txtdenctaban"];
	$ls_cedben    = $_POST["txtcedben"];
	$ls_nomben    = $_POST["txtnomben"];
	$ls_nombenalt = $_POST["hidnombenalt"];
	$ls_numdoc    = $_POST["txtnumdoc"];
	$ls_docnum    = $_POST["hiddocnum"];	
	$ls_filnam    = $_POST["hidfilnam"];
	$li_totrows   = $_POST["hidtotrow"];
	$li_totrowscg = $_POST["hidtotrowscg"];
	$ls_codope    = $_POST["hidcodope"];
	$ld_monret    = $_POST["hidmonret"];	
	$lb_existe    = false;
	if ($ls_codope=='CH')
	   {
	   	 require_once("sigesp_scb_c_movbanco.php");
	     $io_movbco = new sigesp_scb_c_movbanco($aa_seguridad);
		 
		 $ls_disabled = "";
		 $ls_operacion = $io_fun_scb->uf_obteneroperacion();
		 if ($ls_operacion=='CARGAR_DT')
		    {
		      $ls_chevau = $io_movbco->uf_generar_voucher($_SESSION["la_empresa"]["codemp"]);
			}
	     elseif($ls_operacion=='PROCESAR')
		    {
			  $ls_chevau = $_POST["txtchevau"];
			  $lb_existe = $io_movbco->uf_select_voucher($ls_chevau);
		      if ($lb_existe)
		         {
			       require_once("../shared/class_folder/class_mensajes.php");
				   $io_msg = new class_mensajes();
				   
				   $io_msg->message("Nº de Voucher ya existe, favor indicar otro");
			       unset($io_msg);
				 }
	        }
	     unset($io_movbco);
	   }
	else
	   {
	     $ls_disabled = "disabled";
	   }
	$ls_fecmov    = $_POST["txtfecmov"];
	$ld_mondoc    = $_POST["txtmonmov"];
	$ls_conmov    = $_POST["txtconmov"];
	$ls_scgcta    = $_POST["hidscgcta"];//Cuenta Contable asociada a la Cuenta Bancaria.
	$li_estciespg = $_POST["hidestciespg"];//Estatus del Cierre Presupuestario de Gasto.
	$li_estciespi = $_POST["hidestciespi"];//Estatus del Cierre Presupuestario de Ingresos.
}

function uf_load_grid($ai_totrows,&$la_object,$ai_totrowscg,&$la_objscg)
{
	//////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_load_grid
	//		   Access: private
	//	  Description: Función que carga todo el objeto grid.
	//	   Creado Por: Ing. Néstor Falcón.
	// Fecha Creación: 08/07/2007		   Fecha Última Modificación : 08/07/2007.
	//////////////////////////////////////////////////////////////////////////////
	
	for ($li_i=1;$li_i<=$ai_totrows;$li_i++)
	    {
		  $ls_spgcta    = $_POST["txtspgcta".$li_i];
		  $ls_codestpro = $_POST["txtcodestpro".$li_i];
		  $ls_estcla    = $_POST["txtcodtipest".$li_i];
		  $ls_denctaspg = $_POST["txtdenctaspg".$li_i];
		  $ld_mondetspg = $_POST["txtmondetspg".$li_i];
		  $ls_codestpre = $_POST["hidcodestpre".$li_i];
		  
	      $la_object[$li_i][1] = "<input type=text    name=txtspgcta".$li_i."    id=txtspgcta".$li_i."     value='".$ls_spgcta."'    class=sin-borde readonly style=text-align:center  size=15 maxlength=25>";
	      $la_object[$li_i][2] = "<input type=text    name=txtdenctaspg".$li_i." id=txtdenctaspg".$li_i."  value='".$ls_denctaspg."' class=sin-borde readonly style=text-align:left    size=45 maxlength=254 title='".$ls_denctaspg."'>";
	      $la_object[$li_i][3] = "<input type=text    name=txtcodestpro".$li_i." id=txtcodestpro".$li_i."  value='".$ls_codestpro."' class=sin-borde readonly style=text-align:center  size=30 maxlength=254>";
	      $la_object[$li_i][4] = "<input type=text    name=txtcodtipest".$li_i." id=txtcodtipest".$li_i."  value='".$ls_estcla."'    class=sin-borde readonly style=text-align:center  size=10 maxlength=254>";
		  $la_object[$li_i][5] = "<input type=text    name=txtmondetspg".$li_i." id=txtmondetspg".$li_i."  value='".$ld_mondetspg."' class=sin-borde readonly style=text-align:right   size=17 maxlength=23>
		  						  <input type=hidden  name=hidcodestpre".$li_i." id=hidcodestpre".$li_i."  value='".$ls_codestpre."'>";		
		}
    
	for ($li_y=1;$li_y<=$ai_totrowscg;$li_y++)
	    {
		  $ls_scgcta    = $_POST["txtscgcta".$li_y];
		  $ls_denctascg = $_POST["txtdenscgcta".$li_y];
		  $ls_debhab    = $_POST["txtdebhab".$li_y];
		  $ld_mondetscg = $_POST["txtmonscg".$li_y];
		  
		  $la_objscg[$li_y][1] = "<input type=text    name=txtscgcta".$li_y."     id=txtscgcta".$li_y."    value='".$ls_scgcta."'    class=sin-borde readonly style=text-align:center  size=15 maxlength=25>";
		  $la_objscg[$li_y][2] = "<input type=text    name=txtdenscgcta".$li_y."  id=txtdenscgcta".$li_y." value='".$ls_denctascg."' class=sin-borde readonly style=text-align:left    size=71 maxlength=500>";
		  $la_objscg[$li_y][3] = "<input type=text    name=txtdebhab".$li_y."     id=txtdebhab".$li_y."    value='".$ls_debhab."'    class=sin-borde readonly style=text-align:center  size=20>";
		  $la_objscg[$li_y][4] = "<input type=text    name=txtmonscg".$li_y."     id=txtmonscg".$li_y."    value='".$ld_mondetscg."' class=sin-borde readonly style=text-align:right    size=17>";		
		}
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="../shared/css/general.css"  rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css"   rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_scb.js"></script>
<title>Liquidaci&oacute;n de Cr&eacute;ditos</title>
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
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="780" height="40"></td>
  </tr>
  <tr>
  <td height="20" colspan="10" bgcolor="#E7E7E7">
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
    <td height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
	<td class="toolbar" width="22"><a href="javascript: uf_procesar_liquidacion();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Guardar" title="Guardar" width="20" height="20" border="0" /></a></td>
    <td class="toolbar" width="24"><div align="center"><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a></div></td>
    <td width="734" class="toolbar">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<?php
require_once("../shared/class_folder/grid_param.php");
require_once("class_folder/sigesp_scb_c_liquidacion_creditos.php");
$io_scb  = new sigesp_scb_c_liquidacion_creditos("../");
$io_grid = new grid_param();

uf_limpiar_variables();
switch ($ls_operacion){
  case 'CARGAR_DT':
	uf_load_variables($la_seguridad);
	$la_object = $io_scb->uf_load_detalles_spg($ls_rutfil.'/'.$ls_filnam,$li_totrows,$la_datscg,$ld_montotspg);
	if (!empty($la_datscg) && !empty($la_object))
	   {
	     $la_objscg = $io_scb->uf_print_detalles_scg($ls_rutfil.'/'.$ls_filnam,$la_datscg,$li_totrowscg,$ls_codope,$ls_codban,$ls_ctaban,$ls_scgcta,$ld_montotspg,$ld_monret);
	     if ($li_totrowscg<2)
		    {
			  uf_limpiar_variables();
			  uf_clear_grid();
			}
	   }
    else
	   {
	     uf_limpiar_variables();
		 uf_clear_grid();
	   }
  break;
  case 'PROCESAR':
    uf_load_variables($la_seguridad);
	uf_load_grid($li_totrows,$la_object,$li_totrowscg,$la_objscg);
	$la_datos["codban"] = $ls_codban;
	$la_datos["ctaban"] = $ls_ctaban;
	$la_datos["numdoc"] = $ls_numdoc;
	$la_datos["codope"] = $ls_codope;
	$la_datos["cedben"] = $ls_cedben;
	$la_datos["nomben"] = $ls_nomben;
	$la_datos["nombenalt"] = $ls_nombenalt;
	$la_datos["fecmov"] = $ls_fecmov;
	$la_datos["mondoc"] = $ld_mondoc;
	$la_datos["conmov"] = $ls_conmov;	
	$la_datos["chevau"] = $ls_chevau;
	$la_datos["docnum"] = $ls_docnum;
	$la_datos["monret"] = $ld_monret;
	if (!$lb_existe)
	   {
		 $lb_valido = $io_scb->uf_procesar_liquidacion($ls_rutfil.'/'.$ls_filnam,$la_datos,$li_totrows,$li_totrowscg,$la_seguridad);
		 if ($lb_valido)
		    {
			  uf_limpiar_variables();
			  uf_clear_grid();
		    }
	   }
  break;
}
?>
<form id="sigesp_scb_p_liquidacion_creditos.php" name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_scb->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_scb);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <table width="557" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-ventana">
      <td height="22" colspan="5" class="titulo-ventana"><input name="hidmesabi" type="hidden" id="hidmesabi" value="true" />
      <input name="operacion" type="hidden" id="operacion" value="<?php echo $ls_operacion ?>" />
      Liquidaci&oacute;n de Cr&eacute;ditos
        <input name="hidfilnam" type="hidden" id="hidfilnam" value="<?php echo $ls_filnam ?>" />
        <input name="hiddocnum" type="hidden" id="hiddocnum" value="<?php echo $ls_docnum ?>" />
        <input name="hidmonret" type="hidden" id="hidmonret" value="<?php echo $ld_monret ?>" />
        <input name="hidestciespg" type="hidden" id="hidestciespg" value="<?php echo $li_estciespg; ?>" />
        <input name="hidestciespi" type="hidden" id="hidestciespi" value="<?php echo $li_estciespi; ?>" /></td>
    </tr>
    <tr>
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr>
      <td width="70" height="22" style="text-align:right">Banco</td>
      <td height="22" colspan="4"><input name="txtcodban" type="text" id="txtcodban" style="text-align:center" value="<?php echo $ls_codban ?>" size="6" maxlength="3" readonly /> 
        <a href="javascript:uf_load_catalogo_bancos();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" /></a> 
        <input name="txtnomban" type="text" class="sin-borde" id="txtnomban" value="<?php echo $ls_nomban ?>" size="78" maxlength="254" readonly />
      </label></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Cuenta</td>
      <td height="22" colspan="4"><input name="txtctaban" type="text" id="txtctaban" style="text-align:center" value="<?php echo $ls_ctaban ?>" size="30" readonly />        
      <a href="javascript:uf_load_catalogo_cuentas();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" /></a> 
        <input name="txtdenctaban" type="text" class="sin-borde" id="txtdenctaban" value="<?php echo $ls_denctaban ?>" size="60" maxlength="254" readonly />
      </label></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Documento</td>
      <td width="117" height="22"><label>
        <input name="txtnumdoc" type="text" id="txtnumdoc" style="text-align:center" onBlur="rellenar_cadena(this.value,15,'numdoc')" value="<?php echo $ls_numdoc ?>" size="20" maxlength="15" onKeyPress="return keyRestrict(event,'0123456789'+'abcdefghijklmnñopqrstuvwxyz'+'-/'); " />
      </label></td>
      <td width="140" height="22" style="text-align:right">&nbsp;</td>
      <td width="44" height="22" style="text-align:right">Voucher</td>
      <td width="184" height="22"><label>
        <input name="txtchevau" type="text" id="txtchevau" onBlur="javascript:rellenar_cadena(this.value,25,'voucher');" onKeyPress="return keyRestrict(event,'0123456789'); " value="<?php echo $ls_chevau ?>" size="28" maxlength="25" style="text-align:center" <?php echo $ls_disabled ?> />
      </label></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Fecha</td>
      <td height="22"><span style="text-align:left">
        <input name="txtfecmov" type="text" id="txtfecmov" value="<?php echo $ls_fecmov ?>" size="20" maxlength="10" style="text-align:center"/>
      <?php
	    if ($ls_operacion=='CARGAR_DT')
		   {
	  ?>
	         <script language="javascript">uf_validar_estatus_mes();</script>
	  <?php
	       }
	  ?>
	  </span></td>
      <td height="22">&nbsp;</td>
      <td height="22" style="text-align:right">Monto</td>
      <td height="22"><input name="txtmonmov" type="text" id="txtmonmov" value="<?php echo $ld_mondoc ?>" size="28" readonly="readonly" style="text-align:right"/>
      <input name="hidcodope" type="hidden" id="hidcodope" value="<?php echo $ls_codope ?>" /></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Beneficiario</td>
      <td height="22" colspan="4"><input name="txtcedben" type="text" id="txtcedben" value="<?php echo $ls_cedben ?>" size="20" maxlength="10" readonly style="text-align:center" /> <label>
        <input name="txtnomben" type="text" class="sin-borde" id="txtnomben" value="<?php echo $ls_nomben ?>" size="65" maxlength="254" readonly style="text-align:left" />
        <input name="hidscgcta" type="hidden" id="hidscgcta" value="<?php echo $ls_scgcta ?>" />
        <input name="hidnombenalt" type="hidden" id="hidnombenalt" value="<?php echo $ls_nombenalt ?>" />
      </label></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Concepto</td>
      <td height="22" colspan="4"><label>
        <input name="txtconmov" type="text" id="txtconmov" style="text-align:left" value="<?php echo $ls_conmov ?>" size="85" maxlength="500" />
      </label></td>
    </tr>
    <tr>
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="5"><div align="right"><a href="javascript:uf_catalogo_liquidaciones('<?php print $ls_rutfil ?>');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Cargar Créditos..." title="Cargar Créditos..." width="20" height="20" border="0" longdesc="Carga los documentos xml dispuestos a liquidar..." />Cargar Cr&eacute;ditos </a></div></td>
    </tr>
    <tr>
      <td colspan="5"><div align="center"><?php $io_grid->make_gridScroll($li_totrows,$la_rowgri,$la_object,750,'Detalle Presupuestario',"grid_dtspg",100); ?>
        <input name="hidtotrow" type="hidden" id="hidtotrow" value="<?php echo $li_totrows ?>" />
      </div></td>
    </tr>
    <tr>
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="5"><div align="center">
        <?php $io_grid->make_gridScroll($li_totrowscg,$la_titscg,$la_objscg,750,'Detalle Contable',"grid_dtscg",100); ?>
        <input name="hidtotrowscg" type="hidden" id="hidtotrowscg" value="<?php echo $li_totrowscg ?>" />
      </div></td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>
</body>
<script>
f = document.form1;
function rellenar_cadena(as_cadena,ai_longitud,as_campo)
{
  if (as_cadena!="")
	 {
		var mystring=new String(as_cadena);
		cadena_ceros="";
		lencad=mystring.length;
	
		total=ai_longitud-lencad;
		for (i=1;i<=total;i++)
		    {
			  cadena_ceros=cadena_ceros+"0";
		    }
		ls_cadena = cadena_ceros+as_cadena;
		if (as_campo=='numdoc')
		   {
		     document.form1.txtnumdoc.value = ls_cadena;   
		   }
		else
		   {
		     document.form1.txtchevau.value = ls_cadena;
		   }
	}
}

function uf_load_catalogo_bancos()
{
  if (uf_evaluate_cierre('SPG'))
     {
	   li_leer = f.leer.value;
	   if (li_leer==1)
		  {
		    ls_codope = f.hidcodope.value;
		    if (ls_codope=="CH")
			   {
				 window.open("sigesp_cat_bancos.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
			   }
		  }	  
	   else
		  {
		    alert("No tiene permiso para realizar esta operación !!!");
		  }
	 }
}

function uf_load_catalogo_cuentas()
{
  if (uf_evaluate_cierre('SPG'))
     {
	   li_leer = f.leer.value;
	   if (li_leer==1)
		  {
		    ls_codope = f.hidcodope.value;
		    if (ls_codope=="CH")
			   {
				 if (f.txtcodban.value!="")
				    {
					  ls_codban = f.txtcodban.value;
					  ls_nomban = f.txtnomban.value;
					  pagina="sigesp_cat_ctabanco.php?codigo="+ls_codban+"&hidnomban="+ls_nomban;
					  window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,resizable=yes,location=no");
				    }
				 else
				    {
					  alert("Debe seleccionar el Banco !!!");		  
				    }
			   }
		  }
	   else
		  {
		    alert("No tiene permiso para realizar esta operación !!!");	 
		  }
     }
}

function uf_catalogo_liquidaciones(as_rutfil)
{
  if (uf_evaluate_cierre('SPG'))
     {
	   li_leer = f.leer.value;
  	   if (li_leer==1)
	      {
		    window.open("sigesp_scb_cat_liquidaciones.php?rutfil="+as_rutfil,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=680,height=400,left=50,top=50,location=no,resizable=no,dependent=yes");
	      }
	   else
	      {
		    alert("No tiene permiso para realizar esta operación !!!");
	      }
	 }
}

function uf_procesar_liquidacion()
{
  lb_mesabi = f.hidmesabi.value;
  if (lb_mesabi=='true')
     {
	  if (uf_evaluate_cierre('SPG'))
		 {
		   li_incluir = f.incluir.value;
		   if (li_incluir==1)
			  {
				ls_codban = f.txtcodban.value;
				if (ls_codban=="")
				   {
					 alert("Debe indicar el Código del Banco !!!");
				   }
				else
				   {
					 ls_ctaban = f.txtctaban.value;
					 if (ls_ctaban=="")
						{
						  alert("Debe indicar la Cuenta Bancaria !!!");
						}
					 else
						{
						  ls_cedben = f.txtcedben.value;
						  if (ls_cedben=="")
							 {
							   alert("Debe indicar la Cédula del Beneficiario !!!");
							 }
						  else
							 {
							   ls_numdoc = f.txtnumdoc.value;
							   if (ls_numdoc=="")
								  {
									alert("Debe indicar el Número del Documento !!!");
								  }
							   else
								  {
									ls_fecmov = f.txtfecmov.value;
									if (ls_fecmov=="")
									   {
										 alert("Debe indicar la Fecha del Documento !!!");
									   } 						 
									else
									   {
										 ld_monmov = f.txtmonmov.value;
										 if (ld_monmov=="0,00")
											{
											  alert("Debe indicar el Monto del Documento !!!");
											}
										 else
											{
											  if (f.txtconmov.value!='')
												 {
												   li_totrows = f.hidtotrow.value;
												   ls_spgcta = eval("f.txtspgcta1.value");
												   if (li_totrows>0 && ls_spgcta!="")
													  {
														f.operacion.value = "PROCESAR";
														f.action = "sigesp_scb_p_liquidacion_creditos.php";
														f.submit();
													  } 								   
												 }
											  else
												 {
												   alert("Debe indicar el Concepto del Documento !!!");
												 }
											}
									   }
								  }
							 }
						}
				   }
			  }
		   else
			  {
				alert("No tiene permiso para realizar esta operación !!!");
			  }
		 }
     }
  else
     {
	   alert("Operación No puede ser procesada, El Més está Cerrado !!!");
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
</html>