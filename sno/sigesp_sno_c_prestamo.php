<?php
class sigesp_sno_c_prestamo
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_fun_nomina;
	var $io_fecha;
	var $io_sno;
	var $in_cuota;	
	var $ls_codemp;
	var $ls_codnom;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sno_c_prestamo()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sno_c_prestamo
		//		   Access: public (sigesp_sno_p_prestamo)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 02/02/2006 								Fecha Última Modificación : 
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
		require_once("class_folder/class_funciones_nomina.php");
		$this->io_fun_nomina=new class_funciones_nomina();
		require_once("../shared/class_folder/class_fecha.php");
		$this->io_fecha=new class_fecha();		
		require_once("sigesp_sno.php");
		$this->io_sno=new sigesp_sno();
		require_once("sigesp_sno_c_prestamocuotas.php");
		$this->io_cuota=new sigesp_sno_c_prestamocuotas();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		if(array_key_exists("la_nomina",$_SESSION))
		{
        	$this->ls_codnom=$_SESSION["la_nomina"]["codnom"];
        	$this->ld_fecdesper=$_SESSION["la_nomina"]["fecdesper"];
        	$this->ld_fechasper=$_SESSION["la_nomina"]["fechasper"];
		}
		else
		{
			$this->ls_codnom="0000";
        	$this->ld_fecdesper="1900-01-01";
        	$this->ld_fechasper="1900-01-01";
		}
		
	}// end function sigesp_sno_c_prestamo
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_sno_p_prestamo)
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
		unset($this->io_fun_nomina);
		unset($this->io_fecha);
		unset($this->io_sno);
		unset($this->io_cuota);
        unset($this->ls_codemp);
        unset($this->ls_codnom);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_select_prestamo($as_campo,$as_valor)
    {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_prestamo
		//		   Access: public 
		//	    Arguments: as_campo  // Campo por medio del cual se desea filtrar
		//	   			   as_valor  // valor del campor del que se quiere filtrar
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que valida que ningún prestamo tenga asociada este pesonal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
 		$lb_existe=false;
       	$ls_sql="SELECT ".$as_campo." ".
				"  FROM sno_prestamos ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND ".$as_campo."='".$as_valor."'";
       	$rs_data=$this->io_sql->select($ls_sql);
       	if ($rs_data===false)
       	{
        	$this->io_mensajes->message("CLASE->Prestamo MÉTODO->uf_select_prestamo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
       	}
       	else
       	{
			if ($row=$this->io_sql->fetch_row($rs_data))
         	{
           		$lb_existe=true;
         	}
			$this->io_sql->free_result($rs_data);	
       	}
		return $lb_existe;    
	}// end function uf_select_prestamo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_correlativo(&$ai_numpre)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_correlativo
		//		   Access: private (uf_guardar) 
		//      Arguments: ai_numpre  // Nuevo número de prestamo
		//	      Returns: lb_valido True si se ejecutó correctamente ó False si hubo algún error
		//	  Description: Funcion que busca el correlativo del último prestamo  y genera el nuevo correlativo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_numpre=1;
		$ls_sql="SELECT numpre as numero ".
				"  FROM sno_prestamos ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				" ORDER BY numpre DESC ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Prestamo MÉTODO->uf_load_correlativo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_numpre=$row["numero"]+1;
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_load_correlativo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_prestamo($as_codper,$as_codtippre,$ai_numpre,$as_codconc,$ai_stapre,$ai_monpre,$ai_numcuopre,$as_perinipre,
								$ai_monamopre,$ad_fecdesper,$ad_fechasper,$ai_sueper,$ai_moncuo,$as_configuracion,$as_tipcuopre,
								$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_prestamo
		//		   Access: private (uf_guardar) 
		//	    Arguments: as_codper  // Código del Personal
		//				   as_codtippre  // Código del tipo de Prestamo
		//				   ai_numpre  // Número Correlativo del Prestamo
		//				   as_codconc  // Código del Concepto
		//				   ai_stapre  // Estatus del Prestamo
		//				   ai_monpre  // Monto del Prestamo
		//				   ai_numcuopre  // Número de Cuotas
		//				   as_perinipre  // Período Inicial
		//				   ai_monamopre  // Monto Amortizado 
		//				   ad_fecdesper  // Fecha Desde Periodo de Inicio del Prestamo
		//				   ad_fechasper  // Fecha Hasta Periodo de Inicio del Prestamo
		//				   ai_sueper  // sueldo del personal
		//				   ai_moncuo  // Monto de la cuota mensual
		//				   as_configuracion  // Configuración del prestamo si es por monto ó por cuota
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta el prestamo del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_prestamos(codemp,codnom,codper,codtippre,numpre,codconc,stapre,monpre,numcuopre,perinipre,monamopre,fecpre,tipcuopre)".
				"VALUES('".$this->ls_codemp."','".$this->ls_codnom."','".$as_codper."','".$as_codtippre."',".$ai_numpre.",'".$as_codconc."',".
				"".$ai_stapre.",".$ai_monpre.",".$ai_numcuopre.",'".$as_perinipre."',".$ai_monamopre.",'".date("Y/m/d")."','".$as_tipcuopre."')";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Prestamo MÉTODO->uf_insert_prestamo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el Prestamo nro ".$ai_numpre." de tipo ".$as_codtippre." del personal ".
							 "".$as_codper." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			if($lb_valido)
			{	
				$lb_valido = $this->uf_generar_cuotas($as_codper,$as_codtippre,$ai_numpre,$ai_monpre,$ai_numcuopre,$as_perinipre,$ad_fecdesper,
							  			$ad_fechasper,$ai_sueper,$ai_moncuo,$as_configuracion,$as_tipcuopre,$aa_seguridad);
			}
			if($lb_valido)
			{
				$lb_valido = $this->io_cuota->uf_verificar_integridadcuota($as_codper,$as_codtippre,$ai_numpre);
			}
			if($lb_valido)
			{	
				$this->io_mensajes->message("El prestamo fue registrado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("ERROR-> Error al registrar el prestamo.");
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_prestamo	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_ultimoperiodo(&$as_ultpernom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_ultimoperiodo
		//		   Access: private (uf_generar_cuotas) 
		//	    Arguments: as_ultpernom  // Último período de la Nómina
		//	      Returns: lb_valido True si se obtiene el último período de la nómina sin problema False si hubo error
		//	  Description: Funcion que obtiene el último período de la nómina actual
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codperi ".
				"  FROM sno_periodo ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND codperi<> '000' ".
				" ORDER BY codperi DESC ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Prestamo MÉTODO->uf_load_ultimoperiodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_ultpernom=$row["codperi"];
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_load_ultimoperiodo	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_incremento()
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_incremento
		//		   Access: private (uf_generar_cuotas,uf_prestamorecalcular,uf_prestamosuspender) 
		//	      Returns: ai_incremento  // Incremento de la nómina si es semanal, quincenal, mensual ó anual
		//	  Description: función que obtiene el tipo de nómina y determina el incremento
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 13/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_incremento=0;			
		switch($_SESSION["la_nomina"]["tippernom"])
		{
			case 0://Semanal
				$ai_incremento=7;
				break;

			case 1://Quincenal
				$ai_incremento=15;
				break;

			case 2://Mensual
				$ai_incremento=30;
				break;

			case 3://Anual
				$ai_incremento=365;
				break;
		}
		return $ai_incremento;
	}// end function uf_load_incremento
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_incrementar_periodo($ai_tippernom,$ai_incremento,$as_tipcuopre,&$as_percob,&$ad_feciniper,&$ad_fecfinper)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_incrementar_periodo
		//		   Access: private (uf_generar_cuotas,uf_prestamorecalcular,uf_prestamosuspender) 
		//	    Arguments: ai_tippernom  // Tipo de Período de la nómina (Semanal, Quincenal, Mensual, Anual)
		//	    		   ai_incremento  // Cantidad en cuanto se van a incrementar los días del período
		//	    		   as_percob  // Período
		//	    		   ad_feciniper  // Fecha de Inicio del Período
		//	    		   ad_fecfinper  // Fecha de Finalización del Período
		//	      Returns: ai_incremento  // Incremento de la nómina si es semanal, quincenal, mensual ó anual
		//	  Description: función que incrementa el perído, la Fecha de inicio del período y la fecha fin del período
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 13/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($as_tipcuopre=="0")
		{
			$as_percob=str_pad((intval($as_percob)+1),3,"0",0);
			$ad_feciniper=$this->io_sno->uf_suma_fechas($ad_fecfinper,1);
			if ((($ai_tippernom==1)&&(substr($ad_feciniper,0,2)=="16"))||($ai_tippernom==2))
			{
				$ad_fecfinper=$this->io_fecha->uf_last_day(substr($ad_feciniper,3,2),substr($ad_feciniper,6,4));
			}
			else
			{
				$ad_fecfinper=$this->io_sno->uf_suma_fechas($ad_fecfinper,$ai_incremento);
			}
		}
		else
		{
			for($li_i=1;$li_i<=2;$li_i++)
			{
				$as_percob=str_pad((intval($as_percob)+1),3,"0",0);
				$ad_feciniper=$this->io_sno->uf_suma_fechas($ad_fecfinper,1);
				if ((($ai_tippernom==1)&&(substr($ad_feciniper,0,2)=="16"))||($ai_tippernom==2))
				{
					$ad_fecfinper=$this->io_fecha->uf_last_day(substr($ad_feciniper,3,2),substr($ad_feciniper,6,4));
				}
				else
				{
					$ad_fecfinper=$this->io_sno->uf_suma_fechas($ad_fecfinper,$ai_incremento);
				}
			}
		}
		return $lb_valido;
	}// end function uf_incrementar_periodo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_generar_cuotas($as_codper,$as_codtippre,$ai_numpre,$ai_monpre,$ai_numcuopre,$as_perinipre,$ad_fecdesper,
							  $ad_fechasper,$ai_sueper,$ai_moncuo,$as_configuracion,$as_tipcuopre,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_generar_cuotas
		//		   Access: private (uf_insert_prestamo) 
		//	    Arguments: as_codper  // Código del Personal
		//				   as_codtippre  // Código del Tipo de  Prestamo
		//				   ai_numpre  // Número Correlativo del Prestamo
		//				   ai_monpre  // Monto del Prestamo
		//				   ai_numcuopre  // Número de Cuotas
		//				   as_perinipre  // Período Inicial
		//				   ad_fecdesper  // Fecha Desde Periodo de Inicio del Prestamo
		//				   ad_fechasper  // Fecha Hasta Periodo de Inicio del Prestamo
		//				   ai_sueper  // sueldo del personal
		//				   ai_moncuo  // Monto de la cuota mensual
		//				   as_configuracion  // Configuración de la cuota
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que genera las cuotas del prestamo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_tippernom=$_SESSION["la_nomina"]["tippernom"];
		$ls_ultpernom="";
		$lb_valido=$this->io_cuota->uf_verificarsueldo($as_codper,$ai_moncuo,$ai_sueper,$ai_numpre);
		if($lb_valido)
		{
			$lb_valido=$this->uf_load_ultimoperiodo($ls_ultpernom);
			if($lb_valido)
			{			
				$li_incremento = $this->uf_load_incremento();			
				// Guardo la 1ra cuota
				$li_numcuo = 1;
				$ls_percob = $as_perinipre;
				$ld_feciniper = $ad_fecdesper;
				$ld_fecfinper = $ad_fechasper;
				$ld_fecfinper=$this->io_funciones->uf_convertirfecmostrar($ld_fecfinper);
				$lb_valido=$this->io_cuota->uf_insert_cuota($as_codper,$as_codtippre,$ai_numpre,$li_numcuo,$ls_percob,$ld_feciniper,
														    $ld_fecfinper,$ai_moncuo,$aa_seguridad);
				// Guardo a partir de la 2da cuota hasta la penultima cuota
				for ($li_i=($li_numcuo+1);($li_i<=($ai_numcuopre-1))&&$lb_valido;++$li_i)
				{
					$li_numcuo = $li_i;
					$lb_valido=$this->uf_incrementar_periodo($li_tippernom,$li_incremento,$as_tipcuopre,$ls_percob,$ld_feciniper,$ld_fecfinper);
					if($lb_valido)
					{
						$lb_valido=$this->io_cuota->uf_insert_cuota($as_codper,$as_codtippre,$ai_numpre,$li_numcuo,$ls_percob,$ld_feciniper,
														  $ld_fecfinper,$ai_moncuo,$aa_seguridad);
					}
					if(intval($ls_percob)==intval($ls_ultpernom))
					{
						$ls_percob="000";
					}	
					if($as_tipcuopre=="1")
					{
						if(intval($ls_percob)>=intval($ls_ultpernom-1))
						{
							$ls_percob=(intval($ls_percob)-intval($ls_ultpernom));
						}	
					}				
				}
				if($ai_numcuopre>1)
				{
					// Guardo la ultima cuota
					$li_ultcuo = ($ai_monpre - ($ai_moncuo*($ai_numcuopre-1)));
					$li_numcuo = $ai_numcuopre;
					$lb_valido=$this->uf_incrementar_periodo($li_tippernom,$li_incremento,$as_tipcuopre,$ls_percob,$ld_feciniper,$ld_fecfinper);
					if($lb_valido)
					{
						$lb_valido=$this->io_cuota->uf_insert_cuota($as_codper,$as_codtippre,$ai_numpre,$li_numcuo,$ls_percob,$ld_feciniper,
														  $ld_fecfinper,$li_ultcuo,$aa_seguridad);
					}
				}
			}
		}
		else
		{
			$this->io_mensajes->message("El monto a Pagar por prestamos del personal es mayor al 30% de su sueldo. No se puede procesar el prestamo.");
		}
		return $lb_valido;
	}// end function uf_generar_cuotas	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_codper,$as_codtippre,$ai_numpre,$as_codconc,$ai_stapre,$ai_monpre,$ai_numcuopre,$as_perinipre,
						$ai_monamopre,$ad_fecdesper,$ad_fechasper,$ai_sueper,$ai_moncuo,$as_configuracion,$as_tipcuopre,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_sno_p_prestamo) 
		//	    Arguments: as_codper  // Código del Personal
		//				   as_codtippre  // Código del tipo de Prestamo
		//				   ai_numpre  // Número Correlativo del Prestamo
		//				   as_codconc  // Código del Concepto
		//				   ai_stapre  // Estatus del Prestamo
		//				   ai_monpre  // Monto del Prestamo
		//				   ai_numcuopre  // Número de Cuotas
		//				   as_perinipre  // Período Inicial
		//				   ai_monamopre  // Monto Amortizado 
		//				   ad_fecdesper  // Fecha Desde Periodo de Inicio del Prestamo
		//				   ad_fechasper  // Fecha Hasta Periodo de Inicio del Prestamo
		//				   ai_sueper  // sueldo del personal
		//				   ai_moncuo  // Monto de la cuota mensual
		//				   as_configuracion  // Configuración del prestamo si es por monto ó por cuotas
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: función que guarda el prestamo del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;			
		$ai_monpre=str_replace(".","",$ai_monpre);
		$ai_monpre=str_replace(",",".",$ai_monpre);
		$ai_monamopre=str_replace(".","",$ai_monamopre);
		$ai_monamopre=str_replace(",",".",$ai_monamopre);
		$ai_moncuo=str_replace(".","",$ai_moncuo);
		$ai_moncuo=str_replace(",",".",$ai_moncuo);
		$ls_prestamo="0";
		$ls_prestamo=$this->uf_select_config("SNO","CONFIG","VAL_TIPO_PRESTAMO",$ls_prestamo,"I");//configuraciòn para el prestamo        
		switch ($as_existe)
		{
			case "FALSE":
				$lb_valido=$this->uf_load_correlativo($ai_numpre);
				if($lb_valido)
				{
				   $ls_contar=0;
				   if ($ls_prestamo!=0)
				   {				       
				   		$ls_contar=$this->uf_contar_prestamos($as_codper,$as_codtippre);						
				   }
				   
				   if (($ls_prestamo=="0")&&($ls_contar==0))
				   {				
						$lb_valido=$this->uf_insert_prestamo($as_codper,$as_codtippre,$ai_numpre,$as_codconc,$ai_stapre,
						                                     $ai_monpre,$ai_numcuopre,$as_perinipre,$ai_monamopre,$ad_fecdesper,
															 $ad_fechasper,$ai_sueper,$ai_moncuo,$as_configuracion,
															 $as_tipcuopre,$aa_seguridad);
				   }
				   elseif (($ls_prestamo=="1")&&($ls_contar==0))
				   {   
				   		$lb_valido=$this->uf_insert_prestamo($as_codper,$as_codtippre,$ai_numpre,$as_codconc,$ai_stapre,
						                                     $ai_monpre,$ai_numcuopre,$as_perinipre,$ai_monamopre,$ad_fecdesper,
															 $ad_fechasper,$ai_sueper,$ai_moncuo,$as_configuracion,
															 $as_tipcuopre,$aa_seguridad);
				   }
				   elseif (($ls_prestamo=="1")&&($ls_contar>=1))
				   {   
				   		$this->io_mensajes->message("No se puede crear el prestamo, ya que posee prestamos del mismo tipo que no se han Cancelado");
				   }
				}
				break;
		}
		return $lb_valido;
	}// end function uf_guardar		
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete($as_codper,$as_codtippre,$ai_numpre,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete
		//		   Access: public (sigesp_sno_p_prestamo) 
		//	    Arguments: as_codper  // Código del Personal
		//				   as_codtippre  // Código del tipo de Prestamo
		//				   ai_numpre  // Número Correlativo del Prestamo
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina un prestamo siempre y cuando no se haya comenzado a pagar
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        if (!$this->io_cuota->uf_existe_cuotapagada($as_codper,$as_codtippre,$ai_numpre))
		{
			$this->io_sql->begin_transaction();
			$lb_valido=$this->io_cuota->uf_delete_cuota($as_codper,$as_codtippre,$ai_numpre,0,"",$aa_seguridad);
			if($lb_valido)
			{
				$lb_valido=$this->uf_delete_amortizado($as_codper,$as_codtippre,$ai_numpre,$aa_seguridad);
			}
			if($lb_valido)
			{
				$ls_sql="DELETE ".
						"  FROM sno_prestamos ".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND codnom='".$this->ls_codnom."'".
						"   AND codper='".$as_codper."'".
						"   AND codtippre='".$as_codtippre."'".
						"   AND numpre=".$ai_numpre."";
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_sql->rollback();
					$this->io_mensajes->message("CLASE->Prestamo MÉTODO->uf_delete ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="DELETE";
					$ls_descripcion ="Eliminó el prestamo ".$ai_numpre." de tipo ".$as_codtippre." del personal ".
									 "".$as_codper." asociado a la nómina ".$this->ls_codnom;
					$lb_valido=$this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					if($lb_valido)
					{	
						$this->io_mensajes->message("EL prestamo fue Eliminado.");
						$this->io_sql->commit();
					}
					else
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Prestamo MÉTODO->uf_delete ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
						$this->io_sql->rollback();
					}
				}
			}
			else
			{
				$this->io_sql->rollback();
			}
		} 
		else
		{
			$lb_valido=false;
			$this->io_mensajes->message("No se puede eliminar el prestamo. Ya existen cuotas canceladas.");
		}       
		return $lb_valido;
    }// end function uf_delete	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_update_nrocuota_prestamo($as_codper,$as_codtippre,$ai_numpre,$ai_numcuopre,$aa_seguridad)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_nrocuota_prestamo
		//		   Access: private (uf_recalcularprestamo) 
		//	    Arguments: as_codper  // Código del Personal
		//				   as_codtippre  // Código del tipo de Prestamo
		//				   ai_numpre  // Número Correlativo del Prestamo
		//				   ai_numcuopre  // Número de cuotas en que se va a cancelar el prestamo
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si realizó el update ó False si no lo actualizó
		//	  Description: Función que actualiza las cuotas en que se va a pagar un prestamo debido a un recalculo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 09/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 		$lb_valido=false;
       	$ls_sql="UPDATE sno_prestamos ".
				"   SET numcuopre=".$ai_numcuopre." ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND codper='".$as_codper."'".
				"   AND codtippre='".$as_codtippre."'".
				"   AND numpre=".$ai_numpre."";
		$li_row=$this->io_sql->execute($ls_sql);
       	if ($li_row===false)
       	{
        	$this->io_mensajes->message("CLASE->Prestamo MÉTODO->uf_update_nrocuota_prestamo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
       	}
       	else
       	{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el número de cuotas del prestamo ".$ai_numpre." de tipo ".$as_codtippre." del personal ".
							 "".$as_codper." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
       	}
		return $lb_valido;    
	}// end function uf_update_nrocuota_prestamo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_update_monto_prestamo($as_codper,$as_codtippre,$ai_numpre,$ai_monpre,$aa_seguridad)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_monto_prestamo
		//		   Access: private (uf_refinanciarrestamo) 
		//	    Arguments: as_codper  // Código del Personal
		//				   as_codtippre  // Código del tipo de Prestamo
		//				   ai_numpre  // Número Correlativo del Prestamo
		//				   ai_monpre  // monto del prestamo
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si realizó el update ó False si no lo actualizó
		//	  Description: Función que actualiza el monto del prestamo y su saldo actual
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 23/08/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 		$lb_valido=false;
       	$ls_sql="UPDATE sno_prestamos ".
				"   SET monpre=".$ai_monpre." ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND codper='".$as_codper."'".
				"   AND codtippre='".$as_codtippre."'".
				"   AND numpre=".$ai_numpre."";
		$li_row=$this->io_sql->execute($ls_sql);
       	if ($li_row===false)
       	{
        	$this->io_mensajes->message("CLASE->Prestamo MÉTODO->uf_update_monto_prestamo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
       	}
       	else
       	{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Refinancio en ".$ai_monpre." el prestamo ".$ai_numpre." de tipo ".$as_codtippre." del personal ".
							 "".$as_codper." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
       	}
		return $lb_valido;    
	}// end function uf_update_monto_prestamo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_update_observacion_prestamo($as_codper,$as_codtippre,$ai_numpre,$as_campo,$as_observacion,$aa_seguridad)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_observacion_prestamo
		//		   Access: private (uf_recalcularprestamo, uf_suspenderprestamo) 
		//	    Arguments: as_codper  // Código del Personal
		//				   as_codtippre  // Código del tipo de Prestamo
		//				   ai_numpre  // Número Correlativo del Prestamo
		//				   as_campo  // Campo de _Observacion que se va a actualizar si es por recalcular ó por suspender
		//				   as_observacion  // Número de cuotas en que se va a cancelar el prestamo
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si realizó el update ó False si no lo actualizó
		//	  Description: Función que actualiza las cuotas en que se va a pagar un prestamo debido a un recalculo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 11/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 		$lb_valido=false;
       	$ls_sql="UPDATE sno_prestamos ".
				"   SET ".$as_campo."='".$as_observacion."' ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND codper='".$as_codper."'".
				"   AND codtippre='".$as_codtippre."'".
				"   AND numpre=".$ai_numpre."";
		$li_row=$this->io_sql->execute($ls_sql);
       	if ($li_row===false)
       	{
        	$this->io_mensajes->message("CLASE->Prestamo MÉTODO->uf_update_observacion_prestamo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));			
       	}
       	else
       	{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la observación ".$as_campo." del prestamo ".$ai_numpre." de tipo ".$as_codtippre." del personal ".
							 "".$as_codper." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
       	}
		return $lb_valido;    
	}// end function uf_update_observacion_prestamo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_prestamo(&$as_codper,&$ai_numpre,&$as_codtippre,&$as_nomper,&$as_destippre,&$as_codconc,&$as_nomcon,&$ai_stapre,&$ai_monpre,
					   		  &$ai_numcuopre,&$as_perinipre,&$ai_salactpre,&$ai_moncuopre,&$ai_monamopre,&$ad_fecdesper,&$ad_fechasper,&$ai_sueper,
					   		  &$ai_cuofal,&$as_tipcuopre)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_prestamo
		//		   Access: public (sigesp_sno_p_prestamo) 
		//	    Arguments: as_codper  // código de Personal
		//				   ai_numpre  // Número Correlativo del Prestamo
		//				   as_codtippre  // Código del tipo de Prestamo
		//				   as_nomper  // Nombre del Personal
		//				   as_destippre  // Descripción del Tipo de Prestamo
		//				   as_codconc  // Código de concepto
		//				   as_nomcon  // Nombre de Concepto
		//				   ai_stapre  // Estatus del Prestamo
		//				   ai_monpre  // Monto del Prestamo
		//				   ai_numcuopre  // Número de Cuotas del Prestamo
		//				   as_perinipre  // Periodo Inicial del Prestamo
		//				   ai_salactpre  // Saldo Actual del Prestamo
		//				   ai_moncuopre  // Monto del Las cuotas del Prestamo
		//				   ai_monamopre  // Monto Amortizado del Prestamo
		//				   ad_fecdesper  // Fecha desde del período
		//				   ad_fechasper  // Fecha Hasta del Período
		//				   ai_sueper  // sueldo del personal
		//				   ai_cuofal  // cuotas faltantes
		//	      Returns: lb_valido True si se ejecuto el select ó False si hubo error en el select
		//	  Description: Función que obtiene la información del prestamo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 10/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sno_prestamos.codper, sno_prestamos.numpre, sno_prestamos.codtippre, sno_prestamos.codconc, sno_prestamos.monpre, ".
				"		sno_prestamos.numcuopre, sno_prestamos.perinipre, sno_prestamos.monamopre, sno_prestamos.stapre, sno_prestamos.fecpre, ".
				"		sno_prestamos.obsrecpre, sno_prestamos.obssuspre, ".
				"       sno_personal.nomper, sno_personal.apeper, sno_tipoprestamo.destippre, sno_concepto.nomcon, ".
				"       sno_prestamosperiodo.feciniper, sno_prestamosperiodo.fecfinper, sno_personalnomina.sueper, sno_prestamos.tipcuopre, ".
				"		(SELECT count(numcuo) ".
				"		   FROM sno_prestamosperiodo ".
				"		  WHERE sno_prestamosperiodo.estcuo=0 ".
				"			AND sno_prestamos.codemp = sno_prestamosperiodo.codemp ".
				"   		AND sno_prestamos.codnom = sno_prestamosperiodo.codnom ".
				"			AND sno_prestamos.codper = sno_prestamosperiodo.codper ".
				"   		AND sno_prestamos.numpre = sno_prestamosperiodo.numpre ".
				"			AND sno_prestamos.codtippre = sno_prestamosperiodo.codtippre) as cuofal, ".
				"		(SELECT MAX(moncuo) ".
				"		   FROM sno_prestamosperiodo ".
				"		  WHERE sno_prestamosperiodo.estcuo = 0 ".
				"		    AND sno_prestamos.codemp = sno_prestamosperiodo.codemp ".
				"			AND sno_prestamos.codnom = sno_prestamosperiodo.codnom ".
				"   		AND sno_prestamos.codper = sno_prestamosperiodo.codper ".
				"			AND sno_prestamos.numpre = sno_prestamosperiodo.numpre ".
				"  			AND sno_prestamos.codtippre = sno_prestamosperiodo.codtippre ".
				"		  GROUP BY sno_prestamosperiodo.codemp, sno_prestamosperiodo.codnom, sno_prestamosperiodo.numpre, ".
				"				   sno_prestamosperiodo.codper, sno_prestamosperiodo.codtippre) as cuopre ".
				"  FROM sno_prestamos, sno_personal, sno_personalnomina, sno_tipoprestamo, sno_concepto, sno_prestamosperiodo ".
				" WHERE sno_prestamos.codemp='".$this->ls_codemp."' ".
				"   AND sno_prestamos.codnom='".$this->ls_codnom."' ".
				"	AND sno_prestamos.numpre=".$ai_numpre." ".
				"	AND sno_prestamos.codper='".$as_codper."' ".
				"   AND sno_prestamos.codtippre='".$as_codtippre."' ".
				"   AND sno_prestamosperiodo.numcuo=1 ".
				"   AND sno_prestamos.codemp = sno_tipoprestamo.codemp ".
				"   AND sno_prestamos.codnom = sno_tipoprestamo.codnom ".
				"   AND sno_prestamos.codtippre = sno_tipoprestamo.codtippre ".
				"   AND sno_prestamos.codemp = sno_prestamosperiodo.codemp ".
				"   AND sno_prestamos.codnom = sno_prestamosperiodo.codnom ".
				"   AND sno_prestamos.codper = sno_prestamosperiodo.codper ".
				"   AND sno_prestamos.codtippre = sno_prestamosperiodo.codtippre ".
				"   AND sno_prestamos.numpre = sno_prestamosperiodo.numpre ".
				"   AND sno_prestamos.codemp = sno_personal.codemp ".
				"   AND sno_prestamos.codper = sno_personal.codper ".
				"   AND sno_prestamos.codemp = sno_personalnomina.codemp ".
				"   AND sno_prestamos.codnom = sno_personalnomina.codnom ".
				"   AND sno_prestamos.codper = sno_personalnomina.codper ".
				"   AND sno_prestamos.codemp = sno_concepto.codemp ".
				"   AND sno_prestamos.codnom = sno_concepto.codnom ".
				"   AND sno_prestamos.codconc = sno_concepto.codconc ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Prestamo MÉTODO->uf_load_prestamo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));			
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_sueper=$row["sueper"];
				$ai_numpre=$row["numpre"];
				$as_codper=$row["codper"];
				$as_nomper=$row["nomper"]." ".$row["apeper"];
				$as_codtippre=$row["codtippre"];
				$as_destippre=$row["destippre"];
				$as_codconc=$row["codconc"];
				$as_nomcon=$row["nomcon"];
				$as_perinipre=$row["perinipre"];
				$ad_fecdesper=$this->io_funciones->uf_formatovalidofecha($row["feciniper"]);
				$ad_fechasper=$this->io_funciones->uf_formatovalidofecha($row["fecfinper"]);
				$ad_fecdesper=$this->io_funciones->uf_convertirfecmostrar($ad_fecdesper);
				$ad_fechasper=$this->io_funciones->uf_convertirfecmostrar($ad_fechasper);
				$ai_stapre=$row["stapre"];
				$ai_monpre=$row["monpre"];
				$ai_numcuopre=$row["numcuopre"];
				$ai_monamopre=$row["monamopre"];
				$ai_cuofal=$row["cuofal"];
				$ai_salactpre=($ai_monpre-$ai_monamopre);
				$ai_moncuopre=$row["cuopre"];
				$as_tipcuopre=$row["tipcuopre"];
				$ai_monpre=$this->io_fun_nomina->uf_formatonumerico($ai_monpre);
				$ai_monamopre=$this->io_fun_nomina->uf_formatonumerico($ai_monamopre);
				$ai_salactpre=$this->io_fun_nomina->uf_formatonumerico($ai_salactpre);
				$ai_moncuopre=$this->io_fun_nomina->uf_formatonumerico($ai_moncuopre);
			}
			$this->io_sql->free_result($rs_data);		
		}
		return $lb_valido;
	}// end function uf_load_prestamo
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_resumen($as_codper,$as_codconc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_resumen
		//		   Access: public (sigesp_sno_p_prestamorecalcular) 
		//	    Arguments: as_codper  // código de Personal
		//				   as_codconc  // Código de concepto
		//	      Returns: lb_valido True si existe ó False si no existe
		//	  Description: Funcion que busca en la tabla de salidas si ya se calculó este prestamo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 13/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$as_codperi=$_SESSION["la_nomina"]["peractnom"];
		$ls_sql="SELECT COUNT(codper) as total ".
				"  FROM sno_salida ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codperi='".$as_codperi."' ".
				"   AND codper='".$as_codper."' ".
				"   AND codconc='".$as_codconc."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Prestamo MÉTODO->uf_select_resumen ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_total=$row["total"];
			}
			if($ai_total==0)
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_select_resumen
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_recalcularprestamo($as_codper,$as_codtippre,$ai_numpre,$ai_numcuofalpre,$ai_nuemoncuopre,$ai_sueper,$ai_cuopag,
								   $ai_salactpre,$as_obsrecpre,$ai_numcuopre,$as_configuracion,$as_tipcuopre,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_recalcularprestamo
		//		   Access: public (sigesp_sno_p_prestamorecalcular) 
		//	    Arguments: as_codper  // Código del Personal
		//				   as_codtippre  // Código del tipo de Prestamo
		//				   ai_numpre  // Número Correlativo del Prestamo
		//				   ai_numcuofalpre  // Número de Cuotas faltantes
		//				   ai_nuemoncuopre  // Monto de las cuotas del prestamo
		//				   ai_sueper  // sueldo del personal
		//				   ai_cuopag  // Cuotas que han sido canceladas
		//				   ai_salactpre  // Saldo actual del Prestamo
		//				   as_obsrecpre  // Observación de recalculo de las cuotas
		//				   ai_numcuopre  // Número Inicial de Cuotas del Prestamo 
		//				   as_configuracion  // Configuración si es por monto ó por cuota
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el recalcular ó False si hubo error en el recalcular
		//	  Description: función que recalcula las cuotas del prestamo del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 09/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;			
		$ai_salactpre=str_replace(".","",$ai_salactpre);
		$ai_salactpre=str_replace(",",".",$ai_salactpre);
		$ai_nuemoncuopre=str_replace(".","",$ai_nuemoncuopre);
		$ai_nuemoncuopre=str_replace(",",".",$ai_nuemoncuopre);
		$li_totcuo=$ai_numcuofalpre+$ai_cuopag;
		$li_numpricuo=$ai_cuopag+1;
		$ls_ultpernom="";
		if($li_totcuo>$ai_numcuopre)
		{
			$ls_percob="";
			$ld_fecdes="";
			$ld_fechas="";
			$li_numultcuo=$ai_numcuopre;
			$li_cuofin=$ai_numcuopre + ($li_totcuo - $ai_numcuopre);
			$lb_valido=$this->io_cuota->uf_obtener_cuota($as_codper,$as_codtippre,$ai_numpre,"2",$ls_percob,$ld_fecdes,$ld_fechas,$ai_numcuopre);
			$ld_fechas = $this->io_funciones->uf_convertirfecmostrar($ld_fechas);
			if($lb_valido)
			{
				$lb_valido=$this->uf_load_ultimoperiodo($ls_ultpernom);
			}
		}
		else
		{
			$li_numultcuo=$li_totcuo;
			$li_cuofin=$li_totcuo + ($ai_numcuopre-$li_totcuo);
		}		
		if($lb_valido)
		{
			$lb_valido=$this->io_cuota->uf_verificarsueldo($as_codper,$ai_nuemoncuopre,$ai_sueper,$ai_numpre);
		}
		$this->io_sql->begin_transaction();
		if($lb_valido)
		{	
			$li_tippernom = $_SESSION["la_nomina"]["tippernom"];
			$li_incremento = $this->uf_load_incremento();
			$li_cuota=$li_numpricuo;
			$lb_valido=$this->io_cuota->uf_update_cuota($as_codper,$as_codtippre,$ai_numpre,$li_cuota,"","","",$ai_nuemoncuopre,"2",$aa_seguridad);
			for($li_i=($li_numpricuo+1);$li_i<=$li_numultcuo;++$li_i)// Recorro las cuotas que ya está generadas y les actualizo el monto
			{
				$li_cuota=$li_i;
				if(($li_totcuo<=$ai_numcuopre)&&($li_i==$li_numultcuo))// Si voy a actualizar las y es la última que se va a generar
				{
					$ai_nuemoncuopre = ($ai_salactpre - ($ai_nuemoncuopre*($ai_numcuofalpre-1)));
				}
				$lb_valido=$this->io_cuota->uf_update_cuota($as_codper,$as_codtippre,$ai_numpre,$li_cuota,"","","",$ai_nuemoncuopre,"2",$aa_seguridad);
			}
			for($li_i=($li_numultcuo+1);$li_i<=$li_cuofin;++$li_i)// Recorro las restantes bien sea que generarlas ó para eliminarlas
			{
				$li_cuota=$li_i;
				if($li_totcuo>=$ai_numcuopre)// Si necesito generar mas cuotas 
				{
					if(intval($ls_percob)>=intval($ls_ultpernom))
					{
						$ls_percob="000";
					}			
					if($as_tipcuopre=="1")
					{
						if(intval($ls_percob)>=intval($ls_ultpernom-1))
						{
							$ls_percob=(intval($ls_percob)-intval($ls_ultpernom));
						}			
					}		
					if($li_i==$li_cuofin)// Sí es la última cuota
					{
						$ai_nuemoncuopre = ($ai_salactpre - ($ai_nuemoncuopre*($ai_numcuofalpre-1)));
					}
					$lb_valido=$this->uf_incrementar_periodo($li_tippernom,$li_incremento,$as_tipcuopre,$ls_percob,$ld_fecdes,$ld_fechas);
					if($lb_valido)
					{
						$lb_valido=$this->io_cuota->uf_insert_cuota($as_codper,$as_codtippre,$ai_numpre,$li_cuota,$ls_percob,$ld_fecdes,$ld_fechas,$ai_nuemoncuopre,$aa_seguridad);
					}
				}
				else// Si necesito eliminar cuotas
				{
					$lb_valido=$this->io_cuota->uf_delete_cuota($as_codper,$as_codtippre,$ai_numpre,$li_cuota,"1",$aa_seguridad);
				}		
			}
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_update_nrocuota_prestamo($as_codper,$as_codtippre,$ai_numpre,$li_totcuo,$aa_seguridad);
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_update_observacion_prestamo($as_codper,$as_codtippre,$ai_numpre,"obsrecpre",$as_obsrecpre,$aa_seguridad);
		}
		if($lb_valido)
		{
			$lb_valido = $this->io_cuota->uf_verificar_integridadcuota($as_codper,$as_codtippre,$ai_numpre);
		}
		if($lb_valido)
		{	
			$this->io_mensajes->message("Las cuotas fueron recalculadas.");
			$this->io_sql->commit();
		}
		else
		{
			$lb_valido=false;
			$this->io_mensajes->message("Ocurrio un error al recalcular las cuotas.");
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_recalcularprestamo	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_suspenderprestamo($as_codper,$as_codtippre,$ai_numpre,$as_perdes,$ad_fecdes1,$ad_fechas1,$as_perhas,
								  $ad_fecdes2,$ad_fechas2,$as_obssuspre,$as_tipcuopre,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_suspenderprestamo
		//		   Access: public (sigesp_sno_p_prestamosuspender) 
		//	    Arguments: as_codper  // Código del Personal
		//				   as_codtippre  // Código del tipo de Prestamo
		//				   ai_numpre  // Número Correlativo del Prestamo
		//				   as_perdes  // Período desde que se va a suspender el prestamo
		//				   ad_fecdes1  // Fecha desde del periodo Desde
		//				   ad_fechas1  // Fecha hasta del Període Desde
		//				   as_perhas  // Período hasta que se va a suspender el prestamo
		//				   ad_fecdes2  // Fecha desde del periodo Hasta
		//				   ad_fechas2  // Fecha hasta del periodo Hasta
		//				   as_obssuspre  // Observación por medio del cual se va a suspender un prestamo
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el recalcular ó False si hubo error en el recalcular
		//	  Description: función que suspende un prestado desde un período hasta otro período y le modifica los períodos a las cuotas
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 10/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;				
		$ad_fecdes1=$this->io_funciones->uf_convertirdatetobd($ad_fecdes1);
		$ad_fechas1=$this->io_funciones->uf_convertirdatetobd($ad_fechas1);
		$li_numpricuo=0;
		$ls_ultpernom="";
		$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
		$ld_fecdesper=$_SESSION["la_nomina"]["fecdesper"];
		$ld_fechasper=$_SESSION["la_nomina"]["fechasper"];
		$lb_valido=$this->io_cuota->uf_obtener_cuota($as_codper,$as_codtippre,$ai_numpre,"1",$as_perdes,$ad_fecdes1,$ad_fechas1,$li_numpricuo);
		if($lb_valido)
		{
			$lb_valido=$this->io_cuota->uf_obtener_ultimacuota($as_codper,$as_codtippre,$ai_numpre,$li_numultcuo,$ls_percobult,$ld_feciniultper,$ld_fecfinultper);
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_load_ultimoperiodo($ls_ultpernom);
		}
		$this->io_sql->begin_transaction();	
		if($lb_valido)
		{	
			if($li_numpricuo>0)// Si existen cuotas en el periodo seleccionado
			{
				$li_tippernom = $_SESSION["la_nomina"]["tippernom"];
				$li_incremento = $this->uf_load_incremento();			
				$ld_fecfinperact = $this->io_funciones->uf_convertirfecmostrar($ad_fechas2);
				$ls_percobact = $as_perhas;
				for($li_i=$li_numpricuo;($li_i<=$li_numultcuo)&&($lb_valido);++$li_i)
				{
					$li_cuota=$li_i;
					if(intval($ls_percobact)>=intval($ls_ultpernom))
					{
						$ls_percobact="000";
					}					
					if($as_tipcuopre=="1")
					{
						if(intval($ls_percobact)>=intval($ls_ultpernom-1))
						{
							$ls_percobact=(intval($ls_percobact)-intval($ls_ultpernom));
						}			
					}		
					$lb_valido=$this->uf_incrementar_periodo($li_tippernom,$li_incremento,$as_tipcuopre,$ls_percobact,$ld_feciniperact,$ld_fecfinperact);
					if($lb_valido)
					{
						$lb_valido=$this->io_cuota->uf_update_cuota($as_codper,$as_codtippre,$ai_numpre,$li_cuota,$ls_percobact,$ld_feciniperact,$ld_fecfinperact,0,"1",$aa_seguridad);
						if(intval($ls_percobact)>=intval($ls_ultpernom))
						{
							$ls_percobact="000";
						}					
						if($as_tipcuopre=="1")
						{
							if(intval($ls_percobact)>=intval($ls_ultpernom-1))
							{
								$ls_percobact=(intval($ls_percobact)-intval($ls_ultpernom));
							}			
						}		
					}
				}
				if($lb_valido)
				{	
					$lb_valido=$this->uf_update_observacion_prestamo($as_codper,$as_codtippre,$ai_numpre,"obssuspre",$as_obssuspre,$aa_seguridad);
				}				
				if($lb_valido)
				{
					$lb_valido = $this->io_cuota->uf_verificar_integridadcuota($as_codper,$as_codtippre,$ai_numpre);
				}
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("No hay cuotas para el Período Inicial seleccionado.");
			}
		}
		if($lb_valido)
		{	
			$this->io_mensajes->message("Las cuotas fueron suspendidas.");
			$this->io_sql->commit();
		}
		else
		{
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_suspenderprestamo		
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_refinanciarprestamo($as_codper,$as_codtippre,$ai_numpre,$ai_numcuofalpre,$ai_nuemoncuopre,$ai_sueper,$ai_cuopag,
								   $ai_salactpre,$as_obsrecpre,$ai_numcuopre,$as_configuracion,$as_tipcuopre,$ai_nuemonpre,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_refinanciarprestamo
		//		   Access: public (sigesp_sno_p_prestamorefinanciar) 
		//	    Arguments: as_codper  // Código del Personal
		//				   as_codtippre  // Código del tipo de Prestamo
		//				   ai_numpre  // Número Correlativo del Prestamo
		//				   ai_numcuofalpre  // Número de Cuotas faltantes
		//				   ai_nuemoncuopre  // Monto de las cuotas del prestamo
		//				   ai_sueper  // sueldo del personal
		//				   ai_cuopag  // Cuotas que han sido canceladas
		//				   ai_salactpre  // Saldo actual del Prestamo
		//				   as_obsrecpre  // Observación de recalculo de las cuotas
		//				   ai_numcuopre  // Número Inicial de Cuotas del Prestamo 
		//				   as_configuracion  // Configuración si es por monto ó por cuota
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el refinanciar ó False si hubo error en el refinanciar
		//	  Description: función que refinancia el monto del prestamo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 23/08/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;			
		$ai_nuemonpre=str_replace(".","",$ai_nuemonpre);
		$ai_nuemonpre=str_replace(",",".",$ai_nuemonpre);
		$ai_salactpre=str_replace(".","",$ai_salactpre);
		$ai_salactpre=str_replace(",",".",$ai_salactpre);
		$ai_nuemoncuopre=str_replace(".","",$ai_nuemoncuopre);
		$ai_nuemoncuopre=str_replace(",",".",$ai_nuemoncuopre);
		$li_totcuo=$ai_numcuofalpre+$ai_cuopag;
		$li_numpricuo=$ai_cuopag+1;
		$ls_ultpernom="";
		if($li_totcuo>$ai_numcuopre)
		{
			$ls_percob="";
			$ld_fecdes="";
			$ld_fechas="";
			$li_numultcuo=$ai_numcuopre;
			$li_cuofin=$ai_numcuopre + ($li_totcuo - $ai_numcuopre);
			$lb_valido=$this->io_cuota->uf_obtener_cuota($as_codper,$as_codtippre,$ai_numpre,"2",$ls_percob,$ld_fecdes,$ld_fechas,$ai_numcuopre);
			$ld_fechas = $this->io_funciones->uf_convertirfecmostrar($ld_fechas);
			if($lb_valido)
			{
				$lb_valido=$this->uf_load_ultimoperiodo($ls_ultpernom);
			}
		}
		else
		{
			$li_numultcuo=$li_totcuo;
			$li_cuofin=$li_totcuo + ($ai_numcuopre-$li_totcuo);
		}		
		if($lb_valido)
		{
			$lb_valido=$this->io_cuota->uf_verificarsueldo($as_codper,$ai_nuemoncuopre,$ai_sueper,$ai_numpre);
		}
		$this->io_sql->begin_transaction();
		if($lb_valido)
		{	
			$li_tippernom = $_SESSION["la_nomina"]["tippernom"];
			$li_incremento = $this->uf_load_incremento();
			$li_cuota=$li_numpricuo;
			$lb_valido=$this->uf_update_monto_prestamo($as_codper,$as_codtippre,$ai_numpre,$ai_nuemonpre,$aa_seguridad);
			if($lb_valido)
			{	
				$lb_valido=$this->io_cuota->uf_update_cuota($as_codper,$as_codtippre,$ai_numpre,$li_cuota,"","","",$ai_nuemoncuopre,"2",$aa_seguridad);
			}
			for($li_i=($li_numpricuo+1);($li_i<=$li_numultcuo)&&$lb_valido;++$li_i)// Recorro las cuotas que ya está generadas y les actualizo el monto
			{
				$li_cuota=$li_i;
				if(($li_totcuo<=$ai_numcuopre)&&($li_i==$li_numultcuo))// Si voy a actualizar las y es la última que se va a generar
				{
					$ai_nuemoncuopre = ($ai_salactpre - ($ai_nuemoncuopre*($ai_numcuofalpre-1)));
				}
				$lb_valido=$this->io_cuota->uf_update_cuota($as_codper,$as_codtippre,$ai_numpre,$li_cuota,"","","",$ai_nuemoncuopre,"2",$aa_seguridad);
			}
			for($li_i=($li_numultcuo+1);($li_i<=$li_cuofin)&&$lb_valido;++$li_i)// Recorro las restantes bien sea que generarlas ó para eliminarlas
			{
				$li_cuota=$li_i;
				if($li_totcuo>=$ai_numcuopre)// Si necesito generar mas cuotas 
				{
					if(intval($ls_percob)>=intval($ls_ultpernom))
					{
						$ls_percob="000";
					}			
					if($as_tipcuopre=="1")
					{
						if(intval($ls_percob)>=intval($ls_ultpernom-1))
						{
							$ls_percob=(intval($ls_percob)-intval($ls_ultpernom));
						}			
					}		
					if($li_i==$li_cuofin)// Sí es la última cuota
					{
						$ai_nuemoncuopre = ($ai_salactpre - ($ai_nuemoncuopre*($ai_numcuofalpre-1)));
					}
					$lb_valido=$this->uf_incrementar_periodo($li_tippernom,$li_incremento,$as_tipcuopre,$ls_percob,$ld_fecdes,$ld_fechas);
					if($lb_valido)
					{
						$lb_valido=$this->io_cuota->uf_insert_cuota($as_codper,$as_codtippre,$ai_numpre,$li_cuota,$ls_percob,$ld_fecdes,$ld_fechas,$ai_nuemoncuopre,$aa_seguridad);
					}
				}
				else// Si necesito eliminar cuotas
				{
					$lb_valido=$this->io_cuota->uf_delete_cuota($as_codper,$as_codtippre,$ai_numpre,$li_cuota,"1",$aa_seguridad);
				}		
			}
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_update_nrocuota_prestamo($as_codper,$as_codtippre,$ai_numpre,$li_totcuo,$aa_seguridad);
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_update_observacion_prestamo($as_codper,$as_codtippre,$ai_numpre,"obsrecpre",$as_obsrecpre,$aa_seguridad);
		}
		if($lb_valido)
		{
			$lb_valido = $this->io_cuota->uf_verificar_integridadcuota($as_codper,$as_codtippre,$ai_numpre);
		}
		if($lb_valido)
		{	
			$this->io_mensajes->message("Las prestamo fue refinanciado.");
			$this->io_sql->commit();
		}
		else
		{
			$lb_valido=false;
			$this->io_mensajes->message("Ocurrio un error al Refinanciar el prestamo.");
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_refinanciarprestamo	
	//-----------------------------------------------------------------------------------------------------------------------------------

    //-------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_salida($as_codper,$as_codconc,$as_tipsal)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_salida
		//	       Access: private (uf_update_salida_prestamo)
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
	function uf_insert_salida($as_codper,$as_codconc,$as_tipsal,$ad_valsal,$ad_monacusal,$ad_salsal,$as_quirepcon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_salida
		//	       Access: private (uf_update_salida_prestamo)
		//	    Arguments: as_codper // código de personal
		//                 as_codconc //  codigo del concepto   
		//                 as_tipsal  // tipo de salida
		//                 ad_valsal  // vlor de la salida 
		//                 ad_monacusal  //  monto acumulado de la salida   
		//                 ad_salsal  // saldo de la salida  
		//	      Returns: lb_valido true si hace el insert correctamente y false en caso contrario 
		//	  Description: Funcion que inserta  la salida  en la tabla
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 01/02/2006 								Fecha Última Modificación : 14/02/2006
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
		$ls_sql="INSERT INTO sno_salida (codemp,codnom, codperi, codper, codconc, tipsal, valsal, monacusal, salsal, priquisal, segquisal) VALUES ".
                "('".$this->ls_codemp."','".$this->ls_codnom."', '".$ls_peractnom."', '".$as_codper."', '".$as_codconc."', ".
				" '".$as_tipsal."',".$ad_valsal.",".$ad_monacusal.", ".$ad_salsal.",".$li_priquisal.",".$li_segquisal." ) ";
	   $li_row=$this->io_sql->execute($ls_sql);
	   if($li_row===false)
	   {
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Prestamo MÉTODO->uf_insert_salida ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
	   }
	   return $lb_valido;	
	}// end function uf_insert_salida	
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
		//	   Creado Por : Ing. Yozelin Barragan
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
			$this->io_mensajes->message("CLASE->Prestamo MÉTODO->uf_update_salida ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
	   }
	   return $lb_valido;	
	}// end function uf_update_salida	
	//-----------------------------------------------------------------------------------------------------------------------------------
	
   	//-------------------------------------------------------------------------------------------------------------------------------------
	function uf_update_salida_prestamo($as_codper,$as_codtippre,$as_codconc,$as_tipsal,$ad_valsal,$ad_monacusal,$ad_salsal,
									   $ai_numcuo,$ai_numpre,$as_quirepcon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//		 Function: uf_update_salida_prestamo
		//	       Access: private (uf_calcular_prestamo)
		//	    Arguments: as_codper  // codigo del personal
		//                 as_codtippre  // codigo del tipo de prestamo
		//                 as_codconc  //  codigo del concepto
		//                 as_tipsal  // signo del concepto
		//                 ad_valsal  // cuota del prestamo
		//                 ad_monacusal  // acumulado del prestamo  
		//                 ad_salsal  //  saldo del prestamo 
		//                 ai_numcuo  //  número de cuota que se está pagando
		//                 ai_numpre  //  número del prestamo
		//	      Returns: lb_valido True si se ejecuto el update correctamente ó False si hubo error en el update
		//	  Description: Funcion que recorre los prestamos del personal y llama a los metodos deducir la cuota del pago de la nomina
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 01/02/2006 								Fecha Última Modificación : 14/02/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_cuantos=$this->uf_select_salida($as_codper,$as_codconc,$as_tipsal);
		if($li_cuantos==0) // No existen salidas con ese concepto asociado
		{
		   $lb_valido=$this->uf_insert_salida($as_codper,$as_codconc,$as_tipsal,$ad_valsal,$ad_monacusal,$ad_salsal,$as_quirepcon);
		}
		else
		{
		   $lb_valido=$this->uf_update_salida($as_codper,$as_codconc,$as_tipsal,$ad_valsal,$ad_monacusal,$ad_salsal,$as_quirepcon);
		}
		return  $lb_valido;
  	}// end function uf_update_salida_prestamo	
  	//-----------------------------------------------------------------------------------------------------------------------------------

  	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_calcular_prestamo($as_codper,&$ad_dedres,&$ad_totnom,&$ad_priquires,&$ad_segquires)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_calcular_prestamo
		//	       Access: public (sigesp_sno_calcularnomina)
		//	    Arguments: as_codper  // codigo del personal
		//                 ad_dedres //  deducciones  del resumen 
		//                 ad_totnom  //   total de la nomina
		//	      Returns: lb_valido True si se ejecuto  correctamente ó False si hubo error calculando los prestamos 
		//	  Description: Funcion que recorre los prestamos del personal y busca cual es la cuota para deducirla del pago de la nomina
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 01/02/2006 								Fecha Última Modificación : 14/02/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
        $ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
		$lb_valido=true;
		$ls_stapre="1";/* STATUS -> PRESTAMO ACTIVO*/
		$ls_sql=" SELECT  sno_prestamos.codtippre, sno_prestamos.monpre, sno_prestamos.monamopre, sno_prestamos.numpre, ".
                "         sno_prestamos.codconc, sno_prestamosperiodo.moncuo, sno_prestamosperiodo.numcuo, sno_concepto.quirepcon ".
                "   FROM sno_prestamos , sno_prestamosperiodo, sno_concepto ".
                "  WHERE sno_prestamos.codemp='".$this->ls_codemp."' ".
                "    AND sno_prestamos.codnom='".$this->ls_codnom."' ".
				"	 AND sno_prestamos.codper='".$as_codper."' ".
				"    AND sno_prestamos.stapre='".$ls_stapre."' ".  
				"    AND sno_prestamosperiodo.feciniper='".$this->ld_fecdesper."' ".
	            "    AND sno_prestamos.codemp=sno_prestamosperiodo.codemp ".
                "    AND sno_prestamos.codnom=sno_prestamosperiodo.codnom ".
                "    AND sno_prestamos.codper=sno_prestamosperiodo.codper ".
				"	 AND sno_prestamos.codtippre=sno_prestamosperiodo.codtippre ".
				"	 AND sno_prestamos.numpre=sno_prestamosperiodo.numpre ".
	            "    AND sno_prestamos.codemp=sno_concepto.codemp ".
                "    AND sno_prestamos.codnom=sno_concepto.codnom ".
                "    AND sno_prestamos.codconc=sno_concepto.codconc ";
	   $rs_data=$this->io_sql->select($ls_sql);
	   if($rs_data===false)
	   {
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Prestamo MÉTODO->uf_calcular_prestamo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
	   }
	   else
	   {			
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codtippre=$rs_data->fields["codtippre"];
				$li_numcuo=$rs_data->fields["numcuo"];
				$li_numpre=$rs_data->fields["numpre"];
				$ld_cuopre=$rs_data->fields["moncuo"];
				$ls_codconc=$rs_data->fields["codconc"];
				$ld_monamopre=$rs_data->fields["monamopre"];
				$ld_acuemp=$ld_monamopre + $ld_cuopre ;
				$ld_monpre=$rs_data->fields["monpre"];
				$ls_quirepcon=$rs_data->fields["quirepcon"];
				$ld_saldo=($ld_monpre-$ld_monamopre)-$ld_acuemp;
				$lb_valido=$this->uf_update_salida_prestamo($as_codper,$ls_codtippre,$ls_codconc,"D",-$ld_cuopre,$ld_acuemp,$ld_saldo,
														 	$li_numcuo,$li_numpre,$ls_quirepcon);
				if(($lb_valido)&&($_SESSION["la_nomina"]["divcon"]==1))
				{
					switch($ls_quirepcon)
					{
						case "1": // Primera Quincena
							$ad_priquires=$ad_priquires-$ld_cuopre;
							break;
						case "2": // Segunda Quincena
							$ad_segquires=$ad_segquires-$ld_cuopre;
							break;
						case "3": // Ambas Quincena
							$ad_priquires=$ad_priquires-round($ld_cuopre/2,2);
							$ad_segquires=$ad_segquires-round($ld_cuopre/2,2);
							break;
						case "-": // Ambas Quincena
							$ad_priquires=$ad_priquires-round($ld_cuopre/2,2);
							$ad_segquires=$ad_segquires-round($ld_cuopre/2,2);
							break;
					}
				}									 
				$ad_dedres=$ad_dedres + $ld_cuopre;
				$ad_totnom=$ad_totnom - $ld_cuopre;
				$rs_data->MoveNext();
			}//while 
	   }//else
	  return $lb_valido;
	}// end function uf_calcular_prestamo	
	//-------------------------------------------------------------------------------------------------------------------------------------------------

    //-------------------------------------------------------------------------------------------------------------------------------------
	function uf_update_salida_prestamo_vac($as_codper,$as_codpre,$as_codconc,$as_tipsal,$as_tipvac,$ad_valsal,$ad_monacusal,$ad_salsal,
										   $ai_numcuo,$ai_numpre,$as_quirepcon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function : uf_update_salida_prestamo_vac
		//	       Access : public (sigesp_sno_calcularnomina)
		//	    Arguments : $as_codper  // codigo del personal
		//                  $as_codpre  // codigo del prestamo
		//                  $as_codconc  //  codigo del concepto
		//                  $as_tipsal  // signo del concepto
		//                  $as_tipvac  // signo del concepto de vacaciones
		//                  $ad_valsal  // cuota del prestamo
		//                  $ad_monacusal  // acumulado del prestamo  
		//                  $ad_salsal  //  saldo del prestamo 
		//                  $ai_numcuo  //  número de cuota que se está pagando
		//                  $ai_numpre  //  número del prestamo
		//	      Returns : $lb_valido True si se ejecuto el update correctamente ó False si hubo error en el update
		//	  Description : Funcion que dado el prestamo del personal y llama a los metodos deducir la cuota del pago de la nomina
		//	   Creado Por : Ing. Yesenia Moreno
		// Fecha Creación : 07/02/2006 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// Calculo del concepto normal
		$li_cuantos=$this->uf_select_salida($as_codper,$as_codconc,$as_tipsal);
		if($li_cuantos==0) // No existen salidas con ese concepto asociado
		{
			$lb_valido=$this->uf_insert_salida($as_codper,$as_codconc,$as_tipsal,$ad_valsal,$ad_monacusal,$ad_salsal,$as_quirepcon);
		}
		else
		{
			$ad_monacusal=($ad_valsal*-1);
			$ad_salsal=$ad_valsal;
			$lb_valido=$this->uf_update_salida($as_codper,$as_codconc,$as_tipsal,$ad_valsal,$ad_monacusal,$ad_salsal,$as_quirepcon);
		}
		// Calculo del concepto de vacaciones
		$li_cuantos=$this->uf_select_salida($as_codper,$as_codconc,$as_tipvac);
		if($li_cuantos==0) // No existen salidas con ese concepto asociado
		{
			$lb_valido=$this->uf_insert_salida($as_codper,$as_codconc,$as_tipvac,$ad_valsal,$ad_monacusal,$ad_salsal,$as_quirepcon);
		}
		else
		{
			$ad_monacusal=0;
			$ad_salsal=0;
			$lb_valido=$this->uf_update_salida($as_codper,$as_codconc,$as_tipsal,$ad_valsal,$ad_monacusal,$ad_salsal,$as_quirepcon);
		}
		return  $lb_valido;
  	}// end function uf_update_salida_prestamo_vac	
  	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_amortizados()
	{  
		////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_amortizados
		//	       Access: public 
		//	      Returns: lb_valido  true si actualizo el amortizado o false en caso contrario
		//	  Description: Funcion que actualiza el amortizado del prestamo del personal  
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 01/02/2006 								Fecha Última Modificación : 14/02/2006
		////////////////////////////////////////////////////////////////////////////////////////////
		$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
		$li_estcuo=1;/*- CUOTA CANCELADA O -*/
		$lb_valido=true;
		$ls_sql="UPDATE sno_prestamos ".
                "   SET monamopre=(SELECT CASE WHEN sum(moncuo) IS NULL THEN 0.00 ELSE sum(moncuo) END ".
                "                    FROM sno_prestamosperiodo ".
				" 					WHERE sno_prestamosperiodo.codemp='".$this->ls_codemp."'".
				"   				  AND sno_prestamosperiodo.codnom='".$this->ls_codnom."'".
				"                     AND sno_prestamosperiodo.estcuo=".$li_estcuo." ".
				"					  AND sno_prestamosperiodo.codemp=sno_prestamos.codemp".
				" 					  AND sno_prestamosperiodo.codnom=sno_prestamos.codnom".
				" 					  AND sno_prestamosperiodo.codper=sno_prestamos.codper".
				" 					  AND sno_prestamosperiodo.codtippre=sno_prestamos.codtippre".
				" 					  AND sno_prestamosperiodo.numpre=sno_prestamos.numpre)".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ";
	    $li_row=$this->io_sql->execute($ls_sql);
	    if($li_row===false)
	    {
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Prestamo MÉTODO->uf_update_amortizados ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
	    }
		if($lb_valido)
		{
			$ls_sql="UPDATE sno_prestamos ".
					"   SET monamopre=monamopre+(SELECT CASE WHEN sum(monamo) IS NULL THEN 0.00 ELSE sum(monamo) END ".
					"                    		   FROM sno_prestamosamortizado ".
					" 							  WHERE sno_prestamosamortizado.codemp='".$this->ls_codemp."'".
					"   				  			AND sno_prestamosamortizado.codnom='".$this->ls_codnom."'".
					"					 			AND sno_prestamosamortizado.codemp=sno_prestamos.codemp".
					" 					 			AND sno_prestamosamortizado.codnom=sno_prestamos.codnom".
					" 								AND sno_prestamosamortizado.codper=sno_prestamos.codper".
					" 					  			AND sno_prestamosamortizado.codtippre=sno_prestamos.codtippre".
					" 					  			AND sno_prestamosamortizado.numpre=sno_prestamos.numpre)".
					" WHERE codemp='".$this->ls_codemp."' ".
					"   AND codnom='".$this->ls_codnom."' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Prestamo MÉTODO->uf_update_amortizados ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			}
		
		}
		
		return $lb_valido;	
	}// end function uf_update_amortizados	
	//-----------------------------------------------------------------------------------------------------------------------------------

 	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_cancelar_prestamos()
	{  
		////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_cancelar_prestamos
		//	       Access: public (sigesp_sno_c_cierre_periodo)
		//	      Returns: lb_valido  true si actualizo el prestamo o false si hubo un error
		//	  Description: Función que verifica si el amorizado es igual al monto del prestamo se coloca como cancelado 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 14/02/2006 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////

		$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
		$lb_valido=true;
		$ls_sql="UPDATE sno_prestamos ".
                "   SET stapre=3 ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND monpre = monamopre ";

	    $li_row=$this->io_sql->execute($ls_sql);
	    if($li_row===false)
	    {
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Prestamo MÉTODO->uf_cancelar_prestamos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
	    }
	   return $lb_valido;	
	}// end function uf_cancelar_prestamos	
    //-----------------------------------------------------------------------------------------------------------------------------------

 	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_suspender_prestamos()
	{  
		////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_suspender_prestamos
		//		   Access: private 
		//	    Arguments: as_codper // código de personal
		//                 as_codtippre //  codigo del tipo de prestamo   
		//                 ai_numpre //  número del prestamo
		//	      Returns: lb_valido  true si actualizo el prestamo o false si hubo un error
		//	  Description: Función que Si el prestamo está activo pero para el próximo período no tiene las cuotas a cancelar lo
		//					suspende
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 14/02/2006 								Fecha Última Modificación :
		////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
		$ld_fecdesper=$_SESSION["la_nomina"]["fecdesper"];
		$ld_fechasper=$_SESSION["la_nomina"]["fechasper"];
		$li_tippernom = $_SESSION["la_nomina"]["tippernom"];
		$li_numpernom = $_SESSION["la_nomina"]["numpernom"];
		$li_stapre = 1; // Cuota Activa
		$li_incremento = $this->uf_load_incremento();			
		$ld_fechasper=$this->io_funciones->uf_convertirfecmostrar($ld_fechasper);
		$lb_valido=$this->uf_incrementar_periodo($li_tippernom,$li_incremento,0,$ls_peractnom,$ld_fecdesper,$ld_fechasper);
		if($lb_valido)
		{
			if(intval($ls_peractnom)>intval($li_numpernom))
			{
				$ls_peractnom="001";
			}
			$ld_fecdesper=$this->io_funciones->uf_convertirdatetobd($ld_fecdesper);
			$ld_fechasper=$this->io_funciones->uf_convertirdatetobd($ld_fechasper);
			$ls_sql="UPDATE sno_prestamos ".
					"   SET stapre=2 ".
					" WHERE codemp = '".$this->ls_codemp."' ".
					"   AND codnom = '".$this->ls_codnom."' ".
					"   AND monpre > monamopre ".
					"   AND stapre = ".$li_stapre." ".
					"   AND numpre NOT IN (SELECT numpre FROM sno_prestamosperiodo ".
					"                        WHERE sno_prestamos.stapre = ".$li_stapre." ".
					//"   					   AND sno_prestamosperiodo.percob = '".$ls_peractnom."' ".
					"   					   AND sno_prestamosperiodo.feciniper = '".$ld_fecdesper."' ".
					"   					   AND sno_prestamosperiodo.fecfinper = '".$ld_fechasper."' ".
					"						   AND sno_prestamosperiodo.codemp = sno_prestamos.codemp ".
					"   					   AND sno_prestamosperiodo.codnom = sno_prestamos.codnom ".
					"   					   AND sno_prestamosperiodo.codper = sno_prestamos.codper ".
					"   					   AND sno_prestamosperiodo.codtippre = sno_prestamos.codtippre ".
					"   				   	   AND sno_prestamosperiodo.numpre = sno_prestamos.numpre) ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Prestamo MÉTODO->uf_suspender_prestamos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			}
		}
	   	return $lb_valido;	
	}// end function uf_suspender_prestamos	
    //-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_activar_prestamos()
	{  
		////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_activar_prestamos
		//		   Access: private 
		//	    Arguments: as_codper // código de personal
		//                 as_codtippre //  codigo del tipo de prestamo   
		//                 ai_numpre //  número del prestamo
		//	      Returns: lb_valido  true si actualizo el prestamo o false si hubo un error
		//	  Description: Función que Si el prestamo está suspendido pero para el próximo período ya tiene las cuotas a cancelar lo
		//					activa
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/02/2006 								Fecha Última Modificación : 14/02/2006
		////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
		$ld_fecdesper=$_SESSION["la_nomina"]["fecdesper"];
		$ld_fechasper=$_SESSION["la_nomina"]["fechasper"];
		$li_tippernom = $_SESSION["la_nomina"]["tippernom"];
		$li_stapre=2; // Cuota Suspendida
		$li_incremento=$this->uf_load_incremento();			
		$ld_fechasper=$this->io_funciones->uf_convertirfecmostrar($ld_fechasper);
		$lb_valido=$this->uf_incrementar_periodo($li_tippernom,$li_incremento,0,$ls_peractnom,$ld_fecdesper,$ld_fechasper);
		if($lb_valido)
		{
			$ld_fecdesper=$this->io_funciones->uf_convertirdatetobd($ld_fecdesper);
			$ld_fechasper=$this->io_funciones->uf_convertirdatetobd($ld_fechasper);
			$ls_sql="UPDATE sno_prestamos ".
					"   SET stapre=1 ".
					" WHERE codemp = '".$this->ls_codemp."' ".
					"   AND codnom = '".$this->ls_codnom."' ".
					"   AND monpre > monamopre ".
					"   AND stapre = ".$li_stapre." ".
					"   AND numpre  IN (SELECT numpre FROM sno_prestamosperiodo ".
					"                     WHERE sno_prestamos.stapre = ".$li_stapre." ".
					//"   					AND sno_prestamosperiodo.percob = '".$ls_peractnom."' ".
					"   					AND sno_prestamosperiodo.feciniper = '".$ld_fecdesper."' ".
					"   					AND sno_prestamosperiodo.fecfinper = '".$ld_fechasper."' ".
					"						AND sno_prestamosperiodo.codemp = sno_prestamos.codemp ".
					"   					AND sno_prestamosperiodo.codnom = sno_prestamos.codnom ".
					"   					AND sno_prestamosperiodo.codper = sno_prestamos.codper ".
					"   					AND sno_prestamosperiodo.codtippre = sno_prestamos.codtippre ".
					"   					AND sno_prestamosperiodo.numpre = sno_prestamos.numpre)";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Prestamo MÉTODO->uf_activar_prestamos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			}
		}
	   	return $lb_valido;	
	 }// end function uf_activar_prestamos	
   //-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_amortizarprestamo($as_codper,$as_codtippre,$ai_numpre,$ai_numcuofalpre,$ai_nuemoncuopre,$ai_sueper,$ai_cuopag,
								   $ai_salactpre,$as_obsrecpre,$ai_numcuopre,$as_configuracion,$ai_montoamortizar,$as_tipcuopre,
								   $aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_amortizarprestamo
		//		   Access: public (sigesp_sno_p_prestamoamortizar) 
		//	    Arguments: as_codper  // Código del Personal
		//				   as_codtippre  // Código del tipo de Prestamo
		//				   ai_numpre  // Número Correlativo del Prestamo
		//				   ai_numcuofalpre  // Número de Cuotas faltantes
		//				   ai_nuemoncuopre  // Monto de las cuotas del prestamo
		//				   ai_sueper  // sueldo del personal
		//				   ai_cuopag  // Cuotas que han sido canceladas
		//				   ai_salactpre  // Saldo actual del Prestamo
		//				   as_obsrecpre  // Observación de recalculo de las cuotas
		//				   ai_numcuopre  // Número Inicial de Cuotas del Prestamo 
		//				   as_configuracion  // Configuración si es por monto ó por cuota
		//				   ai_montoamortizar  // Monto a Amortizar
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el recalcular ó False si hubo error en el recalcular
		//	  Description: función que recalcula las cuotas del prestamo del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/12/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;			
		$ai_montoamortizar=str_replace(".","",$ai_montoamortizar);
		$ai_montoamortizar=str_replace(",",".",$ai_montoamortizar);
		$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
		$ai_numamo=0;
		$this->io_sql->begin_transaction();
		$lb_valido=$this->uf_load_correlativo_amortizado($ai_numpre,&$ai_numamo);
		if($lb_valido)
		{
			$ls_sql="INSERT INTO sno_prestamosamortizado (codemp, codnom, codper, numpre, codtippre, numamo, peramo, fecamo, ".
					" monamo, desamo) VALUES ('".$this->ls_codemp."','".$this->ls_codnom."', '".$as_codper."', ".$ai_numpre.", ".
					"'".$as_codtippre."', ".$ai_numamo.",'".$ls_peractnom."', '".date("Y-m-d")."', ".$ai_montoamortizar.", '".$as_obsrecpre."') ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Prestamo MÉTODO->uf_amortizarprestamo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			}
		}
		if($lb_valido)
		{
			$ls_sql="UPDATE sno_prestamos ".
					"   SET monamopre = (monamopre+".$ai_montoamortizar.") ".
					" WHERE codemp = '".$this->ls_codemp."' ".
					"   AND codnom = '".$this->ls_codnom."' ".
					"   AND codper = '".$as_codper."' ".
					"   AND numpre = ".$ai_numpre." ".
					"   AND codtippre = '".$as_codtippre."' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Prestamo MÉTODO->uf_amortizarprestamo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			}
		}
		if($lb_valido)
		{
			$ai_salactpre=str_replace(".","",$ai_salactpre);
			$ai_salactpre=str_replace(",",".",$ai_salactpre);
			$ai_salactpre=($ai_salactpre-$ai_montoamortizar);
			$ai_nuemoncuopre=str_replace(".","",$ai_nuemoncuopre);
			$ai_nuemoncuopre=str_replace(",",".",$ai_nuemoncuopre);
			if((intval($ai_salactpre)==0)&&(intval($ai_nuemoncuopre)==0))
			{
				$li_totcuo=$ai_cuopag;
				$lb_valido=$this->io_cuota->uf_delete_cuota($as_codper,$as_codtippre,$ai_numpre,"",0,$aa_seguridad);
				if($lb_valido)
				{
					$lb_valido=$this->uf_cancelar_prestamos();
				}
			}
			else
			{
				$li_totcuo=$ai_numcuofalpre+$ai_cuopag;
				$li_numpricuo=$ai_cuopag+1;
				$ls_ultpernom="";
				if($li_totcuo>$ai_numcuopre)
				{
					$ls_percob="";
					$ld_fecdes="";
					$ld_fechas="";
					$li_numultcuo=$ai_numcuopre;
					$li_cuofin=$ai_numcuopre + ($li_totcuo - $ai_numcuopre);
					$lb_valido=$this->io_cuota->uf_obtener_cuota($as_codper,$as_codtippre,$ai_numpre,"2",$ls_percob,$ld_fecdes,$ld_fechas,$ai_numcuopre);
					$ld_fechas = $this->io_funciones->uf_convertirfecmostrar($ld_fechas);
					if($lb_valido)
					{
						$lb_valido=$this->uf_load_ultimoperiodo($ls_ultpernom);
					}
				}
				else
				{
					$li_numultcuo=$li_totcuo;
					$li_cuofin=$li_totcuo + ($ai_numcuopre-$li_totcuo);
				}		
				if($lb_valido)
				{
					$lb_valido=$this->io_cuota->uf_verificarsueldo($as_codper,$ai_nuemoncuopre,$ai_sueper,$ai_numpre);
				}
				if($lb_valido)
				{	
					$li_tippernom = $_SESSION["la_nomina"]["tippernom"];
					$li_incremento = $this->uf_load_incremento();
					$li_cuota=$li_numpricuo;
					$lb_valido=$this->io_cuota->uf_update_cuota($as_codper,$as_codtippre,$ai_numpre,$li_cuota,"","","",$ai_nuemoncuopre,"2",$aa_seguridad);
					for($li_i=($li_numpricuo+1);$li_i<=$li_numultcuo;++$li_i)// Recorro las cuotas que ya está generadas y les actualizo el monto
					{
						$li_cuota=$li_i;
						if(($li_totcuo<=$ai_numcuopre)&&($li_i==$li_numultcuo))// Si voy a actualizar las y es la última que se va a generar
						{
							$ai_nuemoncuopre = ($ai_salactpre - ($ai_nuemoncuopre*($ai_numcuofalpre-1)));
						}
						$lb_valido=$this->io_cuota->uf_update_cuota($as_codper,$as_codtippre,$ai_numpre,$li_cuota,"","","",$ai_nuemoncuopre,"2",$aa_seguridad);
					}
					for($li_i=($li_numultcuo+1);$li_i<=$li_cuofin;++$li_i)// Recorro las restantes bien sea que generarlas ó para eliminarlas
					{
						$li_cuota=$li_i;
						if($li_totcuo>=$ai_numcuopre)// Si necesito generar mas cuotas 
						{
							if(intval($ls_percob)>=intval($ls_ultpernom))
							{
								$ls_percob="000";
							}					
							if($as_tipcuopre=="1")
							{
								if(intval($ls_percob)>=intval($ls_ultpernom-1))
								{
									$ls_percob=(intval($ls_percob)-intval($ls_ultpernom));
								}	
							}				
							if($li_i==$li_cuofin)// Sí es la última cuota
							{
								$ai_nuemoncuopre = ($ai_salactpre - ($ai_nuemoncuopre*($ai_numcuofalpre-1)));
							}
							$lb_valido=$this->uf_incrementar_periodo($li_tippernom,$li_incremento,$as_tipcuopre,$ls_percob,$ld_fecdes,$ld_fechas);
							if($lb_valido)
							{
								$lb_valido=$this->io_cuota->uf_insert_cuota($as_codper,$as_codtippre,$ai_numpre,$li_cuota,$ls_percob,$ld_fecdes,$ld_fechas,$ai_nuemoncuopre,$aa_seguridad);
							}
						}
						else// Si necesito eliminar cuotas
						{
							$lb_valido=$this->io_cuota->uf_delete_cuota($as_codper,$as_codtippre,$ai_numpre,$li_cuota,"1",$aa_seguridad);
						}		
					}
				}
			}
			if($lb_valido)
			{	
				$lb_valido=$this->uf_update_nrocuota_prestamo($as_codper,$as_codtippre,$ai_numpre,$li_totcuo,$aa_seguridad);
			}
			if($lb_valido)
			{	
				$lb_valido=$this->uf_update_observacion_prestamo($as_codper,$as_codtippre,$ai_numpre,"obsrecpre",$as_obsrecpre,$aa_seguridad);
			}
			if($lb_valido)
			{
				$lb_valido = $this->io_cuota->uf_verificar_integridadcuota($as_codper,$as_codtippre,$ai_numpre);
			}
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó un amortizado número ".$ai_numamo." Monto ".$ai_montoamortizar." del Prestamo nro ".$ai_numpre." de tipo ".$as_codtippre." del personal ".
							 "".$as_codper." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		if($lb_valido)
		{	
			$this->io_mensajes->message("El Monto del prestamo fué amortizado.");
			$this->io_sql->commit();
		}
		else
		{
			$lb_valido=false;
			$this->io_mensajes->message("Ocurrio un error al amortizar el monto.");
			$this->io_sql->rollback();
		}

		return $lb_valido;
	}// end function uf_amortizarprestamo	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_correlativo_amortizado($ai_numpre,&$ai_numamo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_correlativo_amortizado
		//		   Access: private (uf_guardar) 
		//      Arguments: ai_numamo  // Nuevo número de amortizado
		//	      Returns: lb_valido True si se ejecutó correctamente ó False si hubo algún error
		//	  Description: Funcion que busca el correlativo del último prestamo  y genera el nuevo correlativo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_numamo=1;
		$ls_sql="SELECT numamo as numero ".
				"  FROM sno_prestamosamortizado ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND numpre=".$ai_numpre."".
				" ORDER BY numamo DESC ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Prestamo MÉTODO->uf_load_correlativo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_numamo=$row["numero"]+1;
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_load_correlativo_amortizado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_amortizado($as_codper,$as_codtippre,$ai_numpre,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_amortizado
		//		   Access: private (uf_guardar) 
		//      Arguments: ai_numamo  // Nuevo número de amortizado
		//	      Returns: lb_valido True si se ejecutó correctamente ó False si hubo algún error
		//	  Description: Funcion que elimina los amortizados de un prestamo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 13/04/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_numamo=1;
		$ls_sql="DELETE ".
				"  FROM sno_prestamosamortizado ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND codper='".$as_codper."'".
				"   AND codtippre='".$as_codtippre."'".
				"   AND numpre=".$ai_numpre."";
		$rs_data=$this->io_sql->execute($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Prestamo MÉTODO->uf_delete_amortizado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_delete_amortizado
	//-----------------------------------------------------------------------------------------------------------------------------------

	
	//--------------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_config($as_sistema, $as_seccion, $as_variable, $as_valor, $as_tipo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_config
		//		   Access: public
		//	    Arguments: as_sistema  // Sistema al que pertenece la variable
		//				   as_seccion  // Sección a la que pertenece la variable
		//				   as_variable  // Variable a buscar
		//				   as_valor  // valor por defecto que debe tener la variable
		//				   as_tipo  // tipo de la variable
		//	      Returns: $lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que inserta la variable de configuración
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();		
		$ls_sql="DELETE ".
				"  FROM sigesp_config ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codsis='".$as_sistema."' ".
				"   AND seccion='".$as_seccion."' ".
				"   AND entry='".$as_variable."' ";		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Report Contable MÉTODO->uf_insert_config ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			switch ($as_tipo)
			{
				case "C"://Caracter
					$valor = $as_valor;
					break;

				case "D"://Double
					$as_valor=str_replace(".","",$as_valor);
					$as_valor=str_replace(",",".",$as_valor);
					$valor = $as_valor;
					break;

				case "B"://Boolean
					$valor = $as_valor;
					break;

				case "I"://Integer
					$valor = intval($as_valor);
					break;
			}
			$ls_sql="INSERT INTO sigesp_config(codemp, codsis, seccion, entry, value, type)VALUES ".
					"('".$this->ls_codemp."','".$as_sistema."','".$as_seccion."','".$as_variable."','".$valor."','".$as_tipo."')";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Report Contable MÉTODO->uf_insert_config ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
			else
			{
				$this->io_sql->commit();
			}
		}
		return $lb_valido;
	}// end function uf_insert_config	
//------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------
    function uf_select_config($as_sistema, $as_seccion, $as_variable, $as_valor, $as_tipo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_config
		//		   Access: public
		//	    Arguments: as_sistema  // Sistema al que pertenece la variable
		//				   as_seccion  // Sección a la que pertenece la variable
		//				   as_variable  // Variable nombre de la variable a buscar
		//				   as_valor  // valor por defecto que debe tener la variable
		//				   as_tipo  // tipo de la variable
		//	      Returns: $ls_resultado variable buscado
		//	  Description: Función que obtiene una variable de la tabla config
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_valor="";
		$ls_sql="SELECT value ".
				"  FROM sigesp_config ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codsis='".$as_sistema."' ".
				"   AND seccion='".$as_seccion."' ".
				"   AND entry='".$as_variable."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report Contable MÉTODO->uf_select_config ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			$li_i=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_valor=$row["value"];
				$li_i=$li_i+1;
			}
			if($li_i==0)
			{
				$lb_valido=$this->uf_insert_config($as_sistema, $as_seccion, $as_variable, $as_valor, $as_tipo);
				if ($lb_valido)
				{
					$ls_valor=$this->uf_select_config($as_sistema, $as_seccion, $as_variable, $as_valor, $as_tipo);
				}
			}
			$this->io_sql->free_result($rs_data);		
		}
		return rtrim($ls_valor);
	}// end function uf_select_config
//-------------------------------------------------------------------------------------------------------------------------------------
    function uf_contar_prestamos($as_codper,$as_codtippre)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_contar_prestamos
		//		   Access: public
		//	    Arguments: as_codper  // Sistema al que pertenece la variable
		//				   as_tipopres  // Sección a la que pertenece la variable		
		//	      Returns: $ls_resultado variable buscado
		//	  Description: Función que cuenta los prestamo del mismo tipo a un personal en estado activo o suspendido
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 27/08/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_sql=" SELECT COUNT(*) as contar            ".
				"    FROM sno_prestamos                ".
				"   WHERE codper='".$as_codper."'      ".	
				"     AND codtippre='".$as_codtippre."'".				
				"  	  AND (stapre=1 or stapre=2);      ";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("MÉTODO->uf_contar_prestamos ERROR->".
			                            $this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{			
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_valor=$row["contar"];				
			}			
			$this->io_sql->free_result($rs_data);		
		}
		return rtrim($ls_valor);
	}// end uf_contar_prestamos
//-----------------------------------------------------------------------------------------------------------------------------------
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
?>