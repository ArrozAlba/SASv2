<?php
class sigesp_sno_c_metodo_banco
{
	var $io_metodo1;
	var $io_metodo2;
	var $io_funciones;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sno_c_metodo_banco()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sno_c_metodo_banco
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. María Roa
		// Fecha Creación: 01/01/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 04/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_datastore.php");
		$this->DS=new class_datastore();
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad= new sigesp_c_seguridad();
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();
		require_once("sigesp_sno_c_metodo_banco_1.php");
		$this->io_metodo1=new sigesp_sno_c_metodo_banco_1();
		require_once("sigesp_sno_c_metodo_banco_2.php");
		$this->io_metodo2=new sigesp_sno_c_metodo_banco_2();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$this->ls_codnom="0000";
		$this->ls_peractnom="000";
		if(array_key_exists("la_nomina",$_SESSION))
		{
			$this->ls_codnom=$_SESSION["la_nomina"]["codnom"];
			$this->ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
		}
	}// end function sigesp_sno_c_metodo_banco
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco($as_ruta,$as_metodo,$ac_codperi,$ad_fdesde,$ad_fhasta,$ad_fecproc,$adec_montot,$as_codcueban,
							 &$rs_data,$as_codmetban,$as_desope,$as_quincena,$as_ref,$aa_seguridad)
	{ 	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco	
		//	    Arguments: as_ruta   // ruta donde se va aguardar el archivo
		//	    		   as_metodo   // Código del metodo a banco
		//                 ac_codperi  // codigo del periodo
		//                 ad_fdesde   // fecha desde
		//                 ad_fhasta   // fecha hasta
		//                 adec_montot // Monto total
		//                 as_codcueban // Código de la cuenta bancaria a debitar 
		//                 aa_ds_banco // arreglo (datastore) datos banco      
		//                 as_codmetban // código de método a banco 
		//                 as_desope // descripción de operación
		//                 as_quincena // Quincena  apagar
		//				   aa_seguridad // arreglo de seguridad
		//	      Returns: lb_valido True 
		//	  Description: Funcion que segun el banco, genera un archivo txt a disco para cancelación de nomina
		//	   Creado Por: Ing. María Roa
		// Fecha Creación: 01/01/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 04/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
	  	switch ($as_metodo)
		{
			case "BANESCO":
				$lb_valido=$this->io_metodo1->uf_metodo_banco_banesco($as_ruta,$rs_data,$ad_fecproc);
				break;

			case "BANESCO_PAYMUL":
				$lb_valido=$this->io_metodo1->uf_metodo_banco_banesco_paymul($as_ruta,$rs_data,$ad_fecproc,$adec_montot,
				                                                             $as_codcueban,$as_ref);
				break;

			case "BANESCO_PAYMUL_TERCEROS":
				$lb_valido=$this->io_metodo1->uf_metodo_banco_banesco_paymul_terceros($as_ruta,$rs_data,$ad_fecproc,$adec_montot,
				                                                             $as_codcueban,$as_ref);
				break;

			case "BANFOANDES":
				$lb_valido=$this->io_metodo1->uf_metodo_banco_banfoandes($as_ruta,$rs_data,$ad_fecproc,$as_codcueban,$adec_montot);
				break;
				
			case "BANFOANDES_IPSFA":
				$lb_valido=$this->io_metodo1->uf_metodo_banco_banfoandes_ipsfa($as_ruta,$rs_data,$ad_fecproc,$as_codcueban,$adec_montot);
				break;
				
			case "BIV VERSION 2":
				$lb_valido=$this->io_metodo1->uf_metodo_banco_biv_version_2($as_ruta,$rs_data,$as_codmetban);
				break;

			case "BOD NUEVO":
				$lb_valido=$this->io_metodo1->uf_metodo_banco_bod($as_ruta,$ac_codperi,$ad_fdesde,$ad_fhasta,$rs_data);
				break;
				
			case "BOD VERSION 3":
				$lb_valido=$this->io_metodo1->uf_metodo_banco_bod_version_3($as_ruta,$rs_data,$ad_fecproc,$as_codmetban);
				break;

			case "BOD VIEJO":
				$lb_valido=$this->io_metodo1->uf_metodo_banco_bod_viejo($as_ruta,$rs_data,$ad_fecproc,$as_codmetban);
				break;

			case "CANARIAS":
				$lb_valido=$this->io_metodo1->uf_metodo_banco_canarias($as_ruta,$rs_data,$ad_fhasta);
				break;

			case "CARACAS":
				$lb_valido=$this->io_metodo1->uf_metodo_banco_caracas($as_ruta,$rs_data,$adec_montot,$as_codcueban);
				break;

			case "CARIBE":
				$lb_valido=$this->io_metodo1->uf_metodo_banco_caribe($as_ruta,$rs_data,$adec_montot,$ad_fecproc);
				break;
				
			case "CARONI":
				$lb_valido=$this->io_metodo1->uf_metodo_banco_caroni($as_ruta,$rs_data);
				break;

			case "CASA PROPIA":
				$lb_valido=$this->io_metodo1->uf_metodo_banco_casapropia($as_ruta,$rs_data,$ad_fecproc,$as_codcueban,$adec_montot);
				break;

			case "CASA PROPIA 2003":
				$lb_valido=$this->io_metodo1->uf_metodo_banco_casa_propia_2003($as_ruta,$rs_data);
				break;
				
			case "CENTRAL":
				$lb_valido=$this->io_metodo1->uf_metodo_banco_central($as_ruta,$rs_data,$as_codcueban,$adec_montot);
				break;

			case "CENTRAL VERSION 1":
				$lb_valido=$this->io_metodo1->uf_metodo_banco_central_v1($as_ruta,$rs_data,$as_codcueban,$adec_montot);
				break;

			case "CONFEDERADO":
				$lb_valido=$this->io_metodo1->uf_metodo_banco_confederado($as_ruta,$rs_data);
				break;
				
			case "DEL SUR E.A.P.":
				$lb_valido=$this->io_metodo1->uf_metodo_banco_del_sur_eap($as_ruta,$rs_data,$ad_fhasta,$as_codmetban);
				break;

			case "EAP_MICASA":
				$lb_valido=$this->io_metodo2->uf_metodo_banco_eap_micasa($as_ruta,$rs_data);
				break;

			case "FONDO COMUN":
				$lb_valido=$this->io_metodo2->uf_metodo_banco_fondo_comun($as_ruta,$rs_data,$ad_fecproc,$as_codcueban,$as_codmetban,$as_desope);
				break;

			case "INDUSTRIAL":
				$lb_valido=$this->io_metodo2->uf_metodo_banco_industrial($as_ruta,$rs_data);
				break;

			case "LARA":
				$lb_valido=$this->io_metodo2->uf_metodo_banco_lara($as_ruta,$rs_data,$as_codcueban,$adec_montot);
				break;

			case "MERCANTIL":
				$lb_valido=$this->io_metodo2->uf_metodo_banco_mercantil($as_ruta,$rs_data,$ad_fecproc,$as_codcueban,$adec_montot);
				break;

			case "MI CASA":
				$lb_valido=$this->io_metodo2->uf_metodo_banco_mi_casa($as_ruta,$rs_data);
				break;

			case "e-PROVINCIAL":
				$lb_valido=$this->io_metodo1->uf_metodo_banco_e_provincial($as_ruta,$rs_data);
				break;
				
			case "e-PROVINCIAL_02":
				$lb_valido=$this->io_metodo1->uf_metodo_banco_e_provincial_02($as_ruta,$rs_data);
				break;
				
			case "e-PROVINCIAL_03":
				$lb_valido=$this->io_metodo1->uf_metodo_banco_e_provincial_03($as_ruta,$rs_data);
				break;

			case "PROVINCIAL GUANARE":
				$lb_valido=$this->io_metodo2->uf_metodo_banco_provincial_guanare($as_ruta,$rs_data,$ad_fecproc);
				break;

			case "PROVINCIAL NUEVO":
				$lb_valido=$this->io_metodo2->uf_metodo_banco_provincial($as_ruta,$rs_data,$ad_fecproc,$as_codcueban,$adec_montot);
				break;

			case "PROVINCIAL VIEJO": 
				$lb_valido=$this->io_metodo2->uf_metodo_banco_lara($as_ruta,$rs_data,$as_codcueban,$adec_montot);
				break;
				
			case "PROVINCIAL_ALTAMIRA": 
			    $lb_valido=$this->io_metodo1->uf_metodo_banco_provincial_altamira($as_ruta,$rs_data,$as_codmetban,$as_codcueban,$adec_montot,$ad_fecproc);
				break;
				
			case "PROVINCIAL PENSIONES":
				$lb_valido=$this->io_metodo2->uf_metodo_banco_provincial_pensiones($as_ruta,$rs_data,$ad_fecproc,$as_codcueban,$adec_montot);
				break;
				
			case "PROVINCIAL BBVAcash":
				$lb_valido=$this->io_metodo1->uf_metodo_banco_provincial_BBVAcash($as_ruta,$rs_data);
				break;	
			
			case "SOFITASA":
				$lb_valido=$this->io_metodo2->uf_metodo_banco_sofitasa($as_ruta,$rs_data,$ad_fecproc,$as_codmetban);
				break;

			case "V2_CARONI":
				$lb_valido=$this->io_metodo2->uf_metodo_banco_caroni_v_2($as_ruta,$rs_data,$ad_fecproc);
				break;

			case "VENEZUELA":
				$lb_valido=$this->io_metodo2->uf_metodo_banco_venezuela($as_ruta,$rs_data,$ad_fecproc,$as_codcueban,$adec_montot);
				break;

			case "VENEZUELA_SNG":
				$lb_valido=$this->io_metodo2->uf_metodo_banco_venezuela_sng($as_ruta,$rs_data,$ad_fecproc,$as_codcueban,$adec_montot);
				break;
				
			case "VENEZUELA PENSIONES":
				$lb_valido=$this->io_metodo2->uf_metodo_banco_venezuela_pensiones($as_ruta,$rs_data,$ad_fecproc,$as_codcueban,$adec_montot);
				break;

			case "BANPRO":
				$lb_valido=$this->io_metodo2->uf_metodo_banpro($as_ruta,$rs_data,$ad_fecproc,$as_codcueban,$adec_montot);
				break;

			case "BANFOTRAN":
				$lb_valido=$this->io_metodo2->uf_metodo_banco_banfotran($as_ruta,$rs_data,$ad_fecproc,$as_codcueban,$adec_montot,$as_codmetban);
				break;

			case "VENEZUELA PAGO TAQUILLA":
				$lb_valido=$this->io_metodo2->uf_metodo_banco_venezuela_pagotaquilla($as_ruta,$rs_data,$ad_fecproc,$as_codcueban,$adec_montot);
				break;
				
			//case "VENEZUELA_SNG_INGRESO":
				//$lb_valido=$this->io_metodo2->uf_metodo_banco_venezuela_sng_ingreso($rs_data,$ad_fecproc,$as_codcuenta);
				//break;				

			case "VENEZUELA ESPECIAL":
				$lb_valido=$this->io_metodo2->uf_metodo_banco_venezuelaespecial($as_ruta,$rs_data,$ad_fecproc,$as_codcueban,$adec_montot);
				break;

			case "ONLINE_MERCANTIL":
				$lb_valido=$this->io_metodo2->uf_metodo_banco_mercantilonline($as_ruta,$rs_data,$ad_fecproc,$as_codcueban,$adec_montot,$as_codmetban);
				break;

			case "VENEZUELA TARJETA PREPAGADA Y CUENTA ABONO":
				$lb_valido=$this->io_metodo2->uf_metodo_banco_venezuela_prepagoabono($as_ruta,$rs_data,$ad_fecproc,$as_codcueban,$adec_montot);
				break;

			case "BANCO FEDERAL":
				$lb_valido=$this->io_metodo2->uf_metodo_banco_federal($as_ruta,$rs_data,$ad_fecproc,$as_codcueban,$as_codmetban,$as_desope,
																	  $ad_fdesde,$ad_fhasta,$adec_montot,$as_quincena);
				break;
				
			case "BANCO FEDERAL CONSOLIDADO":
				$lb_valido=$this->io_metodo2->uf_metodo_banco_federal_consolidado($as_ruta,$rs_data,$ad_fecproc,$as_codcueban,$as_codmetban,$as_desope,
																	  $ad_fdesde,$ad_fhasta,$adec_montot,$as_quincena);
				break;
/*
			case "BANCO AGRICOLA":
				$lb_valido=$this->io_metodo2->uf_metodo_banco_agricola($as_ruta,$rs_data);
				break;
	*/			
		    case "CORPBANCA":
			    $lb_valido=$this->uf_periodo(&$ls_codperi,&$ls_perides,&$ls_perihas);
				$lb_valido=$this->io_metodo1->uf_metodo_banco_corp_banca($as_ruta,$rs_data,$adec_montot,
				                                                         $ls_codperi,$ls_perides,$ls_perihas);
				break;
				
			case "BANCO_DEL_TESORO":
				$lb_valido=$this->io_metodo1->uf_metodo_banco_deltesoro($as_ruta,$rs_data,$ad_fecproc,$as_codmetban);
				break;
			
			case "BANCO_DEL_TESORO_2008":
				$lb_valido=$this->io_metodo1->uf_metodo_banco_deltesoro_2008($as_ruta,$rs_data,$ad_fecproc,$as_codmetban,
				                                                             $adec_montot,$as_codcueban);
				break;
				
			case "FONZ03":
				$lb_valido=$this->io_metodo2->uf_metodo_fonz03($as_ruta,$rs_data);
				break;
				
			case "FONZ03 NOMINA MILITAR":
				$lb_valido=$this->io_metodo2->uf_metodo_fonz03_militar($as_ruta,$rs_data);
				break;
            
			case "BANCO AGRICOLA":
				$lb_valido=$this->io_metodo2->uf_metodo_banco_banagricola($as_ruta,$aa_ds_banco,$ad_fecproc,$as_codcueban,$adec_montot,$as_mes);
				break;	
				
			case "BANCO AGRICOLA NOMINA":
				$lb_valido=$this->io_metodo2->uf_metodo_banco_agricola($as_ruta,$rs_data);
				//$lb_valido=$this->io_metodo2->uf_metodo_banco_agricola($as_ruta,$aa_ds_banco,$ad_fecproc,$as_codcueban,$adec_montot,$as_mes);
				break;	
				
			default:
				$this->io_mensajes->message("El método seleccionado no esta disponible.");
				break;

			
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion ="Generó el disco al banco Método ".$as_metodo." Período ".$ac_codperi." nómina ".$this->ls_codnom." ";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}// end function uf_metodo_banco
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_listadobanco_gendisk($as_codban,$as_suspendidos,$as_quincena,$as_pagtaqnom,&$rs_data,$as_tipocuenta='',$pago_otros_bancos='')
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listadobanco_gendisk
		//		   Access: public (desde la clase sigesp_sno_r_listadobanco)  
		//	    Arguments: as_codban // Código del banco del que se desea busca el personal
		//	    		   as_suspendidos // si se busca a toto del personal ó solo los activos
		//	    		   as_quincena // Quincena para el cual se quiere filtar
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que tienen asociado el banco 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 04/05/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_monto="";
		$ls_montoaux="";
		switch($as_quincena)
		{
			case 1: // Primera Quincena
				$ls_monto="sno_resumen.priquires as monnetres";
				$ls_montoaux="sno_resumen.priquires";
				break;

			case 2: // Segunda Quincena
				$ls_monto="sno_resumen.segquires as monnetres";
				$ls_montoaux="sno_resumen.segquires";
				break;

			case 3: // Mes Completo
				$ls_monto="sno_resumen.monnetres as monnetres";
				$ls_montoaux="sno_resumen.monnetres";
				break;
		}
		switch($as_pagtaqnom)
		{
			case "0": // Depósito a banco
				$ls_criterio = $ls_criterio."   AND sno_personalnomina.pagbanper=1 ".
										    "   AND sno_personalnomina.pagtaqper=0 ".
										    "   AND sno_personalnomina.pagefeper=0 ";
				break;

			case "1": // Pago por Taquilla
				$ls_criterio = $ls_criterio."   AND sno_personalnomina.pagbanper=0 ".
										    "   AND sno_personalnomina.pagtaqper=1 ".
										    "   AND sno_personalnomina.pagefeper=0 ";
				break;
		}
		if(!empty($as_codban) && empty($pago_otros_bancos))
		{
			$ls_criterio = $ls_criterio." AND sno_personalnomina.codban='".$as_codban."' ";
		}
		if($as_suspendidos=="1") // Mostrar solo el personal suspendido
		{
			$ls_criterio = $ls_criterio." AND (sno_personalnomina.staper='1' OR sno_personalnomina.staper='2')";
		}
		if(!empty($as_tipocuenta))
		{
			$ls_criterio = $ls_criterio."   AND sno_personalnomina.tipcuebanper='".$as_tipocuenta."'";
		}		
		$ls_sql="SELECT sno_personal.codper, sno_personalnomina.codban, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personal.nacper, ".
				"		sno_personalnomina.codcueban, sno_personalnomina.tipcuebanper, ".$ls_monto.", sno_nomina.desnom,sno_nomina.codnom, sno_nomina.tippernom,".
				"		(SELECT SUM(".$ls_montoaux.") ".
				"		  FROM sno_personalnomina, sno_personal, sno_resumen ".
				"  		 WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   	   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
				"   	   AND sno_resumen.codperi='". $this->ls_peractnom."' ".
				"   	   AND sno_resumen.monnetres > 0 ".
				$ls_criterio.
				" 	 	  AND sno_personalnomina.codemp = sno_resumen.codemp ".
				"         AND sno_personalnomina.codnom = sno_resumen.codnom ".
				"         AND sno_personalnomina.codper = sno_resumen.codper ".
				"         AND sno_personal.codemp = sno_personalnomina.codemp ".
				"	      AND sno_personal.codper = sno_personalnomina.codper ".
				"         AND sno_personal.cedper <> sno_personalnomina.codcueban) AS totalabono, ".
				"		(SELECT SUM(".$ls_montoaux.") ".
				"		  FROM sno_personalnomina, sno_personal, sno_resumen ".
				"  		 WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   	   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
				"   	   AND sno_resumen.codperi='". $this->ls_peractnom."' ".
				"   	   AND sno_resumen.monnetres > 0 ".
				$ls_criterio.
				" 	 	  AND sno_personalnomina.codemp = sno_resumen.codemp ".
				"         AND sno_personalnomina.codnom = sno_resumen.codnom ".
				"         AND sno_personalnomina.codper = sno_resumen.codper ".
				"         AND sno_personal.codemp = sno_personalnomina.codemp ".
				"	      AND sno_personal.codper = sno_personalnomina.codper ".
				"         AND sno_personal.cedper = sno_personalnomina.codcueban) AS totalprepago, ".
				"		(SELECT COUNT(sno_personalnomina.codper) ".
				"		  FROM sno_personalnomina, sno_personal, sno_resumen ".
				"  		 WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   	   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
				"   	   AND sno_resumen.codperi='". $this->ls_peractnom."' ".
				"   	   AND sno_resumen.monnetres > 0 ".
				$ls_criterio.
				" 	 	  AND sno_personalnomina.codemp = sno_resumen.codemp ".
				"         AND sno_personalnomina.codnom = sno_resumen.codnom ".
				"         AND sno_personalnomina.codper = sno_resumen.codper ".
				"         AND sno_personal.codemp = sno_personalnomina.codemp ".
				"	      AND sno_personal.codper = sno_personalnomina.codper ".
				"         AND sno_personal.cedper = sno_personalnomina.codcueban) AS nroprepago ".
				"  FROM sno_personal, sno_personalnomina, sno_resumen, sno_nomina  ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_resumen.codperi='". $this->ls_peractnom."' ".
				"   AND sno_resumen.monnetres > 0 ".
				$ls_criterio.
				"	AND sno_personalnomina.codemp = sno_resumen.codemp ".
				"   AND sno_personalnomina.codnom = sno_resumen.codnom ".
				"   AND sno_personalnomina.codper = sno_resumen.codper ".
				"	AND sno_personalnomina.codemp = sno_nomina.codemp ".
				"   AND sno_personalnomina.codnom = sno_nomina.codnom ".
				"   AND sno_personal.codemp = sno_personalnomina.codemp ".
				"	AND sno_personal.codper = sno_personalnomina.codper ".
				"ORDER BY sno_personalnomina.tipcuebanper, sno_personal.codper ";
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_listadobanco_gendisk ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_listadobanco_gendisk
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_listadobanco_gendisk_consolidado($as_codban,$as_suspendidos,$as_quincena,$as_codnomdes,$as_codnomhas,
												 $as_codperdes,$as_codperhas,$as_pagtaqnom,$as_anocurnom,&$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listadobanco_gendisk_consolidado
		//		   Access: public (desde la clase sigesp_snorh_r_listadobanco)  
		//	    Arguments: as_codban // Código del banco del que se desea busca el personal
		//	    		   as_suspendidos // si se busca a toto del personal ó solo los activos
		//	    		   as_quincena // Quincena para el cual se quiere filtar
		//	    		   as_codnomdes // Código de nómina desde el cual se quiere filtrar
		//	    		   as_codnomhas // Código de nómina hasta el cual se quiere filtrar
		//	    		   as_codperdes // Período desde el cual se quiere filtrar
		//	    		   as_codperhas // Período hasta el cual se quiere filtrar
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que tienen asociado el banco 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/05/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_monto="";
		switch($as_quincena)
		{
			case 1: // Primera Quincena
				$ls_monto="sno_hresumen.priquires as monnetres";
				break;

			case 2: // Segunda Quincena
				$ls_monto="sno_hresumen.segquires as monnetres";
				break;

			case 3: // Mes Completo
				$ls_monto="sno_hresumen.monnetres as monnetres";
				break;
		}
		switch($as_pagtaqnom)
		{
			case "0": // Depósito a banco
				$ls_criterio = $ls_criterio."   AND sno_hpersonalnomina.pagbanper=1 ".
										    "   AND sno_hpersonalnomina.pagtaqper=0 ".
										    "   AND sno_hpersonalnomina.pagefeper=0 ";
				break;

			case "1": // Pago por Taquilla
				$ls_criterio = $ls_criterio."   AND sno_hpersonalnomina.pagbanper=0 ".
										    "   AND sno_hpersonalnomina.pagtaqper=1 ".
										    "   AND sno_hpersonalnomina.pagefeper=0 ";
				break;
		}
		if(!empty($as_codban))
		{
			$ls_criterio = $ls_criterio." AND sno_hpersonalnomina.codban='".$as_codban."' ";
		}
		if($as_suspendidos=="1") // Mostrar solo el personal suspendido
		{
			$ls_criterio = $ls_criterio." AND (sno_hpersonalnomina.staper='1' OR sno_hpersonalnomina.staper='2')";
		}
		$ls_sql="SELECT sno_personal.codper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personal.nacper, ".
				"		sno_hpersonalnomina.codcueban, sno_hpersonalnomina.tipcuebanper, ".$ls_monto.",sno_hnomina.codnom,sno_hnomina.desnom, sno_hnomina.tippernom ".
				"  FROM sno_personal, sno_hpersonalnomina, sno_hresumen, sno_hnomina  ".
				" WHERE sno_hpersonalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_hpersonalnomina.codnom>='".$as_codnomdes."' ".
				"   AND sno_hpersonalnomina.codnom<='".$as_codnomhas."' ".
				"	AND sno_hpersonalnomina.anocur = '".$as_anocurnom."'".
				"   AND sno_hresumen.codperi>='".$as_codperdes."' ".
				"   AND sno_hresumen.codperi<='".$as_codperhas."' ".
				"   AND sno_hresumen.monnetres > 0 ".
				$ls_criterio.
				"	AND sno_hpersonalnomina.codemp = sno_hresumen.codemp ".
				"	AND sno_hpersonalnomina.anocur = sno_hresumen.anocur ".
				"	AND sno_hpersonalnomina.codperi = sno_hresumen.codperi ".
				"   AND sno_hpersonalnomina.codnom = sno_hresumen.codnom ".
				"   AND sno_hpersonalnomina.codper = sno_hresumen.codper ".
				"	AND sno_hpersonalnomina.codemp = sno_hnomina.codemp ".
				"	AND sno_hpersonalnomina.anocur = sno_hnomina.anocurnom ".
				"	AND sno_hpersonalnomina.codperi = sno_hnomina.peractnom ".
				"   AND sno_hpersonalnomina.codnom = sno_hnomina.codnom ".
				"   AND sno_personal.codemp = sno_hpersonalnomina.codemp ".
				"	AND sno_personal.codper = sno_hpersonalnomina.codper ".
				"ORDER BY sno_hpersonalnomina.tipcuebanper, sno_personal.codper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_listadobanco_gendisk_consolidado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_listadobanco_gendisk_consolidado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_listadobanco_gendisk_beneficiarios($as_codban,$as_suspendidos,$as_quincena,&$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listadobanco_gendisk_beneficiarios
		//		   Access: public (desde la clase sigesp_sno_r_listadobeneficiario)  
		//	    Arguments: as_codban // Código del banco del que se desea busca el personal
		//	    		   as_suspendidos // si se busca a toto del personal ó solo los activos
		//	    		   as_quincena // Quincena para el cual se quiere filtar
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que tienen asociado el banco 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 04/05/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_monto="";
		switch($as_quincena)
		{
			case 1: // Primera Quincena
				$ls_monto="sno_resumen.priquires";
				break;

			case 2: // Segunda Quincena
				$ls_monto="sno_resumen.segquires";
				break;

			case 3: // Mes Completo
				$ls_monto="sno_resumen.monnetres";
				break;
		}
		$ls_criterio = $ls_criterio."   AND (sno_personalnomina.staper='1' OR sno_personalnomina.staper='2')";
		$ls_criterio = $ls_criterio."   AND sno_beneficiario.forpagben='1' ";
		if(!empty($as_codban))
		{
			$ls_criterio = $ls_criterio." AND sno_beneficiario.codban='".$as_codban."' ";
		}
		$ls_sql="SELECT (sno_beneficiario.codben) AS codper, (sno_beneficiario.cedben) AS cedper, (sno_beneficiario.nomben) as nomper, (sno_beneficiario.apeben) as apeper, ".
				"		(sno_beneficiario.nacben) AS nacper, (sno_beneficiario.ctaban) AS codcueban, (sno_beneficiario.tipcueben) AS tipcuebanper, sno_nomina.desnom, sno_nomina.tippernom,".
				"		(CASE sno_beneficiario.monpagben WHEN 0 ".
				"										 THEN ((".$ls_monto.")*sno_beneficiario.porpagben)/100 ".
				"										 ELSE monpagben END) AS monnetres ".
				"  FROM sno_personal, sno_personalnomina, sno_resumen, sno_nomina, sno_beneficiario  ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_resumen.codperi='". $this->ls_peractnom."' ".
				"   AND sno_resumen.monnetres > 0 ".
				$ls_criterio.
				"	AND sno_personalnomina.codemp = sno_resumen.codemp ".
				"   AND sno_personalnomina.codnom = sno_resumen.codnom ".
				"   AND sno_personalnomina.codper = sno_resumen.codper ".
				"	AND sno_personalnomina.codemp = sno_nomina.codemp ".
				"   AND sno_personalnomina.codnom = sno_nomina.codnom ".
				"   AND sno_personal.codemp = sno_personalnomina.codemp ".
				"	AND sno_personal.codper = sno_personalnomina.codper ".
				"   AND sno_personal.codemp = sno_beneficiario.codemp ".
				"   AND sno_personal.codper = sno_beneficiario.codper ".
				"ORDER BY sno_personalnomina.tipcuebanper, sno_personal.codper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_listadobanco_gendisk_beneficiarios ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_listadobanco_gendisk_beneficiarios
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_montototal($as_codban,$as_suspendidos,$as_quincena,$as_pagtaqnom,&$ad_monto,$as_tipocuenta='')
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_load_montototal
		//		   Access: public (desde la clase sigesp_sno_r_listadobanco)  
		//	    Arguments: as_codban // Código del banco del que se desea busca el personal
		//	    		   as_suspendidos // si se busca a toto del personal ó solo los activos
		//	    		   as_quincena // Quincena para el cual se quiere filtar
		//	    		   ad_monto // monto total a pagar
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que tienen asociado el banco 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 04/05/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_monto="";
		switch($as_quincena)
		{
			case 1: // Primera Quincena
				$ls_monto="sno_resumen.priquires";
				break;

			case 2: // Segunda Quincena
				$ls_monto="sno_resumen.segquires";
				break;

			case 3: // Mes Completo
				$ls_monto="sno_resumen.monnetres";
				break;
		}
		switch($as_pagtaqnom)
		{
			case "0": // Depósito a banco
				$ls_criterio = $ls_criterio."   AND sno_personalnomina.pagbanper=1 ".
										    "   AND sno_personalnomina.pagtaqper=0 ".
										    "   AND sno_personalnomina.pagefeper=0 ";
				break;

			case "1": // Pago por Taquilla
				$ls_criterio = $ls_criterio."   AND sno_personalnomina.pagbanper=0 ".
										    "   AND sno_personalnomina.pagtaqper=1 ".
										    "   AND sno_personalnomina.pagefeper=0 ";
				break;
		}
		if(!empty($as_codban))
		{
			$ls_criterio = $ls_criterio." AND sno_personalnomina.codban='".$as_codban."' ";
		}
		if($as_suspendidos=="1") // Mostrar solo el personal suspendido
		{
			$ls_criterio = $ls_criterio." AND (sno_personalnomina.staper='1' OR sno_personalnomina.staper='2')";
		}
		if(!empty($as_tipocuenta))
		{
			$ls_criterio = $ls_criterio."   AND sno_personalnomina.tipcuebanper='".$as_tipocuenta."'";
		}		
		$ls_sql="SELECT sum(".$ls_monto.") as total ".
				"  FROM sno_personalnomina, sno_resumen  ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_resumen.codperi='". $this->ls_peractnom."' ".
				"   AND ".$ls_monto." > 0 ".
				$ls_criterio.
				"   AND sno_personalnomina.codemp = sno_resumen.codemp ".
				"   AND sno_personalnomina.codnom = sno_resumen.codnom ".
				"   AND sno_personalnomina.codper = sno_resumen.codper ".
				" GROUP BY sno_resumen.codperi";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_load_montototal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ad_monto=$row["total"];
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_load_montototal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_montototal_consolidado($as_codban,$as_suspendidos,$as_quincena,$as_codnomdes,$as_codnomhas,$as_codperdes,
											$as_codperhas,$as_pagtaqnom,$as_anocurnom,&$ad_monto)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_load_montototal_consolidado
		//		   Access: public (desde la clase sigesp_sno_r_listadobanco)  
		//	    Arguments: as_codban // Código del banco del que se desea busca el personal
		//	    		   as_suspendidos // si se busca a toto del personal ó solo los activos
		//	    		   as_quincena // Quincena para el cual se quiere filtar
		//	    		   as_codnomdes // Código de nómina desde el cual se quiere filtrar
		//	    		   as_codnomhas // Código de nómina hasta el cual se quiere filtrar
		//	    		   as_codperdes // Período desde el cual se quiere filtrar
		//	    		   as_codperhas // Período hasta el cual se quiere filtrar
		//	    		   ad_monto // monto total a pagar
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que tienen asociado el banco 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/05/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_monto="";
		switch($as_quincena)
		{
			case 1: // Primera Quincena
				$ls_monto="sno_hresumen.priquires";
				break;

			case 2: // Segunda Quincena
				$ls_monto="sno_hresumen.segquires";
				break;

			case 3: // Mes Completo
				$ls_monto="sno_hresumen.monnetres";
				break;
		}
		switch($as_pagtaqnom)
		{
			case "0": // Depósito a banco
				$ls_criterio = $ls_criterio."   AND sno_hpersonalnomina.pagbanper=1 ".
										    "   AND sno_hpersonalnomina.pagtaqper=0 ".
										    "   AND sno_hpersonalnomina.pagefeper=0 ";
				break;

			case "1": // Pago por Taquilla
				$ls_criterio = $ls_criterio."   AND sno_hpersonalnomina.pagbanper=0 ".
										    "   AND sno_hpersonalnomina.pagtaqper=1 ".
										    "   AND sno_hpersonalnomina.pagefeper=0 ";
				break;
		}
		if(!empty($as_codban))
		{
			$ls_criterio = $ls_criterio." AND sno_hpersonalnomina.codban='".$as_codban."' ";
		}
		if($as_suspendidos=="1") // Mostrar solo el personal suspendido
		{
			$ls_criterio = $ls_criterio." AND (sno_hpersonalnomina.staper='1' OR sno_hpersonalnomina.staper='2')";
		}
		$ls_sql="SELECT sum(".$ls_monto.") as total ".
				"  FROM sno_hpersonalnomina, sno_hresumen  ".
				" WHERE sno_hpersonalnomina.codemp='".$this->ls_codemp."' ".
				"	AND sno_hpersonalnomina.anocur = '".$as_anocurnom."'".
				"   AND sno_hpersonalnomina.codnom>='".$as_codnomdes."' ".
				"   AND sno_hpersonalnomina.codnom<='".$as_codnomhas."' ".
				"   AND sno_hresumen.codperi>='".$as_codperdes."' ".
				"   AND sno_hresumen.codperi<='".$as_codperhas."' ".
				"   AND ".$ls_monto." > 0 ".
				$ls_criterio.
				"   AND sno_hpersonalnomina.codemp = sno_hresumen.codemp ".
				"	AND sno_hpersonalnomina.anocur = sno_hresumen.anocur ".
				"	AND sno_hpersonalnomina.codperi = sno_hresumen.codperi ".
				"   AND sno_hpersonalnomina.codnom = sno_hresumen.codnom ".
				"   AND sno_hpersonalnomina.codper = sno_hresumen.codper ".
				" GROUP BY sno_hresumen.anocur ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_load_montototal_consolidado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ad_monto=$row["total"];
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_load_montototal_consolidado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_montototal_beneficiarios($as_codban,$as_suspendidos,$as_quincena,&$ad_monto)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_load_montototal_beneficiarios
		//		   Access: public (desde la clase sigesp_sno_r_listadobeneficiarios)  
		//	    Arguments: as_codban // Código del banco del que se desea busca el personal
		//	    		   as_suspendidos // si se busca a toto del personal ó solo los activos
		//	    		   as_quincena // Quincena para el cual se quiere filtar
		//	    		   ad_monto // monto total a pagar
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los beneficiarios que tiene el personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 19/11/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_monto="";
		switch($as_quincena)
		{
			case 1: // Primera Quincena
				$ls_monto="sno_resumen.priquires";
				break;

			case 2: // Segunda Quincena
				$ls_monto="sno_resumen.segquires";
				break;

			case 3: // Mes Completo
				$ls_monto="sno_resumen.monnetres";
				break;
		}
		$ls_criterio = $ls_criterio."   AND (sno_personalnomina.staper='1' OR sno_personalnomina.staper='2')";
		$ls_criterio = $ls_criterio."   AND sno_beneficiario.forpagben='1' ";
		if(!empty($as_codban))
		{
			$ls_criterio = $ls_criterio." AND sno_beneficiario.codban='".$as_codban."' ";
		}
		$ls_sql="SELECT SUM(CASE sno_beneficiario.monpagben WHEN 0 ".
				"										 THEN ((".$ls_monto.")*sno_beneficiario.porpagben)/100 ".
				"										 ELSE monpagben END) AS monnetres ".
				"  FROM sno_personalnomina, sno_resumen, sno_beneficiario  ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_resumen.codperi='". $this->ls_peractnom."' ".
				"   AND ".$ls_monto." > 0 ".
				$ls_criterio.
				"   AND sno_personalnomina.codemp = sno_resumen.codemp ".
				"   AND sno_personalnomina.codnom = sno_resumen.codnom ".
				"   AND sno_personalnomina.codper = sno_resumen.codper ".
				"   AND sno_personalnomina.codemp = sno_beneficiario.codemp ".
				"   AND sno_personalnomina.codper = sno_beneficiario.codper ".
				" GROUP BY sno_resumen.codperi ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_load_montototal_beneficiarios ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ad_monto=$row["monnetres"];
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_load_montototal
	//--------------------------------------------------------------------------------------------------------------------------------------
	function uf_listadobanco_gendisk2($as_codban,$as_suspendidos,$as_quincena,$as_pagtaqnom, $as_codconc,&$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listadobanco_gendisk2
		//		   Access: public (desde la clase sigesp_sno_r_metodo_fonz)  
		//	    Arguments: as_codban // Código del banco del que se desea busca el personal
		//	    		   as_suspendidos // si se busca a toto del personal ó solo los activos
		//	    		   as_quincena // Quincena para el cual se quiere filtar
		//                 as_codconc // codigo del concepto
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que tienen asociado el banco 
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 30/01/2009 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_monto="";
		$ls_montoaux="";
		switch($as_quincena)
		{
			case 1: // Primera Quincena
				$ls_monto="sno_resumen.priquires as monnetres";
				$ls_montoaux="sno_resumen.priquires";
				break;

			case 2: // Segunda Quincena
				$ls_monto="sno_resumen.segquires as monnetres";
				$ls_montoaux="sno_resumen.segquires";
				break;

			case 3: // Mes Completo
				$ls_monto="sno_resumen.monnetres as monnetres";
				$ls_montoaux="sno_resumen.monnetres";
				break;
		}
		switch($as_pagtaqnom)
		{
			case "0": // Depósito a banco
				$ls_criterio = $ls_criterio."   AND sno_personalnomina.pagbanper=1 ".
										    "   AND sno_personalnomina.pagtaqper=0 ".
										    "   AND sno_personalnomina.pagefeper=0 ";
				break;

			case "1": // Pago por Taquilla
				$ls_criterio = $ls_criterio."   AND sno_personalnomina.pagbanper=0 ".
										    "   AND sno_personalnomina.pagtaqper=1 ".
										    "   AND sno_personalnomina.pagefeper=0 ";
				break;
		}
		if(!empty($as_codban))
		{
			$ls_criterio = $ls_criterio." AND sno_personalnomina.codban='".$as_codban."' ";
		}
		if($as_suspendidos=="1") // Mostrar solo el personal suspendido
		{
			$ls_criterio = $ls_criterio." AND (sno_personalnomina.staper='1' OR sno_personalnomina.staper='2')";
		}
		
		if (!empty($as_codconc))
		{
			$ls_criterio=" AND sno_salida.codconc='".$as_codconc."'";
		}
		$ls_sql="  SELECT sno_personal.codper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, 
				   sno_personal.nacper, sno_personalnomina.codcueban, sno_personalnomina.tipcuebanper, 
				   sno_salida.codconc, sno_salida.valsal as monto,sno_resumen.monnetres as monnetres, 
				   sno_nomina.desnom,sno_nomina.codnom, 
				   sno_nomina.tippernom                ".
				"  FROM sno_personal, sno_personalnomina, sno_resumen, sno_nomina, sno_salida  ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_resumen.codperi='". $this->ls_peractnom."' ".
				"   AND sno_resumen.monnetres > 0 ".
				"   AND sno_salida.valsal <> 0 ".
				$ls_criterio.
				"	AND sno_personalnomina.codemp = sno_resumen.codemp ".
				"   AND sno_personalnomina.codnom = sno_resumen.codnom ".
				"   AND sno_personalnomina.codper = sno_resumen.codper ".
				"	AND sno_personalnomina.codemp = sno_nomina.codemp ".
				"   AND sno_personalnomina.codnom = sno_nomina.codnom ".
				"   AND sno_personal.codemp = sno_personalnomina.codemp ".
				"	AND sno_personal.codper = sno_personalnomina.codper ".
				"   AND sno_resumen.codemp= sno_salida.codemp".
				"   AND sno_resumen.codper= sno_salida.codper".
				"   AND sno_resumen.codnom= sno_salida.codnom".
				"   AND sno_resumen.codperi= sno_salida.codperi ".
				"ORDER BY sno_personalnomina.tipcuebanper, sno_personal.codper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_listadobanco_gendisk2 ERROR->".
			                           $this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_listadobanco_gendisk2
	
//---------------------------------------------------------------------------------------------------------------------------------------

    function uf_listadobanco_gendisk_consolidado2($as_codban,$as_suspendidos,$as_quincena,$as_codnomdes,$as_codnomhas,
												 $as_codperdes,$as_codperhas,$as_pagtaqnom,$as_anocurnom,$as_codconc,&$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listadobanco_gendisk_consolidado
		//		   Access: public (desde la clase sigesp_snorh_r_listadobanco)  
		//	    Arguments: as_codban // Código del banco del que se desea busca el personal
		//	    		   as_suspendidos // si se busca a toto del personal ó solo los activos
		//	    		   as_quincena // Quincena para el cual se quiere filtar
		//	    		   as_codnomdes // Código de nómina desde el cual se quiere filtrar
		//	    		   as_codnomhas // Código de nómina hasta el cual se quiere filtrar
		//	    		   as_codperdes // Período desde el cual se quiere filtrar
		//	    		   as_codperhas // Período hasta el cual se quiere filtrar
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que tienen asociado el banco 
		//	   Creado Por: Ing. Jennifer Rivero 
		// Fecha Creación: 30/01/2008 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_monto="";
		$ls_groupby="";
		switch($as_quincena)
		{
			case 1: // Primera Quincena
				$ls_monto="sno_hresumen.priquires as monnetres";
				$ls_groupby= " GROUP BY sno_personal.codper, sno_personal.cedper, sno_personal.nomper, ".
					    	 "          sno_personal.apeper, sno_personal.nacper, sno_hpersonalnomina.codcueban, ".
 					     	 "          sno_hpersonalnomina.tipcuebanper, sno_hresumen.priquires, ".
                         	 "          sno_hresumen.priquires, sno_hnomina.codnom,sno_hnomina.desnom, ".
                         	 "          sno_hnomina.tippernom,sno_hsalida.valsal, sno_hsalida.codconc  ";
				break;

			case 2: // Segunda Quincena
				$ls_monto="sno_hresumen.segquires as monnetres";
				$ls_groupby= " GROUP BY sno_personal.codper, sno_personal.cedper, sno_personal.nomper, ".
					     	 "          sno_personal.apeper, sno_personal.nacper, sno_hpersonalnomina.codcueban, ".
 					     	 "          sno_hpersonalnomina.tipcuebanper, sno_hresumen.segquires, ".
                             "          sno_hresumen.priquires, sno_hnomina.codnom,sno_hnomina.desnom, ".
                             "          sno_hnomina.tippernom,sno_hsalida.valsal, sno_hsalida.codconc  ";
				break;

			case 3: // Mes Completo
				$ls_monto="sno_hresumen.monnetres as monnetres";
				$ls_groupby= " GROUP BY sno_personal.codper, sno_personal.cedper, sno_personal.nomper, ".
					     	 "          sno_personal.apeper, sno_personal.nacper, sno_hpersonalnomina.codcueban, ".
 					     	 "          sno_hpersonalnomina.tipcuebanper, sno_hresumen.monnetres, ".
                             "          sno_hresumen.priquires, sno_hnomina.codnom,sno_hnomina.desnom, ".
                             "          sno_hnomina.tippernom,sno_hsalida.valsal, sno_hsalida.codconc  ";
				break;
		}
		
		switch($as_quincena)
		{
			case 1: // Primera Quincena
				$ls_monto="sno_hresumen.priquires as monnetres";
				break;

			case 2: // Segunda Quincena
				$ls_monto="sno_hresumen.segquires as monnetres";
				break;

			case 3: // Mes Completo
				$ls_monto="sno_hresumen.monnetres as monnetres";
				break;
		}
		switch($as_pagtaqnom)
		{
			case "0": // Depósito a banco
				$ls_criterio = $ls_criterio."   AND sno_hpersonalnomina.pagbanper=1 ".
										    "   AND sno_hpersonalnomina.pagtaqper=0 ".
										    "   AND sno_hpersonalnomina.pagefeper=0 ";
				break;

			case "1": // Pago por Taquilla
				$ls_criterio = $ls_criterio."   AND sno_hpersonalnomina.pagbanper=0 ".
										    "   AND sno_hpersonalnomina.pagtaqper=1 ".
										    "   AND sno_hpersonalnomina.pagefeper=0 ";
				break;
		}
		if(!empty($as_codban))
		{
			$ls_criterio = $ls_criterio." AND sno_hpersonalnomina.codban='".$as_codban."' ";
		}
		if($as_suspendidos=="1") // Mostrar solo el personal suspendido
		{
			$ls_criterio = $ls_criterio." AND (sno_hpersonalnomina.staper='1' OR sno_hpersonalnomina.staper='2')";
		}
		if (!empty($as_codconc))
		{
			$ls_criterio=" AND sno_hsalida.codconc='".$as_codconc."'";
		}
		$ls_sql="SELECT sno_personal.codper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, ".
		        "       sno_personal.nacper, ".
				"		sno_hpersonalnomina.codcueban, sno_hpersonalnomina.tipcuebanper, ".$ls_monto.",sno_hnomina.codnom,sno_hnomina.desnom, sno_hnomina.tippernom, ".
				"       sno_hsalida.valsal as monto, sno_hsalida.codconc ".
				"  FROM sno_personal, sno_hpersonalnomina, sno_hresumen, sno_hnomina, sno_hsalida   ".
				" WHERE sno_hpersonalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_hpersonalnomina.codnom>='".$as_codnomdes."' ".
				"   AND sno_hpersonalnomina.codnom<='".$as_codnomhas."' ".
				"	AND sno_hpersonalnomina.anocur = '".$as_anocurnom."'".
				"   AND sno_hresumen.codperi>='".$as_codperdes."' ".
				"   AND sno_hresumen.codperi<='".$as_codperhas."' ".
				"   AND sno_hresumen.monnetres > 0 ".
				"   AND sno_hsalida.valsal <> 0 ".
				$ls_criterio.
				"	AND sno_hpersonalnomina.codemp = sno_hresumen.codemp ".
				"	AND sno_hpersonalnomina.anocur = sno_hresumen.anocur ".
				"	AND sno_hpersonalnomina.codperi = sno_hresumen.codperi ".
				"   AND sno_hpersonalnomina.codnom = sno_hresumen.codnom ".
				"   AND sno_hpersonalnomina.codper = sno_hresumen.codper ".
				"	AND sno_hpersonalnomina.codemp = sno_hnomina.codemp ".
				"	AND sno_hpersonalnomina.anocur = sno_hnomina.anocurnom ".
				"	AND sno_hpersonalnomina.codperi = sno_hnomina.peractnom ".
				"   AND sno_hpersonalnomina.codnom = sno_hnomina.codnom ".
				"   AND sno_personal.codemp = sno_hpersonalnomina.codemp ".
				"	AND sno_personal.codper = sno_hpersonalnomina.codper ".
				"   AND sno_hsalida.codemp= sno_hresumen.codemp ".
      			"   AND sno_hsalida.codnom = sno_hresumen.codnom ".
      			"   AND sno_hsalida.codper= sno_hresumen.codper ".
      			"   AND sno_hsalida.codperi= sno_hresumen.codperi ".
      			"   AND sno_hsalida.anocur =  sno_hresumen.anocur ".$ls_groupby;
				"ORDER BY sno_hpersonalnomina.tipcuebanper, sno_personal.codper "; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_listadobanco_gendisk_consolidado2 ERROR->".
			                            $this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_listadobanco_gendisk_consolidado2
///------------------------------------------------------------------------------------------------------------------------------------	
}
?>