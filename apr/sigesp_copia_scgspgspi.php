<?php 
////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Class : sigesp_copia_sss.php                                	                			  //    
// Description : Procesa la copia de datos del modulo de seguridad									  //
////////////////////////////////////////////////////////////////////////////////////////////////////////

class sigesp_copia_scgspgspi {

	var $io_sql_origen;
	var $io_sql_destino;
	var $io_mensajes;
	var $io_funciones;
	var $io_validacion;
	var	$lo_archivo;
	var $ls_database_source;
	var $ls_dabatase_target;
	
	//-----------------------------------------------------------------------------------------------------------------------------------	
	function sigesp_copia_scgspgspi()
	{
		$ld_fecha=date("_d-m-Y");
		$ls_nombrearchivo="resultado/".$_SESSION["ls_data_des"]."_scgspgspi_result_".$ld_fecha.".txt";
		$this->lo_archivo=@fopen("$ls_nombrearchivo","a+");
	
		$this->ls_database_source=$_SESSION["ls_database"];
		$this->ls_dabatase_target=$_SESSION["ls_data_des"];		
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/class_sql.php");
		require_once("class_folder/class_validacion.php");
		$this->io_mensajes=new class_mensajes();
		$this->io_validacion      = new class_validacion();
		$io_conect	= new sigesp_include();
		$io_conexion_origen = $io_conect->uf_conectar();
		$io_conexion_destino = $io_conect->uf_conectar($this->ls_dabatase_target);
		$this->io_sql_origen = new class_sql($io_conexion_origen);
		$this->io_sql_destino = new class_sql($io_conexion_destino);
	}// end function ue_copiar_scg_basicoS
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function ue_copiar_scgpsgspi_basico()
	{
		$lb_valido=true;
		$this->io_sql_destino->begin_transaction();
		//------------------------------------ Insertar datos básicos fuera de la nómina -----------------------------------------
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_planunico();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_planunicore();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_spioperaciones();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_spgoperaciones();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_fuentefinancimiento();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_spgministerioua();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_spgunidadadministrativa();
		}
		if(array_key_exists("chkspitransferir",$_POST))
		{
			if($lb_valido)
			{	
				$lb_valido=$this->uf_insert_spicuentas();
			}
		}
		if(array_key_exists("chkscgtransferir",$_POST))
		{
			if($lb_valido)
			{	
				$lb_valido=$this->uf_insert_scgcuentas();
			}
		}
		if($lb_valido)
		{	
			if(array_key_exists("chkspgtransferir",$_POST))
			{
				if($lb_valido)
				{	
					$lb_valido=$this->uf_insert_spgestructura1();
				}
				if($lb_valido)
				{	
					$lb_valido=$this->uf_insert_spgestructura2();
				}
				if($lb_valido)
				{	
					$lb_valido=$this->uf_insert_spgestructura3();
				}
				if($lb_valido)
				{	
					$lb_valido=$this->uf_insert_spgestructura4();
				}
				if($lb_valido)
				{	
					$lb_valido=$this->uf_insert_spgestructura5();
				}
				if($lb_valido)
				{	
					$lb_valido=$this->uf_insert_spgcuentas();
				}
			}
		}
		if($lb_valido)
		{	
			$this->io_mensajes->message("La data de Contabilidad, Presupuesto de Gasto y Presupuesto de Ingreso se copió correctamente.");
			$ls_cadena="La data de Contabilidad, Presupuesto de Gasto y Presupuesto de Ingreso se copió correctamente.\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}
		else
		{
			$this->io_mensajes->message("Ocurrió un error al copiar la data de Contabilidad, Presupuesto de Gasto y Presupuesto de Ingreso. Verifique el archivo txt."); 
		}
		if ($lb_valido)
		{
			$this->io_validacion->uf_insert_sistema_apertura('SCG');
			$this->io_validacion->uf_insert_sistema_apertura('SPG');
			$this->io_validacion->uf_insert_sistema_apertura('SPI');
			$this->io_sql_destino->commit();
		}
		else
		{
			$this->io_sql_destino->rollback();	
		}
		return $lb_valido;
	}// end function ue_copiar_scgpsgspi_basico
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_planunico()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_planunico
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sigesp_plan_unico
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 28/12/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido       = true;
		$li_total_select = 0;
		$li_total_insert = 0;
		$ls_sql          = "SELECT sc_cuenta, denominacion ".
						   "  FROM sigesp_plan_unico ";
		$io_recordset    = $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el plan unico.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_sc_cuenta=$this->io_validacion->uf_valida_texto($row["sc_cuenta"],0,25,"");
				$ls_denominacion=$this->io_validacion->uf_valida_texto($row["denominacion"],0,254,"");
				if($ls_sc_cuenta!="")
				{
					$ls_sql="INSERT INTO sigesp_plan_unico (sc_cuenta, denominacion) VALUES('".$ls_sc_cuenta."','".$ls_denominacion."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el plan unico.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en el plan unico.\r\n";
					$ls_cadena=$ls_cadena."Cuenta ".$ls_sc_cuenta." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sigesp_plan_unico Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sigesp_plan_unico Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_planunico
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_planunicore()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_planunicore
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sigesp_plan_unico
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 28/12/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido       = true;
		$li_total_select = 0;
		$li_total_insert = 0;
		$ls_sql          = "SELECT sig_cuenta, denominacion ".
						   "  FROM sigesp_plan_unico_re ";
		$io_recordset    = $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el plan unico_re.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_sig_cuenta=$this->io_validacion->uf_valida_texto($row["sig_cuenta"],0,25,"");
				$ls_denominacion=$this->io_validacion->uf_valida_texto($row["denominacion"],0,254,"");
				if($ls_sig_cuenta!="")
				{
					$ls_sql="INSERT INTO sigesp_plan_unico_re (sig_cuenta, denominacion) VALUES('".$ls_sig_cuenta."','".$ls_denominacion."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el plan unico_re.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en el plan unico_re.\r\n";
					$ls_cadena=$ls_cadena."Cuenta ".$ls_sig_cuenta." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sigesp_plan_unico_re Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sigesp_plan_unico_re Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_planunico
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_scgcuentas()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_scgcuentas
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de scg_cuentass
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 28/12/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido       = true;
		$li_total_select = 0;
		$li_total_insert = 0;
		$ls_sql          = "SELECT codemp, sc_cuenta, denominacion, status, asignado, distribuir, nivel, referencia ".
						   "  FROM scg_cuentas ";
		$io_recordset    = $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el plan contable.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp = $row["codemp"]; 
				$ls_sc_cuenta=$this->io_validacion->uf_valida_texto($row["sc_cuenta"],0,25,"");
				$ls_denominacion=$this->io_validacion->uf_valida_texto($row["denominacion"],0,254,"");
				$ls_status=$this->io_validacion->uf_valida_texto($row["status"],0,1,"");
				$li_asignado=$this->io_validacion->uf_valida_monto($row["asignado"],0);
				$li_distribuir=$this->io_validacion->uf_valida_monto($row["distribuir"],0);
				$li_nivel=$this->io_validacion->uf_valida_monto($row["nivel"],0);
				$ls_referencia=$this->io_validacion->uf_valida_texto($row["referencia"],0,25,"");

				$li_asignado=0;
				$li_enero=0;
				$li_febrero=0;
				$li_marzo=0;
				$li_abril=0;
				$li_mayo=0;
				$li_junio=0;
				$li_julio=0;
				$li_agosto=0;
				$li_septiembre=0;
				$li_octubre=0;
				$li_noviembre=0;
				$li_diciembre=0;

				$li_asignadoaux=0;
				$li_eneroaux=0; 
				$li_febreroaux=0;
				$li_marzoaux=0;
                $li_abrilaux=0; 
				$li_mayoaux=0;
				$li_junioaux=0;
				$li_julioaux=0;
				$li_agostoaux=0;
				$li_septiembreaux=0;
				$li_octubreaux=0;
				$li_noviembreaux=0;
				$li_diciembreaux=0;
				if($ls_sc_cuenta!="")
				{
					$ls_sql="INSERT INTO scg_cuentas (codemp, sc_cuenta, denominacion, status, asignado, distribuir, enero, febrero, marzo, abril, ".
							"mayo, junio, julio, agosto, septiembre, octubre, noviembre, diciembre, nivel, referencia, asignadoaux, eneroaux, ".
							"febreroaux, marzoaux, abrilaux, mayoaux, junioaux, julioaux, agostoaux, septiembreaux, octubreaux, noviembreaux, ".
							"diciembreaux) VALUES('".$ls_codemp."','".$ls_sc_cuenta."','".$ls_denominacion."','".$ls_status."',".
							"".$li_asignado.",".$li_distribuir.",".$li_enero.",".$li_febrero.",".$li_marzo.",".$li_abril.",".$li_mayo.",".$li_junio.",".
							"".$li_julio.",".$li_agosto.",".$li_septiembre.",".$li_octubre.",".$li_noviembre.",".$li_diciembre.",".$li_nivel.",'".$ls_referencia."',".
							"".$li_asignadoaux.",".$li_eneroaux.",".$li_febreroaux.",".$li_marzoaux.",".$li_abrilaux.",".$li_mayoaux.",".$li_junioaux.",".
							"".$li_julioaux.",".$li_agostoaux.",".$li_septiembreaux.",".$li_octubreaux.",".$li_noviembreaux.",".$li_diciembreaux.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el plan contable.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en el plan contable.\r\n";
					$ls_cadena=$ls_cadena."Cuenta ".$ls_sc_cuenta." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  scg_cuentas Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino scg_cuentas Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_scgcuentas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_spicuentas()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_spicuentas
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de spi_cuentas
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 28/12/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido       = true;
		$li_total_select = 0;
		$li_total_insert = 0;
		$ls_sql          = "SELECT codemp, spi_cuenta, denominacion, status, sc_cuenta, distribuir, nivel, referencia ".
						   "  FROM spi_cuentas ";
		$io_recordset    = $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el plan presupuesto de ingreso.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp = $row["codemp"]; 
				$ls_spicuenta=$this->io_validacion->uf_valida_texto($row["spi_cuenta"],0,25,"");
				$ls_denominacion=$this->io_validacion->uf_valida_texto($row["denominacion"],0,254,"");
				$ls_status=$this->io_validacion->uf_valida_texto($row["status"],0,1,"");
				$li_distribuir=$this->io_validacion->uf_valida_monto($row["distribuir"],0);
				$ls_sc_cuenta=$this->io_validacion->uf_valida_texto($row["sc_cuenta"],0,25,"");
				$li_nivel=$this->io_validacion->uf_valida_monto($row["nivel"],0);
				$ls_referencia=$this->io_validacion->uf_valida_texto($row["referencia"],0,25,"");

				$li_previsto=0; 
				$li_devengado=0; 
				$li_cobrado=0; 
				$li_cobrado_anticipado=0; 
				$li_aumento=0; 
				$li_disminucion=0; 
				$li_enero=0;
				$li_febrero=0;
				$li_marzo=0;
                $li_abril=0;
				$li_mayo=0;
				$li_junio=0;
				$li_julio=0; 
				$li_agosto=0;
				$li_septiembre=0;
				$li_octubre=0;
				$li_noviembre=0;
				$li_diciembre=0;

				if($ls_spicuenta!="")
				{
					$ls_sql="INSERT INTO spi_cuentas (codemp, spi_cuenta, denominacion, status, sc_cuenta, previsto, devengado, cobrado, ".
							"cobrado_anticipado, aumento, disminucion, distribuir, enero, febrero, marzo, abril, mayo, junio, julio, agosto, ".
							"septiembre, octubre, noviembre, diciembre, nivel, referencia, previstoaux, devengadoaux, cobradoaux, ".
							"cobrado_anticipadoaux, aumentoaux, disminucionaux, eneroaux, febreroaux, marzoaux, abrilaux, mayoaux, junioaux, ".
							"julioaux, agostoaux, septiembreaux, octubreaux, noviembreaux, diciembreaux) VALUES ".
					        "('".$ls_codemp."','".$ls_spicuenta."','".$ls_denominacion."','".$ls_status."','".$ls_sc_cuenta."',".$li_previsto.",".
							"".$li_devengado.",".$li_cobrado.",".$li_cobrado_anticipado.",".$li_aumento.",".$li_disminucion.",".
							"".$li_distribuir.",".$li_enero.",".$li_febrero.",".$li_marzo.",".$li_abril.",".$li_mayo.",".$li_junio.",".
							"".$li_julio.",".$li_agosto.",".$li_septiembre.",".$li_octubre.",".$li_noviembre.",".$li_diciembre.",".
							"".$li_nivel.",'".$ls_referencia."',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el plan presupuesto de ingreso.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en el  plan presupuesto de ingreso.\r\n";
					$ls_cadena=$ls_cadena."Cuenta ".$ls_spicuenta." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  spi_cuentas Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino spi_cuentas Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_spicuentas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_spioperaciones()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_spioperaciones
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de spi_operaciones
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 28/12/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido       = true;
		$li_total_select = 0;
		$li_total_insert = 0;
		$ls_sql          = "SELECT operacion, denominacion, previsto, aumento, disminucion, devengado, cobrado, cobrado_ant, reservado ".
						   "  FROM spi_operaciones ";
		$io_recordset    = $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar las operaciones de ingreso.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_operacion=$this->io_validacion->uf_valida_texto($row["operacion"],0,3,"");
				$ls_denominacion=$this->io_validacion->uf_valida_texto($row["denominacion"],0,80,"");
				$li_previsto=$this->io_validacion->uf_valida_monto($row["previsto"],0);
				$li_aumento=$this->io_validacion->uf_valida_monto($row["aumento"],0);
				$li_disminucion=$this->io_validacion->uf_valida_monto($row["disminucion"],0);
				$li_devengado=$this->io_validacion->uf_valida_monto($row["devengado"],0);
				$li_cobrado=$this->io_validacion->uf_valida_monto($row["cobrado"],0);
				$li_cobrado_ant=$this->io_validacion->uf_valida_monto($row["cobrado_ant"],0);
				$li_reservado=$this->io_validacion->uf_valida_monto($row["reservado"],0);

				if($ls_operacion!="")
				{
					$ls_sql="INSERT INTO spi_operaciones (operacion, denominacion, previsto, aumento, disminucion, devengado, cobrado, ".
							"cobrado_ant, reservado) VALUES ('".$ls_operacion."','".$ls_denominacion."',".$li_previsto.",".$li_aumento.",".
							"".$li_disminucion.",".$li_devengado.",".$li_cobrado.",".$li_cobrado_ant.",".$li_reservado.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar las operaciones de ingreso.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en las operaciones de ingreso.\r\n";
					$ls_cadena=$ls_cadena."Operación ".$ls_operacion." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  spi_operaciones Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino spi_operaciones Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_spioperaciones
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_spgoperaciones()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_spgoperaciones
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de spg_operaciones
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 28/12/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido       = true;
		$li_total_select = 0;
		$li_total_insert = 0;
		$ls_sql          = "SELECT operacion, denominacion, asignar, aumento, disminucion, precomprometer, comprometer, causar, pagar, reservado ".
						   "  FROM spg_operaciones ";
		$io_recordset    = $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar las operaciones de gasto.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_operacion=$this->io_validacion->uf_valida_texto($row["operacion"],0,3,"");
				$ls_denominacion=$this->io_validacion->uf_valida_texto($row["denominacion"],0,80,"");
				$li_asignar=$this->io_validacion->uf_valida_monto($row["asignar"],0);
				$li_aumento=$this->io_validacion->uf_valida_monto($row["aumento"],0);
				$li_disminucion=$this->io_validacion->uf_valida_monto($row["disminucion"],0);
				$li_precomprometer=$this->io_validacion->uf_valida_monto($row["precomprometer"],0);
				$li_comprometer=$this->io_validacion->uf_valida_monto($row["comprometer"],0);
				$li_causar=$this->io_validacion->uf_valida_monto($row["causar"],0);
				$li_pagar=$this->io_validacion->uf_valida_monto($row["pagar"],0);
				$li_reservado=$this->io_validacion->uf_valida_monto($row["reservado"],0);

				if($ls_operacion!="")
				{
					$ls_sql="INSERT INTO spg_operaciones (operacion, denominacion, asignar, aumento, disminucion, precomprometer, comprometer, ".
							"causar, pagar, reservado) VALUES ('".$ls_operacion."','".$ls_denominacion."',".$li_asignar.",".$li_aumento.",".
							"".$li_disminucion.",".$li_precomprometer.",".$li_comprometer.",".$li_causar.",".$li_pagar.",".$li_reservado.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar las operaciones de gasto.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en las operaciones de gasto.\r\n";
					$ls_cadena=$ls_cadena."Operación ".$ls_operacion." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  spg_operaciones Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino spg_operaciones Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_spgoperaciones
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_fuentefinancimiento()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_fuentefinancimiento
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sigesp_fuentefinanciamiento
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 28/12/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido       = true;
		$li_total_select = 0;
		$li_total_insert = 0;
		$ls_sql          = "SELECT codemp, codfuefin, denfuefin, expfuefin ".
						   "  FROM sigesp_fuentefinanciamiento ";
		$io_recordset    = $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar la Fuente de Financiamiento.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp = $row["codemp"]; 
				$ls_codfuefin = $this->io_validacion->uf_valida_texto($row["codfuefin"],0,2,"");
				$ls_denfuefin = $this->io_validacion->uf_valida_texto($row["denfuefin"],0,80,"");
				$ls_expfuefin=$this->io_validacion->uf_valida_texto($row["expfuefin"],0,254,"");
				if($ls_codfuefin!="")
				{
					$ls_sql="INSERT INTO sigesp_fuentefinanciamiento (codemp, codfuefin, denfuefin, expfuefin) ".
				 			"VALUES ('".$ls_codemp."','".$ls_codfuefin."','".$ls_denfuefin."','".$ls_expfuefin."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar la Fuente de Financiamiento.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en  la Fuente de Financiamiento.\r\n";
					$ls_cadena=$ls_cadena."Fuente de Financimiento ".$ls_codfuefin." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sigesp_fuentefinanciamiento Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sigesp_fuentefinanciamiento Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_fuentefinancimiento
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_spgministerioua()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_spgministerioua
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sigesp_fuentefinanciamiento
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 28/12/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido       = true;
		$li_total_select = 0;
		$li_total_insert = 0;
		$ls_sql          = "SELECT codemp, coduac, denuac, resuac, tipuac ".
						   "  FROM spg_ministerio_ua ";
		$io_recordset    = $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar la Unidad Administradora del Ministerio.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp = $row["codemp"]; 
				$ls_coduac = $this->io_validacion->uf_valida_texto($row["coduac"],0,5,"");
				$ls_denuac = $this->io_validacion->uf_valida_texto($row["denuac"],0,60,"");
				$ls_resuac = $this->io_validacion->uf_valida_texto($row["resuac"],0,60,"");
				$ls_tipuac = $this->io_validacion->uf_valida_texto($row["tipuac"],0,1,"");
				if($ls_coduac!="")
				{
					$ls_sql="INSERT INTO spg_ministerio_ua (codemp, coduac, denuac, resuac, tipuac) ".
				 			"VALUES ('".$ls_codemp."','".$ls_coduac."','".$ls_denuac."','".$ls_resuac."','".$ls_tipuac."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar la Unidad Administradora del Ministerio.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en la Unidad Administradora del Ministerio.\r\n";
					$ls_cadena=$ls_cadena."la Unidad Administradora del Ministerio ".$ls_coduac." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  spg_ministerio_ua Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino spg_ministerio_ua Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_spgministerioua
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_spgunidadadministrativa()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_spgunidadadministrativa
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sigesp_fuentefinanciamiento
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 28/12/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido       = true;
		$li_total_select = 0;
		$li_total_insert = 0;
		$ls_sql          = "SELECT codemp, coduniadm, coduac, denuniadm, estemireq, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, coduniadmsig ".
						   "  FROM spg_unidadadministrativa ";
		$io_recordset    = $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar la Unidad Administrativa.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp = $row["codemp"]; 
				$ls_coduniadm = $this->io_validacion->uf_valida_texto($row["coduniadm"],0,10,"");
				$ls_coduac = $this->io_validacion->uf_valida_texto($row["coduac"],0,5,"");
				$ls_denuniadm = $this->io_validacion->uf_valida_texto($row["denuniadm"],0,100,"");
				$ls_estemireq = $this->io_validacion->uf_valida_texto($row["estemireq"],0,6,"");
				$ls_codestpro1 = $this->io_validacion->uf_valida_texto($row["codestpro1"],0,20,"");
				$ls_codestpro2 = $this->io_validacion->uf_valida_texto($row["codestpro2"],0,6,"");
				$ls_codestpro3 = $this->io_validacion->uf_valida_texto($row["codestpro3"],0,3,"");
				$ls_codestpro4 = $this->io_validacion->uf_valida_texto($row["codestpro4"],0,2,"");
				$ls_codestpro5 = $this->io_validacion->uf_valida_texto($row["codestpro5"],0,2,"");
				$ls_coduniadmsig = $this->io_validacion->uf_valida_texto($row["coduniadmsig"],0,5,"");
				if($ls_coduniadm!="")
				{
					$ls_sql="INSERT INTO spg_unidadadministrativa(codemp,coduniadm,coduac,denuniadm,estemireq,codestpro1,codestpro2,".
							" codestpro3,codestpro4,codestpro5,coduniadmsig) VALUES('".$ls_codemp."','".$ls_coduniadm."','".$ls_coduac."',".
							"'".$ls_denuniadm."',".$ls_estemireq.",'".$ls_codestpro1."','".$ls_codestpro2."','".$ls_codestpro3."',".
							"'".$ls_codestpro4."','".$ls_codestpro5."','".$ls_coduniadmsig."')";						 
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar la Unidad Administrativa.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en la Unidad Administrativa.\r\n";
					$ls_cadena=$ls_cadena."Unidad Administrativa ".$ls_coduniadm." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  spg_unidadadministrativa Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino spg_unidadadministrativa Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_spgunidadadministrativa
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_spgestructura1()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_spgestructura1
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de spg_ep1
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 28/12/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido       = true;
		$li_total_select = 0;
		$li_total_insert = 0;
		$ls_sql          = "SELECT codemp, codestpro1, denestpro1, estcla ".
						   "  FROM spg_ep1 ";
		$io_recordset    = $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar la Estructura Presupuestaria 1.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp = $row["codemp"]; 
				$ls_codestpro1 = $this->io_validacion->uf_valida_texto($row["codestpro1"],0,20,"");
				$ls_denestpro1 = $this->io_validacion->uf_valida_texto($row["denestpro1"],0,254,"");
				$ls_estcla = $this->io_validacion->uf_valida_texto($row["estcla"],0,1,"");
				if($ls_codestpro1!="")
				{
					$ls_sql="INSERT INTO spg_ep1 (codemp, codestpro1, denestpro1, estcla) VALUES('".$ls_codemp."','".$ls_codestpro1."',".
							"'".$ls_denestpro1."','".$ls_estcla."')";						 
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar la Estructura Presupuestaria 1.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en la Estructura Presupuestaria 1.\r\n";
					$ls_cadena=$ls_cadena."Estructura ".$ls_codestpro1." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  spg_ep1 Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino spg_ep1 Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_spgestructura1
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_spgestructura2()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_spgestructura2
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de spg_ep2
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 28/12/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido       = true;
		$li_total_select = 0;
		$li_total_insert = 0;
		$ls_sql          = "SELECT codemp, codestpro1, codestpro2, denestpro2 ".
						   "  FROM spg_ep2 ";
		$io_recordset    = $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar la Estructura Presupuestaria 2\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp = $row["codemp"]; 
				$ls_codestpro1 = $this->io_validacion->uf_valida_texto($row["codestpro1"],0,20,"");
				$ls_codestpro2 = $this->io_validacion->uf_valida_texto($row["codestpro2"],0,6,"");
				$ls_denestpro2 = $this->io_validacion->uf_valida_texto($row["denestpro2"],0,254,"");
				if($ls_codestpro1!="")
				{
					$ls_sql="INSERT INTO spg_ep2 (codemp, codestpro1, codestpro2, denestpro2) VALUES('".$ls_codemp."','".$ls_codestpro1."',".
							"'".$ls_codestpro2."','".$ls_denestpro2."')";						 
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar la Estructura Presupuestaria 2.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en la Estructura Presupuestaria 2.\r\n";
					$ls_cadena=$ls_cadena."Estructura ".$ls_codestpro1.$ls_codestpro2." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  spg_ep2 Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino spg_ep2 Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_spgestructura2
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_spgestructura3()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_spgestructura3
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de spg_ep3
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 28/12/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido       = true;
		$li_total_select = 0;
		$li_total_insert = 0;
		$ls_sql          = "SELECT codemp, codestpro1, codestpro2, codestpro3, denestpro3, codfuefin ".
						   "  FROM spg_ep3 ";
		$io_recordset    = $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar la Estructura Presupuestaria 2\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp = $row["codemp"]; 
				$ls_codestpro1 = $this->io_validacion->uf_valida_texto($row["codestpro1"],0,20,"");
				$ls_codestpro2 = $this->io_validacion->uf_valida_texto($row["codestpro2"],0,6,"");
				$ls_codestpro3 = $this->io_validacion->uf_valida_texto($row["codestpro3"],0,3,"");
				$ls_denestpro3 = $this->io_validacion->uf_valida_texto($row["denestpro3"],0,254,"");
				$ls_codfuefin = $this->io_validacion->uf_valida_texto($row["codfuefin"],0,2,"");
				if($ls_codestpro1!="")
				{
					$ls_sql="INSERT INTO spg_ep3 (codemp, codestpro1, codestpro2, codestpro3, denestpro3, codfuefin) VALUES('".$ls_codemp."',".
							"'".$ls_codestpro1."','".$ls_codestpro2."','".$ls_codestpro3."','".$ls_denestpro3."','".$ls_codfuefin."')";						 
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar la Estructura Presupuestaria 3.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en la Estructura Presupuestaria 3.\r\n";
					$ls_cadena=$ls_cadena."Estructura ".$ls_codestpro1.$ls_codestpro2.$ls_codestpro3." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  spg_ep3 Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino spg_ep3 Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_spgestructura3
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_spgestructura4()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_spgestructura4
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de spg_ep4
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 28/12/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido       = true;
		$li_total_select = 0;
		$li_total_insert = 0;
		$ls_sql          = "SELECT codemp, codestpro1, codestpro2, codestpro3, codestpro4, denestpro4 ".
						   "  FROM spg_ep4 ";
		$io_recordset    = $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar la Estructura Presupuestaria 4\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp = $row["codemp"]; 
				$ls_codestpro1 = $this->io_validacion->uf_valida_texto($row["codestpro1"],0,20,"");
				$ls_codestpro2 = $this->io_validacion->uf_valida_texto($row["codestpro2"],0,6,"");
				$ls_codestpro3 = $this->io_validacion->uf_valida_texto($row["codestpro3"],0,3,"");
				$ls_codestpro4 = $this->io_validacion->uf_valida_texto($row["codestpro4"],0,2,"");
				$ls_denestpro4 = $this->io_validacion->uf_valida_texto($row["denestpro4"],0,254,"");
				if($ls_codestpro1!="")
				{
					$ls_sql="INSERT INTO spg_ep4 (codemp, codestpro1, codestpro2, codestpro3, codestpro4, denestpro4) VALUES('".$ls_codemp."',".
							"'".$ls_codestpro1."','".$ls_codestpro2."','".$ls_codestpro3."','".$ls_codestpro4."','".$ls_denestpro4."')";						 
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar la Estructura Presupuestaria 4.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en la Estructura Presupuestaria 4.\r\n";
					$ls_cadena=$ls_cadena."Estructura ".$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  spg_ep4 Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino spg_ep4 Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_spgestructura4
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_spgestructura5()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_spgestructura5
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de spg_ep5
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 28/12/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido       = true;
		$li_total_select = 0;
		$li_total_insert = 0;
		$ls_sql          = "SELECT codemp, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, denestpro5, codfuefin ".
						   "  FROM spg_ep5 ";
		$io_recordset    = $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar la Estructura Presupuestaria 5\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp = $row["codemp"]; 
				$ls_codestpro1 = $this->io_validacion->uf_valida_texto($row["codestpro1"],0,20,"");
				$ls_codestpro2 = $this->io_validacion->uf_valida_texto($row["codestpro2"],0,6,"");
				$ls_codestpro3 = $this->io_validacion->uf_valida_texto($row["codestpro3"],0,3,"");
				$ls_codestpro4 = $this->io_validacion->uf_valida_texto($row["codestpro4"],0,2,"");
				$ls_codestpro5 = $this->io_validacion->uf_valida_texto($row["codestpro5"],0,2,"");
				$ls_denestpro5 = $this->io_validacion->uf_valida_texto($row["denestpro5"],0,254,"");
				$ls_codfuefin = $this->io_validacion->uf_valida_texto($row["codfuefin"],0,2,"");
				if($ls_codestpro1!="")
				{
					$ls_sql="INSERT INTO spg_ep5 (codemp, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, denestpro5, codfuefin) ".
							"VALUES('".$ls_codemp."','".$ls_codestpro1."','".$ls_codestpro2."','".$ls_codestpro3."','".$ls_codestpro4."',".
							"'".$ls_codestpro5."','".$ls_denestpro5."','".$ls_codfuefin."')";						 
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar la Estructura Presupuestaria 5.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en la Estructura Presupuestaria 5.\r\n";
					$ls_cadena=$ls_cadena."Estructura ".$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  spg_ep5 Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino spg_ep5 Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_spgestructura5
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_spgcuentas()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_spgcuentas
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de spg_cuentas
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 28/12/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido       = true;
		$li_total_select = 0;
		$li_total_insert = 0;
		$ls_sql          = "SELECT codemp, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, spg_cuenta, denominacion, status, sc_cuenta, distribuir, nivel, referencia ".
						   "  FROM spg_cuentas ";
		$io_recordset    = $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el plan presupuesto de gasto.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp = $row["codemp"]; 
				$ls_codestpro1 = $this->io_validacion->uf_valida_texto($row["codestpro1"],0,20,"");
				$ls_codestpro2 = $this->io_validacion->uf_valida_texto($row["codestpro2"],0,6,"");
				$ls_codestpro3 = $this->io_validacion->uf_valida_texto($row["codestpro3"],0,3,"");
				$ls_codestpro4 = $this->io_validacion->uf_valida_texto($row["codestpro4"],0,2,"");
				$ls_codestpro5 = $this->io_validacion->uf_valida_texto($row["codestpro5"],0,2,"");
				$ls_spgcuenta=$this->io_validacion->uf_valida_texto($row["spg_cuenta"],0,25,"");
				$ls_denominacion=$this->io_validacion->uf_valida_texto($row["denominacion"],0,254,"");
				$ls_status=$this->io_validacion->uf_valida_texto($row["status"],0,1,"");
				$li_distribuir=$this->io_validacion->uf_valida_monto($row["distribuir"],0);
				$ls_sc_cuenta=$this->io_validacion->uf_valida_texto($row["sc_cuenta"],0,25,"");
				$li_nivel=$this->io_validacion->uf_valida_monto($row["nivel"],0);
				$ls_referencia=$this->io_validacion->uf_valida_texto($row["referencia"],0,25,"");

				$li_asignado=0;
				$li_precomprometido=0;
				$li_comprometido=0;
				$li_causado=0;
				$li_pagado=0;
				$li_aumento=0;
				$li_disminucion=0;
				$li_reservado=0;
				$li_enero=0;
				$li_febrero=0;
				$li_marzo=0;
                $li_abril=0;
				$li_mayo=0;
				$li_junio=0;
				$li_julio=0; 
				$li_agosto=0;
				$li_septiembre=0;
				$li_octubre=0;
				$li_noviembre=0;
				$li_diciembre=0;

				if($ls_spgcuenta!="")
				{
					$ls_sql="INSERT INTO spg_cuentas (codemp, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, spg_cuenta, denominacion, ".
							"status, sc_cuenta, asignado, precomprometido, comprometido, causado, pagado, aumento, disminucion, distribuir, enero, ".
							"febrero, marzo, abril, mayo, junio, julio, agosto, septiembre, octubre, noviembre, diciembre, nivel, referencia, ".
							"asignadoaux, precomprometidoaux, comprometidoaux, causadoaux, pagadoaux, aumentoaux, disminucionaux, eneroaux, ".
							"febreroaux, marzoaux, abrilaux, mayoaux, junioaux, julioaux, agostoaux, septiembreaux, octubreaux, noviembreaux, ".
							"diciembreaux) VALUES('".$ls_codemp."','".$ls_codestpro1."','".$ls_codestpro2."','".$ls_codestpro3."','".$ls_codestpro4."',".
							"'".$ls_codestpro5."','".$ls_spgcuenta."','".$ls_denominacion."','".$ls_status."','".$ls_sc_cuenta."',".$li_asignado.",".
							"".$li_precomprometido.",".$li_comprometido.",".$li_causado.",".$li_pagado.",".$li_aumento.",".$li_disminucion.",".
							"".$li_distribuir.",".$li_enero.",".$li_febrero.",".$li_marzo.",".$li_abril.",".$li_mayo.",".$li_junio.",".$li_julio.",".
							"".$li_agosto.",".$li_septiembre.",".$li_octubre.",".$li_noviembre.",".$li_diciembre.",".$li_nivel.",'".$ls_referencia."',".
							"0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el plan presupuesto de gasto.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en el  plan presupuesto de gasto.\r\n";
					$ls_cadena=$ls_cadena."Cuenta ".$ls_spgcuenta." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  spg_cuentas Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino spg_cuentas Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_spgcuentas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function ue_limpiar_scgpsgspi_basico()
	{
		$lb_valido=true;
		$this->io_sql_destino->begin_transaction();
		//------------------------------------ Borrar tablas de Contabilidad -----------------------------------------
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla("scg_cuentas","");			
		}	
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla("spi_cuentas","");			
		}	
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla("spg_cuentas","");			
		}	
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla("spg_ep5","");			
		}	
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla("spg_ep4","");			
		}	
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla("spg_ep3","");			
		}	
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla("spg_ep2","");			
		}	
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla("spg_ep1","");			
		}	
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla("sigesp_fuentefinanciamiento","");			
		}	
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla("spg_unidadadministrativa","");			
		}	
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla("spg_ministerio_ua","");			
		}	
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla("spi_operaciones","");			
		}	
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla("spg_operaciones","");			
		}	
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla("sigesp_plan_unico","");			
		}	
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla("sigesp_plan_unico_re","");			
		}	
		if($lb_valido)  	
		{
			$this->io_mensajes->message("La data de Contabilidad, Presupuesto de Gasto y Presupuesto de Ingreso se borró correctamente.");
			$ls_cadena="La data de  Contabilidad, Presupuesto de Gasto y Presupuesto de Ingreso  se borró correctamente.\r\n";
			if ($this->lo_archivo)
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}
		else
		{
			$this->io_mensajes->message("Ocurrió un error al borrar la data de  Contabilidad, Presupuesto de Gasto y Presupuesto de Ingreso . Verifique el archivo txt."); 
		}
		if($lb_valido)
		{
			$this->io_sql_destino->commit();
		}
		else
		{
			$this->io_sql_destino->rollback();	
		}
		return $lb_valido;
	}// end function ue_limpiar_scg_basico
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_limpiar_tabla($as_tabla,$as_condicion)
	{			
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiar_tabla
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Borra la data de la tabla especificada en la base de datos destino
		//				   $as_condicion se agrega por si es necesario algún filtro en la consulta
		//	   Creado Por: 
		// Fecha Creación: 15/11/2006 								Fecha Última Modificación : 	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		$ls_sql="DELETE ".
				"  FROM  ".$as_tabla." ".$as_condicion;
		$io_recordset=$this->io_sql_destino->execute($ls_sql);	
		
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
			$ls_cadena=$ls_cadena."   Tabla  ".$as_tabla."  Blanqueada  \r\n";
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