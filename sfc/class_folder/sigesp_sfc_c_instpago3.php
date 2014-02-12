<?php
 //////////////////////////////////////////////////////////////////////////////////////////
 // Clase:       - sigesp_sfc_c_instrpago
 // Autor:       - Ing. Gerardo Cordero		
 // Descripcion: - Clase que realiza los procesos basicos (insertar,actualizar,eliminar) con 
 //                respecto a la tabla Instrumento de pago.
 // Fecha:       - 16/02/2007     
 //////////////////////////////////////////////////////////////////////////////////////////
class sigesp_sfc_c_instrpago
{

 var $io_funcion;
 var $io_msgc;
 var $io_sql;
 var $datoemp;
 var $io_msg;
 
 
function sigesp_sfc_c_instrpago()
{
	require_once ("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	require_once("sigesp_sob_c_funciones_sob.php"); /* se toma la funcion de convertir cadena a caracteres*/
	$this->funsob=   new sigesp_sob_c_funciones_sob();
	$this->seguridad=   new sigesp_c_seguridad();
	$this->io_funcion = new class_funciones();		
	$io_include = new sigesp_include();
	$io_connect = $io_include->uf_conectar();		
	$this->io_sql= new class_sql($io_connect);
	$this->datoemp=$_SESSION["la_empresa"];	
	require_once("../shared/class_folder/class_mensajes.php");
	$this->io_msg=new class_mensajes();
}

		
function uf_select_instrpago($ls_numinst)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_instrpago
		// Parameters:  - $ls_numinst( Codigo del instrumento de pago).		
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////	
	
	    $ls_codemp=$this->datoemp["codemp"];
		$ls_cadena="SELECT * FROM sfc_instpago
		            WHERE codemp='".$ls_codemp."' AND numinst='".$ls_numinst."';";
		$rs_datauni=$this->io_sql->select($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msg="Error en uf_select_instrpago ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$lb_valido=true;
			}
			else
			{
				$lb_valido=false;
				$this->io_msg="Registro no encontrado";
			}
		}
		return $lb_valido;
}
	
function uf_guardar_instrpago($ls_codcli,$ls_numfac,$ls_codforpag,$ls_numinst,$ld_monto,$ls_codban,$ls_codtie/*,$aa_seguridad*/)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_guardar_nota_instrpago
		// Parameters:  - $ls_codcli( Codigo del cliente).		
		//			    - $ls_numfac( numero de la factura). 	
		//			    - $ls_codforpag( código forma de pago).
		//				- $ls_numinst( número ó código del instrumento de pago).
		//              - $ls_monto( monto a pagar).
		// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
		//////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_instrpago($ls_numinst);
		$ld_monto=$this->funsob->uf_convertir_cadenanumero($ld_monto); /* convierte cadena en numero */
			
/*		 $ls_fecnot=$this->io_funcion->uf_convertirdatetobd($ls_fecnot);*/

		if(!$lb_existe)
		{
		 
            $ls_cadena= "INSERT INTO sfc_instpago (codemp,codcli,numfac,codforpag,numinst,monto,codban,codtiend) 
			              VALUES ('".$ls_codemp."','".$ls_codcli."','".$ls_numfac."','".$ls_codforpag."','".$ls_numinst."',".$ld_monto.",'".$ls_codban."','".$ls_codtie."')";
			print $ls_sql;			  
			$this->io_msg="Registro Incluido!!!";	
			$ls_evento="INSERT";	
		}
		else
		{
			$ls_cadena= "UPDATE sfc_instpago
			             SET codforpag='".$ls_codforpag."', monto=".$ld_monto.", codban='".$ls_codban."' 
						 WHERE codemp='".$ls_codemp."' AND codcli='".$ls_codcli."' AND numinst='".$ls_numinst."';";
			
			$this->io_msg="Registro Actualizado!!!";
			$ls_evento="UPDATE";
		}
       // print $ls_cadena;
		$this->io_sql->begin_transaction();
		$li_numrows=$this->io_sql->execute($ls_cadena);
         
		 
		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_guardar_instrpago".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_msg;
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
       	}
		else
		{
			if($li_numrows>0)
			{
				$lb_valido=true;
				/*
				if($ls_evento=="INSERT")
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion ="Insertó el Propietario ".$ls_codpro." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="UPDATE";
					$ls_descripcion ="Actualizó el Propietario ".$ls_codpro." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				}*/
				$this->io_sql->commit();
			}
			else
			{
				$this->io_sql->rollback();
				if($lb_existe)
				{					
					$lb_valido=0;
					$this->io_msg="No actualizo el registro";					
				}
				else
				{
					$lb_valido=false;
					$this->io_msg="Registro No Incluido!!!";
					
				}
			}

		}
		return $lb_valido;
	}
	

	function uf_delete_instrpag($ls_numfac/*,$aa_seguridad*/)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_delete_conceptos
		// Parameters:  - $ls_codigo( Codigo del concepto).		
		// Descripcion: - Funcion que elimina la unidad de medida.
		//////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_instrpag($ls_numfac);
		
		if($lb_existe)
		{
		    	$ls_cadena= " DELETE FROM sfc_instpago
							  WHERE codemp='".$ls_codemp."' AND numfac='".$ls_numfac."'";
				$this->io_msg="Registro Eliminado!!!";		
	
				$this->io_sql->begin_transaction();
	
				$li_numrows=$this->io_sql->execute($ls_cadena);
	
				if(($li_numrows==false)&&($this->io_sql->message!=""))
				{
					$lb_valido=false;
					$this->io_sql->rollback();
					$this->io_msg="Error en metodo uf_delete_instrpago ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
					print $this->io_msg;
					print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
					//print $ls_cadena;
				}
				else
				{
					if($li_numrows>0)
					{
						$lb_valido=true;
						/*////////////////////////////////         SEGURIDAD               /////////////////////////////
						$ls_evento="DELETE";
						$ls_descripcion ="Eliminó el Propietario ".$ls_codpro." Asociado a la Empresa ".$ls_codemp;
						$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               ////////////////////////////*/			
						$this->io_sql->commit();
					}
					else
					{
						$lb_valido=false;
						$this->is_msgc="Registro No Eliminado!!!";
						$this->io_sql->rollback();
					}
	
				}
		}
		else
		{
			$lb_valido=1;
			$this->io_msg->message("El Registro no Existe");
		}
		return $lb_valido;
	}

/*********************************************************************************************************************************/ 
/*********************** SELECT DETALLES INSTRUMENTO DE PAGO *********************************************************************/
/*********************************************************************************************************************************/ 
function uf_select_detalles_instrpag($ls_numfac,&$aa_data,&$ai_rows)
	{
	 /***************************************************************************************/
	 /*	Function:	    uf_select_detallescot                                               */    
     /* Access:			public                                                              */ 
	 /*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro   */ 
	 /*	Description:	Funcion que se encarga de 									        */    
	 /*  Fecha:          25/03/2006                                                         */        
	 /*	Autor:          GERARDO CORDERO		                                                */     
	 /***************************************************************************************/
		 
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$ls_sql="SELECT * FROM sfc_instpago WHERE codemp='".$ls_codemp."' AND numfac='".$ls_numfac."';";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data==false)
		{
			$this->io_msg_error="Error en select detalles_instrpag".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->io_msg_error;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$ai_rows=$this->io_sql->num_rows($rs_data);    //devuelve numero de filas
				$aa_data=$this->io_sql->obtener_datos($rs_data); // devuelve datos
			}else
			{
				$ai_rows=0;
				$aa_data="";
			}			
		}		
		return $lb_valido;
	}
  /***************************************************************************************************/ 
  /*********************** GUARDAR DETALLES INSTRUMENTO DE PAGO **************************************/
  /***************************************************************************************************/ 
	function uf_guardar_detalles_instrpag($ls_codcli,$ls_numfac,$ls_codforpag,$ls_numinst,$ld_monto,$ls_codban,$ls_codent,$ls_codtie)             
    { 
	
	
	    /***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          GERARDO CORDERO		                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$ld_monto=$this->funsob->uf_convertir_cadenanumero($ld_monto); /* convierte cadena en numero */
		if ($ls_codban=="") $ls_codban="000";
		$ls_sql= " INSERT INTO sfc_instpago (codemp,codcli,numfac,codforpag,numinst,monto,codban,id_entidad,codtiend) ".
			     " VALUES ('".$ls_codemp."','".$ls_codcli."','".$ls_numfac."','".$ls_codforpag."','".$ls_numinst."',".$ld_monto.", '".$ls_codban."','".$ls_codent."','".$ls_codtie."')";
	    
		$this->io_sql->begin_transaction();	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			$this->io_msg="Error en metodo uf_guardar_detalles_instpag".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		    $this->io_sql->rollback();	
		    print $this->io_msg;
		}
		else
		{
			if($li_row>0)
			{
			    /************    SEGURIDAD    **************		 
				  $ls_evento="INSERT";
				  $ls_descripcion ="Insertó la Retencion ".$as_codded.", Detalle de la Valuacion ".$as_codval." Asociado a la Empresa ".$ls_codemp;
				   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion); 
				/**********************************************/
				$this->io_sql->commit();
				$lb_valido=true;
			}
			else
			{
				
				$this->io_sql->rollback();
			}
		
		}		
		return $lb_valido;
	}
  /**************************************************************************************************/ 
  /*********************** BORRAR DETALLES INSTRUMENTO DE PAGO **************************************/
  /**************************************************************************************************/ 
	function uf_delete_detalles_instrpag($ls_numfac,$ls_codforpag)

	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          GERARDO CORDERO		                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		
		$ls_sql= "DELETE FROM sfc_instpago WHERE codemp='".$ls_codemp."' AND numfac='".$ls_numfac."' AND codforpag='".$ls_codforpag."'";
		//print $ls_sql;
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_sql->rollback();
			
			//$this->io_msg="Error en uf_select_detproducto ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			
			print "Error en metodo eliminar_detalles_instrpag".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			/*************    SEGURIDAD    **************		 
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó la Retencion ".$as_codded.",Detalle de la Valuacion ".$as_codval." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
			/********************************************/	
			$lb_valido=true;
			$this->io_sql->commit();
		}
		return $lb_valido;	
	
	}
  /**************************************************************************************************/ 
  /*********************** UPDATE DETALLES INSTRUMENTO DE PAGO **************************************/
  /**************************************************************************************************/ 
 									 
	function uf_update_detalles_instrpag($ls_codcli,$ls_numfac,$ls_codforpag,$ls_numinst,$ld_monto,$ls_codban,$ls_codent)
	 {
	
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$ld_monto=$this->funsob->uf_convertir_cadenanumero($ld_monto); /* convierte cadena en numero */
		
		$ls_sql= "UPDATE sfc_instpago ".
			     " SET monto=".$ld_monto.", numinst='".$ls_numinst."', codban='".$ls_codban."',id_entidad='".$ls_codent."'".
  				 " WHERE codemp='".$ls_codemp."' AND codcli='".$ls_codcli."' AND codforpag='".$ls_codforpag."' AND numfac='".$ls_numfac."';";		
				
		//print("updatepagosantes:".$ls_sql);			
		$this->io_sql->begin_transaction();	
		//print("updatepagosDESPUES:".$ls_sql);
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row==false)
		{			
			//$this->is_msg= "Error en metodo uf_update_detalles_instrpag ".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$this->is_msgc="Error en metodo uf_update_detalles_instrpag".$this->io_funcion->uf_convertirmsg($this->io_sql->message);	
			$this->io_sql->rollback();	
		}
		else
		{
			if($li_row>0)
			{
				/*************    SEGURIDAD    **************		 
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó la retencion ".$as_codpar.", Detalle de la Valuacion ".$as_codval." Asociado a la Empresa ".$ls_codemp;
				   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion); 
				/**********************************************/
				$this->io_sql->commit();
				$lb_valido=true;
			}
			else
			{
				
				$this->io_sql->rollback();	
			}
		
		}		
		return $lb_valido;
	  }
  /*****************************************************************************************/ 
  /*********************** UPDATE ARREGLO DE DETALLES COTIZACION **************************************/
  /*****************************************************************************************/ 
	function uf_update_detalles_instrumentopago($ls_codcli,$ls_numfac,$aa_detallesnuevos,$ai_totalfilasnuevas,$ls_codtie/*,$aa_seguridad*/)
	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          GERARDO CORDERO		                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$lb_update=false;
		$ls_codemp=$this->datoemp["codemp"];
		$lb_valido=$this->uf_select_detalles_instrpag($ls_numfac,$la_detallesviejos,$li_totalfilasviejas);
		$li_totalnuevas=$ai_totalfilasnuevas-1; //-1
	
	
		for ($li_i=1;$li_i<=$li_totalnuevas;$li_i++)
		{
			$lb_existe=false;
			for ($li_j=1;$li_j<=$li_totalfilasviejas;$li_j++)
			{
				if ($la_detallesviejos["codemp"][$li_j]==$ls_codemp && $la_detallesviejos["numfac"][$li_j]==$ls_numfac && $la_detallesviejos["codforpag"][$li_j]==$aa_detallesnuevos["codforpag"][$li_i]) 
				{
				  if($la_detallesviejos["monto"][$li_j] != $aa_detallesnuevos["monto"][$li_i])
					{
					  $lb_update=true;
					}
					$lb_existe = true;
				}
						
			}	
				if (!$lb_existe)
				{
			
				$ls_codforpag=$aa_detallesnuevos["codforpag"][$li_i];
				$ls_denforpag=$aa_detallesnuevos["denforpag"][$li_i];
				$ls_numinst=$aa_detallesnuevos["numinst"][$li_i];
				$ls_codban=$aa_detallesnuevos["codban"][$li_i];
				$ls_monto=$aa_detallesnuevos["monto"][$li_i];
				$ls_codent=$aa_detallesnuevos["codent"][$li_i];
				$this->uf_guardar_detalles_instrpag($ls_codcli,$ls_numfac,$ls_codforpag,$ls_numinst,$ls_monto,$ls_codban,$ls_codent,$ls_codtie);
				}
			if ($lb_update)
			{
				$ls_codforpag=$aa_detallesnuevos["codforpag"][$li_i];
				$ls_denforpag=$aa_detallesnuevos["denforpag"][$li_i];
				$ls_numinst=$aa_detallesnuevos["numinst"][$li_i];
				$ls_codban=$aa_detallesnuevos["codban"][$li_i];
				$ls_monto=$aa_detallesnuevos["monto"][$li_i];
				$ls_codent=$aa_detallesnuevos["codent"][$li_i];
			
				$this->uf_update_detalles_instrpag($ls_codcli,$ls_numfac,$ls_codforpag,$ls_numinst,$ls_monto,$ls_codban,$ls_codent);
			}	  
		}
		for ($li_j=1;$li_j<=$li_totalfilasviejas;$li_j++)
		{
			$lb_existe=false;
			for ($li_i=1;$li_i<=$li_totalnuevas;$li_i++)
			{
				if ($la_detallesviejos["codemp"][$li_j]==$ls_codemp && $la_detallesviejos["numfac"][$li_j]==$ls_numfac && $la_detallesviejos["codforpag"][$li_j] ==$aa_detallesnuevos["codforpag"][$li_i]) 
				{
					$lb_existe = true;
				}				
			}
			if (!$lb_existe)
			{
				$this->uf_delete_detalles_instrpag($ls_numfac,$la_detallesviejos["codforpag"][$li_j]);
				
			}
		}
	
	}
}// FIN DE CLASE
?>
