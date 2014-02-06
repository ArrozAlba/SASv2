<?php
 /* ********************************************************************************************************************************
          Compañia : SOFTBRI C.A.
    Nombre Archivo : sps_def_tasainteres_dao.php
   Tipo de Archivo : Archivo tipo DAO ( DATA ACCESS OBJECT )
             Autor : Ing. Ma. Alejandra Roa  
    Fecha Creación : 21-05-2008 
	   Descripción : Esta clase maneja el acceso de dato de la tabla de tasa de interes por meses y años
    *********************************************************************************************************************************/

require_once("../../../sps/class_folder/utilidades/class_dao.php");
require_once("../../../shared/class_folder/sigesp_c_seguridad.php");

class sps_def_tasainteres_dao extends class_dao
{	
  public function sps_def_tasainteres_dao() // Contructor de la clase
  {
    $this->class_dao("sps_tasa_interes");
    $this->io_seguridad= new sigesp_c_seguridad();
	
	if(array_key_exists("la_empresa",$_SESSION))
	{
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$this->ls_logusr=$_SESSION["la_logusr"];
	}
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
  public function getData( $ps_orden="", $pi_anos ,&$pa_datos="" )  
  {
	$lb_valido = false;
	$ps_orden = "ORDER BY anotasint,mestasint,valtas,numgac ASC";
 	$ls_sql    = "SELECT * FROM ".$this->as_tabla." WHERE anotasint='".$pi_anos."' ".$ps_orden;
        $rs_data= $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_function_sb->message("Error en getData de la tabla ".$this->as_tabla );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $lb_valido=true;
		 $pa_datos=$this->io_sql->obtener_datos($rs_data);
		 $pa_datos=$this->io_function_sb->uf_sort_array($pa_datos); 
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
    
   	$ls_bcv    = str_replace(".", "", $po_object->bcv );		
	$ls_bcv    = str_replace(",", ".", $ls_bcv );			

	if ($ps_operacion=="modificar")
	{
		$ls_sql = " UPDATE ".$this->as_tabla." SET valtas=".$ls_bcv.", numgac=".$po_object->numgac." WHERE anotasint=".$po_object->ano." AND mestasint=".$po_object->mes;
		$li_guardo = $this->io_sql->execute( $ls_sql );
		
	    if ($li_guardo > 0)
	    {
		   $this->io_sql->commit();
		   $lb_guardo=true;
		   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion =" Actualizó en la tabla ".$this->as_tabla." año: ".$po_object->ano." mes: ".$po_object->mes;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->ls_codemp,"SPS",$ls_evento,$this->ls_logusr,"sps_def_tasainteres.html.php",$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
 	    }
	    else
	    {
		   $this->io_sql->rollback();
		   $this->io_function_sb->message($this->io_sql->message);
		   $lb_guardo=false;
 	    }				
	}	
	else
	{
		  $ls_sql    = "INSERT INTO ".$this->as_tabla." (anotasint,mestasint,valtas,numgac) VALUES ('".$po_object->ano."','".$po_object->mes."','".$ls_bcv."','".$po_object->numgac."')";
		  $li_guardo = $this->io_sql->execute( $ls_sql );		
		  if ($li_guardo > 0)
		  {
			$this->io_sql->commit();
			$lb_guardo=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion =" Insertó en la tabla ".$this->as_tabla." año: ".$po_object->ano." mes: ".$po_object->mes;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->ls_codemp,"SPS",$ls_evento,$this->ls_logusr,"sps_def_tasainteres.html.php",$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		  }
		  else
		  {
			$this->io_sql->rollback();
			$lb_guardo=false;
		  }				
	} 
	if ($lb_guardo) 
	{ 
		$this->io_function_sb->message("Los datos fueron registrados."); }	
	else 
	{ 
		$this->io_function_sb->message("La datos no fueron registrados."); }	
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
  public function eliminarData(  $as_mes, $as_ano )
  {
    //Se chequea si el registro se puede eliminar ( Integridad relacional de la Tabla u Objeto asociado inmediato)
    //$lb_eliminable = !($this->ao_vehiculo_dao->getVehiculo($as_codcol));
	$lb_eliminable =  true;
    $lb_borro      =  false;
	if (!$lb_eliminable)
	{
	  $this->io_function_sb->message("No se puede eliminar este registro ya que esta relacionado con registros de otra tabla.");
	}
	else
	{
	  $ls_sql = "DELETE FROM ".$this->as_tabla." WHERE anotasint='".$as_ano."' AND mestasint='".$as_mes."'";
	  $li_elimino=$this->io_sql->execute( $ls_sql );
	  if ($li_elimino > 0)
		{
			$this->io_sql->commit();
			$this->io_function_sb->message("Los datos fueron eliminados.");
			$lb_borro = true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion =" Eliminó en la tabla ".$this->as_tabla." año: ".$po_object->ano." mes: ".$po_object->mes;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->ls_codemp,"SPS",$ls_evento,$this->ls_logusr,"sps_def_tasainteres.html.php",$ls_descripcion);
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
    
} // fin de class 
?>
