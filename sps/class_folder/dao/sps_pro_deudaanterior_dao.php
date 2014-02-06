<?php
/* ********************************************************************************************************************************
          Compañia : SOFTBRI C.A.
    Nombre Archivo : sps_pro_deudaanterior_dao.php
   Tipo de Archivo : Archivo tipo DAO ( DATA ACCESS OBJECT )
             Autor : Ing. Maria Alejandra Roa
	   Descripción : Esta clase maneja el acceso de dato de la tabla deuda anterior del sistema de presatciones sociales
    *********************************************************************************************************************************/

require_once("../../class_folder/utilidades/class_dao.php");
require_once("../../class_folder/dao/sps_pro_antiguedad_dao.php");
require_once("../../../shared/class_folder/sigesp_c_seguridad.php");

class sps_pro_deudaanterior_dao extends class_dao
{
  private $io_antiguedad_dao;
  
  public function sps_pro_deudaanterior_dao()  //constructor de la clase
  {
  	 $this->class_dao("sps_deuda_anterior");  
  	 $this->io_antiguedad_dao = new sps_pro_antiguedad_dao();
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
  public function getData($ps_orden="", &$pa_datos="")
  {
	$lb_valido = false;
 	$ls_sql    = "SELECT * FROM ".$this->as_tabla." ";	
    	$rs_data = $this->io_sql->select($ls_sql);
	
	if($rs_data==false)
	{
		$this->io_msg->message("Error en getData de la tabla ".$this->as_tabla );
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
  //     Function : getDeudaAnterior
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que retorna un arreglo de registros del DAO
  //    Arguments : $ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function getDeudaAnterior($ps_orden="", &$pa_datos="")
  {
    $lb_valido = false;
 	$ls_sql    = "SELECT d.*, p.nomper, p.apeper, n.desnom FROM sps_deuda_anterior d, sno_personal p, sno_nomina n  ".
	             "WHERE p.codemp=d.codemp and p.codper=d.codper and n.codemp=d.codemp and n.codnom=d.codnom ";
    $rs_data = $this->io_sql->select($ls_sql);
	
	if($rs_data==false)
	{
		$this->io_msg->message("Error en getData de la tabla ".$this->as_tabla );
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
  //     Function : getEstatus
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que retorna el valor del status de la deuda anterior
  //    Arguments : $ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function getStatus( $pa_codper,$pa_codnom,$pa_fecha)
  {
    $lb_valido = true;
 	$ls_sql    = "SELECT estdeuant FROM  ".$this->as_tabla." WHERE codemp='".$this->ls_codemp."' and codper='".$pa_codper."' and codnom='".$pa_codnom."' and feccordeuant='".$pa_fecha."' ";
   
    $rs_data = $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_function_sb->message("Error en getEstatus de la tabla ".$this->as_tabla );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $ls_estatus  =$row["estdeuant"]; 
		 if ( $ls_estatus=='P' )
		    { $lb_valido = false; }
		 else { $lb_valido = true; }
	}
		
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
	$lb_guardo    = false;  
	$ld_deuantant = str_replace(".", "", $po_object->deuantant);
	$ld_deuantant = str_replace(",", ".", $ld_deuantant);
	$ld_deuantint = str_replace(".", "", $po_object->deuantint);
	$ld_deuantint = str_replace(",", ".", $ld_deuantint);
	$ld_antpag    = str_replace(".", "", $po_object->antpag);
	$ld_antpag    = str_replace(",", ".", $ld_antpag);
	
	$ls_feccordeuant = $this->io_function->uf_convertirdatetobd($po_object->feccordeuant );
	
	if ($ps_operacion=="modificar")
	{
		$lb_valido = $this->getStatus( $po_object->codper,$po_object->codnom,$ls_feccordeuant);
		
		if ($lb_valido)		
		{
			$ls_sql = " UPDATE ".$this->as_tabla." SET deuantant='".$ld_deuantant."', deuantint='".$ld_deuantint."', antpag='".$ld_antpag."' WHERE codemp='".$this->ls_codemp."' AND codper='".$po_object->codper."' AND codnom='".$po_object->codnom."' AND feccordeuant='".$ls_feccordeuant."'";
			$li_guardo = $this->io_sql->execute( $ls_sql );
			
			if ($li_guardo > 0)
			{
			   $this->io_sql->commit();
			   $this->io_function_sb->message("Los datos fueron actualizados.");
			   $lb_guardo=true;
			   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
			   $ls_evento="UPDATE";
			   $ls_descripcion =" Actualizó en la tabla ".$this->as_tabla." codper=".$po_object->codper." codnom=".$po_object->codnom." feccordeu=".$ls_feccordeuant;
			   $lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->ls_codemp,"SPS",$ls_evento,$this->ls_logusr,"sps_pro_deudaanterior.html.php",$ls_descripcion);
			   /////////////////////////////////         SEGURIDAD               /////////////////////////////	
			}
			else
			{
			   $this->io_sql->rollback();
			   $this->io_function_sb->message("No pudo actualizar los datos.");
			   $lb_guardo=false;
			}				
		}	
		else
		{
			$this->io_function_sb->message("La deuda ha sido pagada, no se puede modificar.");
		}
	}	
	else
	{    
		$ls_estdeuant='E'; 
		$ls_sql    = "INSERT INTO ".$this->as_tabla." (codemp,codper,codnom,feccordeuant,deuantant,deuantint,antpag,estdeuant) VALUES ( '".$this->ls_codemp."','" .$po_object->codper."','".$po_object->codnom."','".$ls_feccordeuant."','".$ld_deuantant."','".$ld_deuantint."','".$ld_antpag."','".$ls_estdeuant."' ) ";
		$li_guardo = $this->io_sql->execute( $ls_sql );
		if ($li_guardo > 0)
		{
			$this->io_sql->commit();
			$this->io_function_sb->message("Los datos fueron registrados.");
			$lb_guardo=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion =" Insertó en la tabla ".$this->as_tabla." codper=".$po_object->codper." codnom=".$po_object->codnom." feccordeu=".$ls_feccordeuant;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->ls_codemp,"SPS",$ls_evento,$this->ls_logusr,"sps_pro_deudaanterior.html.php",$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		else
		{
			$this->io_sql->rollback();
			$this->io_function_sb->message("Los datos no pueden ser registrados.");
			$lb_guardo=false;
		}
	} 
		
	return $lb_guardo;
	
  }  //function guardarData
  
   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : eliminarData
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que elimina un registro de dato
  //    Arguments : $pa_codigo -> representa el codigo clave primario de la tabla
  //      Retorna : Registro eliminado
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////    
  public function eliminarData( $as_codper, $as_codnom, $adt_feccordeuant ) 
  {
    //Se chequea si el registro se puede eliminar ( Integridad relacional de la Tabla u Objeto asociado inmediato)
	$ldt_feccordeuant = $this->io_function->uf_convertirdatetobd($adt_feccordeuant);
        $lb_eliminable = $this->getStatus( $as_codper,$as_codnom,$ldt_feccordeuant);

	$lb_borro      =  false;
	
	if (!$lb_eliminable)
	{
	  $this->io_function_sb->message("No se puede eliminar este registro ya que esta relacionado con movimientos a la tabla de Liquidaciones.");
	}
	else
	{
	  $ls_sql = "DELETE FROM ".$this->as_tabla." WHERE codemp='".$this->ls_codemp."'and codper='".$as_codper."' and codnom='".$as_codnom."' and feccordeuant='".$ldt_feccordeuant."'";
	  $li_elimino=$this->io_sql->execute( $ls_sql );
	  if ($li_elimino > 0)
		{
			$this->io_sql->commit();
			$this->io_function_sb->message("Los datos fueron eliminados.");
			$lb_borro = true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion =" Eliminó en la tabla ".$this->as_tabla." codper=".$as_codper." codnom=".$as_codnom." feccordeu=".$ldt_feccordeuant;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->ls_codemp,"SPS",$ls_evento,$this->ls_logusr,"sps_pro_deudaanterior.html.php",$ls_descripcion);
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
