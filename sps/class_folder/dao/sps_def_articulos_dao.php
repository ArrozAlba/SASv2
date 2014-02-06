<?php
 /*********************************************************************************************************************************
          Compañia : SOFTBRI C.A.
    Nombre Archivo : sps_def_articulos_dao.php
   Tipo de Archivo : Archivo tipo DAO ( DATA ACCESS OBJECT )
             Autor : Ing. Ma. Alejandra Roa  
	   Descripción : Esta clase maneja el acceso de dato de la tabla  articulos.
 *********************************************************************************************************************************/

require_once("../../../sps/class_folder/utilidades/class_dao.php");
require_once("../../../sps/class_folder/dao/sps_pro_liquidacion_dao.php");
require_once("../../../shared/class_folder/sigesp_c_seguridad.php");

class sps_def_articulos_dao extends class_dao
{	
  public function sps_def_articulos_dao() // Contructor de la clase
  {
    	$this->class_dao("sps_articulos");
	$this->ao_liquidacion_dao = new sps_pro_liquidacion_dao();
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
	return $this->io_function_db->uf_generar_codigo(false,"",$this->as_tabla,"id_art");
  }	
  
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getArticulos
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que retorna un arreglo de registros del DAO
  //    Arguments : $ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function getArticulos( $ps_orden="", &$pa_datos="" )  
  {
  	$lb_valido = false;
	$ps_orden = " ORDER BY id_art,numart,fecvig,conart ASC ";
 	$ls_sql    = "SELECT DISTINCT id_art,numart,fecvig,conart FROM ".$this->as_tabla." ".$ps_orden;			  

        $rs_data= $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_function_sb->message("Error en getArticulos de la tabla ".$this->as_tabla );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $lb_valido=true;
		 $pa_data =$this->io_sql->obtener_datos($rs_data);
		 $pa_datos=$this->io_function_sb->uf_sort_array($pa_data); 
		 
	}
	else { $this->io_function_sb->message("Registro no encontrado en la tabla ".$this->as_tabla); }	
	return $lb_valido;
  }
  
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getExistData
  //      Alcance : Publico
  //  Descripción : Función que retorna un true o false, dependiendo si existe el numero de articulo en la tabla.
  //    Arguments : $as_numart -> Parametro que indica el numero del articulo 
  //               
  //      Retorna : $lb_valido: variable boolean 
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function getExistData($as_id_art="",$as_numart="",$adt_fecvig="", &$pa_data="")
  {
	$lb_valido = true;
        $ld_fecha  = $this->io_function->uf_convertirdatetobd($adt_fecvig);
 	$ls_sql    = "SELECT DISTINCT id_art, numart,fecvig,conart FROM ".$this->as_tabla." WHERE id_art=".$as_id_art." and numart=".$as_numart." and fecvig='".$ld_fecha."' ORDER BY id_art, numart,fecvig,conart ASC "; 	 	
        $rs_data= $this->io_sql->select($ls_sql);	
	if($rs_data===false)
	{
	  $this->io_function_sb->message("Error en getExistData de la tabla ".$this->as_tabla );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
	  $pa_data=$this->io_sql->obtener_datos($rs_data); 
	  $lb_valido=true;
	}
	else 
	{ 
		$this->io_function_sb->message("Registro no encontrado en la tabla ");
		$lb_valido=false;
	}
	return $lb_valido;
  }
  
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getDetallesArticulos
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que retorna un arreglo de registros del DAO
  //    Arguments : $as_numart -> Argumento que indica el numero de articulo
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function getDetallesArticulos( $as_id_art="",$as_numart="",$adt_fecvig="", &$pa_datos="" )  
  {
  	$lb_valido = false;
        $ld_fecha  = $this->io_function->uf_convertirdatetobd($adt_fecvig);
 	$ls_sql    = "SELECT numlitart,operador,canmes,diasal,tiempo,condicion,estacu,diaacu FROM ".$this->as_tabla." WHERE id_art='".$as_id_art."' and numart='".$as_numart."' and fecvig='".$ld_fecha."' ORDER BY numlitart,operador,canmes,diasal,tiempo,condicion,estacu,diaacu DESC";
        $rs_data= $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_function_sb->message("Error en getDetallesArticulos de la tabla ".$this->as_tabla );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $lb_valido=true;
		 $pa_data=$this->io_sql->obtener_datos($rs_data); 
		 $pa_datos=$this->io_function_sb->uf_sort_array($pa_data);  
	}
	else { $this->io_function_sb->message("Registro no encontrado en la tabla ".$this->as_tabla); }	
	return $lb_valido;
  }
  
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : guardarArticulos
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que guarda la información 
  //    Arguments : $ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
  public function guardarArticulos( $po_object, $ps_operacion="insertar" )
  {	
  	$lb_guardo   = true;
	$li_registro = 0;
	$li_elimino  = 0;
	$this->io_sql->begin_transaction();
	if ($ps_operacion=="modificar")
	{
		$ls_fecvig = $this->io_function->uf_convertirdatetobd($po_object->fecvig );
		$ls_sql     = "DELETE FROM ".$this->as_tabla." WHERE id_art='".$po_object->id_art."' and numart='".$po_object->numart."' and fecvig='".$ls_fecvig."'";
	    $li_elimino = $this->io_sql->execute( $ls_sql );
	    if ($li_elimino>0)
		{  
			while (($li_registro<count($po_object->dt_art))&&($lb_guardo))
			{   
				$lb_guardo = $this->insertData($po_object->dt_art[$li_registro]);
				$li_registro++;
			} //end del while
		}
	}
	else
	{
		while (($li_registro<count($po_object->dt_art))&&($lb_guardo))
		{
			$lb_guardo = $this->insertData($po_object->dt_art[$li_registro]);
			$li_registro++;
		} //end del while
	} //end del else
	if ($lb_guardo)
	{
	   $this->io_sql->commit();
	   $this->io_function_sb->message("Los Datos fueron registrados.");
	}
	else
	{
	   $this->io_sql->rollback();
	   $this->io_function_sb->message("No pudo registrar los datos.");
	}
	
  } //end function guardarArticulos

  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : insertData
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que guarda la información 
  //    Arguments : $ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
  public function insertData( $po_articulo )
  {	
  	$lb_inserto = false;
	
    $this->io_sql->begin_transaction();
	$li_numcon =1;
	$ls_fecvig = $this->io_function->uf_convertirdatetobd($po_articulo->fecvig );
    $li_canmes = intval($po_articulo->canmes);
    $ld_diasal = str_replace(".", "", $po_articulo->diasal );	
	$ld_diasal = str_replace(",", ".", $ld_diasal );
	$ld_diaacu = str_replace(".", "", $po_articulo->diaacu );	
	$ld_diaacu = str_replace(",", ".", $ld_diaacu );

	if($po_articulo->condicion=="NONE"){$li_numcon=1;}else{$li_numcon++;}
		
	$ls_sql = " INSERT INTO ".$this->as_tabla." (id_art,numart,fecvig,numlitart,numcon,conart,operador,canmes,tiempo,diasal,condicion,estacu,diaacu ) VALUES ('".$po_articulo->id_art."','".$po_articulo->numart."','".$ls_fecvig."','".$po_articulo->numlitart."','".$li_numcon."','".$po_articulo->conart."','".$po_articulo->operador."','".$li_canmes."','".$po_articulo->tiempo."','".$ld_diasal."','".$po_articulo->condicion."','".$po_articulo->estacu."','".$ld_diaacu."' )"  ;
	$li_inserto = $this->io_sql->execute( $ls_sql );
		
		if ($li_inserto>0 )
		 { 
		 	$lb_inserto=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion =" Insertó en la tabla ".$this->as_tabla." código: ".$po_articulo->id_art;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->ls_codemp,"SPS",$ls_evento,$this->ls_logusr,"sps_def_articulos.html.php",$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		 }
		else { $lb_inserto=false; }
		
       /* if ((!$lb_inserto) and ($this->io_sql->getNumError()=="1062")) //Importante el sistema es cliente servidor valida el codig q se almaceno primero, genera el error de clave duplicada entonces genera el sig codigo
		{
			$ls_codigo = $this->getProximoCodigo();
			$po_object->id_art= $ls_codigo;
			$lb_inserto =$this->insertData($po_articulo);
			$this->io_msg->message("La información fue registrada con  $ls_codigo ");
		}	*/
        return $lb_inserto;
  }
  
  
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : updateData
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que guarda la información 
  //    Arguments : $ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
  public function updateData( $po_articulo )
  {	
  	  $lb_actualizo = false;	
  	  
	$this->io_sql->begin_transaction();
	$ls_fecvig = $this->io_function->uf_convertirdatetobd($po_articulo->fecvig );
    $ld_canmes = str_replace(".", "", $po_articulo->canmes );	
	$ld_canmes = str_replace(",", ".", $ld_canmes );	
	$ld_diasal = str_replace(".", "", $po_articulo->diasal );	
	$ld_diasal = str_replace(",", ".", $ld_diasal );
	$ld_diaacu = str_replace(".", "", $po_articulo->diaacu );	
	$ld_diaacu = str_replace(",", ".", $ld_diaacu );	
    	
	$ls_sql = " UPDATE ".$this->as_tabla." SET conart='".$po_articulo->conart."',operador='".$po_articulo->operador."',canmes='".$ld_canmes."',tiempo='".$po_articulo->tiempo."',diasal='".$ld_diasal."',condicion='".$po_articulo->condicion."',estacu='".$po_articulo->estacu."',diaacu='".$ld_diaacu.
	          "' WHERE id_art='".$po_articulo->id_art."'";
	$li_actualizo = $this->io_sql->execute( $ls_sql );
	if ($li_actualizo>0 )
	{ 
		$lb_actualizo=true;
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		$ls_evento="UPDATE";
		$ls_descripcion =" Actualizó en la tabla ".$this->as_tabla." código: ".$po_articulo->id_art;
		$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->ls_codemp,"SPS",$ls_evento,$this->ls_logusr,"sps_def_articulos.html.php",$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////	
	}
	else
	{ 
		$lb_actualizo=false;
	}			  		
	return $lb_actualizo;
  } //end of function updateData
 
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : eliminarData
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que elimina un registro de dato
  //    Arguments : $pa_codigo -> representa el codigo clave primario de la tabla
  //      Retorna : Registro eliminado
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////    
  public function eliminarData( $ps_id_art, $ps_numart, $ps_fecvig )
  {
      $lb_valido  =  false;  
      $this->io_sql->begin_transaction();
	  $ld_fecvig  = $this->io_function->uf_convertirdatetobd($ps_fecvig);
   	  $ls_sql     = "DELETE FROM ".$this->as_tabla." WHERE id_art='".$ps_id_art."' and numart='".$ps_numart."' and fecvig='".$ld_fecvig."'";
	
	  $lb_valido  = $this->io_sql->execute( $ls_sql );	
	  
	  if ($lb_valido)
	  {
		$this->io_sql->commit();
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		$ls_evento="DELETE";
		$ls_descripcion =" Eliminó en la tabla ".$this->as_tabla." código: ".$ps_id_art;
		$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->ls_codemp,"SPS",$ls_evento,$this->ls_logusr,"sps_def_articulos.html.php",$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////
		$this->io_function_sb->message("Los Datos fueron eliminados.");
      }
  	  else
	  {
		$this->io_sql->rollback();
		$this->io_function_sb->message("Los Datos no pueden ser eliminados.");
	  }
	 return $lb_valido;
  }	  
} 
?>
