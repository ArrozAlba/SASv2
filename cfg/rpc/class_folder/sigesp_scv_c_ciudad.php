<?php
class sigesp_scv_c_ciudad
{

	var $ls_sql;
	var $is_msg_error;
	
	function sigesp_scv_c_ciudad($conn)
	{
		require_once("../../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../../shared/class_folder/class_funciones.php");
		require_once("../../shared/class_folder/class_mensajes.php");
		$this->seguridad   = new sigesp_c_seguridad();          
		$this->io_funcion  = new class_funciones();
		$this->io_sql      = new class_sql($conn);		
		$this->io_msg      = new class_mensajes();
		$this->io_database = $_SESSION["ls_database"];
	    $this->io_gestor   = $_SESSION["ls_gestor"]; 
	}
 
function uf_scv_select_ciudad($as_codpai,$as_codest,$as_codciu) 
{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	          Metodo:  uf_scv_select_ciudad
	//	          Access:  public
	//	       Arguments:  $as_codpai    // código de pais
	//        			   $as_codest    // código de estado
	//        			   $as_codciu    // código de ciudad
	//	         Returns:  $lb_valido.
	//	     Description:  Función que se encarga verificar la existencia o no de una ciudad
	//     Elaborado Por:  Ing. Luis Anibal Lang
	// Fecha de Creación:  02/10/2006      
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	$lb_valido=false;
	$ls_sql=" SELECT * FROM scv_ciudades ".
			" WHERE codpai='".$as_codpai."'".
			" AND   codest='".$as_codest."'".
			" AND   codciu='".$as_codciu."'";
	$rs_data=$this->io_sql->select($ls_sql);
	if ($rs_data===false)
	{
		$this->io_msg->message("CLASE->sigecp_scv_c_ciudad METODO->uf_scv_select_ciudad ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	}
	else
	{
		$li_numrows=$this->io_sql->num_rows($rs_data);
		if($li_numrows>0)
		{					 
			$lb_valido=true;
		}
	}
	return $lb_valido;
} // fin function uf_scv_select_ciudad

function uf_scv_insert_ciudad($as_codpai,$as_codest,$as_codciu,$as_desciu,$aa_seguridad) 
{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	          Metodo:  uf_scv_insert_ciudad
	//	          Access:  public
	//	       Arguments:  $as_codpai    // código de pais
	//        			   $as_codest    // código de estado
	//        			   $as_codciu    // código de ciudad
	//        			   $as_desciu    // descripcion de ciudad
	//        			   $aa_seguridad // arreglo de seguridad
	//	         Returns:  $lb_valido.
	//	     Description:  Función que se encarga insertar una ciudad que pertenece a un estado y de un pais en la tabla scv_ciudades
	//     Elaborado Por:  Ing. Luis Anibal Lang
	// Fecha de Creación:  02/10/2006      
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	$lb_valido=false;
	$this->io_sql->begin_transaction();
	$ls_sql= " INSERT INTO scv_ciudades (codpai,codest,codciu,desciu) ".
			 " VALUES ('".$as_codpai."','".$as_codest."','".$as_codciu."','".$as_desciu."')";
	$rs_data=$this->io_sql->execute($ls_sql);
	if ($rs_data===false)
	{
		$this->io_sql->rollback();
		$this->io_msg->message("CLASE->sigecp_scv_c_ciudad METODO->uf_scv_insert_ciudad ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	}
	else
	{
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		$ls_evento="INSERT";
		$ls_descripcion= "Insertó la Ciudad ".$as_desciu." con código ".$as_codciu.
						 " asociado al Estado ".$as_codest;
		$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               ///////////////////////////
		$this->io_sql->commit();
		$lb_valido=true;
	}
	return $lb_valido;
} // fin function uf_scv_insert_ciudad

function uf_scv_update_ciudad($as_codpai,$as_codest,$as_codciu,$as_desciu,$aa_seguridad) 
{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	          Metodo:  uf_scv_update_ciudad
	//	          Access:  public
	//	       Arguments:  $as_codpai    // código de pais
	//        			   $as_codest    // código de estado
	//        			   $as_codciu    // código de ciudad
	//        			   $as_desciu    // descripcion de ciudad
	//        			   $aa_seguridad // arreglo de seguridad
	//	         Returns:  $lb_valido.
	//	     Description:  Función que se encarga actualizar una ciudad que pertenece a un estado y de un pais en la tabla scv_ciudades
	//     Elaborado Por:  Ing. Luis Anibal Lang
	// Fecha de Creación:  02/10/2006      
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	$lb_valido=false;
	$this->io_sql->begin_transaction();
	$ls_sql=" UPDATE scv_ciudades SET  desciu='".$as_desciu."' ".
			" WHERE codpai='".$as_codpai."'".
			" AND   codest='".$as_codest."'".
			" AND   codciu='".$as_codciu."'";
	$rs_data=$this->io_sql->execute($ls_sql);
	if ($rs_data===false)
	{
		$this->io_sql->rollback();
		$this->is_msg_error="CLASE->sigesp_scv_c_ciudad METODO->uf_scv_update_ciudad ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		$ls_evento="UPDATE";
		$ls_descripcion="Actualizó la ciudad ".$as_codciu." asociado al Estado ".$as_codest;
		$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               ///////////////////////////
		$this->io_sql->commit();
		$lb_valido=true;
	}
	return $lb_valido;
} // fin function uf_scv_update_ciudad

function uf_scv_delete_ciudad($as_codpai,$as_codest,$as_codciu,$aa_seguridad)
{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	          Metodo:  uf_scv_delete_ciudad
	//	          Access:  public
	//	       Arguments:  $as_codpai    // código de pais
	//        			   $as_codest    // código de estado
	//        			   $as_codciu    // código de ciudad
	//        			   $aa_seguridad // arreglo de seguridad
	//	         Returns:  $lb_valido.
	//	     Description:  Función que se encarga eliminar una ciudad de la tabla scv_ciudades
	//     Elaborado Por:  Ing. Luis Anibal Lang
	// Fecha de Creación:  03/10/2006      
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	$lb_valido=false;
	$this->io_sql->begin_transaction();
	$ls_sql=" DELETE FROM scv_ciudades ".
			" WHERE codpai='".$as_codpai."'".
			" AND   codest='".$as_codest."'".
			" AND   codciu='".$as_codciu."'";
	$rs_data=$this->io_sql->execute($ls_sql);
	if ($rs_data===false)
	{
		$lb_valido=false;
		$this->io_msg->message("CLASE->sigesp_scv_c_ciudad METODO->uf_scv_delete_ciudad ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	}
	else
	{
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		$ls_evento="DELETE";
		$ls_descripcion="Eliminó la ciudad ".$as_codciu." asociado al Estado ".$as_codest;
		$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               ///////////////////////////
		$lb_valido = true;
	} 		 
	return $lb_valido;
} // fin function uf_scv_delete_ciudad
  
function uf_llenarcombo_pais()
{
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_llenarcombo_pais
//	          Access:  public
//	       Arguments: 
//	         Returns:  $lb_valido.
//		 Description:  Funcion que se encarga de extraer todos aquellos registro de la tabla sigesp_pais
//               	   para ser cargados en un objeto de tipo combobox/list menu. 
//     Elaborado Por:  Ing. Luis Anibal Lang
// Fecha de Creación:  02/10/2006      
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	$ls_sql=" SELECT * FROM sigesp_pais ORDER BY codpai ASC";
	$rs_data=$this->io_sql->select($ls_sql);
	if ($rs_data===false)
	{
		$lb_valido=false; 
		$this->io_msg->message("CLASE->sigesp_scv_c_ciudad METODO->uf_llenarcombo_pais ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	}
	return $rs_data;         
} // fin function uf_llenarcombo_pais

function uf_load_estados($as_codpais)
{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	          Metodo:  uf_load_estados
	//	          Access:  public
	//	       Arguments:  $as_codpais // codigo de pais
	//	         Returns:  $lb_valido.
	//		 Description:  Funcion que se encarga de extraer todos aquellos registro de la tabla sigesp_estado
	//              	   para ser cargados en un objeto de tipo combobox/list menu. 
	//     Elaborado Por:  Ing. Luis Anibal Lang
	// Fecha de Creación:  02/10/2006      
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	$ls_sql= " SELECT * FROM sigesp_estados".
			 " WHERE    codpai='".$as_codpais."'".
			 " ORDER BY desest ASC ";
	$rs_data = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	{
		$this->io_msg->message("CLASE->SIGESP_RPC_C_MUNICIPIO; METODO->uf_load_estados; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	}
	return $rs_data;         
} // fin function uf_load_estados

function uf_generar_codigo($ab_empresa,$as_codemp,$as_tabla,$as_columna,$as_columna2,$as_columna3)
{ 
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	          Metodo:  uf_generar_codigo
	//	          Access:  public
	//	       Arguments:  $as_codpais // codigo de pais
	//					   $ab_empresa // Si usara el campo empresa como filtro    
	//					   $as_codemp    // codigo de la empresa
	//					   $as_tabla     // Nombre de la tabla 
	//					   $ai_length    // longitud del campo
	//	         Returns:  $lb_valido.
	//		 Description:   Este método genera el numero consecutivo del código de cualquier tabla deseada
	//     Elaborado Por:  Ing. Nestor Falcón
	// Fecha de Creación:  02/08/2006      
	//	  Modificado Por:  Ing. Luis Anibal Lang
	// 		Fecha Modif.:  02/10/2006
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	$lb_existe=$this->existe_tabla($as_tabla);
	if ($lb_existe)
	{
		$lb_existe=$this->existe_columna($as_tabla,$as_columna);
		if ($lb_existe)
		{
			$li_longitud=$this->longitud_campo($as_tabla,$as_columna) ;
			if ($ab_empresa)
			{	
				$ls_sql=" SELECT ".$as_columna."".
						" FROM ".$as_tabla."".
						" WHERE codemp='".$as_codemp."'".
						" ORDER BY $as_columna DESC";		
				$rs_funciondb=$this->io_sql->select($ls_sql);
				if ($row=$this->io_sql->fetch_row($rs_funciondb))
				{ 
					$codigo=$row[$as_columna];
					settype($codigo,'int');                             // Asigna el tipo a la variable.
					$codigo = $codigo + 1;                              // Le sumo uno al entero.
					settype($codigo,'string');                          // Lo convierto a varchar nuevamente.
					$ls_codigo=$this->io_funcion->uf_cerosizquierda($codigo,$li_longitud);
				}
				else
				{
					$codigo="1";
					$ls_codigo=$this->io_funcion->uf_cerosizquierda($codigo,$li_longitud);
				}
			}	
			else
			{
				$ls_sql=" SELECT ".$as_columna."".
						" FROM ".$as_tabla."".
						" WHERE codpai='".$as_columna2."'".
						" AND   codest='".$as_columna3."'".
						" ORDER BY $as_columna DESC";	
				$rs_funciondb=$this->io_sql->select($ls_sql);
				if ($row=$this->io_sql->fetch_row($rs_funciondb))
				{ 
					$codigo=$row[$as_columna];
					settype($codigo,'int');                                          // Asigna el tipo a la variable.
					$codigo = $codigo + 1;                                           // Le sumo uno al entero.
					settype($codigo,'string');                                       // Lo convierto a varchar nuevamente.
					$ls_codigo=$this->io_funcion->uf_cerosizquierda($codigo,$li_longitud); 
				}   
				else
				{
					$codigo="1";
					$ls_codigo=$this->io_funcion->uf_cerosizquierda($codigo,$li_longitud);
				}
			}// SI NO TIENE CODIGO DE EMPRESA
		}
		else
		{
		$ls_codigo="";
		$this->is_msg_error="No existe el campo" ;
		}
	}
	else
	{
		$ls_codigo="";
		$this->is_msg_error="No existe la tabla	" ;
	}
	return $ls_codigo;
} // fin function uf_generar_codigo

function longitud_campo($as_tabla,$as_columna)
{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	          Metodo:  uf_generar_codigo
	//	          Access:  public
	//	       Arguments:  $as_tabla   // nombre de la tabla
	//					   $as_columna // nombre de la columna
	//	         Returns:  $lb_valido.
	//		 Description:  Este método verifica la longitud de un campo
	//     Elaborado Por:  Ing. Nestor Falcón
	// Fecha de Creación:  02/08/2006      
	//	  Modificado Por:  Ing. Luis Anibal Lang
	// 		Fecha Modif.:  02/10/2006
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
   $li_length = 0;
   switch ($this->io_gestor)
   {
		case "MYSQLT":
		   $ls_sql=" SELECT character_maximum_length AS width ".
				   " FROM information_schema.columns ".
				   " WHERE TABLE_SCHEMA='".$this->io_database."' AND UPPER(table_name)=UPPER('".$as_tabla."') AND ".
				   "       UPPER(column_name)=UPPER('".$as_columna."')";
		break;
		case "POSTGRES":
		  $ls_sql = " SELECT character_maximum_length AS width ".
					"   FROM INFORMATION_SCHEMA.COLUMNS ".
					"  WHERE table_catalog='".$this->io_database."'".
					"    AND UPPER(table_name)=UPPER('".$as_tabla."')".
					"    AND UPPER(column_name)=UPPER('".$as_columna."')";
		break;
		case "INFORMIX":
			   $ls_sql= "SELECT syscolumns.collength AS width FROM syscolumns, systables ".
						" WHERE syscolumns.tabid = systables.tabid ".
						" AND UPPER(systables.tabname)=UPPER('".$as_tabla."') ".
						" AND UPPER(syscolumns.colname)=UPPER('".$as_columna."')";	
		break;
   }
   $rs_data=$this->io_sql->select($ls_sql);
   if ($row=$this->io_sql->fetch_row($rs_data))   {  $li_length=$row["width"];  } 
   return $li_length; 
} // end function()

function existe_tabla($as_tabla)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Metodo:  existe_tabla
//	          Access:  public
//	       Arguments:  $as_tabla   // nombre de la tabla
//	         Returns:  $lb_valido.
//		 Description:  Este método verifica la existencia de una tabla
//     Elaborado Por:  Ing. Nestor Falcón
// Fecha de Creación:  02/08/2006      
//	  Modificado Por:  Ing. Luis Anibal Lang
// 		Fecha Modif.:  02/10/2006
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
   $lb_existe = false;
   switch ($this->io_gestor)
   {
		case "MYSQLT":
		   $ls_sql= " SELECT * FROM ".
					" INFORMATION_SCHEMA.TABLES ".
					" WHERE TABLE_SCHEMA='".$this->io_database."' AND (UPPER(TABLE_NAME)=UPPER('".$as_tabla."'))";				
		break;
		case "POSTGRES":
		   $ls_sql= " SELECT * FROM ".
					" INFORMATION_SCHEMA.TABLES ".
					" WHERE table_catalog='".$this->io_database."' AND (UPPER(table_name)=UPPER('".$as_tabla."'))";	
		break;
		case "INFORMIX":
		   $ls_sql= " SELECT * FROM ".
					" systables ".
					" WHERE  (UPPER(tabname)=UPPER('".$as_tabla."'))";	
		break;
   }
   $rs_data=$this->io_sql->select($ls_sql);
   if($rs_data===false)
   {   
	  $this->io_msg->message("ERROR en uf_select_table()".$this->io_funcion->uf_convertirmsg($this->io_sql->message));			
	 return false; 
   }
   else
   {
	  if ($row=$this->io_sql->fetch_row($rs_data)) { $lb_existe=true; } 
	  $this->io_sql->free_result($rs_data);	 
   }	  
   return $lb_existe;
} // end function uf_select_table

function existe_columna($as_tabla,$as_columna)
{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	          Metodo:  existe_columna
	//	          Access:  public
	//	       Arguments:  $as_tabla   // nombre de la tabla
	//					   $as_columna // nombre de la columna
	//	         Returns:  $lb_valido.
	//		 Description:  Este método verifica la existencia de una tabla
	//     Elaborado Por:  Ing. Nestor Falcón
	// Fecha de Creación:  02/08/2006      
	//	  Modificado Por:  Ing. Luis Anibal Lang
	// 		Fecha Modif.:  02/10/2006
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
  $lb_existe = false;
   switch ($this->io_gestor)
   {
		case "MYSQLT":
		  $ls_sql = " SELECT COLUMN_NAME ".
					" FROM INFORMATION_SCHEMA.COLUMNS ".
					" WHERE TABLE_SCHEMA='".$this->io_database."' AND UPPER(TABLE_NAME)=UPPER('".$as_tabla."') AND UPPER(COLUMN_NAME)=UPPER('".$as_columna."')";
		break;
		case "POSTGRES":
		  $ls_sql = " SELECT COLUMN_NAME ".
					" FROM INFORMATION_SCHEMA.COLUMNS ".
					" WHERE table_catalog='".$this->io_database."' AND UPPER(table_name)=UPPER('".$as_tabla."') AND UPPER(column_name)=UPPER('".$as_columna."')";
		break;
		case "INFORMIX":
			   $ls_sql= "SELECT syscolumns.* FROM syscolumns, systables ".
						" WHERE syscolumns.tabid = systables.tabid ".
						" AND UPPER(systables.tabname)=UPPER('".$as_tabla."') ".
						" AND UPPER(syscolumns.colname)=UPPER('".$as_columna."')";	
		break;
   }
  
  $rs_data=$this->io_sql->select($ls_sql);
  if($rs_data===false)
  {   
	 $this->io_msg->message("ERROR en uf_select_column()".$this->io_funcion->uf_convertirmsg($this->io_sql->message));			
	 return false;
  }
  else
  {
	  if ($row=$this->io_sql->fetch_row($rs_data)) { $lb_existe=true; } 
	  $this->io_sql->free_result($rs_data);	 
  }	  
  return $lb_existe;
} // end function uf_select_column
} // fin class sigesp_scv_c_ciudad
?>