<?php
class sigesp_sno_c_reversarencargaduria
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
	function sigesp_sno_c_reversarencargaduria()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sno_c_reversarencargaduria
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 26/12/2008 								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
		
	}// end function sigesp_sno_c_reversarencargaduria
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_sno_p_prestamo)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 26/12/2008 								Fecha Última Modificación : 
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
  
 function uf_reversar($as_codenc,$ad_fecinienc, $ad_fecfinenc, $as_obsenc, $as_codper, $as_codnomenc, $as_codperenc,$as_estsuspernom, $aa_seguridad)
 {
 
 /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reversar
		//		   Access: private
		//	    Arguments: as_existe // variable que indica si la encargaduria se encuentra registrada
		//                 as_codenc // código de la encargaduría
		//                 ad_fecinienc // fecha de inicio de la encargaduría
		//                 ad_fecfinenc // fecha de finalización de la encargaduría
		//                 as_obsenc // observación de la encargaduría
		//                 as_codper // código de personal a quién se le va a hacer la encargaduría
		//                 as_codnomenc // código de la nomina del personal encargado
		//                 as_codperenc // código del personal encargado
		//                 as_estsuspernom // estatus para suspender el personal de la nomina
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que reversar el registro de la encargaduría
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 30/12/2008								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codnom=$this->ls_codnom;		
		if ($ls_codnom!=$as_codnomenc)
		{
			$as_tipenc='2';
		}
		else
		{
			$as_tipenc='1';
		}
		if (trim($as_obsenc)=="")
		{
			$as_obsenc='SIN OBSERVACION';
		}
		$ad_fecinienc=$this->io_funciones->uf_convertirdatetobd($ad_fecinienc);
		$ad_fecfinenc=$this->io_funciones->uf_convertirdatetobd($ad_fecfinenc);
		
		$this->io_sql->begin_transaction();
			
			
		if ($as_tipenc=='2') // cuando la encargaduria es en nóminas diferentes
		{			
			$lb_valido=$this->uf_actualizar_estado_personal_encargado($as_codnomenc, $as_codperenc,'1');
			if ($lb_valido)
			{
				$lb_valido=$this->uf_actualizar_personal_encargado_nomina_original($as_codperenc,$ad_fecfinenc,'3');				
			}
			if ($lb_valido)
			{
				$lb_valido=$this->uf_actualizar_constanes_personal_encargado($as_codperenc);				
			}
			if ($lb_valido)
			{
				$lb_valido=$this->uf_actualizar_conceptos_personal_encargado($as_codperenc);				
			}			
			if ($lb_valido)
			{
				$lb_valido=$this->uf_actualizar_estatus_personal_encargaduria($as_codper,'0');
			}
			if ($lb_valido)
			{
				if ($as_estsuspernom=='1')
				{
					$lb_valido=$this->uf_activar_personal_nomina($as_codper);
				}
			}					
			if ($lb_valido)
			{
				$lb_valido=$this->uf_update_encargaduria($as_codenc, $ad_fecfinenc, $as_obsenc,'2', $aa_seguridad);			
							
			}
		}
		else  // cuando la encargaduria es dentro de la misma nómina
		{
			$lb_valido=$this->uf_actualizar_estatus_personal_encargaduria($as_codper,'0');
			if ($lb_valido)
			{
				if ($as_estsuspernom=='1')
				{
					$lb_valido=$this->uf_activar_personal_nomina($as_codper);
				}
			}	
			if ($lb_valido)
			{
				$lb_valido=$this->uf_update_encargaduria($as_codenc, $ad_fecfinenc, $as_obsenc,'2', $aa_seguridad);	
			}					
								
		}				
		if($lb_valido)
		{
			$this->io_sql->commit(); 	
			$this->io_mensajes->message("La Encargaduría Fue Reversada");
		}
		else
		{
			$this->io_sql->rollback();	
			$this->io_mensajes->message("Ocurrio un error al Reversar la Encargaduría");		
		}
		return $lb_valido;
	}// end function uf_reversar
	//-----------------------------------------------------------------------------------------------------------------------------------

	function uf_update_encargaduria($as_codenc, $ad_fecfinenc, $as_obsenc, $as_estenc, $aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_encargaduria
		//		   Access: private
		//	    Arguments: as_codenc // código de la encargaduría
		//                 ad_fecfinenc // fecha de finalización de la encargaduría
		//                 as_obsenc // observación de la encargaduría                
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza el registro de la encargaduria
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 26/12/2008 								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
		$ls_sql="UPDATE sno_encargaduria ".
				"   SET fecfinenc='".$ad_fecfinenc."', ".
				"       obsenc='".$as_obsenc."', ".
				"      estenc = '".$as_estenc."' ".							
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codenc='".$as_codenc."' ";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Registro Encargaduria MÉTODO->uf_update_encargaduria ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el  Registro de Encargaduria ".$as_codenc." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if(!$lb_valido)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Registro Encargaduria MÉTODO->uf_update_encargaduria ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				
			}
		}
		return $lb_valido;
	}// end function uf_update_encargaduria
	//-----------------------------------------------------------------------------------------------------------------------------------  

function uf_actualizar_estatus_personal_encargaduria($as_codper,$as_estatus)
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_actualizar_estatus_personal_encargaduria
		//		   Access: private
		//	    Arguments: as_codper // código de personal 	
		//                 as_estatus // estatus de encargaduria del personal (1 si esta en encargadurio - 0 en caso contrario)
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza el estatus de personal en encargaduria
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 29/12/2008 								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_personalnomina ".
				"   SET estencper='".$as_estatus."' ".	
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codper='".$as_codper."' ";
	
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Registro Encargaduria MÉTODO->uf_actualizar_estatus_personal_encargaduria ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			
		}
		
		return $lb_valido;
	}// end function uf_actualizar_estatus_personal_encargaduria

//-----------------------------------------------------------------------------------------------------------------------------------  
function uf_actualizar_estado_personal_encargado($as_codnom, $as_codper,$as_estatus)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_actualizar_estado_personal_encargado
		//		   Access: private
		//	    Arguments: as_codper // código de personal encargado
		//                 as_codnom // código de la nomina del personal encargado
		//                 ad_fecinienc // fecha de inicio de la encargaduria
		//                 as_estatus // estatus del personal
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza el estatus del personal encargado en su nomina original
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 26/12/2008 								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
		$ls_sql="UPDATE sno_personalnomina ".
				"   SET staper='".$as_estatus."' ".	
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$as_codnom."' ".
				"   AND codper='".$as_codper."' ";
			
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Registro Encargaduria MÉTODO->uf_actualizar_estado_personal_encargado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			
		}
		
		return $lb_valido;
	}// end function uf_actualizar_estado_personal_encargado
	//-----------------------------------------------------------------------------------------------------------------------------------


    function uf_actualizar_personal_encargado_nomina_original($as_codperenc,$ad_fecfinenc,$as_estatus)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_actualizar_personal_encargado_nomina_original
		//		   Access: private
		//	    Arguments: as_codper // código de personal encargado	
		//                 ad_fecfinenc // fecha de finalización de la encargaduria
		//                 as_estatus // estatus del personal
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza el estatus del personal encargado en su nomina 
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 30/12/2008 								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
		$ls_sql="UPDATE sno_personalnomina ".
				"   SET staper='".$as_estatus."', ".
				"       fecegrper='".$ad_fecfinenc."' ".	
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codper='".$as_codperenc."' ";
			
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Registro Encargaduria MÉTODO->uf_actualizar_personal_encargado_nomina_original ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			
		}
		
		return $lb_valido;
	}// end function uf_actualizar_personal_encargado_nomina_original
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_actualizar_constanes_personal_encargado($as_codperenc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_actualizar_constanes_personal_encargado
		//		   Access: private
		//	    Arguments: as_codper // código de personal encargado	
		//                 ad_fecfinenc // fecha de finalización de la encargaduria
		//                 as_estatus // estatus del personal
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza las constantes del personal encargado cuando finaliza la encargaduría 
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 30/12/2008 								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
		/*$ls_sql="UPDATE sno_constantepersonal ".
				"   SET moncon=0, ".
				"       topcon=0, ".
				"       montopcon=0 ".	
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codper='".$as_codperenc."' ";*/
				
		$ls_sql="UPDATE sno_constantepersonal ".
				"   SET moncon=0, ".				
				"       montopcon=0 ".	
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codper='".$as_codperenc."' ";
				
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Registro Encargaduria MÉTODO->uf_actualizar_constanes_personal_encargado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			
		}
		
		return $lb_valido;
	}// end function uf_actualizar_constanes_personal_encargado
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_actualizar_conceptos_personal_encargado($as_codperenc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_actualizar_conceptos_personal_encargado
		//		   Access: private
		//	    Arguments: as_codper // código de personal encargado	
		//                 ad_fecfinenc // fecha de finalización de la encargaduria
		//                 as_estatus // estatus del personal
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza los conceptos del personal encargado cuando finaliza la encargaduría 
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 30/12/2008 								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
		$ls_sql="UPDATE sno_conceptopersonal ".
				"   SET aplcon=0, ".
				"       valcon=0, ".
				"       acuemp=0, ".
				"       acuiniemp=0, ".
				"       acupat=0,  ".
				"       acuinipat=0 ".					
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codper='".$as_codperenc."' ";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Registro Encargaduria MÉTODO->uf_actualizar_conceptos_personal_encargado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			
		}
		
		return $lb_valido;
	}// end function uf_actualizar_conceptos_personal_encargado
	//-----------------------------------------------------------------------------------------------------------------------------------
 

function uf_activar_personal_nomina($as_codper)
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_activar_personal_nomina
		//		   Access: private
		//	    Arguments: as_codper // código de personal 	
		//      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza activa el personal en encargaduria a la nómina original si este fue suspendido
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 29/12/2008 								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_personalnomina ".
				"   SET staper='1' ".	
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codper='".$as_codper."' ";
	
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Registro Encargaduria MÉTODO->uf_activar_personal_nomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			
		}
		
		return $lb_valido;
	}// end function uf_activar_personal_nomina

//-----------------------------------------------------------------------------------------------------------------------------------  
	
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
?>