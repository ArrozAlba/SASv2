<?php 
////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Class : sigesp_copia_siv.php                                	                			  //    
// Description : Procesa la copia de datos del modulo de inventario									  //
////////////////////////////////////////////////////////////////////////////////////////////////////////
class sigesp_copia_siv {

	var $io_sql_origen;
	var $io_sql_destino;
	var $io_mensajes;
	var $io_funciones;
	var $io_validacion;
	var	$lo_archivo;
	var $ls_database_source;
	var $ls_dabatase_target;
	
function sigesp_copia_siv()
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
	$ls_nombrearchivo="resultado/".$_SESSION["ls_data_des"]."_siv_result_".$ld_fecha.".txt";
	$this->lo_archivo=@fopen("$ls_nombrearchivo","a+");
	$this->io_rcbsf			  = new sigesp_c_reconvertir_monedabsf(); 
	$this->li_candeccon= 4;
	$this->li_tipconmon= 1;
	$this->li_redconmon=1;
}


	function ue_copiar_siv_basico()
	{
		$lb_valido=true;
		$this->io_sql_destino->begin_transaction();	
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_configuracion();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_almacen();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_tipoarticulo();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_unidadmedida();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_articulo();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_insert_cargosxarticulos();
		}
	
		if($lb_valido)
		{
			$this->io_mensajes->message("La data de Inventario se copió correctamente.");
			$ls_cadena="La data de Inventario se copió correctamente.\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}
		else
		{
			$this->io_mensajes->message("Ocurrió un error al copiar la data de Inventario. Verifique el archivo txt."); 
		}
		if ($lb_valido)
		{
			$this->io_validacion->uf_insert_sistema_apertura('SIV');
			$this->io_sql_destino->commit();
		}
		else
		{
			$this->io_sql_destino->rollback();	
		}
		return $lb_valido;	
	}

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_configuracion()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_configuracion
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 28/12/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$li_total_select= 0;
		$li_total_insert= 0;
		$ls_sql="SELECT id, metodo, estcatsig, estnum, estcmp".
				"  FROM siv_config ";
		$io_recordset= $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar la configuracion.\r\n".$this->io_sql_origen->message."\r\n";
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
				$li_id= $this->io_validacion->uf_valida_monto($row["id"],0);
				$ls_metodo= $this->io_validacion->uf_valida_texto($row["metodo"],0,20,"");
				$li_estcatsig= $this->io_validacion->uf_valida_monto($row["estcatsig"],0);
				$li_estnum= $this->io_validacion->uf_valida_monto($row["estnum"],0);
				$li_estcmp= $this->io_validacion->uf_valida_monto($row["estcmp"],0);
				if($li_id!="")
				{
					$ls_sql="INSERT INTO siv_config(id, metodo, estcatsig, estnum, estcmp)".
							"	  VALUES (".$li_id.",'".$ls_metodo."',".$li_estcatsig.",'".$li_estnum."','".$li_estcmp."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar la configuracion.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en la Configuracion.\r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  siv_config Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino siv_config Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_configuracion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_almacen()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_configuracion
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 28/12/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$li_total_select= 0;
		$li_total_insert= 0;
		$ls_sql="SELECT codemp, codalm, nomfisalm, desalm, telalm, ubialm, nomresalm, telresalm".
				"  FROM siv_almacen ";
		$io_recordset= $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el almacen.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codalm= $this->io_validacion->uf_valida_texto($row["codalm"],0,10,"");
				$ls_nomfisalm= $this->io_validacion->uf_valida_texto($row["nomfisalm"],0,254,"");
				$ls_desalm= $this->io_validacion->uf_valida_texto($row["desalm"],0,254,"");
				$ls_telalm= $this->io_validacion->uf_valida_texto($row["telalm"],0,20,"");
				$ls_ubialm= $this->io_validacion->uf_valida_texto($row["ubialm"],0,254,"");
				$ls_nomresalm= $this->io_validacion->uf_valida_texto($row["nomresalm"],0,60,"");
				$ls_telresalm= $this->io_validacion->uf_valida_texto($row["telresalm"],0,20,"");
				if($ls_codalm!="")
				{
					$ls_sql="INSERT INTO siv_almacen(codemp, codalm, nomfisalm, desalm, telalm, ubialm, nomresalm, telresalm)".
							"	  VALUES ('".$ls_codemp."','".$ls_codalm."','".$ls_nomfisalm."','".$ls_desalm."','".$ls_telalm."',".
							"             '".$ls_ubialm."','".$ls_nomresalm."','".$ls_telresalm."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el almacen.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los Almacenes.\r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  siv_almacen Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino siv_almacen Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_almacen
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_tipoarticulo()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_tipoarticulo
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 28/12/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$li_total_select= 0;
		$li_total_insert= 0;
		$ls_sql="SELECT codtipart, dentipart, obstipart".
				"  FROM siv_tipoarticulo ";
		$io_recordset= $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el almacen.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codtipart= $this->io_validacion->uf_valida_texto($row["codtipart"],0,4,"");
				$ls_dentipart= $this->io_validacion->uf_valida_texto($row["dentipart"],0,254,"");
				$ls_obstipart= $this->io_validacion->uf_valida_texto($row["obstipart"],0,1000,"");
				if($ls_codtipart!="")
				{
					$ls_sql="INSERT INTO siv_tipoarticulo(codtipart, dentipart, obstipart)".
							"	  VALUES ('".$ls_codtipart."','".$ls_dentipart."','".$ls_obstipart."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el almacen.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los Almacenes.\r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  siv_tipoarticulo Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino siv_tipoarticulo Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_tipoarticulo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_unidadmedida()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_unidadmedida
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 28/12/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$li_total_select= 0;
		$li_total_insert= 0;
		$ls_sql="SELECT codunimed, denunimed, unidad, obsunimed".
				"  FROM siv_unidadmedida ";
		$io_recordset= $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar la unidad de medida.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codunimed= $this->io_validacion->uf_valida_texto($row["codunimed"],0,4,"");
				$ls_denunimed= $this->io_validacion->uf_valida_texto($row["denunimed"],0,100,"");
				$li_unidad= $this->io_validacion->uf_valida_monto($row["unidad"],0);
				$ls_obsunimed= $this->io_validacion->uf_valida_texto($row["obsunimed"],0,4000,"");
				if($ls_codunimed!="")
				{
					$ls_sql="INSERT INTO siv_unidadmedida(codunimed, denunimed, unidad, obsunimed)".
							"	  VALUES ('".$ls_codunimed."','".$ls_denunimed."',".$li_unidad.",'".$ls_obsunimed."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar la unidad de medida.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en las Unidades de Medida.\r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  siv_unidadmedida Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino siv_unidadmedida Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_unidadmedida
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_articulo()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_articulo
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 28/12/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$li_total_select= 0;
		$li_total_insert= 0;
		$ls_sql="SELECT codemp, codart, denart, codtipart, codunimed, feccreart, obsart, exiart, exiiniart, minart, maxart,".
				"       reoart, prearta, preartb, preartc, preartd, fecvenart, codcatsig, spg_cuenta, sc_cuenta, pesart,".
				"       altart, ancart, proart, ultcosart, cosproart, fotart, serart, ubiart, docart, fabart, codmil ".
				"  FROM siv_articulo ";
		$io_recordset= $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar la unidad de medida.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codart=$this->io_validacion->uf_valida_texto($row["codart"],0,20,"");
				$ls_denart=$this->io_validacion->uf_valida_texto($row["denart"],0,254,"");
				$ls_codtipart=$this->io_validacion->uf_valida_texto($row["codtipart"],0,4,"");
				$ls_codunimed=$this->io_validacion->uf_valida_texto($row["codunimed"],0,4,"");
				$ld_feccreart=$this->io_validacion->uf_valida_fecha($row["feccreart"],"1900-01-01");
				$ls_obsart=$this->io_validacion->uf_valida_texto($row["obsart"],0,500,"");
				$li_exiart=$this->io_validacion->uf_valida_monto($row["exiart"],0);
				$li_exiiniart=$this->io_validacion->uf_valida_monto($row["exiiniart"],0);
				$li_minart=$this->io_validacion->uf_valida_monto($row["minart"],0);
				$li_maxart=$this->io_validacion->uf_valida_monto($row["maxart"],0);
				$li_reoart=$this->io_validacion->uf_valida_monto($row["reoart"],0);
				$li_prearta=$this->io_rcbsf->uf_convertir_monedabsf($row["prearta"],$this->li_candeccon,$this->li_tipconmon,1000,$this->li_redconmon);
				$li_preartb=$this->io_rcbsf->uf_convertir_monedabsf($row["preartb"],$this->li_candeccon,$this->li_tipconmon,1000,$this->li_redconmon);
				$li_preartc=$this->io_rcbsf->uf_convertir_monedabsf($row["preartc"],$this->li_candeccon,$this->li_tipconmon,1000,$this->li_redconmon);
				$li_preartd=$this->io_rcbsf->uf_convertir_monedabsf($row["preartd"],$this->li_candeccon,$this->li_tipconmon,1000,$this->li_redconmon);
				$ld_fecvenart=$this->io_validacion->uf_valida_fecha($row["fecvenart"],"1900-01-01");
				$ls_codcatsig=$this->io_validacion->uf_valida_texto($row["codcatsig"],0,15,"");
				$ls_spg_cuenta=$this->io_validacion->uf_valida_texto($row["spg_cuenta"],0,25,"");
				$ls_sc_cuenta=$this->io_validacion->uf_valida_texto($row["sc_cuenta"],0,25,"");
				$li_pesart=$this->io_validacion->uf_valida_monto($row["pesart"],0);
				$li_altart=$this->io_validacion->uf_valida_monto($row["altart"],0);
				$li_ancart=$this->io_validacion->uf_valida_monto($row["ancart"],0);
				$li_proart=$this->io_validacion->uf_valida_monto($row["proart"],0);
				$li_ultcosart=$this->io_rcbsf->uf_convertir_monedabsf($row["ultcosart"],$this->li_candeccon,$this->li_tipconmon,1000,$this->li_redconmon);
				$li_cosproart=$this->io_rcbsf->uf_convertir_monedabsf($row["cosproart"],$this->li_candeccon,$this->li_tipconmon,1000,$this->li_redconmon);
				$ls_foto= $row["fotart"];
				$li_preartaaux=$row["prearta"];
				$li_preartbaux=$row["preartb"];
				$li_preartcaux=$row["preartc"];
				$li_preartdaux=$row["preartd"];
				$li_ultcosartaux=$row["ultcosart"];
				$li_cosproartaux=$row["cosproart"];
				$ls_serart=$this->io_validacion->uf_valida_texto($row["serart"],0,25,"");
				$ls_ubiart=$this->io_validacion->uf_valida_texto($row["ubiart"],0,10,"");
				$ls_docart=$this->io_validacion->uf_valida_texto($row["docart"],0,20,"");
				$ls_fabart=$this->io_validacion->uf_valida_texto($row["fabart"],0,100,"");
				$ls_codmil=$this->io_validacion->uf_valida_texto($row["codmil"],0,15,"");
				if($ls_codart!="")
				{
					$ls_sql="INSERT INTO siv_articulo(codemp, codart, denart, codtipart, codunimed, feccreart, obsart, exiart,".
							"                         exiiniart, minart, maxart, reoart, prearta, preartb, preartc, preartd,".
							"                         fecvenart, codcatsig, spg_cuenta, sc_cuenta, pesart, altart, ancart,".
							"                         proart, ultcosart, cosproart, fotart, preartaaux, preartbaux, preartcaux,".
							"                         preartdaux, ultcosartaux, cosproartaux, serart, ubiart, docart, fabart,".
							"                         codmil)".
							"	  VALUES ('".$ls_codemp."','".$ls_codart."','".$ls_denart."','".$ls_codtipart."',".
							"			  '".$ls_codunimed."','".$ld_feccreart."','".$ls_obsart."',".$li_exiart.",".
							"			  ".$li_exiiniart.",".$li_minart.",".$li_maxart.",".$li_reoart.",".$li_prearta.",".
							"             ".$li_preartb.",".$li_preartc.",".$li_preartd.",'".$ld_fecvenart."','".$ls_codcatsig."',".
							"             '".$ls_spg_cuenta."','".$ls_sc_cuenta."',".$li_pesart.",".$li_altart.",".
							"             ".$li_ancart.",".$li_proart.",".$li_ultcosart.",".$li_cosproart.",'".$ls_foto."',".
							"             ".$li_preartaaux.",".$li_preartbaux.",".$li_preartcaux.",".$li_preartdaux.",".
							"             ".$li_ultcosartaux.",".$li_cosproartaux.",'".$ls_serart."','".$ls_ubiart."',".
							"             '".$ls_docart."','".$ls_fabart."','".$ls_codmil."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el articulo.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los Articulos.\r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  siv_articulo Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino siv_articulo Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_articulo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_cargosxarticulos()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cargosxarticulos
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 28/12/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$li_total_select= 0;
		$li_total_insert= 0;
		$ls_sql="SELECT codemp, codart, codcar".
				"  FROM siv_cargosarticulo ";
		$io_recordset= $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar el cargo por articulo.\r\n".$this->io_sql_origen->message."\r\n";
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
				$ls_codart= $this->io_validacion->uf_valida_texto($row["codart"],0,20,"");
				$ls_codcar= $this->io_validacion->uf_valida_texto($row["codcar"],0,5,"");
				if($ls_codart!="")
				{
					$ls_sql="INSERT INTO siv_cargosarticulo(codemp, codart, codcar)".
							"	  VALUES ('".$ls_codemp."','".$ls_codart."','".$ls_codcar."')";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar el cargo por articulo.\r\n".$this->io_sql_destino->message."\r\n";
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
					$ls_cadena="Hay data inconsistente en los Cargos por Articulos.\r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   Tabla Origen  siv_cargosarticulo Registros ".$li_total_select." \r\n";
			$ls_cadena=$ls_cadena."   Tabla Destino siv_cargosarticulo Registros ".$li_total_insert." \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_cargosxarticulos
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
	}// end function uf_copiar_tabla


function uf_crea_siv_comprobante()
{	
		$lb_valido=true;
	// Prepara cabecera del comprobante
	$ls_comprobante = "0000000APERTURA";
	$ls_fecha		= "2007-01-01";
	$ls_usuario		= $_SESSION["la_logusr"];
	$ls_solicitante = "Apertura";

	$ls_sql_comprobante= " INSERT INTO ".$this->ls_dabatase_target.".siv_movimiento (nummov,fecmov,nomsol,codusu) ".
						 " VALUES ('".$ls_comprobante."','".$ls_fecha."','".$ls_usuario."','".$ls_solicitante."') ";
	$io_recordset=$this->io_sql->Execute($ls_sql_comprobante);
	if($io_recordset===false)
	{ 
		$lb_valido=false;
		$ls_cadena="Problema al crear Comprobante de Apertura.\r\n".$this->io_sql->message."\r\n";
		$ls_cadena=$ls_cadena.$ls_sql_comprobante."\r\n";
		if ($this->lo_archivo)			
		{
			@fwrite($this->lo_archivo,$ls_cadena);
		}
	}
	else
	{			
		$ls_cadena =			  "//*****************************************************************//\r\n";
		$ls_cadena = $ls_cadena."   Creada cabecera de comprobante de apertura de inventario  		\r\n";
		$ls_cadena = $ls_cadena."//*****************************************************************//\r\n";
		if ($this->lo_archivo)			
		{
			@fwrite($this->lo_archivo,$ls_cadena);
		}
	}	
	$ls_sql_comprobante="INSERT ".$this->ls_dabatase_target.".siv_dt_movimiento (codemp, nummov, fecmov, codart, codalm, opeinv, codprodoc, ".
	                    "                                          numdoc, canart, cosart, promov, numdocori, candesart, fecdesart)".
						"SELECT codemp,'0000000APERTURA' nunmov,'2007-01-01' fecmov,codart,codalm,'ENT' opeinv,'APR' codprodoc,".
						"       '0000000APERTURA' numdoc,existencia,(SELECT ultcosart".
                        "                                              FROM ".$this->ls_database_source.".siv_articulo".
                        "                                             WHERE siv_articuloalmacen.codemp=siv_articulo.codemp".
						"                                               AND siv_articuloalmacen.codart=siv_articulo.codart),".
						"       'APE'promov,'0000000APERTURA',existencia,'2007-01-01' fecdesart".
                        "  FROM ".$this->ls_database_source.".siv_articuloalmacen".
                        " GROUP BY codemp,codart,codalm";

/*	$ls_sql_comprobante= 	"INSERT INTO ".$this->ls_dabatase_target.".siv_dt_movimiento (codemp,nummov,fecmov,codart,codalm,opeinv,codprodoc,numdoc,canart,cosart,promov,numdocori) ".
							" VALUES ( ".
							" SELECT	codemp,".$ls_comprobante.",".$ls_fecha.",codart,codalm,'ENT', 'ALM', ".$ls_comprobante.",canart,cosart,'RPC',".$ls_comprobante." ".
							"   FROM ( ".
							"SELECT curAlm.codemp,art.codart,art.exiart AS canart,art.ultcosart AS cosart,curAlm.codalm ".
							"FROM ".$this->ls_database_source.".siv_articulo art,(SELECT codemp,codart,codalm ".
							"  	FROM siv_dt_movimiento ".
							"  	GROUP BY codemp,codalm,codart ".
							"  	ORDER BY codemp,codalm,codart) AS curAlm ".
							"WHERE art.codart=curAlm.codart) AS curTodo )";
*/
	$io_recordset=$this->io_sql->Execute($ls_sql_comprobante);
	if($io_recordset===false)
	{ 
		$lb_valido=false;
		$ls_cadena="Problema al crear Detalle del Comprobante de Apertura.\r\n".$this->io_sql->message."\r\n";
		$ls_cadena=$ls_cadena.$ls_sql_comprobante."\r\n";
		if ($this->lo_archivo)			
		{
			@fwrite($this->lo_archivo,$ls_cadena);
		}
	}
	else
	{			
		$ls_cadena =			"//*****************************************************************//\r\n";
		$ls_cadena = $ls_cadena."   Creado Detalle del Comprobante de apertura de inventario  		\r\n";
		$ls_cadena = $ls_cadena."//*****************************************************************//\r\n";
		if ($this->lo_archivo)			
		{
			@fwrite($this->lo_archivo,$ls_cadena);
		}
	}	
	return $lb_valido;
}


function ue_limpiar_siv_basico()
{
	$lb_valido=true;
	$this->io_sql_destino->begin_transaction();
	//------------------------------------ Borrar tablas de banco -----------------------------------------
	if($lb_valido)	
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('siv_cargosarticulo',"  ");			
		}	
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('siv_articulo',"  ");			
		}
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('siv_tipoarticulo',"  ");			
		}
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('siv_unidadmedida',"  ");			
		}
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('siv_config',"  ");			
		}
		if($lb_valido)		
		{
			$lb_valido=$this->uf_limpiar_tabla('siv_almacen',"  ");			
		}
		
	if($lb_valido)    
	{
		$this->io_mensajes->message("La data de Inventario se borró correctamente.");
		$ls_cadena="La data de Inventario se borró correctamente.\r\n";
		if ($this->lo_archivo)
		{
			@fwrite($this->lo_archivo,$ls_cadena);
		}
	}
	else
	{
		$this->io_mensajes->message("Ocurrió un error al copiar la data de Inventario. Verifique el archivo txt."); 
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
			$ls_cadena="Error al Borrar la tabla".$as_tabla.".\r\n".$this->io_sql->message."\r\n";
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