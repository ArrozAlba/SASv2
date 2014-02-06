<?
 //////////////////////////////////////////////////////////////////////////////////////////
 // Clase:       - sigesp_sob_c_tipoobra
 // Autor:       - Gerardo Cordero.		
 // Descripcion: - Clase que realiza los procesos basicos (insertar,actualizar,eliminar) con 
 //                respecto a la tabla tipo obra.
 // Fecha:       - 10/03/2006     
 //////////////////////////////////////////////////////////////////////////////////////////
class sigesp_sob_c_propietario
{

 var $io_funcion;
 var $io_msgc;
 var $io_sql;
 var $datoemp;
 var $io_msg;
 
 
function sigesp_sob_c_propietario()
{
	require_once ("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$this->seguridad=   new sigesp_c_seguridad();
	$this->io_funcion = new class_funciones();		
	$io_include = new sigesp_include();
	$io_connect = $io_include->uf_conectar();		
	$this->io_sql= new class_sql($io_connect);
	$this->datoemp=$_SESSION["la_empresa"];	
	require_once("../shared/class_folder/class_mensajes.php");
	$this->io_msg=new class_mensajes();
	require_once("../shared/class_folder/sigesp_c_generar_consecutivo.php");
	$this->io_keygen= new sigesp_c_generar_consecutivo();
	
}

		
	function uf_select_propietario($ls_codpro)
	{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_unidad
		// Parameters:  - $ls_codtob( Codigo del Tipo de Obra).		
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////	
	
	    $ls_codemp=$this->datoemp["codemp"];
		$ls_cadena="SELECT * FROM sob_propietario 
		            WHERE codemp='".$ls_codemp."' AND codpro='".$ls_codpro."'";
		$rs_datauni=$this->io_sql->select($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en consulta ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$lb_valido=true;
			}
			else
			{
				$lb_valido=false;
				$this->io_msgc="Registro no encontrado";
			}
		}
		return $lb_valido;
	}
	

	function uf_guardar_propietario(&$ls_codpro,$ls_nompro,$ls_telpro,$ls_dirpro,$ls_nomresppro,$ls_faxpro,$ls_emapro,$ls_status,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_guardar_unidad
		// Parameters:  - $ls_coduni( Codigo de la Unidad de Medida).		
		//			    - $ls_codtun( Codigo de el tipo de Unidad de Medida). 	
		//			    - $ls_nomuni( Nombre de la Unidad).
		//              - $ls_desuni( Breve Descripcion de la Unidad).
		// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
		//////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		if($ls_status=="C")
		{
			$lb_existe=$this->uf_select_propietario($ls_codpro);
			if($lb_existe)
			{
				$lb_valido=$this->uf_update_propietario($ls_codpro,$ls_nompro,$ls_telpro,$ls_dirpro,$ls_nomresppro,$ls_faxpro,$ls_emapro,$aa_seguridad);
			}
			else
			{
				$this->io_msg->message("No existe el registro");
			}
			
		}
		else
		{
			$lb_valido=$this->uf_insert_propietario(&$ls_codpro,$ls_nompro,$ls_telpro,$ls_dirpro,$ls_nomresppro,$ls_faxpro,$ls_emapro,$aa_seguridad);
		}
		if(!$lb_valido)
		{
			$this->io_msgc="Ocurrio un error al procesar la solicitud";	
		}
/*		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_propietario($ls_codpro);

		if(!$lb_existe)
		{
            $ls_cadena= " INSERT INTO sob_propietario(codemp,codpro,nompro,telpro,dirpro,nomresppro,faxpro,emapro) 
			              VALUES ('".$ls_codemp."','".$ls_codpro."','".$ls_nompro."','".$ls_telpro."','".$ls_dirpro."','".$ls_nomresppro."','".$ls_faxpro."','".$ls_emapro."') ";
			$this->io_msgc="Registro Incluido!!!";	
			$ls_evento="INSERT";	
		}
		else
		{
			$ls_cadena= "UPDATE sob_propietario 
			             SET nompro='".$ls_nompro."', telpro='".$ls_telpro."', dirpro='".$ls_dirpro."', nomresppro='".$ls_nomresppro."', faxpro='".$ls_faxpro."', emapro='".$ls_emapro."'  
						 WHERE codemp='".$ls_codemp."' AND codpro='".$ls_codpro."'";
			$this->io_msgc="Registro Actualizado!!!";
			$ls_evento="UPDATE";
		}

		$this->io_sql->begin_transaction();

		$li_numrows=$this->io_sql->execute($ls_cadena);

		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_guardar_conceptos".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_msgc;
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
       	}
		else
		{
			if($li_numrows>0)
			{
				$lb_valido=true;
				if($ls_evento=="INSERT")
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion ="Insertó el Propietario ".$ls_codpro." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="UPDATE";
					$ls_descripcion ="Actualizó el Propietario ".$ls_codpro." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				}
				$this->io_sql->commit();
			}
			else
			{
				$this->io_sql->rollback();
				if($lb_existe)
				{					
					$lb_valido=0;
					$this->io_msgc="No actualizo el registro";					
				}
				else
				{
					$lb_valido=false;
					$this->io_msgc="Registro No Incluido!!!";
					
				}
			}

		}*/
		return $lb_valido;
	}
	
	function uf_insert_propietario(&$ls_codpro,$ls_nompro,$ls_telpro,$ls_dirpro,$ls_nomresppro,$ls_faxpro,$ls_emapro,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_insert_propietario
		// Access:			public
		//	Returns:		Boolean, Retorna true si procesa correctamente
		//	Description:	Funcion que se encarga de determinar si una categoria de partida esta siendo utilizada en otra tabla.
		//  Fecha:          22/04/2006
		//	Autor:          Ing. Laura Cabré	
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$lb_valido= $this->io_keygen->uf_verificar_numero_generado("SOB","sob_propietario","codpro","SOB",3,"","","",&$ls_codpro);
		$ls_sql=" INSERT INTO sob_propietario(codemp,codpro,nompro,telpro,dirpro,nomresppro,faxpro,emapro)".
				"    VALUES ('".$ls_codemp."','".$ls_codpro."','".$ls_nompro."','".$ls_telpro."','".$ls_dirpro."','".$ls_nomresppro."',".
				"            '".$ls_faxpro."','".$ls_emapro."') ";
		$this->io_sql->begin_transaction();	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			$this->io_sql->rollback();
			if($this->io_sql->errno=='23505' || $this->io_sql->errno=='1062' || $this->io_sql->errno=='-239' || $this->io_sql->errno=='-5'|| $this->io_sql->errno=='-1')
			{
				$lb_valido=$this->uf_insert_propietario(&$ls_codpro,$ls_nompro,$ls_telpro,$ls_dirpro,$ls_nomresppro,$ls_faxpro,$ls_emapro,$aa_seguridad);
			}
			else
			{
				$this->io_msg->message("CLASE->Propietario MÉTODO->uf_insert_propietario ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el Propietario ".$ls_codpro." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$this->io_msgc="Registro Incluido!!!";	
			$lb_valido=true;
			$this->io_sql->commit();
		}
		return $lb_valido;
	}
	
	function uf_update_propietario($ls_codpro,$ls_nompro,$ls_telpro,$ls_dirpro,$ls_nomresppro,$ls_faxpro,$ls_emapro,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_update_propietario
		// Access:			public
		//	Returns:		Boolean, Retorna true si procesa correctamente
		//	Description:	Funcion que se encarga de determinar si una categoria de partida esta siendo utilizada en otra tabla.
		//  Fecha:          22/04/2006
		//	Autor:          Ing. Laura Cabré	
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$ls_sql="UPDATE sob_propietario".
				"   SET nompro='".$ls_nompro."', telpro='".$ls_telpro."', dirpro='".$ls_dirpro."',".
				"       nomresppro='".$ls_nomresppro."', faxpro='".$ls_faxpro."', emapro='".$ls_emapro."'  ".
				" WHERE codemp='".$ls_codemp."'".
				"   AND codpro='".$ls_codpro."'";
		$this->io_sql->begin_transaction();	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			$this->io_msg->message("CLASE->Propietario MÉTODO->uf_update_propietario ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			$this->io_msgc="Registro Actualizado!!!";
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Propietario ".$ls_codpro." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$lb_valido=true;
			$this->io_sql->commit();
		}
		return $lb_valido;
	}


	function uf_detectar_dependencia($as_codpro)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_detectar_dependencia
		// Access:			public
		//	Returns:		Boolean, Retorna true si procesa correctamente
		//	Description:	Funcion que se encarga de determinar si un organismo esta siendo utilizada en otra tabla.
		//  Fecha:          17/04/2006
		//	Autor:          Ing. Laura Cabré	
		//////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$this->datoemp["codemp"];
		$ls_cadena="SELECT codobr 
					FROM sob_obra 
					WHERE codpro='".$as_codpro."' AND codemp='".$ls_codemp."'";
		$rs_datauni=$this->io_sql->select($ls_cadena);
		if($rs_datauni===false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en consulta ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$this->io_msgc="Este Organismo no puede ser eliminado, esta siendo utilizado por una Obra!!!";
				$lb_valido=0;
			}
			else
			{
				$lb_valido=1;
			}
		}
		return $lb_valido;
	}

	function uf_delete_propietario($ls_codpro,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_delete_conceptos
		// Parameters:  - $ls_codigo( Codigo del concepto).		
		// Descripcion: - Funcion que elimina la unidad de medida.
		//////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_propietario($ls_codpro);
		
		if(($lb_existe))
		{
			$lb_permitirdelete=$this->uf_detectar_dependencia($ls_codpro);
			if($lb_permitirdelete)
			{			
				$ls_cadena= " DELETE FROM sob_propietario 
							  WHERE codemp='".$ls_codemp."' AND codpro='".$ls_codpro."'";
				$this->io_msgc="Registro Eliminado!!!";		
	
				$this->io_sql->begin_transaction();
	
				$li_numrows=$this->io_sql->execute($ls_cadena);
	
				if(($li_numrows==false)&&($this->io_sql->message!=""))
				{
					$lb_valido=false;
					$this->io_sql->rollback();
					$this->io_msgc="Error en metodo uf_delete_propietario ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
					print $this->io_msgc;
					print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
					print $ls_cadena;
				}
				else
				{
						$lb_valido=true;
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
						$ls_evento="DELETE";
						$ls_descripcion ="Eliminó el Propietario ".$ls_codpro." Asociado a la Empresa ".$ls_codemp;
						$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////			
						$this->io_sql->commit();
				}
			}
			elseif($lb_permitirdelete===0)
			{
				$lb_valido=false;				
				$this->io_msg->message($this->io_msgc);
				$lb_valido=1;
			}
		}
		else
		{
			$lb_valido=1;
			$this->io_msg->message("El Registro no Existe");
		}
		return $lb_valido;
	}

	
}
?>
