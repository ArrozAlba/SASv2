<?php
/* ********************************************************************************************************************************
          Compañia : SOFTBRI C.A.
    Nombre Archivo : sps_def_cartaanticipo_dao.php
   Tipo de Archivo : Archivo tipo DAO ( DATA ACCESS OBJECT )
             Autor : Ing. Maria Alejandra Roa 
	   Descripción : Esta clase maneja el acceso de dato de la tabla causa de retiro del sistema de presatciones sociales
    *********************************************************************************************************************************/

require_once("../../class_folder/utilidades/class_dao.php");
require_once("../../../shared/class_folder/sigesp_c_seguridad.php");
		

class sps_def_cartaanticipo_dao extends class_dao
{
  public function sps_def_cartaanticipo_dao() //constructor de la clase
  {
    $this->class_dao("sps_carta_anticipos");  
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
    $ls_codigo = $this->io_function_db->uf_generar_codigo(true,$this->ls_codemp,$this->as_tabla,"codcarant"); 
    return $ls_codigo;
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
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_upload
	//		   Access: public (sigesp_snorh_d_constanciatrabajo)
	//	    Arguments: as_nombre  // Nombre 
	//				   as_tipo  // Tipo 
	//				   as_tamano  // Tamaño 
	//				   as_nombretemporal  // Nombre Temporal
	//	      Returns: as_nombre sale vacio si da un error y con el mismo valor si se subio correctamente
	//	  Description: Funcion que sube un archivo al servidor
	//	   Creado Por: Ing. Yesenia Moreno
	// Fecha Creación: 12/06/2007 								Fecha Última Modificación : 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  function uf_upload($as_nombre,$as_tipo,$as_tamano,$as_nombretemporal)
  {  print "upload:  ".$as_nombre;
	if($as_nombre!="")
	{
		if (!((strpos($as_tipo, "word")||strpos($as_tipo, "rtf")) && ($as_tamano < 1000000))) 
		{ 
			$as_nombre="";
			$this->io_function_sb->message("El archivo no es válido, es muy grande o no es de Extención RTF.");
		}
		else
		{ 
			if (!((move_uploaded_file($as_nombretemporal, "documentos/original/".$as_nombre))))
			{
				$as_nombre="";
	        	$this->io_function_sb->message("MÉTODO->uf_upload ERROR-> No tiene Permiso para copiar en la carpeta Contacte con el administrador del sistema."); 
			}
			else
			{
				@chmod("documentos/original/".$as_nombre,0755);
			}
		}
	}
	return $as_nombre;	
  }
  //-----------------------------------------------------------------------------------------------------------------------------------
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
  	  $ls_directorio = $po_object->arcrtfcarant;
   	  $ls_arcrtfcarant=$HTTP_POST_FILES[$ls_directorio]['name'];
	  if(strlen($ls_arcrtfcarant)>50)
	  {
		 $this->io_function_sb->message("La Longitud del Nombre del Archivo es mayor a 50 caracteres."); 
		 $lb_valido=false;
	  } 
	  if($ls_arcrtfcarant!="")
	  {
		 $ls_tiparc=$HTTP_POST_FILES['$ls_directorio']['type']; 
		 $ls_tamarc=$HTTP_POST_FILES['$ls_directorio']['size']; 
		 $ls_nomtemarc=$HTTP_POST_FILES['$ls_directorio']['tmp_name'];
		 $ls_arcrtfcarant=uf_upload($ls_arcrtfcarant,$ls_tiparc,$ls_tamarc,$ls_nomtemarc);
	  }
  	
    $lb_guardo = false;
    $ld_marinfcarant = str_replace(".", "", $po_object->marinfcarant );
    $ld_marinfcarant = str_replace(",", ".", $ld_marinfcarant );
    
    $ld_marsupcarant = str_replace(".", "", $po_object->marsupcarant );
    $ld_marsupcarant = str_replace(",", ".", $ld_marsupcarant );
    
	if ($ps_operacion=="modificar")  
	{
        $ls_sql = " UPDATE ".$this->as_tabla." SET descarant='".$po_object->descarant."',concarant='".$po_object->concarant."',tamletcarant='".$po_object->tamletcarant."',intlincarant='".$po_object->intlincarant."',marsupcarant='".$ld_marsupcarant."',marinfcarant='".$ld_marinfcarant."',titcarant='".$po_object->titcarant."',piepagcarant='".$po_object->piepagcarant."',tamletpiepag='".$po_object->tamletpiepag."',arcrtfcarant='".$po_object->arcrtfcarant."'  WHERE codemp='".$this->ls_codemp."' AND codcarant='".$po_object->codcarant."'";
		$li_guardo = $this->io_sql->execute( $ls_sql );
		if ($li_guardo > 0)
	    {
	       $this->io_sql->commit();
	       $this->io_function_sb->message("Los datos fueron actualizados. ");
		   $lb_guardo=true;
		   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion =" Actualizó en la tabla ".$this->as_tabla." código: ".$po_object->codcarant;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->ls_codemp,"SPS",$ls_evento,$this->ls_logusr,"sps_def_cartaanticipo.html.php",$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
 	    }
	    else
	    {
		   $this->io_sql->rollback();
		   $this->io_function_sb->message("Los datos no fueron actualizados. ");
		   $lb_guardo=false;
 	    }				
	}	
	else
	{
		$ls_sql    = "INSERT INTO ".$this->as_tabla." (codemp, codcarant, descarant, concarant, tamletcarant, intlincarant, marinfcarant, marsupcarant, titcarant, piepagcarant, tamletpiepag, arcrtfcarant) VALUES ( '".$this->ls_codemp."', '".$po_object->codcarant."','".$po_object->descarant."','".$po_object->concarant."','".$po_object->tamletcarant."','".$po_object->intlincarant."','".$ld_marinfcarant."','".$ld_marsupcarant."','".$po_object->titcarant."','".$po_object->piepagcarant."','".$po_object->tamletpiepag."','".$po_object->arcrtfcarant."' ) ";
		$li_guardo = $this->io_sql->execute( $ls_sql );
		if ($li_guardo > 0)
		{
			$this->io_sql->commit();
			$this->io_function_sb->message("Los datos fueron registrados.");
			$lb_guardo=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion =" Insertó en la tabla ".$this->as_tabla." código: ".$po_object->codcarant;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->ls_codemp,"SPS",$ls_evento,$this->ls_logusr,"sps_def_cartaanticipo.html.php",$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		else
		{
			$this->io_sql->rollback();
			$this->io_function_sb->message("Los datos no pueden ser registrados.");
			$lb_guardo=false;
		}
	//	if ((!$lb_guardo) and ($this->io_sql->getNumError()=="1062")) //Importante el sistema es cliente servidor valida el codig q se almaceno primero, genera el error de clave duplicada entonces genera el sig codigo
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
   	$lb_borro      =  false;
    $ls_sql = "DELETE FROM ".$this->as_tabla." WHERE codcarant='".$as_codigo."'";
	$li_elimino=$this->io_sql->execute( $ls_sql );
	if ($li_elimino > 0)
	{
			$this->io_sql->commit();
			$this->io_function_sb->message("Los datos fueron eliminados.");
			$lb_borro = true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion =" Eliminó de la tabla ".$this->as_tabla." el código: ".$as_codigo;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->ls_codemp,"SPS",$ls_evento,$this->ls_logusr,"sps_def_cartaanticipo.html.php",$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
	}
	else
	{
			$this->io_sql->rollback();
			$this->io_function_sb->message("Los datos no pueden ser eliminados.");
	}
	return $lb_borro;
  }	
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getCartaAnticipo
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que retorna un arreglo de registros del DAO
  //    Arguments : $ps_codcarant -> Parametro que indica el codigo del modelo de carta de anticipo
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function getCartaAnticipo($ps_codcarant, &$pa_datos="")
  {
    $lb_valido = false;
 	$ls_sql    = "SELECT * FROM ".$this->as_tabla." WHERE codcarant='".$ps_codcarant."'";	
    $rs_data= $this->io_sql->select($ls_sql);
	
	if($rs_data==false)
	{
		$this->io_function_sb->message("Error en getCartaAnticipo. " );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $lb_valido=true;
		 $pa_data=$this->io_sql->obtener_datos($rs_data); 
		 $pa_datos=$this->io_function_sb->uf_sort_array($pa_data); 
	}
	else { $this->io_function_sb->message("Registro no encontrado. "); }
	
	return $lb_valido;
  }

  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getCartaAnticipo_personal
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que retorna un arreglo de registros del DAO
  //    Arguments : $ps_codper -> Pcodigo de personal
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function getCartaAnticipo_personal($ps_codper, &$pa_datos="")
  {
       $lb_valido = false;
 	   $ls_sql    = "SELECT p.cedper,p.nomper,p.apeper,pn.fecingper,c.descar,a.codnom,n.desnom,u.desuniadm,a.monant,a.fecantper,a.motant,a.mondeulab,a.monporant 
		               FROM sps_anticipos a,sno_personalnomina pn,sno_cargo c,sno_nomina n,sno_personal p,sno_unidadadmin u 
					   WHERE a.codemp=pn.codemp and a.codnom=pn.codnom and a.codper=pn.codper and pn.codemp=c.codemp and pn.codnom=c.codnom and pn.codcar=c.codcar and 
					         pn.codemp=n.codemp and pn.codnom=n.codnom and pn.codemp=p.codemp and pn.codper=p.codper and pn.codemp=u.codemp and 
							 pn.minorguniadm=u.minorguniadm and pn.ofiuniadm=u.ofiuniadm and pn.uniuniadm=u.uniuniadm and pn.depuniadm=u.depuniadm and pn.prouniadm=u.prouniadm and a.codper='".$ps_codper."'";
    	$rs_data   = $this->io_sql->select($ls_sql);			
	    if($rs_data==false)
	    {
		   $this->io_function_sb->message("Error en getReporteAnticipo ".$this->as_tabla );
	    }
	    elseif($row=$this->io_sql->fetch_row($rs_data))	
	    {
		   $lb_valido=true;
		   $pa_data =$this->io_sql->obtener_datos($rs_data); 
		   $pa_datos=$this->io_function_sb->uf_sort_array($pa_data); 
	    }
	    else { $this->io_function_sb->message("Registro no encontrado en la tabla "); }
		 
	 return $lb_valido; 		
  }
  
}
?>
