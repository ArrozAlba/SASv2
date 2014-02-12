<?php
 /* ********************************************************************************************************************************
          Compañia : SOFTBRI C.A.
    Nombre Archivo : class_dao.php
   Tipo de Archivo : Archivo tipo Clase
             Autor : Ing. Maria Roa de Briceño
    Fecha Creación : 09-06-2007   
	   Descripción : Esta clase instancia las clases necesaria para la conexión y manejo de datos y sirve de padre para todos los daos
   
    ********************************************************************************************************************************    */
        
	require_once("../../../shared/class_folder/sigesp_include.php");
	require_once("../../../shared/class_folder/class_sql.php");
	require_once("../../../shared/class_folder/class_mensajes.php");
	require_once("../../../shared/class_folder/class_funciones.php");
	require_once("../../../shared/class_folder/class_funciones_db.php");
	require_once("../../../shared/class_folder/class_datastore.php");
    require_once("../../../shared/class_folder/class_mensajes.php");
	require_once("../../../sps/class_folder/utilidades/class_function.php");

//	require_once("../../../shared/class_folder/sigesp_c_seguridad.php");

class class_dao
{
  // Atributos comunes en los DAO's
    protected $io_sql;
	protected $io_empresa; 
	protected $is_msg_error;
	protected $io_msg;
	protected $io_function;
	protected $io_function_db;
	protected $io_conexion; 
	protected $io_include;  
	protected $io_ds;
	protected $as_tabla;
  
  
  public function class_dao($ps_tabla)  
  { 
    //Instancias de objetos en los atributos comunes en los DAO's   
	$io_include          = new sigesp_include();
	$io_conexion         = $io_include->uf_conectar();
	$this->io_sql        = new class_sql($io_conexion);	
	$this->io_msg        = new class_mensajes();		
	$this->io_function   = new class_funciones();	
	$this->io_function_db= new class_funciones_db($io_conexion);
	$this->io_function_sb= new class_function();	                  //class solo de sps
	$this->io_ds         = new class_datastore();	
	$this->as_tabla      = $ps_tabla;

  }
  
  public function getTabla()
  {
  	return $this->as_tabla;
  }
  
  public function getMensaje()
  {
  	return $this->is_msg_error;
  } 
 
  
  function uf_destructor()
  {	
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_msg_error);		
		unset($this->io_function);		
		unset($this->io_function_db);
		unset($this->io_function_sb);
		unset($this->io_ds);
	

  }// end function uf_destructor
} //fin de la clase class_dao
?>