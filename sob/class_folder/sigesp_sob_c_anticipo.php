<?Php
class sigesp_sob_c_anticipo
{
 var $io_function;
 var $la_empresa;
 var $io_sql;
 var $io_msg;
 var $io_funnum;
 var $io_contrato;
 var $io_datastore;

function sigesp_sob_c_anticipo()
{

	require_once("../shared/class_folder/sigesp_include.php");
	$io_siginc=new sigesp_include();
	$io_connect=$io_siginc->uf_conectar();
	require_once("../shared/class_folder/class_sql.php");
	$this->io_sql=new class_sql($io_connect);	
	require_once("../shared/class_folder/class_funciones.php");
	$this->io_function=new class_funciones();
	require_once("../shared/class_folder/class_mensajes.php");
	$this->io_msg=new class_mensajes();	
	$this->la_empresa=$_SESSION["la_empresa"];
	require_once ("sigesp_sob_c_funciones_sob.php");
	$this->io_funnum= new sigesp_sob_c_funciones_sob(); 
	require_once("sigesp_sob_c_contrato.php");
	$this->io_contrato=new sigesp_sob_c_contrato();
	require_once("../shared/class_folder/class_datastore.php");
	$this->io_datastore=new class_datastore();
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$this->seguridad=   new sigesp_c_seguridad();
	require_once("class_folder/sigesp_sob_c_funciones_sob.php");
	$this->io_funsob=   new sigesp_sob_c_funciones_sob();	
	$this->ls_codemp=$this->la_empresa["codemp"];
	require_once("../shared/class_folder/sigesp_c_generar_consecutivo.php");
	$this->io_keygen= new sigesp_c_generar_consecutivo();
}
//*******************************************************************************************************
//							Funciones de Calculos y consultas especiales
//*******************************************************************************************************

function uf_select_cuentacontable($as_codcon,&$ls_cuenta)
{

	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_select_cuentacontable
	//  Access:			public
	//	Returns:		Boolean, Retorna true si se ejecuta correctamente.Cuenta: cuenta
	//					contable asociada al contratista de la obra
	//	Description:	Retornar la cuenta contable asociada con el contratista de la obra
	//  Fecha:          31/03/2006
	//	Autor:          Ing. Laura Cabré		
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;		
	$ls_codemp=$this->la_empresa["codemp"];
	$ls_sql="SELECT p.sc_cuenta as cuenta 
			FROM rpc_proveedor p,sob_contrato c, sob_asignacion a
			WHERE p.codemp='".$ls_codemp."' AND c.codemp='".$ls_codemp."' AND a.codemp='".$ls_codemp."'
			AND c.codcon='".$as_codcon."' AND c.codasi=a.codasi AND  a.cod_pro=p.cod_pro";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
		$this->is_msg_error="Error en select cuentacontable".$this->io_function->uf_convertirmsg($this->io_sql->message);
		print $this->is_msg_error;
		$lb_valido=false;
	}
	else
	{
		if($la_row=$this->io_sql->fetch_row($rs_data))
		{
			$la_data=$this->io_sql->obtener_datos($rs_data);
			$ls_cuenta=$la_data["cuenta"][1];
		}			
	}	
	return $lb_valido;
	

}
function uf_calcular_montototal($ad_montoanticipo,$aa_montodeduccion)
{
	$ld_montototal=$ad_montoanticipo;
	$this->io_datastore->data=$aa_montodeduccion;
	$li_filas=$this->io_datastore->getRowCount("deduccion");
	for($li_i=1;$li_i<=$li_filas;$li_i++)
	{
		$ld_montototal=$ld_montototal-$aa_montodeduccion["deduccion"][$li_i];
	}
	return $ld_montototal;	
}

//*******************************************************************************************************
//							Funciones relacionadas con el Contrato                                    
//*******************************************************************************************************

	function uf_calcular_montoanticipo($as_codcon,&$ad_montoanticipo)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	    uf_calcular_montolimite
		//  Access:			public
		//	Returns:		Boolean, Retorna true si se ejecuta correctamente. Montoanticipo, 
		//					total resultante de la sumade los monstos de todos los anticipos de 
		//					un contrato; 
		//	Description:	Funcion que se encarga calcular la sumatoria de los montos 
		//					de los anticipos asociados a un contrato.
		//  Fecha:          31/03/2006
		//	Autor:          Ing. Laura Cabré		
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;		
		$ld_montoanticipo=0;
		$ad_momontoanticipo=0;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT montotant 
				 FROM sob_anticipo 
				 WHERE codemp='".$ls_codemp."' AND codcon='".$as_codcon."' AND estant<>3";
				 //print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select montoanticipocontratos".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
			$lb_valido=false;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$aa_data=$this->io_sql->obtener_datos($rs_data);
				$li_filas=$this->io_sql->num_rows($rs_data);
				for($li_i=1;$li_i<=$li_filas;$li_i++)
				{
					$ld_montoanticipo=$ld_montoanticipo+$aa_data["montotant"][$li_i];
					
				}
				$ad_montoanticipo=$ld_montoanticipo;				
			}
			else
				$ad_montoanticipo=0;			
		}	
		return $lb_valido;
	}
	
//*******************************************************************************************************
//							Funciones relacionadas con el anticipo                                     
//*******************************************************************************************************

function uf_select_anticipo ($as_codant,$as_codcon,&$aa_data)
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_select_anticipo
	//  Access:			public
	//	Returns:		Boolean, Retorna true si existe el registro en bd
	//	Description:	Funcion que se encarga de verificar si el anticipo, de ser asi retorna
	//					su data
	//  Fecha:          01/04/2006
	//	Autor:          Ing. Laura Cabré		
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$this->la_empresa["codemp"];
	$ls_sql="SELECT * 
			FROM sob_anticipo
			WHERE codemp='".$ls_codemp."' and codcon='".$as_codcon."' AND codant='".$as_codant."'";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
		print "Error en select".$this->io_function->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_data))
		{
			$lb_valido=true;
			$aa_data=$this->io_sql->obtener_datos($rs_data);
		}
		else
		{
			$aa_data="";
		}
	}
	return $lb_valido;
}

function uf_guardar_anticipo ($as_codcon ,&$as_codant ,$af_fecant,$af_fecintant,$ad_porant,$ad_monto,$as_conant,$ad_montotant,$as_sc_cuenta,$aa_seguridad)      
{ 
   
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_guardar_anticipo
	//  Access:			public
	//	Returns:		Boolean, Retorna true si procesa correctamente
	//	Description:	Funcion que se encarga de guardar la cabecera del Anticipo.
	//  Fecha:          03/04/2006
	//	Autor:          Ing. Laura Cabré				
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$this->la_empresa["codemp"];
	$this->io_keygen->uf_verificar_numero_generado("SOB","sob_anticipo","codant","SOBANT",3,"","","",&$as_codant);
	$lb_valido=$this->uf_validar_disponibilidad_anticipos($as_codcon,$as_codant,$ad_monto);
	if($lb_valido)
	{
		$ls_sql="INSERT INTO sob_anticipo (codemp,codcon ,codant ,fecant,fecintant,porant,monto,conant,montotant,sc_cuenta,".
				"                          estant,estapr,estspgscg)".
				" VALUES ('".$ls_codemp."','".$as_codcon."','".$as_codant."','".$af_fecant."','".$af_fecintant."',".
				"         '".$ad_porant."','".$ad_monto."','".$as_conant."','".$ad_montotant."','".$as_sc_cuenta."',1,0,0)";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			if($this->io_sql->errno=='23505' || $this->io_sql->errno=='1062' || $this->io_sql->errno=='-239' || $this->io_sql->errno=='-5'|| $this->io_sql->errno=='-1')
			{
				$lb_valido=$this->uf_guardar_anticipo($as_codcon ,&$as_codant ,$af_fecant,$af_fecintant,$ad_porant,$ad_monto,$as_conant,$ad_montotant,
													  $as_sc_cuenta,$aa_seguridad);
			}
			else
			{
				print "Error en metodo uf_guardar_anticipo".$this->io_function->uf_convertirmsg($this->io_sql->message);
			}
		}
		else
		{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Anticipo ".$as_codant." del Contrato ".$as_codcon." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$lb_valido=true;			
		}		
	}
	return $lb_valido;
}

function uf_update_anticipo($as_codcon, $as_codant,$af_fecant,$af_fecintant,$ad_porant,$ad_monto,$as_conant,$ad_montotant,$as_sc_cuenta,$aa_seguridad)
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_update_anticipo
	// Access:			public
	//	Returns:		Boolean, Retorna true si procesa correctamente
	//	Description:	Funcion que se encarga de actualizar el anticipo. La funcion no incluye
	//                  la verificacion del estado del anticipo, aspecto que debe ser revisado
	//					antes de realizar cualquier actualización.
	//  Fecha:          04/04/2006
	//	Autor:          Ing. Laura Cabré				
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$this->la_empresa["codemp"];
	$ls_sql="UPDATE sob_anticipo
				 SET codcon='".$as_codcon."',fecant='".$af_fecant."',
				 fecintant='".$af_fecintant."',porant='".$ad_porant."',monto='".$ad_monto."',
				 conant='".$as_conant."',montotant='".$ad_montotant."',sc_cuenta='".$as_sc_cuenta."'
				 WHERE codemp='".$ls_codemp."' AND codcon='".$as_codcon."' AND codant='".$as_codant."'";		
	$li_row=$this->io_sql->execute($ls_sql);
	if($li_row===false)
	{			
		print "Error en metodo uf_update_anticipo".$this->io_function->uf_convertirmsg($this->io_sql->message);
		
	}
	else
	{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Anticipo ".$as_codant." del Contrato ".$as_codcon." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			$lb_valido=true;
	}		
	return $lb_valido;
}

function uf_delete_anticipo ($as_codcon,$as_codant,$aa_seguridad)
{
	//////////////////////////////////////////////////////////////////////////////////////////
	//  Function:       uf_delete_contrato
	//  Access:			public
	//	Returns:		Boolean, Retorna true si procesa correctamente
	//	Description:	Funcion que se encarga de eliminar la cabecera de un anticipo. La funcion no incluye
	//                  la verificacion del estado del anticipo, aspecto que debe ser revisado
	//					antes de realizar cualquier eliminación.
	//  Fecha:          04/04/2006
	//	Autor:          Ing. Laura Cabré				
	//////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$this->la_empresa["codemp"];
	$ls_sql="DELETE FROM sob_anticipo
				WHERE codemp='".$ls_codemp."' AND codcon='".$as_codcon."' AND codant='".$as_codant."'";		
	$this->io_sql->begin_transaction();
	$li_row=$this->io_sql->execute($ls_sql);
	if($li_row===false)
	{
		$this->io_sql->rollback();
		print"Error en metodo uf_delete_Anticipo".$this->io_function->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		$lb_valido=true;
		/////////////////////////////////         SEGURIDAD               /////////////////////////////
		$ls_evento="DELETE";
		$ls_descripcion ="Eliminó el Anticipo ".$as_codant." del Contrato ".$as_codcon." Asociado a la Empresa ".$ls_codemp;
		$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////
		$this->io_sql->commit();
	}
	return $lb_valido;		
}
	

//*******************************************************************************************************
//							Funciones relacionadas con las Retenciones                                   
//*******************************************************************************************************
	
/*function uf_select_retenciones ($as_codcon,$as_codant,&$aa_data,&$ai_rows)
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_select_retenciones
	// Access:			public
	//	Returns:		Boolean,arreglo. Retorna true y el arreglo de retenciones si existen registros en bd
	//	Description:	Funcion que se encarga de verificar si existen registros para un
	//                  anticipo y retorna el arreglo con los mismos
	//  Fecha:          04/04/2006
	//	Autor:          Ing. Laura Cabré		
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$this->la_empresa["codemp"];
	$ls_sql="SELECT r.codcon,r.codant, r.codded,d.dended as dended,d.sc_cuenta as cuenta, d.monded as deducible, r.codemp,d.formula
			FROM sob_retencionanticipo r, sigesp_deducciones d
			WHERE r.codemp='".$ls_codemp."' AND d.codemp='".$ls_codemp."' AND r.codded=d.codded AND r.codcon='".$as_codcon."' AND r.codant='".$as_codant."'";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
		$this->is_msg_error="Error en select retencionesanticipo".$this->io_function->uf_convertirmsg($this->io_sql->message);
		print $this->is_msg_error;
	}
	else
	{
		if($la_row=$this->io_sql->fetch_row($rs_data))
		{
			$lb_valido=true;
			$ai_rows=$this->io_sql->num_rows($rs_data);
			$aa_data=$this->io_sql->obtener_datos($rs_data);
		}else
		{
			$ai_rows=0;
			$aa_data="";
		}			
	}		
	return $lb_valido;
}*/

function uf_validar_disponibilidad_anticipos($as_codcon,$as_codant,$ai_monto)
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_validar_disponibilidad_anticipos
	// Access:			public
	//	Returns:		Boolean,arreglo. Retorna true y el arreglo de retenciones si existen registros en bd
	//	Description:	Funcion que se encarga de verificar si existen registros para un
	//                  anticipo y retorna el arreglo con los mismos
	//  Fecha:          04/04/2006
	//	Autor:          Ing. Laura Cabré		
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$this->la_empresa["codemp"];
/*	$ls_sql="SELECT SUM(sob_anticipo.monto) AS totalant, MAX(sob_contrato.monto) AS montocont".
			"  FROM sob_anticipo,sob_contrato".
			" WHERE sob_anticipo.codemp='".$ls_codemp."'".
			"   AND sob_anticipo.codcon='".$as_codcon."'".
			"   AND sob_anticipo.codant<>'".$as_codant."'".
			"   AND sob_anticipo.codemp=sob_contrato.codemp".
			"   AND sob_anticipo.codcon=sob_contrato.codcon";
*/	$ls_sql="SELECT sob_contrato.monto AS montocont,".
			"       (SELECT  SUM(sob_anticipo.monto) FROM sob_anticipo".
			"         WHERE sob_anticipo.codant<>'".$as_codant."'".
			"           AND sob_anticipo.codemp=sob_contrato.codemp".
			"           AND sob_anticipo.codcon=sob_contrato.codcon) AS totalant".
			"  FROM sob_contrato".
			" WHERE sob_contrato.codemp='".$ls_codemp."'".
			"   AND sob_contrato.codcon='".$as_codcon."'";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
		$this->is_msg_error="Error en select retencionesanticipo".$this->io_function->uf_convertirmsg($this->io_sql->message);
		print $this->io_sql->message;
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_data))
		{
			$li_totalant= $row["totalant"];
			$li_anticipo=$li_totalant+$ai_monto;
			$li_montocont= $row["montocont"];
			if($li_anticipo<$li_montocont) 
			{
				$lb_valido=true;
			}
			else
			{
				$this->io_msg->message("La suma de los anticipos otorgados es superior al contrato");
			}
		}
		else
		{
			$lb_valido=true;
		}			
	}
	return $lb_valido;
}


function uf_select_retencion ($as_codcon,$as_codret,$as_codant)
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_select_retencion
	//  Access:			public
	//	Returns:		Boolean,arreglo. Retorna true si la retencion buscada existe en bd
	//	Description:	Funcion que se encarga de verificar si existe una retencion específica asociada a un
	//                  anticipo
	//  Fecha:          04/04/2006
	//	Autor:          Ing. Laura Cabré		
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$this->la_empresa["codemp"];
	$ls_sql="SELECT  codded
			 FROM sob_retencionanticipo
			 WHERE codemp='".$ls_codemp."' AND codcon='".$as_codcon."' AND codded='".$as_codret."' AND codant='".$as_codant."'";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
		$this->is_msg_error="Error en select_retencionanticipo".$this->io_function->uf_convertirmsg($this->io_sql->message);
		print $this->is_msg_error;
	}
	else
	{
		if($la_row=$this->io_sql->fetch_row($rs_data))
		{
			$lb_valido=true;
		}		
	}		
	return $lb_valido;
}

/*function uf_guardar_retenciones($as_codcon ,$as_codant,$as_codded,$aa_seguridad)             
{ 
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_guardar_retenciones
	//  Access:			public
	//	Returns:		Boolean, Retorna true si procesa correctamente
	//	Description:	Funcion que se encarga de guardar una retencion.
	//  Fecha:          03/04/2006
	//	Autor:          Ing. Laura Cabré				
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$this->la_empresa["codemp"];
	$ls_sql="INSERT INTO sob_retencionanticipo ( codemp, codcon,codant,codded )
	         VALUES ('".$ls_codemp."','".$as_codcon."','".$as_codant."','".$as_codded."')";
	$this->io_sql->begin_transaction();	
	$li_row=$this->io_sql->execute($ls_sql);
	if($li_row===false)
	{			
		print "Error en metodo uf_guardar_retencionesanticipo".$this->io_function->uf_convertirmsg($this->io_sql->message);
		$this->io_sql->rollback();	
	}
	else
	{
		if($li_row>0)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó detalle de la Retencion ".$as_codded.", del Anticipo ".$as_codant." del Contrato ".$as_codcon." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
			$lb_valido=true;
		}
		else
		{
			
			$this->io_sql->rollback();
			$lb_valido=1;
		}
	
	}		
	return $lb_valido;
}*/

function uf_guardar_retenciones($as_codcon ,$as_codant,$as_codded,$ai_monret,$ai_montotret,$aa_seguridad)             
{ 
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_guardar_retenciones
	//  Access:			public
	//	Returns:		Boolean, Retorna true si procesa correctamente
	//	Description:	Funcion que se encarga de guardar una retencion.
	//  Fecha:          03/04/2006
	//	Autor:          Ing. Laura Cabré				
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$this->la_empresa["codemp"];
	$ld_monret=$this->io_funsob->uf_convertir_cadenanumero($ai_monret);
	$ld_montotret=$this->io_funsob->uf_convertir_cadenanumero($ai_montotret);
	$ls_sql="INSERT INTO sob_retencionanticipo ( codemp, codcon,codant,codded,monret,montotret )
	         VALUES ('".$ls_codemp."','".$as_codcon."','".$as_codant."','".$as_codded."','".$ld_monret."','".$ld_montotret."')";
	$li_row=$this->io_sql->execute($ls_sql);
	if($li_row===false)
	{			
		print "Error en metodo uf_guardar_retencionesanticipo".$this->io_function->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó detalle de la Retencion ".$as_codded.", del Anticipo ".$as_codant." del Contrato ".$as_codcon." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$lb_valido=true;
	}		
	return $lb_valido;
}

/*function uf_update_retenciones($as_codcon,$as_codant,$aa_retencionesnuevas,$ai_totalfilas,$aa_seguridad)
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_update_retenciones
	// Access:			public
	//	Returns:		Boolean, Retorna true si procesa correctamente
	//	Description:	Funcion que se encarga de actualizar las retenciones del 
	//					anticipo que han sido modificadas.
	//  Fecha:          04/04/2006
	//	Autor:          Ing. Laura Cabré				
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=0;
	$ls_codemp=$this->la_empresa["codemp"];
	$this->uf_select_retenciones ($as_codcon,$as_codant,$la_retencionesviejas,$li_totalviejas);
	$li_totalnuevas=$ai_totalfilas;
	for ($li_i=1;$li_i<$li_totalnuevas;$li_i++)
	{
		$lb_existe=false;
		for ($li_j=1;$li_j<=$li_totalviejas;$li_j++)
		{
			if( ($la_retencionesviejas["codemp"][$li_j] == $ls_codemp) && ($la_retencionesviejas["codcon"][$li_j] == $as_codcon) &&  ($la_retencionesviejas["codded"][$li_j] == $aa_retencionesnuevas["codded"][$li_i]) && ($la_retencionesviejas["codant"][$li_j] == $as_codant))
			{
				
				$lb_existe = true;
			}				
			
		}
		if (!$lb_existe)
		{
			$lb_valido=$this->uf_guardar_retenciones($as_codcon,$as_codant,$aa_retencionesnuevas["codded"][$li_i],$aa_seguridad);			
		}
	}
	
	for ($li_j=1;$li_j<=$li_totalviejas;$li_j++)
	{
		$lb_existe=false;
		for ($li_i=1;$li_i<$li_totalnuevas;$li_i++)
		{
			if( ($la_retencionesviejas["codemp"][$li_j] == $ls_codemp) && ($la_retencionesviejas["codcon"][$li_j] == $as_codcon) &&  ($la_retencionesviejas["codded"][$li_j] == $aa_retencionesnuevas["codded"][$li_i]) && ($la_retencionesviejas["codant"][$li_j] == $as_codant) )
			{
				
				$lb_existe = true;
			}				
			
		}
		if (!$lb_existe)
		{
			$lb_valido=$this->uf_delete_retenciones($as_codcon,$as_codant,$la_retencionesviejas["codded"][$li_j],$aa_seguridad);			
		}
	}			
	return $lb_valido;
}	

function uf_delete_retenciones($as_codcon,$as_codant,$as_codret,$aa_seguridad)
{
	//////////////////////////////////////////////////////////////////////////////////////////
	//  Function:       uf_delete_retenciones
	//  Access:			public
	//	Returns:		Boolean, Retorna true si procesa correctamente
	//	Description:	Funcion que se encarga de eliminar una retencion asociada a un anticipo.
	//  Fecha:          04/04/2006
	//	Autor:          Ing. Laura Cabré				
	//////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$this->la_empresa["codemp"];
	$ls_sql="DELETE FROM sob_retencionanticipo
				WHERE codemp='".$ls_codemp."' AND codcon='".$as_codcon."' AND codded='".$as_codret."' AND codant='".$as_codant."'";		
	$this->io_sql->begin_transaction();
	$li_row=$this->io_sql->execute($ls_sql);
	if($li_row===false)
	{
		$this->io_sql->rollback();
		print"Error en metodo eliminar_retencion anticipo".$this->io_function->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		$lb_valido=true;
		/////////////////////////////////         SEGURIDAD               /////////////////////////////
		$ls_evento="DELETE";
		$ls_descripcion ="Eliminó el detalle de la Retención ".$as_codret.", del Anticipo ".$as_codant." del Contrato ".$as_codcon." Asociado a la Empresa ".$ls_codemp;
		$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////
		$this->io_sql->commit();
	}
	return $lb_valido;	

}*/
	function uf_update_retencion($as_codant,$as_codcon,$as_codded,$ai_monret,$ai_montotret,$aa_seguridad)
	 {
	
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ld_monret=$this->io_funsob->uf_convertir_cadenanumero($ai_monret);
		$ld_montotret=$this->io_funsob->uf_convertir_cadenanumero($ai_montotret);
		$ls_sql="UPDATE sob_retencionanticipo
				SET monret=".$ld_monret.", montotret=".$ld_montotret."
				WHERE codemp='".$ls_codemp."' AND codant='".$as_codant."' AND codcon='".$as_codcon."' AND codded='".$as_codded."'";		
		$this->io_sql->begin_transaction();	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			print "Error en metodo uf_update_retencion ".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();	
		}
		else
		{
				/*************    SEGURIDAD    **************/		 
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó la retencion ".$as_codded.", Detalle de el Anticipo ".$as_codant." Asociado a la Empresa ".$ls_codemp;
				   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion); 
				/**********************************************/
				$this->io_sql->commit();
				$lb_valido=true;
		}		
		return $lb_valido;
	  }
	function uf_update_retenciones($as_codcon,$as_codant,$aa_retencionesnuevas,$ai_totalfilas,$aa_seguridad)
	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          GERARDO CORDERO		                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$lb_update=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$lb_valido=$this->uf_select_retenciones($as_codcon,$as_codant,$la_retencionesviejas,$li_totalviejas);
		$li_totalnuevas=$ai_totalfilas;
		for ($li_i=1;$li_i<$li_totalnuevas;$li_i++)
		{
			$lb_existe=false;
			for ($li_j=1;$li_j<=$li_totalviejas;$li_j++)
			{
				if( ($la_retencionesviejas["codemp"][$li_j] == $ls_codemp) && ($la_retencionesviejas["codcon"][$li_j] == $as_codcon) && ($la_retencionesviejas["codant"][$li_j] == $as_codant) && ($la_retencionesviejas["codded"][$li_j] == $aa_retencionesnuevas["codret"][$li_i]) )
				{
				  if($la_retencionesviejas["monret"][$li_j] != $aa_retencionesnuevas["monret"][$li_i])
					{
					  $lb_update=true;
					}
					$lb_existe = true;
				}				
				
			}
			if (!$lb_existe)
			{
				$lb_valido=$this->uf_guardar_retenciones($as_codcon,$as_codant,$aa_retencionesnuevas["codret"][$li_i],$aa_retencionesnuevas["monret"][$li_i],$aa_retencionesnuevas["montotret"][$li_i],$aa_seguridad);
			}
			if($lb_valido)
			{
				if ($lb_update)
				{
					$this->uf_update_retencion($as_codant,$as_codcon,$aa_retencionesnuevas["codret"][$li_i],$aa_retencionesnuevas["monret"][$li_i],$aa_retencionesnuevas["montotret"][$li_i],$aa_seguridad);
				}
			}
		}
		if($lb_valido)
		{
			for ($li_j=1;$li_j<=$li_totalviejas;$li_j++)
			{
				$lb_existe=false;
				for ($li_i=1;$li_i<$li_totalnuevas;$li_i++)
				{
					if( ($la_retencionesviejas["codemp"][$li_j] == $ls_codemp) && ($la_retencionesviejas["codant"][$li_j] == $as_codant) && ($la_retencionesviejas["codcon"][$li_j] == $as_codcon) && ($la_retencionesviejas["codded"][$li_j] == $aa_retencionesnuevas["codret"][$li_i]) )
					{
						$lb_existe= true;
					}				
				}
				if (!$lb_existe)
				{
					$lb_valido=$this->uf_delete_retenciones($as_codcon,$as_codant,$la_retencionesviejas["codded"][$li_j],$aa_seguridad);
					if(!$lb_valido)
					{break;}
				}
			}			
		}
		return $lb_valido;
	}

function uf_delete_retenciones($as_codcon,$as_codant,$as_codret,$aa_seguridad)
{
	//////////////////////////////////////////////////////////////////////////////////////////
	//  Function:       uf_delete_retenciones
	//  Access:			public
	//	Returns:		Boolean, Retorna true si procesa correctamente
	//	Description:	Funcion que se encarga de eliminar una retencion asociada a un anticipo.
	//  Fecha:          04/04/2006
	//	Autor:          Ing. Laura Cabré				
	//////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$this->la_empresa["codemp"];
	$ls_sql="DELETE FROM sob_retencionanticipo
				WHERE codemp='".$ls_codemp."' AND codcon='".$as_codcon."' AND codded='".$as_codret."' AND codant='".$as_codant."'";		
	$li_row=$this->io_sql->execute($ls_sql);
	if($li_row===false)
	{
		print"Error en metodo eliminar_retencion anticipo".$this->io_function->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		$lb_valido=true;
		/////////////////////////////////         SEGURIDAD               /////////////////////////////
		$ls_evento="DELETE";
		$ls_descripcion ="Eliminó el detalle de la Retención ".$as_codret.", del Anticipo ".$as_codant." del Contrato ".$as_codcon." Asociado a la Empresa ".$ls_codemp;
		$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////
	}
	return $lb_valido;	
}



//*******************************************************************************************************
//							Funciones relacionadas con el estado del Anticipo                 
//*******************************************************************************************************


function uf_update_estado($as_codcon,$as_codant,$ai_estado,$aa_seguridad)
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_update_estado
	// Access:			public
	//	Returns:		Boolean, Retorna true si procesa correctamente
	//	Description:	Funcion que se encarga de actualizar el estado del anticipo
	//  Fecha:          04/04/2006
	//	Autor:          Ing. Laura Cabré				
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$this->la_empresa["codemp"];
	$ls_sql="UPDATE sob_anticipo
				 SET estant='".$ai_estado."'
				 WHERE codemp='".$ls_codemp."' AND codcon='".$as_codcon."' AND codant='".$as_codant."'";		
	$li_row=$this->io_sql->execute($ls_sql);
	if($li_row===false)
	{			
		print "Error en metodo uf_update_estadoanticipo".$this->io_function->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
			$ls_estado=$this->io_funsob->uf_convertir_numeroestado($ai_estado);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Estado del Anticipo ".$as_codant." del Contrato ".$as_codcon." a ".$ls_estado." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			$lb_valido=true;
	}		
	return $lb_valido;
}	

function uf_select_estado ($as_codcon,$as_codant,&$estado)
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_select_estado
	// Access:			public
	//	Returns:		Boolean, Retorna true si existe el registro en bd
	//	Description:	Funcion que se encarga de verificar el estado del anticipo
	//  Fecha:          04/04/2006
	//	Autor:          Ing. Laura Cabré		
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$this->la_empresa["codemp"];
	$ls_sql="SELECT estant
			 FROM sob_anticipo
			 WHERE codemp='".$ls_codemp."' AND codcon='".$as_codcon."' AND codant='".$as_codant."'";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
		print "Error en select estado anticipo".$this->io_function->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		if($la_row=$this->io_sql->fetch_row($rs_data))
		{
			$estado=$la_row["estant"];
			$lb_valido=true;
		}		
	}
	return $lb_valido;
}

//*******************************************************************************************************
//							Funciones Adicionales           
//*******************************************************************************************************
	

function uf_generar_codigoanticipo($as_codcon)
{
	  //////////////////////////////////////////////////////////////////////////////
	 //	Metodo: uf_generarcodigoanticipo
	 //	Access:  public
	 //	Returns: proximo codigo del anticipo buscado
	 //	Description: Funcion que permite generar el proximo codigo de un anticipo, 
	 //              dependiendo del codigo del contrato
	 // Fecha: 06/04/2006
	 // Autor: Ing. Laura Cabré
	 //////////////////////////////////////////////////////////////////////////////
	$ls_codemp=$this->la_empresa["codemp"];
	$ls_sql="SELECT codant 
			FROM sob_anticipo 
			WHERE codemp='".$ls_codemp."' AND codcon='".$as_codcon."' ORDER BY codant DESC";		
	$rs_data=$this->io_sql->select($ls_sql);
	if ($row=$this->io_sql->fetch_row($rs_data))
	{ 
	  $codigo=$row["codant"];
	  settype($codigo,'int');                             // Asigna el tipo a la variable.
	  $codigo = $codigo + 1;                              // Le sumo uno al entero.
	  settype($codigo,'string');                          // Lo convierto a varchar nuevamente.
	  $ls_codigo=$this->io_function->uf_cerosizquierda($codigo,3);	 
	}
	else
	{
	  $codigo="1";
	  $ls_codigo=$this->io_function->uf_cerosizquierda($codigo,3);
	
	}

  return $ls_codigo;	
}
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_recepcion_documentos($as_codemp,$as_numrecdoc,$as_codtipdoc,$as_conant,$ad_fecant,$ai_montotant,$ai_totreten,$as_codcon,$as_codant,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_recepcion_documentos
		//		   Access: private
		//	    Arguments: $as_codsolvia    // codigo de solicitud de viaticos
		//                 $as_numrecdoc    // Numero de Recepcion de documentos
		//				   $as_codtipdoc 	// Codigo de tipo de documento
		//				   $as_codtipdoc	// codigo de tipo de documento
		//				   $as_conant	    // descripcion del documento
		//				   $ad_fecant  		// Fecha del documento
		//				   $ai_montotant  	// Monto total del documento
		//				   $ai_totretten    // Monto total de retenciones
		//				   $as_codcon       // Codigo del contrato
		//				   $aa_seguridad    // Arreglo de las variables de seguridad
		//	      Returns: $lb_valido True si se genero la recepción de documento correctamente
		//	  Description: Retorna un Booleano
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 29/04/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
        $ls_tipodestino= "P";			
		$ls_cedbene= "----------";	
		$ls_codpro=$this->uf_select_contratista($as_codcon); 
		$lb_existe=$this->uf_select_recepcion($as_numrecdoc,$as_codtipdoc,$ls_cedbene,$ls_codpro);
		if(!$lb_existe)
		{
			$ad_fecant= $this->io_function->uf_convertirdatetobd($ad_fecant);
			$this->io_sql->begin_transaction();	
			$ls_sql="INSERT INTO cxp_rd (codemp,numrecdoc,codtipdoc,ced_bene,cod_pro,dencondoc,fecemidoc, fecregdoc, fecvendoc,".
					"                    montotdoc, mondeddoc,moncardoc,tipproben,numref,estprodoc,procede,estlibcom,estaprord,".
					"                    fecaprord,usuaprord,estimpmun,codcla)".
					"     VALUES ('".$this->ls_codemp."','".$as_numrecdoc."','".$as_codtipdoc."','".$ls_cedbene."',".
					"             '".$ls_codpro."','".$as_conant."','".$ad_fecant."','".$ad_fecant."','".$ad_fecant."',
					"               .$ai_montotant.",".$ai_totreten.",0,'".$ls_tipodestino."','".$as_numrecdoc."','R','SOBCON',0,0,'1900-01-01','',0,'--')";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{ 
				$this->io_msg->message("CLASE->Anticipo MÉTODO->uf_procesar_recepcion_documentos ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
				$lb_valido=false;
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_insert_recepcion_documento_contable($as_numrecdoc,$as_codtipdoc,$ls_cedbene,$ls_codpro,$ai_montotant,$as_codcon,$as_codant,$ai_totreten);
				if($lb_valido)
				{
					$lb_valido=$this->uf_update_estatus_generacion_rd($as_codcon, $as_codant,$aa_seguridad);
				}
				if($lb_valido)
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="PROCESS";
					$ls_descripcion="Generó la Recepción de Documento de la llave contrato-anticipo <b>".$as_numrecdoc."</b>";
					$lb_valido= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													  $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													  $aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
			}
			if($lb_valido)
			{
				$this->io_sql->commit();	
			}
			else
			{
				$this->io_sql->rollback();	
			}
		}
		else
		{
			$this->io_msg->message("La Recepcion de Documentos ya Existe.");
			$lb_valido=false;
		}
		return $lb_valido;
	}  // end function uf_procesar_recepcion_documentos
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_recepcion_documento_contable($as_comprobante,$as_codtipdoc,$as_cedbene,$as_codpro,$ai_montotant,$as_codcon,$as_codant,$ai_totreten)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_recepcion_documento_contable
		//		   Access: private
		//	    Arguments: $as_comprobante // Código de Comprobante
		//				   $as_codtipdoc   // Tipo de Documento
		//				   $as_cedbene     // Cédula del Beneficiario
		//				   $as_codpro      // Código del Proveedor
		//				   $ai_montotant   // monto del anticipo
		//				   $ai_totreten    // monto de la retencion
		//	      Returns: $lb_valido True 
		//	  Description: Retorna un Booleano
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 30/04/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->io_ds=new class_datastore();
	    $lb_valido=false;		
		$ls_procede="SOBCON";
		$lb_valido=$this->uf_select_cuentas_contratista($as_codpro,&$as_sccuenta,&$as_ctaant);
		if($lb_valido)
		{
			$li_monto=($ai_montotant+$ai_totreten);
			$li_montoprov=($ai_montotant-$ai_totreten);
			$lb_valido=$this->uf_insert_detalle_contable($as_comprobante,$as_codtipdoc,$as_cedbene,$as_codpro,$ls_procede,'D',$as_ctaant,$li_monto);
			if($lb_valido)
			{
				$ls_sql="SELECT sigesp_deducciones.sc_cuenta,sob_retencionanticipo.montotret,sob_retencionanticipo.codded".
						"  FROM sob_retencionanticipo,sigesp_deducciones ".
						" WHERE sob_retencionanticipo.codemp='".$this->ls_codemp."' ".
						"   AND sob_retencionanticipo.codant='".$as_codant."'".
						"   AND sob_retencionanticipo.codcon='".$as_codcon."'".
						"   AND sob_retencionanticipo.codemp=sigesp_deducciones.codemp ".
						"   AND sob_retencionanticipo.codded=sigesp_deducciones.codded ";
				$rs_data=$this->io_sql->select($ls_sql);
				if($rs_data===false)
				{   
					$this->io_msg->message("CLASE->Anticipo MÉTODO->uf_insert_recepcion_documento_contable ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
					return false;
				}
				else
				{           
					while($row=$this->io_sql->fetch_row($rs_data) and ($lb_valido))
					{
						$ls_codded=  $row["codded"];
						$ls_sccuentaret=  $row["sc_cuenta"];
						$li_montotret=  $row["montotret"];
						$ls_debhab= "H";				
				//		$ls_documento= $this->io_sigesp_int->uf_fill_comprobante($ls_documento);
						$lb_valido=$this->uf_insert_detalle_contable($as_comprobante,$as_codtipdoc,$as_cedbene,$as_codpro,$ls_procede,'H',$ls_sccuentaret,$li_montotret);
					} // end while
					if($lb_valido)
					{
						$lb_valido=$this->uf_insert_detalle_contable($as_comprobante,$as_codtipdoc,$as_cedbene,$as_codpro,$ls_procede,'H',$as_sccuenta,$li_montoprov);
					}
				}
				$this->io_sql->free_result($rs_data);	 
			}
		}
		return $lb_valido;
    } // end function uf_insert_recepcion_documento_contable
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_contratista($as_codcon)
	{
	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_contratista
		//		   Access: private
		//	    Arguments: $as_codcon    // codigo de contrato
		//	      Returns: $ls_codpro Codigo de Proveedor
		//	  Description: Obtiene el codigo del proveedor relacionado con el contrato
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 29/04/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;		
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT sob_asignacion.cod_pro". 
				"  FROM sob_contrato , sob_asignacion". 
				" WHERE sob_contrato.codemp='".$ls_codemp."'".
				"   AND sob_contrato.codcon='".$as_codcon."'".
				"   AND sob_contrato.codemp=sob_asignacion.codemp".
				"   AND sob_contrato.codasi=sob_asignacion.codasi";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select cuentacontable".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				//$la_data=$this->io_sql->obtener_datos($rs_data);
				$ls_codpro=$row["cod_pro"];
			}			
		}	
		return $ls_codpro;
	}
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_cuentas_contratista($as_codpro,&$as_sccuenta,&$as_ctaant)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_contratista
		//		   Access: private
		//	    Arguments: $as_codcon    // codigo de contrato
		//                 $as_sccuenta  // Cuenta de contratista
		//                 $as_ctaant    // Cuenta de anticipo de contratista
		//	      Returns: $lb_valido Devuelve un booleano
		//	  Description: Obtiene las cuentas contables para el asiento del anticipo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 30/04/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT sc_cuenta,sc_ctaant". 
				"  FROM rpc_proveedor". 
				" WHERE codemp='".$ls_codemp."'".
				"   AND cod_pro='".$as_codpro."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
				$this->io_msg->message("CLASE->Anticipo MÉTODO->uf_select_cuentas_contratista ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				//$la_data=$this->io_sql->obtener_datos($rs_data);
				$as_sccuenta=$row["sc_cuenta"];
				$as_ctaant=$row["sc_ctaant"];
				if(($as_sccuenta!="")&&($as_ctaant!=""))
				{
					$lb_valido=true;
				}
				else
				{
					$this->io_msg->message("Falta por configurar alguna cuenta contable del proveedor");
				}
			}			
		}	
		return $lb_valido;
	}
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_detalle_contable($as_comprobante,$as_codtipdoc,$as_cedbene,$as_codpro,$as_procede,$as_debhab,$as_sccuenta,$ai_monto)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_detalle_contable
		//		   Access: private
		//	    Arguments: $as_comprobante // Código de Comprobante
		//				   $as_codtipdoc   // Tipo de Documento
		//				   $as_cedbene     // Cédula del Beneficiario
		//				   $as_codpro      // Código del Proveedor
		//				   $as_procede     // Procedencia del documento
		//				   $as_debhab      // Indica si la cuenta va por el debe o por el haber
		//				   $as_sccuenta    // Cuenta Contable
		//				   $ai_monto       // monto del asiento
		//	      Returns: $lb_valido True 
		//	  Description: Retorna un Booleano
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 06/05/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="INSERT INTO cxp_rd_scg (codemp,numrecdoc,codtipdoc,ced_bene,cod_pro,procede_doc,numdoccom,debhab,".
				"						 sc_cuenta,monto)".
				"     VALUES ('".$this->ls_codemp."','".$as_comprobante."','".$as_codtipdoc."','".$as_cedbene."',".
				"             '".$as_codpro."','".$as_procede."','".$as_comprobante."','".$as_debhab."',".
				"             '".$as_sccuenta."',".$ai_monto.")";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->Anticipo MÉTODO->uf_insert_detalle_contable ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			return false;
		}
		else
		{
			$lb_valido=true;
		}
		return $lb_valido;
	}
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_update_estatus_generacion_rd($as_codcon, $as_conant,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_recepcion_documentos
		//		   Access: private
		//	    Arguments: $as_conant	    // descripcion del documento
		//				   $as_codcon       // Codigo del contrato
		//				   $aa_seguridad    // Arreglo de las variables de seguridad
		//	      Returns: $lb_valido True si se genero la recepción de documento correctamente
		//	  Description: Retorna un Booleano
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 05/05/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="UPDATE sob_anticipo".
				"   SET estgenrd='1'".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codcon='".$as_codcon."'".
				"   AND codant='".$as_conant."'";		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
           	$this->io_msg->message("CLASE->Anticipo MÉTODO->uf_update_estatus_generacion_rd ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$this->io_sql->rollback();
			
		}
		else
		{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó el estatus de generacion de R.D. del Anticipo ".$as_conant." del Contrato ".$as_codcon." Asociado a la Empresa ".$this->ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				$lb_valido=true;
		}		
		return $lb_valido;
	}
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_recepcion($as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro)
	{
	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_recepcion
		//		   Access: private
		//	    Arguments: $as_numrecdoc // Numero de Recepcion de Documentos
		//                 $as_codtipdoc // Codigo de Tipo de Documento
		//                 $as_cedbene   // Cedula de Beneficiario
		//                 $as_codpro    // Codigo de Proveedor
 		//	      Returns: $ls_codpro Codigo de Proveedor
		//	  Description: Verifica la existencia de una Recepcion de Documentos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 29/04/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;		
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT numrecdoc". 
				"  FROM cxp_rd". 
				" WHERE cxp_rd.codemp='".$ls_codemp."'".
				"   AND cxp_rd.numrecdoc='".$as_numrecdoc."'".
				"   AND cxp_rd.codtipdoc='".$as_codtipdoc."'".
				"   AND cxp_rd.cod_pro='".$as_codpro."'".
				"   AND cxp_rd.ced_bene='".$as_cedbene."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select cuentacontable".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true;		
			}			
		}	
		return $lb_existe;
	}
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	
}
?>
