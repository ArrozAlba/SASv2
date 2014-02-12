<?php
class sigesp_rpc_c_estado
{
var $ls_sql;
var $is_msg_error;

	function sigesp_rpc_c_estado($conn)
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
 

function uf_insert_estado($as_codpais,$as_codest,$as_desest,$aa_seguridad) 
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_insert_estado
//	          Access:  public
//	        Arguments 
//       $as_codpais:  Código del Pais al cual pertenece el Estado.
//        $as_codest:  Código que se le asugnara al Estado.
//        $as_desest:  Descripción del Estado que se va a incluir.
//     $aa_seguridad:  Arreglo cargado con la información relacionada al
//                     nombre de la ventana,nombre del usuario etc.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de insertar en la tabla sigesp_estado
//                     un código y denominación para un nuevo estado.  
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  15/08/2006       Fecha Última Actualización:23/04/2006.	 
//////////////////////////////////////////////////////////////////////////////  
	  
	  $ls_sql = " INSERT INTO sigesp_estados (codpai,codest,desest) VALUES ('".$as_codpais."','".$as_codest."','".$as_desest."')";
	  $this->io_sql->begin_transaction();
	  $rs_data=$this->io_sql->execute($ls_sql);
	  if ($rs_data===false)
		 {
		   $lb_valido=false; 
		   $this->io_msg->message("CLASE->SIGESP_RPC_C_ESTADO; METODO->uf_insert_estado; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		 }
	  else
		 {
           $lb_valido=true;
		   /////////////////////////////////         SEGURIDAD               ////////////////////////
		   $ls_evento="INSERT";
		   $ls_descripcion ="Insertó en RPC el Estado ".$as_desest." con código ".$as_codest.
		                    " asociado al Pais ".$as_codpais;
		   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		   $aa_seguridad["ventanas"],$ls_descripcion);
		   /////////////////////////////////         SEGURIDAD               ////////////////////////
		 }
return $lb_valido;
}

function uf_update_estado($as_codpais,$as_codest,$as_desest,$aa_seguridad) 
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_update_estado
//	          Access:  public
//	        Arguments 
//       $as_codpais:  Código del Pais al cual pertenece el Estado.
//        $as_codest:  Código que se le asugnara al Estado.
//        $as_desest:  Descripción del Estado que se va a incluir.
//     $aa_seguridad:  Arreglo cargado con la información relacionada al
//                     nombre de la ventana,nombre del usuario etc.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de actualizar en la tabla sigesp_estado la denominación para un estado.  
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  15/08/2006       Fecha Última Actualización:23/04/2006.	 
//////////////////////////////////////////////////////////////////////////////  

	  $ls_sql=" UPDATE sigesp_estados SET desest='".$as_desest."' WHERE codpai='".$as_codpais."' AND codest='".$as_codest."'";
	  $this->io_sql->begin_transaction();
	  $rs_data=$this->io_sql->execute($ls_sql);
	  if ($rs_data===false)
		 {
		   $lb_valido=false; 
		   $this->io_msg->message("CLASE->SIGESP_RPC_C_ESTADO; METODO->uf_update_estado; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		 }
	  else
		 {
           $lb_valido=true;
		   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		   $ls_evento="UPDATE";
		   $ls_descripcion ="Actualizó en RPC el Estado ".$as_desest." con código ".$as_codest.
		                    " asociado al Pais ".$as_codpais;
		   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		   $aa_seguridad["ventanas"],$ls_descripcion);
		   /////////////////////////////////         SEGURIDAD               ///////////////////////////
		 }
return $lb_valido;
} 

function uf_delete_estado($as_codemp,$as_codpais,$as_codest,$aa_seguridad,$as_desest)
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_delete_estado
//	          Access:  public
//	        Arguments 
//       $as_codpais:  Código del Pais al cual pertenece el Estado.
//        $as_codest:  Código que se le asugnara al Estado.
//        $as_desest:  Descripción del Estado que se va a incluir.
//     $aa_seguridad:  Arreglo cargado con la información relacionada al
//                     nombre de la ventana,nombre del usuario etc.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de eliminar en la tabla sigesp_estado un estado.  
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  15/08/2006       Fecha Última Actualización:24/04/2006.	 
//////////////////////////////////////////////////////////////////////////////  

  $lb_valido = false;
  $ls_sql    = "DELETE FROM sigesp_estados WHERE codpai='".$as_codpais."' AND codest='".$as_codest."'";
  $this->io_sql->begin_transaction();
  $rs_data=$this->io_sql->execute($ls_sql);
  if ($rs_data===false)
	 {
	   $lb_valido=false; 
	   $this->io_msg->message("CLASE->SIGESP_RPC_C_ESTADO; METODO->uf_delete_estado; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
	   $lb_valido=true;
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	   $ls_evento="DELETE";
	   $ls_descripcion ="Eliminó en RPC el Estado con código ".$as_codest." asociado al Pais ".$as_codpais;
	   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	   $aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               ///////////////////////////
	 }	   		
  return $lb_valido;	  
}

function uf_load_estado($as_codpais,$as_codest) 
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_load_estado
//	          Access:  public
//	        Arguments 
//       $as_codpais:  Código del Pais al cual pertenece el Estado.
//        $as_codest:  Código que se le asugnara al Estado.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de eliminar en la tabla sigesp_estado un estado.  
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  15/08/2006       Fecha Última Actualización:24/04/2006.	 
//////////////////////////////////////////////////////////////////////////////  
  
  $lb_valido = false;
  $ls_sql    = " SELECT * FROM sigesp_estados WHERE codpai='".$as_codpais."' AND codest='".$as_codest."'";
  $rs_data   = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
	 {
 	   $lb_valido=false;
	   $this->io_msg->message("CLASE->SIGESP_RPC_C_ESTADO; METODO->uf_load_estado; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
	   $li_numrows=$this->io_sql->num_rows($rs_data);
	   if($li_numrows>0)
		 {
		   $lb_valido=true;
		 }
	 }
 $this->io_sql->free_result($rs_data);
return $lb_valido;
}
  
function uf_llenarcombo_pais()
{
//////////////////////////////////////////////////////////////////////////////
//	     Metodo:  uf_llenarcombo_pais
//	     Access:  public
//	  Arguments:  
//	Description:  Funcion que se encarga de seleccionar todos aquellos estados que pertenecen
//                un pais para ser cargado en un objeto de tipo combobox/list menu. 
//////////////////////////////////////////////////////////////////////////////

	$ls_sql=" SELECT * FROM sigesp_pais WHERE codpai<>'---' ORDER BY codpai ASC";
	$rs_data=$this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
	     $lb_valido=false;
	     $this->io_msg->message("CLASE->SIGESP_RPC_C_ESTADO; METODO->uf_llenarcombo_pais; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	          $lb_valido=false;
	        }
	    }			
return $rs_data;
}

function uf_generar_codigo($ab_empresa,$as_codemp,$as_tabla,$as_columna,$as_columna2)
{ 
//////////////////////////////////////////////////////////////////////////////
//	   Function:  uf_generar_codigo
//	     Access:  public
// 	  Arguments:
//  $ab_empresa:  Variable Booleana que indica si se usará el campo código empresa como filtro.      
//   $as_codemp:  Código de la Empresa.
//    $as_tabla:  Nombre de la Tabla. 
//    $as_campo:  Nombre del campo que desea incrementar.
//   $ai_length:  Longitud del campo.
//	    Returns:  $ls_codigo-> Representa el codigo incrementado o generado
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
					  $ls_sql="SELECT ".$as_columna." FROM ".$as_tabla." WHERE codemp='".$as_codemp."' ORDER BY $as_columna DESC";		
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
					  $ls_sql="SELECT ".$as_columna." FROM ".$as_tabla." WHERE codpai='".$as_columna2."' ORDER BY $as_columna DESC";		
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
	  $this->io_msg->message("CLASE->sigesp_rpc_c_estado METODO->uf_select_table()".$this->io_funcion->uf_convertirmsg($this->io_sql->message));			
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