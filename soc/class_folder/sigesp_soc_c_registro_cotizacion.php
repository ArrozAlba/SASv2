<?php
class sigesp_soc_c_registro_cotizacion
{
  function sigesp_soc_c_registro_cotizacion($as_path)
  {
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: sigesp_soc_c_registro_cotizacion
	//		   Access: public 
	//	  Description: Constructor de la Clase
	//	   Creado Por: Ing. Néstor Falcón.
	// Fecha Creación: 21/05/2007 								Fecha Última Modificación : 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        require_once($as_path."shared/class_folder/class_sql.php");
		require_once($as_path."shared/class_folder/class_fecha.php");
		require_once($as_path."shared/class_folder/sigesp_include.php");
	    require_once($as_path."shared/class_folder/class_mensajes.php");
		require_once($as_path."shared/class_folder/class_funciones.php");
		require_once($as_path."shared/class_folder/sigesp_c_seguridad.php");
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
	function uf_guardar($as_existe,$ad_fecregcot,&$as_numcot,$as_numsolcot,$as_tipcot,$as_obscot,$ai_totrowbienes,$ai_totrowservicios,$as_estcot,
					    $as_forpag,$as_codpro,$ai_diaent,$ad_porcentaje,$ad_subtotal,$ad_creditos,$ad_total,$ai_estinciva,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_soc_p_registro_cotizacion.php)
		//	    Arguments: $as_existe
		//                 $as_numcot           //Número de la Cotización.
		//                 $as_tipcot           //Tipo de Cotización. 
		//                 $as_obscot           //Observación de la Cotización.
		//                 $ad_fecregcot        //Fecha de Registro de la Cotización.
		//                 $ai_totrowbienes     //Total de Filas del Grid de Bienes.
		//                 $ai_totrowservicios  //Total de Filas del Grid de Servicios.
		//                 $as_forpag           //Forma de Pago de la Cotización.
	    //                 $as_codpro           //Proveedor asociado a la Cotización.
		//                 $ai_diaent           //Dias de Entrega de los Items de la Cotización.
		//                 $ld_porcentaje       //Alicuota con la que se calcula el impuesto.
		//                 $aa_seguridad        //Arreglo de Seguridad. 
		//                 $as_estcot           // Estatus del Registro de la Cotización.
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que valida y guarda la sep
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 04/05/2007 								Fecha Última Modificación : 04/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido     = false;	
		$lb_encontrado = $this->uf_select_cotizacion($as_numcot);
		switch($as_tipcot)
		{
			case "B": // si es de Bienes
				$ls_tabla="soc_dtcot_bienes";
				$ls_campo="codart";
				break;
			case "S": // si es de Servicios
				$ls_tabla="soc_dtcot_servicio";
				$ls_campo="codser";
				break;
		}
		$ad_fecregcot = $this->io_funciones->uf_convertirdatetobd($ad_fecregcot);
		switch ($as_existe)
		{
			case "FALSE":
					$lb_valido=$this->uf_validar_fecha_cotizacion($ad_fecregcot);
					if(!$lb_valido)
					{
						$this->io_mensajes->message("La Fecha de esta Cotización es menor a la fecha de la Cotización anterior.");
						return false;
					}
					$lb_valido=$this->io_fecha->uf_valida_fecha_periodo($ad_fecregcot,$this->ls_codemp);
					if (!$lb_valido)
					{
						$this->io_mensajes->message($this->io_fecha->is_msg_error);           
						return false;
					}                    
					$lb_valido=$this->uf_insert_cotizacion($ad_fecregcot,$as_numcot,$as_numsolcot,$as_tipcot,$as_obscot,$ai_totrowbienes,$ai_totrowservicios,$as_estcot,$as_forpag,
					                                       $as_codpro,$ai_diaent,$ad_porcentaje,$ls_tabla,$ls_campo,$ad_subtotal,$ad_creditos,$ad_total,$ai_estinciva,$aa_seguridad);
				
				break;

			case "TRUE":
				if($lb_encontrado)
				{
					$lb_valido=$this->uf_update_cotizacion($ad_fecregcot,$as_numcot,$as_numsolcot,$as_tipcot,$as_obscot,$ai_totrowbienes,$ai_totrowservicios,$as_estcot,$as_forpag,
	                                                       $as_codpro,$ai_diaent,$ad_porcentaje,$ad_subtotal,$ad_creditos,$ad_total,$ai_estinciva,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("La Cotización no existe, no la puede actualizar.");
				}
				break;
		}
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_cotizacion($ad_fecregcot,&$as_numcot,$as_numsolcot,$as_tipcot,$as_obscot,$ai_totrowbienes,$ai_totrowservicios,$as_estcot,$as_forpag,
	                              $as_codpro,$ai_diaent,$ad_porcentaje,$as_tabla,$as_campo,$ad_subtotal,$ad_creditos,$ad_total,$ai_estinciva,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cotizacion
		//		   Access: private
		//	    Arguments: ad_fecregcot         // Fecha de Registro de la Cotización.
		//				   as_numcot            // Número de la Cotización.
		//				   as_codpro            // Código de Proveedor 
		//				   as_obscot            // Observación de la Cotización.
		//				   as_tipcot            // Tipo de Cotización.
		//				   ai_totrowbienes      // Total de Filas de Bienes
		//				   ai_totrowservicios   // Total de Filas de Servicios
		//				   ai_diaent            // Dias de entrega de los items.
		//				   ad_porcentaje        // Alicuota sobre la que se calcula el impuesto
		//				   as_tabla             // Tabla donde se deben insertar los items
		//				   as_campo             // Campo donde se inserta el codigo del Bien, Servicio.
		//				   aa_seguridad         // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta la Cotización.
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 22/05/2007 								Fecha Última Modificación : 22/05/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $ls_numcotaux = $as_numcot;
		$lb_valido    = $this->io_keygen->uf_verificar_numero_generado('SOC','soc_cotizacion','numcot','SOCCOT',15,"","","",&$as_numcot);
		$lb_valido    = true;
		$ls_codusu    = $aa_seguridad["logusr"];
		$ld_descuento = 0;
		$ld_subtotal  = str_replace('.','',$ad_subtotal); 
		$ld_subtotal  = str_replace(',','.',$ld_subtotal);
		$ld_creditos  = str_replace('.','',$ad_creditos);
		$ld_creditos  = str_replace(',','.',$ld_creditos);
		$ld_total     = str_replace('.','',$ad_total);
		$ld_total     = str_replace(',','.',$ld_total);
		$ld_poriva    = str_replace(',','.',$ad_porcentaje);

		if (empty($ai_diaent)){$ai_diaent=0;}
		
		if($lb_valido)
		{
			$ls_sql="INSERT INTO soc_cotizacion (codemp, numcot, cod_pro, numsolcot, feccot, obscot, monsubtot, monimpcot, mondes, montotcot, diaentcom, 
			                                     codusu, estcot, forpagcom, poriva, estinciva, tipcot)".
					"	  VALUES ('".$this->ls_codemp."','".$as_numcot."','".$as_codpro."','".$as_numsolcot."','".$ad_fecregcot."','".$as_obscot."',".$ld_subtotal.",
					              ".$ld_creditos.",".$ld_descuento.",".$ld_total.",".$ai_diaent.",'".$ls_codusu."','".$as_estcot."','".$as_forpag."',".$ld_poriva.",".$ai_estinciva.",'".$as_tipcot."')";        
			$this->io_sql->begin_transaction();//print $ls_sql;			
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_sql->rollback();
				if($this->io_sql->errno=='23505' || $this->io_sql->errno=='1062' || $this->io_sql->errno=='-5' || $this->io_sql->errno=='-1')
				{
					$lb_valido=$this->uf_insert_cotizacion($ad_fecregcot,$as_numcot,$as_numsolcot,$as_tipcot,$as_obscot,$ai_totrowbienes,$ai_totrowservicios,$as_estcot,$as_forpag,
	                              						   $as_codpro,$ai_diaent,$ad_porcentaje,$as_tabla,$as_campo,$ad_subtotal,$ad_creditos,$ad_total,$ai_estinciva,$aa_seguridad);
				}
				else
				{
				  $lb_valido=false;
				  $this->io_mensajes->message("CLASE->sigesp_soc_c_registro_cotizacion.php->MÉTODO->uf_insert_cotizacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
				
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la Cotizacion ".$as_numcot." Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			
				if ($as_tipcot=='B')
				   {
				     if ($lb_valido)
				        {	
					      $lb_valido = $this->uf_insert_bienes($as_numcot,$as_codpro,$ai_totrowbienes,$aa_seguridad);
				        }			
				   }
				elseif($as_tipcot=='S')
				   {
				     if ($lb_valido)
				        {	
					      $lb_valido = $this->uf_insert_servicios($as_numcot,$as_codpro,$ai_totrowservicios,$aa_seguridad);
				        }			
				   }
				if($lb_valido)
				{	
					if($ls_numcotaux!=$as_numcot)
					{
						$this->io_mensajes->message("Se Asigno el Numero de Registro de Cotización: ".$as_numcot);
					}
					$this->io_mensajes->message("La Cotización fue registrada !!!");
					$this->io_sql->commit();
				}
				else
				{
					$lb_valido=false;
					$this->io_mensajes->message("Ocurrio un Error al Registrar la Cotización !!!"); 
					$this->io_sql->rollback();
				}
			}
		}
		return $lb_valido;
	}// end function uf_insert_cotizacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_cotizacion($ad_fecregcot,$as_numcot,$as_numsolcot,$as_tipcot,$as_obscot,$ai_totrowbienes,$ai_totrowservicios,$as_estcot,$as_forpag,
	                              $as_codpro,$ai_diaent,$ad_porcentaje,$ad_subtotal,$ad_creditos,$ad_total,$ai_estinciva,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_cotizacion
		//		   Access: private
		//	    Arguments: as_numcot  // Número de Solicitud 
		//				   as_codpro  // Código de Proveedor 
		//				   as_consol  // Concepto de la Solicitud
		//				   ai_totrowbienes  // Total de Filas de Bienes
		//				   ai_totrowcargos  // Total de Filas de Servicios
		//				   ai_totrowcuentas  // Total de Filas de Cuentas
		//				   ai_totrowcuentascargo  // Total de Filas de Cuentas Cargos
		//				   as_tabla  // Tabla donde se deben insertar los cargos
		//				   as_campo  // Campo donde se inserta el codigo del Bien, Servicio ó Concepto
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que actualiza la solicitud de Ejecución Presupuestaria
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido   = true; 
		$ls_codusu   = $aa_seguridad["logusr"];
		
		$ld_subtotal = str_replace('.','',$ad_subtotal); 
		$ld_subtotal = str_replace(',','.',$ld_subtotal);
		
		$ld_creditos = str_replace('.','',$ad_creditos);
		$ld_creditos = str_replace(',','.',$ld_creditos);
		
		$ld_total    = str_replace('.','',$ad_total);
		$ld_total    = str_replace(',','.',$ld_total);
		if (empty($ai_diaent)){$ai_diaent=0;}
		
		$ls_sql="UPDATE soc_cotizacion              ".
                "   SET obscot = '".$as_obscot."',  ". 
				"	    codusu = '".$ls_codusu."',  ". 
				"    monsubtot = ".$ld_subtotal.",  ".
				"    monimpcot = ".$ld_creditos.",  ".
				"    montotcot = ".$ld_total.",     ".
				"    diaentcom = ".$ai_diaent.",    ".
				"    forpagcom = '".$as_forpag."',  ".
				"    estinciva = ".$ai_estinciva.", ".
				"    tipcot    = '".$as_tipcot."'   ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND numcot='".$as_numcot."'".
				"   AND cod_pro='".$as_codpro."'"; 		
		$this->io_sql->begin_transaction();				
		$rs_data = $this->io_sql->execute($ls_sql);//print $ls_sql;
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->sigesp_soc_c_registro_cotizacion.php->MÉTODO->uf_update_cotizacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la Cotizacion ".$as_numcot." Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			
			if ($lb_valido)
			   {
		         if ($as_tipcot=='B')
	                {
				      $lb_valido=$this->uf_delete_detalles($as_numcot,$as_tipcot,$as_codpro,'INSERT',$ai_totrowbienes,$aa_seguridad);
				      if ($lb_valido)
					     {
					       $lb_valido = $this->uf_insert_bienes($as_numcot,$as_codpro,$ai_totrowbienes,$aa_seguridad);
						 }
		            }
				 elseif($as_tipcot=='S')
				   {
				     $lb_valido=$this->uf_delete_detalles($as_numcot,$as_tipcot,$as_codpro,'INSERT',$ai_totrowservicios,$aa_seguridad);
				      if ($lb_valido)
					     {
					       $lb_valido = $this->uf_insert_servicios($as_numcot,$as_codpro,$ai_totrowservicios,$aa_seguridad);
				         }
				   }
		       }	
			if($lb_valido)
			{	
				$this->io_mensajes->message("La Cotización fue actualizada !!!.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("Ocurrio un Error al Actualizar la Cotización !!!."); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_cotizacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_validar_fecha_cotizacion($ad_fecregcot)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_fecha_cotizacion
		//		   Access: private
		//		 Argument: $ad_fecregsolcot // Fecha de registro de la nueva Solicitud de Cotización.
		//	  Description: Función que busca la fecha de la última Solicitud de Cotizacion y la compara con la fecha actual.
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT numcot,feccot                 ".
				"  FROM soc_cotizacion                ".
				" WHERE codemp='".$this->ls_codemp."' ".
				" ORDER BY numcot DESC                ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->sigesp_soc_c_solicitud_cotizacion->MÉTODO->uf_validar_fecha_cotizacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ld_fecha=$this->io_funciones->uf_formatovalidofecha($row["feccot"]); 
				$lb_valido=$this->io_fecha->uf_comparar_fecha($ld_fecha,$ad_fecregcot); 
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_validar_fecha_cotizacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_cotizacion($as_numcot)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_solicitud_cotizacion
		//		   Access: private
		//	    Arguments: as_numcot  //  Número de la Cotización.
		// 	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si la Cotización existe.
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 22/05/2007 								Fecha Última Modificación : 22/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT numcot FROM soc_cotizacion WHERE codemp='".$this->ls_codemp."' AND numcot='".$as_numcot."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->sigesp_soc_c_registro_cotizacion.php->MÉTODO->uf_select_cotizacion->ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_cotizacion
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_bienes_solicitud($as_numsolcot,$as_codpro)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_bienes_solicitud
		//		   Access: public
		//		 Argument: as_numsol // Número de solicitud
		//	  Description: Función que busca los bienes asociados a una solicitud de cotizacion para ser cargados en el Registro de la Cotizacion.
		//	   Creado Por: Ing. Nestor Falcon.
		// Fecha Creación: 25/05/2007								Fecha Última Modificación : 25/05/2007
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;

		$ls_sql ="SELECT soc_dtsc_bienes.codart,								".
		         "       max(soc_dtsc_bienes.unidad) as unidad,            		".
				 "       SUM(soc_dtsc_bienes.canart) as canart,					".
				 "		 max(siv_articulo.denart) as denart,					".
				 "       max(soc_dtsc_bienes.orden) as orden					".
				 "  FROM soc_sol_cotizacion,soc_dtsc_bienes, siv_articulo       ".
				 " WHERE soc_dtsc_bienes.codemp='".$this->ls_codemp."'          ".
				 "   AND soc_dtsc_bienes.numsolcot='".$as_numsolcot."'          ".
				 "   AND soc_dtsc_bienes.cod_pro='".$as_codpro."'               ".	
				 "   AND soc_sol_cotizacion.estcot='R'                          ".			 
                 "   AND soc_sol_cotizacion.codemp=soc_dtsc_bienes.codemp 	    ".
				 "   AND soc_dtsc_bienes.codemp=siv_articulo.codemp       	    ".
                 "   AND soc_sol_cotizacion.numsolcot=soc_dtsc_bienes.numsolcot ".				 
                 "   AND soc_sol_cotizacion.codemp=siv_articulo.codemp          ".
				 "   AND soc_dtsc_bienes.codart=siv_articulo.codart             ".
                 " GROUP BY soc_dtsc_bienes.codart       						".
				 " ORDER BY orden ASC                                           ";
		
		$rs_data=$this->io_sql->select($ls_sql);//print $ls_sql;
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->sigesp_soc_c_registro_cotizacion.php->MÉTODO->uf_load_bienes_solicitud.ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_bienes_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_servicios_solicitud($as_numsolcot,$as_codpro)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_servicios_solicitud
		//		   Access: public
		//		 Argument: as_numsol // Número de solicitud
		//	  Description: Función que busca los bienes asociados a una solicitud de cotizacion
		//	   Creado Por: Ing. Nestor Falcon.
		// Fecha Creación: 25/05/2007								Fecha Última Modificación : 25/05/2007
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;

		$ls_sql ="SELECT soc_dtsc_servicios.codser, 							   ".
				 "    	 max(soc_servicios.denser) as denser,          			   ".
		         "       SUM(soc_dtsc_servicios.canser) as canser,                 ".
				 "       max(soc_dtsc_servicios.orden) as orden					   ".
		         "  FROM soc_sol_cotizacion,soc_dtsc_servicios, soc_servicios      ".
				 " WHERE soc_dtsc_servicios.codemp='".$this->ls_codemp."'          ".
				 "   AND soc_dtsc_servicios.numsolcot='".$as_numsolcot."'          ".
				 "   AND soc_dtsc_servicios.cod_pro='".$as_codpro."'               ".	
				 "   AND soc_sol_cotizacion.estcot='R'                             ".
                 "   AND soc_sol_cotizacion.codemp=soc_dtsc_servicios.codemp 	   ".
                 "   AND soc_sol_cotizacion.numsolcot=soc_dtsc_servicios.numsolcot ".				 
				 "   AND soc_dtsc_servicios.codemp=soc_servicios.codemp       	   ".
				 "   AND soc_dtsc_servicios.codser=soc_servicios.codser            ". 
                 " GROUP BY soc_dtsc_servicios.codser                              ".
				 " ORDER BY orden ASC                                              ";//print $ls_sql;
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->sigesp_soc_c_solicitud_cotizacion.php->MÉTODO->uf_load_servicios_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_servicios_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------

	//----------------------------------------------------
	function uf_insert_bienes($as_numcot,$as_codpro,$ai_totrowbienes,$aa_seguridad)
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
	// Fecha Creación: 27/05/2007 								Fecha Última Modificación : 27/05/2007
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		$lb_valido=true;
		for ($i=1;($i<$ai_totrowbienes)&&($lb_valido);$i++)
			{
			  $ls_codart = $_POST["txtcodart".$i];
			  $ld_canart = $_POST["txtcanart".$i];
			  $ld_canart = str_replace('.','',$ld_canart);
			  $ld_canart = str_replace(',','.',$ld_canart);
			  $ls_uniart = 'D';
			  $ld_preart = $_POST["txtpreart".$i];
			  $ld_preart = str_replace('.','',$ld_preart);
			  $ld_preart = str_replace(',','.',$ld_preart);
			  $ld_subart = $_POST["txtsubart".$i];
			  $ld_subart = str_replace('.','',$ld_subart);
			  $ld_subart = str_replace(',','.',$ld_subart);
			  $ld_creart = $_POST["txtcreart".$i];
			  $ld_creart = str_replace('.','',$ld_creart);
			  $ld_creart = str_replace(',','.',$ld_creart);
			  $ld_totart = $_POST["txttotart".$i];
			  $ld_totart = str_replace('.','',$ld_totart);
			  $ld_totart = str_replace(',','.',$ld_totart);
			  $ls_calart = $_POST["cmbcalart".$i];

			  $ls_sql    = "INSERT INTO soc_dtcot_bienes (codemp, numcot, cod_pro, codart, unidad, canart, preuniart, moniva, monsubart, montotart, nivcalart, orden)".
						   " VALUES ('".$this->ls_codemp."','".$as_numcot."','".$as_codpro."','".$ls_codart."','".$ls_uniart."',".$ld_canart.",".$ld_preart.",".
						   "         ".$ld_creart.",".$ld_subart.",".$ld_totart.",'".$ls_calart."',".$i.")";
			  $rs_data   = $this->io_sql->execute($ls_sql);
			  if ($rs_data===false)
				 {
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->sigesp_soc_c_registro_cotizacion.php->MÉTODO->uf_insert_bienes. ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				 }
			  else
				 {
			       /////////////////////////////////         SEGURIDAD               /////////////////////////////		
				   $ls_evento="INSERT";
				   $ls_descripcion ="Insertó el Articulo ".$ls_codart." a la Cotizacion ".$as_numcot." Asociado a la empresa ".$this->ls_codemp;
				   $lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
						
				 }
			}
	    return $lb_valido;
	}// end function uf_insert_bienes.
	//----------------------------------------------------

	//----------------------------------------------------
	function uf_insert_servicios($as_numcot,$as_codpro,$ai_totrowservicios,$aa_seguridad)
	{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_insert_servicios
	//		   Access: private
	//	    Arguments: as_numsolcot        // Número de Solicitud de Cotizacion.
	//              $ai_totrowproveedores  // Total de Filas de Proveedores.
	//				   ai_totrowservicios  // Total de Filas de Servicios.
	//				   aa_seguridad        // arreglo de las variables de seguridad
	//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
	//	  Description: Funcion que inserta los Servicios de una Cotizacion.
	//	   Creado Por: Ing. Nestor Falcón.
	// Fecha Creación: 06/05/2007 								Fecha Última Modificación : 06/05/2007
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		$lb_valido=true;
	   for ($i=1;($i<$ai_totrowservicios)&&($lb_valido);$i++)
		   {
			 $ls_codser = $_POST["txtcodser".$i];
			 $ld_canser = $_POST["txtcanser".$i];
			 $ld_canser = str_replace('.','',$ld_canser);
			 $ld_canser = str_replace(',','.',$ld_canser);
             $ld_preser = $_POST["txtpreser".$i];
		     $ld_preser = str_replace('.','',$ld_preser);
			 $ld_preser = str_replace(',','.',$ld_preser);
			 $ld_subser = $_POST["txtsubser".$i];
			 $ld_subser = str_replace('.','',$ld_subser);
			 $ld_subser = str_replace(',','.',$ld_subser);
			 $ld_creser = $_POST["txtcreser".$i];
			 $ld_creser = str_replace('.','',$ld_creser);
			 $ld_creser = str_replace(',','.',$ld_creser);
			 $ld_totser = $_POST["txttotser".$i];
			 $ld_totser = str_replace('.','',$ld_totser);
			 $ld_totser = str_replace(',','.',$ld_totser);
			 $ls_calser = $_POST["cmbcalser".$i];			 
			 
			 $ls_sql    = " INSERT INTO soc_dtcot_servicio (codemp, numcot, cod_pro, codser, canser, monuniser, moniva, monsubser, montotser, nivcalser, orden) ".
						   " VALUES ('".$this->ls_codemp."','".$as_numcot."','".$as_codpro."','".$ls_codser."',".$ld_canser.",".$ld_preser.",      ".
						   "         ".$ld_creser.",".$ld_subser.",".$ld_totser.",'".$ls_calser."',".$i.")";
		     $rs_data = $this->io_sql->execute($ls_sql);//print $ls_sql;
			 if ($rs_data===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->sigesp_soc_c_registro_cotizacion.php->MÉTODO->uf_insert_servicios ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
				else
				{
					   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
					   $ls_evento="INSERT";
					   $ls_descripcion ="Insertó el Servicio ".$ls_codser." a la Cotizacion ".$as_numcot." Asociado a la empresa ".$this->ls_codemp;
					   $lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////		
						
				}
		   }
	 return $lb_valido;
	}// end function uf_insert_servicios.
	//----------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_cotizacion($as_numcot,$as_tipcot,$as_codpro,$ai_totrowbienes,$ai_totrowservicios,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_cotizacion
		//		   Access: public
		//	    Arguments: as_numsol  // Número de Solicitud 
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que elimina la Cotizacion.
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 27/05/2007 								Fecha Última Modificación : 27/05/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();	
		if ($as_tipcot=='B')
		   {
             $li_totrow = $ai_totrowbienes;
		   }			
		elseif($as_tipcot=='S')
		   {
		     $li_totrow = $ai_totrowservicios;
		   }
		$lb_valido=$this->uf_delete_detalles($as_numcot,$as_tipcot,$as_codpro,'DELETE',$li_totrow,$aa_seguridad);
		if($lb_valido)
		{
			$ls_sql="DELETE FROM soc_cotizacion WHERE codemp = '".$this->ls_codemp."' AND numcot = '".$as_numcot."' AND cod_pro='".$as_codpro."'";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->sigesp_soc_c_registro_cotizacion.php->MÉTODO->uf_delete_cotizacion.php->ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="DELETE";
				$ls_descripcion ="Elimino la Cotizacion ".$as_numcot." Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				if($lb_valido)
				{	
					$this->io_mensajes->message("La Cotización fue Eliminada !!!");
					$this->io_sql->commit();
					$this->io_sql->close();

				}
				else
				{
					$this->io_sql->rollback();
					$lb_valido=false;
					$this->io_mensajes->message("Ocurrio un Error al Eliminar la Cotización !!!."); 
				    $this->io_sql->close();
				}
			}
		}
		else
		{
			$this->io_mensajes->message("Ocurrio un Error al Eliminar la Cotización !!!."); 
			$this->io_sql->rollback();
			$this->io_sql->close();
		}
		return $lb_valido;
	}// end function uf_delete_cotizacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_detalles($as_numcot,$as_tipcot,$as_codpro,$as_desope,$ai_totrow,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_detalles
		//		   Access: private
		//	    Arguments: as_numcot  // Número de Solicitud de cotizacion.
		//				   as_tabla  // Tabla donde se deben insertar los cargos
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el mismo.
		//	  Description: Funcion que elimina los detalles de una cotizacion.
		//	   Creado Por: Ing. Néstor Falcon.
		// Fecha Creación: 27/05/2007 								Fecha Última Modificación : 27/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if ($as_tipcot=='B')
		   {
		     for($i=1;$i<$ai_totrow;$i++)
		        {
			      $ls_codart = $_POST["txtcodart".$i];
				  $ls_sql    = "DELETE FROM soc_dtcot_bienes ".
				               " WHERE codemp='".$this->ls_codemp."' AND numcot='".$as_numcot."' AND codart='".$ls_codart."' AND cod_pro='".$as_codpro."'";
				  $rs_data   = $this->io_sql->execute($ls_sql);//print $ls_sql;
				  if ($rs_data===false)
				     {
					   $this->io_sql->rollback();
					   $lb_valido = false;
			           $this->io_mensajes->message("CLASE->sigesp_soc_c_registro_cotizacion.php->MÉTODO->uf_delete_detalles(Bienes)->ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					 }
				}
			}
		elseif($as_tipcot=='S')
		    {
		      for ($i=1;$i<$ai_totrow;$i++)
		          {
			        $ls_codser = $_POST["txtcodser".$i];
				    $ls_sql    = "DELETE FROM soc_dtcot_servicio ".
					             " WHERE codemp='".$this->ls_codemp."' AND numcot='".$as_numcot."' AND codser='".$ls_codser."' AND cod_pro='".$as_codpro."'";
				    $rs_data   = $this->io_sql->execute($ls_sql);
				    if ($rs_data===false)
				       {
					     $this->io_sql->rollback();
					     $lb_valido = false;
			             $this->io_mensajes->message("CLASE->sigesp_soc_c_registro_cotizacion.php->MÉTODO->uf_delete_detalles(Servicios)->ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					   }
				  }
			}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó todos los detalles de la cotizacion ".$as_numcot." Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		return $lb_valido;
	}// end function uf_delete_detalles
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_bienes($as_numcot,$as_codpro)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_bienes
		//		   Access: public
		//		 Argument: as_numcot // Número de Cotización.
		//	  Description: Función que busca los bienes asociados a una solicitud de cotizacion para ser cargados en el Registro de la Cotizacion.
		//	   Creado Por: Ing. Nestor Falcon.
		// Fecha Creación: 28/05/2007								Fecha Última Modificación : 28/05/2007
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;

		$ls_sql ="SELECT soc_dtcot_bienes.codart, soc_dtcot_bienes.unidad, soc_dtcot_bienes.canart, soc_dtcot_bienes.preuniart, 
		                 soc_dtcot_bienes.moniva,soc_dtcot_bienes.monsubart, soc_dtcot_bienes.montotart, 
						 soc_dtcot_bienes.nivcalart, siv_articulo.denart, soc_cotizacion.estcot
					FROM soc_cotizacion, soc_dtcot_bienes, siv_articulo
				   WHERE soc_dtcot_bienes.codemp='".$this->ls_codemp."'
				     AND soc_dtcot_bienes.numcot='".$as_numcot."'
					 AND soc_dtcot_bienes.cod_pro='".$as_codpro."'
				     AND soc_cotizacion.codemp=soc_dtcot_bienes.codemp
				     AND soc_cotizacion.numcot=soc_dtcot_bienes.numcot
					 AND soc_cotizacion.cod_pro=soc_dtcot_bienes.cod_pro
				     AND soc_dtcot_bienes.codemp=siv_articulo.codemp
				     AND soc_dtcot_bienes.codart=siv_articulo.codart
				   ORDER BY soc_dtcot_bienes.orden ASC";//print $ls_sql;
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->sigesp_soc_c_registro_cotizacion.php->MÉTODO->uf_load_bienes.ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_bienes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_servicios($as_numcot,$as_codpro)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_servicios
		//		   Access: public
		//		 Argument: as_numcot // Número de la Cotización.
		//                 as_codpro //Código del Proveedor.
		//	  Description: Función que busca los bienes asociados a una solicitud de cotizacion
		//	   Creado Por: Ing. Nestor Falcon.
		// Fecha Creación: 28/05/2007								Fecha Última Modificación : 28/05/2007
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;

		$ls_sql ="SELECT soc_dtcot_servicio.codser, soc_dtcot_servicio.canser,soc_cotizacion.estcot,
					     soc_dtcot_servicio.monuniser, soc_dtcot_servicio.moniva, soc_dtcot_servicio.monsubser,
					     soc_dtcot_servicio.montotser, soc_dtcot_servicio.nivcalser, soc_servicios.denser
				    FROM soc_cotizacion, soc_dtcot_servicio, soc_servicios
				   WHERE soc_dtcot_servicio.codemp='".$this->ls_codemp."'
				     AND soc_dtcot_servicio.numcot='".$as_numcot."'
					 AND soc_dtcot_servicio.cod_pro='".$as_codpro."'
				     AND soc_cotizacion.codemp=soc_dtcot_servicio.codemp
				     AND soc_cotizacion.numcot=soc_dtcot_servicio.numcot
				     AND soc_cotizacion.cod_pro=soc_dtcot_servicio.cod_pro
					 AND soc_servicios.codemp=soc_dtcot_servicio.codemp
				     AND soc_servicios.codser=soc_dtcot_servicio.codser
				   ORDER BY soc_dtcot_servicio.orden ASC";//print $ls_sql;
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->sigesp_soc_c_solicitud_cotizacion.php->MÉTODO->uf_load_servicios ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_servicios
	//-----------------------------------------------------------------------------------------------------------------------------------

  function uf_load_porcentaje_credito($as_coditem,$as_tipitem,&$lb_valido)
  {
  ///////////////////////////////////////////////////////////////////////////////////////////////
  //	     Function: uf_load_porcentaje_credito
  //		   Access: public
  //		 Argument: as_coditem // Número de la Cotización.
  //                   as_tipitem //Código del Proveedor.
  //	  Description: Función que busca los cargos asociados al Item (Bien o Servicio).
  //	   Creado Por: Ing. Nestor Falcon.
  // Fecha Creación: 26/07/2007								Fecha Última Modificación : 26/07/2007
  ////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido = true;
    $ls_tabla1 = "";
	$ls_tabla2 = "";
    $ld_porcre = 0;
	
	switch ($as_tipitem){
	  case 'B':
	    $ls_tabla1 = "siv_articulo";
		$ls_tabla2 = " siv_cargosarticulo";
		$ls_campo  = "codart";
	  break;
	  case 'S':
	    $ls_tabla1 = "soc_servicios";
		$ls_tabla2 = "soc_serviciocargo";
		$ls_campo  = "codser";
	  break;
	}
	
	$ls_sql = "SELECT sigesp_cargos.porcar 
			     FROM sigesp_cargos, $ls_tabla1, $ls_tabla2
			    WHERE $ls_tabla2.codemp='".$this->ls_codemp."'
			      AND $ls_tabla2.$ls_campo='".$as_coditem."'
			      AND sigesp_cargos.codemp=$ls_tabla2.codemp
				  AND sigesp_cargos.codcar=$ls_tabla2.codcar
				  AND $ls_tabla1.codemp=$ls_tabla2.codemp
				  AND $ls_tabla1.$ls_campo=$ls_tabla2.$ls_campo";
	 $rs_dato = $this->io_sql->select($ls_sql);//print $ls_sql;
	 if ($rs_dato===false)
		{
		  $this->io_mensajes->message("CLASE->sigesp_soc_c_solicitud_cotizacion.php->MÉTODO->uf_load_porcentaje_credito ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		  return false;
		}
	 else
	    {
		  if ($row=$this->io_sql->fetch_row($rs_dato))
		     {
			   $ld_porcre = $row["porcar"];
			 }
		} 
	 return $ld_porcre;
  }//uf_load_porcentaje_credito.
}  
?>