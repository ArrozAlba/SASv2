<?php
class sigesp_soc_c_servicios
{
var $ls_sql;
var $is_msg_error;
	
	function sigesp_soc_c_servicios($conn)
	{
	  require_once("../../shared/class_folder/sigesp_c_seguridad.php");
	  require_once("../../shared/class_folder/class_funciones.php");
	  require_once("../../shared/class_folder/class_mensajes.php");
	  $this->seguridad  = new sigesp_c_seguridad();		           
	  $this->io_funcion = new class_funciones();		  
	  $this->io_sql     = new class_sql($conn);
	  $this->io_msg     = new class_mensajes();
	}

function uf_insert_servicio($as_codemp,$as_codigo,$as_codtipser,$as_denominacion,$ad_precio,$as_spgcuenta,$ar_grid,$ai_total,
							$as_codunimed,$aa_seguridad) 
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_insert_servicio
// 	          Access:  public
//	       Arguments:  
//        $as_codemp=.
//        $as_codigo=.
//     $as_codtipser=.
//  $as_denominacion=.
//        $ad_precio=.
//     $as_spgcuenta=.
//          $ar_grid=.
//         $ai_total=.
//     $aa_seguridad=.
// 	         Returns:		
//	     Description:  Funcion que carga los valores traidos en la carga de datos desde el $ar_datos y asigna el valor respectivo a cada 
//			           variable y a realiza una busqueda para decidir si el registro ya existe para actualizarlo "UPDATE"o si el registro no existe realizar un "INSERT". 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:09/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 
  $ad_precio=str_replace('.','',$ad_precio);
  $ad_precio=str_replace(',','.',$ad_precio);
  $ls_sql = " INSERT INTO soc_servicios (codemp, codser, codtipser, denser, preser, spg_cuenta, codunimed) ".
			" VALUES ('".$as_codemp."','".$as_codigo."','".$as_codtipser."','".$as_denominacion."',".
			"          ".$ad_precio.",'".$as_spgcuenta."','".$as_codunimed."')";
  $this->io_sql->begin_transaction();
  $rs_data=$this->io_sql->execute($ls_sql);
  if ($rs_data===false)
	 {
	   $lb_valido=false;
	   $this->io_msg->message("CLASE->SIGESP_SOC_C_SERVICIO; METODO->uf_insert_servicio; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
		if ($this->uf_insert_dtcargos($as_codemp,$as_codigo,$ar_grid,$ai_total,$aa_seguridad))
		   {
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               ////////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion =" Insertó en SOC el Servicio ".$as_codigo." con denominación ".$as_denominacion;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               ////////////////////////////////	
		  }
	 }
return $lb_valido;
}

function uf_update_servicio($as_codemp,$as_codser,$as_codtipser,$as_denser,$ad_precio,$as_spgcuenta,$ar_grid,$ai_total,$as_codunimed,$aa_seguridad) 
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
  $ls_sql="UPDATE soc_servicios ".
		  "   SET codtipser='".$as_codtipser."',".
		  "       denser='".$as_denser."',".
		  "       preser='".$ad_precio."', ".
		  "       spg_cuenta='".$as_spgcuenta."', ".
		  "       codunimed='".$as_codunimed."' ".
		  " WHERE codemp='".$as_codemp."'".
		  "   AND codser='".$as_codser."'";
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
	   $ls_sql= "DELETE FROM soc_servicios".
	   			" WHERE codemp='".$as_codemp."'".
				"   AND codser='".$as_codser."'";	           
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
  $ls_sql = " DELETE FROM soc_serviciocargo".
  			"  WHERE codemp='".$as_codemp."'".
			"    AND codser='".$as_codser."'";	    
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

function uf_select_servicio($as_codemp,$as_codigo) 
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_select_servicio
//	          Access:  public
//	       Arguments:  
//        $as_codemp:.
//        $as_codigo:.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de verificar si existe o no un servicio, la funcion devuelve 
//                     true en caso de encontrarlo, caso contrario devuelve false. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:09/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 
	$lb_valido= false;
	$ls_sql="SELECT * FROM soc_servicios".
			" WHERE codemp='".$as_codemp."'".
			"   AND codser='".$as_codigo."'";
	$rs_data= $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	{
		$lb_valido=false;
		$this->io_msg->message("CLASE->SIGESP_SOC_C_SERVICIO; METODO->uf_select_servicio; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
			 $ls_sql    = " INSERT INTO soc_serviciocargo (codemp, codcar, codser)".
			 			  " VALUES ('".$as_codemp."','".$ls_codcar."','".$as_codigo."')";                                                       
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
}//Fin de la Clase...
?> 