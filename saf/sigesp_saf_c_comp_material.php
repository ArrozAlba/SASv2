<?php
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/sigesp_c_seguridad.php");
class sigesp_saf_c_comp_material
{
	var $dat;
	var $SQL;
	var $fun;
	
	function sigesp_saf_c_comp_material()
	{
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");		
		$this->io_sql=new class_sql($io_conexion);
		$this->io_funcion=new class_funciones();	
		$this->io_msg=new class_mensajes();
		$this->seguridad= new sigesp_c_seguridad();
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];	
	}
	
	function guardar($as_codtipest, $as_codcomp, $as_dencomp, $as_existe, $aa_seguridad)
	{
	/////////////////////////////////////////////////////////////////////////////////////////
		//	  Function:  guardar
		//	    Access:  public
		//	 Arguments:  aa_seguridad   // arreglo de registro de seguridad
		//	    Returns: $lb_valido-----> true: operacion exitosa false: operacion no exitosa
		//	Description: Esta funcion inserta un edificio como un inmueble
		/////////////////////////////////////////////////////////////////////////////////////////
		$ls_valido=true;	
		switch ($as_existe)
		{
			case "FALSE":
			$ls_valido=$this->uf_saf_insert_comp_material($as_codtipest, $as_codcomp, $as_dencomp, $aa_seguridad);
			if ($ls_valido)
			{
				$this->io_msg->message("El Componente fue Registrado");
			}			
			break;
			
			case "TRUE":
			$ls_valido=$this->uf_saf_update_comp_material($as_codtipest, $as_codcomp, $as_dencomp, $aa_seguridad);
			if ($ls_valido)
			{
				$this->io_msg->message("El Componente fue Actualizado");
			}	
			break;
		}
		return $ls_valido;
	}//fin de guardar()
//--------------------------------------------------------------------------------------------------------------------------------------	
	function  uf_saf_insert_comp_material($as_codtipest, $as_codcomp, $as_dencomp, $aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////
		//	  Function:  uf_saf_insert_comp_material
		//	    Access:  public
		//	 Arguments:  
		//                aa_seguridad   // arreglo de registro de seguridad
		//	    Returns: $lb_valido-----> true: operacion exitosa false: operacion no exitosa
		//	Description: Esta funcion inserta un edificio como un inmueble
		/////////////////////////////////////////////////////////////////////////////////////////		
		$lb_valido=false;		
        $this->io_sql->begin_transaction();
		$ls_codemp=$this->ls_codemp;
		$ls_sql = " INSERT INTO saf_componente(codemp, codtipest, codcomp, dencomp) ".
    			  "      VALUES ('".$ls_codemp."','".$as_codtipest."','".$as_codcomp."','".$as_dencomp."');" ;							
		$rs_data=$this->io_sql->execute($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->materiales MÉTODO->uf_saf_insert_comp_material ERROR->".
			                       $this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el componente ".codcomp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
		return $lb_valido;
	}//fin de la uf_saf_insert_comp_material
//---------------------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------------------------------
    function  uf_saf_update_comp_material($as_codtipest, $as_codcomp, $as_dencomp, $aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////
		//	  Function: uf_saf_update_comp_material
		//	    Access:  public
		//	 Arguments: aa_seguridad   // arreglo de registro de seguridad
		//	    Returns: $lb_valido-----> true: operacion exitosa false: operacion no exitosa
		//	Description: Esta funcion que actuliza un edificio como un inmueble
		/////////////////////////////////////////////////////////////////////////////////////////		
		$lb_valido=false;	
		$ls_codemp=$this->ls_codemp;	
        $this->io_sql->begin_transaction();
		$ls_sql = " UPDATE saf_componente ".
                  "    SET codemp='".$ls_codemp."', ".
				  "        codtipest='".$as_codtipest."', ".
				  "        codcomp='".$as_codcomp."', ". 
				  "        dencomp='".$as_dencomp."' ".
                  "  WHERE codtipest='".$as_codtipest."'".
				  "    AND codcomp='".$as_codcomp."'";
															
		$rs_data=$this->io_sql->execute($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->inmueble_edificio MÉTODO->uf_saf_update_comp_material ERROR->".
			                       $this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="se actualizó el Componente ".$as_codcomp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
		return $lb_valido;
	}//fin de la uf_saf_update_comp_material
//----------------------------------------------------------------------------------------------------------------------------------------   
///--------------------------------------------------------------------------------------------------------------------------------------
    function uf_eliminar_comp_material($as_codtipest, $as_codcomp, $aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////
		//	  Function:  uf_eliminar_comp_material 
		//	    Access:  public
		//	 Arguments: aa_seguridad   // arreglo de registro de seguridad
		//	    Returns: $lb_valido-----> true: operacion exitosa false: operacion no exitosa
		//	Description: Esta funcion elimina el componente
		/////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;	
		$ls_codemp=$this->ls_codemp;	
        $this->io_sql->begin_transaction();
		$ls_sql = " DELETE FROM  saf_componente ".                 
                  "  WHERE codtipest='".$as_codtipest."'".
				  "    AND codcomp='".$as_codcomp."'";
															
		$rs_data=$this->io_sql->execute($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->inmueble_edificio MÉTODO->uf_eliminar_comp_material ERROR->".
			                       $this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="se Eliminó el Componente ".$as_codcomp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
		return $lb_valido;
	}
//--------------------------------------------------------------------------------------------------------------------------------------
}//fin de la clase sigesp_c_inmueble_edificio
?>