<?php
 //////////////////////////////////////////////////////////////////////////////////////////
 // Clase:       - sigesp_sfc_c_factura
 // Autor:       - Ing. Gerardo Cordero		
 // Descripcion: - Clase que realiza los procesos basicos (insertar,actualizar,eliminar) con 
 //                respecto a la tabla sfc_factura y sfc_detfactura.
 // Fecha:       - 16/02/2007     
 //////////////////////////////////////////////////////////////////////////////////////////
class sigesp_sfc_c_factura
{

 var $io_funcion;
 var $io_msgc;
 var $io_sql;
 var $datoemp;
 var $io_msg;
 
 
function sigesp_sfc_c_factura()
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

		
function uf_select_factura($ls_numfac)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_factura
		// Parameters:  - $ls_numfac( Codigo de la factura).		
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////	
	
	    $ls_codemp=$this->datoemp["codemp"];
		$ls_cadena="SELECT * FROM sfc_factura
		            WHERE codemp='".$ls_codemp."' AND numfac='".$ls_numfac."';";
		$rs_datauni=$this->io_sql->select($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_factura ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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
				$this->io_msgc="Registro no encontrado";
			}
		}
		return $lb_valido;
}

function uf_guardar_factura($ls_codcli,$ls_numfac,$ls_numcot,$ls_codusu,$ls_fecemi,$ls_conpag,$ld_monto,$ls_estfaccon,$ls_montoret,$ls_esppag,$ls_montopar,$ls_codtiend/*,$aa_seguridad*/)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_guardar_factura.
		// Parameters:  - $ls_codcli( Codigo del cliente).		
		//				- $ls_numfac(Número de la factura)
		//			    - $ls_numcot.
		//			    - $ls_codusu(código del usuario).
		//				- $ls_fecemi(fecha de la emision de la factura).
		//				- $ls_conpag(condicion de pago: Crédito ó Contado).
		//              - $ld_monto(monto a pagar).
		//              - $ld_estfac(Estado de la factura: Cancelada ó No cancelada).
		// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
		//////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_factura($ls_numfac);
		$ld_monto=$this->funsob->uf_convertir_cadenanumero($ld_monto); /* convierte cadena en numero */
		$ld_montoret=$this->funsob->uf_convertir_cadenanumero($ls_montoret); /* convierte cadena en numero */
		$ld_montopar=$this->funsob->uf_convertir_cadenanumero($ls_montopar); /* convierte cadena en numero */

		
		$ls_fecemi=$this->io_funcion->uf_convertirdatetobd($ls_fecemi);	
			
		if(!$lb_existe)
		{
		 
            $ls_cadena= "INSERT INTO sfc_factura (codemp,codcli,numfac,numcot,codusu,fecemi,conpag,monto,estfac,estfaccon,montoret,esppag,montopar,codtiend) VALUES ('".$ls_codemp."','".$ls_codcli."','".$ls_numfac."','".$ls_numcot."','".$ls_codusu."','".$ls_fecemi."','".$ls_conpag."',".$ld_monto.",'N','".$ls_estfaccon."',".$ld_montoret.",'".$ls_esppag."',".$ld_montopar.",'".$ls_codtiend."')";
						  
			$this->io_msg="Registro Incluido!!!";	
			$ls_evento="INSERT";	
		}
		else
		{
			$ls_cadena= "UPDATE sfc_factura
			             SET codcli='".$ls_codcli."', numcot='".$ls_numcot."', codusu='".$ls_codusu."', fecemi='".$ls_fecemi."', conpag='".$ls_conpag."', monto=".$ld_monto.", estfaccon='".$ls_estfaccon."', montoret=".$ld_montoret.", esppag='".$ls_esppag."', montopar=".$ld_montopar." WHERE codemp='".$ls_codemp."' AND numfac='".$ls_numfac."';";
			
			$this->io_msg="Registro Actualizado!!!";
			$ls_evento="UPDATE";
		}
     //  print "guardar factura: ".$ls_evento.": ".$ls_cadena;
		
		$this->io_sql->begin_transaction();
		$li_numrows=$this->io_sql->execute($ls_cadena);
         
		 
		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			print $ls_cadena;
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_guardar_factura".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_msgc;
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
					$this->io_msgc="No actualizo el registro";					
				}
				else
				{
					$lb_valido=false;
					$this->io_msgc="Registro No Incluido!!!";
					
				}
			}

		}
		return $lb_valido;
}
/*******************************************************************************************************************************/
function uf_actualizar_facturastatus($ls_numfac,$ls_estfaccon)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_guardar_factura.
		// Parameters:  - $ls_codcli( Codigo del cliente).		
		//				- $ls_numfac(Número de la factura)
		//			    - $ls_numcot.
		//			    - $ls_codusu(código del usuario).
		//				- $ls_fecemi(fecha de la emision de la factura).
		//				- $ls_conpag(condicion de pago: Crédito ó Contado).
		//              - $ld_monto(monto a pagar).
		//              - $ld_estfac(Estado de la factura: Cancelada ó No cancelada).
		// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
		//////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_factura($ls_numfac);
			
		if(!$lb_existe)
		{
		}
		else
		{
			$ls_cadena= "UPDATE sfc_factura ".
			            "SET estfaccon='".$ls_estfaccon."' ". 
						"WHERE codemp='".$ls_codemp."' AND numfac='".$ls_numfac."';";
			
			$this->io_msgc="Registro Actualizado!!!";
			$ls_evento="UPDATE";
		}
      // print "guardar factura: ".$ls_evento.": ".$ls_cadena;
		
		$this->io_sql->begin_transaction();
		$li_numrows=$this->io_sql->execute($ls_cadena);
         
		 
		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_actualizar_facturastatus".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_msgc;
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
       	}
		else
		{
			if($li_numrows>0)
			{
				$lb_valido=true;
			
				$this->io_sql->commit();
			}
			else
			{
				$this->io_sql->rollback();
				if($lb_existe)
				{					
					$lb_valido=0;
					$this->io_msgc="No actualizo el registro";					

				}
				else
				{
					$lb_valido=false;
					$this->io_msgc="Registro No Incluido!!!";
					
				}
			}

		}
	return $lb_valido;
}
/*******************************************************************************************************************************/	

function uf_delete_factura($ls_numfac/*,$aa_seguridad*/)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_delete_factura.
		// Parameters:  - $ls_numfac (número de la factura).
		// Descripcion: - Funcion que elimina una factura.
		//////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_factura($ls_numfac);
		
		if($lb_existe)
		{
		    	$ls_cadena= " DELETE FROM sfc_factura WHERE codemp='".$ls_codemp."' AND numfac='".$ls_numfac."'";
				$this->io_msgc="Registro Eliminado!!!";		
	
				$this->io_sql->begin_transaction();
	
				$li_numrows=$this->io_sql->execute($ls_cadena);
	
				if(($li_numrows==false)&&($this->io_sql->message!=""))
				{
					$lb_valido=false;
					$this->io_sql->rollback();
					$this->io_msgc="Error en metodo uf_delete_factura ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
					print $this->io_msgc;
					print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
					/*print "delete factura:".$ls_cadena;*/
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
/******************************************************************************************************************************/
/******************  FACTURA DETALLES **************************************************************************************/
/******************************************************************************************************************************/
function uf_select_detfactura($ls_numfac)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_detfactura.
		// Parameters:  - $ls_numfac( Número de la factura).		
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////	
	
	    $ls_codemp=$this->datoemp["codemp"];
		$ls_cadena="SELECT * FROM sfc_detfactura
		            WHERE codemp='".$ls_codemp."' AND numfac='".$ls_numfac."';";
		$rs_datauni=$this->io_sql->select($ls_cadena);
       /* print $ls_cadena;*/
		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_detfactura ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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
				$this->io_msgc="Registro no encontrado";
			}
		}
		return $lb_valido;
}
	
function uf_select_detproducto($ls_numfac,$ls_codpro)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_detproducto
		// Parameters:  - $ls_codpro( Codigo del producto).		
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////	
	   
	    $ls_codemp=$this->datoemp["codemp"];
		$ls_cadena="SELECT * FROM sfc_detfactura
		            WHERE codemp='".$ls_codemp."' AND numfac='".$ls_numfac."' AND codpro='".$ls_codpro."';";
		$rs_datauni=$this->io_sql->select($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_detproducto ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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
				$this->io_msgc="Registro no encontrado";
			}
		}
		return $lb_valido;
}	

	function uf_delete_detfactura($ls_numfac/*,$aa_seguridad*/)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_delete_detfactura
		// Parameters:  - $ls_numfac (número de factura).
		// Descripcion: - Funcion que elimina una factura.
		//////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_detfactura($ls_numfac);
		
		if($lb_existe)
		{
		    	$ls_cadena= " DELETE FROM sfc_detfactura
							  WHERE codemp='".$ls_codemp."' AND numfac='".$ls_numfac."'";
				/*$this->io_msgc="Registro Eliminado!!!";		*/
	
				$this->io_sql->begin_transaction();
	
				$li_numrows=$this->io_sql->execute($ls_cadena);
	
				if(($li_numrows==false)&&($this->io_sql->message!=""))
				{
					$lb_valido=false;
					$this->io_sql->rollback();
					$this->io_msgc="Error en metodo uf_delete_detfactura ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
					print $this->io_msgc;
					print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
					/*print $ls_cadena;*/
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



/*************************************************************************************************************/
/********************  INSTRUMENTO DE PAGO *******************************************************************/
/*************************************************************************************************************/
function uf_select_instpago($ls_numfac)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_detfactura.
		// Parameters:  - $ls_numfac( Número de la factura).		
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////	
	
	    $ls_codemp=$this->datoemp["codemp"];
		$ls_cadena="SELECT * FROM sfc_instpago
		            WHERE codemp='".$ls_codemp."' AND numfac='".$ls_numfac."';";
		$rs_datauni=$this->io_sql->select($ls_cadena);
       /* print $ls_cadena;*/
		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_instpago ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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
				$this->io_msgc="Registro no encontrado";
			}
		}
		return $lb_valido;
}
	
function uf_delete_instpago($ls_numfac/*,$aa_seguridad*/)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_delete_detfactura
		// Parameters:  - $ls_numfac (número de factura).
		// Descripcion: - Funcion que elimina una factura.
		//////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_instpago($ls_numfac);
		
		if($lb_existe)
		{
		    	$ls_cadena= " DELETE FROM sfc_instpago
							  WHERE codemp='".$ls_codemp."' AND numfac='".$ls_numfac."'";
				/*$this->io_msgc="Registro Eliminado!!!";		*/
	
				$this->io_sql->begin_transaction();
	
				$li_numrows=$this->io_sql->execute($ls_cadena);
	
				if(($li_numrows==false)&&($this->io_sql->message!=""))
				{
					$lb_valido=false;
					$this->io_sql->rollback();
					$this->io_msgc="Error en metodo uf_delete_instpago ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
					print $this->io_msgc;
					print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
					/*print $ls_cadena;*/
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
/******************************************************************************************************/ 
/*********************** SELECT DETALLES FACTURACION **************************************************/
/******************************************************************************************************/ 
function uf_select_detallesfac ($ls_numfac,&$aa_data,&$ai_rows)
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
		$ls_sql="SELECT  *
				 FROM sfc_detfactura
				 WHERE codemp='".$ls_codemp."' AND numfac='".$ls_numfac."';";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select detallesfac".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
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
  /*****************************************************************************************/ 
  /*********************** GUARDAR DETALLES FACTURACION ************************************/
  /*****************************************************************************************/ 
	function uf_guardar_detallesfac($ls_numfac,$ls_codpro,$ls_canpro,$ls_prepro,$ls_porimp,$ls_codalm,$ls_codtie)             
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
		$ls_canpro=$this->funsob->uf_convertir_cadenanumero($ls_canpro); /* convierte cadena en numero */
		$ls_prepro=$this->funsob->uf_convertir_cadenanumero($ls_prepro); /* convierte cadena en numero */
		$ls_porimp=$this->funsob->uf_convertir_cadenanumero($ls_porimp); /* convierte cadena en numero */
		
		$ls_sql= "INSERT INTO sfc_detfactura (codemp,numfac,codpro,canpro,prepro,porimp,codalm,codtiend) VALUES ('".$ls_codemp."','".$ls_numfac."','".$ls_codpro."',".$ls_canpro.",".$ls_prepro.",".$ls_porimp.",'".$ls_codalm."','".$ls_codtie."')";		
	   
		$this->io_sql->begin_transaction();	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
				
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_guardar_detallesfac".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_msgc;
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
  /*****************************************************************************************/ 
  /*********************** BORRAR DETALLES FACTURACION *************************************/
  /*****************************************************************************************/ 
	function uf_delete_detallesfac($ls_numfac,$ls_codpro/*$aa_seguridad*/)

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
		
		$ls_sql= "DELETE FROM sfc_detfactura WHERE codemp='".$ls_codemp."' AND numfac='".$ls_numfac."' AND codpro='".$ls_codpro."';";
		
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_sql->rollback();
			
			//$this->io_msgc="Error en uf_select_detproducto ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			
			print"Error en metodo eliminar_detallesfac".$this->io_function->uf_convertirmsg($this->io_sql->message);
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
  /*****************************************************************************************/ 
  /*********************** UPDATE DETALLES FACTURACION *************************************/
  /*****************************************************************************************/ 
	function uf_update_detallesfac($ls_numfac,$ls_codpro,$ls_canpro,$ls_prepro,$ls_porimp,$ls_codalm/*,$aa_seguridad*/)
	 {
	
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$ls_canpro=$this->funsob->uf_convertir_cadenanumero($ls_canpro); /* convierte cadena en numero */
		$ls_prepro=$this->funsob->uf_convertir_cadenanumero($ls_prepro); /* convierte cadena en numero */
		$ls_porimp=$this->funsob->uf_convertir_cadenanumero($ls_porimp); /* convierte cadena en numero */
		
		$ls_sql="UPDATE sfc_detfactura 
				SET  canpro=".$ls_canpro.", prepro=".$ls_prepro.", porimp=".$ls_porimp.", codalm=".$ls_codalm." 
				WHERE codemp='".$ls_codemp."' AND codpro='".$ls_codpro."' AND numfac='".$ls_numfac."';";		
				
					
		$this->io_sql->begin_transaction();	
		//print($ls_sql);
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			print "Error en metodo uf_update_detallesfac ".$this->io_function->uf_convertirmsg($this->io_sql->message);
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
  /*********************** UPDATE ARREGLO DE DETALLES **************************************/
  /*****************************************************************************************/ 
	function uf_update_detallesfacturas($ls_codcli,$ls_numfac,$aa_detallesnuevos,$ai_totalfilasnuevas,$ls_codtie/*,$aa_seguridad*/)
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
		$lb_valido=$this->uf_select_detallesfac($ls_numfac,$la_detallesviejos,$li_totalfilasviejas);
		$li_totalnuevas=$ai_totalfilasnuevas-1; //-1
	
	
		for ($li_i=1;$li_i<=$li_totalnuevas;$li_i++)
		{
			$lb_existe=false;
			for ($li_j=1;$li_j<=$li_totalfilasviejas;$li_j++)
			{
				if ($la_detallesviejos["codemp"][$li_j]=$ls_codemp && $la_detallesviejos["numfac"][$li_j]=$ls_numfac && $la_detallesviejos["codpro"][$li_j] ==$aa_detallesnuevos["codpro"][$li_i]) 
				{
				  if($la_detallesviejos["canpro"][$li_j] != $aa_detallesnuevos["canpro"][$li_i])
					{
					  $lb_update=true;
					}
					$lb_existe = true;
				}
						
			}	
				if (!$lb_existe)
				{
				$ls_codpro=$aa_detallesnuevos["codpro"][$li_i];
				$ls_canpro=$aa_detallesnuevos["canpro"][$li_i];
				$ls_prepro=$aa_detallesnuevos["prepro"][$li_i];
				$ls_porimp=$aa_detallesnuevos["porimp"][$li_i];
				$ls_codalm=$aa_detallesnuevos["codalm"][$li_i];
				$this->uf_guardar_detallesfac($ls_numfac,$ls_codpro,$ls_canpro,$ls_prepro,$ls_porimp,$ls_codalm,$ls_codtie);
				}
			if ($lb_update)
			{
			
			$ls_codpro=$aa_detallesnuevos["codpro"][$li_i];
			$ls_canpro=$aa_detallesnuevos["canpro"][$li_i];
			$ls_prepro=$aa_detallesnuevos["prepro"][$li_i];
			$ls_porimp=$aa_detallesnuevos["porimp"][$li_i];
			$ls_codalm=$aa_detallesnuevos["codalm"][$li_i];
			$this->uf_update_detallesfac($ls_numfac,$ls_codpro,$ls_canpro,$ls_prepro,$ls_porimp,$ls_codalm/*,$aa_seguridad*/);
			
			}
		
		  
		}
		for ($li_j=1;$li_j<=$li_totalfilasviejas;$li_j++)
		{
			$lb_existe=false;
			for ($li_i=1;$li_i<=$li_totalnuevas;$li_i++)
			{
				if ($la_detallesviejos["codemp"][$li_j]==$ls_codemp && $la_detallesviejos["numfac"][$li_j]==$ls_numfac && $la_detallesviejos["codpro"][$li_j] ==$aa_detallesnuevos["codpro"][$li_i]) 
				{
					$lb_existe = true;
				}				
			}
			if (!$lb_existe)
			{
				$this->uf_delete_detallesfac($ls_numfac,$la_detallesviejos["codpro"][$li_j]);
			}
		}
	
	}

function uf_select_existencia($ls_codpro,$ls_codalm)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_detfactura.
		// Parameters:  - $ls_numfac( Número de la factura).		
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////	
	
	    $ls_codemp=$this->datoemp["codemp"];
		$ls_cadena="SELECT existencia FROM sim_articuloalmacen
		            WHERE codemp='".$ls_codemp."' AND codart='".$ls_codpro."' AND codalm='".$ls_codalm."'";
		$rs_datauni=$this->io_sql->select($ls_cadena);
       /* print $ls_cadena;*/
		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_detfactura ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$ls_exist=$row["existencia"];
			}
			
		}
		return $ls_exist;
}

/**ULTIMO*/
function uf_select_nota($ls_numfac)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_factura
		// Parameters:  - $ls_numfac( Codigo de la factura).		
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////	
	
	    $ls_codemp=$this->datoemp["codemp"];
		$ls_cadena="SELECT * FROM sfc_nota
		            WHERE codemp='".$ls_codemp."' AND nro_factura='".$ls_numfac."';";
		$rs_datauni=$this->io_sql->select($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_factura ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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
				$this->io_msgc="Registro no encontrado";
			}
		}
		return $lb_valido;
}


function uf_delete_notas($ls_numfac/*,$aa_seguridad*/)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_delete_factura.
		// Parameters:  - $ls_numfac (número de la factura).
		// Descripcion: - Funcion que elimina una factura.
		//////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_nota($ls_numfac);
		
		if($lb_existe)
		{
		    	$ls_cadena= " DELETE FROM sfc_nota WHERE codemp='".$ls_codemp."' AND nro_factura='".$ls_numfac."'";
						
	
				$this->io_sql->begin_transaction();
	
				$li_numrows=$this->io_sql->execute($ls_cadena);
	
				if(($li_numrows==false)&&($this->io_sql->message!=""))
				{
					$lb_valido=false;
					$this->io_sql->rollback();
					$this->io_msgc="Error en metodo uf_delete_factura ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
					print $this->io_msgc;
					print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
					/*print "delete factura:".$ls_cadena;*/
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


}/*FIN DE LA CLASE */
?>
