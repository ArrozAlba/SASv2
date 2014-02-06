<?php
//************************************************************************//
// autor: Ing. Oscar Sequera.
// fecha de creacion: 26-11-2007
//***********************************************************************//

require_once("../shared/class_folder/class_sql.php");
class sigesp_sfc_c_transferencia_almacen
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_sfc_c_transferencia_almacen()
	{
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones_db.php");
		$this->dat_emp=   $_SESSION["la_empresa"];
		$this->ls_gestor= $_SESSION["ls_gestor"];
		$in=              new sigesp_include();
		$this->con=       $in->uf_conectar();
		$this->io_sql=    new class_sql($this->con);
		$this->seguridad= new sigesp_c_seguridad();
		$this->fun=       new class_funciones_db($this->con);
		$this->io_msg=    new class_mensajes();
		$this->io_funcion=new class_funciones();
		
	}
	
function uf_sfc_select_dt_transferencia_almacen($ls_codemp,$ls_numtra,$ls_codart)
	{
		
		$lb_valido=true;
		$ls_sql = "SELECT * FROM sfc_dt_transferenciaalmacen  ".
				  " WHERE codemp='".$ls_codemp."'".
				  "   AND numtra='".$ls_numtra."'".
				  "   AND codart='".$ls_codart."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->transferencia MÉTODO->uf_sfc_select_dt_transferencia_almacen ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;
	} // end  function uf_sfc_select_dt_transferencia_almacen

function uf_sim_select_dt_transferencia_almacen($ls_codemp,$ls_numtra,$ls_codart)
	{
		
		$lb_valido=true;
		$ls_sql = "SELECT * FROM sim_dt_transferencia  ".
				  " WHERE codemp='".$ls_codemp."'".
				  "   AND numtra='".$ls_numtra."'".
				  "   AND codart='".$ls_codart."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->transferencia MÉTODO->uf_sim_select_dt_transferencia_almacen ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;
	} // end  function uf_sim_select_dt_transferencia_almacen

function uf_sfc_select_transferencia_almacen($ls_codemp,$ls_numtra,$ls_fecemi,$ls_codalmori,$ls_codalmdes)
	{
		
		$lb_valido=true;
		$ls_sql = "SELECT * FROM sfc_transferenciaalmacen  ".
				  " WHERE codemp='".$ls_codemp."'".
				  "   AND numtra='".$ls_numtra."'".
				  "   AND codalmori='".$ls_codalmori."'".
				  "   AND codalmdes='".$ls_codalmdes."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->transferencia MÉTODO->uf_sfc_select_transferencia_almacen ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;
	} // end  function uf_sfc_select_transferencia_almacen

function uf_sfc_select_transferencia($ls_codemp)
	{
		
		$lb_valido=true;
		$ls_sql = "SELECT * FROM sfc_transferenciaalmacen WHERE codemp='".$ls_codemp."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->transferencia MÉTODO->uf_sfc_select_transferencia ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;
	} // end  function uf_sfc_select_transferencia

function uf_sim_select_transferencia_almacen($ls_codemp,$ls_numtra,$ls_fecemi,$ls_codalmori,$ls_codalmdes)
	{
		
		$lb_valido=true;
		$ls_sql = "SELECT * FROM sim_transferencia  ".
				  " WHERE codemp='".$ls_codemp."'".
				  "   AND numtra='".$ls_numtra."'".
				  "   AND fecemi='".$ls_fecemi."'".
				  "   AND codalmori='".$ls_codalmori."'".
				  "   AND codalmdes='".$ls_codalmdes."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->transferencia MÉTODO->uf_sim_select_transferencia_almacen ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;
	} // end  function uf_sim_select_transferencia_almacen

function uf_sfc_insert_transferencia_almacen($ls_codemp,&$ls_numtra,$ls_fecemi,$ls_codalmori,$ls_codalmdes)
	{
		
		$lb_valido=true;
		$ls_emp="";
		$ls_empresa="";
		$ls_tabla="sfc_transferenciaalmacen";
		$ls_columna="numtra";
		$as_numtra=$this->fun->uf_generar_codigo($ls_emp,$ls_empresa,$ls_tabla,$ls_columna);
		$ls_sql="INSERT INTO sfc_transferenciaalmacen (codemp,numtra,fecemi,codalmori,codalmdes)".
				" VALUES ('".$ls_codemp."','".$ls_numtra."','".$ls_fecemi."','".$ls_codalmori."','".$ls_codalmdes."')";
				
		$li_row=$this->io_sql->execute($ls_sql);
		
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->transferencia MÉTODO->uf_sfc_insert_transferencia_almacen ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
				$lb_valido=true;
		}
		return $lb_valido;
	} // end  function uf_sfc_insert_transferencia_almacen

function uf_sfc_delete_transferencia_almacen($ls_codemp,$ls_numtra,$ls_fecemi,$ls_codalmori,$ls_codalmdes)
	{
		$lb_valido=true;
		$ls_sql = " DELETE FROM sfc_transferenciaalmacen".
				  " WHERE codemp= '".$ls_codemp. "'".
				  "   AND numtra= '".$ls_numtra. "'".
				  "   AND codalmori= '".$ls_codalmori. "'".
				  "   AND codalmdes= '".$ls_codalmdes. "'".
				  "   AND fecemi= '".$ls_fecemi. "'";
		$this->io_sql->begin_transaction();	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->transferencia MÉTODO->uf_sfc_delete_transferencia_almacen ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
		}
		return $lb_valido;
	} // end  function uf_sfc_delete_transferencia_almacen 

function uf_sfc_insert_dt_transferencia_almacen($ls_codemp,$ls_numtra,$ls_fecemi,$ls_codart,$ls_cantidad)
	{
		
		$lb_valido=true;
		$ls_sql="INSERT INTO sfc_dt_transferenciaalmacen (codemp, numtra,fecemi,codart,cantidad)".
				" VALUES ('".$ls_codemp."','".$ls_numtra."','".$ls_fecemi."','".$ls_codart."','".$ls_cantidad."')";
				
		
		$li_row=$this->io_sql->execute($ls_sql);
		
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->transferencia MÉTODO->uf_sfc_insert_dt_transferencia_almacen ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
				$lb_valido=true;
		}
		return $lb_valido;
	} // end function uf_sfc_insert_dt_transferencia_almacen

function uf_sfc_delete_dt_transferencia_almacen($ls_codemp,$ls_numtra,$ls_fecemi,$ls_codart,$ls_cantidad)
	{
		
		$lb_valido=true;
		$ls_sql = " DELETE FROM sfc_dt_transferenciaalmacen".
				  " WHERE codemp= '".$ls_codemp. "'".
				  "   AND numtra= '".$ls_numtra. "'".
				  "   AND fecemi= '".$ls_fecemi. "'".
				  "   AND cantidad='".$ls_cantidad."'".
				  "   AND codart= '".$ls_codart. "'";
				
		$li_exec=$this->io_sql->select($ls_sql);

		if($li_exec==false)
		{
			$this->io_msg->message("CLASE->transferencia MÉTODO->uf_sfc_delete_dt_transferencia_almacen ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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

} 
?>
