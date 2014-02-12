<?php
class sigesp_ins_c_consolidacion_contable{

function sigesp_ins_c_consolidacion_contable($as_path)
{
	///////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: sigesp_ins_c_consolidacion_contable
	//		   Access: public 
	//	  Description: Constructor de la Clase
	//	   Creado Por: Ing. Néstor Falcón.
	// Fecha Creación: 17/09/2008. 								Fecha Última Modificación : 
	///////////////////////////////////////////////////////////////////////////////////////////////////
	require_once($as_path."shared/class_folder/class_sql.php");
	require_once($as_path."shared/class_folder/sigesp_include.php");	
	require_once($as_path."shared/class_folder/class_mensajes.php");
	require_once($as_path."shared/class_folder/class_funciones.php");
	require_once($as_path."shared/class_folder/sigesp_c_seguridad.php");
	    
	$this->io_include = new sigesp_include();
	$ls_conect        = $this->io_include->uf_conectar();
	$this->io_sql = new class_sql($ls_conect);	
	$this->io_msg = new class_mensajes();
	$this->io_sss = new sigesp_c_seguridad();
	$this->io_fun = new class_funciones();
	$this->ls_codemp = $_SESSION["la_empresa"]["codemp"];
}

function uf_load_db_consolidan()
{
	///////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_load_db_consolidan
	//		   Access: private
	//	      Returns: 
	//	  Description: Devuelve el nombre de las Bases de Datos que consolidan sobre otra Base de Datos.
	//	   Creado Por: Ing. Nestor Falcón.
	// Fecha Creación: 17/09/2008. 								Fecha Última Modificación : 06/05/2007
	///////////////////////////////////////////////////////////////////////////////////////////////////

	$ls_sql = "SELECT TRIM(nombasdat) as nombasdat  ".
	          "  FROM sigesp_consolidacion ".
			  "	WHERE codemp='".$this->ls_codemp."' ".
			  "	  AND TRIM(nombasdat)<>''".
			  " GROUP BY TRIM(nombasdat) ";
	
	$rs_data = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
	     $this->io_msg->message("CLASS->sigesp_ins_c_consolidacion_contable.php->MÉTODO->uf_load_db_consolidan;ERROR->".$this->io_fun->uf_convertirmsg($this->io_sql->message));
	   }
	return $rs_data; 
}

function uf_procesar_consolidacion_contable($as_path)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_procesar_consolidacion_contable
	//		   Access: public
	//	    Arguments: 
	//	      Returns: 
	//	  Description: 
	//	   Creado Por: Ing. Nestor Falcón.
	// Fecha Creación: 17/09/2008. 								Fecha Última Modificación : 17/09/2008.
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
	$lb_valido = true;
	$la_dbname = $this->uf_load_db_consolidan();
	$li_totrow = $this->io_sql->num_rows($la_dbname);
	if ($li_totrow>0)
	   {
         $this->io_sql->begin_transaction();
		 $lb_valido = $this->uf_delete_datos_consolidacion();
		 if ($lb_valido)
		    {
			  $lb_valido = $this->uf_match_scgcuentas($as_path,$la_dbname);
			}		 
	     if ($lb_valido)
		    {
			  $this->io_sql->commit();
			  $this->io_msg->message("Proceso ejecutado con Éxito !!!");
			}
	     else
		    {
			  $this->io_sql->rollback();
			  $this->io_msg->message("Ocurrió un error en la Ejecución de la Consolidación !!!");
			}
	   }
    else
	   {
	     $lb_valido = false;
		 $this->io_msg->message("Información de Consolidación No Disponible, Contacte al Administrador del Sistema !!!");
	   } 
    return $lb_valido;
}

function uf_match_scgcuentas($as_path,$aa_dbname)
{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_match_scgcuentas
	//		   Access: private
	//	    Arguments: $as_dbname = Arregle cargado con las Bases de Datos que consolida en la Consolidadora.
	//	      Returns: 
	//	  Description: Compara los Planes de Cuenta Contables de las Bases de Datos que consolidan contra la consolidadora.
	//	   Creado Por: Ing. Nestor Falcón.
	// Fecha Creación: 17/09/2008. 								Fecha Última Modificación : 17/09/2008.
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $lb_valido = true;
	require_once($as_path."sigesp_config.php");
	$ls_dbname = $_SESSION["ls_database"];//Base de Datos Consolidadora.
	$la_scgcta = $this->uf_load_scgcta();
	if (!empty($la_scgcta))
	   {
		 while(!$aa_dbname->EOF && $lb_valido)
			  { 
			    $ls_datbas = $aa_dbname->fields["nombasdat"];//Base de Datos que se integra a la Consolidadora.
				if (!empty($ls_datbas))
				   { 
					 $this->uf_obtener_parametros_conexion($i,$empresa,$as_path,$ls_datbas,$as_hostname,$as_login,$as_password,$as_gestor);
					 $ls_conaux = $this->io_include->uf_conectar_otra_bd($as_hostname,$as_login,$as_password,$ls_datbas,$as_gestor);
					 $this->io_sqlaux = new class_sql($ls_conaux);
					 $la_datscg = $this->uf_load_scgcta_consolida($ls_datbas);//Cargo en un arreglo el plan de cuentas contable.
					 if (!empty($la_datscg))
					    { 
						  $la_datemp = $this->uf_load_datos_empresa();
						  //$la_ctascg = array_intersect_assoc($la_scgcta,$la_datscg);//Arreglo con elementos comunes.					      
						  //if (count($la_scgcta)==count($la_ctascg))//Comparo el arreglo original con el intersectado para ver si son iguales.
						  $lb_valido = $this->uf_verificar_plan_cuenta($la_scgcta,$la_datscg);
						  if ($lb_valido)
					         {
						       $li_estempcon = $la_datemp[0]["estempcon"];
							   if ($li_estempcon==0)//Si la empresa no es Consolidadora.
							      {
								    $la_datcon    = $this->uf_load_plan_cuentas_scg($ls_datbas);//La información es la misma del Plan de Cuentas Orginal (Empresa Consolidadora).
								    $ls_codaltemp = $la_datemp[0]["codaltemp"]; 
								  }
							   elseif($li_estempcon==1)//Si la empresa es Consolidadora
							      {
									$la_datcon    = $this->uf_load_contable_consolidacion($ls_datbas);//Extraigo la Información de la tabla scg_cuentas_consolida.								  
								    $ls_codaltemp = "----";//El Código alterno de la Empresa en blanco ya que scg_cuentas_consolida maneja los mismos.
								  }
							   if (!empty($ls_codaltemp))
							      { 
							        $lb_valido = $this->uf_insert_scgcuenta_consolida($la_datcon,$ls_codaltemp);//Inserto el Plan de Cuentas en la Tabla scg_cuentas_consolida usando el Código de Empresa Alterno.
								    if ($lb_valido)
									   {
										 if ($li_estempcon==0)//Si la empresa no es Consolidadora.
										    {
											  $la_datsal = $this->uf_load_scg_saldos($ls_datbas);//Cargo los datos de la tabla scg_saldos, solo las estatus S.
										    }  
										 elseif($li_estempcon==1)//Si la empresa es Consolidadora
										    {
											  $la_datsal = $this->uf_load_saldos_consolida($ls_datbas);//Cargo los datos de la tabla scg_saldos_consolida, solo las estatus S.
										    }
										 if (!empty($la_datsal))
										    { 
											  $lb_valido = $this->uf_insert_saldos_consolida($la_datsal,$ls_codaltemp);//Inserto la Data de los Saldos.
										    }
									     else
										    {
											 // $lb_valido = false;
											}
									   }
								  }
						       else
							      {
								    $lb_valido = false;
								    $this->io_msg->message("La Empresa ".$ls_datbas.", No dispone del Código Alterno !!!");
								    break;
								  } 
							 }
						  else
					         {
					           $lb_valido = false;
					           $this->io_msg->message("No se puede proceder a la Consolidación Contable, ya que el Plan de Cuentas de la Empresa ".$la_datemp[0]["nomemp"]." no coincide con el Plan de Cuentas de la Empresa Consolidadora !!!");
						       break;
							 }
						  unset($la_ctascg,$la_datemp);
						}
					 unset($ls_conaux,$this->io_sqlaux);
					 $aa_dbname->MoveNext();//Esto debe ir cuando todo se haya ejecutado al pelo.  
				   }
			    else
				   {
					 $lb_valido = false;
					 break;
				   }
			  }
	     if ($lb_valido)
		    {
			  unset($la_datcon,$la_datsal);
			  $la_datcon = $this->uf_load_plan_cuentas_scg($ls_dbname);
			  if (!empty($la_datcon))
			     {
				   $ls_codaltemp = $_SESSION["la_empresa"]["codaltemp"];
				   $lb_valido    = $this->uf_insert_scgcuenta_consolida($la_datcon,$ls_codaltemp);//Inserto el Plan de Cuentas en la Tabla scg_cuentas_consolida usando el Código de Empresa Alterno.
				   if ($lb_valido)
					  {
					    $la_datsal = $this->uf_load_scg_saldos_local($ls_dbname);
						if (!empty($la_datsal))
						   {
						     $lb_valido = $this->uf_insert_saldos_consolida($la_datsal,$ls_codaltemp);//Inserto la Data de los Saldos.
						     unset($la_datcon,$la_datsal,$ls_codaltemp);
						   }
					  }
				 }
			  else
			     {
				   $lb_valido = false;
				   $this->io_msg->message("Plan de Cuentas Contables de ".$ls_dbname.", No Disponible, Contacte al Administrador del Sistema !!!");
				 }
			} 
	   }
    else
	   {
	     $lb_valido = false;
		 $this->io_msg->message("Plan de Cuentas Contables de ".$ls_dbname.", No Disponible, Contacte al Administrador del Sistema !!!");
	   }
	return $lb_valido;
}

function uf_obtener_parametros_conexion($i,$empresa,$as_path,$as_database,&$as_hostname,&$as_login,&$as_password,&$as_gestor)
{
  $as_hostname="";
  $as_login="";
  $as_password="";
  $as_gestor="";
  for ($li_i=1;$li_i<=$i;$li_i++)
	  { 
		if ($empresa["database"][$li_i]==trim($as_database))
		   {
		     $as_hostname = $empresa["hostname"][$li_i];
			 $as_login    = $empresa["login"][$li_i];
			 $as_password = $empresa["password"][$li_i];
		     $as_gestor   = $empresa["gestor"][$li_i];
		   }	
	  }
}

function uf_load_scgcta()
{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_load_scgcta
	//		   Access: private
	//	      Returns: 
	//	  Description: Carga el Plan de Cuentas Contable de la BD Consolidadora con solo las cuenta de tipo 'S'.
	//	   Creado Por: Ing. Nestor Falcón.
	// Fecha Creación: 17/09/2008. 								Fecha Última Modificación : 17/09/2008.
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
	$la_datscg = array();
	$ls_sql = "SELECT TRIM(sc_cuenta) as sc_cuenta,nivel
	             FROM scg_cuentas 
				WHERE codemp='".$this->ls_codemp."'
				  AND status='S'
				ORDER BY sc_cuenta ASC";
    $rs_data = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
	     return false;
		 $this->io_msg->message("CLASS->sigesp_ins_c_consolidacion_contable.php->MÉTODO->uf_load_scgcta;ERROR->".$this->io_fun->uf_convertirmsg($this->io_sql->message));
	   }
    else
	   {
	     $la_datscg = $rs_data->GetRows();
	   }
	return $la_datscg;
}

function uf_load_scgcta_consolida($as_dbname)
{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_load_scgcta
	//		   Access: private
	//	      Returns: 
	//	  Description: 
	//	   Creado Por: Ing. Nestor Falcón.
	// Fecha Creación: 17/09/2008. 								Fecha Última Modificación : 17/09/2008.
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
	$la_datscg = array();
	$ls_sql = "SELECT TRIM(sc_cuenta) as sc_cuenta,nivel
			     FROM scg_cuentas 
				WHERE codemp='".$this->ls_codemp."'
				  AND status='S'
				ORDER BY sc_cuenta ASC";
	$rs_data = $this->io_sqlaux->select($ls_sql);
	if ($rs_data===false)
	   {
	     return false;
		 $this->io_msg->message("CLASS->sigesp_ins_c_consolidacion_contable.php->MÉTODO->uf_load_scgcta_consolida;ERROR->".$this->io_fun->uf_convertirmsg($this->io_sql->message));
	   }
    else
	   {
		 $la_datscg = $rs_data->GetRows();
		 if (empty($la_datscg))
		    {
			  $this->io_msg->message("Plan de Cuentas Contables de ".$as_dbname.", No Disponible, Contacte al Administrador del Sistema !!!");
			}
	   }
	return $la_datscg;
}

function uf_load_datos_empresa()
{
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //	     Function: uf_load_datos_empresa
  //		   Access: private
  //	      Returns: 
  //	  Description: Carga el Nombre, Estatus de Consolidación y el Código Alterno de la Empresa.
  //	   Creado Por: Ing. Nestor Falcón.
  //   Fecha Creación: 18/09/2008. 								Fecha Última Modificación : 18/09/2008.
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  $la_datemp = array();
  $ls_sql  = "SELECT nombre as nomemp,estempcon,codaltemp 
                FROM sigesp_empresa 
			   WHERE codemp='".$this->ls_codemp."'";
  $rs_data = $this->io_sqlaux->select($ls_sql);
  if ($rs_data===false)
	 {
	   return false;
	   $this->io_msg->message("CLASS->sigesp_ins_c_consolidacion_contable.php->MÉTODO->uf_load_datos_empresa;ERROR->".$this->io_fun->uf_convertirmsg($this->io_sql->message));
	 }
  else
     {
	   $la_datemp = $rs_data->GetRows();
	 }
  return $la_datemp;
}

function uf_load_contable_consolidacion($as_dbname)
{
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //	     Function: uf_load_contable_consolidacion
  //		   Access: private
  //	      Returns: 
  //	  Description: Carga el Nombre, Estatus de Consolidación y el Código Alterno de la Empresa.
  //	   Creado Por: Ing. Nestor Falcón.
  //   Fecha Creación: 18/09/2008. 								Fecha Última Modificación : 18/09/2008.
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$la_datscgcon = array();
	$ls_sql = "SELECT codemp,TRIM(sc_cuenta) as sc_cuenta,status,denominacion,nivel,referencia
			     FROM scg_cuentas_consolida
				WHERE status='S'
				ORDER BY sc_cuenta ASC";
	$rs_data = $this->io_sqlaux->select($ls_sql);
	if ($rs_data===false)
	   {
	    return false;
		 $this->io_msg->message("CLASS->sigesp_ins_c_consolidacion_contable.php->MÉTODO->uf_load_contable_consolidacion;ERROR->".$this->io_fun->uf_convertirmsg($this->io_sql->message));
	   }
    else
	   {
		 $la_datscgcon = $rs_data->GetRows();
		 if (empty($la_datscgcon))
		    {
			  $lb_valido = false;
			  $this->io_msg->message("Información de scg_cuentas_consolida en ".$as_dbname.", No Disponible, Contacte al Administrador del Sistema !!!");
			}
	   }
	return $la_datscgcon;
}

function uf_load_plan_cuentas_scg($as_dbname)
{
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //	     Function: uf_load_plan_cuentas_scg
  //		   Access: private
  //	      Returns: 
  //	  Description: Carga los datos del Plan de Cuentas Contable de la Empresa Consolidadora.
  //	   Creado Por: Ing. Nestor Falcón.
  //   Fecha Creación: 18/09/2008. 								Fecha Última Modificación : 18/09/2008.
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$la_datplacta = array();
	$ls_sql = "SELECT TRIM(sc_cuenta) as sc_cuenta,status,denominacion,nivel,referencia
			     FROM scg_cuentas
				WHERE codemp='".$this->ls_codemp."'
				  AND status='S'
				ORDER BY sc_cuenta ASC";
	$rs_data = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
	     $lb_valido = false;
		 $this->io_msg->message("CLASS->sigesp_ins_c_consolidacion_contable.php->MÉTODO->uf_load_plan_cuentas_scg;ERROR->".$this->io_fun->uf_convertirmsg($this->io_sql->message));
	   }
    else
	   {
		 $la_datscgcon = $rs_data->GetRows();
		 if (empty($la_datscgcon))
		    {
			  $lb_valido = false;
			  $this->io_msg->message("Información de scg_cuentas ".$as_dbname.", No Disponible, Contacte al Administrador del Sistema !!!");
			}
	   }
	return $la_datscgcon;
}

function uf_load_saldos_consolida($as_dbname)
{
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //	     Function: uf_load_saldos_consolida
  //		   Access: private
  //	      Returns: 
  //	  Description: Carga el Nombre, Estatus de Consolidación y el Código Alterno de la Empresa.
  //	   Creado Por: Ing. Nestor Falcón.
  //   Fecha Creación: 18/09/2008. 								Fecha Última Modificación : 18/09/2008.
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$la_datsalcon = array();
	$ls_sql = "SELECT scg_saldos_consolida.codemp,
	                  TRIM(scg_saldos_consolida.sc_cuenta) as sc_cuenta,
					  scg_saldos_consolida.fecsal,
					  scg_saldos_consolida.debe_mes,
					  scg_saldos_consolida.haber_mes
			     FROM scg_saldos_consolida, scg_cuentas
				WHERE scg_cuentas.status='S'
				  AND TRIM(scg_saldos_consolida.sc_cuenta)=TRIM(scg_cuentas.sc_cuenta)				  
				ORDER BY sc_cuenta ASC";
	$rs_data = $this->io_sqlaux->select($ls_sql);
	if ($rs_data===false)
	   {
	     $lb_valido = false;
		 $this->io_msg->message("CLASS->sigesp_ins_c_consolidacion_contable.php->MÉTODO->uf_load_saldos_consolida;ERROR->".$this->io_fun->uf_convertirmsg($this->io_sql->message));
	   }
    else
	   {
		 $la_datsalcon = $rs_data->GetRows();
		 if (empty($la_datsalcon))
		    {
			  $lb_valido = false;
			  $this->io_msg->message("Información de scg_saldos_consolida en ".$as_dbname.", No Disponible, Contacte al Administrador del Sistema !!!");
			}
	   }
	return $la_datsalcon;
}

function uf_load_scg_saldos($as_dbname)
{
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //	     Function: uf_load_scg_saldos
  //		   Access: private
  //	      Returns: 
  //	  Description: Carga el Nombre, Estatus de Consolidación y el Código Alterno de la Empresa.
  //	   Creado Por: Ing. Nestor Falcón.
  //   Fecha Creación: 18/09/2008. 								Fecha Última Modificación : 18/09/2008.
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$la_datscgsal = array();
	$ls_sql = "SELECT scg_saldos.codemp,TRIM(scg_saldos.sc_cuenta) as sc_cuenta,
					  scg_saldos.fecsal,scg_saldos.debe_mes,scg_saldos.haber_mes
			     FROM scg_saldos, scg_cuentas
				WHERE scg_saldos.codemp='".$this->ls_codemp."'
				  AND scg_cuentas.status='S'
				  AND scg_saldos.sc_cuenta=scg_cuentas.sc_cuenta
				ORDER BY sc_cuenta ASC";
	$rs_datos = $this->io_sqlaux->select($ls_sql);
	if ($rs_datos===false)
	   {
	     return false;
		 $this->io_msg->message("CLASS->sigesp_ins_c_consolidacion_contable.php->MÉTODO->uf_load_scg_saldos;ERROR->".$this->io_fun->uf_convertirmsg($this->io_sqlaux->message));
	   }
    else
	   {
		 $la_datscgsal = $rs_datos->GetRows();
		 if (empty($la_datscgsal))
		    {
			  $lb_valido = false;
			  $this->io_msg->message("Información de scg_saldos en ".$as_dbname.", No Disponible, Contacte al Administrador del Sistema !!!");
			}
	   }
	return $la_datscgsal;
}

function uf_load_scg_saldos_local($as_dbname)
{
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //	     Function: uf_load_scg_saldos_local
  //		   Access: private
  //	      Returns: 
  //	  Description: Carga el Nombre, Estatus de Consolidación y el Código Alterno de la Empresa.
  //	   Creado Por: Ing. Nestor Falcón.
  //   Fecha Creación: 18/09/2008. 								Fecha Última Modificación : 18/09/2008.
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$la_datscgsal = array();
	$ls_sql = "SELECT scg_saldos.codemp,TRIM(scg_saldos.sc_cuenta) as sc_cuenta,
					  scg_saldos.fecsal,scg_saldos.debe_mes,scg_saldos.haber_mes
			     FROM scg_saldos, scg_cuentas
				WHERE scg_saldos.codemp='".$this->ls_codemp."'
				  AND scg_cuentas.status='S'
				  AND scg_saldos.sc_cuenta=scg_cuentas.sc_cuenta
				ORDER BY sc_cuenta ASC";
	$rs_datos = $this->io_sql->select($ls_sql);
	if ($rs_datos===false)
	   {
	     return false;
		 $this->io_msg->message("CLASS->sigesp_ins_c_consolidacion_contable.php->MÉTODO->uf_load_scg_saldos;ERROR->".$this->io_fun->uf_convertirmsg($this->io_sql->message));
	   }
    else
	   {
		 $la_datscgsal = $rs_datos->GetRows();
		 if (empty($la_datscgsal))
		    {
			  $lb_valido = false;
			  $this->io_msg->message("Información de scg_saldos en ".$as_dbname.", No Disponible, Contacte al Administrador del Sistema !!!");
			}
	   }
	return $la_datscgsal;
}

function uf_insert_scgcuenta_consolida($aa_datcon,$as_codaltemp)
{
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //	     Function: uf_insert_scgcuenta_consolida
  //		   Access: private
  //	      Returns: 
  //	  Description: Carga el Nombre, Estatus de Consolidación y el Código Alterno de la Empresa.
  //	   Creado Por: Ing. Nestor Falcón.
  //   Fecha Creación: 18/09/2008. 								Fecha Última Modificación : 18/09/2008.
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
  $lb_valido = true;
  if ($as_codaltemp!='----')
     {
	   $ls_codemp = $as_codaltemp;
	 }
  $li_totrows = count($aa_datcon);
  for ($li_i=0;$li_i<$li_totrows && $lb_valido;$li_i++)
      {
		if ($as_codaltemp=='----')
		   {
		     $ls_codemp = $aa_datcon[$li_i]["codemp"];	
		   }
		$ls_scgcta    = $aa_datcon[$li_i]["sc_cuenta"];
		$ls_estcta    = $aa_datcon[$li_i]["status"];
		$ls_denscgcta = $aa_datcon[$li_i]["denominacion"];
		$li_nivscgcta = $aa_datcon[$li_i]["nivel"];
		$ls_refscgcta = $aa_datcon[$li_i]["referencia"];
		
	    $ls_sql = "INSERT INTO scg_cuentas_consolida (codemp,sc_cuenta,status,denominacion,nivel,referencia) 
					  VALUES ('".$ls_codemp."','".$ls_scgcta."','".$ls_estcta."','".$ls_denscgcta."',".$li_nivscgcta.",'".$ls_refscgcta."')";
	    $rs_data = $this->io_sql->execute($ls_sql);
	    if ($rs_data===false)
		   {
		     $lb_valido = false;
		     $this->io_msg->message("CLASS->sigesp_ins_c_consolidacion_contable.php->MÉTODO->uf_insert_scgcuenta_consolida2;ERROR->".$this->io_fun->uf_convertirmsg($this->io_sql->message));
			 break;
		   }
	  }  
  return $lb_valido;
}

function uf_insert_saldos_consolida($aa_datcon,$as_codaltemp)
{
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //	     Function: uf_insert_saldos_consolida
  //		   Access: private
  //	      Returns: 
  //	  Description: Carga el Nombre, Estatus de Consolidación y el Código Alterno de la Empresa.
  //	   Creado Por: Ing. Nestor Falcón.
  //   Fecha Creación: 18/09/2008. 								Fecha Última Modificación : 18/09/2008.
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  $lb_valido = true;
  if ($as_codaltemp!='----')
     {
	   $ls_codemp = $as_codaltemp;
	 }
  $li_totrows = count($aa_datcon);
  for ($li_i=0;$li_i<$li_totrows && $lb_valido;$li_i++)
      {
	    if ($as_codaltemp=='----')
		   {
		     $ls_codemp = $aa_datcon[$li_i]["codemp"];	
		   }
		$ls_scgcta = $aa_datcon[$li_i]["sc_cuenta"];
		$ls_fecsal = $aa_datcon[$li_i]["fecsal"];
		
		$ld_debmes = $aa_datcon[$li_i]["debe_mes"];
		$ld_habmes = $aa_datcon[$li_i]["haber_mes"];
		
		$ls_sql = "INSERT INTO scg_saldos_consolida (codemp,sc_cuenta,fecsal,debe_mes,haber_mes) 
                   VALUES ('".$ls_codemp."','".$ls_scgcta."','".$ls_fecsal."',".$ld_debmes.",".$ld_habmes.")";

		$rs_data = $this->io_sql->execute($ls_sql);
	    if ($rs_data===false)
	       {
	         $lb_valido = false;
		     $this->io_msg->message("CLASS->sigesp_ins_c_consolidacion_contable.php->MÉTODO->uf_insert_saldos_consolida;ERROR->".$this->io_fun->uf_convertirmsg($this->io_sql->message));
		   }
	  }
  return $lb_valido;
}

function uf_delete_datos_consolidacion()
{
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //	     Function: uf_delete_datos_consolidacion
  //		   Access: private
  //	      Returns: 
  //	  Description: Carga el Nombre, Estatus de Consolidación y el Código Alterno de la Empresa.
  //	   Creado Por: Ing. Nestor Falcón.
  //   Fecha Creación: 18/09/2008. 								Fecha Última Modificación : 18/09/2008.
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$lb_valido = true;
	$ls_sql = "DELETE FROM scg_saldos_consolida";
	$rs_data = $this->io_sql->execute($ls_sql);
	if ($rs_data===false)
	   {
	     $lb_valido = false;
		 $this->io_msg->message("CLASS->sigesp_ins_c_consolidacion_contable.php->MÉTODO->uf_delete_datos_consolidacion;ERROR->".$this->io_fun->uf_convertirmsg($this->io_sql->message));
	   }
    else
	   {
		 $ls_sql = "DELETE FROM scg_cuentas_consolida";
		 $rs_data = $this->io_sql->execute($ls_sql);
		 if ($rs_data===false)
		    {
			  $lb_valido = false;
			  $this->io_msg->message("CLASS->sigesp_ins_c_consolidacion_contable.php->MÉTODO->uf_delete_datos_consolidacion;ERROR->".$this->io_fun->uf_convertirmsg($this->io_sql->message));
		    }
	   }
	return $lb_valido;
}

function uf_verificar_plan_cuenta($aa_consolidadora,$aa_consolida)
{
	foreach ($aa_consolida as $cuenta_consolida)  // recorro el plan de cuentas del arreglo que consolida
	{
		$encontrado=false;
		foreach ($aa_consolidadora as $cuenta_consolidadora) // por cada cuenta de la que consolida debe estar en la consolidadora
		{
			if (($cuenta_consolida['sc_cuenta'] == $cuenta_consolidadora['sc_cuenta']) && ($cuenta_consolida['nivel'] == $cuenta_consolidadora['nivel']))
			{
				$encontrado=true;
				break;
			}
		}
		if ($encontrado == false)
		{
			  $this->io_msg->message("LA CUENTA ".$cuenta_consolida['sc_cuenta']. " CON NIVEL ".$cuenta_consolida['nivel']." NO EXISTE EN LA CONSOLIDADORA.");
			   return false;
		}
	}
	return true;
}

}
?>