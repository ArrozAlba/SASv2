<?php
 
    session_start();
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
	ini_set('max_execution_time ','0');

	$ls_logusr=$_SESSION["la_logusr"];
	$ls_codnom=$_SESSION["la_nomina"]["codnom"];
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad_nomina("SNO","sigesp_sno_d_concepto.php",$ls_codnom,$ls_permisos,$la_seguridad,$la_permisos);
	$ls_loncodestpro1=$_SESSION["la_empresa"]["loncodestpro1"];
	$ls_loncodestpro2=$_SESSION["la_empresa"]["loncodestpro2"];
	$ls_loncodestpro3=$_SESSION["la_empresa"]["loncodestpro3"];
	$ls_loncodestpro4=$_SESSION["la_empresa"]["loncodestpro4"];
	$ls_loncodestpro5=$_SESSION["la_empresa"]["loncodestpro5"];
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
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codconc,$ls_nomcon,$ls_titcon,$ls_forcon,$li_acumaxcon,$li_valmincon,$li_valmaxcon,$ls_concon,$ls_cueprecon;
		global $ls_cueconcon,$ls_forpatcon,$ls_cueprepatcon,$la_sigcon,$ls_glocon,$ls_aplisrcon,$ls_desdescon,$ls_arc,$ls_aplarccon;
		global $ls_cueconpatcon,$ls_titretempcon,$ls_titretpatcon,$li_valminpatcon,$li_valmaxpatcon,$ls_sigcon,$ls_sueintcon;
		global $ls_intprocon,$ls_global,$ls_isr,$ls_sueldointegral,$ls_programatica,$ls_denconcon,$ls_dencueconpat;
		global $ls_denprecon,$ls_presupuesto,$ls_contable,$ls_activarbloqueo,$ls_existe,$la_descon,$ls_coddescon,$ls_desper;
		global $ls_dencueprepat,$ls_concepto,$ls_signo,$ls_personal,$ls_prenomina,$ls_sueldointegralvac,$ls_sueintvaccon,$io_fun_nomina;
		global $ls_operacion,$ls_desnom,$ls_spgcuenta,$ls_contabilizacion,$li_activarbloqueo,$ls_modalidad,$ls_nomestpro1,$ls_nomestpro2,$ls_nomestpro3;
		global $ls_nomestpro4,$ls_nomestpro5,$ls_titulo,$ls_codestpro1,$ls_denestpro1,$ls_codestpro2,$ls_denestpro2,$ls_codestpro3;
		global $ls_denestpro3,$ls_codestpro4,$ls_denestpro4,$ls_codestpro5,$ls_denestpro5,$li_maxlen,$ls_estprog1, $ls_estprog2, $ls_estprog3, $ls_estprog4,$ls_estprog5;
		global $ls_activarislr,$io_concepto,$li_calculada,$ls_conprocon,$ls_aplconprocon,$li_confconprocon;
		global $ls_estcla1,$ls_estcla2,$ls_estcla3,$ls_estcla4,$ls_estcla5;
		global $ls_intingcon, $ls_ingreso, $ls_cueingcon, $ls_dencueing, $li_poringcon, $ls_porcentajeingreso;
		global $ls_repacucon,$ls_repconsunicon, $ls_consunicon, $la_quirepcon, $ls_activoreporte;
		global $ls_frevarcon, $ls_asifidper, $ls_asifidpat,$ls_persalnor,$ls_perenc, $ls_aplresenc, $ls_activarresenc,$ls_codente,$ls_txtente;
		
		require_once("sigesp_sno.php");
		$io_sno=new sigesp_sno();
		$ls_activarislr="";
		$ls_activarresenc="";
		$ls_codconc="";
		$ls_nomcon="";
		$ls_titcon="";
		$ls_forcon="";
		$li_acumaxcon="0";
		$li_valmincon="0";
		$li_valmaxcon="0";
		$ls_concon="";
		$ls_cueprecon="";
		$ls_denprecon="";
		$ls_cueconcon="";
		$ls_denconcon="";
		$ls_coddescon="";
		$ls_desdescon="";
		$ls_forpatcon="";
		$ls_cueprepatcon="";
		$ls_cueconpatcon="";
		$ls_titretempcon="";
		$ls_titretpatcon="";
		$li_valminpatcon="";
		$li_valmaxpatcon="";
		$ls_sigcon="";		
		$la_sigcon[0]="";
		$la_sigcon[1]="";
		$la_sigcon[2]="";
		$la_sigcon[3]="";
		$la_sigcon[4]="";
		$la_sigcon[5]="";
		$la_descon[0]="";
		$la_descon[1]="";
		$la_descon[2]="";
		$la_quirepcon[0]="";
		$la_quirepcon[1]="";
		$la_quirepcon[2]="";
		$ls_glocon="";
		$ls_aplisrcon="";
		$ls_aplarccon="";
		$ls_aplconprocon="";
		$ls_sueintcon="";
		$ls_sueintvaccon="";
		$ls_intprocon="";
		$li_conprenom="";
		$ls_global="";
		$ls_isr="";
		$ls_arc="";
		$ls_repacucon="";
		$ls_repconsunicon="";
		$ls_consunicon="";
		$ls_conprocon="";
		$ls_intingcon="";
		$ls_cueingcon="";
		$ls_dencueing="";
		$li_poringcon="0,00";
		$ls_porcentajeingreso="disabled";
		$ls_prenomina=" checked";
		$ls_sueldointegral="";
		$ls_sueldointegralvac="";
		$ls_programatica="";
		$ls_dencueconpat="";
		$ls_dencueprepat="";
		$ls_presupuesto="style='visibility:hidden'";
		$ls_contable="style='visibility:hidden'";
		$ls_estprog1="style='visibility:hidden'";
		$ls_estprog2="style='visibility:hidden'";
		$ls_estprog3="style='visibility:hidden'";		
		$ls_estprog4="style='visibility:hidden'";		
		$ls_estprog5="style='visibility:hidden'";		
		$ls_ingreso="style='visibility:hidden'";
		$ls_activarbloqueo="";
		$ls_concepto="";
		$ls_signo="";
		$ls_personal=" disabled";
		$ls_frevarcon="";
		$ls_asifidper="";
		$ls_asifidpat="";		
		$ls_existe=$io_fun_nomina->uf_obtenerexiste();			
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		$li_confconprocon=$_SESSION["la_nomina"]["conpronom"];
		$ls_desnom=$_SESSION["la_nomina"]["desnom"];
		$ls_desper=$_SESSION["la_nomina"]["descripcionperiodo"];
		$ls_spgcuenta=$io_sno->uf_select_config("SNO","NOMINA","SPGCUENTA","401","C");
		$ls_contabilizacion=$io_sno->uf_select_config("SNO","SPG","CONTABILIZACION","UBICACION ADMINISTRATIVA","C");
		$li_activarbloqueo=$io_sno->uf_select_config("SNO","CONFIG","ACTIVAR_BLOQUEO","0","I");
		if($io_concepto->uf_select_islr())
		{
			$ls_activarislr=" disabled";
		}
		$ls_persalnor="";
		$ls_perenc="";
		 $ls_aplresenc="";
		$ls_nomestpro1=$_SESSION["la_empresa"]["nomestpro1"];		
		$ls_nomestpro2=$_SESSION["la_empresa"]["nomestpro2"];		
		$ls_nomestpro3=$_SESSION["la_empresa"]["nomestpro3"];		
		$ls_nomestpro4=$_SESSION["la_empresa"]["nomestpro4"];		
		$ls_nomestpro5=$_SESSION["la_empresa"]["nomestpro5"];		
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
		$ls_estcla1="";
		$ls_estcla2="";
		$ls_estcla3="";
		$ls_estcla4="";
		$ls_estcla5="";
		$ls_codente="";
		$ls_txtente="";
		$ls_activoreporte="disabled";
		if($io_concepto->uf_select_resumen_encargaduria())
		{
			$ls_activarresenc=" disabled";
		}
		if(($_SESSION["la_nomina"]["tippernom"]=='2')&&($_SESSION["la_nomina"]["divcon"]=='1'))// Nóminas Mensuales y con división de conceptos
		{
			$ls_activoreporte="";
		}
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
		unset($io_sno);
		require_once("sigesp_sno_c_calcularnomina.php");
		$io_calcularnomina=new sigesp_sno_c_calcularnomina();
		$li_calculada=str_pad($io_calcularnomina->uf_existesalida(),1,"0");
		unset($io_calcularnomina);
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
		// Fecha Creación: 23/03/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codconc, $ls_nomcon, $ls_titcon, $ls_forcon, $li_acumaxcon, $li_valmincon, $li_valmaxcon, $ls_concon;
		global $ls_cueprecon, $ls_denprecon, $ls_cueconcon, $ls_dencuecon, $ls_codestpro1, $ls_codestpro2, $ls_codestpro3;
		global $ls_denestpro1, $ls_denestpro2, $ls_codestpro3, $ls_sigcon, $ls_glocon, $ls_aplisrcon, $ls_sueintcon;
		global $ls_sueintvaccon, $ls_intprocon, $li_conprenom, $ls_forpatcon, $ls_cueprepatcon, $ls_dencueprepat, $ls_cueconpatcon;
		global $ls_dencueconpat, $ls_titretempcon, $ls_titretpatcon, $li_valminpatcon, $li_valmaxpatcon, $io_fun_nomina;
		global $ls_coddescon, $ls_desdescon, $ls_codprov, $ls_codben, $ls_descon, $ls_aplarccon, $ls_codestpro4, $ls_codestpro5;
		global $ls_denestpro4, $ls_denestpro5, $ls_aplconprocon,$ls_modalidad;
		global $ls_estcla,$ls_estcla1,$ls_estcla2,$ls_estcla3,$ls_estcla4,$ls_estcla5;
		global $ls_intingcon, $ls_cueingcon, $ls_dencueing, $li_poringcon;
		global $ls_repacucon,$ls_repconsunicon,$ls_consunicon, $ls_quirepcon;
		global $ls_frevarcon, $ls_asifidper, $ls_asifidpat,$ls_persalnor,$ls_perenc, $ls_aplresenc,$io_concepto;
		global $ls_codente,$ls_txtente;
		
		$ls_codconc=$_POST["txtcodconc"];
		$ls_nomcon=$_POST["txtnomcon"];
		$ls_titcon=$_POST["txttitcon"];
		$ls_forcon=$_POST["txtforcon"];
		$li_acumaxcon=$_POST["txtacumaxcon"];
		$li_valmincon=$_POST["txtvalmincon"];
		$li_valmaxcon=$_POST["txtvalmaxcon"];
		$ls_concon=$_POST["txtconcon"];
		$ls_cueprecon=$_POST["txtcuepre"];
		$ls_denprecon=$_POST["txtdencuepre"];
		$ls_cueconcon=$_POST["txtcuecon"];
		$ls_dencuecon=$_POST["txtdencuecon"];
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
		$ls_estcla1=$_POST["txtestcla1"];
		$ls_estcla2=$_POST["txtestcla2"];
		$ls_estcla3=$_POST["txtestcla3"];
		$ls_estcla4=$_POST["txtestcla4"];
		$ls_estcla5=$_POST["txtestcla5"];
		$ls_cueingcon=$_POST["txtcueingcon"];
		$ls_dencueing=$_POST["txtdencueing"];
		$ls_codente=$_POST["txt_codente"];
		$ls_txtente=$_POST["txt_ente"];
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
		$ls_sigcon=$io_fun_nomina->uf_obtenervalor("cmbsigcon","");
		if($ls_sigcon=="")
		{
			$ls_sigcon=$io_fun_nomina->uf_obtenervalor("txtsigcon","");
		}
		$ls_intingcon=$io_fun_nomina->uf_obtenervalor("chkintingcon","0");
		$li_poringcon=$io_fun_nomina->uf_obtenervalor("txtporingcon","0");
		$ls_glocon=$io_fun_nomina->uf_obtenervalor("chkglocon","0");
		$ls_aplisrcon=$io_fun_nomina->uf_obtenervalor("chkaplisrcon","0");
		$ls_aplarccon=$io_fun_nomina->uf_obtenervalor("chkaplarccon","0");
		$ls_aplconprocon=$io_fun_nomina->uf_obtenervalor("chkaplconprocon","0");
		$ls_sueintcon=$io_fun_nomina->uf_obtenervalor("chksueintcon","0");
		$ls_sueintvaccon=$io_fun_nomina->uf_obtenervalor("chksueintvaccon","0");
		$ls_intprocon=$io_fun_nomina->uf_obtenervalor("chkintprocon","0");
		$li_conprenom=$io_fun_nomina->uf_obtenervalor("chkconprenom","0");
		$ls_forpatcon=$io_fun_nomina->uf_obtenervalor("txtforpatcon","");
		$ls_cueprepatcon=$io_fun_nomina->uf_obtenervalor("txtcueprepat","");
		$ls_dencueprepat=$io_fun_nomina->uf_obtenervalor("txtdencueprepat","");
		$ls_cueconpatcon=$io_fun_nomina->uf_obtenervalor("txtcueconpat","");
		$ls_dencueconpat=$io_fun_nomina->uf_obtenervalor("txtdencueconpat","");
		$ls_titretempcon=$io_fun_nomina->uf_obtenervalor("txttitretempcon","");
		$ls_titretpatcon=$io_fun_nomina->uf_obtenervalor("txttitretpatcon","");
		$li_valminpatcon=$io_fun_nomina->uf_obtenervalor("txtvalminpatcon","0");
		$li_valmaxpatcon=$io_fun_nomina->uf_obtenervalor("txtvalmaxpatcon","0");
		$ls_repacucon=$io_fun_nomina->uf_obtenervalor("chkrepacucon","0");
		$ls_repconsunicon=$io_fun_nomina->uf_obtenervalor("chkrepconsunicon","0");
		$ls_consunicon=$io_fun_nomina->uf_obtenervalor("txtconsunicon","");		
		$ls_descon=$io_fun_nomina->uf_obtenervalor("cmbdescon","");
		$ls_quirepcon=$io_fun_nomina->uf_obtenervalor("cmbquirepcon","-");
		$ls_frevarcon=$io_fun_nomina->uf_obtenervalor("chkfrevarcon","0");
		$ls_asifidper=$io_fun_nomina->uf_obtenervalor("chkasifidper","0");		
		$ls_persalnor=$io_fun_nomina->uf_obtenervalor("chkpersalnor","0");
		$ls_asifidpat=$io_fun_nomina->uf_obtenervalor("chkasifidpat","0");
		$ls_perenc=$io_fun_nomina->uf_obtenervalor("chkperenc","0");	
		$ls_aplresenc=$io_fun_nomina->uf_obtenervalor("chkaplresenc","0");	
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
		if($io_concepto->uf_select_resumen_encargaduria())
		{
			$ls_activarresenc=" disabled";
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
<title >Definici&oacute;n de Concepto</title>
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
	if($ls_existe=="TRUE")
	{
		$ls_presupuesto="style='visibility:visible'";
		$ls_contable="style='visibility:hidden'";	
		if($li_activarbloqueo=="0")
		{
			$ls_activarbloqueo="";
		}
		else
		{
			$ls_activarbloqueo="readonly";
		}
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
			$lb_valido=$io_concepto->uf_guardar($ls_existe,$ls_codconc,$ls_nomcon,$ls_titcon,$ls_forcon,$li_acumaxcon,$li_valmincon,
												$li_valmaxcon,$ls_concon,$ls_cueprecon,$ls_cueconcon,$ls_codpro,$ls_estcla,$ls_sigcon,$ls_glocon,
												$ls_aplisrcon,$ls_sueintcon,$ls_intprocon,$ls_forpatcon,$ls_cueprepatcon,$ls_cueconpatcon,
												$ls_titretempcon,$ls_titretpatcon,$li_valminpatcon,$li_valmaxpatcon,$li_conprenom,$ls_sueintvaccon,
												$ls_codprov,$ls_codben,$ls_aplarccon,$ls_aplconprocon,$ls_intingcon,$ls_cueingcon,$li_poringcon,
												$ls_repacucon,$ls_repconsunicon,$ls_consunicon,$ls_quirepcon,$ls_frevarcon,$ls_asifidper,$ls_asifidpat,$ls_persalnor,$ls_perenc,$ls_aplresenc,
												$ls_codente,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
			}
			else
			{
				$io_fun_nomina->uf_seleccionarcombo("A-D-P-R-B-E",$ls_sigcon,$la_sigcon,6);
				$io_fun_nomina->uf_seleccionarcombo("1-2-3",$ls_quirepcon,$la_quirepcon,3);
				$io_fun_nomina->uf_seleccionarcombo(" -P-B",$ls_descon,$la_descon,3);
			}
			break;

		case "ELIMINAR":
			uf_load_variables();
			$lb_valido=$io_concepto->uf_delete_concepto($ls_codconc,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
			}
			else
			{
				$ls_signo=" disabled";
				$io_fun_nomina->uf_seleccionarcombo("A-D-P-R-B-E",$ls_sigcon,$la_sigcon,6);
				$io_fun_nomina->uf_seleccionarcombo("1-2-3",$ls_quirepcon,$la_quirepcon,3);
				if($ls_glocon=="1")
				{
					$ls_global="checked";
				}
				if($ls_aplisrcon=="1")
				{
					$ls_isr="checked";
				}
				if($ls_aplarccon=="1")
				{
					$ls_arc="checked";
				}
				if($ls_repacucon=="1")
				{
					$ls_repacucon="checked";
				}
				if($ls_repconsunicon=="1")
				{
					$ls_repconsunicon="checked";
				}
				if($ls_aplconprocon=="1")
				{
					$ls_conprocon="checked";
				}
				if($ls_sueintcon=="1")
				{
					$ls_sueldointegral="checked";
				}
				if($ls_sueintvaccon=="1")
				{
					$ls_sueldointegralvac="checked";
				}
				if($ls_intingcon=="1")
				{
					$ls_intingcon="checked";
					$ls_ingreso="style='visibility:visible'";
					$ls_porcentajeingreso = "enabled";
				}
				if($ls_frevarcon=="1")
				{
					$ls_frevarcon="checked";
				}
				if($ls_asifidper=="1")
				{
					$ls_asifidper="checked";
				}
				if($ls_asifidpat=="1")
				{
					$ls_asifidpat="checked";
				}
				if($ls_persalnor=="1")
				{
					$ls_persalnor="checked";
				}
				if($ls_perenc=="1")
				{
					$ls_perenc="checked";
				}
				if($ls_aplresenc=="1")
				{
					$ls_aplresenc="checked";
				}				
				if($ls_intprocon=="1")
				{
					$ls_programatica="checked";
					$ls_estprog1="style='visibility:visible'";
					$ls_estprog2="style='visibility:visible'";
					$ls_estprog3="style='visibility:visible'";						
					$ls_estprog4="style='visibility:visible'";		
					$ls_estprog5="style='visibility:visible'";		
				}
				if($li_conprenom=="0")
				{
					$ls_prenomina="";
				}
				switch ($ls_sigcon)
				{
					case "A":
						$ls_presupuesto="style='visibility:visible'";
						$ls_contable="style='visibility:hidden'";
						break;
	
					case "E":
						$ls_presupuesto="style='visibility:visible'";
						$ls_contable="style='visibility:hidden'";
						break;
	
					case "D":
						$ls_presupuesto="style='visibility:hidden'";
						$ls_contable="style='visibility:visible'";
						break;
	
					case "P":
						$ls_presupuesto="style='visibility:hidden'";
						$ls_contable="style='visibility:visible'";
						break;
	
					case "B":
						$ls_presupuesto="style='visibility:hidden'";
						$ls_contable="style='visibility:visible'";
						break;
	
					default:
						$ls_presupuesto="style='visibility:hidden'";
						$ls_contable="style='visibility:hidden'";
						break;
	
				}
			}
			break;

		case "BUSCAR":
			$ls_codconc=$_POST["txtcodconc"];
			$ls_intprocon=$io_fun_nomina->uf_obtenervalor("chkintprocon","0");
			$lb_valido=$io_concepto->uf_load_concepto($ls_existe,$ls_codconc,$ls_nomcon,$ls_titcon,$ls_forcon,$li_acumaxcon,$li_valmincon,
													  $li_valmaxcon,$ls_concon,$ls_cueprecon,$ls_cueconcon,$ls_codpro,$ls_sigcon,
													  $ls_glocon,$ls_aplisrcon,$ls_sueintcon,$ls_intprocon,$ls_forpatcon,$ls_cueprepatcon,
													  $ls_cueconpatcon,$ls_titretempcon,$ls_titretpatcon,$li_valminpatcon,$li_valmaxpatcon,
													  $ls_denprecon,$ls_denconcon,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
													  $ls_codestpro5,$ls_denestpro1,$ls_denestpro2,$ls_denestpro3,$ls_denestpro4,$ls_denestpro5,
													  $ls_dencueconpat,$ls_dencueprepat,$li_conprenom,$ls_sueintvaccon,$ls_descon,
													  $ls_coddescon,$ls_desdescon,$ls_aplarccon,$ls_aplconprocon,$ls_estcla1,$ls_estcla2,
													  $ls_estcla3,$ls_estcla4,$ls_estcla5,$ls_intingcon,$ls_cueingcon,$ls_dencueing,$li_poringcon,
													  $ls_repacucon,$ls_repconsunicon,$ls_consunicon,$ls_quirepcon,$ls_frevarcon,$ls_asifidper,$ls_asifidpat,$ls_persalnor,$ls_perenc, $ls_aplresenc, $ls_codente, $ls_txtente);
			$ls_concepto=" readonly";
			$ls_signo=" disabled";
			$ls_personal="";
			if($ls_glocon=="1")
			{
				$ls_global="checked";
			}
			if($ls_aplisrcon=="1")
			{
				$ls_isr="checked";
			}
			if($ls_aplarccon=="1")
			{
				$ls_arc="checked";
			}
			if($ls_repacucon=="1")
			{
				$ls_repacucon="checked";
			}
			if($ls_repconsunicon=="1")
			{
				$ls_repconsunicon="checked";
			}
			if($ls_aplconprocon=="1")
			{
				$ls_conprocon="checked";
			}
			if($ls_sueintcon=="1")
			{
				$ls_sueldointegral="checked";
			}
			if($ls_sueintvaccon=="1")
			{
				$ls_sueldointegralvac="checked";
			}
			if($li_conprenom=="0")
			{
				$ls_prenomina="";
			}
			if($ls_frevarcon=="1")
			{
				$ls_frevarcon="checked";
			}
			if($ls_asifidper=="1")
			{
				$ls_asifidper="checked";
			}
			if($ls_asifidpat=="1")
			{
				$ls_asifidpat="checked";
			}
			if($ls_persalnor=="1")
			{
				$ls_persalnor="checked";
			}
			if($ls_perenc=="1")
			{
				$ls_perenc="checked";
			}
			if($ls_aplresenc=="1")
			{
				$ls_aplresenc="checked";
			}	
			if($ls_intprocon=="1")
			{
				$ls_programatica="checked";
				$ls_estprog1="style='visibility:visible'";
				$ls_estprog2="style='visibility:visible'";
				$ls_estprog3="style='visibility:visible'";						
				$ls_estprog4="style='visibility:visible'";		
				$ls_estprog5="style='visibility:visible'";		
			}
			if($ls_intingcon=="1")
			{
				$ls_intingcon="checked";
				$ls_ingreso="style='visibility:visible'";
				$ls_porcentajeingreso = "enabled";
			}				
			$io_fun_nomina->uf_seleccionarcombo("A-D-P-R-B-E",$ls_sigcon,$la_sigcon,6);
			$io_fun_nomina->uf_seleccionarcombo("1-2-3",$ls_quirepcon,$la_quirepcon,3);
			$io_fun_nomina->uf_seleccionarcombo(" -P-B",$ls_descon,$la_descon,3);
			switch ($ls_sigcon)
			{
				case "A":
					$ls_presupuesto="style='visibility:visible'";
					$ls_contable="style='visibility:hidden'";
					break;

				case "E":
					$ls_presupuesto="style='visibility:visible'";
					$ls_contable="style='visibility:hidden'";
					break;

				case "D":
					$ls_presupuesto="style='visibility:hidden'";
					$ls_contable="style='visibility:visible'";
					break;

				case "P":
					$ls_presupuesto="style='visibility:hidden'";
					$ls_contable="style='visibility:visible'";
					break;

				case "B":
					$ls_presupuesto="style='visibility:hidden'";
					$ls_contable="style='visibility:visible'";
					break;

				default:
					$ls_presupuesto="style='visibility:hidden'";
					$ls_contable="style='visibility:hidden'";
					break;

			}
			break;

		case "CARGARAPORTE":
			uf_load_variables();
			if($ls_glocon=="1")
			{
				$ls_global="checked";
			}
			if($ls_aplisrcon=="1")
			{
				$ls_isr="checked";
			}
			if($ls_aplarccon=="1")
			{
				$ls_arc="checked";
			}
			if($ls_repacucon=="1")
			{
				$ls_repacucon="checked";
			}
			if($ls_repconsunicon=="1")
			{
				$ls_repconsunicon="checked";
			}
			if($ls_aplconprocon=="1")
			{
				$ls_conprocon="checked";
			}
			if($ls_sueintcon=="1")
			{
				$ls_sueldointegral="checked";
			}
			if($ls_sueintvaccon=="1")
			{
				$ls_sueldointegralvac="checked";
			}
			if($ls_intprocon=="1")
			{
				$ls_programatica="checked";
				$ls_estprog1="style='visibility:visible'";
				$ls_estprog2="style='visibility:visible'";
				$ls_estprog3="style='visibility:visible'";						
				$ls_estprog4="style='visibility:visible'";		
				$ls_estprog5="style='visibility:visible'";		
			}
			if($ls_frevarcon=="1")
			{
				$ls_frevarcon="checked";
			}
			if($ls_asifidper=="1")
			{
				$ls_asifidper="checked";
			}
			if($ls_asifidpat=="1")
			{
				$ls_asifidpat="checked";
			}
			if($li_conprenom=="0")
			{
				$ls_prenomina="";
			}
			if($ls_persalnor=="1")
			{
				$ls_persalnor="checked";
			}
			if($ls_intingcon=="1")
			{
				$ls_intingcon="checked";
				$ls_ingreso="style='visibility:visible'";
				$ls_porcentajeingreso = "enabled";
			}	
			if($ls_perenc=="1")
			{
				$ls_perenc="checked";
			}
			if($ls_aplresenc=="1")
			{
				$ls_aplresenc="checked";
			}				
			$io_fun_nomina->uf_seleccionarcombo("A-D-P-R-B-E",$ls_sigcon,$la_sigcon,6);
			$io_fun_nomina->uf_seleccionarcombo("1-2-3",$ls_quirepcon,$la_quirepcon,3);
			$io_fun_nomina->uf_seleccionarcombo(" -P-B",$ls_descon,$la_descon,3);
			switch ($ls_sigcon)
			{
				case "A":
					$ls_presupuesto="style='visibility:visible'";
					$ls_contable="style='visibility:hidden'";
					break;

				case "E":
					$ls_presupuesto="style='visibility:visible'";
					$ls_contable="style='visibility:hidden'";
					break;

				case "D":
					$ls_presupuesto="style='visibility:hidden'";
					$ls_contable="style='visibility:visible'";
					break;

				case "P":
					$ls_presupuesto="style='visibility:hidden'";
					$ls_contable="style='visibility:visible'";
					break;

				case "B":
					$ls_presupuesto="style='visibility:hidden'";
					$ls_contable="style='visibility:visible'";
					break;

				default:
					$ls_presupuesto="style='visibility:hidden'";
					$ls_contable="style='visibility:hidden'";
					break;

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
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_nomina.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" title='Nuevo' alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title='Guardar 'alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" title='Buscar' alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" title='Eliminar' alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title='Salir' alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" title='Ayuda' alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_abrirformulas();"><img src="../shared/imagebank/tools20/herramienta.png" title='Formulas' alt="Formulas" width="20" height="20" border="0"></a></div></td>
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
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="730" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-ventana">Definición de Concepto </td>
        </tr>
        <tr>
          <td width="175" height="22">&nbsp;</td>
          <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo</div></td>
          <td colspan="3"><div align="left">
            <input name="txtcodconc" type="text" id="txtcodconc" size="13" maxlength="10" value="<?php print $ls_codconc;?>" onKeyUp="javascript: ue_validarnumero(this);" onBlur="javascript: ue_rellenarcampo(this,10);" <?php print $ls_concepto;?>>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Nombre</div></td>
          <td colspan="3"><div align="left">
            <input name="txtnomcon" type="text" id="txtnomcon" value="<?php print $ls_nomcon;?>" size="33" maxlength="30" onKeyUp="javascript: ue_validarcomillas(this);" onBlur="javascript: ue_copiar();">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">T&iacute;itulo</div></td>
          <td colspan="3"><div align="left">
            <input name="txttitcon" type="text" id="txttitcon" value="<?php print $ls_titcon;?>" size="90" maxlength="254" onKeyUp="javascript: ue_validarcomillas(this);">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Signo</div></td>
          <td colspan="3"><div align="left">
            <select name="cmbsigcon" id="cmbsigcon" onChange="javascript: ue_cargaraporte();" <?php print $ls_signo;?>>
              <option value="" selected>--Seleccione Una--</option>
              <option value="A" <?php print $la_sigcon[0]; ?>>Asignaci&oacute;n</option>
              <option value="D" <?php print $la_sigcon[1]; ?>>Deducci&oacute;n</option>
              <option value="P" <?php print $la_sigcon[2]; ?>>Aporte Patronal</option>
              <option value="R" <?php print $la_sigcon[3]; ?>>Reporte</option>
              <option value="B" <?php print $la_sigcon[4]; ?>>Reintegro Deducci&oacute;n</option>
              <option value="E" <?php print $la_sigcon[5]; ?>>Reintegro Asignaci&oacute;n</option>
              </select>
            <input name="txtsigcon" type="hidden" id="txtsigcon" value="<?php print $ls_sigcon;?>">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">F&oacute;rmula</div></td>
          <td colspan="3">
            <div align="left">
              <textarea name="txtforcon" cols="90" rows="2" id="txtforcon" onKeyUp="javascript: ue_validarcomillas(this);" <?php print $ls_activarbloqueo; ?>="<?php print $ls_activarbloqueo; ?>"><?php print $ls_forcon;?></textarea>
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Acumulado M&aacute;ximo </div></td>
          <td colspan="3"><div align="left">
            <input name="txtacumaxcon" type="text" id="txtacumaxcon" value="<?php print $li_acumaxcon;?>" size="23" maxlength="20" onKeyPress="return(ue_formatonumero(this,'.',',',event))" style="text-align:right">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Valor M&iacute;nimo </div></td>
          <td colspan="3"><div align="left">
            <input name="txtvalmincon" type="text" id="txtvalmincon" value="<?php print $li_valmincon;?>" size="23" maxlength="20" onKeyPress="return(ue_formatonumero(this,'.',',',event))" style="text-align:right">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Valor M&aacute;ximo </div></td>
          <td colspan="3"><div align="left">
            <input name="txtvalmaxcon" type="text" id="txtvalmaxcon" value="<?php print $li_valmaxcon;?>" size="23" maxlength="20" onKeyPress="return(ue_formatonumero(this,'.',',',event))" style="text-align:right">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Condici&oacute;n</div></td>
          <td colspan="3"><div align="left">
            <input name="txtconcon" type="text" id="txtconcon" value="<?php print $ls_concon;?>" size="90" maxlength="500" onKeyUp="javascript: ue_validarcomillas(this);">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Aplica Impuesto Sobre Renta </div></td>
          <td width="45"><div align="left"><input name="chkaplisrcon" type="checkbox" class="sin-borde" id="chkaplisrcon" value="1" <?php print $ls_isr; if($ls_isr==""){print $ls_activarislr;}?>></div></td>
          <td><div align="right"><?php if ($ls_sueint==""){print "Pertenece al Sueldo Integral";}else{print "Pertenece a ". $ls_sueint;}?> </div></td>
          <td width="214"><div align="left"><input name="chksueintcon" type="checkbox" class="sin-borde" id="chksueintcon" value="1" onClick="javascript: ue_activarfideicomiso('SUELDOI');" <?php print $ls_sueldointegral;?>></div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Concepto Global  </div></td>
          <td><div align="left"><input name="chkglocon" type="checkbox" class="sin-borde" id="chkglocon" value="1" <?php print $ls_global;?> onClick="javascript: ue_chequear_encargaduria('3');"></div></td>
          <td><div align="right">Evaluar en Pren&oacute;mina</div></td>
          <td><div align="left"><input name="chkconprenom" type="checkbox" class="sin-borde" id="chkconprenom" value="1" <?php print $ls_prenomina;?>></div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Integrar Program&aacute;tica Concepto</div></td>
          <td><div align="left"><input name="chkintprocon" type="checkbox" class="sin-borde" id="chkintprocon" onClick="javascript: ue_activarprogramatica();" value="1" <?php print $ls_programatica;?>></div></td>
          <td><div align="right"><?php if ($ls_sueint==""){print "Pertenece al Sueldo Integral de Vacaciones";}else{print "Pertenece a ". $ls_sueint." de Vacaciones";}?> </div></td>
          <td><div align="left"><input name="chksueintvaccon" type="checkbox" class="sin-borde" id="chksueintvaccon" value="1" <?php print $ls_sueldointegralvac;?>></div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Aplica ARC </div></td>
          <td><div align="left">
            <input name="chkaplarccon" type="checkbox" class="sin-borde" id="chkaplarccon" value="1" <?php print $ls_arc;?>>
          </div></td>
          <td><div align="right">Contabilizaci&oacute;n por Proyecto </div></td>
          <td>
            <input name="chkaplconprocon" type="checkbox" class="sin-borde" id="chkaplconprocon" value="1" <?php print $ls_conprocon; if($li_confconprocon!="1"){ print "disabled";} ?> >          </td>
        </tr>
        <tr>
          <td height="22"><div align="right">Integrar con Ingresos </div></td>
          <td><div align="left">
            <input name="chkintingcon" type="checkbox" class="sin-borde" id="chkintingcon" onClick="javascript: ue_activaringreso();" value="1" <?php print $ls_intingcon;?>>
          </div></td>
          <td><div align="right">Asignar a fideicomiso </div></td>
          <td><input name="chkasifidper" type="checkbox" class="sin-borde" id="chkasifidper" value="1" onClick="javascript: ue_activarfideicomiso('FIDEICOMISO');" <?php print $ls_asifidper;?>></td>
        </tr>
              <tr>
                <td height="22"><div align="right">Frecuencia Variable </div></td>
                <td><input name="chkfrevarcon" type="checkbox" class="sin-borde" id="chkfrevarcon" value="1" <?php print $ls_frevarcon;?>></td>
				<td><div align="right">Pertenece a Salario Normal</div></td>
				<td><input name="chkpersalnor" type="checkbox" class="sin-borde" id="chkchkpersalnor" value="1"  <?php print $ls_persalnor;?>></td>
              </tr>
		<tr>
                <td height="22"><div align="right">Pertenece a Encargadur&iacute;a </div></td>
                <td><input name="chkperenc" type="checkbox" class="sin-borde" id="chkperenc" value="1" <?php print $ls_perenc;?> onClick="javascript: ue_chequear_encargaduria('1');"></td>
				<td><div align="right">Resumen por Encargadur&iacute;a </div></td>
				<td><input name="chkaplresenc" type="checkbox" class="sin-borde" id="chkchkaplresenc" value="1"  <?php print $ls_aplresenc; print $ls_activarresenc;?> onClick="javascript: ue_chequear_encargaduria('2');"></td>
              </tr>
              <tr>
                <td height="22"><div align="right">Ente </div></td>
                <td  colspan="3"><input name="txt_codente" type="text" id="txt_codente" value="<?php print $ls_codente;?>" size="13" readonly>
                  <a href="javascript:ue_ente();"><img src="../shared/imagebank/tools/buscar.gif" alt="Buscar" width="15" height="15" border="0" ></a>
                <input name="txt_ente" type="text" class="sin-borde" id="txt_ente" value="<?php print $ls_txtente;?>" size="53" readonly>                  </td>
              </tr>
              <tr>
                <td height="22">&nbsp;</td>
                <td  colspan="3"><div align="left"><strong><?php print $ls_titulo; ?></strong></div></td>
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
          <td height="22"><div align="right">Cuenta de Presupuesto </div></td>
          <td colspan="3"><div align="left">
              <input name="txtcuepre" type="text" id="txtcuepre" value="<?php print $ls_cueprecon;?>" size="28" maxlength="25" readonly>
              <a href="javascript: ue_buscarcuentapresupuesto();"><img id="cuentapresupuesto" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0" <?php print $ls_presupuesto ?>></a>
              <input name="txtdencuepre" type="text" class="sin-borde" id="txtdencuepre" value="<?php print $ls_denprecon;?>" size="50" maxlength="100" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Cuenta Contable </div></td>
          <td colspan="3"><div align="left">
            <input name="txtcuecon" type="text" id="txtcuecon" value="<?php print $ls_cueconcon;?>" size="28" maxlength="25" readonly>
            <a href="javascript: ue_buscarcuentacontable();"><img id="cuentaabono" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0" <?php print $ls_contable?>></a>
            <input name="txtdencuecon" type="text" class="sin-borde" id="txtdencuecon" value="<?php print $ls_denconcon;?>" size="50" maxlength="100" readonly>
		  </div></td>
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
          <td height="22" colspan="4" class="titulo-celdanew">Reportes</td>
          </tr>
        <tr>
          <td height="22"><div align="right">Reportar Acumulado </div></td>
          <td colspan="3"><div align="left">
            <input name="chkrepacucon" type="checkbox" class="sin-borde" id="chkrepacucon" value="1" <?php print $ls_repacucon;?>>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Reportar Constante de Unidad </div></td>
          <td colspan="3"><div align="left">
                <input name="chkrepconsunicon" type="checkbox" class="sin-borde" id="chkrepconsunicon" value="1" <?php print $ls_repconsunicon;?>> 
            C&oacute;digo Constante 
            <input name="txtconsunicon" type="text" id="txtconsunicon" value="<?php print $ls_consunicon;?>" size="20" maxlength="10" onBlur="javascript: ue_rellenarcampo(this,10);">          
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Reportar en </div></td>
          <td colspan="3"><select name="cmbquirepcon" id="cmbquirepcon" <?php print $ls_activoreporte;?>>
            <option value="-" selected>--Seleccione Una--</option>
            <option value="1" <?php print $la_quirepcon[0]; ?>>Primera Quincena</option>
            <option value="2" <?php print $la_quirepcon[1]; ?>>Segunda Quincena</option>
            <option value="3" <?php print $la_quirepcon[2]; ?>>Ambas Quincenas</option>
          </select> 
            (Solo para n&oacute;minas mensuales con divisi&oacute;n de concepto) </td>
        </tr>
<?php if($ls_sigcon=="P") { ?>		
        <tr class="titulo-celdanew">
          <td height="20" colspan="4">           
            <div align="left" class="titulo-celdanew">Aporte Patronal </div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">Asignar al fideicomiso </div></td>
          <td colspan="3"><div align="left">
            <input name="chkasifidpat" type="checkbox" class="sin-borde" id="chkasifidpat" value="1" <?php print $ls_asifidpat;?>>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">F&oacute;rmula</div></td>
          <td colspan="3">
            <div align="left">
              <textarea name="txtforpatcon" cols="90" rows="2" id="txtforpatcon" onKeyUp="javascript: ue_validarcomillas(this);"><?php print $ls_forpatcon;?></textarea>
            </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Valor M&iacute;nimo </div></td>
          <td colspan="3">            <div align="left">
            <input name="txtvalminpatcon" type="text" id="txtvalminpatcon" value="<?php print $li_valminpatcon;?>" size="23" maxlength="20" onKeyPress="return(ue_formatonumero(this,'.',',',event))" style="text-align:right">          
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Valor M&aacute;ximo </div></td>
          <td colspan="3">            <div align="left">
            <input name="txtvalmaxpatcon" type="text" id="txtvalmaxpatcon" value="<?php print $li_valmaxpatcon;?>" size="23" maxlength="20" onKeyPress="return(ue_formatonumero(this,'.',',',event))" style="text-align:right">          
          </div></td>
        </tr>
        <tr>
          <td><div align="right">Estad&iacute;stico</div></td>
          <td colspan="3">
            <div align="left">
              <input name="txtcueprepat" type="text" id="txtcueprepat" value="<?php print $ls_cueprepatcon;?>" size="28" maxlength="25" readonly>
              <a href="javascript: ue_buscarcuentapresupuesto_patron();"><img id="cuentapresupuestopatron" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>            
              <input name="txtdencueprepat" type="text" class="sin-borde" id="txtdencueprepat" value="<?php print $ls_dencueprepat;?>" size="50" maxlength="100" readonly>          
            </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Cuenta Contable </div></td>
          <td colspan="3">
            <div align="left">
              <input name="txtcueconpat" type="text" id="txtcueconpat" value="<?php print $ls_cueconpatcon;?>" size="28" maxlength="25" readonly>
              <a href="javascript: ue_buscarcuentacontable_patron();"><img id="cuentaabonopatron" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>            
              <input name="txtdencueconpat" type="text" class="sin-borde" id="txtdencueconpat" value="<?php print $ls_dencueconpat;?>" size="50" maxlength="100" readonly>          
            </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">T&iacute;tulo de Reporte Personal </div></td>
          <td colspan="3">            <div align="left">
            <input name="txttitretempcon" type="text" id="txttitretempcon" value="<?php print $ls_titretempcon;?>" size="13" maxlength="10" onKeyUp="javascript: ue_validarcomillas(this);">          
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">T&iacute;tulo de Reporte Patr&oacute;n </div></td>
          <td colspan="3">            <div align="left">
            <input name="txttitretpatcon" type="text" id="txttitretpatcon" value="<?php print $ls_titretpatcon;?>" size="13" maxlength="10" onKeyUp="javascript: ue_validarcomillas(this);">          
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Destino de Contabilizaci&oacute;n </div></td>
          <td colspan="3">
            <div align="left">
              <select name="cmbdescon" id="cmbdescon" onChange="javascript: ue_limpiar();">
                <option value=" " <?php print $la_descon[0]; ?>> </option>
                <option value="P" <?php print $la_descon[1]; ?>>PROVEEDOR</option>
                <option value="B" <?php print $la_descon[2]; ?>>BENEFICIARIO</option>
              </select>
              <input name="txtcodproben" type="text" id="txtcodproben" value="<?php print $ls_coddescon;?>" size="15" maxlength="10" readonly>
              <a href="javascript: ue_buscardestino();"><img  src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>            
              <input name="txtnombre" type="text" class="sin-borde" id="txtnombre" value="<?php print $ls_desdescon;?>" size="50" maxlength="30" readonly>          
            </div></td>
        </tr>
<?php } ?>				
        <tr>
          <td height="22"><div align="right"></div></td>
          <td colspan="3">
            <div align="left">
              <input name="operacion" type="hidden" id="operacion">            
              <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>">          
              <input name="modalidad" type="hidden" id="modalidad" value="<?php print $ls_modalidad;?>">
              <input name="calculada" type="hidden" id="calculada" value="<?php print $li_calculada;?>">
            </div></td>
        </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td colspan="3"><div align="left">
            <input name="btnpersonalconcepto" type="button" class="boton" id="btnpersonalconcepto" value="Personal" onClick="javascript: ue_personal();" <?php print $ls_personal;?>>
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
function ue_cargaraporte()
{
	f=document.form1;
	f.operacion.value="CARGARAPORTE";
	f.action="sigesp_sno_d_concepto.php";
	f.submit();
}

function ue_nuevo()
{
	f=document.form1;
	li_calculada=f.calculada.value;
	if(li_calculada=="0")
	{		
		li_incluir=f.incluir.value;
		if(li_incluir==1)
		{	
			f.operacion.value="NUEVO";
			f.existe.value="FALSE";		
			f.action="sigesp_sno_d_concepto.php";
			f.submit();
		}
		else
		{
			alert("No tiene permiso para realizar esta operacion");
		}
	}
	else
	{
		alert("La nómina ya se calculó reverse y vuelva a intentar");
	}
}

function ue_guardar()
{
	f=document.form1;
	li_calculada=f.calculada.value;
	if(li_calculada=="0")
	{		
		li_incluir=f.incluir.value;
		li_cambiar=f.cambiar.value;
		lb_existe=f.existe.value;
		valido=true;
		if(((lb_existe=="TRUE")&&(li_cambiar==1))||(lb_existe=="FALSE")&&(li_incluir==1))
		{
			codconc = ue_validarvacio(f.txtcodconc.value);
			nomcon = ue_validarvacio(f.txtnomcon.value);
			titcon = ue_validarvacio(f.txttitcon.value);
			sigcon = ue_validarvacio(f.cmbsigcon.value);
			forcon = ue_validarvacio(f.txtforcon.value);
			concon = ue_validarvacio(f.txtconcon.value);
			acumaxcon = ue_validarvacio(f.txtacumaxcon.value);
			valmincon = ue_validarvacio(f.txtvalmincon.value);
			valmaxcon = ue_validarvacio(f.txtvalmaxcon.value);
			cuenta="V";
			destino="P";
			codproben="----------";
			forpatcon="";
			if((sigcon=="A")||(sigcon=="E"))
			{
				cuenta= ue_validarvacio(f.txtcuepre.value);
			}
			if((sigcon=="D")||(sigcon=="P")||(sigcon=="B"))
			{
				cuenta= ue_validarvacio(f.txtcuecon.value);
			}
			if(f.chkintingcon.checked)
			{
				cuenta= ue_validarvacio(f.txtcueingcon.value);
				if(f.txtporingcon.value<=0)
				{
					alert("El Procentaje debe ser mayor que cero.");
					valido=false;
				}
			}
			if(sigcon=="P")
			{
				destino = ue_validarvacio(f.cmbdescon.value);
				codproben = ue_validarvacio(f.txtcodproben.value);
				forpatcon = ue_validarvacio(f.txtforpatcon.value);
				cueprepat = ue_validarvacio(f.txtcueprepat.value);
				cueconpat = ue_validarvacio(f.txtcueconpat.value);
				if ((forpatcon=="")||(cueprepat=="")||(cueconpat==""))
				{
					valido=false;
					alert("Debe llenar los datos del Aporte Patronal.");
				}
			}
			if(valido)
			{
				valido=ue_validar_formula(forcon,"Formula del concepto Inválida (IIF).");
			}
			if(valido)
			{
				valido=ue_validar_formula(concon,"Condición del concepto Inválida (IIF).");
			}
			if(valido)
			{
				valido=ue_validar_formula(forpatcon,"Fórmula del concepto Patrón Inválida (IIF).");
			}
			if(valido)
			{
				if ((codconc!="")&&(nomcon!="")&&(titcon!="")&&(sigcon!="")&&(forcon!="")&&(acumaxcon!="")&&(valmincon!="")&&
					(valmaxcon!="")&&(cuenta!="")&&(destino!="")&&(codproben!=""))
				{
					f.chkaplresenc.disabled=false;
					f.operacion.value="GUARDAR";					
					f.action="sigesp_sno_d_concepto.php";
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
	else
	{
		alert("La nómina ya se calculó reverse y vuelva a intentar");
	}
}

function ue_eliminar()
{
	f=document.form1;
	li_calculada=f.calculada.value;
	if(li_calculada=="0")
	{		
		li_eliminar=f.eliminar.value;
		if(li_eliminar==1)
		{	
			if(f.existe.value=="TRUE")
			{
				codconc = ue_validarvacio(f.txtcodconc.value);
				if (codconc!="")
				{
					if(confirm("¿Desea eliminar el Registro actual?"))
					{
						f.operacion.value="ELIMINAR";
						f.action="sigesp_sno_d_concepto.php";
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
	else
	{
		alert("La nómina ya se calculó reverse y vuelva a intentar");
	}
}

function ue_chequear_encargaduria(tipo)
{
	f=document.form1;
	if (tipo=='2')
	{
		sigcon=f.cmbsigcon.value;
		if ((sigcon!="A")&&(f.chkaplresenc.checked))
		{
			alert("Este tipo solamente aplica para conceptos de tipo Asignación");
			f.chkaplresenc.checked=false;
		}
		if ((f.chkperenc.checked)&&(f.chkaplresenc.checked))
		{
			alert("Este tipo no se puede asociar a un concepto que pertenezca a la Encargaduria");
			f.chkaplresenc.checked=false;
		}
		if (f.chkglocon.checked)
		{
			alert("El tipo de concepto Resumen por Encargaduría no puede ser de tipo global");
			f.chkglocon.checked=false;
		}		
		
	}
	else if (tipo=='1')
	{
		if ((f.chkperenc.checked)&&(f.chkaplresenc.checked))
		{
			alert("El tipo de concepto Resumen por Encargaduría no aplica tipo a este tipo");
			f.chkperenc.checked=false;
		}
		
	}
	else if (tipo=='3')
	{
		if ((f.chkaplresenc.checked)&&(f.chkglocon.checked))
		{
			alert("El tipo de concepto Resumen por Encargaduría no puede ser de tipo global");
			f.chkglocon.checked=false;
		}
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
		window.open("sigesp_sno_cat_concepto.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_abrirformulas()
{
	f=document.form1;
	window.open("manuales/funciones_de_nomina.pdf","formulas","menubar=no,toolbar=no,scrollbars=yes,width=750,height=600,left=50,top=50,location=no,resizable=yes");
}

function ue_buscarcuentapresupuesto()
{
	f=document.form1;
	sigcon = ue_validarvacio(f.cmbsigcon.value);
	if((sigcon=="A")||(sigcon=="E"))
	{
		window.open("sigesp_sno_cat_cuentapresupuesto.php?spg_cuenta=<?php print $ls_spgcuenta;?>","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
}

function ue_buscarcuentapresupuesto_patron()
{
	window.open("sigesp_sno_cat_cuentapresupuesto.php?spg_cuenta=<?php print $ls_spgcuenta;?>&tipo=PATRONAL","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarcuentacontable()
{
	f=document.form1;
	sigcon = ue_validarvacio(f.cmbsigcon.value);
	if((sigcon=="D")||(sigcon=="P")||(sigcon=="B"))
	{
		window.open("sigesp_sno_cat_cuentacontable.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
}

function ue_buscarcuentaingreso()
{
	f=document.form1;
	sigcon = ue_validarvacio(f.cmbsigcon.value);
	if((sigcon=="D")&&(f.chkintingcon.checked))
	{
		window.open("sigesp_sno_cat_cuentaingreso.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
}

function ue_buscarcuentacontable_patron()
{
	window.open("sigesp_sno_cat_cuentacontable.php?tipo=PATRONAL","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_personal()
{
	f=document.form1;
	codconc=ue_validarvacio(f.txtcodconc.value);
	nomcon=ue_validarvacio(f.txtnomcon.value);
	location.href="sigesp_sno_d_conceptopersonal.php?codconc="+codconc+"&nomcon="+nomcon+"";
}

function ue_estructura1()
{
	   window.open("sigesp_snorh_cat_estpre1.php?tipo=asignacioncargo","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
}

function ue_ente()
{
	   window.open("sigesp_sno_cat_ente.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
}

function ue_estructura2()
{
	f=document.form1;
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

function ue_estructura3()
{
	f=document.form1;
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

function ue_estructura4()
{
	f=document.form1;
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

function ue_estructura5()
{
	f=document.form1;
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

//--------------------------------------------------------
//	Función que habilita los campos de la programática
//--------------------------------------------------------
function ue_activarprogramatica()
{
	f=document.form1;
	sigcon = ue_validarvacio(f.cmbsigcon.value);
	if((sigcon=="A")||(sigcon=="E")||(sigcon=="P"))
	{
		if(f.chkintprocon.checked)
		{
			document.images["estpro1"].style.visibility="visible";
			document.images["estpro2"].style.visibility="visible";
			document.images["estpro3"].style.visibility="visible";
			if(f.modalidad.value=="2")
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
			if(f.modalidad.value=="2")
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
		f.chkintprocon.checked=false;
		document.images["estpro1"].style.visibility="hidden";
		document.images["estpro2"].style.visibility="hidden";
		document.images["estpro3"].style.visibility="hidden";
		if(f.modalidad.value=="2")
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
		f.txtestcla1.value="";
		f.txtestcla2.value="";
		f.txtestcla3.value="";
		f.txtestcla4.value="";
		f.txtestcla5.value="";
		
	}
}

//--------------------------------------------------------
//	Función que habilita los campos del las cuentas de ingreso
//--------------------------------------------------------
function ue_activaringreso()
{
	f=document.form1;
	if(f.cmbsigcon.value=="D")
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

function ue_limpiar()
{
	f=document.form1;
	f.txtcodproben.value="";
	f.txtnombre.value="";
}

function ue_buscardestino()
{
	f=document.form1;
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

function ue_copiar()
{
	f=document.form1;
	if(f.txttitcon.value=="")
	{
		f.txttitcon.value=f.txtnomcon.value;
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

function ue_validar_formula(formula, texto)
{
	valido=true;	
	len=formula.length;
	pos=strpos(formula,"II");
	if(pos==-1)
	{
		pos=strpos(formula,"ii");
	}
	aux=formula;
	while((pos>=0)&&(valido))
	{
		cadena=aux.substr(pos,3);
		aux=aux.substr(pos+3,len);	
		if((cadena!='IIF')&&(cadena!='iif'))
		{
			valido=false;
			alert(texto);
		}
		else
		{
			pos=strpos(aux, 'i');
		}	
	} 
	return valido;
}

function strpos(str, ch)
{
	for (var i = 0; i < str.length; i++)
	if (str.substring(i, i+1) == ch) return i;
	return -1;
}
</script> 
</html>
