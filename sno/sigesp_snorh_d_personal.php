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
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_d_personal.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   
   function uf_limpiarvariables()
   {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function:  uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $ls_codper,$ls_cedper,$ls_nomper,$ls_apeper,$ls_dirper,$ld_fecnacper,$ls_edocivper,$ls_telhabper,$ls_telmovper;
		global $ls_sexper,$li_estaper,$li_pesper,$ls_codpro,$ls_nivacaper,$ls_catper,$ls_cajahoper,$li_numhijper,$ls_contraper;
		global $li_tipvivper,$ls_tenvivper,$li_monpagvivper,$ls_cuecajahoper,$ls_cuelphper,$ls_cuefidper,$ld_fecingadmpubper;
		global $ld_fecingper,$li_anoservpreper,$ld_fecegrper,$ls_nomfot,$ls_tipfot,$ls_tamfot,$ls_codpai,$ls_codest,$ls_codmun;
		global $ls_codpar,$ls_obsper,$ls_cauegrper,$ls_obsegrper,$ls_cedbenper,$ls_estper,$ls_despro,$ls_despai,$ls_desest;
		global $ls_desmun,$ls_despar,$ls_familiar,$ls_estudio,$ls_trabajo,$la_edocivper,$la_sexper,$la_nivacaper,$la_contraper;
		global $la_tipvivper,$la_cauegrper,$ls_impuesto,$ls_existe,$ls_vacacion,$ls_permiso,$la_nacper,$ls_coreleper,$ls_operacion;
		global $io_fun_nomina,$ls_cenmedper,$lb_valido,$ls_turper,$ls_horper,$ls_tipsanper,$ls_hcmper,$la_turper;
		global $ls_codcom,$ls_descom,$ls_codran,$ls_desran,$ls_numexpper,$ls_codpainac,$ls_codestnac,$ls_despainac,$ls_desestnac,$ls_beneficiario;
		global $ls_codtippersss,$ls_dentippersss, $ld_fecreingper, $ld_fecjubper, $ls_codunivipladin, $ls_denunivipladin, $la_enviorec,
		        $ld_fecleypen;
		global $io_personal, $li_alfnumcodper, $ls_codcausa, $ls_dencausa, $la_situacion, $ld_fecsitu;
		global $li_talcamper, $li_talpanper, $li_talzapper, $li_anoservprecont, $li_anoservprefijo, $ls_cauegrper2, $ls_codorg, $ls_desorg,$li_porcajahoper, $ls_codger, $ls_denger, $li_anoperobr,$ls_carantper;
		
		$ls_codper="";
		$ls_cedper="";
	 	$ls_nomper="";
		$ls_apeper="";
	 	$ls_dirper="";
		$ld_fecnacper="dd/mm/aaaa";			
		$ls_edocivper="";
		$ls_telhabper="";
		$ls_telmovper="";
	 	$ls_sexper="";
	 	$li_estaper=0;
		$li_pesper=0;
	 	$ls_codpro="";
	 	$ls_nivacaper="";
		$ls_catper="";
	 	$ls_cajahoper="";
		$ls_hcmper="";
	 	$li_numhijper=0;
		$ls_contraper="";
	 	$li_tipvivper="";
	 	$ls_tenvivper="";
		$li_monpagvivper=0;
	 	$ls_cuecajahoper="";
	 	$ls_cuelphper="";
		$ls_cuefidper="";
	 	$ld_fecingadmpubper="dd/mm/aaaa";
		$ld_fecingper="dd/mm/aaaa";
	 	$li_anoservpreper=0;
		$li_anoservprecont=0;
	 	$ld_fecegrper="dd/mm/aaaa";
		$ls_nomfot="blanco.jpg"; 
		$ls_tipfot=""; 
		$ls_tamfot=""; 
	 	$ls_codpai="";
	 	$ls_codest="";
	 	$ls_codmun="";
	 	$ls_codpar="";
		$ls_obsper="";
	 	$ls_cauegrper="";
		$ls_obsegrper="";
		$ls_cedbenper="";
		$ls_estper="";
		$ls_despro="";
		$ls_despai="";
		$ls_desest="";
		$ls_desmun="";
		$ls_despar="";
		$la_nacper[0]="";
		$la_nacper[1]="";
		$la_edocivper[0]="";
		$la_edocivper[1]="";
		$la_edocivper[2]="";
		$la_edocivper[3]="";
		$la_edocivper[4]="";
		$la_sexper[0]="";
		$la_sexper[1]="";
		$la_nivacaper[0]="";
		$la_nivacaper[1]="";
		$la_nivacaper[2]="";
		$la_nivacaper[3]="";
		$la_nivacaper[4]="";
		$la_nivacaper[5]="";
		$la_nivacaper[6]="";
		$la_nivacaper[7]="";
		$la_contraper[0]="";
		$la_contraper[1]="";
		$la_tipvivper[0]="";
		$la_tipvivper[1]="";
		$la_tipvivper[2]="";
		$la_tipvivper[3]="";
		$la_cauegrper[0]="";
		$la_cauegrper[1]="";
		$la_cauegrper[2]="";
		$la_cauegrper[3]="";
		$la_cauegrper[4]="";
		$la_cauegrper[5]="";
		$la_cauegrper[6]="";
		$la_cauegrper[7]="";
		$la_cauegrper[8]="";
		$la_cauegrper[9]="";
		$la_turper[0]="";
		$la_turper[1]="";
		$ls_turper="0";
		$ls_horper="";
		$ls_familiar="disabled";
		$ls_estudio="disabled";
		$ls_trabajo="disabled";
		$ls_impuesto="disabled";
		$ls_vacacion="disabled";
		$ls_permiso="disabled";
		$ls_beneficiario="disabled";
		$ls_coreleper="";
		$ls_cenmedper="";
		$ls_tipsanper="";
		$ls_codcom="";
		$ls_descom="";
		$ls_codran="";
		$ls_desran="";
		$ls_numexpper="";
		$ls_codpainac="";
		$ls_codestnac="";
		$ls_despainac="";
		$ls_desestnac="";
		$ls_codtippersss="";
		$ls_dentippersss="";
		$ld_fecreingper="dd/mm/aaaa";
		$ld_fecjubper="dd/mm/aaaa";
		$ls_codunivipladin="";
		$ls_denunivipladin="";		
		$la_enviorec[0]="";
		$la_enviorec[1]="";	
		$ld_fecleypen="dd/mm/aaaa";		
		$ls_codcausa="";
		$ls_dencausa="";
		$lb_valido=true;
		$ls_existe=$io_fun_nomina->uf_obtenerexiste();
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		$li_alfnumcodper=$io_personal->io_sno->uf_select_config("SNO","CONFIG","ALFNUM_CODPER","0","I");
		$la_situacion[0]="";
		$la_situacion[1]="";
		$la_situacion[2]="";
		$la_situacion[3]="";
		$la_situacion[4]="";
		$ld_fecsitu="dd/mm/aaaa";
		$li_talcamper="";
		$li_talpanper="";
		$li_talzapper=0;
		$li_anoservprefijo=0;
		$ls_cauegrper2="";
		$ls_codorg="";
		$ls_desorg="";
		$li_porcajahoper=0;
		$ls_codger="";
		$ls_denger="";
		$li_anoperobr=0;
		$ls_carantper="";
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
   		global $ls_codper, $ls_estper, $ls_cedper, $ls_nomper, $ls_apeper, $ls_dirper, $ld_fecnacper, $ls_edocivper, $ls_telhabper;
		global $ls_telmovper, $ls_sexper, $ls_nacper, $li_estaper,$li_pesper, $ls_codpro, $ls_nivacaper, $ls_catper, $ls_cajahoper;
		global $li_numhijper, $ls_contraper, $li_tipvivper, $ls_tenvivper, $li_monpagvivper, $ls_cuecajahoper, $ls_cuelphper;
		global $ls_cuefidper, $ld_fecingadmpubper, $ld_fecingper, $li_anoservpreper, $ld_fecegrper, $ls_codpai, $ls_codest, $ls_codmun;
		global $ls_codpar, $ls_obsper, $ls_cauegrper, $ls_obsegrper, $ls_cedbenper, $ls_despro, $ls_despai, $ls_desest,	$ls_desmun;
		global $ls_despar, $ls_coreleper, $io_fun_nomina,$ls_cenmedper,$ls_turper,$ls_horper,$ls_hcmper,$ls_tipsanper;
		global $ls_codcom,$ls_descom,$ls_codran,$ls_desran,$ls_numexpper,$ls_codpainac,$ls_codestnac,$ls_despainac,$ls_desestnac;
		global $ls_codtippersss, $ls_dentippersss, $ld_fecreingper, $ld_fecjubper, $ls_codunivipladin, $ls_denunivipladin,$ls_enviorec, $ld_fecleypen,$ls_codcausa, $ls_dencausa, $ls_situacion, $ld_fecsitu;
		global $li_talcamper, $li_talpanper, $li_talzapper, $li_anoservprecont, $li_anoservprefijo, $ls_cauegrper2, $ls_codorg, $ls_desorg,$li_porcajahoper, $ls_codger, $ls_denger,$li_anoperobr,$ls_carantper;
		global $ls_tipperrif, $la_datos, $ls_seljur, $ls_selgub, $ls_selven, $ls_selext, $ls_numpririf, $ls_numterrif;  
		
		$ls_codper=$_POST["txtcodper"];
		$ls_estper=$_POST["txtestper"];
		$ls_cedper=$_POST["txtcedper"];
		$ls_nomper=$_POST["txtnomper"];
		$ls_apeper=$_POST["txtapeper"];
		$ls_dirper=$_POST["txtdirper"];
		$ld_fecnacper=$_POST["txtfecnacper"];
		$ls_edocivper=$_POST["cmbedocivper"];
		$ls_telhabper=$_POST["txttelhabper"];
		$ls_telmovper=$_POST["txttelmovper"];
		$ls_sexper=$_POST["cmbsexper"];
		$ls_nacper=$_POST["cmbnacper"];
		$li_estaper=$_POST["txtestaper"];
		$li_pesper=$_POST["txtpesper"];
		$ls_codpro=$_POST["txtcodpro"];
		$ls_nivacaper=$_POST["cmbnivacaper"];
		$ls_catper=$_POST["txtcatper"];
		$ls_cajahoper=$io_fun_nomina->uf_obtenervalor("chkcajahoper","0");
		$ls_hcmper=$io_fun_nomina->uf_obtenervalor("chkhcmper","0");
		$li_numhijper=$_POST["txtnumhijper"];
		$ls_contraper=$_POST["cmbcontraper"];
		$li_tipvivper=$_POST["cmbtipvivper"];
		$ls_tenvivper=$_POST["txttenvivper"];
		$li_monpagvivper=$_POST["txtmonpagvivper"];
		$ls_cuecajahoper=$_POST["txtcuecajahoper"];
		$ls_cuelphper=$_POST["txtcuelphper"];;
		$ls_cuefidper=$_POST["txtcuefidper"];
		$ld_fecingadmpubper=$_POST["txtfecingadmpubper"];
		$ld_fecingper=$_POST["txtfecingper"];
		$li_anoservpreper=$_POST["txtanoservpreper"];
		$ld_fecegrper=$_POST["txtfecegrper"];
		$ls_codpai=$_POST["txtcodpai"];
		$ls_codest=$_POST["txtcodest"];
		$ls_codmun=$_POST["txtcodmun"];
		$ls_codpar=$_POST["txtcodpar"];
		$ls_obsper=$_POST["txtobsper"];		
		$ls_obsegrper=$_POST["txtobsegrper"];
		$ls_cedbenper=$_POST["txtcedbenper"];
		$ls_despro=$_POST["txtdespro"];
		$ls_despai=$_POST["txtdespai"];
		$ls_desest=$_POST["txtdesest"];
		$ls_desmun=$_POST["txtdesmun"];
		$ls_despar=$_POST["txtdespar"];
		$ls_coreleper=$_POST["txtcoreleper"];
		$ls_cenmedper=$_POST["cmbcenmedper"];
		$ls_turper=$_POST["cmbturper"];
		$ls_horper=$_POST["txthorper"];
		$ls_tipsanper=$_POST["txttipsanper"];
		$ls_codcom=$_POST["txtcodcom"];
		$ls_descom=$_POST["txtdescom"];
		$ls_codran=$_POST["txtcodran"];
		$ls_desran=$_POST["txtdesran"];
		$ls_numexpper=$_POST["txtnumexpper"];
		$ls_codpainac=$_POST["txtcodpainac"];
		$ls_codestnac=$_POST["txtcodestnac"];
		$ls_despainac=$_POST["txtdespainac"];
		$ls_desestnac=$_POST["txtdesestnac"];
		$ls_codtippersss=trim($_POST["txtcodtippersss"]);
		$ls_dentippersss=$_POST["txtdestippersss"];
		$ld_fecreingper=$_POST["txtfecreingper"];
		$ld_fecjubper=$_POST["txtfecjubper"];
		$ls_codunivipladin=$_POST["txtcodunivipladin"];
		$ls_denunivipladin=$_POST["txtdenunivipladin"];
		$ls_enviorec=$_POST["cmbenviorec"];		
		$ld_fecleypen=$_POST["txtfecleypen"];
		$ls_codcausa=$_POST["txtcodcausa"];
		$ls_dencausa=$_POST["txtdencausa"];
		$ls_situacion=$_POST["cmbsituacion"];
		if(empty($ls_codtippersss))
		{
			$ls_codtippersss='-------';
		}
		$ld_fecsitu=$_POST["txtfecsitu"];
		$li_talcamper=$_POST["txttalcamper"];
		$li_talpanper=$_POST["txttalpanper"];
		$li_talzapper=$_POST["txttalzapper"];
		$li_anoservprecont=$_POST["txtanoservprecont"];
		$li_anoservprefijo=$_POST["txtanoservprefijo"];		
		$ls_cauegrper=$_POST["txtcauegrper2"];
		$ls_codorg=$_POST["txtcodorg"];
		$ls_desorg=$_POST["txtdesorg"];
		$li_porcajahoper=$_POST["txtporcajahoper"];
		$ls_codger=$_POST["txtcodger"];
		$ls_denger=$_POST["txtdenger"];
		$li_anoperobr=$_POST["txtanoperobr"];
		$ls_carantper=$_POST["txtcarantper"];
		if  (array_key_exists("cmbtipperrif",$_POST))
		{
			$ls_tipperrif          = $_POST["cmbtipperrif"];
			$la_datos["tipperrif"] = $ls_tipperrif;
		}
		else
		{
		   $ls_tipperrif = "J";	  
		}
		   $ls_seljur = $ls_selgub = $ls_selven = $ls_selext = "";
		if ($ls_tipperrif=='J')
		{
		   $ls_seljur = "selected";
		}	
		elseif($ls_tipperrif=='G')
		{
		   $ls_selgub = "selected";
		}
		elseif($ls_tipperrif=='V')
		{
		   $ls_selven = "selected";
		}
		else
		{
		   $ls_selext = "selected";
		}
		if  (array_key_exists("txtnumpririf",$_POST))
		{
		   $ls_numpririf    = $_POST["txtnumpririf"];
		   $la_datos["numpririf"] = $ls_numpririf;
		}
		else
		{
		   $ls_numpririf = ""; 
		}
		if  (array_key_exists("txtnumterrif",$_POST))
		{
		   $ls_numterrif    = $_POST["txtnumterrif"];
		   $la_datos["numterrif"] = $ls_numterrif;
		}
		else
		{
		   $ls_numterrif = ""; 
		}
		
   }
   //--------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
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
<title >Definici&oacute;n de Personal</title>
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
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo2 {color: #6699CC}
-->
</style>
</head>
<body>
<?php 
	require_once("sigesp_snorh_c_personal.php");
	$io_personal=new sigesp_snorh_c_personal();
	require_once("../shared/class_folder/class_fecha.php");
	$io_fecha=new class_fecha();
	require_once("../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();
	
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "GUARDAR":
			uf_load_variables();
			$lb_valido=true;
			$ls_nomfot=$HTTP_POST_FILES['txtfotper']['name']; 
			if ($ls_nomfot!="")
			{
				$ls_nomfot=$ls_cedper.substr($ls_nomfot,strrpos($ls_nomfot,"."));
			}
			$ls_tipfot=$HTTP_POST_FILES['txtfotper']['type']; 
			$ls_tamfot=$HTTP_POST_FILES['txtfotper']['size']; 
			$ls_nomtemfot=$HTTP_POST_FILES['txtfotper']['tmp_name'];
			$ls_nomfot=$io_personal->uf_upload($ls_nomfot,$ls_tipfot,$ls_tamfot,$ls_nomtemfot);
			
			
			if (($ld_fecegrper!='1900-01-01')&&($ld_fecegrper!='1900/01/01'))
			{
				$ls_fecperi=$io_personal->uf_buscar_fecha_periodo($ls_codper);
				if ($ls_fecperi!="")
				{ 
					$valido1=$io_fecha->uf_comparar_fecha($ls_fecperi,$ld_fecegrper); 
					if (!$valido1)
					{
						$lb_valido=false;
						$ls_fecperi=$io_funciones->uf_convertirfecmostrar($ls_fecperi);
						$io_personal->io_mensajes->message("La Fecha de Egreso no puede ser menor al $ls_fecperi que es el utlimo Calculo de Nómina para el Personal con Código $ls_codper.");
					}						
				}
			}
			
			if ($lb_valido)
			{
			    $lb_valido=$io_personal->uf_guardar($ls_existe,$ls_codper,$ls_cedper,$ls_nomper,$ls_apeper,$ls_dirper,
				                                    $ld_fecnacper,$ls_edocivper, $ls_telhabper,$ls_telmovper,$ls_sexper,
													$li_estaper,$li_pesper,$ls_codpro,$ls_nivacaper,$ls_catper,
													$ls_cajahoper,$li_numhijper,$ls_contraper,$li_tipvivper,$ls_tenvivper,
													$li_monpagvivper,$ls_cuecajahoper, $ls_cuelphper,$ls_cuefidper,
													$ld_fecingadmpubper,$ld_fecingper,$li_anoservpreper,$ld_fecegrper,$ls_codpai,
													$ls_codest,$ls_codmun, $ls_codpar,$ls_cauegrper,$ls_obsegrper,$ls_cedbenper,
													$ls_obsper,$ls_nomfot, $ls_nacper,$ls_coreleper,$ls_cenmedper,$ls_turper,
													$ls_horper,$ls_hcmper,$ls_tipsanper,$ls_codcom,$ls_codran,
													$ls_numexpper,$ls_codpainac,$ls_codestnac,$ls_codtippersss,$ld_fecreingper,
													$ld_fecjubper,$ls_codunivipladin,$ls_enviorec,$ld_fecleypen,$ls_codcausa,
													$ls_situacion, $ld_fecsitu, $li_talcamper, $li_talpanper, $li_talzapper,
													$li_anoservprecont,	$li_anoservprefijo, $ls_codorg,$li_porcajahoper,
													$ls_codger,$li_anoperobr,$ls_carantper, $la_datos, $la_seguridad);	
			}										 
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
			}
			else
			{
				$io_fun_nomina->uf_seleccionarcombo("S-C-D-V-K",$ls_edocivper,$la_edocivper,5);
				$io_fun_nomina->uf_seleccionarcombo("F-M",$ls_sexper,$la_sexper,2);
				$io_fun_nomina->uf_seleccionarcombo("V-E",$ls_nacper,$la_nacper,2);
				$io_fun_nomina->uf_seleccionarcombo("0-1-2-3-4-5-6-7",$ls_nivacaper,$la_nivacaper,8);
				$io_fun_nomina->uf_seleccionarcombo("0-1",$ls_contraper,$la_contraper,2);
				$io_fun_nomina->uf_seleccionarcombo("0-1-2-3",$li_tipvivper,$la_tipvivper,4);				
				$io_fun_nomina->uf_seleccionarcombo("0-1",$ls_turper,$la_turper,2);
				$io_fun_nomina->uf_seleccionarcombo("1-2",$ls_enviorec,$la_enviorec,2);
				$io_fun_nomina->uf_seleccionarcombo("1-2-3-4-5",$ls_situacion,$la_situacion,5);
				$ls_cajahoper="";
				if($ls_cajahoper=="1")
				{
					$ls_cajahoper="checked";
				}
				$ls_hcmper="";
				if($ls_hcmper=="1")	
				{
					$ls_hcmper="checked";
				}	
			}
			break;

		case "BUSCAR":
			$ls_codper=$_GET["codper"];
			$lb_valido=$io_personal->uf_load_personal($ls_codper,$ls_existe,$ls_cedper,$ls_nomper,$ls_apeper,$ls_dirper,
													  $ld_fecnacper,$ls_edocivper, $ls_telhabper,$ls_telmovper,$ls_sexper,
													  $li_estaper,$li_pesper,$ls_codpro,$ls_nivacaper,$ls_catper,
											          $ls_cajahoper,$li_numhijper,$ls_contraper,$li_tipvivper,$ls_tenvivper,
													  $li_monpagvivper,$ls_cuecajahoper, $ls_cuelphper,$ls_cuefidper,
													  $ld_fecingadmpubper,$ld_fecingper,$li_anoservpreper,$ld_fecegrper,
													  $ls_codpai, $ls_codest,$ls_codmun,$ls_codpar,$ls_cauegrper,$ls_obsegrper,
													  $ls_cedbenper,$ls_obsper,$ls_estper, $ls_despro,$ls_despai,$ls_desest,
													  $ls_desmun,$ls_despar,$ls_nomfot,$ls_nacper,$ls_coreleper,$ls_cenmedper,
													  $ls_turper,$ls_horper,$ls_hcmper,$ls_tipsanper,$ls_codcom,$ls_codran,
													  $ls_descom,$ls_desran,$ls_numexpper,
													  $ls_codpainac,$ls_codestnac,$ls_despainac,$ls_desestnac,$ld_fecreingper,
													  $ld_fecjubper,$ls_codunivipladin,$ls_denunivipladin,$ls_enviorec, 
													  $ld_fecleypen,$ls_codcausa,$ls_situacion, $ld_fecsitu,
													  $li_talcamper, $li_talpanper, $li_talzapper,$li_anoservprecont,
													  $li_anoservprefijo, $ls_cauegrper2, $ls_codtippersss,$ls_dentippersss,
													  $ls_codorg,$ls_desorg,$li_porcajahoper, $ls_codger, $ls_denger,
													  $li_anoperobr,$ls_carantper,$ls_tipperrif,$ls_numpririf,$ls_numterrif);					  
			$io_fun_nomina->uf_seleccionarcombo("S-C-D-V-K",$ls_edocivper,$la_edocivper,5);
			$io_fun_nomina->uf_seleccionarcombo("F-M",$ls_sexper,$la_sexper,2);
			$io_fun_nomina->uf_seleccionarcombo("V-E",$ls_nacper,$la_nacper,2);
			$io_fun_nomina->uf_seleccionarcombo("0-1-2-3-4-5-6-7",$ls_nivacaper,$la_nivacaper,8);
			$io_fun_nomina->uf_seleccionarcombo("0-1",$ls_contraper,$la_contraper,2);
			$io_fun_nomina->uf_seleccionarcombo("0-1-2-3",$li_tipvivper,$la_tipvivper,4);
			//$io_fun_nomina->uf_seleccionarcombo("N-D-P-R-T-J-F-P",$ls_cauegrper,$la_cauegrper,8);
			$io_fun_nomina->uf_seleccionarcombo("0-1",$ls_turper,$la_turper,2);
			$io_fun_nomina->uf_seleccionarcombo("1-2",$ls_enviorec,$la_enviorec,2);
			$io_fun_nomina->uf_seleccionarcombo("1-2-3-4-5",$ls_situacion,$la_situacion,5);
			$io_fun_nomina->uf_seleccionarcombo("J-G-V-E",$ls_tipperrif,$la_tipperrif,4);
			$ls_familiar="";
			$ls_estudio="";
			$ls_trabajo="";
			$ls_impuesto="";
			$ls_vacacion="";			
			$ls_permiso="";
			$ls_beneficiario="";
			if($ls_cajahoper=="1")
			{
				$ls_cajahoper="checked";
			}
			else
			{
				$ls_cajahoper="";
			}
			if($ls_hcmper=="1")	
			{
				$ls_hcmper="checked";
			}	
			else
			{
				$ls_hcmper="";
			}			
			break;
			
	}
	$io_personal->uf_destructor();
	unset($io_personal);
?>
<a name="top"></a>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo2">Sistema de Nómina</td>
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
    <td height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="22"><div align="center"></div></td>
    <td class="toolbar" width="28"><div align="center"></div></td>
    <td class="toolbar" width="528">&nbsp;</td>
  </tr>
</table>

<p>&nbsp;</p>
<form action="" method="post" enctype="multipart/form-data" name="form1">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td width="778" colspan="2">
      <div align="center">
      <p>&nbsp;</p>
      <table width="736" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="3" class="titulo-ventana">Definici&oacute;n de Personal </td>
        </tr>
        <tr>
          <td height="20" colspan="3" class="titulo-celdanew">Informaci&oacute;n</td>
        </tr>
        <tr class="formato-blanco">
          <td height="20" colspan="3"><div align="center">Los Campos en (*) son necesarios para la Incluir el personal</div></td>
          </tr>
        <tr>
          <td width="212" height="22"><div align="right">(*) C&oacute;digo</div></td>
          <td width="322">
                <div align="left">
                  <input name="txtcodper" type="text" id="txtcodper" size="13" maxlength="10" value="<?php print $ls_codper;?>"  onKeyUp="javascript: ue_validar(this);" onBlur="javascript: ue_rellenarcampo(this,10);"> 
                  <input name="txtestper" type="text" class="sin-borde2" id="txtestper" style="text-align: center" value="<?php print $ls_estper;?>" readonly>           
              </div></td>
          <td width="194" rowspan="7"><img id="foto" name="foto" src="fotospersonal/<?php print $ls_nomfot; ?>" width="150" height="200"></td>
        </tr>
        <tr>
          <td height="22"><div align="right">(*) C&eacute;dula</div></td>
          <td>
                <div align="left">
                  <input name="txtcedper" type="text" id="txtcedper" value="<?php print $ls_cedper;?>" size="13" maxlength="10"  onKeyUp="javascript: ue_validarnumero(this);">
                </div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">(*) Nombre </div></td>
          <td>
                <div align="left">
                  <input name="txtnomper" type="text" id="txtnomper" size="63" maxlength="60" value="<?php print $ls_nomper;?>" onKeyUp="javascript: ue_validarcomillas(this);">
                </div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">(*) Apellido</div></td>
          <td>
                <div align="left">
                  <input name="txtapeper" type="text" id="txtapeper" value="<?php print $ls_apeper;?>" size="63" maxlength="60" onKeyUp="javascript: ue_validarcomillas(this);">
                </div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">(*) Direcci&oacute;n</div></td>
          <td>
                <div align="left">
                  <input name="txtdirper" type="text" id="txtdirper" value="<?php print $ls_dirper;?>" size="63" maxlength="250" onKeyUp="javascript: ue_validarcomillas(this);">
                </div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">(*) Fecha de Nacimiento</div></td>
          <td>
                <div align="left">
                  <input name="txtfecnacper" type="text" id="txtfecnacper" value="<?php print $ld_fecnacper;?>" size="15" maxlength="10" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" datepicker="true">
                </div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">(*) Estado Civil</div></td>
          <td><div align="left">
            <select name="cmbedocivper" id="cmbedocivper">
              <option value="" selected>--Seleccione Uno--</option>
              <option value="S" <?php print $la_edocivper[0];?>>Soltero</option>
              <option value="C" <?php print $la_edocivper[1];?>>Casado</option>
              <option value="D" <?php print $la_edocivper[2];?>>Divorciado</option>
              <option value="V" <?php print $la_edocivper[3];?>>Viudo</option>
              <option value="K" <?php print $la_edocivper[4];?>>Concubino</option>
            </select>
          </div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">Tel&eacute;fono de Habitaci&oacute;n</div></td>
          <td colspan="2"><div align="left">
            <input name="txttelhabper" type="text" id="txttelhabper" value="<?php print $ls_telhabper;?>" size="18" maxlength="15" onKeyUp="javascript: ue_validartelefono(this);">            
          </div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">Tel&eacute;fono M&oacute;vil </div></td>
          <td colspan="2"><div align="left">
            <input name="txttelmovper" type="text" id="txttelmovper" value="<?php print $ls_telmovper;?>" size="18" maxlength="15" onKeyUp="javascript: ue_validartelefono(this);">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Correo Electr&oacute;nico </div></td>
          <td colspan="2"><div align="left">
            <input name="txtcoreleper" type="text" id="txtcoreleper" value="<?php print $ls_coreleper;?>" size="80" maxlength="100">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">(*) Nacionalidad</div></td>
          <td colspan="2"><div align="left">
            <select name="cmbnacper" id="cmbnacper">
                <option value="" selected>--Seleccione Uno--</option>
                <option value="V" <?php print $la_nacper[0];?>>Venezolano</option>
                <option value="E" <?php print $la_nacper[1];?>>Extranjero</option>
              </select>
          </div></td>
        </tr>
		<!-- Agregado para providencia seniat 300!-->
		<tr>
		  <td height="22" align="right"> R.I.F</td>
		  <td height="22" colspan="4"><select name="cmbtipperrif" id="cmbtipperrif" tabindex="7" onChange="document.form1.txtnumpririf.focus();">
			<option value="J" <?php echo $ls_seljur ?>>J </option>
			<option value="G" <?php echo $ls_selgub ?>>G </option>
			<option value="V" <?php echo $ls_selven ?>>V </option>
			<option value="E" <?php echo $ls_selext ?>>E </option>
		  </select> 
			<span class="Estilo2">-</span> 
			<input name="txtnumpririf" type="text" id="txtnumpririf" style="text-align:center" tabindex="8" onBlur="javascript: ue_rellenarcampo(this,8)" onKeyPress="return keyRestrict(event,'1234567890');" onKeyUp="javascript:uf_set_focus();" value="<?php echo $ls_numpririf ?>" size="10" maxlength="8"> 
			<strong>-</strong>
			<label>
			<input name="txtnumterrif" type="text" id="txtnumterrif" style="text-align:center" tabindex="9" onKeyPress="return keyRestrict(event,'1234567890');" value="<?php echo $ls_numterrif ?>" size="2" maxlength="1">
			</label></td>
		</tr>
        <!-- Agregado para providencia seniat 300!-->
		<tr>
          <td height="22"><div align="right">(*) G&eacute;nero</div></td>
          <td colspan="2">
                <div align="left">
                  <select name="cmbsexper" id="cmbsexper">
 			        <option value="" selected>--Seleccione Uno--</option>
                    <option value="F" <?php print $la_sexper[0];?>>Femenino</option>
                    <option value="M" <?php print $la_sexper[1];?>>Masculino</option>
                  </select>
                </div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">Estatura</div></td>
          <td colspan="2">
                <div align="left">
                  <input name="txtestaper" type="text" id="txtestaper" value="<?php print $li_estaper;?>" size="8" maxlength="5" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))">            
                </div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">Peso</div></td>
          <td colspan="2"><div align="left">
            <input name="txtpesper" type="text" id="txtpesper" value="<?php print $li_pesper;?>" size="8" maxlength="5" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))">
          </div></td>
        </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td><fieldset><legend>Tallas</legend> 
              	<label>
              	<div align="left">Camisa  
              	  <input name="txttalcamper" type="text" id="txttalcamper" value="<?php print $li_talcamper;?>"
				                                              size="8" maxlength="5" style="text-align:right">
              	</div>
              	</label>
				
				
              	<label>
              	<br>
              	<div align="left">Pantal&oacute;n
              	  <input name="txttalpanper" type="text" id="txttalpanper" value="<?php print $li_talpanper;?>"
				                                              size="8" maxlength="5" style="text-align:right">
              	</div></label>
				
				
				
			  	<label>
			  	<br>
			  	<div align="left">Zapatos  
			  	  <input name="txttalzapper" type="text" id="txttalzapper" value="<?php print $li_talzapper;?>"
				                                              size="8" maxlength="5" style="text-align:right"
															  onKeyPress="return(ue_formatonumero(this,'.',',',event))">
			  	</div></label>
			  </fieldset> </td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">(*) Profesi&oacute;n</div></td>
          <td colspan="2">
                <div align="left">
                  <input name="txtcodpro" type="text" id="txtcodpro" value="<?php print $ls_codpro;?>" size="6" maxlength="3" readonly> 
                    <a href="javascript: ue_buscarprofesion();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a> 
                  <input name="txtdespro" type="text" class="sin-borde" id="txtdespro" value="<?php print $ls_despro;?>" size="60" maxlength="120" readonly>
                </div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">(*) Nivel Acad&eacute;mico </div></td>
          <td colspan="2">
                <div align="left">
                  <select name="cmbnivacaper" id="cmbnivacaper">
  		            <option value="" selected>--Seleccione Uno--</option>
                    <option value="0" <?php print $la_nivacaper[0];?>>Ninguno</option>
                    <option value="1" <?php print $la_nivacaper[1];?>>Primaria</option>
                    <option value="2" <?php print $la_nivacaper[2];?>>Bachiller</option>
                    <option value="3" <?php print $la_nivacaper[3];?>>T&eacute;cnico Superior</option>
                    <option value="4" <?php print $la_nivacaper[4];?>>Universitario</option>
                    <option value="5" <?php print $la_nivacaper[5];?>>Maestria</option>
                    <option value="6" <?php print $la_nivacaper[6];?>>PostGrado</option>
                    <option value="7" <?php print $la_nivacaper[7];?>>Doctorado</option>
                  </select>
                </div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">(*) Pais</div></td>
          <td colspan="2">
              <div align="left">
                <input name="txtcodpai" type="text" id="txtcodpai" value="<?php print $ls_codpai;?>" size="6" maxlength="3" readonly>
                <a href="javascript: ue_buscarpais();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a> 
                <input name="txtdespai" type="text" class="sin-borde" id="txtdespai" value="<?php print $ls_despai;?>" size="60" maxlength="50" readonly>       
                </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">(*) Estado</div></td>
          <td colspan="2" >
              <div align="left">
                <input name="txtcodest" type="text" id="txcodest" value="<?php print $ls_codest;?>" size="6" maxlength="3" readonly>
                <a href="javascript: ue_buscarestado();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
                <input name="txtdesest" type="text" class="sin-borde" id="txtdesest" value="<?php print $ls_desest;?>" size="60" maxlength="50" readonly>              
                </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">(*) Municipio</div></td>
          <td colspan="2"><div align="left">
            <input name="txtcodmun" type="text" id="txtcodmun" value="<?php print $ls_codmun;?>" size="6" maxlength="3" readonly>
              <a href="javascript: ue_buscarmunicipio();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
              <input name="txtdesmun" type="text" class="sin-borde" id="txtdesmun" value="<?php print $ls_desmun;?>" size="60" maxlength="50" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">(*) Parroquia</div></td>
          <td colspan="2"><div align="left">
            <input name="txtcodpar" type="text" id="txtcodpar" value="<?php print $ls_codpar;?>" size="6" maxlength="3" readonly>
              <a href="javascript: ue_buscarparroquia();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
              <input name="txtdespar" type="text" class="sin-borde" id="txtdespar" value="<?php print $ls_despar;?>" size="60" maxlength="50" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Componente Militar </div></td>
          <td colspan="2"><label>
            <input name="txtcodcom" type="text" id="txtcodcom" value="<?php print $ls_codcom;?>" size="12" maxlength="10" readonly>
            <a href="javascript: ue_buscarcomponente();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdescom" type="text" class="sin-borde" id="txtdescom" value="<?php print $ls_descom;?>" size="60" maxlength="50" readonly>
          </label></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Rango Militar </div></td>
          <td colspan="2"><label>
            <input name="txtcodran" type="text" id="txtcodran" value="<?php print $ls_codran;?>" size="12" maxlength="10" readonly>
            <a href="javascript: ue_buscarrango();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdesran" type="text" class="sin-borde" id="txtdesran" value="<?php print $ls_desran;?>" size="60" maxlength="50" readonly>
          </label></td>
        </tr>
        <tr>
          <td height="22"><div align="right">C&eacute;dula del Beneficiario </div></td>
          <td colspan="2"><div align="left">
            <input name="txtcedbenper" type="text" id="txtcedbenper" value="<?php print $ls_cedbenper;?>" size="11" maxlength="8" onKeyUp="javascript: ue_validarnumero(this);">            
          </div></td>
          </tr>
        <tr>
          <td height="22"><div align="right"> (*) N&uacute;mero de Hijos</div></td>
          <td colspan="2"><div align="left">
            <input name="txtnumhijper" type="text" id="txtnumhijper" value="<?php print $li_numhijper;?>" size="5" maxlength="2" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Conyuge Trabaja </div></td>
          <td colspan="2">
                <div align="left">
                  <select name="cmbcontraper" id="cmbcontraper">
  			        <option value="" selected>--Seleccione Uno--</option>
                    <option value="0" <?php print $la_contraper[0];?>>Si</option>
                    <option value="1" <?php print $la_contraper[1];?>>No</option>
                  </select>
                </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Observaci&oacute;n</div></td>
          <td colspan="2">
            <div align="left">
              <textarea name="txtobsper" cols="80" rows="3" id="txtobsper" onKeyUp="javascript: ue_validarcomillas(this);"> <?php print $ls_obsper;?></textarea>
            </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Foto</div></td>
          <td colspan="2">
              <div align="left">
                <input name="txtfotper" type="file" id="txtfotper" size="50" maxlength="200">
              </div></td>
        </tr>
        <tr>
          <td height="20"><div align="right">Centro M&eacute;dico IVSS </div></td>
          <td height="20" colspan="2"><div align="left">
            <select name="cmbcenmedper" id="cmbcenmedper">
				  <option value="" selected>--Seleccione Uno--</option>
				  <option value="A01">AV PPAL SAN JOSE MARACAY</option>
				  <option value="A02">C AYACUCHO STA ROSA MARACAY </option>
				  <option value="A04">AV UNIVERSIDAD EL LIMON</option>
				  <option value="A10">URB LAS MERCEDES LA VICTORIA</option>
				  <option value="A15">CTRO CAGUA ULT TRANS CORINSA</option>
				  <option value="A20">HOSP JOSE VARGAS CALLE OVALLES</option>
				  <option value="B01">PASEO MENESES CDAD BOLIVAR</option>
				  <option value="B02">PASEO GASPAN MDO PE CD BOLIVAR</option>
				  <option value="B10">URB GUAIPARO SAN FELIX</option>
				  <option value="B11">VIA RIO CLARO SAN FELIX</option>
				  <option value="B12">SECTOR UD-14S SAN FELIX </option>
				  <option value="B20">URB LOS OLIVOS PTO ORDAZ</option>
				  <option value="B22">UNARE</option>
				  <option value="B30">CALLE CUYUHI UPATA</option>
				  <option value="B40">FRENTE ALTAVISTA SUR PTO ORDAZ</option>
				  <option value="B60">FINAL CALLE GRATEU - EL CALLAO</option>
				  <option value="CO1">AV MONTES DE OCA VALENCIA</option>
				  <option value="C03">AV PRINCIPAL NAGUANAGUA</option>
				  <option value="C05">AV L ALVARADO LA CANDELARIA</option>
				  <option value="C10">CARRETERA YAGUA GUACARA</option>
				  <option value="C11">C PPAL BARRIO GALLARDO S JOAQUIH</option>
				  <option value="C12">C PROCER B MCAL SUCRE MARIARA</option>
				  <option value="C13">AV 6 URB POCATERRA TOCUYITO</option>
				  <option value="C14">UR PARAPARAL LOS GUAYOS VALENCIA</option>
				  <option value="C20">FINAL CALLE PLAZA PTO CABELLO</option>
				  <option value="C21">URB STA CRUZ Z IND PTO CABELLO</option>
				  <option value="C22">AV PPA LA SORPRESA PTO CABELLO</option>
				  <option value="C30">CARRETERA NACIONAL MORON</option>
				  <option value="C40">ALTOS COLOHIA PSIQUI NAGUANAGUA</option>
				  <option value="C50">AV G MOTORS Z I SUR II VALENCIA</option>
				  <option value="D02">AV PRINCIPAL EL CEMENTERIO</option>
				  <option value="D03">2DA TRANSVERSAL GUAICAIPURO</option>
				  <option value="D04">AV SUCRE CATIA</option>
				  <option value="D06">LOS JARDINES DEL VALLE</option>
				  <option value="D07">AV INTERCOMUNAL ANTIMANO</option>
				  <option value="D08">AV LOS SAMANES EL PARAISO</option>
				  <option value="D09">AV PPAL EL CUARTEL CATIA</option>
				  <option value="D10">AV M F TOVAR SAN BERNARDINO</option>
				  <option value="D12">CENTRO AMB UD5 LA HACIENDA</option>
				  <option value="D13">EDF MUNICIPAL MACARAO</option>
				  <option value="D50">AV SOUBLETTE LA GUAIRA</option>
				  <option value="D51">CALLE PRINCIPAL CARABALLEDA</option>
				  <option value="D52">CALLE PRINCIPAL CARAYACA</option>
				  <option value="D53">CALL PPAL LOS MANGOS NAIGUATA</option>
				  <option value="D54">CIUDAD VACACIONAL LOS CARACAS</option>
				  <option value="D60">CALLE LEBRUN PETARE</option>
				  <option value="D70">CALLE JOSE FELIX RIVAS CHACAO</option>
				  <option value="D80">C GONZALES RINCONES-LA TRINIDAD</option>
				  <option value="E01">AV 5 DE JULIO BARCELONA</option>
				  <option value="E10">CAMPO GUARAGUAO PTO LA CRUZ</option>
				  <option value="E11">BARRIO GUANIRE PTO LA CRUZ</option>
				  <option value="E20">CARRETERA VEA EL TIGRE</option>
				  <option value="E30">AV INTER SEC GARZA PTO LA CRUZ</option>
				  <option value="E40">AV VENEZUELA - ANACO</option>
				  <option value="F01">CALLE FEDERACION CORO</option>
				  <option value="F10">C RAFAEL GONZALEZ PTO FIJO</option>
				  <option value="F20">URB JUDIBANA AMUAY</option>
				  <option value="F21">AV TACHIRA AV INTERCOM LAGOVEN</option>
				  <option value="F30">CAMPO SHELL HOSPITAL CARDON</option>
				  <option value="GOl">SECTOR SANTA ISABEL SAN JUAN</option>
				  <option value="G03">URB LA MISION CALABOZO</option>
				  <option value="G40">CALLE ATARRAYA - V DE LA PASCUA</option>
				  <option value="HOl">CARRET A BIRUACA-SAN FERNANDO</option>
				  <option value="JOl">AV CARABOBO SAN CARLOS</option>
				  <option value="J30">CARRETERA NACIONAL-TINAQUILLO</option>
				  <option value="K01">U PROCERES BRNAS-TURINO FE y A</option>
				  <option value="LOl">AV 13 ENTRE CALLS 49 Y 50 BQTO</option>
				  <option value="L10">CARRl C 4Y5 BARRIO UNION BQTO</option>
				  <option value="L20">PROL A L SALLE F SISAL II BQTO</option>
				  <option value="L30">CALLE CURIRAGUA - CARORA</option>
				  <option value="M01">AV BERMUDEZ LOS TEQUES</option>
				  <option value="M10">URB RUIZ PIMEDA GUARENAS</option>
				  <option value="M15">AV PERIMETRAL CUA</option>
				  <option value="M20">U LUIS TOVAR CARR STA TERESA TUY</option>
				  <option value="NOl">AV 4 DE MAYO PORLAMAR</option>
				  <option value="NO5">U VILLA ROSA LADO COL PORLAMAR</option>
				  <option value="/01">CARRET NAC VIA LA CRUZ MATURIN</option>
				  <option value="POl">AVENIDA 21 - GUANARE</option>
				  <option value="P10">URB MAMANICO - ACARIGUA</option>
				  <option value="ROl">FIN AV AMERICAS CERCA TERMINAL</option>
				  <option value="SOl">CALLE SUCRE CUMANA</option>
				  <option value="S20">CALLE CARABOO - CARUPANO</option>
				  <option value="TOl">CALLE 5 ESQ CRR 8 SAN CRISTOBAL</option>
				  <option value="T10">CALLE 4 PALMIRA</option>
				  <option value="T20">ZONA INDUSTRIAL LA FRIA</option>
				  <option value="T30">URB STA TERESA SAN CRISTOBAL</option>
				  <option value="UOl">CALLE NEGRO PRIMERO TUCUPITA</option>
				  <option value="WOl">AV RIO NEGRO PTO AYACUCHO</option>
				  <option value="XOl">AV 19 DE ABRIL TRUJILLO</option>
				  <option value="X10">FINAL CALLE 10 VALERA</option>
				  <option value="Xll">URB LAS BEATRIZ VALERA</option>
				  <option value="X20">EDIF CONTINENTAL C 10 VALERA</option>
				  <option value="YOl">AVDA YARACUY SAN FELIPE</option>
				  <option value="Y40">CARRETERA NACIONAL – CHIVACOA</option>
				  <option value="Z0l">AV GUAJIRA URB SAN JACINTO</option>
				  <option value="Z02">AV 7 ESQ CALLE VARGAS VERITAS</option>
				  <option value="Z03">CALLE 100 SABANETA LARGA </option>
				  <option value="Z04">CAMPO PARAISO LA CONCEPCION</option>
				  <option value="ZO5">AV 4 NRO 71-37 - BELLA VISTA</option>
				  <option value="Z07">ENTRADA DE STA CRUZ DE MARA</option>
				  <option value="Z08">CTRO STA RITA CALLE LA PLANTA</option>
				  <option value="Z09">AMB CABIMAS AV 32 LOS LAURELES</option>
				  <option value="ZlO">AMB CIUDAD OJEDA-C STA MONICA</option>
				  <option value="Z20">CTRO AUX MONS GODOY A 5 D JULI</option>
				  <option value="Z21">AV F ARM CANCHANCHA DELICIAS</option>
				  <option value="Z22">HOSP NORIEGA FRENTE AL LGO MBO</option>
				  <option value="Z30">AV BOLIVAR - STA BARBARA ZULIA</option>
            </select>
          </div></td>
          </tr>
        <tr>
          <td height="20"><div align="right">Turno</div></td>
          <td height="20" colspan="2">
                <div align="left">
                  <select name="cmbturper" id="cmbturper">
  			        <option value="" selected>--Seleccione Uno--</option>
                    <option value="0" <?php print $la_turper[0];?>>Diurno</option>
                    <option value="1" <?php print $la_turper[1];?>>Nocturno</option>
					<option value="2" <?php print $la_turper[1];?>>Mixto</option>
                  </select>
                </div></td>		  
        </tr>
        <tr>
          <td height="20"><div align="right">Horario</div></td>
          <td height="20" colspan="2">
				<div align="left">
                  <input name="txthorper" type="text" id="txthorper" value="<?php print $ls_horper;?>" size="48" maxlength="45" onKeyUp="javascript: ue_validarcomillas(this);">
                </div>		  </td>
        </tr>
        <tr>
          <td height="20"><div align="right">HCM (Poliza de Maternidad) </div></td>
          <td height="20" colspan="2"><input name="chkhcmper" type="checkbox" class="sin-borde" id="chkhcmper" onChange="javascript:ue_hcm();" value="1" <?php print $ls_hcmper;?>></td>
        </tr>
        <tr>
          <td height="20"><div align="right">Tipo de Sangre </div></td>
          <td height="20" colspan="2"><div align="left">
            <label></label>
            <label>
            <input name="txttipsanper" type="text" id="txttipsanper" value="<?php print $ls_tipsanper;?>" size="15" maxlength="10" onKeyUp="javascript: ue_validarcomillas(this);">
            </label>
</div></td>
        </tr>
        <tr>
          <td height="20"><div align="right">N&uacute;mero de Expediente </div></td>
          <td height="20" colspan="2"><label>
            <input name="txtnumexpper" type="text" id="txtnumexpper" value="<?php print $ls_numexpper;?>" size="23" maxlength="20" onKeyUp="javascript: ue_validarcomillas(this);">
          </label></td>
        </tr>
        <tr>
        <td height="22"><div align="right">Cargo Original</div></td>
        <td colspan="3"><div align="left">
            <input name="txtcarantper" type="text" id="txtcarantper"  size="60"  maxlength="100"  onKeyUp="javascript: ue_validarcomillas(this);" value="<?php print $ls_carantper;?>">		
        </div></td>
      </tr>
        <tr>
          <td height="20"><div align="right">(*) Tipo de Personal </div></td>
          <td height="20" colspan="2"><label>
            <input name="txtcodtippersss" type="text" id="txtcodtippersss" value="<?php print $ls_codtippersss;?>" size="15" maxlength="8" onKeyUp="" readonly>
           <a href="javascript: ue_buscartipopersonalsss();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>&nbsp;
            
            <input name="txtdestippersss" type="text" class="sin-borde" id="txtdestippersss" value="<?php print $ls_dentippersss;?>" size="60" maxlength="50" readonly>
            </label></td>
        </tr>
		<tr class="formato-blanco">
                <td height="20"><div align="right">Gerencia</div></td>
              <td height="28" colspan="2"><input name="txtcodger" type="text" id="txtcodger"  size="16"  value="<?php print $ls_codger;?>" readonly>
                <a href="javascript: ue_catalogo_gerencia();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a><a href="javascript:catalogo_gerencia();"></a>
                  <input name="txtdenuger" type="text" class="sin-borde" id="txtdenger" size="57" value="<?php print $ls_denger;?>"  readonly>
              </td>               
	  </tr>
        <tr>
          <td height="20"><div align="right">Unidad Viplad&iacute;n </div></td>
          <td height="20" colspan="2"><div align="left">
            <input name="txtcodunivipladin" type="text" id="txtcodunivipladin" value="<?php print $ls_codunivipladin;?>" size="20" maxlength="15" onKeyUp="" readonly>
            <a href="javascript: ue_buscarunidadvipladin();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>&nbsp;
            <input name="txtdenunivipladin" type="text" class="sin-borde" id="txtdenunivipladin" value="<?php print $ls_denunivipladin;?>" size="65" maxlength="50" readonly>
</div></td>
        </tr>
		<tr>
          <td height="20"><div align="right">C&oacute;digo del Organigrama</div></td>
          <td height="20" colspan="2"><label>
           <input name="txtcodorg" type="text" id="txtcodorg" size="20" maxlength="10" value="<?php print $ls_codorg;?>" readonly>
           <a href="javascript: ue_catalogo_organigrama();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>&nbsp;
           <input name="txtdesorg" type="text" class="sin-borde" id="txtdesorg" size="65" value="<?php print $ls_desorg;?>"  readonly>
              </label></td>
        </tr>
		<tr>
          <td height="20"><div align="right">&nbsp;</div></td>
          <td height="20" colspan="2">
           <a href="javascript: ue_consultar_ubicacion_fisica();">Consultar Ubicaci&oacute;n F&iacute;sica seg&uacute;n Organigrama</a>
           </td>
        </tr>
        <tr>
          <td height="20"><div align="right">Modo de Envio del Recibo de Pago:  </div></td>
          <td height="20" colspan="2">
                <div align="left">
                  <select name="cmbenviorec" id="cmbenviorec">
  			        <option value="-" selected>--Seleccione Uno--</option>
                    <option value="I" <?php print $la_enviorec[0];?>>IPOSTEL</option>
                    <option value="D" <?php print $la_enviorec[1];?>>DOMESA</option>
					<option value="O" <?php print $la_enviorec[1];?>>ON-LINE</option>
                  </select>
                </div>		  </td>	
        </tr>
        <tr class="titulo-celdanew">
          <td height="20" colspan="3">Lugar de Nacimiento </td>
          </tr>
        <tr>
          <td height="20"><div align="right">Pais de Nacimiento </div></td>
          <td height="20" colspan="2"><input name="txtcodpainac" type="text" id="txtcodpainac" value="<?php print $ls_codpainac;?>" size="6" maxlength="3" readonly>
            <a href="javascript: ue_buscarpaisnac();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdespainac" type="text" class="sin-borde" id="txtdespainac" value="<?php print $ls_despainac;?>" size="60" maxlength="50" readonly></td>
        </tr>
        <tr>
          <td height="20"><div align="right">Estado de Nacimiento </div></td>
          <td height="20" colspan="2"><input name="txtcodestnac" type="text" id="txtcodestnac" value="<?php print $ls_codestnac;?>" size="6" maxlength="3" readonly>
            <a href="javascript: ue_buscarestadonac();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdesestnac" type="text" class="sin-borde" id="txtdesestnac" value="<?php print $ls_desestnac;?>" size="60" maxlength="50" readonly></td>
        </tr>
        <tr>
          <td height="20" colspan="3" class="titulo-celdanew"><div align="center">Vivienda</div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">(*) Tipo de Vivienda </div></td>
          <td colspan="2">
                <div align="left">
                    <select name="cmbtipvivper" id="cmbtipvivper">
                      <option value="" selected>--Seleccione Uno--</option>
                      <option value="0" <?php print $la_tipvivper[0];?>>Propia</option>
                      <option value="1" <?php print $la_tipvivper[1];?>>Alquilada</option>
                      <option value="2" <?php print $la_tipvivper[2];?>>No tiene</option>
                      <option value="3" <?php print $la_tipvivper[3];?>>De un Familiar</option>
                    </select>
                </div></td></tr>
        <tr>
          <td height="22"><div align="right">Tenencia de la Vivienda </div></td>
          <td colspan="2">
                <div align="left">
                  <input name="txttenvivper" type="text" id="txttenvivper" value="<?php print $ls_tenvivper;?>" size="43" maxlength="40" onKeyUp="javascript: ue_validarcomillas(this);">
                </div></td></tr>
        <tr>
          <td height="22"><div align="right">Monto Pagado por la Vivienda </div></td>
          <td colspan="2">
                <div align="left">
                  <input name="txtmonpagvivper" type="text" id="txtmonpagvivper" value="<?php print $li_monpagvivper;?>" size="23" maxlength="20" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))">
                </div></td></tr>
        <tr>
          <td height="20" colspan="3" class="titulo-celdanew"><div align="center">Cuentas</div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">Cuenta Caja de Ahorro </div></td>
          <td colspan="2">
                <div align="left">
                  <input name="txtcuecajahoper" type="text" id="txtcuecajahoper" value="<?php print $ls_cuecajahoper;?>" size="30" maxlength="25" onKeyUp="javascript: ue_validarnumero(this);"> 
                Tiene Caja de Ahorro 
                  <input name="chkcajahoper" type="checkbox" class="sin-borde" id="chkcajahoper" value="1" <?php print $ls_cajahoper;?> onClick="javascript: ue_chequear_caja_ahorro();">
                </div></td>
        </tr>
		<tr>
          <td height="22"><div align="right">Porcentaje Caja de Ahorro </div></td>
          <td colspan="2"><div align="left">
              <input name="txtporcajahoper" type="text" id="txtporcajahoper"  size="8" maxlength="5" style="text-align:left" onKeyPress="return(ue_formatonumero(this,'.',',',event))" value="<?php print $li_porcajahoper;?>" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Cuenta Ley de Pol&iacute;tica </div></td>
          <td colspan="2">
                <div align="left">
                  <input name="txtcuelphper" type="text" id="txtcuelphper" value="<?php print $ls_cuelphper;?>" size="30" maxlength="25" onKeyUp="javascript: ue_validarnumero(this);">
                </div></td></tr>
        <tr>
          <td height="22"><div align="right">Cuenta Fideicomiso </div></td>
          <td colspan="2">
                <div align="left">
                  <input name="txtcuefidper" type="text" id="txtcuefidper" value="<?php print $ls_cuefidper;?>" size="30" maxlength="25" onKeyUp="javascript: ue_validarnumero(this);">
                </div></td></tr>
        <tr>
          <td height="20" colspan="3" class="titulo-celdanew"><div align="center">Fechas de Ingreso y Egreso</div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">(*) Fecha de Ingreso a la Administraci&oacute;n P&uacute;blica </div></td>
          <td colspan="2">
              <div align="left">
                <input name="txtfecingadmpubper" type="text" id="txtfecingadmpubper" value="<?php print $ld_fecingadmpubper;?>" size="15" maxlength="10" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" datepicker="true">
              </div></td>
          </tr>
        <tr>
          <td height="22"><div align="right"> (*) A&ntilde;os de Servicio Previo </div></td>
          <td colspan="2"><div align="left">
            <input name="txtanoservpreper" type="text" id="txtanoservpreper" value="<?php print $li_anoservpreper;?>" size="5" maxlength="2" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);">
          </div></td>
          </tr>
        <tr>
         <td height="22"><div align="right"> A&ntilde;os de Servicio Previo a la Adm. Pub. <br>
          (Empleado Fijo)</div></td>
          <td colspan="2"><div align="left">
            <input name="txtanoservprefijo" type="text" id="txtanoservprefijo" value="<?php print $li_anoservprefijo;?>" size="5" maxlength="2" style="text-align:right"  onKeyUp="javascript: ue_validarnumero(this);">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right"> A&ntilde;os de Servicio Previo a la Adm. Pub. <br>
          (Empleado Contrato)</div></td>
          <td colspan="2"><div align="left">
            <input name="txtanoservprecont" type="text" id="txtanoservprecont" value="<?php print $li_anoservprecont;?>" size="5" maxlength="2" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);">
          </div></td>
        </tr>
		<tr>
          <td height="22"><div align="right"> A&ntilde;os de Servicio Personal Obrero</div></td>
          <td colspan="2"><div align="left">
            <input name="txtanoperobr" type="text" id="txtanoperobr" value="<?php print $li_anoperobr;?>" size="5" maxlength="2" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);">
          </div></td>
        </tr> 
        <tr>
          <td height="22"><div align="right">(*) Fecha de Ingreso a la Instituci&oacute;n</div></td>
          <td colspan="2">
              <div align="left">
                <input name="txtfecingper" type="text" id="txtfecingper" value="<?php print $ld_fecingper;?>" size="15" maxlength="10" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);"datepicker="true">
              </div></td>
          </tr>
		  
        <tr>
          <td height="22"><div align="right">Fecha de Reingreso a la Instituci&oacute;n </div></td>
          <td colspan="2"><input name="txtfecreingper" type="text" id="txtfecreingper" value="<?php print $ld_fecreingper;?>" size="15" maxlength="10" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" datepicker="true"> 
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Formato de Fecha (dd/mm/yyyy)  or  (01/01/1900)</strong> <strong> or  (1900-01-01)</strong></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Fecha de Jubilaci&oacute;n </div></td>
          <td colspan="2"><input name="txtfecjubper" type="text" id="txtfecjubper" value="<?php print $ld_fecjubper;?>" size="15" maxlength="10" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" datepicker="true">
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Formato de Fecha (dd/mm/yyyy)  or  (01/01/1900) or  (1900-01-01)</strong></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Fecha de Egreso de la Instituci&oacute;n </div></td>
          <td colspan="2">
              <div align="left">
                <input name="txtfecegrper" type="text" id="txtfecegrper" value="<?php print $ld_fecegrper;?>" size="15" maxlength="10"  onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);"  datepicker="true">
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Formato de Fecha (dd/mm/yyyy)  or  (01/01/1900)</strong> <strong>or  (1900-01-01)</strong></div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">Fecha de LOSSFAN </div></td>
          <td colspan="2">
              <div align="left">
                <input name="txtfecleypen" type="text" id="txtfecleypen" value="<?php print $ld_fecleypen;?>" size="15" maxlength="10" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" datepicker="true">
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Formato de Fecha (dd/mm/yyyy)  or  (01/01/1900)</strong> <strong>or  (1900-01-01)</strong></div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Causa de Egreso</div></td>
          <td colspan="2"><div align="left">
           <!-- <select name="cmbcauegrper" id="cmbcauegrper"  disabled="disabled">
               <option value="" selected>--Seleccione Uno--</option>
                <option value="N" <?php print $la_cauegrper[0];?> >Ninguno</option>
                <option value="D" <?php print $la_cauegrper[1];?> >Despido</option>
				<option value="1" <?php print $la_cauegrper[2];?> >Despido 102</option>
				<option value="2" <?php print $la_cauegrper[3];?> >Despido 125</option>				
                <option value="P" <?php print $la_cauegrper[4];?> >Pensionado</option>
                <option value="R" <?php print $la_cauegrper[5];?> >Renuncia</option>
                <option value="T" <?php print $la_cauegrper[6];?> >Traslado</option>
                <option value="J" <?php print $la_cauegrper[7];?> >Jubilado</option>
                <option value="F" <?php print $la_cauegrper[8];?> >Fallecido</option>
                <option value="P" <?php print $la_cauegrper[9];?> >Terminación de Contrato</option>
            </select>-->
			    <input name="txtcauegrper" type="text" id="txtcauegrper" size="15"  value="<?php print $ls_cauegrper2?>" maxlength="10" readonly>
				 <input name="txtcauegrper2" type="hidden" id="txtcauegrper2" value="<?php print $ls_cauegrper?>" size="15" maxlength="10" >
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Situaci&oacute;n del Personal </div></td>
          <td colspan="2"><div align="left">
              <select name="cmbsituacion" id="cmbsituacion">
                <option value="" selected>--Seleccione Uno--</option>
                <option value="1" <?php print $la_situacion[0];?> >Ninguno</option>
                <option value="2" <?php print $la_situacion[1];?> >Fallecido</option>
                <option value="3" <?php print $la_situacion[2];?> >Pensionado</option>
                <option value="4" <?php print $la_situacion[3];?> >Jubilado</option> 
				<option value="5" <?php print $la_situacion[4];?> >Retiro</option>                 
              </select>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Fecha de la Situaci&oacute;n </div></td>
          <td colspan="2"><div align="left">
              <input name="txtfecsitu" type="text" id="txtfecsitu" value="<?php print $ld_fecsitu;?>" size="15" maxlength="10" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" datepicker="true">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Causales</div></td>
          <td height="20" colspan="2"><div align="left">
            <input name="txtcodcausa" type="text" id="txtcodcausa" value="<?php print $ls_codcausa;?>" size="5" maxlength="15" onKeyUp="" readonly>
            <a href="javascript: ue_buscarcausa();"><img id="causa" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>&nbsp;
            <input name="txtdencausa" type="text" class="sin-borde" id="txtdencausa" value="<?php print $ls_dencausa;?>" size="60" maxlength="50" readonly>
</div></td>
        </tr>
        <tr>
          <td><div align="right">Observaci&oacute;n de Egreso </div></td>
          <td colspan="2" >
            <div align="left">
              <textarea name="txtobsegrper" cols="80" rows="3" id="txtobsegrper" onKeyUp="javascript: ue_validarcomillas(this);"><?php print $ls_obsegrper;?></textarea>
            </div></td>
        </tr>
         <tr class="formato-blanco">
           <td height="22" colspan="6"><div align="center"><a href="#top">Volver Arriba</a> </div></td>
         </tr>
       
        <tr>
          <td height="22"><div align="right"></div></td>
          <td colspan="2"><div align="left">
            <input name="operacion" type="hidden" id="operacion">
            <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>">
            <input name="alfnumcodper" type="hidden" id="alfnumcodper" value="<?php print $li_alfnumcodper;?>">
            <input name="txtcatper" type="hidden" id="txtcatper" value="<?php print $ls_catper;?>" size="23" maxlength="20" onKeyUp="javascript: ue_validarcomillas(this);">
          </div></td>
        </tr>
        <tr>
          <td height="22" colspan="3">            <div align="center">
              <input name="btnfamiliar" type="button" class="boton" id="btnfamiliar" value="Familiares" onClick="javascript: ue_familiar();" <?php print $ls_familiar;?>>
              <input name="btnestudio" type="button" class="boton" id="btnestudio" value="Estudios" onClick="javascript: ue_estudio();" <?php print $ls_estudio;?>>
              <input name="btntrabajo" type="button" class="boton" id="btntrabajo" value="Trabajos" onClick="javascript: ue_trabajo();" <?php print $ls_trabajo;?>>
              <input name="btnimpuesto" type="button" class="boton" id="btnimpuesto" value="ISR" onClick="javascript: ue_impuesto();" <?php print $ls_impuesto;?>>
              <input name="btnpermiso" type="button" class="boton" id="btnpermiso" value="Permiso" onClick="javascript: ue_permiso();" <?php print $ls_permiso;?>>
              <input name="btnfideicomiso" type="button" class="boton" id="btnfideicomiso" onClick="javascript: ue_fideicomiso();" value="Fideicomiso" <?php print $ls_permiso;?>>
              <input name="btnvacacion" type="button" class="boton" id="btnvacacion" onClick="javascript: ue_vacacion();" value="Vacaciones" <?php print $ls_vacacion;?>>
              <input name="btnbeneficiario" type="button" class="boton" id="btnbeneficiario" onClick="javascript: ue_beneficiario();" value="Beneficiarios" <?php print $ls_beneficiario;?>>
          </div></td>
          </tr>
      </table>
	  <p>&nbsp;</p>
      </div>
    </td>
  </tr>
</table>
<?php
	if(($ls_operacion=="BUSCAR")||($lb_valido===false))
	{
		print "<script language='javascript'>";
		print "		f=document.form1;";
		print "		f.cmbcenmedper.value='".$ls_cenmedper."';";		
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
		f.action="sigesp_snorh_d_personal.php";
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
		valido=true;
		codper=ue_validarvacio(f.txtcodper.value);
		cedper=ue_validarvacio(f.txtcedper.value);
		nomper=ue_validarvacio(f.txtnomper.value);
		apeper=ue_validarvacio(f.txtapeper.value);
		dirper=ue_validarvacio(f.txtdirper.value);
		edocivper=ue_validarvacio(f.cmbedocivper.value);
		sexper=ue_validarvacio(f.cmbsexper.value);
		codpro=ue_validarvacio(f.txtcodpro.value);
		nivacaper=ue_validarvacio(f.cmbnivacaper.value);
		numhijper=ue_validarvacio(f.txtnumhijper.value);
		monpagvivper=ue_validarvacio(f.txtmonpagvivper.value);
		anoservpreper=ue_validarvacio(f.txtanoservpreper.value);
		codpai=ue_validarvacio(f.txtcodpai.value);
		codest=ue_validarvacio(f.txtcodest.value);
		codmun=ue_validarvacio(f.txtcodmun.value);
		codpar=ue_validarvacio(f.txtcodpar.value);	
		f.txtfecnacper.value=ue_validarfecha(f.txtfecnacper.value);	
		fecnacper=ue_validarvacio(f.txtfecnacper.value);	
		f.txtfecingadmpubper.value=ue_validarfecha(f.txtfecingadmpubper.value);
		fecingadmpubper=ue_validarvacio(f.txtfecingadmpubper.value);
		f.txtfecingper.value=ue_validarfecha(f.txtfecingper.value);
		fecingper=ue_validarvacio(f.txtfecingper.value);
		f.txtfecegrper.value=ue_validarfecha(f.txtfecegrper.value);	
		fecegrper=ue_validarvacio(f.txtfecegrper.value);
		f.txtfecreingper.value=ue_validarfecha(f.txtfecreingper.value);	
		fecreingper=ue_validarvacio(f.txtfecreingper.value);
		f.txtfecjubper.value=ue_validarfecha(f.txtfecjubper.value);	
		fecjubper=ue_validarvacio(f.txtfecjubper.value);
		tipvivper=ue_validarvacio(f.cmbtipvivper.value);
		nacper=ue_validarvacio(f.cmbnacper.value);	
		coreleper=ue_validarcorreo(f.txtcoreleper.value);
		estaper=ue_validarvacio(f.txtestaper.value);
		codtippersss=ue_validarvacio(f.txtcodtippersss.value);
		if(estaper=="")
		{
			f.txtestaper.value="0";
		}
		pesper=ue_validarvacio(f.txtpesper.value);
		if(pesper=="")
		{
			f.txtpesper.value="0";
		}
		if(!ue_comparar_fechas(fecnacper,fecingadmpubper))
		{
			valido=false;
			alert("La fecha de Ingreso a la administración pública es menor que la de Nacimiento.");
		}
		if(!ue_comparar_fechas(fecnacper,fecingper))
		{
			valido=false;
			alert("La fecha de Ingreso a la institución es menor que la de Nacimiento.");
		}
		if(!ue_comparar_fechas(fecingadmpubper,fecingper))
		{
			valido=false;
			alert("La fecha de Ingreso a la institución es menor que la de Ingreso a la administración pública.");
		}
		if(!((fecreingper=="01/01/1900")||(fecreingper=="1900-01-01")))
		{
			if(!ue_comparar_fechas(fecingper,fecreingper))
			{
				valido=false;
				alert("La fecha de Reingreso es menor que la de Ingreso a la administración pública.");
			}
		}
		if(!((fecjubper=="01/01/1900")||(fecjubper=="1900-01-01")))
		{
			if(!ue_comparar_fechas(fecingper,fecjubper))
			{
				valido=false;
				alert("La fecha de Jubilación es menor que la de Ingreso a la administración pública.");
			}
		}
		if(!((fecegrper=="01/01/1900")||(fecegrper=="1900-01-01")))
		{
			if(!ue_comparar_fechas(fecingper,fecegrper))
			{
				valido=false;
				alert("La fecha de Egreso de la institución es menor que la de Ingreso a la institución.");
			}
		}
		if(valido)
		{
			if ((codper=="")||(cedper=="")||(nomper=="")||(apeper=="")||(dirper=="")||(fecnacper=="")||(edocivper=="")||(sexper=="")
				||(codpro=="")||(nivacaper=="")||(numhijper=="")||(monpagvivper=="")||(fecingadmpubper=="")||(fecingper=="")
				||(anoservpreper=="")||(codpai=="")||(codest=="")||(codmun=="")||(codpar=="")||(tipvivper=="")||(nacper=="")||(coreleper==false)||(codtippersss==false))
			{
				alert("Debe llenar todos los datos.");
			}
			else
			{
				f.operacion.value="GUARDAR";
				f.action="sigesp_snorh_d_personal.php";
				f.submit();
			}	
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
		window.open("sigesp_snorh_cat_personal.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function uf_set_focus()
{
  f = document.form1;
  ls_numrif = f.txtnumpririf.value;
  li_len = ls_numrif.length;
  if (li_len=='8')
     {
	   f.txtnumterrif.focus();
	 }
}

function ue_ayuda()
{
	width=(screen.width);
	height=(screen.height);
	//window.open("../hlp/index.php?sistema=SNO&subsistema=SNR&nomfis=sno/sigesp_hlp_snr_personal.php","Ayuda","menubar=no,toolbar=no,scrollbars=yes,width="+width+",height="+height+",resizable=yes,location=no");
}

function ue_validar(valor)
{
	f=document.form1;
	alfnumcodper=f.alfnumcodper.value;
	if(alfnumcodper==1)
	{
		ue_validarcomillas(valor);
	}
	else
	{
		ue_validarnumero(valor);
	}
}

function ue_buscarprofesion()
{
	window.open("sigesp_snorh_cat_profesion.php?tipo=personal","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarpais()
{
	window.open("sigesp_snorh_cat_pais.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarestado()
{
	f=document.form1;
	codpai=ue_validarvacio(f.txtcodpai.value);
	if(codpai!="")
	{
		window.open("sigesp_snorh_cat_estado.php?codpai="+codpai+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un pais.");
	}
}

function ue_buscarmunicipio()
{
	f=document.form1;
	codpai=ue_validarvacio(f.txtcodpai.value);
	codest=ue_validarvacio(f.txtcodest.value);
	if((codpai!="")||(codest!=""))
	{
		window.open("sigesp_snorh_cat_municipio.php?codpai="+codpai+"&codest="+codest+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un pais y un estado.");
	}
}

function ue_buscarparroquia()
{
	f=document.form1;
	codpai=ue_validarvacio(f.txtcodpai.value);
	codest=ue_validarvacio(f.txtcodest.value);
	codmun=ue_validarvacio(f.txtcodmun.value);
	if((codpai!="")||(codest!="")||(codmun!=""))
	{
		window.open("sigesp_snorh_cat_parroquia.php?codpai="+codpai+"&codest="+codest+"&codmun="+codmun+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un pais, un estado y un municipio.");
	}
}

function ue_buscarcomponente()
{
	window.open("sigesp_snorh_cat_componente.php?tipo=personal","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarrango()
{
	f=document.form1;
	codcom=ue_validarvacio(f.txtcodcom.value);
	if(codcom!="")
	{
		window.open("sigesp_snorh_cat_rango.php?tipo=personal&codcom="+codcom+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un Componente.");
	}
}

function ue_familiar()
{
	f=document.form1;
	codper=ue_validarvacio(f.txtcodper.value);
	nomper=ue_validarvacio(f.txtnomper.value)+" "+ue_validarvacio(f.txtapeper.value);
	f.txtfecnacper.value=ue_validarfecha(f.txtfecnacper.value);	
	fecnacper=ue_validarvacio(f.txtfecnacper.value);	
	location.href="sigesp_snorh_d_familiar.php?codper="+codper+"&nomper="+nomper+"&fecnacper="+fecnacper+"";
}

function ue_estudio()
{
	f=document.form1;
	codper=ue_validarvacio(f.txtcodper.value);
	nomper=ue_validarvacio(f.txtnomper.value)+" "+ue_validarvacio(f.txtapeper.value);
	f.txtfecnacper.value=ue_validarfecha(f.txtfecnacper.value);	
	fecnacper=ue_validarvacio(f.txtfecnacper.value);	
	location.href="sigesp_snorh_d_estudiorealizado.php?codper="+codper+"&nomper="+nomper+"&fecnacper="+fecnacper+"";
}

function ue_trabajo()
{
	f=document.form1;
	codper=ue_validarvacio(f.txtcodper.value);
	nomper=ue_validarvacio(f.txtnomper.value)+" "+ue_validarvacio(f.txtapeper.value);
	f.txtfecnacper.value=ue_validarfecha(f.txtfecnacper.value);	
	fecnacper=ue_validarvacio(f.txtfecnacper.value);	
	f.txtfecingper.value=ue_validarfecha(f.txtfecingper.value);	
	fecingper=ue_validarvacio(f.txtfecingper.value);	
	location.href="sigesp_snorh_d_trabajoanterior.php?codper="+codper+"&nomper="+nomper+"&fecnacper="+fecnacper+"&fecingper="+fecingper+"";
}

function ue_impuesto()
{
	f=document.form1;
	codper=ue_validarvacio(f.txtcodper.value);
	nomper=ue_validarvacio(f.txtnomper.value)+" "+ue_validarvacio(f.txtapeper.value);
	location.href="sigesp_snorh_d_isr.php?codper="+codper+"&nomper="+nomper+"";
}

function ue_permiso()
{
	f=document.form1;
	codper=ue_validarvacio(f.txtcodper.value);
	nomper=ue_validarvacio(f.txtnomper.value)+" "+ue_validarvacio(f.txtapeper.value);
	f.txtfecnacper.value=ue_validarfecha(f.txtfecnacper.value);	
	fecnacper=ue_validarvacio(f.txtfecnacper.value);	
	f.txtfecingper.value=ue_validarfecha(f.txtfecingper.value);	
	fecingper=ue_validarvacio(f.txtfecingper.value);	
	location.href="sigesp_snorh_d_permiso.php?codper="+codper+"&nomper="+nomper+"&fecnacper="+fecnacper+"&fecingper="+fecingper+"";
}

function ue_fideicomiso()
{
	f=document.form1;
	codper=ue_validarvacio(f.txtcodper.value);
	nomper=ue_validarvacio(f.txtnomper.value)+" "+ue_validarvacio(f.txtapeper.value);
	f.txtfecnacper.value=ue_validarfecha(f.txtfecnacper.value);	
	fecnacper=ue_validarvacio(f.txtfecnacper.value);	
	f.txtfecingper.value=ue_validarfecha(f.txtfecingper.value);	
	fecingper=ue_validarvacio(f.txtfecingper.value);
	fecingadmpubper=ue_validarvacio(f.txtfecingadmpubper.value);
	
		
	location.href="sigesp_snorh_d_fideicomiso.php?codper="+codper+"&nomper="+nomper+"&fecnacper="+fecnacper+"&fecingper="+fecingper+"&fecingadmpubper="+fecingadmpubper+"";
}

function ue_vacacion()
{
	f=document.form1;
	codper=ue_validarvacio(f.txtcodper.value);
	nomper=ue_validarvacio(f.txtnomper.value)+" "+ue_validarvacio(f.txtapeper.value);
	location.href="sigesp_snorh_d_vacacion.php?codper="+codper+"&nomper="+nomper+"";	
}

function ue_beneficiario()
{
	f=document.form1;
	codper=ue_validarvacio(f.txtcodper.value);
	nomper=ue_validarvacio(f.txtnomper.value)+" "+ue_validarvacio(f.txtapeper.value);
	location.href="sigesp_snorh_d_beneficiario.php?codper="+codper+"&nomper="+nomper+"";	
}

function ue_hcm()
{
	// SE QUITO EL DÍA 13/09/2007
	// POR SOLICITUD DEL SR ANIBAL YA QUE SE DEBE MARCAR TODO EL PERSONAL
	//f=document.form1;
	//if(f.cmbsexper.value!="F")
	//{
	//	f.chkhcmper.checked=false;
	//	alert("La poliza de maternidad es solo para las Mujeres");
	//}
}

function ue_buscarpaisnac()
{
	window.open("sigesp_snorh_cat_pais.php?tipo=NACIMIENTO","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarestadonac()
{
	f=document.form1;
	codpai=ue_validarvacio(f.txtcodpainac.value);
	if(codpai!="")
	{
		window.open("sigesp_snorh_cat_estado.php?tipo=NACIMIENTO&codpai="+codpai+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un pais.");
	}
}

function ue_buscartipopersonalsss()
{
	window.open("sigesp_snorh_cat_tipopersonalsss.php?tipo=personal","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarunidadvipladin()
{
	window.open("sigesp_snorh_cat_unidadvipladin.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarcausa()
{
	  if (document.images["causa"].style.visibility!="hidden")
	  {
		window.open("sigesp_snorh_cat_causa.php?tipo=personal","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
	  }
}

function ue_select_causa()
{
	f=document.form1;
	document.images["causa"].style.visibility="visible";
	document.form1.txtcodcausa.style.visibility="visible";
}

function ue_select_causa2()
{
	f=document.form1;
	document.images["causa"].style.visibility="hidden";
	document.form1.txtcodcausa.value="";
	document.form1.txtdencausa.value="";
}

function ue_catalogo_organigrama()
{
   
   pagina="../srh/pages/vistas/catalogos/sigesp_srh_cat_organigrama.php?valor_cat=0&tipo=2";
	window.open(pagina,"catalogo","menubar=no, toolbar=no, scrollbars=yes,width=530, height=400,resizable=yes, location=no,				dependent=yes");
	 
}

function ue_chequear_caja_ahorro()
{
   f=document.form1;
   if (f.chkcajahoper.checked)
   {
   		f.txtporcajahoper.readOnly=false;
   }
   else
   {
   		f.txtporcajahoper.value=0;
		f.txtporcajahoper.readOnly=true;
   }
  
	 
}


function ue_consultar_ubicacion_fisica ()
{
	f=document.form1;
	codorg=f.txtcodorg.value;
	if (codorg=="")
	{
		alert("Debe seleccionar el Código del Organigrama");	
	}
	else
	{
		
		window.open("sigesp_snorh_pdt_ubicacion_fisica.php?codorg="+codorg,"cat","menubar=no,toolbar=no,scrollbars=yes,width=680,height=400,left=50,top=50,location=no,resizable=yes");
	}
}

function ue_catalogo_gerencia()
{
	window.open("../srh/pages/vistas/catalogos/sigesp_srh_cat_gerencia.php?valor_cat=0&tipo=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=yes");
}

var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
</script> 
</html>