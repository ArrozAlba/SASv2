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
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_d_ipasme_beneficiario.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
   
   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 18/07/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $li_codben,$ls_cedben,$ls_tiptraben,$ls_codpare,$ls_nacben,$ls_prinomben,$ls_segnomben,$ls_priapeben,$ls_segapeben;
		global $ls_sexben,$ld_fecnacben,$ls_estcivben,$ld_fecfalben,$ls_codban,$ls_numcueben,$ls_tipcueben,$ls_codper,$ls_nomper;
		global $ls_operacion,$ls_existe,$io_fun_nomina,$lb_valido;

		$li_codben="0";
		$ls_cedben="";
		$ls_tiptraben="I";
		$ls_codpare="";
		$ls_nacben="";
		$ls_prinomben="";
		$ls_segnomben="";
		$ls_priapeben="";
		$ls_segapeben="";
		$ls_sexben="";
		$ld_fecnacben="dd/mm/aaaa";
		$ls_estcivben="";
		$ld_fecfalben="dd/mm/aaaa";
		$ls_codban="";
		$ls_numcueben="";
		$ls_tipcueben="";
		$lb_valido=true;
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
		// Fecha Creación: 18/03/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $li_codben,$ls_cedben,$ls_tiptraben,$ls_codpare,$ls_nacben,$ls_prinomben,$ls_segnomben,$ls_priapeben,$ls_segapeben;
		global $ls_sexben,$ld_fecnacben,$ls_estcivben,$ld_fecfalben,$ls_codban,$ls_numcueben,$ls_tipcueben,$ls_codper,$ls_nomper;
		
		$ls_codper=$_POST["txtcodper"];
		$ls_nomper=$_POST["txtnomper"];
		$li_codben=$_POST["txtcodben"];
		$ls_cedben=$_POST["txtcedben"];
		$ls_tiptraben=$_POST["cmbtiptraben"];
		$ls_codpare=$_POST["cmbcodpare"];
		$ls_nacben=$_POST["cmbnacben"];
		$ls_prinomben=$_POST["txtprinomben"];
		$ls_segnomben=$_POST["txtsegnomben"];
		$ls_priapeben=$_POST["txtpriapeben"];
		$ls_segapeben=$_POST["txtsegapeben"];
		$ls_sexben=$_POST["cmbsexben"];
		$ld_fecnacben=$_POST["txtfecnacben"];
		$ls_estcivben=$_POST["cmbestcivben"];
		$ld_fecfalben=$_POST["txtfecfalben"];
		$ls_codban=$_POST["cmbcodban"];
		$ls_numcueben=$_POST["txtnumcueben"];
		$ls_tipcueben=$_POST["cmbtipcueben"];
   }
   //--------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
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
</script>
<title >Definici&oacute;n de Beneficiario para el IPASME</title>
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/validaciones.js"></script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {color: #6699CC}
-->
</style>
</head>
<body>
<?php 
	require_once("sigesp_snorh_c_ipasme_beneficiario.php");
	$io_beneficiario=new sigesp_snorh_c_ipasme_beneficiario();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
		 	$ls_codper=$_GET["codper"];
			$ls_nomper=$_GET["nomper"];
			$ld_fecnacper=$_GET["fecnacper"];
			break;

		case "GUARDAR":
			uf_load_variables();
			$lb_valido=$io_beneficiario->uf_guardar($ls_existe,$ls_codper,$li_codben,$ls_cedben,$ls_tiptraben,$ls_codpare,
													$ls_nacben,$ls_prinomben,$ls_segnomben,$ls_priapeben,$ls_segapeben,
													$ls_sexben,$ld_fecnacben,$ls_estcivben,$ld_fecfalben,$ls_codban,$ls_numcueben,
													$ls_tipcueben,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
				$ls_codper=$_POST["txtcodper"];
				$ls_nomper=$_POST["txtnomper"];
				$ld_fecnacper=$_POST["txtfecnacper"];
			}
			break;

		case "ELIMINAR":
			uf_load_variables();
			$lb_valido=$io_beneficiario->uf_delete_ipasme_beneficiario($ls_codper,$li_codben,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
				$ls_codper=$_POST["txtcodper"];
				$ls_nomper=$_POST["txtnomper"];
				$ld_fecnacper=$_POST["txtfecnacper"];
			}
			break;
	}
	$io_beneficiario->uf_destructor();
	unset($io_beneficiario);
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<td width="432" height="20" bgcolor="#E7E7E7"><span class="Estilo1">Sistema de Nómina</span></td>
			<td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequeñas"><b><?php print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  	</table>
	 </td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_volver();"><img src="../shared/imagebank/tools20/salir.gif"  title="Salir" alt="Salir" width="20" height="20" border="0"></a></div></td>
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
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigesp_snorh_d_ipasme_afiliado.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="550" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr>
          <td colspan="2"><input name="txtnomper" type="text" class="sin-borde2" id="txtnomper" value="<?php print $ls_nomper;?>" size="60" readonly>
            <input name="txtcodper" type="hidden" id="txtcodper" value="<?php print $ls_codper;?>">
            <input name="txtfecnacper" type="hidden" id="txtfecnacper" value="<?php print $ld_fecnacper;?>"></td>
        </tr>
        <tr class="titulo-ventana">
          <td height="20" colspan="2" class="titulo-ventana">Definici&oacute;n de Beneficiario del IPASME </td>
        </tr>
        <tr>
          <td width="143" height="22">&nbsp;</td>
          <td width="351">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo</div></td>
          <td>
              <div align="left">
                <input name="txtcodben" type="text" id="txtcodben" value="<?php print $li_codben;?>" size="10" maxlength="10" readonly>
              </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Tipo de Transacci&oacute;n </div></td>
          <td>
            <div align="left">
              <select name="cmbtiptraben" id="cmbtiptraben">
                <option value="I" selected>Ingreso</option>
                <option value="M">Modificaci&oacute;n</option>
              </select>
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">C&eacute;dula</div></td>
          <td>
            <div align="left">
              <input name="txtcedben" type="text" id="txtcedben" value="<?php print $ls_cedben;?>" size="13" maxlength="10" onKeyUp="javascript: ue_validarnumero(this);">
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Primer Nombre</div></td>
          <td>
            <div align="left">
              <input name="txtprinomben" type="text" id="txtprinomben" onKeyUp="javascript: ue_validarcomillas(this);" value="<?php print $ls_prinomben;?>" size="18" maxlength="15">
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Segundo Nombre </div></td>
          <td>
            <div align="left">
              <input name="txtsegnomben" type="text" id="txtsegnomben" onKeyUp="javascript: ue_validarcomillas(this);" value="<?php print $ls_segnomben;?>" size="18" maxlength="15">
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Primer Apellido</div></td>
          <td>
            
              <div align="left">
                <input name="txtpriapeben" type="text" id="txtpriapeben" onKeyUp="javascript: ue_validarcomillas(this);" value="<?php print $ls_priapeben;?>" size="18" maxlength="15">
            </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Segundo Apellido</div></td>
          <td>
            
              <div align="left">
                <input name="txtsegapeben" type="text" id="txtsegapeben" onKeyUp="javascript: ue_validarcomillas(this);" value="<?php print $ls_segapeben;?>" size="18" maxlength="15">
            </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Parentesco</div></td>
          <td><div align="left">
            <select name="cmbcodpare" id="cmbcodpare">
              <option value="" selected>--Seleccione Uno--</option>
              <option value="01">Padres</option>
              <option value="02">Abuelos</option>
              <option value="03">Hijos</option>
              <option value="04">Hermanos</option>
              <option value="05">Conyuge</option>
              <option value="06">Concubino</option>
            </select>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Nacionalidad</div></td>
          <td><label>
            <div align="left">
              <select name="cmbnacben" id="cmbnacben">
                <option value="" selected>--Seleccione--</option>
                <option value="V">Venezolano</option>
                <option value="E">Extranjero</option>
              </select>
              </div>
          </label></td>
        </tr>
        <tr>
          <td height="22"><div align="right">G&eacute;nero</div></td>
          <td>
            <div align="left">
              <select name="cmbsexben" id="cmbsexben">
                <option value="" selected>--Seleccione Uno--</option>
                <option value="F">Femenino</option>
                <option value="M">Masculino</option>
              </select>
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Fecha Nacimiento </div></td>
          <td>
            <div align="left">
              <input name="txtfecnacben" type="text" id="txtfecnacben" value="<?php print $ld_fecnacben;?>" size="15" maxlength="10" datepicker="true" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);">
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Estado Civil </div></td>
          <td>            <label>
              <div align="left">
                <select name="cmbestcivben" id="cmbestcivben">
                  <option value="" selected>--Seleccione--</option>
                  <option value="S">Soltero</option>
                  <option value="C">Casado</option>
                  <option value="V">Viudo</option>
                  <option value="D">Divorciado</option>
                  <option value="K">Concubino</option>
                </select>
              </div>
              </label>          </td>
        </tr>
        <tr>
          <td height="22"><div align="right">Fecha de Fallecimiento </div></td>
          <td>
            <div align="left">
              <input name="txtfecfalben" type="text" id="txtfecfalben" value="<?php print $ld_fecfalben;?>" size="15" maxlength="10" datepicker="true" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);">
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Banco</div></td>
          <td>
            <div align="left">
              <select name="cmbcodban" id="cmbcodban">
                <option value="" selected>--Seleccione--</option>
                <option value="001">Banco Central de Venezuela</option>
                <option value="003">Banco Industrial de Venezuela, C.A.</option>
                <option value="006">Banco de Coro, C.A.</option>
                <option value="007">Banco de Fomento Regional de los Andes</option>
                <option value="008">Banco Guayana, C.A.</option>
                <option value="102">Banco de Venezuela,SACA, Banco Universal</option>
                <option value="104">Banco Venezolano de Cr&eacute;dito,S.A. Banco Universal</option>
                <option value="105">Banco Mercantil C.A. SACA Banco Universal</option>
                <option value="108">Banco Provincial S.A. Banco Universal</option>
                <option value="114">Banco del Caribe C.A. Banco Universal</option>
                <option value="115">Banco Exterior C.A. Banco Universal</option>
                <option value="116">Banco Occidental de Descuento Banco Universal C.A. SACA</option>
                <option value="121">Corp Banca C.A. Banco Universal</option>
                <option value="128">Banco Caron&iacute; C.A. Banco Universal</option>
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
                <option value="194">Banco de Cr&eacute;dito de Colombia</option>
                <option value="196">Abn-amro N.V. (Sucursal Venezuela)</option>
                <option value="408">Pro-Vivienda E.A.P.</option>
                <option value="410">Casa Propia E.A.P.</option>
                <option value="425">Mi Casa E.A.P. C.A.</option>
                <option value="428">Banplus E.A.P. C.A.</option>
                <option value="601">Instituto Municipal de Cr&eacute;dito Popular</option>
              </select>
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">N&uacute;mero de Cuenta </div></td>
          <td>
            <div align="left">
              <input name="txtnumcueben" type="text" id="txtnumcueben" value="<?php print $ls_numcueben;?>" size="28" maxlength="25" onKeyUp="javascript: ue_validarnumero(this);">
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Tipo de Cuenta Banacaria </div></td>
          <td>
            <div align="left">
              <select name="cmbtipcueben" id="cmbtipcueben">
                <option value="" selected>--Seleccione--</option>
                <option value="A">Ahorro</option>
                <option value="C">Corriente</option>
              </select>
              </div></td></tr>
        <tr>
          <td height="22"><div align="right"></div></td>
          <td><input name="operacion" type="hidden" id="operacion">
            <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>">          </td>
        </tr>
      </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
<?php
	if($lb_valido===false)
	{
		print "<script language='javascript'>";
		print "		f=document.form1;";
		print "		f.cmbtiptraben.value='".$ls_tiptraben."';";
		print "		f.cmbcodpare.value='".$ls_codpare."';";
		print "		f.cmbnacben.value='".$ls_nacben."';";
		print "		f.cmbsexben.value='".$ls_sexben."';";
		print "		f.cmbestcivben.value='".$ls_estcivben."';";
		print "		f.cmbcodban.value='".$ls_codban."';";
		print "		f.cmbtipcueben.value='".$ls_tipcueben."';";
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
		codper=ue_validarvacio(f.txtcodper.value);
		nomper=ue_validarvacio(f.txtnomper.value);	
		fecnacper=f.txtfecnacper.value;	
		f.action="sigesp_snorh_d_ipasme_beneficiario.php?codper="+codper+"&nomper="+nomper+"&fecnacper="+fecnacper;
		f.submit();
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_volver()
{
	f=document.form1;
	f.operacion.value="BUSCAR";
	f.existe.value="TRUE";	
	codper=ue_validarvacio(f.txtcodper.value);
	f.action="sigesp_snorh_d_ipasme_afiliado.php?codper="+codper;
	f.submit();
}

function ue_guardar()
{
	f=document.form1;
	valido=true;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_existe=f.existe.value;
	if(((lb_existe=="TRUE")&&(li_cambiar==1))||(lb_existe=="FALSE")&&(li_incluir==1))
	{
		codper = ue_validarvacio(f.txtcodper.value);
		codben = ue_validarvacio(f.txtcodben.value);
		tiptraben = ue_validarvacio(f.cmbtiptraben.value);
		codpare = ue_validarvacio(f.cmbcodpare.value);
		nacben = ue_validarvacio(f.cmbnacben.value);
		prinomben=ue_validarvacio(f.txtprinomben.value);
		priapeben=ue_validarvacio(f.txtpriapeben.value);
		sexben=ue_validarvacio(f.cmbsexben.value);
		estcivben=ue_validarvacio(f.cmbestcivben.value);
		f.txtfecnacben.value=ue_validarfecha(f.txtfecnacben.value);	
		f.txtfecfalben.value=ue_validarfecha(f.txtfecfalben.value);	
		f.txtfecnacper.value=ue_validarfecha(f.txtfecnacper.value);	
		fecnacben=f.txtfecnacben.value;
		fecfalben=f.txtfecfalben.value;
		fecnacper=f.txtfecnacper.value;
		if(!((fecfalben=="01/01/1900")||(fecfalben=="1900-01-01")))
		{
			if(!ue_comparar_fechas(fecnacben,fecfalben))
			{
				alert(fecnacben+"   "+fecfalben);
				alert("La fecha de Fallecimiento es menor que la de Nacimiento.");
				valido=false;
			}
		}
		if(codpare=="01")// Padres
		{
			if(!ue_comparar_fechas(fecnacben,fecnacper))
			{
				alert("La fecha de Nacimiento del Padre es menor que la de Nacimiento del Afiliado.");
				valido=false;
			}
		}
		if(codpare=="02")// Abuelos
		{
			if(!ue_comparar_fechas(fecnacben,fecnacper))
			{
				alert("La fecha de Nacimiento del Abuelo es menor que la de Nacimiento del Afiliado.");
				valido=false;
			}
		}
		if(codpare=="03")// Hijos
		{
			if(!ue_comparar_fechas(fecnacper,fecnacben))
			{
				alert("La fecha de Nacimiento del Afiliado es menor que la de Nacimiento del Hijo.");
				valido=false;
			}
		}
		if(valido)
		{		
			if ((codper!="")&&(codben!="")&&(tiptraben!="")&&(codpare!="")&&(nacben!="")&&(prinomben!="")&&
				(priapeben!="")&&(sexben!="")&&(estcivben!="")&&(fecnacben!="")&&(fecnacben!="1900-01-01"))
			{
				f.operacion.value="GUARDAR";
				f.action="sigesp_snorh_d_ipasme_beneficiario.php";
				f.submit();
			}
			else
			{
				alert("Debe llenar todos los datos.");
			}
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
			codper = ue_validarvacio(f.txtcodper.value);
			codben = ue_validarvacio(f.txtcodben.value);
			if ((codper!="")&&(codben!=""))
			{
				if(confirm("¿Desea eliminar el Registro actual?"))
				{
					f.operacion.value="ELIMINAR";
					f.action="sigesp_snorh_d_ipasme_beneficiario.php";
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
		codper = ue_validarvacio(f.txtcodper.value);
		window.open("sigesp_snorh_cat_ipasme_beneficiario.php?codper="+codper+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
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

var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
</script> 
</html>