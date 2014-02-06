<?php
class sigesp_soc_c_modcla
{
var $ls_sql;
	
		function sigesp_soc_c_modcla($conn)
		{
		  require_once("../../shared/class_folder/sigesp_c_seguridad.php");	  
          require_once("../../shared/class_folder/class_funciones.php");		  
		  require_once("../../shared/class_folder/class_mensajes.php");
		  require_once("../../shared/class_folder/class_datastore.php");
		  $this->seguridad  = new sigesp_c_seguridad();		
		  $this->io_funcion = new class_funciones();
		  $this->io_sql     = new class_sql($conn);
		  $this->io_msg     = new class_mensajes();
		  $this->io_ds=new class_datastore();
		}
 
function uf_insert_modalidad($as_codemp,$as_codigo,$as_denominacion,$ar_grid,$ai_total,$aa_seguridad) 
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_insert_modalidad
//	          Access:  public
// 	       Arguments:  
//        $as_codemp:.
//        $as_codigo:.
//     $as_codtipser:.
//  $as_denominacion:.
//        $ad_precio:.
//     $as_spgcuenta:.
//   		$ar_grid:.
//         $ai_total:.
//     $aa_seguridad:.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de insertar una nueva modalidad en la tabla soc_modalidadclausulas. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:09/03/2006.	 
//////////////////////////////////////////////////////////////////////////////  

  $ls_sql = "INSERT INTO soc_modalidadclausulas (codemp, codtipmod, denmodcla) VALUES ('".$as_codemp."','".$as_codigo."','".$as_denominacion."')";
  $this->io_sql->begin_transaction();
  $rs_data=$this->io_sql->execute($ls_sql);
  if ($rs_data===false)
	 {
	   $lb_valido=false;
	   $this->io_msg->message("CLASE->SIGESP_SOC_C_MODCLA; METODO->uf_insert_modalidad; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
		if ($this->uf_insert_dt_modalidad($as_codemp,$as_codigo,$ar_grid,$ai_total,$aa_seguridad))
		   {
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               ////////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion =" Insertó en SOC la Modalidad ".$as_codigo." con denominación ".$as_denominacion;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               ////////////////////////////////	
		  }
	 }
return $lb_valido;
}

function uf_update_modalidad($as_codemp,$as_codigo,$as_denominacion,$ar_grid,$ai_total,$aa_seguridad) 
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_update_modalidad
//	          Access:  public
//	       Arguments: 
//        $as_codemp:
//        $as_codigo:
//  $as_denominacion:
//          $ar_grid:
//         $ai_total:
//     $aa_seguridad: 
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de actualizar los datos de una modalidad en la tabla soc_modalidadclausulas.  
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:09/03/2006.	 
//////////////////////////////////////////////////////////////////////////////  

  $lb_valido=false;
  $ls_sql=" UPDATE soc_modalidadclausulas ".
		  "    SET denmodcla='".$as_denominacion."'".
		  "  WHERE codemp='".$as_codemp."' AND codtipmod='".$as_codigo."'";
  $this->io_sql->begin_transaction();
  $rs_data=$this->io_sql->execute($ls_sql);
  if ($rs_data===false)
  	 {
	   $lb_valido=false;
	   $this->io_msg->message("CLASE->SIGESP_SOC_C_MODCLA; METODO->uf_update_modalidad; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
		if ($this->uf_delete_modxcla($as_codemp,$as_codigo,$aa_seguridad))
		   {                  
		   if ($this->uf_insert_dt_modalidad($as_codemp,$as_codigo,$ar_grid,$ai_total,$aa_seguridad))
		      {                        
			    $lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó en SOC la Modalidad ".$as_codigo;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												 $aa_seguridad["ventanas"],$ls_descripcion);
			   /////////////////////////////////         SEGURIDAD               /////////////////////////////
		     }
		}
	 }
return $lb_valido;
} 

function uf_delete_modalidad($as_codemp,$as_codigo,$aa_seguridad)
{          		 
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_delete_modalidad
//	          Access:  public
//	        Arguments 
//        $as_codemp:  Código de la Empresa.
//        $as_codigo:
//     $aa_seguridad:
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de eliminar una modalidad en la tabla soc_modalidadclausulas.  
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:09/03/2006.	 
//////////////////////////////////////////////////////////////////////////////  

   if ($this->uf_delete_modxcla($as_codemp,$as_codigo,$aa_seguridad))
      {
	    $ls_sql  = "DELETE FROM soc_modalidadclausulas WHERE codemp='".$as_codemp."' AND codtipmod='".$as_codigo."'";	      
	    $rs_data = $this->io_sql->execute($ls_sql);
		if ($rs_data===false)
		   {
				$lb_valido=false;
	            $this->io_msg->message("CLASE->SIGESP_SOC_C_MODCLA; METODO->uf_delete_modalidad; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		   }
		else
		   {
		     $lb_valido=true;
			 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
			 $ls_evento="DELETE";
			 $ls_descripcion ="Eliminó en SOC la Modalidad ".$as_codigo;
			 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											 $aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
	       }
 	  }
   return $lb_valido;
}

function uf_delete_modxcla($as_codemp,$as_codigo,$aa_seguridad)
{          		 
//////////////////////////////////////////////////////////////////////////////
//	          Metodo: uf_delete_modxcla
//	          Access:  public
//	       Arguments:  
//        $as_codemp: 
//        $as_codigo: 
//     $aa_seguridad:
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de eliminar las modalidades por clausulas en la tabla soc_dtm_clausulas.  
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:09/03/2006.	 
//////////////////////////////////////////////////////////////////////////////  

  $lb_valido = false;        
  $ls_sql    = "DELETE FROM soc_dtm_clausulas WHERE codemp='".$as_codemp."' AND codtipmod='".$as_codigo."'";	 
  $this->io_sql->begin_transaction();
  $rs_data   = $this->io_sql->execute($ls_sql);
  if ($rs_data===false)
	 {
       $lb_valido=false;
	   $this->io_msg->message("CLASE->SIGESP_SOC_C_MODCLA; METODO->uf_delete_modxcla; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		$ls_evento="DELETE";
		$ls_descripcion ="Eliminó las Clausulas asociados a la Modalidad ".$as_codigo;
		$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										$aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////*/            
	   $lb_valido=true;
	 } 		 
  return $lb_valido;
}

function uf_select_modalidad($as_codemp,$as_codigo) 
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_select_modalidad
// 	          Access:  public
//	       Arguments:  
//        $as_codemp:.
//        $as_codigo:.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de verificar si existe o no una modalidad, la funcion devuelve true si el
//                     registro es encontrado caso contrario devuelve false. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:09/03/2006.	 
//////////////////////////////////////////////////////////////////////////////  
  $lb_valido = false;
  $ls_sql  = "SELECT codtipmod FROM soc_modalidadclausulas WHERE codemp='".$as_codemp."' AND codtipmod='".$as_codigo."'";
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
	 {
	   $lb_valido=false;
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

function uf_insert_dt_modalidad($as_codemp,$as_codigo,$ar_grid,$ai_total,$aa_seguridad)
{
//////////////////////////////////////////////////////////////////////////////
//	         Funcion: uf_insert_dt_modalidad
//	          Access:  public
// 	       Arguments:  
//        $as_codemp:.
//        $as_codigo:.
//          $ar_grid:.
//         $ai_total:.
//     $aa_seguridad:.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de insertar detalles para una modalidad en la tabla soc_dtm_clausulas. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:09/03/2006.	 
//////////////////////////////////////////////////////////////////////////////

	 $lb_valido=true;
	 for($i=1;$i<=$ai_total;$i++)
	   {
		  if ($lb_valido)
		     {
			 $ls_codcla=$ar_grid["clausula"][$i];    
             if(!empty($ls_codcla))			            
			   {
				 $ls_sql=" INSERT INTO soc_dtm_clausulas (codemp, codtipmod, codcla) VALUES ('".$as_codemp."','".$as_codigo."','".$ls_codcla."')"; 
				 $rs_data=$this->io_sql->execute($ls_sql);              
				 if ($rs_data===false)
					{				 
					  $lb_valido=false;  
					  $this->io_msg->message('Error en Insertar Cargos Para el Servicio !!!');  			     	
					  $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
					  $this->io_sql->rollback();
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
	  } 
return $lb_valido;
}

function uf_saf_load_clausulas($ls_codemp,&$ai_totrows,&$object) 
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_saf_load_clausulas
// 	          Access:  public
//	       Arguments:  
//        $as_codemp:.
//        $as_codigo:.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de buscar las clausulas, la funcion devuelve true si el
//                     registro es encontrado caso contrario devuelve false. 
//     Elaborado Por:  
// Fecha de Creación:  15/09/08      Fecha Última Actualización: 
//////////////////////////////////////////////////////////////////////////////  
  $lb_valido = false;
  $ls_sql = " SELECT codcla,dencla ".
            " FROM   soc_clausulas ".
		    " ORDER  BY codcla ASC "; 
    $rs=$this->io_sql->select($ls_sql);	
	if($rs==false)
	{
		$this->io_msg->message($fun->uf_convertirmsg($io_sql->message));
	}
	else
	{
		$ai_totrows=0;
		while($row=$this->io_sql->fetch_row($rs))
		{
		    $lb_valido=true;
			$ai_totrows=$ai_totrows + 1;
			$ls_codcla=$row["codcla"];
			$ls_dencla=$row["dencla"]; 

			$object[$ai_totrows][1]="<div align=center><img src=../../shared/imagebank/tools15/aprobado.gif width=15 height=15  onClick='javascript: ue_agregar(".$ai_totrows.");'></div>";
			$object[$ai_totrows][2]="<input type=text name=txtcodcla".$ai_totrows." id=txtcodcla".$ai_totrows." value='".$ls_codcla."' class=sin-borde readonly style=text-align:center size=6 maxlength=15 >";		
			$object[$ai_totrows][3]="<input type=text name=txtdencla".$ai_totrows." id=txtdencla".$ai_totrows." value='".$ls_dencla."' class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
		}
		if ($ai_totrows==0)
		{
			$lb_valido=false;
			$object[$ai_totrows][1]="<input name=chkagregar".$ai_totrows." type=checkbox id=chkagregar".$ai_totrows." value=1 class=sin-borde >";
			$object[$ai_totrows][2]="<input type=text name=txtcodcla".$ai_totrows." id=txtcodcla".$ai_totrows." class=sin-borde readonly style=text-align:left size=6 maxlength=10 >";		
			$object[$ai_totrows][3]="<input type=text name=txtdencla".$ai_totrows." id=txtdencla".$ai_totrows." class=sin-borde readonly style=text-align:left   size=15 maxlength=254>";
		}
		
	}
  $this->io_sql->free_result($rs);
  return $lb_valido;
}

}//Fin de la Clase...
?> 