<?Php
class sigesp_sob_c_puntodecuenta
{
 var $io_function;
 var $la_empresa;
 var $io_sql;
 var $io_msg;
 var $io_funnum;
 var $io_contrato;
 var $io_datastore;

function sigesp_sob_c_puntodecuenta()
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
	require_once("../shared/class_folder/class_datastore.php");
	$this->io_datastore=new class_datastore();
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$this->seguridad=   new sigesp_c_seguridad();
	//print_r ($this->seguridad);
	require_once("class_folder/sigesp_sob_c_funciones_sob.php");
	$this->io_funsob=   new sigesp_sob_c_funciones_sob();	
}

//*******************************************************************************************************
//							Funciones relacionadas con el Punto de Cuenta                          
//*******************************************************************************************************
function uf_generar_codigo($as_codobr)
{
	  //////////////////////////////////////////////////////////////////////////////
	 //	Metodo: uf_generarcodigo
	 //	Access:  public
	 //	Returns: proximo codigo del Punto de Cuenta
	 //	Description: Funcion que permite generar el proximo codigo de un Punto de Cuenta
	 // Fecha: 27/06/2006
	 // Autor: Ing. Laura Cabré
	 //////////////////////////////////////////////////////////////////////////////

	$ls_codemp=$this->la_empresa["codemp"];
	$ls_sql="SELECT codpuncue 
			FROM sob_puntodecuenta
			WHERE codemp='".$ls_codemp."' AND codobr='".$as_codobr."'
			ORDER BY codpuncue DESC";		
	$rs_data=$this->io_sql->select($ls_sql);
	if ($row=$this->io_sql->fetch_row($rs_data))
	{ 
	  $codigo=$row["codpuncue"];
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

function uf_select_puntodecuenta ($as_codpuncue,$as_codobr,&$aa_data)
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
	$ls_sql="SELECT p.*,o.desobr,prov.nompro,prov.nomreppro,u.nomuni FROM sob_puntodecuenta p,sob_obra o,
			rpc_proveedor prov, sob_unidad u
			WHERE p.codemp='$ls_codemp' AND p.codemp=o.codemp AND p.codemp=prov.codemp AND p.codemp=u.codemp 
			AND p.codobr=o.codobr AND p.cod_pro=prov.cod_pro AND p.coduni=u.coduni AND p.codpuncue='$as_codpuncue' 
			AND p.codobr='$as_codobr'";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
		print "Error en uf_select_puntodecuenta".$this->io_function->uf_convertirmsg($this->io_sql->message);
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
			$aa_data=array();
			$lb_valido=0;
		}
	}
	return $lb_valido;
}

function uf_guardar_puntodecuenta ($as_codobr ,$as_codpuncue ,$as_codpro,$as_coduni,$as_despuncue,$as_repuncue,$as_asupuncue,$as_lapejepuncue,$as_monivapuncue,$as_monbrupuncue,$as_monantpuncue,$as_porantpuncue,$as_obspuncue,$as_fecpuncue,$as_porivapuncue,$aa_seguridad)      
{ 
   
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_guardar_puntodecuenta
	//  Access:			public
	//	Returns:		Boolean, Retorna true si procesa correctamente
	//	Description:	Funcion que se encarga de guardar la cabecera del Punto de Cuenta
	//  Fecha:          27/06/2006
	//	Autor:          Ing. Laura Cabré				
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$this->la_empresa["codemp"];
	$ld_lapejepuncue=$this->io_funsob->uf_convertir_cadenanumero($as_lapejepuncue);
	//$ld_monnetpuncue=$this->io_funsob->uf_convertir_cadenanumero($as_monnetpuncue);
	$ld_monivapuncue=$this->io_funsob->uf_convertir_cadenanumero($as_monivapuncue);
	$ld_monbrupuncue=$this->io_funsob->uf_convertir_cadenanumero($as_monbrupuncue);
	$ld_monantpuncue=$this->io_funsob->uf_convertir_cadenanumero($as_monantpuncue);
	$ld_porantpuncue=$this->io_funsob->uf_convertir_cadenanumero($as_porantpuncue);
	$ld_porivapuncue=$this->io_funsob->uf_convertir_cadenanumero($as_porivapuncue);		
	$ls_fecpuncue=$this->io_function->uf_convertirdatetobd($as_fecpuncue);	
	$ls_sql="INSERT INTO sob_puntodecuenta (codemp,codobr,codpuncue,cod_pro,coduni,despuncue,rempuncue,asupuncue,lapejepuncue,monivapuncue,monbrupuncue,monantpuncue,porantpuncue,obspuncue,fecpuncue,porivapuncue)
	         VALUES ('$ls_codemp','$as_codobr' ,'$as_codpuncue' ,'$as_codpro','$as_coduni','$as_despuncue','$as_repuncue','$as_asupuncue',$ld_lapejepuncue,$ld_monivapuncue,$ld_monbrupuncue,$ld_monantpuncue,$ld_porantpuncue,'$as_obspuncue','$ls_fecpuncue','$ld_porivapuncue')";
	
	$this->io_sql->begin_transaction();	
	$li_row=$this->io_sql->execute($ls_sql);
	if($li_row===false)
	{			
		print "Error en metodo uf_guardar_puntodecuenta".$this->io_function->uf_convertirmsg($this->io_sql->message);
		$this->io_sql->rollback();	
	}
	else
	{
		if($li_row>0)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el Punto de Cuenta ".$as_codpuncue." de la Obra ".$as_codobr." con motivo de $as_asupuncue, asociado a la Empresa ".$ls_codemp;
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
		}
	
	}		
	return $lb_valido;
}

function uf_update_puntodecuenta($as_codobr,$as_codpuncue ,$as_codpro,$as_coduni,$as_despuncue,$as_repuncue,
									$as_asupuncue,$as_lapejepuncue,$as_monnetpuncue,$as_monivapuncue,
									$as_monbrupuncue,$as_monantpuncue,$as_porantpuncue,$as_obspuncue,$as_fecpuncue,$as_porivapuncue,
									$aa_seguridad) 
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_update_puntodecuenta
	// Access:			public
	//	Returns:		Boolean, Retorna true si procesa correctamente
	//	Description:	Funcion que se encarga de actualizar el Punto de Cuenta.
	//  Fecha:          28/06/2006
	//	Autor:          Ing. Laura Cabré				
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$this->la_empresa["codemp"];
	$ld_lapejepuncue=$this->io_funsob->uf_convertir_cadenanumero($as_lapejepuncue);
	$ld_monnetpuncue=$this->io_funsob->uf_convertir_cadenanumero($as_monnetpuncue);
	$ld_monivapuncue=$this->io_funsob->uf_convertir_cadenanumero($as_monivapuncue);
	$ld_monbrupuncue=$this->io_funsob->uf_convertir_cadenanumero($as_monbrupuncue);
	$ld_monantpuncue=$this->io_funsob->uf_convertir_cadenanumero($as_monantpuncue);
	$ld_porantpuncue=$this->io_funsob->uf_convertir_cadenanumero($as_porantpuncue);	
	$ld_porivapuncue=$this->io_funsob->uf_convertir_cadenanumero($as_porivapuncue);	
	$ls_fecpuncue=$this->io_function->uf_convertirdatetobd($as_fecpuncue);	

	   $ls_sql="UPDATE sob_puntodecuenta
				SET codpuncue='$as_codpuncue' ,cod_pro='$as_codpro',coduni='$as_coduni',despuncue='$as_despuncue',rempuncue='$as_repuncue',asupuncue='$as_asupuncue',lapejepuncue='$ld_lapejepuncue',monnetpuncue='$ld_monnetpuncue',monivapuncue='$ld_monivapuncue',monbrupuncue='$ld_monbrupuncue',monantpuncue='$ld_monantpuncue',porantpuncue='$ld_porantpuncue',obspuncue='$as_obspuncue',fecpuncue='$ls_fecpuncue',porivapuncue='$ld_porivapuncue'
				WHERE codemp='".$ls_codemp."' AND codobr='".$as_codobr."' AND codpuncue='".$as_codpuncue."'";		
	
	$this->io_sql->begin_transaction();	
	$li_row=$this->io_sql->execute($ls_sql);
	if($li_row===false)
	{			
		print "Error en metodo uf_update_puntodecuenta".$this->io_function->uf_convertirmsg($this->io_sql->message);
		$this->io_sql->rollback();
		
	}
	else
	{
		if($li_row>0)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Punto de Cuenta ".$as_codpuncue." de la Obra ".$as_codobr." Asociado a la Empresa ".$ls_codemp;
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
}

//*******************************************************************************************************
//							Funciones relacionadas con las Cuentas
//*******************************************************************************************************

function uf_select_cuentas ($as_codpuncue,$as_codobr,&$aa_data,&$ai_rows)
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_select_cuentas
	// Access:			public
	//	Returns:		Boolean,arreglo. Retorna true y el arreglo de cuentas si existen registros en bd
	//	Description:	Funcion que se encarga de verificar si existen registros para un
	//                  punto de cuenta y retorna el arreglo con los mismos
	//  Fecha:          27/06/2006
	//	Autor:          Ing. Laura Cabré		
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$this->la_empresa["codemp"];
	$ls_sql="SELECT ca.*,(asignado-(comprometido+precomprometido)+aumento-disminucion) AS disponible
			FROM sob_cuentapuntodecuenta ca,spg_cuentas c
			WHERE ca.codemp='".$ls_codemp."' AND codpuncue='".$as_codpuncue."' AND codobr='$as_codobr' 
			AND ca.codestpro1=c.codestpro1 AND ca.codestpro2=c.codestpro2 AND ca.codestpro3=c.codestpro3
			AND ca.codestpro4=c.codestpro4 AND ca.codestpro5=c.codestpro5 AND ca.spg_cuenta=c.spg_cuenta";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
		$this->is_msg_error="Error en uf_select_cuentas".$this->io_function->uf_convertirmsg($this->io_sql->message);
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
			$aa_data=array();
		}			
	}		
	return $lb_valido;
}

function uf_select_cuenta ($as_codpuncue,$as_codobr,$as_codestpro5,$as_codestpro4,$as_codestpro3,$as_codestpro2,$as_spg_cuenta)
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_select_cuenta
	//  Access:			public
	//	Returns:		Boolean,arreglo. Retorna true si la cuenta buscada existe en bd
	//	Description:	Funcion que se encarga de verificar si existe una cuenta específica asociada a un
	//                  punto de cuenta
	//  Fecha:          27/06/2006
	//	Autor:          Ing. Laura Cabré		
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$this->la_empresa["codemp"];
	$ls_sql="SELECT  *
			 FROM sob_cuentapuntodecuenta
			 WHERE codemp='".$ls_codemp."' AND codpuncue='".$as_codpuncue."' AND codobr='".$as_codobr."' 
			 AND codestpro5='".$as_codestpro5."' AND codestpro4='$as_codestpro4' AND codestpro3='$as_codestpro3'
			 AND codestpro2='$as_codestpro2' AND  spg_cuenta='$as_spg_cuenta'";			 
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
		$this->is_msg_error="Error en uf_select_cuenta".$this->io_function->uf_convertirmsg($this->io_sql->message);
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

function uf_guardar_cuentas($as_codpuncue,$as_codobr,$as_codestpro5,$as_codestpro4,$as_codestpro3,$as_codestpro2,$as_codestpro1,$as_spg_cuenta,$as_concuepuncue,$as_monto,$aa_seguridad)             
{ 
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_guardar_cuentas
	//  Access:			public
	//	Returns:		Boolean, Retorna true si procesa correctamente
	//	Description:	Funcion que se encarga de guardar una Cuenta
	//  Fecha:          27/06/2006
	//	Autor:          Ing. Laura Cabré				
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$this->la_empresa["codemp"];
	$ls_monto=$this->io_funsob->uf_convertir_cadenanumero($as_monto);
	$ls_sql="INSERT INTO sob_cuentapuntodecuenta ( codemp,codpuncue,codobr,codestpro5,codestpro4,codestpro3,codestpro2,codestpro1,spg_cuenta,concuepuncue,monto)
	         VALUES ('$ls_codemp','$as_codpuncue','$as_codobr','$as_codestpro5','$as_codestpro4','$as_codestpro3','$as_codestpro2','$as_codestpro1','$as_spg_cuenta','$as_concuepuncue','$ls_monto')";
	$this->io_sql->begin_transaction();	
	$li_row=$this->io_sql->execute($ls_sql);
	if($li_row===false)
	{			
		print "Error en metodo uf_guardar_cuentas".$this->io_function->uf_convertirmsg($this->io_sql->message);
		$this->io_sql->rollback();	
	}
	else
	{
		if($li_row>0)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó Cuenta del Punto de Cuenta ".$as_codpuncue.", de la Obra ".$as_codobr.", Asociado a la Empresa ".$ls_codemp;
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
}

function uf_update_cuentas($as_codpuncue,$as_codobr,$aa_cuentasnuevas,$ai_totalfilas,$aa_seguridad)
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_update_cuentas
	//  Access:			public
	//	Returns:		Boolean, Retorna true si procesa correctamente
	//	Description:	Funcion que se encarga de actualizar las cuentas de gastos
	//					del Punto de Cuenta que han sido modificadas.
	//  Fecha:          28/06/2006
	//	Autor:          Ing. Laura Cabré				
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$ls_codemp=$this->la_empresa["codemp"];
	$this->uf_select_cuentas($as_codpuncue,$as_codobr,$la_cuentasviejas,$li_totalviejas);
	$li_totalnuevas=$ai_totalfilas;
	for ($li_i=1;$li_i<$li_totalnuevas;$li_i++)
	{
		$lb_existe=false;
		$lb_update=false;
		for ($li_j=1;$li_j<=$li_totalviejas;$li_j++)
		{
			if( ($la_cuentasviejas["codemp"][$li_j] == $ls_codemp) && 
				($la_cuentasviejas["codpuncue"][$li_j] == $as_codpuncue) &&  
				($la_cuentasviejas["codobr"][$li_j] == $as_codobr) &&
				($la_cuentasviejas["codestpro5"][$li_j] == $aa_cuentasnuevas["codestpro5"][$li_i]) &&
				($la_cuentasviejas["codestpro4"][$li_j] == $aa_cuentasnuevas["codestpro4"][$li_i]) &&
				($la_cuentasviejas["codestpro3"][$li_j] == $aa_cuentasnuevas["codestpro3"][$li_i]) &&
				($la_cuentasviejas["codestpro2"][$li_j] == $aa_cuentasnuevas["codestpro2"][$li_i]) &&
				($la_cuentasviejas["codestpro1"][$li_j] == $aa_cuentasnuevas["codestpro1"][$li_i]) &&
				($la_cuentasviejas["spg_cuenta"][$li_j] == $aa_cuentasnuevas["spg_cuenta"][$li_i])	)				
				{
					
					if ($la_cuentasviejas["monto"][$li_j] != $aa_cuentasnuevas["monto"][$li_i])
					{
						$lb_update=true;
					}
					$lb_existe = true;
				}				
			
		}
		if (!$lb_existe)
		{
			$lb_valido=$this->uf_guardar_cuentas($as_codpuncue,$as_codobr,$aa_cuentasnuevas["codestpro5"][$li_i],$aa_cuentasnuevas["codestpro4"][$li_i],$aa_cuentasnuevas["codestpro3"][$li_i],$aa_cuentasnuevas["codestpro2"][$li_i],$aa_cuentasnuevas["codestpro1"][$li_i],$aa_cuentasnuevas["spg_cuenta"][$li_i],$aa_cuentasnuevas["concuepuncue"][$li_i],$aa_cuentasnuevas["monto"][$li_i],$aa_seguridad);
		}
		if($lb_update)
		{
		  	$lb_valido=$this->uf_update_montocuenta($as_codpuncue,$as_codobr,$aa_cuentasnuevas["codestpro5"][$li_i],$aa_cuentasnuevas["codestpro4"][$li_i],$aa_cuentasnuevas["codestpro3"][$li_i],$aa_cuentasnuevas["codestpro2"][$li_i],$aa_cuentasnuevas["codestpro1"][$li_i],$aa_cuentasnuevas["spg_cuenta"][$li_i],$aa_cuentasnuevas["monto"][$li_i],$aa_seguridad);
		}
	}
	
	for ($li_j=1;$li_j<=$li_totalviejas;$li_j++)
	{
		$lb_existe=false;
		for ($li_i=1;$li_i<$li_totalnuevas;$li_i++)
		{
			if( ($la_cuentasviejas["codemp"][$li_j] == $ls_codemp) && 
				($la_cuentasviejas["codpuncue"][$li_j] == $as_codpuncue) &&  
				($la_cuentasviejas["codobr"][$li_j] == $as_codobr) &&
				($la_cuentasviejas["codestpro5"][$li_j] == $aa_cuentasnuevas["codestpro5"][$li_i]) &&
				($la_cuentasviejas["codestpro4"][$li_j] == $aa_cuentasnuevas["codestpro4"][$li_i]) &&
				($la_cuentasviejas["codestpro3"][$li_j] == $aa_cuentasnuevas["codestpro3"][$li_i]) &&
				($la_cuentasviejas["codestpro2"][$li_j] == $aa_cuentasnuevas["codestpro2"][$li_i]) &&
				($la_cuentasviejas["codestpro1"][$li_j] == $aa_cuentasnuevas["codestpro1"][$li_i]) &&
				($la_cuentasviejas["spg_cuenta"][$li_j] == $aa_cuentasnuevas["spg_cuenta"][$li_i])	) 
			{
				
				$lb_existe = true;
			}				
			
		}
		if (!$lb_existe)
		{				
			$lb_valido=$this->uf_delete_cuentas($as_codpuncue,$as_codobr,$la_cuentasviejas["codestpro5"][$li_j],$la_cuentasviejas["codestpro4"][$li_j],$la_cuentasviejas["codestpro3"][$li_j],$la_cuentasviejas["codestpro2"][$li_j],$la_cuentasviejas["codestpro1"][$li_j],$la_cuentasviejas["spg_cuenta"][$li_j],$aa_seguridad);
		}
	}	
	return $lb_valido;
}	

function uf_update_montocuenta($as_codpuncue,$as_codobr,$as_codestpro5,$as_codestpro4,$as_codestpro3,$as_codestpro2,
								$as_codestpro1,$as_spg_cuenta,$as_monto,$aa_seguridad)
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_update_montocuenta
	//  Access:			public
	//	Returns:		Boolean, Retorna true si procesa correctamente
	//	Description:	Funcion que se encarga de actualizar la descripcion de una garantía
	//                  de pago asociada a un contrato.
	//  Fecha:          28/03/2006
	//	Autor:          Ing. Laura Cabré				
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ld_monto=$this->io_funsob->uf_convertir_cadenanumero($as_monto);
	$ls_codemp=$this->la_empresa["codemp"];
	$ls_sql="UPDATE sob_cuentapuntodecuenta
			SET monto='".$ld_monto."'
			WHERE codemp='".$ls_codemp."' AND codpuncue='$as_codpuncue' AND codobr='$as_codobr' AND 
			codestpro5='$as_codestpro5' AND codestpro4='$as_codestpro4' AND codestpro3='$as_codestpro3'
			AND codestpro2='$as_codestpro2' AND codestpro1='$as_codestpro1' AND spg_cuenta='$as_spg_cuenta'";
	$this->io_sql->begin_transaction();	
	$li_row=$this->io_sql->execute($ls_sql);
	if($li_row===false)
	{			
		print "Error en metodo uf_update_montocuenta ".$this->io_function->uf_convertirmsg($this->io_sql->message);
		$this->io_sql->rollback();	
	}
	else
	{
		if($li_row>0)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la el monto de la Cuenta ".$as_spg_cuenta."= ".$as_monto.", del Punto de Cuenta ".$as_codpuncue." de la Obra $as_codobr, Asociado a la Empresa ".$ls_codemp;
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
		}
	
	}		
	return $lb_valido;
  }
  
function uf_delete_cuentas($as_codpuncue,$as_codobr,$as_codestpro5,$as_codestpro4,$as_codestpro3,$as_codestpro2,
								$as_codestpro1,$as_spg_cuenta,$aa_seguridad)
{
	//////////////////////////////////////////////////////////////////////////////////////////
	//  Function:       uf_delete_cuentas
	//  Access:			public
	//	Returns:		Boolean, Retorna true si procesa correctamente
	//	Description:	Funcion que se encarga de eliminar Cuenta de gasto asociada a un Punto de Cuenta
	//  Fecha:          28/06/2006
	//	Autor:          Ing. Laura Cabré				
	//////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$this->la_empresa["codemp"];
	$ls_sql="DELETE FROM sob_cuentapuntodecuenta
				WHERE codemp='".$ls_codemp."' AND codpuncue='$as_codpuncue' 
				AND codobr='$as_codobr' AND codestpro5='$as_codestpro5' AND codestpro4='$as_codestpro4'
				AND codestpro3='$as_codestpro3'	AND codestpro2='$as_codestpro2' AND codestpro1='$as_codestpro1' 
				AND spg_cuenta='$as_spg_cuenta'";	
	$this->io_sql->begin_transaction();
	$li_row=$this->io_sql->execute($ls_sql);
	if($li_row===false)
	{
		$this->io_sql->rollback();
		print"Error en metodo uf_delete_cuentas".$this->io_function->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		$lb_valido=true;
		/////////////////////////////////         SEGURIDAD               /////////////////////////////
		$ls_evento="DELETE";
		$ls_descripcion ="Eliminó la Cuenta ".$as_spg_cuenta."= ".$as_monto.", del Punto de Cuenta ".$as_codpuncue." de la Obra $as_codobr, Asociado a la Empresa ".$ls_codemp;
		$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////
		$this->io_sql->commit();
	}
	return $lb_valido;	

}

//*******************************************************************************************************
//							Funciones relacionadas con el estado del Punto de Cuenta   
//*******************************************************************************************************


function uf_update_estado($as_codpuncue,$as_codobr,$ai_estado,$aa_seguridad)
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_update_estado
	//  Access:			public
	//	Returns:		Boolean, Retorna true si procesa correctamente
	//	Description:	Funcion que se encarga de actualizar el estado del Punto de Cuenta
	//  Fecha:          29/06/2006
	//	Autor:          Ing. Laura Cabré				
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$this->la_empresa["codemp"];
	$ls_sql="UPDATE sob_puntodecuenta
				 SET estpuncue='".$ai_estado."'
				 WHERE codemp='".$ls_codemp."' AND codpuncue='".$as_codpuncue."' AND codobr='".$as_codobr."'";		
	
	$this->io_sql->begin_transaction();	
	$li_row=$this->io_sql->execute($ls_sql);
	if($li_row===false)
	{			
		print "Error en metodo uf_update_estado del Punto de Cuenta".$this->io_function->uf_convertirmsg($this->io_sql->message);
		$this->io_sql->rollback();
	}
	else
	{
	
		if($li_row>0)
		{
		    
			$ls_estado=$this->io_funsob->uf_convertir_numeroestado($ai_estado);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Estado del Punto de Cuenta ".$as_codpuncue." de la Obra".$as_codobr." a ".$ls_estado." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$this->io_sql->commit();
			$lb_valido=true;
		}
		else
		{
			$lb_valido=0;
			$this->io_sql->rollback();				
		}
	
	}		
	return $lb_valido;
}	

function uf_select_estado ($as_codpuncue,$as_codobr,&$estado)
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_select_estado
	//  Access:			public
	//	Returns:		Boolean, Retorna true si existe el registro en bd
	//	Description:	Funcion que se encarga de verificar el estado del Punto de Cuenta
	//  Fecha:          04/04/2006
	//	Autor:          Ing. Laura Cabré		
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$this->la_empresa["codemp"];
	$ls_sql="SELECT estpuncue
			 FROM sob_puntodecuenta
			 WHERE codemp='".$ls_codemp."' AND codpuncue='".$as_codpuncue."' AND codobr='".$as_codobr."'";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
		print "Error en select estado Punto de Cuenta ".$this->io_function->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		if($la_row=$this->io_sql->fetch_row($rs_data))
		{
			$estado=$la_row["estpuncue"];
			$lb_valido=true;
		}		
	}
	return $lb_valido;
}
 function uf_select_cargos($as_codpuncue,&$aa_data,&$ai_rows)
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
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT  a.codemp,a.codpuncue,a.codcar,c.dencar,a.monto,a.formula,c.codestpro,c.spg_cuenta
				 FROM sob_cargopuntodecuenta a, sigesp_cargos c 
				 WHERE a.codemp='".$ls_codemp."' AND c.codemp='".$ls_codemp."' AND a.codpuncue='".$as_codpuncue."' AND a.codcar=c.codcar";
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select".$this->io_function->uf_convertirmsg($this->io_sql->message);
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
	}
	function uf_guardar_dtcargos($as_codpuncue,$as_codcar,$as_basimp,$as_monto,$as_formula,$aa_seguridad)
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
		$ls_codemp=$this->la_empresa["codemp"];
		$ad_basimp=$this->io_funsob->uf_convertir_cadenanumero($as_basimp);
		$ad_monto=$this->io_funsob->uf_convertir_cadenanumero($as_monto);
		$ls_sql="INSERT INTO sob_cargopuntodecuenta (codemp,codpuncue,codcar,basimp,monto,formula)
		         VALUES ('".$ls_codemp."','".$as_codpuncue."','".$as_codcar."',".$ad_basimp.",".$ad_monto.",'".$as_formula."')";
		
		$this->io_sql->begin_transaction();	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{			
			print "Error en metodo uf_guardar_dtcargos".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();	
		}
		else
		{
			if($li_row>0)
			{
				/************    SEGURIDAD    **************/		 
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Cargo ".$as_codcar.", Detalle del Punto de Cuenta ".$as_codpuncue." Asociado a la Empresa ".$ls_codemp;
				   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion); 
				/*******************************************/
				$this->io_sql->commit();
				$lb_valido=true;
			}
			else
			{
				
				$this->io_sql->rollback();
			}
		
		}		
		return $lb_valido;
	}	
	function uf_delete_dtcargos($as_codpuncue,$as_codcar,$aa_seguridad)
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
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="DELETE FROM sob_cargopuntodecuenta
					WHERE codemp='".$ls_codemp."' AND codpuncue='".$as_codpuncue."' AND codcar='".$as_codcar."'";		
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_sql->rollback();
			print"Error en metodo eliminar_dtpartidas".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			/*************    SEGURIDAD    **************/		 
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó el Cargo ".$as_codcar.",Detalle del Punto de Cuenta ".$as_codpuncue." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
			/********************************************/	
			$lb_valido=true;
			$this->io_sql->commit();
		}
		return $lb_valido;	
	
	}
	function uf_update_dtcargos($as_codpuncue,$as_basimp,$aa_cargosnuevos,$ai_totalfilas,$aa_seguridad)
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
		$ls_codemp=$this->la_empresa["codemp"];
		$lb_valido=$this->uf_select_cargos($as_codpuncue,$la_cargosviejos,$li_totalviejos);
		$li_totalnuevas=$ai_totalfilas;
		for ($li_i=1;$li_i<$li_totalnuevas;$li_i++)
		{
			$lb_existe=false;
			for ($li_j=1;$li_j<=$li_totalviejos;$li_j++)
			{
				if( ($la_cargosviejos["codemp"][$li_j] == $ls_codemp) && ($la_cargosviejos["codpuncue"][$li_j] == $as_codpuncue) &&($la_cargosviejos["codcar"][$li_j] == $aa_cargosnuevos["codcar"][$li_i]) )
				{
					
					$lb_existe = true;
				}				
				
			}
			if (!$lb_existe)
			{
				$this->uf_guardar_dtcargos($as_codpuncue,$aa_cargosnuevos["codcar"][$li_i],$as_basimp,$aa_cargosnuevos["moncar"][$li_i],$aa_cargosnuevos["formula"][$li_i],$aa_seguridad);
			}
		}
		
		for ($li_j=1;$li_j<=$li_totalviejos;$li_j++)
		{
			$lb_existe=false;
			for ($li_i=1;$li_i<$li_totalnuevas;$li_i++)
			{
				if( ($la_cargosviejos["codemp"][$li_j] == $ls_codemp) &&($la_cargosviejos["codpuncue"][$li_j] == $as_codpuncue) &&($la_cargosviejos["codcar"][$li_j] == $aa_cargosnuevos["codcar"][$li_i]) )
				{
				  $lb_existe = true;
				}				
				
			}
			if (!$lb_existe)
			{
				$this->uf_delete_dtcargos($as_codpuncue,$la_cargosviejos["codcar"][$li_j],$aa_seguridad);
				
			}
		}			
}
function uf_load_dt_obra ($as_codemp,$as_codobr)
 {
        /***************************************************************************************/
		/*	Function:	    uf_load_dt_obra                                                    */    
		/*  Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si se consiguieron datos con dichos filtros  */ 
		/*	Description:	función que se encarga de cargar las partidas asignadas a la obra  */    
		/*  Fecha:          23/10/2007                                                         */        
		/*	Autor:          Ing. Carlos Zambrano                                               */     
		/***************************************************************************************/
  $lb_valido = true;
  $ls_sql = "SELECT sob_partidaobra.codpar,sob_partida.nompar,sob_unidad.nomuni,sob_partida.prepar,sob_partidaobra.canparobr,
                    sob_partidaobra.canparasi,sob_partidaobra.canpareje,sob_partida.despar
			   FROM sob_partidaobra, sob_partida, sob_unidad
			  WHERE sob_partidaobra.codemp = '".$as_codemp."' 
			    AND sob_partidaobra.codobr = '".$as_codobr."' 
			    AND sob_partidaobra.codemp=sob_partida.codemp
			    AND sob_partidaobra.codpar=sob_partida.codpar
				AND sob_partida.codemp=sob_unidad.codemp
			    AND sob_partida.coduni=sob_unidad.coduni
				ORDER BY sob_partidaobra.codpar ASC";
 //print "sql = >".$ls_sql.'<br>';
  $rs_data= $this->io_sql->select($ls_sql);
  if($rs_data===false)
		{
			$this->is_msg_error="Error en select".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
			$lb_valido = false;
		}
   return $rs_data;		  
 }


}
?>