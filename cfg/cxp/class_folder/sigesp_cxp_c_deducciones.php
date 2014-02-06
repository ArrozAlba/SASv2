<?php 
class sigesp_cxp_c_deducciones
{
	var $ls_sql;
	var $is_msg_error;
	
	function sigesp_cxp_c_deducciones($conn)
	{
	  require_once("../../shared/class_folder/sigesp_c_seguridad.php");	     
	  require_once("../../shared/class_folder/class_funciones.php");		  
	  require_once("../../shared/class_folder/class_mensajes.php");
	  require_once("../../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");
	  $this->io_funcion   = new class_funciones();
	  $this->seguridad    = new sigesp_c_seguridad();		  
	  $this->io_sql       = new class_sql($conn);
	  $this->io_msg       = new class_mensajes();
	  $this->io_rcbsf     = new sigesp_c_reconvertir_monedabsf();
	  $this->li_candeccon = $_SESSION["la_empresa"]["candeccon"];
	  $this->li_tipconmon = $_SESSION["la_empresa"]["tipconmon"];
	  $this->li_redconmon = $_SESSION["la_empresa"]["redconmon"];
	}

function uf_insert_deduccion($as_codemp,$ar_datos,$aa_seguridad) 
{
	//////////////////////////////////////////////////////////////////////////////
	//    	 Metodo:  uf_insert_deduccion
	//	     Access:  public
	//	  Arguments:  $as_codemp,$ar_datos,$aa_seguridad
	//	    Returns:  $lb_valido= Variable booleana que devuelve true si la sentencia
	//                       SQL fue ejecutada sin errores de lo contrario devuelve false.    	 	
	//	Description:  Función que se encargar de insertar un nuevo registro en la
	//                base de datos seleccionada.
	//////////////////////////////////////////////////////////////////////////////
	$li_islr      = 0;
	$li_iva       = 0;
	$li_estretmun = 0; 
	$li_otros     = 0;
	$li_retaposol = 0;
	$ls_codigo=$ar_datos["codigo"];
	$ls_denominacion=$ar_datos["denominacion"];
	$ld_porcentaje=$ar_datos["porcentaje"];
	if (empty($ld_porcentaje))
       {
	     $ld_porcentaje=0.0;
       } 
    $ls_contable = $ar_datos["contable"];
	$ls_formula  = $ar_datos["formula"];
	$ls_tipodeduccion=$ar_datos["tipodeduccion"]; 
	$ls_tipoperdeduccion=$ar_datos["tipoperdeduccion"];
	$ls_codconret=$ar_datos["codconret"];
	if ($ls_tipodeduccion=="S")
	   {
		 $li_islr=1;   
	   }
	else
	if ($ls_tipodeduccion=="I")
	   {
		 $li_iva=1;
	   }
	else
	if ($ls_tipodeduccion=="M")
	   {
		 $li_estretmun=1;
	   }		
	if ($ls_tipodeduccion=="O")
	   {
		 $li_otros=1;
	   }		
	if ($ls_tipodeduccion=="A")
	   {
		 $li_retaposol=1;
	   }		
	$ld_deducible=$ar_datos["deducible"];
	if (empty($ld_deducible))
	   {
	     $ld_deducible=0;
	   }
	else
	   {
	     $ld_deducible=str_replace('.','',$ld_deducible);;
 	     $ld_deducible=str_replace(',','.',$ld_deducible);;
	   } 
	/* if ($ls_tipoperdeduccion=="")
	   {
		 $li_otros=1;
	   } */ 
	$ls_sql="INSERT INTO sigesp_deducciones (codemp,codded,dended,sc_cuenta,porded,monded,islr,iva,estretmun,formula,".
			"                                otras,tipopers,retaposol,codconret)".
			" VALUES ('".$as_codemp."','".$ls_codigo."','".$ls_denominacion."','".$ls_contable."',".$ld_porcentaje.", ".
			"          ".$ld_deducible.",".$li_islr.",".$li_iva.",".$li_estretmun.",'".$ls_formula."',".$li_otros.",".
			"         '".$ls_tipoperdeduccion."',".$li_retaposol.",'".$ls_codconret."')";
	//print $ls_sql;
	$li_numrows=$this->io_sql->execute($ls_sql);
	$this->io_sql->begin_transaction();
	if ($li_numrows===false)
	   {
	     $lb_valido=false;
		 $this->io_msg->message("CLASE->SIGESP_CXP_C_DEDUCCIONES; METODO->uf_insert_deduccion; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   }
	else
	   {
		 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		 $ls_evento="INSERT";
		 $ls_descripcion ="Insertó en CXP una Nueva Deducción con código ".$ls_codigo." y denominación ".$ls_denominacion;
		 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		 $aa_seguridad["ventanas"],$ls_descripcion);
		 /////////////////////////////////         SEGURIDAD               /////////////////////////// 
		 $lb_valido=true;
		 /*$this->io_rcbsf->io_ds_datos->insertRow("campo","mondedaux");
		 $this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_deducible);

		 $this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
		 $this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codemp);
		 $this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
		
		 $this->io_rcbsf->io_ds_filtro->insertRow("filtro","codded");
		 $this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codigo);
		 $this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
		
		$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sigesp_deducciones",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);*/
	   }
	   return $lb_valido;
}

function uf_update_deduccion($as_codemp,$ar_datos,$aa_seguridad) 
{
//////////////////////////////////////////////////////////////////////////////
//	Metodo: uf_update_deduccion
//	Access:  public
//	Arguments: $as_codemp,$ar_datos,$aa_seguridad
//	Returns: $lb_valido= Variable booleana que devuelve true si la sentencia
//                       SQL fue ejecutada sin errores de lo contrario devuelve false. 
//	Description: Función que se encarga de actualizar registros en la tabla de
//               sigesp_deducciones.
//////////////////////////////////////////////////////////////////////////////
	$li_islr      = 0;
	$li_iva       = 0;
	$li_estretmun = 0;
	$li_otras     = 0;   
	$li_retaposol = 0;
	$ls_codigo=$ar_datos["codigo"];
	$ls_denominacion=$ar_datos["denominacion"];
	$ld_porcentaje=$ar_datos["porcentaje"];
	$ls_contable=$ar_datos["contable"];
	$ld_deducible=$ar_datos["deducible"];
	$ld_deducible=str_replace('.','',$ld_deducible);
	$ld_deducible=str_replace(',','.',$ld_deducible);
	$ls_formula=$ar_datos["formula"];
	$ls_tipodeduccion=$ar_datos["tipodeduccion"];
	$ls_tipoperdeduccion=$ar_datos["tipoperdeduccion"];
	$ls_codconret=$ar_datos["codconret"];
	if ($ls_tipodeduccion=="S")
	   {
		 $li_islr=1;   
	   }
	else
	if ($ls_tipodeduccion=="I")
	   {
		 $li_iva=1;
	   }
	else
	if ($ls_tipodeduccion=="M")
	   {
		 $li_estretmun=1;
	   }
	if ($ls_tipodeduccion=="O")
	   {
		 $li_otras=1;
	   }   		
	if ($ls_tipodeduccion=="A")
	   {
		 $li_retaposol=1;
	   }		
	$ls_sql=" UPDATE sigesp_deducciones ".
	        "    SET dended='".$ls_denominacion."',sc_cuenta='".$ls_contable."',porded=".$ld_porcentaje.",   ".
			"        monded=".$ld_deducible.",islr=".$li_islr.",iva=".$li_iva.",estretmun=".$li_estretmun.", ".
			"        formula='".$ls_formula."',otras = ".$li_otras.",tipopers='".$ls_tipoperdeduccion."',".
			"        retaposol=".$li_retaposol.",codconret='".$ls_codconret."'".
			" WHERE codemp='".$as_codemp."'".
			"   AND codded = '".$ls_codigo."'";
	$this->io_sql->begin_transaction();
	$li_numrows=$this->io_sql->execute($ls_sql);
	if ($li_numrows===false)
	   {
		 $this->io_msg->message("CLASE->SIGESP_CXP_C_DEDUCCIONES; METODO->uf_update_deduccion; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		 $lb_valido=false;
	   }
	else
	   {
		 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	     $ls_evento="UPDATE";
		 $ls_descripcion ="Actualizó en CXP la Deducción "." ".$ls_codigo;
		 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		 $aa_seguridad["ventanas"],$ls_descripcion);
		 /////////////////////////////////         SEGURIDAD               /////////////////////////// 
		 $lb_valido=true; 
		 /*$this->io_rcbsf->io_ds_datos->insertRow("campo","mondedaux");
		 $this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_deducible);

		 $this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
		 $this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
		 $this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
		
		 $this->io_rcbsf->io_ds_filtro->insertRow("filtro","codded");
		 $this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codigo);
		 $this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
		
		$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sigesp_deducciones",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);*/
	   }
	 return $lb_valido;
} 

function uf_delete_deduccion($as_codemp,$as_codigo,$as_dendeduc,$aa_seguridad)
{
	//////////////////////////////////////////////////////////////////////////////
	//	     Metodo:  uf_delete_deduccion
	//	     Access:  public
	//	  Arguments:  $as_codemp,$as_codigo,$aa_seguridad
	//	    Returns:  $lb_valido= Variable booleana que devuelve true si la sentencia
	//                SQL fue ejecutada sin errores de lo contrario devuelve false.
	//	Description:  Función que se encarga de eliminar registros en la tabla 
	//                sigesp_deducciones.  
	//////////////////////////////////////////////////////////////////////////////
	  $lb_valido = false;
	  $ls_sql    = " DELETE FROM sigesp_deducciones WHERE codemp='".$as_codemp."' AND codded='".$as_codigo."'";	    
	  $this->io_sql->begin_transaction();
	  $rs_data   = $this->io_sql->execute($ls_sql);
	  if ($rs_data===false)
		 {
		   $this->io_msg->message("CLASE->SIGESP_CXP_C_DEDUCCIONES; METODO->uf_delete_deduccion; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		   $lb_valido=false;
		 }
	  else
		 {
		   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		   $ls_evento="DELETE";
		   $ls_descripcion ="Eliminó en CXP la Deducción ".$as_codigo." con denominación ".$as_dendeduc;
		   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		   $aa_seguridad["ventanas"],$ls_descripcion);
		   /////////////////////////////////         SEGURIDAD               /////////////////////////// 
		   $lb_valido=true;
		 } 		 
  return $lb_valido;
}

function uf_select_deduccion($as_codemp,$as_codigo) 
{
//////////////////////////////////////////////////////////////////////////////
//	Metodo: uf_select_deduccion
//	Access:  public
//	Arguments: $as_codemp,$as_codigo
//	Returns: $lb_valido= Variable booleana que devuelve true si la fue
//                       encontrado el registro y la sentencia SQL 
//                       fue ejecutada sin errores de lo contrario devuelve false.	
//	Description: Función que se encarga de buscar registros en la tabla
//               sigesp_deducciones.
//////////////////////////////////////////////////////////////////////////////
	$ls_sql=" SELECT * FROM sigesp_deducciones WHERE codemp='".$as_codemp."' AND codded='".$as_codigo."'";
	$rs_deducciones=$this->io_sql->select($ls_sql);
	if ($rs_deducciones===false)
	   {
		 $lb_valido=false;
  	     $this->io_msg->message("CLASE->SIGESP_CXP_C_DEDUCCIONES; METODO->uf_delete_deduccion; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   }
	else
	   {
	     $li_numrows=$this->io_sql->num_rows($rs_deducciones);	
	     if($li_numrows>0)
		   {
		     $lb_valido=true;
			 $this->io_sql->free_result($rs_deducciones);
		   }
		 else
		   {
			 $lb_valido=false;
		   }
	   }
return $lb_valido;
}
}//Fin de la Clase...
?> 