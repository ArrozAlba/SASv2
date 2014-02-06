<?php

class sigesp_siv_c_componente
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_siv_c_componente()
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_datastore.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_msg=new class_mensajes();
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=    new class_sql($this->con);
		$this->seguridad= new sigesp_c_seguridad();
		$this->io_funcion=new class_funciones();
	}
	
	function uf_siv_select_componente($as_codemp,$as_codart,$as_codcom)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	       Function:  uf_siv_select_componente
		//           Access:  public (sigesp_siv_d_componentes)
		//	     Argumentos:  $as_codemp // codigo de empresa
		//  				  $as_codart // codigo de articulo
		//  				  $as_codcom // codigo de componente
		//	        Returns:  Retorna un Booleano
		//	    Description:  Funcion que verifica si existe un componente en la tabla siv_componente
		//       Creado por:  Ing. Luis Anibal Lang           
		// Fecha de Cracion:   01/02/2006							Fecha de Ultima Modificación: 01/02/2006	
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
		$lb_valido=true;
		$ls_sql = "SELECT * FROM siv_componente  ".
				  " WHERE codemp='".$as_codemp."'".
				  " AND codart='".$as_codart."'". 
				  " AND codcom='".$as_codcom."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->componente MÉTODO->uf_siv_select_componente ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	} // end function uf_siv_select_componente

	function  uf_siv_insert_componente($as_codemp, $as_codart, $as_codcom, $as_descom, $as_codunimed, $ai_cancom, $aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	       Function:  uf_siv_insert_componente
		//           Access:  public (sigesp_siv_d_componentes)
		//	     Argumentos:  $as_codemp    // codigo de empresa
		//  				  $as_codart    // codigo de articulo
		//  				  $as_codcom    // codigo de componente
		//  				  $as_descom    // descripcion de componente
		//  				  $as_codunimed // codigo de unidad de medida
		// 					  $ai_cancom    // cantidad de componente
		//  				  $aa_seguridad // arreglo de registro de seguridad
		//	        Returns:  Retorna un Booleano
		//	    Description:  Funcion que inserta un componente de un articulo en la tabla de  siv_componente
		//       Creado por:  Ing. Luis Anibal Lang           
		// Fecha de Cracion:   01/02/2006							Fecha de Ultima Modificación: 01/02/2006	
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
		$lb_valido=true;
		$ls_sql="";
		$this->io_sql->begin_transaction();
		$ls_sql="INSERT INTO siv_componente (codemp, codart, codcom, descom, codunimed, cancom)".
				" VALUES ('".$as_codemp."','".$as_codart."','".$as_codcom."','".$as_descom."','".$as_codunimed."',".$ai_cancom.")";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->componente MÉTODO->uf_siv_insert_componente ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Componente ".$as_codcom." Asociado al Articulo ".$as_codart." de la Empresa ".$as_codemp;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$this->io_sql->commit();
		}
		return $lb_valido;
	} //  end  function  uf_siv_insert_componente

	function  uf_siv_update_componente($as_codemp, $as_codart, $as_codcom, $as_descom, $as_codunimed, $ai_cancom, $aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	       Function:  uf_siv_update_componente
		//           Access:  public (sigesp_siv_d_componentes)
		//	     Argumentos:  $as_codemp    // codigo de empresa
		//  				  $as_codart    // codigo de articulo
		//  				  $as_codcom    // codigo de componente
		//  				  $as_descom    // descripcion de componente
		//  				  $as_codunimed // codigo de unidad de medida
		// 					  $ai_cancom    // cantidad de componente
		//  				  $aa_seguridad // arreglo de registro de seguridad
		//	        Returns:  Retorna un Booleano
		//	    Description:  Funcion que modifica un componente de un articulo en la tabla de  siv_componente
		//       Creado por:  Ing. Luis Anibal Lang           
		// Fecha de Cracion:   01/02/2006							Fecha de Ultima Modificación: 01/02/2006	
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
		$lb_valido=true;
		$ls_sql = "UPDATE siv_componente SET   descom='". $as_descom ."',codunimed='". $as_codunimed ."',cancom='". $ai_cancom ."'".
					" WHERE codart='" . $as_codart ."'".
					" AND codemp='" . $as_codemp ."'".
					" AND codcom='" . $as_codcom ."'";
        $this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->componente MÉTODO->uf_siv_update_componente ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el Componente ".$as_codcom." Asociado al Articulo ".$as_codart." de la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	  return $lb_valido;
	} // end  function  uf_siv_update_componente

	function uf_siv_delete_componente($as_codemp,$as_codart,$as_codcom, $aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	       Function:  uf_siv_delete_componente
		//           Access:  public (sigesp_siv_d_componentes)
		//	     Argumentos:  $as_codemp // codigo de empresa
		//  				  $as_codart // codigo de articulo
		//  				  $as_codcom // codigo de componente
		//                    $aa_seguridad   // arreglo de registro de seguridad
		//	        Returns:  Retorna un Booleano
		//	    Description:  Funcion  que elimina un componente de un articulo en la tabla de  siv_componente
		//       Creado por:  Ing. Luis Anibal Lang           
		// Fecha de Cracion:   01/02/2006							Fecha de Ultima Modificación: 01/02/2006	
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
		$lb_valido=true;
		$ls_sql="";
		$ls_sql= " DELETE FROM siv_componente".
					" WHERE codemp= '".$as_codemp. "'".
					" AND codart= '".$as_codart. "'". 
					" AND codcom= '".$as_codcom. "'"; 
		$this->io_sql->begin_transaction();	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->componente MÉTODO->uf_siv_delete_componente ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó el Componente ".$as_codcom." Asociado al Articulo ".$as_codart." de la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			//////////////////////////////////         SEGURIDAD               /////////////////////////////			
			$this->io_sql->commit();
		}
		return $lb_valido;
	} 

} 
?>
