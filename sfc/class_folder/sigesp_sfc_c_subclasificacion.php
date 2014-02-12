<?php
 //////////////////////////////////////////////////////////////////////////////////////////
 // Clase:       - sigesp_sfc_c_clasificacion
 // Autor:       - Ing. Gerardo Cordero		
 // Descripcion: - Clase que realiza los procesos basicos (insertar,actualizar,eliminar) con 
 //                respecto a la tabla cliente.
 // Fecha:       - 30/11/2006     
 //////////////////////////////////////////////////////////////////////////////////////////
class sigesp_sfc_c_subclasificacion
{

 var $io_funcion;
 var $io_msgc;
 var $io_sql;
 var $datoemp;
 var $io_msg;
 
 
function sigesp_sfc_c_subclasificacion()
{
	require_once ("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$this->seguridad=   new sigesp_c_seguridad();
	$this->io_funcion = new class_funciones();		
	$io_include = new sigesp_include();
	$io_connect = $io_include->uf_conectar();		
	$this->io_sql= new class_sql($io_connect);
	$this->datoemp=$_SESSION["la_empresa"];	
	require_once("../shared/class_folder/class_mensajes.php");
	$this->io_msg=new class_mensajes();
}

		
	function uf_select_subclasificacion($ls_codsub)
	{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_unidad
		// Parameters:  - $ls_codtob( Codigo del Tipo de Obra).		
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////	
	
	    $ls_codemp=$this->datoemp["codemp"];
		$ls_cadena="SELECT * FROM sfc_subclasificacion 
		            WHERE cod_sub='".$ls_codsub."'";
		$rs_datauni=$this->io_sql->select($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_subclasificacion".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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
	

	function uf_guardar_subclasificacion($ls_codsub,$ls_nomsub,$ls_codcla/*,$aa_seguridad*/)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_guardar_unidad
		// Parameters:  - $ls_coduni( Codigo de la Unidad de Medida).		
		//			    - $ls_codtun( Codigo de el tipo de Unidad de Medida). 	
		//			    - $ls_nomuni( Nombre de la Unidad).
		//              - $ls_desuni( Breve Descripcion de la Unidad).
		// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
		//////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_subclasificacion($ls_codsub);

		if(!$lb_existe)
		{
            $ls_cadena= " INSERT INTO sfc_subclasificacion(cod_sub,densub,codcla) 
			              VALUES ('".$ls_codsub."','".$ls_nomsub."','".$ls_codcla."')";
			$this->io_msgc="Registro Incluido!!!";	
			$ls_evento="INSERT";	
		}
		else
		{
			$ls_cadena= "UPDATE sfc_subclasificacion
			             SET densub='".$ls_nomsub."',codcla='001' 
						 WHERE cod_sub='".$ls_codsub."'";
			
			$this->io_msgc="Registro Actualizado!!!";
			$ls_evento="UPDATE";
		}

		$this->io_sql->begin_transaction();

		$li_numrows=$this->io_sql->execute($ls_cadena);

		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_guardar_subclasificacion".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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
	

	function uf_delete_subclasificacion($ls_codsub/*,$aa_seguridad*/)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_delete_conceptos
		// Parameters:  - $ls_codigo( Codigo del concepto).		
		// Descripcion: - Funcion que elimina la unidad de medida.
		//////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_subclasificacion($ls_codsub);
		
		if($lb_existe)
		{
		    	$ls_cadena= " DELETE FROM sfc_subclasificacion
							  WHERE cod_sub='".$ls_codsub."'";
				$this->io_msgc="Registro Eliminado!!!";		
	
				$this->io_sql->begin_transaction();
	
				$li_numrows=$this->io_sql->execute($ls_cadena);
	
				if(($li_numrows==false)&&($this->io_sql->message!=""))
				{
					$lb_valido=false;
					$this->io_sql->rollback();
					$this->io_msgc="Error en metodo uf_delete_clasificacion ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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
/*********************************************************************************************************************************/ 
/*********************** SELECT DETALLES Sublineas de Articulos  *****************************************************************/
/*********************************************************************************************************************************/ 
function uf_select_detalles_sublineas($ls_codcla,&$aa_data,&$ai_rows)
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
		$ls_sql="SELECT * FROM sfc_subclasificacion WHERE codcla='".$ls_codcla."';";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data==false)
		{
			$this->io_msg_error="Error en select detalles_sublineas".$this->io_function->uf_convertirmsg($this->io_sql->message);
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
	function uf_guardar_detalles_sublineas($ls_codcla,$ls_codsub,$ls_densub)             
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
		$ls_sql= " INSERT INTO sfc_subclasificacion (cod_sub,densub,codcla) ".
			     " VALUES ('".$ls_codsub."','".$ls_densub."','".$ls_codcla."')";
	    print $ls_sql;
		$this->io_sql->begin_transaction();	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
				
		/*	$this->is_msg_error="Error en select detallesfac".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;*/
		$this->io_msg="Error en metodo uf_guardar_detalles_instpag".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		$this->io_sql->rollback();	
		print $this->io_msg;
		print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
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
	function uf_delete_detalles_sublineas($ls_codcla,$ls_codsub)

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
		
		$ls_sql= "DELETE FROM sfc_subclasificacion WHERE codcla='".$ls_codcla."' AND cod_sub='".$ls_codsub."'";
		//print $ls_sql;
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_sql->rollback();
			
			//$this->io_msg="Error en uf_select_detproducto ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			
			print "Error en metodo eliminar_detalles_sublineas".$this->io_function->uf_convertirmsg($this->io_sql->message);
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
 									 
	function uf_update_detalles_sublinea($ls_codcla,$ls_codsub,$ls_densub)
	 {
	
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		
		
		$ls_sql= "UPDATE sfc_instpago ".
			     " SET densub=".$ld_densub.", codcla='".$ls_codcla."'".
  				 " WHERE cod_sub='".$ls_codsub."';";		
				
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
	function uf_update_detalles_sublineas($ls_codcla,$aa_detallesnuevos,$ai_totalfilasnuevas/*,$aa_seguridad*/)
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
		$lb_valido=$this->uf_select_detalles_sublineas($ls_codcla,$la_detallesviejos,$li_totalfilasviejas);
		$li_totalnuevas=$ai_totalfilasnuevas-1; //-1
	
	
		for ($li_i=1;$li_i<=$li_totalnuevas;$li_i++)
		{
			$lb_existe=false;
			for ($li_j=1;$li_j<=$li_totalfilasviejas;$li_j++)
			{
				if ($la_detallesviejos["codcla"][$li_j]==$ls_codcla && $la_detallesviejos["codsub"][$li_j]==$aa_detallesnuevos["codsub"][$li_i]) 
				{
				  if($la_detallesviejos["densub"][$li_j] != $aa_detallesnuevos["densub"][$li_i])
					{
					  $lb_update=true;
					}
					$lb_existe = true;
				}
						
			}	
				if (!$lb_existe)
				{
				$ls_codsub=$aa_detallesnuevos["codsub"][$li_i];
				$ls_densub=$aa_detallesnuevos["densub"][$li_i];
				
				//$ls_impcot=$aa_detallesnuevos["impcot"][$li_i];
//				print $ls_codcli."/".$ls_numcot."/".$ls_codpro."/".$ls_cancot."/".$ls_precot."/".$ls_impcot;
				$this->uf_guardar_detalles_sublineas($ls_codcla,$ls_codsub,$ls_densub);
				//print $ls_codcli."/".$ls_numcot."/".$ls_codpro."/".$ls_cancot."/".$ls_precot."/".$ls_impcot;
	//			print "guardar_detallescot";
				}
			if ($lb_update)
			{
				$ls_codsub=$aa_detallesnuevos["codsub"][$li_i];
				$ls_densub=$aa_detallesnuevos["densub"][$li_i];
							
				$this->uf_update_detalles_sublinea($ls_codcla,$ls_codsub,$ls_densub);
				//print "despues".$ls_codcli."/".$ls_numfac."/".$ls_codforpag."/".$ls_numinst."/".$ls_monto."/";
				//print "update_detallespago";
			}	  
		}
		for ($li_j=1;$li_j<=$li_totalfilasviejas;$li_j++)
		{
			$lb_existe=false;
			for ($li_i=1;$li_i<=$li_totalnuevas;$li_i++)
			{
				if ($la_detallesviejos["codcla"][$li_j]==$ls_codcla && $la_detallesviejos["codsub"][$li_j] ==$aa_detallesnuevos["codsub"][$li_i]) 
				{
					$lb_existe = true;
				}				
			}
			if (!$lb_existe)
			{
				//$this->uf_delete_detallescot($ls_numcot,$la_detallesviejos["codpro"][$li_j]);
				$this->uf_delete_detalles_sublinea($ls_codcla,$la_detallesviejos["codsub"][$li_j]);
				//print "delete_detallescot";
			}
		}
	
	}
	
}
?>
