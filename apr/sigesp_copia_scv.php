<?php 
////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Class : sigesp_copia_scv.php                                	                			  //    
// Description : Procesa la copia de datos del modulo de seguridad									  //
////////////////////////////////////////////////////////////////////////////////////////////////////////

class sigesp_copia_scv {

	var $io_sql_origen;
	var $io_sql_destino;
	var $io_mensajes;
	var $io_funciones;
	var $io_validacion;
	var	$lo_archivo;
	var $ls_database_source;
	var $ls_database_target;
	
function sigesp_copia_scv()
{
	$ld_fecha=date("_d-m-Y");
	$ls_nombrearchivo="";
	$ls_nombrearchivo="resultado/".$_SESSION["ls_data_des"]."_scv_result_".$ld_fecha.".txt";
	$this->lo_archivo=@fopen("$ls_nombrearchivo","a+");

	$this->ls_database_source = $_SESSION["ls_database"];
	$this->ls_database_target = $_SESSION["ls_data_des"];
	require_once("class_folder/class_validacion.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");
	
	$this->io_validacion  = new class_validacion();
	$this->io_mensajes    = new class_mensajes();
	$io_conect			  = new sigesp_include();
	$io_conexion_origen   = $io_conect->uf_conectar();
	$io_conexion_destino  = $io_conect->uf_conectar($this->ls_database_target);
	$this->io_sql_origen  = new class_sql($io_conexion_origen);
	$this->io_sql_destino = new class_sql($io_conexion_destino);
	$this->io_rcbsf		  = new sigesp_c_reconvertir_monedabsf();
    $io_msg				  = new class_mensajes();
  }

function ue_copiar_scv_basico()
{
	$lb_valido=true;
	$this->io_sql_destino->begin_transaction();
	if ($lb_valido)
	   {	
		 $lb_valido=$this->uf_copiar_categorias();
	   } 
	if ($lb_valido)
	   {	
		 $lb_valido=$this->uf_copiar_ciudades();
	   } 
	if ($lb_valido)
	   {	
		 $lb_valido=$this->uf_copiar_distancias();
	   } 
	if ($lb_valido)
	   {	
		 $lb_valido=$this->uf_copiar_misiones();
	   } 
	if ($lb_valido)
	   {	
		 $lb_valido=$this->uf_copiar_regiones();
	   } 
	if ($lb_valido)
	   {	
		 $lb_valido=$this->uf_copiar_dt_regiones();
	   } 
	if ($lb_valido)
	   {	
		 $lb_valido=$this->uf_copiar_rutas();
	   } 
	if ($lb_valido)
	   {	
		 $lb_valido=$this->uf_copiar_transporte();
	   } 
	if ($lb_valido)
	   {	
		 $lb_valido=$this->uf_copiar_tarifas();
	   } 
	if ($lb_valido)
	   {	
		 $lb_valido=$this->uf_copiar_tarifas_kms();
	   } 
	if ($lb_valido)
	   {	
		 $lb_valido=$this->uf_copiar_otras_asignaciones();
	   } 

   if ($lb_valido)
	  {
	    $this->io_mensajes->message("La data de Viaticos se copió correctamente.");
		$ls_cadena="La data de Viaticos se copió correctamente.\r\n";
		if ($this->lo_archivo)			
		   {
			 @fwrite($this->lo_archivo,$ls_cadena);
		   }
	  }
	else
	  {
	    $this->io_mensajes->message("Ocurrió un error al copiar la data de Viaticos. Verifique el archivo txt."); 
	  }
  if ($lb_valido)
	 {
			$this->io_validacion->uf_insert_sistema_apertura('SCV');
	  		 $this->io_sql_destino->commit();
	 }
  else
	 {
	   $this->io_sql_destino->rollback();	
	 }
  return $lb_valido;	
}

function uf_copiar_categorias()
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_copiar_procedencias
//		   Access: private
//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	  Description: Función que selecciona la data de $as_database_source (base de datos origen) y los inserta en $as_dabatase_target (base de datos destino)
//	   Creado Por: 
// Fecha Creación: 20/11/2006 								Fecha Última Modificación : 	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		
		$ls_sql = "SELECT codemp, codcat, dencat
					 FROM scv_categorias";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if ($io_recordset===false)
		   {  
			 $lb_valido=false;
			 $ls_cadena="Problema al Copiar Categorias.\r\n".$this->io_sql_origen->ErrorMsg()."\r\n";
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
				    if (!empty($ls_codemp))
				       {
						 $ls_codcat = $this->io_validacion->uf_valida_texto($row["codcat"],0,1,"-");
						 $ls_dencat = $this->io_validacion->uf_valida_texto($row["dencat"],0,254,"-");
					     $ls_sql = "INSERT INTO scv_categorias (codemp, codcat, dencat) VALUES ('".$ls_codemp."','".$ls_codcat."','".$ls_dencat."')";
					     $li_row = $this->io_sql_destino->execute($ls_sql);
					     if ($li_row===false)
					        {
							  $lb_valido=false;
							  $ls_cadena="Error al Insertar Categorias .\r\n".$this->io_sql_destino->message."\r\n";
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
					     $ls_cadena="Hay data inconsistente en Categorias.\r\n";
					     if ($this->lo_archivo)			
				 	        {
						      @fwrite($this->lo_archivo,$ls_cadena);
					        }
				       }
			      }
			 $ls_cadena = "//*****************************************************************//\r\n";
			 $ls_cadena = $ls_cadena."   Tabla Origen  scv_categorias Registros ".$li_total_select." \r\n";
			 $ls_cadena = $ls_cadena."   Tabla Destino scv_categorias Registros ".$li_total_insert." \r\n";
			 $ls_cadena = $ls_cadena."//*****************************************************************//\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		return $lb_valido;
	}// end function uf_copiar_categorias	

function uf_copiar_ciudades()
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_copiar_ciudades
//		   Access: private
//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	  Description: Función que selecciona la data de $as_database_source (base de datos origen) y los inserta en $as_dabatase_target (base de datos destino)
//	   Creado Por: 
// Fecha Creación: 20/11/2006 								Fecha Última Modificación : 	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		
		$ls_sql = "SELECT codpai, codest, codciu, desciu
					 FROM scv_ciudades";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if ($io_recordset===false)
		   {  
			 $lb_valido=false;
			 $ls_cadena="Problema al Copiar Ciudades.\r\n".$this->io_sql_origen->ErrorMsg()."\r\n";
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
				    $ls_codpai = $this->io_validacion->uf_valida_texto($row["codpai"],0,3,"");
				    if (!empty($ls_codpai))
				       {
						 $ls_codest = $this->io_validacion->uf_valida_texto($row["codest"],0,3,"---");
						 $ls_codciu = $this->io_validacion->uf_valida_texto($row["codciu"],0,3,"---");
					     $ls_desciu = $this->io_validacion->uf_valida_texto($row["desciu"],0,100,"-");
						 
						 $ls_sql = "INSERT INTO scv_ciudades (codpai, codest, codciu, desciu) VALUES ('".$ls_codpai."','".$ls_codest."','".$ls_codciu."','".$ls_desciu."')";
					     $li_row = $this->io_sql_destino->execute($ls_sql);
					     if ($li_row===false)
					        {
							  $lb_valido=false;
							  $ls_cadena="Error al Insertar Ciudades .\r\n".$this->io_sql_destino->message."\r\n";
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
					     $ls_cadena="Hay data inconsistente en Ciudades.\r\n";
					     if ($this->lo_archivo)			
				 	        {
						      @fwrite($this->lo_archivo,$ls_cadena);
					        }
				       }
			      }
			 $ls_cadena = "//*****************************************************************//\r\n";
			 $ls_cadena = $ls_cadena."   Tabla Origen  scv_ciudades Registros ".$li_total_select." \r\n";
			 $ls_cadena = $ls_cadena."   Tabla Destino scv_ciudades Registros ".$li_total_insert." \r\n";
			 $ls_cadena = $ls_cadena."//*****************************************************************//\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		return $lb_valido;
	}// end function uf_copiar_ciudades	

function uf_copiar_distancias()
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_copiar_ciudades
//		   Access: private
//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	  Description: Función que selecciona la data de $as_database_source (base de datos origen) y los inserta en $as_dabatase_target (base de datos destino)
//	   Creado Por: 
// Fecha Creación: 20/11/2006 								Fecha Última Modificación : 	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		
		$ls_sql = "SELECT codpaiori, codestori, codciuori, codpaides, codestdes, codciudes, cankms
					 FROM scv_distancias";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if ($io_recordset===false)
		   {  
			 $lb_valido=false;
			 $ls_cadena="Problema al Copiar Distancias.\r\n".$this->io_sql_origen->ErrorMsg()."\r\n";
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
				    $ls_codpaiori = $this->io_validacion->uf_valida_texto($row["codpaiori"],0,3,"");
				    if (!empty($ls_codpaiori))
				       {
						 $ls_codestori = $this->io_validacion->uf_valida_texto($row["codestori"],0,3,"---");
					     $ls_codciuori = $this->io_validacion->uf_valida_texto($row["codciuori"],0,3,"---");
						 $ls_codpaides = $this->io_validacion->uf_valida_texto($row["codpaides"],0,3,"---");
						 $ls_codestdes = $this->io_validacion->uf_valida_texto($row["codestdes"],0,3,"---");
					     $ls_codciudes = $this->io_validacion->uf_valida_texto($row["codciudes"],0,3,"---");
						 $ld_cankms    = $this->io_validacion->uf_valida_monto($row["codciudes"],0);
						 
						 $ls_sql = "INSERT INTO scv_distancias (codpaiori, codestori, codciuori, codpaides, codestdes, codciudes, cankms) 
						                                VALUES ('".$ls_codpaiori."','".$ls_codestori."','".$ls_codciuori."','".$ls_codpaides."','".$ls_codestdes."','".$ls_codciudes."',".$ld_cankms.")";
					     $li_row = $this->io_sql_destino->execute($ls_sql);
					     if ($li_row===false)
					        {
							  $lb_valido=false;
							  $ls_cadena="Error al Insertar Distancias .\r\n".$this->io_sql_destino->message."\r\n";
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
					     $ls_cadena="Hay data inconsistente en Distancias.\r\n";
					     if ($this->lo_archivo)			
				 	        {
						      @fwrite($this->lo_archivo,$ls_cadena);
					        }
				       }
			      }
			 $ls_cadena = "//*****************************************************************//\r\n";
			 $ls_cadena = $ls_cadena."   Tabla Origen  scv_distancias Registros ".$li_total_select." \r\n";
			 $ls_cadena = $ls_cadena."   Tabla Destino scv_distancias Registros ".$li_total_insert." \r\n";
			 $ls_cadena = $ls_cadena."//*****************************************************************//\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		return $lb_valido;
	}// end function uf_copiar_distancias

function uf_copiar_misiones()
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_copiar_misiones
//		   Access: private
//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	  Description: Función que selecciona la data de $as_database_source (base de datos origen) y los inserta en $as_dabatase_target (base de datos destino)
//	   Creado Por: 
// Fecha Creación: 20/11/2006 								Fecha Última Modificación : 	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		
		$ls_sql = "SELECT codemp, codmis, denmis
					 FROM scv_misiones";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if ($io_recordset===false)
		   {  
			 $lb_valido=false;
			 $ls_cadena="Problema al Copiar Misiones.\r\n".$this->io_sql_origen->ErrorMsg()."\r\n";
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
				    if (!empty($ls_codemp))
				       {
						 $ls_codmis = $this->io_validacion->uf_valida_texto($row["codmis"],0,5,"-----");
					     $ls_denmis = $this->io_validacion->uf_valida_texto($row["denmis"],0,254,"-");
						 
						 $ls_sql = "INSERT INTO scv_misiones (codemp, codmis, denmis) VALUES ('".$ls_codemp."','".$ls_codmis."','".$ls_denmis."')";
					     $li_row = $this->io_sql_destino->execute($ls_sql);
					     if ($li_row===false)
					        {
							  $lb_valido=false;
							  $ls_cadena="Error al Insertar Misiones .\r\n".$this->io_sql_destino->message."\r\n";
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
					     $ls_cadena="Hay data inconsistente en Misiones.\r\n";
					     if ($this->lo_archivo)			
				 	        {
						      @fwrite($this->lo_archivo,$ls_cadena);
					        }
				       }
			      }
			 $ls_cadena = "//*****************************************************************//\r\n";
			 $ls_cadena = $ls_cadena."   Tabla Origen  scv_misiones Registros ".$li_total_select." \r\n";
			 $ls_cadena = $ls_cadena."   Tabla Destino scv_misiones Registros ".$li_total_insert." \r\n";
			 $ls_cadena = $ls_cadena."//*****************************************************************//\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		return $lb_valido;
	}// end function uf_copiar_misiones
	
function uf_copiar_regiones()
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_copiar_regiones
//		   Access: private
//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	  Description: Función que selecciona la data de $as_database_source (base de datos origen) y los inserta en $as_dabatase_target (base de datos destino)
//	   Creado Por: 
// Fecha Creación: 20/11/2006 								Fecha Última Modificación : 	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		
		$ls_sql = "SELECT codemp, codreg, codpai, denreg
					 FROM scv_regiones";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if ($io_recordset===false)
		   {  
			 $lb_valido=false;
			 $ls_cadena="Problema al Copiar Regiones.\r\n".$this->io_sql_origen->ErrorMsg()."\r\n";
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
				    if (!empty($ls_codemp))
				       {
						 $ls_codreg = $this->io_validacion->uf_valida_texto($row["codreg"],0,5,"-----");
						 $ls_codpai = $this->io_validacion->uf_valida_texto($row["codpai"],0,3,"---");
					     $ls_denreg = $this->io_validacion->uf_valida_texto($row["denreg"],0,254,"-");
						 
						 $ls_sql = "INSERT INTO scv_regiones (codemp, codreg, codpai, denreg) VALUES ('".$ls_codemp."','".$ls_codreg."','".$ls_codpai."','".$ls_denreg."')";
					     $li_row = $this->io_sql_destino->execute($ls_sql);
					     if ($li_row===false)
					        {
							  $lb_valido=false;
							  $ls_cadena="Error al Insertar Regiones .\r\n".$this->io_sql_destino->message."\r\n";
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
					     $ls_cadena="Hay data inconsistente en Regiones.\r\n";
					     if ($this->lo_archivo)			
				 	        {
						      @fwrite($this->lo_archivo,$ls_cadena);
					        }
				       }
			      }
			 $ls_cadena = "//*****************************************************************//\r\n";
			 $ls_cadena = $ls_cadena."   Tabla Origen  scv_regiones Registros ".$li_total_select." \r\n";
			 $ls_cadena = $ls_cadena."   Tabla Destino scv_regiones Registros ".$li_total_insert." \r\n";
			 $ls_cadena = $ls_cadena."//*****************************************************************//\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		return $lb_valido;
	}// end function uf_copiar_regiones	

function uf_copiar_dt_regiones()
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_copiar_dt_regiones
//		   Access: private
//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	  Description: Función que selecciona la data de $as_database_source (base de datos origen) y los inserta en $as_dabatase_target (base de datos destino)
//	   Creado Por: 
// Fecha Creación: 20/11/2006 								Fecha Última Modificación : 	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		
		$ls_sql = "SELECT codemp, codreg, codpai, codest
					 FROM scv_dt_regiones";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if ($io_recordset===false)
		   {  
			 $lb_valido=false;
			 $ls_cadena="Problema al Copiar Detalle de las Regiones.\r\n".$this->io_sql_origen->ErrorMsg()."\r\n";
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
				    if (!empty($ls_codemp))
				       {
						 $ls_codreg = $this->io_validacion->uf_valida_texto($row["codreg"],0,5,"-----");
						 $ls_codpai = $this->io_validacion->uf_valida_texto($row["codpai"],0,3,"---");
					     $ls_codest = $this->io_validacion->uf_valida_texto($row["codest"],0,3,"---");
						 
						 $ls_sql = "INSERT INTO scv_dt_regiones (codemp, codreg, codpai, codest) VALUES ('".$ls_codemp."','".$ls_codreg."','".$ls_codpai."','".$ls_codest."')";
					     $li_row = $this->io_sql_destino->execute($ls_sql);
					     if ($li_row===false)
					        {
							  $lb_valido=false;
							  $ls_cadena="Error al Insertar Detalle de las Regiones .\r\n".$this->io_sql_destino->message."\r\n";
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
					     $ls_cadena="Hay data inconsistente en el Detalle de las Regiones.\r\n";
					     if ($this->lo_archivo)			
				 	        {
						      @fwrite($this->lo_archivo,$ls_cadena);
					        }
				       }
			      }
			 $ls_cadena = "//*****************************************************************//\r\n";
			 $ls_cadena = $ls_cadena."   Tabla Origen  scv_dt_regiones Registros ".$li_total_select." \r\n";
			 $ls_cadena = $ls_cadena."   Tabla Destino scv_dt_regiones Registros ".$li_total_insert." \r\n";
			 $ls_cadena = $ls_cadena."//*****************************************************************//\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		return $lb_valido;
	}// end function uf_copiar_dt_regiones	
	
function uf_copiar_rutas()
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_copiar_regiones
//		   Access: private
//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	  Description: Función que selecciona la data de $as_database_source (base de datos origen) y los inserta en $as_dabatase_target (base de datos destino)
//	   Creado Por: 
// Fecha Creación: 20/11/2006 								Fecha Última Modificación : 	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		
		$ls_sql = "SELECT codemp, codrut, codpaiori, codestori, codciuori, codpaides, codestdes, codciudes, desrut
					 FROM scv_rutas";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if ($io_recordset===false)
		   {  
			 $lb_valido=false;
			 $ls_cadena="Problema al Copiar Rutas.\r\n".$this->io_sql_origen->ErrorMsg()."\r\n";
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
				    if (!empty($ls_codemp))
				       {
						 $ls_codrut    = $this->io_validacion->uf_valida_texto($row["codrut"],0,5,"-----");
						 $ls_codpaiori = $this->io_validacion->uf_valida_texto($row["codpaiori"],0,3,"---");
						 $ls_codestori = $this->io_validacion->uf_valida_texto($row["codestori"],0,3,"---");
						 $ls_codciuori = $this->io_validacion->uf_valida_texto($row["codciuori"],0,3,"---");
						 $ls_codpaides = $this->io_validacion->uf_valida_texto($row["codpaides"],0,3,"---");
						 $ls_codestdes = $this->io_validacion->uf_valida_texto($row["codestdes"],0,3,"---");
						 $ls_codciudes = $this->io_validacion->uf_valida_texto($row["codciudes"],0,3,"---");
					     $ls_desrut    = $this->io_validacion->uf_valida_texto($row["desrut"],0,254,"-");
						 
						 $ls_sql = "INSERT INTO scv_rutas (codemp, codrut, codpaiori, codestori, codciuori, codpaides, codestdes, codciudes, desrut) 
						                           VALUES ('".$ls_codemp."','".$ls_codrut."','".$ls_codpaiori."','".$ls_codestori."','".$ls_codciuori."','".$ls_codpaides."','".$ls_codestdes."','".$ls_codciudes."','".$ls_desrut."')";
					     $li_row = $this->io_sql_destino->execute($ls_sql);
					     if ($li_row===false)
					        {
							  $lb_valido=false;
							  $ls_cadena="Error al Insertar Rutas .\r\n".$this->io_sql_destino->message."\r\n";
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
					     $ls_cadena="Hay data inconsistente en Rutas.\r\n";
					     if ($this->lo_archivo)			
				 	        {
						      @fwrite($this->lo_archivo,$ls_cadena);
					        }
				       }
			      }
			 $ls_cadena = "//*****************************************************************//\r\n";
			 $ls_cadena = $ls_cadena."   Tabla Origen  scv_rutas Registros ".$li_total_select." \r\n";
			 $ls_cadena = $ls_cadena."   Tabla Destino scv_rutas Registros ".$li_total_insert." \r\n";
			 $ls_cadena = $ls_cadena."//*****************************************************************//\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		return $lb_valido;
	}// end function uf_copiar_rutas	
	
function uf_copiar_transporte()
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_copiar_transporte
//		   Access: private
//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	  Description: Función que selecciona la data de $as_database_source (base de datos origen) y los inserta en $as_dabatase_target (base de datos destino)
//	   Creado Por: 
// Fecha Creación: 20/11/2006 								Fecha Última Modificación : 	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		
		$ls_sql = "SELECT codemp, codtra, codtiptra, dentra, tartra
					 FROM scv_transportes";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if ($io_recordset===false)
		   {  
			 $lb_valido=false;
			 $ls_cadena="Problema al Copiar Transportes.\r\n".$this->io_sql_origen->ErrorMsg()."\r\n";
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
				    if (!empty($ls_codemp))
				       {
						 $ls_codtra    = $this->io_validacion->uf_valida_texto($row["codtra"],0,4,"-----");
						 $ls_codtiptra = $this->io_validacion->uf_valida_texto($row["codtiptra"],0,4,"---");
						 $ls_dentra    = $this->io_validacion->uf_valida_texto($row["dentra"],0,254,"---");
						 $ld_tartraaux = $this->io_validacion->uf_valida_monto($row["tartra"],0);
						 $ld_tartra    = $this->io_rcbsf->uf_convertir_monedabsf($ld_tartraaux,4,1,1000,1);
						 
						 $ls_sql = "INSERT INTO scv_transportes (codemp, codtra, codtiptra, dentra, tartra, tartraaux) 
						                                 VALUES ('".$ls_codemp."','".$ls_codtra."','".$ls_codtiptra."','".$ls_dentra."',".$ld_tartra.",".$ld_tartraaux.")";
					     $li_row = $this->io_sql_destino->execute($ls_sql);
					     if ($li_row===false)
					        {
							  $lb_valido=false;
							  $ls_cadena="Error al Insertar Transportes.\r\n".$this->io_sql_destino->message."\r\n";
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
					     $ls_cadena="Hay data inconsistente en Transportes.\r\n";
					     if ($this->lo_archivo)			
				 	        {
						      @fwrite($this->lo_archivo,$ls_cadena);
					        }
				       }
			      }
			 $ls_cadena = "//*****************************************************************//\r\n";
			 $ls_cadena = $ls_cadena."   Tabla Origen  scv_transportes Registros ".$li_total_select." \r\n";
			 $ls_cadena = $ls_cadena."   Tabla Destino scv_transportes Registros ".$li_total_insert." \r\n";
			 $ls_cadena = $ls_cadena."//*****************************************************************//\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		return $lb_valido;
	}// end function uf_copiar_transporte		

function uf_copiar_tarifas()
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_copiar_tarifas
//		   Access: private
//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	  Description: Función que selecciona la data de $as_database_source (base de datos origen) y los inserta en $as_dabatase_target (base de datos destino)
//	   Creado Por: 
// Fecha Creación: 20/11/2006 								Fecha Última Modificación : 	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		
		$ls_sql = "SELECT codemp, codtar, codcat, codnom, dentar, codpai, codreg, monbol, mondol, monpas, monhos, monali, monmov, nacext
					 FROM scv_tarifas";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if ($io_recordset===false)
		   {  
			 $lb_valido=false;
			 $ls_cadena="Problema al Copiar Tarifas.\r\n".$this->io_sql_origen->ErrorMsg()."\r\n";
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
				    if (!empty($ls_codemp))
				       {
						 $ls_codtar    = $this->io_validacion->uf_valida_texto($row["codtar"],0,4,"----");
						 $ls_codcat    = $this->io_validacion->uf_valida_texto($row["codcat"],0,1,"-");
						 $ls_codnom    = $this->io_validacion->uf_valida_texto($row["codmon"],0,4,"----");
						 $ls_dentar    = $this->io_validacion->uf_valida_texto($row["dentar"],0,254,"-");
						 $ls_codpai    = $this->io_validacion->uf_valida_texto($row["codpai"],0,3,"---");
						 $ls_codreg    = $this->io_validacion->uf_valida_texto($row["codreg"],0,5,"-----");
						 $ld_monbolaux = $this->io_validacion->uf_valida_monto($row["monbol"],0);
					     $ld_monbol    = $this->io_rcbsf->uf_convertir_monedabsf($ld_monbolaux,4,1,1000,1);
						 $ld_mondolaux = $this->io_validacion->uf_valida_monto($row["mondol"],0);
						 $ld_mondol    = $this->io_rcbsf->uf_convertir_monedabsf($ld_mondolaux,4,1,1000,1);
						 $ld_monpasaux = $this->io_validacion->uf_valida_monto($row["monpas"],0);
					     $ld_monpas    = $this->io_rcbsf->uf_convertir_monedabsf($ld_monpasaux,4,1,1000,1);
						 $ld_monhosaux = $this->io_validacion->uf_valida_monto($row["monhos"],0);
						 $ld_monhos    = $this->io_rcbsf->uf_convertir_monedabsf($ld_monhosaux,4,1,1000,1);
						 $ld_monaliaux = $this->io_validacion->uf_valida_monto($row["monali"],0);
					     $ld_monali    = $this->io_rcbsf->uf_convertir_monedabsf($ld_monaliaux,4,1,1000,1);
						 $ld_monmovaux = $this->io_validacion->uf_valida_monto($row["monmov"],0);
       					 $ld_monmov    = $this->io_rcbsf->uf_convertir_monedabsf($ld_monmovaux,4,1,1000,1);
						 $ls_nacext    = $this->io_validacion->uf_valida_texto($row["nacext"],0,3,"---");

						 $ls_sql = "INSERT INTO scv_tarifas (codemp, codtar, codcat, codnom, dentar, codpai, codreg, monbol,mondol,
						                                     monpas, monhos, monali, monmov, nacext, monbolaux,mondolaux,monpasaux,
															 monhosaux, monaliaux, monmovaux) 
						                           VALUES ('".$ls_codemp."','".$ls_codtar."','".$ls_codcat."','".$ls_codnom."','".$ls_dentar."','".$ls_codpai."','".$ls_codreg."',".$ld_monbol.",".$ld_mondol.",".$ld_monpas.",
					     								   ".$ld_monhos.",".$ld_monali.",".$ld_monmov.",'".$ls_nacext."',".$ld_monbolaux.",".$ld_mondolaux.",".$ld_monpasaux.",".$ld_monhosaux.",".$ld_monaliaux.",".$ld_monmovaux.")";
					     $li_row = $this->io_sql_destino->execute($ls_sql);
					     if ($li_row===false)
					        {
							  $lb_valido=false;
							  $ls_cadena="Error al Insertar Tarifas.\r\n".$this->io_sql_destino->message."\r\n";
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
					     $ls_cadena="Hay data inconsistente en Tarifas.\r\n";
					     if ($this->lo_archivo)			
				 	        {
						      @fwrite($this->lo_archivo,$ls_cadena);
					        }
				       }
			      }
			 $ls_cadena = "//*****************************************************************//\r\n";
			 $ls_cadena = $ls_cadena."   Tabla Origen  scv_tarifas Registros ".$li_total_select." \r\n";
			 $ls_cadena = $ls_cadena."   Tabla Destino scv_tarifas Registros ".$li_total_insert." \r\n";
			 $ls_cadena = $ls_cadena."//*****************************************************************//\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		return $lb_valido;
	}// end function uf_copiar_tarifas.		
	
function uf_copiar_tarifas_kms()
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_copiar_tarifas_kms
//		   Access: private
//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	  Description: Función que selecciona la data de $as_database_source (base de datos origen) y los inserta en $as_dabatase_target (base de datos destino)
//	   Creado Por: 
// Fecha Creación: 20/11/2006 								Fecha Última Modificación : 	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		
		$ls_sql = "SELECT codemp, codtar, dentar, kmsdes, kmshas, montar
					 FROM scv_tarifakms";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if ($io_recordset===false)
		   {  
			 $lb_valido=false;
			 $ls_cadena="Problema al Copiar Tarifas Por Kilometraje.\r\n".$this->io_sql_origen->ErrorMsg()."\r\n";
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
				    if (!empty($ls_codemp))
				       {
						 $ls_codtar    = $this->io_validacion->uf_valida_texto($row["codtar"],0,4,"----");
						 $ls_dentar    = $this->io_validacion->uf_valida_texto($row["dentar"],0,100,"-");
						 $ld_kmsdes    = $this->io_validacion->uf_valida_monto($row["kmsdes"],0);
						 $ld_kmshas    = $this->io_validacion->uf_valida_monto($row["kmshas"],0);
						 $ld_montaraux = $this->io_validacion->uf_valida_monto($row["montar"],0);
					     $ld_montar    = $this->io_rcbsf->uf_convertir_monedabsf($ld_montaraux,4,1,1000,1);

						 $ls_sql = "INSERT INTO scv_tarifakms (codemp, codtar, dentar, kmsdes, kmshas, montar, montaraux) 
						                           VALUES ('".$ls_codemp."','".$ls_codtar."','".$ls_dentar."',".$ld_kmsdes.",".$ld_kmshas.",".$ld_montar.",".$ld_montaraux.")";
					     $li_row = $this->io_sql_destino->execute($ls_sql);
					     if ($li_row===false)
					        {
							  $lb_valido=false;
							  $ls_cadena="Error al Insertar Tarifas Por Kilometraje.\r\n".$this->io_sql_destino->message."\r\n";
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
					     $ls_cadena="Hay data inconsistente en Tarifas Por Kilometraje.\r\n";
					     if ($this->lo_archivo)			
				 	        {
						      @fwrite($this->lo_archivo,$ls_cadena);
					        }
				       }
			      }
			 $ls_cadena = "//*****************************************************************//\r\n";
			 $ls_cadena = $ls_cadena."   Tabla Origen  scv_tarifakms Registros ".$li_total_select." \r\n";
			 $ls_cadena = $ls_cadena."   Tabla Destino scv_tarifakms Registros ".$li_total_insert." \r\n";
			 $ls_cadena = $ls_cadena."//*****************************************************************//\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		return $lb_valido;
	}// end function uf_copiar_tarifas_kms.		

function uf_copiar_otras_asignaciones()
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_copiar_otras_asignaciones
//		   Access: private
//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	  Description: Función que selecciona la data de $as_database_source (base de datos origen) y los inserta en $as_dabatase_target (base de datos destino)
//	   Creado Por: 
// Fecha Creación: 20/11/2006 								Fecha Última Modificación : 	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_select=0;
		$li_total_insert=0;
		
		$ls_sql = "SELECT codemp, codotrasi, denotrasi, tarotrasi
					 FROM scv_otrasasignaciones";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if ($io_recordset===false)
		   {  
			 $lb_valido=false;
			 $ls_cadena="Problema al Copiar Otras asiganciones.\r\n".$this->io_sql_origen->ErrorMsg()."\r\n";
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
				    if (!empty($ls_codemp))
				       {
						 $ls_codotrasi    = $this->io_validacion->uf_valida_texto($row["codotrasi"],0,4,"----");
						 $ls_denotrasi    = $this->io_validacion->uf_valida_texto($row["denotrasi"],0,254,"-");
						 $ld_tarotrasiaux = $this->io_validacion->uf_valida_monto($row["tarotrasi"],0);
					     $ld_tarotrasi    = $this->io_rcbsf->uf_convertir_monedabsf($ld_tarotrasiaux,4,1,1000,1);

						 $ls_sql = "INSERT INTO scv_otrasasignaciones (codemp, codotrasi, denotrasi, tarotrasi, tarotrasiaux) 
						                           VALUES ('".$ls_codemp."','".$ls_codotrasi."','".$ls_denotrasi."',".$ld_tarotrasi.",".$ld_tarotrasiaux.")";
					     $li_row = $this->io_sql_destino->execute($ls_sql);
					     if ($li_row===false)
					        {
							  $lb_valido=false;
							  $ls_cadena="Error al Insertar Otras Asignaciones.\r\n".$this->io_sql_destino->message."\r\n";
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
					     $ls_cadena="Hay data inconsistente en Otras Asignaciones.\r\n";
					     if ($this->lo_archivo)			
				 	        {
						      @fwrite($this->lo_archivo,$ls_cadena);
					        }
				       }
			      }
			 $ls_cadena = "//*****************************************************************//\r\n";
			 $ls_cadena = $ls_cadena."   Tabla Origen  scv_otrasasignaciones Registros ".$li_total_select." \r\n";
			 $ls_cadena = $ls_cadena."   Tabla Destino scv_otrasasignaciones Registros ".$li_total_insert." \r\n";
			 $ls_cadena = $ls_cadena."//*****************************************************************//\r\n";
			 if ($this->lo_archivo)			
			    {
				  @fwrite($this->lo_archivo,$ls_cadena);
			    }
		   }
		return $lb_valido;
	}// end function uf_copiar_otras_asignaciones.
  
	function ue_limpiar_scv_basico()
	{
		$lb_valido=true;
		
		$this->io_sql_destino->begin_transaction();
		//------------------------------------ Borrar tablas de Seguridad -----------------------------------------
		if($lb_valido)
		
			if($lb_valido)		
			{
				$lb_valido=$this->uf_limpiar_tabla('scv_otrasasignaciones');
			}	
			if($lb_valido)		
			{
				$lb_valido=$this->uf_limpiar_tabla('scv_tarifakms');
			}	
			if($lb_valido)		
			{
				$lb_valido=$this->uf_limpiar_tabla('scv_transportes');
			}	
			if($lb_valido)		
			{
				$lb_valido=$this->uf_limpiar_tabla('scv_rutas');
			}	
			if($lb_valido)		
			{
				$lb_valido=$this->uf_limpiar_tabla('scv_dt_regiones');
			}	
			if($lb_valido)		
			{
				$lb_valido=$this->uf_limpiar_tabla('scv_regiones');
			}	
			if($lb_valido)		
			{
				$lb_valido=$this->uf_limpiar_tabla('scv_tarifas');
			}	
			if($lb_valido)		
			{
				$lb_valido=$this->uf_limpiar_tabla('scv_misiones');
			}	
			if($lb_valido)		
			{
				$lb_valido=$this->uf_limpiar_tabla('scv_distancias');
			}	
			if($lb_valido)		
			{
				$lb_valido=$this->uf_limpiar_tabla('scv_ciudades');
			}	
			if($lb_valido)		
			{
				$lb_valido=$this->uf_limpiar_tabla('scv_categorias');
			}	
			
		if($lb_valido)  	
		{
			$this->io_mensajes->message("La data de Viaticos se borró correctamente.");
			$ls_cadena="La data de Seguridad se borró correctamente.\r\n";
			if ($this->lo_archivo)
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}
		else
		{
			$this->io_mensajes->message("Ocurrió un error al borrar la data de Viaticos. Verifique el archivo txt."); 
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
			$ls_sql="DELETE FROM ".$as_tabla;
	
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
