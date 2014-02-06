<?php
class sigesp_scv_c_anulacionsolicitud
 {
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $ls_codemp;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_scv_c_anulacionsolicitud($as_path)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_scv_c_anulacionsolicitud
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 13/04/2008 								Fecha Última Modificación : 
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
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		
	}// end function sigesp_scv_c_anulacionsolicitud
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_sep_p_solicitud.php)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 02/05/2007								Fecha Última Modificación : 
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
	function uf_load_solicitudes($as_codsolvia,$ad_fecregdes,$ad_fecreghas,$as_tipooperacion)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_recepciones
		//		   Access: public
		//		 Argument: as_codsolvia     // Numero de Solicitud de Viaticos
		//                 ad_fecregdes     // Fecha (Emision) de inicio de la Busqueda
		//                 ad_fecreghas     // Fecha (Emision) de fin de la Busqueda
		//                 as_tipooperacion // Codigo de la Unidad Ejecutora
		//	  Description: Función que busca las solicitudes  a aanular o reversar anulacion
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: Ing. María Beatriz Unda
		// Fecha Creación: 13/04/2008								Fecha Última Modificación : 05/02/2009
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido = true;
		
		$ls_sql="SELECT scv_solicitudviatico.codsolvia, scv_solicitudviatico.fecsolvia,scv_rutas.desrut,scv_misiones.denmis".
				"  FROM scv_solicitudviatico,scv_rutas,scv_misiones".
				" WHERE scv_solicitudviatico.codemp='".$this->ls_codemp."'".
				"   AND estsolvia='R'".
				"   AND fecsolvia>='".$ad_fecregdes."'".
				"   AND fecsolvia<='".$ad_fecreghas."'".
				"   AND codsolvia like '".$as_codsolvia."'".
				"   AND scv_solicitudviatico.codemp=scv_misiones.codemp".
				"   AND scv_solicitudviatico.codmis=scv_misiones.codmis".
				"   AND scv_solicitudviatico.codemp=scv_rutas.codemp".
				"   AND scv_solicitudviatico.codrut=scv_rutas.codrut".
				"  GROUP BY scv_solicitudviatico.codsolvia,scv_solicitudviatico.fecsolvia,scv_rutas.desrut,scv_misiones.denmis ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Anulacion MÉTODO->uf_load_solicitudes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_solicitudes
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_validar_estatus_solicitud($as_codsolvia)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_recepciones
		//		   Access: public
		//		 Argument: as_codsolvia // Numero de Solicitud de Viaticos
		//	  Description: Función que busca las solicitudes  a aanular o reversar anulacion
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 13/04/2008								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_existe=false;
		$ls_sql="SELECT codsolvia ".
				"  FROM scv_solicitudviatico".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codsolvia='".$as_codsolvia."'".
				"   AND estsolvia='A'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Anulacion MÉTODO->uf_validar_estatus_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_existe=true;
			}
		}
		return $ls_existe;
	}// end function uf_load_solicitudes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_estatus_solicitud($as_codsolvia,$aa_seguridad) 
	{
		//////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_update_estatus_solicitud
		//	          Access:  public
		//	       Arguments:  $as_codsolvia  // Código de la Empresa.
		//              	   $aa_seguridad  // Arreglo de Seguridad
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de actualizar el estatus de la solicitud de viaticos
		//     Elaborado Por:  Ing. Luis Anibal Lang
		// Fecha de Creación:  13/04/2008        
		////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=false;
		$this->io_sql->begin_transaction();
		$ls_sql="UPDATE scv_solicitudviatico".
				"   SET estsolvia='A'".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codsolvia='".$as_codsolvia."'";
		$rs_data = $this->io_sql->execute($ls_sql);
		if ($rs_data===false)
		{
			$this->io_sql->rollback();
			$this->io_mensajes->message("CLASE->Anulacion MÉTODO->uf_update_estatus_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Anulo la Solicitud de Viaticos  ".$as_codsolvia." Asociado a la Empresa ".$this->ls_codemp;
			$ls_variable= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
						$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
						$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		     
			$this->io_sql->commit();
		}  		      
		return $lb_valido;
	} // fin de la function uf_update_estatus_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------

}
?>