<?php

class sigesp_srh_c_uni_vipladin
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;
	var $coduni = null;	
	var $denuni = null;
	var $codemp = null;

	function sigesp_srh_c_uni_vipladin($path)
	{   require_once($path."shared/class_folder/class_sql.php");
		require_once($path."shared/class_folder/class_datastore.php");
		require_once($path."shared/class_folder/class_mensajes.php");
		require_once($path."shared/class_folder/sigesp_include.php");
		require_once($path."shared/class_folder/sigesp_c_seguridad.php");
		require_once($path."shared/class_folder/class_funciones.php");
		$this->io_msg=new class_mensajes();
		$this->io_funcion = new class_funciones();
		$this->la_empresa=$_SESSION["la_empresa"];
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=new class_sql($this->con);
		$this->seguridad= new sigesp_c_seguridad();
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		
		
		
	}
	
	
 
//--------------------------------------------------------------------------------------------------------------------------------	
	function uf_srh_select_uni_vipladin($as_coduni)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_uni_vipladin
		//         Access: public (sigesp_srh_d_uni_vipladin)
		//      Argumento: $as_coduni    // codigo de la unidad vipladin
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una busqueda de un departamento en la tabla de  srh_departamento
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 01/04/2008							Fecha Última Modificación: 05/09/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM srh_unidadvipladin  ".
				  " WHERE codunivipladin='".trim($as_coduni)."'".
				  " AND codemp='".$this->ls_codemp."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Unidad VIPLADIN MÉTODO->uf_srh_select_uni_vipladin ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  //  end function uf_srh_select_uni_vipladin
	
//----------------------------------------------------------------------------------------------------------------------------------

	function  uf_srh_insert_uni_vipladin($as_coduni,$as_denuni,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_insert_uni_vipladin
		//         Access: public (sigesp_srh_d_uni_vipladin)
		//      Argumento: $as_coduni       // codigo de la unidad vipladin
	    //                 $as_denuni      // denominacion de la unidad vipladin 
	    //                 $aa_seguridad    // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta una unidad vipladin en srh_unidadvipladin
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 01/04/2008							Fecha Última Modificación: 05/09/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        $this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO srh_unidadvipladin (codunivipladin, denunivipladin,codemp) ".
					" VALUES('".$as_coduni."','".$as_denuni."','".$this->ls_codemp."')" ;
			
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->Unidad VIPLADIN MÉTODO->uf_srh_insert_uni_vipladin ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó Unidad VIPLADIN ".$as_coduni;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$this->io_sql->commit();
		}
		return $lb_valido;
	} // end  function  uf_srh_insert_uni_vipladin
	
//-----------------------------------------------------------------------------------------------------------------------------------

	function uf_srh_update_uni_vipladin($as_coduni,$as_denuni,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_update_uni_vipladin
		//         Access: public (sigesp_srh_d_uni_vipladin)
		//      Argumento: $as_coduni   // codigo de la unidad vipladin 
	    //                 $as_denuni   // denominacion de la unidad vipladin
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que modifica una unidad vipladin en srh_unidadvipladin
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 01/04/2008							Fecha Última Modificación: 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql = "UPDATE srh_unidadvipladin SET   denunivipladin='". $as_denuni."'". 
				   " WHERE codunivipladin='" . $as_coduni ."'".
				   " AND codemp='".$this->ls_codemp."'";
        
		$this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->Unidad VIPLADIN MÉTODO->uf_srh_update_uni_vipladin ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó Unidad VIPLADIN ".$as_coduni;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	  return $lb_valido;
	} // end  function uf_srh_update_uni_vipladin
//-------------------------------------------------------------------------------------------------------------------------------

	function uf_srh_delete_uni_vipladin($as_coduni,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_delete_uni_vipladin
		//         Access: public (sigesp_srh_d_uni_vipladin)
		//      Argumento: $as_coduni  // codigo de la unidad vipladin
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina una unidad vipladin
		//                
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 01/04/2008						Fecha Última Modificación: 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe= $this->uf_srh_select_unidad_personal ($as_coduni);
		if($lb_existe)
		{
			$lb_existe=true;		
			$lb_valido=false;
			
		}
		else
		{
			$this->io_sql->begin_transaction();	
			$ls_sql =  " DELETE FROM srh_unidadvipladin ".
						 " WHERE codunivipladin= '".$as_coduni. "'"; 
						 
						 
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->tiporequerimiento MÉTODO->uf_srh_delete_tiporequerimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la unidad vipladin ".$as_coduni;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
		}
		return array($lb_valido,$lb_existe);
	} // end function uf_srh_delete_uni_vipladin
	
//--------------------------------------------------------------------------------------------------------------------------------
	
	function uf_srh_select_unidad_personal ($as_coduni)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_unidad_personal
		//         tipreqess: private
		//      Argumento: $as_coduni   // codigo de la unidad vipladin
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existen unidades vipladin asociadas a un personal
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 09/04/2008							Fecha Última Modificación: 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$ls_sql = "SELECT * FROM sno_personal  ".
				  " WHERE codunivipladin='".$as_coduni."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Unidad VIPLADIN  MÉTODO->uf_srh_select_unidad_personal ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	} // end function uf_srh_select_tiporeq_requerimiento

	
//--------------------------------------------------------------------------------------------------------------------------------
	
	function uf_srh_buscar_uni_vipladin($as_coduni,$as_denuni)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_uni_vipladin
		//         Access: private
		//      Argumento: $as_coduni  // codigo de la unidad vipladin
		//	      Returns: Retorna un Booleano
		//    Description: Funcion busca una unidad vipladin
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 01/04/2008							Fecha Última Modificación:
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$ls_coddestino="txtcodunivi";
		$ls_dendestino="txtdenunivi";
	
		
		$lb_valido=true;
		$ls_sql="SELECT * FROM srh_unidadvipladin".
				" WHERE codunivipladin like '".$as_coduni."' ".
				"   AND denunivipladin like '".$as_denuni."' ".
			   " ORDER BY codunivipladin";
	
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Unidad VIPLADIN MÉTODO->uf_srh_buscar_uni_vipladin( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			     
					$ls_coduni=$row["codunivipladin"];
					$ls_denuni=htmlentities ($row["denunivipladin"]);
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['codunivipladin']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					$cell->appendChild($dom->createTextNode($row['codunivipladin']." ^javascript:aceptar(\"$ls_coduni\",\"$ls_denuni\",\"$ls_coddestino\",\"$ls_dendestino\");^_self"));
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_denuni));												
					$row_->appendChild($cell);
					
					
			
			}
			return $dom->saveXML();
		
			
			
	
		}
    } // uf_srh_buscar_uni_vipladin(

//---------------------------------------------------------------------------------------------------------------------------------	

}// end   class sigesp_srh_c_departamento
?>
