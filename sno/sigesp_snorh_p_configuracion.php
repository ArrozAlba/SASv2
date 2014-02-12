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
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_p_configuracion.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_select_campos()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_campos
		//		   Access: private
		//	  Description: Función que selecciona todos los campos de configuración
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/04/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $io_sno,$io_fun_nomina,$li_vac_reportar,$ls_vac_codconvac,$la_vac_metban,$ls_vac_metban,$li_vac_desincorporar,$li_salvacper;
		global $ls_est_codconcsuec,$la_est_estnom,$ls_est_estnom,$la_est_ordcons,$ls_est_ordcons,$la_est_ordconc,$ls_est_ordconc;
		global $la_est_estrec,$ls_est_estrec,$li_est_numlin,$la_est_prilpt,$ls_est_prilpt,$la_est_agrsem,$ls_est_agrsem,$li_con_parnom,$li_estctaalt;
		global $la_con_consue,$ls_con_consue,$ls_con_cuecon,$la_con_conapo,$ls_con_conapo,$la_con_conpro,$ls_con_conpro, $li_con_agrcon;
		global $li_con_gennotdeb,$li_con_genvou,$la_con_descon,$ls_con_descon,$li_par_excpersus,$li_par_perrep,$ld_par_fecfinano;
		global $la_par_metcalfid,$ls_par_metcalfid,$ls_fpj_codorgfpj,$ls_fpj_codconcfpj,$la_fpj_metfpj,$ls_fpj_metfpj,$ls_lph_codconclph;
		global $la_lph_metlph,$ls_lph_metlph,$ls_fpa_codconcfpa,$la_fpa_metfpa,$ls_fpa_metfpa,$li_fps_antcom,$li_fps_fraali;
		global $la_fps_metfps,$ls_fps_metfps,$ls_man_cueconc,$ls_man_cueconccaj,$li_man_actblofor,$li_man_actblocalnom;
		global $la_man_metrescon,$ls_man_metrescon,$la_dis_metdisnom,$ls_dis_metdisnom,$li_con_genrecdoc,$li_con_genrecdocapo;
		global $ls_con_tipdocnom,$ls_con_tipdocapo,$ls_ipas_codorgipas,$ls_ipas_codconcahoipas,$ls_ipas_codconcseripas;  
		global $ls_ipas_conhipespipas,$ls_ipas_conhipampipas,$ls_ipas_conhipconipas,$ls_ipas_conhiphipipas,$ls_ipas_conhiplphipas;
		global $ls_ipas_conhipvivipas,$ls_ipas_conperipas,$ls_ipas_conturipas,$ls_ipas_conproipas,$ls_ipas_conasiipas;
		global $ls_ipas_convehipas,$ls_ipas_concomipas,$ls_ivss_numemp,$li_vac_desincorporar,$ls_par_concsuelant;
		global $la_par_confpre,$ls_par_confpre,$li_par_camuniadm,$li_par_campasogrado,$li_par_incperben,$ls_par_cueconben,$li_par_codunirac,$li_par_camdedtipper;
		global $li_par_comautrac,$li_par_ajusuerac, $li_par_modpensiones,$li_par_loncueban, $li_par_valloncueban, $li_par_valporpre;
		global $ls_con_confidnom,$la_con_confidnom,$ls_con_recdocfid,$ls_con_tipdocfid,$ls_con_cueconfid,$ls_con_codbenfid;
		global $ls_ivss_metodo,$la_ivss_metodo, $li_par_alfnumcodper, $li_con_parfpj, $ls_edadM, $ls_edadF, $ls_anoM, $ls_anoT;
		global $li_prestamo, $li_par_campsuerac, $li_fps_intasiextra,$ls_sueint,$li_persobregiro,$li_genrecdocpagperche,$ls_tipdocpagperche,$ls_readonly;
		global $li_fps_incvacagui;
		
		
		//-------------------------------------SUELDO INTEGRAL--------------------------------------------------
		$ls_sueint=trim($io_sno->uf_select_config("SNO","NOMINA","DENOMINACION SUELDO INTEGRAL","C",""));		
		if ($ls_sueint=="")
		{
			$ls_readonly="readonly";
		}
		else
		{
			$ls_readonly="";
		}
		//-----------------------------------------------------------------------------------------------------

		//-------------------------------------VACACIONES------------------------------------------------------
		$li_vac_reportar=trim($io_sno->uf_select_config("SNO","NOMINA","MOSTRAR VACACION","0","C"));
		$li_salvacper=trim($io_sno->uf_select_config("SNO","NOMINA","SALIDA VACACION","0","C"));
		$ls_vac_codconvac=trim($io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO VACACION","","C"));
		$li_vac_desincorporar=trim($io_sno->uf_select_config("SNO","NOMINA","DESINCORPORAR DE NOMINA","0","C"));
		$la_vac_metban[0]="";
		$la_vac_metban[1]="";
		$la_vac_metban[2]="";
		$la_vac_metban[3]="";
		$la_vac_metban[4]="";
		$ls_vac_metban=trim($io_sno->uf_select_config("SNO","CONFIG","METODO_VACACIONES","0","C"));
		$io_fun_nomina->uf_seleccionarcombo("0-1-2-3-4",$ls_vac_metban,$la_vac_metban,5);
		//-----------------------------------------------------------------------------------------------------

		//--------------------------------------ESTILO DE NÓMINA-----------------------------------------------
		$ls_est_codconcsuec=trim($io_sno->uf_select_config("SNO","NOMINA","SNO COD SUELDO","0000SUELDO","C"));
		$la_est_estnom[0]="";
		$la_est_estnom[1]="";
		$la_est_estnom[2]="";
		$ls_est_estnom=trim($io_sno->uf_select_config("SNO","NOMINA","REP NOMINA","NORMAL","C"));
		$io_fun_nomina->uf_seleccionarcombo("NORMAL-CNU-SEAM",$ls_est_estnom,$la_est_estnom,3);
		$la_est_ordcons[0]="";
		$la_est_ordcons[1]="";
		$la_est_ordcons[2]="";
		$la_est_ordcons[3]="";
		$ls_est_ordcons=trim($io_sno->uf_select_config("SNO","CONFIG","ORDEN CONSTANTE","CODIGO","C"));
		$io_fun_nomina->uf_seleccionarcombo("CODIGO-NOMBRE-APELLIDO-UNIDAD",$ls_est_ordcons,$la_est_ordcons,3);
		$la_est_ordconc[0]="";
		$la_est_ordconc[1]="";
		$la_est_ordconc[2]="";
		$la_est_ordconc[3]="";
		$ls_est_ordconc=trim($io_sno->uf_select_config("SNO","CONFIG","ORDEN CONCEPTO","CODIGO","C"));
		$io_fun_nomina->uf_seleccionarcombo("CODIGO-NOMBRE-APELLIDO-UNIDAD",$ls_est_ordconc,$la_est_ordconc,3);
		$la_est_estrec[0]="";
		$la_est_estrec[1]="";
		$la_est_estrec[2]="";
		$ls_est_estrec=trim($io_sno->uf_select_config("SNO","NOMINA","REP RECIBOS","NORMAL","C"));
		$io_fun_nomina->uf_seleccionarcombo("NORMAL-CNU-SEAM",$ls_est_estrec,$la_est_estrec,3);
		$li_est_numlin=trim($io_sno->uf_select_config("SNO","NOMINA","REP RECIBO LINEAS","","C"));
		$la_est_prilpt[0]="";
		$la_est_prilpt[1]="";
		$ls_est_prilpt=trim($io_sno->uf_select_config("SNO","PRINT","RECIBOS","WINDOWS","C"));
		$io_fun_nomina->uf_seleccionarcombo("WINDOWS-GOBERNACION PORTUGUESA",$ls_est_prilpt,$la_est_prilpt,2);
		$la_est_agrsem[0]="";
		$la_est_agrsem[1]="";
		$la_est_agrsem[2]="";
		$ls_est_agrsem=trim($io_sno->uf_select_config("SNO","NOMINA","NOM_SEM_SR","2","C"));
		$io_fun_nomina->uf_seleccionarcombo("1-2-3",$ls_est_agrsem,$la_est_agrsem,3);
		//-----------------------------------------------------------------------------------------------------

		//-------------------------------------CONTABILIZACIÓN-------------------------------------------------
		$li_con_parnom=trim($io_sno->uf_select_config("SNO","CONFIG","CONTA GLOBAL","0","I"));
		$la_con_consue[0]="";
		$la_con_consue[1]="";
		$la_con_consue[2]="";
		$la_con_consue[3]="";
		$ls_con_consue=trim($io_sno->uf_select_config("SNO","NOMINA","CONTABILIZACION","OCP","C"));
		$io_fun_nomina->uf_seleccionarcombo("CP-OCP-OC-O",$ls_con_consue,$la_con_consue,4);
		$ls_con_cuecon=trim($io_sno->uf_select_config("SNO","CONFIG","CTA.CONTA","XXXXXXXXXXXXX","C"));
		$la_con_conapo[0]="";
		$la_con_conapo[1]="";
		$la_con_conapo[2]="";
		$la_con_conapo[3]="";
		$ls_con_conapo=trim($io_sno->uf_select_config("SNO","NOMINA","CONTABILIZACION APORTES","OCP","C"));
		$io_fun_nomina->uf_seleccionarcombo("CP-OCP-OC-O",$ls_con_conapo,$la_con_conapo,4);
		$la_con_conpro[0]="";
		$la_con_conpro[1]="";
		$ls_con_conpro=trim($io_sno->uf_select_config("SNO","SPG","CONTABILIZACION","UBICACION ADMINISTRATIVA","C"));
		$io_fun_nomina->uf_seleccionarcombo("UBICACION ADMINISTRATIVA-CONCEPTOS",$ls_con_conpro,$la_con_conpro,2);
		$li_con_agrcon=trim($io_sno->uf_select_config("SNO","NOMINA","AGRUPARCONTA","0","I"));
		$li_con_gennotdeb=trim($io_sno->uf_select_config("SNO","CONFIG","GENERAR NOTA DEBITO","1","I"));
		$li_con_genvou=trim($io_sno->uf_select_config("SNO","CONFIG","VOUCHER GENERAR","1","I"));
		$li_con_genrecdoc=trim($io_sno->uf_select_config("SNO","CONFIG","GENERAR RECEPCION DOCUMENTO","0","I"));
		$li_con_genrecdocapo=trim($io_sno->uf_select_config("SNO","CONFIG","GENERAR RECEPCION DOCUMENTO APORTE","0","I"));
		$ls_con_tipdocnom=trim($io_sno->uf_select_config("SNO","CONFIG","TIPO DOCUMENTO NOMINA","","C"));
		$ls_con_tipdocapo=trim($io_sno->uf_select_config("SNO","CONFIG","TIPO DOCUMENTO APORTE","","C"));
		$la_con_descon[0]="";
		$la_con_descon[1]="";
		$la_con_descon[2]="";
		$ls_con_descon=trim($io_sno->uf_select_config("SNO","NOMINA","CONTABILIZACION DESTINO","","C"));
		switch (substr($ls_con_descon,0,1))
		{
			case "P":
				$ls_con_descon=substr($ls_con_descon,1,strlen($ls_con_descon)-1);
				$ls_destino="P";
				break;
				
			case "B":
				$ls_con_descon=substr($ls_con_descon,1,strlen($ls_con_descon)-1);
				$ls_destino="B";
				break;
				
			default:
				$ls_con_descon=substr($ls_con_descon,1,strlen($ls_con_descon)-1);
				$ls_destino=" ";
		}
		$io_fun_nomina->uf_seleccionarcombo(" -P-B",$ls_destino,$la_con_descon,3);
		$ls_con_confidnom=$io_sno->uf_select_config("SNO","NOMINA","CONTABILIZACION FIDEICOMISO","OC","C");
		$la_con_confidnom[0]="";		
		$io_fun_nomina->uf_seleccionarcombo("OC",$ls_con_confidnom,$la_con_confidnom,1);
		$ls_con_recdocfid=$io_sno->uf_select_config("SNO","CONFIG","GENERAR RECEPCION DOCUMENTO FIDEICOMISO","0" ,"I");			
		$ls_con_tipdocfid=$io_sno->uf_select_config("SNO","CONFIG","TIPO DOCUMENTO FIDEICOMISO","","C");			
		$ls_con_cueconfid=trim($io_sno->uf_select_config("SNO","CONFIG","CTA.CONTABLE_FIDEICOMISO","XXXXXXXXXXXXX","C"));
		$ls_con_codbenfid=trim($io_sno->uf_select_config("SNO","NOMINA","DESTINO FIDEICOMISO","----------","C"));
		$li_genrecdocpagperche=trim($io_sno->uf_select_config("SNO","CONFIG","GENERAR RECEPCION DOCUMENTO PAGO PERSONAL CHEQUE","0","I"));
		$ls_tipdocpagperche=trim($io_sno->uf_select_config("SNO","CONFIG","TIPO DOCUMENTO PAGO PERSONAL CHEQUE","","C"));	
		$li_estctaalt=trim($io_sno->uf_select_config("SNO","CONFIG","UTILIZAR_CTA.CONTA_REC_DOC_PROV_BEN","0","I"));		
		//-----------------------------------------------------------------------------------------------------

		//-------------------------------------PARÁMETROS------------------------------------------------------
		$li_par_excpersus=trim($io_sno->uf_select_config("SNO","CONFIG","EXCLUIR_SUSPENDIDOS","0","I"));
		$li_par_perrep=trim($io_sno->uf_select_config("SNO","CONFIG","NOPERMITIR_REPETIDOS","1","I"));
		$ld_par_fecfinano=trim($io_sno->uf_select_config("SNO","ANTIGUEDAD","FECHA_TOPE","","C"));
		$la_par_metcalfid[0]="";
		$la_par_metcalfid[1]="";
		$ls_par_metcalfid=trim($io_sno->uf_select_config("SNO","CONFIG","METODO FIDECOMISO","VERSION 2","C"));
		$io_fun_nomina->uf_seleccionarcombo("VERSION 2-VERSION CONSEJO",$ls_par_metcalfid,$la_par_metcalfid,2);
		$ls_par_concsuelant=trim($io_sno->uf_select_config("SNO","CONFIG","CONCEPTO_SUELDO_ANT","XXXXXXXXXX","C"));
		$la_par_confpre[0]="";
		$la_par_confpre[1]="";
		$ls_par_confpre=trim($io_sno->uf_select_config("SNO","CONFIG","CONFIGURACION_PRESTAMO","CUOTAS","C"));
		$io_fun_nomina->uf_seleccionarcombo("CUOTAS-MONTO",$ls_par_confpre,$la_par_confpre,2);
		$li_par_camuniadm=trim($io_sno->uf_select_config("SNO","CONFIG","CAMBIAR_UNIDAD_ADM_RAC","0","I"));
		$li_par_campasogrado=trim($io_sno->uf_select_config("SNO","CONFIG","CAMBIAR_PASO_GRADO_RAC","0","I"));
		$li_par_incperben=trim($io_sno->uf_select_config("SNO","CONFIG","INCLUIR_A_BENEFICIARIO","0","I"));
		$ls_par_cueconben=trim($io_sno->uf_select_config("SNO","CONFIG","CUENTA_CONTABLE_BENEFICIARIO","","C"));
		$li_par_codunirac=trim($io_sno->uf_select_config("SNO","CONFIG","CODIGO_UNICO_RAC","0","I"));
		$li_par_comautrac=trim($io_sno->uf_select_config("SNO","CONFIG","COMPENSACION_AUTOMATICA_RAC","1","I"));
		$li_par_ajusuerac=trim($io_sno->uf_select_config("SNO","CONFIG","AJUSTAR_SUELDO_RAC","0","I"));
		$li_par_modpensiones=trim($io_sno->uf_select_config("SNO","CONFIG","CAMBIAR_PENSIONES","0","I"));
		$li_par_loncueban=trim($io_sno->uf_select_config("SNO","CONFIG","LONGITUD_CUENTA_BANCO","0","I"));
		$li_par_valloncueban=trim($io_sno->uf_select_config("SNO","CONFIG","VALIDAR_LONGITUD_CUEBANCO","0","I"));
		$li_par_valporpre=trim($io_sno->uf_select_config("SNO","CONFIG","VAL_PORCENTAJE_PRESTAMO","1","I"));
		$li_par_alfnumcodper=trim($io_sno->uf_select_config("SNO","CONFIG","ALFNUM_CODPER","0","I"));
		$li_prestamo=trim($io_sno->uf_select_config("SNO","CONFIG","VAL_TIPO_PRESTAMO","0","I"));
		$li_par_campsuerac=trim($io_sno->uf_select_config("SNO","CONFIG","CAMBIAR_SUELDO_RAC","0","I"));
		$li_par_camdedtipper=trim($io_sno->uf_select_config("SNO","CONFIG","CAMBIAR_DEDICACION_TIPO_PERSONAL_RAC","0","I"));
		$li_persobregiro=trim($io_sno->uf_select_config("SNO","CONFIG","SOBREGIRO_CUENTAS_TRABAJADOR","0","I"));		
		//-----------------------------------------------------------------------------------------------------

		//-------------------------------------Aportes FPJ-----------------------------------------------------
		$ls_fpj_codorgfpj=trim($io_sno->uf_select_config("SNO","NOMINA","COD ORGANISMO FPJ","XXXXXXXX","C"));
		$ls_fpj_codconcfpj=trim($io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO FPJ","XXXXXXXX","C"));
		$la_fpj_metfpj[0]="";
		$la_fpj_metfpj[1]="";
		$ls_fpj_metfpj=trim($io_sno->uf_select_config("SNO","CONFIG","METODO FPJ","SUELDO NORMAL","C"));
		$io_fun_nomina->uf_seleccionarcombo("SUELDO NORMAL-SUELDO INTEGRAL",$ls_fpj_metfpj,$la_fpj_metfpj,2);
		$li_con_parfpj=trim($io_sno->uf_select_config("SNO","CONFIG","CONF JUB","0","I"));
		$ls_edadM=trim($io_sno->uf_select_config("SNO","NOMINA","EDADM","0","C"));
		$ls_edadF=trim($io_sno->uf_select_config("SNO","NOMINA","EDADF","0","C"));
		$ls_anoM=trim($io_sno->uf_select_config("SNO","NOMINA","ANOM","0","C"));
		$ls_anoT=trim($io_sno->uf_select_config("SNO","NOMINA","ANOT","0","C"));
		//-----------------------------------------------------------------------------------------------------

		//-------------------------------------Aportes LPH-----------------------------------------------------
		$ls_lph_codconclph=trim($io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO LPH","XXXXXXXXXX","C"));
		$la_lph_metlph[0]="";
		$la_lph_metlph[1]="";
		$la_lph_metlph[2]="";
		$la_lph_metlph[3]="";
		$la_lph_metlph[4]="";
		$la_lph_metlph[5]="";
		$la_lph_metlph[6]="";
		$la_lph_metlph[7]="";
		$la_lph_metlph[8]="";
		$la_lph_metlph[9]="";
		$la_lph_metlph[10]="";
		$la_lph_metlph[11]="";
		$la_lph_metlph[12]="";
		$la_lph_metlph[13]="";
		$la_lph_metlph[14]="";
		$la_lph_metlph[15]="";
		$la_lph_metlph[16]="";
		$ls_lph_metlph=trim($io_sno->uf_select_config("SNO","NOMINA","METODO LPH","SIN METODO","C"));
		$io_fun_nomina->uf_seleccionarcombo("SIN METODO-VIVIENDA-CASA PROPIA-MERENAP-MIRANDA-FONDO MUTUAL HABITACIONAL-BANESCO-MI CASA EAP-CANARIAS-VENEZUELA-DELSUR-MERCANTIL-CENTRAL-CAJA FAMILIA-FONDO_COMUN_EAP-BOD-BANAVIH",
											$ls_lph_metlph,$la_lph_metlph,17);
		//-----------------------------------------------------------------------------------------------------

		//-------------------------------------Aportes FPA-----------------------------------------------------
		$ls_fpa_codconcfpa=trim($io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO FPA","XXXXXXXXXX","C"));
		$la_fpa_metfpa[0]="";
		$la_fpa_metfpa[1]="";
		$la_fpa_metfpa[2]="";
		$la_fpa_metfpa[3]="";
		$ls_fpa_metfpa=trim($io_sno->uf_select_config("SNO","NOMINA","METODO FPA","SIN METODO","C"));
		$io_fun_nomina->uf_seleccionarcombo("SIN METODO-VENEZUELA-MERCANTIL-CENTRAL",$ls_fpa_metfpa,$la_fpa_metfpa,4);
		//-----------------------------------------------------------------------------------------------------

		//-------------------------------------Aportes FPS-----------------------------------------------------
		$li_fps_antcom=trim($io_sno->uf_select_config("SNO","NOMINA","COMPLEMENTO ANTIGUEDAD","0","I"));
		$li_fps_fraali=trim($io_sno->uf_select_config("SNO","NOMINA","FRACCION ALICUOTA","0","I"));
		$li_fps_intasiextra=trim($io_sno->uf_select_config("SNO","NOMINA","INT_ASIG_EXTRA","0","I"));
		$li_fps_incvacagui=trim($io_sno->uf_select_config("SNO","NOMINA","INC_VACACIONES_AGUINALDO","0","I"));
		$la_fps_metfps[0]="";
		$la_fps_metfps[1]="";
		$la_fps_metfps[2]="";
		$la_fps_metfps[3]="";
		$la_fps_metfps[4]="";
		$la_fps_metfps[5]="";
		$la_fps_metfps[6]="";
		$la_fps_metfps[7]="";
		$la_fps_metfps[8]="";	
		$la_fps_metfps[9]="";		
		$la_fps_metfps[10]="";	
		$la_fps_metfps[11]="";	
		$ls_fps_metfps=trim($io_sno->uf_select_config("SNO","CONFIG","METODO FPS","SIN METODO","C"));
		$io_fun_nomina->uf_seleccionarcombo("SIN METODO-CARIBE-UNION-MERCANTIL-VENEZOLANO DE CREDITO-BANCO DE VENEZUELA-VENEZUELA-BANCO PROVINCIAL-BANESCO-CENTRAL BANCO UNIVERSAL-DEL SUR-BANCO INDUSTRIAL",$ls_fps_metfps,$la_fps_metfps,12);
		//-----------------------------------------------------------------------------------------------------

		//-------------------------------------Mantenimiento---------------------------------------------------
		$ls_man_cueconc=trim($io_sno->uf_select_config("SNO","NOMINA","SPGCUENTA","401","C"));
		$ls_man_cueconccaj=trim($io_sno->uf_select_config("SNO","NOMINA","CTACAJA","","C"));
		$li_man_actblofor=trim($io_sno->uf_select_config("SNO","CONFIG","ACTIVAR_BLOQUEO","0","I"));
		$li_man_actblocalnom=trim($io_sno->uf_select_config("SNO","CONFIG","BLOQUEO_ACTIVAR","0","I"));
		$la_man_metrescon[0]="";
		$la_man_metrescon[1]="";
		$ls_man_metrescon=trim($io_sno->uf_select_config("SNO","CONFIG","METODO RESUMEN CONTABLE","SIN METODO","C"));
		$io_fun_nomina->uf_seleccionarcombo("SIN METODO-METODO CTA_ABONO",$ls_man_metrescon,$la_man_metrescon,2);
		//-----------------------------------------------------------------------------------------------------

		//-------------------------------------Disco Nómina----------------------------------------------------
		$la_dis_metdisnom[0]="";
		$la_dis_metdisnom[1]="";
		$la_dis_metdisnom[2]="";
		$la_dis_metdisnom[3]="";
		$la_dis_metdisnom[4]="";
		$ls_dis_metdisnom=trim($io_sno->uf_select_config("SNO","CONFIG","METODO GD NOMINA","SIN METODO","C"));
		$io_fun_nomina->uf_seleccionarcombo("SIN METODO-Excel-Cultura Excel-Metodo #2-Excel #2",$ls_dis_metdisnom,$la_dis_metdisnom,5);
		//-----------------------------------------------------------------------------------------------------

		//-------------------------------------Aportes IPASME-----------------------------------------------------
		$ls_ipas_codorgipas=trim($io_sno->uf_select_config("SNO","NOMINA","COD ORGANISMO IPAS","XXX","C"));
		$ls_ipas_codconcahoipas=trim($io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO AHORRO IPAS","XXXXXXXXXX","C"));
		$ls_ipas_codconcseripas=trim($io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO SERVICIO IPAS","XXXXXXXXXX","C"));
		$ls_ipas_conhipespipas=trim($io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO HIPOTECARIO ESPECIAL IPAS","XXXXXXXXXX","C"));
		$ls_ipas_conhipampipas=trim($io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO HIPOTECARIO AMLIACION IPAS","XXXXXXXXXX","C"));
		$ls_ipas_conhipconipas=trim($io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO HIPOTECARIO CONSTRUCCION IPAS","XXXXXXXXXX","C"));
		$ls_ipas_conhiphipipas=trim($io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO HIPOTECARIO HIPOTECA IPAS","XXXXXXXXXX","C"));
		$ls_ipas_conhiplphipas=trim($io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO HIPOTECARIO LPH IPAS","XXXXXXXXXX","C"));
		$ls_ipas_conhipvivipas=trim($io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO HIPOTECARIO VIVIENDA IPAS","XXXXXXXXXX","C"));
		$ls_ipas_conperipas=trim($io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO PERSONAL IPAS","XXXXXXXXXX","C"));
		$ls_ipas_conturipas=trim($io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO TURISTICOS IPAS","XXXXXXXXXX","C"));
		$ls_ipas_conproipas=trim($io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO PROVEEDURIA IPAS","XXXXXXXXXX","C"));
		$ls_ipas_conasiipas=trim($io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO ASISTENCIALES IPAS","XXXXXXXXXX","C"));
		$ls_ipas_convehipas=trim($io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO VEHICULOS IPAS","XXXXXXXXXX","C"));
		$ls_ipas_concomipas=trim($io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO COMERCIALES IPAS","XXXXXXXXXX","C"));		
		//-----------------------------------------------------------------------------------------------------
		
		//-------------------------------------IVSS-----------------------------------------------------
		$ls_ivss_numemp=trim($io_sno->uf_select_config("SNO","NOMINA","COD ORGANISMO IVSS","XXXXXXXXX","C"));			
		if ($ls_ivss_numemp=="XXXXXXXXX")
		{
			$ls_ivss_numemp=$io_sno->uf_numero_IVSS();					
		}		
		$la_ivss_metodo[0]="";
		$la_ivss_metodo[1]="";
		$ls_ivss_metodo=trim($io_sno->uf_select_config("SNO","CONFIG","METODO IVSS","SUELDO NORMAL","C"));
		$io_fun_nomina->uf_seleccionarcombo("SUELDO NORMAL-SUELDO INTEGRAL",$ls_ivss_metodo,$la_ivss_metodo,2);
		//-----------------------------------------------------------------------------------------------------
   }// end function uf_select_campos
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_load_variables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Función que obtiene el valor de los campos 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 05/04/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		global $li_vac_reportar,$ls_vac_codconvac,$ls_vac_metvac,$li_salvacper,$ls_est_codconcsue,$ls_est_estnom,$ls_est_ordcons,$ls_est_ordconc;
		global $ls_est_estrec,$li_est_numlin,$ls_est_prilpt,$ls_est_agrsem,$li_con_parnom,$ls_con_consue,$ls_con_cuecon,$ls_con_conapo;
		global $ls_con_conpro,$li_con_agrcon,$li_con_gennotdeb,$li_con_genvou,$ls_con_descon,$li_par_excpersus,$li_par_perrep;
		global $ld_par_fecfinano,$ls_par_metcalfid,$ls_fpj_codorgfpj,$ls_fpj_codconcfpj,$ls_fpj_metfpj,$ls_lph_codconclph,$ls_lph_metlph;
		global $ls_fpa_codconcfpa,$ls_fpa_metfpa,$li_fps_antcom,$li_fps_fraali,$ls_fps_metfps,$ls_man_cueconc,$ls_man_cueconccaj;
		global $li_man_actblofor,$li_man_actblocalnom,$ls_man_metrescon,$ls_dis_metdisnom,$li_con_genrecdoc,$li_con_genrecdocapo;
		global $ls_con_tipdocnom,$ls_con_tipdocapo,$io_fun_nomina,$ls_ipas_codorgipas,$ls_ipas_codconcahoipas,$ls_ipas_codconcseripas;
		global $ls_ipas_conhipespipas,$ls_ipas_conhipampipas,$ls_ipas_conhipconipas,$ls_ipas_conhiphipipas,$ls_ipas_conhiplphipas;
		global $ls_ipas_conhipvivipas,$ls_ipas_conperipas,$ls_ipas_conturipas,$ls_ipas_conproipas,$ls_ipas_conasiipas;
		global $ls_ipas_convehipas,$ls_ipas_concomipas,$ls_ivss_numemp,$li_vac_desincorporar,$ls_par_concsuelant,$ls_par_confpre;
		global $li_par_camuniadm,$li_par_campasogrado,$li_par_incperben,$ls_par_cueconben,$li_par_codunirac,$li_par_comautrac,$li_par_ajusuerac,$li_par_camdedtipper;
		global $li_par_loncueban, $li_par_valloncueban,$li_par_modpensiones,$li_par_valporpre;
		global $ls_con_confidnom,$ls_con_recdocfid,$ls_con_tipdocfid,$ls_con_cueconfid,$ls_con_codbenfid,$ls_ivss_metodo, $li_par_alfnumcodper;
		global $li_con_parfpj, $ls_edadM, $ls_edadF, $ls_anoM, $ls_anoT, $li_prestamo, $li_par_campsuerac;
		global $li_fps_intasiextra,$ls_sueint,$li_persobregiro, $li_genrecdocpagperche,$ls_tipdocpagperche,$li_estctaalt;
		global $li_fps_incvacagui;

		$li_vac_reportar=$io_fun_nomina->uf_obtenervalor("chkvacreportar","0");
		$li_salvacper=$io_fun_nomina->uf_obtenervalor("chksalvacper","0");	
		$li_vac_desincorporar=$io_fun_nomina->uf_obtenervalor("chkvacdesincorporar","0");	
		$ls_vac_codconvac=$io_fun_nomina->uf_obtenervalor("txtcodconvac","");	
		$ls_vac_metvac=$io_fun_nomina->uf_obtenervalor("cmbmetvac","");	
		$ls_est_codconcsue=$io_fun_nomina->uf_obtenervalor("txtcodconcsue","0000SUELDO");	
		$ls_est_estnom=$io_fun_nomina->uf_obtenervalor("cmbestnom","NORMAL");	
		$ls_est_ordcons=$io_fun_nomina->uf_obtenervalor("cmbordcons","CODIGO");	
		$ls_est_ordconc=$io_fun_nomina->uf_obtenervalor("cmbordconc","CODIGO");	
		$ls_est_estrec=$io_fun_nomina->uf_obtenervalor("cmbestrec","NORMAL");	
		$li_est_numlin=$io_fun_nomina->uf_obtenervalor("txtnumlin","NORMAL");	
		$ls_est_prilpt=$io_fun_nomina->uf_obtenervalor("cmbprilpt","WINDOWS");	
		$ls_est_agrsem=$io_fun_nomina->uf_obtenervalor("cmbagrsem","2");	
		$li_con_parnom=$io_fun_nomina->uf_obtenervalor("chkparnom","0");	
		$ls_con_consue=$io_fun_nomina->uf_obtenervalor("cmbconsue","OCP");	
		$ls_con_cuecon=$io_fun_nomina->uf_obtenervalor("txtcuecon","XXXXXXXXXXXXX");	
		$ls_con_conapo=$io_fun_nomina->uf_obtenervalor("cmbconapo","OCP");	
		$ls_con_conpro=$io_fun_nomina->uf_obtenervalor("cmbconpro","UBICACION ADMINISTRATIVA");	
		$li_con_agrcon=$io_fun_nomina->uf_obtenervalor("chkagrcon","0");	
		$li_con_gennotdeb=$io_fun_nomina->uf_obtenervalor("chkgennotdeb","0");	
		$li_con_genrecdoc=$io_fun_nomina->uf_obtenervalor("chkgenrecdoc","0");	
		$li_con_genrecdocapo=$io_fun_nomina->uf_obtenervalor("chkgenrecdocapo","0");	
		$ls_con_tipdocnom=$io_fun_nomina->uf_obtenervalor("txttipdocnom","");
		$ls_con_tipdocapo=$io_fun_nomina->uf_obtenervalor("txttipdocapo","");
		$li_con_genvou=$io_fun_nomina->uf_obtenervalor("chkgenvou","0");	
		$ls_con_descon=$io_fun_nomina->uf_obtenervalor("cmbdescon"," ").$io_fun_nomina->uf_obtenervalor("txtcodproben"," ");
		$ls_con_confidnom=$io_fun_nomina->uf_obtenervalor("cmbconfidnom","OC");
		$ls_con_recdocfid=$io_fun_nomina->uf_obtenervalor("chkrecdocfid","0");
		$ls_con_tipdocfid=$io_fun_nomina->uf_obtenervalor("txttipdocfid","");
		$ls_con_cueconfid=$io_fun_nomina->uf_obtenervalor("txtcueconfid","");
		$ls_con_codbenfid=$io_fun_nomina->uf_obtenervalor("txtcodbenfid","");		
		$li_par_excpersus=$io_fun_nomina->uf_obtenervalor("chkexcpersus","0");	
		$li_par_perrep=$io_fun_nomina->uf_obtenervalor("chkperrep","0");	
		$ld_par_fecfinano=$io_fun_nomina->uf_obtenervalor("txtfecfinano","");	
		$ls_par_metcalfid=$io_fun_nomina->uf_obtenervalor("cmbmetcalfid","VERSION 2");	
		$ls_par_confpre=$io_fun_nomina->uf_obtenervalor("cmbconfpre","CUOTAS");	
		$li_par_camuniadm=$io_fun_nomina->uf_obtenervalor("chkcamuniadm","0");
		$li_par_camdedtipper=$io_fun_nomina->uf_obtenervalor("chkcamdedtipper","0");
		$li_par_campasogrado=$io_fun_nomina->uf_obtenervalor("chkcampasogrado","0");
		$li_par_incperben=$io_fun_nomina->uf_obtenervalor("chkincperben","0");
		$li_par_codunirac=$io_fun_nomina->uf_obtenervalor("chkcodunirac","0");
		$li_par_comautrac=$io_fun_nomina->uf_obtenervalor("chkcomautrac","0");
		$li_par_ajusuerac=$io_fun_nomina->uf_obtenervalor("chkajusuerac","0");
		$li_par_modpensiones=$io_fun_nomina->uf_obtenervalor("chkmodpensiones","0");
		$ls_par_cueconben=$io_fun_nomina->uf_obtenervalor("txtcueconben","");
		$li_par_valporpre=$io_fun_nomina->uf_obtenervalor("chkvalporpre","0");
		$li_par_campsuerac=$io_fun_nomina->uf_obtenervalor("chkcamsuerac","0");
		$ls_fpj_codorgfpj=$io_fun_nomina->uf_obtenervalor("txtcodorgfpj","XXXXXXXX");	
		$ls_fpj_codconcfpj=$io_fun_nomina->uf_obtenervalor("txtcodconcfpj","XXXXXXXX");	
		$ls_fpj_metfpj=$io_fun_nomina->uf_obtenervalor("cmbmetfpj","SUELDO NORMAL");	
		$ls_lph_codconclph=$io_fun_nomina->uf_obtenervalor("txtcodconclph","XXXXXXXXXX");	
		$ls_lph_metlph=$io_fun_nomina->uf_obtenervalor("cmbmetlph","SIN METODO");	
		$ls_fpa_codconcfpa=$io_fun_nomina->uf_obtenervalor("txtcodconcfpa","XXXXXXXXXX");	
		$ls_fpa_metfpa=$io_fun_nomina->uf_obtenervalor("cmbmetfpa","SIN METODO");	
		$li_fps_antcom=$io_fun_nomina->uf_obtenervalor("chkantcom","0");
		$li_fps_incvacagui=$io_fun_nomina->uf_obtenervalor("chkincvacagui","0");
		$li_fps_fraali=$io_fun_nomina->uf_obtenervalor("chkfraali","0");
		$li_fps_intasiextra=$io_fun_nomina->uf_obtenervalor("chkintasiextra","0");
		$ls_fps_metfps=$io_fun_nomina->uf_obtenervalor("cmbmetfps","SIN METODO");	
		$ls_man_cueconc=$io_fun_nomina->uf_obtenervalor("txtcueconc","401");	
		$ls_man_cueconccaj=$io_fun_nomina->uf_obtenervalor("txtcueconccaj","");	
		$li_man_actblofor=$io_fun_nomina->uf_obtenervalor("chkactblofor","0");	
		$li_man_actblocalnom=$io_fun_nomina->uf_obtenervalor("chkactblocalnom","0");	
		$ls_man_metrescon=$io_fun_nomina->uf_obtenervalor("cmbmetrescon","SIN METODO");	
		$ls_dis_metdisnom=$io_fun_nomina->uf_obtenervalor("cmbmetdisnom","SIN METODO");	
		$ls_ipas_codorgipas=$io_fun_nomina->uf_obtenervalor("txtcodorgipas","XXX");
		$ls_ipas_codconcahoipas=$io_fun_nomina->uf_obtenervalor("txtcodconcahoipas","XXXXXXXXXX");
		$ls_ipas_codconcseripas=$io_fun_nomina->uf_obtenervalor("txtcodconcseripas","XXXXXXXXXX");
		$ls_ipas_conhipespipas=$io_fun_nomina->uf_obtenervalor("txtconhipespipas","XXXXXXXXXX");
		$ls_ipas_conhipampipas=$io_fun_nomina->uf_obtenervalor("txtconhipampipas","XXXXXXXXXX");
		$ls_ipas_conhipconipas=$io_fun_nomina->uf_obtenervalor("txtconhipconipas","XXXXXXXXXX");
		$ls_ipas_conhiphipipas=$io_fun_nomina->uf_obtenervalor("txtconhiphipipas","XXXXXXXXXX");
		$ls_ipas_conhiplphipas=$io_fun_nomina->uf_obtenervalor("txtconhiplphipas","XXXXXXXXXX");
		$ls_ipas_conhipvivipas=$io_fun_nomina->uf_obtenervalor("txtconhipvivipas","XXXXXXXXXX");
		$ls_ipas_conperipas=$io_fun_nomina->uf_obtenervalor("txtconperipas","XXXXXXXXXX");
		$ls_ipas_conturipas=$io_fun_nomina->uf_obtenervalor("txtconturipas","XXXXXXXXXX");
		$ls_ipas_conproipas=$io_fun_nomina->uf_obtenervalor("txtconproipas","XXXXXXXXXX");
		$ls_ipas_conasiipas=$io_fun_nomina->uf_obtenervalor("txtconasiipas","XXXXXXXXXX");
		$ls_ipas_convehipas=$io_fun_nomina->uf_obtenervalor("txtconvehipas","XXXXXXXXXX");
		$ls_ipas_concomipas=$io_fun_nomina->uf_obtenervalor("txtconcomipas","XXXXXXXXXX");
		$ls_ivss_numemp=$io_fun_nomina->uf_obtenervalor("txtnumempivss","XXXXXXXXX");
		$ls_par_concsuelant=$io_fun_nomina->uf_obtenervalor("txtcodconcsuelant","XXXXXXXXXX");
		$li_par_loncueban=$io_fun_nomina->uf_obtenervalor("txtloncueban","0");
		$li_par_alfnumcodper=$io_fun_nomina->uf_obtenervalor("chkalfnumcodper","0");
		$li_par_valloncueban=$io_fun_nomina->uf_obtenervalor("chkvalloncueban","0");
		$ls_ivss_metodo=$io_fun_nomina->uf_obtenervalor("cmbmetivss","SIN METODO");
		$li_con_parfpj=$io_fun_nomina->uf_obtenervalor("chkparfpj","0");
		$ls_edadM=$io_fun_nomina->uf_obtenervalor("txtedadM","0");
		$ls_edadF=$io_fun_nomina->uf_obtenervalor("txtedadF","0");
		$ls_anoM=$io_fun_nomina->uf_obtenervalor("txtanoM","0");
		$ls_anoT=$io_fun_nomina->uf_obtenervalor("txtanoT","0");
		$li_prestamo=$io_fun_nomina->uf_obtenervalor("chkprestamos","0");
		$ls_sueint=$io_fun_nomina->uf_obtenervalor("txtsueint","");
		$li_persobregiro=$io_fun_nomina->uf_obtenervalor("chkpersobregiro","0");
		$ls_tipdocpagperche=$io_fun_nomina->uf_obtenervalor("txttipdocpagper","");
		$li_genrecdocpagperche=$io_fun_nomina->uf_obtenervalor("chkgenrecdocpagper","0");
		$li_estctaalt=$io_fun_nomina->uf_obtenervalor("chkestctaalt","0");
		if ($ls_sueint=="")
		{
			$ls_readonly="readonly";
		}
		else
		{
			$ls_readonly="";
		}
   }// end function uf_load_variables
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
<title >Configuraci&oacute;n</title>
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
<script language="javascript" src="../shared/js/valida_tecla.js"></script>
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {font-size: 12px}
.Estilo2 {color: #6699CC}
-->
</style>
</head>
<body>
<?php 
	require_once("sigesp_sno.php");
	$io_sno=new sigesp_sno();
	$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
	switch ($ls_operacion) 
	{
		case "REPARARSUBNOMINAS":
			$lb_valido=$io_sno->uf_reparar_subnominas($la_seguridad);
			break;
			
		case "REPARARCONCEPTOPERSONAL":
			$lb_valido=$io_sno->uf_reparar_conceptopersonal($la_seguridad);
			break;
			
		case "RECALCULARSUELDOINTEGRAL":
			$lb_valido=$io_sno->uf_recalcular_sueldointegral($la_seguridad);
			break;
		
		case "MANTENIMIENTOHISTORICOS":
			$lb_valido=$io_sno->uf_mantenimiento_historicos($la_seguridad);
			break;
		
		case "REPARARACUMULADOCONCEPTOS":
			$lb_valido=$io_sno->uf_mantenimiento_repararacumuladoconceptos($la_seguridad);
			break;
		
		case "GUARDAR":
			uf_load_variables();
			$lb_valido=$io_sno->uf_guardar_configuracion($li_vac_reportar,$ls_vac_codconvac,$ls_vac_metvac,$ls_est_codconcsue,
														 $ls_est_estnom,$ls_est_ordcons,$ls_est_ordconc,$ls_est_estrec,$li_est_numlin,
									  					 $ls_est_prilpt,$ls_est_agrsem,$li_con_parnom,$ls_con_consue,$ls_con_cuecon,
									  					 $ls_con_conapo,$ls_con_conpro,$li_con_agrcon,$li_con_gennotdeb,$li_con_genvou,
									  					 $ls_con_descon,$li_par_excpersus,$li_par_perrep,$ld_par_fecfinano,$ls_par_metcalfid,
									 					 $ls_fpj_codorgfpj,$ls_fpj_codconcfpj,$ls_fpj_metfpj,$ls_lph_codconclph,$ls_lph_metlph,
														 $ls_fpa_codconcfpa,$ls_fpa_metfpa,$li_fps_antcom,$li_fps_fraali,$ls_fps_metfps,
									  					 $ls_man_cueconc,$ls_man_cueconccaj,$li_man_actblofor,$li_man_actblocalnom,
									  					 $ls_man_metrescon,$ls_dis_metdisnom,$li_con_genrecdoc,$li_con_genrecdocapo,
														 $ls_con_tipdocnom,$ls_con_tipdocapo,$ls_ipas_codorgipas,$ls_ipas_codconcahoipas,
														 $ls_ipas_codconcseripas,$ls_ipas_conhipespipas,$ls_ipas_conhipampipas,$ls_ipas_conhipconipas,
														 $ls_ipas_conhiphipipas,$ls_ipas_conhiplphipas,$ls_ipas_conhipvivipas,$ls_ipas_conperipas,
														 $ls_ipas_conturipas,$ls_ipas_conproipas,$ls_ipas_conasiipas,$ls_ipas_convehipas,
														 $ls_ipas_concomipas,$ls_ivss_numemp,$li_vac_desincorporar,$ls_par_concsuelant,
														 $ls_par_confpre,$li_par_camuniadm,$li_par_campasogrado,$li_par_incperben,$ls_par_cueconben,
														 $li_par_codunirac,$li_par_comautrac,$li_par_ajusuerac,$li_par_loncueban,$li_par_modpensiones,
														 $li_par_valloncueban,$li_par_valporpre,$ls_con_confidnom,$ls_con_recdocfid,
														 $ls_con_tipdocfid,$ls_con_cueconfid,$ls_con_codbenfid,$ls_ivss_metodo,
														 $li_par_alfnumcodper,$li_con_parfpj,$ls_edadM, $ls_edadF,$ls_anoM,$ls_anoT,$li_prestamo, 
														 $li_par_campsuerac,$li_fps_intasiextra,$ls_sueint,$li_par_camdedtipper,$li_persobregiro,
														 $li_genrecdocpagperche,$ls_tipdocpagperche,$li_salvacper,$li_estctaalt,$li_fps_incvacagui,
														 $la_seguridad);
			
			if($lb_valido)
			{
				$io_sno->io_mensajes->message("La configuración fue registrada.");
			}
			else
			{
				$io_sno->io_mensajes->message("Ocurrio un error al guardar la configuración.");
			}
			break;
	}
	uf_select_campos();
	unset($io_sno);
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
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
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td width="25" height="20" class="toolbar"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif"  title="Guardar" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif"  title="Salir" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif"  title="Ayuda" alt="Ayuda" width="20" height="20"></div></td>
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
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="760" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="710" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-ventana">Configuraci&oacute;n</td>
        </tr>
		<tr>
          <td height="22" colspan="4" class="titulo-celdanew">Sueldo Integral</td>
          </tr>
		  <tr>
          <td height="22"><div align="right">Cambiar denominaci&oacute;n Sueldo Integral</div></td>
          <td colspan="4"><div align="left"><input name="chksueint" type="checkbox" class="sin-borde" value="1" <?php if($ls_sueint!=""){print "checked";} ?>   onClick="javascript: activar_denominacion();">
              <input name="txtsueint" type="text" id="txtsueint" value="<?php print $ls_sueint;?>" size="60" maxlength="100" onKeyUp="ue_validarcomillas(this);"  <?php print $ls_readonly;?> >
          </div></td>
        </tr>
        <tr>
        <tr class="titulo-celdanew">
          <td height="20" colspan="4"><div align="center">Vacaciones</div></td>
          </tr>
		  <tr>
          <td width="166" height="22"><div align="right">M&eacute;todo Vacaci&oacute;n </div></td>
          <td><div align="left">
            <select name="cmbmetvac" id="cmbmetvac">
              <option value="0" <?php print $la_vac_metban[0]; ?>>Sin M&eacute;todo</option>
              <option value="1" <?php print $la_vac_metban[1]; ?>>M&eacute;todo #0</option>
            </select>
          </div></td>
          <td><div align="right">Desincorporar de la N&oacute;mina </div></td>
          <td><div align="left">
            <input name="chkvacdesincorporar" type="checkbox" class="sin-borde" id="chkvacdesincorporar" value="1" <?php if($li_vac_desincorporar!="0"){print "checked";} ?>>
          </div></td>
        </tr>
		<tr>
          <td height="22"><div align="right">Reportar</div></td>
          <td width="198"><div align="left"><input name="chkvacreportar" type="checkbox" class="sin-borde" value="1" <?php if($li_vac_reportar!="0"){print "checked";} ?>></div></td>
          <td width="138"><div align="right">C&oacute;digo Concepto Vacaci&oacute;n</div></td>
          <td width="198"><div align="left"><input name="txtcodconvac" type="text" id="txtcodconvac" value="<?php print $ls_vac_codconvac;?>" size="13" maxlength="10" onKeyUp="javascript: ue_validarnumero(this);" onBlur="javascript: ue_rellenarcampo(this,10);"></div></td>
        </tr>
		
		<tr>
          <td height="22"><div align="right">Permitir salida del Personal a partir del mes anterior a la Fecha de Vencimiento</div></td>
          <td width="198"><div align="left"><input name="chksalvacper" type="checkbox" class="sin-borde" value="1" <?php if($li_salvacper!="0"){print "checked";} ?>></div></td>
        </tr>
		
        <tr class="titulo-celdanew">
          <td height="20" colspan="4"><div align="center">Contabilizaci&oacute;n</div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">Par&aacute;metros por n&oacute;mina </div></td>
          <td><div align="left"><input name="chkparnom" type="checkbox" class="sin-borde" id="chkparnom" value="1" <?php if($li_con_parnom!="0"){print "checked";} ?> onChange="javascript: ue_bloquear();"></div></td>
          <td><div align="right">Agrupar Contable</div></td>
          <td><div align="left">
            <input name="chkagrcon" type="checkbox" class="sin-borde" id="chkagrcon" value="1" <?php if($li_con_agrcon!="0"){print "checked";} if($li_con_parnom!="0"){print "disabled";} ?>>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Destino Contabilizaci&oacute;n</div></td>
          <td colspan="3"><div align="left">
              <select name="cmbdescon" id="cmbdescon" <?php if($li_con_parnom!="0"){print "disabled";} ?> onChange="javascript: ue_limpiar();">
                <option value=" " <?php print $la_con_descon[0]; ?>> </option>
                <option value="P" <?php print $la_con_descon[1]; ?>>PROVEEDOR</option>
                <option value="B" <?php print $la_con_descon[2]; ?>>BENEFICIARIO</option>
              </select>
              <input name="txtcodproben" type="text" id="txtcodproben" value="<?php print $ls_con_descon;?>" readonly>
              <a href="javascript: ue_buscardestino();"><img  src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
              <input name="txtnombre" type="text" class="sin-borde" id="txtnombre" size="50" maxlength="30" readonly>
          </div></td>
        </tr>
		<tr>
		   <td height="22"><div align="right">Utilizar Cuenta Contable para el registro del Gasto por pagar </div>          </td>
		  <td>
            <div align="left">
              <input name="chkestctaalt" type="checkbox" class="sin-borde" id="chkestctaalt" value="1" <?php  if($li_estctaalt=="1"){print "checked";} if($li_con_parnom!="0"){print "disabled";} ?>  onClick="javascript:ue_chequear_nomina_beneficiario();" >          
            </div></td>			
			<tr>
        <tr>
          <td height="22" colspan="2"><div align="center" class="titulo-conect Estilo1">N&oacute;mina</div></td>
          <td colspan="2"><div align="center" class="titulo-conect Estilo1">Aportes</div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">N&oacute;mina</div></td>
          <td><div align="left">
            <select name="cmbconsue" id="cmbconsue" onChange="javascript: ue_contabilizacionnomina();" <?php if($li_con_parnom!="0"){print "disabled";} ?>>
              <option value="CP" <?php print $la_con_consue[0]; ?>>Causar y Pagar</option>
              <option value="OCP" <?php print $la_con_consue[1]; ?>>Compromete, Causa y Paga</option>
              <option value="OC" <?php print $la_con_consue[2]; ?>>Compromete y Causa</option>
              <option value="O" <?php print $la_con_consue[3]; ?>>Compromete</option>
            </select>
          </div></td>
          <td><div align="right">Aportes</div></td>
          <td><select name="cmbconapo" id="cmbconapo" onChange="javascript: ue_contabilizacionaportes();" <?php if($li_con_parnom!="0"){print "disabled";} ?>>
            <option value="CP" <?php print $la_con_conapo[0]; ?>>Causar y Pagar</option>
            <option value="OCP" <?php print $la_con_conapo[1]; ?>>Compromete, Causa y Paga</option>
            <option value="OC" <?php print $la_con_conapo[2]; ?>>Compromete y Causa</option>
            <option value="O" <?php print $la_con_conapo[3]; ?>>Compromete</option>
          </select></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Generar Recepci&oacute;n de Documento a la N&oacute;mina </div></td>
          <td><div align="left">
            <input name="chkgenrecdoc" type="checkbox" class="sin-borde" id="chkgenrecdoc" value="1" onChange="javascript: ue_recepcionnomina();" <?php if($li_con_genrecdoc!="0"){print "checked";} if($li_con_parnom!="0"){print "disabled";} ?>>
</div></td>
          <td><div align="right">Generar Recepci&oacute;n de Documento a los aportes </div></td>
          <td><div align="left">
            <input name="chkgenrecdocapo" type="checkbox" class="sin-borde" id="chkgenrecdocapo" value="1" onChange="javascript: ue_recepcionaportes();" <?php if($li_con_genrecdocapo!="0"){print "checked";} if($li_con_parnom!="0"){print "disabled";} ?>>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Tipo de Documento N&oacute;mina </div></td>
          <td><div align="left">
            <input name="txttipdocnom" type="text" id="txttipdocnom" value="<?php print $ls_con_tipdocnom;?>" readonly>
            <a href="javascript: ue_buscartipodocumento('NOMINA');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
          </div></td>
          <td><div align="right">Tipo de Documento Aporte </div></td>
          <td><div align="left">
            <input name="txttipdocapo" type="text" id="txttipdocapo" value="<?php print $ls_con_tipdocapo;?>" readonly>
            <a href="javascript: ue_buscartipodocumento('APORTE');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
        </tr>
		<tr>
          <td height="22"><div align="right">Cuenta Contable</div></td>
          <td><div align="left">
            <input name="txtcuecon" type="text" id="txtcuecon" value="<?php print $ls_con_cuecon;?>" readonly>
            <a href="javascript: ue_buscarcuentacontable('CONFIGURACION');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
          <td><div align="right"></div></td>
          <td><div align="left"></div></td>
        </tr>
		<tr>
		  <td height="22"><div align="right">Generar Nota D&eacute;bito en bancos</div></td>
		  <td><div align="left">
		    <input name="chkgennotdeb" type="checkbox" class="sin-borde" id="chkgennotdeb" value="1"  onChange="javascript: ue_notadebito();"  <?php if($li_con_gennotdeb!="0"){print "checked";}  if($li_con_parnom!="0"){print "disabled";} ?>>
		    </div></td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  </tr>
		   <tr>
		   <td height="22"><div align="right">Generar Recepci&oacute;n de Documento Autmaticamente para el Pago del Personal con Cheque</div>          </td>
         <td>
            <div align="left">
              <input name="chkgenrecdocpagper" type="checkbox" class="sin-borde" id="chkgenrecdocpagper" value="1" <?php if($li_genrecdocpagperche!="0"){print "checked";} if($li_con_parnom!="0"){print "disabled";} ?>>          
            </div></td>
			<td height="22"><div align="right">Tipo de Documento del Pago de Personal</div></td>
		  <td>
		    <input name="txttipdocpagper" type="text" id="txttipdocpagper" value="<?php print $ls_tipdocpagperche;?>" readonly>
		    <a href="javascript: ue_buscartipodocumento('PAGOPERSONAL');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a> </td>
		  </tr>
		<tr>
		  <td height="22" colspan="2"><div align="center"><span class="titulo-conect Estilo1">Prestaci&oacute;n Antiguedad </span></div></td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  </tr>
		<tr>
		  <td height="22"><div align="right">Prestaci&oacute;n Antiguedad</div></td>
		  <td>
		    <select name="cmbconfidnom" id="cmbconfidnom" onChange="javascript: ue_contabilizacionfideicomiso();" <?php  if($li_con_parnom!="0"){print "disabled";} ?>>
		      <option value="OC" <?php print $la_con_confidnom[0]; ?>>Compromete y Causa</option>
		      </select>		    </td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  </tr>
		<tr>
		  <td height="22"><div align="right">Generar Recepcion de Documentos</div></td>
		  <td>		    <input name="chkrecdocfid" type="checkbox" class="sin-borde" id="chkrecdocfid" value="1" onChange="javascript: ue_recepcionfideicomiso();" <?php if($ls_con_recdocfid=="1"){ print " checked ";}  if($li_con_parnom!="0"){print "disabled";} ?>>		    </td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  </tr>
		<tr>
		  <td height="22"><div align="right">Tipo de Documento </div></td>
		  <td>
		    <input name="txttipdocfid" type="text" id="txttipdocfid" value="<?php print $ls_con_tipdocfid;?>" readonly>
		    <a href="javascript: ue_buscartipodocumento('FIDEICOMISO');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a> </td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  </tr>
		<tr>
		  <td height="22"><div align="right">Beneficiario</div></td>
		  <td>
              <input name="txtcodbenfid" type="text" id="txtcodbenfid" value="<?php print $ls_con_codbenfid; ?>" readonly>
            <a href="javascript: ue_buscarbeneficiario();"><img  src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a> </td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  </tr>
		<tr>
		  <td height="22"><div align="right">Cuenta Contable</div></td>
		  <td>
		    <input name="txtcueconfid" type="text" id="txtcueconfid" value="<?php print $ls_con_cueconfid;?>" readonly>
		    <a href="javascript: ue_buscarcuentacontable('FIDEICOMISO');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  </tr>		 
		<tr>
          <td height="20" colspan="4" class="titulo-celdanew"><div align="center">Par&aacute;metros</div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">Ordenar Constantes </div></td>
          <td><div align="left">
            <select name="cmbordcons" id="cmbordcons">
              <option value="CODIGO" <?php print $la_est_ordcons[0]; ?>>C&oacute;digo</option>
              <option value="NOMBRE" <?php print $la_est_ordcons[1]; ?>>Nombre</option>
              <option value="APELLIDO" <?php print $la_est_ordcons[2]; ?>>Apellido</option>
              <option value="UNIDAD" <?php print $la_est_ordcons[3]; ?>>Unidad</option>
            </select>
          </div></td>
          <td><div align="right">Ordenar Conceptos </div></td>
          <td><div align="left">
            <select name="cmbordconc" id="cmbordconc">
              <option value="CODIGO" <?php print $la_est_ordconc[0]; ?>>C&oacute;digo</option>
              <option value="NOMBRE" <?php print $la_est_ordconc[1]; ?>>Nombre</option>
              <option value="APELLIDO" <?php print $la_est_ordconc[2]; ?>>Apellido</option>
              <option value="UNIDAD" <?php print $la_est_ordconc[3]; ?>>Unidad</option>
            </select>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Excluir personas suspendidas post calculos de los reportes </div></td>
          <td><div align="left"><input name="chkexcpersus" type="checkbox" class="sin-borde" id="chkexcpersus" value="1" <?php if($li_par_excpersus!="0"){print "checked";}?>></div></td>
          <td><div align="right">No permitir personas repetidas ente n&oacute;minas NORMALES</div></td>
          <td><div align="left"><input name="chkperrep" type="checkbox" class="sin-borde" id="chkperrep" value="1" <?php if($li_par_perrep!="0"){print "checked";}?>></div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Fecha Tope Fin de a&ntilde;o </div></td>
          <td><div align="left"><input name="txtfecfinano" type="text" id="txtfecfinano" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" value="<?php print $ld_par_fecfinano;?>" maxlength="10" datepicker="true"></div></td>
          <td><div align="right">M&eacute;todo Calculo Prestaci&oacute;n Antiguedad </div></td>
          <td><div align="left">
            <select name="cmbmetcalfid" id="cmbmetcalfid">
              <option value="VERSION 2" <?php print $la_par_metcalfid[0]; ?>>VERSI&Oacute;N 2 </option>
              <option value="VERSION CONSEJO" <?php print $la_par_metcalfid[1]; ?>>VERSI&Oacute;N CONSEJO</option>
            </select>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo Concepto Sueldo Anterior </div></td>
          <td><input name="txtcodconcsuelant" type="text" id="txtcodconcsuelant" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $ls_par_concsuelant;?>" maxlength="10" readonly >
            <a href="javascript: ue_buscarconcepto('concsuelant');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></td>
          <td><div align="right">Configurar Prestamo </div></td>
          <td><select name="cmbconfpre" id="cmbconfpre">
            <option value="CUOTAS" <?php print $la_par_confpre[0]; ?>>CUOTAS</option>
            <option value="MONTO" <?php print $la_par_confpre[1]; ?>>MONTO</option>
                              </select></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Cambiar la Unidad Administrativa cuando la n&oacute;mina sea con RAC </div></td>
          <td><div align="left">
            <input name="chkcamuniadm" type="checkbox" class="sin-borde" id="chkcamuniadm" value="1" <?php if($li_par_camuniadm!="0"){print "checked";}?>>
          </div></td>
          <td><div align="right">Cambiar el Paso y Grado cuando la n&oacute;mina sea con RAC</div></td>
          <td>
            <div align="left">
              <input name="chkcampasogrado" type="checkbox" class="sin-borde" id="chkcampasogrado" value="1" <?php if($li_par_campasogrado!="0"){print "checked";}?>>          
            </div></td>
        </tr>
		<tr>
          <td height="22"><div align="right">Cambiar la Dedicaci&oacute;n y el Tipo de Personal cuando la n&oacute;mina sea con RAC </div></td>
          <td><div align="left">
            <input name="chkcamdedtipper" type="checkbox" class="sin-borde" id="chkcamdedtipper" value="1" <?php if($li_par_camdedtipper!="0"){print "checked";}?>>
          </div></td>
		   <td height="22"><div align="right">Cambiar el sueldo cuando la n&oacute;mina de Obreros sea con RAC</div></td>
         <td>
            <div align="left">
              <input name="chkcamsuerac" type="checkbox" class="sin-borde" id="chkcamsuerac" value="1" <?php if($li_par_campsuerac!="0"){print "checked";}?>>          
            </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Incluir el personal automaticamente en el m&oacute;dulo de Beneficiarios </div></td>
          <td>
            <div align="left">
              <input name="chkincperben" type="checkbox" class="sin-borde" id="chkincperben" value="1" onChange="javascript: ue_personalbeneficiario();" <?php if($li_par_incperben!="0"){print "checked";}?>>          
            </div></td>
          <td><div align="right">Cuenta contable para los Beneficiarios </div></td>
          <td><div align="left">
            <input name="txtcueconben" type="text" id="txtcueconben" value="<?php print $ls_par_cueconben;?>" readonly>
          <a href="javascript: ue_buscarcuentacontablebene();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Implementar el c&oacute;digo &uacute;nico de RAC </div></td>
          <td><div align="left">
            <input name="chkcodunirac" type="checkbox" class="sin-borde" id="chkcodunirac" value="1" <?php if($li_par_codunirac!="0"){print "checked";}?>>
          </div></td>
          <td><div align="right">Compensaci&oacute;n automatica en los tabuladores del rac </div></td>
          <td><div align="left">
            <input name="chkcomautrac" type="checkbox" class="sin-borde" id="chkcomautrac" value="1" <?php if($li_par_comautrac!="0"){print "checked";}?>>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Ajustar sueldo seg&uacute;n compensaci&oacute;n </div></td>
          <td><div align="left">
            <input name="chkajusuerac" type="checkbox" class="sin-borde" id="chkajusuerac" value="1" <?php if($li_par_ajusuerac!="0"){print "checked";}?>>
          </div></td>
          <td><div align="right">Modificar Datos Pensiones </div></td>
          <td><div align="left">
            <input name="chkmodpensiones" type="checkbox" class="sin-borde" id="chkmodpensiones" value="1" <?php if($li_par_modpensiones!="0"){print "checked";}?>>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Validar Cuotas de Prestamos No mayores al 30% del Sueldo</div></td>
          <td><div align="left">
            <input name="chkvalporpre" type="checkbox" class="sin-borde" id="chkvalporpre" value="1" <?php if($li_par_valporpre!="0"){print "checked";}?>>
          </div></td>
          <td><div align="right">Permitir Alfanumericos en el C&oacute;digo de personal</div></td>
          <td><div align="left">
            <input name="chkalfnumcodper" type="checkbox" class="sin-borde" id="chkalfnumcodper" value="1" <?php if($li_par_alfnumcodper!="0"){print "checked";}?>>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Validar Longitud de Cuenta Bancaria </div></td>
          <td><div align="left">
              <input name="chkvalloncueban" type="checkbox" class="sin-borde" id="chkvalloncueban" value="1" <?php if($li_par_valloncueban!="0"){print "checked";}?>>
            Longitud
            <input name="txtloncueban" type="text" id="txtloncueban" size="6" maxlength="2" value="<?php print $li_par_loncueban;?>">
          </div></td>
          <td height="22"><div align="right">No permitir m&uacute;ltiples Pr&eacute;stamos del mismo Tipo</div></td>
          <td><input name="chkprestamos" type="checkbox" class="sin-borde" id="chkprestamos" value="1" <?php if($li_prestamo!="0"){print "checked";}?>></td>
        </tr>
        <tr>
         
          <td height="22"><div align="right">Permitir sobregiro en las Cuentas del Personal en  el c&aacute;lculo de la n&oacute;mina</div></td>
          <td><div align="left">
              <input name="chkpersobregiro" type="checkbox" class="sin-borde" id="chkpersobregiro" value="1" <?php if($li_persobregiro!="0"){print "checked";}?>>          
            </div></td>
        </tr>
        <tr>
          <td height="22" colspan="4" class="titulo-celdanew">IVSS</td>
          </tr>
        <tr>
          <td height="22"><div align="right">N&uacute;mero de Empresa IVSS </div></td>
          <td><div align="left">
            <label>
            <input name="txtnumempivss" type="text" id="txtnumempivss" value="<?php print $ls_ivss_numemp;?>" maxlength="9">
            </label>
</div></td>
          <td height="22"><div align="right">M&eacute;todo de IVSS </div></td>
          <td><div align="left">
              <select name="cmbmetivss" id="cmbmetivss">
                <option value="SUELDO NORMAL" <?php print $la_ivss_metodo[0];?>>SUELDO BASICO</option>
                <option value="SUELDO INTEGRAL" <?php print $la_ivss_metodo[1];?>><?php if ($ls_sueint==""){print "SUELDO INTEGRAL"; } else { print (strtoupper($ls_sueint));}?></option>
              </select>
          </div></td>
        </tr>
        <tr class="titulo-celdanew">
          <td height="22" colspan="4" class="titulo-celdanew">Aporte - IPASME </td>
          </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo de Organismo </div></td>
          <td><div align="left">
            <label>
            <input name="txtcodorgipas" type="text" id="txtcodorgipas" value="<?php print $ls_ipas_codorgipas;?>" maxlength="3">
            </label>
</div></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo Concepto de Ahorro </div></td>
          <td><div align="left">
            <label>
            <input name="txtcodconcahoipas" type="text" id="txtcodconcahoipas" value="<?php print $ls_ipas_codconcahoipas;?>" maxlength="10" readonly>
            </label>
            <a href="javascript: ue_buscarconcepto('cajaahorro');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div><a href="javascript: ue_buscarconcepto();"></a></td>
          <td><div align="right">C&oacute;digo Concepto de Servicio Asistencial </div></td>
          <td><div align="left">
            <input name="txtcodconcseripas" type="text" id="txtcodconcseripas" value="<?php print $ls_ipas_codconcseripas;?>" maxlength="10" readonly>
            <a href="javascript: ue_buscarconcepto('servasi');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
        </tr>
        <tr>
          <td height="22" colspan="4"><div align="center"><span class="titulo-conect Estilo1">Conceptos para las Cobranzas </span></div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">Concepto Hipotecario (Especial) </div></td>
          <td><div align="left">
            <input name="txtconhipespipas" type="text" id="txtconhipespipas" value="<?php print $ls_ipas_conhipespipas;?>" maxlength="10" readonly>
			<a href="javascript: ue_buscarconcepto('conhipes');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
          <td><div align="right">Concepto Hipotecario (Refacci&oacute;n &oacute; Ampliaci&oacute;n) </div></td>
          <td><div align="left">
            <input name="txtconhipampipas" type="text" id="txtconhipampipas" value="<?php print $ls_ipas_conhipampipas;?>" maxlength="10" readonly>
            <a href="javascript: ue_buscarconcepto('conhipamp');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Concepto Hipotecario (Construcci&oacute;n) </div></td>
          <td><div align="left">
            <input name="txtconhipconipas" type="text" id="txtconhipconipas" value="<?php print $ls_ipas_conhipconipas;?>" maxlength="10" readonly>
            <a href="javascript: ue_buscarconcepto('conhipcon');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
          <td><div align="right">Concepto Hipotecario (Cancelar Hipoteca) </div></td>
          <td><div align="left">
            <input name="txtconhiphipipas" type="text" id="txtconhiphipipas" value="<?php print $ls_ipas_conhiphipipas;?>" maxlength="10" readonly>
            <a href="javascript: ue_buscarconcepto('conhiphip');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Concepto Hipotecario (L.P.H.) </div></td>
          <td><div align="left">
            <input name="txtconhiplphipas" type="text" id="txtconhiplphipas" value="<?php print $ls_ipas_conhiplphipas;?>" maxlength="10" readonly>
            <a href="javascript: ue_buscarconcepto('conhiplph');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
          <td><div align="right">Concepto Hipotecario (Adquirir Vivienda) </div></td>
          <td><div align="left">
            <input name="txtconhipvivipas" type="text" id="txtconhipvivipas" value="<?php print $ls_ipas_conhipvivipas;?>" maxlength="10" readonly>
            <a href="javascript: ue_buscarconcepto('conhipvivi');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Concepto Personal </div></td>
          <td><div align="left">
            <input name="txtconperipas" type="text" id="txtconperipas" value="<?php print $ls_ipas_conperipas;?>" maxlength="10" readonly>
            <a href="javascript: ue_buscarconcepto('conper');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
          <td><div align="right">Concepto Tur&iacute;sticos </div></td>
          <td><div align="left">
            <input name="txtconturipas" type="text" id="txtconturipas" value="<?php print $ls_ipas_conturipas;?>" maxlength="10" readonly>
            <a href="javascript: ue_buscarconcepto('conturi');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Concepto Proveduria </div></td>
          <td><div align="left">
            <input name="txtconproipas" type="text" id="txtconproipas" value="<?php print $ls_ipas_conproipas;?>" maxlength="10" readonly>
            <a href="javascript: ue_buscarconcepto('conpro');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
          <td><div align="right">Concepto Asistenciales </div></td>
          <td><div align="left">
            <input name="txtconasiipas" type="text" id="txtconasiipas" value="<?php print $ls_ipas_conasiipas;?>" maxlength="10" readonly>
            <a href="javascript: ue_buscarconcepto('conasi');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Concepto Veh&iacute;culos </div></td>
          <td><div align="left">
            <input name="txtconvehipas" type="text" id="txtconvehipas" value="<?php print $ls_ipas_convehipas;?>" maxlength="10" readonly>
            <a href="javascript: ue_buscarconcepto('convehi');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
          <td><div align="right">Concepto Comerciales </div></td>
          <td><div align="left">
            <input name="txtconcomipas" type="text" id="txtconcomipas" value="<?php print $ls_ipas_concomipas;?>" maxlength="10" readonly>
            <a href="javascript: ue_buscarconcepto('concomi');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
        </tr>
        <tr>
          <td height="20" colspan="4" class="titulo-celdanew"><div align="center">Aporte - Fondo de Pensiones de Jubilaciones </div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo de Organismo </div></td>
          <td><div align="left">
            <input name="txtcodorgfpj" type="text" id="txtcodorgfpj" value="<?php print $ls_fpj_codorgfpj;?>" onKeyUp="javascript: ue_validarnumero(this);">
          </div></td>
          <td><div align="right">C&oacute;digo de Concepto FPJ</div></td>
          <td><div align="left">
            <input name="txtcodconcfpj" type="text" id="txtcodconcfpj" value="<?php print $ls_fpj_codconcfpj;?>" maxlength="21" onKeyUp="javascript: ue_validartexto(this);" >
            <a href="javascript: ue_buscarconcepto('concfpj');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">M&eacute;todo de FPJ</div></td>
          <td><div align="left">
              <select name="cmbmetfpj" id="cmbmetfpj">
                <option value="SUELDO NORMAL" <?php print $la_fpj_metfpj[0];?>>SUELDO BASICO</option>
                <option value="SUELDO INTEGRAL" <?php print $la_fpj_metfpj[1];?>><?php if ($ls_sueint==""){print "SUELDO INTEGRAL"; } else { print (strtoupper($ls_sueint));}?></option>
              </select>
          </div></td>
          <td height="22"><div align="right">Par&aacute;metros de FPJ      (Edad y a&ntilde;os Servicios) </div></td>
          <td><div align="left">
            <input name="chkparfpj" type="checkbox" class="sin-borde" id="chkparfpj" 
			value="1" <?php if($li_con_parfpj!="0"){print "checked";} ?> 
			onChange="javascript: ue_bloquear2();">
          </div></td>
        </tr>
		<tr>
		  <td height="22"><div align="right">Edad (Personal Masculino)</div></td>
		  <td><input name="txtedadM" type="text" id="txtedadM"		      
			   value="<?php print $ls_edadM;?>" size="6" maxlength="2"
			   <?php if($li_con_parfpj=="0"){print "disabled";} ?> ></td>
		  <td height="22"><div align="right">Edad (Personal Femenino)</div></td>
		  <td><input name="txtedadF" type="text" id="txtedadF"  
		      <?php if($li_con_parfpj=="0"){print "disabled";} ?>   
			  value="<?php print $ls_edadF;?>" size="6" maxlength="2" ></td>
		</tr>
		<tr>
          <td height="22"><div align="right">A&ntilde;os de Servicios (Minimos)</div></td>
          <td><input name="txtanoM" type="text" id="txtanoM"		      
			   value="<?php print $ls_anoM;?>" size="6" maxlength="2"
			   <?php if($li_con_parfpj=="0"){print "disabled";} ?> ></td>
          <td><div align="right">A&ntilde;os de Servicios (M&aacute;ximo)</div></td>
		  
         <td><input name="txtanoT" type="text" id="txtanoT"		      
			   value="<?php print $ls_anoT; ?>" size="6" maxlength="2"
			   <?php if($li_con_parfpj!="0"){print "disabled";} ?> ></td>
         </tr>
		 <tr class="titulo-celdanew">
          <td height="20" colspan="4"><div align="center">Aporte - R&eacute;gimen Prestacional de Vivienda y H&aacute;bitat </div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo de Concepto RPVYH </div></td>
          <td><div align="left">
            <input name="txtcodconclph" type="text" id="txtcodconclph" value="<?php print $ls_lph_codconclph;?>" onKeyUp="javascript: ue_validartexto(this);" maxlength="21" >
            <a href="javascript: ue_buscarconcepto('conclph');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
          <td><div align="right">M&eacute;todo de RPVYH </div></td>
          <td><div align="left">
            <select name="cmbmetlph" id="cmbmetlph">
              <option value="SIN METODO" <?php print $la_lph_metlph[0];?>>SIN METODO</option>
              <option value="VIVIENDA" <?php print $la_lph_metlph[1];?>>VIVIENDA</option>
              <option value="CASA PROPIA" <?php print $la_lph_metlph[2];?>>CASA PROPIA</option>
              <option value="MERENAP" <?php print $la_lph_metlph[3];?>>MERENAP</option>
              <option value="MIRANDA" <?php print $la_lph_metlph[4];?>>MIRANDA</option>
              <option value="FONDO MUTUAL HABITACIONAL" <?php print $la_lph_metlph[5];?>>FONDO MUTUAL HABITACIONAL</option>
              <option value="BANESCO" <?php print $la_lph_metlph[6];?>>BANESCO</option>
              <option value="MI CASA EAP" <?php print $la_lph_metlph[7];?>>MI CASA EAP</option>
              <option value="CANARIAS" <?php print $la_lph_metlph[8];?>>CANARIAS</option>
              <option value="VENEZUELA" <?php print $la_lph_metlph[9];?>>VENEZUELA</option>
              <option value="DELSUR" <?php print $la_lph_metlph[10];?>>DELSUR</option>
              <option value="MERCANTIL" <?php print $la_lph_metlph[11];?>>MERCANTIL</option>
              <option value="CENTRAL" <?php print $la_lph_metlph[12];?>>CENTRAL</option>
              <option value="CAJA FAMILIA" <?php print $la_lph_metlph[13];?>>CAJA FAMILIA</option>
              <option value="FONDO_COMUN_EAP" <?php print $la_lph_metlph[14];?>>FONDO COMÚN EAP</option>
              <option value="FONDO_COMUN_MRE" <?php print $la_lph_metlph[14];?>>FONDO COMÚN MRE</option>
              <option value="BOD" <?php print $la_lph_metlph[15];?>>BOD</option>
			  <option value="BANAVIH" <?php print $la_lph_metlph[16];?>>BANAVIH</option>
           </select>
          </div></td>
        </tr>
        <tr>
          <td height="20" colspan="4" class="titulo-celdanew">            
            <div align="center">Aporte - Plan de Ahorro </div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo de Concepto FPA </div></td>
          <td><div align="left">
            <input name="txtcodconcfpa" type="text" id="txtcodconcfpa" value="<?php print $ls_fpa_codconcfpa;?>" onKeyUp="javascript: ue_validartexto(this);" maxlength="21" >
            <a href="javascript: ue_buscarconcepto('concfpa');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
          <td><div align="right">M&eacute;todo de FPA </div></td>
          <td><div align="left">
            <select name="cmbmetfpa" id="cmbmetfpa">
              <option value="SIN METODO" <?php print $la_fpa_metfpa[0];?>>SIN METODO</option>
              <option value="VENEZUELA" <?php print $la_fpa_metfpa[1];?>>VENEZUELA</option>
              <option value="MERCANTIL" <?php print $la_fpa_metfpa[2];?>>MERCANTIL</option>
			  <option value="CENTRAL" <?php print $la_fpa_metfpa[3];?>>CENTRAL BANCO UNIVERSAL</option>
            </select>
          </div></td>
        </tr>
        <tr>
          <td height="20" colspan="4" class="titulo-celdanew">Aporte  - Prestaci&oacute;n Antiguedad
            <div align="center"></div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">Antiguedad Complementaria </div></td>
          <td><div align="left">
            <input name="chkantcom" type="checkbox" class="sin-borde" id="chkantcom" value="1" <?php if($li_fps_antcom!="0"){print "checked";}?>>
          </div></td>
          <td><div align="right">Fracci&oacute;n Alicuota</div></td>
          <td><div align="left">
            <input name="chkfraali" type="checkbox" class="sin-borde" id="chkfraali" value="1" <?php if($li_fps_fraali!="0"){print "checked";}?>>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Incluir Alicuta de Bono Vacacional en Alicutoa de Bono de Fin de A&ntilde;o </div></td>
          <td><input name="chkincvacagui" type="checkbox" class="sin-borde" id="chkincvacagui" value="1" <?php if($li_fps_incvacagui!="0"){print "checked";}?>></td>
          <td><div align="right">Aplicar la Asignaci&oacute;n extra al Sueldo Diario </div></td>
          <td><div align="left">
            <input name="chkintasiextra" type="checkbox" class="sin-borde" id="chkintasiextra" value="1" <?php if($li_fps_intasiextra!="0"){print "checked";}?>>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">M&eacute;todo de FPS </div></td>
          <td>
            <div align="left">
              <select name="cmbmetfps" id="cmbmetfps">
                <option value="SIN METODO" <?php print $la_fps_metfps[0];?>>SIN METODO</option>
                <option value="CARIBE" <?php print $la_fps_metfps[1];?>>CARIBE</option>
                <option value="UNION" <?php print $la_fps_metfps[2];?>>UNION</option>
                <option value="MERCANTIL" <?php print $la_fps_metfps[3];?>>MERCANTIL</option>
                <option value="VENEZOLANO DE CREDITO" <?php print $la_fps_metfps[4];?>>VENEZOLANO DE CREDITO</option>
                <option value="BANCO DE VENEZUELA" <?php print $la_fps_metfps[5];?>>BANCO DE VENEZUELA</option>
                <option value="VENEZUELA" <?php print $la_fps_metfps[6];?>>VENEZUELA</option>
                <option value="BANCO PROVINCIAL" <?php print $la_fps_metfps[7];?>>BANCO PROVINCIAL</option>
                <option value="BANESCO" <?php print $la_fps_metfps[8];?>>BANESCO</option>
                <option value="CENTRAL BANCO UNIVERSAL" <?php print $la_fps_metfps[9];?>>CENTRAL BANCO UNIVERSAL</option>
                <option value="DEL SUR" <?php print $la_fps_metfps[10];?>>DEL SUR</option>
                <option value="BANCO INDUSTRIAL" <?php print $la_fps_metfps[11];?>>BANCO INDUSTRIA</option>
              </select>
            </div></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr class="titulo-celdanew">
          <td height="22" colspan="4"><div align="center">Mantenimiento</div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">Cuentas de Conceptos </div></td>
          <td><div align="left">
            <input name="txtcueconc" type="text" id="txtcueconc" value="<?php print $ls_man_cueconc;?>" onKeyPress="return keyRestrict(event,'1234567890'+',');">
          </div></td>
          <td><div align="right">Activar Bloqueo de F&oacute;rmula de Conceptos</div></td>
          <td><div align="left"><a href="javascript: ue_buscarcuentacaja();"></a>
            <input name="chkactblofor" type="checkbox" class="sin-borde" id="chkactblofor" value="1" <?php if($li_man_actblofor!="0"){print "checked";}?>>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">M&eacute;todo Resumen Contable </div></td>
          <td><div align="left">
            <select name="cmbmetrescon" id="cmbmetrescon">
              <option value="SIN METODO" <?php print $la_man_metrescon[0];?>>SIN METODO</option>
              <option value="METODO CTA_ABONO" <?php print $la_man_metrescon[1];?>>METODO CTA_ABONO</option>
            </select>
          </div></td>
          <td><div align="right"></div></td>
          <td><div align="left"></div></td>
        </tr>
        <tr>
          <td height="22" colspan="2"><div align="center">
                <input name="btnrepsubnomina" type="button" class="boton" id="btnrepsubnomina" value="Reparar Subn&oacute;minas"  style="width: 180px;" onClick="javascript: ue_repararsubnominas();" >
            	</div>   </td>
		  <td colspan="2"><div align="center">
                <input name="btnrepconceptopersonal" type="button" class="boton" id="btnrepconceptopersonal" style="width: 180px;" onClick="javascript: ue_repararconceptopersonal();" value="Reparar Concepto-Personal" >
              </div></td>
          </tr>
        <tr>
          <td height="22" colspan="2">           
              <div align="center">
                <input name="btnrecsueldointegral" type="button" class="boton" id="btnrecsueldointegral" style="width: 180px;" value="<?php if ($ls_sueint==""){print "Recalcular Sueldo Integral"; } else { print "Recalcular ".$ls_sueint;}?>" onClick="javascript: ue_recalcularsueldointegral();" >
              </div></td>
          <td colspan="2"><div align="center">
            <input name="btnmanhistoricos" type="button" class="boton" id="btnmanhistoricos"style="width: 180px;" onClick="javascript: ue_mantenimientohistoricos();" value="Mantenimiento Hist&oacute;ricos" >
          </div></td>
          </tr>
        <tr>
          <td height="22" colspan="2"><div align="center">
            <input name="btnrepacuconc" type="button" class="boton" id="btnrepacuconc" style="width: 180px;" value="Reparar Acumulado Conceptos" onClick="javascript: ue_repararacumuladoconceptos();" >
          </div></td>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td><div align="right"></div></td>
          <td colspan="3">
		      <div align="left">
		        <input name="operacion" type="hidden" id="operacion">
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
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
function ue_guardar()
{
	f=document.form1;
	li_cambiar=f.cambiar.value;
	ls_sueint=f.txtsueint.value;
	if(li_cambiar==1)
	{
		if ((f.chksueint.checked)&&(ls_sueint==""))
		{
			alert ('Seleccionó la opción Cambiar denominación Sueldo Integral. Debe ingresar la nueva denominación');
		}
		else if ((f.chkgenrecdocpagper.checked)&&(f.txttipdocpagper.value==""))
		{
			alert('Debe seleccionar el Tipo de Documento para el Pago del Personal');
		}
		else
		{
			f=document.form1;
			f.operacion.value="GUARDAR";
			f.action="sigesp_snorh_p_configuracion.php";
			f.submit();
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_cerrar()
{
	location.href="sigespwindow_blank.php";
}

function ue_repararsubnominas()
{
	f=document.form1;
	f.operacion.value="REPARARSUBNOMINAS";
	f.action="sigesp_snorh_p_configuracion.php";
	f.submit();
}

function ue_repararconceptopersonal()
{
	f=document.form1;
	f.operacion.value="REPARARCONCEPTOPERSONAL";
	f.action="sigesp_snorh_p_configuracion.php";
	f.submit();
}

function ue_recalcularsueldointegral()
{
	f=document.form1;
	f.operacion.value="RECALCULARSUELDOINTEGRAL";
	f.action="sigesp_snorh_p_configuracion.php";
	f.submit();
}

function ue_mantenimientohistoricos()
{
	f=document.form1;
	f.operacion.value="MANTENIMIENTOHISTORICOS";
	f.action="sigesp_snorh_p_configuracion.php";
	f.submit();
}

function ue_repararacumuladoconceptos()
{
	if(confirm("¿Este proceso actualizará todos los acumulados de los Conceptos según el cálculo de la nómina. Lo desea ejecutar?"))
	{
		f=document.form1;
		f.operacion.value="REPARARACUMULADOCONCEPTOS";
		f.action="sigesp_snorh_p_configuracion.php";
		f.submit();
	}
}

function ue_buscarcuentacontable(tipo)
{
	f=document.form1;
	if(f.chkparnom.checked==false)
	{
		if(tipo=="CONFIGURACION")
		{
			if(f.chkgenrecdoc.checked==false)
			{
				consue=ue_validarvacio(f.cmbconsue.value);
				if(consue=="OC")
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

function ue_buscarcuentacontablebene()
{
	f=document.form1;
	if(f.chkincperben.checked==true)
	{
		window.open("sigesp_sno_cat_cuentacontable.php?tipo=CONFIGURACIONPARAMETRO","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
}

function ue_buscardestino()
{
	f=document.form1;
	if(f.chkparnom.checked==false)
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

function ue_limpiar()
{
	f=document.form1;
	f.txtcodproben.value="";
	f.txtnombre.value="";
}

function ue_bloquear()
{
	f=document.form1;
	if(f.chkparnom.checked)
	{
		f.cmbconsue.disabled=true;
		f.cmbconapo.disabled=true;
		f.chkagrcon.disabled=true;
		f.chkgennotdeb.disabled=true;
		f.cmbdescon.disabled=true;
		f.chkgenrecdoc.disabled=true;
		f.chkgenrecdocapo.disabled=true;
		f.txttipdocnom.disabled=true;
		f.txttipdocapo.disabled=true;
		f.cmbconfidnom.disabled=true;
		f.chkrecdocfid.disabled=true;
		f.txttipdocfid.disabled=true;
		f.txtcueconfid.disabled=true;
		f.txtcodbenfid.disabled=true;
		f.chkgenrecdocpagper.disabled=true;
		f.txttipdocpagper.disabled=true;
		f.chkestctaalt.disabled=true;
		
		
	}
	else
	{
		f.cmbconsue.disabled=false;
		f.cmbconapo.disabled=false;
		f.chkagrcon.disabled=false;
		f.chkgennotdeb.disabled=false;
		f.cmbdescon.disabled=false;
		f.chkgenrecdoc.disabled=false;
		f.chkgenrecdocapo.disabled=false;
		f.txttipdocnom.disabled=false;
		f.txttipdocapo.disabled=false;
		f.cmbconfidnom.disabled=false;
		f.chkrecdocfid.disabled=false;
		f.txttipdocfid.disabled=false;
		f.txtcueconfid.disabled=false;
		f.txtcodbenfid.disabled=false;
		f.chkgenrecdocpagper.disabled=false;
		f.txttipdocpagper.disabled=false;
		f.chkestctaalt.disabled=false;
	}
}

function ue_bloquear2()
{
	f=document.form1;
	if(f.chkparfpj.checked)
	{
	    f.txtedadM.disabled="";
		f.txtedadM.disabled=false;
		f.txtedadF.disabled="";
		f.txtedadF.disabled=false;
		f.txtanoM.disabled="";
		f.txtanoM.disabled=false;
		f.txtanoT.disabled="";
		f.txtanoT.disabled=true;
		f.txtanoT.value=0;
	}
	else
	{
	    f.txtedadM.disabled="";
		f.txtedadM.disabled=true;
		f.txtedadM.value=0;
		f.txtedadF.disabled="";
		f.txtedadF.disabled=true;
		f.txtedadF.value=0;
		f.txtanoM.disabled="";
		f.txtanoM.disabled=true;
		f.txtanoM.value=0;
		f.txtanoT.disabled="";
		f.txtanoT.disabled=false;
	}
	
}
function ue_buscarcuentacaja()
{
	window.open("sigesp_sno_cat_cuentacontable.php?tipo=CONFIGURACIONCAJA","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_contabilizacionnomina()
{
	f=document.form1;
	if(f.chkparnom.checked==false)
	{
		f.chkgenrecdoc.checked=false;
		f.txttipdocnom.value="";
		f.txtcuecon.value="";
		f.chkgennotdeb.checked=false;
	}
}

function ue_recepcionnomina()
{
	f=document.form1;
	if(f.chkparnom.checked==false)
	{
		consulnom=ue_validarvacio(f.cmbconsue.value);
		if((consulnom!="OC"))
		{
			f.chkgenrecdoc.checked=false;
		}
		else
		{
			f.txttipdocnom.value="";
			f.txtcuecon.value="";
		}
	}
}

function ue_buscartipodocumento(tipo)
{
	f=document.form1;
	if(f.chkparnom.checked==false)
	{
		if(tipo=="NOMINA")
		{
			if(f.chkgenrecdoc.checked)
			{
				window.open("sigesp_snorh_cat_tipodocumento.php?tipo="+tipo+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
			}
		}
		if(tipo=="APORTE")
		{
			if(f.chkgenrecdocapo.checked)
			{
				window.open("sigesp_snorh_cat_tipodocumento.php?tipo="+tipo+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
			}
		}
		if(tipo=="FIDEICOMISO")
		{
			if(f.chkrecdocfid.checked)
			{
				window.open("sigesp_snorh_cat_tipodocumento.php?tipo="+tipo+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
			}
		}
		if(tipo=="PAGOPERSONAL")
		{
			if(f.chkgenrecdocpagper.checked)
			{
				window.open("sigesp_snorh_cat_tipodocumento.php?tipo="+tipo+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
			}
		}
	}
}

function ue_notadebito()
{
	f=document.form1;
	if(f.chkparnom.checked==false)
	{
		consulnom=ue_validarvacio(f.cmbconsue.value);
		if((consulnom=="OCP")||(consulnom=="CP"))
		{
			//f.chkgennotdeb.checked=true;
		}
		else
		{
			f.chkgennotdeb.checked=false;	
		}
	}
}

function ue_contabilizacionaportes()
{
	f=document.form1;
	if(f.chkparnom.checked==false)
	{
		f.chkgenrecdocapo.checked=false;
		f.txttipdocapo.value="";
	}
}

function ue_recepcionaportes()
{
	f=document.form1;
	if(f.chkparnom.checked==false)
	{
		conaponom=ue_validarvacio(f.cmbconapo.value);
		if((conaponom!="OC"))
		{
			f.chkgenrecdocapo.checked=false;
			f.txttipdocapo.value="";
		}
		else
		{
			f.txttipdocapo.value="";
		}
	}
}

function ue_personalbeneficiario()
{
	f=document.form1;
	if(f.chkincperben.checked==false)
	{
		f.txtcueconben.value="";
	}
}


function ue_contabilizacionfideicomiso()
{
	f=document.form1;
	if(f.chkparnom.checked==false)
	{
		f.chkrecdocfid.checked=false;
		f.txttipdocfid.value="";
	}
}

function ue_recepcionfideicomiso()
{
	f=document.form1;
	if(f.chkparnom.checked==false)
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

function ue_buscarbeneficiario()
{
	f=document.form1;
	if(f.chkparnom.checked==false)
	{
		window.open("sigesp_catdinamic_bene.php?tipo=FIDEICOMISO","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
}

function ue_buscarconcepto(tipo)
{
	window.open("sigesp_sno_cat_concepto.php?tipo="+tipo+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}


function activar_denominacion()
{
	f=document.form1;
	if(f.chksueint.checked==true)
	{
		f.txtsueint.readOnly=false;
		
	}
	else
	{
		f.txtsueint.value="";
		f.txtsueint.readOnly=true;
	}
}
function ue_chequear_nomina_beneficiario()
{
	f=document.form1;
	if(((f.cmbconsue.value!="OC")||(f.chkgenrecdoc.checked==false))&&(f.chkestctaalt.checked))
	{
		alert("Esta Opción es valida solo para Nóminas Compromete y Causa que Generen Recepción de Documento.");
		f.chkestctaalt.checked=false;
	}
}

</script> 
</html>