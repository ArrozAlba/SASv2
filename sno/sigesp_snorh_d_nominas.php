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
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_d_nominas.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables() 
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $ls_codnom,$ls_desnom,$ls_tippernom,$ls_despernom,$ls_anocurnom,$ld_fecininom,$ls_peractnom,$ls_tipnom,$ls_subnom;
   		global $ls_racnom,$ls_adenom,$ls_espnom,$ls_ctnom,$ls_ctmetnom,$ls_consulnom,$ls_descomnom,$ls_codpronom,$ls_codbennom;
   		global $ls_conaponom,$ls_cueconnom,$ls_notdebnom,$ls_numvounom,$ls_recdocnom,$ls_tipdocnom,$ls_recdocapo,$ls_tipdocapo;
   		global $ls_perresnom,$ls_operacion,$ls_existe,$io_fun_nomina,$io_nomina,$ls_activo,$ls_activo_cod,$ls_activo_contabilizacion;
		global $la_tippernom, $la_tipnom, $la_consulnom, $la_conaponom, $la_descomnom,$li_conta_global, $ls_disabled, $ls_disabled_subnom;
		global $ls_disabled_establecer, $ls_conpernom, $ls_conpronom, $ls_titrepnom, $ls_codorgcestic, $ls_confidnom, $la_confidnom;
		global $ls_recdocfid, $ls_tipdocfid, $ls_codbenfid, $ls_cueconfid, $ls_divcon, $ls_informa,$li_genrecdocpagperche,$ls_tipdocpagperche;
		global $li_estctaalt,$ls_racobrnom;
				
		$ls_codnom="";
		$ls_desnom="";
		$ls_tippernom="";
		$la_tippernom[0]="";
		$la_tippernom[1]="";
		$la_tippernom[2]="";
		$la_tippernom[3]="";
		$ls_despernom="";
		$fecha=$_SESSION["la_empresa"]["periodo"];
		$ls_anocurnom=substr($fecha,0,4);
		$ld_fecininom="01/01/".substr($fecha,0,4);
		$ls_peractnom="001";
		$ls_tipnom="";
		$la_tipnom[0]="";
		$la_tipnom[1]="";
		$la_tipnom[2]="";
		$la_tipnom[3]="";
		$la_tipnom[4]="";
		$la_tipnom[5]="";
		$la_tipnom[6]="";
		$la_tipnom[7]="";
		$la_tipnom[8]="";
		$la_tipnom[9]="";
		$la_tipnom[10]="";
		$la_tipnom[11]="";
		$la_tipnom[12]="";
		$la_tipnom[13]="";
		$la_tipnom[14]="";
		$ls_subnom="";
		$ls_racnom="";
		$ls_racobrnom="";
		$ls_adenom="";
		$ls_divcon="";
		$ls_espnom="";
		$ls_conpronom="";
		$ls_ctnom="";
		$ls_ctmetnom="";
		$ls_conpernom="";
		$ls_consulnom="OCP";
		$la_consulnom[0]="";
		$la_consulnom[1]="";
		$la_consulnom[2]="";
		$la_consulnom[3]="";
		$ls_descomnom="";
		$la_descomnom[0]="";
		$la_descomnom[1]="";
		$la_descomnom[2]="";
		$ls_codpronom="----------";
		$ls_codbennom="----------";
		$ls_conaponom="OCP";
		$la_conaponom[0]="";
		$la_conaponom[1]="";
		$la_conaponom[2]="";
		$la_conaponom[3]="";
		$ls_cueconnom="";
		$ls_notdebnom="";
		$ls_numvounom="0";
		$ls_recdocnom="";
		$ls_tipdocnom="";
		$ls_recdocapo="";
		$ls_tipdocapo="";
		$ls_perresnom="";
		$ls_codorgcestic="";
		$ls_cueconfid="";
		$ls_confidnom="OC"; 
		$la_confidnom[0]="";		
		$ls_recdocfid="";
		$ls_tipdocfid="";
		$ls_codbenfid="----------";
		$ls_activo_contabilizacion="";
		$ls_activo="";
		$ls_titrepnom="";
		$ls_activo_cod="";
		$li_genrecdocpagperche=0;
		$li_estctaalt=0;
		$ls_tipdocpagperche="";	
		$ls_disabled=" disabled";
		$ls_disabled_subnom=" disabled";
		$ls_disabled_establecer=" disabled";
		$ls_informa="";
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		$ls_existe=$io_fun_nomina->uf_obtenerexiste();
		$li_conta_global=$io_nomina->io_sno->uf_select_config("SNO","CONFIG","CONTA GLOBAL","0","I");
		if($li_conta_global=="0")
		{
			$li_estctaalt=trim($io_nomina->io_sno->uf_select_config("SNO","CONFIG","UTILIZAR_CTA.CONTA_REC_DOC_PROV_BEN","0","I"));	
			$li_genrecdocpagperche=trim($io_nomina->io_sno->uf_select_config("SNO","CONFIG","GENERAR RECEPCION DOCUMENTO PAGO PERSONAL CHEQUE","0","I"));	
		    $ls_tipdocpagperche=trim($io_nomina->io_sno->uf_select_config("SNO","CONFIG","TIPO DOCUMENTO PAGO PERSONAL CHEQUE","","C"));	
			$ls_consulnom=$io_nomina->io_sno->uf_select_config("SNO","NOMINA","CONTABILIZACION","OCP","C");
			$ls_notdebnom=$io_nomina->io_sno->uf_select_config("SNO","CONFIG","GENERAR NOTA DEBITO","1","I");
			$ls_recdocnom=$io_nomina->io_sno->uf_select_config("SNO","CONFIG","GENERAR RECEPCION DOCUMENTO","0","I");
			$ls_recdocfid=$io_nomina->io_sno->uf_select_config("SNO","CONFIG","GENERAR RECEPCION DOCUMENTO FIDEICOMISO","0" ,"I");
			$ls_recdocapo=$io_nomina->io_sno->uf_select_config("SNO","CONFIG","GENERAR RECEPCION DOCUMENTO APORTE","0","I");
			$ls_tipdocnom=$io_nomina->io_sno->uf_select_config("SNO","CONFIG","TIPO DOCUMENTO NOMINA","","C");
			$ls_tipdocapo=$io_nomina->io_sno->uf_select_config("SNO","CONFIG","TIPO DOCUMENTO APORTE","","C");
			$ls_tipdocfid=$io_nomina->io_sno->uf_select_config("SNO","CONFIG","TIPO DOCUMENTO FIDEICOMISO","","C");
			$ls_conaponom=$io_nomina->io_sno->uf_select_config("SNO","NOMINA","CONTABILIZACION APORTES","OCP","C");
			$ls_confidnom=$io_nomina->io_sno->uf_select_config("SNO","NOMINA","CONTABILIZACION FIDEICOMISO","OCP","C");
			$ls_cueconnom=trim($io_nomina->io_sno->uf_select_config("SNO","CONFIG","CTA.CONTA","XXXXXXXXXXXXX","C"));
			$ls_cueconfid=trim($io_nomina->io_sno->uf_select_config("SNO","CONFIG","CTA.CONTABLE_FIDEICOMISO","XXXXXXXXXXXXX","C"));
			$ls_descomnom=trim($io_nomina->io_sno->uf_select_config("SNO","NOMINA","CONTABILIZACION DESTINO","","C"));
			$ls_codbenfid=trim($io_nomina->io_sno->uf_select_config("SNO","NOMINA","DESTINO FIDEICOMISO","----------","C"));
			switch (substr($ls_descomnom,0,1))
			{
				case "P":
					$ls_codpronom=substr($ls_descomnom,1,strlen($ls_descomnom)-1);
					$ls_codbennom="----------";
					break;
					
				case "B":
					$ls_codbennom=substr($ls_descomnom,1,strlen($ls_descomnom)-1);
					$ls_codpronom="----------";
					break;
			}
			$ls_activo_contabilizacion="";
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
		// Fecha Creación: 18/03/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codnom,$ls_desnom,$ls_tippernom,$ls_despernom,$ls_anocurnom,$ld_fecininom,$ls_peractnom,$ls_tipnom,$ls_subnom;
   		global $ls_racnom,$ls_adenom,$ls_espnom,$ls_ctnom,$ls_ctmetnom,$ls_consulnom,$ls_descomnom,$ls_codpronom,$ls_codbennom;
   		global $ls_conaponom,$ls_cueconnom,$ls_notdebnom,$ls_numvounom,$ls_recdocnom,$ls_tipdocnom,$ls_recdocapo,$ls_tipdocapo;
		global $io_fun_nomina,$io_nomina,$ls_conpernom,$ls_conpronom, $ls_titrepnom, $ls_codorgcestic, $ls_confidnom, $ls_recdocfid;
		global $ls_tipdocfid, $ls_cueconfid, $ls_codbenfid, $ls_divcon, $ls_informa,$li_genrecdocpagperche,$ls_tipdocpagperche,$li_estctaalt;
		global $ls_racobrnom;
		
		$ls_codnom=$_POST["txtcodnom"];
		$ls_desnom=$io_fun_nomina->uf_obtenervalor("txtdesnom","");
		$ls_tippernom=$io_fun_nomina->uf_obtenervalor("cmbtippernom","");
		$ls_despernom=$io_fun_nomina->uf_obtenervalor("txtdespernom","");
		$ls_anocurnom=$io_fun_nomina->uf_obtenervalor("txtanocurnom","");
		$ld_fecininom=$io_fun_nomina->uf_obtenervalor("txtfecininom","");
		$ls_peractnom=$io_fun_nomina->uf_obtenervalor("txtperactnom","");
		$ls_tipnom=$io_fun_nomina->uf_obtenervalor("cmbtipnom","");
		$ls_subnom=$io_fun_nomina->uf_obtenervalor("chksubnom","0");
		$ls_racnom=$io_fun_nomina->uf_obtenervalor("chkracnom","0");
		$ls_racobrnom=$io_fun_nomina->uf_obtenervalor("chkracobrnom","0");
		$ls_adenom=$io_fun_nomina->uf_obtenervalor("chkadenom","0");
		$ls_divcon=$io_fun_nomina->uf_obtenervalor("chkdivcon","0");
		$ls_espnom=$io_fun_nomina->uf_obtenervalor("chkespnom","0");
		$ls_conpronom=$io_fun_nomina->uf_obtenervalor("chkconpronom","0");
		$ls_ctnom=$io_fun_nomina->uf_obtenervalor("chkctnom","0");
		$ls_ctmetnom=$io_fun_nomina->uf_obtenervalor("txtctmetnom","");
		$ls_conpernom=$io_fun_nomina->uf_obtenervalor("chkconpernom","0");
		$ls_tipdocpagperche=$io_fun_nomina->uf_obtenervalor("txttipdocpagper","");
		$li_genrecdocpagperche=$io_fun_nomina->uf_obtenervalor("chkgenrecdocpagper","0");
		$li_estctaalt=$io_fun_nomina->uf_obtenervalor("chkestctaalt","0");
		$ls_titrepnom=$_POST["txttitrepnom"];
		$ls_informa=$io_fun_nomina->uf_obtenervalor("txtinforma","");
		$ls_codorgcestic=$io_fun_nomina->uf_obtenervalor("txtcodorgcestic","");
		$li_conta_global=$io_nomina->io_sno->uf_select_config("SNO","CONFIG","CONTA GLOBAL","0","I");
		if($li_conta_global=="0")
		{
			
			$li_estctaalt=trim($io_nomina->io_sno->uf_select_config("SNO","CONFIG","UTILIZAR_CTA.CONTA_REC_DOC_PROV_BEN","0","I"));	
			$li_genrecdocpagperche=trim($io_nomina->io_sno->uf_select_config("SNO","CONFIG","GENERAR RECEPCION DOCUMENTO PAGO PERSONAL CHEQUE","0","I"));
		    $ls_tipdocpagperche=trim($io_nomina->io_sno->uf_select_config("SNO","CONFIG","TIPO DOCUMENTO PAGO PERSONAL CHEQUE","","C"));	
			$ls_consulnom=$io_nomina->io_sno->uf_select_config("SNO","NOMINA","CONTABILIZACION","OCP","C");
			$ls_notdebnom=$io_nomina->io_sno->uf_select_config("SNO","CONFIG","GENERAR NOTA DEBITO","1","I");
			$ls_recdocnom=$io_nomina->io_sno->uf_select_config("SNO","CONFIG","GENERAR RECEPCION DOCUMENTO","0","I");
			$ls_recdocfid=$io_nomina->io_sno->uf_select_config("SNO","CONFIG","GENERAR RECEPCION DOCUMENTO FIDEICOMISO","0" ,"I");			
			$ls_recdocapo=$io_nomina->io_sno->uf_select_config("SNO","CONFIG","GENERAR RECEPCION DOCUMENTO APORTE","0","I");
			$ls_tipdocnom=$io_nomina->io_sno->uf_select_config("SNO","CONFIG","TIPO DOCUMENTO NOMINA","","C");
			$ls_tipdocapo=$io_nomina->io_sno->uf_select_config("SNO","CONFIG","TIPO DOCUMENTO APORTE","","C");
			$ls_tipdocfid=$io_nomina->io_sno->uf_select_config("SNO","CONFIG","TIPO DOCUMENTO FIDEICOMISO","","C");			
			$ls_conaponom=$io_nomina->io_sno->uf_select_config("SNO","NOMINA","CONTABILIZACION APORTES","OCP","C");
			$ls_confidnom=$io_nomina->io_sno->uf_select_config("SNO","NOMINA","CONTABILIZACION FIDEICOMISO","OCP","C");
			$ls_cueconnom=trim($io_nomina->io_sno->uf_select_config("SNO","CONFIG","CTA.CONTA","XXXXXXXXXXXXX","C"));
			$ls_cueconfid=trim($io_nomina->io_sno->uf_select_config("SNO","CONFIG","CTA.CONTABLE_FIDEICOMISO","XXXXXXXXXXXXX","C"));
			$ls_descomnom=trim($io_nomina->io_sno->uf_select_config("SNO","NOMINA","CONTABILIZACION DESTINO","","C"));
			$ls_codbenfid=trim($io_nomina->io_sno->uf_select_config("SNO","NOMINA","DESTINO FIDEICOMISO","----------","C"));			
			switch (substr($ls_descomnom,0,1))
			{
				case "P":
					$ls_codpronom=substr($ls_descomnom,1,strlen($ls_descomnom)-1);
					$ls_codbennom="----------";
					break;
					
				case "B":
					$ls_codbennom=substr($ls_descomnom,1,strlen($ls_descomnom)-1);
					$ls_codpronom="----------";
					break;
			}
		}
		else
		{
			$ls_consulnom=$_POST["cmbconsulnom"];
			$ls_descomnom=$_POST["cmbdesconnom"];
			switch (substr($ls_descomnom,0,1))
			{
				case "P":
					$ls_codpronom=$_POST["txtcodproben"];
					$ls_codbennom="----------";
					break;
					
				case "B":
					$ls_codbennom=$_POST["txtcodproben"];
					$ls_codpronom="----------";
					break;
			}
			$ls_codbenfid=$_POST["txtcodbenfid"];
			$ls_conaponom=$_POST["cmbconaponom"];
			$ls_confidnom=$_POST["cmbconfidnom"];
			$ls_cueconnom=$_POST["txtcueconnom"];
			$ls_notdebnom=$io_fun_nomina->uf_obtenervalor("chknotdebnom","0");
			$ls_numvounom="0";
			$ls_recdocnom=$io_fun_nomina->uf_obtenervalor("chkrecdocnom","0");
			$ls_recdocfid=$io_fun_nomina->uf_obtenervalor("chkrecdocfid","0");
			$ls_tipdocnom=$_POST["txttipdocnom"];
			$ls_tipdocfid=$_POST["txttipdocfid"];
			$ls_recdocapo=$io_fun_nomina->uf_obtenervalor("chkrecdocapo","0");
			$ls_tipdocapo=$_POST["txttipdocapo"];
			$ls_cueconfid=$_POST["txtcueconfid"];
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
<title>Definici&oacute;n de la Nomina</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	background-color: #EAEAEA;
	margin-left: 0px;
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
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_nomina.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/validaciones.js"></script>
<style type="text/css">
<!--
.Estilo3 {color: #6699CC}
-->
</style>
</head>
<body>
<?php 
	require_once("sigesp_snorh_c_nominas.php");
	$io_nomina=new sigesp_snorh_c_nominas();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
			$io_fun_nomina->uf_seleccionarcombo("CP-OCP-OC-O",$ls_consulnom,$la_consulnom,4);	
			$io_fun_nomina->uf_seleccionarcombo("CP-OCP-OC-O",$ls_conaponom,$la_conaponom,4);	
			$io_fun_nomina->uf_seleccionarcombo("OC",$ls_confidnom,$la_confidnom,1);	
			$io_fun_nomina->uf_seleccionarcombo(" -P-B",substr($ls_descomnom,0,1),$la_descomnom,3);	
			break;

		case "GUARDAR":
			uf_load_variables();
			$lb_valido=$io_nomina->uf_guardar_nomina($ls_existe,$ls_codnom,$ls_desnom,$ls_tippernom,$ls_despernom,$ls_anocurnom,
											  		 $ld_fecininom,$ls_peractnom,$ls_tipnom,$ls_subnom,$ls_racnom,$ls_adenom,
   											  		 $ls_espnom,$ls_ctnom,$ls_ctmetnom,$ls_consulnom,substr($ls_descomnom,0,1),$ls_codpronom,
											  		 $ls_codbennom,$ls_conaponom,$ls_cueconnom,$ls_notdebnom,$ls_numvounom,
											  		 $ls_recdocnom,$ls_tipdocnom,$ls_recdocapo,$ls_tipdocapo,$ls_conpernom,
													 $ls_conpronom,$ls_titrepnom,$ls_codorgcestic,$ls_confidnom,$ls_recdocfid,$ls_tipdocfid,
													 $ls_codbenfid,$ls_cueconfid,$ls_divcon,$ls_informa,$li_genrecdocpagperche,$ls_tipdocpagperche,
													 $li_estctaalt,$ls_racobrnom,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
			}
			else
			{
				$io_fun_nomina->uf_seleccionarcombo("0-1-2-3",$ls_tippernom,$la_tippernom,4);			
				$io_fun_nomina->uf_seleccionarcombo("1-2-3-4-5-6-7-8-9-10-11-12-13-14",$ls_tipnom,$la_tipnom,14);	
			}
			$io_fun_nomina->uf_seleccionarcombo("CP-OCP-OC-O",$ls_consulnom,$la_consulnom,4);	
			$io_fun_nomina->uf_seleccionarcombo("CP-OCP-OC-O",$ls_conaponom,$la_conaponom,4);
			$io_fun_nomina->uf_seleccionarcombo("OC",$ls_confidnom,$la_confidnom,1);	
			$io_fun_nomina->uf_seleccionarcombo(" -P-B",substr($ls_descomnom,0,1),$la_descomnom,3);	
			break;

		case "ELIMINAR":
			uf_load_variables();
			$lb_valido=$io_nomina->uf_delete_nomina($ls_codnom,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
			}
			else
			{
				$lb_valido=$io_nomina->load_nomina($li_conta_global,$ls_existe,$ls_codnom,$ls_desnom,$ls_tippernom,$ls_despernom,$ls_anocurnom,
												   $ld_fecininom,$ls_peractnom,$ls_tipnom,$ls_subnom,$ls_racnom,$ls_adenom,
												   $ls_espnom,$ls_ctnom,$ls_ctmetnom,$ls_consulnom,$ls_descomnom,$ls_codpronom,
												   $ls_codbennom,$ls_conaponom,$ls_cueconnom,$ls_notdebnom,$ls_numvounom,
												   $ls_recdocnom,$ls_tipdocnom,$ls_recdocapo,$ls_tipdocapo,$li_total,$ls_conpernom,
												   $ls_conpronom,$ls_titrepnom,$ls_codorgcestic,$ls_confidnom,$ls_recdocfid,$ls_tipdocfid,
												   $ls_codbenfid,$ls_cueconfid,$ls_divcon,$ls_informa,$li_genrecdocpagperche,$ls_tipdocpagperche,
												   $li_estctaalt,$ls_racobrnom);
				if($lb_valido)
				{
					$ls_activo="disabled";
					$ls_activo_cod="readonly";
					$io_fun_nomina->uf_seleccionarcombo("0-1-2-3",$ls_tippernom,$la_tippernom,4);			
					$io_fun_nomina->uf_seleccionarcombo("1-2-3-4-5-6-7-8-9-10-11-12-13-14",$ls_tipnom,$la_tipnom,14);			
				}
			}
			$io_fun_nomina->uf_seleccionarcombo("CP-OCP-OC-O",$ls_consulnom,$la_consulnom,4);	
			$io_fun_nomina->uf_seleccionarcombo("CP-OCP-OC-O",$ls_conaponom,$la_conaponom,4);	
			$io_fun_nomina->uf_seleccionarcombo("OC",$ls_confidnom,$la_confidnom,1);	
			$io_fun_nomina->uf_seleccionarcombo(" -P-B",substr($ls_descomnom,0,1),$la_descomnom,3);	
			break;

		case "BUSCAR":
			$ls_existe="FALSE";
			$ls_codnom=$_POST["txtcodnom"];
			$li_total=1;
			$lb_valido=$io_nomina->load_nomina($li_conta_global,$ls_existe,$ls_codnom,$ls_desnom,$ls_tippernom,$ls_despernom,$ls_anocurnom,
											   $ld_fecininom,$ls_peractnom,$ls_tipnom,$ls_subnom,$ls_racnom,$ls_adenom,
   											   $ls_espnom,$ls_ctnom,$ls_ctmetnom,$ls_consulnom,$ls_descomnom,$ls_codpronom,
											   $ls_codbennom,$ls_conaponom,$ls_cueconnom,$ls_notdebnom,$ls_numvounom,
											   $ls_recdocnom,$ls_tipdocnom,$ls_recdocapo,$ls_tipdocapo,$li_total,$ls_conpernom,
											   $ls_conpronom,$ls_titrepnom,$ls_codorgcestic,$ls_confidnom,$ls_recdocfid,$ls_tipdocfid,
											   $ls_codbenfid,$ls_cueconfid,$ls_divcon,$ls_informa,$li_genrecdocpagperche,$ls_tipdocpagperche,
											   $li_estctaalt,$ls_racobrnom); 
			if($lb_valido)
			{
				$ls_activo="disabled";
				$ls_activo_cod="readonly";
				$ls_disabled="";
				if($ls_subnom=="1")
				{
					$ls_disabled_subnom="";
				}
				if($li_total=="0")
				{
					$ls_disabled_establecer="";
				}
				$io_fun_nomina->uf_seleccionarcombo("0-1-2-3",$ls_tippernom,$la_tippernom,4);			
				$io_fun_nomina->uf_seleccionarcombo("1-2-3-4-5-6-7-8-9-10-11-12-13-14",$ls_tipnom,$la_tipnom,14);
			}
			$io_fun_nomina->uf_seleccionarcombo("CP-OCP-OC-O",$ls_consulnom,$la_consulnom,4);	
			$io_fun_nomina->uf_seleccionarcombo("CP-OCP-OC-O",$ls_conaponom,$la_conaponom,4);	
			$io_fun_nomina->uf_seleccionarcombo("OC",$ls_confidnom,$la_confidnom,1);	
			$io_fun_nomina->uf_seleccionarcombo(" -P-B",substr($ls_descomnom,0,1),$la_descomnom,3);	
			break;
	}
	$io_nomina->uf_destructor();
	unset($io_nomina);
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo3">Sistema de Nómina</td>
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
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif"  title="Salir" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
  <table width="760" height="223" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td>
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<p>&nbsp;</p>
<table width="710" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
		  <tr class="titulo-ventana">
			<td height="20" colspan="4">Definici&oacute;n de la Nomina </td>
		  </tr>
		  <tr >
			<td width="144" height="22">&nbsp;</td>
			<td colspan="3">&nbsp;</td>
		  </tr>
		  <tr>
			<td height="22"><div align="right" >
				<p>Codigo</p>
			</div></td>
			<td colspan="3"><div align="left" >
			  <input name="txtcodnom" type="text" id="txtcodnom" value="<?php print $ls_codnom;?>" size="6" maxlength="4" onKeyUp="javascript: ue_validarnumero(this);" onBlur="javascript: ue_rellenarcampo(this,4);" <?php print $ls_activo_cod;?>>
</div></td>
          </tr>
              <tr >
                <td height="22"><div align="right">Denominaci&oacute;n</div></td>
                <td colspan="3"><div align="left">
                  <input name="txtdesnom" type="text" id="txtdesnom" value="<?php print $ls_desnom;?>" onKeyPress="javascript: ue_validarcomillas(this);" size="70" maxlength="100" <?php print $ls_activo;?>>
                </div></td>
              </tr>
              <tr>
                <td height="22"><div align="right">Tipo de Periodo </div></td>
                <td colspan="3"><div align="left">
                  <select name="cmbtippernom" id="cmbtippernom" onChange="javascript: ue_nominasmensuales('QUITAR');" <?php print $ls_activo;?>>
                    <option value="" selected>--Seleccione--</option>
                    <option value="0" <?php print $la_tippernom[0]; ?>>Semanal</option>
                    <option value="1" <?php print $la_tippernom[1]; ?>>Quincenal</option>
                    <option value="2" <?php print $la_tippernom[2]; ?>>Mensual</option>
                    <option value="3" <?php print $la_tippernom[3]; ?>>Anual</option>
                  </select>
                </div></td>
              </tr>
              <tr>
                <td height="22"><div align="right">Denominaci&oacute;n del Periodo </div></td>
                <td colspan="3"><div align="left">
                  <input name="txtdespernom" type="text" id="txtdespernom" value="<?php print $ls_despernom;?>" onKeyPress="javascript: ue_validarcomillas(this);"  size="70" maxlength="100" <?php print $ls_activo;?>>
                </div></td>
              </tr>
              <tr>
                <td height="22"><div align="right">A&ntilde;o</div></td>
                <td width="147"><div align="left">
                  <input name="txtanocurnom" type="text" id="txtanocurnom" value="<?php print $ls_anocurnom;?>" size="6" maxlength="4" readonly>
                </div></td>
                <td width="228"><div align="right">Inicio</div></td>
                <td width="181"><div align="left">
                  <input name="txtfecininom" type="text" id="txtfecininom"  style="text-align:left " onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);"  value="<?php print $ld_fecininom; ?>" size="17" maxlength="15" datepicker="true" <?php print $ls_activo;?> onBlur="javascript: ue_validar_formatofecha(this);">
                </div></td>
              </tr>
              <tr>
                <td height="22"><div align="right">Periodo Actual </div></td>
                <td><div align="left">
                  <input name="txtperactnom" type="text" id="txtperactnom" value="<?php print $ls_peractnom;?>" size="6" maxlength="3" readonly>
</div></td>
                <td><div align="right">Tipo de Nomina </div></td>
                <td><div align="left">
                  <select name="cmbtipnom" id="cmbtipnom" onChange="javascript: ue_verificarrac('0');" <?php print $ls_activo;?>>
                    <option value="" selected>--Seleccione--</option>
                    <option value="1" <?php print $la_tipnom[0]; ?>>Empleado Fijo</option>
                    <option value="2" <?php print $la_tipnom[1]; ?>>Empleado Contratado</option>
                    <option value="3" <?php print $la_tipnom[2]; ?>>Obrero Fijo</option>
                    <option value="4" <?php print $la_tipnom[3]; ?>>Obrero Contratado</option>
                    <option value="5" <?php print $la_tipnom[4]; ?>>Docente Fijo</option>
                    <option value="6" <?php print $la_tipnom[5]; ?>>Docente Contratado</option>
                    <option value="7" <?php print $la_tipnom[6]; ?>>Jubilado</option>
                    <option value="8" <?php print $la_tipnom[7]; ?>>Comision de Servicios</option>
                    <option value="9" <?php print $la_tipnom[8]; ?>>Libre Nombramiento</option>
                    <option value="10" <?php print $la_tipnom[9]; ?>>Militar</option>
                    <option value="11" <?php print $la_tipnom[10]; ?>>Honorarios Profesionales</option>
                    <option value="12" <?php print $la_tipnom[11]; ?>>Pensionado</option>
                    <option value="13" <?php print $la_tipnom[12]; ?>>Suplente</option>
                    <option value="14" <?php print $la_tipnom[13]; ?>>Contratado</option>
                    <option value="15" <?php print $la_tipnom[14]; ?>>Incapacitados</option>
                  </select>
                </div></td>
              </tr>
              <tr>
                <td height="20"><div align="right"></div></td>
                <td colspan="3">&nbsp;</td>
              </tr>
              <tr class="titulo-celdanew">
                <td height="20" colspan="4"><div align="center" class="titulo-celdanew">Atributos Especiales de Nomina </div></td>
            </tr>
              <tr>
                <td height="22"><div align="right">
                  Sub Nominas
</div></td>
                <td height="22"><div align="left">
                  <input name="chksubnom" type="checkbox" class="sin-borde" id="chksubnom" style=" width:15px; height:15px" value="1" <?php if($ls_subnom=="1"){ print " checked ";} print $ls_activo;?>>
                </div></td>
                <td height="22">
                  <div align="right">Adelanto de Quincena (solo para n&oacute;minas Mensuales) </div></td>
                <td height="22"><div align="left">
                  <input name="chkadenom" type="checkbox" class="sin-borde" id="chkadenom" style=" width:15px; height:15px" value="1" onChange="javascript: ue_nominasmensuales('ADELANTO');" <?php if($ls_adenom=="1"){ print " checked ";} print $ls_activo;?>>
</div></td>
              </tr>
              <tr>
                <td height="22"><div align="right">R.A.C. </div></td>
                <td height="22"><input name="chkracnom" type="checkbox" class="sin-borde" id="chkracnom" style=" width:15px; height:15px" value="1" onChange="javascript: ue_verificarrac('1');" <?php if($ls_racnom=="1"){ print " checked ";} print $ls_activo;?>></td>
                <td height="22"><div align="right">Dividir Conceptos en Quincena (solo para n&oacute;minas Mensuales) </div></td>
                <td height="22"><input name="chkdivcon" type="checkbox" class="sin-borde" id="chkdivcon" style=" width:15px; height:15px" value="1" onChange="javascript: ue_nominasmensuales('DIVIDIR');" <?php if($ls_divcon=="1"){ print " checked ";} print $ls_activo;?>></td>
              </tr>
              <tr>
                <td height="22">
                <div align="right">Clasificacion de Obreros </div></td>
                <td height="22"><div align="left">
                  <input name="chkracobrnom" type="checkbox" class="sin-borde" id="chkracobrnom" style=" width:15px; height:15px" value="1" onChange="javascript: ue_verificarrac('2');"  <?php if($ls_racobrnom=="1"){ print " checked ";} print $ls_activo;?>>
                </div></td>
                <td height="22"><div align="right">Validar Contabilizaci&oacute;n del periodo anterior</div></td>
                <td height="22"><div align="left">
                  <input name="chkconpernom" type="checkbox" class="sin-borde" id="chkconpernom" value="1" <?php if($ls_conpernom=="1"){ print " checked ";} ?>>
                </div></td>
              </tr>
              <tr>
                <td height="22"><div align="right">Nomina Especial </div></td>
                <td height="22"><div align="left">
                  <input name="chkespnom" type="checkbox" class="sin-borde" id="chkespnom" style=" width:15px; height:15px" value="1"  onChange="javascript: ue_desmarcarcestaticket();" <?php if($ls_espnom=="1"){ print " checked ";} print $ls_activo; ?>>
                </div></td>
                <td height="22"><div align="right">Contabilizaci&oacute;n de N&oacute;minas por Proyecto </div></td>
                <td height="22"><div align="left">
                  <input name="chkconpronom" type="checkbox" class="sin-borde" id="chkconpronom" style=" width:15px; height:15px" value="1" <?php if($ls_conpronom=="1"){ print " checked ";} print $ls_activo; ?>>
                </div></td>
              </tr>
              <tr>
                <td height="22"><div align="right">T&iacute;tulo en el reporte </div></td>
                <td height="22" colspan="3"><input name="txttitrepnom" type="text" id="txttitrepnom" value="<?php print $ls_titrepnom;?>" size="80" maxlength="50"></td>
              </tr>
              <tr>
                <td height="22"><div align="right">Informaci&oacute;n para Reporte </div></td>
                <td height="22" colspan="3"><div align="left">
                  <textarea name="txtinforma" cols="100" onKeyUp="ue_validarcomillas(this);" rows="6" id="txtinforma"><?php  print $ls_informa; ?>
                </textarea>
                </div></td>
              </tr>
              <tr>
                <td height="22" colspan="4" class="titulo-celdanew">N&oacute;mina de Cesta Ticket (N&oacute;mina Especial) </td>
              </tr>
              <tr>
                <td height="22"><div align="right">Nomina de Cesta Ticket</div></td>
                <td height="22"><div align="left">
                  <input name="chkctnom" type="checkbox" class="sin-borde" id="chkctnom" style=" width:15px; height:15px" value="1" onChange="javascript: ue_verificar();" <?php if($ls_ctnom=="1"){ print " checked ";} print $ls_activo; ?>>
                </div></td>
                <td height="22"><div align="right">M&eacute;todo de Cesta Ticket </div></td>
                <td height="22"><div align="left">
                  <input name="txtctmetnom" type="text" id="txtctmetnom" value="<?php print $ls_ctmetnom;?>" size="5" maxlength="2" readonly>
                  <a href="javascript: ue_buscarmetodoct();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
              </tr>
              <tr>
                <td height="22"><div align="right">C&oacute;digo de Organismo </div></td>
                <td height="22"><label>
                  <input name="txtcodorgcestic" type="text" id="txtcodorgcestic" size="6" maxlength="4" onKeyUp="javascript: ue_verificar();" onKeyPress="javascript: ue_validarcomillas(this);" value="<?php print $ls_codorgcestic; ?>" >
                </label></td>
                <td height="22"><div align="right"></div></td>
                <td height="22">&nbsp;</td>
              </tr>
              <tr>
                <td height="22" colspan="4">&nbsp;</td>
              </tr>
              <tr class="titulo-celdanew">
                <td height="20" colspan="4"><div align="center" class="titulo-celdanew">Configuraci&oacute;n Contabilizaci&oacute;n de N&oacute;mina </div></td>
            </tr>
				<tr>
				  <td height="22"><div align="right">N&oacute;mina</div></td>
				  <td height="22" colspan="3"><select name="cmbconsulnom" id="cmbconsulnom" onChange="javascript: ue_contabilizacionnomina();" <?php print $ls_activo_contabilizacion;?>>
                    <option value="CP" <?php print $la_consulnom[0]; ?>>Causar y Pagar</option>
                    <option value="OCP" <?php print $la_consulnom[1]; ?>>Compromete, Causa y Paga</option>
                    <option value="OC" <?php print $la_consulnom[2]; ?>>Compromete y Causa</option>
                    <option value="O" <?php print $la_consulnom[3]; ?>>Compromete</option>
                  </select>	</td>
		  </tr>
				<tr>
				  <td height="22"><div align="right">Generar Recepci&oacute;n de Documento a la N&oacute;mina</div></td>
				  <td height="22"><div align="left">
				    <input name="chkrecdocnom" type="checkbox" class="sin-borde" id="chkrecdocnom" value="1" onChange="javascript: ue_recepcionnomina();" <?php if($ls_recdocnom=="1"){ print " checked ";} print $ls_activo_contabilizacion;?>>
			      </div></td>
		          <td height="22"><div align="right">Tipo de Documento N&oacute;mina</div></td>
		          <td height="22"><div align="left">
		            <input name="txttipdocnom" type="text" id="txttipdocnom" value="<?php print $ls_tipdocnom;?>" readonly>
	              <a href="javascript: ue_buscartipodocumento('NOMINA');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
		  </tr>
              <tr>
                <td height="22"><div align="right">
                  <div align="right">Cuenta Contable</div>
                </div></td>
                <td height="22"><div align="left">
                  <input name="txtcueconnom" type="text" id="txtcueconnom" value="<?php print $ls_cueconnom;?>" readonly>
                  <a href="javascript: ue_buscarcuentacontable('NOMINA');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
                <td height="22"><div align="right">Generar Nota D&eacute;bito en bancos</div></td>
                <td height="22"><div align="left">
                  <input name="chknotdebnom" type="checkbox" class="sin-borde" id="chknotdebnom" value="1" onChange="javascript: ue_notadebito();" <?php if($ls_notdebnom=="1"){ print " checked ";} print $ls_activo_contabilizacion;?>>
                </div></td>
              </tr>
              <tr>
                <td height="22"><div align="right">Destino Contabilizaci&oacute;n</div></td>
                <td height="22" colspan="3"><div align="left">
                  <select name="cmbdesconnom" id="cmbdesconnom" onChange="javascript: ue_limpiar();" <?php print $ls_activo_contabilizacion; ?>>
                    <option value=" " <?php print $la_descomnom[0]; ?>> </option>
                    <option value="P" <?php print $la_descomnom[1]; ?>>PROVEEDOR</option>
                    <option value="B" <?php print $la_descomnom[2]; ?>>BENEFICIARIO</option>
                  </select>
                  <input name="txtcodproben" type="text" id="txtcodproben" value="<?php if(substr($ls_descomnom,0,1)=="P"){ print $ls_codpronom;} if(substr($ls_descomnom,0,1)=="B"){ print $ls_codbennom;} ?>" readonly>
                  <a href="javascript: ue_buscardestino();"><img  src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
                  <input name="txtnombre" type="text" class="sin-borde" id="txtnombre" size="50" maxlength="30" readonly>
</div>                  <div align="right"></div>                  <div align="left"><a href="javascript: ue_buscartipodocumento('NOMINA');"></a></div></td>
              </tr>			  
		  <tr>
		   <td height="22"><div align="right">Utilizar Cuenta Contable para el registro del Gasto por pagar</div>          </td>
		  <td>
            <div align="left">
              <input name="chkestctaalt" type="checkbox" class="sin-borde" id="chkestctaalt" value="1" <?php  if($li_estctaalt=="1"){print "checked";} ?>  onClick="javascript:ue_chequear_nomina_beneficiario();">          
            </div></td>			
			<tr>  
			    <tr>
		   <td height="22"><div align="right">Generar Recepci&oacute;n de Documento para el Pago del Personal con Cheque</div>          </td>
		  <td>
            <div align="left">
              <input name="chkgenrecdocpagper" type="checkbox" class="sin-borde" id="chkgenrecdocpagper" value="1" <?php  if($li_genrecdocpagperche=="1"){print "checked";} ?>>          
            </div></td>
			<td height="22"><div align="right">Tipo de Documento del Pago de Personal</div></td>
		  <td>
		    <input name="txttipdocpagper" type="text" id="txttipdocpagper" value="<?php print $ls_tipdocpagperche;?>" readonly>
		    <a href="javascript: ue_buscartipodocumento('PAGOPERSONAL');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a> </td>
		  </tr>
                <tr class="titulo-celdanew">
                  <td height="22" colspan="4"><div align="center" class="titulo-celdanew">Configuraci&oacute;n Contabilizaci&oacute;n de Aportes </div></td>
                </tr>
              <tr>
                <td height="22"><div align="right">Aportes</div></td>
                <td height="22" colspan="3"><div align="left">
                  <select name="cmbconaponom" id="cmbconaponom" onChange="javascript: ue_contabilizacionaportes();"  <?php print $ls_activo_contabilizacion; ?>>
                    <option value="CP" <?php print $la_conaponom[0]; ?>>Causar y Pagar</option>
                    <option value="OCP" <?php print $la_conaponom[1]; ?>>Compromete, Causa y Paga</option>
                    <option value="OC" <?php print $la_conaponom[2]; ?>>Compromete y Causa</option>
                    <option value="O" <?php print $la_conaponom[3]; ?>>Compromete</option>
                  </select>
                </div></td>
              </tr>
              <tr>
                <td height="22"><div align="right">Generar Recepci&oacute;n de Documento a los aportes</div></td>
                <td height="22"><div align="left">
                  <input name="chkrecdocapo" type="checkbox" class="sin-borde" id="chkrecdocapo" value="1" onChange="javascript: ue_recepcionaportes();"  <?php if($ls_recdocapo=="1"){ print " checked ";} print $ls_activo_contabilizacion; ?>>
                </div></td>
                <td height="22"><div align="right">Tipo de Documento Aporte</div></td>
                <td height="22"><div align="left">
                  <input name="txttipdocapo" type="text" id="txttipdocapo" value="<?php print $ls_tipdocapo;?>" readonly>
                <a href="javascript: ue_buscartipodocumento('APORTE');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
              </tr>
              <tr>
                <td height="22" colspan="4" class="titulo-celdanew">C&oacute;nfiguraci&oacute;n Prestaci&oacute;n Antiguedad </td>
              </tr>
              <tr>
                <td height="22"><div align="right">Prestaci&oacute;n Antiguedad </div></td>
                <td height="22" colspan="3"><div align="left">
                  <select name="cmbconfidnom" id="cmbconfidnom" onChange="javascript: ue_contabilizacionfideicomiso();" <?php print $ls_activo_contabilizacion; ?>>
                    <option value="OC" <?php print $la_confidnom[0]; ?>>Compromete y Causa</option>
                  </select>
                </div></td>
              </tr>
              <tr>
                <td height="22"><div align="right">Generar Recepcion de Documentos </div></td>
                <td height="22"><div align="left">
                  <input name="chkrecdocfid" type="checkbox" class="sin-borde" id="chkrecdocfid" value="1" onChange="javascript: ue_recepcionfideicomiso();" <?php if($ls_recdocfid=="1"){ print " checked ";} print $ls_activo_contabilizacion;?>>
                </div></td>
                <td height="22"><div align="right">Tipo de Documento </div></td>
                <td height="22"><div align="left">
                  <input name="txttipdocfid" type="text" id="txttipdocfid" value="<?php print $ls_tipdocfid;?>" readonly>
                <a href="javascript: ue_buscartipodocumento('FIDEICOMISO');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a> </div></td>
              </tr>
              <tr>
                <td height="22"><div align="right">Beneficiario</div></td>
                <td height="22" colspan="3"><div align="left">
                  <div align="left">
                    <input name="txtcodbenfid" type="text" id="txtcodbenfid" value="<?php print $ls_codbenfid; ?>" readonly>
                    <a href="javascript: ue_buscarbeneficiario();"><img  src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>                  </div>
                </div></td>
              </tr>
              <tr>
                <td height="22"><div align="right">Cuenta Contable </div></td>
                <td height="22" colspan="3"><div align="left">
                  <input name="txtcueconfid" type="text" id="txtcueconfid" value="<?php print $ls_cueconfid;?>" readonly>
                <a href="javascript: ue_buscarcuentacontable('FIDEICOMISO');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
              </tr>
              <tr>
                <td height="22">&nbsp;</td>
                <td height="22" colspan="3">&nbsp;</td>
              </tr>
              <tr>
                <td height="22">&nbsp;</td>
                <td height="22" colspan="3"><div align="left">
                  <input name="botsubnom" type="button" class="boton" id="botsubnom" onClick="javascript: uf_subnomina(document.form1.txtcodnom.value)" value="SubNomina" <?php  print $ls_disabled_subnom; ?>>
                  <input name="botpernom" type="button" class="boton" id="botpernom"  onClick="javascript: uf_periodo(document.form1.txtcodnom.value)" value="Periodo" <?php  print $ls_disabled; ?>>
				  <input name="botestper" type="button" class="boton" id="botestper"  onClick="javascript: uf_establecer(document.form1.txtcodnom.value)" value="Establecer Per&iacute;odo" <?php  print $ls_disabled_establecer; ?>>
				  <input name="botperadi" type="button" class="boton" id="botperadi"  onClick="javascript: uf_periodoadicional(document.form1.txtcodnom.value)" value="Crear Periodo Adicional " <?php  print $ls_disabled; ?>>
                </div></td>
              </tr>
          </table>
            <p align="center">
              <input name="operacion" type="hidden" id="operacion">
              <input name="existe" type="hidden" id="existe" value="<?php  print $ls_existe; ?>">
              <input name="activo" type="hidden" id="activo" value="<?php  print $ls_activo_contabilizacion; ?>">
          </p>
        </td>
      </tr>
  </table>
</form>  
</div>
</body>
<script language="javascript">
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);

function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		f.operacion.value="NUEVO";
		f.existe.value="FALSE";		
		f.action="sigesp_snorh_d_nominas.php";
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
		codnom = ue_validarvacio(f.txtcodnom.value);
		desnom = ue_validarvacio(f.txtdesnom.value);
		tippernom = ue_validarvacio(f.cmbtippernom.value);
		despernom = ue_validarvacio(f.txtdespernom.value);
		anocurnom = ue_validarvacio(f.txtanocurnom.value);
		fecininom = ue_validarvacio(f.txtfecininom.value);
		tipnom = ue_validarvacio(f.cmbtipnom.value);
		desconnom = ue_validarvacio(f.cmbdesconnom.value);
		codproben = ue_validarvacio(f.txtcodproben.value);
		if ((codnom!="")&&(desnom!="")&&(tippernom!="")&&(despernom!="")&&(anocurnom!="")&&(fecininom!="")&&
			(tipnom!="")&&(desconnom!="")&&(codproben!=""))
		{
			if(f.chkctnom.checked==true)
			{
				if(f.txtctmetnom.value!="")
				{
					f.operacion.value="GUARDAR";
					f.action="sigesp_snorh_d_nominas.php";
					f.submit();
				}
				else
				{
					alert("Debe Seleccionar un método de Cesta Ticket");
				}
			}
			else
			{
				f.operacion.value="GUARDAR";
				f.action="sigesp_snorh_d_nominas.php";
				f.submit();
			}
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
			codnom = ue_validarvacio(f.txtcodnom.value);
			if (codnom!="")
			{
				if(confirm("¿Desea eliminar el Registro actual?"))
				{
					f.operacion.value="ELIMINAR";
					f.action="sigesp_snorh_d_nominas.php";
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
{	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		window.open("sigesp_snorh_cat_nomina.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
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
	//window.open("../hlp/index.php?sistema=SNO&subsistema=SNR&nomfis=sno/sigesp_hlp_snr_profesion.php","Ayuda","menubar=no,toolbar=no,scrollbars=yes,width="+width+",height="+height+",resizable=yes,location=no");
}

function uf_establecer(cod)
{
	f=document.form1;
	if (cod=="")
	{
		alert("Debe seleccionar previamente una Nomina");
	}
	else
	{
		ls_nombre=f.txtdesnom.value;  
		location.href="sigesp_snorh_d_establecer_periodo.php?codnom="+cod+"&desnom="+ls_nombre;
	}	
}

function uf_periodoadicional(cod)
{
	f=document.form1;
	if (cod=="")
	{
		alert("Debe seleccionar previamente una Nomina");
	}
	else
	{
		if(f.txtperactnom.value=="000")
		{
			ls_nombre=f.txtdesnom.value;
			ls_tippernom=f.cmbtippernom.value;
			if (ls_tippernom=='3')
			{
				alert('Las nóminas con periodo anual no se le pueden crear periodos adicionales');
			}
			else
			{ 
				location.href="sigesp_snorh_d_periodoadicional.php?codnom="+cod+"&desnom="+ls_nombre+"&tippernom="+ls_tippernom;
			}
		}
		else
		{
			alert("El Periodo Actual de la nómina debe estar en '000' para poder utilizar esta opción. ");
		}
	}	
}

function uf_subnomina(cod)
{
	f=document.form1;
	if (cod=="")
	{
		alert("Debe seleccionar previamente una Nomina");
	}
	else
	{
		ls_nombre=f.txtdesnom.value;  
		location.href="sigesp_snorh_d_subnomina.php?codnom="+cod+"&desnom="+ls_nombre;
	}
}

function uf_periodo(cod)
{
	f=document.form1;
	if (cod=="")
	{
		alert("Debe seleccionar previamente una Nomina");
	}
	else
	{
		ls_nombre=f.txtdesnom.value;  
		pagina="sigesp_snorh_periodo.php?txtcodigo="+cod+"&txtdenominacion="+ls_nombre;
		window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=500,left=20,top=20,resizable=yes,location=no");
	}
}

function ue_buscarmetodoct()
{
	f=document.form1;
	if((f.chkctnom.checked)&&(f.chkespnom.checked))
	{
		window.open("sigesp_snorh_cat_ct.php?tipo=nomina","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
}

function ue_contabilizacionnomina()
{
	f=document.form1;
	if(f.activo.value=="")
	{
		f.chkrecdocnom.checked=false;
		f.txttipdocnom.value="";
		f.txtcueconnom.value="";
		f.chknotdebnom.checked=false;
	}
}

function ue_recepcionnomina()
{
	f=document.form1;
	if(f.activo.value=="")
	{
		consulnom=ue_validarvacio(f.cmbconsulnom.value);
		if((consulnom!="OC"))
		{
			f.chkrecdocnom.checked=false;
		}
		else
		{
			f.txttipdocnom.value="";
			f.txtcueconnom.value="";
		}
	}
}

function ue_buscartipodocumento(tipo)
{
	f=document.form1;
	valido=false;
	if(f.activo.value=="")
	{
		if(tipo=="NOMINA")
		{
			if(f.chkrecdocnom.checked)
			{
				valido=true;
			}
		}
		if(tipo=="APORTE")
		{
			if(f.chkrecdocapo.checked)
			{
				valido=true;
			}
		}
		if(tipo=="FIDEICOMISO")
		{
			if(f.chkrecdocfid.checked)
			{
				valido=true;
			}
		}
		if(tipo=="PAGOPERSONAL")
		{
			if(f.chkgenrecdocpagper.checked)
			{
				valido=true;
			}
		}
	}
	if(valido)
	{
		window.open("sigesp_snorh_cat_tipodocumento.php?tipo="+tipo+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
}

function ue_buscarcuentacontable(tipo)
{
	f=document.form1;
	if(f.activo.value=="")
	{
		if(tipo=='NOMINA')
		{
			if(f.chkrecdocnom.checked==false)
			{
				consulnom=ue_validarvacio(f.cmbconsulnom.value);
				if((consulnom=="OC"))
				{
					window.open("sigesp_sno_cat_cuentacontable.php?tipo="+tipo,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
				}
			}
		}
		else
		{
			if(f.chkrecdocfid.checked==false)
			{
				window.open("sigesp_sno_cat_cuentacontable.php?tipo="+tipo,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
			}
		}
	}
}

function ue_notadebito()
{
	f=document.form1;
	if(f.activo.value=="")
	{
		consulnom=ue_validarvacio(f.cmbconsulnom.value);
		if((consulnom=="OCP")||(consulnom=="CP"))
		{
			//f.chknotdebnom.checked=true;
		}
		else
		{
			f.chknotdebnom.checked=false;	
		}
	}
}

function ue_buscardestino()
{
	f=document.form1;
	if(f.activo.value=="")
	{
		descon=ue_validarvacio(f.cmbdesconnom.value);
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

function ue_buscarbeneficiario()
{
	f=document.form1;
	if(f.activo.value=="")
	{
		window.open("sigesp_catdinamic_bene.php?tipo=FIDEICOMISO","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
}

function ue_limpiar()
{
	f=document.form1;
	if(f.activo.value=="")
	{
		f.txtcodproben.value="";
		f.txtnombre.value="";
	}
}

function ue_contabilizacionaportes()
{
	f=document.form1;
	if(f.activo.value=="")
	{
		f.chkrecdocapo.checked=false;
		f.txttipdocapo.value="";
	}
}

function ue_recepcionaportes()
{
	f=document.form1;
	if(f.activo.value=="")
	{
		conaponom=ue_validarvacio(f.cmbconaponom.value);
		if((conaponom!="OC"))
		{
			f.chkrecdocapo.checked=false;
			f.txttipdocapo.value="";
		}
		else
		{
			f.txttipdocapo.value="";
		}
	}
}

function ue_contabilizacionfideicomiso()
{
	f=document.form1;
	if(f.activo.value=="")
	{
		f.chkrecdocfid.checked=false;
		f.txttipdocfid.value="";
	}
}

function ue_recepcionfideicomiso()
{
	f=document.form1;
	if(f.activo.value=="")
	{
		confidnom=ue_validarvacio(f.cmbconfidnom.value);
		if((confidnom!="OC"))
		{
			f.chkrecdocfid.checked=false;
		}
		else
		{
			f.txttipdocfid.value="";
			f.txtcueconfid.value="";
		}
	}
}

function ue_verificar()
{
	f=document.form1;
	if(!(f.chkespnom.checked))
	{
		f.chkctnom.checked=false;	
		f.txtcodorgcestic.value="";
	}
}

function ue_desmarcarcestaticket()
{
	f=document.form1;
	f.chkctnom.checked=false;	
	f.txtctmetnom.value="";
	f.txtcodorgcestic.value="";
}

function ue_nominasmensuales(tipo)
{
	f=document.form1;
	tippernom=ue_validarvacio(f.cmbtippernom.value);
	if(tipo=='QUITAR')
	{
		f.chkdivcon.checked=false;
		f.chkadenom.checked=false;
	}
	else
	{
		if((tippernom!="2"))
		{
			alert("Esta opción es solo para nóminas mensuales");
			f.chkadenom.checked=false;
			f.chkdivcon.checked=false;
		}
		else
		{
			if(tipo=='ADELANTO')
			{
				f.chkdivcon.checked=false;
			}
			else
			{
				f.chkadenom.checked=false;
			}
		}
	}
}

function ue_verificarrac(tipo)
{
	f=document.form1;
	if(tipo==0)
	{
		f.chkracnom.checked=false;
		f.chkracobrnom.checked=false;
	}
	tipnom=ue_validarvacio(f.cmbtipnom.value);
	if((tipnom=='3')||(tipnom=='4'))
	{
		if (tipo==1)
		{
			if(f.chkracnom.checked==true)
			{
				f.chkracobrnom.checked=false;
			}
		}
		if (tipo==2)
		{
			if(f.chkracobrnom.checked==true)
			{
				f.chkracnom.checked=true;
			}
		}
	}
	else
	{
		f.chkracobrnom.checked=false;
	}
}

function ue_chequear_nomina_beneficiario()
{
	f=document.form1;
	if(((f.cmbconsulnom.value!="OC")||(f.chkrecdocnom.checked==false))&&(f.chkestctaalt.checked))
	{
		alert("Esta Opción es valida solo para Nóminas Compromete y Causa que Generen Recepción de Documento.");
		f.chkestctaalt.checked=false;
	}
}

</script>
<script language="javascript1.2" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>