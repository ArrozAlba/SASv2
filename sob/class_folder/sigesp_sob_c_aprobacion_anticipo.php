<?php
class sigesp_sob_c_aprobacion_anticipo
 {
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $ls_codemp;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sob_c_aprobacion_anticipo($as_path)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sob_c_aprobacion_anticipo
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
	}// end function sigesp_sob_c_aprobacion_anticipo
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
	function uf_load_anticipo($as_codant,$ad_fecantdes,$ad_fecanthas,$as_tipooperacion)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_anticipo
		//		   Access: public
		//		 Argument: as_codant        // Numero del anticipo
		//                 ad_fecantdes     // Fecha (Registro) de inicio de la Busqueda
		//                 ad_fecanthas     // Fecha (Registro) de fin de la Busqueda
		//                 as_tipooperacion // Tipo de Operación
		//	  Description: Función que busca los anticipos
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 03/02/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sob_anticipo.codant, sob_anticipo.fecant, sob_anticipo.monto, sob_anticipo.codcon, sob_contrato.codasi, sob_anticipo.estapr ".
				"  FROM sob_anticipo, sob_contrato ".
				" WHERE sob_anticipo.codemp = '".$this->ls_codemp."'".
				"   AND sob_anticipo.codant LIKE '".$as_codant."' ".
				"   AND sob_anticipo.fecant >= '".$ad_fecantdes."' ".
				"   AND sob_anticipo.fecant <= '".$ad_fecanthas."' ".
				"   AND sob_anticipo.estant = 1 ".
				"   AND sob_anticipo.estspgscg = 0 ".
				"   AND sob_anticipo.estapr=".$as_tipooperacion."".
				"   AND sob_anticipo.codemp=sob_contrato.codemp ".
				"	AND sob_anticipo.codcon=sob_contrato.codcon ".
				" ORDER BY sob_anticipo.codant, sob_anticipo.codcon ";
		$this->rs_data=$this->io_sql->select($ls_sql);
		if($this->rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Contrato MÉTODO->uf_load_anticipo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_load_anticipo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_validar_estatus_anticipo($as_codant,$as_codcon,$as_estapr)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_estatus_anticipo
		//		   Access: private
		//		 Argument: as_codant  //  Codigo de anticipo
		//				   as_codcon  //  Còdigo del Contrato
		//				   as_estapr  //  Estatus de la aprobación del anticipo
		// 	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que valida el estatus de aprobacion del anticipo
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 26/02/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codant ".
				"  FROM sob_anticipo ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codant='".$as_codant."' ".
				"   AND codcon='".$as_codcon."' ".
				"   AND estant=1 ".
				"   AND estapr=".$as_estapr."";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Aprobacion MÉTODO->uf_validar_estatus_anticipo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_validar_estatus_anticipo
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_validar_cuentas($as_codant,$as_codcon)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_cuentas
		//		   Access: private
		//		 Argument: as_codant        // còdigo del anticipo
		//				   as_codcon  //  Còdigo del Contrato
		//	  Description: Función que busca que las cuentas presupuestarias estén en la programática seleccionada
		//				   de ser asi puede aprobar la sep de lo contrario no la apruebas
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sob_anticipo.sc_cuenta AS cuentaanticipo, rpc_proveedor.sc_cuenta AS cuentaproveedor, ".
				"		(SELECT COUNT(codemp) ".
				"		   FROM scg_cuentas ".
				"		  WHERE scg_cuentas.codemp = sob_anticipo.codemp ".
				"			AND scg_cuentas.sc_cuenta = sob_anticipo.sc_cuenta) AS existecuentaanticipo, ".		
				"		(SELECT COUNT(codemp) ".
				"		   FROM scg_cuentas ".
				"		  WHERE scg_cuentas.codemp = rpc_proveedor.codemp ".
				"			AND scg_cuentas.sc_cuenta = rpc_proveedor.sc_cuenta) AS existecuentaproveedor ".		
				"  FROM sob_anticipo, sob_contrato, sob_asignacion, rpc_proveedor  ".
				" WHERE sob_anticipo.codemp='".$this->ls_codemp."' ".
				"   AND sob_anticipo.codant='".$as_codant."'".
				"   AND sob_anticipo.codcon='".$as_codcon."'".
				"   AND sob_anticipo.codemp=sob_contrato.codemp ".
				"   AND sob_anticipo.codcon=sob_contrato.codcon ".
				"   AND sob_asignacion.codemp=sob_contrato.codemp ".
				"   AND sob_asignacion.codasi=sob_contrato.codasi ".
				"   AND rpc_proveedor.codemp=sob_asignacion.codemp ".
				"   AND rpc_proveedor.cod_pro=sob_asignacion.cod_pro ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Aprobacion MÉTODO->uf_validar_cuentas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			while((!$rs_data->EOF)&&($lb_valido))
			{
				$ls_sccuenta=$rs_data->fields["cuentaanticipo"];
				$li_existe=$rs_data->fields["existecuentaanticipo"];
				if(!($li_existe>0))
				{
					$lb_valido=false;
					$this->io_mensajes->message("La Cuenta Contable ".$ls_sccuenta." Asociada al anticipo no existe en el plan de cuenta "); 
				}
				$ls_sccuenta=$rs_data->fields["cuentaproveedor"];
				$li_existe=$rs_data->fields["existecuentaproveedor"];
				if(!($li_existe>0))
				{
					$lb_valido=false;
					$this->io_mensajes->message("La Cuenta Contable ".$ls_sccuenta." Asociada al proveedor no existe en el plan de cuenta "); 
				}
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_validar_cuentas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_estatus_anticipo($as_codant,$as_codcon,$as_estapr,$ad_fecapr,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_estatus_anticipo
		//		   Access: private
		//		 Argument: as_codant        // còdigo del anticipo
		//				   as_codcon  //  Còdigo del Contrato
		//                 as_estapr    //  Estatus en que se desea colocar
		//                 ad_fecapr //  Fecha de aprobacion 
		//                 aa_seguridad //  Arreglo que contiene informacion de seguridad
		// 	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que valida el estatus de aprobacion de la solicitud 
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
		$ls_sql="UPDATE sob_anticipo ".
				"   SET estapr = ".$as_estapr.", ".
				"       fecapr = '".$ad_fecapr."' ".
				" WHERE codemp = '".$this->ls_codemp."'".
				"	AND codant = '".$as_codant."' ".
				"	AND codcon = '".$as_codcon."' ";
		$this->io_sql->begin_transaction();				
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Aprobacion MÉTODO->uf_update_estatus_anticipo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			if($as_estapr==1)
			{
				$ls_descripcion ="Aprobó el anticipo <b>".$as_codant."</b> del contrato <b>".$as_codcon."</b> Asociado a la Empresa <b>".$this->ls_codemp."<b>";
			}
			else
			{
				$ls_descripcion ="Reversó la Aprobacion del anticipo <b>".$as_codant."</b> del contrato <b>".$as_codcon."</b> Asociado a la Empresa <b>".$this->ls_codemp."<b>";
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
	}// end function uf_update_estatus_anticipo
	//-----------------------------------------------------------------------------------------------------------------------------------	
}
?>