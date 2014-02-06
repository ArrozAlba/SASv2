<?php
class sigesp_cxp_c_recep_doc
 {
    var $ls_sql;
	var $is_msg_error;
	
	function sigesp_cxp_c_recep_doc($conn)
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
	    $this->seguridad = new sigesp_c_seguridad();		 
		$this->la_emp=$_SESSION["la_empresa"];
		$this->io_sql=new class_sql($conn);
		$this->io_msg= new class_mensajes();
	    $this->io_funcion = new class_funciones();
	}
  
function uf_update_recepcion($as_codemp,$ar_datos,$aa_seguridad)
{
//////////////////////////////////////////////////////////////////////////////
// 	          Método:  uf_update_recepcion
//	          Access:  public
//	       Arguments:   
//        $as_codemp:  Código de la Empresa.
//         $ar_datos:  Arreglo cargado con la informacion proveniente de la interfaz de Registro de Recepciones de Documentos.
//     $aa_seguridad:  Arreglo cargado con la información acerca de la ventana,usuario,etc.
//	         Returns:  $lb_valido= Variable booleana que devuelve true si la sentencia
//                     SQL fue ejecutada sin errores de lo contrario devuelve false.    	
//	     Description:  Función que se encarga de modificar registros en la tabla cxp_rd dependiendo del resultado de la función
//                     uf_existe_recep_doc (Encontrado=true, No encontrado=false).    
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:11/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 
  $ls_concepto		=$ar_datos["concepto"];
  $ls_codcla        =$ar_datos["codcla"];  
  $ls_numrecdoc		=$ar_datos["numdocumento"];
  $ls_tipodoc		=$ar_datos["tipodoc"];
  $ls_numref	    =$ar_datos["numcontrol"];
  if (empty($ls_numref))
	 {
	   $ls_numcontrol="N/A"; 
	 }
  else
	 {
	   $ls_numcontrol=$ls_numref;
	 } 
  $ls_fecemi        =$ar_datos["fecemision"];
  $ls_fecemision	=$this->io_funcion->uf_convertirdatetobd($ls_fecemi);
  $ls_fecven        =$ar_datos["fecvencimiento"];
  $ls_fecvencimiento=$this->io_funcion->uf_convertirdatetobd($ls_fecven);
  $ls_fecreg        =$ar_datos["fecregistro"];
  $ls_fecregistro	=$this->io_funcion->uf_convertirdatetobd($ls_fecreg);
  $ld_montototal	=$ar_datos["montototal"];
  $ld_montototal	=str_replace('.','',$ld_montototal);
  $ld_montototal	=str_replace(',','.',$ld_montototal);  
  $ld_montoded	    =$ar_datos["montoded"];
  $ld_montoded	    =str_replace('.','',$ld_montoded);
  $ld_montoded	    =str_replace(',','.',$ld_montoded);
  $ld_montootroscred=$ar_datos["montootroscred"]; 
  $ld_montootroscred=str_replace('.','',$ld_montootroscred);
  $ld_montootroscred=str_replace(',','.',$ld_montootroscred);
  $li_libcom        =$ar_datos["libro"]; 	  
  $li_impuesto      =$ar_datos["impuesto"]; 	  
  $ls_estprov       =$ar_datos["tipoproben"];
  $ls_estaprord     =0;
  if ($ls_estprov=="P")  
	 {
	   $ls_codpro =$ar_datos["codproben"];
	   $ls_cedbene="----------";
	 }
  else
	 {
	   $ls_cedbene =$ar_datos["codproben"];
	   $ls_codpro="----------";
	 }
  $ls_usuaprord=$aa_seguridad["logusr"];
  $ls_status   =$ar_datos["estatusdoc"];
  if ($ls_status=="RECIBIDO") 
	 {
	   $ls_estatus="R";
	 }
  if ($ls_status=="ANULADA") 
	 {
	   $ls_estatus="A";
	 }
  if ($ls_status=="Solicitud de Pago Emitida Completa") 
	 {
	   $ls_estatus="E";
	 }
  if ($ls_status=="Procesada Directamente sin una Solicitud") 
	 {
	   $ls_estatus="C";
	 }
  $ls_procededoc=$ar_datos["procede"];
  if (empty($ls_procededoc))
	 {
	   $ls_procededoc="CXPRCD";
	 }
  $ls_sql=" UPDATE cxp_rd ".
		  " SET    dencondoc='".$ls_concepto."',       codcla='".$ls_codcla."',         ".
		  "        fecemidoc='".$ls_fecemision."',     fecregdoc='".$ls_fecregistro."', ".
		  "        fecvendoc='".$ls_fecvencimiento."', montotdoc=".$ld_montototal.",    ".
		  "        mondeddoc=".$ld_montoded.",         moncardoc=".$ld_montootroscred.",".
		  "        numref='".$ls_numcontrol."',        estlibcom=".$li_libcom."         ".
		  " WHERE  codemp='".$as_codemp."' AND numrecdoc='".$ls_numrecdoc."' AND codtipdoc='".$ls_tipodoc."' AND cod_pro='".$ls_codpro."' AND ced_bene='".$ls_cedbene."'";		  
	$rs_data=$this->io_sql->execute($ls_sql);
	if ($rs_data===false)
	   {
 	     $this->io_sql->rollback();
		 $this->io_msg->message("CLASE->SIGESP_CXP_C_RECEP_DOC; METODO->uf_update_recepcion; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		 $lb_valido=false;
	   }
	else
	  {
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	    $ls_evento="UPDATE";
	    $ls_descripcion ="Actualizó en CXP la Recepción Número ".$ls_numrecdoc;
	    $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	    $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	    $aa_seguridad["ventanas"],$ls_descripcion);
	    /////////////////////////////////         SEGURIDAD               ////////////////////////////
		$lb_valido=true;
	  }//11
return $lb_valido;
}

function uf_insert_recepcion($as_codemp,$ar_datos,$aa_seguridad)
{  	   
//////////////////////////////////////////////////////////////////////////////
//	          Método:  uf_insert_recepcion
//	          Access:  public
//	        Arguments   
//        $as_codemp:  Código de la Empresa.
//         $ar_datos:  Arreglo cargado con la informacion proveniente de la interfaz de Registro de Recepciones de Documentos.
//     $aa_seguridad:  Arreglo cargado con la información acerca de la ventana,usuario,etc.
//	         Returns:  $lb_valido= Variable booleana que devuelve true si la sentencia SQL fue ejecutada sin errores de lo contrario devuelve false.    	
//	     Description:  Función que se encarga de incluir registros en la tabla cxp_rd dependiendo del resultado de la función
//                     uf_existe_recep_doc (Encontrado=true, No encontrado=false).    
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:10/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 

  $ls_concepto		=$ar_datos["concepto"];
  $ls_codcla        =$ar_datos["codcla"];  
  $ls_numrecdoc		=$ar_datos["numdocumento"];
  $ls_tipodoc		=$ar_datos["tipodoc"];
  $ls_numref	    =$ar_datos["numcontrol"];
  if ($ls_numref=="")
	 {
	   $ls_numcontrol="N/A"; 
	 }
  else
	 {
	   $ls_numcontrol=$ls_numref;
	 }	 
  $ls_fecemi         =$ar_datos["fecemision"];
  $ls_fecemision	 =$this->io_funcion->uf_convertirdatetobd($ls_fecemi);
  $ls_fecven         =$ar_datos["fecvencimiento"];
  $ls_fecvencimiento =$this->io_funcion->uf_convertirdatetobd($ls_fecven);
  $ls_fecreg         =$ar_datos["fecregistro"];
  $ls_fecregistro	 =$this->io_funcion->uf_convertirdatetobd($ls_fecreg);
  $ld_montototal	 =$ar_datos["montototal"];
  $ld_montototal	 =str_replace('.','',$ld_montototal);
  $ld_montototal	 =str_replace(',','.',$ld_montototal);  
  $ld_montoded	     =$ar_datos["montoded"];
  $ld_montoded	     =str_replace('.','',$ld_montoded);
  $ld_montoded	     =str_replace(',','.',$ld_montoded);
  $ld_montootroscred =$ar_datos["montootroscred"]; 
  $ld_montootroscred =str_replace('.','',$ld_montootroscred);
  $ld_montootroscred =str_replace(',','.',$ld_montootroscred);
  $li_libcom        =$ar_datos["libro"]; 	  
  $li_impuesto      =$ar_datos["impuesto"]; 	  
  $ls_estprov       =$ar_datos["tipoproben"];
  $ls_estaprord     =0;
  if ($ls_estprov=="P")  
	 {
	   $ls_codpro =$ar_datos["codproben"];
	   $ls_cedbene="----------";
	 }
  else
	 {
	   $ls_cedbene =$ar_datos["codproben"];
	   $ls_codpro="----------";
	 }
  $ls_status        =$ar_datos["estatusdoc"];
  $ls_usuaprord="N/A";
  if ($ls_status=="RECIBIDO") 
	 {
	   $ls_estatus="R";
	 }
  if ($ls_status=="ANULADA") 
	 {
	   $ls_estatus="A";
	 }
  if ($ls_status=="Solicitud de Pago Emitida Completa") 
	 {
	   $ls_estatus="E";
	 }
  if ($ls_status=="Procesada Directamente sin una Solicitud") 
	 {
	   $ls_estatus="C";
	 }
  $ls_procede=$ar_datos["procede"];
  if (empty($ls_procede))
     {
	   $ls_procede="CXPRCD";   
	 }
  $ls_sql=" INSERT INTO cxp_rd (codemp, numrecdoc, codtipdoc, ced_bene, cod_pro, codcla, ".
	      " dencondoc, fecemidoc, fecregdoc, fecvendoc, montotdoc, mondeddoc, moncardoc, tipproben, ".
		  " numref, estprodoc, procede, estlibcom, estaprord, fecaprord, usuaprord) ".
		  " VALUES ".
		  " ('".$as_codemp."','".$ls_numrecdoc."','".$ls_tipodoc."','".$ls_cedbene."','".$ls_codpro."', ".
		  " '".$ls_codcla."','".$ls_concepto."','".$ls_fecemision."','".$ls_fecregistro."','".$ls_fecvencimiento."', ".
		  " ".$ld_montototal.",".$ld_montoded.",".$ld_montootroscred.",'".$ls_estprov."','".$ls_numcontrol."', ".
		  " 'R', '".$ls_procede."',".$li_libcom.",".$ls_estaprord.",'0000/00/00','')";		
	$this->io_sql->begin_transaction();
	$li_row=$this->io_sql->execute($ls_sql);
	if ($li_row===false)
	   {
		  $this->io_msg->message("CLASE->SIGESP_CXP_C_RECEP_DOC; METODO->uf_insert_recepcion; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		  $lb_valido=false;
	   }
	else
	   {
		 $this->io_sql->commit();
		 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		 $ls_evento="INSERT";
		 $ls_descripcion ="Insertó en CXP Nueva Recepción ".$ls_numrecdoc;
		 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
		 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
		 $aa_seguridad["ventanas"],$ls_descripcion);
		 /////////////////////////////////         SEGURIDAD               /////////////////////////// 
		 $lb_valido=true;
	   }	  	
return $lb_valido;
}

function uf_select_recepcion($as_codemp,$ar_datos)
{
//////////////////////////////////////////////////////////////////////////////
//	          Método:  uf_select_recepcion
//	          Access:  public
//	       Arguments:    
//        $as_codemp:  Código de la Empresa.
//         $ar_datos:  Arreglo cargado con la informacion proveniente de la interfaz de Registro de Recepciones de Documentos.
//	         Returns:  $lb_valido = Variable booleana que devuelve true si la fue encontrado el registro y la sentencia SQL 
//                     fue ejecutada sin errores de lo contrario devuelve false.
//	     Description:  Función que se encarga de buscar una recepción dentro de la tabla cxp_rd.
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:17/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 
	
	$lb_existe    = false;
	$ls_numrecdoc = $ar_datos["numdocumento"];
	$ls_codigo    = $ar_datos["codproben"];
	$ls_tiproben  = $ar_datos["tipoproben"];
	$ls_codtipdoc = $ar_datos["tipodoc"];
	if ($ls_tiproben=='P')
	   {
	     $ls_codpro =$ls_codigo;
		 $ls_cedbene='----------';
	   }
	else
	   {
	     $ls_codpro ='----------';
		 $ls_cedbene=$ls_codigo;
	   }
	$ls_sql=" SELECT * FROM cxp_rd ".
			" WHERE  codemp='".$as_codemp."' AND numrecdoc='".$ls_numrecdoc."' AND cod_pro='".$ls_codpro."' AND ".
			"        ced_bene='".$ls_cedbene."'                                                                 ";
	$rs_data=$this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
	     $this->io_msg->message("CLASE->SIGESP_CXP_C_RECEP_DOC; METODO->uf_select_recepcion; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   }
    else
	   {
		 if ($row=$this->io_sql->fetch_row($rs_data))
		    {
			  $lb_existe=true;
		    }
	   }
return $lb_existe;
}
	
function uf_delete_recepcion($as_codemp,$ar_datos,$aa_seguridad)
{
//////////////////////////////////////////////////////////////////////////////
// 	          Método:  uf_delete_recepcion
//	          Access:  public
//	       Arguments:   
//        $as_codemp:  Código de la Empresa.
//         $ar_datos:  Arreglo cargado con la informacion proveniente de la interfaz de Registro de Recepciones de Documentos.
//     $aa_seguridad:  Arreglo cargado con la información acerca de la ventana,usuario,etc.
//	         Returns:  $lb_valido= Variable booleana que devuelve true si la sentencia SQL fue ejecutada sin errores de lo contrario devuelve false.
//	     Description:  Función que se encarga de eliminar una recepción con todos
//                     sus registros asociados.
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:11/03/2006.	 
//////////////////////////////////////////////////////////////////////////////         
	
	$ls_codproben =$ar_datos["codproben"]; 
	$ls_estprov   =$ar_datos["tipoproben"];
	if ($ls_estprov=="P")
	   {
		 $ls_codpro =$ls_codproben;
		 $ls_cedbene="----------"; 
	   }
	else
	   {
		 $ls_cedbene=$ls_codproben;
		 $ls_codpro ="----------";
	   }
	$ls_numrecdoc  = $ar_datos["numdocumento"];
	$ls_codtipdoc  = $ar_datos["tipodoc"];
	$lb_valido     = $this->uf_delete_historico($as_codemp,$ar_datos,$aa_seguridad);
	if ($lb_valido)
	   {
		 $lb_valido=$this->uf_delete_dt_scg($as_codemp,$ar_datos,$aa_seguridad);
		 if ($lb_valido)
			{
			  $lb_valido=$this->uf_delete_dt_spg($as_codemp,$ar_datos,$aa_seguridad);
			  if ($lb_valido)
				 {
				   $lb_valido=$this->uf_delete_dtded($as_codemp,$ls_numrecdoc,$aa_seguridad);
				   if ($lb_valido)
					  {
						$lb_valido=$this->uf_delete_dtotroscre($as_codemp,$ls_numrecdoc,$aa_seguridad);
						if ($lb_valido)
						   {
							 $ls_sql=" DELETE ".
									 " FROM   cxp_rd ".
									 " WHERE  codemp='".$as_codemp."'    AND cod_pro='".$ls_codpro."'      AND".
									 "        ced_bene='".$ls_cedbene."' AND numrecdoc='".$ls_numrecdoc."' AND".
									 "        codtipdoc='".$ls_codtipdoc."' ";
							 $li_numrows=$this->io_sql->execute($ls_sql);
							 if ($li_numrows===false)
								{  
								  $this->io_sql->rollback();
								  $this->io_msg->message("CLASE->SIGESP_CXP_C_RECEP_DOC; METODO->uf_delete_recepcion; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
								  $lb_valido=false; 
								}
							 else
								{
								   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
								   $ls_evento="DELETE";
								   $ls_descripcion ="Eliminó en CXP la Recepción Número ".$ls_numrecdoc;
								   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
								   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
								   $aa_seguridad["ventanas"],$ls_descripcion);
								   /////////////////////////////////         SEGURIDAD               /////////////////////////// 
								   $lb_valido=true;
								}
							}//5   	                         
						 else
						   {
							 $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
							 $lb_valido=false;
						   }
					  }//4
				   else
					 {
					   $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
					   $lb_valido=false;
					 }	 
				 }//3
			  else
				{
				  $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
				  $lb_valido=false; 
				}
			}//2
		  else
			{
			  $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
			  $lb_valido=false;
			}
		  }//1
		else
		  {
			$this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		  }	
return $lb_valido;
}
				
function uf_insert_dt_scg($as_codemp,$ar_datos,$as_numrecdoc,$as_comprobante,$as_sccuenta,$as_debhab,$ad_monto,$aa_seguridad)
{
////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Método:  uf_insert_dt_scg
//	          Access:  public
//	        Arguments   
//        $as_codemp:  Código de la Empresa.
//         $ar_datos:  Arreglo cargardo con informacion proveniente de la Interfaz de la Recepción.
//     $as_numrecdoc:  Número de la Recepcion de Documento.
//      $as_sccuenta:  Cuenta contable asociada al Detalle Contable.
//        $as_debhab:  Tipo de Operacio D=Debe,H=Haber.
//         $ad_monto:  Monto por el cual se realiza el registro del Detalle Contable.
//     $aa_seguridad:  Arreglo cargado con la información acerca de la ventana,usuario,etc.
//	         Returns:  $lb_valido= Variable booleana que devuelve true si la sentencia
//                     SQL fue ejecutada sin errores de lo contrario devuelve false.
//	     Description:  Función que se encarga de insertar detalles de tipo contable
//                     dentro de la tabla cxp_rd_scg para una recepción.    
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:24/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 
  $ls_numrecdoc =$ar_datos["numdocumento"];
  $ls_codtipdoc =$ar_datos["tipodoc"];
  $ls_fecha     =$ar_datos["fecregistro"];
  $ls_codproben =$ar_datos["codproben"]; 
  $ls_estprov   =$ar_datos["tipoproben"];
  if ($as_debhab=="Debe")
	 {
	   $ls_debhab="D";
	 }
  else
	{
	  $ls_debhab="H";
	}
  if ($ls_estprov=="P")
	 {
	   $ls_codpro =$ls_codproben;
	   $ls_cedbene="----------"; 
	 }
  else
	 {
	   $ls_cedbene=$ls_codproben;
	   $ls_codpro ="----------";
	 }	 
  $ls_procede=$ar_datos["procede"];
  if (empty($ls_procede))
     {
	   $ls_procede="CXPRCD";
	 }
  $li_estgenasi =0;
  $ad_monto=str_replace('.','',$ad_monto);
  $ad_monto=str_replace(',','.',$ad_monto);
  
  $ls_sql=" INSERT INTO cxp_rd_scg ".
		  " (codemp,numrecdoc,codtipdoc,cod_pro,ced_bene,procede_doc,numdoccom,debhab,sc_cuenta, ".
		  "  monto,estgenasi) ".
		  "  VALUES ".
		  " ('".$as_codemp."','".$ls_numrecdoc."', '".$ls_codtipdoc."','".$ls_codpro."',".
		  " '".$ls_cedbene."','".$ls_procede."','".$as_comprobante."','".$ls_debhab."',".
		  " '".$as_sccuenta."',".$ad_monto.",".$li_estgenasi.") ";
  $li_numrows=$this->io_sql->execute($ls_sql);
  if ($li_numrows===false)
	 {
	   $this->io_sql->rollback();
	   $this->io_msg->message("CLASE->SIGESP_CXP_C_RECEP_DOC; METODO->uf_insert_dt_scg; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   $lb_valido=false;
	 }
  else
	{
	  /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	  $ls_evento="INSERT";
	  $ls_descripcion ="Insertó en CXP Detalle Contable para la Recepción Número ".$ls_numrecdoc;
	  $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	  $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	  $aa_seguridad["ventanas"],$ls_descripcion);
	  /////////////////////////////////         SEGURIDAD               /////////////////////////// 
	  $lb_valido=true;
	}
return $lb_valido;  
}

function uf_insert_dt_spg($as_codemp,$ar_datos,$as_numrecdoc,$as_comprobante,$as_codestpro,$as_spgcuenta,$ad_montopre,$aa_seguridad)
{
/////////////////////////////////////////////////////////////////////////////////////////
//	          Método:  uf_insert_dt_spg
//	          Access:  public
//	       Arguments:  $as_codemp,$ar_datos,$as_compromisopre,$as_codestpro,
//                     $as_spgcuenta,$ad_montopre,$aa_seguridad.
//	         Returns:  $lb_valido= Variable booleana que devuelve true si la sentencia
//                     SQL fue ejecutada sin errores de lo contrario devuelve false.
//	     Description:  Función que se encarga de insertar detalles de tipo presupuestarios
//                     dentro de la tabla cxp_rd_spg para una recepción.    
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:24/03/2006.	 
////////////////////////////////////////////////////////////////////////////////////////// 
  $ls_codtipdoc =$ar_datos["tipodoc"];
  $ls_codproben =$ar_datos["codproben"]; 
  $ls_estprov   =$ar_datos["tipoproben"];
  if ($ls_estprov=="P")
	 {
	   $ls_codpro =$ls_codproben;
	   $ls_cedbene="----------"; 
	 }
  else
	 {
	   $ls_cedbene=$ls_codproben;
	   $ls_codpro ="----------";
	 }
  $ls_procede=$ar_datos["procede"];
  if (empty($ls_procede))
     {
	   $ls_procede="CXPRCD";
	 }
  $ls_codestpro1 = substr($as_codestpro,0,20);
  $ls_codestpro2 = substr($as_codestpro,21,6);
  $ls_codestpro3 = substr($as_codestpro,28,3);
  $ls_codestpro4 = "00";
  $ls_codestpro5 = "00";
  $ls_codestpro  = $ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
  $ld_montoded	 = str_replace('.','',$ad_montopre);
  $ld_montoded	 = str_replace(',','.',$ld_montoded);
  if (empty($as_comprobante))
     {
	   $as_comprobante=$as_numrecdoc;
	 }
  $ls_sql=" INSERT INTO cxp_rd_spg ".
		  " (codemp,numrecdoc,codtipdoc,cod_pro,ced_bene,procede_doc,numdoccom,codestpro,".
		  "  spg_cuenta,monto) ".
		  " VALUES ".
		  " ('".$as_codemp."','".$as_numrecdoc."','".$ls_codtipdoc."','".$ls_codpro."', ".
		  " '".$ls_cedbene."','".$ls_procede."','".$as_comprobante."','".$ls_codestpro."', ".
		  " '".$as_spgcuenta."',".$ld_montoded.")";
  $li_numrows=$this->io_sql->execute($ls_sql);
  if ($li_numrows===false)
	 {
	   $this->io_sql->rollback();
	   $this->io_msg->message("CLASE->SIGESP_CXP_C_RECEP_DOC; METODO->uf_insert_dt_spg; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   $lb_valido=false;
	 }
	else
	{
	  /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	  $ls_evento="INSERT";
	  $ls_descripcion ="Insertó en CXP Detalle Presupuestario para la Recepción Número ".$as_numrecdoc;
	  $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	  $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	  $aa_seguridad["ventanas"],$ls_descripcion);
	  /////////////////////////////////         SEGURIDAD               /////////////////////////// 
	  $lb_valido=true;
	}
  return $lb_valido;
}
	
function uf_delete_dt_scg($as_codemp,$ar_datos,$aa_seguridad)
{
//////////////////////////////////////////////////////////////////////////////
//        	  Método:  uf_delete_dt_scg
//	          Access:  public
//	       Arguments:  $as_codemp,$ar_datos,$aa_seguridad
//	         Returns:  $lb_valido= Variable booleana que devuelve true si la sentencia
//                     SQL fue ejecutada sin errores de lo contrario devuelve false.
//	     Description:  Función que se encarga de eliminar detalles de tipo contable
//                     dentro de la tabla cxp_rd_scg para una recepción.    
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:24/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 
  $ls_numrecdoc=$ar_datos["numdocumento"];
  $ls_codtipdoc=$ar_datos["tipodoc"];
  $ls_codproben=$ar_datos["codproben"];
  $ls_estprov=$ar_datos["tipoproben"];
  if ($ls_estprov=="P")
	 {
	   $ls_codpro =$ls_codproben;
	   $ls_cedbene="----------"; 
	 }
  else
	 {
	   $ls_cedbene=$ls_codproben;
	   $ls_codpro ="----------";
	 }	 
  $ls_sql=" DELETE            ".
		  " FROM   cxp_rd_scg ".
		  " WHERE  codemp='".$as_codemp."'       AND numrecdoc='".$ls_numrecdoc."' AND".
		  "        codtipdoc='".$ls_codtipdoc."' AND cod_pro='".$ls_codpro."'      AND".
		  "        ced_bene='".$ls_cedbene."'                                         ";
  $li_numrows=$this->io_sql->execute($ls_sql);
  if ($li_numrows===false)
	 {
	   $this->io_sql->rollback();
	   $this->io_msg->message("CLASE->SIGESP_CXP_C_RECEP_DOC; METODO->uf_delete_dt_scg; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   $lb_valido=false;
	 }
  else
	 {
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	   $ls_evento="DELETE";
	   $ls_descripcion ="Eliminó en CXP Detalle Contable para la Recepción Número ".$ls_numrecdoc;
	   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	   $aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               //////////////////////////// 
	   $lb_valido=true;
	 }
return $lb_valido;
}
	
function uf_delete_dt_spg($as_codemp,$ar_datos,$aa_seguridad)
{
//////////////////////////////////////////////////////////////////////////////
//	          Método:  uf_delete_dt_spg
//	          Access:  public
//	        Arguments  $as_codemp,$ar_datos,$aa_seguridad
//	         Returns:  $lb_valido= Variable booleana que devuelve true si la sentencia
//                     SQL fue ejecutada sin errores de lo contrario devuelve false.
//	     Description:  Función que se encarga de eliminar detalles de tipo presupuestario
//                     dentro de la tabla cxp_rd_scg para una recepción.    
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:24/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 
  $ls_numrecdoc=$ar_datos["numdocumento"];
  $ls_codtipdoc=$ar_datos["tipodoc"];
  $ls_codproben=$ar_datos["codproben"];
  $ls_estprov  =$ar_datos["tipoproben"];
  if ($ls_estprov=="P")
	 {
	   $ls_codpro =$ls_codproben;
	   $ls_cedbene="----------"; 
	 }
  else
	 {
	   $ls_cedbene=$ls_codproben;
	   $ls_codpro ="----------";
	 }	 
  $ls_sql=" DELETE ".
		  " FROM   cxp_rd_spg ".
		  " WHERE  codemp='".$as_codemp."'       AND numrecdoc='".$ls_numrecdoc."' AND".
		  "        codtipdoc='".$ls_codtipdoc."' AND cod_pro='".$ls_codpro."'      AND ".
		  "        ced_bene='".$ls_cedbene."' ";
  $li_numrows=$this->io_sql->execute($ls_sql);
  if ($li_numrows===false)
	 {
	   $this->io_sql->rollback();
   	   $this->io_msg->message("CLASE->SIGESP_CXP_C_RECEP_DOC; METODO->uf_delete_dt_spg; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   $lb_valido=false;
	 }
  else
	{
	  /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	  $ls_evento="DELETE";
	  $ls_descripcion ="Eliminó en CXP Detalle Presupuestario para la Recepción Número ".$ls_numrecdoc;
	  $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	  $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	  $aa_seguridad["ventanas"],$ls_descripcion);
	  /////////////////////////////////         SEGURIDAD               ////////////////////////////
	  $lb_valido=true;
	}
return $lb_valido;
}
	
function uf_insert_dedxrecepdoc($as_codemp,$as_compromiso,$as_codded,$ad_monobjret,$ad_montoret,$ad_porcentaje,$as_sccuenta,$aa_seguridad)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	        Método:  insert_dedxrecepdoc
//	        Access:  public
//	      Arguments 
//      $as_codemp:
//  $as_compromiso:
//      $as_codded:
//   $ad_monobjret:
//    $ad_montoret:
//  $ad_porcentaje:
//    $as_sccuenta:
//   $aa_seguridad:
//	       Returns:  $lb_valido= Variable booleana que devuelve true si la sentencia
//                   SQL fue ejecutada sin errores de lo contrario devuelve false.      
//	   Description:  Función que se encarga de insertar deducciones dentro de la tabla cxp_rd_deducciones para una recepción.    
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:24/03/2006.	 
////////////////////////////////////////////////////////////////////////////// //////////////////////////////////////////////////////////////////////////////
  $ls_procede   = $_POST["hidprocede"];
  $ls_codpro    = $_POST["hidcodpro"];
  $ls_cedbene   = $_POST["hidcedbene"];
  $ls_numrecdoc = $_POST["hidnumrecdoc"];
  $ls_tipodoc   = $_POST["hidcodtipdoc"];
  $ls_codpro    = $_POST["hidcodpro"];
  $ls_cedbene   = $_POST["hidcedbene"];
  $ls_sql=" INSERT INTO cxp_rd_deducciones ".
		  " (codemp,numrecdoc,codtipdoc,cod_pro,ced_bene,codded,procede_doc,numdoccom,monobjret,".
		  "  monret,porded,sc_cuenta)".
		  "  VALUES ".
		  " ('".$as_codemp."','".$ls_numrecdoc."','".$ls_tipodoc."','".$ls_codpro."',".
		  " '".$ls_cedbene."','".$as_codded."','".$ls_procede."','".$as_compromiso."',".
		  " ".$ad_monobjret.",".$ad_montoret.",".$ad_porcentaje.",'".$as_sccuenta."')";
  $this->io_sql->begin_transaction();  
  $li_row=$this->io_sql->execute($ls_sql);
  if ($li_row===false)
	 {
	   $this->io_sql->rollback();
	   $this->io_msg->message("CLASE->SIGESP_CXP_C_RECEP_DOC; METODO->uf_insert_dedxrecepdoc; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   print $this->io_sql->message;
	   $lb_valido=false;
	 }
  else
	 {
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	   $ls_evento="INSERT";
	   $ls_descripcion ="Insertó en CXP una Deducción para la Recepción Número ".$ls_numrecdoc;
	   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	   $aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               ////////////////////////////
	   $this->io_sql->commit();
	   $lb_valido=true;
	 }
return $lb_valido;
} 

function uf_insert_carxrecepdoc($as_codemp,$as_procededoc,$as_compromiso,$as_codcar,$ad_monobjret,$ad_montoret,$as_codestpro,
                                $as_spgcuenta,$ad_porcentaje,$as_formula,$aa_seguridad)
{
//////////////////////////////////////////////////////////////////////////////
//	          Método:  uf_insert_carxrecepdoc
//	          Access:  public
//	       Arguments:  $as_codemp,$as_procedencia,$as_compromiso,$as_codcar,$ad_monobjret
//                     $ad_montoret,$as_codestpro,$as_spgcuenta,$ad_porcentaje,$aa_seguridad
//	         Returns:  $lb_valido= Variable booleana que devuelve true si la sentencia
//                     SQL fue ejecutada sin errores de lo contrario devuelve false. 
//	     Description:  Función que se encarga de insertar Otros Créditos 
//                     dentro de la tabla cxp_rd_cargos para una recepción.    
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/02/2006       Fecha Última Actualización:24/03/2006.	 
//////////////////////////////////////////////////////////////////////////////   
   $ls_codpro      = $_POST["hidcodpro"];
   $ls_cedbene     = $_POST["hidcedbene"];
   $ls_numrecdoc   = $_POST["hidnumrecdoc"];
   $ls_tipodoc     = $_POST["hidcodtipdoc"];
   $ls_codpro      = $_POST["hidcodpro"];
   $ls_cedbene     = $_POST["hidcedbene"];
   $ls_codestpro1  = substr($as_codestpro,0,20);
   $ls_codestpro2  = substr($as_codestpro,20,6);
   $ls_codestpro3  = substr($as_codestpro,26,3);
   $ls_codestpro4  = '00';
   $ls_codestpro5  = '00';
   $as_procedencia = "CXPRCD";
   $ls_sql=" INSERT INTO cxp_rd_cargos ".
		   " (codemp,numrecdoc,codtipdoc,cod_pro,ced_bene,codcar,procede_doc,numdoccom,monobjret,".
		   "  monret,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta,porcar,formula) ".
		   " VALUES ".
		   " ('".$as_codemp."','".$ls_numrecdoc."','".$ls_tipodoc."','".$ls_codpro."',".
		   " '".$ls_cedbene."','".$as_codcar."','".$as_procedencia."','".$as_compromiso."',".
		   " ".$ad_monobjret.",".$ad_montoret.",'".$ls_codestpro1."','".$ls_codestpro2."',".
		   " '".$ls_codestpro3."','".$ls_codestpro4."','".$ls_codestpro5."','".$as_spgcuenta."',".
		   " ".$ad_porcentaje.",'".$as_formula."')";
  $rs_data = $this->io_sql->execute($ls_sql);
  if ($rs_data===false)
	 {
	   $this->io_sql->rollback();
	   $this->io_msg->message("CLASE->SIGESP_CXP_C_RECEP_DOC; METODO->uf_insert_carxrecepdoc; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   $lb_valido=false;
     }
  else
	{
	  /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	  $ls_evento="INSERT";
	  $ls_descripcion ="Insertó en CXP Otros Créditos para la Recepción Número ".$ls_numrecdoc;
	  $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	  $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	  $aa_seguridad["ventanas"],$ls_descripcion);
	  /////////////////////////////////         SEGURIDAD               ////////////////////////////
	  $lb_valido=true;
	}
return $lb_valido;
} 

function uf_insert_cargos_comprobante($as_codemp,$as_numrecdoc,$as_codtipdoc,$as_tipproben,$as_codigo,$as_codcar,$as_procededoc, 
	                                  $as_numdoccom,$ad_monobjret,$ad_monret,$as_codestpro1,$as_codestpro2,$as_codestpro3,
									  $as_codestpro4,$as_codestpro5,$as_spgcuenta,$as_formula,$ad_porcar,$aa_seguridad)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	         Método:   uf_insert_cargos_comprobante
// 	         Access:   public
//	       Arguments   $as_codemp,$as_procedencia,$as_compromiso,$as_codcar,$ad_monobjret
//                     $ad_montoret,$as_codestpro,$as_spgcuenta,$ad_porcentaje,$aa_seguridad
//	         Returns:  $lb_valido= Variable booleana que devuelve true si la sentencia
//                     SQL fue ejecutada sin errores de lo contrario devuelve false. 
//	     Description:  Función que se encarga de insertar una nueva modalidad en la tabla soc_modalidadclausulas. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  17/03/2006       Fecha Última Actualización:17/03/2006.	 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	   if ($as_tipproben=='P')
	      {
		    $ls_codpro =$as_codigo;
			$ls_cedbene='----------';
		  }
		else
		  {
		    $ls_codpro ='----------';
			$ls_cedbene=$as_codigo;
	      }  
	   if (empty($as_procededoc))
	      {
		    $as_procededoc="CXPRCD";
		  }
	   $ls_sql=" INSERT INTO cxp_rd_cargos ".
		       " (codemp,numrecdoc,codtipdoc,cod_pro,ced_bene,codcar,procede_doc,numdoccom,monobjret,      ".
		       "  monret,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta,porcar,formula) ".
		       " VALUES ".
		       " ('".$as_codemp."','".$as_numrecdoc."','".$as_codtipdoc."','".$ls_codpro."',".
		       " '".$ls_cedbene."','".$as_codcar."','".$as_procededoc."','".$as_numdoccom."',".
		       " ".$ad_monobjret.",".$ad_monret.",'".$as_codestpro1."','".$as_codestpro2."',".
		       " '".$as_codestpro3."','".$as_codestpro4."','".$as_codestpro5."','".$as_spgcuenta."',".
		       " ".$ad_porcar.",'".$as_formula."')";
  $rs_data=$this->io_sql->execute($ls_sql);
  if ($rs_data===false)
	 {
	   $this->io_msg->message("CLASE->SIGESP_CXP_C_RECEP_DOC; METODO->uf_insert_cargos_comprobante; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   $lb_valido=false;
     }
  else
	{
	  /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	  $ls_evento="INSERT";
	  $ls_descripcion ="Insertó en CXP Otros Créditos para la Recepción Número ".$as_numrecdoc;
	  $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	  $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	  $aa_seguridad["ventanas"],$ls_descripcion);
	  /////////////////////////////////         SEGURIDAD               ////////////////////////////
	  $lb_valido=true;
	}
return $lb_valido;
}

function uf_insert_historicord($as_codemp,$ar_datos,$aa_seguridad)
{
//////////////////////////////////////////////////////////////////////////////
//	Método:       ue_insert_historico_rd
//	Access:       public
//	Arguments:    
// $as_codemp:
//  $ar_datos
//$aa_seguridad
//	Returns:      $lb_valido= Variable booleana que devuelve true si la sentencia
//                SQL fue ejecutada sin errores de lo contrario devuelve false. 
//	Description:  Función que se encarga de insertar registros de tipo histórico 
//                dentro de la tabla cxp_historico_rd para una recepción.    
//////////////////////////////////////////////////////////////////////////////
  $ls_numrecdoc=$ar_datos["numdocumento"];
  $ls_codtipdoc=$ar_datos["tipodoc"];
  $ls_tipproben=$ar_datos["tipoproben"];
  if ($ls_tipproben=="P")
     {
	   $ls_codpro =$ar_datos["codproben"];
	   $ls_cedbene='----------'; 
	 }
  else
     {
  	   $ls_cedbene =$ar_datos["codproben"];
	   $ls_codpro='----------'; 
	 }
  $ls_fecha =$ar_datos["fecregistro"];
  $ls_fecreg=$this->io_funcion->uf_convertirdatetobd($ls_fecha);
  $ls_estprodoc="R";
  $ls_sql=" INSERT INTO cxp_historico_rd ".
		  " (codemp, numrecdoc, codtipdoc, ced_bene, cod_pro, fecha, estprodoc)".
		  " VALUES ".
		  " ('".$as_codemp."','".$ls_numrecdoc."','".$ls_codtipdoc."','".$ls_cedbene."','".$ls_codpro."','".$ls_fecreg."',".
		  " '".$ls_estprodoc."')";
  $li_row=$this->io_sql->execute($ls_sql);
  if ($li_row===false)
	 {
	   $this->io_sql->rollback();
	   $this->io_msg->message("CLASE->SIGESP_CXP_C_RECEP_DOC; METODO->uf_insert_historicord; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   $lb_valido=false;
	 }
  else
	{
	  /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	  $ls_evento="INSERT";
	  $ls_descripcion ="Insertó en CXP un Registro Histórico para la Recepción Número ".$ls_numrecdoc;
	  $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	  $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	  $aa_seguridad["ventanas"],$ls_descripcion);
	  /////////////////////////////////         SEGURIDAD               ////////////////////////////
	  $lb_valido=true;
	}
  return $lb_valido;
}

function uf_delete_historico($as_codemp,$ar_datos,$aa_seguridad)
{
//////////////////////////////////////////////////////////////////////////////
//	Método:       uf_delete_historico
//	Access:       public
//	Arguments:    $as_codemp,$ar_datos,$aa_seguridad
//	Returns:      $lb_valido= Variable booleana que devuelve true si la sentencia
//                SQL fue ejecutada sin errores de lo contrario devuelve false. 
//	Description:  Función que se encarga de eliminar registros de tipo histórico 
//                dentro de la tabla cxp_historico_rd para una recepción.    
//////////////////////////////////////////////////////////////////////////////

  $ls_numrecdoc = $ar_datos["numdocumento"];
  $ls_codtipdoc = $ar_datos["tipodoc"];
  $ls_codproben = $ar_datos["codproben"];
  $ls_tipproben = $ar_datos["tipoproben"];
  if ($ls_tipproben=='P')
     {
       $ls_codpro  = $ls_codproben;
       $ls_cedbene = "----------";	 
	 }
  else
     {
       $ls_cedbene = $ls_codproben;
       $ls_codpro  = "----------";	 
	 }
 
  $ls_sql=" DELETE FROM cxp_historico_rd ".
		  " WHERE codemp='".$as_codemp."' AND numrecdoc='".$ls_numrecdoc."' AND codtipdoc='".$ls_codtipdoc."' AND cod_pro='".$ls_codpro."' AND ced_bene='".$ls_cedbene."'";
  $this->io_sql->begin_transaction();
  $li_numrows=$this->io_sql->execute($ls_sql);
  if ($li_numrows===false)
	 {
	   $this->io_sql->rollback();
	   $this->io_msg->message("CLASE->SIGESP_CXP_C_RECEP_DOC; METODO->uf_delete_historico; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   $lb_valido=false;
	 }
	else
	 {
	   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
	   $ls_evento="DELETE";
	   $ls_descripcion ="Eliminó en CXP Registro Histórico para la Recepción Número ".$ls_numrecdoc;
	   $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	   $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	   $aa_seguridad["ventanas"],$ls_descripcion);
	   /////////////////////////////////         SEGURIDAD               ////////////////////////////
	   $lb_valido=true;
	 }
return $lb_valido;
}

function uf_delete_dtded($as_codemp,$as_compromiso,$aa_seguridad)
{
//////////////////////////////////////////////////////////////////////////////
//	Método:       delete_detalles_ded
//	Access:       public
//	Arguments:    $as_codemp,$as_compromiso,$aa_seguridad
//	Returns:      $lb_valido= Variable booleana que devuelve true si la sentencia
//                SQL fue ejecutada sin errores de lo contrario devuelve false. 
//	Description:  Función que se encarga de eliminar detalles de deducciones  
//                dentro de la tabla cxp_rd_deducciones para una recepción.    
//////////////////////////////////////////////////////////////////////////////
	$ls_sql=" DELETE ".
		    " FROM   cxp_rd_deducciones ".
		    " WHERE  codemp='".$as_codemp."' AND numrecdoc='".$as_compromiso."'";
	$li_numrows=$this->io_sql->execute($ls_sql);
	if ($li_numrows===false)
	  {
		$this->io_sql->rollback();
   	    $this->io_msg->message("CLASE->SIGESP_CXP_C_RECEP_DOC; METODO->uf_delete_dtded; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
      }
	else
	  {
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	    $ls_evento="DELETE";
	    $ls_descripcion ="Eliminó en CXP Detalle de Deducciones ".$as_compromiso;
	    $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	    $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	    $aa_seguridad["ventanas"],$ls_descripcion);
	    /////////////////////////////////         SEGURIDAD               ////////////////////////////
		$lb_valido=true;
	  }
return $lb_valido;
}

function uf_delete_dtotroscre($as_codemp,$as_compromiso,$aa_seguridad)
{
//////////////////////////////////////////////////////////////////////////////
//	Método:       uf_delete_dtotroscre
//	Access:       public
//	Arguments:    $as_codemp,$as_compromiso,$aa_seguridad
//	Returns:      $lb_valido= Variable booleana que devuelve true si la sentencia
//                SQL fue ejecutada sin errores de lo contrario devuelve false. 
//	Description:  Función que se encarga de eliminar detalles de Otros Créditos  
//                dentro de la tabla cxp_rd_cargos para una recepción.    
//////////////////////////////////////////////////////////////////////////////
	$ls_sql=" DELETE FROM cxp_rd_cargos WHERE  codemp='".$as_codemp."' AND numrecdoc='".$as_compromiso."'";
	$li_numrows=$this->io_sql->execute($ls_sql);
	if ($li_numrows===false)
	  {
		$this->io_sql->rollback();
   	    $this->io_msg->message("CLASE->SIGESP_CXP_C_RECEP_DOC; METODO->uf_delete_dtotroscre; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
	  }
	else
	  {
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	    $ls_evento="DELETE";
	    $ls_descripcion ="Eliminó en CXP Detalle de Otros Créditos ".$as_compromiso;
	    $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
	    $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
	    $aa_seguridad["ventanas"],$ls_descripcion);
	    /////////////////////////////////         SEGURIDAD               ////////////////////////////
		$lb_valido=true;
	  }
	return $lb_valido;
}

function uf_llenarcombo_concepto()
{
//////////////////////////////////////////////////////////////////////////////
//	Método:       uf_llenarcombo_concepto
//	Access:       public
//	Returns:      $rs = Resulset con los valores resultantes de la Consulta.	
//	Description:  Función que se encarga de buscar registros  
//                dentro de la tabla cxp_clasificador_rd para una recepción
//                para llenar el combo.    
//////////////////////////////////////////////////////////////////////////////
	$ls_sql=" SELECT * FROM cxp_clasificador_rd ORDER BY codcla ASC";
	$rs=$this->io_sql->select($ls_sql);
	return $rs;
}

function uf_load_dtcargos_comprobantesoc($as_codemp,$as_comprobante,&$lb_valido)
{
	$ls_sql ="SELECT a.*,b.porcar FROM soc_solicitudcargos a,sigesp_cargos b ".
	         " WHERE a.codemp='".$as_codemp."' AND a.numordcom='".$as_comprobante."' AND a.codcar=b.codcar"; 
	$rs_data=$this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {	
	     $lb_valido=false;
		 $this->io_msg->message("CLASE->SIGESP_CLASS_C_RECEP_DOC; METODO->uf_load_dtcargos_comprobantesoc; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   }
	 else
	   {
	     $lb_valido=true;
	   }
return $rs_data;
}

function uf_load_dtcargos_comprobantesep($as_codemp,$as_numsol,&$lb_valido)
{
	$ls_sql=" SELECT a.*,b.porcar FROM sep_solicitudcargos a, sigesp_cargos b ".
	        "  WHERE a.codemp='".$as_codemp."' AND a.numsol='".$as_numsol."' AND a.codcar=b.codcar"; 
	$rs_data=$this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {	
	     $lb_valido=false;
		 $this->io_msg->message("CLASE->SIGESP_CLASS_C_RECEP_DOC; METODO->uf_load_dtcargos_comprobantesep; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   }
	 else
	   {
	     $lb_valido=true;
	   }
return $rs_data;
}

function uf_load_estatus($as_codemp,$as_numrecdoc,$as_codtipdoc,$as_cedbene,$as_codpro,&$as_estprodoc)
{
  $ls_sql=" SELECT estprodoc FROM cxp_rd ".
		  " WHERE codemp='".$as_codemp."' AND numrecdoc='".$as_numrecdoc."' AND ".
          " codtipdoc='".$as_codtipdoc."' AND ced_bene='".$as_cedbene."' AND cod_pro='".$as_codpro."'";
  $rs_data=$this->io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $lb_valido=false;
	   $this->io_msg->message("CLASE->SIGESP_CLASS_C_RECEP_DOC; METODO->uf_load_estatus;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
     {
	   if ($row=$this->io_sql->fetch_row($rs_data))
	      {
		    $as_estprodoc=$row["estprodoc"];
		  }
	   $lb_valido=true;
	 }
return $lb_valido;
}

function uf_select_rd_cargos($as_codemp,$as_numrecdoc,$as_codtipdoc,$as_codpro,$as_cedbene,$as_codcar,$as_procede,$as_numdoccom,&$ab_existe)
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_select_cargos
//	          Access:  public
//	        Arguments   
//        $as_codemp:  Código de la Empresa.
//     $as_numrecdoc:  Número de la Recepción de Documento.
//     $as_codtipdoc:  Denominación de la Clasificación.
//        $as_codpro:  Código del Proveedor.
//       $as_cedbene:  Cédula del Beneficiario.
//        $as_codcar:  Código del Cargo.
//       $as_procede:  Procedencia del Documento.
//     $as_numdoccom:  Número del Comprobante.
//        $ab_existe:  Variable booloeana que se pasa por referencia, devuelve true si el registro es encontrado, caso contrario false.
//     $aa_seguridad:  Arreglo cargado con la información acerca de la ventana,usuario,etc.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de insertar una nueva clasificacion en la tabla rpc_clasificacion. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  23/03/2006       Fecha Última Actualización:23/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 
  $ab_existe=false;
  $ls_sql=" SELECT * FROM cxp_rd_cargos ".
          " WHERE codemp='".$as_codemp."' AND numrecdoc='".$as_numrecdoc."' AND codtipdoc='".$as_codtipdoc."' AND cod_pro='".$as_codpro."' AND ".
		  " ced_bene='".$as_cedbene."' AND codcar='".$as_codcar."' AND procede_doc='".$as_procede."' AND numdoccom='".$as_numdoccom."'";
  $rs_data=$this->io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	  $this->io_msg->message("CLASE->SIGESP_CLASS_C_RECEP_DOC; METODO->uf_select_rd_cargos;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));   
	 }
  else
     {
	   $li_numrows=$this->io_sql->num_rows($rs_data);
	   if ($li_numrows>0)
	      {
		    $ab_existe=true;
		  }
	 }
return $rs_data;
}

function uf_select_rd_deducciones($as_codemp,$as_numrecdoc,$as_codpro,$as_cedbene,$as_codded,&$ab_existe)
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_select_rd_deducciones
//	          Access:  public
//	        Arguments   
//        $as_codemp:  Código de la Empresa.
//     $as_numrecdoc:  Número de la Recepción de Documento.
//     $as_codtipdoc:  Denominación de la Clasificación.
//        $as_codpro:  Código del Proveedor.
//       $as_cedbene:  Cédula del Beneficiario.
//        $as_codcar:  Código del Cargo.
//       $as_procede:  Procedencia del Documento.
//     $as_numdoccom:  Número del Comprobante.
//        $ab_existe:  Variable booloeana que se pasa por referencia, devuelve true si el registro es encontrado, caso contrario false.
//     $aa_seguridad:  Arreglo cargado con la información acerca de la ventana,usuario,etc.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de insertar una nueva clasificacion en la tabla rpc_clasificacion. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  24/03/2006       Fecha Última Actualización:24/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 
  $ab_existe=false;
  $ls_sql=" SELECT * FROM cxp_rd_deducciones ".
          " WHERE codemp='".$as_codemp."' AND numrecdoc='".$as_numrecdoc."' AND cod_pro='".$as_codpro."' AND ced_bene='".$as_cedbene."' AND codded='".$as_codded."'"; 
  $rs_data=$this->io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $ab_existe=false;
	   $this->io_msg->message("CLASE->SIGESP_CLASS_C_RECEP_DOC; METODO->uf_select_rd_deducciones;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));   
	 }
  else
     {
	   $li_numrows=$this->io_sql->num_rows($rs_data);
	   if ($li_numrows>0)
	      {
		    $ab_existe=true;
		  }
	 }
return $rs_data;
}

function uf_load_dt_orden_compra($as_codemp,$as_numordcom,$as_estcondat,&$lb_valido)
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_load_dt_orden_compra
//	          Access:  public
//	        Arguments   
//        $as_codemp:  Código de la Empresa.
//     $as_numordcom:  Número de la Orden de Compra.
//     $aa_seguridad:  Arreglo cargado con la información acerca de la ventana,usuario,etc.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de cargar todos los detalles asociados a una orden de compra. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  10/04/2006       Fecha Última Actualización:10/04/2006.	 
////////////////////////////////////////////////////////////////////////////// 
 
   $ls_gestor = $_SESSION["ls_gestor"];
   switch ($ls_gestor){
   case 'MYSQLT':
	  $ls_cadena=" CONCAT(soc_solicitudcargos.codestpro1,soc_solicitudcargos.codestpro2,soc_solicitudcargos.codestpro3,soc_solicitudcargos.codestpro4,soc_solicitudcargos.codestpro5)= ".
				 " CONCAT(soc_cuentagasto.codestpro1,soc_cuentagasto.codestpro2,soc_cuentagasto.codestpro3,soc_cuentagasto.codestpro4,soc_cuentagasto.codestpro5) ";
	  $ls_where =" AND CONCAT(soc_cuentagasto.codestpro1,soc_cuentagasto.codestpro2,soc_cuentagasto.codestpro3,soc_cuentagasto.codestpro4,soc_cuentagasto.codestpro5)= ".
				 " CONCAT(spg_cuentas.codestpro1,spg_cuentas.codestpro2,spg_cuentas.codestpro3,spg_cuentas.codestpro4,spg_cuentas.codestpro5) ";
	  break;
   case 'ORACLE':
	  $ls_cadena = " soc_solicitudcargos.codestpro1||soc_solicitudcargos.codestpro2||soc_solicitudcargos.codestpro3||soc_solicitudcargos.codestpro4||soc_solicitudcargos.codestpro5= ".
				   " soc_cuentagasto.codestpro1||soc_cuentagasto.codestpro2||soc_cuentagasto.codestpro3||soc_cuentagasto.codestpro4||soc_cuentagasto.codestpro5";
	  $ls_where  = " AND soc_cuentagasto.codestpro1||soc_cuentagasto.codestpro2||soc_cuentagasto.codestpro3||soc_cuentagasto.codestpro4||soc_cuentagasto.codestpro5= ".
				   "     spg_cuentas.codestpro1||spg_cuentas.codestpro2||spg_cuentas.codestpro3||spg_cuentas.codestpro4||spg_cuentas.codestpro5";
	  break;
   case 'POSTGRES':
	  $ls_cadena = " (soc_solicitudcargos.codestpro1||soc_solicitudcargos.codestpro2||soc_solicitudcargos.codestpro3||soc_solicitudcargos.codestpro4||soc_solicitudcargos.codestpro5)= ".
				   " (soc_cuentagasto.codestpro1||soc_cuentagasto.codestpro2||soc_cuentagasto.codestpro3||soc_cuentagasto.codestpro4||soc_cuentagasto.codestpro5) ";
	  $ls_where	 = " AND (soc_cuentagasto.codestpro1||soc_cuentagasto.codestpro2||soc_cuentagasto.codestpro3||soc_cuentagasto.codestpro4||soc_cuentagasto.codestpro5)= ".
				   " (spg_cuentas.codestpro1||spg_cuentas.codestpro2||spg_cuentas.codestpro3||spg_cuentas.codestpro4||spg_cuentas.codestpro5)";
	  break;	    
   case 'ANYWHERE':
	  $ls_cadena=" CONCAT(soc_solicitudcargos.codestpro1+soc_solicitudcargos.codestpro2+soc_solicitudcargos.codestpro3+soc_solicitudcargos.codestpro4+soc_solicitudcargos.codestpro5) = ".
		"          CONCAT(soc_cuentagasto.codestpro1+soc_cuentagasto.codestpro2+soc_cuentagasto.codestpro3+soc_cuentagasto.codestpro4+soc_cuentagasto.codestpro5) ";
	  $ls_where =" CONCAT(soc_cuentagasto.codestpro1+soc_cuentagasto.codestpro2+soc_cuentagasto.codestpro3+soc_cuentagasto.codestpro4+soc_cuentagasto.codestpro5)= ".
				 " CONCAT(spg_cuentas.codestpro1+spg_cuentas.codestpro2+spg_cuentas.codestpro3+spg_cuentas.codestpro4+spg_cuentas.codestpro5)  ";
	  break;
   }
  if( ($as_estcondat=="B") || ($as_estcondat=="-") || ($as_estcondat=="") )
  {
		$ls_sql="SELECT soc_cuentagasto.numordcom,soc_cuentagasto.codestpro1,soc_cuentagasto.codestpro2,soc_cuentagasto.codestpro3,soc_cuentagasto.codestpro4,soc_cuentagasto.codestpro5,soc_cuentagasto.spg_cuenta,soc_cuentagasto.estcla,
					   COALESCE(soc_solicitudcargos.monobjret,0) as baseimp ,COALESCE(soc_solicitudcargos.monret,soc_cuentagasto.monto) as monto,spg_cuentas.sc_cuenta,soc_solicitudcargos.codcar
				FROM   spg_cuentas,soc_cuentagasto 
				LEFT   OUTER JOIN soc_solicitudcargos
				ON     (soc_solicitudcargos.numordcom=soc_cuentagasto.numordcom AND ".$ls_cadena." AND soc_solicitudcargos.spg_cuenta=soc_cuentagasto.spg_cuenta AND soc_solicitudcargos.estcla=soc_cuentagasto.estcla)
				WHERE  soc_cuentagasto.numordcom='".$as_numordcom."' AND soc_cuentagasto.codemp='".$as_codemp."' AND (soc_cuentagasto.estcondat='B' OR soc_cuentagasto.estcondat='-' OR soc_cuentagasto.estcondat='' ) AND soc_cuentagasto.spg_cuenta=spg_cuentas.spg_cuenta AND soc_cuentagasto.estcla=spg_cuentas.estcla ".$ls_where."
				ORDER BY soc_cuentagasto.numordcom";
				
  }			  
  if($as_estcondat=="S") 
  {
  	  $ls_sql="SELECT soc_cuentagasto.numordcom,soc_cuentagasto.codestpro1,soc_cuentagasto.codestpro2,soc_cuentagasto.codestpro3,soc_cuentagasto.codestpro4,soc_cuentagasto.codestpro5,soc_cuentagasto.spg_cuenta,soc_cuentagasto.estcla,
					   COALESCE(soc_solicitudcargos.monobjret,0) as baseimp ,COALESCE(soc_solicitudcargos.monret,soc_cuentagasto.monto) as monto,spg_cuentas.sc_cuenta,soc_solicitudcargos.codcar
				FROM   spg_cuentas,soc_cuentagasto
				LEFT   OUTER JOIN soc_solicitudcargos
				ON     (soc_solicitudcargos.numordcom=soc_cuentagasto.numordcom AND ".$ls_cadena." AND soc_solicitudcargos.spg_cuenta=soc_cuentagasto.spg_cuenta AND soc_solicitudcargos.estcla=soc_cuentagasto.estcla)
				WHERE  soc_cuentagasto.numordcom='".$as_numordcom."' AND soc_cuentagasto.codemp='".$as_codemp."' AND (soc_cuentagasto.estcondat='S') AND soc_cuentagasto.spg_cuenta=spg_cuentas.spg_cuenta AND soc_cuentagasto.estcla=spg_cuentas.estcla ".$ls_where."
				ORDER BY numordcom";
				
  }	  

  $rs_data=$this->io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $lb_valido=false;
       $this->io_msg->message("CLASE->SIGESP_CXP_RECEP_DOC; METODO->uf_load_dt_orden_compra; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
     	print ($this->io_sql->message);
	 }
  else
     {
	   $lb_valido=true;
     }
return $rs_data;
}

function uf_load_dtotros_sep($as_codemp,$as_numsol,&$lb_valido)
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_load_dtotros_sep
//	          Access:  public
//	        Arguments   
//        $as_codemp:  Código de la Empresa.
//        $as_numsol:  Número de la Solicitud.
//     $aa_seguridad:  Arreglo cargado con la información acerca de la ventana,usuario,etc.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de extraer todos los detalles de Servicios asociados a una SEP. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  07/04/2006       Fecha Última Actualización:07/04/2006.	 
////////////////////////////////////////////////////////////////////////////// 
   $ls_gestor = $_SESSION["ls_gestor"];
   switch ($ls_gestor){
       case 'MYSQLT':
	      $ls_cadena = "CONCAT(sep_solicitudcargos.codestpro1,sep_solicitudcargos.codestpro2,sep_solicitudcargos.codestpro3,sep_solicitudcargos.codestpro4,sep_solicitudcargos.codestpro5)=".
		               "CONCAT(sep_cuentagasto.codestpro1,sep_cuentagasto.codestpro2,sep_cuentagasto.codestpro3,sep_cuentagasto.codestpro4,sep_cuentagasto.codestpro5)";
	      $ls_sqlaux = "CONCAT(sep_cuentagasto.codestpro1,sep_cuentagasto.codestpro2,sep_cuentagasto.codestpro3,sep_cuentagasto.codestpro4,sep_cuentagasto.codestpro5)=
		                CONCAT(spg_cuentas.codestpro1,spg_cuentas.codestpro2,spg_cuentas.codestpro3,spg_cuentas.codestpro4,spg_cuentas.codestpro5)";
		  break;
	   case 'ORACLE':
	      $ls_cadena = " sep_solicitudcargos.codestpro1||sep_solicitudcargos.codestpro2||sep_solicitudcargos.codestpro3||sep_solicitudcargos.codestpro4||sep_solicitudcargos.codestpro5=".
		               " sep_cuentagasto.codestpro1||sep_cuentagasto.codestpro2||sep_cuentagasto.codestpro3||sep_cuentagasto.codestpro4||sep_cuentagasto.codestpro5";
	      $ls_sqlaux = " sep_cuentagasto.codestpro1||sep_cuentagasto.codestpro2||sep_cuentagasto.codestpro3||sep_cuentagasto.codestpro4||sep_cuentagasto.codestpro5=
		                 spg_cuentas.codestpro1||spg_cuentas.codestpro2||spg_cuentas.codestpro3||spg_cuentas.codestpro4||spg_cuentas.codestpro5";
		  break;
	   case 'POSTGRES':
	      $ls_cadena = " sep_solicitudcargos.codestpro1||sep_solicitudcargos.codestpro2||sep_solicitudcargos.codestpro3||sep_solicitudcargos.codestpro4||sep_solicitudcargos.codestpro5=".
		               " sep_cuentagasto.codestpro1||sep_cuentagasto.codestpro2||sep_cuentagasto.codestpro3||sep_cuentagasto.codestpro4||sep_cuentagasto.codestpro5";
	      $ls_sqlaux = " sep_cuentagasto.codestpro1||sep_cuentagasto.codestpro2||sep_cuentagasto.codestpro3||sep_cuentagasto.codestpro4||sep_cuentagasto.codestpro5=
		                 spg_cuentas.codestpro1||spg_cuentas.codestpro2||spg_cuentas.codestpro3||spg_cuentas.codestpro4||spg_cuentas.codestpro5";
		  break;	    
	   case 'ANYWHERE':
	      $ls_cadena = " sep_solicitudcargos.codestpro1+sep_solicitudcargos.codestpro2+sep_solicitudcargos.codestpro3+sep_solicitudcargos.codestpro4+sep_solicitudcargos.codestpro5=".
			           " sep_cuentagasto.codestpro1+sep_cuentagasto.codestpro2+sep_cuentagasto.codestpro3+sep_cuentagasto.codestpro4+sep_cuentagasto.codestpro5";
	      $ls_sqlaux = " sep_cuentagasto.codestpro1+sep_cuentagasto.codestpro2+sep_cuentagasto.codestpro3+sep_cuentagasto.codestpro4+sep_cuentagasto.codestpro5=
		                 spg_cuentas.codestpro1+spg_cuentas.codestpro2+spg_cuentas.codestpro3+spg_cuentas.codestpro4+spg_cuentas.codestpro5";
		  break;
   }
   $ls_sql = "SELECT sep_cuentagasto.numsol, sep_cuentagasto.codestpro1, sep_cuentagasto.codestpro2, sep_cuentagasto.codestpro3,
				     sep_cuentagasto.codestpro4, sep_cuentagasto.codestpro5, sep_cuentagasto.spg_cuenta, sep_cuentagasto.estcla,
				     COALESCE(sep_solicitudcargos.monobjret,0) as baseimp , COALESCE(sep_solicitudcargos.monret,sep_cuentagasto.monto) as monto,
				     spg_cuentas.sc_cuenta, sep_solicitudcargos.codcar
			    FROM spg_cuentas, sep_cuentagasto
			    LEFT OUTER JOIN sep_solicitudcargos
				  ON (sep_solicitudcargos.numsol=sep_cuentagasto.numsol
			     AND $ls_cadena
			     AND sep_solicitudcargos.spg_cuenta=sep_cuentagasto.spg_cuenta 
			     AND sep_solicitudcargos.estcla=sep_cuentagasto.estcla)
			   WHERE sep_cuentagasto.codemp='".$_SESSION["la_empresa"]["codemp"]."'
			     AND sep_cuentagasto.numsol='".$as_numsol."' 
			     AND sep_cuentagasto.spg_cuenta=spg_cuentas.spg_cuenta 
			     AND sep_cuentagasto.estcla=spg_cuentas.estcla
			     AND $ls_sqlaux
			   ORDER BY sep_cuentagasto.numsol;";
  $rs_data = $this->io_sql->select($ls_sql);//echo $ls_sql.'<br>';
  if ($rs_data===false)
     {
	   $lb_valido=false;
       $this->io_msg->message("CLASE->SIGESP_CXP_RECEP_DOC; METODO->uf_load_dtotros_sep; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
       echo $this->io_sql->message;
	 }
  else
     {
	   $lb_valido=true;
     }
  return $rs_data;
}

function uf_load_datos_cargo($as_codemp,$as_codcar,&$lb_valido)
{
  $ls_sql=" SELECT codestpro,spg_cuenta FROM sigesp_cargos WHERE codemp='".$as_codemp."' AND codcar='".$as_codcar."'";
  $rs_data=$this->io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $lb_valido=false;
	   $this->io_msg->message("CLASE->SIGESP_CXP_RECEP_DOC; METODO->uf_load_datos_cargo; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
     {
	   $lb_valido=true;
	 }	 		  
return $rs_data;
} 

function uf_load_sccuenta_proveedor($as_codemp,$as_tabla,$as_columna,$as_codproben,&$lb_valido)
{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_load_sccuenta_proveedor
//	          Access:  public
//	        Arguments   
//        $as_codemp:  Código de la Empresa.
//        $as_numdoc:  Número de la Orden de Compra.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de extraer el codigo de la cuenta contable asociada al documento del proveedor. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  06/04/2006       Fecha Última Actualización:06/04/2006.	 
////////////////////////////////////////////////////////////////////////////// 
  $ls_sql  = " SELECT sc_cuenta FROM $as_tabla WHERE codemp='".$as_codemp."' AND $as_columna='".$as_codproben."'";
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $lb_valido=false;
	   $this->io_msg->message("CLASE->SIGESP_CXP_RECEP_DOC; METODO->uf_load_sccuenta_proveedor; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
     {
	   $lb_valido=true;
	 }	 		  
return $rs_data;
} 

function uf_load_tiposep($as_codemp,$as_numsol,&$lb_valido)
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_load_tiposep
//	          Access:  public
//	        Arguments   
//        $as_codemp:  Código de la Empresa.
//        $as_numdoc:  Número de la Orden de Compra.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de extraer el Tipo de la Solicitud Presupuestaria. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  06/04/2006       Fecha Última Actualización:06/04/2006.	 
////////////////////////////////////////////////////////////////////////////// 
  $ls_sql=" SELECT a.modsep ".
          " FROM sep_tiposolicitud a,sep_solicitud b ".
		  " WHERE b.codemp='".$as_codemp."' AND a.codtipsol=b.codtipsol AND b.numsol='".$as_numsol."'";
  $rs_data=$this->io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $lb_valido=false;
	 }
  else
     {
	   if ($row_sep=$this->io_sql->fetch_row($rs_data))
	      {
		    $ls_tiposep=$row_sep["modsep"];
		  }
	   $lb_valido=true;
	 }
return $ls_tiposep;
}

function uf_load_cuentascg($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_spgcuenta,&$lb_valido)
{
//////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_load_cuentascg
//	          Access:  public
//	        Arguments   
//    $as_codestpro1:  Código de la Estructura Presupuestaria número 1.
//    $as_codestpro2:  Código de la Estructura Presupuestaria número 2.
//    $as_codestpro3:  Código de la Estructura Presupuestaria número 3.
//    $as_codestpro4:  Código de la Estructura Presupuestaria número 4.
//    $as_codestpro5:  Código de la Estructura Presupuestaria número 5.
//     $as_spgcuenta:  Cuenta Presupuestaria asociada a la Estructura Presupuestaria.  
//     $aa_seguridad:  Arreglo cargado con la información acerca de la ventana,usuario,etc.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de extraer la Cuenta Contable asociada a una Estructura Presupuestaria. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  24/03/2006       Fecha Última Actualización:24/03/2006.	 
////////////////////////////////////////////////////////////////////////////// 
  $ls_sccuenta="";
  $ls_sql=" SELECT sc_cuenta ".
          " FROM spg_cuentas ".
		  " WHERE codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' AND ".
		  "       codestpro4='".$as_codestpro4."' AND codestpro5='".$as_codestpro5."' AND spg_cuenta='".$as_spgcuenta."'";
  $rs_data=$this->io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $lb_valido=false; 
	   $this->io_msg->message("CLASE->SIGESP_CXP_RECEP_DOC; METODO->uf_load_cuenta_contable; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
     {
	   if ($row=$this->io_sql->fetch_row($rs_data))
	      {
		    $ls_sccuenta=$row["sc_cuenta"];  
		    $lb_valido=true;
		  }
	   else
	      {
		    $lb_valido=false;
		  }
	 }
return $ls_sccuenta;
}

function check_cargo($as_codemp,$as_cuentapre)
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Metodo:  check_cargo
//	          Access:  public
//	        Arguments   
//        $as_codemp:  Código de la Empresa.
//     $as_cuentapre:  Número de la Cuenta Presupuestaria.
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de varificar si dicha cuenta presupúestaria pertenece a un cargo. 
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  17/08/2006       Fecha Última Actualización:17/08/2006.	 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
  $lb_valido = false;
  $ls_sql    = " SELECT * FROM sigesp_cargos WHERE codemp='".$as_codemp."' AND spg_cuenta='".$as_cuentapre."'";
  $rs_data=$this->io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $lb_valido = false;
	   $this->io_msg->message("CLASE->SIGESP_CXP_RECEP_DOC; METODO->check_cargo; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
     {
	   if ($row=$this->io_sql->fetch_row($rs_data))
	      {
	        $lb_valido = true;
		  }
	 }	 		  
return $lb_valido;
}

function uf_load_tipo_solicitud($as_numsol)
{
  $ls_sql  = "SELECT sep_tiposolicitud.modsep
			    FROM sep_tiposolicitud, sep_solicitud
			   WHERE sep_solicitud.codemp = '".$_SESSION["la_empresa"]["codemp"]."'
			     AND sep_solicitud.numsol = '".$as_numsol."'
			     AND sep_solicitud.codemp = sep_tiposolicitud.codemp
			     AND sep_solicitud.codtipsol = sep_tiposolicitud.codtipsol;";
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $lb_valido=false; 
	   $this->io_msg->message("CLASE->SIGESP_CXP_RECEP_DOC; METODO->uf_load_tipo_solicitud; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   echo $this->io_sql->message;
	 }
  else
     {
	   if ($row=$this->io_sql->fetch_row($rs_data))
	      {
		    $ls_tipsol = $row["modsep"];  
		  }
	 }
  return $ls_tipsol;
}
}//Fin de la Clase...
?>