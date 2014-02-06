<?php
class sigesp_spg_c_estprog2
{
var $is_msg_error;
	
		function sigesp_spg_c_estprog2($conn)
		{
		  require_once("../../shared/class_folder/sigesp_c_seguridad.php");
	      require_once("../../shared/class_folder/class_funciones.php");
          require_once("../../shared/class_folder/class_mensajes.php");
		  require_once("class_folder/sigesp_spg_c_estprog3.php");
		  $this->io_estpro3   = new sigesp_spg_c_estprog3($conn);
		  $this->io_seguridad = new sigesp_c_seguridad();
		  $this->io_funcion   = new class_funciones();
		  $this->io_sql       = new class_sql($conn);
		  $this->io_msg       = new class_mensajes();		
		}

function uf_spg_select_estprog2($as_codemp,$as_codestpro1,$as_codestpro2,$as_estcla)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_spg_select_estprog4
//	          Access:  public
// 	        Arguments   
//        $as_codemp:  Código de la Empresa.
//    $as_codestpro1:  Código del Primer  Nivel de la Estructura Presupuestaria o Programática.
//    $as_codestpro2:  Código del Segundo Nivel de la Estructura Presupuestaria o Programática.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de verificar si existe o no el segundo codigo nivel. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  12/09/2006       Fecha Última Actualización:12/09/2006.	 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 

  $lb_valido = false; 
  $ls_sql  = "SELECT codestpro2 FROM spg_ep2                                                                        ".
             " WHERE codemp='".$as_codemp."' AND codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND estcla='".$as_estcla."'";
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
	 {
 	   $lb_valido=false;
 	   $this->io_msg->message("CLASE->SIGESP_SPG_C_ESTPROG2; METODO->uf_spg_select_estprog2; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
	   $li_numrows = $this->io_sql->num_rows($rs_data);
	   if($li_numrows>0)
		 {
		   $lb_valido=true;
		   $this->io_sql->free_result($rs_data);
		 }
	 }
  return $lb_valido;
}

function uf_spg_insert_estprog2($as_codemp,$as_codestpro1,$as_codestpro2,$as_denestpro2,$ai_estmodest,$as_estcla,$aa_seguridad)
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_spg_insert_estprog2
// 	        Arguments   
//        $as_codemp:
//    $as_codestpro1:
//    $as_codestpro2:
//    $as_denestpro2:
//     $aa_seguridad:
//	          Access:  public
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de insertar el cuarto Nivel de la Estructura Programatica. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:08/09/2006.	 
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//     $as_codestpro1 = $io_funcion->uf_cerosizquierda($as_codestpro1,25);
	// $as_codestpro2 = $io_funcion->uf_cerosizquierda($as_codestpro2,25);
	 $ls_sql = "INSERT INTO spg_ep2 (codemp,codestpro1,codestpro2,denestpro2,estcla) VALUES ('".$as_codemp."','".$as_codestpro1."','".$as_codestpro2."','".$as_denestpro2."','".$as_estcla."') ";
	 $this->io_sql->begin_transaction();
	 $rs_data = $this->io_sql->execute($ls_sql);
	 if ($rs_data===false)		     
	    {
		  $lb_valido          = false;
 	      $this->is_msg_error = "CLASE->SIGESP_SPG_C_ESTPROG2; METODO->uf_spg_insert_estprog2; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		 }
	 else
		 {
		   $lb_valido      = true;
		   if ($ai_estmodest=='1')
		      {
			    $as_codestpro3 = $this->io_funcion ->uf_cerosizquierda('',25);
				$lb_existe=$this->io_estpro3->uf_spg_select_estprog3($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_estcla);
				if(!$lb_existe)
				{
					$lb_valido = $this->io_estpro3->uf_spg_insert_estprog3($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,'NINGUNO','--',$ai_estmodest,$as_estcla,'1',$aa_seguridad);
					if (!$lb_valido)
				   {
				     $lb_valido = false;
		      	     $this->is_msg_error = "CLASE->SIGESP_SPG_C_ESTPROG3; METODO->uf_spg_insert_estprog3(Insert Nivel 3 Default); ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
				   }
				}

			    
			  }
		   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		   $ls_evento      = "INSERT";
		   $ls_descripcion = "Insertó en SPG Nuevo Estructura Presupuestaria/programatica ".$as_denestpro2." asociado al Nivel 1 con ".$as_codestpro1;
		   $ls_variable    = $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		   $aa_seguridad["ventanas"],$ls_descripcion);
		   /////////////////////////////////         SEGURIDAD               /////////////////////////// 		     
         }
return $lb_valido;
}
	
function uf_spg_update_estprog2($as_codemp,$as_codestpro1,$as_codestpro2,$as_denestpro2,$as_estcla,$aa_seguridad)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_spg_update_estprog4
//	          Access:  public
// 	        Arguments   
//        $as_codemp:  Código de la Empresa.
//    $as_codestpro1:  Código del Primer  Nivel de la Estructura Presupuestaria o Programática.
//    $as_codestpro2:  Código del Segundo Nivel de la Estructura Presupuestaria o Programática.
//    $as_codestpro3:  Código del Tercer  Nivel de la Estructura Presupuestaria o Programática.
//    $as_codestpro4:  Código del Cuarto  Nivel de la Estructura Presupuestaria o Programática.
//     $aa_seguridad:  Arreglo cargado con la información acerca de la ventana,usuario,etc.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de modificar la denominacion de tercer nivel de una Estructura Presupuestaria o Programática, 
//                     la funcion devuelve true si el registro es encontrado caso contrario devuelve false. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  12/09/2006       Fecha Última Actualización:12/09/2006.	 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 

  $ls_sql=" UPDATE spg_ep2 SET denestpro2='".$as_denestpro2."' WHERE codemp='".$as_codemp."' AND codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND  estcla='".$as_estcla."'";
  $this->io_sql->begin_transaction();
  $rs_data=$this->io_sql->execute($ls_sql);
  if ($rs_data===false)
	 {
	   $lb_valido=false;
 	   $this->io_msg->message("CLASE->SIGESP_SPG_C_ESTPROG2; METODO->uf_spg_update_estprog2; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
	   $lb_valido = true;
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	   $ls_evento      = "UPDATE";
	   $ls_descripcion = " Actualizo la denominacion del codigo ".$as_codestpro2." en spg_ep2 asociado al codigo ".$as_codestpro1;
	   $ls_variable    = $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	   $aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////		     
     }  		      
return $lb_valido;
}	

function uf_spg_delete_estpro2($as_codemp,$as_codestpro1,$as_codestpro2,$as_denestpro2,$as_estcla,$aa_seguridad)
{
  $lb_tiene  = $this->uf_check_relaciones($as_codemp,$as_codestpro1,$as_codestpro2,$as_estcla);
  $lb_existe = $this->uf_spg_select_estprog2($as_codemp,$as_codestpro1,$as_codestpro2,$as_estcla);
  $lb_valido = false;
  if (($lb_existe) && (!$lb_tiene))
     {
	   $ls_sql  = "DELETE FROM spg_ep2 WHERE codemp='".$as_codemp."' AND codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' and estcla='".$as_estcla."'";
	   $rs_data = $this->io_sql->execute($ls_sql);
	   if ($rs_data===false)
	      {
		    $lb_valido = false;
			$this->is_msg->message("CLASE->SIGESP_SPG_C_ESTPROG2; METODO->uf_delete_estpro2; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		  }
	   else
	      {
			 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
			 $ls_evento      = "DELETE";
			 $ls_descripcion = "Elimino de Presupuesto la Estructuta 2 con denominacion".$as_denestpro2;
			 $ls_variable    = $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
			 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
			 $aa_seguridad["ventanas"],$ls_descripcion);
			 /////////////////////////////////         SEGURIDAD               /////////////////////////// 		   
			 $lb_valido=true;
		  } 
	   }
return $lb_valido;
}

function uf_check_relaciones($as_codemp,$as_codestpro1,$as_codestpro2,$as_estcla)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_check_relaciones
//	          Access:  public
// 	        Arguments   
//        $as_codemp:  Código de la Empresa.
//    $as_codestpro1:  Código del Primer  Nivel de la Estructura Presupuestaria o Programática.
//    $as_codestpro2:  Código del Segundo Nivel de la Estructura Presupuestaria o Programática.
//    $as_codestpro3:  Código del Tercer  Nivel de la Estructura Presupuestaria o Programática.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de verificar si existen tablas relacionadas al Código de la Clasificación. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:22/03/2006.	 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
  $lb_valido = false;
  $ls_sql  = "SELECT * FROM spg_cuentas WHERE codemp='".$as_codemp."' AND codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."'";
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
	  {
		$lb_valido=false;
        $this->io_msg->message("CLASE->SIGESP_SPG_C_ESTPROG2; METODO->uf_check_relaciones; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	  }
	else
	  {
		if ($row=$this->io_sql->fetch_row($rs_data))
		   {
			 $lb_valido=true;
			 $this->is_msg_error="El Registro no puede ser eliminado, posee registros asociados a otras tablas !!!";
		   }
		else
		   {
			 $ls_sql = "SELECT codestpro2 FROM spg_ep3 WHERE codemp='".$as_codemp."' AND codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND estcla='".$as_estcla."'";
			 $rs_data = $this->io_sql->select($ls_sql);
             if ($rs_data===false)
	            {
		          $lb_valido=false;
                  $this->io_msg->message("CLASE->SIGESP_SPG_C_ESTPROG2; METODO->uf_check_relaciones; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	            }
	         else
	            {
		          if ($row=$this->io_sql->fetch_row($rs_data))
		             {
					   $this->is_msg_error="El Registro no puede ser eliminado, posee registros asociados a otras tablas !!!";
					   $lb_valido=true;
	 	             }
	            }
		   }
	  }
	return $lb_valido;	
}
}
?>		