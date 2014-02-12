<?php 
////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Class : sigesp_copia_scb.php                                	                			  //    
// Description : Procesa la copia de datos del modulo de banco										  //
////////////////////////////////////////////////////////////////////////////////////////////////////////

class sigesp_copia_scb {

	var $io_sql_origen;
	var $io_sql_destino;
	var $io_mensajes;
	var $io_funciones;
	var $io_validacion;
	var	$lo_archivo;
	var $ls_database_source;
	var $ls_database_target;
	
	//-----------------------------------------------------------------------------------------------------------------------------------	
	function sigesp_copia_scb()
	{
		$ld_fecha=date("_d-m-Y");
		$ls_nombrearchivo="resultado/".$_SESSION["ls_data_des"]."_scb_result_".$ld_fecha.".txt";
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
	}// end function sigesp_copia_scb
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function ue_copiar_banco_basico()
	{
		$lb_valido=true;
		$this->io_sql_destino->begin_transaction();
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_banco();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_tipocuenta();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_cuentabanco();
		}		
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_chequeras();
		}		
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_tipocolocacion();
		}			
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_colocacion();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_agencias();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_concepto();
		}		
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_formatocartaorden();
		}		
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_config();
		}		
	
		if($lb_valido)
		{
			$this->io_mensajes->message("La data de Banco se copió correctamente.");
			$ls_cadena="La data de Banco se copió correctamente.\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}
		else
		{
			$this->io_mensajes->message("Ocurrió un error al copiar la data de Banco. Verifique el archivo txt."); 
		}
		if ($lb_valido)
		{
			$this->io_validacion->uf_insert_sistema_apertura('SCB');
			$this->io_sql_destino->commit();
		}
		else
		{
			$this->io_sql_destino->rollback();	
		}
		return $lb_valido;	
	}// end function ue_copiar_banco_basico
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_insert_banco()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_banco
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de scb_banco
		//	   Creado Por: 
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido       = true;
		$li_total_select = 0;
		$li_total_insert = 0;
		$ls_sql          = "SELECT codemp, codban, nomban, dirban, gerban, telban, conban, movcon, esttesnac ".
						   "  FROM scb_banco ";
		$io_recordset    = $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar los Bancos.\r\n".$this->io_sql_origen->message."\r\n";
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
				$la_codban=$this->io_validacion->uf_valida_texto($row["codban"],0,3,"");
				$ls_nomban=$this->io_validacion->uf_valida_texto($row["nomban"],0,60,"");
				$ls_dirban=$this->io_validacion->uf_valida_texto($row["dirban"],0,80,"");
				$ls_gerban=$this->io_validacion->uf_valida_texto($row["gerban"],0,60,"");
				$ls_telban=$this->io_validacion->uf_valida_texto($row["telban"],0,20,"");
				$ls_conban=$this->io_validacion->uf_valida_texto($row["conban"],0,60,"");
				$ls_movcon=$this->io_validacion->uf_valida_texto($row["movcon"],0,20,"");
				$li_esttesnac=$this->io_validacion->uf_valida_monto($row["esttesnac"],0);

				if($la_codban!="")
				{
					$ls_sql="INSERT INTO scb_banco (codemp, codban, nomban, dirban, gerban, telban, conban, movcon, esttesnac) VALUES ".
							"('".$ls_codemp."','".$la_codban."','".$ls_nomban."','".$ls_dirban."','".$ls_gerban."','".$ls_telban."',".
							"'".$ls_conban."','".$ls_movcon."',".$li_esttesnac.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el banco.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en el banco.\r\n";
					$ls_cadena=$ls_cadena."banco ".$la_codban." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  scb_banco Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino scb_banco Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_banco
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_insert_tipocuenta()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_tipocuenta
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de scb_banco
		//	   Creado Por: 
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido       = true;
		$li_total_select = 0;
		$li_total_insert = 0;
		$ls_sql          = "SELECT codtipcta, nomtipcta ".
						   "  FROM scb_tipocuenta ";
		$io_recordset    = $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar los Tipos de Cuenta.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codtipcta=$this->io_validacion->uf_valida_texto($row["codtipcta"],0,3,"");
				$ls_nomtipcta=$this->io_validacion->uf_valida_texto($row["nomtipcta"],0,30,"");
				if($ls_codtipcta!="")
				{
					$ls_sql="INSERT INTO scb_tipocuenta (codtipcta, nomtipcta) VALUES ('".$ls_codtipcta."','".$ls_nomtipcta."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el tipo de cuenta.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en el el tipo de cuenta.\r\n";
					$ls_cadena=$ls_cadena."Tipo de Cuenta ".$ls_codtipcta." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  scb_tipocuenta Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino scb_tipocuenta Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_tipocuenta
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_insert_cuentabanco()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cuentabanco
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de scb_ctabanco
		//	   Creado Por: 
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido       = true;
		$li_total_select = 0;
		$li_total_insert = 0;
		$ls_sql          = "SELECT codemp, codban, ctaban, codtipcta, ctabanext, dencta, sc_cuenta, fecapr, feccie, estact ".
						   "  FROM scb_ctabanco ";
		$io_recordset    = $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar las cuentas de banco .\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codban=$this->io_validacion->uf_valida_texto($row["codban"],0,3,"");
				$ls_ctaban=$this->io_validacion->uf_valida_texto($row["ctaban"],0,25,"");
				$ls_codtipcta=$this->io_validacion->uf_valida_texto($row["codtipcta"],0,3,"");
				$ls_ctabanext=$this->io_validacion->uf_valida_texto($row["ctabanext"],0,25,"");
				$ls_dencta=$this->io_validacion->uf_valida_texto($row["dencta"],0,50,"");
				$ls_sc_cuenta=$this->io_validacion->uf_valida_texto($row["sc_cuenta"],0,25,"");
				$ld_fecapr=$this->io_validacion->uf_valida_fecha($row["fecapr"],"1900-01-01");
				$ld_feccie=$this->io_validacion->uf_valida_fecha($row["feccie"],"1900-01-01");
				$li_estact=$this->io_validacion->uf_valida_monto($row["estact"],0);
				if($ls_codban!="")
				{
					$ls_sql="INSERT INTO scb_ctabanco (codemp, codban, ctaban, codtipcta, ctabanext, dencta, sc_cuenta, fecapr, feccie, estact) ".
							"VALUES ('".$ls_codemp."','".$ls_codban."','".$ls_ctaban."','".$ls_codtipcta."','".$ls_ctabanext."','".$ls_dencta."',".
							"'".$ls_sc_cuenta."','".$ld_fecapr."','".$ld_feccie."',".$li_estact.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar lsa cuentas de banco.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en las cuentas de banco.\r\n";
					$ls_cadena=$ls_cadena."Banco ".$ls_codban." Cuentas ".$ls_ctaban." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  scb_ctabanco Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino scb_ctabanco Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_cuentabanco
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_insert_chequeras()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_chequeras
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de scb_ctabanco
		//	   Creado Por: 
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido       = true;
		$li_total_select = 0;
		$li_total_insert = 0;
		$ls_sql          = "SELECT codemp, codban, ctaban, numche, estche, numchequera ".
						   "  FROM scb_cheques ";
		$io_recordset    = $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar Chequera.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp   = $this->io_validacion->uf_valida_texto($row["codemp"],0,4,"");
				$ls_codban   = $this->io_validacion->uf_valida_texto($row["codban"],0,3,"");
				$ls_ctaban   = $this->io_validacion->uf_valida_texto($row["ctaban"],0,25,"");
				$ls_numche   = $this->io_validacion->uf_valida_texto($row["numche"],0,15,"");
				$li_estche   = $this->io_validacion->uf_valida_monto($row["estche"],0);
				$ls_chequera = $this->io_validacion->uf_valida_texto($row["numchequera"],0,10,"");

				if(!empty($ls_codban) && !empty($ls_ctaban) && !empty($ls_numche) && !empty($ls_chequera))
				{
					$ls_sql="INSERT INTO scb_cheques (codemp, codban, ctaban, numche, estche, numchequera) ".
							"VALUES ('".$ls_codemp."','".$ls_codban."','".$ls_ctaban."','".$ls_numche."',".$li_estche.",'".$ls_chequera."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar Chequera.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en las Chequeras.\r\n";
					$ls_cadena=$ls_cadena."Banco ".$ls_codban." Cuentas ".$ls_ctaban." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  scb_cheques Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino scb_cheques Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_chequeras
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_insert_tipocolocacion()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_tipocolocacion
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de scb_ctabanco
		//	   Creado Por: 
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido       = true;
		$li_total_select = 0;
		$li_total_insert = 0;
		$ls_sql          = "SELECT codtipcol, nomtipcol ".
						   "  FROM scb_tipocolocacion ";
		$io_recordset    = $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar los tipos de colocación .\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codtipcol=$this->io_validacion->uf_valida_texto($row["codtipcol"],0,3,"");
				$ls_nomtipcol=$this->io_validacion->uf_valida_texto($row["nomtipcol"],0,60,"");
				if($ls_codtipcol!="")
				{
					$ls_sql="INSERT INTO scb_tipocolocacion (codtipcol, nomtipcol) ".
							"VALUES ('".$ls_codtipcol."','".$ls_nomtipcol."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar los tipos de colocación.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los tipos de colocación.\r\n";
					$ls_cadena=$ls_cadena."Tipo Colocación ".$ls_codtipcol." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  scb_tipocolocacion Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino scb_tipocolocacion Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_tipocolocacion
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_insert_colocacion()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_colocacion
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de scb_colocacion
		//	   Creado Por: 
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido       = true;
		$li_total_select = 0;
		$li_total_insert = 0;
		$ls_sql          = "SELECT codemp, codban, ctaban, numcol, dencol, codtipcol, feccol, diacol, tascol, monto, fecvencol, monint, sc_cuenta, ".
						   "	   spi_cuenta, estreicol ".
						   "  FROM scb_colocacion ";
		$io_recordset    = $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar las colocaciones .\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codban=$this->io_validacion->uf_valida_texto($row["codban"],0,3,"");
				$ls_ctaban=$this->io_validacion->uf_valida_texto($row["ctaban"],0,25,"");
				$ls_numcol=$this->io_validacion->uf_valida_texto($row["numcol"],0,15,"");
				$ls_dencol=$this->io_validacion->uf_valida_texto($row["dencol"],0,200,"");
				$ls_codtipcol=$this->io_validacion->uf_valida_texto($row["codtipcol"],0,3,"");
				$ld_feccol=$this->io_validacion->uf_valida_fecha($row["feccol"],"1900-01-01");
				$li_diacol=$this->io_validacion->uf_valida_monto($row["diacol"],0);
				$li_tascol=$this->io_validacion->uf_valida_monto($row["tascol"],0);
				$li_monto=$this->io_validacion->uf_valida_monto($row["monto"],0);
				$ld_fecvencol=$this->io_validacion->uf_valida_fecha($row["fecvencol"],"1900-01-01");
				$li_monint=$this->io_validacion->uf_valida_monto($row["monint"],0);
				$ls_sc_cuenta=$this->io_validacion->uf_valida_texto($row["sc_cuenta"],0,25,"");
				$ls_spi_cuenta=$this->io_validacion->uf_valida_texto($row["spi_cuenta"],0,25,"");
				$li_estreicol=$this->io_validacion->uf_valida_monto($row["estreicol"],0);
				$li_monto=$this->io_rcbsf->uf_convertir_monedabsf($li_monto,2,1,1000,1);
				$li_monint=$this->io_rcbsf->uf_convertir_monedabsf($li_monint,2,1,1000,1);
				$li_montoaux=$this->io_validacion->uf_valida_monto($row["monto"],0);
				$li_monintaux=$this->io_validacion->uf_valida_monto($row["monint"],0);
				if($ls_numcol!="")
				{
					$ls_sql="INSERT INTO scb_colocacion (codemp, codban, ctaban, numcol, dencol, codtipcol, feccol, diacol, tascol, monto, ".
						    "fecvencol, monint, sc_cuenta, spi_cuenta, estreicol, montoaux, monintaux) VALUES ('".$ls_codemp."','".$ls_codban."',".
							"'".$ls_ctaban."','".$ls_numcol."','".$ls_dencol."','".$ls_codtipcol."','".$ld_feccol."',".$li_diacol.",".$li_tascol.",".
							"".$li_monto.",'".$ld_fecvencol."',".$li_monint.",'".$ls_sc_cuenta."','".$ls_spi_cuenta."',".$li_estreicol.",".
							"".$li_montoaux.",".$li_monintaux.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar las colocaciones .\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en las colocaciones.\r\n";
					$ls_cadena=$ls_cadena." Colocación ".$ls_numcol." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  scb_colocacion Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino scb_colocacion Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_colocacion
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_insert_agencias()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_agencias
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de scb_colocacion
		//	   Creado Por: 
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido       = true;
		$li_total_select = 0;
		$li_total_insert = 0;
		$ls_sql          = "SELECT codemp, codban, codage, nomage ".
						   "  FROM scb_agencias ";
		$io_recordset    = $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar las agencias .\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codban=$this->io_validacion->uf_valida_texto($row["codban"],0,3,"");
				$ls_codage=$this->io_validacion->uf_valida_texto($row["codage"],0,10,"");
				$ls_nomage=$this->io_validacion->uf_valida_texto($row["nomage"],0,80,"");
				if($ls_codage!="")
				{
					$ls_sql="INSERT INTO scb_agencias (codemp, codban, codage, nomage) VALUES ('".$ls_codemp."','".$ls_codban."',".
							"'".$ls_codage."','".$ls_nomage."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar la agencia .\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en las Agencias.\r\n";
					$ls_cadena=$ls_cadena."Agencia ".$ls_codage." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  scb_agencias Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino scb_agencias Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_agencias
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_insert_concepto()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_concepto
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de scb_colocacion
		//	   Creado Por: 
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido       = true;
		$li_total_select = 0;
		$li_total_insert = 0;
		$ls_sql          = "SELECT codconmov, denconmov, codope ".
						   "  FROM scb_concepto ";
		$io_recordset    = $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar los conceptos .\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codconmov=$this->io_validacion->uf_valida_texto($row["codconmov"],0,3,"");
				$ls_denconmov=$this->io_validacion->uf_valida_texto($row["denconmov"],0,80,"");
				$ls_codope=$this->io_validacion->uf_valida_texto($row["codope"],0,2,"");
				if($ls_codconmov!="")
				{
					$ls_sql="INSERT INTO scb_concepto (codconmov, denconmov, codope) VALUES ('".$ls_codconmov."','".$ls_denconmov."',".
							"'".$ls_codope."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el concepto .\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los conceptos.\r\n";
					$ls_cadena=$ls_cadena."Concepto ".$ls_codconmov." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  scb_concepto Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino scb_concepto Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_concepto
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_insert_formatocartaorden()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_formatocartaorden
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de scb_cartaorden
		//	   Creado Por: 
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido       = true;
		$li_total_select = 0;
		$li_total_insert = 0;
		$ls_sql          = "SELECT codigo, encabezado, cuerpo, pie, nombre, status, codemp, archrtf ".
						   "  FROM scb_cartaorden ";
		$io_recordset    = $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar los formatos de carta orden .\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codigo=$this->io_validacion->uf_valida_texto($row["codigo"],0,3,"");
				$ls_encabezado=$this->io_validacion->uf_valida_texto($row["encabezado"],0,8000,"");
				$ls_cuerpo=$this->io_validacion->uf_valida_texto($row["cuerpo"],0,8000,"");
				$ls_pie=$this->io_validacion->uf_valida_texto($row["pie"],0,8000,"");
				$ls_nombre=$this->io_validacion->uf_valida_texto($row["nombre"],0,50,"");
				$li_status=$this->io_validacion->uf_valida_monto($row["status"],0);
				$ls_codemp=$this->io_validacion->uf_valida_texto($row["codemp"],0,4,"");
				$ls_archrtf=$this->io_validacion->uf_valida_texto($row["archrtf"],0,50,"");
				if($ls_codigo!="")
				{
					$ls_sql="INSERT INTO scb_cartaorden (codigo, encabezado, cuerpo, pie, nombre, status, codemp, archrtf) VALUES ".
							"('".$ls_codigo."','".$ls_encabezado."','".$ls_cuerpo."','".$ls_pie."','".$ls_nombre."',".$li_status.",".
							"'".$ls_codemp."','".$ls_archrtf."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el formato de carta orden .\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en el formato de carta orden .\r\n";
					$ls_cadena=$ls_cadena."Formato ".$ls_codigo." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  scb_cartaorden Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino scb_cartaorden Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_formatocartaorden
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_insert_config()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_config
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de scb_cartaorden
		//	   Creado Por: 
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido       = true;
		$li_total_select = 0;
		$li_total_insert = 0;
		$ls_sql          = "SELECT id, numordpag ".
						   "  FROM scb_config ";
		$io_recordset    = $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el config de bancos.\r\n".$this->io_sql_origen->message."\r\n";
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
				$li_id=$this->io_validacion->uf_valida_monto($row["id"],0);
				$ls_numordpag=$this->io_validacion->uf_valida_texto($row["numordpag"],0,15,"");
				if($li_id!="")
				{
					$ls_sql="INSERT INTO scb_config (id, numordpag) VALUES ".
							"(".$li_id.",'".$ls_numordpag."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el config de bancos.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en el config de bancos.\r\n";
					$ls_cadena=$ls_cadena."Formato ".$li_id." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  scb_config Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino scb_config Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_config
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function ue_limpiar_banco_basico()
	{
		$lb_valido=true;
		$this->io_sql_destino->begin_transaction();
		//------------------------------------ Borrar tablas de banco -----------------------------------------
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('scb_colocacion',"  ");			
		}
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('scb_tipocolocacion',"  ");			
		}
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('scb_ctabanco',"  ");			
		}
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('scb_chequera',"  ");			
		}
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('scb_tipocuenta',"  ");			
		}
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('scb_agencias',"  ");			
		}
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('scb_banco',"  ");			
		}
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('scb_concepto',"  ");			
		}
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('scb_cartaorden',"  ");			
		}
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('scb_config',"  ");			
		}
		if($lb_valido)
		{
			$this->io_mensajes->message("La data de Banco se borró correctamente.");
			$ls_cadena="La data de Banco se borró correctamente.\r\n";
			if ($this->lo_archivo)
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}
		else
		{
			$this->io_mensajes->message("Ocurrió un error al borrar la data de Banco. Verifique el archivo txt."); 
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
	}// end function ue_limpiar_banco_basico
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