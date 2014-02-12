<?php
class sigesp_spg_c_transferencia
{
	var $is_msg_error;
	var $io_sql;
	var $io_include;
	var $io_int_scg;
	var $io_int_spg;
	var $io_msg;
	var $io_function;
	var $is_codemp;
	var $is_procedencia;
	var $is_comprobante;
	var $is_cod_prov;
	var $is_ced_ben;
	var $id_fecha;
	var $ii_tipo_comp;
	var $is_descripcion;
	var $is_tipo;
	var $ib_contabilizar;
	var $ib_spg_enlace_contable;
	var $as_codban;
	var $as_ctaban;
	var $is_comprobante_ori;
function sigesp_spg_c_transferencia($as_hostname, $as_login, $as_password,$as_database,$as_gestor)
{
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_fecha.php");	
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_sigesp_int.php");
	require_once("../shared/class_folder/class_sigesp_int_int.php");	
	require_once("../shared/class_folder/class_sigesp_int_scg.php");
	require_once("../shared/class_folder/class_sigesp_int_spg.php");
	require_once("../shared/class_folder/class_sigesp_int_spi.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$this->bddestino=$as_database;
    $this->io_include=new sigesp_include();
	$this->io_connect_destino=$this->io_include->uf_conectar_otra_bd($as_hostname, $as_login, $as_password,$as_database,$as_gestor);	
	$this->io_function=new class_funciones();	
	$this->sig_int=new class_sigesp_int();
	$this->sig_int->io_sql=new class_sql($this->io_connect_destino);
	
	$this->sig_int_int=new class_sigesp_int_int();
	$this->sig_int_int->io_sql=new class_sql($this->io_connect_destino);
	$this->sig_int_int->int_spg->io_sql=new class_sql($this->io_connect_destino);
	$this->sig_int_int->int_scg->io_sql=new class_sql($this->io_connect_destino);
	$this->sig_int_int->int_spi->io_sql=new class_sql($this->io_connect_destino);
		
    $this->io_fecha=new class_fecha();
	$this->io_connect=$this->io_include->uf_conectar();
	$this->io_sql=new class_sql($this->io_connect);
	$this->io_sql_destino=new class_sql($this->io_connect_destino);
	$this->io_msg = new class_mensajes();
	$this->io_int_spg=new class_sigesp_int_spg();
	$this->io_int_spg->io_sql=new class_sql($this->io_connect_destino);
	$this->io_int_spg->sig_int->io_sql=new class_sql($this->io_connect_destino);
	$this->io_int_spg->io_int_scg->io_sql=new class_sql($this->io_connect_destino);
	$this->io_int_spi=new class_sigesp_int_spi();
	$this->io_int_spi->io_sql=new class_sql($this->io_connect_destino);	
	$this->io_int_spi->sig_int->io_sql=new class_sql($this->io_connect_destino);
	$this->io_int_spi->io_int_scg->io_sql=new class_sql($this->io_connect_destino);	
	$this->io_int_scg=new class_sigesp_int_scg();
	$this->io_int_scg->io_sql=new class_sql($this->io_connect_destino);
	$this->is_msg_error="";
	$this->io_seguridad= new sigesp_c_seguridad;
	$this->dts_empresa=$_SESSION["la_empresa"];
	$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$this->ls_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
	$this->ls_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
	$this->ls_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
	$this->ls_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
	$this->ls_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
}
/**********************************************************************************************************************************/

	function uf_transferir_comprobantes($as_codempdes,$as_comprobante,$as_fecha,$as_fecconta,$as_procede,$as_codban,$as_ctaban)
	{
	  $lb_existe_cmp=false;
	  $lb_valido=true;
	  $lb_resultado=true;
	  $ls_newfec=$this->io_function->uf_convertirdatetobd($as_fecha);
	  $this->io_sql_destino->begin_transaction();
	  $lb_valido = $this->uf_select_comprobante($as_codempdes,$as_procede,$as_comprobante,$ls_newfec);
	  if (!$lb_valido)
	     {
		   $lb_valido = $this->uf_procesar_cmp_md($as_codempdes,$as_comprobante,$ls_newfec,$as_procede);
		   if ($lb_valido)
		      {
		        $ls_newfecconta = $this->io_function->uf_convertirdatetobd($as_fecconta);
		 		$_SESSION["fechacomprobante"] = $ls_newfecconta;
		        $lb_valido = $this->uf_procesar_cmp($as_codempdes,$as_comprobante,$ls_newfecconta,$as_procede,$as_codban,$as_ctaban);
				if ($lb_valido)
				   {
				     $this->io_sql_destino->commit();
				     $ls_descripcion = "Se traspasó la Solicitud de Modificación Presupuestaria Nro. ".$as_comprobante." de procedencia ".$as_procede." y fecha ".$as_fecconta." de manera exitosa ";
				     $lb_resultado=$this->uf_insertar_resultado($_SESSION["ls_database"],$this->bddestino,$ls_descripcion); 
				   }
		        else
		           {
				     $this->io_sql_destino->rollback();
				     $ls_descripcion = "No se pudo traspasar el Comprobante de Presupuesto Nro. ".$as_comprobante." de procedencia ".$as_procede." y fecha ".$as_fecconta." ";
				     $lb_resultado=$this->uf_insertar_resultado($_SESSION["ls_database"],$this->bddestino,$ls_descripcion); 
				   }
	    
	          }
	       else
		      {
			    $this->io_sql_destino->rollback();
			    $ls_descripcion = "No se pudo traspasar la Solicitud de Modificación Presupuestaria Nro. ".$as_comprobante." de procedencia ".$as_procede." y fecha ".$as_fecconta." ";
			    $lb_resultado=$this->uf_insertar_resultado($_SESSION["ls_database"],$this->bddestino,$ls_descripcion);
		      }		   	   
	     }
	 return $lb_valido;
	}
//--------------------------------------------------------------------------------------------------------------------------------------	
	
	function uf_transferir_asipre($as_codempdes,$as_comprobante,$as_fecha,$as_procede,$as_codban,$as_ctaban,$as_origen)
	{
	  $lb_existe_cmp=false;
	  $lb_valido=true;
	  $lb_resultado=true;
	  $this->is_comprobante_ori = $as_comprobante;
	  $this->io_sql_destino->begin_transaction();
	  $lb_valido = $this->uf_select_comp_int($as_codempdes,$as_procede,$as_comprobante,$as_fecha,$as_codban,$as_ctaban);
	  if (!$lb_valido)
	  {
		 $_SESSION["fechacomprobante"] = $as_fecha;
		 $lb_valido = $this->uf_procesar_cmp_int($as_codempdes,$as_comprobante,$as_fecha,$as_procede,$as_origen,$as_codban,$as_ctaban);

		 if($lb_valido)
		 {
		  $this->io_sql_destino->commit();
		  $ls_descripcion = "Se traspasó el Comprobante de Presupuesto Nro. ".$as_comprobante." de procedencia ".$as_procede." y fecha ".$as_fecha." de manera exitosa ";
		  $lb_resultado=$this->uf_insertar_resultado($_SESSION["ls_database"],$this->bddestino,$ls_descripcion); 
		 }
		 else
		 {
		  $this->io_sql_destino->rollback();
		  $ls_descripcion = "No se pudo traspasar el Comprobante de Presupuesto Nro. ".$as_comprobante." de procedencia ".$as_procede." y fecha ".$as_fecha." ";
		  $lb_resultado=$this->uf_insertar_resultado($_SESSION["ls_database"],$this->bddestino,$ls_descripcion); 
		 }
	    		   	   
	  }
	 //$this->io_sql_destino->close();
	 return $lb_valido;
	}
//--------------------------------------------------------------------------------------------------------------------------------------	
	
	
	function uf_reversar_transferencia_comprobantes($as_comprobante,$as_fecha,$as_fecconta,$as_procede,$as_codban,$as_ctaban,$aa_seguridad)
	{
		 $lb_valido = true;
		 $ls_descripcion    ="";
		 $li_tipo_comp      ="";
		 $ls_tipo_destino   ="";
		 $ls_cod_pro        ="";
		 $ls_ced_bene       ="";
		 $li_total          ="";
		 $li_numpolcon      ="";
		 $li_totalaux       ="";
		 $ls_codban         = '---';
		 $ls_ctaban         = '-------------------------';
		 $this->sig_int_int->ib_procesando_cmp=false;
		 
		 $lb_valido=$this->uf_cargar_cmp($this->ls_codemp,$as_procede,$as_comprobante,$as_fecconta,$as_codban,$as_ctaban,
		                                 $ls_descripcion,$li_tipo_comp,$ls_tipo_destino,$ls_cod_pro,$ls_ced_ben,$li_total,$li_numpolcon,$li_totalaux);
		 if ($lb_valido)
		 {
			$checkclose = false;
			$as_fecha=$this->io_function->uf_convertirdatetobd($as_fecha);											
			$lb_valido= $this->sig_int_int->uf_init_delete($this->ls_codemp,$as_procede,$as_comprobante,$as_fecha,
			                                               $ls_tipo_destino,$ls_ced_ben,$ls_cod_pro,$checkclose,
														   $ls_codban,$ls_ctaban);	
			
			if($lb_valido === true)
			{
				    $this->sig_int_int->uf_int_init_transaction_begin();  
					$lb_valido = $this->sig_int_int->uf_init_end_transaccion_integracion($aa_seguridad);
					if($lb_valido)
					{
					 $ls_descripcion = "El traspaso del Comprobante Presupuestario Nro. ".$as_comprobante." de procedencia ".$as_procede." y fecha ".$as_fecconta." fue reversada por inconsistencia";
		             $lb_resultado=$this->uf_insertar_resultado($_SESSION["ls_database"],$this->bddestino,$ls_descripcion);
					}
					else
					{ 			
				     $this->io_msg->message("Error: ".$this->sig_int_int->is_msg_error);
					}	   	
			}
		
		 }
		  $this->sig_int_int->uf_sql_transaction($lb_valido);
		  return $lb_valido;
	}//fin uf_reversar_transferencia_comprobantes
//-------------------------------------------------------------------------------------------------------------------------------------	
	function uf_procesar_cmp_md($as_codempdes,$as_comprobante,$as_fecha,$as_procede)
	{
	 $lb_valido = false;
	 $ls_descripcion    ="";
	 $li_tipo_comp      ="";
	 $ls_tipo_destino   ="";
	 $ls_cod_pro        ="";
	 $ls_ced_bene       ="";
	 $li_total          ="";
	 $li_numpolcon      ="";
	 $li_estapro        ="";
	 $ld_fechaconta     ="";
	 $ld_fechaanula     ="";
	 $li_totalaux       ="";
	 $ls_codfuefin      ="";
	 $ls_coduac         ="";
       
	 $lb_valido = $this->uf_cargar_comprobante($this->ls_codemp,$as_procede,$as_comprobante,$as_fecha,$ls_descripcion,$li_tipo_comp,
											   $ls_tipo_destino,$ls_cod_pro,$ls_ced_bene,$li_total,$li_numpolcon,$li_estpro,$ld_fechaconta,
											   $ld_fechaanula,$li_totalaux,$ls_codfuefin,$ls_coduac);
								   
	 if ($lb_valido)
	    {
	      $lb_valido = $this->uf_sigesp_comprobante($as_codempdes,$as_procede,$as_comprobante,$as_fecha,$li_tipo_comp,
		                                            $ls_descripcion,$ls_tipo_destino,$ls_cod_pro,$ls_ced_bene,$li_total,
													$li_estapro,$ls_codfuefin,$ls_coduac,$li_total);
	      if ($lb_valido)
	         {
		       $lb_valido = $this->uf_procesar_dt_comprobante($as_codempdes,$as_procede,$as_comprobante,$as_fecha,$ls_cod_pro,
			                                                  $ls_ced_bene,$li_tipo_comp);
		       if (!$lb_valido)
		          {
		 		    $this->io_msg->message("Hubo un Error con el detalle de la Solicitud Nro. ".$as_comprobante);
		          }  
	         }
	      else
	         { 
	   		   $this->io_msg->message("Hubo un Error en el Registro de la Solicitud Nro. ".$as_comprobante);
	         }
	   
	 }	  
	  return $lb_valido;
	}
	
	function uf_procesar_cmp($as_codempdes,$as_comprobante,$as_fechacont,$as_procede,$as_codban,$as_ctaban)
	{
	 $lb_valido = true;
	 $ls_descripcion    ="";
	 $li_tipo_comp      ="";
	 $ls_tipo_destino   ="";
	 $ls_cod_pro        ="";
	 $ls_ced_bene       ="";
	 $li_total          ="";
	 $li_numpolcon      ="";
	 $li_totalaux       ="";
	 $ls_codban         = '---';
     $ls_ctaban         = '-------------------------';
	 
	 $lb_valido = $this->uf_cargar_cmp($this->ls_codemp,$as_procede,$as_comprobante,$as_fechacont,$as_codban,$as_ctaban,
	                                   $ls_descripcion,$li_tipo_comp,$ls_tipo_destino,$ls_cod_pro,$ls_ced_ben,$li_total,$li_numpolcon,$li_totalaux);
								   
	 if ($lb_valido)
	    {
	      $lb_valido = $this->sig_int->uf_sigesp_comprobante($as_codempdes,$as_procede,$as_comprobante,$as_fechacont,$li_tipo_comp,$ls_descripcion,$ls_tipo_destino,$ls_cod_pro,$ls_ced_bene,$li_total,$ls_codban,$ls_ctaban);
	      if ($lb_valido)
	         {
	           $lb_valido = $this->uf_procesar_dt_cmp($as_codempdes,$as_procede,$as_comprobante,$as_fechacont,$ls_cod_pro,$ls_ced_bene,$li_tipo_comp,$ls_codban,$ls_ctaban);
	           if (!$lb_valido)
				  {
				    $this->io_msg->message("Hubo un Error con el detalle del Comprobante Nro. ".$as_comprobante);
				  } 
	         }
		  else
		     { 
		       $this->io_msg->message("Hubo un Error en el Registro de la Comprobante Nro. ".$as_comprobante);
		     }
	    }	
	 return $lb_valido;
	}
	
	function uf_procesar_cmp_int($as_codempdes,$as_comprobante,$as_fechacont,$as_procede,$as_origen,$as_codban,$as_ctaban)
	{
	 $lb_valido = true;
	 $ls_descripcion    ="";
	 $li_tipo_comp      ="";
	 $ls_tipo_destino   ="";
	 $ls_cod_pro        ="";
	 $ls_ced_bene       ="";
	 $li_total          ="";
	 $li_numpolcon      ="";
	 $li_totalaux       ="";
	 $ls_codban         = '---';
     $ls_ctaban         = '-------------------------';
	 
	 $lb_valido = $this->uf_cargar_cmp($this->ls_codemp,$as_procede,$as_comprobante,$as_fechacont,$as_codban,$as_ctaban,
	                                   $ls_descripcion,$li_tipo_comp,$ls_tipo_destino,$ls_cod_pro,$ls_ced_bene,$li_total,$li_numpolcon,$li_totalaux);
								   
	 if ($lb_valido)
	 {
	   $ls_comprobanteint = substr($_SESSION["la_empresa"]["codaltemp"],2,2).substr($as_comprobante,2,13);
	   $lb_valido = $this->sig_int->uf_sigesp_comprobante($as_codempdes,$as_procede,$ls_comprobanteint,$as_fechacont,$li_tipo_comp,$ls_descripcion,$ls_tipo_destino,$ls_cod_pro,$ls_ced_bene,$li_total,$ls_codban,$ls_ctaban);
	   
	   if($lb_valido)
	   {
	    $lb_valido = $this->uf_procesar_dt_cmp_int($as_codempdes,$as_procede,$as_comprobante,$as_fechacont,$ls_cod_pro,$ls_ced_bene,$li_tipo_comp,$ls_codban,$ls_ctaban,$as_origen);
	    if(!$lb_valido)
		{
		 $this->io_msg->message("Hubo un Error con el detalle del Comprobante Nro. ".$as_comprobante);
		} 
	  }
	  else
	  { 
	   $this->io_msg->message("Hubo un Error en el Registro de la Comprobante Nro. ".$as_comprobante);
	  }
	 }	
	 return $lb_valido;
	}
	
	
	function uf_sigesp_insert_comprobante($as_codemp,$as_procede,$as_comprobante,$as_fecha,$ai_tipo_comp,$as_descripcion,
	                                      $as_tipo,$as_cod_prov,$as_ced_ben,$as_codfuefin,$as_coduniadm,$ai_total)
	{
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
    //	Function:  uf_sigesp_insert_comprobante
    //	  Access:  public
	// Arguments:  as_codemp->codigo empresa; as_procede-> procedencia; as_comprobante-> comprobante;
	//             as_fecha-< fecha ai_tipo_comp-< tipo comprobante (1,2); as_descripcion->descripcion;
	//             as_tipo->tipo fuente as_ced_ben-< beneficiario;as_cod_prov-> proveedor
	//	Returns:	 lb_valido -> variable boolean
	//	Description: Método que inserta el registro comprobante (información cabezera )en la tabla SIGESP_Cmp. Usado en el mòdulo de comprobante contable
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_fec=$this->io_function->uf_convertirdatetobd($as_fecha);
		$ls_sql = " INSERT INTO sigesp_cmp_md (codemp,procede,comprobante,fecha,descripcion,tipo_comp,tipo_destino,".
		          "                            cod_pro,ced_bene,total,estapro,codfuefin,coduac)".
				  " VALUES('".$as_codemp."', '".$as_procede."', '".$as_comprobante."','".$ls_fec."', ".
				  "        '".$as_descripcion."',".$ai_tipo_comp.",'".$as_tipo."','".$as_cod_prov."', ".
				  "        '".$as_ced_ben."', ".$ai_total. ",1,'".$as_codfuefin."','".$as_coduniadm."')";		  
		$li_result=$this->io_sql_destino->execute($ls_sql);
		if($li_result===false)
		{
			$lb_valido=false;
			$this->is_msg_error = "Error en método uf_sigesp_insert_comprobante ";
		}
		return $lb_valido;
	} // end function uf_sigesp_insertcomporbante()
/**********************************************************************************************************************************/
	
	function uf_select_comprobante($as_codemp,$as_procedencia,$as_comprobante,$as_fecha)
	{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	 Function:  uf_select_comprobante()
	//	   Access:  public
	//	Arguments:  $as_codemp-> empresa,$as_procedencia->procedencia,$as_comprobante->comprobante,$as_fecha
	//	  Returns:	booleano lb_existe
	//Description:  Método que verifica si existe o no el comprobante en la base de dtso destino
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_existe=false;
	   $ldt_newfec=$this->io_function->uf_convertirdatetobd($as_fecha);
	   $ls_sql =   " SELECT comprobante ".
	               " FROM sigesp_cmp_md ".
				   " WHERE codemp='".$as_codemp."' AND procede='".$as_procedencia."' AND comprobante='".$as_comprobante."' ".
				   " AND fecha = '".$ldt_newfec."'";	  			        			   		   
	   $lr_result = $this->io_sql_destino->select($ls_sql);
	   if($lr_result===false)
	   {
		  $this->is_msg_error="Error en Select Comprobante".$this->io_function->uf_convertirmsg($this->io_sql_destino->message);
		  return false;
	   }
	   else  
	   { 
	    $li_numrows=$this->io_sql_destino->num_rows($lr_result);
		if($li_numrows > 0)
		{
			 $lb_existe=true;
		}  
	  }
	  return $lb_existe;
	} // end function uf_select_comprobante
/**********************************************************************************************************************************/
    
	function uf_select_comp_int($as_codemp,$as_procedencia,$as_comprobante,$as_fecha, $as_codban, $as_ctaban)
	{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	 Function:  uf_select_comp_int()
	//	   Access:  public
	//	Arguments:  $as_codemp-> empresa,$as_procedencia->procedencia,$as_comprobante->comprobante,$as_fecha
	//	  Returns:	booleano lb_existe
	//Description:  Método que verifica si existe o no el comprobante en la base de datos destino
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_existe=false;
	   $ldt_newfec=$this->io_function->uf_convertirdatetobd($as_fecha);
	   $ls_sql =   " SELECT comprobante ".
	               " FROM sigesp_cmp ".
				   " WHERE codemp='".$as_codemp."' AND procede='".$as_procedencia."' AND comprobante='".$as_comprobante."' ".
				   " AND fecha = '".$ldt_newfec."' AND codban = '".$as_codban." AND ctaban = '".$as_ctaban."'";	  			        			   		   
	   $lr_result = $this->io_sql_destino->select($ls_sql);
	   if($lr_result===false)
	   {
		  $this->is_msg_error="Error en Select Comprobante".$this->io_function->uf_convertirmsg($this->io_sql_destino->message);
		  return false;
	   }
	   else  
	   { 
	    $li_numrows=$this->io_sql_destino->num_rows($lr_result);
		if($li_numrows > 0)
		{
			 $lb_existe=true;
		}  
	  }
	  return $lb_existe;
	} // end function uf_select_comprobante
	

	function uf_cargar_comprobante($as_codemp,$as_procedencia,$as_comprobante,$as_fecha,&$as_descripcion,&$ai_tipo_comp,
	                               &$as_tipo_destino,&$as_cod_pro,&$as_ced_ben,&$ai_total,&$ai_numpolcon,&$ai_estapro,&$ad_fechaconta,
								   &$ad_fechaanula,&$ai_totalaux,&$as_codfuefin,&$as_coduac)
	{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	 Function:  uf_cargar_comprobante()
	//	   Access:  public
	//	Arguments:  $as_codemp-> empresa,$as_procedencia->procedencia,$as_comprobante->comprobante,$as_fecha
	//	  Returns:	booleano lb_existe
	//Description:  Método que devuelve los datos asociados a un comprobante
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=false;
	   $ls_newfec=$this->io_function->uf_convertirdatetobd($as_fecha);
	   $ls_sql =   " SELECT descripcion, tipo_comp, tipo_destino, cod_pro, ".
       			   " ced_bene, total, numpolcon, estapro, fechaconta,".
       			   " fechaanula, totalaux, codfuefin, coduac ".
	               " FROM sigesp_cmp_md ".
				   " WHERE codemp='".$as_codemp."' AND procede='".$as_procedencia."' AND comprobante='".$as_comprobante."' ";		   
	   $lr_result = $this->io_sql->select($ls_sql);
	   if($lr_result===false)
	   {
		  $this->is_msg_error="Error en Select Comprobante".$this->io_function->uf_convertirmsg($this->io_sql->message);
	   }
	   else  
	   { 
	      if($row=$this->io_sql->fetch_row($lr_result)) 
		  { 
		     $as_descripcion    =rtrim($row["descripcion"]);
			 $ai_tipo_comp       =$row["tipo_comp"];
			 $as_tipo_destino   =$row["tipo_destino"];
			 $as_cod_pro        =$row["cod_pro"];
			 $as_ced_ben        =$row["ced_bene"];
			 $ai_total          =$row["total"];
			 $ai_numpolcon      =$row["numpolcon"];
			 $ai_estapro        =$row["estapro"];
			 $ad_fechaconta     =$row["fechaconta"];
			 $ad_fechaanula     =$row["fechaanula"];
			 $ai_totalaux       =$row["totalaux"];
			 $as_codfuefin      =$row["codfuefin"];
			 $as_coduac         =$row["coduac"];
			 $lb_valido         =true;
		  }  
	  }
	  return $lb_valido;
	} // end function uf_cargar_comprobante
/**********************************************************************************************************************************/

function uf_cargar_cmp($as_codemp,$as_procedencia,$as_comprobante,$as_fechacont,$as_codban,$as_ctaban,&$as_descripcion,
                       &$ai_tipo_comp,&$as_tipo_destino,&$as_cod_pro,&$as_ced_ben,&$ai_total,&$ai_numpolcon,&$ai_totalaux)
	{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	 Function:  uf_cargar_cmp()
	//	   Access:  public
	//	Arguments:  $as_codemp-> empresa,$as_procedencia->procedencia,$as_comprobante->comprobante,$as_fecha
	//	  Returns:	booleano lb_existe
	//Description:  Método que devuelve los datos asociados a un comprobante de gasto
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=false;
	   $ls_newfec=$this->io_function->uf_convertirdatetobd($as_fechacont);
	   $ls_sql = "SELECT descripcion, tipo_comp, tipo_destino, cod_pro, ced_bene, total, numpolcon, totalaux
	                FROM sigesp_cmp
				   WHERE codemp = '".$as_codemp."'
				     AND procede = '".$as_procedencia."'
					 AND comprobante = '".$as_comprobante."'
				     AND fecha = '".$ls_newfec."'
					 AND codban = '".$as_codban."'
					 AND ctaban = '".$as_ctaban."'";
	   $lr_result = $this->io_sql->select($ls_sql);
	   if ($lr_result===false)
	      {
		    $this->is_msg_error="Error en Select Comprobante".$this->io_function->uf_convertirmsg($this->io_sql->message);
	      }
	   else  
	      { 
	        if ($row=$this->io_sql->fetch_row($lr_result)) 
		       {  
				 $as_descripcion    =rtrim($row["descripcion"]);
				 $ai_tipo_comp      =$row["tipo_comp"];
				 $as_tipo_destino   =$row["tipo_destino"];
				 $as_cod_pro        =$row["cod_pro"];
				 $as_ced_ben        =$row["ced_bene"];
				 $ai_total          =$row["total"];
				 $ai_numpolcon      =$row["numpolcon"];
				 $ai_totalaux       =$row["totalaux"];
				 $lb_valido         =true;
		       }  
	      }
	  return $lb_valido;
	} // end function uf_cargar_comprobante
/**********************************************************************************************************************************/

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_sigesp_comprobante($as_codemp,$as_procedencia,$as_comprobante,$as_fecha,$ai_tipo_comp,$as_descripcion,
	                               $as_tipo,$as_cod_pro,$as_ced_bene,$adec_monto,$li_estapro,$as_codfuefin,$as_coduniadm,$ai_total)
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 	 Function:   uf_sigesp_comprobante
	// 	   Access:  public
	//  Arguments:  $as_codemp->empresa,$as_procede->procede,$as_comprobante->comprobante,$as_fecha->fecha comprobante,
    //	            $as_cuenta->cuenta contable ,$as_procede_doc->procede documento,$as_documento->nº documento
	//              $as_operacion->operacion debe haber,$adec_monto->mnto movimiento
	//	  Returns:  Boolean
	//Description:  Procesa un comprobante 
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	{
		$this->is_codemp=$as_codemp;
		$this->is_procedencia=$as_procedencia;
		$this->is_comprobante=$as_comprobante;
		$this->id_fecha=$as_fecha;
   	    $this->ii_tipo_comp=$ai_tipo_comp;
		$this->is_descripcion=$as_descripcion;
		$this->is_tipo=$as_tipo;

		if ($as_tipo=="B")
		{
		   $this->is_ced_ben  = $as_ced_bene;
   	       $this->is_cod_prov = "----------"; 
		}
		if ($as_tipo=="P")
		{
		   $this->is_ced_ben  = "----------";
		   $this->is_cod_prov = $as_cod_pro;
		}
		if ($as_tipo=="-")
		{
		   $this->is_ced_ben  = "----------";
		   $this->is_cod_prov = "----------";
		}
	    $this->ib_new_comprobante=true;		
		$lb_valido = $this->uf_sigesp_insert_comprobante($as_codemp,$as_procedencia,$as_comprobante,$as_fecha,$ai_tipo_comp,$as_descripcion,$as_tipo,$this->is_cod_prov,$this->is_ced_ben,$as_codfuefin,$as_coduniadm,$ai_total);
		
		return $lb_valido;
	} // end function uf_procesar_comprobante_en_linea()
/**********************************************************************************************************************************/
function uf_guardar_automatico($as_comprobante,$ad_fecha,$as_proccomp,$as_desccomp,$as_prov,$as_bene,$as_tipo,$ai_tipo_comp,$li_estapro,$as_codfuefin,$as_coduniadm)
{
	$lb_valido=false;
	$dat=$_SESSION["la_empresa"];
	$_SESSION["fechacomprobante"]=$ad_fecha;
	if($this->uf_valida_datos_cmp($as_comprobante,$ad_fecha,$as_proccomp,$as_desccomp,&$as_prov,&$as_bene,$as_tipo))
	{	
	   $lb_valido=$this->uf_sigesp_comprobante($dat["codemp"],$as_proccomp,$as_comprobante,$ad_fecha,$ai_tipo_comp,$as_desccomp,$as_tipo,$as_prov,$as_bene,0,$li_estapro,$as_codfuefin,$as_coduniadm);
	   if (!$lb_valido)
	   {
	      $this->io_msg->message("Error al procesar el comprobante Presupuestario".$this->is_msg_error);
	   }  
	   else  
	   {   
	       $this->io_msg->message("El Movimiento fue registrado.");
	   }
	   
	   $ib_valido = $lb_valido;
	   
	   if($lb_valido)
	   {
		  $ib_new = $this->ib_new_comprobante;
	   }	
	   else  
	   {  
	      $lb_valido=true;  
	   } 	
	}
	else
	{ 
	   $this->io_msg->message("Error en valida datos comprobante");
    }
	return $lb_valido;
}
/**********************************************************************************************************************************/
function uf_procesar_dt_comprobante($as_codemp,$as_procede,$as_comprobante,$ad_fecha,$as_cod_pro,$as_ced_bene,$as_tipo)
{
	$lb_valido = true;
    $arr_cmp["codemp"]       = $as_codemp;
	$arr_cmp["comprobante"]  = $as_comprobante;
	$arr_cmp["fecha"]        = $ad_fecha;
	$arr_cmp["procedencia"]  = $as_procede;
	$arr_cmp["proveedor"]    = $as_cod_pro;
	$arr_cmp["beneficiario"] = $as_ced_bene;
	$arr_cmp["tipo"]         = $as_tipo;
	$ls_tipocomp = "P";
	$ls_sql = "SELECT DT.codestpro1,DT.codestpro2,DT.codestpro3, DT.codestpro4,DT.codestpro5 ,DT.estcla,DT.spg_cuenta,  ".
       		"		  DT.procede_doc, DT.documento,  DT.operacion, DT.descripcion, DT.monto, DT.orden ".
            " 	FROM spg_dtmp_cmp DT, spg_cuentas C, sigesp_procedencias P, spg_operaciones OP ".
            "  WHERE DT.codemp='".$as_codemp."'
				 AND DT.procede='".$as_procede."'
				 AND DT.comprobante='".$as_comprobante."'
				 AND DT.fecha='".$ad_fecha."'			     
				 AND DT.procede=P.procede 
			     AND DT.codemp=C.codemp 
				 AND DT.spg_cuenta=C.spg_cuenta 
				 AND OP.operacion = DT.operacion 
				 AND DT.codestpro1=C.codestpro1  
				 AND DT.codestpro2=C.codestpro2
				 AND DT.codestpro3=C.codestpro3
				 AND DT.codestpro4=C.codestpro4
				 AND DT.codestpro5=C.codestpro5
				 AND DT.estcla=C.estcla
               ORDER BY DT.orden";							
	$rs_dt_cmp=$this->io_sql->select($ls_sql);
	if ($rs_dt_cmp===false)
 	   {
	     $this->io_msg->message($this->io_function->uf_convertirmsg($this->io_sql->message));
	   }
	else
	   {
	     while(($row=$this->io_sql->fetch_row($rs_dt_cmp))&&($lb_valido))
			  {
			    $ls_codestpro1  = $row["codestpro1"];
			    $ls_codestpro2  = $row["codestpro2"];
			    $ls_codestpro3  = $row["codestpro3"];
			    $ls_codestpro4  = $row["codestpro4"];
			    $ls_codestpro5  = $row["codestpro5"];
			    $ls_estcla      = $row["estcla"];
			    $ls_spg_cuenta  = $row["spg_cuenta"];
			    $ls_procede_doc = $row["procede_doc"];
			    $ls_documento   = $row["documento"];
			    $ls_operacion   = $row["operacion"];
			    $ls_descripcion = $row["descripcion"];
			    $li_monto       = $row["monto"];
			    $li_orden       = $row["orden"];
	  
	            $lb_valido      = $this->uf_guardar_movimientos($arr_cmp,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
				                                                $ls_codestpro4,$ls_codestpro5,$ls_spg_cuenta,$ls_procede_doc,
                                                                $ls_descripcion,$ls_documento,$ls_operacion,0,$li_monto,
																$ls_tipocomp,$ls_estcla,$li_orden);
	          }
	   }
	return $lb_valido;
}
/**********************************************************************************************************************************/

function uf_procesar_dt_cmp($as_codemp,$as_procede,$as_comprobante,$ad_fecha,$as_cod_pro,$as_ced_bene,$as_tipo,$as_codban,$as_ctaban)
{
	$lb_valido = true;
    $arr_cmp["codemp"]       = $as_codemp;
	$arr_cmp["comprobante"]  = $as_comprobante;
	$arr_cmp["fecha"]        = $ad_fecha;
	$arr_cmp["procedencia"]  = $as_procede;
	$arr_cmp["proveedor"]    = $as_cod_pro;
	$arr_cmp["beneficiario"] = $as_ced_bene;
	$arr_cmp["tipo"]         = $as_tipo;
	$ls_tipocomp = "P";
	$ls_sql = "SELECT DT.codestpro1,DT.codestpro2,DT.codestpro3, DT.codestpro4,DT.codestpro5 ,DT.estcla,DT.spg_cuenta,
       				  DT.procede_doc, DT.documento,  DT.operacion, DT.descripcion, DT.monto, DT.orden
             	 FROM spg_dt_cmp DT, spg_cuentas C, sigesp_procedencias P, spg_operaciones OP
             	WHERE DT.codemp='".$as_codemp."'
				  AND DT.procede='".$as_procede."'
				  AND DT.comprobante='".$as_comprobante."'
				  AND DT.fecha='".$ad_fecha."' 
				  AND DT.procede=P.procede 
				  AND DT.codemp=C.codemp
				  AND DT.spg_cuenta=C.spg_cuenta
				  AND OP.operacion = DT.operacion 
				  AND DT.codestpro1=C.codestpro1  
				  AND DT.codestpro2=C.codestpro2
				  AND DT.codestpro3=C.codestpro3
				  AND DT.codestpro4=C.codestpro4
				  AND DT.codestpro5=C.codestpro5
				  AND DT.estcla=C.estcla 
			   ORDER BY DT.orden";						
	$rs_dt_cmp=$this->io_sql->select($ls_sql);
	if ($rs_dt_cmp===false)
	   {
	     $this->io_msg->message($this->io_function->uf_convertirmsg($this->io_sql->message));
	   }
	else
	   {
	     while(($row=$this->io_sql->fetch_row($rs_dt_cmp))&&($lb_valido))
	          {
			    $ls_codestpro1  = $row["codestpro1"];
			    $ls_codestpro2  = $row["codestpro2"];
			    $ls_codestpro3  = $row["codestpro3"];
			    $ls_codestpro4  = $row["codestpro4"];
			    $ls_codestpro5  = $row["codestpro5"];
			    $ls_estcla      = $row["estcla"];
			    $ls_spg_cuenta  = trim($row["spg_cuenta"]);
			    $ls_procede_doc = $row["procede_doc"];
			    $ls_documento   = $row["documento"];
			    $ls_operacion   = $row["operacion"];
			    $ls_descripcion = $row["descripcion"];
			    $li_monto       = $row["monto"];
			    $li_orden       = $row["orden"];
	            $lb_valido = $this->uf_guardar_movcmp($arr_cmp,$ls_codestpro1,$ls_codestpro2 ,$ls_codestpro3,$ls_codestpro4,
													  $ls_codestpro5,$ls_spg_cuenta,$ls_procede_doc,$ls_descripcion,$ls_documento,
													  $ls_operacion,0,$li_monto,$ls_tipocomp,$as_codban,$as_ctaban,$ls_estcla);
	 }
	
	}
	return $lb_valido;
}
/**********************************************************************************************************************************/


function uf_procesar_dt_cmp_int($as_codemp,$as_procede,$as_comprobante,$ad_fecha,$as_cod_pro,$as_ced_bene,$as_tipo,$as_codban,$as_ctaban,$as_origen)
{
	$lb_valido = true;
	$ls_codaltemp=$_SESSION["la_empresa"]["codaltemp"];
	$ls_comprobanteint = substr($_SESSION["la_empresa"]["codaltemp"],2,2).substr($as_comprobante,2,13);
    $arr_cmp["codemp"]       = $as_codemp;
	$arr_cmp["comprobante"]  = $ls_comprobanteint;
	$arr_cmp["fecha"]        = $ad_fecha;
	$arr_cmp["procedencia"]  = $as_procede;
	$arr_cmp["proveedor"]    = $as_cod_pro;
	$arr_cmp["beneficiario"] = $as_ced_bene;
	$arr_cmp["tipo"]         = $as_tipo;
	$ls_tipocomp = "C";
	$ls_sql=" SELECT DT.codestpro1,DT.codestpro2,DT.codestpro3, DT.codestpro4,DT.codestpro5 ,DT.estcla,DT.spg_cuenta,  ".
       		"		 DT.procede_doc, DT.documento,  DT.operacion, DT.descripcion, DT.monto, DT.orden                   ".
            " 	FROM  spg_dt_cmp DT, spg_cuentas C, sigesp_procedencias P, spg_operaciones OP ".
            " 	WHERE DT.procede=P.procede AND DT.codemp=C.codemp AND DT.spg_cuenta=C.spg_cuenta AND  ".
			"       OP.operacion = DT.operacion AND (DT.codestpro1=C.codestpro1  AND DT.codestpro2=C.codestpro2 AND  ".
			"       DT.codestpro3=C.codestpro3  AND DT.codestpro4=C.codestpro4   AND DT.codestpro5=C.codestpro5 AND  ".
			"       DT.estcla=C.estcla) AND DT.codemp='".$as_codemp."'  AND  DT.procede='".$as_procede."' AND ".
			"       DT.comprobante='".$as_comprobante."'  AND  DT.fecha='".$ad_fecha."' ".
			"   ORDER BY DT.orden";								
	$rs_dt_cmp=$this->io_sql->select($ls_sql);
	if($rs_dt_cmp===false)
	{
		$this->io_msg->message($this->io_function->uf_convertirmsg($this->io_sql->message));
	}
	else
	{
	 while(($row=$this->io_sql->fetch_row($rs_dt_cmp))&&($lb_valido))
	 {
	  $ls_codestpro1  = $row["codestpro1"];
	  $ls_codestpro2  = $row["codestpro2"];
	  $ls_codestpro3  = $row["codestpro3"];
	  $ls_codestpro4  = $row["codestpro4"];
	  $ls_codestpro5  = $row["codestpro5"];
	  $ls_estcla      = $row["estcla"];
	  $ls_spg_cuenta  = $row["spg_cuenta"];
	  $ls_procede_doc = $row["procede_doc"];
	  $ls_documento   = $row["documento"];
	  $ls_operacion   = $row["operacion"];
	  $ls_descripcion = $row["descripcion"];
	  $li_monto       = $row["monto"];
	  $li_orden       = $row["orden"];
      $lb_valido = $this->uf_cuenta_existe($as_codemp,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_spg_cuenta);
	  if ($lb_valido)
	  {	  
	   switch (strtoupper($as_origen))
	   {
	    case 'CS':$this->ib_contabilizar = true;
		          $this->ib_spg_enlace_contable = true; 
		          $lb_valido=$this->uf_guardar_movcmp_int($arr_cmp,$ls_codestpro1,$ls_codestpro2 ,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_spg_cuenta,$ls_procede_doc,$ls_descripcion,
		 							 $ls_documento,$ls_operacion,0,$li_monto,$ls_tipocomp,$as_codban,$as_ctaban,
                                     $ls_estcla);
		break;
		
		case 'UE': $this->ib_contabilizar = true;
		           $this->ib_spg_enlace_contable = true;
		           $lb_valido=$this->uf_guardar_movcmp_int_ue($arr_cmp,$ls_codestpro1,$ls_codestpro2 ,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_spg_cuenta,$ls_procede_doc,$ls_descripcion,
		 							 $ls_documento,$ls_operacion,0,$li_monto,$ls_tipocomp,$as_codban,$as_ctaban,
                                     $ls_estcla);
		break;							 						 
	   } 
	  }
	  else
	  {
	   $this->io_msg->message("La Cuenta de Gasto: <".$ls_spg_cuenta."> no existe en la Estructura de Gasto ".substr($ls_codestpro1,-$this->ls_loncodestpro1)." - ".substr($ls_codestpro2,-$this->ls_loncodestpro2)." - ".substr($ls_codestpro3,-$this->ls_loncodestpro3)." - ".substr($ls_codestpro4,-$this->ls_loncodestpro4)." - ".substr($ls_codestpro5,-$this->ls_loncodestpro5)." \n en la Base de Datos Destino ");
	  }								 
	 }// end While
	
	}
	return $lb_valido;
}
/**********************************************************************************************************************************/

function uf_cargar_dt_contable_cmp($as_codemp,$as_procede,$as_comprobante,$adt_fecha)
{
	$ld_fecha=$this->io_function->uf_convertirdatetobd($adt_fecha);
	$rs_dt_scg=$this->uf_scg_cargar_detalle_comprobante( $as_codemp, $as_procede,$as_comprobante, $ld_fecha,&$lds_detalle_cmp);
	if($rs_dt_scg===false)
	{
		$this->io_msg->message($this->io_function->uf_convertirmsg($this->io_int_scg->io_sql->message));
	}
	return $rs_dt_scg;
}
/**********************************************************************************************************************************/
 function uf_scg_cargar_detalle_comprobante($as_codemp,$as_procede,$as_comprobante,$as_fecha,$lds_detalle_cmp)
 {	 
	////////////////////////////////////////////////////////////////////////////////////////////////////
	// 	 Function: uf_scg_cargar_detalle_comprobante
	// 	   Access:  public
	//	  Returns:  estructura de datos
	//Description:  inserta la información del saldo de la cuenta correspondiente.
	////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql =  " SELECT DISTINCT DT.sc_cuenta as sc_cuenta,C.denominacion as denominacion,DT.procede_doc as procede_doc,P.desproc as despro,".
               		 "                 DT.documento as documento,DT.fecha as fecha,DT.debhab as debhab,DT.descripcion as descripcion,DT.monto as monto,DT.orden as orden " .
					 " FROM scg_dtmp_cmp DT,scg_cuentas C, sigesp_procedencias P ".
					 " WHERE DT.codemp='".$as_codemp."' AND DT.procede='".$as_procede."' AND DT.comprobante='".$as_comprobante."' AND ".
					 "       DT.fecha= '".$as_fecha."' AND DT.sc_cuenta=C.sc_cuenta AND DT.procede=P.procede ".
					 " ORDER BY DT.orden ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data==false)
		{
			$this->is_msg_error="Error en cargar detalle comprobante".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
		}
		return $rs_data;
	 }  // end function uf_scg_cargar_detalle_comprobante()
/**********************************************************************************************************************************/
function uf_valida_datos_cmp($as_comprobante,$ad_fecha,$as_procedencia,$as_desccomp,$as_cod_prov,$as_ced_bene,$as_tipo)
{
	$ls_desproc ="";
	if(!$this->io_int_spg->uf_valida_procedencia($as_procedencia,&$ls_desproc ) )
	{
	   $this->io_msg->message("Procedencia invalida.",$ls_desproc);
	   return false	;
	} 

	if(trim($as_comprobante)=="")
	{
		$this->io_msg->message("Debe registrar el comprobante contable.");
		return false;
	}

	if(trim($as_comprobante)=="000000000000000")
	{
		$this->io_msg->message("Debe registrar el comprobante contable.");
		return false;
	}
	
	
	if((trim($as_cod_prov)=="----------")&&($as_tipo=="P"))
	{
		$this->io_msg->message("Debe registrar el codigo del proveedor.");
		return false;
	}
	if((trim($as_cod_prov)=="")&&($as_tipo=="P"))
	{
		$this->io_msg->message("Debe registrar el codigo del proveedor.");
		return false;
	}
	
	if((trim($as_cod_prov)!="----------" )&&($as_tipo=="B"))
	{
		$as_cod_prov = "----------";
	}
		
	if((trim($as_ced_bene)=="----------")&&($as_tipo=="B"))
	{
		$this->io_msg->message("Debe registrar la cédula del beneficiario1.");
		return false;
	}
	if((trim($as_ced_bene)=="")&&($as_tipo=="B"))
	{
		$this->io_msg->message("Debe registrar la cédula del beneficiario.2");
		return false;	
	}
	
	if((trim($as_ced_bene)!="----------" )&&($as_tipo=="P"))
	{
		$as_ced_bene="----------";
	}
	if($as_tipo=="-")
	{
		$as_ced_bene="----------";
		$as_cod_prov="----------";
	}

  return true;
}
/**********************************************************************************************************************************/
function uf_guardar_movimientos($arr_cmp,$ls_est1,$ls_est2,$ls_est3,$ls_est4,$ls_est5,$ls_cuenta,$ls_procede_doc,$ls_descripcion,
								$ls_documento,$ls_operacionpre,$ldec_monto_ant,$ldec_monto_act,$ls_tipocomp,$ls_estcla,$li_orden)
{
	$lb_valido = false;
	$estpro[0] = $ls_est1;
	$estpro[1] = $ls_est2;
	$estpro[2] = $ls_est3;
	$estpro[3] = $ls_est4;
	$estpro[4] = $ls_est5;
	$estpro[5] = $ls_estcla;		
	
	$ls_mensaje = $this->io_int_spg->uf_operacion_codigo_mensaje($ls_operacionpre) ;
	
	if ($ls_mensaje!="")
	   {
	     if (!$this->uf_spg_valida_datos_movimiento($ls_cuenta,$ls_descripcion,$ls_documento,&$ldec_monto))
		    {  
		      $this->io_msg->message($this->is_msg_error);
		      return false;
		    }
		 $this->io_int_spg->is_codemp	   = $arr_cmp["codemp"];
		 $this->io_int_spg->is_comprobante = $arr_cmp["comprobante"];
		 $this->io_int_spg->id_fecha	   = $arr_cmp["fecha"];
		 $this->io_int_spg->is_procedencia = $arr_cmp["procedencia"];
		 $this->io_int_spg->is_cod_prov	   = $arr_cmp["proveedor"];
		 $this->io_int_spg->is_ced_bene	   = $arr_cmp["beneficiario"];
		 $this->io_int_spg->is_tipo		   = $arr_cmp["tipo"];
		 $lb_valido = $this->uf_spg_comprobante_actualizar($ldec_monto_ant, $ldec_monto_act, $ls_tipocomp);
	 	 if ($lb_valido)
		    {
			  $ls_sc_cuenta="";	
			  if ($arr_cmp["tipo"]=="B")  
				 { $ls_fuente = $arr_cmp["beneficiario"]; }	
			  else
			     { 
				   if ($arr_cmp["tipo"]=="P")
				      {  
				 	    $ls_fuente = $arr_cmp["proveedor"]; 
				      }	
				   else 
				      {  
					    $ls_fuente = "----------"; 
				      } 
			     }
			  if (!$this->io_int_spg->uf_spg_select_cuenta($arr_cmp["codemp"],$estpro,$ls_cuenta,&$ls_status,&$ls_denominacion,&$ls_sc_cuenta))
			     {
			       return false;
			     }
			$ls_comprobante = $this->io_int_spg->uf_fill_comprobante($this->is_comprobante);
		    $ls_operacion   = $this->io_int_spg->uf_operacion_mensaje_codigo($ls_mensaje);
		    if(empty($ls_operacion)) { return false; }
		    if(!$this->io_int_spg->uf_valida_procedencia( $this->io_int_spg->is_procedencia , $ls_denproc)) { return false; }
		    if(!$this->io_int_spg->io_fecha->uf_valida_fecha_mes($this->io_int_spg->is_codemp,$this->io_int_spg->id_fecha))
		    {
		 	   $this->is_msg_error = "Fecha Invalida."	;
	 		   $this->io_msg->message($this->is_msg_error);			   		  		  
 			   return false;
		    }
		    if ($this->uf_spg_select_movimiento($estpro, $ls_cuenta, $ls_procede_doc, $ls_documento, $ls_operacion, $lo_monto_movimiento, $lo_orden))  
		       {
		 	     $this->is_msg_error = "El Movimiento Presupuestario ya existe !!!";
	 		     $this->io_msg->message($this->is_msg_error);			   		  		  		  
 			     return false; 	 
		       }
			
			$lb_valido = $this->uf_spg_comprobante_actualizar(0,$ldec_monto_ant,"C");
			if ($lb_valido)
			   {
			     $lb_valido = $this->uf_insert_movimiento_spg($estpro,$ls_cuenta,$ls_procede_doc,$ls_documento,$ls_operacion,$ls_descripcion,$ldec_monto_act,$li_orden);
				 if ($lb_valido)
				    {
					  $ls_mensaje = strtoupper($ls_mensaje); // devuelve cadena en MAYUSCULAS
					  $li_pos_i   = strpos($ls_mensaje,"C"); 
					  if (!($li_pos_i===false))
					     {			      
					       if ($this->ib_AutoConta)
					          { 
						        $lb_valido = $this->uf_spg_integracion_scg($ls_codemp,$ls_cuenta,$ls_procede_doc,$ls_documento,
								                                           $ls_descripcion,$ldec_monto);
					          }
					     } 
					  if (!$lb_valido)
						 {
						   $this->io_msg->message("No se registraron los detalles presupuestario".$this->io_int_spg->is_msg_error);
						 }
				    }
			   }
		}
		 else
		    {
		  	  $lb_valido=false;
	 	    }
       }
    $ldec_monto = 0;
  return $lb_valido;
}


function uf_guardar_movcmp($arr_cmp,$ls_est1,$ls_est2,$ls_est3,$ls_est4,$ls_est5,$ls_cuenta,$ls_procede_doc,$ls_descripcion,
									$ls_documento,$ls_operacionpre,$ldec_monto_ant,$ldec_monto_act,$ls_tipocomp,$as_codban,$as_ctaban,
                                    $as_estcla)
	{
		$lb_valido=false;
		$estpro[0]=$ls_est1;
		$estpro[1]=$ls_est2;
		$estpro[2]=$ls_est3;
		$estpro[3]=$ls_est4;
		$estpro[4]=$ls_est5;
		$estpro[5]=$as_estcla;
		$ls_mensaje = $this->io_int_spg->uf_operacion_codigo_mensaje($ls_operacionpre);
		if($ls_mensaje!="")
		{
			if(!$this->uf_spg_valida_datos_movimiento($ls_cuenta,$ls_descripcion,$ls_documento,&$ldec_monto))
			{ 
			   $this->io_msg->message($this->is_msg_error);
			   return false;
			}
			$this->io_int_spg->is_codemp=$arr_cmp["codemp"];
			$this->io_int_spg->is_comprobante=$arr_cmp["comprobante"];
			$this->io_int_spg->id_fecha=$arr_cmp["fecha"];
			$this->io_int_spg->is_procedencia=$arr_cmp["procedencia"];
			$this->io_int_spg->is_cod_prov=$arr_cmp["proveedor"];
			$this->io_int_spg->is_ced_bene=$arr_cmp["beneficiario"];
			$this->io_int_spg->is_tipo=$arr_cmp["tipo"];
			$this->io_int_spg->ib_AutoConta=true;
			if ($arr_cmp["tipo"]=="B")  
			{ 
			   $ls_fuente = $arr_cmp["beneficiario"]; 
			}	
			else
			{ 
				if ($arr_cmp["tipo"]=="P")
				 {  
					$ls_fuente = $arr_cmp["proveedor"]; 
				 }	
				 else 
				 {  
					$ls_fuente = "----------"; 
				 } 
			}
			$ls_status="";$ls_denominacion="";$ls_sc_cuenta="";
			if(!$this->io_int_spg->uf_spg_select_cuenta($arr_cmp["codemp"],$estpro,$ls_cuenta,&$ls_status,&$ls_denominacion,&$ls_sc_cuenta))
			{  
			  return false;
			}
			$this->io_int_spg->io_int_scg->id_fecha = $arr_cmp["fecha"];
			$lb_valido = $this->io_int_spg->uf_int_spg_insert_movimiento($arr_cmp["codemp"],$arr_cmp["procedencia"],
																		 $arr_cmp["comprobante"],$arr_cmp["fecha"],
																		 $arr_cmp["tipo"],$ls_fuente,$arr_cmp["proveedor"],
																		 $arr_cmp["beneficiario"],$estpro,$ls_cuenta,$ls_procede_doc,
																		 $ls_documento,$ls_descripcion,$ls_mensaje,$ldec_monto_act,
																		 $ls_sc_cuenta,true,$as_codban,$as_ctaban);
			if(!$lb_valido)
			{
				$this->io_msg->message("No se registraron los detalles presupuestarios".$this->io_int_spg->is_msg_error);
				
			}
	   }
	   $ldec_monto = 0;
	   return $lb_valido;
	}
 //---------------------------------------------------------------------------------------------------------------------------------

function uf_guardar_movcmp_int($arr_cmp,$ls_est1,$ls_est2,$ls_est3,$ls_est4,$ls_est5,$ls_cuenta,$ls_procede_doc,$ls_descripcion,
									$ls_documento,$ls_operacionpre,$ldec_monto_ant,$ldec_monto_act,$ls_tipocomp,$as_codban,$as_ctaban,
                                    $as_estcla)
	{
		$lb_valido=false;
		$estpro[0]=$ls_est1;
		$estpro[1]=$ls_est2;
		$estpro[2]=$ls_est3;
		$estpro[3]=$ls_est4;
		$estpro[4]=$ls_est5;
		$estpro[5]=$as_estcla;
		$ls_mensaje = $this->io_int_spg->uf_operacion_codigo_mensaje($ls_operacionpre);
		if($ls_mensaje!="")
		{
			if(!$this->uf_spg_valida_datos_movimiento($ls_cuenta,$ls_descripcion,$ls_documento,&$ldec_monto))
			{ 
			   $this->io_msg->message($this->is_msg_error);
			   return false;
			}
			$this->io_int_spg->is_codemp=$arr_cmp["codemp"];
			$this->io_int_spg->is_comprobante=$arr_cmp["comprobante"];
			$this->io_int_spg->id_fecha=$arr_cmp["fecha"];
			$this->io_int_spg->is_procedencia=$arr_cmp["procedencia"];
			$this->io_int_spg->is_cod_prov=$arr_cmp["proveedor"];
			$this->io_int_spg->is_ced_bene=$arr_cmp["beneficiario"];
			$this->io_int_spg->is_tipo=$arr_cmp["tipo"];
			$this->io_int_spg->ib_AutoConta=false;
			if ($arr_cmp["tipo"]=="B")  
			{ 
			   $ls_fuente = $arr_cmp["beneficiario"]; 
			}	
			else
			{ 
				if ($arr_cmp["tipo"]=="P")
				 {  
					$ls_fuente = $arr_cmp["proveedor"]; 
				 }	
				 else 
				 {  
					$ls_fuente = "----------"; 
				 } 
			}
			$ls_status="";$ls_denominacion="";$ls_sc_cuenta="";
			if(!$this->io_int_spg->uf_spg_select_cuenta($arr_cmp["codemp"],$estpro,$ls_cuenta,&$ls_status,&$ls_denominacion,&$ls_sc_cuenta))
			{  
			  return false;
			}
			$this->io_int_spg->io_int_scg->id_fecha = $arr_cmp["fecha"];
			$lb_valido = $this->io_int_spg->uf_int_spg_insert_movimiento($arr_cmp["codemp"],$arr_cmp["procedencia"],
																		 $arr_cmp["comprobante"],$arr_cmp["fecha"],
																		 $arr_cmp["tipo"],$ls_fuente,$arr_cmp["proveedor"],
																		 $arr_cmp["beneficiario"],$estpro,$ls_cuenta,$ls_procede_doc,
																		 $ls_documento,$ls_descripcion,$ls_mensaje,$ldec_monto_act,
																		 $ls_sc_cuenta,false,$as_codban,$as_ctaban);
			 if($lb_valido === true)
			  {
                $ls_mensaje=strtoupper($ls_operacionpre); // devuelve cadena en MAYUSCULAS
				$li_pos_i=strpos($ls_mensaje,"C"); 
				if (!($li_pos_i===false) and ($this->ib_spg_enlace_contable))
				{
					if ($this->ib_contabilizar)
					{
						$this->is_codemp      = $arr_cmp["codemp"];
						$this->is_comprobante = $arr_cmp["comprobante"]; 
						$this->id_fecha		  =$arr_cmp["fecha"];
			            $this->is_procedencia =$arr_cmp["procedencia"];
						$lb_valido=$this->uf_spg_integracion_scg_int($arr_cmp["codemp"], $ls_sc_cuenta,$ls_procede_doc,$ls_documento,$ls_descripcion,$ldec_monto_act,$as_codban,$as_ctaban);
					}
				}
			  }	 																 
			if(!$lb_valido)
			{
				$this->io_msg->message("No se registraron los detalles presupuestarios".$this->io_int_spg->is_msg_error);
				
			}
	   }
	   $ldec_monto = 0;
	   return $lb_valido;
	}
 //---------------------------------------------------------------------------------------------------------------------------------

function uf_guardar_movcmp_int_ue($arr_cmp,$ls_est1,$ls_est2,$ls_est3,$ls_est4,$ls_est5,$ls_cuenta,$ls_procede_doc,$ls_descripcion,
									$ls_documento,$ls_operacionpre,$ldec_monto_ant,$ldec_monto_act,$ls_tipocomp,$as_codban,$as_ctaban,
                                    $as_estcla)
	{
		$lb_valido=false;
		$estpro[0]=$ls_est1;
		$estpro[1]=$ls_est2;
		$estpro[2]=$ls_est3;
		$estpro[3]=$ls_est4;
		$estpro[4]=$ls_est5;
		$estpro[5]=$as_estcla;
		$ls_mensaje = $this->io_int_spg->uf_operacion_codigo_mensaje($ls_operacionpre);
		if($ls_mensaje!="")
		{
			if(!$this->uf_spg_valida_datos_movimiento($ls_cuenta,$ls_descripcion,$ls_documento,&$ldec_monto))
			{ 
			   $this->io_msg->message($this->is_msg_error);
			   return false;
			}
			$this->io_int_spg->is_codemp=$arr_cmp["codemp"];
			$this->io_int_spg->is_comprobante=$arr_cmp["comprobante"];
			$this->io_int_spg->id_fecha=$arr_cmp["fecha"];
			$this->io_int_spg->is_procedencia=$arr_cmp["procedencia"];
			$this->io_int_spg->is_cod_prov=$arr_cmp["proveedor"];
			$this->io_int_spg->is_ced_bene=$arr_cmp["beneficiario"];
			$this->io_int_spg->is_tipo=$arr_cmp["tipo"];
			$this->io_int_spg->ib_AutoConta=false;
			if ($arr_cmp["tipo"]=="B")  
			{ 
			   $ls_fuente = $arr_cmp["beneficiario"]; 
			}	
			else
			{ 
				if ($arr_cmp["tipo"]=="P")
				 {  
					$ls_fuente = $arr_cmp["proveedor"]; 
				 }	
				 else 
				 {  
					$ls_fuente = "----------"; 
				 } 
			}
			$ls_status="";$ls_denominacion="";$ls_sc_cuenta="";
			if(!$this->io_int_spg->uf_spg_select_cuenta($arr_cmp["codemp"],$estpro,$ls_cuenta,&$ls_status,&$ls_denominacion,&$ls_sc_cuenta))
			{  
			  return false;
			}
			$this->io_int_spg->io_int_scg->id_fecha = $arr_cmp["fecha"];
			$ls_operacionpre = trim($ls_operacionpre);
			if (($ls_operacionpre == "PC")||($ls_operacionpre == "CS"))
			{
			  $lb_valido = $this->io_int_spg->uf_int_spg_insert_movimiento($arr_cmp["codemp"],$arr_cmp["procedencia"],
				 														 $arr_cmp["comprobante"],$arr_cmp["fecha"],
																		 $arr_cmp["tipo"],$ls_fuente,$arr_cmp["proveedor"],
																		 $arr_cmp["beneficiario"],$estpro,$ls_cuenta,$ls_procede_doc,
																		 $ls_documento,$ls_descripcion,$ls_mensaje,$ldec_monto_act,
																		 $ls_sc_cuenta,false,$as_codban,$as_ctaban);
			  if($lb_valido === true)
			  {
                $ls_mensaje=strtoupper($ls_operacionpre); // devuelve cadena en MAYUSCULAS
				$li_pos_i=strpos($ls_mensaje,"C"); 
				if (!($li_pos_i===false) and ($this->ib_spg_enlace_contable))
				{			      
					if ($this->ib_contabilizar)
					{
					    $this->is_codemp      = $arr_cmp["codemp"];
						$this->is_comprobante = $arr_cmp["comprobante"]; 
						$this->id_fecha       = $arr_cmp["fecha"];
			            $this->is_procedencia = $arr_cmp["procedencia"];
						$lb_valido=$this->uf_spg_integracion_scg_int($arr_cmp["codemp"],$ls_sc_cuenta,$ls_procede_doc,$ls_documento,$ls_descripcion,$ldec_monto,$as_codban,$as_ctaban);
					}
				}
			  }	 															 															 
			}
			elseif($ls_operacionpre == "CG")
			{
			  $ls_operacion_cs = "CS";
			  $ls_mensaje = $this->io_int_spg->uf_operacion_codigo_mensaje($ls_operacion_cs);
		      if($ls_mensaje!="")
		      {
			   $lb_valido = $this->io_int_spg->uf_int_spg_insert_movimiento($arr_cmp["codemp"],$arr_cmp["procedencia"],
																		 $arr_cmp["comprobante"],$arr_cmp["fecha"],
																		 $arr_cmp["tipo"],$ls_fuente,$arr_cmp["proveedor"],
																		 $arr_cmp["beneficiario"],$estpro,$ls_cuenta,$ls_procede_doc,
																		 $ls_documento,$ls_descripcion,$ls_mensaje,$ldec_monto_act,
																		 $ls_sc_cuenta,false,$as_codban,$as_ctaban);
																		 
			   if($lb_valido)
			   {
                $ls_mensaje=strtoupper($ls_operacionpre); // devuelve cadena en MAYUSCULAS
				$li_pos_i=strpos($ls_mensaje,"C"); 
				if (!($li_pos_i===false) and ($this->ib_spg_enlace_contable))
				{			      
				 if ($this->ib_contabilizar)
				 {
				  $this->is_codemp      = $arr_cmp["codemp"];
				  $this->is_comprobante = $arr_cmp["comprobante"]; 
				  $this->id_fecha       = $arr_cmp["fecha"];
			      $this->is_procedencia = $arr_cmp["procedencia"];
				  $lb_valido=$this->uf_spg_integracion_scg_int($arr_cmp["codemp"],$ls_sc_cuenta,$ls_procede_doc,$ls_documento,$ls_descripcion,$ldec_monto,$as_codban,$as_ctaban);
				 }
			    } 														 															 
				$ls_operacion_ue = "GC";
			    $ls_mensaje = $this->io_int_spg->uf_operacion_codigo_mensaje($ls_operacion_ue);
		        if($ls_mensaje!="")
		        {
				 $lb_valido = $this->uf_int_spg_insert_movimiento_ue($arr_cmp["codemp"],$arr_cmp["procedencia"],
																     $arr_cmp["comprobante"],$arr_cmp["fecha"],
																     $arr_cmp["tipo"],$ls_fuente,$arr_cmp["proveedor"],
																     $arr_cmp["beneficiario"],$estpro,$ls_cuenta,$ls_procede_doc,
																     $ls_documento,$ls_descripcion,$ls_mensaje,$ldec_monto_act,
																     $ls_sc_cuenta,true,$as_codban,$as_ctaban);
				}// Valido el mensaje de GC
			   } // Valido la Insercion del movimeinto CS															 
			  } // Valido el mensaje de CS															 
			} // elseif GC
			elseif(($ls_operacionpre == "GC")||($ls_operacionpre == "CP")||($ls_operacionpre == "PG"))
			{
			 $ls_mensaje = $this->io_int_spg->uf_operacion_codigo_mensaje($ls_operacionpre);
		     if($ls_mensaje!="")
		     {
			  $lb_valido = $this->uf_int_spg_insert_movimiento_ue($arr_cmp["codemp"],$arr_cmp["procedencia"],
																 $arr_cmp["comprobante"],$arr_cmp["fecha"],
																 $arr_cmp["tipo"],$ls_fuente,$arr_cmp["proveedor"],
																 $arr_cmp["beneficiario"],$estpro,$ls_cuenta,$ls_procede_doc,
																 $ls_documento,$ls_descripcion,$ls_mensaje,$ldec_monto_act,
																 $ls_sc_cuenta,true,$as_codban,$as_ctaban);
			 }													 
			} // elseif GC, CP, PG
			elseif($ls_operacionpre == "CCP")
			{
			  $ls_operacion_cs = "CS";
			  $ls_mensaje = $this->io_int_spg->uf_operacion_codigo_mensaje($ls_operacion_cs);
		      if($ls_mensaje!="")
		      {
			   $lb_valido = $this->io_int_spg->uf_int_spg_insert_movimiento($arr_cmp["codemp"],$arr_cmp["procedencia"],
																		 $arr_cmp["comprobante"],$arr_cmp["fecha"],
																		 $arr_cmp["tipo"],$ls_fuente,$arr_cmp["proveedor"],
																		 $arr_cmp["beneficiario"],$estpro,$ls_cuenta,$ls_procede_doc,
																		 $ls_documento,$ls_descripcion,$ls_mensaje,$ldec_monto_act,
																		 $ls_sc_cuenta,false,$as_codban,$as_ctaban);
			   if ($lb_valido)
			   {
			    $ls_operacion_ue = "CP";
			    $ls_mensaje = $this->io_int_spg->uf_operacion_codigo_mensaje($ls_operacion_ue);
		        if($ls_mensaje!="")
		        {
				 $lb_valido = $this->uf_int_spg_insert_movimiento_ue($arr_cmp["codemp"],$arr_cmp["procedencia"],
																     $arr_cmp["comprobante"],$arr_cmp["fecha"],
																     $arr_cmp["tipo"],$ls_fuente,$arr_cmp["proveedor"],
																     $arr_cmp["beneficiario"],$estpro,$ls_cuenta,$ls_procede_doc,
																     $ls_documento,$ls_descripcion,$ls_mensaje,$ldec_monto_act,
																     $ls_sc_cuenta,true,$as_codban,$as_ctaban);
				}// Valido el mensaje de CP
			   } // Valido la Insercion del movimeinto CS															 
			  } // Valido el mensaje de CS							
			}															 
			if(!$lb_valido)
			{
				$this->io_msg->message("No se registraron los detalles presupuestarios".$this->io_int_spg->is_msg_error);
				
			}
	   }
	   $ldec_monto = 0;
	   return $lb_valido;
	}
 //---------------------------------------------------------------------------------------------------------------------------------
 
 
/**********************************************************************************************************************************/
   function uf_insert_movimiento_spg($estprog,$as_cuenta,$as_procede_doc,$as_documento,$as_operacion,$as_descripcion,$ad_monto_actual,$li_orden)
   {
	   ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   //	 Function:  uf_spg_insert_movimiento
	   //	Arguments:  estprog->estructura programatica del gasto; as_cuenta->cuenta gasto ; as_procede_doc procedenca del documento
	   //               as_documento  n° del documento; as_operacion  operacion de gasto; as_descripcion	 descripcion del movimiento  
	   //               adec_monto   monto del movimiento 
	   //	  Returns:  lb_valido -> variable boolean
	   // Description:  Este método inserta un movimiento presupuestario en las tablas de detalle comprobante spg.
	   ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
	   $lb_valido = true;
	   $ls_fecha  = $this->io_function->uf_convertirdatetobd($this->id_fecha);
	   $ls_sql = " INSERT INTO spg_dtmp_cmp (codemp,procede,comprobante,fecha,codestpro1,codestpro2,codestpro3, ".
	             "                           codestpro4,codestpro5,spg_cuenta,procede_doc,documento,operacion,  ".
				 "                           descripcion,monto,orden,estcla) ".
			     " VALUES('".$this->is_codemp."','".$this->is_procedencia."','".$this->is_comprobante."', ".
				 "        '".$ls_fecha."','".$estprog[0]."','".$estprog[1]."','".$estprog[2]."','".$estprog[3]."', ".
				 "        '".$estprog[4]."','".$as_cuenta."','".$as_procede_doc."','".$as_documento."', ".
				 "        '".$as_operacion."','".$as_descripcion."','".$ad_monto_actual."',".$li_orden.", ".
				 "        '".$estprog[5]."')";		  
	   $li_rows=$this->io_sql_destino->execute($ls_sql);
	   if($li_rows===false)
	   {
		  $lb_valido=false;
		  $this->is_msg_error = "Error de SQL método->uf_spg_insert_movimiento class->xspg ::".$this->io_function->uf_convertirmsg($this->io_sql->message);
	   }
	   return $lb_valido;
	}// end function uf_spg_insert_movimiento_gasto
/**********************************************************************************************************************************/
	function uf_spg_obtener_orden_movimiento()
	{   
	//////////////////////////////////////////////////////////////////////////////
	//	   Function:  uf_spg_obtener_orden_movimiento
	//	    Returns:  li_orden -> numero del orden
	//	Description:  Retorna el número de orden del movimiento de gasto spg
	/////////////////////////////////////////////////////////////////////////////	
		$li_orden=0;
		$ld_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
		$ls_sql= " SELECT count(*) as orden  FROM spg_dtmp_cmp".
				 " WHERE codemp='".$this->is_codemp."' AND procede='".$this->is_procedencia."' AND comprobante='".$this->is_comprobante."'".
				 " AND fecha='".$ld_fecha."' " ;
		$rs_data=$this->io_sql->select($ls_sql);
	    if($rs_data===false)
	    {
   	 	   $this->is_msg_error="Error de SQL método->uf_spg_obtener_orden_movimiento class->xspg ::".$this->io_function->uf_convertirmsg($this->io_sql->message);
		   return false;
	    }
	    else {  if($row=$this->io_sql->fetch_row($rs_data))  { $li_orden=$row["orden"]; } } 
		
	   $this->io_sql->free_result($rs_data);		
	   return $li_orden;
    } //end function uf_spg_obtener_orden_movimiento()
/**********************************************************************************************************************************/
	function uf_spg_integracion_scg($as_codemp, $as_scgcuenta, $as_procede_doc, $as_documento, $as_descripcion, $adec_monto_actual)
	{
		$lb_valido=true;$ls_debhab=""; $ls_status=""; $ls_denominacion=""; $ls_mensaje_error="";$ldec_monto=0;$li_orden=0;
	
		if($adec_monto_actual > 0) 	{ $ls_debhab = "D"; }
		else{  $ls_debhab = "H"; }
		if (!$this->io_int_spg->io_int_scg->uf_scg_select_cuenta( $as_codemp, $as_scgcuenta, &$ls_status, $ls_denominacion))
		{
		   $this->io_msg->message("La cuenta contable [". trim($as_scgcuenta) ."] no existe.");
		   return false;
		} 
		if($ls_status!="C")
		{ 
		   $this->io_msg->message("La cuenta contable [". trim($as_scgcuenta) ."] no es de movimiento.");
		   return false;
		} 
		
		$this->io_int_spg->io_int_scg->is_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
		$this->io_int_spg->io_int_scg->is_codemp=$as_codemp;
		$this->io_int_spg->io_int_scg->is_procedencia=$this->is_procedencia;
		$this->io_int_spg->io_int_scg->is_comprobante=$this->is_comprobante;
		
		if (!$this->uf_scg_select_movimiento($as_scgcuenta, $as_procede_doc, $as_documento, $ls_debhab, $ldec_monto, $li_orden))
		{
		   	//$lb_valido = $this->io_int_scg->uf_scg_registro_movimiento_int($as_codemp, $as_scgcuenta, $as_procede_doc, $as_documento, $ls_debhab, $as_descripcion, 0, $adec_monto_actual);
			$lb_valido = $this->uf_scg_procesar_insert_movimiento($as_codemp,$this->is_procedencia,$this->is_comprobante,$this->id_fecha,$this->is_tipo,$this->is_cod_prov,$this->is_ced_ben,$as_scgcuenta, $as_procede_doc, $as_documento, $ls_debhab, $as_descripcion, 0, $adec_monto_actual);
		}																	 
	return $lb_valido;
	}//uf_spg_integracion_scg
/**********************************************************************************************************************************/
	function uf_scg_procesar_insert_movimiento($as_codemp,$as_procede, $as_comprobante, $as_fecha,
                                     	      $as_tipo_destino,$as_cod_prov, $as_ced_bene, $as_cuenta,
										      $as_procede_doc, $as_documento,$as_debhab,$as_descripcion,
										      $adec_monto_anterior, $adec_monto_actual )
    {											  
	///////////////////////////////////////////////////////////////////////////////////////////////////
	// 	 Function:  uf_scg_procesar_insert_movimiento
	// 	   Access:  public
	//  Arguments:  $as_codemp->empresa,$as_procede->procede,$as_comprobante->comprobante,$as_fecha->fecha comprobante,
    //	            $as_cuenta->cuenta contable ,$as_procede_doc->procede documento,$as_documento->nº documento
	//              $as_operacion->operacion debe haber,$adec_monto->mnto movimiento
	//	  Returns:  Boolean
	//Description:  Este método registra un movimiento contable (Método Principal MAIN )
	////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_desproc="";	
		$li_orden=0;
		$this->is_codemp      = $as_codemp;
		$this->is_procedencia = $as_procede;
		$this->is_comprobante = $as_comprobante;
		$this->id_fecha       = $as_fecha;
		$this->is_cod_prov    = $as_cod_prov;
		$this->is_ced_ben     = $as_ced_bene;
		$this->is_tipo        = $as_tipo_destino;

		if (!($this->io_int_spg->io_int_scg->uf_valida_procedencia( $as_procede , $ls_desproc))) { return false; }	 
		
		if ($this->uf_scg_select_movimiento($as_cuenta,$as_procede_doc,$as_documento,$as_debhab,&$adec_monto_actual,&$li_orden)) 
		{
		   $this->is_msg_error="El movimiento contable ya existe.";
		   return false; 	
		}
		$lb_valido = $this->uf_scg_insert_movimiento($as_cuenta,$as_procede_doc,$as_documento,$as_debhab,$as_descripcion,$adec_monto_actual);
		return $lb_valido;
	} //end function uf_scg_registro_movimiento()
/**********************************************************************************************************************************/
	function uf_scg_select_movimiento($as_cuenta,$as_procede_doc,$as_documento,$as_debhab,&$adec_monto,&$ai_orden)
	{
	////////////////////////////////////////////////////////////////////////////////////////////////////
	// 	 Function:  uf_scg_select_movimiento
	// 	   Access:  public
	//  Arguments:  as_sc_cuenta-> cuenta contable;as_procede_doc->procedencia documento ; as_documento-> documento
	//              as_debhab->operacion debe-haber; adec_monto->monto Operacion;ai_orden->orden movimiento
	//	  Returns:  boolean
	//Description:  Este método verifica si existe o no el movimiento contable
	////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_existe = false;
		$ld_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
	    $ls_sql =   " SELECT monto,orden".
		            " FROM scg_dtmp_cmp".
		            " WHERE codemp='".$this->is_codemp."' AND procede='".$this->is_procedencia."' AND comprobante='".$this->is_comprobante."' AND ".
					"       fecha='".$ld_fecha."' AND procede_doc='".$as_procede_doc."' AND documento ='".$as_documento."' AND sc_cuenta='".$as_cuenta."' AND debhab='".$as_debhab."'";
		$rs_mov=$this->io_sql->select($ls_sql);
		
		if($rs_mov===false)	{  $this->is_msg_error = "Error en el método uf_scg_select_movimiento ".$this->io_function->uf_convertirmsg($this->io_sql->message);	}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_mov))
			{
				$lb_existe=true;
				$adec_monto = $row["monto"];
				$ai_orden   = $row["orden"];
			}
			else  {  $lb_existe=false; }
		}
	   $this->io_sql->free_result($rs_mov);		
	   return $lb_existe;
	} // end function uf_scg_select_movimiento
/**********************************************************************************************************************************/
	function uf_scg_delete_movimiento($as_codemp,$as_procede,$as_comprobante,$as_fecha,$as_cuenta,$as_procede_doc,$as_documento,$as_operacion )
	{
	////////////////////////////////////////////////////////////////////////////////////////////////////
	// 	 Function:  uf_scg_delete_movimiento
	// 	   Access:  public
	//	  Returns:  boolean
	//Description:  Este método elimina el movimineto contable
	////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$ls_fecha = $this->io_function->uf_convertirdatetobd($as_fecha);
		
		$ls_sql =   " DELETE FROM scg_dtmp_cmp ".
					" WHERE codemp='".$as_codemp."' AND procede='".$as_procede."' AND comprobante='".$as_comprobante ."' AND fecha= '".$ls_fecha."' AND ".
					"       sc_cuenta= '".$as_cuenta."' AND procede_doc='".$as_procede_doc."' AND documento ='".$as_documento."' AND debhab='".$as_operacion."'";
		$li_result=$this->io_sql->execute($ls_sql);
		if($li_result===false)
		{
			$this->is_msg_error = "Error en método uf_scg_delete_movimiento ".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
	    return $lb_valido;
	} // end function uf_scg_delete_movimiento()
/**********************************************************************************************************************************/
	////////////////////////////////////////////////////////////////////////////////////////////////////
	// 	 Function:  uf_scg_insert_movimiento
	// 	   Access:  public
	//  Arguments:  $as_cuenta->cuenta contable ,$as_procede_doc->procede documento,$as_documento->nº documento
	//              $as_operacion->operacion debe haber,$adec_monto->mnto movimiento
	//	  Returns:  Boolean
	//Description:  Este método registra un movimiento final contable enla tabla movimiento  (DEPENDE DEL PROCESAR)
	////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_scg_insert_movimiento( $as_cuenta, $as_procede_doc, $as_documento, $as_debhab, $as_descripcion, $adec_monto )
	{
		$lb_valido = true;
		$li_orden = $this->uf_scg_obtener_orden_movimiento();
		$ls_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
		$ls_sql = "INSERT INTO scg_dtmp_cmp (codemp,procede,comprobante,fecha,sc_cuenta,procede_doc,documento,debhab,descripcion,monto,orden) " . 
				  " VALUES('".$this->is_codemp."','".$this->is_procedencia."','".$this->is_comprobante."','" .$ls_fecha."','".$as_cuenta."', '".$as_procede_doc."','".$as_documento."','".$as_debhab."','".$as_descripcion."',".$adec_monto.",".$li_orden.")" ;
		$li_result=$this->io_sql_destino->execute($ls_sql);

		if($li_result===false)
		{
		   
		   if($this->io_sql->errno==1452)
		   {
			   $this->is_msg_error = "Error en método uf_scg_insert_movimiento, Fallo alguna clave foranea";
		   }
		   else
		   {
		   		$this->is_msg_error = "Error en método uf_scg_insert_movimiento ".$this->io_function->uf_convertirmsg($this->io_sql->message);
		   }
		   //print $this->io_sql->message;
		   $lb_valido=false;
		}
		return $lb_valido;
	} // end function uf_scg_insert_movimiento()
/**********************************************************************************************************************************/
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//   Function:  uf_spg_select_movimiento
    //     Access: public	
	//	Arguments:  as_est1...as_est5 -> estructura programatica  ; as_cuenta->cuenta presupuestaria
	//              as_procede_doc- > procedenca del documento ; as_documento -> n° del documento
	//	  Returns:	lb_valido -> variable boolean
	//Description:  Este método verifica si el movimiento ya existe o no en la tabla de movimientos presupuestario de gasto,
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	function uf_spg_select_movimiento($estprog,$as_cuenta,$as_procede_doc,$as_documento,$as_operacion,&$adec_monto,&$ai_orden)
	{
  	    $lb_existe  = false;
		$ls_cuenta  = "";$lb_existe=false;$ldec_monto=0;$li_orden=0;
		$ls_codemp  =  $this->is_codemp ;
		$ls_procedencia = $as_procede_doc;
		$ls_comprobante = $as_documento;
		$ls_fecha = $this->io_function->uf_convertirdatetobd($this->id_fecha);
	    $ls_sql = "SELECT spg_cuenta,monto,orden
			         FROM spg_dtmp_cmp
			        WHERE codemp = '".$ls_codemp."'
					  AND codestpro1 = '".$estprog[0]."'
					  AND codestpro2 = '".$estprog[1]."'
					  AND codestpro3 = '".$estprog[2]."'
					  AND codestpro4 = '".$estprog[3]."'
					  AND codestpro5 = '".$estprog[4]."'
					  AND estcla = '".$estprog[5]."'
					  AND procede = '".$this->is_procedencia."'
					  AND comprobante = '".$this->is_comprobante."'
					  AND fecha = '".$ls_fecha."'
					  AND procede_doc = '".$as_procede_doc."'
					  AND documento = '".$as_documento."'
					  AND spg_cuenta = '".$as_cuenta."'
					  AND operacion = '".$as_operacion."' "; 
		$rs_data=$this->io_sql_destino->select($ls_sql);
		if ($rs_data===false)
		   {
   	 	     $this->is_msg_error="Error de SQL método->uf_spg_select_movimiento class->xspg ::".$this->io_function->uf_convertirmsg($this->io_sql->message);
		     return false;
		   }
		else
		   {
		     if ($row=$this->io_sql->fetch_row($rs_data))
			    {
				  $ls_cuenta  = $row["spg_cuenta"];
				  $ldec_monto = $row["monto"];
				  $adec_monto = $ldec_monto;
				  $li_orden   = $row["orden"];
				  $ai_orden   = $li_orden;
				  $lb_existe  = true;
			    }				
		   }
		$this->io_sql->free_result($rs_data);				
		return $lb_existe;
	} // end function uf_select_movimientos
/**********************************************************************************************************************************/
	function uf_spg_comprobante_actualizar($ai_montoanterior, $ai_montoactual, $ls_tipocomp)
    {
      $lb_valido=false; 
	  $li_tipocomp=0;
	  if ($ls_tipocomp=="C") { $li_tipocomp=1; }
      if ($ls_tipocomp=="P") { $li_tipocomp=2; }
	  if ($this->uf_spg_comprobante_select())
	     { 
		   $lb_valido = $this->uf_spg_comprobante_update($ai_montoanterior, $ai_montoactual);
   	     }
	  else 
	     { 
	       $lb_valido = $this->uf_spg_comprobante_insert($ai_montoactual, $li_tipocomp);  
	     }
     return $lb_valido;
    } // end function uf_spg_comprobante_actualizar()
/**********************************************************************************************************************************/
	/////////////////////////////////////////////////////////////////////////////////////////////////////
    //    Function: uf_spg_comprobante_select
    //      Access: public
    //     Returns: retorna valido
    // Description: Este método verifica si existe el comprobante SIGESP_cmp
    /////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_spg_comprobante_select()
	{
	  $lb_existe = false;
	  $ld_fecha  = $this->io_function->uf_convertirdatetobd($this->id_fecha);
	  $ls_sql    = "SELECT codemp
		              FROM sigesp_cmp_md
				     WHERE procede = '".$this->is_procedencia."'
					   AND comprobante = '".$this->is_comprobante."'
					   AND fecha='".$ld_fecha."'";
	  $rs_data = $this->io_sql_destino->select($ls_sql);
	  if ($rs_data===false)
	     {
   	 	   $this->is_msg_error="Error de SQL método->uf_spg_comprobante_select class->xspg ::".$this->io_function->uf_convertirmsg($this->io_sql->message);
	     }
	  else
	     {
		   if ($row=$this->io_sql_destino->fetch_row($rs_data))
		      { 
			    $lb_existe=true;
			  }
		 } 
	  $this->io_sql_destino->free_result($rs_data);		
	  return $lb_existe;
	} // end function uf_spg_comprobante_select()
/**********************************************************************************************************************************/
	 /////////////////////////////////////////////////////////////////////////////////////////////////////
    //    Function: uf_spg_comprobante_update
    //   Arguments: ai_montoanterior -> monto anterior ;$ai_montoactual -> monto actual
    //      Access: public
    //     Returns: retorna valido
    // Description: Este método actualiza si existe el comprobante SIGESP_cmp
    /////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_spg_comprobante_update($li_montoanterior, $li_montoactual)
	{
	   $lb_valido = true;
	   $li_total = ( - $li_montoanterior + $li_montoactual);
	   $ld_fecha = $this->io_function->uf_convertirdatetobd($this->id_fecha);
	   $ls_sql = "UPDATE sigesp_cmp_md
	                 SET total = total + '".$li_total."'
	               WHERE procede = '".$this->is_procedencia."'
				     AND comprobante = '".$this->is_comprobante."'
					 AND fecha = '".$ld_fecha."'";
	   $li_exec = $this->io_sql_destino->execute($ls_sql);//Se cambió de $this->io_sql a $this->io_sql_destino 23/04/09.
	   if ($li_exec===false)
	      {
 	        $this->is_msg_error="Error de SQL método->uf_spg_comprobante_update class->xspg ::".$this->io_function->uf_convertirmsg($this->io_sql->message);
		    $lb_valido = false;
	      }	   
	   return $lb_valido;
	} // function uf_spg_comprobante_update()
/**********************************************************************************************************************************/
    /////////////////////////////////////////////////////////////////////////////////////////////////////
    //    Function: uf_spg_comprobante_insert
    //   Arguments: ai_montoanterior -> monto anterior ;$ai_montoactual -> monto actual
    //      Access: public
    //     Returns: retorna valido
    // Description: Este método inserta en el compronate de gasto
    /////////////////////////////////////////////////////////////////////////////////////////////////////
	function  uf_spg_comprobante_insert($ai_monto, $ai_tipocomp)
	{
		$lb_valido=true;
		$ls_codemp = $this->is_codemp;  $ls_procede = $this->is_procedencia; $ls_comprobante = $this->is_comprobante;
		$ls_descripcion=$this->is_descripcion; 	$ls_tipo=$this->is_tipo;
		$ls_codpro=$this->is_cod_prov;
		$ls_cedbene=$this->is_ced_ben;	
		$ld_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
	    $ls_sql = " INSERT INTO sigesp_cmp_md(codemp,procede,comprobante,fecha,descripcion,total,".
		          "                           tipo_destino,cod_pro,ced_bene,tipo_comp)  ".
				  " VALUES ('".$ls_codemp."', '".$ls_procede."', '".$ls_comprobante."', ".
				  "         '".$ld_fecha."', '".$ls_descripcion."', '".$ai_monto."',    ".
				  "         '".$ls_tipo."', '".$ls_codpro."', '".$ls_cedbene."', '".$ai_tipocomp."' )";	  
		$li_exec=$this->io_sql_destino->execute($ls_sql);                                                                                                                                                                                          
		if($li_exec===false)
		{
 	       $this->is_msg_error="Error de SQL método->uf_spg_comprobante_insert class->class_sigesp_int_spg ::".$this->io_function->uf_convertirmsg($this->io_sql->message);
		   //print $this->io_sql->message;
		   $lb_valido=false;
		}
	return $lb_valido;
   }  // end function uf_spg_comp_insert
/**********************************************************************************************************************************/
function uf_spg_valida_datos_movimiento($as_cuenta,$as_descripcion,$as_documento,$adec_monto)
{
	if (trim($as_cuenta)=="")
	{
		$this->is_msg_error = "Registre la Cuenta Gasto." ;
		return false;	
	}
	if(trim($as_descripcion)=="")
	{
		$this->is_msg_error = "Registre la Descripción del Movimiento." ;
		return false;
	}
	
	if(trim($as_documento) =="") 
	{
		$this->is_msg_error = "Registre el Nº de documento." 	;
		return false;	
	}
	 return true ;
}
/**********************************************************************************************************************************/
function uf_guardar_movimientos_contable($arr_cmp,$as_cuenta,$as_procede_doc,$as_descripcion,$as_documento,
                                         $as_operacioncon,$adec_monto)
{
	$lb_valido=false;

	if(!$this->uf_scg_valida_datos_mov_contable($as_cuenta,$as_descripcion,$as_documento,$adec_monto))
	{ 
		$this->io_msg->message($this->is_msg_error);
	   return false;
	}
	$lb_valido = $this->uf_scg_procesar_movimiento_cmp($arr_cmp["codemp"],$arr_cmp["procedencia"],$arr_cmp["comprobante"],$arr_cmp["fecha"],
                                                       $arr_cmp["proveedor"],$arr_cmp["beneficiario"],$arr_cmp["tipo"],$arr_cmp["tipo_comp"],
                                                       $as_cuenta,$as_procede_doc,$as_documento,$as_operacioncon,$as_descripcion,$adec_monto);
	if(!$lb_valido)
	{
		$this->io_msg->message("Error al registrar movimiento contable".$this->io_int_scg->is_msg_error);
	}
	$ldec_monto = 0;
    return $lb_valido;
 }
/**********************************************************************************************************************************/
	function uf_scg_valida_datos_mov_contable($as_cuenta,$as_descripcion,$as_documento,$adec_monto)
	{
		if (trim($as_cuenta)=="")
		{
			$this->is_msg_error = "Registre la Cuenta Gasto." ;
			return false;	
		}
		
		if(trim($as_descripcion)=="")
		{
			$this->is_msg_error = "Registre la Descripción del Movimiento." ;
			return false;
		}
		
		if(trim($as_documento) =="") 
		{
			$this->is_msg_error = "Registre el Nº de documento." 	;
			return false;	
		}
		
		if($adec_monto == 0)
		{
			$this->is_msg_error = "Registre el Monto." ;	
			return false;
		} 
	
	   return true ;
	}
/**********************************************************************************************************************************/
	function uf_scg_procesar_movimiento_cmp($as_codemp,$as_procedencia,$as_comprobante,$ad_fecha,
											$as_proveedor,$as_beneficiario,$as_tipo,$as_tipo_comp,$as_sc_cuenta,
											$as_procede_doc,$as_documento,$as_operacion,$as_descripcion,$adec_monto)
	{
		$this->is_codemp     = $as_codemp;
		$this->is_procedencia= $as_procedencia;
		$this->is_comprobante= $as_comprobante;
		$this->id_fecha		 = $ad_fecha;
		$this->is_cod_prov   = $as_proveedor;
		$this->is_ced_ben    = $as_beneficiario;
		$this->is_tipo       = $as_tipo;		
	
		$this->is_comprobante = $this->io_function->uf_cerosizquierda($as_comprobante,15);
		$as_documento		  =	$this->io_function->uf_cerosizquierda($as_documento,15);
		$lb_valido=true;

		if(!$this->io_int_scg->uf_scg_select_cuenta($as_codemp,$as_sc_cuenta,&$ls_status,&$ls_denominacion))
		{
			$this->io_msg->message("La cuenta ".$as_sc_cuenta." no existe");
			return false;
		}
		
		//- valido que sea una cuenta de movimiento
		if($ls_status!="C")
		{
			$this->io_msg->message("La cuenta ".$as_sc_cuenta." no es de movimiento");
			return false;
		}
		
		//-- verifico la Procede_Doc
		if(!$this->io_int_scg->uf_valida_procedencia($as_procede_doc,&$as_descproc))
		{
			$this->io_msg->message("La procedencia ".$as_procede_doc." no esta registrada");
			return false;
		}
		
		//-- verifico la Fecha
		if(!$this->io_fecha->uf_valida_fecha_mes($as_codemp,$ad_fecha))
		{
			$this->io_msg->message($this->int_fec->is_msg_error);
			return false;
		}

		if($this->uf_scg_select_movimiento($as_sc_cuenta,$as_procede_doc,$as_documento,$as_operacion, &$adec_monto_anterior,&$ai_orden))
		{	
			$this->io_msg->message("El Movimiento ya existe ");
			return false;
		}
		//Inicio la transacion
		if($lb_valido)
		{
			$lb_valido= $this->uf_scg_insert_movimiento( $as_sc_cuenta, $as_procede_doc, $as_documento, $as_operacion, $as_descripcion, &$adec_monto );
		}
		return $lb_valido;
	}
/**********************************************************************************************************************************/
	////////////////////////////////////////////////////////////////////////////////////////////////////
	// 	 Function:  uf_scg_obtener_orden_movimiento
	// 	   Access:  public
	//	  Returns:  integer
	//Description:  Este método genera un numero de orden secuencial de los movimiento 
	////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_scg_obtener_orden_movimiento()
	{
		$li_orden=0;
		$ld_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
		$ls_sql = " SELECT count(*) as orden " .
					" FROM scg_dtmp_cmp " .
					" WHERE codemp='".$this->is_codemp."' AND procede= '". $this->is_procedencia ."' AND comprobante= '".$this->is_comprobante."' AND fecha='".$this->id_fecha."'";
		$rs_saldos=$this->io_sql->select($ls_sql);
		if($rs_saldos==false)
		{
		  $this->is_msg_error = "Error en el método uf_scg_obtener_orden_movimiento ".$this->io_sql->message;
		  $lb_valido=false;
		}
		else
		{
		  if($row=$this->io_sql->fetch_row($rs_saldos))  {	$li_orden=$row["orden"]; }
		}		 
		return $li_orden;
	} //fin de uf_scg_obtener_orden_movimiento()
/**********************************************************************************************************************************/
	 /////////////////////////////////////////////////////////////////////////////////////////////////////
    //    Function: uf_int_spg_delete_movimiento
    //      Access: public
    //   Arguments: $as_codemp -> codigo empresa  ; $as_procedencia -> procedencia documento ; as_comprobante -> comprobante de gasto ; $as_fecha -> fecha comprobante ;
	//              $estprog -> arreglo que contiene la estructura programatica ; $as_cuenta-> cuenta gasto ;
    //	            $as_procede_doc -< procedencia documento ; $as_documento-> documento ; $as_descripcion -> descripcion ; $as_mensaje -> mensaje ; $adec_monto-> monto operacion
    //     Returns: retorna un mensaje interno para operaciones 
    // Description: Método que elimina un movimiento de gasto por medio de la integracion en lote
    /////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_int_spg_delete_movimiento($as_codemp,$as_procedencia,$as_comprobante,$as_fecha,$as_tipo,$as_fuente,$as_cod_pro,$as_ced_bene,
	                                      $estprog,$as_cuenta,$as_procede_doc,$as_documento,$as_descripcion,$as_mensaje,$as_tipo_comp,
										  $adec_monto_anterior,$adec_monto_actual,$as_sc_cuenta)
	{
	   $lb_valido=false;
	   $this->is_codemp      = $as_codemp;
	   $this->is_procedencia = $as_procedencia;
	   $this->is_comprobante = $as_comprobante;
	   $this->id_fecha       = $as_fecha;
	   $this->is_tipo=$as_tipo;
	   $this->is_fuente=$as_fuente;
	   $this->is_cod_prov=$as_cod_pro;
	   $this->is_ced_ben=$as_ced_bene;
       $ls_operacion = $this->io_int_spg->uf_operacion_mensaje_codigo($as_mensaje);
	   if(empty($ls_operacion)) { return false; }
	   if(!$this->uf_spg_select_movimiento( $estprog, $as_cuenta, $as_procede_doc, $as_documento, $ls_operacion, $lo_monto_movimiento, $lo_orden))  
	   {
          $this->io_msg->message("El movimiento no existe.");			   		  
		  return false; 	
	   }
   
       $lb_valido = $this->uf_spg_delete_movimiento($estprog, $as_cuenta, $as_procede_doc, $as_documento, $ls_operacion) ;
	   if ($lb_valido)
	   {
          $lb_valido = $this->uf_spg_comprobante_actualizar($lo_monto_movimiento,0,"C");
	   }
	   return $lb_valido;
    } // end function uf_int_spg_delete_movimiento()
/**********************************************************************************************************************************/
    
/**********************************************************************************************************************************/
	//////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	 Function:  uf_verificar_comprobante()
	//	   Access:  public
	//	Arguments:  $as_codemp-> empresa,$as_procedencia->procedencia,$as_comprobante->comprobante,$as_fecha
	//	  Returns:	booleano lb_existe
	//Description:  Método que verifica si existe o no el comprobante
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_verificar_comprobante($as_codemp,$as_procedencia,$as_comprobante)
	{
	   $lb_existe=false;
	   $ls_sql =   " SELECT comprobante ".
	               " FROM   sigesp_cmp_md ".
				   " WHERE codemp='".$as_codemp."' AND procede='".$as_procedencia."' AND comprobante='".$as_comprobante."' ";
	   $lr_result = $this->io_sql->select($ls_sql);
	   if($lr_result===false)
	   {
		  $this->is_msg_error="Error en delete Comprobante".$this->io_function->uf_convertirmsg($this->io_sql->message);
		  return false;
	   }
	   else  
	   { 
	      if($row=$this->io_sql->fetch_row($lr_result)) 
		  { 
		     $lb_existe=true;
		  }  
	  }
	  return $lb_existe;
	} // end function uf_select_comprobante
/**********************************************************************************************************************************/
function uf_load_fuentes_financiamiento($as_codemp)
{
  $ls_sql  = "SELECT codfuefin, denfuefin FROM sigesp_fuentefinanciamiento WHERE codemp='".$as_codemp."' ORDER BY codfuefin ASC";
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
  {
	   $this->is_msg_error="Error.CLASS->sigesp_spg_c_mod_presupuestarias.php.-Método->uf_load_fuentes_financiamiento ".$this->io_function->uf_convertirmsg($this->io_sql->message);
	   $this->io_msg->message($this->is_msg_error);
	   return false;
  }
  return $rs_data;
}
/**********************************************************************************************************************************/
function uf_load_unidades_administradoras($as_codemp)
{
  $ls_sql   = "SELECT coduac, denuac FROM spg_ministerio_ua WHERE codemp='".$as_codemp."' ORDER BY coduac ASC";
  $rs_datos = $this->io_sql->select($ls_sql);
  if ($rs_datos===false)
  {
	   $this->is_msg_error="Error.CLASS->sigesp_spg_c_mod_presupuestarias.php.-Método->uf_load_unidades_administradoras ".$this->io_function->uf_convertirmsg($this->io_sql->message);
	   $this->io_msg->message($this->is_msg_error);
	   return false;
  }
  return $rs_datos;
}
//---------------------------------------------------------------------------------------------------------------------------------

	function uf_insertar_resultado($as_database_origen,$as_database_destino,$as_descripcion)
	{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	Funcion       uf_insertar_resultado
	//	Access        public
	//	Arguments	  ai_totrows  // total de filas del detalle
	//				  ao_object  // objetos del detalle
	//	Returns	      lb_valido. Retorna una variable booleana
	//	Description   Registra el resultado de 	el proceso de transferencia de modificaciones 
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_codproc = "SPGTRF";
		$ls_codsis =  "SPG";
		$li_codres = "";
		$this->io_sql->begin_transaction();
		$ls_sql="SELECT MAX(codres) as codigo FROM sigesp_dt_proc_cons";
		$rs_data = $this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
		 $lb_valido=false;
		 $this->io_msg->message("Error al seleccionar Resultado".$this->io_function->uf_convertirmsg($this->io_sql->message));
		}
		else
		{   
		 while($row=$this->io_sql->fetch_row($rs_data))
		 {
		   $li_codres=$row["codigo"];
		 }			
		}  // else
		if($lb_valido)  
		{	
			$li_codres++;
			$ls_codres=str_pad($li_codres,10,"0",0);
			$ls_sql="INSERT INTO sigesp_dt_proc_cons (codres, codproc, codsis, fecha, bdorigen, bddestino, descripcion) ".
					"VALUES('".$ls_codres."','".$ls_codproc."', '".$ls_codsis."', '".date("Y-m-d H:i")."', ".
					"'".$as_database_origen."', '".$as_database_destino."','".$as_descripcion."') ";			
			$li_row=$this->io_sql->Execute($ls_sql);
			if($li_row===false)
			{
				$this->io_sql->rollback();
				$this->io_msg->message("Error al Insertar Resultado".$this->io_function->uf_convertirmsg($this->io_sql->message));
				$lb_valido = false;
			}
			else
			{
			  $this->io_sql->commit();
			}
	    }		
	 return $lb_valido;
	}
//------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_codempresa_bd($as_hostname, $as_login, $as_password,$as_database,$as_gestor)
	{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	Funcion       uf_obtener_codempresa_bd
	//	Access        public
	//	Arguments	  $as_hostname  // hostname para conectar con la Base de Datos
	//                $as_login     // login para conectar con la Base de Datos
	//                $as_password  // password o clave para conectac con la Base de Datos
	//                $as_database  // nombre de la Base Datos con la que se quiere conectar
	//                $as_gestor    // nombre del gestor que maneja la Base de Datos
	//                $as_codempdes // Código de la Empresa destino
	//	Returns	      lb_valido. Retorna una variable booleana
	//	Description   Devuelve el Código de Empresa de la Base de Datos referenciada
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_sql="SELECT codemp ". 
				"  FROM sigesp_empresa ";
		$ls_codemp="";		
		$rs_data   = $this->io_sql_destino->select($ls_sql);
		if ($rs_data===false)
		   {
			  $this->io_msg->message($this->io_function->uf_convertirmsg($io_sql_destino->message));		 
		   }
		else
		   {
			 $li_numrows = $this->io_sql_destino->num_rows($rs_data);
			 if ($li_numrows>0)
				{
				 $lb_valido=true;
				 if ($row=$this->io_sql_destino->fetch_row($rs_data))
				 {
				  $ls_codemp = $row["codemp"];
				 }                  
				 $this->io_sql_destino->free_result($rs_data);	
				}
		   }
	return $ls_codemp;
	}
//------------------------------------------------------------------------------------------------------------------------------------
    function uf_cargar_cmp_md($as_codemp,$as_procedencia,$as_comprobante,$as_fechacont)
	{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	 Function:  uf_cargar_cmp_md()
	//	   Access:  public
	//	Arguments:  $as_codemp-> empresa,$as_procedencia->procedencia,$as_comprobante->comprobante,$as_fecha
	//	  Returns:	booleano lb_existe
	//Description:  Método que devuelve los datos asociados a un comprobante de gasto
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=false;
	   $ls_newfec=$this->io_function->uf_convertirdatetobd($as_fechacont);
	   $ls_sql =   " SELECT * ".
	               " FROM sigesp_cmp_md ".
				   " WHERE codemp='".$as_codemp."'".
				   "   AND procede='".$as_procedencia."'".
				   "   AND comprobante='".$as_comprobante."' ".
				   "   AND fecha = '".$ls_newfec."'";		   
	   $lr_result = $this->io_sql_destino->select($ls_sql);
	   if($lr_result===false)
	   {
		  $this->is_msg_error="Error en Clase->uf_cargar_cmp_md".$this->io_function->uf_convertirmsg($this->io_sql->message);
	   }
	   else  
	   { 
	      if($row=$this->io_sql->fetch_row($lr_result)) 
		  { 
		     $lb_valido =true;
		  }  
	  }
	  return $lb_valido;
	} // end function uf_cargar_comprobante_md
//-----------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_cargar_dtmp_cmp($as_codemp,$as_procedencia,$as_comprobante,$as_fechacont)
	{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	 Function:  uf_cargar_dtmp_cmp()
	//	   Access:  public
	//	Arguments:  $as_codemp-> empresa,$as_procedencia->procedencia,$as_comprobante->comprobante,$as_fecha
	//	  Returns:	booleano lb_existe
	//Description:  Método que devuelve los detalles asociados a un comprobante de gasto
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=false;
	   $ls_newfec=$this->io_function->uf_convertirdatetobd($as_fechacont);
	   $ls_sql =   " SELECT * ".
	               " FROM spg_dtmp_cmp ".
				   " WHERE codemp='".$as_codemp."'".
				   "   AND procede='".$as_procedencia."'".
				   "   AND comprobante='".$as_comprobante."' ".
				   "   AND fecha = '".$ls_newfec."'";	   
	   $lr_result = $this->io_sql_destino->select($ls_sql);
	   if($lr_result===false)
	   {
		  $this->is_msg_error="Error en Clase->uf_cargar_dtmp_cmp".$this->io_function->uf_convertirmsg($this->io_sql->message);
	   }
	   else  
	   { 		     
			 $lb_valido =true; 
	   }
	  return $lb_valido;
	} // end function uf_cargar_comprobante_md
//------------------------------------------------------------------------------------------------------------------------------------
    function uf_reversar_transferencia_comprobantes_md($as_comprobante,$as_fecha,$as_fecconta,$as_procede,$aa_seguridad)
	{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	 Function:  uf_reversar_transferencia_comprobantes_md()
	//	   Access:  public
	//	Arguments:  $as_codemp-> empresa,$as_procedencia->procedencia,$as_comprobante->comprobante,$as_fecha
	//	  Returns:	booleano lb_existe
	//Description:  elimna el detalle y la cabecera de los comprobante que se generan al modificar el presupuesto
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido = true;		 
		 $lb_valido=$this->uf_cargar_dtmp_cmp($this->ls_codemp,$as_procede,$as_comprobante,$as_fecconta);
		 if ($lb_valido)
		 {
			$ls_newfec=$this->io_function->uf_convertirdatetobd($as_fecconta);
			$ls_sql="  DELETE FROM spg_dtmp_cmp                  ".
                    "   WHERE codemp='".$this->ls_codemp."'      ".
                    "     AND procede='".$as_procede."'          ".
                    "     AND comprobante='".$as_comprobante."'  ".
	                "     AND fecha = '".$ls_newfec."'           ";
			   $lr_result1 = $this->io_sql_destino->execute($ls_sql);
			   if($lr_result1===false)
			   {
				  $this->is_msg_error="Error en Clase->uf_reversar_transferencia_comprobantes_md".
				                       $this->io_function->uf_convertirmsg($this->io_sql->message);
			   }
			   else  
			   { 
		     
					 $lb_valido =true;
			   } 
		 }
		 //----------------Selecciona y elimna de la cabecera------------------------------------------------------
		 $lb_valido2=$this->uf_cargar_cmp_md($this->ls_codemp,$as_procede,$as_comprobante,$as_fecconta);
		 if (($lb_valido2)&&($lb_valido))
		 {
			$ls_newfec2=$this->io_function->uf_convertirdatetobd($as_fecconta);
			$ls_sql="  DELETE FROM sigesp_cmp_md                 ".
                    "   WHERE codemp='".$this->ls_codemp."'      ".
                    "     AND procede='".$as_procede."'          ".
                    "     AND comprobante='".$as_comprobante."'  ".
	                "     AND fecha = '".$ls_newfec2."'          "; 
			   $lr_result2 =$this->io_sql_destino->execute($ls_sql);
			   if($lr_result2===false)
			   {
				  $this->is_msg_error="Error en Clase->uf_reversar_transferencia_comprobantes_md".
				                       $this->io_function->uf_convertirmsg($this->io_sql->message);
			   }
			   else  
			   { 
		     
					 $lb_valido =true; 
			   } 
		 }
		 return $lb_valido;
	}//fin uf_reversar_transferencia_comprobantes
//------------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------------
   function uf_transferir_comprobantes_md($as_codempdes,$as_comprobante,$as_fecha,$as_fecconta,$as_procede)
	{
		  $lb_existe_cmp=false;
		  $lb_valido=true;
		  $lb_resultado=true;
		  $ls_newfec=$this->io_function->uf_convertirdatetobd($as_fecha);
		  $lb_existe_cmp = $this->uf_select_comprobante($as_codempdes,$as_procede,$as_comprobante,$ls_newfec);
		  if (!$lb_existe_cmp)
		  {
			$lb_valido = $this->uf_procesar_cmp_md($as_codempdes,$as_comprobante,$ls_newfec,$as_procede);
			if($lb_valido)
			{
			 $this->io_sql_destino->commit();
			}
			else
			{
			 $this->io_sql_destino->rollback();
			}
		  }
	 return $lb_valido;
	}//fin de uf_transferir_comprobantes_md
	
	
	function uf_cerrar_presupuesto($as_codempdes,$ai_cerrar)
	{
		  $lb_valido=false;
		  $ls_sql    = " UPDATE sigesp_empresa set estciespg = ".$ai_cerrar.", estciespi = ".$ai_cerrar." where codemp = '".$as_codempdes."'";
		  $li_result = $this->io_sql_destino->execute($ls_sql);
		  if($li_result===false)
		  {
		    $this->is_msg_error="Error en Transferencia->uf_cerrar_presupuesto".
				                       $this->io_function->uf_convertirmsg($this->io_sql->message);
			$this->io_sql_destino->rollback();						   
		  }
		  else  
		  {  
		    $this->io_sql_destino->commit();
			$lb_valido = true;
		  }

	 return $lb_valido;
	}//fin de uf_cerrar_presupuesto
//------------------------------------------------------------------------------------------------------------------------------------

function uf_int_spg_insert_movimiento_ue($as_codemp,$as_procedencia,$as_comprobante,$as_fecha,$as_tipo,$as_fuente,$as_cod_prov,
										  $as_ced_ben,$estprog,$as_cuenta,$as_procede_doc,$as_documento,$as_descripcion,
										  $as_mensaje,$adec_monto,$as_sc_cuenta,$ab_spg_enlace_contable,$as_codban,$as_ctaban)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_int_spg_insert_movimiento_ue
		//		   Access: public 
		//       Argument: as_codemp // Código de Empresa
		//				   as_procedencia // Procedencia del Documento
		//				   as_comprobante // Número de Comprobante
		//				   as_fecha  // Fecha del Comprobante
		//				   as_tipo // Tipo
		//       		   as_fuente // Tipo Destino
		//       		   as_cod_prov // Código del Proveedor
		//       		   as_ced_ben // Cédula del Beneficiario
		//				   estprog // Estructura Programática 
		//       		   as_cuenta // cuenta
		//				   as_procede_doc // Procede del Documento
		//				   as_documento // Número del Documento
		//				   as_descripcion // Descripción del Movimiento
		//				   as_mensaje // Mensaje del Movimiento
		//				   adec_monto // Monto del Movimiento
		//				   as_sc_cuenta // Cuenta Contable del Movimiento
		//				   ab_spg_enlace_contable // Enlace Contable
		//       		   as_codban // Código de Banco
		//       		   as_ctaban // Cuenta de Banco
		//	  Description: Método que inserta un movimiento de gasto por medio de la integracion en lote proveniente de las Unidades ejecutoras
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno
		// Adaptado   Por: Ing. Arnaldo Suárez 				
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_denproc="";
		$ls_status="";
		$ls_denominacion="";
		$ls_SC_Cuenta="";
		$li_orden ="";
		$this->is_codemp=$as_codemp;
		$this->is_procedencia=$as_procedencia;
		$this->is_comprobante=$as_comprobante;
		$this->is_descripcion=$as_descripcion;
		$this->id_fecha=$as_fecha;
		$this->is_tipo=$as_tipo;
		$this->is_fuente=$as_fuente;
		$this->is_cod_prov=$as_cod_prov;
		$this->is_ced_ben=$as_ced_ben;
		$this->as_codban=$as_codban;
		$this->as_ctaban=$as_ctaban;
		$ls_comprobante=$this->is_comprobante;
		$ls_operacion=$this->io_int_spg->uf_operacion_mensaje_codigo($as_mensaje);
		if(empty($ls_operacion))
		{
			return false;
		}
		if(!$this->io_int_spg->uf_valida_procedencia($this->is_procedencia,$ls_denproc))
		{
			return false;
		}
		if(!$this->io_int_spg->io_fecha->uf_valida_fecha_periodo($as_fecha,$as_codemp))
		{
			$this->is_msg_error = "Fecha Invalida."	;
			$this->io_msg->message($this->is_msg_error);			   		  		  
			return false;
		}
		if($this->uf_spg_select_movimiento_ue($estprog,$as_cuenta,$as_procede_doc,$as_documento,$ls_operacion,$lo_monto_movimiento,
										   $li_orden))  
		{
			$this->is_msg_error = "El movimiento Presupuestario ya existe.";
			$this->io_msg->message($this->is_msg_error);			   		  		  		  
			return false; 	
		}
		$lb_valido = $this->uf_spg_comprobante_actualizar_ue(0,$adec_monto,"C");
		if ($lb_valido===true)
		{
				$lb_valido =$this->uf_insert_movimiento_spg_ue($estprog,$as_cuenta,$as_procede_doc,$as_documento,$ls_operacion,$as_descripcion,$adec_monto);
				if(($lb_valido)) 
				{
					$as_mensaje=strtoupper($as_mensaje); // devuelve cadena en MAYUSCULAS
					$li_pos_i=strpos($as_mensaje,"C"); 
					if (!($li_pos_i===false) and ($this->ib_spg_enlace_contable))
					{			      
						if ($this->ib_contabilizar)
						{
							$lb_valido=$this->uf_spg_integracion_scg_int($as_codemp,$as_sc_cuenta,$as_procede_doc,$as_documento,$as_descripcion,$adec_monto,$as_codban,$as_ctaban);
						}
					} 
				}
		}
	   return $lb_valido;
	}  // end function uf_int_spg_insert_movimiento_ue
	//-----------------------------------------------------------------------------------------------------------------------------------
    
	
	function uf_spg_select_movimiento_ue($estprog,$as_cuenta,$as_procede_doc,$as_documento,$as_operacion,&$adec_monto,&$ai_orden)
	{
  	    $lb_existe=false;
		$ls_cuenta  = "";$lb_existe=false;$ldec_monto=0;$li_orden=0;
		$ls_codemp  =  $this->is_codemp ;
		$ls_procedencia = $as_procede_doc;
		$ls_comprobante = $as_documento;
		$ls_fecha = $this->io_function->uf_convertirdatetobd($this->id_fecha);
	    $ls_sql = " SELECT spg_cuenta,monto,orden ".
			      " FROM  spg_dt_cmp_int ".		
			      " WHERE codemp='".$ls_codemp."' AND codestpro1 ='".$estprog[0]."'  AND ".
				  "       codestpro2 ='".$estprog[1]."' AND codestpro3 ='".$estprog[2]."'   AND  ". 
			      "       codestpro4 ='".$estprog[3]."' AND codestpro5 ='".$estprog[4]."'  AND   ".
				  "       estcla='".$estprog[5]."' AND procede='".$this->is_procedencia."'   AND  ".
				  "       comprobante='".$this->is_comprobante."' AND  fecha='".$ls_fecha."' AND ".
			      "       procede_doc='".$as_procede_doc."' AND documento ='".$as_documento."' AND ".
			      "       spg_cuenta ='".$as_cuenta."'  AND  operacion='".$as_operacion."' "; 
		$rs_data=$this->io_sql_destino->select($ls_sql);
		if($rs_data===false)
		{
   	 	    $this->is_msg_error="Error de SQL método->uf_spg_select_movimiento_ue class->transferencia ::".$this->io_function->uf_convertirmsg($this->io_sql->message);
		    return false;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_cuenta=$row["spg_cuenta"];
				$ldec_monto=$row["monto"];
				$adec_monto=$ldec_monto;
				$li_orden=$row["orden"];
				$ai_orden=$li_orden;
				$lb_existe=true;
			}				
		}
		$this->io_sql->free_result($rs_data);				
		return $lb_existe;
	} // end function uf_select_movimientos
/**********************************************************************************************************************************/


/**********************************************************************************************************************************/
   function uf_insert_movimiento_spg_ue($estprog,$as_cuenta,$as_procede_doc,$as_documento,$as_operacion,$as_descripcion,$ad_monto_actual)
   {
	   ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   //	 Function:  uf_insert_movimiento_spg_ue
	   //	Arguments:  estprog->estructura programatica del gasto; as_cuenta->cuenta gasto ; as_procede_doc procedenca del documento
	   //               as_documento  n° del documento; as_operacion  operacion de gasto; as_descripcion	 descripcion del movimiento  
	   //               adec_monto   monto del movimiento 
	   //	  Returns:  lb_valido -> variable boolean
	   // Description:  Este método inserta un movimiento presupuestario en las tablas de detalle comprobante spg proveniente de la Unidad Ejecutora.
	   ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
	   $lb_valido = true;
	   $ls_fecha  = $this->io_function->uf_convertirdatetobd($this->id_fecha);
	   $li_orden=$this->uf_spg_obtener_orden_movimiento_ue();
	   $ls_sql = " INSERT INTO spg_dt_cmp_int (codemp,procede,comprobante,fecha,codestpro1,codestpro2,codestpro3, ".
	             "                           codestpro4,codestpro5,spg_cuenta,procede_doc,documento,operacion,  ".
				 "                           descripcion,monto,orden,estcla) ".
			     " VALUES('".$this->is_codemp."','".$this->is_procedencia."','".$this->is_comprobante."', ".
				 "        '".$ls_fecha."','".$estprog[0]."','".$estprog[1]."','".$estprog[2]."','".$estprog[3]."', ".
				 "        '".$estprog[4]."','".$as_cuenta."','".$as_procede_doc."','".$as_documento."', ".
				 "        '".$as_operacion."','".$as_descripcion."','".$ad_monto_actual."',".$li_orden.", ".
				 "        '".$estprog[5]."')";		  
	   $li_rows=$this->io_sql_destino->execute($ls_sql);
	   if($li_rows===false)
	   {
		  $lb_valido=false;
		  $this->is_msg_error = "Error de SQL método->uf_spg_insert_movimiento_int class->transferencia ::".$this->io_function->uf_convertirmsg($this->io_sql->message);
	   }
	   return $lb_valido;
	}// end function uf_spg_insert_movimiento_gasto
/**********************************************************************************************************************************/

//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spg_comprobante_actualizar_ue($ai_montoanterior, $ai_montoactual, $ls_tipocomp)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spg_comprobante_actualizar_ue
		//		   Access: public 
		//       Argument: ai_montoanterior // Monto Anterior del Movimiento
		//				   ai_montoactual // Monto Actual del Movimiento
		//				   ls_tipocomp // Tipo de Comprobante		
		//	  Description: Este método actualiza  el comprobante sigesp_cmp_int, para los generados en las Unidades Ejecutoras
		//	      Returns: retorna valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		// Adaptado   Por: Ing. Arnaldo Suárez 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false; 
		$li_tipocomp=0;
		if($ls_tipocomp=="C")
		{
			$li_tipocomp=1;
		}
		if($ls_tipocomp=="P")
		{
			$li_tipocomp=2;
		}	
		if ($this->uf_spg_comprobante_select_ue())
		{
			$lb_valido = $this->uf_spg_comprobante_update_ue($ai_montoanterior, $ai_montoactual);
		}
		else 
		{ 
			$lb_valido = $this->uf_spg_comprobante_insert_ue($ai_montoactual, $li_tipocomp);  
		}
		return $lb_valido;
    } // end function uf_spg_comprobante_actualizar_ue
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spg_comprobante_select_ue()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spg_comprobante_select_ue
		//		   Access: public 
		//       Argument: 	
		//	  Description: Este método verifica si existe el comprobante SIGESP_cmp_int
		//	      Returns: retorna valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007ç
		// Adaptado   Por: Ing. Arnaldo Suárez
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		$ld_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
		$ls_sql="SELECT * ".
				"  FROM sigesp_cmp_int ".
				" WHERE procede='".$this->is_procedencia."' ".
				"   AND comprobante='".$this->is_comprobante."' ".
				"   AND fecha='".$ld_fecha."' ".
				"   AND codban='".$this->as_codban."' ".
				"   AND ctaban='".$this->as_ctaban."' ";
		$rs_data = $this->io_sql_destino->select($ls_sql);
	    if($rs_data===false)
	    {
			$this->is_msg_error="CLASE->sigesp_int_spg MÉTODO->uf_spg_select_movimiento ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
	    }
	    else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true;
			}
			$this->io_sql->free_result($rs_data);		
		} 
		return $lb_existe;
	}  // end function uf_spg_comprobante_select
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spg_comprobante_update_ue($li_montoanterior, $li_montoactual)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spg_comprobante_update
		//		   Access: public 
		//       Argument: li_montoanterior // Monto anterior
		//				   li_montoactual // Monto Actual
		//	  Description: Este método actualiza el monto si existe el comprobante SIGESP_cmp_int
		//	      Returns: retorna valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		// Adaptado  Por: Ing. Arnaldo Suárez
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total=(-$li_montoanterior+$li_montoactual);
		$ld_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
		$ls_sql="UPDATE sigesp_cmp_int ".
				"   SET total = total + '".$li_total."'  ".
				" WHERE procede='".$this->is_procedencia."' ".
				"   AND comprobante= '".$this->is_comprobante."' ".
				"   AND fecha='".$ld_fecha."' ".
				"   AND codban='".$this->as_codban."' ".
				"   AND ctaban='".$this->as_ctaban."' ";
		$li_exec=$this->io_sql_destino->execute($ls_sql);
		if($li_exec===false)
		{
			$this->is_msg_error="CLASE->sigesp_int_spg MÉTODO->uf_spg_comprobante_update_ue ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
		}	   
		return $lb_valido;
	}  // end function uf_spg_comprobante_update
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spg_comprobante_insert_ue($ai_monto,$ai_tipocomp)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spg_comprobante_insert
		//		   Access: public 
		//       Argument: ai_monto // Monto
		//				   ai_tipocomp // Tipo de Comprobante
		//	  Description: Este método inserta en el compronate de gasto
		//	      Returns: retorna valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_codemp=$this->is_codemp;
		$ls_procede=$this->is_procedencia;
		$ls_comprobante=$this->is_comprobante;
		$ls_descripcion=$this->is_descripcion;
		$ls_tipo=$this->is_tipo;
		$ls_codpro=$this->is_cod_prov;
		$ls_cedbene=$this->is_ced_ben;		
		$ld_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
		$ls_sql="INSERT INTO sigesp_cmp_int(codemp,procede,comprobante,fecha,descripcion,total,tipo_destino,cod_pro,ced_bene,".
				" tipo_comp,codban,ctaban)  VALUES ('".$ls_codemp."', '".$ls_procede."', '".$ls_comprobante."', '".$ld_fecha."', ".
			    "'".$ls_descripcion."', '".$ai_monto."', '".$ls_tipo."', '".$ls_codpro."', '".$ls_cedbene."', '".$ai_tipocomp."', ".
				"'".$this->as_codban."', '".$this->as_ctaban."' )";
		$li_exec=$this->io_sql_destino->execute($ls_sql);                                                                                                                                                                                          
		if($li_exec===false)
		{
			$this->is_msg_error="CLASE->sigesp_c_transferencia MÉTODO->uf_spg_comprobante_insert_ue ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
		}
		return $lb_valido;
	}  // end function uf_spg_comprobante_insert_ue
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	function uf_spg_obtener_orden_movimiento_ue()
	{   
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spg_obtener_orden_movimiento
		//		   Access: public 
		//       Argument: 
		//	  Description: Retorna el número de orden del movimiento de gasto spg
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_sql="SELECT count(*) as orden  ".
				"  FROM spg_dt_cmp_int".
				" WHERE codemp='".$this->is_codemp."' ".
				"   AND procede='".$this->is_procedencia."' ".
				"   AND comprobante='".$this->is_comprobante."'".
				"   AND fecha='".$this->id_fecha."' ".
				"   AND codban='".$this->as_codban."' ".
				"   AND ctaban='".$this->as_ctaban."' ";
		$rs_data=$this->io_sql_destino->select($ls_sql);
	    if($rs_data===false)
	    {
			$this->is_msg_error="CLASE->sigesp_int_spg MÉTODO->uf_spg_obtener_orden_movimiento_ue ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
	    }
	    else
		{
			if($row=$this->io_sql_destino->fetch_row($rs_data))
			{
				$li_orden=$row["orden"];
			} 
			$this->io_sql_destino->free_result($rs_data);		
		}  
	   return $li_orden;
    } // end function uf_spg_obtener_orden_movimiento
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	function uf_spg_integracion_scg_int($as_codemp, $as_scgcuenta, $as_procede_doc, $as_documento, $as_descripcion, $adec_monto_actual,$as_codban,$as_ctaban)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_spg_integracion_scg_int
		//		   Access: public 
		//       Argument: as_codemp //  Código de Empresa
		//				   as_scgcuenta // Cuenta 
		//				   as_procede_doc // Procede del Documento
		//				   as_documento // Número del Documento
		//				   as_descripcion // Descripción del Documento
		//				   adec_monto_actual // Monto Actual
		//	  Description: Este método generar un asiento contable generado por intercompañia
		//	      Returns: boolean si es valido ó no
		//	   Creado Por: Ing. wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		//   Adaptado Por: Ing. Arnaldo Suárez
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_debhab="";
		$ls_status="";
		$ls_denominacion="";
		$ls_mensaje_error="";
		$ldec_monto=0;$li_orden=0;
		if($adec_monto_actual > 0)
		{
			$ls_debhab = "D";
		}
		else
		{
			$ls_debhab = "H";
		}
		if(!$this->io_int_scg->uf_scg_select_cuenta($as_codemp, $as_scgcuenta, &$ls_status, $ls_denominacion))
		{
			$this->io_msg->message("La cuenta contable [". trim($as_scgcuenta) ."] no existe.");
			return false;
		} 
		if($ls_status!="C")
		{ 
			$this->io_msg->message("La cuenta contable [". trim($as_scgcuenta) ."] no es de movimiento.");
			return false;
		} 
		$this->io_int_scg->is_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
		$this->io_int_scg->is_codemp=$as_codemp;
		$this->io_int_scg->is_procedencia=$this->is_procedencia;
		$this->io_int_scg->is_comprobante=$this->is_comprobante;
		$this->io_int_scg->as_codban=$as_codban;
		$this->io_int_scg->as_ctaban=$as_ctaban;
		$this->as_codban=$as_codban;
		$this->as_ctaban=$as_ctaban;
		$lb_valproc = false;
		$lb_valido = $this->uf_scg_load_det_int($as_procede_doc, $as_documento,$adec_monto_actual, $lb_valproc, $rs_data);
		if (($lb_valido)&&($lb_valproc))
		   {
		     while($row=$this->io_sql->fetch_row($rs_data))
		          {
				    $ls_scgcuenta      = trim($row["sc_cuenta"]); 
				    $ls_debhab         = $row["debhab"];
				    $adec_monto_actual = $row["monto"];
		   	        if (!$this->uf_scg_select_movimiento_int($ls_scgcuenta, $as_procede_doc, $as_documento, $ls_debhab, $ldec_monto, $li_orden))
					   {
						 $adec_monto_actual=abs($adec_monto_actual);
						 $lb_valido = $this->io_int_scg->uf_scg_procesar_insert_movimiento($as_codemp,$this->is_procedencia,$this->is_comprobante,$this->id_fecha,$this->is_tipo,$this->is_cod_prov,$this->is_ced_ben,$ls_scgcuenta, $as_procede_doc, $as_documento, $ls_debhab, $as_descripcion, 0, $adec_monto_actual,$as_codban,$as_ctaban);
					   }
		          }
		 
	       }
		elseif(!$lb_valproc)
		   {
		     $lb_valido = true;
		   }
																			 
		return $lb_valido;
	} // end function uf_spg_integracion_scg
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	
	function uf_scg_select_movimiento_int($as_cuenta,$as_procede_doc,$as_documento,$as_debhab,&$adec_monto,&$ai_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scg_select_movimiento_int
		//		   Access: public 
		//       Argument: as_cuenta // cuenta
		//       		   as_procede_doc // Procede del movimiento
		//       		   as_documento // Número del Documento
		//       		   as_debhab // Operación si es debe ó haber
		//       		   adec_monto // Monto del Movimiento
		//       		   ai_orden // Orden al Insertar los registros
		//	  Description: Este método verifica si existe o no el movimiento contable intercompañia
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		//   Adaptado Por: Ing. Arnaldo Suárez
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_existe=false;
		$ld_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
	    $ls_sql="SELECT monto, orden ".
		        "  FROM scg_dt_cmp ".
		        " WHERE codemp='".$this->is_codemp."' ".
				"   AND procede='".$this->is_procedencia."' ".
				"   AND comprobante='".$this->is_comprobante."' ".
				"   AND fecha='".$ld_fecha."' ".
				"   AND codban='".$this->as_codban."' ".
				"   AND ctaban='".$this->as_ctaban."' ".
				"   AND procede_doc='".$as_procede_doc."' ".
				"   AND documento ='".$as_documento."' ".
				"   AND sc_cuenta='".$as_cuenta."' ".
				"   AND debhab='".$as_debhab."'";			
		$rs_mov=$this->io_sql_destino->select($ls_sql);
		if($rs_mov===false)
		{
			$this->is_msg_error="CLASE->sigesp_spc_c_transferencia MÉTODO->uf_scg_select_movimiento_int ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql_destino->fetch_row($rs_mov))
			{
				$lb_existe=true;
				$adec_monto=$row["monto"];
				$ai_orden=$row["orden"];
			}
			else
			{
				$lb_existe=false;
			}
			$this->io_sql_destino->free_result($rs_mov);	
		}
		return $lb_existe;
	} // end function uf_scg_select_movimiento_int
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	
	function uf_scg_load_det_int($as_procede_doc, $as_documento, $adec_monto, $lb_procval, &$rs_mov)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scg_load_det_int
		//		   Access: public 
		//       Argument: $rs_mov datos
		//	  Description: Este método que devuelve la informacion generada en las tablas de asientos intercompañia
		//	      Returns: Data
	    //    Creado  Por: Ing. Arnaldo Suárez
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=false;
		$ld_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
		$ls_tabla = "";
		$ls_codestpreint = "";
		$ls_estclaint    = "";
		$tabla_codcmp    = "";
		if ($this->uf_get_codestpreint_destino($this->bddestino,$ls_codestpreint,$ls_estclaint))
		{
			switch(trim($this->is_procedencia))
			{
			 case 'SNOCNO' : $ls_tabla     = "sno_dt_scg_int";
			                 $tabla_codcmp = "codcom";
							 $lb_procval = true;
							 break;
							 
			 case 'SAFCDP' : $ls_tabla     = "saf_depreciacion_int";
			                 $tabla_codcmp = "comprobante";
							 $lb_procval = true;
							 break;	
							 
			 case 'SIVCND' : $ls_tabla     = "siv_dt_scg_int";
			 				 $tabla_codcmp = "codcmp";
							 $lb_procval = true;
							 break;
							 
			 case 'CXPSOP' : $ls_tabla     = "cxp_scg_inter";
			 				 $tabla_codcmp = "comprobante";
							 $lb_procval = true;
							 break;				 
			 default :
			                 $lb_procval = false;				 				 				 			 
			}
			if ((!empty($ls_tabla))&&(!empty($tabla_codcmp)))
			{
				$ls_sql="SELECT  distinct ".$ls_tabla.".sc_cuenta, ".$ls_tabla.".debhab, ".$ls_tabla.".monto ".
						"  FROM  ".$ls_tabla.",scg_dt_cmp, spg_cuentas, spg_ep1    ".
						" WHERE  ".$ls_tabla.".codemp='".$this->is_codemp."'       ".
						"   AND scg_dt_cmp.procede='".$this->is_procedencia."'     ".
						"   AND scg_dt_cmp.comprobante=".$ls_tabla.".".$tabla_codcmp." ".
						"   AND scg_dt_cmp.fecha='".$ld_fecha."'                   ".
						"   AND scg_dt_cmp.codban='".$this->as_codban."'           ".
						"   AND scg_dt_cmp.ctaban='".$this->as_ctaban."'           ".
						"   AND scg_dt_cmp.procede_doc='".$as_procede_doc."'       ".
						"   AND scg_dt_cmp.documento ='".$as_documento."'          ".
						"   AND spg_cuentas.codemp         = spg_ep1.codemp        ".
						"   AND spg_cuentas.codestpro1     = spg_ep1.codestpro1    ".
						"   AND spg_cuentas.estcla         = spg_ep1.estcla        ".
						"   AND spg_ep1.estint = 1                                 ".
						"   AND (".$ls_tabla.".sc_cuenta  = spg_ep1.sc_cuenta OR   ".
						"   ".$ls_tabla.".sc_cuenta   = spg_cuentas.sc_cuenta)     ".
						"   AND spg_ep1.codestpro1 = '".$ls_codestpreint."'  ".
						"   AND spg_ep1.estcla = '".$ls_estclaint."'  ";
						//"   AND  ".$ls_tabla.".monto = ".$adec_monto."             ";
				$rs_mov=$this->io_sql->select($ls_sql);
				if($rs_mov===false)
				{
					$this->is_msg_error="CLASE->sigesp_spc_c_transferencia MÉTODO->uf_scg_load_det_int ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
				}
				else
				{  
					$li_numrows=$this->io_sql->num_rows($rs_mov);
					if($li_numrows > 0)
					{
						$lb_valido=true;
					}
				}
		 }		
	  }
		return $lb_valido;
	} // end function uf_scg_select_movimiento_int
	//-----------------------------------------------------------------------------------------------------------------------------------
	
 function uf_get_codestpreint_destino($as_bddestino,&$as_codestproint,&$as_estclaint)
 {
  $lb_valido = true;
  $ls_sql = "SELECT codestpro1, estcla FROM sigesp_consolidacion ".
             " WHERE nombasdat = '".trim($as_bddestino)."'";
  $rs_data=$this->io_sql->select($ls_sql);
  if($rs_data===false)
  {
   $lb_valido = false;
   $this->is_msg_error="CLASE->sigesp_spc_c_transferencia MÉTODO->uf_get_codestpreint_destino ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
  }
  else
  {  
   $li_numrows=$this->io_sql->num_rows($rs_data);
   if($li_numrows > 0)
   {
    if($row=$this->io_sql_destino->fetch_row($rs_data))
    {
     $as_codestproint = $row["codestpro1"];
	 $as_estclaint    = $row["estcla"];
     $lb_valido=true;
    }
   }
  } 
  return $lb_valido;	
}

function uf_update_estatus($as_codemp,$as_comprobante,$as_fecha,$as_procede,$as_codban,$as_ctaban)
{
//////////////////////////////////////////////////////////////////////////////////////////////////////////
//	 Function:  uf_update_estatus
//	   Access:  public
//	Arguments:  $as_procedencia->procedencia,$as_comprobante->comprobante,$as_fecha
//	  Returns:	booleano lb_existe
//Description:  actuliza el estatus del comprobante
/////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido = false;		 
	 $ls_fecha=$this->io_function->uf_convertirdatetobd($as_fecha);
	 $ls_sql="  UPDATE  sigesp_cmp                        ".
			 "     SET  esttrfcmp = 1                     ".
			 "   WHERE codemp='".$as_codemp."'            ".
			 "     AND procede='".$as_procede."'          ".
			 "     AND comprobante='".$as_comprobante."'  ".
			 "     AND codban = '".$as_codban."'          ".
			 "     AND ctaban = '".$as_ctaban."'          ".
			 "     AND fecha = '".$ls_fecha."'            "; 
		   $rs_data = $this->io_sql_destino->execute($ls_sql);
		   if($rs_data===false)
		   {
			  $this->is_msg_error="Error en Clase->uf_update_estatus".
								   $this->io_function->uf_convertirmsg($this->io_sql->message);
		   }
		   else  
		   { 			  
			  $lb_valido =true;				 
		   }		
	 return $lb_valido;
}//fin uf_update_estatus

function uf_cuenta_existe($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_estcla,$as_spg_cuenta)
{
//////////////////////////////////////////////////////////////////////////////////////////////////////////
//	 Function:  uf_update_estatus
//	   Access:  public
//	Arguments:  $as_procedencia->procedencia,$as_comprobante->comprobante,$as_fecha
//	  Returns:	booleano lb_existe
//Description:  actuliza el estatus del comprobante
/////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido = false;		 
	 $ls_sql="  SELECT  spg_cuenta                        ".
			 "     FROM  spg_cuentas                      ".
			 " WHERE codemp='".$as_codemp."' 			  ".
				"	AND codestpro1 = '".$as_codestpro1."' ".
				"   AND codestpro2 = '".$as_codestpro2."' ".
				"   AND codestpro3 = '".$as_codestpro3."' ".
				"   AND codestpro4 = '".$as_codestpro4."' ".
				"   AND codestpro5 = '".$as_codestpro5."' ".
				"   AND spg_cuenta = '".$as_spg_cuenta."' " .
				"   AND estcla     = '".$as_estcla."' ";
		   $rs_data = $this->io_sql_destino->select($ls_sql);
		   if($rs_data===false)
		   {
			  $this->is_msg_error="Error en Clase->uf_cuenta_existe".
								   $this->io_function->uf_convertirmsg($this->io_sql->message);
		   }
		   elseif ($row=$this->io_sql_destino->fetch_row($rs_data))
			{
				if(trim($row["spg_cuenta"]) != "")
				{
				 $lb_valido=true;
				}
			}	
	 return $lb_valido;
}//fin uf_cuenta_existe

}// Fin de la Clase
?>