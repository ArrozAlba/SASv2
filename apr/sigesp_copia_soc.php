<?php 
////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Class : sigesp_copia_soc.php                                	                			  //    
// Description : Procesa la copia de datos del modulo de Compras									  //
////////////////////////////////////////////////////////////////////////////////////////////////////////
class sigesp_copia_soc {

	var $io_sql_origen;
	var $io_sql_destino;
	var $io_mensajes;
	var $io_funciones;
	var $io_validacion;
	var	$lo_archivo;
	var $ls_database_source;
	var $ls_dabatase_target;
	
function sigesp_copia_soc()
{
		$ld_fecha=date("_d-m-Y");
		$ls_nombrearchivo="resultado/".$_SESSION["ls_data_des"]."_soc_result_".$ld_fecha.".txt";
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
}


function ue_copiar_soc_basico()
{
	$lb_valido=true;
	$this->io_sql_destino->begin_transaction();
	if ($lb_valido)
	   {	
		 $lb_valido=$this->uf_copiar_clausulas();
	   } 
	if ($lb_valido)
	   {	
		 $lb_valido=$this->uf_copiar_modalidad_clausula();
	   } 
	if ($lb_valido)
	   {	
 	     $lb_valido = $this->uf_copiar_tipos_servicios();
	   } 
	if ($lb_valido)
	   {	
 	     $lb_valido = $this->uf_copiar_servicios();
	   } 
	if ($lb_valido)
	   {	
 	     $lb_valido = $this->uf_copiar_servicio_cargo();
	   } 
  if ($lb_valido)
	 {
	   $this->io_mensajes->message("La data de Compras se copió correctamente.");
	   $ls_cadena="La data de Compras se copió correctamente.\r\n";
	   if ($this->lo_archivo)			
		  {
		    @fwrite($this->lo_archivo,$ls_cadena);
		  }
	 }
  else
	{
	  $this->io_mensajes->message("Ocurrió un error al copiar la data de Compras. Verifique el archivo txt."); 
	}
 if ($lb_valido)
	{
	$this->io_validacion->uf_insert_sistema_apertura('SOC');
	  $this->io_sql_destino->commit();
	}
 else
	{
	  $this->io_sql_destino->rollback();	
	}
	return $lb_valido;	
}

function uf_copiar_clausulas()
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_copiar_clausulas
//		   Access: private
//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	  Description: Función que selecciona la data de $as_database_source (base de datos origen) y los inserta en $as_dabatase_target (base de datos destino)
//	   Creado Por: 
// Fecha Creación: 20/11/2006 								Fecha Última Modificación : 	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		
		$ls_sql = "SELECT codemp, codcla, dencla
					 FROM soc_clausulas";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if ($io_recordset===false)
		   {  
			 $lb_valido=false;
			 $ls_cadena="Problema al Copiar Clausulas.\r\n".$this->io_sql_origen->ErrorMsg()."\r\n";
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
				    if (!empty($ls_codemp))
				       {
                         $ls_codcla = $this->io_validacion->uf_valida_texto($row["codcla"],0,6,"-");
						 $ls_dencla = $this->io_validacion->uf_valida_texto($row["dencla"],0,254,"-");
					     $ls_sql = "INSERT INTO soc_clausulas (codemp, codcla, dencla) VALUES ('".$ls_codemp."','".$ls_codcla."','".$ls_dencla."')";
					     $li_row = $this->io_sql_destino->execute($ls_sql);
					     if ($li_row===false)
					        {
							  $lb_valido=false;
							  $ls_cadena="Error al Insertar Clausulas .\r\n".$this->io_sql_destino->message."\r\n";
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
					     $ls_cadena="Hay data inconsistente en Clausulas.\r\n";
					     if ($this->lo_archivo)			
				 	        {
						      @fwrite($this->lo_archivo,$ls_cadena);
					        }
				       }
			      }
			 $ls_cadena = "//*****************************************************************//\r\n";
			 $ls_cadena = $ls_cadena."   Tabla Origen  soc_clausulas Registros ".$li_total_select." \r\n";
			 $ls_cadena = $ls_cadena."   Tabla Destino soc_clausulas Registros ".$li_total_insert." \r\n";
			 $ls_cadena = $ls_cadena."//*****************************************************************//\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		return $lb_valido;
	}// end function uf_copiar_clausulas

function uf_copiar_modalidad_clausula()
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_copiar_modalidad_clausula
//		   Access: private
//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	  Description: Función que selecciona la data de $as_database_source (base de datos origen) y los inserta en $as_dabatase_target (base de datos destino)
//	   Creado Por: 
// Fecha Creación: 20/11/2006 								Fecha Última Modificación : 	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		
		$ls_sql = "SELECT codemp, codtipmod, denmodcla
					 FROM soc_modalidadclausulas";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if ($io_recordset===false)
		   {  
			 $lb_valido=false;
			 $ls_cadena="Problema al Copiar Modalidad Clausulas.\r\n".$this->io_sql_origen->ErrorMsg()."\r\n";
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
				    if (!empty($ls_codemp))
				       {
                         $ls_codtipmod = $this->io_validacion->uf_valida_texto($row["codtipmod"],0,2,"--");
						 $ls_denmodcla = $this->io_validacion->uf_valida_texto($row["denmodcla"],0,100,"-");
					     $ls_sql = "INSERT INTO soc_modalidadclausulas (codemp, codtipmod, denmodcla) VALUES ('".$ls_codemp."','".$ls_codtipmod."','".$ls_denmodcla."')";
					     $li_row = $this->io_sql_destino->execute($ls_sql);
					     if ($li_row===false)
					        {
							  $lb_valido=false;
							  $ls_cadena="Error al Insertar Modalidad Clausulas .\r\n".$this->io_sql_destino->message."\r\n";
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
					     $ls_cadena="Hay data inconsistente en Modalidad Clausulas.\r\n";
					     if ($this->lo_archivo)			
				 	        {
						      @fwrite($this->lo_archivo,$ls_cadena);
					        }
				       }
			      }
			 $ls_cadena = "//*****************************************************************//\r\n";
			 $ls_cadena = $ls_cadena."   Tabla Origen  soc_modalidadclausulas Registros ".$li_total_select." \r\n";
			 $ls_cadena = $ls_cadena."   Tabla Destino soc_modalidadclausulas Registros ".$li_total_insert." \r\n";
			 $ls_cadena = $ls_cadena."//*****************************************************************//\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		return $lb_valido;
	}// end function uf_copiar_modalidad_clausula
		
function uf_copiar_tipos_servicios()
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_copiar_sistemas
//		   Access: private
//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	  Description: Función que selecciona la data de $as_database_source (base de datos origen) y los inserta en $as_dabatase_target (base de datos destino)
//	   Creado Por: 
// Fecha Creación: 20/11/2006 								Fecha Última Modificación : 	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		
		$ls_sql = "SELECT codtipser, dentipser, obstipser, codmil
					 FROM soc_tiposervicio";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if ($io_recordset===false)
		   {  
			 $lb_valido=false;
			 $ls_cadena="Problema al Copiar Tipos de Servicios.\r\n".$this->io_sql_origen->ErrorMsg()."\r\n";
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
				    $ls_codtipser = $row["codtipser"];
				    if (!empty($ls_codtipser))
				       {
                         $ls_dentipser = $this->io_validacion->uf_valida_texto($row["dentipser"],0,254,"-");
						 $ls_obstipser = $this->io_validacion->uf_valida_texto($row["obstipser"],0,1000,"");
					     $ls_codmil    = $this->io_validacion->uf_valida_texto($row["codmil"],0,15,"");						 
						 
						 $ls_sql = "INSERT INTO soc_tiposervicio (codtipser, dentipser, obstipser, codmil) VALUES ('".$ls_codtipser."','".$ls_dentipser."','".$ls_obstipser."','".$ls_codmil."')";
					     $li_row = $this->io_sql_destino->execute($ls_sql);
					     if ($li_row===false)
					        {
							  $lb_valido=false;
							  $ls_cadena="Error al Insertar Tipos de Servicios .\r\n".$this->io_sql_destino->message."\r\n";
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
					     $ls_cadena="Hay data inconsistente en Tipos de Servicios.\r\n";
					     if ($this->lo_archivo)			
				 	        {
						      @fwrite($this->lo_archivo,$ls_cadena);
					        }
				       }
			      }
			 $ls_cadena = "//*****************************************************************//\r\n";
			 $ls_cadena = $ls_cadena."   Tabla Origen  soc_tiposervicio Registros ".$li_total_select." \r\n";
			 $ls_cadena = $ls_cadena."   Tabla Destino soc_tiposervicio Registros ".$li_total_insert." \r\n";
			 $ls_cadena = $ls_cadena."//*****************************************************************//\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		return $lb_valido;
	}// end function uf_copiar_tipos_servicios
	
function uf_copiar_servicios()
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_copiar_sistemas
//		   Access: private
//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	  Description: Función que selecciona la data de $as_database_source (base de datos origen) y los inserta en $as_dabatase_target (base de datos destino)
//	   Creado Por: 
// Fecha Creación: 20/11/2006 								Fecha Última Modificación : 	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		
		$ls_sql = "SELECT codemp, codser, codtipser, denser, preser, spg_cuenta
					 FROM soc_servicios";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if ($io_recordset===false)
		   {  
			 $lb_valido=false;
			 $ls_cadena="Problema al Copiar Servicios.\r\n".$this->io_sql_origen->ErrorMsg()."\r\n";
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
				    if (!empty($ls_codemp))
				       {
                         $ls_codser    = $this->io_validacion->uf_valida_texto($row["codser"],0,10,"-");
						 $ls_codtipser = $this->io_validacion->uf_valida_texto($row["codtipser"],0,4,"----");
					     $ls_denser    = $this->io_validacion->uf_valida_texto($row["denser"],0,254,"-");
						 $ld_preser    = $this->io_validacion->uf_valida_monto($row["preser"],0);
						 $ld_preser    = $this->io_rcbsf->uf_convertir_monedabsf($ld_preser,4,1,1000,1);
						 $ls_spgcta    = $this->io_validacion->uf_valida_texto($row["spg_cuenta"],0,25,"");
						 $ld_preseraux = $this->io_validacion->uf_valida_monto($row["preser"],0);
						 
						 $ls_sql = "INSERT INTO soc_servicios (codemp, codser, codtipser, denser, preser, spg_cuenta, preseraux) 
						                               VALUES ('".$ls_codemp."','".$ls_codser."','".$ls_codtipser."','".$ls_denser."',".$ld_preser.",'".$ls_spgcta."',".$ld_preseraux.")";
					     $li_row = $this->io_sql_destino->execute($ls_sql);
					     if ($li_row===false)
					        {
							  $lb_valido=false;
							  $ls_cadena="Error al Insertar Servicios .\r\n".$this->io_sql_destino->message."\r\n";
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
					     $ls_cadena="Hay data inconsistente en Servicios.\r\n";
					     if ($this->lo_archivo)			
				 	        {
						      @fwrite($this->lo_archivo,$ls_cadena);
					        }
				       }
			      }
			 $ls_cadena = "//*****************************************************************//\r\n";
			 $ls_cadena = $ls_cadena."   Tabla Origen  soc_servicios Registros ".$li_total_select." \r\n";
			 $ls_cadena = $ls_cadena."   Tabla Destino soc_servicios Registros ".$li_total_insert." \r\n";
			 $ls_cadena = $ls_cadena."//*****************************************************************//\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		return $lb_valido;
	}// end function uf_copiar_tipos_servicios

function uf_copiar_servicio_cargo()
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_copiar_servicio_cargo
//		   Access: private
//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	  Description: Función que selecciona la data de $as_database_source (base de datos origen) y los inserta en $as_dabatase_target (base de datos destino)
//	   Creado Por: 
// Fecha Creación: 20/11/2006 								Fecha Última Modificación : 	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		
		$ls_sql = "SELECT codemp, codcar, codser
					 FROM soc_serviciocargo";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if ($io_recordset===false)
		   {  
			 $lb_valido=false;
			 $ls_cadena="Problema al Copiar Cargos Por Servicios.\r\n".$this->io_sql_origen->ErrorMsg()."\r\n";
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
				    if (!empty($ls_codemp))
				       {
                         $ls_codcar = $this->io_validacion->uf_valida_texto($row["codcar"],0,5,"-----");
						 $ls_codser = $this->io_validacion->uf_valida_texto($row["codser"],0,10,"----------");
					     $ls_sql = "INSERT INTO soc_serviciocargo (codemp, codcar, codser) VALUES ('".$ls_codemp."','".$ls_codcar."','".$ls_codser."')";
					     $li_row = $this->io_sql_destino->execute($ls_sql);
					     if ($li_row===false)
					        {
							  $lb_valido=false;
							  $ls_cadena="Error al Insertar Cargos Por Servicios.\r\n".$this->io_sql_destino->message."\r\n";
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
					     $ls_cadena="Hay data inconsistente en Cargos Por Servicios.\r\n";
					     if ($this->lo_archivo)			
				 	        {
						      @fwrite($this->lo_archivo,$ls_cadena);
					        }
				       }
			      }
			 $ls_cadena = "//*****************************************************************//\r\n";
			 $ls_cadena = $ls_cadena."   Tabla Origen  soc_serviciocargo Registros ".$li_total_select." \r\n";
			 $ls_cadena = $ls_cadena."   Tabla Destino soc_serviciocargo Registros ".$li_total_insert." \r\n";
			 $ls_cadena = $ls_cadena."//*****************************************************************//\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		return $lb_valido;
	}// end function uf_copiar_servicio_cargo


function ue_limpiar_soc_basico()
{
	$lb_valido=true;
	$this->io_sql_destino->begin_transaction();
	//------------------------------------ Borrar tablas de banco -----------------------------------------
	    if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('soc_serviciocargo');
		}
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('soc_servicios');
		}
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('soc_tiposervicio');
		}
		if($lb_valido)		   
		{
			$lb_valido=$this->uf_limpiar_tabla('soc_modalidadclausulas');
		}	
		if($lb_valido)		   
		{
			$lb_valido=$this->uf_limpiar_tabla('soc_clausulas');
		}	
	if($lb_valido)    
	{
		$this->io_mensajes->message("La data de Compras se borró correctamente.");
		$ls_cadena="La data de Compras se borró correctamente.\r\n";
		if ($this->lo_archivo)
		{
			@fwrite($this->lo_archivo,$ls_cadena);
		}
	}
	else
	{
		$this->io_mensajes->message("Ocurrió un error al copiar la data de Compras. Verifique el archivo txt."); 
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

function uf_limpiar_tabla($as_tabla)
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
	$ls_sql="DELETE FROM $as_tabla";
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
}
?>