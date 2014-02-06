<?php 
////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Class : sigesp_copia_saf.php                                 	                			  //    
// Description : Procesa la copia de datos del modulo de activos fijos								  //
////////////////////////////////////////////////////////////////////////////////////////////////////////

class sigesp_copia_saf {

	var $io_sql_origen;
	var $io_sql_destino;
	var $io_mensajes;
	var $io_funciones;
	var $io_validacion;
	var	$lo_archivo;
	var $ls_database_source;
	var $ls_dabatase_target;
	
function sigesp_copia_saf()
{
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");/////agregado el 06/12/2007
	require_once("class_folder/class_validacion.php");
	$this->ls_database_source = $_SESSION["ls_database"];
	$this->ls_database_target = $_SESSION["ls_data_des"];
	$this->io_mensajes        = new class_mensajes();		
	$this->io_funciones       = new class_funciones();
	$this->io_validacion      = new class_validacion();
	$io_conect	              = new sigesp_include();
	$io_conexion_origen       = $io_conect->uf_conectar();
	$io_conexion_destino      = $io_conect->uf_conectar($this->ls_database_target);
	$this->io_sql_origen      = new class_sql($io_conexion_origen);
	$this->io_sql_destino 	  = new class_sql($io_conexion_destino);
	$ld_fecha=date("_d-m-Y");
	$ls_nombrearchivo="resultado/".trim($_SESSION["la_empresa"]["sigemp"])."_saf_result_".$ld_fecha.".txt";
	$this->lo_archivo=@fopen("$ls_nombrearchivo","a+");
	$this->io_rcbsf			  = new sigesp_c_reconvertir_monedabsf(); 
	$this->li_candeccon= 4;
	$this->li_tipconmon= 1;
	$this->li_redconmon=1;
}

function ue_copiar_saf_basico()
{
	$lb_valido=true;
	$this->io_sql_destino->begin_transaction();
	//------------------------------------ Insertar datos básicos fuera de saf -----------------------------------------
	if($lb_valido)
	{	
		$lb_valido=$this->uf_insert_catalogo();
	}		
	/*if($lb_valido)
	{	
		$lb_valido=$this->uf_insert_causas();
	}*/
	if($lb_valido)
	{	
		$lb_valido=$this->uf_insert_condicioncompra();
	}		
	if($lb_valido)
	{	
		$lb_valido=$this->uf_insert_conservacionbien();
	}		
	if($lb_valido)
	{	
		$lb_valido=$this->uf_insert_metodo();
	}		
	if($lb_valido)
	{	
		$lb_valido=$this->uf_insert_situacioncontable();
	}		
	if($lb_valido)
	{	
		$lb_valido=$this->uf_insert_grupo();
	}		
	if($lb_valido)
	{	
		$lb_valido=$this->uf_insert_subgrupo();
	}		
	if($lb_valido)
	{	
		$lb_valido=$this->uf_insert_seccion();
	}		
	if($lb_valido)
	{	
		$lb_valido=$this->uf_insert_rotulacion();
	}
	if($lb_valido)
	{	
		$lb_valido=$this->uf_insert_activo();
	}		
	if($lb_valido)
	{	
		$lb_valido=$this->uf_insert_dta();
	}		
	if($lb_valido)
	{	
		$lb_valido=$this->uf_insert_depreciacion();
	}
	if($lb_valido)
	{	
		$this->io_mensajes->message("La data de Activos se copió correctamente.");
		$ls_cadena="La data de Activos se copió correctamente.\r\n";
		if ($this->lo_archivo)			
		{
			@fwrite($this->lo_archivo,$ls_cadena);
		}
	}
	else
	{
		$this->io_mensajes->message("Ocurrió un error al copiar la data de Activos. Verifique el archivo txt."); 
	}
	if ($lb_valido)
	{
		$this->io_validacion->uf_insert_sistema_apertura('SAF');
		$this->io_sql_destino->commit();
	}
	else
	{
		$this->io_sql_destino->rollback();	
	}
	return $lb_valido;	
}


	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_catalogo()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_catalogo
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 28/12/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$li_total_select= 0;
		$li_total_insert= 0;
		$ls_sql="SELECT catalogo, dencat, spg_cuenta".
				"  FROM saf_catalogo ";
		$io_recordset= $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el catalogo.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_catalogo= $this->io_validacion->uf_valida_texto($row["catalogo"],0,15,"");
				$ls_dencat = $this->io_validacion->uf_valida_texto($row["dencat"],0,254,"");
				$ls_spg_cuenta = $this->io_validacion->uf_valida_texto($row["spg_cuenta"],0,25,"");
				if($ls_catalogo!="")
				{
					$ls_sql="INSERT INTO saf_catalogo(catalogo, dencat, spg_cuenta)".
							"	  VALUES ('".$ls_catalogo."','".$ls_dencat."','".$ls_spg_cuenta."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el catalogo.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en el catalogo.\r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  saf_catalogo Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino saf_catalogo Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_catalogo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_causas()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_causas
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 28/12/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$li_total_select= 0;
		$li_total_insert= 0;
		$ls_sql="SELECT codcau, dencau, tipcau, estafecon, estafepre, expcau".
				"  FROM saf_causas ";
		$io_recordset= $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar la causa.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codcau= $this->io_validacion->uf_valida_texto($row["codcau"],0,3,"");
				$ls_dencau = $this->io_validacion->uf_valida_texto($row["dencau"],0,254,"");
				$ls_tipcau = $this->io_validacion->uf_valida_texto($row["tipcau"],0,1,"");
				$li_estafecon=$this->io_validacion->uf_valida_monto($row["estafecon"],0);
				$li_estafepre=$this->io_validacion->uf_valida_monto($row["estafepre"],0);
				$ls_expcau = $this->io_validacion->uf_valida_texto($row["expcau"],0,254,"");
				if($ls_codcau!="")
				{
					$ls_sql="INSERT INTO saf_causas(codcau, dencau, tipcau, estafecon, estafepre, expcau)".
							"	  VALUES ('".$ls_codcau."','".$ls_dencau."','".$ls_tipcau."',".$li_estafecon.",".
							"             ".$li_estafepre.",'".$ls_expcau."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);print $ls_sql.';<br>';
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar la causa.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en las Causas.\r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  saf_causas Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino saf_causas Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_causas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_condicioncompra()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_condicioncompra
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 28/12/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$li_total_select= 0;
		$li_total_insert= 0;
		$ls_sql="SELECT codconcom, denconcom, expconcom".
				"  FROM saf_condicioncompra ";
		$io_recordset= $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar la condicion de compra.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codconcom= $this->io_validacion->uf_valida_texto($row["codconcom"],0,2,"");
				$ls_denconcom = $this->io_validacion->uf_valida_texto($row["denconcom"],0,254,"");
				$ls_expconcom = $this->io_validacion->uf_valida_texto($row["expconcom"],0,500,"");
				if($ls_codconcom!="")
				{
					$ls_sql="INSERT INTO saf_condicioncompra(codconcom, denconcom, expconcom)".
							"	  VALUES ('".$ls_codconcom."','".$ls_denconcom."','".$ls_expconcom."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar la condicion de compra.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en las Condiciones de compra.\r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  saf_condicioncompra Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino saf_condicioncompra Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_condicioncompra
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_conservacionbien()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_conservacionbien
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 28/12/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$li_total_select= 0;
		$li_total_insert= 0;
		$ls_sql="SELECT codconbie, denconbie, desconbie".
				"  FROM saf_conservacionbien ";
		$io_recordset= $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el pais.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codconbie= $this->io_validacion->uf_valida_texto($row["codconbie"],0,1,"");
				$ls_denconbie = $this->io_validacion->uf_valida_texto($row["denconbie"],0,30,"");
				$ls_desconbie = $this->io_validacion->uf_valida_texto($row["desconbie"],0,8000,"");
				if($ls_codconbie!="")
				{
					$ls_sql="INSERT INTO saf_conservacionbien(codconbie, denconbie, desconbie)".
							"	  VALUES ('".$ls_codconbie."','".$ls_denconbie."','".$ls_desconbie."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el pais.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los Paises.\r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  saf_conservacionbien Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino saf_conservacionbien	 Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_conservacionbien
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_metodo()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_metodo
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 28/12/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$li_total_select= 0;
		$li_total_insert= 0;
		$ls_sql="SELECT codmetdep, denmetdep, formetdep".
				"  FROM saf_metodo ";
		$io_recordset= $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el metodo.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codmetdep= $this->io_validacion->uf_valida_texto($row["codmetdep"],0,3,"");
				$ls_denmetdep = $this->io_validacion->uf_valida_texto($row["denmetdep"],0,100,"");
				$ls_formetdep = $this->io_validacion->uf_valida_texto($row["formetdep"],0,254,"");
				if($ls_codmetdep!="")
				{
					$ls_sql="INSERT INTO saf_metodo(codmetdep, denmetdep, formetdep)".
							"	  VALUES ('".$ls_codmetdep."','".$ls_denmetdep."','".$ls_formetdep."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el metodo.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los Metodos.\r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$lb_valido = $this->uf_insert_metodo_default();
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  saf_metodo Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino saf_metodo Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_metodo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_situacioncontable()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_situacioncontable
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 28/12/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$li_total_select= 0;
		$li_total_insert= 0;
		$ls_sql="SELECT codsitcon, densitcon, expsitcon".
				"  FROM saf_situacioncontable ";
		$io_recordset= $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar la situacion contable.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codsitcon= $this->io_validacion->uf_valida_texto($row["codsitcon"],0,1,"");
				$ls_densitcon = $this->io_validacion->uf_valida_texto($row["densitcon"],0,254,"");
				$ls_expsitcon = $this->io_validacion->uf_valida_texto($row["expsitcon"],0,8000,"");
				if($ls_codsitcon!="")
				{
					$ls_sql="INSERT INTO saf_situacioncontable(codsitcon, densitcon, expsitcon)".
							"	  VALUES ('".$ls_codsitcon."','".$ls_densitcon."','".$ls_expsitcon."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar la situacion contable.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en las Situaciones Contables.\r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  saf_situacioncontable Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino saf_situacioncontable Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_situacioncontable
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_grupo()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_grupo
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 28/12/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$li_total_select= 0;
		$li_total_insert= 0;
		$ls_sql="SELECT codgru, dengru".
				"  FROM saf_grupo ";
		$io_recordset= $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el grupo.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codgru= $this->io_validacion->uf_valida_texto($row["codgru"],0,3,"");
				$ls_dengru = $this->io_validacion->uf_valida_texto($row["dengru"],0,254,"");
				if($ls_codgru!="")
				{
					$ls_sql="INSERT INTO saf_grupo(codgru, dengru)".
							"	  VALUES ('".$ls_codgru."','".$ls_dengru."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el grupo.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los Grupos.\r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  saf_grupo Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino saf_grupo Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_grupo
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_subgrupo()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_subgrupo
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 28/12/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$li_total_select= 0;
		$li_total_insert= 0;
		$ls_sql="SELECT codgru, codsubgru, densubgru".
				"  FROM saf_subgrupo ";
		$io_recordset= $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el sub-grupo.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codgru= $this->io_validacion->uf_valida_texto($row["codgru"],0,3,"");
				$ls_codsubgru = $this->io_validacion->uf_valida_texto($row["codsubgru"],0,3,"");
				$ls_densubgru = $this->io_validacion->uf_valida_texto($row["densubgru"],0,254,"");
				if($ls_codsubgru!="")
				{
					$ls_sql="INSERT INTO saf_subgrupo(codgru, codsubgru, densubgru)".
							"	  VALUES ('".$ls_codgru."','".$ls_codsubgru."','".$ls_densubgru."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el subgrupo.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los Sub-Grupos.\r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  saf_subgrupo Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino saf_subgrupo Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_subgrupo
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seccion()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_seccion
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 28/12/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$li_total_select= 0;
		$li_total_insert= 0;
		$ls_sql="SELECT codgru, codsubgru, codsec, densec".
				"  FROM saf_seccion ";
		$io_recordset= $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar la seccion.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codgru= $this->io_validacion->uf_valida_texto($row["codgru"],0,3,"");
				$ls_codsubgru= $this->io_validacion->uf_valida_texto($row["codsubgru"],0,3,"");
				$ls_codsec= $this->io_validacion->uf_valida_texto($row["codsec"],0,3,"");
				$ls_densec = $this->io_validacion->uf_valida_texto($row["densec"],0,254,"");
				if($ls_codsec!="")
				{
					$ls_sql="INSERT INTO saf_seccion(codgru, codsubgru, codsec, densec)".
							"	  VALUES ('".$ls_codgru."','".$ls_codsubgru."','".$ls_codsec."','".$ls_densec."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar la seccion.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en las Secciones.\r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  saf_seccion Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino saf_seccion Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_seccion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_rotulacion()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_rotulacion
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 28/12/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$li_total_select= 0;
		$li_total_insert= 0;
		$ls_sql="SELECT codrot, denrot, emprot".
				"  FROM saf_rotulacion ";
		$io_recordset= $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar la rotulacion.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codrot= $this->io_validacion->uf_valida_texto($row["codrot"],0,1,"");
				$ls_denrot= $this->io_validacion->uf_valida_texto($row["denrot"],0,100,"");
				$ls_emprot= $this->io_validacion->uf_valida_texto($row["emprot"],0,8000,"");
				if($ls_codrot!="")
				{
					$ls_sql="INSERT INTO saf_rotulacion(codrot, denrot, emprot)".
							"	  VALUES ('".$ls_codrot."','".$ls_denrot."','".$ls_emprot."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar la rotulacion.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en las Rotulaciones.\r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  saf_rotulacion Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino saf_rotulacion Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_rotulacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_activo()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_activo
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 28/12/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$li_total_select= 0;
		$li_total_insert= 0;
		$ls_sql="SELECT codemp, codact, denact, maract, modact, fecregact, feccmpact, codrot, codconbie, codgru, codsubgru,".
				"       codsec, spg_cuenta_act, spg_cuenta_dep, sc_cuenta, esttipinm, codmetdep, catalogo, costo, cossal,".
				"       vidautil, estdepact, obsact, fotact, codpai, codest, codmun, cod_pro, nompro, ced_bene, numordcom,".
				"       monordcom, codfuefin, numsolpag, fecemisol, codsitcon, codban, ctaban, codtipcta, codconcom, tippag,".
				"       numregpag, numconman, codproman, feciniman, fecfinman, rifase, numpolase, percobase, moncobase, fecvigase,".
				"       codprorot, fecrot, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5".
				"  FROM saf_activo ";
		$io_recordset= $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el activo.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp= $this->io_validacion->uf_valida_texto($row["codemp"],0,4,"");
				$ls_codact= $this->io_validacion->uf_valida_texto($row["codact"],0,15,"");
				$ls_denact= $this->io_validacion->uf_valida_texto($row["denact"],0,254,"");
				$ls_maract= $this->io_validacion->uf_valida_texto($row["maract"],0,100,"");
				$ls_modact= $this->io_validacion->uf_valida_texto($row["modact"],0,100,"");
				$ld_fecregact= $this->io_validacion->uf_valida_fecha($row["fecregact"],"1900-01-01");
				$ld_feccmpact= $this->io_validacion->uf_valida_fecha($row["feccmpact"],"1900-01-01");
				$ls_codrot= $this->io_validacion->uf_valida_texto($row["codrot"],0,1,"1");
				$ls_codconbie= $this->io_validacion->uf_valida_texto($row["codconbie"],0,1,"1");
				$ls_spg_cuenta_act= $this->io_validacion->uf_valida_texto($row["spg_cuenta_act"],0,58,"");
				$ls_spg_cuenta_dep= $this->io_validacion->uf_valida_texto($row["spg_cuenta_dep"],0,58,"");
				$ls_sc_cuenta= $this->io_validacion->uf_valida_texto($row["sc_cuenta"],0,25,"");
				$ls_esttipinm= $this->io_validacion->uf_valida_monto($row["esttipinm"],0);
				$ls_codmetdep= $this->io_validacion->uf_valida_texto($row["codmetdep"],0,3,"---");
				$ls_catalogo= $this->io_validacion->uf_valida_texto($row["catalogo"],0,15,"---------------");
				$li_costo= $this->io_rcbsf->uf_convertir_monedabsf($row["costo"],$this->li_candeccon,$this->li_tipconmon,1000,$this->li_redconmon);
				$li_cossal= $this->io_rcbsf->uf_convertir_monedabsf($row["cossal"],$this->li_candeccon,$this->li_tipconmon,1000,$this->li_redconmon);
				$li_vidautil= $this->io_validacion->uf_valida_monto($row["vidautil"],0);
				$ls_estdepact= $this->io_validacion->uf_valida_monto($row["estdepact"],0);
				$ls_obsact= $this->io_validacion->uf_valida_texto($row["obsact"],0,3,"");
				$ls_fotact= $row["fotact"];
				$ls_codpai= $this->io_validacion->uf_valida_texto($row["codpai"],0,3,"");
				$ls_codest= $this->io_validacion->uf_valida_texto($row["codest"],0,3,"");
				$ls_codmun= $this->io_validacion->uf_valida_texto($row["codmun"],0,3,"");
				$ls_cod_pro= $this->io_validacion->uf_valida_texto($row["cod_pro"],0,10,"");
				$ls_nompro= $this->io_validacion->uf_valida_texto($row["nompro"],0,100,"");
				$ls_ced_bene= $this->io_validacion->uf_valida_texto($row["ced_bene"],0,10,"");
				$ls_numordcom= $this->io_validacion->uf_valida_texto($row["numordcom"],0,15,"");
				$li_monordcom= $this->io_rcbsf->uf_convertir_monedabsf($row["monordcom"],$this->li_candeccon,$this->li_tipconmon,1000,$this->li_redconmon);
				$ls_codfuefin= $this->io_validacion->uf_valida_texto($row["codfuefin"],0,2,"");
				$ls_numsolpag= $this->io_validacion->uf_valida_texto($row["numsolpag"],0,15,"");
				$ld_fecemisol= $this->io_validacion->uf_valida_fecha($row["fecemisol"],"1900-01-01");
				$ls_codsitcon= $this->io_validacion->uf_valida_texto($row["codsitcon"],0,1,"");
				$ls_codban= $this->io_validacion->uf_valida_texto($row["codban"],0,3,"");
				$ls_ctaban= $this->io_validacion->uf_valida_texto($row["ctaban"],0,25,"");
				$ls_codtipcta= $this->io_validacion->uf_valida_texto($row["codtipcta"],0,3,"");
				$ls_codconcom= $this->io_validacion->uf_valida_texto($row["codconcom"],0,2,"");
				$ls_tippag= $this->io_validacion->uf_valida_texto($row["tippag"],0,1,"");
				$ls_numregpag= $this->io_validacion->uf_valida_texto($row["numregpag"],0,25,"");
				$ls_numconman= $this->io_validacion->uf_valida_texto($row["numconman"],0,25,"");
				$ls_codproman= $this->io_validacion->uf_valida_texto($row["codproman"],0,10,"");
				$ld_feciniman= $this->io_validacion->uf_valida_fecha($row["feciniman"],"1900-01-01");
				$ld_fecfinman= $this->io_validacion->uf_valida_fecha($row["fecfinman"],"1900-01-01");
				$ls_rifase= $this->io_validacion->uf_valida_texto($row["rifase"],0,15,"");
				$ls_numpolase= $this->io_validacion->uf_valida_texto($row["numpolase"],0,25,"");
				$li_percobase= $this->io_validacion->uf_valida_monto($row["percobase"],0);
				$li_moncobase= $this->io_rcbsf->uf_convertir_monedabsf($row["monordcom"],$this->li_candeccon,$this->li_tipconmon,1000,$this->li_redconmon);
				$ld_fecvigase= $this->io_validacion->uf_valida_fecha($row["fecvigase"],"1900-01-01");
				$ls_codprorot= $this->io_validacion->uf_valida_texto($row["codprorot"],0,10,"");
				$ld_fecrot= $this->io_validacion->uf_valida_fecha($row["fecrot"],"1900-01-01");
				$ls_codestpro1= $this->io_validacion->uf_valida_texto($row["codestpro1"],0,22,"");
				$ls_codestpro2= $this->io_validacion->uf_valida_texto($row["codestpro2"],0,6,"");
				$ls_codestpro3= $this->io_validacion->uf_valida_texto($row["codestpro3"],0,3,"");
				$ls_codestpro4= $this->io_validacion->uf_valida_texto($row["codestpro4"],0,2,"");
				$ls_codestpro5= $this->io_validacion->uf_valida_texto($row["codestpro5"],0,2,"");
				$li_costoaux= $this->io_validacion->uf_valida_monto($row["costo"],0);
				$li_cossalaux= $this->io_validacion->uf_valida_monto($row["cossal"],0);
				$li_monordcomaux= $this->io_validacion->uf_valida_monto($row["monordcom"],0);
				$li_moncobaseaux= $this->io_validacion->uf_valida_monto($row["moncobase"],0);
				if($ls_codpai=="---")
				{
					$ls_codest= "---";
					$ls_codmun= "---";
				}
				if($ls_codest=="---")
				{
					$ls_codmun= "---";
				}
				$this->uf_load_codigo_pais(&$ls_codpai);
				$this->uf_load_codigo_estado($ls_codpai,&$ls_codest);
				$this->uf_load_codigo_municipio($ls_codpai,$ls_codest,&$ls_codmun);
				if($ls_codact!="")
				{
					$ls_sql="INSERT INTO saf_activo(codemp, codact, denact, maract, modact, fecregact, feccmpact, codrot,".
							"                       codconbie, spg_cuenta_act, spg_cuenta_dep,".
							"                       sc_cuenta, esttipinm, codmetdep, catalogo, costo, cossal, vidautil,".
							"                       estdepact, obsact, fotact, codpai, codest, codmun, cod_pro, nompro,".
							"                       ced_bene, numordcom, monordcom, codfuefin, numsolpag, fecemisol, codsitcon,".
							"                       codban, ctaban, codtipcta, codconcom, tippag, numregpag, numconman,".
							"                       codproman, feciniman, fecfinman, rifase, numpolase, percobase, moncobase,".
							"                       fecvigase, codprorot, fecrot, codestpro1, codestpro2, codestpro3, codestpro4,".
							"                       codestpro5, costoaux, cossalaux, monordcomaux, moncobaseaux)".
							"	  VALUES ('".$ls_codemp."','".$ls_codact."','".$ls_denact."','".$ls_maract."','".$ls_modact."',".
							"             '".$ld_fecregact."','".$ld_feccmpact."','".$ls_codrot."','".$ls_codconbie."',".
							"             '".$ls_spg_cuenta_act."',".
							"             '".$ls_spg_cuenta_dep."','".$ls_sc_cuenta."','".$ls_esttipinm."','".$ls_codmetdep."',".
							"             '".$ls_catalogo."',".$li_costo.",".$li_cossal.",".$li_vidautil.",'".$ls_estdepact."',".
							"             '".$ls_obsact."','".$ls_fotact."','".$ls_codpai."','".$ls_codest."','".$ls_codmun."',".
							"             '".$ls_cod_pro."','".$ls_nompro."','".$ls_ced_bene."','".$ls_numordcom."',".$li_monordcom.",".
							"             '".$ls_codfuefin."','".$ls_numsolpag."','".$ld_fecemisol."','".$ls_codsitcon."',".
							"             '".$ls_codban."','".$ls_ctaban."','".$ls_codtipcta."','".$ls_codconcom."',".
							"             '".$ls_tippag."','".$ls_numregpag."','".$ls_numconman."','".$ls_codproman."',".
							"             '".$ld_feciniman."','".$ld_fecfinman."','".$ls_rifase."','".$ls_numpolase."',".
							"             ".$li_percobase.",".$li_moncobase.",'".$ld_fecvigase."','".$ls_codprorot."',".
							"             '".$ld_fecrot."','".$ls_codestpro1."','".$ls_codestpro2."','".$ls_codestpro3."',".
							"             '".$ls_codestpro4."','".$ls_codestpro5."',".$li_costoaux.",".$li_cossalaux.",".
							"             ".$li_monordcomaux.",".$li_moncobaseaux.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el activo.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los Activos.\r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  saf_activo Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino saf_activo Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_activo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_dta()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_pais
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 28/12/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$li_total_select= 0;
		$li_total_insert= 0;
		$ls_sql="SELECT codemp, codact, ideact, coduniadm, seract, idchapa, codres, fecincact, fecdesact, fecajuact, obsideact,".
				"       tipcau, codusureg, estact, estcon, codrespri".
				"  FROM saf_dta ";
		$io_recordset= $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el detalle del Activo .\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp= $this->io_validacion->uf_valida_texto($row["codemp"],0,4,"");
				$ls_codact= $this->io_validacion->uf_valida_texto($row["codact"],0,15,"");
				$ls_ideact= $this->io_validacion->uf_valida_texto($row["ideact"],0,15,"");
				$ls_coduniadm= $this->io_validacion->uf_valida_texto($row["coduniadm"],0,10,"");
				$ls_seract= $this->io_validacion->uf_valida_texto($row["seract"],0,20,"");
				$ls_idchapa= $this->io_validacion->uf_valida_texto($row["idchapa"],0,15,"");
				$ls_codres= $this->io_validacion->uf_valida_texto($row["codres"],0,10,"");
				$ld_fecincact= $this->io_validacion->uf_valida_fecha($row["fecincact"],"1900-01-01");
				$ld_fecdesact= $this->io_validacion->uf_valida_fecha($row["fecdesact"],"1900-01-01");
				$ld_fecajuact= $this->io_validacion->uf_valida_fecha($row["fecajuact"],"1900-01-01");
				$ls_obsideact= $this->io_validacion->uf_valida_texto($row["obsideact"],0,255,"");
				$ls_tipcau= $this->io_validacion->uf_valida_texto($row["tipcau"],0,1,"");
				$ls_codusureg= $this->io_validacion->uf_valida_texto($row["codusureg"],0,50,"");
				$ls_estact= $this->io_validacion->uf_valida_texto($row["estact"],0,1,"");
				$ls_estcon= $this->io_validacion->uf_valida_monto($row["estcon"],0);
				$ls_codrespri= $this->io_validacion->uf_valida_texto($row["codrespri"],0,10,"");
				if($ls_codact!="")
				{
					$ls_sql="INSERT INTO saf_dta(codemp, codact, ideact, coduniadm, seract, idchapa, codres, fecincact, fecdesact,".
							"                    fecajuact, obsideact, tipcau, codusureg, estact, estcon, codrespri)".
							"	  VALUES ('".$ls_codemp."','".$ls_codact."','".$ls_ideact."','".$ls_coduniadm."','".$ls_seract."',".
							"             '".$ls_idchapa."','".$ls_codres."','".$ld_fecincact."','".$ld_fecdesact."',".
							"             '".$ld_fecajuact."','".$ls_obsideact."','".$ls_tipcau."','".$ls_codusureg."',".
							"             '".$ls_estact."','".$ls_estcon."','".$ls_codrespri."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el detalle del Activo.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los Detalles de Activos.\r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  saf_dta Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino saf_dta Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_dta
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_depreciacion()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_depreciacion
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 28/12/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$li_total_select= 0;
		$li_total_insert= 0;
		$ls_sql="SELECT codemp, codact, ideact, fecdep, mondepmen, mondepano, mondepacu, estcon, fechaconta, fechaanula".
				"  FROM saf_depreciacion ";
		$io_recordset= $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar la depreciacion.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp= $this->io_validacion->uf_valida_texto($row["codemp"],0,4,"");
				$ls_codact = $this->io_validacion->uf_valida_texto($row["codact"],0,15,"");
				$ls_ideact = $this->io_validacion->uf_valida_texto($row["ideact"],0,15,"");
				$ld_fecdep=$this->io_validacion->uf_valida_fecha($row["fecdep"],"1950-01-01");
				$li_mondepmen= $this->io_rcbsf->uf_convertir_monedabsf($row["mondepmen"],$this->li_candeccon,$this->li_tipconmon,1000,$this->li_redconmon);
				$li_mondepano= $this->io_rcbsf->uf_convertir_monedabsf($row["mondepano"],$this->li_candeccon,$this->li_tipconmon,1000,$this->li_redconmon);
				$li_mondepacu= $this->io_rcbsf->uf_convertir_monedabsf($row["mondepacu"],$this->li_candeccon,$this->li_tipconmon,1000,$this->li_redconmon);
				$ls_estcon= $this->io_validacion->uf_valida_monto($row["estcon"],0);
				$li_mondepmenaux= $this->io_rcbsf->uf_convertir_monedabsf($row["mondepmenaux"],$this->li_candeccon,0,1000,$this->li_redconmon);
				$li_mondepanoaux= $this->io_rcbsf->uf_convertir_monedabsf($row["mondepanoaux"],$this->li_candeccon,0,1000,$this->li_redconmon);
				$li_mondepacuaux= $this->io_rcbsf->uf_convertir_monedabsf($row["mondepacuaux"],$this->li_candeccon,0,1000,$this->li_redconmon);
				$ld_fechaconta=$this->io_validacion->uf_valida_fecha($row["fechaconta"],"1950-01-01");
				$ld_fechaanula=$this->io_validacion->uf_valida_fecha($row["fechaanula"],"1950-01-01");
				if($ls_codact!="")
				{
					$ls_sql="INSERT INTO saf_depreciacion(codemp, codact, ideact, fecdep, mondepmen, mondepano, mondepacu,".
							"                             estcon, mondepmenaux, mondepanoaux, mondepacuaux, fechaconta, fechaanula)".
							"	  VALUES ('".$ls_codemp."','".$ls_codact."','".$ls_ideact."','".$ld_fecdep."',".$li_mondepmen.",".
							"             ".$li_mondepano.",".$li_mondepacu.",".$ls_estcon.",".$li_mondepmenaux.",".
							"            ".$li_mondepanoaux.",".$li_mondepacuaux.",'".$ld_fechaconta."','".$ld_fechaanula."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar la depreciacion.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en las Depreciaciones.\r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  saf_depreciacion Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino saf_depreciacion Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_depreciacion
	//-----------------------------------------------------------------------------------------------------------------------------------



function uf_copiar_tabla($as_database_source,$as_database_target,$as_table,$as_fields,$as_key_field,$as_comment)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_copiar_tabla
//		   Access: private
//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	  Description: Función que selecciona la data de $as_database_source (base de datos origen) y los inserta en $as_dabatase_target (base de datos destino)
//	   Creado Por: 
// Fecha Creación: 20/11/2006 								Fecha Última Modificación : 	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		
		$ls_sql_source = "SELECT COUNT(*) AS norigen FROM ".$as_database_source.".".$as_table." ";
		$ls_sql_target = "SELECT COUNT(*) AS destino FROM ".$as_database_target.".".$as_table." ";
		
		$ls_sql =	"INSERT INTO ".$as_database_target.".".$as_table." (".$as_fields.") ".
					"SELECT ".$as_fields." ".
					"FROM ".$as_database_source.".".$as_table." ".
					"WHERE 	".$as_key_field." NOT IN (SELECT ".$as_key_field." ".
					" 		FROM ".$as_database_target.".".$as_table.") ";				
	
		$io_recordset=$this->io_sql->Execute($ls_sql);
		print $ls_sql."<br>";
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Problema al Copiar ".$as_comment.".\r\n".$this->io_sql->message."\r\n";
			$ls_cadena=$ls_cadena.$ls_sql."\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}
		else
		{			
			$ls_cadena=			  "//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  ".$as_table." Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino ".$as_table." Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_copiar_especialidad



function ue_limpiar_saf_basico()
{
	$lb_valido=true;
	$this->io_sql_destino->begin_transaction();
	//------------------------------------ Borrar tablas de saf -----------------------------------------
	if($lb_valido)	
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('saf_depreciacion',"  ");			
		}			
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('saf_dta',"  ");			
		}
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('saf_activo',"  ");			
		}
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('saf_catalogo',"  ");			
		}	
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('saf_seccion',"  ");			
		}
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('saf_subgrupo',"  ");			
		}
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('saf_grupo',"  ");			
		}
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('saf_situacioncontable',"  ");			
		}		
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('saf_causas',"  ");			
		}		
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('saf_condicioncompra',"  ");			
		}	
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('saf_conservacionbien',"  ");			
		}	
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('saf_rotulacion',"  ");			
		}	
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('saf_metodo',"  ");			
		}	
					
	if($lb_valido)	
	{	
		$this->io_mensajes->message("La data de Activos se borró correctamente.");
		$ls_cadena="La data de Activos se borró correctamente.\r\n";
		if ($this->lo_archivo)			
		{
			@fwrite($this->lo_archivo,$ls_cadena);
		}
	}
	else
	{
		$this->io_mensajes->message("Ocurrió un error al copiar la data de Activos. Verifique el archivo txt."); 
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
}


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
		$io_recordset=$this->io_sql_destino->Execute($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Borrar la tabla".$as_tabla.".\r\n".$this->io_sql->message." \r\n";
			$ls_cadena=$ls_cadena.$ls_sql."\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}
		else
		{
			//$io_recordset->Close();
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
	function uf_load_codigo_pais(&$as_codpai)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_codigo_pais
		//		   Access: public
		//		 Argumens: as_despai // Descripción de Pais
		//		 		   as_codpai // Código de Pais
		//	      Returns: lb_valido True si se ejecuto el select ó False si hubo error en el select
		//	  Description: Función que se encarga de obtener el código de un pais ded acuerdo a la descripción
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 02/10/2007								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_total=0;
		$ls_sql="SELECT codpai ".
				"  FROM sigesp_pais ".
				" WHERE codpai ='".$as_codpai."'";
		$io_recordset= $this->io_sql_destino->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el documento.\r\n".$this->io_sql_destino->message."\r\n";
			$ls_cadena=$ls_cadena.$ls_sql."\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}

		else
		{
			if(!($row=$this->io_sql_destino->fetch_row($io_recordset)))
			{
				$as_codpai= "---"; 
			}
		}
		return $lb_valido;
	}// end function uf_load_codigo_pais.
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_codigo_estado($as_codpai,&$as_codest)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_codigo_estado
		//		   Access: public
		//		 Argumens: as_desest // Descripción del Estado
		//		 		   as_codpai // Código de Pais
		//		 		   as_codest // Código del Estado
		//	      Returns: lb_valido True si se ejecuto el select ó False si hubo error en el select
		//	  Description: Función que se encarga de obtener el Código del Estado según su pais y descripción
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 02/10/2007								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_total=0; 
		$ls_sql="SELECT codest ".
				"  FROM sigesp_estados ".
				" WHERE codpai = '".$as_codpai."' ".
				"   AND codest = '".$as_codest."'";
		$io_recordset= $this->io_sql_destino->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el documento.\r\n".$this->io_sql_destino->message."\r\n";
			$ls_cadena=$ls_cadena.$ls_sql."\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}

		else
		{
			if(!($row=$this->io_sql_destino->fetch_row($io_recordset)))
			{
				$as_codest= "---"; 
			}
		}
		return $lb_valido;
	}// end function uf_load_codigo_estado.
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_codigo_municipio($as_codpai,$as_codest,&$as_codmun)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_codigo_municipios
		//		   Access: public
		//		 Argumens: as_denmun // Descripción del Municipio
		//		 		   as_codpai // Código de Pais
		//		 		   as_codest // Código del Estado
		//		 		   as_codmun // Código del Municipio
		//	      Returns: lb_valido True si se ejecuto el select ó False si hubo error en el select
		//	  Description: Función que se encarga de obtener el municipio según su pais y estado
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 02/10/2007								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_total=0; 
		
		$ls_sql="SELECT codmun ".
				"  FROM sigesp_municipio ".
				" WHERE codpai = '".$as_codpai."' ".
				"   AND codest = '".$as_codest."'".
				"   AND codmun = '".$as_codmun."'";
		$io_recordset= $this->io_sql_destino->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el documento.\r\n".$this->io_sql_destino->message."\r\n";
			$ls_cadena=$ls_cadena.$ls_sql."\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}
		else
		{
			if(!($row=$this->io_sql_destino->fetch_row($io_recordset)))
			{
				$as_codmun= "---"; 
			}
		}
		return $lb_valido;
	}// end function uf_load_codigo_municipios.
	//-----------------------------------------------------------------------------------------------------------------------------------

function uf_insert_metodo_default()
{
  $lb_valido =  true;
  $ls_sql="SELECT codmetdep FROM saf_metodo WHERE codmetdep='---'"; 
  $io_recordset= $this->io_sql_destino->select($ls_sql);
  if ($io_recordset===false)
	 { 
	   $lb_valido=false;
	   $ls_cadena="Error al Seleccionar el metodo de Depreciacion.\r\n".$this->io_sql_destino->message."\r\n";
	   $ls_cadena=$ls_cadena.$ls_sql."\r\n";
	   if ($this->lo_archivo)			
		  {
		    @fwrite($this->lo_archivo,$ls_cadena);
		  }
	 }
  else
	{
	  if (!($row=$this->io_sql_destino->fetch_row($io_recordset)))
		 {
		   $ls_sql = "INSERT INTO saf_metodo (codmetdep, denmetdep, formetdep) VALUES ('---','Sin definir','')";
		   $io_recordset= $this->io_sql_destino->execute($ls_sql);
		   if ($io_recordset===false)
		      { 
			    $lb_valido=false;
			    $ls_cadena="Error al Insertar Metodo de Depreciacion.\r\n".$this->io_sql_destino->message."\r\n";
			    $ls_cadena=$ls_cadena.$ls_sql."\r\n";
			    if ($this->lo_archivo)			
				   {
				     @fwrite($this->lo_archivo,$ls_cadena);
				   }
		      }
	      }
    }
  return $lb_valido;
}
}
?>