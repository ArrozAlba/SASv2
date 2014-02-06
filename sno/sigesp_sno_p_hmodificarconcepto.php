<?php
    session_start();
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
	$ls_loncodestpro1=$_SESSION["la_empresa"]["loncodestpro1"];
	$ls_loncodestpro2=$_SESSION["la_empresa"]["loncodestpro2"];
	$ls_loncodestpro3=$_SESSION["la_empresa"]["loncodestpro3"];
	$ls_loncodestpro4=$_SESSION["la_empresa"]["loncodestpro4"];
	$ls_loncodestpro5=$_SESSION["la_empresa"]["loncodestpro5"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_codnom=$_SESSION["la_nomina"]["codnom"];
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad_nomina("SNO","sigesp_sno_p_hmodificarconcepto.php",$ls_codnom,$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("sigesp_sno.php");
	$io_sno=new sigesp_sno();
	global $ls_sueint;
	$ls_sueint=trim($io_sno->uf_select_config("SNO","NOMINA","DENOMINACION SUELDO INTEGRAL","C",""));
	unset($io_sno);
   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 10/04/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codconc,$ls_nomcon,$ls_titcon,$ls_nomestpro1,$ls_nomestpro2,$ls_nomestpro3,$ls_codestpro1,$ls_codestpro2;
		global $ls_codestpro3,$ls_denestpro1,$ls_denestpro2,$ls_denestpro3,$ls_isr,$ls_sueldointegral,$ls_programatica,$ls_estprog1;
		global $ls_estprog2,$ls_estprog3,$ls_operacion,$ls_desnom,$ls_desper,$ls_nomestpro4,$ls_nomestpro5,$io_fun_nomina;
		global $ls_codestpro4,$ls_codestpro5,$ls_denestpro4,$ls_denestpro5,$ls_titulo,$li_maxlen,$ls_modalidad;
		global $ls_cueprecon,$ls_denprecon,$ls_cueconcon,$ls_denconcon,$ls_cueprepatcon,$ls_cueconpatcon,$ls_dencueconpat,$ls_dencueprepat;
		global $ls_presupuesto,$ls_contable,$la_descon,$ls_coddescon,$ls_desdescon,$ls_spgcuenta,$li_contabilizado,$ls_activarislr;
		global $io_concepto, $ls_arc, $ls_sueldointegralvac, $ls_conprocon, $li_confconpronom;
		global $ls_estcla1,$ls_estcla2,$ls_estcla3,$ls_estcla4,$ls_estcla5;
		global $ls_intingcon, $ls_ingreso, $ls_cueingcon, $ls_dencueing, $li_poringcon, $ls_porcentajeingreso;
		global $ls_asifidper, $ls_asifidpat;
		
		require_once("sigesp_sno.php");
		$io_sno=new sigesp_sno();
		require_once("sigesp_sno_c_ajustarcontabilizacion.php");
		$io_ajustar=new sigesp_sno_c_ajustarcontabilizacion();
		$ls_codconc="";
		$ls_nomcon="";
		$ls_titcon="";
		$ls_cueprecon="";
		$ls_denprecon="";
		$ls_cueconcon="";
		$ls_denconcon="";
		$ls_cueprepatcon="";
		$ls_cueconpatcon="";
		$ls_dencueconpat="";
		$ls_dencueprepat="";
		$la_descon[0]="";
		$la_descon[1]="";
		$la_descon[2]="";
		$ls_coddescon="";
		$ls_desdescon="";
		$ls_nomestpro1=$_SESSION["la_empresa"]["nomestpro1"];
		$ls_nomestpro2=$_SESSION["la_empresa"]["nomestpro2"];
		$ls_nomestpro3=$_SESSION["la_empresa"]["nomestpro3"];
		$ls_nomestpro4=$_SESSION["la_empresa"]["nomestpro4"];		
		$ls_nomestpro5=$_SESSION["la_empresa"]["nomestpro5"];	
		$li_confconpronom=$_SESSION["la_nomina"]["conpronom"];	
		$ls_codestpro1="";
		$ls_denestpro1="";
		$ls_codestpro2="";
		$ls_denestpro2="";
		$ls_codestpro3="";
		$ls_denestpro3="";
		$ls_codestpro4="";
		$ls_denestpro4="";
		$ls_codestpro5="";
		$ls_denestpro5="";
		$ls_arc="";
		$ls_intingcon="";
		$ls_cueingcon="";
		$ls_dencueing="";
		$li_poringcon="0,00";
		$ls_porcentajeingreso="disabled";
		$ls_conprocon="";
		$ls_sueldointegralvac="";
		$ls_estcla1="";
		$ls_estcla2="";
		$ls_estcla3="";
		$ls_estcla4="";
		$ls_estcla5="";
		$ls_asifidper="";
		$ls_asifidpat="";
		$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
		switch($ls_modalidad)
		{
			case "1": // Modalidad por Proyecto
				$ls_titulo="Estructura Presupuestaria";
				$ls_codestpro4="00";
				$ls_codestpro5="00";
				$li_maxlen=25;
				break;
				
			case "2": // Modalidad por Presupuesto
				$ls_titulo="Estructura Programática";
				$li_maxlen=5;
				break;
		}
		$ls_isr="";
		$ls_sueldointegral="";
		$ls_programatica="";
		$ls_presupuesto="style='visibility:hidden'";
		$ls_ingreso="style='visibility:hidden'";
		$ls_contable="style='visibility:hidden'";
		$ls_estprog1="style='visibility:hidden'";
		$ls_estprog2="style='visibility:hidden'";		
		$ls_estprog3="style='visibility:hidden'";		
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		$ls_desnom=$_SESSION["la_nomina"]["desnom"];
		$ls_desper=$_SESSION["la_nomina"]["descripcionperiodo"];
		$ls_spgcuenta=$io_sno->uf_select_config("SNO","NOMINA","SPGCUENTA","401","C");
		$li_contabilizado=$io_ajustar->uf_contabilizado();
		unset($io_sno);
		unset($io_ajustar);
		$ls_activarislr="";
		if($io_concepto->uf_select_islr_historico())
		{
			$ls_activarislr=" disabled";
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
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 10/04/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codconc, $ls_nomcon, $ls_titcon, $ls_codestpro1, $ls_codestpro2, $ls_codestpro3, $ls_denestpro1, $ls_denestpro2;
		global $ls_codestpro3, $ls_aplisrcon, $ls_sueintcon, $ls_intprocon, $io_fun_nomina, $ls_codestpro4, $ls_codestpro5;
		global $ls_denestpro4, $ls_denestpro5,$ls_descon,$ls_coddescon,$ls_desdescon,$ls_cueprecon,$ls_denprecon;
		global $ls_cueconcon,$ls_denconcon,$ls_cueprepatcon,$ls_cueconpatcon,$ls_dencueconpat,$ls_dencueprepat,$ls_codprov;
		global $ls_aplarccon,$ls_sueintvaccon,$ls_codben,$ls_aplconprocon;
		global $ls_estcla,$ls_estcla1,$ls_estcla2,$ls_estcla3,$ls_estcla4,$ls_estcla5;
		global $ls_intingcon, $ls_cueingcon, $ls_dencueing, $li_poringcon;
		global $ls_asifidper, $ls_asifidpat;
		
		$ls_codconc=$_POST["txtcodconc"];
		$ls_nomcon=$_POST["txtnomcon"];
		$ls_titcon=$_POST["txttitcon"];
		$ls_codestpro1=$_POST["txtcodestpro1"];
		$ls_codestpro2=$_POST["txtcodestpro2"];
		$ls_codestpro3=$_POST["txtcodestpro3"];
		$ls_codestpro4=$_POST["txtcodestpro4"];
		$ls_codestpro5=$_POST["txtcodestpro5"];
		$ls_denestpro1=$_POST["txtdenestpro1"];
		$ls_denestpro2=$_POST["txtdenestpro2"];
		$ls_denestpro3=$_POST["txtdenestpro3"];
		$ls_denestpro4=$_POST["txtdenestpro4"];
		$ls_denestpro5=$_POST["txtdenestpro5"];
		$ls_cueprecon=$_POST["txtcuepre"];
		$ls_denprecon=$_POST["txtdencuepre"];
		$ls_cueconcon=$_POST["txtcuecon"];
		$ls_dencuecon=$_POST["txtdencuecon"];
		$ls_estcla1=$_POST["txtestcla1"];
		$ls_estcla2=$_POST["txtestcla2"];
		$ls_estcla3=$_POST["txtestcla3"];
		$ls_estcla4=$_POST["txtestcla4"];
		$ls_estcla5=$_POST["txtestcla5"];
		$ls_cueingcon=$_POST["txtcueingcon"];
		$ls_dencueing=$_POST["txtdencueing"];
		$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
		switch($ls_modalidad)
		{
			case "1": // Modalidad por Proyecto
				$ls_estcla=$ls_estcla3;
				break;
				
			case "2": // Modalidad por Presupuesto
				$ls_estcla=$ls_estcla5;
				break;
		}
		$ls_intingcon=$io_fun_nomina->uf_obtenervalor("chkintingcon","0");
		$li_poringcon=$io_fun_nomina->uf_obtenervalor("txtporingcon","0");
		$ls_aplisrcon=$io_fun_nomina->uf_obtenervalor("chkaplisrcon","0");
		$ls_sueintcon=$io_fun_nomina->uf_obtenervalor("chksueintcon","0");
		$ls_intprocon=$io_fun_nomina->uf_obtenervalor("chkintprocon","0");
		$ls_aplarccon=$io_fun_nomina->uf_obtenervalor("chkaplarccon","0");
		$ls_aplconprocon=$io_fun_nomina->uf_obtenervalor("chkconprocon","0");
		$ls_sueintvaccon=$io_fun_nomina->uf_obtenervalor("chksueintvaccon","0");
		$ls_cueprepatcon=$io_fun_nomina->uf_obtenervalor("txtcueprepat","");
		$ls_dencueprepat=$io_fun_nomina->uf_obtenervalor("txtdencueprepat","");
		$ls_cueconpatcon=$io_fun_nomina->uf_obtenervalor("txtcueconpat","");
		$ls_dencueconpat=$io_fun_nomina->uf_obtenervalor("txtdencueconpat","");
		$ls_descon=$io_fun_nomina->uf_obtenervalor("cmbdescon","");
		$ls_coddescon=$io_fun_nomina->uf_obtenervalor("txtcodproben","");
		$ls_desdescon=$io_fun_nomina->uf_obtenervalor("txtnombre","");
		$ls_asifidper=$io_fun_nomina->uf_obtenervalor("chkasifidper","0");
		$ls_asifidpat=$io_fun_nomina->uf_obtenervalor("chkasifidpat","0");
		$ls_codprov="----------";
		$ls_codben="----------";
		if($ls_descon=="P")
		{
			$ls_codprov=$io_fun_nomina->uf_obtenervalor("txtcodproben","");
			$ls_codben="----------";
			$ls_coddescon=$ls_codprov;
			$ls_desdescon=$io_fun_nomina->uf_obtenervalor("txtnombre","");
		}
		if($ls_descon=="B")
		{
			$ls_codprov="----------";
			$ls_codben=$io_fun_nomina->uf_obtenervalor("txtcodproben","");
			$ls_coddescon=$ls_codben;
			$ls_desdescon=$io_fun_nomina->uf_obtenervalor("txtnombre","");
		}
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
<title >Modificar Concepto Hist&oacute;rico</title>
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
</head>
<body>
<?php 
	require_once("sigesp_sno_c_concepto.php");
	$io_concepto=new sigesp_sno_c_concepto();
	uf_limpiarvariables();
	$ld_ano=substr($_SESSION["la_empresa"]["periodo"],0,4);
	$ld_fechasnom=substr($_SESSION["la_nomina"]["fechasper"],0,4);
	if($ld_fechasnom!=$ld_ano)
	{
		print("<script language=JavaScript>");
		print(" alert('Este proceso esta desactivo para Períodos de años Diferentes al Periodo de la Empresa.');");
		print(" location.href='sigespwindow_blank_hnomina.php'");
		print("</script>");
	}	
	switch ($ls_operacion) 
	{
		case "GUARDAR":
			uf_load_variables();
			if($ls_intprocon=="1")
			{
				$ls_codpro=str_pad($ls_codestpro1,25,"0",0).str_pad($ls_codestpro2,25,"0",0).str_pad($ls_codestpro3,25,"0",0);
				$ls_codpro=$ls_codpro.str_pad($ls_codestpro4,25,"0",0).str_pad($ls_codestpro5,25,"0",0);
 			}
			else
			{
				$ls_codpro="";
			}
			$lb_valido=$io_concepto->uf_update_hconcepto($ls_codconc,$ls_codpro,$ls_aplisrcon,$ls_sueintcon,$ls_intprocon,
														 $ls_cueprecon,$ls_cueconcon,$ls_cueprepatcon,$ls_cueconpatcon,
														 $ls_codprov,$ls_estcla,$ls_codben,$ls_aplarccon,$ls_sueintvaccon,
														 $ls_aplconprocon,$ls_intingcon,$ls_cueingcon,$li_poringcon,$ls_asifidper,
														 $ls_asifidpat,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
			}
			else
			{
				if($ls_aplisrcon=="1")
				{
					$ls_isr="checked";
				}
				if($ls_sueintcon=="1")
				{
					$ls_sueldointegral="checked";
				}
				if($ls_aplarccon=="1")
				{
					$ls_arc="checked";
				}
				if($ls_sueintvaccon=="1")
				{
					$ls_sueldointegralvac="checked";
				}
				if($ls_aplconprocon=="1")
				{
					$ls_conprocon="checked";
				}
				if($ls_intprocon=="1")
				{
					$ls_programatica="checked";
					$ls_estprog1="style='visibility:visible'";
					$ls_estprog2="style='visibility:visible'";
					$ls_estprog3="style='visibility:visible'";						
				}
			}
			break;
	}
	$io_concepto->uf_destructor();
	unset($io_concepto);
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
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_hnomina.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title='Guardar 'alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title='Salir' alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" title='Ayuda' alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
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
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank_hnomina.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="750" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="700" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-ventana">Modificar Concepto Hist&oacute;rico</td>
        </tr>
        <tr>
          <td width="164" height="22">&nbsp;</td>
          <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo</div></td>
          <td colspan="3"><div align="left">
            <input name="txtcodconc" type="text" id="txtcodconc" size="13" maxlength="10" value="<?php print $ls_codconc;?>" readonly>
            <a href="javascript: ue_buscarconcepto();"><img id="concepto" src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Concepto"></a></div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Nombre</div></td>
          <td colspan="3"><div align="left">
            <input name="txtnomcon" type="text" id="txtnomcon" value="<?php print $ls_nomcon;?>" size="33" maxlength="30" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">T&iacute;itulo</div></td>
          <td colspan="3"><div align="left">
            <input name="txttitcon" type="text" id="txttitcon" value="<?php print $ls_titcon;?>" size="90" maxlength="254" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Aplica Impuesto Sobre Renta </div></td>
          <td width="48"><div align="left">
            <input name="chkaplisrcon" type="checkbox" class="sin-borde" id="chkaplisrcon" value="1" <?php print $ls_isr;  if($ls_isr==""){print $ls_activarislr;} ?>>
          </div></td>
          <td width="276"><div align="right"><?php if ($ls_sueint==""){print "Pertenece al Sueldo Integral";}else{print "Pertenece a ". $ls_sueint;}?></div></td>
          <td width="202"><div align="left">
            <input name="chksueintcon" type="checkbox" class="sin-borde" id="chksueintcon" value="1" onClick="javascript: ue_activarfideicomiso('SUELDOI');" <?php print $ls_sueldointegral;?>>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Integrar Program&aacute;tica Concepto</div></td>
          <td><div align="left">
            <input name="chkintprocon" type="checkbox" class="sin-borde" id="chkintprocon" onClick="javascript: ue_activarprogramatica('<?php print $ls_modalidad;?>');" value="1" <?php print $ls_programatica;?>>
            </div></td>
          <td><div align="right"><?php if ($ls_sueint==""){print "Pertenece al Sueldo Integral de Vacaciones";}else{print "Pertenece a ". $ls_sueint." de Vacaciones";}?></div></td>
          <td><div align="left">
            <input name="chksueintvaccon" type="checkbox" class="sin-borde" id="chksueintvaccon" value="1" <?php print $ls_sueldointegralvac;?>>
          </div></td>
        </tr>
        <tr>
          <td height="20"><div align="right">Aplica ARC</div></td>
          <td><div align="left">
            <input name="chkaplarccon" type="checkbox" class="sin-borde" id="chkaplarccon" value="1"  <?php print $ls_arc;?> >
          </div></td>
          <td><div align="right">Contabilizaci&oacute;n por Proyecto </div></td>
          <td><div align="left">
            <input name="chkconprocon" type="checkbox" class="sin-borde" id="chkconprocon" value="1"  <?php print $ls_conprocon; if($li_confconpronom!="1"){ print "disabled";} ?>  >
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Integrar con Ingresos </div></td>
          <td><div align="left">
            <input name="chkintingcon" type="checkbox" class="sin-borde" id="chkintingcon" onClick="javascript: ue_activaringreso();" value="1" <?php print $ls_intingcon;?>>
          </div></td>
          <td><div align="right">Asignar a fideicomiso</div></td>
          <td><div align="left">
            <input name="chkasifidper" type="checkbox" class="sin-borde" id="chkasifidper" value="1" onClick="javascript: ue_activarfideicomiso('FIDEICOMISO');" <?php print $ls_asifidper;?>>
          </div></td>
        </tr>
        <tr>
          <td height="20"><div align="right"></div></td>
          <td colspan="3"><div align="left"><strong><?php print $ls_titulo; ?></strong></div></td>
        </tr>
              <tr>
                <td height="22"><div align="right">
                <?php print $ls_nomestpro1;?>				
                </div></td>
                <td  colspan="3">	
				  <div align="left">
                  <input name="txtcodestpro1" type="text" id="txtcodestpro1" value="<?php print $ls_codestpro1;?>" size="<?php print $ls_loncodestpro1+10; ?>" maxlength="<?php $ls_loncodestpro1+5?>" readonly>
                  <a href="javascript:ue_estructura1();"><img src="../shared/imagebank/tools/buscar.gif" alt="Buscar" name="estpro1" width="15" height="15" border="0" id="estpro1" <?php print $ls_estprog1; ?>></a>
                  <input name="txtdenestpro1" type="text" class="sin-borde" id="txtdenestpro1" value="<?php print $ls_denestpro1;?>" size="53" readonly>
				  <input name="txtestcla1" type="hidden" id="txtestcla1" size="2" value="<?php print $ls_estcla1;?>">
				  </div>              </td>
              </tr>
            <tr>
                <td height="22">
				<div align="right">
				<?php print $ls_nomestpro2;?>			  </div>			  </td>
                <td colspan="3">
				 <div align="left" >
                 <input name="txtcodestpro2" type="text" id="txtcodestpro2" value="<?php print $ls_codestpro2 ; ?>" size="<?php print $ls_loncodestpro2+10; ?>" maxlength="<?php print $ls_loncodestpro2+5; ?>" readonly>
                 <a href="javascript:ue_estructura2();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" name="estpro2" width="15" height="15" border="0" id="estpro2" <?php print $ls_estprog2; ?>></a>
                 <input name="txtdenestpro2" type="text" class="sin-borde" id="txtdenestpro2" value="<?php print $ls_denestpro2 ; ?>" size="53" readonly>
                 <input name="txtestcla2" type="hidden" id="txtestcla2" size="2" value="<?php print $ls_estcla2;?>">
                 </div>				</td>
            </tr>
            <tr>
              <td height="22">
                <div align="right">
				<?php print $ls_nomestpro3; ?>			  </div>			  </td>
              <td colspan="3">
			    <div align="left">
                <input name="txtcodestpro3" type="text" id="txtcodestpro3" value="<?php print $ls_codestpro3;?>" size="<?php print $ls_loncodestpro3+10; ?>" maxlength="<?php print $ls_loncodestpro3+5; ?>" readonly>
                <a href="javascript:ue_estructura3();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" name="estpro3" width="15" height="15" border="0" id="estpro3" <?php print $ls_estprog3; ?>></a>
                <input name="txtdenestpro3" type="text" class="sin-borde" id="txtdenestpro3" value="<?php print $ls_denestpro3;?>" size="53" readonly>
                <input name="txtestcla3" type="hidden" id="txtestcla3" size="2" value="<?php print $ls_estcla3;?>">
                </div></td>
            </tr>
<?php if($ls_modalidad=="1") // Por Proyecto
	  {?>
 				<input name="txtcodestpro4" type="hidden" id="txtcodestpro4" value="<?php print $ls_codestpro4;?>">
 				<input name="txtdenestpro4" type="hidden" id="txtdenestpro4" value="<?php print $ls_denestpro4;?>">
 				<input name="txtcodestpro5" type="hidden" id="txtcodestpro5" value="<?php print $ls_codestpro5;?>">
 				<input name="txtdenestpro5" type="hidden" id="txtdenestpro5" value="<?php print $ls_denestpro5;?>">
                <input name="txtestcla4" type="hidden" id="txtestcla4" value="<?php print $ls_estcla4;?>">
                <input name="txtestcla5" type="hidden" id="txtestcla5" value="<?php print $ls_estcla5;?>">
                
<?php }
	  else
	  {?>
            <tr>
              <td height="22">
                <div align="right">
				<?php print $ls_nomestpro4; ?>			  </div>			  </td>
              <td colspan="3">
			    <div align="left">
                <input name="txtcodestpro4" type="text" id="txtcodestpro4" value="<?php print $ls_codestpro4;?>" size="<?php print $ls_loncodestpro4+10; ?>" maxlength="<?php print $ls_loncodestpro4+10; ?>" readonly>
                <a href="javascript:ue_estructura4();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" name="estpro4" width="15" height="15" border="0" id="estpro4" <?php print $ls_estprog4; ?>></a>
                <input name="txtdenestpro4" type="text" class="sin-borde" id="txtdenestpro4" value="<?php print $ls_denestpro4;?>" size="53" readonly>
                <input name="txtestcla4" type="hidden" id="txtestcla4" size="2" value="<?php print $ls_estcla4;?>">
                </div></td>
            </tr>
            <tr colspan="3">
              <td height="22">
                <div align="right">
				<?php print $ls_nomestpro5; ?>			  </div>			  </td>
              <td colspan="3">
			    <div align="left">
                <input name="txtcodestpro5" type="text" id="txtcodestpro5" value="<?php print $ls_codestpro5;?>" size="<?php print $ls_loncodestpro5+10; ?>" maxlength="<?php print $ls_loncodestpro5+1; ?>" readonly>
                <a href="javascript:ue_estructura5();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" name="estpro5" width="15" height="15" border="0" id="estpro5" <?php print $ls_estprog5; ?>></a>
                <input name="txtdenestpro5" type="text" class="sin-borde" id="txtdenestpro5" value="<?php print $ls_denestpro5;?>" size="53" readonly>
                <input name="txtestcla5" type="hidden" id="txtestcla5" size="2" value="<?php print $ls_estcla5;?>">
                </div></td>
            </tr>
<?php } ?>
        <tr>
          <td height="22"><div align="right">Cuenta de Presupuesto</div></td>
          <td colspan="3"><input name="txtcuepre" type="text" id="txtcuepre" value="<?php print $ls_cueprecon;?>" size="28" maxlength="25" readonly>
            <a href="javascript: ue_buscarcuentapresupuesto();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="cuentapresupuesto" width="15" height="15" border="0" id="cuentapresupuesto" <?php print $ls_presupuesto ?>></a>
            <input name="txtdencuepre" type="text" class="sin-borde" id="txtdencuepre" value="<?php print $ls_denprecon;?>" size="50" maxlength="100" readonly></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Cuenta Contable </div></td>
          <td colspan="3"><input name="txtcuecon" type="text" id="txtcuecon" value="<?php print $ls_cueconcon;?>" size="28" maxlength="25" readonly>
            <a href="javascript: ue_buscarcuentacontable();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="cuentaabono" width="15" height="15" border="0" id="cuentaabono" <?php print $ls_contable?>></a>
            <input name="txtdencuecon" type="text" class="sin-borde" id="txtdencuecon" value="<?php print $ls_denconcon;?>" size="50" maxlength="100" readonly></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Cuenta de Ingreso </div></td>
          <td colspan="3"><div align="left">
            <input name="txtcueingcon" type="text" id="txtcueingcon" value="<?php print $ls_cueingcon;?>" size="28" maxlength="25" readonly>
            <a href="javascript: ue_buscarcuentaingreso();"><img id="cuentaingreso" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0" <?php print $ls_ingreso?>></a>
            <input name="txtdencueing" type="text" class="sin-borde" id="txtdencueing" value="<?php print $ls_dencueing;?>" size="50" maxlength="100" readonly>
		  </div>		  </td>
        </tr>
        <tr>
          <td height="22"><div align="right">Porcentaje </div></td>
          <td colspan="3">
            <input name="txtporingcon" type="text" id="txtporingcon" value="<?php print $li_poringcon;?>" size="5" maxlength="6" onKeyPress="return(ue_formatonumero(this,'.',',',event))" style="text-align:right" <?php print $ls_porcentajeingreso; ?>>          </td>
        </tr>
        <tr>
          <td height="22" colspan="4" class="titulo-celdanew"><div align="center">Aporte Patronal </div>
            <div align="left"></div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">Asignar al fideicomiso</div></td>
          <td colspan="3"><div align="left">
            <input name="chkasifidpat" type="checkbox" class="sin-borde" id="chkasifidpat" value="1" <?php print $ls_asifidpat;?>>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Estad&iacute;stico</div></td>
          <td colspan="3"><div align="left">
            <input name="txtcueprepat" type="text" id="txtcueprepat" value="<?php print $ls_cueprepatcon;?>" size="28" maxlength="25" readonly>
            <a href="javascript: ue_buscarcuentapresupuesto_patron();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="cuentapresupuestopatron" width="15" height="15" border="0" id="cuentapresupuestopatron" <?php print $ls_presupuesto; ?>></a>
            <input name="txtdencueprepat" type="text" class="sin-borde" id="txtdencueprepat" value="<?php print $ls_dencueprepat;?>" size="50" maxlength="100" readonly>
</div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Cuenta Contable</div></td>
          <td colspan="3"><div align="left">
            <input name="txtcueconpat" type="text" id="txtcueconpat" value="<?php print $ls_cueconpatcon;?>" size="28" maxlength="25" readonly>
            <a href="javascript: ue_buscarcuentacontable_patron();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="cuentaabonopatron" width="15" height="15" border="0" id="cuentaabonopatron" <?php print $ls_contable; ?>></a>
            <input name="txtdencueconpat" type="text" class="sin-borde" id="txtdencueconpat" value="<?php print $ls_dencueconpat;?>" size="50" maxlength="100" readonly>
</div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Destino de Contabilizaci&oacute;n</div></td>
          <td colspan="3"><div align="left">
            <select name="cmbdescon" id="cmbdescon" onChange="javascript: ue_limpiar();" disabled>
              <option value=" " <?php print $la_descon[0]; ?>> </option>
              <option value="P" <?php print $la_descon[1]; ?>>PROVEEDOR</option>
              <option value="B" <?php print $la_descon[2]; ?>>BENEFICIARIO</option>
            </select>
            <input name="txtcodproben" type="text" id="txtcodproben" value="<?php print $ls_coddescon;?>" size="15" maxlength="10" readonly>
            <a href="javascript: ue_buscardestino();"><img  src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtnombre" type="text" class="sin-borde" id="txtnombre" value="<?php print $ls_desdescon;?>" size="50" maxlength="30" readonly>
</div></td>
        </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td colspan="3"><input name="operacion" type="hidden" id="operacion"> <input name="txtsigcon" type="hidden" id="txtsigcon">
            <input name="contabilizado" type="hidden" id="contabilizado" value="<?php print $li_contabilizado;?>"></td>
        </tr>
      </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>      
<p>&nbsp;</p>
</body>
<script language="javascript">

function ue_guardar()
{
	f=document.form1;
	li_cambiar=f.cambiar.value;
	if(li_cambiar==1)
	{
		codconc = ue_validarvacio(f.txtcodconc.value);
		if (codconc!="")
		{
			f.operacion.value="GUARDAR";
			f.action="sigesp_sno_p_hmodificarconcepto.php";
			f.submit();
		}
		else
		{
			alert("Debe seleccionar un concepto.");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_cerrar()
{
	location.href = "sigespwindow_blank_hnomina.php";
}

function ue_buscarconcepto()
{
   window.open("sigesp_sno_cat_hconcepto.php?tipo=MODIFICAR","_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
}


function ue_buscarcuentapresupuesto()
{
	f=document.form1;
	if(f.contabilizado.value=="0")
	{
		sigcon = ue_validarvacio(f.txtsigcon.value);
		if((sigcon=="A")||(sigcon=="E"))
		{
			window.open("sigesp_sno_cat_cuentapresupuesto.php?spg_cuenta=<?php print $ls_spgcuenta;?>","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
		}
	}
	else
	{
		alert("El período está contabilizado. no se puede hacer ningún ajuste de cuentas.");
	}
}

function ue_buscarcuentapresupuesto_patron()
{
	f=document.form1;
	if(f.contabilizado.value=="0")
	{
		sigcon = ue_validarvacio(f.txtsigcon.value);
		if(sigcon=="P")
		{
			window.open("sigesp_sno_cat_cuentapresupuesto.php?spg_cuenta=<?php print $ls_spgcuenta;?>&tipo=PATRONAL","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
		}
	}
	else
	{
		alert("El período está contabilizado. no se puede hacer ningún ajuste de cuentas.");
	}
}

function ue_buscarcuentacontable()
{
	f=document.form1;
	if(f.contabilizado.value=="0")
	{
		sigcon = ue_validarvacio(f.txtsigcon.value);
		if((sigcon=="D")||(sigcon=="P")||(sigcon=="B"))
		{
			window.open("sigesp_sno_cat_cuentacontable.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
		}
	}
	else
	{
		alert("El período está contabilizado. no se puede hacer ningún ajuste de cuentas.");
	}
}

function ue_buscarcuentacontable_patron()
{
	f=document.form1;
	if(f.contabilizado.value=="0")
	{
		sigcon = ue_validarvacio(f.txtsigcon.value);
		if(sigcon=="P")
		{
			window.open("sigesp_sno_cat_cuentacontable.php?tipo=PATRONAL","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
		}
	}
	else
	{
		alert("El período está contabilizado. no se puede hacer ningún ajuste de cuentas.");
	}
}

function ue_activarfideicomiso(tipo)
{
	f=document.form1;
	switch(tipo)
	{
		case 'SUELDOI':
			f.chkasifidper.checked=false;		
			break;
		
		case 'FIDEICOMISO':
			if(f.chksueintcon.checked==true)
			{
				alert('Ya el concepto esta como <?php if($ls_sueint==""){print "sueldo integral";}else{print $ls_sueint;}?>. No debe marcarlo para fideicomiso.');
				f.chkasifidper.checked=false;		
			}		
			break;
	}
}

function ue_estructura1()
{
	if(f.contabilizado.value=="0")
	{
		window.open("sigesp_snorh_cat_estpre1.php?tipo=asignacioncargo","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("El período está contabilizado. no se puede hacer ningún ajuste de Estructura.");
	}
}

function ue_estructura1()
{
	   window.open("sigesp_snorh_cat_estpre1.php?tipo=asignacioncargo","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
}

function ue_estructura2()
{
	f=document.form1;
	if(f.contabilizado.value=="0")
	{
		codestpro1=f.txtcodestpro1.value;
		denestpro1=f.txtdenestpro1.value;
		estcla1=f.txtestcla1.value;
		if((codestpro1!="")&&(denestpro1!=""))
		{
			pagina="sigesp_snorh_cat_estpre2.php?tipo=asignacioncargo&codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&estcla1="+estcla1;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione la Estructura nivel 1");
		}
	}
	else
	{
		alert("El período está contabilizado. no se puede hacer ningún ajuste de Estructura.");
	}
}

function ue_estructura3()
{
	f=document.form1;
	if(f.contabilizado.value=="0")
	{
		codestpro1=f.txtcodestpro1.value;
		denestpro1=f.txtdenestpro1.value;
		codestpro2=f.txtcodestpro2.value;
		denestpro2=f.txtdenestpro2.value;
		estcla2=f.txtestcla2.value;
		if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!=""))
		{
			pagina="sigesp_snorh_cat_estpre3.php?tipo=asignacioncargo&codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2+"&estcla2="+estcla2;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione la Estructura de nivel Anterior");
		}
	}
	else
	{
		alert("El período está contabilizado. no se puede hacer ningún ajuste de Estructura.");
	}
}

function ue_estructura4()
{
	f=document.form1;
	if(f.contabilizado.value=="0")
	{
		codestpro1=f.txtcodestpro1.value;
		denestpro1=f.txtdenestpro1.value;
		codestpro2=f.txtcodestpro2.value;
		denestpro2=f.txtdenestpro2.value;
		codestpro3=f.txtcodestpro3.value;
		denestpro3=f.txtdenestpro3.value;
		estcla3=f.txtestcla3.value;
		if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!="")&&(codestpro3!="")&&(denestpro3!=""))
		{
			pagina="sigesp_snorh_cat_estpre4.php?tipo=asignacioncargo&codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2+"&codestpro3="+codestpro3+"&denestpro3="+denestpro3+"&estcla3="+estcla3;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione la Estructura de nivel Anterior");
		}
	}
	else
	{
		alert("El período está contabilizado. no se puede hacer ningún ajuste de Estructura.");
	}
}

function ue_estructura5()
{
	f=document.form1;
	if(f.contabilizado.value=="0")
	{
		codestpro1=f.txtcodestpro1.value;
		denestpro1=f.txtdenestpro1.value;
		codestpro2=f.txtcodestpro2.value;
		denestpro2=f.txtdenestpro2.value;
		codestpro3=f.txtcodestpro3.value;
		denestpro3=f.txtdenestpro3.value;
		codestpro4=f.txtcodestpro4.value;
		denestpro4=f.txtdenestpro4.value;
		estcla4=f.txtestcla4.value;
		if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!="")&&(codestpro3!="")&&(denestpro3!="")&&(codestpro4!="")&&(denestpro4!=""))
		{
			pagina="sigesp_snorh_cat_estpre5.php?tipo=asignacioncargo&codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2+"&codestpro3="+codestpro3+"&denestpro3="+denestpro3+"&codestpro4="+codestpro4+"&denestpro4="+denestpro4+"&estcla4="+estcla4;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione la Estructura de nivel Anterior");
		}
	}
	else
	{
		alert("El período está contabilizado. no se puede hacer ningún ajuste de Estructura.");
	}
}

//--------------------------------------------------------
//	Función que habilita los campos de la programática
//--------------------------------------------------------
function ue_activarprogramatica(modalidad)
{
	f=document.form1;
	if(f.contabilizado.value=="0")
	{
		if(f.chkintprocon.checked)
		{
			document.images["estpro1"].style.visibility="visible";
			document.images["estpro2"].style.visibility="visible";
			document.images["estpro3"].style.visibility="visible";
			if(modalidad=="2")
			{
				document.images["estpro4"].style.visibility="visible";
				document.images["estpro5"].style.visibility="visible";
			}
		}
		else
		{
			document.images["estpro1"].style.visibility="hidden";
			document.images["estpro2"].style.visibility="hidden";
			document.images["estpro3"].style.visibility="hidden";
			if(modalidad=="2")
			{
				document.images["estpro4"].style.visibility="hidden";
				document.images["estpro5"].style.visibility="hidden";
			}
			f.txtcodestpro1.value="";
			f.txtcodestpro2.value="";
			f.txtcodestpro3.value="";
			f.txtcodestpro4.value="";
			f.txtcodestpro5.value="";
			f.txtdenestpro1.value="";
			f.txtdenestpro2.value="";
			f.txtdenestpro3.value="";
			f.txtdenestpro4.value="";
			f.txtdenestpro5.value="";
		}
	}
	else
	{
		alert("El período está contabilizado. no se puede hacer ningún ajuste de Estructura.");
		f.chkintprocon.checked=false;
	}
}

function ue_limpiar()
{
	f=document.form1;
	f.txtcodproben.value="";
	f.txtnombre.value="";
}

//--------------------------------------------------------
//	Función que habilita los campos del las cuentas de ingreso
//--------------------------------------------------------
function ue_activaringreso()
{
	f=document.form1;
	if(f.txtsigcon.value=="D")
	{
		if(f.chkintingcon.checked==false)
		{
			f.txtporingcon.disabled=true;
			f.txtporingcon.value="0,00";
			document.images["cuentaingreso"].style.visibility="hidden";
			f.txtcueingcon.value="";
			f.txtdencueing.value="";
		}
		else
		{
			document.images["cuentaingreso"].style.visibility="visible";
			f.txtporingcon.disabled=false;
		}
	}
	else
	{
		alert("Esta opción esta disponible solo para Conceptos de tipo Deducción.");
		f.chkintingcon.checked=false;		
	}
}

function ue_buscarcuentaingreso()
{
	f=document.form1;
	sigcon = ue_validarvacio(f.txtsigcon.value);
	if((sigcon=="D")&&(f.chkintingcon.checked))
	{
		window.open("sigesp_sno_cat_cuentaingreso.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
}

function ue_buscardestino()
{
	f=document.form1;
	if(f.contabilizado.value=="0")
	{
		sigcon = ue_validarvacio(f.txtsigcon.value);
		if(sigcon=="P")
		{	
			descon=ue_validarvacio(f.cmbdescon.value);
			if(descon!="")
			{
				if(descon=="P")
				{
					window.open("sigesp_catdinamic_prove.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
				}
				else
				{
					window.open("sigesp_catdinamic_bene.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
				}	
			}
			else
			{
				alert("Debe seleccionar un destino de Contabilización.");
			}
		}
	}
	else
	{
		alert("El período está contabilizado. no se puede hacer ningún ajuste de Proveedor ó Beneficiario.");
	}
}
</script> 
</html>