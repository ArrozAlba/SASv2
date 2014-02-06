<?php 
class sigesp_spg_c_tipomodificaciones
{

var $ls_sql;
	
	function sigesp_spg_c_tipomodificaciones()
	{
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/sigesp_c_seguridad.php");
		$this->seguridad = new sigesp_c_seguridad();		  
		require_once("../../shared/class_folder/class_funciones.php");
		$this->io_funcion = new class_funciones();
		require_once("../../shared/class_folder/class_mensajes.php");
		$this->io_msg= new class_mensajes();		
		require_once("../../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/sigesp_c_generar_consecutivo.php");
		$this->io_keygen= new sigesp_c_generar_consecutivo();
	}
 
	//----------------------------------------------------------------------------------------------------------------------------
	function uf_insert_tipomodificacion($as_codemp,&$as_codtipmodpre,$as_dentipmodpre,$as_pretipmodpre,$as_contipmodpre,
										$aa_seguridad) 
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_tipomodificacion
		//		   Access: private
		//	    Arguments: as_codemp        // Codigo de Empresa
		//				   as_codtipmodpre  // Codigo
		//				   as_dentipmodpre  // Denominacion
		//				   as_pretipmodpre  // Prefijo
		//				   as_contipmodpre  // Contador
		//				   aa_seguridad     // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta el tipo de modificaciones presupuestarias.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 10/11/2008 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$this->io_keygen->uf_verificar_numero_generado("SPG","spg_tipomodificacion","codtipmodpre","SPGMOD",4,"","","",&$as_codtipmodpre);
		$ls_sql="INSERT INTO spg_tipomodificacion  (codemp, codtipmodpre, dentipmodpre, pretipmodpre, contipmodpre)".
				" VALUES ('".$as_codemp."','".$as_codtipmodpre."','".$as_dentipmodpre."','".$as_pretipmodpre."','".$as_contipmodpre."')";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if ($li_row===false)		     
		{
			if($this->io_sql->errno=='23505' || $this->io_sql->errno=='1062' || $this->io_sql->errno=='-239' || $this->io_sql->errno=='-5'|| $this->io_sql->errno=='-1')
			{
				$lb_valido=$this->uf_insert_tipomodificacion($as_codemp,&$as_codtipmodpre,$as_dentipmodpre,$as_pretipmodpre,
															 $as_contipmodpre,$aa_seguridad);
			}
			else
			{
				$this->io_sql->rollback();
				$this->io_msg->message("CLASE->Tipo Modificacion MÉTODO->uf_insert_tipomodificacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		else
		{
			$lb_valido=true;
			$this->io_sql->commit();
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó en el tipo de modificacion ".$as_codtipmodpre;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////// 		     
		}
		$this->io_sql->close();
		return $lb_valido;
	}
	//----------------------------------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------------------------------------
	function uf_update_tipomodificacion($as_codemp,$as_codtipmodpre,$as_dentipmodpre,$aa_seguridad) 
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_tipomodificacion
		//		   Access: private
		//	    Arguments: as_codemp        // Codigo de Empresa
		//				   as_codtipmodpre  // Codigo
		//				   as_dentipmodpre  // Denominacion
		//				   as_pretipmodpre  // Prefijo
		//				   as_contipmodpre  // Contador
		//				   aa_seguridad     // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta el tipo de modificaciones presupuestarias.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 10/11/2008 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="UPDATE spg_tipomodificacion ".
			  	"   SET dentipmodpre='".$as_dentipmodpre."' ".
			  	" WHERE codemp='" .$as_codemp. "'".
				"   AND codtipmodpre = '".$as_codtipmodpre."'";
		
		$rs_clausula=$this->io_sql->execute($ls_sql);
		$this->io_sql->begin_transaction();
		if ($rs_clausula===false)
		{
			$this->io_sql->rollback();
			$this->io_msg->message("CLASE->Tipo Modificacion MÉTODO->uf_update_tipomodificacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			$lb_valido=true;
			$this->io_sql->commit();
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó en el Tipo de Modificacion ".$as_codtipmodpre;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		     
		}  		      
		return $lb_valido;
	 } 
	//----------------------------------------------------------------------------------------------------------------------------
		
	//----------------------------------------------------------------------------------------------------------------------------
	function uf_delete_tipomodificacion($as_codemp,$as_codtipmodpre,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_tipomodificacion
		//		   Access: private
		//	    Arguments: as_codemp        // Codigo de Empresa
		//				   as_codtipmodpre  // Codigo
		//				   as_dentipmodpre  // Denominacion
		//				   as_pretipmodpre  // Prefijo
		//				   as_contipmodpre  // Contador
		//				   aa_seguridad     // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta el tipo de modificaciones presupuestarias.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 10/11/2008 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false; 
		$lb_existe=$this->uf_check_relacion($as_codemp,$as_codtipmodpre);
		if(!$lb_existe)
		{ 
			$ls_sql="DELETE FROM spg_tipomodificacion".
					" WHERE codemp='".$as_codemp."'".
					"   AND codtipmodpre='".$as_codtipmodpre."'";  

			$rs_data = $this->io_sql->execute($ls_sql);
			$this->io_sql->begin_transaction();
			if ($rs_data===false)
			{   print $this->io_sql->message;
				$this->io_sql->rollback();
				$this->io_msg->message("CLASE->Tipo Modificacion MÉTODO->uf_delete_tipomodificacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				$lb_valido = true;
				$this->io_sql->commit();
				$this->io_msg->message('Registro Eliminado'); 
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó en el Tipo de Modificacion ".$as_codtipmodpre;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               ///////////////////////////// 		     
			} 		 
			$this->io_sql->close();
		}
		else
		{
			$this->io_msg->message('No se puede Eliminar debido a que tiene registros asociados'); 
		}
		return $lb_valido;
	}
	//----------------------------------------------------------------------------------------------------------------------------


	//----------------------------------------------------------------------------------------------------------------------------
	function uf_check_relacion($as_codemp,$as_codtipmodpre) 
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_tipomodificacion
		//		   Access: private
		//	    Arguments: as_codemp        // Codigo de Empresa
		//				   as_codtipmodpre  // Codigo
		//				   as_dentipmodpre  // Denominacion
		//				   as_pretipmodpre  // Prefijo
		//				   as_contipmodpre  // Contador
		//				   aa_seguridad     // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta el tipo de modificaciones presupuestarias.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 10/11/2008 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codtipmodpre".
				"  FROM sigesp_cmp_md".
				" WHERE codemp='".$as_codemp."'".
				"   AND codtipmodpre='".$as_codtipmodpre."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_msg->message("CLASE->Tipo Modificacion MÉTODO->uf_check_relacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
		}
		//$this->io_sql->free_result($rs_data);
		return $lb_valido;
	}
	//----------------------------------------------------------------------------------------------------------------------------

}//Fin de la Clase...
?> 