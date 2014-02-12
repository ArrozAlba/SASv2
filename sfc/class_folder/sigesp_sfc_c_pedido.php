<?php
 //////////////////////////////////////////////////////////////////////////////////////////
 // Clase:       - sigesp_sfc_c_pedido
 // Autor:       - Ing. Zulheymar Rodr�guez
 // Descripcion: - Clase que realiza los procesos basicos (insertar,actualizar,eliminar) con
 //                respecto a la tabla sfc_pedido.
 // Fecha:       - 28/08/2007
 //////////////////////////////////////////////////////////////////////////////////////////
class sigesp_sfc_c_pedido
{
 var $io_funcion;
 var $io_msgc;
 var $io_sql;
 var $datoemp;
 var $io_msg;
function sigesp_sfc_c_pedido()
{
	require_once ("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	require_once("sigesp_sob_c_funciones_sob.php"); /** Se toma la funcion de convertir cadena a caracteres **/
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/class_mensajes.php");
	$this->funsob=   new sigesp_sob_c_funciones_sob();
	$this->seguridad=   new sigesp_c_seguridad();
	$this->io_funcion = new class_funciones();
	$io_include = new sigesp_include();
	$io_connect = $io_include->uf_conectar();
	$this->io_sql= new class_sql($io_connect);
	$this->datoemp=$_SESSION["la_empresa"];
	$this->io_msg=new class_mensajes();
	$io_datastore=new class_datastore();
}
function uf_select_pedido($ls_numped,$ls_codtie)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_pedido
		// Parameters:  - $ls_numped( Codigo del Pedido).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////
	    $ls_codemp=$this->datoemp["codemp"];
		$ls_sql="SELECT * FROM sfc_pedido
		            WHERE codemp='".$ls_codemp."' AND numpedido='".$ls_numped."' AND codtiend='".$ls_codtie."';";
		$rs_datauni=$this->io_sql->select($ls_sql);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_pedido ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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
function uf_guardar_pedido($ls_codtie,$ls_numped,$ls_codusu,$ls_fecped,$ls_obsped,$ls_estped,$aa_seguridad,$ls_status)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_guardar_pedido
		// Parameters:  - $ls_codtie( Codigo de la Tienda).
		//			    - $ls_numped(C�digo del Pedido).
		//			    - $ls_codusu( c�digo del usuario).
		//				- $ls_fecped(Fecha del Pedido).
		//				- $ls_obsped(Observaci�n).
		//              - $ls_estped(Estatus del Pedido).
		// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
		//////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_pedido($ls_numped,$ls_codtie);
		$ls_fecped=$this->io_funcion->uf_convertirdatetobd($ls_fecped);
		if(!$lb_existe)
		{
            $ls_sql= "INSERT INTO sfc_pedido (codemp,numpedido,codusu,fecpedido,obspedido,estpedido,codtiend) VALUES ('".$ls_codemp."','".$ls_numped."','".$ls_codusu."','".$ls_fecped."','".$ls_obsped."','".$ls_estped."','".$ls_codtie."')";

			$this->io_msgc="Registro Incluido!!!";
			$ls_evento="INSERT";
		}
		else
		{

			$ls_sql= "UPDATE sfc_pedido
			             SET  codusu='".$ls_codusu."',fecpedido='".$ls_fecped."',estpedido='".$ls_estped."',obspedido='".$ls_obsped."' WHERE codemp='".$ls_codemp."' AND numpedido='".$ls_numped."' AND codtiend='".$ls_codtie."';";

			$this->io_msgc="Registro Actualizado PEDIDO!!!";
			$ls_evento="UPDATE";
		}
        //print $ls_sql;
		$this->io_sql->begin_transaction();
		$li_numrows=$this->io_sql->execute($ls_sql);


		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_guardar_pedido".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_sql->message;
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
					$ls_descripcion ="Insert� el Predido ".$ls_numped." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="UPDATE";
					$ls_descripcion ="Actualiz� el Predido ".$ls_numped." Asociado a la Empresa ".$ls_codemp;
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
function uf_update_pedidostatus($ls_numped,$ls_estped,$ls_codtie,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_guardar_pedido
		// Parameters:  - $ls_numped(C�digo del Pedido).
		//              - $ls_estped(Estatus del Pedido).
		// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
		//////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_pedido($ls_numped,$ls_codtie);

       // if ($ls_numcot=='0000000000000000000000000') $ls_estcot='E';

			$ls_sql= "UPDATE sfc_pedido ".
			             "SET estpedido='".$ls_estped."' ".
						 "WHERE codemp='".$ls_codemp."' AND numpedido='".$ls_numped."' AND codtiend='".$ls_codtie."';";

			$this->io_msgc="Registro Actualizado EST!!!";
			$ls_evento="UPDATE";

       // print $ls_sql."   pedidostatus";
		$this->io_sql->begin_transaction();
		$li_numrows=$this->io_sql->execute($ls_sql);


		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_update_pedidostatus".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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
					$ls_descripcion ="Insert� el Status del Predido ".$ls_numped." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="UPDATE";
					$ls_descripcion ="Actualiz� el Status del Predido ".$ls_numped." Asociado a la Empresa ".$ls_codemp;
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


function uf_delete_pedido($ls_numped,$ls_codtie,$aa_seguridad)
{
	//////////////////////////////////////////////////////////////////////////////////////////
	// Function:    - uf_delete_pedido
	// Parameters:  - $ls_numped(C�digo del Pedido).
	// Descripcion: - Funcion que elimina un pedido.
	//////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$this->datoemp["codemp"];
	$lb_existe=$this->uf_select_pedido($ls_numped,$ls_codtie);

	if($lb_existe)
	{
			$ls_sql= " DELETE FROM sfc_pedido WHERE codemp='".$ls_codemp."' AND numpedido='".$ls_numped."' AND codtiend='".$ls_codtie."' ";
			$this->io_msgc="Registro Eliminado!!!";

			$this->io_sql->begin_transaction();

			$li_numrows=$this->io_sql->execute($ls_sql);

			if(($li_numrows==false)&&($this->io_sql->message!=""))
			{
				$lb_valido=false;
				$this->io_sql->rollback();
				$this->io_msgc="Error en metodo uf_delete_pedido ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
				print $this->io_msgc;
				print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
				//print $ls_sql;
			}
			else
			{
				if($li_numrows>0)
				{
					$lb_valido=true;
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="DELETE";
					$ls_descripcion ="Elimin� el Predido ".$ls_numped." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
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
/****************************************************  PEDIDO DETALLES ********************************************************/
/******************************************************************************************************************************/
function uf_select_detpedido($ls_numped,$ls_codtie)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_detpedido
		// Parameters:  - $ls_numped( Codigo del Pedido).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

	    $ls_codemp=$this->datoemp["codemp"];
		$ls_sql="SELECT * FROM sfc_detpedido
		            WHERE codemp='".$ls_codemp."' AND numpedido='".$ls_numped."' AND codtiend='".$ls_codtie."';";
		$rs_datauni=$this->io_sql->select($ls_sql);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_detpedido ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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

function uf_select_detproducto($ls_numped,$ls_codpro,$ls_codtie)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_detproducto
		// Parameters:  - $ls_codpro( Codigo del producto).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

	    $ls_codemp=$this->datoemp["codemp"];
		$ls_sql="SELECT * FROM sfc_detpedido
		            WHERE codemp='".$ls_codemp."' AND numpedido='".$ls_numped."' AND codart='".$ls_codpro."' AND codtiend='".$ls_codtie."';";
		$rs_datauni=$this->io_sql->select($ls_sql);

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
function uf_guardar_detpedido($ls_numped,$ls_codpro,$ls_canped,$ls_codalm,$ls_codtie,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_guardar_detpedido
		// Parameters:  - $ls_numped(C�digo del Pedido).
		//			    - $ls_codpro( c�digo del producto).
		//				- $ls_cancot (cantidad de producto).
		//				- $ls_codtie( C�digo de la Tienda donde se hizo el Pedido).
		// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
		//////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_detproducto($ls_numped,$ls_codpro,$ls_codtie);
		$ls_canped=$this->funsob->uf_convertir_cadenanumero($ls_canped); /* convierte cadena en numero */
		if ($lb_existe)
		    {
				$ls_sql= "UPDATE sfc_detpedido SET canped=".$ls_canped.",codalm='".$ls_codalm."' WHERE codemp='".$ls_codemp."' AND codart='".$ls_codpro."' AND numpedido='".$ls_numped."' AND codtiend='".$ls_codtie."';";
			//print $ls_sql;
			$this->io_msgc="Registro Actualizado!!!";
			$ls_evento="UPDATE";
			}
		  else
			{
	            $ls_sql= "INSERT INTO sfc_detpedido (codemp,codtiend,numpedido,codart,canped,codalm) VALUES ('".
				            $ls_codemp."','".$ls_codtie."','".$ls_numped."','".$ls_codpro."',".$ls_canped.",'".$ls_codalm."')";
     		//print $ls_sql;
			$this->io_msgc="Registro Incluido!!!";
			$ls_evento="INSERT";
		    }

       // print $ls_sql."detpedido";
		$this->io_sql->begin_transaction();
		$li_numrows=$this->io_sql->execute($ls_sql);


		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_guardar_detpedido".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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
					$ls_descripcion ="Insert� el Detalle ".$ls_codpro." del Pedido ".$ls_numped." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="UPDATE";
					$ls_descripcion ="Actualiz� el Detalle ".$ls_codpro." del Pedido ".$ls_numped." Asociado a la Empresa ".$ls_codemp;
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
				if ($lb_existe)
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


function uf_delete_detpedido($ls_numped,$ls_codtie,$aa_seguridad)
{
	//////////////////////////////////////////////////////////////////////////////////////////
	// Function:    - uf_delete_detpedido
	// Parameters:  - $ls_numped(C�digo del Pedido).
	// Descripcion: - Funcion que elimina un Pedido.
	//////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$this->datoemp["codemp"];
	$lb_existe=$this->uf_select_detpedido($ls_numped,$ls_codtie);

	if($lb_existe)
	{
			$ls_sql= " DELETE FROM sfc_detpedido
						  WHERE codemp='".$ls_codemp."' AND numpedido='".$ls_numped."' AND codtiend='".$ls_codtie."'";

			//print $ls_sql;
			$this->io_msgc="Registro Eliminado!!!";

			$this->io_sql->begin_transaction();

			$li_numrows=$this->io_sql->execute($ls_sql);

			if(($li_numrows==false)&&($this->io_sql->message!=""))
			{
				$lb_valido=false;
				$this->io_sql->rollback();


				$this->io_msgc="Error en metodo uf_delete_detpedido ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
				print $this->io_msgc;
				print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
				//print $ls_sql;
			}
			else
			{
				if($li_numrows>0)
				{
					$lb_valido=true;
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="DELETE";
					$ls_descripcion ="Elimin� los Detalles del Pedido ".$ls_numped." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
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
/*****************************************************************************************************/
/*************************** SELECT DETALLES PEDIDO **************************************************/
/*****************************************************************************************************/
function uf_select_detallesped ($ls_numped,$ls_codtie,&$aa_data,&$ai_rows)
	{
	 /***************************************************************************************/
	 /*	Function:	    uf_select_detallesped                                               */
     /* Access:			public                                                              */
	 /*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro   */
	 /*	Description:	Funcion que se encarga de 									        */
	 /*  Fecha:         28/08/2007                   	                                    */
	 /*	Autor:          Ing. Zulheymar Rodr�guez                                            */
	 /***************************************************************************************/

		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$ls_sql="SELECT  *
				 FROM sfc_detpedido
				 WHERE codemp='".$ls_codemp."' AND numpedido='".$ls_numped."' and codtiend='".$ls_codtie."';";
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select detallesped".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$ai_rows=$this->io_sql->num_rows($rs_data);    //devuelve numero de filas
				$aa_data=$this->io_sql->obtener_datos($rs_data); // devuelve datos
				//print_r($aa_data);
			}else
			{
				$ai_rows=0;
				$aa_data="";
			}
		}
		return $lb_valido;
	}
/*****************************************************************************************/
/************************** GUARDAR DETALLES PEDIDO **************************************/
/*****************************************************************************************/
function uf_guardar_detallesped($ls_numped,$ls_codpro,$ls_canped,$ls_codproveedor,$ls_codalm,$ls_codtie,$aa_seguridad)
{
 /***************************************************************************************/
 /*	Function:	    uf_guardar_detallesped                                               */
 /* Access:			public                                                              */
 /*	Returns:		Boolean, Retorna true si guard� el registro						*/
 /*	Description:	Funcion que se encarga de 									        */
 /*  Fecha:         28/08/2007                   	                                    */
 /*	Autor:          Ing. Zulheymar Rodr�guez                                            */
 /***************************************************************************************/
	$lb_valido=false;
	$ls_codemp=$this->datoemp["codemp"];
	$ls_canped=$this->funsob->uf_convertir_cadenanumero($ls_canped); /* convierte cadena en numero */
	$ls_sql= "INSERT INTO sfc_detpedido (codemp,codtiend,numpedido,codart,cantped,codalm,cod_pro) VALUES ('".$ls_codemp."','".$ls_codtie."','".$ls_numped."','".$ls_codpro."',".$ls_canped.",'".$ls_codalm."','".$ls_codproveedor."')";
	//print $ls_sql;
	$this->io_sql->begin_transaction();
	$li_row=$this->io_sql->execute($ls_sql);
	if($li_row===false)
	{
	/****************************************************************************************************************/

		$lb_valido=false;
		$this->is_msgc="Error en metodo uf_guardar_detallesped".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		$this->io_sql->rollback();
		print $this->io_sql->message;
		print $this->io_funcion->uf_convertirmsg($this->io_sql->message);

	/******************************************************************************************************************/
		/*$this->io_msgc="Error en metodo uf_guardar_detallescot".$this->io_function->uf_convertirmsg($this->io_sql->message);
		$this->io_sql->rollback();
		print $this->io_msgc;
		print $this->io_funcion->uf_convertirmsg($this->io_sql->message);*/
	}
	else
	{
		if($li_row>0)
		{
			//************    SEGURIDAD    **************
			  $ls_evento="INSERT";
			  $ls_descripcion ="Insert� el Detalle ".$ls_codpro." del Pedido ".$ls_numped." Asociado a la Empresa ".$ls_codemp;
			   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
			//**********************************************/
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
/*********************** BORRAR DETALLES PEDIDO  *****************************************/
/*****************************************************************************************/
function uf_delete_detallesped($ls_numped,$ls_codpro,$ls_codtie,$aa_seguridad)
{
 /***************************************************************************************/
 /*	Function:	    uf_delete_detallesped                                               */
 /* Access:			public                                                              */
 /*	Returns:		Boolean, Retorna true si elimin� el registro					    */
 /*	Description:	Funcion que se encarga de 									        */
 /*  Fecha:         28/08/2007                   	                                    */
 /*	Autor:          Ing. Zulheymar Rodr�guez                                            */
 /***************************************************************************************/
	$lb_valido=false;
	$ls_codemp=$this->datoemp["codemp"];

	$ls_sql= "DELETE FROM sfc_detpedido WHERE codemp='".$ls_codemp."' AND numpedido='".$ls_numped."' AND codart='".$ls_codpro."' AND codtiend='".$ls_codtie."';";
print $ls_sql;
	$this->io_sql->begin_transaction();
	$li_row=$this->io_sql->execute($ls_sql);
	if($li_row===false)
	{
		$this->io_sql->rollback();

		//$this->io_msgc="Error en uf_select_detproducto ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);

		print"Error en metodo uf_delete_detallesped".$this->io_function->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		//*************    SEGURIDAD    **************
		$ls_evento="DELETE";
		$ls_descripcion ="Elimin� el Detalle ".$ls_codpro." del Pedido ".$ls_numped." Asociado a la Empresa ".$ls_codemp;
		$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
		//********************************************/
		$lb_valido=true;
		$this->io_sql->commit();
	}
	return $lb_valido;

}
/*****************************************************************************************/
/*********************** UPDATE DETALLES PEDIDO  *****************************************/
/*****************************************************************************************/
function uf_update_detallesped($ls_numped,$ls_codpro,$ls_canped,$ls_codproveedor,$ls_codalm,$ls_codtie,$aa_seguridad)
 {

	$lb_valido=false;
	$ls_codemp=$this->datoemp["codemp"];
	$ls_canped=$this->funsob->uf_convertir_cadenanumero($ls_canped); /* convierte cadena en numero */
	$ls_sql="UPDATE sfc_detpedido
			SET  cantped=".$ls_canped.",codalm='".$ls_codalm."', cod_pro='".$ls_codproveedor."'
			WHERE codemp='".$ls_codemp."' AND codart='".$ls_codpro."' AND numpedido='".$ls_numped."' AND codtiend='".$ls_codtie."';";

//print $ls_sql;
	$this->io_sql->begin_transaction();

	$li_row=$this->io_sql->execute($ls_sql);
	if($li_row===false)
	{
	/***********************************************************************************************************/
	$lb_valido=false;
		$this->is_msgc="Error en metodo uf_update_detallesped".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		$this->io_sql->rollback();
		print $this->io_sql->message;
		print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
	/************************************************************************************************************/


		/*print "Error en metodo uf_update_detallescot ".$this->io_function->uf_convertirmsg($this->io_sql->message);
		$this->io_sql->rollback();	*/
	}
	else
	{
		if($li_row>0)
		{
			//*************    SEGURIDAD    **************
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualiz� el Detalle ".$ls_codpro." del Pedido ".$ls_numped." Asociado a la Empresa ".$ls_codemp;
			   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
			//**********************************************/
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
/*********************** UPDATE ARREGLO DE DETALLES PEDIDO *******************************/
/*****************************************************************************************/
function uf_update_detallespedidos($ls_numped,$ls_codtie,$aa_detallesnuevos,$ai_totalfilasnuevas,$aa_seguridad,$as_status)
{
 /***************************************************************************************/
 /*	Function:	    uf_update_detallespedidos                                           */
 /* Access:			public                                                              */
 /*	Description:	Funcion que se encarga de cargar en un arreglo los detalles	        */
 /*  Fecha:         28/08/2007                   	                                    */
 /*	Autor:          Ing. Zulheymar Rodr�guez                                            */
 /***************************************************************************************/
	$lb_valido=false;
	$lb_update=false;
	$ls_codemp=$this->datoemp["codemp"];
	$lb_valido=$this->uf_select_detallesped($ls_numped,$ls_codtie,$la_detallesviejos,$li_totalfilasviejas);
	$li_totalnuevas=$ai_totalfilasnuevas-1;
	for ($li_i=1;$li_i<=$li_totalnuevas;$li_i++)
	{
		for ($li_j=1;$li_j<=$li_totalfilasviejas;$li_j++)
		{
			$lb_existe=false;
			if ($la_detallesviejos["codemp"][$li_j]==$ls_codemp && $la_detallesviejos["numpedido"][$li_j]==$ls_numped && $la_detallesviejos["codart"][$li_j] ==$aa_detallesnuevos["codart"][$li_i])
			{
				$li_cant_new=$this->funsob->uf_convertir_cadenanumero($aa_detallesnuevos["canpro"][$li_i]);
				$li_cant_ant=number_format($la_detallesviejos["cantped"][$li_j],2,".","");
			    if($la_detallesviejos["cantped"][$li_j] <> $li_cant_new)//ojo verificar campos
				{
				    $lb_update=true;
				}
				$lb_existe = true;
			}
		}

		if (!$lb_existe && $as_status=="")
		{
		   $ls_codtie=$aa_detallesnuevos["codtiend"][$li_i];
		   $ls_codpro=$aa_detallesnuevos["codart"][$li_i];
		   $ls_canped=$aa_detallesnuevos["canpro"][$li_i];
		   $ls_codproveedor=$aa_detallesnuevos["codproveedor"][$li_i];
		   $ls_codalm=$aa_detallesnuevos["codalm"][$li_i];
		   $this->uf_guardar_detallesped($ls_numped,$ls_codpro,$ls_canped,$ls_codproveedor,$ls_codalm,$ls_codtie,$aa_seguridad);
		}

		else
		{
		   $ls_codtie=$aa_detallesnuevos["codtiend"][$li_i];
		   $ls_codpro=$aa_detallesnuevos["codart"][$li_i];
		   $ls_canped=$aa_detallesnuevos["canpro"][$li_i];
		   $ls_codproveedor=$aa_detallesnuevos["codproveedor"][$li_i];
		   $ls_codalm=$aa_detallesnuevos["codalm"][$li_i];
		   $this->uf_update_detallesped($ls_numped,$ls_codpro,$ls_canped,$ls_codproveedor,$ls_codalm,$ls_codtie,$aa_seguridad);
		}
	}


	for ($li_j=1;$li_j<=$li_totalfilasviejas;$li_j++)
	{

		//print_r($la_detallesviejos);
		$lb_existe=false;
		for ($li_i=1;$li_i<=$li_totalnuevas;$li_i++)
		{


			if ($la_detallesviejos["codemp"][$li_j]=$ls_codemp && $la_detallesviejos["numpedido"][$li_j]=$ls_numped && $la_detallesviejos["codart"][$li_j] ==$aa_detallesnuevos["codart"][$li_i])
			{
				$lb_existe = true;
			}
		}
		//print $la_detallesviejos["codemp"][$li_j]."- PRO -".$la_detallesviejos["codart"][$li_j];
		if (!$lb_existe)
		{

			$this->uf_delete_detallesped($ls_numped,$la_detallesviejos["codart"][$li_j],$ls_codtie,$aa_seguridad);
		}
	}


}
}/*FIN DE LA CLASE */
?>
