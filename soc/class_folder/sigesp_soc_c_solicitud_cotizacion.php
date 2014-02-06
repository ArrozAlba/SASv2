<?php
class sigesp_soc_c_solicitud_cotizacion
{
  function sigesp_soc_c_solicitud_cotizacion($as_path)
  {
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: sigesp_soc_c_solicitud_cotizacion
	//		   Access: public 
	//	  Description: Constructor de la Clase
	//	   Creado Por: Ing. Néstor Falcón.
	// Fecha Creación: 12/04/2007 								Fecha Última Modificación : 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        require_once($as_path."shared/class_folder/sigesp_include.php");
		require_once($as_path."shared/class_folder/class_sql.php");
	    require_once($as_path."shared/class_folder/class_fecha.php");		
		require_once($as_path."shared/class_folder/class_funciones.php");
		require_once($as_path."shared/class_folder/sigesp_c_seguridad.php");
		require_once($as_path."shared/class_folder/class_mensajes.php");
		require_once($as_path."shared/class_folder/sigesp_c_generar_consecutivo.php");
		$io_include			 = new sigesp_include();
		$io_conexion		 = $io_include->uf_conectar();
		$this->io_sql        = new class_sql($io_conexion);	
		$this->io_mensajes   = new class_mensajes();		
		$this->io_funciones  = new class_funciones();	
		$this->io_seguridad  = new sigesp_c_seguridad();
		$this->io_fecha      = new class_fecha();
		$this->ls_codemp     = $_SESSION["la_empresa"]["codemp"]; 
		$this->io_keygen     = new sigesp_c_generar_consecutivo();
  }

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,&$as_numsolcot,$as_tipsolcot,$as_obssolcot,$as_consolcot,$as_uniejeaso,$ad_fecregsolcot,
	                    $as_codunieje,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_estcla,
						$as_cedpersol,$as_codcarper,$ai_totrowbienes,$ai_totrowservicios,$ai_totrowsep,$ai_totrowproveedores,
						$as_telpersol,$as_faxpersol,$as_tipbiesol,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_soc_p_solicitud_cotizacion.php)
		//	    Arguments: as_existe             // Fecha de Solicitud
		//				   as_numsolcot          // Número de la Solicitud de Cotizacion.
		//				   as_tipsolcot          // Tipo de la Solicitud de Cotizacion.
		//				   as_obssolcot          // Observación de la Solicitud de Cotización.
		//				   ad_fecregsolcot  	 // Fecha de Registro de la Solicitud de Cotización.
		//				   as_codunieje 		 // Codigo de Unidad Ejecutora.
		//				   as_cedpersol  		 // Cédula del Personal Silicitante de la Base de Datos.
		//				   ai_totrowbienes  	 // Total de Filas de Bienes
		//				   ai_totrowservicios  	 // Total de Filas de Servicios
		//				   ai_totrowsep          // Total de Filas de Solicitudes de Ejecución Presupuestaria.
		//				   ai_totrowproveedores  // Total de Filas de Proveedores.
		//				   as_telpersol          // Teléfono de la Persona Solicitante.
		//				   as_faxpersol          // Fax del Solicitante.
		//				   aa_seguridad          // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que valida y guarda la sep
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 04/05/2007 								Fecha Última Modificación : 16/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido     = false;	
		$lb_encontrado = $this->uf_select_solicitud_cotizacion($as_numsolcot);
		switch($as_tipsolcot)
		{
			case "B": // si es de Bienes
				$ls_tabla="soc_dtsc_bienes";
				$ls_campo="codart";
				break;
			case "S": // si es de Servicios
				$ls_tabla="soc_dtsc_servicios";
				$ls_campo="codser";
				break;
		}
		$ad_fecregsolcot = $this->io_funciones->uf_convertirdatetobd($ad_fecregsolcot);
		
		switch ($as_existe)
		{
			case "FALSE":
					$lb_valido=$this->uf_validar_fecha_solicitud_cotizacion($ad_fecregsolcot);
					if(!$lb_valido)
					{
						$this->io_mensajes->message("La Fecha de esta Solicitud es menor a la fecha de la Solicitud anterior.");
						return false;
					}
					$lb_valido=$this->io_fecha->uf_valida_fecha_periodo($ad_fecregsolcot,$this->ls_codemp);
					if (!$lb_valido)
					{
						$this->io_mensajes->message($this->io_fecha->is_msg_error);           
						return false;
					}                    
					$lb_valido=$this->uf_insert_solicitud_cotizacion($ad_fecregsolcot,$as_numsolcot,$as_codunieje,$as_codestpro1,$as_codestpro2,$as_codestpro3,
					                                                 $as_codestpro4,$as_codestpro5,$as_estcla,$as_cedpersol,$as_codcarper,$as_telpersol,
					                                                 $as_faxpersol,$as_tipsolcot,$as_obssolcot,$as_consolcot,$as_uniejeaso,$ai_totrowbienes,
																	 $ai_totrowservicios,$ai_totrowsep,$ai_totrowproveedores,$ls_tabla,$ls_campo,$as_tipbiesol,$aa_seguridad);
				
				break;

			case "TRUE":
				if($lb_encontrado)
				{
					$lb_valido=$this->uf_update_solicitud_cotizacion($ad_fecregsolcot,$as_numsolcot,$as_codunieje,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
	                                                                 $as_codestpro5,$as_estcla,$as_cedpersol,$as_codcarper,$as_telpersol,
					                                                 $as_faxpersol,$as_tipsolcot,$as_obssolcot,$as_consolcot,$as_uniejeaso,$ai_totrowbienes,
																	 $ai_totrowservicios,$ai_totrowsep,$ai_totrowproveedores,$ls_tabla,$ls_campo,$as_tipbiesol,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("La Solicitud no existe, no la puede actualizar !!!");
				}
				break;
		}
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_solicitud_cotizacion($ad_fecregsolcot,&$as_numsolcot,$as_codunieje,$as_codestpro1,$as_codestpro2,$as_codestpro3,
	                                        $as_codestpro4,$as_codestpro5,$as_estcla,$as_cedpersol,$as_codcarper,$as_telpersol,$as_faxpersol,
					                        $as_tipsolcot,$as_obssolcot,$as_consolcot,$as_uniejeaso,$ai_totrowbienes,$ai_totrowservicios,
											$ai_totrowsep,$ai_totrowproveedores,$as_tabla,$as_campo,$as_tipbiesol,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_solicitud
		//		   Access: private
		//	    Arguments: ad_fecregsol         // Fecha de Solicitud
		//				   as_numsolcot         // Número de Solicitud de Cotizacion.
		//				   as_codunije          // Codigo de Unidad Ejecutora.
		//				   as_codprov           // Código de Proveedor 
		//				   as_cedben            // Código de Beneficiario
		//				   as_obssolcot         // Observación de la Solicitud
		//				   as_consolcot         // Concepto de la Solicitud
		//				   as_codtipsol         // Código Tipo de solicitud
		//				   ai_total             // Total de la solicitud
		//				   ai_totrowbienes      // Total de Filas de Bienes
		//				   ai_totrowservicios   // Total de Filas de Servicios
		//				   ai_totrowsep         // Total de Filas de Conceptos
		//				   ai_totrowproveedores // Total de Filas de Conceptos
		//				   as_tabla             // Tabla donde se deben insertar los cargos
		//				   as_campo             // Campo donde se inserta el codigo del Bien, Servicio ó Concepto
		//				   aa_seguridad         // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta la solicitud de Ejecución Presupuestaria
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 03/05/2007 								Fecha Última Modificación : 03/05/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $ls_numsolaux=$as_numsolcot;
		$lb_valido = $this->io_keygen->uf_verificar_numero_generado('SOC','soc_sol_cotizacion','numsolcot','SOCSOL',15,"","","",&$as_numsolcot);
		$lb_valido = true;
		$ls_codusu = $aa_seguridad["logusr"];
		if (empty($as_obssolcot))
		   {
		     $as_obssolcot='N/A';
		   }
		if($lb_valido)
		{
			$ls_sql="INSERT INTO soc_sol_cotizacion (codemp,numsolcot,fecsol,obssol,estcot,codusu,cedper,codcar,soltel,solfax,coduniadm,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,estcla,tipsolcot,consolcot,uniejeaso,tipsolbie)".
					"	  VALUES ('".$this->ls_codemp."','".$as_numsolcot."','".$ad_fecregsolcot."','".$as_obssolcot."','R','".$ls_codusu."','".$as_cedpersol."',".
					"             '".$as_codcarper."','".$as_telpersol."','".$as_faxpersol."','".$as_codunieje."','".$as_codestpro1."','".$as_codestpro2."',
								  '".$as_codestpro3."','".$as_codestpro4."','".$as_codestpro5."','".$as_estcla."',
					              '".$as_tipsolcot."','".$as_consolcot."','".$as_uniejeaso."','".$as_tipbiesol."')";
			$this->io_sql->begin_transaction();
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
			    $this->io_sql->rollback();
				if($this->io_sql->errno=='23505' || $this->io_sql->errno=='1062' || $this->io_sql->errno=='-5' || $this->io_sql->errno=='-1')
				{
					$lb_valido=$this->uf_insert_solicitud_cotizacion($ad_fecregsolcot,$as_numsolcot,$as_codunieje,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
																	 $as_codestpro5,$as_estcla,$as_cedpersol,$as_codcarper,$as_telpersol,$as_faxpersol,
					                        			             $as_tipsolcot,$as_obssolcot,$as_consolcot,$as_uniejeaso,$ai_totrowbienes,$ai_totrowservicios,
														             $ai_totrowsep,$ai_totrowproveedores,$as_tabla,$as_campo,$as_tipbiesol,$aa_seguridad);
				}
				else
				{
				  $lb_valido=false;
				  $this->io_mensajes->message("CLASE->sigesp_soc_c_solicitud_cotizacion.php->MÉTODO->uf_insert_solicitud_cotizacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				  print $this->io_sql->message;
				}
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la solicitud de Cotizacion ".$as_numsolcot." Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				if ($as_tipsolcot=='B')
				   {
				     if ($lb_valido)
				        {	
					      $lb_valido = $this->uf_insert_bienes($as_numsolcot,$ai_totrowbienes,$ai_totrowproveedores,$aa_seguridad);
				        }			
				   }
				elseif($as_tipsolcot=='S')
				   {
				     if ($lb_valido)
				        {	
					      $lb_valido = $this->uf_insert_servicios($as_numsolcot,$ai_totrowservicios,$ai_totrowproveedores,$aa_seguridad);
				        }			
				   }
				if($lb_valido)//Se almacenan Las Solicitudes de Ejecución Presupuestarias asociadas a una Solicitud de Cotización.
				{	
					$lb_valido=$this->uf_insert_solicitudes_presupuestarias($as_numsolcot,$ai_totrowsep,$as_tabla,$aa_seguridad);
				}			
				if($lb_valido)
				{	
					if($ls_numsolaux!=$as_numsolcot)
					{
						$this->io_mensajes->message("Se Asigno el Numero de Solicitud de Cotización: ".$as_numsolcot);
					}
					$this->io_mensajes->message("La Solicitud fue registrada !!!");
					$this->io_sql->commit();
				}
				else
				{
					$lb_valido=false;
					$this->io_mensajes->message("Ocurrio un Error al Registrar la Solicitud !!!"); 
					$this->io_sql->rollback();
				}
			}
		}
		return $lb_valido;
	}// end function uf_insert_solicitud_cotizacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_solicitud_cotizacion($ad_fecregsolcot,$as_numsolcot,$as_codunieje,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
	                                        $as_codestpro5,$as_estcla,$as_cedpersol,$as_codcarper,$as_telpersol,$as_faxpersol,
					                        $as_tipsolcot,$as_obssolcot,$as_consolcot,$as_uniejeaso,$ai_totrowbienes,$ai_totrowservicios,
											$ai_totrowsep,$ai_totrowproveedores,$as_tabla,$ls_campo,$as_tipbiesol,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_solicitud
		//		   Access: private
		//	    Arguments: as_numsol  // Número de Solicitud 
		//				   as_coduniadm  // Codigo de Unidad Administrativa
		//				   as_codprov  // Código de Proveedor 
		//				   as_cedben  // Código de Beneficiario
		//				   as_consol  // Concepto de la Solicitud
		//				   as_codtipsol  // Código Tipo de solicitud
		//				   ai_totrowbienes  // Total de Filas de Bienes
		//				   ai_totrowcargos  // Total de Filas de Servicios
		//				   ai_totrowcuentas  // Total de Filas de Cuentas
		//				   ai_totrowcuentascargo  // Total de Filas de Cuentas Cargos
		//				   as_tabla  // Tabla donde se deben insertar los cargos
		//				   as_campo  // Campo donde se inserta el codigo del Bien, Servicio ó Concepto
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que actualiza la solicitud de Ejecución Presupuestaria
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = true;
		$ls_codusu = $aa_seguridad["logusr"];
		$ls_sql="UPDATE soc_sol_cotizacion ".
                "   SET obssol = '".$as_obssolcot."',     ".
				"    consolcot = '".$as_consolcot."',     ".
				"    uniejeaso = '".$as_uniejeaso."',     ".
				"	    codusu = '".$ls_codusu."',        ". 
				"		cedper = '".$as_cedpersol."',     ". 
			    "		codcar = '".$as_codcarper."',     ".  
			    "		soltel = '".$as_telpersol."',     ". 
		        "		solfax = '".$as_faxpersol."',     ". 
	            "		coduniadm = '".$as_codunieje."',  ".
				"	   codestpro1 = '".$as_codestpro1."', ".
				"	   codestpro2 = '".$as_codestpro2."', ".
				"	   codestpro3 = '".$as_codestpro3."', ".
				"	   codestpro4 = '".$as_codestpro4."', ".
				"	   codestpro5 = '".$as_codestpro5."', ".
	 	     	"	   estcla = '".$as_estcla."'          ".
				" WHERE codemp='".$this->ls_codemp."'     ".
				"   AND numsolcot='".$as_numsolcot."'";			
		$this->io_sql->begin_transaction();		
		$rs_data = $this->io_sql->execute($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->sigesp_soc_c_solicitud_cotizacion.php->MÉTODO->uf_update_solicitud_cotizacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la Solicitud de Cotizacion ".$as_numsolcot." Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if ($lb_valido)
			   {
		         $lb_valido=$this->uf_delete_detalles($as_numsolcot,$as_tipsolcot,$ai_totrowsep,$as_tabla,$aa_seguridad);
				 if ($as_tipsolcot=='B')
	                {
				      if ($lb_valido)
					     {
					       $lb_valido = $this->uf_insert_bienes($as_numsolcot,$ai_totrowbienes,$ai_totrowproveedores,$aa_seguridad);
						 }
		            }
				 elseif($as_tipsolcot=='S')
				   {
				      if ($lb_valido)
					     {
					       $lb_valido = $this->uf_insert_servicios($as_numsolcot,$ai_totrowservicios,$ai_totrowproveedores,$aa_seguridad);
				         }
				   }
		       }	
			if ($lb_valido)
			   {	
				$lb_valido=$this->uf_insert_solicitudes_presupuestarias($as_numsolcot,$ai_totrowsep,$as_tabla,$aa_seguridad);
			   }			
			if($lb_valido)
			{	
				$this->io_mensajes->message("La Solicitud fue actualizada !!!");
				$this->io_sql->commit();
				$this->io_sql->close();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("Ocurrio un Error al Actualizar la Solicitud !!!"); 
				$this->io_sql->rollback();
				$this->io_sql->close();
			}
		}
		return $lb_valido;
	}// end function uf_update_solicitud_cotizacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//----------------------------------------------------
	function uf_insert_bienes($as_numsolcot,$ai_totrowbienes,$ai_totrowproveedores,$aa_seguridad)
	{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_insert_bienes
	//		   Access: private
	//	    Arguments: as_numsolcot     // Número de Solicitud de Cotizacion.
	//           $ai_totrowproveedores  // Total de Filas de Proveedores.
	//				   ai_totrowbienes  // Total de Filas de Bienes
	//				   aa_seguridad     // arreglo de las variables de seguridad
	//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
	//	  Description: Funcion que inserta los bienes de una  Solicitud de Cotizacion.
	//	   Creado Por: Ing. Nestor Falcón.
	// Fecha Creación: 06/05/2007 								Fecha Última Modificación : 06/05/2007
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
			$lb_valido=true;
			for ($y=1;$y<$ai_totrowproveedores;$y++)
				{
				  $ls_codpro = $_POST["txtcodpro".$y];
				  if (!empty($ls_codpro))
					 {
					   for ($i=1;($i<$ai_totrowbienes)&&($lb_valido);$i++)
						   {
							 $ls_codart     = $_POST["txtcodart".$i];
							 $ld_canart     = $_POST["txtcanart".$i];
							 $ld_canart     = str_replace('.','',$ld_canart);
							 $ld_canart     = str_replace(',','.',$ld_canart);
							 $ls_uniart     = "D";
							 $ls_numsep     = $_POST["hidnumsep".$i];
							 $ls_codunieje  = $_POST["hidcodunieje".$i];
							 $ls_codestpro  = $_POST["hidcodestpro".$i];
							 $ls_codestpro1 = substr($ls_codestpro,0,25);
							 $ls_codestpro2 = substr($ls_codestpro,25,25);
							 $ls_codestpro3 = substr($ls_codestpro,50,25);
							 $ls_codestpro4 = substr($ls_codestpro,75,25);
							 $ls_codestpro5 = substr($ls_codestpro,100,25);
							 $ls_estcla     = $_POST["estcla".$i];
							 
							 $ls_sql    = " INSERT INTO soc_dtsc_bienes (codemp, numsolcot, codart, cod_pro, unidad, canart, orden, numsep,coduniadm,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,estcla) ".
										  " VALUES ('".$this->ls_codemp."','".$as_numsolcot."','".$ls_codart."','".$ls_codpro."','".$ls_uniart."',".$ld_canart.",".$i.",
										            '".$ls_numsep."','".$ls_codunieje."','".$ls_codestpro1."','".$ls_codestpro2."','".$ls_codestpro3."','".$ls_codestpro4."','".$ls_codestpro5."','".$ls_estcla."')";
							 $rs_data = $this->io_sql->execute($ls_sql);
							 if ($rs_data===false)
								{
									$lb_valido=false;
									$this->io_mensajes->message("CLASE->sigesp_soc_c_solicitud_cotizacion.php->MÉTODO->uf_insert_bienes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
								}
								else
								{
									$lb_valido = $this->uf_update_estatus_incorporacion('B',$as_numsolcot,$ls_numsep,$ls_codart,'INSERT');
									if ($lb_valido)
									   {
										 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
										 $ls_evento="INSERT";
										 $ls_descripcion ="Insertó el Articulo ".$ls_codart." a la Solicitud de Cotizacion ".$as_numsolcot." Asociado a la empresa ".$this->ls_codemp;
										 $lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
																		$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
																		$aa_seguridad["ventanas"],$ls_descripcion);
										 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
									   }
								}
						   }
					 }  
				}
	 return $lb_valido;
	}// end function uf_insert_bienes.
	//----------------------------------------------------

	//----------------------------------------------------
	function uf_insert_servicios($as_numsolcot,$ai_totrowservicios,$ai_totrowproveedores,$aa_seguridad)
	{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_insert_servicios
	//		   Access: private
	//	    Arguments: as_numsolcot        // Número de Solicitud de Cotizacion.
	//              $ai_totrowproveedores  // Total de Filas de Proveedores.
	//				   ai_totrowservicios  // Total de Filas de Servicios.
	//				   aa_seguridad        // arreglo de las variables de seguridad
	//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
	//	  Description: Funcion que inserta los Servicios de una  Solicitud de Cotizacion.
	//	   Creado Por: Ing. Nestor Falcón.
	// Fecha Creación: 06/05/2007 								Fecha Última Modificación : 06/05/2007
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
			$lb_valido=true;
			for ($y=1;$y<$ai_totrowproveedores;$y++)
				{
				  $ls_codpro = $_POST["txtcodpro".$y];
				  if (!empty($ls_codpro))
					 {
					   for ($i=1;($i<$ai_totrowservicios)&&($lb_valido);$i++)
						   {
							 $ls_codser     = $_POST["txtcodser".$i];
							 $ld_canser     = $_POST["txtcanser".$i];
							 $ld_canser     = str_replace('.','',$ld_canser);
							 $ld_canser     = str_replace(',','.',$ld_canser);
    					     $ls_numsep     = $_POST["hidnumsep".$i];
							 $ls_codunieje  = $_POST["hidcodunieje".$i];
							 $ls_codestpro  = $_POST["hidcodestpro".$i];
							 $ls_codestpro1 = substr($ls_codestpro,0,25);
							 $ls_codestpro2 = substr($ls_codestpro,25,25);
							 $ls_codestpro3 = substr($ls_codestpro,50,25);
							 $ls_codestpro4 = substr($ls_codestpro,75,25);
							 $ls_codestpro5 = substr($ls_codestpro,100,25);
							 $ls_estcla     = $_POST["estcla".$i];

							 $ls_sql    = " INSERT INTO soc_dtsc_servicios (codemp, numsolcot, codser, cod_pro, canser, orden, numsep, coduniadm,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,estcla) ".
										  " VALUES ('".$this->ls_codemp."','".$as_numsolcot."','".$ls_codser."','".$ls_codpro."',".$ld_canser.",".$i.",'".$ls_numsep."','".$ls_codunieje."','".$ls_codestpro1."',
										            '".$ls_codestpro2."','".$ls_codestpro3."','".$ls_codestpro4."','".$ls_codestpro5."','".$ls_estcla."')";
							 $rs_data = $this->io_sql->execute($ls_sql);
							 if ($rs_data===false)
								{
									$lb_valido=false;
									$this->io_mensajes->message("CLASE->sigesp_soc_c_solicitud_cotizacion.php->MÉTODO->uf_insert_servicios ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
								}
								else
								{
							      $lb_valido = $this->uf_update_estatus_incorporacion('S',$as_numsolcot,$ls_numsep,$ls_codser,'INSERT');
								  if ($lb_valido)
									 {
									   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
									   $ls_evento="INSERT";
									   $ls_descripcion ="Insertó el Servicio ".$ls_codser." a la Solicitud de Cotizacion ".$as_numsolcot." Asociado a la empresa ".$this->ls_codemp;
									   $lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
																		$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
																		$aa_seguridad["ventanas"],$ls_descripcion);
										/////////////////////////////////         SEGURIDAD               /////////////////////////////		
								     }
								}
						   }
					 }  
				}
	 return $lb_valido;
	}// end function uf_insert_servicios.
	//----------------------------------------------------

    function uf_insert_solicitudes_presupuestarias($as_numsolcot,$ai_totrowsep,$as_tabla,$aa_seguridad)
	{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_insert_servicios
	//		   Access: private
	//	    Arguments: $as_numsolcot
	//                 $ai_totrowsep
	//                 $aa_seguridad
	//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
	//	  Description: Funcion que inserta los Servicios de una  Solicitud de Cotizacion.
	//	   Creado Por: Ing. Nestor Falcón.
	// Fecha Creación: 06/05/2007 								Fecha Última Modificación : 06/05/2007
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		$lb_valido=true;
		for ($i=1;$i<$ai_totrowsep;$i++)
			{
			  $ls_unieje     = $_POST["txtunieje".$i];
			  $ls_numsep     = $_POST["txtnumsep".$i];
			  $ls_estcla     = $_POST["estcla".$i];
			  $ls_codestpro  = $_POST["hidcodestpro".$i];
			  $ls_codestpro1 = substr($ls_codestpro,0,25);
			  $ls_codestpro2 = substr($ls_codestpro,25,25);
			  $ls_codestpro3 = substr($ls_codestpro,50,25);
			  $ls_codestpro4 = substr($ls_codestpro,75,25);
			  $ls_codestpro5 = substr($ls_codestpro,100,25);
			  
			  if (!empty($ls_numsep))
				 {
				   $ls_sql  = " INSERT INTO soc_solcotsep (codemp, numsolcot, numsol, codunieje, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, estcla) 
				                                   VALUES ('".$this->ls_codemp."','".$as_numsolcot."','".$ls_numsep."','".$ls_unieje."','".$ls_codestpro1."','".$ls_codestpro2."','".$ls_codestpro3."','".$ls_codestpro4."','".$ls_codestpro5."','".$ls_estcla."')";
				   $rs_data = $this->io_sql->execute($ls_sql);
				   if ($rs_data===false)
					  {
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->sigesp_soc_c_solicitud_cotizacion.php->MÉTODO->uf_insert_solicitudes_presupuestarias; ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
					  }
				   else
					  {
				        $lb_valido = $this->uf_update_estatus_sep('INSERT',$ls_numsep,$as_tabla);
				        if ($lb_valido)
					       {
							 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
							 $ls_evento="INSERT";
							 $ls_descripcion ="Insertó SEP  ".$ls_numsep." a la Solicitud de Cotizacion ".$as_numsolcot." Asociado a la empresa ".$this->ls_codemp;
							 $lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
															 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
															 $aa_seguridad["ventanas"],$ls_descripcion);
							 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
						   }
					  }
				 }  
			}
	      return $lb_valido;
	}// end function uf_insert_solicitudes_presupuestarias.

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_solicitud_cotizacion($as_numsolcot)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_solicitud_cotizacion
		//		   Access: private
		//	    Arguments: as_numsolcot  //  Número de Solicitud
		// 	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si la Solicitud de Cotización Existe.
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT numsolcot                     ".
				"  FROM soc_sol_cotizacion            ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numsolcot='".$as_numsolcot."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->sigesp_soc_c_solicitud_cotizacion.php->MÉTODO->uf_select_solicitud_cotizacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if(!$row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=false;
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_existe;
	}// end function uf_select_solicitud_cotizacion
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_validar_fecha_solicitud_cotizacion($ad_fecregsolcot)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_fecha_solicitud_cotizacion
		//		   Access: private
		//		 Argument: $ad_fecregsolcot // Fecha de registro de la nueva Solicitud de Cotización.
		//	  Description: Función que busca la fecha de la última Solicitud de Cotizacion y la compara con la fecha actual.
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT numsolcot,fecsol              ".
				"  FROM soc_sol_cotizacion            ".
				" WHERE codemp='".$this->ls_codemp."' ".
				" ORDER BY numsolcot DESC             ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->sigesp_soc_c_solicitud_cotizacion->MÉTODO->uf_validar_fecha_solicitud_cotizacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ld_fecha=$this->io_funciones->uf_formatovalidofecha($row["fecsol"]); 
				$lb_valido=$this->io_fecha->uf_comparar_fecha($ld_fecha,$ad_fecregsolcot); 
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_validar_fecha_solicitud_cotizacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_bienes($as_numsolcot)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_bienes
		//		   Access: public
		//		 Argument: as_numsol // Número de solicitud
		//	  Description: Función que busca los bienes asociados a una solicitud de cotizacion
		//	   Creado Por: Ing. Nestor Falcon.
		// Fecha Creación: 08/05/2007								Fecha Última Modificación : 08/05/2007
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;

		$ls_sql ="SELECT soc_dtsc_bienes.numsolcot,soc_dtsc_bienes.codart,               ".
		         "       max(soc_dtsc_bienes.cod_pro) as cod_pro,						 ".
				 "       max(soc_dtsc_bienes.unidad) as unidad,                 		 ".
				 "       max(soc_dtsc_bienes.canart) as canart,                          ".
				 "       max(siv_articulo.denart) as denart,                             ".
				 "       max(soc_dtsc_bienes.numsep) as numsep,                          ".
		         "       max(soc_dtsc_bienes.coduniadm) as coduniadm,                    ".
			     "       max(soc_dtsc_bienes.codestpro1) as codestpro1,                  ".	
			     "       max(soc_dtsc_bienes.codestpro2) as codestpro2,                  ".	
			     "       max(soc_dtsc_bienes.codestpro3) as codestpro3,                  ".
			     "       max(soc_dtsc_bienes.codestpro4) as codestpro4,                  ".	
			     "       max(soc_dtsc_bienes.codestpro5) as codestpro5,                  ".					 								 				 
			     "       max(soc_dtsc_bienes.estcla) as estcla,                          ".	
				 "		 soc_dtsc_bienes.orden 											 ".				 
				 "  FROM soc_sol_cotizacion,soc_dtsc_bienes, siv_articulo, rpc_proveedor ".
				 " WHERE soc_dtsc_bienes.codemp='".$this->ls_codemp."'             		 ".
				 "   AND soc_dtsc_bienes.numsolcot='".$as_numsolcot."'             		 ".
                 "   AND soc_sol_cotizacion.codemp=soc_dtsc_bienes.codemp 	       		 ".
				 "   AND soc_dtsc_bienes.codemp=siv_articulo.codemp       	       		 ".
                 "   AND soc_sol_cotizacion.numsolcot=soc_dtsc_bienes.numsolcot    		 ".				 
                 "   AND soc_sol_cotizacion.codemp=siv_articulo.codemp             		 ".
				 "   AND soc_dtsc_bienes.codart=siv_articulo.codart                		 ".
				 "   AND soc_dtsc_bienes.codemp=rpc_proveedor.codemp             		 ".
				 "   AND soc_dtsc_bienes.cod_pro=rpc_proveedor.cod_pro             		 ".
                 " GROUP BY soc_dtsc_bienes.codart,soc_dtsc_bienes.numsep,				 ".
				 "          soc_dtsc_bienes.numsolcot,soc_dtsc_bienes.orden 	     	 ".
				 " ORDER BY soc_dtsc_bienes.orden  ASC                                   ";
		$rs_data=$this->io_sql->select($ls_sql);//print $ls_sql;
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->sigesp_soc_c_solicitud_cotizacion.php->MÉTODO->uf_load_bienes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_bienes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_servicios($as_numsolcot)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_servicios
		//		   Access: public
		//		 Argument: as_numsolcot // Número de solicitud
		//	  Description: Función que busca los bienes asociados a una solicitud de cotizacion
		//	   Creado Por: Ing. Nestor Falcon.
		// Fecha Creación: 12/05/2007								Fecha Última Modificación : 12/05/2007
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
		$ls_sql ="SELECT soc_dtsc_servicios.numsolcot,soc_dtsc_servicios.codser,   			 ".
				 "       max(soc_dtsc_servicios.canser) as canser,							 ".
				 "       max(soc_servicios.denser) as denser,            			         ".
				 "       max(soc_dtsc_servicios.numsep) as numsep,                 			 ".
		         "       max(soc_dtsc_servicios.coduniadm) as coduniadm,                     ".
			     "       max(soc_dtsc_servicios.codestpro1) as codestpro1,                   ".	
			     "       max(soc_dtsc_servicios.codestpro2) as codestpro2,                   ".	
			     "       max(soc_dtsc_servicios.codestpro3) as codestpro3,                   ".
			     "       max(soc_dtsc_servicios.codestpro4) as codestpro4,                   ".	
			     "       max(soc_dtsc_servicios.codestpro5) as codestpro5,                   ".					 								 				 
			     "       max(soc_dtsc_servicios.estcla) as estcla,                           ".					 
				 "		 soc_dtsc_servicios.orden                                            ".
		         "  FROM soc_sol_cotizacion,soc_dtsc_servicios, soc_servicios, rpc_proveedor ".
				 " WHERE soc_dtsc_servicios.codemp='".$this->ls_codemp."'          			 ".
				 "   AND soc_dtsc_servicios.numsolcot='".$as_numsolcot."'          			 ".
                 "   AND soc_sol_cotizacion.codemp=soc_dtsc_servicios.codemp 	   			 ".
                 "   AND soc_sol_cotizacion.numsolcot=soc_dtsc_servicios.numsolcot 			 ".				 
				 "   AND soc_dtsc_servicios.codemp=soc_servicios.codemp       	   			 ".
				 "   AND soc_dtsc_servicios.codser=soc_servicios.codser            			 ". 
				 "   AND soc_dtsc_servicios.codemp=rpc_proveedor.codemp             	     ".
				 "   AND soc_dtsc_servicios.cod_pro=rpc_proveedor.cod_pro             		 ".
                 " GROUP BY soc_dtsc_servicios.codser, soc_dtsc_servicios.numsep,            ".
				 "          soc_dtsc_servicios.numsolcot,soc_dtsc_servicios.orden 	     	 ".
				 " ORDER BY soc_dtsc_servicios.orden ASC                                     ";
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->sigesp_soc_c_solicitud_cotizacion.php->MÉTODO->uf_load_servicios ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_servicios
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_proveedores($as_numsolcot,$as_tipsolcot)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_proveedores
		//		   Access: public
		//		 Argument: as_numsol // Número de solicitud
		//	  Description: Función que busca los proveedores asociados a una solicitud de cotizacion
		//	   Creado Por: Ing. Nestor Falcon.
		// Fecha Creación: 11/05/2007								Fecha Última Modificación : 11/05/2007
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;

		if ($as_tipsolcot=='B')
		   {
		     $ls_tabla = "soc_dtsc_bienes";
		   }
		elseif($as_tipsolcot=='S')
		   {
		     $ls_tabla = "soc_dtsc_servicios";
		   }
		$ls_sql =" SELECT $ls_tabla.cod_pro, 
		              max(rpc_proveedor.nompro) as nompro, 
				max(rpc_proveedor.dirpro) as dirpro, 
				max(rpc_proveedor.telpro) as telpro
  			      FROM soc_sol_cotizacion, $ls_tabla, rpc_proveedor
 			     WHERE $ls_tabla.codemp='".$this->ls_codemp."'
  				AND $ls_tabla.numsolcot = '".$as_numsolcot."'
				AND $ls_tabla.cod_pro<>'----------'
   				AND soc_sol_cotizacion.codemp=$ls_tabla.codemp
   				AND soc_sol_cotizacion.numsolcot=$ls_tabla.numsolcot
   				AND $ls_tabla.codemp=rpc_proveedor.codemp
   				AND $ls_tabla.cod_pro=rpc_proveedor.cod_pro
 			     GROUP BY $ls_tabla.cod_pro";//print $ls_sql;
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->sigesp_soc_c_solicitud_cotizacion.php->MÉTODO->uf_load_proveedores.ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_proveedores
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_solicitud_cotizacion($as_numsolcot,$as_tipsolcot,$ai_totrowsep,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_solicitud_cotizacion
		//		   Access: public
		//	    Arguments: as_numsol  // Número de Solicitud 
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que elimina la solicitud de Ejecución Presupuestaria
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 13/03/2007 								Fecha Última Modificación : 13/05/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();
		if ($as_tipsolcot=='B')
		   {
			 $ls_tabla = "soc_dtsc_bienes";
		   }
		elseif($as_tipsolcot=='S')
		   {
			 $ls_tabla = "soc_dtsc_servicios";
		   }
		$lb_valido=$this->uf_delete_detalles($as_numsolcot,$as_tipsolcot,$ai_totrowsep,$ls_tabla,$aa_seguridad);
		if($lb_valido)
		{
			$ls_sql="DELETE FROM soc_sol_cotizacion WHERE codemp = '".$this->ls_codemp."' AND numsolcot = '".$as_numsolcot."'";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->sigesp_soc_c_solicitud_cotizacion.php->MÉTODO->uf_delete_solicitud_cotizacion.php->ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="DELETE";
				$ls_descripcion ="Elimino la solicitud de cotizacion ".$as_numsolcot." Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				if($lb_valido)
				{	
					$this->io_mensajes->message("La Solicitud fue Eliminada !!!");
					$this->io_sql->commit();
				}
				else
				{
					$lb_valido=false;
					$this->io_mensajes->message("Ocurrio un Error al Eliminar la Solicitud !!!"); 
					$this->io_sql->rollback();
				}
			}
		}
		else
		{
			$this->io_mensajes->message("Ocurrio un Error al Eliminar la Solicitud !!!"); 
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_delete_solicitud_cotizacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_detalles($as_numsolcot,$as_tipsolcot,$ai_totrows,$as_tabla,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_detalles
		//		   Access: private
		//	    Arguments: as_numsol  // Número de Solicitud de cotizacion.
		//				   as_tabla  // Tabla donde se deben insertar los cargos
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el mismo.
		//	  Description: Funcion que elimina los detalles de una solicitud de cotizacion.
		//	   Creado Por: Ing. Néstor Falcon.
		// Fecha Creación: 13/05/2007 								Fecha Última Modificación : 13/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = true;
        $ls_sql    = "DELETE FROM $as_tabla WHERE codemp = '".$this->ls_codemp."' AND numsolcot = '".$as_numsolcot."'";
	    $rs_data = $this->io_sql->execute($ls_sql);
		if ($rs_data===false)
		   {
		     $this->io_sql->rollback();
			 $lb_valido = false;
			 $this->io_mensajes->message("CLASE->sigesp_soc_c_solicitud_cotizacion.php->MÉTODO->uf_delete_detalles(Bienes)->ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		   }
		if ($lb_valido)
		   {
			 $ls_sql  = "DELETE FROM soc_solcotsep WHERE codemp='".$this->ls_codemp."' AND numsolcot = '".$as_numsolcot."'";
			 $rs_data = $this->io_sql->execute($ls_sql);
			 if ($rs_data===false)
			    {
				  $lb_valido=false;
			      $this->io_mensajes->message("CLASE->sigesp_soc_c_solicitud_cotizacion.php->MÉTODO->uf_delete_detalles ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		  	    }
		   }
		if ($lb_valido)
		   {
		     if ($as_tipsolcot=='B')
			    {
				  $ls_tabla = "sep_dt_articulos";
				}
			 elseif($as_tipsolcot=='S')
			    {
				  $ls_tabla = "sep_dt_servicio";
				}
			 if ($ai_totrows>1)
			    {
				  for ($i=1;$i<$ai_totrows;$i++)
					  {
					    $ls_numsep = trim($_POST["txtnumsep".$i]);
					    if (!empty($ls_numsep))
						   {
							 $ls_sql = "UPDATE $ls_tabla SET estincite='NI', ".
									   "       numdocdes=' '                 ". 					 
									   " WHERE codemp='".$this->ls_codemp."' ".
									   "   AND numsol='".$ls_numsep."'       ".
									   "   AND numdocdes='".$as_numsolcot."' ".
									   "   AND estincite='SC'                ";
							 $rs_data = $this->io_sql->execute($ls_sql);//print $ls_sql;
							 if ($rs_data===false)
							    {
								  $lb_valido=false;
								  $this->io_mensajes->message("CLASE->sigesp_soc_c_solicitud_cotizacion.php->MÉTODO->uf_delete_detalles ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
							    }
						     else
							    {
								  $this->uf_update_estatus_sep('DELETE',$ls_numsep,$ls_tabla);
								}
						   }
					  }
				}
		   }
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó todos los detalles de la solicitud de cotizacion ".$as_numsolcot." Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		return $lb_valido;
	}// end function uf_delete_detalles
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_bienes_sep($as_numsol)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_bienes_sep
		//		   Access: public
		//		 Argument: as_numsol // Número de solicitud
		//	  Description: Función que busca los Bienes asociados a una Solicitud de Ejecución Presupuestaria.
		//	   Creado Por: Ing. Nestor Falcon.
		// Fecha Creación: 16/05/2007								Fecha Última Modificación : 16/05/2007
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
					 
		$ls_sql = "SELECT sep_solicitud.numsol,
						  sep_solicitud.coduniadm,
		                  sep_dt_articulos.codart, 
						  sep_dt_articulos.canart,
					      sep_dt_articulos.unidad,
						  sep_dt_articulos.codestpro1,
						  sep_dt_articulos.codestpro2,
						  sep_dt_articulos.codestpro3,
						  sep_dt_articulos.codestpro4,
						  sep_dt_articulos.codestpro5,
						  sep_dt_articulos.estcla,
						  siv_articulo.denart, 
						  siv_unidadmedida.unidad as uniart
					 FROM sep_solicitud, sep_dt_articulos, siv_articulo, siv_unidadmedida
					WHERE sep_solicitud.codemp='".$this->ls_codemp."'
					  AND sep_solicitud.numsol = '".$as_numsol."'
					  AND sep_dt_articulos.estincite = 'NI'
					  AND (sep_solicitud.estsol='C' OR sep_solicitud.estsol='P')
					  AND sep_solicitud.codemp=sep_dt_articulos.codemp
					  AND sep_solicitud.numsol=sep_dt_articulos.numsol
					  AND sep_solicitud.codemp=siv_articulo.codemp
					  AND sep_dt_articulos.codemp=siv_articulo.codemp
					  AND sep_dt_articulos.codart=siv_articulo.codart
					  AND siv_articulo.codunimed=siv_unidadmedida.codunimed";//print $ls_sql;

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->sigesp_soc_c_solicitud_cotizacion.php->MÉTODO->uf_load_bienes_sep.ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_bienes_sep
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_servicios_sep($as_numsol)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_servicios_sep
		//		   Access: public
		//		 Argument: as_numsol // Número de solicitud
		//	  Description: Función que busca los Servicios asociados a una Solicitud de Ejecución Presupuestaria.
		//	   Creado Por: Ing. Nestor Falcon.
		// Fecha Creación: 16/05/2007								Fecha Última Modificación : 16/05/2007
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;

		$ls_sql ="SELECT sep_solicitud.numsol,
		                 sep_solicitud.coduniadm,
		                 sep_dt_servicio.codser, 
						 sep_dt_servicio.canser, 
 						 sep_dt_servicio.codestpro1,
						 sep_dt_servicio.codestpro2,
						 sep_dt_servicio.codestpro3,
						 sep_dt_servicio.codestpro4,
						 sep_dt_servicio.codestpro5,
						 sep_dt_servicio.estcla,
						 soc_servicios.denser
				    FROM sep_solicitud, sep_dt_servicio, soc_servicios
				   WHERE sep_solicitud.codemp='".$this->ls_codemp."'
					 AND sep_solicitud.numsol = '".$as_numsol."'
					 AND sep_dt_servicio.estincite = 'NI'
					 AND (sep_solicitud.estsol='C' OR sep_solicitud.estsol='P')
					 AND sep_solicitud.codemp=sep_dt_servicio.codemp
					 AND sep_solicitud.numsol=sep_dt_servicio.numsol
					 AND sep_solicitud.codemp=soc_servicios.codemp
					 AND sep_dt_servicio.codser=soc_servicios.codser";
		
		$rs_data=$this->io_sql->select($ls_sql);//print $ls_sql;
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->sigesp_soc_c_solicitud_cotizacion.php->MÉTODO->uf_load_servicios_sep.ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_servicios_sep
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_sep_solcot($as_numsolcot)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_sep_solcot
		//		   Access: public
		//		 Argument: as_numsolcot // Número de solicitud de Cotizacion
		//	  Description: Función que busca los Solicitudes de Ejecución Presupuestaria Asociadas a una Solicitud de Cotizacion.
		//	   Creado Por: Ing. Nestor Falcon.
		// Fecha Creación: 20/05/2007								Fecha Última Modificación : 20/05/2007
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
		$ls_sql = "SELECT soc_solcotsep.numsol as numsep, 
		                  sep_solicitud.consol as densep, 
						  sep_solicitud.monto as monsep, 
		                  soc_solcotsep.codunieje, 
						  spg_unidadadministrativa.denuniadm,
						  soc_solcotsep.codestpro1,
						  soc_solcotsep.codestpro2,
						  soc_solcotsep.codestpro3,
						  soc_solcotsep.codestpro4,
						  soc_solcotsep.codestpro5,
						  soc_solcotsep.estcla 
					 FROM sep_solicitud, soc_solcotsep, spg_unidadadministrativa
					WHERE soc_solcotsep.codemp='".$this->ls_codemp."'
					  AND soc_solcotsep.numsolcot='".$as_numsolcot."'
					  AND sep_solicitud.codemp=soc_solcotsep.codemp
					  AND sep_solicitud.numsol=soc_solcotsep.numsol
					  AND soc_solcotsep.codemp=spg_unidadadministrativa.codemp
					  AND soc_solcotsep.codunieje=spg_unidadadministrativa.coduniadm";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->sigesp_soc_c_solicitud_cotizacion.php->MÉTODO->uf_load_sep_solcot.ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_sep_solcot
	//-----------------------------------------------------------------------------------------------------------------------------------

    function uf_update_estatus_incorporacion($as_tipsolcot,$as_numsolcot,$as_numsep,$as_codigo,$as_desope)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_update_estatus_incorporacion
	//		   Access: public
	//		 Argument: $as_numsolcot       //Numero de la Solicitud de Cotización.
	//                 $as_numsep          //Número de la Solicituds de Ejecución Presupuestaria. 
	//                 $as_tipsolcot       //Tipo de Solicitud de Cotización B= Bienes, S= Servicios.  
	//                 $as_codigo          //
	//                 $as_desope	       //Si la operacion a realizar es un Insert o un Delete.
	//	  Description: Función actualiza el estatus de incorporacion del item en las Tabla de Detalles de la SEP.
	//	   Creado Por: Ing. Nestor Falcon.
	// Fecha Creación: 20/05/2007								Fecha Última Modificación : 20/05/2007
	//////////////////////////////////////////////////////////////////////////////
	  
		$lb_valido = true;
		if ($as_tipsolcot=='B')
		   {
			 $ls_tabla = "sep_dt_articulos";
			 $ls_campo = "codart";
		   }
		elseif($as_tipsolcot=='S')
		   {
			 $ls_tabla  = "sep_dt_servicio";
			 $ls_campo  = "codser";
		   }
			$ls_sql = "UPDATE $ls_tabla SET estincite='SC', ".
			          "       numdocdes='".$as_numsolcot."' ".
					  " WHERE codemp='".$this->ls_codemp."' ".
					  "   AND numsol='".$as_numsep."'       ".
					  "   AND $ls_campo='".$as_codigo."'    ".
					  "   AND estincite='NI'                ";

		$rs_recordset = $this->io_sql->execute($ls_sql);//print $ls_sql.'<br>';
		if ($rs_recordset===false)
		   {
			 $lb_valido = false;
			 $this->io_mensajes->message("CLASE->sigesp_soc_c_solicitud_cotizacion.php->MÉTODO->uf_update_estatus_incorporacion->ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			 $this->io_sql->rollback();
		   }
		return $lb_valido;
	}// end function uf_update_estatus_incorporacion 

    function uf_update_estatus_sep($as_desope,$as_numsep,$as_tabla)
    {
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_update_estatus_sep
	//		   Access: public
	//		 Argument: 
	//      $as_desope //Descripcion de la Operacion (INSERT,UPDATE,DELETE). 
	//      $as_numsep //Número de la Solicitud de Ejecución Presupuestaria.
	//       $as_tabla //Tabla donde verificaremos el estatus de los items incluidos en una Solicitud de Cotizacion,
	//                   si es de Tipo Bienes Tabla=sep_dt_articulos, Tipo Servicios=sep_d_servicios.  
	//	  Description: Función actualiza el estatus de la SEP a procesada en caso de que ningun Item se encuentre como 
	//                 NI = NO INCORPORADO.
	//	   Creado Por: Ing. Nestor Falcon.
	// Fecha Creación: 26/07/2007								Fecha Última Modificación : 20/07/2007
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////

      $lb_valido = true;
      if ($as_desope=='INSERT')
	     {
		   $ls_sql = "UPDATE sep_solicitud 
		                 SET estsol='P' 
					   WHERE codemp='".$this->ls_codemp."' 
					     AND numsol='".$as_numsep."' 
						 AND estsol='C'";
		   $rs_data = $this->io_sql->execute($ls_sql);//print $ls_sql;
		   if ($rs_data===false)
		      {
			    $lb_valido = false;
			    $this->io_mensajes->message("CLASE->sigesp_soc_c_solicitud_cotizacion.php->MÉTODO->uf_update_estatus_sep(INSERT)->ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			  }
		 } 
      else
	     {
		   $ls_sql = "SELECT sep_solicitud.numsol 
		                FROM sep_solicitud, $as_tabla 
					   WHERE sep_solicitud.codemp='".$this->ls_codemp."' 
					     AND sep_solicitud.numsol='".$as_numsep."'
						 AND $as_tabla.estincite<>'NI'
						 AND sep_solicitud.codemp=$as_tabla.codemp
						 AND sep_solicitud.numsol=$as_tabla.numsol";
		   $rs_data = $this->io_sql->select($ls_sql);
		   if ($rs_data===false)
		      {
			    $lb_valido = false;
			    $this->io_mensajes->message("CLASE->sigesp_soc_c_solicitud_cotizacion.php->MÉTODO->uf_update_estatus_sep->ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			  }
		   else
		      {
			    $li_numrows = $this->io_sql->num_rows($rs_data);
				if ($li_numrows<=0)
				   {
				     $ls_sql = "UPDATE sep_solicitud 
					               SET estsol='C'
					             WHERE codemp='".$this->ls_codemp."'
								   AND numsol='".$as_numsep."'";
				     $rs_data = $this->io_sql->execute($ls_sql);
					 if ($rs_data===false)
					    {
						  $lb_valido = false;
						  $this->io_mensajes->message("CLASE->sigesp_soc_c_solicitud_cotizacion.php->MÉTODO->uf_update_estatus_sep->ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
						}  
				   }
			  }
		 }
	  return $lb_valido;
    }//Fin de la Funcion uf_update_estatus_sep.
}
?>