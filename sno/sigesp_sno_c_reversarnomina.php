<?php
class sigesp_sno_c_reversarnomina
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_fun_nomina;	
	var $ls_codemp;
	var $ls_codnom;
	var $ls_peractnom;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sno_c_reversarnomina()
	{	
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sno_c_reversarnomina
		//		   Access: public (sigesp_sno_p_reversarnomina)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
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
		$this->io_seguridad=new sigesp_c_seguridad();
		require_once("class_folder/class_funciones_nomina.php");
		$this->io_fun_nomina=new class_funciones_nomina();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
        $this->ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$this->ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
	}// end function sigesp_sno_c_reversarnomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_sno_p_reversarnomina)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
		unset($this->io_fun_nomina);
        unset($this->ls_codemp);
        unset($this->ls_codnom);
        unset($this->ls_peractnom);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_resumenpago($as_peractnom,&$ai_totasi,&$ai_totded,&$ai_totapoemp,&$ai_totapopat,&$ai_totnom,&$ai_nropro)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_resumenpago
		//		   Access: public (sigesp_sno_p_reversarnomina.php)
		//	    Arguments: as_peractnom  // período Actual de la nómina
		//				   ai_totasi  // Total de Asignaciones
		//				   ai_totded  // Total de Deducciones
		//				   ai_totapoemp  // Total de Aportes de Empleados
		//				   ai_totapopat  // Total de Aportes de Patron
		//				   ai_totnom  // Total de la Nómina
		//				   ai_nropro  // Número de personas a procesar
		//	      Returns: lb_valido True si se encontro ó False si no se encontró
		//	  Description: Función que obtiene el resumen de pago de la nómina 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT SUM(CASE WHEN asires IS NULL THEN 0 ELSE asires END) AS asign, SUM(CASE WHEN dedres IS NULL THEN 0 ELSE dedres END) AS deduc,".
				"		SUM(CASE WHEN apoempres IS NULL THEN 0 ELSE apoempres END) AS apoemp, ".
				"		SUM(CASE WHEN apopatres IS NULL THEN 0 ELSE apopatres END) AS apopat, SUM(CASE WHEN monnetres IS NULL THEN 0 ELSE monnetres END) AS totnom, ".
				"		count(codper) as totper ".
				"  FROM sno_resumen ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND codperi='".$as_peractnom."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Reversar Nómina MÉTODO->uf_load_resumenpago ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totasi=$this->io_fun_nomina->uf_formatonumerico($row["asign"]);
				$ai_totded=$this->io_fun_nomina->uf_formatonumerico(abs($row["deduc"]));
				$ai_totapoemp=$this->io_fun_nomina->uf_formatonumerico(abs($row["apoemp"]));
				$ai_totapopat=$this->io_fun_nomina->uf_formatonumerico(abs($row["apopat"]));
				$ai_totnom=$this->io_fun_nomina->uf_formatonumerico($row["totnom"]);
				$ai_nropro=$row["totper"];
			}
			$this->io_sql->free_result($rs_data);		
		}
		return $lb_valido;
	}// end function uf_load_resumenpago
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_reversarnomina($aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reversarnomina
		//		   Access: public (sigesp_sno_p_reversarnomina.php)
		//	    Arguments: aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el update
		//	  Description: Funcion que reversa la información de la nómina de un personal en específico
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$this->io_sql->begin_transaction();
		$lb_valido=$this->uf_delete_salidas();
		if($lb_valido)
		{
			$lb_valido=$this->uf_delete_resumen();
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_update_periodos($aa_seguridad);
		}
		if($lb_valido)
		{
			$this->io_sql->commit();
			$this->io_mensajes->message("La Nómina fue reversada.");
		}
		else
		{
			$this->io_sql->rollback();
			$this->io_mensajes->message("Ocurrio un error al reversar la nómina.");
		}
		return $lb_valido;
    }// end function uf_reversarnomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_salidas()
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_salidas
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina las salidas de todo el personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" DELETE ".
				" FROM sno_salida ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND codperi='".$this->ls_peractnom."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Reversar Nómina MÉTODO->uf_delete_salidas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
    }// end function uf_delete_salidas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_resumen()
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_resumen
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el update
		//	  Description: Funcion que elimina de la tabla resumen toda la informacíón
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
				"  FROM sno_resumen ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND codperi='".$this->ls_peractnom."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Reversar Nómina MÉTODO->uf_delete_resumen ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
    }// end function uf_delete_resumen
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_periodos($aa_seguridad)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_periodos
		//		   Access: private
		//	    Arguments: aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido true si realizo el update correctamente   false en caso contrario
		//	  Description: Funcion que actualiza en la tabla sno_periodo el monto total 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////////////////////////
		$ld_totper=0;
		$lb_valido=true;
		$ls_sql="UPDATE sno_periodo ".
				"   SET totper=".$ld_totper." ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codperi='".$this->ls_peractnom."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Reversar Nómina MÉTODO->uf_update_periodos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reversó la nómina ".$this->ls_codnom." para el período ".$this->ls_peractnom." ";
			$lb_valido=$this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido==false)
			{
				$this->io_mensajes->message("CLASE->Reversar Nómina MÉTODO->uf_update_periodos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
		}	
	   	return $lb_valido;	
	 }// end function uf_update_periodos
    //-----------------------------------------------------------------------------------------------------------------------------------
}
?>