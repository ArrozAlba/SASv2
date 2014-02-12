<?
 //////////////////////////////////////////////////////////////////////////////////////////
 // Clase:       - sigesp_sob_c_unidad
 // Autor:       - Gerardo Cordero.		
 // Descripcion: - Clase que realiza los procesos basicos (insertar,actualizar,eliminar) con 
 //                respecto a la tabla de unidades de medida.
 // Fecha:       - 08/03/2006     
 //////////////////////////////////////////////////////////////////////////////////////////
class sigesp_sob_c_unidad
{

 var $io_funcion;
 var $io_msgc;
 var $io_sql;
 var $datoemp;
 var $io_msg;
 
 
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sob_c_unidad()
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
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_unidad($ls_coduni,$ls_codtun)
	{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_unidad
		// Parameters:  - $ls_coduni( Codigo de la Unidad de Medida).		
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////	
	
	    $ls_codemp=$this->datoemp["codemp"];
		$ls_sql="SELECT *".
				"  FROM sob_unidad ".
		        " WHERE codemp='".$ls_codemp."'".
				"   AND coduni='".$ls_coduni."'".
				"   AND codtun='".$ls_codtun."'";
		$rs_datauni=$this->io_sql->select($ls_sql);

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
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_unidades($as_coduni,&$aa_data)
	{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_unidad
		// Parameters:  - $ls_coduni( Codigo de la Unidad de Medida).		
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////	
	    $ls_codemp=$this->datoemp["codemp"];
		$ls_sql="SELECT *".
				"  FROM sob_unidad ".
		        " WHERE codemp='".$ls_codemp."'".
				"   AND coduni='".$as_coduni."'";
		$rs_datauni=$this->io_sql->select($ls_sql);

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
				$aa_data=$this->io_sql->obtener_datos($rs_datauni);
			}
			else
			{
				$lb_valido=false;
				$this->io_msgc="Registro no encontrado";
				$aa_data="";
			}
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar_unidad(&$ls_coduni,$ls_codtun,$ls_nomuni,$ls_desuni,$ls_status,$aa_seguridad)
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
			$lb_existe=$this->uf_select_unidad($ls_coduni,$ls_codtun);
			if($lb_existe)
			{
				$lb_valido=$this->uf_update_unidad($ls_coduni,$ls_codtun,$ls_nomuni,$ls_desuni,$ls_status,$aa_seguridad);
			}
			else
			{
				$this->io_msg->message("No existe el tipo de unidad");
			}
		
		}
		else
		{
			$lb_valido=$this->uf_insert_unidad(&$ls_coduni,$ls_codtun,$ls_nomuni,$ls_desuni,$ls_status,$aa_seguridad);
		}
	/*	$ls_codemp=$this->datoemp["codemp"];

		if(!$lb_existe)
		{
            $ls_sql= " INSERT INTO sob_unidad(codemp,coduni,codtun,nomuni,desuni) 
						VALUES('".$ls_codemp."','".$ls_coduni."','".$ls_codtun."','".$ls_nomuni."','".$ls_desuni."') ";
			$ls_evento="INSERT";
		}
		else
		{
			$ls_sql= "UPDATE sob_unidad 
			             SET nomuni='".$ls_nomuni."', desuni='".$ls_desuni."' 
						 WHERE codemp='".$ls_codemp."' AND coduni='".$ls_coduni."' AND codtun='".$ls_codtun."'";			
			$ls_evento="UPDATE";
		}

		$this->io_sql->begin_transaction();

		$li_numrows=$this->io_sql->execute($ls_sql);

		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_guardar_conceptos".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_msgc;
       	}
		else
		{
			if($li_numrows>0)
			{			
				if (!$lb_existe)
				{					
					$this->io_msgc="Registro Incluido!!!";
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion ="Insertó la Unidad".$ls_coduni." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
				else
				{
					$this->io_msgc="Registro Modificado!!!";
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="UPDATE";
					$ls_descripcion ="Actualizó la Unidad".$ls_coduni." Asociado a la Empresa ".$ls_codemp;
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
				if(!$lb_existe) 
				{
					$this->io_msgc="Registro no Incluido!!!";
					$lb_valido=false;
				}	
				else
				{
					$lb_valido=0;
					$lb_valido=false;
				}			
			}

		}*/
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_unidad(&$ls_coduni,$ls_codtun,$ls_nomuni,$ls_desuni,$ls_status,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_insert_unidad
		// Access:			public
		//	Returns:		Boolean, Retorna true si procesa correctamente
		//	Description:	Funcion que se encarga de determinar si una categoria de partida esta siendo utilizada en otra tabla.
		//  Fecha:          22/04/2006
		//	Autor:          Ing. Laura Cabré	
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$lb_valido= $this->io_keygen->uf_verificar_numero_generado("SOB","sob_unidad","coduni","SOB",3,"","","",&$ls_coduni);
		$ls_sql="INSERT INTO sob_unidad(codemp,coduni,codtun,nomuni,desuni) ".
				"	VALUES('".$ls_codemp."','".$ls_coduni."','".$ls_codtun."','".$ls_nomuni."','".$ls_desuni."') ";
		$this->io_sql->begin_transaction();	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			$this->io_sql->rollback();
			if($this->io_sql->errno=='23505' || $this->io_sql->errno=='1062' || $this->io_sql->errno=='-239' || $this->io_sql->errno=='-5'|| $this->io_sql->errno=='-1')
			{
				$lb_valido=$this->uf_insert_unidad(&$ls_coduni,$ls_codtun,$ls_nomuni,$ls_desuni,$ls_status,$aa_seguridad);
			}
			else
			{
				$this->io_msg->message("CLASE->unidad MÉTODO->uf_insert_unidad ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		else
		{
			$this->io_msgc="Registro Incluido!!!";
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la Unidad".$ls_coduni." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$lb_valido=true;
			$this->io_sql->commit();
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_unidad($ls_coduni,$ls_codtun,$ls_nomuni,$ls_desuni,$ls_status,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_detectar_dependencia
		// Access:			public
		//	Returns:		Boolean, Retorna true si procesa correctamente
		//	Description:	Funcion que se encarga de determinar si una categoria de partida esta siendo utilizada en otra tabla.
		//  Fecha:          22/04/2006
		//	Autor:          Ing. Laura Cabré	
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$ls_sql="UPDATE sob_unidad ".
				"   SET nomuni='".$ls_nomuni."', desuni='".$ls_desuni."' ".
				" WHERE codemp='".$ls_codemp."'".
				"   AND coduni='".$ls_coduni."'".
				"   AND codtun='".$ls_codtun."'";			
		$this->io_sql->begin_transaction();	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			$this->io_msg->message("CLASE->unidad MÉTODO->uf_update_unidad ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			$this->io_msgc="Registro Modificado!!!";
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la Unidad".$ls_coduni." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$lb_valido=true;
			$this->io_sql->commit();
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_detectar_dependencia($as_coduni)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_detectar_dependencia
		// Access:			public
		//	Returns:		Boolean, Retorna true si procesa correctamente
		//	Description:	Funcion que se encarga de determinar si una unidad esta siendo utilizada en otra tabla.
		//  Fecha:          17/04/2006
		//	Autor:          Ing. Laura Cabré	
		//////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$this->datoemp["codemp"];
		$ls_sql="SELECT codpar".
				"  FROM sob_partida ".
				" WHERE coduni='".$as_coduni."'".
				"   AND codemp='".$ls_codemp."'";
		$rs_datauni=$this->io_sql->select($ls_sql);
		if($rs_datauni===false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en consulta ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			return $lb_valido;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$this->io_msgc="Esta Unidad no puede ser Eliminada, esta siendo utilizada por una Partida!!!";
				$lb_valido=0;
				return $lb_valido;
			}
			else
			{
				$ls_sql="SELECT codcon". 
						"  FROM sob_contrato ".
						" WHERE placonuni='".$as_coduni."'".
						"    OR mulreuni='".$as_coduni."'".
						"    OR lapgaruni='".$as_coduni."'".
						"   AND codemp='".$ls_codemp."'";
				$rs_datauni=$this->io_sql->select($ls_sql);
				if($rs_datauni===false)
				{
					$lb_valido=false;
					$this->io_msgc="Error en consulta ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
					return $lb_valido;
				}
				else
				{
					if($row=$this->io_sql->fetch_row($rs_datauni))
					{
						$this->io_msgc="Esta Unidad no puede ser Eliminada, esta siendo utilizada por un Contrato!!!";
						$lb_valido=0;
						return $lb_valido;
					}
					else
					{
						$ls_sql="SELECT codact". 
								"  FROM sob_acta ".
								" WHERE coduni='".$as_coduni."'".
								"   AND codemp='".$ls_codemp."'";
						$rs_datauni=$this->io_sql->select($ls_sql);
						if($rs_datauni===false)
						{
							$lb_valido=false;
							$this->io_msgc="Error en consulta ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
							return $lb_valido;
						}
						else
						{
							if($row=$this->io_sql->fetch_row($rs_datauni))
							{
								$this->io_msgc="Esta Unidad no puede ser Eliminada, esta siendo utilizada por un Acta!!!";
								$lb_valido=0;
								return $lb_valido;
							}
							else
							{
								$lb_valido=1;
								return $lb_valido;
							}
						}
					}
				}
				
			}
		}		
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_unidad($ls_coduni,$ls_codtun,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_delete_conceptos
		// Parameters:  - $ls_codigo( Codigo del concepto).		
		// Descripcion: - Funcion que elimina la unidad de medida.
		//////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_unidad($ls_coduni,$ls_codtun);
		if(($lb_existe))
		{
			$lb_permitirdelete=$this->uf_detectar_dependencia($ls_coduni);
			if($lb_permitirdelete===1)
			{
				$ls_sql="DELETE FROM sob_unidad ".
						" WHERE codemp='".$ls_codemp."'".
						"   AND coduni='".$ls_coduni."'".
						"   AND codtun='".$ls_codtun."'";
				$this->io_msgc="Registro Eliminado";		
				$this->io_sql->begin_transaction();
				$li_numrows=$this->io_sql->execute($ls_sql);
				if(($li_numrows==false)&&($this->io_sql->message!=""))
				{
					$lb_valido=false;
					$this->io_sql->rollback();
					$this->io_msg->message("CLASE->unidad MÉTODO->uf_llenarcombo_tipouni ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message)); 
				}
				else
				{
						$lb_valido=true;
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
						$ls_evento="DELETE";
						$ls_descripcion ="Eliminó la unidad ".$ls_coduni." Asociado a la Empresa ".$ls_codemp;
						$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
						$this->io_sql->commit();
				}
			}	
			elseif ($lb_permitirdelete===0)
			{
				$this->io_msg->message($this->io_msgc);
			}		
		}
		else
		{
			$this->io_msg->message("El Registro no Existe");
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_llenarcombo_tipouni(&$aa_data)
	{
	 //////////////////////////////////////////////////////////////////////////////
	 //	Metodo: uf_llenarcombo_tenencia	 //
	 //	Access:  public
	 //	Returns: arreglo con los tipos de tenencia
	 //	Description: Funcion que permite llenar el combo de tenencias, retorna true
	 //              y el arreglo si todo marcha adecuadamente
	 // Fecha: 08/03/2006
     // Autor: Laura Cabré
	 //////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codtun,nomtun".
				"  FROM sob_tipounidad ".
				"ORDER BY codtun ASC";
		$lr_data=$this->io_sql->select($ls_sql);
		if($lr_data===false)
		{
			$this->io_msg->message("CLASE->unidad MÉTODO->uf_llenarcombo_tipouni ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($lr_data))
			{
				$lb_valido=true;
				$aa_data=$this->io_sql->obtener_datos($lr_data);
			}else
			{
				$aa_data="";
			}			
		}		
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>
