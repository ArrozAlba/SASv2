<?php
/* ********************************************************************************************************************************
          Compañia : SOFTBRI C.A.
    Nombre Archivo : sps_causaretiro_dao.php
   Tipo de Archivo : Archivo tipo DAO ( DATA ACCESS OBJECT )
             Autor : Ing. Maria Alejandra Roa 
	   Descripción : Esta clase maneja el acceso de dato de la tabla causa de retiro del sistema de presatciones sociales
    *********************************************************************************************************************************/

require_once("../../class_folder/utilidades/class_dao.php");
require_once("../../class_folder/dao/sps_pro_liquidacion_dao.php");
require_once("../../../shared/class_folder/sigesp_c_seguridad.php");
		

class sps_causaretiro_dao extends class_dao
{
  private $io_liquidacion_dao;
  public function sps_causaretiro_dao()
  {
    $this->class_dao("sps_causaretiro");  //constructor de la clase
    $this->io_liquidacion_dao = new sps_pro_liquidacion_dao();
    $this->io_seguridad= new sigesp_c_seguridad();
    
    if(array_key_exists("la_empresa",$_SESSION))
	{
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$this->ls_logusr=$_SESSION["la_logusr"];
	}
  }

  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getProximoCodigo
  //      Alcance : Publico
  //         Tipo : String 
  //  Descripción : Función que devuelve el proximo codigo generado que representa el id del nuevo registro.
  //    Arguments :
  //      Retorna : Codigo generado en string 
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function getProximoCodigo()
  {
    $ls_codcauret = $this->io_function_db->uf_generar_codigo(false,"",$this->as_tabla,"codcauret"); 
    return $ls_codcauret;
  }
  
   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getData
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que retorna un arreglo de registros del DAO
  //    Arguments : $ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function getData($ps_orden="", &$pa_datos="")
  {
    	$lb_valido = false;
     	$ps_orden  = " ORDER BY codcauret, dencauret ASC";
 	$ls_sql    = "SELECT * FROM ".$this->as_tabla." ".$ps_orden;	
    	$rs_data= $this->io_sql->select($ls_sql);
	
	if($rs_data==false)
	{
		$this->io_function_sb->message("Error en getData de la tabla ".$this->as_tabla );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $lb_valido=true;
		 $pa_datos=$this->io_sql->obtener_datos($rs_data); 
	}
	else { $this->io_function_sb->message("Registro no encontrado en la tabla ".$this->as_tabla); }
	
	return $lb_valido;
  }
  
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : guardarData
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que guarda la información 
  //    Arguments : $ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
  public function guardarData( $po_object , $ps_operacion="insertar" )
  {	
    $lb_guardo = false;
	if ($ps_operacion=="modificar")
	{
        $ls_sql = " UPDATE ".$this->as_tabla." SET dencauret='".$po_object->dencauret."' WHERE codcauret='".$po_object->codcauret."'";
		$li_guardo = $this->io_sql->execute( $ls_sql );
		
	    if ($li_guardo > 0)
	    {
	       $this->io_sql->commit();
	       $this->io_function_sb->message("Los datos fueron actualizados. ");
		   $lb_guardo=true;
		   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion =" Actualizó en la tabla ".$this->as_tabla." código: ".$po_object->codcauret;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->ls_codemp,"SPS",$ls_evento,$this->ls_logusr,"sps_def_causaretiro.html.php",$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
 	    }
	    else
	    {
		   $this->io_sql->rollback();
		   $this->io_function_sb->message("Los datos no fueron actualizados. ".$this->io_sql->$message);
		   $lb_guardo=false;
 	    }				
	}	
	else
	{
		$ls_sql    = "INSERT INTO ".$this->as_tabla." (codcauret,dencauret) VALUES ( '" .$po_object->codcauret."', '".$po_object->dencauret."' ) ";
		$li_guardo = $this->io_sql->execute( $ls_sql );
		if ($li_guardo > 0)
		{
			$this->io_sql->commit();
			$this->io_function_sb->message("Los datos fueron registrados.");
			$lb_guardo=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion =" Insertó en la tabla ".$this->as_tabla." código: ".$po_object->codcauret;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->ls_codemp,"SPS",$ls_evento,$this->ls_logusr,"sps_def_causaretiro.html.php",$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		else
		{
			$this->io_sql->rollback();
			$this->io_function_sb->message("Los datos no pueden ser registrados.");
			$lb_guardo=false;
		}
		//if ((!$lb_guardo) and ($this->io_sql->getNumError()=="1062")) //Importante el sistema es cliente servidor valida el codig q se almaceno primero, genera el error de clave duplicada entonces genera el sig codigo
//		{
//			$ls_codigo = $this->getProximoCodigo();
//			$po_object->codcauret = $ls_codigo;
//			$lb_guardo =$this->guardarData($po_object);
//			$this->io_msg->message("Los datos fueron registrados con  $ls_codigo ");
//		}		
	} 
	return $lb_guardo;
  } 
  
   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : eliminarData
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que elimina un registro de dato
  //    Arguments : $pa_codigo -> representa el codigo clave primario de la tabla
  //      Retorna : Registro eliminado
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////    
  public function eliminarData( $as_codigo )
  {
    //Se chequea si el registro se puede eliminar ( Integridad relacional de la Tabla u Objeto asociado inmediato)
    $lb_eliminable = !($this->io_liquidacion_dao->getRelationLiq($as_codigo));   
	$lb_borro      =  false;

	if (!$lb_eliminable)
	{
	  $this->io_function_sb->message("No se puede eliminar este registro ya que esta relacionado con movimientos a la tabla de Liquidaciones.");
	}
	else
	{
	  $ls_sql = "DELETE FROM ".$this->as_tabla." WHERE codcauret='".$as_codigo."'";
	  $li_elimino=$this->io_sql->execute( $ls_sql );
	  if ($li_elimino > 0)
		{
			$this->io_sql->commit();
			$this->io_function_sb->message("Los datos fueron eliminados.");
			$lb_borro = true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion =" Eliminó de la tabla ".$this->as_tabla." el código: ".$as_codigo;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->ls_codemp,"SPS",$ls_evento,$this->ls_logusr,"sps_def_causaretiro.html.php",$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		else
		{
			$this->io_sql->rollback();
			$this->io_function_sb->message("Los datos no pueden ser eliminados.");
		}
	}
	return $lb_borro;
  }	
}
?>
