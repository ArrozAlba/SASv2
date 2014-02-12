<?php
class sigesp_rpc_c_socio
{

var $ls_sql;
	
		function sigesp_rpc_c_socio($conn)
		{  
		  require_once("../shared/class_folder/sigesp_c_seguridad.php");
	      $this->seguridad = new sigesp_c_seguridad();
		  require_once("../shared/class_folder/class_mensajes.php");
		  require_once("../shared/class_folder/class_funciones.php");
		  $this->io_funcion = new class_funciones();
		  $this->io_sql= new class_sql($conn);
		  $this->io_msg= new class_mensajes();		
		}
 
function uf_insert_socio($as_codemp,$as_prov,$as_cedula,$as_nombre,
                        $as_apellido,$as_direccion,$as_cargo,
                        $as_telefono,$as_email,$aa_seguridad) 
{
 //////////////////////////////////////////////////////////////////////////////
 //
 //	Metodo       uf_insert_socio
 //	Access       public
 //	Arguments    $as_codemp,$as_prov,$as_cedula,$as_nombre,$as_apellido,
 //              $as_direccion,$as_cargo,$as_telefono,$as_email,$aa_seguridad
 //	Returns		 $lb_valido. Retorna una variable booleana.
 //	Description  Funcion que toma los valores traidos y a realiza una inserción 
 //              en la tabla de rpc_proveedorsocios
 //
 //////////////////////////////////////////////////////////////////////////////

    $lb_valido=true;

	$ls_sql = " INSERT INTO rpc_proveedorsocios ".
			  " (codemp,cod_pro,cedsocio,nomsocio,apesocio,carsocio,telsocio,dirsocio,email) ". 
			  " VALUES ". 
			  " ('".$as_codemp."','".$as_prov."','".$as_cedula."','".$as_nombre."','".$as_apellido."',".
			  " '".$as_cargo."','".$as_telefono."','".$as_direccion."','".$as_email."')";
	$this->io_sql->begin_transaction();
	$li_numrows=$this->io_sql->execute($ls_sql);
	if ($li_numrows===false)
	   {
           $lb_valido=false;
		   $this->io_sql->rollback();
		   $this->io_msg->message('Error en Inclusión !!!');
		   $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));			
	   }
	else
	   {
         $lb_valido=true;
		 $this->io_sql->commit();
		 $this->io_msg->message('Registro Incluido !!!');
	     /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		 $ls_evento="INSERT";
		 $ls_descripcion ="Insertó en RPC al Socio ".$as_nombre." ".$as_apellido." con cédula ".$as_cedula.
		                  " asociado al Proveedor ".$as_prov;
		 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		 $aa_seguridad["ventanas"],$ls_descripcion);
		 /////////////////////////////////         SEGURIDAD               ///////////////////////////
	   }
return $lb_valido;
}

function uf_update_socio($as_codemp,$as_prov,$as_cedula,$as_nombre,
                         $as_apellido,$as_direccion,$as_cargo,
                         $as_telefono,$as_email,$aa_seguridad) 
{
 //////////////////////////////////////////////////////////////////////////////
 //
 //	Metodo       uf_update_socio
 //	Access       public
 //	Arguments    $as_codemp,$as_prov,$as_cedula,$as_nombre,$as_apellido,
 //              $as_direccion,$as_cargo,$as_telefono,$as_email,$aa_seguridad
 //	Returns		 $lb_valido. Retorna una variable booleana.
 //	Description  Funcion que toma los valores traidos y a realiza una actualizacion 
 //              en la tabla de rpc_proveedorsocios.
 //
 //////////////////////////////////////////////////////////////////////////////

    $lb_valido= true;

	$ls_sql=" UPDATE rpc_proveedorsocios ".
			" SET    nomsocio='".$as_nombre."',    apesocio='".$as_apellido."', ".
            "        dirsocio='".$as_direccion."', carsocio='".$as_cargo."',". 
			"        telsocio='".$as_telefono."',  email='".$as_email."' ". 
			" WHERE  codemp   ='".$as_codemp."'   AND ".
            "        cedsocio = '".$as_cedula."'  AND ".
            "        cod_pro  ='".$as_prov."'";
	
	$this->io_sql->begin_transaction();
	$li_numrows=$this->io_sql->execute($ls_sql);
	if ($li_numrows===false)
	   {
		  $lb_valido= false;
		  $this->io_sql->rollback();
		  $this->io_msg->message('Error en Actualización !!!');
		  $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));  
	   }
	else
	   {
         $lb_valido= true;
		 $this->io_sql->commit();
		 $this->io_msg->message('Registro Actualizado !!!');     
	     /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		 $ls_evento="UPDATE";
		 $ls_descripcion ="Actualizó en RPC al Socio ".$as_cedula. " asociado al Proveedor ".$as_prov;
		 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		 $aa_seguridad["ventanas"],$ls_descripcion);
		 /////////////////////////////////         SEGURIDAD               ///////////////////////////
	   }
return $lb_valido;
} 

function uf_delete_socio($as_codemp,$as_prov,$as_cedula,$aa_seguridad)
{
 //////////////////////////////////////////////////////////////////////////////
 //
 //	Metodo       uf_delete_socio
 //	Access       public
 //	Arguments    $as_codemp,$as_prov,$as_cedula,$aa_seguridad
 //	Returns 	 $lb_valido. Retorna una variable booleana.	
 //	Description  Funcion de eliminar un socio de la tabla rpc_proveedorsocios
 //
 //////////////////////////////////////////////////////////////////////////////

  $lb_valido=true;

  $ls_sql = " DELETE ".
			" FROM rpc_proveedorsocios ".
			" WHERE  codemp='".$as_codemp."'   AND ".
            "        cedsocio='".$as_cedula."' AND ".
			"        cod_pro='".$as_prov."' ";	    
  $this->io_sql->begin_transaction();
  $li_numrows=$this->io_sql->execute($ls_sql);
  if ($li_numrows===false)
	 {            
       $lb_valido=false;     
       $this->io_sql->rollback();
	   $this->io_msg->message('Error en Eliminación !!!');
	   $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));                   
	 }
  else
	 {
       $lb_valido=true;
	   $this->io_sql->commit();
	   $this->io_msg->message('Registro Eliminado !!!');        
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	   $ls_evento="DELETE";
	   $ls_descripcion ="Eliminó en RPC al Socio ".$as_cedula." asociado al Proveedor ".$as_prov;
	   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	   $aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               ///////////////////////////
	 } 		 
return $lb_valido;
}

function uf_select_socio($as_codemp,$as_prov,$as_cedula) 
{
 //////////////////////////////////////////////////////////////////////////////
 //
 //	Metodo       uf_select_socio
 //	Access       public
 //	Arguments    $as_codemp,$as_prov,$as_cedula
 //	Returns 	 $lb_valido. Retorna una variable booleana
 //	Description  Busca un registro dentro de la tabla de en rpc_proveedorsocios
 //              la base de datos y retorna una variable booleana de que  
 //              existe. 
 //
 //////////////////////////////////////////////////////////////////////////////

    $lb_valido=true;
	$ls_sql=" SELECT  * ".
			" FROM   rpc_proveedorsocios ". 
			" WHERE  codemp='".$as_codemp."'   AND ".
            "        cedsocio='".$as_cedula."' AND ". 
			"        cod_pro='".$as_prov."'";

	$rs=$this->io_sql->select($ls_sql);
	if ($rs===false)
	   {
		   $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
		   $lb_valido=false;
	   }
	else
	   {
 		   $li_numrows=$this->io_sql->num_rows($rs);
		   if($li_numrows>0)
			 {
			   $lb_valido=true;
		   	$this->io_sql->free_result($rs);

			 }
		   else
			 {
			   $lb_valido=false;
			 }
	   }
 return $lb_valido;
}



function uf_select_proveedor($as_codemp,$as_codpro)
{
 //////////////////////////////////////////////////////////////////////////////
 //
 //	Funcion       uf_existe_proveedor
 //	Access        public
 //	Arguments     $as_codemp,$as_codpro
 //	Returns   	  $lb_valido. Retorna una variable booleana
 //	Description   Busca un registro dentro de la tabla rpc_proveedor en 
 //               la base de datos y retorna una variable booleana de que  
 //               existe 
 //
 //////////////////////////////////////////////////////////////////////////////

		$ls_sql="";
		$lb_valido=false;
		$ls_sql=" SELECT * FROM rpc_proveedor WHERE codemp ='".$as_codemp."' AND cod_pro='".$as_codpro."' ";
		$rs_data=$this->io_sql->select($ls_sql);
        if ($rs_data===false)
		   {
              $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
		      $lb_valido=false;
		   }
		else
		   {
	          $li_numrows=$this->io_sql->num_rows($rs_data);
			  if($li_numrows>0)
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
	}
}//Fin de la Clase...
?>