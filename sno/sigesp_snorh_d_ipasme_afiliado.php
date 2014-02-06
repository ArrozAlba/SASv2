<?php
    session_start();
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
	$ls_logusr=$_SESSION["la_logusr"];
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_d_ipasme_afiliado.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 17/07/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $ls_codper,$ls_nomper,$ls_tiptraafi,$ls_coddep,$ls_desdep,$ls_actlabafi,$ls_tipafiafi,$ls_codban,$ls_cuebanafi;
		global $ls_tipcueafi,$ls_codent,$ls_codmun,$ls_codloc,$ls_urbafi,$ls_aveafi,$ls_nomresafi,$ls_pisafi,$ls_zonafi,$ls_numresafi;
		global $ls_beneficiario,$ls_activarcodigo,$ld_fecnacper,$ls_operacion,$ls_existe,$io_fun_nomina,$lb_valido;
		
		$ls_codper="";
		$ls_nomper="";
		$ls_tiptraafi="I";
		$ls_coddep="";
		$ls_desdep="";
		$ls_actlabafi="";
		$ls_tipafiafi="";
		$ls_codban="";
		$ls_cuebanafi="";
		$ls_tipcueafi="";
		$ls_codent="";
		$ls_codmun="";
		$ls_codloc="";
		$ls_urbafi="";
		$ls_aveafi="";
		$ls_nomresafi="";
		$ls_pisafi="";
		$ls_zonafi="";
		$ls_numresafi="";
		$ls_beneficiario="disabled";
		$ls_activarcodigo="";
		$lb_valido=true;
		$ld_fecnacper="01/01/1900";
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		$ls_existe=$io_fun_nomina->uf_obtenerexiste();
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_load_variables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Función que carga todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 17/07/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codper,$ls_nomper,$ls_tiptraafi,$ls_coddep,$ls_desdep,$ls_actlabafi,$ls_tipafiafi,$ls_codban,$ls_cuebanafi;
		global $ls_tipcueafi,$ls_codent,$ls_codmun,$ls_codloc,$ls_urbafi,$ls_aveafi,$ls_nomresafi,$ls_pisafi,$ls_zonafi;
		global $ld_fecnacper,$ls_numresafi;

		$ls_codper=$_POST["txtcodper"];
		$ls_nomper=$_POST["txtnomper"];
		$ls_tiptraafi=$_POST["cmbtiptraafi"];
		$ls_coddep=$_POST["txtcoddep"];
		$ls_desdep=$_POST["txtdesdep"];
		$ls_actlabafi=$_POST["cmbactlabafi"];
		$ls_tipafiafi=$_POST["cmbtipafiafi"];
		$ls_codban=$_POST["cmbcodban"];
		$ls_cuebanafi=$_POST["txtcuebanafi"];
		$ls_tipcueafi=$_POST["cmbtipcueafi"];
		$ls_codent=$_POST["cmbcodent"];
		$ls_codmun=$_POST["cmbcodmun"];
		$ls_codloc=$_POST["cmbcodloc"];
		$ls_urbafi=$_POST["txturbafi"];
		$ls_aveafi=$_POST["txtaveafi"];
		$ls_nomresafi=$_POST["txtnomresafi"];
		$ls_pisafi=$_POST["txtpisafi"];
		$ls_zonafi=$_POST["txtzonafi"];
		$ls_numresafi=$_POST["txtnumresafi"];
		$ld_fecnacper=$_POST["txtfecnacper"];
   }
   //--------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
<!--
if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey)){
		window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ 
		return false; 
		} 
		} 
	}

function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
<title >Definici&oacute;n de Afiliados para el IPASME</title>
<meta http-equiv="imagetoolbar" content="no"> 
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #EFEBEF;
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
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_nomina.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/localizacion.js"></script>
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {color: #6699CC}
-->
</style>
</head>
<body>
<?php 
	require_once("sigesp_snorh_c_ipasme_afiliado.php");
	$io_afiliado=new sigesp_snorh_c_ipasme_afiliado();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "GUARDAR":
			uf_load_variables();
			$lb_valido=$io_afiliado->uf_guardar($ls_existe,$ls_codper,$ls_tiptraafi,$ls_coddep,$ls_actlabafi,$ls_tipafiafi,$ls_codban,
												$ls_cuebanafi,$ls_tipcueafi,$ls_codent,$ls_codmun,$ls_codloc,$ls_urbafi,$ls_aveafi,
												$ls_nomresafi,$ls_numresafi,$ls_pisafi,$ls_zonafi,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
			}
			break;

		case "ELIMINAR":
			uf_load_variables();
			$lb_valido=$io_afiliado->uf_delete_ipasme_afiliado($ls_codper,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
			}
			break;
			
		case "BUSCAR":
			$ls_codper=$_GET["codper"];
			$lb_valido=$io_afiliado->uf_load_ipasme_afiliado($ls_existe,$ls_codper,$ls_nomper,$ls_tiptraafi,$ls_coddep,$ls_desdep,
															 $ls_actlabafi,$ls_tipafiafi,$ls_codban,$ls_cuebanafi,$ls_tipcueafi,
															 $ls_codent,$ls_codmun,$ls_codloc,$ls_urbafi,$ls_aveafi,
															 $ls_nomresafi,$ls_numresafi,$ls_pisafi,$ls_zonafi,$ld_fecnacper);
			$ls_beneficiario="";
			$ls_activarcodigo="style='visibility:hidden'";
			break;
	}
	$io_afiliado->uf_destructor();
	unset($io_afiliado);
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Nómina</td>
			<td width="346" bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
	  	    <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td></tr>
        </table>
	 </td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif"  title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		
<table width="700" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td>
      <p>&nbsp;</p>
      <table width="650" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="2" class="titulo-ventana">Definici&oacute;n de Afiliados para el IPASME</td>
        </tr>
        <tr>
          <td width="86" height="22">&nbsp;</td>
          <td width="302">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right"> Personal </div></td>
          <td>
            <div align="left">
              <input name="txtcodper" type="text" id="txtcodper" size="13" maxlength="10" value="<?php print $ls_codper;?>" readonly>
              <a href="javascript: ue_buscarpersonal();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0" <?php print $ls_activarcodigo;?>></a>
              <input name="txtnomper" type="text" class="sin-borde" id="txtnomper" value="<?php print $ls_nomper;?>" size="60" maxlength="120" readonly>
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Dependencia</div></td>
          <td><div align="left">
            <input name="txtcoddep" type="text" id="txtcoddep" size="14" maxlength="11" value="<?php print $ls_coddep;?>" readonly>
            <a href="javascript: ue_buscardependencia();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdesdep" type="text" class="sin-borde" id="txtdesdep" value="<?php print $ls_desdep;?>" size="60" maxlength="70" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Tipo de Transacci&oacute;n </div></td>
          <td height="22">
            <div align="left">
              <select name="cmbtiptraafi">
                <option value="I" selected>Ingreso</option>
                <option value="M">Modificaci&oacute;n</option>
              </select>
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Actividad Laboral </div></td>
          <td height="22">
            <div align="left">
              <select name="cmbactlabafi">
                <option value="" selected>--Seleccione--</option>
                <option value="A">Administrativo</option>
                <option value="D">Docente</option>
                <option value="M">M&eacute;dico</option>
                <option value="C">Contratado</option>
              </select>
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Tipo de Afiliaci&oacute;n </div></td>
          <td height="22">
            <div align="left">
              <select name="cmbtipafiafi" id="cmbtipafiafi">
                <option value="" selected>--Seleccione--</option>
                <option value="1">Asistencial</option>
                <option value="2">Total &oacute; Integral</option>
              </select>
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Banco</div></td>
          <td height="22">
            <div align="left">
              <select name="cmbcodban" id="cmbcodban">
                <option value="" selected>--Seleccione--</option>
                <option value="001">Banco Central de Venezuela</option>
                <option value="003">Banco Industrial de Venezuela, C.A.</option>
                <option value="006">Banco de Coro, C.A.</option>
                <option value="007">Banco de Fomento Regional de los Andes</option>
                <option value="008">Banco Guayana, C.A.</option>
                <option value="102">Banco de Venezuela,SACA, Banco Universal</option>
                <option value="104">Banco Venezolano de Crédito,S.A. Banco Universal</option>
                <option value="105">Banco Mercantil C.A. SACA Banco Universal</option>
                <option value="108">Banco Provincial S.A. Banco Universal</option>
                <option value="114">Banco del Caribe C.A. Banco Universal</option>
                <option value="115">Banco Exterior C.A. Banco Universal</option>
                <option value="116">Banco Occidental de Descuento Banco Universal C.A. SACA</option>
                <option value="121">Corp Banca C.A. Banco Universal</option>
                <option value="128">Banco Caroní C.A. Banco Universal</option>
                <option value="133">Banco Federal C.A.</option>
                <option value="134">Banesco Banco Universal SACA</option>
                <option value="137">Banco Sofitasa Banco Universal C.A.</option>
                <option value="138">Banco Plaza C.A.</option>
                <option value="140">Banco Canarias de Venezuela Banco Universal C.A.</option>
                <option value="141">Banco Confederado S.A. Banco Comercial Regional</option>
                <option value="144">Eurobanco Banco Comercial C.A.</option>
                <option value="147">Nuevo Mundo Banco Comercial C.A.</option>
                <option value="148">Total Bank, C.A. Banco Comercial</option>
                <option value="150">Bolivar Banco C.A.</option>
                <option value="151">Fondo Comun C.A. Banco Universal</option>
                <option value="157">Del Sur Banco Universal C.A.</option>
                <option value="158">C.A. Central Banco Universal</option>
                <option value="160">Banco Galicia de Venezuela C.A. Banco Comercial</option>
                <option value="190">Citibank N.A.</option>
                <option value="191">Banco Tequendama C.A.</option>
                <option value="193">Banco Standard Chartered</option>
                <option value="194">Banco de Crédito de Colombia</option>
                <option value="196">Abn-amro N.V. (Sucursal Venezuela)</option>
                <option value="408">Pro-Vivienda E.A.P.</option>
                <option value="410">Casa Propia E.A.P.</option>
                <option value="425">Mi Casa E.A.P. C.A.</option>
                <option value="428">Banplus E.A.P. C.A.</option>
                <option value="601">Instituto Municipal de Crédito Popular</option>
              </select>
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Nro Cuenta Bancaria </div></td>
          <td height="22">
              <div align="left">
                <input name="txtcuebanafi" type="text" id="txtcuebanafi" value="<?php print $ls_cuebanafi;?>" size="28" maxlength="25" onKeyUp="javascript: ue_validarnumero(this);">
              </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Tipo de Cuenta Bancaria </div></td>
          <td height="22">
            <div align="left">
              <select name="cmbtipcueafi" id="cmbtipcueafi">
                <option value="" >--Seleccione--</option>
                <option value="A">Ahorro</option>
                <option value="C">Corriente</option>
              </select>
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Entidad</div></td>
          <td height="22">
            <div align="left">
              <select name="cmbcodent" id="cmbcodent" onChange="javascript: ue_cargarmunicipios();">
                <option value="" selected>--Seleccione--</option>
                <option value="01">DTTO FEDERAL</option>
                <option value="02">AMAZONAS</option>
                <option value="03">ANZOATEGUI</option>
                <option value="04">APURE</option>
                <option value="05">ARAGUA</option>
                <option value="06">BARINAS</option>
                <option value="07">BOLIVAR</option>
                <option value="08">CARABOBO</option>
                <option value="09">COJEDES</option>
                <option value="10">DELTA AMACURO</option>
                <option value="11">FALCON</option>
                <option value="12">GUARICO</option>
                <option value="13">LARA</option>
                <option value="14">MERIDA</option>
                <option value="15">MIRANDA</option>
                <option value="16">MONAGAS</option>
                <option value="17">NUEVA ESPARTA</option>
                <option value="18">PORTUGUESA</option>
                <option value="19">SUCRE</option>
                <option value="20">TACHIRA</option>
                <option value="21">TRUJILLO</option>
                <option value="22">YARACUY</option>
                <option value="23">ZULIA</option>
                <option value="24">VARGAS</option>
              </select>
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Municipios</div></td>
          <td height="22">
            <div align="left">
              <select name="cmbcodmun" id="cmbcodmun" onChange="javascript: ue_cargarlocalidad();">
                <option value="" selected>--Seleccione--</option>
              </select>
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Localidad</div></td>
          <td height="22">
            <div align="left">
              <select name="cmbcodloc" id="cmbcodloc">
                <option value="" selected>--Seleccione--</option>
              </select>
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Urbanizaci&oacute;n &oacute; Barrio de Residencia </div></td>
          <td height="22">
              <div align="left">
                <input name="txturbafi" type="text" id="txturbafi" value="<?php print $ls_urbafi;?>" size="35" maxlength="30" onKeyUp="ue_validarcomillas(this);">
              </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Avenida y/o Calle de Residencia </div></td>
          <td height="22">
            <div align="left">
              <input name="txtaveafi" type="text" id="txtaveafi" value="<?php print $ls_aveafi;?>" size="35" maxlength="30" onKeyUp="ue_validarcomillas(this);">
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Nombre de Residencia </div></td>
          <td height="22">
            <div align="left">
              <input name="txtnomresafi" type="text" id="txtnomresafi" value="<?php print $ls_nomresafi;?>" size="35" maxlength="30" onKeyUp="ue_validarcomillas(this);">
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">N&uacute;mero de Residencia </div></td>
          <td height="22">
            <div align="left">
              <input name="txtnumresafi" type="text" id="txtnumresafi" value="<?php print $ls_numresafi;?>" size="8" maxlength="5" onKeyUp="javascript: ue_validarnumero(this);">
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Piso</div></td>
          <td height="22"><div align="left">
            <input name="txtpisafi" type="text" id="txtpisafi" value="<?php print $ls_pisafi;?>" size="8" maxlength="2" onKeyUp="javascript: ue_validarnumero(this);">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Zona Postal </div></td>
          <td height="22">
            <div align="left">
              <input name="txtzonafi" type="text" id="txtzonafi" value="<?php print $ls_zonafi;?>" size="8" maxlength="5" onKeyUp="javascript: ue_validarnumero(this);">
              </div></td></tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td height="22">
            <input name="btnbeneficiario" type="button" class="boton" id="btnbeneficiario" value="Beneficiario" onClick="javascript: ue_beneficiario();" <?php print $ls_beneficiario;?>>          </td>
        </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td height="22">
		  <input name="operacion" type="hidden" id="operacion">
		  <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>">
		  <input name="txtfecnacper" type="hidden" id="txtfecnacper" value="<?php print $ld_fecnacper;?>"></td>
        </tr>
      </table>    
      <p>&nbsp;</p></td>
  </tr>
</table>
<?php
	if(($ls_operacion=="BUSCAR")||($lb_valido===false))
	{
		print "<script language='javascript'>";
		print "		f=document.form1;";
		print "		f.cmbtiptraafi.value='".$ls_tiptraafi."';";		
		print "		f.cmbactlabafi.value='".$ls_actlabafi."';";
		print "		f.cmbtipafiafi.value='".$ls_tipafiafi."';";
		print "		f.cmbcodban.value='".$ls_codban."';";
		print "		f.cmbtipcueafi.value='".$ls_tipcueafi."';";
		print "		f.cmbcodent.value='".$ls_codent."';";
		print "		f.cmbcodent.onchange();";
		print "		f.cmbcodmun.value='".$ls_codmun."';";
		print "		f.cmbcodmun.onchange();";
		print "		f.cmbcodloc.value='".$ls_codloc."';";
		print "</script>";
	}
?>
</form>      
<p>&nbsp;</p>
</body>
<script language="javascript">
function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		f.operacion.value="NUEVO";
		f.existe.value="FALSE";		
		f.action="sigesp_snorh_d_ipasme_afiliado.php";
		f.submit();
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_existe=f.existe.value;
	if(((lb_existe=="TRUE")&&(li_cambiar==1))||(lb_existe=="FALSE")&&(li_incluir==1))
	{
		coddep = ue_validarvacio(f.txtcoddep.value);
		desdep = ue_validarvacio(f.txtdesdep.value);
		entdep = ue_validarvacio(f.cmbcodent.value);
		mundep = ue_validarvacio(f.cmbcodmun.value);
		if ((coddep!="")&&(desdep!="")&&(entdep!="")&&(mundep!=""))
		{
			f.operacion.value="GUARDAR";
			f.action="sigesp_snorh_d_ipasme_afiliado.php";
			f.submit();
		}
		else
		{
			alert("Debe llenar todos los datos.");
		}
	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_eliminar()
{
	f=document.form1;
	li_eliminar=f.eliminar.value;
	if(li_eliminar==1)
	{	
		if(f.existe.value=="TRUE")
		{
			coddep = ue_validarvacio(f.txtcoddep.value);
			if (coddep!="")
			{
				if(confirm("¿Desea eliminar el Registro actual?"))
				{
					f.operacion.value="ELIMINAR";
					f.action="sigesp_snorh_d_ipasme_afiliado.php";
					f.submit();
				}
			}
			else
			{
				alert("Debe buscar el registro a eliminar.");
			}
		}
		else
		{
			alert("Debe buscar el registro a eliminar.");
		}
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_cerrar()
{
	location.href = "sigespwindow_blank.php";
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		window.open("sigesp_snorh_cat_ipasme_afiliado.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_ayuda()
{
	width=(screen.width);
	height=(screen.height);
	//window.open("../hlp/index.php?sistema=SNO&subsistema=SNR&nomfis=sno/sigesp_hlp_snr_ipasafiliado.php","Ayuda","menubar=no,toolbar=no,scrollbars=yes,width="+width+",height="+height+",resizable=yes,location=no");
}

function ue_beneficiario()
{
	f=document.form1;
	codper=ue_validarvacio(f.txtcodper.value);
	nomper=ue_validarvacio(f.txtnomper.value);
	fecnacper=f.txtfecnacper.value;
	location.href="sigesp_snorh_d_ipasme_beneficiario.php?codper="+codper+"&nomper="+nomper+"&fecnacper="+fecnacper+"";
}

function ue_buscarpersonal()
{
	f=document.form1;
	if(f.existe.value=="FALSE")
	{
		window.open("sigesp_snorh_cat_personal.php?tipo=ipasme","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
}

function ue_buscardependencia()
{
	window.open("sigesp_snorh_cat_ipasme_dependencias.php?tipo=afiliado","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}
</script> 
</html>