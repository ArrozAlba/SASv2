<?php
 /*********************************************************************************************************************************
          Compañia : SOFTBRI C.A.
    Nombre Archivo : sps_pro_sueldos_dao.php
   Tipo de Archivo : Archivo tipo DAO ( DATA ACCESS OBJECT )
             Autor : Ing. Ma. Alejandra Roa  
 	   Descripción : Esta clase maneja el acceso de dato de la tabla sueldos.
 *********************************************************************************************************************************/

require_once("../../../sps/class_folder/utilidades/class_dao.php");
require_once("../../../sps/class_folder/dao/sps_pro_antiguedad_dao.php");
require_once("../../../shared/class_folder/sigesp_c_seguridad.php");

class sps_pro_sueldos_dao extends class_dao
{	

  private $ao_antiguedad_dao;
  private $ao_redondeo;
  
  public function sps_pro_sueldos_dao() // Contructor de la clase
  {
    $this->class_dao("sps_sueldos");
	$this->ao_antiguedad_dao = new sps_pro_antiguedad_dao();
	$this->io_seguridad= new sigesp_c_seguridad();
	
	if(array_key_exists("la_empresa",$_SESSION))
	{
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$this->ls_logusr=$_SESSION["la_logusr"];
	}
  }
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : guardarSueldos
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que guarda la información 
  //    Arguments : $ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
  public function guardarSueldos($po_object, $ps_operacion="insertar" )
  {	
  	$lb_guardo   = true;
	$li_registro = 0;
	
	if ($ps_operacion=="modificar")
	{
		$lb_valido = $this->eliminarparaActualizar( $po_object->codper, $po_object->codnom); 
		if ($lb_valido)
		{    
			while (($li_registro<count($po_object->dt_sueldo))&&($lb_guardo))
			{
				$lb_guardo = $this->insertData($po_object->dt_sueldo[$li_registro]);
				$li_registro++;
			} //end del while
		}
	}
	else
	{
		while (($li_registro<count($po_object->dt_sueldo))&&($lb_guardo))
		{
			$lb_guardo = $this->insertData($po_object->dt_sueldo[$li_registro]);
			$li_registro++;
		} //end del while
	} //end del else
	if ($lb_guardo)
	{
	   $this->io_sql->commit();
	   $this->io_function_sb->message("Los datos fueron actualizados.");
	}
	else
	{
	   $this->io_sql->rollback();
	   $this->io_function_sb->message("No pudo actualizar los datos.");
	}	 
  } //end function guardarSueldos
  
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : insertData
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que guarda la información 
  //    Arguments : $ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
  public function insertData( $po_sueldos)
  {	
  	$lb_inserto = false;
	$this->io_sql->begin_transaction();
	
	$ls_fecincsue    = $this->io_function->uf_convertirdatetobd($po_sueldos->fecincsue );
	$ld_monsuebas    = str_replace(".", "", $po_sueldos->monsuebas );	
	$ld_monsuebas    = str_replace(",", ".", $ld_monsuebas );	
	$ld_monsueint    = str_replace(".", "", $po_sueldos->monsueint );	
	$ld_monsueint    = str_replace(",", ".", $ld_monsueint );
	$ld_monsuenordia = str_replace(".", "", $po_sueldos->monsuenordia );	
	$ld_monsuenordia = str_replace(",", ".", $ld_monsuenordia );
	
	$ls_sql = " INSERT INTO ".$this->as_tabla." (codemp,codper,codnom,fecincsue,monsuebas,monsueint,monsuenordia)
		    VALUES ('".$this->ls_codemp."','".$po_sueldos->codper."','".$po_sueldos->codnom."','".$ls_fecincsue."','".$ld_monsuebas."','".   		            $ld_monsueint."','".$ld_monsuenordia."' ) ";						
	$li_inserto = $this->io_sql->execute( $ls_sql );
	if ($li_inserto>0 )
	{ 
	 	$lb_inserto=true;
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		$ls_evento="INSERT";
		$ls_descripcion =" Insertó en la tabla ".$this->as_tabla." codper=".$po_sueldos->codper." codnom=".$po_sueldos->codnom." ";
		$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->ls_codemp,"SPS",$ls_evento,$this->ls_logusr,"sps_pro_sueldos.html.php",$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////	
	}
	else { $lb_inserto=false; }
    return $lb_inserto;
  }
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : updateData
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que actualiza la información 
  //    Arguments : $ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
  public function updateData( $po_sueldos, $as_stabol )
  {	
    $lb_actualizo = false;
    
	$this->io_sql->begin_transaction();
	
	$ls_fecincsue    = $this->io_function->uf_convertirdatetobd($po_sueldos->fecincsue );
	$ld_monsuebas    = str_replace(".", "", $po_sueldos->monsuebas );	
	$ld_monsuebas    = str_replace(",", ".", $ld_monsuebas );	
	$ld_monsueint    = str_replace(".", "", $po_sueldos->monsueint );	
	$ld_monsueint    = str_replace(",", ".", $ld_monsueint );
	$ld_monsuenordia = str_replace(".", "", $po_sueldos->monsuenordia );	
	$ld_monsuenordia = str_replace(",", ".", $ld_monsuenordia );


	$ls_sql = " UPDATE ".$this->as_tabla." SET monsuebas='".$ld_monsuebas."',monsueint='".$ld_monsueint."',monsuenordia='".$ld_monsuenordia."'
				WHERE codemp='".$this->ls_codemp."',codper='".$po_sueldos->codper."',codnom='".$po_sueldos->codnom."',fecincsue='".$ls_fecincsue."'";
	$li_actualizo = $this->io_sql->execute( $ls_sql );
    if ($li_actualizo>0 )
	{ 
		$lb_actualizo=true;
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	    $ls_evento="UPDATE";
	    $ls_descripcion =" Actualizó en la tabla ".$this->as_tabla." codper=".$po_sueldos->codper." codnom=".$po_sueldos->codnom." ";
	    $lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->ls_codemp,"SPS",$ls_evento,$this->ls_logusr,"sps_pro_sueldos.html.php",$ls_descripcion);
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
  //  Descripción : Función que elimina un registro de datos
  //    Arguments : $pa_codigo -> representa el codigo clave primario de la tabla
  //      Retorna : Registro eliminado
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////    
  public function eliminarData( $as_codper, $as_codnom)
  {
     $lb_borro =  false;
     $ls_sql   = "SELECT * FROM sps_antiguedad WHERE codemp='".$this->ls_codemp."' and codper='".$as_codper."' and codnom='".$as_codnom."'  ";	
     $rs_data= $this->io_sql->select($ls_sql);
	 if($row=$this->io_sql->fetch_row($rs_data))
	 {
		 $lb_valido=true;
	 }
	 else 
	 { 
		$lb_valido=false;
	 }
     if ($lb_valido)
	 {
	   $this->io_function_sb->message("No se puede eliminar este registro ya que esta relacionado con movimientos a la tabla de Liquidaciones.");
	 }
	 else
	 {
	    $ls_sql = "DELETE FROM ".$this->as_tabla." WHERE codemp='".$this->ls_codemp."' and codper='".$as_codper."' and codnom='".$as_codnom."' ";
	    $li_elimino=$this->io_sql->execute( $ls_sql );
	    if ($li_elimino > 0)
		{
			$this->io_sql->commit();
			$this->io_function_sb->message("Los datos fueron eliminados.");
			$lb_borro = true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		    $ls_evento="DELETE";
		    $ls_descripcion =" Eliminó en la tabla ".$this->as_tabla." codper=".$as_codper." codnom=".$as_codnom." ";
		    $lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->ls_codemp,"SPS",$ls_evento,$this->ls_logusr,"sps_pro_sueldos.html.php",$ls_descripcion);
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
  public function eliminarparaActualizar( $as_codper, $as_codnom)
  {
     $lb_borro =  false;
     $ls_sql = "DELETE FROM ".$this->as_tabla." WHERE codemp='".$this->ls_codemp."' and codper='".$as_codper."' and codnom='".$as_codnom."' ";
	 $li_elimino=$this->io_sql->execute( $ls_sql );
	 if ($li_elimino > 0)
	 {
		$this->io_sql->commit();
		$lb_borro = true;
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	    $ls_evento="DELETE";
	    $ls_descripcion =" Eliminó para actualizar en la tabla ".$this->as_tabla." codper=".$as_codper." codnom=".$as_codnom." ";
	    $lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->ls_codemp,"SPS",$ls_evento,$this->ls_logusr,"sps_pro_sueldos.html.php",$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////
	 }
	 else
	 { 	$this->io_sql->rollback(); }
	 return $lb_borro;
  }
  
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getSueldosNomina
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que retorna un arreglo de registros del DAO
  //    Arguments : $as_codper -> Parametro que indica el codigo de personal
  //                $as_codnom -> Parametro que indica el codigo de nomina
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function getSueldosNomina( $as_codper="",$as_codnom="", &$pa_nomina="" )  
  {   
  	$lb_valido = false;
        //if ($_SESSION["ls_gestor"]=="MYSQL")		  
        //{ 
	//    $ls_sql = " SELECT min(p.fecdesper) as fecincsue, pn.sueper as monsuebas,sum(sueintper) as monsueint,pn.sueproper/30 as monsuenordia".
	//              " FROM sno_hpersonalnomina pn INNER JOIN sno_hperiodo p ON pn.codemp=p.codemp and pn.codnom=p.codnom and pn.anocur=p.anocur //and      pn.codperi=p.codperi".
	 //             " WHERE pn.codemp='".$this->ls_codemp."' and pn.codnom='".$as_codnom."' and pn.codper='".$as_codper."' and ( pn.staper='1' OR pn.staper='2')".
	//              " GROUP BY YEAR(fecdesper),month(fecdesper)";
	//}
	//elseif ($_SESSION["ls_gestor"]=="POSTGRES")
	//{
		$ls_sql = " SELECT min(p.fecdesper) as fecincsue, pn.sueper as monsuebas,sum(sueintper) as monsueint,pn.sueproper/30 as monsuenordia".
	              " FROM sno_hpersonalnomina pn INNER JOIN sno_hperiodo p ON pn.codemp=p.codemp and pn.codnom=p.codnom and pn.anocur=p.anocur and pn.codperi=p.codperi".
	              " WHERE pn.codemp='".$this->ls_codemp."' and pn.codnom='".$as_codnom."' and pn.codper='".$as_codper."' and ( pn.staper='1' OR pn.staper='2')"." GROUP BY EXTRACT(YEAR FROM fecdesper), EXTRACT(MONTH FROM fecdesper), pn.sueper,pn.sueproper" ;	
	//}
        $rs_data= $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_function_sb->message("Error en getSueldosNomina de la tabla ".$this->as_tabla );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $lb_valido=true;
		 //$pa_nomina  =$this->io_sql->sql_getdata($rs_data);
		 $pa_nomina =$this->io_sql->obtener_datos($rs_data);
		 $pa_nomina  =$this->io_function_sb->uf_sort_array($pa_nomina); 
	}
	else { $this->io_function_sb->message("Registro no encontrado en la tabla ".$this->as_tabla); }	
	return $lb_valido;
  }

  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getSueldos
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que retorna un arreglo de registros del DAO
  //    Arguments : $ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function getSueldos( $ps_orden="", &$pa_datos="" )  
  {
  	$lb_valido = false;
 	$ls_sql    = "SELECT DISTINCT s.codper,p.nomper,p.apeper,s.codnom, n.desnom FROM sps_sueldos s, sno_personal p, sno_nomina n
				  WHERE  s.codemp=p.codemp and s.codper=p.codper and s.codemp=n.codemp and s.codnom=n.codnom";			  
    $rs_data= $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_function_sb->message("Error en getSueldos de la tabla ".$this->as_tabla );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $lb_valido=true;
		 $pa_datos =$this->io_sql->obtener_datos($rs_data);
	}
	else { $this->io_function_sb->message("Registro no encontrado en la tabla ".$this->as_tabla); }	
	return $lb_valido;
  }
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getDetallesSueldos
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que retorna un arreglo de registros del DAO
  //    Arguments : $as_codper -> Argumento que indica el codigo de personal
  //                $as_codnom -> Argumento que indica el codigo de nomina      
  //                $pa_datos  -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function getDetallesSueldos( $as_codper="",$as_codnom="", &$pa_datos="" )  
  {
  	$lb_valido = false;
 	$ls_sql    = "SELECT fecincsue,monsuebas,monsueint,monsuenordia FROM ".$this->as_tabla." WHERE codemp='".$this->ls_codemp."' and codper='".$as_codper."' and codnom='".$as_codnom."'";
    $rs_data= $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_function_sb->message("Error en getDetallesSueldos de la tabla ".$this->as_tabla );
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
  //     Function : getCabeceraReporteSueldos
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que retorna un arreglo de datos
  //    Arguments : $ps_orden -> Parametro que indica el orden de los datos
  //                $ps_codper1 -> Codigo del personal DESDE
  //                $ps_codper2 -> Codigo del personal HASTA
  //      Retorna : $lb_valido:Boolean y $pa_datos: arreglo de registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function getCabeceraReporteSueldos($ps_orden="",$ps_codper1,$ps_codper2,&$pa_datos="")
  {
    $lb_valido = false;
	$ls_sql    = "SELECT DISTINCT s.codper, p.nomper,p.apeper FROM  sps_sueldos s, sno_personal p WHERE s.codemp=p.codemp and s.codper=p.codper and s.codemp='".$this->ls_codemp."' and s.codper between '".$ps_codper1."' and '".$ps_codper2."' ".$ps_orden;	
	$rs_data= $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_msg->message("Error en getCabeceraReporteSueldos de la tabla ".$this->as_tabla );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $lb_valido=true;
		 $pa_data =$this->io_sql->obtener_datos($rs_data);
		 $pa_datos=$this->io_function_sb->uf_sort_array($pa_data); 
	}
	else                                   
	{ 
		$lb_valido=false;
	}
	return $lb_valido;
  }
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getDetalleSueldos
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que retorna un arreglo de datos
  //    Arguments : $ps_orden  -> Parametro que indica el orden de los datos
  //                $ps_codper -> Codigo del personal 
  //      Retorna : $lb_valido:Boolean y $pa_datos: arreglo de registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function getDetalleSueldos($ps_orden="",$ps_codper,&$pa_datos="")
  {  
    $lb_valido = false;
        $ps_orden = "ORDER BY fecincsue,monsuebas,monsueint,monsuenordia ASC ";	
	$ls_sql    = "SELECT fecincsue,monsuebas,monsueint,monsuenordia FROM  sps_sueldos WHERE codemp='".$this->ls_codemp."' and codper ='".$ps_codper."' ".$ps_orden;	
	$rs_data= $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_msg->message("Error en getDetalleSueldos de la tabla ".$this->as_tabla );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $lb_valido=true;
		 $pa_data =$this->io_sql->obtener_datos($rs_data);
		 $pa_datos=$this->io_function_sb->uf_sort_array($pa_data); 
	}
	else                                   
	{ 
		$lb_valido=false;
	}
	return $lb_valido;
  }
   //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
  //     Function : get_personal_sueldos
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que obtiene la información del personal que posee historico de sueldos en sps
  //    Arguments : $ps_codper -> codigo de personal,$ps_nomper:nombre personal,$ps_apeper:apellido personal
  //				$ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
   public function get_personal_sueldos($ps_codper,$ps_nomper,$ps_apeper,$ps_order,&$pa_datos="")
   {
  		 $lb_valido = false;
		 $ls_codper = $ps_codper."%";
		 $ls_nomper = $ps_nomper."%"; 
		 $ls_apeper = $ps_apeper."%";
		 $ps_order  = "ORDER BY a.codper, p.nomper, p.apeper ASC";
		 $ls_sql    = "SELECT DISTINCT a.codper, p.nomper, p.apeper  
		               FROM sps_sueldos a, sno_personal p
					   WHERE a.codemp=p.codemp and a.codper=p.codper and a.codemp='".$this->ls_codemp."' and p.codper like '".	$ls_codper."' and p.nomper like '".$ls_nomper."' and p.apeper like '".$ls_apeper."' ".$ps_order;
		 $rs_data   = $this->io_sql->select($ls_sql);			
		 if($rs_data==false)
		 {
			$this->io_function_sb->message("Error en get_personal_sueldos ".$this->as_tabla );
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
