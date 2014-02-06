<?php
class sigesp_snorh_c_fideiconfigurable
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $ls_codemp;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_snorh_c_fideiconfigurable()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_snorh_c_fideiconfigurable
		//		   Access: public (sigesp_snorh_d_fideiconfigurable)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 05/04/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include  = new sigesp_include();
		$io_conexion = $io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad=new sigesp_c_seguridad();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function sigesp_snorh_c_fideiconfigurable
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_snorh_d_fideiconfigurable)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 05/04/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
        unset($this->ls_codemp);		
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------
		
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_fideiconfigurable($ai_anocurfid, $as_codded, $as_codtipper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_fideiconfigurable
		//		   Access: private
		//	    Arguments: ai_anocurfid  // año en curso fideicomiso
		//				   as_codded     // codigo de dedicacion
		//                 as_codtipper  // codigo tipo personal
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el fideiconfigurable existe
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 05/04/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT anocurfid ".
				"  FROM sno_fideiconfigurable ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND anocurfid='".$ai_anocurfid."' ".
				"   AND codded='".$as_codded."' ".
				"   AND codtipper='".$as_codtipper."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Fideiconfigurable MÉTODO->uf_select_fideiconfigurable ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_fideiconfigurable
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_fideiconfigurable($ai_anocurfid, $as_codded, $as_codtipper, $ai_diabonvacfid, $ai_diabonfinfid, $as_cueprefid, $aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_fideiconfigurable
		//		   Access: private
		//	    Arguments: ai_anocurfid  // año en curso fideicomiso
		//				   as_codded     // codigo de dedicacion
		//                 as_codtipper  // codigo tipo personal
		//                 ai_diabonvacfid //dias de bono vacacional fideicomiso
		//                 ai_diabonfinfid //dias de bono fin de año fideicomiso		
		//				   as_cueprefid    // cuenta presupuestaria
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla sno_fideiconfigurable
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 05/04/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();
		$ls_sql="INSERT INTO sno_fideiconfigurable(codemp, anocurfid, codded, codtipper, diabonvacfid, diabonfinfid, cueprefid)VALUES".
				"('".$this->ls_codemp."','".$ai_anocurfid."','".$as_codded."','".$as_codtipper."','".$ai_diabonvacfid."',".
				"'".$ai_diabonfinfid."','".$as_cueprefid."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->fideiconfigurable MÉTODO->uf_insert_fideiconfigurable ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la Configuración de Fideicomiso. Año ".$ai_anocurfid." Dedicación ".$as_codded." Tipo Personal ".$as_codtipper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("La configuración del Fideicomiso fue registrada.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->fideiconfigurable MÉTODO->uf_insert_fideiconfigurable ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_fideiconfigurable
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_fideiconfigurable($ai_anocurfid,$as_codded,$as_codtipper,$ai_diabonvacfid,$ai_diabonfinfid,$as_cueprefid,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//    	 Function: uf_update_fideiconfigurable
		//	    Arguments: ai_anocurfid  // año en curso fideicomiso
		//				   as_codded     // codigo de dedicacion
		//                 as_codtipper  // codigo tipo personal
		//                 ai_diabonvacfid //dias de bono vacacional fideicomiso
		//                 ai_diabonfinfid //dias de bono fin de año fideicomiso		
		//				   as_cueprefid    // cuenta presupuestaria
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza en la tabla sno_fideiconfigurable
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 05/04/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();
		$ls_sql="UPDATE sno_fideiconfigurable ".
				"   SET diabonvacfid = ".$ai_diabonvacfid.", ".
				"       diabonfinfid = ".$ai_diabonfinfid.", ".
				"		cueprefid = '".$as_cueprefid."' ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND anocurfid='".$ai_anocurfid."'".
				"   AND codded='".$as_codded."'".
				"   AND codtipper='".$as_codtipper."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->fideiconfigurable MÉTODO->uf_update_fideiconfigurable ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			////////////////////////////////         SEGURIDAD               //////////////////////////////
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la Configuración de Fideicomiso. Año ".$ai_anocurfid." Dedicación ".$as_codded." Tipo Personal ".$as_codtipper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("La configuración del Fideicomiso fue Actualizada.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->fideiconfigurable MÉTODO->uf_update_fideiconfigurable ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_fideiconfigurable
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$ai_anocurfid,$as_codded,$as_codtipper,$ai_diabonvacfid,$ai_diabonfinfid,$as_cueprefid,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//	    Arguments: ai_anocurfid  // año en curso fideicomiso
		//				   as_codded     // codigo de dedicacion
		//                 as_codtipper  // codigo tipo personal
		//                 ai_diabonvacfid //dias de bono vacacional fideicomiso
		//                 ai_diabonfinfid //dias de bono fin de año fideicomiso		
		//				   as_cueprefid  //  cuenta presupeustaria
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que guarda en la tabla sno_fideiconfigurable
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 05/04/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		switch ($as_existe)
		{
			case "FALSE":
				if($this->uf_select_fideiconfigurable($ai_anocurfid, $as_codded, $as_codtipper)===false)
				{
					$lb_valido=$this->uf_insert_fideiconfigurable($ai_anocurfid,$as_codded,$as_codtipper,$ai_diabonvacfid,$ai_diabonfinfid,$as_cueprefid,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("La configuración del Fideicomiso ya existe, No la puede incluir.");
				}
				break;

			case "TRUE":
				if(($this->uf_select_fideiconfigurable($ai_anocurfid, $as_codded, $as_codtipper)))
				{
					$lb_valido=$this->uf_update_fideiconfigurable($ai_anocurfid,$as_codded,$as_codtipper,$ai_diabonvacfid,$ai_diabonfinfid,$as_cueprefid,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("La configuración del Fideicomiso no existe, No la puede actualizar.");
				}
				break;
		}
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_fideiconfigurable($ai_anocurfid,$as_codded,$as_codtipper,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_fideiconfigurable
		//	    Arguments: ai_anocurfid  // año en curso fideicomiso
		//				   as_codded     // codigo de dedicacion
		//                 as_codtipper  // codigo tipo personal
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina de la tabla sno_fideiconfigurabl
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 05/04/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM sno_fideiconfigurable ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND anocurfid='".$ai_anocurfid."'".
				"   AND codded='".$as_codded."'".
				"   AND codtipper='".$as_codtipper."'";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Fideiconfigurable MÉTODO->uf_delete_fideiconfigurable ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó la Configuración de Fideicomiso. Año ".$ai_anocurfid." Dedicación ".$as_codded." Tipo Personal ".$as_codtipper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("La configuración del Fideicomiso fue Eliminada.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Fideiconfigurable MÉTODO->uf_delete_Fideiconfigurable ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
    }// end function uf_delete_fideiconfigurable
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_dias_vacaagui($ai_anocurfid,$as_codded,$as_codtipper,&$ai_diavac,&$ai_diaagui)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_dias_vacaagui
		//		   Access: public (sigesp_snorh_c_fideicomiso)
		//	    Arguments: ai_anocurfid  // año en curso fideicomiso
		//				   as_codded     // codigo de dedicacion
		//                 as_codtipper  // codigo tipo personal
		//                 ai_diavac  // días de vacaciones
		//                 ai_diaagui  // días de aguinaldo 
		//	      Returns: lb_valido True si existe la configuración ó False si no existe
		//	  Description: Funcion que obtiene los días de vacaciones y de aguinaldo para esa dedicación y tipo de personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 12/04/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT diabonvacfid, diabonfinfid ".
				"  FROM sno_fideiconfigurable ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND anocurfid='".$ai_anocurfid."'".
				"   AND codded='".$as_codded."'".
				"   AND codtipper='".$as_codtipper."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Fideiconfigurable MÉTODO->uf_load_dias_vacaagui ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if(!$row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=false;
        		$this->io_mensajes->message("La configuración del fideicomiso para el Año->".$ai_anocurfid." Dedicación->".$as_codded." Tipo de Personal->".$as_codtipper." No se ha realizado.Debe configurarla para hacer el proceso."); 
			}
			else
			{
				$ai_diavac=$row["diabonvacfid"];
				$ai_diaagui=$row["diabonfinfid"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_dias_vacaagui
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>