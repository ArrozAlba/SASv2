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
	$ls_codnom=$_SESSION["la_nomina"]["codnom"];
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad_nomina("SNO","sigesp_sno_p_prestamo.php",$ls_codnom,$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codper,$ls_nomper,$ls_codtippre,$ls_destippre,$ls_codconc,$ls_nomcon,$li_stapre,$la_stapre,$li_monpre,$li_amoprepre;
		global $li_numcuopre,$ls_perinipre,$li_salactpre,$li_moncuopre,$li_monamopre,$ld_fecdesper,$ld_fechasper,$li_numpre,$li_cuofal;
		global $li_sueper,$ls_personal,$ls_tipoprestamo,$ls_concepto,$ls_periodo,$ls_status,$ls_desactivar,$ls_botones,$ls_existe;
		global $ls_operacion,$ls_desnom,$io_fun_nomina,$ls_desper,$ls_configuracion,$ls_cuota;
		global $ls_tipocuota,$la_tipcuopre,$ls_tipcuopre,$li_valporpre;
		
		require_once("sigesp_sno.php");
		$io_sno=new sigesp_sno();
		$lb_valido=$io_sno->uf_crear_sessionnomina();		
		$ls_desnom="";
		$ls_peractnom="";
		$ls_desper="";			
		if($lb_valido==false)
		{
			print "<script language=JavaScript>";
			print "location.href='sigespwindow_blank.php'";
			print "</script>";		
		}
		else
		{
			$ls_desnom=$_SESSION["la_nomina"]["desnom"];
			$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
			$ls_desper=$_SESSION["la_nomina"]["descripcionperiodo"];
			$ls_tippernom=$_SESSION["la_nomina"]["tippernom"];
		}
		$ls_codper="";
		$ls_nomper="";
		$ls_codtippre="";
		$ls_destippre="";
		$ls_codconc="";
		$ls_nomcon="";
		$li_stapre="";
		$ls_tipocuota=" disabled";
		if($ls_tippernom=="1")
		{
			$ls_tipocuota="";
		}	
		$la_tipcuopre[0]="";
		$la_tipcuopre[1]="";
		$ls_tipcuopre="0";
		$la_stapre[0]="";
		$la_stapre[1]="";
		$la_stapre[2]="";
		$li_monpre=0;
		$li_numcuopre=1;
		$li_amoprepre=0;
		$li_numpre="";
		$ls_perinipre="";
		$li_salactpre=0;
		$li_moncuopre=0;
		$li_monamopre=0;
		$li_numpre=0;
		$li_cuofal=0;
		$ld_fecdesper="dd/mm/aaaa";
		$ld_fechasper="dd/mm/aaaa";
		$li_sueper=0;
		$ls_personal="style='visibility:visible'";
		$ls_tipoprestamo="style='visibility:visible'";
		$ls_concepto="style='visibility:visible'";						
		$ls_periodo="style='visibility:visible'";
		$ls_cuota="style='visibility:visible'";
		$ls_status="";						
		$ls_desactivar="";		
		$ls_botones=" disabled";	
		$ls_existe=$io_fun_nomina->uf_obtenerexiste();
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		$ls_configuracion=trim($io_sno->uf_select_config("SNO","CONFIG","CONFIGURACION_PRESTAMO","CUOTAS","C"));
		$li_valporpre=trim($io_sno->uf_select_config("SNO","CONFIG","VAL_PORCENTAJE_PRESTAMO","1","I"));
		unset($io_sno);			
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
   		global $ls_codper, $ls_nomper, $ls_codtippre, $ls_destippre, $ls_codconc, $ls_nomcon, $li_monpre, $li_numcuopre;
		global $ls_perinipre, $li_salactpre, $li_moncuopre, $li_monamopre, $ld_fecdesper, $ld_fechasper, $li_numpre, $li_sueper;
		global $li_stapre,$io_fun_nomina,$ls_tipcuopre;
	
		$ls_codper=$_POST["txtcodper"];
		$ls_nomper=$_POST["txtnomper"];
		$ls_codtippre=$_POST["txtcodtippre"];
		$ls_destippre=$_POST["txtdestippre"];
		$ls_codconc=$_POST["txtcodconc"];
		$ls_nomcon=$_POST["txtnomcon"];
		$li_monpre=$_POST["txtmonpre"];
		$li_numcuopre=$_POST["txtnumcuopre"];
		$ls_perinipre=$_POST["txtperinipre"];
		$li_salactpre=$_POST["txtsalactpre"];
		$li_moncuopre=$_POST["txtmoncuopre"];
		$li_monamopre=$_POST["txtmonamopre"];
		$ld_fecdesper=$_POST["txtfecdesper"];
		$ld_fechasper=$_POST["txtfechasper"];
		$li_numpre=$_POST["txtnumpre"];
		$li_sueper=$_POST["txtsueper"];
		$li_stapre=$io_fun_nomina->uf_obtenervalor("cmbstapre","");
		$ls_tipcuopre=$io_fun_nomina->uf_obtenervalor("cmbtipcuopre",$_POST["txttipcuopre"]);
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
<title >Prestamos</title>
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_nomina.js"></script>
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php 
	require_once("sigesp_sno_c_prestamo.php");
	$io_prestamo=new sigesp_sno_c_prestamo();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "GUARDAR":
			uf_load_variables();
			$lb_valido=$io_prestamo->uf_guardar($ls_existe,$ls_codper,$ls_codtippre,$li_numpre,$ls_codconc,$li_stapre,$li_monpre,
											    $li_numcuopre,$ls_perinipre,$li_monamopre,$ld_fecdesper,$ld_fechasper,$li_sueper,
											    $li_moncuopre,$ls_configuracion,$ls_tipcuopre,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
			}
			else
			{
				$io_fun_nomina->uf_seleccionarcombo("1-2-3",$li_stapre,$la_stapre,3);
				$io_fun_nomina->uf_seleccionarcombo("0-1",$ls_tipcuopre,$la_tipcuopre,2);
			}
			break;

		case "ELIMINAR":
			uf_load_variables();
			$lb_valido=$io_prestamo->uf_delete($ls_codper,$ls_codtippre,$li_numpre,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
			}
			else
			{
				$io_fun_nomina->uf_seleccionarcombo("1-2-3",$li_stapre,$la_stapre,3);
				$io_fun_nomina->uf_seleccionarcombo("0-1",$ls_tipcuopre,$la_tipcuopre,2);
			}
			break;
			
		case "BUSCAR":
			$ls_codper=$_POST["txtcodper"];
			$li_numpre=$_POST["txtnumpre"];
			$ls_codtippre=$_POST["txtcodtippre"];
			$lb_valido=$io_prestamo->uf_load_prestamo($ls_codper,$li_numpre,$ls_codtippre,$ls_nomper,$ls_destippre,$ls_codconc,
			                                          $ls_nomcon,$li_stapre,$li_monpre,$li_numcuopre,$ls_perinipre,$li_salactpre,
													  $li_moncuopre,$li_monamopre,$ld_fecdesper,$ld_fechasper,$li_sueper,$li_cuofal,
													  $ls_tipcuopre);
			$io_fun_nomina->uf_seleccionarcombo("1-2-3",$li_stapre,$la_stapre,3);
			$io_fun_nomina->uf_seleccionarcombo("0-1",$ls_tipcuopre,$la_tipcuopre,2);
			$ls_personal="style='visibility:hidden'";
			$ls_tipoprestamo="style='visibility:hidden'";
			$ls_concepto="style='visibility:hidden'";						
			$ls_periodo="style='visibility:hidden'";
			$ls_cuota="style='visibility:hidden'";
			$ls_status=" disabled";						
			$ls_desactivar=" readOnly";		
			$ls_botones="";		
			$ls_tipocuota=" disabled";
			break;		
	}
	$io_prestamo->uf_destructor();
	unset($io_prestamo);
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema"><?php print $ls_desnom;?></td>
			<td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><?php print $ls_desper;?></span></div></td>
			 <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td></tr>
	  </table>
	</td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_nomina.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" title='Nuevo' alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title='Guardar 'alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" title='Buscar' alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" title='Eliminar' alt="Eliminar" width="20" height="18" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title='Salir' alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" title='Ayuda' alt="Ayuda" width="20" height="20"></div></td>
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
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank_nomina.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="635" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="585" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-ventana">Prestamos</td>
        </tr>
        <tr>
          <td width="114" height="22">&nbsp;</td>
          <td width="462" colspan="3">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">Personal</div></td>
          <td><div align="left">
            <input name="txtcodper" type="text" id="txtcodper" size="13" maxlength="10" value="<?php print $ls_codper;?>" readonly>
            <a href="javascript: ue_buscarpersonal();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0" <? print $ls_personal; ?>></a>
            <input name="txtnomper" type="text" class="sin-borde" id="txtnomper" size="63" maxlength="100" value="<?php print $ls_nomper;?>" readonly>
            <input name="txtsueper" type="hidden" id="txtsueper" value="<?php print $li_sueper;?>"> 
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Tipo de Prestamo </div></td>
          <td>
            <div align="left">
              <input name="txtcodtippre" type="text" id="txtcodtippre" value="<?php print $ls_codtippre;?>" size="13" maxlength="10"  readonly>
              <a href="javascript: ue_buscarprestamo();"><img id="tipo" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0" <?php print $ls_tipoprestamo; ?>></a>
              <input name="txtdestippre" type="text" class="sin-borde" id="txtdestippre" value="<?php print $ls_destippre;?>" size="63" maxlength="100" readonly>
            </div></td></tr>
        <tr>
          <td height="22"><div align="right">Concepto</div></td>
          <td>
            <div align="left">
              <input name="txtcodconc" type="text" id="txtcodconc" value="<?php print $ls_codconc;?>" size="13" maxlength="10" readonly>
              <a href="javascript: ue_buscarconcepto();"><img id="concepto" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0" <?php print $ls_concepto; ?>></a>
              <input name="txtnomcon" type="text" class="sin-borde" id="txtnomcon" value="<?php print $ls_nomcon;?>" size="63" maxlength="30" readonly>
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Per&iacute;odo de Inicio </div></td>
          <td><div align="left">
            <input name="txtperinipre" type="text" id="txtperinipre" value="<?php print $ls_perinipre;?>" size="6" maxlength="3" readonly>
            <a href="javascript: ue_buscarperiodo();"><img id="periodo" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0" <?php print $ls_periodo; ?>></a>
            <input name="txtfecdesper" type="text" id="txtfecdesper" value="<?php print $ld_fecdesper;?>" size="13" maxlength="10" readonly>
            -
            <input name="txtfechasper" type="text" id="txtfechasper" value="<?php print $ld_fechasper;?>" size="13" maxlength="10" readonly>
          </div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">Tipo de Cuota </div></td>
          <td><label>
            <select name="cmbtipcuopre" id="cmbtipcuopre" <?php print $ls_tipocuota; ?>>
              <option value="0" <?php print $la_tipcuopre[0]; ?>>Por periodo</option>
              <option value="1" <?php print $la_tipcuopre[1]; ?>>Mensual</option>
            </select>
            <input name="txttipcuopre" type="hidden" id="txttipcuopre" value="<?php print $ls_tipcuopre; ?>">
          (Solo para n&oacute;minas quincenales)</label></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Estado Actual </div></td>
          <td>
            <div align="left">
              <select name="cmbstapre" id="cmbstapre" <?php print $ls_status;?>>
                <option value="" selected>--Seleccione Uno--</option>
                <option value="1" <?php print $la_stapre[0]; ?>>Activo</option>
                <option value="2" <?php print $la_stapre[1]; ?>>Suspendido</option>
                <option value="3" <?php print $la_stapre[2]; ?>>Cancelado</option>
              </select>
            </div></td></tr>
        <tr>
          <td height="22"><div align="right">Monto Prestamo </div></td>
          <td>
            <div align="left">
              <input name="txtmonpre" type="text" id="txtmonpre" value="<?php print $li_monpre;?>" size="23" maxlength="20" onKeyPress="javascript: return(ue_formatonumero(this,'.',',',event))" style="text-align:right" <?php print $ls_desactivar;?>>
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Nro Cuotas </div></td>
          <td>
            <div align="left">
              <input name="txtnumcuopre" type="text" id="txtnumcuopre" value="<?php print $li_numcuopre;?>" size="7" maxlength="4" style="text-align:right" onKeyPress="javascript: ue_validarnumero(this);" <?php if($ls_configuracion=="MONTO"){ print " readonly ";}else{ print $ls_desactivar;} ?>>
<?php if($ls_configuracion=="CUOTAS"){
?>
              <a href="javascript: ue_actualizarcuota();"><img id="cuota" src="../shared/imagebank/tools20/actualizar.jpg" alt="Cuota" width="15" height="15" border="0" <?php print $ls_cuota; ?>></a>
<?php }
?>
			  </div></td></tr>
        <tr>
          <td height="22"><div align="right">Monto Cuotas</div></td>
          <td>
            <div align="left">
              <input name="txtmoncuopre" type="text" id="txtmoncuopre" value="<?php print $li_moncuopre;?>" size="23" maxlength="20" style="text-align:right" onKeyPress="javascript: return(ue_formatonumero(this,'.',',',event))"  <?php if($ls_configuracion=="CUOTAS"){ print " readonly ";}else{ print $ls_desactivar;} ?>>
<?php if($ls_configuracion=="MONTO"){
?>
              <a href="javascript: ue_actualizarcuotamonto();"><img id="cuota" src="../shared/imagebank/tools20/actualizar.jpg" alt="Cuota" width="15" height="15" border="0" <?php print $ls_cuota; ?>></a>
<?php }
?>
			  </div></td></tr>

        <tr>
          <td height="22"><div align="right">Amortizado</div></td>
          <td>
            <div align="left">
              <input name="txtmonamopre" type="text" id="txtmonamopre" value="<?php print $li_monamopre;?>" size="23" maxlength="20" style="text-align:right" readonly>
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Saldo</div></td>
          <td>
            <div align="left">
              <input name="txtsalactpre" type="text" id="txtsalactpre" value="<?php print $li_salactpre;?>" size="23" maxlength="20" style="text-align:right" readonly>
              </div></td></tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td><input name="operacion" type="hidden" id="operacion">
            <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>">            
            <input name="txtnumpre" type="hidden" id="txtnumpre" value="<?php print $li_numpre;?>">
            <input name="txtcuofal" type="hidden" id="txtcuofal" value="<?php print $li_cuofal;?>">
            <input name="configuracion" type="hidden" id="configuracion" value="<?php print $ls_configuracion;?>">
            <input name="valporpre"  type="hidden" id="valporpre" value="<?php print $li_valporpre;?>"></td>
        </tr>
        <tr>
          <td height="22" colspan="2">
            <div align="center">
              <input name="btncuotas" type="button" class="boton" id="btncuotas" value="Ver Cuotas" onClick="javascript: ue_buscarcuotas();" <?php print $ls_botones;?>>
              <input name="btnrecalcular" type="button" class="boton" id="btnrecalcular" value="Recalcular Cuotas" onClick="javascript: ue_recalcular();" <?php print $ls_botones;?>>
              <input name="btnsuspender" type="button" class="boton" id="btnsuspender" value="Suspender Cuotas" onClick="javascript: ue_suspender();" <?php print $ls_botones;?>>
              <input name="btnamortizar" type="button" class="boton" id="btnamortizar" value="Amortizar" onClick="javascript: ue_amortizar();" <?php print $ls_botones;?>>
              <input name="btnrefinanciar" type="button" class="boton" id="btnrefinanciar" value="Refinanciar" onClick="javascript: ue_refinanciar();" <?php print $ls_botones;?>>
            </div></td>
          </tr>
      </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
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
		f.action="sigesp_sno_p_prestamo.php";
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
		if(f.existe.value=="FALSE")
		{
			valido=true;
			codper = ue_validarvacio(f.txtcodper.value);
			codtippre = ue_validarvacio(f.txtcodtippre.value);
			codconc = ue_validarvacio(f.txtcodconc.value);
			perinipre = ue_validarvacio(f.txtperinipre.value);
			stapre = ue_validarvacio(f.cmbstapre.value);
			monpre = ue_validarvacio(f.txtmonpre.value);
			numcuopre = ue_validarvacio(f.txtnumcuopre.value);
			valporpre = ue_validarvacio(f.valporpre.value);
			if(valporpre==1)
			{
				sueper=f.txtsueper.value;
				sueper=(sueper*0.3);
				moncuopre=f.txtmoncuopre.value;
				while(moncuopre.indexOf('.')>0)
				{//Elimino todos los puntos o separadores de miles
					moncuopre=moncuopre.replace(".","");
				}
				moncuopre=moncuopre.replace(",",".");
			
				if(sueper<moncuopre)
				{
					valido=false;
					alert("El monto de la cuota no puede ser mayor que el 30% del sueldo");
				}	
			}
			if(f.existe.value=="FALSE")
			{
				if((stapre!="1"))
				{
					alert("Un prestamo nuevo debe estar Activo");
					valido=false;
				}
			}
			
			if(valido)
			{
				if ((codper!="")&&(codtippre!="")&&(codconc!="")&&(perinipre!="")&&(stapre!="")&&(monpre!="")&&
					(monpre!="0")&&(numcuopre!="")&&(numcuopre!="0")&&(moncuopre!="")&&(moncuopre!="0"))
				{
					f.operacion.value="GUARDAR";
					f.action="sigesp_sno_p_prestamo.php";
					f.submit();
				}
				else
				{
					alert("Debe llenar todos los datos.");
				}
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
			codtippre = ue_validarvacio(f.txtcodtippre.value);
			numpre=ue_validarvacio(f.txtnumpre.value);
			if ((codper!="")&&(codtippre!="")&&(numpre!=""))
			{
				if(confirm("¿Desea eliminar el Registro actual?"))
				{
					f.operacion.value="ELIMINAR";
					f.action="sigesp_sno_p_prestamo.php";
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
	location.href = "sigespwindow_blank_nomina.php";
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		window.open("sigesp_sno_cat_prestamo.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}		
}

function ue_buscarcuotas()
{
	f=document.form1;
	numpre=f.txtnumpre.value;
	codper=f.txtcodper.value;
	codtippre=f.txtcodtippre.value;
	if(f.existe.value=="TRUE")
	{
		window.open("sigesp_sno_prestamocuota.php?numpre="+numpre+"&codper="+codper+"&codtippre="+codtippre+"","Cuotas","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
}

function ue_recalcular()
{
	f=document.form1;
	if(f.existe.value=="TRUE")
	{
		if(f.cmbstapre.value=="1")
		{
			f.operacion.value="NUEVO";
			f.existe.value="TRUE";	
				
			f.action="sigesp_sno_p_prestamorecalcular.php?cmbstapre="+f.cmbstapre.value;
			f.submit();
		}
		else
		{
			alert("No se puede recalcular un prestamo que está Suspendido ó Cancelado.");
		}
	}
}

function ue_suspender()
{
	f=document.form1;
	if(f.existe.value=="TRUE")
	{
		if(f.cmbstapre.value=="1")
		{
			f.operacion.value="NUEVO";
			f.existe.value="TRUE";	
				
			f.action="sigesp_sno_p_prestamosuspender.php?cmbstapre="+f.cmbstapre.value;
			f.submit();
		}
		else
		{
			alert("No se puede Suspender un prestamo que está Suspendido ó Cancelado.");
		}
	}
}

function ue_amortizar()
{
	f=document.form1;
	if(f.existe.value=="TRUE")
	{
		if(f.cmbstapre.value=="1")
		{
			f.operacion.value="NUEVO";
			f.existe.value="TRUE";	
				
			f.action="sigesp_sno_p_prestamoamortizar.php?cmbstapre="+f.cmbstapre.value;
			f.submit();
		}
		else
		{
			alert("No se puede amortizar un prestamo que está Suspendido ó Cancelado.");
		}
	}
}

function ue_refinanciar()
{
	f=document.form1;
	if(f.existe.value=="TRUE")
	{
		if(f.cmbstapre.value=="1")
		{
			f.operacion.value="NUEVO";
			f.existe.value="TRUE";	
				
			f.action="sigesp_sno_p_prestamorefinanciar.php?cmbstapre="+f.cmbstapre.value;
			f.submit();
		}
		else
		{
			alert("No se puede amortizar un prestamo que está Suspendido ó Cancelado.");
		}
	}
}

function ue_buscarpersonal()
{
	f=document.form1;
	if(f.existe.value=="FALSE")
	{
		window.open("sigesp_sno_cat_personalnomina.php?tipo=prestamo","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
}

function ue_buscarprestamo()
{
	f=document.form1;
	if(f.existe.value=="FALSE")
	{
		window.open("sigesp_sno_cat_tipoprestamo.php?tipo=prestamo","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
}

function ue_buscarconcepto()
{
	f=document.form1;
	if(f.existe.value=="FALSE")
	{
		window.open("sigesp_sno_cat_concepto.php?tipo=PRESTAMO","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
}

function ue_buscarperiodo()
{
	f=document.form1;
	if(f.existe.value=="FALSE")
	{
		window.open("sigesp_sno_cat_periodo.php?tipo=prestamo","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
}

//--------------------------------------------------------
//	Función que calcula las cuotas mensuales por cuota
//--------------------------------------------------------
function ue_actualizarcuota()
{
	f=document.form1;
	if(f.existe.value=="FALSE")
	{
		monpre=f.txtmonpre.value;
		numcuopre=f.txtnumcuopre.value;
		monamopre=f.txtmonamopre.value;
		if(monpre=="")
		{
			monpre=0;
		}
		if((numcuopre!="")&&(numcuopre>0))
		{
			while(monpre.indexOf('.')>0)
			{//Elimino todos los puntos o separadores de miles
				monpre=monpre.replace(".","");
			}
			monpre=monpre.replace(",",".");
		
			while(monamopre.indexOf('.')>0)
			{//Elimino todos los puntos o separadores de miles
				monamopre=monamopre.replace(".","");
			}
			monamopre=monamopre.replace(",",".");
	
			moncuopre=(monpre/numcuopre);
		
			if((monpre<0)||(moncuopre<0))
			{
				alert("El saldo está negativo!!!");
				f.txtmonpre.value=0;
				f.txtamoprepre.value=0;
				f.txtsalactpre.value=0;
				f.txtmoncuopre.value=0;
			}
			else
			{
				salactpre=(monpre-monamopre);
				salactpre=uf_convertir(salactpre);
				moncuopre=uf_convertir(moncuopre);
				f.txtsalactpre.value=salactpre;
				f.txtmoncuopre.value=moncuopre;
			}
		}
		else
		{
			f.txtnumcuopre.value="";
			alert("Número de Cuotas Inválido");
		}
	}
}

//--------------------------------------------------------
//	Función que calcula las cuotas mensuales por monto
//--------------------------------------------------------
function ue_actualizarcuotamonto()
{
	f=document.form1;
	if(f.existe.value=="FALSE")
	{
		monpre=f.txtmonpre.value;
		moncuopre=f.txtmoncuopre.value;
		monamopre=f.txtmonamopre.value;
		if(monpre=="")
		{
			monpre=0;
		}
		while(moncuopre.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			moncuopre=moncuopre.replace(".","");
		}
		moncuopre=moncuopre.replace(",",".");
		if((moncuopre!="")&&(moncuopre>0))
		{
			while(monpre.indexOf('.')>0)
			{//Elimino todos los puntos o separadores de miles
				monpre=monpre.replace(".","");
			}
			monpre=monpre.replace(",",".");
		
			while(monamopre.indexOf('.')>0)
			{//Elimino todos los puntos o separadores de miles
				monamopre=monamopre.replace(".","");
			}
			monamopre=monamopre.replace(",",".");
	
	
			numcuopre=Math.ceil((monpre/moncuopre));
		
			if((monpre<0)||(numcuopre<0))
			{
				alert("El saldo está negativo!!!");
				f.txtmonpre.value=0;
				f.txtamoprepre.value=0;
				f.txtsalactpre.value=0;
				f.txtmoncuopre.value=0;
				f.txtmoncuopre.value=0;
			}
			else
			{
				salactpre=(monpre-monamopre);
				salactpre=uf_convertir(salactpre);
				moncuopre=uf_convertir(moncuopre);
				f.txtsalactpre.value=salactpre;
				f.txtmoncuopre.value=moncuopre;
				f.txtnumcuopre.value=numcuopre;
			}
		}
		else
		{
			f.txtmoncuopre.value="";
			f.txtnumcuopre.value="0";
			alert("Monto de Cuotas Inválido");
		}
	}
}
</script> 
</html>