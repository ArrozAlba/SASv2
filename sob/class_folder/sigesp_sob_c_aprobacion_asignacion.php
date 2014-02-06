<?php
class sigesp_sob_c_aprobacion_asignacion
 {
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $ls_codemp;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sob_c_aprobacion_asignacion($as_path)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sob_c_aprobacion_asignacion
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
	}// end function sigesp_sob_c_aprobacion_asignacion
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_sep_p_solicitud.php)
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
	function uf_load_asignaciones($as_codasi,$ad_fecasides,$ad_fecasihas,$as_tipooperacion)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_asignaciones
		//		   Access: public
		//		 Argument: as_codasi        // Numero de la Asignación
		//                 ad_fecregdes     // Fecha (Registro) de inicio de la Busqueda
		//                 ad_fecreghas     // Fecha (Registro) de fin de la Busqueda
		//                 as_tipooperacion // Tipo de Operación
		//	  Description: Función que busca las asignaciones
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 03/02/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sob_asignacion.codasi, sob_asignacion.codobr, sob_asignacion.estapr, sob_asignacion.fecasi, sob_asignacion.montotasi, ".
				"       (SELECT nompro".
				"          FROM rpc_proveedor".
				"         WHERE sob_asignacion.codemp=rpc_proveedor.codemp ".
				"           AND sob_asignacion.cod_pro=rpc_proveedor.cod_pro) AS nombre".
				"  FROM sob_asignacion ".
				" WHERE sob_asignacion.codemp = '".$this->ls_codemp."'".
				"   AND sob_asignacion.codasi LIKE '".$as_codasi."' ".
				"   AND sob_asignacion.fecasi >= '".$ad_fecasides."' ".
				"   AND sob_asignacion.fecasi <= '".$ad_fecasihas."' ".
				"   AND (sob_asignacion.estasi=1 OR sob_asignacion.estasi=6)".
				"   AND sob_asignacion.estspgscg = 0".
				"   AND sob_asignacion.estapr=".$as_tipooperacion."".
				" ORDER BY sob_asignacion.codasi, sob_asignacion.codobr ";
		$this->rs_data=$this->io_sql->select($ls_sql);
		if($this->rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Aprobacion MÉTODO->uf_load_asignaciones ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_load_asignaciones
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_validar_estatus_asignacion($as_codasi,$as_estapr)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_estatus_asignacion
		//		   Access: private
		//		 Argument: as_codasi        // Numero de la Asignación
		//				   as_estapr  //  Estatus de la aprobación de la asignación
		// 	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que valida el estatus de aprobacion de la asignación 
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 26/02/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codasi ".
				"  FROM sob_asignacion ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codasi='".$as_codasi."' ".
				"   AND (sob_asignacion.estasi=1 OR sob_asignacion.estasi=6)".
				"   AND estapr=".$as_estapr."";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Aprobacion MÉTODO->uf_validar_estatus_asignacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_validar_estatus_asignacion
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_validar_cuentas($as_codasi)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_cuentas
		//		   Access: private
		//		 Argument: as_codasi        // Numero de la Asignación
		//	  Description: Función que busca que las cuentas presupuestarias estén en la programática seleccionada
		//				   de ser asi puede aprobar la sep de lo contrario no la apruebas
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, spg_cuenta, monto, ".
				"	    (SELECT (asignado-(comprometido+precomprometido)+aumento-disminucion) ".
				"		   FROM spg_cuentas ".
				"		  WHERE spg_cuentas.codemp = sob_cuentasasignacion.codemp ".
				"			AND spg_cuentas.codestpro1 = sob_cuentasasignacion.codestpro1 ".
				"		    AND spg_cuentas.codestpro2 = sob_cuentasasignacion.codestpro2 ".
				"		    AND spg_cuentas.codestpro3 = sob_cuentasasignacion.codestpro3 ".
				"		    AND spg_cuentas.codestpro4 = sob_cuentasasignacion.codestpro4 ".
				"		    AND spg_cuentas.codestpro5 = sob_cuentasasignacion.codestpro5 ".
				"		    AND spg_cuentas.estcla = sob_cuentasasignacion.estcla ".
				"			AND spg_cuentas.spg_cuenta = sob_cuentasasignacion.spg_cuenta) AS disponibilidad, ".		
				"		(SELECT COUNT(codemp) ".
				"		   FROM spg_cuentas ".
				"		  WHERE spg_cuentas.codemp = sob_cuentasasignacion.codemp ".
				"			AND spg_cuentas.codestpro1 = sob_cuentasasignacion.codestpro1 ".
				"		    AND spg_cuentas.codestpro2 = sob_cuentasasignacion.codestpro2 ".
				"		    AND spg_cuentas.codestpro3 = sob_cuentasasignacion.codestpro3 ".
				"		    AND spg_cuentas.codestpro4 = sob_cuentasasignacion.codestpro4 ".
				"		    AND spg_cuentas.codestpro5 = sob_cuentasasignacion.codestpro5 ".
				"		    AND spg_cuentas.estcla = sob_cuentasasignacion.estcla ".
				"			AND spg_cuentas.spg_cuenta = sob_cuentasasignacion.spg_cuenta) AS existe ".		
				"  FROM sob_cuentasasignacion  ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codasi='".$as_codasi."'";
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
				$ls_codestpro1=$rs_data->fields["codestpro1"];
				$ls_codestpro2=$rs_data->fields["codestpro2"];
				$ls_codestpro3=$rs_data->fields["codestpro3"];
				$ls_codestpro4=$rs_data->fields["codestpro4"];
				$ls_codestpro5=$rs_data->fields["codestpro5"];
				$ls_spg_cuenta=$rs_data->fields["spg_cuenta"];
				$li_monto=$rs_data->fields["monto"];
				$li_disponibilidad=$rs_data->fields["disponibilidad"];
				$li_existe=$rs_data->fields["existe"];
				if($li_existe>0)
				{
					if($li_monto>$li_disponibilidad)
					{
						$li_monto=number_format($li_monto,2,",",".");
						$li_disponibilidad=number_format($li_disponibilidad,2,",",".");
						$this->io_mensajes->message("No hay Disponibilidad en la cuenta ".$ls_spg_cuenta." Disponible=[".$li_disponibilidad."] Cuenta=[".$li_monto."]"); 
					}
				}
				else
				{
					$lb_valido=false;
					$this->io_mensajes->message("La cuenta ".$ls_spg_cuenta." No Existe en la Estructura ".$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5.""); 
				}
				$rs_data->MoveNext();
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_validar_cuentas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_estatus_solicitud($as_codasi,$as_estapr,$ad_fecapr,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_estatus_solicitud
		//		   Access: private
		//	    Arguments: as_codasi    //  Còdigo de Asignación
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
		$ls_sql="UPDATE sob_asignacion ".
				"   SET estapr = ".$as_estapr.", ".
				"       fecapr = '".$ad_fecapr."' ".
				" WHERE codemp = '".$this->ls_codemp."'".
				"	AND codasi = '".$as_codasi."' ";
		$this->io_sql->begin_transaction();				
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Aprobacion MÉTODO->uf_update_estatus_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			if($as_estapr==1)
			{
				$ls_descripcion ="Aprobó la Asignación <b>".$as_codasi."</b> Asociado a la Empresa <b>".$this->ls_codemp."<b>";
			}
			else
			{
				$ls_descripcion ="Reversó la Aprobacion de la Asignación <b>".$as_codasi."</b> Asociado a la Empresa <b>".$this->ls_codemp."<b>";
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
	}// end function uf_update_estatus_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------	
}
?>