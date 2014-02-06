<?php
class sigesp_ins_c_reprocesar_spg
{
	var $io_sql;
	var $io_message;
	var $io_function;
	var $is_msg_error;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_ins_c_reprocesar_spg()
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/class_fecha.php");
		require_once("../shared/class_folder/class_funciones.php");	
		require_once("../shared/class_folder/class_sigesp_int.php");
		require_once("../shared/class_folder/class_sigesp_int_scg.php");	
		require_once("../shared/class_folder/class_sigesp_int_spg.php");			
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$io_siginc=new sigesp_include();
		$con=$io_siginc->uf_conectar();
		$this->io_sql=new class_sql($con);
		$this->io_message=new class_mensajes();
		$this->io_function=new class_funciones();
		$this->io_int_spg=new class_sigesp_int_spg();
		$this->io_seguridad=new sigesp_c_seguridad();		
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_reprocesar_saldos($ls_codemp,$as_sin_validacion,$as_codestpro1desde,$as_codestpro1hasta,$aa_seguridad)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////
		// 	 Function:  uf_reprocesar_saldos
		// 	   Access:  public
		//  Arguments:  
		//	  Returns:  Boolean
		//Description:  Este método realiza la actualización de los saldos de las cuentas presupustarias 
		//				segun los movimientos realizado en base a las mismas.
		////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		//Actualizo los saldos de las cuentas a 0 
		$this->io_sql->begin_transaction();
		$ls_criterio="";
		if(trim($as_codestpro1desde)!="")
		{
			$as_codestpro1desde=str_pad($as_codestpro1desde,25,"0",0);
			$ls_criterio=$ls_criterio."   AND codestpro1>='".$as_codestpro1desde."' ";
		}
		if(trim($as_codestpro1hasta)!="")
		{
			$as_codestpro1hasta=str_pad($as_codestpro1hasta,25,"0",0);
			$ls_criterio=$ls_criterio."   AND codestpro1<='".$as_codestpro1hasta."' ";
		}
		$ls_sql="UPDATE spg_cuentas ".
				"   SET asignado=0, ".
				"       precomprometido=0, ".
				"       comprometido=0, ".
				"       causado=0, ".
				"       pagado=0, ".
				"       aumento=0, ".
				"       disminucion=0 ".
				" WHERE codemp ='".$ls_codemp."'".
				$ls_criterio;
		$li_numrow=$this->io_sql->execute($ls_sql);
		if($li_numrow===false)
		{
            $this->io_message->message("CLASE->Reprocesar SPG MÉTODO->uf_reprocesar_saldos ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}		
		//Arreglo con los codigos de las operaciones. Los duplicados es por la filas que se actualizan
		$la_mensajes=array(1=>'I',2=>'A',3=>'D',4=>'R',5=>'O',6=>'C',7=>'P',8=>'OC',9=>'OC',10=>'OCP',11=>'OCP',12=>'OCP',
						   13=>'CP',14=>'CP');
		//Arreglo de las filas que se van a actualizar
		$la_fila=array(1=>'asignado',2=>'aumento',3=>'disminucion',4=>'precomprometido',5=>'comprometido',
					   6=>'causado',7=>'pagado',8=>'comprometido',9=>'causado',10=>'comprometido',11=>'causado',
					   12=>'pagado',13=>'causado',14=>'pagado');
		$li_total_codigos=count($la_mensajes);	
		for($li_i=1;($li_i<=$li_total_codigos)&&($lb_valido);$li_i++)		
		{
			$ls_codigo=$this->io_int_spg->uf_operacion_mensaje_codigo($la_mensajes[$li_i]);
			$ls_mensaje=$this->io_int_spg->uf_operacion_codigo_mensaje($ls_codigo);
			$ls_sql="SELECT codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,estcla,fecha, spg_cuenta, sum(monto) AS monto ". 
					"  FROM spg_dt_cmp  ".
					" WHERE operacion='".$ls_codigo."' ".
					$ls_criterio.
					" GROUP BY codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,estcla,spg_cuenta,fecha ".
					" ORDER BY codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,estcla,spg_cuenta,fecha ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_message->message("CLASE->Reprocesar SPG MÉTODO->uf_reprocesar_saldos ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
				$lb_valido=false;
			}
			else
			{
				while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
				{
					$_SESSION["fechacomprobante"]=$this->io_function->uf_formatovalidofecha($row["fecha"]);
					$la_estprog[0]=$row["codestpro1"];
					$la_estprog[1]=$row["codestpro2"];
					$la_estprog[2]=$row["codestpro3"];
					$la_estprog[3]=$row["codestpro4"];
					$la_estprog[4]=$row["codestpro5"];
					$la_estprog[5]=$row["estcla"];
					$ldec_monto=$row["monto"];
					$ls_cuenta=$row["spg_cuenta"];
					$ls_fila=$la_fila[$li_i];
					$lb_valido=$this->uf_spg_saldos_update($ls_codemp,$la_estprog,$ls_cuenta,$ls_fila,$ldec_monto,$ls_mensaje,$as_sin_validacion);							
					if(!$lb_valido)
					{
						$this->io_message->message("Error al actualizar la cuenta ".$ls_cuenta." con mensaje ".$ls_mensaje." de programatica ".$la_estprog[0]."-".$la_estprog[1]."-".$la_estprog[2]."-".$la_estprog[3]."-".$la_estprog[4]."-".$la_estprog[5]);			
					}					
				}		
			}								
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reproceso los Saldos de Presupuesto de Gasto";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		if($lb_valido)
		{
			$this->io_sql->commit(); 
		}
		else
		{
			$this->io_sql->rollback();
		}
		return $lb_valido;	
	}//fin uf_reprocesar_saldos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spg_saldos_update($as_codemp, $estprog, $as_cuenta, $as_fila, $ai_valor, $as_mensaje, $as_sin_validacion )
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	 Function: uf_spg_saldos_update
		//	  Returns:  boolean si existe o  no 
		//Description:  actualiza el saldo de una cuenta
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
		$lb_valido=true;
		$ls_nextcuenta=$as_cuenta;
		$li_nivel=$this->io_int_spg->uf_spg_obtener_nivel($ls_nextcuenta);
		while(($li_nivel>=1)and($lb_valido)and($ls_nextcuenta!=""))
		{ 
			if ($this->uf_spg_saldo_select($as_codemp, $estprog, $ls_nextcuenta, &$ls_status, &$ld_asignado, &$ld_aumento, &$ld_disminucion, &$ld_precomprometido, &$ld_comprometido, &$ld_causado, &$ld_pagado))
			{				    
				if ($this->uf_spg_saldos_ajusta($estprog, $ls_nextcuenta, $as_mensaje, $ls_status, 0, $ai_valor, &$ld_asignado, &$ld_aumento, &$ld_disminucion, &$ld_precomprometido, &$ld_comprometido, &$ld_causado, &$ld_pagado,$as_sin_validacion))
			    {
					$ls_sql="UPDATE spg_cuentas ".
							"   SET ".$as_fila."=".$as_fila."+".$ai_valor."".
							" WHERE codemp='".$as_codemp."' ".
							"   AND codestpro1 ='".$estprog[0]."' ".
							"   AND codestpro2 ='".$estprog[1]."' ".
							"   AND codestpro3 ='".$estprog[2]."' ".
							"   AND codestpro4 ='".$estprog[3]."' ".
							"   AND codestpro5 ='".$estprog[4]."' ".
							"   AND estcla ='".$estprog[5]."' ".
							"   AND spg_cuenta = '".$ls_nextcuenta."'";
					$li_rows=$this->io_sql->execute($ls_sql);
					if($li_rows===false)
					{
						$this->is_msg_error="CLASE->sigesp_int_spg MÉTODO->uf_spg_saldos_update ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
						$lb_valido=false;
					}
				}
				else
				{
					$lb_valido=false;
				}
			}
			else
			{
				$lb_valido=false;
			}
			if($this->io_int_spg->uf_spg_obtener_nivel($ls_nextcuenta)==1)
			{
				break;
			}
			$ls_nextcuenta=$this->io_int_spg->uf_spg_next_cuenta_nivel($ls_nextcuenta);
			$li_nivel=$this->io_int_spg->uf_spg_obtener_nivel($ls_nextcuenta);
		}
		return $lb_valido;
	} // end function uf_spg_saldos_update
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spg_saldos_ajusta($estprog,$as_cuenta,$as_mensaje,$as_status,$adec_monto_anterior,$adec_monto_actual,
	                              &$adec_asignado,&$adec_aumento,&$adec_disminucion,&$adec_precomprometido,&$adec_comprometido,
								  &$adec_causado,&$adec_pagado,$as_sin_validacion)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	 Function: uf_spg_saldos_ajusta
		//	  Returns:  boolean si existe o  no 
		//Description:  ajusta el saldo de una cuenta
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
       $la_empresa    =  $_SESSION["la_empresa"];
       $ls_vali_nivel =  $la_empresa["vali_nivel"];
	   $lb_valido =true;
   	   $int_spg=new class_sigesp_int_spg();
	   $ldec_disponible = (($adec_asignado + $adec_aumento) - ( $adec_disminucion + $adec_comprometido + $adec_precomprometido));
	   $li_nivel = $int_spg->uf_spg_obtener_nivel( $as_cuenta );
	   $as_mensaje = strtoupper($as_mensaje);
	   $li_pos_i=strpos($as_mensaje,"I"); //I-Asignacion
	   if($as_sin_validacion==0)
	   {
		   if (!($li_pos_i===false))
		   {
			   $adec_asignado = $adec_asignado - $adec_monto_anterior + $adec_monto_actual;
			   $lb_procesado = true;
		   }
		   $li_pos_a=strpos($as_mensaje,"A"); // A-Aumento 
		   if (!($li_pos_a===false))
			   { 
			   $adec_aumento = $adec_aumento - $adec_monto_anterior + $adec_monto_actual;
			   $lb_procesado = true;
		   }
		   $li_pos_d=strpos($as_mensaje,"D"); //	D-Disminucion
		   if (!($li_pos_d===false))
		   {
			  if($adec_monto_actual <=( $ldec_disponible + $adec_monto_anterior))  {$adec_disminucion = $adec_disminucion - $adec_monto_anterior + $adec_monto_actual; }
			  else
			  {
				$lb_valido = false;
				$this->io_message->message( "El monto a disminuir es mayor que la Disponibilidad. ");			
			  }
			  $lb_procesado = true	;
		   }
		   $li_pos_r=strpos($as_mensaje,"R"); //R-PreComprometer
		   if (!($li_pos_r===false))
		   {
			 if ($li_nivel < $ls_vali_nivel)
			 {
				if ($adec_monto_actual > ($ldec_disponible + $adec_monto_anterior))
				{
					$lb_valido = false;
					$this->io_message->message( "Error de disponibilidad Presupuestaria");	
				 }				
				 else 
				 {
					$adec_precomprometido = $adec_precomprometido - $adec_monto_anterior + $adec_monto_actual;
				 }
			 } 	
			 else 
			 { 
			   $adec_precomprometido = $adec_precomprometido - $adec_monto_anterior + $adec_monto_actual;
			 }
			 $lb_procesado = true	;
		   }
		   $li_pos_o=strpos($as_mensaje,"O"); //	O-Comprometer
		   if (!($li_pos_o===false))
		   {
			 if ($li_nivel < $ls_vali_nivel) 
			 {
				 if($adec_monto_actual > ( $ldec_disponible + $adec_monto_anterior))
				 {
					 $lb_valido = false;
					$this->io_message->message( "Error de disponibilidad Presupuestaria");	
				 }			
				 else { $adec_comprometido = $adec_comprometido - $adec_monto_anterior + $adec_monto_actual;}
			 }	
			 else {	$adec_comprometido = $adec_comprometido - $adec_monto_anterior + $adec_monto_actual;}
			 $lb_procesado = true;
		  }
		 $li_pos_c=strpos($as_mensaje,"C"); 	//	C-Causar
		 if (!($li_pos_c===false))
		 {
			 if ($as_status=="C") // solo valido cuenta de movimiento
			 {
				 if(($ldec_causado - $adec_monto_anterior + $adec_monto_actual) <= $ldec_comprometido) {	$adec_causado = $adec_causado - $adec_monto_anterior + $adec_monto_actual;}
				 else
				 {		
					$lb_valido = false;
					$this->io_message->message("Intenta Causar mas que lo Comprometido ".$ls_programatica_cuenta );
				}
			 }
			 else {$adec_causado = $adec_causado - $adec_monto_anterior + $adec_monto_actual;}
			 $lb_procesado = true;
		  }
		 $li_pos_p=strpos($as_mensaje,"P");  // P-Pagar
		 if (!($li_pos_p===false))
		 {
			if ($as_status=="C") // solo valido cuenta de movimiento
			{
				if (($ldec_pagado - $adec_monto_anterior + $adec_monto_actual) <= $ldec_causado){$adec_pagado = $adec_pagado - $adec_monto_anterior + $adec_monto_actual;}
				else
				{
					$lb_valido = false;
					$this->io_message->message(" Intenta Pagar mas que lo Causado ".$ls_programatica_cuenta);
				}
			}	
			else {$adec_pagado = $adec_pagado - $adec_monto_anterior + $adec_monto_actual;}
			$lb_procesado = true;
		  }
		 if(!$lb_procesado)
		 {
			$this->io_message->message(" El codigo de mensaje es Invalido : ".as_mensaje);
			$lb_valido = false;
		 }
	  }	 
	  elseif($as_sin_validacion==1)// sin la validacion 
	  {
		   if (!($li_pos_i===false))
		   {
			   
			   $adec_asignado = $adec_asignado - $adec_monto_anterior + $adec_monto_actual;
			   $lb_procesado = true;
		   }
		   $li_pos_a=strpos($as_mensaje,"A"); // A-Aumento 
		   if (!($li_pos_a===false))
			   { 
			   $adec_aumento = $adec_aumento - $adec_monto_anterior + $adec_monto_actual;
			   $lb_procesado = true;
		   }
		   $li_pos_d=strpos($as_mensaje,"D"); //	D-Disminucion
		   if (!($li_pos_d===false))
		   {
			  if($adec_monto_actual <=( $ldec_disponible + $adec_monto_anterior))  
			  {
			    $adec_disminucion = $adec_disminucion - $adec_monto_anterior + $adec_monto_actual;
			  }
			  $lb_procesado = true	;
		   }
		   $li_pos_r=strpos($as_mensaje,"R"); //R-PreComprometer
		   if (!($li_pos_r===false))
		   {
			 if ($li_nivel < $ls_vali_nivel)
			 {
				$adec_precomprometido = $adec_precomprometido - $adec_monto_anterior + $adec_monto_actual;
			 } 	
			 else 
			 { 
			    $adec_precomprometido = $adec_precomprometido - $adec_monto_anterior + $adec_monto_actual;
			 }
			 $lb_procesado = true	;
		   }
		   $li_pos_o=strpos($as_mensaje,"O"); //	O-Comprometer
		   if (!($li_pos_o===false))
		   {
			 if ($li_nivel < $ls_vali_nivel) 
			 {
				 $adec_comprometido = $adec_comprometido - $adec_monto_anterior + $adec_monto_actual;
			 }	
			 else 
			 {	
			    $adec_comprometido = $adec_comprometido - $adec_monto_anterior + $adec_monto_actual;
			 }
			 $lb_procesado = true;
		  }
		 $li_pos_c=strpos($as_mensaje,"C"); 	//	C-Causar
		 if (!($li_pos_c===false))
		 {
			 if ($as_status=="C") // solo valido cuenta de movimiento
			 {
				 if(($adec_causado - $adec_monto_anterior + $adec_monto_actual) <= $adec_comprometido) 
				 {	
				   $adec_causado = $adec_causado - $adec_monto_anterior + $adec_monto_actual;
				 }
				 else
				 {		
				   $adec_causado = $adec_causado - $adec_monto_anterior + $adec_monto_actual;
				}
			 }
			 else 
			 {
			   $adec_causado = $adec_causado - $adec_monto_anterior + $adec_monto_actual;
			 }
			 $lb_procesado = true;
		  }
		 $li_pos_p=strpos($as_mensaje,"P");  // P-Pagar
		 if (!($li_pos_p===false))
		 {
			if ($as_status=="C") // solo valido cuenta de movimiento
			{
				if (($adec_pagado - $adec_monto_anterior + $adec_monto_actual) <= $adec_causado)
				{
				  $adec_pagado = $adec_pagado - $adec_monto_anterior + $adec_monto_actual;
				}
				else
				{
				  $adec_pagado = $adec_pagado - $adec_monto_anterior + $adec_monto_actual;
				}
			}	
			else 
			{
			  $adec_pagado = $adec_pagado - $adec_monto_anterior + $adec_monto_actual;
			}
			$lb_procesado = true;
		  }
		 if(!$lb_procesado)
		 {
			$this->io_message->message(" El codigo de mensaje es Invalido : ".as_mensaje);
			$lb_valido = false;
		 }
	  }
	  return $lb_valido;
    }//fin uf_spg_saldos_ajusta
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spg_saldo_select($as_codemp, $estprog, $as_cuenta, &$as_status, &$adec_asignado, &$adec_aumento, &$adec_disminucion,
								 &$adec_precomprometido, &$adec_comprometido,&$adec_causado, &$adec_pagado)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spg_saldo_select
		//		   Access: public 
		//       Argument: as_codemp // Código de Empresa
		//				   estprog //  Estructura Programatica
		//				   as_cuenta // Cuenta 
		//				   as_status // Estatus de la Cuenta
		//				   adec_asignado // Monto del Asignado
		//				   adec_aumento // Monto del Aumento
		//				   adec_disminucion //  Monto de la Disminución
		//				   adec_precomprometido // Monto del Precomprometido
		//				   adec_comprometido // Monto del comprometido
		//				   adec_causado // Monto del Causado
		//				   adec_pagado // Monto del Pagado 
		//	  Description: verifica si existe un saldo a esa cuenta
		//	      Returns: boolean si existe o  no 
		//	   Creado Por: Ing. wilmer Briceño
		// Modificado Por: Ing. Yozelin Barragan					Fecha Última Modificación : 06/02/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido =true;
		$ls_sql="SELECT status,asignado,aumento,disminucion,precomprometido,comprometido,causado,pagado ".
				"  FROM spg_cuentas ".
				" WHERE codemp='".$as_codemp."' ".
				"   AND codestpro1 = '".$estprog[0]."' ".
				"   AND codestpro2 = '".$estprog[1]."' ".
				"   AND codestpro3 = '".$estprog[2]."' ".
				"   AND codestpro4 = '".$estprog[3]."' ".
				"   AND codestpro5 = '".$estprog[4]."' ".
				"   AND spg_cuenta = '".$as_cuenta."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_spg MÉTODO->uf_spg_saldo_select ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{  
				$as_status=$row["status"];
				$adec_asignado=$row["asignado"];
				$adec_aumento=$row["aumento"];
				$adec_disminucion=$row["disminucion"];
				$adec_precomprometido=$row["precomprometido"];
				$adec_comprometido=$row["comprometido"];
				$adec_causado=$row["causado"];
				$adec_pagado=$row["pagado"];
			}
			$this->io_sql->free_result($rs_data);		
		}
		return $lb_valido;
	} // end function uf_spg_saldo_select
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>