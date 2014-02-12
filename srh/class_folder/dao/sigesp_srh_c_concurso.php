<?php

class sigesp_srh_c_concurso
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;
	var $codcon = null;	
	var $descon = null;
	var $codemp = null;

	function sigesp_srh_c_concurso($path)
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

function uf_srh_getProximoCodigo()
  {
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_getProximoCodigo
		//         Access: public (sigesp_srh_d_concurso)
		//      Argumento: 
		//	      Returns: Retorna el nuevo cÃ³digo de un concurso
		//    Description: Funcion que genera un cÃ³digo un concurso
		//	   Creado Por: Maria Beatriz Unda
		// Fecha CreaciÃ³n:13/01/2008							Fecha Ãšltima ModificaciÃ³n:13/01/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $ls_sql = "SELECT MAX(codcon) AS codigo FROM srh_concurso ";
    $lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
	if ($lb_hay)
    $ls_codcon = $la_datos["codigo"][0]+1;
    $ls_codcon = str_pad ($ls_codcon,10,"0","left");
    return $ls_codcon;
  }


	function uf_srh_select_concurso($as_codcon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_select_concurso
		//         Access: public (sigesp_srh_d_concurso)
		//      Argumento: $as_codcon    // codigo del concurso
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza una busqueda de una concurso en la tabla de  srh_concurso
		//	   Creado Por: Maria Beatriz Unda
		// Fecha CreaciÃ³n: 31/08/2007							Fecha Ãšltima ModificaciÃ³n: 31/08/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM srh_concurso  ".
				  " WHERE codcon='".trim($as_codcon)."'";
				  " AND codemp='".$this->ls_codemp."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->concurso MÃ‰TODO->uf_srh_select_concurso ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  //  end function uf_srh_select_concurso

	function  uf_srh_insert_concurso($as_codcon,$as_descon,$as_fechaaper,$as_fechacie,$as_codcar,$ai_cantcar,$as_estatus,$as_tipo, $as_codnom,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_insert_concurso
		//         Access: public (sigesp_srh_d_concurso)
		//      Argumento: $as_codcon        // codigo de la concurso
	    //                 $as_descon       // descripcion del concurso
		//		           $$ls_fechaaper  // fecha de apertura del concurso
		//			       $ls_fechacie   // fecha de cierre del concurso
		//                 $as_codcar    // codigo del cargo
		//				   &ai_cantcar   // cantidad de cargos para el concurso
		//                 $as_estatus  // estatus del concurso
		//                 $as_tipo  // tipo de concurso
	    //                 $aa_seguridad  // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un concurso  en la tabla de srh_concurso
		//	   Creado Por: Maria Beatriz Unda
		// Fecha CreaciÃ³n: 31/08/2007							Fecha Ãšltima ModificaciÃ³n: 04/09/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
	
        $this->io_sql->begin_transaction();
		
		$as_fechacie=$this->io_funcion->uf_convertirdatetobd($as_fechacie);
		$as_fechaaper=$this->io_funcion->uf_convertirdatetobd($as_fechaaper);

				
		$ls_sql = "INSERT INTO srh_concurso (codcon,descon,fechaaper,fechacie,codcar,cantcar,estatus,tipo,codnom,codemp) ".
					" VALUES ('".$as_codcon."','".$as_descon."','".$as_fechaaper."','".$as_fechacie."','".$as_codcar."','".$ai_cantcar."','".$as_estatus."','".$as_tipo."', '".$as_codnom."','".$this->ls_codemp."')" ;
		


		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->concurso MÃ‰TODO->uf_srh_insert_concurso ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="InsertÃ³ el concurso ".$as_codcon;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],

												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$this->io_sql->commit();
		}
		return $lb_valido;
	} // end  function  uf_srh_insert_concurso

	function uf_srh_update_concurso($as_codcon,$as_descon,$as_fechaaper,$as_fechacie,$as_codcar,$ai_cantcar, $as_estatus,$as_tipo, $as_codnom,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_update_concurso
		//         Access: public (sigesp_srh_d_concurso)
		//      Argumento: $as_codcon  // codigo de la concurso
	    //                  $as_descon       // descripcion del concurso
		//		           $$ls_fechaaper  // fecha de apertura del concurso
		//			       $ls_fechacie   // fecha de cierre del concurso
		//                 $as_codcar    // codigo del cargo
		//                 $ai_cantcar	// cantidad de cargos para el concurso
		//                 $as_estatus  // estatus del concurso
		//                 $as_tipo  // tipo de concurso
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que modifica un concurso en la tabla de srh_concurso
		//	   Creado Por: Maria Beatriz Unda
		// Fecha CreaciÃ³n: 31/08/2007							Fecha Ãšltima ModificaciÃ³n: 31/08/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $as_fechacie=$this->io_funcion->uf_convertirdatetobd($as_fechacie);
		 $as_fechaaper=$this->io_funcion->uf_convertirdatetobd($as_fechaaper);
	  	 
		 $ls_sql = "UPDATE srh_concurso SET   descon='". $as_descon."',  fechaaper='".$as_fechaaper."',  fechacie='".$as_fechacie."', codcar='".$as_codcar."', cantcar='".$ai_cantcar."',tipo='".$as_tipo."', codnom='".$as_codnom."',estatus='". $as_estatus."'". 
				   " WHERE codcon='" . $as_codcon ."'".
				   " AND codemp='".$this->ls_codemp."'";
				   			   
				   
        $this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->concurso MÃ‰TODO->uf_srh_update_concurso ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modifico el concurso ".$as_codcon;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	  return $lb_valido;
	} // end  function uf_srh_update_concurso
	
	
	function uf_select_concurso_persona ($as_codcon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_concurso_persona
		//		   Access: private
 		//	    Arguments: as_codcon // código del concurso
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el concurso esta asociada a una persona
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 25/04/2008								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT codcon ".
				 "  FROM srh_asignar_concurso".
				 "  WHERE codemp='".$this->ls_codemp."' ".
				 "    AND codcon='".$as_codcon."' ";
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->concurso ->uf_select_concurso_persona  ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if(!$row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_existe;
	}
	
	
 function uf_select_concurso_requisitosminimos ($as_codcon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_concurso_requisitosminimos
		//		   Access: private
 		//	    Arguments: as_codcon // código del concurso
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el concurso esta asociada a un requisito minimo
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 25/04/2008								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT codcon ".
				 "  FROM srh_requisitos_minimos".
				 "  WHERE codemp='".$this->ls_codemp."' ".
				 "    AND codcon='".$as_codcon."' ";
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->concurso ->uf_select_concurso_requisitosminimos  ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if(!$row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_existe;
	}

function uf_select_concurso_evaluacion_psicologica ($as_codcon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_concurso_evaluacion_psicologica
		//		   Access: private
 		//	    Arguments: as_codcon // código del concurso
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el concurso esta asociada a una evaluacion psicologica
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 25/04/2008								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT codcon ".
				 "  FROM srh_evaluacion_psicologica".
				 "  WHERE codemp='".$this->ls_codemp."' ".
				 "    AND codcon='".$as_codcon."' ";
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->concurso ->uf_select_concurso_evaluacion_psicologica ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if(!$row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_existe;
	}

	
function uf_select_concurso_entrevista_tecnica ($as_codcon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_concurso_entrevista_tecnica
		//		   Access: private
 		//	    Arguments: as_codcon // código del concurso
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el concurso esta asociada a una entrevista tecnica
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 25/04/2008								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT codcon ".
				 "  FROM srh_entrevista_tecnica".
				 "  WHERE codemp='".$this->ls_codemp."' ".
				 "    AND codcon='".$as_codcon."' ";
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->concurso ->uf_select_concurso_entrevista_tecnica ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if(!$row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_existe;
	}


function uf_select_concurso_resultados_evaluacion ($as_codcon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_concurso_resultados_evaluacion
		//		   Access: private
 		//	    Arguments: as_codcon // código del concurso
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el concurso esta asociada a un resultado de evaluacion aspirante
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 25/04/2008								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT codcon ".
				 "  FROM srh_resultados_evaluacion_aspirante".
				 "  WHERE codemp='".$this->ls_codemp."' ".
				 "    AND codcon='".$as_codcon."' ";
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->concurso ->uf_select_concurso_resultados_evaluacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if(!$row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_existe;
	}
	
	
function uf_select_concurso_ascenso ($as_codcon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_concurso_ascenso
		//		   Access: private
 		//	    Arguments: as_codcon // código del concurso
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el concurso esta asociada a un registro de ascenso
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 25/04/2008								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT codcon ".
				 "  FROM srh_registro_ascenso".
				 "  WHERE codemp='".$this->ls_codemp."' ".
				 "    AND codcon='".$as_codcon."' ";
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->concurso ->uf_select_concurso_ascenso ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if(!$row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_existe;
	}
	
	
function uf_select_concurso_requisitos ($as_codcon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_concurso_requisitos
		//		   Access: private
 		//	    Arguments: as_codcon // código del concurso
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el concurso esta asociada a un requisito de concurso
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 25/04/2008								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT codcon ".
				 "  FROM srh_requisitos_concurso".
				 "  WHERE codemp='".$this->ls_codemp."' ".
				 "    AND codcon='".$as_codcon."' ";
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->concurso->uf_select_concurso_requisitos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if(!$row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_existe;
	}
	
	function uf_select_concurso_ganadores ($as_codcon)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_concurso_ganadores
		//		   Access: private
 		//	    Arguments: as_codcon // código del concurso
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el concurso esta asociada a un ganador de concurso
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación: 25/04/2008								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;	    
		$ls_sql= "SELECT codcon ".
				 "  FROM srh_ganadores_concurso".
				 "  WHERE codemp='".$this->ls_codemp."' ".
				 "    AND codcon='".$as_codcon."' ";
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_msg->message("CLASE->concurso ->uf_select_concurso_ganadores ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if(!$row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_existe;
	}
	

	function uf_srh_delete_concurso($as_codcon,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_delete_factor
		//         Access: public (sigesp_srh_d_concurso)
		//      Argumento: $as_codcon  // codigo del concurso
	    //                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un concurso en la tabla de srh_concurso  
		//     Creado Por: Maria Beatriz Unda
		// Fecha CreaciÃ³n: 31/08/2007							Fecha Ãšltima ModificaciÃ³n: 31/08/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=false;
	     $lb_existe=true;
		
		if (($this->uf_select_concurso_persona ($as_codcon)===false)&&
		     ($this->uf_select_concurso_requisitosminimos($as_codcon)===false)&&
			 ($this->uf_select_concurso_evaluacion_psicologica ($as_codcon)===false) &&
			 ($this->uf_select_concurso_entrevista_tecnica ($as_codcon)===false)&&
			 ($this->uf_select_concurso_resultados_evaluacion($as_codcon)===false)&&
			 ($this->uf_select_concurso_ascenso($as_codcon)===false)&&
			 ($this->uf_select_concurso_ganadores($as_codcon)===false)&&
			 ($this->uf_select_concurso_requisitos($as_codcon)===false))
		 {
		    $lb_existe=false;
			$this->io_sql->begin_transaction();	
			$ls_sql = " DELETE FROM srh_concurso".
						 " WHERE codcon= '".$as_codcon. "'"; 
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false){
			$this->io_msg->message("CLASE->concurso MÃ‰TODO->uf_srh_delete_concurso ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="EliminÃ³ el concurso ".$as_codcon;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
		}
		
		return array($lb_valido,$lb_existe);
	} // end function uf_srh_delete_concurso
	

		
	
	function uf_srh_buscar_concurso($as_codcon,$as_descon,$as_fechaaper1,$as_fechaaper2,$as_fechacie1,$as_fechacie2,$as_estatus,$as_tipo,$as_tipo_caja)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_concurso
		//         Access: private
		//      Argumento: $as_codcon  // codigo de la concurso
		//	      Returns: Retorna un Booleano
		//    Description: Funcion busca un concurso  para luego mostrarla
		//	   Creado Por: MarÃ­a Beatriz Unda
		// Fecha CreaciÃ³n: 04/09/2007							Fecha Ãšltima ModificaciÃ³n: 04/09/2007
		//  Modificado por: Jennifer Rivero						Fecha de modificiòn: 28/02/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
				
		if (empty($as_estatus))
			{
			  $as_estatus='%%';
			}
		
		$as_fechacie1=$this->io_funcion->uf_convertirdatetobd($as_fechacie1);
		$as_fechacie2=$this->io_funcion->uf_convertirdatetobd($as_fechacie2);
		$as_fechaaper2=$this->io_funcion->uf_convertirdatetobd($as_fechaaper2);
		$as_fechaaper1=$this->io_funcion->uf_convertirdatetobd($as_fechaaper1);
		
		
		if (empty($as_fechaaper2))
			{
			  $as_fechaaper2='1/01/2108';
			}
			
		if (empty($as_fechacie2))
			{
			  $as_fechacie2='1/01/2108';
			}
		
		if (empty($as_fechaaper1))
			{
			  $as_fechaaper1='01/01/1900';
			}
			
		if (empty($as_fechacie1))
			{
			  $as_fechacie1='01/01/1900';
			}
		
		$lb_valido=true;
		
		$ls_sql="SELECT srh_concurso.*, sno_cargo.descar, sno_cargo.codcar, sno_asignacioncargo.denasicar, ".
		        "  sno_asignacioncargo.codasicar  FROM srh_concurso  ".
		        " LEFT JOIN sno_cargo ON (srh_concurso.codcar = sno_cargo.codcar AND srh_concurso.codnom = sno_cargo.codnom)
				   LEFT JOIN sno_asignacioncargo ON (srh_concurso.codcar = sno_asignacioncargo.codasicar AND srh_concurso.codnom = sno_asignacioncargo.codnom) ".          
				" WHERE codcon like '".$as_codcon."' ".		
				"  AND descon like '".$as_descon."' ".				
				 " AND  fechaaper between  '".$as_fechaaper1."' AND '".$as_fechaaper2."' " .
                 " AND  fechacie between '".$as_fechacie1."' AND '".$as_fechacie2."' ".
 				 " AND  estatus like '".$as_estatus."' ".
			   " ORDER BY codcon";

		$rs_data=$this->io_sql->select($ls_sql);
		
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->concurso METODO->uf_srh_buscar_concurso( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			     
					$ls_codcon=$row["codcon"];
					$ls_descon= trim (htmlentities  ($row["descon"]));
					$ls_fechaaper=$this->io_funcion->uf_formatovalidofecha($row["fechaaper"]);
				    $ls_fechaaper=$this->io_funcion->uf_convertirfecmostrar($ls_fechaaper);
					
					$ls_fechacie=$this->io_funcion->uf_formatovalidofecha($row["fechacie"]);
				    $ls_fechacie=$this->io_funcion->uf_convertirfecmostrar($ls_fechacie);				
				
					$ls_estatus=trim ($row["estatus"]);
					$ls_tipo=trim ($row["tipo"]);
					
					$ls_codcar1=$row["codasicar"];
					$ls_codcar2=$row["codcar"];
					 $ls_codnom=trim($row["codnom"]);
					 if ($ls_codcar1=="")
					 {	
					 	$ls_codcar=$row["codcar"];
						$ls_descar=trim ( htmlentities ($row["descar"]));
					 }
					 else
					 {
					   	$ls_descar=trim (htmlentities ($row["denasicar"]));
					    $ls_codcar=$row["codasicar"];
						
					 }
				
					$li_cantcar=$row["cantcar"];
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['codcon']);
					$cell = $row_->appendChild($dom->createElement('cell'));  
		//-------------------------------agregado el 28/02/2008-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
					switch ($as_tipo)
				    {
					  case  "M":
					  	$ls_coddestino="txtcodcon";
						$ls_dendestino="txtdescon";
						$ls_tipodestino="combotipo";
						$ls_codnomdestino="txtcodnom";
	
						$ls_fechaaperdestino="txtfechaaper";
						$ls_fechaciedestino="txtfechacie";
						$ls_estatusdestino="comboestatus";
		
						$ls_codcardestino="txtcodcar";
						$ls_descardestino="txtdescar";
						$li_cantcardestino="txtcantcar"; 
												
					   $cell->appendChild($dom->createTextNode($row['codcon']." ^javascript:aceptar(\"$ls_codcon\",\"$ls_descon\",\"$ls_coddestino\",\"$ls_dendestino\",\"$ls_fechaaperdestino\",\"$ls_fechaaper\", \"$ls_fechaciedestino\",\"$ls_fechacie\",\"$ls_codcardestino\",\"$ls_codcar\", \"$ls_descar\", \"$ls_descardestino\", \"$li_cantcardestino\",\"$li_cantcar\",\"$ls_estatusdestino\",\"$ls_estatus\",\"$ls_tipodestino\",\"$ls_tipo\",\"$ls_codnom\", \"$ls_codnomdestino\");^_self"));				 
					  break;
					  
					  case "R":
					  	if($as_tipo_caja=="1")
		         		 {
		        			$ls_codcurdestino="txtcurdes";
		        			$cell->appendChild($dom->createTextNode($row['codcon']." ^javascript:aceptar_origen(\"$ls_codcon\",\"$ls_codcurdestino\");^_self"));		        			
		        	     }
		        		elseif($as_tipo_caja=="2")
		        		{
		        			$ls_codcurhasta="txtcurhas";
		        			$cell->appendChild($dom->createTextNode($row['codcon']." ^javascript:aceptar_hasta(\"$ls_codcon\",\"$ls_codcurhasta\");^_self"));		        			
		        		}					  	
					  				 
					  break;
				    }
		//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------		    
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_descon));												
					$row_->appendChild($cell);
						
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_fechaaper));												
					$row_->appendChild($cell);
				
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_fechacie));												
					$row_->appendChild($cell);
				
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($row['estatus']));												
					$row_->appendChild($cell);
					
			
			}
			return $dom->saveXML();
		

		}
        // Response xml
		
	} // end function uf_srh_buscar_concurso(
	

}// end   class sigesp_srh_c_concurso
?>