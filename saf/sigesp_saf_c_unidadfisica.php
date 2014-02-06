<?php
require_once("../shared/class_folder/class_sql.php");
class sigesp_saf_c_unidadfisica
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_saf_c_unidadfisica()
	{
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_msg=new class_mensajes();
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$this->io_sql=      new class_sql($this->con);
		$this->seguridad=   new sigesp_c_seguridad();
		$this->io_funcion = new class_funciones();
	}
	
	function uf_saf_select_unidadfisica($as_coduniadm)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_unidadfisica
		//         Access: private 
		//      Argumento: $as_coduniadm //codigo de Unidad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica existe la unidad 
		//	   Creado Por: Ing. Luis Lang / Ing. Yesenia Moreno
		// Fecha Creación: 21/11/2007 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT coduniadm".
				"  FROM saf_unidadadministrativa  ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND coduniadm='".$as_coduniadm."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->unidadfisica MÉTODO->uf_saf_select_unidadfisica ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
		}
		return $lb_valido;
	}  // end function uf_saf_select_unidadfisica
	
	function  uf_saf_insert_unidadfisica($as_coduniadm,$as_denuniadm,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_insert_cambioresponsable
		//         Access: public
		//      Argumento: $as_coduniadm //codigo de unidad
		//                 $as_denuniadm //Denominacion de Unidad
		//				   $aa_seguridad //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta la unidad fisica
		//	   Creado Por: Ing. Luis Lang / Ing. Yesenia Moreno
		// Fecha Creación: 21/11/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO saf_unidadadministrativa (codemp, coduniadm, denuniadm) ".
				" VALUES('".$this->ls_codemp."','".$as_coduniadm."','".$as_denuniadm."')" ;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->unidadfisica MÉTODO->uf_saf_insert_cambioresponsable ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la Unidad Fisica ".$as_coduniadm." Asociado a la Empresa ".$this->ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if($lb_valido)
			{
				$this->io_sql->commit();
				$this->io_msg->message("Se Inserto la Unidad Fisica con Exito");
			}
			else
			{
				$this->io_sql->rollback();
				$this->io_msg->message("No se Inserto la Unidad Fisica");
			}
		}
		return $lb_valido;
	} //end function  uf_saf_insert_cambioresponsable

	function uf_saf_update_unidadfisica($as_coduniadm,$as_denuniadm,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_update_unidadfisica
		//         Access: public 
		//      Argumento: $as_coduniadm //codigo de unidad
		//                 $as_denuniadm //Denominacion de Unidad
		//				   $aa_seguridad //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza un los responsables de un activo en la tabla saf_dta
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 03/04/2006 								Fecha Última Modificación : 03/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();
		$ls_sql="UPDATE saf_unidadadministrativa".
				"   SET denuniadm='".$as_denuniadm."'".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND coduniadm='".$as_coduniadm."'";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->unidadfisica MÉTODO->uf_saf_update_unidadfisica ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la Unidad Fisica ".$as_coduniadm." Asociado a la Empresa ".$this->ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if($lb_valido)
			{
				$this->io_sql->commit();
				$this->io_msg->message("Se Actualizo la Unidad Fisica con Exito");
			}
			else
			{
				$this->io_sql->rollback();
				$this->io_msg->message("No se Actualizo la Unidad Fisica");
			}
		}
	    return $lb_valido;
	} // end  function uf_saf_update_unidadfisica

	function uf_saf_procesar_unidadfisica($as_coduniadm,$as_denuniadm,$as_estatus,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_procesar_cambioresponsable
		//         Access: public
		//      Argumento: $as_coduniadm //codigo de unidad
		//                 $as_denuniadm //Denominacion de Unidad
		//                 $as_estatus //Estatus de INSERT/UPDATE
		//				   $aa_seguridad //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza las operaciones asociadas al grabar una unidad fisica de Activos
		//	   Creado Por: Ing. Luis Lang / Ing. Yesenia Moreno
		// Fecha Creación: 21/11/2007 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		if($as_estatus=="C")
		{
			$lb_existe=$this->uf_saf_select_unidadfisica($as_coduniadm);
			if($lb_existe)
			{
				$lb_valido=$this->uf_saf_update_unidadfisica($as_coduniadm,$as_denuniadm,$aa_seguridad);
			}
			else
			{
				$this->io_msg->message("La Unidad a actualizar no Existe");
			}
		}
		else
		{
			$lb_existe=$this->uf_saf_select_unidadfisica($as_coduniadm);
			if(!$lb_existe)
			{
				$lb_valido=$this->uf_saf_insert_unidadfisica($as_coduniadm,$as_denuniadm,$aa_seguridad);
			}
			else
			{
				$this->io_msg->message("Ya existe la Unidad");
			}
		}
		return $lb_valido;
	} // end  function uf_saf_procesar_cambioresponsable
//----------------------------------------------------------------------------------------------------------------------------------
	function uf_saf_delete_unidadfisica($as_coduniadm,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_delete_grupo
		//		   Access: private
		//		 Argument: as_coduniadm // Codigo de Unidad
		//				   aa_seguridad // Arreglo de variables de seguridad
		//	  Description: Funcion que elimina una unidad
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 22/11/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=$this->uf_saf_selectactivos($as_coduniadm);
		if(!$lb_valido)
		{
			$this->io_sql->begin_transaction();
			$ls_sql="DELETE ".
					"  FROM saf_unidadadministrativa". 
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND coduniadm='".$as_coduniadm."'";
			$rs_data=$this->io_sql->execute($ls_sql);
			if($rs_data===false)
			{
				$this->io_msg->message("CLASE->unidadfisica MÉTODO->uf_saf_delete_unidadfisica ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la Unidad Fisica ".$as_coduniadm." Asociado a la Empresa ".$this->ls_codemp;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
		}	
		else
		{
			$this->io_msg->message("Posee activos relacionados");
			$lb_valido=false;
		}
		return $lb_valido;
	}//fin function uf_saf_delete_unidadfisica
//----------------------------------------------------------------------------------------------------------------------------------
	function uf_saf_selectactivos($as_coduniadm)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_unidadfisica
		//         Access: private 
		//      Argumento: $as_coduniadm //codigo de Unidad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica existe la unidad 
		//	   Creado Por: Ing. Luis Lang / Ing. Yesenia Moreno
		// Fecha Creación: 21/11/2007 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codact".
				"  FROM saf_dta".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND coduniadm='".$as_coduniadm."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->unidadfisica MÉTODO->uf_saf_selectactivos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
		}
		return $lb_valido;
	}  // end function uf_saf_selectactivos
	
} 
?>
