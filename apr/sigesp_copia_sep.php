<?php 
////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Class : sigesp_copia_siv.php                                	                			  //    
// Description : Procesa la copia de datos del modulo de inventario									  //
////////////////////////////////////////////////////////////////////////////////////////////////////////
class sigesp_copia_sep {

	var $io_sql_origen;
	var $io_sql_destino;
	var $io_mensajes;
	var $io_funciones;
	var $io_validacion;
	var	$lo_archivo;
	var $ls_database_source;
	var $ls_dabatase_target;
	
	//-----------------------------------------------------------------------------------------------------------------------------------	
	function sigesp_copia_sep()
	{
		$ld_fecha=date("_d-m-Y");
		$ls_nombrearchivo="resultado/".$_SESSION["ls_data_des"]."_sep_result_".$ld_fecha.".txt";
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
	}// end function sigesp_copia_sep
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function ue_copiar_sep_basico()
	{
		$lb_valido=true;
		$this->io_sql_destino->begin_transaction();
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_tiposolicitud();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_conceptos();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_conceptoscargos();
		}		
		if($lb_valido)
		{
			$this->io_mensajes->message("La data de Solicitud de Ejecucion Presupuestaria se copió correctamente.");
			$ls_cadena="La data de Solicitud de Ejecucion Presupuestaria se copió correctamente.\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}
		else
		{
			$this->io_mensajes->message("Ocurrió un error al copiar la data de Solicitud de Ejecucion Presupuestaria. Verifique el archivo txt."); 
		}
		if ($lb_valido)
		{
			$this->io_validacion->uf_insert_sistema_apertura('SEP');
			$this->io_sql_destino->commit();
		}
		else
		{
			$this->io_sql_destino->rollback();	
		}
		return $lb_valido;	
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_insert_tiposolicitud()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_tiposolicitud
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sep_tiposolicitud
		//	   Creado Por: 
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido       = true;
		$li_total_select = 0;
		$li_total_insert = 0;
		$ls_sql          = "SELECT codtipsol, dentipsol, estope, modsep ".
						   "  FROM sep_tiposolicitud ";
		$io_recordset    = $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar los Tipos de Solicitud.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codtipsol=$this->io_validacion->uf_valida_texto($row["codtipsol"],0,2,"");
				$ls_dentipsol=$this->io_validacion->uf_valida_texto($row["dentipsol"],0,80,"");
				$ls_estope=$this->io_validacion->uf_valida_texto($row["estope"],0,1,"");
				$ls_modsep=$this->io_validacion->uf_valida_texto($row["modsep"],0,1,"");
				if($ls_codtipsol!="")
				{
					$ls_sql="INSERT INTO sep_tiposolicitud (codtipsol, dentipsol, estope, modsep) VALUES ('".$ls_codtipsol."','".$ls_dentipsol."',".
							"'".$ls_estope."','".$ls_modsep."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar los Tipos de Solicitud.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los Tipos de Solicitud.\r\n";
					$ls_cadena=$ls_cadena."Tipo solicitud ".$ls_codtipsol." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sep_tiposolicitud Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sep_tiposolicitud Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_tiposolicitud
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_insert_conceptos()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_conceptos
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sep_conceptos
		//	   Creado Por: 
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido       = true;
		$li_total_select = 0;
		$li_total_insert = 0;
		$ls_sql          = "SELECT codconsep, denconsep, monconsepe, obsconesp, spg_cuenta ".
						   "  FROM sep_conceptos ";
		$io_recordset    = $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar los Conceptos.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codconsep=$this->io_validacion->uf_valida_texto($row["codconsep"],0,3,"");
				$ls_denconsep=$this->io_validacion->uf_valida_texto($row["denconsep"],0,254,"");
				$li_monconsepe=$this->io_validacion->uf_valida_monto($row["monconsepe"],0);
				$ls_obsconesp=$this->io_validacion->uf_valida_texto($row["obsconesp"],0,8000,"");
				$ls_spg_cuenta=$this->io_validacion->uf_valida_texto($row["spg_cuenta"],0,25,"");
				$li_monconsepe=$this->io_rcbsf->uf_convertir_monedabsf($li_monconsepe,2,1,1000,1);
				$li_monconsepeaux=$this->io_validacion->uf_valida_monto($row["monconsepe"],0);
				if($ls_codconsep!="")
				{
					$ls_sql="INSERT INTO sep_conceptos (codconsep, denconsep, monconsepe, obsconesp, spg_cuenta, monconsepeaux) VALUES ".
							"('".$ls_codconsep."','".$ls_denconsep."',".$li_monconsepe.",'".$ls_obsconesp."','".$ls_spg_cuenta."',".$li_monconsepeaux.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar los Concepto.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los Conceptos.\r\n";
					$ls_cadena=$ls_cadena."Conceptos ".$ls_codconsep." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sep_conceptos Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sep_conceptos Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_conceptos
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_insert_conceptoscargos()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_conceptoscargos
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de sep_conceptocargos
		//	   Creado Por: 
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido       = true;
		$li_total_select = 0;
		$li_total_insert = 0;
		$ls_sql          = "SELECT codemp, codcar, codconsep ".
						   "  FROM sep_conceptocargos ";
		$io_recordset    = $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar los Cargos de los Conceptos.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codconsep=$this->io_validacion->uf_valida_monto($row["codconsep"],0,3,"");
				if($ls_codconsep!="")
				{
					$ls_sql="INSERT INTO sep_conceptocargos (codemp, codcar, codconsep) VALUES ('".$ls_codemp."','".$ls_codcar."','".$ls_codconsep."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar los Cargos de los Concepto.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los Cargos de los Concepto.\r\n";
					$ls_cadena=$ls_cadena."Conceptos ".$ls_codconsep." \r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  sep_conceptocargos Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino sep_conceptocargos Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_conceptoscargos
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function ue_limpiar_sep_basico()
	{
		$lb_valido=true;
		$this->io_sql_destino->begin_transaction();
		//------------------------------------ Borrar tablas de sep -----------------------------------------
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('sep_conceptocargos',"  ");			
		}
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('sep_conceptos'," ");			
		}
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('sep_tiposolicitud'," ");			
		}
		if($lb_valido)
		{	
			$this->io_mensajes->message("La data de Solicitud de Ejecución Presupuestaria se borró correctamente.");
			$ls_cadena="La data de Solicitud de Ejecución Presupuestaria se borró correctamente.\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}
		else
		{
			$this->io_mensajes->message("Ocurrió un error al Borrar la data de Solicitud de Ejecución Presupuestaria. Verifique el archivo txt."); 
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
	}// end function ue_limpiar_sep_basico
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