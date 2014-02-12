<?php
 ////////////////////////////////////////////////////////////////////////////////////////////////////////
 //       Class : class_sigesp_sno_integracion_php                                                     //    
 // Description : Esta clase tiene todos los metodos necesario para el manejo de la rutina integradora //
 //               de las nominas y aportes															   //
 ////////////////////////////////////////////////////////////////////////////////////////////////////////
class class_sigesp_siv_integracion
{
	//Instancia de la clase funciones.
    var $is_msg_error;
	var $dts_empresa; 
	var $obj="";
	var $io_sql;
	var $io_siginc;
	var $io_conect;
	var $io_function;	
    var $io_sigesp_int;
	var $io_sigesp_int_spg;
	var $io_sigesp_int_scg;	
	var $io_fecha;
	var $io_msg;
	var $is_codemp="";
	var $is_procede="";
	var $idt_fecha;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function class_sigesp_siv_integracion()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: class_sigesp_sno_integracion
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 25/10/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/class_sql.php");  
		require_once("../shared/class_folder/class_datastore.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/class_sigesp_int.php");
		require_once("../shared/class_folder/class_sigesp_int_int.php");
		require_once("../shared/class_folder/class_sigesp_int_spg.php");
		require_once("../shared/class_folder/class_sigesp_int_scg.php");
		require_once("../shared/class_folder/class_sigesp_int_spi.php");
		require_once("../shared/class_folder/class_fecha.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("class_funciones_mis.php");
	    $this->io_fun_mis=new class_funciones_mis();
	    $this->io_fecha=new class_fecha();
        $this->io_sigesp_int=new class_sigesp_int_int();
		$this->io_function=new class_funciones() ;
		$this->io_siginc=new sigesp_include();
		$this->io_connect=$this->io_siginc->uf_conectar();
		$this->io_sql=new class_sql($this->io_connect);		
		$this->obj=new class_datastore();
		$this->dts_empresa=$_SESSION["la_empresa"];
		$this->is_codemp=$this->dts_empresa["codemp"];		
		$this->dts_inventario=new class_datastore();
		$this->io_msg=new class_mensajes();		
		$this->io_sigesp_int_spg=new class_sigesp_int_spg();
		$this->io_sigesp_int_scg=new class_sigesp_int_scg();		
		$this->io_seguridad=new sigesp_c_seguridad();		
		$this->as_procede="";
		$this->as_comprobante="";
		$this->ad_fecha="";
		$this->as_codban="";
		$this->as_ctaban="";
	}// end function class_sigesp_sno_integracion
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destroy_objects()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destroy_objects
		//		   Access: public 
		//	  Description: Destructor de los objectos de la Clase
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 25/10/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if( is_object($this->io_fecha) ) { unset($this->io_fecha);  }
		if( is_object($this->io_sigesp_int) ) { unset($this->io_sigesp_int);  }
		if( is_object($this->io_function) ) { unset($this->io_function);  }
		if( is_object($this->io_siginc) ) { unset($this->io_siginc);  }
		if( is_object($this->io_connect) ) { unset($this->io_connect);  }
		if( is_object($this->io_sql) ) { unset($this->io_sql);  }	   
		if( is_object($this->obj) ) { unset($this->obj);  }	   
		if( is_object($this->dts_empresa) ) { unset($this->dts_empresa);  }	   
		if( is_object($this->io_msg) ) { unset($this->io_msg);  }	   
		if( is_object($this->io_sigesp_int_spg) ) { unset($this->io_sigesp_int_spg);  }	   
		if( is_object($this->io_sigesp_int_scg) ) { unset($this->io_sigesp_int_scg);  }	   
		if( is_object($this->io_seguridad) ) { unset($this->io_seguridad);  }	   
	}// end function uf_destroy_objects
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_contabilizacion_siv($as_comprobante,$adt_fecha,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_contabilizacion_siv
		//		   Access: public (sigesp_mis_p_contabiliza_sno.php)
		//	    Arguments: as_comprobante  // Código de Comprobante
		//				   adt_fecha  // Fecha de contabilización
		//				   aa_seguridad  // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Funcion que procesa la contabilización dado un comprobante
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 25/10/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
	    if(!$this->uf_obtener_data_comprobante($as_comprobante))
		{
			return false;
		}
		$lb_valido=$this->uf_procesar_contabilizacion_despachos($as_comprobante,$adt_fecha,$aa_seguridad);
        return $lb_valido;		
    } // end function uf_procesar_contabilizacion_siv
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_data_comprobante($as_comprobante)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_data_comprobante
		//		   Access: private
		//	    Arguments: as_comprobante  // Código de Comprobante
		//	      Returns: lb_existe True si existe el comprobante
		//	  Description: Este metodo que obtiene la información agrupada en un registro con la información 
		//                  de cabecera de proveedor,beneficiario tipo destino y descripcion 
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 25/10/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_existe = false;		
		$ls_sql="SELECT siv_despacho.codemp, siv_despacho.numorddes, MAX(obsdes) as obsdes,MAX(fecdes) as fecdes,".
				"       '' as operacion,MAX(siv_dt_scg.estint) as estint". 
				"  FROM siv_despacho,siv_dt_scg ".
				" WHERE siv_despacho.codemp = '".$this->is_codemp."' ".
				"   AND siv_dt_scg.codcmp='".$as_comprobante."'".
			//	"   AND siv_dt_scg.estint = 0 ".
				"   AND siv_despacho.codemp=siv_dt_scg.codemp ".
				"   AND siv_despacho.numorddes=siv_dt_scg.codcmp ".
				" GROUP BY siv_despacho.codemp, siv_despacho.numorddes".
			    " ORDER BY numorddes  ";	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_msg->message("CLASE->Integración SNO MÉTODO->uf_obtener_data_comprobante ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{                 
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true;			
				$this->dts_inventario->data=$this->io_sql->obtener_datos($rs_data);
			}
			else
			{
				$this->io_msg->message("ERROR-> El Comprobante ".$as_comprobante." no existe.");			
			}
		}
		$this->io_sql->free_result($rs_data);
		return $lb_existe;
	} // end function uf_obtener_data_comprobante
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_procesar_contabilizacion_despachos($as_comprobante,$adt_fecha,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_contabilizacion_despachos
		//		   Access: private
		//	    Arguments: as_comprobante  // Código de Comprobante
		//				   adt_fecha  // Fecha de contabilización
		//				   aa_seguridad  // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Funcion que procesa la contabilización de una Nómina
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 25/10/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $ls_procede="SIVCND";
		$this->is_procede="SIVCND";
		$ls_comprobante=$this->io_sigesp_int->uf_fill_comprobante(trim($as_comprobante));
		$ls_codigo_destino="----------";
		$ls_descripcion=$this->dts_inventario->getValue("obsdes",1);	
        $ls_tipo_destino="-";			
        $ls_mensaje=$this->dts_inventario->getValue("operacion",1);
		$li_estatus=$this->dts_inventario->getValue("estint",1);
		if($li_estatus==1) 
		{
		   $this->io_msg->message("El despacho ya esta contabilizado.");
		   return false;
		}
		$ls_fecdes=$this->dts_inventario->getValue("fecdes",1);
		$ls_fecdes=$this->io_function->uf_convertirdatetobd($ls_fecdes);
		$adt_fecha=$this->io_function->uf_convertirdatetobd($adt_fecha);
		if(!$this->io_fecha->uf_comparar_fecha($ls_fecdes,$adt_fecha))
		{
			$this->io_msg->message("ERROR-> La Fecha de Contabilizacion es menor que la fecha del despacho ".$as_comprobante);
			return false;
		}
		// Creo la cabecera del Comprobante
		$ls_codban="---";
		$ls_ctaban="-------------------------";
		$li_tipo_comp=1; // comprobante Normal
		$this->as_procede=$ls_procede;
		$this->as_comprobante=$ls_comprobante;
		$this->ad_fecha=$adt_fecha;
		$this->as_codban=$ls_codban;
		$this->as_ctaban=$ls_ctaban;
		$lb_valido=$this->io_sigesp_int->uf_int_init($this->is_codemp,$ls_procede,$ls_comprobante,$adt_fecha,$ls_descripcion,
													 $ls_tipo_destino,$ls_codigo_destino,false,$ls_codban,$ls_ctaban,$li_tipo_comp);
		$this->io_sigesp_int->uf_int_config(false,false);
		if (!$lb_valido)
		{   
           $this->io_msg->message($this->io_sigesp_int->is_msg_error); 
		   return false;		   		   
		}
		// inicia transacción SQL
		$this->io_sigesp_int->uf_int_init_transaction_begin();
		if ($lb_valido)
        {	// Se procesan los detalles de Contabilidad
			$lb_valido = $this->uf_procesar_detalles_contables($as_comprobante,""); 
			if ($lb_valido)
			{	// Se inserta el comprobante con sus detalles contables y presupuestarios
				$lb_valido = $this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad); 
				if (!$lb_valido) 
				{ 
					if (!empty($this->io_sigesp_int->is_msg_error))
					{
						$this->io_msg->message("ERROR-> ".$this->io_sigesp_int->is_msg_error);
					}	
				}
			}
			if($lb_valido)
			{	// Se Actualiza el estatus de la nómina que está contabilizada
				$lb_valido=$this->uf_update_estatus_despacho($as_comprobante,1);
			}
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_update_fecha_contabilizado_siv($this->is_codemp,$ls_comprobante,
															    $adt_fecha,'1900-01-01');
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Contabilizó el Despacho <b>".$ls_comprobante."</b>";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		// Se Finaliza la transacción con Commit ó Rollback de acuerdo al $lb_valido
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);		
		return  $lb_valido;
	} // end function uf_procesar_contabilizacion_despachos
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_estatus_despacho($as_comprobante,$ai_estatus)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_estatus_despacho
		//		   Access: private
		//	    Arguments: as_comprobante  // Código del comprobante 
		//				   ai_estatus  // estatus si es 0 ó 1
		//	      Returns: lb_valido True si se actualizó correctamente
		//	  Description: Método que actualiza el estatus del despacho en contabilizado o no 
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 25/10/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;	
		$ls_sql="UPDATE siv_dt_scg".
				"   SET estint=".$ai_estatus.
				" WHERE codemp='".$this->is_codemp."' ".
				"   AND codcmp='".$as_comprobante."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
           	$this->io_msg->message("CLASE->Integración SNO MÉTODO->uf_update_estatus_despacho ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_update_estatus_despacho
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_reversar_contabilizacion_despacho($as_comprobante,$ad_fechaconta,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reversar_contabilizacion_despacho
		//		   Access: public 
		//	    Arguments: as_comprobante  // Código de Comprobante
		//				   ad_fechaconta  // Fecha en que fue contabilizado el Documento
		//				   aa_seguridad  // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el reverso correctamente
		//	  Description: Método que reversa la contabilizacion del despacho
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 25/10/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->dts_inventario->resetds("numorddes");		
	    if(!$this->uf_obtener_data_comprobante($as_comprobante))
		{
			return false ;
		}		
		$lb_valido=$this->uf_reversar_contabilizacion($as_comprobante,$ad_fechaconta,$aa_seguridad);
        return $lb_valido;		
    } // end function uf_reversar_contabilizacion_nomina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_reversar_contabilizacion($as_comprobante,$ad_fechaconta,$aa_seguridad)	
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reversar_contabilizacion
		//		   Access: private
		//	    Arguments: as_comprobante  // Código de Comprobante
		//				   ad_fechaconta  // Fecha en que fue contabilizado el Documento
		//				   aa_seguridad  // Arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el reverso correctamente
		//	  Description: Este metodo reversa contablemente y presupuestariamente una nómina contabilizada
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 25/10/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
   	    $ldt_fecha=$ad_fechaconta;
        $ls_codemp=$this->is_codemp;
        $ls_procede="SIVCND";
        $ls_comprobante=$this->io_sigesp_int->uf_fill_comprobante(trim($as_comprobante));
		$ls_cod_pro="----------";	
		$ls_ced_bene="----------";	
        $ls_tipo_destino="-";			
		$ls_codban="---";
		$ls_ctaban="-------------------------";
		// Buscamos el comprobante a reversar						
	    $lb_valido = $this->io_sigesp_int->uf_obtener_comprobante($ls_codemp,$ls_procede,$ls_comprobante,$ldt_fecha,$ls_codban,
																  $ls_ctaban,$ls_tipo_destino,$ls_ced_bene,$ls_cod_pro);
		if (!$lb_valido) 
		{ 
			$this->io_msg->message("ERROR-> No existe el comprobante Nº ".$ls_comprobante."-".$ls_procede.".");
			return false;
		}
		$lb_check_close=false;
		// Creamos la cabecera del comprobante y validamos la información
		$lb_valido = $this->io_sigesp_int->uf_init_delete($ls_codemp,$ls_procede,$ls_comprobante,$ldt_fecha,$ls_tipo_destino,
														  $ls_ced_bene,$ls_cod_pro,$lb_check_close,$ls_codban,$ls_ctaban);
		if(!$lb_valido)	
		{ 
 		   $this->io_msg->message("".$this->io_sigesp_int->is_msg_error);
		   return false; 
		}
		// Iniciamos la transacción en la BD
        $this->io_sigesp_int->uf_int_init_transaction_begin();
	    if($lb_valido) 
		{// Si se hizo nota de Débito se Reversa 
		   $lb_valido=$this->uf_update_estatus_despacho($as_comprobante,0); 
	    } 
		if($lb_valido)
		{	// Reversamos los detalles y el comprobante
			$lb_valido = $this->io_sigesp_int->uf_init_end_transaccion_integracion($aa_seguridad); 
			if(!$lb_valido)
			{
				$this->io_msg->message(" ERROR-> ".$this->io_sigesp_int->is_msg_error);
			}		   
		}
		if($lb_valido)
		{						
			$lb_valido=$this->uf_update_fecha_contabilizado_siv($this->is_codemp,$ls_comprobante,'1900-01-01','1900-01-01');
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reverso Contabilización del despacho <b>".$ls_comprobante."</b>";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		// Finalizamos la transacción en la base de datos
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		return  $lb_valido;
	} // end function uf_reversar_contabilizacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_fecha_contabilizado_siv($as_codemp,$as_comprobante,$ad_fechaconta,$ad_fechaanula)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_fecha_contabilizado_siv
		//		   Access: private
		//	    Arguments: as_codemp  // Código de empresa
		//                 as_comprobante  // Comprobante
		//                 ad_fechaconta  // Fecha de contabilización
		//                 ad_fechaanula  // Fecha de Anulacion
		//                 ad_fecha  // Fecha de contabilización ó de Anulación
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Método que actualiza las fechas de contabilizacion y anulacion
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 07/11/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;
		$ls_campo1="";
		$ls_campo2="";
		if($ad_fechaconta!="")
		{
			$ls_campo1=" fechaconta='".$ad_fechaconta."' ";
		}
		if($ad_fechaanula!="")
		{
			$ls_campo2=" fechaanula='".$ad_fechaanula."' ";
		}
		if($ls_campo1!="")
		{
			if($ls_campo2!="")
			{
				$ls_campos=$ls_campo1.", ".$ls_campo2;
			}
			else
			{
				$ls_campos=$ls_campo1;
			}
		}
		else
		{
			$ls_campos=$ls_campo2;
		}
		$ls_sql="UPDATE siv_dt_scg ".
		        "   SET ".$ls_campos.
                " WHERE codemp='".$as_codemp."' ".
				"   AND codcmp='".$as_comprobante."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
            $this->io_msg->message("CLASE->Integración SEP MÉTODO->uf_update_fecha_contabilizado_siv ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_update_fecha_contabilizado_siv
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_configuracion()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_configuracion
		//		   Access: private
		//	    Arguments:
		//	      Returns: lb_existe True si existe el comprobante
		//	  Description: Verifica la configuracion de los despachos de inventario.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: Ing.					  			Fecha Última Modificación : 13/05/08
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido = false;		
		$ls_sql = "SELECT value".
		          "  FROM sigesp_config".
				  " WHERE codemp='".$this->is_codemp."'".
				  "   AND codsis='SIV'".
				  "   AND seccion='CONFIG'".
				  "   AND entry='CONTA DESPACHO'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_msg->message("CLASE->Integración SNO MÉTODO->uf_select_configuracion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{                 
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_value=trim($row["value"]);
				if($ls_value=="1")
				{
					$lb_valido=true;
				}
			}
		}
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	} // end function uf_select_configuracion
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_detalles_contables($as_comprobante,$as_codcomapo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_detalles_contables
		//		   Access: private
		//	    Arguments: as_comprobante  // Código del comprobante de Nómina
		//				   as_codcomapo  // Código del comprobante de Aportes
		//	      Returns: lb_valido True si se insertaron correctamente los detalles en el datastored
		//	  Description: Método que recorre la tabla generada por nomina de asientos contables para ser
		//                  insertado en el datastore para la integracioón contable.
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 25/10/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;		
		$ls_sql="SELECT sc_cuenta, debhab, obsdes, monto, codcmp".
				"  FROM siv_despacho,siv_dt_scg ".
				" WHERE siv_despacho.codemp = '".$this->is_codemp."' ".
				"   AND siv_dt_scg.codcmp = '".$as_comprobante."' ".
				"   AND siv_despacho.codemp=siv_dt_scg.codemp ".
				"   AND siv_despacho.numorddes=siv_dt_scg.codcmp ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   
           	$this->io_msg->message("CLASE->Integración SNO MÉTODO->uf_procesar_detalles_contables ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{           
   	       while($row=$this->io_sql->fetch_row($rs_data) and ($lb_valido))
		   {
				$ls_scg_cuenta = $row["sc_cuenta"];
				$ls_mensaje = $row["debhab"];
				$ldec_monto = $row["monto"];				
				$ls_descripcion = $row["obsdes"];				
				$ls_documento = $row["codcmp"];								
                $ls_documento = $this->io_sigesp_int->uf_fill_comprobante(trim($ls_documento));
				// Incluimos el detalle de contabilidad en el datastored
				$lb_valido = $this->io_sigesp_int->uf_scg_insert_datastore($this->is_codemp,$ls_scg_cuenta,$ls_mensaje,$ldec_monto,$ls_documento,$this->is_procede,$ls_descripcion);				
				if (!$lb_valido)
				{  
				   $this->io_msg->message("ERROR->".$this->io_sigesp_int->is_msg_error);
				   break;
				}
		   } // end while
		}
		$this->io_sql->free_result($rs_data);	 
		return $lb_valido;
    } // end function uf_procesar_detalles_contables
	//-----------------------------------------------------------------------------------------------------------------------------------

}
?>