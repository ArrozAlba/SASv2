<?php
class sigesp_srh_class_report_2
{
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_srh_class_report_2($as_path="../../")
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_srh_class_report_2
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 24/09/2008
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		require_once($as_path."shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$this->io_conexion=$io_include->uf_conectar();
		require_once($as_path."shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($this->io_conexion);	
		$this->DS=new class_datastore();
		$this->DS2=new class_datastore();
		$this->ds_detalle=new class_datastore();
		require_once($as_path."shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once($as_path."shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
		require_once($as_path."shared/class_folder/class_fecha.php");
		$this->io_fecha=new class_fecha();		
		require_once($as_path."shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad=new sigesp_c_seguridad();		
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function sigesp_srh_class_report_2
	//---------------------------------------------------------------------------------------------------------------------------------
	function uf_select_concursante($as_codcon,$as_codper)
	{		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_concursante
		//         Access: public  
		//	    Arguments: as_codcon     // código del concurso
		//                 as_codper    //  códdigo de la persona
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de un concursante
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 24/09/2008									Fecha Última Modificación :  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
       $ls_sql= " SELECT srh_concursante.*, sigesp_pais.despai, sigesp_estados.desest ".
	           "  FROM srh_concursante,sigesp_pais,sigesp_estados ".
				" WHERE trim(srh_concursante.codcon) = '".trim($as_codcon)."' ".
				" AND trim(srh_concursante.codper) = '".trim($as_codper)."' ".
                " AND srh_concursante.codemp='".$this->ls_codemp."' ".
				" AND sigesp_pais.codpai = srh_concursante.codpai ".
				" AND sigesp_estados.codpai = srh_concursante.codpai ".
				" AND sigesp_estados.codest = srh_concursante.codest ";	 

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_concursante ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
	}// end function uf_select_concursante
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_select_esrudios_concursante($as_codcon,$as_codper)
	{		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_esrudios_concursante
		//         Access: public  
		//	    Arguments: as_codcon     // código del concurso
		//                 as_codper    //  códdigo de la persona
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de un concursante
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 24/09/2008									Fecha Última Modificación :  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
       $ls_sql= " SELECT srh_estudiosconcursante.* ".
	           "  FROM srh_estudiosconcursante".
				" WHERE trim(codcon) = '".trim($as_codcon)."' ".
				" AND trim(codper) = '".trim($as_codper)."' ".
                " AND codemp='".$this->ls_codemp."' ";	 

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_esrudios_concursante ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS2->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_esrudios_concursante
	
//--------------------------------------------------------------------------------------------------------------------------------
	
function uf_select_cursos_concursante($as_codcon,$as_codper)
{		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_cursos_concursante
		//         Access: public  
		//	    Arguments: as_codcon     // código del concurso
		//                 as_codper    //  códdigo de la persona
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de un concursante
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 24/09/2008									Fecha Última Modificación :  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
       $ls_sql= " SELECT srh_cursosconcursante.* ".
	           "  FROM srh_cursosconcursante".
				" WHERE trim(codcon) = '".trim($as_codcon)."' ".
				" AND trim(codper) = '".trim($as_codper)."' ".
                " AND codemp='".$this->ls_codemp."' ";	 

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_cursos_concursante ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS2->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_cursos_concursante
//--------------------------------------------------------------------------------------------------------------------------------
	
function uf_select_trabajos_concursante($as_codcon,$as_codper)
{		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_trabajos_concursante
		//         Access: public  
		//	    Arguments: as_codcon     // código del concurso
		//                 as_codper    //  códdigo de la persona
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de un concursante
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 24/09/2008									Fecha Última Modificación :  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
       $ls_sql= " SELECT srh_trabajosconcursante.* ".
	           "  FROM srh_trabajosconcursante".
				" WHERE trim(codcon) = '".trim($as_codcon)."' ".
				" AND trim(codper) = '".trim($as_codper)."' ".
                " AND codemp='".$this->ls_codemp."' ".
				" ORDER BY fecegrtraper DESC";	 

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_trabajos_concursante ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS2->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_trabajos_concursante

//--------------------------------------------------------------------------------------------------------------------------------
	
function uf_select_familiares_concursante($as_codcon,$as_codper)
{		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_familiares_concursante
		//         Access: public  
		//	    Arguments: as_codcon     // código del concurso
		//                 as_codper    //  códdigo de la persona
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de un concursante
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 24/09/2008									Fecha Última Modificación :  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
       $ls_sql= " SELECT srh_familiaresconcursante.* ".
	           "  FROM srh_familiaresconcursante".
				" WHERE trim(codcon) = '".trim($as_codcon)."' ".
				" AND trim(codper) = '".trim($as_codper)."' ".
                " AND codemp='".$this->ls_codemp."' ";	 

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_familiares_concursante ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS2->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_familiares_concursante

//--------------------------------------------------------------------------------------------------------------------------------
	
function uf_select_requisitos_concursante($as_codcon,$as_codper)
{		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_requisitos_concursante
		//         Access: public  
		//	    Arguments: as_codcon     // código del concurso
		//                 as_codper    //  códdigo de la persona
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de un concursante
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 24/09/2008									Fecha Última Modificación :  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
       $ls_sql= " SELECT srh_requisitosconcursante.*, srh_requisitos_concurso.desreqcon ".
	           "  FROM srh_requisitosconcursante, srh_requisitos_concurso".
				" WHERE trim(srh_requisitosconcursante.codcon) = '".trim($as_codcon)."' ".
				" AND trim(srh_requisitosconcursante.codper) = '".trim($as_codper)."' ".
                " AND srh_requisitosconcursante.codemp='".$this->ls_codemp."' ".
				" AND srh_requisitos_concurso.codemp='".$this->ls_codemp."' ".
				" AND srh_requisitos_concurso.codcon=srh_requisitosconcursante.codcon".
				" AND srh_requisitos_concurso.codreqcon=srh_requisitosconcursante.codreqcon";	 

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_requisitos_concursante ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS2->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_requisitos_concursante

//--------------------------------------------------------------------------------------------------------------------------------
	
function uf_select_concurso($as_codcon,&$rs_data)
{		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_concurso
		//         Access: public  
		//	    Arguments: as_codcon     // código del concurso
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de un concurso
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 25/09/2008									Fecha Última Modificación :  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
        $ls_sql= " SELECT srh_concurso.*, sno_asignacioncargo.codasicar, sno_asignacioncargo.denasicar, ".
		 	      " sno_cargo.codcar, sno_cargo.descar  ".
				  " FROM   srh_concurso ".
		          " LEFT JOIN sno_asignacioncargo ON  (srh_concurso.codcar=sno_asignacioncargo.codasicar AND      ".
			      "                               srh_concurso.codnom=sno_asignacioncargo.codnom)  ".
			      " LEFT JOIN sno_cargo  ON  (srh_concurso.codcar=sno_cargo.codcar AND ".
				  "  srh_concurso.codnom=sno_cargo.codnom) ".	 
				  " WHERE srh_concurso.codemp='".$this->ls_codemp."' ".
				  " AND srh_concurso.codcon = '".$as_codcon."'  ".
				  " ORDER BY codcon ";	
	  
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_concurso ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
			
		return $lb_valido;
	}// end function uf_select_concurso
//--------------------------------------------------------------------------------------------------------------------------------

function uf_select_concursante_concurso($as_codcondes,$as_codconhas,$as_estconper,$as_orden,&$rs_datcon)
{		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_concursante_concurso
		//         Access: public  
		//	    Arguments: as_codcon     // código del concurso
		//                 as_codper    //  códdigo de la persona
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de un concursante dado los parámetros del reporte
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 25/09/2008									Fecha Última Modificación :  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		if(!empty($as_codcondes))
		{
			$ls_criterio= $ls_criterio."   AND srh_concursante.codcon>='".$as_codcondes."'";
		}
		if(!empty($as_codconhas))
		{
			$ls_criterio= $ls_criterio."   AND srh_concursante.codcon<='".$as_codconhas."'";
		}
		
		switch($as_estconper)
		{
			case "2": // filtra por participantes activos
				$ls_criterio= $ls_criterio."   AND srh_concursante.estconper='1' ";
				break;

			case "3": // filtra por participantes excluidos
				$ls_criterio= $ls_criterio."   AND srh_concursante.estconper='0' ";
				break;
		}
		
		switch($as_orden)
		{
			case "1": // Ordena por  código del concursante
				$ls_orden="srh_concursante.codper DESC";
				break;

			case "2": // Ordena por nombre del concursante
				$ls_orden="srh_concursante.nomper";
				break;
           case "3": // Ordena por apellido del concursante
				$ls_orden="srh_concursante.apeper";
				break;
		   case "4": // Ordena por estatus del concursante
			$ls_orden="srh_concursante.estconper";
			break;
		}
		
        $ls_sql= " SELECT srh_concursante.*  ".
				  " FROM   srh_concursante ".		         	 
				  " WHERE srh_concursante.codemp='".$this->ls_codemp."' ".
				  $ls_criterio.
				  " ORDER BY ".$ls_orden."  ";	

		$rs_datcon=$this->io_sql->select($ls_sql);
		if($rs_datcon===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_concursante_concurso ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if ($this->io_sql->num_rows($rs_datcon)==0)
			{
				$lb_valido=false;
			}
		}
			
		return $lb_valido;
	}// end function uf_select_concursante_concurso
//--------------------------------------------------------------------------------------------------------------------------------

function uf_select_requisitos_faltantes_concursante($as_codcon,$as_codper)
{		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_requisitos_faltantes_concursante
		//         Access: public  
		//	    Arguments: as_codcon     // código del concurso
		//                 as_codper    //  códdigo de la persona
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de un concursante
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 24/09/2008									Fecha Última Modificación :  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
       $ls_sql= " SELECT srh_requisitos_concurso.desreqcon ".
	           "  FROM srh_requisitosconcursante, srh_requisitos_concurso".
				" WHERE trim(srh_requisitosconcursante.codcon) = '".trim($as_codcon)."' ".
				" AND trim(srh_requisitosconcursante.codper) = '".trim($as_codper)."' ".
                " AND srh_requisitosconcursante.codemp='".$this->ls_codemp."' ".
				" AND srh_requisitos_concurso.codemp='".$this->ls_codemp."' ".
				" AND srh_requisitos_concurso.codcon=srh_requisitosconcursante.codcon".
				" AND srh_requisitos_concurso.codreqcon=srh_requisitosconcursante.codreqcon".
				" AND srh_requisitosconcursante.entreqcon=0";	 

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_requisitos_faltantes_concursante ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS2->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_requisitos_faltantes_concursante
//--------------------------------------------------------------------------------------------------------------------------------

} // fin de la clase sigesp_srh_class_report_2

?>