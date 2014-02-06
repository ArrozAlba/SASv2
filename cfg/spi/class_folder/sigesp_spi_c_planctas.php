<?php
class sigesp_spi_c_planctas
{
	 var $int_scg;
	 var $dat;
	 var $msg;
	 var $fun;
	 var $int_spi;
	 var $is_msg_error;
	 var $io_seguridad;
	 
	function sigesp_spi_c_planctas()
	{
		require_once("../../shared/class_folder/sigesp_c_check_relaciones.php");
		require_once("../../shared/class_folder/class_mensajes.php");
		require_once("../../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../../shared/class_folder/class_funciones.php");
		require_once("../../shared/class_folder/class_sigesp_int_scg.php");
		require_once("../../shared/class_folder/class_sigesp_int_spi.php");
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_conect          = new sigesp_include();
        $conn               = $io_conect->uf_conectar ();
		$this->msg          = new class_mensajes();
		$this->fun          = new class_funciones();
		$this->msg          = new class_mensajes();
		$this->int_scg      = new class_sigesp_int_scg();	
		$this->int_spi      = new class_sigesp_int_spi();
		$this->dat          = $_SESSION["la_empresa"];
		$this->io_seguridad = new sigesp_c_seguridad();
	    $this->io_chkrel    = new sigesp_c_check_relaciones($conn);
	}

function uf_valida_cuenta($as_cuenta ,$as_cuenta_scg)
{
		
	$ls_programa ="";$ls_Status="";
	$ls_spi_cuenta=trim($as_cuenta);
	$ls_formato=trim($this->dat["formplan"]);
	$ls_formato_spi=trim($this->dat["formspi"]);
	$ls_cuenta_pad=$this->int_scg->uf_pad_cuenta_plan(&$ls_formato,$ls_spi_cuenta);

	$li_len_cta=strlen($ls_cuenta_pad);
	$li_len_formato=strlen($ls_formato);
	
	if($li_len_cta!=$li_len_formato)
	{
		$this->msg->message("Cuentas no poseen el formato del plan unico");
		return false;
	}
	
	$li_len_ctaspi=strlen($ls_spi_cuenta);
	$li_len_formato_spi=strlen(str_replace('-','',$ls_formato_spi));
	
	if($li_len_ctaspi!=$li_len_formato_spi)
	{
		$this->msg->message("Formato de presupuesto ".$ls_formato_spi." no corresponde al de la cuenta introducida ".$ls_spi_cuenta);
		return false;
	}
	if($li_len_cta<$li_len_ctaspi)
	{
		$ls_NextCuenta = $ls_cuenta_pad;
	}
	else
	{
		$ls_NextCuenta = $this->int_spi->uf_spi_next_cuenta_nivel($ls_cuenta_pad);			
	}
	
	$lb_valido=$this->int_scg->uf_select_plan_unico_cuenta_recurso($ls_NextCuenta,&$as_denominacion);
	if(!$lb_valido)
	{
	/*	$this->msg->message("La Cuenta no Existe en el Plan Unico de Cuentas de Recursos y Egresos");
		return false;*/
		$lb_valido=$this->int_scg->uf_select_plan_unico_cuenta_recurso($ls_spi_cuenta,&$as_denominacion);
	}
	if(substr($ls_spi_cuenta,0,1)!=trim($this->dat["ingreso_p"]))
	{
		$this->msg->message("Las Cuentas de Ingreso deben comenzar con ".$this->dat["ingreso_p"]);
		return false;
	}

	// Verifico si es de nivel apropiado

	$li_nivel=$this->int_spi->uf_spi_obtener_nivel($ls_spi_cuenta);
	
	if($li_nivel <= 1)
	{
		$this->msg->message("Las Cuentas de Nivel 'Partida' no son Validas");
		return false;
	}
	
	if($li_nivel<= 2)
	{
		$this->msg->message("Las Cuentas de Nivel 'Genericas' no son Validas");
		return false;
	}	
		
//	*-- Verifico que si no hay cuentas con movimientos de nivel superior
	$li_nivel =$this->int_spi->uf_spi_obtener_nivel($ls_spi_cuenta);
	
	if($li_nivel > 1)
	{
		$ls_NextCuenta = $this->int_spi->uf_spi_next_cuenta_nivel($ls_spi_cuenta);
		
		do 
		{
			if($this->int_spi->uf_spi_select_cuenta($this->dat["codemp"], $ls_NextCuenta,&$as_status,&$as_denominacion, $as_cuenta_scg))
			{
				if($as_status=="C")
				{
					$this->msg->message("Existen cuentas de nivel superior con Movimiento");
					return false;
				}
			}
			$ls_NextCuenta = $this->int_spi->uf_spi_next_cuenta_nivel($ls_NextCuenta);

		$li_nivel=$this->int_spi->uf_spi_obtener_nivel($ls_NextCuenta);
		}while( $li_nivel > 1);
	}
	return true;
}//uf_valida_cuenta

function uf_procesar_cuentas($as_cuenta_spi,$as_denominacion_cta,$as_cuenta_scg,$aa_security)
{
		$ls_spi_cuenta=$as_cuenta_spi;
		$ls_cuenta_tempo=$ls_spi_cuenta;
		$ls_denominacion_cta=$as_denominacion_cta;
		$ls_scg_cuenta=$as_cuenta_scg;
		$lb_valido=true;							
		//Tomo los valores anteriores de la cuenta y denominacion.
		if($this->int_spi->uf_spi_select_cuenta($this->dat["codemp"],$ls_spi_cuenta, &$ls_status, &$ls_denominacion, $ls_scg_cuenta ))
		{
				$lb_valido=$this->int_spi->uf_spi_update_cuenta($ls_spi_cuenta,$ls_denominacion_cta,$as_cuenta_scg);
				if($lb_valido)
				{
					$ls_ctapu="";
					$ls_denctapu="";
					$ls_ctaplan="";
					$this->is_msg_error="Denominación Actualizada";
					$disabled="";
					$this->int_spi->io_sql->commit();
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="UPDATE";
					$ls_desc_event="Actualizo la cuenta ".$ls_spi_cuenta." asociada a la cuenta contable ".$ls_scg_cuenta; 
					$ls_variable= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_security[1],$aa_security[2],$ls_evento,$aa_security[3],$aa_security[4],$ls_desc_event);
					////////////////////////////////         SEGURIDAD               //////////////////////////////
					
				}
				else
				{
					$this->is_msg_error="Error ".$this->int_spi->io_sql->message;
				}
		}
		else
		{
			$ls_cuenta_tempo = $ls_spi_cuenta;
			$ls_denominacion = "";
			$ls_NextCuenta = $this->int_spi->uf_spi_next_cuenta_nivel($ls_spi_cuenta);
			$li_Nivel      = $this->int_spi->uf_spi_obtener_nivel($ls_NextCuenta);
			$li_fila = 1	; 	 
			$lds_cuenta_temp=new class_datastore();		
			do 
			{
			  if(!$this->int_spi->uf_spi_select_cuenta($this->dat["codemp"],$ls_NextCuenta, &$ls_status, &$ls_denominacion, $ls_scg_cuenta ))
			  {
				  $ls_PadNextCuenta =$this->int_scg->uf_pad_cuenta_plan($this->dat["formplan"],$ls_NextCuenta);
 				  $this->int_spi->uf_select_plan_unico_cuenta($ls_PadNextCuenta,&$as_denominacion_plan);
										
					  if($li_Nivel > 1)
					  {
						  $ls_cuenta_ref = $this->int_spi->uf_spi_next_cuenta_nivel( $ls_NextCuenta );
					  }
					  else	
					  {
						  $ls_cuenta_ref = "             ";
					  }
					  
					  $lds_cuenta_temp->insertRow("sc_cuenta",trim($ls_scg_cuenta));
					  $lds_cuenta_temp->insertRow("spi_cuenta",$ls_NextCuenta);
					  $lds_cuenta_temp->insertRow("denominacion",$as_denominacion_plan);				  			  
					  $lds_cuenta_temp->insertRow("sc_cuenta_ref",$ls_cuenta_ref);				  			  
					  $lds_cuenta_temp->insertRow("nivel",$li_Nivel);				  			  
					  $li_fila =  $li_fila + 1;								  
				  } 
	
				if ($li_Nivel > 1)
				{
						$ls_NextCuenta = $this->int_spi->uf_spi_next_cuenta_nivel( $ls_NextCuenta );
						$li_Nivel      = $this->int_spi->uf_spi_obtener_nivel( $ls_NextCuenta );
						
				}
				else
				{
					$li_Nivel = 0 ;
				}
			}while( $li_Nivel >= 1);
			
			$li_total = $lds_cuenta_temp->getRowCount("sc_cuenta");
			
			 if($li_total>0)
			 {
				 $this->int_spi->io_sql->begin_transaction();
				for($li_fila=1;$li_fila<=$li_total;$li_fila++)
				{
					 $ls_scg_cuenta  = $lds_cuenta_temp->getValue("sc_cuenta",$li_fila); 	    
					 $ls_spi_cuenta  = $lds_cuenta_temp->getValue("spi_cuenta",$li_fila);
					 $ls_denominacion= $lds_cuenta_temp->getValue("denominacion",$li_fila) ;	    		 
					 $ls_cuenta_ref  = $lds_cuenta_temp->getValue("sc_cuenta_ref",$li_fila) ;	    		 
					 $li_Nivel       = $lds_cuenta_temp->getValue("nivel",$li_fila); 	    		 
					 $ls_mensaje_error="Error en Guardar";
					 $ls_status = "S";

					 $lb_valido = $this->int_spi->uf_spi_insert_cuenta($ls_spi_cuenta,$ls_denominacion,trim($ls_scg_cuenta),$ls_status,$li_Nivel,$ls_cuenta_ref);
					 if (!$lb_valido)
					 {
						break; 
					 }
					 else
					 {
						 /////////////////////////////////         SEGURIDAD               /////////////////////////////
						$ls_evento="INSERT";
						$ls_desc_event="Inserto la cuenta ".$ls_spi_cuenta.", asociada a la cuenta contable ".$ls_scg_cuenta; 
						//////////////////////////////         SEGURIDAD               /////////////////////////////
					 }	
				}
			 }
				
			 if($lb_valido)
			 {
				
					$ls_cuenta = $ls_cuenta_tempo;
					$ls_Cuenta_temp = $this->int_spi->uf_spi_padcuenta_plan( $this->dat["formplan"] , $ls_cuenta);
					$ls_NextCuenta = $this->int_spi->uf_spi_next_cuenta_nivel($ls_cuenta);
					$li_Nivel      = $this->int_spi->uf_spi_obtener_nivel($ls_cuenta);
					$ls_status	  = "C";
					$lb_valido = 	$this->int_spi->uf_spi_insert_cuenta($ls_cuenta,$ls_denominacion_cta,$ls_scg_cuenta,$ls_status,$li_Nivel,$ls_NextCuenta);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="INSERT";
					$ls_desc_event="Inserto la cuenta ".$ls_cuenta.", asociada  a la cuenta contable ".$ls_scg_cuenta; 
					//////////////////////////////         SEGURIDAD               /////////////////////////////
			 }
			
				 if ($lb_valido)
				 {
						$this->int_spi->io_sql->commit();
						$this->is_msg_error="Registro guardado";
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
						$ls_variable= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_security[1],$aa_security[2],$ls_evento,$aa_security[3],$aa_security[4],$ls_desc_event);
						////////////////////////////////         SEGURIDAD               //////////////////////////////
				 }
				 else
				 {
					   $this->int_spg->io_sql->rollback();
  					   $this->is_msg_error="Error al guardar cuenta ".$ls_cuenta;
				 }
		}
		return $lb_valido;
	}

function uf_procesar_delete_cuenta($as_cuenta_spi,$as_dencuentaspi,$as_cuenta_scg,$lb_existe,$aa_security)
{
	$ls_codemp=$this->dat["codemp"];
	$lb_valido=false;
	$ls_condicion = " AND column_name='spi_cuenta'";//Nombre del o los campos que deseamos buscar.
	$ls_mensaje   = "";                             //Mensaje que será enviado al usuario si se encuentran relaciones a asociadas al campo.
	$lb_tiene     = $this->io_chkrel->uf_check_relaciones($ls_codemp,$ls_condicion,'spi_cuentas',$as_cuenta_spi,$ls_mensaje);
	$lb_existe=$this->int_spi->uf_spi_select_cuenta($ls_codemp, $as_cuenta_spi, &$ls_status, &$ls_denominacion, $as_cuenta_scg );
	if($lb_existe)
	{
		if($lb_tiene)
		{
			$this->msg->message("Existen movimientos asociados a la cuenta ".$as_cuenta_spi." cuya cuenta Contable es ".$as_cuenta_scg);
			return false;
		}
		else
		{				
				$ls_cuenta_cero = $this->int_spi->uf_spi_cuenta_sin_cero($as_cuenta_spi);
				$li_total_rows = $this->int_spi->uf_spi_select_cuenta_sin_cero($ls_codemp,$ls_cuenta_cero);										
				if($li_total_rows > 1)
				{
				   $lb_valido=false;
				}
				else 
				{
					
					$lb_valido = $this->int_spi->uf_spi_delete_cuenta($ls_codemp, $as_cuenta_spi);   
					$ls_NextCuenta = $this->int_spi->uf_spi_next_cuenta_nivel($ls_cuenta_cero);
					$li_Nivel      = $this->int_spi->uf_spi_obtener_nivel($ls_NextCuenta);
					
						do 
						{
							if($this->int_spi->uf_spi_select_cuenta($ls_codemp,$ls_NextCuenta, &$ls_status, &$ls_denominacion, $as_cuenta_scg ))
							{
								 $ls_PadNextCuenta =$this->int_spi->uf_spi_padcuenta_plan($this->dat["formplan"] , $ls_NextCuenta);				
								 $this->int_scg->uf_select_plan_unico_cuenta($ls_PadNextCuenta,&$as_denominacion_plan);
																
									if($li_Nivel > 1)
									{
										 $ls_cuenta_ref = $this->int_spi->uf_spi_next_cuenta_nivel( $ls_NextCuenta );
									}
									else	
									{
										 $ls_cuenta_ref = "             ";
									}
									$ls_cuenta_cero = $this->int_spi->uf_spi_cuenta_recortar_next($ls_NextCuenta);
									$li_total_rows = $this->int_spi->uf_spi_select_cuenta_sin_cero($ls_codemp,$ls_cuenta_cero);
									
									if($li_total_rows>1)
									{
										//$this->msg->message("Existen cuentas de nivel inferior ... no se puede eliminar.");				
									}
									else
									{		  
										$lb_valido = $this->int_spi->uf_spi_delete_cuenta($ls_codemp,$ls_NextCuenta);   
										
									}
										  
							} 
							if ($li_Nivel > 1)
							{
								$ls_NextCuenta = $this->int_spi->uf_spi_next_cuenta_nivel( $ls_NextCuenta );
								$li_Nivel      = $this->int_spi->uf_spi_obtener_nivel( $ls_NextCuenta );										
							}
							else
							{
								$li_Nivel = 0 ;
							}
						}while( $li_Nivel >= 1);
						
				}
					if($lb_valido)
					{
					   $this->int_spi->io_sql->commit();
					   $this->is_msg_error="Registro Eliminado";
					   /////////////////////////////////         SEGURIDAD               /////////////////////////////
						$ls_evento="DELETE";
						$ls_desc_event="Elimino la cuenta ".$as_cuenta_spi." asociada a la cuenta contable ".$as_cuenta_scg;
						$ls_variable= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_security[1],$aa_security[2],$ls_evento,$aa_security[3],$aa_security[4],$ls_desc_event);
						////////////////////////////////         SEGURIDAD               //////////////////////////////
					}
					else
					{
					   $this->int_spi->io_sql->rollback();
					   $this->is_msg_error=$this->int_spg->is_msg_error;
					}
			}
	}
	return $lb_valido;
}
}
?>