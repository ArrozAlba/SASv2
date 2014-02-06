<?php
class sigesp_sno_c_calcularnomina
{
	var $io_sql;
	var $io_mensajes;
	var $io_seguridad;
	var $io_fun_nomina;
	var $io_funciones;
	var $io_evaluador;
	var $io_prestamo;
	var $io_vacacion;
	var $io_sno;
	var $ls_codemp;
	var $ls_codnom;
	var $ls_conpronom;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sno_c_calcularnomina()
	{	
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sno_c_calcularnomina
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad= new sigesp_c_seguridad();
		require_once("class_folder/class_funciones_nomina.php");
		$this->io_fun_nomina=new class_funciones_nomina();
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
		require_once("sigesp_sno_c_evaluador.php");
		$this->io_evaluador=new sigesp_sno_c_evaluador();
		require_once("sigesp_sno_c_prestamo.php");
		$this->io_prestamo=new sigesp_sno_c_prestamo();
		require_once("sigesp_sno_c_vacacion.php");
		$this->io_vacacion=new sigesp_sno_c_vacacion();
		require_once("sigesp_sno_c_calcularencargaduria.php");
		$this->io_calenc=new sigesp_sno_c_calcularencargaduria();
		require_once("../shared/class_folder/class_fecha.php");
		$this->io_fecha=new class_fecha();			
		require_once("sigesp_sno.php");
		$this->io_sno=new sigesp_sno();		
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
        $this->ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$this->ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
		$this->ls_conpronom=$_SESSION["la_nomina"]["conpronom"];
		$this->lb_sobregiro="0";
		$this->lb_sobregiro=trim($this->io_sno->uf_select_config("SNO","CONFIG","SOBREGIRO_CUENTAS_TRABAJADOR","0","I"));

	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_totalpersonal(&$ai_nropro)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_totalpersonal
		//		   Access: private
		//	    Arguments: ai_nropro  // Número de personas a procesar
		//	      Returns: lb_valido True si se ejecutó con éxito el select y false si hubo agún error
		//	  Description: Funcion que obtiene el total de personas a procesar
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 16/02/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_desincorporar=$this->io_sno->uf_select_config("SNO","NOMINA","DESINCORPORAR DE NOMINA","0","C");
		$ls_sql="SELECT count(codper) as total".
				"  FROM sno_personalnomina ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ";
		switch ($li_desincorporar)
		{
			case "0"; // No se Desincorpora de la nómina 
				$ls_sql=$ls_sql." AND (sno_personalnomina.staper='1' OR sno_personalnomina.staper='2' OR sno_personalnomina.staper='9') ";
				break;
	
			case "1"; // Se desincorpora de la nómina
				$ls_sql=$ls_sql." AND (sno_personalnomina.staper='1' OR sno_personalnomina.staper='9') ";
				break;
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_obtener_totalpersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if(!$rs_data->EOF)
			{
				$ai_nropro=$rs_data->fields["total"];
			}
			$this->io_sql->free_result($rs_data);		
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_resumenpago($as_peractnom,&$ai_totasi,&$ai_totded,&$ai_totapoemp,&$ai_totapopat,&$ai_totnom,&$ai_nropro)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_resumenpago
		//		   Access: public (sigesp_sno_p_calcularnomina.php)
		//	    Arguments: as_peractnom  // período Actual de la nómina
		//				   ai_totasi  // Total de Asignaciones
		//				   ai_totded  // Total de Deducciones
		//				   ai_totapoemp  // Total de Aportes de Empleados
		//				   ai_totapopat  // Total de Aportes de Patron
		//				   ai_totnom  // Total de la Nómina
		//				   ai_nropro  // Número de personas a procesar
		//	      Returns: lb_valido True si se encontro ó False si no se encontró
		//	  Description: Funcion que obtiene el la suma de todo lo que se pago en la nómina 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT SUM(CASE WHEN asires IS NULL THEN 0 ELSE asires END) AS asign, SUM(CASE WHEN dedres IS NULL THEN 0 ELSE dedres END) AS deduc,".
				"		SUM(CASE WHEN apoempres IS NULL THEN 0 ELSE apoempres END) AS apoemp, ".
				"		SUM(CASE WHEN apopatres IS NULL THEN 0 ELSE apopatres END) AS apopat, SUM(CASE WHEN monnetres IS NULL THEN 0 ELSE monnetres END) AS totnom ".
				"  FROM sno_resumen ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codperi='".$as_peractnom."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_obtener_resumenpago ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if(!$rs_data->EOF)
			{
				$ai_totasi=$this->io_fun_nomina->uf_formatonumerico($rs_data->fields["asign"]);
				$ai_totded=$this->io_fun_nomina->uf_formatonumerico(abs($rs_data->fields["deduc"]));
				$ai_totapoemp=$this->io_fun_nomina->uf_formatonumerico(abs($rs_data->fields["apoemp"]));
				$ai_totapopat=$this->io_fun_nomina->uf_formatonumerico(abs($rs_data->fields["apopat"]));
				$ai_totnom=$this->io_fun_nomina->uf_formatonumerico($rs_data->fields["totnom"]);
			}
			$this->io_sql->free_result($rs_data);		
			$lb_valido=$this->uf_obtener_totalpersonal($ai_nropro);
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_existesalida()
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_existesalida
		//		   Access: public (sigesp_sno_p_calcularnomina.php)
		//	      Returns: lb_valido True si existe alguna salida y false si no existe Salida
		//	  Description: Funcion que verifica si hay registros en salida
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 16/02/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT count(codper) as total".
				"  FROM sno_resumen ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codperi='".$this->ls_peractnom."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=true;
			$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_existesalida ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if(!$rs_data->EOF)
			{
				if($rs_data->fields["total"]>0)
				{
					$lb_valido=true;
				}
			}
			$this->io_sql->free_result($rs_data);		
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesarnomina($aa_seguridad)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesarnomina
		//		   Access: public (sigesp_sno_p_calcularnomina.php)
		//	    Arguments: aa_seguridad // arreglo de seguridad
		//	      Returns: lb_valido True si se proceso correctamente ó False si hubo error 
		//	  Description: función que selecciona el personal y procesa el calculo de la nómina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 16/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if ($this->ls_conpronom=="1")
		{ 
		    $ai_totalper=0;
			$this->uf_obtener_totalpersonal($ai_totalper);
			$ai_totalperpro=0;
			$this->uf_obtener_totalpersonalproyecto($ai_totalperpro);			
			if ($ai_totalperpro!=0)
			{
				$ls_mensaje=" ";
				$li=0; 					
				$this->uf_obtener_informacionpersonalpro($ls_mensaje,$li);
				if ($li!=0)
				{
					if ($li>10)
					{
						$ls_mensaje='Existen '.$li.' Personas que no posee Proyectos Asociados en este Nómina \n';
						$this->io_mensajes->message($ls_mensaje);
						$lb_valido=false;
					}
					else
					{
						$ls_mensaje2='El siguiente Personal no posee Proyectos Asociados \n';
						$this->io_mensajes->message($ls_mensaje2.$ls_mensaje);
						$lb_valido=false;
					}
				}
			}
		}
		if ($lb_valido)
		{   
			$li_desincorporar=$this->io_sno->uf_select_config("SNO","NOMINA","DESINCORPORAR DE NOMINA","0","C");
			$lb_valido=true;		
			$ls_sql="SELECT sno_personalnomina.codper ".
					"  FROM sno_personalnomina ".
					" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
					"   AND sno_personalnomina.codnom='".$this->ls_codnom."' ";
			switch ($li_desincorporar)
			{
				case "0"; // No se Desincorpora de la nómina 
					$ls_sql=$ls_sql." AND (sno_personalnomina.staper='1' OR sno_personalnomina.staper='9' OR sno_personalnomina.staper='2') ";
					break;
		
				case "1"; // Se desincorpora de la nómina
					$ls_sql=$ls_sql." AND (sno_personalnomina.staper='1' OR sno_personalnomina.staper='9')";
					break;
			}
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_procesarnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				$this->io_sql->begin_transaction();
				$li_total_nomi=0;
				$i=0;
				while ((!$rs_data->EOF)&&($lb_valido))
				{
					$ls_codper=$rs_data->fields["codper"];
					$i=$i+1;
					$lb_valido=$this->uf_calcularnomina($ls_codper,$li_total_nomi);
					$rs_data->MoveNext();
				}
				$this->io_sql->free_result($rs_data);	
				if($lb_valido)
				{
				   $lb_valido=$this->uf_delete_final_resumen();
				}
				if($lb_valido)
				{
					$lb_valido=$this->uf_update_periodos($aa_seguridad);
				}
				if($lb_valido)
				{
				   $lb_valido=$this->uf_generar_rep_vacaciones();
				}
				if($lb_valido)
				{
					// procesa el personal en encargaduria
					$lb_valido=$this->uf_procesar_encargaduria();
				}
				if($lb_valido)
				{
					$this->io_sql->commit();
					$this->io_mensajes->message("El cálculo de la nómina fue procesado.");
				}
				else
				{
					$this->io_sql->rollback();
					$this->io_mensajes->message("Ocurrio un error al calcular la nómina.");
				}
			}
		}//fin del if (lb_valido)
		else
		{
			$this->io_mensajes->message("Ocurrio un error al calcular la nómina.");
		}		
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_calcularnomina($as_codper,&$ad_totnom)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_calcularnomina
		//		   Access: private
		//	    Arguments: as_codper  // Código del Personal
		//	               ad_totnom  // Total acumulado de la nómina
		//	      Returns: lb_valido  True si se calculó la nómina completa ó False si no se calculó completa
		//	  Description: Funcion que procesa el calculo de los conceptos para el personal dado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
		$lb_valido=$this->io_evaluador->uf_config_session($as_codper);
		if ($lb_valido)
		{
			// procesa la nómina del personal
			$lb_valido=$this->uf_procesar_nomina_personal($as_codper,$ad_totnom);
		}
		if($lb_valido)
		{
			// procesa el personal de vacación
			$lb_valido=$this->io_vacacion->uf_calcular_vacacion($as_codper,$ad_totnom);
		}		
		unset($_SESSION["la_vacacionpersonal"]);
		unset($_SESSION["la_tablasueldo"]);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_nomina_personal($as_codper,&$ad_totnom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_nomina_personal
		//		   Access: private
		//	    Arguments: as_codper  // Código del Personal
		//	               ad_totnom  // Total acumulado de la nómina
		//	      Returns: lb_valido  True si se calculó la nómina completa ó False si no se calculó completa
		//	  Description: Funcion que procesa el calculo de los conceptos para el personal dado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ld_asires=0;
		$ld_dedres=0;
		$ld_apoempres=0;
		$ld_apopatres=0;
		$ld_priquires=0;
		$ld_segquires=0;
		$ld_monnetres=0;
		$ld_totalneto=0;
		$lb_valido=true;
		$ld_sueper=$this->io_evaluador->personal->sueper;
		$ld_horper=$this->io_evaluador->personal->horper;
		$li_numhijper=$this->io_evaluador->personal->numhijper;
		$lb_valido=$this->uf_insert_resumen($as_codper);
		if($lb_valido)
		{
			$lb_valido=$this->uf_evaluar_conceptopersonal($as_codper,$ld_asires,$ld_dedres,$ld_apoempres,$ld_apopatres,$ld_priquires,
			                                              $ld_segquires,$ld_monnetres,$ad_totnom);
		}
		if($lb_valido)
		{
		   	// comienzo el proceso de prestamos
			$lb_valido=$this->io_prestamo->uf_calcular_prestamo($as_codper,$ld_dedres,$ad_totnom,$ld_priquires,$ld_segquires);
		}
		$ld_totalneto=$ld_asires-($ld_dedres+$ld_apoempres);
		$li_adenom=$_SESSION["la_nomina"]["adenom"];
		$li_divcon=$_SESSION["la_nomina"]["divcon"];
		if($li_adenom==1)
		{
		    $ld_priquires=round(($ld_totalneto/2),2);
			$ld_segquires=$ld_totalneto-$ld_priquires;
		}
		else
		{
			if($li_divcon==0)
			{
				$ld_priquires=$ld_totalneto;
				$ld_segquires=0;
			}
			else
			{
				if(($ld_priquires+$ld_segquires)!=$ld_totalneto)
				{
					$ld_ajuste= $ld_totalneto - ($ld_priquires+$ld_segquires);
					$ld_segquires = $ld_segquires + $ld_ajuste;
				}
			}
		}
		//Verifico si las deducciones son mayor a las asignaciones 
		if($ld_asires<($ld_dedres+$ld_apoempres))
		{
			$ls_nomper=$this->io_evaluador->personal->nomper;
		  	$ls_apeper=$this->io_evaluador->personal->apeper;
			$ls_nombre=$ls_nomper." , ".$ls_apeper;
			if ($this->lb_sobregiro=='0')
			{
				$this->io_mensajes->message("Se ha detectado que la persona Código ".$as_codper." Nombre ".$ls_nombre."  posee Deducciones mayores a las Asignaciones.");
				$lb_valido=false;
			}
		}
		if ($lb_valido)
		{
			$lb_valido=$this->uf_update_resumen_acumulado($as_codper,$ld_asires,$ld_dedres,$ld_apoempres,$ld_apopatres,$ld_priquires,$ld_segquires,$ld_totalneto);
		}
		$ld_sueproper=$this->uf_calcular_sueldo_promedio($as_codper);    
		if($lb_valido)
		{
			$lb_valido=$this->uf_update_personalnomina($as_codper,$ld_sueproper);
		}
		return  $lb_valido; 
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_resumen($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_resumen
		//		   Access: private
		//	    Arguments: as_codper  // Código del Personal
		//	      Returns: lb_valido  True si inserta correctamenta en la tabla  ó False si hubo error
		//	  Description: Funcion que inserta en la tabla resumen   
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ld_asires=0;
		$ld_dedres=0;
		$ld_apoempres=0;
		$ld_apopatres=0;
		$ld_priquires=0;
		$ld_segquires=0;
		$ld_monnetres=0;
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_resumen (codemp,codnom,codperi,codper,asires,dedres,apoempres,apopatres,priquires,segquires, ".
		        "monnetres) VALUES ('".$this->ls_codemp."','".$this->ls_codnom."','".$this->ls_peractnom."','".$as_codper."', ".
				"'".$ld_asires."','".$ld_dedres."','".$ld_apoempres."','".$ld_apopatres."','".$ld_priquires."','".$ld_segquires."',".
				"'".$ld_monnetres."') ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_insert_resumen ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
	   return $lb_valido;	
	 }
	//-----------------------------------------------------------------------------------------------------------------------------------		

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_evaluar_conceptopersonal($as_codper,&$ad_asires,&$ad_dedres,&$ad_apoempres,&$ad_apopatres,&$ad_priquires,&$ad_segquires,
	                                     &$ad_monnetres,&$ad_totnom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_evaluar_conceptopersonal
		//		   Access: private
		//	    Arguments: as_codper // código de personal
		//                 ad_asires // asignación del resumen 
		//                 ad_dedres //  deducciones  del resumen 
		//                 ad_apoempres // aporte del empleado
		//                 ad_apopatres // aporte del patrón
		//                 ad_priquires // monto primera quincena
		//                 ad_segquires // monto segunda quincena
		//                 ad_monnetres // monto neto del período
		//                 ad_totnom  //   total de la nomina
		//	      Returns: lb_valido True si se evaluaron los conceptos ó False si hubo error
		//	  Description: Funcion que obtiene los conceptos por personal y los evalua
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;

		$ls_sql="SELECT codemp, codnom, codper, codconc, nomcon, titcon, sigcon, forcon, glocon, acumaxcon, valmincon, valmaxcon, concon, cueprecon, cueconcon, ".
				"		aplisrcon, sueintcon, intprocon, codpro, forpatcon, cueprepatcon, cueconpatcon, titretempcon, titretpatcon, ".
				"		valminpatcon, valmaxpatcon, codprov, cedben, conprenom, sueintvaccon, aplarccon, aplcon, valcon, acuemp, ".
				"  		acuiniemp, acupat, acuinipat, quirepcon ".
				"  FROM calculo_conceptospersonal ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codper='".$as_codper."' ".
				" ORDER BY codemp, codnom, codper";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_evaluar_conceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_i=1;
			while ((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codconc=$rs_data->fields["codconc"];
				$ls_valminpatcon=$rs_data->fields["valminpatcon"];
				$ls_valmaxpatcon=$rs_data->fields["valmaxpatcon"];
				$_SESSION["la_conceptopersonal"]["codconc"]=$ls_codconc;
				$_SESSION["la_conceptopersonal"]["valminpatcon"]=$ls_valminpatcon;
				$_SESSION["la_conceptopersonal"]["valmaxpatcon"]=$ls_valmaxpatcon;
				$ls_concon=$rs_data->fields["concon"];
				$ls_codconc=$rs_data->fields["codconc"];
				$ls_glocon=$rs_data->fields["glocon"];
				$ls_aplcon=$rs_data->fields["aplcon"];
				$ls_forcon=$rs_data->fields["forcon"];
				$ls_sigcon=$rs_data->fields["sigcon"];
				$ls_forpatcon=$rs_data->fields["forpatcon"];
				$ls_acuemp=$rs_data->fields["acuemp"];
				$ls_acupat=$rs_data->fields["acupat"];
				$ls_quirepcon=$rs_data->fields["quirepcon"];
				$ld_valmincon=$rs_data->fields["valmincon"];
				$ld_valmaxcon=$rs_data->fields["valmaxcon"];
				$lb_filtro=true;
				$lb_aplica=true;
				if (trim($ls_concon)!="")
				{
					$lb_filtro=false;
					$lb_valido=$this->io_evaluador->uf_evaluar($as_codper,$ls_concon,$lb_filtro);
				}				  
				if($ls_glocon==0)
				{
					if($ls_aplcon==0)
					{
						$lb_aplica=false;
					}
				}
				if(($lb_aplica)&&($lb_filtro))
				{
					$lb_valido=$this->uf_calcular_personal($as_codper,$ls_codconc,$ld_valcon,$ls_forcon,$ld_valmincon,$ld_valmaxcon); 
					if($lb_valido)
					{
						if(($ls_sigcon=="A")||($ls_sigcon=="B"))
						{
						   $lb_valido=$this->uf_guardar_salida($as_codper,$ls_codconc,"A",$ld_valcon,$ls_acuemp,$ls_quirepcon);
						   if($lb_valido)
						   {
								$ad_asires=$ad_asires + $ld_valcon;
								$ad_totnom=$ad_totnom + $ld_valcon;    
						   }
						}
						if (($ls_sigcon=="D")||($ls_sigcon=="E"))
						{
						   $lb_valido=$this->uf_guardar_salida($as_codper,$ls_codconc,"D",-$ld_valcon,$ls_acuemp,$ls_quirepcon);
						   if($lb_valido)
						   {
								$ad_dedres=$ad_dedres + $ld_valcon;
								$ad_totnom=$ad_totnom - $ld_valcon;
						   }
						}
						if(($ls_sigcon=="P"))
						{
						   $lb_valido=$this->uf_guardar_salida($as_codper,$ls_codconc,"P1",-$ld_valcon,$ls_acuemp,$ls_quirepcon);
						   if($lb_valido)
						   {
								$ad_totnom=$ad_totnom - $ld_valcon;
								$ad_apoempres=$ad_apoempres + $ld_valcon;
						   }
						   $lb_ok=$this->uf_calcular_aporte($as_codper,$ls_codconc,$ls_forpatcon,$ld_valconapo,$ls_quirepcon);
						   if(!($lb_ok))
						   {
								return false;
						   }
						   $lb_valido=$this->uf_guardar_salida($as_codper,$ls_codconc,"P2",-$ld_valconapo,$ls_acupat,$ls_quirepcon);
						   if($lb_valido)
						   {
								$ad_apopatres=$ad_apopatres + $ld_valconapo;
						   }
						}
						if(($ls_sigcon=="R"))
						{
						   $lb_valido=$this->uf_guardar_salida($as_codper,$ls_codconc,$ls_sigcon,$ld_valcon,0,$ls_quirepcon);
						}
					}	
					if(($lb_valido)&&($_SESSION["la_nomina"]["divcon"]==1))
					{
						switch($ls_sigcon)
						{
							case "D":
								$ld_valcon=$ld_valcon*(-1);
								break;
							case "E":
								$ld_valcon=$ld_valcon*(-1);
								break;
							case "P":
								$ld_valcon=$ld_valcon*(-1);
								break;
							case "R":
								$ld_valcon=0;
								break;
						}
						switch($ls_quirepcon)
						{
							case "1": // Primera Quincena
								$ad_priquires=$ad_priquires+$ld_valcon;
								break;
							case "2": // Segunda Quincena
								$ad_segquires=$ad_segquires+$ld_valcon;
								break;
							case "3": // Ambas Quincena
								$ad_priquires=$ad_priquires+round($ld_valcon/2,2);
								$ad_segquires=$ad_segquires+round($ld_valcon/2,2);
								break;
							case "-": // Ambas Quincena
								$ad_priquires=$ad_priquires+round($ld_valcon/2,2);
								$ad_segquires=$ad_segquires+round($ld_valcon/2,2);
								break;
						}
					}				
				}//if($lb_aplica)
				unset($_SESSION["la_concetopersonal"]);
				$rs_data->MoveNext();
			}//while
		}//else	
		$this->io_sql->free_result($rs_data);	
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_calcular_personal($as_codper,$as_codconc,&$ad_valcon,$as_forcon,$ad_valmincon,$ad_valmaxcon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_calcular_personal
		//		   Access: private
		//		Arguments: as_codper // código de personal
		//				   as_codcon // codigo del concepto 
		//                 as_forcon // formula del concepto
		//                 as_valcon //  valor del concepto  
		//	      Returns: lb_valido True si se evaluaron los conceptos ó False si hubo error
		//	  Description: Funcion que calcula los conceptos por personal y los evalua
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=$this->io_evaluador->uf_evaluar($as_codper,$as_forcon,$ad_valcon);
		if($lb_valido)
		{
 	  		if($ad_valmincon>0)//verifico el minimo del concepto 
			{
				if($ad_valcon<$ld_valmincon)
				{
					$ad_valcon=$ad_valmincon;
				}
			}
			if($ad_valmaxcon>0)//verifico el maximo del concepto
			{
				if($ad_valcon>$ld_valmaxcon)
				{
					$ad_valcon=$ad_valmaxcon;
				}
			}
		}
  	  	return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar_salida($as_codper,$as_codconc,$as_tipsal,$ad_valsal,$ad_monacusal,$as_quirepcon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar_salida
		//		   Access: private
		//		Arguments: as_codper // código de personal
		//				   as_codcon // codigo del concepto 
		//                 as_tipsal // tipo de la salida
		//                 ad_valsal //  valor de la salida 
		//                 ad_monacusal // monto acumulado de la salida         
		//	      Returns: lb_valido True si se inserto correctamente ó False si hubo error
		//	  Description: Funcion que inserta en la tabla sno_salida
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ld_salsal=0;
		$lb_valido=true;
		$li_priquisal=0;
		$li_segquisal=0;
		switch($as_quirepcon)
		{
			case '1':
				$li_priquisal=$ad_valsal;
				break;
			case '2':
				$li_segquisal=$ad_valsal;
				break;
			case '3':
				$li_priquisal=round($ad_valsal/2,2);
				$li_segquisal=round($ad_valsal/2,2);
				if(($li_priquisal+$li_segquisal)!=$ad_valsal)
				{
					$ld_ajuste= $ad_valsal - ($li_priquisal+$li_segquisal);
					$li_segquisal = $li_segquisal + $ld_ajuste;
				}
				break;
		}
		$ls_sql="INSERT INTO sno_salida (codemp,codnom,codperi,codper,codconc,tipsal,valsal,monacusal,salsal,priquisal,segquisal) ". 
				"VALUES ('".$this->ls_codemp."','".$this->ls_codnom."','".$this->ls_peractnom."','".$as_codper."', ".
				"'".$as_codconc."','".$as_tipsal."',".$ad_valsal.",".$ad_monacusal.",".$ld_salsal.",".$li_priquisal.",".$li_segquisal.") ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_guardar_salida ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido; 
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_calcular_aporte($as_codper,$as_codcon,$as_forcon,&$ad_valcon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_calcular_aporte
		//		   Access: private
		//		Arguments: as_codper // código de personal
		//				   as_codcon // codigo del concepto 
		//                 as_forcon // formula del concepto
		//                 as_valcon //  valor del concepto  
		//	      Returns: lb_valido True si se evaluaron los conceptos ó False si hubo error
		//	  Description: Funcion que calcula los conceptos por personal y los evalua
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=$this->io_evaluador->uf_evaluar($as_codper,$as_forcon,$ad_valcon);
		if($lb_valido)
		{
			$la_conceptopersonal=$_SESSION["la_conceptopersonal"];
			$ld_valminpatcon=$la_conceptopersonal["valminpatcon"];
			$ld_valmaxpatcon=$la_conceptopersonal["valmaxpatcon"];
			if($ld_valminpatcon>0)//verifico el minimo del concepto
			{
				if($ad_valcon<$ld_valminpatcon)
				{
					$ad_valcon=$ld_valminpatcon;
				}
			}
			if($ld_valmaxpatcon>0)//verifico el maximo del concepto
			{
				if($ad_valcon>$ld_valmaxpatcon)
				{
					$ad_valcon=$ld_valmaxpatcon;
				}
			}
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_resumen_acumulado($as_codper,$ad_asires,$ad_dedres,$ad_apoempres,$ad_apopatres,$ad_priquires,$ad_segquires,
	                                     $ad_monnetres)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_resumen_acumulado
		//		   Access: private
		//		Arguments: as_codper // código de personal
		//                 ad_asires //  asignación del resumen    
		//                 ad_dedres  // deduccion del resumen 
		//                 ad_apoempres  // aporte del empleado 
		//                 ad_apopatres  //  aporte del patrón   
		//                 ad_priquires  // monto primera quincena
		//                 ad_segquires //   monto segunda quincena
		//                 ad_monnetres  //  monto neto del período  
		//	      Returns: lb_valido true si realizo el update correctamente   false en caso contrario
		//	  Description: Funcion que actualiza en la tabla de sno_resumen
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_resumen ".
		        "   SET asires=".$ad_asires.", ".
				"       dedres=".$ad_dedres.", ".
		        "       apoempres=".$ad_apoempres.", ".
				"       apopatres=".$ad_apopatres.", ".
				"       priquires=".$ad_priquires.", ".
				"       segquires=".$ad_segquires.", ".
				"       monnetres=".$ad_monnetres." ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codperi='".$this->ls_peractnom."' ".
				"   AND codper='".$as_codper."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_update_resumen_acumulado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
	   return $lb_valido;	
	 }
    //-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_calcular_sueldo_promedio($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_calcular_sueldo_promedio
		//		   Access: private
		//		Arguments: as_codper // código de personal
		//	      Returns: ld_suelprom valor del sueldo promedio del personal
		//	  Description: Funcion que calcula el sueldo promedio del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ld_sueproper=0;
	    $ls_sql="SELECT (SUM(CASE WHEN sueper IS NULL THEN 0 ELSE sueper END)/COUNT(codper)) AS sueldo ".
                "  FROM sno_hpersonalnomina ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codper='".$as_codper."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_calcular_sueldo_promedio ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ld_sueproper=number_format($row["sueldo"],2,".","");
			}
		}
		return $ld_sueproper;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_personalnomina($as_codper,$ad_sueproper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_personalnomina
		//		   Access: private
		//		Arguments: as_codper // código de personal
		//                 ad_sueproper //  sueldo promedio del personal    
		//	      Returns: lb_valido true si realizo el update correctamente   false en caso contrario
		//	  Description: Funcion que actualiza en la tabla  sno_personalnomina el sueldo promedio y el sueldo integral del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ld_sueldointegral=$this->io_evaluador->personal->sueldointegral;
		$ld_salarionormal=$this->io_evaluador->personal->salarionormal;
		$ls_sql="UPDATE sno_personalnomina ".
		        "   SET sueintper=".$ld_sueldointegral.", ".
				"       sueproper=".$ad_sueproper.", ".
				"       salnorper=".$ld_salarionormal." ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codper='".$as_codper."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_update_personalnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;	
	 }
    //-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_final_resumen()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_final_resumen
		//		   Access: private
		//	      Returns: lb_valido true si realizo el delete correctamente   false en caso contrario
		//	  Description: Funcion que elimina en la tabla  sno_resumen aquellos registros que no estén en salida
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		    
		$lb_valido=true;
		$ls_sql="DELETE FROM sno_resumen ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"	AND codnom='".$this->ls_codnom."' ".
				"	AND codperi='".$this->ls_peractnom."' ".
				"   AND codnom NOT IN (SELECT codnom FROM sno_salida ".
                "		      			WHERE codemp='".$this->ls_codemp."' ".
				"						  AND codnom='".$this->ls_codnom."' ".
				"						  AND codperi='".$this->ls_peractnom."') ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
        {
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_delete_final_resumen ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
	   	}
		return $lb_valido;
    }
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_periodos($aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_periodos
		//		   Access: private
		//		Arguments: aa_seguridad // arreglo de las variables de seguridad
		//	      Returns: lb_valido true si realizo el update correctamente   false en caso contrario
		//	  Description: Funcion que actualiza en la tabla sno_periodo el monto total
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ld_totper=0;
		$lb_valido=true;
		$ls_sql="SELECT SUM(CASE WHEN asires IS NULL THEN 0 ELSE asires END) AS asign, SUM(CASE WHEN dedres IS NULL THEN 0 ELSE dedres END) AS deduc,".
				"		SUM(CASE WHEN apoempres IS NULL THEN 0 ELSE apoempres END) AS apoemp, ".
				"		SUM(CASE WHEN apopatres IS NULL THEN 0 ELSE apopatres END) AS apopat, SUM(CASE WHEN monnetres IS NULL THEN 0 ELSE monnetres END) AS totnom ".
				"  FROM sno_resumen ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codperi='".$this->ls_peractnom."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data==false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_update_periodos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ld_totper=number_format($row["totnom"],2,".","");
				$li_totasi=$this->io_fun_nomina->uf_formatonumerico($row["asign"]);
				$li_totded=$this->io_fun_nomina->uf_formatonumerico($row["deduc"]);
				$li_totapoemp=$this->io_fun_nomina->uf_formatonumerico($row["apoemp"]);
				$li_totapopat=$this->io_fun_nomina->uf_formatonumerico($row["apopat"]);
				$li_totnom=$this->io_fun_nomina->uf_formatonumerico($row["totnom"]);
			}			
			$ls_sql="UPDATE sno_periodo ".
					"   SET totper=".$ld_totper." ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"   AND codnom='".$this->ls_codnom."' ".
					"   AND codperi='".$this->ls_peractnom."' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
		   	{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_update_periodos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		   	}
		   	else
		   	{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="PROCESS";
				$ls_descripcion="Calculó la nómina ".$this->ls_codnom." para el período ".$this->ls_peractnom." ".
								"Total a Asignación ".$li_totasi.", Total Deducción ".$li_totded.", ".
								"Total a Aporte Empleado ".$li_totapoemp.", Total Aporte Patrón ".$li_totapopat.", ".
								"Total Nómina ".$li_totnom;
				$lb_valido=$this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				if($lb_valido==false)
				{
					$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_update_periodos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}	
		   }
	   }
	   return $lb_valido;	
	 }
    //-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_generar_rep_vacaciones()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_generar_rep_vacaciones
		//		   Access: private
		//	      Returns: lb_valido True si se genero el reporte de  vacaciones  ó False si no se genero correctamente
		//	  Description: Funcion que devuelve de la tabla sigesp config los valores y el codigo de la vacaciones
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_ok=false;
		$li_vac_reportar=trim($this->io_sno->uf_select_config("SNO","NOMINA","MOSTRAR VACACION","0","C"));
		$ls_vac_codconvac=trim($this->io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO VACACION","","C"));
		if(($li_vac_reportar==1)&&($ls_vac_codconvac!=""))
		{
			$lb_valido=$this->uf_delete_salidas_vac($ls_vac_codconvac); 
			$lb_ok=true;
		}
		if(($lb_valido)&&($lb_ok))
		{
			$ld_totneto=0;
			$ls_staper=2; // personal de vacaciones 
			$ls_sql="SELECT sno_personalnomina.sueper, sno_personalnomina.horper, sno_personalnomina.codper,sno_personal.cedper, ".
				    "       sno_personal.nomper,sno_personal.apeper,sno_personal.numhijper ".
				    "  FROM sno_personalnomina , sno_personal ".
				    " WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				    "   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
				    "   AND sno_personalnomina.staper='".$ls_staper."' ".
					"   AND sno_personalnomina.codemp=sno_personal.codemp ".
				    "   AND sno_personalnomina.codper=sno_personal.codper ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_generar_rep_vacaciones ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			}
			else
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{	   
					$ls_codper=$row["codper"];
					$li_cuantos=$this->uf_count_resumen($ls_codper);
					if($li_cuantos==0)
					{
						$lb_valido=$this->uf_insert_resumen_vac($ls_codper,$ld_totneto);
					}
					if($lb_valido)
					{
						$lb_valido=$this->uf_guardar_salida($ls_codper,$ls_vac_codconvac,"R",$ld_totneto,0,3);
					}
				} 
			}	   
	 	}
		return $lb_valido;
	}
   //-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_vac_config(&$as_codvac)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_vac_config
		//		   Access: private
		//		Arguments: as_codvac   // codigo de vacaciones 
		//	      Returns: lb_valido True si se evaluo la vacaciones  ó False si no se evaluo en el sigesp_config
		//	  Description: Funcion que devuelve de la tabla sigesp config los valores y el codigo de la vacaciones
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_valor=$this->io_sno->uf_select_config("SNO", "CONFIG", "MOSTRAR VACACION", "0", "I");
        $as_codvac=$this->io_sno->uf_select_config("SNO", "CONFIG", "COD CONCEPTO VACACION", "", "C");
		if(($ls_valor==1)&&($as_codvac!=""))
		{
			$lb_valido=true;
		}
		return  $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	
    
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_salidas_vac($as_codvac)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_salidas_vac
		//		   Access: private
		//		Arguments: as_codvac   // codigo de vacaciones 
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina las salidas para las generar el reporte de vacaciones
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
				"  FROM sno_salida ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codperi='".$this->ls_peractnom."' ".
				"   AND codconc='".$as_codvac."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_delete_salidas_vac ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
    }
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_count_resumen($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_count_resumen
		//		   Access: private
		//		Arguments: as_codper   // código de personal
		//	      Returns: li_cuantos si existe el registro en sno_salida
		//	  Description: Funcion que devuelve si existen  en la tabla resumen  
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	    $li_cuantos=0;
		$ls_sql="SELECT count(codper) AS cuantos ".
                "  FROM sno_resumen ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codperi='".$this->ls_peractnom."' ".
				"   AND codper='".$as_codper."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_count_resumen ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
			   $li_cuantos=$row["cuantos"];
			}
		}
		return $li_cuantos;		  
	 }
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_resumen_vac($as_codper,$ad_totneto)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_resumen_vac
		//		   Access: private
		//		Arguments: as_codper   // código de personal
		//                 ad_totneto //  total neto  
		//	      Returns: lb_valido True si inserta correctamenta en la tabla  ó False si hubo error
		//	  Description: Funcion que inserta en la tabla resumen  para la generacion de reportes de vacaciones 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_resumen (codemp,codnom,codperi,codper,asires,dedres,apoempres, apopatres, priquires, segquires, ".
				" monnetres) VALUES ('".$this->ls_codemp."', '".$this->ls_codnom."','".$this->ls_peractnom."','".$as_codper."', ".
				"'".$ad_totneto."','".$ad_totneto."','".$ad_totneto."','".$ad_totneto."','".$ad_totneto."','".$ad_totneto."', ".
				"'".$ad_totneto."') ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_insert_resumen_vac ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;	
	 }
	//-----------------------------------------------------------------------------------------------------------------------------------		

	//------------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_totalpersonalproyecto(&$as_totalperpro)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_totalpersonalproyecto
		//		   Access: private
		//	    Arguments: 
		//	      Returns: lb_valido True si se ejecutó con éxito el select y false si hubo agún error
		//	  Description: Funcion que obtiene el total de personal con proyecto
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 27/08/2008								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;		
		$li_desincorporar=$this->io_sno->uf_select_config("SNO","NOMINA","DESINCORPORAR DE NOMINA","0","C");		
		$ls_sql=" SELECT count(sno_personalnomina.codper) as contar". 
                "  FROM sno_personalnomina, sno_personal                                     ".
                " WHERE sno_personalnomina.codemp='".$this->ls_codemp."'                     ". 
                "   AND sno_personalnomina.codnom='".$this->ls_codnom."'                     ".
				"   AND sno_personal.codemp=sno_personalnomina.codemp                        ".
				"   AND sno_personal.codper=sno_personalnomina.codper                        ".
                "   AND sno_personalnomina.codper not in                                     ".
				"       (SELECT sno_proyectopersonal.codper FROM sno_proyectopersonal        ".
				"                                            WHERE sno_proyectopersonal.codemp='".$this->ls_codemp."' ".
				"											AND sno_proyectopersonal.codnom='".$this->ls_codnom."')   "; 
				switch ($li_desincorporar)
				{
					case "0"; // No se Desincorpora de la nómina 
						$ls_sql=$ls_sql." AND (sno_personalnomina.staper='1' OR sno_personalnomina.staper='2') ";
						break;
			
					case "1"; // Se desincorpora de la nómina
						$ls_sql=$ls_sql." AND sno_personalnomina.staper='1' ";
						break;
				} 					
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_obtener_totalpersonalproyecto ERROR->".
			                            $this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if(!$rs_data->EOF)
			{
				$as_totalperpro=$rs_data->RecordCount();
			}
			$this->io_sql->free_result($rs_data);		
		}
		return $lb_valido;
	}// fin de uf_obtener_totalpersonalproyecto
	//-------------------------------------------------------------------------------------------------------------------------------------

	//-------------------------------------------------------------------------------------------------------------------------------------
    function uf_obtener_informacionpersonalpro(&$as_mensaje,&$li)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_informacionpersonalpro
		//		   Access: private
		//	    Arguments: 
		//	      Returns: lb_valido True si se ejecutó con éxito el select y false si hubo agún error
		//	  Description: Funcion que obtiene el total de personal con proyecto
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 27/08/2008								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_desincorporar=$this->io_sno->uf_select_config("SNO","NOMINA","DESINCORPORAR DE NOMINA","0","C");		
		$ls_sql=" SELECT sno_personalnomina.codper, sno_personal.nomper, sno_personal.apeper ". 
                "  FROM sno_personalnomina, sno_personal                                     ".
                " WHERE sno_personalnomina.codemp='".$this->ls_codemp."'                     ". 
                "   AND sno_personalnomina.codnom='".$this->ls_codnom."'                     ".
				"   AND sno_personal.codemp=sno_personalnomina.codemp                        ".
				"   AND sno_personal.codper=sno_personalnomina.codper                        ".
                "   AND sno_personalnomina.codper not in                                     ".
				"       (SELECT sno_proyectopersonal.codper FROM sno_proyectopersonal        ".
				"       WHERE sno_proyectopersonal.codemp='".$this->ls_codemp."' ".
				"		AND sno_proyectopersonal.codnom='".$this->ls_codnom."')  ";
				
		switch ($li_desincorporar)
		{
			case "0"; // No se Desincorpora de la nómina 
				$ls_sql=$ls_sql." AND (sno_personalnomina.staper='1' OR sno_personalnomina.staper='2') ";
				break;
	
			case "1"; // Se desincorpora de la nómina
				$ls_sql=$ls_sql." AND sno_personalnomina.staper='1' ";
				break;
		}				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_obtener_informacionpersonalpro ERROR->".
			                            $this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			while((!$rs_data->EOF))
			{
				$li++;
				$ls_codper=$rs_data->fields["codper"];
				$ls_nomper=$rs_data->fields["nomper"];
				$ls_apeper=$rs_data->fields["apeper"];
				$as_mensaje = $as_mensaje.' Código: '.$ls_codper.'  -  '.$ls_apeper.', '.$ls_nomper.'\n';
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);		
		}

		return $lb_valido;
	}// fin de uf_obtener_informacionpersonalpro
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_encargaduria()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_encargaduria
		//		   Access: private
		//	      Returns: lb_valido 
		//	  Description: Funcion realizar el cáculo del pago por encargaduría
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 31/12/2008 								Fecha Última Modificación : 		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ld_fecdesper=$_SESSION["la_nomina"]["fecdesper"];
		$ld_fechasper=$_SESSION["la_nomina"]["fechasper"];
		$ls_sql="SELECT codper, codperenc, fecfinenc ".
				"  FROM sno_encargaduria ".
				" WHERE sno_encargaduria.codemp='".$this->ls_codemp."' ".
				"   AND sno_encargaduria.codnom='".$this->ls_codnom."' ".
				"   AND sno_encargaduria.estenc='1' ".
				"   AND sno_encargaduria.tipenc = '1' ".
				"   AND ((sno_encargaduria.fecinienc <= '".$ld_fecdesper."') ".
				"   OR   (sno_encargaduria.fecfinenc <= '".$ld_fechasper."')  ".
				"   OR   (sno_encargaduria.fecinienc BETWEEN '".$ld_fecdesper."' AND '".$ld_fechasper."')  )";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_procesar_encargaduria ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codperenc=$rs_data->fields["codperenc"];
				$ls_codper=$rs_data->fields["codper"];
				$ld_fecfinenc=$rs_data->fields["fecfinenc"];
				// Para calcular la diferencia a pagar por conceptos de encargaduría
				$ls_sql_enc="SELECT sno_conceptopersonal.codconc, sno_concepto.nomcon, sno_concepto.forcon,".
					"   sno_concepto.valmincon,  sno_concepto.valmaxcon, sno_concepto.sigcon, sno_concepto.quirepcon".
					"  FROM sno_conceptopersonal, sno_concepto ".
					" WHERE sno_conceptopersonal.codemp='".$this->ls_codemp."' ".
					"   AND sno_conceptopersonal.codnom='".$this->ls_codnom."' ".
					"   AND sno_conceptopersonal.codper='".$ls_codper."' ".	
					"   AND sno_conceptopersonal.aplcon ='1'".				
					"   AND sno_concepto.conperenc = '1'".
					"   AND sno_conceptopersonal.codemp=sno_concepto.codemp ".
					"   AND sno_conceptopersonal.codnom=sno_concepto.codnom ".
					"   AND sno_conceptopersonal.codconc=sno_concepto.codconc ";
				$rs_data_enc=$this->io_sql->select($ls_sql_enc);
				if($rs_data_enc===false)
				{
					$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_procesar_encargaduria ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
					$lb_valido=false; 
				}
				else
				{
					$lb_hay=$rs_data_enc->RecordCount();
					$ld_totdif=0;
					while((!$rs_data_enc->EOF)&&($lb_valido))
					{
						$ls_codcon=$rs_data_enc->fields["codconc"];				
						$ls_nomcon=$rs_data_enc->fields["nomcon"];
						$ls_forcon=$rs_data_enc->fields["forcon"];
						$ld_valmincon=$rs_data_enc->fields["valmincon"];
						$ld_valmaxcon=$rs_data_enc->fields["valmaxcon"];			
						$ls_sigcon=trim($rs_data_enc->fields["sigcon"]);
						$ls_quirepcon=trim($rs_data_enc->fields["quirepcon"]);
						$lb_valido=$this->io_evaluador->uf_crear_personalnomina($ls_codper);
						$_SESSION["la_conceptopersonal"]["codconc"]=$ls_codcon;
						if ($lb_valido)
						{
							$lb_valido=$this->io_evaluador->uf_evaluar($ls_codper,$ls_forcon,$ld_valcon);
							if($lb_valido)
							{
								if($ad_valmincon>0)//verifico el minimo del concepto 
								{
									if($ld_valcon<$ld_valmincon)
									{
										$ld_valcon=$ld_valmincon;
									}
								}
								if($ad_valmaxcon>0)//verifico el maximo del concepto
								{
									if($ld_valcon>$ld_valmaxcon)
									{
										$ld_valcon=$ld_valmaxcon;
									}
								}
							}
							$lb_valido=$this->io_calenc->uf_buscar_concepto_encargado($ls_codperenc,$ls_codcon,$ld_valconenc);
							$ld_dif=abs($ld_valcon - $ld_valconenc);
							if (($ls_sigcon=='A')||($ls_sigcon=='B'))
							{
								$ld_totdif=$ld_totdif + $ld_dif;	
							}
							else if (($ls_sigcon=='D')||($ls_sigcon=='P')||($ls_sigcon=='E'))
							{
								$ld_totdif=$ld_totdif - $ld_dif;
							}										
						}	
						$rs_data_enc->MoveNext();
					} // fin del seguno while
					
					//Se busca el código del concepto de resumen de encargaduría
					$lb_valido=$this->uf_buscar_concepto_resumen_encargaduria($ls_codconc); 
					if (($lb_valido) && ($ls_codconc!=""))
					{
					    $li_existe = $this->uf_select_salida($ls_codperenc,$ls_codconc,'A');
						if ($li_existe>0)
						{
							$lb_valido=$this->uf_update_salida($ls_codperenc,$ls_codconc,'A',$ld_totdif,0,0,$ls_quirepcon);
						}
						else
						{
							//Se inserta el valor del concepto en salida					
							$lb_valido=$this->uf_guardar_salida($ls_codperenc,$ls_codconc,'A',$ld_totdif,0,$ls_quirepcon);
						}
						if ($lb_valido)
						{
							//Se actualiza el resumen del personal		
							$ld_fecfinenc=$this->io_funciones->uf_formatovalidofecha($ld_fecfinenc);				
							$ld_fechasper=$this->io_funciones->uf_formatovalidofecha($ld_fechasper);								
							if ($this->io_fecha->uf_comparar_fecha($ld_fecfinenc,$ld_fechasper))
							{
								$li_numdias=$this->io_fecha->uf_restar_fechas($ld_fecfinenc,$ld_fechasper);
								$li_numdias=$li_numdias+1;
								$ls_tipper=$_SESSION["la_nomina"]["tippernom"];
								switch($as_tippernom)
								{
									case "0": // Semanal
										$li_numdiaper=7;
										break;
									case "1": // Quincenal
										$li_numdiaper=15;
										break;
									case "2": // Mensual
										$li_numdiaper=30;
										break;
									case "3": // Anual
										$li_numdiaper=365;
										break;
								}
								$ld_totdif=($ld_totdif*$li_numdias)/$li_numdiaper;
								$ld_totdif=number_format($ld_totdif,2,'.','');
							}
							$lb_valido=$this->uf_actualizar_resumen_personal_encargado($ls_codperenc,$ld_totdif);
						}
					}
					elseif($ls_codconc!="")
					{
						$lb_valido=false;
						$this->io_mensajes->message("No existe concepto para el Resumen de la Encargaduría. No se puede procesar el cálculo de la Nómina.");
					}
				}
				$rs_data->MoveNext();
			} // fin del primer while
		}	   
	 	
		return $lb_valido;
	}// fin uf_procesar_encargaduria
   //-----------------------------------------------------------------------------------------------------------------------------------	
   
    //-----------------------------------------------------------------------------------------------------------------------------------
	function uf_buscar_concepto_resumen_encargaduria(&$as_codconc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_concepto_resumen_encargaduria
		//		   Access: private
		//	      Returns: lb_valido 
		//	  Description: Funcion busca el código del concepto tildado como resumen de encargaduría
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 31/12/2008 								Fecha Última Modificación : 		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;	
		$as_codconc="";	
		$ls_sql="SELECT codconc ".
				"  FROM sno_concepto ".
				" WHERE sno_concepto.codemp='".$this->ls_codemp."' ".
				"   AND sno_concepto.codnom='".$this->ls_codnom."' ".
				"   AND sno_concepto.aplresenc='1' ";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_buscar_concepto_resumen_encargaduria ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if((!$rs_data->EOF)&&($lb_valido))
			{
				$as_codconc=$rs_data->fields["codconc"];
				$rs_data->MoveNext();
			}
		}	   
		return $lb_valido;
	}// uf_buscar_concepto_resumen_encargaduria
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_actualizar_resumen_personal_encargado($as_codper, $ad_monto)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_actualizar_resumen_personal_encargado
		//		   Access: private
		//	      Returns: lb_valido 
		//	  Description: Funcion que actualiza el resumen de pago del personal con el monto de la diferencia por encargaduria
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 31/12/2008 								Fecha Última Modificación : 		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
				
		$ls_sql="SELECT asires, monnetres,priquires,segquires ".
				"  FROM sno_resumen ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codperi='".$this->ls_peractnom."' ".
				"   AND codper='".$as_codper."' ";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_actualizar_resumen_personal_encargado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if((!$rs_data->EOF)&&($lb_valido))
			{
				$ld_asires=$rs_data->fields["asires"];
				$ld_monnetres=$rs_data->fields["monnetres"];
				$ld_totasires=$ld_asires + $ad_monto;
				$ld_totmonnetres=$ld_monnetres + $ad_monto;
				$ld_priquires=$rs_data->fields["priquires"];
				$ld_segquires=$rs_data->fields["segquires"];				 
				
				$li_adenom=$_SESSION["la_nomina"]["adenom"];
				$li_divcon=$_SESSION["la_nomina"]["divcon"];
				if($li_adenom==1)
				{
					$ld_priquires=round(($ld_totmonnetres/2),2);
					$ld_segquires=$ld_totmonnetres-$ld_priquires;
				}
				else
				{
					if($li_divcon==0)
					{
						$ld_priquires=$ld_totmonnetres;						
					}
					else
					{
						if(($ld_priquires+$ld_segquires)!=$ld_totmonnetres)
						{
							$ld_ajuste= $ld_totmonnetres - ($ld_priquires+$ld_segquires);
							$ld_segquires = $ld_segquires + $ld_ajuste;
						}
					}
				}
				
				// Para calcular la diferencia a pagar por conceptos de encargaduría
				$ls_sql_enc=" UPDATE sno_resumen ".
					        " SET  asires = ".$ld_totasires.", ".
							"      monnetres = ".$ld_totmonnetres.", ".
							"      priquires = ".$ld_priquires.", ".
							"      segquires = ".$ld_segquires." ".
							" WHERE codemp='".$this->ls_codemp."' ".
							"   AND codnom='".$this->ls_codnom."' ".
							"   AND codperi='".$this->ls_peractnom."' ".
							"   AND codper='".$as_codper."' ";
											
				$li_row=$this->io_sql->execute($ls_sql_enc);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_actualizar_resumen_personal_encargado ERROR->".
			                                    $this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}				
			} 
		} 
		return $lb_valido;
	}// fin uf_actualizar_resumen_personal_encargado
    //-----------------------------------------------------------------------------------------------------------------------------------

    //-------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_salida($as_codper,$as_codconc,$as_tipsal)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_salida
		//	       Access: private  
		//	    Arguments: as_codper // código de personal
		//                 as_codconc //  codigo del concepto  
		//                 as_tipsal  // tipo de la salida 
		//	      Returns: li_cuantos // cuantos existen
		//	  Description: Funcion que devuelve si exsten salidas con este concepto asociado
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 01/02/2006 								Fecha Última Modificación : 14/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
		$li_cuantos=0;
		$ls_sql=" SELECT count(codper) as cuantos ".
                "   FROM sno_salida ".
                "  WHERE codemp='".$this->ls_codemp."'".
				"    AND codnom='".$this->ls_codnom."'".
				"    AND codperi='".$ls_peractnom."'".
				"    AND codper='".$as_codper."'".
				"    AND codconc='".$as_codconc."'".
				"	 AND tipsal='".$as_tipsal."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
		  $lb_valido=false;
		  $this->io_mensajes->message("CLASE->Prestamo MÉTODO->uf_select_salida ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
			   $li_cuantos=$row["cuantos"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return $li_cuantos;		  
 	}// end function uf_select_salida	
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_salida($as_codper,$as_codconc,$as_tipsal,$ad_valsal,$ad_monacusal,$ad_salsal,$as_quirepcon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//		 Function : uf_update_salida
		//	       Access : private (uf_update_salida_prestamo)
		//	    Arguments : as_codper // código de personal
		//                  as_codconc //  codigo del concepto   
		//                  as_tipsal  // tipo de salida
		//                  ad_valsal  // valor de la salida 
		//                  ad_monacusal  //  monto acumulado de la salida   
		//                  ad_salsal  // saldo de la salida  
		// 	      Returns : $lb_valido true si realizo el update correctamente   false en caso contrario
		//	  Description : Funcion que actualiza en la tabla de sno_salida
		//	   Creado Por : Ing. Yesenia Moreno
		// Fecha Creación : 01/02/2006 								Fecha Última Modificación : 14/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
		$lb_valido=true;		
		$li_priquisal=0;
		$li_segquisal=0;
		switch($as_quirepcon)
		{
			case '1':
				$li_priquisal=$ad_valsal;
				break;
			case '2':
				$li_segquisal=$ad_valsal;
				break;
			case '3':
				$li_priquisal=round($ad_valsal/2,2);
				$li_segquisal=round($ad_valsal/2,2);
				if(($li_priquisal+$li_segquisal)!=$ad_valsal)
				{
					$ld_ajuste= $ad_valsal - ($li_priquisal+$li_segquisal);
					$li_segquisal = $li_segquisal + $ld_ajuste;
				}
				break;
		}
		$ls_sql="UPDATE sno_salida ".
				"	SET valsal=(valsal+".$ad_valsal."), ".
				"		monacusal=(monacusal+".$ad_monacusal."), ".
		        "       salsal=(salsal+".$ad_salsal."), ".
				"		priquisal=(priquisal+".$li_priquisal."),".
				"		segquisal=(segquisal+".$li_segquisal.") ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codperi='".$ls_peractnom."' ".
				"   AND codper='".$as_codper."' ".
				"   AND codconc='".$as_codconc."' ".
				"   AND tipsal='".$as_tipsal."' ";
	   $li_row=$this->io_sql->execute($ls_sql);
	   if($li_row===false)
	   {
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_update_salida ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
	   }
	   return $lb_valido;	
	}// end function uf_update_salida	
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>