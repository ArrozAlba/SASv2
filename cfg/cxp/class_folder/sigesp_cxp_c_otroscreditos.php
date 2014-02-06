<?php 
class sigesp_cxp_c_otroscreditos
{
var $ls_sql;
var $is_msg_error;
	
		function sigesp_cxp_c_otroscreditos($conn)
		{
		  require_once("../../shared/class_folder/sigesp_c_seguridad.php");
          require_once("../../shared/class_folder/class_funciones.php");
		  require_once("../../shared/class_folder/class_mensajes.php");
		  require_once("../../shared/class_folder/sigesp_c_check_relaciones.php"); 
	      $this->seguridad  = new sigesp_c_seguridad();	
		  $this->io_funcion = new class_funciones();
		  $this->io_sql     = new class_sql($conn);
		  $this->io_msg     = new class_mensajes($conn);
		  $this->io_chek    = new sigesp_c_check_relaciones($conn);
		  $this->ls_gestor  = $_SESSION["ls_gestor"];
		}

function uf_insert_otroscreditos($as_codemp,$ar_datos,$ai_estmodest,$aa_seguridad,$as_estcla) 
{
//////////////////////////////////////////////////////////////////////////////
//	Metodo: uf_insert_otroscreditos
//	Access:  public
//	Arguments:  $as_codemp,$ar_datos,$aa_seguridad
//	Returns: $lb_valido= Variable booleana que devuelve true si la sentencia
//                       SQL fue ejecutada sin errores de lo contrario devuelve false.
//	Description: Función que se encarga de insertar un registro en la tabla
//              sigesp_cargos.     
//////////////////////////////////////////////////////////////////////////////

	$ls_codigo       = $ar_datos["codigo"];
	$ls_denominacion = $ar_datos["denominacion"];
	$ls_spgcuenta    = $ar_datos["spg_cuenta"];
	$ls_codestpro    = $ar_datos["codestpro"];
	$ls_codestpro1   = $ar_datos["codestpro1"];
	$ls_codestpro2   = $ar_datos["codestpro2"];
	$ls_codestpro3   = $ar_datos["codestpro3"];
	$ls_codestpro4   = $ar_datos["codestpro4"];
	$ls_codestpro5   = $ar_datos["codestpro5"];
	
		 $ls_codestpro  = $ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
		
	
	$ld_porcentaje = $ar_datos["porcentaje"];
	if (empty($ld_porcentaje))
	   {
	     $ld_porcentaje=0;
	   }
	else
	   {
	     $ld_porcentaje = str_replace('.','',$ld_porcentaje);
 	     $ld_porcentaje = str_replace(',','.',$ld_porcentaje);
	   }   
	$li_estlibcompras   = $ar_datos["estlibcompras"];
	$ls_formula         = $ar_datos["formula"];
	$ls_sql             = " INSERT INTO sigesp_cargos                                                                         ".
			              " (codemp,codcar,dencar,codestpro,spg_cuenta,porcar,estlibcom,formula,estcla)                              ".
			              " VALUES                                                                                            ".
			              " ('".$as_codemp."','".$ls_codigo."','".$ls_denominacion."','".$ls_codestpro."','".$ls_spgcuenta."',".
			              " ".$ld_porcentaje.",".$li_estlibcompras.",'".$ls_formula."','".$as_estcla."')                                       ";
	$this->io_sql->begin_transaction();
	$li_numrows=$this->io_sql->execute($ls_sql);
	if ($li_numrows===false)
	   {
		 $this->io_msg->message("CLASE->SIGESP_CXP_C_OTROSCREDITOS; METODO->uf_insert_otroscreditos; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		 $lb_valido=false;
	   }
	else
	   {
	     /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	     $ls_evento="INSERT";
	     $ls_descripcion ="Insertó en CXP el Cargo ".$ls_denominacion." con código ".$ls_codigo;
	     $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	     $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	     $aa_seguridad["ventanas"],$ls_descripcion);
	     /////////////////////////////////         SEGURIDAD               ///////////////////////////
		 $lb_valido=true;
	   }
return $lb_valido;
}

function uf_update_otroscreditos($as_codemp,$ar_datos,$ai_estmodest,$aa_seguridad,$as_estcla) 
{
//////////////////////////////////////////////////////////////////////////////
//	     Metodo:  uf_update_otroscreditos
//	     Access:  public
//	  Arguments:  $as_codemp,$ar_datos,$aa_seguridad
//	    Returns:  $lb_valido= Variable booleana que devuelve true si la sentencia
//                SQL fue ejecutada sin errores de lo contrario devuelve false.
//	Description:  Función que se encarga de actualizar registros en la tabla
//                sigesp_cargos.   
//////////////////////////////////////////////////////////////////////////////
	$ls_codigo       = $ar_datos["codigo"];
	$ls_denominacion = $ar_datos["denominacion"];
	$ls_spgcuenta    = $ar_datos["spg_cuenta"];
	$ls_codestpro    = $ar_datos["codestpro"];
	$ls_codestpro1   = $ar_datos["codestpro1"];
	$ls_codestpro2   = $ar_datos["codestpro2"];
	$ls_codestpro3   = $ar_datos["codestpro3"];
	$ls_codestpro4   = $ar_datos["codestpro4"];
	$ls_codestpro5   = $ar_datos["codestpro5"];
	
	$ls_codestpro  = $ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
	
	$ld_porcentaje    = $ar_datos["porcentaje"];
	$li_estlibcompras = $ar_datos["estlibcompras"];	
	$ls_formula       = $ar_datos["formula"];
	$ls_sql=" UPDATE sigesp_cargos ".
			" SET  dencar='".$ls_denominacion."',codestpro='".$ls_codestpro."',spg_cuenta='".$ls_spgcuenta."',".
			" porcar=".$ld_porcentaje.",estlibcom=".$li_estlibcompras.",formula='".$ls_formula."', estcla='".$as_estcla."' ".
			" WHERE codemp='" .$as_codemp. "' AND codcar = '" .$ls_codigo. "'";
	$this->io_sql->begin_transaction();
	$li_numrows=$this->io_sql->execute($ls_sql);
	if ($li_numrows===false)
	   {
		 $this->io_msg->message("CLASE->SIGESP_CXP_C_OTROSCREDITOS; METODO->uf_update_otroscreditos; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		 $lb_valido=false;
	   }
	else
	   {
		 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	  	 $ls_evento="UPDATE";
		 $ls_descripcion ="Actualizó en CXP el Cargo con código ".$ls_codigo;
	     $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		 $aa_seguridad["ventanas"],$ls_descripcion);
		 /////////////////////////////////         SEGURIDAD               ///////////////////////////
		 $lb_valido=true;
	   }
return $lb_valido;
} 

function uf_delete_otroscreditos($as_codemp,$as_codigo,$as_dencar,$aa_seguridad)
{
//////////////////////////////////////////////////////////////////////////////
//	Metodo: uf_delete_otroscreditos
//	Access:  public
//	Arguments:  $as_codemp,$ar_datos,$aa_seguridad
//	Returns: $lb_valido= Variable booleana que devuelve true si la sentencia
//                       SQL fue ejecutada sin errores de lo contrario devuelve false.
//	Description: Funcion que se encarga de Eliminar registros en la tabla 
//               sigesp_cargos.
//////////////////////////////////////////////////////////////////////////////
  
	$lb_valido = false;
	switch ($this->ls_gestor) 
	{
		case 'INFORMIX':
			$as_condicion = " AND (colname='codcar')";
			break;
	
		case 'POSTGRES':
			$as_condicion = " AND (column_name='codcar')";
			break;
			
		case 'MYSQLT':
			$as_condicion = " AND (column_name='codcar')";
			break;
	}
	//Nombre del o los campos que deseamos buscar.
	$as_mensaje   = "";                           //Mensaje que será enviado al usuario si se encuentran relaciones a asociadas al campo.
	$lb_tiene     = $this->io_chek->uf_check_relaciones($as_codemp,$as_condicion,'sigesp_cargos',$as_codigo,$as_mensaje);//Verifica los movimientos asociados a la cuenta  
	if (!$lb_tiene)
	{
		  	  $ls_sql    = "DELETE FROM sigesp_cargos WHERE codemp='".$as_codemp."' AND codcar='".$as_codigo."'";	   
			  $this->io_sql->begin_transaction();
			  $rs_data = $this->io_sql->execute($ls_sql);
			  if ($rs_data===false)
		      {
		     	$this->io_msg->message("CLASE->SIGESP_CXP_C_OTROSCREDITOS; METODO->uf_delete_otroscreditos; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			   $lb_valido=false;
			  }
		      else
		      {
			   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
			   $ls_evento="DELETE";
			   $ls_descripcion =" Eliminó en CXP el Cargo con código ".$as_codigo." con denominación ".$as_dencar;
			   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
			   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
			   $aa_seguridad["ventanas"],$ls_descripcion);
			   /////////////////////////////////         SEGURIDAD               ///////////////////////////
			   $lb_valido=true;
		  	  }	   
		  	
	}
	else
	{
	   $this->io_msg->message($this->io_chek->is_msg_error);
	}	 
  
  return $lb_valido;
}


function uf_select_otroscreditos($as_codemp,$as_codigo) 
{
//////////////////////////////////////////////////////////////////////////////
//	     Metodo:  uf_select_otroscreditos
// 	     Access:  public
//	   Arguments  $as_codemp,$as_codigo
//	    Returns:  $lb_valido= Variable booleana que devuelve true si la fue
//                encontrado el registro y la sentencia SQL 
//                fue ejecutada sin errores de lo contrario devuelve false.	
//	Description:  Función que se encarga de buscar registros en la tabla
//                sigesp_cargos.
//////////////////////////////////////////////////////////////////////////////
	$ls_sql=" SELECT * FROM sigesp_cargos WHERE codemp='".$as_codemp."' AND codcar='".$as_codigo."'";
	$rs_otroscred=$this->io_sql->select($ls_sql);
	if ($rs_otroscred===false)
	   {
		 $this->io_msg->message("CLASE->SIGESP_CXP_C_OTROSCREDITOS; METODO->uf_select_otroscreditos; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		 $lb_valido=false;
	   }
	else
	   {
	     $li_numrows=$this->io_sql->num_rows($rs_otroscred);
		 if ($li_numrows>0)
			{
			  $lb_valido=true;
			  $this->io_sql->free_result($rs_otroscred);
			}
		 else
			{
			  $lb_valido=false;
			}
		}
return $lb_valido;
}
//-------------------------------------------------------------------------------------------------------------------------------------
function uf_select_configuracion_iva($as_codemp,&$as_confiva) 
{
	//////////////////////////////////////////////////////////////////////////////
	//	     Metodo:  uf_select_configuracion_iva
	// 	     Access:  public
	//	   Arguments  $as_codemp,$as_codigo
	//	    Returns:  $lb_valido= Variable que devuelve la configuraciondel iva si es contable
	//                o presupuestario.	
	//	Description:  Función que se encarga de buscar registros en la tabla
	//                sigesp_cargos.
	//////////////////////////////////////////////////////////////////////////////
    $lb_valido=false;
	$ls_sql=" SELECT * FROM sigesp_empresa WHERE codemp='".$as_codemp."'";
	$rs_data=$this->io_sql->select($ls_sql);
	if ($rs_data===false)
	{
	   $this->io_msg->message("CLASE->SIGESP_CXP_C_OTROSCREDITOS; METODO->uf_select_configuracion_iva; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   $lb_valido=false;
	}
	else
	{
	  /* $li_numrows=$this->io_sql->num_rows($rs_data);
	   if ($li_numrows>0)*/
	   while($row=$this->io_sql->fetch_row($rs_data))
	   {
		  $lb_valido=true;
		  $as_confiva=$row["confiva"];
	   }
	   $this->io_sql->free_result($rs_data);
	}
    return $lb_valido;
}
//-------------------------------------------------------------------------------------------------------------------------------------
}//Fin de la Clase...
?> 