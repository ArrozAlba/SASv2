<?php
/*********************************************************************************************************************************
          Compañia : SOFTBRI C.A.
    Nombre Archivo : sps_pro_personal_dao.php
   Tipo de Archivo : Archivo tipo DAO ( DATA ACCESS OBJECT )
             Autor : Ing. Ma. Alejandra Roa  
	   Descripción : Esta clase maneja el acceso de datos de la tabla de personal
 *********************************************************************************************************************************/

require_once("../../../sps/class_folder/utilidades/class_dao.php");

class sps_pro_personal_dao extends class_dao
{	
  public function sps_pro_personal_dao() // Contructor de la clase
  {
    $this->class_dao("sno_personal");
  }
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getPersonal
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que retorna un arreglo de registros del DAO
  //    Arguments : $ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function getPersonal( $ps_orden="",&$pa_datos="",$ps_cedper="",$ps_nomper="",$ps_apeper="",$ps_codnom="" )  
  {  
  	$lb_valido = false;
  	if ($ps_codnom == "all") $ps_codnom="";
	$ps_orden = "ORDER BY p.codper,p.nomper,p.apeper,n.codnom,n.desnom ASC ";
  	$ls_sql    = "SELECT p.codper,p.nomper,p.apeper,n.codnom,n.desnom FROM sno_personal p,sno_personalnomina pn INNER JOIN sno_nomina n ON pn.codnom=n.codnom
				  WHERE  n.codemp=pn.codemp and n.codnom=pn.codnom and p.codemp=pn.codemp and p.codper=pn.codper and n.espnom='0' ";	
  	if ($ps_cedper != "")
 	  $ls_sql .= "AND p.cedper LIKE '%$ps_cedper%' ";
 	if ($ps_nomper != "")
 	  $ls_sql .= "AND p.nomper LIKE '%$ps_nomper%' ";
 	if ($ps_apeper != "")
 	  $ls_sql .= "AND p.apeper LIKE '%$ps_apeper%' ";
 	if ($ps_codnom != "")
 	  $ls_sql .= "AND pn.codnom LIKE '%$ps_codnom%' ";
 	$ls_sql .= $ps_orden; 

    $rs_data= $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_msg->message("Error en getPersonal de la tabla ".$this->as_tabla );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $lb_valido=true;
		 $pa_datos=$this->io_sql->obtener_datos($rs_data);
		 $pa_datos=$this->io_function_sb->uf_sort_array($pa_datos); 
	}
	else { $this->io_msg->message("Registro no encontrado en la tabla ".$this->as_tabla); }	
	return $lb_valido;
  }
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getNominas
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que retorna un arreglo de registros del DAO
  //    Arguments : $ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function getNominas( $ps_orden="", &$pa_datos="" )  
  {
  	$lb_valido = false;
	$ps_orden = "ORDER BY codnom,desnom ASC ";
 	$ls_sql    = "SELECT codnom,desnom FROM sno_nomina 
				  WHERE  espnom='0' ".$ps_orden;	
  	  	  
    $rs_data= $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_msg->message("Error en getNominas " );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $lb_valido=true;
		 $pa_datos=$this->io_sql->obtener_datos($rs_data); 
		 $pa_datos=$this->io_function_sb->uf_sort_array($pa_datos); 
	}
	else { $this->io_msg->message("Registro no encontrado en la tabla "); }	
	return $lb_valido;
  }
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //     Function : getPersonalliq
  //      Alcance : Publico
  //         Tipo : Object Data Record
  //  Descripción : Función que retorna un arreglo de registros del DAO
  //    Arguments : $ps_codper: codigo del personal
  //                $ps_nomper: nombre  del personal
  //                $ps_apeper: apellido  del personal 
  //                $ps_orden -> Parametro que indica el orden de las columnas asociado a la tabla
  //                $pa_datos -> Arreglo de datos    
  //      Retorna : Obtener los registros.
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function getPersonalliq($ps_orden="", &$pa_datos="",$ps_cedper="",$ps_nomper="",$ps_apeper="",$ps_codnom="") 
  {
  	$lb_valido= false;
	if ($ps_codnom == "all") $ps_codnom="";
	$ps_orden = "ORDER BY p.codper,p.nomper,p.apeper,n.codnom,n.desnom,pn.fecingper,pn.sueintper,a.denasicar, d.desded, tp.destipper ASC ";
  	$ls_sql    = "SELECT p.codper,p.nomper,p.apeper,n.codnom,n.desnom,pn.fecingper,pn.sueintper,a.denasicar, d.desded, tp.destipper FROM sno_personal p, sno_asignacioncargo a, sno_dedicacion d, sno_tipopersonal tp,sno_personalnomina pn INNER JOIN sno_nomina n ON pn.codnom=n.codnom
				  WHERE  n.codemp=pn.codemp and n.codnom=pn.codnom and p.codemp=pn.codemp and p.codper=pn.codper and n.espnom='0' and pn.codemp=tp.codemp and pn.codded=tp.codded and pn.codtipper=tp.codtipper and tp.codemp=d.codemp and tp.codded=d.codded and pn.codemp=a.codemp and pn.codnom=a.codnom and pn.codasicar=a.codasicar ";
  	if ($ps_cedper != "")
 	  $ls_sql .= "AND p.cedper LIKE '%$ps_cedper%' ";
 	if ($ps_nomper != "")
 	  $ls_sql .= "AND p.nomper LIKE '%$ps_nomper%' ";
 	if ($ps_apeper != "")
 	  $ls_sql .= "AND p.apeper LIKE '%$ps_apeper%' ";
 	if ($ps_codnom != "")
 	  $ls_sql .= "AND pn.codnom LIKE '%$ps_codnom%' ";
 	$ls_sql .= $ps_orden;	
    $rs_data= $this->io_sql->select($ls_sql);
	if($rs_data==false)
	{
		$this->io_msg->message("Error en getPersonalliq de la tabla ".$this->as_tabla );
	}
	elseif($row=$this->io_sql->fetch_row($rs_data))
	{
		 $lb_valido=true;
		 $pa_datos=$this->io_sql->obtener_datos($rs_data);
		 $pa_datos=$this->io_function_sb->uf_sort_array($pa_datos); 
	}
	else { $this->io_msg->message("Registro no encontrado en la tabla ".$this->as_tabla); }	
	return $lb_valido;
  }

} // fin de class sps_pro_personal_dao
?>
