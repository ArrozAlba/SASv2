<?php
class sigesp_c_check_relaciones
{
var $ls_sql;
var $is_msg_error;
var $ls_database;
var $ls_gestor;
	
function sigesp_c_check_relaciones($conn)
{
  require_once("sigesp_c_seguridad.php");	      
  require_once("class_funciones.php");		  
  require_once("class_mensajes.php");
  $this->io_funcion   = new class_funciones();
  $this->io_seguridad = new sigesp_c_seguridad();		  
  $this->io_sql       = new class_sql($conn);
  $this->io_msg       = new class_mensajes();
  $this->ls_database  = $_SESSION["ls_database"];
  $this->ls_gestor    = $_SESSION["ls_gestor"];
}

function uf_select_table_names($as_condicion,$as_tabla_maestro,&$lb_valido)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_select_table_names
//	          Access:  public
//	        Arguments 
//        $as_gestor:  Nombre del Gestor de Base de Datos.
//      $as_database:  Nombre de la Base de Datos de Donde Obtendremos el o los nombres de las Tablas que poseen el campo
//                     que viene proporcionado como parametro.
//     $as_condicion:  String que completa la sentencia sql, donde debe escribirse el campo de busqueda(Ejm: codemp='".$as_codemp."').
//        $lb_valido:  Variable booleana que devolverá si fueron encontradas o no Tablas con ese nombre de campo.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga encontrar el nombre de todas aquellas Tablas que posean el o los campos definidos
//                     por la variable $as_condicion dentro de su estructura, y luego ser vaciadas en un resulset, la función devuelve 
//                     $lb_valido=true si y solo si encuentra tablas con dicho(s) campo(s).
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  09/11/2006       Fecha Última Actualización:10/11/2006.	 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
  
  $lb_valido = false;  
  switch ($this->ls_gestor)
  {
		case "MYSQLT":
			 $ls_sql = " SELECT DISTINCT TABLE_NAME AS table_name,column_name FROM INFORMATION_SCHEMA.COLUMNS ".
					   "  WHERE TABLE_SCHEMA='".$this->ls_database."' ".$as_condicion." AND TABLE_NAME<>'".$as_tabla_maestro."'";
			  break;
		case "POSTGRES":
			 $ls_sql = " SELECT DISTINCT table_name,column_name FROM INFORMATION_SCHEMA.COLUMNS ".
					   "  WHERE table_catalog='".$this->ls_database."' ".$as_condicion." AND table_name<>'".$as_tabla_maestro."'";
			 break;
		case "INFORMIX":
		   $ls_sql= "SELECT systables.tabname AS table_name, syscolumns.colname AS column_name  FROM syscolumns, systables ".
					" WHERE syscolumns.tabid = systables.tabid ".
					" AND UPPER(systables.tabname)<>UPPER('".$as_tabla_maestro."') ".
					" ".$as_condicion." ";	
		break;
  }
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
	 {
	   $this->io_msg->is_msg_error="ERROR en uf_select_table_names()".$this->io_funcion->uf_convertirmsg($this->io_sql->message);	
	 }
  else
	 {
	   $li_numrows = $this->io_sql->num_rows($rs_data); 
	   if ($li_numrows>0)
	      {
		    $lb_valido = true;
		  }
	 }
return $rs_data;
}

function uf_check_relaciones($as_codemp,$as_condicion,$as_tabla_maestro,$as_value,$as_mensaje)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_check_relaciones
//	          Access:  public
//	       Arguments: 
//        $as_gestor:  Nombre del Gestor de Base de Datos.
//      $as_database:  Nombre de la Base de Datos de Donde Obtendremos el o los nombres de las Tablas que poseen el campo.
//        $as_codemp:  Código de la Empresa.
//     $as_condicion:  Cadena sql que completará la búsqueda del campo.
//         $as_valor:  Valor de búsqueda en la data contenida en la(s) Tabla(s).
//       $as_mensaje:  Mensaje que será presentado al usuario una vez terminada la búsqueda.
//           Returns:  $lb_valido.
//	     Description:  Función que se encarga de verificar si el campo posee relaciones asociadas a otras tablas para poder ser eliminado.
// Fecha de Creación:  09/11/2006       Fecha Última Actualización:10/11/2006.	 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
  
  $lb_valido = false;
  $lb_tiene  = false;
  $rs_data   = $this->uf_select_table_names($as_condicion,$as_tabla_maestro,$lb_valido);
  if ($lb_valido)
     {
        while ($row=$this->io_sql->fetch_row($rs_data))	 
	          {
				$ls_table_name  = $row["table_name"];
				$ls_column_name = $row["column_name"];
				switch ($this->ls_gestor)
				{
						case "MYSQLT":
							 $as_condicion2 = "AND table_name='$ls_table_name' AND column_name='codemp' ";
							  break;
						case "POSTGRES":
							 $as_condicion2 = "AND table_name='$ls_table_name' AND column_name='codemp' ";
							 break;
						case "INFORMIX":
						  	 $as_condicion2= "AND tabname='$ls_table_name' AND colname='codemp' ";	
						break;
				}
				$rs_codemp      = $this->uf_select_table_names($as_condicion2,$as_tabla_maestro,$lb_valido);//Verificamos que la tabla posea el código de la empresa.
				if ($row_codemp=$this->io_sql->fetch_row($rs_codemp))
				   {
					 $ls_sql   = "SELECT codemp FROM $ls_table_name WHERE codemp='".$as_codemp."' AND $ls_column_name ='".$as_value."'";//Buscamos la existencia del campo (como dato) dentro de la Tabla.
					 $rs_datos = $this->io_sql->select($ls_sql);
					 if ($rs_datos===false)
					    {
						  $this->is_msg_error="ERROR en uf_check_relaciones()".$this->io_funcion->uf_convertirmsg($this->io_sql->message);			
					    } 
				 	 else
					    {
						  if ($row_data=$this->io_sql->fetch_row($rs_datos))
							 { 
							   $lb_tiene = true;
							   if (!empty($as_mensaje))
							      {
								    $this->is_msg_error = $as_mensaje;
								  }
							   else
							      {
								    $this->is_msg_error="El registro no puede ser eliminado, posee registros asociados a otras tablas !!!";  
								  }
							   $this->io_sql->free_result($rs_datos);
							   break;
							 }
					    }
				   }
			  }
	 }
  return $lb_tiene;
}
}
?>