<?php
class sigesp_scb_c_liquidacion_creditos{

  function sigesp_scb_c_liquidacion_creditos($as_path)
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

	function uf_load_liquidaciones($as_rutfil,&$li_totrow)
	{
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	     Function: uf_load_liquidaciones
	  //		   Access: private
	  //	    Arguments: $as_rutfil = Ruta del directorio de donde se cargarán los archivos xml.
	  //	      Returns: Arreglo cargado con los archivos xml ubicados en $as_rutfil para ser procesados.
	  //	  Description: Carga la cabecera del Movimiento bancario a partir de los archivos xml ubicados en $as_rutfil.
	  //	   Creado Por: Ing. Nestor Falcón.
	  //   Fecha Creación: 07/07/2008. 							Fecha Última Modificación : 07/07/2008.
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	  $li_i = 0;
	  $la_filnam = $this->io_xml->uf_load_archivos($as_rutfil);
	  if (!empty($la_filnam))
	     {
		   $li_totrow = count($la_filnam["filnam"]);
		   for ($li_y=1;$li_y<=$li_totrow;$li_y++)
		       {
			     $lb_valido = true;
				 $ls_filnam = $la_filnam["filnam"][$li_y];
				 $ls_rutori = $as_rutfil.'/'.$ls_filnam;
				 $la_datmov = $this->io_xml->uf_cargar_liquidaciones($ls_rutori);
				 if (!empty($la_datmov))
				    {
					  if ($lb_valido)
					     {
						   $ls_codope = $la_datmov[1]['codope'];
						   $ls_codban = $la_datmov[1]['codban'];
						   $ls_ctaban = $la_datmov[1]['ctaban'];
						   $ls_nomban = $ls_denctaban = "";
						   if ($ls_codope=='CH')
							  {
							    $ls_denope = "Cheque";
								if (!empty($ls_codban) || !empty($ls_ctaban))
								   {
								     $lb_valido = false;
							         $ls_errmsg = "$ls_filnam.- Para Operación Cheque, No puede Asignarse Banco y Cuenta !!!";
								   }
							  }
						   elseif($ls_codope=='ND')
							  {
							    $ls_denope = "Nota de Débito";
								$lb_existe = $this->io_xml->uf_validar_banco($this->ls_codemp,$ls_codban);
							    if (!$lb_existe)
								   {
									 $ls_errmsg = "$ls_filnam.- Código del Banco no encontrado !!!"; 
									 $lb_valido = false;
								   }
							    if ($lb_valido)
					               {
								     $lb_existe = $this->io_xml->uf_validar_cuenta_bancaria($this->ls_codemp,$ls_codban,$ls_ctaban);
								     if (!$lb_existe)
									    { 
										  $ls_errmsg = "$ls_filnam.- Cuenta Bancaria no encontrada !!!";
										  $lb_valido = false;
									    }
								     else
									    {
										  if (!empty($ls_codban) && !empty($ls_ctaban))
										     {
											   $ls_nomban = $this->uf_load_nomban_denctaban($ls_codban,$ls_ctaban,$ls_denctaban);
										     }
									    }
						           }
							  }
						   else
							  {
							    $lb_valido = false;
							    $ls_errmsg = "$ls_filnam.- Operación Bancaria No Válida !!!";
							    $ls_denope = "Error en Operación";
							  }
						 }
					  if ($lb_valido)
					     {
						   $ls_docnum = $la_datmov[1]['documento'];					  
						   $lb_existe = $this->uf_load_numsep($ls_docnum,$ld_montotsep,$ls_fecsep,$ls_estsep,$ls_tipsep);//Verificación de la Existencia del Crédito.
						   if ($lb_existe)
							  {							    
								if ($ls_tipsep=='O')
								   {
									 $ld_totpagpre = $ld_monpagpen = 0;						   
									 $ld_totpagpre = $this->uf_load_pagos_previos($ls_docnum);//Carga y Verificación de Pagos Previos.
									 $ld_totpagpre = number_format(floatval($ld_totpagpre),2,'.','');
									 $ld_montotsep = number_format(floatval($ld_montotsep),2,'.','');    
									 $ld_monpagpen = ($ld_montotsep-$ld_totpagpre);//Monto de Pagos Pendientes.
									 $ld_monpagpen = number_format(floatval($ld_monpagpen),2,'.','');
									 $ld_monmov    = number_format(floatval($la_datmov[1]['monto']),2,'.','');
									 if ($ld_monmov>$ld_monpagpen)//Comparación Monto del Mov. Bancario contra Pendiente del Crédito.
									    {
										  $lb_valido = false;
										  $ls_errmsg = "$ls_filnam.- Monto del Movimiento supera Monto Restante del Crédito ($ld_monmov ; $ld_monpagpen) !!!";
									    }
									 else
									    {
										  $ld_monmov = number_format($ld_monmov,2,',','.');							  
									    }
								   }
							    else
								   {
								     $lb_valido = false;
									 $ls_errmsg = "$ls_filnam.- Error en Tipo de SEP, Solo serán las Procesadas de Tipo O = Concepto !!!";								   
								   }
							  }					  
						   else
							  {
							    $lb_valido = false;
							    $ls_errmsg = "$ls_filnam.- Número de SEP (Crédito), No encontrada !!!";
							  }
						 }
					  if ($lb_valido)
					     {
						   $ls_cedben = $ls_codben = $la_datmov[1]['ced_bene'];
						   $lb_exiben = $this->io_xml->uf_validar_beneficiario($this->ls_codemp,$ls_cedben);
						   if ($lb_exiben)
							  {
							    $ls_nomben = $this->uf_load_nombre_beneficiario($ls_cedben);
							  }
						   else
							  {
							    $lb_valido = false;
							    $ls_errmsg = "$ls_filnam.- Beneficiario No encontrado !!!";
							  }
						 }
					   $ls_fecmov = date("d/m/Y");
					   $ls_fecmov=$this->io_funcion->uf_convertirdatetobd($ls_fecmov);
					  /*/ Comentado para Ignorar la validación con la fecha del movimiento
					  if ($lb_valido)
					     {
						   $ls_fecmov = $la_datmov[1]['fecmov'];
						   if (!empty($ls_fecmov))
						      {
							    if ($ls_fecmov>=$ls_fecsep)
								   {
									 $ls_fecmov = $this->io_funcion->uf_convertirfecmostrar($ls_fecmov);
								   }
								 else
								   {
									 $ls_errmsg = "$ls_filnam.- Fecha del Movimiento menor a la Fecha de Emisión del Crédito !!!"; 
									 $lb_valido = false;
								   }
							  }
						   else
						      {
							    $lb_valido = false;
								$ls_errmsg = "$ls_filnam.- Fecha del Movimiento en Blanco !!!";								 
							  }
						 }
					  /*/ //Comentado para Ignorar la validación con la fecha del movimiento
					  if ($lb_valido)
					     {
						   $ls_numdoc = $la_datmov[1]['numdoc'];
						   if (!empty($ls_numdoc))
							  {
							    $lb_valido = false;
							    $ls_errmsg = "$ls_filnam.- XML NO Válido, Número Documento Distinto de Blanco !!!";
							  }						 
						 }
					  if (!$lb_valido)
					     {
						   $ls_rutdes = "../scc/liquidacion/procesados/";
						   $lb_copval = $this->io_xml->uf_mover_xml($ls_filnam,$ls_rutori,$ls_rutdes);
						   if ($lb_copval)
							  {
								$this->io_xml->uf_update_xml_procesado($ls_filnam,$ls_rutdes,"SCB_MOVBCO",$lb_valido,$ls_errmsg);
							  }
						 }
					  if ($lb_valido)
					     {
						   $li_i++;
						   $ls_conmov    = utf8_decode($la_datmov[1]['conmov']);
					       $ls_nombenalt = utf8_decode($la_datmov[1]['nombenalt']);

						   $la_object[$li_i][1] = "<a href=\"javascript: uf_aceptar('$ls_codban','$ls_nomban','$ls_ctaban','$ls_denctaban','$ls_fecmov','$ld_monmov','$ls_conmov','$ls_codben','$ls_nomben','$ls_nombenalt','$ls_codope','$ls_docnum','$ls_filnam','$ls_estsep');\">".$ls_codben."</a>";					  
						   $la_object[$li_i][2] = "<input type=text     name=txtnomben".$li_i." id=txtnomben".$li_i." value='".$ls_nomben."' class=sin-borde readonly style=text-align:left    size=20 maxlength=254 title='".$ls_nomben."'>";
						   $la_object[$li_i][3] = "<input type=text     name=txtconmov".$li_i." id=txtconmov".$li_i." value='".$ls_conmov."' class=sin-borde readonly style=text-align:left    size=30 maxlength=254 title='".$ls_conmov."'>";
						   $la_object[$li_i][4] = "<input type=text     name=txtmonmov".$li_i." id=txtmonmov".$li_i." value='".$ld_monmov."' class=sin-borde readonly style=text-align:right   size=15 maxlength=254>";
						   $la_object[$li_i][5] = "<input type=text     name=txtfecmov".$li_i." id=txtfecmov".$li_i." value='".$ls_fecmov."' class=sin-borde readonly style=text-align:center  size=8 maxlength=254>";
						   $la_object[$li_i][6] = "<input type=text     name=txtdenope".$li_i." id=txtdenope".$li_i." value='".$ls_denope."' class=sin-borde readonly style=text-align:center  size=10 maxlength=15 title='".$ls_denope."'>
												   <input type=hidden   name=txtfilnam".$li_i." id=txtfilnam".$li_i." value='".$ls_filnam."'>";						 
						 }
					}
			   }  
	       $li_totrow = $li_i;
		   if ($li_totrow==0)
		      {
			    $li_totrow++;
			  }
		   if ($li_i==0)
		      {
		        $li_totrow = 1;
		        $la_object[$li_totrow][1] = "";					  
		        $la_object[$li_totrow][2] = "<input type=text  name=txtnomben".$li_totrow." id=txtnomben".$li_totrow." value='' class=sin-borde readonly style=text-align:left    size=20>";
		        $la_object[$li_totrow][3] = "<input type=text  name=txtconmov".$li_totrow." id=txtconmov".$li_totrow." value='' class=sin-borde readonly style=text-align:left    size=30>";
		        $la_object[$li_totrow][4] = "<input type=text  name=txtmonmov".$li_totrow." id=txtmonmov".$li_totrow." value='' class=sin-borde readonly style=text-align:right   size=15>";
		        $la_object[$li_totrow][5] = "<input type=text  name=txtfecmov".$li_totrow." id=txtfecmov".$li_totrow." value='' class=sin-borde readonly style=text-align:center  size=8>";
		        $la_object[$li_totrow][6] = "<input type=text  name=txtdenope".$li_totrow." id=txtdenope".$li_totrow." value='' class=sin-borde readonly style=text-align:center  size=10>";
			  }
		 }
	  else
	     {
	       $li_totrow = 1;
		   $la_object[$li_totrow][1] = "";					  
		   $la_object[$li_totrow][2] = "<input type=text  name=txtnomben".$li_totrow." id=txtnomben".$li_totrow." value='' class=sin-borde readonly style=text-align:left    size=20>";
		   $la_object[$li_totrow][3] = "<input type=text  name=txtconmov".$li_totrow." id=txtconmov".$li_totrow." value='' class=sin-borde readonly style=text-align:left    size=30>";
		   $la_object[$li_totrow][4] = "<input type=text  name=txtmonmov".$li_totrow." id=txtmonmov".$li_totrow." value='' class=sin-borde readonly style=text-align:right   size=15>";
		   $la_object[$li_totrow][5] = "<input type=text  name=txtfecmov".$li_totrow." id=txtfecmov".$li_totrow." value='' class=sin-borde readonly style=text-align:center  size=8>";
		   $la_object[$li_totrow][6] = "<input type=text  name=txtdenope".$li_totrow." id=txtdenope".$li_totrow." value='' class=sin-borde readonly style=text-align:center  size=10>";
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
		   $this->io_msg->message("CLASE->sigesp_scb_c_liquidacion_creditos.php->MÉTODO->uf_load_nombre_beneficiario;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	
	function uf_load_detalles_spg($as_filnam,&$li_totdet,&$la_datscg,&$ld_totmonspg)
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
	  require_once("class_funciones_banco.php");
	  $io_funciones_scb = new class_funciones_banco();
	  
	  $ld_montotmov = $io_funciones_scb->uf_obtenervalor("txtmonmov",0);
      $ld_montotmov = str_replace(".","",$ld_montotmov);
	  $ld_montotmov = str_replace(",",".",$ld_montotmov);
	  
	  $lb_valido = true;
	  $li_totdet = $li_y = 0;
	  $li_estmodest 	= $_SESSION["la_empresa"]["estmodest"];
	  $li_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
	  $li_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
	  $li_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
	  $li_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
	  $li_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];	  
	  
	  if (!empty($as_filnam))
	     {
		   $la_datmov = $this->io_xml->uf_cargar_detalles_spg($as_filnam);
		   if (!empty($la_datmov))
			  {
			    $li_totdet    = count($la_datmov);
				$la_datscg    = array(); 
				$ld_totmonspg = 0;//Acumulador para la sumatoria de los Detalles de la Liquidación.
				for ($li_i=1;$li_i<=$li_totdet;$li_i++ && $lb_valido)
				    {
				      $ls_spgcta = $la_datmov[$li_i]['spgcta'];//Cuenta Presupuestaria.
					  $ls_estcla = $la_datmov[$li_i]['estcla'];
				      if ($ls_estcla=='A')
					     {
						   $ls_denestcla = "Acción";
						 }
					  elseif($ls_estcla=='P')
						 {
						   $ls_denestcla = "Proyecto";						 
						 }
					  else
						 {
						   $ls_errmsg = "$as_filnam.- Modalidad Presupuestaria Invalida, P=Proyecto y A=Acción !!!";
						   $this->io_msg->message($ls_errmsg);
						   $lb_valido = false;								   
						 }
					  $ls_denctaspg  = ""; 
					  $ls_codestpro1 = $la_datmov[$li_i]['codestpro1'];
					  $ls_codestpro2 = $la_datmov[$li_i]['codestpro2'];
					  $ls_codestpro3 = $la_datmov[$li_i]['codestpro3'];
					  $ls_codestpro4 = $la_datmov[$li_i]['codestpro4'];
					  $ls_codestpro5 = $la_datmov[$li_i]['codestpro5'];
					  $lb_valido     = $this->uf_validar_presupuesto($ls_spgcta,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_denctaspg);
					  if ($lb_valido)
					     {
						   $ls_denestpro1 = $this->uf_load_denestpro1($ls_codestpro1,$ls_estcla);
						   $ls_scgcta     = $this->uf_load_scgcta($ls_spgcta,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_denctascg);
						   if (empty($ls_scgcta))
						      {
							    $ls_errmsg = "$as_filnam.- Cuenta Contable no encontrada para $ls_spgcta !!!";
						        $this->io_msg->message($ls_errmsg);
						        $lb_valido = false;
							  }
						   else
						      {
							    $li_y++;
								$ls_codestpre  = $ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;					  
							    $ls_codestpro1 = substr($ls_codestpro1,-$li_loncodestpro1);
							    $ls_codestpro2 = substr($ls_codestpro2,-$li_loncodestpro2);
							    $ls_codestpro3 = substr($ls_codestpro3,-$li_loncodestpro3);
							    $ls_codestpro  = $ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3;
							    if ($li_estmodest==2)
								   {
									 $ls_codestpro4 = substr($ls_codestpro4,-$li_loncodestpro4);
									 $ls_codestpro5 = substr($ls_codestpro5,-$li_loncodestpro5);
									 $ls_codestpro  = $ls_codestpro.'-'.$ls_codestpro4.'-'.$ls_codestpro5;
								   }
								$li_estmodest = $_SESSION["la_empresa"]["estmodest"];
							    $ld_mondetspg = $la_datmov[$li_i]['monto'];
								$la_datscg["scgcta"][$li_y]    = $ls_scgcta;   // Armado de la Información Contable
								$la_datscg["denctascg"][$li_y] = $ls_denctascg;// asociada a la Cuenta y Estructura
								$la_datscg["mondetscg"][$li_y] = $ld_mondetspg;// presupuestaria.							   
								$ld_totmonspg +=$ld_mondetspg;
							    $ld_mondetspg = number_format($ld_mondetspg,2,',','.');						    					 
							    $la_object[$li_i][1] = "<input type=text  name=txtspgcta".$li_i."      id=txtspgcta".$li_i."    value='".$ls_spgcta."' 	  class=sin-borde readonly style=text-align:center  size=15 maxlength=25>";
							    $la_object[$li_i][2] = "<input type=text  name=txtdenctaspg".$li_i."   id=txtdenctaspg".$li_i." value='".$ls_denctaspg."' class=sin-borde readonly style=text-align:left    size=45 maxlength=500 title='".$ls_denctaspg."'>";
							    $la_object[$li_i][3] = "<input type=text  name=txtcodestpro".$li_i."   id=txtcodestpro".$li_i." value='".$ls_codestpro."' class=sin-borde readonly style=text-align:center  size=30 maxlength=129 title='".$ls_denestpro1."'>";
							    $la_object[$li_i][4] = "<input type=text  name=txtcodtipest".$li_i."   id=txtcodtipest".$li_i." value='".$ls_denestcla."' class=sin-borde readonly style=text-align:center  size=10 maxlength=8>";
							    $la_object[$li_i][5] = "<input type=text  name=txtmondetspg".$li_i."   id=txtmondetspg".$li_i." value='".$ld_mondetspg."' class=sin-borde readonly style=text-align:right   size=17 maxlength=24>
													    <input type=hidden  name=hidcodestpre".$li_i." id=hidcodestpre".$li_i." value='".$ls_codestpre."'>";
							  }
						 }
				      else
					     { 					   
						   $li_totdet = 1;
						   $la_object[$li_totdet][1] = "<input type=text   name=txtspgcta".$li_totdet."    id=txtspgcta".$li_totdet."    value='' class=sin-borde readonly style=text-align:center  size=15 maxlength=25>";
						   $la_object[$li_totdet][2] = "<input type=text   name=txtdenctaspg".$li_totdet." id=txtdenctaspg".$li_totdet." value='' class=sin-borde readonly style=text-align:center  size=45 maxlength=254>";
						   $la_object[$li_totdet][3] = "<input type=text   name=txtcodestpro".$li_totdet." id=txtcodestpro".$li_totdet." value='' class=sin-borde readonly style=text-align:left    size=30 maxlength=254>";
						   $la_object[$li_totdet][4] = "<input type=text   name=txtcodtipest".$li_totdet." id=txtcodtipest".$li_totdet." value='' class=sin-borde readonly style=text-align:center  size=10 maxlength=254>";
						   $la_object[$li_totdet][5] = "<input type=text   name=txtmondetspg".$li_totdet." id=txtmondetspg".$li_totdet." value='' class=sin-borde readonly style=text-align:right   size=17 maxlength=23>
													   <input type=hidden  name=txtfilnam".$li_totdet."    id=txtfilnam".$li_totdet."    value=''>";		
						   
						   $la_datscg = "";
						   $ls_errmsg = "$as_filnam.- Error en datos de la Afectación Presupuestaria !!!";
					  	   $this->io_msg->message($ls_errmsg);
						   $lb_valido = false;
						 }					
					}					  
			    $ld_montotsep = $io_funciones_scb->uf_obtenervalor("txtmonmov",0);
				$ld_montotsep = str_replace('.','',$ld_montotsep);
				$ld_montotsep = str_replace(',','.',$ld_montotsep);
				if ($ld_montotsep!=$ld_totmonspg && $lb_valido)
				   { 
				     $li_totdet = 1;
				     $la_object[$li_totdet][1] = "<input type=text   name=txtspgcta".$li_totdet."    id=txtspgcta".$li_totdet."    value='' class=sin-borde readonly style=text-align:center  size=15 maxlength=25>";
				     $la_object[$li_totdet][2] = "<input type=text   name=txtdenctaspg".$li_totdet." id=txtdenctaspg".$li_totdet." value='' class=sin-borde readonly style=text-align:center  size=45 maxlength=254>";
				     $la_object[$li_totdet][3] = "<input type=text   name=txtcodestpro".$li_totdet." id=txtcodestpro".$li_totdet." value='' class=sin-borde readonly style=text-align:left    size=30 maxlength=254>";
				     $la_object[$li_totdet][4] = "<input type=text   name=txtcodtipest".$li_totdet." id=txtcodtipest".$li_totdet." value='' class=sin-borde readonly style=text-align:center  size=10 maxlength=254>";
				     $la_object[$li_totdet][5] = "<input type=text   name=txtmondetspg".$li_totdet." id=txtmondetspg".$li_totdet." value='' class=sin-borde readonly style=text-align:right   size=17 maxlength=23>
											      <input type=hidden name=txtfilnam".$li_totdet."    id=txtfilnam".$li_totdet."    value=''>";		

					 $la_datscg = "";
					 $ls_errmsg = "$as_filnam.- Descuadre entre el monto del Movimiento Bancario y su Detalle Presupuestario !!!";
					 $this->io_msg->message($ls_errmsg);
					 $lb_valido = false;
				   }
				if (!$lb_valido)
		           {
					 $ls_rutdes = "../scc/liquidacion/procesados/";
					 $ls_filnam = trim(substr($as_filnam,30,254));
					 $lb_copval = $this->io_xml->uf_mover_xml($ls_filnam,$as_filnam,$ls_rutdes);
					 if ($lb_copval)
					    {
						  $this->io_xml->uf_update_xml_procesado($ls_filnam,$ls_rutdes,"SCB_MOVBCO",$lb_valido,$ls_errmsg);
					    }
				   }
			  }
	       if (isset($la_datmov))
		      {
			    unset($la_datmov);
			  }		   
		 }
	  return $la_object;
	}
	
	function uf_load_scgcta($as_spgcta,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_estcla,&$ls_denctascg)
	{
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	     Function: uf_load_scgcta
	  //		   Access: private
	  //	    Arguments: $as_spgcta
	  //                   $as_codestpro1
	  //                   $as_codestpro2
	  //                   $as_codestpro3
	  //                   $as_codestpro4
	  //                   $as_codestpro5
	  //                   $as_estcla
	  //                   $ls_denctascg
	  //	      Returns: .
	  //	  Description: .
	  //	   Creado Por: Ing. Néstor Falcón.
	  //   Fecha Creación: 28/07/2008. 							Fecha Última Modificación : 28/07/2008.
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	  $ls_sql = "SELECT trim(spg_cuentas.sc_cuenta) as sc_cuenta, (scg_cuentas.denominacion) as denctascg
	               FROM spg_cuentas, scg_cuentas
				  WHERE spg_cuentas.codemp='".$this->ls_codemp."' 
				    AND trim(spg_cuentas.spg_cuenta)='".trim($as_spgcta)."'
					AND spg_cuentas.codestpro1='".$as_codestpro1."'
					AND spg_cuentas.codestpro2='".$as_codestpro2."'
					AND spg_cuentas.codestpro3='".$as_codestpro3."'
					AND spg_cuentas.codestpro4='".$as_codestpro4."'
					AND spg_cuentas.codestpro5='".$as_codestpro5."'
					AND spg_cuentas.estcla='".$as_estcla."'
					AND spg_cuentas.status='C'
					AND spg_cuentas.codemp=scg_cuentas.codemp
					AND spg_cuentas.sc_cuenta=scg_cuentas.sc_cuenta";
					
	  $rs_data = $this->io_sql->select($ls_sql);
	  if ($rs_data===false)
	     {
		   $lb_valido = false;
		   $this->io_msg->message("CLASE->sigesp_scb_c_liquidacion_creditos.php->MÉTODO->uf_load_scgcta;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		   echo $this->io_sql->message;
		 }
	  else
	     {
		   if ($row=$this->io_sql->fetch_row($rs_data))
		      {
			    $ls_scgcta    = $row["sc_cuenta"];
			    $ls_denctascg = $row["denctascg"];
			    unset($rs_data,$row);
			  }
		 }
	  return $ls_scgcta;
	}
	
	function uf_validar_presupuesto($as_spgcta,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_estcla,&$ls_denctaspg)
	{
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	     Function: uf_validar_presupuesto
	  //		   Access: private
	  //	    Arguments: $as_spgcta
	  //                   $as_codestpro1
	  //                   $as_codestpro2
	  //                   $as_codestpro3
	  //                   $as_codestpro4
	  //                   $as_codestpro5
	  //                   $as_estcla
	  //	      Returns: .
	  //	  Description: .
	  //	   Creado Por: Ing. Néstor Falcón.
	  //   Fecha Creación: 28/07/2008. 							Fecha Última Modificación : 28/07/2008.
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	  $lb_existe = false;
	  $ls_sql = "SELECT denominacion
	               FROM spg_cuentas
				  WHERE codemp='".$this->ls_codemp."' 
				    AND trim(spg_cuentas.spg_cuenta)='".trim($as_spgcta)."'
					AND codestpro1='".$as_codestpro1."'
					AND codestpro2='".$as_codestpro2."'
					AND codestpro3='".$as_codestpro3."'
					AND codestpro4='".$as_codestpro4."'
					AND codestpro5='".$as_codestpro5."'
					AND estcla='".$as_estcla."'";
					
	  $rs_data = $this->io_sql->select($ls_sql);
	  if ($rs_data===false)
	     {
		   $lb_valido = false;
		   $this->io_msg->message("CLASE->sigesp_scb_c_liquidacion_creditos.php->MÉTODO->uf_validar_presupuesto;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		   echo $this->io_sql->message;
		 }
	  else
	     {
		   if ($row=$this->io_sql->fetch_row($rs_data))
		      {
			    $ls_denctaspg = $row["denominacion"];
				$lb_existe = true;
			    unset($rs_data,$row);
			  }
		 }
	  return $lb_existe;
    }
	
	function uf_load_denestpro1($as_codestpro1,$as_estcla)
	{
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	     Function: uf_load_scgcta
	  //		   Access: private
	  //	    Arguments: $as_spgcta
	  //                   $as_codestpro1
	  //                   $as_estcla
	  //	      Returns: .
	  //	  Description: .
	  //	   Creado Por: Ing. Néstor Falcón.
	  //   Fecha Creación: 28/07/2008. 							Fecha Última Modificación : 28/07/2008.
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	  $ls_sql = "SELECT denestpro1
	               FROM spg_ep1
				  WHERE codemp='".$this->ls_codemp."' 
					AND codestpro1='".$as_codestpro1."'
					AND estcla='".$as_estcla."'";
					
	  $rs_data = $this->io_sql->select($ls_sql);
	  if ($rs_data===false)
	     {
		   $lb_valido = false;
		   $this->io_msg->message("CLASE->sigesp_scb_c_liquidacion_creditos.php->MÉTODO->uf_load_denestpro1;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		   echo $this->io_sql->message;
		 }
	  else
	     {
		   if ($row=$this->io_sql->fetch_row($rs_data))
		      {
			    $ls_denestpro1 = $row["denestpro1"];
			    unset($rs_data,$row);
			  }
		 }
	  return $ls_denestpro1;
	}
	
	function uf_load_contable($as_codban,$as_ctaban,&$ls_denctascg)
	{
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	     Function: uf_print_detalle_scg
	  //		   Access: private
	  //	    Arguments: $as_codban = Código del Banco.
	  //                   $as_ctaban = Cuenta Bancaria.
	  //	      Returns: .
	  //	  Description: .
	  //	   Creado Por: Ing. Néstor Falcón.
	  //   Fecha Creación: 28/07/2008. 							Fecha Última Modificación : 28/07/2008.
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	  $ls_sql = "SELECT trim(scb_ctabanco.sc_cuenta) as sc_cuenta, scg_cuentas.denominacion as denctascg
	               FROM scb_banco, scb_ctabanco, scg_cuentas
				  WHERE scb_banco.codemp='".$this->ls_codemp."'
				    AND scb_ctabanco.codban='".$as_codban."'
					AND scb_ctabanco.ctaban='".$as_ctaban."'
					AND scb_ctabanco.codemp=scb_banco.codemp
					AND scb_ctabanco.codban=scb_banco.codban					
					AND scb_ctabanco.codemp=scg_cuentas.codemp
					AND scb_ctabanco.sc_cuenta=scg_cuentas.sc_cuenta";
					
	  $rs_data = $this->io_sql->select($ls_sql);
	  if ($rs_data===false)
	     {
		   $lb_valido = false;
		   $this->io_msg->message("CLASE->sigesp_scb_c_liquidacion_creditos.php->MÉTODO->uf_load_contable;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		   echo $this->io_sql->message;
		 }
	  else
	     {
		   if ($row=$this->io_sql->fetch_row($rs_data))
		      {
			    $ls_scgcta    = $row["sc_cuenta"];
				$ls_denctascg = $row["denctascg"];
			    unset($rs_data,$row);
			  }
		 }
	  return $ls_scgcta;
	}
	
	function uf_print_detalles_scg($as_filnam,$aa_datscg,&$li_totrowscg,$as_codope,$as_codban,$as_ctaban,&$ls_ctascg,$ad_montotscg,&$ld_monret)
	{
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	     Function: uf_print_detalle_scg
	  //		   Access: private
	  //	    Arguments: $as_filnam    =
	  //                   $la_datscg    = 
	  //                   $li_totrowscg =
	  //                   $as_codope    =
	  //                   $as_codban    =
	  //                   $as_ctaban    =
	  //                   $ls_ctascg    =
	  //                   $ad_montotscg =
	  //	      Returns: .
	  //	  Description: .
	  //	   Creado Por: Ing. Néstor Falcón.
	  //   Fecha Creación: 28/07/2008. 							Fecha Última Modificación : 28/07/2008.
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
         $lb_valido = true;
		 $li_totrowscg = count($aa_datscg["scgcta"]);
		 
		 for ($li_i=1;$li_i<=$li_totrowscg;$li_i++)
		     {
			   $ls_scgcta    = trim($aa_datscg["scgcta"][$li_i]);
			   $ls_denctascg = $aa_datscg["denctascg"][$li_i];
			   $ld_mondetscg = number_format($aa_datscg["mondetscg"][$li_i],2,',','.');			   
			   
			   $la_objscg[$li_i][1] = "<input type=text name=txtscgcta".$li_i."    id=txtscgcta".$li_i."    value='".$ls_scgcta."' 	  class=sin-borde readonly style=text-align:center  size=15 maxlength=25>";
			   $la_objscg[$li_i][2] = "<input type=text name=txtdenscgcta".$li_i." id=txtdenscgcta".$li_i." value='".$ls_denctascg."' class=sin-borde readonly style=text-align:left    size=71 maxlength=500 title='".$ls_denctascg."'>";
			   $la_objscg[$li_i][3] = "<input type=text name=txtdebhab".$li_i."    id=txtdebhab".$li_i."    value='Debe' 			  class=sin-borde readonly style=text-align:center  size=20>";
			   $la_objscg[$li_i][4] = "<input type=text name=txtmonscg".$li_i."    id=txtmonscg".$li_i."    value='".$ld_mondetscg."' class=sin-borde readonly style=text-align:right   size=17>";
			 }
		 $ld_monscg = $ld_monret = 0;
		 $la_scgdat = $this->io_xml->uf_load_contable_liquidacion($as_filnam);
		 if (!empty($la_scgdat))
		    {
			  $li_totrow = count($la_scgdat);
			  if ($li_totrow>0)
			     {
				   $ls_scgcta = trim($la_scgdat[1]['scg_cuenta']);
				   if (!empty($ls_scgcta))
				      {
					    $lb_existe = $this->io_xml->uf_validar_scgcuenta($this->ls_codemp,$ls_scgcta);
					    if ($lb_existe)
						   {
							 $li_totrowscg++;
							 $ls_denscgcta = $this->uf_load_denctascg($ls_scgcta);
							 $ld_monscg    = number_format($la_scgdat[1]['monto'],2,'.','');
							 $ld_monret    = $ld_monscg;
							 $ld_monscg    = number_format($la_scgdat[1]['monto'],2,',','.');					    
							 $la_objscg[$li_i][1] = "<input type=text name=txtscgcta".$li_i."    id=txtscgcta".$li_i."    value='".$ls_scgcta."'    class=sin-borde readonly style=text-align:center  size=15 maxlength=25>";
							 $la_objscg[$li_i][2] = "<input type=text name=txtdenscgcta".$li_i." id=txtdenscgcta".$li_i." value='".$ls_denscgcta."' class=sin-borde readonly style=text-align:left    size=71 maxlength=500 title='".$ls_denscgcta."'>";
							 $la_objscg[$li_i][3] = "<input type=text name=txtdebhab".$li_i."    id=txtdebhab".$li_i."    value='Haber' 			   class=sin-borde readonly style=text-align:center  size=20>";
							 $la_objscg[$li_i][4] = "<input type=text name=txtmonscg".$li_i."    id=txtmonscg".$li_i."    value='".$ld_monscg."'    class=sin-borde readonly style=text-align:right   size=17>";
							 $li_i++;
						   }
					    else
						   {
							 $lb_valido = false;
							 $ls_errmsg = "$as_filnam.- Cuenta Contable No Encontrada !!!";
							 $this->io_msg->message($ls_errmsg);					    
							 $ls_rutdes = "../scc/liquidacion/procesados/";
							 $ls_filnam = trim(substr($as_filnam,30,254));
							 $lb_copval = $this->io_xml->uf_mover_xml($ls_filnam,$as_filnam,$ls_rutdes);
							 if ($lb_copval)
							    {
								  $this->io_xml->uf_update_xml_procesado($ls_filnam,$ls_rutdes,"SCB_MOVBCO",$lb_valido,$ls_errmsg);
								  unset($ls_filnam,$ls_rutdes,$ls_errmsg,$as_filnam);
							    }
						   } 
					  }
				 }			
			}
         if ($lb_valido)
		    {
			  $ls_denscgcta = "";
			  if ($as_codope=='ND')
				 {
				   $ls_ctascg = $this->uf_load_contable($as_codban,$as_ctaban,$ls_denscgcta);
				 }
			  $ld_montotscg = number_format($ad_montotscg,2,'.','');
			  $ld_montotscg = number_format($ad_montotscg-$ld_monret,2,',','.');
			  $la_objscg[$li_i][1] = "<input type=text name=txtscgcta".$li_i."     id=txtscgcta".$li_i."    value='".$ls_ctascg."'    class=sin-borde readonly style=text-align:center  size=15 maxlength=25>";
			  $la_objscg[$li_i][2] = "<input type=text name=txtdenscgcta".$li_i."  id=txtdenscgcta".$li_i." value='".$ls_denscgcta."' class=sin-borde readonly style=text-align:left    size=71 maxlength=500 title='".$ls_denscgcta."'>";
			  $la_objscg[$li_i][3] = "<input type=text name=txtdebhab".$li_i."     id=txtdebhab".$li_i."    value='Haber'             class=sin-borde readonly style=text-align:center  size=20>";
			  $la_objscg[$li_i][4] = "<input type=text name=txtmonscg".$li_i."     id=txtmonscg".$li_i."    value='".$ld_montotscg."' class=sin-borde readonly style=text-align:right   size=17>";
			  $li_totrowscg++;			
			}		 
	     else
		    {
			  $li_totrowscg = 1;
			  $la_objscg[$li_totrowscg][1] = "<input type=text    name=txtscgcta".$li_totrowscg."     id=txtscgcta".$li_totrowscg."    value='' class=sin-borde readonly style=text-align:center  size=15 maxlength=25>";
	          $la_objscg[$li_totrowscg][2] = "<input type=text    name=txtdenscgcta".$li_totrowscg."  id=txtdenscgcta".$li_totrowscg." value='' class=sin-borde readonly style=text-align:center  size=71 maxlength=500>";
	          $la_objscg[$li_totrowscg][3] = "<input type=text    name=txtdebhab".$li_totrowscg."     id=txtdebhab".$li_totrowscg."    value='' class=sin-borde readonly style=text-align:center  size=20>";
	          $la_objscg[$li_totrowscg][4] = "<input type=text    name=txtmonscg".$li_totrowscg."     id=txtmonscg".$li_totrowscg."    value='' class=sin-borde readonly style=text-align:center  size=17>
							                  <input type=hidden  name=txtdesmovscg".$li_totrowscg."  id=txtdesmovscg".$li_totrowscg." value=''>";
			}
		 return $la_objscg;
	}
	
	function uf_load_nomban_denctaban($as_codban,$as_ctaban,&$ls_denctaban)
	{
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	     Function: uf_load_nomban_denctaban
	  //		   Access: private
	  //	    Arguments: $as_codban    = Código del Banco.
	  //                   $as_ctaban    = Número de la Cuenta Bancaria.
	  //                   $ls_denctaban = Nombre de la Cuenta Bancaria.
	  //	      Returns: Nombre del Banco.
	  //	  Description: Método que se encargar de extraer los nombres del Banco y de la Cuenta Bancaria que viene por parámetro.
	  //	   Creado Por: Ing. Néstor Falcón.
	  //   Fecha Creación: 29/07/2008. 							Fecha Última Modificación : 29/07/2008.
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
      $ls_sql = "SELECT scb_banco.nomban, scb_ctabanco.dencta
	               FROM scb_banco, scb_ctabanco
				  WHERE scb_banco.codemp='".$this->ls_codemp."'
				    AND scb_banco.codban='".$as_codban."'
					AND scb_ctabanco.ctaban='".$as_ctaban."'
				    AND scb_banco.codemp=scb_ctabanco.codemp
					AND scb_banco.codban=scb_ctabanco.codban";
					
	  $rs_data = $this->io_sql->select($ls_sql);
	  if ($rs_data===false)
	     {
		   $lb_valido = false;
		   $this->io_msg->message("CLASE->sigesp_scb_c_liquidacion_creditos.php->MÉTODO->uf_load_nombre_beneficiario;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		   echo $this->io_sql->message;
		 }
	  else
	     {
		   if ($row=$this->io_sql->fetch_row($rs_data))
		      {
			    $ls_nomban    = $row["nomban"];
			    $ls_denctaban = $row["dencta"];
			    unset($rs_data,$row);
			  }
		 }
	  return $ls_nomban;  
	}
	
	function uf_procesar_liquidacion($as_filnam,$aa_datos,$ai_totrows,$ai_totrowscg,$aa_seguridad)
	{
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	     Function: uf_procesar_liquidacion.
	  //		   Access: private
	  //	    Arguments: $as_filnam    =
	  //                   $aa_datos     =
	  //                   $ai_totrows   =
	  //                   $ai_totrowscg =
	  //                   $aa_seguridad =
	  //	  Description: Método que se encarga de Insertar el Movimiento Bancario y sus detalles Presupuestarios/Contables.
	  //	   Creado Por: Ing. Néstor Falcón.
	  //   Fecha Creación: 29/07/2008. 							Fecha Última Modificación : 29/07/2008.
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  
	  $lb_valido = true;
	  $ls_errmsg = "";
	  $this->io_sql->begin_transaction();
	  $ls_codban = $aa_datos["codban"];	  
	  $ld_fecmov = $aa_datos["fecmov"];	  
	  $lb_valido = $this->io_xml->uf_validar_banco($this->ls_codemp,$ls_codban);
	  if ($lb_valido)
	     {
		   $ls_ctaban = $aa_datos["ctaban"];
		   $lb_valido = $this->io_xml->uf_validar_cuenta_bancaria($this->ls_codemp,$ls_codban,$ls_ctaban);
		   if ($lb_valido)
		      {
			    $ls_codope = $aa_datos["codope"];
				if ($ls_codope=='CH' || $ls_codope=='ND')
				   {
				     $ls_cedben = $aa_datos["cedben"];
					 $lb_valido = $this->io_xml->uf_validar_beneficiario($this->ls_codemp,$ls_cedben);
					 if ($lb_valido)
					    {
						  $ls_numdoc 	= $aa_datos["numdoc"];	  
						  $ls_fecmov 	= $aa_datos["fecmov"];
						  $ld_mondoc 	= $aa_datos["mondoc"];
						  $ls_conmov 	= $aa_datos["conmov"]; 
						  $ls_nomproben = $aa_datos["nomben"];
						  $ls_chevau    = $aa_datos["chevau"]; 
						  $lb_existe = false;
						  if ($lb_valido)
							 {
							   $lb_existe = $this->io_xml->uf_load_movimiento_bancario($this->ls_codemp,$ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope);
							   if ($lb_existe)
							      {
								    $ls_errmsg = "$as_filnam.- El Documento ya se encuentra Registrado !!!";
								    $this->io_msg->message($ls_errmsg);
								    $lb_valido = false;
								  }
							 }
						  if (!$lb_existe && $lb_valido)
							 {
							   $lb_valido = $this->uf_insert_movimiento_banco($aa_datos,$aa_seguridad);
							 }
						  if ($lb_valido)
						     {
							   $lb_valido = $this->uf_insert_movimiento_banco_spg($ai_totrows,$aa_datos,$aa_seguridad);
							 }
						  if ($lb_valido)
						     {
							   $lb_valido = $this->uf_insert_movimiento_banco_scg($ai_totrowscg,$aa_datos,$aa_seguridad);							 
							 }
						  unset($aa_datos);
						}
				     else
					    {
					      $ls_errmsg = "$as_filnam.- Código/Cédula del Beneficiario no encontrado !!!";
					      $this->io_msg->message($ls_errmsg);
					      return false;
						}
				   }
			    else
				   {
				     $ls_errmsg = "$as_filnam.- Error en Tipo de Operacion, solo estan permitidos CH=Cheques y ND=Notas de Debito !!!";
				     $this->io_msg->message($ls_errmsg);
				     $lb_valido = false;
				   }
			  }
		   else
		      {
			    $ls_errmsg = "$as_filnam.- Cuenta Bancaria no encontrada !!!";
			    $this->io_msg->message($ls_errmsg);
			    $lb_valido = false;
			  }
		 }	
	  else
	     {
		   $ls_errmsg = "$as_filnam.- Codigo del Banco no encontrado !!!"; 
		   $this->io_msg->message($ls_errmsg);
		   $lb_valido = false;
		 }
      $ls_filnam = $_POST["hidfilnam"];
	  $ls_rutdes = "../scc/liquidacion/procesados/";
	  $lb_copval = $this->io_xml->uf_mover_xml($ls_filnam,$as_filnam,$ls_rutdes);
	  if ($lb_copval)
		 {
		   $this->io_xml->uf_update_xml_liquidacion($ls_filnam,$ls_rutdes,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ls_chevau,$ls_conmov,$ld_fecmov,$lb_valido,$ls_errmsg);
		 }
	  if ($lb_valido)
	     {
		   $this->io_sql->commit();
		   $this->io_msg->message("Movimiento Registrado con Éxito !!!");
		 }
	  else
	     {
		   $this->io_sql->rollback();
		   $this->io_msg->message("Error en Registro de Movimiento !!!");
		 }	 
	  return $lb_valido;
	}
	
	function uf_insert_movimiento_banco($aa_datos,$aa_seguridad)
	{
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	     Function: uf_insert_movimiento_banco
	  //		   Access: private
	  //	    Arguments: $as_codban
	  //                   $as_ctaban
	  //                   $as_numdoc
	  //                   $as_codope
	  //                   $as_cedben
	  //                   $as_nomproben
	  //                   $as_fecmov
	  //                   $ad_mondoc
	  //                   $aa_seguridad
	  //	      Returns: Arreglo cargado con la cabecera para ser procesado el movimiento bancario.
	  //	  Description: .
	  //	   Creado Por: Ing. Néstor Falcón.
	  //   Fecha Creación: 07/07/2008. 							Fecha Última Modificación : 07/07/2008.
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	  $lb_valido = true;	
	  $ls_codban = $aa_datos["codban"];
	  $ls_ctaban = $aa_datos["ctaban"];  
	  $ls_numdoc = str_pad($aa_datos["numdoc"],15,0,0);
	  $ls_codope = $aa_datos["codope"];
	  if ($ls_codope=='CH')
	     {
		   $ls_chevau = $aa_datos["chevau"];
		   $ls_prodoc = "SCBBCH";
		 }
	  elseif($ls_codope=='ND')
	     {
		   $ls_chevau = "";
		   $ls_prodoc = "SCBBND";		 
		 }
	  else
	     {
		   return false;		   		 
		 }
		 
	  $ls_cedben = $aa_datos["cedben"];
	  $ls_nomben = $aa_datos["nombenalt"];//Nombre del Beneficiario Alterno.
	  if (empty($ls_nomben))
	     {
		   $ls_nomben = $aa_datos["nomben"]; 
		 }
	  
	  $ls_fecmov = $this->io_funcion->uf_convertirdatetobd($aa_datos["fecmov"]);
	  $ld_monmov = $aa_datos["mondoc"];
	  $ld_monret = $aa_datos["monret"];
	  $ls_conmov = $aa_datos["conmov"];
	  
	  $li_pos = strpos($ld_monmov,',');
	  if (!empty($li_pos))
	     {
		   $ld_monmov = str_replace('.','',$ld_monmov);
		   $ld_monmov = str_replace(',','.',$ld_monmov);
		 }

	  $ls_sql = "INSERT INTO scb_movbco (codemp,codban,ctaban,numdoc,codope,estmov,cod_pro,ced_bene,tipo_destino,codconmov,fecmov,conmov,
	                          nomproben,monto,estbpd,estcon,estcobing,esttra,chevau,estimpche,monobjret,monret,procede,comprobante,
							  fecha,id_mco,emicheproc,emicheced,emichenom,emichefec,estmovint,codusu,codopeidb,aliidb,feccon,
							  estreglib,numcarord,numpolcon,coduniadmsig,codbansig,fecordpagsig,tipdocressig,numdocressig,
							  estmodordpag,codfuefin,forpagsig,medpagsig,codestprosig,fechaconta,fechaanula) 
					  VALUES ('".$this->ls_codemp."','".$ls_codban."','".$ls_ctaban."','".$ls_numdoc."','".$ls_codope."','N','----------',
					          '".$ls_cedben."','B','---','".$ls_fecmov."','".$ls_conmov."','".$ls_nomben."',".$ld_monmov.",
							  'M',0,0,1,'".$ls_chevau."',0,".$ld_monmov.",".$ld_monret.",'".$ls_prodoc."','".$ls_numdoc."','1900-01-01','',0,'','','1900-01-01',0,'CREDITO',
							  '--',0.00,'1900-01-01','','',0,'','',NULL,'','','',NULL,NULL,NULL,NULL,'1900-01-01','1900-01-01')";
	  
	  $rs_data = $this->io_sql->execute($ls_sql);//echo $ls_sql.'<br>';
	  if ($rs_data===false)
	     {
		   $lb_valido = false;
		   $this->io_msg->message("CLASE->sigesp_scb_c_liquidacion_creditos.php->MÉTODO->uf_insert_movimiento_banco;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		   echo $this->io_sql->message;
		 }
	  else
	     {
		   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		   $ls_evento="INSERT";
		   $ls_descripcion = "Insertó Movimiento Bancario ".$ls_numdoc.",Banco $ls_codban,Cuenta $ls_ctaban, Operacion $ls_codope,Estatus N y Procede SICCRE, Asociado a la empresa ".$this->ls_codemp;
		   $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
		   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		 }
	  return $lb_valido;	
	}
	
	function uf_insert_movimiento_banco_spg($ai_totrows,$aa_datos,$aa_seguridad)
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
	  if ($ai_totrows>0)
		 {
	       $ls_codban = $aa_datos["codban"];
		   $ls_ctaban = $aa_datos["ctaban"];  
		   $ls_numdoc = str_pad($aa_datos["numdoc"],15,0,0);
		   $ls_docnum = str_pad($aa_datos["docnum"],15,0,0);
		   $ls_codope = $aa_datos["codope"];
		   $ls_fecmov = $this->io_funcion->uf_convertirdatetobd($aa_datos["fecmov"]);
		   $ls_conmov = $aa_datos["conmov"];

		   for ($li_x=1;$li_x<=$ai_totrows;$li_x++)
			   {
			     $ls_spgcta    = trim($_POST["txtspgcta".$li_x]);
			     $ls_codestpro = $_POST["hidcodestpre".$li_x]; 
				 $ld_mondetspg = $_POST["txtmondetspg".$li_x];
				 $ld_mondetspg = str_replace('.','',$ld_mondetspg);
				 $ld_mondetspg = str_replace(',','.',$ld_mondetspg);
				 $ls_denestcla = $_POST["txtcodtipest".$li_x];
				 if ($ls_denestcla=='Proyecto')
				    {
					  $ls_estcla = 'P';
					}
	             elseif($ls_denestcla=='Acción')
				    {
					  $ls_estcla = 'A';
					}
				 $ls_sql = "INSERT INTO scb_movbco_spg (codemp, codban, ctaban, numdoc, codope, estmov, codestpro, spg_cuenta, estcla, documento, operacion, desmov, procede_doc, monto)
					  		     VALUES ('".$this->ls_codemp."','".$ls_codban."','".$ls_ctaban."','".$ls_numdoc."','".$ls_codope."','N',
					                     '".$ls_codestpro."','".$ls_spgcta."','".$ls_estcla."','".$ls_docnum."','CP','".$ls_conmov."','SEPSPC',".$ld_mondetspg.")";
					  $rs_data = $this->io_sql->execute($ls_sql);
					  if ($rs_data===false)
						 {
						   $lb_valido = false;
						   $this->io_msg->message("CLASE->sigesp_scb_c_liquidacion_creditos.php->MÉTODO->uf_insert_movimiento_banco_spg;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
						   echo $this->io_sql->message;
						 }
			          else
					     {
						   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
						   $ls_evento="INSERT";
						   $ls_descripcion = "Insertó Movimiento Bancario de Presupuestario de Gasto ".$ls_numdoc.",Banco $ls_codban,Cuenta $ls_ctaban,Operacion $ls_codope,Estatus N, Cuenta $ls_spgcta - CP por $ld_mondetspg, y Procede SICCRE, Asociado a la empresa ".$this->ls_codemp;
						   $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
															$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
															$aa_seguridad["ventanas"],$ls_descripcion);
						   /////////////////////////////////         SEGURIDAD               /////////////////////////////								 
						 }
			   }
	     }
	  return $lb_valido;	
	}

	function uf_insert_movimiento_banco_scg($ai_totrowscg,$aa_datos,$aa_seguridad)
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
	  if ($ai_totrowscg>0)
		 {
	       $ls_codban = $aa_datos["codban"];
		   $ls_ctaban = $aa_datos["ctaban"];  
		   $ls_numdoc = str_pad($aa_datos["numdoc"],15,0,0);
		   $ls_docnum = str_pad($aa_datos["docnum"],15,0,0);
		   $ls_codope = $aa_datos["codope"];
		   $ls_fecmov = $this->io_funcion->uf_convertirdatetobd($aa_datos["fecmov"]);
		   $ls_conmov = $aa_datos["conmov"];
		   
		   $ld_montotdeb = $ld_montothab = 0;
		   for ($li_x=1;$li_x<=$ai_totrowscg;$li_x++)
			   {
			     $ls_scgcta    = trim($_POST["txtscgcta".$li_x]);
			     $ls_dendebhab = $_POST["txtdebhab".$li_x]; 
				 $ld_mondetscg = $_POST["txtmonscg".$li_x];
				 $ld_mondetscg = str_replace('.','',$ld_mondetscg);
				 $ld_mondetscg = str_replace(',','.',$ld_mondetscg);
				 $ls_debhab    = substr($ls_dendebhab,0,1);
				 if ($ls_debhab=='D')
				    {
					  $ld_montotdeb += $ld_mondetscg;
					}
				 elseif($ls_debhab=='H')
				    {
					  $ld_montothab += $ld_mondetscg;
				    }
				 $ls_sql = "INSERT INTO scb_movbco_scg (codemp,codban,ctaban,numdoc,codope,estmov,scg_cuenta,debhab,codded,documento,desmov,procede_doc,monto,monobjret)
					             VALUES ('".$this->ls_codemp."','".$ls_codban."','".$ls_ctaban."','".$ls_numdoc."','".$ls_codope."','N',
					                    '".$ls_scgcta."','".$ls_debhab."','00000','".$ls_docnum."','".$ls_conmov."','SEPSPC',".$ld_mondetscg.",0.00)";
					  $rs_data = $this->io_sql->execute($ls_sql);
					  if ($rs_data===false)
						 {
						   $lb_valido = false;
						   $this->io_msg->message("CLASE->sigesp_scb_c_liquidacion_creditos.php->MÉTODO->uf_insert_movimiento_banco_scg;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
						   echo $this->io_sql->message;
						 }
					  else
					     {
						   /////////////////////////////////         SEGURIDAD               /////////////////////////////		
						   $ls_evento="INSERT";
						   $ls_descripcion = "Insertó Movimiento Bancario Contable ".$ls_numdoc.",Banco $ls_codban,Cuenta $ls_ctaban,Operacion $ls_codope,Estatus N, Cuenta $ls_scgcta - $ls_debhab por $ld_mondetscg, y Procede SICCRE, Asociado a la empresa ".$this->ls_codemp;
						   $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
															$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
															$aa_seguridad["ventanas"],$ls_descripcion);
						   /////////////////////////////////         SEGURIDAD               /////////////////////////////								 
						 }
			   }
	       if ($ld_montotdeb!=$ld_montothab)
		      {
			    $ls_errmsg = "Movimiento Contable descuadrado, total Debe diferente al total Haber !!!";
				$this->io_msg->message($ls_errmsg);
				return false;
			  }
		 }
	  return $lb_valido;	
	}
	
	function uf_load_numsep($as_numsep,&$ld_montotsep,&$ls_fecsep,&$ls_estsol,&$ls_modsep)
	{
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	     Function: uf_load_numsep
	  //		   Access: private
	  //	    Arguments: $as_numsep    = Número de la SEP (Crédito).
	  //                   $ld_montotsep = Monto total de la SEP (Crédito).
	  //                   $ls_fecsep    = Fecha de Registro de la SEP (Crédito).
	  //				   $ls_estsol    = Estatus de la Sep.(Sólo se procesarán las Contabilizadas).
	  //				   $ls_modsep    = Tipo de la SEP. Sólo se procesarán las de Tipo O=Concepto).
	  //	      Returns: Existencia de la SEP (Crédito).
	  //	  Description: Método que se encargar de extraer existencia,monto y fecha de la SEP que viene por parámetro.
	  //	   Creado Por: Ing. Néstor Falcón.
	  //   Fecha Creación: 11/08/2008. 							Fecha Última Modificación : 11/08/2008.
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	  $lb_existe = false;
	  $ls_sql = "SELECT sep_solicitud.numsol,COALESCE(sep_solicitud.monto,0) as monto,sep_solicitud.fecregsol,
	                    sep_solicitud.estsol, sep_tiposolicitud.modsep
	               FROM sep_solicitud, sep_tiposolicitud
				  WHERE codemp = '".$this->ls_codemp."' 
				    AND numsol = '".$as_numsep."'
					AND sep_solicitud.codtipsol=sep_tiposolicitud.codtipsol";
	  $rs_data = $this->io_sql->select($ls_sql);
	  if ($rs_data===false)
	     {
		   $lb_valido = false;
		   $this->io_msg->message("CLASE->sigesp_scb_c_liquidacion_creditos.php->MÉTODO->uf_load_numsep;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		   echo $this->io_sql->message;
		 }
	  else
	     {
		   if ($row=$this->io_sql->fetch_row($rs_data))
		      {
			    $ld_montotsep = $row["monto"];
			    $ls_fecsep    = $row["fecregsol"];
				$ls_estsol    = $row["estsol"];
				$ls_modsep    = $row["modsep"];
				$lb_existe    = true;
			    unset($rs_data,$row);
			  }
		 }
	  return $lb_existe;	
	}
	
	function uf_load_pagos_previos($as_numsep)
	{
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	     Function: uf_load_pagos_previos
	  //		   Access: private
	  //	    Arguments: $as_numsep    = Número de la SEP (Crédito).
	  //	      Returns: Monto con los pagos realizados Previamente.
	  //	  Description: Método que se encarga de extraer el Total de Abonos realizados al Crédito (SEP).
	  //	   Creado Por: Ing. Néstor Falcón.
	  //   Fecha Creación: 11/08/2008. 							Fecha Última Modificación : 11/08/2008.
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	  $lb_existe = false;
	  $ld_totpagpre = 0;
	  $ls_sql = "SELECT SUM(COALESCE(scb_movbco_spg.monto,0)) as totpagpre
				   FROM scb_movbco, scb_movbco_spg 
				  WHERE scb_movbco.codemp = '".$this->ls_codemp."'
				    AND scb_movbco.cod_pro='----------'
				    AND scb_movbco.tipo_destino = 'B'
				    AND scb_movbco_spg.documento = '".$as_numsep."'
				    AND scb_movbco_spg.procede_doc = 'SEPSPC'
				    AND scb_movbco.codusu='CREDITO'
				    AND (scb_movbco.codope='CH' OR scb_movbco.codope='ND')
				    AND (scb_movbco.estmov<>'A' OR scb_movbco.estmov<>'O')
				    AND scb_movbco.codemp=scb_movbco_spg.codemp
				    AND scb_movbco.codban=scb_movbco_spg.codban
				    AND scb_movbco.ctaban=scb_movbco_spg.ctaban
				    AND scb_movbco.numdoc=scb_movbco_spg.numdoc
				    AND scb_movbco.estmov=scb_movbco_spg.estmov					
				  GROUP BY scb_movbco_spg.codemp,scb_movbco_spg.documento";
	  $rs_data = $this->io_sql->select($ls_sql);
	  if ($rs_data===false)
	     {
		   $lb_valido = false;
		   $this->io_msg->message("CLASE->sigesp_scb_c_liquidacion_creditos.php->MÉTODO->uf_load_pagos_previos;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		   echo $this->io_sql->message;
		 }
	  else
	     {
		   if ($row=$this->io_sql->fetch_row($rs_data))
		      {
			    $ld_totpagpre = $row["totpagpre"];
			    unset($rs_data,$row);
			  }
		 }
	  return $ld_totpagpre;	
	}
	
	function uf_load_denctascg($as_scgcta)
	{
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	     Function: uf_load_denctascg
	  //		   Access: private
	  //	    Arguments: $as_scgcta = Código Contable.
	  //	      Returns: .
	  //	  Description: .
	  //	   Creado Por: Ing. Néstor Falcón.
	  //   Fecha Creación: 01/09/2008. 							Fecha Última Modificación : 28/07/2008.
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	  $ls_sql = "SELECT LTRIM(denominacion) as denctascg
	               FROM scg_cuentas
				  WHERE codemp    = '".$this->ls_codemp."'
				    AND sc_cuenta = '".$as_scgcta."'";
										
	  $rs_data = $this->io_sql->select($ls_sql);
	  if ($rs_data===false)
	     {
		   $lb_valido = false;
		   $this->io_msg->message("CLASE->sigesp_scb_c_liquidacion_creditos.php->MÉTODO->uf_load_denctascg;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		   echo $this->io_sql->message;
		 }
	  else
	     {
		   if ($row=$this->io_sql->fetch_row($rs_data))
		      {
				$ls_denctascg = $row["denctascg"];
			    unset($rs_data,$row);
			  }
		 }
	  return $ls_denctascg;
	}
}	
?>  