
<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/sigesp_c_seguridad.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");
class sigesp_siv_c_clase
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_siv_c_clase()
	{
		$this->io_msg=new class_mensajes();
		$this->dat_emp=$_SESSION["la_empresa"];
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=new class_sql($this->con);
		$this->seguridad= new sigesp_c_seguridad();
		$this->io_funcion = new class_funciones();
	}//fin de la function sigesp_saf_c_metodos()
	
   //-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar_clase($as_codemp,$as_codseg,$as_codfam,$as_desclase,$as_codclase,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar_clase
		//		   Access: public
		//	    Arguments: as_codseg  // código del segmento
		//                 as_codfam  // codigo de la familia
		//                 as_codclase  // codigo de la clase
		//				   as_desclase  // descripcion de la clase
		//	      Returns: $lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que inserta los datos que entran como parámetro en la tabla siv_clase
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 13/11/2008				Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_existe="true";
		$ls_sql = "INSERT INTO siv_clase(codemp,codseg,codfami,codclase,desclase)". 
				  "VALUES( '".$as_codemp."','".$as_codseg."','".$as_codfam."','".$as_codclase."','".$as_desclase."')"; 
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			//print ($this->io_sql->message);
			$this->io_msg->message("CLASE-> MÉTODO->uf_guardar_clase ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la clase".$as_codclase." perteneciente a la familia".$as_codfam. " y al segmento".$as_codseg." de la Empresa ".$as_codemp;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
                $this->io_sql->commit();
		}
	    return  $lb_valido;
	}// end function uf_guardar_clase	
	//-----------------------------------------------------------------------------------------------------------------------------------

    function  uf_actualizar_clase($as_codemp,$as_codseg,$as_codfam,$as_desclase,$as_codclase,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_actualizar_clase
		//         Access: public (sigesp_siv_d_activo)
		//     Argumentos: $as_empresa    // codigo de empresa                
		//				   $as_codseg    // codigo del segmento         	      
		//			       $as_desseg    // denominacion del segmento          
		//				   $as_tipo // Tipo Bien/Obra: cuyos valores pueden ser “B” Bienes y “S” de Obras y/o Servicios.	  
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza los datos basicos de un activo en la tabla saf_activo
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 10/11/2008 				Fecha Última Modificación:
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();
		
		$ls_sql="UPDATE siv_clase".
				"   SET desclase='".$as_desclase."'".
				" WHERE codemp =  '".$as_codemp."'". 
				"   AND codseg =  '".$as_codseg ."'".
				"   AND codfami =  '".$as_codfam ."'".
				"   AND codclase= '".$as_codclase."'"; 
				
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE-> MÉTODO->uf_actualizar_clase ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la demominación de clase ".$as_codclase." de la familia ".$as_codfam." perteneciente al segmento".$as_codseg." de la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();

		}
	    return $lb_valido;
	}// fin de la function uf_actualizar_clase

	function uf_elimina_clase($as_codemp,$as_codseg,$as_codfam,$as_codclase,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_elimina_clase
		//         Access: public 
		//      Argumento: $as_codemp //codigo de empresa 
		//				   $as_codseg //codigo del segmento
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un determinado activo en la tabla siv_segmento
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 10/10/2008								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$this->io_sql->begin_transaction();	
		/*$lb_encontrado=$this->uf_siv_select_familia($as_codemp,$as_codseg);
		if($lb_encontrado)
		{
			$this->io_msg->message("No se puede eliminar ya que existen clases pertenecientes al segmento".$as_codseg);
		}
		else
		{
			$lb_encontrado=$this->uf_siv_select_clase($as_codemp,$as_codseg);
			if($lb_encontrado)
			{
				$this->io_msg->message("No se puede eliminar ya que existen clases pertenecientes al segmento".$as_codseg);
			}
			else
			{*/
			   $lb_encontrado=$this->uf_siv_select_producto($as_codemp,$as_codseg);
				if($lb_encontrado)
				{
					$this->io_msg->message("No se puede eliminar ya que existen productos pertenecientes al segmento".$as_codseg);
				}
				else
				{
						$ls_sql = " DELETE FROM siv_clase".
								  " WHERE siv_clase.codemp= '".$as_codemp. "'".
								  " AND siv_clase.codseg= '".$as_codseg. "'".
								  " AND siv_clase.codfami='".$as_codfam. "'".
								  " AND siv_clase.codclase='".$as_codclase. "'"; 
						$li_exec=$this->io_sql->execute($ls_sql);
						if($li_exec===false)
						{
							$this->io_msg->message("CLASE-> MÉTODO->uf_elimina_clase ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
							$this->io_sql->rollback();
						}
						else
						{
							$lb_valido=true;
							/////////////////////////////////         SEGURIDAD               /////////////////////////////
							$ls_evento="DELETE";
							$ls_descripcion ="Eliminó la clase ".$as_codclase." de la familia ".$as_codfam." perteneciente al segmento ".$as_codseg." de la Empresa ".$as_codemp;
							$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
															$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
															$aa_seguridad["ventanas"],$ls_descripcion);
							/////////////////////////////////         SEGURIDAD               /////////////////////////////			
							$this->io_sql->commit();
						}
				 }// fin del else
			//}
		//}
		return $lb_valido;
	} //fin de uf_elimina_clase
   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_siv_select_segmento($as_codemp,$as_codseg)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_select_segmento
		//         Access: public 
		//      Argumento: $as_codemp //codigo de empresa 
		//				   $as_codact //codigo de activo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existe datos en la tabla siv_segmento
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT * FROM siv_segmento ".
				  "WHERE codemp='".$as_codemp."' ".
				  "AND codseg='".$as_codseg."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE-> MÉTODO->uf_siv_select_segmento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{$lb_valido=true;}
		}
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	}//fin de la function uf_siv_select_segmento
  	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	function uf_siv_select_familia($as_codemp,$as_codseg,$as_codfam)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_select_familia
		//         Access: public 
		//      Argumento: $as_codemp //codigo de empresa 
		//				   $as_codseg //codigo del segmento
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existe datos en la tabla siv_familia
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT siv_familia.*,siv_segmento.codseg FROM siv_familia,siv_segmento ".
				  "WHERE siv_familia.codemp='".$as_codemp."' ".
				  "AND siv_familia.codseg='".$as_codseg."'".
				  "AND siv_familia.codfami='".$as_codfam."'".
				  "AND siv_segmento.codseg=siv_familia.codseg " ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE-> MÉTODO->uf_siv_select_familia ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{$lb_valido=true;}
		}
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	}//fin de la function uf_siv_select_familia
  ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_siv_select_clase($as_codemp,$as_codseg)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_select_clase
		//         Access: public 
		//      Argumento: $as_codemp //codigo de empresa 
		//				   $as_codact //codigo de activo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existe datos en la tabla siv_segmento
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT siv_clase.*,siv_segmento.codseg,siv_familia.codfami FROM siv_clase,siv_segmento,siv_familia ".
				  "WHERE siv_clase.codemp='".$as_codemp."' ".
				  "AND siv_clase.codseg='".$as_codseg."' ".
                  "AND siv_segmento.codemp=siv_familia.codemp ".
				  "AND siv_segmento.codseg=siv_familia.codseg ".
				  "AND siv_familia.codfami=siv_clase.codfami ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->activo MÉTODO->uf_siv_select_clase ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{$lb_valido=true;}
		}
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	}//fin de la function uf_siv_select_clase
   ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_siv_select_producto($as_codemp,$as_codseg)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_select_clase
		//         Access: public 
		//      Argumento: $as_codemp //codigo de empresa 
		//				   $as_codact //codigo de activo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existe datos en la tabla siv_segmento
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT siv_producto.*,siv_segmento.codseg,siv_familia.codfami,siv_clase.codclase ".
		          "FROM siv_producto,siv_segmento,siv_familia,siv_clase ".
				  "WHERE siv_producto.codemp='".$as_codemp."' ".
				  " AND siv_producto.codseg='".$as_codseg."'".
				  " AND siv_segmento.codseg=siv_familia.codseg ".
				  " AND siv_producto.codseg=siv_clase.codseg ".
				  " AND siv_familia.codfami=siv_clase.codfami ".
				  " AND siv_clase.codclase=siv_producto.codclase ";
				  
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->activo MÉTODO->uf_siv_select_clase ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{$lb_valido=true;}
		}
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	}//fin de la function uf_siv_select_clase
}//fin de la class sigesp_siv_c_segmento
?>
