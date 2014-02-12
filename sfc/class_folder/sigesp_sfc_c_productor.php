<?php
class sigesp_sfc_c_productor
{
 var $io_funcion;
 var $io_msgc;
 var $io_sql;
 var $datoemp;
 var $io_msg; 
function sigesp_sfc_c_productor()
{
	require_once("sigesp_sob_c_funciones_sob.php"); /** Se toma la funcion de convertir cadena a caracteres **/
	require_once ("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	require_once("../shared/class_folder/class_datastore.php");
	$this->seguridad=   new sigesp_c_seguridad();
	$this->io_funcion = new class_funciones();		
	$io_include = new sigesp_include();
	$io_connect = $io_include->uf_conectar();		
	$io_sql=new class_sql($io_connect);
	$this->datoemp=$_SESSION["la_empresa"];	
	require_once("../shared/class_folder/class_mensajes.php");
	$this->io_msg=new class_mensajes();
	$this->funsob=   new sigesp_sob_c_funciones_sob();
	$this->io_datastore= new class_datastore();
	$this->io_sql= new class_sql($io_connect);

}		
	function uf_select_productor($as_codcli)
	{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_unidad
		// Parameters:  - $ls_codtob( Codigo del Tipo de Obra).		
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////	
	
	    $ls_codemp=$this->datoemp["codemp"];
		$ls_cadena="SELECT * FROM sfc_productor 
		            WHERE codemp='".$ls_codemp."' AND codcli='".$as_codcli."'";
		$rs_datauni=$this->io_sql->select($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_productor".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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
	

	function uf_guardar_productor($as_codcli,$as_nrocarta,$as_hect,$as_hectprod,$as_hectsin,$as_codtenencia,$aa_seguridad)
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
		$ls_hect=$this->funsob->uf_convertir_cadenanumero($ls_hect);		
		$ls_hectprod=$this->funsob->uf_convertir_cadenanumero($ls_hectprod);		
		$ls_hectsin=$this->funsob->uf_convertir_cadenanumero($ls_hectsin);		
		$lb_existe=$this->uf_select_productor($as_codcli);	
		
		$as_hect = str_replace(".","",$as_hect); 
		$as_hect = str_replace(",",".",$as_hect);			
					 		
		$as_hectprod = str_replace(".","",$as_hectprod); 
		$as_hectprod = str_replace(",",".",$as_hectprod);			

		$as_hectsin = str_replace(".","",$as_hectsin); 
		$as_hectsin = str_replace(",",".",$as_hectsin);			
		if(!$lb_existe)
		{
            $ls_cadena= "INSERT INTO sfc_productor (codemp,codcli,nro_cartagr,hect_tot,hect_prod,hect_sinprod,codtenencia) 
			              VALUES ('".$ls_codemp."','".$as_codcli."','".$as_nrocarta."','".$as_hect."',
						          '".$as_hectprod."','".$as_hectsin."','".$as_codtenencia."')";
			$this->io_msgc="Registro Incluido!!!";				
			$ls_evento="INSERT";	
		}
		else
		{
			$ls_cadena= "UPDATE sfc_productor
			             SET    nro_cartagr='".$as_nrocarta."', hect_tot='".$as_hect."', hect_prod='".$as_hectprod."', 
						        hect_sinprod='".$as_hectsin."', codtenencia='".$as_codtenencia."' 
						 WHERE  codemp='".$ls_codemp."'   AND   codcli='".$as_codcli."'" ;
			$ls_evento="UPDATE";
		}
		//$this->io_sql->begin_transaction();
		$li_numrows=$this->io_sql->execute($ls_cadena);

		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_guardar_productor".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_msgc;
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
       	}
		else
		{
			if($li_numrows>0)
			{
			   // $this->io_sql->commit();
				if($ls_evento=="INSERT")
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion ="Insert el Productor ".$ls_codcli." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="UPDATE";
					$ls_descripcion ="Actualiz el Productor ".$ls_codcli." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				}
				$lb_valido=true;
				$this->io_msgc="Registro Actualizado!!!";
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

	function uf_delete_productor($ls_codcli,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_delete_conceptos
		// Parameters:  - $ls_codigo( Codigo del concepto).		
		// Descripcion: - Funcion que elimina la unidad de medida.
		//////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_productor($ls_codcli);
		
		if($lb_existe)
		{
		    	$ls_cadena= " DELETE FROM sfc_productor
							  WHERE codemp='".$ls_codemp."' AND codcli='".$ls_codcli."'";
				$this->io_sql->begin_transaction();
				$li_numrows=$this->io_sql->execute($ls_cadena);	
				if(($li_numrows==false)&&($this->io_sql->message!=""))
				{
					$this->io_sql->rollback();
					$lb_valido=false;
				}
				else
				{
					if($li_numrows>0)
					{
					    $this->io_sql->commit();
						$lb_valido=true;
						////////////////////////////////         SEGURIDAD               /////////////////////////////
						$ls_evento="DELETE";
						$ls_descripcion ="Elimin el Productor ".$ls_codcli." Asociado a la Empresa ".$ls_codemp;
						$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               ////////////////////////////			
					}
					else
					{
						$lb_valido=false;
						//$this->is_msgc="Registro No Eliminado!!!";
						$this->io_sql->rollback();
					}
	
				}
		}
		else
		{
			$lb_valido=1;
		//	$this->io_msg->message("El Registro no Existe");
		}
		return $lb_valido;		
	}

/**********************************************************************************************************************************/
/************************************************  RUBROS AGRCOLAS POR PRODUCTOR *********************************************/
/**********************************************************************************************************************************/

/******************************************************************************************************/ 
/*********************** SELECT RUBROS AGRCOLAS POR PRODUCTOR  *******************************/
/******************************************************************************************************/ 
function uf_select_rubroproductor($ls_codcli,&$aa_data,&$ai_rows)
	{
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$ls_sql="SELECT  *
				 FROM sfc_rubroagri_cliente
				 WHERE codcli='".$ls_codcli."' AND codemp='".$ls_codemp."';";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select rubroproductor ".$this->io_function->uf_convertirmsg($this->io_sql->message);
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
  /*********************** GUARDAR AGRCOLAS POR PRODUCTOR  ************************************/
  /*****************************************************************************************/ 
	function uf_guardar_rubroproductor($ls_codcli,$ls_nrocarta,$ls_codrubro,$ls_hectprod,$ls_cantprod,$ls_estarub,$ls_idclasificacion,$ls_codclasificacion,$aa_seguridad)
    { 
	   	$lb_valido   = false;
		$ls_codemp   = $this->datoemp["codemp"];
		$ls_hectprod = $this->funsob->uf_convertir_cadenanumero($ls_hectprod);
		$ls_cantprod = $this->funsob->uf_convertir_cadenanumero($ls_cantprod);
		$ls_animal   = $this->funsob->uf_convertir_cadenanumero($ls_animal);
		
		$ls_sql= "INSERT INTO sfc_rubroagri_cliente (codemp,codcli,nro_cartagr,id_clasificacion,cod_clasificacion,
				  hect_prod,cant_pro,estarub) VALUES ('".$ls_codemp."','".$ls_codcli."',
				  '".$ls_nrocarta."','".$ls_idclasificacion."','".$ls_codclasificacion."',".$ls_hectprod.",".$ls_cantprod.",'".$ls_estarub."')";
		//$this->io_sql->begin_transaction();	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{				
		    $lb_valido=false;
			$this->is_msgc="Error en metodo uf_guardar_rubroproductor".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_msgc;
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($li_row>0)
			{
			 //   $this->io_sql->commit();
				$ls_evento="INSERT";
				$ls_descripcion ="Insert el Rubro ".$ls_codrubro.",del Productor ".$ls_codcli." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion); 
				$lb_valido=true;
			}
			else
			{				
				$this->io_sql->rollback();
			}		
		}		
		return $lb_valido;
	}	
	function uf_buscar_idclasificacion($ls_codrubro,&$ls_idclasificacion,&$ls_codclasificacion)
	{
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$ls_sql="SELECT id_clasificacion,cod_clasificacion
				 FROM   sfc_clasificacionrubro
				 WHERE  cod_rubro='".$ls_codrubro."' AND codemp='".$ls_codemp."';";
		$rs=$this->io_sql->select($ls_sql);
		if($rs==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_productor".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs))
			{
				$lb_valido=true;
				$ls_idclasificacion =($row["id_clasificacion"]);
				$ls_codclasificacion=($row["cod_clasificacion"]);
			}
			else
			{
				$lb_valido=false;
				$this->io_msgc="Registro no encontrado";
			}
		}
		return $lb_valido;
	}
  /*****************************************************************************************/ 
  /*********************** BORRAR TODOS LOS RUBROS AGRCOLAS POR PRODUCTOR******/
  /*****************************************************************************************/ 
	function uf_delete_rubroproductor($ls_codcli,$aa_seguridad)
	{
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$ls_sql= "DELETE FROM sfc_rubroagri_cliente WHERE codemp='".$ls_codemp."' AND  codcli='".$ls_codcli."' ;";		
		//$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_sql->rollback();			
			print"Error en metodo eliminar_rubrocliente ".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			//$this->io_sql->commit();
			$ls_evento="DELETE";
			$ls_descripcion ="Elimin los Rubros, del Cliente ".$ls_codcli." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
			$lb_valido=true;
		}
		return $lb_valido;	
	
	}	
  /*****************************************************************************************/ 
  /**********BORRAR UNA RUBROS AGRCOLAS POR PRODUCTOR DE UN CLIENTE **********/
  /*****************************************************************************************/ 
	function uf_delete_rubrosproductor($ls_codcli,$aa_seguridad)
	{ 
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$ls_sql= "DELETE 
		          FROM  sfc_rubroagri_cliente 
		          WHERE codemp='".$ls_codemp."' 
				  AND   codcli='".$ls_codcli."' ";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_sql->rollback();			
			print"Error en metodo eliminar_rubrosproductor ".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
   		    $this->io_sql->commit();
			$ls_evento="DELETE";
			$ls_descripcion ="Elimin el Rubro ".$ls_codrubro.", del Cliente ".$ls_codcli." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
			$lb_valido=true;
		}
		return $lb_valido;		
	} 
  /*****************************************************************************************/ 
  /*********************** UPDATE DETALLES RUBROS AGRCOLAS POR PRODUCTOR********/
  /*****************************************************************************************/ 
	function uf_update_rubroproductor($ls_codcli,$ls_nrocarta,$ls_id_clasificacion,$ls_cod_clasificacion,$ls_hectprod,$ls_cantprod,$ls_estarub,$aa_seguridad)
	 {	
		$lb_valido=false;	
		$ls_hectprod=$this->funsob->uf_convertir_cadenanumero($ls_hectprod);
		$ls_cantprod=$this->funsob->uf_convertir_cadenanumero($ls_cantprod);
		$ls_animal=$this->funsob->uf_convertir_cadenanumero($ls_animal);
		$ls_sql="UPDATE sfc_rubroagri_cliente 
				 SET    hect_prod='".$ls_hectprod."', cant_pro='".$ls_cantprod."',estarub='".$ls_estarub."'
				 WHERE  codcli='".$ls_codcli."' 	
				 AND    nro_cartagr='".$ls_nrocarta."' 
				 AND    id_clasificacion='".$ls_id_clasificacion."' 	
				 AND    cod_clasificacion='".$ls_cod_clasificacion."'  ";				 
		$this->io_sql->begin_transaction();	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{		
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_update_rubroproductor".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_msgc;
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($li_row>0)
			{
			    $this->io_sql->commit();
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualiz el rubro ".$ls_codrubro.", del Cliente ".$ls_codcli." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion); 
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
  /*********************** UPDATE ARREGLO DE CLIENTE RUBROS  **************************************/
  /****************************************************************************************************/ 
  
  /*Revisar, este llama a los metodos anteriores*/
	function uf_update_rubrosproductor($ls_codcli,$aa_detallesnuevos,$as_tentierra,$ai_totalfilasnuevas,$aa_seguridad)
	{
		$lb_valido=false;	
		$ls_codemp=$this->datoemp["codemp"];
		$lb_valido=$this->uf_select_rubroproductor($ls_codcli,$la_detallesviejos,$li_totalfilasviejas);
		$li_totalnuevas=$ai_totalfilasnuevas-1; //-1	
		
		if($lb_valido)
		{
		   $lb_valido=$this->uf_delete_rubroproductor($ls_codcli,$aa_seguridad);
		}
		
		for ($li_i=1;$li_i<=$li_totalnuevas;$li_i++)
		{
			  $ls_nrocarta  = $aa_detallesnuevos["nro_cartagr"][$li_i];
			  $ls_codrubro  = $aa_detallesnuevos["cod_rubro"][$li_i];
			  $ls_codclaagri= $aa_detallesnuevos["cod_claagri"][$li_i];
			  $ls_hectprod  = $aa_detallesnuevos["hect_prod"][$li_i];
			  $ls_cantprod  = $aa_detallesnuevos["cant_prod"][$li_i];
			  $ls_nroprod   = $aa_detallesnuevos["nro_prod"][$li_i];
			  $la_desc_tipo = $aa_detallesnuevos["desc_tipo"][$li_i];		
			  $ls_estarub   = $aa_detallesnuevos["estarub"][$li_i];
			  
			  $this->uf_guardar_rubroproductor($ls_codcli,$ls_nrocarta,$ls_codrubro,$ls_hectprod,
			                                $ls_cantprod,$ls_estarub,$ls_codrubro,$ls_codclaagri,$aa_seguridad);
			  
		}
	}
	
/**********************************************************************************************************************************/
/************************************************  RUBROS PECUARIOS POR PRODUCTOR *********************************************/
/**********************************************************************************************************************************/

/******************************************************************************************************/ 
/*********************** SELECT RUBROS PECUARIOS POR PRODUCTOR  *******************************/
/******************************************************************************************************/ 
function uf_select_rubropecproductor($ls_codcli,&$aa_data,&$ai_rows)
	{
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$ls_sql="SELECT  *
				 FROM sfc_rubropec_cliente
				 WHERE codcli='".$ls_codcli."' AND codemp='".$ls_codemp."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select rubropecproductor ".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$ai_rows=$this->io_sql->num_rows($rs_data);    //devuelve numero de filas
				$aa_data=$this->io_sql->obtener_datos($rs_data); // devuelve datos
			}
			else
			{
				$ai_rows=0;
				$aa_data="";
			}			
		}		
		return $lb_valido;
	}
  /*****************************************************************************************/ 
  /*********************** GUARDAR AGRCOLAS POR PRODUCTOR  ************************************/
  /*****************************************************************************************/ 
	function uf_guardar_rubropecproductor($ls_codcli,$ls_nrocarta,$ls_codrubro,$ls_cla_rubro,$ls_hectprod,$ls_cantprod,$ls_animal,$aa_seguridad)
    { 
	   	$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$ls_hectprod=$this->funsob->uf_convertir_cadenanumero($ls_hectprod);
		$ls_cantprod=$this->funsob->uf_convertir_cadenanumero($ls_cantprod);
		$ls_animal=$this->funsob->uf_convertir_cadenanumero($ls_animal);
		
		$ls_sql= "INSERT INTO sfc_rubropec_cliente (codemp,codcli,nro_cartagr,id_clasificacion,cod_clasificacion,
				  hect_prod,cant_pro,nro_animales) VALUES ('".$ls_codemp."','".$ls_codcli."',
				  '".$ls_nrocarta."','".$ls_codrubro."','".$ls_cla_rubro."','".$ls_hectprod."',
				  '".$ls_cantprod."','".$ls_animal."')";	
	    $this->io_sql->begin_transaction();	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{				
		    $lb_valido=false;
			$this->is_msgc="Error en metodo uf_guardar_rubropecproductor".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_msgc;
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($li_row>0)
			{
			   $this->io_sql->commit();
			   $ls_evento="INSERT";
			   $ls_descripcion ="Insert el Rubro Pecuario ".$ls_codrubro.", del Cliente ".$ls_codcli." Asociado a la Empresa ".$ls_codemp;
			   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion); 
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
  /*********************** BORRAR TODOS LOS RUBROS AGRCOLAS POR PRODUCTOR******/
  /*****************************************************************************************/ 
	function uf_delete_rubropecproductor($ls_codcli,$aa_seguridad)
	{
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$ls_sql= "DELETE FROM sfc_rubropec_cliente WHERE codemp='".$ls_codemp."' AND codcli='".$ls_codcli."';";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_sql->rollback();			
			print"Error en metodo eliminar_rubropeccliente ".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			$this->io_sql->commit();
			$ls_evento="DELETE";
			$ls_descripcion ="Elimin los Rubros Pecuarios, del Cliente ".$ls_codcli." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
			$lb_valido=true;
		}
		return $lb_valido;	
	
	}	
  /*****************************************************************************************/ 
  /**********BORRAR UNA RUBROS AGRCOLAS POR PRODUCTOR DE UN CLIENTE **********/
  /*****************************************************************************************/ 
	function uf_delete_rubrospecproductor($ls_codcli,$ls_codrubro,$aa_seguridad)
	{
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];	
		$ls_sql= "DELETE 
		          FROM  sfc_rubropec_cliente 
		          WHERE codemp='".$ls_codemp."' 
				  AND   codcli='".$ls_codcli."' AND id_rubro='".$ls_codrubro."'";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_sql->rollback();			
		}
		else
		{
		    $this->io_sql->commit();
			///*************    SEGURIDAD    **************		 
			$ls_evento="DELETE";
			$ls_descripcion ="Elimin el Rubro Pecuario ".$ls_codrubro.", del Cliente ".$ls_codcli." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
			/********************************************/	
			$lb_valido=true;
		}
		return $lb_valido;		
	} 
  /*****************************************************************************************/ 
  /*********************** UPDATE DETALLES RUBROS AGRCOLAS POR PRODUCTOR********/
  /*****************************************************************************************/ 
	function uf_update_rubropecproductor($ls_codcli,$ls_nrocarta,$ls_codrubro,$ls_clapec,$ls_hectprod,$ls_cantprod,$ls_animal,$aa_seguridad)
	 {	
		$lb_valido=false;	
		$ls_hectprod=$this->funsob->uf_convertir_cadenanumero($ls_hectprod);
		$ls_cantprod=$this->funsob->uf_convertir_cadenanumero($ls_cantprod);
		$ls_animal=$this->funsob->uf_convertir_cadenanumero($ls_animal);
		$ls_sql="UPDATE sfc_rubropec_cliente 
				 SET    hect_prod='".$ls_hectprod."', cant_pro='".$ls_cantprod."',nro_animales='".$ls_animal."' 
				 WHERE  codcli            = '".$ls_codcli."'  
				 AND    nro_cartagr       = '".$ls_nrocarta."'
				 AND    id_clasificacion  = '".$ls_codrubro."' 	
				 AND    cod_clasificacion = '".$ls_clapec."' ";	
		$this->io_sql->begin_transaction();	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{		
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_update_rubropecproductor".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_msgc;
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($li_row>0)
			{
			    $this->io_sql->commit();
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualiz el Rubro Pecuario ".$ls_codrubro.", del Cliente ".$ls_codcli." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion); 
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
  /*********************** UPDATE ARREGLO DE CLIENTE RUBROS  **************************************/
  /****************************************************************************************************/ 
  
  /*Revisar, este llama a los metodos anteriores*/
	function uf_update_rubrospecproductor($ls_codcli,$aa_detallesnuevos,$ai_totalfilasnuevas,$aa_seguridad)
	{
		$lb_valido=false;		
		$ls_codemp=$this->datoemp["codemp"];
		$lb_valido=$this->uf_select_rubropecproductor($ls_codcli,$la_detallesviejos,$li_totalfilasviejas);
		$li_totalnuevas=$ai_totalfilasnuevas-1; //-1	  	 
		
		if($lb_valido)
		{
		   $lb_valido=$this->uf_delete_rubropecproductor($ls_codcli,$aa_seguridad);
		   if($lb_valido)
		   {
		        $lb_existe=false;
		   }
		}
		
		for ($li_i=1;$li_i<=$li_totalnuevas;$li_i++)
		{
				  $ls_nrocarta  = $aa_detallesnuevos["nro_cartagr"][$li_i];
				  $ls_codrubro  = $aa_detallesnuevos["cod_rubro"][$li_i];
				  $ls_cla_rubro = $aa_detallesnuevos["cla_rubro"][$li_i];
				  $ls_hectprod  = $aa_detallesnuevos["hect_prod"][$li_i];
				  $ls_cantprod  = $aa_detallesnuevos["cant_prod"][$li_i];
				  $ls_nroprod   = $aa_detallesnuevos["nro_prod"][$li_i];
				  $la_desc_tipo = $aa_detallesnuevos["desc_tipo"][$li_i];			 
				  $ls_animal=$aa_detallesnuevos["nro_animales"][$li_i];
				  $this->uf_guardar_rubropecproductor($ls_codcli,$ls_nrocarta,$ls_codrubro,$ls_cla_rubro,$ls_hectprod,$ls_cantprod,$ls_animal,$aa_seguridad);
		}
	}
	
}// FIN DE LA CLASE
?>
