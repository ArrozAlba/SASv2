<?php
class sigesp_scb_c_progpago_creditos{

  function sigesp_scb_c_progpago_creditos($as_path)
  {
    require_once($as_path."shared/class_folder/class_sql.php");
	require_once($as_path."shared/class_folder/class_mensajes.php");
	require_once($as_path."shared/class_folder/sigesp_include.php");
	require_once($as_path."shared/class_folder/class_funciones.php");
	require_once($as_path."shared/class_folder/sigesp_c_seguridad.php");
	require_once($as_path."shared/class_folder/class_funciones_xml.php");
	
	$io_include = new sigesp_include();
	$ls_conect  = $io_include->uf_conectar();
	$this->io_sql = new class_sql($ls_conect);
	$this->io_msg = new class_mensajes();
	$this->io_xml = new class_funciones_xml();
	$this->ls_codemp    = $_SESSION["la_empresa"]["codemp"];
	$this->io_funcion   = new class_funciones();
	$this->io_seguridad = new sigesp_c_seguridad();
  }

	function uf_load_solicitudes_pago($as_rutfil,&$li_totrow)
	{
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	     Function: uf_load_movimientos_bancarios
	  //		   Access: private
	  //	    Arguments: $as_rutfil = Ruta del directorio de donde se cargarán los archivos xml.
	  //	      Returns: Arreglo cargado con los archivos xml ubicados en $as_rutfil para ser procesados.
	  //	  Description: Carga la cabecera del Movimiento bancario a partir de los archivos xml ubicados en $as_rutfil.
	  //	   Creado Por: Ing. Nestor Falcón.
	  //   Fecha Creación: 07/07/2008. 							Fecha Última Modificación : 07/07/2008.
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	  $la_filnam = $this->io_xml->uf_load_archivos($as_rutfil);
	  if (!empty($la_filnam))
	     {
		   $li_i = 0;
		   $li_totrow = count($la_filnam["filnam"]);
		   for ($li_y=1;$li_y<=$li_totrow;$li_y++)
		       {
			     $ls_filnam = $la_filnam["filnam"][$li_y];
				 $la_datmov = $this->io_xml->uf_cargar_solicitudes_pago($as_rutfil.'/'.$ls_filnam);
				 if (!empty($la_datmov))
				    {
				      $li_i++;
					  $ls_cedben = $ls_codben = $la_datmov[1]['ced_bene'];
				      $ls_numsol = $la_datmov[1]['numsol'];
					  $ld_monsol = number_format($la_datmov[1]['monto'],2,',','.');
					  $ls_fecliq = $this->io_funcion->uf_convertirfecmostrar($la_datmov[1]['fecliq']);
					  $ls_estsol = $la_datmov[1]['estatus'];
					  $lb_exiben = $this->io_xml->uf_validar_beneficiario($this->ls_codemp,$ls_cedben);
					  if ($lb_exiben)
						 {
					       $ls_nomben = $this->uf_load_nombre_beneficiario($ls_cedben);
 						   if (!empty($ls_nomben))
						      {
							    $ls_cedben = $ls_cedben.' - '.$ls_nomben;
							  }
						 }
				      $la_object[$li_i][1] = "<a href=\"javascript: uf_aceptar('$ls_numsol','$ls_codben','$ls_nomben','$ld_monsol','$ls_fecliq','$ls_estsol','$ls_filnam');\">".$ls_numsol."</a>";
					  $la_object[$li_i][2] = "<input type=text     name=txtcedben".$li_i." id=txtcedben".$li_i." value='".$ls_cedben."' class=sin-borde readonly style=text-align:left    size=55 maxlength=254 title='".$ls_cedben."'>";
					  $la_object[$li_i][3] = "<input type=text     name=txtmonsol".$li_i." id=txtmonsol".$li_i." value='".$ld_monsol."' class=sin-borde readonly style=text-align:right   size=15 maxlength=254 title='".$ld_monsol."'>";
					  $la_object[$li_i][4] = "<input type=text     name=txtfecliq".$li_i." id=txtfecliq".$li_i." value='".$ls_fecliq."' class=sin-borde readonly style=text-align:center  size=18 maxlength=18>
											  <input type=hidden   name=txtfilnam".$li_i." id=txtfilnam".$li_i." value='".$ls_filnam."'>";					
					}
			   }  
		 }
	  else
	     {
	       $li_totrow = 1;
	       $la_object[$li_totrow][1] = "";
	       $la_object[$li_totrow][2] = "<input type=text     name=txtcedben".$li_totrow." id=txtcedben".$li_totrow." value='' class=sin-borde readonly style=text-align:left    size=40 maxlength=254>";
	       $la_object[$li_totrow][3] = "<input type=text     name=txtmonsol".$li_totrow." id=txtmonsol".$li_totrow." value='' class=sin-borde readonly style=text-align:left    size=45 maxlength=254>";
	       $la_object[$li_totrow][4] = "<input type=text     name=txtfecliq".$li_totrow." id=txtfecliq".$li_totrow." value='' class=sin-borde readonly style=text-align:center  size=18 maxlength=18>
								        <input type=hidden   name=txtfilnam".$li_totrow." id=txtfilnam".$li_totrow." value=''>";		 
		 }
	  return $la_object;
	}

    function uf_load_nombre_beneficiario($as_cedben)
	{
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	     Function: uf_load_denominaciones
	  //		   Access: private
	  //	    Arguments: $as_cedben = Cédula del Beneficiario.
	  //	      Returns: Arreglo cargado con los archivos xml ubicados en $as_rutfil para ser procesados.
	  //	  Description: Carga el nombre asociado al beneficiario de la Solicitud de Desembolso.
	  //	   Creado Por: Ing. Néstor Falcón.
	  //   Fecha Creación: 07/07/2008. 							Fecha Última Modificación : 07/07/2008.
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	  $ls_sql = "SELECT nombene, apebene
	               FROM rpc_beneficiario
				  WHERE codemp='".$this->ls_codemp."' 
				    AND trim(ced_bene)='".trim($as_cedben)."'";	
	  $rs_data = $this->io_sql->select($ls_sql);
	  if ($rs_data===false)
	     {
		   $lb_valido = false;
		   $this->io_msg->message("CLASE->sigesp_scb_c_progpago_creditos.php->MÉTODO->uf_load_nombre_beneficiario;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		   echo $this->io_sql->message;
		 }
	  else
	     {
		   if ($row=$this->io_sql->fetch_row($rs_data))
		      {
			    $ls_nomben = $row["nombene"];
			    $ls_apeben = $row["apebene"];
				if (!empty($ls_apeben))
				   {
				     $ls_nomben = $ls_nomben.', '.$ls_apeben;
				   }
			    unset($rs_data);
			  }
		 }
	  return $ls_nomben;
	}
	
	function uf_load_detalles_desembolso($as_filnam,&$li_totdet)
	{ 
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	     Function: uf_load_detalles_desembolso
	  //		   Access: private
	  //	    Arguments: $as_filnam = Nombre del archivo xml a procesar con toda su ruta de ubicación.
	  //                   $la_object = Matriz cargada con la información de los detalles de la liquidación.
	  //                   $li_totdet = Número total de filas de los detalles de la liquidación contenidos en el xml.
	  //	      Returns: Arreglo cargado con los archivos xml ubicados en $as_rutfil para ser procesados.
	  //	  Description: Carga los detalles de la liquidación a partir del archivo xml $as_filnam.
	  //	   Creado Por: Ing. Nestor Falcón.
	  //   Fecha Creación: 07/07/2008. 							Fecha Última Modificación : 07/07/2008.
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	  $li_totdet = 0;
	  if (!empty($as_filnam))
	     {
		   $la_datmov = $this->io_xml->uf_cargar_detalles_desembolso($as_filnam);
		   if (!empty($la_datmov))
			  {
			    $li_totdet    = count($la_datmov);
				$ld_montotdes = 0;//Acumulador para la sumatoria de los Detalles del Desembolso.
				for ($li_i=1;$li_i<=$li_totdet;$li_i++)
				    {
				      $ls_numsoldes = $la_datmov[$li_i]['numsol'];//Número de la Solicitud de Desembolso.
					  $ls_nombenalt = $la_datmov[$li_i]['nombenalt'];
					  $ld_mondetdes = $la_datmov[$li_i]['mondet'];//Monto del detalle del desembolso.
					  $ld_montotdes += $ld_mondetdes;
					  $ld_mondetdes = number_format($ld_mondetdes,2,',','.');
							
					  $la_object[$li_i][1] = "<input type=text    name=txtbenalt".$li_i." id=txtbenalt".$li_i." value='".$ls_nombenalt."' class=sin-borde readonly style=text-align:left    size=20 maxlength=15>";
					  $la_object[$li_i][2] = "<a href=javascript:uf_catalogo_banco(".$li_i.")><img src=../shared/imagebank/tools15/buscar.gif title=Buscar Banco Liquidación  style=position:absolute border=0></a><input type=text name=txtcodban".$li_i." id=txtcodban".$li_i." value=''  class=sin-borde readonly style=text-align:left  size=40 maxlength=254>";
					  $la_object[$li_i][3] = "<a href=javascript:uf_catalogo_cuenta_banco(".$li_i.")><img src=../shared/imagebank/tools15/buscar.gif title=Buscar Cuenta Banco Liquidación style=position:absolute border=0></a><input type=text name=txtctaban".$li_i." id=txtctaban".$li_i." value=''  class=sin-borde readonly style=text-align:left  size=45 maxlength=254>";
					  $la_object[$li_i][4] = "<input type=text    name=txtmonsol".$li_i." id=txtmonsol".$li_i." value='".$ld_mondetdes."' class=sin-borde readonly style=text-align:right  size=18 maxlength=18>
											  <input type=hidden  name=txtfilnam".$li_i." id=txtfilnam".$li_i." value='".$as_filnam."'>
											  <input type=hidden  name=hidcodban".$li_i." id=hidcodban".$li_i." value=''>
											  <input type=hidden  name=hidnomban".$li_i." id=hidnomban".$li_i." value=''>											  
											  <input type=hidden  name=hidctaban".$li_i." id=hidctaban".$li_i." value=''>
											  <input type=hidden  name=hidnumsol".$li_i." id=hidnumsol".$li_i." value='".$ls_numsoldes."'>";
				    }					  
	          }
	       if (isset($la_datmov))
		      {
			    unset($la_datmov);
			  }		   
		 }	  
	  return $la_object;
	}
	
	function uf_procesar_programacion($as_filnam,$ai_totrow,$aa_seguridad)
	{
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	     Function: uf_procesar_programacion
	  //		   Access: private
	  //	    Arguments: $as_filnam
	  //                   $ai_totrow
	  //                   $aa_seguridad
	  //	      Returns: .
	  //	  Description: .
	  //	   Creado Por: Ing. Nestor Falcón.
	  //   Fecha Creación: 07/07/2008. 							Fecha Última Modificación : 07/07/2008.
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  require_once("../shared/class_folder/class_datastore.php");
	  $io_dsprog = new class_datastore();

	  $lb_valido = true;
	  $ls_cedben = $_POST["txtcedben"];
	  $lb_exiben = $this->io_xml->uf_validar_beneficiario($this->ls_codemp,$ls_cedben);
	  if ($lb_exiben)
	     {
		   $ls_numsol = $_POST["txtnumsol"];
		   $ls_fecliq = $this->io_funcion->uf_convertirdatetobd($_POST["txtfecliq"]);
		   $lb_valido = $this->uf_load_solicitud_pago($as_filnam,$ls_numsol,$ls_fecliq);
		   if ($lb_valido) 
		      {
		        for ($li_i=1;$li_i<=$ai_totrow;$li_i++)
				    {
					  $ls_numsoldes = $_POST["hidnumsol".$li_i];
					  if ($ls_numsoldes!=$ls_numsol)
						 {
						   $ls_errmsg = "$as_filnam.- Inconsistencia entre Numeros de Solicitud (Cabecera/Detalle) !!!"; 
						   $this->io_msg->message($ls_errmsg);
						   $lb_valido = false;
						 }
					  if ($lb_valido)
					     {
						   $ls_codban = $_POST["hidcodban".$li_i];
						   $lb_exiban = $this->io_xml->uf_validar_banco($this->ls_codemp,$ls_codban);							  
						   if (!$lb_exiban)
						      {
							    $ls_errmsg = "$as_filnam.- Codigo del Banco no encontrado !!!"; 
								$this->io_msg->message($ls_errmsg);
								$lb_valido = false;
							  }
						 }
					  if ($lb_valido)
						 {
						   $ls_ctaban = $_POST["hidctaban".$li_i];
						   $lb_exicta = $this->io_xml->uf_validar_cuenta_bancaria($this->ls_codemp,$ls_codban,$ls_ctaban);			   
						   if (!$lb_exicta)
							  {
							    $ls_errmsg = "$ls_filnam.- Cuenta Bancaria no encontrada !!!";
							    $this->io_msg->message($ls_errmsg);
								$lb_valido = false;
							  }
						 }
				      if ($lb_valido)
					     {
							$io_dsprog->insertRow("codban",$ls_codban);
							$io_dsprog->insertRow("ctaban",$ls_ctaban);
							$io_dsprog->insertRow("numsol",$ls_numsol);
							$io_dsprog->insertRow("fecpropag",$ls_fecliq);
						 }
					  else
					     {
						   if (isset($io_dsprog) || !empty($io_dsprog))
						      {
							    unset($io_dsprog);
							  }
						 }
					}
			  }
		 }
	  else
	     {
		   $ls_msgerr = "$as_filnam.- Código/Cédula del Beneficiario no encontrado !!!";
		   $this->io_msg->message($ls_msgerr);
		   $lb_valido = false;
		 }
	  if ($lb_valido)
	     {
		   $la_items = array('0'=>'numsol','1'=>'codban','2'=>'ctaban');
		   $io_dsprog->group_by($la_items,array(),'codban');
		   $this->io_sql->begin_transaction();
		   $lb_valido = $this->uf_insertar_programacion($io_dsprog,$aa_seguridad);
		 }
	  if ($lb_valido)
	     {
		   $this->io_sql->commit();
		   $this->io_xml->$io_docxml->save($as_filnam);
		   $this->io_msg->message("Documento $as_filnam, procesado con Éxito !!!");
		 }
	  else
	     {
		   $this->io_sql->rollback();
		   $this->io_msg->message("Documento $as_filnam, No se realizó la Programación del Pago !!!");
		 }
	}
	
	function uf_load_solicitud_pago($as_filnam,$as_numsol,$ad_fecliq)
	{
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	     Function: uf_load_soliciud_pago
	  //		   Access: private
	  //	    Arguments: $as_numsol
	  //                   $ad_fecliq
	  //	      Returns: .
	  //	  Description: .
	  //	   Creado Por: Ing. Nestor Falcón.
	  //   Fecha Creación: 07/07/2008. 							Fecha Última Modificación : 07/07/2008.
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	  $lb_valido = false;
	  $ld_fecliq = $this->io_funcion->uf_convertirdatetobd($ad_fecliq);
	  $ls_sql = "SELECT fecemisol, fecaprosol, fechaconta, estprosol, estaprosol
	               FROM cxp_solicitudes
				  WHERE codemp = '".$this->ls_codemp."'
				    AND numsol = '".$as_numsol."'";
	  $rs_data = $this->io_sql->select($ls_sql);
	  if ($rs_data===false)
	     {
		   $lb_valido = false;
		   $this->io_msg->message("CLASE->sigesp_scb_c_progpago_creditos.php->MÉTODO->uf_load_nombre_beneficiario;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		   echo $this->io_sql->message;
		 }				
	  else
	     {
		   if ($row=$this->io_sql->fetch_row($rs_data))
		      {
				$ls_fecemisol = $row["fecemisol"];
				if ($ld_fecliq>=$ls_fecemisol)
				   {
				     $li_estaprosol = $row["estaprosol"];
					 if ($li_estaprosol==1)
					    {
						  $ls_fecaprosol = $row["fecaprosol"];
						  if ($ls_fecaprosol!='1900-01-01' && $ld_fecliq>=$ls_fecaprosol)
					         {						  
						       $ls_fechaconta = $row["fechaconta"];
					 	       $ls_estprosol  = $row["estprosol"];
							   if ($ls_estprosol=='C')
							      {
								    if ($ld_fecliq>=$ls_fechaconta && $ls_fechaconta!='1900-01-01')
									   {
										 return true;
									   }
								    else
									   { 
										 $ls_msgerr = "$as_filnam.- Fecha de Liquidacion Menor a la Fecha de Contabilizacion de la Solicitud de Pago, o Error fecha !!!";
										 $this->io_msg->message($ls_msgerr);
										 return false;
									   }								  
								  }
							   else
							      {
								    $ls_msgerr = "$as_filnam.- La Solicitud de Pago Nro. $as_numsol, no esta Contabilizada !!!";
								    $this->io_msg->message($ls_msgerr);
								    return false;								  
								  }							   
						     }
						  else
						     {
							   $ls_msgerr = "$as_filnam.- Fecha de Liquidacion Menor a la Fecha de Aprobacion de la Solicitud de Pago, o Error en fecha !!!";
							   $this->io_msg->message($ls_msgerr);
							   return false;
							 }	
						}
					 else
					    {
						  $ls_msgerr = "$as_filnam.- La Solicitud de Pago Nro. $as_numsol, no esta Aprobada !!!";
						  $this->io_msg->message($ls_msgerr);
						  return false;
						}
				   }
				else
				   {
				     $ls_msgerr = "$as_filnam.- Fecha de Liquidacion Menor a la Fecha de Emisión de la Solicitud de Pago !!!";
				     $this->io_msg->message($ls_msgerr);
					 return false;
				   }				
			  }
		   else
		      {
			    $ls_msgerr = "$as_filnam.- Número de la Solicitud de Pago no encontrado !!!";
			    $this->io_msg->message($ls_msgerr);
			    return false;
			  }
		 }
	  return $lb_valido;
	}
	
	function uf_insertar_programacion($ao_dsprog,$aa_seguridad)
	{
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	     Function: uf_insertar_programacion
	  //		   Access: private
	  //	    Arguments: $ao_dsprog
	  //                   $aa_seguridad
	  //	      Returns: .
	  //	  Description: .
	  //	   Creado Por: Ing. Nestor Falcón.
	  //   Fecha Creación: 07/07/2008. 							Fecha Última Modificación : 07/07/2008.
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	  $lb_valido = true;
	  $li_totrow = $ao_dsprog->getRowCount('codban');
	  if ($li_totrow>0)
	     {
		   for ($li_i=1;$li_i<=$li_totrow;$li_i++)
		       {
				 $ls_codban = $ao_dsprog->getValue("codban",$li_i);
				 $ls_ctaban = $ao_dsprog->getValue("ctaban",$li_i);
				 $ls_numsol = $ao_dsprog->getValue("numsol",$li_i);
				 $ls_fecpropag = $ao_dsprog->getValue("fecpropag",$li_i);
				 
				 $ls_sql = "INSERT INTO scb_prog_pago (codemp, codban, ctaban, numsol, fecpropag, estmov, codusu, esttipvia) 
				                 VALUES ('".$this->ls_codemp."','".$ls_codban."','".$ls_ctaban."','".$ls_numsol."','".$ls_fecpropag."','P','SICCRE',0)";
			     $rs_data = $this->io_sql->execute($ls_sql);
				 if ($rs_data===false)
				    {
					  $lb_valido = false;
					  $this->io_msg->message("CLASE->sigesp_scb_c_progpago_creditos.php->MÉTODO->uf_insertar_programacion;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		   			  echo $this->io_sql->message;
					}
			     else
				    {
					  ////////////////////////Seguridad///////////////////////////////////////////////////////////
					  $ls_evento="INSERT";
					  $ls_descripcion="Programo la solicitud  ".$ls_numsol." para el banco ".$ls_codban." y la cuenta ".$ls_ctaban.", Fecha $ls_fecpropag y Usuario SICCRE";
					  $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
					  ////////////////////////////////////////////////////////////////////////////////////////////						 
				  	  if ($lb_valido)
					     {
						   $lb_valido = $this->io_xml->uf_update_xml_progpago($as_filnam,$as_codban,$as_ctaban);
						 }
					}
			   }
		   if ($lb_valido)
		      {
		        if (isset($ao_dsprog))
				   {
				     unset($ao_dsprog);
				   }
				$ls_sql = "UPDATE cxp_solicitudes 
				              SET estprosol = 'S' 
							WHERE codemp='".$this->ls_codemp."' AND numsol='".$ls_numsol."'";
				$rs_data = $this->io_sql->execute($ls_sql);
				if ($rs_data===false)
				   {
					  $lb_valido = false;
					  $this->io_msg->message("CLASE->sigesp_scb_c_progpago_creditos.php->MÉTODO->uf_insertar_programacion (UPDATE cxp_solicitudes);ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		   			  echo $this->io_sql->message;
				   }										
			    else
				   {
				     /////////////////////////Seguridad//////////////////////////////////////////////////////////////////////////////
				     $ls_descripcion="Actualizó la solicitud  ".$ls_numsol." para el banco ".$ls_codban." y la cuenta ".$ls_ctaban.", con estatus S";
					  $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],"UPDATE",$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
				     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////				   
				   }
			  }
		   if ($lb_valido)
			  {
			    $ls_sql = "INSERT INTO cxp_historico_solicitud(codemp, numsol, fecha, estprodoc)
						         VALUES('".$this->ls_codemp."','".$ls_numsol."','".$ls_fecpropag."','S')";
			  	$rs_data = $this->io_sql->execute($ls_sql);
				if ($rs_data===false)
				   {
					  $lb_valido = false;
					  $this->io_msg->message("CLASE->sigesp_scb_c_progpago_creditos.php->MÉTODO->uf_insertar_programacion (INSERT Histórico);ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		   			  echo $this->io_sql->message;
				   }										
			    else
				   {
				     /////////////////////////Seguridad//////////////////////////////////////////////////////////////////////////////
				     $ls_descripcion="Insertó registro histórico de la solicitud  ".$ls_numsol.", con estatus S";
					 $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],"INSERT",$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
				     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////				   
				   }			  
			  }			  
		 }		 
	  return $lb_valido;
	}	
}
?>