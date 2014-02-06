<?php
class sigesp_scb_c_recuperacion_creditos{

  function sigesp_scb_c_recuperacion_creditos($as_path)
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
	$this->ls_codemp     = $_SESSION["la_empresa"]["codemp"];
	$this->io_funcion    = new class_funciones();
	$this->io_seguridad  = new sigesp_c_seguridad();
  }

	function uf_load_movimientos_bancarios($as_rutfil,&$li_totrow)
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
				 $la_datmov = $this->io_xml->uf_cargar_scb_movbco($as_rutfil.'/'.$ls_filnam,'P');
				 if (!empty($la_datmov))
				    {
				      $li_i++;
					  $ls_numdoc = $la_datmov[1]['numdoc'];
				      $ls_codban = $la_datmov[1]['codban'];
					  $ls_ctaban = $la_datmov[1]['ctaban'];
					  $lb_exiban = $this->io_xml->uf_validar_banco($this->ls_codemp,$ls_codban);
					  $lb_exicta = $this->io_xml->uf_validar_cuenta_bancaria($this->ls_codemp,$ls_codban,$ls_ctaban);
					  if ($lb_exiban && $lb_exicta)
						 {
					       $ls_nomban = $this->uf_load_denominaciones($ls_codban,$ls_ctaban,$ls_denctaban);
 						 }
				      $ls_codope = $la_datmov[1]['codope'];
					  if ($ls_codope=='DP')
					     {
						   $ls_codope = $ls_codope.' - Depósito';
						 }
					  elseif($ls_codope=='NC')
					     {
						   $ls_codope = $ls_codope.' - Nota de Crédito';
						 }
					  else
					     {
		   				   $this->io_msg->message("Error en Tipo de Operación, solo están permitidos DP=Depósitos y NC=Notas de Crédito !!!");
						 }
					  $ls_estmov = 'N';
					  if (!empty($ls_codban) && !empty($ls_nomban))
						 { 
						   $ls_codban = $ls_codban.' - '.$ls_nomban;
						 }
					  if (!empty($ls_ctaban) && !empty($ls_denctaban))
						 { 
						   $ls_ctaban = $ls_ctaban.' - '.$ls_denctaban;
						 }
					  $la_object[$li_i][1] = "<input type=checkbox name=chk".$li_i."       id=chk".$li_i."       value=1  class=sin-borde>";
					  $la_object[$li_i][2] = "<input type=text     name=txtnumdoc".$li_i." id=txtnumdoc".$li_i." value='".$ls_numdoc."' class=sin-borde readonly style=text-align:center  size=20 maxlength=15>";
					  $la_object[$li_i][3] = "<input type=text     name=txtcodban".$li_i." id=txtcodban".$li_i." value='".$ls_codban."' class=sin-borde readonly style=text-align:left    size=40 maxlength=254 title='".$ls_codban."'>";
					  $la_object[$li_i][4] = "<input type=text     name=txtctaban".$li_i." id=txtctaban".$li_i." value='".$ls_ctaban."' class=sin-borde readonly style=text-align:left    size=45 maxlength=254 title='".$ls_ctaban."'>";
					  $la_object[$li_i][5] = "<input type=text     name=txtcodope".$li_i." id=txtcodope".$li_i." value='".$ls_codope."' class=sin-borde readonly style=text-align:center  size=18 maxlength=18>
											  <input type=hidden   name=txtfilnam".$li_i." id=txtfilnam".$li_i." value='".$ls_filnam."'>";
					}
			   }  
		 }
	  else
	     {
	       $li_totrow = 1;
		   $la_object[$li_totrow][1] = "<input type=checkbox name=chk".$li_totrow."       id=chk".$li_totrow."       value=1  class=sin-borde disabled>";
	       $la_object[$li_totrow][2] = "<input type=text     name=txtnumdoc".$li_totrow." id=txtnumdoc".$li_totrow." value='' class=sin-borde readonly style=text-align:center  size=20 maxlength=15>";
	       $la_object[$li_totrow][3] = "<input type=text     name=txtcodban".$li_totrow." id=txtcodban".$li_totrow." value='' class=sin-borde readonly style=text-align:left    size=40 maxlength=254>";
	       $la_object[$li_totrow][4] = "<input type=text     name=txtctaban".$li_totrow." id=txtctaban".$li_totrow." value='' class=sin-borde readonly style=text-align:left    size=45 maxlength=254>";
	       $la_object[$li_totrow][5] = "<input type=text     name=txtcodope".$li_totrow." id=txtcodope".$li_totrow." value='' class=sin-borde readonly style=text-align:center  size=18 maxlength=18>
								        <input type=hidden   name=txtfilnam".$li_totrow." id=txtfilnam".$li_totrow." value=''>";		
		 
		 }
	  return $la_object;
	}

    function uf_load_denominaciones($as_codban,$as_ctaban,&$ls_denctaban)
	{
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	     Function: uf_load_denominaciones
	  //		   Access: private
	  //	    Arguments: $as_codban = Código del Banco.
	  //                   $as_ctaban = Código de la Cuenta Bancaria.
	  //                   $ls_denctaban = Denominación de la Cuenta Bancaria.
	  //	      Returns: Arreglo cargado con los archivos xml ubicados en $as_rutfil para ser procesados.
	  //	  Description: Carga la cabecera del Movimiento bancario a partir de los archivos xml ubicados en $as_rutfil.
	  //	   Creado Por: Ing. Néstor Falcón.
	  //   Fecha Creación: 07/07/2008. 							Fecha Última Modificación : 07/07/2008.
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	  $ls_sql = "SELECT scb_banco.nomban,scb_ctabanco.dencta
	               FROM scb_banco, scb_ctabanco
				  WHERE scb_ctabanco.codemp='".$this->ls_codemp."' 
				    AND scb_ctabanco.codban='".$as_codban."'
					AND scb_ctabanco.ctaban='".$as_ctaban."'
					AND scb_banco.codemp=scb_ctabanco.codemp
					AND scb_banco.codban=scb_ctabanco.codban";
	
	  $rs_data = $this->io_sql->select($ls_sql);
	  if ($rs_data===false)
	     {
		   $lb_valido = false;
		   $this->io_msg->message("CLASE->sigesp_scb_c_recuperacion_creditos.php->MÉTODO->uf_load_denominaciones;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		   echo $this->io_sql->message;
		 }
	  else
	     {
		   if ($row=$this->io_sql->fetch_row($rs_data))
		      {
			    $ls_nomban    = $row["nomban"];
			    $ls_denctaban = $row["dencta"];
			  }
		 }
	  return $ls_nomban;
	}
	
	function uf_insert_movimiento_banco($as_filnam,$as_numdoc,$as_codban,$as_ctaban,$as_codope,$aa_datos,&$ls_msgerr,$aa_seguridad)
	{
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	     Function: uf_insert_movimiento_banco
	  //		   Access: private
	  //	    Arguments: $aa_datos
	  //	      Returns: Arreglo cargado con la cabecera para ser procesado el movimiento bancario.
	  //	  Description: .
	  //	   Creado Por: Ing. Néstor Falcón.
	  //   Fecha Creación: 07/07/2008. 							Fecha Última Modificación : 07/07/2008.
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	  $lb_valido = true;	
	  $ls_numdoc = str_pad($as_numdoc,15,0,0);
	  $ls_cedben = trim($aa_datos[1]["ced_bene"]);
	  $lb_existe = $this->io_xml->uf_validar_beneficiario($this->ls_codemp,$ls_cedben);
	  if (!$lb_existe)
	     {
		   $ls_msgerr = "$as_filnam.- Código/Cédula del Beneficiario no encontrado !!!";
		   $this->io_msg->message($ls_msgerr);
		   return false;
		 }
	  $ls_fecmov    = $aa_datos[1]["fecmov"];	  
	  $ls_conmov    = $aa_datos[1]["conmov"];
	  $ls_nomproben = $aa_datos[1]["nomproben"];
	  $ld_monto     = $aa_datos[1]["monto"];
	  $li_pos 		= strpos($ld_monto,',');
	  if (!empty($li_pos))
	     {
		   $ld_monto = str_replace('.','',$ld_monto);
		   $ld_monto = str_replace(',','.',$ld_monto);
		 }

	  $ls_sql = "INSERT INTO scb_movbco (codemp,codban,ctaban,numdoc,codope,estmov,cod_pro,ced_bene,tipo_destino,codconmov,fecmov,conmov,
	                          nomproben,monto,estbpd,estcon,estcobing,esttra,chevau,estimpche,monobjret,monret,procede,comprobante,
							  fecha,id_mco,emicheproc,emicheced,emichenom,emichefec,estmovint,codusu,codopeidb,aliidb,feccon,
							  estreglib,numcarord,numpolcon,coduniadmsig,codbansig,fecordpagsig,tipdocressig,numdocressig,
							  estmodordpag,codfuefin,forpagsig,medpagsig,codestprosig,fechaconta,fechaanula) 
					  VALUES ('".$this->ls_codemp."','".$as_codban."','".$as_ctaban."','".$ls_numdoc."','".$as_codope."','N','----------',
					          '".$ls_cedben."','B','---','".$ls_fecmov."','".$ls_conmov."','".$ls_nomproben."',".$ld_monto.",
							  'M',0,0,1,'',0,0.00,0.00,'SICCRE','".$ls_numdoc."','1900-01-01','',0,'','','1900-01-01',0,'CREDITO',
							  '--',0.00,'1900-01-01','','',0,'','',NULL,'','','',NULL,NULL,NULL,NULL,'1900-01-01','1900-01-01')";
	  $rs_data = $this->io_sql->execute($ls_sql);
	  if ($rs_data===false)
	     {
		   $lb_valido = false;
		   $this->io_msg->message("CLASE->sigesp_scb_c_recuperacion_creditos.php->MÉTODO->uf_insert_movimiento_banco;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		   echo $this->io_sql->message;
		 }
	  else
	     {
		   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		   $ls_evento="INSERT";
		   $ls_descripcion = "Insertó Movimiento Bancario ".$as_numdoc.",Banco $as_codban,Cuenta $as_ctaban, Operacion $as_codope,Estatus N y Procede SICCRE, Asociado a la empresa ".$this->ls_codemp;
		   $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
		   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		 }
	  return $lb_valido;	
	}

	function uf_insert_movimiento_banco_scg($as_filnam,$as_numdoc,$as_codban,$as_ctaban,$as_codope,$aa_datos,&$ls_msgerr,$aa_seguridad)
	{
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	     Function: uf_insert_movimiento_banco_scg
	  //		   Access: private
	  //	    Arguments: $aa_datos
	  //	      Returns: Arreglo cargado con la cabecera para ser procesado el movimiento bancario.
	  //	  Description: .
	  //	   Creado Por: Ing. Néstor Falcón.
	  //   Fecha Creación: 07/07/2008. 							Fecha Última Modificación : 07/07/2008.
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

      $lb_valido = true;
	  $li_totscg = count($aa_datos);
	  if ($li_totscg>0)
		 {
	       $ld_montotdeb = $ld_montothab = 0;
		   for ($li_x=1;$li_x<=$li_totscg;$li_x++)
			   {
				 $ls_docnum = str_pad($aa_datos[$li_x]["numdoc"],15,0,0);
				 $ls_bancod = $aa_datos[$li_x]["codban"];
				 $ls_ctanum = $aa_datos[$li_x]["ctaban"];
				 $ls_opecod = $aa_datos[$li_x]["codope"];
				 $ls_estmov = $aa_datos[$li_x]["estmov"];
				 if (($as_numdoc==$ls_docnum)&&($as_codban==$ls_bancod)&&($as_ctaban==$ls_ctanum)&&($as_codope==$ls_opecod)&&($ls_estmov=='N'))
					{
					  $ls_scgcta = trim($aa_datos[$li_x]["scg_cuenta"]);
					  $lb_existe = $this->io_xml->uf_validar_scgcuenta($this->ls_codemp,$ls_scgcta);
					  if (!$lb_existe)
					     {
						   $ls_msgerr = "$as_filnam.- Cuenta Contable no encontrada !!!";
						   $this->io_msg->message($ls_msgerr);
						   return false;
						 }
					  $ls_debhab = $aa_datos[$li_x]["debhab"];
					  if ($ls_debhab!='D' && $ls_debhab!='H')
						 {
						   $ls_msgerr = "$as_filnam.- Error en Afectacion (SCB_MOVBCO_SCG - debhab), solo estan permitidos D=Debe y H=Haber !!!";
				           $this->io_msg->message($ls_msgerr);
						   return false;
						 }
					  $ls_desmov = $aa_datos[$li_x]["desmov"];
					  $ld_monto  = $aa_datos[$li_x]["monto"];
					  if ($ls_debhab=='D')
					     {
						   $ld_montotdeb += $ld_monto;
						 }
					  elseif($ls_debhab=='H')
					     {
						   $ld_montothab += $ld_monto;
						 }
					  $ls_sql = "INSERT INTO scb_movbco_scg (codemp,codban,ctaban,numdoc,codope,estmov,scg_cuenta,debhab,codded,documento,desmov,procede_doc,monto,monobjret)
					                  VALUES ('".$this->ls_codemp."','".$as_codban."','".$as_ctaban."','".$as_numdoc."','".$as_codope."','N',
					                          '".$ls_scgcta."','".$ls_debhab."','00000','".$as_numdoc."','".$ls_desmov."','SICCRE',".$ld_monto.",0.00)";
					  $rs_data = $this->io_sql->execute($ls_sql);
					  if ($rs_data===false)
						 {
						   $lb_valido = false;
						   $this->io_msg->message("CLASE->sigesp_scb_c_recuperacion_creditos.php->MÉTODO->uf_insert_movimiento_banco_scg;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
						   echo $this->io_sql->message;
						 }
					  else
					     {
						   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
						   $ls_evento="INSERT";
						   $ls_descripcion = "Insertó Movimiento Bancario Contable ".$as_numdoc.",Banco $as_codban,Cuenta $as_ctaban,Operacion $as_codope,Estatus N, Cuenta $ls_scgcta - $ls_debhab por $ld_monto, y Procede SICCRE, Asociado a la empresa ".$this->ls_codemp;
						   $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
															$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
															$aa_seguridad["ventanas"],$ls_descripcion);
						   /////////////////////////////////         SEGURIDAD               /////////////////////////////								 
						 }
					}
				 else
					{
					  $ls_msgerr = "$as_filnam.- Inconsistencia de Datos entre Cabecera del Documento y Detalle Contable !!!";
					  $this->io_msg->message($ls_msgerr);
					  return false;
					}
			   }
	       if ($ld_montotdeb!=$ld_montothab)
		      {
			    $ls_msgerr = "$as_filnam.- Movimiento Contable descuadrado, total Debe diferente al total Haber !!!";
				$this->io_msg->message($ls_msgerr);
				return false;
			  }
		 }
	  return $lb_valido;	
	}

	function uf_insert_movimiento_banco_spi($as_filnam,$as_numdoc,$as_codban,$as_ctaban,$as_codope,$aa_datos,&$ls_msgerr,$aa_seguridad)
	{
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	     Function: uf_insert_movimiento_banco_spi
	  //		   Access: private
	  //	    Arguments: $aa_datos
	  //	      Returns: Arreglo cargado con la cabecera para ser procesado el movimiento bancario.
	  //	  Description: .
	  //	   Creado Por: Ing. Néstor Falcón.
	  //   Fecha Creación: 07/07/2008. 							Fecha Última Modificación : 07/07/2008.
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

      $lb_valido = true;
	  $li_totspi = count($aa_datos);
	  if ($li_totspi>0)
		 {
	       for ($li_x=1;$li_x<=$li_totspi;$li_x++)
			   {
				 $ls_docnum = str_pad($aa_datos[$li_x]["numdoc"],15,0,0);
				 $ls_bancod = $aa_datos[$li_x]["codban"];
				 $ls_ctanum = $aa_datos[$li_x]["ctaban"];
				 $ls_opecod = $aa_datos[$li_x]["codope"];
				 $ls_estmov = $aa_datos[$li_x]["estmov"];
				 if (($as_numdoc==$ls_docnum)&&($as_codban==$ls_bancod)&&($as_ctaban==$ls_ctanum)&&($as_codope==$ls_opecod)&&($ls_estmov=='N'))
					{
					  $ls_spicta = trim($aa_datos[$li_x]["spi_cuenta"]);
					  $lb_existe = $this->io_xml->uf_validar_spicuenta($this->ls_codemp,$ls_spicta);
					  if (!$lb_existe)
					     {
						   $ls_msgerr = "$as_filnam.- Cuenta Presupuestaria de Ingreso no encontrada !!!";
						   $this->io_msg->message($ls_msgerr);
						   return false;
						 }					  
					  $ls_opespi = $aa_datos[$li_x]["operacion"];
					  $lb_existe = $this->io_xml->uf_validar_spioperacion($ls_opespi);
					  if (!$lb_existe)
					     {
						   $ls_msgerr = "$as_filnam.- Operacion de Ingreso no encontrada !!!";
						   $this->io_msg->message($ls_msgerr);
						   return false;
						 }
			          $ls_desmov = $aa_datos[$li_x]["desmov"];
			          $ld_monto  = $aa_datos[$li_x]["monto"];

			          $ls_sql = "INSERT INTO scb_movbco_spi (codemp, codban, ctaban, numdoc, codope, estmov, spi_cuenta, documento,
				                                             operacion, desmov, procede_doc, monto)
					  		     VALUES ('".$this->ls_codemp."','".$ls_bancod."','".$ls_ctanum."','".$ls_docnum."','".$as_codope."','N',
					                     '".$ls_spicta."','".$ls_docnum."','".$ls_opespi."','".$ls_desmov."','SICCRE',".$ld_monto.")";
					  $rs_data = $this->io_sql->execute($ls_sql);
					  if ($rs_data===false)
						 {
						   $lb_valido = false;
						   $this->io_msg->message("CLASE->sigesp_scb_c_recuperacion_creditos.php->MÉTODO->uf_insert_movimiento_banco_spi;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
						   echo $this->io_sql->message;
						 }
			          else
					     {
						   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
						   $ls_evento="INSERT";
						   $ls_descripcion = "Insertó Movimiento Bancario Ingresos ".$as_numdoc.",Banco $as_codban,Cuenta $as_ctaban,Operacion $as_codope,Estatus N, Cuenta $ls_spicta - $ls_opespi por $ld_monto, y Procede SICCRE, Asociado a la empresa ".$this->ls_codemp;
						   $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
															$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
															$aa_seguridad["ventanas"],$ls_descripcion);
						   /////////////////////////////////         SEGURIDAD               /////////////////////////////								 
						 }
					}
				 else
					{
					  $ls_msgerr = "$as_filnam.- Inconsistencia de Datos entre Cabecera del Documento y Detalle de Ingreso !!!";
					  $this->io_msg->message($ls_msgerr);
					  return false;
					}
			   }
	     }
	  return $lb_valido;	
	}
	
	function uf_procesar_cobranza($as_rutfil,$ai_totrows,$aa_seguridad)
	{
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	     Function: uf_procesar_cobranza.
	  //		   Access: private
	  //	    Arguments: $aa_datos
	  //	      Returns: Arreglo cargado con la cabecera para ser procesado el movimiento bancario.
	  //	  Description: .
	  //	   Creado Por: Ing. Néstor Falcón.
	  //   Fecha Creación: 07/07/2008. 							Fecha Última Modificación : 07/07/2008.
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  require_once("class_funciones_banco.php");
	  $io_funciones_scb = new class_funciones_banco();
	  
	  $lb_valido = true;
	  for ($li_i=1;$li_i<=$ai_totrows;$li_i++)
	      {
			if (array_key_exists("chk".$li_i,$_POST))
			   {
			     $this->io_sql->begin_transaction();
				 $ls_filnam = trim($io_funciones_scb->uf_obtenervalor("txtfilnam".$li_i,""));
			     if (!empty($ls_filnam))
				    {
					  $ls_rutori = $as_rutfil.'/'.$ls_filnam;//Ruta Completa de Ubicación del Archivo xml.
					  $ls_rutdes = "../scc/cobranza/procesados/";
					  $la_datmov = $this->io_xml->uf_cargar_scb_movbco($ls_rutori,'C');					  
					  $li_totdat = count($la_datmov);
					  for ($li_z=1;$li_z<=$li_totdat;$li_z++)
					      {
						    $ls_numdoc = $la_datmov[$li_z]["numdoc"];
							$ls_codban = $la_datmov[$li_z]["codban"];
							$lb_existe = $this->io_xml->uf_validar_banco($this->ls_codemp,$ls_codban);
							if (!$lb_existe)
							   {
							     $ls_errmsg = "$ls_filnam.- Codigo del Banco no encontrado !!!"; 
								 $this->io_msg->message($ls_errmsg);
								 $lb_valido = false;
							   }
							if ($lb_valido)
							   {
								 $ls_ctaban = $la_datmov[$li_z]["ctaban"];
								 $lb_existe = $this->io_xml->uf_validar_cuenta_bancaria($this->ls_codemp,$ls_codban,$ls_ctaban);
								 if (!$lb_existe)
								    {
									  $ls_errmsg = "$ls_filnam.- Cuenta Bancaria no encontrada !!!";
									  $this->io_msg->message($ls_errmsg);
								      $lb_valido = false;
								    }
							   }
							if ($lb_valido)
							   {
								 $ls_codope = $la_datmov[$li_z]["codope"];
								 if ($ls_codope!='DP' && $ls_codope!='NC')
								    {
									  $ls_errmsg = "$ls_filnam.- Error en Tipo de Operacion, solo estan permitidos DP=Depositos y NC=Notas de Credito !!!";
									  $this->io_msg->message($ls_errmsg);
								      $lb_valido = false;
								    }
							   }
							$lb_existe = false;
							if ($lb_valido)
							   {
							     $lb_existe = $this->io_xml->uf_load_movimiento_bancario($this->ls_codemp,$ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope);
							   }
							if (!$lb_existe && $lb_valido)
							   {
							     $lb_valido = $this->uf_insert_movimiento_banco($ls_filnam,$ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope,$la_datmov,$ls_errmsg,$aa_seguridad);
							     if ($lb_valido)
								    {
									  $la_datscg = $this->io_xml->uf_cargar_scb_movbco_scg($ls_rutori);
									  if (!empty($la_datscg))
									     {
										   $lb_valido = $this->uf_insert_movimiento_banco_scg($ls_filnam,$ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope,$la_datscg,$ls_errmsg,$aa_seguridad); 
										   unset($la_datscg);
										 }									  
									  else
									     {
										   $ls_errmsg = "$ls_filnam.- Movimiento Bancario sin Detalle Contable !!!";
										   $this->io_msg->message($ls_errmsg);
										   return false;										 
										 }
									}
							     if ($lb_valido)
								    {
									  $la_datspi = $this->io_xml->uf_cargar_scb_movbco_spi($ls_rutori);
									  if (!empty($la_datspi))
									     {
										   $lb_valido = $this->uf_insert_movimiento_banco_spi($ls_filnam,$ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope,$la_datspi,$ls_errmsg,$aa_seguridad); 
										   unset($la_datspi);
										 }									  
									}
							   }							
						    elseif($lb_existe)
							   {
							     $ls_errmsg = "$ls_filnam.- Movimiento Bancario ya esta registrado !!!";
								 $this->io_msg->message($ls_errmsg);
							     return false;
							   }
						    unset($la_datmov);
						  }					  
					   if ($lb_valido)
						  {
							$this->io_sql->commit();
							$this->io_msg->message("Documento $ls_filnam, procesado con Éxito !!!");
					  	  }
					   else
						  {
						    $this->io_sql->rollback();
						    $this->io_msg->message("Documento $ls_filnam, Registro No Incluido !!!");
						  }
					   $lb_copval = $this->io_xml->uf_mover_xml($ls_filnam,$ls_rutori,$ls_rutdes);
					   if ($lb_copval)
						  {
						    $this->io_xml->uf_update_xml_procesado($ls_filnam,$ls_rutdes,"SCB_MOVBCO",$lb_valido,$ls_errmsg);
						  }
					}
			   }
		  } 	 
	}
}
?>