<?php
class sigesp_snorh_c_seleccionarhnomina
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_personal;
	var $ls_codemp;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_snorh_c_seleccionarhnomina()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_snorh_c_seleccionarhnomina
		//		   Access: public (sigesp_snorh_p_seleccionarhnomina)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad=new sigesp_c_seguridad();
		require_once("sigesp_snorh_c_personal.php");
		$this->io_personal=new sigesp_snorh_c_personal();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		
	}// end function sigesp_snorh_c_seleccionarhnomina
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_snorh_p_seleccionarhnomina)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
		unset($this->io_personal);
        unset($this->ls_codemp);
        
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesarhistorico($as_codnom,$ai_anocurnom,$as_peractnom)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesarhistorico
		//		   Access: public (sigesp_snorh_p_seleccionarhnomina)
		//	    Arguments: as_codnom  // Código de la nómina
		//				   ai_anocurnom  // año en curso de la nómina
		//				   as_peractnom  // período actual de la nómina
		//	      Returns: lb_valido True si se ejecuto el proceso correctamnet ó False si hubo error en el proceso
		//	  Description: Funcion que elimina de las tablas temporales los registros que existen e inserta los de la nómina, año y 
		//					período seleccionado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/04/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();
		if($lb_valido)
		{
			$lb_valido=$this->uf_delete_temporal($as_codnom);
		}		
		if($lb_valido)
		{
			$lb_valido=$this->uf_insert_temporal($as_codnom,$ai_anocurnom,$as_peractnom);
		}
		if($lb_valido)
		{	
			$this->io_sql->commit();
		}
		else
		{
			$lb_valido=false;
        	$this->io_mensajes->message("Ocurrio un error al procesar la Data Histórica."); 
			$this->io_sql->rollback();
		}
				
		return $lb_valido;
	}// end function uf_procesarhistorico
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_temporal($as_codnom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_temporal
		//		   Access: private
		//	      Returns: lb_valido True si se ejecutaron los delete ó False si hubo error en los delete
		//	  Description: Funcion que elimina todos los registros del personal con el viejo código
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 30/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($lb_valido)
		{// eliminamos el temporal de codigo unico de rac
			$ls_sql="DELETE ".
					"  FROM sno_thcodigounicorac ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codnom='".$as_codnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// eliminamos el temporal de asignación de cargo
			$ls_sql="DELETE ".
					"  FROM sno_thasignacioncargo ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codnom='".$as_codnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}		
		if($lb_valido)
		{// eliminamos el temporal de cargo
			$ls_sql="DELETE ".
					"  FROM sno_thcargo ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codnom='".$as_codnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// eliminamos el temporal de concepto
			$ls_sql="DELETE ".
					"  FROM sno_thconcepto ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codnom='".$as_codnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// eliminamos el temporal de concepto personal
			$ls_sql="DELETE ".
					"  FROM sno_thconceptopersonal ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codnom='".$as_codnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// eliminamos el temporal de concepto vacación
			$ls_sql="DELETE ".
					"  FROM sno_thconceptovacacion ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codnom='".$as_codnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// eliminamos el temporal de constante
			$ls_sql="DELETE ".
					"  FROM sno_thconstante ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codnom='".$as_codnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// eliminamos el temporal de constante personal
			$ls_sql="DELETE ".
					"  FROM sno_thconstantepersonal ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codnom='".$as_codnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// eliminamos el temporal de grado
			$ls_sql="DELETE ".
					"  FROM sno_thgrado ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codnom='".$as_codnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// eliminamos el temporal de nómina
			$ls_sql="DELETE ".
					"  FROM sno_thnomina ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codnom='".$as_codnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// eliminamos el temporal de período
			$ls_sql="DELETE ".
					"  FROM sno_thperiodo ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codnom='".$as_codnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// eliminamos el temporal de encargaduria
			$ls_sql="DELETE ".
					"  FROM sno_thencargaduria ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codnom='".$as_codnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// eliminamos el temporal de primas docentes temporal
			$ls_sql="DELETE ".
					"  FROM sno_thprimadocentepersonal ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codnom='".$as_codnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// eliminamos el temporal de primas docentes
			$ls_sql="DELETE ".
					"  FROM sno_thprimasdocentes ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codnom='".$as_codnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// eliminamos el temporal de personal nómina
			$ls_sql="DELETE ".
					"  FROM sno_thpersonalnomina ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codnom='".$as_codnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// eliminamos el temporal de personal pension
			$ls_sql="DELETE ".
					"  FROM sno_thpersonalpension ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codnom='".$as_codnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// eliminamos el temporal de prenómina
			$ls_sql="DELETE ".
					"  FROM sno_thprenomina ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codnom='".$as_codnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// eliminamos el temporal de prestamos
			$ls_sql="DELETE ".
					"  FROM sno_thprestamos ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codnom='".$as_codnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// eliminamos el temporal de prestamos período
			$ls_sql="DELETE ".
					"  FROM sno_thprestamosperiodo ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codnom='".$as_codnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// eliminamos el temporal de prestamos amortizado
			$ls_sql="DELETE ".
					"  FROM sno_thprestamosamortizado ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codnom='".$as_codnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// eliminamos el temporal de prima concepto
			$ls_sql="DELETE ".
					"  FROM sno_thprimaconcepto ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codnom='".$as_codnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// eliminamos el temporal de prima grado
			$ls_sql="DELETE ".
					"  FROM sno_thprimagrado ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codnom='".$as_codnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// eliminamos el temporal de resumen
			$ls_sql="DELETE ".
					"  FROM sno_thresumen ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codnom='".$as_codnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// eliminamos el temporal de salida
			$ls_sql="DELETE ".
					"  FROM sno_thsalida ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codnom='".$as_codnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// eliminamos el temporal de subnómina
			$ls_sql="DELETE ".
					"  FROM sno_thsubnomina ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codnom='".$as_codnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// eliminamos el temporal de tabulador
			$ls_sql="DELETE ".
					"  FROM sno_thtabulador ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codnom='".$as_codnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// eliminamos el temporal de tipo prestamos
			$ls_sql="DELETE ".
					"  FROM sno_thtipoprestamo ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codnom='".$as_codnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// eliminamos el temporal de unidad administrativa
			$ls_sql="DELETE ".
					"  FROM sno_thunidadadmin ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codnom='".$as_codnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// eliminamos el temporal de vacacion personal
			$ls_sql="DELETE ".
					"  FROM sno_thvacacpersonal ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codnom='".$as_codnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// eliminamos el temporal de proyectos
			$ls_sql="DELETE ".
					"  FROM sno_thproyecto ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codnom='".$as_codnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// eliminamos el temporal de proyectospersonal
			$ls_sql="DELETE ".
					"  FROM sno_thproyectopersonal ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codnom='".$as_codnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// eliminamos el temporal de Clasificación de Obreros
			$ls_sql="DELETE ".
					"  FROM sno_thclasificacionobrero ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codnom='".$as_codnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		return $lb_valido;
	}// end function uf_delete_temporal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_temporal($as_codnom,$ai_anocurnom,$as_peractnom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_temporal
		//		   Access: private
		//	    Arguments: as_codnom  // Código de la nómina
		//	    		   ai_anocurnom  // año en curso
		//	    		   as_peractnom  // período Actual de la nómina
		//	      Returns: lb_valido True si se ejecutaron los delete ó False si hubo error en los delete
		//	  Description: Funcion que elimina todos los registros del personal con el viejo código
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 30/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($lb_valido)
		{// insertamos el temporal de asignación de cargo
			$ls_sql="INSERT INTO sno_thasignacioncargo (codemp, codnom, anocur, codperi, codasicar, denasicar, claasicar, minorguniadm, ".
					"			 ofiuniadm, uniuniadm, depuniadm, prouniadm, codtab, codpas, codgra, codded, codtipper, numvacasicar, ".
					"			 numocuasicar, codproasicar, estcla, grado)".
					"     SELECT codemp, codnom, anocur, codperi, codasicar, denasicar, claasicar, minorguniadm, ofiuniadm, uniuniadm, ".
					"			 depuniadm, prouniadm, codtab, codpas, codgra, codded, codtipper, numvacasicar, numocuasicar, codproasicar, estcla, grado ".
					"       FROM sno_hasignacioncargo ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codnom='".$as_codnom."'".
					"        AND anocur='".$ai_anocurnom."'".
					"        AND codperi='".$as_peractnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// insertamos el temporal de codigo unico de rac
			$ls_sql="INSERT INTO sno_thcodigounicorac (codemp, codnom, anocur, codperi, codasicar, codunirac, estcodunirac)".
					"     SELECT codemp, codnom, anocur, codperi, codasicar, codunirac, estcodunirac ".
					"       FROM sno_hcodigounicorac ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codnom='".$as_codnom."'".
					"        AND anocur='".$ai_anocurnom."'".
					"        AND codperi='".$as_peractnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// insertamos el temporal de cargo
			$ls_sql="INSERT INTO sno_thcargo (codemp, codnom, anocur, codperi, codcar, descar)".
					"     SELECT codemp, codnom, anocur, codperi, codcar, descar ".
					"       FROM sno_hcargo ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codnom='".$as_codnom."'".
					"        AND anocur='".$ai_anocurnom."'".
					"        AND codperi='".$as_peractnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// insertamos el temporal de concepto
			$ls_sql="INSERT INTO sno_thconcepto (codemp, codnom, anocur, codperi, codconc, nomcon, titcon, sigcon, forcon, glocon, ".
					"			 acumaxcon, valmincon, valmaxcon, concon, cueprecon, cueconcon, aplisrcon, sueintcon, intprocon, codpro, ".
					"			 forpatcon, cueprepatcon, cueconpatcon, titretempcon, titretpatcon, valminpatcon, valmaxpatcon, codprov, ".
					"			 cedben, conprenom, sueintvaccon, aplarccon, conprocon, estcla, intingcon, spi_cuenta, poringcon, repacucon, ".
					"			 repconsunicon, consunicon, quirepcon, asifidper, asifidpat, frevarcon, persalnor,aplresenc,conperenc, codente)".
					"     SELECT codemp, codnom, anocur, codperi, codconc, nomcon, titcon, sigcon, forcon, glocon, acumaxcon, valmincon, ".
					"			 valmaxcon, concon, cueprecon, cueconcon, aplisrcon, sueintcon, intprocon, codpro, forpatcon, cueprepatcon, ".
					"			 cueconpatcon, titretempcon, titretpatcon, valminpatcon, valmaxpatcon, codprov, cedben, conprenom, ".
					"			 sueintvaccon, aplarccon, conprocon, estcla, intingcon, spi_cuenta, poringcon, repacucon, repconsunicon, ".
					"			 consunicon, quirepcon, asifidper, asifidpat, frevarcon, persalnor,aplresenc,conperenc, codente ".
					"       FROM sno_hconcepto ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codnom='".$as_codnom."'".
					"        AND anocur='".$ai_anocurnom."'".
					"        AND codperi='".$as_peractnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// insertamos el temporal de concepto personal
			$ls_sql="INSERT INTO sno_thconceptopersonal (codemp, codnom, anocur, codperi, codper, codconc, aplcon, valcon, acuemp, ".
					"			 acuiniemp, acupat, acuinipat)".
					"     SELECT codemp, codnom, anocur, codperi, codper, codconc, aplcon, valcon, acuemp, acuiniemp, acupat, acuinipat ".
					"       FROM sno_hconceptopersonal ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codnom='".$as_codnom."'".
					"        AND anocur='".$ai_anocurnom."'".
					"        AND codperi='".$as_peractnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// insertamos el temporal de concepto vacación
			$ls_sql="INSERT INTO sno_thconceptovacacion (codemp, codnom, anocur, codperi, codconc, forsalvac, acumaxsalvac, minsalvac, ".
					"			 maxsalvac, consalvac, forpatsalvac, minpatsalvac, maxpatsalvac, forreivac, acumaxreivac, minreivac, ".
					"			 maxreivac, conreivac, forpatreivac, minpatreivac, maxpatreivac)".
					"     SELECT codemp, codnom, anocur, codperi, codconc, forsalvac, acumaxsalvac, minsalvac, maxsalvac, consalvac, ".
					"			 forpatsalvac, minpatsalvac, maxpatsalvac, forreivac, acumaxreivac, minreivac, maxreivac, conreivac, ".
					"			 forpatreivac, minpatreivac, maxpatreivac ".
					"       FROM sno_hconceptovacacion ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codnom='".$as_codnom."'".
					"        AND anocur='".$ai_anocurnom."'".
					"        AND codperi='".$as_peractnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// insertamos el temporal de constante
			$ls_sql="INSERT INTO sno_thconstante (codemp, codnom, anocur, codperi, codcons, nomcon, unicon, equcon, topcon, valcon, reicon, tipnumcon,conespseg,esttopmod,conperenc)".
					"     SELECT codemp, codnom, anocur, codperi, codcons, nomcon, unicon, equcon, topcon, valcon, reicon, tipnumcon,conespseg,esttopmod,conperenc ".
					"       FROM sno_hconstante ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codnom='".$as_codnom."'".
					"        AND anocur='".$ai_anocurnom."'".
					"        AND codperi='".$as_peractnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// insertamos el temporal de constante personal
			$ls_sql="INSERT INTO sno_thconstantepersonal (codemp, codnom, anocur, codperi, codper, codcons, moncon,montopcon)".
					"     SELECT codemp, codnom, anocur, codperi, codper, codcons, moncon,montopcon ".
					"       FROM sno_hconstantepersonal ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codnom='".$as_codnom."'".
					"        AND anocur='".$ai_anocurnom."'".
					"        AND codperi='".$as_peractnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// insertamos el temporal de grado
			$ls_sql="INSERT INTO sno_thgrado (codemp, codnom, anocur, codperi, codtab, codpas, codgra, monsalgra, moncomgra)".
					"     SELECT codemp, codnom, anocur, codperi, codtab, codpas, codgra, monsalgra, moncomgra ".
					"       FROM sno_hgrado ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codnom='".$as_codnom."'".
					"        AND anocur='".$ai_anocurnom."'".
					"        AND codperi='".$as_peractnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// insertamos el temporal de nómina
			$ls_sql="INSERT INTO sno_thnomina (codemp, codnom, anocurnom, peractnom, desnom, tippernom, despernom, fecininom, numpernom, tipnom, ".
					"			 subnom, racnom, adenom, espnom, ctnom, ctmetnom, diabonvacnom, diareivacnom, diainivacnom, diatopvacnom, ".
					"			 diaincvacnom, consulnom, descomnom, codpronom, codbennom, conaponom, cueconnom, notdebnom, numvounom, perresnom, ".
					"			 recdocnom, recdocapo, tipdocnom, tipdocapo, conpernom, conpronom, titrepnom, codorgcestic, confidnom, recdocfid, ".
					"			 tipdocfid, codbenfid, cueconfid, divcon,recdocpagperche,tipdocpagperche,estctaalt,racobrnom) ".
					"     SELECT codemp, codnom, anocurnom, peractnom, desnom, tippernom, despernom, fecininom, numpernom, tipnom, subnom, racnom, ".
					"			 adenom, espnom, ctnom, ctmetnom, diabonvacnom, diareivacnom, diainivacnom, diatopvacnom, diaincvacnom, ".
					"			 consulnom, descomnom, codpronom, codbennom, conaponom, cueconnom, notdebnom, numvounom, perresnom,  recdocnom, ".
					"			 recdocapo, tipdocnom, tipdocapo, conpernom, conpronom, titrepnom, codorgcestic, confidnom, recdocfid, tipdocfid, ".
					"			 codbenfid, cueconfid, divcon,recdocpagperche,tipdocpagperche,estctaalt,racobrnom ".
					"       FROM sno_hnomina ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codnom='".$as_codnom."'".
					"        AND anocurnom='".$ai_anocurnom."'".
					"        AND peractnom='".$as_peractnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// insertamos el temporal de período
			$ls_sql="INSERT INTO sno_thperiodo (codemp, codnom, anocur, codperi, fecdesper, fechasper, totper, cerper, conper, apoconper, obsper, peradi, ingconper, fidconper)".
					"     SELECT codemp, codnom, anocur, codperi, fecdesper, fechasper, totper, cerper, conper, apoconper, obsper, peradi, ingconper, fidconper ".
					"       FROM sno_hperiodo ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codnom='".$as_codnom."'".
					"        AND anocur='".$ai_anocurnom."'".
					"        AND codperi='".$as_peractnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// insertamos el temporal de encargaduria
			$ls_sql="INSERT INTO sno_thencargaduria (codemp,anocur,codperi,codnom, codenc,tipenc,fecinienc, fecfinenc,codper, codperenc, codnomperenc,estenc,obsenc,estsuspernom)".
					"     SELECT codemp,anocur,codperi,codnom,codenc, tipenc,fecinienc,fecfinenc,codper,codperenc ,codnomperenc, estenc, obsenc,estsuspernom ".
					"       FROM sno_hencargaduria ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codnom='".$as_codnom."'".
					"        AND anocur='".$ai_anocurnom."'".
					"        AND codperi='".$as_peractnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}		
		if($lb_valido)
		{// insertamos el temporal de personal nómina
			$ls_sql="INSERT INTO sno_thprimadocentepersonal (codemp,codper,anocur,codperi,codnom,codpridoc) ".
					"     SELECT codemp,codper,anocur,codperi,codnom,codpridoc".
					"       FROM sno_hprimadocentepersonal ".
					"      WHERE codemp='".$this->ls_codemp."' ".
					"        AND codnom='".$as_codnom."' ".
					"        AND anocur='".$ai_anocurnom."' ".
					"        AND codperi='".$as_peractnom."' ";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// insertamos el temporal de personal nómina
			$ls_sql="INSERT INTO sno_thprimasdocentes (codemp,codpridoc,anocur,codperi,despridoc,valpridoc,tippridoc,codnom) ".
					"     SELECT codemp,codpridoc,anocur,codperi,despridoc,valpridoc,tippridoc,codnom".
					"       FROM sno_hprimasdocentes ".
					"      WHERE codemp='".$this->ls_codemp."' ".
					"        AND codnom='".$as_codnom."' ".
					"        AND anocur='".$ai_anocurnom."' ".
					"        AND codperi='".$as_peractnom."' ";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// insertamos el temporal de personal nómina
			$ls_sql="INSERT INTO sno_thpersonalnomina (codemp, codnom, anocur, codperi, codper, codsubnom, codasicar, codtab, codgra, ".
					"			 codpas, sueper, horper, minorguniadm, ofiuniadm, uniuniadm, depuniadm, prouniadm, pagbanper, codban, ".
					"			 codcueban, tipcuebanper, codcar, fecingper, staper, cueaboper, fecculcontr, codded, codtipper, quivacper, ".
					"			 codtabvac, sueintper, pagefeper, sueproper, codage, fecegrper, fecsusper, cauegrper, codescdoc, ".
					"			 codcladoc, codubifis, tipcestic, conjub, catjub, codclavia, codunirac, pagtaqper, fecascper, grado, descasicar, coddep, salnorper, estencper) ".
					"     SELECT codemp, codnom, anocur, codperi, codper, codsubnom, codasicar, codtab, codgra, codpas, sueper, horper, ".
					"			 minorguniadm, ofiuniadm, uniuniadm, depuniadm, prouniadm, pagbanper, codban, codcueban, tipcuebanper, ".
					"			 codcar, fecingper, staper, cueaboper, fecculcontr, codded, codtipper, quivacper, codtabvac, sueintper, ".
					"			 pagefeper, sueproper, codage, fecegrper, fecsusper, cauegrper, codescdoc, codcladoc, codubifis, tipcestic, ".
					"			 conjub,catjub, codclavia,codunirac, pagtaqper, fecascper, grado, descasicar, coddep, salnorper, estencper ".
					"       FROM sno_hpersonalnomina ".
					"      WHERE codemp='".$this->ls_codemp."' ".
					"        AND codnom='".$as_codnom."' ".
					"        AND anocur='".$ai_anocurnom."' ".
					"        AND codperi='".$as_peractnom."' ";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// insertamos el temporal de Personal Pensión
			$ls_sql= "INSERT INTO sno_thpersonalpension (codemp, codnom, anocur, codperi, codper, suebasper, pritraper, pridesper, prianoserper, ".
					 "									prinoascper, priespper, priproper, subtotper, porpenper, monpenper, tipjub, fecvid, prirem, segrem) ".
					 "     SELECT codemp, codnom, anocur, codperi, codper, suebasper, pritraper, pridesper, prianoserper, prinoascper, priespper,".
					 "			  priproper, subtotper, porpenper, monpenper, tipjub, fecvid, prirem, segrem ".
					 "       FROM sno_hpersonalpension ".
					 "      WHERE codemp='".$this->ls_codemp."' ".
					 "        AND codnom='".$as_codnom."' ".
					 "        AND anocur='".$ai_anocurnom."' ".
					 "        AND codperi='".$as_peractnom."' ";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// insertamos el temporal de prenómina
			$ls_sql="INSERT INTO sno_thprenomina (codemp, codnom, codper, anocur, codperi, codconc, tipprenom, valprenom, valhis) ".
					"     SELECT codemp, codnom, codper, anocur, codperi, codconc, tipprenom, valprenom, valhis ".
					"       FROM sno_hprenomina ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codnom='".$as_codnom."'".
					"        AND anocur='".$ai_anocurnom."'".
					"        AND codperi='".$as_peractnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// insertamos el temporal de prestamos
			$ls_sql="INSERT INTO sno_thprestamos (codemp, codnom, codper, anocur, codperi,  numpre, codtippre, codconc, monpre, numcuopre, ".
					"			 perinipre, monamopre, stapre, fecpre, obsrecpre, obssuspre, tipcuopre) ".
					"     SELECT codemp, codnom, codper, anocur, codperi, numpre, codtippre, codconc, monpre, numcuopre, perinipre, ".
					"			 monamopre, stapre, fecpre, obsrecpre, obssuspre, tipcuopre ".
					"       FROM sno_hprestamos ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codnom='".$as_codnom."'".
					"        AND anocur='".$ai_anocurnom."'".
					"        AND codperi='".$as_peractnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// insertamos el temporal de prestamos período
			$ls_sql="INSERT INTO sno_thprestamosperiodo (codemp, codnom, codper, anocur, codperi, numpre, codtippre, numcuo, percob, ".
					"			 feciniper, fecfinper, moncuo, estcuo)".
					"     SELECT codemp, codnom, codper, anocur, codperi, numpre, codtippre, numcuo, percob, feciniper, fecfinper, ".
					"			 moncuo, estcuo ".
					"       FROM sno_hprestamosperiodo ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codnom='".$as_codnom."'".
					"        AND anocur='".$ai_anocurnom."'".
					"        AND codperi='".$as_peractnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// insertamos el temporal de prestamos amortizado
			$ls_sql="INSERT INTO sno_thprestamosamortizado (codemp, codnom, codper, numpre, codtippre, anocur, codperi, numamo, peramo, fecamo, monamo, desamo)".
					"     SELECT codemp, codnom, codper, numpre, codtippre, anocur, codperi, numamo, peramo, fecamo, monamo, desamo ".
					"       FROM sno_hprestamosamortizado ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codnom='".$as_codnom."'".
					"        AND anocur='".$ai_anocurnom."'".
					"        AND codperi='".$as_peractnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// insertamos el temporal de prima concepto
			$ls_sql="INSERT INTO sno_thprimaconcepto (codemp, codnom, anocur, codperi, codconc, anopri, valpri) ".
					"     SELECT codemp, codnom, anocur, codperi, codconc, anopri, valpri ".
					"       FROM sno_hprimaconcepto ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codnom='".$as_codnom."'".
					"        AND anocur='".$ai_anocurnom."'".
					"        AND codperi='".$as_peractnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// insertamos el temporal de prima grado
			$ls_sql="INSERT INTO sno_thprimagrado (codemp, codnom, anocur, codperi, codtab, codpas, codgra, codpri, despri, monpri) ".
					"     SELECT codemp, codnom, anocur, codperi, codtab, codpas, codgra, codpri, despri, monpri ".
					"       FROM sno_hprimagrado ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codnom='".$as_codnom."'".
					"        AND anocur='".$ai_anocurnom."'".
					"        AND codperi='".$as_peractnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// insertamos el temporal de resumen
			$ls_sql="INSERT INTO sno_thresumen (codemp, codnom, codper, anocur, codperi, asires, dedres, apoempres, apopatres, ".
					"			 priquires, segquires, monnetres, notres) ".
					"     SELECT codemp, codnom, codper, anocur, codperi, asires, dedres, apoempres, apopatres, priquires, ".
					"			 segquires, monnetres, notres ".
					"       FROM sno_hresumen ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codnom='".$as_codnom."'".
					"        AND anocur='".$ai_anocurnom."'".
					"        AND codperi='".$as_peractnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// insertamos el temporal de salida
			$ls_sql="INSERT INTO sno_thsalida (codemp, codnom, codper, anocur, codperi, codconc, tipsal, valsal, monacusal, salsal, priquisal, segquisal) ".
					"     SELECT codemp, codnom, codper, anocur, codperi, codconc, tipsal, valsal, monacusal, salsal, priquisal, segquisal ".
					"       FROM sno_hsalida ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codnom='".$as_codnom."'".
					"        AND anocur='".$ai_anocurnom."'".
					"        AND codperi='".$as_peractnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// insertamos el temporal de subnómina
			$ls_sql="INSERT INTO sno_thsubnomina (codemp, codnom, anocur, codperi, codsubnom, dessubnom) ".
					"     SELECT codemp, codnom, anocur, codperi, codsubnom, dessubnom ".
					"       FROM sno_hsubnomina ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codnom='".$as_codnom."'".
					"        AND anocur='".$ai_anocurnom."'".
					"        AND codperi='".$as_peractnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// insertamos el temporal de tabulador
			$ls_sql="INSERT INTO sno_thtabulador (codemp, codnom, anocur, codperi, codtab, destab,maxpasgra)".
					"     SELECT codemp, codnom, anocur, codperi, codtab, destab,maxpasgra ".
					"       FROM sno_htabulador ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codnom='".$as_codnom."'".
					"        AND anocur='".$ai_anocurnom."'".
					"        AND codperi='".$as_peractnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// insertamos el temporal de tipo prestamos
			$ls_sql="INSERT INTO sno_thtipoprestamo (codemp, codnom, anocur, codperi, codtippre, destippre)".
					"     SELECT codemp, codnom, anocur, codperi, codtippre, destippre ".
					"       FROM sno_htipoprestamo ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codnom='".$as_codnom."'".
					"        AND anocur='".$ai_anocurnom."'".
					"        AND codperi='".$as_peractnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// insertamos el temporal de unidad administrativa
			$ls_sql="INSERT INTO sno_thunidadadmin (codemp, codnom, anocur, codperi, minorguniadm, ofiuniadm, uniuniadm, depuniadm, ".
					"			 prouniadm, desuniadm, codprouniadm,estcla) ".
					"     SELECT codemp, codnom, anocur, codperi, minorguniadm, ofiuniadm, uniuniadm, depuniadm, prouniadm, desuniadm, codprouniadm,estcla ".
					"       FROM sno_hunidadadmin ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codnom='".$as_codnom."'".
					"        AND anocur='".$ai_anocurnom."'".
					"        AND codperi='".$as_peractnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// insertamos el temporal de vacacion personal
			$ls_sql="INSERT INTO sno_thvacacpersonal (codemp, codnom, anocur, codperi, codper, codvac, fecvenvac, fecdisvac, fecreivac, ".
					"			 diavac, stavac, sueintbonvac, sueintvac, diabonvac, obsvac, diapenvac, persalvac, peringvac, dianorvac, ".
					"			 quisalvac, quireivac, diaadivac, diaadibon, diafer, sabdom, diapag, pagcan, periodo_1, cod_1, nro_dias_1, ".
					"			 monto_1, periodo_2, cod_2, nro_dias_2, monto_2, periodo_3, cod_3, nro_dias_3, monto_3, periodo_4, cod_4, ".
					"			 nro_dias_4, monto_4, periodo_5, cod_5, nro_dias_5, monto_5, diapervac, pagpersal) ".
					"     SELECT codemp, codnom, anocur, codperi, codper, codvac, fecvenvac, fecdisvac, fecreivac, diavac, stavac, ".
					"			 sueintbonvac, sueintvac, diabonvac, obsvac, diapenvac, persalvac, peringvac, dianorvac, quisalvac, ".
					"			 quireivac, diaadivac, diaadibon, diafer, sabdom, diapag, pagcan, periodo_1, cod_1, nro_dias_1, monto_1, ".
					"			 periodo_2, cod_2, nro_dias_2, monto_2, periodo_3, cod_3, nro_dias_3, monto_3, periodo_4, cod_4, nro_dias_4, ".
					"			 monto_4, periodo_5, cod_5, nro_dias_5, monto_5, diapervac, pagpersal ".
					"       FROM sno_hvacacpersonal ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codnom='".$as_codnom."'".
					"        AND anocur='".$ai_anocurnom."'".
					"        AND codperi='".$as_peractnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// insertamos el temporal de proyecto
			$ls_sql="INSERT INTO sno_thproyecto (codemp, codnom, anocur, codperi, codproy, nomproy, estproproy) ".
					"     SELECT codemp, codnom, anocur, codperi, codproy, nomproy, estproproy ".
					"       FROM sno_hproyecto ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codnom='".$as_codnom."'".
					"        AND anocur='".$ai_anocurnom."'".
					"        AND codperi='".$as_peractnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// insertamos el temporal de proyectopersonal
			$ls_sql="INSERT INTO sno_thproyectopersonal (codemp, codnom, anocur, codperi, codproy, codper, totdiaper, totdiames, pordiames) ".
					"     SELECT codemp, codnom, anocur, codperi, codproy, codper, totdiaper, totdiames, pordiames ".
					"       FROM sno_hproyectopersonal ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codnom='".$as_codnom."'".
					"        AND anocur='".$ai_anocurnom."'".
					"        AND codperi='".$as_peractnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		if($lb_valido)
		{// insertamos el temporal de proyectopersonal
			$ls_sql="INSERT INTO sno_thclasificacionobrero (codemp, grado, codnom, anocur, codperi, suemin, suemax, tipcla, obscla) ".
					"     SELECT codemp, grado, codnom, anocur, codperi, suemin, suemax, tipcla, obscla ".
					"       FROM sno_hclasificacionobrero ".
					"      WHERE codemp='".$this->ls_codemp."'".
					"        AND codnom='".$as_codnom."'".
					"        AND anocur='".$ai_anocurnom."'".
					"        AND codperi='".$as_peractnom."'";
			$lb_valido=$this->uf_procesar_sql($ls_sql);
		}
		return $lb_valido;
	}// end function uf_insert_temporal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_sql($as_sql)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_sql
		//		   Access: private
		//	    Arguments: as_sql  // Sentencia SQL que se quiere ejecutar
		//	      Returns: lb_valido True si se ejecuto el sql ó False si hubo error en el sql
		//	  Description: Funcion que ejecuta un sql
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 30/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_row=$this->io_sql->execute($as_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Selecionar hnomina MÉTODO->uf_procesar_sql ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		return $lb_valido;
	}// end function uf_procesar_sql
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
}
?>