<?php
class sigesp_rpc_c_municipio
{

var $ls_sql;
	
	function sigesp_rpc_c_municipio($conn)
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
 
function uf_insert_municipio($as_codpais,$as_codest,$as_codmun,$as_denmun,$aa_seguridad) 
{
//////////////////////////////////////////////////////////////////////////////
//	    Metodo:  uf_insertmunicipio
//	    Access:  public
//	 Arguments:  $as_codemp,$ar_datos,$aa_seguridad
//	   Returns:	 $lb_valido.	
// Description:  Función que se encarga de insertar un municipio para un estado en específico. 
//////////////////////////////////////////////////////////////////////////////

  $ls_sql = " INSERT INTO sigesp_municipio (codpai,codest,codmun,denmun) ".
			" VALUES ('".$as_codpais."','".$as_codest."','".$as_codmun."','".$as_denmun."')";
  $this->io_sql->begin_transaction();
  $rs_data=$this->io_sql->execute($ls_sql);
  if ($rs_data===false)
	 {
	   $lb_valido=false; 
	   $this->io_msg->message("CLASE->SIGESP_RPC_C_MUNICIPIO; METODO->uf_insert_municipio; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	   $ls_evento="INSERT";
	   $ls_descripcion ="Insertó en RPC el Municipio ".$as_denmun." con código ".$as_codmun.
						" asociado al Estado ".$as_codest;
	   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	   $aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               ///////////////////////////
	   $lb_valido=true;
	 }
return $lb_valido;
}

function uf_update_municipio($as_codpais,$as_codest,$as_codmun,$as_denmun,$aa_seguridad) 
{
//////////////////////////////////////////////////////////////////////////////
//	    Metodo:  uf_update_municipio
//	    Access:  public
//	 Arguments:  $as_codpais,$as_codest,$as_codmun,$as_denmun,$aa_seguridad
//	   Returns:	 $lb_valido.	
// Description:  Función que se encarga de actualizar los datos de un municipio en la tabla sigesp_municipio . 
//////////////////////////////////////////////////////////////////////////////

  $ls_sql=" UPDATE sigesp_municipio SET  denmun='".$as_denmun."' ".
		  "  WHERE codpai='".$as_codpais."' AND codest='".$as_codest."' AND codmun='".$as_codmun."'";
  $this->io_sql->begin_transaction();
  $rs_data=$this->io_sql->execute($ls_sql);
  if ($rs_data===false)
	 {
	   $lb_valido=false; 
	   $this->io_msg->message("CLASE->SIGESP_RPC_C_MUNICIPIO; METODO->uf_update_municipio; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	   $ls_evento="UPDATE";
	   $ls_descripcion ="Actualizó en RPC el Municipio ".$as_denmun." con código ".$as_codmun.
						" asociado al Estado ".$as_codest;
	   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	   $aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               ///////////////////////////
	   $lb_valido=true;
	 }
return $lb_valido;
} 

function uf_delete_municipio($as_codemp,$as_codpais,$as_codest,$as_codmun,$aa_seguridad)
{
//////////////////////////////////////////////////////////////////////////////
//	    Metodo:  uf_delete_municipio
//	    Access:  public
//	 Arguments:  $as_codpais,$as_codest,$as_codmun,$aa_seguridad
//	   Returns:	 $lb_valido.	
// Description:  Función que se encarga de eliminar un municipio en la tabla sigesp_municipio . 
//////////////////////////////////////////////////////////////////////////////

  $ls_sql=" DELETE FROM sigesp_municipio WHERE codpai='".$as_codpais."' AND codest='".$as_codest."' AND codmun='".$as_codmun."'";
  $this->io_sql->begin_transaction();
  $rs_data=$this->io_sql->execute($ls_sql);
  if ($rs_data===false)
	 {
	   $lb_valido=false; 
	   $this->io_msg->message("CLASE->SIGESP_RPC_C_MUNICIPIO; METODO->uf_delete_municipio; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	   $ls_evento="DELETE";
	   $ls_descripcion ="Eliminó en RPC el Municipio con código ".$as_codmun." asociado al Estado ".$as_codest;
	   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
									$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
									$aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               ///////////////////////////
	   $lb_valido = true;
	 } 		 
  return $lb_valido;
}

function uf_load_municipio($as_codpais,$as_codest,$as_codmun) 
{
//////////////////////////////////////////////////////////////////////////////
//	    Metodo:  uf_select_municipio
//	    Access:  public
//	 Arguments:  $as_codpais,$as_codest,$as_codmun
//	   Returns:	 $lb_valido.	
// Description:  Función que se encarga de verificar si existe o no un municipio para un estado específico
//               la funcion devuelve true en caso de que exista, caso contrario devuelve false. 
//////////////////////////////////////////////////////////////////////////////

	$ls_sql=" SELECT * FROM sigesp_municipio ".
	        " WHERE codpai='".$as_codpais."' AND codest='".$as_codest."' AND codmun='".$as_codmun."'" ;
	$rs_data=$this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
	     $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
		 $lb_valido=false;
	   }
	else
	   {
		$li_numrows=$this->io_sql->num_rows($rs_data);
		if($li_numrows>0)
			{					 
			  $lb_valido=true;
			}
			else
			{					 
			  $lb_valido=false;
			}
	   }
	return $lb_valido;
 }
  
function uf_llenarcombo_pais()
{
//////////////////////////////////////////////////////////////////////////////
//	    Metodo:  uf_llenarcombo_pais
//	    Access:  public
//	   Returns:	 $lb_valido.	
// Description:  Funcion que se encarga de extraer todos aquellos registro de la tabla sigesp_pais
//               para ser cargados en un objeto de tipo combobox/list menu. 
//////////////////////////////////////////////////////////////////////////////

	$ls_sql=" SELECT * FROM sigesp_pais ORDER BY codpai ASC";
	$rs_data=$this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
		 $lb_valido=false; 
		 $this->io_msg->message("CLASE->SIGESP_RPC_C_MUNICIPIO; METODO->uf_llenarcombo_pais; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   }
	else
	   {
		 $li_numrows=$this->io_sql->num_rows($rs_data);	   
		 if ($li_numrows>0)
			{
			  $lb_valido=true;
			}
		 else
			{
			 $lb_valido = false; 
			}
	   }
  return $rs_data;         
}

function uf_load_estados($as_codpais)
{
//////////////////////////////////////////////////////////////////////////////
//	    Metodo:  uf_load_estados
//	    Access:  public
//	   Returns:	 $lb_valido.	
// Description:  Funcion que se encarga de extraer todos aquellos registro de la tabla sigesp_estado
//               para ser cargados en un objeto de tipo combobox/list menu. 
//////////////////////////////////////////////////////////////////////////////

		$ls_sql  = " SELECT * FROM sigesp_estados WHERE codpai='".$as_codpais."' ORDER BY desest ASC ";
		$rs_data = $this->io_sql->select($ls_sql);
	    if ($rs_data===false)
		   {
		     $lb_valido=false; 
		     $this->io_msg->message("CLASE->SIGESP_RPC_C_MUNICIPIO; METODO->uf_load_estados; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		   }
		else
		   {
		     $li_numrows=$this->io_sql->num_rows($rs_data);	   
             if ($li_numrows>0)
                {
	              $lb_valido=true;
                }
             else
                {
		         $lb_valido = false; 
				}
	       }
  return $rs_data;         
}

function uf_generar_codigo($ab_empresa,$as_codemp,$as_tabla,$as_columna,$as_columna2,$as_columna3)
{ 
//////////////////////////////////////////////////////////////////////////////
//	Function:  uf_generar_codigo
//	Access:  public
//	Arguments:
// ab_empresa   // Si usara el campo empresa como filtro      
// as_codemp    // codigo de la empresa
// as_tabla     // Nombre de la tabla 
// as_campo     // nombre del campo que desea incrementar
// ai_length    // longitud del campo
//	Returns:		ls_codigo   // representa el codigo incrementado o generado
//	Description:  Este método genera el numero consecutivo del código de
//                cualquier tabla deseada
//////////////////////////////////////////////////////////////////////////////
 	$lb_existe=$this->existe_tabla($as_tabla);
	if ($lb_existe)
	   {
	      $lb_existe=$this->existe_columna($as_tabla,$as_columna);
		  if ($lb_existe)
		  {
			   $li_longitud=$this->longitud_campo($as_tabla,$as_columna) ;
			   if ($ab_empresa)
			   {	
					  $ls_sql="SELECT ".$as_columna." FROM ".$as_tabla." WHERE CodEmp='".$as_codemp."' ORDER BY $as_columna DESC";		
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
					  $ls_sql=" SELECT ".$as_columna." FROM ".$as_tabla." WHERE codpai='".$as_columna2."' AND ".
					          " codest='".$as_columna3."' ".
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
				}
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
}

function longitud_campo($as_tabla,$as_columna)
{
//////////////////////////////////////////////////////////////////////////
//	     Function: longitud_campo
//		   Access: public 
//	  Description: determina la longitud de una columna tipo caracter
//	   Creado Por: Ing. Wilmer Briceño
//  Fecha Creación: 06/07/2006 							
//////////////////////////////////////////////////////////////////////////

   $li_length = 0;
   switch ($this->io_gestor)
   {
		case "MYSQL":
		   $ls_sql=" SELECT character_maximum_length AS width ".
				   " FROM information_schema.columns ".
				   " WHERE TABLE_SCHEMA='".$this->io_database."' AND UPPER(table_name)=UPPER('".$as_tabla."') AND ".
				   "       UPPER(column_name)=UPPER('".$as_columna."')";
		break;
		case "POSTGRE":
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
}

function existe_tabla($as_tabla)
{
////////////////////////////////////////////////////////////////////
//	     Function: existe_tabla
//		   Access: public 
//		Argumento: $as_tabla   // nombre de la tabla
//	  Description: deternima si existe una columna en una tabla
//	   Creado Por: Ing. Wilmer Briceño
//  Fecha Creación: 06/07/2006 							
//  Modificado Por: Ing. Luis Anibal Lang
//    Fecha Modif.: 27/10/2006
////////////////////////////////////////////////////////////////////   

   $lb_existe = false;
   switch ($this->io_gestor)
   {
		case "MYSQL":
		   $ls_sql= " SELECT * FROM ".
					" INFORMATION_SCHEMA.TABLES ".
					" WHERE TABLE_SCHEMA='".$this->io_database."' AND (UPPER(TABLE_NAME)=UPPER('".$as_tabla."'))";				
		break;
		case "POSTGRE":
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
}

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
		case "MYSQL":
		  $ls_sql = " SELECT COLUMN_NAME ".
					" FROM INFORMATION_SCHEMA.COLUMNS ".
					" WHERE TABLE_SCHEMA='".$this->io_database."' AND UPPER(TABLE_NAME)=UPPER('".$as_tabla."') AND UPPER(COLUMN_NAME)=UPPER('".$as_columna."')";
		break;
		case "POSTGRE":
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
}
}//Fin de la Clase...
?>