<?php
ini_set('precision','15');
class class_sigesp_int_spi extends class_sigesp_int
{
	var $io_function;
	var $sig_int;
	var $io_int_scg;
	var $io_fecha;
	var $is_msg_error;
	var $io_sql;
	var $io_connect;
	var $int_spgctas;
	var $io_include;
	var $io_msg;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function class_sigesp_int_spi()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: class_sigesp_int_spi
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->io_include=new sigesp_include();
		$this->io_function=new class_funciones();	
		$this->sig_int=new class_sigesp_int();
		$this->io_int_scg=new class_sigesp_int_scg();
		$this->io_fecha=new class_fecha();
		$this->ls_dabatase_target=$_SESSION["ls_data_des"];		
		//$this->io_connect=$this->io_include->uf_conectar($this->ls_dabatase_target);
		$this->io_connect=$this->io_include->uf_conectar_otra_bd($_SESSION['sigesp_servidor_apr'],$_SESSION['sigesp_usuario_apr'],
									   $_SESSION['sigesp_clave_apr'], $_SESSION['sigesp_basedatos_apr'], 
									   $_SESSION['sigesp_gestor_apr']);
		$this->io_sql=new class_sql($this->io_connect);
		$this->io_msg = new class_mensajes();
	} // end function class_sigesp_int_spi
	//-----------------------------------------------------------------------------------------------------------------------------------

    ///////////////////////////////////////////////////////Generacion de Plan de cuentas////////////////////////////////////////////////////
   
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spi_obtener_nivel($as_cuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spi_obtener_nivel
		//		   Access: public 
		//       Argument: as_cuenta // Cuenta
		//	  Description: obtiene el nivel de la cuenta de ingreso
		//	      Returns: nivel de la cuenta
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_nivel=0;
		$li_anterior=0;
		$li_longitud=0;
		$ls_cadena="";
	    $this->uf_init_niveles();
		$li_nivel = count($this->ia_niveles_spi);
		do
		{
			$li_anterior=$this->ia_niveles_spi[ $li_nivel - 1 ]  + 1;
			$li_longitud=$this->ia_niveles_spi[ $li_nivel ] - $this->ia_niveles_spi[ $li_nivel - 1 ];
			$ls_cadena=substr(trim($as_cuenta), $li_anterior , $li_longitud ); 
			$li=intval($ls_cadena);
			if($li>0)
			{
				return $li_nivel;
			}
			$li_nivel = $li_nivel - 1;
		}while($li_nivel > 1);
		return $li_nivel;
	} // end function uf_spi_obtener_nivel
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spi_next_cuenta_nivel($as_cuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spi_next_cuenta_nivel
		//		   Access: public 
		//       Argument: as_cuenta // Cuenta
		//	  Description: Este método obtiene el siguiente nivel de la cuenta
		//	      Returns: cuenta referencia nivel anterior
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $this->uf_init_niveles();
		$li_MaxNivel=0;
		$li_nivel=0;
		$li_anterior=0;
		$li_longitud=0;
		$ls_cadena="";
		$li_MaxNivel=count($this->ia_niveles_spi);
		$li_nivel=$this->uf_spi_obtener_nivel($as_cuenta);
		if($li_nivel>1)
		{
			$li_anterior=$this->ia_niveles_spi[$li_nivel - 1]; 
			$ls_cadena=substr($as_cuenta,0,$li_anterior+1);  
			$li_longitud=strlen($ls_cadena);
			$li_long=(($this->ia_niveles_spi[$li_MaxNivel]+1) - $li_longitud);
			$ls_newcadena=$this->io_function->uf_cerosderecha(trim($ls_cadena),$li_long+$li_longitud);
			$ls_cadena=$ls_newcadena;
		} 
		return $ls_cadena;
	}//end function uf_spg_next_cuenta_nivel
	//-----------------------------------------------------------------------------------------------------------------------------------
   
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spi_select_cuenta_movimiento($as_spi_cuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spi_select_cuenta_movimiento
		//		   Access: public 
		//       Argument: as_spi_cuenta // Cuenta
		//	  Description: Este método verifica si la cuenta posee movimientos asociados
		//	      Returns: boolean si existe o no 
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		$la_empresa=$_SESSION["la_empresa"];
		$ls_codemp=$la_empresa["codemp"];
		$ls_sql="SELECT spi_cuenta, monto, orden ".
				"  FROM spi_dt_cmp".		
				" WHERE codemp='".$ls_codemp."' ".
				"   AND spi_cuenta='".$as_spi_cuenta."'"; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error = "CLASE->sigesp_int_spi MÉTODO->uf_spi_select_cuenta_movimiento ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_existe;
	}//end function uf_spi_select_cuenta_movimiento
	//-----------------------------------------------------------------------------------------------------------------------------------
   
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spi_padcuenta_plan($as_formpre,$as_cuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spi_padcuenta_plan
		//		   Access: public 
		//       Argument: as_formpre // formato de presupuesto
		//       		   as_cuenta // Cuenta
		//	  Description: Este método rellena valores en 0 a la derecha de la cuenta
		//	      Returns: Cadena
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_MaxNivel=0;
		$li_longitud=0;
		$li_len_cadena=0;
		$ls_Cadena="";
		$ls_formato="";
		$ls_formatoaux="";
		$ls_formato=trim($as_formpre);
		$ls_formatoaux=str_replace( "-", "",$ls_formato);
		$ls_formatoaux=$this->io_function->uf_trim($ls_formatoaux);
		$li_longitud=strlen($ls_formatoaux);
		$ls_cadena=$this->io_function->uf_trim($as_cuenta);
		$li_len_cadena=strlen($ls_cadena);
		$ls_cadena=$this->io_function->uf_rellenar_der ( $ls_cadena , 0 , $li_longitud);
		$as_formpre=$ls_formatoaux;
		return $ls_cadena;
	} // end function uf_spi_padcuenta_plan
	//-----------------------------------------------------------------------------------------------------------------------------------
   
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spi_pad_cuenta($as_cuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spi_pad_cuenta
		//		   Access: public 
		//       Argument: as_cuenta // Cuenta
		//	  Description: Este método rellena valores en 0 a la derecha de la cuenta
		//	      Returns: Cadena
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->uf_init_niveles();
		$li_MaxNivel=count($this->ia_niveles_spi);
		$ls_cadena=trim($as_cuenta);
		$ls_cadena=$this->io_function->uf_rellenar_der($ls_cadena,"0",$this->ia_niveles_spi[$li_MaxNivel-1]);
		return $ls_cadena;
	} // end function uf_spi_pad_cuenta
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spi_cuenta_sin_cero($as_cuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spi_cuenta_sin_cero
		//		   Access: public 
		//       Argument: as_cuenta // Cuenta
		//	  Description: Este método retorna la cuenta sin ceros a la derecha
		//	      Returns: Cadena
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->uf_init_niveles();
		$li_nivel=0;
		$li_anterior=0;
		$ls_cadena="";
		$li_nivel=$this->uf_spi_obtener_nivel($as_cuenta);
		$li_anterior=$this->ia_niveles_spi[ $li_nivel ] ;
		$li_len=strlen($li_anterior);
		$ls_cadena=substr($as_cuenta, 0, $li_anterior+1);
		return $ls_cadena;
	} //end function uf_spi_cuenta_sin_cero
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spi_cuenta_recortar_next($as_cuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spi_cuenta_recortar_next
		//		   Access: public 
		//       Argument: as_cuenta // Cuenta
		//	  Description: Este método retorna la cuenta sin ceros a la derecha
		//	      Returns: string
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->uf_init_niveles();
		$li_nivel=0;
		$li_anterior=0;
		$ls_cadena="";
		$li_nivel=$this->uf_spi_obtener_nivel($as_cuenta);
		$li_anterior=$this->ia_niveles_spi[$li_nivel] ;
		$li_len=strlen($li_anterior);
		$ls_cadena=substr($as_cuenta,0,$li_anterior+1);
		return $ls_cadena;
	} //end function uf_spi_cuenta_recortar_next
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_spi_insert_cuenta($as_spi_cuenta,$as_denominacion,$as_sc_cuenta,$as_status,$as_nivel,$as_referencia)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spi_insert_cuenta
		//		   Access: public 
		//       Argument: as_spi_cuenta // Cuenta
		//       		   as_denominacion // Denominación de la Cuenta
		//       		   as_sc_cuenta // Cuenta contable
		//       		   as_status // Estatus de la Cuenta
		//       		   as_nivel // nivel de la Cuenta
		//       		   as_referencia // Cuenta de Referencia
		//	  Description: Este método inserta una cuenta de gasto en la tabla maestra 
		//	      Returns: un boolean 
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$data=$_SESSION["la_empresa"];
		$ls_codemp=$data["codemp"];
		$ls_sql=" INSERT INTO spi_cuentas(codemp, spi_cuenta, denominacion, status, sc_cuenta, previsto, devengado, cobrado, ".
			 	" cobrado_anticipado, aumento, disminucion, distribuir, enero, febrero, marzo, abril, mayo, junio, julio, agosto, ".
			 	" septiembre, octubre, noviembre, diciembre, nivel, referencia) values ('".$ls_codemp."','".$as_spi_cuenta."', ".
			 	" '".$as_denominacion."','".$as_status."','".$as_sc_cuenta."',0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,".$as_nivel.",'".$as_referencia."')";
		$li_rows=$this->io_sql->execute($ls_sql);
		if($li_rows===false)
		{
			$lb_valido=false;	
			$this->is_msg_error="CLASE->sigesp_int_spi MÉTODO->uf_spi_insert_cuenta ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		return $lb_valido;
    } // end function uf_spi_insert_cuenta
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spi_update_cuenta($as_spi_cuenta,$as_denominacion,$as_sc_cuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spi_update_cuenta
		//		   Access: public 
		//       Argument: as_spi_cuenta // Cuenta
		//       		   as_denominacion // Denominación de la Cuenta
		//       		   as_sc_cuenta // Cuenta contable
		//	  Description: Este método actualiza una cuenta de gasto en la tabla maestra 
		//	      Returns: un boolean 
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$data=$_SESSION["la_empresa"];
		$ls_codemp=$data["codemp"];
		$ls_sql="UPDATE spi_cuentas ".
				"   SET denominacion='".$as_denominacion."', ".
				"       sc_cuenta='".$as_sc_cuenta.="' ".
			 	" WHERE codemp='".$ls_codemp."'  ".
				"   AND spi_cuenta='".$as_spi_cuenta."'";
		$li_numrows=$this->io_sql->execute($ls_sql);
		if($li_numrows===false)
		{
			$lb_valido=false;	
			$this->is_msg_error="CLASE->sigesp_int_spi MÉTODO->uf_spi_update_cuenta ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		return $lb_valido;
	} // end function 
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spi_select_cuenta_sin_cero($is_codemp,$as_cuenta_cero)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spi_select_cuenta_sin_cero
		//		   Access: public 
		//       Argument: is_codemp // Código de Empresa
		//       		   as_cuenta_cero // Cuenta
		//	  Description: Verifica la cantidad existente de la consulta
		//	      Returns: un boolean 
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=false;
	    $li_rows=0;
		$ls_sql="SELECT count(*) as nveces ".
				"  FROM spi_cuentas ".
		        " WHERE codemp='".$is_codemp."' ".
				"   AND spi_cuenta LIKE '".$as_cuenta_cero."%' ";
		$rs_data=$this->io_sql->select($ls_sql);
        if($rs_data===false)
	    {
		   	$lb_valido=false;	
			$this->is_msg_error="CLASE->sigesp_int_spi MÉTODO->uf_spi_select_cuenta_sin_cero ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
	    }
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_rows=$row["nveces"];
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
	    return $li_rows;
	 }	// end function uf_spi_select_cuenta_sin_cero
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spi_select_cuenta($as_codemp,$as_spi_cuenta,&$as_status,&$as_denominacion,&$as_scgcuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spi_select_cuenta
		//		   Access: public 
		//       Argument: as_codemp // Código de Empresa
		//       		   as_spi_cuenta // Cuenta
		//       		   as_status // Estatus de la Cuenta
		//       		   as_denominacion // Denominación de la Cuenta
		//       		   as_scgcuenta // Cuenta Contable
		//	  Description: Verifica si existe o no la cuenta y retorna informacion de la cuenta
		//	      Returns: un boolean 
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_cuenta="";
		$ls_denominacion="";
		$ls_status="";
		$ls_scgcuenta="";
		$lb_existe=false;
		$ls_sql="SELECT spi_cuenta,status,denominacion,sc_cuenta ".
				"  FROM spi_cuentas ".
		   		" WHERE codemp='".$as_codemp."' ".
				"   AND trim(spi_cuenta)= '".rtrim($as_spi_cuenta)."'" ;
		$rs_data = $this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;	
			$this->is_msg_error="CLASE->sigesp_int_spi MÉTODO->uf_spi_select_cuenta ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_denominacion=$row["denominacion"];
				$as_denominacion=$ls_denominacion;
				$ls_status=$row["status"];
				$as_status=$ls_status;
				$ls_scgcuenta=$row["sc_cuenta"];
				$as_scgcuenta=$ls_scgcuenta;
				$lb_existe = true;	 			
			}
			else
			{
				$this->is_msg_error = "La cuenta Presupuestaria ".$as_spi_cuenta." no esta registrada";
			}    
			$this->io_sql->free_result($rs_data);
		}
		return $lb_existe;
	}	// end function uf_spi_select_cuenta
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spi_delete_cuenta($as_codemp, $as_spi_cuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spi_delete_cuenta
		//		   Access: public 
		//       Argument: as_codemp // Código de Empresa
		//       		   as_spi_cuenta // Cuenta
		//	  Description: Borra de la tabla maestra la cuenta de gasto
		//	      Returns: un boolean 
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM spi_cuentas ".
			 	" WHERE codemp='".$as_codemp."' ".
				"   AND spi_cuenta ='".$as_spi_cuenta."'";
		$li_rows = $this->io_sql->execute($ls_sql);
		if($li_rows===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_spi MÉTODO->uf_spi_delete_cuenta ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		return $lb_valido;
	}	// end function uf_spi_delete_cuenta
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_plan_unico_cuenta($as_cuenta,$as_denominacion,$as_status)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_plan_unico_cuenta
		//		   Access: public 
		//       Argument: as_cuenta // Cuenta
		//       		   as_denominacion // Denominación de la Cuenta
		//       		   as_status // Estatus de la Cuenta
		//	  Description: Método que inserta cuenta y denominacion en el plan unico de recursos
		//	      Returns: un boolean 
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($this->uf_select_plan_unico_cuenta($as_cuenta,$as_denominacion))
		{
			if($as_status=='C')		   
			{
				$ls_sql="UPDATE sigesp_plan_unico_re ".
						"   SET denominacion='".$as_denominacion."'".
						" WHERE sig_cuenta='".trim($as_cuenta)."'";
				$li_rows=$this->io_sql->execute($ls_sql);
				if($li_rows===false)
				{
					$this->is_msg_error="CLASE->sigesp_int_spi MÉTODO->uf_insert_plan_unico_cuenta ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
					return false;
				}
			}
			else
			{
				$this->is_msg_error="Cuenta ya existe introduzca un nuevo codigo.";
				return false;
			}
		}
		else
		{
			$ls_sql="INSERT INTO sigesp_plan_unico_re (sig_cuenta,denominacion) VALUES('".trim($as_cuenta)."' , '".trim($as_denominacion)."')";
			$li_rows=$this->io_sql->execute($ls_sql);
			if($li_rows===false)
			{
				$this->is_msg_error="CLASE->sigesp_int_spi MÉTODO->uf_insert_plan_unico_cuenta ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
				return false;
			}
		}
		return $lb_valido;
	}	// end function  uf_insert_plan_unico_cuenta
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_plan_unico_cuenta($as_cuenta,$as_denominacion)
    {	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_plan_unico_cuenta
		//		   Access: public 
		//       Argument: as_cuenta // Cuenta
		//       		   as_denominacion // Denominación de la Cuenta
		//	  Description: Verifica si existe o no en la tabla de SIGESP_Plan_Unico
		//	      Returns: un boolean 
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = false;
		$ls_sql="SELECT sig_cuenta,denominacion ".
				"  FROM sigesp_plan_unico_re ".
				" WHERE sig_cuenta='". $as_cuenta ."'"; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_spi MÉTODO->uf_select_plan_unico_cuenta ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true;
				$is_den_plan_cta=$row["denominacion"];
				$as_denominacion=$row["denominacion"];
			}
			$this->io_sql->free_result($rs_data);	   
		}
		return $lb_existe;
	} // end function  uf_select_plan_unico_cuenta
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	///////////////////////////////////////////FIN METODOS PLAN CUENTA////////////////////////////////////////////////////////////////////
   
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spi_delete_movimiento($as_cuenta,$as_procede_doc,$as_documento,$as_operacion)	
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spi_delete_movimiento
		//		   Access: public 
		//       Argument: as_cuenta // Cuenta
		//       		   as_procede_doc // Procede del Documento
		//       		   as_documento // Número de Documento
		//       		   as_operacion // Operación del Documento
		//	  Description: Este método elimina un movimiento presupuestario en las tablas de detalle comprobante de ingresos 
		//	      Returns: un boolean 
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 01/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->ib_db_error=false;
		$this->is_msg_error="";
		$data=$_SESSION["la_empresa"];
		$ls_codemp=$data["codemp"];
		$ld_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
		$ls_sql="DELETE FROM spi_dt_cmp ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND procede='".$this->is_procedencia."' ".
				"   AND comprobante='".$this->is_comprobante."' ".
				"   AND fecha='".$ld_fecha."' ".
				"   AND codban='".$this->as_codban."' ".
				"   AND ctaban='".$this->as_ctaban."' ".
				"   AND spi_cuenta='".$as_cuenta."' ".
				"   AND procede_doc='".$as_procede_doc."' ".
				"   AND documento ='".$as_documento."' ".
				"   AND operacion ='".$as_operacion."'";
		$li_rows=$this->io_sql->execute($ls_sql);
		if($li_rows===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_spi MÉTODO->uf_spi_delete_movimiento ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		return $lb_valido;
	} // end function uf_spi_delete_movimiento
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spi_insert_movimiento_ingreso($as_cuenta,$as_procede_doc,$as_documento,$as_operacion,$as_descripcion,$adec_monto)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spi_insert_movimiento_ingreso
		//		   Access: public 
		//       Argument: as_cuenta // Cuenta
		//       		   as_procede_doc // Procede del Documento
		//       		   as_documento // Número de Documento
		//       		   as_operacion // Operación del Documento
		//       		   as_descripcion // Descripcion del Movimiento
		//       		   adec_monto // Monto del Movimiento
		//	  Description: Este método inserta un movimiento presupuestario en las tablas de detalle comprobante de ingresos 
		//	      Returns: un boolean 
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 01/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_orden=0;
		$li_rows=0;
		$lb_valido=true;		
		$this->is_msg_error="";	
		$li_orden=$this->uf_spi_obtener_orden_movimiento();		
		$ls_sql=" INSERT INTO spi_dt_cmp (codemp,procede,comprobante,fecha,spi_cuenta,procede_doc,documento,operacion, ".
		        "                         descripcion,monto,orden,codban,ctaban) ".
				" VALUES('".$this->is_codemp."','".$this->is_procedencia."','".$this->is_comprobante."','".$this->id_fecha."', ".
				"        '".$as_cuenta."','".$as_procede_doc."','".$as_documento."','".$as_operacion."','".$as_descripcion."', ".
				"        ".$adec_monto.",".$li_orden.",'".$this->as_codban."','".$this->as_ctaban."')" ;
		$li_result=$this->io_sql->execute($ls_sql);
		if($li_result===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_spi MÉTODO->uf_spi_insert_movimiento_ingreso ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		return $lb_valido;
	} // end function uf_spi_delete_movimiento
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spi_obtener_orden_movimiento()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spi_obtener_orden_movimiento
		//		   Access: public 
		//       Argument: 
		//	  Description: Retorna el número de orden del movimiento de ingresos spi
		//	      Returns: li_orden // numero del orden
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 01/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_orden=0;
		$ls_sql="SELECT count(*) as orden ".
			 	"  FROM spi_dt_cmp ".
			 	" WHERE codemp='".$this->is_codemp."' ".
				"   AND procede='".$this->is_procedencia."' ".
				"   AND comprobante='".$this->is_comprobante."' ".
				"   AND fecha='".$this->id_fecha."'".
				"   AND codban='".$this->as_codban."'". 
				"   AND ctaban='".$this->as_ctaban."'"; 
		$this->is_msg_error="";
		$this->ib_db_error=false;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_spi MÉTODO->uf_spi_obtener_orden_movimiento ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($ls_sql))
			{
				$li_orden=$row["orden"];
			}			
			return true;
		}
		return $li_orden;
	} // end function uf_spi_obtener_orden_movimiento
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spi_saldo_ajusta($as_codemp,$as_mensaje,$as_cuenta,$as_status,$adec_monto_anterior,$adec_monto_actual,
								 &$adec_previsto,&$adec_aumento,&$adec_disminucion,&$adec_devengado,&$adec_cobrado,
								 &$adec_cobrado_anticipado)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spi_saldo_ajusta
		//		   Access: public 
		//       Argument: as_codemp // Código de Empresa
		//				   as_mensaje // Mensaje del Movimiento
		//				   as_cuenta // Cuenta del Movimiento
		//				   as_status // Estatus del Movimiento
		//				   adec_monto_anterior // Monto Anterior del Movimiento
		//				   adec_monto_actual // Monto Actual del Movimiento
		//				   adec_previsto // Monto Previsto
		//				   adec_aumento // Monto Aumento
		//				   adec_disminucion // Monto Disminución
		//				   adec_devengado // Monto Devengado
		//				   adec_cobrado // Monto Cobrado
		//				   adec_cobrado_anticipado // Monto Cobrado Anticipado
		//	  Description: ajusta el saldo de una cuenta
		//	      Returns: boolean 
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 01/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_nivel=0; 		
		$lb_procesado=false;
		$ld_disponible=($adec_previsto+$adec_aumento-$adec_disminucion)-$adec_devengado;
		/*print "PREVISTO>>>>>".$adec_previsto."<br>";
		print "DEVENGADO>>>>".$adec_devengado."<br>";
		print "COBRADO>>>>".$adec_cobrado."<br>";
		print "DISPONIBLE>>>".$ld_disponible."<br>";
		print "ANTERIOR>>>>>".$adec_monto_anterior."<br>";
		print "ACTUAL>>>>>>>".$adec_monto_actual."<br>";
		print "MENSAJE>>>>>>".$as_mensaje."<br>";*/
		$li_nivel=$this->uf_spi_obtener_nivel($as_cuenta);
		$as_mensaje=trim(strtoupper($as_mensaje));
		//	I-Previsto
	    $li_pos_i=strpos($as_mensaje,"I"); 
		if(!($li_pos_i===false))
		{ 
			$adec_previsto=$adec_previsto-$adec_monto_anterior+$adec_monto_actual;
			$lb_procesado=true;
		}
		//	A-Aumento
		$li_pos_a=strpos($as_mensaje,"A"); 
		if(!($li_pos_a===false))
		{
			$adec_aumento=$adec_aumento-$adec_monto_anterior+$adec_monto_actual;
			$lb_procesado=true;
		}
		//	D-Disminución
		$li_pos_d=strpos($as_mensaje,"D"); 
		if(!($li_pos_d===false))
		{ 
			if(round($adec_monto_actual,2)<=round($adec_previsto,2))
			{ 
				$adec_disminucion=$adec_disminucion-$adec_monto_anterior+$adec_monto_actual;
			}
			else
			{
				$lb_valido=false;
				$this->io_msg->message( "El monto a disminuir es mayor que el previsto. ");			
			}
			$lb_procesado=true;
		}
		//	E-Devengado
		$li_pos_e=strpos($as_mensaje,"E"); 
		if(!($li_pos_e===false))
		{ 
			// se quito por solicitud de Anibal 22/03/2007
			//if($adec_monto_actual<=$adec_previsto)
			//{ 
				$adec_devengado=$adec_devengado-$adec_monto_anterior+$adec_monto_actual;
			//}
			//else
			//{ 
			//	$lb_valido = false;
			//	$this->io_msg->message( "El monto a devengar es mayor que el previsto. ");			
			//}
			$lb_procesado = true;
		}
		//	C-Cobrado
		$li_pos_c=strpos($as_mensaje,"C"); 
		if(!($li_pos_c===false))
		{
			$li_total=$adec_cobrado-$adec_monto_anterior+$adec_monto_actual;
			if(round($li_total,2)<=round($adec_devengado,2))
			{ 
				$adec_cobrado=$adec_cobrado-$adec_monto_anterior+$adec_monto_actual;
			}
			else
			{ 
				$lb_valido = false;
				$this->io_msg->message("El monto a cobrar es mayor que el devengado.");
			}
			$lb_procesado = true;
		}
		//	N-Cobrado Anticipado
		$li_pos_n=strpos($as_mensaje,"N"); 
		if(!($li_pos_n===false))
		{ 
			$adec_cobrado_anticipado=$adec_cobrado_anticipado-$adec_monto_anterior+$adec_monto_actual;
			$lb_procesado=true;
		}
		if(!$lb_procesado)
		{
			$this->is_msg_error = "El codigo de operacion es Invalido.";
			$lb_valido=false;
		}
		return $lb_valido;
	} // end function uf_spi_saldo_ajusta
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spi_saldo_ingreso($as_codemp,$as_cuenta,$as_mensaje,$adec_monto_anterior,$adec_monto_actual)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spi_saldo_ingreso
		//		   Access: public 
		//       Argument: as_codemp // Código de Empresa
		//				   as_cuenta // Cuenta del Movimiento
		//				   as_mensaje // Mensaje del Movimiento
		//				   adec_monto_anterior // Monto Anterior del Movimiento
		//				   adec_monto_actual // Monto Actual del Movimiento
		//	  Description: actualiza el monto saldo cuenta de ingreso
		//	      Returns: boolean 
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 01/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nextCuenta=$as_cuenta;
		$li_nivel=$this->uf_spi_obtener_nivel($ls_nextCuenta);
		 while(($li_nivel >= 1)&&($lb_valido)&&($ls_nextCuenta!=''))
		 {
			if($this->uf_spi_saldo_select($as_codemp,$ls_nextCuenta,&$ls_status,&$ldec_previsto,&$ldec_aumento,&$ldec_disminucion,&$ldec_devengado,&$ldec_cobrado,&$ldec_cobrado_anticipado))
		    {
				if($this->uf_spi_saldo_ajusta($as_codemp,$as_mensaje,$ls_nextCuenta,$ls_status,$adec_monto_anterior,$adec_monto_actual,&$ldec_previsto,&$ldec_aumento,&$ldec_disminucion,&$ldec_devengado,&$ldec_cobrado,&$ldec_cobrado_anticipado))
				{
					if(!$this->uf_spi_saldo_update($as_codemp,$ls_nextCuenta,$ldec_previsto,$ldec_aumento,$ldec_disminucion,$ldec_devengado,$ldec_cobrado,$ldec_cobrado_anticipado))
					{
						$lb_valido = false;
					}
				}
				else
				{
					$lb_valido = false;
				}
			}
			else
			{
				$lb_valido = false;
			}
			if($this->uf_spi_obtener_nivel($ls_nextCuenta )==1)
			{
				break;
			}
			$ls_nextCuenta=$this->uf_spi_next_cuenta_nivel($ls_nextCuenta);
			$li_nivel=$this->uf_spi_obtener_nivel($ls_nextCuenta);  
		}
		return $lb_valido;
	} // end function uf_spi_saldo_ingreso
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spi_saldo_select($as_codemp,$as_cuenta,$as_status,&$adec_previsto,&$adec_aumento,&$adec_disminucion,&$adec_devengado,
								 &$adec_cobrado,&$adec_cobrado_ant)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spi_saldo_select
		//		   Access: public 
		//       Argument: as_codemp // Código de Empresa
		//				   as_cuenta // Cuenta del Movimiento
		//				   as_status // Estatus de la Cuenta
		//				   adec_previsto // Monto Previsto
		//				   adec_aumento // Monto Aumento
		//				   adec_disminucion // Monto Disminución
		//				   adec_devengado // Monto Devengado
		//				   adec_cobrado // Monto Cobrado
		//				   adec_cobrado_anticipado // Monto Cobrado Anticipado
		//	  Description: verifica si existe un saldo a esa cuenta
		//	      Returns: boolean 
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 01/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_sql="SELECT status ".
				"  FROM spi_cuentas ".
				" WHERE codemp='".$as_codemp."' ".
				"   AND spi_cuenta='".$as_cuenta."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_spi MÉTODO->uf_spi_saldo_select ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
	        $lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_status=$row["status"];
				$lb_valido=true;
			}
			else
			{
				$this->is_msg_error="CLASE->sigesp_int_spi MÉTODO->uf_spi_saldo_select ERROR->La cuenta no existe ".$as_cuenta;
				$lb_valido=false;
			}
		}
		if($as_status=="C") // Cuenta de Movimiento
		{
			if($lb_valido)
			{
				$ls_operacion="previsto";
				$adec_previsto=0;
				$lb_valido=$this->uf_calcular_disponible_por_rango($as_cuenta,&$adec_previsto,$ls_operacion);
			}
			if($lb_valido)
			{
				$ls_operacion="aumento";
				$adec_aumento=0;
				$lb_valido=$this->uf_calcular_disponible_por_rango($as_cuenta,&$adec_aumento,$ls_operacion);
			}
			if($lb_valido)
			{
				$ls_operacion="disminucion";
				$adec_disminucion=0;
				$lb_valido=$this->uf_calcular_disponible_por_rango($as_cuenta,&$adec_disminucion,$ls_operacion);
			}
			if($lb_valido)
			{
				$ls_operacion="devengado";
				$adec_devengado=0;
				$lb_valido=$this->uf_calcular_disponible_por_rango($as_cuenta,&$adec_devengado,$ls_operacion);
			}
			if($lb_valido)
			{
				$ls_operacion="cobrado";
				$adec_cobrado=0;
				$lb_valido=$this->uf_calcular_disponible_por_rango($as_cuenta,&$adec_cobrado,$ls_operacion);
			}
			if($lb_valido)
			{
				$ls_operacion="cobrado_ant";
				$adec_cobrado_ant=0;
				$lb_valido=$this->uf_calcular_disponible_por_rango($as_cuenta,&$adec_cobrado_ant,$ls_operacion);
			}
		}
		if($as_status=="S") // Cuenta de Madre
		{
			$ls_sql="SELECT status,previsto,aumento,disminucion,devengado,cobrado,cobrado_anticipado ".
					"  FROM spi_cuentas ".
					" WHERE codemp='".$as_codemp."' ".
					"   AND spi_cuenta='".$as_cuenta."'";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->is_msg_error="CLASE->sigesp_int_spi MÉTODO->uf_spi_saldo_select ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
				$lb_valido=false;
			}
			else
			{
				if($row=$this->io_sql->fetch_row($rs_data))
				{
					$as_status=$row["status"];
					$adec_previsto=$row["previsto"];
					$adec_aumento=$row["aumento"];
					$adec_disminucion=$row["disminucion"];
					$adec_devengado=$row["devengado"];
					$adec_cobrado=$row["cobrado"];
					$adec_cobrado_ant=$row["cobrado_anticipado"];				
					$lb_valido=true;
				}
				else
				{
					$this->is_msg_error="CLASE->sigesp_int_spi MÉTODO->uf_spi_saldo_select ERROR->La cuenta no existe ".$as_cuenta;
					$lb_valido=false;
				}
			}
		}
		return $lb_valido;	
	} // end function uf_spi_saldo_select
	//-----------------------------------------------------------------------------------------------------------------------------------
		
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spi_saldo_update($as_codemp,$as_cuenta,$adec_previsto,$adec_aumento,$adec_disminucion,$adec_devengado,
								 $adec_cobrado,$adec_cobrado_anticipado)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spi_saldo_update
		//		   Access: public 
		//       Argument: as_codemp // Código de Empresa
		//				   as_cuenta // Cuenta del Movimiento
		//				   adec_previsto // Monto Previsto
		//				   adec_aumento // Monto Aumento
		//				   adec_disminucion // Monto Disminución
		//				   adec_devengado // Monto Devengado
		//				   adec_cobrado // Monto Cobrado
		//				   adec_cobrado_anticipado // Monto Cobrado Anticipado
		//	  Description: actualiza el saldo de una cuenta
		//	      Returns: boolean 
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 01/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->ib_db_error=false;
		$this->is_msg_error="";
		$ls_sql="UPDATE spi_cuentas ".
			    "   SET previsto=".$adec_previsto.",".
				"       aumento=".$adec_aumento.",".
				"       disminucion=".$adec_disminucion.",".
				"       devengado=".$adec_devengado.",".
				"       cobrado=".$adec_cobrado.",".
				"       cobrado_anticipado=".$adec_cobrado_anticipado." ".
			    " WHERE codemp='".$as_codemp."' ".
				"   AND spi_cuenta='".$as_cuenta."'";
		$li_result=$this->io_sql->execute($ls_sql);
		if($li_result===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_spi MÉTODO->uf_spi_saldo_update ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;		
		}
		return $lb_valido;
	} // end function uf_spi_saldo_update
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spi_select_movimiento($as_cuenta,$as_procede_doc,$as_documento,$as_operacion,&$ldec_monto,&$li_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spi_select_movimiento
		//		   Access: public 
		//       Argument: as_cuenta // Cuenta del Movimiento
		//				   as_procede_doc // Procede del movimiento
		//				   as_documento // Número de Documento
		//				   as_operacion // Operación del Documento
		//				   ldec_monto // Monto del movimiento 
		//				   li_orden // Orden del movimiento
		//	  Description: Este método verifica si el movimiento ya existe o no en la tabla de movimientos presupuestario de ingreso
		//	      Returns: boolean 
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 01/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		$ldec_monto=0;
		$li_orden=0;
		$ls_procede=$this->is_procedencia;
		$ls_comprobante=$this->is_comprobante;
		$ld_fecha=$this->id_fecha;
		$ld_fecha=$this->io_function->uf_convertirdatetobd($ld_fecha);
		$ls_sql="SELECT monto, orden ".
				"  FROM spi_dt_cmp ".
				" WHERE procede='".$ls_procede."' ".
				"   AND comprobante='".$ls_comprobante."' ".
				"   AND fecha='".$ld_fecha."' ".
				"   AND codban='".$this->as_codban."' ".
				"   AND ctaban='".$this->as_ctaban."' ".
				" 	AND procede_doc='".$as_procede_doc."' ".
				"   AND documento='".$as_documento."' ".
				"   AND spi_cuenta='".$as_cuenta."' ".
				"	AND operacion='".$as_operacion."' ";			
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_spi MÉTODO->uf_spi_select_movimiento ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ldec_monto=$row["monto"];
				$li_orden=$row["orden"];
				$lb_existe=true;
			}
		}	
		return $lb_existe;
	} // end function uf_spi_select_movimiento
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spi_existe_comprobante($ls_procede,$ls_comprobante,$ld_fecha,$as_codban,$as_ctaban,$lo_comp)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spi_existe_comprobante
		//		   Access: public 
		//       Argument: ls_procede // Procede del Movimiento
		//				   ls_comprobante // Número de Comprobante
		//				   ld_fecha // Fecha del comprobante
		//				   as_codban // Código de Banco
		//				   as_ctaban // Cuenta de Banco
		//				   lo_comp // 
		//	  Description: Este método obtiene un detalle de un compromiso
		//	      Returns: boolean 
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 01/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = false;
		$ls_cad="";
		$li_result=0;
		$ls_sql="SELECT * ".
				"  FROM spi_dt_cmp ".
				" WHERE procede='".$ls_procede."' ".
				"   AND comprobante='".$ls_comprobante."' ".
				"   AND fecha='".$ld_fecha."'".
				"   AND codban='".$as_codban."'".
				"   AND ctaban='".$as_ctaban."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_spi MÉTODO->uf_spi_existe_comprobante ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$lb_existe=false;			
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true;
				$lo_comp=$row;
			}
			else
			{
				$lb_existe=false;
				$row=array();
			}
		}				
		return $lb_existe;
	} // end function uf_spi_existe_comprobante
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spi_existe_movimiento($as_procede,$as_comprobante,$ad_fecha,$as_procede_doc,$as_documento,$as_cuenta,
									  $as_codban,$as_ctaban)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spi_existe_movimiento
		//		   Access: public 
		//       Argument: as_procede // Procede del Movimiento
		//				   as_comprobante // Número de Comprobante
		//				   ad_fecha // Fecha del comprobante
		//				   as_procede_doc // Procede del Movimiento
		//				   as_documento // Número del Documento
		//				   as_cuenta // Cuenta de Presupuesto
		//				   as_codban // Código de Banco
		//				   as_ctaban // Cuenta de Banco
		//	  Description: Este método verifica si la cuenta posee movimientos asociados
		//	      Returns: boolean 
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 01/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		$ls_sql="SELECT count(*) as nVeces ".
				"  FROM spi_dt_cmp ".
				" WHERE procede='".$as_procede."' ".
				"   AND comprobante='".$as_comprobante."' ".
				"   AND fecha='".$ad_fecha."' ".
				"   AND codban='".$as_codban."' ".
				"   AND ctaban='".$as_ctaban."' ".
				"   AND procede_doc='".$as_procede_doc."' ".
				"   AND documento='".$as_documento."' ".
				"   AND spi_cuenta='".$as_cuenta."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_spi MÉTODO->uf_spi_existe_movimiento ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else
		{	
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				if($row["nVeces"]>0)
				{
					$lb_existe=true;
				}
			}		
		}		
		return $lb_existe;
	} // end function uf_spi_existe_movimiento
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spi_comprobante_actualizar($ldec_monto_anterior,$ldec_monto_actual,$ls_tipocomp)	
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spi_comprobante_actualizar
		//		   Access: public 
		//       Argument: ldec_monto_anterior // Monto Anterior del Movimiento
		//				   ldec_monto_actual // Monto Actual del Movimiento
		//				   ls_tipocomp // Tipo de Comprobante
		//	  Description: Este método actualiza  el comprobante SIGESP_cmp
		//	      Returns: boolean 
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 01/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
	    if($ls_tipocomp=="C")
		{
			$li_tipocomp=1;
		}
        if($ls_tipocomp=="P")
		{
			$li_tipocomp=2;
		}							
		if($this->uf_spi_comprobante_select())
		{
			$lb_valido=$this->uf_spi_comprobante_update($ldec_monto_anterior,$ldec_monto_actual);
		}
		else
		{
			$lb_valido=$this->uf_spi_comprobante_insert($ldec_monto_actual, $li_tipocomp);
		}
		return $lb_valido;
	} // end function uf_spi_comprobante_actualizar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spi_comprobante_delete()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spi_comprobante_delete
		//		   Access: public 
		//       Argument: 
		//	  Description: Este método elimina un comprobante
		//	      Returns: boolean 
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 01/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_procede=$this->sig_int->is_procedencia;
		$ls_comprobante=$this->sig_int->is_comprobante;
		$ld_fecha=$this->sig_int->id_fecha;		
		$ls_codban=$this->sig_int->as_codban;		
		$ls_ctaban=$this->sig_int->as_ctaban;		
		$ls_sql="DELETE FROM sigesp_cmp ".
				" WHERE procede='".$ls_procede."' ".
				"   AND comprobante='".$ls_comprobante."' ".
				"   AND fecha='".$ld_fecha."' ".
				"   AND codban='".$ls_codban."' ".
				"   AND ctaban='".$ls_ctaban."' ";
		$li_result=$this->io_sql->execute($ls_sql);
		if($li_result===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_spi MÉTODO->uf_spi_comprobante_delete ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else
		{
			return true;
		}
	}// end function uf_spi_comprobante_delete
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spi_comprobante_insert($ldec_monto,$ai_tipocomp)	
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spi_comprobante_insert
		//		   Access: public 
		//       Argument: ldec_monto // Monto del Comprobante
		//				   ai_tipocomp // Tipo de Comprobante
		//	  Description: Este método inserta en el comprobante de  ingreso
		//	      Returns: boolean 
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 01/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_procede=$this->sig_int->is_procedencia;
		$ls_comprobante=$this->sig_int->is_comprobante;
		$ld_fecha=$this->sig_int->id_fecha;
		$ls_descripcion=$this->sig_int->is_descripcion;
		$ls_tipo=$this->sig_int->is_tipo;
		$ls_cod_pro=$this->sig_int->is_cod_prov;
		$ls_ced_bene=$this->sig_int->is_ced_ben;
		$ls_codban=$this->sig_int->as_codban;		
		$ls_ctaban=$this->sig_int->as_ctaban;		
		$ls_sql="INSERT INTO sigesp_cmp(procede, comprobante, fecha, descripcion, total, tipo_destino, cod_pro, ced_bene,tipo_comp, ".
				"codban, ctaban)  VALUES ('".$ls_procede."','".$ls_comprobante."','".$ld_fecha."','".$ls_descripcion."',".
				" ".$ldec_monto.", '".$ls_tipo."','".$ls_cod_pro."','".$ls_ced_bene."', '".$ai_tipocomp."', '".$ls_codban."', ".
				"'".$ls_ctaban."' )";
		$li_result=$this->io_sql->execute($ls_sql);
		if($li_result===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_spi MÉTODO->uf_spi_comprobante_insert ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else
		{
			return true;
		}		
	}// end function uf_spi_comprobante_insert
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spi_comprobante_select()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spi_comprobante_select
		//		   Access: public 
		//       Argument: 
		//	  Description: Este método verifica si existe el comprobante SIGESP_cmp
		//	      Returns: boolean 
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 01/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		$ld_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
		$ls_sql="SELECT count(*) as nVeces ".
				"  FROM sigesp_cmp ".
				" WHERE procede='".$this->is_procedencia."' ".
				"   AND comprobante='".$this->is_comprobante."' ".
				"   AND fecha='".$ld_fecha."'".
				"   AND codban='".$this->as_codban."'".
				"   AND ctaban='".$this->as_ctaban."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_spi MÉTODO->uf_spi_comprobante_select ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return  false;			
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true;
			}
		}
		return $lb_existe;
	}// end function uf_spi_comprobante_select
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spi_comprobante_update($ldec_monto_anterior,$ldec_monto_actual)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spi_comprobante_update
		//		   Access: public 
		//       Argument: ldec_monto_anterior // Monto Anterior
		//				   ldec_monto_actual // Monto Actual
		//	  Description: Este método actualiza si existe el comprobante SIGESP_cmp
		//	      Returns: boolean 
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 01/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_procede=$this->is_procedencia;
		$ls_comprobante=$this->is_comprobante;
		$ld_fecha=$this->id_fecha;		
		$ls_codemp=$this->is_codemp;	
		$ldec_total=-$ldec_monto_anterior+$ldec_monto_actual;
		$lb_valido=true;
		$ls_sql="UPDATE sigesp_cmp ".
				"   SET total = total + ".$ldec_total." ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND procede='".$ls_procede."' ".
				"   AND comprobante='".$ls_comprobante."' ".
				"   AND fecha='".$ld_fecha."'".
				"   AND codban='".$this->as_codban."'".
				"   AND ctaban='".$this->as_ctaban."'";
		$li_result=$this->io_sql->execute($ls_sql);
		if($li_result===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_spi MÉTODO->uf_spi_comprobante_update ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_spi_comprobante_update
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_opera_mensaje_codigo($as_mensaje,$lb_valido)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_opera_mensaje_codigo
		//		   Access: public 
		//       Argument: as_mensaje // Mensaje 
		//				   lb_valido // 
		//	  Description: Este método mediante la cadena mensaje retorna el codigo operacion asociado
		//	      Returns: retorna el codigo de operacion del gasto definida en las tablas spg_operaciones
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 01/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$li_previsto=0;
		$li_aumento=0;
		$li_disminucion=0;
		$li_devengado=0;
		$li_cobrado =0;
		$li_cobrado_ant=0;
		$ls_codigo="";
		$ls_mensaje=strtoupper($as_mensaje); // devuelve cadena en MAYUSCULAS
		$li_pos_i=strpos($as_mensaje,"I"); 
		if(!($li_pos_i===false))
		{
			$li_previsto=1;
		}
		$li_pos_a=strpos($as_mensaje,"A"); 
		if(!($li_pos_a===false))
		{
			$li_aumento=1;
		}
		$li_pos_d=strpos($as_mensaje,"D"); 
		if(!($li_pos_d===false))
		{
			$li_disminucion=1;
		}
		$li_pos_e=strpos($as_mensaje,"E"); 
		if(!($li_pos_e===false))
		{
			$li_devengado=1;
		}
		$li_pos_c=strpos($as_mensaje,"C"); 
		if(!($li_pos_c===false))
		{
			$li_cobrado=1;
		}
		$li_pos_n=strpos($as_mensaje,"N"); 
		if(!($li_pos_n===false))
		{
			$li_cobrado_ant=1;
		}
		$ls_sql="SELECT operacion ".
				"  FROM spi_operaciones ".
				" WHERE previsto=".$li_previsto." ".
				"   AND aumento=".$li_aumento." ".
				"   AND disminucion=".$li_disminucion." ".
				"   AND devengado=".$li_devengado." ".
				"   AND cobrado=".$li_cobrado." ".
				"  AND cobrado_ant=".$li_cobrado_ant." ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{	
			$this->is_msg_error="CLASE->sigesp_int_spi MÉTODO->uf_opera_mensaje_codigo ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codigo=$row["operacion"];
				$lb_valido=true;
			}			
		}
		if(!$lb_valido)
		{
			$this->io_msg->message("No existe el codigo de operacion para el mensaje: ".$ls_mensaje);
			return "";
		}
		
		return $ls_codigo;
	}// end function uf_opera_mensaje_codigo
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_operacion_codigo_mensaje($as_operacion)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_operacion_codigo_mensaje
		//		   Access: public 
		//       Argument: as_operacion // Operacion
		//	  Description: Este método recibe un codigo de operacion y genra mediante el los codigos de mensajes
		//                 interno de operaciones de cuentas 
		//	      Returns: retorna un mensaje interno para operaciones 
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 01/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	    $ls_mensaje="";
		$ls_sql="SELECT previsto,aumento,disminucion,devengado,cobrado,cobrado_ant ".
				"  FROM spi_operaciones ".
				" WHERE operacion = '".$as_operacion."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_spi MÉTODO->uf_operacion_codigo_mensaje ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return $ls_mensaje;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_previsto = $row["previsto"];
				$li_aumento = $row["aumento"];
				$li_disminucion = $row["disminucion"];
				$li_devengado = $row["devengado"];
				$li_cobrado = $row["cobrado"];
				$li_cobrado_ant = $row["cobrado_ant"];
				if($li_previsto==1)
				{
					$ls_mensaje=$ls_mensaje."I";
				}
				if($li_aumento==1)
				{
					$ls_mensaje=$ls_mensaje."A";
				}
				if($li_disminucion==1)
				{
					$ls_mensaje=$ls_mensaje."D";
				}
				if($li_devengado==1)
				{
					$ls_mensaje=$ls_mensaje."E";
				}
				if($li_cobrado==1)
				{
					$ls_mensaje=$ls_mensaje."C";
				}
				if($li_cobrado_ant==1)
				{
					$ls_mensaje=$ls_mensaje."N";
				}
				$ls_mensaje=trim($ls_mensaje);
			}
			else
			{
				$this->is_msg_error =  "No esta definido el código de operacion ".$as_operacion;
				$this->io_msg->message($this->is_msg_error);			   		  		  			  
			}
			$this->io_sql->free_result($rs_data);
	    }
		return $ls_mensaje;
    }// end function uf_operacion_codigo_mensaje
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spi_del_movimiento($ls_mensaje,$ls_cuenta,$ls_procede_doc,$ls_documento,$ls_descripcion,$ldec_monto_ant,
								   $ldec_monto_act,$ls_sccuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_operacion_codigo_mensaje
		//		   Access: public 
		//       Argument: ls_mensaje // Mensaje del Documento
		//       		   ls_cuenta // Cuenta del Movimiento
		//       		   ls_procede_doc // Procede del Movimiento
		//       		   ls_documento // Número del Documento
		//       		   ls_descripcion // Descripción del Movimiento
		//       		   ldec_monto_ant // Monto Anterior del Movimiento
		//       		   ldec_monto_act // Monto Actual del Movimiento
		//       		   ls_sccuenta // Cuenta Contable
		//	  Description: Este método elimina un movimiento
		//	      Returns: retorna un mensaje interno para operaciones 
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 01/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		// busco el codigo de operacion correspondiente al mensaje
		$ls_codope=$this->uf_opera_mensaje_codigo($ls_mensaje,&$lb_valido);
		//Primero Verifico que exista
		if(!$this->uf_spi_select_movimiento($ls_cuenta, $ls_procede_doc, $ls_documento, $ls_codope, &$lo_monto_movimiento,&$lo_orden))
		{
			$this->io_msg->message("El Movimiento no existe");
			return false;
		}
		$lb_valido=$this->uf_spi_delete_movimiento($ls_cuenta,$ls_procede_doc,$ls_documento,$ls_codope);
		//-----------------
		//- Actualizo saldos --
		//----------------------
		if($lb_valido)
		{// note que paso el monto que devuelve mov_select
			$lb_valido=$this->uf_spi_saldo_ingreso($_SESSION["la_empresa"]["codemp"],$ls_cuenta,$ls_mensaje,$ldec_monto,0);
		}
		//*--------------------------
		//*-- Check for header --
		//*--------------------------
		if($lb_valido)
		{
		// note que paso el monto que devuelve mov_select
			$lb_valido = $this->uf_spi_comprobante_actualizar($ldec_monto,0);
		}
		//--------------------------
		//- Integracion con contabilidad --
		//--------------------------
		$li_devengado=0;
		$li_pos_e=strpos($ls_mensaje,"E"); 
		if(!($li_pos_e===false))
		{
			$li_devengado=1;
		}
		if(($lb_valido)&&($this->ib_autoconta)&&($li_devengado==1)) 
		{
			//-- valido que la cuenta exista
			$lb_valido = $this->int_scg->uf_scg_validar_cuenta($ls_sccuenta, &$ls_status);
			if($lb_valido)
			{
				$this->io_msg->message("La cuenta contable ".$ls_sccuenta." no existe");
			}		
			//- valido que sea una cuenta de movimiento
			if($lb_valido)
			{	
				if($ls_status!="C")
				{
					$this->io_msg->message("La cuenta contable ".$ls_sccuenta." no es de movimiento");
					$lb_valido=false;
				}
			}			
			if($lb_valido)
			{
				$lb_valido=$this->int_scg->uf_scg_del_movimiento($this->io_function->iif_string("$ldec_monto>0","H","D"),$ls_sccuenta,$ls_procede_doc, $ls_documento,$ls_descripcion, abs($ldec_monto));
			}
		}		
		return $lb_valido;
	}// end function uf_spi_del_movimiento
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_int_spi_insert_movimiento($as_codemp,$as_procedencia,$as_comprobante,$as_fecha,$as_tipo,$as_fuente,$as_cod_prov,
	                                      $as_ced_ben,$as_cuenta,$as_procede_doc,$as_documento,$as_descripcion,$as_mensaje,
										  $adec_monto,$as_sc_cuenta,$ab_spg_enlace_contable,$as_codban,$as_ctaban)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_int_spg_insert_movimiento
		//		   Access: public 
		//       Argument: as_codemp // Código de Empresa
		//				   as_procedencia // Procedencia del Documento
		//				   as_comprobante // Número de Comprobante
		//				   as_fecha  // Fecha del Comprobante
		//				   as_tipo // Tipo
		//       		   as_fuente // Tipo Destino
		//       		   as_cod_prov // Código del Proveedor
		//       		   as_ced_ben // Cédula del Beneficiario
		//       		   as_cuenta // cuenta
		//				   as_procede_doc // Procede del Documento
		//				   as_documento // Número del Documento
		//				   as_descripcion // Descripción del Movimiento
		//				   as_mensaje // Mensaje del Movimiento
		//				   adec_monto // Monto del Movimiento
		//				   as_sc_cuenta // Cuenta Contable del Movimiento
		//				   ab_spg_enlace_contable // Enlace Contable
		//       		   as_codban // Código de Banco
		//       		   as_ctaban // Cuenta de Banco
		//	  Description: Método que inserta un movimiento de ingreso por medio de la integracion en lote
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 01/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_denproc="";
		$ls_status="";
		$ls_denominacion="";
		$ls_SC_Cuenta="";
		$this->is_codemp=$as_codemp;
		$this->is_procedencia=$as_procedencia;
		$this->is_comprobante=$as_comprobante;
		$this->is_descripcion=$as_descripcion;
		$this->id_fecha=$as_fecha;
		$this->is_tipo=$as_tipo;
		$this->is_fuente=$as_fuente;
		$this->is_cod_prov=$as_cod_prov;
		$this->is_ced_ben=$as_ced_ben;
		$this->ib_spg_enlace_contable=$ab_spg_enlace_contable;
		$this->as_codban=$as_codban;
		$this->as_ctaban=$as_ctaban;
		$ls_comprobante=$this->uf_fill_comprobante( $this->is_comprobante );
		$ls_operacion=$this->uf_opera_mensaje_codigo($as_mensaje,&$lb_valido);
		if(empty($ls_operacion))
		{ 
			return false;
		}
		if(!$this->uf_valida_procedencia($this->is_procedencia,$ls_denproc))
		{
			return false;
		}
		if(!$this->io_fecha->uf_valida_fecha_mes($this->is_codemp,$this->id_fecha))
		{
			$this->is_msg_error="Fecha Invalida.";
			$this->io_msg->message($this->is_msg_error);			   		  		  
			return false;
		}
		if($this->uf_spi_select_movimiento($as_cuenta, $as_procede_doc, $as_documento, $ls_operacion, &$lo_monto_movimiento,
										   &$lo_orden))  
		{
			$this->is_msg_error="El movimiento contable ya existe.";
			$this->io_msg->message($this->is_msg_error);			   		  		  		  
			return false; 	
		}
		$lb_valido = $this->uf_spi_saldo_ingreso($as_codemp,$as_cuenta,$as_mensaje,0,$adec_monto);
		if ($lb_valido)
		{
			$lb_valido = $this->uf_spi_comprobante_actualizar(0,$adec_monto,"C");
			if($lb_valido)
			{
				$lb_valido =$this->uf_spi_insert_movimiento_ingreso($as_cuenta,$as_procede_doc,$as_documento,$ls_operacion,$as_descripcion,$adec_monto);
				if(($lb_valido)) 
				{
					$as_mensaje=strtoupper($as_mensaje); // devuelve cadena en MAYUSCULAS
					$li_pos_i=strpos($as_mensaje,"E"); 
					if(!($li_pos_i===false)&&($this->ib_spg_enlace_contable))
					{			      
						if ($this->ib_AutoConta)
						{
							$lb_valido=$this->uf_spi_integracion_scg($as_codemp,$as_sc_cuenta,$as_procede_doc,$as_documento,$as_descripcion,$adec_monto);
						}
					} 
				}
			}
		}
		return $lb_valido;
	} // end function uf_int_spi_insert_movimiento
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spi_integracion_scg($as_codemp,$as_scgcuenta,$as_procede_doc,$as_documento,$as_descripcion,$adec_monto_actual)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_int_spg_insert_movimiento
		//		   Access: public 
		//       Argument: as_codemp // Código de Empresa
		//				   as_scgcuenta // Cuenta Contable del Movimiento
		//				   as_procede_doc // Procede del Documento
		//				   as_documento // Número del Documento
		//				   as_descripcion // Descripción del Movimiento
		//				   adec_monto_actual // Monto del Movimiento
		//	  Description: Este método generar un asiento contable automáticamente cuando se genera un asiento en presupuesto 
		//				   de ingreso con operaciones de devengado de un documento. 
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 01/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_debhab="";
		$ls_status="";
		$ls_denominacion="";
		$ls_mensaje_error="";
		$ldec_monto=0;
		$li_orden=0;
		if($adec_monto_actual>0)
		{
			$ls_debhab = "H";
		}
		else
		{
			$ls_debhab = "D";
		}
		if(!$this->io_int_scg->uf_scg_select_cuenta($as_codemp, $as_scgcuenta, &$ls_status, $ls_denominacion))
		{
			$this->io_msg->message("La cuenta contable [". trim($as_scgcuenta) ."] no existe.");
			return false;
		} 
		if($ls_status!="C")
		{ 
			$this->io_msg->message("La cuenta contable [". trim($as_scgcuenta) ."] no es de movimiento.");
			return false;
		} 
		$this->io_int_scg->is_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
		$this->io_int_scg->is_codemp=$as_codemp;
		$this->io_int_scg->is_procedencia=$this->is_procedencia;
		$this->io_int_scg->is_comprobante=$this->is_comprobante;
		$this->io_int_scg->as_codban=$this->as_codban;
		$this->io_int_scg->as_ctaban=$this->as_ctaban;
		if($this->io_int_scg->uf_scg_select_movimiento($as_scgcuenta, $as_procede_doc, $as_documento, $ls_debhab, $ldec_monto, $li_orden))
		{
			$ldec_monto = $ldec_monto + $adec_monto_actual;
			$lb_valido = $this->io_int_scg->uf_scg_update_movimiento($as_codemp, $as_scgcuenta, $as_procede_doc, $as_documento, $as_documento, $as_descripcion, $as_descripcion, $ls_debhab, $ls_debhab, $adec_monto_actual, $ldec_monto);
		}					   
		else
		{
			//$lb_valido = $this->io_int_scg->uf_scg_registro_movimiento_int($as_codemp, $as_scgcuenta, $as_procede_doc, $as_documento, $ls_debhab, $as_descripcion, 0, $adec_monto_actual);
			$lb_valido = $this->io_int_scg->uf_scg_procesar_insert_movimiento($as_codemp,$this->is_procedencia,
																			  $this->is_comprobante,$this->id_fecha,
																			  $this->is_tipo,$this->is_cod_prov,
																			  $this->is_ced_ben,$as_scgcuenta,$as_procede_doc,
																			  $as_documento,$ls_debhab,$as_descripcion,0,
																			  $adec_monto_actual,$this->as_codban,
																			  $this->as_ctaban);
		}																	 
		return $lb_valido;
	} // end function uf_spi_integracion_scg
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spi_valida_cuenta($ls_cuenta,$ls_status,$ls_denominacion,$ls_sccuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spi_valida_cuenta
		//		   Access: public 
		//       Argument: ls_cuenta // cuenta Presupuestaria
		//				   ls_status // Estatus de la cuenta
		//				   ls_denominacion // denominación de la cuenta
		//				   ls_sccuenta // Cuenta contable
		//	  Description: función que valida que la cuenta exista
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 01/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_cuenta = trim($ls_cuenta);
		$data=$_SESSION["la_empresa"];
		$ls_codemp=$data["codemp"];
		$lb_existe=false;
		$ls_sql="SELECT spi_cuenta,status,denominacion,sc_cuenta ".
				"  FROM spi_cuentas ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND spi_cuenta='".$ls_cuenta."'";		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_spi MÉTODO->uf_spi_valida_cuenta ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else
		{
		   if($row=$this->io_sql->fetch_row($rs_data))
		   {
			   $ls_status=$row["status"];
			   $ls_denominacion=$row["denominacion"];
			   $ls_sccuenta=$row["sc_cuenta"];
			   $lb_existe=true;	
		   } 
		   else
		   {
			   $lb_existe=false;	
		   }  
		}
		return $lb_existe;
	} // end function uf_spi_valida_cuenta
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_int_spi_delete_movimiento($as_codemp,$as_procedencia,$as_comprobante,$as_fecha,$as_tipo,$as_fuente,$as_cod_pro,$as_ced_bene,
	                                      $as_cuenta,$as_procede_doc,$as_documento,$as_descripcion,$as_mensaje,$as_tipo_comp,
										  $adec_monto_anterior,$adec_monto_actual,$as_sc_cuenta,$as_codban,$as_ctaban)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_int_spi_delete_movimiento
		//		   Access: public 
		//       Argument: as_codemp // Código de Empresa
		//				   as_procedencia // Procedencia del Documento
		//				   as_comprobante // Número de Comprobante
		//				   as_fecha  // Fecha del Comprobante
		//				   as_tipo // Tipo
		//       		   as_fuente // Tipo Destino
		//       		   as_cod_prov // Código del Proveedor
		//       		   as_ced_ben // Cédula del Beneficiario
		//       		   as_cuenta // cuenta
		//				   as_procede_doc // Procede del Documento
		//				   as_documento // Número del Documento
		//				   as_descripcion // Descripción del Movimiento
		//				   as_mensaje // Mensaje del Movimiento
		//				   as_tipo_comp // Tipo de Comprobante
		//				   adec_monto_anterior // Monto anterior del Movimiento
		//				   adec_monto_actual // Monto actual del Movimiento
		//				   as_sc_cuenta // Cuenta Contable del Movimiento
		//				   ab_spg_enlace_contable // Enlace Contable
		//       		   as_codban // Código de Banco
		//       		   as_ctaban // Cuenta de Banco
		//	  Description: Método que elimina un movimiento de ingreso por medio de la integracion en lote
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 01/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$this->is_codemp=$as_codemp;
		$this->is_procedencia=$as_procedencia;
		$this->is_comprobante=$as_comprobante;
		$this->id_fecha=$as_fecha;
		$this->is_tipo=$as_tipo;
		$this->is_fuente=$as_fuente;
		$this->is_cod_prov=$as_cod_pro;
		$this->is_ced_ben=$as_ced_bene;
		$this->as_codban=$as_codban;
		$this->as_ctaban=$as_ctaban;
		$ls_operacion=$this->uf_opera_mensaje_codigo($as_mensaje,&$lb_valido);
		if(empty($ls_operacion))
		{
			return false;
		}
		if(!$this->uf_spi_select_movimiento($as_cuenta, $as_procede_doc, $as_documento, $ls_operacion, &$lo_monto_movimiento, $lo_orden))  
		{
			$this->io_msg->message("El movimiento contable no existe.");			   		  
			return false; 	
		}
		$lb_valido = $this->uf_valida_integridad_referencial_comprobante($as_cuenta,$as_procede_doc,$as_documento,$ls_operacion,$as_tipo,$as_cod_pro,$as_ced_bene,$adec_monto_anterior);
		if ($lb_valido)   
		{
			$lb_valido = $this->uf_spi_delete_movimiento($as_cuenta, $as_procede_doc, $as_documento, $ls_operacion);
			if ($lb_valido)
			{
				$lb_valido = $this->uf_spi_saldo_ingreso($as_codemp,$as_cuenta,$as_mensaje,$lo_monto_movimiento,0);
				if ($lb_valido)
				{
					$lb_valido = $this->uf_spi_comprobante_actualizar($lo_monto_movimiento,0,"C");
					if(($lb_valido)&&($this->ib_AutoConta))
					{
						$as_mensaje=strtoupper($as_mensaje); // devuelve cadena en MAYUSCULAS
						$li_pos_i=strpos($as_mensaje,"E"); 
						if (!($li_pos_i===false))
						{
							if (!$this->io_int_scg->uf_scg_valida_cuenta($as_codemp,$as_sc_cuenta))
							{
								$this->io_msg->message("La cuenta contable ".$as_sc_cuenta." no existe");			   		  
								$lb_valido=false;
							}
							else
							{
								if ($lo_monto_movimiento>0) 
								{
									$ls_debhab='H';
								}
								else 
								{
									$ls_debhab='D';
								}
								$lb_valido=$this->io_int_scg->uf_scg_delete_movimiento($as_codemp,$as_procedencia,$as_comprobante,
																					   $as_fecha,$as_sc_cuenta,$as_procede_doc,
																					   $as_documento,$ls_debhab,$as_codban,$as_ctaban);
							}
						}  
					}
				}  
			}
		}
		return $lb_valido;
    } // end function uf_int_spi_delete_movimiento
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_valida_integridad_referencial_comprobante($as_cuenta,$as_procede_doc,$as_documento,$as_operacion,
	                                                      $as_tipo_destino,$as_cod_pro,$as_ced_bene,$adec_monto_anterior)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_valida_integridad_referencial_comprobante
		//		   Access: public 
		//       Argument: as_cuenta // cuenta
		//				   as_procede_doc // Procede del Documento
		//				   as_documento // Número del Documento
		//				   as_operacion // Operación del documento
		//       		   as_tipo_destino // Tipo Destino
		//       		   as_cod_prov // Código del Proveedor
		//       		   as_ced_ben // Cédula del Beneficiario
		//				   adec_monto_anterior // Monto anterior del Movimiento
		//	  Description: Método que verifica si el registro esta asociado a otra tabla 
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 01/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe_referencia=false;
		$ls_codemp=$this->is_codemp;
		$ls_procedencia=$this->is_procedencia;
		$ls_comprobante=$this->is_comprobante;
		$as_fecha=$this->id_fecha;
		$as_codban=$this->as_codban;
		$as_ctaban=$this->as_ctaban;
		if($adec_monto_anterior>0)
		{
			$lb_valido = $this->uf_valida_integridad_comprobante_ajuste($ls_codemp,$ls_comprobante,$ls_procedencia,$as_tipo_destino,
																		$as_cod_pro,$as_ced_bene,$as_cuenta,$as_operacion,
																		$lb_existe_referencia,$as_codban,$as_ctaban);
			if ($lb_valido)																	   
			{
				if ($lb_existe_referencia)
				{
					$this->io_msg->message("El comprobante es referenciado en otro");			   
					return false; 	
				}
				$lb_valido = $this->uf_valida_integridad_comprobante_otros($ls_codemp,$ls_comprobante,$ls_procedencia,$as_tipo_destino,
																		   $as_cod_pro,$as_ced_bene,$as_cuenta,$as_operacion,
																		   $lb_existe_referencia,$as_codban,$as_ctaban);
				if ($lb_valido)																	   
				{
					if ($lb_existe_referencia)
					{
						$this->io_msg->message("El comprobante es referenciado en otro");			   
						return false; 	
					}
				} 
			}
		}
		return $lb_valido;
	} // end function uf_valida_integridad_referencial_comprobante
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_valida_integridad_comprobante_ajuste($as_codemp,$as_comprobante,$as_procedencia,$as_tipo_destino,$as_cod_pro,$as_ced_bene,
	                                                  $as_cuenta,$as_operacion,&$ab_existe_referencia,$as_codban,$as_ctaban)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_valida_integridad_comprobante_ajuste
		//		   Access: public 
		//       Argument: as_codemp // Código de Empresa
		//				   as_comprobante // Número de Comprobante
		//				   as_procedencia // Procedencia del Documento
		//       		   as_tipo_destino // Tipo Destino
		//       		   as_cod_prov // Código del Proveedor
		//       		   as_ced_ben // Cédula del Beneficiario
		//       		   as_cuenta // cuenta
		//				   as_operacion  // Operación del Comprobante
		//				   ab_existe_referencia // Si existe referencia 
		//       		   as_codban // Código de Banco
		//       		   as_ctaban // Cuenta de Banco
		//	  Description: Método que valida si el movimiento esta asociado con otro.
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 01/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
	    $ls_sql="SELECT D.procede As procede,D.comprobante As comprobante,D.fecha as fecha ".
			    "  FROM spi_dt_cmp D,sigesp_cmp C".		
			    " WHERE C.codemp='".$as_codemp."'  ".
				"   AND procede_doc='".$as_procedencia."' ".
			    "   AND D.comprobante='".$as_comprobante."'  ".
				"   AND tipo_destino='".$as_tipo_destino."' ".
				"   AND D.procede_doc='".$as_procedencia."' ".
				"   AND D.spi_cuenta ='".$as_cuenta."' ".
				"   AND operacion='".$as_operacion."' ".
				"   AND monto<0 ".
				"   AND C.tipo_comp=1 ".
				"   AND C.cod_pro='".$as_cod_pro."' ".
				"   AND C.ced_bene='".$as_ced_bene."' ".
				"	AND C.codban='".$as_codban."' ".
				"   AND C.ctaban='".$as_ctaban."'".
				"   AND D.codemp=C.codemp ".
				"   AND D.procede=C.procede ".
				"   AND D.comprobante=C.comprobante ".
				"   AND D.fecha=C.fecha ".
				"	AND C.codban=D.codban ".
				"   AND C.ctaban=D.ctaban ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_spi MÉTODO->uf_valida_integridad_comprobante_ajuste ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
		    return false;
		}
		else
		{
		    while($row=$this->io_sql->fetch_row($rs_data) )
			{
			    $ab_existe_referencia=true;
				$this->is_msg_error=$this->is_msg_error."Comprobante: ".$row["procede"].$row["procede"].$row["fecha"];
	            $this->io_msg->message($this->is_msg_error);			   		  		  				
			}				
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_valida_integridad_comprobante_ajuste
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_valida_integridad_comprobante_otros($as_codemp,$as_comprobante,$as_procedencia,$as_tipo_destino,$as_cod_pro,$as_ced_bene,
	                                                 $as_cuenta,$as_operacion,&$ab_existe_referencia,$as_codban,$as_ctaban)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_valida_integridad_comprobante_ajuste
		//		   Access: public 
		//       Argument: as_codemp // Código de Empresa
		//				   as_comprobante // Número de Comprobante
		//				   as_procedencia // Procedencia del Documento
		//       		   as_tipo_destino // Tipo Destino
		//       		   as_cod_prov // Código del Proveedor
		//       		   as_ced_ben // Cédula del Beneficiario
		//       		   as_cuenta // cuenta
		//				   as_operacion  // Operación del Comprobante
		//				   ab_existe_referencia // Si existe referencia 
		//       		   as_codban // Código de Banco
		//       		   as_ctaban // Cuenta de Banco
		//	  Description: Método que valida si el movimiento esta asociado con otro.
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 01/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_mensaje=$this->uf_operacion_codigo_mensaje($as_operacion);
		$ls_mensaje=strtoupper($ls_mensaje); // devuelve cadena en MAYUSCULAS
		// caso exepcional
 	    $li_pos_e=strpos($ls_mensaje,"E");
		$li_pos_c=strpos($ls_mensaje,"C");
        if (!($li_pos_e===false) and !($li_pos_c===false))
		{
			return true;
		}
		$ls_cadena_incluir="";
	    $ls_cadena_excluir="";
		$li_pos_e=strpos($ls_mensaje,"E");
	    if(!($li_pos_e===false))
		{
			$ls_cadena_excluir=$ls_cadena_excluir."O.devengado=0 AND ";
		}
		$li_pos_c=strpos($ls_mensaje,"C");
	    if(!($li_pos_c===false))
		{
			$ls_cadena_excluir=$ls_cadena_excluir."O.cobrado=0 AND ";
		}
 		else
		{
			$ls_cadena_incluir=$ls_cadena_incluir."O.cobrado=1 OR ";
		}
        $ls_condicion="";         
        if(!empty($ls_cadena_excluir)) 
		{
		    $ls_cadena_excluir = "(".substr($ls_cadena_excluir,0,strlen($ls_cadena_excluir)- 4).")";
            $ls_condicion =$ls_condicion.$ls_cadena_excluir." AND ";
		}
        if(!empty($ls_cadena_incluir)) 
		{
		    $ls_cadena_incluir = "(".substr($ls_cadena_incluir,0,strlen($ls_cadena_incluir)- 3).")";
            $ls_condicion =$ls_condicion.$ls_cadena_incluir." AND ";
		}
	    $ls_sql="SELECT D.procede As procede,D.comprobante As comprobante,D.fecha as fecha ".
			    "  FROM spi_dt_cmp D,sigesp_cmp C,spi_operaciones O ".		
			    " WHERE C.codemp='".$as_codemp."'  ".
			    "   AND D.comprobante='".$as_comprobante."'  ".
				"   AND tipo_destino='".$as_tipo_destino."' ".
				"   AND C.cod_pro='".$as_cod_pro."' ".
				"   AND C.ced_bene='".$as_ced_bene."' ".
			    "   AND D.procede_doc='".$as_procedencia."' ".
				"   AND D.spi_cuenta ='".$as_cuenta."' ".
				"   AND D.operacion='".$as_operacion."' ".
				"   AND ".$ls_condicion." monto>0 ".
				"   AND C.codban='".$as_codban."' ".
				"   AND C.ctaban='".$as_ctaban."' ".
				"   AND C.tipo_comp=1 ".
				"   AND D.codemp=C.codemp ".
				"   AND D.procede=C.procede ".
				"   AND D.comprobante=C.comprobante ".
				"   AND D.fecha=C.fecha ".
				"	AND C.codban=D.codban ".
				"   AND C.ctaban=D.ctaban ".
				"   AND D.operacion=O.operacion";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_spi MÉTODO->uf_valida_integridad_comprobante_otros ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
		    return false;
		}
		else
		{
		    $this->is_msg_error ="";
		    while($row=$this->io_sql->fetch_row($rs_data) )
			{
			    $ab_existe_referencia=true;
				$this->io_msg->message("Comprobante: ".$row[" procede :"].$row[" Fecha :"].$row["fecha"]);
			}				
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_valida_integridad_comprobante_ajuste
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_calcular_disponible_por_rango($as_spi_cuenta,&$adec_monto,$as_operacion)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_calcular_disponible_por_rango
		//		   Access: public 
		//       Argument: as_spi_cuenta // cuenta Presupuestaria
		//       		   adec_monto // Monto del Movimiento
		//       		   as_operacion // Operación del movimiento
		//	  Description: Método que consulta y suma dependiando de la operacion
		//	      Returns: Retorna monto asignado
		//	   Creado Por: Ing. wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 01/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	    $lb_valido=true;
		$ldec_monto=0;
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ld_fecha=$this->io_function->uf_convertirdatetobd($_SESSION["fechacomprobante"]);
		$ld_inicio=$this->io_function->uf_convertirdatetobd($_SESSION["la_empresa"]["periodo"]);
		$ls_sql="SELECT COALESCE(SUM(monto),0) As monto ".
                "  FROM spi_dt_cmp, spi_operaciones  ".
                " WHERE codemp='".$ls_codemp."' ".
                "   AND spi_operaciones.".$as_operacion."=1 ".
				"   AND spi_dt_cmp.spi_cuenta = '".$as_spi_cuenta."' ".
				"   AND fecha >='".$ld_inicio."' AND fecha <='".$ld_fecha."' ".
				"   AND spi_dt_cmp.operacion=spi_operaciones.operacion ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   // error interno sql
            $this->io_msg->message("Error en uf_calcular_disponible_por_rango ".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
			   $ldec_monto = $row["monto"];
			}
			$this->io_sql->free_result($rs_data);
		}
		$adec_monto = $ldec_monto;
		return $lb_valido;
	} // fin function uf_calcular_disponible_por_rango
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	////////////////////////////////////////////////// MÉTODOS CON TRANSACCIONES /////////////////////////////////////////////////

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spi_update_movimiento($as_codemp, $as_procede, $as_comprobante, $as_fecha, $as_cod_prov, $as_ced_bene, $as_descripcion, 
	                                  $as_tipo, $ai_tipo_comp, $as_cuenta_i, $as_cuenta_f, $as_procede_doc_i, $as_procede_doc_f, 
									  $as_documento_i, $as_documento_f, $as_descripcion_i, $as_descripcion_f, $as_mensaje_i, 
									  $as_mensaje_f, $ad_monto_i, $ad_monto_f, $as_codban, $as_ctaban)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spi_update_movimiento
		//		   Access: public 
		//       Argument: 
		//	  Description: 
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 01/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$this->is_codemp=$as_codemp;
		$this->is_procedencia=$as_procede;
		$this->id_fecha=$as_fecha;
		$this->is_cod_prov=$as_cod_prov;
		$this->is_ced_ben=$as_ced_bene;
		$this->is_descripcion=$as_descripcion;
		$this->is_tipo=$as_tipo;
		$this->ii_tipo_comp=$ai_tipo_comp;
		$this->is_comprobante=$as_comprobante;
		$this->as_codban=$as_codban;
		$this->as_ctaban=$as_ctaban;
		$ls_operacion_i=$this->uf_opera_mensaje_codigo($as_mensaje_i,$lb_valido);
		$ls_operacion_f=$this->uf_opera_mensaje_codigo($as_mensaje_f,$lb_valido);
		if(!($this->uf_spi_select_cuenta($as_codemp, $as_cuenta_i, $ls_status_i, $ls_denominacion_i, $as_scgcuenta_i)))
		{   
			$this->io_msg->message("La cuenta [ ".$as_cuenta_i." ] no esta definida en el plan de cuentas de ingreso.");
			return false;
		}
		// valido el estatus de la cuenta
		if($ls_status_i!="C")
		{
			$this->io_msg->message("La cuenta [ ".$as_cuenta_i." ] no es de movimiento.");
			return false;	
		}
		// valido si existe la cuenta f.
		if(!($this->uf_spi_select_cuenta($as_codemp,$as_cuenta_f,$ls_status_f,$ls_denominacion_f,$as_scgcuenta_f)))
		{
			$this->io_msg->message("La cuenta [ ".$as_cuenta_f." ] no esta definida en el plan de cuentas de ingreso.");
			return false;	
		}
		// valido el estatud de la cuenta
		if($ls_status_f!="C")
		{
			$this->io_msg->message("La cuenta [ ".$as_cuenta_f." ] no es de movimiento.");
			return false;
		}
		// valido la fecha del movimiento con respecto al mes si esta abierto
		if (!($this->io_fecha->uf_valida_fecha_mes( $as_codemp, $as_fecha )))
		{
			$is_msg_error = $this->sig_int->$is_msg_error ;
			return false;
		}
		// verifico si existe el movimiento presupuestario de ingreso
		if(!($this->uf_spi_select_movimiento($as_cuenta_i, $as_procede_doc_i, $as_documento_i, $ls_operacion_i, &$ld_monto, &$ld_orden)))
		{
			$this->io_msg->message("El movimiento no existe.");
			return false ;  										  
		}
		if ($ld_monto <> $ad_monto_i)
		{
			$this->io_msg->message("El Monto anterior no coincide SPI.upd_movimiento");
			return false;
		}
		// inicio transacción de data
		$this->io_sql->begin_transaction();
		$lb_valido = $this->uf_spi_delete_movimiento( $as_cuenta_i, $as_procede_doc_i, $as_documento_i, $ls_operacion_i );
		if($lb_valido)
		{
			$lb_valido = $this->uf_spi_insert_movimiento_ingreso($as_cuenta_f, $as_procede_doc_f, $as_documento_f, $ls_operacion_f, $as_descripcion_f, $ad_monto_f);
			if($lb_valido)
			{
				$lb_valido = $this->uf_spi_saldo_ingreso($as_codemp,$as_cuenta_i,$as_mensaje_i,$ad_monto_i,0);
				if($lb_valido)
				{ 		
					$lb_valido = $this->uf_spi_saldo_ingreso($as_codemp,$as_cuenta_f,$as_mensaje_f,0,$ad_monto_f);
					if($lb_valido)
					{
						$lb_valido = $this->uf_spi_comprobante_actualizar($ad_monto_i, 0, $ai_tipo_comp);
						if ($lb_valido)
						{
							$lb_valido = $this->uf_spi_comprobante_actualizar(0, $ad_monto_f, $ai_tipo_comp);
						}
					}      
					//Integracion con contabilidad
					$as_mensaje_i=strtoupper($as_mensaje_i);
					$li_pos_c=strpos($as_mensaje_i,"C");
					if(($lb_valido)&&($this->ib_AutoConta)&&(!($li_pos_c===false)))
					{
						if(!($this->int_scg->uf_scg_select_cuenta($as_codemp,$as_cuenta_i, &$ls_status_i,&$ls_denominacion_i)))
						{
							$this->io_msg->message(" La cuenta contable " .trim($as_cuenta_i)." no existe  ");
							$lb_valido=false;
						}
						//valido que sea una cuenta de movimiento
						if(($lb_valido)&&($ls_status_i<>"C"))
						{
							$this->io_msg->message(" La cuenta contable " .trim($as_cuenta_i)." no es de movimiento ");
							$lb_valido=false;
						}
						if($lb_valido)
						{
							if($ld_monto_i>0)
							{
								$ls_debhab = "D";
							}
							else
							{
								$ls_debhab = "H";
							}
							$lb_valido =  $this->int_scg->uf_scg_procesar_delete_movimiento($as_codemp,$as_procede,$as_comprobante,
																							$as_fecha,$as_cuenta_i,$as_procede_doc_i,
																							$as_documento_i,$ls_debhab,$ad_monto_i,
																							$as_codban,$as_ctaban);
						}
					}
					$as_mensaje_f=strtoupper($as_mensaje_f);
					$li_pos_c=strpos($as_mensaje_f,"C");
					if(($lb_valido)&&($this->ib_AutoConta)&&(!($li_pos_c===false))) 
					{
						if (!$this->int_scg->uf_scg_select_cuenta($as_codemp,$as_cuenta_f,&$ls_status_f,&$ls_denominacion_i))
						{
							$this->io_msg->message(" La cuenta contable " .trim($as_cuenta_f)." no existe  ");
							$lb_valido=false;
						}
						//valido que sea una cuenta de movimiento
						if (($lb_valido) && ($ls_status_i<>"C"))
						{
							$this->io_msg->message(" La cuenta contable " .trim($as_cuenta_f)." no es de movimiento ");
							$lb_valido=false;
						}
						if ($lb_valido)
						{
							if($ld_monto_i>0)
							{
								$ls_debhab = "D";
							}
							else
							{
								$ls_debhab = "H";
							}
							$lb_valido= $this->int_scg->uf_scg_procesar_insert_movimiento($as_codemp,$as_procede, $as_comprobante, $as_fecha,
														$this->is_tipo,$this->is_cod_prov,$this->is_ced_ben,$as_cuenta_f,
														$as_procede_doc_f, $as_documento_f,$ls_debhab,$as_descripcion_f,
														$adec_monto_anterior, $ad_monto_f,$as_codban,$as_ctaban);						
						}
					} 
				}
			}  
	   	}   
		//Realizo la Transacción 
		if ($lb_valido)
		{
			$this->io_sql->commit(); 
			$lb_valido = true;   
		}
		else
		{
			$this->io_sql->rollback();
			$lb_valido = false;
		}
		return $lb_valido;
	}  // end function uf_spi_update_movimiento
	//-----------------------------------------------------------------------------------------------------------------------------------

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
?>