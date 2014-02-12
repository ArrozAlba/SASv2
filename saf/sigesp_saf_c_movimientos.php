<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/sigesp_c_seguridad.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("sigesp_saf_c_activo.php");

class sigesp_saf_c_movimientos
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_saf_c_movimientos()
	{
		$this->io_msg=new class_mensajes();
		$this->dat_emp=$_SESSION["la_empresa"];
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=new class_sql($this->con);
		$this->seguridad= new sigesp_c_seguridad();
		$this->io_funcion = new class_funciones();
		$this->io_activo = new sigesp_saf_c_activo();
		$this->tipcat = $this->io_activo->uf_select_valor_config($this->dat_emp["codemp"]);
	}
	
	function uf_saf_select_movimientos($as_codigo)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_saf_select_movimientos
	//	Access:    public
	//	Arguments:
	//  as_catalogo    // codigo de movimientos
	//	Returns:		$lb_valido-----> true: encontrado false: no encontrado
	//	Description:  Esta funcion busca una causa de movimiento en la tabla de  saf_causas
	//              
	//////////////////////////////////////////////////////////////////////////////		

		global $fun;
		global $msg;
		$lb_valido=true;
		$ls_cadena="";
		$li_exec=-1;
		
		$this->is_msg_error = "";
			
		
		$ls_sql = "SELECT * FROM saf_causas  ".
					" WHERE CodCau='".$as_codigo."'" ;
		
			
		$li_exec=$this->io_sql->select($ls_sql);

		if($li_exec===false)
		{
			$this->io_msg->message("CLASE->movimientos MÉTODO->uf_saf_select_movimientos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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

	}//fin de la function uf_saf_select_movimientos()

	function  uf_saf_insert_movimientos($as_codigo,$as_denominacion,$as_tipo,$ai_contable,$ai_presupuestaria,$as_explicacion,$aa_seguridad)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_saf_insert_movimientos
	//	Access:    public
	//	Arguments:
	//  as_codigo         // codigo de causa
	//  as_denominacion   // denominacion de la causa
	//  as_tipo           // cuenta asociada 
	//  ai_contable       // estatus contabke
	//  ai_presupuestaria // estatus presupuestario
	//  as_explicacion // explicacion de la causa
	//  aa_seguridad   // arreglo de registro de seguridad
	//	Returns:		$lb_valido-----> true: operacion exitosa false: operacion no exitosa
	//	Description:  Esta funcion inserta un movimiento en la tabla de  saf_causas
	//              
	//////////////////////////////////////////////////////////////////////////////		

		global $fun;
		global $msg;
		$lb_valido=true;
		$ls_sql="";
		$li_exec=-1;
		
		$this->is_msg_error = "";
			
        $this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO saf_causas (CodCau, DenCau, TipCau, EstAfeCon, EstAfePre, ExpCau, EstCat) ".
					" VALUES('".$as_codigo."','".$as_denominacion."','".$as_tipo."','".$ai_contable."',".
					" '".$ai_presupuestaria."','".$as_explicacion."',".$this->tipcat.")" ;
			
		$li_exec=$this->io_sql->execute($ls_sql);
		if($li_exec===false)
		{
			$this->io_msg->message("CLASE->movimientos MÉTODO->uf_saf_insert_movimientos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();

		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la Causa de Movimiento ".$as_codigo;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$this->io_sql->commit();
		}
		
		return $lb_valido;

	}//fin de la uf_saf_insert_movimientos

	function uf_saf_update_movimientos($as_codigo,$as_denominacion,$as_radiotipo,$ai_contable,$ai_presupuestaria,$as_explicacion,$aa_seguridad) 
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_saf_update_movimientos
	//	Access:    public
	//	Arguments:
	//  as_codigo         // codigo de causa
	//  as_denominacion   // denominacion de la causa
	//  as_radiotipo      // cuenta asociada 
	//  ai_contable       // estatus contabke
	//  ai_presupuestaria // estatus presupuestario
	//  as_explicacion    // explicacion de la causa
	//  aa_seguridad   // arreglo de registro de seguridad
	//	Returns:		$lb_valido-----> true: operacion exitosa false: operacion no exitosa
	//	Description:  Esta funcion modifica un movimiento en la tabla de  saf_causas
	//              
	//////////////////////////////////////////////////////////////////////////////		

	 $lb_valido=true;
	 $ls_cadena="";
	 $li_exce=-1;
	
	 $ls_sql = "UPDATE saf_causas SET   DenCau='". $as_denominacion ."', TipCau='". $as_radiotipo ."',".
	 			" EstAfeCon='". $ai_contable ."', EstAfePre='". $ai_presupuestaria ."', ExpCau='". $as_explicacion ."'".
				" WHERE CodCau='" . $as_codigo ."' AND EstCat= ".$this->tipcat;

        $this->io_sql->begin_transaction();
		$li_exec = $this->io_sql->execute($ls_sql);
		if($li_exec===false)
		{
			$this->io_msg->message("CLASE->movimientos MÉTODO->uf_saf_update_movimientos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();

		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la Causa de Movimiento ".$as_codigo;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}

 
	  return $lb_valido;

	}// fin de la function uf_sss_update_movimientos

	function uf_saf_delete_movimientos($as_codigo,$aa_seguridad)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_saf_delete_movimientos
	//	Access:    public
	//	Arguments:
	//  as_codigo         // codigo de causa
	//  aa_seguridad   // arreglo de registro de seguridad
	//	Returns:		$lb_valido-----> true: operacion exitosa false: operacion no exitosa
	//	Description:  Esta funcion elimina un movimiento en la tabla de  saf_causas
	//              
	//////////////////////////////////////////////////////////////////////////////		

		$lb_valido=true;
		$ls_sql="";
		$li_exec=-1;
		
		$ib_db_error = false;
		$this->is_msg_error = "";
		$msg=new class_mensajes();
		$ls_sql = " DELETE FROM saf_causas".
					 " WHERE CodCau= '".$as_codigo. "'  AND EstCat= ".$this->tipcat;
	
		$this->io_sql->begin_transaction();	
		$li_exec=$this->io_sql->execute($ls_sql);

		if($li_exec===false)
		{
			$this->io_msg->message("CLASE->movimientos MÉTODO->uf_saf_delete_movimientos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();

		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó la Causa de Movimiento ".$as_codigo;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////			
			$this->io_sql->commit();
		}
			
		 		
		return $lb_valido;
	
	} //fin de uf_saf_delete_movimientos

}//fin de la class sigesp_saf_c_movimientos
?>
