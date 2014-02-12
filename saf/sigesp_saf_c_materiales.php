<?php
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/sigesp_c_seguridad.php");
class sigesp_saf_c_materiales
{
	var $dat;
	var $SQL;
	var $fun;
	
	function sigesp_saf_c_materiales()
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
	
	function guardar($as_codtipest, $as_dentipest, $as_existe, $aa_seguridad)
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
			$ls_valido=$this->uf_saf_insert_material($as_codtipest, $as_dentipest, $aa_seguridad);
			if ($ls_valido)
			{
				$this->io_msg->message("El Material fue Registrado");
			}			
			break;
			
			case "TRUE":
			$ls_valido=$this->uf_saf_update_materiales($as_codtipest, $as_dentipest, $aa_seguridad);
			if ($ls_valido)
			{
				$this->io_msg->message("El Material fue Actualizado");
			}	
			break;
		}
		return $ls_valido;
	}//fin de guardar()
//--------------------------------------------------------------------------------------------------------------------------------------	
	function  uf_saf_insert_material($as_codtipest, $as_dentipest, $aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////
		//	  Function:  uf_saf_insert_material
		//	    Access:  public
		//	 Arguments:  
		//                aa_seguridad   // arreglo de registro de seguridad
		//	    Returns: $lb_valido-----> true: operacion exitosa false: operacion no exitosa
		//	Description: Esta funcion inserta un edificio como un inmueble
		/////////////////////////////////////////////////////////////////////////////////////////		
		$lb_valido=false;		
        $this->io_sql->begin_transaction();
		$ls_codemp=$this->ls_codemp;
		$ls_sql = " INSERT INTO saf_tipoestructura(codemp, codtipest, dentipest) ".
    			  "      VALUES ('".$ls_codemp."','".$as_codtipest."', '".$as_dentipest."');" ;							
		$rs_data=$this->io_sql->execute($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->materiales MÉTODO->uf_saf_insert_material ERROR->".
			                       $this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el material ".$as_codtipest;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
		return $lb_valido;
	}//fin de la uf_saf_insert_grupo
//---------------------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------------------------------
    function  uf_saf_update_materiales($as_codtipest, $as_dentipest, $aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////
		//	  Function: uf_saf_update_materiales
		//	    Access:  public
		//	 Arguments: aa_seguridad   // arreglo de registro de seguridad
		//	    Returns: $lb_valido-----> true: operacion exitosa false: operacion no exitosa
		//	Description: Esta funcion que actuliza un edificio como un inmueble
		/////////////////////////////////////////////////////////////////////////////////////////		
		$lb_valido=false;	
		$ls_codemp=$this->ls_codemp;	
        $this->io_sql->begin_transaction();
		$ls_sql = " UPDATE saf_tipoestructura ".
				  "    SET codemp='".$ls_codemp."', ".
				  "        codtipest='".$as_codtipest."', ". 
				  "        dentipest='".$as_dentipest."' ".
				  "	 WHERE codemp   ='".$ls_codemp."'  ".
				  "    AND codtipest='".$as_codtipest."'";
															
		$rs_data=$this->io_sql->execute($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->inmueble_edificio MÉTODO->uf_saf_update_materiales ERROR->".
			                       $this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="se actualizó el Material ".$as_codtipest;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
		return $lb_valido;
	}//fin de la uf_saf_update_materiales
//----------------------------------------------------------------------------------------------------------------------------------------   
///--------------------------------------------------------------------------------------------------------------------------------------
  function uf_select_componente($as_codtipest)
  {
        /////////////////////////////////////////////////////////////////////////////////////////
		//	  Function: uf_select_componente
		//	    Access:  public
		//	 Arguments: aa_seguridad   // arreglo de registro de seguridad
		//	    Returns: $lb_valido-----> true: operacion exitosa false: operacion no exitosa
		//	Description: Esta funcion que busca los componentes
		/////////////////////////////////////////////////////////////////////////////////////////	
		$lb_valido=true;
		$cantidad=0;
		$as_existe="FALSE";
		$ls_sql=" SELECT codemp, codtipest, codcomp, dencomp FROM saf_componente".
		        "  WHERE codtipest='".$as_codtipest."'";
		$rs_data=$this->io_sql->select($ls_sql); 
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->inmueble_edificio MÉTODO->uf_select_componente ERROR->".
								   $this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			
		}
		else
		{
		    $cantidad=$this->io_sql->num_rows($rs_data);		
			$this->io_sql->free_result($rs_data);		
		}// fin del else
		return $cantidad;
  }// fin de uf_select_componente
  //-------------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------------
   function uf_elimina_materiales($as_codtipest, $aa_seguridad)
   {
        /////////////////////////////////////////////////////////////////////////////////////////
		//	  Function: uf_elimina_materiales
		//	    Access:  public
		//	 Arguments: 
		//	    Returns: $lb_valido-----> true: operacion exitosa false: operacion no exitosa
		//	Description: Esta funcion que elimina los materiales
		/////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;	
		$ls_codemp=$this->ls_codemp;
		$cantidad=0;
		$cantidad=$this->uf_select_componente($as_codtipest); 
		if ($cantidad==0)
		{	
			$ls_sql = " DELETE FROM saf_tipoestructura WHERE codtipest='".$as_codtipest."'";
																
			$rs_data=$this->io_sql->execute($ls_sql);
			if($rs_data===false)
			{
				$this->io_msg->message("CLASE->inmueble_edificio MÉTODO->uf_elimina_materiales ERROR->".
									   $this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;				
			}
			else
			{
				$lb_valido=true;				
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="ELIMINAR";
				$ls_descripcion ="se Eliminó el Material ".$as_codtipest;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				
			}
		}// fin del if
		else
		{
			$this->io_msg->message("No se puede Eliminar el Material pues tiene componentes asociados");
		}
		return $lb_valido;
   }// fin uf_elimina_materiales
 //-------------------------------------------------------------------------------------------------------------------------------------
}//fin de la clase sigesp_c_inmueble_edificio
?>