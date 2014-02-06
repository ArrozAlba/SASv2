<?php
class sigesp_rpc_c_pais
{
var $ls_sql;
var $arremp;
var $is_msg_error;

	
		function sigesp_rpc_c_pais($conn)
		{
		  require_once("../../shared/class_folder/sigesp_c_seguridad.php");
		  require_once("../../shared/class_folder/class_mensajes.php");		
		  require_once("../../shared/class_folder/class_funciones.php");
	      
		  $this->seguridad = new sigesp_c_seguridad();         
		  $this->io_funcion = new class_funciones();
		  $this->arremp=$_SESSION["la_empresa"];
		  $this->io_sql= new class_sql($conn);		
		  $this->io_msg = new class_mensajes();
		}
 

function uf_insert_pais($as_codpais,$as_denpais,$aa_seguridad) 
{
 //////////////////////////////////////////////////////////////////////////////
 //	Metodo: uf_insert_pais
 //	Access:  public
 //	Arguments: $as_codpais,$as_denpais,$aa_seguridad
 //	Description: Funcion que se encarga de insertar pais dentro de la tabla sigesp_pais. 
 //////////////////////////////////////////////////////////////////////////////
	$ls_sql = " INSERT INTO sigesp_pais (codpai,despai) VALUES('".$as_codpais."','".$as_denpais."')";
	$this->io_sql->begin_transaction();
	$rs_data=$this->io_sql->execute($ls_sql);
	if ($rs_data===false)
	   {
	     $lb_valido=false; 
	     $this->io_msg->message("CLASE->SIGESP_RPC_C_PAIS; METODO->uf_insert_pais; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   }
	else
	   {
		 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		 $ls_evento="INSERT";
		 $ls_descripcion ="Insertó en RPC el Pais ".$as_denpais." con el código ".$as_codpais;
		 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		 $aa_seguridad["ventanas"],$ls_descripcion);
		 /////////////////////////////////         SEGURIDAD               ///////////////////////////	   
	     $lb_valido=true;
	   }
return $lb_valido;
}

function uf_update_pais($as_codpais,$as_denpais,$aa_seguridad) 
{
//////////////////////////////////////////////////////////////////////////////
//	     Metodo:  uf_update_pais
//	     Access:  public
//	  Arguments:  $as_codpais,$as_denpais,$aa_seguridad
//	Description:  Funcion que se encarga de actulizar los datos de un pais. 
//////////////////////////////////////////////////////////////////////////////
	$ls_sql=" UPDATE sigesp_pais SET  despai='".$as_denpais."' WHERE codpai = '" .$as_codpais. "'";
	$this->io_sql->begin_transaction();
	$rs_data=$this->io_sql->execute($ls_sql);
	if ($rs_data===false)
	   {
	     $lb_valido=false; 
	     $this->io_msg->message("CLASE->SIGESP_RPC_C_PAIS; METODO->uf_update_pais; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   }
	else
	   {
		 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		 $ls_evento="UPDATE";
		 $ls_descripcion ="Actualizó en RPC el Pais ".$as_denpais." con el código ".$as_codpais;
		 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		 $aa_seguridad["ventanas"],$ls_descripcion);
		 /////////////////////////////////         SEGURIDAD               ///////////////////////////
	     $lb_valido=true;
	   }
return $lb_valido;
} 
		
function uf_delete_pais($as_codemp,$as_codpais,$as_denpais,$aa_seguridad)
{
//////////////////////////////////////////////////////////////////////////////
//	    Metodo:  uf_delete_pais
//	    Access:  public
//	 Arguments:  $as_codpais,$as_denpais,$aa_seguridad           
// Description:  Funcion que se encarga de eliminar un pais de la tabla sigesp_pais. 
//////////////////////////////////////////////////////////////////////////////
	
	 $ls_sql=" DELETE FROM sigesp_pais WHERE codpai='".$as_codpais."'";
	 $this->io_sql->begin_transaction();
	 $rs_data=$this->io_sql->execute($ls_sql);
	 if ($rs_data===false)
		{
		  $lb_valido=false; 
		  $this->io_msg_error = "CLASE->SIGESP_RPC_C_PAIS; METODO->uf_update_pais; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
	 else
		{
		  /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		  $ls_evento="DELETE";
		  $ls_descripcion ="Eliminó en RPC el Pais ".$as_denpais." con el código ".$as_codpais;
		  $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		  $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		  $aa_seguridad["ventanas"],$ls_descripcion);
		  /////////////////////////////////         SEGURIDAD               ///////////////////////////
		  $lb_valido=true;
		}
      return $lb_valido;
}

function uf_load_pais($as_codpais) 
{
//////////////////////////////////////////////////////////////////////////////
//	     Metodo:  uf_load_pais
//	     Access:  public
//	  Arguments:  $as_codpais
//	Description:  Funcion que se encarga de verificar si existe o no un pais, en caso
//                de existir la funcion devuelve true, caso contrario false. 
//////////////////////////////////////////////////////////////////////////////

	$lb_valido=false;
	$ls_sql=" SELECT * FROM sigesp_pais WHERE codpai='".$as_codpais."'";
	$rs_data=$this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
	     $lb_valido=false; 
	     $this->io_msg->message("CLASE->SIGESP_RPC_C_PAIS; METODO->uf_load_pais; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   }
	else
	   {
	     $li_numrows=$this->io_sql->num_rows($rs_data);
		 if ($li_numrows>0)
			{
			  $lb_valido=true;
			}
		}			
return $lb_valido;
}

function uf_check_relaciones($as_codemp,$as_codpai)
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_check_relaciones
//	          Access:  public
// 	        Arguments 
//        $as_codemp:  Código de la Empresa.  
//    $as_codtipoorg:  Código del Tipo Empresa.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de verificar si existen tablas relacionadas al Código del Tipo Empresa. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:22/03/2006.	 
//////////////////////////////////////////////////////////////////////////////

	$ls_sql="SELECT * FROM rpc_proveedor WHERE codemp='".$as_codemp."' AND codpai='".$as_codpai."'";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	  {
		$lb_valido=false;
	    $this->io_msg->message("CLASE->SIGESP_RPC_C_PAIS; METODO->uf_check_relaciones; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	  }
	else
	  {
		if ($row=$this->io_sql->fetch_row($rs_data))
		   {
		     $lb_valido=true;
		 	 $this->is_msg_error="El Pais no puede ser eliminado, posee registros asociados a otras tablas !!!";
		   }
		else
		   {
		     $lb_valido=false;
			 $this->is_msg_error="Registro no encontrado !!!";
	 	  }
	}
return $lb_valido;	
}
}//Fin de la Clase...
?>