<?php
class sigesp_sob_c_aprobacion_valuacion
 {
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $ls_codemp;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sob_c_aprobacion_valuacion($as_path)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sob_c_aprobacion_valuacion
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once($as_path."shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once($as_path."shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once($as_path."shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once($as_path."shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
		require_once($as_path."shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad= new sigesp_c_seguridad();
	    require_once($as_path."shared/class_folder/class_fecha.php");		
		$this->io_fecha= new class_fecha();
		$this->rs_data="";
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function sigesp_sob_c_aprobacion_valuacion
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public 
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
		unset($this->io_fecha);
        unset($this->ls_codemp);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_valuacion($as_codval,$ad_fecantdes,$ad_fecanthas,$as_tipooperacion)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_valuacion
		//		   Access: public
		//		 Argument: as_codval        // Numero del anticipo
		//                 ad_fecantdes     // Fecha (Registro) de inicio de la Busqueda
		//                 ad_fecanthas     // Fecha (Registro) de fin de la Busqueda
		//                 as_tipooperacion // Tipo de Operación
		//	  Description: Función que busca los anticipos
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 03/02/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sob_valuacion.codval, sob_valuacion.fecha, sob_valuacion.montotval, sob_valuacion.codcon, sob_contrato.codasi, sob_valuacion.estapr ".
				"  FROM sob_valuacion, sob_contrato ".
				" WHERE sob_valuacion.codemp = '".$this->ls_codemp."'".
				"   AND sob_valuacion.codval LIKE '".$as_codval."' ".
				"   AND sob_valuacion.fecha >= '".$ad_fecantdes."' ".
				"   AND sob_valuacion.fecha <= '".$ad_fecanthas."' ".
				"   AND sob_valuacion.estval = 1 ".
				"   AND sob_valuacion.estspgscg = 0 ".
				"   AND sob_valuacion.estapr=".$as_tipooperacion."".
				"   AND sob_valuacion.codemp=sob_contrato.codemp ".
				"	AND sob_valuacion.codcon=sob_contrato.codcon ".
				" ORDER BY sob_valuacion.codval, sob_valuacion.codcon ";
		$this->rs_data=$this->io_sql->select($ls_sql);
		if($this->rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Valuación MÉTODO->uf_load_valuacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_load_valuacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_validar_estatus_valuacion($as_codval,$as_codcon,$as_estapr)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_estatus_valuacion
		//		   Access: private
		//		 Argument: as_codval  //  Codigo de valuacion
		//				   as_codcon  //  Còdigo del Contrato
		//				   as_estapr  //  Estatus de la aprobación del anticipo
		// 	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que valida el estatus de aprobacion del anticipo
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 26/02/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codval ".
				"  FROM sob_valuacion ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codval='".$as_codval."' ".
				"   AND codcon='".$as_codcon."' ".
				"   AND estval=1 ".
				"   AND estapr=".$as_estapr."";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Aprobacion MÉTODO->uf_validar_estatus_valuacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if($rs_data->EOF)
			{
				$lb_existe=false;
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_existe;
	}// end function uf_validar_estatus_valuacion
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_estatus_valuacion($as_codval,$as_codcon,$as_estapr,$ad_fecapr,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_estatus_valuacion
		//		   Access: private
		//		 Argument: as_codval        // còdigo de valuación
		//				   as_codcon  //  Còdigo del Contrato
		//                 as_estapr    //  Estatus en que se desea colocar
		//                 ad_fecapr //  Fecha de aprobacion 
		//                 aa_seguridad //  Arreglo que contiene informacion de seguridad
		// 	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que valida el estatus de aprobacion de la valuación
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=$this->io_fecha->uf_valida_fecha_periodo($ad_fecapr,$this->ls_codemp);
		if (!$lb_valido)
		{
			$this->io_mensajes->message($this->io_fecha->is_msg_error);           
			return false;
		}
		if($as_estapr==0)
		{
			$ad_fecapr="1900-01-01";
		}
		$ad_fecapr=$this->io_funciones->uf_convertirdatetobd($ad_fecapr);
		$ls_sql="UPDATE sob_valuacion ".
				"   SET estapr = ".$as_estapr.", ".
				"       fecapr = '".$ad_fecapr."' ".
				" WHERE codemp = '".$this->ls_codemp."'".
				"	AND codval = '".$as_codval."' ".
				"	AND codcon = '".$as_codcon."' ";
		$this->io_sql->begin_transaction();				
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Aprobacion MÉTODO->uf_update_estatus_valuacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			if($as_estapr==1)
			{
				$ls_descripcion ="Aprobó la valuación <b>".$as_codval."</b> del contrato <b>".$as_codcon."</b> Asociado a la Empresa <b>".$this->ls_codemp."<b>";
			}
			else
			{
				$ls_descripcion ="Reversó la Aprobacion de la valuación <b>".$as_codval."</b> del contrato <b>".$as_codcon."</b> Asociado a la Empresa <b>".$this->ls_codemp."<b>";
			}
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if($lb_valido)
			{
				$this->io_sql->commit();
			}
			else
			{
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_estatus_valuacion
	//-----------------------------------------------------------------------------------------------------------------------------------	
}
?>