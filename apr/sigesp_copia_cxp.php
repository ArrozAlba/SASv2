<?php 
////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Class : sigesp_copia_cxp.php                                                 			  	  //    
// Description : Procesa la copia de datos del modulo de cuentas por pagar							  //
////////////////////////////////////////////////////////////////////////////////////////////////////////

class sigesp_copia_cxp {

	var $io_sql_origen;
	var $io_sql_destino;
	var $io_mensajes;
	var $io_funciones;
	var $io_validacion;
	var	$lo_archivo;
		
	//-----------------------------------------------------------------------------------------------------------------------------------	
	function sigesp_copia_cxp()
	{
		$ld_fecha=date("_d-m-Y");
		$ls_nombrearchivo="resultado/".$_SESSION["ls_data_des"]."_cxp_result_".$ld_fecha.".txt";
		$this->lo_archivo=@fopen("$ls_nombrearchivo","a+");
	
		$this->ls_database_source=$_SESSION["ls_database"];
		$this->ls_dabatase_target=$_SESSION["ls_data_des"];		
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/class_sql.php");
		require_once("class_folder/class_validacion.php");
		require_once("../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");
		$this->io_rcbsf	 = new sigesp_c_reconvertir_monedabsf(); 
		$this->io_mensajes=new class_mensajes();
		$this->io_validacion      = new class_validacion();
		$io_conect	= new sigesp_include();
		$io_conexion_origen = $io_conect->uf_conectar();
		$io_conexion_destino = $io_conect->uf_conectar($this->ls_dabatase_target);
		$this->io_sql_origen = new class_sql($io_conexion_origen);
		$this->io_sql_destino = new class_sql($io_conexion_destino);
	}// end function sigesp_copia_cxp
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function ue_copiar_cxp_basico()
	{
		$lb_valido=true;
		$this->io_sql_destino->begin_transaction();
		//------------------------------------ Insertar datos básicos de cxp -----------------------------------------
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_clasificador();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_documento();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_cargos();
		}		
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_deducciones();
		}			
		if($lb_valido)
		{	
			$this->io_mensajes->message("La data de Cuentas por Pagar se copió correctamente.");
			$ls_cadena="La data de Cuentas por Pagar se copió correctamente.\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}
		else
		{
			$this->io_mensajes->message("Ocurrió un error al copiar la data de Cuentas por Pagar. Verifique el archivo txt."); 
		}
		if ($lb_valido)
		{
			$this->io_validacion->uf_insert_sistema_apertura('CXP');
			$this->io_sql_destino->commit();
		}
		else
		{
			$this->io_sql_destino->rollback();	
		}
		return $lb_valido;
	}//	end function ue_copiar_cxp_basico
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_insert_clasificador()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_clasificador
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de cxp_clasificador_rd y los inserta en cxp_clasificador_rd
		//	   Creado Por: 
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido       = true;
		$li_total_select = 0;
		$li_total_insert = 0;
		$ls_sql          = "SELECT codcla, dencla ".
						   "  FROM cxp_clasificador_rd ";
		$io_recordset    = $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar los Clasificadores de la Recepción.\r\n".$this->io_sql_origen->message."\r\n";
			$ls_cadena=$ls_cadena.$ls_sql."\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}
		else
		{   
			$li_total_select = $this->io_sql_origen->num_rows($io_recordset);
			while($row=$this->io_sql_origen->fetch_row($io_recordset))
			{
				$ls_codcla=$this->io_validacion->uf_valida_texto($row["codcla"],0,2,"");
				$ls_dencla=$this->io_validacion->uf_valida_texto($row["dencla"],0,60,"");

				if($ls_codcla!="")
				{
					$ls_sql="INSERT INTO cxp_clasificador_rd (codcla, dencla) VALUES ('".$ls_codcla."','".$ls_dencla."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar los Clasificadores de la Recepción.\r\n".$this->io_sql_destino->message."\r\n";
						$ls_cadena=$ls_cadena.$ls_sql."\r\n";
						if ($this->lo_archivo)			
						{
							@fwrite($this->lo_archivo,$ls_cadena);
						}
					}
					else
					{ 
						$li_total_insert++;
					}
				}
				else
				{
					$ls_cadena="Hay data inconsistente en los Clasificadores de la Recepción.\r\n";
					$ls_cadena=$ls_cadena."Clasificador ".$ls_codcla." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  cxp_clasificador_rd Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino cxp_clasificador_rd Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_clasificador
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_insert_documento()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_clasificador
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de cxp_clasificador_rd y los inserta en cxp_clasificador_rd
		//	   Creado Por: 
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido       = true;
		$li_total_select = 0;
		$li_total_insert = 0;
		$ls_sql          = "SELECT codtipdoc, dentipdoc, estcon, estpre ".
						   "  FROM cxp_documento ";
		$io_recordset    = $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar los Tipos de Documentos de la Recepción.\r\n".$this->io_sql_origen->message."\r\n";
			$ls_cadena=$ls_cadena.$ls_sql."\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}
		else
		{   
			$li_total_select = $this->io_sql_origen->num_rows($io_recordset);
			while($row=$this->io_sql_origen->fetch_row($io_recordset))
			{
				$ls_codtipdoc=$this->io_validacion->uf_valida_texto($row["codtipdoc"],0,5,"");
				$ls_dentipdoc=$this->io_validacion->uf_valida_texto($row["dentipdoc"],0,80,"");
				$ls_estcon=$this->io_validacion->uf_valida_monto($row["estcon"],0);
				$ls_estpre=$this->io_validacion->uf_valida_monto($row["estpre"],0);

				if($ls_codtipdoc!="")
				{
					$ls_sql="INSERT INTO cxp_documento (codtipdoc, dentipdoc, estcon, estpre) VALUES ('".$ls_codtipdoc."','".$ls_dentipdoc."',".
							"".$ls_estcon.",".$ls_estpre.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar los Tipos de Documentos de la Recepción.\r\n".$this->io_sql_destino->message."\r\n";
						$ls_cadena=$ls_cadena.$ls_sql."\r\n";
						if ($this->lo_archivo)			
						{
							@fwrite($this->lo_archivo,$ls_cadena);
						}
					}
					else
					{ 
						$li_total_insert++;
					}
				}
				else
				{
					$ls_cadena="Hay data inconsistente en los Tipos de Documentos de la Recepción.\r\n";
					$ls_cadena=$ls_cadena."Clasificador ".$ls_codcla." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  cxp_documento Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino cxp_documento Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_clasificador
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_insert_cargos()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cargos
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sigesp_cargos y los inserta en sigesp_cargos
		//	   Creado Por: 
		// Fecha Creación: 20/11/2006 								Fecha Última Modificación : 	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido       = true;
		$li_total_select = 0;
		$li_total_insert = 0;
		$ls_sql          = "SELECT codemp, codcar, dencar, codestpro, spg_cuenta, porcar, estlibcom, formula ".
						   "  FROM sigesp_cargos ";
		$io_recordset    = $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar los Cargos.\r\n".$this->io_sql_origen->message."\r\n";
			$ls_cadena=$ls_cadena.$ls_sql."\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}
		else
		{   
			$li_total_select = $this->io_sql_origen->num_rows($io_recordset);
			while($row=$this->io_sql_origen->fetch_row($io_recordset))
			{
				$ls_codemp=$this->io_validacion->uf_valida_texto($row["codemp"],0,4,"");
				$ls_codcar=$this->io_validacion->uf_valida_texto($row["codcar"],0,5,"");
				$ls_dencar=$this->io_validacion->uf_valida_texto($row["dencar"],0,254,"");
				$ls_codestpro=$this->io_validacion->uf_valida_texto($row["codestpro"],0,33,"");
				$ls_spg_cuenta=$this->io_validacion->uf_valida_texto($row["spg_cuenta"],0,25,"");
				$li_porcar=$this->io_validacion->uf_valida_monto($row["porcar"],0);
				$li_estlibcom=$this->io_validacion->uf_valida_monto($row["estlibcom"],0);
				$ls_formula=$this->io_validacion->uf_valida_texto($row["formula"],0,254,"");
				if($ls_codcar!="")
				{
					$ls_sql="INSERT INTO sigesp_cargos (codemp, codcar, dencar, codestpro, spg_cuenta, porcar, estlibcom, formula) VALUES ".
							"('".$ls_codemp."','".$ls_codcar."','".$ls_dencar."','".$ls_codestpro."','".$ls_spg_cuenta."',".$li_porcar.",".
							"".$li_estlibcom.",'".$ls_formula."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar los Cargos.\r\n".$this->io_sql_destino->message."\r\n";
						$ls_cadena=$ls_cadena.$ls_sql."\r\n";
						if ($this->lo_archivo)			
						{
							@fwrite($this->lo_archivo,$ls_cadena);
						}
					}
					else
					{ 
						$li_total_insert++;
					}
				}
				else
				{
					$ls_cadena="Hay data inconsistente en los Cargos.\r\n";
					$ls_cadena=$ls_cadena."Cargo ".$ls_codcar." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sigesp_cargos Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sigesp_cargos Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_cargos
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_insert_deducciones()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_deducciones
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sigesp_cargos y los inserta en sigesp_cargos
		//	   Creado Por: 
		// Fecha Creación: 20/11/2006 								Fecha Última Modificación : 	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido       = true;
		$li_total_select = 0;
		$li_total_insert = 0;
		$ls_sql          = "SELECT codemp, codded, dended, sc_cuenta, porded, monded, islr, iva, estretmun, formula, otras ".
						   "  FROM sigesp_deducciones ";
		$io_recordset    = $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar las Deducciones.\r\n".$this->io_sql_origen->message."\r\n";
			$ls_cadena=$ls_cadena.$ls_sql."\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}
		else
		{   
			$li_total_select = $this->io_sql_origen->num_rows($io_recordset);
			while($row=$this->io_sql_origen->fetch_row($io_recordset))
			{
				$ls_codemp=$this->io_validacion->uf_valida_texto($row["codemp"],0,4,"");
				$ls_codded=$this->io_validacion->uf_valida_texto($row["codded"],0,5,"");
				$ls_dended=$this->io_validacion->uf_valida_texto($row["dended"],0,254,"");
				$ls_sc_cuenta=$this->io_validacion->uf_valida_texto($row["sc_cuenta"],0,25,"");
				$li_porded=$this->io_validacion->uf_valida_monto($row["porded"],0);
				$li_monded=$this->io_validacion->uf_valida_monto($row["monded"],0);
				$li_islr=$this->io_validacion->uf_valida_monto($row["islr"],0);
				$li_iva=$this->io_validacion->uf_valida_monto($row["iva"],0);
				$li_estretmun=$this->io_validacion->uf_valida_monto($row["estretmun"],0);
				$ls_formula=$this->io_validacion->uf_valida_monto($row["formula"],0,254,"");
				$li_otras=$this->io_validacion->uf_valida_monto($row["otras"],0);
				$li_mondedaux=$this->io_validacion->uf_valida_monto($row["monded"],0);
				$li_monded=$this->io_rcbsf->uf_convertir_monedabsf($li_monded,2,1,1000,1);
				if($ls_codded!="")
				{
					$ls_sql="INSERT INTO sigesp_deducciones (codemp, codded, dended, sc_cuenta, porded, monded, islr, iva, estretmun, formula, ".
							"otras, mondedaux) VALUES ('".$ls_codemp."','".$ls_codded."','".$ls_dended."','".$ls_sc_cuenta."',".$li_porded.",".
							"".$li_monded.",".$li_islr.",".$li_iva.",".$li_estretmun.",'".$ls_formula."',".$li_otras.",".$li_mondedaux.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar las Deducciones.\r\n".$this->io_sql_destino->message."\r\n";
						$ls_cadena=$ls_cadena.$ls_sql."\r\n";
						if ($this->lo_archivo)			
						{
							@fwrite($this->lo_archivo,$ls_cadena);
						}
					}
					else
					{ 
						$li_total_insert++;
					}
				}
				else
				{
					$ls_cadena="Hay data inconsistente en las Deducciones.\r\n";
					$ls_cadena=$ls_cadena."Deducción ".$ls_codded." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sigesp_deducciones Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sigesp_deducciones Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_deducciones
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function ue_limpiar_cxp_basico()
	{
		$lb_valido=true;
		$this->io_sql_destino->begin_transaction();
		//------------------------------------ Borrar tablas de cxp -----------------------------------------
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('cxp_clasificador_rd',"  ");			
		}
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('cxp_documento'," ");			
		}
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('sigesp_cargos'," ");			
		}
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('sigesp_deducciones'," ");
		}
			
		if($lb_valido)
		{	
			$this->io_mensajes->message("La data de Cuentas por Pagar se borró correctamente.");
			$ls_cadena="La data de Cuentas por Pagar se borró correctamente.\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}
		else
		{
			$this->io_mensajes->message("Ocurrió un error al Borrar la data de Cuentas por Pagar. Verifique el archivo txt."); 
		}
		if ($lb_valido)
		{
			$this->io_sql_destino->commit();
		}
		else
		{
			$this->io_sql_destino->rollback();	
		}
		return $lb_valido;
	}// end function ue_limpiar_cxp_basico
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_limpiar_tabla($as_tabla,$as_condicion)
	{			
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiar_tabla
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sn_profesion y los inserta en sno_profesión
		//	   Creado Por: 
		// Fecha Creación: 15/11/2006 								Fecha Última Modificación : 	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="DELETE ".
				"  FROM  ".$as_tabla." ".$as_condicion;
		$io_recordset=$this->io_sql_destino->Execute($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Borrar la tabla".$as_tabla.".\r\n".$this->io_sql_destino->message."\r\n";
			$ls_cadena=$ls_cadena.$ls_sql."\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}
		else
		{
			$ls_cadena = "//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla ".$as_tabla." Blanqueada  \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
				@fwrite($this->lo_archivo,$as_tabla." \r\n ");
			}
		}		
		return $lb_valido;
	}// end function uf_limpiar_tabla
	//-----------------------------------------------------------------------------------------------------------------------------------	
}
?>