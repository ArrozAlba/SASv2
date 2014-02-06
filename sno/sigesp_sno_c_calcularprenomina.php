<?php
class sigesp_sno_c_calcularprenomina
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_fun_nomina;
	var $io_sno;
	var $io_evaluador;
	var $io_vacacion;
	var $ls_codemp;
	var $ls_codnom;
	var $ls_peractnom;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sno_c_calcularprenomina()
	{	
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sno_c_calcularnomina
		//		   Access: public (sigesp_sno_p_calcularnomina)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 17/02/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
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
		require_once("class_folder/class_funciones_nomina.php");
		$this->io_fun_nomina=new class_funciones_nomina();
		require_once("sigesp_sno.php");
		$this->io_sno=new sigesp_sno();
		require_once("sigesp_sno_c_evaluador.php");
		$this->io_evaluador=new sigesp_sno_c_evaluador();
		require_once("sigesp_sno_c_vacacion.php");
		$this->io_vacacion=new sigesp_sno_c_vacacion();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
        $this->ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$this->ls_peractnom=$_SESSION["la_nomina"]["peractnom"];		
	}// end function sigesp_sno_c_calcularnomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_sno_p_calcularnomina)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 28/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
		unset($this->io_fun_nomina);
		unset($this->io_sno);
		unset($this->io_evaluador);
		unset($this->io_vacacion);
        unset($this->ls_codemp);
        unset($this->ls_codnom);
        unset($this->ls_peractnom);       
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_totalpersonal($as_codperdes,$as_codperhas,&$ai_nropro)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_totalpersonal
		//		   Access: private
		//	    Arguments: as_codperdes  // Código de Personal Desde
		//	    		   as_codperhas  // Código de Personal Hasta
		//	    		   ai_nropro  // Número de personas a procesar
		//	      Returns: lb_valido True si se ejecutó con éxito el select y false si hubo agún error
		//	  Description: Funcion que obtiene el total de personas a procesar
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 17/02/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_desincorporar=$this->io_sno->uf_select_config("SNO","NOMINA","DESINCORPORAR DE NOMINA","0","C");
		$lb_valido=true;
		$ls_sql="SELECT count(sno_personalnomina.codper) AS total ".
				"  FROM sno_personalnomina ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$this->ls_codnom."' ";
		switch ($li_desincorporar)
		{
			case "0"; // No se Desincorpora de la nómina 
				$ls_sql=$ls_sql." AND (sno_personalnomina.staper='1' OR sno_personalnomina.staper='2')";
				break;
	
			case "1"; // Se desincorpora de la nómina
				$ls_sql=$ls_sql." AND sno_personalnomina.staper='1' ";
				break;
		}
		if($as_codperdes!="")
		{
			$ls_sql=$ls_sql." AND sno_personalnomina.codper>='".$as_codperdes."'";
		}
		if($as_codperhas!="")
		{
			$ls_sql=$ls_sql." AND sno_personalnomina.codper<='".$as_codperhas."'";
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Prenómina MÉTODO->uf_load_totalpersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_nropro=$row["total"];
			}
			$this->io_sql->free_result($rs_data);		
		}
		return $lb_valido;
	}// end function uf_load_totalpersonal
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_resumenprenomina($as_peractnom,$as_codperdes,$as_codperhas,&$ai_totasiprenom,&$ai_totdedprenom,
										 &$ai_totapoempprenom,&$ai_totapopatprenom,&$ai_totprenom,&$ai_nropro,&$ai_totasinomant,
										 &$ai_totdednomant,&$ai_totapoempnomant,&$ai_totapopatnomant,&$ai_totnomant)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_resumenprenomina
		//		   Access: public (sigesp_sno_p_calcularprenomina.php)
		//	    Arguments: as_peractnom  // período Actual de la nómina
		//	    		   as_codperdes  // Código de Personal Desde
		//	    		   as_codperhas  // Código de Personal Hasta
		//				   ai_totasiprenom  // Total de Asignaciones de la Prenómina
		//				   ai_totdedprenom  // Total de Deducciones de la Prenómina
		//				   ai_totapoempprenom  // Total de Aportes de Empleados de la Prenómina
		//				   ai_totapopatprenom  // Total de Aportes de Patron de la Prenómina
		//				   ai_totprenom  // Total de la prenómina
		//				   ai_nropro  // Número de personas a procesar
		//				   ai_totasinomant  // Total de Asignaciones de la Nómina Anterior
		//				   ai_totdednomant  // Total de Deducciones de la Nómina Anterior
		//				   ai_totapoempnomant  // Total de Aportes de Empleados de la Nómina Anterior
		//				   ai_totapopatnomant  // Total de Aportes de Patron de la Nómina Anterior
		//				   ai_totnomant  // Total de la Nómina Anterior
		//	      Returns: lb_valido True si se encontro ó False si no se encontró
		//	  Description: Funcion que obtiene el resumen de pago de la nómina 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 17/02/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT (SELECT SUM(valprenom) ".
				"  		   FROM sno_prenomina ".
				" 		  WHERE codemp='".$this->ls_codemp."' ".
				"           AND codnom='".$this->ls_codnom."' ".
				"           AND codperi='".$as_peractnom."' ".
				"           AND (tipprenom='A' OR tipprenom='V1' OR tipprenom='W1')) AS asigprenom, ".
		        "       (SELECT SUM(valprenom)".
				"  		   FROM sno_prenomina ".
				" 		  WHERE codemp='".$this->ls_codemp."' ".
				"           AND codnom='".$this->ls_codnom."' ".
				"           AND codperi='".$as_peractnom."' ".
				"           AND (tipprenom='D' OR tipprenom='V2' OR tipprenom='W2')) AS deduprenom, ".
		        "       (SELECT SUM(valprenom)".
				"  		   FROM sno_prenomina ".
				" 		  WHERE codemp='".$this->ls_codemp."' ".
				"           AND codnom='".$this->ls_codnom."' ".
				"           AND codperi='".$as_peractnom."' ".
				"           AND (tipprenom='P1' OR tipprenom='V3' OR tipprenom='W3')) AS apoempprenom, ".
		        "       (SELECT SUM(valprenom)".
				"  		   FROM sno_prenomina ".
				" 		  WHERE codemp='".$this->ls_codemp."' ".
				"           AND codnom='".$this->ls_codnom."' ".
				"           AND codperi='".$as_peractnom."' ".
				"           AND (tipprenom='P2' OR tipprenom='V4' OR tipprenom='W4')) AS apopatprenom, ".
				"       (SELECT SUM(valhis)".
				"  		   FROM sno_prenomina ".
				" 		  WHERE codemp='".$this->ls_codemp."' ".
				"           AND codnom='".$this->ls_codnom."' ".
				"           AND codperi='".$as_peractnom."' ".
				"           AND (tipprenom='A' OR tipprenom='V1' OR tipprenom='W1')) AS asignomant, ".
		        "       (SELECT SUM(valhis)".
				"  		   FROM sno_prenomina ".
				" 		  WHERE codemp='".$this->ls_codemp."' ".
				"           AND codnom='".$this->ls_codnom."' ".
				"           AND codperi='".$as_peractnom."' ".
				"           AND (tipprenom='D' OR tipprenom='V2' OR tipprenom='W2')) AS dedunomant, ".
		        "       (SELECT SUM(valhis)".
				"  		   FROM sno_prenomina ".
				" 		  WHERE codemp='".$this->ls_codemp."' ".
				"           AND codnom='".$this->ls_codnom."' ".
				"           AND codperi='".$as_peractnom."' ".
				"           AND (tipprenom='P1' OR tipprenom='V3' OR tipprenom='W3')) AS apoempnomant, ".
		        "       (SELECT SUM(valhis)".
				"  		   FROM sno_prenomina ".
				" 		  WHERE codemp='".$this->ls_codemp."' ".
				"           AND codnom='".$this->ls_codnom."' ".
				"           AND codperi='".$as_peractnom."' ".
				"           AND (tipprenom='P2' OR tipprenom='V4' OR tipprenom='W4')) AS apopatnomant ".
				"  FROM sno_prenomina ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codperi='".$as_peractnom."' ".
				" GROUP BY codemp,codnom,codperi";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Prenómina MÉTODO->uf_load_resumenprenomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totasiprenom=$this->io_fun_nomina->uf_formatonumerico($row["asigprenom"]);
				$ai_totdedprenom=$this->io_fun_nomina->uf_formatonumerico(abs($row["deduprenom"]));
				$ai_totapoempprenom=$this->io_fun_nomina->uf_formatonumerico(abs($row["apoempprenom"]));	   
				$ai_totapopatprenom=$this->io_fun_nomina->uf_formatonumerico(abs($row["apopatprenom"]));
				$ai_totprenom=($row["asigprenom"]+$row["deduprenom"]+$row["apoempprenom"]);
				$ai_totprenom=$this->io_fun_nomina->uf_formatonumerico($ai_totprenom);
				$ai_totasinomant=$this->io_fun_nomina->uf_formatonumerico($row["asignomant"]);
				$ai_totdednomant=$this->io_fun_nomina->uf_formatonumerico(abs($row["dedunomant"]));
				$ai_totapoempnomant=$this->io_fun_nomina->uf_formatonumerico(abs($row["apoempnomant"]));	   
				$ai_totapopatnomant=$this->io_fun_nomina->uf_formatonumerico(abs($row["apopatnomant"]));
				$ai_totnomant=($row["asignomant"]+$row["dedunomant"]+$row["apoempnomant"]);	   
				$ai_totnomant=$this->io_fun_nomina->uf_formatonumerico($ai_totnomant);
			}
			$this->io_sql->free_result($rs_data);		
			$lb_valido=$this->uf_load_totalpersonal($as_codperdes,$as_codperhas,$ai_nropro);
		}
		return $lb_valido;
	}// end function uf_load_resumenprenomina
	//-----------------------------------------------------------------------------------------------------------------------------------	

//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_salida()
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_salida
		//		   Access: public (sigesp_sno_p_calcularprenomina.php)
		//	      Returns: lb_existe True si existe alguna salida y false si no existe Salida
		//	  Description: Funcion que verifica si hay registros en salida
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 17/02/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		$ls_sql="SELECT count(codper) as total".
				"  FROM sno_resumen ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codperi='".$this->ls_peractnom."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_existe=true;
			$this->io_mensajes->message("CLASE->Calcular Prenómina MÉTODO->uf_select_salida ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				if($row["total"]>0)
				{
					$lb_existe=true;
				}
			}
			$this->io_sql->free_result($rs_data);		
		}
		return $lb_existe;
	}// end function uf_select_salida
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesarprenomina($as_codperdes,$as_codperhas,$aa_seguridad)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesarnomina
		//		   Access: public (sigesp_sno_p_calcularprenomina.php)
		//	    Arguments: as_codperdes // Código del personal Desde
		//	    		   as_codperhas // Código del personal Hasta
		//	     		   aa_seguridad // arreglo de seguridad
		//	      Returns: lb_valido True si se proceso correctamente ó False si hubo error 
		//	  Description: función que selecciona el personal y procesa la prenómina 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 17/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_desincorporar=$this->io_sno->uf_select_config("SNO","NOMINA","DESINCORPORAR DE NOMINA","0","C");
		$ls_perhis="000";
		$ls_anocurhis="1900";
		$lb_valido=true;
		$ls_sql="SELECT sno_personalnomina.codper ".
				"  FROM sno_personalnomina ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$this->ls_codnom."' ";							
		switch ($li_desincorporar)
		{
			case "0"; // No se Desincorpora de la nómina 
				$ls_sql=$ls_sql." AND (sno_personalnomina.staper='1' OR sno_personalnomina.staper='2')";
				break;
	
			case "1"; // Se desincorpora de la nómina
				$ls_sql=$ls_sql." AND sno_personalnomina.staper='1'";
				break;
		}
		if($as_codperdes!="")
		{
			$ls_sql=$ls_sql." AND sno_personalnomina.codper>='".$as_codperdes."'";
		}
		if($as_codperhas!="")
		{
			$ls_sql=$ls_sql." AND sno_personalnomina.codper<='".$as_codperhas."'";
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Prenómina MÉTODO->uf_procesarprenomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			$this->io_sql->begin_transaction();
			if($lb_valido) // Borra la información de la tabla de prenómina
			{
			   $lb_valido=$this->uf_delete_prenomina($aa_seguridad);
			}
			if($lb_valido) // Obtiene el año y período anterior
			{
				$lb_valido=$this->uf_load_periodoanterior($ls_perhis,$ls_anocurhis);
			}
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido)) // Procesa todo el personal
			{
				$ls_codper=$row["codper"];
				$lb_valido=$this->uf_procesar_prenominapersonal($ls_codper,$ls_perhis,$ls_anocurhis);
			}
			$this->io_sql->free_result($rs_data);	
			if($lb_valido) // chequea el personal que fué sacado de la nómina en este período
			{
				$lb_valido=$this->uf_procesar_historicopersonal($as_codperdes,$as_codperhas,$ls_perhis,$ls_anocurhis);
			}
			if($lb_valido)
			{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="PROCESS";
					$ls_descripcion ="Se procesó la prenómina asociado a la nómina ".$this->ls_codnom." periodo ".$this->ls_peractnom;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////								
			}
			if($lb_valido)
			{
				$this->io_sql->commit();
				$this->io_mensajes->message("El Cálculo de la prenómina fue procesado.");
			}
			else
			{
				$this->io_sql->rollback();
				$this->io_mensajes->message("Ocurrio un error al calcular la Prenómina.");
			}
		}
		return $lb_valido;
	}// end function uf_procesarprenomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_prenomina($aa_seguridad)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_prenomina
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina toda la información de la Prenómina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 17/02/2006 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
				"  FROM sno_prenomina ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codperi='".$this->ls_peractnom."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Prenómina MÉTODO->uf_delete_prenomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Se eliminó la prenómina asociado a la nómina ".$this->ls_codnom." periodo ".$this->ls_peractnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////										
		}
		return $lb_valido;
    }// end function uf_delete_prenomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_periodoanterior(&$as_perhis,&$as_anocurhis)
	{
		/////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_periodoanterior
		//		   Access: private
		//	    Arguments: as_perhis // Período Histórico Anterior
		//				   as_anocurhis // Año en curso Histórico Anterior
		//	      Returns: lb_valido True si no hubo ningún error al obtener el período y año anterior y false si hubo error
		//	  Description: Función que obtiene el período y año anterior y si el perído da 000 buscamos el último del año anterior 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 17/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$as_perhis=str_pad((intval($this->ls_peractnom)-1),3,"0",0);
		if($as_perhis=="000")
		{
			$as_anocurhis=(intval($_SESSION["la_nomina"]["anocurnom"])-1);
			$lb_valido=$this->uf_load_ultimoperiodohistorico($as_anocurhis,$as_perhis);
		}
		else
		{
			$as_anocurhis=$_SESSION["la_nomina"]["anocurnom"];
		}
  	  	return $lb_valido;
	}// end function uf_load_periodoanterior
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_ultimoperiodohistorico($as_anocurhis,&$as_ultperhis)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_ultimoperiodohistorico
		//		   Access: private 
		//	    Arguments: as_anocurhis // año en curso Histórico
		//				   as_ultperhis // Último período del Histórico
		//	      Returns: lb_valido True si el select lo ejecuto correctamente y false si no le ejecutó
		//	  Description: Funcion que obtiene el último períod de un histórico dado un año
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 17/02/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codperi ".
				"  FROM sno_hperiodo ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND anocur='".$as_anocurhis."' ".
				" ORDER BY codperi ASC ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular prenómina MÉTODO->uf_load_ultimoperiodohistorico ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_ultperhis=$row["codperi"];
			}
			$this->io_sql->free_result($rs_data);		
		}
		return $lb_valido;
	}// end function uf_load_ultimoperiodohistorico
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_prenominapersonal($as_codper,$as_perhis,$as_anocurhis)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_prenominapersonal
		//		   Access: private
		//	    Arguments: as_codper // codigo de la persona a procesar 
		//	    		   as_perhis // Período Histórico Anterior
		//				   as_anocurhis // Año en curso Histórico Anterior
		//	      Returns: lb_valido True si los calculos de la prenomina fueron exitosos, false en caso contrario 
		//	  Description: Funcion que calcula la prenomina por persona  
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 17/02/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_valido=$this->io_evaluador->uf_config_session($as_codper);
		if($lb_valido) // Se evaluan los conceptos del personal
		{
			$lb_valido=$this->uf_evaluar_conceptospersonal($as_codper,$as_perhis,$as_anocurhis);
		}
		if($lb_valido) // Se evalua si el personal tiene prestamos
		{
			$lb_valido=$this->uf_evaluar_prestamospersonal($as_codper,$as_perhis,$as_anocurhis);
		}
		if($lb_valido) // Se evalua si el personal esta de vacaciones
		{
			$lb_valido=$this->uf_evaluar_vacacionespersonal($as_codper,$as_perhis,$as_anocurhis);
		}
		if($lb_valido) // Se liberan las variables de sessión que se instanciaron en un principio
		{
			$lb_valido=$this->uf_liberarsession();
		}
		return  $lb_valido; 
	}// end function uf_procesar_prenominapersonal
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_evaluar_conceptospersonal($as_codper,$as_perhis,$as_anocurhis)
	{
		/////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_evaluar_conceptospersonal
		//		   Access: private
		//		Arguments: as_codper // código de personal
		//	    		   as_perhis // Período Histórico Anterior
		//				   as_anocurhis // Año en curso Histórico Anterior
		//	      Returns: lb_valido True si se evaluaron los conceptos ó False si hubo error
		//	  Description: Funcion que obtiene los conceptos por personal y los evalua
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 17/02/2006 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////
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
			$this->io_mensajes->message("CLASE->Calcular Prenómina MÉTODO->uf_evaluar_conceptospersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_i=1;
			while ((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_codconc=$rs_data->fields["codconc"];
				$_SESSION["la_conceptopersonal"]["codconc"]=$ls_codconc;
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
				$li_valcon=0;
				$li_valconhis=0;					
				$lb_filtro=true;
				$lb_aplica=true;
				if (!(trim($ls_concon)==""))// Si el concepto tiene condición
				{
					$lb_valido=$this->io_evaluador->uf_evaluar($as_codper,$ls_concon,$lb_filtro);
				}
				if($ls_glocon==0) // Si el concepto no es Global
				{
					if($ls_aplcon==0) // Si no se aplica el concepto al personal
					{
						$lb_aplica=false;
					}
				}
				if(($lb_valido)&&($lb_aplica)&&($lb_filtro))// Si se aplica el concepto y el filtro es válido
				{
					$lb_valido=$this->uf_calcular_personal($as_codper,$ls_codconc,$li_valcon,$ls_forcon,$li_valmincon,$li_valmaxcon); 
					if($lb_valido)
					{
						if(($ls_sigcon=="A")||($ls_sigcon=="B"))// Si es una Asignación 
						{
							$lb_valido=$this->uf_load_historico($as_codper,$ls_codconc,"A",$li_valconhis,$as_perhis,$as_anocurhis);
							if($lb_valido)
							{
								$lb_valido=$this->uf_verificar_montos($as_codper,$ls_codconc,"A",$li_valcon,$li_valconhis);
							}
						}
						if (($ls_sigcon=="D")||($ls_sigcon=="E"))// Si es una Deducción
						{
							$lb_valido=$this->uf_load_historico($as_codper,$ls_codconc,"D",$li_valconhis,$as_perhis,$as_anocurhis);
							if($lb_valido)
							{
								$lb_valido=$this->uf_verificar_montos($as_codper,$ls_codconc,"D",-$li_valcon,$li_valconhis);
							}
						}
						if(($ls_sigcon=="P"))// Si es un Aporte Patronal
						{
						   $lb_valido=$this->uf_load_historico($as_codper,$ls_codconc,"P1",$li_valconhis,$as_perhis,$as_anocurhis);
							if($lb_valido)
							{
								$lb_valido=$this->uf_verificar_montos($as_codper,$ls_codconc,"P1",-$li_valcon,$li_valconhis);
							}
							$lb_valido=$this->uf_calcular_personal($as_codper,$ls_codconc,$li_valcon,$ls_forpatcon,$li_valminpatcon,$li_valmaxpatcon);
							if($lb_valido)
							{
								$lb_valido=$this->uf_load_historico($as_codper,$ls_codconc,"P2",$li_valconhis,$as_perhis,$as_anocurhis);
								if($lb_valido)
								{
									$lb_valido=$this->uf_verificar_montos($as_codper,$ls_codconc,"P2",-$li_valcon,$li_valconhis);
								}
							}
						}
						if(($ls_sigcon=="R"))// Si es un Reporte
						{
							$lb_valido=$this->uf_load_historico($as_codper,$ls_codconc,"R",$li_valconhis,$as_perhis,$as_anocurhis);
							if($lb_valido)
							{
								$lb_valido=$this->uf_verificar_montos($as_codper,$ls_codconc,"R",$li_valcon,$li_valconhis);
							}
						}
					}//if($lb_valido)
				}//if($lb_aplica)&&($lb_filtro)
				unset($_SESSION["la_concetopersonal"]);
				$rs_data->MoveNext();
		  	}//while
			$this->io_sql->free_result($rs_data);	
		}//else	
		return $lb_valido;
	}// end function uf_evaluar_conceptospersonal
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_calcular_personal($as_codper,$as_codconc,&$ai_valcon,$as_forcon,$ai_valmincon,$ai_valmaxcon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_calcular_personal
		//		   Access: private
		//	    Arguments: as_codper // código de personal
		//				   as_codcon // codigo del concepto 
		//                 ai_valcon //  valor del concepto  
		//                 as_forcon // formula del concepto
		//                 ai_valmincon // Valor Mínimo del concepto
		//                 ai_valmaxcon // Valor Máximo del concepto
		//	      Returns: lb_valido True si se evaluaron los conceptos ó False si hubo error
		//	  Description: Función que evalua el concepto del Personal y Verifica que sea mayor que el mínimo y menor que
		//					el máximo del concepto 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 17/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=$this->io_evaluador->uf_evaluar($as_codper,$as_forcon,$ai_valcon);
		if($lb_valido)
		{
			if($ai_valmincon>0)//verifico que el mínimo del concepto sea mayor que cero
			{
				if($ai_valcon<$ai_valmincon) // si el valor del concepto es menor que el mínimo
				{
					$ai_valcon=$ai_valmincon;
				}
			}
			if($ai_valmaxcon>0)//verifico que el máximo del concepto sea mayor que cero
			{
				if($ai_valcon>$ai_valmaxcon)// si el valor del concepto es mayor que el máximo
				{
					$ai_valcon=$ai_valmaxcon;
				}
			}
		}
  	  	return $lb_valido;
	}// end function uf_calcular_personal
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_historico($as_codper,$as_codconc,$as_sigcon,&$ai_valconhis,$as_perhis,$as_anocurhis)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_historico
		//		   Access: private 
		//	    Arguments: as_codper // código de personal
		//				   as_codconc // codigo del concepto 
		//				   as_sigcon // Signo del concepto 
		//                 ai_valconhis // valor del concepto en los históricos
		//                 as_perhis // Período Histórico
		//                 as_anocurhis // Año en curso histórico
		//	      Returns: lb_valido True si el select lo ejecuto correctamente y false si no le ejecutó
		//	  Description: Funcion que obtiene el valor del concepto en históricos
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 17/02/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_valconhis=0;
		$ls_sql="SELECT valsal ".
				"  FROM sno_hsalida ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND anocur='".$as_anocurhis."'".
				"   AND codperi='".$as_perhis."'".
				"   AND codconc='".$as_codconc."'".
				"   AND codper='".$as_codper."'".
				"   AND tipsal='".$as_sigcon."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular prenómina MÉTODO->uf_load_historico ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_valconhis=$row["valsal"];
			}
			$this->io_sql->free_result($rs_data);		
		}
		return $lb_valido;
	}// end function uf_load_historico
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_verificar_montos($as_codper,$as_codconc,$as_sigcon,$ai_valcon,$ai_valconhis)
	{
		/////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_montos
		//		   Access: private
		//	    Arguments: as_codper // código de personal
		//				   as_codconc // codigo del concepto 
		//                 as_sigcon // Signo del concepto
		//                 ai_valcon //  valor del concepto  
		//                 ai_valconhis //  valor del concepto histórico  
		//	      Returns: lb_valido True si no hubo ningún error al verificar los montos y false si hubo error
		//	  Description: Función que verifica si los montos de los conceptos son diferentes del histórico con respecto al actual
		//					entonces guarda en la prenomina.
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 17/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_valcon= number_format($ai_valcon,4,".","");
		$ai_valconhis= number_format($ai_valconhis,4,".","");
		if(doubleval($ai_valcon)!=doubleval($ai_valconhis))
		{
			if($this->uf_select_prenomina($as_codper,$as_codconc,$as_sigcon))
			{
				$lb_valido=$this->uf_update_prenomina($as_codper,$as_codconc,$as_sigcon,$ai_valcon,$ai_valconhis);
			}
			else
			{
				$lb_valido=$this->uf_insert_prenomina($as_codper,$as_codconc,$as_sigcon,$ai_valcon,$ai_valconhis);
			}
		}
  	  	return $lb_valido;
	}// end function uf_verificar_montos
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_prenomina($as_codper,$as_codconc,$as_tipprenomina)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_prenomina
		//		   Access: private
		//	    Arguments: as_codper // código de personal
		//				   as_codconc // codigo del concepto 
		//                 as_tipprenomina // tipo de la salida de prenómina
		//	      Returns: lb_existe True si existe la prenómina  y false si no existe
		//	  Description: Funcion que verifica si la prenómina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 20/03/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
	  	$ls_sql="SELECT codper ".
				"  FROM sno_prenomina ".
				" WHERE codemp='".$this->ls_codemp."'".
				"	AND codnom='".$this->ls_codnom."'".
				"	AND codperi='".$this->ls_peractnom."'".
				"	AND codper='".$as_codper."'".
				"	AND codconc='".$as_codconc."'".
				"	AND tipprenom='".$as_tipprenomina."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Calcular Prenómina MÉTODO->uf_select_prenomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_existe=false;
		}
		else
		{
			if(!$row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=false;
			}
			$this->io_sql->free_result($rs_data);	
		}
	   	return $lb_existe; 
	}//end function uf_select_prenomina
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_prenomina($as_codper,$as_codconc,$as_tipprenomina,$ai_valprenomina,$ai_valhis)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_prenomina
		//		   Access: private
		//	    Arguments: as_codper // código de personal
		//				   as_codconc // codigo del concepto 
		//                 as_tipprenomina // tipo de la salida de prenómina
		//                 ai_valprenomina //  valor de la salida  de prenómina
		//                 ai_valhis //  valor de la salida en el histórico
		//	      Returns: lb_valido True si se insertó la prenómina correctamente y false si hubo error
		//	  Description: Funcion que inserta la prenómina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 17/02/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
	  	$ls_sql="INSERT INTO sno_prenomina(codemp,codnom,codperi,codper,codconc,tipprenom,valprenom,valhis)VALUES('".$this->ls_codemp."',".
	  			"'".$this->ls_codnom."','".$this->ls_peractnom."','".$as_codper."','".$as_codconc."','".$as_tipprenomina."',". 
		      	"".$ai_valprenomina.",".$ai_valhis.") ";
		$li_row=$this->io_sql->execute($ls_sql);
	   	if($li_row===false)
	   	{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Prenómina MÉTODO->uf_insert_prenomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
	   	}
	   	return $lb_valido; 
	}//end function uf_insert_prenomina
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_prenomina($as_codper,$as_codconc,$as_tipprenomina,$ai_valprenomina,$ai_valhis)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_prenomina
		//		   Access: private
		//	    Arguments: as_codper // código de personal
		//				   as_codconc // codigo del concepto 
		//                 as_tipprenomina // tipo de la salida de prenómina
		//                 ai_valprenomina //  valor de la salida  de prenómina
		//                 ai_valhis //  valor de la salida en el histórico
		//	      Returns: lb_valido True si se actualizó la prenómina correctamente y false si hubo error
		//	  Description: Funcion que actualiza la prenómina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 20/03/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
	  	$ls_sql="UPDATE sno_prenomina ".
				"   SET valprenom=(valprenom+".$ai_valprenomina."), ".
				"	    valhis=(valhis+".$ai_valhis.")".
				" WHERE codemp='".$this->ls_codemp."'".
				"	AND codnom='".$this->ls_codnom."'".
				"	AND codperi='".$this->ls_peractnom."'".
				"	AND codper='".$as_codper."'".
				"	AND codconc='".$as_codconc."'".
				"	AND tipprenom='".$as_tipprenomina."'";
		$li_row=$this->io_sql->execute($ls_sql);
	   	if($li_row===false)
	   	{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Prenómina MÉTODO->uf_update_prenomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
	   	}
	   	return $lb_valido; 
	}//end function uf_update_prenomina
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_evaluar_prestamospersonal($as_codper,$as_perhis,$as_anocurhis)
	{
		/////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_evaluar_prestamospersonal
		//		   Access: private
		//		Arguments: as_codper // código de personal
		//	    		   as_perhis // Período Histórico Anterior
		//				   as_anocurhis // Año en curso Histórico Anterior
		//	      Returns: lb_valido True si se evaluaron los conceptos ó False si hubo error
		//	  Description: Funcion que obtiene los prestamos por personal y los evalua
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 20/02/2006 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        $ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
		$ld_fecdes=$_SESSION["la_nomina"]["fecdesper"];
		$ls_stapre="1";/* STATUS -> PRESTAMO ACTIVO*/
		$ls_sql=" SELECT sno_prestamos.codconc, SUM(CASE WHEN sno_prestamosperiodo.moncuo IS NULL THEN 0 ELSE sno_prestamosperiodo.moncuo END) AS total ".
                "  FROM  sno_prestamos , sno_prestamosperiodo ".
                "  WHERE sno_prestamos.codemp='".$this->ls_codemp."' ".
                "    AND sno_prestamos.codnom='".$this->ls_codnom."' ".
				"	 AND sno_prestamos.codper='".$as_codper."' ".
				"    AND sno_prestamos.stapre='".$ls_stapre."' ".  
				//"    AND sno_prestamosperiodo.percob='".$ls_peractnom."' ".
				"    AND sno_prestamosperiodo.feciniper='".$ld_fecdes."' ".
				"    AND sno_prestamos.codemp=sno_prestamosperiodo.codemp ".
                "    AND sno_prestamos.codnom=sno_prestamosperiodo.codnom ".
                "    AND sno_prestamos.codper=sno_prestamosperiodo.codper ".
				"	 AND sno_prestamos.codtippre=sno_prestamosperiodo.codtippre ".
				"	 AND sno_prestamos.numpre=sno_prestamosperiodo.numpre ".
	            "  GROUP BY sno_prestamos.codconc ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Prenómina MÉTODO->uf_evaluar_prestamospersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_i=1;
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$li_valconhis=0;
				$li_valcon=($row["total"]*-1);
				$ls_codconc=$row["codconc"];
 				$lb_valido=$this->uf_load_historico($as_codper,$ls_codconc,"D",$li_valconhis,$as_perhis,$as_anocurhis);
				if($lb_valido)
				{
					$lb_valido=$this->uf_verificar_montos($as_codper,$ls_codconc,"D",$li_valcon,$li_valconhis);
				}//if
		  	}//while
			$this->io_sql->free_result($rs_data);	
		}//else	
		return $lb_valido;
	}//end function uf_evaluar_prestamospersonal
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_evaluar_vacacionespersonal($as_codper,$as_perhis,$as_anocurhis)
	{
		/////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_evaluar_vacacionespersonal
		//		   Access: private
		//		Arguments: as_codper // código de personal
		//	    		   as_perhis // Período Histórico Anterior
		//				   as_anocurhis // Año en curso Histórico Anterior
		//	      Returns: lb_valido True si se evaluaron los conceptos ó False si hubo error
		//	  Description: Funcion que obtiene los prestamos por personal y los evalua
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 20/02/2006 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		// Obtener si el personal está de Salida de Vacaciones
		$lb_valido=$this->io_vacacion->uf_load_salidavacacion($as_codper);
		if($lb_valido)
		{
			if($_SESSION["la_vacacion"]["envacacion"]==1)
			{
				$li_sueintvac=0;
				$li_suebonvac=0;
				$lb_valido=$this->io_vacacion->uf_load_sueldointegral_vac($as_codper,$li_sueintvac);
				if($lb_valido)
				{
					$lb_valido=$this->io_vacacion->uf_load_sueldobono_vac($as_codper,$li_suebonvac);
				}
				if($lb_valido)
				{
					$ls_codvac=$_SESSION["la_vacacion"]["codvac"];
					$lb_valido=$this->io_vacacion->uf_update_sueldointegral_vac($as_codper,$ls_codvac,$li_sueintvac,$li_suebonvac);
				}
				// Calculamos la Salida de Vacaciones
				$lb_valido=$this->uf_evaluar_conceptovacacion($as_codper,"S",$as_perhis,$as_anocurhis);
			}				
		}
		if($lb_valido)
		{
			// Obtener si el personal está de Reingreso de Vacaciones
			$lb_valido=$this->io_vacacion->uf_load_reingresovacacion($as_codper);
			if($lb_valido)
			{
				if($_SESSION["la_vacacion"]["envacacion"]==1)
				{
					// Calculamos el Reingreso de Vacaciones
					$lb_valido=$this->uf_evaluar_conceptovacacion($as_codper,"R",$as_perhis,$as_anocurhis);
				}
			}
		}
		if($lb_valido)
		{
			$li_desincorporar=$this->io_sno->uf_select_config("SNO","NOMINA","DESINCORPORAR DE NOMINA","0","C");
			if ($li_desincorporar == 0) // se aplica solo cuando no se desincorpora de la nómina
			{
				// Obtener si al personal no se le han cancelado la vacaciones 
				$lb_valido=$this->io_vacacion->uf_load_vacaciondisfrutada($as_codper);
				if($_SESSION["la_vacacion"]["envacacion"]==1)
				{
					$li_sueintvac=0;
					$li_suebonvac=0;
					$lb_valido=$this->io_vacacion->uf_load_sueldointegral_vac($as_codper,$li_sueintvac);
					if($lb_valido)
					{
						$lb_valido=$this->io_vacacion->uf_load_sueldobono_vac($as_codper,$li_suebonvac);
					}
					if($lb_valido)
					{
						$ls_codvac=$_SESSION["la_vacacion"]["codvac"];
						$lb_valido=$this->io_vacacion->uf_update_sueldointegral_vac($as_codper,$ls_codvac,$li_sueintvac,$li_suebonvac);
					}
					if($lb_valido)
					{
						// Calculamos la Salida
						$lb_valido=$this->uf_evaluar_conceptovacacion($as_codper,"S",$as_perhis,$as_anocurhis);
					}
				}		
			}		
		}
		return $lb_valido;
	}//end function uf_evaluar_vacacionespersonal
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_evaluar_conceptovacacion($as_codper,$as_tipo,$as_perhis,$as_anocurhis)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_evaluar_conceptovacacion
		//		   Access: private
		//	    Arguments: as_codper // código de personal
		//				   as_tipo // tipo de calculo si es de salida ó de reingreso
		//	    		   as_perhis // Período Histórico Anterior
		//				   as_anocurhis // Año en curso Histórico Anterior
		//	      Returns:	lb_valido True si se calculo correctamente la salida de vacaciones al personal False si no se calcularon bien
		//	  Description: función que dado el código de personal se calculan la salida de las vacaciones 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 20/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="";
		$lb_valido==$this->io_vacacion->uf_load_conceptovacacion($as_tipo,$as_codper,$ls_sql);
		if($lb_valido)
		{
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Calcular Prenómina MÉTODO->uf_evaluar_conceptovacacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			}
			else
			{
				while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
				{
					$li_conprenom=$row["conprenom"];
					if($li_conprenom==1) // Si este concepto se evalua en la prenómina
					{
						$ls_codconc=$row["codconc"];
						$ai_acumulado=0;
						$ai_acumuladopat=0;
						$ls_formula=$row["formula"];
						$ls_formulapat=$row["formulapat"];
						$ls_condicion=$row["condicion"];
						$li_glocon=$row["glocon"];
						$li_aplcon=$row["aplcon"];
						$ls_sigcon=$row["sigcon"];
						$li_minimo=$row["minimo"];
						$li_maximo=$row["maximo"];
						$li_minimopat=$row["minimopat"];
						$li_maximopat=$row["maximopat"];
						$li_valor=0;
						$li_valconhis=0;
						$lb_filtro=true;
						$lb_aplica=true;
						if (!(trim($ls_condicion)=="")) // Si tiene una condición
						{
							$lb_filtro=false;
							$lb_valido=$this->io_evaluador->uf_evaluar($as_codper,$ls_condicion,$lb_filtro);
						}
						if($li_glocon==0) // Si el concepto NO es global
						{
							if($li_aplcon==0) // Si el concepto NO se aplica al personal
							{
								$lb_aplica=false;
							}
						}
						if(($lb_valido)&&($lb_filtro)&&($lb_aplica))
						{
							$lb_valido=$this->uf_calcular_personal($as_codper,$ls_codconc,$li_valor,$ls_formula,$li_minimo,$li_maximo);		
							if(($ls_sigcon=="A")||($ls_sigcon=="B")) // Si son Asignaciones 
							{
								if($as_tipo=="S")
								{
									$as_tipovac="V1";
								}
								else
								{
									$as_tipovac="W1";
								}								
								$lb_valido=$this->uf_load_historico($as_codper,$ls_codconc,$as_tipovac,$li_valconhis,$as_perhis,$as_anocurhis);
							   	if($lb_valido)
							   	{
							   		$lb_valido=$this->uf_verificar_montos($as_codper,$ls_codconc,$as_tipovac,$li_valor,$li_valconhis);
							   	}
							}
							if(($ls_sigcon=="D")||($ls_sigcon=="E")) // Si son Deducciones 
							{
								if($as_tipo=="S")
								{
									$as_tipovac="V2";
								}
								else
								{
									$as_tipovac="W2";
								}								
								$li_valor=($li_valor*-1);
								$lb_valido=$this->uf_load_historico($as_codper,$ls_codconc,$as_tipovac,$li_valconhis,$as_perhis,$as_anocurhis);
							   	if($lb_valido)
							   	{
							   		$lb_valido=$this->uf_verificar_montos($as_codper,$ls_codconc,$as_tipovac,$li_valor,$li_valconhis);
							   	}
							}
							if($ls_sigcon=="P") // Si es un Aporte Patronal
							{
								if($as_tipo=="S")
								{
									$as_tipovac="V3";
								}
								else
								{
									$as_tipovac="W3";
								}								
								$li_valor=($li_valor*-1);
								$lb_valido=$this->uf_load_historico($as_codper,$ls_codconc,$as_tipovac,$li_valconhis,$as_perhis,$as_anocurhis);
							   	if($lb_valido)
							   	{
							   		$lb_valido=$this->uf_verificar_montos($as_codper,$ls_codconc,$as_tipovac,$li_valor,$li_valconhis);
							   	}
								if($lb_valido)
								{
									if($as_tipo=="S")
									{
										$as_tipovac="V4";
									}
									else
									{
										$as_tipovac="W4";
									}								
									$li_valor=0;
									$li_valconhis=0;
									$lb_valido=$this->uf_calcular_personal($as_codper,$ls_codconc,$li_valor,$ls_formulapat,$li_minimopat,$li_maximopat);		
									if($lb_valido)
									{
										$li_valor=($li_valor*-1);
										$lb_valido=$this->uf_load_historico($as_codper,$ls_codconc,$as_tipovac,$li_valconhis,$as_perhis,$as_anocurhis);
										if($lb_valido)
										{
											$lb_valido=$this->uf_verificar_montos($as_codper,$ls_codconc,$as_tipovac,$li_valor,$li_valconhis);
										}
									}
								}
							}
							if($ls_sigcon=="R") // Si es un Reporte
							{
								if($as_tipo=="S")
								{
									$as_tipovac="V5";
								}
								else
								{
									$as_tipovac="W5";
								}								
								$lb_valido=$this->uf_load_historico($as_codper,$ls_codconc,$as_tipovac,$li_valconhis,$as_perhis,$as_anocurhis);
								if($lb_valido)
								{
									$lb_valido=$this->uf_verificar_montos($as_codper,$ls_codconc,$as_tipovac,$li_valor,$li_valconhis);
								}
							}
						}
					}					
				}
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}//end function uf_evaluar_conceptovacacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_liberarsession()
	{
		/////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_liberarsession
		//		   Access: private
		//	      Returns: lb_valido True si se liberaron correctamente las sessiones y false si hubo error
		//	  Description: Función que libera las variables de sessión
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 20/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if(!array_key_exists("la_personalnomina",$_SESSION))
		{
			unset($_SESSION["la_personalnomina"]);
		}
		if(!array_key_exists("la_vacacionpersonal",$_SESSION))
		{
			unset($_SESSION["la_vacacionpersonal"]);
		}
		if(!array_key_exists("la_tablasueldo",$_SESSION))
		{
			unset($_SESSION["la_tablasueldo"]);
		}
		if(!array_key_exists("la_vacacion",$_SESSION))
		{
			unset($_SESSION["la_vacacion"]);
		}
  	  	return $lb_valido;
	}//end function uf_liberarsession
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_historicopersonal($as_codperdes,$as_codperhas,$as_perhis,$as_anocurhis)
	{
		/////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_historicopersonal
		//		   Access: private
		//	    Arguments: as_codperdes // Código del personal Desde
		//	    		   as_codperhas // Código del personal Hasta
		//				   as_perhis // Período Histórico Anterior
		//				   as_anocurhis // Año en curso Histórico Anterior
		//	      Returns: lb_valido True si se proceso el histórico correctamente ó False si hubo error
		//	  Description: Funcion que obtiene el personal que se encuentra en el histórico pero que no está en la nómina actual
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 20/02/2006 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ld_fecdesper=$_SESSION["la_nomina"]["fecdesper"];
		$ld_fechasper=$_SESSION["la_nomina"]["fechasper"];
		$ls_sql="SELECT codper, codconc, tipsal, valsal ".
				"  FROM sno_hsalida ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND anocur='".$as_anocurhis."' ".
				"   AND codperi='".$as_perhis."' ".
				"   AND codper IN (SELECT codper ".
				"					  FROM sno_personalnomina ".
				" 					 WHERE codemp='".$this->ls_codemp."' ".
				"                      AND codnom='".$this->ls_codnom."' ".
				"                      AND staper<>'1' ".
				"                      AND ((fecegrper>='".$ld_fecdesper."' AND fecegrper<='".$ld_fechasper."')".
				"                           OR (fecsusper>='".$ld_fecdesper."' AND fecsusper<='".$ld_fechasper."')))";
		if($as_codperdes!="")
		{
			$ls_sql=$ls_sql." AND sno_hsalida.codper>='".$as_codperdes."' ";
		}
		if($as_codperhas!="")
		{
			$ls_sql=$ls_sql." AND sno_hsalida.codper<='".$as_codperhas."' ";
		}

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Prenómina MÉTODO->uf_procesar_historicopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_i=1;
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codper=$row["codper"];
				$ls_codconc=$row["codconc"];
				$ls_tipsal=$row["tipsal"];
				$li_valsal=0;
				$li_valsalhis=$row["valsal"];
		   		$lb_valido=$this->uf_verificar_montos($ls_codper,$ls_codconc,$ls_tipsal,$li_valsal,$li_valsalhis);
		  	}//while
			$this->io_sql->free_result($rs_data);	
		}//else	
		return $lb_valido;
	}//end function uf_procesar_historicopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------	

	
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
?>