<?php
class sigesp_soc_c_generar_orden_analisis
{
  function sigesp_soc_c_generar_orden_analisis($as_path)
  {
	////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: sigesp_soc_c_generar_orden_analisis
	//		   Access: public 
	//	  Description: Constructor de la Clase
	//	   Creado Por: Ing. Laura Cabré
	// Fecha Creación: 05/08/2007 								Fecha Última Modificación : 29/05/2007 
	////////////////////////////////////////////////////////////////////////////////////////////////////
        require_once($as_path."shared/class_folder/sigesp_include.php");
		require_once($as_path."shared/class_folder/class_sql.php");
		require_once($as_path."shared/class_folder/class_funciones.php");
		require_once($as_path."shared/class_folder/sigesp_c_seguridad.php");
		require_once($as_path."shared/class_folder/class_mensajes.php");
		require_once($as_path."shared/class_folder/class_datastore.php");
		require_once($as_path."shared/class_folder/class_datastore.php");
		require_once($as_path."shared/class_folder/sigesp_c_generar_consecutivo.php");
        require_once($as_path."shared/class_folder/evaluate_formula.php");
		$io_include			= new sigesp_include();
		$io_conexion		= $io_include->uf_conectar();
		$this->io_sql       = new class_sql($io_conexion);	
		$this->io_mensajes  = new class_mensajes();		
		$this->io_funciones = new class_funciones();	
		$this->io_seguridad = new sigesp_c_seguridad();
		$this->ls_codemp    = $_SESSION["la_empresa"]["codemp"];
		$this->io_dscuentas = new class_datastore();
		$this->io_dscargos  = new class_datastore();
		$this->io_keygen    = new sigesp_c_generar_consecutivo(); 
		$this->io_evaluate  = new evaluate_formula(); 
  }

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_analisis_cotizacion($as_numanacot,$ad_fecdes,$ad_fechas,$as_tipanacot)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_analisis_cotizacion
		//		   Access: public
		//		 Argument: 
		//   $as_numanacot //Número del Análisis de Cotizacion
		//      $ad_fecdes //Fecha a partir del cual comenzará la búsqueda de los Análisis de Cotizacion
		//      $ad_fechas //Fecha hasta el cual comenzará la búsqueda de los Análisis de Cotizacion
		//   $as_tipanacot//Tipo del Analisis de Cotizacion B=Bienes , S=Servicios.
		//      $as_tipope //Tipo de la Operación a ejecutar A=Aprobacion, R=Reverso de la Aprobación.
		//	  Description: Función que busca los Analisis de Cotizacion que esten dispuestas para Aprobacion/Reverso.
		//	   Creado Por: Ing. Laura Cabre
		// Fecha Creación: 05/08/2007								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = true;
        $ls_straux = "";
		
        if (!empty($as_numordcom))
		   {
		     $ls_straux = " AND a.numanacot LIKE '%".$as_numanacot."%'";
		   } 
		if (!empty($ad_fecdes) && !empty($ad_fechas))
		   {  
		     $ld_fecdes = $this->io_funciones->uf_convertirdatetobd($ad_fecdes);
			 $ld_fechas = $this->io_funciones->uf_convertirdatetobd($ad_fechas);
			 $ls_straux = $ls_straux." AND a.fecanacot BETWEEN '".$ld_fecdes."' AND '".$ld_fechas."'";
		   }
		if ($as_tipanacot!='-')
		   {  
		     $ls_straux = $ls_straux." AND tipsolcot='".$as_tipanacot."'";
		   }
		$ls_sql ="SELECT DISTINCT a.numanacot,a.obsana,a.fecanacot,a.tipsolcot,a.fecapro
				    FROM soc_analisicotizacion a
		           WHERE a.codemp='$this->ls_codemp' $ls_straux 
					 AND a.estana=1 
					 AND a.numanacot NOT IN (SELECT CASE WHEN numanacot IS NULL THEN '-------' ELSE numanacot END
					                           FROM soc_ordencompra 
											  WHERE codemp='$this->ls_codemp' AND estcom<>3) 
				 ORDER BY numanacot ASC";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->sigesp_soc_c_aprobacion_analisis_cotizacion.php->MÉTODO->uf_load_analisis_cotizacion.ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_ordenes_compra
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($ai_totrows,$as_tipope,$ad_fecope,$aa_seguridad)
	{
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_guardar
	//		   Access: public
	//		 Argument: 
	//     $ai_totrows //Total de elementos cargados en el Grid de Analisis de Cotizacion
	//      $as_tipope //Tipo de la Operación a realizar A=Aprobación, R=Reverso de Aprobación.
	//      $ad_fecope //Fecha en la cual se ejecuta la Operación.
	//   $aa_seguridad //Arreglo de seguridad cargado de la informacion de usuario y pantalla.
	//	  Description: Función que recorre el grid de los analisis de cotizacion y genera las respectivas ordenes de compra
	//	   Creado Por: Ing. Laura Cabré
	// Fecha Creación: 09/08/2007								Fecha Última Modificación : 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = false;
	  $ld_fecha=$this->io_funciones->uf_convertirdatetobd($ad_fecope);
	  $ls_tipafeiva = $_SESSION["la_empresa"]["confiva"]; 
	  $this->io_sql->begin_transaction();
	  for ($i=1;$i<=$ai_totrows;$i++)
		  {
			if (array_key_exists("chk".$i,$_POST))
			   {
					$ls_numanacot = $_POST["txtnumanacot".$i];
				 	if($_POST["txttipanacot".$i] == "Bienes")
					 	$ls_tipsolcot = "B";
					else
						$ls_tipsolcot = "S";		 	
					$ls_obsana = $_POST["txtobsanacot".$i];				
					$la_ganadores=$this->uf_select_cotizacion_analisis($ls_numanacot,$ls_tipsolcot);
					$li_totalganadores=count($la_ganadores);
					$ls_numordcom="";
					for($li_i=0;$li_i<$li_totalganadores;$li_i++)
					{
						$ls_proveedor		= $la_ganadores[$li_i]["cod_pro"];
						$ls_cotizacion		= $la_ganadores[$li_i]["numcot"];
						$ls_tipo_proveedor	= $la_ganadores[$li_i]["tipconpro"];
						$this->uf_select_items($ls_cotizacion,$ls_proveedor,$ls_numanacot,$ls_tipsolcot,$la_items,$li_totrow);
						$this->uf_select_items_cotizacion($ls_cotizacion,$ls_proveedor,$ls_numanacot,$ls_tipsolcot,$la_items_cotizacion,$li_totrow_cotizacion);
						$this->uf_calculardetalles_montos($li_totrow_cotizacion,&$la_items_cotizacion,$ls_tipsolcot,$ls_tipo_proveedor,$ls_cotizacion);
						$this->uf_calcular_montos($li_totrow_cotizacion,$la_items_cotizacion,$la_totales,$ls_tipo_proveedor);
						$this->uf_viene_de_sep($ls_cotizacion,$ls_proveedor,$lb_viene_sep);
						$li_subtotal   = $la_totales["subtotal"];
						$li_totaliva   = $la_totales["totaliva"];
						$li_total      = $la_totales["total"];	
						if ($ls_tipsolcot=='B')
						   {
						     $ls_procede = 'SOCCOC';
						     $ls_numini  = 'numordcom';//Número Inicial.
						   }
						elseif($ls_tipsolcot=='S')
						   {
						     $ls_procede = 'SOCCOS';
							 $ls_numini  = 'numordser';//Número Inicial.
						   }
						$ls_numordcom = $this->io_keygen->uf_generar_numero_nuevo('SOC','soc_ordencompra','numordcom',$ls_procede,15,$ls_numini,"estcondat",$ls_tipsolcot);
						if ($ls_numordcom==false)
						   {
						     $this->io_mensajes->message("Este documento está configurado para el manejo de Prefijos, y en este momento Ud. No tiene acceso a ninguno. Por favor diríjase al Administrador del Sistema");
							 echo "<script language=JavaScript>";
		                     echo "location.href='sigespwindow_blank.php'";
		                     echo "</script>";
						   }
						else
						   {
						     $ls_numsolaux  = $ls_numordcom;
						     $lb_valido=$this->uf_select_solicitud($ls_numanacot,$ls_concepto,$ls_unidad,$ls_uniejeaso,$ls_tipbiesolcot);
						     if ($lb_valido)
						        { 
							      $ls_codestpro1 = $ls_codestpro2 = $ls_codestpro3 = $ls_codestpro4 = $ls_codestpro5 = $ls_estcla = "";
							      $lb_valido = $this->uf_select_unidades_ejecutoras($ls_numanacot, $lb_viene_sep,$la_items,$li_totrow,$la_unidades,$ls_concepto,$ls_unidad,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla);
							      if ($lb_valido)
						 	         { 
								       $lb_valido=$this->uf_insert_orden_compra($ls_proveedor,$li_total,$li_totaliva, $li_subtotal,$aa_seguridad,$ls_tipsolcot,$ls_numanacot,$ls_obsana,$ld_fecha,$ls_concepto,$ls_unidad,
								                                         $ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_uniejeaso,$ls_tipbiesolcot,&$ls_numordcom);
								       if ($lb_valido)
								          { 
										  
									        if ($lb_viene_sep)
									           { 
										         $lb_valido=$this->uf_insert_enlace_sep($ls_numordcom,$ls_tipsolcot,0,$la_unidades,$aa_seguridad);								
									           }
									        if ($lb_valido)
									           { 			 	
										    if ($ls_tipsolcot=="B")
										       {
											     $lb_valido=$this->uf_insert_bienes($ls_numordcom,$aa_seguridad,$ls_proveedor,$ls_cotizacion,$la_items_cotizacion,$li_totrow_cotizacion,$ls_tipo_proveedor);
										       }
										    elseif($ls_tipsolcot=="S")
										       {
											     $lb_valido=$this->uf_insert_servicios($ls_numordcom,$aa_seguridad,$ls_proveedor,$ls_cotizacion,$la_items_cotizacion,$li_totrow_cotizacion,$ls_tipo_proveedor);
										       }
										   if($lb_valido)//Si la afectacion del Iva es Presupuestaria.
										       {
											     $lb_valido=$this->uf_insert_cuentas_presupuestarias($ls_numordcom,$ls_tipsolcot,$la_items,$li_totrow,$la_items_cotizacion,$li_totrow_cotizacion,$aa_seguridad,$ls_tipo_proveedor,$ls_cotizacion,$lb_viene_sep,$ls_proveedor);											
										       }
										    if ($lb_valido && ($ls_tipo_proveedor != "F")) // si el proveedor es de tipo formal no se le calculan los cargos
										       { 
											     $lb_valido=$this->uf_insert_cuentas_cargos($ls_numordcom,$ls_tipsolcot,$la_items,$li_totrow,$aa_seguridad,$lb_viene_sep);
										       }
										    if ($lb_valido)
										       {
											     $ls_estcom=0;
											     $lb_valido=$this->uf_validar_cuentas($ls_numordcom,$ls_estcom,$ls_tipsolcot);
										       }
										    if (!$lb_valido)
										       {
											     break;
										       }
									      }								
								}
							         }
						        }
						   }
					}
				 if (!$lb_valido)
					{
					  break;
					}
			   }
		  }
	   if ($lb_valido)
		  {
		  	if($ls_numsolaux!=$ls_numordcom)
			{
				$this->io_mensajes->message("Se Asigno el Numero a la Orden de Compra: ".$ls_numordcom);
			}
			$this->io_sql->commit();
			$this->io_mensajes->message("Operación realizada con Éxito !!!");
		    $this->io_sql->close();
		  }
	   else 
		  {
			$this->io_sql->rollback();
			$this->io_mensajes->message("Error Operación !!!");
		    $this->io_sql->close();
		  }
	}// end function uf_guardar
    //---------------------------------------------------------------------------------------------------------------------------------------	

    //---------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_cotizacion_analisis($as_numanacot, $ls_tipanacot)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_cotizacion_analisis
		//		   Access: public
		//		  return :	arreglo que contiene las cotizaciones que participaron en un determinado analisis 
		//	  Description: Metodo que  devuelve las cotizaciones que participaron en un determinado analisis
		//	   Creado Por: Ing. Laura Cabré
		// 			  Fecha: 14/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_proveedores=array();
		$lb_valido=false;	
		if($ls_tipanacot == "B")
			$ls_tabla = "soc_dtac_bienes";
		elseif($ls_tipanacot == "S")	
			$ls_tabla = "soc_dtac_servicios";
		$ls_sql= "SELECT cxa.numcot, cxa.cod_pro, p.tipconpro
				  FROM soc_cotxanalisis cxa, rpc_proveedor p
				  WHERE cxa.codemp='$this->ls_codemp' AND cxa.numanacot='$as_numanacot' 
				  AND cxa.codemp=p.codemp AND  cxa.cod_pro = p.cod_pro
				  AND cxa.numcot IN 
				  (SELECT numcot FROM $ls_tabla WHERE codemp='$this->ls_codemp')";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("ERROR->uf_select_cotizacion_analisis".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;	
		}
		else
		{
			$li_i=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$aa_proveedores[$li_i]=$row;					
				$li_i++;
			}																
		}
		return $aa_proveedores;
	}//fin de uf_select_cotizacion_analisis
	//---------------------------------------------------------------------------------------------------------------------------------------	

    //---------------------------------------------------------------------------------------------------------------------------------------	
    function uf_insert_orden_compra($as_codpro,$ai_total,$ai_totaliva, $ai_subtotal,$aa_seguridad,$as_tipsolcot,$as_numanacot,
                                    $as_observacion,$ad_fecha,$as_concepto,$ls_unidad,$as_codestpro1,$as_codestpro2,$as_codestpro3,
								    $as_codestpro4,$as_codestpro5,$as_estcla,$as_uniejeaso,$as_tipbieordcom,&$ls_numordcom)
	{/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_insert_orden_compra
	//	    Arguments: as_codpro  --->   Codigo del proveedor al cual se le esta haciendo la orden de compra
	//	      Returns: devuelve true si se inserto correctamente la cabecera de la orden de compra o false en caso contrario
	//	  Description: Funcion que que se encarga dde insertar una orden de compra
	//	   Creado Por: Ing. Laura Cabré
	// Fecha Creación: 20/06/2007 								Fecha Última Modificación : 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$as_forpag="-";
	$as_diaplacom=0;
	$lb_valido=$this->uf_load_datos_entrega($as_numanacot,$as_codpro,&$as_diaplacom,&$as_forpag);
	$ls_fecordcom=$this->io_funciones->uf_convertirdatetobd($ad_fecha);	
	if($as_tipsolcot=="B")  
	{		
		$lb_valido = $this->io_keygen->uf_verificar_numero_generado('SOC','soc_ordencompra','numordcom','SOCCOC',15,"","estcondat","B",$ls_numordcom);
		$ld_monsubtotbie=$ai_subtotal;
		$ld_monsubtotser=0;
	}
	else
	{
		$lb_valido = $this->io_keygen->uf_verificar_numero_generado('SOC','soc_ordencompra','numordcom','SOCCOS',15,"","estcondat","S",$ls_numordcom);
		$ld_monsubtotbie=0;
		$ld_monsubtotser=$ai_subtotal;
	}
	$lb_valido=true;	
	if($lb_valido)
	{
     	$ld_monsubtotbie = 0;
     	$ld_monsubtotser = 0;
     	$ld_monbasimp = 0;
     	$ld_mondes = 0;
		$li_estpenalm = 0;
		$li_estapro   = 0;
		$ld_fecaprord = "1900-01-01";
		$ls_codusuapr = "";
		$ls_numpolcon = 0;
		$ls_fecent = "1900-01-01";
		$as_rb_rblugcom = 0;
		$as_codmon='---';
		$as_codfuefin='--';
		$as_estcom=0;
		$as_codtipmod="--";
		
		$as_coduniadm=$ls_unidad;
		
		$ai_estsegcom=0;   	
		$ad_porsegcom=0;
		$ad_monsegcom=0;
		$as_concom="-";
		$as_conordcom=$as_concepto; 
		$ld_mondes=0;
		$as_codpai="---";
		$as_codest="---";
		$as_codmun="---";
		$as_codpar="---";
		$as_lugentnomdep="";
		$as_lugentdir="";
		$ad_antpag=0;
		$ad_tascamordcom=0;
		$ad_montotdiv=0;
		$as_obscom="";
		
		$ls_sql=" INSERT INTO soc_ordencompra (codemp, numordcom, estcondat, cod_pro, codmon, codfuefin, ".
		        "                              fecordcom, estsegcom, porsegcom, monsegcom, forpagcom, estcom, diaplacom, ".
				"							   concom, obscom, monsubtotbie, monsubtotser, monsubtot, monbasimp, monimp, ".
				"							   mondes, montot, estpenalm, codpai, codest, codmun, codpar, lugentnomdep, ".
				"							   lugentdir, monant, estlugcom, tascamordcom, montotdiv, estapro, fecaprord, ".
				"                              codusuapr, numpolcon, coduniadm, codestpro1,codestpro2,codestpro3,codestpro4,codestpro5, 
				                               estcla,obsordcom, fecent,numanacot,codtipmod,uniejeaso,tipbieordcom) ".
				" VALUES ('".$this->ls_codemp."','".$ls_numordcom."','".$as_tipsolcot."','".$as_codpro."','".$as_codmon."', ".
				"         '".$as_codfuefin."','".$ls_fecordcom."','".$ai_estsegcom."',".$ad_porsegcom.",".
				"         '".$ad_monsegcom."','".$as_forpag."','".$as_estcom."','".$as_diaplacom."','".$as_concom."', ".
				"         '".$as_conordcom."',".$ld_monsubtotbie.",".$ld_monsubtotser.",".$ai_subtotal.",".$ld_monbasimp.", ".
				"         ".$ai_totaliva.",".$ld_mondes.",".$ai_total.",".$li_estpenalm.",'".$as_codpai."', ".
				"         '".$as_codest."','".$as_codmun."','".$as_codpar."','".$as_lugentnomdep."','".$as_lugentdir."', ".
				"         ".$ad_antpag.",".$as_rb_rblugcom.",".$ad_tascamordcom.",".$ad_montotdiv.",".$li_estapro.", ".
				"         '".$ld_fecaprord."','".$ls_codusuapr."','".$ls_numpolcon."','".$as_coduniadm."','".$as_codestpro1."',
				          '".$as_codestpro2."','".$as_codestpro3."','".$as_codestpro4."','".$as_codestpro5."','".$as_estcla."',
						  '".$as_obscom."','".$ls_fecent."','".$as_numanacot."','".$as_codtipmod."','".$as_uniejeaso."','".$as_tipbieordcom."')";        
		$rs_data=$this->io_sql->execute($ls_sql);
		if($rs_data===false)
		{
			$this->io_sql->rollback();
		    if($this->io_sql->errno=='23505' || $this->io_sql->errno=='1062')
			{
			 	$this->uf_insert_orden_compra($as_codpro,$ai_total,$ai_totaliva, $ai_subtotal,$aa_seguridad,$as_tipsolcot,
				                              $as_numanacot,$as_observacion,$ad_fecha,$as_concepto,$ls_unidad,$as_codestpro1,
											  $as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_estcla,
											  $as_uniejeaso,$as_tipbieordcom,&$ls_numordcom);
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->sigesp_soc_c_generar_orden_analisis.php; MÉTODO->uf_insert_orden_compra ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}			
						
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la Orden de Compra ".$ls_numordcom." tipo ".$as_tipsolcot." de fecha".$ls_fecordcom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
	    }
	}
		return $lb_valido;
	}// fin uf_insert_orden_compra
    //---------------------------------------------------------------------------------------------------------------------------------------	
    
	//---------------------------------------------------------------------------------------------------------------------------------------
	function  uf_select_items($as_numcot,$as_codpro,$as_numanacot,$as_tipsolcot,&$aa_items,&$li_i)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_items
		//		   Access: public
		//		  return :	arreglo que contiene los items que participaron en un determinado analisis, de manera detallada en caso de que
		// 					los items se repitan
		//	  Description: Metodo que  devuelve los items que participaron en un determinado analisis, de manera detallada en caso de que
		// 					los items se repitan
		//	   Creado Por: Ing. Laura Cabré
		// 			  Fecha: 10/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$aa_items=array();
		$lb_valido=false;
		if($as_tipsolcot=="B")
		{				
			$ls_sql="SELECT d.codart as codigo, a.denart as denominacion, p.nompro, dts.canart as cantidad, dt.preuniart as precio,
			                dt.moniva,dt.montotart as monto,d.obsanacot, d.numcot, d.cod_pro, dts.numsep
					   FROM soc_dtac_bienes d,siv_articulo a, rpc_proveedor p,soc_dtcot_bienes dt, soc_dtsc_bienes dts , soc_cotizacion c					  
					  WHERE d.codemp='".$this->ls_codemp."' 
					    AND d.numanacot='".$as_numanacot."'
						AND dt.cod_pro='".$as_codpro."' 
						AND dt.numcot='".$as_numcot."'
						AND d.codemp=a.codemp 
						AND a.codemp=p.codemp 
						AND p.codemp=dt.codemp
						AND dt.codemp=dts.codemp
						AND dts.codemp=c.codemp											
						AND dt.cod_pro=dts.cod_pro
						AND dt.codart=dts.codart											
						AND dt.cod_pro=c.cod_pro
						AND dt.numcot=c.numcot								
						AND c.numsolcot=dts.numsolcot												 
						AND	d.codart=a.codart 
						AND d.cod_pro=p.cod_pro 
						AND d.numcot=dt.numcot 
						AND d.cod_pro=dt.cod_pro 
						AND d.codart=dt.codart";				
		}
		else
		{
				
				$ls_sql="SELECT d.codser as codigo, a.denser as denominacion, p.nompro, dts.canser as cantidad, dt.monuniser as precio, dt.moniva,dt.montotser as monto,
					        d.obsanacot, d.numcot, d.cod_pro, dts.numsep
					   FROM soc_dtac_servicios d,soc_servicios a, rpc_proveedor p,soc_dtcot_servicio dt, soc_dtsc_servicios dts , soc_cotizacion c					  
					  WHERE d.codemp='$this->ls_codemp' 
					    AND d.numanacot='$as_numanacot' 
						AND dt.cod_pro='$as_codpro' 
						AND dt.numcot='$as_numcot' 				    
						AND d.codemp=a.codemp 
						AND a.codemp=p.codemp 
						AND p.codemp=dt.codemp
						AND dt.codemp=dts.codemp
						AND dts.codemp=c.codemp											
						AND dt.cod_pro=dts.cod_pro
						AND dt.codser=dts.codser											
						AND dt.cod_pro=c.cod_pro
						AND dt.numcot=c.numcot								
						AND c.numsolcot=dts.numsolcot												 
						AND	d.codser=a.codser
						AND d.cod_pro=p.cod_pro 
						AND d.numcot=dt.numcot 
						AND d.cod_pro=dt.cod_pro 
						AND d.codser=dt.codser";	
				
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;	
		}
		else
		{
			$li_i=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_i++;
				$aa_items[$li_i]=$row;					
			}																
		}
		return $aa_items;
	}
	//--------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------
	function uf_calcular_montos($ai_totrow,$aa_items,&$aa_totales,$as_tipo_proveedor)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_calcular_montos
		//		   Access: public
		//		  return :	arreglo  montos totalizados
		//	  Description: Metodo que  devuelve arreglo  montos totalizados
		//	   Creado Por: Ing. Laura Cabré
		// 			  Fecha: 09/08/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$li_subtotal=0;
		 	$li_totaliva=0;
		 	$li_total=0;
		 	$aa_totales=array();
			for($li_j=1;$li_j<=$ai_totrow;$li_j++)
		 	{
				$li_subtotal+=(($aa_items[$li_j]["precio"]) * ($aa_items[$li_j]["cantidad"]));
			if($as_tipo_proveedor != "F") //En caso de que el roveedor sea formal no se le calculan los cargos
					$li_totaliva+=$aa_items[$li_j]["moniva"];	
			}
			$li_total=$li_totaliva+$li_subtotal;		 
			$aa_totales["subtotal"]=$li_subtotal;
			$aa_totales["totaliva"]=$li_totaliva;
			$aa_totales["total"]=$li_total;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------
	function uf_calculardetalles_montos($ai_totrow,&$aa_items,$as_tipsolcot,$as_tipo_proveedor,$as_cotizacion)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_calculardetalles_montos
		//		   Access: public
		//		  return :	arreglo  montos totalizados
		//	  Description: Metodo que  devuelve arreglo  montos totalizados
		//	   Creado Por: Ing. Laura Cabré
		// 			  Fecha: 09/08/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		for($li_j=1;$li_j<=$ai_totrow;$li_j++)
		{
			$ls_codart=$aa_items[$li_j]["codigo"];
			$ls_numsep=$aa_items[$li_j]["numsep"];
			$li_cantidad=$aa_items[$li_j]["cantidad"];
			$li_precio=$aa_items[$li_j]["precio"];
			$li_subtotart=($li_cantidad*$li_precio);
			$lb_valido=$this->uf_obtenercargos_items($ls_codart,$ls_numsep,$as_tipsolcot,$li_subtotart,$li_moncargo,$as_cotizacion);
			if($as_tipo_proveedor == "F") //En caso de que el roveedor sea formal no se le calculan los cargos
			{
				$li_moncargo=0;
			}
			$li_montotart=($li_subtotart+$li_moncargo);
			$aa_items[$li_j]["moniva"]=$li_moncargo;
			$aa_items[$li_j]["monto"]=$li_montotart;
			
		}
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------------------
	function  uf_obtenercargos_items($as_codart,$as_numsep,$as_tipsolcot,$ai_precio,&$ai_moncargo,$as_cotizacion)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtenercargos_items
		//		   Access: public
		//		  return :	arreglo que contiene los items que participaron en un determinado analisis, de manera detallada en caso de que
		// 					los items se repitan
		//	  Description: Metodo que  devuelve los items que participaron en un determinado analisis, de manera detallada en caso de que
		// 					los items se repitan
		//	   Creado Por: Ing. Laura Cabré
		// 			  Fecha: 10/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_moncargo=0;
		$lb_valido=true;
		if($as_tipsolcot=="B")
		{				
			$ls_sql="SELECT moniva".
					"   FROM soc_dtcot_bienes".
					"  WHERE soc_dtcot_bienes.codemp='".$this->ls_codemp."' ".
					"    AND soc_dtcot_bienes.numcot='".$as_cotizacion."'".
					"    AND soc_dtcot_bienes.codart='".$as_codart."' ";				
		}
		else
		{
			$ls_sql="SELECT moniva".
					"   FROM soc_dtcot_servicio".
					"  WHERE soc_dtcot_servicio.codemp='".$this->ls_codemp."' ".
					"    AND soc_dtcot_servicio.numcot='".$as_cotizacion."'".
					"    AND soc_dtcot_servicio.codser='".$as_codart."' ";				
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("Funtion-> uf_obtenercargos_items ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;	
		}
		else
		{
			$li_i=0;
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				//$li_i++;
				$ai_moncargo=$row["moniva"];
				//$li_moncar=$this->io_evaluate->uf_evaluar($ls_formula,$ai_precio,&$lb_valido);
				//$ai_moncargo += $li_moncar;
			}																
		}
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------------------
	function uf_load_datos_entrega($as_numanacot,$as_codpro,&$as_diaentcom,&$as_forpagcom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_datos_entrega
		//		   Access: public
		//		  return :	
		//	  Description: 
		//	   Creado Por: Ing. Laura Cabré
		// 			  Fecha: 10/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT diaentcom,forpagcom".
				"   FROM soc_cotizacion,soc_cotxanalisis".
				"  WHERE soc_cotxanalisis.codemp='".$this->ls_codemp."' ".
				"    AND soc_cotxanalisis.cod_pro='".$as_codpro."'".
				"    AND soc_cotxanalisis.numanacot='".$as_numanacot."' ".
				"    AND soc_cotizacion.codemp=soc_cotxanalisis.codemp".
				"    AND soc_cotizacion.numcot=soc_cotxanalisis.numcot";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("Funtion-> uf_load_datos_entrega ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;	
		}
		else
		{
			$li_i=0;
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_diaentcom=$row["diaentcom"];
				$as_forpagcom=strtoupper($row["forpagcom"]);
			}																
		}
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------------------
	function uf_load_formula($as_numsol,$as_codbieser,$as_estcondat)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_formula
		//		   Access: public
		//		  return :	
		//	  Description: 
		//	   Creado Por: Ing. Laura Cabré
		// 			  Fecha: 10/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_formula="";
		if($as_estcondat=="B")
		{
			$ls_sql="SELECT formula".
					"   FROM sep_dta_cargos".
					"  WHERE sep_dta_cargos.codemp='".$this->ls_codemp."' ".
					"    AND sep_dta_cargos.numsol='".$as_numsol."'".
					"    AND sep_dta_cargos.codart='".$as_codbieser."' ";
		}
		else
		{
			$ls_sql="SELECT formula".
					"   FROM sep_dts_cargos".
					"  WHERE sep_dts_cargos.codemp='".$this->ls_codemp."' ".
					"    AND sep_dts_cargos.numsol='".$as_numsol."'".
					"    AND sep_dts_cargos.codart='".$as_codbieser."' ";
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("Funtion-> uf_load_formula ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;	
		}
		else
		{
			$li_i=0;
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_formula=$row["formula"];
			}																
		}
		return $ls_formula;
	}
	//--------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_bienes($as_numordcom,$aa_seguridad,$as_codpro,$ls_numcot,$aa_items_cotizacion,$ai_totrow_cotizacion,$as_tipo_proveedor)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_bienes
		//		   Access: private
		//	    Arguments: as_numordcom  ---> número de la Orden de Compra
		//                 as_items  ---> listado de indices de items q van a ser guardados
		//				   as_numanacot--->numero de analisis de cotizacion
		//	      Returns: true si se insertaron los bienes correctamente o false en caso contrario
		//	  Description: este metodo inserta los bienes de una   orden de compra
		//	   Creado Por: Ing. Laura Cabre
		// Fecha Creación: 21/06/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;	

		for($li_i=1;($li_i<=$ai_totrow_cotizacion)&&($lb_valido);$li_i++)
		{
			$ls_numsep     = trim($aa_items_cotizacion[$li_i]["numsep"]);
			$ls_codart     = $aa_items_cotizacion[$li_i]["codigo"];
			$ls_denart     = $aa_items_cotizacion[$li_i]["denominacion"];
			$li_canart     = $aa_items_cotizacion[$li_i]["cantidad"];
			$ld_preuniart  = $aa_items_cotizacion[$li_i]["precio"];
			$ld_monsubart  = ($aa_items_cotizacion[$li_i]["precio"]) * ($aa_items_cotizacion[$li_i]["cantidad"]);
			if(trim($ls_numsep)!="")
			{
				$ls_formula  = $this->uf_load_formula($ls_numsep,$ls_codart,"B");
				$ld_monimp=$this->io_evaluate->uf_evaluar($ls_formula,$ld_monsubart,&$lb_valido);
				$ld_montotart=($ld_monsubart+$ld_monimp);
			}
			else
			{
				$ld_montotart  = $aa_items_cotizacion[$li_i]["monto"];
				$ld_monimp     = $aa_items_cotizacion[$li_i]["moniva"];
			}
			$ls_codunieje  = trim($aa_items_cotizacion[$li_i]["coduniadm"]);
			$ls_codestpro1 = trim($aa_items_cotizacion[$li_i]["codestpro1"]);
			$ls_codestpro2 = trim($aa_items_cotizacion[$li_i]["codestpro2"]);
			$ls_codestpro3 = trim($aa_items_cotizacion[$li_i]["codestpro3"]);
			$ls_codestpro4 = trim($aa_items_cotizacion[$li_i]["codestpro4"]);
			$ls_codestpro5 = trim($aa_items_cotizacion[$li_i]["codestpro5"]);
			$ls_estcla     = trim($aa_items_cotizacion[$li_i]["estcla"]);
			$la_data       = $this->uf_select_bienes_servicios($ls_codart,"B",$as_codpro,$ls_numcot);
			$ls_unidad     = $la_data["unidad"];	
						
			$ls_sql = "INSERT INTO soc_dt_bienes (codemp,numordcom,estcondat,codart,unidad,canart,penart,preuniart,monsubart,
			                                      montotart,orden,numsol,coduniadm,codestpro1,codestpro2,codestpro3,codestpro4,
												  codestpro5,estcla)".
                    "  VALUES ('".$this->ls_codemp."','".$as_numordcom."','B','".$ls_codart."','".$ls_unidad."',".$li_canart.",0, 
					           ".$ld_preuniart.",".$ld_monsubart.",".$ld_montotart.",".$li_i.",'".$ls_numsep."','".$ls_codunieje."',
							   '".$ls_codestpro1."','".$ls_codestpro2."','".$ls_codestpro3."','".$ls_codestpro4."',
							   '".$ls_codestpro5."','".$ls_estcla."')";
			$li_row = $this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
			    $this->io_mensajes->message("CLASE->sigesp_soc_c_generar_orden_analisis.php;MÉTODO->uf_insert_bienes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				echo $this->io_sql->message;
			}
			else
			{
				if($lb_valido)
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion ="Insertó el Articulo ".$ls_codart." a la Orden de Compra  ".$as_numordcom." Asociado a la empresa ".$this->ls_codemp;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			    }
				if($as_tipo_proveedor!="F")
			    	$lb_valido=$this->uf_insert_cargos($as_numordcom,"B",$aa_seguridad,$ls_codart,$ld_monsubart,$ld_monimp,$ld_montotart,$ls_numsep,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla);
			}
		}
		return $lb_valido;
	}// end function uf_insert_bienes
	//-----------------------------------------------------------------------------------------------------------------------------------	
	//--------------------------------------------------------------------------------------------------------------------
	function uf_insert_servicios($as_numordcom,$aa_seguridad,$as_codpro,$ls_numcot,$aa_items_cotizacion,$ai_totrow_cotizacion,$as_tipo_proveedor)
								
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_servicios
		//		   Access: private
		//	    Arguments: as_numordcom  ---> número de la Orden de Compra
		//                 as_items  ---> listado de indices de items q van a ser guardados
		//				   $as_numanacot--->numero de analisis de cotizacion
		//	      Returns: true si se insertaron los bienes correctamente o false en caso contrario
		//	  Description: este metodo inserta los bienes de una   orden de compra
		//	   Creado Por: Ing. Laura Cabre
		// Fecha Creación: 21/06/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		for($li_i=1;($li_i<=$ai_totrow_cotizacion)&&($lb_valido);$li_i++)
		{
			$ls_codser     = $aa_items_cotizacion[$li_i]["codigo"];
			$ls_denser     = $aa_items_cotizacion[$li_i]["denominacion"];
			$li_canser     = $aa_items_cotizacion[$li_i]["cantidad"];
			$ld_preuniser  = $aa_items_cotizacion[$li_i]["precio"];
			$ld_monsubser  = ($aa_items_cotizacion[$li_i]["precio"]) * ($aa_items_cotizacion[$li_i]["cantidad"]);
			$ls_numsep     = trim($aa_items_cotizacion[$li_i]["numsep"]);
			if(trim($ls_numsep)!="")
			{
				$ls_formula  = $this->uf_load_formula($ls_numsep,$ls_codser,"S");
				$ld_monimp=$this->io_evaluate->uf_evaluar($ls_formula,$ld_monsubser,&$lb_valido);
				$ld_montotser=($ld_monsubser+$ld_monimp);
			}
			else
			{
				$ld_montotser  = $aa_items_cotizacion[$li_i]["monto"];
				$ld_monimp     = $aa_items_cotizacion[$li_i]["moniva"];
			}

			$ls_codunieje  = trim($aa_items_cotizacion[$li_i]["coduniadm"]);
			$ls_codestpro1 = trim($aa_items_cotizacion[$li_i]["codestpro1"]);
			$ls_codestpro2 = trim($aa_items_cotizacion[$li_i]["codestpro2"]);
			$ls_codestpro3 = trim($aa_items_cotizacion[$li_i]["codestpro3"]);
			$ls_codestpro4 = trim($aa_items_cotizacion[$li_i]["codestpro4"]);
			$ls_codestpro5 = trim($aa_items_cotizacion[$li_i]["codestpro5"]);
			$ls_estcla     = trim($aa_items_cotizacion[$li_i]["estcla"]);
			
	        $ls_sql=" INSERT INTO soc_dt_servicio (codemp, numordcom, estcondat, codser, canser, monuniser, monsubser, montotser, 
			                                       orden, numsol,coduniadm, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, estcla)".
                    "  VALUES ('".$this->ls_codemp."','".$as_numordcom."','S','".$ls_codser."',".$li_canser.",".$ld_preuniser.",
					           ".$ld_monsubser.",".$ld_montotser.",'".$li_i."','".$ls_numsep."','".$ls_codunieje."','".$ls_codestpro1."','".$ls_codestpro2."',
							   '".$ls_codestpro3."','".$ls_codestpro4."','".$ls_codestpro5."','".$ls_estcla."')";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
			    $this->io_mensajes->message("CLASE->sigesp_soc_c_generar_orden_analisis.php;MÉTODO->uf_insert_servicios ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				echo $this->io_sql->message.'<br>';
			}
			else
			{
				if($lb_valido)
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion ="Insertó el servicio ".$ls_codser." a la Orden de Compra  ".$as_numordcom." Asociado a la empresa ".$this->ls_codemp;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////	
					if($as_tipo_proveedor!="F")
						$lb_valido=$this->uf_insert_cargos($as_numordcom,"S",$aa_seguridad,$ls_codser,$ld_monsubser,$ld_monimp,$ld_montotser,$ls_numsep,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla);	
			    }
			}
		}
		return $lb_valido;
	}// end function uf_insert_servicios
	//-----------------------------------------------------------------------------------------------------------------------------------	
	//--------------------------------------------------------------------------------------------------------------------
	function uf_insert_cuentas_presupuestarias($as_numordcom,$as_estcondat,$aa_items,$li_totrow,$aa_items_cotizacion,$ai_totrow_cotizacion,$aa_seguridad,$as_tipo_proveedor,$as_numcot,$ab_vienesep, $as_codpro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cuentas_presupuestarias
		//		   Access: private
		//	    Arguments: as_numordcom  ---> Número de la orden de compra 
		//                 as_estcondat  ---> estatus de la orden de compra  bienes o servicios
		//				   as_items  	---> items de la orden de compra
		//				   aa_seguridad  ---> arreglo de las variables de seguridad
		//				   aa_numcot------>numero de cotizacion
		//				   ab_vienesep--->booleano que indica si la solicitud viene de sep o no
		//				   as_codpro----> codigo del proveedor
		//				   aa_items_cotizacion--->items sumarizados en la cotizacion
		//				   ai_totrow_cotizacion--->cantidad de elementos en el arreglo anterior
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta las cuentas de una Solicitud de Ejecución Presupuestaria
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Modificado Por: Ing. Yozelin Barrgan, Ing. Laura Cabre
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 21/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_dscuentas->data=array();
		$ls_tipafeiva = $_SESSION["la_empresa"]["confiva"];
		for($li_i=1;($li_i<=$li_totrow)&&($lb_valido);$li_i++)
		{
			$ls_codart  = $aa_items[$li_i]["codigo"];
			$ls_numsep  = $aa_items[$li_i]["numsep"];			
			$la_cuentas = $this->uf_select_cuentas_presupuestarias($as_numcot,$ls_codart,$ls_numsep,$as_estcondat,$ab_vienesep,$as_codpro);
			for ($li_j=0;$li_j<count($la_cuentas);$li_j++)
			    {
				  $ls_estcla    = $la_cuentas[$li_j]["estcla"];
				  $ls_codestpro = $la_cuentas[$li_j]["programatica"];
				  $ls_spgcta    = $la_cuentas[$li_j]["spg_cuenta"];
				  $ld_moncue    = ($aa_items[$li_i]["precio"]) * ($aa_items[$li_i]["cantidad"]);
				  $this->io_dscuentas->insertRow("coditem",$ls_codart);
				  $this->io_dscuentas->insertRow("moncue",$ld_moncue);
				  $this->io_dscuentas->insertRow("cuenta",$ls_spgcta);
				  $this->io_dscuentas->insertRow("codestpro",$ls_codestpro);	
				  $this->io_dscuentas->insertRow("estcla",$ls_estcla);
			    }			
		}
		
		//Por cada item se guarda su respectiva cuenta de cargo
		if($as_tipo_proveedor != "F" && $ls_tipafeiva=='P')// En caso de que el proveedor sea tipo formal, no se le calculan los cargos
		{
			for($li_i=1;($li_i<=$li_totrow)&&($lb_valido);$li_i++)
			{
				$ls_codart = trim($aa_items[$li_i]["codigo"]);
				$ls_numsep = $aa_items[$li_i]["numsep"];
				if($ab_vienesep)
					$la_cargos=$this->uf_select_cargos_sep($ls_codart,$ls_numsep,$as_estcondat);
				else
					$la_cargos=$this->uf_select_cargos($ls_codart,$as_estcondat);
				   
				if(count($la_cargos)>0)
				{
					$ls_estcla    = $la_cargos["estcla"];
					$ls_codestpro = trim($la_cargos["codestpro"]);
					$ls_spgcta    = $la_cargos["spg_cuenta"];
					$ld_monto     = $aa_items[$li_i]["cantidad"] * $aa_items[$li_i]["precio"];
					$ls_formula   = str_replace('$LD_MONTO',$ld_monto,$la_cargos["formula"]);
					eval('$li_moncue ='.$ls_formula.";");					
					$this->io_dscuentas->insertRow("estcla",$ls_estcla);
					$this->io_dscuentas->insertRow("codestpro",$ls_codestpro);	
					$this->io_dscuentas->insertRow("cuenta",$ls_spgcta);			
					$this->io_dscuentas->insertRow("moncue",$li_moncue);	
					$this->io_dscuentas->insertRow("coditem",$ls_codart);		
				}
			}
		}
		if(count($this->io_dscuentas->data)>0)
		{
			$this->io_dscuentas->group_by(array('0'=>'codestpro','1'=>'cuenta','2'=>'estcla'),array('0'=>'moncue'),'moncue');
			$li_total=$this->io_dscuentas->getRowCount('codestpro');
			for ($li_fila=1;$li_fila<=$li_total;$li_fila++)
			    {
				  $ls_estcla     = $this->io_dscuentas->getValue('estcla',$li_fila);
				  $ls_codpro     = $this->io_dscuentas->getValue('codestpro',$li_fila);
				  $ls_cuenta     = $this->io_dscuentas->getValue('cuenta',$li_fila);
				  $li_moncue     = $this->io_dscuentas->getValue('moncue',$li_fila);
				  $ls_codestpro1 = substr($ls_codpro,0,25);
				  $ls_codestpro2 = substr($ls_codpro,25,25);
				  $ls_codestpro3 = substr($ls_codpro,50,25);
				  $ls_codestpro4 = substr($ls_codpro,75,25);
				  $ls_codestpro5 = substr($ls_codpro,100,25);
				
				$ls_sql="INSERT INTO soc_cuentagasto (codemp, numordcom, estcondat, codestpro1, codestpro2, codestpro3, codestpro4,  ".
						"							  codestpro5, estcla, spg_cuenta, monto)".
						"	  VALUES ('".$this->ls_codemp."','".$as_numordcom."','".$as_estcondat."','".$ls_codestpro1."','".$ls_codestpro2."',".
						" 			  '".$ls_codestpro3."','".$ls_codestpro4."','".$ls_codestpro5."','".$ls_estcla."','".$ls_cuenta."',".$li_moncue.")";        
				
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->sigesp_soc_c_generar_orden_analisis.php; MÉTODO->uf_insert_cargos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion ="Insertó la Cuenta ".$ls_cuenta." de programatica ".$ls_codpro." a la orden de compra ".$as_numordcom. " Asociado a la empresa ".$this->ls_codemp;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				}
			}
		}
		return $lb_valido;
	}// end function uf_insert_cuentas_presupuestarias
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_cuentas_presupuestarias($as_numcot,$as_coditem,$as_numsep,$ls_tipsolcot,$ab_vienesep,$as_codpro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_cuentas_presupuestarias
		//		   Access: public
		//	    Arguments: $as_coditem-->codigo del item
		//		return	 : arreglo con las cuentas de gasto asociadas a un item
		//	  Description: Metodo que  retorna  las cuentas de gasto asociadas a un item
		//	   Creado Por: Ing. Laura Cabré
		// 			  Fecha: 23/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_cuentas=array();
		$lb_valido=false;
		if($ab_vienesep)//Si viene de una sep
		{
			if($ls_tipsolcot=="B")
			{
				$ls_sql="SELECT sep_dt_articulos.spg_cuenta, 
				                sep_dt_articulos.codestpro1, 
								sep_dt_articulos.codestpro2, 
				                sep_dt_articulos.codestpro3, 
								sep_dt_articulos.codestpro4, 
								sep_dt_articulos.codestpro5,
								sep_dt_articulos.estcla
						   FROM sep_dt_articulos, soc_solcotsep, soc_cotizacion
						  WHERE sep_dt_articulos.codemp = '".$this->ls_codemp."' 
						    AND soc_cotizacion.numcot = '".$as_numcot."' 
							AND	soc_cotizacion.cod_pro = '".$as_codpro."' 
							AND sep_dt_articulos.codart = '".$as_coditem."' 
							AND	soc_solcotsep.numsol = '".$as_numsep."' 
							AND soc_cotizacion.codemp = soc_solcotsep.codemp 
							AND soc_cotizacion.numsolcot = soc_solcotsep.numsolcot 
							AND soc_solcotsep.codemp = sep_dt_articulos.codemp
							AND soc_solcotsep.numsol = sep_dt_articulos.numsol";
			}
			else
			{
				$ls_sql="SELECT sep_dt_servicio.spg_cuenta, 
				                sep_dt_servicio.codestpro1, 
								sep_dt_servicio.codestpro2, 
								sep_dt_servicio.codestpro3, 
								sep_dt_servicio.codestpro4, 
								sep_dt_servicio.codestpro5,
								sep_dt_servicio.estcla
						   FROM sep_dt_servicio, soc_solcotsep, soc_cotizacion
						  WHERE sep_dt_servicio.codemp = '".$this->ls_codemp."' 
						    AND soc_cotizacion.numcot = '".$as_numcot."' 
							AND	soc_cotizacion.cod_pro = '".$as_codpro."' 
							AND sep_dt_servicio.codser = '".$as_coditem."'
							AND soc_solcotsep.numsol = '".$as_numsep."' 
							AND soc_cotizacion.codemp = soc_solcotsep.codemp
							AND soc_cotizacion.numsolcot = soc_solcotsep.numsolcot 
							AND soc_solcotsep.codemp = sep_dt_servicio.codemp
							AND	soc_solcotsep.numsol = sep_dt_servicio.numsol";
			}
		}
		else//Si no viene de una sep
		{
			if($ls_tipsolcot=="B")
			{
				$ls_sql="SELECT siv_articulo.spg_cuenta, 
				                spg_dt_unidadadministrativa.codestpro1, 
								spg_dt_unidadadministrativa.codestpro2, 
								spg_dt_unidadadministrativa.codestpro3, 
								spg_dt_unidadadministrativa.codestpro4, 
								spg_dt_unidadadministrativa.codestpro5,
								spg_dt_unidadadministrativa.estcla
						   FROM siv_articulo, spg_unidadadministrativa, spg_dt_unidadadministrativa, soc_sol_cotizacion, soc_cotizacion, soc_dtsc_bienes
						  WHERE siv_articulo.codemp = '".$this->ls_codemp."' 
						    AND	siv_articulo.codart = '".$as_coditem."'
							AND soc_cotizacion.numcot = '".$as_numcot."' 
							AND soc_cotizacion.cod_pro= '".$as_codpro."' 
						    AND siv_articulo.codemp = soc_sol_cotizacion.codemp 
						    AND siv_articulo.codart = soc_dtsc_bienes.codart
						    AND soc_cotizacion.codemp = soc_sol_cotizacion.codemp 
						    AND soc_cotizacion.numsolcot = soc_sol_cotizacion.numsolcot 
						    AND soc_cotizacion.cod_pro=soc_dtsc_bienes.cod_pro
						    AND spg_unidadadministrativa.codemp = spg_dt_unidadadministrativa.codemp 
						    AND spg_unidadadministrativa.coduniadm = spg_dt_unidadadministrativa.coduniadm
						    AND soc_dtsc_bienes.codemp=soc_sol_cotizacion.codemp   
						    AND soc_dtsc_bienes.numsolcot=soc_sol_cotizacion.numsolcot
						    AND soc_dtsc_bienes.codemp=spg_dt_unidadadministrativa.codemp 
						    AND soc_dtsc_bienes.coduniadm=spg_dt_unidadadministrativa.coduniadm
						    AND soc_dtsc_bienes.codestpro1=spg_dt_unidadadministrativa.codestpro1
						    AND soc_dtsc_bienes.codestpro2=spg_dt_unidadadministrativa.codestpro2
						    AND soc_dtsc_bienes.codestpro3=spg_dt_unidadadministrativa.codestpro3
						    AND soc_dtsc_bienes.codestpro4=spg_dt_unidadadministrativa.codestpro4
						    AND soc_dtsc_bienes.codestpro5=spg_dt_unidadadministrativa.codestpro5
						    AND soc_dtsc_bienes.estcla=spg_dt_unidadadministrativa.estcla";
			}
			else
			{
				$ls_sql="SELECT soc_servicios.spg_cuenta, 
				                spg_dt_unidadadministrativa.codestpro1, 
								spg_dt_unidadadministrativa.codestpro2, 
								spg_dt_unidadadministrativa.codestpro3, 
								spg_dt_unidadadministrativa.codestpro4, 
								spg_dt_unidadadministrativa.codestpro5,
								spg_dt_unidadadministrativa.estcla
						   FROM soc_servicios, spg_unidadadministrativa, spg_dt_unidadadministrativa, soc_sol_cotizacion, soc_cotizacion, soc_dtsc_servicios
						  WHERE soc_servicios.codemp = '".$this->ls_codemp."' 
						    AND soc_servicios.codser = '".$as_coditem."' 
							AND	soc_cotizacion.numcot = '".$as_numcot."' 
							AND soc_cotizacion.cod_pro= '".$as_codpro."'
							AND soc_servicios.codemp = soc_sol_cotizacion.codemp 
						    AND soc_servicios.codser = soc_dtsc_servicios.codser
						    AND soc_cotizacion.codemp = soc_sol_cotizacion.codemp 
						    AND soc_cotizacion.numsolcot = soc_sol_cotizacion.numsolcot 
						    AND soc_cotizacion.cod_pro=soc_dtsc_servicios.cod_pro
						    AND spg_unidadadministrativa.codemp = spg_dt_unidadadministrativa.codemp 
						    AND spg_unidadadministrativa.coduniadm = spg_dt_unidadadministrativa.coduniadm
						    AND soc_dtsc_servicios.codemp=soc_sol_cotizacion.codemp   
						    AND soc_dtsc_servicios.numsolcot=soc_sol_cotizacion.numsolcot
						    AND soc_dtsc_servicios.codemp=spg_dt_unidadadministrativa.codemp 
						    AND soc_dtsc_servicios.coduniadm=spg_dt_unidadadministrativa.coduniadm
						    AND soc_dtsc_servicios.codestpro1=spg_dt_unidadadministrativa.codestpro1
						    AND soc_dtsc_servicios.codestpro2=spg_dt_unidadadministrativa.codestpro2
						    AND soc_dtsc_servicios.codestpro3=spg_dt_unidadadministrativa.codestpro3
						    AND soc_dtsc_servicios.codestpro4=spg_dt_unidadadministrativa.codestpro4
						    AND soc_dtsc_servicios.codestpro5=spg_dt_unidadadministrativa.codestpro5
						    AND soc_dtsc_servicios.estcla=spg_dt_unidadadministrativa.estcla";
			}
		}		

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("ERROR->uf_select_cuentas_presupuestarias".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			print $this->io_sql->message;
		}
		else
		{
			$li_i=0;
			while($row=$this->io_sql->fetch_row($rs_data))//
			{
				$ls_codestpro1 = str_pad(trim($row["codestpro1"]),25,0,0);
				$ls_codestpro2 = str_pad(trim($row["codestpro2"]),25,0,0);
				$ls_codestpro3 = str_pad(trim($row["codestpro3"]),25,0,0);
				$ls_codestpro4 = str_pad(trim($row["codestpro4"]),25,0,0);
				$ls_codestpro5 = str_pad(trim($row["codestpro5"]),25,0,0);
				
				$la_cuentas[$li_i]["estcla"]       = $row["estcla"];
				$la_cuentas[$li_i]["spg_cuenta"]   = trim($row["spg_cuenta"]);
				$la_cuentas[$li_i]["programatica"] = $ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
				$li_i++;
			}			
		}
		
		return $la_cuentas;	
	}//fin de uf_select_cuentas_presupuestarias
    //---------------------------------------------------------------------------------------------------------------------------------------	
    //---------------------------------------------------------------------------------------------------------------------------------------
    function uf_insert_cuentas_cargos($as_numordcom,$as_estcondat,$aa_items,$li_totrow,$aa_seguridad,$ab_vienesep)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cuentas_cargos
		//		   Access: private
		//	    Arguments: as_numordcom  ---> numero de la orden de compra
		//                 as_estcondat  ---> estatus de la orden de compra  bienes o servicios
		//				   ai_totrowcuentascargo  ---> filas del grid cuentas cargos
		//				   ai_totrowcargos  ---> filas del grid de los creditos
		//				   aa_seguridad  ---> variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: este metodo inserta la cuentas de los cargos asociadas a una orden de compra
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Modificado Por: Ing. Yozelin Barrgan, Ing Laura Cabre
		// Fecha Creación: 24/06/2007 								Fecha Última Modificación : 01/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_dscargos->data=array();	
		$ls_tipafeiva = $_SESSION["la_empresa"]["confiva"];
		for($li_i=1;($li_i<=$li_totrow)&&($lb_valido);$li_i++)
		{
			$ls_codart=$aa_items[$li_i]["codigo"];
			$ls_numsep=$aa_items[$li_i]["numsep"];
			if($ab_vienesep)
				$la_cargos=$this->uf_select_cargos_sep($ls_codart,$ls_numsep,$as_estcondat);
			else
				$la_cargos=$this->uf_select_cargos($ls_codart,$as_estcondat);			
			if(count($la_cargos))
			{
				$ls_codcar  = $la_cargos["codcar"];
				$ld_bascar  = ($aa_items[$li_i]["precio"]) * ($aa_items[$li_i]["cantidad"]);
				$ld_monto   = $aa_items[$li_i]["cantidad"] * $aa_items[$li_i]["precio"];
				$ls_formula = str_replace('$LD_MONTO',$ld_monto,$la_cargos["formula"]);
				eval('$ld_moncar ='.$ls_formula.";");	
				$ls_formulacargo = $la_cargos["formula"];		
				$ls_codpro       = $la_cargos["codestpro"];
				$ls_estcla       = $la_cargos["estcla"];
				$ls_spg_cuenta   = $la_cargos["spg_cuenta"];
				
				$this->io_dscargos->insertRow("codcar",$ls_codcar);	
				$this->io_dscargos->insertRow("monobjret",$ld_bascar);	
				$this->io_dscargos->insertRow("monret",$ld_moncar);	
				$this->io_dscargos->insertRow("formula",$ls_formulacargo);
				$this->io_dscargos->insertRow("codestpro",$ls_codpro);
				$this->io_dscargos->insertRow("estcla",$ls_estcla);	
				$this->io_dscargos->insertRow("spg_cuenta",$ls_spg_cuenta);
			}
		}
		$this->io_dscargos->group_by(array('0'=>'codestpro','1'=>'spg_cuenta','2'=>'estcla','3'=>'codcar'),array('0'=>'monobjret','1'=>'monret'),'monobjret');
		$li_totrow=$this->io_dscargos->getRowCount("codcar");
		if ($ls_tipafeiva=='P')
		   {
				for($li_i=1;($li_i<=$li_totrow)&&($lb_valido);$li_i++)
				{
					$ls_codcargo   = $this->io_dscargos->getValue("codcar",$li_i);
					$ls_codpro     = $this->io_dscargos->getValue("codestpro",$li_i);
					$ls_estcla     = $this->io_dscargos->getValue("estcla",$li_i);
					$ls_spg_cuenta = trim($this->io_dscargos->getValue("spg_cuenta",$li_i));
					$ld_monobjret  = $this->io_dscargos->getValue("monobjret",$li_i);
					$ld_monret     = $this->io_dscargos->getValue("monret",$li_i);
					$ls_formula    = $this->io_dscargos->getValue("formula",$li_i);		
					$ls_codestpro1 = substr($ls_codpro,0,25);
					$ls_codestpro2 = substr($ls_codpro,25,25);
					$ls_codestpro3 = substr($ls_codpro,50,25);
					$ls_codestpro4 = substr($ls_codpro,75,25);
					$ls_codestpro5 = substr($ls_codpro,100,25);
					$ls_sc_cuenta  = "";
					$lb_valido=$this->uf_select_cuentacontable($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_spg_cuenta,$ls_estcla,&$ls_sc_cuenta);
															   
					if($lb_valido)
					{
						$ls_sql="INSERT INTO soc_solicitudcargos (codemp, numordcom,  estcondat, codcar, monobjret, monret, codestpro1, ".
								"                                 codestpro2, codestpro3, codestpro4, codestpro5, estcla, spg_cuenta, sc_cuenta, ".
								"								  formula, monto) ".
								"	  VALUES ('".$this->ls_codemp."','".$as_numordcom."','".$as_estcondat."','".$ls_codcargo."',".$ld_monobjret.", ".
								"			  ".$ld_monret.",'".$ls_codestpro1."','".$ls_codestpro2."','".$ls_codestpro3."', ".
								" 			  '".$ls_codestpro4."','".$ls_codestpro5."','".$ls_estcla."','".$ls_spg_cuenta."','".$ls_sc_cuenta."','".$ls_formula."', ".
								"			   ".$ld_monret.")";
						$li_row=$this->io_sql->execute($ls_sql);
						if($li_row===false)
						{
							$lb_valido=false;
							$this->io_mensajes->message("CLASE->sigesp_soc_c_generar_orden_analisis.php; MÉTODO->uf_insert_cuentas_cargos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
						}
						else
						{
						    $lb_valido=true;
							/////////////////////////////////         SEGURIDAD               /////////////////////////////		
							$ls_evento="INSERT";
							$ls_descripcion ="Insertó la Cuenta ".$ls_spg_cuenta." de programatica ".$ls_codpro."Tipo = ".$ls_estcla." al cargo ".$ls_codcargo." de la orden de compra  ".$as_numordcom. " Asociado a la empresa ".$this->ls_codemp;
							$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
															$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
															$aa_seguridad["ventanas"],$ls_descripcion);
							/////////////////////////////////         SEGURIDAD               /////////////////////////////		
						}
					}
					else
					{
						$this->io_mensajes->message("ERROR-> La cuenta Presupuestaria ".$ls_spg_cuenta." No tiene cuenta contable asociada."); 
					}
				}
			}
			elseif($ls_tipafeiva=='C')
			{
			  for ($li_i=1;($li_i<=$li_totrow)&&($lb_valido);$li_i++)
			     {
				   $ls_codcargo   = $this->io_dscargos->getValue("codcar",$li_i);
				   $ls_codctascg  = $this->io_dscargos->getValue("spg_cuenta",$li_i);
				   $ld_monobjret  = $this->io_dscargos->getValue("monobjret",$li_i);
				   $ld_monret	  = $this->io_dscargos->getValue("monret",$li_i);
				   $ls_formula	  = $this->io_dscargos->getValue("formula",$li_i);
				 /*  $ls_codestpro1 = $this->io_dscargos->getValue("codestpro1",$li_i);
				   $ls_codestpro2 = $this->io_dscargos->getValue("codestpro2",$li_i);
				   $ls_codestpro3 = $this->io_dscargos->getValue("codestpro3",$li_i);
				   $ls_codestpro4 = $this->io_dscargos->getValue("codestpro4",$li_i);
				   $ls_codestpro5 = $this->io_dscargos->getValue("codestpro5",$li_i);
				   $ls_estcla = $this->io_dscargos->getValue("estcla",$li_i);*/
				   $ls_codestpro1 = '--------------------';
				   $ls_codestpro2 = '------';
				   $ls_codestpro3 = '---';
				   $ls_codestpro4 = '--';
				   $ls_codestpro5 = '--';
				   $ls_estcla ='-';
				   
		           $ls_sql        = "INSERT INTO soc_solicitudcargos (codemp,numordcom,estcondat,codcar,monobjret,monret,
				                                                      codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,
																	  estcla,spg_cuenta, sc_cuenta,formula, monto)
					    			 VALUES ('".$this->ls_codemp."','".$as_numordcom."','".$as_estcondat."','".$ls_codcargo."',
							    			 ".$ld_monobjret.",".$ld_monret.",'".$ls_codestpro1."','".$ls_codestpro2."',
							    			 '".$ls_codestpro3."','".$ls_codestpro4."','".$ls_codestpro5."','".$ls_estcla."',
							    			 '".$ls_codctascg."','".$ls_codctascg."','".$ls_formula."',".$ld_monret.")";
                   
				   $rs_data = $this->io_sql->execute($ls_sql);
				   if ($rs_data===false)
				      {
					    $lb_valido = false;
						$this->io_mensajes->message("CLASE->sigesp_soc_c_generar_orden_analisis.php(Iva Contable);MÉTODO->uf_insert_cuentas_cargos (Iva Contable);ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					  } 
				   else
				      {
					     $lb_valido = true;
						 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
						 $ls_evento="INSERT";
						 $ls_descripcion ="Insertó la Cuenta Contable ".$ls_codctascg." al cargo ".$ls_codcargo." de la orden de compra  ".$as_numordcom. " de tipo ".$as_estcondat." Asociado a la empresa ".$this->ls_codemp;
						 $lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
						 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
					  }
				 }
			}// fin del if de $ls_tipafeiva

		return $lb_valido;

	}// end function uf_insert_cuentas_cargos
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	function uf_validar_cuentas($as_numordcom,&$as_estcom,$as_estcondat)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_cuentas
		//		   Access: private
		//		 Argument: as_numordcom ---> mumero de la orden de compra
		//				   as_estcom  ---> estatus de la orden de compra
		//                 as_estcondat ---> tipo de la orden de compra bienes o servicios
		//	  Description: Función que busca que las cuentas presupuestarias estén en la programática seleccionada
		//				   de ser asi coloca la sep en emitida sino la coloca en registrada
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 12/05/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, estcla, TRIM(spg_cuenta) AS spg_cuenta, monto, ".
				"	    (SELECT (asignado-(comprometido+precomprometido)+aumento-disminucion) ".
				"		   FROM spg_cuentas ".
				"		  WHERE spg_cuentas.codemp = soc_cuentagasto.codemp ".
				"			AND spg_cuentas.codestpro1 = soc_cuentagasto.codestpro1 ".
				"		    AND spg_cuentas.codestpro2 = soc_cuentagasto.codestpro2 ".
				"		    AND spg_cuentas.codestpro3 = soc_cuentagasto.codestpro3 ".
				"		    AND spg_cuentas.codestpro4 = soc_cuentagasto.codestpro4 ".
				"		    AND spg_cuentas.codestpro5 = soc_cuentagasto.codestpro5 ".
				"		    AND spg_cuentas.estcla = soc_cuentagasto.estcla ".
				"			AND spg_cuentas.spg_cuenta = soc_cuentagasto.spg_cuenta) AS disponibilidad, ".		
				"		(SELECT COUNT(codemp) ".
				"		   FROM spg_cuentas ".
				"		  WHERE spg_cuentas.codemp = soc_cuentagasto.codemp ".
				"			AND spg_cuentas.codestpro1 = soc_cuentagasto.codestpro1 ".
				"		    AND spg_cuentas.codestpro2 = soc_cuentagasto.codestpro2 ".
				"		    AND spg_cuentas.codestpro3 = soc_cuentagasto.codestpro3 ".
				"		    AND spg_cuentas.codestpro4 = soc_cuentagasto.codestpro4 ".
				"		    AND spg_cuentas.codestpro5 = soc_cuentagasto.codestpro5 ".
				"		    AND spg_cuentas.estcla = soc_cuentagasto.estcla ".
				"			AND spg_cuentas.spg_cuenta = soc_cuentagasto.spg_cuenta) AS existe ".		
				"  FROM soc_cuentagasto  ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numordcom='".$as_numordcom."' ".
				"   AND estcondat='".$as_estcondat."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->sigesp_soc_c_generar_orden_analisis.php; MÉTODO->uf_validar_cuentas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			$lb_existe=true;
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_existe))
			{
				$ls_estcla     = trim($row["estcla"]);
				$ls_codestpro1 = trim($row["codestpro1"]);
				$ls_codestpro2 = trim($row["codestpro2"]);
				$ls_codestpro3 = trim($row["codestpro3"]);
				$ls_codestpro4 = trim($row["codestpro4"]);
				$ls_codestpro5 = trim($row["codestpro5"]);
				$ls_spg_cuenta = trim($row["spg_cuenta"]);
				$li_monto      = $row["monto"];
				$li_disponibilidad=$row["disponibilidad"];
				$li_existe=$row["existe"];
				if($li_existe>0)
				{
					if($li_monto>$li_disponibilidad)
					{
						$li_monto=number_format($li_monto,2,",",".");
						$li_disponibilidad=number_format($li_disponibilidad,2,",",".");
						$this->io_mensajes->message("No hay Disponibilidad en la cuenta ".$ls_spg_cuenta." Disponible=[".$li_disponibilidad."] Cuenta=[".$li_monto."]"); 
					}
				}
				else
				{
					$lb_existe = false;
					$this->io_mensajes->message("La cuenta ".$ls_spg_cuenta." No Existe en la Estructura ".$ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3.'-'.$ls_codestpro4.'-'.$ls_codestpro5."; Tipo = ".$ls_estcla); 
				}
				
			}
			$this->io_sql->free_result($rs_data);	
			if($lb_existe)
			{
				$as_estcom=1; // EMITIDA SE DEBE CAMBIAR EN LETRAS (E)
			}
			else
			{
				$as_estcom=0; // REGISTRO SE DEBE CAMBIAR EN LETRAS (R)
			}
			$ls_sql="UPDATE soc_ordencompra ".
					"   SET estcom='".$as_estcom."' ".
					" WHERE codemp = '".$this->ls_codemp."' AND ".
					"	    numordcom = '".$as_numordcom."' AND ".
					"       estcondat= '".$as_estcondat."'  ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->sigesp_soc_c_generar_orden_analisis.php; MÉTODO->uf_validar_cuentas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}			
		}
		return $lb_valido;
	}// end function uf_validar_cuentas
	//-----------------------------------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_cargos($as_coditem,$ls_tipsolcot)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_cargos
		//		   Access: public
		//	    Arguments: $as_coditem-->codigo del item
		//		return	 : arreglo con los cargos asociados al item
		//	  Description: Metodo que  retorna los cargos asociados al item
		//	   Creado Por: Ing. Laura Cabré
		// 			  Fecha: 22/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_cargos=array();
		$lb_valido=false;
		if($ls_tipsolcot=="B")
		{				
			$ls_sql= "SELECT s.codart, s.codcar, c.formula,c.codestpro,c.estcla, c.spg_cuenta 
					    FROM siv_cargosarticulo s, sigesp_cargos c
					   WHERE s.codemp='$this->ls_codemp' 
					     AND s.codart='$as_coditem' 
						 AND s.codemp=c.codemp
					     AND s.codcar=c.codcar";
		}
		else
		{
			$ls_sql= "SELECT s.codser, s.codcar, c.formula ,c.codestpro, c.estcla, c.spg_cuenta 
					    FROM soc_serviciocargo s, sigesp_cargos c
					   WHERE s.codemp='$this->ls_codemp' 
					     AND s.codser='$as_coditem' 
						 AND s.codemp=c.codemp
					     AND s.codcar=c.codcar";			
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("ERROR->uf_select_cargos".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))//
			{
				$la_cargos=$row;
				unset($row);
				$this->io_sql->free_result($rs_data);				
			}			
		}
		return $la_cargos;	
	}//fin de uf_select_cargos
	//-----------------------------------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_cargos_sep($as_coditem,$as_numsep,$ls_tipsolcot)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_cargos_sep
		//		   Access: public
		//	    Arguments: $as_coditem-->codigo del item
		//				   $as_numsep--->numero de la sep a la cual esta asociada el item
		//				   $ls_tipsolcot--->Si es de bienes o de servicio
		//		return	 : arreglo con los cargos asociados al item, si la solicitud esta asociada a una sep
		//	  Description: Metodo que  retorna los cargos asociados al item, si la solicitud esta asociada a una sep
		//	   Creado Por: Ing. Laura Cabré
		// 			  Fecha: 22/06/2007								Fecha Última Modificación : 13/11/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_cargos=array();
		$lb_valido=false;
		if($ls_tipsolcot=="B")
		{				
			$ls_sql="SELECT dta.formula, dta.codcar, 
			                sc.codestpro1,sc.codestpro2,sc.codestpro3,sc.codestpro4,sc.codestpro5,sc.estcla,sc.spg_cuenta
					   FROM sep_dta_cargos dta, sep_solicitudcargos sc
					  WHERE dta.codemp = '$this->ls_codemp' AND
						    dta.codart = '".trim($as_coditem)."' AND
						    dta.numsol = '".trim($as_numsep)."' AND
						    dta.codemp = sc.codemp AND
						    dta.numsol = sc.numsol AND
						    dta.codcar = sc.codcar";	
		}
		else
		{
			$ls_sql= "SELECT dta.formula, dta.codcar, sc.codestpro1,sc.codestpro2,sc.codestpro3,sc.codestpro4,sc.codestpro5,sc.estcla,sc.spg_cuenta
					    FROM sep_dts_cargos dta, sep_solicitudcargos sc
					   WHERE dta.codemp = '$this->ls_codemp' 
					     AND dta.codser = '".trim($as_coditem)."' 
					     AND dta.numsol = '".trim($as_numsep)."' 
					     AND dta.codemp = sc.codemp 
						 AND dta.numsol = sc.numsol 
						 AND dta.codcar = sc.codcar";			
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("ERROR->uf_select_cargos_sep".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			print $this->io_sql->message;
		}
		else
		{
		  if ($row=$this->io_sql->fetch_row($rs_data))//
			 {
			   $ls_codestpro1 = str_pad(trim($row["codestpro1"]),25,0,0);
			   $ls_codestpro2 = str_pad(trim($row["codestpro2"]),25,0,0);
			   $ls_codestpro3 = str_pad(trim($row["codestpro3"]),25,0,0);
			   $ls_codestpro4 = str_pad(trim($row["codestpro4"]),25,0,0);
			   $ls_codestpro5 = str_pad(trim($row["codestpro5"]),25,0,0);
				
			   $la_cargos["codestpro"]  = $ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;;				
			   $la_cargos["formula"]    = $row["formula"];
			   $la_cargos["spg_cuenta"] = trim($row["spg_cuenta"]);
			   $la_cargos["codcar"]     = $row["codcar"];
			   $la_cargos["estcla"]     = $row["estcla"];
			   unset($row);
			 }			
		   $this->io_sql->free_result($rs_data);
		}
		return $la_cargos;	
	}//fin de uf_select_cargos_sep
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_cuentacontable($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_spgcuenta,$as_estcla,&$as_sccuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_cuentacontable
		//		   Access: private
		//	    Arguments: as_codestpro1  --->  Còdigo de Estructura Programàtica
		//	    		   as_codestpro2  --->  Còdigo de Estructura Programàtica
		//	    		   as_codestpro3  --->  Còdigo de Estructura Programàtica
		//	    		   as_codestpro4  --->  Còdigo de Estructura Programàtica
		//	    		   as_codestpro5  --->  Còdigo de Estructura Programàtica
		//	    		   as_spgcuenta   --->  Cuentas Presupuestarias
		// 	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que obtiene la cuenta contable 
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$as_sccuenta="";
		$ls_sql="SELECT sc_cuenta ".
				"  FROM spg_cuentas ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codestpro1='".trim($as_codestpro1)."' ".
				"   AND codestpro2='".trim($as_codestpro2)."' ".
				"   AND codestpro3='".trim($as_codestpro3)."' ".
				"   AND codestpro4='".trim($as_codestpro4)."' ".
				"   AND codestpro5='".trim($as_codestpro5)."' ".
				"   AND spg_cuenta='".trim($as_spgcuenta)."' ".
				"   AND estcla='".$as_estcla."'"; //print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->sigesp_soc_c_generar_orden_analisis.php; MÉTODO->uf_select_cuentacontable ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_sccuenta=$row["sc_cuenta"];
				unset($row);
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_select_cuentacontable
//---------------------------------------------------------------------------------------------------------------------------------------	
//---------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_bienes_servicios($as_coditem,$as_tipo,$as_codpro,$as_numcot)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_bienes_servicios
		//		   Access: public
		//		  return :	arreglo que contiene algunos datos basicos que faltan de los bienes/servicios
		//	  Description: Metodo que  devuelve algunos datos basicos que faltan de los bienes/servicios
		//	   Creado Por: Ing. Laura Cabré
		// 			  Fecha: 21/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_datos=array();
		$lb_valido=false;
		if($as_tipo=="B")
		{
			$ls_sql= "SELECT a.spg_cuenta, d.unidad 
					    FROM siv_articulo a, soc_dtcot_bienes d
					   WHERE a.codemp='$this->ls_codemp' 
					     AND a.codemp=d.codemp
						 AND a.codart='$as_coditem' 
						 AND d.cod_pro='$as_codpro' 
						 AND d.numcot='$as_numcot' 
						 AND a.codart=d.codart";				
		}
		else
		{
			$ls_sql= "SELECT a.spg_cuenta
						FROM soc_servicios a, soc_dtcot_servicio d
					   WHERE a.codemp='$this->ls_codemp' 
					     AND a.codemp=d.codemp
						 AND a.codser='$as_coditem' 
						 AND d.cod_pro='$as_codpro' 
						 AND d.numcot='$as_numcot' 
						 AND a.codser=d.codser";	
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("ERROR->uf_select_bienes_servicios".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;	
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$aa_datos["spg_cuenta"]=$row["spg_cuenta"];	
				
				if(array_key_exists("unidad",$row))
					$aa_datos["unidad"]=$row["unidad"];					
			}																
		}
		return $aa_datos;
	}//fin de uf_select_cotizacion_analisis
//---------------------------------------------------------------------------------------------------------------------------------------		
//---------------------------------------------------------------------------------------------------------------------------------------
	function  uf_select_items_cotizacion($as_numcot,$as_codpro,$as_numanacot,$as_tipsolcot,&$aa_items,&$li_i)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_items
		//		   Access: public
		//		  return :	arreglo que contiene los items que participaron en un determinado analisis, de manera combinada en caso de que
		//					los items se repitan 
		//	  Description: Metodo que  devuelve los items que participaron en un determinado analisis, de manera combinada en caso de que
		//					los items se repitan 
		//	   Creado Por: Ing. Laura Cabré
		// 			  Fecha: 10/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$aa_items=array();
		$lb_valido=false;
		if($as_tipsolcot=="B")
		{				
			$ls_sql="SELECT DISTINCT d.codart as codigo, a.denart as denominacion, p.nompro, soc_dtsc_bienes.canart as cantidad, 
							dt.preuniart as precio, dt.moniva,dt.montotart as monto, d.obsanacot, d.numcot, d.cod_pro,
							soc_dtsc_bienes.numsep,soc_dtsc_bienes.coduniadm,soc_dtsc_bienes.codestpro1,soc_dtsc_bienes.codestpro2,
						    soc_dtsc_bienes.codestpro3,soc_dtsc_bienes.codestpro4,soc_dtsc_bienes.codestpro5,
							soc_dtsc_bienes.estcla
				       FROM soc_dtac_bienes d,siv_articulo a, rpc_proveedor p,soc_dtcot_bienes dt, soc_sol_cotizacion,
					        soc_dtsc_bienes, soc_cotizacion
					  WHERE d.codemp='".$this->ls_codemp."' 
					    AND d.numanacot='".$as_numanacot."' 
						AND dt.cod_pro='".$as_codpro."' 
						AND dt.numcot='".$as_numcot."' 
					    AND soc_cotizacion.codemp=soc_sol_cotizacion.codemp    
					    AND soc_cotizacion.numsolcot=soc_sol_cotizacion.numsolcot 
					    AND soc_sol_cotizacion.codemp=soc_dtsc_bienes.codemp
					    AND soc_sol_cotizacion.numsolcot=soc_dtsc_bienes.numsolcot
					    AND soc_dtsc_bienes.codemp=dt.codemp
					    AND soc_dtsc_bienes.codart=dt.codart
					    AND soc_dtsc_bienes.codemp=d.codemp
					    AND soc_dtsc_bienes.codart=d.codart  
					    AND d.codemp=soc_cotizacion.codemp
					    AND d.numcot=soc_cotizacion.numcot
					    AND d.codemp=a.codemp 
					    AND a.codemp=p.codemp 
					    AND p.codemp=dt.codemp 
					    AND d.codart=a.codart 
					    AND d.cod_pro=p.cod_pro 
					    AND d.numcot=dt.numcot 
					    AND d.cod_pro=dt.cod_pro 
					    AND d.codart=dt.codart";
		}
		else
		{
				$ls_sql="SELECT DISTINCT d.codser as codigo, a.denser as denominacion, p.nompro, soc_dtsc_servicios.canser as cantidad, dt.monuniser as precio, dt.moniva,dt.montotser as monto,
					            d.obsanacot, d.numcot, d.cod_pro,soc_dtsc_servicios.numsep,soc_dtsc_servicios.coduniadm,
								soc_dtsc_servicios.codestpro1,soc_dtsc_servicios.codestpro2,soc_dtsc_servicios.codestpro3,
								soc_dtsc_servicios.codestpro4,soc_dtsc_servicios.codestpro5,soc_dtsc_servicios.estcla,sep_dts_cargos.formula
					       FROM soc_dtac_servicios d,soc_servicios a, rpc_proveedor p,soc_dtcot_servicio dt, soc_sol_cotizacion,
						   		soc_dtsc_servicios, soc_cotizacion
				 	      WHERE d.codemp='".$this->ls_codemp."' 
						    AND d.numanacot='".$as_numanacot."'
							AND dt.cod_pro='".$as_codpro."'
							AND dt.numcot='".$as_numcot."' 
							AND soc_cotizacion.codemp=soc_sol_cotizacion.codemp    
							AND soc_cotizacion.numsolcot=soc_sol_cotizacion.numsolcot 
							AND soc_sol_cotizacion.codemp=soc_dtsc_servicios.codemp
							AND soc_sol_cotizacion.numsolcot=soc_dtsc_servicios.numsolcot
							AND soc_dtsc_servicios.codemp=dt.codemp
							AND soc_dtsc_servicios.codser=dt.codser
							AND soc_dtsc_servicios.codemp=d.codemp
							AND soc_dtsc_servicios.codser=d.codser  
							AND d.codemp=soc_cotizacion.codemp
							AND d.numcot=soc_cotizacion.numcot
							AND d.codemp=a.codemp 
							AND a.codemp=p.codemp 
							AND p.codemp=dt.codemp 
							AND d.codser=a.codser 
							AND d.cod_pro=p.cod_pro 
							AND d.numcot=dt.numcot 
							AND d.cod_pro=dt.cod_pro 
							AND d.codser=dt.codser";
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			echo $this->io_sql->message.'<br>';	
			$lb_valido=false;
			$this->io_mensajes->message("CLASS->sigesp_soc_c_generar_orden_analisis.php->Metodo->uf_select_items_cotizacion.ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_i=0;
			while($row=$this->io_sql->fetch_row($rs_data))//Se verifica si la solicitud es de bienes o de servicios
			{
				$li_i++;
				$aa_items[$li_i]=$row;					
			}																
		    unset($row);
			$this->io_sql->free_result($rs_data); 
		}
		return $aa_items;
	}
	
	//--------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------
	function uf_insert_cargos($as_numordcom,$as_estcondat,$aa_seguridad,$as_coditem,$ad_monbasimp,$as_monimp,$as_monto,$as_numsep,
							  $as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_estcla)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cargos
		//		   Access: private
		//	    Arguments: as_numordcom  ---> número de la orden de compra		
		//                 as_estcondat  ---> estatus de la orden de compra  bienes o servicios
		//				   aa_seguridad  ---> arreglo con los parametros de seguridad
		//	      Returns: true si se insertaron los cargos correctamente o false en caso contrario
		//	  Description: Funcion que inserta los cargos de una Orden de Compra en la tabla segun el tipo de la orden 
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Modificado Por. Yozelin Barragan 
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 12/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_cargos=$this->uf_select_cargos($as_coditem,$as_estcondat);
		$lb_valido=true;
		if(count($la_cargos)>0)
			{
			switch($as_estcondat)
			{
				case "B": // si es de Bienes
					$ls_tabla="soc_dta_cargos";
					$ls_campo="codart";
				break;
				
				case "S": // si es de Servicios
					$ls_tabla="soc_dts_cargos";
					$ls_campo="codser";
				break;
			}	
			$ls_codcar=$la_cargos["codcar"];
			$ls_formulacargo=$la_cargos["formula"];	
	
			$ls_sql="INSERT INTO ".$ls_tabla." (codemp, numordcom, estcondat, ".$ls_campo.", codcar, numsol,monbasimp, monimp,".
					" 						    monto, formula, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, estcla)".
					"	  VALUES ('".$this->ls_codemp."','".$as_numordcom."','".$as_estcondat."','".$as_coditem."','".$ls_codcar."','".$as_numsep."',".
					" 			  ".$ad_monbasimp.",".$as_monimp.",".$as_monto.",'".$ls_formulacargo."','".$as_codestpro1."','".$as_codestpro2."',".
					"			  '".$as_codestpro3."','".$as_codestpro4."','".$as_codestpro5."','".$as_estcla."')";        
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				//print $this->io_sql->message;
				$this->io_mensajes->message("CLASE->sigesp_soc_c_generar_orden_analisis.php;MÉTODO->uf_insert_cargos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Cargo ".$ls_codcar." a la Orden de Compra ".$as_numordcom. "Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
		}
	
		return $lb_valido;
	}// end function uf_insert_cargos
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------
	function uf_select_solicitud($as_numanacot,&$as_concepto,&$as_unidad,&$as_uniejeaso,&$as_tipbiesolcot)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_solicitud
		//		   Access: public
		//		  return : variable con el concepto de la solicitud de cotizacion y la unidad ejecutora
		//	  Description: Metodo que  devuelve el concepto de la solicitud de cotizacion y la unidad ejecutora
		//	   Creado Por: Ing. Laura Cabré
		// 			  Fecha: 31/10/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$as_concepto = array();
		$lb_valido   = true;
		$ls_sql = "SELECT soc_sol_cotizacion.uniejeaso,soc_sol_cotizacion.consolcot,
		                  soc_sol_cotizacion.coduniadm, soc_sol_cotizacion.tipsolbie
					 FROM soc_sol_cotizacion , soc_analisicotizacion
					WHERE soc_sol_cotizacion.codemp = '".$this->ls_codemp."'
					  AND soc_analisicotizacion.numanacot = '".$as_numanacot."'
					  AND soc_analisicotizacion.codemp = soc_sol_cotizacion.codemp
					  AND soc_analisicotizacion.numsolcot = soc_sol_cotizacion.numsolcot";
	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("ERROR->uf_select_solicitud".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
		  if ($row=$this->io_sql->fetch_row($rs_data))
			 {
			   $as_concepto     = $row["consolcot"];
			   $as_uniejeaso    = $row["uniejeaso"];	
			   $as_unidad       = $row["coduniadm"];
			   $as_tipbiesolcot = $row["tipsolbie"];
			}																
		}		
		return $lb_valido;
	}//fin de uf_select_solicitud
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	function uf_select_unidades_ejecutoras($as_numanacot, $ab_viene_sep,$aa_items, $ai_totrow,&$aa_unidades,&$as_concepto,&$as_unidad,
	                                       &$as_codestpro1,&$as_codestpro2,&$as_codestpro3,&$as_codestpro4,&$as_codestpro5,&$as_estcla)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_unidades_ejecutoras
		//		   Access: public
		//			Param: as_numanacot---->numero del analisis de cotizacion
		//				   ab_viene_sep---->variable que indica si la solicitud posee sep asociadas.
		//				   aa_items---->arreglo con los items, es usado en caso de q la variable anterior venga en true
		//				   ai_totrow--->cantidad de items
		//		  return :	arreglo con la(s) unidad(es) ejecutora(s), una variable con el concepto de la colicitud de cotizacion
		//					y una variable con la unidad ejecutora a ser guardada en la cabecera de la orden de compra
		//	   Creado Por: Ing. Laura Cabré
		// 			  Fecha: 30/10/2007								Fecha Última Modificación : 11/11/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_unidades = array();
		$lb_valido=true;
		$as_concepto="";
		require_once("../shared/class_folder/class_datastore.php");
		$this->io_dsunidades = new class_datastore();

		if($ab_viene_sep)//Si la solicitud de cotizacion tiene asociada al menos una sep
		{
			$la_sep = array();
			//Se obtienen las sep a las cuales estan asociados los items que formaran parte de la orden de compra
			for($li_i=1; $li_i<=$ai_totrow; $li_i++)
			{
				$la_sep[$li_i] = $aa_items[$li_i]["numsep"];
			}
						
			$la_sep = array_unique($la_sep);//se eliminan los repetidos	
			sort($la_sep);//se reordena la matriz
			$li_j=0;
			for($li_i=0; $li_i<count($la_sep); $li_i++)
			{
				$ls_sep = $la_sep[$li_i];
				$ls_sql = "SELECT soc_solcotsep.numsol, 
				                  soc_solcotsep.codunieje,
								  soc_solcotsep.codestpro1,
								  soc_solcotsep.codestpro2,
								  soc_solcotsep.codestpro3,
								  soc_solcotsep.codestpro4,
								  soc_solcotsep.codestpro5,
								  soc_solcotsep.estcla,
								  spg_unidadadministrativa.denuniadm,soc_sol_cotizacion.consolcot
					         FROM soc_solcotsep, soc_analisicotizacion, spg_unidadadministrativa,soc_sol_cotizacion
					        WHERE soc_solcotsep.codemp = '".$this->ls_codemp."'
					          AND soc_analisicotizacion.numanacot = '".$as_numanacot."'
					          AND soc_solcotsep.numsol = '".$ls_sep."'
					          AND soc_analisicotizacion.codemp = soc_solcotsep.codemp
							  AND soc_analisicotizacion.codemp = soc_sol_cotizacion.codemp
					          AND soc_analisicotizacion.numsolcot = soc_solcotsep.numsolcot
							  AND soc_analisicotizacion.numsolcot = soc_sol_cotizacion.numsolcot
							  AND soc_solcotsep.codemp = spg_unidadadministrativa.codemp
					          AND soc_solcotsep.codunieje = spg_unidadadministrativa.coduniadm";
				$rs_data=$this->io_sql->select($ls_sql);
				if($rs_data===false)
				{
					$this->io_mensajes->message("ERROR->uf_select_unidades_ejecutoras ".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					$lb_valido=false;	
				}
				else
				{				
				  if ($row=$this->io_sql->fetch_row($rs_data))
					 {
					   $aa_unidades[$li_j]=$row;
					   $this->io_dsunidades->insertRow("codunieje",$row["codunieje"]);
					   $this->io_dsunidades->insertRow("codestpro1",$row["codestpro1"]);
					   $this->io_dsunidades->insertRow("codestpro2",$row["codestpro2"]);
					   $this->io_dsunidades->insertRow("codestpro3",$row["codestpro3"]);
					   $this->io_dsunidades->insertRow("codestpro4",$row["codestpro4"]);
					   $this->io_dsunidades->insertRow("codestpro5",$row["codestpro5"]);
					   $this->io_dsunidades->insertRow("estcla",$row["estcla"]);
					   $as_concepto =$row["consolcot"];	
					   //$as_concepto = $as_concepto."Nro. SEP:".$row["numsol"].".Unidad Ejecutora:".$row["codunieje"]." - ".$row["denuniadm"].";  ";	

					   $li_j++;
					}	
				} 
			}
			$la_campos = array("codunieje","codestpro1","codestpro2","codestpro3","codestpro4","codestpro5","estcla");
			$la_monto  = array("monto");
		    $this->io_dsunidades->group_by($la_campos,$la_monto,"monto");
			$li_totrowuni = $this->io_dsunidades->getRowCount("codunieje");
			if ($li_totrowuni==1)
			   { 
				 $as_unidad     = $this->io_dsunidades->getValue("codunieje",1);
				 $as_codestpro1 = $this->io_dsunidades->getValue("codestpro1",1);
				 $as_codestpro2 = $this->io_dsunidades->getValue("codestpro2",1);
				 $as_codestpro3 = $this->io_dsunidades->getValue("codestpro3",1);
				 $as_codestpro4 = $this->io_dsunidades->getValue("codestpro4",1);
				 $as_codestpro5 = $this->io_dsunidades->getValue("codestpro5",1);
				 $as_estcla     = $this->io_dsunidades->getValue("estcla",1);
				// $as_concepto   = "";
			   }				
			elseif($li_totrowuni>1)
			   {
				 $as_unidad     = "----------";
				 $as_codestpro1 = "-------------------------";
				 $as_codestpro2 = "-------------------------";
				 $as_codestpro3 = "-------------------------";
				 $as_codestpro4 = "-------------------------";
				 $as_codestpro5 = "-------------------------"; 
				 $as_estcla     = "-";
			   }
		    unset($this->io_dsunidades);
		}
		else//En caso de que la solicitud no este asociada a alguna sep, se busca la unidad ejecutora de la solicitud
		{
			$ls_sql = "SELECT c.coduniadm,c.codestpro1,c.codestpro2,c.codestpro3,c.codestpro4,c.codestpro5,c.estcla,c.consolcot
						 FROM soc_analisicotizacion a, soc_sol_cotizacion c
						WHERE a.codemp = '$this->ls_codemp'
						  AND a.numanacot = '$as_numanacot'
					 	  AND a.codemp = c.codemp
						  AND a.numsolcot = c.numsolcot";
			$rs_data=$this->io_sql->select($ls_sql);					
			if($rs_data===false)
			{
				$this->io_mensajes->message("ERROR->uf_select_unidades_ejecutoras 2".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$lb_valido=false;	
			}
			else
			{				
			  if ($row=$this->io_sql->fetch_row($rs_data))
				 {
				   $as_unidad     = trim($row["coduniadm"]);
				   $as_codestpro1 = trim($row["codestpro1"]);
				   $as_codestpro2 = trim($row["codestpro2"]);
				   $as_codestpro3 = trim($row["codestpro3"]);
				   $as_codestpro4 = trim($row["codestpro4"]);
				   $as_codestpro5 = trim($row["codestpro5"]);
				   $as_estcla     = $row["estcla"];
				   $as_concepto   = $row["consolcot"];
				 }																
			}			
		}	
		return $lb_valido;
		//return false;
	}//fin de uf_select_unidades_ejecutoras
	
	//---------------------------------------------------------------------------------------------------------------------------------------	
	//---------------------------------------------------------------------------------------------------------------------------------------	
	function uf_insert_enlace_sep($as_numordcom,$as_estcondat,$as_estcom,$aa_unidades,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_enlace_sep
		//		   Access: private
		//	    Arguments: as_numordcom  ---> número de la Orden de Compra
		//                 as_estcondat  ---> estatus de la orden de compra  bienes o servicios
		//				   ai_totrowbienes  ---> total de filas de bienes
		//                 as_estcom   ---> estatus de la orden de compra 
		//				   aa_seguridad  ---> arreglo con los parametros de seguridad
		//	      Returns: true si se insertaron los bienes correctamente o false en caso contrario
		//	  Description: este metodo inserta los bienes de una   orden de compra
		//	   Creado Por: Ing. Yozelin Barragan
		// Modificado por: Ing. Laura Cabre
		// Fecha Creación: 12/05/2007 								Fecha Última Modificación : 30/10/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total = count($aa_unidades);
		for($li_fila=0;$li_fila<$li_total;$li_fila++)
		{
			$ls_numsol     = $aa_unidades[$li_fila]["numsol"];
			$ls_estcla     = $aa_unidades[$li_fila]["estcla"];
			$ls_codunieje  = $aa_unidades[$li_fila]["codunieje"];
			$ls_codestpro1 = str_pad(trim($aa_unidades[$li_fila]["codestpro1"]),25,0,0);
			$ls_codestpro2 = str_pad(trim($aa_unidades[$li_fila]["codestpro2"]),25,0,0);
			$ls_codestpro3 = str_pad(trim($aa_unidades[$li_fila]["codestpro3"]),25,0,0);
			$ls_codestpro4 = str_pad(trim($aa_unidades[$li_fila]["codestpro4"]),25,0,0);
			$ls_codestpro5 = str_pad(trim($aa_unidades[$li_fila]["codestpro5"]),25,0,0);
			
			$ls_sql=" INSERT INTO soc_enlace_sep (codemp, numordcom, estcondat, numsol, estordcom, coduniadm, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, estcla)".
					"  VALUES ('".$this->ls_codemp."','".$as_numordcom."','".$as_estcondat."','".$ls_numsol."','".$as_estcom."','".$ls_codunieje."','".$ls_codestpro1."', 
					           '".$ls_codestpro2."','".$ls_codestpro3."','".$ls_codestpro4."','".$ls_codestpro5."','".$ls_estcla."')";                                                                       
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Generar Orden Analisis MÉTODO->uf_insert_enlace_sep ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			    echo $this->io_sql->message;
			}
			else
			{
				if($lb_valido)
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion ="Insertó el enlace de la sep ".$ls_numsol." a la Orden de Compra  ".$as_numordcom." tipo ".$as_estcondat." Asociado a la empresa ".$this->ls_codemp;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				}
			}
		}
		return $lb_valido;
	}// end function uf_insert_enlace_sep
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_viene_de_sep($as_numcot,$as_codpro, &$ab_viene_sep)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_viene_de_sep
		//		   Access: public
		//		  return :	variable que indica si la solicitud esta o no asociada a una sep
		//	  Description: Metodo que indica si la solicitud esta o no asociada a una sep
		//	   Creado Por: Ing. Laura Cabré
		// 			Fecha: 11/11/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ab_viene_sep = false;
		$lb_valido=true;
		if($lb_valido)
		{
			$ls_sql = "SELECT soc_solcotsep.numsol
					     FROM soc_solcotsep, soc_cotizacion
					    WHERE soc_solcotsep.codemp='".$this->ls_codemp."'
						  AND soc_cotizacion.numcot='".$as_numcot."' 
					      AND soc_cotizacion.cod_pro = '".$as_codpro."'
						  AND soc_solcotsep.codemp = soc_cotizacion.codemp
						  AND soc_solcotsep.numsolcot = soc_cotizacion.numsolcot"; 
	
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_mensajes->message("ERROR->uf_viene_de_sep".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$lb_valido=false;	
			}
			else
			{				
				if($row=$this->io_sql->fetch_row($rs_data))
				{
					$ab_viene_sep = true;
				}																
			}
		}	
		return $lb_valido;
	}//fin de uf_viene_de_sep
}
?>