<?php
class sigesp_sno_c_prestamocuotas
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $ls_codemp;
	var $ls_codnom;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sno_c_prestamocuotas()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sno_c_prestamocuotas
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 13/02/2006 								Fecha Última Modificación : 
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
		$this->io_seguridad= new sigesp_c_seguridad();		
		require_once("sigesp_sno.php");
		$this->io_sno=new sigesp_sno();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		if(array_key_exists("la_nomina",$_SESSION))
		{
        	$this->ls_codnom=$_SESSION["la_nomina"]["codnom"];
		}
		else
		{
			$this->ls_codnom="0000";
		}
	}// end function sigesp_sno_c_prestamocuotas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_cuota($as_codper,$as_codtippre,$ai_numpre,$ai_numcuo,$as_percob,$ad_feciniper,$ad_fecfinper,$ai_moncuo,
							 $aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function : uf_insert_cuota
		//		   Access : private (sigesp_sno_c_prestamo) 
		//	    Arguments : as_codper  // Código del Personal
		//				    as_codtippre  // Código del tipo de Prestamo
		//				    ai_numpre  // Número Correlativo del Prestamo
		//				    ai_numcuo  // Número de Cuota
		//				    as_percob  // Período donde se va a cobrar la cuota
		//				    ad_feciniper  // Fecha Inicio del Período donde se va a cobrar la cuota
		//				    ad_fecfinper  // Fecha Fin del Período donde se va a cobrar la cuota
		//				    ai_moncuo  // Monto de la cuota
		//				    aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns : $lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description : Funcion que inserta la cuota del prestamo
		//	   Creado Por : Ing. Yesenia Moreno
		// Fecha Creación : 07/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ad_feciniper=$this->io_funciones->uf_convertirdatetobd($ad_feciniper);
		$ad_fecfinper=$this->io_funciones->uf_convertirdatetobd($ad_fecfinper);
		$ls_sql="INSERT INTO sno_prestamosperiodo(codemp,codnom,codper,codtippre,numpre,numcuo,percob,feciniper,fecfinper,moncuo,estcuo)".
				"VALUES('".$this->ls_codemp."','".$this->ls_codnom."','".$as_codper."','".$as_codtippre."',".$ai_numpre.",".$ai_numcuo.",".
				"'".$as_percob."','".$ad_feciniper."','".$ad_fecfinper."',".$ai_moncuo.",'0')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Prestamo Cuota MÉTODO->uf_insert_cuota ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la cuota ".$ai_numcuo." del Prestamo nro ".$ai_numpre." de tipo ".$as_codtippre." del personal ".
							 "".$as_codper." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if(!$lb_valido)
			{
				$this->io_mensajes->message("CLASE->Prestamo Cuota MÉTODO->uf_insert_cuota ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			}
		}
		return $lb_valido;
	}// end function uf_insert_cuota	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_delete_cuota($as_codper,$as_codtippre,$ai_numpre,$ai_numcuo,$as_tipo,$aa_seguridad)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_cuota
		//		   Access: public (sigesp_sno_c_prestamo) 
		//	    Arguments: as_codper  // Código del Personal
		//				   as_codtippre  // Código del tipo de Prestamo
		//				   ai_numpre  // Número Correlativo del Prestamo
		//				   ai_numcuo  // Número de la cuota
		//				   as_tipo  // Tipo de eliminación de las cuotas si son todas las cuotas ó solo las no canceladas
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si realizó el delete ó False si no lo eliminó
		//	  Description: Función que elimina las cuotas asociadas a un prestamo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 		$lb_valido=false;
       	$ls_sql="DELETE ".
				"  FROM sno_prestamosperiodo ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND codper='".$as_codper."'".
				"   AND codtippre='".$as_codtippre."'".
				"   AND numpre=".$ai_numpre."".
				"   AND estcuo=0";
		if($as_tipo=="1")//Se eliminan solo la cuota actual
		{
			$ls_sql=$ls_sql."   AND numcuo=".$ai_numcuo." ";
		}

       	$li_row=$this->io_sql->execute($ls_sql);
       	if ($li_row===false)
       	{
        	$this->io_mensajes->message("CLASE->Prestamo Cuota MÉTODO->uf_delete_cuota ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
       	}
       	else
       	{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="DELETE";
				if ($as_tipo=="1")//Se eliminan solo la cuota actual
				{
					$ls_descripcion ="Eliminó la cuota ".$ai_numcuo." No cancelada del prestamo ".$ai_numpre." de tipo ".$as_codtippre." del personal ".
									 "".$as_codper." asociado a la nómina ".$this->ls_codnom;
				}
				else //Se eliminan todas las cuotas
				{
					$ls_descripcion ="Eliminó las cuotas No canceladas del prestamo ".$ai_numpre." de tipo ".$as_codtippre." del personal ".
									 "".$as_codper." asociado a la nómina ".$this->ls_codnom;
				}
				
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
       	}
		return $lb_valido;    
	}// end function uf_delete_cuota
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_cuota($as_codper,$as_codtippre,$ai_numpre,$as_tipo,&$as_percob,&$ad_feciniper,&$ad_fecfinper,&$ai_numcuo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_cuota
		//		   Access: public (sigesp_sno_c_prestamo) 
		//	    Arguments: as_codper  // Código del Personal
		//				   as_codtippre  // Código del tipo de Prestamo
		//				   ai_numpre  // Número Correlativo del Prestamo
		//				   as_tipo  // Tipo de Llamada al método
		//	    		   as_percob  // Periodo a cobrar
		//	    		   ad_feciniper  // Fecha de Inicio del Período
		//	    		   ad_fecfinper  // Fecha Fin del Periodo
		//	    		   ai_numcuo  // Número de Cuota
		//	      Returns: lb_valido True si se obtiene el número de cuota sin problema False si hubo error
		//	  Description: Funcion que obtiene el número de cuota dado el periodo y las fechas desde y hasta
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 10/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		switch($as_tipo)
		{
			case "1": // se filtra por periodo de cobro, fecha de inicio, fecha de fin
				$ls_sql="SELECT numcuo, percob, feciniper, fecfinper ".
						"  FROM sno_prestamosperiodo ".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND codnom='".$this->ls_codnom."'".
						"   AND codper='".$as_codper."'".
						"   AND codtippre='".$as_codtippre."'".
						"   AND numpre=".$ai_numpre."".
						"   AND estcuo=0".
						//"   AND percob='".$as_percob."'".
						"   AND feciniper='".$ad_feciniper."'".
						"   AND fecfinper='".$ad_fecfinper."'";
				break;
				
			case "2": // se filtra por número de cuota
				$ls_sql="SELECT numcuo, percob, feciniper, fecfinper ".
						"  FROM sno_prestamosperiodo ".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND codnom='".$this->ls_codnom."'".
						"   AND codper='".$as_codper."'".
						"   AND codtippre='".$as_codtippre."'".
						"   AND numpre=".$ai_numpre."".
						"   AND estcuo=0".
						"   AND numcuo=".$ai_numcuo."";
				break;
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Prestamo Cuota MÉTODO->uf_obtener_cuota ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_numcuo=$row["numcuo"];
				$as_percob=$row["percob"];
				$ad_feciniper=$this->io_funciones->uf_formatovalidofecha($row["feciniper"]);
				$ad_fecfinper=$this->io_funciones->uf_formatovalidofecha($row["fecfinper"]);
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_obtener_cuota
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_ultimacuota($as_codper,$as_codtippre,$ai_numpre,&$ai_numcuo,&$as_percob,&$ad_feciniper,&$ad_fecfinper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_ultimacuota
		//		   Access: public (sigesp_sno_c_prestamo) 
		//	    Arguments: as_codper  // Código del Personal
		//				   as_codtippre  // Código del tipo de Prestamo
		//				   ai_numpre  // Número Correlativo del Prestamo
		//	    		   ai_numcuo  // Número de Cuota
		//	    		   as_percob  // Periodo a cobrar
		//	    		   ad_feciniper  // Fecha de Inicio del Período
		//	    		   ad_fecfinper  // Fecha Fin del Periodo
		//	      Returns: lb_valido True si se obtiene la última cuota del prestamo sin problema False si hubo error
		//	  Description: Funcion que obtiene la información de la última del prestamo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 09/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT numcuo, percob, feciniper, fecfinper".
				"  FROM sno_prestamosperiodo ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND codper='".$as_codper."'".
				"   AND codtippre='".$as_codtippre."'".
				"   AND numpre=".$ai_numpre."".
				" ORDER BY numcuo DESC ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Prestamo Cuota MÉTODO->uf_obtener_ultimacuota ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_numcuo=$row["numcuo"];
				$as_percob=$row["percob"];
				$ad_feciniper=$row["feciniper"];
				$ad_fecfinper=$row["fecfinper"];
			}
			else //si no hay cuotas busco el periodo inicial
			{
				$ls_sql="SELECT sno_prestamos.perinipre, sno_periodo.fecdesper, sno_periodo.fechasper ".
						"  FROM sno_prestamos, sno_periodo ".
						" WHERE sno_prestamos.codemp='".$this->ls_codemp."' ".
						"   AND sno_prestamos.codnom='".$this->ls_codnom."' ".
						"   AND sno_prestamos.codper='".$as_codper."' ".
						"   AND sno_prestamos.codtippre='".$as_codtippre."' ".
						"   AND sno_prestamos.numpre=".$ai_numpre." ".
						"   AND sno_periodo.codperi<>'000' ".
						"   AND sno_prestamos.codemp=sno_periodo.codemp ".
						"	AND sno_prestamos.codnom=sno_periodo.codnom ".
						"   AND sno_prestamos.perinipre=sno_periodo.codperi ";			
				$rs_data=$this->io_sql->select($ls_sql);
				if($rs_data===false)
				{
					$this->io_mensajes->message("CLASE->Prestamo Cuota MÉTODO->uf_obtener_ultimacuota ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
					$lb_valido=false;
				}
				else
				{
					if($row=$this->io_sql->fetch_row($rs_data))
					{
						$ai_numcuo=0;
						$as_percob=$row["perinipre"];
						$ad_feciniper=$row["fecdesper"];
						$ad_fecfinper=$row["fechasper"];
					}
				}
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_obtener_ultimacuota
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_update_cuota($as_codper,$as_codtippre,$ai_numpre,$ai_numcuo,$as_percob,$as_feciniper,$as_fecfinper,$ai_moncuopre,$as_tipo,$aa_seguridad)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_cuota
		//		   Access: public (sigesp_sno_c_prestamo) 
		//	    Arguments: as_codper  // Código del Personal
		//				   as_codtippre  // Código del tipo de Prestamo
		//				   ai_numpre  // Número Correlativo del Prestamo
		//				   ai_numcuo  // Número de cuota del prestamo
		//				   as_percob  // Período en que se va a cobrar esa cuota
		//				   as_feciniper  // Fecha desde del período
		//				   as_fecfinper  // Fecha Hasta del Período
		//				   ai_moncuopre  // Monto de la cuota del prestamo
		//				   as_tipo  // Tipo de actualización si es de los periodos a cobrar ó si es el monto de la cuota
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si realizó el update ó False si no lo actualizó
		//	  Description: Función que actualiza el periodo y las fechas del mismo de una cuota esto debido a una suspensión
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 10/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 		$lb_valido=false;
		$as_feciniper=$this->io_funciones->uf_convertirdatetobd($as_feciniper);
		$as_fecfinper=$this->io_funciones->uf_convertirdatetobd($as_fecfinper);
		switch($as_tipo)
		{
			case "1": // Actualizo el período y las fechas de inicio y fin
				$ls_sql="UPDATE sno_prestamosperiodo ".
						"   SET percob='".$as_percob."', ".
						"   	feciniper='".$as_feciniper."', ".
						"   	fecfinper='".$as_fecfinper."' ".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND codnom='".$this->ls_codnom."'".
						"   AND codper='".$as_codper."'".
						"   AND codtippre='".$as_codtippre."'".
						"   AND numpre=".$ai_numpre."".
						"   AND estcuo=0".
						"   AND numcuo=".$ai_numcuo."";
				break;

			case "2": // Actualizo el monto de la cuota
				$ls_sql="UPDATE sno_prestamosperiodo ".
						"   SET moncuo=".$ai_moncuopre." ".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND codnom='".$this->ls_codnom."'".
						"   AND codper='".$as_codper."'".
						"   AND codtippre='".$as_codtippre."'".
						"   AND numpre=".$ai_numpre."".
						"   AND estcuo=0".
						"   AND numcuo=".$ai_numcuo."";
				break;
		}
		$li_row=$this->io_sql->execute($ls_sql);
       	if ($li_row===false)
       	{
        	$this->io_mensajes->message("CLASE->Prestamo Cuota MÉTODO->uf_update_cuota ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
       	}
       	else
       	{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			switch($as_tipo)
			{
				case "1": // Actualizo el período y las fechas de inicio y fin
					$ls_descripcion ="Actualizó el período y las fechas de la cuota ".$ai_numcuo." del prestamo ".$ai_numpre." de tipo ".$as_codtippre." del personal ".
									 "".$as_codper." asociado a la nómina ".$this->ls_codnom;
				break;

				case "2": // Actualizo el monto de la cuota
					$ls_descripcion ="Actualizó el monto de la cuota ".$ai_numcuo." del prestamo ".$ai_numpre." de tipo ".$as_codtippre." del personal ".
									 "".$as_codper." asociado a la nómina ".$this->ls_codnom;
					break;
			}
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
       	}
		return $lb_valido;    
	}// end function uf_update_cuota
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_existe_cuotapagada($as_codper,$as_codtippre,$ai_numpre)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_existe_cuotapagada
		//		   Access: private (sigesp_sno_c_prestamo) 
		//	    Arguments: as_codper  // Código del Personal
		//				   as_codtippre  // Código del tipo de Prestamo
		//				   ai_numpre  // Número Correlativo del Prestamo
		//	      Returns: lb_valido True si existe ó False si no existe
		//	  Description: Funcion que valida que no haya ninguna cuota pagada  para poder eliminar el prestamo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 		$lb_existe=false;
       	$ls_sql="SELECT COUNT(codper) as total ".
				"  FROM sno_prestamosperiodo ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND codper='".$as_codper."'".
				"   AND codtippre='".$as_codtippre."'".
				"   AND numpre=".$ai_numpre."".
				"   AND estcuo=1";
       	$rs_data=$this->io_sql->select($ls_sql);
       	if ($rs_data===false)
       	{
        	$this->io_mensajes->message("CLASE->Prestamo Cuota MÉTODO->uf_existepago ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
       	}
       	else
       	{
			if ($row=$this->io_sql->fetch_row($rs_data))
         	{
            	$li_row=$row["total"];
            	if ($li_row>0)  
				{
               		$lb_existe=true;
				}
         	}
			$this->io_sql->free_result($rs_data);	
       	}
		return $lb_existe ;    
	}// end function uf_update_cuota
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_verificar_integridadcuota($as_codper,$as_codtippre,$ai_numpre)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_integridadcuota
		//		   Access: public (sigesp_sno_c_prestamo) 
		//	    Arguments: as_codper  // Código del Personal
		//				   as_codtippre  // Código del tipo de Prestamo
		//				   ai_numpre  // Número Correlativo del Prestamo
		//	      Returns: lb_valido True si existe ó False si no existe
		//	  Description: Funcion que valida que la suma de los montos de las cuotas sea exactamente igual que el del prestamo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 13/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 		$lb_valido=false;
       	$ls_sql="SELECT monpre, ".
				"       (SELECT SUM(sno_prestamosperiodo.moncuo) FROM sno_prestamosperiodo ".
				"         WHERE sno_prestamos.codemp=sno_prestamosperiodo.codemp ".
				"   		AND sno_prestamos.codnom=sno_prestamosperiodo.codnom ".
				"   		AND sno_prestamos.codper=sno_prestamosperiodo.codper ".
				"           AND sno_prestamos.numpre=sno_prestamosperiodo.numpre ".
				"   		AND sno_prestamos.codtippre=sno_prestamosperiodo.codtippre) AS cuotas, ".
				"       (SELECT SUM(sno_prestamosamortizado.monamo) FROM sno_prestamosamortizado ".
				"   	  WHERE sno_prestamos.codemp=sno_prestamosamortizado.codemp ".
				"   		AND sno_prestamos.codnom=sno_prestamosamortizado.codnom ".
				"   		AND sno_prestamos.codper=sno_prestamosamortizado.codper ".
				"           AND sno_prestamos.numpre=sno_prestamosamortizado.numpre ".
				"   		AND sno_prestamos.codtippre=sno_prestamosamortizado.codtippre) AS amortizado ".
				"  FROM sno_prestamos ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codper='".$as_codper."' ".
				"   AND codtippre='".$as_codtippre."' ".
				"   AND numpre=".$ai_numpre.
				" GROUP BY codemp, codnom, numpre, codper, codtippre, monpre ";
       	$rs_data=$this->io_sql->select($ls_sql);
       	if ($rs_data===false)
       	{
        	$this->io_mensajes->message("CLASE->Prestamo Cuota MÉTODO->uf_verificar_integridadcuota ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
       	}
       	else
       	{
			if($row=$this->io_sql->fetch_row($rs_data))
         	{
            	$li_cuotas=$row["cuotas"];
            	$li_amortizado=$row["amortizado"];
            	$li_monpre=$row["monpre"];
         	}
			$li_total=round($li_cuotas+$li_amortizado,2);
			$li_monpre=round($li_monpre,2);
			if ($li_total==$li_monpre)  
			{
				$lb_valido=true;
			}
			else
			{
	        	$this->io_mensajes->message("ERROR->La suma de las cuotas no son iguales al prestamo.");
			}
			$this->io_sql->free_result($rs_data);	
       	}
		return $lb_valido ;    
	}// end function uf_verificar_integridadcuota
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_verificarsueldo($as_codper,$ai_moncuo,$ai_sueper,$ai_numpre)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificarsueldo
		//		   Access: private (uf_generarcuotas) 
		//	    Arguments: as_codper  // Código del Personal
		//	    		   ai_moncuo  // Monto de la cuota mensual
		//			       ai_sueper  // Sueldo del personal
		//			       ai_numpre  // Número de Prestamos
		//	      Returns: lb_valido True si la cuota que va a pagar es menor que le 30% del sueldo ó False si es mayor
		//	  Description: Funcion que suma las cuotas de los prestamos que tenga el personal y luego verifica que estas sean menores al
		//				    30% del sueldo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_validarprestamo=$this->io_sno->uf_select_config("SNO","CONFIG","VAL_PORCENTAJE_PRESTAMO","1","I");
		if($li_validarprestamo=="1")
		{
			$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
			$ld_fecdesper=$_SESSION["la_nomina"]["fecdesper"];
			$ls_sql="SELECT SUM(moncuo) as montocuota ".
					"  FROM sno_prestamosperiodo ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codnom='".$this->ls_codnom."'".
					"   AND codper='".$as_codper."'".
					//"   AND percob='".$ls_peractnom."'".
					"   AND numpre<>'".$ai_numpre."'".
					"   AND feciniper='".$ld_fecdesper."'";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_mensajes->message("CLASE->Prestamo Cuota MÉTODO->uf_verificarsueldo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
			}
			else
			{
				$li_montocuota=0;
				if($row=$this->io_sql->fetch_row($rs_data))
				{
					$li_montocuota=$row["montocuota"];
				}
				$li_totalpago=($ai_moncuo+$li_montocuota);
				$li_maxpago=($ai_sueper*0.3);
				if($li_totalpago>$li_maxpago)
				{
					$lb_valido=false;
					$this->io_mensajes->message("El monto de la Cuota es mayor al 30% del sueldo.");
				}
				$this->io_sql->free_result($rs_data);	
			}
		}
		return $lb_valido;
	}// end function uf_verificarsueldo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_cancelar_cuotas()
	{  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_cancelar_cuota
		//		   Access: public
		//	      Returns: lb_valido true si hizo el update correctamente false en caso contrario  
		//	  Description: Funcion que coloca como canceladas las cuotas del período actual
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 01/02/2006 								Fecha Última Modificación : 14/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
		$ld_fecdesper=$_SESSION["la_nomina"]["fecdesper"];
		$ld_fechasper=$_SESSION["la_nomina"]["fechasper"];
		$li_estcuo=1;/*- CUOTA CANCELADA O -*/
		$lb_valido=true;
		$ls_sql="UPDATE sno_prestamosperiodo ".
				"	SET estcuo=".$li_estcuo."".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				//"   AND percob='".$ls_peractnom."'".
				"   AND feciniper='".$ld_fecdesper."'".
				"   AND fecfinper='".$ld_fechasper."'";

	    $li_row=$this->io_sql->execute($ls_sql);
	    if($li_row===false)
	    {
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Prestamo Cuota MÉTODO->uf_cancelar_cuota ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
	    }
	   return $lb_valido;	
	 }// end function uf_cancelar_cuota
	 //-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_verificar_cuota_cobrada()
	{  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_cuota_cobrada
		//		   Access: public
		//	      Returns: lb_valido true si hizo el update correctamente false en caso contrario  
		//	  Description: Funcion que verifica que las cuotas sean cobradas en el cálculo de la nómina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 09/11/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
		$ld_fecdesper=$_SESSION["la_nomina"]["fecdesper"];
		$ld_fechasper=$_SESSION["la_nomina"]["fechasper"];
		$lb_valido=false;
		$ls_sql="SELECT sno_prestamos.codper ".
				"  FROM sno_prestamosperiodo, sno_prestamos ".
				" WHERE sno_prestamosperiodo.codemp='".$this->ls_codemp."' ".
				"   AND sno_prestamosperiodo.codnom='".$this->ls_codnom."'".
				//"   AND sno_prestamosperiodo.percob='".$ls_peractnom."'".
				"   AND sno_prestamosperiodo.feciniper='".$ld_fecdesper."'".
				"   AND sno_prestamosperiodo.fecfinper='".$ld_fechasper."'".
				"   AND sno_prestamos.stapre='1' ".  
				"   AND sno_prestamosperiodo.codemp = sno_prestamos.codemp ".
				"   AND sno_prestamosperiodo.codnom = sno_prestamos.codnom ".
				"   AND sno_prestamosperiodo.codper = sno_prestamos.codper ".
				" GROUP BY sno_prestamos.codper ";
	    $rs_data=$this->io_sql->select($ls_sql);
	    if($rs_data===false)
	    {
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Prestamo Cuota MÉTODO->uf_verificar_cuota_cobrada ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
	    }
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_sql="SELECT sno_prestamos.codper ".
						"  FROM sno_prestamosperiodo, sno_prestamos, sno_salida ".
						" WHERE sno_prestamosperiodo.codemp='".$this->ls_codemp."' ".
						"   AND sno_prestamosperiodo.codnom='".$this->ls_codnom."'".
						//"   AND sno_prestamosperiodo.percob='".$ls_peractnom."'".
						"   AND sno_prestamosperiodo.feciniper='".$ld_fecdesper."'".
						"   AND sno_prestamosperiodo.fecfinper='".$ld_fechasper."'".
						"   AND sno_salida.valsal <> 0 ".
						"   AND sno_prestamos.stapre='1' ".  
						"   AND sno_prestamosperiodo.codemp = sno_prestamos.codemp ".
						"   AND sno_prestamosperiodo.codnom = sno_prestamos.codnom ".
						"   AND sno_prestamosperiodo.codper = sno_prestamos.codper ".
						"   AND sno_prestamosperiodo.codemp = sno_salida.codemp ".
						"   AND sno_prestamosperiodo.codnom = sno_salida.codnom ".
						"   AND sno_prestamosperiodo.codper = sno_salida.codper ".
						//"   AND sno_prestamosperiodo.percob = sno_salida.codperi ".
						"   AND sno_prestamos.codconc = sno_salida.codconc ";
					$rs_data2=$this->io_sql->select($ls_sql);
					if($rs_data2===false)
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Prestamo Cuota MÉTODO->uf_verificar_cuota_cobrada ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
					}
					else
					{
						if($row=$this->io_sql->fetch_row($rs_data2))
						{
							$lb_valido=true;
						}
						else
						{
							$this->io_mensajes->message("ERROR-> Se creo un Prestamo y la cuota para este periodo no fué cobrada en el calculo de la nómina. Debe Reversar la nómina y volverla a calcular");
						}
					}
			}
			else
			{
				$lb_valido=true;
			}
		}
	   return $lb_valido;	
	 }// end function uf_verificar_cuota_cobrada
	 //-----------------------------------------------------------------------------------------------------------------------------------
}
?>