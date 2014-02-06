<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/sigesp_c_seguridad.php");
require_once("../shared/class_folder/class_funciones_db.php");
require_once("../shared/class_folder/class_funciones.php");

class sigesp_sim_c_movimiento
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_sim_c_movimiento()
	{
		$in=              new sigesp_include();
		$this->con=       $in->uf_conectar();
		$this->io_sql=       new class_sql($this->con);
		$this->seguridad= new sigesp_c_seguridad();
		$this->fun=       new class_funciones_db($this->con);
		$this->io_msg=    new class_mensajes();
		$this->DS=        new class_datastore();
		$this->io_funcion=new class_funciones();
	}
	

	function uf_sim_select_movimiento($as_nummov,$as_fecmov)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_sim_select_movimiento
	//	Access:    public
	//	Arguments:
	//  as_nummov    // numero de movimiento
	//  as_fecmov    // fecha de movimiento
	//	Returns:		$lb_valido-----> true: encontrado false: no encontrado
	//	Description:  Esta funcion busca si existe un componente en la tabla de  sim_movimiento
	//              
	//////////////////////////////////////////////////////////////////////////////		

		global $fun;
		global $msg;
		$lb_valido=true;
		$ls_cadena="";
		$li_exec=-1;
		
		$this->is_msg_error = "";
		
		$this->io_sql->begin_transaction();
		$ls_sql = "SELECT * FROM sim_movimiento  ".
				  " WHERE nummov='".$as_nummov."'".
				  " AND fecmov='".$as_fecmov."'";
		
		$li_exec=$this->io_sql->select($ls_sql);

		if($li_exec===false)
		{
			$this->io_msg->message("CLASE->movimiento MÉTODO->uf_sim_select_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($li_exec))
			{
				$lb_valido=true;
				
			}
			else
			{
				$lb_valido=false;
			}
		}
			
		$this->io_sql->free_result($li_exec);
		return $lb_valido;

	}


	function uf_sim_insert_movimiento(&$as_nummov,$ad_fecmov,$as_nomsol,$as_codusu,$aa_seguridad)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_sim_insert_movimiento
	//	Access:    public
	//	Arguments:
	//  as_nummov    // numero de movimiento
	//  ad_fecmov    // fecha de movimiento
	//  as_nomsol    // nombre del solicitante
	//  as_codusu    // codigo del usuario
	//  aa_seguridad // arreglo de registro de seguridad
	//	Returns:		$lb_valido-----> true: operacion exitosa false: operacion no exitosa
	//	Description:  Esta funcion inserta un movimiento en la tabla de  sim_movimiento
	//              
	//////////////////////////////////////////////////////////////////////////////		
		global $fun;
		global $msg;
		$lb_valido=true;
		$ls_sql="";
		$li_exec=-1;
		
		$this->is_msg_error = "";
		//$this->io_sql->begin_transaction();

		$ls_emp="";
		$ls_empresa="";
		$ls_tabla="sim_movimiento";
		$ls_columna="nummov";
				
		$as_nummov=$this->fun->uf_generar_codigo($ls_emp,$ls_empresa,$ls_tabla,$ls_columna);
		
		
		$ls_sql="INSERT INTO sim_movimiento ( nummov, fecmov, nomsol, codusu)".
				" VALUES ('".$as_nummov."','".$ad_fecmov."','".$as_nomsol."','".$as_codusu."')";
		
		$li_exec=$this->io_sql->execute($ls_sql);
		if($li_exec===false)
		{
			$this->io_msg->message("CLASE->movimiento MÉTODO->uf_sim_insert_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			//$this->io_sql->rollback();

		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
/*				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Movimiento ".$as_nummov." de Fecha ".$ad_fecmov;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);*/
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				//$this->io_sql->commit();
		}
		
		return $lb_valido;

	} 

	function uf_sim_update_movimiento($as_nummov,$ad_fecmov,$as_nomsol,$as_codusu,$aa_seguridad)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_sim_update_movimiento
	//	Access:    public
	//	Arguments:
	//  as_nummov    // numero de movimiento
	//  as_fecmov    // fecha de movimiento
	//  as_nomsol    // nombre del solicitante
	//  as_codusu    // codigo del usuario
	//  aa_seguridad // arreglo de registro de seguridad
	//	Returns:		$lb_valido-----> true: operacion exitosa false: operacion no exitosa
	//	Description:  Esta funcion modifica un movimiento en la tabla de  sim_movimiento
	//              
	//////////////////////////////////////////////////////////////////////////////		

	 $lb_valido=true;
	 $ls_cadena="";
	 $li_exce=-1;


	
	 $ls_sql = "UPDATE sim_movimiento SET   nomsol='". $as_nomsol ."' ".
	 		    " WHERE nummov='" . $as_nummov ."'".
				" AND fecmov='" . $ad_fecmov ."'";
       // $this->io_sql->begin_transaction();
		$li_exec = $this->io_sql->execute($ls_sql);
		if($li_exec==false&&($this->io_sql->message!=""))
		{
			$this->io_msg->message("CLASE->movimiento MÉTODO->uf_sim_update_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			//$this->io_sql->rollback();

		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
/*			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el Movimiento ".$as_nummov." de Fecha ".$ad_fecmov;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);*/
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			//$this->io_sql->commit();
		}

 
	  return $lb_valido;

	} 

	function uf_sim_delete_movimiento($as_nummov,$as_fecmov,$aa_seguridad)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_sim_delete_movimiento
	//	Access:    public
	//	Arguments:
	//  as_nummov    // numero de movimiento
	//  as_fecmov    // fecha de movimiento
	//  aa_seguridad   // arreglo de registro de seguridad
	//	Returns:		$lb_valido-----> true: operacion exitosa false: operacion no exitosa
	//	Description:  Esta funcion eliminar un movimiento en la tabla de  sim_movimiento
	//              
	//////////////////////////////////////////////////////////////////////////////		
		$lb_valido=true;
		$ls_sql="";
		$li_exec=-1;
		
		$ib_db_error = false;
		$this->is_msg_error = "";
		$msg=new class_mensajes();

		$ls_sql = " DELETE FROM sim_movimiento".
					 " WHERE nummov= '".$as_nummov. "'".
					 " AND fecmov= '".$as_fecmov. "'";
	
		$this->io_sql->begin_transaction();	
		$li_exec=$this->io_sql->execute($ls_sql);
	
			if($li_exec===false)
			{
				$this->io_msg->message("CLASE->movimiento MÉTODO->uf_sim_delete_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
	
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
/*				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el Movimiento ".$as_nummov." de Fecha ".$ad_fecmov;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);*/
				//////////////////////////////////         SEGURIDAD               /////////////////////////////			
//				$this->io_sql->commit();
			}
		return $lb_valido;
	
	} 

	function uf_sim_select_dt_movimiento($as_CodEmp,$as_nummov,$ad_fecmov,$as_codart,$as_codalm,$as_opeinv,$as_codprodoc,$as_numdoc)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_sim_select_dt_movimiento
	//	Access:    public
	//	Arguments:
	//  as_CodEmp    // codigo de empresa
	//  as_nummov    // numero de movimiento
	//  ad_fecmov    // fecha de movimiento
	//  as_codart    // codigo de articulo
	//  as_codalm    // codigo de almacen
	//  as_opeinv    // codigo de operacion de inventario
	//  as_codprodoc // codigo de procedencia del documento
	//  as_numdoc    // numero de documento
	//	Returns:		$lb_valido-----> true: operacion exitosa false: operacion no exitosa
	//	Description:  Esta funcion busca los detalles asociados a un  movimientos  en la tabla de  sim_dt_movimiento
	//              
	//////////////////////////////////////////////////////////////////////////////		
		$lb_valido=true;
		$ls_sql="SELECT * FROM sim_dt_movimiento".
				" WHERE codemp='". $as_CodEmp ."'".
				" AND nummov='". $as_nummov ."'".
				" AND fecmov='". $ad_fecmov ."'".
				" AND codart='". $as_codart ."'".
				" AND codalm='". $as_codalm ."'".
				" AND opeinv='". $as_opeinv ."'".
				" AND codprodoc='". $as_codprodoc ."'".
				" AND numdoc='". $as_numdoc ."'";
		$li_exec=$this->io_sql->select($ls_sql);

		if($li_exec===false)
		{
			$this->io_msg->message("CLASE->movimiento MÉTODO->uf_sim_select_dt_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($li_exec))
			{
				$lb_valido=true;
				
			}
			else
			{
				$lb_valido=false;
			}
		}
			
		$this->io_sql->free_result($li_exec);
		return $lb_valido;
	}
	function uf_sim_insert_dt_movimiento($as_CodEmp,$as_nummov,$ad_fecmov,$as_codart,$as_codalm,$as_opeinv,$as_codprodoc,$as_numdoc,
										 $ai_canart,$ai_cosart,$aa_seguridad)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_sim_insert_dt_movimiento
	//	Access:    public
	//	Arguments:
	//  as_CodEmp    // codigo de empresa
	//  as_nummov    // numero de movimiento
	//  ad_fecmov    // fecha de movimiento
	//  as_codart    // codigo de articulo
	//  as_codalm    // codigo de almacen
	//  as_opeinv    // codigo de operacion de inventario
	//  as_codprodoc // codigo de procedencia del documento
	//  as_numdoc    // numero de documento
	//  ai_canart    // cantidad de articulos
	//  ai_cosart    // costo del articulo
	//  aa_seguridad // arreglo de registro de seguridad
	//	Returns:		$lb_valido-----> true: operacion exitosa false: operacion no exitosa
	//	Description:  Esta funcion inserta un detalle de movimiento en la tabla de  sim_dt_movimiento
	//              
	//////////////////////////////////////////////////////////////////////////////		
		global $fun;
		global $msg;
		$lb_valido=true;
		$ls_sql="";
		$li_exec=-1;
		
		$this->is_msg_error = "";
//		$this->io_sql->begin_transaction();
			
		
		$ls_sql="INSERT INTO sim_dt_movimiento (codemp, nummov, fecmov, codart, codalm, opeinv, codprodoc, numdoc, canart, cosart)".
				" VALUES ('".$as_CodEmp."','".$as_nummov."','".$ad_fecmov."','".$as_codart."','".$as_codalm."','".$as_opeinv."',".
				" '".$as_codprodoc."','".$as_numdoc."','".$ai_canart."','".$ai_cosart."')";
		
		$li_exec=$this->io_sql->execute($ls_sql);
		if($li_exec===false)
		{
			$this->io_msg->message("CLASE->movimiento MÉTODO->uf_sim_insert_dt_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
//			$this->io_sql->rollback();

		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
/*				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Detalle del Movimiento ".$as_nummov." de Fecha ".$ad_fecmov." de la Empresa ".$as_CodEmp.
								 " con el Artículo ".$as_codart." en el Almacen ".$as_codalm." representando una Operacioón de ".$as_opeinv.
								 ", Código de Procedencia ".$as_codprodoc." y Número de Documento ".$as_numdoc;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);*/
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
//				$this->io_sql->commit();
		}
		
		return $lb_valido;

	} 

	function uf_sim_update_dt_movimiento($as_CodEmp,$as_nummov,$ad_fecmov,$as_codart,$as_codalm,$as_opeinv,$as_codprodoc,$as_numdoc,
										 $ai_canart,$ai_cosart,$aa_seguridad)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_sim_update_dt_movimiento
	//	Access:    public
	//	Arguments:
	//  as_CodEmp    // codigo de empresa
	//  as_nummov    // numero de movimiento
	//  ad_fecmov    // fecha de movimiento
	//  as_codart    // codigo de articulo
	//  as_codalm    // codigo de almacen
	//  as_opeinv    // codigo de operacion de inventario
	//  as_codprodoc // codigo de procedencia del documento
	//  as_numdoc    // numero de documento
	//  ai_canart    // cantidad de articulos
	//  ai_cosart    // costo del articulo
	//  aa_seguridad // arreglo de registro de seguridad
	//	Returns:		$lb_valido-----> true: operacion exitosa false: operacion no exitosa
	//	Description:  Esta funcion modifica un detalle de movimiento en la tabla de  sim_dt_movimiento
	//              
	//////////////////////////////////////////////////////////////////////////////		

	 $lb_valido=true;
	 $ls_cadena="";
	 $li_exce=-1;
	
	 $ls_sql = "UPDATE sim_dt_movimiento SET   canart='". $ai_canart ."',cosart='". $ai_cosart ."' ".
	 		    " WHERE codemp='" . $as_CodEmp ."'".
				" AND nummov='" . $as_nummov ."'".
				" AND fecmov='" . $ad_fecmov ."'".
				" AND codart='" . $as_codart ."'".
				" AND codalm='" . $as_codalm ."'".
				" AND opeinv='" . $as_opeinv ."'".
				" AND codprodoc='" . $as_codprodoc ."'".
				" AND numdoc='" . $as_numdoc ."'";
//        $this->io_sql->begin_transaction();
		$li_exec = $this->io_sql->execute($ls_sql);
		if($li_exec==false&&($this->io_sql->message!=""))
		{
			$this->io_msg->message("CLASE->movimiento MÉTODO->uf_sim_update_dt_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
//			$this->io_sql->rollback();

		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
/*			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el Detalle del Movimiento ".$as_nummov." de Fecha ".$ad_fecmov." de la Empresa ".$as_CodEmp.
							 " con el Articulo ".$as_codart." en el Almacen ".$as_codalm." representando una Operacion de ".$as_opeinv.
							 ", Codigo de Procedencia ".$as_codprodoc." y Número de Documento ".$as_numdoc;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);*/
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
//			$this->io_sql->commit();
		}

 
	  return $lb_valido;

	} 

	function uf_sim_obtener_dt_movimiento($as_CodEmp,$as_nummov,$ad_fecmov,&$data,&$ai_totrows)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_sim_obtener_dt_movimiento
	//	Access:    public
	//	Arguments:
	//  as_CodEmp    // codigo de empresa
	//  as_nummov    // numero de movimiento
	//  ad_fecmov    // fecha de movimiento
	//  data         // datos del select
	//  ai_totrows   // total de filas encontradas
	//	Returns:		$lb_valido-----> true: operacion exitosa false: operacion no exitosa
	//	Description:  Esta funcion busca los detalles asociados a un  movimientos  en la tabla de  sim_dt_movimiento
	//              
	//////////////////////////////////////////////////////////////////////////////		
		$lb_valido=true;
		$ls_sql="SELECT * FROM sim_dt_movimiento".
				" WHERE codemp='". $as_CodEmp ."'".
				" AND nummov='". $as_nummov ."'".
				" AND fecmov='". $ad_fecmov ."'";
		$li_exec=$this->io_sql->select($ls_sql);
		if($li_exec===false)
		{
			$this->io_msg->message("CLASE->movimiento MÉTODO->uf_sim_obtener_dt_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if(!$row=$this->io_sql->fetch_row($li_exec))
			{
				$lb_valido=false;
			}
			else
			{
				$data=$this->io_sql->obtener_datos($li_exec);
				$this->DS->data=$data;
				$ai_totrows=$this->DS->getRowCount("nummov");
			
			}
		}
		return $lb_valido;
	}

} 
?>
