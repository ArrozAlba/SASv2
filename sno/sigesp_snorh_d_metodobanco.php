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
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_d_metodobanco.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $ls_codmet,$ls_desmet,$ls_tipmet,$ls_codempnom,$ls_tipcuecrenom,$ls_tipcuedebnom,$ls_numplalph;
		global $ls_numconlph,$ls_suclph,$ls_cuelph,$ls_grulph,$ls_subgrulph,$ls_conlph,$ls_numactlph,$ls_numofifps;
		global $ls_numfonfps,$ls_confps,$ls_nroplafps,$ls_nomtipmet,$ls_existe,$ls_operacion,$io_fun_nomina;
		global $ls_codofinom,$ls_debcuelph,$ls_codagelph,$ls_apaposlph,$ls_numconnom, $ls_pagtaqnom,$lb_ref;
		
		$ls_codmet="";			
		$ls_desmet="";
		$ls_tipmet="";			
		$ls_codempnom="";
		$ls_codofinom="";
		$ls_tipcuecrenom="";			
		$ls_tipcuedebnom="";			
		$ls_numplalph="";
		$ls_numconlph="";			
		$ls_suclph="";
		$ls_cuelph="";			
		$ls_grulph="";
		$ls_subgrulph="";			
		$ls_conlph="";
		$ls_numactlph="";			
		$ls_numofifps="";
		$ls_numfonfps="";			
		$ls_confps="";
		$ls_nroplafps="";			
		$ls_confps="";
		$ls_nroplafps="";			
		$ls_nomtipmet="";
		$ls_debcuelph="";
		$ls_codagelph="";
		$ls_apaposlph="";		
		$ls_numconnom="";
		$ls_pagtaqnom="";
		$lb_ref="";
		$ls_existe=$io_fun_nomina->uf_obtenerexiste();
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
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
   		global $ls_codmet,$ls_desmet,$ls_tipmet,$ls_nomtipmet,$ls_codempnom,$ls_tipcuecrenom;
		global $ls_tipcuedebnom,$ls_numplalph,$ls_numconlph,$ls_suclph,$ls_cuelph,$ls_grulph,$ls_subgrulph,$ls_conlph;
		global $ls_numactlph,$ls_numofifps,$ls_numfonfps,$ls_confps,$ls_nroplafps,$io_fun_nomina,$ls_codofinom,$ls_debcuelph;
		global $ls_codagelph,$ls_apaposlph,$ls_numconnom,$ls_pagtaqnom,$lb_ref;
		
		$ls_codmet=$_POST["txtcodmet"];
		$ls_desmet=$_POST["txtdesmet"];
		$ls_tipmet=$_POST["txttipmet"];	
		$ls_nomtipmet=$_POST["txtnomtipmet"];
		$ls_codempnom=$io_fun_nomina->uf_obtenervalor("txtcodempnom","");
		$ls_codofinom=$io_fun_nomina->uf_obtenervalor("txtcodofinom","");
		$ls_tipcuecrenom=$io_fun_nomina->uf_obtenervalor("txttipcuecrenom","");
		$ls_tipcuedebnom=$io_fun_nomina->uf_obtenervalor("txttipcuedebnom","");
		$ls_numconnom=$io_fun_nomina->uf_obtenervalor("txtnumconnom","");
		$ls_pagtaqnom=$io_fun_nomina->uf_obtenervalor("chkpagtaqnom","0");
		$ls_debcuelph=$io_fun_nomina->uf_obtenervalor("chkdebcuelph","0");
		$ls_numplalph=$io_fun_nomina->uf_obtenervalor("txtnumplalph","");
		$ls_numconlph=$io_fun_nomina->uf_obtenervalor("txtnumconlph","");
		$ls_suclph=$io_fun_nomina->uf_obtenervalor("txtsuclph","");
		$ls_cuelph=$io_fun_nomina->uf_obtenervalor("txtcuelph","");
		$ls_grulph=$io_fun_nomina->uf_obtenervalor("txtgrulph","");
		$ls_subgrulph=$io_fun_nomina->uf_obtenervalor("txtsubgrulph","");
		$ls_conlph=$io_fun_nomina->uf_obtenervalor("txtconlph","");
		$ls_numactlph=$io_fun_nomina->uf_obtenervalor("txtnumactlph","");
		$ls_numofifps=$io_fun_nomina->uf_obtenervalor("txtnumofifps","");
		$ls_numfonfps=$io_fun_nomina->uf_obtenervalor("txtnumfonfps","");
		$ls_confps=$io_fun_nomina->uf_obtenervalor("txtconfps","");
		$ls_nroplafps=$io_fun_nomina->uf_obtenervalor("txtnroplafps","");
		$ls_codagelph=$io_fun_nomina->uf_obtenervalor("txtcodagelph","");
		$ls_apaposlph=$io_fun_nomina->uf_obtenervalor("txtapaposlph","");
		$lb_ref=$io_fun_nomina->uf_obtenervalor("checkref","0");		
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
<title >Definici&oacute;n de M&eacute;todo a Banco</title>
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
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">

<script language="JavaScript" type="text/JavaScript">
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
//-->
</script>
<style type="text/css">
<!--
.Estilo1 {color: #6699CC}
-->
</style>
</head>

<body>
<?php 
	require_once("sigesp_snorh_c_metodobanco.php");
	$io_metodobanco=new sigesp_snorh_c_metodobanco();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "GUARDAR":
			uf_load_variables();
			$lb_valido=$io_metodobanco->uf_guardar($ls_existe,$ls_codmet,$ls_desmet,$ls_tipmet,$ls_codempnom,$ls_codofinom,
												   $ls_tipcuecrenom,$ls_tipcuedebnom,$ls_numplalph,$ls_numconlph,$ls_suclph,
												   $ls_cuelph,$ls_grulph,$ls_subgrulph,$ls_conlph,$ls_numactlph,$ls_numofifps,
												   $ls_numfonfps,$ls_confps,$ls_nroplafps,$ls_debcuelph,$ls_codagelph,
												   $ls_apaposlph,$ls_numconnom,$ls_pagtaqnom,$lb_ref,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
			}
			break;

		case "ELIMINAR":
			uf_load_variables();
			$lb_valido=$io_metodobanco->uf_delete($ls_codmet,$ls_tipmet,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
			}
			break;
			
		case "BUSCAR":
			uf_load_variables();
			$lb_valido=$io_metodobanco->uf_load_metodobanco($ls_existe,$ls_codmet,$ls_desmet,$ls_tipmet,$ls_codempnom,$ls_codofinom,
														    $ls_tipcuecrenom,$ls_tipcuedebnom,$ls_numplalph,$ls_numconlph,
															$ls_suclph,$ls_cuelph,$ls_grulph,$ls_subgrulph,$ls_conlph,$ls_numactlph,
															$ls_numofifps,$ls_numfonfps,$ls_confps,$ls_nroplafps,$ls_debcuelph,
															$ls_codagelph,$ls_apaposlph,$ls_numconnom,$ls_pagtaqnom,$lb_ref);
			$io_fun_nomina->uf_seleccionarcombo("1-2-3",$ls_tipmet,$la_tipmet,3);
			if($ls_debcuelph=="1")
			{
				$ls_debcuelph="checked";
			}
			else
			{
				$ls_debcuelph="";
			}
			break;

		case "CARGARMETODO":
			uf_load_variables();
			break;
	}
	$io_metodobanco->uf_destructor();
	unset($io_metodobanco);
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
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
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
<table width="550" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="510" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="2" class="titulo-ventana">Definici&oacute;n de M&eacute;todo a Banco </td>
        </tr>
        <tr>
          <td width="159" height="22"><div align="right"></div></td>
          <td width="345">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo</div></td>
          <td>            <div align="left">
            <input name="txtcodmet" type="text" id="txtcodmet" value="<?php print $ls_codmet;?>" size="7" maxlength="4" onKeyUp="javascript: ue_validarnumero(this);" onBlur="javascript: ue_rellenarcampo(this,4);" readonly>          
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Descripci&oacute;n</div></td>
          <td>            <div align="left">
            <input name="txtdesmet" type="text" id="txtdesmet" value="<?php print $ls_desmet;?>" size="60" maxlength="100" onKeyUp="javascript: ue_validarcomillas(this);" readonly>          
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Tipo</div></td>
          <td>            <div align="left">
                <input name="txtnomtipmet" type="text" id="txtnomtipmet" value="<?php print $ls_nomtipmet;?>" size="50" maxlength="100" readonly>            
                <input name="txttipmet" type="hidden" id="txttipmet" value="<?php print $ls_tipmet;?>">          
            </div></td>
        </tr>
<?php if($ls_tipmet=="0"){ // Es de nómina ?>		
        <tr>
          <td height="22"><div align="right">C&oacute;digo de Empresa </div></td>
          <td>              <div align="left">
            <input name="txtcodempnom" type="text" id="txtcodempnom" value="<?php print $ls_codempnom;?>" size="13" maxlength="10" onKeyUp="javascript: ue_validarcomillas(this);">            
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Nro. Convenio/C&oacute;d. Banco </div></td>
          <td><div align="left">
            <input name="txtnumconnom" type="text" id="txtnumconnom" value="<?php print $ls_numconnom;?>" size="10" maxlength="8" onKeyUp="javascript: ue_validarcomillas(this);">          
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo de Oficina </div></td>
          <td><div align="left">
            <input name="txtcodofinom" type="text" id="txtcodofinom" value="<?php print $ls_codofinom;?>" size="9" maxlength="5" onKeyUp="javascript: ue_validarcomillas(this);">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Tipo de Cuenta Cr&eacute;dito</div></td>
          <td>              <div align="left">
            <input name="txttipcuecrenom" type="text" id="txttipcuecrenom" value="<?php print $ls_tipcuecrenom;?>" size="5" maxlength="2" onKeyUp="javascript: ue_validarcomillas(this);">		
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Tipo de Cuenta D&eacute;bito </div></td>
          <td><div align="left">
            <input name="txttipcuedebnom" type="text" id="txttipcuedebnom" value="<?php print $ls_tipcuedebnom;?>" size="5" maxlength="2" onKeyUp="javascript: ue_validarcomillas(this);">          
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Pago por Taquilla</div></td>
          <td><div align="left"><input name="chkpagtaqnom" type="checkbox" class="sin-borde" id="chkpagtaqnom" value="1" <?php if($ls_pagtaqnom=="1"){print "checked"; }?>></div></td>
        </tr>
		<tr>
          <td height="22"><div align="right">Autoincrementar Nro de Ref. </div></td>
              <td><label>			    
               <input type="checkbox" name="checkref" id="checkref" value="1" <?php if($lb_ref=="1"){print "checked";};?>>
              </label>
		</td>
        </tr>
<?php } 
	  if($ls_tipmet=="1"){ // Es de Ley de Política ?>		
        <tr>
          <td height="22"><div align="right">Código de Agencia</div></td>
          <td>            <div align="left">
            <input name="txtcodagelph" type="text" id="txtcodagelph" value="<?php print $ls_codagelph;?>" size="18" maxlength="3" onKeyUp="javascript: ue_validarcomillas(this);">          
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Apartado Postal</div></td>
          <td>            <div align="left">
            <input name="txtapaposlph" type="text" id="txtapaposlph" value="<?php print $ls_apaposlph;?>" size="18" maxlength="8" onKeyUp="javascript: ue_validarcomillas(this);">          
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Debito a Cuenta </div></td>
          <td>            <div align="left">
            <input name="chkdebcuelph" type="checkbox" class="sin-borde" id="chkdebcuelph" value="1" <?php print $ls_debcuelph;?>>          
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Nro de Planilla </div></td>
          <td>            <div align="left">
            <input name="txtnumplalph" type="text" id="txtnumplalph" value="<?php print $ls_numplalph;?>" size="18" maxlength="15" onKeyUp="javascript: ue_validarcomillas(this);">          
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Nro de Contrato</div></td>
          <td>            <div align="left">
            <input name="txtnumconlph" type="text" id="txtnumconlph" value="<?php print $ls_numconlph;?>" size="13" maxlength="10" onKeyUp="javascript: ue_validarcomillas(this);">          
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Sucursal</div></td>
          <td>            <div align="left">
            <input name="txtsuclph" type="text" id="txtsuclph" value="<?php print $ls_suclph;?>" size="8" maxlength="5" onKeyUp="javascript: ue_validarcomillas(this);">          
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Cuenta</div></td>
          <td>            <div align="left">
            <input name="txtcuelph" type="text" id="txtcuelph" value="<?php print $ls_cuelph;?>" size="28" maxlength="25" onKeyUp="javascript: ue_validarcomillas(this);">          
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Grupo</div></td>
          <td>            <div align="left">
            <input name="txtgrulph" type="text" id="txtgrulph" value="<?php print $ls_grulph;?>" size="13" maxlength="10" onKeyUp="javascript: ue_validarcomillas(this);">          
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Subgrupo </div></td>
          <td>            <div align="left">
            <input name="txtsubgrulph" type="text" id="txtsubgrulph" value="<?php print $ls_subgrulph;?>" size="8" maxlength="5" onKeyUp="javascript: ue_validarcomillas(this);">          
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Contrato</div></td>
          <td>            <div align="left">
            <input name="txtconlph" type="text" id="txtconlph" value="<?php print $ls_conlph;?>" size="18" maxlength="15" onKeyUp="javascript: ue_validarcomillas(this);">          
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Nro de Archivo</div></td>
          <td>            <div align="left">
            <input name="txtnumactlph" type="text" id="txtnumactlph" value="<?php print $ls_numactlph;?>" size="13" maxlength="10" onKeyUp="javascript: ue_validarcomillas(this);">          
          </div></td>
        </tr>
<?php } 
	  if($ls_tipmet=="2"){ // Es  de Prestaciones Sociales ?>		
        <tr>
          <td height="22"><div align="right">Nro de Oficina</div></td>
          <td>            <div align="left">
            <input name="txtnumofifps" type="text" id="txtnumofifps" value="<?php print $ls_numofifps;?>" size="8" maxlength="3" onKeyUp="javascript: ue_validarcomillas(this);">          
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Nro de Fondo</div></td>
          <td>            <div align="left">
            <input name="txtnumfonfps" type="text" id="txtnumfonfps" value="<?php print $ls_numfonfps;?>" size="13" maxlength="6" onKeyUp="javascript: ue_validarcomillas(this);">          
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Contrato</div></td>
          <td>            <div align="left">
            <input name="txtconfps" type="text" id="txtconfps" value="<?php print $ls_confps;?>" size="13" maxlength="6" onKeyUp="javascript: ue_validarcomillas(this);">          
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Nro Plan </div></td>
          <td>            <div align="left">
            <input name="txtnroplafps" type="text" id="txtnroplafps" value="<?php print $ls_nroplafps;?>" size="13" maxlength="10" onKeyUp="javascript: ue_validarcomillas(this);">          
          </div></td>
        </tr>
<?php } ?>
        <tr>
          <td>&nbsp;</td>
          <td><input name="operacion" type="hidden" id="operacion">
            <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>"></td>
        </tr>
      </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>      
<p>&nbsp;</p>
</body>
<script language="javascript">
function ue_cargarcamposmetodo()
{
	f=document.form1;
	f.operacion.value="CARGARMETODO";
	f.action="sigesp_snorh_d_metodobanco.php";
	f.submit();
}

function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		f.operacion.value="NUEVO";
		f.existe.value="FALSE";	
		f.action="sigesp_snorh_d_metodobanco.php";
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
		codmet = ue_validarvacio(f.txtcodmet.value);
		desmet = ue_validarvacio(f.txtdesmet.value);
		tipmet = ue_validarvacio(f.txttipmet.value);
		if ((codmet!="")&&(desmet!="")&&(tipmet!=""))
		{
			f.operacion.value="GUARDAR";
			f.action="sigesp_snorh_d_metodobanco.php";
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
		codmet = ue_validarvacio(f.txtcodmet.value);
		tipmet = ue_validarvacio(f.cmbtipmet.value);
		if ((codmet!="")&&(tipmet!=""))
		{
			if(confirm("¿Desea eliminar el Registro actual?"))
			{
				f.operacion.value="ELIMINAR";
				f.action="sigesp_snorh_d_metodobanco.php";
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
		window.open("sigesp_snorh_cat_metodobanco.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
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
	//window.open("../hlp/index.php?sistema=SNO&subsistema=SNR&nomfis=sno/sigesp_hlp_snr_metodobanco.php","Ayuda","menubar=no,toolbar=no,scrollbars=yes,width="+width+",height="+height+",resizable=yes,location=no");
}
</script> 
</html>