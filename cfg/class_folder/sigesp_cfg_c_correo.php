<?php
class sigesp_cfg_c_correo
 {
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_id_process;
	var $ls_codemp;
	var $io_dscuentas;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_cfg_c_correo($as_path)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_cxp_c_recepcion
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 02/04/2007 								Fecha Última Modificación : 
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
		require_once($as_path."shared/class_folder/class_datastore.php");
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function sigesp_cxp_c_solicitudpago
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_cxp_p_recepcion.php)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 02/04/2007								Fecha Última Modificación : 
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
	function uf_load_configuracion_correo(&$ai_msjenvio,&$ai_msjsmtp,&$as_msjservidor,&$as_msjpuerto,&$ai_msjhtml,
											&$as_msjremitente)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_configuracion_correo
		//		   Access: private
		//		 Argument: $ai_msjenvio // Indica si se va a enviar mensajes
		//                 $ai_msjsmtp  // Indica si el servidor es smtp
		//                 $as_msjservidor  // Nombre del Servidor
		//                 $as_msjpuerto  // Puerto de envio de correo
		//                 $ai_msjhtml  // Indica si el mensaje es HTML
		//                 $as_msjremitente  // Correo electronico del remitente
		//				   aa_seguridad // arreglo de las variables de seguridad
		//	  Description: Función que carga la configuracion de correo electronico
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 02/01/2009								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ai_msjenvio=0;
		$ai_msjsmtp=0;
		$as_msjservidor="";
		$as_msjpuerto="";
		$ai_msjhtml=0;
		$as_msjremitente="";
		$ls_sql="SELECT msjenvio,msjsmtp,msjservidor,msjpuerto,msjhtml,msjremitente ".
				"  FROM sigesp_correo  ".
				" WHERE codemp='".$this->ls_codemp."' "; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Correo MÉTODO->uf_load_configuracion_correo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_msjenvio=$row["msjenvio"];
				$ai_msjsmtp=$row["msjsmtp"];
				$as_msjservidor=$row["msjservidor"];
				$as_msjpuerto=$row["msjpuerto"];
				$ai_msjhtml=$row["msjhtml"];
				$as_msjremitente=$row["msjremitente"];
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_validar_fecha_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_configuracion_correo()
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_configuracion_correo
		//		   Access: private
		//		 Argument: 
		//	  Description: Función que verifica si existe la configuracion de correo electronico
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 02/01/2009								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido="FALSE";
		$ls_sql="SELECT codemp ".
				"  FROM sigesp_correo  ".
				" WHERE codemp='".$this->ls_codemp."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Correo MÉTODO->uf_select_configuracion_correo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido="TRUE";
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_validar_fecha_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($ai_msjenvio,$ai_msjsmtp,$as_msjservidor,$as_msjpuerto,$ai_msjhtml,$as_msjremitente,$aa_seguridad)
	{		
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_configuracion_correo
		//		   Access: private
		//		 Argument: $ai_msjenvio // Indica si se va a enviar mensajes
		//                 $ai_msjsmtp  // Indica si el servidor es smtp
		//                 $as_msjservidor  // Nombre del Servidor
		//                 $as_msjpuerto  // Puerto de envio de correo
		//                 $ai_msjhtml  // Indica si el mensaje es HTML
		//                 $as_msjremitente  // Correo electronico del remitente
		//				   aa_seguridad // arreglo de las variables de seguridad
		//	  Description: Función que realiza las operaciones de la pantalla
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 02/01/2009								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;	
		$as_existe=$this->uf_select_configuracion_correo();
		$this->io_sql->begin_transaction();				
		switch ($as_existe)
		{
			case "FALSE":
				$lb_valido=$this->uf_insert_configuracion_correo($ai_msjenvio,$ai_msjsmtp,$as_msjservidor,$as_msjpuerto,
																 $ai_msjhtml,$as_msjremitente,$aa_seguridad);
				break;

			case "TRUE":
					$lb_valido=$this->uf_update_configuracion_correo($ai_msjenvio,$ai_msjsmtp,$as_msjservidor,$as_msjpuerto,
																     $ai_msjhtml,$as_msjremitente,$aa_seguridad);
				break;
		}
		if($lb_valido)
		{	
			$lb_valido=true;
			$this->io_sql->commit();
			$this->io_mensajes->message("La Configuración ha sido Registrada."); 
		}			
		else
		{
			$lb_valido=false;
			$this->io_mensajes->message("Ocurrio un Error al Registrar la Configuración."); 
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_configuracion_correo($ai_msjenvio,$ai_msjsmtp,$as_msjservidor,$as_msjpuerto,$ai_msjhtml,
											$as_msjremitente,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_configuracion_correo
		//		   Access: private
		//		 Argument: $ai_msjenvio // Indica si se va a enviar mensajes
		//                 $ai_msjsmtp  // Indica si el servidor es smtp
		//                 $as_msjservidor  // Nombre del Servidor
		//                 $as_msjpuerto  // Puerto de envio de correo
		//                 $ai_msjhtml  // Indica si el mensaje es HTML
		//                 $as_msjremitente  // Correo electronico del remitente
		//				   aa_seguridad // arreglo de las variables de seguridad
		//	  Description: Función que inserta la configuracion de correo electronico
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 02/01/2009								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$ls_sql="INSERT INTO sigesp_correo (codemp, msjenvio, msjsmtp, msjservidor, msjpuerto, msjhtml, msjremitente)".
				"	  VALUES ('".$this->ls_codemp."',".$ai_msjenvio.",".$ai_msjsmtp.",'".$as_msjservidor."',".
				" 			  '".$as_msjpuerto."',".$ai_msjhtml.",'".$as_msjremitente."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Correo MÉTODO->uf_insert_configuracion_correo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la configuracion de correo electronico del servidor  ".$as_msjservidor.
							 " Con el Puerto".$as_msjpuerto." Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}// end function uf_insert_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_configuracion_correo($ai_msjenvio,$ai_msjsmtp,$as_msjservidor,$as_msjpuerto,$ai_msjhtml,
											$as_msjremitente,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_configuracion_correo
		//		   Access: private
		//		 Argument: $ai_msjenvio // Indica si se va a enviar mensajes
		//                 $ai_msjsmtp  // Indica si el servidor es smtp
		//                 $as_msjservidor  // Nombre del Servidor
		//                 $as_msjpuerto  // Puerto de envio de correo
		//                 $ai_msjhtml  // Indica si el mensaje es HTML
		//                 $as_msjremitente  // Correo electronico del remitente
		//				   aa_seguridad // arreglo de las variables de seguridad
		//	  Description: Función que inserta la configuracion de correo electronico
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 02/01/2009								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sigesp_correo ".
				"   SET msjenvio = '".$ai_msjenvio."', msjsmtp = '".$ai_msjsmtp."',msjservidor = '".$as_msjservidor."',".
				"       msjpuerto = '".$as_msjpuerto."', msjhtml = '".$ai_msjhtml."',msjremitente = '".$as_msjremitente."'".
				" WHERE codemp = '".$this->ls_codemp."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Correo MÉTODO->uf_update_configuracion_correo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la configuracion de correo electronico del servidor  ".$as_msjservidor.
							 " Con el Puerto".$as_msjpuerto." Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}// end function uf_update_estatus_procedencia
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>