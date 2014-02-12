<?php
class sigesp_sep_c_carxconceptos
{
var $ls_sql;
	
		function sigesp_sep_c_carxconceptos($conn)
		{
		  require_once("../../shared/class_folder/class_mensajes.php");
		  require_once("../../shared/class_folder/sigesp_c_seguridad.php");
		  $this->io_sql= new class_sql($conn);
		  $this->io_msg= new class_mensajes();
		  $this->seguridad = new sigesp_c_seguridad();		
		}
 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////           Inicio function uf_insertconcepto          ///////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		function uf_insert($as_codemp,$as_codconsep,$as_codcar,$aa_seguridad) 
		{
         
		  $ls_sql = " INSERT INTO sep_conceptocargos ".
		            " (codemp,codcar,codconsep) ".
					" VALUES ('".$as_codemp."','".$as_codconsep."','".$as_codcar."')";
          $this->io_sql->begin_transaction();
		  $li_numrows=$this->io_sql->execute($ls_sql);
		  if ($li_numrows===false)
		     {
		       $this->io_sql->rollback();
			   $this->io_msg->message('Error en Inclusión !!!');			  
			   $this->io_msg->message($fun->uf_convertirmsg($io_sql->message));
			 }
		  else
		     {
		       
			  /* /////////////////////////////////         SEGURIDAD               ////////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion =" Insertó Concepto "." ".$as_codconsep;
					$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												    $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												    $aa_seguridad["ventanas"],$ls_descripcion);
			   /////////////////////////////////         SEGURIDAD               ////////////////////////////////	
			   $this->io_sql->commit();
		       $this->io_msg->message('Registro Incluido !!!');*/
		     }
		}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////           Fin de function uf_insertclasif            //////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


		
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////           Inicio Function uf_eliminarconcepto       ///////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		function uf_eliminar($as_codemp,$as_codconsep,$as_codcar,$aa_seguridad) 
		{          		 
		  $ls_sql = " DELETE ".
		            " FROM sep_conceptocargos ".
					" WHERE codemp='".$as_codemp."',".
                    " codcar='".$as_codconsep."',codconsep='".$as_codcar."'";	    

		  $this->io_sql->begin_transaction();
		  $li_numrows=$this->io_sql->execute($ls_sql);
		  if ($li_numrows===false)
		     {
			   $this->io_sql->rollback();
			   $this->io_msg->message('Error en Eliminación !!!');
			   $this->io_msg->message($fun->uf_convertirmsg($io_sql->message));
			 }
		  else
		     {
/*			    /////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="DELETE";
					$ls_descripcion ="Eliminó Concepto "." ".$as_codconsep;
					$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												    $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												    $aa_seguridad["ventanas"],$ls_descripcion);
			   /////////////////////////////////         SEGURIDAD               /////////////////////////////
			   $this->io_sql->commit();
			   $this->io_msg->message('Registro Eliminado !!!'); */
		     } 		 
		  }
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////           Fin Function uf_eliminarclasificacion       ////////////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////              Inicio Function uf_existeconcepto         ///////////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		function uf_existe($as_codemp,$as_codconsep,$as_codcar)  
		{
		  $ls_sql=" SELECT * ".
		          " FROM sep_conceptocargos ".
                  " WHERE codemp='".$as_codemp."',".
                  " codcar='".$as_codconsep."',codconsep='".$as_codcar."'";	    

		  $rs=$this->io_sql->select($ls_sql);
		  $li_numrows=$this->io_sql->num_rows($rs);
          if ($li_numrows==false)
			 {
			   $lb_valido=false;
			 }
		  else
			 {
                if($li_numrows<=0)
                {
                  $lb_valido=false;
                }
                else
                {
			      $lb_valido=true;
	  		      $this->io_sql->free_result($rs);
                }
			 } 
		  return $lb_valido;
		}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////                Fin Function uf_existeconcepto       //////////////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
?> 