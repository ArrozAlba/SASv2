<?php 
///////////////////////////////////////////////////////////////////////////////////////////////////////
//       Class : sigesp_copia_rpc.php                                                 			  //    
// Description : Procesa la copia de datos del modulo de nomina										  //
////////////////////////////////////////////////////////////////////////////////////////////////////////

class sigesp_copia_rpc {

	var $io_sql_origen;
	var $io_sql_destino;
	var $io_mensajes;
	var $io_funciones;
	var $io_validacion;
	var	$lo_archivo;
	var $ls_database_source;
	var $ls_database_target;

	
function sigesp_copia_rpc()
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
	$ls_nombrearchivo="resultado/".$_SESSION["ls_data_des"]."_rpc_result_".$ld_fecha.".txt";
	$this->lo_archivo=@fopen("$ls_nombrearchivo","a+");
	$this->io_rcbsf			  = new sigesp_c_reconvertir_monedabsf(); 
	$this->li_candeccon= 4;
	$this->li_tipconmon= 1;
	$this->li_redconmon=1;
}


function ue_copiar_rpc_basico()
{
	$lb_valido=true;
	$this->io_sql_destino->begin_transaction();
	//------------------------------------ Insertar datos básicos de rpc -----------------------------------------
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_clasificacion();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_documentos();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_especialidad();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_tipoorganizacion();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_pais();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_estado();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_municipio();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_parroquia();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_proveedor();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_proveedorsocios();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_beneficiario();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_especialidadxproveedor();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_documentosxproveedor();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_clasificacionxproveedor();
		}
	if($lb_valido)
	{	
		$this->io_mensajes->message("La data de Proveedores y Beneficiarios se copió correctamente.");
		$ls_cadena="La data de Proveedores y Beneficiarios se copió correctamente.\r\n";
		if ($this->lo_archivo)			
		{
			@fwrite($this->lo_archivo,$ls_cadena);
		}
	}
	else
	{
		$this->io_mensajes->message("Ocurrió un error al copiar la data de Proveedores y Beneficiarios. Verifique el archivo txt."); 
	}
	if ($lb_valido)
		{
			$this->io_validacion->uf_insert_sistema_apertura('RPC');
			$this->io_sql_destino->commit();
		}
	else
		{
			$this->io_sql_destino->rollback();	
		}	
	return $lb_valido;			
}


	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_clasificacion()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_clasificacion
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 28/12/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$li_total_select= 0;
		$li_total_insert= 0;
		$ls_sql="SELECT codemp, codclas, denclas".
				"  FROM rpc_clasificacion ";
		$io_recordset= $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar la clasificacion.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp= $row["codemp"]; 
				$ls_codclas= $this->io_validacion->uf_valida_texto($row["codclas"],0,2,"");
				$ls_denclas = $this->io_validacion->uf_valida_texto($row["denclas"],0,60,"");
				if($ls_codclas!="")
				{
					$ls_sql="INSERT INTO rpc_clasificacion(codemp, codclas, denclas)".
							"	  VALUES ('".$ls_codemp."','".$ls_codclas."','".$ls_denclas."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar la profesión.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en las Clasificaciones.\r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  rpc_clasificacion Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino rpc_clasificacion Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_clasificacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_documentos()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_documentos
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 28/12/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$li_total_select= 0;
		$li_total_insert= 0;
		$ls_sql="SELECT codemp, coddoc, dendoc".
				"  FROM rpc_documentos ";
		$io_recordset= $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el documento.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp= $row["codemp"]; 
				$ls_coddoc= $this->io_validacion->uf_valida_texto($row["coddoc"],0,3,"");
				$ls_dendoc = $this->io_validacion->uf_valida_texto($row["dendoc"],0,254,"");
				if($ls_coddoc!="")
				{
					$ls_sql="INSERT INTO rpc_documentos(codemp, coddoc, dendoc)".
							"	  VALUES ('".$ls_codemp."','".$ls_coddoc."','".$ls_dendoc."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el Documento.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los Documentos.\r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  rpc_documentos Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino rpc_documentos Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_documentos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_especialidad()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_especialidad
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 28/12/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$li_total_select= 0;
		$li_total_insert= 0;
		$ls_sql="SELECT codesp, denesp".
				"  FROM rpc_especialidad ";
		$io_recordset= $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar la especialidad.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codesp= $this->io_validacion->uf_valida_texto($row["codesp"],0,3,"");
				$ls_denesp = $this->io_validacion->uf_valida_texto($row["denesp"],0,254,"");
				if($ls_codesp!="")
				{
					$ls_sql="INSERT INTO rpc_especialidad(codesp, denesp)".
							"	  VALUES ('".$ls_codesp."','".$ls_denesp."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar la especialidad.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en las Especialidades.\r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  rpc_especialidad Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino rpc_especialidad Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_documentos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_tipoorganizacion()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_tipoorganizacion
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 28/12/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$li_total_select= 0;
		$li_total_insert= 0;
		$ls_sql="SELECT codtipoorg, dentipoorg".
				"  FROM rpc_tipo_organizacion ";
		$io_recordset= $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el tipo de organizacion.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codtipoorg= $this->io_validacion->uf_valida_texto($row["codtipoorg"],0,2,"");
				$ls_dentipoorg = $this->io_validacion->uf_valida_texto($row["dentipoorg"],0,254,"");
				if($ls_codtipoorg!="")
				{
					$ls_sql="INSERT INTO rpc_tipo_organizacion(codtipoorg, dentipoorg)".
							"	  VALUES ('".$ls_codtipoorg."','".$ls_dentipoorg."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el tipo de organizacion.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los Tipos de Organizacion.\r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  rpc_tipo_organizacion Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino rpc_tipo_organizacion Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_documentos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_pais()
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
		$ls_sql="SELECT codpai, despai".
				"  FROM sigesp_pais ";
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
				$ls_codpai= $this->io_validacion->uf_valida_texto($row["codpai"],0,3,"");
				$ls_despai = $this->io_validacion->uf_valida_texto($row["despai"],0,50,"");
				if($ls_codpai!="")
				{
					$ls_sql="INSERT INTO sigesp_pais(codpai, despai)".
							"	  VALUES ('".$ls_codpai."','".$ls_despai."')";
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
			$ls_cadena=$ls_cadena."   Tabla Origen  sigesp_pais Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sigesp_pais Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_pais
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_estado()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_estado
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 28/12/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$li_total_select= 0;
		$li_total_insert= 0;
		$ls_sql="SELECT codpai, codest, desest".
				"  FROM sigesp_estados ";
		$io_recordset= $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el estado.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codpai= $this->io_validacion->uf_valida_texto($row["codpai"],0,3,"");
				$ls_codest= $this->io_validacion->uf_valida_texto($row["codest"],0,3,"");
				$ls_desest = $this->io_validacion->uf_valida_texto($row["desest"],0,50,"");
				if($ls_codest!="")
				{
					$ls_sql="INSERT INTO sigesp_estados(codpai, codest, desest)".
							"	  VALUES ('".$ls_codpai."','".$ls_codest."','".$ls_desest."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el estado.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los Estados.\r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_sql="INSERT INTO sigesp_estados(codpai, codest, desest) ".
					"	SELECT codpai, '---', 'por defecto' ".
					"	  FROM sigesp_pais ".
					"	 WHERE codpai<>'---'".
					"      AND codpai NOT IN (SELECT codpai".
					"						    FROM sigesp_estados".
					"                          WHERE sigesp_pais.codpai=sigesp_estados.codpai".
					"                            AND codest='---')".
					"	 GROUP BY codpai ";
			$li_row=$this->io_sql_destino->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$ls_cadena="Error al Insertar el estado.\r\n".$this->io_sql_destino->message."\r\n";
				$ls_cadena=$ls_cadena.$ls_sql."\r\n";
				if ($this->lo_archivo)			
				{
					@fwrite($this->lo_archivo,$ls_cadena);
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sigesp_estados Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sigesp_estados Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_estado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_municipio()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_municipio
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 28/12/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$li_total_select= 0;
		$li_total_insert= 0;
		$ls_sql="SELECT codpai, codest, codmun, denmun".
				"  FROM sigesp_municipio ";
		$io_recordset= $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el municipio.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codpai= $this->io_validacion->uf_valida_texto($row["codpai"],0,3,"");
				$ls_codest= $this->io_validacion->uf_valida_texto($row["codest"],0,3,"");
				$ls_codmun= $this->io_validacion->uf_valida_texto($row["codmun"],0,3,"");
				$ls_denmun = $this->io_validacion->uf_valida_texto($row["denmun"],0,50,"");
				if($ls_codmun!="")
				{
					$ls_sql="INSERT INTO sigesp_municipio(codpai, codest, codmun, denmun)".
							"	  VALUES ('".$ls_codpai."','".$ls_codest."','".$ls_codmun."','".$ls_denmun."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el municipio.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los Municipios.\r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_sql="INSERT INTO sigesp_municipio(codpai, codest, codmun, denmun) ".
					"	SELECT codpai, codest, '---', 'por defecto' ".
					"	  FROM sigesp_estados ".
					"	 WHERE codpai<>'---'".
					"      AND codpai NOT IN (SELECT codpai".
					"						    FROM sigesp_municipio".
					"                          WHERE sigesp_estados.codpai=sigesp_municipio.codpai".
					"                            AND sigesp_estados.codest=sigesp_municipio.codest".
					"                            AND codmun='---')".
					"	 GROUP BY codpai, codest ";
			$li_row=$this->io_sql_destino->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$ls_cadena="Error al Insertar el municipio.\r\n".$this->io_sql_destino->message."\r\n";
				$ls_cadena=$ls_cadena.$ls_sql."\r\n";
				if ($this->lo_archivo)			
				{
					@fwrite($this->lo_archivo,$ls_cadena);
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sigesp_municipio Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sigesp_municipio Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_estado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_parroquia()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_parroquia
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 28/12/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$li_total_select= 0;
		$li_total_insert= 0;
		$ls_sql="SELECT codpai, codest, codmun, codpar, denpar".
				"  FROM sigesp_parroquia ";
		$io_recordset= $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar la parroquia.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codpai= $this->io_validacion->uf_valida_texto($row["codpai"],0,3,"");
				$ls_codest= $this->io_validacion->uf_valida_texto($row["codest"],0,3,"");
				$ls_codmun= $this->io_validacion->uf_valida_texto($row["codmun"],0,3,"");
				$ls_codpar= $this->io_validacion->uf_valida_texto($row["codpar"],0,3,"");
				$ls_denpar = $this->io_validacion->uf_valida_texto($row["denpar"],0,50,"");
				if($ls_codpar!="")
				{
					$ls_sql="INSERT INTO sigesp_parroquia(codpai, codest, codmun, codpar, denpar)".
							"	  VALUES ('".$ls_codpai."','".$ls_codest."','".$ls_codmun."','".$ls_codpar."','".$ls_denpar."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar la parroquias.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en las Parroquias.\r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_sql="INSERT INTO sigesp_parroquia(codpai, codest, codmun, codpar, denpar) ".
					"	SELECT codpai, codest, codmun, '---', 'por defecto' ".
					"	  FROM sigesp_municipio ".
					"	 WHERE codpai<>'---'".
					"      AND codpai NOT IN (SELECT codpai".
					"						    FROM sigesp_parroquia".
					"                          WHERE sigesp_municipio.codpai=sigesp_parroquia.codpai".
					"                            AND sigesp_municipio.codest=sigesp_parroquia.codest".
					"                            AND sigesp_municipio.codmun=sigesp_parroquia.codmun".
					"                            AND codpar='---')".
					"	 GROUP BY codpai, codest, codmun ";
			$li_row=$this->io_sql_destino->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$ls_cadena="Error al Insertar la parroquia .\r\n".$this->io_sql_destino->message."\r\n";
				$ls_cadena=$ls_cadena.$ls_sql."\r\n";
				if ($this->lo_archivo)			
				{
					@fwrite($this->lo_archivo,$ls_cadena);
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sigesp_parroquia Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sigesp_parroquia Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_parroquia
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_proveedor()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_proveedor
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 28/12/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$li_total_select= 0;
		$li_total_insert= 0;
		$ls_sql="SELECT codemp, cod_pro, nompro, dirpro, telpro, faxpro, nacpro, rifpro, nitpro, fecreg, capital, sc_cuenta,".
				"       obspro, estpro, estcon, estaso, ocei_fec_reg, ocei_no_reg, monmax, cedrep, nomreppro, emailrep, carrep,".
				"       registro, nro_reg, tomo_reg, folreg, fecregmod, regmod, nummod, tommod, folmod, inspector, foto,".
				"       codbansig, codban, codmon, codtipoorg, codesp, ctaban, numlic, fecvenrnc, numregsso, fecvensso,".
				"       numregince, fecvenince, estprov, pagweb, email, codpai, codest, codmun, codpar, graemp, tipconpro ".
				"  FROM rpc_proveedor ";
		$io_recordset= $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el tipo de organizacion.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_cod_pro= $this->io_validacion->uf_valida_texto($row["cod_pro"],0,10,"");
				$ls_nompro= $this->io_validacion->uf_valida_texto($row["nompro"],0,100,"");
				$ls_dirpro= $this->io_validacion->uf_valida_texto($row["dirpro"],0,254,"");
				$ls_telpro= $this->io_validacion->uf_valida_texto($row["telpro"],0,50,"");
				$ls_faxpro= $this->io_validacion->uf_valida_texto($row["faxpro"],0,30,"");
				$ls_nacpro= $this->io_validacion->uf_valida_texto($row["nacpro"],0,1,"");
				$ls_rifpro= $this->io_validacion->uf_valida_texto($row["rifpro"],0,15,"");
				$ls_nitpro= $this->io_validacion->uf_valida_texto($row["nitpro"],0,15,"");
				$ld_fecreg= $this->io_validacion->uf_valida_fecha($row["fecreg"],"1900-01-01");
				$li_capital= $this->io_rcbsf->uf_convertir_monedabsf($row["capital"],$this->li_candeccon,$this->li_tipconmon,1000,$this->li_redconmon);
				$ls_sc_cuenta= $this->io_validacion->uf_valida_texto($row["sc_cuenta"],0,25,"");
				$ls_obspro= $this->io_validacion->uf_valida_texto($row["obspro"],0,8000,"");
				$ls_estpro= $this->io_validacion->uf_valida_monto($row["estpro"],0);
				$ls_estcon= $this->io_validacion->uf_valida_monto($row["estcon"],0); 
				$ls_estaso= $this->io_validacion->uf_valida_monto($row["estaso"],0);
				$ld_ocei_fec_reg= $this->io_validacion->uf_valida_fecha($row["ocei_fec_reg"],"1900-01-01");
				$ls_ocei_no_reg= $this->io_validacion->uf_valida_texto($row["ocei_no_reg"],0,17,"");
				$li_monmax= $this->io_rcbsf->uf_convertir_monedabsf($row["monmax"],$this->li_candeccon,$this->li_tipconmon,1000,$this->li_redconmon);
				$ls_cedrep= $this->io_validacion->uf_valida_texto($row["cedrep"],0,10,"");
				$ls_nomreppro= $this->io_validacion->uf_valida_texto($row["nomreppro"],0,50,"");
				$ls_emailrep= $this->io_validacion->uf_valida_texto($row["emailrep"],0,100,"");
				$ls_carrep= $this->io_validacion->uf_valida_texto($row["carrep"],0,35,"");
				$ls_registro= $this->io_validacion->uf_valida_texto($row["registro"],0,35,"");
				$ls_nro_reg= $this->io_validacion->uf_valida_texto($row["nro_reg"],0,15,"");
				$ls_tomo_reg= $this->io_validacion->uf_valida_texto($row["tomo_reg"],0,5,"");
				$ls_folreg= $this->io_validacion->uf_valida_texto($row["folreg"],0,5,"");
				$ld_fecregmod= $this->io_validacion->uf_valida_fecha($row["fecregmod"],"1900-01-01");
				$ls_regmod= $this->io_validacion->uf_valida_texto($row["regmod"],0,35,"");
				$ls_nummod= $this->io_validacion->uf_valida_texto($row["nummod"],0,15,"");
				$ls_tommod= $this->io_validacion->uf_valida_texto($row["tommod"],0,5,"");
				$ls_folmod= $this->io_validacion->uf_valida_texto($row["folmod"],0,5,"");
				$ls_inspector= $this->io_validacion->uf_valida_monto($row["estprov"],0);
				$ls_foto= $row["foto"];
				$ls_codbansig= $this->io_validacion->uf_valida_texto($row["codbansig"],0,3,"");
				$ls_codban= $this->io_validacion->uf_valida_texto($row["codban"],0,3,"");
				$ls_codmon= $this->io_validacion->uf_valida_texto($row["codmon"],0,3,"");
				$ls_codtipoorg= $this->io_validacion->uf_valida_texto($row["codtipoorg"],0,2,"--");
				$ls_codesp= $this->io_validacion->uf_valida_texto($row["codesp"],0,3,"");
				$ls_ctaban= $this->io_validacion->uf_valida_texto($row["ctaban"],0,25,"");
				$ls_numlic= $this->io_validacion->uf_valida_texto($row["numlic"],0,25,"");
				$ld_fecvenrnc= $this->io_validacion->uf_valida_fecha($row["fecvenrnc"],"1900-01-01");
				$ls_numregsso= $this->io_validacion->uf_valida_texto($row["numregsso"],0,15,"");
				$ld_fecvensso= $this->io_validacion->uf_valida_fecha($row["fecvensso"],"1900-01-01");
				$ls_numregince= $this->io_validacion->uf_valida_texto($row["numregince"],0,15,"");
				$ld_fecvenince= $this->io_validacion->uf_valida_fecha($row["fecvenince"],"1900-01-01");
				$ls_estprov= $this->io_validacion->uf_valida_monto($row["estprov"],0);
				$ls_pagweb= $this->io_validacion->uf_valida_texto($row["pagweb"],0,200,"");
				$ls_email= $this->io_validacion->uf_valida_texto($row["email"],0,200,"");
				$ls_codpai= $this->io_validacion->uf_valida_texto($row["codpai"],0,3,"---");
				$ls_codest= $this->io_validacion->uf_valida_texto($row["codest"],0,3,"---");
				$ls_codmun= $this->io_validacion->uf_valida_texto($row["codmun"],0,3,"---");
				$ls_codpar= $this->io_validacion->uf_valida_texto($row["codpar"],0,3,"---");
				if($ls_codpai=="---")
				{
					$ls_codest= "---";
					$ls_codmun= "---";
					$ls_codpar= "---";
				}
				if($ls_codest=="---")
				{
					$ls_codmun= "---";
					$ls_codpar= "---";
				}
				if($ls_codmun=="---")
				{
					$ls_codpar= "---";
				}
				$this->uf_load_codigo_pais(&$ls_codpai);
				$this->uf_load_codigo_estado($ls_codpai,&$ls_codest);
				$this->uf_load_codigo_municipio($ls_codpai,$ls_codest,&$ls_codmun);
				$this->uf_load_codigo_parroquia($ls_codpai,$ls_codest,$ls_codmun,&$ls_codpar);
				$ls_graemp= $this->io_validacion->uf_valida_texto($row["graemp"],0,4,"");
				$ls_tipconpro= $this->io_validacion->uf_valida_texto($row["tipconpro"],0,1,"");
				$li_capitalaux= $this->io_validacion->uf_valida_monto($row["capital"],0);
				$li_monmaxaux= $this->io_validacion->uf_valida_monto($row["monmax"],0);
				
				if($ls_codtipoorg!="")
				{
					$ls_sql="INSERT INTO rpc_proveedor(codemp, cod_pro, nompro, dirpro, telpro, faxpro, nacpro, rifpro, nitpro,".
							"                          fecreg, capital, sc_cuenta, obspro, estpro, estcon, estaso, ocei_fec_reg,".
							"                          ocei_no_reg, monmax, cedrep, nomreppro, emailrep, carrep, registro,".
							"                          nro_reg, tomo_reg, folreg, fecregmod, regmod, nummod, tommod, folmod,".
							"                          inspector, foto, codbansig, codban, codmon, codtipoorg, codesp, ctaban,".
							"                          numlic, fecvenrnc, numregsso, fecvensso, numregince, fecvenince, estprov,".
							"                          pagweb, email, codpai, codest, codmun, codpar, graemp, tipconpro, ".
							"                          capitalaux, monmaxaux)".
							"	  VALUES ('".$ls_codemp."','".$ls_cod_pro."','".$ls_nompro."','".$ls_dirpro."','".$ls_telpro."',".
							"             '".$ls_faxpro."','".$ls_nacpro."','".$ls_rifpro."','".$ls_nitpro."','".$ld_fecreg."',".
							"             ".$li_capital.",'".$ls_sc_cuenta."','".$ls_obspro."','".$ls_estpro."',".
							"             '".$ls_estcon."','".$ls_estaso."','".$ld_ocei_fec_reg."','".$ls_ocei_no_reg."',".
							"             ".$li_monmax.",'".$ls_cedrep."','".$ls_nomreppro."','".$ls_emailrep."','".$ls_carrep."',".
							"             '".$ls_registro."','".$ls_nro_reg."','".$ls_tomo_reg."','".$ls_folreg."',".
							"             '".$ld_fecregmod."','".$ls_regmod."','".$ls_nummod."','".$ls_tommod."','".$ls_folmod."',".
							"             '".$ls_inspector."','".$ls_foto."','".$ls_codbansig."','".$ls_codban."','".$ls_codmon."',".
							"             '".$ls_codtipoorg."','".$ls_codesp."','".$ls_ctaban."','".$ls_numlic."','".$ld_fecvenrnc."',".
							"             '".$ls_numregsso."','".$ld_fecvensso."','".$ls_numregince."','".$ld_fecvenince."',".
							"             '".$ls_estprov."','".$ls_pagweb."','".$ls_email."','".$ls_codpai."','".$ls_codest."',".
							"             '".$ls_codmun."','".$ls_codpar."','".$ls_graemp."','".$ls_tipconpro."',".
							"             ".$li_capitalaux.",".$li_monmaxaux.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el proveedor.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los Proveedores.\r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  rpc_proveedor Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino rpc_proveedor Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_proveedor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_proveedorsocios()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_proveedorsocios
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 28/12/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$li_total_select= 0;
		$li_total_insert= 0;
		$ls_sql="SELECT codemp, cod_pro, cedsocio, nomsocio, apesocio, carsocio, telsocio, dirsocio, email, foto".
				"  FROM rpc_proveedorsocios ";
		$io_recordset= $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el tipo de organizacion.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp=$this->io_validacion->uf_valida_texto($row["codemp"],0,4,"");;
				$ls_codpro=$this->io_validacion->uf_valida_texto($row["cod_pro"],0,10,"----------");
				$ls_cedsocio=$this->io_validacion->uf_valida_texto($row["cedsocio"],0,10,"----------"); 
				$ls_nomsocio=$this->io_validacion->uf_valida_texto($row["nomsocio"],0,50,""); 
				$ls_apesocio=$this->io_validacion->uf_valida_texto($row["apesocio"],0,50,"");  
				$ls_carsocio=$this->io_validacion->uf_valida_texto($row["carsocio"],0,100,"");
				$ls_telsocio=$this->io_validacion->uf_valida_texto($row["telsocio"],0,20,""); 
				$ls_dirsocio=$this->io_validacion->uf_valida_texto($row["dirsocio"],0,254,""); 
				$ls_email=$this->io_validacion->uf_valida_texto($row["email"],0,100,"");  
				$ls_foto=$io_recordset->fields["foto"];
				if($ls_codpro!="")
				{
					$ls_sql="INSERT INTO rpc_proveedorsocios(codemp, cod_pro, cedsocio, nomsocio, apesocio, carsocio, telsocio,".
							"                                dirsocio, email, foto)".
							"	  VALUES ('".$ls_codemp."','".$ls_codpro."','".$ls_cedsocio."','".$ls_nomsocio."',".
							"             '".$ls_apesocio."','".$ls_carsocio."','".$ls_telsocio."','".$ls_dirsocio."',".
							"             '".$ls_email."','".$ls_foto."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el proveedor socio.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los Socios de Proveedores .\r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  rpc_proveedorsocios Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino rpc_proveedorsocios Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_proveedorsocios
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_beneficiario()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_beneficiario
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 28/12/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$li_total_select= 0;
		$li_total_insert= 0;
		$ls_sql="SELECT codemp, ced_bene, codpai, codest, codmun, codpar, codtipcta, rifben, nombene, apebene, dirbene, telbene,".
				"       celbene, email, sc_cuenta, codbansig, codban, ctaban, foto, fecregben, nacben, numpasben, tipconben,".
				"       tipcuebanben".
				"  FROM rpc_beneficiario ";
		$io_recordset= $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el Beneficiario.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_cedbene=$this->io_validacion->uf_valida_texto($row["ced_bene"],0,10,"----------"); // Cédula del Beneficiario.  
				$ls_codpai=$this->io_validacion->uf_valida_texto($row["codpai"],0,3,"");
				$ls_codest=$this->io_validacion->uf_valida_texto($row["codest"],0,3,"");
				$ls_codmun=$this->io_validacion->uf_valida_texto($row["codmun"],0,3,"");
				$ls_codpar=$this->io_validacion->uf_valida_texto($row["codpar"],0,3,"");
				$ls_codtipcta=$this->io_validacion->uf_valida_texto($row["codtipcta"],0,10,"---"); // Código del Tipo de la Cuenta Asociada al Beneficiario.  
				$ls_nombene=$this->io_validacion->uf_valida_texto($row["nombene"],0,50,"");
				$ls_apebene=$this->io_validacion->uf_valida_texto($row["apebene"],0,50,"");
				$ls_dirbene=$this->io_validacion->uf_valida_texto($row["dirbene"],0,254,"");
				$ls_telbene=$this->io_validacion->uf_valida_texto($row["telbene"],0,20,"");
				$ls_celbene=$this->io_validacion->uf_valida_texto($row["celbene"],0,20,"");
				$ls_email=$this->io_validacion->uf_valida_texto($row["email"],0,100,"");
				$ls_sccuenta=$this->io_validacion->uf_valida_texto($row["sc_cuenta"],0,25,"");
				$ls_codbansig=$this->io_validacion->uf_valida_texto($row["codbansig"],0,3,"");
				$ls_codban=$this->io_validacion->uf_valida_texto($row["codban"],0,3,"");
				$ls_ctaban=$this->io_validacion->uf_valida_texto($row["ctaban"],0,25,"");
				$ls_foto=$row["foto"];
				$ls_rif=$this->io_validacion->uf_valida_texto($row["rif"],0,12,"");
				$ld_fecregben= $this->io_validacion->uf_valida_fecha($row["fecregben"],"1900-01-01");
				$ls_nacben=$this->io_validacion->uf_valida_texto($row["nacben"],0,1,"");
				$ls_numpasben=$this->io_validacion->uf_valida_texto($row["numpasben"],0,10,"");
				$ls_tipconben=$this->io_validacion->uf_valida_texto($row["tipconben"],0,1,"");
				$ls_tipcuebanben=$this->io_validacion->uf_valida_texto($row["tipcuebanben"],0,1,"");
				if($ls_cedbene!="")
				{
					$ls_sql="INSERT INTO rpc_beneficiario (codemp, ced_bene, codpai, codest, codmun, codpar, codtipcta, rifben, ".
							"nombene, apebene, dirbene, telbene, celbene, email, sc_cuenta, codbansig, codban, ctaban, foto, ".
							"fecregben, nacben, numpasben, tipconben,tipcuebanben) VALUES('".$ls_codemp."','".$ls_cedbene."','".$ls_codpai."', ".
						  	"'".$ls_codest."','".$ls_codmun."','".$ls_codpar."','".$ls_codtipcta."','".$ls_rif."','".$ls_nombene."',".
							"'".$ls_apebene."','".$ls_dirbene."','".$ls_telbene."','".$ls_celbene."','".$ls_email."','".$ls_sccuenta."',".
							"'".$ls_codbansig."','".$ls_codban."','".$ls_ctaban."','".$ls_foto."','".$ld_fecregben."',".
							"'".$ls_nacben."','".$ls_numpasben."','".$ls_tipconben."','".$ls_tipcuebanben."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el beneficiario.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los Beneficiarios.\r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  rpc_beneficiario Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino rpc_beneficiario Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_beneficiario
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_clasificacionxproveedor()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_clasificacionxproveedor
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 28/12/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$li_total_select= 0;
		$li_total_insert= 0;
		$ls_sql="SELECT codemp, cod_pro, codclas, status, nivstatus".
				"  FROM rpc_clasifxprov ";
		$io_recordset= $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar la clasificacion por proveedor.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp= $row["codemp"]; 
				$ls_codclas= $this->io_validacion->uf_valida_texto($row["codclas"],0,2,"");
				$ls_cod_pro = $this->io_validacion->uf_valida_texto($row["cod_pro"],0,10,"");
				$ls_status = $row["status"];
				$ls_nivstatus = $row["nivstatus"];
				if($ls_codclas!="")
				{
					$ls_sql="INSERT INTO rpc_clasifxprov(codemp, cod_pro, codclas, status, nivstatus)".
							"	  VALUES ('".$ls_codemp."','".$ls_cod_pro."','".$ls_codclas."','".$ls_status."','".$ls_nivstatus."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar la clasificacion por proveedor.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en las Clasificaciones por proveedor.\r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  rpc_clasifxprov Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino rpc_clasifxprov Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_clasificacionxproveedor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_especialidadxproveedor()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_especialidadxproveedor
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 28/12/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$li_total_select= 0;
		$li_total_insert= 0;
		$ls_sql="SELECT codemp, cod_pro, codesp".
				"  FROM rpc_espexprov ";
		$io_recordset= $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar la especialidad por proveedor.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp = $this->io_validacion->uf_valida_texto($row["codemp"],0,4,"");
				$ls_cod_pro = $this->io_validacion->uf_valida_texto($row["cod_pro"],0,10,"");
				$ls_codesp= $this->io_validacion->uf_valida_texto($row["codesp"],0,3,"");
				if($ls_codesp!="")
				{
					$ls_sql="INSERT INTO rpc_espexprov(codemp, cod_pro, codesp)".
							"	  VALUES ('".$ls_codemp."','".$ls_cod_pro."','".$ls_codesp."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar la especialidad por proveedor.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en las Especialidades por proveedor.\r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  rpc_espexprov Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino rpc_espexprov Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_especialidadxproveedor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_documentosxproveedor()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_documentosxproveedor
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 28/12/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$li_total_select= 0;
		$li_total_insert= 0;
		$ls_sql="SELECT codemp, coddoc, cod_pro, fecrecdoc, fecvendoc, estdoc, estorig".
				"  FROM rpc_docxprov ";
		$io_recordset= $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el documento.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codemp= $row["codemp"]; 
				$ls_coddoc= $this->io_validacion->uf_valida_texto($row["coddoc"],0,3,"");
				$ls_cod_pro= $this->io_validacion->uf_valida_texto($row["cod_pro"],0,10,"");
				$ld_fecrecdoc= $this->io_validacion->uf_valida_fecha($row["fecrecdoc"],"1900-01-01");
				$ld_fecvendoc= $this->io_validacion->uf_valida_fecha($row["fecvendoc"],"1900-01-01");
				$ls_estdoc= $row["estdoc"]; 
				$ls_estorig= $row["estorig"]; 
				if($ls_coddoc!="")
				{
					$ls_sql="INSERT INTO rpc_docxprov(codemp, coddoc, cod_pro, fecrecdoc, fecvendoc, estdoc, estorig)".
							"	  VALUES ('".$ls_codemp."','".$ls_coddoc."','".$ls_cod_pro."','".$ld_fecrecdoc."',".
							"             '".$ld_fecvendoc."','".$ls_estdoc."','".$ls_estorig."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el Documento.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los Documentos.\r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  rpc_docxprov Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino rpc_docxprov Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_documentosxproveedor
	//-----------------------------------------------------------------------------------------------------------------------------------

function ue_limpiar_rpc_basico()
{
	$lb_valido=true;
	$this->io_sql_destino->begin_transaction();
	//------------------------------------ Borrar tablas de rpc -----------------------------------------
	if($lb_valido)
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('rpc_proveedorsocios',"");			
		}
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('rpc_docxprov',"");			
		}
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('rpc_espexprov',"");			
		}
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('rpc_clasifxprov',"");			
		}
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('rpc_proveedor',"");			
		}
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('rpc_beneficiario',"");			
		}
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('sigesp_parroquia',"");			
		}
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('sigesp_municipio',"");			
		}
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('sigesp_estados',"");			
		}
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('sigesp_pais',"");			
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('rpc_especialidad',"");			
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('rpc_tipo_organizacion'," ");			
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('rpc_clasificacion',"");			
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_limpiar_tabla('rpc_documentos',"");			
		}
				
				
				
	if($lb_valido)
	{	
		$this->io_mensajes->message("La data de Proveedores y Contratistas se borró correctamente.");
		$ls_cadena="La data de Proveedores y Contratistas se borró correctamente.\r\n";
		if ($this->lo_archivo)			
		{
			@fwrite($this->lo_archivo,$ls_cadena);
		}
	}
	else
	{
		$this->io_mensajes->message("Ocurrió un error al copiar la data de Proveedores y Contratistas. Verifique el archivo txt."); 
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
		
		$this->io_mensajes->message("resultado:".$io_recordset." \n ".$ls_sql);

		if($io_recordset===false)
		{ 
			$lb_valido=false;
			//$ls_cadena="Error al Borrar la tabla".$as_tabla.".\r\n".$this->io_sql->ErrorMsg()."\r\n";
			$ls_cadena="Problema al borrar ".$as_tabla.".\r\n".$this->io_sql->message."\r\n";
			$ls_cadena=$ls_cadena.$ls_sql."\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}
		else
		{
			//$this->io_sql->Close();
			$ls_cadena = "//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla    Blanqueada  \r\n";
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

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_codigo_parroquia($as_codpai,$as_codest,$as_codmun,&$as_codpar)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_codigo_parroquia
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
		$ls_sql="SELECT codpar ".
				"  FROM sigesp_parroquia ".
				" WHERE codpai = '".$as_codpai."' ".
				"   AND codest = '".$as_codest."'".
				"   AND codmun = '".$as_codmun."'".
				"   AND codpar = '".$as_codpar."'";
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
				$as_codpar= "---"; 
			}
		}
		return $lb_valido;
	}// end function uf_load_codigo_parroquia.
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>