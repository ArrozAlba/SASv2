<?php
class sigesp_cfg_c_inicio_contadores
{
	var $ls_sql;
	var $is_msg_error;
//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_cfg_c_inicio_contadores($conn)
	{
	  require_once("../shared/class_folder/sigesp_c_seguridad.php");
	  require_once("../shared/class_folder/class_funciones.php");
	  require_once("../shared/class_folder/class_mensajes.php");
	  $this->seguridad  = new sigesp_c_seguridad();		           
	  $this->io_funcion = new class_funciones();		  
	  $this->io_sql     = new class_sql($conn);
	  $this->io_msg     = new class_mensajes();
	  $this->io_database  = $_SESSION["ls_database"];
	  $this->io_gestor    = $_SESSION["ls_gestor"];
	}
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_select_nro_control_id($as_codemp,$as_id,$as_codsis,$as_procede) 
{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	          Metodo:  uf_select_nro_control_id
	//	       Arguments:  $as_codemp  ----> codigo de la empresa
	//                     $as_id      ----> codigo del id
	//                     $as_codsis  ----> codigo del sistema
	//                     $as_procede ----> codigo del procede
	//	         Returns:  $lb_valido.
	//	     Description:  Función que se encarga de verificar si existe o no un codigo id, la funcion devuelve 
	//                     true en caso de encontrarlo, caso contrario devuelve false. 
	//     Elaborado Por:  Ing. Yozelin Barragan.
	// Fecha de Creación:  14/03/2007       Fecha Última Actualización:.	 
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
    $lb_valido = false;
    $ls_sql    = " SELECT * ".
                 " FROM   sigesp_ctrl_numero ".
                 " WHERE  codemp='".$as_codemp."'   AND  codsis='".$as_codsis."'  AND  ".
		 	     "        procede='".$as_procede."' AND  id='".$as_id."' ";
	$rs_data   = $this->io_sql->select($ls_sql);
    if ($rs_data===false)
    {
       $lb_valido=false;
 	   $this->io_msg->message("CLASE->sigesp_cfg_c_inicio_contadores; METODO->uf_select_nro_control_id; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
    }
    else
    {
	   $li_numrows=$this->io_sql->num_rows($rs_data);
	   if ($li_numrows>0)
	   {
			$lb_valido=true;
		    $this->io_sql->free_result($rs_data);
	   }
    } 
    return $lb_valido;
}
//----------------------------------------------------------------------------------------------------------------------------
function uf_update_contador($as_codemp,$as_id,$as_codsis,$as_procede,$ai_nro_inicial,$ai_nro_final,$as_prefijo,$aa_seguridad) 
{
	///////////////////////////////////////////////////////////////////////////////////////////////////
	//	          Metodo:  uf_update_contador
	//	       Arguments:  $as_codemp  ----> codigo de la empresa
	//                     $as_id      ----> codigo del id
	//                     $as_codsis  ----> codigo del sistema
	//                     $as_procede ----> codigo del procede
	//                     $ai_nro_inicial ----> nro inicial
	//                     $ai_nro_final   ----> nro final
	//                     $as_prefijo     ----> prefijo
	//	         Returns:  $lb_valido.
	//	     Description:  Función que se encarga de actualizar los registros, la funcion devuelve 
	//                     true en caso de encontrarlo, caso contrario devuelve false. 
	//     Elaborado Por:  Ing. Yozelin Barragan.
	// Fecha de Creación:  16/03/2007       Fecha Última Actualización:.	 
	//////////////////////////////////////////////////////////////////////////////////////////////////
   $ls_sql=" UPDATE sigesp_ctrl_numero ".
           " SET    prefijo='".$as_prefijo."', nro_inicial='".$ai_nro_inicial."', nro_final='".$ai_nro_final."' ".
           " WHERE  codemp='".$as_codemp."' AND codsis='".$as_codsis."' AND procede='".$as_procede."' AND id='".$as_id."' ";
   $this->io_sql->begin_transaction();
   $rs_data=$this->io_sql->execute($ls_sql);
   if ($rs_data===false)
   {
	   $lb_valido=false;
  	   $this->io_msg->message("CLASE->sigesp_cfg_c_inicio_contadores; METODO->uf_update_contador; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
   }
   else
   {
		$lb_valido=true;
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		$ls_evento="UPDATE";
		$ls_descripcion ="Actualizo el contador  ".$as_id." del sistema ".$as_codsis;
		$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										 $aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////
	}
    return $lb_valido;
} 
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_insert_contador($as_codemp,$as_id,$as_codsis,$as_procede,$ai_nro_inicial,$ai_nro_final,$as_prefijo,$aa_seguridad) 
{
	///////////////////////////////////////////////////////////////////////////////////////////////////
	//	          Metodo:  uf_insert_contador
	//	       Arguments:  $as_codemp  ----> codigo de la empresa
	//                     $as_id      ----> codigo del id
	//                     $as_codsis  ----> codigo del sistema
	//                     $as_procede ----> codigo del procede
	//                     $ai_nro_inicial ----> nro inicial
	//                     $ai_nro_final   ----> nro final
	//                     $as_prefijo     ----> prefijo
	//	         Returns:  $lb_valido.
	//	     Description:  Función que se encarga de insertar un registro, la funcion devuelve 
	//                     true en caso de encontrarlo, caso contrario devuelve false. 
	//     Elaborado Por:  Ing. Yozelin Barragan.
	// Fecha de Creación:  16/03/2007       Fecha Última Actualización:.	 
	//////////////////////////////////////////////////////////////////////////////////////////////////
	$ls_id="";
	$lb_existe=$this->uf_select_id($as_codemp,$ls_id,$as_codsis,$as_procede);
	if($lb_existe)
	{
	  /*if($ls_id!="0001") 
	  {   
   	    settype($ls_id,'int');
		$ls_id_anterior = $ls_id - 1;  
	  }
	  else  
	  {	
   	    settype($ls_id,'int');
	    $ls_id_anterior = $ls_id ; 
	  }*/
	 // settype($ls_id_anterior,'string');                        
	 //$ls_id_anterior=$this->io_funcion->uf_cerosizquierda($ls_id_anterior,4);
	  $li_estidact="0";
	  $lb_valido=$this->uf_update_id_actual($as_codemp,$ls_id,$as_codsis,$as_procede,$li_estidact,$aa_seguridad); 
	  if($lb_valido)
	  {
	     $lb_valido=$this->uf_guardar_contador($as_codemp,$as_id,$as_codsis,$as_procede,$ai_nro_inicial,$ai_nro_final,$as_prefijo,$aa_seguridad); 
	  }	
	}
	else
	{
	    $lb_valido=$this->uf_guardar_contador($as_codemp,$as_id,$as_codsis,$as_procede,$ai_nro_inicial,$ai_nro_final,$as_prefijo,$aa_seguridad); 
	}
    return $lb_valido;
}
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_select_id($as_codemp,&$as_id,$as_codsis,$as_procede) 
{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	          Metodo:  uf_select_id
	//	       Arguments:  $as_codemp  ----> codigo de la empresa
	//                     $as_id      ----> codigo del id
	//                     $as_codsis  ----> codigo del sistema
	//                     $as_procede ----> codigo del procede
	//	         Returns:  $lb_valido.
	//	     Description:  Función que se encarga de verificar si existe o no un codigo id, la funcion devuelve 
	//                     true en caso de encontrarlo, caso contrario devuelve false. 
	//     Elaborado Por:  Ing. Yozelin Barragan.
	// Fecha de Creación:  14/03/2007       Fecha Última Actualización:.	 
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
    $lb_valido = false;
    $ls_sql    = " SELECT * ".
                 " FROM   sigesp_ctrl_numero ".
                 " WHERE  codemp='".$as_codemp."'   AND  codsis='".$as_codsis."'  AND  ".
		 	     "        procede='".$as_procede."' ".
				 " ORDER BY id DESC ";
	$rs_data   = $this->io_sql->select($ls_sql);
    if ($rs_data===false)
    {
       $lb_valido=false;
 	   $this->io_msg->message("CLASE->sigesp_cfg_c_inicio_contadores; METODO->uf_select_id; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
    }
    else
    {
	   $li_numrows=$this->io_sql->num_rows($rs_data);
	   if ($row=$this->io_sql->fetch_row($rs_data))
	   {
			$as_id=$row["id"];
			$lb_valido=true;
		    $this->io_sql->free_result($rs_data);
	   }
    } 
    return $lb_valido;
}
//----------------------------------------------------------------------------------------------------------------------------
function uf_guardar_contador($as_codemp,$as_id,$as_codsis,$as_procede,$ai_nro_inicial,$ai_nro_final,$as_prefijo,$aa_seguridad) 
{
	///////////////////////////////////////////////////////////////////////////////////////////////////
	//	          Metodo:  uf_guardar_contador
	//	       Arguments:  $as_codemp  ----> codigo de la empresa
	//                     $as_id      ----> codigo del id
	//                     $as_codsis  ----> codigo del sistema
	//                     $as_procede ----> codigo del procede
	//                     $ai_nro_inicial ----> nro inicial
	//                     $ai_nro_final   ----> nro final
	//                     $as_prefijo     ----> prefijo
	//	         Returns:  $lb_valido.
	//	     Description:  Función que se encarga de insertar un registro, la funcion devuelve 
	//                     true en caso de encontrarlo, caso contrario devuelve false. 
	//     Elaborado Por:  Ing. Yozelin Barragan.
	// Fecha de Creación:  16/03/2007       Fecha Última Actualización:.	 
	//////////////////////////////////////////////////////////////////////////////////////////////////
	$li_maxlen=15;
	$li_nro_actual=1;
	$ls_estidact="1";
	$ls_sql = " INSERT INTO sigesp_ctrl_numero ".
			  " (codemp, codsis, procede, id, prefijo, nro_inicial, nro_final, maxlen, nro_actual, estact) ".
			  " VALUES ('".$as_codemp."','".$as_codsis."','".$as_procede."','".$as_id."','".$as_prefijo."','".$ai_nro_inicial."', ".
			  "         '".$ai_nro_final."','".$li_maxlen."','".$li_nro_actual."','".$ls_estidact."')";
	$this->io_sql->begin_transaction();
	$rs_data=$this->io_sql->execute($ls_sql);
	if ($rs_data===false)
	{
	   $lb_valido=false;
	   $this->io_msg->message("CLASE->sigesp_cfg_c_inicio_contadores; METODO->uf_guardar_contador; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	}
	else
	{
		$lb_valido=true;
		/////////////////////////////////         SEGURIDAD               ////////////////////////////////		
		$ls_evento="INSERT";
		$ls_descripcion ="Actualizo el contador  ".$as_id." del sistema ".$as_codsis;
		$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               ////////////////////////////////	
	}
    return $lb_valido;
}
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_update_id_actual($as_codemp,$as_id,$as_codsis,$as_procede,$ai_estidact,$aa_seguridad) 
{
	///////////////////////////////////////////////////////////////////////////////////////////////////
	//	          Metodo:  uf_update_id_actual
	//	       Arguments:  $as_codemp  ----> codigo de la empresa
	//                     $as_id      ----> codigo del id
	//                     $as_codsis  ----> codigo del sistema
	//                     $as_procede ----> codigo del procede
	//                     $ai_nro_inicial ----> nro inicial
	//                     $ai_nro_final   ----> nro final
	//                     $as_prefijo     ----> prefijo
	//	         Returns:  $lb_valido.
	//	     Description:  Función que se encarga de actualizar los registros, la funcion devuelve 
	//                     true en caso de encontrarlo, caso contrario devuelve false. 
	//     Elaborado Por:  Ing. Yozelin Barragan.
	// Fecha de Creación:  16/03/2007       Fecha Última Actualización:.	 
	//////////////////////////////////////////////////////////////////////////////////////////////////
    $ls_sql=" UPDATE sigesp_ctrl_numero ".
           " SET    estact='".$ai_estidact."' ".
           " WHERE  codemp='".$as_codemp."' AND codsis='".$as_codsis."' AND procede='".$as_procede."' AND id='".$as_id."' ";
   $this->io_sql->begin_transaction();
   $rs_data=$this->io_sql->execute($ls_sql);
   if ($rs_data===false)
   {
	   $lb_valido=false;
  	   $this->io_msg->message("CLASE->sigesp_cfg_c_inicio_contadores; METODO->uf_update_id_actual; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
   }
   else
   {
		$lb_valido=true;
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		$ls_evento="UPDATE";
		$ls_descripcion ="Actualizo el contador  ".$as_id." del sistema ".$as_codsis." estatus actual id".$ai_estidact;
		$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										 $aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////
	}
    return $lb_valido;
} 
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_select_denominacion_procede(&$as_despro,$as_procede) 
{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	          Metodo:  uf_select_denominacion_procede
	//	       Arguments:  $as_codemp  ----> codigo de la empresa
	//                     $as_id      ----> codigo del id
	//                     $as_codsis  ----> codigo del sistema
	//                     $as_procede ----> codigo del procede
	//	         Returns:  $lb_valido.
	//	     Description:  Función que se encarga de verificar si existe o no un codigo id, la funcion devuelve 
	//                     true en caso de encontrarlo, caso contrario devuelve false. 
	//     Elaborado Por:  Ing. Yozelin Barragan.
	// Fecha de Creación:  14/03/2007       Fecha Última Actualización:.	 
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
    $lb_valido = false;
	$ls_sql="SELECT * FROM sigesp_procedencias WHERE procede='".$as_procede."'";
	$rs_data   = $this->io_sql->select($ls_sql);
    if ($rs_data===false)
    {
       $lb_valido=false;
 	   $this->io_msg->message("CLASE->sigesp_cfg_c_inicio_contadores; METODO->uf_select_denominacion_procede; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
    }
    else
    {
	   $li_numrows=$this->io_sql->num_rows($rs_data);
	   if ($row=$this->io_sql->fetch_row($rs_data))
	   {
			$as_despro=$row["desproc"];
			$lb_valido=true;
		    $this->io_sql->free_result($rs_data);
	   }
    } 
    return $lb_valido;
}
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_select_denominacion_sistema(&$as_nomsis,$as_codsis) 
{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	          Metodo:  uf_select_denominacion_sistema
	//	       Arguments:  $as_codemp  ----> codigo de la empresa
	//                     $as_id      ----> codigo del id
	//                     $as_codsis  ----> codigo del sistema
	//                     $as_procede ----> codigo del procede
	//	         Returns:  $lb_valido.
	//	     Description:  Función que se encarga de verificar si existe o no un codigo id, la funcion devuelve 
	//                     true en caso de encontrarlo, caso contrario devuelve false. 
	//     Elaborado Por:  Ing. Yozelin Barragan.
	// Fecha de Creación:  14/03/2007       Fecha Última Actualización:.	 
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
    $lb_valido = false;
	$ls_sql="SELECT * FROM sss_sistemas WHERE codsis='".$as_codsis."'";
	$rs_data   = $this->io_sql->select($ls_sql);
    if ($rs_data===false)
    {
       $lb_valido=false;
 	   $this->io_msg->message("CLASE->sigesp_cfg_c_inicio_contadores; METODO->uf_select_denominacion_sistema; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
    }
    else
    {
	   $li_numrows=$this->io_sql->num_rows($rs_data);
	   if ($row=$this->io_sql->fetch_row($rs_data))
	   {
			$as_nomsis=$row["nomsis"];
			$lb_valido=true;
		    $this->io_sql->free_result($rs_data);
	   }
    } 
    return $lb_valido;
}
//----------------------------------------------------------------------------------------------------------------------------




























function uf_update_servicio($as_codemp,$as_codser,$as_codtipser,$as_denser,$ad_precio,$as_spgcuenta,$ar_grid,$ai_total,$aa_seguridad) 
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo: uf_update_servicio
//	          Access:  public
//		   Arguments:  
//        $as_codemp=.
//        $as_codser=.
//     $as_codtipser=.
//  	  $as_denser=.
//  	  $ad_precio=.
//     $as_spgcuenta=.
//  		$ar_grid=.
//  	   $ai_total=.
//     $aa_seguridad=.		
//	         Returns:  $lb_valido.
//	     Description:  Funcion que se encarga de actualizar los datos de un servicio en la tabla soc_servicios. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:09/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 

  $ad_precio=str_replace('.','',$ad_precio);
  $ad_precio=str_replace(',','.',$ad_precio);

  $ls_sql=" UPDATE soc_servicios ".
		  " SET codtipser='".$as_codtipser."',denser='".$as_denser."',preser='".$ad_precio."', ".
		  " spg_cuenta='".$as_spgcuenta."' ".
		  " WHERE codemp='".$as_codemp."' AND codser='".$as_codser."'";

  $this->io_sql->begin_transaction();
  $rs_data=$this->io_sql->execute($ls_sql);
  if ($rs_data===false)
	 {
	   $lb_valido=false;
  	   $this->io_msg->message("CLASE->SIGESP_SOC_C_SERVICIO; METODO->uf_update_servicio; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
		if ($this->uf_delete_cargosxservicio($as_codemp,$as_codser,$aa_seguridad))
		   {                  
		   if ($this->uf_insert_dtcargos($as_codemp,$as_codser,$ar_grid,$ai_total,$aa_seguridad))
		      {                        
			    $lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó en SOC el Servicio ".$as_codser;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												 $aa_seguridad["ventanas"],$ls_descripcion);
			   /////////////////////////////////         SEGURIDAD               /////////////////////////////
		     }
		}
	 }
return $lb_valido;
} 
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_delete_servicio($as_codemp,$as_codser,$as_denser,$aa_seguridad)
{          		 
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_delete_servicio
//	          Access:  public
//	       Arguments:
//        $as_codemp:.
//        $as_codser:.
//        $as_denser:.
//     $aa_seguridad:.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de eliminar un servicio en la tabla soc_servicios. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:09/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 
  
  if ($this->uf_delete_cargosxservicio($as_codemp,$as_codser,$aa_seguridad))
     {
	   $ls_sql  = " DELETE FROM soc_servicios WHERE codemp='".$as_codemp."' AND codser='".$as_codser."'";	           
	   $rs_data = $this->io_sql->execute($ls_sql);
	   if ($rs_data===false)
		  { 
		    $lb_valido=false;
		    $this->is_msg_error="CLASE->SIGESP_SOC_C_SERVICIO; METODO->uf_delete_servicio; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		  }
	   else
		  { 
		    $lb_valido=true;
		    /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		    $ls_evento="DELETE";
		    $ls_descripcion ="Eliminó en SOC el Servicio ".$as_codser. " con denominacion ".$as_denser;
		    $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										$aa_seguridad["ventanas"],$ls_descripcion);
		    /////////////////////////////////         SEGURIDAD               /////////////////////////////
	 	  }  		 
     }  
  return $lb_valido;
}
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_delete_cargosxservicio($as_codemp,$as_codser,$aa_seguridad)
{          		 
//////////////////////////////////////////////////////////////////////////////
//	          Metodo: uf_delete_cargosxservicio
//	          Access:  public
//	       Arguments:  
//        $as_codemp:.
//        $as_codser:.
//     $aa_seguridad:.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de eliminar los cargos asociados a un servicio en la tabla soc_serviciocargo. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:09/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 

  $lb_valido=false;        
  $ls_sql = " DELETE FROM soc_serviciocargo WHERE codemp='".$as_codemp."' AND codser='".$as_codser."'";	    
  $this->io_sql->begin_transaction();
  $rs_data=$this->io_sql->execute($ls_sql);
  if ($rs_data===false)
	 {
       $lb_valido=false;
	   $this->io_sql->rollback();
 	   $this->is_msg_error="CLASE->SIGESP_SOC_C_SERVICIO; METODO->uf_delete_cargosxservicio; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
	 }
  else
	 {
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		$ls_evento="DELETE";
		$ls_descripcion ="Eliminó los Cargos asociados al Servicio ".$as_codser;
		$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										$aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////*/            
	   $lb_valido=true;
	 } 		 
  return $lb_valido;
}
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_insert_dtcargos($as_codemp,$as_codigo,$ar_grid,$ai_total,$aa_seguridad)
{
//////////////////////////////////////////////////////////////////////////////
//	         Funcion:  uf_insert_dtcargos
//	          Access:  public
//	       Arguments:  
//        $as_codemp:.
//        $as_codigo:.
//          $ar_grid:.
//         $ai_total:.
//     $aa_seguridad:.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de insertar detalles de cargo para un servicio. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:09/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 

	 $lb_valido=true;
	 for($i=1;$i<=$ai_total;$i++)
	   {
		  if ($lb_valido)
		     {
			 $ls_codcar = $ar_grid["cargo"][$i];               
			 $ls_sql    = " INSERT INTO soc_serviciocargo (codemp, codcar, codser) VALUES ('".$as_codemp."','".$ls_codcar."','".$as_codigo."')";                                                       
			 $rs_data   = $this->io_sql->execute($ls_sql);              
			 if ($rs_data===false)
				{				 
				  $lb_valido=false;  
	              $this->io_msg->message("CLASE->SIGESP_SOC_C_SERVICIO; METODO->uf_insert_dtcargos; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				}
			else
				{				 
				  $lb_valido=true;  		                    
				  /////////////////////////////////         SEGURIDAD               /////////////////////////////		
				  $ls_evento      ="INSERT";
				  $ls_descripcion =" Insertó en SOC cargos asociados al Servicio ".$as_codigo;
				  $ls_variable    = $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
				  $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
				  $aa_seguridad["ventanas"],$ls_descripcion);
				  /////////////////////////////////         SEGURIDAD               ///////////////////////////// 
			  }  				
		  }
	  } 
return $lb_valido;
}
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_llenarcombo_tiposer()
{
	$ls_sql  = "SELECT * FROM soc_tiposervicio ORDER BY codtipser ASC";
	$rs_data = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
	     $lb_valido=false;
 	     $this->io_msg->message("CLASE->SIGESP_SOC_C_SERVICIO; METODO->uf_llenarcombo_tiposer; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   }
    else
	   {
	     $li_numrows=$this->io_sql->num_rows($rs_data);
	     if ($li_numrows>0)
		    {
		      $lb_valido=true;
		    }
	   } 
	return $rs_data;         
}
//-----------------------------------------------------------------------------------------------------------------------------------
}//Fin de la Clase...
?> 