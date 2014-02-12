<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
     print "<script language=JavaScript>";
     print "location.href='../sigesp_inicio_sesion.php'";
     print "</script>";		
   }
require_once("class_folder/class_funciones_soc.php");
$io_fun_compra = new class_funciones_soc();
$io_fun_compra->uf_load_seguridad("SOC","sigesp_soc_p_registro_cotizacion.php",$ls_permisos,&$la_seguridad,$la_permisos);
$ls_reporte = $io_fun_compra->uf_select_config("SOC","REPORTE","FORMATO_REGCOT","sigesp_soc_rfs_registro_cotizacion.php","C");

$ls_logusr = $_SESSION["la_logusr"];
$ls_codemp = $_SESSION["la_empresa"]["codemp"];
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
   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 16/05/2007			Fecha Última Modificación : 16/05/2007 
		//////////////////////////////////////////////////////////////////////////////
   		global $io_fundb,$io_fun_compra,$io_soc,$li_totrowbienes,$li_totrowservicios,$ld_subtotal,$ld_creditos,$ld_total;
        global $ls_codemp,$ls_numcot,$ls_tipcot,$ls_fecregcot,$ls_codpro,$ls_nompro,$ls_tipconpro,$ls_obscot,$li_diaent;
		global $ls_forpag,$ls_estatus,$ls_existe,$ls_numsolcot,$ls_estcot,$ls_parametros,$li_estinciva;
		global $ld_poriva,$ls_operacion,$li_estciespg,$li_estciespi,$ls_disabled;
						
		require_once("../shared/class_folder/class_funciones_db.php");
	    require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/sigesp_c_generar_consecutivo.php");
		
	    $io_include	  = new sigesp_include();
		$io_conexion  = $io_include->uf_conectar();
		$io_funciondb = new class_funciones_db($io_conexion); 
		$io_keygen    = new sigesp_c_generar_consecutivo();
		
		$ls_numcot    = $io_keygen->uf_generar_numero_nuevo('SOC','soc_cotizacion','numcot','SOCSOL',15,"","","");
		$ls_estatus   = "REGISTRO";
		$ls_estcot    = "0";
		$ls_fecregcot = date("d/m/Y");
		$ls_numsolcot = "";
		$ls_tipcot    = '-';
		$ls_codpro    = "";
		$ls_nompro    = "";
		$ls_tipconpro = "";
		$ls_obscot    = "";
		$li_diaent    = "";
		$ld_poriva    = "0";
		$ls_forpag    = '-';
		$li_totrowbienes    = 0;
		$li_totrowservicios = 0;
		$ld_subtotal 		= "0,00";
		$ld_creditos 		= "0,00";
		$ld_total			= "0,00";
		$ls_operacion       = $io_fun_compra->uf_obteneroperacion();
		$ls_existe          = $io_fun_compra->uf_obtenerexiste();	
		$ls_parametros      = ""; 
		$li_estinciva       = 0;
		$li_estciespg = $io_fun_compra->uf_load_estatus_cierre($li_estciespi,$li_estciescg);
		$ls_disabled  = "";
		if ($li_estciespg==1 || $li_estciespi==1)
		   {
		     $ls_disabled = "disabled";
		   }
   }
     //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_load_variables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Función que carga todas las variables necesarias en la página
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 16/05/2007			Fecha Última Modificación : 16/05/2007
		//////////////////////////////////////////////////////////////////////////////
        global $li_totrowbienes,$li_totrowservicios,$ld_subtotal,$ld_creditos,$ld_total,$ls_fecregcot,$ls_numsolcot;		
		global $ls_estcot,$ls_numcot,$ls_tipcot,$ls_codpro,$ls_nompro,$ls_tipconpro,$ls_obscot,$ls_forpag,$li_diaent;
		global $ld_poriva,$li_estinciva,$li_estciespg,$li_estciespi;
		
		$ls_estcot    		= $_POST["hidestcot"];
		$ls_fecregcot 		= $_POST["txtfecregcot"];
		$ls_numcot    		= $_POST["txtnumcot"];
		$ls_tipcot    		= $_POST["cmbtipcot"];
		$ls_codpro 	  		= $_POST["txtcodprov"];
		$ls_nompro 	  		= $_POST["txtnomprov"];
		$ls_obscot 	  		= $_POST["txtobscot"];
		$ls_forpag 	  		= $_POST["cmbformapago"];
		$li_diaent    		= $_POST["txtdiasentrega"];
		$ld_poriva    		= $_POST["txtporiva"];
		$li_totrowbienes	= $_POST["totrowbienes"];
		$li_totrowservicios = $_POST["totrowservicios"];
		$ld_subtotal		= $_POST["txtsubtotal"];
		$ld_creditos        = $_POST["txtcreditos"];
		$ld_total			= $_POST["txttotal"];
		$ls_numsolcot       = $_POST["hidnumsolcot"];
		$ls_tipconpro       = $_POST["hidtipconpro"];
		if (array_key_exists("chkinconpora",$_POST))
		   {
		     $li_estinciva = $_POST["chkinconpora"];
		   }
		else
		   {
		     $li_estinciva = 0;
		   }
        $li_estciespg = $_POST["hidestciespg"];//Estatus del Cierre Presupuestario de Gasto.
		$li_estciespi = $_POST["hidestciespi"];//Estatus del Presupuestario de Ingresos.
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_load_data(&$as_parametros)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Función que carga todas las variables necesarias en la página
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 27/05/2007								Fecha Última Modificación : 27/05/2007
		//////////////////////////////////////////////////////////////////////////////
   		global $li_totrowbienes,$li_totrowservicios,$li_totrowsep,$li_totrowproveedores;
			
		for($li_i=1;($li_i<$li_totrowbienes);$li_i++)
		{
			$ls_codart = $_POST["txtcodart".$li_i];
			$ls_denart = $_POST["txtdenart".$li_i];
			$ld_canart = $_POST["txtcanart".$li_i];
			$ld_preart = $_POST["txtpreart".$li_i];
			$ld_subart = $_POST["txtsubart".$li_i];
			$ld_creart = $_POST["txtcreart".$li_i];
			$ld_totart = $_POST["txttotart".$li_i];
			$ls_calart = $_POST["cmbcalart".$li_i];
			$ld_porcre = $_POST["hidporcre".$li_i];
			
			$as_parametros=$as_parametros."&txtcodart".$li_i."=".$ls_codart."&txtdenart".$li_i."=".$ls_denart."".
					   					  "&txtcanart".$li_i."=".$ld_canart."&txtpreart".$li_i."=".$ld_preart."".
										  "&txtsubart".$li_i."=".$ld_subart."&txtcreart".$li_i."=".$ld_creart."".
										  "&txttotart".$li_i."=".$ld_totart."&cmbcalart".$li_i."=".$ls_calart."".
										  "&hidporcre".$li_i."=".$ld_porcre;
		}
		$as_parametros=$as_parametros."&totalbienes=".$li_totrowbienes."";
		for($li_i=1;($li_i<$li_totrowservicios);$li_i++)
		{
			$ls_codser = $_POST["txtcodser".$li_i];
			$ls_denser = $_POST["txtdenser".$li_i];
			$ld_canser = $_POST["txtcanser".$li_i];
			$ld_preser = $_POST["txtpreser".$li_i];
			$ld_subser = $_POST["txtsubser".$li_i];
			$ld_creser = $_POST["txtcreser".$li_i];
			$ld_totser = $_POST["txttotser".$li_i];
			$ls_calser = $_POST["cmbcalser".$li_i];
			$ld_porcre = $_POST["hidporcre".$li_i];
			
			$as_parametros=$as_parametros."&txtcodser".$li_i."=".$ls_codser."&txtdenser".$li_i."=".$ls_denser."".
					  					  "&txtcanser".$li_i."=".$ld_canser."&txtpreser".$li_i."=".$ld_preser."".
										  "&txtsubser".$li_i."=".$ld_subser."&txtcreser".$li_i."=".$ld_creser."".
										  "&txttotser".$li_i."=".$ld_totser."&cmbcalser".$li_i."=".$ls_calser."".
										  "&hidporcre".$li_i."=".$ld_porcre;
		}
		$as_parametros=$as_parametros."&totalservicios=".$li_totrowservicios."";
   }
   //--------------------------------------------------------------

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Registro de Cotizaciones</title>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/general.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_soc.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
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
</style>
</head>
<body onLoad="writetostatus('<?php print "Base de Datos: ".$_SESSION["ls_database"].". Usuario: ".$_SESSION["la_logusr"];?>')">
<?php
require_once("class_folder/sigesp_soc_c_registro_cotizacion.php");
$io_soc=new sigesp_soc_c_registro_cotizacion("../");
uf_limpiarvariables();
switch($ls_operacion){
  case 'GUARDAR':
			uf_load_variables();
			$lb_valido=$io_soc->uf_guardar($ls_existe,$ls_fecregcot,$ls_numcot,$ls_numsolcot,$ls_tipcot,$ls_obscot,$li_totrowbienes,$li_totrowservicios,&$ls_estcot,
					                       $ls_forpag,$ls_codpro,$li_diaent,$ld_poriva,$ld_subtotal,$ld_creditos,$ld_total,$li_estinciva,$la_seguridad);
			uf_load_data(&$ls_parametros);
			switch($ls_estcot)
			{
				case "R": 
					$ls_estatus="REGISTRO";//La Cotización está almacenada pero no ha sido tomada por un Análisis de Cotización. 
					break;
				case "P": 
					$ls_estatus="PROCESADA";//La Cotización ya fue incorporada en un Análisis de Cotización.
					break;
			}
			if($lb_valido)
			{
				$ls_existe="TRUE";
			}
  break;

	case "ELIMINAR":
		uf_load_variables();
		$lb_valido=$io_soc->uf_delete_cotizacion($ls_numcot,$ls_tipcot,$ls_codpro,$li_totrowbienes,$li_totrowservicios,$la_seguridad);
		if(!$lb_valido)
		{
			uf_load_data(&$ls_parametros);
			switch($ls_estcot)
			{
				case "R": 
					$ls_estatus="REGISTRO";
					break;
				case "P": 
					$ls_estatus="PROCESADA";
					break;
			}
			$ls_existe="TRUE";
		}
		else
		{
			uf_limpiarvariables();
			$ls_existe    = "FALSE";
			$ls_operacion = "NUEVO";
		}
		break;
  }

if ($ls_tipcot=='B')
   {
     $ls_selbie = "selected";
	 $ls_selser = "";
   }
elseif($ls_tipcot=='S')
   {
     $ls_selbie = "";
     $ls_selser = "selected";
   }
else
   {
     $ls_selser = "";
     $ls_selbie = "";
   }
   
$ls_contado = "";
$ls_credito = "";
$ls_cheque  = "";
$ls_carta   = "";
$ls_abono   = "";
$ls_otros   = "";
switch($ls_forpag){
  case 'Contado':
    $ls_contado = "selected";
  break;
  case 'Crédito':
    $ls_credito = "selected";
  break;
  case 'Cheque':
    $ls_cheque = "selected";
  break;
  case 'Carta':
    $ls_carta = "selected";
  break;
  case 'Abono':
    $ls_abono = "selected";
  break;
  case 'Otros':
    $ls_otros = "selected";
  break;
}
?>
<div align="center">
  <table width="800" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
    <tr>
      <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" alt="Encabezado" width="800" height="40" /></td>
    </tr>
	<td width="800" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
			
            <td width="430" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Ordenes de Compra</td>
			  <td width="350" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
	  	    <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
        </table>
    </td>  
    <tr>
      <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
    </tr>
    <tr>
      <td height="13" bgcolor="#FFFFFF" class="toolbar"></td>
    </tr>
    <tr>
      <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" title="Nuevo" alt="Nuevo" width="20" height="20" border="0" /></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Guardar" width="20" height="20" border="0" /></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0" /></a><a href="javascript:ue_imprimir();"></a><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0" /></a><a href="../soc/sigespwindow_blank.php"></a><a href="javascript:ue_imprimir();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0" title="Imprimir" /></a><a href="../soc/sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0" title="Salir"/></a></td>
    </tr>
  </table>
  <p>&nbsp;</p>
<form id="formulario" name="formulario" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_compra->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_compra);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
    <table width="800" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr class="titulo-celdanew"><input name="tipo" type="hidden" id="tipo" value="RC" />
        <td height="22" colspan="6" class="titulo-ventana">Registro de Cotizaciones 
      <input name="hidestcot" type="hidden" id="hidestcot" value="<?php print $ls_estcot ?>" />
      <input name="hidestciespg" type="hidden" id="hidestciespg" value="<?php echo $li_estciespg ?>" />
      <input name="hidestciespi" type="hidden" id="hidestciespi" value="<?php echo $li_estciespi ?>" />      </tr>
      <tr style="visibility:hidden">
        <td width="143" height="22">Reporte en
          <select name="cmbbsf" id="cmbbsf">
            <option value="0" selected="selected">Bs.</option>
            <option value="1">Bs.F.</option>
          </select></td>
        <td width="154" height="13">&nbsp;</td>
        <td width="189" height="13">&nbsp;</td>
        <td width="106" height="13">&nbsp;</td>
        <td width="40" height="13">&nbsp;</td>
        <td width="178" height="13">&nbsp;</td>
      </tr>
      <tr>
        <td height="13" style="text-align:right">Estatus</td>
        <td height="13" colspan="5"><input name="txtestatus" type="text" class="sin-borde2" id="txtestatus" value="<?php print $ls_estatus; ?>" size="70" readonly="readonly"/></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">N&uacute;mero</td>
        <td height="22"><label>
          <input name="txtnumcot" type="text" id="txtnumcot" value="<?php print $ls_numcot ?>" size="20" maxlength="15" style="text-align:center"  tabindex="0" <?php if(($la_permisos["administrador"]!=1)||($ls_operacion!="NUEVO")){print "readonly";} ?>>
        </label></td>
        <td height="22"><input name="operacion" type="hidden" class="formato-blanco" id="operacion"  value="<?php print $ls_operacion?>" />
        <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe ?>" />
        <input name="hidnumsolcot" type="hidden" id="hidnumsolcot" value="<?php print $ls_numsolcot ?>" />
        <input name="txtporiva" type="hidden" id="txtporiva" style="text-align:right" value="<?php print $ld_poriva ?>" size="6" maxlength="5" tabindex="6" onkeypress="return keyRestrict(event,'1234567890'+','); "/>
        <input name="hidtipconpro" type="hidden" id="hidtipconpro" value="<?php print $ls_tipconpro ?>" /></td>
        <td height="22">&nbsp;</td>
        <td height="22" style="text-align:right"><strong>Fecha</strong></td>
        <td height="22"><label>
          <input name="txtfecregcot" type="text" class="sin-borde" id="txtfecregcot" value="<?php print $ls_fecregcot ?>" size="15" maxlength="12" readonly />
        </label></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Tipo</td>
        <td height="22"><select name="cmbtipcot" id="cmbtipcot" tabindex="1" onchange="javascript:ue_cargargrid()" style="width:120px" <?php echo $ls_disabled; ?>>
          <option value="-">---seleccione---</option>
          <option value="B" <?php print $ls_selbie ?>>Bienes</option>
          <option value="S" <?php print $ls_selser ?>>Servicios</option>
                </select></td>
        <td height="22" colspan="4"><label></label></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Proveedor</td>
        <td height="22" colspan="5"><label>
          <input name="txtcodprov" type="text" id="txtcodprov" value="<?php print $ls_codpro ?>" size="20" maxlength="10" style="text-align:center" readonly tabindex="2" />
          <a href="javascript:ue_catalogo_proveedores();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" title="Buscar Proveedor..."  /></a></label>          <label>
          <input name="txtnomprov" type="text" class="sin-borde" id="txtnomprov" value="<?php print $ls_nompro ?>" size="90" maxlength="50" readonly style="text-align:left" />
          </label></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Observaci&oacute;n</td>
        <td height="22" colspan="5"><label>
          <input name="txtobscot" type="text" id="txtobscot" value="<?php print $ls_obscot ?>" size="114" maxlength="254" style="text-align:left" tabindex="3" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyzáéíóú '+'¡!:;_°#@/?¿%&$*-,.+(){}[]='); " <?php echo $ls_disabled; ?> />
        </label></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Forma de Pago</td>
        <td height="22"><label>
              <select name="cmbformapago"  style="width:120px" tabindex="4" id="cmbformapago" <?php echo $ls_disabled;?>>
                <option value="-">---seleccione---</option>
                <option value="CONTADO" <?php print $ls_contado ?>>CONTADO</option>
                <option value="CREDITO" <?php print $ls_credito ?>>CREDITO</option>
                <option value="CHEQUE"  <?php print $ls_cheque ?>>CHEQUE</option>
                <option value="CARCRE"  <?php print $ls_carta ?>>CARTA DE CREDITO</option>
                <option value="ABOCUE"  <?php print $ls_abono ?>>ABONO EN CUENTA</option>
                <option value="OTROS"   <?php print $ls_otros ?>>OTROS</option>
			  </select>            </td>
        </label></td>
        <td height="22">Plazo de Entrega 
          <input name="txtdiasentrega" type="text" id="txtdiasentrega" value="<?php print $li_diaent ?>" size="6" maxlength="3" style="text-align:right" tabindex="5" onKeyPress="return keyRestrict(event,'1234567890'); " <?php echo $ls_disabled; ?>/> 
        <strong>d&iacute;as. </strong></td>
        <td height="22" colspan="3"><label>
          <input name="chkincorpora" type="checkbox" class="sin-borde" id="chkinconpora" value="1" onclick="javascrip:ue_cargargrid()" readonly style="visibility:hidden"/>
        </label></td>
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
        <td height="22" colspan="2"><a href="javascript:uf_catalogo_solicitudes();"><img src="../shared/imagebank/tools20/nuevo.gif" title="Agregar Solicitud de Cotizaci&oacute;n" width="20" height="20" border="0" />Agregar Solicitud de Cotizaci&oacute;n</a>
          <input name="totrowbienes" type="hidden" id="totrowbienes" value="<?php print $li_totrowbienes;?>" />
          <input name="totrowservicios" type="hidden" id="totrowservicios" value="<?php print $li_totrowservicios;?>" /><input name="parametros"    type="hidden" id="parametros"    value="<?php print $ls_parametros;?>" />
          <input name="formato"    type="hidden" id="formato"    value="<?php print $ls_reporte; ?>" /></td>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
      </tr>
      <tr>
        <td height="13" colspan="2">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
       <table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
         <tr> 
           <td align="center"><div id="bienesservicios"></div></td>
         </tr>
       </table>      
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td height="134" colspan="6">
          <table width="800" border="0" cellpadding="0" cellspacing="0" align="center" class="formato-blanco">
            <tr class="titulo-celdanew">
              <td height="15" colspan="4">Totales</td>
            </tr>
            <tr>
              <td width="24" height="13">&nbsp;</td>
              <td width="412" height="13" align="right">&nbsp;</td>
              <td width="119" height="13" class="formato-blanco2">&nbsp;</td>
              <td width="243" height="13" align="center" class="formato-blanco2">&nbsp;</td>
            </tr>
            <tr>
              <td height="22">&nbsp;</td>
              <td height="22" align="right">&nbsp;</td>
              <td height="22" class="formato-blanco2" style="text-align:right"><strong>Subtotal</strong></td>
              <td height="22" align="center" class="formato-blanco2"><div align="left">
                  <input name="txtsubtotal" type="text" class="titulo-conect" id="txtsubtotal" style="text-align:right" value="<?php print $ld_subtotal ?>" size="20" maxlength="20" readonly />
              </div></td>
            </tr>
            <tr>
              <td height="22">&nbsp;</td>
              <td height="22" align="right">&nbsp;</td>
              <td height="22" align="right" class="formato-blanco2"><strong>Cr&eacute;ditos</strong></td>
              <td height="22" align="left" class="formato-blanco2"><div align="left">
                  <input name="txtcreditos" type="text" class="titulo-conect" id="txtcreditos" value="<?php print $ld_creditos ?>" size="20"  style="text-align:right "  readonly />
              </div></td>
            </tr>
            <tr>
              <td height="22">&nbsp;</td>
              <td height="22">&nbsp;</td>
              <td height="22" align="right"><strong>Total Cotizaci&oacute;n&nbsp;</strong></td>
              <td height="22"><div align="left">
                  <input name="txttotal" type="text" class="titulo-conect" id="txttotal" style="text-align:right" value="<?php print $ld_total ?>" size="20" readonly />
              </div></td>
            </tr>
            <tr>
              <td height="22">&nbsp;</td>
              <td height="22">&nbsp;</td>
              <td height="22">&nbsp;</td>
              <td height="22">&nbsp;</td>
            </tr>
          </table>        </td>
      </tr>
      <tr>
        <td height="22" colspan="6">&nbsp;</td>
      </tr>
    </table>
    <p>&nbsp;</p>
  </form>
</div>
</body>
<script language="javascript">
f = document.formulario;
function writetostatus(input){
    window.status=input
    return true
}

function ue_nuevo()
{
  if (uf_evaluate_cierre())
     {
	   li_incluir = f.incluir.value;
  	   if (li_incluir==1)
  	      {	
		    f.operacion.value = "NUEVO";
		    f.existe.value    = "FALSE";		
		    f.action          = "sigesp_soc_p_registro_cotizacion.php";
		    f.submit();
   	      }
	   else
   	      { 
 	        alert("No tiene permiso para realizar esta operación !!!");
          }
	 }
}

function ue_cargargrid()
{
	// Cargamos las variables para pasarlas al AJAX
	ls_tipcot               = f.cmbtipcot.value;
	f.totrowbienes.value    = 1;
	f.totrowservicios.value = 1;
	f.txtsubtotal.value     = '0,00';
	f.txtcreditos.value     = '0,00';
	f.txttotal.value        = '0,00';
	f.hidestcot.value       = '0';
	f.existe.value          = 'FALSE';
	f.hidnumsolcot.value    = '';
	f.parametros.value      = '';
	
	// Div donde se van a cargar los resultados
	divgrid = document.getElementById('bienesservicios');
	// Instancia del Objeto AJAX
	ajax=objetoAjax();
	// Pagina donde están los métodos para buscar y pintar los resultados
	ajax.open("POST","class_folder/sigesp_soc_c_registro_cotizacion_ajax.php",true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			divgrid.innerHTML = ajax.responseText;
		}
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	// Enviar todos los campos a la pagina para que haga el procesamiento
    ajax.send("tipo="+ls_tipcot+"&totalbienes=1&totalservicios=1"+"&proceso=LIMPIAR");	
    pagina="sigesp_soc_cat_proveedor.php?tipo=RC";
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=650,height=500 resizable=yes,location=no,left=50,top=50,dependent=yer");
}

function ue_guardar()
{
if (uf_evaluate_cierre())
   {
	li_incluir = f.incluir.value;
	li_cambiar = f.cambiar.value;
	lb_existe  = f.existe.value;
	if (((lb_existe=="TRUE")&&(li_cambiar==1))||(lb_existe=="FALSE")&&(li_incluir==1))
	   {
	     lb_valido = true;
		 ls_estcot = f.hidestcot.value;
		 if (ls_estcot=="1")
		    {
			  lb_valido = false;
			  alert("La Cotización ya ha sido procesada, No puede ser modificada !!!.");
		    }
	     else
		    {
			  // Obtenemos el total de filas de los bienes
			  li_totrowbienes      = ue_calcular_total_fila_local("txtcodart");
			  f.totrowbienes.value = li_totrowbienes;
			  // Obtenemos el total de filas de los Servicios
			  li_totrowservicios      = ue_calcular_total_fila_local("txtcodser");
			  f.totrowservicios.value = li_totrowservicios;
		      ls_numcot = ue_validarvacio(f.txtnumcot.value);
			  ls_tipcot = ue_validarvacio(f.cmbtipcot.value); 
			  if (ls_tipcot!='-')
			     {
			       ls_obscot    = ue_validarvacio(f.txtobscot.value); 
				   ls_fecregcot = ue_validarvacio(f.txtfecregcot.value);
				   if (lb_valido)
					  {
						lb_valido = ue_validarcampo(ls_numcot,"El Número de Cotización no puede estar vacio.",f.txtnumcot);
					  }
				   if (lb_valido)
					  {
						lb_valido = ue_validarcampo(ls_obscot,"La Observación de la Cotización no puede estar vacia.",f.txtobscot);
					  }
				   if (lb_valido)
					  {
						lb_valido = ue_validarcampo(ls_fecregcot,"La Fecha no puede estar vacia.",f.txtfecregcot);
					  }
				   if (lb_valido)
				      {
					    lb_enciva = false;
						ld_poriva = 0;
						if (ls_tipcot=='B')//Cotización de Tipo Bienes.
						   {
							 li_rowbienes = f.totrowbienes.value;
							 if (li_rowbienes>1)
							    {
								  for (j=1;(j<li_rowbienes)&&(lb_valido);j++)
								      {
									    ls_codart = eval("f.txtcodart"+j+".value");
									    ls_denart = eval("f.txtdenart"+j+".value");
										ld_totart = eval("f.txttotart"+j+".value");
									    ld_totart = ue_formato_calculo(ld_totart);
										if (!lb_enciva)
										   {
										     ld_poriva = eval("f.hidporcre"+j+".value");
										     if (ld_poriva>0)
											    {
												  lb_enciva = true;
												}
										   }
										if (ld_totart<=0)
									       {
										     alert("El Total del Bien/Material "+ls_codart+" - "+ls_denart+" debe ser mayor que Cero.")
										     lb_valido = false;
									       }
								      }
							    }
							 else
							    {
								  alert("La Cotización debe tener al menos un Bien/Material incorporado !!!");
								  lb_valido = false;
							    }
						   }
					     else
						   {
							 li_rowservicios = f.totrowservicios.value;
							 if (li_rowservicios>1)
							    {
								  for (j=1;(j<li_rowservicios)&&(lb_valido);j++)
								      {
									    ls_codser = eval("f.txtcodser"+j+".value");
										ls_denser = eval("f.txtdenser"+j+".value");
										ld_totser = eval("f.txttotser"+j+".value");
									    ld_totser = ue_formato_calculo(ld_totser);
									    if (!lb_enciva)
										   {
										     ld_poriva = eval("f.hidporcre"+j+".value");
										     if (ld_poriva>0)
											    {
												  lb_enciva = true;
												}
										   }
										if (ld_totser<=0)
									       {
										     alert("El Total del Servicio "+ls_codser+" - "+ls_denser+" debe ser mayor que Cero.")
										     lb_valido = false;
								 	       }
								      }
							    }
							 else
							    {
								  alert("La Cotización debe tener al menos un Servicio incorporado !!!");
								  lb_valido = false;
							    }
						   } 
					  } 
				   if (lb_valido)
					  {
						f.txtporiva.value = ld_poriva;
						f.operacion.value="GUARDAR";
						f.action="sigesp_soc_p_registro_cotizacion.php";
						f.submit();		
					  }
				 }
			  else
			     {
	               alert("Debe seleccionar el Tipo de Cotización a Registrar !!!");
				 }
			}
	   }
    else
	   {
 	     alert("No tiene permiso para realizar esta operación !!!");
	   }
   }
}

function uf_catalogo_solicitudes()
{
  if (uf_evaluate_cierre())
     {
       ls_tipcot = f.cmbtipcot.value;
       ls_codpro = f.txtcodprov.value;
       li_leer   = f.leer.value;
       if (li_leer=='1')
          {
            if (ls_tipcot=='-')
               {
	             alert("Debe seleccionar el tipo de Cotización a Registrar !!!");
	           }
            else
               { 
	             if (ls_codpro=="")
		            {
			          alert("Debe seleccionar un Proveedor !!!");
		            }
	             else
		            {
			          ls_estcot = f.hidestcot.value;
				      if (ls_estcot=='0')
				         {
				           pagina="sigesp_soc_cat_solicitud_cotizacion.php?origen=RC&codpro="+ls_codpro;
			               window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=650,height=500 resizable=yes,location=no,left=50,top=50,dependent=yer");
					     }
                      else
				         {
					       alert("La Cotización ya ha sido procesada, No puede ser modificada !!!");
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

function ue_catalogo_proveedores()
{
  if (uf_evaluate_cierre())
     {
	   ls_estcot = f.hidestcot.value;
	   if (ls_estcot=='0')
		  {
		    pagina="sigesp_soc_cat_proveedor.php?tipo=RC";
		    window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=650,height=500 resizable=yes,location=no,left=50,top=50,dependent=yer");
		  }
	   else
		  {
		    alert("La Cotización ya ha sido procesada, No puede ser modificada !!!");
		  }
	 }
}

function ue_procesar_monto(ls_tipcot,fila)
{
if (uf_evaluate_cierre())
   {
	ls_tipconpro = f.hidtipconpro.value;
	ls_estcot    = f.hidestcot.value;
	if (ls_estcot=="1")
	   {
	     alert("La Cotización ya ha sido procesada, No puede ser modificada !!!.");
	   }
	else
	   {
		 if (f.chkincorpora.checked==true)
		    {
		      li_incorpora = 1;
		    }
	 	 else
		    {
		      li_incorpora = 0;
		    }
		 
		 // Obtenemos el total de filas de los servicios
		 li_totrowservicios      = ue_calcular_total_fila_local("txtcodser");
		 f.totrowservicios.value = li_totrowservicios;
		 // Obtenemos el total de filas de los bienes
		 li_totalrowbienes    = ue_calcular_total_fila_local("txtcodart");
		 f.totrowbienes.value = li_totalrowbienes;

		 if (ls_tipcot=="B")
		    {
			  // Cargamos los valores de la fila indicada
			  ls_codart = eval("f.txtcodart"+fila+".value");
			  ld_canart = eval("f.txtcanart"+fila+".value");
			  ld_preart = eval("f.txtpreart"+fila+".value");
			  ld_porcre = eval("f.hidporcre"+fila+".value");
			  ld_canart = ue_formato_calculo(ld_canart);
			  ld_preart = ue_formato_calculo(ld_preart);
			  // Si es una fila que tiene Artículos
			  if (ls_codart!="")
			     {
				   // Si la cantidad de artículos ó el precio es mayor que cero calculamos
				   if ((ld_canart>0)||(ld_preart>0))
				      {
					    ld_subart = (ld_canart*ld_preart);
						if ((ld_porcre>0) && (ls_tipconpro=='O' || ls_tipconpro=='E'))
						   {
						     ld_creart = (ld_subart*(ld_porcre/100));
						   }
						else
						   {
						     ld_creart = 0;
						   }
						ld_totart = (ld_subart+ld_creart);
						ld_subart = redondear(ld_subart,3);
						ld_creart = redondear(ld_creart,3);
						ld_totart = redondear(ld_totart,3);
					    eval("f.txtsubart"+fila+".value='"+uf_convertir(ld_subart)+"'");
						eval("f.txtcreart"+fila+".value='"+uf_convertir(ld_creart)+"'");
					    eval("f.txttotart"+fila+".value='"+uf_convertir(ld_totart)+"'");
					  }
				   ue_actualizar_totales(ls_tipcot);//Actualizamos los totales de la solicitud
			     }
		    }
		 if (ls_tipcot=="S")
		    {
			  // Cargamos los valores de la fila indicada
			  ls_codser = eval("f.txtcodser"+fila+".value");
			  ld_canser = eval("f.txtcanser"+fila+".value");
			  ld_preser = eval("f.txtpreser"+fila+".value");
			  ld_porcre = eval("f.hidporcre"+fila+".value");
			  ld_canser = ue_formato_calculo(ld_canser);
			  ld_preser = ue_formato_calculo(ld_preser);
			  //Si es una fila que tiene Artículos
			  if (ls_codser!="")
			     {
				   if ((ld_canser>0)||(ld_preser>0))				   //Si la cantidad de artículos ó el precio es mayor que cero calculamos
				      {
					    if (li_incorpora=='1')
					       { 
					         if ((ld_porcre>0) && (ls_tipconpro=='O' || ls_tipconpro=='E'))
						        {
						          ld_preser = (ld_preser/(1+ld_porcre/100));
     					          ld_creser = (ld_preser*(ld_porcre/100));
						        }
					         else
						        {
							      ld_creser = "0";
						 	    }
					     	 ld_subser = (ld_canser*ld_preser);
						 	 ld_totser = (ld_subser+ld_creser);
						 	 ld_subser = redondear(ld_subser,3);
						 	 ld_creser = redondear(ld_creser,3);
						 	 ld_totser = redondear(ld_totser,3);
					         eval("f.txtpreser"+fila+".value='"+uf_convertir(ld_preser)+"'");
						     eval("f.txtsubser"+fila+".value='"+uf_convertir(ld_subser)+"'");
						     eval("f.txtcreser"+fila+".value='"+uf_convertir(ld_creser)+"'");
					         eval("f.txttotser"+fila+".value='"+uf_convertir(ld_totser)+"'");
					       }				     
					    else
					       {                                    
					         if ((ld_porcre>0) && (ls_tipconpro=='O' || ls_tipconpro=='E'))
						        {
							      ld_creser = (ld_preser*ld_canser*(ld_porcre/100));
							    }
						     else
						        {
							      ld_creser = 0;
							    }
							 ld_subser = (ld_canser*ld_preser);
						     ld_totser = (ld_subser+ld_creser);
						     ld_subser = redondear(ld_subser,3);
						     ld_creser = redondear(ld_creser,3);
						     ld_totser = redondear(ld_totser,3);
					         eval("f.txtsubser"+fila+".value='"+uf_convertir(ld_subser)+"'");
						     eval("f.txtcreser"+fila+".value='"+uf_convertir(ld_creser)+"'");
					         eval("f.txttotser"+fila+".value='"+uf_convertir(ld_totser)+"'");
					       }
						ue_actualizar_totales(ls_tipcot);// Actualizamos los totales de la solicitud
				      }
			     }
		    }
	  }
 }
}

function ue_actualizar_totales(tipo)
{
	ld_subtotal = 0;
	ld_creditos = 0;
	ld_total    = 0;
    ls_estcot   = f.hidestcot.value;
	if (ls_estcot==0) 
	   {
	     if (tipo=="B")
	        {
		      rowbienes=f.totrowbienes.value;
		      // Recorremos los bienes y sumamos para colocarlo en los totales
		      for (fila_bienes=1;fila_bienes<rowbienes;fila_bienes++)
				  {
					montobienes    = eval("f.txtsubart"+fila_bienes+".value");
					montobienes    = ue_formato_calculo(montobienes);
					ld_subtotal    = eval(ld_subtotal+"+"+montobienes);
					montocargos    = eval("f.txtcreart"+fila_bienes+".value");
					montocargos    = ue_formato_calculo(montocargos);
					ld_creditos    = eval(ld_creditos+"+"+montocargos);
					montoarticulos = eval("f.txttotart"+fila_bienes+".value");
					montoarticulos = ue_formato_calculo(montoarticulos);
					ld_total       = eval(ld_total+"+"+montoarticulos);
				  }
	        }
     	 if (tipo=="S")
	        {
		      rowservicios=f.totrowservicios.value;
		      //Recorremos los servicios y sumamos para colocarlo en los totales
		      for (fila_servicios=1;fila_servicios<rowservicios;fila_servicios++)
		          {
					montoservicios = eval("f.txtsubser"+fila_servicios+".value");
					montoservicios = ue_formato_calculo(montoservicios);
					ld_subtotal    = eval(ld_subtotal+"+"+montoservicios);
					montocargos    = eval("f.txtcreser"+fila_servicios+".value");
					montocargos    = ue_formato_calculo(montocargos);
					ld_creditos    = eval(ld_creditos+"+"+montocargos);
					montoservicios = eval("f.txttotser"+fila_servicios+".value");
					montoservicios = ue_formato_calculo(montoservicios);
					ld_total       = eval(ld_total+"+"+montoservicios);
				  }
			}
		 f.txtsubtotal.value = uf_convertir(ld_subtotal);
		 f.txtcreditos.value = uf_convertir(ld_creditos);
		 f.txttotal.value    = uf_convertir(ld_total);
	   }
	else
	   {
	     alert("La Cotización ya ha sido procesada, No puede ser modificada !!!.");
	   }
}

function ue_delete_bienes(fila)
{  
  if (uf_evaluate_cierre())
     {
	   ld_subtotal = 0;
	   ld_creditos = 0;
	   ld_total    = 0; 
	   ls_tipcot = f.cmbtipcot.value;
	   ls_estcot = f.hidestcot.value;
	   if (ls_estcot=="1")
 	      {
	        alert("La Cotización ya ha sido procesada, No puede ser modificada !!!.");
	      }
	   else
	      {
		    if (confirm("¿Desea eliminar el Registro actual?"))
		       {
				 valido=true;
				 parametros="";
				 codigo="";
				 //---------------------------------------------------------------------------------
				 // Cargar los Bienes y eliminar el seleccionado
				 //---------------------------------------------------------------------------------
				 // Obtenemos el total de filas de los bienes
				 li_totrowbienes      = ue_calcular_total_fila_local("txtcodart");
				 f.totrowbienes.value = li_totrowbienes;
				
				 li_i=0;
				 for (j=1;(j<li_totrowbienes)&&(valido);j++)
					 {
					   if (j!=fila)
						  {
						    li_i = li_i+1;
						    //Calculamos los Totales.
						    montobienes    = eval("f.txtsubart"+j+".value");
						    montobienes    = ue_formato_calculo(montobienes);
						    ld_subtotal    = eval(ld_subtotal+"+"+montobienes);
						    montocargos    = eval("f.txtcreart"+j+".value");
						    montocargos    = ue_formato_calculo(montocargos);
						    ld_creditos    = eval(ld_creditos+"+"+montocargos);
						    montoarticulos = eval("f.txttotart"+j+".value");
						    montoarticulos = ue_formato_calculo(montoarticulos);
						    ld_total       = eval(ld_total+"+"+montoarticulos);
	
						    //Capturamos los valores de los objetos que no se eliminaran.
						    ls_codart  = eval("f.txtcodart"+j+".value");
						    ls_denart  = eval("f.txtdenart"+j+".value");
						    ld_canart  = eval("f.txtcanart"+j+".value");
						    ld_preart  = eval("f.txtpreart"+j+".value");
					        ld_subart  = eval("f.txtsubart"+j+".value");
					        ld_creart  = eval("f.txtcreart"+j+".value");
					        ld_totart  = eval("f.txttotart"+j+".value");
					        ls_calart  = eval("f.cmbcalart"+j+".value");
					        ld_porcre  = eval("f.hidporcre"+j+".value");
					        parametros = parametros+"&txtcodart"+li_i+"="+ls_codart+"&txtdenart"+li_i+"="+ls_denart+"&txtcanart"+li_i+"="+ld_canart+
					                           "&txtpreart"+li_i+"="+ld_preart+"&txtsubart"+li_i+"="+ld_subart+"&hidporcre"+li_i+"="+ld_porcre+
											   "&txtcreart"+li_i+"="+ld_creart+"&txttotart"+li_i+"="+ld_totart+"&cmbcalart"+li_i+"="+ls_calart;
				          }
			         } 
			     li_i=li_i+1;
				 parametros=parametros+"&totalbienes="+li_i+"";
				 f.totrowbienes.value=li_i;
			
			     f.txtsubtotal.value = uf_convertir(ld_subtotal);
			     f.txtcreditos.value = uf_convertir(ld_creditos);
			     f.txttotal.value    = uf_convertir(ld_total);
				 if ((parametros!="")&&(valido))
			        {
				      divgrid = document.getElementById("bienesservicios");
				      ajax=objetoAjax();
				      ajax.open("POST","class_folder/sigesp_soc_c_registro_cotizacion_ajax.php",true);
				      ajax.onreadystatechange=function() {
					  if (ajax.readyState==4) {
						   divgrid.innerHTML = ajax.responseText
					     }
				    }
				 ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
				 ajax.send("proceso=AGREGARBIENES"+parametros);
			   }
		  }
    }
	 }
}

function ue_delete_servicios(fila)
{
  if (uf_evaluate_cierre())
     {
	   ld_subtotal = 0;
	   ld_creditos = 0;
	   ld_total    = 0; 
	   ls_tipcot   = f.cmbtipcot.value;
	   ls_estcot   = f.hidestcot.value;
	   if (ls_estcot=="1")
  	      {
	        alert("La Cotización ya ha sido procesada, No puede ser modificada !!!.");
	      }
	   else
	      {
		    if (confirm("¿Desea eliminar el Registro actual?"))
		       {
			     valido=true;
			     parametros="";
			     codigo="";
				 //---------------------------------------------------------------------------------
				 // Cargar los Servicios y eliminar el seleccionado
				 //---------------------------------------------------------------------------------
				 // Obtenemos el total de filas de los Servicios
				 li_totrowservicios = ue_calcular_total_fila_local("txtcodser");
				 li_i=0;
				 for (j=1;(j<li_totrowservicios)&&(valido);j++)
				     {
					   if (j!=fila)
					      {
						    li_i=li_i+1;		
						    //Calculamos los Totales.
						    montoservicios = eval("f.txtsubser"+j+".value");
						    montoservicios = ue_formato_calculo(montoservicios);
						    ld_subtotal    = eval(ld_subtotal+"+"+montoservicios);
						    montocargos    = eval("f.txtcreser"+j+".value");
						    montocargos    = ue_formato_calculo(montocargos);
						    ld_creditos    = eval(ld_creditos+"+"+montocargos);
					 	    montoservicios = eval("f.txttotser"+j+".value");
						    montoservicios = ue_formato_calculo(montoservicios);
						    ld_total       = eval(ld_total+"+"+montoservicios);
						    //Capturamos los valores de los objetos que no se eliminaran.
						    ls_codser  = eval("f.txtcodser"+j+".value");
						    ls_denser  = eval("f.txtdenser"+j+".value");
						    ld_canser  = eval("f.txtcanser"+j+".value");
							ld_preser  = eval("f.txtpreser"+j+".value");
							ld_subser  = eval("f.txtsubser"+j+".value");
							ld_creser  = eval("f.txtcreser"+j+".value");
							ld_totser  = eval("f.txttotser"+j+".value");
							ls_calser  = eval("f.cmbcalser"+j+".value");
							ld_porcre  = eval("f.hidporcre"+j+".value");
							parametros = parametros+"&txtcodser"+li_i+"="+ls_codser+"&txtdenser"+li_i+"="+ls_denser+"&txtcanser"+li_i+"="+ld_canser+
													"&txtpreser"+li_i+"="+ld_preser+"&txtsubser"+li_i+"="+ld_subser+"&txtcreser"+li_i+"="+ld_creser+
													"&txttotser"+li_i+"="+ld_totser+"&cmbcalser"+li_i+"="+ls_calser+"&hidporcre"+li_i+"="+ld_porcre;
						  }
					 }
			     li_i=li_i+1;
				 parametros=parametros+"&totalservicios="+li_i+"";
				 f.totrowservicios.value=li_i;
	
				 f.txtsubtotal.value = uf_convertir(ld_subtotal);
				 f.txtcreditos.value = uf_convertir(ld_creditos);
				 f.txttotal.value    = uf_convertir(ld_total);
			
			     if ((parametros!="")&&(valido))
			        {
				      divgrid = document.getElementById("bienesservicios");
				      ajax=objetoAjax();
				      ajax.open("POST","class_folder/sigesp_soc_c_registro_cotizacion_ajax.php",true);
				      ajax.onreadystatechange=function() {
					  if (ajax.readyState==4) {
						   divgrid.innerHTML = ajax.responseText
					     }
				      }
				      ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
				      ajax.send("proceso=AGREGARSERVICIOS"+parametros);
			        }
		       }
	      }
	 }
}

function ue_eliminar()
{
  if (uf_evaluate_cierre())
     {
	   li_eliminar=f.eliminar.value;
	   if (li_eliminar==1)
  	      {	
		    if (f.existe.value=="TRUE")
		       {
			     ls_estcot = f.hidestcot.value;
			     if (ls_estcot=="1")
			        {
			          alert("La Cotización ya ha sido procesada, No puede ser modificada !!!.");
			        }
			     else
			        {
				      ls_numcot = ue_validarvacio(f.txtnumcot.value);
				      if (ls_numcot!="")
				         {
					       if (confirm("¿Desea eliminar el Registro actual?"))
					          {
								f.operacion.value       = "ELIMINAR";
								f.action                = "sigesp_soc_p_registro_cotizacion.php";
								li_totrowbienes         = ue_calcular_total_fila_local("txtcodart");
								f.totrowbienes.value    = li_totrowbienes;
								li_totrowservicio       = ue_calcular_total_fila_local("txtcodser");
								f.totrowservicios.value = li_totrowservicio;
								f.submit();
							  }
				         }
				      else
				         {
					       alert("Debe buscar el registro a eliminar.");
				         }
			        }
		       }
		    else
			   {
			     alert("Debe buscar el registro a eliminar.");
			   }
   	      }
	   else
   	      {
 		    alert("No tiene permiso para realizar esta operación !!!");
   	      }	 
	 }
}

function ue_reload()
{
	parametros = f.parametros.value;
	ls_tipcot  = f.cmbtipcot.value;// Para saber si es de bienes, servicios ó conceptos
	if(ls_tipcot=="B")
	{
		proceso="AGREGARBIENES";
	}
	if(ls_tipcot=="S")
	{
		proceso="AGREGARSERVICIOS";
	}

	if(parametros!="")
	{
		divgrid = document.getElementById("bienesservicios");
		ajax=objetoAjax();
		ajax.open("POST","class_folder/sigesp_soc_c_registro_cotizacion_ajax.php",true);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				divgrid.innerHTML = ajax.responseText
			}
		}
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.send("proceso="+proceso+parametros);
	}
}

function ue_buscar()
{
	f       = document.formulario;
	li_leer = f.leer.value;
	if (li_leer==1)
   	{
		window.open("sigesp_soc_cat_cotizacion.php?origen=RC","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=400,left=50,top=50,location=no,resizable=yes");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operación !!!");
   	}
}

function ue_imprimir()
{
	li_imprimir = f.imprimir.value;
	lb_existe   = f.existe.value;
	if (li_imprimir==1)
	   {
		 if (lb_existe=="TRUE")
		    {
			  ls_numcot = f.txtnumcot.value;
			  ls_feccot = f.txtfecregcot.value;
			  ls_codpro = f.txtcodprov.value;
			  ls_tipcot = f.cmbtipcot.value;
			  if (ls_tipcot!='-')
			     {
			       ls_formato   = f.formato.value;
				   tiporeporte=f.cmbbsf.value;
			       window.open("reportes/"+ls_formato+"?numcot="+ls_numcot+"&tipcot="+ls_tipcot+"&cod_pro="+ls_codpro+"&feccot="+ls_feccot+"&tiporeporte="+tiporeporte,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
			     }
		      else
			     {
			       alert("Debe seleccionar un Tipo de Cotización válida (Bienes/Servicios) !!!");
			     }
		    }
		 else
		    { 
			  alert("Debe existir un documento a imprimir");
		    }
	   }
	else
	   {
	     alert("No tiene permiso para realizar esta operación !!!");
	   }
}

function uf_evaluate_cierre()
{
  lb_valido = true;
  li_estciespg = f.hidestciespg.value;
  li_estciespi = f.hidestciespi.value;
  if (li_estciespg==1 || li_estciespi==1)
	 {
	   lb_valido = false;
	   alert("Ya fué procesado el Cierre Presupuestario, No pueden efectuarse movimientos, Contacte al Administrador del Sistema !!!");
	 }
  return lb_valido;
}
</script>
<?php
if(($ls_operacion=="GUARDAR")||(($ls_operacion=="ELIMINAR")&&(!$lb_valido)))
{
	print "<script language=JavaScript>";
	print "   ue_reload();";
	print "</script>";
}
?>
</html>