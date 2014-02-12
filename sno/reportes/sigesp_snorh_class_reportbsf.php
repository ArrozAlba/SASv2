<?php
class sigesp_snorh_class_reportbsf
{
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_snorh_class_reportbsf()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_snorh_class_reportbsf
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/06/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$this->io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($this->io_conexion);	
		$this->DS=new class_datastore();
		$this->DS_detalle=new class_datastore();
		require_once("../../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function sigesp_snorh_class_report
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
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
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codsis='".$as_sistema."'".
				"   AND seccion='".$as_seccion."'".
				"   AND entry='".$as_variable."'";
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
		return $ls_valor;
	}// end function uf_select_config
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_nombrenomina($as_codnom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_nombrenomina
		//		   Access: public
		//	    Arguments: as_codnom  // código de nómina
		//	      Returns: $ls_desnom variable buscado
		//	  Description: Función que obtiene la descripción de una nómina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 10/08/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_desnom="";
		$ls_sql="SELECT desnom ".
				"  FROM sno_nomina ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$as_codnom."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report Contable MÉTODO->uf_select_nombrenomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_desnom=$row["desnom"];
			}
			$this->io_sql->free_result($rs_data);		
		}
		return $ls_desnom;
	}// end function uf_select_nombrenomina
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
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
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codsis='".$as_sistema."'".
				"   AND seccion='".$as_seccion."'".
				"   AND entry='".$as_variable."'";		
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
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_listadopersonal_personal($as_codnomdes,$as_codnomhas,$as_codperdes,$as_codperhas,$as_activo,$as_egresado,
										 $as_causaegreso,$as_activono,$as_vacacionesno,$as_suspendidono,$as_egresadono,
										 $as_masculino,$as_femenino,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listadopersonal_personal
		//         Access: public (desde la clase sigesp_snorh_rpp_listadopersonal)  
		//	    Arguments: as_codnomdes // Código de la nómina donde se empieza a filtrar
		//	  			   as_codnomhas // Código de la nómina donde se termina de filtrar		  
		//	    		   as_codperdes // Código de personal donde se empieza a filtrar
		//	  			   as_codperhas // Código de personal donde se termina de filtrar		  
		//	  			   as_activo // Estatus Activo		  
		//	  			   as_egresado // Estatus Egresado
		//	  			   as_causaegreso // Causa del egreso
		//	  			   as_activono // Estatus Activo dentro de la nómina		  
		//	  			   as_vacacionesno // Estatus Vaciones dentro de la nómina
		//	  			   as_suspendidono // Estatus Suspendido dentro de la nómina
		//	  			   as_egresadono // Estatus Egresado dentro de la nómina
		//	  			   as_orden // Orden en que se quiere sacar el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/06/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		$lb_ok=false;
		if(!empty($as_codnomdes))
		{
			$ls_criterio= $ls_criterio." AND sno_personalnomina.codnom>='".$as_codnomdes."'";
			if(!empty($as_codnomhas))
			{
				$ls_criterio= $ls_criterio." AND sno_personalnomina.codnom<='".$as_codnomhas."'";
			}
			if(!empty($as_activono))
			{
				$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='1' ";
				$lb_ok=true;
			}
			if(!empty($as_vacacionesno))
			{
				if($lb_ok)
				{
					$ls_criterio= $ls_criterio." OR sno_personalnomina.staper='2' ";
				}
				else
				{
					$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='2' ";
					$lb_ok=true;
				}
			}
			if(!empty($as_egresadono))
			{
				if($lb_ok)
				{
					$ls_criterio= $ls_criterio." OR sno_personalnomina.staper='3' ";
				}
				else
				{
					$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='3' ";
					$lb_ok=true;
				}
			}
			if(!empty($as_suspendidono))
			{
				if($lb_ok)
				{
					$ls_criterio= $ls_criterio." OR sno_personalnomina.staper='4' ";
				}
				else
				{
					$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='4' ";
					$lb_ok=true;
				}
			}
			if($lb_ok)
			{
				$ls_criterio= $ls_criterio." )";
				$lb_ok=false;
			}
		}
		if(!empty($as_codperdes))
		{
			$ls_criterio= $ls_criterio." AND sno_personal.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio." AND sno_personal.codper<='".$as_codperhas."'";
		}
		if(!empty($as_activo))
		{
			$ls_criterio= $ls_criterio." AND (sno_personal.estper='1' ";
			$lb_ok=true;
		}
		if(!empty($as_egresado))
		{
			$ls_causaegreso="";
			if(!empty($as_causaegreso))
			{
				$ls_causaegreso=" AND sno_personal.cauegrper='".$as_causaegreso."'";
			}
			if($lb_ok)
			{
				$ls_criterio= $ls_criterio." OR (sno_personal.estper='3' ".$ls_causaegreso.") ";
			}
			else
			{
				$ls_criterio= $ls_criterio." AND (sno_personal.estper='3' ".$ls_causaegreso." ";
				$lb_ok=true;
			}
		}
		if($lb_ok)
		{
			$ls_criterio= $ls_criterio.")";
		}
		$lb_anterior=false;
		if(!empty($as_masculino))
		{
			$ls_criterio= $ls_criterio. " AND (sno_personal.sexper='M' ";
			$lb_anterior=true;
		}
		if(!empty($as_femenino))
		{
			if($lb_anterior)
			{
				$ls_criterio= $ls_criterio. " OR sno_personal.sexper='F' ";
			}
			else
			{
				$ls_criterio= $ls_criterio. " AND (sno_personal.sexper='F' ";
				$lb_anterior=true;
			}
		}
		if($lb_anterior)
		{
			$ls_criterio= $ls_criterio.")";
		}				


		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;
		}
		if((!empty($as_codnomdes))&&(!empty($as_codnomhas)))
		{ // si busco por nómina
			$ls_sql="SELECT sno_personal.codper,sno_personal.nomper,sno_personal.apeper,sno_personal.fecingper,sno_personal.estper, ".
					"		sno_personal.nivacaper, sno_profesion.despro, sno_personalnomina.staper AS estnom, sno_nomina.desnom,   ".
					"		sno_personalnomina.fecingper AS fecingnom, sno_personal.cedper, sno_personal.sexper, sno_personal.fecnacper, ".
					"		sno_nomina.racnom,  sno_personalnomina.sueper, sno_dedicacion.desded, sno_tipopersonal.destipper, ".
					"       (SELECT denasicar FROM sno_asignacioncargo ".
					"   	  WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
					"           AND sno_personalnomina.codemp = sno_asignacioncargo.codemp ".
					"		    AND sno_personalnomina.codnom = sno_asignacioncargo.codnom ".
					"           AND sno_personalnomina.codasicar = sno_asignacioncargo.codasicar) AS denasicar, ".
					"       (SELECT descar FROM sno_cargo ".
					"   	  WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
					"		    AND sno_personalnomina.codemp = sno_cargo.codemp ".
					"		    AND sno_personalnomina.codnom = sno_cargo.codnom ".
					"           AND sno_personalnomina.codcar = sno_cargo.codcar) AS descar ".
					"  FROM sno_personal, sno_profesion, sno_personalnomina, sno_nomina, sno_dedicacion, sno_tipopersonal ".
					" WHERE sno_personal.codemp = '".$this->ls_codemp."'".
					"   ".$ls_criterio.
					"   AND sno_personal.codemp = sno_profesion.codemp ".
					"	AND sno_personal.codpro = sno_profesion.codpro ".
					"   AND sno_personal.codemp = sno_personalnomina.codemp ".
					"	AND sno_personal.codper = sno_personalnomina.codper ".
					"   AND sno_personalnomina.codemp = sno_nomina.codemp ".
					"	AND sno_personalnomina.codnom = sno_nomina.codnom  ".
					"   AND sno_personalnomina.codemp = sno_dedicacion.codemp ".
					"	AND sno_personalnomina.codded = sno_dedicacion.codded  ".
					"   AND sno_personalnomina.codemp = sno_tipopersonal.codemp ".
					"	AND sno_personalnomina.codded = sno_tipopersonal.codded  ".
					"	AND sno_personalnomina.codtipper = sno_tipopersonal.codtipper  ".
					$ls_orden;
		}
		else
		{	// Si no busco por nómina
			$ls_sql="SELECT sno_personal.codper,sno_personal.nomper,sno_personal.apeper,sno_personal.fecingper,sno_personal.estper, ".
					"		sno_personal.nivacaper, sno_profesion.despro, '---' AS estnom, '---' AS desnom, '---' AS fecingnom, ".
					"		sno_personal.cedper, sno_personal.sexper, sno_personal.fecnacper, '0' AS racnom, '0' AS sueper, ".
					"		'---' AS desded, '---' AS destipper, '---' AS denasicar, '---' AS descar ".
					"  FROM sno_personal, sno_profesion".
					" WHERE sno_personal.codemp = '".$this->ls_codemp."'".
					"   ".$ls_criterio.
					"   AND sno_personal.codemp = sno_profesion.codemp ".
					"	AND sno_personal.codpro = sno_profesion.codpro ".
					$ls_orden;
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_listadopersonal_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_listadopersonal_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_comparado0506_programado($as_rango)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_comparado0506_programado
		//         Access: public (desde la clase sigesp_snorh_rpp_comparado0506)  
		//	    Arguments: as_rango // rango de meses a sumar
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de la programación de reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 19/01/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codrep,codded,codtipper,moneneaux AS monene,monfebaux AS monfeb,monmaraux AS monmar,monabraux AS monabr,".
				"		monmayaux AS monmay,monjunaux AS monjun,monjulaux AS monjul ,monagoaux AS monago,monsepaux AS monsep,".
				"		monoctaux AS monoct,monnovaux AS monnov,mondicaux AS mondic, ".
				"		carene,carfeb,carmar,carabr,carmay,carjun,carjul,carago,carsep,caroct,carnov,cardic, ".
				"		(SELECT sno_dedicacion.desded FROM  sno_dedicacion ".
				"	 	  WHERE sno_programacionreporte.codemp = sno_dedicacion.codemp ".
				"			AND sno_programacionreporte.codded = sno_dedicacion.codded) as desded, ".
				"		(SELECT sno_tipopersonal.destipper FROM  sno_tipopersonal ".
				"	 	  WHERE sno_programacionreporte.codemp = sno_tipopersonal.codemp ".
				"			AND sno_programacionreporte.codded = sno_tipopersonal.codded ".
				"			AND sno_programacionreporte.codtipper = sno_tipopersonal.codtipper) as destipper ".
				"  FROM sno_programacionreporte ".
				" WHERE codemp = '".$this->ls_codemp."'".
				"   AND codrep = '0506'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_comparado0506_programado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_comparado0506_programado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_comparado0506_real($as_rango,$as_codded,$as_codtipper,&$ai_cargoreal,&$ai_montoreal)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_comparado0506_real
		//         Access: public (desde la clase sigesp_snorh_rpp_comparado0506)  
		//	    Arguments: as_rango // rango de meses a sumar
		//	   			   as_codded // código de dedicación
		//	   			   as_codtipper // código de tipo de personal
		//	   			   ai_cargoreal // Cargo Real
		//	   			   ai_montoreal // Monto Real
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de la programación de reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/01/2007								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->io_sql=new class_sql($this->io_conexion);
		$lb_valido=true;
		$ls_criterio="";
		$ls_groupcargos="";
		$ls_groupmontos="";
		if($as_codtipper=="0000")
		{
			$ls_criterio=" AND sno_hpersonalnomina.codded='".$as_codded."'";
			$ls_groupcargos=" GROUP BY sno_hpersonalnomina.codper, sno_hpersonalnomina.codded, sno_hpersonalnomina.codtipper ";
			$ls_groupmontos=" GROUP BY sno_hpersonalnomina.codded ";
		}
		else
		{
			$ls_criterio=" AND sno_hpersonalnomina.codded='".$as_codded."'".
						 " AND sno_hpersonalnomina.codtipper='".$as_codtipper."'";
			$ls_groupcargos=" GROUP BY sno_hpersonalnomina.codper, sno_hpersonalnomina.codded, sno_hpersonalnomina.codtipper ";
			$ls_groupmontos=" GROUP BY sno_hpersonalnomina.codded, sno_hpersonalnomina.codtipper ";
		}

		$ls_sql="SELECT sno_hpersonalnomina.codper ".
				"  FROM sno_hpersonalnomina, sno_hperiodo, sno_hnomina ".
				" WHERE sno_hpersonalnomina.codemp = '".$this->ls_codemp."'".
				"   AND sno_hpersonalnomina.anocur = '".substr($_SESSION["la_empresa"]["periodo"],0,4)."'".
				"   AND sno_hnomina.espnom = 0 ".
				"   AND sno_hnomina.ctnom = 0 ".
				$ls_criterio.
				"   AND substr(sno_hperiodo.fecdesper,6,2) >= '".substr($as_rango,0,2)."'".
				"   AND substr(sno_hperiodo.fechasper,6,2) <= '".substr($as_rango,3,2)."'".
				"   AND sno_hnomina.codemp = sno_hperiodo.codemp ".
				"   AND sno_hnomina.codnom = sno_hperiodo.codnom ".
				"	AND sno_hnomina.anocurnom = sno_hperiodo.anocur ".
				"   AND sno_hnomina.peractnom = sno_hperiodo.codperi ".
				"   AND sno_hpersonalnomina.codemp = sno_hperiodo.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hperiodo.codnom ".
				"	AND sno_hpersonalnomina.anocur = sno_hperiodo.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hperiodo.codperi ".
				$ls_groupcargos;

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_comparado0506_real ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_cargoreal=$ai_cargoreal+1;
			}
			$this->io_sql->free_result($rs_data);
		}
		if($lb_valido)
		{
			$ls_sql="SELECT sum(sno_hsalida.valsalaux) as monto ".
					"  FROM sno_hpersonalnomina, sno_hsalida, sno_hperiodo ".
					" WHERE sno_hpersonalnomina.codemp = '".$this->ls_codemp."'".
					"   AND sno_hpersonalnomina.anocur = '".substr($_SESSION["la_empresa"]["periodo"],0,4)."'".
					$ls_criterio.
					"   AND substr(sno_hperiodo.fecdesper,6,2) >= '".substr($as_rango,0,2)."'".
					"   AND substr(sno_hperiodo.fechasper,6,2) <= '".substr($as_rango,3,2)."'".
					"   AND (sno_hsalida.tipsal = 'A' OR sno_hsalida.tipsal = 'V1' OR sno_hsalida.tipsal = 'W1')".
					"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
					"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
					"	AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
					"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
					"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
					"   AND sno_hpersonalnomina.codemp = sno_hperiodo.codemp ".
					"   AND sno_hpersonalnomina.codnom = sno_hperiodo.codnom ".
					"	AND sno_hpersonalnomina.anocur = sno_hperiodo.anocur ".
					"   AND sno_hpersonalnomina.codperi = sno_hperiodo.codperi ".
					$ls_groupmontos;
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_mensajes->message("CLASE->Report MÉTODO->uf_comparado0506_real ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
			}
			else
			{
				if($row=$this->io_sql->fetch_row($rs_data))
				{
					$ai_montoreal=round($row["monto"],2);
				}
				$this->io_sql->free_result($rs_data);
			}
		}		
		return $lb_valido;
	}// end function uf_comparado0506_real
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_comparado0506_gasto_programado($as_rango,$as_cuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_comparado0506_gasto_programado
		//         Access: public (desde la clase sigesp_snorh_rpp_comparado0506)  
		//	    Arguments: as_rango // rango de meses a sumar
		//	   			   as_cuenta // cuenta presupuestaria por el que se quiere filtrar
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del gasto programado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 24/01/2007								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->io_sql=new class_sql($this->io_conexion);
		$lb_valido=true;
		switch(substr($as_rango,0,2))
		{
			case "01":
				$ls_campo=" eneroaux ";
				break;
			case "02":
				$ls_campo=" febreroaux ";
				break;
			case "03":
				$ls_campo=" marzoaux ";
				break;
			case "04":
				$ls_campo=" abrilaux ";
				break;
			case "05":
				$ls_campo=" mayoaux ";
				break;
			case "06":
				$ls_campo=" junioaux ";
				break;
			case "07":
				$ls_campo=" julioaux ";
				break;
			case "08":
				$ls_campo=" agostoaux ";
				break;
			case "09":
				$ls_campo=" septiembreaux ";
				break;
			case "10":
				$ls_campo=" octubreaux ";
				break;
			case "11":
				$ls_campo=" noviembreaux ";
				break;
			case "12":
				$ls_campo=" diciembreaux ";
				break;
		}
		$ls_anocur=substr($_SESSION["la_empresa"]["periodo"],0,4);
		$ls_sql="SELECT ".$ls_anocur." AS anocur, spg_plantillareporte.spg_cuenta AS cuenta, ".
				"		 MAX(spg_plantillareporte.".$ls_campo.") AS programado, MAX(spg_cuentas.status) AS status ".
				"  FROM spg_plantillareporte, spg_cuentas ".
				" WHERE spg_plantillareporte.codemp = '".$this->ls_codemp."' ".
				"	AND spg_plantillareporte.codrep = '0506' ".
				"   AND spg_plantillareporte.spg_cuenta LIKE '".$as_cuenta."%' ".
				"   AND spg_plantillareporte.codemp = spg_cuentas.codemp  ".
				"   AND spg_plantillareporte.spg_cuenta = spg_cuentas.spg_cuenta ".
				" GROUP BY spg_plantillareporte.codemp, spg_plantillareporte.spg_cuenta ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_comparado0506_real ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);		
				$this->DS_detalle->group_by(array('0'=>'cuenta'),array('0'=>'programado'),'programado');
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_comparado0506_gasto_programado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_comparado0506_gasto_real($as_rango,$as_cuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_comparado0506_gasto_real
		//         Access: public (desde la clase sigesp_snorh_rpp_comparado0506)  
		//	    Arguments: as_rango // rango de meses a sumar
		//	   			   as_cuenta // cuenta presupuestaria por el que se quiere filtrar
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del gasto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 24/01/2007								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->io_sql=new class_sql($this->io_conexion);
		$lb_valido=true;
		$ls_anocur=substr($_SESSION["la_empresa"]["periodo"],0,4);
		$ls_sql="SELECT MAX(sno_hperiodo.anocur) AS anocur, SUM(sno_hsalida.valsalaux) AS total, sno_hconcepto.cueprecon AS cuenta ".
				"  FROM sno_hsalida, sno_hconcepto, sno_hperiodo ".
				" WHERE sno_hperiodo.codemp = '".$this->ls_codemp."' ".
				"	AND sno_hperiodo.anocur = '".$ls_anocur."' ".
				"   AND SUBSTR(sno_hperiodo.fecdesper,6,2)>='".substr($as_rango,0,2)."' ".
				"   AND SUBSTR(sno_hperiodo.fecdesper,6,2)<='".substr($as_rango,0,2)."' ".
				"   AND sno_hsalida.valsal <> 0 ".
				"   AND (sno_hsalida.tipsal = 'A' OR sno_hsalida.tipsal = 'V1' OR sno_hsalida.tipsal = 'W1')".
				"   AND sno_hconcepto.cueprecon LIKE '".$as_cuenta."%' ".
				"	AND sno_hconcepto.cueprecon <> '' ".
				"   AND sno_hperiodo.codemp = sno_hsalida.codemp ".
				"   AND sno_hperiodo.codnom = sno_hsalida.codnom ".
				"   AND sno_hperiodo.anocur = sno_hsalida.anocur ".
				"   AND sno_hperiodo.codperi = sno_hsalida.codperi ".
				"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				" GROUP BY sno_hconcepto.cueprecon ".
				" UNION ".
				"SELECT MAX(sno_hperiodo.anocur) AS anocur, SUM(sno_hsalida.valsal) AS total, sno_hconcepto.cueprepatcon AS cuenta ".
		 		"  FROM sno_hsalida, sno_hconcepto, sno_hperiodo ".
				" WHERE sno_hperiodo.codemp = '".$this->ls_codemp."' ".
				"	AND sno_hperiodo.anocur = '".$ls_anocur."' ".
				"   AND SUBSTR(sno_hperiodo.fecdesper,6,2)>='".substr($as_rango,0,2)."' ".
				"   AND SUBSTR(sno_hperiodo.fecdesper,6,2)<='".substr($as_rango,0,2)."' ".
				"   AND sno_hsalida.valsal <> 0 ".
				"   AND (sno_hsalida.tipsal = 'P2' OR sno_hsalida.tipsal = 'V4' OR sno_hsalida.tipsal = 'W4')".
				"   AND sno_hconcepto.cueprepatcon LIKE '".$as_cuenta."%' ".
				"	AND sno_hconcepto.cueprepatcon <> '' ".
				"   AND sno_hperiodo.codemp = sno_hsalida.codemp ".
				"   AND sno_hperiodo.codnom = sno_hsalida.codnom ".
				"   AND sno_hperiodo.anocur = sno_hsalida.anocur ".
				"   AND sno_hperiodo.codperi = sno_hsalida.codperi ".
				"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				" GROUP BY sno_hconcepto.cueprepatcon ".
				" ORDER BY cuenta ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_comparado0506_real ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);		
				$this->DS_detalle->group_by(array('0'=>'cuenta'),array('0'=>'total'),'total');
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_comparado0506_gasto_real
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_comparado0711_programado($as_rango)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_comparado0711_programado
		//         Access: public (desde la clase sigesp_snorh_rpp_comparado0711)  
		//	    Arguments: as_rango // rango de meses a sumar
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de la programación de reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 27/06/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;

		$ls_sql="SELECT codrep,codded,codtipper,moneneaux AS monene,monfebaux AS monfeb,monmaraux AS monmar,monabraux AS monabr,".
				"		monmayaux AS monmay,monjunaux AS monjun,monjulaux AS monjul,monagoaux AS monago,monsepaux AS monsep,".
				"		monoctaux AS monoct,monnovaux AS monnov,mondicaux AS mondic, ".
				"		carene,carfeb,carmar,carabr,carmay,carjun,carjul,carago,carsep,caroct,carnov,cardic, ".
				"		(SELECT sno_dedicacion.desded FROM  sno_dedicacion ".
				"	 	  WHERE sno_programacionreporte.codemp = sno_dedicacion.codemp ".
				"			AND sno_programacionreporte.codded = sno_dedicacion.codded) as desded, ".
				"		(SELECT sno_tipopersonal.destipper FROM  sno_tipopersonal ".
				"	 	  WHERE sno_programacionreporte.codemp = sno_tipopersonal.codemp ".
				"			AND sno_programacionreporte.codded = sno_tipopersonal.codded ".
				"			AND sno_programacionreporte.codtipper = sno_tipopersonal.codtipper) as destipper ".
				"  FROM sno_programacionreporte ".
				" WHERE codemp = '".$this->ls_codemp."'".
				"   AND codrep = '0711'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_comparado0711_programado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_comparado0711_programado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_comparado0711_real($as_rango,$as_codded,$as_codtipper,&$ai_cargoreal,&$ai_montoreal)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_comparado0711_real
		//         Access: public (desde la clase sigesp_snorh_rpp_comparado0711)  
		//	    Arguments: as_rango // rango de meses a sumar
		//	   			   as_codded // código de dedicación
		//	   			   as_codtipper // código de tipo de personal
		//	   			   ai_cargoreal // Cargo Real
		//	   			   ai_montoreal // Monto Real
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de la programación de reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 27/06/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->io_sql=new class_sql($this->io_conexion);
		$lb_valido=true;
		$ls_criterio="";
		$ls_groupcargos="";
		$ls_groupmontos="";
		if($as_codtipper=="0000")
		{
			$ls_criterio=" AND sno_hpersonalnomina.codded='".$as_codded."'";
			$ls_groupcargos=" GROUP BY sno_hpersonalnomina.codper, sno_hpersonalnomina.codded, sno_hpersonalnomina.codtipper ";
			$ls_groupmontos=" GROUP BY sno_hpersonalnomina.codded ";
		}
		else
		{
			$ls_criterio=" AND sno_hpersonalnomina.codded='".$as_codded."'".
						 " AND sno_hpersonalnomina.codtipper='".$as_codtipper."'";
			$ls_groupcargos=" GROUP BY sno_hpersonalnomina.codper, sno_hpersonalnomina.codded, sno_hpersonalnomina.codtipper ";
			$ls_groupmontos=" GROUP BY sno_hpersonalnomina.codded, sno_hpersonalnomina.codtipper ";
		}

		$ls_sql="SELECT sno_hpersonalnomina.codper ".
				"  FROM sno_hpersonalnomina, sno_hperiodo, sno_hnomina ".
				" WHERE sno_hpersonalnomina.codemp = '".$this->ls_codemp."'".
				"   AND sno_hpersonalnomina.anocur = '".substr($_SESSION["la_empresa"]["periodo"],0,4)."'".
				$ls_criterio.
				"   AND sno_hnomina.tipnom <> 7 ".
				"   AND sno_hnomina.espnom = 0 ".
				"   AND sno_hnomina.ctnom = 0 ".
				"   AND substr(sno_hperiodo.fecdesper,6,2) >= '".substr($as_rango,0,2)."'".
				"   AND substr(sno_hperiodo.fechasper,6,2) <= '".substr($as_rango,3,2)."'".
				"   AND sno_hnomina.codemp = sno_hperiodo.codemp ".
				"   AND sno_hnomina.codnom = sno_hperiodo.codnom ".
				"	AND sno_hnomina.anocurnom = sno_hperiodo.anocur ".
				"   AND sno_hnomina.peractnom = sno_hperiodo.codperi ".
				"   AND sno_hpersonalnomina.codemp = sno_hperiodo.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hperiodo.codnom ".
				"	AND sno_hpersonalnomina.anocur = sno_hperiodo.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hperiodo.codperi ".
				$ls_groupcargos;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_comparado0711_real ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_cargoreal=$ai_cargoreal+1;
			}
			$this->io_sql->free_result($rs_data);
		}
		if($lb_valido)
		{
			$ls_sql="SELECT sum(sno_hsalida.valsalaux) as monto ".
					"  FROM sno_hpersonalnomina, sno_hsalida, sno_hperiodo, sno_hnomina ".
					" WHERE sno_hpersonalnomina.codemp = '".$this->ls_codemp."'".
					"   AND sno_hpersonalnomina.anocur = '".substr($_SESSION["la_empresa"]["periodo"],0,4)."'".
					$ls_criterio.
					"   AND sno_hnomina.tipnom <> 7 ".
					"   AND substr(sno_hperiodo.fecdesper,6,2) >= '".substr($as_rango,0,2)."'".
					"   AND substr(sno_hperiodo.fechasper,6,2) <= '".substr($as_rango,3,2)."'".
					"   AND (sno_hsalida.tipsal = 'A' OR sno_hsalida.tipsal = 'V1' OR sno_hsalida.tipsal = 'W1')".
					"   AND sno_hnomina.codemp = sno_hperiodo.codemp ".
					"   AND sno_hnomina.codnom = sno_hperiodo.codnom ".
					"	AND sno_hnomina.anocurnom = sno_hperiodo.anocur ".
					"   AND sno_hnomina.peractnom = sno_hperiodo.codperi ".
					"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
					"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
					"	AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
					"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
					"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
					"   AND sno_hpersonalnomina.codemp = sno_hperiodo.codemp ".
					"   AND sno_hpersonalnomina.codnom = sno_hperiodo.codnom ".
					"	AND sno_hpersonalnomina.anocur = sno_hperiodo.anocur ".
					"   AND sno_hpersonalnomina.codperi = sno_hperiodo.codperi ".
					$ls_groupmontos;
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_mensajes->message("CLASE->Report MÉTODO->uf_comparado0711_real ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
			}
			else
			{
				if($row=$this->io_sql->fetch_row($rs_data))
				{
					$ai_montoreal=$row["monto"];
				}
				$this->io_sql->free_result($rs_data);
			}
		}		
		return $lb_valido;
	}// end function uf_comparado0711_real
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_comparado0712_programado($as_rango)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_comparado0712_programado
		//         Access: public (desde la clase sigesp_snorh_rpp_comparado0712)  
		//	    Arguments: as_rango // rango de meses a sumar
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de la programación de reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 29/06/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;

		$ls_sql="SELECT codrep,codded,codtipper,moneneaux AS monene,monfebaux AS monfeb,monmaraux AS monmar,monabraux AS monabr,".
				"		monmayaux AS monmay,monjunaux AS monjun,monjulaux AS monjul,monagoaux AS monago,monsepaux AS monsep,".
				"		monoctaux AS monoct,monnovaux AS monnov,mondicaux AS mondic, ".
				"		carene,carfeb,carmar,carabr,carmay,carjun,carjul,carago,carsep,caroct,carnov,cardic ".
				"  FROM sno_programacionreporte ".
				" WHERE codemp = '".$this->ls_codemp."'".
				"   AND codrep = '0712'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_comparado0712_programado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_comparado0712_programado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_comparado0712_real($as_rango,$as_catjub,$as_conjub,&$ai_cargoreal,&$ai_montoreal)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_comparado0712_real
		//         Access: public (desde la clase sigesp_snorh_rpp_comparado0712)  
		//	    Arguments: as_rango // rango de meses a sumar
		//	   			   as_catjub // Categoría de Jubilación
		//	   			   as_conjub // Condición de Jubilación
		//	   			   ai_cargoreal // Cargo Real
		//	   			   ai_montoreal // Monto Real
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de la programación de reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 29/06/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->io_sql=new class_sql($this->io_conexion);
		$lb_valido=true;
		$ls_criterio="";
		$ls_groupcargos="";
		$ls_groupmontos="";
		if($as_conjub=="0000")
		{
			$ls_criterio=" AND sno_hpersonalnomina.catjub='".$as_catjub."'";
			$ls_groupcargos=" GROUP BY sno_hpersonalnomina.codper, sno_hpersonalnomina.catjub, sno_hpersonalnomina.conjub ";
			$ls_groupmontos=" GROUP BY sno_hpersonalnomina.catjub ";
		}
		else
		{
			$ls_criterio=" AND sno_hpersonalnomina.catjub='".$as_catjub."'".
						 " AND sno_hpersonalnomina.conjub='".$as_conjub."'";
			$ls_groupcargos=" GROUP BY sno_hpersonalnomina.codper, sno_hpersonalnomina.catjub, sno_hpersonalnomina.conjub ";
			$ls_groupmontos=" GROUP BY sno_hpersonalnomina.catjub, sno_hpersonalnomina.conjub ";
		}
		$ls_sql="SELECT sno_hpersonalnomina.codper ".
				"  FROM sno_hpersonalnomina, sno_hperiodo, sno_hnomina ".
				" WHERE sno_hpersonalnomina.codemp = '".$this->ls_codemp."'".
				"   AND sno_hpersonalnomina.anocur = '".substr($_SESSION["la_empresa"]["periodo"],0,4)."'".
				$ls_criterio.
				"   AND sno_hnomina.tipnom = 7 ".
				"   AND sno_hnomina.espnom = 0 ".
				"   AND sno_hnomina.ctnom = 0 ".
				"   AND substr(sno_hperiodo.fecdesper,6,2) >= '".substr($as_rango,0,2)."'".
				"   AND substr(sno_hperiodo.fechasper,6,2) <= '".substr($as_rango,3,2)."'".
				"   AND sno_hnomina.codemp = sno_hperiodo.codemp ".
				"   AND sno_hnomina.codnom = sno_hperiodo.codnom ".
				"	AND sno_hnomina.anocurnom = sno_hperiodo.anocur ".
				"   AND sno_hnomina.peractnom = sno_hperiodo.codperi ".
				"   AND sno_hpersonalnomina.codemp = sno_hperiodo.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hperiodo.codnom ".
				"	AND sno_hpersonalnomina.anocur = sno_hperiodo.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hperiodo.codperi ".
				$ls_groupcargos;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_comparado0712_real ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_cargoreal=$ai_cargoreal+1;
			}
			$this->io_sql->free_result($rs_data);
		}
		if($lb_valido)
		{
			$ls_sql="SELECT sum(sno_hsalida.valsalaux) as monto ".
					"  FROM sno_hpersonalnomina, sno_hsalida, sno_hperiodo, sno_hnomina ".
					" WHERE sno_hpersonalnomina.codemp = '".$this->ls_codemp."'".
					"   AND sno_hpersonalnomina.anocur = '".substr($_SESSION["la_empresa"]["periodo"],0,4)."'".
					$ls_criterio.
					"   AND sno_hnomina.tipnom = 7 ".
					"   AND (sno_hsalida.tipsal = 'A' OR sno_hsalida.tipsal = 'V1' OR sno_hsalida.tipsal = 'W1') ".
					"   AND substr(sno_hperiodo.fecdesper,6,2) >= '".substr($as_rango,0,2)."'".
					"   AND substr(sno_hperiodo.fechasper,6,2) <= '".substr($as_rango,3,2)."'".
					"   AND sno_hnomina.codemp = sno_hperiodo.codemp ".
					"   AND sno_hnomina.codnom = sno_hperiodo.codnom ".
					"	AND sno_hnomina.anocurnom = sno_hperiodo.anocur ".
					"   AND sno_hnomina.peractnom = sno_hperiodo.codperi ".
					"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
					"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
					"	AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
					"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
					"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
					"   AND sno_hpersonalnomina.codemp = sno_hperiodo.codemp ".
					"   AND sno_hpersonalnomina.codnom = sno_hperiodo.codnom ".
					"	AND sno_hpersonalnomina.anocur = sno_hperiodo.anocur ".
					"   AND sno_hpersonalnomina.codperi = sno_hperiodo.codperi ".
					$ls_groupmontos;
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_mensajes->message("CLASE->Report MÉTODO->uf_comparado0712_real ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
			}
			else
			{
				if($row=$this->io_sql->fetch_row($rs_data))
				{
					$ai_montoreal=$row["monto"];
				}
				$this->io_sql->free_result($rs_data);
			}
		}		
		return $lb_valido;
	}// end function uf_comparado0712_real
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_prestacionantiguedad_personal($as_codnom,$as_anocurper,$as_mescurper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_prestacionantiguedad_personal
		//         Access: public (desde la clase sigesp_snorh_rpp_prestacionantiguedad)  
		//	    Arguments: as_codnom // Código de Nómina
		//	  			   as_anocurper // Año en curso
		//	  			   as_mescurper // Mes en curso		  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del fideicomiso del  personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/07/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_mes=str_pad($as_mescurper,2,"0",0);
		$ls_sql="SELECT sno_personal.cedper,sno_personal.nomper,sno_personal.apeper, sno_fideiperiodo.sueintperaux AS sueintper, ".
				"		sno_fideiperiodo.bonvacperaux AS bonvacper, sno_fideiperiodo.bonfinperaux AS bonfinper, sno_fideiperiodo.apoperaux AS apoper, sno_fideiperiodo.diafid, ".
				"		sno_fideiperiodo.diaadi ".
				"  FROM sno_personal, sno_fideiperiodo ".
				" WHERE sno_fideiperiodo.codemp = '".$this->ls_codemp."' ".
				"   AND sno_fideiperiodo.codnom = '".$as_codnom."' ".
				"   AND sno_fideiperiodo.anocurper = '".$as_anocurper."' ".
				"   AND sno_fideiperiodo.mescurper = '".$li_mes."' ".
				"   AND sno_personal.codemp = sno_fideiperiodo.codemp ".
				"	AND sno_personal.codper = sno_fideiperiodo.codper ".
				" ORDER BY sno_personal.codper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_prestacionantiguedad_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_prestacionantiguedad_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_constanciatrabajo_constancia($as_codcont,$as_codnom,$as_codperdes,$as_codperhas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_constanciatrabajo_constancia
		//         Access: public (desde la clase sigesp_snorh_rpp_constanciatrabajo)  
		//	    Arguments: as_codcont // Código de la Constancia
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de la constancia
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/07/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio=" sno_personalnomina.codnom = '".$as_codnom."' ";
		if(!empty($as_codperdes))
		{
			$ls_criterio= $ls_criterio." AND sno_personalnomina.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio." AND sno_personalnomina.codper<='".$as_codperhas."'";
		}
		$ls_sql="SELECT codcont, descont, concont, tamletcont, intlincont, marinfcont, marsupcont, titcont, piepagcont, ".
				"		tamletpiecont, arcrtfcont ".
				"  FROM sno_constanciatrabajo ".
				" WHERE codemp = '".$this->ls_codemp."' ".
				"   AND codcont = '".$as_codcont."' ".
				"   AND codemp IN (SELECT codemp FROM sno_personalnomina ".
				"					WHERE ".$ls_criterio.")";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_constanciatrabajo_constancia ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_constanciatrabajo_constancia
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_constanciatrabajo_personal($as_codnom,$ai_rac,$as_codperdes,$as_codperhas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_constanciatrabajo_personal
		//         Access: public (desde la clase sigesp_snorh_rpp_constanciatrabajo)  
		//	    Arguments: as_codnom // Código de la Nómina
		//	   			   ai_rac // Código de personal donde se empieza a filtrar
		//	   			   as_codperdes // Código de personal donde se empieza a filtrar
		//	  			   as_codperhas // Código de personal donde se termina de filtrar		  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/07/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		if(!empty($as_codperdes))
		{
			$ls_criterio= " AND sno_personal.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio." AND sno_personal.codper<='".$as_codperhas."'";
		}
		if($ai_rac=="1") // Utiliza RAC
		{
			$ls_descar="       (SELECT denasicar FROM sno_asignacioncargo ".
					   "   	     WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
					   "           AND sno_personalnomina.codnom='".$as_codnom."' ".
					   "           AND sno_personalnomina.codemp = sno_asignacioncargo.codemp ".
					   "		   AND sno_personalnomina.codnom = sno_asignacioncargo.codnom ".
				       "           AND sno_personalnomina.codasicar = sno_asignacioncargo.codasicar) as descar ";
		}
		else // No utiliza RAC
		{
			$ls_descar="       (SELECT descar FROM sno_cargo ".
					   "   	     WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
					   "           AND sno_personalnomina.codnom='".$as_codnom."' ".
					   "		   AND sno_personalnomina.codemp = sno_cargo.codemp ".
					   "		   AND sno_personalnomina.codnom = sno_cargo.codnom ".
				       "           AND sno_personalnomina.codcar = sno_cargo.codcar) as descar ";
		}
		$ls_sql="SELECT sno_personal.codper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personal.fecingper, ".
				"		sno_personal.dirper, sno_personal.fecnacper, sno_personal.edocivper, sno_personal.nacper, sno_personal.telhabper, ".
				"		sno_personal.telmovper, sno_personalnomina.sueper, ".$ls_descar.", sno_personalnomina.horper, ".
				"		sno_personalnomina.sueintperaux AS sueintper, sno_personalnomina.sueproperaux AS sueproper, sno_personal.fecegrper, ".
				"		sno_unidadadmin.desuniadm, sno_dedicacion.desded, sno_tipopersonal.destipper, sno_nomina.tipnom  ".
				"  FROM sno_personal, sno_personalnomina, sno_unidadadmin, sno_dedicacion, sno_tipopersonal, sno_nomina ".
				" WHERE sno_personal.codemp = '".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom = '".$as_codnom."' ".
				$ls_criterio.
				"   AND sno_personal.codemp = sno_personalnomina.codemp ".
				"   AND sno_personal.codper = sno_personalnomina.codper ".
				"	AND sno_personalnomina.codemp = sno_nomina.codemp ".			
				"	AND sno_personalnomina.codnom = sno_nomina.codnom ".			
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				"   AND sno_personalnomina.codemp = sno_dedicacion.codemp ".
				"   AND sno_personalnomina.codded = sno_dedicacion.codded ".
				"   AND sno_personalnomina.codemp = sno_tipopersonal.codemp ".
				"   AND sno_personalnomina.codded = sno_tipopersonal.codded ".
				"   AND sno_personalnomina.codtipper = sno_tipopersonal.codtipper ".
				" GROUP BY sno_personal.codper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personal.fecingper, ".
				"		   sno_personal.dirper, sno_personal.fecnacper, sno_personal.edocivper, sno_personal.nacper, sno_personal.telhabper,".
				"		   sno_personal.telmovper, sno_personalnomina.sueper, sno_personalnomina.horper, sno_personalnomina.sueintper, ".
				"		   sno_personalnomina.sueproper, sno_unidadadmin.desuniadm, sno_dedicacion.desded, sno_tipopersonal.destipper, ".
				"		   sno_personalnomina.codemp,sno_personalnomina.codnom, sno_personalnomina.codcar, sno_personalnomina.codasicar, ".
				"		   sno_personal.fecegrper, sno_nomina.tipnom	".
				" ORDER BY sno_personal.cedper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_constanciatrabajo_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_constanciatrabajo_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_constanciatrabajo_integralpromedio_mensual($as_codnom,$as_codper,$as_mes,$as_anocurnom,&$ai_sueintper,&$ai_sueproper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_constanciatrabajo_integralpromedio_mensual
		//         Access: public (desde la clase sigesp_snorh_rpp_constanciatrabajo)  
		//	    Arguments: as_codnom // Código de la Nómina
		//	   			   as_codper // Código de personal 
		//	  			   as_mes // Mes donde se va a buscar el sueldo integral
		//	  			   as_anocurnom // Año en curso de la Nómina
		//	  			   ai_sueintper // Sueldo Integral Mensual 
		//	  			   ai_sueproper // Sueldo Promedio Mensual
		//	      Returns: lb_valido True si se no ocrrio ningún error
		//    Description: función que busca la información del sueldo integral ni del sueldo promedio del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 24/10/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_sueintper=0;
		$ai_sueproper=0;
		$ls_sql="SELECT SUM(sueintper) AS sueintper, SUM(sueproper) AS sueproper ".
				"  FROM sno_hpersonalnomina, sno_hperiodo , sno_hnomina ".
				" WHERE sno_hpersonalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_hpersonalnomina.anocur='".$as_anocurnom."' ".
				"   AND sno_hpersonalnomina.codper='".$as_codper."' ".
				"   AND SUBSTR(sno_hperiodo.fecdesper,6,2)='".$as_mes."' ".
				"   AND sno_hnomina.espnom='0' ".
				"   AND sno_hpersonalnomina.codemp= sno_hperiodo.codemp".
				"   AND sno_hpersonalnomina.codnom= sno_hperiodo.codnom".
				"   AND sno_hpersonalnomina.anocur= sno_hperiodo.anocur".
				"   AND sno_hpersonalnomina.codperi= sno_hperiodo.codperi".
				"   AND sno_hnomina.codemp= sno_hperiodo.codemp ".
				"   AND sno_hnomina.codnom= sno_hperiodo.codnom ".
				"   AND sno_hnomina.anocurnom= sno_hperiodo.anocur".
				"   AND sno_hnomina.peractnom= sno_hperiodo.codperi ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_constanciatrabajo_integralpromedio_mensual ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_sueintper=$row["sueintper"];
				$ai_sueproper=$row["sueproper"];
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_constanciatrabajo_integralpromedio_mensual
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_aportepatronal_personal($as_codnomdes,$as_codnomhas,$as_anodes,$as_mesdes,$as_anohas,$as_meshas,
									    $as_perdes,$as_perhas,$as_codconc,$as_conceptocero,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_aportepatronal_personal
		//		   Access: public (desde la clase sigesp_snorh_rpp_aportepatronal)  
		//	    Arguments: as_codnomdes // Código nómina desde
		//	  			   as_codnomhas // Código nómina hasta
		//	    		   as_anodes // Año en curso Desde
		//	  			   as_mesdes // mes Desde
		//	    		   as_anohas // Año en curso Hasta
		//	  			   as_meshas // mes Hasta
		//	    		   as_perdes // Período desde
		//	  			   as_perhas // Período hasta
		//	    		   as_codconc // Código del concepto del que se desea busca el personal
		//				   as_conceptocero // Conceptos en cero
		//	  			   as_orden // orden por medio del cual se desea que salga el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que tienen asociado el concepto	de tipo aporte patronal 
		//				   en las nóminas seleccionadas
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 10/07/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		if(!empty($as_codnomdes))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.codnom>='".$as_codnomdes."' ";
		}
		if(!empty($as_codnomhas))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.codnom<='".$as_codnomhas."' ";
		}
		if(!empty($as_anodes))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.anocur>='".$as_anodes."' ";
		}
		if(!empty($as_anohas))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.anocur<='".$as_anohas."' ";
		}
		if(!empty($as_perdes))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.codperi>='".$as_perdes."' ";
		}
		if(!empty($as_perhas))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.codperi<='".$as_perhas."' ";
		}
		if(!empty($as_codconc))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.codconc='".$as_codconc."' ";
		}
		if(!empty($as_conceptocero))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.valsal<>0 ";
		}
		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;

			case "4": // Ordena por Cédula de personal
				$ls_orden="ORDER BY sno_personal.cedper ";
				break;
		}
		$ls_sql="SELECT sno_personal.codper,sno_personal.cedper, sno_personal.apeper, sno_personal.nomper,".
				"       (SELECT SUM(valsalaux) ".
				"		   FROM sno_hsalida ".
				"   	  WHERE (sno_hsalida.tipsal='P1' OR sno_hsalida.tipsal='V3' OR sno_hsalida.tipsal='W3' OR sno_hsalida.tipsal='Q1') ".
				$ls_criterio.
				"           AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   		AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   		AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   		AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   		AND sno_hpersonalnomina.codper = sno_hsalida.codper) as personal, ".
				"       (SELECT SUM(valsalaux) ".
				"		   FROM sno_hsalida ".
				"   	  WHERE (sno_hsalida.tipsal='P2' OR sno_hsalida.tipsal='V4' OR sno_hsalida.tipsal='W4' OR sno_hsalida.tipsal='Q2') ".
				$ls_criterio.
				"           AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   		AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   		AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   		AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   		AND sno_hpersonalnomina.codper = sno_hsalida.codper) as patron ".
				"  FROM sno_personal, sno_hpersonalnomina, sno_hsalida ".
				" WHERE sno_hpersonalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_hpersonalnomina.codnom>='".$as_codnomdes."' ".
				"   AND sno_hpersonalnomina.codnom<='".$as_codnomhas."' ".
				"   AND sno_hpersonalnomina.anocur>='".$as_anodes."' ".
				"   AND sno_hpersonalnomina.anocur<='".$as_anohas."' ".
				"   AND sno_hpersonalnomina.codperi>='".$as_perdes."' ".
				"   AND sno_hpersonalnomina.codperi<='".$as_perhas."' ".
				"   AND sno_hpersonalnomina.staper='1' ".
				$ls_criterio.
				"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
				"   AND sno_personal.codemp = sno_hpersonalnomina.codemp ".
				"   AND sno_personal.codper = sno_hpersonalnomina.codper ".
				" GROUP BY sno_hpersonalnomina.codemp, sno_hpersonalnomina.codnom, sno_hpersonalnomina.codper, sno_personal.codper,".
				" 		   sno_hpersonalnomina.anocur, sno_hpersonalnomina.codperi, sno_personal.cedper, sno_personal.apeper, sno_personal.nomper	".
				"   ".$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_aportepatronal_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);			
				$this->DS->group_by(array('0'=>'codper'),array('0'=>'personal','1'=>'patron'),'codper');
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_aportepatronal_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_ipasme_afiliado($as_codperdes,$as_codperhas,$as_tiptra,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_ipasme_afiliado
		//         Access: public (desde la clase sigesp_snorh_rpp_ipasme_afiliado)  
		//	    Arguments: as_codperdes // Código de personal donde se empieza a filtrar
		//	  			   as_codperhas // Código de personal donde se termina de filtrar		  
		//	  			   as_tiptra // Tipo de Transacción  
		//	  			   as_orden // Orden en que se quiere sacar el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 20/07/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		$lb_ok=false;
		if(!empty($as_codperdes))
		{
			$ls_criterio= " AND sno_ipasme_afiliado.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio." AND sno_ipasme_afiliado.codper<='".$as_codperhas."'";
		}
		if(!empty($as_tiptra))
		{
			$ls_criterio= $ls_criterio." AND sno_ipasme_afiliado.tiptraafi='".$as_tiptra."'";
		}
		switch($as_orden)
		{
			case "1": // Ordena por cédula de personal
				$ls_orden="ORDER BY sno_personal.cedper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;
		}
		$ls_sql="SELECT sno_personal.codper, sno_personal.cedper, sno_personal.nomper,sno_personal.apeper, ".
				"		sno_personal.fecingper, sno_ipasme_afiliado.tiptraafi, sno_ipasme_afiliado.tipafiafi ".
				"  FROM sno_personal, sno_ipasme_afiliado ".
				" WHERE sno_personal.codemp = '".$this->ls_codemp."'".
				"   ".$ls_criterio." ".
				"   AND sno_personal.codemp = sno_ipasme_afiliado.codemp ".
				"	AND sno_personal.codper = sno_ipasme_afiliado.codper ".
				$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_ipasme_afiliado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_ipasme_afiliado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_ipasme_beneficiario_personal($as_codperdes,$as_codperhas,$as_tiptra,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_ipasme_beneficiario_personal
		//         Access: public (desde la clase sigesp_snorh_rpp_ipasme_beneficiario)  
		//	    Arguments: as_codperdes // Código de personal donde se empieza a filtrar
		//	  			   as_codperhas // Código de personal donde se termina de filtrar		  
		//	  			   as_tiptra // Tipo de Transacción  
		//	  			   as_orden // Orden en que se quiere sacar el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 20/07/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		$lb_ok=false;
		if(!empty($as_codperdes))
		{
			$ls_criterio= " AND sno_ipasme_afiliado.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio." AND sno_ipasme_afiliado.codper<='".$as_codperhas."'";
		}
		if(!empty($as_tiptra))
		{
			$ls_criterio= $ls_criterio." AND sno_ipasme_beneficiario.tiptraben='".$as_tiptra."'";
		}
		switch($as_orden)
		{
			case "1": // Ordena por cédula de personal
				$ls_orden="ORDER BY sno_personal.cedper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;
		}
		$ls_sql="SELECT sno_personal.codper, sno_personal.cedper, sno_personal.nomper,sno_personal.apeper ".
				"  FROM sno_personal, sno_ipasme_afiliado, sno_ipasme_beneficiario ".
				" WHERE sno_personal.codemp = '".$this->ls_codemp."'".
				"   ".$ls_criterio." ".
				"   AND sno_personal.codemp = sno_ipasme_afiliado.codemp ".
				"	AND sno_personal.codper = sno_ipasme_afiliado.codper ".
				"	AND sno_ipasme_afiliado.codemp = sno_ipasme_beneficiario.codemp ".
				"	AND sno_ipasme_afiliado.codper = sno_ipasme_beneficiario.codper ".
				" GROUP BY sno_personal.codper, sno_personal.cedper, sno_personal.nomper,sno_personal.apeper ".
				$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_ipasme_beneficiario_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_ipasme_beneficiario_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_ipasme_beneficiario_bene($as_codper,$as_tiptra)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_ipasme_beneficiario_bene
		//         Access: public (desde la clase sigesp_snorh_rpp_ipasme_beneficiario)  
		//	    Arguments: as_codper // Código de personal
		//	  			   as_tiptra // Tipo de Transacción  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del Beneficario del personal 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 20/07/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		if(!empty($as_tiptra))
		{
			$ls_criterio= $ls_criterio." AND tiptraben='".$as_tiptra."'";
		}
		$ls_sql="SELECT codben, cedben, prinomben, priapeben, tiptraben, codpare  ".
				"  FROM sno_ipasme_beneficiario ".
				" WHERE codemp = '".$this->ls_codemp."'".
				"   AND codper = '".$as_codper."'".
				"   ".$ls_criterio." ".
				" ORDER BY codben ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_ipasme_beneficiario_bene ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_ipasme_beneficiario_bene
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_ipasme_dependencia($as_coddepdes,$as_coddephas,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_ipasme_dependencia
		//         Access: public (desde la clase sigesp_snorh_rpp_ipasme_dependencia)  
		//	    Arguments: as_coddepdes // Código de Dependencia donde se empieza a filtrar
		//	  			   as_coddephas // Código de Dependencia donde se termina de filtrar		  
		//	  			   as_orden // Orden en que se quiere sacar el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las Dependencias
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 20/07/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		$lb_ok=false;
		if(!empty($as_coddepdes))
		{
			$ls_criterio= " AND coddep>='".$as_coddepdes."'";
		}
		if(!empty($as_coddephas))
		{
			$ls_criterio= $ls_criterio." AND coddep<='".$as_coddephas."'";
		}
		switch($as_orden)
		{
			case "1": // Ordena por código de Dependencia
				$ls_orden="ORDER BY coddep ";
				break;

			case "2": // Ordena por Descripción de Dependencia
				$ls_orden="ORDER BY desdep ";
				break;
		}
		$ls_sql="SELECT coddep,desdep ".
				"  FROM sno_ipasme_dependencias ".
				" WHERE codemp = '".$this->ls_codemp."'".
				"   ".$ls_criterio." ".
				$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_ipasme_dependencia ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_ipasme_dependencia
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_ipasme_aporte_concepto($as_codnomdes,$as_codnomhas,$as_ano,$as_mes,$as_conceptocero)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_ipasme_aporte_concepto
		//		   Access: public (desde la clase sigesp_snorh_rpp_ipasme_aporte)  
		//	    Arguments: as_codnomdes // Código nómina desde
		//	  			   as_codnomhas // Código nómina hasta
		//	    		   as_ano // Año en curso
		//	  			   as_mes // mes
		//				   as_conceptocero // Conceptos en cero
		//	  			   as_orden // orden por medio del cual se desea que salga el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los conceptos de aportes al ipasme 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/07/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		$ls_codconc_ahorro=trim($this->uf_select_config("SNO","NOMINA","COD CONCEPTO AHORRO IPAS","XXXXXXXXXX","C"));
		$ls_codconc_servicio=trim($this->uf_select_config("SNO","NOMINA","COD CONCEPTO SERVICIO IPAS","XXXXXXXXXX","C"));
		if(!empty($as_codnomdes))
		{
			$ls_criterio = $ls_criterio." AND sno_hconcepto.codnom>='".$as_codnomdes."' ";
		}
		if(!empty($as_codnomhas))
		{
			$ls_criterio = $ls_criterio." AND sno_hconcepto.codnom<='".$as_codnomhas."' ";
		}
		if(!empty($as_ano))
		{
			$ls_criterio = $ls_criterio." AND sno_hconcepto.anocur='".$as_ano."' ";
		}
		if(!empty($as_mes))
		{
			$ls_criterio = $ls_criterio." AND SUBSTR(sno_hperiodo.fecdesper,6,2)='".$as_mes."' ";
		}
		if(!empty($as_conceptocero))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.valsal<>0 ";
		}
		$ls_criterio = $ls_criterio." AND (sno_hsalida.codconc='".$ls_codconc_ahorro."' OR sno_hsalida.codconc='".$ls_codconc_servicio."')";
									
		$ls_sql="SELECT sno_hconcepto.codconc, sno_hconcepto.nomcon ".
				"  FROM sno_personal, sno_ipasme_afiliado, sno_hpersonalnomina, sno_hconcepto, sno_hsalida, sno_hperiodo ".
				" WHERE sno_hconcepto.codemp='".$this->ls_codemp."' ".
				$ls_criterio.
				"	AND sno_hconcepto.codemp = sno_hsalida.codemp ".
				"   AND sno_hconcepto.anocur = sno_hsalida.anocur ".
				"   AND sno_hconcepto.codperi = sno_hsalida.codperi ".
				"   AND sno_hconcepto.codnom = sno_hsalida.codnom ".
				"   AND sno_hconcepto.codconc = sno_hsalida.codconc ".
				"   AND sno_hconcepto.codemp = sno_hperiodo.codemp ".
				"   AND sno_hconcepto.anocur = sno_hperiodo.anocur ".
				"   AND sno_hconcepto.codperi = sno_hperiodo.codperi ".
				"	AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"	AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"	AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
				"	AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"	AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   AND sno_personal.codemp = sno_hpersonalnomina.codemp ".
				"   AND sno_personal.codper = sno_hpersonalnomina.codper ".
				"   AND sno_personal.codemp = sno_ipasme_afiliado.codemp ".
				"   AND sno_personal.codper = sno_ipasme_afiliado.codper ".
				" GROUP BY sno_hconcepto.codconc, sno_hconcepto.nomcon ".
				" ORDER BY sno_hconcepto.codconc ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_ipasme_aporte_concepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);			
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_ipasme_aporte_concepto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_ipasme_aporte_personal($as_codconc,$as_codnomdes,$as_codnomhas,$as_ano,$as_mes,$as_conceptocero,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_ipasme_aporte_personal
		//		   Access: public (desde la clase sigesp_snorh_rpp_ipasme_aporte)  
		//	    Arguments: as_codconc // Código del concepto del que se desea busca el personal
		//	    		   as_codnomdes // Código nómina desde
		//	  			   as_codnomhas // Código nómina hasta
		//	    		   as_ano // Año en curso
		//	  			   as_mes // mes
		//				   as_conceptocero // Conceptos en cero
		//	  			   as_orden // orden por medio del cual se desea que salga el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que tienen asociado el concepto	de tipo aporte patronal 
		//				   en las nóminas seleccionadas
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/07/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->io_sql=new class_sql($this->io_conexion);
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		if(!empty($as_codnomdes))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.codnom>='".$as_codnomdes."' ";
		}
		if(!empty($as_codnomhas))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.codnom<='".$as_codnomhas."' ";
		}
		if(!empty($as_ano))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.anocur='".$as_ano."' ";
		}
		if(!empty($as_mes))
		{
			$ls_criterio = $ls_criterio." AND SUBSTR(sno_hperiodo.fecdesper,6,2)='".$as_mes."' ";
		}
		if(!empty($as_codconc))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.codconc='".$as_codconc."' ";
		}
		if(!empty($as_conceptocero))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.valsal<>0 ";
		}
		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;

			case "4": // Ordena por Cédula de personal
				$ls_orden="ORDER BY sno_personal.cedper ";
				break;
		}
		$ls_sql="SELECT sno_personal.cedper, sno_personal.apeper, sno_personal.nomper,".
				"       (SELECT SUM(valsalaux) ".
				"		   FROM sno_hsalida ".
				"   	  WHERE (sno_hsalida.tipsal='P1' OR sno_hsalida.tipsal='V3' OR sno_hsalida.tipsal='W3' OR sno_hsalida.tipsal='Q1') ".
				$ls_criterio.
				"           AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   		AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   		AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   		AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   		AND sno_hpersonalnomina.codper = sno_hsalida.codper) as personal, ".
				"       (SELECT SUM(valsalaux) ".
				"		   FROM sno_hsalida ".
				"   	  WHERE (sno_hsalida.tipsal='P2' OR sno_hsalida.tipsal='V4' OR sno_hsalida.tipsal='W4' OR sno_hsalida.tipsal='Q2') ".
				$ls_criterio.
				" 			AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   		AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   		AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   		AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   		AND sno_hpersonalnomina.codper = sno_hsalida.codper) as patron ".
				"  FROM sno_personal, sno_ipasme_afiliado, sno_hpersonalnomina, sno_hsalida, sno_hperiodo ".
				" WHERE sno_hpersonalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_hpersonalnomina.codnom>='".$as_codnomdes."' ".
				"   AND sno_hpersonalnomina.codnom<='".$as_codnomhas."' ".
				"   AND sno_hpersonalnomina.anocur='".$as_ano."' ".
				"   AND sno_hpersonalnomina.staper='1' ".
				$ls_criterio.
				"	AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"	AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"	AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
				"	AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"	AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"	AND sno_hsalida.codemp = sno_hperiodo.codemp ".
				"	AND sno_hsalida.codnom = sno_hperiodo.codnom ".
				"	AND sno_hsalida.anocur = sno_hperiodo.anocur ".
				"	AND sno_hsalida.codperi  = sno_hperiodo.codperi ".
				"   AND sno_personal.codemp = sno_hpersonalnomina.codemp ".
				"   AND sno_personal.codper = sno_hpersonalnomina.codper ".
				"   AND sno_personal.codemp = sno_ipasme_afiliado.codemp ".
				"   AND sno_personal.codper = sno_ipasme_afiliado.codper ".
				" GROUP BY sno_personal.cedper ".
				"   ".$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_ipasme_aporte_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);			
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_ipasme_aporte_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_ipasme_cobranza_concepto($as_codnomdes,$as_codnomhas,$as_ano,$as_mes)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_ipasme_cobranza_concepto
		//		   Access: public (desde la clase sigesp_snorh_rpp_ipasme_cobranza)  
		//	    Arguments: as_codnomdes // Código nómina desde
		//	  			   as_codnomhas // Código nómina hasta
		//	    		   as_ano // Año en curso
		//	  			   as_mes // mes
		//	  			   as_orden // orden por medio del cual se desea que salga el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los conceptos de cobranzas al ipasme 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/07/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		$ls_hip_especial=trim($this->uf_select_config("SNO","NOMINA","COD CONCEPTO HIPOTECARIO ESPECIAL IPAS","XXXXXXXXXX","C"));
		$ls_hip_ampliacion=trim($this->uf_select_config("SNO","NOMINA","COD CONCEPTO HIPOTECARIO AMLIACION IPAS","XXXXXXXXXX","C"));
		$ls_hip_construccion=trim($this->uf_select_config("SNO","NOMINA","COD CONCEPTO HIPOTECARIO CONSTRUCCION IPAS","XXXXXXXXXX","C"));
		$ls_hip_hipoteca=trim($this->uf_select_config("SNO","NOMINA","COD CONCEPTO HIPOTECARIO HIPOTECA IPAS","XXXXXXXXXX","C"));
		$ls_hip_lph=trim($this->uf_select_config("SNO","NOMINA","COD CONCEPTO HIPOTECARIO LPH IPAS","XXXXXXXXXX","C"));
		$ls_hip_vivienda=trim($this->uf_select_config("SNO","NOMINA","COD CONCEPTO HIPOTECARIO VIVIENDA IPAS","XXXXXXXXXX","C"));
		$ls_personal=trim($this->uf_select_config("SNO","NOMINA","COD CONCEPTO PERSONAL IPAS","XXXXXXXXXX","C"));
		$ls_turistico=trim($this->uf_select_config("SNO","NOMINA","COD CONCEPTO TURISTICOS IPAS","XXXXXXXXXX","C"));
		$ls_proveeduria=trim($this->uf_select_config("SNO","NOMINA","COD CONCEPTO PROVEEDURIA IPAS","XXXXXXXXXX","C"));
		$ls_asistencial=trim($this->uf_select_config("SNO","NOMINA","COD CONCEPTO ASISTENCIALES IPAS","XXXXXXXXXX","C"));
		$ls_vehiculo=trim($this->uf_select_config("SNO","NOMINA","COD CONCEPTO VEHICULOS IPAS","XXXXXXXXXX","C"));
		$ls_comercial=trim($this->uf_select_config("SNO","NOMINA","COD CONCEPTO COMERCIALES IPAS","XXXXXXXXXX","C"));		
		if(!empty($as_codnomdes))
		{
			$ls_criterio = $ls_criterio." AND sno_hconcepto.codnom>='".$as_codnomdes."' ";
		}
		if(!empty($as_codnomhas))
		{
			$ls_criterio = $ls_criterio." AND sno_hconcepto.codnom<='".$as_codnomhas."' ";
		}
		if(!empty($as_ano))
		{
			$ls_criterio = $ls_criterio." AND sno_hconcepto.anocur='".$as_ano."' ";
		}
		if(!empty($as_mes))
		{
			$ls_criterio = $ls_criterio." AND SUBSTR(sno_hperiodo.fecdesper,6,2)='".$as_mes."' ";
		}
		$ls_criterio = $ls_criterio." AND sno_hsalida.valsal<>0 ";
		$ls_criterio = $ls_criterio." AND (sno_hsalida.codconc='".$ls_hip_especial."' OR sno_hsalida.codconc='".$ls_hip_ampliacion."'".
									"  OR  sno_hsalida.codconc='".$ls_hip_construccion."' OR sno_hsalida.codconc='".$ls_hip_hipoteca."'".
									"  OR  sno_hsalida.codconc='".$ls_hip_lph."' OR sno_hsalida.codconc='".$ls_hip_vivienda."'".
									"  OR  sno_hsalida.codconc='".$ls_personal."' OR sno_hsalida.codconc='".$ls_turistico."'".
									"  OR  sno_hsalida.codconc='".$ls_proveeduria."' OR sno_hsalida.codconc='".$ls_asistencial."'".
									"  OR  sno_hsalida.codconc='".$ls_vehiculo."' OR sno_hsalida.codconc='".$ls_comercial."')";
		$ls_sql="SELECT sno_hconcepto.codconc, sno_hconcepto.nomcon ".
				"  FROM sno_hconcepto, sno_hsalida, sno_hperiodo, sno_ipasme_afiliado  ".
				" WHERE sno_hconcepto.codemp='".$this->ls_codemp."' ".
				$ls_criterio.
				"   AND sno_hconcepto.codemp = sno_hperiodo.codemp ".
				"   AND sno_hconcepto.anocur = sno_hperiodo.anocur ".
				"   AND sno_hconcepto.codperi = sno_hperiodo.codperi ".
				"   AND sno_hconcepto.codemp = sno_hsalida.codemp ".
				"   AND sno_hconcepto.anocur = sno_hsalida.anocur ".
				"   AND sno_hconcepto.codperi = sno_hsalida.codperi ".
				"   AND sno_hconcepto.codnom = sno_hsalida.codnom ".
				"   AND sno_hconcepto.codconc = sno_hsalida.codconc ".
				"   AND sno_hsalida.codemp = sno_ipasme_afiliado.codemp ".
				"   AND sno_hsalida.codper = sno_ipasme_afiliado.codper ".
				" GROUP BY sno_hconcepto.codconc, sno_hconcepto.nomcon ".
				" ORDER BY sno_hconcepto.codconc ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_ipasme_cobranza_concepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);			
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_ipasme_cobranza_concepto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_ipasme_cobranza_personal($as_codconc,$as_codnomdes,$as_codnomhas,$as_ano,$as_mes,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_ipasme_cobranza_personal
		//		   Access: public (desde la clase sigesp_snorh_rpp_ipasme_cobranza)  
		//	    Arguments: as_codconc // Código del concepto del que se desea busca el personal
		//	    		   as_codnomdes // Código nómina desde
		//	  			   as_codnomhas // Código nómina hasta
		//	    		   as_ano // Año en curso
		//	  			   as_mes // mes
		//	  			   as_orden // orden por medio del cual se desea que salga el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que tienen asociado el concepto	de cobranza 
		//				   en las nóminas seleccionadas
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/07/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		if(!empty($as_codnomdes))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.codnom>='".$as_codnomdes."' ";
		}
		if(!empty($as_codnomhas))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.codnom<='".$as_codnomhas."' ";
		}
		if(!empty($as_ano))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.anocur='".$as_ano."' ";
		}
		if(!empty($as_mes))
		{
			$ls_criterio = $ls_criterio." AND SUBSTR(sno_hperiodo.fecdesper,6,2)='".$as_mes."' ";
		}
		if(!empty($as_codconc))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.codconc='".$as_codconc."' ";
		}
		$ls_criterio = $ls_criterio." AND sno_hsalida.valsal<>0 ";
		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;

			case "4": // Ordena por Cédula de personal
				$ls_orden="ORDER BY sno_personal.cedper ";
				break;
		}
		$ls_sql="SELECT sno_personal.cedper, sno_personal.apeper, sno_personal.nomper, SUM(sno_hsalida.valsalaux) as valsal ".
				"  FROM sno_personal, sno_ipasme_afiliado, sno_hpersonalnomina, sno_hsalida, sno_hperiodo ".
				" WHERE sno_hpersonalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_hpersonalnomina.codnom>='".$as_codnomdes."' ".
				"   AND sno_hpersonalnomina.codnom<='".$as_codnomhas."' ".
				"   AND sno_hpersonalnomina.anocur='".$as_ano."' ".
				"   AND sno_hpersonalnomina.staper='1' ".
				$ls_criterio.
				"	AND sno_hsalida.codemp = sno_hperiodo.codemp ".
				"	AND sno_hsalida.codnom = sno_hperiodo.codnom ".
				"	AND sno_hsalida.anocur = sno_hperiodo.anocur ".
				"	AND sno_hsalida.codperi  = sno_hperiodo.codperi ".
				"	AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"	AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"	AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
				"	AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"	AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   AND sno_personal.codemp = sno_hpersonalnomina.codemp ".
				"   AND sno_personal.codper = sno_hpersonalnomina.codper ".
				"   AND sno_personal.codemp = sno_ipasme_afiliado.codemp ".
				"   AND sno_personal.codper = sno_ipasme_afiliado.codper ".
				" GROUP BY sno_personal.cedper ".
				"   ".$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_ipasme_cobranza_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);			
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_ipasme_cobranza_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_sane_ingreso($as_codperdes,$as_codperhas,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_sane_ingreso
		//         Access: public (desde la clase sigesp_snorh_rpp_listadopersonal)  
		//	    Arguments: as_codperdes // Código de personal donde se empieza a filtrar
		//	  			   as_codperhas // Código de personal donde se termina de filtrar		  
		//	  			   as_orden // Orden en que se quiere sacar el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 26/07/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		if(!empty($as_codperdes))
		{
			$ls_criterio= " AND sno_personal.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio." AND sno_personal.codper<='".$as_codperhas."'";
		}
		$ls_criterio= $ls_criterio." AND sno_personal.estper='1'";

		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;
		}
		$ls_sql="SELECT sno_personal.codper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personal.fecnacper, sno_personal.fecingper, ".
				"		(SELECT SUM(sueperaux) ".
				"		   FROM sno_personalnomina, sno_nomina ".
				"		  WHERE (sno_personalnomina.staper = '1' OR sno_personalnomina.staper = '2') ".
				"			AND sno_personalnomina.codemp = sno_personal.codemp ".
				"			AND sno_personalnomina.codper = sno_personal.codper ".
				"			AND sno_personalnomina.codemp = sno_nomina.codemp ".
				"			AND sno_personalnomina.codnom = sno_nomina.codnom ".
				"			AND sno_nomina.espnom = '0' ) as sueldo, ".
				"		(SELECT COUNT(codper) ".
				"		   FROM sno_personalnomina, sno_nomina ".
				"		  WHERE (sno_personalnomina.staper = '1' OR sno_personalnomina.staper = '2') ".
				"			AND sno_personalnomina.codemp = sno_personal.codemp ".
				"			AND sno_personalnomina.codper = sno_personal.codper ".
				"			AND sno_personalnomina.codemp = sno_nomina.codemp ".
				"			AND sno_personalnomina.codnom = sno_nomina.codnom ".
				"			AND sno_nomina.espnom = '0' ) as total ".
				"  FROM sno_personal ".
				" WHERE sno_personal.codemp = '".$this->ls_codemp."'".
				"   ".$ls_criterio." ".
				$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_sane_ingreso ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_sane_ingreso
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_sane_retiro($as_codperdes,$as_codperhas,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_sane_retiro
		//         Access: public (desde la clase sigesp_snorh_r_sane_retiro)  
		//	    Arguments: as_codperdes // Código de personal donde se empieza a filtrar
		//	  			   as_codperhas // Código de personal donde se termina de filtrar		  
		//	  			   as_orden // Orden en que se quiere sacar el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 28/07/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		if(!empty($as_codperdes))
		{
			$ls_criterio= " AND codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio." AND codper<='".$as_codperhas."'";
		}
		$ls_criterio= $ls_criterio." AND estper='3'";

		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;
		}
		$ls_sql="SELECT codper, cedper, nomper, apeper, fecegrper, cauegrper ".
				"  FROM sno_personal ".
				" WHERE codemp = '".$this->ls_codemp."'".
				"   ".$ls_criterio." ".
				$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_sane_retiro ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_sane_retiro
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_sane_salario($as_codperdes,$as_codperhas,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_sane_salario
		//         Access: public (desde la clase sigesp_snorh_rpp_sane_salario)  
		//	    Arguments: as_codperdes // Código de personal donde se empieza a filtrar
		//	  			   as_codperhas // Código de personal donde se termina de filtrar		  
		//	  			   as_orden // Orden en que se quiere sacar el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 28/07/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		if(!empty($as_codperdes))
		{
			$ls_criterio= " AND sno_personal.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio." AND sno_personal.codper<='".$as_codperhas."'";
		}
		$ls_criterio= $ls_criterio." AND sno_personal.estper='1'";

		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;
		}
		$ls_sql="SELECT sno_personal.codper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, ".
				"		(SELECT SUM(sueperaux) ".
				"		   FROM sno_personalnomina, sno_nomina ".
				"		  WHERE (sno_personalnomina.staper = '1' OR sno_personalnomina.staper = '2') ".
				"			AND sno_personalnomina.codemp = sno_personal.codemp ".
				"			AND sno_personalnomina.codper = sno_personal.codper ".
				"			AND sno_personalnomina.codemp = sno_nomina.codemp ".
				"			AND sno_personalnomina.codnom = sno_nomina.codnom ".
				"			AND sno_nomina.espnom = '0' ) as sueldo, ".
				"		(SELECT COUNT(codper) ".
				"		   FROM sno_personalnomina, sno_nomina ".
				"		  WHERE (sno_personalnomina.staper = '1' OR sno_personalnomina.staper = '2') ".
				"			AND sno_personalnomina.codemp = sno_personal.codemp ".
				"			AND sno_personalnomina.codper = sno_personal.codper ".
				"			AND sno_personalnomina.codemp = sno_nomina.codemp ".
				"			AND sno_personalnomina.codnom = sno_nomina.codnom ".
				"			AND sno_nomina.espnom = '0' ) as total ".
				"  FROM sno_personal ".
				" WHERE sno_personal.codemp = '".$this->ls_codemp."'".
				"   ".$ls_criterio." ".
				$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_sane_salario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_sane_salario
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_sane_centromedico($as_codperdes,$as_codperhas,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_sane_centromedico
		//         Access: public (desde la clase sigesp_snorh_rpp_listadopersonal)  
		//	    Arguments: as_codperdes // Código de personal donde se empieza a filtrar
		//	  			   as_codperhas // Código de personal donde se termina de filtrar		  
		//	  			   as_orden // Orden en que se quiere sacar el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 29/07/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		if(!empty($as_codperdes))
		{
			$ls_criterio= " AND sno_personal.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio." AND sno_personal.codper<='".$as_codperhas."'";
		}
		$ls_criterio= $ls_criterio." AND sno_personal.estper='1'";

		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;
		}
		$ls_sql="SELECT codper, cedper, nomper, apeper, cenmedper ".
				"  FROM sno_personal ".
				" WHERE sno_personal.codemp = '".$this->ls_codemp."'".
				"   ".$ls_criterio." ".
				$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_sane_centromedico ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_sane_centromedico
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_sane_modificacion($as_codperdes,$as_codperhas,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_sane_modificacion
		//         Access: public (desde la clase sigesp_snorh_rpp_sane_modificacion)  
		//	    Arguments: as_codperdes // Código de personal donde se empieza a filtrar
		//	  			   as_codperhas // Código de personal donde se termina de filtrar		  
		//	  			   as_orden // Orden en que se quiere sacar el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 29/07/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		if(!empty($as_codperdes))
		{
			$ls_criterio= " AND sno_personal.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio." AND sno_personal.codper<='".$as_codperhas."'";
		}
		$ls_criterio= $ls_criterio." AND sno_personal.estper='1'";

		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;
		}
		$ls_sql="SELECT codper, nacper, cedper, nomper, apeper, fecnacper, sexper ".
				"  FROM sno_personal ".
				" WHERE sno_personal.codemp = '".$this->ls_codemp."'".
				"   ".$ls_criterio." ".
				$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_sane_modificacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_sane_modificacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_sane_reposos($as_codperdes,$as_codperhas,$ad_fecdes,$ad_fechas,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_sane_reposos
		//         Access: public (desde la clase sigesp_snorh_rpp_sane_reposos)  
		//	    Arguments: as_codperdes // Código de personal donde se empieza a filtrar
		//	  			   as_codperhas // Código de personal donde se termina de filtrar		  
		//	    		   ad_fecdes // fecha Desde del reposo
		//	  			   ad_fechas // Fecha Hasta del Reposo		  
		//	  			   as_orden // Orden en que se quiere sacar el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal que tiene reposo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 31/07/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		$ad_fecdes=$this->io_funciones->uf_convertirdatetobd($ad_fecdes);
		$ad_fechas=$this->io_funciones->uf_convertirdatetobd($ad_fechas);
		if(!empty($as_codperdes))
		{
			$ls_criterio= " AND sno_personal.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio." AND sno_personal.codper<='".$as_codperhas."'";
		}
		if(!empty($ad_fecdes))
		{
			$ls_criterio= " AND sno_permiso.feciniper>='".$ad_fecdes."'";
		}
		if(!empty($ad_fechas))
		{
			$ls_criterio= $ls_criterio." AND sno_permiso.feciniper<='".$ad_fechas."'";
		}
		$ls_criterio= $ls_criterio." AND sno_personal.estper='1'";

		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;
		}
		$ls_sql="SELECT sno_personal.codper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_permiso.feciniper, sno_permiso.fecfinper ".
				"  FROM sno_personal, sno_permiso ".
				" WHERE sno_personal.codemp = '".$this->ls_codemp."'".
				"	AND sno_permiso.tipper = 2 ".
				"   ".$ls_criterio." ".
				"   AND sno_personal.codemp = sno_permiso.codemp ".
				"   AND sno_personal.codper = sno_permiso.codper ".
				$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_sane_reposos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_sane_reposos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_sane_permisos($as_codperdes,$as_codperhas,$ad_fecdes,$ad_fechas,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_sane_permisos
		//         Access: public (desde la clase sigesp_snorh_rpp_sane_reposos)  
		//	    Arguments: as_codperdes // Código de personal donde se empieza a filtrar
		//	  			   as_codperhas // Código de personal donde se termina de filtrar		  
		//	    		   ad_fecdes // fecha Desde del reposo
		//	  			   ad_fechas // Fecha Hasta del Reposo		  
		//	  			   as_orden // Orden en que se quiere sacar el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal que tiene reposo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/08/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		$ad_fecdes=$this->io_funciones->uf_convertirdatetobd($ad_fecdes);
		$ad_fechas=$this->io_funciones->uf_convertirdatetobd($ad_fechas);
		if(!empty($as_codperdes))
		{
			$ls_criterio= " AND sno_personal.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio." AND sno_personal.codper<='".$as_codperhas."'";
		}
		if(!empty($ad_fecdes))
		{
			$ls_criterio= " AND sno_permiso.feciniper>='".$ad_fecdes."'";
		}
		if(!empty($ad_fechas))
		{
			$ls_criterio= $ls_criterio." AND sno_permiso.feciniper<='".$ad_fechas."'";
		}
		$ls_criterio= $ls_criterio." AND sno_personal.estper='1'";

		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;
		}
		$ls_sql="SELECT sno_personal.codper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_permiso.feciniper, sno_permiso.fecfinper ".
				"  FROM sno_personal, sno_permiso ".
				" WHERE sno_personal.codemp = '".$this->ls_codemp."'".
				"	AND sno_permiso.remper = 0 ".
				"   ".$ls_criterio." ".
				"   AND sno_personal.codemp = sno_permiso.codemp ".
				"	AND sno_personal.codper = sno_permiso.codper ".
				$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_sane_permisos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_sane_permisos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_retencionislr_personal($as_codnomdes,$as_codnomhas,$as_ano,$as_mes,$as_conceptocero,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_retencionislr_personal
		//         Access: public (desde la clase sigesp_snorh_rpp_retencion_islr)  
		//	    Arguments: as_codnomdes // Código de Nómina donde se empieza a filtrar
		//	  			   as_codnomhas // Código de nómina donde se termina de filtrar		  
		//	  			   as_ano // Año 
		//	  			   as_mes // Mes
		//	  			   as_conceptocero // Si el concepto es Cero
		//	  			   as_orden // Orden en que se quiere sacar el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 04/08/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_concepto="";
		$ls_orden="";
		$lb_ok=false;
		if(!empty($as_codnomdes))
		{
			$ls_criterio= " AND sno_hperiodo.codnom>='".$as_codnomdes."'";
		}
		if(!empty($as_codnomhas))
		{
			$ls_criterio= $ls_criterio." AND sno_hperiodo.codnom<='".$as_codnomhas."'";
		}
		if(!empty($as_conceptocero))
		{
			$ls_concepto= $ls_concepto." AND sno_hsalida.valsal<>0";
		}
		switch($as_orden)
		{
			case "1": // Ordena por código de personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;
				
			case "4": // Ordena por cédula de personal
				$ls_orden="ORDER BY sno_personal.cedper ";
				break;
		}
		$ls_sql="SELECT sno_personal.codper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personalisr.porisr, ".
		        "	  (SELECT SUM(valsalaux) FROM sno_hsalida, sno_hconcepto ".
		        "		WHERE (sno_hsalida.tipsal = 'A' OR sno_hsalida.tipsal = 'V1' OR sno_hsalida.tipsal = 'W1' ".
		        "		   OR  sno_hsalida.tipsal = 'D' OR sno_hsalida.tipsal = 'V2' OR sno_hsalida.tipsal = 'W2' ".
		        "		   OR  sno_hsalida.tipsal = 'P1' OR sno_hsalida.tipsal = 'V3' OR sno_hsalida.tipsal = 'W3') ".
		        "		  AND sno_hconcepto.aplarccon = 1 ".
		        $ls_concepto.
		        "		  AND sno_hsalida.codemp = sno_hconcepto.codemp ".
		        "		  AND sno_hsalida.anocur = sno_hconcepto.anocur ".
		        "		  AND sno_hsalida.codperi = sno_hconcepto.codperi ".
		        "		  AND sno_hsalida.codnom = sno_hconcepto.codnom ".
		        "		  AND sno_hsalida.codconc = sno_hconcepto.codconc ".
		        "		  AND sno_hsalida.codemp = sno_hpersonalnomina.codemp ".
		        "		  AND sno_hsalida.anocur = sno_hpersonalnomina.anocur ".
		        "		  AND sno_hsalida.codperi = sno_hpersonalnomina.codperi ".
		        "		  AND sno_hsalida.codnom = sno_hpersonalnomina.codnom ".
		        "		  AND sno_hsalida.codper = sno_hpersonalnomina.codper ".
		        "		  AND sno_hsalida.codemp  = sno_hperiodo.codemp ".
		        "		  AND sno_hsalida.anocur  = sno_hperiodo.anocur ".
		        "		  AND sno_hsalida.codperi  = sno_hperiodo.codperi ".
		        "		  AND sno_hsalida.codnom  = sno_hperiodo.codnom ".
		        "		GROUP BY sno_hsalida.codper ) AS arc, ".
		        "	  (SELECT SUM(valsalaux) FROM sno_hsalida, sno_hconcepto ".
		        "		WHERE (sno_hsalida.tipsal = 'A' OR sno_hsalida.tipsal = 'V1' OR sno_hsalida.tipsal = 'W1' ".
		        "		   OR  sno_hsalida.tipsal = 'D' OR sno_hsalida.tipsal = 'V2' OR sno_hsalida.tipsal = 'W2' ".
		        "		   OR  sno_hsalida.tipsal = 'P1' OR sno_hsalida.tipsal = 'V3' OR sno_hsalida.tipsal = 'W3') ".
		        $ls_concepto.
		        "		  AND sno_hconcepto.aplisrcon = 1 ".
		        "		  AND sno_hsalida.codemp = sno_hconcepto.codemp ".
		        "		  AND sno_hsalida.anocur = sno_hconcepto.anocur ".
		        "		  AND sno_hsalida.codperi = sno_hconcepto.codperi ".
		        "		  AND sno_hsalida.codnom = sno_hconcepto.codnom ".
		        "		  AND sno_hsalida.codconc = sno_hconcepto.codconc ".
		        "		  AND sno_hsalida.codemp = sno_hpersonalnomina.codemp ".
		        "		  AND sno_hsalida.anocur = sno_hpersonalnomina.anocur ".
		        "		  AND sno_hsalida.codperi = sno_hpersonalnomina.codperi ".
		        "		  AND sno_hsalida.codnom = sno_hpersonalnomina.codnom ".
		        "		  AND sno_hsalida.codper = sno_hpersonalnomina.codper ".
		        "		  AND sno_hsalida.codemp  = sno_hperiodo.codemp ".
		        "		  AND sno_hsalida.anocur  = sno_hperiodo.anocur ".
		        "		  AND sno_hsalida.codperi  = sno_hperiodo.codperi ".
		        "		  AND sno_hsalida.codnom  = sno_hperiodo.codnom ".
		        "		GROUP BY sno_hsalida.codper ) AS islr ".
		        "  FROM sno_personal, sno_personalisr, sno_hpersonalnomina, sno_hperiodo ".
		        " WHERE sno_personal.codemp = '".$this->ls_codemp."'".
		        "   AND sno_hperiodo.anocur = '".$as_ano."' ".
		        "   AND SUBSTR(sno_hperiodo.fecdesper,6,2) = '".$as_mes."' ".
		        "   AND SUBSTR(sno_hperiodo.fecdesper,1,4) = '".$as_ano."' ".
		        "   AND sno_personalisr.codisr = '".$as_mes."' ".
				"   ".$ls_criterio." ".
				"   AND sno_personal.codemp = sno_personalisr.codemp ".
		        "   AND sno_personal.codper = sno_personalisr.codper ".
		        "   AND sno_personal.codemp = sno_hpersonalnomina.codemp ".
		        "   AND sno_personal.codper = sno_hpersonalnomina.codper ".
		        "   AND sno_hpersonalnomina.codemp = sno_hperiodo.codemp ".
		        "   AND sno_hpersonalnomina.codnom = sno_hperiodo.codnom ".
		        "   AND sno_hpersonalnomina.anocur = sno_hperiodo.anocur ".
		        "   AND sno_hpersonalnomina.codperi = sno_hperiodo.codperi ".
				$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_retencionislr_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
				$this->DS->group_by(array('0'=>'codper'),array('0'=>'arc','1'=>'islr'),'arc');
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_retencionislr_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_retencionarc_personal($aa_nominas,$ai_total,$as_ano,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_retencionarc_personal
		//         Access: public (desde la clase sigesp_snorh_rpp_retencion_arc)  
		//	    Arguments: aa_nominas // Arreglo de Nóminas por el cual se debe filtrar
		//	  			   ai_total // total de Nómina por el cual se va a filtrar	  
		//	  			   as_ano // Año 
		//	  			   as_orden // Orden en que se quiere sacar el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 04/08/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio=" AND (";
		$ls_orden="";
		$lb_ok=false;
		for($li_i=1;$li_i<=$ai_total;$li_i++)
		{
			$ls_codnom=$aa_nominas[$li_i];
			$ls_criterio= $ls_criterio." sno_hpersonalnomina.codnom='".$ls_codnom."' ";
			if($li_i<$ai_total)
			{
				$ls_criterio= $ls_criterio." OR ";
			}
		}
		$ls_criterio=$ls_criterio." )";
		switch($as_orden)
		{
			case "1": // Ordena por código de personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;
				
			case "4": // Ordena por cédula de personal
				$ls_orden="ORDER BY sno_personal.cedper ";
				break;
		}
		$ls_sql="SELECT sno_personal.codper, sno_personal.nacper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper ".
				"  FROM sno_personal, sno_personalisr, sno_hpersonalnomina, sno_hperiodo ".
				" WHERE sno_personal.codemp = '".$this->ls_codemp."' ".
				"   AND sno_hperiodo.anocur = '".$as_ano."' ".
		        "   AND SUBSTR(sno_hperiodo.fecdesper,1,4) = '".$as_ano."' ".
				"   ".$ls_criterio." ".
				"   AND sno_hpersonalnomina.codemp = sno_hperiodo.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hperiodo.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hperiodo.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hperiodo.codperi ".
				"   AND sno_personal.codemp = sno_personalisr.codemp ".
				"   AND sno_personal.codper = sno_personalisr.codper ".
				"   AND sno_personal.codemp = sno_hpersonalnomina.codemp ".
				"   AND sno_personal.codper = sno_hpersonalnomina.codper ".
		        " GROUP BY sno_personal.codper, sno_personal.nacper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper ".
				$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_retencionarc_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_retencionarc_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_retencionarc_meses($as_codper,$aa_nominas,$ai_total,$as_ano)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_retencionarc_meses
		//         Access: public (desde la clase sigesp_snorh_rpp_retencion_islr)  
		//	    Arguments: as_codper // Código de Personal
		//	    		   aa_nominas // Arreglo de Nóminas por el cual se debe filtrar
		//	  			   ai_total // total de Nómina por el cual se va a filtrar	  	  
		//	  			   as_ano // Año 
		//	  			   as_orden // Orden en que se quiere sacar el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 04/08/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio=" AND (";
		$ls_concepto="";
		$ls_orden="";
		$lb_ok=false;
		for($li_i=1;$li_i<=$ai_total;$li_i++)
		{
			$ls_codnom=$aa_nominas[$li_i];
			$ls_criterio= $ls_criterio." sno_hsalida.codnom='".$ls_codnom."' ";
			if($li_i<$ai_total)
			{
				$ls_criterio= $ls_criterio." OR ";
			}
		}
		$ls_criterio=$ls_criterio." )";

		if(!empty($as_conceptocero))
		{
			$ls_concepto= $ls_concepto." AND sno_hsalida.valsal<>0";
		}
		$ls_sql="SELECT sno_personalisr.codper, sno_personalisr.codisr, sno_personalisr.porisr, sno_hsalida.anocur, ".
				"       (SELECT SUM(valsalaux) ".
				"          FROM sno_hsalida, sno_hconcepto, sno_hperiodo ".
				"         WHERE sno_hsalida.codemp = '".$this->ls_codemp."' ".
				"           AND sno_hsalida.codper = '".$as_codper."' ".
				"           AND sno_hperiodo.anocur = '".$as_ano."' ".
		        "   		AND SUBSTR(sno_hperiodo.fecdesper,1,4) = '".$as_ano."' ".
				"           AND sno_personalisr.codisr = SUBSTR(sno_hperiodo.fecdesper,6,2) ".
				"           AND sno_hconcepto.aplarccon = 1 ".
				"           AND (sno_hsalida.tipsal = 'A' OR sno_hsalida.tipsal = 'V1' OR sno_hsalida.tipsal = 'W1' ".
				"            OR sno_hsalida.tipsal = 'D' OR sno_hsalida.tipsal = 'V2' OR sno_hsalida.tipsal = 'W2' ".
				"            OR sno_hsalida.tipsal = 'P1' OR sno_hsalida.tipsal = 'V3' OR sno_hsalida.tipsal = 'W3') ".
				"           ".$ls_criterio." ".$ls_concepto.
				"           AND sno_hsalida.codemp = sno_hperiodo.codemp ".
				"           AND sno_hsalida.anocur = sno_hperiodo.anocur ".
				"           AND sno_hsalida.codperi = sno_hperiodo.codperi ".
				"           AND sno_hsalida.codnom = sno_hperiodo.codnom ".
				"           AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"           AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"           AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"           AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"           AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				"           AND sno_hsalida.codemp = sno_personalisr.codemp ".
				"           AND sno_hsalida.codper = sno_personalisr.codper ".
				"         GROUP BY sno_hsalida.codper, sno_hperiodo.anocur, sno_personalisr.codisr) as arc, ".
				"       (SELECT SUM(valsalaux) ".
				"          FROM sno_hsalida, sno_hconcepto, sno_hperiodo ".
				"         WHERE sno_hsalida.codemp = '".$this->ls_codemp."' ".
				"           AND sno_hsalida.codper = '".$as_codper."' ".
				"           AND sno_hperiodo.anocur = '".$as_ano."' ".
		        "   		AND SUBSTR(sno_hperiodo.fecdesper,1,4) = '".$as_ano."' ".
				"           AND sno_personalisr.codisr = SUBSTR(sno_hperiodo.fecdesper,6,2) ".
				"           AND sno_hconcepto.aplisrcon = 1 ".
				"           AND (sno_hsalida.tipsal = 'A' OR sno_hsalida.tipsal = 'V1' OR sno_hsalida.tipsal = 'W1' ".
				"            OR sno_hsalida.tipsal = 'D' OR sno_hsalida.tipsal = 'V2' OR sno_hsalida.tipsal = 'W2' ".
				"            OR sno_hsalida.tipsal = 'P1' OR sno_hsalida.tipsal = 'V3' OR sno_hsalida.tipsal = 'W3') ".
				"           ".$ls_criterio." ".$ls_concepto.
				"           AND sno_hsalida.codemp = sno_hperiodo.codemp ".
				"           AND sno_hsalida.anocur = sno_hperiodo.anocur ".
				"           AND sno_hsalida.codperi = sno_hperiodo.codperi ".
				"           AND sno_hsalida.codnom = sno_hperiodo.codnom ".
				"           AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"           AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"           AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"           AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"           AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				"           AND sno_hsalida.codemp = sno_personalisr.codemp ".
				"           AND sno_hsalida.codper = sno_personalisr.codper ".
				"         GROUP BY sno_hsalida.codper, sno_hperiodo.anocur, sno_personalisr.codisr) as islr ".
				"  FROM sno_hsalida, sno_personalisr  ".
				" WHERE sno_hsalida.codper = '".$as_codper."' ".
				"   AND sno_hsalida.codemp = '".$this->ls_codemp."' ".
				"   AND sno_hsalida.anocur = '".$as_ano."' ".
				"   ".$ls_criterio." ".
				"   AND sno_hsalida.codemp = sno_personalisr.codemp ".
				"   AND sno_hsalida.codper = sno_personalisr.codper ".
				"   GROUP BY sno_hsalida.codper, sno_hsalida.anocur, sno_personalisr.codisr, sno_personalisr.porisr, ".
				"			 sno_personalisr.codemp, sno_personalisr.codper ".
				"   ORDER BY sno_personalisr.codisr ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_retencionarc_meses ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_retencionarc_meses
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_retencionarc_aporte($as_codper,$aa_nominas,$ai_total,$as_ano)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_retencionarc_aporte
		//		   Access: public (desde la clase sigesp_snorh_rpp_aportepatronal)  
		//	    Arguments: as_codper // Código de Personal
		//	    		   aa_nominas // Arreglo de Nóminas por el cual se debe filtrar
		//	  			   ai_total // total de Nómina por el cual se va a filtrar	  	  
		//	    		   as_ano // Año en curso
		//	  			   as_orden // orden por medio del cual se desea que salga el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que tienen asociado los concepto de tipo aporte patronal 
		//				   en las nóminas seleccionadas
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/08/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->io_sql=new class_sql($this->io_conexion);	
		$lb_valido=true;
		$ls_orden="";
		$ls_criterio=" AND (";
		for($li_i=1;$li_i<=$ai_total;$li_i++)
		{
			$ls_codnom=$aa_nominas[$li_i];
			$ls_criterio= $ls_criterio." sno_hsalida.codnom='".$ls_codnom."' ";
			if($li_i<$ai_total)
			{
				$ls_criterio= $ls_criterio." OR ";
			}
		}
		$ls_criterio=$ls_criterio." )";
		if(!empty($as_ano))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.anocur='".$as_ano."' ";
		}
		$ls_criterio = $ls_criterio." AND sno_hsalida.codper='".$as_codper."' ";
		$ls_criterio = $ls_criterio." AND sno_hsalida.valsal<>0 ";
		$ls_sql="SELECT sno_hconcepto.codconc, sno_hconcepto.nomcon, SUM(sno_hsalida.valsalaux) as monto ".
				"  FROM sno_hsalida, sno_hconcepto, sno_hperiodo ".
				" WHERE (sno_hsalida.tipsal='P1' OR sno_hsalida.tipsal='V3' OR sno_hsalida.tipsal='W3' OR sno_hsalida.tipsal='Q1')".
				$ls_criterio.
		        "   AND SUBSTR(sno_hperiodo.fecdesper,1,4) = '".$as_ano."' ".
				"   AND sno_hsalida.codemp = sno_hperiodo.codemp ".
				"   AND sno_hsalida.codnom = sno_hperiodo.codnom ".
				"   AND sno_hsalida.anocur = sno_hperiodo.anocur ".
				"   AND sno_hsalida.codperi = sno_hperiodo.codperi ".
				"  	AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				" GROUP BY sno_hconcepto.codconc, sno_hconcepto.nomcon ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_retencionarc_aporte ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);			
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_retencionarc_aporte
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_vacaciones_personal($as_codperdes,$as_codperhas,$as_vencida,$as_programada,$as_vacacion,$as_disfrutada,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_vacaciones_personal
		//         Access: public (desde la clase sigesp_snorh_rpp_vacaciones)  
		//	    Arguments: as_codperdes // Código de personal donde se empieza a filtrar
		//	  			   as_codperhas // Código de personal donde se termina de filtrar		  
		//	    		   as_vencida // Si la vacación esta vencida
		//	  			   as_programada // Si la vacación esta programada	  
		//	  			   as_vacacion //  Si la vacación esta en vacación
		//	  			   as_disfrutada //  Si la vacación esta disfrutada
		//	  			   as_orden // Orden en que se quiere sacar el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal que tiene vacaciones
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 23/08/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		$lb_anterior=false;
		if(!empty($as_codperdes))
		{
			$ls_criterio= " AND sno_personal.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio." AND sno_personal.codper<='".$as_codperhas."'";
		}
		if(!empty($as_vencida))
		{
			$ls_criterio= $ls_criterio. " AND (sno_vacacpersonal.stavac=1 ";
			$lb_anterior=true;
		}
		if(!empty($as_programada))
		{
			if($lb_anterior)
			{
				$ls_criterio= $ls_criterio.= " OR sno_vacacpersonal.stavac=2 ";
			}
			else
			{
				$ls_criterio= $ls_criterio. " AND (sno_vacacpersonal.stavac=2 ";
				$lb_anterior=true;
			}
		}
		if(!empty($as_vacacion))
		{
			if($lb_anterior)
			{
				$ls_criterio= $ls_criterio. " OR sno_vacacpersonal.stavac=3 ";
			}
			else
			{
				$ls_criterio= $ls_criterio. " AND (sno_vacacpersonal.stavac=3 ";
				$lb_anterior=true;
			}
		}
		if(!empty($as_disfrutada))
		{
			if($lb_anterior)
			{
				$ls_criterio= $ls_criterio. " OR sno_vacacpersonal.stavac=4 ";
			}
			else
			{
				$ls_criterio= $ls_criterio. " AND (sno_vacacpersonal.stavac=4 ";
				$lb_anterior=true;
			}
		}
		if($lb_anterior)
		{
			$ls_criterio= $ls_criterio.")";
		}
		$ls_criterio= $ls_criterio." AND sno_personal.estper='1'";
		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;
		}
		$ls_sql="SELECT sno_personal.codper, sno_personal.apeper, sno_personal.nomper,sno_personal.fecingper ".
				"  FROM sno_personal, sno_vacacpersonal ".
				" WHERE sno_personal.codemp = '".$this->ls_codemp."'".
				"   ".$ls_criterio." ".
				"	AND sno_personal.codemp = sno_vacacpersonal.codemp ".
				"   AND sno_personal.codper = sno_vacacpersonal.codper ".
				" GROUP BY sno_personal.codper,sno_personal.apeper, sno_personal.nomper,sno_personal.fecingper ".
				"   ".$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_vacaciones_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_vacaciones_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_vacaciones_vacacion($as_codper,$as_vencida,$as_programada,$as_vacacion,$as_disfrutada,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_vacaciones_vacacion
		//         Access: public (desde la clase sigesp_snorh_rpp_vacaciones)  
		//	    Arguments: as_codper // Código de personal
		//	    		   as_vencida // Si la vacación esta vencida
		//	  			   as_programada // Si la vacación esta programada	  
		//	  			   as_vacacion //  Si la vacación esta en vacación
		//	  			   as_disfrutada //  Si la vacación esta disfrutada
		//	  			   as_orden // Orden en que se quiere sacar el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal que tiene vacaciones
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 23/08/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		$lb_anterior=false;
		if(!empty($as_vencida))
		{
			$ls_criterio= $ls_criterio. " AND (sno_vacacpersonal.stavac=1 ";
			$lb_anterior=true;
		}
		if(!empty($as_programada))
		{
			if($lb_anterior)
			{
				$ls_criterio= $ls_criterio.= " OR sno_vacacpersonal.stavac=2 ";
			}
			else
			{
				$ls_criterio= $ls_criterio. " AND (sno_vacacpersonal.stavac=2 ";
				$lb_anterior=true;
			}
		}
		if(!empty($as_vacacion))
		{
			if($lb_anterior)
			{
				$ls_criterio= $ls_criterio. " OR sno_vacacpersonal.stavac=3 ";
			}
			else
			{
				$ls_criterio= $ls_criterio. " AND (sno_vacacpersonal.stavac=3 ";
				$lb_anterior=true;
			}
		}
		if(!empty($as_disfrutada))
		{
			if($lb_anterior)
			{
				$ls_criterio= $ls_criterio. " OR sno_vacacpersonal.stavac=4 ";
			}
			else
			{
				$ls_criterio= $ls_criterio. " AND ( sno_vacacpersonal.stavac=4 ";
				$lb_anterior=true;
			}
		}
		if($lb_anterior)
		{
			$ls_criterio= $ls_criterio.")";
		}
		$ls_criterio= $ls_criterio." AND sno_personal.estper='1'";
		$ls_sql="SELECT sno_vacacpersonal.codvac, sno_vacacpersonal.fecvenvac, sno_vacacpersonal.fecdisvac, sno_vacacpersonal.fecreivac, ".
				"		sno_vacacpersonal.diavac, sno_vacacpersonal.stavac, sno_vacacpersonal.sueintvacaux AS sueintvac, sno_vacacpersonal.diabonvac, ".
				"		sno_vacacpersonal.obsvac, sno_vacacpersonal.dianorvac, sno_vacacpersonal.diaadivac, sno_vacacpersonal.diaadibon, ".
				"		sno_vacacpersonal.diafer, sno_vacacpersonal.sabdom ".
				"  FROM sno_personal, sno_vacacpersonal ".
				" WHERE sno_personal.codemp = '".$this->ls_codemp."'".
				"	AND sno_personal.codper = '".$as_codper."'".
				"   ".$ls_criterio." ".
				"	AND sno_personal.codemp = sno_vacacpersonal.codemp ".
				"   AND sno_personal.codper = sno_vacacpersonal.codper ".
				" ORDER BY sno_vacacpersonal.codvac ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_vacaciones_vacacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_vacaciones_vacacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_cestaticket_personal($as_codnomdes,$as_codnomhas,$as_ano,$as_mes,$as_codperi,$as_codconcdes,$as_codconchas,
									 $as_conceptocero,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_cestaticket_personal
		//		   Access: public (desde la clase sigesp_snorh_rpp_cestaticket)  
		//	    Arguments: as_codnomdes // Código nómina desde
		//	  			   as_codnomhas // Código nómina hasta
		//	    		   as_ano // Año en curso
		//	  			   as_mes // mes
		//	    		   as_codperi // Código del periodo
		//	    		   as_codconcdes // Código del concepto Desde del que se desea busca el personal
		//	    		   as_codconchas // Código del concepto Hasta del que se desea busca el personal
		//				   as_conceptocero // Conceptos en cero
		//	  			   as_orden // orden por medio del cual se desea que salga el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que tienen asociado el concepto	de tipo aporte patronal 
		//				   en las nóminas seleccionadas
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 10/07/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		if(!empty($as_codnomdes))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.codnom>='".$as_codnomdes."' ";
		}
		if(!empty($as_codnomhas))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.codnom<='".$as_codnomhas."' ";
		}
		if(!empty($as_ano))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.anocur='".$as_ano."' ";
		}
		if(!empty($as_codperi))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.codperi='".$as_codperi."' ";
		}
		if(!empty($as_codconcdes))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.codconc>='".$as_codconcdes."' ";
		}
		if(!empty($as_codconchas))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.codconc<='".$as_codconchas."' ";
		}
		if(!empty($as_conceptocero))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.valsal<>0 ";
		}
		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="ORDER BY sno_hsalida.codconc, sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_hsalida.codconc, sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_hsalida.codconc, sno_personal.nomper ";
				break;

			case "4": // Ordena por Cédula de personal
				$ls_orden="ORDER BY sno_hsalida.codconc, sno_personal.cedper ";
				break;
		}
		$ls_sql="SELECT sno_personal.codper,sno_personal.cedper, sno_personal.apeper, sno_personal.nomper, sno_hsalida.valsalaux AS valsal, ".
				"		sno_cestaticunidadadm.est1cestic , sno_cestaticunidadadm.est2cestic, sno_cestaticket.moncesticaux AS moncestic, ".
				"		sno_hsalida.codconc, sno_hconcepto.nomcon ".
				"  FROM sno_personal, sno_hpersonalnomina, sno_hsalida, sno_hnomina, sno_hconcepto, sno_cestaticunidadadm, sno_cestaticket ".
				" WHERE sno_hpersonalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_hpersonalnomina.codnom>='".$as_codnomdes."' ".
				"   AND sno_hpersonalnomina.codnom<='".$as_codnomhas."' ".
				"   AND sno_hpersonalnomina.anocur='".$as_ano."' ".
				"   AND sno_hpersonalnomina.codperi='".$as_codperi."' ".
				"   AND sno_hpersonalnomina.staper='1' ".
				"   AND sno_hnomina.espnom = '1' ".
				"   AND sno_hnomina.ctnom = '1' ".
				$ls_criterio.
				"   AND sno_personal.codemp = sno_hpersonalnomina.codemp ".
				"   AND sno_personal.codper = sno_hpersonalnomina.codper ".
				"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
				"   AND sno_hconcepto.codemp = sno_hsalida.codemp ".
				"   AND sno_hconcepto.codnom = sno_hsalida.codnom ".
				"   AND sno_hconcepto.anocur = sno_hsalida.anocur ".
				"   AND sno_hconcepto.codperi = sno_hsalida.codperi ".
				"   AND sno_hconcepto.codconc = sno_hsalida.codconc ".
				"   AND sno_hpersonalnomina.codemp = sno_cestaticunidadadm.codemp ".
				"   AND sno_hpersonalnomina.codemp = sno_hnomina.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hnomina.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hnomina.anocurnom ".
				"   AND sno_hpersonalnomina.codperi = sno_hnomina.peractnom ".
				"   AND sno_hnomina.ctmetnom = sno_cestaticket.codcestic ".
				"   AND sno_cestaticket.codemp = sno_cestaticunidadadm.codemp ".
				"   AND sno_cestaticket.codcestic = sno_cestaticunidadadm.codcestic ".
				"   AND sno_hpersonalnomina.minorguniadm = sno_cestaticunidadadm.minorguniadm ".
				"   AND sno_hpersonalnomina.ofiuniadm = sno_cestaticunidadadm.ofiuniadm ".
				"   AND sno_hpersonalnomina.uniuniadm = sno_cestaticunidadadm.uniuniadm ".
				"   AND sno_hpersonalnomina.depuniadm = sno_cestaticunidadadm.depuniadm ".
				"   AND sno_hpersonalnomina.prouniadm = sno_cestaticunidadadm.prouniadm ".
				"   ".$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_cestaticket_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);			
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_cestaticket_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_familiar_personal($as_codperdes,$as_codperhas,$as_conyuge,$as_progenitor,$as_hijo,$as_hermano,$as_masculino,
								  $as_femenino,$ai_edaddesde,$ai_edadhasta,$as_codnomdes,$as_codnomhas,$as_activo,$as_egresado,
								  $as_activono,$as_vacacionesno,$as_suspendidono,$as_egresadono,$as_personalmasculino,
								  $as_personalfemenino,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_familiar_personal
		//         Access: public (desde la clase sigesp_snorh_rpp_familiar)  
		//	    Arguments: as_codperdes // Código de personal donde se empieza a filtrar
		//	  			   as_codperhas // Código de personal donde se termina de filtrar		  
		//	    		   as_conyuge // Si el familiar es Conyuge
		//	  			   as_progenitor // Si el familiar es Progenitor
		//	  			   as_hijo //  Si el familiar es Hijo
		//	  			   as_hermano //  Si el familiar es Hermano
		//	  			   as_masculino //  Si el familiar es Masculino
		//	  			   as_femenino //  Si el familiar es Femenino
		//	  			   ai_edaddesde //  Edad Desde
		//	  			   ai_edadhasta //  Edad Hasta
		//	  			   as_codnomdes //  Código de Nómina Desde
		//	  			   as_codnomhas //  Código de Nómina Hasta
		//	  			   as_activo // Estatus Activo		  
		//	  			   as_egresado // Estatus Egresado
		//	  			   as_activono // Estatus Activo dentro de la nómina		  
		//	  			   as_vacacionesno // Estatus Vaciones dentro de la nómina
		//	  			   as_suspendidono // Estatus Suspendido dentro de la nómina
		//	  			   as_egresadono // Estatus Egresado dentro de la nómina
		//	  			   as_personalmasculino //  Si el Personal es Masculino
		//	  			   as_personalfemenino //  Si el Personal es Femenino
		//	  			   as_orden // Orden en que se quiere sacar el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal que tiene Familiares
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/09/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		$lb_ok=false;
		$lb_anterior=false;
		if(!empty($as_codnomdes))
		{
			$ls_criterio= $ls_criterio." AND sno_personalnomina.codnom>='".$as_codnomdes."'";
			if(!empty($as_codnomhas))
			{
				$ls_criterio= $ls_criterio." AND sno_personalnomina.codnom<='".$as_codnomhas."'";
			}
			if(!empty($as_activono))
			{
				$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='1' ";
				$lb_ok=true;
			}
			if(!empty($as_vacacionesno))
			{
				if($lb_ok)
				{
					$ls_criterio= $ls_criterio." OR sno_personalnomina.staper='2' ";
				}
				else
				{
					$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='2' ";
					$lb_ok=true;
				}
			}
			if(!empty($as_egresadono))
			{
				if($lb_ok)
				{
					$ls_criterio= $ls_criterio." OR sno_personalnomina.staper='3' ";
				}
				else
				{
					$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='3' ";
					$lb_ok=true;
				}
			}
			if(!empty($as_suspendidono))
			{
				if($lb_ok)
				{
					$ls_criterio= $ls_criterio." OR sno_personalnomina.staper='4' ";
				}
				else
				{
					$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='4' ";
					$lb_ok=true;
				}
			}
			if($lb_ok)
			{
				$ls_criterio= $ls_criterio." )";
				$lb_ok=false;
			}
		}
		if(!empty($as_codperdes))
		{
			$ls_criterio= $ls_criterio." AND sno_personal.codper>='".$as_codperdes."'";
			if(!empty($as_codperhas))
			{
				$ls_criterio= $ls_criterio." AND sno_personal.codper<='".$as_codperhas."'";
			}
		}
		if(!empty($as_activo))
		{
			$ls_criterio= $ls_criterio." AND (sno_personal.estper='1' ";
			$lb_ok=true;
		}
		if(!empty($as_egresado))
		{
			if($lb_ok)
			{
				$ls_criterio= $ls_criterio." OR sno_personal.estper='3' ";
			}
			else
			{
				$ls_criterio= $ls_criterio." AND (sno_personal.estper='3' ";
				$lb_ok=true;
			}
		}
		if($lb_ok)
		{
			$ls_criterio= $ls_criterio.")";
		}
		if(!empty($as_conyuge))
		{
			$ls_criterio= $ls_criterio. " AND (sno_familiar.nexfam='C' ";
			$lb_anterior=true;
		}
		if(!empty($as_progenitor))
		{
			if($lb_anterior)
			{
				$ls_criterio= $ls_criterio.= " OR sno_familiar.nexfam='P' ";
			}
			else
			{
				$ls_criterio= $ls_criterio. " AND (sno_familiar.nexfam='P' ";
				$lb_anterior=true;
			}
		}
		if(!empty($as_hijo))
		{
			if($lb_anterior)
			{
				$ls_criterio= $ls_criterio. " OR sno_familiar.nexfam='H' ";
			}
			else
			{
				$ls_criterio= $ls_criterio. " AND (sno_familiar.nexfam='H' ";
				$lb_anterior=true;
			}
		}
		if(!empty($as_hermano))
		{
			if($lb_anterior)
			{
				$ls_criterio= $ls_criterio. " OR sno_familiar.nexfam='E' ";
			}
			else
			{
				$ls_criterio= $ls_criterio. " AND (sno_familiar.nexfam='E' ";
				$lb_anterior=true;
			}
		}
		if($lb_anterior)
		{
			$ls_criterio= $ls_criterio.")";
		}
		$lb_anterior=false;
		if(!empty($as_masculino))
		{
			$ls_criterio= $ls_criterio. " AND (sno_familiar.sexfam='M' ";
			$lb_anterior=true;
		}
		if(!empty($as_femenino))
		{
			if($lb_anterior)
			{
				$ls_criterio= $ls_criterio. " OR sno_familiar.sexfam='F' ";
			}
			else
			{
				$ls_criterio= $ls_criterio. " AND (sno_familiar.sexfam='F' ";
				$lb_anterior=true;
			}
		}
		if($lb_anterior)
		{
			$ls_criterio= $ls_criterio.")";
		}				
		$lb_anterior=false;
		if(!empty($as_personalmasculino))
		{
			$ls_criterio= $ls_criterio. " AND (sno_personal.sexper='M' ";
			$lb_anterior=true;
		}
		if(!empty($as_personalfemenino))
		{
			if($lb_anterior)
			{
				$ls_criterio= $ls_criterio. " OR sno_personal.sexper='F' ";
			}
			else
			{
				$ls_criterio= $ls_criterio. " AND (sno_personal.sexper='F' ";
				$lb_anterior=true;
			}
		}
		if($lb_anterior)
		{
			$ls_criterio= $ls_criterio.")";
		}				
		if(!empty($ai_edadhasta))
		{
			if($ai_edaddesde==$ai_edadhasta)
			{
				$ld_hoy=date('Y')."-".date('m')."-".date('d');
				$li_resta=$ai_edadhasta+1;
				$ld_fecha=date("Y-m-d", strtotime("$ld_hoy -$li_resta year"));
				$ls_criterio= $ls_criterio." AND sno_familiar.fecnacfam>='".$ld_fecha."' ";
				$ld_fecha=date("Y-m-d", strtotime("$ld_hoy -$ai_edaddesde year"));
				$ls_criterio= $ls_criterio." AND sno_familiar.fecnacfam<'".$ld_fecha."' ";
			}
			else
			{
				$ld_hoy=date('Y')."-".date('m')."-".date('d');
				$ld_fecha=date("Y-m-d", strtotime("$ld_hoy -$ai_edadhasta year"));
				$ls_criterio= $ls_criterio." AND sno_familiar.fecnacfam>='".$ld_fecha."' ";
			}
		}
		if(!empty($ai_edaddesde))
		{
			if($ai_edaddesde!=$ai_edadhasta)
			{
				$ld_hoy=date('Y')."-".date('m')."-".date('d');
				$ld_fecha=date("Y-m-d", strtotime("$ld_hoy -$ai_edaddesde year"));
				$ls_criterio= $ls_criterio." AND sno_familiar.fecnacfam<='".$ld_fecha."' ";
			}
		}
		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Cédula de personal
				$ls_orden="ORDER BY sno_personal.cedper ";
				break;

			case "3": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "4": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;
		}
		if((!empty($as_codnomdes))&&(!empty($as_codnomhas)))
		{ // si busco por nómina
			$ls_sql="SELECT sno_personal.codper,sno_personal.apeper, sno_personal.nomper, sno_personal.fecnacper  ".
					"  FROM sno_personal, sno_familiar, sno_personalnomina ".
					" WHERE sno_personal.codemp = '".$this->ls_codemp."'".
					"   ".$ls_criterio." ".
					"	AND sno_personal.codemp = sno_personalnomina.codemp ".
					"   AND sno_personal.codper = sno_personalnomina.codper ".
					"	AND sno_personal.codemp = sno_familiar.codemp ".
					"   AND sno_personal.codper = sno_familiar.codper ".
					" GROUP BY sno_personal.codper, sno_personal.apeper, sno_personal.nomper, sno_personal.fecnacper ".
					"   ".$ls_orden;
		}
		else
		{ // si no busco por nómina
			$ls_sql="SELECT sno_personal.codper,sno_personal.apeper, sno_personal.nomper, sno_personal.fecnacper  ".
					"  FROM sno_personal, sno_familiar ".
					" WHERE sno_personal.codemp = '".$this->ls_codemp."'".
					"   ".$ls_criterio." ".
					"	AND sno_personal.codemp = sno_familiar.codemp ".
					"   AND sno_personal.codper = sno_familiar.codper ".
					" GROUP BY sno_personal.codper, sno_personal.apeper, sno_personal.nomper, sno_personal.fecnacper ".
					"   ".$ls_orden;
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_familiar_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_familiar_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_familiar_familiar($as_codper,$as_conyuge,$as_progenitor,$as_hijo,$as_hermano,$as_masculino,$as_femenino,$ai_edaddesde,$ai_edadhasta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_familiar_familiar
		//         Access: public (desde la clase sigesp_snorh_rpp_vacaciones)  
		//	    Arguments: as_codper // Código de personal
		//	    		   as_conyuge // Si el familiar es Conyuge
		//	  			   as_progenitor // Si el familiar es Progenitor
		//	  			   as_hijo //  Si el familiar es Hijo
		//	  			   as_hermano //  Si el familiar es Hermano
		//	  			   as_masculino //  Si el familiar es Masculino
		//	  			   as_femenino //  Si el familiar es Femenino
		//	  			   ai_edaddesde //  Edad Desde
		//	  			   ai_edadhasta //  Edad Hasta
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los familiares del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/09/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$lb_anterior=false;
		if(!empty($as_conyuge))
		{
			$ls_criterio= $ls_criterio. " AND (sno_familiar.nexfam='C' ";
			$lb_anterior=true;
		}
		if(!empty($as_progenitor))
		{
			if($lb_anterior)
			{
				$ls_criterio= $ls_criterio.= " OR sno_familiar.nexfam='P' ";
			}
			else
			{
				$ls_criterio= $ls_criterio. " AND (sno_familiar.nexfam='P' ";
				$lb_anterior=true;
			}
		}
		if(!empty($as_hijo))
		{
			if($lb_anterior)
			{
				$ls_criterio= $ls_criterio. " OR sno_familiar.nexfam='H' ";
			}
			else
			{
				$ls_criterio= $ls_criterio. " AND (sno_familiar.nexfam='H' ";
				$lb_anterior=true;
			}
		}
		if(!empty($as_hermano))
		{
			if($lb_anterior)
			{
				$ls_criterio= $ls_criterio. " OR sno_familiar.nexfam='E' ";
			}
			else
			{
				$ls_criterio= $ls_criterio. " AND (sno_familiar.nexfam='E' ";
				$lb_anterior=true;
			}
		}
		if($lb_anterior)
		{
			$ls_criterio= $ls_criterio.")";
		}
		$lb_anterior=false;
		if(!empty($as_masculino))
		{
			$ls_criterio= $ls_criterio. " AND (sno_familiar.sexfam='M' ";
			$lb_anterior=true;
		}
		if(!empty($as_femenino))
		{
			if($lb_anterior)
			{
				$ls_criterio= $ls_criterio. " OR sno_familiar.sexfam='F' ";
			}
			else
			{
				$ls_criterio= $ls_criterio. " AND (sno_familiar.sexfam='F' ";
				$lb_anterior=true;
			}
		}
		if($lb_anterior)
		{
			$ls_criterio= $ls_criterio.")";
		}		
		if(!empty($ai_edadhasta))
		{
			if($ai_edaddesde==$ai_edadhasta)
			{
				$ld_hoy=date('Y')."-".date('m')."-".date('d');
				$li_resta=$ai_edadhasta+1;
				$ld_fecha=date("Y-m-d", strtotime("$ld_hoy -$li_resta year"));
				$ls_criterio= $ls_criterio." AND sno_familiar.fecnacfam>='".$ld_fecha."' ";
				$ld_fecha=date("Y-m-d", strtotime("$ld_hoy -$ai_edaddesde year"));
				$ls_criterio= $ls_criterio." AND sno_familiar.fecnacfam<'".$ld_fecha."' ";
			}
			else
			{
				$ld_hoy=date('Y')."-".date('m')."-".date('d');
				$ld_fecha=date("Y-m-d", strtotime("$ld_hoy -$ai_edadhasta year"));
				$ls_criterio= $ls_criterio." AND sno_familiar.fecnacfam>='".$ld_fecha."' ";
			}
		}
		if(!empty($ai_edaddesde))
		{
			if($ai_edaddesde!=$ai_edadhasta)
			{
				$ld_hoy=date('Y')."-".date('m')."-".date('d');
				$ld_fecha=date("Y-m-d", strtotime("$ld_hoy -$ai_edaddesde year"));
				$ls_criterio= $ls_criterio." AND sno_familiar.fecnacfam<='".$ld_fecha."' ";
			}
		}
		$ls_sql="SELECT cedfam, nomfam, apefam, sexfam, fecnacfam, nexfam, estfam ".
				"  FROM sno_familiar ".
				" WHERE codemp = '".$this->ls_codemp."'".
				"	AND codper = '".$as_codper."'".
				"   ".$ls_criterio." ".
				" ORDER BY apefam,nomfam,nexfam  ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_familiar_familiar ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_familiar_familiar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_consolidadoconcepto_conceptos($as_codnomdes,$as_codnomhas,$as_codconcdes,$as_codconchas,$as_codperdes,$as_codperhas,
											  $as_tipconc,$as_conceptocero,$as_personaldes,$as_personalhas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_consolidadoconcepto_conceptos
		//         Access: public (desde la clase sigesp_snorh_rpp_conceptos)  
		//	    Arguments: as_codnomdes // Código de la Nómina donde se empieza a filtrar
		//				   as_codnomhas // Código de la Nómina donde se termina de filtrar
		//	    		   as_codconcdes // Código del concepto donde se empieza a filtrar
		//				   as_codconchas // Código del concepto donde se termina de filtrar
		//				   as_codperdes // Código del período donde se empieza a filtrar
		//	  			   as_codperhas // Código del período donde se termina de filtrar		  
		//	  			   as_tipconc // Tipo de Concepto
		//	  			   as_conceptocero // criterio que me indica si se desea quitar los conceptos que tienen monto cero
		//				   as_personaldes // Código del personal donde se empieza a filtrar
		//	  			   as_personalhas // Código del personal donde se termina de filtrar		  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los conceptos que se calcularon en la nómina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 24/10/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		if(!empty($as_codnomdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_hconcepto.codnom>='".$as_codnomdes."'";
		}
		if(!empty($as_codnomhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_hconcepto.codnom<='".$as_codnomhas."'";
		}
		if(!empty($as_codconcdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_hconcepto.codconc>='".$as_codconcdes."'";
		}
		if(!empty($as_codconchas))
		{
			$ls_criterio= $ls_criterio."   AND sno_hconcepto.codconc<='".$as_codconchas."'";
		}
		if(!empty($as_codperdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_hconcepto.codperi>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_hconcepto.codperi<='".$as_codperhas."'";
		}
		if(!empty($as_conceptocero))
		{
			$ls_criterio = $ls_criterio."   AND sno_hsalida.valsal<>0 ";
		}
		if(!empty($as_personaldes))
		{
			$ls_criterio= $ls_criterio."   AND sno_hsalida.codper>='".$as_personaldes."'";
		}
		if(!empty($as_personalhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_hsalida.codper<='".$as_personalhas."'";
		}
		if(!empty($as_tipconc))
		{
			$ls_criterio = $ls_criterio."   AND sno_hconcepto.sigcon='".$as_tipconc."' ";
		}
		$ls_sql="SELECT sno_hconcepto.codconc, sno_hconcepto.nomcon,  sno_hconcepto.sigcon ".
				"  FROM sno_hconcepto, sno_hsalida ".
				" WHERE sno_hconcepto.codemp='".$this->ls_codemp."' ".
				"   ".$ls_criterio.
				"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				" GROUP BY sno_hconcepto.sigcon, sno_hconcepto.codconc, sno_hconcepto.nomcon ".
				" ORDER BY sno_hconcepto.sigcon, sno_hconcepto.codconc ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_consolidadoconcepto_conceptos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_consolidadoconcepto_conceptos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_consolidadoconcepto_personal($as_codnomdes,$as_codnomhas,$as_codconc,$as_codperdes,$as_codperhas,
											 $as_conceptocero,$as_personaldes,$as_personalhas,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_consolidadoconcepto_personal
		//         Access: public (desde la clase sigesp_snorh_rpp_conceptos)  
		//	    Arguments: as_codnomdes // Código de la Nómina donde se empieza a filtrar
		//				   as_codnomhas // Código de la Nómina donde se termina de filtrar
		//	    		   as_codconc // Código del concepto
		//				   as_codconchas // Código del concepto donde se termina de filtrar
		//				   as_codperdes // Código del período donde se empieza a filtrar
		//	  			   as_codperhas // Código del período donde se termina de filtrar		  
		//	  			   as_conceptocero // criterio que me indica si se desea quitar los conceptos que tienen monto cero
		//				   as_personaldes // Código del personal donde se empieza a filtrar
		//	  			   as_personalhas // Código del personal donde se termina de filtrar		  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los conceptos que se calcularon en la nómina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 24/10/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		if(!empty($as_codnomdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_hsalida.codnom>='".$as_codnomdes."'";
		}
		if(!empty($as_codnomhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_hsalida.codnom<='".$as_codnomhas."'";
		}
		if(!empty($as_codconc))
		{
			$ls_criterio= $ls_criterio."   AND sno_hsalida.codconc='".$as_codconc."'";
		}
		if(!empty($as_codperdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_hsalida.codperi>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_hsalida.codperi<='".$as_codperhas."'";
		}
		if(!empty($as_conceptocero))
		{
			$ls_criterio = $ls_criterio."   AND sno_hsalida.valsal<>0 ";
		}
		if(!empty($as_personaldes))
		{
			$ls_criterio= $ls_criterio."   AND sno_hsalida.codper>='".$as_personaldes."'";
		}
		if(!empty($as_personalhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_hsalida.codper<='".$as_personalhas."'";
		}
		$ls_criterio = $ls_criterio."   AND sno_hsalida.tipsal<>'P2' ";
		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="ORDER BY sno_hpersonalnomina.codnom, sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_hpersonalnomina.codnom, sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_hpersonalnomina.codnom, sno_personal.nomper ";
				break;

			case "4": // Ordena por Cédula de personal
				$ls_orden="ORDER BY sno_hpersonalnomina.codnom, sno_personal.cedper ";
				break;
		}
		$ls_sql="SELECT sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, SUM(sno_hsalida.valsalaux) AS total ".
				"  FROM sno_hpersonalnomina, sno_personal, sno_hsalida ".
				" WHERE sno_hpersonalnomina.codemp='".$this->ls_codemp."' ".
				"   ".$ls_criterio.
				"   AND sno_hsalida.codemp = sno_hpersonalnomina.codemp ".
				"   AND sno_hsalida.codnom = sno_hpersonalnomina.codnom ".
				"   AND sno_hsalida.anocur = sno_hpersonalnomina.anocur ".
				"   AND sno_hsalida.codperi = sno_hpersonalnomina.codperi ".
				"   AND sno_hsalida.codper = sno_hpersonalnomina.codper ".
				"   AND sno_personal.codemp = sno_hpersonalnomina.codemp ".
				"   AND sno_personal.codper = sno_hpersonalnomina.codper ".
				" GROUP BY sno_hpersonalnomina.codnom, sno_hpersonalnomina.codper, sno_personal.codper, sno_personal.cedper, ".
				"		   sno_personal.nomper, sno_personal.apeper ".
				$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_consolidadoconcepto_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_consolidadoconcepto_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_listadocumpleanos($as_codnomdes,$as_codnomhas,$as_codperdes,$as_codperhas,$as_mes,$as_activo,$as_egresado,
								  $as_activono,$as_vacacionesno,$as_suspendidono,$as_egresadono,$as_nomnormal,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listadocumpleanos
		//         Access: public (desde la clase sigesp_snorh_rpp_listadocumpleano)  
		//	    Arguments: as_codnomdes // Código de la nómina donde se empieza a filtrar
		//	  			   as_codnomhas // Código de la nómina donde se termina de filtrar		  
		//	    		   as_codperdes // Código de personal donde se empieza a filtrar
		//	  			   as_codperhas // Código de personal donde se termina de filtrar		  
		//	  			   as_mes // Mes de Cumpleaños
		//	  			   as_activo // Estatus Activo		  
		//	  			   as_egresado // Estatus Egresado
		//	  			   as_activono // Estatus Activo dentro de la nómina		  
		//	  			   as_vacacionesno // Estatus Vaciones dentro de la nómina
		//	  			   as_suspendidono // Estatus Suspendido dentro de la nómina
		//	  			   as_egresadono // Estatus Egresado dentro de la nómina
		//	  			   as_nomnormal // Filtrar solo por nóminas normales
		//	  			   as_orden // Orden en que se quiere sacar el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/12/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		$lb_ok=false;
		if(!empty($as_codnomdes))
		{
			$ls_criterio= $ls_criterio." AND sno_personalnomina.codnom>='".$as_codnomdes."'";
			if(!empty($as_codnomhas))
			{
				$ls_criterio= $ls_criterio." AND sno_personalnomina.codnom<='".$as_codnomhas."'";
			}
			if(!empty($as_nomnormal))
			{
				$ls_criterio= $ls_criterio." AND sno_nomina.espnom='0'";
			}
			if(!empty($as_activono))
			{
				$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='1' ";
				$lb_ok=true;
			}
			if(!empty($as_vacacionesno))
			{
				if($lb_ok)
				{
					$ls_criterio= $ls_criterio." OR sno_personalnomina.staper='2' ";
				}
				else
				{
					$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='2' ";
					$lb_ok=true;
				}
			}
			if(!empty($as_egresadono))
			{
				if($lb_ok)
				{
					$ls_criterio= $ls_criterio." OR sno_personalnomina.staper='3' ";
				}
				else
				{
					$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='3' ";
					$lb_ok=true;
				}
			}
			if(!empty($as_suspendidono))
			{
				if($lb_ok)
				{
					$ls_criterio= $ls_criterio." OR sno_personalnomina.staper='4' ";
				}
				else
				{
					$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='4' ";
					$lb_ok=true;
				}
			}
			if($lb_ok)
			{
				$ls_criterio= $ls_criterio." )";
				$lb_ok=false;
			}
		}
		if(!empty($as_codperdes))
		{
			$ls_criterio= $ls_criterio." AND sno_personal.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio." AND sno_personal.codper<='".$as_codperhas."'";
		}
		if(!empty($as_mes))
		{
			$ls_criterio= $ls_criterio." AND substr(sno_personal.fecnacper,6,2)='".$as_mes."'";
		}
		$lb_ok=false;
		if(!empty($as_activo))
		{
			$ls_criterio= $ls_criterio." AND (sno_personal.estper='1' ";
			$lb_ok=true;
		}
		if(!empty($as_egresado))
		{
			if($lb_ok)
			{
				$ls_criterio= $ls_criterio." OR sno_personal.estper='3' ";
			}
			else
			{
				$ls_criterio= $ls_criterio." AND (sno_personal.estper='3'  ";
				$lb_ok=true;
			}
		}
		if($lb_ok)
		{
			$ls_criterio= $ls_criterio.")";
		}
		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;
		}
		if((!empty($as_codnomdes))&&(!empty($as_codnomhas)))
		{ // si busco por nómina
			$ls_sql="SELECT sno_personal.codper, MAX(sno_personal.nomper) AS nomper, MAX(sno_personal.apeper) AS apeper, ".
					"		MAX(sno_personal.fecnacper) AS fecnacper ".
					"  FROM sno_personal, sno_personalnomina, sno_nomina ".
					" WHERE sno_personal.codemp = '".$this->ls_codemp."'".
					"   ".$ls_criterio.
					"   AND sno_personal.codemp = sno_personalnomina.codemp ".
					"	AND sno_personal.codper = sno_personalnomina.codper ".
					"   AND sno_personalnomina.codemp = sno_nomina.codemp ".
					"	AND sno_personalnomina.codnom = sno_nomina.codnom  ".
					" GROUP BY sno_personal.codper ".
					$ls_orden;
		}
		else
		{	// Si no busco por nómina
			$ls_sql="SELECT sno_personal.codper, MAX(sno_personal.nomper) AS nomper, MAX(sno_personal.apeper) AS apeper, ".
					"		MAX(sno_personal.fecnacper) AS fecnacper ".
					"  FROM sno_personal ".
					" WHERE sno_personal.codemp = '".$this->ls_codemp."'".
					"   ".$ls_criterio.
					" GROUP BY sno_personal.codper ".
					$ls_orden;
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_listadocumpleanos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_listadocumpleanos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_fichapersonal_personal($as_codnomdes,$as_codnomhas,$as_codperdes,$as_codperhas,$as_activo,$as_egresado,
									   $as_causaegreso,$as_activono,$as_vacacionesno,$as_suspendidono,$as_egresadono,
									   $as_masculino,$as_femenino,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_fichapersonal_personal
		//         Access: public (desde la clase sigesp_snorh_rpp_fichapersonal)  
		//	    Arguments: as_codnomdes // Código de la nómina donde se empieza a filtrar
		//	  			   as_codnomhas // Código de la nómina donde se termina de filtrar		  
		//	    		   as_codperdes // Código de personal donde se empieza a filtrar
		//	  			   as_codperhas // Código de personal donde se termina de filtrar		  
		//	  			   as_activo // Estatus Activo		  
		//	  			   as_egresado // Estatus Egresado
		//	  			   as_causaegreso // Causa del egreso
		//	  			   as_activono // Estatus Activo dentro de la nómina		  
		//	  			   as_vacacionesno // Estatus Vaciones dentro de la nómina
		//	  			   as_suspendidono // Estatus Suspendido dentro de la nómina
		//	  			   as_egresadono // Estatus Egresado dentro de la nómina
		//	  			   as_orden // Orden en que se quiere sacar el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/06/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		$lb_ok=false;
		if(!empty($as_codnomdes))
		{
			$ls_criterio= $ls_criterio." AND sno_personalnomina.codnom>='".$as_codnomdes."'";
			if(!empty($as_codnomhas))
			{
				$ls_criterio= $ls_criterio." AND sno_personalnomina.codnom<='".$as_codnomhas."'";
			}
			if(!empty($as_activono))
			{
				$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='1' ";
				$lb_ok=true;
			}
			if(!empty($as_vacacionesno))
			{
				if($lb_ok)
				{
					$ls_criterio= $ls_criterio." OR sno_personalnomina.staper='2' ";
				}
				else
				{
					$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='2' ";
					$lb_ok=true;
				}
			}
			if(!empty($as_egresadono))
			{
				if($lb_ok)
				{
					$ls_criterio= $ls_criterio." OR sno_personalnomina.staper='3' ";
				}
				else
				{
					$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='3' ";
					$lb_ok=true;
				}
			}
			if(!empty($as_suspendidono))
			{
				if($lb_ok)
				{
					$ls_criterio= $ls_criterio." OR sno_personalnomina.staper='4' ";
				}
				else
				{
					$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='4' ";
					$lb_ok=true;
				}
			}
			if($lb_ok)
			{
				$ls_criterio= $ls_criterio." )";
				$lb_ok=false;
			}
		}
		if(!empty($as_codperdes))
		{
			$ls_criterio= $ls_criterio." AND sno_personal.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio." AND sno_personal.codper<='".$as_codperhas."'";
		}
		if(!empty($as_activo))
		{
			$ls_criterio= $ls_criterio." AND (sno_personal.estper='1' ";
			$lb_ok=true;
		}
		if(!empty($as_egresado))
		{
			$ls_causaegreso="";
			if(!empty($as_causaegreso))
			{
				$ls_causaegreso=" AND sno_personal.cauegrper='".$as_causaegreso."'";
			}
			if($lb_ok)
			{
				$ls_criterio= $ls_criterio." OR (sno_personal.estper='3' ".$ls_causaegreso.") ";
			}
			else
			{
				$ls_criterio= $ls_criterio." AND (sno_personal.estper='3' ".$ls_causaegreso." ";
				$lb_ok=true;
			}
		}
		if($lb_ok)
		{
			$ls_criterio= $ls_criterio.")";
		}
		$lb_anterior=false;
		if(!empty($as_masculino))
		{
			$ls_criterio= $ls_criterio. " AND (sno_personal.sexper='M' ";
			$lb_anterior=true;
		}
		if(!empty($as_femenino))
		{
			if($lb_anterior)
			{
				$ls_criterio= $ls_criterio. " OR sno_personal.sexper='F' ";
			}
			else
			{
				$ls_criterio= $ls_criterio. " AND (sno_personal.sexper='F' ";
				$lb_anterior=true;
			}
		}
		if($lb_anterior)
		{
			$ls_criterio= $ls_criterio.")";
		}				
		$ls_campos="       (SELECT denasicar FROM sno_asignacioncargo ".
				   "   	     WHERE sno_personalnomina.codasicar<>'0000000' ".
				   "           AND sno_personalnomina.codemp = sno_asignacioncargo.codemp ".
				   "		   AND sno_personalnomina.codnom = sno_asignacioncargo.codnom ".
				   "           AND sno_personalnomina.codasicar = sno_asignacioncargo.codasicar) as denasicar, ".
				   "       (SELECT descar FROM sno_cargo ".
				   "   	     WHERE sno_personalnomina.codcar<>'0000000000' ".
				   "		   AND sno_personalnomina.codemp = sno_cargo.codemp ".
				   "		   AND sno_personalnomina.codnom = sno_cargo.codnom ".
				   "           AND sno_personalnomina.codcar = sno_cargo.codcar) as descar ";
		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;
		}
		$ls_sql="SELECT sno_personal.codper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, ".
				"       sno_personal.fecingper, sno_personal.dirper, sno_personal.coreleper, sno_personal.sexper, ".
				"		sno_personal.telhabper, sno_personal.telmovper, sno_personal.fecnacper, sno_personal.estper, ".
				"		sno_personal.nacper, sno_personal.edocivper, sno_personal.numhijper, sno_personal.fecegrper,	".
				"		sno_profesion.despro, sno_unidadadmin.desuniadm, sigesp_estados.desest, sigesp_municipio.denmun, ".$ls_campos.
				"  FROM sno_personal, sno_profesion, sno_personalnomina, sno_nomina, sno_unidadadmin, sigesp_estados, sigesp_municipio ".
				" WHERE sno_personal.codemp = '".$this->ls_codemp."'".
				"   ".$ls_criterio.
				"   AND sno_personal.codemp = sno_profesion.codemp ".
				"	AND sno_personal.codpro = sno_profesion.codpro ".
				"   AND sno_personal.codemp = sno_personalnomina.codemp ".
				"	AND sno_personal.codper = sno_personalnomina.codper ".
				"   AND sno_personalnomina.codemp = sno_nomina.codemp ".
				"	AND sno_personalnomina.codnom = sno_nomina.codnom  ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"	AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm  ".
				"	AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm  ".
				"	AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm  ".
				"	AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm  ".
				"	AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm  ".
				"   AND sno_personal.codpai = sigesp_estados.codpai ".
				"	AND sno_personal.codest = sigesp_estados.codest ".
				"   AND sno_personal.codpai = sigesp_municipio.codpai ".
				"	AND sno_personal.codest = sigesp_municipio.codest ".
				"	AND sno_personal.codmun = sigesp_municipio.codmun ".
				$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_fichapersonal_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_fichapersonal_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_credencialespersonal_personal($as_codperdes,$as_codperhas,$as_activo,$as_egresado,$as_causaegreso,$as_masculino,
											  $as_femenino,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_credencialespersonal_personal
		//         Access: public (desde la clase sigesp_snorh_rpp_credencialespersonal)  
		//	    Arguments: as_codperdes // Código de personal donde se empieza a filtrar
		//	  			   as_codperhas // Código de personal donde se termina de filtrar		  
		//	  			   as_activo // Estatus Activo		  
		//	  			   as_egresado // Estatus Egresado
		//	  			   as_causaegreso // Causa del egreso
		//	  			   as_orden // Orden en que se quiere sacar el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/05/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		$lb_ok=false;
		if(!empty($as_codperdes))
		{
			$ls_criterio= " AND sno_personal.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio." AND sno_personal.codper<='".$as_codperhas."'";
		}
		if(!empty($as_activo))
		{
			$ls_criterio= $ls_criterio." AND (sno_personal.estper='1' ";
			$lb_ok=true;
		}
		if(!empty($as_egresado))
		{
			$ls_causaegreso="";
			if(!empty($as_causaegreso))
			{
				$ls_causaegreso=" AND sno_personal.cauegrper='".$as_causaegreso."'";
			}
			if($lb_ok)
			{
				$ls_criterio= $ls_criterio." OR (sno_personal.estper='3' ".$ls_causaegreso.") ";
			}
			else
			{
				$ls_criterio= $ls_criterio." AND (sno_personal.estper='3' ".$ls_causaegreso." ";
				$lb_ok=true;
			}
		}
		if($lb_ok)
		{
			$ls_criterio= $ls_criterio.")";
		}
		$lb_anterior=false;
		if(!empty($as_masculino))
		{
			$ls_criterio= $ls_criterio. " AND (sno_personal.sexper='M' ";
			$lb_anterior=true;
		}
		if(!empty($as_femenino))
		{
			if($lb_anterior)
			{
				$ls_criterio= $ls_criterio. " OR sno_personal.sexper='F' ";
			}
			else
			{
				$ls_criterio= $ls_criterio. " AND (sno_personal.sexper='F' ";
				$lb_anterior=true;
			}
		}
		if($lb_anterior)
		{
			$ls_criterio= $ls_criterio.")";
		}				
		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;
		}
		$ls_sql="SELECT sno_personal.codper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personal.fecingper, ".
				"		sno_personal.estper, sno_personal.fecnacper, sno_personal.turper, sno_personal.horper, sno_personal.nivacaper, ".
				"		sno_personal.anoservpreper, sno_profesion.despro  ".
				"  FROM sno_personal, sno_profesion" .
				" WHERE sno_personal.codemp = '".$this->ls_codemp."'".
				"   ".$ls_criterio.
				"   AND sno_personal.codemp = sno_profesion.codemp ".
				"	AND sno_personal.codpro = sno_profesion.codpro ".
				$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_credencialespersonal_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_credencialespersonal_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_credencialespersonal_educacionformal($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_credencialespersonal_educacionformal
		//         Access: public (desde la clase sigesp_snorh_rpp_credencialespersonal)  
		//	    Arguments: as_codper  // Código de personal 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de la educación formal del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 09/05/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codestrea, tipestrea, insestrea, titestrea, feciniact, fecfinact, aprestrea, anoaprestrea ".
				"  FROM sno_estudiorealizado ".
				" WHERE codemp = '".$this->ls_codemp."'".
				"	AND codper = '".$as_codper."' ".
				"   AND (tipestrea='0' OR tipestrea='1' OR tipestrea='2' OR tipestrea='3' OR tipestrea='4' ".
				"	 OR tipestrea='5' OR tipestrea='6' OR tipestrea='7')".
				" ORDER BY tipestrea, feciniact ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_credencialespersonal_educacionformal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_credencialespersonal_educacionformal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_credencialespersonal_educacioninformal($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_credencialespersonal_educacioninformal
		//         Access: public (desde la clase sigesp_snorh_rpp_credencialespersonal)  
		//	    Arguments: as_codper  // Código de personal 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de la educación informal del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 14/05/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codestrea, tipestrea, insestrea, titestrea, feciniact, fecfinact, aprestrea, horestrea ".
				"  FROM sno_estudiorealizado ".
				" WHERE codemp = '".$this->ls_codemp."'".
				"	AND codper = '".$as_codper."' ".
				"   AND (tipestrea='8' OR tipestrea='9')".
				" ORDER BY tipestrea, feciniact ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_credencialespersonal_educacioninformal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_credencialespersonal_educacioninformal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_credencialespersonal_trabajosanterior($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_credencialespersonal_trabajosanterior
		//         Access: public (desde la clase sigesp_snorh_rpp_credencialespersonal)  
		//	    Arguments: as_codper  // Código de personal 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los trabajos anteriores del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 14/05/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codtraant, emptraant, ultcartraant, fecingtraant, fecrettraant, emppubtraant, anolab, meslab, dialab ".
				"  FROM sno_trabajoanterior ".
				" WHERE codemp = '".$this->ls_codemp."'".
				"	AND codper = '".$as_codper."' ".
				" ORDER BY fecingtraant ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_credencialespersonal_trabajosanterior ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_credencialespersonal_trabajosanterior
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_credencialespersonal_cargafamiliar($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_credencialespersonal_cargafamiliar
		//         Access: public (desde la clase sigesp_snorh_rpp_credencialespersonal)  
		//	    Arguments: as_codper  // Código de personal 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los hijos menores de 18 años del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 14/05/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ld_hoy=date('Y')."-".date('m')."-".date('d');
		$ld_fecha=date("Y-m-d", strtotime("$ld_hoy -18 year"));
		$ls_sql="SELECT cedfam, nomfam, apefam, sexfam, fecnacfam, nexfam ".
				"  FROM sno_familiar ".
				" WHERE codemp = '".$this->ls_codemp."'".
				"	AND codper = '".$as_codper."' ".
				"	AND nexfam = 'H' ".
				"   AND fecnacfam >= '".$ld_fecha."' ".
				" ORDER BY fecnacfam ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_credencialespersonal_trabajosanterior ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_credencialespersonal_cargafamiliar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_listadobanco_banco($as_codban,$as_suspendidos,$as_sc_cuenta,$as_ctaban,$as_codnomdes,$as_codnomhas,$as_codperdes,
								   $as_codperhas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listadobanco_banco
		//		   Access: public (desde la clase sigesp_snorh_rpp_listadobanco)  
		//	    Arguments: as_codban // Código del banco del que se desea busca el personal
		//	    		   as_suspendidos // si se busca a toto del personal ó solo los activos
		//	    		   as_sc_cuenta // cuenta contable del banco
		//	    		   as_ctaban // cuenta del banco
		//	    		   as_codnomdes // Código de Nómina Desde
		//	    		   as_codnomhas // Código de Nómina Hasta
		//	    		   as_codperdes // Código de Periodo Desde
		//	    		   as_codperhas // Código de Periodo Hasta
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del banco seleccionado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 18/05/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		if(!empty($as_codnomdes))
		{
			$ls_criterio = $ls_criterio." AND sno_hpersonalnomina.codnom>='".$as_codnomdes."' ";
		}
		if(!empty($as_codnomhas))
		{
			$ls_criterio = $ls_criterio." AND sno_hpersonalnomina.codnom<='".$as_codnomhas."' ";
		}
		if(!empty($as_codperdes))
		{
			$ls_criterio = $ls_criterio." AND sno_hpersonalnomina.codperi>='".$as_codperdes."' ";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio = $ls_criterio." AND sno_hpersonalnomina.codperi<='".$as_codperhas."' ";
		}
		if(!empty($as_codban))
		{
			$ls_criterio = $ls_criterio." AND sno_hpersonalnomina.codban='".$as_codban."' ";
		}
		if($as_suspendidos=="1") // Mostrar solo el personal suspendido
		{
			$ls_criterio = $ls_criterio." AND (sno_hpersonalnomina.staper='1' OR sno_hpersonalnomina.staper='2')";
		}
		$ls_sql="SELECT scb_banco.codban, scb_banco.nomban ".
				"  FROM sno_hpersonalnomina, sno_hresumen, scb_banco  ".
				" WHERE sno_hpersonalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_hpersonalnomina.anocur='".substr($_SESSION["la_empresa"]["periodo"],0,4)."' ".
				"   AND (sno_hpersonalnomina.pagbanper=1 OR sno_hpersonalnomina.pagbanper=1) ".
				"   AND sno_hpersonalnomina.pagefeper=0 ".
				"   AND sno_hresumen.monnetres > 0 ".
				$ls_criterio.
				"   AND sno_hpersonalnomina.codemp = sno_hresumen.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hresumen.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hresumen.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hresumen.codperi ".
				"   AND sno_hpersonalnomina.codper = sno_hresumen.codper ".
				"   AND sno_hpersonalnomina.codemp = scb_banco.codemp ".
				"   AND sno_hpersonalnomina.codban = scb_banco.codban ".
				" GROUP BY scb_banco.codban, scb_banco.nomban ".
				" ORDER BY scb_banco.nomban ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_listadobanco_banco ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);	
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_listadobanco_banco
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_listadobanco_personal($as_codban,$as_suspendidos,$as_tipcueban,$as_quincena,$as_codnomdes,$as_codnomhas,
								      $as_codperdes,$as_codperhas,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listadobanco_personal
		//		   Access: public (desde la clase sigesp_sno_rpp_listadobanco)  
		//	    Arguments: as_codban // Código del banco del que se desea busca el personal
		//	    		   as_suspendidos // si se busca a toto del personal ó solo los activos
		//	    		   as_tipcueban // tipo de cuenta bancaria (Ahorro,  Corriente, Activos liquidos)
		//	  			   as_quincena // Quincena para el cual se quiere filtrar
		//	    		   as_codnomdes // Código de Nómina Desde
		//	    		   as_codnomhas // Código de Nómina Hasta
		//	    		   as_codperdes // Código de Periodo Desde
		//	    		   as_codperhas // Código de Periodo Hasta
		//	  			   as_orden // Orden del reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que tienen asociado el banco 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/05/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		$ls_monto="";
		switch($as_quincena)
		{
			case 1: // Primera Quincena
				$ls_monto="SUM(sno_hresumen.priquiresaux) as monnetres";
				break;

			case 2: // Segunda Quincena
				$ls_monto="SUM(sno_hresumen.segquiresaux) as monnetres";
				break;

			case 3: // Mes Completo
				$ls_monto="SUM(sno_hresumen.monnetresaux) as monnetres";
				break;
		}
		if(!empty($as_codnomdes))
		{
			$ls_criterio = $ls_criterio." AND sno_hpersonalnomina.codnom>='".$as_codnomdes."' ";
		}
		if(!empty($as_codnomhas))
		{
			$ls_criterio = $ls_criterio." AND sno_hpersonalnomina.codnom<='".$as_codnomhas."' ";
		}
		if(!empty($as_codperdes))
		{
			$ls_criterio = $ls_criterio." AND sno_hpersonalnomina.codperi>='".$as_codperdes."' ";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio = $ls_criterio." AND sno_hpersonalnomina.codperi<='".$as_codperhas."' ";
		}
		if(!empty($as_codban))
		{
			$ls_criterio = $ls_criterio." AND sno_hpersonalnomina.codban='".$as_codban."' ";
		}
		if($as_suspendidos=="1") // Mostrar solo el personal suspendido
		{
			$ls_criterio = $ls_criterio." AND (sno_hpersonalnomina.staper='1' OR sno_hpersonalnomina.staper='2')";
		}
		switch($as_tipcueban)
		{
			case "A": // Cuenta de Ahorro
				$ls_criterio = $ls_criterio." AND sno_hpersonalnomina.tipcuebanper='A' ";
				break;
				
			case "C": // Cuenta corriente
				$ls_criterio = $ls_criterio." AND sno_hpersonalnomina.tipcuebanper='C' ";
				break;

			case "L": // Cuenta Activos Líquidos
				$ls_criterio = $ls_criterio." AND sno_hpersonalnomina.tipcuebanper='L' ";
				break;
		}
		switch($as_orden)
		{
			case "1": // Ordena por Código del Personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido del Personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre del Personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;

			case "4": // Ordena por Cédula del Personal
				$ls_orden="ORDER BY sno_personal.cedper ";
				break;
		}
		$ls_sql="SELECT sno_personal.cedper, MAX(sno_personal.nomper) AS nomper, MAX(sno_personal.apeper) AS apeper, ".$ls_monto.", ".
				"       MAX(sno_hpersonalnomina.codcueban) AS codcueban ".
				"  FROM sno_personal, sno_hpersonalnomina, sno_hresumen  ".
				" WHERE sno_hpersonalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_hpersonalnomina.anocur='".substr($_SESSION["la_empresa"]["periodo"],0,4)."' ".
				"   AND sno_hpersonalnomina.pagbanper=1 ".
				"   AND sno_hpersonalnomina.pagefeper=0 ".
				"   AND sno_hpersonalnomina.pagtaqper=0 ".
				"   AND sno_hresumen.monnetres > 0 ".
				$ls_criterio.
				"	AND sno_hpersonalnomina.codemp = sno_hresumen.codemp ".
				"	AND sno_hpersonalnomina.anocur = sno_hresumen.anocur ".
				"	AND sno_hpersonalnomina.codperi = sno_hresumen.codperi ".
				"   AND sno_hpersonalnomina.codnom = sno_hresumen.codnom ".
				"   AND sno_hpersonalnomina.codper = sno_hresumen.codper ".
				"   AND sno_personal.codemp = sno_hpersonalnomina.codemp ".
				"	AND sno_personal.codper = sno_hpersonalnomina.codper ".
				" GROUP BY sno_personal.codper, sno_personal.cedper, sno_personal.apeper, sno_personal.nomper ".
				$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_listadobanco_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_listadobanco_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_listadobancotaquilla_personal($as_codban,$as_suspendidos,$as_quincena,$as_codnomdes,$as_codnomhas,
								      		  $as_codperdes,$as_codperhas,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listadobancotaquilla_personal
		//		   Access: public (desde la clase sigesp_sno_rpp_listadobanco)  
		//	    Arguments: as_codban // Código del banco del que se desea busca el personal
		//	    		   as_suspendidos // si se busca a toto del personal ó solo los activos
		//	  			   as_quincena // Quincena para el cual se quiere filtrar
		//	    		   as_codnomdes // Código de Nómina Desde
		//	    		   as_codnomhas // Código de Nómina Hasta
		//	    		   as_codperdes // Código de Periodo Desde
		//	    		   as_codperhas // Código de Periodo Hasta
		//	  			   as_orden // Orden del reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que tienen asociado el banco 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/09/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		$ls_monto="";
		switch($as_quincena)
		{
			case 1: // Primera Quincena
				$ls_monto="SUM(sno_hresumen.priquiresaux) as monnetres";
				break;

			case 2: // Segunda Quincena
				$ls_monto="SUM(sno_hresumen.segquiresaux) as monnetres";
				break;

			case 3: // Mes Completo
				$ls_monto="SUM(sno_hresumen.monnetresaux) as monnetres";
				break;
		}
		if(!empty($as_codnomdes))
		{
			$ls_criterio = $ls_criterio." AND sno_hpersonalnomina.codnom>='".$as_codnomdes."' ";
		}
		if(!empty($as_codnomhas))
		{
			$ls_criterio = $ls_criterio." AND sno_hpersonalnomina.codnom<='".$as_codnomhas."' ";
		}
		if(!empty($as_codperdes))
		{
			$ls_criterio = $ls_criterio." AND sno_hpersonalnomina.codperi>='".$as_codperdes."' ";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio = $ls_criterio." AND sno_hpersonalnomina.codperi<='".$as_codperhas."' ";
		}
		if(!empty($as_codban))
		{
			$ls_criterio = $ls_criterio." AND sno_hpersonalnomina.codban='".$as_codban."' ";
		}
		if($as_suspendidos=="1") // Mostrar solo el personal suspendido
		{
			$ls_criterio = $ls_criterio." AND (sno_hpersonalnomina.staper='1' OR sno_hpersonalnomina.staper='2')";
		}
		switch($as_orden)
		{
			case "1": // Ordena por Código del Personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido del Personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre del Personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;

			case "4": // Ordena por Cédula del Personal
				$ls_orden="ORDER BY sno_personal.cedper ";
				break;
		}
		$ls_sql="SELECT sno_personal.cedper, MAX(sno_personal.nomper) AS nomper, MAX(sno_personal.apeper) AS apeper, ".$ls_monto.", ".
				"       MAX(sno_hpersonalnomina.codcueban) AS codcueban ".
				"  FROM sno_personal, sno_hpersonalnomina, sno_hresumen  ".
				" WHERE sno_hpersonalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_hpersonalnomina.anocur='".substr($_SESSION["la_empresa"]["periodo"],0,4)."' ".
				"   AND sno_hpersonalnomina.pagbanper=0 ".
				"   AND sno_hpersonalnomina.pagefeper=0 ".
				"   AND sno_hpersonalnomina.pagtaqper=1 ".
				"   AND sno_hresumen.monnetres > 0 ".
				$ls_criterio.
				"	AND sno_hpersonalnomina.codemp = sno_hresumen.codemp ".
				"	AND sno_hpersonalnomina.anocur = sno_hresumen.anocur ".
				"	AND sno_hpersonalnomina.codperi = sno_hresumen.codperi ".
				"   AND sno_hpersonalnomina.codnom = sno_hresumen.codnom ".
				"   AND sno_hpersonalnomina.codper = sno_hresumen.codper ".
				"   AND sno_personal.codemp = sno_hpersonalnomina.codemp ".
				"	AND sno_personal.codper = sno_hpersonalnomina.codper ".
				" GROUP BY sno_personal.codper, sno_personal.cedper, sno_personal.apeper, sno_personal.nomper ".
				$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_listadobancotaquilla_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_listadobancotaquilla_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_antiguedadpersonal_personal($as_codnomdes,$as_codnomhas,$as_codperdes,$as_codperhas,$as_activo,$as_egresado,
										    $as_causaegreso,$as_activono,$as_vacacionesno,$as_suspendidono,$as_egresadono,
										    $as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_antiguedadpersonal_personal
		//         Access: public (desde la clase sigesp_snorh_rpp_antiguedadpersonal)  
		//	    Arguments: as_codnomdes // Código de la nómina donde se empieza a filtrar
		//	  			   as_codnomhas // Código de la nómina donde se termina de filtrar		  
		//	    		   as_codperdes // Código de personal donde se empieza a filtrar
		//	  			   as_codperhas // Código de personal donde se termina de filtrar		  
		//	  			   as_activo // Estatus Activo		  
		//	  			   as_egresado // Estatus Egresado
		//	  			   as_causaegreso // Causa del egreso
		//	  			   as_activono // Estatus Activo dentro de la nómina		  
		//	  			   as_vacacionesno // Estatus Vaciones dentro de la nómina
		//	  			   as_suspendidono // Estatus Suspendido dentro de la nómina
		//	  			   as_egresadono // Estatus Egresado dentro de la nómina
		//	  			   as_orden // Orden en que se quiere sacar el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/06/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		$lb_ok=false;
		if(!empty($as_codnomdes))
		{
			$ls_criterio= $ls_criterio." AND sno_personalnomina.codnom>='".$as_codnomdes."'";
			if(!empty($as_codnomhas))
			{
				$ls_criterio= $ls_criterio." AND sno_personalnomina.codnom<='".$as_codnomhas."'";
			}
			if(!empty($as_activono))
			{
				$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='1' ";
				$lb_ok=true;
			}
			if(!empty($as_vacacionesno))
			{
				if($lb_ok)
				{
					$ls_criterio= $ls_criterio." OR sno_personalnomina.staper='2' ";
				}
				else
				{
					$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='2' ";
					$lb_ok=true;
				}
			}
			if(!empty($as_egresadono))
			{
				if($lb_ok)
				{
					$ls_criterio= $ls_criterio." OR sno_personalnomina.staper='3' ";
				}
				else
				{
					$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='3' ";
					$lb_ok=true;
				}
			}
			if(!empty($as_suspendidono))
			{
				if($lb_ok)
				{
					$ls_criterio= $ls_criterio." OR sno_personalnomina.staper='4' ";
				}
				else
				{
					$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='4' ";
					$lb_ok=true;
				}
			}
			if($lb_ok)
			{
				$ls_criterio= $ls_criterio." )";
				$lb_ok=false;
			}
		}
		if(!empty($as_codperdes))
		{
			$ls_criterio= $ls_criterio." AND sno_personal.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio." AND sno_personal.codper<='".$as_codperhas."'";
		}
		if(!empty($as_activo))
		{
			$ls_criterio= $ls_criterio." AND (sno_personal.estper='1' ";
			$lb_ok=true;
		}
		if(!empty($as_egresado))
		{
			$ls_causaegreso="";
			if(!empty($as_causaegreso))
			{
				$ls_causaegreso=" AND sno_personal.cauegrper='".$as_causaegreso."'";
			}
			if($lb_ok)
			{
				$ls_criterio= $ls_criterio." OR (sno_personal.estper='3' ".$ls_causaegreso.") ";
			}
			else
			{
				$ls_criterio= $ls_criterio." AND (sno_personal.estper='3' ".$ls_causaegreso." ";
				$lb_ok=true;
			}
		}
		if($lb_ok)
		{
			$ls_criterio= $ls_criterio.")";
		}
		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;
		}
		$ls_sql="SELECT DISTINCT sno_personal.codper,sno_personal.nomper,sno_personal.apeper,sno_personal.fecingper, sno_nomina.racnom, ".
				"       (SELECT denasicar FROM sno_asignacioncargo ".
				"   	  WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"           AND sno_personalnomina.codemp = sno_asignacioncargo.codemp ".
				"		    AND sno_personalnomina.codnom = sno_asignacioncargo.codnom ".
				"           AND sno_personalnomina.codasicar = sno_asignacioncargo.codasicar) as denasicar, ".
				"       (SELECT descar FROM sno_cargo ".
				"   	  WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"		    AND sno_personalnomina.codemp = sno_cargo.codemp ".
				"		    AND sno_personalnomina.codnom = sno_cargo.codnom ".
				"           AND sno_personalnomina.codcar = sno_cargo.codcar) as descar ".
				"  FROM sno_personal, sno_personalnomina, sno_nomina ".
				" WHERE sno_personal.codemp = '".$this->ls_codemp."'".
				"   ".$ls_criterio.
				"	AND sno_nomina.espnom='0' ".
				"   AND sno_personal.codemp = sno_personalnomina.codemp ".
				"	AND sno_personal.codper = sno_personalnomina.codper ".
				"   AND sno_personalnomina.codemp = sno_nomina.codemp ".
				"	AND sno_personalnomina.codnom = sno_nomina.codnom ".
				$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_antiguedadpersonal_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_antiguedadpersonal_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_permisospersonal_personal($as_codnomdes,$as_codnomhas,$as_codperdes,$as_codperhas,$as_activo,$as_egresado,
										 $as_causaegreso,$as_activono,$as_vacacionesno,$as_suspendidono,$as_egresadono,
										 $as_masculino,$as_femenino,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_permisospersonal_personal
		//         Access: public (desde la clase sigesp_snorh_rpp_listadopermisos)  
		//	    Arguments: as_codnomdes // Código de la nómina donde se empieza a filtrar
		//	  			   as_codnomhas // Código de la nómina donde se termina de filtrar		  
		//	    		   as_codperdes // Código de personal donde se empieza a filtrar
		//	  			   as_codperhas // Código de personal donde se termina de filtrar		  
		//	  			   as_activo // Estatus Activo		  
		//	  			   as_egresado // Estatus Egresado
		//	  			   as_causaegreso // Causa del egreso
		//	  			   as_activono // Estatus Activo dentro de la nómina		  
		//	  			   as_vacacionesno // Estatus Vaciones dentro de la nómina
		//	  			   as_suspendidono // Estatus Suspendido dentro de la nómina
		//	  			   as_egresadono // Estatus Egresado dentro de la nómina
		//	  			   as_orden // Orden en que se quiere sacar el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 20/07/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		$lb_ok=false;
		if(!empty($as_codnomdes))
		{
			$ls_criterio= $ls_criterio." AND sno_personalnomina.codnom>='".$as_codnomdes."'";
			if(!empty($as_codnomhas))
			{
				$ls_criterio= $ls_criterio." AND sno_personalnomina.codnom<='".$as_codnomhas."'";
			}
			if(!empty($as_activono))
			{
				$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='1' ";
				$lb_ok=true;
			}
			if(!empty($as_vacacionesno))
			{
				if($lb_ok)
				{
					$ls_criterio= $ls_criterio." OR sno_personalnomina.staper='2' ";
				}
				else
				{
					$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='2' ";
					$lb_ok=true;
				}
			}
			if(!empty($as_egresadono))
			{
				if($lb_ok)
				{
					$ls_criterio= $ls_criterio." OR sno_personalnomina.staper='3' ";
				}
				else
				{
					$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='3' ";
					$lb_ok=true;
				}
			}
			if(!empty($as_suspendidono))
			{
				if($lb_ok)
				{
					$ls_criterio= $ls_criterio." OR sno_personalnomina.staper='4' ";
				}
				else
				{
					$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='4' ";
					$lb_ok=true;
				}
			}
			if($lb_ok)
			{
				$ls_criterio= $ls_criterio." )";
				$lb_ok=false;
			}
		}
		if(!empty($as_codperdes))
		{
			$ls_criterio= $ls_criterio." AND sno_personal.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio." AND sno_personal.codper<='".$as_codperhas."'";
		}
		if(!empty($as_activo))
		{
			$ls_criterio= $ls_criterio." AND (sno_personal.estper='1' ";
			$lb_ok=true;
		}
		if(!empty($as_egresado))
		{
			$ls_causaegreso="";
			if(!empty($as_causaegreso))
			{
				$ls_causaegreso=" AND sno_personal.cauegrper='".$as_causaegreso."'";
			}
			if($lb_ok)
			{
				$ls_criterio= $ls_criterio." OR (sno_personal.estper='3' ".$ls_causaegreso.") ";
			}
			else
			{
				$ls_criterio= $ls_criterio." AND (sno_personal.estper='3' ".$ls_causaegreso." ";
				$lb_ok=true;
			}
		}
		if($lb_ok)
		{
			$ls_criterio= $ls_criterio.")";
		}
		$lb_anterior=false;
		if(!empty($as_masculino))
		{
			$ls_criterio= $ls_criterio. " AND (sno_personal.sexper='M' ";
			$lb_anterior=true;
		}
		if(!empty($as_femenino))
		{
			if($lb_anterior)
			{
				$ls_criterio= $ls_criterio. " OR sno_personal.sexper='F' ";
			}
			else
			{
				$ls_criterio= $ls_criterio. " AND (sno_personal.sexper='F' ";
				$lb_anterior=true;
			}
		}
		if($lb_anterior)
		{
			$ls_criterio= $ls_criterio.")";
		}				
		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;
		}
		if((!empty($as_codnomdes))&&(!empty($as_codnomhas)))
		{	// si busco por nómina
			$ls_sql="SELECT sno_personal.codper, MAX(sno_personal.nomper) AS nomper, MAX(sno_personal.apeper) AS apeper, ".
					"		MAX(sno_personal.estper) AS estper, MAX(sno_personal.cedper) AS cedper, MAX(sno_personal.dirper) AS dirper, ".
					"		MAX(sno_personal.telhabper) AS telhabper, MAX(sno_personal.telmovper) AS telmovper, MAX(sno_personal.coreleper) AS coreleper ".
					"  FROM sno_personal, sno_personalnomina, sno_permiso  ".
					" WHERE sno_personal.codemp = '".$this->ls_codemp."'".
					"   ".$ls_criterio.
					"   AND sno_personal.codemp = sno_personalnomina.codemp ".
					"	AND sno_personal.codper = sno_personalnomina.codper ".
					"   AND sno_personal.codemp = sno_permiso.codemp ".
					"	AND sno_personal.codper = sno_permiso.codper ".
					" GROUP BY sno_personal.codper ".
					$ls_orden;
		}
		else
		{	// Si no busco por nómina
			$ls_sql="SELECT sno_personal.codper, MAX(sno_personal.nomper) AS nomper, MAX(sno_personal.apeper) AS apeper, ".
					"		MAX(sno_personal.estper) AS estper, MAX(sno_personal.cedper) AS cedper, MAX(sno_personal.dirper) AS dirper, ".
					"		MAX(sno_personal.telhabper) AS telhabper, MAX(sno_personal.telmovper) AS telmovper, MAX(sno_personal.coreleper) AS coreleper ".
					"  FROM sno_personal, sno_permiso ".
					" WHERE sno_personal.codemp = '".$this->ls_codemp."'".
					"   ".$ls_criterio.
					"   AND sno_personal.codemp = sno_permiso.codemp ".
					"	AND sno_personal.codper = sno_permiso.codper ".
					" GROUP BY sno_personal.codper ".
					$ls_orden;
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_permisospersonal_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_permisospersonal_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_permisospersonal_permiso($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_permisospersonal_permiso
		//         Access: public (desde la clase sigesp_snorh_rpp_listadopermisos)  
		//	    Arguments: as_codper // Código del personal
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los permisos del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 20/07/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT numper, feciniper, fecfinper, numdiaper, afevacper, tipper, obsper, remper ".
				"  FROM sno_permiso ".
				" WHERE codemp = '".$this->ls_codemp."'".
				"	AND codper = '".$as_codper."' ".
				" ORDER BY codper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_permisospersonal_permiso ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_permisospersonal_permiso
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_trabajosanteriorespersonal_personal($as_codnomdes,$as_codnomhas,$as_codperdes,$as_codperhas,$as_activo,$as_egresado,		
													$as_causaegreso,$as_activono,$as_vacacionesno,$as_suspendidono,$as_egresadono,
										 			$as_masculino,$as_femenino,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_trabajosanteriorespersonal_personal
		//         Access: public (desde la clase sigesp_snorh_rpp_listadotrabajosanteriores)  
		//	    Arguments: as_codnomdes // Código de la nómina donde se empieza a filtrar
		//	  			   as_codnomhas // Código de la nómina donde se termina de filtrar		  
		//	    		   as_codperdes // Código de personal donde se empieza a filtrar
		//	  			   as_codperhas // Código de personal donde se termina de filtrar		  
		//	  			   as_activo // Estatus Activo		  
		//	  			   as_egresado // Estatus Egresado
		//	  			   as_causaegreso // Causa del egreso
		//	  			   as_activono // Estatus Activo dentro de la nómina		  
		//	  			   as_vacacionesno // Estatus Vaciones dentro de la nómina
		//	  			   as_suspendidono // Estatus Suspendido dentro de la nómina
		//	  			   as_egresadono // Estatus Egresado dentro de la nómina
		//	  			   as_orden // Orden en que se quiere sacar el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 23/07/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		$lb_ok=false;
		if(!empty($as_codnomdes))
		{
			$ls_criterio= $ls_criterio." AND sno_personalnomina.codnom>='".$as_codnomdes."'";
			if(!empty($as_codnomhas))
			{
				$ls_criterio= $ls_criterio." AND sno_personalnomina.codnom<='".$as_codnomhas."'";
			}
			if(!empty($as_activono))
			{
				$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='1' ";
				$lb_ok=true;
			}
			if(!empty($as_vacacionesno))
			{
				if($lb_ok)
				{
					$ls_criterio= $ls_criterio." OR sno_personalnomina.staper='2' ";
				}
				else
				{
					$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='2' ";
					$lb_ok=true;
				}
			}
			if(!empty($as_egresadono))
			{
				if($lb_ok)
				{
					$ls_criterio= $ls_criterio." OR sno_personalnomina.staper='3' ";
				}
				else
				{
					$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='3' ";
					$lb_ok=true;
				}
			}
			if(!empty($as_suspendidono))
			{
				if($lb_ok)
				{
					$ls_criterio= $ls_criterio." OR sno_personalnomina.staper='4' ";
				}
				else
				{
					$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='4' ";
					$lb_ok=true;
				}
			}
			if($lb_ok)
			{
				$ls_criterio= $ls_criterio." )";
				$lb_ok=false;
			}
		}
		if(!empty($as_codperdes))
		{
			$ls_criterio= $ls_criterio." AND sno_personal.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio." AND sno_personal.codper<='".$as_codperhas."'";
		}
		if(!empty($as_activo))
		{
			$ls_criterio= $ls_criterio." AND (sno_personal.estper='1' ";
			$lb_ok=true;
		}
		if(!empty($as_egresado))
		{
			$ls_causaegreso="";
			if(!empty($as_causaegreso))
			{
				$ls_causaegreso=" AND sno_personal.cauegrper='".$as_causaegreso."'";
			}
			if($lb_ok)
			{
				$ls_criterio= $ls_criterio." OR (sno_personal.estper='3' ".$ls_causaegreso.") ";
			}
			else
			{
				$ls_criterio= $ls_criterio." AND (sno_personal.estper='3' ".$ls_causaegreso." ";
				$lb_ok=true;
			}
		}
		if($lb_ok)
		{
			$ls_criterio= $ls_criterio.")";
		}
		$lb_anterior=false;
		if(!empty($as_masculino))
		{
			$ls_criterio= $ls_criterio. " AND (sno_personal.sexper='M' ";
			$lb_anterior=true;
		}
		if(!empty($as_femenino))
		{
			if($lb_anterior)
			{
				$ls_criterio= $ls_criterio. " OR sno_personal.sexper='F' ";
			}
			else
			{
				$ls_criterio= $ls_criterio. " AND (sno_personal.sexper='F' ";
				$lb_anterior=true;
			}
		}
		if($lb_anterior)
		{
			$ls_criterio= $ls_criterio.")";
		}				
		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;
		}
		if((!empty($as_codnomdes))&&(!empty($as_codnomhas)))
		{	// si busco por nómina
			$ls_sql="SELECT sno_personal.codper, MAX(sno_personal.nomper) AS nomper, MAX(sno_personal.apeper) AS apeper, ".
					"		MAX(sno_personal.estper) AS estper, MAX(sno_personal.cedper) AS cedper, MAX(sno_personal.dirper) AS dirper, ".
					"		MAX(sno_personal.telhabper) AS telhabper, MAX(sno_personal.telmovper) AS telmovper, MAX(sno_personal.coreleper) AS coreleper ".
					"  FROM sno_personal, sno_personalnomina, sno_trabajoanterior ".
					" WHERE sno_personal.codemp = '".$this->ls_codemp."'".
					"   ".$ls_criterio.
					"   AND sno_personal.codemp = sno_personalnomina.codemp ".
					"	AND sno_personal.codper = sno_personalnomina.codper ".
					"   AND sno_personal.codemp = sno_trabajoanterior.codemp ".
					"	AND sno_personal.codper = sno_trabajoanterior.codper ".
					" GROUP BY sno_personal.codper ".
					$ls_orden;
		}
		else
		{	// Si no busco por nómina
			$ls_sql="SELECT sno_personal.codper, MAX(sno_personal.nomper) AS nomper, MAX(sno_personal.apeper) AS apeper, ".
					"		MAX(sno_personal.estper) AS estper, MAX(sno_personal.cedper) AS cedper, MAX(sno_personal.dirper) AS dirper, ".
					"		MAX(sno_personal.telhabper) AS telhabper, MAX(sno_personal.telmovper) AS telmovper, MAX(sno_personal.coreleper) AS coreleper ".
					"  FROM sno_personal, sno_trabajoanterior ".
					" WHERE sno_personal.codemp = '".$this->ls_codemp."'".
					"   ".$ls_criterio.
					"   AND sno_personal.codemp = sno_trabajoanterior.codemp ".
					"	AND sno_personal.codper = sno_trabajoanterior.codper ".
					" GROUP BY sno_personal.codper ".
					$ls_orden;
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_trabajosanteriorespersonal_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_trabajosanteriorespersonal_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_trabajosanteriorespersonal_trabajo($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_trabajosanteriorespersonal_trabajo
		//         Access: public (desde la clase sigesp_snorh_rpp_listadotrabajosanteriores)  
		//	    Arguments: as_codper // Código del personal
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los Trabajos anteriores del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 23/07/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codtraant, emptraant, ultcartraant, ultsuetraant, fecingtraant, fecrettraant, emppubtraant, anolab, meslab, dialab ".
				"  FROM sno_trabajoanterior ".
				" WHERE codemp = '".$this->ls_codemp."'".
				"	AND codper = '".$as_codper."' ".
				" ORDER BY codper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_trabajosanteriorespersonal_trabajo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_trabajosanteriorespersonal_trabajo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_estudiospersonal_personal($as_codnomdes,$as_codnomhas,$as_codperdes,$as_codperhas,$as_activo,$as_egresado,		
										  $as_causaegreso,$as_activono,$as_vacacionesno,$as_suspendidono,$as_egresadono,
										  $as_masculino,$as_femenino,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_estudiospersonal_personal
		//         Access: public (desde la clase sigesp_snorh_rpp_listadoestudios)  
		//	    Arguments: as_codnomdes // Código de la nómina donde se empieza a filtrar
		//	  			   as_codnomhas // Código de la nómina donde se termina de filtrar		  
		//	    		   as_codperdes // Código de personal donde se empieza a filtrar
		//	  			   as_codperhas // Código de personal donde se termina de filtrar		  
		//	  			   as_activo // Estatus Activo		  
		//	  			   as_egresado // Estatus Egresado
		//	  			   as_causaegreso // Causa del egreso
		//	  			   as_activono // Estatus Activo dentro de la nómina		  
		//	  			   as_vacacionesno // Estatus Vaciones dentro de la nómina
		//	  			   as_suspendidono // Estatus Suspendido dentro de la nómina
		//	  			   as_egresadono // Estatus Egresado dentro de la nómina
		//	  			   as_orden // Orden en que se quiere sacar el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 23/07/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		$lb_ok=false;
		if(!empty($as_codnomdes))
		{
			$ls_criterio= $ls_criterio." AND sno_personalnomina.codnom>='".$as_codnomdes."'";
			if(!empty($as_codnomhas))
			{
				$ls_criterio= $ls_criterio." AND sno_personalnomina.codnom<='".$as_codnomhas."'";
			}
			if(!empty($as_activono))
			{
				$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='1' ";
				$lb_ok=true;
			}
			if(!empty($as_vacacionesno))
			{
				if($lb_ok)
				{
					$ls_criterio= $ls_criterio." OR sno_personalnomina.staper='2' ";
				}
				else
				{
					$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='2' ";
					$lb_ok=true;
				}
			}
			if(!empty($as_egresadono))
			{
				if($lb_ok)
				{
					$ls_criterio= $ls_criterio." OR sno_personalnomina.staper='3' ";
				}
				else
				{
					$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='3' ";
					$lb_ok=true;
				}
			}
			if(!empty($as_suspendidono))
			{
				if($lb_ok)
				{
					$ls_criterio= $ls_criterio." OR sno_personalnomina.staper='4' ";
				}
				else
				{
					$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='4' ";
					$lb_ok=true;
				}
			}
			if($lb_ok)
			{
				$ls_criterio= $ls_criterio." )";
				$lb_ok=false;
			}
		}
		if(!empty($as_codperdes))
		{
			$ls_criterio= $ls_criterio." AND sno_personal.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio." AND sno_personal.codper<='".$as_codperhas."'";
		}
		if(!empty($as_activo))
		{
			$ls_criterio= $ls_criterio." AND (sno_personal.estper='1' ";
			$lb_ok=true;
		}
		if(!empty($as_egresado))
		{
			$ls_causaegreso="";
			if(!empty($as_causaegreso))
			{
				$ls_causaegreso=" AND sno_personal.cauegrper='".$as_causaegreso."'";
			}
			if($lb_ok)
			{
				$ls_criterio= $ls_criterio." OR (sno_personal.estper='3' ".$ls_causaegreso.") ";
			}
			else
			{
				$ls_criterio= $ls_criterio." AND (sno_personal.estper='3' ".$ls_causaegreso." ";
				$lb_ok=true;
			}
		}
		if($lb_ok)
		{
			$ls_criterio= $ls_criterio.")";
		}
		$lb_anterior=false;
		if(!empty($as_masculino))
		{
			$ls_criterio= $ls_criterio. " AND (sno_personal.sexper='M' ";
			$lb_anterior=true;
		}
		if(!empty($as_femenino))
		{
			if($lb_anterior)
			{
				$ls_criterio= $ls_criterio. " OR sno_personal.sexper='F' ";
			}
			else
			{
				$ls_criterio= $ls_criterio. " AND (sno_personal.sexper='F' ";
				$lb_anterior=true;
			}
		}
		if($lb_anterior)
		{
			$ls_criterio= $ls_criterio.")";
		}				
		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;
		}
		if((!empty($as_codnomdes))&&(!empty($as_codnomhas)))
		{	// si busco por nómina
			$ls_sql="SELECT sno_personal.codper, MAX(sno_personal.nomper) AS nomper, MAX(sno_personal.apeper) AS apeper, ".
					"		MAX(sno_personal.estper) AS estper, MAX(sno_personal.cedper) AS cedper, MAX(sno_personal.dirper) AS dirper, ".
					"		MAX(sno_personal.telhabper) AS telhabper, MAX(sno_personal.telmovper) AS telmovper, MAX(sno_personal.coreleper) AS coreleper ".
					"  FROM sno_personal, sno_personalnomina, sno_estudiorealizado ".
					" WHERE sno_personal.codemp = '".$this->ls_codemp."'".
					"   ".$ls_criterio.
					"   AND sno_personal.codemp = sno_personalnomina.codemp ".
					"	AND sno_personal.codper = sno_personalnomina.codper ".
					"   AND sno_personal.codemp = sno_estudiorealizado.codemp ".
					"	AND sno_personal.codper = sno_estudiorealizado.codper ".
					" GROUP BY sno_personal.codper ".
					$ls_orden;
		}
		else
		{	// Si no busco por nómina
			$ls_sql="SELECT sno_personal.codper, MAX(sno_personal.nomper) AS nomper, MAX(sno_personal.apeper) AS apeper, ".
					"		MAX(sno_personal.estper) AS estper, MAX(sno_personal.cedper) AS cedper, MAX(sno_personal.dirper) AS dirper, ".
					"		MAX(sno_personal.telhabper) AS telhabper, MAX(sno_personal.telmovper) AS telmovper, MAX(sno_personal.coreleper) AS coreleper ".
					"  FROM sno_personal, sno_estudiorealizado ".
					" WHERE sno_personal.codemp = '".$this->ls_codemp."'".
					"   ".$ls_criterio.
					"   AND sno_personal.codemp = sno_estudiorealizado.codemp ".
					"	AND sno_personal.codper = sno_estudiorealizado.codper ".
					" GROUP BY sno_personal.codper ".
					$ls_orden;
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_estudiospersonal_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_estudiospersonal_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_estudiospersonal_estudios($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_estudiospersonal_estudios
		//         Access: public (desde la clase sigesp_snorh_rpp_listadoestudios)  
		//	    Arguments: as_codper // Código del personal
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los Trabajos anteriores del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 23/07/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codestrea, tipestrea, insestrea, desestrea, titestrea, calestrea, fecgraestrea, feciniact, fecfinact ".
				"  FROM sno_estudiorealizado ".
				" WHERE codemp = '".$this->ls_codemp."'".
				"	AND codper = '".$as_codper."' ".
				" ORDER BY codper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_estudiospersonal_estudios ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_estudiospersonal_estudios
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_listadopersonalunidadadm_personal($as_codnomdes,$as_codnomhas,$as_codperdes,$as_codperhas,$as_activo,$as_egresado,
										 		  $as_causaegreso,$as_activono,$as_vacacionesno,$as_suspendidono,$as_egresadono,
										 		  $as_masculino,$as_femenino,$as_coduniadmdes,$as_coduniadmhas,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listadopersonalunidadadm_personal
		//         Access: public (desde la clase sigesp_snorh_rpp_listadopersonal)  
		//	    Arguments: as_codnomdes // Código de la nómina donde se empieza a filtrar
		//	  			   as_codnomhas // Código de la nómina donde se termina de filtrar		  
		//	    		   as_codperdes // Código de personal donde se empieza a filtrar
		//	  			   as_codperhas // Código de personal donde se termina de filtrar		  
		//	  			   as_activo // Estatus Activo		  
		//	  			   as_egresado // Estatus Egresado
		//	  			   as_causaegreso // Causa del egreso
		//	  			   as_activono // Estatus Activo dentro de la nómina		  
		//	  			   as_vacacionesno // Estatus Vaciones dentro de la nómina
		//	  			   as_suspendidono // Estatus Suspendido dentro de la nómina
		//	  			   as_egresadono // Estatus Egresado dentro de la nómina
		//	  			   as_masculino // Solo el personal masculino
		//	  			   as_femenino // Solo el personal femenino
		//	    		   as_coduniadmdes // Código de unidad administrativa donde se empieza a filtrar
		//	  			   as_coduniadmhas // Código de unidad administrativa donde se termina de filtrar		  
		//	  			   as_orden // Orden en que se quiere sacar el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 14/08/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		$lb_ok=false;
		if(!empty($as_codnomdes))
		{
			$ls_criterio= $ls_criterio." AND sno_personalnomina.codnom>='".$as_codnomdes."'";
			if(!empty($as_codnomhas))
			{
				$ls_criterio= $ls_criterio." AND sno_personalnomina.codnom<='".$as_codnomhas."'";
			}
			if(!empty($as_activono))
			{
				$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='1' ";
				$lb_ok=true;
			}
			if(!empty($as_vacacionesno))
			{
				if($lb_ok)
				{
					$ls_criterio= $ls_criterio." OR sno_personalnomina.staper='2' ";
				}
				else
				{
					$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='2' ";
					$lb_ok=true;
				}
			}
			if(!empty($as_egresadono))
			{
				if($lb_ok)
				{
					$ls_criterio= $ls_criterio." OR sno_personalnomina.staper='3' ";
				}
				else
				{
					$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='3' ";
					$lb_ok=true;
				}
			}
			if(!empty($as_suspendidono))
			{
				if($lb_ok)
				{
					$ls_criterio= $ls_criterio." OR sno_personalnomina.staper='4' ";
				}
				else
				{
					$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='4' ";
					$lb_ok=true;
				}
			}
			if($lb_ok)
			{
				$ls_criterio= $ls_criterio." )";
				$lb_ok=false;
			}
		}
		if(!empty($as_codperdes))
		{
			$ls_criterio= $ls_criterio." AND sno_personal.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio." AND sno_personal.codper<='".$as_codperhas."'";
		}
		if(!empty($as_coduniadmdes))
		{
			$ls_criterio= $ls_criterio." AND sno_personalnomina.codper>='".$as_codperdes."'";
		}
		if(!empty($as_coduniadmhas))
		{
			$ls_criterio= $ls_criterio." AND sno_personal.codper<='".$as_codperhas."'";
		}
		if(!empty($as_activo))
		{
			$ls_criterio= $ls_criterio." AND (sno_personal.estper='1' ";
			$lb_ok=true;
		}
		if(!empty($as_egresado))
		{
			$ls_causaegreso="";
			if(!empty($as_causaegreso))
			{
				$ls_causaegreso=" AND sno_personal.cauegrper='".$as_causaegreso."'";
			}
			if($lb_ok)
			{
				$ls_criterio= $ls_criterio." OR (sno_personal.estper='3' ".$ls_causaegreso.") ";
			}
			else
			{
				$ls_criterio= $ls_criterio." AND (sno_personal.estper='3' ".$ls_causaegreso." ";
				$lb_ok=true;
			}
		}
		if($lb_ok)
		{
			$ls_criterio= $ls_criterio.")";
		}
		$lb_anterior=false;
		if(!empty($as_masculino))
		{
			$ls_criterio= $ls_criterio. " AND (sno_personal.sexper='M' ";
			$lb_anterior=true;
		}
		if(!empty($as_femenino))
		{
			if($lb_anterior)
			{
				$ls_criterio= $ls_criterio. " OR sno_personal.sexper='F' ";
			}
			else
			{
				$ls_criterio= $ls_criterio. " AND (sno_personal.sexper='F' ";
				$lb_anterior=true;
			}
		}
		if($lb_anterior)
		{
			$ls_criterio= $ls_criterio.")";
		}				


		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;
		}
		$ls_sql="SELECT DISTINCT sno_personal.codper, sno_personal.nomper, sno_personal.apeper, sno_unidadadmin.desuniadm, ".
				"		sno_ubicacionfisica.desubifis, ".
				"		(SELECT despai FROM sigesp_pais ".
				"		  WHERE sigesp_pais.codpai = sno_ubicacionfisica.codpai) AS despai, ".
				"		(SELECT desest FROM sigesp_estados ".
				"		  WHERE sigesp_estados.codpai = sno_ubicacionfisica.codpai ".
				"			AND sigesp_estados.codest = sno_ubicacionfisica.codest) AS desest, ".
				"		(SELECT denmun FROM sigesp_municipio ".
				"		  WHERE sigesp_municipio.codpai = sno_ubicacionfisica.codpai ".
				"			AND sigesp_municipio.codest = sno_ubicacionfisica.codest ".
				"			AND sigesp_municipio.codmun = sno_ubicacionfisica.codmun) AS denmun, ".
				"		(SELECT denpar FROM sigesp_parroquia ".
				"		  WHERE sigesp_parroquia.codpai = sno_ubicacionfisica.codpai ".
				"			AND sigesp_parroquia.codest = sno_ubicacionfisica.codest ".
				"			AND sigesp_parroquia.codmun = sno_ubicacionfisica.codmun ".
				"			AND sigesp_parroquia.codpar = sno_ubicacionfisica.codpar) AS denpar ".
				"  FROM sno_personal, sno_personalnomina, sno_unidadadmin, sno_ubicacionfisica  ".
				" WHERE sno_personal.codemp = '".$this->ls_codemp."'".
				"   ".$ls_criterio.
				"   AND sno_personal.codemp = sno_personalnomina.codemp ".
				"	AND sno_personal.codper = sno_personalnomina.codper ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"	AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm  ".
				"	AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm  ".
				"	AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm  ".
				"	AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm  ".
				"	AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm  ".
				"   AND sno_personalnomina.codemp = sno_ubicacionfisica.codemp ".
				"	AND sno_personalnomina.codubifis = sno_ubicacionfisica.codubifis  ".
				$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_listadopersonalunidadadm_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_listadopersonalunidadadm_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_listadopersonalcontratado_personal($as_codnomdes,$as_codnomhas,$as_codperdes,$as_codperhas,$as_activo,$as_egresado,
										 		   $as_causaegreso,$as_activono,$as_vacacionesno,$as_suspendidono,$as_egresadono,
										 		   $as_masculino,$as_femenino,$ad_fecculcontrdes,$ad_fecculcontrhas,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listadopersonalcontratado_personal
		//         Access: public (desde la clase sigesp_snorh_rpp_listadopersonalcontratado)  
		//	    Arguments: as_codnomdes // Código de la nómina donde se empieza a filtrar
		//	  			   as_codnomhas // Código de la nómina donde se termina de filtrar		  
		//	    		   as_codperdes // Código de personal donde se empieza a filtrar
		//	  			   as_codperhas // Código de personal donde se termina de filtrar		  
		//	  			   as_activo // Estatus Activo		  
		//	  			   as_egresado // Estatus Egresado
		//	  			   as_causaegreso // Causa del egreso
		//	  			   as_activono // Estatus Activo dentro de la nómina		  
		//	  			   as_vacacionesno // Estatus Vaciones dentro de la nómina
		//	  			   as_suspendidono // Estatus Suspendido dentro de la nómina
		//	  			   as_egresadono // Estatus Egresado dentro de la nómina
		//	  			   ad_fecculcontrdes // Fecha de culminación de Contrato Desde
		//	  			   ad_fecculcontrhas // Fecha de culminación de Contrato Hasta
		//	  			   as_orden // Orden en que se quiere sacar el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 16/08/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		$lb_ok=false;
		if(!empty($as_codnomdes))
		{
			$ls_criterio= $ls_criterio." AND sno_personalnomina.codnom>='".$as_codnomdes."'";
			if(!empty($as_codnomhas))
			{
				$ls_criterio= $ls_criterio." AND sno_personalnomina.codnom<='".$as_codnomhas."'";
			}
			if(!empty($as_activono))
			{
				$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='1' ";
				$lb_ok=true;
			}
			if(!empty($as_vacacionesno))
			{
				if($lb_ok)
				{
					$ls_criterio= $ls_criterio." OR sno_personalnomina.staper='2' ";
				}
				else
				{
					$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='2' ";
					$lb_ok=true;
				}
			}
			if(!empty($as_egresadono))
			{
				if($lb_ok)
				{
					$ls_criterio= $ls_criterio." OR sno_personalnomina.staper='3' ";
				}
				else
				{
					$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='3' ";
					$lb_ok=true;
				}
			}
			if(!empty($as_suspendidono))
			{
				if($lb_ok)
				{
					$ls_criterio= $ls_criterio." OR sno_personalnomina.staper='4' ";
				}
				else
				{
					$ls_criterio= $ls_criterio." AND (sno_personalnomina.staper='4' ";
					$lb_ok=true;
				}
			}
			if($lb_ok)
			{
				$ls_criterio= $ls_criterio." )";
				$lb_ok=false;
			}
		}
		if(!empty($as_codperdes))
		{
			$ls_criterio= $ls_criterio." AND sno_personal.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio." AND sno_personal.codper<='".$as_codperhas."'";
		}
		if(!empty($as_activo))
		{
			$ls_criterio= $ls_criterio." AND (sno_personal.estper='1' ";
			$lb_ok=true;
		}
		if(!empty($as_egresado))
		{
			$ls_causaegreso="";
			if(!empty($as_causaegreso))
			{
				$ls_causaegreso=" AND sno_personal.cauegrper='".$as_causaegreso."'";
			}
			if($lb_ok)
			{
				$ls_criterio= $ls_criterio." OR (sno_personal.estper='3' ".$ls_causaegreso.") ";
			}
			else
			{
				$ls_criterio= $ls_criterio." AND (sno_personal.estper='3' ".$ls_causaegreso." ";
				$lb_ok=true;
			}
		}
		if($lb_ok)
		{
			$ls_criterio= $ls_criterio.")";
		}
		$lb_anterior=false;
		if(!empty($as_masculino))
		{
			$ls_criterio= $ls_criterio. " AND (sno_personal.sexper='M' ";
			$lb_anterior=true;
		}
		if(!empty($as_femenino))
		{
			if($lb_anterior)
			{
				$ls_criterio= $ls_criterio. " OR sno_personal.sexper='F' ";
			}
			else
			{
				$ls_criterio= $ls_criterio. " AND (sno_personal.sexper='F' ";
				$lb_anterior=true;
			}
		}
		if($lb_anterior)
		{
			$ls_criterio= $ls_criterio.")";
		}			
		if((!empty($ad_fecculcontrdes))&&($ad_fecculcontrdes!="1900-01-01"))
		{
			$ls_criterio= $ls_criterio." AND sno_personalnomina.fecculcontr>='".$ad_fecculcontrdes."'";
		}
		if((!empty($ad_fecculcontrhas))&&($ad_fecculcontrdes!="1900-01-01"))
		{
			$ls_criterio= $ls_criterio." AND sno_personalnomina.fecculcontr<='".$ad_fecculcontrhas."'";
		}
		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;
		}
		$ls_sql="SELECT DISTINCT sno_personal.codper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personal.fecingper, ".
				"		sno_personal.estper, sno_personal.nivacaper, sno_profesion.despro, sno_nomina.desnom, ".
				"		sno_personalnomina.staper AS estnom, sno_personalnomina.fecingper AS fecingnom, sno_personalnomina.fecculcontr  ".
				"  FROM sno_personal, sno_profesion, sno_personalnomina, sno_nomina ".
				" WHERE sno_personal.codemp = '".$this->ls_codemp."'".
				"	AND (sno_nomina.tipnom='2' OR sno_nomina.tipnom='4' OR sno_nomina.tipnom='6')".
				"   ".$ls_criterio.
				"   AND sno_personal.codemp = sno_profesion.codemp ".
				"	AND sno_personal.codpro = sno_profesion.codpro ".
				"   AND sno_personal.codemp = sno_personalnomina.codemp ".
				"	AND sno_personal.codper = sno_personalnomina.codper ".
				"   AND sno_personalnomina.codemp = sno_nomina.codemp ".
				"	AND sno_personalnomina.codnom = sno_nomina.codnom  ".
				$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_listadopersonalcontratado_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_listadopersonalcontratado_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_listadogenerico()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listadogenerico
		//         Access: public (desde la clase sigesp_snorh_rpp_listadopersonalgenerico)  
		//	    Arguments: 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/08/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT DISTINCT sno_personal.codper, ".$_SESSION["ls_campo"]." ".
				"  FROM sno_personal, sno_profesion, sno_personalnomina, sno_nomina, sno_cargo, sno_asignacioncargo, ".
				"		sno_dedicacion, sno_tipopersonal, sno_ubicacionfisica, sno_unidadadmin ".
				" WHERE sno_personal.codemp = '".$this->ls_codemp."'".
				"   ".$_SESSION["ls_criterio"].
				"   AND sno_personal.codemp = sno_profesion.codemp ".
				"	AND sno_personal.codpro = sno_profesion.codpro ".
				"   AND sno_personal.codemp = sno_personalnomina.codemp ".
				"	AND sno_personal.codper = sno_personalnomina.codper ".
				"   AND sno_personalnomina.codemp = sno_nomina.codemp ".
				"	AND sno_personalnomina.codnom = sno_nomina.codnom ".
				"   AND sno_personalnomina.codemp = sno_cargo.codemp ".
				"	AND sno_personalnomina.codnom = sno_cargo.codnom ".
				"	AND sno_personalnomina.codcar = sno_cargo.codcar ".
				"   AND sno_personalnomina.codemp = sno_asignacioncargo.codemp ".
				"	AND sno_personalnomina.codnom = sno_asignacioncargo.codnom ".
				"	AND sno_personalnomina.codasicar = sno_asignacioncargo.codasicar ".
				"   AND sno_personalnomina.codemp = sno_dedicacion.codemp ".
				"	AND sno_personalnomina.codded = sno_dedicacion.codded ".
				"   AND sno_personalnomina.codemp = sno_tipopersonal.codemp ".
				"	AND sno_personalnomina.codded = sno_tipopersonal.codded ".
				"	AND sno_personalnomina.codtipper = sno_tipopersonal.codtipper ".
				"   AND sno_personalnomina.codemp = sno_ubicacionfisica.codemp ".
				"	AND sno_personalnomina.codubifis = sno_ubicacionfisica.codubifis ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"	AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"	AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"	AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"	AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"	AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				" ORDER BY sno_personal.codper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_listadogenerico ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_listadogenerico
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_recibopago_personal($as_codnom,$ad_fecdesper,$ad_fechasper,$as_codperdes,$as_codperhas,
									$as_coduniadm,$as_conceptocero,$as_conceptop2,$as_conceptoreporte,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_recibopago_personal
		//         Access: public (desde la clase sigesp_sno_r_recibopago)  
		//	    Arguments: as_codnom // Código del nómina donde se va a filtrar
		//	    		   ad_fecdesper // Fecha del periodo donde se empieza a filtrar
		//	  			   ad_fechasper // Fecha del periodo donde se termina de filtrar		  
		//	    		   as_codperdes // Código del personal donde se empieza a filtrar
		//	  			   as_codperhas // Código del personal donde se termina de filtrar		  
		//	  			   as_coduniadm // Código de la unidad administrativa	  
		//	  			   as_conceptoreporte // criterio que me indica si se desea mostrar los conceptos de tipo reporte
		//	  			   as_orden // Orde a mostrar en el reporte		  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal que se le calculó la nómina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/09/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		if(!empty($ad_fecdesper))
		{
			$ls_criterio= $ls_criterio."AND sno_hperiodo.fecdesper>='".$this->io_funciones->uf_convertirdatetobd($ad_fecdesper)."'";
		}
		if(!empty($ad_fechasper))
		{
			$ls_criterio= $ls_criterio."   AND sno_hperiodo.fechasper<='".$this->io_funciones->uf_convertirdatetobd($ad_fechasper)."'";
		}
		if(!empty($as_codnom))
		{
			$ls_criterio= $ls_criterio."AND sno_hpersonalnomina.codnom='".$as_codnom."'";
		}
		if(!empty($as_codperdes))
		{
			$ls_criterio= $ls_criterio."AND sno_hpersonalnomina.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_hpersonalnomina.codper<='".$as_codperhas."'";
		}
		if(!empty($as_coduniadm))
		{
			$ls_criterio=$ls_criterio."   AND sno_hpersonalnomina.minorguniadm='".substr($as_coduniadm,0,4)."' ";
			$ls_criterio=$ls_criterio."   AND sno_hpersonalnomina.ofiuniadm='".substr($as_coduniadm,5,2)."' ";
			$ls_criterio=$ls_criterio."   AND sno_hpersonalnomina.uniuniadm='".substr($as_coduniadm,8,2)."' ";
			$ls_criterio=$ls_criterio."   AND sno_hpersonalnomina.depuniadm='".substr($as_coduniadm,11,2)."' ";
			$ls_criterio=$ls_criterio."   AND sno_hpersonalnomina.prouniadm='".substr($as_coduniadm,14,2)."' ";
		}
		if(!empty($as_conceptocero))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.valsal<>0 ";
		}
		if(!empty($as_conceptop2))
		{
			if(!empty($as_conceptoreporte))
			{
				$ls_criterio = $ls_criterio." AND (sno_hsalida.tipsal='A' OR sno_hsalida.tipsal='V1' OR sno_hsalida.tipsal='W1' OR ".
											"      sno_hsalida.tipsal='D' OR sno_hsalida.tipsal='V2' OR sno_hsalida.tipsal='W2' OR ".
											"      sno_hsalida.tipsal='P1' OR sno_hsalida.tipsal='V3' OR sno_hsalida.tipsal='W3' OR ".
											"	   sno_hsalida.tipsal='P2' OR sno_hsalida.tipsal='V4' OR sno_hsalida.tipsal='W4' OR ".
											"  	   sno_hsalida.tipsal='R')";
			}
			else
			{
				$ls_criterio = $ls_criterio." AND (sno_hsalida.tipsal='A' OR sno_hsalida.tipsal='V1' OR sno_hsalida.tipsal='W1' OR ".
											"      sno_hsalida.tipsal='D' OR sno_hsalida.tipsal='V2' OR sno_hsalida.tipsal='W2' OR ".
											"      sno_hsalida.tipsal='P1' OR sno_hsalida.tipsal='V3' OR sno_hsalida.tipsal='W3' OR ".
											"	   sno_hsalida.tipsal='P2' OR sno_hsalida.tipsal='V4' OR sno_hsalida.tipsal='W4') ";
			}
		}
		else
		{
			if(!empty($as_conceptoreporte))
			{
				$ls_criterio = $ls_criterio." AND (sno_hsalida.tipsal='A' OR sno_hsalida.tipsal='V1' OR sno_hsalida.tipsal='W1' OR ".
											"      sno_hsalida.tipsal='D' OR sno_hsalida.tipsal='V2' OR sno_hsalida.tipsal='W2' OR ".
											"      sno_hsalida.tipsal='P1' OR sno_hsalida.tipsal='V3' OR sno_hsalida.tipsal='W3' OR ".
											"  	   sno_hsalida.tipsal='R')";
			}
			else
			{
				$ls_criterio = $ls_criterio." AND (sno_hsalida.tipsal='A' OR sno_hsalida.tipsal='V1' OR sno_hsalida.tipsal='W1' OR ".
											"      sno_hsalida.tipsal='D' OR sno_hsalida.tipsal='V2' OR sno_hsalida.tipsal='W2' OR ".
											"      sno_hsalida.tipsal='P1' OR sno_hsalida.tipsal='V3' OR sno_hsalida.tipsal='W3')";
			}
		}
		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;
		}
		$ls_sql="SELECT DISTINCT sno_personal.codper, MAX(sno_personal.cedper) as cedper, max(sno_personal.nomper) as nomper, ".
				"       MAX(sno_personal.apeper) as apeper, MAX(sno_personal.nacper) as nacper, MAX(sno_hpersonalnomina.codcueban) AS codcueban, ".
				"       MAX(sno_personal.fecingper) as fecingper, sum(sno_hsalida.valsalaux) as total, MAX(sno_hunidadadmin.desuniadm) as desuniadm, ".
				"       MAX(sno_hunidadadmin.minorguniadm) as minorguniadm, MAX(sno_hunidadadmin.ofiuniadm) AS ofiuniadm, ".
				"       MAX(sno_hunidadadmin.uniuniadm) AS uniuniadm, MAX(sno_hunidadadmin.depuniadm) as depuniadm, MAX(sno_hunidadadmin.prouniadm) AS prouniadm, ".
				"       MAX(sno_hpersonalnomina.sueperaux) AS sueper, MAX(sno_hpersonalnomina.pagbanper) AS pagbanper, MAX(sno_hpersonalnomina.pagefeper) AS pagefeper, ".
				"       MAX(sno_ubicacionfisica.desubifis) AS desubifis, MAX(sno_hnomina.desnom) AS desnom, MAX(sno_hnomina.racnom) AS racnom, ".
				"		 MAX(sno_hnomina.adenom) AS adenom,".
				"		(SELECT nomban FROM scb_banco ".
				"		   WHERE scb_banco.codemp = sno_hpersonalnomina.codemp ".
				" 			 AND scb_banco.codban = sno_hpersonalnomina.codban) AS banco,".
				"       (SELECT MAX(denasicar) FROM sno_hasignacioncargo ".
				"   	  WHERE sno_hpersonalnomina.codemp = sno_hasignacioncargo.codemp ".
				"		    AND sno_hpersonalnomina.codnom = sno_hasignacioncargo.codnom ".
				"		    AND sno_hpersonalnomina.anocur = sno_hasignacioncargo.anocur ".
				"           AND sno_hpersonalnomina.codasicar = sno_hasignacioncargo.codasicar) as denasicar, ".
				"       (SELECT MAX(descar) FROM sno_hcargo ".
				"   	  WHERE sno_hpersonalnomina.codemp = sno_hcargo.codemp ".
				"		    AND sno_hpersonalnomina.codnom = sno_hcargo.codnom ".
				"		    AND sno_hpersonalnomina.anocur = sno_hcargo.anocur ".
				"           AND sno_hpersonalnomina.codcar = sno_hcargo.codcar) as descar ".
				"  FROM sno_personal, sno_hpersonalnomina, sno_hsalida, sno_hunidadadmin, sno_ubicacionfisica, sno_hnomina, sno_hperiodo ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"   AND (sno_hsalida.tipsal<>'P2' AND sno_hsalida.tipsal<>'V4' AND sno_hsalida.tipsal<>'W4' ) ".
				"   ".$ls_criterio." ".
				"   AND sno_hpersonalnomina.codemp = sno_hnomina.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hnomina.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hnomina.anocurnom ".
				"   AND sno_hpersonalnomina.codperi = sno_hnomina.peractnom ".
				"   AND sno_hnomina.codemp = sno_hperiodo.codemp ".
				"   AND sno_hnomina.codnom = sno_hperiodo.codnom ".
				"   AND sno_hnomina.anocurnom = sno_hperiodo.anocur ".
				"   AND sno_hnomina.peractnom = sno_hperiodo.codperi ".
				"   AND sno_hpersonalnomina.codemp = sno_personal.codemp ".
				"   AND sno_hpersonalnomina.codper = sno_personal.codper ".
				"   AND sno_hpersonalnomina.codemp = sno_ubicacionfisica.codemp ".
				"   AND sno_hpersonalnomina.codubifis = sno_ubicacionfisica.codubifis ".
				"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
				"   AND sno_hpersonalnomina.codemp = sno_hunidadadmin.codemp ".
				"   AND sno_hpersonalnomina.anocur = sno_hunidadadmin.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hunidadadmin.codperi ".
				"   AND sno_hpersonalnomina.minorguniadm = sno_hunidadadmin.minorguniadm ".
				"   AND sno_hpersonalnomina.ofiuniadm = sno_hunidadadmin.ofiuniadm ".
				"   AND sno_hpersonalnomina.uniuniadm = sno_hunidadadmin.uniuniadm ".
				"   AND sno_hpersonalnomina.depuniadm = sno_hunidadadmin.depuniadm ".
				"   AND sno_hpersonalnomina.prouniadm = sno_hunidadadmin.prouniadm ".
				" GROUP BY sno_hpersonalnomina.codemp, sno_personal.codper, sno_hpersonalnomina.codnom, sno_hpersonalnomina.codcar, ".        
				"          sno_hpersonalnomina.codasicar, sno_hpersonalnomina.anocur,sno_hpersonalnomina.codban ".
				"   ".$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_recibopago_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_recibopago_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_recibopago_conceptopersonal($as_codnom,$ad_fecdesper,$ad_fechasper,$as_codper,$as_conceptocero,$as_conceptop2,
											$as_conceptoreporte,$as_tituloconcepto)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_recibopago_conceptopersonal
		//         Access: public (desde la clase sigesp_sno_rpp_recibopago)  
		//	    Arguments: as_codnom // Código del nómina donde se va a filtrar
		//	    		   ad_fecdesper // Fecha del periodo donde se empieza a filtrar
		//	  			   ad_fechasper // Fecha del periodo donde se termina de filtrar		  
		//	    		   as_codper // Código del personal que se desea buscar la salida
		//	  			   as_conceptocero // criterio que me indica si se desea quitar los conceptos cuyo valor es cero
		//	  			   as_conceptop2 // criterio que me indica si se desea mostrar los conceptos de tipo aporte patronal
		//	  			   as_conceptoreporte // criterio que me indica si se desea mostrar los conceptos de tipo reporte
		//	  			   as_tituloconcepto // criterio que me indica si se desea mostrar los títulos de los conceptos
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los conceptos asociados al personal que se le calculó la nómina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 05/05/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_campo="sno_hconcepto.nomcon";
		if(!empty($ad_fecdesper))
		{
			$ls_criterio= $ls_criterio."   AND sno_hperiodo.fecdesper>='".$this->io_funciones->uf_convertirdatetobd($ad_fecdesper)."'";
		}
		if(!empty($ad_fechasper))
		{
			$ls_criterio= $ls_criterio."   AND sno_hperiodo.fechasper<='".$this->io_funciones->uf_convertirdatetobd($ad_fechasper)."'";
		}
		if(!empty($as_codnom))
		{
			$ls_criterio= $ls_criterio."   AND sno_hsalida.codnom='".$as_codnom."'";
		}
		if(!empty($as_tituloconcepto))
		{
			$ls_campo = "sno_hconcepto.titcon";
		}
		if(!empty($as_conceptocero))
		{
			$ls_criterio = $ls_criterio."   AND sno_hsalida.valsal<>0 ";
		}
		if(!empty($as_conceptop2))
		{
			if(!empty($as_conceptoreporte))
			{
				$ls_criterio = $ls_criterio." AND (sno_hsalida.tipsal='A' OR sno_hsalida.tipsal='V1' OR sno_hsalida.tipsal='W1' OR ".
											"      sno_hsalida.tipsal='D' OR sno_hsalida.tipsal='V2' OR sno_hsalida.tipsal='W2' OR ".
											"      sno_hsalida.tipsal='P1' OR sno_hsalida.tipsal='V3' OR sno_hsalida.tipsal='W3' OR ".
											"	   sno_hsalida.tipsal='P2' OR sno_hsalida.tipsal='V4' OR sno_hsalida.tipsal='W4' OR ".
											"  	   sno_hsalida.tipsal='R')";
			}
			else
			{
				$ls_criterio = $ls_criterio." AND (sno_hsalida.tipsal='A' OR sno_hsalida.tipsal='V1' OR sno_hsalida.tipsal='W1' OR ".
											"      sno_hsalida.tipsal='D' OR sno_hsalida.tipsal='V2' OR sno_hsalida.tipsal='W2' OR ".
											"      sno_hsalida.tipsal='P1' OR sno_hsalida.tipsal='V3' OR sno_hsalida.tipsal='W3' OR ".
											"	   sno_hsalida.tipsal='P2' OR sno_hsalida.tipsal='V4' OR sno_hsalida.tipsal='W4') ";
			}
		}
		else
		{
			if(!empty($as_conceptoreporte))
			{
				$ls_criterio = $ls_criterio." AND (sno_hsalida.tipsal='A' OR sno_hsalida.tipsal='V1' OR sno_hsalida.tipsal='W1' OR ".
											"      sno_hsalida.tipsal='D' OR sno_hsalida.tipsal='V2' OR sno_hsalida.tipsal='W2' OR ".
											"      sno_hsalida.tipsal='P1' OR sno_hsalida.tipsal='V3' OR sno_hsalida.tipsal='W3' OR ".
											"  	   sno_hsalida.tipsal='R')";
			}
			else
			{
				$ls_criterio = $ls_criterio." AND (sno_hsalida.tipsal='A' OR sno_hsalida.tipsal='V1' OR sno_hsalida.tipsal='W1' OR ".
											"      sno_hsalida.tipsal='D' OR sno_hsalida.tipsal='V2' OR sno_hsalida.tipsal='W2' OR ".
											"      sno_hsalida.tipsal='P1' OR sno_hsalida.tipsal='V3' OR sno_hsalida.tipsal='W3')";
			}
		}
		$ls_sql="SELECT sno_hconcepto.codconc, MAX(".$ls_campo.") as nomcon, SUM(sno_hsalida.valsalaux) AS valsal, MAX(sno_hsalida.tipsal) AS tipsal ".
				"  FROM sno_hsalida, sno_hconcepto, sno_hperiodo ".
				" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"   AND sno_hsalida.codper='".$as_codper."'".
				"   ".$ls_criterio.
				"   AND sno_hsalida.codemp = sno_hconcepto.codemp ".
				"   AND sno_hsalida.codnom = sno_hconcepto.codnom ".
				"   AND sno_hsalida.anocur = sno_hconcepto.anocur ".
				"   AND sno_hsalida.codperi = sno_hconcepto.codperi ".
				"   AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				"   AND sno_hsalida.codemp = sno_hperiodo.codemp ".
				"   AND sno_hsalida.codnom = sno_hperiodo.codnom ".
				"   AND sno_hsalida.anocur = sno_hperiodo.anocur ".
				"   AND sno_hsalida.codperi = sno_hperiodo.codperi ".
				" GROUP BY sno_hconcepto.codconc, sno_hsalida.tipsal  ".
				" ORDER BY sno_hconcepto.codconc ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_recibopago_conceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_recibopago_conceptopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_comparado0406_programado($as_rango)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_comparado0406_programado
		//         Access: public (desde la clase sigesp_snorh_rpp_comparado0711)  
		//	    Arguments: as_rango // rango de meses a sumar
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de la programación de reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/09/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;

		$ls_sql="SELECT codrep,codded,codtipper,moneneaux AS monene,monfebaux AS monfeb,monmaraux AS monmar,monabraux AS monabr,".
				"		monmayaux AS monmay,monjunaux AS monjun,monjulaux AS monjul,monagoaux AS monago,monsepaux AS monsep,".
				"		monoctaux AS monoct,monnovaux AS monnov,mondicaux AS mondic, ".
				"		carene,carfeb,carmar,carabr,carmay,carjun,carjul,carago,carsep,caroct,carnov,cardic, ".
				"		(SELECT sno_dedicacion.desded FROM  sno_dedicacion ".
				"	 	  WHERE sno_programacionreporte.codemp = sno_dedicacion.codemp ".
				"			AND sno_programacionreporte.codded = sno_dedicacion.codded) as desded, ".
				"		(SELECT sno_tipopersonal.destipper FROM  sno_tipopersonal ".
				"	 	  WHERE sno_programacionreporte.codemp = sno_tipopersonal.codemp ".
				"			AND sno_programacionreporte.codded = sno_tipopersonal.codded ".
				"			AND sno_programacionreporte.codtipper = sno_tipopersonal.codtipper) as destipper ".
				"  FROM sno_programacionreporte ".
				" WHERE codemp = '".$this->ls_codemp."'".
				"   AND codrep = '0406'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_comparado0406_programado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_comparado0406_programado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_comparado0406_real($as_rango,$as_codded,$as_codtipper,&$ai_cargoreal,&$ai_montoreal)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_comparado0406_real
		//         Access: public (desde la clase sigesp_snorh_rpp_comparado0711)  
		//	    Arguments: as_rango // rango de meses a sumar
		//	   			   as_codded // código de dedicación
		//	   			   as_codtipper // código de tipo de personal
		//	   			   ai_cargoreal // Cargo Real
		//	   			   ai_montoreal // Monto Real
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de la programación de reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/09/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->io_sql=new class_sql($this->io_conexion);
		$lb_valido=true;
		$ls_criterio="";
		$ls_groupcargos="";
		$ls_groupmontos="";
		if($as_codtipper=="0000")
		{
			$ls_criterio=" AND sno_hpersonalnomina.codded='".$as_codded."'";
			$ls_groupcargos=" GROUP BY sno_hpersonalnomina.codper, sno_hpersonalnomina.codded, sno_hpersonalnomina.codtipper ";
			$ls_groupmontos=" GROUP BY sno_hpersonalnomina.codded ";
		}
		else
		{
			$ls_criterio=" AND sno_hpersonalnomina.codded='".$as_codded."'".
						 " AND sno_hpersonalnomina.codtipper='".$as_codtipper."'";
			$ls_groupcargos=" GROUP BY sno_hpersonalnomina.codper, sno_hpersonalnomina.codded, sno_hpersonalnomina.codtipper ";
			$ls_groupmontos=" GROUP BY sno_hpersonalnomina.codded, sno_hpersonalnomina.codtipper ";
		}

		$ls_sql="SELECT sno_hpersonalnomina.codper ".
				"  FROM sno_hpersonalnomina, sno_hperiodo, sno_hnomina ".
				" WHERE sno_hpersonalnomina.codemp = '".$this->ls_codemp."'".
				"   AND sno_hpersonalnomina.anocur = '".substr($_SESSION["la_empresa"]["periodo"],0,4)."'".
				$ls_criterio.
				"   AND sno_hnomina.tipnom <> 7 ".
				"   AND sno_hnomina.espnom = 0 ".
				"   AND sno_hnomina.ctnom = 0 ".
				"   AND substr(sno_hperiodo.fecdesper,6,2) >= '".substr($as_rango,0,2)."'".
				"   AND substr(sno_hperiodo.fechasper,6,2) <= '".substr($as_rango,3,2)."'".
				"   AND sno_hnomina.codemp = sno_hperiodo.codemp ".
				"   AND sno_hnomina.codnom = sno_hperiodo.codnom ".
				"	AND sno_hnomina.anocurnom = sno_hperiodo.anocur ".
				"   AND sno_hnomina.peractnom = sno_hperiodo.codperi ".
				"   AND sno_hpersonalnomina.codemp = sno_hperiodo.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hperiodo.codnom ".
				"	AND sno_hpersonalnomina.anocur = sno_hperiodo.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hperiodo.codperi ".
				$ls_groupcargos;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_comparado0406_real ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_cargoreal=$ai_cargoreal+1;
			}
			$this->io_sql->free_result($rs_data);
		}
		if($lb_valido)
		{
			$ls_sql="SELECT sum(sno_hsalida.valsalaux) as monto ".
					"  FROM sno_hpersonalnomina, sno_hsalida, sno_hperiodo, sno_hnomina ".
					" WHERE sno_hpersonalnomina.codemp = '".$this->ls_codemp."'".
					"   AND sno_hpersonalnomina.anocur = '".substr($_SESSION["la_empresa"]["periodo"],0,4)."'".
					$ls_criterio.
					"   AND sno_hnomina.tipnom <> 7 ".
					"   AND substr(sno_hperiodo.fecdesper,6,2) >= '".substr($as_rango,0,2)."'".
					"   AND substr(sno_hperiodo.fechasper,6,2) <= '".substr($as_rango,3,2)."'".
					"   AND (sno_hsalida.tipsal = 'A' OR sno_hsalida.tipsal = 'V1' OR sno_hsalida.tipsal = 'W1')".
					"   AND sno_hnomina.codemp = sno_hperiodo.codemp ".
					"   AND sno_hnomina.codnom = sno_hperiodo.codnom ".
					"	AND sno_hnomina.anocurnom = sno_hperiodo.anocur ".
					"   AND sno_hnomina.peractnom = sno_hperiodo.codperi ".
					"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
					"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
					"	AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
					"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
					"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
					"   AND sno_hpersonalnomina.codemp = sno_hperiodo.codemp ".
					"   AND sno_hpersonalnomina.codnom = sno_hperiodo.codnom ".
					"	AND sno_hpersonalnomina.anocur = sno_hperiodo.anocur ".
					"   AND sno_hpersonalnomina.codperi = sno_hperiodo.codperi ".
					$ls_groupmontos;
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_mensajes->message("CLASE->Report MÉTODO->uf_comparado0406_real ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
			}
			else
			{
				if($row=$this->io_sql->fetch_row($rs_data))
				{
					$ai_montoreal=$row["monto"];
				}
				$this->io_sql->free_result($rs_data);
			}
		}		
		return $lb_valido;
	}// end function uf_comparado0406_real
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>