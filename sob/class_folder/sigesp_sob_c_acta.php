<?PHP
class sigesp_sob_c_acta
{
	var $io_funcion;
	var $is_msg_error;
	var $io_sql;
	var $la_empresa;
	var $io_msg;
		
	function sigesp_sob_c_acta()
	{						
		require_once ("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once ("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$this->seguridad=   new sigesp_c_seguridad();
		$this->io_function = new class_funciones();		
		$this->io_msg= new class_mensajes();		
		$io_include=new sigesp_include();
		$io_connect=$io_include->uf_conectar();		
		$this->io_sql= new class_sql($io_connect);					
		$this->la_empresa=$_SESSION["la_empresa"];
		require_once ("sigesp_c_generar_consecutivo_acta.php");
		$this->io_keygen= new sigesp_c_generar_consecutivo_acta(); 
	}
	
function uf_update_estado($as_codcon,$as_codact,$ai_tipact,$ai_estado,$aa_seguridad)
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_update_estado
	// Access:			public
	//	Returns:		Boolean, Retorna true si procesa correctamente
	//	Description:	Funcion que se encarga de actualizar el estado del Acta
	//  Fecha:          11/04/2006
	//	Autor:          Ing. Laura Cabré				
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$this->la_empresa["codemp"];
	$ls_sql="UPDATE sob_acta 
				 SET estact='".$ai_estado."'
				 WHERE codemp='".$ls_codemp."' AND codcon='".$as_codcon."' AND codact='".$as_codact."' AND tipact='".$ai_tipact."'";		
	
	$this->io_sql->begin_transaction();	
	$li_row=$this->io_sql->execute($ls_sql);
	if($li_row===false)
	{			
		print "Error en metodo uf_update_estadoacta".$this->io_function->uf_convertirmsg($this->io_sql->message);
		$this->io_sql->rollback();
	}
	else
	{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el estado del Acta ".$as_codact.", de Tipo ".$ai_tipact." del Contrato ".$as_codcon." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		

			$this->io_sql->commit();
			$lb_valido=true;
	}		
	return $lb_valido;
}	

function uf_select_estado ($as_codcon,$as_codact,$ai_tipact,&$ai_estado)
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_select_estado
	// Access:			public
	//	Returns:		Boolean, Retorna true si existe el registro en bd
	//	Description:	Funcion que se encarga de verificar el estado del Acta
	//  Fecha:          11/04/2006
	//	Autor:          Ing. Laura Cabré		
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$this->la_empresa["codemp"];
	$ls_sql="SELECT estact
			 FROM sob_acta
			 WHERE codemp='".$ls_codemp."' AND codact='".$as_codact."' AND tipact='".$ai_tipact."' AND codcon='".$as_codcon."'";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
		print "Error en select estadoacta".$this->io_function->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		if($la_row=$this->io_sql->fetch_row($rs_data))
		{
			$ai_estado=$la_row["estact"];
			$lb_valido=true;
		}		
	}
	return $lb_valido;
}
	
function uf_select_acta($as_codcon,$as_codact,$as_tipact,&$aa_data)
{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_select_acta
		//  Access:			public
		//	Returns:		Boolean, Retorna true si existe el registro en bd
		//	Description:	Funcion que se encarga de retornar la informacion de un acta
		//  Fecha:          08/04/2006
		//	Autor:          Ing. Laura Cabré
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_empresa=$this->la_empresa["codemp"];
		$ls_sql="SELECT *
				FROM sob_acta a
				WHERE a.codemp='".$ls_empresa."' AND codcon='".$as_codcon."' AND codact='".$as_codact."' AND tipact='".$as_tipact."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$aa_data=$this->io_sql->obtener_datos($rs_data);
				$lb_valido=true;
			}
			else
			{
				//$this->is_msg_error="No encontro registro";
				$lb_valido=0;
				$aa_data="";
			}
		}
		return $lb_valido;	
}

function uf_select_actas($as_codcon,$as_tipact,&$aa_data)
{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_select_actas
		//  Access:			public
		//	Returns:		Boolean, Retorna true si existe el registro en bd
		//	Description:	Funcion que se encarga de retornar la informacion de todas las actas de un tipo
		//					pertenencientes a un contrato
		//  Fecha:          22/06/2006
		//	Autor:          Ing. Laura Cabré
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_empresa=$this->la_empresa["codemp"];
		$ls_sql="SELECT *
				FROM sob_acta a
				WHERE a.codemp='".$ls_empresa."' AND codcon='".$as_codcon."' AND tipact='".$as_tipact."'
				ORDER BY a.fecact DESC";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en uf_select_actas".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$aa_data=$this->io_sql->obtener_datos($rs_data);
				$lb_valido=true;
			}
			else
			{
				//$this->is_msg_error="No encontro registro";
				$lb_valido=0;
				$aa_data="";
			}
		}
		return $lb_valido;	
}


function uf_guardar_acta($as_codcon,&$as_codact,$as_tipact,$af_fecact,$af_feciniact,$af_fecfinact,$af_feccreact,$as_persusact,$as_coduni,$as_motact,
						 $as_repempact,$as_cedinsact,$as_cedresact,$as_cedsupact,$af_fecregvalact,$as_obsact,$as_civinsact,$as_nomresact,$as_civresact,$aa_seguridad)             
{ 
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_guardar_acta
	//  Access:			public
	//	Returns:		Boolean, Retorna true si procesa correctamente
	//	Description:	Funcion que se encarga de guardar el acta.
	//  Fecha:          10/04/2006
	//	Autor:          Ing. Laura Cabré				
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$this->la_empresa["codemp"];
	$lb_valido= $this->io_keygen->uf_verificar_numero_generado("SOB","sob_acta","codact","SOBACT",6,"","codcon",$as_codcon,"tipact",$as_tipact,&$as_codact);
	if($lb_valido)
	{
		$ls_sql="INSERT INTO sob_acta (codemp, codcon,codact,tipact";
		if ($af_fecact!="")
		{
			$ls_sql=$ls_sql.",fecact";
		}
		if ($af_feciniact!="")
		{
			$ls_sql=$ls_sql.",feciniact";
		}
		if ($af_fecfinact!="")
		{
			$ls_sql=$ls_sql.",fecfinact";
		}
		if ($af_feccreact!="")
		{
			$ls_sql=$ls_sql.",fecrecact";
		}	
		if ($af_fecregvalact!="")
		{
			$ls_sql=$ls_sql.",fecregvalact";
		}
		
		if ($as_civinsact!="")
		{
			$ls_sql=$ls_sql.",civinsact";
		}	
		if ($as_nomresact!="")
		{
			$ls_sql=$ls_sql.",nomresact";
		}	
		if ($as_civresact!="")
		{
			$ls_sql=$ls_sql.",civresact";
		}	
		$ls_sql=$ls_sql.",persusact,coduni,motact,repempact,cedinsact,cedresact,cedsupact,obsact,estact) VALUES ('".$ls_codemp."','".$as_codcon."','".$as_codact."','".$as_tipact."'";
		if ($af_fecact!="")
		{
			$ls_sql=$ls_sql.",'".$af_fecact."'";
		}
		if ($af_feciniact!="")
		{
			$ls_sql=$ls_sql.",'".$af_feciniact."'";
		}
		if ($af_fecfinact!="")
		{
			$ls_sql=$ls_sql.",'".$af_fecfinact."'";
		}
		if ($af_feccreact!="")
		{
			$ls_sql=$ls_sql.",'".$af_feccreact."'";
		}	
		if ($af_fecregvalact!="")
		{
			$ls_sql=$ls_sql.",'".$af_fecregvalact."'";
		}		
		if ($as_civinsact!="")
		{
			$ls_sql=$ls_sql.",'".$as_civinsact."'";
		}	
		if ($as_nomresact!="")
		{
			$ls_sql=$ls_sql.",'".$as_nomresact."'";
		}	
		if ($as_civresact!="")
		{
			$ls_sql=$ls_sql.",'".$as_civresact."'";
		}	
		$ls_sql=$ls_sql.",'".$as_persusact."','".$as_coduni."','".$as_motact."','".$as_repempact."','".$as_cedinsact."','".$as_cedresact."','------','".$as_obsact."',1)";print $ls_sql;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
				if($this->io_sql->errno=='23505' || $this->io_sql->errno=='1062' || $this->io_sql->errno=='-239' || $this->io_sql->errno=='-5'|| $this->io_sql->errno=='-1')
				{
					$lb_valido=$this->uf_guardar_acta($as_codcon,&$as_codact,$as_tipact,$af_fecact,$af_feciniact,$af_fecfinact,$af_feccreact,$as_persusact,$as_coduni,
													  $as_motact,$as_repempact,$as_cedinsact,$as_cedresact,$as_cedsupact,$af_fecregvalact,$as_obsact,$as_civinsact,
													  $as_nomresact,$as_civresact,$aa_seguridad);
				}
				else
				{
					$this->io_msg->message("CLASE->Acta MÉTODO->uf_guardar_acta ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message)); 
					$lb_valido=false;
				}
		}
		else
		{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Acta ".$as_codact.", de Tipo ".$as_tipact." del Contrato ".$as_codcon." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				$lb_valido=true;			
		}		
	}
	return $lb_valido;
}

function uf_update_acta($as_codcon,$as_codact,$as_tipact,$af_fecact,$af_feciniact,$af_fecfinact,$af_feccreact,$as_persusact,
						$as_coduni,$as_motact,$as_repempact,$as_cedinsact,$as_cedresact,$as_cedsupact,$af_fecregvalact,
						$as_obsact,$as_civinsact,$as_nomresact,$as_civresact,$aa_seguridad)
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_update_acta
	// Access:			public
	//	Returns:		Boolean, Retorna true si procesa correctamente
	//	Description:	Funcion que se encarga de actualizar el Acta. La funcion no incluye
	//                  la verificacion del estado del acta, aspecto que debe ser revisado
	//					antes de realizar cualquier actualización.
	//  Fecha:          20/04/2006
	//	Autor:          Ing. Laura Cabré				
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$this->la_empresa["codemp"];
	$ls_sql="UPDATE sob_acta 
				 SET codemp='".$ls_codemp."'";	
	if ($af_fecact!="")
	{
		$ls_sql=$ls_sql.",fecact='".$af_fecact."'";
	}
	if ($af_feciniact!="")
	{
		$ls_sql=$ls_sql.",feciniact='".$af_feciniact."'";
	}
	if ($af_fecfinact!="")
	{
		$ls_sql=$ls_sql.",fecfinact='".$af_fecfinact."'";
	}
	if ($af_feccreact!="")
	{
		$ls_sql=$ls_sql.",fecrecact='".$af_feccreact."'";
	}	
	if ($af_fecregvalact!="")
	{
		$ls_sql=$ls_sql.",fecregvalact='".$af_fecregvalact."'";
	}
	
	if ($as_civinsact!="")
	{
		$ls_sql=$ls_sql.",civinsact='".$as_civinsact."'";
	}		
	if ($as_nomresact!="")
	{
		$ls_sql=$ls_sql.",nomresact='".$as_nomresact."'";
	}		
	if ($as_civresact!="")
	{
		$ls_sql=$ls_sql.",civresact='".$as_civresact."'";
	}		
	$ls_sql=$ls_sql.",persusact='".$as_persusact."',coduni='".$as_coduni."',motact='".$as_motact."',repempact='".$as_repempact."',cedinsact='".$as_cedinsact."',cedresact='".$as_cedresact."',cedsupact='".$as_cedsupact."',obsact='".$as_obsact."'
	WHERE codcon='".$as_codcon."' AND tipact='".$as_tipact."' AND codact='".$as_codact."'";
	$this->io_sql->begin_transaction();	
	$li_row=$this->io_sql->execute($ls_sql);
	if($li_row===false)
	{			
		print "Error en metodo uf_update_actainicio".$this->io_function->uf_convertirmsg($this->io_sql->message);
		$this->io_sql->rollback();
	}
	else
	{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Acta ".$as_codact.", de Tipo ".$as_tipact." del Contrato ".$as_codcon." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			$this->io_sql->commit();
			$lb_valido=true;
	}		
	return $lb_valido;
}

function uf_revisar_contrato_acta($as_codcon,$as_tipact)
{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_revisar_contrato_acta
		// Access:			public
		//	Returns:		Boolean, Retorna true si el contrato porsee un acta del tipo indicado
		//	Description:	Funcion que se encarga de verificar si un contrato posee asociada un acta del tipo indicado
		//  Fecha:          20/04/2006
		//	Autor:          Ing. Laura Cabré		
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT codcon 
				FROM sob_acta 
				WHERE codemp='".$ls_codemp."' AND codcon='".$as_codcon."' AND tipact='".$as_tipact."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			print "Error en uf_revisar_contrato_acta".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;				
			}			
		}
		return $lb_valido;			
}

}
?>
