<?php
class sigesp_snorh_c_sueldominimo
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $ls_codemp;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_snorh_c_sueldominimo()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_snorh_c_sueldominimo
		//		   Access: public (sigesp_snorh_d_sueldominimo)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/02/2008 								Fecha Última Modificación : 
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
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function sigesp_snorh_c_sueldominimo
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_snorh_d_ct_met)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/02/2008 								Fecha Última Modificación : 
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
	function uf_select_sueldominimo($as_codigo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_sueldominimo
		//		   Access: private
 		//	    Arguments: as_codigo  // código del sueldo minimo
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el sueldo minimo está registrado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/02/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codsuemin ".
			    "  FROM sno_sueldominimo ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codsuemin='".$as_codigo."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Sueldo Minimo MÉTODO->uf_select_sueldominimo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_sueldominimo
	//-----------------------------------------------------------------------------------------------------------------------------------
   
	//-----------------------------------------------------------------------------------------------------------------------------------		
	function uf_insert_sueldominimo($as_codsuemin, $ai_anosuemin, $as_gacsuemin, $as_decsuemin, $ad_fecvigsuemin, $ai_monsuemin, $as_obssuemin, $aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_sueldominimo
		//		   Access: private
 		//	    Arguments: as_codsuemin  // código del sueldo mínimo
 		//	    		   ai_anosuemin  // Año del sueldo minimo
 		//	    		   as_gacsuemin  // Número de Gaceta 
 		//	    		   as_decsuemin  // Número de Decreto 
 		//	    		   ad_fecvigsuemin  //  Fecha de Entrada en vigencia
 		//	    		   ai_monsuemin  // Monto del sueldo Mínimo
 		//	    		   as_obssuemin  // Obervación
 		//	    		   aa_seguridad  // arreglo de seguridad
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que inserta en la tabla sno_sueldominimo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/02/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_sueldominimo(codemp, codsuemin, anosuemin, gacsuemin, decsuemin, fecvigsuemin, monsuemin, obssuemin)". 
			    "     VALUES ('".$this->ls_codemp."','".$as_codsuemin."',".$ai_anosuemin.",'".$as_gacsuemin."','".$as_decsuemin."',".
				"			  '".$ad_fecvigsuemin."',".$ai_monsuemin.",'".$as_obssuemin."')";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Sueldo Minimo MÉTODO->uf_insert_sueldominimo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el Sueldo Mínimo ".$as_codsuemin;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Sueldo Minimo fue Registrado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Sueldo Minimo MÉTODO->uf_insert_sueldominimo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_sueldominimo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------		
	function uf_update_sueldominimo($as_codsuemin, $ai_anosuemin, $as_gacsuemin, $as_decsuemin, $ad_fecvigsuemin, $ai_monsuemin, $as_obssuemin, $aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_sueldominimo
		//		   Access: private
 		//	    Arguments: as_codsuemin  // código del sueldo mínimo
 		//	    		   ai_anosuemin  // Año del sueldo minimo
 		//	    		   as_gacsuemin  // Número de Gaceta 
 		//	    		   as_decsuemin  // Número de Decreto 
 		//	    		   ad_fecvigsuemin  //  Fecha de Entrada en vigencia
 		//	    		   ai_monsuemin  // Monto del sueldo Mínimo
 		//	    		   as_obssuemin  // Obervación
 		//	    		   aa_seguridad  // arreglo de seguridad
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que inserta en la tabla sno_sueldominimo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/02/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
		$ls_sql="UPDATE sno_sueldominimo ".
			    "   SET anosuemin = ".$ai_anosuemin.", ".
				"       gacsuemin = '".$as_gacsuemin."',".
				"       decsuemin = '".$as_decsuemin."', ".
				"		fecvigsuemin = '".$ad_fecvigsuemin."', ".
				"       monsuemin = ".$ai_monsuemin.", ".
				"		obssuemin = '".$as_obssuemin."' ".
				" WHERE codemp = '".$this->ls_codemp."' ".
				"   AND codsuemin = '".$as_codsuemin."' ";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Sueldo Minimo MÉTODO->uf_update_sueldominimo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Sueldo Mínimo ".$as_codsuemin;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Sueldo Minimo fue Actualizado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Sueldo Minimo MÉTODO->uf_update_sueldominimo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_sueldominimo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe, $as_codsuemin, $ai_anosuemin, $as_gacsuemin, $as_decsuemin, $ad_fecvigsuemin, $ai_monsuemin, $as_obssuemin, $aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_snorh_d_sueldominimo)
 		//	    Arguments: as_codsuemin  // código del sueldo mínimo
 		//	    		   ai_anosuemin  // Año del sueldo minimo
 		//	    		   as_gacsuemin  // Número de Gaceta 
 		//	    		   as_decsuemin  // Número de Decreto 
 		//	    		   ad_fecvigsuemin  //  Fecha de Entrada en vigencia
 		//	    		   ai_monsuemin  // Monto del sueldo Mínimo
 		//	    		   as_obssuemin  // Obervación
 		//	    		   aa_seguridad  // arreglo de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que guarda en la tabla sno_sueldominimo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/02/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		$ai_monsuemin=str_replace(".","",$ai_monsuemin);
		$ai_monsuemin=str_replace(",",".",$ai_monsuemin);	
		$ad_fecvigsuemin=$this->io_funciones->uf_convertirdatetobd($ad_fecvigsuemin);			
		switch ($as_existe)
		{
			case "FALSE":
				if($this->uf_select_sueldominimo($as_codsuemin)===false)
				{
					$lb_valido=$this->uf_insert_sueldominimo($as_codsuemin, $ai_anosuemin, $as_gacsuemin, $as_decsuemin, $ad_fecvigsuemin, 
															 $ai_monsuemin, $as_obssuemin, $aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("El Sueldo Minimo ya existe, no la puede incluir.");
				}
				break;

			case "TRUE":
				if(($this->uf_select_sueldominimo($as_codsuemin)))
				{
					$lb_valido=$this->uf_update_sueldominimo($as_codsuemin, $ai_anosuemin, $as_gacsuemin, $as_decsuemin, $ad_fecvigsuemin, 
															 $ai_monsuemin, $as_obssuemin, $aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("El Sueldo Minimo no existe, no la puede actualizar.");
				}
				break;
		}
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete($as_codsuemin,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete
		//		   Access: public (sigesp_snorh_d_sueldominimo)
 		//	    Arguments: as_codsuemin  // código del sueldominimo
 		//	    		   aa_seguridad  // arreglo de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que elimina en la tabla sno_sueldominimo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/02/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
				"  FROM sno_sueldominimo ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codsuemin='".$as_codsuemin."' ";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Sueldo Minimo MÉTODO->uf_delete ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó el Sueldo Minimo ".$as_codsuemin;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Sueldo Minimo fue Eliminado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Sueldo Minimo MÉTODO->uf_delete ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_delete
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>