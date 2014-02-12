<?php
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/sigesp_c_seguridad.php");
//-----------------------------------------------------------------------------------------------------------------------------------
class sigesp_snorh_c_incor_susp_egre_personal
{
	var $io_sql;
	var $io_msg;
	var $io_function;
	var $io_seguridad;
	var $io_include;
	var $ls_codemp;
	var $io_connect;
//-----------------------------------------------------------------------------------------------------------------------------------
function sigesp_snorh_c_incor_susp_egre_personal()
{
	$this->io_include=new sigesp_include();
	$io_connect=$this->io_include->uf_conectar();
	$this->io_sql=new class_sql($io_connect);
	$this->io_function=new class_funciones();
	$this->io_msg=new class_mensajes();
	$this->io_fecha= new class_fecha();		
	$this->io_seguridad= new sigesp_c_seguridad();
    $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
}
//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_llenarcombo_nomina(&$rs_data)
	{   //////////////////////////////////////////////////////////////////////////////
		//	      Function:  uf_llenarcombo_metodo                                   
		//	     Arguments:  $rs_data  ResulSet (referencia)                          
		//	       Returns:  True si es correcto o false es otro caso                  
		//	   Description:  Funcion que se usa  para llenar el  combo de la nomina  
	    //     Creado por :  Ing. Yozelin Barragán                                 
	    // Fecha Creación :  30/03/2006        Fecha última Modificacion :         
		/////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql= " SELECT codnom,desnom ".
		          " FROM sno_nomina ".
				  " WHERE codemp='".$this->ls_codemp."' order by  codnom ";
		 $rs_data=$this->io_sql->select($ls_sql);
		 if($rs_data===false)
		 {
		      $lb_valido=false;
		      $this->io_msg->message("CLASE->sigesp_snorh_incor_susp_egre_personal MÉTODO->uf_llenarcombo_nomina ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		 }
	return $lb_valido;
	}//fin
//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_grid_personal($as_estper,$as_codnom,&$rs_data)
	{/////////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_load_grid_personal
	//	     Arguments:  $as_estper  //  estatus del personal
	//                   $as_codnom  //	codigo de la nomina   
	//                   $rs_data  // ResulSet  (referencia)       
	//         Returns:	 $lb_valido  True si es correcto  False en caso contrario
	//	   Description:  Funcion que carga el grid con el personal de la nomina 
	//     Creado por :  Ing. Yozelin Barragán                                 
	// Fecha Creación :  30/03/2006        Fecha última Modificacion :         
	//////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true; 
	$ls_sql=" SELECT DISTINCT P.codper,P.cedper,P.apeper,P.nomper,P.estper ".
			" FROM   sno_personal P, sno_personalnomina PN ".
			" WHERE  P.estper='".$as_estper."' AND P.codemp=PN.codemp AND P.codemp='".$this->ls_codemp."' AND".
			"        P.codper=PN.codper AND PN.codnom='".$as_codnom."' ".
			" ORDER BY P.codper";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
		  $lb_valido=false;
		  $this->io_msg->message("CLASE->sigesp_snorh_incor_susp_egre_personal MÉTODO->uf_load_grid_personal ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
	}
	return  $lb_valido;
}//fin 
//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_grid_personalnomina($as_estper,$as_codnom,&$rs_data)
	{/////////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_load_grid_personalnomina
	//	     Arguments:  $as_estper  //  estatus del personal
	//                   $as_codnom  //	codigo de la nomina   
	//                   $rs_data  // ResulSet  (referencia)       
	//         Returns:	 $lb_valido  True si es correcto  False en caso contrario
	//	   Description:  Funcion que carga el grid con el personal de la nomina 
	//     Creado por :  Ing. Yozelin Barragán                                 
	// Fecha Creación :  31/03/2006        Fecha última Modificacion :         
	//////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true; 
	$ls_sql=" SELECT DISTINCT P.codper,P.cedper,P.apeper,P.nomper,PN.staper ".
			" FROM   sno_personal P, sno_personalnomina PN ".
			" WHERE  PN.staper='".$as_estper."' AND P.codemp=PN.codemp AND P.codemp='".$this->ls_codemp."' AND".
			"        P.codper=PN.codper AND PN.codnom='".$as_codnom."' ".
			" ORDER BY P.codper";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
		  $lb_valido=false;
		  $this->io_msg->message("CLASE->sigesp_snorh_incor_susp_egre_personal MÉTODO->uf_load_grid_personalnomina ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
	}
	return  $lb_valido;
}//fin 
//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_status_personal($as_estper,$adt_fecegrper,$as_codper,$as_obsegrper,$as_cauegrper,$aa_seguridad)
	{/////////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_update_status_personal
	//	     Arguments:  $as_estper  ---  estatus del personal
	//                   $adt_fecegrper --- fecha de egreso del personal     
	//                   $as_codper --- codigo del personal  
	//                   $as_obsegrper ---  observación de egreso del personal
	//                   $as_cauegrper  --- causa de egreso del personal
	//                   $aa_seguridad --- arreglo de seguridad   
	//         Returns:	 $lb_valido  True si es correcto  False en caso contrario
	//	   Description:  Funcion que actualiza el personal cuando de cambia el estatus del mismo 
	//     Creado por :  Ing. Yozelin Barragán                                 
	// Fecha Creación :  03/04/2006        Fecha última Modificacion :         
	//////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$ls_sql=" SELECT estper ".
	        " FROM   sno_personal ".
			" WHERE  codemp='".$this->ls_codemp."' AND codper='".$as_codper."' ";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
	    $lb_valido=false;
	    $this->io_msg->message("CLASE->sigesp_snorh_incor_susp_egre_personal MÉTODO->uf_update_status_personal ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
	}
	else
	{
	    if($row=$this->io_sql->fetch_row($rs_data))  
		{
          $ls_estper_actual=$row["estper"];  		
		}
	}
	if($lb_valido)
	{
		if($ls_estper_actual==$as_estper)
		{
		  $this->io_msg->message(" El status de la persona es identico no hubo cambio...");
		  $lb_valido=false;
		}
		if($lb_valido)
		{
			if($as_estper=='3')
			{
				  $ls_sql=" UPDATE sno_personal ".
						  " SET    fecegrper='".$adt_fecegrper."', ".
						  "        estper='".$as_estper."', ".
						  "        obsegrper='".$as_obsegrper."', ".
						  "        cauegrper='".$as_cauegrper."' ".
						  " WHERE  codemp='".$this->ls_codemp."' AND codper='".$as_codper."' AND estper='".$ls_estper_actual."' ";
			}
			else
			{
				  $ldt_fecingper=$adt_fecegrper;
				  $ls_sql=" UPDATE sno_personal ".
						  " SET    estper='".$as_estper."', ".
						  "        fecingper='".$ldt_fecingper."', ".
						  "        obsegrper='".$as_obsegrper."', ".
						  "        cauegrper='".$as_cauegrper."' ".
						  " WHERE  codemp='".$this->ls_codemp."' AND codper='".$as_codper."' AND estper='".$ls_estper_actual."' ";
			}
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_msg->message("CLASE->sigesp_snorh_incor_susp_egre_personal MÉTODO->uf_update_status_personal ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			}
			else
			{
			   $lb_valido=true;
			}
			if($lb_valido)
			{
				  $ls_sql=" UPDATE sno_personalnomina ".
						  " SET    staper='".$as_estper."' ".
						  " WHERE  codemp='".$this->ls_codemp."' AND codper='".$as_codper."' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_msg->message("CLASE->sigesp_snorh_incor_susp_egre_personal MÉTODO->uf_update_status_personal ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			}
			else
			{
			   $lb_valido=true;
			}
		 }
	  }
   }
   if($lb_valido)
   {
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion =" Se actualizo el status del Personal ".$as_codper." status".$as_estper;
			$ls_variable= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											   $aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		$this->io_sql->commit();
   }
   else
   {
        $this->io_sql->rollback();

   }
   return $lb_valido;
}
//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_update_status_personalnomina($as_codnom,$as_staper,$adt_fecegrper,$as_codper,$aa_seguridad)
	{/////////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_update_status_personalnomina
	//	     Arguments:  $as_codnom  ---  codigo de la nomina 
	//                   $as_staper  ---  estatus del personal
	//                   $adt_fecegrper --- fecha de egreso del personal     
	//                   $as_codper --- codigo del personal  
	//                   $aa_seguridad --- arreglo de seguridad   
	//         Returns:	 $lb_valido  True si es correcto  False en caso contrario
	//	   Description:  Funcion que actualiza el personal cuando de cambia el estatus del mismo 
	//     Creado por :  Ing. Yozelin Barragán                                 
	// Fecha Creación :  03/04/2006        Fecha última Modificacion :         
	//////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$ls_sql=" SELECT staper ".
	        " FROM   sno_personalnomina ".
			" WHERE  codemp='".$this->ls_codemp."' AND codper='".$as_codper."' ";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
	    $lb_valido=false;
	    $this->io_msg->message("CLASE->sigesp_snorh_incor_susp_egre_personal MÉTODO->uf_update_status_personalnomina ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
	}
	else
	{
	    if($row=$this->io_sql->fetch_row($rs_data))  
		{
          $ls_staper_actual=$row["staper"];  		
		}
	}
	if($lb_valido)
	{
		if($as_staper=='1')
		{
			  $ls_sql=" UPDATE sno_personalnomina ".
					  " SET    staper='".$as_staper."', ".
					  "        fecingper='".$adt_fecegrper."' ".
					  " WHERE  codemp='".$this->ls_codemp."' AND codnom='".$as_codnom."'  AND codper='".$as_codper."' AND ".
					  "        staper='".$ls_staper_actual."' ";
		}
		elseif($as_staper=='4')
		{
			  $ls_sql=" UPDATE sno_personalnomina ".
					  " SET    staper='".$as_staper."', ".
					  "        fecsusper='".$adt_fecegrper."' ".
					  " WHERE  codemp='".$this->ls_codemp."' AND codnom='".$as_codnom."'  AND codper='".$as_codper."' AND ".
					  "        staper='".$ls_staper_actual."' ";
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->sigesp_snorh_incor_susp_egre_personal MÉTODO->uf_update_status_personalnomina ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
		   $lb_valido=true;
		}
	}	
   if($lb_valido)
   {
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion =" Se actualizo el status del Personal ".$as_codper." status".$as_staper." de la nomina".$as_codnom;
			$ls_variable= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											   $aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		$this->io_sql->commit();
   }
   else
   {
       $this->io_sql->rollback();

   }
   return $lb_valido;
}
//-----------------------------------------------------------------------------------------------------------------------------------	
}//fin
?>
