<?php
 //////////////////////////////////////////////////////////////////////////////////////////
 // Clase:       - sigesp_sfc_c_clasificacion
 // Autor:       - Ing. Gerardo Cordero		
 // Descripcion: - Clase que realiza los procesos basicos (insertar,actualizar,eliminar) con 
 //                respecto a la tabla cliente.
 // Fecha:       - 30/11/2006     
 //////////////////////////////////////////////////////////////////////////////////////////
class sigesp_sfc_c_clasificacion
{

 var $io_funcion;
 var $io_msgc;
 var $io_sql;
 var $datoemp;
 var $io_msg;
 
 
function sigesp_sfc_c_clasificacion()
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

		
	function uf_select_clasificacion($ls_codcla)
	{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_unidad
		// Parameters:  - $ls_codtob( Codigo del Tipo de Obra).		
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////	
	
	    $ls_codemp=$this->datoemp["codemp"];
		$ls_cadena="SELECT * FROM sfc_clasificacion 
		            WHERE codcla='".$ls_codcla."'";
		$rs_datauni=$this->io_sql->select($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_clasificacion".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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
	

	function uf_guardar_clasificacion($ls_codcla,$ls_nomcla,$aa_seguridad)
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
		$lb_existe=$this->uf_select_clasificacion($ls_codcla);

		if(!$lb_existe)
		{
            $ls_cadena= " INSERT INTO sfc_clasificacion(codcla,dencla) 
			              VALUES ('".$ls_codcla."','".$ls_nomcla."')";
						  
			$this->io_msgc="Registro Incluido!!!";	
			$ls_evento="INSERT";	
		}
		else
		{
			$ls_cadena= "UPDATE sfc_clasificacion
			             SET dencla='".$ls_nomcla."' WHERE codcla='".$ls_codcla."'";
			
			$this->io_msgc="Registro Actualizado!!!";
			$ls_evento="UPDATE";
		}

		$this->io_sql->begin_transaction();

		$li_numrows=$this->io_sql->execute($ls_cadena);

		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_guardar_tienda".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_msgc;
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
       	}
		else
		{
			if($li_numrows>0)
			{
				$lb_valido=true;
				
				if($ls_evento=="INSERT")
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion ="Insertó la Clasificacion ".$ls_codcla." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="UPDATE";
					$ls_descripcion ="Actualizó la Clasificacion ".$ls_codcla." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				}
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
	

	function uf_delete_clasificacion($ls_codcla,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_delete_conceptos
		// Parameters:  - $ls_codigo( Codigo del concepto).		
		// Descripcion: - Funcion que elimina la unidad de medida.
		//////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_clasificacion($ls_codcla);
		
		if($lb_existe)
		{
		    	$ls_cadena= " DELETE FROM sfc_clasificacion
							  WHERE codcla='".$ls_codcla."'";
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
						////////////////////////////////         SEGURIDAD               /////////////////////////////
						$ls_evento="DELETE";
						$ls_descripcion ="Eliminó la Clasificacion ".$ls_codcla." Asociado a la Empresa ".$ls_codemp;
						$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               ///////////////////////////			
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
/**********************************************************************************************************************************/
/**********************************************************************************************************************************/
/***************************  DEDUCCIONES - DEDUCCIONES - DEDUCCIONES - DEDUCCIONES - DEDUCCIONES  ********************************/
/**********************************************************************************************************************************/
/**********************************************************************************************************************************/


/******************************************************************************************************/ 
/*********************** SELECT CLIENTE DEDUCCIONES  **************************************************/
/******************************************************************************************************/ 
function uf_select_lineassublineas($ls_codcla,&$aa_data,&$ai_rows)
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
				 FROM sfc_subclasificacion
				 WHERE codcla='".$ls_codcla."';";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select lineassublineas".$this->io_function->uf_convertirmsg($this->io_sql->message);
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
  /*********************** GUARDAR CLIENTE DEDUCCIONES  ************************************/
  /*****************************************************************************************/ 
	function uf_guardar_lineassublineas($ls_codcla,$ls_codsub,$ls_densub,$aa_seguridad)
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
		
		$ls_sql= "INSERT INTO sfc_subclasificacion (cod_sub,codcla,den_sub) VALUES ('".$ls_codsub."','".$ls_codcla."','".$ls_densub."')";		
	    //print $ls_sql;
		$this->io_sql->begin_transaction();	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{				
		    $lb_valido=false;
			$this->is_msgc="Error en metodo uf_guardar_clientededuccion".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_msgc;
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($li_row>0)
			{
			    /************    SEGURIDAD    **************/		 
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
  /*********************** BORRAR TODAS LAS DEDUCCIONES DE UN CLIENTE ******************************/
  /*****************************************************************************************/ 
	function uf_delete_sublineas($ls_codcla)

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
		
		$ls_sql= "DELETE FROM sfc_subclasificacion WHERE codcla='".$ls_codcla."';";
		
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_sql->rollback();
			
			print"Error en metodo eliminar_sublineas ".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			/*************    SEGURIDAD    **************/		 
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
  /*********************** BORRAR UNA DEDUCCION DE UN CLIENTE ******************************/
  /*****************************************************************************************/ 
	function uf_delete_lineassublineas($ls_codcla,$ls_codsub)

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
		
		$ls_sql= "DELETE FROM sfc_subclasificacion WHERE codcla='".$ls_codcla."' AND cod_sub='".$ls_codsub."';";
		
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_sql->rollback();
			
			print"Error en metodo eliminar_clientededuccion ".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			/*************    SEGURIDAD    **************/		 
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
  /*********************** UPDATE DETALLES COTIZACION **************************************/
  /*****************************************************************************************/ 
	function uf_update_sublineas($ls_codcla,$ls_codsub,$ls_densub,$aa_seguridad)
	 {
	
		$lb_valido=false;	
		
		$ls_sql="UPDATE sfc_subclasificacion 
				SET  den_sub=".$ls_densub.", codcla=".$ls_codcla." 
				WHERE cod_sub='".$ls_codsub."';";		
				
					
		$this->io_sql->begin_transaction();	
		//print($ls_sql);
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{		
		/***********************************************************************************************************/
		$lb_valido=false;
			$this->is_msgc="Error en metodo uf_update_detallescot".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_msgc;
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
		/************************************************************************************************************/
		
			
			/*print "Error en metodo uf_update_detallescot ".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();	*/
		}
		else
		{
			if($li_row>0)
			{
				/*************    SEGURIDAD    **************/		 
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
 
 /****************************************************************************************************/ 
  /*********************** UPDATE ARREGLO DE CLIENTE DEDUCCIONES **************************************/
  /****************************************************************************************************/ 
  
  /*Revisar, este llama a los metodos anteriores*/
	function uf_update_lineassublineas($ls_codcla,$aa_detallesnuevos,$ai_totalfilasnuevas,$aa_seguridad)

	{

        
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          GERARDO CORDERO		                                               */     
		/***************************************************************************************/
		//print "codigo:".$ls_codcli;
		//print_r($aa_detallesnuevos);
		
		$lb_valido=false;		
		$ls_codemp=$this->datoemp["codemp"];		
		$lb_valido=$this->uf_select_lineassublineas($ls_codcla,$la_detallesviejos,$li_totalfilasviejas);
		//print "viejos-codded:".$aa_detallesnuevos["codded"];
		//print_r($la_detallesviejos);	
		$li_totalnuevas=$ai_totalfilasnuevas-1; //-1	  	 
		for ($li_i=1;$li_i<=$li_totalnuevas;$li_i++)
		{
			$lb_existe=false;
			for ($li_j=1;$li_j<=$li_totalfilasviejas;$li_j++)
			{
				if ($la_detallesviejos["cod_sub"][$li_j] ==$aa_detallesnuevos["cod_sub"][$li_i]) 
				//if ($la_detallesviejos["codemp"][$li_j]==$ls_codemp && $la_detallesviejos["codcli"][$li_j]==$ls_codcli)
				{	
					
					if($la_detallesviejos["cod_sub"][$li_j] != $aa_detallesnuevos["cod_sub"][$li_i])
					{
					  $lb_update=true;
					}
					$lb_existe = true;
				}						
			}	
			if (!$lb_existe)
			 {
			  $ls_codsub=$aa_detallesnuevos["cod_sub"][$li_i];
			  $ls_descsub=$aa_detallesnuevos["descsub"][$li_i];
			  $this->uf_guardar_lineassublineas($ls_codcla,$ls_codsub,$ls_descsub,$aa_seguridad);
			  }
			 if ($lb_update)
			{
			
			$ls_codsub=$aa_detallesnuevos["cod_sub"][$li_i];
			$ls_descsub=$aa_detallesnuevos["descsub"][$li_i];					
			$this->uf_update_sublineas($ls_codcla,$ls_codsub,$ls_descsub,$aa_seguridad);
			
			}
		}
		for ($li_j=1;$li_j<=$li_totalfilasviejas;$li_j++)
		{
			$lb_existe=false;
			for ($li_i=1;$li_i<=$li_totalnuevas;$li_i++)
			{
				if ($la_detallesviejos["codcla"][$li_j]==$ls_codcla &&$la_detallesviejos["cod_sub"][$li_j] ==$aa_detallesnuevos["cod_sub"][$li_i]) 
				{					
					$lb_existe = true;
				}				
			}
			if (!$lb_existe)
			{
				$this->uf_delete_lineassublineas($ls_codcla,$la_detallesviejos["cod_sub"][$li_j]);
				}
		}
	}
}
?>
