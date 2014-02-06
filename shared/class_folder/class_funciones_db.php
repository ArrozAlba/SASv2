<?php
  ////////////////////////////////////////////////////////////////////////////////////////////////////////
  //       Class : class_funciones_db
  // Description : Clase que posee funciones de manejo de configuracion interna de base de datos
  ////////////////////////////////////////////////////////////////////////////////////////////////////////
class class_funciones_db
{
    var $is_msg_error;
    var $io_database;
    function class_funciones_db($conn)//Constructor de la clase.
	{
	  require_once("class_funciones.php");
	  require_once("class_mensajes.php");
	  $this->io_sql       = new class_sql($conn);
	  $this->io_funcion   = new class_funciones(); 
	  $this->io_msg   = new class_mensajes(); 
	  $this->io_database  = $_SESSION["ls_database"];
	  $this->ls_gestor    = $_SESSION["ls_gestor"];
	} // end contructor

    function uf_longitud_columna_char($as_tabla,$as_columna)
    {
       /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   //	     Function: uf_longitud_columna_char
	   //		   Access: public 
	   //	  Description: determina la longitud de una columna tipo caracter
	   //	   Creado Por: Ing. Wilmer Briceño
	   //  Fecha Creación: 06/07/2006 							
	   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	   $li_length = 0;
	   switch ($this->ls_gestor)
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

	function uf_select_column($as_tabla,$as_columna)
	{
	   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   //	     Function: uf_select_column
	   //		   Access: public 
	   //		Argumento: $as_tabla   // nombre de la tabla
	   //				   $as_columna // nombre de la columna	
	   //	  Description: deternima si existe una columna en una tabla
	   //	   Creado Por: Ing. Wilmer Briceño
	   //  Fecha Creación: 06/07/2006 								
	   //  Modificado Por: Ing. Luis Anibal Lang
	   //    Fecha Modif.: 27/10/2006
	   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      $lb_existe = false;
	   switch ($this->ls_gestor)
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


	function uf_select_table($as_tabla)
	{
	   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   //	     Function: uf_select_table
	   //		   Access: public 
	   //		Argumento: $as_tabla   // nombre de la tabla
	   //	  Description: deternima si existe una columna en una tabla
	   //	   Creado Por: Ing. Wilmer Briceño
	   //  Fecha Creación: 06/07/2006 							
	   //  Modificado Por: Ing. Luis Anibal Lang
	   //    Fecha Modif.: 27/10/2006
	   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
       $lb_existe = false;
	   switch ($this->ls_gestor)
	   {
	   		case "MYSQLT":
			   $ls_sql= " SELECT * FROM ".
						" INFORMATION_SCHEMA.TABLES ".
						" WHERE TABLE_SCHEMA='".$this->io_database."' AND (UPPER(TABLE_NAME)=UPPER('".$as_tabla."'))";				
			break;
	   		case "POSTGRES":
			   $ls_sql= " SELECT * FROM ".
						" INFORMATION_SCHEMA.TABLES ".
						" WHERE table_catalog='".$this->io_database."' AND (UPPER(table_name)=UPPER('".$as_tabla."'))";				break;
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

    function uf_generar_codigo($ab_empresa,$as_codemp,$as_tabla,$as_columna)
	{ 
		//////////////////////////////////////////////////////////////////////////////////////////
		//	Function :  uf_generar_codigo
		//	  Access :  public
		//	Arguments:
		//           ab_empresa   // Si usara el campo empresa como filtro      
		//           as_codemp    // codigo de la empresa
		//           as_tabla     // Nombre de la tabla 
		//           as_campo     // nombre del campo que desea incrementar
		//           ai_length    // longitud del campo
		//	  Returns:	ls_codigo   // representa el codigo incrementado o generado
		//	Description:  Este método genera el numero consecutivo del código de
		//                cualquier tabla deseada
		///////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=$this->uf_select_table($as_tabla);
		if ($lb_existe)
		   {
			  $lb_existe=$this->uf_select_column($as_tabla,$as_columna);
			  if ($lb_existe)
			  {
				   $li_longitud=$this->uf_longitud_columna_char($as_tabla,$as_columna) ;
				   if ($ab_empresa)
				   {	
						  $ls_sql="SELECT ".$as_columna." FROM ".$as_tabla." WHERE codemp='".$as_codemp."' ORDER BY ".$as_columna." DESC LIMIT 1";	
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
						  $ls_sql="SELECT ".$as_columna." FROM ".$as_tabla." ORDER BY ".$as_columna." DESC LIMIT 1";		
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
	 } // end function
///--------------------------------------------------------------------------------------------------------------------------------
    function uf_select_constraint($as_tabla,$as_constrains)
	{ 
		//////////////////////////////////////////////////////////////////////////////////////////
		//	Function :  uf_select_constraint
		//	  Access :  public
		//	Arguments:  as_tabla   // Si usara el campo empresa como filtro      
		//              as_constrains    // codigo de la empresa	
		//	  Returns:	ls_existe   // representa si exite el constrains en la tabla dada
		//Description:  Este método genera el numero consecutivo del código de
		//                cualquier tabla deseada
		//  Creado Por: Ing. Jennifer Rivero
	    //  Fecha Creación: 27/05/2008 		
		///////////////////////////////////////////////////////////////////////////////////////////
		   $lb_existe = false;
		   switch ($this->ls_gestor)
		   {
				case "MYSQLT":
				   $ls_sql=" SELECT * FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS ".
                           "         WHERE TABLE_NAME='".$as_tabla."' ".
        				   "         AND CONSTRAINT_NAME='".$as_constrains."'".
       					   "         AND TABLE_SCHEMA='".$this->io_database."'";				   			
				break;
				case "POSTGRES":
				   $ls_sql= " SELECT * FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS  ".
                            "        WHERE TABLE_NAME='".$as_tabla."'".
        					"        AND CONSTRAINT_NAME='".$as_constrains."'".
      						"        AND TABLE_CATALOG='".$this->io_database."'";	   
				  
				break;				
		   }
		   $rs_data=$this->io_sql->select($ls_sql);
		   if($rs_data===false)
		   {   
			  $this->io_msg->message("ERROR en uf_select_constraint".$this->io_funcion->uf_convertirmsg($this->io_sql->message));			
			 return false; 
		   }
		   else
		   {
			  if ($row=$this->io_sql->fetch_row($rs_data)) 
			  { 
			     $lb_existe=true;
			  } 
			  $this->io_sql->free_result($rs_data);	 
		   }	  
		   return $lb_existe;
   }///fin  uf_select_constraint

//------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_type_columna($as_tabla,$as_columna,$type)
	{	   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   //	     Function: uf_select_type_columna
	   //		   Access: public 
	   //		Argumento: $as_tabla   // nombre de la tabla
	   //				   $as_columna // nombre de la columna	
	   //	  Description: deternima el tipo de datos de la columna en una tabla
	   //	   Creado Por: Ing. Jennifer rivero
	   //  Fecha Creación: 31/07/2008		   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      $lb_existe = false;
	   switch ($this->ls_gestor)
	   {
	   		case "MYSQLT":
			  $ls_sql = " SELECT DATA_TYPE                                    ".
						"   FROM INFORMATION_SCHEMA.COLUMNS                   ".
						"  WHERE TABLE_SCHEMA='".$this->io_database."'        ".
						"    AND UPPER(TABLE_NAME)=UPPER('".$as_tabla."')     ".
						"    AND UPPER(COLUMN_NAME)=UPPER('".$as_columna."')  ".
						"    AND UPPER(DATA_TYPE)=UPPER('".$type."')          ";
			break;
	   		case "POSTGRES":
			  $ls_sql = " SELECT DATA_TYPE                                   ".
						"   FROM INFORMATION_SCHEMA.COLUMNS                  ".
						"  WHERE table_catalog='".$this->io_database."'      ".
						"    AND UPPER(table_name)=UPPER('".$as_tabla."')    ".
						"    AND UPPER(column_name)=UPPER('".$as_columna."') ".
						"    AND UPPER(DATA_TYPE)=UPPER('".$type."')         "; 
			break;	   		
	   }
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  {   
         $this->io_msg->message("ERROR en uf_select_type_columna()".$this->io_funcion->uf_convertirmsg($this->io_sql->message));			
		 return false;
	  }
	  else
	  {
		  if ($row=$this->io_sql->fetch_row($rs_data))
		  { 
		  	$lb_existe=true; 
		  } 
  		  $this->io_sql->free_result($rs_data);	 
	  }	  
	  return $lb_existe;
	} // uf_select_type_columna
//------------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------------
   function uf_select_vista($as_vista)
	{
	   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   //	     Function: uf_select_vista
	   //		   Access: public 
	   //		Argumento: $as_vista  // nombre de la vista
	   //	  Description: deternima si existe una vista existe en la base de dtaos
	   //	   Creado Por: Ing. Jennifer Rivero
	   //  Fecha Creación: 17/08/2008							
	   	   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
       $lb_existe = false;
	   switch ($this->ls_gestor)
	   {
	   		case "MYSQLT":
			   $ls_sql= " SELECT table_name ".
			            "   FROM INFORMATION_SCHEMA.VIEWS ".						
						"  WHERE TABLE_SCHEMA='".$this->io_database."' ".  
						"    AND (UPPER(TABLE_NAME)=UPPER('".$as_vista."'))";				
			break;
	   		case "POSTGRES":
			   $ls_sql= " SELECT table_name ". 
			            "   FROM INFORMATION_SCHEMA.VIEWS ".
						"  WHERE table_catalog='".$this->io_database."'".
						"    AND table_schema='public'".
						"    AND (UPPER(TABLE_NAME)=UPPER('".$as_vista."'))";			            
			break;
	   		
	   }
	   $rs_data=$this->io_sql->select($ls_sql);//echo $ls_sql.'<br>';
	   if($rs_data===false)
	   {   
          $this->io_msg->message("ERROR en uf_select_vista".$this->io_funcion->uf_convertirmsg($this->io_sql->message));			
 		  return false; 
	   }
	   else
	   {
	 	  if ($row=$this->io_sql->fetch_row($rs_data)) 
		  { 
		  		$lb_existe=true; 
		  } 
   		  $this->io_sql->free_result($rs_data);	 
   	   }	  
	   return $lb_existe;
	} // end function uf_select_vista  
//------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------
    function uf_tamano_type_columna($as_tabla,$as_columna)
	{	   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   //	     Function: uf_tamano_type_columna
	   //		   Access: public 
	   //		Argumento: $as_tabla   // nombre de la tabla
	   //				   $as_columna // nombre de la columna	
	   //	  Description: deternima el tipo de datos de la columna en una tabla
	   //	   Creado Por: Ing. Jennifer rivero
	   //  Fecha Creación: 17/08/2008		   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      $lb_existe = false;
	  $tamano=0;
	   switch ($this->ls_gestor)
	   {
	   		case "POSTGRES":
			  $ls_sql =" SELECT a.attname as nomcolum,      ".
					   "		t.typname as type,          ".
					   "		CASE WHEN a.attlen='-1'     ".
					   "			 THEN (a.atttypmod - 4) ".
					   "			 ELSE a.attlen          ".
					   "		 END as tamano              ".
					   "  FROM pg_catalog.pg_attribute a    ".
					   "  LEFT JOIN pg_catalog.pg_type t ON t.oid = a.atttypid   ".
					   "  LEFT JOIN pg_catalog.pg_class c ON c.oid = a.attrelid  ".
					   "  LEFT JOIN pg_catalog.pg_constraint cc ON cc.conrelid = c.oid AND cc.conkey[1] = a.attnum ".
					   "  LEFT JOIN pg_catalog.pg_attrdef d ON d.adrelid = c.oid AND a.attnum = d.adnum            ".
					   "  WHERE c.relname ='".$as_tabla."'                                                         ".
					   "	AND a.attname= '".$as_columna."'                                                       ".
					   "	AND a.attnum > 0                                                                       ".
					   "	AND t.oid = a.atttypid                                                                 ";			  
				
			  $rs_data=$this->io_sql->select($ls_sql);
			  if($rs_data===false)
			  {   
				 $this->io_msg->message("ERROR en uf_tamano_type_columna()".
										$this->io_funcion->uf_convertirmsg($this->io_sql->message));			
				 return false;
			  }
			  else
			  {
				  if ($row=$this->io_sql->fetch_row($rs_data))
				  { 
					  $tamano=$row["tamano"];
				  } 
				  $this->io_sql->free_result($rs_data);	 
			  }	
			  break;     		
	   }	 
	  return $tamano;
	} // uf_select_type_columna
//---------------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------------
	function uf_tamano_type_columna_Mysql($as_tabla,$as_columna,&$as_valor1,&$as_valor2,&$as_valor3,&$as_valor4)
	{	   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   //	     Function: uf_tamano_type_columna_Mysql
	   //		   Access: public 
	   //		Argumento: $as_tabla   // nombre de la tabla
	   //				   $as_columna // nombre de la columna	
	   //	  Description: deternima el tipo de datos de la columna en una tabla
	   //	   Creado Por: Ing. Jennifer Rivero
	   //  Fecha Creación: 17/08/2008		   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      $lb_existe = false;
	  $tamano=0;
	   switch ($this->ls_gestor)
	   {
	   		case "MYSQLT":
			  $ls_sql ="  select T.* from INFORMATION_SCHEMA.columns T ".
					   "		where T.table_schema='".$this->io_database."' ".
					   "		and T.table_name='".$as_tabla."' ".
					   "		and T.column_name='".$as_columna."'";			  
				
			  $rs_data=$this->io_sql->select($ls_sql);
			  if($rs_data===false)
			  {   
				 $this->io_msg->message("ERROR en uf_tamano_type_columna_Mysql()".
										$this->io_funcion->uf_convertirmsg($this->io_sql->message));			
				 return false;
			  }
			  else
			  {
				  if ($row=$this->io_sql->fetch_row($rs_data))
				  { 
					   $lb_existe=true;
					   $as_valor1=$row["CHARACTER_MAXIMUM_LENGTH"];
					   $as_valor2=$row["CHARACTER_OCTET_LENGTH"];
					   $as_valor3=$row["NUMERIC_PRECISION"];
					   $as_valor4=$row["NUMERIC_SCALE"];
					   
				  } 
				  $this->io_sql->free_result($rs_data);	 
			  }	
			  break;     		
	   }	 
	  return $lb_existe;
	} // uf_select_type_columna
///---------------------------------------------------------------------------------------------------------------------------------------

function uf_select_index($as_tabla,$as_index)
{
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //	   Function: uf_select_index()
  //	     Access: public 
  //    Description: Función que ubica si el indice ha sido creado. 
  // Fecha Creacion: 26/02/2009								Fecha Ultima Modificacion : 26/02/2009. 
  ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  $lb_existe = false;
  switch($_SESSION["ls_gestor"]){
    case "MYSQLT":
 	  $ls_sql = "SELECT DISTINCT INDEX_NAME 
	               FROM information_schema.STATISTICS 
				  WHERE TABLE_SCHEMA = '".$_SESSION["ls_database"]."'
				    AND INDEX_SCHEMA = '".$_SESSION["ls_database"]."'
					AND TABLE_NAME = '".$as_tabla."'
					AND INDEX_NAME = '".$as_index."';";
	break;
    case "POSTGRES":
	  $ls_sql = "SELECT relname FROM pg_catalog.pg_class WHERE relname= '".$as_index."'";		
	break;	 			  
  }
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $this->io_msg->message("Error--> uf_select_index");  
	   $lb_existe = true;
	 }
  else
     {
	   if ($row=$this->io_sql->fetch_row($rs_data))
	      {
		    $lb_existe = true;
		  }
	 }
  return $lb_existe;
}
} // end class_funcrions_db 
?>